<?php $this->set('pageTitle', $location->retailer->name . ' Location ID #' . $location->id); ?>
<?php $this->Html->script('/js/jquery.animate.number.min', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/filtering', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/csv_template', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/bootstrap3-wysihtml5.all.min', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/notes', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/jquery.blockui', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/ajax_paging', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/Spin', ['block' => 'scriptBottom']); ?>
<?php $this->Html->css('/css/bootstrap3-wysihtml5', ['block' => true]); ?>
<style>
    table.table tr.fields-info th {
    }
    table.table tr.fields-info th,
    table.table tr.fields-info td {
        min-width: 190px !important;
        text-align: center;
        white-space: nowrap !important;
    }
</style>
<script>

    var STATS_URL = window.location.origin + window.location.pathname;
    var LOCATION_ID = <?= $location->id ?>;
    //IN UTC
    var START_DATE  = '<?=$periodStartRaw?>';
    var END_DATE  = '<?=$periodEndRaw; ?>';

    $(document).ready(function() {

        var options = {
            animation: true,
            placement: 'top',
            html: true,
            container: 'body'
        };

        $('tr.fields-info th').popover(options);
        var tabid = window.location.hash.substr(1);

        if (tabid.length) {
            $('#' + tabid).tab('show');
        }

        $('#ZonesCount').click(function() {
            $('#notes-tab4').tab('show');
        });
        $('#ImpressionsCount').click(function() {
            $('#notes-tab3').tab('show');
        });

        if(window.location.hash) {
            var hash = window.location.hash;
            $(hash).click();
        } else {
            $('#notes-tab5').click();
        }

        $('#notes-tab3').click(function(e) {
            e.preventDefault();
            var url = $(this).attr("data-url");

            // ajax load from data-url
            $('#tab_content3').load(url,function(result){
                $('#notes-tab3').tab('show');
            });

        });

        // $('#notes-tab6').click(function(e) {
        $(document).on('click','#notes-tab6', function (e) {
            e.preventDefault();
            var url = $(this).attr("data-url");
            // ajax load from data-url
            $('#tab_content6').load(url,function(result){
                $('#notes-tab6').tab('show');
            });

        });
    });

</script>
<script>
    <?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>

    var commaStep = $.animateNumber.numberStepFactories.separator(',');

    $('#ZonesCount').animateNumber({numberStep: commaStep, number: <?= $location->zones_count ?>});
    $('#ImpressionsCount').animateNumber({numberStep: commaStep, number: <?= $WeeksImpressionCount ?>});
    $('#DevicesCount').animateNumber({numberStep: commaStep, number: <?= $LocationDevicesCount ?>});

    $(function() {
        $('#notes-tab5').click(function() {
            setTimeout(
                function() { ivory_google_map_init(); },
                500
            );
        });
    });

    <?php $this->Html->scriptEnd(); ?>
</script>

<?= $this->element('Header/Common/filter_bar'); ?>
<?= $this->element('Header/Common/stats_bar',['type'=>'locations']); ?>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-bars"></i> Graphs, Actions and MetaData</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li ><a class="add-new-note" data-note-model="Retailers" data-model-id="<?= $location->id; ?>" href="#">Add Note</a></li>
                        <li ><a class="edit-location" href="/customer/locations/edit/<?= $location->id ?>">Edit Location</a></li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs" role="tablist">
                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="notes-tab2" data-toggle="tab" aria-expanded="false">Location Notes&nbsp;&nbsp; <span title="Add Notes" class="add-new-note" data-note-model="Locations" data-model-id="<?= $location->id ?>" style="cursor: pointer; /*float: right;*/"><i class="fa fa-plus-circle"></i></span></a></li>
                    <li role="presentation" class=""><a href="#tab_content3" role="tab" id="notes-tab3" data-toggle="tab" aria-expanded="false" data-url="/customer/locations/ajaxImpressions/<?= $location->id;?>">All Beacon Impressions</a></li>
                    <li role="presentation" class=""><a href="#tab_content6" role="tab" id="notes-tab6" data-toggle="tab" aria-expanded="false" data-url="/customer/locations/ajaxImpressions/<?= $location->id;?>/geofences">All Geofence Impressions</a></li>
                    <li role="presentation" class=""><a href="#tab_content4" role="tab" id="notes-tab4" data-toggle="tab" aria-expanded="false">Zones</a></li>
                    <li role="presentation" class=""><a href="#tab_content5" role="tab" id="notes-tab5" data-toggle="tab" aria-expanded="false">Location Information</a></li>
                    <li role="presentation" class=""><a href="#tab_content7" role="tab" id="notes-tab7" data-toggle="tab" aria-expanded="false">Add / Assign a beacon to this location</a></li>
                    <li role="presentation" class=""><a href="#tab_content8" role="tab" id="notes-tab8" data-toggle="tab" aria-expanded="false">Add / Assign an access point to this location</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <?= $this->element('CustomerSection/Locations/View/Tabs/Notes'); ?>
                    <?= $this->element('CustomerSection/Locations/View/Tabs/Zones'); ?>
                    <!-- impressions tab loaded by AJAX -->
                    <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                        <ul id="NotesResults" class="messages">
                            <li class="no-data">
                                <p style="color: #aaa !important; text-align: center;"><i class="fa fa-gear fa-spin" style="font-size:20px; margin-right: 10px;"></i>&nbsp;Please wait while impressions are loading<br/> (May take a while)<br/></p>
                            </li>
                        </ul>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content6" aria-labelledby="profile-tab">
                        <ul  class="messages">
                            <li class="no-data">
                                <p style="color: #aaa !important; text-align: center;"><i class="fa fa-gear fa-spin" style="font-size:20px; margin-right: 10px;"></i>&nbsp;Please wait while impressions are loading<br/> (May take a while)<br/></p>
                            </li>
                        </ul>
                    </div>
                    <?= $this->element('CustomerSection/Locations/View/Tabs/LocationInformation'); ?>
                    <?= $this->element('CustomerSection/Locations/View/Tabs/AddBeacon'); ?>
                    <?= $this->element('CustomerSection/Locations/View/Tabs/AddAccessPoint'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="">
    <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="x_panel tile fixed_height_320">
            <div class="x_title">
                <h2 style="font-weight: bold;">Contact Information</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php if (!empty($location->account_representative)): ?>
                    <h4>Contact Information</h4>
                    <address style="float: left;">
                        <i class="fa fa-fw fa-building"></i>&nbsp;
                        <?= $location->billing_street1 ?><br/>
                        <?php if (!empty($location->billing_street2)): ?>
                            <?= $location->billing_street2 ?><br/>
                        <?php endif; ?>
                        <i class="fa fa-fw"></i>&nbsp;
                        <?= $location->billing_city ?>,
                        <?= $location->region->subdiv_alt ?>
                        <?= $location->billing_postal_code ?>
                    </address>

                    <div class="clearfix"></div>

                    <p><i class="fa fa-fw fa-phone-square"></i>&nbsp; <?= $location->main_phone ?></p>

                <?php else: ?>

                    <h4>No address saved for this Retailer</h4>

                    <p align="center" style="margin-top: 40px;">
                        <a href="/customer/retailers/edit/<?= $location->id; ?>" class="btn btn-primary btn-lg"><i class="fa fa-phone-square"></i>&nbsp; Add Address/Contact Information</a>
                    </p>
                <?php endif ; ?>
                <br/>
                <h4>Industry</h4>
                <p><i class="fa fa-fw fa-industry"></i>&nbsp; <?= $location->industry->name ?? "" ?></p>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
    <div class="x_panel tile fixed_height_320">
        <div class="x_title">
            <h2>Account Representative</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <?php if (!empty($location->account_representative)): ?>
                <p><?= $location->account_representative->profile->first_name; ?> <?= $location->account_representative->profile->last_name; ?></p>

                <address style="float: left;">
                    <i class="fa fa-fw fa-building"></i>&nbsp;
                    <?= $location->billing_street1 ?><br/>
                    <?php if (!empty($location->billing_street2)): ?>
                        <?= $location->billing_street2 ?><br/>
                    <?php endif; ?>
                    <i class="fa fa-fw"></i>&nbsp;
                    <?= $location->billing_city ?>,
                    <?= $location->region->subdiv_alt ?>
                    <?= $location->billing_postal_code ?>
                </address>

                <div class="clearfix"></div>

                <p><i class="fa fa-fw fa-phone-square"></i>&nbsp; <?= $location->main_phone ?></p>
            <?php else: ?>

                <h4>No Account Representative configured for this retailer.</h4>

                <p align="center" style="margin-top: 40px;">
                    <a href="/customer/retailers/edit/<?= $location->id; ?>" class="btn btn-primary btn-lg"><i class="fa fa-user-plus"></i>&nbsp; Add Account Representative</a>
                </p>
            <?php endif; ?>
            <div class="clearfix"></div>

        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
    <div class="x_panel tile fixed_height_320">
        <div class="x_title">
            <h2>Company Contact(s)</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php if (!empty($location->retailers_contacts)): ?>
                <p><?= $location->account_representative->profile->first_name; ?> <?= $location->account_representative->profile->last_name; ?></p>
                <address style="float: left;">
                    <i class="fa fa-fw fa-building"></i>&nbsp;
                    <?= $location->billing_street1 ?><br/>
                    <?php if (!empty($location->billing_street2)): ?>
                        <?= $location->billing_street2 ?><br/>
                    <?php endif; ?>
                    <i class="fa fa-fw"></i>&nbsp;
                    <?= $location->billing_city ?>,
                    <?= $location->region->subdiv_alt ?>
                    <?= $location->billing_postal_code ?>
                </address>
                <div class="clearfix"></div>
                <p><i class="fa fa-fw fa-phone-square"></i>&nbsp; <?= $location->main_phone ?></p>
            <?php else: ?>

                <h4>No Contacts are configured for this retailer.</h4>
                <p align="center" style="margin-top: 40px;">
                    <a href="/customer/retailers/edit/<?= $location->id; ?>" class="btn btn-primary btn-lg"><i class="fa fa-users"></i>&nbsp; Add Contacts</a>
                </p>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
