<?php
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/comman_function/reports_func.php';

$customerno = $customer_id;
$cm = new CustomerManager($customerno);
$customer = $cm->getcustomerdetail_byid($customerno);
if($type == 'pdf'){
    $reportType = "PDF";
}else if($type == 'xls'){
    $reportType = "XLS";
}
$filesuffix = "exceptionReport";
$title = "Exception Report";
$subTitle = array(
        "Date: $date"
    );
$columns = array("#","Vehicle No", "Overspeed", "Harsh Break", "Sudden Acceleration");
$savefile = 0;
$location = "../../customer/$customer_id/reports/dailyreport.sqlite";
$cust_pass = strtolower(substr(preg_replace('/[\s\.]/', '', $customer->customername), 0, 3)) . $customerno;
if (file_exists($location)) {
    $DATA = GetDailyReport_Data($location, $date);
}
exceptionReport_html($title, $subTitle, $columns, $customer, $DATA, $reportType);
?>
Note :  <br/>
        <ul style='float:left;text-align:left;'>
            <li>- Daily Exception Report does not consider offline data. Offline Data is the data wherein the device is under a low network area and device sends data when it comes in network.</li>
            <li>- Online data field gives you an approximate indication of the actual time the device sent real time data.</li>
            <li>- If you see any erratic data in this report, you may shoot an email to support@elixiatech.com and we will be there to support.</li>
            <li>- Harsh Break is speed drop of 40 km / hr per second.</li>
            <li>- Sudden Acceleration is speed rise of 20 km / hr per second.</li>
            <li>- When unit is replaced, daily exception report will be valid for the new unit only.</li>
        </ul>
<?php
$content = ob_get_clean();
$full_path = $filesuffix;
renderReport($reportType,$content,$full_path,$savefile);

function GetDailyReport_Data($location, $days) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    $sqlday = date("dmy", strtotime($days));
    $query = "SELECT * from A$sqlday";
    $result = $db->query($query);
    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            $Datacap = new stdClass();
            $Datacap->date = strtotime($days);
            $Datacap->uid = $row['uid'];
            $Datacap->vehicleid = $row['vehicleid'];
            $Datacap->overspeed = $row['overspeed'];
            $Datacap->harsh_break = $row['harsh_break'];
            $Datacap->sudden_acc = $row['sudden_acc'];
            $REPORT[] = $Datacap;
        }
    }
    return $REPORT;
}

function exceptionReport_html($title, $subTitle, $columns, $customer_details, $datarows, $type){
    echo report_header($type, $title, $subTitle, $columns,$customer_details);
    displayExceptionReport($datarows,$type,$customer_details->customerno);
}

function displayExceptionReport($datarows,$type,$customerno=null){
    $data = '';
    if (isset($datarows)) {
        $vehiclemanager = new VehicleManager($customerno);
        $i = 1;
        foreach ($datarows as $vehicle) {
            if($vehicle->overspeed > 0 || $vehicle->harsh_break > 0 || $vehicle->sudden_acc > 0){
               $vehicles = $vehiclemanager->get_vehicle_details_pdf($vehicle->vehicleid, $customerno);
               $veh_no = @$vehicles->vehicleno;
               if($veh_no != ''){
                $data .= "<tr><td >$i</td><td>$veh_no</td>";
                $data .= "<td> $vehicle->overspeed</td>";
                $data .= "<td>$vehicle->harsh_break</td>";
                $data .= " <td>$vehicle->sudden_acc</td>";
                $data .= "</tr>";
                $i++;
               }

            }
        }
    }else{
        $data .="<tr><td colspan='5' style='text-align:center;'>No Data</td></tr>";
    }
    $data .="</tbody></table></div>";
    echo $data;
}
?>