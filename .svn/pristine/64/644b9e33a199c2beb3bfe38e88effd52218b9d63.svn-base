<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
?>
<script type="text/javascript">
    function saveToDatabase(editableObj, column, id, date, vehicleid, uid) {
    // $(editableObj).css("background","#CFF");
    // console.log("logged");
    $(editableObj).css("background", "#FFF url(loading.gif) no-repeat right");
    $.ajax({
    url: "route_ajax.php?act=edit",
            type: "POST",
            data:'column=' + column + '&editval=' + editableObj.innerText + '&id=' + id + '&date=' + date + '&vehicleid=' + vehicleid + '&uid=' + uid,
            success: function(data){
            $(editableObj).css("background", "#FFF");
            }
    });
    }

</script>


<?php
if (isset($_POST['STdate'])) {
    include_once 'reports_sqlite_function.php';
    $STdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['STdate'], 'string')));
    $EDdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['EDdate'], 'string')));

    $vehicleno = GetSafeValueString($_POST['vehicleno'], 'string');
    $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
    $STime = $_POST['STime'];
    $ETime = $_POST['ETime'];
    $interval = $_POST['interval'];
    $deviceid = GetSafeValueString($_POST['deviceid'], 'string');
    $tempselect = 1;

    if ($STdate != '' && $STdate != '0000-00-00' && $STdate != '1970-01-01') {
        $SDate = $STdate . ' ' . $STime;
    }

    if ($EDdate != '' && $EDdate != '0000-00-00' && $EDdate != '1970-01-01') {
        $EDate = $EDdate . ' ' . $ETime;
    }

    $datecheck = datediff($SDate, $EDate);
    $datediffcheck = date_SDiff($SDate, $EDate);
    if ($datecheck != 1) {
        echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    } else {
        if ($datediffcheck <= 30) {
            //print_r($_POST); die;
            //$return = gettemptabularreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $tripmin, $tripmax);

            $reports = getSqlitereport($STdate, $vehicleid, $STime, $ETime, $interval, $deviceid, $EDdate); // passed vehicle id for single record.//
            //print("<pre>"); print_r($reports); die;
            if (isset($reports) && !empty($reports)) {
            $final_graph_data_temp1 = "";
            $final_graph_data_temp2 = "";
            $final_graph_data_temp3 = "";
            $final_graph_data_temp4 = "";
            $final_graph_data = $reports[1];
            //$final_graph_data_temp1 = $final_graph_data;

            $final_graph_data_len0 = strlen($final_graph_data[0]);
            $final_graph_data_len1 = strlen($final_graph_data[1]);
            $final_graph_data_len2 = strlen($final_graph_data[2]);
            $final_graph_data_len3 = strlen($final_graph_data[3]);
            // var_dump($final_graph_data[0]);
            $final_graph_data_temp1 = (isset($final_graph_data[0]) && $final_graph_data_len0 > 0) ? $final_graph_data[0] : "";
            $final_graph_data_temp2 = (isset($final_graph_data[1]) && $final_graph_data_len1 > 0) ? $final_graph_data[1] : "";
            $final_graph_data_temp3 = (isset($final_graph_data[2]) && $final_graph_data_len2 > 0) ? $final_graph_data[2] : "";
            $final_graph_data_temp4 = (isset($final_graph_data[3]) && $final_graph_data_len3 > 0) ? $final_graph_data[3] : "";

            if (isset($final_graph_data)) {
                echo '<div id="temp_container" style="width: 950px; min-height: 600px; margin: 0 auto;"></div>';
                echo "<br>";
            }
            extract(get_min_max_temp($tempselect, $reports[2]));
            $static_min_temp = get_min_temperature(null, true);
            $static_max_temp = get_max_temperature(null, true);
            $ignition_off_graph = str_replace('#0#', $static_max_temp, $reports[3]);
            $ignition_off_graph = str_replace('#1#', ($static_max_temp - 2), $ignition_off_graph);
            $ignition_on_graph = str_replace('#1#', $static_max_temp, $reports[3]);
            $ignition_on_graph = str_replace('#0#', ($static_max_temp - 2), $ignition_on_graph);
            }
            //include 'pages/panels/temprephist.php';
            //echo $reports[0];
            // print("<pre>"); print_r($reports[0]); die;
            if (isset($reports) && !empty($reports)) {

                include 'pages/panels/changeSqlite.php';
                include 'displaychangeSqlitedata_new.php';
            } else { /* echo "else 2 "; die; */
                echo "<script type='text/javascript'>jQuery('#error').show();jQuery('#error').fadeOut(3000);</script>";
            }
        } else {
            /* echo "else 3 "; die; */
            echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
        }
    }
}
?>


