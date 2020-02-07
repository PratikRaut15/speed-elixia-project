<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {

    include_once 'reports_fuel_functions.php';

    $customerno = exit_issetor($_SESSION['customerno']);
    $STdate = date('d-m-Y', strtotime($_POST['STdate']));
    $STime = $_POST['STime'];
    $EDdate = date('d-m-Y', strtotime($_POST['EDdate']));
    $ETime = $_POST['ETime'];
    $vehicleid = (int) $_POST['vehicleid'];
    $vehicleno = $_POST['vehicleno'];
    $interval = (int) $_POST['interval'];

    $datediffcheck = datediff_cmn($STdate, $EDdate);

    if (strtotime($STdate) > strtotime($EDdate)) {
        echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(5000)</script>";
        exit;
    } else if ($vehicleid == 0) {
        echo "<script>jQuery('#error3').show();jQuery('#error3').fadeOut(5000)</script>";
        exit;
    } else {

        if ($datediffcheck <= 30) {

            $reports = get_fuel_report($STdate, $STime, $EDdate, $ETime, $vehicleid, $interval, $customerno);
            if ($_SESSION['switch_to'] == 3) {
                if (isset($_SESSION['Warehouse'])) {
                    $vehicle = $_SESSION['Warehouse'];
                } else {
                    $vehicle = 'Warehouse';
                }
            } else {
                $vehicle = 'Vehicle No.';
            }
            $title = 'Fuel Report';
            $subTitle = array(
                "$vehicle: $vehicleno",
                "Start Date: $STdate $STime",
                "End Date: $EDdate $ETime"
            );
            $columns = array('Time', 'Location', 'Fuel Level (In litres)');

            echo table_header($title, $subTitle);
            if ($reports['status'] == 1) {
                echo '<div class="container"><div id="fuel_container" style="min-width: 910px; min-height: 500px; margin: 0 auto;"></div></div>';
            }
            include 'pages/display_fuel_new.php';
        } else {
            echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(5000);</script>";
            exit;
        }
    }
}
?>
<?php if ($reports['status'] == 1) { ?>
    <script type="text/javascript">
        jQuery(function () {
            $('#fuel_container').highcharts('StockChart', {
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
                legend: {
                    enabled: true,
                    align: 'bottom',
                    borderColor: 'black',
                    borderWidth: 1,
                    layout: 'horizontal',
                    verticalAlign: 'top',
                    //y: 50,
                    shadow: true
                },
                title: {
                    text: '<?php echo $title; ?>'
                },
                xAxis: {
                    type: 'datetime',
                },
                yAxis: {
                    title: {
                        text: 'Litre'
                    },
                    min: 0
                },
                tooltip: {
                    pointFormat: '{point.x:%e- %b}: {point.y:.1f} ltr',
                },
                navigator: {
                    series: {
                        id: 'nav'
                    }
                },
                series: [
                    {
                        name: 'Fuel',
                        data: [<?php echo $reports['graph']; ?>],
                        dataGrouping: {
                            enabled: false
                        },
                        color: '#006301',
                    },
                ]
            }, function (chart) {
                var navigator = chart.get('nav');
                navigator.setData([<?php echo $reports['graph']; ?>]);
            }
            );
        });
    </script>
<?php } ?>