<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ScanResult[]|\Cake\Collection\CollectionInterface $scanResults
 */
?>
<style>
    td.scantime {
        flex-direction: row-reverse;
    }
</style>

<?php $this->set('pageTitle', 'Scan Results'); ?>

<div class="x_panel">
    <div class="x_title">
        <h2>These are the recorded scan results</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
         <section class="content">
            <div class="row">
                <div class="scanResults index col-md-12 col-sm-12 col-xs-12 table-custom">
                    <table class="table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('accesspoint_id') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('mac_addr') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('vendor') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('rssi') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('scan_timestamp') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scanResults as $scanResult): ?>
                                <tr>
                                    <td data-title="Access Point MAC Address"><?= h($scanResult['payload']['ap_mac_addr']) ?></td>
                                    <td data-title="MAC Address"><?= h($scanResult['payload']['mac_addr']) ?></td>
                                    <td data-title="Vendor"><?= h($scanResult['payload']['vendor']) ?></td>
                                    <td data-title="RSSI"><?= $this->Number->format($scanResult['payload']['rssi']) ?></td>
                                    <td class = "scantime" data-title="Timestamp"><?= h(gmdate("F j, Y, g:i a", $scanResult['log_time']/1000)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <!-- Create a link to effectively paginate the data. This will pass back the LastEvaluatedKey to the controller to rescan the dynamodB table with these values. --->

                    <!-- Do not display the Previous link if on the first page (page 0) -->
                    <?php if ($page > 0): ?>
                        <?php echo $this->Html->link('Previous', ['controller' => 'ScanResults', 'action' => 'index', '?' => ['page' => ($page-1), 'key' => serialize($prevlastvalkey)]], ['class' => 'pull-left btn btn-primary']); ?>
                    <?php endif; ?>

                    <!-- Only display the Next link if there are more results. -->
                    <?php if ($lastevalkey != null): ?>
                        <?php echo $this->Html->link('Next', ['controller' => 'ScanResults', 'action' => 'index', '?' => ['page' => ($page+1), 'key' => serialize($lastevalkey)]], ['class' => 'pull-right btn btn-primary']); ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</div>
