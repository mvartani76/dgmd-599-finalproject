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
<?= $this->Form->create($accessPoint, ['id' => 'editAccessPointForm']) ?>
<div class="x_panel">
    <div class="x_title">
        <h2>Access Points <small>edit access point</small></h2>
        <div class="clearfix"></div>
    </div>
    
    <div id="fpwidth" style="display: none"><?php echo $floorplans->width;?></div>
    <div id="fpheight" style="display: none"><?php echo $floorplans->height;?></div>
    <div class="x_content">
        <section class="content">
            <div class="users add-form col-md-12">
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('mac_addr', 'MAC Address', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
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
                        <?= $this->Form->label('attachFloorplan', 'Attach Floorplan?', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->control('attachFloorplan', ['label' => false, 'options' => ['No' => 'No', 'Yes' => 'Yes'], 'class' => 'form-control']); ?>

                        </div>
                    </div>
                </div>
                <br />
                <div class="row" id="floorplan-group">
                    <div class="form-group">
                        <?= $this->Form->label('heatmap.floorplan_id', 'Floorplan', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->control('heatmap.floorplan_id', ['label' => false, 'options' => $floorplans_select, 'class' => 'form-control']); ?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row" id="xypos-group">
                    <div class="form-group">
                        <?= $this->Form->label('heatmap.x', 'x position', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('heatmap.x', ['label' => false, 'error' => false, 'class' => 'form-control']);?>
                        </div>
                        <?= $this->Form->label('heatmap.y', 'y position', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('heatmap.y', ['label' => false, 'error' => false, 'class' => 'form-control']);?>
                        </div>
                    </div>
                    <?= $this->Form->input('width', array( 'value' => $floorplans->width, 'hiddenField' => true)); ?>
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
<script>
$('#attachfloorplan').on('change',function(){
    var selection = $(this).val();
    console.log(selection);
    switch(selection){
    case "Yes":
        $("#floorplan-group").show()
        $("#xypos-group").show()
        break;
    default:
        $("#floorplan-group").hide()
        $("#xypos-group").hide()
    }
});
</script>
<!-- Script to show/hide floorplan selection based on select status -->
<script>
    $( document ).ready(function() {
    var selection = document.getElementById('attachfloorplan').value;
    
    console.log(selection)

    switch(selection){
    case "Yes":
        $("#floorplan-group").show()
        $("#xypos-group").show()
        break;
    default:
        $("#floorplan-group").hide()
        $("#xypos-group").hide()
    }
});
</script>

    /*$('#editAccessPointForm').submit(function(e) {
           e.preventDefault();
        var width = parseInt(document.getElementById('fpwidth').innerHTML);
        var height = parseInt(document.getElementById('fpheight').innerHTML); 

        // Read in the mac_addr form value
        //var x = document.getElementById('heatmap-x').value
        var x = document.forms["editAccessPointForm"]["heatmap-x"];
        var y = document.forms["editAccessPointForm"]["heatmap-y"];

        //console.log(x.value)
        //console.log(y.value)
        var xval = x.value;
        var yval = y.value;
        console.log(xval)
        console.log(width)
        console.log(typeof xval)
        console.log(typeof width)
        // Then make sure the MAC Address is 12 characters. Form limits more than 12 so can check using less than.
        if (parseInt(x.value) > width)
        {
            $('.message-callout').switchClass('callout-success', 'callout-error');
            $('.callout-title').text('Enter X Position');
            $('.callout-text').text('There was an error editing this Access Point. The X position must be less than the floorplan width.');
            $('.message-callout').show();
            x.focus();
            return false;
        }
        // Then make sure the MAC Address is 12 characters. Form limits more than 12 so can check using less than.
        if (parseInt(y.value) > height)
        {
            $('.message-callout').switchClass('callout-success', 'callout-error');
            $('.callout-title').text('Enter Y Position');
            $('.callout-text').text('There was an error editing this Access Point. The Y position must be less than the floorplan height.');
            $('.message-callout').show();
            y.focus();
            return false;
        }

        // If it passes all form validation, then we can submit

           var jqxhr = $.post('/customer/AccessPoints/edit/40', $('#editAccessPointForm').serialize(), function(data) {
               console.log(data);
               console.log("wtf")
               var r = $.parseJSON(data);
               if (r.success == 1) {
                   $('.callout-title').text(r.message);
                   $('.callout-text').text('The Access Point was created and added to the database and assigned to this Location with the AP Zone name you chose');
                   $('.message-callout').show();
                   $('#editAccessPointForm').trigger('submit');
               } else {
                   $('.message-callout').switchClass('callout-success', 'callout-error')
                   $('.callout-title').text(r.message);
                   $('.callout-text').text('There was an error inserting this Access Point, this could be for several reasons, please send a screenshot of this message to support');
               }
           });

});*/

