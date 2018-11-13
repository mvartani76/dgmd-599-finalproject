<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FloorplansLibrary Entity.
 *
 * @property int $id
 * @property int $customer_id
 * @property int $user_id
 * @property \App\Model\Entity\Customer $customer
 * @property string $filename
 * @property string $path
 * @property int $filesize
 * @property string $mimetype
 * @property string $filelink_cdn
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class FloorplansLibrary extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => true,
    ];
    
    public function isVideo() {
        return $this->media_type === 'VIDEO';
    }

    public function isImage() {
        return $this->media_type === 'IMAGE';
    }

    public function isVector() {
        return $this->media_type === 'VECTOR';
    }
}
