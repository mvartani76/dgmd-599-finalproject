<?php $this->set('hideTitle', true); ?>
<?php $this->Html->script('/js/dropzone', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/jquery.blockui', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/ajax_paging', ['block' => 'scriptBottom']); ?>

<style>
    .media-library-image-wrapper {
        position: relative;

    }

    .media-library-image-wrapper .media-library-image .sub-meta-data a {
        color: #fff;
        font-size: .9em;
        margin-left: 15px;
    }

    .media-library-image-wrapper .media-library-image .sub-meta-data {
        height: 25px;
        background-color: rgba(0,0,0,.65);
        color: #fff;
        margin-bottom: -25px;
        padding: 0px;
        line-height: 25px;
        vertical-align: middle;
        position: absolute;
        opacity: 0;
        bottom: 0;
        left: 0;
        width: 100%;
    }

    .media-library-image.image-selected {
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(102, 175, 233, 0.6);
        border-color: #66afe9 !important;
    }

    .media-library-image-wrapper .media-library-image div.media-library-image-holder {
        position: relative;
        overflow: hidden;
    }

    .media-library-image-wrapper .media-library-image img {
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }



    .media-library-image-wrapper .media-library-image {
        position: relative;
        cursor: pointer;
        overflow: hidden;
        top: 0;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        left: 0;
        padding: 3px;
        border: 1px solid #eee;
    }

    .media-library-image-wrapper .cb-select {
        position: absolute;
        z-index: 100;
        top: 5px;
        left: 9px;
    }

    img.bb {
        padding: 3px;
        border: 1px solid #ccc;
    }

</style>

<div class="x_panel">
    <div class="x_title">
        <h2>Your Floor Plan Library</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="row">
                <div class="impressions index col-md-12">

                    <?= $this->Form->create($customer, ['url' => $this->request->here . '?' . http_build_query($this->request->query), 'class' => 'submit-once']); ?>

                    <?= $this->Form->hidden('action', ['value' => 'DELETE_IMAGES']); ?>
                    <?= $this->Form->hidden('id'); ?>

                    <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                        <ul id="myTabs" class="nav nav-tabs" role="tab-list">
                            <li role="presentation" class=""><a href="/marketing/floorplans/floorplans_library" id="library-tab" role="_tab" data-toggle="_tab" aria-controls="library" aria-expanded="false"><i class="fa fa-image"></i>&nbsp; Floor Plans Library</a></li>
                            <li role="presentation" class=""><a href="/marketing/floorplans/floorplans_library#uploadFloorplans" role="_tab" id="media-tab" data-toggle="_tab" aria-controls="media" aria-expanded="false"><i class="fa fa-upload"></i>&nbsp; Upload Floor Plans</a></li>
                            <li role="presentation" class="active"><a href="#editMedia" role="_tab" id="delete-media-tab" data-toggle="_tab" aria-controls="media" aria-expanded="true"><i class="fa fa-times-circle"></i>&nbsp; Deleting <?= count($customer->floorplans_library) ?> Floorplans</a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="deleteMedia" aria-labelledby="delete-media-tab">

                                <?php $c = 0; ?>

                                <?php foreach($customer->floorplans_library as $image): ?>

                                    <div class="x_content">
                                        <ul class="list-unstyled msg_list">
                                            <li>
                                                <a style="text-decoration: none; width: 100%; display: block;">
                                                    <span class="image">
                                                        <?php if ($image->media_type === 'IMAGE'): ?>
                                                            <img style="width: 200px;" alt="img" class="bb" src="<?= 'https://' . \Cake\Core\Configure::read('Settings.floorplans_container') . '/' . $image->path . 'thumbnail-' . $image->filename; ?>" />
                                                        <?php else: ?>
                                                            <img style="width: 200px;" alt="img" class="bb" src="<?= $image->video_thumb; ?>" />
                                                        <?php endif; ?>
                                                    </span>
                                                    <span>
                                                        <span style="color: #333; font-size: 1.2em; font-weight: 500;"><?= $image->title; ?> (Image ID #<?= $image->id; ?>)</span>
                                                        <span class="time badge bg-dark-red" style="text-align: right; margin-right: 15px; color: #fff; font-weight: bold;"><i class="fa fa-times-circle"></i> &nbsp;Will be Deleted</span>
                                                    </span>
                                                    <span class="message" style="margin-top: 10px; color: #666;">
                                                        <?= $image->description; ?><br/>
                                                        <em><?= $image->filename; ?></em>
                                                    </span>
                                                    <span style="left: 225px; width: 50%; color: #333; position: absolute; bottom: 13px;" class="metadata form-control">


                                                                <span style="margin-bottom: 0;"><strong>Size: </strong><span class="pblue"><?= $this->Number->toReadableSize($image->filesize); ?></span></span>
                                                        &nbsp;&nbsp;
                                                                <span style="margin-bottom: 0;"><strong>Type: </strong><span class="pblue"><?= $image->mimetype; ?></span></span>
                                                        &nbsp;&nbsp;
                                                                <span style="margin-bottom: 0;"><strong>Dimensions: </strong><span class="pblue"><?= $image->width . 'x' . $image->height . ''; ?></span></span>


                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <?php $c++; ?>
                                <?php endforeach; ?>

                                <hr style="float: left; width: 100%;" />

                                <div style="float: left; width: 100%;" class="form-group">

                                    <div class="callout callout-danger">
                                        <p><strong><i class="fa fa-exclamation-triangle"></i> &nbsp;Warning</strong><br/>
                                            Once you delete these Floorplans, it is irreversible. You will be required to upload them again.
                                        </p>
                                    </div>


                                    <div class="col-md-3 col-md-3 col-sm-3"></div>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <?= $this->Form->button('', ['style' => 'display: none;', 'type' => 'submit']) ?>
                                        
                                        <!-- Using a link instead of cancel button as cancel button was redirecting back to index -->
                                        <a href="/marketing/floorplans/floorplans_library" class="pull-right btn btn-default"><i class="fa fa-times-circle"></i> &nbsp; Cancel</a> 

                                        <?= $this->Form->button(__('<i class="fa fa-eraser"></i> &nbsp;Delete Floorplans'), ['class' => 'pull-right btn btn-danger']) ?>
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
