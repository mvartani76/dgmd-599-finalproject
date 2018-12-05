<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Heatmap[]|\Cake\Collection\CollectionInterface $heatmaps
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Heatmap'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Access Points'), ['controller' => 'AccessPoints', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Access Point'), ['controller' => 'AccessPoints', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Floorplans Library'), ['controller' => 'FloorplansLibrary', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Floorplans Library'), ['controller' => 'FloorplansLibrary', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="heatmaps index large-9 medium-8 columns content">
    <h3><?= __('Heatmaps') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('accesspoint_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('floorplan_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('x') ?></th>
                <th scope="col"><?= $this->Paginator->sort('y') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($heatmaps as $heatmap): ?>
            <tr>
                <td><?= $this->Number->format($heatmap->id) ?></td>
                <td><?= $heatmap->has('access_point') ? $this->Html->link($heatmap->access_point->id, ['controller' => 'AccessPoints', 'action' => 'view', $heatmap->access_point->id]) : '' ?></td>
                <td><?= $heatmap->has('floorplans_library') ? $this->Html->link($heatmap->floorplans_library->id, ['controller' => 'FloorplansLibrary', 'action' => 'view', $heatmap->floorplans_library->id]) : '' ?></td>
                <td><?= $this->Number->format($heatmap->x) ?></td>
                <td><?= $this->Number->format($heatmap->y) ?></td>
                <td><?= h($heatmap->created) ?></td>
                <td><?= h($heatmap->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $heatmap->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $heatmap->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $heatmap->id], ['confirm' => __('Are you sure you want to delete # {0}?', $heatmap->id)]) ?>
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
