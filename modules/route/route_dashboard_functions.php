<?php
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/RouteManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function addroute($routename, $routearray, $vehiclearray)
{
    $routename = GetSafeValueString($routename,"string");
    $routearray = GetSafeValueString($routearray,"string");
    $vehiclearray = GetSafeValueString($vehiclearray,"string");
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routemanager->add_route($routename, $routearray, $vehiclearray, $_SESSION['userid']);
}

function addroute_enh($routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray)
{
    $routename = GetSafeValueString($routename,"string");
    $routearray = GetSafeValueString($routearray,"string");
    $vehiclearray = GetSafeValueString($vehiclearray,"string");
    $thourarray = GetSafeValueString($thourarray,"string");
    $tminarray = GetSafeValueString($tminarray,"string");
    $distancearray = GetSafeValueString($distancearray,"string");
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routemanager->add_route_enh($routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray, $_SESSION['userid']);
}

function editroute($routeid, $routename, $routearray, $vehiclearray)
{
    $routeid = GetSafeValueString($routeid,"string");
    $routename = GetSafeValueString($routename,"string");
    $routearray = GetSafeValueString($routearray,"string");
    $vehiclearray = GetSafeValueString($vehiclearray,"string");
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routemanager->edit_route($routeid, $routename, $routearray, $vehiclearray, $_SESSION['userid']);
}
function editroute_enh($routeid, $routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray)
{
    $routeid = GetSafeValueString($routeid,"string");
    $routename = GetSafeValueString($routename,"string");
    $routearray = GetSafeValueString($routearray,"string");
    $vehiclearray = GetSafeValueString($vehiclearray,"string");
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routemanager->edit_route_enh($routeid, $routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray, $_SESSION['userid']);
}
function getvehicles()
{
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_all_vehicles();
    return $vehicles;
}
function getaddedvehicles($routeid)
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $vehicles = $routemanager->get_added_vehicles($routeid);
    return $vehicles;
}
function get_route_name($routen)
{
    $routen = GetSafeValueString($routen, 'string');
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_all_routes();
    $status = NULL;
    if(isset($routes))
    {
        foreach($routes as $thisroute)
        {
            if($thisroute->routename == $routen)
            {
                $status = "notok";
                break;
            }
        }
        if(!isset($status))
        {
            $status = "ok";
        }
    }
    else
    {
        $status = "ok";
    }
    echo $status;
}
function get_chks_for_route($routeid)
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoints = $routemanager->getchksforroute($routeid);
    return $checkpoints;
}
function get_chks_for_route_enh($routeid)
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoints = $routemanager->getchksforroute_enh($routeid);
    return $checkpoints;
}

function getchkpname($chkid)
{
    $chkid = GetSafeValueString($chkid, 'long');
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoint = $routemanager->getchknameforroute($chkid);
    return $checkpoint;
}

function delroute($routeid)
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routemanager->DeleteRoute($routeid, $_SESSION['userid']);
}

function getroutes()
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_all_routes();
    return $routes;
}

function getvehicles_withroute()
{
    $rm = new RouteManager($_SESSION['customerno']);
    $routedata = $rm->getvehiclesByRoute();
    return $routedata;
}

function getStartLocation($routeid)
{
    $rm = new RouteManager($_SESSION['customerno']);
    $startlocation = $rm->getStartLocation($routeid);
    return $startlocation;
}

function getDistanceCovered($vehicleid, $startdate, $date,$odometer, $endchkpt, $startchkpt, $flag, $crad)
{
    $userdate = date('Y-m-d', strtotime($date));
    $customerno = $_SESSION['customerno'];
    $unitno = getunitnotemp($vehicleid);
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
    $locationchk = "../../customer/$customerno/reports/chkreport.sqlite";
    $pathchk = "sqlite:$locationchk";
    $dbchk = new PDO($pathchk);
    $endchkrad = getcheckpointcrad($endchkpt);
    $startchkptrad = getcheckpointcrad($startchkpt);

    if($flag == 1){
       if(file_exists($location))
            {
                 $path = "sqlite:$location";
                 $db = new PDO($path);
                 $Query= "SELECT * FROM vehiclehistory where vehicleid = $vehicleid AND lastupdated >= '$date' Order By lastupdated ASC Limit 1 ";
                 $result = $db->query($Query);
                    if(isset($result) && $result !='')
                    {
                        foreach($result as $row){
                            $lastodometer = $row['odometer'] + ($endchkrad * 1000);
                        }
                    }

            }
    }
    else
    {
          $lastodometer =  $odometer +  ($endchkrad * 1000);
    }
            if(file_exists($location))
            {
                $path = "sqlite:$location";
                 $db = new PDO($path);
                 $Query= "SELECT * FROM vehiclehistory where vehicleid = $vehicleid AND lastupdated >= '$startdate' Order By lastupdated ASC Limit 1 ";
                 $result = $db->query($Query);
                    if(isset($result) && $result !='')
                    {
                        foreach($result as $row){
                              $firstodometer = $row['odometer'] ;
                        }
                    }
            }



    return round((($lastodometer - $firstodometer) / 1000) + $crad, 2);



}


