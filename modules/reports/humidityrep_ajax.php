<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
 include 'reports_common_functions.php';
 $sdate = $_POST['STdate'];
 $edate = $_POST['EDdate'];
 $stime = $_POST['STime'];
 $etime = $_POST['ETime'];
 $interval_p = $_POST['interval'];
 $tempselect = 1;
 if ($_SESSION["temp_sensors"] == 2) {
  $tempselect = GetSafeValueString($_POST['tempsel'], 'string');
 }
 $newsdate = date("Y-m-d", strtotime($sdate));
 $newedate = date("Y-m-d", strtotime($edate));
 $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
 $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
 $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
 $datecheck = datediff($sdate, $edate);
 $datediffcheck = date_SDiff($newsdate, $newedate);
 $temp_limit = 15;
 $STdate = GetSafeValueString($sdate, 'string');
 $EDdate = GetSafeValueString($edate, 'string');
 $interval = GetSafeValueString($interval_p, 'long');
 $deviceid = GetSafeValueString($_POST['deviceid'], 'long');
 if (isset($_SESSION['ecodeid'])) {
  $startdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['s_start'], 'string')));
  $enddate = date('Y-m-d', strtotime(GetSafeValueString($_POST['e_end'], 'string')));
  if (strtotime($sdate) < strtotime($startdate) || strtotime($edate) > strtotime($enddate)) {
   echo "<script>jQuery('#error4').show();jQuery('#error4').fadeOut(3000)</script>";
  } else {
   if ($datecheck == 1) {
    if ($datediffcheck <= 30) {
     $return = gethumidityreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime);
     $final_graph_data = $return[1];
     if (isset($final_graph_data)) {
      echo '<div id="temp_container" style="width: 950px; min-height: 600px; margin: 0 auto;"></div>';
      echo "<br>";
     }
     extract(get_min_max_temp($tempselect, $return[2]));
     $static_min_temp = get_min_temperature(null, true);
     $static_max_temp = get_max_temperature(null, true);
     $ignition_off_graph = str_replace('#0#', $static_max_temp, $return[3]);
     $ignition_off_graph = str_replace('#1#', ($static_max_temp - 2), $ignition_off_graph);
     $ignition_on_graph = str_replace('#1#', $static_max_temp, $return[3]);
     $ignition_on_graph = str_replace('#0#', ($static_max_temp - 2), $ignition_on_graph);
     include 'pages/panels/humidityrephist.php';
     echo $return[0];
    } else {
     echo $error2;
    }
   } else if ($datecheck == 0) {
    echo $error1;
   } else {
    echo $error;
   }
  }
 } else {
  if ($datecheck == 1) {
   if ($datediffcheck <= 30) {
    $return = gethumidityreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime);
    $final_graph_data = $return[1];
    if (isset($final_graph_data)) {
     echo '<div id="temp_container" style="width: 950px; min-height: 600px; margin: 0 auto;"></div>';
     echo "<br>";
    }
    extract(get_min_max_temp($tempselect, $return[2]));
    $static_min_temp = get_min_temperature(null, true);
    $static_max_temp = get_max_temperature(null, true);
    $ignition_off_graph = str_replace('#0#', $static_max_temp, $return[3]);
    $ignition_off_graph = str_replace('#1#', ($static_max_temp - 2), $ignition_off_graph);
    $ignition_on_graph = str_replace('#1#', $static_max_temp, $return[3]);
    $ignition_on_graph = str_replace('#0#', ($static_max_temp - 2), $ignition_on_graph);
    include 'pages/panels/humidityrephist.php';
    echo $return[0];
   } else {
    echo $error2;
   }
  } else if ($datecheck == 0) {
   echo $error1;
  } else {
   echo $error;
  }
 }
}
?>
<script type="text/javascript">
 $(function () {
<?php
if (isset($final_graph_data)) {
 ?>
    $('#temp_container').highcharts('StockChart', {
      rangeSelector: {
        buttons: [{
            type: 'hour',
            count: 10,
            text: '10 H'
          }, {
            type: 'all',
            text: 'All'
          }],
        selected: 0,
        inputEnabled: false,
        enabled: true
      },
      legend: {
        enabled: true,
        align: 'bottom',
        borderColor: 'black',
        borderWidth: 1,
        layout: 'horizontal',
        verticalAlign: 'top',
        y: 50,
        shadow: true
      },
      title: {
        text: 'Humidity Report'
      },
      xAxis: {
        type: 'datetime'
      },
      yAxis: {
        title: {
          text: 'Humidity[%]'
        },
 <?php
 //if($_SESSION['customerno']==116){
 //echo "tickPositions: [15,18,21,24,27,30],";
 //}
 ?>
        min: <?php echo $static_min_temp; ?>,
        plotLines: [
          {
            value: <?php echo $temp_max_limit; ?>,
            color: 'black',
            dashStyle: 'shortdash',
            width: 2,
            label: {
              text: 'Humidity Limit(Max)'
            }
          },
          {
            value: <?php echo $temp_min_limit; ?>,
            color: 'black',
            dashStyle: 'shortdash',
            width: 2,
            label: {
              text: 'Humidity Limit(Min)'
            }
          }
        ]
      },
      tooltip: {
        pointFormat: '{point.x:%e- %b}: {point.y:.1f} %'
      },
      navigator: {
        series: {
          id: 'nav'
        }
      },
      series: [
        {
          name: 'Ignition-Off',
          data: [<?php echo $ignition_off_graph; ?>],
          dataGrouping: {
            enabled: false
          },
          threshold: <?php echo $static_max_temp - 1; ?>,
          color: '#FAAC58',
          type: 'area',
          negativeColor: '#fff'
        },
        {
          name: 'Ignition-On',
          data: [<?php echo $ignition_on_graph; ?>],
          dataGrouping: {
            enabled: false
          },
          threshold: <?php echo $static_max_temp - 1; ?>,
          color: '#A3EDBA',
          type: 'area',
          negativeColor: '#FFFFFF'
        },
        {
          name: 'Humidity',
          data: [<?php echo $final_graph_data; ?>],
          dataGrouping: {
            enabled: false
          },
          threshold: <?php echo $temp_max_limit; ?>,
          color: '#DE1D22',
          negativeColor: '#01A4E3'
        },
      ]
    }, function (chart) {
      var navigator = chart.get('nav');
      navigator.setData([<?php echo $final_graph_data; ?>]);
    }
    );
 <?php
} else {
 echo "jQuery('#temp_container').html('No data for Chart generation');";
}
?>
 });
</script>