<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

if(isset($_POST['STdate']) && isset($_POST['EDdate'])){
    
    include 'reports_advanced_functions.php';
    
    $sdate = isset($_POST['STdate']) ? $_POST['STdate'] : date('d-m-Y');
    $edate = isset($_POST['EDdate']) ? $_POST['EDdate'] : date('d-m-Y');
    $datecheck = datediff($sdate, $edate);
    
    if($datecheck==1){
        $month_diff = get_month_diff($sdate,$edate);
        
        if($month_diff == 0){
            $STdate = GetSafeValueString($sdate, 'string');
            $EDdate = GetSafeValueString($edate, 'string');
            $totaldays = gendays($STdate, $EDdate);
            $column_name = "sudden_acc";
            $data = get_advanced_dailyreport_all($STdate, $EDdate);
            $table_format = $data[0];
            $all_vehicle_total = $data[1];
            $graph_report_drill = $data[2];
            $graph_height = $data[3];
            $drill_height = (count($totaldays)*$single_chart_height)+$def_chart_height;
            $report_name = "Sudden Acceleration Analysis";
            $table_header = "Date-wise Sudden Acceleration Count";
            
            include 'pages/panels/advancedReport.php';
            echo "<tbody>".$table_format."</tbody>";
        }
        else{
            echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
        }
    }
    else if($datecheck==0){
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    }
    else{
        echo "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    }
}
?>

<script type="text/javascript">
$(function () {
<?php
if(!empty($all_vehicle_total)){
?>
    var veh_total = <?php echo json_encode($all_vehicle_total); ?>;
    var veh_drill = <?php echo $graph_report_drill; ?>;
    var drill_height = <?php echo $drill_height; ?>;
    var graph_height = <?php echo $graph_height; ?>;
    
    $('#graph_container').highcharts({
        chart: { 
            type: 'bar',
            events:{
                drilldown: function(e){
                    this.setTitle(null, { text: e.point.name});
                    $("#graph_container").css({height:drill_height});
                    this.setSize(null, drill_height);
                    $(window).resize();
                },
                drillup: function(){
                    this.setTitle(null, { text: 'All Vehicles'});
                    $("#graph_container").css({height:graph_height});
                    this.setSize(null, graph_height);
                    $(window).resize();
                }
            }
        },
        title: { text: '<?php echo $report_name; ?>' },
        subtitle: { text: 'All Vehicles' },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Total'
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.0f}'
                }
            }
        },
        

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
        },

        series: [{
            name: 'Vehicles',
            colorByPoint: true,
            data: veh_total
        }],
        drilldown: {
            series: veh_drill
        }
    });
    <?php
    }
    else{
        echo "$('#graph_container').html('No data for Chart generation');";
    }
    ?>
});
</script>
