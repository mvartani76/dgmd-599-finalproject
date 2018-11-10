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

    $('#ImpressionsCount').animateNumber({numberStep: commaStep, number: <?= $accessPoint->impressions_count; ?>});
    $('#DevicesCount').animateNumber({numberStep: commaStep, number: <?= $accessPoint->total_devices_count ?>});
    $('#Impressions7Count').animateNumber({numberStep: commaStep, number: <?= $ic ?>});
    <?php $this->Html->scriptEnd(); ?>
</script>


<?= $this->element('Header/Common/filter_bar'); ?>
<?= $this->element('Header/Common/stats_bar',['type'=>'accesspoints']); ?>
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
                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Acess Point Notes&nbsp;&nbsp; <span title="Add Notes" class="add-new-note" data-note-model="Beacons" data-model-id="<?= $accessPoint->id ?>" style="cursor: pointer; float: right;"><i class="fa fa-plus-circle"></i></span></a></li>
                    <li role="presentation"><a href="#tab-dwell-times" role="tab" data-toggle="tab" aria-expanded="false">Dwell Times</a></li>
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
                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                        <div class="paginate-ajax-container">
                            <?php $this->start('paginated_content.AccessPointsNotes'); ?>
                            <?php if (is_object($notes) && !$notes->isEmpty()): ?>
                                <?php

                                $this->Paginator->setAjax();
                                $this->Paginator->options(
                                    [
                                        'url' => array_merge($this->request->pass, ['ajax' => true, 'mdl' => 'AccessPointsNotes'], $this->request->query)
                                    ]
                                );
                                ?>
                                <?= $this->element('paginator', ['model' => 'AccessPointsNotes', 'isAjax' => true]) ?>
                                <ul id="NotesResults" class="messages">
                                    <?php foreach($notes as $k => $note): ?>
                                        <?php $altc = ($k % 2 === 0) ? 'alt-bg':null; ?>
                                        <li class="<?= $altc; ?>">
                                            <div class="message_date">
                                                <h3 class="date text-info"><?= date('j', strtotime($this->Time->format($note->created))); ?></h3>
                                                <p class="month"><?= date('M', strtotime($this->Time->format($note->created))); ?></p>
                                            </div>
                                            <div class="message_wrapper">
                                                <h4 style="margin-bottom: 20px;" class="heading"><?= $note->subject ?></h4>
                                                <hr/>
                                                <blockquote class="message"><?= $note->entry; ?></blockquote>
                                                <hr/>
                                                <h5><?= $note->author->profile->first_name ?> <?= $note->author->profile->last_name ?> <small style="color: #999; margin-left: 40px;">at <?= $this->Time->format($note->created,'MM/dd/yyyy hh:mm a')?></small></h5>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?= $this->element('paginator', ['model' => 'AccessPointsNotes', 'isAjax' => true]) ?>
                            <?php else: ?>
                                <ul id="NotesResults" class="messages">
                                    <li class="no-data">
                                        <p style="color: #aaa !important; text-align: center;">There are no notes to display for this particular Access Point.<br/><br/>
                                            <a href="#" class="btn-sm btn btn-primary add-new-note" data-note-model="AccessPoints" data-model-id="<?= $accessPoint->id; ?>">
                                                <i class="fa fa-plus-circle"></i> &nbsp;Add a Note
                                            </a>
                                        </p>
                                    </li>
                                </ul>
                            <?php endif; ?>
                            <?php $this->end(); ?>
                            <?php echo $this->fetch('paginated_content.AccessPointsNotes'); ?>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content4" aria-labelledby="profile-tab">
                        <div class="paginate-ajax-container">
                            <?php $this->start('paginated_content.scanResults'); ?>

                            <!-- ScanResults is an array and not an object partially because it is coming from DynamodB -->
                            <!-- Cannot use is_object() or is_empty() methods. Just use array count > 0. -->
                            <?php if (count($scanResults, COUNT_RECURSIVE) > 0): ?>
                                <div class="callout callout-info">
                                    <?= $this->Paginator->counter([
                                        'format' => 'Page {{page}} of {{pages}} - {{count}} total records'
                                    ]) ?>
                                </div>
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
                                                <td data-title="Timestamp"><?= h(gmdate("F j, Y, g:i a", $scanResult['log_time']/1000)) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                    <?= $this->element('paginator', ['model' => 'ScanResults', 'isAjax' => true]) ?>
                                </div> */
                            <?php else: ?>
                                <ul id="NotesResults" class="messages">
                                    <li class="no-data">
                                        <p style="color: #aaa !important; text-align: center;">There are no scan results to display for this access point.</p>
                                    </li>
                                </ul>
                            <?php endif; ?>
                            <?php $this->end(); ?>
                            <?php echo $this->fetch('paginated_content.scanResults'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
