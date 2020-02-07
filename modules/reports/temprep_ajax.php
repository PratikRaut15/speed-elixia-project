<?php
    set_time_limit(90);
    if (isset($_POST['nomensdeviceid']) && $_POST['nomensdeviceid'] != '') {
        include 'reports_common_functions.php';
        $getNomesName = getNomensName($_POST['nomensdeviceid'], $_SESSION['temp_sensors']);
        echo json_encode($getNomesName);exit;
    } elseif (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
        include 'reports_common_functions.php';
        $STdate = $_POST['STdate'];
        $EDdate = $_POST['EDdate'];
        $stime = $_POST['STime'];
        $etime = $_POST['ETime'];
        $interval_p = $_POST['interval'];
        $tripmin = isset($_POST['tripmin']);
        $tripmax = isset($_POST['tripmax']);
        $tempsel = isset($_POST['tempsel']);
        $customMinTemp = $_POST['customMinTemp'];
        $customMaxTemp = $_POST['customMaxTemp'];

        $objReport = new stdClass();
        $objReport->customMinTemp = $customMinTemp;
        $objReport->customMaxTemp = $customMaxTemp;

        if ($_SESSION['temp_sensors'] == 4) {
            $tempselect = "4-all";
        } elseif ($_SESSION['temp_sensors'] == 3) {
            $tempselect = "3-all";
        } elseif ($_SESSION['temp_sensors'] == 2) {
            $tempselect = "2-all";
        } else {
            $tempselect = "1-all";
        }

        if ($_SESSION["temp_sensors"] == 2) {
            $tempselect = GetSafeValueString($tempselect, 'string');
        }

        //echo $tempselect; die;
        if (isset($_POST['tempsel'])) {
            $tempselArr = explode("-", $_POST['tempsel']); // this array will check it is temp1 or temp2 or All.
        } else {
            $tempselArr[0] = "1";
            $tempselArr[1] = "0";
        }

        $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
        $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
        $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
        $error3 = "<script>jQuery('#error3').show();jQuery('#error3').fadeOut(3000)</script>";
        $datecheck = datediff($STdate, $EDdate);
        $datediffcheck = date_SDiff($STdate, $EDdate);
        $temp_limit = 15;
        $deviceid = 0;

        $interval = GetSafeValueString($interval_p, 'long');
        $deviceid = GetSafeValueString($_POST['deviceid'], 'long');
        if ((!isset($deviceid) || $deviceid == 0 || $deviceid == "NULL" || $deviceid == '') && isset($_POST['vehicleno'])) {
            $vehicleno = GetSafeValueString($_POST['vehicleno'], 'string');
            $devicemanager = new DeviceManager($_SESSION['customerno']);
            $devices = $devicemanager->devicesformapping_byId($vehicleno);
            if ($devices) {
                foreach ($devices as $row) {
                    $deviceid = $row->deviceid;
                }
            }
        }
        if ($deviceid != 0) {
            if (isset($_SESSION['ecodeid'])) {
                /*Client Code Validation */
                $validation = clientCodeValidation($_POST['s_start'], $_POST['e_end'], $_POST['days'], $STdate, $EDdate);
                if (isset($validation) && !empty($validation)) {
                    if ($validation['isError'] == 1) {
                        echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
                        exit;
                    } else {
                        $STdate = date('d-m-Y', strtotime($validation['reportStartDate']));
                        $EDdate = date('d-m-Y', strtotime($validation['reportEndDate']));
                        echo "<script>jQuery('#SDate').val('" . $STdate . "');</script>";
                        echo "<script>jQuery('#EDate').val('" . $EDdate . "');</script>";
                    }
                }

                if ($datecheck == 1) {
                    if ($datediffcheck <= 30) {
                        $return = gettemptabularreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $tripmin, $tripmax, $tempsel, $objReport);

                        $final_graph_data_temp1 = "";
                        $final_graph_data_temp2 = "";
                        $final_graph_data_temp3 = "";
                        $final_graph_data_temp4 = "";
                        $final_graph_data = $return[1];
                        //$final_graph_data_temp1 = $final_graph_data;

                        $final_graph_data_temp1 = (isset($final_graph_data[0]) && count($final_graph_data[0] > 0)) ? $final_graph_data[0] : "";
                        $final_graph_data_temp2 = (isset($final_graph_data[1]) && count($final_graph_data[1] > 0)) ? $final_graph_data[1] : "";
                        $final_graph_data_temp3 = (isset($final_graph_data[2]) && count($final_graph_data[2] > 0)) ? $final_graph_data[2] : "";
                        $final_graph_data_temp4 = (isset($final_graph_data[3]) && count($final_graph_data[3] > 0)) ? $final_graph_data[3] : "";

                        if (isset($final_graph_data)) {
                            echo '<div id="temp_container" style="width: 950px; min-height: 600px; margin: 0 auto;"></div>';
                            echo "<br>";
                        }
                        extract(get_min_max_temp($tempsel, $return[2]));
                        $static_min_temp = get_min_temperature(null, true);
                        $static_max_temp = get_max_temperature(null, true);
                        $ignition_off_graph = str_replace('#0#', $static_max_temp, $return[3]);
                        $ignition_off_graph = str_replace('#1#', ($static_max_temp - 2), $ignition_off_graph);
                        $ignition_on_graph = str_replace('#1#', $static_max_temp, $return[3]);
                        $ignition_on_graph = str_replace('#0#', ($static_max_temp - 2), $ignition_on_graph);
                        include 'pages/panels/temprephist.php';
                        echo $return[0];
                    } else {
                        echo $error2;
                    }
                } elseif ($datecheck == 0) {
                    echo $error1;
                } else {
                    echo $error;
                }
            } else {
                if ($datecheck == 1) {
                    if ($datediffcheck <= 30) {
                        $return = gettemptabularreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $tripmin, $tripmax, $tempsel, $objReport);
                        $final_graph_data_temp1 = "";
                        $final_graph_data_temp2 = "";
                        $final_graph_data_temp3 = "";
                        $final_graph_data_temp4 = "";
                        $final_graph_data = $return[1];
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
                        extract(get_min_max_temp($tempsel, $return[2]));
                        $static_min_temp = get_min_temperature(null, true);
                        $static_max_temp = get_max_temperature(null, true);
                        $ignition_off_graph = str_replace('#0#', $static_max_temp, $return[3]);
                        $ignition_off_graph = str_replace('#1#', ($static_max_temp - 2), $ignition_off_graph);
                        $ignition_on_graph = str_replace('#1#', $static_max_temp, $return[3]);
                        $ignition_on_graph = str_replace('#0#', ($static_max_temp - 2), $ignition_on_graph);
                        include 'pages/panels/temprephist.php';
                        //print("<pre>"); print_r($return[0]); die;
                        echo $return[0];
                    } else {
                        echo $error2;
                    }
                } elseif ($datecheck == 0) {
                    echo $error1;
                } else {
                    echo $error;
                }
            }
        } else {
            echo $error3;
        }
    }
