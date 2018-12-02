<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Apzone $apzone
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Apzone'), ['action' => 'edit', $apzone->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Apzone'), ['action' => 'delete', $apzone->id], ['confirm' => __('Are you sure you want to delete # {0}?', $apzone->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Apzones'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Apzone'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Access Points'), ['controller' => 'access_points', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Access Point'), ['controller' => 'access_points', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="apzones view large-9 medium-8 columns content">
    <h3><?= h($apzone->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Location') ?></th>
            <td><?= $apzone->has('location') ? $this->Html->link($apzone->location->location, ['controller' => 'Locations', 'action' => 'view', $apzone->location->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Access Point') ?></th>
            <td><?= $apzone->has('access_point') ? $this->Html->link($apzone->access_point->id, ['controller' => 'access_points', 'action' => 'view', $apzone->access_point->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fixture No') ?></th>
            <td><?= h($apzone->fixture_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Floor') ?></th>
            <td><?= h($apzone->floor) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($apzone->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Scanresults Count') ?></th>
            <td><?= $this->Number->format($apzone->scanresults_count) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Review Date') ?></th>
            <td><?= h($apzone->review_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Last Scanresult') ?></th>
            <td><?= h($apzone->last_scanresult) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($apzone->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($apzone->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ignore Further Incidents') ?></th>
            <td><?= $apzone->ignore_further_incidents ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Reviewed') ?></th>
            <td><?= $apzone->is_reviewed ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Placement') ?></h4>
        <?= $this->Text->autoParagraph(h($apzone->placement)); ?>
    </div>
</div>
