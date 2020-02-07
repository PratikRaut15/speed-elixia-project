<?php
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');
ini_set('display_errors', 'on');
$RELATIVE_PATH_DOTS = "../../../";
require_once "../../../lib/system/utilities.php";
require_once "../../../lib/autoload.php";
require_once "class/class.api.php";
$apiobj = new api(); //class
$arrArea = $apiobj->getAreaMasterList();
if (isset($arrArea) && !empty($arrArea)) {
    foreach ($arrArea as $areaval) {
        $lat = "";
        $long = "";
        $address = urlencode($areaval->address);
        $key = "";
        $google_api1 = signLocationUrl("http://maps.google.com/maps/api/geocode/json?address=$address&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $google_api1);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        $results1 = curl_exec($ch1);
        $array_data1 = json_decode($results1);
        $partial_match = retval_issetor($array_data1->results[0]->partial_match, null);
        if ($array_data1->status === 'OK' && $partial_match == 0) {
            $location = $array_data1->results[0]->geometry->location;
            $lat = $location->lat;
            $long = $location->lng;
        }
        curl_close($ch1);
        $objArea = new stdClass();
        $objArea->areaid = $areaval->areaid;
        $objArea->address = $areaval->address;
        $objArea->lat = $lat;
        $objArea->lng = $long;
        $apiobj->updateAreaMaster($objArea);
    }
}
function updateAreaMaster($objArea) {
    $slq = "UPDATE areamaster SET lat='$objArea->lat', lng='$objArea->lng' WHERE areaid= $objArea->areaid ";
    $this->db->query($sql, __FILE__, __LINE__);
}

function getAreaMasterList() {
    $arrArea = array();
    $sql = "select * from areamaster";
    $record = $this->db->query($sql, __FILE__, __LINE__);
    while ($row = $this->db->fetch_array($record)) {
        $area = new stdClass();
        $area->areaid = $row['areaid'];
        $area->address = $row['address'];
        $arrArea[] = $area;
    }
    return $arrArea;
}

function retval_issetor(&$var, $def = false) {
    return isset($var) ? $var : $def;
}

?>