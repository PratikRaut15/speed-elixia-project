<?php
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/bo/VehicleManager.php';
//include '../../lib/bo/GroupManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/RouteManager.php';

if(!isset($_SESSION))
{
    session_start();
}
class VODatacap {}
function getdate_IST() 
{
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}
function getvehicle($vehicleid)
{
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicle = $vehiclemanager->get_vehicle_with_driver($vehicleid);
    if($vehicle->isdeleted=='1')
        header("location:vehicle.php");
	
    return $vehicle;
}
function getvehicles()
{
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles();
    return $vehicles;
}
function getvehicles_latlng($routeid)
{
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles_latlng($routeid);
    return $vehicles;
}
function getshopscount()
{
    $route_obj = new RouteManager($_SESSION['customerno']);
    $route = $route_obj->get_shopscount();
    return $route;
}

function getroutes($vehicleid)
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->getroutebyvehicleid($vehicleid);
    return $routes;
}

function getroutes_list()
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_all_routes();
    return $routes;
}

function get_routeid($vehicleid)
{
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_routeid_for_vehicleid($vehicleid);
    return $routes;
}

function getDistanceReport($start_chk, $end_chk)
{
    $reports = array();
    $customerno = $_SESSION['customerno'];
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $geo = new GeoCoder($_SESSION['customerno']);
    if($_SESSION['groupid']!=0){
        $VEHICLES = $vehiclemanager->get_groups_vehicles_withlatlong($_SESSION['groupid']);
    }
    else{
        $VEHICLES = $vehiclemanager->GetAllVehicles_withlatlong();
    }
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $chk_start = getlatlng_chkpt($start_chk);
    $chk_end = getlatlng_chkpt($end_chk);
    //$chk_start_location = getChkLocation($start_chk, $location);
    //$chk_end_location = getChkLocation($end_chk, $location);
    
    foreach($VEHICLES as $vehicle )
    {
        $report = new VORoute();
        $report->vehicleno = $vehicle->vehicleno;
        $report->lat = $vehicle->cgeolat; 
        $report->clong = $vehicle->cgeolong;
        $report->start_lat = $chk_start->lat;
        $report->start_long = $chk_start->long;
        $report->end_lat = $chk_end->lat;
        $report->end_long = $chk_end->long;
        $report->location = $geo->get_location_bylatlong($vehicle->cgeolat, $vehicle->cgeolong);
        $report->start_location = getChkLocation($start_chk, $location, $vehicle->vehicleid);
        $report->end_location = getChkLocation($end_chk, $location, $vehicle->vehicleid);
        $reports[] = $report;
    }
    return $reports;
    
    //print_r($reports); die();
    
}

function getChkLocation($chk, $location, $vehicleid){
    $today = date('Y-m-d');
    $path = "sqlite:".$location;
    $db = new PDO($path);
    $Query = "select * from V".$vehicleid." order by chkrepid DESC limit 1";
    $result = $db->query($Query);
        if(isset($result) && $result != "")
        {
            foreach ($result as $row) 
            {      
                if($row["status"] == '1' && $row['chkid'] == 533)
                {
                    $outbound = "Outbound";
                    return $outbound;  
                }else if($row["status"] == '0' && $row['chkid'] == 533){
                    
                    $inbound = "Inbound";
                    return $inbound;
                }else{
                    
                    //select * from V1170  where chkrepid <(select chkrepid from V1170  where chkid=910  order by chkrepid DESC limit 1 )  and chkid<>910 order by chkrepid DESC limit 1
                  $Query1 = "select * from V".$vehicleid." where chkrepid <(select chkrepid from V".$vehicleid."  where chkid=$chk  order by chkrepid DESC limit 1 )  and chkid<>$chk order by chkrepid DESC limit 1";
                  $result1 = $db->query($Query1); 
                  if(isset($result1) && $result1 != "")
                    {
                      $count =  $result1->rowCount();
                      
                      if($count>0){
                        foreach ($result1 as $row1) 
                          {      

                              if($row1['chkid'] == 533)
                              {   

                                 $outbound = "Outbound";
                                  return $outbound;  
                              }else{
                                  $inbound = "Inbound";
                                  return $inbound;
                              }
                          }
                      }
                      else{
                          $outbound = "Outbound";
                                  return $outbound;  
                      }
                    }
                }
            }
        }
    //die("     ==========================           ");
}

