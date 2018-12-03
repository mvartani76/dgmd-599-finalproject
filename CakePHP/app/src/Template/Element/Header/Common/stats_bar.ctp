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
        <!-- Only display zone related tiles if there are zones. -->
        <?php if ($LocationZonesCount > 0): ?>
            <!-- Update CSS if both APZones and Zones exist -->
            <?php if ($LocationAPZonesCount > 0): ?>
                <div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <?php else: ?>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php endif; ?>
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-crosshairs"></i>
                    </div>
                    <div class="count">
                        <a href="#" id="ZonesCount">
                            0
                        </a>
                    </div>
                    <!-- Make Box Title Smaller if displaying both Zone/APZone Values -->
                    <?php if ($LocationAPZonesCount > 0): ?>
                        <h4>Defined Zones</h4>
                    <?php else: ?>
                        <h3>Defined Zones</h3>
                    <?php endif; ?>
                    <p>&nbsp;</p>
                </div>
            </div>
            <!-- Update CSS if both APZones and Zones exist -->
            <?php if ($LocationAPZonesCount > 0): ?>
                <div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <?php else: ?>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php endif; ?>
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-location-arrow"></i>
                    </div>
                    <div class="count">
                        <a href="#" id="ImpressionsCount">
                            0
                        </a>
                    </div>
                    <!-- Make Box Title Smaller if displaying both Zone/APZone Values -->
                    <?php if ($LocationAPZonesCount > 0): ?>
                        <h4>Impressions for time period</h4>
                    <?php else: ?>
                        <h3>Impressions for time period</h3>
                    <?php endif; ?>
                    <p class="time-filter-period">Last 7 days</p>
                </div>
            </div>
            <!-- Update CSS if both APZones and Zones exist -->
            <?php if ($LocationAPZonesCount > 0): ?>
                <div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <?php else: ?>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php endif; ?>
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-mobile-phone"></i>
                    </div>
                    <div class="count">
                        <a href="#" id="DevicesCount">
                            0
                        </a>
                    </div>
                    <!-- Make Box Title Smaller if displaying both Zone/APZone Values -->
                    <?php if ($LocationAPZonesCount > 0): ?>
                        <h4>Unique Devices</h4>
                    <?php else: ?>
                        <h3>Unique Devices</h3>
                    <?php endif; ?>
                    <p class="time-filter-period">Last 7 days</p>
                </div>
            </div>
        <?php endif; ?>
        <!-- Only display apzone related tiles if there are apzones. -->
        <?php if ($LocationAPZonesCount > 0): ?>
            <!-- Update CSS if both APZones and Zones exist -->
            <?php if ($LocationZonesCount > 0): ?>
                <div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <?php else: ?>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php endif; ?>
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-crosshairs"></i>
                    </div>
                    <div class="count">
                        <a href="#" id="APZonesCount">
                            0
                        </a>
                    </div>
                    <!-- Make Box Title Smaller if displaying both Zone/APZone Values -->
                    <?php if ($LocationZonesCount > 0): ?>
                        <h4>Defined APZones</h4>
                    <?php else: ?>
                        <h3>Defined APZones</h3>
                    <?php endif; ?>
                    <p>&nbsp;</p>
                </div>
            </div>
            <!-- Update CSS if both APZones and Zones exist -->
            <?php if ($LocationZonesCount > 0): ?>
                <div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <?php else: ?>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php endif; ?>
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-location-arrow"></i>
                    </div>
                    <div class="count">
                        <a href="#" id="ScanResultsCount">
                            0
                        </a>
                    </div>
                    <!-- Make Box Title Smaller if displaying both Zone/APZone Values -->
                    <?php if ($LocationZonesCount > 0): ?>
                        <h4>Scan Results for time period</h4>
                    <?php else: ?>
                        <h3>Scan Results for time period</h3>
                    <?php endif; ?>
                    <p class="time-filter-period">Last 7 days</p>
                </div>
            </div>
            <!-- Update CSS if both APZones and Zones exist -->
            <?php if ($LocationZonesCount > 0): ?>
                <div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <?php else: ?>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <?php endif; ?>
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-mobile-phone"></i>
                    </div>
                    <div class="count">
                        <a href="#" id="UniqueScanResultsCount">
                            0
                        </a>
                    </div>
                    <!-- Make Box Title Smaller if displaying both Zone/APZone Values -->
                    <?php if ($LocationZonesCount > 0): ?>
                        <h4>Unique Scan Results</h4>
                    <?php else: ?>
                        <h3>Unique Scan Results</h3>
                    <?php endif; ?>
                    <p class="time-filter-period">Last 7 days</p>
                </div>
            </div>
    <?php endif; ?>
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
<?php if ($type == 'accesspoints'): ?>
    <div class="top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-cube" style="font-size:48px;"></i>
                </div>
                <div class="count">
                    <a href="#" id="TotalScanCount">
                        0
                    </a>
                </div>
                <div class="ap-tile-stats">
                    <h3>Total Scan Results</h3>
                </div>
                <p>&nbsp;</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-cubes" style="font-size:48px;"></i></div>
                <div class="count">
                    <a href="#" id="TotalUniqueDevices">
                        0
                    </a>
                </div>
                <div class="ap-tile-stats">
                    <h3>Total Unique Devices</h3>
                </div>
                <p>&nbsp;</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-location-arrow" style="font-size:48px;"></i></div>
                <div class="count">
                    <a href="#" data-map="/customer/scanresults/viewby/accesspoint/<?= $accessPoint->id ?>?stdt={START}&nddt={END}" id="TotalScanCount_time">
                        0
                    </a>
                </div>
                <div class="ap-tile-stats">
                    <h3>Scan Results for Time Period</h3>
                </div>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-cubes" style="font-size:48px;"></i></div>
                <div class="count">
                    <a href="#" data-map="/customer/scanresults/viewby/accesspoint/<?= $accessPoint->id ?>?stdt={START}&nddt={END}" id="TotalUniqueDevices_time">
                        0
                    </a>
                </div>
                <div class="ap-tile-stats">
                    <h3>Unique Devices for Time Period</h3>
                </div>
                <p class="time-filter-period">Last 7 days</p>
            </div>
        </div>
    </div>
<?php endif; ?>
