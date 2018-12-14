<!-- Populate stylesheet with wifi icon position -->
<?= $content->positionAccessPoint(); ?>
<div class="heatmap-container">
    <div class="heatmap-content" style="background-image: url('<?= $content->getFullImageUrl() ?>')">
    	<div class="ap-position"><span class="fa fa-wifi red"></span></div>
    </div>
</div>
