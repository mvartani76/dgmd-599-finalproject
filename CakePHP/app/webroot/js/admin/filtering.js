$(function() {
    $( ".apply-dateRange" ).bind( "click", function() {
        console.log("enter apply-dateRange");
        //Send to server converted to UTC.
        var start_date =  moment($('.start-range-date').data('DateTimePicker').date()).tz('UTC').format('MM/DD/YYYY');
        var end_date = moment($('.end-range-date').data('DateTimePicker').date()).tz('UTC').format('MM/DD/YYYY');
        var ajaxData = {};
        if(typeof BEACON_ID !== "undefined") {
            var ajaxData = {
                starttime: start_date,
                endtime: end_date,
                action: 'updateStats',
                beaconId: BEACON_ID
            };
        }
        if(typeof LOCATION_ID !== "undefined"){
            var ajaxData = {
                starttime: start_date,
                endtime:   end_date,
                action:    'updateStats',
                retailerId: LOCATION_ID
            };
        }
        if(typeof RETAILER_ID !== "undefined"){
            var ajaxData = {
                starttime: start_date,
                endtime:   end_date,
                action:    'updateStats',
                retailerId: RETAILER_ID
            };
        }
        if(typeof ACCESS_POINT_ID !== "undefined"){
            var ajaxData = {
                starttime: start_date,
                endtime:   end_date,
                action:    'updateStats',
                accesspointId: ACCESS_POINT_ID
            };
        }        

        $.post(STATS_URL + '.json',ajaxData)
            .done(function (r) {
                var data = r;
                var commaStep = $.animateNumber.numberStepFactories.separator(',');
                if(typeof BEACON_ID !== "undefined") {
                    $('#ImpressionsCount').animateNumber({numberStep: commaStep, number: data.it});
                    $('#CampaignsCount').animateNumber({numberStep: commaStep, number: data.cb});
                    $('#DevicesCount').animateNumber({numberStep: commaStep, number: data.dc});
                    $('#Impressions7Count').animateNumber({numberStep: commaStep, number: data.ic});
                }
                if(typeof LOCATION_ID !== "undefined") {
                    $('#ZonesCount').animateNumber({numberStep: commaStep,      number: data.LocationZonesCount   });
                    $('#ImpressionsCount').animateNumber({numberStep: commaStep,    number: data.WeeksImpressionCount   });
                    $('#DevicesCount').animateNumber({numberStep: commaStep,        number: data.LocationDevicesCount   });
                }
                if(typeof RETAILER_ID !== "undefined"){
                    $('#LocationsCount').animateNumber({numberStep: commaStep,      number: data.RetailerLocationsCount   });
                    $('#ImpressionsCount').animateNumber({numberStep: commaStep,    number: data.WeeksImpressionCount   });
                    $('#DevicesCount').animateNumber({numberStep: commaStep,        number: data.RetailerDevicesCount   });
                    $('#BeaconsCount').animateNumber({numberStep: commaStep,        number: data.RetailerBeaconCount   });
                }
                if(typeof ACCESS_POINT_ID !== "undefined"){
                    $('#TotalScanCount').animateNumber({numberStep: commaStep, number: data.totalScanCount});
                    $('#TotalUniqueDevices').animateNumber({numberStep: commaStep, number: data.totalUniqueDevices});
                    $('#TotalScanCount_time').animateNumber({numberStep: commaStep, number: data.totalScanCount_time});
                    $('#TotalUniqueDevices_time').animateNumber({numberStep: commaStep, number: data.totalUniqueDevices_time});
                }                
            });
        setValuesDateRange($('.dateRange.start-range-date').data('DateTimePicker').date(), $('.dateRange.end-range-date').data('DateTimePicker').date());
        $('.row-date-range').hide();

    });

});
