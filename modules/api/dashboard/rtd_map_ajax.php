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
if (isset($userkey) && $userkey != '' && $all == 1) {
    $isWareHouse = isset($isWareHouse) ? $isWareHouse : 1;
    $pageIndex = isset($pageIndex) ? $pageIndex : 1;
    $pageSize = isset($pageSize) ? $pageSize : -1;
    $searchstring = isset($searchstring) ? $searchstring : '';
    $groupid = isset($groupid) ? $groupid : 0;
    $isRequiredThirdParty = isset($isRequiredThirdParty) ? $isRequiredThirdParty : 0;
    $deviceData = $apiobj->dashboardDevices($userkey, $pageIndex, $pageSize, $searchstring, $groupid, $isRequiredThirdParty, $isWareHouse);
    $html = '';
    if (isset($deviceData) && isset($deviceData['result']) && !empty($deviceData['result'])) {
        foreach ($deviceData['result'] as $result) {
            $temphtml = '';
            $temp1html = '';
            $temp2html = '';
            $temp3html = '';
            $temp4html = '';
            $cnt = $result['temp_sensors'];
            $size = ((100 / $cnt)-5);

            $divsize = " width='" . $size . "%' ";
            $t1 = $t2 = $t3 = $t4 = $temp1 = $temp1 = $temp3 = $temp4 = $statu1 = $statu2 = $statu3 = $statu4 = $t1_range = $t2_range = $t3_range = $t4_range = '';
            $placehoders['{{WAREHOSUE}}'] = $result['vehicleno'];
            $placehoders['{{vehicleStatus}}'] = $result['vehicleStatus'];
            $hoders['{{WAREHOSUE}}'] = $result['vehicleno'];
            $hoders['{{LASTUPDATED}}'] = $result['lastupdated'];
            switch ($result['temp_sensors']) {
            case 4:
                $t4 = $result['n4'];
                $temp4 = $result['temp4'];
                $status4 = $result['status4'];
                $t4_range = $result['temp4_min'] . " To " . $result['temp4_max'];

                $hoders['{{TEMPNAME}}'] = $t4;
                $hoders['{{TEMP}}'] = $temp4;
                if($temp4 == speedConstants::TEMP_WIRECUT || $temp4 == speedConstants::TEMP_NOTACTIVE){
                    $status4 = 'notActive';
                }
                $hoders['{{TEMPRANGE}}'] = $t4_range;
                $hoders['{{TEMPSTATUS}}'] = $status4;
                $hoders['{{DIVSIZE}}'] = $divsize;
                $hoders['{{STATUS}}'] = '';
                if(isset($result['kind']) && $result['kind'] != "Warehouse"){
                    $hoders['{{STATUS}}'] = "<br><div style='background-color: gray; padding: 10px; margin-top: 10px;'>".$result['veh_status']."</div>";
                }
                $temp4html = file_get_contents('templates/temperature.html');
                foreach ($hoders as $key => $val) {
                    $temp4html = str_replace($key, $val, $temp4html);
                }
            case 3:
                $t3 = $result['n3'];
                $temp3 = $result['temp3'];
                $status3 = $result['status3'];
                $t3_range = $result['temp3_min'] . " To " . $result['temp3_max'];
                $hoders['{{TEMPNAME}}'] = $t3;
                $hoders['{{TEMP}}'] = $temp3;
                if($temp3 == speedConstants::TEMP_WIRECUT || $temp3 == speedConstants::TEMP_NOTACTIVE){
                    $status3 = 'notActive';
                }
                $hoders['{{TEMPRANGE}}'] = $t3_range;
                $hoders['{{TEMPSTATUS}}'] = $status3;
                $hoders['{{DIVSIZE}}'] = $divsize;
                $hoders['{{STATUS}}'] = '';
                if(isset($result['kind']) && $result['kind'] != "Warehouse"){
                    $hoders['{{STATUS}}'] = "<br><div style='background-color: gray; padding: 10px;'>".$result['veh_status']."</div>";
                }
                $temp3html = file_get_contents('templates/temperature.html');
                foreach ($hoders as $key => $val) {
                    $temp3html = str_replace($key, $val, $temp3html);
                }
            case 2:
                $t2 = $result['n2'];
                $temp2 = $result['temp2'];
                $status2 = $result['status2'];
                $t2_range = $result['temp2_min'] . " To " . $result['temp2_max'];
                $hoders['{{TEMPNAME}}'] = $t2;
                $hoders['{{TEMP}}'] = $temp2;
                if($temp2 == speedConstants::TEMP_WIRECUT || $temp2 == speedConstants::TEMP_NOTACTIVE){
                    $status2 = 'notActive';
                }
                $hoders['{{TEMPRANGE}}'] = $t2_range;
                $hoders['{{TEMPSTATUS}}'] = $status2;
                $hoders['{{DIVSIZE}}'] = $divsize;
                $hoders['{{STATUS}}'] = '';
                if(isset($result['kind']) && $result['kind'] != "Warehouse"){
                    $hoders['{{STATUS}}'] = "<br><div style='background-color: gray; padding: 10px;'>".$result['veh_status']."</div>";
                }
                $temp2html = file_get_contents('templates/temperature.html');
                foreach ($hoders as $key => $val) {
                    $temp2html = str_replace($key, $val, $temp2html);
                }
            case 1:
                $t1 = $result['n1'];
                $temp1 = $result['temp1'];
                $status1 = $result['status1'];
                $t1_range = $result['temp1_min'] . " To " . $result['temp1_max'];
                $hoders['{{TEMPNAME}}'] = $t1;
                $hoders['{{TEMP}}'] = $temp1;
                if($temp1 == speedConstants::TEMP_WIRECUT || $temp1 == speedConstants::TEMP_NOTACTIVE){
                    $status1 = 'notActive';
                }
                $hoders['{{TEMPRANGE}}'] = $t1_range;
                $hoders['{{TEMPSTATUS}}'] = $status1;
                $hoders['{{DIVSIZE}}'] = $divsize;
                $hoders['{{STATUS}}'] = '';
                if(isset($result['kind']) && $result['kind'] != "Warehouse"){
                    $hoders['{{STATUS}}'] = "<br><div style='background-color: gray; padding: 10px;'>".$result['veh_status']."</div>";
                }
                $temp1html = file_get_contents('templates/temperature.html');
                foreach ($hoders as $key => $val) {
                    $temp1html = str_replace($key, $val, $temp1html);
                }

            }
            $disp = '';
                switch ($result['temp_sensors']) {
                    case 4:
                        $tableMain = "tableMain4";
                        break;
                    case 3:
                        $tableMain = "tableMain3";
                        break;
                     case 2:
                       $tableMain = "tableMain2";
                        break;
                    case 1:
                        $tableMain = "tableMain1";
                        break;
                    default:
                       $tableMain = "tableMain";
                        break;
                }
            // $disp .= "<table width='100%;' id='tableMain'>";
                $disp .= "<table width='100%;' id='$tableMain'>";
            $disp .= "<tr>";
            if($temp1html!=''){
                $disp .= "<td ".$divsize.">".$temp1html."</td>";
            }
            if($temp2html!=''){
                $disp .= "<td ".$divsize.">".$temp2html."</td>";
            }
            if($temp3html!=''){
                $disp .= "<td ".$divsize.">".$temp3html."</td>";
            }
            if($temp4html!=''){
                $disp .= "<td ".$divsize.">".$temp4html."</td>";
            }
            $disp .="</tr>";
            $disp .="</table>";
            $temphtml .= $disp;
            $html .= $temphtml;
        }
    } else {
        $html .= "Data Not Available";
    }
    //echo json_encode($deviceData);
    echo $html;
}
?>
