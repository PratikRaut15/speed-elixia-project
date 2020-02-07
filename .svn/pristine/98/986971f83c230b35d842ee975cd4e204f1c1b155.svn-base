<?php
    include 'route_dashboard_functions.php';
    $yesterday = $date = date("d-m-Y", strtotime("-1 days"));
    $sdate = retval_issetor($_POST['SDate'], $yesterday); //date - replace with sdate
    $edate = retval_issetor($_POST['EDate'], $yesterday);
    $title = 'Summary Report';
    $subTitle = array(
        "From Date: $sdate",
        "End Date: $edate",
    );
    $dailyReportChangeDate = "20-02-2015";
    $dailyReportChangeDate = strtotime($dailyReportChangeDate);
    $startdate = strtotime($sdate);
    $enddate = strtotime($edate);
    $changedate = "2016-03-03";
    echo table_header($title, $subTitle);

?>
<div class="container-fluid">
    <ul style='font-size:13px;float:left;margin:0px; text-align: left;  font-weight:bold;'>
        <li>SL: Start Location</li>
        <li>EL: End Location</li>
        <li>DT: Distance Travelled [KM]</li>

        <?php if ($_SESSION['customerno'] != '135') {?>
        <li>AS: Average Speed [KM/HR]</li>
        <?php }?>
        <li>RT: Running Time [HH:MM]</li>
        <?php if ($startdate <= $enddate) {?>
        <li>OS: Overspeed [Times] / (Overspeed Limit [km / hr])</li>
        <?php if ($_SESSION['customerno'] == '126') {?>
        <li>SK: Start KM</li>
        <li>EK: End KM</li>
        <?php }?>
<?php }
    if ($_SESSION['use_door_sensor'] == 1) {
        echo "<li>DSU: Door Sensor Usage [HH:MM]</li>";
    }
    if ($_SESSION['use_ac_sensor'] == 1) {
        echo "<li>ACU: AC Usage [HH:MM]</li>";
    }
    if ($_SESSION['use_genset_sensor'] == 1) {
        echo "<li>GNU: Genset Usage [HH:MM]</li>";
    }
?>
    </ul>
    <?php if ($dailyReportChangeDate <= $startdate) {?>
    <ul style='font-size:13px;float:right;margin:0px; text-align: left;font-weight:bold;'>
        <li>TS: Top Speed [KM/HR]</li>
        <li>TSL: Top Speed Location</li>

        <?php
        if ($startdate >= strtotime($changedate)) {?>
        <li>ION: Idle Ignition On Time [HH:MM]</li>
        <li>IOFF: Idle Ignition Off Time [HH:MM]</li>
        <?php }?>
    </ul>
    <?php }?>
    <table class="table newTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Vehicle No</th>
                <th>Driver Name</th>
                <th>Group</th>
                <th>SL</th>
                <th>EL</th>
                <th>TSL</th>
                <th>DT</th>
                <?php if ($_SESSION['customerno'] == 126) {?>
                <th>SK</th>
                <th>EK</th>
                <?php }?>
                <?php if ($_SESSION['customerno'] != '135') {?>
                <th>AS</th>
                <?php }?>
                <th>RT</th>
                <?php if ($startdate >= strtotime($changedate)) {?>
                <th>ION</th>
                <th>IOFF</th>
                <?php }
                    if ($_SESSION['use_door_sensor'] == 1) {
                        echo "<th>DSU</th>";
                    }
                    if ($_SESSION['use_ac_sensor'] == 1) {
                        echo "<th>ACU</th>";
                    }
                    if ($_SESSION['use_genset_sensor'] == 1) {
                        echo "<th>GNU</th>";
                    }
                if ($dailyReportChangeDate <= $startdate) {?>
                    <th>OS</th>
                    <th>TS</th>

                <?php }?>
            </tr>
        </thead>
        <tbody>
        <?php
            $datediffcheck = date_SDiff($sdate, $edate);
            if ($datediffcheck <= 30) {
                $DATA = get_data_fordashboard($sdate, $edate);
            } else {
                echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
            }
            echo "</tbody>";
            if ($startdate <= $enddate) {
                echo "</table>
                    <ul style='float:left;text-align:left;'>Note : <br/><br/>
                        <li>- Daily Summary Report does not consider offline data. Offline Data is the data wherein the device is under a low network area and device sends data when it comes in network.</li>
                        <li>- FreeWheeling - FreeWheeling either means riding on a downhill with ignition off to save fuel or there is some issue with the ignition connection. If you see Freewheeling on a frequent basis, please get the ignition wire connection checked.</li>
                        <li>- Online data field gives you an approximate indication of the actual time the device sent real time data.</li>
                        <li>- Average Distance is calculated in effect from Feb 28, 2015.</li>
                        <li>- When unit is replaced, daily summary report will be valid for the new unit only.</li>
                        <li>- If you see any erratic data in this report, you may shoot an email to support@elixiatech.com and we will be there to support.</li>
                    </ul> ";
            }
            echo "</div>";
        ?>
