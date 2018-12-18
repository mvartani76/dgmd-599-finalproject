<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Heatmap $heatmap
 */
?>
<?php $this->Html->script('/js/jquery.animate.number.min', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/csv_template', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/bootstrap3-wysihtml5.all.min', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/notes', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/jquery.blockui', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/ajax_paging', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/Spin', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/load_on_map', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('https://maps.googleapis.com/maps/api/js?key='.\Cake\Core\Configure::read('Settings.goo_api_maps_key').'', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('/js/admin/filtering', ['block' => 'scriptBottom']); ?>
<?php $this->Html->css('/css/bootstrap3-wysihtml5', ['block' => true]); ?>
<?php $this->Html->css('/css/progress', ['block' => true]); ?>
<?php $this->Html->script('/js/heatmap', ['block' => 'scriptBottom']); ?>
<?php $this->AssetCompress->css('heatmaps', ['block' => 'scriptTop']); ?>

<?php $this->set('pageTitle', 'Show All Heatmap Activity'); ?>

<script>
    var STATS_URL = window.location.origin + window.location.pathname;
    var HEATMAP_ID = <?= $heatmap->id ?>;
    //IN UTC
    var START_DATE  = '<?=$periodStartRaw?>';
    var END_DATE  = '<?=$periodEndRaw; ?>';
    $(function() {
        var options = {
            animation: true,
            placement: 'top',
            html: true,
            container: 'body'
        };
        $('tr.fields-info th').popover(options)
    });

</script>


<div class="x_panel">
    <div class="x_title" style="vertical-align:middle;">
        <!--<h2 style="display:inline-block; vertical-align:middle; float: left; width: 50%">Show all heatmap activity for <?= h($heatmap->floorplans_library->title) ?></h2><div style="display:inline-block; vertical-align:middle; width: 50%"><?= $this->element('Header/daterangepicker') ?></div>-->
        <h2 class="col-md-6 align-self-center" style="display:inline-block; vertical-align:middle;">Show all heatmap activity for <?= h($heatmap->floorplans_library->title) ?></h2><div><?= $this->element('Header/daterangepicker') ?></div>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
  
            <div class="heatmaps col-lg-8 col-md-9 col-sm-12 col-xs-12">
                <div class="heatmap-page-content" style="background-image: url('<?= $heatmap->getFullImageUrl() ?>');">
                </div>
            </div>
            <div class="callout col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Floorplan Title</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= h($heatmap->floorplans_library->title) ?></div><br /><br />
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Floorplan Width</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= $this->Number->format($heatmap->floorplans_library->width) ?>px</div><br /><br />
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Floorplan Height</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= $this->Number->format($heatmap->floorplans_library->height) ?>px</div><br /><br />
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Floorplan Width</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= $this->Number->format($heatmap->floorplans_library->width_m) ?>m</div><br /><br />
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Floorplan Height</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= $this->Number->format($heatmap->floorplans_library->height_m) ?>m</div><br /><br />
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Number of Width Divs</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= $this->Number->format($heatmap->floorplans_library->num_width_divs) ?></div><br /><br />
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Number of Height Divs</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= $this->Number->format($heatmap->floorplans_library->num_height_divs) ?></div><br /><br />
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Pixels per Width Div</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= $this->Number->format($heatmap->floorplans_library->width/$heatmap->floorplans_library->num_width_divs) ?></div><br /><br />
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Pixels per Height Div</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= $this->Number->format($heatmap->floorplans_library->height/$heatmap->floorplans_library->num_height_divs) ?></div><br /><br />
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Meters per Width Div</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= $this->Number->format($heatmap->floorplans_library->width_m/$heatmap->floorplans_library->num_width_divs) ?></div><br /><br />
                <div class="heatmap-field-title col-lg-6 col-sm-6 col-xs-12">Meters per Height Div</div>
                <div class="heatmap-field-value col-lg-6 col-sm-6 col-xs-12"><?= $this->Number->format($heatmap->floorplans_library->height_m/$heatmap->floorplans_library->num_height_divs) ?></div><br /><br />
            </div>
        
    </div>
</div>

<script>
    $(document).ready(function() {

        // minimal heatmap instance configuration
        var heatmapInstance = h337.create({
        // only container is required, the rest will be defaults
        container: document.querySelector('.heatmap-page-content')
        });
        
        var heatmapdata = <?= $heatmapdata ?>;

        // Do not show data if all values are 0
        if (heatmapdata.max != 0) {
            // if you have a set of datapoints always use setData instead of addData
            // for data initialization
            heatmapInstance.setData(heatmapdata);
        } else {
            // Sending empty set appears to clear heatmap
            heatmapInstance.setData({data:[]});
        }

        $(function() {
        $( ".apply-dateRange" ).bind( "click", function() {
            //Send to server converted to UTC.
            var start_date =  moment($('.start-range-date').data('DateTimePicker').date()).tz('UTC').format('MM/DD/YYYY');
            var end_date = moment($('.end-range-date').data('DateTimePicker').date()).tz('UTC').format('MM/DD/YYYY');
            var ajaxData = {};
            
            if (typeof HEATMAP_ID !== "undefined"){
                var ajaxData = {
                    starttime: start_date,
                    endtime:   end_date,
                    action:    'updateStats',
                    heatmapId: HEATMAP_ID
                };
            }

            $.post(STATS_URL + '.json',ajaxData)
                .done(function (r) {
                    var data = r;
                    
                    if (typeof HEATMAP_ID !== "undefined") {
                        
                        // Do not show data if all values are 0
                        if (data.max != 0) {
                            // Update heatmap with data from new time query
                            heatmapInstance.setData(data);
                        } else {
                            // Sending empty set appears to clear heatmap
                            heatmapInstance.setData({data:[]});
                        }
                    }       
                });
            setValuesDateRange($('.dateRange.start-range-date').data('DateTimePicker').date(),
                                $('.dateRange.end-range-date').data('DateTimePicker').date());
            $('.row-date-range').hide();
    });

});
        
    });
</script>