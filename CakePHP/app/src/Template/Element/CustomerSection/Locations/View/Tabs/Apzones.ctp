<div role="tabpanel" class="tab-pane fade" id="tab_content9" aria-labelledby="profile-tab">

    <div class="paginate-ajax-container">
        <?php $this->start('paginated_content.Apzones'); ?>
        <?php if (is_object($apzones) && !$apzones->isEmpty()): ?>
            <?php
                $this->Paginator->setAjax();
                $this->Paginator->options(
                [
                    'url' => array_merge($this->request->pass, ['ajax' => true, 'mdl' => 'Apzones'], $this->request->query)
                ]
            );
            ?>

            <div class="callout callout-info">
                <?= $this->Paginator->counter([
                    'format' => 'Page {{page}} of {{pages}} - {{count}} total records', 'model' => 'Zones'
                ]) ?>
            </div>
            <div  class="col-md-12 col-sm-12 col-xs-12 table-custom">
                <table id="APzonesData" class="table-hover table-striped location-content">
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id', 'Apzone ID', ['model' => 'Apzones']) ?></th>
                        <th><?= $this->Paginator->sort('location.location', 'Location Name<br/><small>(brand or subsidiary)</small>', ['model' => 'Zones', 'escape' => false]) ?></th>
                        <th><?= $this->Paginator->sort('access_point.mac_addr', 'MAC Address', ['model' => 'Apzones']) ?></th>
                        <th><?= $this->Paginator->sort('placement', 'Placement', ['model' => 'Apzones']) ?></th>
                        <th><?= $this->Paginator->sort('floor', 'Floor', ['model' => 'Apzones']) ?></th>
                        <th><?= $this->Paginator->sort('total_devices_count', 'Devices for this Apzone<br/>', ['model' => 'Apzones', 'escape' => false]) ?></th>
                        <th class="actions" style="text-align: right; width: 233px;"><?= __('Actions') ?></th>
                    </tr>
                    </thead>

                    <?php foreach($apzones as $apzone): ?>

                        <?php

                        $data = $apzone->toArray();

                        $placement = \Cake\Utility\Text::truncate($apzone->placement, 150);

                        ?>

                        <tr>
                            <td data-title="Apzone ID"><?= $this->Number->format($apzone->id) ?></td>
                            <td data-title="Location Name"><?= h($apzone->location->location) ?></td>
                            <td data-title="MAC Address"><?= h(join(':', str_split($apzone->access_point->mac_addr,2))) ?></td>
                            <td data-title="Placement"><p style="max-width: 170px;" title="<?= $apzone->placement ?>"><?= h($placement) ?></p></td>
                            <td data-title="Floor"><?= h($apzone->floor) ?></td>
                            <td data-title="Scan Results"><a title="View this entry's scan results" href="/customer/impressions/viewby/zone/<?= $apzone->id ?>"><?= $this->Number->format($apzone->total_devices_count) ?></a></td>

                            <td data-title="Actions" class="actions" style="width: 233px;">
                                <?= $this->Html->link('<i class="fa fa-search"></i>&nbsp;View', ['controller' => 'apzones', 'action' => 'view', $apzone->id], ['class' => 'btn btn-primary btn-xs', 'escape' => false]); ?>
                                <?= $this->Html->link('<i class="fa fa-pencil"></i>&nbsp;Edit', ['controller' => 'apzones', 'action' => 'edit', $apzone->id], ['class' => 'btn btn-default btn-xs', 'escape' => false]); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>


                <?= $this->element('paginator', ['model' => 'Apzones', 'isAjax' => true]) ?>
            </div>
        <?php else: ?>
            <ul id="ApzonesResults" class="messages">
                <li class="no-data">
                    <p style="color: #aaa !important; text-align: center;">There are no zones to display for this particular location.</p>
                </li>
            </ul>
        <?php endif; ?>
        <?php $this->end(); ?>
        <?php echo $this->fetch('paginated_content.Apzones'); ?>

    </div>
</div>
