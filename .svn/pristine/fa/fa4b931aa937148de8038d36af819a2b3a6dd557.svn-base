<?php
$title = 'Temperature Exception Report';
if ($_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse'])) {
        $veh = $_SESSION['Warehouse'];
    } else {
        $veh = 'Warehouse';
    }
    $subTitle = array(
        "$veh: {$_POST['vehicleno']}",
        "Start Date: $sdate $stime",
        "End Date: $edate $etime",
        "Interval: $interval_p mins",
        "Temperature Limit(Max): $temp_max_limit &deg;C",
        "Temperature Limit(Min): $temp_min_limit &deg;C"
    );
} else {
    $subTitle = array(
        "Vehicle No: {$_POST['vehicleno']}",
        "Start Date: $sdate $stime",
        "End Date: $edate $etime",
        "Interval: $interval_p mins",
        "Temperature Limit(Max): $temp_max_limit &deg;C",
        "Temperature Limit(Min): $temp_min_limit &deg;C"
    );
}
echo table_header($title, $subTitle);
?>


<br/><br/>

<div class="clearfix"></div>

<div class="container">
    <table class='table newTable' >
        <thead>
            <tr>
                <th>Start Date</th>
                <th>Start Time</th>
                <th>Start Temp</th>
                <th>End Date</th>
                <th>End Time</th>
                <th>End Temp</th>
                <th>Duration [HH:MM]</th>
            </tr>
        </thead>
        <tbody>