<script type="text/javascript">
    $(function () {
    // $('#temp_container').text("hello i m called");
    // var errorMsg = "<p>Example error message</p>";
    // document.getElementById("temp_container").innerHTML = errorMsg;

<?php
if (isset($final_graph_data) && $final_graph_data_temp1 != "") {
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
                        borderColor: 'black',
                        borderWidth: 1,
                        layout: 'horizontal',
                        verticalAlign: 'top',
                        y: 50,
                        shadow: true
                },
                title: {
                text: 'Temperature Report'
                },
                xAxis: {
                type: 'datetime'
                },
                yAxis: {
                opposite: false,
                        title: {
                        text: 'Temperature [°C]'
                        },
    <?php
    //if($_SESSION['customerno']==116){
    //echo "tickPositions: [15,18,21,24,27,30],";
    //}
    ?>
                min:<?php echo $static_min_temp; ?>,
                        plotLines: [
                        {
                        value: <?php echo $temp_max_limit; ?>,
                                color: 'black',
                                dashStyle: 'shortdash',
                                width: 2,
                                label: {
                                text: 'Temperature Limit(Max)'
                                }
                        },
                        {
                        value: <?php echo $temp_min_limit; ?>,
                                color: 'black',
                                dashStyle: 'shortdash',
                                width: 2,
                                label: {
                                text: 'Temperature Limit(Min)'
                                }
                        }
                        ]
                },
                tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: {point.x: %e-%b %H:%M}: {point.y:.1f} °C <br/>',
                        valueDecimals: 2,
                        split: true
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
                        negativeColor: '#fff',
                        enableMouseTracking: false
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
                        negativeColor: '#FFFFFF',
                        enableMouseTracking: false
                },
                {
                name: 'Temperature1',
                        data: [<?php echo $final_graph_data_temp1; ?>],
                        dataGrouping: {
                        enabled: false
                        },
                        threshold: <?php echo $temp_max_limit; ?>,
                        color: '#DE1D22',
                        negativeColor: '#f08e91'
                },
    <?php
    if ($final_graph_data_temp2 != "") {
        ?>
                    {
                    name: 'Temperature2',
                            data: [<?php echo $final_graph_data_temp2; ?>],
                            dataGrouping: {
                            enabled: false
                            },
                            threshold: <?php echo $temp_max_limit; ?>,
                            color: '#01A4E3',
                            negativeColor: '#ccf0ff'
                    },
        <?php
    }
    if ($final_graph_data_temp3 != "") {
        ?>
                    {
                    name: 'Temperature3',
                            data: [<?php echo $final_graph_data_temp3; ?>],
                            dataGrouping: {
                            enabled: false
                            },
                            threshold: <?php echo $temp_max_limit; ?>,
                            color: '#4d9702',
                            negativeColor: '#bffe81'
                    },
        <?php
    }
    if ($final_graph_data_temp4 != "") {
        ?>
                    {
                    name: 'Temperature4',
                            data: [<?php echo $final_graph_data_temp4; ?>],
                            dataGrouping: {
                            enabled: false
                            },
                            threshold: <?php echo $temp_max_limit; ?>,
                            color: '#b20159',
                            negativeColor: '#ffcce6'
                    },
        <?php
    }
    ?>
                ]
        }, function (chart) {
        var navigator = chart.get('nav');
        navigator.setData([<?php echo $final_graph_data_temp1; ?>]);
        }
        );
    <?php
} else {
    echo "jQuery('#temp_container').html('No data for Chart generation');";
}
?>
    });
</script>
<!-- <script type="text/javascript">
<?php
if (isset($interval_p) && $interval_p == "1") {
    ?>
            jQuery('#tblTempReport').hide();
    <?php
}
?>
</script>
-->