<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Heatmap $heatmap
 */
?>
<?php $this->Html->script('/js/heatmap', ['block' => 'scriptBottom']); ?>
<?php $this->AssetCompress->css('heatmaps', ['block' => 'scriptTop']); ?>

<?php $this->set('pageTitle', 'Show All Heatmap Activity'); ?>

<div class="x_panel">
    <div class="x_title">
        <h2>Show all heatmap activity for <?= h($heatmap->floorplans_library->title) ?></h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="heatmaps view large-9 medium-8 columns content">
                <div class="heatmap-page-content" style="background-image: url('<?= $heatmap->getFullImageUrl() ?>')">
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    $(document).ready(function() {

        // minimal heatmap instance configuration
        var heatmapInstance = h337.create({
        // only container is required, the rest will be defaults
        container: document.querySelector('.heatmap-page-content')
        });
        
        var heatmapdata = <?= $heatmapdata ?>;

        // if you have a set of datapoints always use setData instead of addData
        // for data initialization
        heatmapInstance.setData(heatmapdata);
    });
</script>