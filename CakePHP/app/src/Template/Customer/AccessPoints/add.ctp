<?php echo $this->Html->script('/js/country_region', ['block' => 'scriptBottom']); ?>
<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key='.\Cake\Core\Configure::read('Settings.goo_api_maps_key'), ['block' => 'scriptBottom', 'defer' => true, 'async' => true]); ?>


<?php $this->set('pageTitle', 'Add New Beacon'); ?>
<?= $this->Form->create($beacon) ?>
<div class="x_panel">
    <div class="x_title">
        <h2>Beacons <small>add new beacon</small></h2>
        <p><small>Adds a single beacon with a NEW location to the database - used for testing individual beacons</small></p>
        <p><small>To add a new beacon to an existing location <a href="/customer/locations">click here</a> and view the location, then click "Add a Beacon to this location"</small></p>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="users add-form col-md-9 form-horizontal form-label-left">
                <div class="row">

                        <div class="form-group">
                            <?= $this->Form->label('Beacons.minor_dec', 'Beacon Minor', ['class' => 'control-label col-md-2 col-sm-4 col-xs-12'] ); ?>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <?= $this->Form->input('Beacons.minor_dec', ['label' => false, 'class' => 'form-control col-md-12 col-xs-12']);?>
                            </div>
                        </div>
                </div>
                <br />
            </div>
        </section>
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <h2>Location <small>add new location</small></h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="users add-form col-md-12 form-horizontal form-label-left">
                <div class="row">

                    <div class="form-group">
                        <?= $this->Form->label('Locations.regional_name', 'Regional Name', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                            <?= $this->Form->input('Locations.regional_name',      ['placeholder' => 'ie: JFK Intl Airport (optional)', 'class' => 'form-control col-md-7 col-xs-12', 'label' => false]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= $this->Form->label('Locations.name', 'Location Name', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                            <?= $this->Form->input('Locations.location',      ['placeholder' => 'ie: Best Buy', 'class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'required' => true]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= $this->Form->label('Locations.store_no', 'Store Number', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                            <?= $this->Form->input('Locations.main_phone',      ['placeholder' => 'ie: 408 (optional)', 'class' => 'form-control col-md-7 col-xs-12', 'label' => false]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= $this->Form->label('Locations.country_id', 'Full Address', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                            <?= $this->Form->input('Locations.country_id',      ['options' => $countries, 'empty' => 'Select Country', 'class' => 'source-country-list form-control col-md-7 col-xs-12', 'label' => false, 'required' => true]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= $this->Form->label('Locations.placetype_id', 'Placetype', ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'] ); ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?= $this->Form->input('Locations.placetype_id',['options' => $placetypes, 'empty' => 'Set a PlaceType' ,'class' => 'form-control col-md-7 col-xs-12', 'label' => false]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-6"></label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                            <?= $this->Form->input('Locations.address1',      ['placeholder' => 'Address line 1', 'class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'required' => true]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-6"></label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                            <?= $this->Form->input('Locations.address2',      ['placeholder' => 'Address line 2 (optional)', 'class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'required' => true]); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-6"></label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                            <?= $this->Form->input('Locations.address3',      ['placeholder' => 'Address line 3 (optional)', 'class' => 'form-control col-md-7 col-xs-12', 'label' => false]); ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-6"></label>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?= $this->Form->input('Locations.city',      ['placeholder' => 'City', 'class' => 'form-control', 'label' => false, 'required' => true]); ?>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?= $this->Form->input('Locations.region_id',      ['empty' => 'State/Province', 'class' => 'form-control target-region-list', 'label' => false]); ?>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?= $this->Form->input('Locations.postal_code',      ['placeholder' => 'Postal Code', 'class' => 'form-control', 'label' => false]); ?>
                        </div>


                    </div>

                    <div class="form-group">
                        <?= $this->Form->label('Zones.placement', 'Description', ['class' => 'control-label col-md-3 col-sm-3 col-xs-6'] ); ?>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                            <?= $this->Form->input('Zones.placement',      ['placeholder' => 'On my desk in the office...', 'class' => 'form-control col-md-6 col-xs-12', 'label' => false]); ?>
                        </div>

                    </div>

                    <div class="form-group">
                         <?= $this->Form->label('Locations.lat', 'Latitude', ['class' => 'control-label col-md-3 col-sm-3 col-xs-6'] ); ?>
                          <div class="col-md-6 col-sm-9 col-xs-12">
                               <?= $this->Form->input('Locations.lat',      ['type' => 'text', 'placeholder' => '-45.4827', 'class' => 'form-control col-md-6 col-xs-12', 'label' => false]); ?>
                           </div>
                     </div>

                    <div class="form-group">
                       <?= $this->Form->label('Locations.lng', 'Longitude', ['class' => 'control-label col-md-3 col-sm-3 col-xs-6'] ); ?>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                           <?= $this->Form->input('Locations.lng',      ['type' => 'text', 'placeholder' => '110.1240', 'class' => 'form-control col-md-6 col-xs-12', 'label' => false]); ?>
                         </div>
                     </div>
                    <hr>

                </div>
                <br />
                <div class="row">

                    <div class="form-group">
                        <div class="col-md-3 col-md-3 col-sm-3"></div>
                        <div class="col-md-6 col-xs-12">

                            <?= $this->Form->button('', ['style' => 'display: none;', 'type' => 'submit']) ?>
                            <?= $this->Form->button(__('<i class="fa fa-times-circle"></i> &nbsp; Cancel'), ['name' => '_CANCEL', 'class' => 'pull-right btn btn-default']) ?>
                            <?= $this->Form->button(__('<i class="fa fa-plus-circle"></i> &nbsp;Create Beacon'), ['class' => 'pull-right btn btn-primary']) ?>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </section>
    </div>
</div>