?>
<script type="text/javascript">
    $(function () {
        var vehicleno = $("#vno").val();
        var tempsel   = $("#tempsel").val();
        var SDate     = $("#SDate").val();
        var STime     = $("#STime").val();
        var EDate     = $("#EDate").val();
        var ETime     = $("#ETime").val();
        var interval  = $("#interval").val();
        var chartTitle= "Temperature Report For " +vehicleno+ " <br> From "+SDate+" "+STime+" To "+EDate+" "+ETime+ " For "+interval+" Interval";

<?php

    /**
     * Changes Made By :Pratik Raut
     * Date : 16-10-209
     * Reason : graph was not coming
     */
    //if (isset($final_graph_data) && $final_graph_data_temp1 != "") {

    if (isset($final_graph_data)) {
    ?>
   /* vat test = $('#tempsel option[value="1-0"]').html();
    console.log(test);*/

     var tempSelectVal =  $("#tempsel option:selected").val();

    if(tempSelectVal == '1-0' || tempSelectVal == '1-all' || tempSelectVal == '2-all'|| tempSelectVal == '3-all'|| tempSelectVal == '4-all'){
       var temp1 =  $("#hddn_1-0").text();
    }

    if(tempSelectVal == '2-0' || tempSelectVal == '2-all'|| tempSelectVal == '3-all'|| tempSelectVal == '4-all'){
           var temp2 =  $("#hddn_2-0").text();
    }
    if(tempSelectVal == '3-0'|| tempSelectVal == '3-all'|| tempSelectVal == '4-all'){
        var temp3 =  $("#hddn_3-0").text();
    }
    if(tempSelectVal == '4-0'|| tempSelectVal == '4-all'){
         var temp4 =  $("#hddn_4-0").text();
    }

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
                    text: chartTitle
                },
                xAxis: {
                    type: 'datetime'
                },
                yAxis: {
                    opposite: false,
                    title: {
                        text: 'Temperature [°C]'
                    },
                    maxPadding:-2,
                           labels: {
                                style: {
                                        fontSize: '7px',
                                },
                                x:-8,
                                y:3
                            },
                <?php
                    //if($_SESSION['customerno']==116){
                        //echo "tickPositions: [15,18,21,24,27,30],";
                        //}
                    ?>
                min:<?php echo $static_min_temp-2; ?>,
                max:<?php echo $static_max_temp+2; ?>,
                tickInterval: 2,
                plotLines: [
                        {
                            value:                                   <?php echo $temp_max_limit; ?>,
                            color: 'black',
                            dashStyle: 'shortdash',
                            width: 2,
                            label: {
                                text: 'Temperature Limit (Max:'+<?php echo $temp_max_limit; ?>+'°C)'
                            }
                        },
                        {
                            value:                                   <?php echo $temp_min_limit; ?>,
                            color: 'black',
                            dashStyle: 'shortdash',
                            width: 2,
                            label: {
                                text: 'Temperature Limit (Min:'+<?php echo $temp_min_limit; ?>+'°C)'
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
                        threshold:                                   <?php echo $static_max_temp - 1; ?>,
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
                        threshold:                                   <?php echo $static_max_temp - 1; ?>,
                        color: '#A3EDBA',
                        type: 'area',
                        negativeColor: '#FFFFFF',
                        enableMouseTracking: false
                    },
                    <?php
                        if ($final_graph_data_temp1 != "" && ($tempselArr[0] == "1" || $tempselArr[1] == "all")) {
                            ?>
                    {
                        name: temp1,
                        data: [<?php echo $final_graph_data_temp1; ?>],
                        dataGrouping: {
                            enabled: false
                        },
                        threshold:                                   <?php echo $temp_max_limit; ?>,
                        color: '#DE1D22',
                        negativeColor: '#f08e91'
                    },
                    <?php
                        }
                            if ($final_graph_data_temp2 != "" && ($tempselArr[0] == "2" || $tempselArr[1] == "all")) {
                            ?>
                    {
                        name: temp2,
                        data: [<?php echo $final_graph_data_temp2; ?>],
                        dataGrouping: {
                            enabled: false
                        },
                        threshold:                                   <?php echo $temp_max_limit; ?>,
                        color: '#0000cd',
                        negativeColor: '#4169e1'
                    },
                    <?php
                        }
                            if ($final_graph_data_temp3 != "" && ($tempselArr[0] == "3" || $tempselArr[1] == "all")) {
                            ?>
                    {
                        name: temp3,
                        data: [<?php echo $final_graph_data_temp3; ?>],
                        dataGrouping: {
                            enabled: false
                        },
                        threshold:                                   <?php echo $temp_max_limit; ?>,
                        color: '#4d9702',
                        negativeColor: '#bffe81'
                    },
                    <?php
                        }
                            if ($final_graph_data_temp4 != "" && ($tempselArr[0] == "4" || $tempselArr[1] == "all")) {
                            ?>
                    {
                        name: temp4,
                        data: [<?php echo $final_graph_data_temp4; ?>],
                        dataGrouping: {
                            enabled: false
                        },
                        threshold:                                   <?php echo $temp_max_limit; ?>,
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
<script type="text/javascript">
    <?php
        if (isset($interval_p) && $interval_p == "1") {
        ?>
        jQuery('#tblTempReport').hide();
    <?php
        }
    ?>
</script>
