<!-- Populate stylesheet with wifi icon position -->
<?= $content->positionAccessPoint(); ?>
<div class="heatmap-container">
    <div class="heatmap-content" id="myheatmap-content" style="background-image: url('<?= $content->getFullImageUrl() ?>');border:1px solid gray;">
    	<div class="ap-position"><span class="fa fa-wifi red"></span></div>
    </div>
</div>
