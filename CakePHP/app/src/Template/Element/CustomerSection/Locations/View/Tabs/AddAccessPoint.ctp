<div role="tabpanel" class="tab-pane fade in" id="tab_content8" aria-labelledby="home-tab">
    <div style="display:none" class="message-callout callout callout-success">
        <p align="left">
            <strong class="callout-title">Access Point added successfully</strong>
        </p>
        <p class="callout-text">Message goes here</p>
    </div>

    <?= $this->Form->create($accessPoint, ['id' => 'addAccessPointForm', 'url' => ['action' => 'addAccessPoint']]);?> 
    <div class="row" style="margin-top:30px!important">

        <div class="form-group addAccessPoint">
            <?= $this->Form->label('mac_addr', 'MAC Address', ['class' => 'control-label col-md-3 col-sm-3 col-xs-6'] ); ?>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <?= $this->Form->input('mac_addr', ['label' => false, 'class' => 'form-control col-md-6 col-xs-12']);?>
            </div>
            <?= $this->Form->label('Apzones.placement', 'Placement Description', ['class' => 'control-label col-md-3 col-sm-3 col-xs-6'] ); ?>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <?= $this->Form->input('Apzones.placement', ['label' => false, 'class' => 'form-control col-md-6 col-xs-12']);?>
            </div>
            <?= $this->Form->hidden('reassign_accesspoint', ['id' => 'reassign_accesspoint', 'value' => 0]);?>
            <?= $this->Form->hidden('location_id', ['value' => $location->id]);?>
        </div>

    </div>
    <br />
    <div class="row">
        <div class="form-group">
            <div class="col-md-3 col-md-3 col-sm-3"></div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <?= $this->Form->button('', ['style' => 'display: none;', 'type' => 'submit']) ?>
                <?= $this->Form->button(__('<i class="fa fa-times-circle"></i> &nbsp; Cancel'), ['name' => '_CANCEL', 'class' => 'pull-right btn btn-default']) ?>
                <?= $this->Form->button(__('<i class="fa fa-plus-circle"></i> &nbsp;Create Access Point'), ['class' => 'pull-right btn btn-primary']) ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
    <?= $this->Form->end();?>
</div>

<script>
    $(document).ready(function() {
       $('#addAccessPointForm').submit(function(e) {
           e.preventDefault();
           var jqxhr = $.post('/customer/Locations/addAccessPoint', $('#addAccessPointForm').serialize(), function(data) {
               console.log(data);
               var r = $.parseJSON(data);
               if (r.success == 1) {
                   $('.callout-title').text(r.message);
                   $('.callout-text').text('The Access Point was created and added to the database and assigned to this Location with the AP Zone name you chose');
                   $('.message-callout').show();
                   $('#addAccessPointForm').trigger('reset');
               } else if (r.reassign == 'location') {
                   ans = confirm('This access point already exists attached to the following location: \n' + r.location + '\r\n' + 'Do you wish to re-assign it to this location?');
                   //Set this to signal access point should be reassigned.
                   if (ans) {
                       //Set this to signal access point should be reassigned.
                       $('#reassign_accesspoint').val(1);
                       $('#addAccessPointForm').trigger('submit');
                   }
               } else if (r.reassign == 'new') {
                   ans = confirm('This access point already exists but is not attached to a location. \n' + 'Do you wish to assign it to this location?');

                   if (ans) {
                       //Set this to signal access point should be reassigned.
                       $('#reassign_accesspoint').val(1);
                       $('#addAccessPointForm').trigger('submit');
                   }

               } else {
                   $('.message-callout').switchClass('callout-success', 'callout-error')
                   $('.callout-title').text(r.message);
                   $('.callout-text').text('There was an error inserting this Access Point, this could be for several reasons, please send a screenshot of this message to support');

               }
           });

       });
    });
</script>