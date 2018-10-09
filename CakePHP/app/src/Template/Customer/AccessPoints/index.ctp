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

                        </tbody>
                    </table>
                    <?= $this->element('paginator') ?>
                </div>
            </div>
        </section>
    </div>
</div>