function getCheckCompleteTrip($vehicleid,$date,$endchkpt)
{
    $customerno = $_SESSION['customerno'];
    $locationchk = "../../customer/$customerno/reports/chkreport.sqlite";
    $pathnew = "sqlite:$locationchk";
    $dbchk = new PDO($pathnew);
    $Querychk = $dbchk->prepare("SELECT * FROM V$vehicleid WHERE chkid = $endchkpt AND status = '0' AND date > '$date' Limit 1");
    $Querychk->execute();
    $resultchk = $Querychk->fetchAll();
    if(isset($resultchk) && !empty($resultchk))
    {
         foreach($resultchk as $rowchk)
         {
             return $rowchk['date'];
         }
    }
    else
    {
        return 0;
    }

}

function getunitnotemp($vehicleid)
{
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getuidfromvehicleid($vehicleid);
    return $unitno;
}

function getEndLocation($routeid)
{
    $rm = new RouteManager($_SESSION['customerno']);
    $startlocation = $rm->getEndLocation($routeid);
    return $startlocation;
}
function getStartTime($vehicleid,$checkpointid)
{
   // $REPORT = array();
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM V$vehicleid WHERE chkid = $checkpointid AND status = '1' Order By date DESC Limit 1";
    $result = $db->query($Query);
    if(isset($result) && $result !=''){
        foreach($result as $row){
            $Datacap = new VORoute();
            $Datacap->chkrepid = $row['chkrepid'];
            $Datacap->chkid = $row['chkid'];
            $Datacap->date = $row['date'];
           // $REPORT[] = $Datacap;
        }
        return $Datacap;
    }
    return null;
}

function getEndTime($vehicleid,$checkpointid, $date)
{
   // $REPORT = array();
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM V$vehicleid WHERE chkid = $checkpointid AND status = '0' AND date > '$date' Order By date DESC Limit 1";
    $result = $db->query($Query);
    if(isset($result) && $result !=''){
        foreach($result as $row){
            $Datacap = new VORoute();
            $Datacap->chkrepid = $row['chkrepid'];
            $Datacap->chkid = $row['chkid'];
            $Datacap->date = $row['date'];
           // $REPORT[] = $Datacap;
        }
        return $Datacap;
    }
    return null;
}

function getStdTime($routeid)
{
    $rm = new RouteManager($_SESSION['customerno']);
    $time = $rm->getStdTime($routeid);
    return $time;
}

function getLastCrossed($routeid,$vehicleid,$date)
{
    $chk = array();
    $rm = new RouteManager($_SESSION['customerno']);
    $lastchk = $rm->getAllChkptForRoute($routeid);
    foreach($lastchk as $checkpoint)
    {
       $chk[] = $checkpoint->checkpointid;
    }
    $chkpt = implode(',',$chk);
    $end = end($chk);
    //echo $chkpt;echo "</br>";
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM V$vehicleid WHERE chkid IN($chkpt) AND date >= '$date' Order By date DESC Limit 1";
    $result = $db->query($Query);
    if(isset($result) && $result !=''){
        foreach($result as $row){
            $Datacap = new VORoute();

            if($end == $row['chkid'])
            {
                $Datacap->flag = 1;
                $rowlast = getLastReadings($row['chkid'], $date, $vehicleid, $location, 0);
                //print_r($rowlast);echo "<br/>";
                $Datacap->chkrepid = $rowlast['chkrepid'];
                $Datacap->status = $rowlast['status'];
                $Datacap->chkid = $rowlast['chkid'];
                $Datacap->date = $rowlast['date'];


            }
            else
            {

                $Datacap->flag = 0;
                 $rowlast = getLastReadings($row['chkid'], $date, $vehicleid, $location, 1);
                $Datacap->chkrepid = $row['chkrepid'];
            $Datacap->status = $rowlast['status'];
            $Datacap->chkid = $rowlast['chkid'];
            $Datacap->date = $rowlast['date'];

            }
            $Datacap->cname = getcheckpointname($row['chkid']);
            $Datacap->eta = getcheckpointeta($row['chkid']);
            $Datacap->crad = getcheckpointcrad($row['chkid']);
           // $REPORT[] = $Datacap;
        }
        return $Datacap;
    }
    return null;


}

