<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ScanResult $scanResult
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Scan Results'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Accesspoints'), ['controller' => 'AccessPoints', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Accesspoint'), ['controller' => 'AccessPoints', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="scanResults form large-9 medium-8 columns content">
    <?= $this->Form->create($scanResult) ?>
    <fieldset>
        <legend><?= __('Add Scan Result') ?></legend>
        <?php
            echo $this->Form->control('accesspoint_id', ['options' => $accesspoints, 'empty' => true]);
            echo $this->Form->control('mac_addr');
            echo $this->Form->control('rssi');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
