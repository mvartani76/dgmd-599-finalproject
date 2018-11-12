<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AccessPoint $accessPoint
 */
?>

<?php $this->set('pageTitle', 'Edit Access Point'); ?>
<?= $this->Form->create($accessPoint) ?>
<div class="x_panel">
    <div class="x_title">
        <h2>Access Points <small>edit access point</small></h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="users add-form col-md-12">
                <div class="row">
                    <div class="form-group">
                        <?= $this->Form->label('mac_addr', 'MAC Address', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <?= $this->Form->input('mac_addr', ['label' => false, 'error' => false, 'class' => 'form-control']);?>
                            <!-- Insert Error Message below input form field -->
                            <?php if ($this->Form->isFieldError('mac_addr')): ?>
                                <?= $this->Form->error('mac_addr') ?>
                            <?php endif; ?>
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
    </div>
    </section>
</div>
</div>