function getLastReadings($endchk, $date, $vehicleid, $location, $status){
    $path = "sqlite:$location";
    $db = new PDO($path);
    if($status != 0){
        $Query = "SELECT * FROM V$vehicleid WHERE chkid = $endchk  AND date >= '$date' Order By date DESC Limit 1";
    }
    else{
         $Query = "SELECT * FROM V$vehicleid WHERE chkid = $endchk AND status=$status AND date >= '$date' Order By date ASC Limit 1";
    }
    $result = $db->query($Query);
    if(isset($result) && $result !=''){
        foreach($result as $row){

        return $row;

        }

   }
}

function getLastCrossedEnd($routeid,$vehicleid,$date,$chk)
{
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM V$vehicleid WHERE chkid = $chk AND status = '0' AND date > '$date' Order By date ASC Limit 1";
    $result = $db->query($Query);
    if(isset($result) && $result !=''){
        foreach($result as $row){
            $Datacap = new VORoute();
            $Datacap->chkrepid = $row['chkrepid'];
            $Datacap->chkid = $row['chkid'];
            $Datacap->date = $row['date'];

            $Datacap->cname = getcheckpointname($row['chkid']);
            $Datacap->eta = getcheckpointeta($row['chkid']);
           // $REPORT[] = $Datacap;
        }
        return $Datacap;
    }
    return null;


}

function getRouteDistance($routeid)
{

    $rm = new RouteManager($_SESSION['customerno']);
    $lastchk = $rm->getAllChkptForRoute($routeid);
    $distancelast = end($lastchk);
    return $distancelast->distance;
 }

function getcheckpointname($checkpointid)
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoints = $routemanager->getchknameforroute($checkpointid);
    return $checkpoints;
}

function getcheckpointcrad($checkpointid)
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoints = $routemanager->getchkcradforroute($checkpointid);
    return $checkpoints;
}

function getcheckpointeta($checkpointid)
{

    $routemanager = new RouteManager($_SESSION['customerno']);
    $seq = $routemanager->getSeq($checkpointid);
    $checkpoints = $routemanager->getchketa($checkpointid, $seq);
    return $checkpoints;
}

function getimage($lastupdated,$stoppage_flag,$ignition,$type,$curspeed,$overspeed_limit,$stoppage_transit_time,$igstatus)
{
     $ServerIST_less1 = new DateTime();
    $ServerIST_less1->modify('-60 minutes');
    $lastupdated = new DateTime($lastupdated);
    $diff = getduration($stoppage_transit_time);
    if($type=='Truck' || $type=='Bus'){
        $image = "<img class='imgcl'  src='../../images/RTD/Vehicles/";
        $image .= "Bus/Bus";
        }
        elseif($type=='Car')
        {
            $image = "<img class='imgcl'  src='../../images/RTD/Vehicles/";
            $image .= "Car/Car";
        }

    if($_SESSION['portable']!='1')
    {
        if($lastupdated<$ServerIST_less1)
        {
             $image .= "I.png'>";

        }
        else
        {
            if ($ignition=='0')
            {
                $image .= "IOn.png'>";
            }
            else
            {
                if($curspeed > $overspeed_limit)
                {
                    $image .= "O.png'>";
                }
                else
                {
                    if($stoppage_flag == '0')
                    {
                        $image .= "N.png'>";
                    }
                    else
                    {
                        $image .= "R.png'>";
                    }
                }
            }
        }
    }
    else
    {
        $image .= "R.png'>";
    }

    echo $image;
}

function getduration($StartTime)
{
    $EndTime = date('Y-m-d H:i:s');
//                echo $EndTime.'_'.$StartTime.'<br>';
                $idleduration = strtotime($EndTime) - strtotime($StartTime);
                $years = floor($idleduration / (365 * 60 * 60 * 24));
                $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
                $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                if($years >= '1' || $months >= '1'){
                    $diff = date('d-m-Y', strtotime($StartTime));
                }
                else if($days>0){
                    $diff = $days.' days '.$hours.' hrs ago';
                }
                else if($hours>0){
                    $diff = $hours.' hrs and '.$minutes.' mins ago';
                }
                else if($minutes>0){
                    $diff = $minutes.' mins ago';
                }
                else{
                    $seconds = strtotime($EndTime) - strtotime($StartTime);
                    $diff = $seconds.' sec ago';
                }
                return $diff;
}

function location($lat,$long, $usegeolocation)
{
    $address = NULL;
    if($lat !='0' && $long!='0')
    {
        if($usegeolocation == 1)
        {
            $API = "http://www.speed.elixiatech.com/location.php?lat=".$lat."&long=".$long."";
            $location = json_decode(file_get_contents("$API&sensor=false"));
            @$address = "Near ".$location->results[0]->formatted_address;
				if($location->results[0]->formatted_address==""){
					$GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
					$address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);
				}
        }
        else
        {
            $GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
            $address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);
        }
    }
    return $address;
}

?>
