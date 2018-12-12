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
        <li><?= $this->Html->link(__('List Access Points'), ['controller' => 'access_points', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Access Point'), ['controller' => 'access_points', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Floorplans Library'), ['controller' => 'floorplans_library', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Floorplans Library'), ['controller' => 'floorplans_library', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="heatmaps form large-9 medium-8 columns content">
    <?= $this->Form->create($heatmap) ?>
    <fieldset>
        <legend><?= __('Edit Heatmap') ?></legend>
        <?php
            echo $this->Form->control('accesspoint_id', ['options' => $accessPoints]);
            echo $this->Form->control('floorplan_id', ['options' => $floorplans]);
            echo $this->Form->control('x');
            echo $this->Form->control('y');
            echo $this->Form->control('width_m');
            echo $this->Form->control('height_m');
            echo $this->Form->control('num_width_divs');
            echo $this->Form->control('num_height_divs');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
