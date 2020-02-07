<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
if (!defined('RELATIVE_PATH_DOTS')) {
    define("RELATIVE_PATH_DOTS", $RELATIVE_PATH_DOTS);
}

function get_odometer_reading($vehicleid, $s_date_time, $customerno,$unitno){
    $s_date = date("Y-m-d", strtotime($s_date_time));
    $full_path = RELATIVE_PATH_DOTS."customer/$customerno/unitno/$unitno/sqlite/$s_date.sqlite";
    if(file_exists($full_path)){
        $path = "sqlite:".RELATIVE_PATH_DOTS."customer/$customerno/unitno/$unitno/sqlite/$s_date.sqlite";
        $Query = "SELECT vehiclehistory.odometer from vehiclehistory
        WHERE vehiclehistory.vehicleid=$vehicleid AND vehiclehistory.lastupdated >= '$s_date_time'
        ORDER BY vehiclehistory.lastupdated asc Limit 1";

        try{
            $db = new PDO($path);
            $result = $db->query($Query);

            if(isset($result) && $result != ""){
                foreach ($result as $row){
                    $odometer = $row['odometer'];
                }
                return $odometer;
            }
        } catch (Exception $e) {
            prettyPrint($e);
           return '';
        }
    }
    else{
        return "";
    }
}

?>
