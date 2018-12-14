<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Heatmap[]|\Cake\Collection\CollectionInterface $heatmaps
 */
?>
<?php $this->set('pageTitle', 'Heatmaps'); ?>
<?php $this->AssetCompress->css('heatmaps', ['block' => 'scriptTop']); ?>
<div class="x_panel">
    <div class="x_title">
        <h2>
            These are the active heatmaps. <span class="badge bg-green"><?= $this->Paginator->counter('{{count}}') ?> total</span></h2>
        <div class="clearfix"></div>
    </div>
    <a class="pull-right btn btn-xs btn-primary hidden-md hidden-lg" href="/marketing/content/create_content"><i class="fa fa-plus-circle"></i> &nbsp;Create Content</a>
    <div class="x_content">
        <section class="content">
            <div class="table-custom">
                <p>This is a listing of the active heatmaps.</p>
                <p>You can view and upload floorplans <a href="/marketing/content/floorplans_library">clicking here.</a></p>
                <hr/>
                <?php if ($heatmaps->isEmpty()): ?>
                <div class="no-data">
                    <p>'There are no active heatmaps savailable.' <?= $this->Html->link(__('Create Content Now'), ['action' => 'create_content']) ?></p>
                </div>
                <?php else: ?>
                <table class="table-striped">
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('accesspoint_id', 'AccessPoint ID') ?></th>
                        <th><?= $this->Paginator->sort('floorplan_id') ?></th>
                        <th><?= $this->Paginator->sort('x') ?></th>
                        <th><?= $this->Paginator->sort('y') ?></th>
                        <th><?= $this->Paginator->sort('width_m') ?></th>
                        <th><?= $this->Paginator->sort('height_m') ?></th>
                        <th><?= $this->Paginator->sort('num_width_divs') ?></th>
                        <th><?= $this->Paginator->sort('num_height_divs') ?></th>
                        <th><?= $this->Paginator->sort('created') ?></th>
                        <th><?= $this->Paginator->sort('modified') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($heatmaps as $heatmap): ?>
                        <tr>
                            <td data-title="Heatmap ID"><?= $this->Number->format($heatmap->id) ?></td>
                            <td><?= $heatmap->has('access_point') ? $this->Html->link($heatmap->access_point->id, '/customer/AccessPoints/view/'.$heatmap->access_point->id) : '' ?></td>
                            <td><?= $heatmap->has('floorplans_library') ? $heatmap->floorplans_library->title : '' ?></td>
                            <td data-title="X"><?= $this->Number->format($heatmap->x) ?></td>
                            <td data-title="Y"><?= $this->Number->format($heatmap->y) ?></td>
                            <td><?= $heatmap->has('floorplans_library') ? $heatmap->floorplans_library->width_m : '' ?></td>
                            <td><?= $heatmap->has('floorplans_library') ? $heatmap->floorplans_library->height_m : '' ?></td>
                            <td><?= $heatmap->has('floorplans_library') ? $heatmap->floorplans_library->num_width_divs : '' ?></td>
                            <td><?= $heatmap->has('floorplans_library') ? $heatmap->floorplans_library->num_height_divs : '' ?></td>
                            <td data-title="Created"><?= $this->Time->format($heatmap->created) ?></td>
                            <td data-title="Updated"><?= $this->Time->format($heatmap->modified) ?></td>
                            <td data-title="Actions" class="actions">
                                <a href="#" class='btn btn-default btn-xs loadPreview' data-preview="<?=$heatmap->id;?>"><i class="fa fa-search"></i>&nbsp;Preview</a>
                                <?= $this->Html->link('<i class="fa fa-edit"></i>&nbsp;Edit', ['action' => 'edit', $heatmap->id], ['class' => 'btn btn-primary btn-xs', 'escape' => false]); ?>
                                <?= $this->Html->link(__('<i class="fa fa-times-circle"></i> &nbsp;Delete'), ['action' => 'delete', $heatmap->id], ['class' => 'btn btn-danger btn-xs', 'escape' => false]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
                <?= $this->element('paginator') ?>
                <?= $this->element('Marketing/Heatmaps/modal_preview') ?>
            </div>
        </section>
    </div>
</div>

<script>
jQuery(function($) {
    $('.loadPreview').click(function(e) {
        var that = this;
        e.preventDefault();
        $('#modal-content-preview').modal('show');
        id = $(that).data('preview');
        
        $('#modal-content-preview .modal-body').load('/marketing/heatmaps/preview/' + id);
    });
});
</script>

<script>
$(document).on("click", ".heatmap-content", function(event){
    var rect = event.target.getBoundingClientRect();
    
    // Subtract off bounding box to get relative position of heatmap-content.
    // The top left corner of heatmap-content will be 0,0
    var x = event.pageX - rect.left;
    var y = event.pageY - rect.top;

    // Calculate the new position to move the fa icon to as %
    // Limit to 95% to prevent overlap outside of image background
    var leftpct = Math.min(((x/rect.width)*100),95).toString();
    var toppct = Math.min(((y/rect.height)*100),95).toString();

    var lpstr = leftpct.concat('%');
    var tpstr = toppct.concat('%');

    // Update the left/top css properties of the ap-position div
    $('div.ap-position').css({'left': lpstr, 'top': tpstr});
});
</script>

<script>
jQuery(function($) {
    $('.apbutton').click(function(e) {
        var $this = $(this);
        $this.toggleClass('apbutton');
        if($this.hasClass('apbutton')){
            $this.text('Update Access Point Position');
            $this.removeClass('btn-info');
            $this.addClass('btn-primary');
        } else {
            $this.text('Save Updated AP Position');
            $this.removeClass('btn-primary');
            $this.addClass('btn-info');
        }
    });
});
</script>