<?php $this->set('hideTitle', true); ?>
<?php $this->Html->script('/js/dropzone', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/jquery.blockui', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/ajax_paging', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/floorplans_library', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/Spin', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/player/flowplayer.min', ['block' => 'scriptBottom']); ?>

<?php $this->Html->css('/css/dropzone', ['block' => true]); ?>
<?php $this->Html->css('/css/media_library', ['block' => true]); ?>
<?php $this->Html->css('/player/skin/functional', ['block' => true]); ?>



<div class="x_panel">
    <div class="x_title">
        <h2>Your Floor PLan Library</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="row">
                <div class="impressions index col-md-12">

                    <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                        <ul id="myTabs" class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#floorplansLibrary" id="library-tab" role="tab" data-toggle="tab" aria-controls="library" aria-expanded="true"><i class="fa fa-image"></i>&nbsp; Floor Plan Library</a></li>
                            <li role="presentation" class=""><a href="#uploadFloorPlans" role="tab" id="media-tab" data-toggle="tab" aria-controls="floorplans" aria-expanded="false"><i class="fa fa-upload"></i>&nbsp; Upload Floor Plans <span id="upload-total-progress"></span></a></li>
                            <li role="presentation" class="disabled"><a href="#" role="tab" id="" data-toggle="" aria-controls="" aria-expanded="false"><i class="fa fa-edit"></i> &nbsp;Edit Floor Plans</a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="floorplansLibrary" aria-labelledby="library-tab">

                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label for="">
                                <button type="button" id="select-all-images" class="select-all btn-round btn btn-sm btn-default">
                                    <i class="fa fa-check-square"></i>&nbsp;
                                    Select All
                                </button>
                                </label>
                            </div>  
                            
                            <div class="col-md-8 col-sm-8 col-xs-12">  
                                <div class="pull-right" style=" text-align: right;">

                                    <div style="display: inline-block; width: 230px; vertical-align: top;">
                                        <div class="input-group input-group-sm">
                                            <input type="text" id="floorPlansSearchTerm" class="form-control" placeholder="Search for...">
                                          <span class="input-group-btn">
                                            <button class="btn btn-primary" id="doFloorPlanSearch" type="button"><i class="fa fa-search"></i></button>
                                          </span>
                                        </div>
                                    </div>

                                    <?= $this->Form->create(null, ['url' => '/marketing/floorplans/edit_floorplans_library', 'style' => 'display: inline-block;']); ?>

                                    <input type="hidden" value="" name="ids"/>
                                    <div id="edit-container"></div>

                                    <button type="submit" class="btn-round btn btn-sm btn-primary">
                                        <i class="fa fa-pencil-square"></i>&nbsp;
                                        Edit
                                    </button>
                                    <?= $this->Form->end(); ?>
                                    <?= $this->Form->create(null, ['url' => '/marketing/floorplans/delete_floorplans_library', 'style' => 'display: inline-block;']); ?>
                                    <input type="hidden" value="" name="ids"/>

                                    <div id="del-container"></div>

                                    <button type="submit" class="btn-round btn btn btn-sm btn-default">
                                        <i class="fa fa-times-circle"></i>&nbsp;
                                        Delete
                                    </button>
                                    <?= $this->Form->end(); ?>
                                </div>
                            </div> 

                                <hr/>

                                <div class="paginate-ajax-container" style="text-align: center;">
                                    <?php $this->start('paginated_content.FloorplansLibrary'); ?>

                                    <div id="image-library-container">

                                    <?php echo $this->element('Marketing/Floorplans/library'); ?>

                                    </div>

                                    <?php $this->end(); ?>
                                    <?php echo $this->fetch('paginated_content.FloorplansLibrary'); ?>

                                </div>

                            </div>
                            <div role="tabpanel" class="dropzone tab-pane fade" id="uploadFloorplans" aria-labelledby="media-tab">

                                <div class="callout callout-info">
                                    <p><strong>Notice:</strong><br/>
                                    You may only upload vector files.
                                    </p>
                                </div>

                                <hr/>

                                <form action="/marketing/floorplans/upload_floorplans" class="dropzone" id="dropzone">
                                    <div class="dz-message">
                                        <h4>Drop files here or click to upload</h4>
                                        <br/>
                                        <span><i style="color: #aaa" class="fa fa-3x fa-upload"></i></span>
                                    </div>
                                </form>

                                <hr/>

                                <div class="media-previews dz">
                                    <p style="color: #aaa; text-align: center; margin: 15px 0; font-size: .9em;">
                                        File previews will show here
                                    </p>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </section>
    </div>
</div>