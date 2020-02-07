<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {

    include 'reports_overspeed_functions.php';

    $STdate = $_POST['STdate'];

    $EDdate = $_POST['EDdate'];

    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    $error3 = "<script>jQuery('#error3').show();jQuery('#error3').fadeOut(3000)</script>";


    if (isset($_SESSION['ecodeid'])) {
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
    }
    $datecheck = datediff($STdate, $EDdate);

    $datediffcheck = date_SDiff($STdate, $EDdate);
    if ($datecheck == 1) {
        if ($datediffcheck <= 30) {
            $deviceid = 0;

            $deviceid = GetSafeValueString($_POST['deviceid'], 'long');
            $deviceid = "";
            if ((!isset($deviceid) || $deviceid == 0 || $deviceid == NULL || $deviceid == '') && isset($_POST['vehicleno'])) {
                $vehicleno = GetSafeValueString($_POST['vehicleno'], 'string');
                $devicemanager = new DeviceManager($_SESSION['customerno']);
                $devices = $devicemanager->devicesformapping_byId($vehicleno);
                if ($devices) {
                    foreach ($devices as $row) {
                        $deviceid = $row->deviceid;
                    }
                }
            }

            $overspeed_limit = getoverspeed_limit($deviceid);
            if ($deviceid != 0) {
                include 'pages/panels/overspeedrep.php';
                $data = getoverspeedreport($STdate, $EDdate, $deviceid, $_POST['STime'], $_POST['ETime']);
                echo $data[0];
                $final_graph_data = $data[1];
            } else {
                echo $error3;
            }
        } else {
            echo $error2;
        }
    } else if ($datecheck == 0) {
        echo $error1;
    } else {
        echo $error;
    }
}
?>

<script type="text/javascript">
    $(function () {
<?php
if (isset($final_graph_data)) {
    ?>

            $('#overspeed_container').highcharts('StockChart', {
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
                    enabled: true,
                },
                title: {
                    text: 'Speed Report'
                },
                xAxis: {
                    type: 'datetime',
                },
                yAxis: {
                    title: {
                        text: 'Speed (km/hr)'
                    },
                    min: 0,
                    plotLines: [{
                            value: <?php echo $overspeed_limit; ?>,
                            color: 'black',
                            dashStyle: 'shortdash',
                            width: 2,
                            label: {
                                text: 'Overspeed Limit'
                            }
                        }]
                },
                tooltip: {
                    pointFormat: '{point.x:%e- %b}: {point.y:.0f} km/hr',
                },
                series: [
                    {
                        name: 'Speed',
                        data: [<?php echo $final_graph_data; ?>],
                        dataGrouping: {
                            enabled: false
                        },
                        threshold: <?php echo $overspeed_limit; ?>,
                        color: '#DE1D22',
                        negativeColor: '#01A4E3'

                    }]
            });
    <?php
} else {
    echo "jQuery('#overspeed_container').html('No data for Chart generation');";
}
?>
    });

</script>

