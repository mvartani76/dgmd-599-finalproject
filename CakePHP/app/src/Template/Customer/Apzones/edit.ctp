<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Apzone $apzone
 */
?>
<?php $this->set('pageTitle', 'Edit Apzone'); ?>
<?= $this->Form->create($apzone) ?>
<div class="x_panel">
    <div class="x_title">
        <h1><?= __('Edit Apzone #{0}', $apzone->id) ?></h1>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="users add-form col-md-12">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3 col-md-3 col-sm-3"></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <?= $this->Form->input('location_id', ['label' => 'Location ID', 'options' => $locations, 'class' => 'form-control', 'default' => $apzone->location_id])?>
                            <?= $this->Form->input('accesspoint_id', ['label' => 'Access Point ID', 'options' => $access_points, 'class' => 'form-control', 'default' => $apzone->accesspoint_id])?>
                            <?= $this->Form->input('fixture_no', ['label' => 'Fixture #', 'class' => 'form-control'])?>
                            <?= $this->Form->input('placement', ['label' => 'Placement', 'class' => 'form-control'])?>
                            <?= $this->Form->input('floor', ['label' => 'Floor', 'class' => 'form-control'])?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3 col-md-3 col-sm-3"></div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->button('', ['style' => 'display: none;', 'type' => 'submit']) ?>
                            <?= $this->Form->button(__('<i class="fa fa-times-circle"></i> &nbsp; Cancel'), ['name' => '_CANCEL', 'class' => 'pull-right btn btn-default']) ?>
                            <?= $this->Form->button(__('<i class="fa fa-plus-circle"></i> &nbsp;Submit'), ['class' => 'pull-right btn btn-primary']) ?>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </section>
    </div>
</div>
