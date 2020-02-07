<?PHP

require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/CustomerManager.php';
require_once '../../lib/bo/DeviceManager.php';
require_once '../../lib/bo/VehicleManager.php';

class VOProbity {
    
}

//$serverpath = $_SERVER['DOCUMENT_ROOT']."/speed";
//$serverpath = "/home/elixia5/public_html/elixiatech.com/speed";
$cm = new CustomerManager();
$customernos = $cm->get_distinct_customerno_for_batch();
if (isset($customernos)) {
    foreach ($customernos as $thiscustomerno) {
        
        //For Customer No 48 For Rupesh Construction
        
        if($thiscustomerno->customerno == 48){
            $vm = new VehicleManager($thiscustomerno->customerno);
            $vehicles = $vm->get_all_vehicles_for_batch($thiscustomerno->customerno);
            $today = date('Y-m-d H:i:s');
            foreach ($vehicles as $vehicle) {
                    if($vehicle->imei == 99122 || $vehicle->imei == 99117 || $vehicle->imei ==900188 || $vehicle->imei==900354 || $vehicle->imei==99119 || $vehicle->imei==900709){
                        $customerno = $thiscustomerno->customerno;
                    $unitno = $vehicle->imei;
                 //   $messagekey = $vm->get_messagekey_for_vehicle($vehicle->vehicleid);
                    $day = date('Y-m-d', strtotime($vehicle->lastupdated));
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$day.sqlite";
                    $vehicle->firstodometer = getOdometer($vehicle->vehicleid, $location, $vehicle->lastupdated);
                    $vehicle->odometer;
                    $diff = ($vehicle->odometer - $vehicle->firstodometer) / 1000;
                    $vno = preg_replace("/[\s-;]+/", " ", $vehicle->vehicleno);
                    $datetime = date('m/d/Y h:i:s A', strtotime($vehicle->lastupdated));
                    $url = "http://203.129.224.98:8080/LiveWorks_Mumbai/LineDataCollector?vno=" . urlencode($vno) . "&lat=" . urlencode($vehicle->cgeolat) . "&long=" . urlencode($vehicle->cgeolong) . "&workkey=" . urldecode($vehicle->workkey) . "&imei=" . urlencode($vehicle->imei) . "&direction=" . urlencode($vehicle->direction) . "&distance=" . urlencode($diff) . "&velocity=" . urlencode($vehicle->curspeed) . "&datetime=" . urlencode($datetime) . "&loadno=" . urlencode($vehicle->batchno) . "&manufacurer=ELIXITECH"; 
                             
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    $result = curl_exec($ch);
                    var_dump($result);
                    curl_close($ch);
                    }
                    
            }
            
        }
        
        
        
         
        
//      For Customer No 15 & 21  for Supreme Infra
        if ($thiscustomerno->customerno == 15 || $thiscustomerno->customerno == 21) {            
            $vm = new VehicleManager($thiscustomerno->customerno);
            $vehicles = $vm->get_all_vehicles_for_batch($thiscustomerno->customerno);
            $today = date('Y-m-d H:i:s');
            if (!empty($vehicles)) {
                foreach ($vehicles as $vehicle) {
                    $customerno = $thiscustomerno->customerno;
                    $unitno = $vehicle->imei;
                    $messagekey = $vm->get_messagekey_for_vehicle($vehicle->vehicleid);
                    $day = date('Y-m-d', strtotime($vehicle->lastupdated));
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$day.sqlite";
                    if($vehicle->pmid !='0'){
                        $static = $vm->get_static_sqlite($vehicle->pmid);
						$locationstatic = "../../customer/$customerno/$static->master";
                    
                    if ($messagekey != 2) {
                        if (file_exists($location) && !empty($vehicle->batchno) && ($vehicle->starttime == '' || $vehicle->starttime != '' )) {
                            if ($vehicle->starttime != '' && strtotime($today) >= strtotime($vehicle->starttime) && $vehicle->dummybatchno != '') {
                                $path = "sqlite:$locationstatic";
                                $db = new PDO($path);
                                if(is_null($vehicle->last_fid)){
                                    $Query = "SELECT * FROM unithistory order by fid ASC limit 1 ";
                                }
                                else{
                                    $Query = "SELECT * FROM unithistory where fid>$vehicle->last_fid order by fid ASC limit 1 ";                                 }
                                $result = $db->query($Query);
                                $record = new VOProbity();

                                foreach ($result as $row) {
                                    $record->uhid = $row['uhid'];
                                    $record->uid = $row['uid'];
                                    $record->fid = $row['fid'];
                                    $record->unitno = $row['unitno'];
                                    $record->lastupdated = $row['lastupdated'];
                                    $record->vehicleno = $vehicle->vehicleno;
                                    $record->curspeed = getCurspeed($locationstatic, $row['lastupdated']);
                                    $record->firstodometer = getOdometerFirst($locationstatic);
                                    $record->lastodometer = getOdometer($locationstatic, $row['lastupdated']);
                                    $record->cgeolat = GetLat($locationstatic,  $row['fid']);
                                    $record->cgeolong = GetLong($locationstatic,  $row['fid']);
                                    $record->direction = GetDirection($locationstatic,  $row['fid']);
                                }
                                if(isset($record->fid) && !is_null($record->fid)){
                                    $vm->update_batch_fid($vehicle->vehicleid, $record->fid);
                                    $diff = ($record->lastodometer - $record->firstodometer) / 1000;
                                    $datetime = date('m/d/Y h:i:s A', strtotime($today));
                                   $url = "http://203.129.224.98:8080/LiveWorks_Mumbai/LineDataCollector?vno=" . urlencode($record->vehicleno) . "&lat=" . urlencode($record->cgeolat) . "&long=" . urlencode($record->cgeolong) . "&workkey=" . urldecode($static->workkey) . "&imei=" . urlencode($vehicle->imei) . "&direction=" . urlencode($record->direction) . "&distance=" . urlencode($diff) . "&velocity=" . urlencode($record->curspeed) . "&datetime=" . urlencode($datetime) . "&loadno=" . urlencode($vehicle->dummybatchno) . "&manufacurer=ELIXITECH";  
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                    $result = curl_exec($ch);
                                    var_dump($result);
                                    curl_close($ch);
                                }
                                else{
                                    $vm->reset_dummybatch($vehicle->vehicleid);
                                }

                                /*$query = "Update unithistory set flag = 1 where fid = $record->fid";

                                $db->query($query);
                                $sql = "SELECT count(*) from unithistory where flag <> 1";
                                $result1 = $db->query($sql)->fetchColumn();
                                if ($result1 < 1) {
                                    $query = "Update unithistory set flag = 0";

                                    $db->query($query);
                                    $vm->reset_dummybatch($vehicle->vehicleid);
                                }*/
                            }
                            else {
                                $vehicle->firstodometer = getOdometer($vehicle->vehicleid, $location, $vehicle->lastupdated);
                                $vehicle->odometer;
                                $diff = ($vehicle->odometer - $vehicle->firstodometer) / 1000;
                                $vno = preg_replace("/[\s-;]+/", " ", $vehicle->vehicleno);
                                $datetime = date('m/d/Y h:i:s A', strtotime($vehicle->lastupdated));
                              $url = "http://203.129.224.98:8080/LiveWorks_Mumbai/LineDataCollector?vno=" . urlencode($vno) . "&lat=" . urlencode($vehicle->cgeolat) . "&long=" . urlencode($vehicle->cgeolong) . "&workkey=" . urldecode($vehicle->workkey) . "&imei=" . urlencode($vehicle->imei) . "&direction=" . urlencode($vehicle->direction) . "&distance=" . urlencode($diff) . "&velocity=" . urlencode($vehicle->curspeed) . "&datetime=" . urlencode($datetime) . "&loadno=" . urlencode($vehicle->batchno) . "&manufacurer=ELIXITECH"; 

                             
                              $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                $result = curl_exec($ch);
                                var_dump($result);
                                curl_close($ch);
                               
                            }
                        }
                        else if (file_exists($locationstatic) && $vehicle->starttime != '' && $vehicle->dummybatchno != '' && strtotime($today) >= strtotime($vehicle->starttime)) {
                            $path = "sqlite:$locationstatic";
                            $db = new PDO($path);
                            if(is_null($vehicle->last_fid)){
                                $Query = "SELECT * FROM unithistory order by fid ASC limit 1 ";
                            }
                            else{
                                $Query = "SELECT * FROM unithistory where fid>$vehicle->last_fid order by fid ASC limit 1 ";                                 }
                            
                            $result = $db->query($Query);
                            $record = new VOProbity();
                            foreach ($result as $row) {
                                $record->uhid = $row['uhid'];
                                $record->uid = $row['uid'];
                                $record->fid = $row['fid'];
                                $record->unitno = $row['unitno'];
                                $record->lastupdated = $row['lastupdated'];
                                $record->vehicleno = $vehicle->vehicleno;
                                $record->curspeed = getCurspeed($locationstatic, $row['lastupdated']);
                                $record->firstodometer = getOdometerFirst($locationstatic);
                                $record->lastodometer = getOdometer($locationstatic, $row['lastupdated']);
                                $record->cgeolat = GetLat($locationstatic,  $row['fid']);
                                $record->cgeolong = GetLong($locationstatic,  $row['fid']);
                                $record->direction = GetDirection($locationstatic,  $row['fid']);
                            }
                            
                            if(isset($record->fid) && !is_null($record->fid)){
                                $vm->update_batch_fid($vehicle->vehicleid, $record->fid);
                                $diff = ($record->lastodometer - $record->firstodometer) / 1000;
                                $datetime = date('m/d/Y h:i:s A', strtotime($today));
                                $url = "http://203.129.224.98:8080/LiveWorks_Mumbai/LineDataCollector?vno=" . urlencode($record->vehicleno) . "&lat=" . urlencode($record->cgeolat) . "&long=" . urlencode($record->cgeolong) . "&workkey=" . urldecode($static->workkey) . "&imei=" . urlencode($vehicle->imei) . "&direction=" . urlencode($record->direction) . "&distance=" . urlencode($diff) . "&velocity=" . urlencode($record->curspeed) . "&datetime=" . urlencode($datetime) . "&loadno=" . urlencode($vehicle->dummybatchno) . "&manufacurer=ELIXITECH"; 

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                $result = curl_exec($ch);
                                var_dump($result);
                                curl_close($ch);
                            }
                            else{
                                $vm->reset_dummybatch($vehicle->vehicleid);
                            }
                            
                            /* 
                            $query = "Update unithistory set flag = 1 where fid = $record->fid";
                            $db->query($query);
                            $sql = "SELECT * from unithistory where flag <> 1";
                            //$result1 = $db->query($sql)->fetchColumn();
                            $result1 = $db->query($sql);
                            if (isset($result1) && $result1=="") {
                                $query = "Update unithistory set flag = 0";
                                $db->query($query);
                                $vm->reset_dummybatch($vehicle->vehicleid);
                            }*/

                        }
                    }
                    else {
                        $vm->reset_batchno($vehicle->vehicleid);
                    }
                    }
                    
                }
            }
        }
    }
}

function getOdometer($location, $date) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM vehiclehistory where lastupdated >= '$date' Order by lastupdated ASC Limit 1 ";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['odometer'];
        }
    } else {
        return 0;
    }
}

function getVehicle($location, $uid) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT vehicleno FROM vehiclehistory  limit 1";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['vehicleno'];
        }
    } else {
        return 0;
    }
}

function getCurspeed($location, $lastupdated) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT curspeed FROM vehiclehistory where  lastupdated >= '$lastupdated' order by lastupdated ASC limit 1";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['curspeed'];
        }
    } else {
        return 0;
    }
}

function getOdometerFirst($location) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM vehiclehistory Order by vehiclehistoryid ASC Limit 1 ";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['odometer'];
        }
    } else {
        return 0;
    }
}

function GetLat($location, $did) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM devicehistory where did = '$did' Limit 1 ";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['devicelat'];
        }
    } else {
        return 0;
    }
}

function GetLong($location, $did) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM devicehistory where did = '$did'  Limit 1 ";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['devicelong'];
        }
    } else {
        return 0;
    }
}

function GetDirection($location, $did) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM devicehistory where did = '$did'  Limit 1 ";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['directionchange'];
        }
    } else {
        return 0;
    }
}
?>

