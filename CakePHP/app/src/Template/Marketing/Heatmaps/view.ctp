<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Heatmap $heatmap
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Heatmap'), ['action' => 'edit', $heatmap->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Heatmap'), ['action' => 'delete', $heatmap->id], ['confirm' => __('Are you sure you want to delete # {0}?', $heatmap->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Heatmaps'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Heatmap'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Access Points'), ['controller' => 'AccessPoints', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Access Point'), ['controller' => 'AccessPoints', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Floorplans Library'), ['controller' => 'FloorplansLibrary', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Floorplans Library'), ['controller' => 'FloorplansLibrary', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="heatmaps view large-9 medium-8 columns content">
    <h3><?= h($heatmap->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Access Point') ?></th>
            <td><?= $heatmap->has('access_point') ? $this->Html->link($heatmap->access_point->id, ['controller' => 'AccessPoints', 'action' => 'view', $heatmap->access_point->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Floorplans Library') ?></th>
            <td><?= $heatmap->has('floorplans_library') ? $this->Html->link($heatmap->floorplans_library->id, ['controller' => 'FloorplansLibrary', 'action' => 'view', $heatmap->floorplans_library->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($heatmap->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('X') ?></th>
            <td><?= $this->Number->format($heatmap->x) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Y') ?></th>
            <td><?= $this->Number->format($heatmap->y) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($heatmap->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($heatmap->modified) ?></td>
        </tr>
    </table>
</div>
