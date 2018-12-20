<?= $this->Form->create($libraryItem, ['id' => 'CropFloorplanForm']); ?>

<?= $this->Form->hidden('media_id', ['value' => $libraryItem->id]); ?>

<div class="row">
    <div class="col-md-8 docs-buttons">
        <div class="btn-group" style="margin-bottom: 5px;">
            <button type="button" title="Zoom In" data-method="zoom" data-option="0.1" class="btn btn-sm btn-primary">
                <i class="fa fa-search-plus"></i>
            </button>
            <button type="button" title="Zoom Out" data-method="zoom" data-option="-0.1" class="btn btn-sm btn-primary">
                <i class="fa fa-search-minus"></i>
            </button>
        </div>
        <div class="btn-group" style="margin-bottom: 5px;">
            <button type="button" title="Rotate Left" data-option="-15" data-method="rotate" class="btn btn-sm btn-primary">
                <i class="fa fa-rotate-left"></i>
            </button>
            <button type="button" title="Rotate Right" data-option="15" data-method="rotate" class="btn btn-sm btn-primary">
                <i class="fa fa-rotate-right"></i>
            </button>
        </div>
        <div class="btn-group" style="margin-bottom: 5px;">
            <button type="button" title="Flip Horizontal" data-option="-1" data-method="scaleX" class="btn btn-sm btn-primary">
                <i class="fa fa fa-arrows-h"></i>
                &nbsp;
                Flip
            </button>
            <button type="button" title="Flip Vertical" data-option="-1" data-method="scaleY" class="btn btn-sm btn-primary">
                <i class="fa fa fa-arrows-v"></i>
                &nbsp;
                Flip
            </button>
        </div>
        <div class="btn-group" style="margin-bottom: 5px;">
            <button type="button" title="Move Left" data-second-option="0" data-option="-10" data-method="move" class="btn btn-sm btn-primary">
                <i class="fa fa fa-arrow-left"></i>
                &nbsp;
                Left
            </button>
            <button type="button" title="Move Right" data-second-option="0" data-option="10" data-method="move" class="btn btn-sm btn-primary">
                Right
                &nbsp;
                <i class="fa fa fa-arrow-right"></i>
            </button>
            <button type="button" title="Move Up" data-second-option="-10" data-option="0" data-method="move" class="btn btn-sm btn-primary">
                Up
                &nbsp;
                <i class="fa fa fa-arrow-up"></i>
            </button>
            <button type="button" title="Move Down" data-second-option="10" data-option="0" data-method="move" class="btn btn-sm btn-primary">
                Down
                &nbsp;
                <i class="fa fa fa-arrow-down"></i>
            </button>
        </div>
        <div class="btn-group" style="margin-bottom: 5px;">
            <button type="button" title="Reset Image" data-method="reset" class="btn btn-sm btn-primary">
                <i class="fa fa-refresh"></i>
                &nbsp;
                Reset
            </button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div>
            <img id="TargetCropFloorplan" src="<?= 'https://' . \Cake\Core\Configure::read('Settings.floorplans_container') . '/' . $libraryItem->path . $libraryItem->filename; ?>" style="max-width: 100%;" />
        </div>
    </div>
    <div class="col-md-4">

        <div class="row">
            <div class="col-md-8" style="height: 230px !important;">
                <div style="background: #333; display: inline-block; padding: 2px;">
                    <div class="img-preview preview-lg"></div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-md-1"></div>
            <div class="col-md-7">
                <div class="input-group input-group-sm">
                    <label for="dataWidth" class="input-group-addon">Width</label>
                    <input type="text"  placeholder="width" id="dataWidth" class="form-control">
                    <span class="input-group-addon">px</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-7">
                <div class="input-group input-group-sm">
                    <label for="dataHeight" class="input-group-addon">Height</label>
                    <input type="text"  placeholder="height" id="dataHeight" class="form-control">
                    <span class="input-group-addon">px</span>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 30px;">
            <div class="col-md-12">
                <button onclick="$('#FormSubmitButtonHandle').trigger('click');" target="#CropFloorplanForm" data-dismiss="" data-btn-text="Cropping Floor Plan"  class="submit-the-form btn btn-lg btn-primary btn-block">
                    <i class="fa fa-crop"></i>
                    &nbsp;
                    Crop Image
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->Form->hidden('img_crop_x', ['id' => 'imgCropX']) ?>
<?= $this->Form->hidden('img_crop_y', ['id' => 'imgCropY']) ?>
<?= $this->Form->hidden('img_crop_w', ['id' => 'imgCropW']) ?>
<?= $this->Form->hidden('img_crop_h', ['id' => 'imgCropH']) ?>
<?= $this->Form->hidden('img_crop_r', ['id' => 'imgCropR']) ?>
<?= $this->Form->hidden('img_crop_sx', ['id' => 'imgCropSx']) ?>
<?= $this->Form->hidden('img_crop_sy', ['id' => 'imgCropSy']) ?>



<?= $this->Form->end(); ?>
