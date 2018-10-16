<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ScanResult[]|\Cake\Collection\CollectionInterface $scanResults
 */
?>

<?php $this->set('pageTitle', 'Scan Results'); ?>

<div class="x_panel">
    <div class="x_title">
        <h2>These are the recorded scan results</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
         <section class="content">

            <div class="row">
                <div class="impressions index col-md-12 col-sm-12 col-xs-12 table-custom">
                    <table class="table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('accesspoint_id') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('mac_addr') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('vendor') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('rssi') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('scan_timestamp') ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scanResults as $scanResult): ?>
                                <tr>
                                    <td data-title="Scan Result ID"><?= $this->Number->format($scanResult->id) ?></td>
                                    <td data-title="Access Point ID"><?= $this->Number->format($scanResult->accesspoint_id) ?></td>
                                    <td data-title="MAC Address"><?= h(join(':', str_split($scanResult->mac_addr,2))) ?></td>
                                    <td data-title="Vendor"><?= h($scanResult->vendor) ?></td>
                                    <td data-title="RSSI"><?= $this->Number->format($scanResult->rssi) ?></td>
                                    <td data-title="Timestamp"><?= h($scanResult->scan_timestamp) ?></td>
                                    <td data-title="Actions" class="actions">
                                    <?= $this->Html->link('<i class="fa fa-search"></i>&nbsp;View Details', ['action' => 'view', $scanResult->id], ['class' => 'btn btn-default btn-xs', 'escape' => false]); ?>
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
