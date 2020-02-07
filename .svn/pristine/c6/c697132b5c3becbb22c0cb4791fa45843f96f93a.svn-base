<?php
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/comman_function/reports_func.php';


$s_date = "27-02-2015";
$s_date = strtotime($s_date);
$s_date1 = "09-03-2015";
$s_date1 = strtotime($s_date1);
$c_date = strtotime($date);

class VODatacap {

}

$cm = new CustomerManager($customer_id);
$customer = $cm->getcustomerdetail_byid($customer_id);

if ($s_date1 == $c_date) {
    $location = "../../customer/$customer_id/reports/dailyreport_new.sqlite";
    //$location = "../../customer/$customer_id/reports/dailyreport.sqlite";
}
else {
    $location = "../../customer/$customer_id/reports/dailyreport.sqlite";
}
$cust_pass = strtolower(substr(preg_replace('/[\s\.]/', '', $customer->customername), 0, 3)) . $customer_id;

if (file_exists($location)) {
    $DATA = GetDailyReport_Data($location, $date);
}
$data = '';
if (isset($DATA)) {
    $vehiclemanager = new VehicleManager($customer_id);
    $geocode = '1';
    $i = 1;
    $data = '';
    $geocode = isset($_POST['geocode']) ? $_POST['geocode'] : null;
    $vehicle_group = array();
    if (!empty($groupid)) {
        $vehicle_group = $vehiclemanager->get_grouped_vehicles_by_groupid($customer_id, $groupid);
    }

    foreach ($DATA as $vehicle) {

        if (!empty($groupid)) {
            if (in_array($vehicle->vehicleid, $vehicle_group)) {
                $vehicles = $vehiclemanager->get_vehicle_details_pdf($vehicle->vehicleid, $customer->customerno);
            }
            else {
                continue;
            }
        }
        else {
            $vehicles = $vehiclemanager->get_vehicle_details_pdf($vehicle->vehicleid, $customer->customerno);
        }
        $groupname = $cm->getgroupname_new($vehicle->uid, $customer->customerno);
        $hours = floor($vehicle->runningtime / 60);
        $minutes = $vehicle->runningtime % 60;
        $idletimeHrs = $vehicle->idletime % 60;
        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }
        $hourss = floor($vehicle->genset / 60);
        $minutess = $vehicle->genset % 60;
        if ($minutess < 10) {
            $minutess = '0' . $minutess;
        }
        $data .= "<tr><td >$i</td><td >$vehicles->vehicleno</td>";
        $data .= "<td >$vehicles->drivername</td>";
        $data .= "<td >$groupname</td>";
        $data .= "<td >" . round(($vehicle->totaldistance / 1000), 2) . "</td>";
        $data .= "<td >$hours:$minutes</td>";
        $data .= "<td >$vehicle->trip_count</td>";
        $data .= "<td ></td>";
        $data .= "<td ></td>";
        $data .= "<td >$idletimeHrs Hrs</td>";
        $data .= "<td ></td>";
        $data .= "<td ></td>";
        $data .= "</tr>";
        $i++;
    }
}

if ($type == 'pdf') {
    require_once('../reports/html2pdf.php');
    $title = 'Trip Summary Report';
    $subTitle = array(
        "Date: $date"
    );
    echo pdf_header($title, $subTitle, $customer);
    ?>
    <br/><br/>
    <table id="search_table_2" style="width: 95%; border: none;padding-left: 30px;">
        <tr>
            <td style="width:30%;border: none;">FC: Fuel Consumed</td>
            <td style="width:40%;border: none;"></td>
            <td style="width:30%;border: none;">IT: Idle Time</td>
        </tr>
        <tr>
            <td style="width:30%;border: none;">DT: Distance Travelled [KM]</td>
            <td style="width:40%;border: none;"></td>
            <td style="width:30%;border: none;">RT: Running Time [HH:MM]</td>
        </tr>
        <tr>
            <td style="width:30%;border: none;">AS: At Site</td>
            <td style="width:40%;border: none;"></td>
            <td style="width:30%;border: none;">ITT: Idle Time in Transit</td>
        </tr>
    </table>
    <br/><br/>
    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <tbody>
            <tr style='background-color:#CCCCCC;font-weight:bold;'>
                <td >#</td>
                <td  >Vehicle No</td>
                <td style="width: 100px;">Driver Name</td>
                <td style="width: 100px;">Group</td>
                <td >DT</td>
                <td>RT</td>
                <td>No. of Trips</td>
                <td>Fuel Cosumed</td>
                <td>Mileage</td>
                <td>Idle Time</td>
                <td>At Site</td>
                <td>ITT</td>

            </tr>
    <?php
    if (isset($data)) {
        echo $data;
    }
    ?>
        </tbody>
    </table>
    <hr style='margin-top:5px;'>
    <?php if ($s_date <= $c_date) { ?>
        Note :  <br/>
        <ul style='float:left;text-align:left;'>
            <li>- Daily Trip Summary Report does not consider offline data. Offline Data is the data wherein the device is under a low network area and device sends data when it comes in network.
            </li>
            <li>- FreeWheeling - FreeWheeling either means riding on a downhill with ignition off to save fuel or there is some issue with the ignition connection. If you see Freewheeling on a frequent basis, please get the ignition wire connection checked.</li>
            <li>- Online data field gives you an approximate indication of the actual time the device sent real time data.</li>
            <li>- If you see any erratic data in this report, you may shoot an email to support@elixiatech.com and we will be there to support.
            </li>
            <li>- When unit is replaced, daily trip summary report will be valid for the new unit only.</li>
        </ul>
    <?php } ?>
    <!--
    <div align='right' style='text-align:center;'> Report Generated On: <?php //echo date(speedConstants::DEFAULT_DATETIME); ?></div><hr>
    -->
    <?php
    $content = ob_get_clean();

    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($date . "_summaryreport.pdf");
    }
    catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
