<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Apzone $apzone
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Apzones'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Access Points'), ['controller' => 'AccessPoints', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Access Point'), ['controller' => 'AccessPoints', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="apzones form large-9 medium-8 columns content">
    <?= $this->Form->create($apzone) ?>
    <fieldset>
        <legend><?= __('Add Apzone') ?></legend>
        <?php
            echo $this->Form->control('location_id', ['options' => $locations]);
            echo $this->Form->control('accesspoint_id', ['options' => $accessPoints]);
            echo $this->Form->control('fixture_no');
            echo $this->Form->control('placement');
            echo $this->Form->control('floor');
            echo $this->Form->control('scanresults_count');
            echo $this->Form->control('ignore_further_incidents');
            echo $this->Form->control('is_reviewed');
            echo $this->Form->control('review_date', ['empty' => true]);
            echo $this->Form->control('last_scanresult', ['empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
