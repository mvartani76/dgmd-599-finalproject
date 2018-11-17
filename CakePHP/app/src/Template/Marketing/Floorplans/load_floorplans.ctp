<?= $this->Form->create(null, ['id' => 'ContentSaveMediaSelection', 'url' => '#']); ?>

<?= $this->Form->hidden('target_element_id', ['id' => 'ModalTargetElementId', 'value' => $this->request->query['target_field']]); ?>
<?= $this->Form->hidden('thumb_container_field_id', ['id' => 'ThumbFieldElementId', 'value' => $this->request->query['thumb_container_field']]); ?>

<?= $this->Form->hidden('target_path_field_id', ['id' => 'TargetPathElementId', 'value' => $this->request->query['target_path_field_id']]); ?>
<?= $this->Form->hidden('thumb_field_id', ['id' => 'ThumbFieldRigidElementId', 'value' => $this->request->query['thumb_field_id']]); ?>

<?= $this->Form->hidden('selected_media_entry', ['id' => 'SelectedMediaEntry']) ?>
<?= $this->Form->hidden('selected_media_source', ['id' => 'SelectedMediaSource']) ?>
<?= $this->Form->hidden('selected_media_width', ['id' => 'SelectedMediaWidth']) ?>
<?= $this->Form->hidden('selected_media_height', ['id' => 'SelectedMediaHeight']) ?>
<?= $this->Form->hidden('selected_media_size', ['id' => 'SelectedMediaSize']) ?>
<?= $this->Form->hidden('selected_video_source', ['id' => 'SelectedVideoSource']) ?>
<?= $this->Form->hidden('selected_media_thumb_source', ['id' => 'SelectedMediaThumbSource']) ?>
<?= $this->Form->hidden('selected_media_type', ['id' => 'SelectedMediaType', 'value' => $this->request->query['media_type']]) ?>

<div class="paginate-ajax-container">
    <?php $this->start('paginated_content.FloorplansLibrary'); ?>
    <div id="image-library-container">
        <?php echo $this->element('Marketing/Floorplans/library', ['modal' => true]); ?>
    </div>

    <?php $this->end(); ?>
    <?php echo $this->fetch('paginated_content.FloorplansLibrary'); ?>
</div>

<?= $this->Form->end(); ?>