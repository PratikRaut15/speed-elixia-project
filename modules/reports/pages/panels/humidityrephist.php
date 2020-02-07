<?php
$title = 'Humidity Report';
if ($_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse'])) {
        $veh = $_SESSION['Warehouse'];
    } else {
        $veh = 'Warehouse';
    }
    $subTitle = array(
        "$veh : {$_POST['vehicleno']}",
        "Start Date: $sdate $stime",
        "End Date: $edate $etime",
        "Interval: $interval_p mins"
    );
} else {
    $subTitle = array(
        "Vehicle No: {$_POST['vehicleno']}",
        "Start Date: $sdate $stime",
        "End Date: $edate $etime",
        "Interval: $interval_p mins"
    );
}
echo table_header($title, $subTitle);
$deviceid = $_POST['deviceid'];
$unit = getunitdetails($deviceid);
?>
<br/><br/><div class="clearfix"></div><div class="container">
    <table class='table newTable' >
        <thead>
            <tr>
                <th>Time</th>
                <?php
                if ($_SESSION['switch_to'] != 3) {
                    echo "<th>Location</th>";
                }
                ?>
                <?php
                if ($_SESSION['use_humidity'] == 1) {
                    echo "<th>Humidity %</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>