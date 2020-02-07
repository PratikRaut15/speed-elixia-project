<?php

include_once '../../lib/system/Validator.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOGeoZone.php';
include_once '../../lib/model/VOZone.php';

class ZoneManager extends VersionedManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function getZones() {
        $zones = array();
        $Query = "select * from zone WHERE customerno=%s AND isdeleted=0";
        $zoneQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($zoneQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $zone = new VOZone();
                $zone->zoneid = $row['zoneid'];
                $zone->zonename = $row['zonename'];
                $zones[] = $zone;
            }
            return $zones;
        }
        return NULL;
    }

    public function zonenamebyid($zoneid) {
        $Query = "SELECT zonename FROM `zone` WHERE customerno=%d AND zoneid=%d AND isdeleted=0";
        $geozoneQuery = sprintf($Query, $this->_Customerno, Sanitise::String($zoneid));
        $this->_databaseManager->executeQuery($geozoneQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $geozonepart = new VOGeoZone();
                $geozonepart->zonename = $row['zonename'];
                return $geozonepart;
            }
        }
        return null;
    }

    public function get_added_vehicles_zone($zoneid) {
        $vehicles = Array();
        $Query = 'select * from vehicle 
            INNER JOIN zoneman ON zoneman.vehicleid = vehicle.vehicleid
            where zoneman.zoneid=%s AND zoneman.customerno=%s AND vehicle.customerno=%s AND vehicle.isdeleted=0 AND zoneman.isdeleted=0';
        if ($_SESSION['groupid'] != 0)
            $Query.=" AND vehicle.groupid =%d";

        if ($_SESSION['groupid'] != 0)
            $vehiclesQuery = sprintf($Query, Sanitise::Long($zoneid), $this->_Customerno, $this->_Customerno, $_SESSION['groupid']);
        else
            $vehiclesQuery = sprintf($Query, Sanitise::Long($zoneid), $this->_Customerno, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                //$vehicle->devicekey = $row['devicekey'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function modify_multiple_zones($zone_arr, $insert_arr, $userid) {
        $zone_csv = implode(',', $zone_arr);
        $Update_Q = "UPDATE geozone SET isdeleted=1,userid=$userid WHERE zoneid in ($zone_csv) AND customerno = $this->_Customerno";
        $this->_databaseManager->executeQuery($Update_Q);

        $insert_csv = implode(',', $insert_arr);
        $Insert_Q = "INSERT INTO geozone (`customerno`,`zoneid`,`geolat`,`geolong`,`userid`) VALUES $insert_csv";
        $this->_databaseManager->executeQuery($Insert_Q);
    }

    public function getzonefromname($fencename) {
        $Query = "SELECT zoneid FROM `zone` WHERE customerno=%d AND zonename='%s' AND isdeleted=0";
        $geofenceQuery = sprintf($Query, $this->_Customerno, Sanitise::String($fencename));
        $this->_databaseManager->executeQuery($geofenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row["zoneid"];
            }
        }
        return null;
    }

    public function InsertZone($geozone) {
        $Query = "INSERT INTO zone (`customerno`,`zonename`,`userid`,`isdeleted`) VALUES (%d,'%s',%d,0)";
        $SQL = sprintf($Query, $this->_Customerno, Sanitise::String($geozone->zonename), Sanitise::Long($geozone->userid));
        $this->_databaseManager->executeQuery($SQL);
        $geozone->zoneid = $this->_databaseManager->get_insertedId();
    }

    public function SaveGeozone($geozone) {
        if (!isset($geozone->geozoneid)) {
            $this->Insert($geozone);
        } else {
            //$this->Update($geofence);
        }
    }

    private function Insert($geozone) {
        $Query = "INSERT INTO geozone (`customerno`,`zoneid`,`geolat`,`geolong`,`isdeleted`,`userid`) VALUES (%d,%d,'%f','%f',0,%d)";
        $SQL = sprintf($Query, $this->_Customerno, Sanitise::Long($geozone->zoneid), Sanitise::Float($geozone->geolat), Sanitise::Float($geozone->geolong), Sanitise::Long($geozone->userid));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function InsertZoneMan($geozone) {
        $Query = "INSERT INTO zoneman (`zoneid`,`vehicleid`,`customerno`,`userid`,`isdeleted`) VALUES (%d,'%d',%d,%d,'0')";

        $SQL = sprintf($Query, Sanitise::Long($geozone->zoneid), Sanitise::Long($geozone->vehicle), $this->_Customerno, Sanitise::Long($geozone->userid));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_geozone_from_zoneid($zoneid) {
        $geozone = Array();
        $Query = "SELECT * FROM `geozone` WHERE customerno=%d AND zoneid=%d AND isdeleted=0";
        $geozoneQuery = sprintf($Query, $this->_Customerno, Sanitise::String($zoneid));
        $_SESSION['query'] = $geozoneQuery;
        $this->_databaseManager->executeQuery($geozoneQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $geozonepart = new VOGeoZone();
                $geozonepart->geozoneid = $row['geozoneid'];
                $geozonepart->zoneid = $row['zoneid'];
                $geozonepart->customerno = $row['customerno'];
                $geozonepart->geolat = $row['geolat'];
                $geozonepart->geolong = $row['geolong'];
                $geozone[] = $geozonepart;
            }
            return $geozone;
        }
        return null;
    }

    public function getzonefromnameid($zonename, $zoneid) {
        $Query = "SELECT zoneid FROM `zone` WHERE zoneid!=%d AND customerno=%d AND zonename='%s' AND isdeleted=0";
        $geozoneQuery = sprintf($Query, Sanitise::String($zoneid), $this->_Customerno, Sanitise::String($zonename));
        $this->_databaseManager->executeQuery($geozoneQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row["zoneid"];
            }
        }
        return null;
    }

    public function updategeozone($zoneid, $zonename) {
        $Query = "UPDATE `zone` SET `zonename`='%s' WHERE zoneid=%d AND customerno = %d AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($zonename), Sanitise::String($zoneid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeleteGeozoneData($zoneid, $userid) {
        $Query = "UPDATE geozone SET isdeleted=1,userid=%d WHERE zoneid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($userid), Sanitise::long($zoneid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function delzoneman($zoneid) {
        $Query = "Update zoneman Set `isdeleted`=1 WHERE zoneid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($zoneid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getzones_with_bounds() {
        $main_zone=array();
        $zones = $this->getZones();
        if ($zones) {
            foreach ($zones as $thiszone) {
                $subs['zoneid'] = $thiszone->zoneid;
                $subs['zonename'] = $thiszone->zonename;
                $subs['zone_bound'] = $this->get_geozone_by_zoneid($thiszone->zoneid);
                $main_zone[] = $subs;
            }
            return $main_zone;
        }
        return NULL;
    }

    public function get_geozone_by_zoneid($zoneid) {
        $geozones = Array();

        $Query = "SELECT * FROM `geozone` WHERE customerno=%d AND zoneid=%d AND isdeleted=0";
        $geozoneQuery = sprintf($Query, $this->_Customerno, $zoneid);
        //$_SESSION['query'] = $geofenceQuery;
        $this->_databaseManager->executeQuery($geozoneQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $geozonepart = new VOGeoZone();
                $geozonepart->geozoneid = $row['geozoneid'];
                $geozonepart->zoneid = $row['zoneid'];
                $geozonepart->customerno = $row['customerno'];
                $geozonepart->geolat = $row['geolat'];
                $geozonepart->geolong = $row['geolong'];
                $geozones[] = $geozonepart;
            }
            return $geozones;
        }
        return null;
    }
public function DeleteGeozone($zoneid, $userid) {
        $Query = "UPDATE geozone SET isdeleted=1,userid=%d WHERE zoneid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($userid), Sanitise::long($zoneid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        $Query = "UPDATE zone SET isdeleted=1,userid=%d WHERE zoneid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($userid), Sanitise::long($zoneid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        $Query = "UPDATE zoneman SET isdeleted=1,userid=%d WHERE zoneid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($userid), Sanitise::long($zoneid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }
    
     public function getallcheckpoints() {
        $checkpoints = array();
        //$Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `checkpoint` where customerno=%d AND isdeleted=0";
        $Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `checkpoint`
                    where customerno=%d AND isdeleted=0 order by cname ASC";
        $checkpointQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new stdClass();
                $checkpoint->cname = $row['cname'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }
    
    public function getallcheckpointscz() {
        $checkpoints = array();
        //$Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `checkpoint` where customerno=%d AND isdeleted=0";
        $Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `circularzone`
                    where customerno=%d AND isdeleted=0 order by cname ASC";
        $checkpointQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new stdClass();
                $checkpoint->cname = $row['cname'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }
}



/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

