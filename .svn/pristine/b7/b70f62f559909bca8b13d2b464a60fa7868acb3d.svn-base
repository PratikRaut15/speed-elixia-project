<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
   $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS.'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS.'lib/autoload.php';
include_once $RELATIVE_PATH_DOTS."lib/comman_function/reports_func.php";

if(!isset($_SESSION)){
    session_start();
}
function adddriver($details)
{
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $response = $drivermanager->add_driver($details, $_SESSION['userid']);
    return $response;
}


function modifydriver($details) {
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $response = $drivermanager->moddriver($details,$_SESSION['userid']);
    return $response;
}

function deldriver($did)
{
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $driverid = GetSafeValueString($did, "string");
    $resp = $drivermanager->deldriver($driverid, $_SESSION['userid']);
    return $resp;
}
function getvehicles()
{
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles_with_drivers();
    return $vehicles;
}
function getdrivers()
{
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $drivers = $drivermanager->get_all_drivers_with_vehicles();
    return $drivers;
}

function getdrivers_allocated()
{
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $drivers = $drivermanager->get_all_drivers_with_vehicles_allocated();
    return $drivers;
}


function getdriver($driverid)
{
    $driverid = GetSafeValueString($driverid, "string");
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $driver = $drivermanager->get_driver_with_vehicle($driverid);
    return $driver;
}
/*
function getdriveralert_by_did($driverid){
    $driverid = GetSafeValueString($driverid, "string");
    $drivermanager = new DriverManager($_SESSION['customerno']);
    $driveralerts = $drivermanager->get_driveralert_by_id($driverid);
    return $driveralerts;
}
 *
 */

function mapdriver($did,$vid)
{
    $driverid= GetSafeValueString($did,"string");
    $vehicleid= GetSafeValueString($vid,"string");
    if(isset($vehicleid) && isset($driverid))
    {
        $dm = new DriverManager($_SESSION['customerno']);
        $dm->mapdrivertovehicle($vehicleid,$driverid,$_SESSION['userid']);
    }
}
function demapdriver($vid)
{
    $vehicleid= GetSafeValueString($vid,"string");
    if(isset($vehicleid))
    {
        $dm = new DriverManager($_SESSION['customerno']);
        $dm->demapdriver($vehicleid, $_SESSION['userid']);
    }
}
function printdriversformapping()
{
    $drivers = getdrivers();
    if(isset($drivers))
    {
        foreach ($drivers as $driver)
        {
            $row = '<ul id="mapping">';
            $row .= "<li id='d_$driver->driverid'";
            if($driver->vehicleid>0)
            {
                $row .= ' class="driverassigned"';
            }
            else
            {
                $row .= ' class="driver"';
            }
            $row .= " onclick='st($driver->driverid)'>";
            $row .= $driver->drivername;
            $row .= "<input type='hidden' id='dl_$driver->driverid'";
            if($driver->vehicleid!=0 && isset($driver->vehicleid))
                $row .= " value='$driver->vehicleid'>";
            $row .= "</li></ul>";
            echo $row;
        }
    }
}
function printvehiclesformapping()
{
    $vehicles = getvehicles();
    if(isset($vehicles))
    {
        foreach ($vehicles as $vehicle)
        {
            $row = "<ul id='mapping'>";
            $row .= "<li id='v_$vehicle->vehicleid'";
            if($vehicle->driverid>0)
            {
                $row .= ' class="vehicleassigned"';
            }
            else
            {
                $row .= ' class="vehicle"';
            }
            $row .= " onclick='sd($vehicle->vehicleid)'>";
            $row .=  $vehicle->vehicleno;
            $row .= "<input type='hidden' id='vl_$vehicle->vehicleid'";
            if($vehicle->driverid!=0 && isset($vehicle->driverid))
                $row .= " value='$vehicle->driverid'>";
            $row .="</li></ul>";
            echo $row;
        }
    }
}
function drivereligibility($did)
{
    $driverid= GetSafeValueString($did,"string");
    if(isset($driverid))
    {
        $dm = new DriverManager($_SESSION['customerno']);
        $driver = $dm->getdriverfromvehicles($driverid);
        if(isset($driver) && $driver->driverid > 0)
        {
            echo("notok");
        }
        else
        {
            echo("ok");
        }
    }
    else
    {
        echo("notok");
    }
}
function checkdrivername($drivername)
{
    $drivername = GetSafeValueString($drivername, 'string');
    $dm = new DriverManager($_SESSION['customerno']);
    $drivers = $dm->get_all_drivers();
    $status = NULL;
    if(isset($drivers))
    {
        foreach($drivers as $thisdriver)
        {
            if($thisdriver->drivername == $drivername)
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

function checkdriverusername($username)
{
    $username = GetSafeValueString($username, 'string');
    $dm = new DriverManager($_SESSION['customerno']);
    $users = $dm->check_username($username);
    if($users=="found")
    {
     echo 1;
    }
    else{
    echo 0;
    }

}

function addamount_driver($data){
$dlm = new DeliveryManager($data['customerno']);
$dlm = $dlm->add_amount_driver($data);
return $dlm;
}

function get_totalfund($driverid,$customerno){
    $dlm = new DeliveryManager($customerno);
    $totalamt = $dlm->get_all_totalfund($driverid);
    return $totalamt;
}


?>