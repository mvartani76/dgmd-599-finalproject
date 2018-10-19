<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Apzone[]|\Cake\Collection\CollectionInterface $apzones
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Apzone'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Access Points'), ['controller' => 'AccessPoints', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Access Point'), ['controller' => 'AccessPoints', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="apzones index large-9 medium-8 columns content">
    <h3><?= __('Apzones') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('location_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('accesspoint_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fixture_no') ?></th>
                <th scope="col"><?= $this->Paginator->sort('floor') ?></th>
                <th scope="col"><?= $this->Paginator->sort('scanresults_count') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ignore_further_incidents') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_reviewed') ?></th>
                <th scope="col"><?= $this->Paginator->sort('review_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('last_scanresult') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($apzones as $apzone): ?>
            <tr>
                <td><?= $this->Number->format($apzone->id) ?></td>
                <td><?= $apzone->has('location') ? $this->Html->link($apzone->location->location, ['controller' => 'Locations', 'action' => 'view', $apzone->location->id]) : '' ?></td>
                <td><?= $apzone->has('access_point') ? $this->Html->link($apzone->access_point->id, ['controller' => 'AccessPoints', 'action' => 'view', $apzone->access_point->id]) : '' ?></td>
                <td><?= h($apzone->fixture_no) ?></td>
                <td><?= h($apzone->floor) ?></td>
                <td><?= $this->Number->format($apzone->scanresults_count) ?></td>
                <td><?= h($apzone->ignore_further_incidents) ?></td>
                <td><?= h($apzone->is_reviewed) ?></td>
                <td><?= h($apzone->review_date) ?></td>
                <td><?= h($apzone->last_scanresult) ?></td>
                <td><?= h($apzone->created) ?></td>
                <td><?= h($apzone->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $apzone->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $apzone->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $apzone->id], ['confirm' => __('Are you sure you want to delete # {0}?', $apzone->id)]) ?>
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
