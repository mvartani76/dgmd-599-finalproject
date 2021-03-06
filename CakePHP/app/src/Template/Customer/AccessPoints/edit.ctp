<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AccessPoint $accessPoint
 */
?>

<?php $this->Html->script('/js/admin/filtering', ['block' => 'scriptBottom']); ?>

<?php $this->set('pageTitle', 'Edit Access Point'); ?>
    <div style="display:none" class="message-callout callout callout-success">
        <p align="left">
            <strong class="callout-title">Access Point added successfully</strong>
        </p>
        <p class="callout-text">Message goes here</p>
    </div>
<?= $this->Form->create($accessPoint) ?>
<div class="x_panel">
    <div class="x_title">
        <h2>Access Points <small>edit access point</small></h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="users add-form col-md-12">
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('mac_addr', 'MAC Address', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-3 col-offset-sm-1 col-xs-12'] ); ?>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('mac_addr', ['label' => false, 'error' => false, 'class' => 'form-control']);?>
                            <!-- Insert Error Message below input form field -->
                            <?php if ($this->Form->isFieldError('mac_addr')): ?>
                                <?= $this->Form->error('mac_addr') ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('attachFloorplan', 'Attach Floorplan?', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->control('attachFloorplan', ['label' => false, 'options' => ['No' => 'No', 'Yes' => 'Yes'], 'class' => 'form-control']); ?>

                        </div>
                    </div>
                </div>
                <br />
                <div class="row" id="floorplan-group">
                    <div class="form-group">
                        <?= $this->Form->label('heatmap.floorplan_id', 'Floorplan', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->control('heatmap.floorplan_id', ['label' => false, 'options' => $floorplans_select, 'class' => 'form-control']); ?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row" id="xpos-group">
                    <div class="form-group">
                        <?= $this->Form->label('heatmap.x', 'x position', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('heatmap.x', ['label' => false, 'class' => 'form-control']);?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row" id="ypos-group">
                    <div class="form-group">
                        <?= $this->Form->label('heatmap.y', 'y position', ['class' => 'control-label col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-offset-sm-1 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('heatmap.y', ['label' => false, 'class' => 'form-control']);?>
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
<!-- Script to show/hide floorplan selection based on select status -->
<!-- This handles changes to the select status -->
<script>
$('#attachfloorplan').on('change',function(){
    var selection = $(this).val();
    console.log(selection);
    switch(selection){
    case "Yes":
        $("#floorplan-group").show()
        $("#xpos-group").show()
        $("#ypos-group").show()
        break;
    default:
        $("#floorplan-group").hide()
        $("#xpos-group").hide()
        $("#ypos-group").hide()
    }
});
</script>
<!-- Script to show/hide floorplan selection based on select status -->
<!-- This handles initial page startup -->
<script>
    $( document ).ready(function() {
    var selection = document.getElementById('attachfloorplan').value;
    
    switch(selection){
    case "Yes":
        $("#floorplan-group").show()
        $("#xpos-group").show()
        $("#ypos-group").show()
        break;
    default:
        $("#floorplan-group").hide()
        $("#xpos-group").hide()
        $("#ypos-group").hide()
    }
});
</script>
