<?php
namespace App\Model\Table;

use App\Lib\PathProcessor\FileProcessor;
use App\Lib\Utils;
use App\Lib\Zencoder\Zencoder;
use App\Model\Entity\FloorplansLibrary;
use App\Validation\Providers\VideoImage;
use Aws\S3\S3Client;
use Cake\Controller\Component\AuthComponent;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\ORM\D2goTable as Table;
use Cake\Validation\Validator;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Tools\Controller\Component\AuthUserComponent;


/**
 * FloorplansLibrary Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Customers
 */



class FloorplansLibraryTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('floorplans_library');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');


        $this->hasMany('FloorplansLibraryEdits', [
            'className' => 'FloorplansLibrary',
            'foreignKey' => 'parent_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
            'saveStrategy' => 'append'
        ]);

        $_s3 = new S3Client(
            [
                'version' => 'latest',
                'region'  => 'us-west-2',
                'credentials' => [
                    'key' => Configure::read('Settings.aws_s3_key'),
                    'secret' => Configure::read('Settings.aws_s3_secret')
                ]
            ]
        );

        $adapter = new AwsS3Adapter($_s3, Configure::read('Settings.floorplans_container'));

        $this->addBehavior('CreatorModifier.CreatorModifier', [
            'events' => [
                'Model.beforeSave' => [
                    'user_id' => 'new'
                ]
            ],
            'sessionUserIdKey' => 'Auth.User.id',
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'filename' => [

                'filesystem' => [
                    'adapter' => $adapter
                ],

                'pathProcessor' => 'App\Lib\PathProcessor\FileProcessor',
                'writer' => 'App\Lib\Writer\HookedWriter',

                'transformer' => function(RepositoryInterface $table, EntityInterface $entity, $data, $field, $settings) {
                    $patch = [];

                    $vi  = new VideoImage($data);

                    if ($vi->isImage($data)) {
                        $patch['media_type'] = 'IMAGE';
                    } elseif ($vi->isVideo($data)) {
                        $patch['media_type'] = 'VIDEO';
                    } elseif ($vi->isVector($data)) {
                        $patch['media_type'] = 'VECTOR';
                    }

                    $md5 = $entity->md5;
                    $r   = $entity->r;

                    $extension  = pathinfo($data['name'], PATHINFO_EXTENSION);
                    $name       = $entity->user_id . '.' . $r . '.' . $md5 . '.' . $extension;

                    $data['name'] = $name;

                    $tmp        = tempnam(sys_get_temp_dir(), 'upload_') . '.' . $name;

                    if ($patch['media_type'] === 'IMAGE') {
                        $thumb = new \Imagick();
                        $image = new \Imagick();

                        $thumb->readImage($data['tmp_name']);
                        $image->readImage($data['tmp_name']);

                        $h = $image->getImageHeight();
                        $w = $image->getImageWidth();

                        $r = ($h / $w);

                        $newHeight  = 1280;
                        $newWidth   = $newHeight / $r;

                        $image->scaleImage($newWidth, $newHeight);


                        $thumb->cropThumbnailImage(300, 180);

                        $entity->width  = $newWidth;
                        $entity->height = $newHeight;

                        $image->setImageCompressionQuality(80);
                        $thumb->setImageCompressionQuality(80);

                        $thumb->writeImage($tmp);
                        $image->writeImage($data['tmp_name']);

                        $patch['filesize'] = filesize($data['tmp_name']);



                        $this->patchEntity($entity, $patch, ['validate' => false]);

                        return [
                            $data['tmp_name']   => $data['name'],
                            $tmp                => 'thumbnail-' . $data['name']
                        ];

                    } elseif ($patch['media_type'] === 'VIDEO') {

                        $this->patchEntity($entity, $patch, ['validate' => false]);

                        return [
                            $data['tmp_name']   => $data['name']
                        ];

                    }
                },

                'fields' => [
                    'dir' => 'path',
                    'type' => 'mimetype'
                ],
                'path' => 'uploads/{model}/{user_id}/{year}/{month}/{day}/{time}/',

            ]
        ]);

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);
    }

    public function afterSave(Event $event, EntityInterface $entity, \ArrayObject $options) {
        if ($entity->doEncoding === true) {
            /*
             * Do Zencoding
             */

            $Zencoder = new Zencoder();
            $Zencoder->addJob($entity);
        }
    }


    public function bindForEdits($dmid, $i) {

        $this->removeBehavior('Upload');

        $libraryItem = $this->find()
            ->where(['FloorplansLibrary.id' => $dmid])
            ->contain(
                [
                    'FloorplansLibraryEdits'
                ]
            )
            ->first();

        $_s3 = new S3Client(
            [
                'version' => 'latest',
                'region'  => 'us-west-2',
                'credentials' => [
                    'key' => Configure::read('Settings.aws_s3_key'),
                    'secret' => Configure::read('Settings.aws_s3_secret')
                ]
            ]
        );

        $adapter = new AwsS3Adapter($_s3, Configure::read('Settings.floorplans_container'));

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'filename' => [
                'filesystem' => [
                    'adapter' => $adapter
                ],
                'pathProcessor' => 'App\Lib\PathProcessor\EditedFileProcessor',
                'writer' => 'App\Lib\Writer\HookedWriter',

                'transformer' => function(RepositoryInterface $table, EntityInterface $entity, $data, $field, $settings) use ($i, $libraryItem) {

                    $md5    = $entity->md5;
                    $r      = $entity->r;
                    $rand   = $entity->rand;

                    $extension  = pathinfo($data['name'], PATHINFO_EXTENSION);
                    $name       = $this->Auth->id('id') . '.' . $r . '.' . $md5 . '_cropped_' . $rand . '.' . $extension;

                    $data['name'] = $name;

                    $tmp        = tempnam(sys_get_temp_dir(), 'upload_') . '.' . $name;


                    $image = new \Imagick();
                    $image->readImage($data['tmp_name']);

                    // first rotate the image
                    if ($i['r']) {
                        $image->rotateImage(new \ImagickPixel('#00000000'), $i['r']);
                    }

                    $image->cropImage($i['w'],$i['h'],$i['x'],$i['y']);

                    if ($i['sx'] == -1) {
                        $image->flopImage();
                    }

                    if ($i['sy'] == -1) {
                        $image->flipImage();
                    }



                    $image->writeImage($data['tmp_name']);

                    $thumb = new \Imagick();
                    $thumb->readImage($data['tmp_name']);

                    $thumb->thumbnailImage(300, 180, true);
                    $thumb->setImageCompressionQuality(80);

                    $canvas = new \Imagick();
                    $canvas->newImage(300,180,'white','jpg');
                    $geo    = $thumb->getImageGeometry();
                    $x = (300 - $geo['width'])/2;
                    $y = (180 - $geo['height'])/2;

                    $canvas->compositeImage($thumb, \IMAGICK::COMPOSITE_OVER, $x, $y);
                    $canvas->writeImage($tmp);

                    $entity->width      = $i['w'];
                    $entity->height     = $i['h'];
                    $entity->filesize   = filesize($data['tmp_name']);

                    $patch = [
                        'media_status'  => 'EDITED',
                        'is_edited_image'  => true,
                        'filesize'      => $entity->filesize,
                        'parent_id'     => $libraryItem->id
                    ];

                    $this->patchEntity($entity, $patch, ['validate' => false]);

                    return [
                        $data['tmp_name']   => $data['name'],
                        $tmp                => 'thumbnail-' . $data['name']
                    ];

                },

                'fields' => [
                    'dir' => 'path',
                    'type' => 'mimetype'
                ],
                'path' => 'uploads/{model}/'.$this->Auth->user('id').'/{year}/{month}/{day}/{time}/',

            ]
        ]);


    }



    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('filename', 'create')
            ->notEmpty('filename');

        $validator
            ->allowEmpty('path');

        $validator
            ->integer('filesize')
            ->allowEmpty('filesize');

        $VideoImage = new VideoImage();

        $validator->provider('VideoImage', $VideoImage);

        $validator->add('filename', 'fileUnderPhpSizeLimit', [
            'rule' => 'isUnderPhpSizeLimit',
            'message' => 'This file is too large',
            'provider' => 'VideoImage'
        ]);

        $validator->add('filename', 'fileUnderFormSizeLimit', [
            'rule' => 'isUnderFormSizeLimit',
            'message' => 'This file is too large',
            'provider' => 'VideoImage'
        ]);

        $validator->add('filename', 'fileCompletedUpload', [
            'rule' => 'isCompletedUpload',
            'message' => 'This file could not be uploaded completely',
            'provider' => 'VideoImage'
        ]);

        $validator->add('filename', 'fileFileUpload', [
            'rule' => 'isFileUpload',
            'message' => 'Uploaded file not found',
            'provider' => 'VideoImage'
        ]);

        $validator->add('filename', 'fileSuccessfulWrite', [
            'rule' => 'isSuccessfulWrite',
            'message' => 'There was an error writing your uploaded file, please try again.',
            'provider' => 'VideoImage'
        ]);

        $validator->add('filename', 'fileBelowMaxSize', [
            'rule' => ['isBelowMaxSize', 1610612736],
            'message' => 'This file is too large, please upload a file smaller than 1.5 GB',
            'provider' => 'VideoImage'
        ]);

        $validator->add('filename', 'fileAboveMinWidth', [
            'rule' => ['isAboveMinWidth', 200],
            'message' => 'This floor plan should at least be 200px wide',
            'provider' => 'VideoImage'
        ]);


        $validator->add('filename', 'isVideoImage', [
            'rule' => 'isVideoOrImage',
            'provider' => 'VideoImage',
            'message' => 'Please upload -only- Video or Image files. If you uploaded a file with an image or video extension, please verify it is a valid file and not corrupted.'
        ]);

        $validator
            ->allowEmpty('mimetype');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        return $rules;
    }
}