function report_desc_seq_new($vehicleid, $customerno,$firstelement,$lastelement,$starttime,$endtime)
{
    $starttime = $starttime.":00";
    $endtime = $endtime.":00";
     $STdate = date("Y-m-d");
    // $STdate = "2014-08-07";
     $interval = 5;
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V".$vehicleid." WHERE DATE(date) = '".$STdate."' AND TIME(date) BETWEEN '".$starttime."' AND '".$endtime."' ORDER BY date DESC, chkrepid DESC";
    $reportdesc = array();
    $laststatus = '';
    $i=1;
    try 
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if(isset($result) && $result != "")
        {
            foreach ($result as $row) 
            {      
                if($row["chkid"] == $firstelement && $i != 1 && $laststatus == '')
                {
                    $checkpoint = new VODatacap();
                    $checkpoint->id = $row["chkrepid"];
                    $checkpoint->date = $row["date"];
                    $checkpoint->lastupdated = $row["date"];                    
                    $checkpoint->chkid = $row["chkid"];
                    $checkpoint->status = $row["status"];
                    $reportdesc[] = $checkpoint;
                    return $reportdesc;
                }
                else if($row["chkid"] == $firstelement && $i != 1 && $laststatus == $row["status"])
                {
                    return $reportdesc;
                }
                
                if($row["chkid"] == $checkpoint_old1->chkid && $row["status"] == '0' && round(abs(strtotime($checkpoint_old1->lastupdated) - strtotime($row["date"])) / 60,2) > $interval)
                {
                    $checkpoint = new VODatacap();
                    $checkpoint->id = $row["chkrepid"];
                    $checkpoint->lastupdated = $row["date"];
                    $checkpoint->chkid = $row["chkid"];
                    $checkpoint->status = $row["status"];
                    $reportdesc[] = $checkpoint_old1;                                        
                    $reportdesc[] = $checkpoint;                                        
                }
                if($row["status"] == '1')
                {
                    $checkpoint_old1 = new VODatacap();
                    $checkpoint_old1->id = $row["chkrepid"];
                    $checkpoint_old1->lastupdated = $row["date"];
                    $checkpoint_old1->chkid = $row["chkid"];
                    $checkpoint_old1->status = $row["status"];
                    if($row["chkid"] == $firstelement)
                    {
                        $reportdesc[] = $checkpoint_old1;
                    }
                }
                elseif($row["chkid"] != $checkpoint_old1->chkid && $row["status"] == '0')
                {
                    $checkpoint = new VODatacap();
                    $checkpoint->id = $row["chkrepid"];
                    $checkpoint->lastupdated = $row["date"];
                    $checkpoint->chkid = $row["chkid"];
                    $checkpoint->status = $row["status"];
                    $reportdesc[] = $checkpoint;                                                            
                }
//                $reportdesc[] = $checkpoint_old1;
//                if(($last_insertedid = $checkpoint->chkiddesc && $last_insertedid == $firstelement) || ($last_insertedid = $checkpoint->chkiddesc && $last_insertedid == $lastelement))
                
                if($row["chkid"] == $firstelement && $laststatus == '')
                {
                    $checkpoint = new VODatacap();
                    $checkpoint->id = $row["chkrepid"];
                    $checkpoint->lastupdated = $row["date"];
                    $checkpoint->chkid = $row["chkid"];
                    $checkpoint->status = $row["status"];
                     $reportdesc[] = $checkpoint;
                $laststatus = $row["status"];
                }
                $i++;
            }
        }
    }
    catch (PDOException $e)
    {
        $reportdesc = 0;
    }
    return $reportdesc;    
}


