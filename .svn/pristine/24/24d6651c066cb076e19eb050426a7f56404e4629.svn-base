<?php

include_once '../../lib/bo/ZoneManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/system/utilities.php';
include_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/components/ajaxpage.inc.php';

class jsonop {
    // Empty class!
}

if (!isset($_SESSION)) {
    session_start();
}

function getzone() {
    $geozonemanager = new ZoneManager($_SESSION['customerno']);
    $geozones = $geozonemanager->getZones();
    return $geozones;
}

function getvehicles_all() {
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_all_vehicles();
    return $vehicles;
}

function getaddedvehicles_zone($zoneid) {
    $geozonemanager = new ZoneManager($_SESSION['customerno']);
    $vehicles = $geozonemanager->get_added_vehicles_zone($zoneid);
    return $vehicles;
}

function getvehicle() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->getvehiclewithfences();
    return $vehicles;
}

function delzone($zoneid) {
    $geofencemanager = new ZOneManager($_SESSION['customerno']);
    $zoneid = GetSafeValueString($zoneid, "string");
    $geofencemanager->DeleteGeozone($zoneid, $_SESSION['userid']);
}

function printfencesformapping() {
    $geofences = getfences();
    if (isset($geofences)) {
        foreach ($geofences as $geofence) {
            $row = '<ul id="mapping">';
            $row .= "<li id='d_$geofence->fenceid'";
            if ($geofence->vehicleid > 0) {
                $row .= ' class="driverassigned"';
            } else {
                $row .= ' class="driver"';
            }
            $row .= " onclick='st($geofence->fenceid)'>";
            $row .= $geofence->fencename;
            $row .= "<input type='hidden' id='dl_$geofence->fenceid'";
            if ($geofence->vehicleid != 0 && isset($geofence->vehicleid))
                $row .= " value='$geofence->vehicleid'>";
            $row .= "</li></ul>";
            echo $row;
        }
    }
}

function printvehiclesformapping() {
    $vehicles = getvehicles();
    if (isset($vehicles)) {
        foreach ($vehicles as $vehicle) {
            $row = "<ul id='mapping'>";
            $row .= "<li id='v_$vehicle->vehicleid'";
            if ($vehicle->fenceid > 0) {
                $row .= ' class="vehicleassigned"';
            } else {
                $row .= ' class="vehicle"';
            }
            $row .= " onclick='sd($vehicle->vehicleid)'>";
            $row .= $vehicle->vehicleno;
            $row .= "<input type='hidden' id='vl_$vehicle->vehicleid'";
            if ($vehicle->fenceid != 0 && isset($vehicle->fenceid))
                $row .= " value='$vehicle->fenceid'>";
            $row .="</li></ul>";
            echo $row;
        }
    }
}

function createnewzone($lat, $long, $zonename, $vehicle_array) {
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $gm = new ZoneManager($customerno);

    if (isset($zonename) && $zonename != "") {
        $zoneid = $gm->getzonefromname($zonename);
        if (isset($zoneid) && $zoneid > 0) {
            $status = "samename";
        } else {
            $zone = new VOZone();
            $zone->zonename = $zonename;
            $zone->userid = $userid;
            $gm->InsertZone($zone);

            $latarray = explode(",", $lat);
            $longarray = explode(",", $long);

            for ($i = 0; $i < count($latarray); $i++) {
                $geozone = new VOGeoZone();
                $geozone->zoneid = $zone->zoneid;
                $geozone->geolat = $latarray[$i];
                $geozone->geolong = $longarray[$i];
                $geozone->userid = $userid;
                $gm->SaveGeozone($geozone);
            }
            if (!empty($vehicle_array)) {
                $vehicles = explode(",", $vehicle_array);

                for ($i = 0; $i < count($vehicles); $i++) {
                    $geozone = new VOGeoZone();
                    $geozone->zoneid = $zone->zoneid;
                    $geozone->vehicle = $vehicles[$i];
                    $geozone->userid = $userid;
                    $gm->InsertZoneMan($geozone);
                }
            }
            $status = "ok";
        }
    } else {
        $status = "detailsreqd";
    }
    echo $status;
}

