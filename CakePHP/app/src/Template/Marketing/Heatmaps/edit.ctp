<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Heatmap $heatmap
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $heatmap->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $heatmap->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Heatmaps'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Access Points'), ['controller' => 'AccessPoints', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Access Point'), ['controller' => 'AccessPoints', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Floorplans Library'), ['controller' => 'FloorplansLibrary', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Floorplans Library'), ['controller' => 'FloorplansLibrary', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="heatmaps form large-9 medium-8 columns content">
    <?= $this->Form->create($heatmap) ?>
    <fieldset>
        <legend><?= __('Edit Heatmap') ?></legend>
        <?php
            echo $this->Form->control('accesspoint_id', ['options' => $accessPoints]);
            echo $this->Form->control('floorplan_id', ['options' => $floorplansLibrary]);
            echo $this->Form->control('x');
            echo $this->Form->control('y');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
