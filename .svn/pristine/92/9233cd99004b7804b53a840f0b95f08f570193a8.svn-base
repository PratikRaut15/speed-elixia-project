<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    $title = 'Humidity and Temperature Report';
    include 'reports_common_functions.php';
    $sdate = $_POST['STdate'];
    $edate = $_POST['EDdate'];
    $stime = $_POST['STime'];
    $etime = $_POST['ETime'];
    $interval_p = $_POST['interval'];
    if (isset($sdate)) {
        $pointStart = (strtotime($sdate) * 1000) - (strtotime('02-01-1970 00:00:00') * 1000);
    }
    $tickInterval = 60 * 60 * 1000;
    $xAxisTitle = 'Timeline';

    /*
      $tempselect = 1;
      if ($_SESSION["temp_sensors"] == 2) {
      $tempselect = GetSafeValueString($_POST['tempsel'], 'string');
      }
     */
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
                    $return = gethumiditytempreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime);
                    $humidityData = $return[1];
                    /*
                      extract(get_min_max_temp($tempselect, $return[2]));
                      $static_min_temp = get_min_temperature(null, true);
                      $static_max_temp = get_max_temperature(null, true);
                     */
                    $temperatureData = $return[3];

                    include 'pages/panels/humiditytemprephist.php';
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
                $return = gethumiditytempreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime);
                $humidityData = $return[1];
                /*
                  extract(get_min_max_temp($tempselect, $return[2]));
                  $static_min_temp = get_min_temperature(null, true);
                  $static_max_temp = get_max_temperature(null, true);
                 */
                $temperatureData = $return[3];

                include 'pages/panels/humiditytemprephist.php';
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
if (isset($humidityData)) {
    ?>
            $('#humtemp_container').highcharts({
                chart: {
                    zoomType: 'x',
                    panning: true,
                    panKey: 'shift'
                },
                title: {
                    text: '<?php echo $title; ?>'
                },
                subtitle: {
                    text: 'Source: speed.elixiatech.com'
                },
                xAxis: [{
                        title: {
                            enabled: true,
                            text: '<?php echo $xAxisTitle; ?>'
                        },
                        crosshair: true,
                        type: 'datetime'
                    }],
                yAxis: [{// Primary yAxis
                        labels: {
                            format: '{value}°C',
                            style: {
                                color: Highcharts.getOptions().colors[1]
                            }
                        },
                        title: {
                            text: 'Temperature',
                            style: {
                                color: Highcharts.getOptions().colors[1]
                            }
                        }
                    }, {// Secondary yAxis
                        title: {
                            text: 'Humidity',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        labels: {
                            format: '{value} %',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        opposite: true
                    }],
                tooltip: {
                    shared: true
                },
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    x: 120,
                    verticalAlign: 'top',
                    y: 100,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                },
                series: [{
                        name: 'Humidity',
                        type: 'spline',
                        yAxis: 1,
                        data: [<?php echo $humidityData; ?>],
                        pointStart: <?php echo $pointStart; ?>,
                        tickInterval: <?php echo $tickInterval; ?>,
                        tooltip: {
                            valueSuffix: ' %'
                        }

                    }, {
                        name: 'Temperature',
                        type: 'spline',
                        data: [<?php echo $temperatureData; ?>],
                        pointStart: <?php echo $pointStart; ?>,
                        tickInterval: <?php echo $tickInterval; ?>,
                        tooltip: {
                            valueSuffix: '°C'
                        }
                    }]
            });
    <?php
} else {
    echo "jQuery('#humtemp_container').html('No data for Chart generation');";
}
?>
    });

</script>
