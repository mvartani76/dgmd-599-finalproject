<?php $this->set('pageTitle', 'Access Point ID: ' . $accessPoint->id); ?>
<?php $this->Html->script('/js/jquery.animate.number.min', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/csv_template', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/bootstrap3-wysihtml5.all.min', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/notes', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/jquery.blockui', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/ajax_paging', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/Spin', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/load_on_map', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('https://maps.googleapis.com/maps/api/js?key='.\Cake\Core\Configure::read('Settings.goo_api_maps_key').'', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/filtering', ['block' => 'scriptBottom']); ?>
<?php $this->Html->css('/css/bootstrap3-wysihtml5', ['block' => true]); ?>
<?php $this->Html->css('/css/progress', ['block' => true]); ?>
<?= $this->AssetCompress->script('app-v2') ?>
<style>

    table.table tr.fields-info th {
    }
    table.table tr.fields-info th,
    table.table tr.fields-info td {
        min-width: 190px !important;
        text-align: center;
        white-space: nowrap !important;
    }

    .uploadifive-queue button.close-file {
        position: absolute;
        right: 10px;
        top: 10px;
    }
    .uploadifive-queue-item {
        position: relative;
    }

    td.scantime {
        flex-direction: row-reverse;
    }

</style>
<script>
    var STATS_URL = window.location.origin + window.location.pathname;
    var ACCESS_POINT_ID = <?= $accessPoint->id ?>;
    //IN UTC
    var START_DATE  = '<?=$periodStartRaw?>';
    var END_DATE  = '<?=$periodEndRaw; ?>';
    $(function() {
        var options = {
            animation: true,
            placement: 'top',
            html: true,
            container: 'body'
        };
        $('tr.fields-info th').popover(options)
    });

</script>
<script>
    <?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>

    var commaStep = $.animateNumber.numberStepFactories.separator(',');

    $('#TotalScanCount').animateNumber({numberStep: commaStep, number: <?= $totalScanCount ?>});
    $('#TotalUniqueDevices').animateNumber({numberStep: commaStep, number: <?= $totalUniqueDevices ?>});
    $('#TotalScanCount_time').animateNumber({numberStep: commaStep, number: <?= $totalScanCount_time ?>});
    $('#TotalUniqueDevices_time').animateNumber({numberStep: commaStep, number: <?= $totalUniqueDevices_time ?>});
    <?php $this->Html->scriptEnd(); ?>
</script>

<!-- Display Access Point ID at the top of the page so the user knows which access point
     the data is associated with. -->

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="row x_title" style="border-bottom: none; padding-bottom: 0; margin-bottom: 0;">
            <div class="col-md-6">
                <h2>Access Point ID ID #<?= $accessPoint->id ?> (<?= h(join(':', str_split($accessPoint->mac_addr,2))) ?>)</h2>
            </div>
            <?= $this->element('Header/daterangepicker') ?>
        </div>
    </div>
</div>



<?= $this->element('Header/Common/stats_bar',['type' => 'accesspoints']); ?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-bars"></i> Graphs, Actions and MetaData</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab_content4" role="tab" id="profile-tab4" data-toggle="tab" aria-expanded="false">All Activity</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade" id="tab_content1" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div style="width: 100%; height: 500px;" id="cht1"></div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div style="width: 100%; height: 500px;" id="cht2"></div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab-dwell-times" aria-labelledby="profile-tab">
                        <div class="paginate-ajax" data-target=".table-custom" data-load="/DwellTimes?accessPoint_id=<?= $accessPoint->id ?>"></div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content4" aria-labelledby="profile-tab">
                        <div class="paginate-ajax-container">
                            <!-- ScanResults is an array and not an object partially because it is coming from DynamodB -->
                            <!-- Cannot use is_object() or is_empty() methods. Just use array count > 0. -->
                            <?php if (count($scanResults, COUNT_RECURSIVE) > 0): ?>
                                <div class="col-md-12 col-sm-12 col-xs-12 table-custom">
                                    <table id="ScanResultsData" class="table-hover table-striped impressions-content">
                                        <thead>
                                        <tr>
                                            <th><?= $this->Paginator->sort('mac_addr', 'MAC Address', ['model' => 'ScanResults']) ?></th>
                                            <th><?= $this->Paginator->sort('vendor', 'Vendor', ['model' => 'ScanResults']) ?></th>
                                            <th><?= $this->Paginator->sort('rssi', 'RSSI', ['model' => 'ScanResults']) ?></th>
                                            <th><?= $this->Paginator->sort('timestamp', 'Timestamp<br/>', ['model' => 'ScanResults', 'escape' => false]) ?></th>
                                        </tr>
                                        </thead>
                                        <?php foreach($scanResults as $scanResult): ?>
                                            <?php
                                            $data = $scanResult;
                                            $class = null;
                                            ?>
                                            <tr>
                                                <td data-title="MAC Address"><?= h($scanResult['payload']['mac_addr']) ?></td>
                                                <td data-title="Vendor"><?= h($scanResult['payload']['vendor']) ?></td>
                                                <td data-title="RSSI"><?= $this->Number->format($scanResult['payload']['rssi']) ?></td>
                                                <td class="scantime" data-title="Timestamp"><?= h(gmdate("F j, Y, g:i a", $scanResult['log_time']/1000)) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <!-- Create a link to effectively paginate the data. This will pass back the LastEvaluatedKey to the controller to rescan the dynamodB table with these values. --->

                                        <!-- Do not display the Previous link if on the first page (page 0) -->
                                        <?php if ($page > 0): ?>
                                            <?php echo $this->Html->link('Previous', ['controller' => 'AccessPoints', 'action' => 'view', $accessPoint->id, '?' => ['page' => ($page-1), 'key' => serialize($prevlastvalkey)]], ['class' => 'pull-left btn btn-primary']); ?>
                                        <?php endif; ?>

                                        <!-- Only display the Next link if there are more results. -->
                                        <?php if ($lastevalkey != null): ?>
                                            <?php echo $this->Html->link('Next', ['controller' => 'AccessPoints', 'action' => 'view', $accessPoint->id, '?' => ['page' => ($page+1), 'key' => serialize($lastevalkey)]], ['class' => 'pull-right btn btn-primary']); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <ul id="NotesResults" class="messages">
                                    <li class="no-data">
                                        <p style="color: #aaa !important; text-align: center;">There are no scan results to display for this access point.</p>
                                    </li>
                                </ul>
                            <?php endif; ?>
                            <?php $this->end(); ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
