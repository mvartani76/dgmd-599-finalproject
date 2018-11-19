<?php

    if (!empty($modal)) {
        if ($modal) {
            $hideCB = true;
        } else {
            $hideCB = false;
        }
    } else {
        $hideCB = false;
    }

?>

    <div class="row">
        <?php if (!$library->isEmpty()): ?>
        <?php foreach($library as $libitem): ?>

            <?php

                $pass = $this->request->pass;

                $f = array_search('refreshed', $pass);
                if ($f !== false) {
                    $pass[$f] = null;
                }


                $this->Paginator->setAjax();
                $this->Paginator->options(
                    [
                        'url' => array_merge($this->request->pass, ['ajax' => true, 'mdl' => 'FloorplansLibrary'], $this->request->query)
                    ]
                );
                
            ?>


                <?php

                if ($hideCB) {
                    $bsClasses = 'col-lg-3 col-md-3 col-sm-6 col-xs-12';
                } else {
                    $bsClasses = 'col-lg-2 col-md-3 col-sm-6 col-xs-12';
                }


                ?>

            <div class="<?= $bsClasses ?>">
                <div class="media-library-image-wrapper" rel="tool-tip" data-placement="top" title="<?= $libitem->width ?> x <?= $libitem->height ?> &ndash; <?= $this->Number->toReadableSize($libitem->filesize) ?>" >
                    <?php

                    $extra = '';

                    if ($libitem->media_type === 'VIDEO') {
                        if ($libitem->media_status === 'PROCESSING') {
                            $url = '/img/public/site/processing_video.gif';
                            $furl = '/img/public/site/processing_video.gif';
                        } elseif ($libitem->media_status === 'COMPLETE') {

                            $extra = '<div style="background: url(/img/public/site/play_icon.png) no-repeat center transparent; top: 0; z-index: 75; position: absolute; height: 100%; width: 100%;"></div>';

                            $url = $libitem->video_sthumb;
                            $furl = $libitem->video_thumb;
                        }
                    } elseif ($libitem->media_type === 'IMAGE') {
                        $url = 'https://' . \Cake\Core\Configure::read('Settings.floorplans_container') . '/' . $libitem->path . 'thumbnail-' . $libitem->filename;
                        $furl = 'https://' . \Cake\Core\Configure::read('Settings.floorplans_container') . '/' . $libitem->path . $libitem->filename;
                    }

                    ?>

                    <label class="media-library-image">
                        <div class="media-library-image-holder">
                            <img  class=" img-responsive" src="<?= $url; ?>" />

                            <?= $extra ?>

                            <?php if (!$hideCB): ?>
                                <input type="checkbox" value="<?=$libitem->id; ?>" class="cb-select"/>
                            <?php else: ?>
                                <input type="radio"  data-width="<?= $libitem->width ?>" data-height="<?= $libitem->height ?>" data-size="<?= $this->Number->toReadableSize($libitem->filesize) ?>" data-tpath="<?= $url ?>" data-path="<?= $furl ?>" data-title="<?= $libitem->title ?>" data-media-type="<?= $libitem->media_type ?>" data-filename="<?= $libitem->filename ?>" data-media-id="<?= $libitem->id ?>" name="SelectedFloorplanFile" value="<?=$libitem->id; ?>" class="cb-select"/>
                            <?php endif; ?>

                            <?php if ($libitem->media_type === 'VIDEO'): ?>
                                <span class="typetype" title="Video Media">
                                    <i class="fa fa-video-camera"></i>
                                </span>
                                <?php $meta = 'Video'; ?>
                                <?php $class = 'preview-selected-media-file'; ?>
                                <?php $mediaLink = '#'; ?>
                            <?php elseif ($libitem->media_type === 'IMAGE'): ?>
                                <span class="typetype" title="Image Media">
                                    <i class="fa fa-camera"></i>
                                </span>
                                <?php $class = 'lightbox-image'; ?>
                                <?php $meta = 'Image'; ?>
                                <?php $mediaLink = 'https://' . \Cake\Core\Configure::read('Settings.floorplans_container') . '/' . $libitem->path . $libitem->filename; ?>
                            <?php endif; ?>

                            <div class="sub-meta-data">
                                <!-- Pass in data-hostname so I can match where the request is coming from. -->
                                <a class="<?= $class ?>" data-restrict-edit="<?= (!empty($libitem->parent_id) === true) ? 'true' : 'false' ?>" data-media-id="<?= $libitem->id ?>" data-title="<?= $libitem->title ?>" data-description="<?= $libitem->description ?>" data-hostname ="<?= \Cake\Core\Configure::read('Settings.floorplans_container') ?>" href="<?= $mediaLink ?>">
                                    View <?= $meta ?>
                                </a>
                                <span class="pull-right" style="padding-right: 10px;">
                                    <a class="meta-links" title="Edit Image" href="/marketing/floorplans/editFloorplansLibrary?ids[0]=<?= $libitem->id ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a class="meta-links" title="Delete Image" href="/marketing/floorplans/deleteFloorplansLibrary?ids[0]=<?= $libitem->id ?>">
                                        <i class="fa fa-times-circle"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        <?php endforeach; ?>
        <?php else: ?>
            <div class="col-xs-12">
                <div style="margin-bottom: 50px; height: 80px; width: 100%; position: relative;">
                    <div style="text-align: center; position: absolute; top: 50%; left: 50%; margin-left: -25%; margin-top: -10px; width: 50%; height: 20px; border-bottom: 1px solid #bbb;">
                        <div style="width: 100px; left: 50%; margin-left: -50px; position: absolute; height: 20px; margin-bottom: -10px; padding: 0 6px; bottom: 0; background: #fff; width: auto; text-align: center;">No Floorplans Found<br/><br/><a class="btn btn-xs btn-default" target="_blank" href="/marketing/floorplans/floorplans_library#uploadFloorplans"><i class="fa fa-plus-circle"></i> &nbsp;Add Floorplans</a></div>
                    </div>
                </div>
            </div>



        <?php endif; ?>
    </div>
    <?= $this->element('paginator') ?>