else {
    require_once '../../lib/bo/simple_html_dom.php';
    $title = 'Trip Summary Report';
    $subTitle = array(
        "Date: $date"
    );

    $finalreport = excel_header($title, $subTitle, $customer);
    $finalreport .= "<table><tr><td>DT: Distance Travelled [KM]</td></tr><tr><td>RT: Running Time [HH:MM]</td></tr><tr></tr></table>";
    $finalreport .= "
    <table id='search_table_2' style='width: 1000px; font-size:9px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <tbody>
        <tr style='background-color:#CCCCCC;font-weight:bold;'>
            <td >#</td>
            <td >Vehicle No</td>
            <td >Driver Name</td>
            <td>Group</td>
            <td >Distance Travelled</td>
            <td >RT</td>
            <td >No. of Trips</td>
            <td >Fuel Consumed</td>
            <td >Mileage</td>
            <td >Idle Time</td>
            <td >At Site</td>
            <td >Idle Time in Transit</td>
            ";


    $contentcsv = $finalreport;
    $contentcsv .= $data;
    $contentcsv .= "</tbody></table><hr style='margin-top:5px;'>";

    $html = str_get_html($contentcsv);
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename={$date}_summaryreport.xls");
    echo $html;
}

function GetDailyReport_Data($location, $days) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    $sqlday = date("dmy", strtotime($days));
    $query = "SELECT * from A$sqlday";
    $result = $db->query($query);

    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            $Datacap = new VODatacap();
            $Datacap->date = strtotime($days);
            $Datacap->uid = $row['uid'];
            $Datacap->avgspeed = $row['avgspeed'];
            $Datacap->genset = $row['genset'];
            $Datacap->overspeed = $row['overspeed'];
            $Datacap->totaldistance = $row['totaldistance'];
            $Datacap->fenceconflict = $row['fenceconflict'];
            $Datacap->idletime = $row['idletime'];
            $Datacap->runningtime = $row['runningtime'];
            $Datacap->vehicleid = $row['vehicleid'];
            $Datacap->dev_lat = $row['dev_lat'];
            $Datacap->dev_long = $row['dev_long'];
            $Datacap->first_dev_lat = $row['first_dev_lat'];
            $Datacap->first_dev_long = $row['first_dev_long'];
            $Datacap->harsh_break = $row['harsh_break'];
            $Datacap->sudden_acc = $row['sudden_acc'];
            if ($row['towing'] == 0) {
                $Datacap->towing = "No";
            }
            else {
                $Datacap->towing = "Yes";
            }

            $Datacap->overspeed = $row['overspeed'];
            $Datacap->topspeed = $row['topspeed'];
            $Datacap->topspeed_lat = $row['topspeed_lat'];
            $Datacap->topspeed_long = $row['topspeed_long'];
            $Datacap->avgdistance = $row['average_distance'];
            $Datacap->trip_count = $row['trip_count'];
            $REPORT[] = $Datacap;
        }
    }
    return $REPORT;
}

function get_location_detail($lat, $long, $customerno) {
    $geo_location = "N/A";
    if ($lat != "" && $long != "") {
        $geo_obj = new GeoCoder($customerno);
        $geo_location = $geo_obj->get_location_bylatlong($lat, $long);
    }
    return $geo_location;
}

function getlocation($lat, $long, $geocode, $customerno) {
    $address = null;
    $usegeolocation = get_usegeolocation($customerno);
    $key = $lat . $long;
    if (!isset($GLOBALS[$key])) {
        if ($lat != '0' && $long != '0') {
            if (isset($_SESSION['Session_UserRole']) && $_SESSION['Session_UserRole'] == "elixir" && $geocode == "2") {

                $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
                $location = json_decode(file_get_contents($API . "&sensor=false"));
                @$address = $location->results[0]->formatted_address;
//
            }
            else {



                if ($usegeolocation == 1) {
                    $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
                    $location = json_decode(file_get_contents("$API&sensor=false"));
                    @$address = "Near " . $location->results[0]->formatted_address;
                    if ($location->results[0]->formatted_address == "") {
                        $GeoCoder_Obj = new GeoCoder($customerno);
                        $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
                    }
                }
                else {
                    $GeoCoder_Obj = new GeoCoder($customerno);
                    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
                }
            }
        }
        $output = $address;
        $GLOBALS[$key] = $address;
    }
    else {
        $output = $GLOBALS[$key];
    }
    return $output;
}

function get_usegeolocation($customerno) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $geolocation = $GeoCoder_Obj->get_use_geolocation();
    return $geolocation;
}
?>
