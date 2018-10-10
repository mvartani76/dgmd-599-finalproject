<?php if ($type == 'geofences'): ?>
    <div class="top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-building"></i>
                </div>
                <div class="count">
                    <a href="/admin/impressions/viewby/geofence/<?= $geofence->id ?>?stdt=<?= $periodStartRaw ?>&nddt=<?= $periodEndRaw ?>"
                       data-map="/admin/impressions/viewby/geofence/<?= $geofence->id ?>?stdt={START}&nddt={END}"
                       id="ImpressionsCount">
                        0
                    </a>
                </div>

                <h3>Total Impressions</h3>
                <p>For this Geofence</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-location-arrow"></i>
                </div>
                <div class="count">
                    <a href="/admin/impressions/viewby/geofence/<?= $geofence->id ?>?stdt=<?= $periodStartRaw ?>&nddt=<?= $periodEndRaw ?>"
                       data-map="/admin/impressions/viewby/geofence/<?= $geofence->id ?>?stdt={START}&nddt={END}"
                       id="DevicesCount">
                        0
                    </a>
                </div>

                <h3>Unique Devices</h3>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-mobile-phone"></i>
                </div>
                <div class="count">
                    <a href="/admin/impressions/viewby/geofence/<?= $geofence->id ?>?stdt=<?= $periodStartRaw ?>&nddt=<?= $periodEndRaw ?>"
                       data-map="/admin/impressions/viewby/geofence/<?= $geofence->id ?>?stdt={START}&nddt={END}"
                       id="Impressions7Count">
                        0
                    </a>
                </div>

                <h3>Impressions for time period</h3>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($type == 'beacons'): ?>
    <div class="top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-building"></i>
                </div>
                <div class="count">
                    <a href="/admin/impressions/viewby/beacon/<?= $beacon->id ?>?stdt=<?= $periodStartRaw ?>&nddt=<?= $periodEndRaw ?>"
                       data-map="/admin/impressions/viewby/beacon/<?= $beacon->id ?>?stdt={START}&nddt={END}"
                       id="ImpressionsCount">
                        0
                    </a>
                </div>

                <h3>Total Impressions</h3>
                <p>For this Beacon</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-wifi"></i></div>
                <div class="count">
                    <a href="<?= $this->Url->build([
                        'controller' => 'Campaigns',
                        'action' => 'index',
                        '?' => [
                            'beacon_major' => (int) $beacon->major,
                            'beacon_minor' => (int) $beacon->minor_dec,
                            'stdt' => $periodStartRaw,
                            'nddt' => $periodEndRaw,
                        ]
                    ]) ?>"
                       data-map="/admin/impressions/viewby/beacon/<?= $beacon->id ?>?stdt={START}&nddt={END}"
                       id="CampaignsCount">
                        0
                    </a>
                </div>

                <h3>Active Campaigns</h3>
                <p>Tied to this Beacon</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-location-arrow"></i>
                </div>
                <div class="count">
                    <a href="<?= $this->Url->build([
                        'controller' => 'Devices',
                        'action' => 'index',
                        '?' => [
                            'beacon_id' => (int) $beacon->id,
                            'stdt' => $periodStartRaw,
                            'nddt' => $periodEndRaw,
                        ]
                    ]) ?>"
                       data-map="/admin/impressions/viewby/beacon/<?= $beacon->id ?>?stdt={START}&nddt={END}"
                       id="DevicesCount">
                        0
                    </a>
                </div>

                <h3>Unique Devices</h3>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-mobile-phone"></i>
                </div>
                <div class="count">
                    <a href="/admin/impressions/viewby/beacon/<?= $beacon->id ?>?stdt=<?= $periodStartRaw ?>&nddt=<?= $periodEndRaw ?>"
                       data-map="/admin/impressions/viewby/beacon/<?= $beacon->id ?>?stdt={START}&nddt={END}"
                       id="Impressions7Count">
                        0
                    </a>
                </div>

                <h3>Impressions for time period</h3>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($type == 'locations'): ?>
    <div class="top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-crosshairs"></i>
                </div>
                <div class="count">
                    <a href="#" id="ZonesCount">
                        0
                    </a>
                </div>

                <h3>Defined Zones</h3>
                <p>&nbsp;</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-location-arrow"></i>
                </div>
                <div class="count">
                    <a href="#" id="ImpressionsCount">
                        0
                    </a>
                </div>

                <h3>Impressions for time period</h3>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-mobile-phone"></i>
                </div>
                <div class="count">
                    <a href="#" id="DevicesCount">
                        0
                    </a>
                </div>

                <h3>Unique Devices</h3>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($type == 'retailers'): ?>
    <div class="top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-building"></i>
                </div>
                <div class="count">
                    <a href="#" id="LocationsCount">
                        0
                    </a>
                </div>

                <h3>Defined Locations</h3>
                <p>With at least 1 beacon</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-bluetooth"></i></div>
                <div class="count">
                    <a href="#" id="BeaconsCount">
                        0
                    </a>
                </div>

                <h3>Beacons</h3>
                <p>Across all locations</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-map-marker"></i></div>
                <div class="count">
                    <a href="#" id="GeofencesCount">
                        0
                    </a>
                </div>

                <h3>Geofences</h3>
                <p>Across all locations</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-location-arrow"></i>
                </div>
                <div class="count">
                    <a href="#" id="ImpressionsCount">
                        0
                    </a>
                </div>

                <h3>Beacon Impressions</h3>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-location-arrow"></i>
                </div>
                <div class="count">
                    <a href="#" id="GeofenceImpressionsCount">
                        0
                    </a>
                </div>

                <h3>Geofence Impressions</h3>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-mobile-phone"></i>
                </div>
                <div class="count">
                    <a href="#" id="DevicesCount">
                        0
                    </a>
                </div>

                <h3>Unique Devices</h3>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($type == 'customers'): ?>
    <div class="top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-cube"></i>
                </div>
                <div class="count">
                    <a href="/admin/campaigns/customer/<?= $customer->id ?>/active" id="ActiveCampaignsCount">
                        0
                    </a>
                </div>

                <h3>Active Campaigns</h3>
                <p>&nbsp;</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-cubes"></i></div>
                <div class="count">
                    <a href="/admin/campaigns/customer/<?= $customer->id ?>/all" id="AllCampaignsCount">
                        0
                    </a>
                </div>

                <h3>All Campaigns</h3>
                <p>&nbsp;</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-location-arrow"></i>
                </div>
                <div class="count">
                    <a href="#" id="ImpressionsCount">
                        0
                    </a>
                </div>

                <h3>Total Impressions</h3>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
    </div>
<?php endif; ?>
