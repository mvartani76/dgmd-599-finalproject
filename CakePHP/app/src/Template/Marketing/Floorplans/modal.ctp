<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">
        Content
        &nbsp;
        <span id="content-modal-content-count" class="badge"><?= $totalCustomerContentCount ?> Content Items Available</span>
        <?php if ($totalCustomerContentCount !== $totalFilterContentCount): ?>
        <span id="content-modal-content-filter-count" class="badge"><?= $totalFilterContentCount ?> Filtered</span>
        <?php endif; ?>
    </h4>
    <form id="form-content-modal-filters" class="form-horizontal" method="GET" action="<?= $this->request->here ?>">
        <input type="hidden" name="page" value="<?= $this->Paginator->current() ?>">
        <div class="row">
            <div class="col-xs-4">
                <?= $this->Form->control('keywords', [
                    'class' => 'form-control',
                    'value' => $keywords,
                ]) ?>
            </div>
            <div class="col-xs-4" style="padding-top: 20px">
                <?= $this->Form->control('current_user_only', [
                    'label' => 'Only show my content?',
                    'type' => 'checkbox',
                    'hiddenField' => false,
                    'checked' => $currentUserOnly ? true : false,
                ]) ?>
            </div>
            <div class="col-xs-4 text-right" style="padding-top: 20px">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-select-content hide">Select</button>
            </div>
        </div>
    </form>
</div>
<div class="modal-body">
    <div class="clearfix">
        <?php /*
        We need to print the content items seperately for every page so that the
        landscape ones are returned last.  That way it stacks nicer in the modal.
        */ ?>
        <?php foreach($content as $item): ?>
        <?php if ($item->getOrientation() === 'landscape') continue; ?>
        <a class="content-preview-modal <?= $item->getOrientation() ?>" href="#content-<?= $item->id ?>" data-id="<?= $item->id ?>">
            <?= $this->MobilePreview->preview($item) ?>
        </a>
        <?php endforeach; ?>

        <?php foreach($content as $item): ?>
        <?php if ($item->getOrientation() === 'portrait') continue; ?>
        <a class="content-preview-modal <?= $item->getOrientation() ?>" href="#content-<?= $item->id ?>" data-id="<?= $item->id ?>">
            <?= $this->MobilePreview->preview($item) ?>
        </a>
        <?php endforeach; ?>
    </div>

    <?= $this->element('paginator') ?>
</div>