function editzoning($lat, $long, $zonename, $zoneid, $vehicle_array) {
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $gm = new ZoneManager($customerno);

    if (isset($zonename) && $zonename != "") {
        $zoningid = $gm->getzonefromnameid($zonename, $zoneid);
        if (isset($zoningid) && $zoningid > 0) {
            $status = "notok";
        } else {
            $gm->updategeozone($zoneid, $zonename);
            $gm->DeleteGeozoneData($zoneid, $userid);

            $latarray = explode(",", $lat);
            $longarray = explode(",", $long);

            for ($i = 0; $i < count($latarray); $i++) {
                $geozone = new VOGeoZone();
                $geozone->zoneid = $zoneid;
                $geozone->geolat = $latarray[$i];
                $geozone->geolong = $longarray[$i];
                $geozone->userid = $userid;
                $gm->SaveGeozone($geozone);
            }
            $vehicles = explode(",", $vehicle_array);
            if ($vehicles) {
                $gm->delzoneman($zoneid);
            }
            for ($i = 0; $i < count($vehicles); $i++) {
                $geozone = new VOGeoZone();
                $geozone->zoneid = $zoneid;
                $geozone->vehicle = $vehicles[$i];
                $geozone->userid = $userid;
                $gm->InsertZoneMan($geozone);
            }
            $status = "ok";
        }
    } else {
        $status = "detailsreqd";
    }
    echo $status;
}

function mapfence($fenceid, $vehicleid) {
    $fenceid = GetSafeValueString($fenceid, "string");
    $vehicleid = GetSafeValueString($vehicleid, "string");

    if (isset($vehicleid)) {
        $gm = new GeofenceManager($_SESSION['customerno']);
        $gm->mapfencetovehicle($fenceid, $vehicleid);
    }
}

function demapfence($vehicleid) {
    $vehicleid = GetSafeValueString($vehicleid, "string");

    if (isset($vehicleid)) {
        $gm = new GeofenceManager($_SESSION['customerno']);
        $gm->demapfence($vehicleid);
    }
}

function getvehicleforfence($fenceid) {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $fenceid = GetSafeValueString($fenceid, 'long');
    $devices = $devicemanager->deviceforfence($fenceid);
    $finaloutput = array();
    if (isset($devices)) {
        foreach ($devices as $thisdevice) {
            $output = new jsonop();
            $date = new DateTime($thisdevice->lastupdated);
            $output->cgeolat = $thisdevice->devicelat;
            $output->cgeolong = $thisdevice->devicelong;
            $output->cname = $thisdevice->vehicleno;
            $output->cdrivername = $thisdevice->drivername;
            $output->cdriverphone = $thisdevice->driverphone;
            $output->cspeed = $thisdevice->curspeed;
            $output->clastupdated = $date->format('D d-M-Y H:i');
            $output->cvehicleid = $thisdevice->vehicleid;
            if ($thisdevice->type == 'Car')
                $output->image = '../../images/Car.png';
            else if ($thisdevice->type == 'Truck')
                $output->image = '../../images/Truck.png';
            else if ($thisdevice->type == 'Cab')
                $output->image = '../../images/Cab.png';
            else if ($thisdevice->type == 'Bus')
                $output->image = '../../images/Bus.png';
            $finaloutput[] = $output;
        }
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function fence_eligibility($fenceid) {
    $gm = new GeofenceManager($_SESSION['customerno']);
    $fenceid = GetSafeValueString($fenceid, 'long');

    if (isset($fenceid)) {
        $fence = $gm->getvehicleforfence($fenceid);
        if (isset($fence) && $fence->vehicleid > 0) {
            echo("notok");
        } else {
            echo("ok");
        }
    } else {
        echo("notok");
    }
}

function zoneonmap($zoneid) {
    $zoneid = GetSafeValueString($zoneid, 'long');
    $fencemanager = new ZoneManager($_SESSION['customerno']);
    $zone = $fencemanager->get_geozone_from_zoneid($zoneid);
    $finaloutput = array();
    if (isset($zone)) {
        foreach ($zone as $zonepart) {
            $output = new jsonop();
            $output->cgeolat = $zonepart->geolat;
            $output->cgeolong = $zonepart->geolong;
            $finaloutput[] = $output;
        }
    }
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}

function editfence($fenceid, $fencename) {
    $geofencemanager = new GeofenceManager($_SESSION['customerno']);
    $fenceid = GetSafeValueString($fenceid, "string");
    $fencename = GetSafeValueString($fencename, "string");
    $geofencemanager->updategeofence($fenceid, $fencename);
}

function DelFenceByVehicleid($fenceid, $vehicleid) {
    $geofencemanager = new GeofenceManager($_SESSION['customerno']);
    $fenceid = GetSafeValueString($fenceid, "string");
    $vehicleid = GetSafeValueString($vehicleid, "string");
    $geofencemanager->DeleteFenceByVehicleid($fenceid, $vehicleid, $_SESSION['userid']);
}

function getzonenamebyid($zoneid) {
    $geofencemanager = new ZoneManager($_SESSION['customerno']);
    $fenceid = GetSafeValueString($zoneid, "string");
    $geofences = $geofencemanager->zonenamebyid($zoneid);
    return $geofences;
}

?>
