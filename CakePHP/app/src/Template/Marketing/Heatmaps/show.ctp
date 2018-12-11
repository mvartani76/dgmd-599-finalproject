<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Heatmap $heatmap
 */
?>
<?php $this->Html->script('/js/heatmap', ['block' => 'scriptBottom']); ?>
<?php $this->AssetCompress->css('heatmaps', ['block' => 'scriptTop']); ?>
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
    <div class="heatmap-page-content" style="background-image: url('<?= $heatmap->getFullImageUrl() ?>')">
    </div>
</div>

<script>
    $(document).ready(function() {

        // minimal heatmap instance configuration
        var heatmapInstance = h337.create({
        // only container is required, the rest will be defaults
        container: document.querySelector('.heatmap-page-content')
        });
        console.log(h337);
        // now generate some random data
        var points = [];
        var max = 0;
        var width = 800;
        var height = 400;
        var len = 200;

        while (len--) {
          var val = Math.floor(Math.random()*100);
          max = Math.max(max, val);
          var point = {
            x: Math.floor(Math.random()*width),
            y: Math.floor(Math.random()*height),
            value: val
          };
          points.push(point);
        }
        // heatmap data format
        var data = { 
          max: max, 
          data: points 
        };
        // if you have a set of datapoints always use setData instead of addData
        // for data initialization
        heatmapInstance.setData(data);
    });
</script>