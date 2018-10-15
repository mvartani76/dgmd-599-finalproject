<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ScanResult[]|\Cake\Collection\CollectionInterface $scanResults
 */
?>

<?php $this->set('pageTitle', 'Scan Results'); ?>

<div class="x_panel">
    <div class="x_title">
        <h2>Scan Results</h2>
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
                                <th><?= $this->Paginator->sort('accesspoint_id') ?></th>
                                <th><?= $this->Paginator->sort('scan_timestamp') ?></th>
                                <th><?= $this->Paginator->sort('mac_addr') ?></th>
                                <th><?= $this->Paginator->sort('rssi') ?></th>
                                <th><?= $this->Paginator->sort('created') ?></th>
                                <th><?= $this->Paginator->sort('modified') ?></th>
                                <th><class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scanResults as $scanResult): ?>
                                <tr>
                                    <td data-title="Id"><?= $this->Number->format($scanResult->id) ?></td>
                                    <td><?= $scanResult->has('accessPoint') ? $this->Html->link($scanResult->accessPoint->id, ['controller' => 'AccessPoints', 'action' => 'view', $scanResult->accessPoint->id]) : '' ?></td>
                                    <td data-title="Timestamp"><?= h($scanResult->scan_timestamp) ?></td>
                                    <td data-title="MAC Address"><?= h($scanResult->mac_addr) ?></td>
                                    <td data-title="RSSI"><?= $this->Number->format($scanResult->rssi) ?></td>
                                    <td data-title="Created"><?= $scanResult->created?></td>
                                    <td data-title="Modified"><?= $scanResult->modified?></td>
                                    <td data-title="Actions" class="actions">
                                    <?= $this->Html->link('<i class="fa fa-search"></i>&nbsp;View Details', ['action' => 'view', $scanResult->id], ['class' => 'btn btn-default btn-xs', 'escape' => false]); ?>
                                    <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;Edit', ['action' => 'edit', $scanResult->id], ['class' => 'btn btn-default btn-xs', 'escape' => false]); ?>
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
