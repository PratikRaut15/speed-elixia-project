<?php
if(isset($_POST['STdate']) && isset($_POST['EDdate'])){
    include_once 'reports_fuelefficiency_functions.php';
    $today = date('Y-m-d');
    $enddate = date('Y-m-d', strtotime($_POST['EDdate']));
    $STdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['STdate'], 'string')));
    $EDdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['EDdate'], 'string')));
    $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
    $datediffcheck = date_SDiff($STdate, $EDdate);
    $ReportType = GetSafeValueString($_POST['report'], 'string');
    $title = 'Fuel Consumption Report';
    $subTitle = array("Vehicle No: ".$_POST['vehicleno'], 'Start Date: '.$_POST['STdate'].' '.$_POST['STime'], 'End Date: '.$_POST['EDdate'].' '.$_POST['ETime']);
    
    if(isset($_SESSION['ecodeid'])){
        $startdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['s_start'], 'string')));
        $enddate1 = date('Y-m-d',strtotime(GetSafeValueString($_POST['e_end'], 'string')));
    }
    if(strtotime($STdate)>strtotime($EDdate)){
        echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(5000)</script>";
    }
    else if($_POST['vehicleid']=='Select Vehicle'){
        echo "<script>jQuery('#error3').show();jQuery('#error3').fadeOut(5000)</script>";
    }
    else if($enddate == $today){
        echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(5000)</script>";
    }
    else if(isset($_SESSION['ecodeid'])){
        if(strtotime($STdate) < strtotime($startdate) || strtotime($EDdate) > strtotime($enddate1)){
            echo "<script>jQuery('#error8').show();jQuery('#error8').fadeOut(5000)</script>";
        }
        else{
        
            if($datediffcheck <= 30){
                $reports = getdailyreport_byID($STdate,$EDdate,$vehicleid);
                if($reports){
                    include 'pages/panels/fuelconsumptionrep.php';
                    include 'pages/displayfuelconsumption_new.php';
                }
                else{
                    echo "<script type='text/javascript'>jQuery('#error').show();jQuery('#error').fadeOut(5000);</script>";
                }
            }
            else{
                echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(5000);</script>";
            }
        }
    }
    else{
        
        if($datediffcheck <= 30){
            
            $reports = getdailyreport_byID($STdate,$EDdate,$vehicleid);
            if($reports){
                if($ReportType=='Graph'){
                    echo table_header($title, $subTitle);
                    $graph_data = generate_fuelefficiency_graph($reports);
                    echo '<div id="temp_container" style="width: 900px; min-height: '.$graph_data['height'].'px; margin: 0 auto;"></div>';
                }
                else{
                    include 'pages/panels/fuelconsumptionrep.php';
                    include 'pages/displayfuelconsumption_new.php';
                }
            }
            else{
                echo "<script type='text/javascript'>jQuery('#error').show();jQuery('#error').fadeOut(5000);</script>";
            }
        }
        else{
            echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(5000);</script>";
        }
    }
}
?>
<script type="text/javascript">
    jQuery(function(){
        <?php
        if($ReportType == 'Graph' && !empty($graph_data)){
        ?>  
        jQuery('#temp_container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: '<?php echo $title; ?>'
        },
        xAxis: {
            categories: [<?php echo $graph_data['vehs']; ?>],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Fuel Consumed',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' Litres'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 100,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Litres',
            data: [<?php echo $graph_data['data']; ?>]
        }]
    });        
        <?php
        }
        else{
            echo "jQuery('#temp_container').html('No data for Chart generation');";
        }
        ?>
    });
    
</script>