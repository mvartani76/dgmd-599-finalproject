<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ScanResult $scanResult
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Scan Result'), ['action' => 'edit', $scanResult->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Scan Result'), ['action' => 'delete', $scanResult->id], ['confirm' => __('Are you sure you want to delete # {0}?', $scanResult->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Scan Results'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Scan Result'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Accesspoints'), ['controller' => 'AccessPoints', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Accesspoint'), ['controller' => 'AccessPoints', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="scanResults view large-9 medium-8 columns content">
    <h3><?= h($scanResult->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Accesspoint') ?></th>
            <td><?= $scanResult->has('accesspoint') ? $this->Html->link($scanResult->accesspoint->id, ['controller' => 'AccessPoints', 'action' => 'view', $scanResult->accesspoint->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mac Addr') ?></th>
            <td><?= h($scanResult->mac_addr) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($scanResult->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rssi') ?></th>
            <td><?= $this->Number->format($scanResult->rssi) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($scanResult->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($scanResult->modified) ?></td>
        </tr>
    </table>
</div>
