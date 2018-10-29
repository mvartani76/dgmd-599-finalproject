<?php $this->set('pageTitle', 'Locations'); ?>
<div class="x_panel">
    <div class="x_title">
        <h2>These are the Locations we have that have beacons deployed  <span class="badge bg-green"><?= $this->Paginator->counter('{{count}}') ?> total</span></h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">

            <div class="row">
                <div class="locations  index col-md-12 col-sm-12 col-xs-12 table-custom">
                    <table class="table-striped">
                        <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id') ?></th>
                            <th><?= $this->Paginator->sort('location', 'Location Name<br/><small>(brand or subsidiary)</small>', ['escape' => false]) ?></th>
                            <th><?= $this->Paginator->sort('regional_name', 'Regional Name<br/><small>(Airport, Mall, etc)</small>', ['escape' => false]) ?></th>
                            <th><?= $this->Paginator->sort('impressions_count', 'Impressions for this Location<br/><small>For all zones</small>', ['escape' => false]) ?></th>
                            <th><?= $this->Paginator->sort('devices_count', 'Scanned Devices for this Location<br/><small>For all apzones</small>', ['escape' => false]) ?></th>
                            <th>Extra</th>
                            <th class="actions" style="/*width: 233px;*/"><?= __('Actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($locations as $location): ?>
                            <?php

                            $data = $location->toArray();

                            $fb   = \Cake\Utility\Hash::extract($data, 'zones.{n}.flagged_beacon');

                            $class = null;
                            $flagExists = false;

                            if (!empty($fb)) {
                                foreach($fb as $flag) {
                                    if (!empty($flag)) {
                                        $class = 'error';
                                        $flagExists = true;
                                    }
                                }
                            }

                            ?>
                            <tr class="<?= $class ?>">
                                <td data-title="Id"><?= $this->Number->format($location->id) ?></td>
                                <td data-title="Location Name">
                                    <a href="/customer/locations/view/<?= $location->id; ?>"><?= h($location->location) ?></a><br/>
                                    <?= $location->address1 ?><br/>
                                    <?= $location->city ?? "" ?> <?= $location->region->subdiv_alt ?? "" ?> <?= $location->postal_code ?? "" ?><br/>
                                    <?= $location->country->country_name ?? ""?>
                                </td>
                                <td data-title="Regional Name"><?= h($location->regional_name) ?></td>
                                <td data-title="Impressions"><a title="View this entry's impressions" href="/customer/impressions/viewby/location/<?= $location->id ?>">
                                        <?= $this->Number->format(array_sum(\Cake\Utility\Hash::extract($location->zones, '{n}.impressions_count'))) ?>
                                    </a>
                                    <a title="View this locations's zones" href="/customer/locations/view/<?= $location->id ?>#notes-tab4"> (<?= sngw('zone', $location->zones_count) ?>)</a><br/>
                                </td>
                                <td data-title="ScanResults"><a title="View this entry's scan results" href="/customer/scan_results/viewby/location/<?= $location->id ?>">
                                        <?= $this->Number->format(array_sum(\Cake\Utility\Hash::extract($location->apzones, '{n}.total_devices_count'))) ?>
                                    </a>
                                    <a title="View this locations's AP zones" href="/customer/locations/view/<?= $location->id ?>#notes-tab4"> (<?= sngw('apzone', $location->apzones_count) ?>)</a><br/>
                                </td>
                                <td data-title="Extra" style="text-align: right;">
                                    <?php if ($flagExists): ?>
                                        <a class="btn btn-danger btn-xs" href="/customer/flagged_beacons/by_location/<?= $location->id ?>">
                                            <i class="fa fa-minus-circle"></i> &nbsp; View Flag
                                        </a>
                                    <?php endif; ?>
                                </td>

                                <td data-title="Actions" class="actions" style="/*width: 233px;*/">
                                    <?= $this->Html->link('<i class="fa fa-search"></i>&nbsp;View', ['action' => 'view', $location->id], ['class' => 'btn btn-primary btn-xs', 'escape' => false]); ?>
                                    <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;Edit', ['action' => 'edit', $location->id], ['class' => 'btn btn-default btn-xs', 'escape' => false]); ?>
                                    <?= $this->Form->postLink('<i class="fa fa-times-circle"></i> ' . __('Flag as Duplicate'), [
                                        'prefix' => false,
                                        'action' => 'flagDuplicate',
                                        $location->id,
                                    ], [
                                        'class' => 'btn btn-warning btn-xs',
                                        'confirm' => __('Are you sure you want flag the following location as a duplicate? \n{0}?', $location->location),
                                        'escape' => false,
                                    ]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?= $this->element('paginator') ?>
                </div>
            </div>
        </section>
    </div>
</div>
