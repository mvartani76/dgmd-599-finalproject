<?php $this->set('pageTitle', 'Access Point Inventory'); ?>

<div class="x_panel">
    <div class="x_title">
        <h2>These are the access points in your current inventory</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
         <section class="content">

            <div class="row">
                <div class="impressions index col-md-12 col-sm-12 col-xs-12 table-custom">
                    <table class="table-striped">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('id') ?></th>
                                <th><?= $this->Paginator->sort('mac_addr', 'MAC Address') ?></th>
                                <th><?= $this->Paginator->sort('total_devices_count', 'Total Devices Count') ?></th>
                                <th><?= $this->Paginator->sort('total_unique_devices_count', 'Total Unique Devices Count') ?></th>
                                <th><?= $this->Paginator->sort('Locations.address1', 'Location') ?></th>
                                <th><?= $this->Paginator->sort('created') ?></th>
                                <th><?= $this->Paginator->sort('modified') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($accessPoints as $accessPoint): ?>
                                <tr>
                                    <td data-title="Id"><?= $this->Number->format($accessPoint->id) ?></td>
                                    <td data-title="MAC Address"><?= h(join(':', str_split($accessPoint->mac_addr,2))) ?></td>
                                    <td data-title="Total Devices Count"><?= $this->Number->format($accessPoint->total_devices_count) ?></td>
                                    <td data-title="Total Unique Devices Count"><?= $this->Number->format($accessPoint->total_unique_devices_count) ?></td>
                                    <td data-title="Location"></td>
                                    <td data-title="Created"><?= $accessPoint->created?></td>
                                    <td data-title="Modified"><?= $accessPoint->modified?></td>
                                    <td data-title="Actions" class="actions">
                                    <?= $this->Html->link('<i class="fa fa-search"></i>&nbsp;View Details', ['action' => 'view', $accessPoint->id], ['class' => 'btn btn-default btn-xs', 'escape' => false]); ?>
                                    <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;Edit', ['action' => 'edit', $accessPoint->id], ['class' => 'btn btn-default btn-xs', 'escape' => false]); ?>
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