function report_desc_seq($vehicleid, $customerno,$firstelement,$lastelement)
{
     $STdate = date("Y-m-d");
    // $STdate = "2014-07-07";
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V".$vehicleid." WHERE DATE(date) = '".$STdate."' ORDER BY chkrepid DESC,date DESC";
    $reportdesc = array();
    $laststatus = '';
    $i=1;
    try 
    {
        $db = new PDO($path);
        $result = $db->query($Query);
        if(isset($result) && $result != "")
        {
            foreach ($result as $row) 
            {      
                if($row["chkid"] == $firstelement && $i != 1 && $laststatus == '')
                {
                    $checkpoint = new VODatacap();
                    $checkpoint->id = $row["chkrepid"];
                    $checkpoint->date = $row["date"];
                    $checkpoint->lastupdated = $row["date"];                    
                    $checkpoint->chkid = $row["chkid"];
                    $checkpoint->status = $row["status"];
                    $reportdesc[] = $checkpoint;
                    return $reportdesc;
                }
                else if($row["chkid"] == $firstelement && $i != 1 && $laststatus == $row["status"])
                {
                    return $reportdesc;
                }
                $checkpoint = new VODatacap();
                $checkpoint->id = $row["chkrepid"];
                $checkpoint->lastupdated = $row["date"];
                $checkpoint->chkid = $row["chkid"];
                $checkpoint->status = $row["status"];
                $reportdesc[] = $checkpoint;
//                if(($last_insertedid = $checkpoint->chkiddesc && $last_insertedid == $firstelement) || ($last_insertedid = $checkpoint->chkiddesc && $last_insertedid == $lastelement))
                
                if($row["chkid"] == $firstelement && $laststatus == '')
                {
                $laststatus = $row["status"];
                }
                $i++;
            }
        }
    }
    catch (PDOException $e)
    {
        $reportdesc = 0;
    }
    return $reportdesc;    
}

function getlatlng_chkpt($chkid)
{
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_latlng_chkpt($chkid);
    return $vehicles;
}


function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);
  if ($unit == "K") {
    return (round($miles * 1.609344,2));
  } else if ($unit == "N") {
      return (round($miles * 0.8684,2));
    } else {
        return round($miles,2);
      }
}

function get_all_checkpoint() {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcheckpoints();
    return $checkpoints;
}

function vehicleimage($device)
{

    $basedir = "../../images/vehicles/";
    $directionfile = round($device->directionchange/10);
    if($device->type=='Car' || $device->type=='Cab')
    {
        $device->type='Car';
        if ($device->ignition=='0')
        {
            $image = $device->type."/Idle/".$device->type.$directionfile.".png";
        }
        else
        {
            if($device->curspeed > $device->overspeed_limit)
            {
                $image = $device->type."/Overspeed/".$device->type.$directionfile.".png";
            }
            else
            {
                $image = $device->type."/Normal/".$device->type.$directionfile.".png";
            }
        }    
    }
    else if($device->type=='Bus' || $device->type=='Truck')
    {
        $device->type='Bus';
        if ($device->ignition=='0')
        {
            $image = $device->type."/Idle/".$device->type.$directionfile.".png";
        }
        else
        {
            if($device->curspeed > $device->overspeed_limit)
            {
                $image = $device->type."/Overspeed/".$device->type.$directionfile.".png";
            }
            else
            {
                $image = $device->type."/Normal/".$device->type.$directionfile.".png";
            }
        }    
    }
    else
    {
        if ($device->ignition=='0')
        {
            $image = $device->type."/".$device->type."I.png";
        }
        else
        {
            if($device->curspeed > $device->overspeed_limit)
            {
                $image = $device->type."/".$device->type."O.png";
            }
            else
            {
                $image = $device->type."/".$device->type."N.png";
            }
        }  
    }
    $image = $basedir.$image;
    return $image;
}

?>
