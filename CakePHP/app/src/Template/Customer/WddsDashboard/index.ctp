<?php

echo $this->Html->script('heatmap', array('inline' => false));
echo $this->Html->script('//maps.googleapis.com/maps/api/js?key='.\Cake\Core\Configure::read('Settings.goo_api_maps_key').'&libraries=visualization', array('inline' => false));
echo $this->Html->script('gmaps-heatmap', array('inline' => false));
echo $this->Html->script('cluster/markerclusterer', array('inline' => false));
echo $this->Html->script('cluster/jqtooltip', array('inline' => false));
echo $this->Html->css('maps/jqtooltip', ['inline' => false]);


$this->set('pageTitle', 'WddsDashboard');

?>

<style>
    html, body { height:100%; }
    #legend {
        position: relative;
        width: 100%;
        height: 50px;
        margin-top: 20px;
    }

    #legendGradient {
        width: 100%;
        height: 35px;
        border: 1px solid #555;
        z-index: 1;
    }
</style>
<div class="col-md-12">
    <div class="row tile_count">

        <div class="row x_title" style="border-bottom: 1px solid #D9DEE4;">
            <div class="col-lg-7 col-lg-offset-5 col-md-7 col-md-offset-5 col-sm-7 col-sm-offset-5 col-xs-8 col-xs-offset-4">
                <h3>Wifi Device Detection System Dashboard</h3>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-6 col-sm-6 col-xs-12 tile_stats_count hidden-lg hidden-md">
            <div class="right center" style="margin-left:calc(50% - 105px);">
                <span class="count_top"><i class="fa fa-user"></i> Todays Scan Results</span>
                <div class="count"><?= $this->Number->format($totalScanCount); ?></div>
                <span class="count_bottom"><i class="<?= $d['dir']; ?>"><i class="fa fa-sort-<?= $d['arr']; ?>"></i><?= round($d['chg'],1); ?>% </i> from <span style="border-bottom: dashed 1px #999;" title="Up to <?= date('m/d/Y g:ia', strtotime('-1 day')); ?>">yesterday</span></span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-6 col-sm-6 col-xs-12 tile_stats_count hidden-lg hidden-md">
            <div class="right center" style="margin-left:calc(50% - 105px);">
                <span class="count_top"><i class="fa fa-mobile-phone"></i> Total Access Points</span>
                <div class="count"><a href="/admin/devices"><?= $this->Number->format($accessPointsCount); ?></a></div>
                <span class="count_bottom"></span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-6 col-sm-6 col-xs-12 tile_stats_count hidden-lg hidden-md">
            <div class="right center" style="margin-left:calc(50% - 105px);">
                <span class="count_top"><i class="fa fa-calendar"></i> Weekly Impressions</span>
                <div class="count"><?= $this->Number->format($dw['imp']); ?></div>
                <span class="count_bottom"><i class="<?= $dw['dir']; ?>"><i class="fa fa-sort-<?= $dw['arr']; ?>"></i><?= round($dw['chg'],1); ?>% </i> from last week</span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-6 col-sm-6 col-xs-12 tile_stats_count hidden-lg hidden-md">
            <div class="right center" style="margin-left:calc(50% - 105px);">
                <span class="count_top"><i class="fa fa-user"></i> Total Scan Results</span>
                <div class="count"><a href="/admin/impressions"><?= $this->Number->format($totalScanCount) ?></a></div>
                <span class="count_bottom"></span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-6 col-sm-6 col-xs-12 tile_stats_count hidden-lg hidden-md">
            <div class="right center" style="margin-left:calc(50% - 105px);">
                <span class="count_top"><i class="fa fa-location-arrow"></i> Total Unique Devices</span>
                <div class="count"><a href="/admin/users/unverified">><?= $this->Number->format($totalUniqueDevicesCount) ?></a></div>
                <span class="count_bottom"></span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-3 col-sm-6 col-xs-12 tile_stats_count hidden-lg hidden-md">
            <div class="right center" style="margin-left:calc(50% - 105px);">
                <span class="count_top"><i class="fa fa-location-arrow"></i> Total Unique Vendors</span>
                <div class="count"><a href="/admin/users/unverified"><?= $this->Number->format($totalUniqueVendors) ?></a></div>
                <span class="count_bottom"></span>
            </div>
        </div>

        <!-- separator -->
        <div class="animated flipInY col-lg-2 col-md-3 col-sm-6 col-xs-12 tile_stats_count hidden-sm hidden-xs">
            <div class="left"></div>
            <div class="right center">
                <span class="count_top"><i class="fa fa-user"></i> Todays Scan Results</span>
                <div class="count"><?= $this->Number->format($tsc); ?></div>
                <span class="count_bottom"><i class="<?= $d['dir']; ?>"><i class="fa fa-sort-<?= $d['arr']; ?>"></i><?= round($d['chg'],1); ?>% </i> from <span style="border-bottom: dashed 1px #999;" title="Up to <?= date('m/d/Y g:ia', strtotime('-1 day')); ?>">yesterday</span></span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-3 col-sm-6 col-xs-12 tile_stats_count hidden-sm hidden-xs">
            <div class="left"></div>
            <div class="right center">
                <span class="count_top"><i class="fa fa-mobile-phone"></i> Total Access Points</span>
                <div class="count"><a href="/admin/devices"><?= $this->Number->format($accessPointsCount); ?></a></div>
                <span class="count_bottom"></span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-3 col-sm-6 col-xs-12 tile_stats_count hidden-sm hidden-xs">
            <div class="left"></div>
            <div class="right center">
                <span class="count_top"><i class="fa fa-user"></i> Total Scan Results</span>
                <div class="count"><a href="/admin/impressions"><?= $this->Number->format($totalScanCount) ?></a></div>
                <span class="count_bottom"></span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-3 col-sm-6 col-xs-12 tile_stats_count hidden-sm hidden-xs">
            <div class="left"></div>
            <div class="right center">
                <span class="count_top"><i class="fa fa-calendar"></i> Weekly Scan Results</span>
                <div class="count"><?= $this->Number->format($totalScanCountLastWeek); ?></div>
                <span class="count_bottom"><i class="<?= $dw['dir']; ?>"><i class="fa fa-sort-<?= $dw['arr']; ?>"></i><?= round($dw['chg'],1); ?>% </i> from last week</span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-3 col-sm-6 col-xs-12 tile_stats_count hidden-sm hidden-xs">
            <div class="left"></div>
            <div class="right center">
                <span class="count_top"><i class="fa fa-location-arrow"></i> Total Unique Devices</span>
                <div class="count"><a href="/admin/users/unverified"><?= $this->Number->format($totalUniqueDevicesCount) ?></a></div>
                <span class="count_bottom"></span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-3 col-sm-6 col-xs-12 tile_stats_count hidden-sm hidden-xs">
            <div class="left"></div>
            <div class="right center">
                <span class="count_top"><i class="fa fa-calendar"></i> Weekly Unique Devices</span>
                <div class="count"><?= $this->Number->format($totalUniqueDevicesCountLastWeek); ?></div>
                <span class="count_bottom"><i class="<?= $dw['dir']; ?>"><i class="fa fa-sort-<?= $dw['arr']; ?>"></i><?= round($dw['chg'],1); ?>% </i> from last week</span>
            </div>
        </div>
        <div class="animated flipInY col-lg-2 col-md-3 col-sm-6 col-xs-12 tile_stats_count">
            <div class="left hidden-sm hidden-xs"></div>
            <div class="right center">
                <span class="count_top"><i class="fa fa-user"></i> Todays Unique Devices</span>
                <div class="count"><?= $this->Number->format($tudc); ?></div>
                <span class="count_bottom"><i class="<?= $du['dir']; ?>"><i class="fa fa-sort-<?= $du['arr']; ?>"></i><?= round($du['chg'],1); ?>% </i> from <span style="border-bottom: dashed 1px #999;" title="Up to <?= date('m/d/Y g:ia', strtotime('-1 day')); ?>">yesterday</span></span>
            </div>
        </div>        
        <div class="animated flipInY col-lg-2 col-md-3 col-sm-6 col-xs-12 tile_stats_count hidden-sm hidden-xs">
            <div class="left"></div>
            <div class="right center">
                <span class="count_top"><i class="fa fa-location-arrow"></i> Total Unique Vendors</span>
                <div class="count"><a href="/admin/users/unverified"><?= $this->Number->format($totalUniqueVendors) ?></a></div>
                <span class="count_bottom"></span>
            </div>
        </div>
    </div>

