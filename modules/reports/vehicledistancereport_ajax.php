<?php

    if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
        include 'reports_common_functions.php';
        include_once 'distancereport_functions.php';

        $STdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['STdate'], 'string')));
        $EDdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['EDdate'], 'string')));
        $ReportType = GetSafeValueString($_POST['report'], 'string');
        $datediffcheck = date_SDiff($STdate, $EDdate);

        $vehicleno = GetSafeValueString($_POST['vehicleno'], 'string');
        $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');


        /*print('<pre>');
        print_r($_SESSION);
        die;*/


        if (strtotime($STdate) > strtotime($EDdate)) {
            echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
        } elseif (isset($_SESSION['ecodeid'])) {

            /*Client Code Validation */


            $validation = clientCodeValidation($_POST['s_start'], $_POST['e_end'], $_POST['days'], $STdate, $EDdate);
            if (isset($validation) && !empty($validation)) {
                if ($validation['isError'] == 1) {
                    echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
                    die();
                } else {
                    $STdate = date('d-m-Y', strtotime($validation['reportStartDate']));
                    $EDdate = date('d-m-Y', strtotime($validation['reportEndDate']));
                    echo "<script>jQuery('#SDate').val('" . $STdate . "');</script>";
                    echo "<script>jQuery('#EDate').val('" . $EDdate . "');</script>";
                }
            }

            if ($datediffcheck <= 30) {
                $reports = getdailyreport_All($STdate, $EDdate);

                if (isset($reports)) {
                    if ($ReportType == 'Graph') {
                        $graph_data = generate_graph_data($reports);

                        //print_r($graph_data); die;

                        echo '<div id="temp_container" style="width: 900px; min-height: ' . $graph_data['height'] . 'px; margin: 0 auto;"></div>';
                    } elseif ($ReportType == 'Table') {
                        include 'pages/panels/distancerep.php';
                        include 'displaydistancedata_new.php';
                    } else {
                        echo "<script type='text/javascript'>jQuery('#error3').show();jQuery('#error3').fadeOut(3000);</script>";
                    }
                } else {
                    echo "<script type='text/javascript'>jQuery('#error').show();jQuery('#error').fadeOut(3000);</script>";
                }
            } else {
                echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
            }

        } else {
            if ($datediffcheck <= 30) {
                $reports = getdailyreport_All($STdate, $EDdate, $vehicleid); // passed vehicle id for single record.//

                /*print("<pre>");
                print_r($reports);
                die;*/

                if (isset($reports)) {
                    if ($ReportType == 'Graph') {
                        $graph_data = generate_graph_data($reports);



                       //$array =  explode(",", $graph_data['vehs']);
            //print("<pre>"); print_r($graph_data); die;

                       //$graph_data['vehs'] = $array[0];
                      // print("<pre>"); print_r($graph_data); die;

                        echo '<div id="temp_container" style="width: 900px; min-height: ' . $graph_data['height'] . 'px; margin: 0 auto;"></div>';
                    } elseif ($ReportType == 'Table') {
                        include 'pages/panels/vehicledistancerep.php';
                        include 'displayVehicledistancedata_new.php';
                    } else {
                        echo "<script type='text/javascript'>jQuery('#error3').show();jQuery('#error3').fadeOut(3000);</script>";
                    }
                } else {
                    echo "<script type='text/javascript'>jQuery('#error').show();jQuery('#error').fadeOut(3000);</script>";
                }
            } else {
                echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
            }
        }
    }
?>
<script type="text/javascript">
    jQuery(function () {
<?php
    if ($ReportType == 'Graph' && !empty($graph_data)) {
    ?>

            jQuery('#temp_container').highcharts({
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Distance Analysis Report '
                },
                xAxis: {
                    categories: [<?php echo $graph_data['date']; ?>],
                    title: {
                        text: null
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Distance (KMS)',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                tooltip: {
                    valueSuffix: ' KMS'
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
                        name: 'Distance (KMPH)',
                        data: [<?php echo $graph_data['data']; ?>]
                    }]
            });
    <?php
        } else {
            echo "jQuery('#temp_container').html('No data for Chart generation');";
        }
    ?>
    });

</script>
