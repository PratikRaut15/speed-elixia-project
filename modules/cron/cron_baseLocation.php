<?php
$RELATIVE_PATH_DOTS = "../../";
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
$customerNo = isset($_GET['customerno']) ? $_GET['customerno'] : 0;
if ($customerNo == 0 || $customerNo == '') {
    echo "Please Enter Customer No.";
} else {
    $um = new UnitManager($customerNo);
    $cm = new CronManager();
    $unitArray = array();
    $fileNotFound = array();
    $recordNotFound = array();
    $latlongArray = array();
    $unitArray = $um->cron_getunitForCust($customerNo);
    $i = 0;
    $j = 0;
    foreach ($unitArray as $unitNo) {
        //print_r($unitNo->unitno);
        $location = "../../customer/$customerNo/unitno/$unitNo->unitno/sqlite/2019-03-18.sqlite";
        if (file_exists($location)) {
            $location = "sqlite:" . $location;
            $query = "SELECT u.unitno,v.vehicleno,d.devicelat, d.devicelong FROM  devicehistory d
						  INNER JOIN unithistory u on u.uid = d.uid
						  INNER JOIN vehiclehistory v on v.vehicleid = u.vehicleid
						  WHERE d.lastupdated BETWEEN '2019-03-18 02:00:00' AND '2019-03-18 03:00:00' AND d.ignition = 0
						  AND v.curspeed = 0 AND d.devicelat NOT LIKE '%00%' AND d.devicelong NOT LIKE '%00%' LIMIT 1  ";
            $database = new PDO($location);
            $temp_result = $database->query($query);
            $result = $temp_result->fetch(PDO::FETCH_ASSOC);
            if (is_array($result) && !empty($result)) {
                $latlongArray[$unitNo->unitno] = $result;
            } else {
                $recordNotFound[$j] = $unitNo->unitno;
                $j++;
            }
        } else {
            $fileNotFound[$i] = $unitNo->unitno;
            $i++;
        }
    }
    // echo "<pre>";
    // echo "LAT LONG ARRAY ";
    // print_r($latlongArray);
    // echo "FILE NOT FOUND ";
    //print_r($fileNotFound);
    //die();
    // echo "NO RECORDS ";
    // print_r($recordNotFound);
}
//FINAL HTML
echo 'CORRECT ARRAY';
foreach ($latlongArray as $key => $record) {
    $key = $record['unitno'];
    $address = getAddress($record['devicelat'], $record['devicelong']);
    $latlongArray[$key]['address'] = $address;
}
$html = '';
$html = '<table border=1>';
$html .= '<th>Unit No</th><th>Vehicle No</th><th>Lat</th><th>Long</th><th>Address</th>';
foreach ($latlongArray as $result) {
    $html .= '<tr><td>' . $result['unitno'] . '</td><td>' . $result['vehicleno'] . '</td><td>' . $result['devicelat'] . '</td><td>' . $result['devicelong'] . '</td><td>' . $result['address'] . '</td></tr>';
}
$html .= '</table>';
echo $html;
//FILE NOT FOUND
echo 'FILE NOT FOUND';
$html_2 = '';
$html_2 = '<table border=1>';
$html_2 .= '<th>Unit No</th>';
foreach ($fileNotFound as $result) {
    // print_r($result);
    $html_2 .= '<tr><td>' . $result . '</td></tr>';
}
$html_2 .= '</table>';
echo $html_2;
//RECORD NOT FOUND
echo 'RECORD NOT FOUND';
$html_3 = '';
$html_3 = '<table border=1>';
$html_3 .= '<th>Unit No</th>';
foreach ($recordNotFound as $result) {
    $html_3 .= '<tr><td>' . $result . '</td></tr>';
}
$html_3 .= '</table>';
echo $html_3;
function getAddress($latitude, $longitude) {
    if (!empty($latitude) && !empty($longitude)) {
        $API = signLocationUrl("http://maps.google.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude . "&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
        $location = json_decode(file_get_contents("$API&sensor=false"));
        $area_name = $location->results[0]->formatted_address;
        if (isset($location) && isset($area_name) && $area_name != "") {
            $address = $area_name;
            return $address;
        } else {
            $address = "Unknown Location";
        }
    } else {
        return false;
    }
}

?>