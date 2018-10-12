<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ScanResult[]|\Cake\Collection\CollectionInterface $scanResults
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Scan Result'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Accesspoints'), ['controller' => 'AccessPoints', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Accesspoint'), ['controller' => 'AccessPoints', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="scanResults index large-9 medium-8 columns content">
    <h3><?= __('Scan Results') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('accesspoint_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('mac_addr') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rssi') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($scanResults as $scanResult): ?>
            <tr>
                <td><?= $this->Number->format($scanResult->id) ?></td>
                <td><?= $scanResult->has('accesspoint') ? $this->Html->link($scanResult->accesspoint->id, ['controller' => 'AccessPoints', 'action' => 'view', $scanResult->accesspoint->id]) : '' ?></td>
                <td><?= h($scanResult->mac_addr) ?></td>
                <td><?= $this->Number->format($scanResult->rssi) ?></td>
                <td><?= h($scanResult->created) ?></td>
                <td><?= h($scanResult->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $scanResult->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $scanResult->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $scanResult->id], ['confirm' => __('Are you sure you want to delete # {0}?', $scanResult->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
