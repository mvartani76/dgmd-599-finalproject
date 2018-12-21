<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AccessPoint $accessPoint
 */
?>

<?php $this->Html->script('/js/admin/filtering', ['block' => 'scriptBottom']); ?>

<?php $this->set('pageTitle', 'Edit Heatmap'); ?>
    <div style="display:none" class="message-callout callout callout-success">
        <p align="left">
            <strong class="callout-title">Heatmap added successfully</strong>
        </p>
        <p class="callout-text">Message goes here</p>
    </div>
<?= $this->Form->create($heatmap) ?>
<div class="x_panel">
    <div class="x_title">
        <h2>Heatmaps <small>edit heatmap</small></h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="users add-form col-md-12">
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('accesspoint_id', 'Access Point ID', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-3 col-offset-sm-1 col-xs-12'] ); ?>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->control('accesspoint_id', ['label' => false, 'options' => $accessPoints, 'class' => 'form-control']);?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('floorplan_id', 'Floorplan ID', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->control('floorplan_id', ['label' => false, 'options' => $floorplans, 'class' => 'form-control']); ?>

                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('x', 'x position', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('x', ['label' => false, 'class' => 'form-control']);?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('y', 'y position', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('y', ['label' => false, 'class' => 'form-control']);?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('width_m', 'Width (m)', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('width_m', ['label' => false, 'class' => 'form-control']);?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('height_m', 'Height (m)', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('height_m', ['label' => false, 'class' => 'form-control']);?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('num_width_divs', 'Number of Width Divs', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('num_width_divs', ['label' => false, 'class' => 'form-control']);?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('num_height_divs', 'Number of Height Divs', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('num_height_divs', ['label' => false, 'class' => 'form-control']);?>
                        </div>
                    </div>
                </div>
                <br />                
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3 col-md-3 col-sm-3"></div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->button('', ['style' => 'display: none;', 'type' => 'submit']) ?>
                            <?= $this->Form->button(__('<i class="fa fa-times-circle"></i> &nbsp; Cancel'), ['name' => '_CANCEL', 'class' => 'pull-right btn btn-default']) ?>
                            <?= $this->Form->button(__('<i class="fa fa-plus-circle"></i> &nbsp;Submit'), ['class' => 'pull-right btn btn-primary']) ?>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </section>
    </div>
</div>