</div>


    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph">

                <div class="row x_title">
                    <div class="col-md-6">
                        <h3>Network Activities <small>Graph title sub-title</small></h3>
                    </div>
                    <?= $this->element('Header/daterangepicker',['disabled'=>'disabled']) ?>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div id="totalscanresultsbyvendor"></div>
                        <?= $this->element('Charts/total_scanresults_by_vendor'); ?>
                    </div>

                    <div class="col-md-8 col-xs-12">
                        <div id="hc-2"></div>
                        <?= $this->element('Charts/retailer_performance'); ?>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div id="totalscanresultsbydaylastweek"></div>
                        <?= $this->element('Charts/total_scanresults_by_day_last_week'); ?>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div id="highcharts-container-2"></div>
                        <?= $this->element('Charts/total_scanresults_by_day'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div id="uniquedevicesbydaylastweek"></div>
                        <?= $this->element('Charts/total_uniquedevices_by_day_last_week'); ?>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


<script>
    var map, heatmap;
    var infowindow = new google.maps.InfoWindow();

    function initMap() {
        map = new google.maps.Map(document.getElementById('heatmapArea'), {
            zoom: 5,
            center: {lat: 40.713129, lng: -97.470701},
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        getPoints();
    }

    function toggleHeatmap() {
        heatmap.setMap(heatmap.getMap() ? null : map);
    }


    function changeGradient() {
        var gradient = [
            'rgba(0, 255, 255, 0)',
            'rgba(0, 255, 255, 1)',
            'rgba(0, 191, 255, 1)',
            'rgba(0, 127, 255, 1)',
            'rgba(0, 63, 255, 1)',
            'rgba(0, 0, 255, 1)',
            'rgba(0, 0, 223, 1)',
            'rgba(0, 0, 191, 1)',
            'rgba(0, 0, 159, 1)',
            'rgba(0, 0, 127, 1)',
            'rgba(63, 0, 91, 1)',
            'rgba(127, 0, 63, 1)',
            'rgba(191, 0, 31, 1)',
            'rgba(255, 0, 0, 1)'
        ]
        heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
    }

    function changeRadius() {
        heatmap.set('radius', heatmap.get('radius') ? null : 20);
    }

    function changeOpacity() {
        heatmap.set('opacity', heatmap.get('opacity') ? null : 0.2);
    }

    // Heatmap data: 500 Points
    function getPoints() {

        var points = [];
        var markers = [];


        $.ajax({
            type: 'GET',
            url: '/admin/impressions/heatmap',
            cache: true,
            success: function (response) {

                var json = (response);


                var TTWindows = {};
                var i = 0;

                $(json.data).each(function (i, v) {

                    c = v.count;

                    var weighted = {
                        location: new google.maps.LatLng(v.lat, v.lng),
                        weight: c
                    };

                    points.push(new google.maps.LatLng(v.lat, v.lng));

                    if (v.has_icon) {
                        var markerImage = new google.maps.MarkerImage(v.icon,
                            new google.maps.Size(32, 32));
                    } else {
                        var markerImage = new google.maps.MarkerImage('/img/public/mapping/pin.png',
                            new google.maps.Size(32, 32));
                    }


                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(v.lat,v.lng),
                        draggable: false,
                        icon: markerImage
                    });


                    var MarkerContent = '<div class="MarkerContentTT">' +
                            '<span class="CLOSE_INFO_WINDOW"><i class="fa fa-fw fa-times-circle"></i></span>' +
                            '<p><strong>Retailer: </strong> ' + v.retailer + '</p>' +
                            '<p>' + v.address + '</p>' +
                            '<p></p>' +
                            '<p><strong>Impressions: </strong> ' + v.count + '</p>' +
                            '<p><strong>Last Imp.: </strong> ' + v.last_impression + '</p>' +
                        '</div>'



                    google.maps.event.addListener(marker, "click", function(e) {


                        var configtooltip = {
                            position: "tipbottom",
                            content: MarkerContent,
                            event: "click",
                            _mouseleave: false,
                            theme: "tipthemed2go",
                            followcursor: false,
                            eventout: "click",
                            animation:"swingX",
                            animationout:"swingYclose"
                        };

                        TTWindows[i++] = new InfoBox({latlng: marker.getPosition(), map: map, tooltip: configtooltip});

                    });



                    markers.push(marker);




                });

                heatmap = new google.maps.visualization.HeatmapLayer({
                    data: points,
                    dissipating: true,
                    map: map
                });

                var options = {
                    imagePath: '/img/public/mapping/cluster/m'
                };

                var markerCluster = new MarkerClusterer(map, markers, options);

            }
        });

    }


    initMap();

</script>
