<?php $this->set('hideTitle', true); ?>
<?php $this->Html->script('/js/dropzone', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/jquery.blockui', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/ajax_paging', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/floorplans_library', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/Spin', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/crop/cropper', ['block' => 'scriptBottom']); ?>
<?php $this->Html->css('/css/crop/cropper', ['block' => true]); ?>
<?php $this->Html->css('/css/media_library', ['block' => true]); ?>

<style>
    .dim {
        color: #999;
    }
    .dim2 {
        color: #ccc;
    }
</style>

<div class="x_panel">
    <div class="x_title">
        <h2>Your Floorplans Library</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="row">
                <div class="impressions index col-md-12">

                    <?= $this->Form->create($customer, ['url' => $this->request->here . '?' . http_build_query($this->request->query), 'class' => 'submit-once']); ?>

                    <?= $this->Form->hidden('action', ['value' => 'SAVE_IMAGES']); ?>
                    <?= $this->Form->hidden('id'); ?>

                    <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                        <ul id="myTabs" class="nav nav-tabs" role="tab-list">
                            <li role="presentation" class=""><a href="/marketing/floorplans/floorplans_library" id="library-tab" role="_tab" data-toggle="_tab" aria-controls="library" aria-expanded="false"><i class="fa fa-image"></i>&nbsp; Floorplans Library</a></li>
                            <li role="presentation" class=""><a href="/marketing/floorplans/floorplans_library#uploadFloorplans" role="_tab" id="media-tab" data-toggle="_tab" aria-controls="media" aria-expanded="false"><i class="fa fa-upload"></i>&nbsp; Upload Floorplans</a></li>
                            <li role="presentation" class="active"><a href="#editFloorplans" role="_tab" id="edit-media-tab" data-toggle="_tab" aria-controls="media" aria-expanded="true"><i class="fa fa-edit"></i>&nbsp; Editing <?= sngw('Floor plan', count($customer->floorplans_library)); ?></a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="editFloorplans" aria-labelledby="edit-media-tab">

                                <?php $c = 0; ?>

                                <?php foreach($customer->floorplans_library as $image): ?>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <?php if ($image->isVideo()): ?>
                                                <img class="bb img-responsive" src="<?= $image->video_sthumb; ?>" />
                                            <?php elseif ($image->isImage()): ?>
                                                <img class="bb img-responsive" src="<?= 'https://' . \Cake\Core\Configure::read('Settings.floorplans_container') . '/' . $image->path . 'thumbnail-' . $image->filename; ?>" />
                                            <?php endif; ?>
                                            <div class="row" style="padding: 10px; font-size: .90em;">
                                                <div class="col-md-12" style="margin-bottom: 15px;">
                                                    <?php

                                                    if ($image->is_edited_image) {
                                                        $d = 'disabled';
                                                        $t = 'You cannot crop a \'cropped\' image. Please crop the source file';
                                                    } else {
                                                        $d = '';
                                                        $t = '';
                                                    }


                                                    ?>
                                                    <button <?= $d ?> title="<?= $t ?>" type="button"  data-media-id="<?= $image->id ?>" class="CreateCroppedFloorplan <?= $d ?> btn btn-block btn-primary">
                                                        <i class="fa fa-crop"></i>
                                                        &nbsp;
                                                        Crop Floor Plan
                                                    </button>
                                                </div>

                                                <div class="col-md-12" style="margin-bottom: 15px;">
                                                    <button href="<?= 'https://' . \Cake\Core\Configure::read('Settings.floorplans_container') . '/' . $image->path . $image->filename; ?>" type="button" data-media-id="<?= $image->id ?>" class="lightbox-image btn btn-block btn-default">
                                                        <i class="fa fa-image"></i>
                                                        &nbsp;
                                                        Preview
                                                    </button>
                                                </div>
                                                <div class="col-md-12" style="margin-bottom: 15px;">
                                                    <a href="/marketing/floorplans/delete_floorplans_library?ids[0]=<?= $image->id ?>" class="btn btn-block btn-danger">
                                                        <i class="fa fa-times-circle"></i>
                                                        &nbsp;
                                                        Delete Image
                                                    </a>
                                                </div>
                                                <div class="col-md-12">
                                                    <p>Image ID: <span class="pblue"><?= $image->id; ?></span></p>
                                                </div>
                                                <div class="col-md-12">
                                                    <p>Size: <span class="pblue"><?= $this->Number->toReadableSize($image->filesize); ?></span></p>
                                                </div>
                                                <div class="col-md-12">
                                                    <p>Type: <span class="pblue"><?= $image->mimetype; ?></span></p>
                                                </div>
                                                <div class="col-md-12">
                                                    <p>Dimensions: <span class="pblue"><?= $image->width . '</span><span class="dim">px</span> <span class="dim2">x </span><span class="pblue">' . $image->height . '</span><span class="dim">px</span>'; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?= $this->Form->hidden("floorplans_library.{$c}.id", ['value' => $image->id]); ?>
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <?= $this->Form->input("floorplans_library.{$c}.title", ['placeholder' => 'Image Title', 'class' => 'form-control']); ?>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <?= $this->Form->input("floorplans_library.{$c}.description", ['placeholder' => 'Floorplan Description', 'type' => 'textarea', 'class' => 'resres form-control']); ?>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-control" style="vertical-align: middle; ">
                                                        <span style="display: inline-block; vertical-align: middle; line-height: 20px; float: left;"><strong>Filename: </strong><?= $image->filename; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $this->Form->input("floorplans_library.{$c}.width_m", ['placeholder' => 'Floorplan Width (meters)', 'type' => 'text', 'class' => 'resres form-control']); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $this->Form->input("floorplans_library.{$c}.height_m", ['placeholder' => 'Floorplan Height (meters)', 'type' => 'text', 'class' => 'resres form-control']); ?>
                                                </div>
                                            </div>
                                            <br />
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $this->Form->input("floorplans_library.{$c}.num_width_divs", ['placeholder' => 'Number of Divisions', 'type' => 'text', 'class' => 'resres form-control']); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $this->Form->input("floorplans_library.{$c}.num_height_divs", ['placeholder' => 'Number of Divisions', 'type' => 'text', 'class' => 'resres form-control']); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <hr/>
                                                    <h5 style="font-weight: 600;">Cropped and Edited Images</h5>

                                                    <div id="ImagesCroppedContainer<?= $image->id ?>">
                                                    <?php if ($image->has('floorplans_library_edits') && !empty($image->floorplans_library_edits)): ?>
                                                        <?php foreach($image->floorplans_library_edits as $libraryEdit): ?>

                                                            <?php
                                                                $bsClasses = 'col-lg-2 col-md-3 col-sm-6 col-xs-12';
                                                            ?>

                                                            <div class="<?= $bsClasses ?>">
                                                                <div class="media-library-image-wrapper">
                                                                    <?php

                                                                    $extra = '';

                                                                    $url = 'https://' . \Cake\Core\Configure::read('Settings.floorplans_container') . '/' . $libraryEdit->path . 'thumbnail-' . $libraryEdit->filename;
                                                                    $furl = 'https://' . \Cake\Core\Configure::read('Settings.floorplans_container') . '/' . $libraryEdit->path . $libraryEdit->filename;

                                                                    ?>

                                                                    <label class="media-library-image">
                                                                        <div class="media-library-image-holder">
                                                                            <img class=" img-responsive" src="<?= $url; ?>" />

                                                                            <span class="typetype" title="Image Media">
                                                                                <i class="fa fa-camera"></i>
                                                                            </span>
                                                                            <div class="sub-meta-data">
                                                                                <a class="lightbox-image" data-restrict-edit="<?= (!empty($libraryEdit->parent_id) === true) ? 'true' : 'false' ?>" data-media-id="<?= $libraryEdit->id ?>" data-title="<?= $libraryEdit->title ?>" data-description="<?= $libraryEdit->description ?>" href="<?= 'https://' . \Cake\Core\Configure::read('Settings.floorplans_container') . '/' . $libraryEdit->path . $libraryEdit->filename; ?>">View Image</a>


                                                                                <span class="pull-right" style="padding-right: 10px;">
                                                                                    <a class="meta-links" title="Edit Floor Plan" href="/marketing/floorplans/editFloorplansLibrary?ids[0]=<?= $libraryEdit->id ?>">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a>
                                                                                    <a class="meta-links" title="Delete Floor Plan" href="/marketing/floorplans/deleteFloorplansLibrary?ids[0]=<?= $libraryEdit->id ?>">
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

                                                            <div id="NoEditedFloorplansFound" style="height: 80px; width: 100%; position: relative;">
                                                                <div style="text-align: center; position: absolute; top: 50%; left: 50%; margin-left: -35%; margin-top: -10px; width: 70%; height: 20px; border-bottom: 1px solid #bbb;">
                                                                    <div style="width: 160px; left: 50%; margin-left: -80px; position: absolute; height: 20px; margin-bottom: -10px; padding: 0 6px; bottom: 0; background: #fff; width: auto; text-align: center;">No Edited Floorplans Found</div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <br/>
                                    <hr/>
                                    <br/>
                                    <?php $c++; ?>
                                <?php endforeach; ?>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2 col-md-2 col-sm-3"></div>
                                        <div class="col-md-10 col-sm-10 col-xs-12">
                                            <div class="row">
                                                <div class="col-md-8" style="text-align: right;">
                                                    <?= $this->Form->button('', ['style' => 'display: none;', 'type' => 'submit']) ?>
                                                    <?= $this->Form->button(__('<i class="fa fa-save"></i> &nbsp;Save Changes'), ['class' => 'btn btn-primary']) ?>
                                                    <?= $this->Form->button(__('<i class="fa fa-times-circle"></i> &nbsp; Cancel'), ['name' => '_CANCEL', 'class' => 'btn btn-default']) ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <?= $this->Form->end(); ?>

                </div>
            </div>


        </section>
    </div>
</div>






