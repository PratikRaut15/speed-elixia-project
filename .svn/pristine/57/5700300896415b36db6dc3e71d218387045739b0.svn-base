<?php 
if(isset($_POST['STdate']) && isset($_POST['EDdate']))
{
    include_once 'reports_common_functions.php';
    $STdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['STdate'], 'string')));
    $EDdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['EDdate'], 'string')));
    $STHour = isset($_POST['Shour']) ? GetSafeValueString($_POST['Shour'], 'string') : '00:00';
    $ReportType = GetSafeValueString($_POST['report'], 'string');
    $datediffcheck = date_SDiff($STdate, $EDdate);
    $title = 'Fuel Consumption Analysis Report';
    $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate", "End Date: $EDdate");
    
    if(strtotime($STdate)>strtotime($EDdate)){
        echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    }
    else if(isset($_SESSION['ecodeid'])){
        $startdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['s_start'], 'string')));
        $enddate = date('Y-m-d',strtotime(GetSafeValueString($_POST['e_end'], 'string')));
        if(strtotime($STdate) < strtotime($startdate) || strtotime($EDdate) > strtotime($enddate)){
            echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
        }
         else{ 
            if($datediffcheck <=30){
                $reports = getdailyreport_All($STdate, $EDdate); 
                if(isset($reports)){
                    if($ReportType == 'Graph'){
                        echo table_header($title, $subTitle);
                        $graph_data = generate_fuel_graph($reports);
                        echo '<div id="temp_container" style="width: 900px; min-height: '.$graph_data['height'].'px; margin: 0 auto;"></div>';
                    }
                    else if($ReportType == 'Table'){
                       include 'pages/panels/fuelconsumption_rep.php';
                       include 'fuelconsumption_data.php';  
                    }
                    else{
                        echo "<script type='text/javascript'>jQuery('#error3').show();jQuery('#error3').fadeOut(3000);</script>";
                    }
                }
                else
                    echo "<script type='text/javascript'>jQuery('#error').show();jQuery('#error').fadeOut(3000);</script>";
            }
            else{
                echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
            }
        }
    }
    else{ 
        if($datediffcheck <=30){
            $reports = getdailyreport_All($STdate, $EDdate); 
            if(isset($reports)){
                
                if($ReportType == 'Graph'){
                    echo table_header($title, $subTitle);
                    $graph_data = generate_fuel_graph($reports);
                    echo '<div id="temp_container" style="width: 900px; min-height: '.$graph_data['height'].'px; margin: 0 auto;"></div>';
                }
                else if($ReportType == 'Table'){
                    include 'pages/panels/fuelconsumption_rep.php';
                    include 'fuelconsumption_data.php';  
                }
                else{
                    echo "<script type='text/javascript'>jQuery('#error3').show();jQuery('#error3').fadeOut(3000);</script>";
                }
            }
            else
                echo "<script type='text/javascript'>jQuery('#error').show();jQuery('#error').fadeOut(3000);</script>";
        }
        else{
            echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
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
                text: 'Fuel',
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
            name: 'Fuel',
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