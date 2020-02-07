<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
require_once "class/class.api.php";

$apiobj = new api();
extract($_REQUEST);
$dcDetails = array('lat'=>19.02148000,'lng'=>73.10307000,'rad'=>0.24,'eta'=>'03:00:00');
if (isset($userkey) && $userkey != '' && $all == 1) {
    $pageIndex = isset($pageIndex) ? $pageIndex : 1;
    $pageSize = isset($pageSize) ? $pageSize : -1;
    $searchstring = isset($searchstring) ? $searchstring : '';
    $groupid = isset($groupid) ? $groupid : 0;
    $isRequiredThirdParty = isset($isRequiredThirdParty) ? $isRequiredThirdParty : 0;
    $deviceData = $apiobj->dashboardDevices($userkey, $pageIndex, $pageSize, $searchstring, $groupid, $isRequiredThirdParty,$dcDetails);
    $html = '';
    if (isset($deviceData) && isset($deviceData['result']) && !empty($deviceData['result'])) {
        foreach ($deviceData['result'] as $result) {
            $temphtml = '';
            $temp1html = '';

            $cnt = $result['temp_sensors'];
            $size = ((100 / $cnt)-5);

            $divsize = " width='" . $size . "%' ";

            $placehoders['{{WAREHOSUE}}'] = $result['vehicleno'];
            $hoders['{{WAREHOSUE}}'] = $result['vehicleno'];
            $hoders['{{LOCATION}}'] = $result['location'];
            $hoders['{{ETA}}'] = $result['eta'];
            $hoders['{{ARRIVES}}'] = $result['arrives'];
            $hoders['{{STATUS}}'] = $result['status'];
            $hoders['{{DEPOTNAME}}'] = $result['depotname'];
            $hoders['{{LASTUPDATED}}'] = $result['lastupdated'];

            $temp1html = file_get_contents('templates/temperature.html');
                foreach ($hoders as $key => $val) {
                    $temp1html = str_replace($key, $val, $temp1html);
                }


            $temphtml .= $temp1html;
            $html .= $temphtml;
        }
    } else {
        $html .= "Data Not Available";
    }
    //echo json_encode($deviceData);
    echo $html;
}
?>
