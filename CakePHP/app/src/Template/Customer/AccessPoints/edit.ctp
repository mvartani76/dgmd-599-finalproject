<?php $this->set('pageTitle', 'Edit Beacon'); ?>
<?= $this->Form->create($beacon) ?>
<div class="x_panel">
    <div class="x_title">
        <h2>Beacons <small>edit beacon</small></h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="users add-form col-md-12">
                <div class="row">

                    <div class="form-group">
                        <?= $this->Form->label('minor_dec', 'Beacon Minor', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('minor_dec', ['label' => false, 'class' => 'form-control col-md-3 col-xs-12']);?>
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
                            <?= $this->Form->button(__('<i class="fa fa-plus-circle"></i> &nbsp;Create Beacon'), ['class' => 'pull-right btn btn-primary']) ?>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
    </div>
    </section>
</div>
</div>