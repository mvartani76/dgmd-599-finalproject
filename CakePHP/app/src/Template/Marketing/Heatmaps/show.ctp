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
    <div class="x_title">
        <h2>Show all heatmap activity for <?= h($heatmap->floorplans_library->title) ?><?= $this->element('Header/daterangepicker') ?></h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <section class="content">
            <div class="heatmaps view large-9 medium-8 columns content">
                <div class="heatmap-page-content" style="background-image: url('<?= $heatmap->getFullImageUrl() ?>')">
                </div>
            </div>
        </section>
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

        // if you have a set of datapoints always use setData instead of addData
        // for data initialization
        heatmapInstance.setData(heatmapdata);

        $(function() {
        $( ".apply-dateRange" ).bind( "click", function() {
            //Send to server converted to UTC.
            var start_date =  moment($('.start-range-date').data('DateTimePicker').date()).tz('UTC').format('MM/DD/YYYY');
            var end_date = moment($('.end-range-date').data('DateTimePicker').date()).tz('UTC').format('MM/DD/YYYY');
            var ajaxData = {};
            
            if(typeof HEATMAP_ID !== "undefined"){
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
                    
                    if(typeof HEATMAP_ID !== "undefined") {
                        // Update heatmap with data from new time query
                        heatmapInstance.setData(data);
                    }       
                });
            setValuesDateRange($('.dateRange.start-range-date').data('DateTimePicker').date(),
                                $('.dateRange.end-range-date').data('DateTimePicker').date());
            $('.row-date-range').hide();
    });

});
        
    });
</script>