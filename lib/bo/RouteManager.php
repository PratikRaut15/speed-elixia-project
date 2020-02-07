<?php

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
if (!defined('RELATIVE_PATH_DOTS')) {
    define("RELATIVE_PATH_DOTS", $RELATIVE_PATH_DOTS);
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
require_once $RELATIVE_PATH_DOTS . 'lib/bo/CheckpointManager.php';

class RouteManager extends VersionedManager {

    public function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

    public function get_all_routes($objRoute = NULL) {
        $routes = Array();
        $routeWhereCondn = "";
        if (isset($objRoute)) {
            $routeid = $objRoute->routeid;
            $routeWhereCondn = " AND routeid = " . $routeid;
        }
        $Query = "SELECT * FROM route where customerno=%d AND isdeleted=0 " . $routeWhereCondn . " ORDER BY routename";
        $routesQuery = sprintf($Query, $this->_Customerno);
        //echo"Route query is: ".$routesQuery; exit();
        $this->_databaseManager->executeQuery($routesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $route = new VORoute();
                $route->routeid = $row['routeid'];
                $route->routename = $row['routename'];
                $route->routeTat = $row['routeTat'];
                $routes[] = $route;
            }
            return $routes;
        }
        return null;
    }

    public function get_all_routes_enh() {
        $routes = Array();
        $Query = "SELECT * FROM `route` INNER JOIN routeman ON  route.routeid = routeman.routeid
            where route.customerno=%d AND route.isdeleted=0 AND routeman.distance= 0 AND routeman.timetaken <> '' AND routeman.isdeleted=0  ORDER BY route.routename";
        $routesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($routesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $route = new VORoute();
                $route->routeid = $row['routeid'];
                $route->routename = $row['routename'];
                $routes[] = $route;
            }
            return $routes;
        }
        return null;
    }

    public function getchksforroute($routeid) {
        $checkpoints = Array();
        //$Query = "select * from routeman where routeid=%s AND customerno=%d AND isdeleted=0 ORDER BY sequence ASC";
        $Query = "  SELECT  routeman.routeid
                            , routeman.checkpointid
                            , routeman.sequence
                            , checkpoint.cname
                            , routeman.eta
                            , routeman.etd
                            , routeman.r_eta
                            , routeman.r_etd
                            , routeman.kmFromLastCheckpoint
                    from    checkpoint
                    INNER JOIN routeman ON routeman.checkpointid = checkpoint.checkpointid
                    WHERE   routeman.routeid = %s
                    AND     routeman.customerno = %s
                    AND     checkpoint.customerno = %s
                    AND     checkpoint.isdeleted = 0
                    AND     routeman.isdeleted = 0
                    ORDER BY routeman.sequence ASC";
        $checkpointsQuery = sprintf($Query, Sanitise::Long($routeid), $this->_Customerno, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VORoute();
                $checkpoint->routeid = $row['routeid'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->sequence = $row['sequence'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->eta = (isset($row['eta']) && $row['eta'] != '00:00:00') ? date('H:i', strtotime($row['eta'])) : '';
                $checkpoint->etd = (isset($row['etd']) && $row['etd'] != '00:00:00') ? date('H:i', strtotime($row['etd'])) : '';
                $checkpoint->r_eta = (isset($row['r_eta']) && $row['r_eta'] != '00:00:00') ? date('H:i', strtotime($row['r_eta'])) : '';
                $checkpoint->r_etd = (isset($row['r_etd']) && $row['r_etd'] != '00:00:00') ? date('H:i', strtotime($row['r_etd'])) : '';
                $checkpoint->km = isset($row['kmFromLastCheckpoint']) ? $row['kmFromLastCheckpoint'] : '';
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return NULL;
    }

    public function getchksforroute_enh($routeid) {
        $checkpoints = Array();
        //$Query = "select * from routeman where routeid=%s AND customerno=%d AND isdeleted=0 ORDER BY sequence ASC";
        $Query = "select *, routeman.distance,routeman.timetaken from checkpoint
            INNER JOIN routeman ON routeman.checkpointid = checkpoint.checkpointid
            where routeman.routeid=%s AND routeman.customerno=%s AND checkpoint.customerno=%s AND checkpoint.isdeleted=0 AND routeman.isdeleted=0 ORDER BY routeman.sequence ASC";
        $checkpointsQuery = sprintf($Query, Sanitise::Long($routeid), $this->_Customerno, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VORoute();
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->sequence = $row['sequence'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->timetaken = $row['timetaken'];
                $checkpoint->distance = $row['distance'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return NULL;
    }

    public function getchknameforroute($chkid) {
        $Query = "select * from checkpoint where checkpointid=%s AND customerno=%s AND isdeleted=0";
        $checkpointsQuery = sprintf($Query, Sanitise::Long($chkid), $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VORoute();
                //$checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                //$checkpoint->vehicleid = $row['vehicleid'];
            }
            return $checkpoint->cname;
        }
        return NULL;
    }

    public function getchkcradforroute($chkid) {
        $Query = "select * from checkpoint where checkpointid=%s AND customerno=%s AND isdeleted=0";
        $checkpointsQuery = sprintf($Query, Sanitise::Long($chkid), $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VORoute();
                //$checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->crad = $row['crad'];
                //$checkpoint->vehicleid = $row['vehicleid'];
            }
            return $checkpoint->crad;
        }
        return NULL;
    }

    public function getchketa($chkid, $seq) {
        $Query = "select timetaken from routeman where checkpointid=%s AND customerno=%s  AND isdeleted=0";
        $checkpointsQuery = sprintf($Query, Sanitise::Long($chkid), $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VORoute();
                //$checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->timetaken = $row['timetaken'];
                //$checkpoint->vehicleid = $row['vehicleid'];
            }
            return $checkpoint->timetaken;
        }
        return NULL;
    }

    public function getSeq($chkid) {
        $Query = "select sequence from routeman where checkpointid=%s AND customerno=%s AND isdeleted=0";
        $checkpointsQuery = sprintf($Query, Sanitise::Long($chkid), $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VORoute();
                //$checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->sequence = $row['sequence'];
                //$checkpoint->vehicleid = $row['vehicleid'];
            }
            return $checkpoint->sequence;
        }
        return NULL;
    }

    public function getvehiclesforroute($routeid) {
        $vehicles = Array();
        if (isset($_SESSION['ecodeid'])) {
            $Query = "select * from vehiclerouteman, ecodeman where vehiclerouteman.routeid=%s AND vehiclerouteman.customerno=%d AND ecodeman.ecodeid=%d
                AND vehiclerouteman.vehicleid=ecodeman.vehicleid AND vehiclerouteman.isdeleted=0";
            $checkpointsQuery = sprintf($Query, Sanitise::Long($routeid), $this->_Customerno, $_SESSION['e_id']);
        } else {
            $Query = "select * from vehiclerouteman where routeid=%s AND customerno=%d AND isdeleted=0";
            $checkpointsQuery = sprintf($Query, Sanitise::Long($routeid), $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VORoute();
                $vehicle->vehicleid = $row['vehicleid'];
                //$vehicle->sequence = $row['routeid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return NULL;
    }

    public function getvehiclenoforroute($vehicleid) {
        $Query = "select * from vehicle where vehicleid=%s AND customerno=%s AND isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VORoute();
                //$checkpoint->checkpointid = $row['checkpointid'];
                $vehicle->vehicleno = $row['vehicleno'];
                //$checkpoint->vehicleid = $row['vehicleid'];
            }
            return $vehicle->vehicleno;
        }
        return NULL;
    }

    public function get_all_checkpointid_forroute($routeid) {
        $Routes = Array();
        $Query = "SELECT * FROM `routeman` where customerno=%d AND routeid=%d AND routeman.isdeleted=0 ORDER BY `sequence` ASC ";
        $RoutesQuery = sprintf($Query, $this->_Customerno, $routeid);
        $this->_databaseManager->executeQuery($RoutesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Route = new VORoute();
                $Route->routeid = $row['routeid'];
                $Route->checkpointid = $row['checkpointid'];
                $Routes[] = $Route;
            }
            return $Routes;
        }
        return null;
    }

    public function add_Route($routename, $routearray, $vehiclearray, $userid, $chkDetails, $routeTat, $routeType = null) {
        $Query = "INSERT INTO route (routename,customerno,userid,isdeleted,timestamp, routeTat, routeType) VALUES ('%s','%d','%d','0','%s',%d,%d)";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($routename), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays), Sanitise::Long($routeTat), Sanitise::Long($routeType));
        $this->_databaseManager->executeQuery($SQL);
        $routeid = $this->_databaseManager->get_insertedId();
        $Query = "INSERT INTO routeman (`routeid`,`checkpointid`,`sequence`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','%d','%d','0','%s')";
        $date = new Date();
        $today = $date->MySQLNow();
        //echo $routearrays = $routearray;
        $theArray = explode(",", $routearray);
        $listingCounter = 1;
        foreach ($theArray as $checkpointid) {
            if ($checkpointid != '') {
                $SQL = sprintf($Query, Sanitise::Long($routeid), Sanitise::Long($checkpointid), Sanitise::Long($listingCounter), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
                $listingCounter = $listingCounter + 1;
            }
        }
        if (isset($chkDetails) && !empty($chkDetails)) {
            $updateQuery = "UPDATE routeman
                            SET     eta = '%s'
                                    , etd = '%s'
                                    ,  r_eta = '%s'
                                    , r_etd = '%s'
                                    , kmFromLastCheckpoint = %d
                            WHERE   routeid=%d
                            AND     checkpointid=%d
                            AND     customerno=%d
                            AND     isdeleted=0";
            foreach ($chkDetails as $data) {
                $data[2] = date(speedConstants::TIME_Hi, strtotime($data[2]));
                $data[3] = date(speedConstants::TIME_Hi, strtotime($data[3]));
                $data[4] = date(speedConstants::TIME_Hi, strtotime($data[4]));
                $data[5] = date(speedConstants::TIME_Hi, strtotime($data[5]));
                $km = (isset($data[6]) && $data[6] != '') ? $data[6] : NULL;
                $updateSql = sprintf($updateQuery, $data['2'], $data['3'], $data['4'], $data['5'], Sanitise::Long($km), Sanitise::Long($routeid), $data['0'], $this->_Customerno);
                $this->_databaseManager->executeQuery($updateSql);
            }
        }
        $date = new Date();
        $todayss = $date->MySQLNow();
        //echo $routearrays = $routearray;
        $vehicleArray = explode(",", $vehiclearray);
        foreach ($vehicleArray as $vehicleid) {
            if ($vehicleid != '') {
                $Query = "DELETE FROM vehiclerouteman WHERE  vehicleid=%d AND customerno=%d";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
                $Query = "INSERT INTO vehiclerouteman (`routeid`,`vehicleid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','%d','0','%s')";
                $SQL = sprintf($Query, Sanitise::Long($routeid), Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todayss));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        //echo $routearrays = $routearray;
        $checkpointarray = explode(",", $routearray);
        $vehiclesArray = explode(",", $vehiclearray);
        foreach ($vehiclesArray as $vehiclesid) {
            if ($vehiclesid != '') {
                foreach ($checkpointarray as $checkpointid) {
                    if ($checkpointid != '') {
                        $CheckQuery = "SELECT vehicleid FROM `checkpointmanage` WHERE checkpointid=%d AND vehicleid=%d AND customerno=%d AND isdeleted=0";
                        $checkpointsQuery = sprintf($CheckQuery, Sanitise::Long($checkpointid), Sanitise::Long($vehiclesid), $this->_Customerno);
                        $this->_databaseManager->executeQuery($checkpointsQuery);
                        if ($this->_databaseManager->get_rowCount() == 0) {
                            $Query = "INSERT INTO checkpointmanage (`checkpointid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d','%d','1','%d','0')";
                            $SQL = sprintf($Query, Sanitise::Long($checkpointid), Sanitise::Long($vehiclesid), $this->_Customerno, Sanitise::Long($userid));
                            $this->_databaseManager->executeQuery($SQL);
                        }
                    }
                }
            }
        }
    }

    public function add_Route_enh($routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray, $userid) {
        $Query = "INSERT INTO route (routename,customerno,userid,isdeleted,timestamp) VALUES ('%s','%d','%d','0','%s')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($routename), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays));
        $this->_databaseManager->executeQuery($SQL);
        $route->routeid = $this->_databaseManager->get_insertedId();
        $Query = "INSERT INTO routeman (`routeid`,`checkpointid`,`timetaken`,`distance`,`sequence`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%s','%f','%d','%d','%d','0','%s')";
        $date = new Date();
        $today = $date->MySQLNow();
        //$routearrays = $routearray;
        $routeArray = explode(",", $routearray);
        $thourArray = explode(",", $thourarray);
        $tminArray = explode(",", $tminarray);
        $distanceArray = explode(",", $distancearray);
        $route->routeid;
        $listingCounter = 1;
        foreach ($routeArray as $key => $value) {
            $SQL = sprintf($Query, Sanitise::Long($route->routeid), Sanitise::Long($value), Sanitise::String(($thourArray[$key] * 60) + $tminArray[$key]), Sanitise::Float($distanceArray[$key]), Sanitise::Long($listingCounter), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($today));
            $this->_databaseManager->executeQuery($SQL);
            $listingCounter = $listingCounter + 1;
        }
        $Query = "INSERT INTO vehiclerouteman (`routeid`,`vehicleid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','%d','0','%s')";
        $date = new Date();
        $todayss = $date->MySQLNow();
        //echo $routearrays = $routearray;
        $vehicleArray = explode(",", $vehiclearray);
        foreach ($vehicleArray as $vehicleid) {
            if ($vehicleid != '') {
                $SQL = sprintf($Query, Sanitise::Long($route->routeid), Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todayss));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        //echo $routearrays = $routearray;
        $checkpointarray = explode(",", $routearray);
        $vehiclesArray = explode(",", $vehiclearray);
        foreach ($vehiclesArray as $vehiclesid) {
            if ($vehiclesid != '') {
                foreach ($checkpointarray as $checkpointid) {
                    if ($checkpointid != '') {
                        $CheckQuery = "SELECT vehicleid FROM `checkpointmanage` WHERE checkpointid=%d AND vehicleid=%d AND customerno=%d AND isdeleted=0";
                        $checkpointsQuery = sprintf($CheckQuery, Sanitise::Long($checkpointid), Sanitise::Long($vehiclesid), $this->_Customerno);
                        $this->_databaseManager->executeQuery($checkpointsQuery);
                        if ($this->_databaseManager->get_rowCount() == 0) {
                            $Query = "INSERT INTO checkpointmanage (`checkpointid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d','%d','1','%d','0')";
                            $SQL = sprintf($Query, Sanitise::Long($checkpointid), Sanitise::Long($vehiclesid), $this->_Customerno, Sanitise::Long($userid));
                            $this->_databaseManager->executeQuery($SQL);
                        }
                    }
                }
            }
        }
    }

    public function edit_Route($routeid, $routename, $routearray, $vehiclearray, $userid, $chkDetails, $routeTat = null, $routeType = null) {
        $checkpoint_array = $this->get_all_checkpointid_forroute($routeid);
        $vehicles_Array = $this->getvehiclesforroute($routeid);
        if ($routeTat != "api") {
            $sqlRoute = "UPDATE route SET routename='%s', routeTat=%d,userid=%d,routeType=%d WHERE routeid=%d AND customerno=%d";
            $sqlExe = sprintf($sqlRoute, Sanitise::String($routename), Sanitise::Long($routeTat), Sanitise::Long($userid), Sanitise::Long($routeType), Sanitise::Long($routeid), $this->_Customerno);
            $this->_databaseManager->executeQuery($sqlExe);
        }
        if (is_array($vehicles_Array) && count($vehicles_Array) > 0 && !empty($vehicles_Array)) {
            foreach ($vehicles_Array as $vehicles) {
                if ($vehicles != '') {
                    foreach ($checkpoint_array as $checkpoint) {
                        if ($checkpoint != '') {
                            $CheckQuery = "UPDATE `checkpointmanage` SET isdeleted='1' WHERE checkpointid=%d AND vehicleid=%d AND customerno=%d AND isdeleted=0 LIMIT 1";
                            $checkpointsQuery = sprintf($CheckQuery, Sanitise::Long($checkpoint->checkpointid), Sanitise::Long($vehicles->vehicleid), $this->_Customerno);
                            $this->_databaseManager->executeQuery($checkpointsQuery);
                        }
                    }
                }
            }
        }
        $Query = "DELETE FROM routeman WHERE routeid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($routeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "DELETE FROM vehiclerouteman WHERE  routeid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($routeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "INSERT INTO routeman (`routeid`,`checkpointid`,`sequence`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','%d','%d','0','%s')";
        $date = new Date();
        $today = $date->MySQLNow();
        //echo $routearrays = $routearray;
        $theArray = explode(",", $routearray);
        $listingCounter = 1;
        foreach ($theArray as $checkpointid) {
            if ($checkpointid != '') {
                $SQL = sprintf($Query, Sanitise::Long($routeid), Sanitise::Long($checkpointid), Sanitise::Long($listingCounter), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
                $listingCounter = $listingCounter + 1;
            }
        }
        if (isset($chkDetails) && !empty($chkDetails)) {
            $updateQuery = "UPDATE  routeman
                            SET     eta = '%s'
                                    , etd = '%s'
                                    , r_eta = '%s'
                                    , r_etd = '%s'
                                    , kmFromLastCheckpoint = %d
                            WHERE   routeid = %d
                            AND     checkpointid = %d
                            AND     customerno = %d
                            AND     isdeleted=0";
            foreach ($chkDetails as $data) {
                $data[2] = ($data[2] != '') ? date(speedConstants::TIME_Hi, strtotime($data[2])) : '00:00:00';
                $data[3] = ($data[3] != '') ? date(speedConstants::TIME_Hi, strtotime($data[3])) : '00:00:00';
                $data[4] = ($data[4] != '') ? date(speedConstants::TIME_Hi, strtotime($data[4])) : '00:00:00';
                $data[5] = ($data[5] != '') ? date(speedConstants::TIME_Hi, strtotime($data[5])) : '00:00:00';
                $km = (isset($data[6]) && $data[6] != '') ? $data[6] : NULL;
                $updateSql = sprintf($updateQuery, $data['2'], $data['3'], $data['4'], $data['5'], Sanitise::Long($km), Sanitise::Long($routeid), $data['0'], $this->_Customerno);
                $this->_databaseManager->executeQuery($updateSql);
            }
        }
        $date = new Date();
        $todayss = $date->MySQLNow();
        //echo $routearrays = $routearray;
        $vehicleArray = explode(",", $vehiclearray);
        foreach ($vehicleArray as $vehicleid) {
            if ($vehicleid != '') {
                $Query = "DELETE FROM vehiclerouteman WHERE  vehicleid=%d AND customerno=%d";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
                $Query = "INSERT INTO vehiclerouteman (`routeid`,`vehicleid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','%d','0','%s')";
                $SQL = sprintf($Query, Sanitise::Long($routeid), Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todayss));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        //echo $routearrays = $routearray;
        $checkpointarray = explode(",", $routearray);
        $vehiclesArray = explode(",", $vehiclearray);
        foreach ($vehiclesArray as $vehiclesid) {
            if ($vehiclesid != '') {
                foreach ($checkpointarray as $checkpointid) {
                    if ($checkpointid != '') {
                        $CheckQuery = "SELECT vehicleid FROM `checkpointmanage` WHERE checkpointid=%d AND vehicleid=%d AND customerno=%d AND isdeleted=0";
                        $checkpointsQuery = sprintf($CheckQuery, Sanitise::Long($checkpointid), Sanitise::Long($vehiclesid), $this->_Customerno);
                        $this->_databaseManager->executeQuery($checkpointsQuery);
                        if ($this->_databaseManager->get_rowCount() == 0) {
                            $Query = "INSERT INTO checkpointmanage (`checkpointid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d','%d','1','%d','0')";
                            $SQL = sprintf($Query, Sanitise::Long($checkpointid), Sanitise::Long($vehiclesid), $this->_Customerno, Sanitise::Long($userid));
                            $this->_databaseManager->executeQuery($SQL);
                        }
                    }
                }
            }
        }
    }

    public function edit_Route_enh($routeid, $routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray, $userid) {
        $checkpoint_array = $this->get_all_checkpointid_forroute($routeid);
        $vehicles_Array = $this->getvehiclesforroute($routeid);
        foreach ($vehicles_Array as $vehicles) {
            if ($vehicles != '') {
                foreach ($checkpoint_array as $checkpoint) {
                    if ($checkpoint != '') {
                        $CheckQuery = "UPDATE `checkpointmanage` SET isdeleted='1' WHERE checkpointid=%d AND vehicleid=%d AND customerno=%d AND isdeleted=0 LIMIT 1";
                        $checkpointsQuery = sprintf($CheckQuery, Sanitise::Long($checkpoint->checkpointid), Sanitise::Long($vehicles->vehicleid), $this->_Customerno);
                        $this->_databaseManager->executeQuery($checkpointsQuery);
                    }
                }
            }
        }
        $Query = "UPDATE routeman SET isdeleted=1,userid=%d WHERE routeid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($routeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE vehiclerouteman SET isdeleted=1,userid=%d WHERE routeid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($routeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "INSERT INTO routeman (`routeid`,`checkpointid`,`timetaken`,`distance`,`sequence`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%s','%f','%d','%d','%d','0','%s')";
        $date = new Date();
        $today = $date->MySQLNow();
        //$routearrays = $routearray;
        $routeArray = explode(",", $routearray);
        $thourArray = explode(",", $thourarray);
        $tminArray = explode(",", $tminarray);
        $distanceArray = explode(",", $distancearray);
        $route->routeid;
        $listingCounter = 1;
        foreach ($routeArray as $key => $value) {
            echo $SQL = sprintf($Query, Sanitise::Long($routeid), Sanitise::Long($value), Sanitise::String(($thourArray[$key] * 60) + $tminArray[$key]), Sanitise::Float($distanceArray[$key]), Sanitise::Long($listingCounter), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($today));
            $this->_databaseManager->executeQuery($SQL);
            $listingCounter = $listingCounter + 1;
        }
        $Query = "INSERT INTO vehiclerouteman (`routeid`,`vehicleid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','%d','0','%s')";
        $date = new Date();
        $todayss = $date->MySQLNow();
        //echo $routearrays = $routearray;
        $vehicleArray = explode(",", $vehiclearray);
        foreach ($vehicleArray as $vehicleid) {
            if ($vehicleid != '') {
                $SQL = sprintf($Query, Sanitise::Long($routeid), Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todayss));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        //echo $routearrays = $routearray;
        $checkpointarray = explode(",", $routearray);
        $vehiclesArray = explode(",", $vehiclearray);
        foreach ($vehiclesArray as $vehiclesid) {
            if ($vehiclesid != '') {
                foreach ($checkpointarray as $checkpointid) {
                    if ($checkpointid != '') {
//                                $CheckQuery = "SELECT vehicleid FROM `checkpointmanage` WHERE checkpointid=%d AND vehicleid=%d AND customerno=%d AND isdeleted=0";
                        //                               $checkpointsQuery = sprintf($CheckQuery, Sanitise::Long($checkpointid), Sanitise::Long($vehiclesid),$this->_Customerno);
                        //                              $this->_databaseManager->executeQuery($checkpointsQuery);
                        //                             if($this->_databaseManager->get_rowCount() == 0)
                        //                            {
                        $Query = "INSERT INTO checkpointmanage (`checkpointid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d','%d','1','%d','0')";
                        $SQL = sprintf($Query, Sanitise::Long($checkpointid), Sanitise::Long($vehiclesid), $this->_Customerno, Sanitise::Long($userid));
                        $this->_databaseManager->executeQuery($SQL);
                        //                          }
                    }
                }
            }
        }
    }

//    public function getallroutes()
    //    {
    //        $routes = Array();
    //        $Query = "SELECT routeid,routename FROM `route` where customerno=%d AND isdeleted=0";
    //        $routeQuery = sprintf($Query,$this->_Customerno);
    //        $this->_databaseManager->executeQuery($routeQuery);
    //
    //            while ($row = $this->_databaseManager->get_nextRow())
    //            {
    //                $route = new VORoute();
    //                $route->routename = $row['routename'];
    //                $route->routeid = $row['routename'];
    //                $routes[] = $route;
    //            }
    //
    //        return $routes;
    //    }
    public function DeleteRoute($routeid, $userid) {
        $checkpoint_array = $this->get_all_checkpointid_forroute($routeid);
        $vehicles_Array = $this->getvehiclesforroute($routeid);
        foreach ($vehicles_Array as $vehicles) {
            if ($vehicles != '') {
                foreach ($checkpoint_array as $checkpoint) {
                    if ($checkpoint != '') {
                        $CheckQuery = "UPDATE `checkpointmanage` SET isdeleted='1' WHERE checkpointid=%d AND vehicleid=%d AND customerno=%d AND isdeleted=0";
                        $checkpointsQuery = sprintf($CheckQuery, Sanitise::Long($checkpoint->checkpointid), Sanitise::Long($vehicles->vehicleid), $this->_Customerno);
                        $this->_databaseManager->executeQuery($checkpointsQuery);
                    }
                }
            }
        }
        $Query = "UPDATE route SET isdeleted=1,userid=%d WHERE routeid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($routeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE routeman SET isdeleted=1,userid=%d WHERE routeid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($routeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE vehiclerouteman SET isdeleted=1,userid=%d WHERE routeid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($routeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_added_vehicles($routeid) {
        $vehicles = Array();
        $Query = 'select * from vehicle
            INNER JOIN vehiclerouteman ON vehiclerouteman.vehicleid = vehicle.vehicleid
            where vehiclerouteman.routeid=%s AND vehiclerouteman.customerno=%s AND vehicle.customerno=%s AND vehicle.isdeleted=0 AND vehiclerouteman.isdeleted=0';
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, Sanitise::Long($routeid), $this->_Customerno, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, Sanitise::Long($routeid), $this->_Customerno, $this->_Customerno);
        }
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

    public function get_shopscount() {
        $Query = "SELECT count(*) as shops FROM `routeman` where customerno=%d AND routeman.isdeleted=0 GROUP BY `routeid` ORDER BY shops DESC LIMIT 1 ";
        $RoutesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($RoutesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $shops = $row['shops'];
            }
            return $shops;
        }
        return null;
    }

    public function get_routeid_for_vehicleid($vehicleid) {
        $checkpoints = Array();
        $Query = "SELECT routeid FROM `vehiclerouteman` where customerno=%d AND vehiclerouteman.isdeleted=0 AND vehiclerouteman.vehicleid=%d LIMIT 1 ";
        $RoutesQuery = sprintf($Query, $this->_Customerno, $vehicleid);
        $this->_databaseManager->executeQuery($RoutesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $routeid = $row['routeid'];
            }
            return $routeid;
        }
        return NULL;
    }

    public function getroutebyvehicleid($vehicleid) {
        $checkpoints = Array();
        $routeid = $this->get_routeid_for_vehicleid($vehicleid);
        //$Query = "select * from routeman where routeid=%s AND customerno=%d AND isdeleted=0 ORDER BY sequence ASC";
        if (isset($routeid)) {
            $Query = "select * from checkpoint
                INNER JOIN routeman ON routeman.checkpointid = checkpoint.checkpointid
                where routeman.routeid=%d AND routeman.customerno=%s AND checkpoint.customerno=%s AND checkpoint.isdeleted=0 AND routeman.isdeleted=0 ORDER BY routeman.sequence ASC";
            $checkpointsQuery = sprintf($Query, Sanitise::Long($routeid), $this->_Customerno, $this->_Customerno);
            $this->_databaseManager->executeQuery($checkpointsQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $checkpoint = new VORoute();
                    $checkpoint->checkpointid = $row['checkpointid'];
                    $checkpoint->sequence = $row['sequence'];
                    $checkpoint->cname = $row['cname'];
                    $checkpoint->routeid = $row['routeid'];
                    $checkpoints[] = $checkpoint;
                }
                return $checkpoints;
            }
            return NULL;
        } else {
            return NULL;
        }
    }

    public function getroutebyvehicleidreverse($vehicleid) {
        $checkpoints = array();
        $routeid = $this->get_routeid_for_vehicleid($vehicleid);
        if (isset($routeid)) {
            $Query = "select * from checkpoint
                INNER JOIN routeman ON routeman.checkpointid = checkpoint.checkpointid
                where routeman.routeid=%d AND routeman.customerno=%s AND checkpoint.customerno=%s AND checkpoint.isdeleted=0 AND routeman.isdeleted=0
                ORDER BY routeman.sequence DESC";
            $checkpointsQuery = sprintf($Query, Sanitise::Long($routeid), $this->_Customerno, $this->_Customerno);
            $this->_databaseManager->executeQuery($checkpointsQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $checkpoint = new VORoute();
                    $checkpoint->checkpointid = $row['checkpointid'];
                    $checkpoint->sequence = $row['sequence'];
                    $checkpoint->cname = $row['cname'];
                    $checkpoint->routeid = $row['routeid'];
                    $checkpoints[] = $checkpoint;
                }
                return $checkpoints;
            }
            return null;
        } else {
            return null;
        }
    }

    public function getVehicleRoute() {
        $vehicles = Array();
        $Query = "vehicle.vehicleid, vehicle.cusrspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, vehicle.groupid, vehicle.stoppage_flag, vehicle.vehicleno,
                 vehicle.kind, devices.devicelat, devices.devicelong, devices.igmition, devices.status, customer.use_geolocation, ignitionalert.status as igstatus,
                 vehiclerouteman.routeid,
                INNER JOIN devices ON devices.uid = vehicle.uid
                INNER JOIN unit ON devices.uid = unit.uid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
                INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid";
    }

    public function getRoute($routeid) {
        $Query = "SELECT routename FROM route WHERE customerno=%d AND routeid=%d";
        $SQL = sprintf($Query, $this->_Customerno, $routeid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                echo $row['routename'];
            }
        }
    }

    public function getdevicekey($routeid) {
        $Query = "SELECT devicekey FROM route WHERE customerno=%d AND routeid=%d";
        $SQL = sprintf($Query, $this->_Customerno, $routeid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                echo $row['devicekey'];
            }
        }
    }

    public function getvehiclesByRoute() {
        $vehicles = Array();
        $Query = "SELECT vehiclerouteman.vehicleid, vehiclerouteman.routeid, route.routename,vehicle.vehicleid, vehicle.curspeed, vehicle.odometer,vehicle.overspeed_limit, vehicle.stoppage_transit_time,
                 vehicle.groupid, vehicle.stoppage_flag, vehicle.vehicleno, vehicle.kind, devices.devicelat, devices.devicelong, devices.ignition, devices.status, customer.use_geolocation,
                 devices.deviceid,devices.lastupdated, ignitionalert.status as igstatus, unit.unitno FROM vehiclerouteman
                INNER JOIN route ON route.routeid = vehiclerouteman.routeid
                INNER JOIN vehicle ON vehicle.vehicleid = vehiclerouteman.vehicleid
                INNER JOIN devices ON devices.uid = vehicle.uid
                INNER JOIN unit ON devices.uid = unit.uid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
                INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                WHERE vehiclerouteman.customerno=%d AND vehiclerouteman.isdeleted=0 ";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d Order by route.routename";
        } else {
            $Query .= " Order by route.routename";
        }
        if ($_SESSION['groupid'] != 0) {
            $routeQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $routeQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($routeQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->routeid = $row['routeid'];
                $vehicle->routename = $row['routename'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->groupid = $row['groupid'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->igstatus = $row["igstatus"];
                $vehicle->use_geolocation = $row["use_geolocation"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getvehiclesByRoute_ById($routeid) {
        $vehicles = Array();
        $Query = "SELECT vehiclerouteman.vehicleid, vehiclerouteman.routeid, route.routename,vehicle.vehicleid, vehicle.curspeed, vehicle.odometer,vehicle.overspeed_limit, vehicle.stoppage_transit_time,
                 vehicle.groupid, vehicle.stoppage_flag, vehicle.vehicleno, vehicle.kind, devices.devicelat, devices.devicelong, devices.ignition, devices.status, customer.use_geolocation,
                 devices.deviceid,devices.lastupdated, ignitionalert.status as igstatus, unit.unitno FROM vehiclerouteman
                INNER JOIN route ON route.routeid = vehiclerouteman.routeid
                INNER JOIN vehicle ON vehicle.vehicleid = vehiclerouteman.vehicleid
                INNER JOIN devices ON devices.uid = vehicle.uid
                INNER JOIN unit ON devices.uid = unit.uid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
                INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                WHERE vehiclerouteman.customerno=%d AND vehiclerouteman.routeid=%d AND vehiclerouteman.isdeleted=0 ";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d Order by route.routename";
        } else {
            $Query .= " Order by route.routename";
        }
        if ($_SESSION['groupid'] != 0) {
            $routeQuery = sprintf($Query, $this->_Customerno, $routeid, $_SESSION['groupid']);
        } else {
            $routeQuery = sprintf($Query, $this->_Customerno, $routeid);
        }
        $this->_databaseManager->executeQuery($routeQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->routeid = $row['routeid'];
                $vehicle->routename = $row['routename'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->groupid = $row['groupid'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->igstatus = $row["igstatus"];
                $vehicle->use_geolocation = $row["use_geolocation"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getStartLocation($routeid) {
        $Query = "SELECT routeman.checkpointid, routeman.timetaken, routeman.sequence, checkpoint.cname FROM routeman
                 INNER JOIN checkpoint ON checkpoint.checkpointid = routeman.checkpointid
                WHERE routeman.customerno = %d AND routeman.routeid=%d AND routeman.sequence=1 AND routeman.isdeleted=0 Limit 1";
        $SQL = sprintf($Query, $this->_Customerno, $routeid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chk = new VORoute();
                $chk->checkpointid = $row['checkpointid'];
                $chk->sequence = $row['sequence'];
                $chk->cname = $row['cname'];
                $chk->timetaken = $row['timetaken'];
            }
            return $chk;
        }
        return null;
    }

    public function getEndLocation($routeid) {
        $Query = "SELECT routeman.checkpointid, routeman.sequence, checkpoint.cname FROM routeman
                 INNER JOIN checkpoint ON checkpoint.checkpointid = routeman.checkpointid
                WHERE routeman.customerno = %d AND routeman.routeid=%d AND routeman.isdeleted=0 Order by routeman.sequence DESC Limit 1";
        $SQL = sprintf($Query, $this->_Customerno, $routeid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chk = new VORoute();
                $chk->checkpointid = $row['checkpointid'];
                $chk->sequence = $row['sequence'];
                $chk->cname = $row['cname'];
            }
            return $chk;
        }
        return null;
    }

    public function getStdTime($routeid) {
        $Query = "SELECT timetaken FROM routeman WHERE customerno=%d AND routeid=%d AND isdeleted=0 order by sequence DESC Limit 1";
        $SQL = sprintf($Query, $this->_Customerno, $routeid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['timetaken'];
            }
        }
    }

    public function getAllChkptForRoute($routeid) {
        $chks = array();
        $Query = "SELECT checkpointid,timetaken,distance FROM routeman WHERE customerno=%d AND routeid=%d AND isdeleted=0 Order by sequence ASC";
        $SQL = sprintf($Query, $this->_Customerno, $routeid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chk = new VORoute();
                $chk->checkpointid = $row['checkpointid'];
                $chk->timetaken = $row['timetaken'];
                $chk->distance = $row['distance'];
                $chks[] = $chk;
            }
            return $chks;
        }
        return null;
    }

    public function deleteRouteChk($chkid, $routeid) {
        $chkQuery = "SELECT checkpointid FROM routeman WHERE checkpointid = %d AND routeid=%d";
        $chkSQl = sprintf($chkQuery, $chkid, $routeid);
        $this->_databaseManager->executeQuery($chkSQl);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Query = "Delete FROM routeman WHERE customerno=%d AND routeid=%d AND checkpointid=%d";
            $SQL = sprintf($Query, $this->_Customerno, $routeid, $chkid);
            $this->_databaseManager->executeQuery($SQL);
            return "del";
        } else {
            return "notdel";
        }
    }

    public function get_route_details() {
        $route = array();
        $routnames = array();
        $vehicles = array();
        $Query = "SELECT a.routeid, a.routename, b.vehicleid, c.checkpointid, c.sequence
            FROM route as a
            INNER join routeman as c on a.routeid=c.routeid
            left join vehiclerouteman as b on a.routeid=b.routeid
            left join vehicle as d on d.vehicleid=b.vehicleid
            where a.isdeleted=0
            and b.isdeleted=0
            and c.isdeleted=0
            and d.isdeleted=0
            and a.customerno = $this->_Customerno
            and d.customerno = $this->_Customerno
            and c.customerno = $this->_Customerno
            and d.customerno = $this->_Customerno
            order by a.routeid, b.vehicleid, c.sequence";
        $this->_databaseManager->executeQuery($Query);
        $this->_databaseManager->get_rowCount();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $route[$row['routeid']][$row['vehicleid']][$row['sequence']] = $row['checkpointid'];
                $vehicles[$row['vehicleid']] = $row['vehicleid'];
                $routnames[$row['routeid']] = $row['routename'];
            }
        }
        if (!empty($vehicles)) {
            $veh_details = array();
            foreach ($vehicles as $vehid) {
                if (!isset($veh_details[$vehid])) {
                    $veh_details[$vehid] = $this->trip_summary_vehicles($vehid);
                }
            }
        }
        return array($route, $routnames, $veh_details);
    }

    public function get_routedetails($groupid = null) {
        $grpCondition = '';
        if (isset($groupid) && $groupid != 0) {
            $grpCondition = " AND d.groupid = $groupid";
        }
        $route = array();
        $routnames = array();
        $vehicles = array();
        $routeTat = array();
        $Query = "SELECT a.routeid, a.routename, b.vehicleid, c.checkpointid, c.sequence, a.routeTat
            FROM route as a
            LEFT join vehiclerouteman as b on a.routeid=b.routeid
            LEFT join routeman as c on a.routeid=c.routeid
            LEFT join vehicle as d on d.vehicleid=b.vehicleid
            where a.isdeleted=0
            and b.isdeleted=0
            and c.isdeleted=0
            and d.isdeleted=0
            and a.customerno = $this->_Customerno
            and d.customerno = $this->_Customerno " . $grpCondition . "
            order by a.routeid, b.vehicleid, c.sequence
        "; //echo"Query is: ".$Query; exit();
        $this->_databaseManager->executeQuery($Query);
        $this->_databaseManager->get_rowCount();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $route[$row['routeid']][$row['vehicleid']][$row['sequence']] = $row['checkpointid'];
                $vehicles[$row['vehicleid']] = $row['vehicleid'];
                $routnames[$row['routeid']] = $row['routename'];
                $routeTat[$row['routeid']] = $row['routeTat'];
            }
        }
        if (!empty($vehicles)) {
            $veh_details = array();
            foreach ($vehicles as $vehid) {
                if (!isset($veh_details[$vehid])) {
                    $veh_details[$vehid] = $this->trip_summary_vehicles($vehid);
                }
            }
        }
        return array($route, $routnames, $veh_details, $routeTat);
    }

    public function get_routedetailsroute($routeid) {
        $route = array();
        $routnames = array();
        $vehicles = array();
        $Query = "SELECT a.routeid, a.routename, b.vehicleid, /*c.checkpointid, c.sequence,*/ a.routeTat
            FROM route as a
            LEFT join vehiclerouteman as b on a.routeid=b.routeid
            /*LEFT join routeman as c on a.routeid=c.routeid*/
            LEFT join vehicle as d on d.vehicleid=b.vehicleid
            where a.isdeleted=0
            and a.routeid = $routeid
            and b.isdeleted=0
           /* and c.isdeleted=0*/
            and d.isdeleted=0
            and a.customerno = $this->_Customerno
            and d.customerno = $this->_Customerno
            order by a.routeid, b.vehicleid/*, c.sequence*/
        "; //echo"Query is: ".$Query; exit();
        $this->_databaseManager->executeQuery($Query);
        $this->_databaseManager->get_rowCount();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                // $route[$row['routeid']][$row['vehicleid']][$row['sequence']] = $row['checkpointid'];
                $vehicles[$row['vehicleid']] = $row['vehicleid'];
                $routnames[$row['routeid']] = $row['routename'];
                $routeTat[$row['routeid']] = $row['routeTat'];
            }
        }
        //echo"Data is<pre>"; print_r($vehicles); exit();
        if (!empty($vehicles)) {
            $vehicleId = '';
            $veh_details = array();
            foreach ($vehicles as $vehid) {
                if (!isset($veh_details[$vehid])) {
                    $vehicleId .= $vehid . ',';
                    //$veh_details[$vehid] = $this->trip_summary_vehicles($vehid);
                }
            }
            //echo"Vehicle id string is: ". rtrim($vehicleId,','); exit();
            $veh_details = $this->trip_summary_vehicles(rtrim($vehicleId, ','));
        }
        return array($route, $routnames, $veh_details, $routeTat);
    }

    public function getRouteChkDetails($routeid, $chkid) {
        $details = array();
        $sql = "SELECT eta, etd, r_eta, r_etd, sequence FROM routeman WHERE routeid= %s AND checkpointid=%d AND customerno=%d and isdeleted=0";
        $Query = sprintf($sql, $routeid, $chkid, $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $details = $row;
            }
        }
        return $details;
    }

    public function trip_summary_vehicles($vehid) {
        //echo"<br>Vehicle id: ".$vehid;
        $vehs = array();
        /* $Query = "SELECT a.vehicleid, a.vehicleno, b.drivername, b.driverphone, c.devicelat, c.devicelong, d.unitno
          FROM vehicle as a
          left join driver as b on a.driverid=b.driverid
          left join devices as c on a.uid=c.uid
          left join unit as d on a.uid=d.uid
          where a.vehicleid=$vehid and a.isdeleted=0 group by a.vehicleid
          "; */
        $Query = "SELECT a.vehicleid, a.vehicleno, b.drivername, b.driverphone, c.devicelat, c.devicelong, d.unitno
            FROM vehicle as a
            left join driver as b on a.driverid=b.driverid
            left join devices as c on a.uid=c.uid
            left join unit as d on a.uid=d.uid
            where a.vehicleid IN($vehid) and a.isdeleted=0 group by a.vehicleid
        "; //echo"QUery is: ".$Query; exit();
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $city = location_cmn($row['devicelat'], $row['devicelong'], 0, $this->_Customerno);
                //$city = city_by_google($row['devicelat'],$row['devicelong']);
                /* $vehs = array(
                  'vehid' => $row['vehicleid'],
                  'vehno' => $row['vehicleno'],
                  'unitno' => $row['unitno'],
                  'driverno' => $row['drivername'],
                  'cellno' => $row['driverphone'],
                  'city' => $city,
                  ); */
                $vehs[$row['vehicleid']]['vehid'] = $row['vehicleid'];
                $vehs[$row['vehicleid']]['vehno'] = $row['vehicleno'];
                $vehs[$row['vehicleid']]['unitno'] = $row['unitno'];
                $vehs[$row['vehicleid']]['driverno'] = $row['drivername'];
                $vehs[$row['vehicleid']]['cellno'] = $row['driverphone'];
                $vehs[$row['vehicleid']]['city'] = $city;
            }
        }
        return $vehs;
    }

    public function get_route_fromcustomer($customerno) {
        $routes = array();
        $Query = 'SELECT *  FROM `route` where isdeleted=0 AND customerno=' . $customerno;
        $customerRouteQuery = sprintf($Query, Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($customerRouteQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $route = new stdClass();
                $route->routeid = $row["routeid"];
                $route->routename = $row["routename"];
                $routes[] = $route;
            }
            return $routes;
        }
        $route = new stdClass();
        $route->routeid = 0;
        $route->routename = "";
        $routes[] = $route;
        return $routes;
    }

    public function demapVehicleRouteMapping($routeid, $vehicleid, $customerno, $userid) {
        $date = new Date();
        $today = $date->MySQLNow();
        $Query = "UPDATE vehiclerouteman SET isdeleted=1, `timestamp`  = '%s' , userid = %d WHERE routeid=%d and vehicleid = %d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::Long($userid), Sanitise::Long($routeid), Sanitise::Long($vehicleid), $customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_routes_for_vehicleid($vehicleid) {
        $checkpoints = Array();
        $Query = "SELECT v.routeid, r.routename FROM `vehiclerouteman` v
        INNER JOIN route r on r.routeid = v.routeid
        where v.customerno=%d AND v.isdeleted=0 AND v.vehicleid=%d LIMIT 1 ";
        $RoutesQuery = sprintf($Query, $this->_Customerno, $vehicleid);
        $this->_databaseManager->executeQuery($RoutesQuery);
        $route = array();
        $obj = new stdClass();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $obj->routeid = $row['routeid'];
                $obj->routename = $row['routename'];
                $route[] = $obj;
            }
            return $route;
        }
        return NULL;
    }

    public function addFutureRoute($routearray, $vehicleid, $userid) {
        $Query = "UPDATE futureRoutes SET isDeleted=1 WHERE vehicleId = %d AND customerNo=%d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        /* $theArray = explode(",", $routearray);
          foreach ($theArray as $routeid) {
          $sql = "SELECT frId FROM futureRoutes WHERE routeid= %s AND vehicleid=%d AND customerno=%d and isdeleted=0";
          $Query1 = sprintf($sql, $routeid, $vehicleid, $this->_Customerno);
          // die;
          $this->_databaseManager->executeQuery($Query1);
          // $c = $this->_databaseManager->get_rowCount();
          // echo "count is ". $c; die;
          if ($this->_databaseManager->get_rowCount() > 0) {
          // echo "Something Went Wrong";
          echo $error = 1;
          return $error;
          }
          }
         */
        $Query = "INSERT INTO futureRoutes (`customerNo`,`vehicleId`, `routeId`,`nextRouteId`,`frSequence`,`createdBy`, `createdOn` ,`updatedBy`, `updatedOn`, `isDeleted`) VALUES (%d,'%d','%d','%d','%d','%d','%s','%d','%s', '0')";
        $date = new Date();
        $today = $date->MySQLNow();
        //echo $routearrays = $routearray;
        $theArray = explode(",", $routearray);
        $listingCounter = 1;
        // $db->exec('BEGIN IMMEDIATE TRANSACTION');
        $i = 0;
        foreach ($theArray as $key => $value) {
            //$routeid
            $i = $key + 1;
            $nextRouteId = 0;
            if ($key + 1 != count($theArray)) {
                $nextRouteId = $theArray[$i];
            }
            $SQL = sprintf($Query, $this->_Customerno, Sanitise::Long($vehicleid), Sanitise::Long($value), // routeid
                    Sanitise::Long($nextRouteId), Sanitise::Long($listingCounter), Sanitise::Long($userid), Sanitise::DateTime($today), Sanitise::Long($userid), Sanitise::DateTime($today)
            );
            $this->_databaseManager->executeQuery($SQL);
            // $db->exec($SQL);
            $listingCounter = $listingCounter + 1;
        }
        // $db->exec('COMMIT TRANSACTION');
    }

    public function getNextFutureRoute($routeid, $vehicleid) {
        $details = array();
        $sql = "SELECT nextRouteId, updatedBy as userid FROM futureRoutes WHERE routeid= %d AND vehicleid=%d AND customerno=%d and isdeleted=0 ORDER BY frSequence ASC LIMIT 1";
        $Query = sprintf($sql, $routeid, $vehicleid, $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $details = $row;
            }
        }
        $date = new Date();
        $today = $date->MySQLNow();
        $Query1 = "UPDATE futureRoutes SET isDeleted = 1, updatedOn = '%s' WHERE vehicleId = %d AND customerNo=%d AND routeId = %d AND isDeleted = 0";
        $SQL = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::Long($routeid));
        $this->_databaseManager->executeQuery($SQL);
        return $details;
    }

    public function get_future_routes_for_vehicleid($vehicleid) {
        $futureRoute = array();
        $Query = "SELECT f.routeid, r.routename
        FROM `futureRoutes` f
        INNER JOIN route r on r.routeid = f.routeid
        where f.customerno=%d AND f.isdeleted=0 AND f.vehicleid=%d ORDER BY f.frSequence ASC ";
        $RoutesQuery = sprintf($Query, $this->_Customerno, $vehicleid);
        $this->_databaseManager->executeQuery($RoutesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $obj = new stdClass();
                $obj->routeid = $row['routeid'];
                $obj->routename = $row['routename'];
                $futureRoute[] = $obj;
            }
            return $futureRoute;
        }
        return NULL;
    }

    public function getRouteTypes() {
        $routes = Array();
        $Query = "select
            distinct rtm.routeTypeName,
            rtm.id,
            rtm.routeTypeId
            from
            route_type_master as rtm
            where
            rtm.customerNo = %d AND rtm.isDeleted=0";
        $routesQuery = sprintf($Query, $this->_Customerno);
        //echo"Route query is: ".$routesQuery; exit();
        $this->_databaseManager->executeQuery($routesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $route = new stdClass();
                $route->routeTypeId = $row['routeTypeId'];
                $route->routeTypeName = $row['routeTypeName'];
                $routes[] = $route;
            }
            return $routes;
        }
        return null;
    }

    /* Function to fetch route wise tracking data starts here */

    public function getRouteWiseData($route, $startDate = null, $endDate = null, $customerno, $reportType = null, $vehicleId = 0, $isAPI = 0) {
        // SP name = fetch_data_for_route_wise_report
        // input parameters
        // routeIdParam
        // customerNoParam generateVehicleWiseRouteDataLive
        $QUERY = 'CALL ' . speedConstants::SP_FETCH_DATA_FOR_ROUTE_WISE_REPORT . '(' . $route . ',' . $vehicleId . ',' . $this->_Customerno . ')';
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $dataArray = [];
            $allData = $this->_databaseManager->get_recordSet();
            /* Separating checkpoint data */
            if ($isAPI) {
                $route = $allData[0]['routeid'];
            }
            $checkPointsDataBySequence = $this->seperateCheckPointDataBySequentially($allData, $route); //echo"checkpoints:<pre>"; print_r($checkPointsDataBySequence); exit();
            $getUniqueVehicleIdFromAllData = $this->getDistinctVehicleIdFromAllData($allData);
            $vehcileWiseRouteData = NULL;
            if (!empty($checkPointsDataBySequence)) {
                if ($reportType != 'liveReport') {
                    $vehcileWiseRouteData = $this->generateVehicleWiseRouteData($getUniqueVehicleIdFromAllData, $checkPointsDataBySequence, $startDate, $endDate, $customerno, $allData[0]['routeTat'], $isAPI);
                } else {
                    $vehcileWiseRouteData = $this->generateVehicleWiseRouteDataLive($getUniqueVehicleIdFromAllData, $checkPointsDataBySequence, $customerno, $allData[0]['routeTat']);
                }
            }
            if (!$isAPI) {
                $dataArray['checkPoints'] = $checkPointsDataBySequence;
            }
            if ($vehcileWiseRouteData == null || $vehcileWiseRouteData == '' || $vehcileWiseRouteData == false) {
                if ($isAPI) {
                    $dataArray = NULL;
                } else {
                    $dataArray['vehcile_wise_route_data'] = false;
                }
            } else {
                if ($isAPI) {
                    $dataArray = $vehcileWiseRouteData;
                } else {
                    $dataArray['vehcile_wise_route_data'] = $vehcileWiseRouteData;
                }
            }
            return $dataArray;
            //echo"Data got for report:<pre>"; print_r($vehcileWiseRouteData); exit();
        } else {
            return null;
        }
    }

    /* Function to fetch route wise tracking data ends here */

    public function seperateCheckPointDataBySequentially($allData, $route) {
        $checkPointWiseData = [];
        foreach ($allData as $key => $value) {
            if ($key == 0) {
                $checkPointWiseData[$key]['checkpointid'] = $value['checkpointid'];
                $checkPointWiseData[$key]['cname'] = $value['cname'];
                $checkPointWiseData[$key]['sequence'] = $value['sequence'];
                $checkPointWiseData[$key]['eta'] = $value['eta'];
                $checkPointWiseData[$key]['etd'] = $value['etd'];
                $checkPointWiseData[$key]['cgeolat'] = isset($value['cgeolat']) ? $value['cgeolat'] : '0';
                $checkPointWiseData[$key]['cgeolong'] = isset($value['cgeolong']) ? $value['cgeolong'] : '0';
                $checkPointWiseData[$key]['kmFromLastCheckpoint'] = isset($value['kmFromLastCheckpoint']) ? $value['kmFromLastCheckpoint'] : 'NA';
            } else {
                if ($value['sequence'] != 1) {
                    $checkPointWiseData[$key]['checkpointid'] = $value['checkpointid'];
                    $checkPointWiseData[$key]['cname'] = $value['cname'];
                    $checkPointWiseData[$key]['sequence'] = $value['sequence'];
                    $checkPointWiseData[$key]['eta'] = $value['eta'];
                    $checkPointWiseData[$key]['etd'] = $value['etd'];
                    $checkPointWiseData[$key]['cgeolat'] = isset($value['cgeolat']) ? $value['cgeolat'] : '0';
                    $checkPointWiseData[$key]['cgeolong'] = isset($value['cgeolong']) ? $value['cgeolong'] : '0';
                    $checkPointWiseData[$key]['kmFromLastCheckpoint'] = isset($value['kmFromLastCheckpoint']) ? $value['kmFromLastCheckpoint'] : 'NA';
                } else {
                    break;
                }
            }
        }

        if (isset($allData[0]['isReverseRoute']) && $allData[0]['isReverseRoute'] == 1) {
            //return array_reverse($checkPointWiseData);
            //$_SESSION['customerno'];

            $chkManager = new CheckpointManager($this->_Customerno);
            $arrCheckpoints = $chkManager->getCheckPointWiseDataForReverseRoute($this->_Customerno, $route);

            return $arrCheckpoints;
            //echo"checkpoint for reverse route is: <pre>";
            //print_r($arrCheckpoints); exit();
            /*  $QUERY = 'CALL fetch_data_for_route_wise_report_checkpoints('.$route.','.$this->_Customerno.')';
              //$QUERY = 'CALL '.speedConstants::SP_FETCH_DATA_FOR_ROUTE_WISE_REPORT.'('.$route.','.$this->_Customerno.')';
              $this->_databaseManager->executeQuery($QUERY);
              if ($this->_databaseManager->get_rowCount() > 0) {
              $dataArray = [];
              $allData = $this->_databaseManager->get_recordSet();
              print_r($allData);
              //return $allData;
              } */

            //print_r($this->getCheckPointWiseDataForReverseRoute($_SESSION['customerno'],$route)); exit();
            //return $this->getCheckPointWiseDataForReverseRoute($_SESSION['customerno'],$route);
            //return $checkPointWiseData;
        } else {
            return $checkPointWiseData;
        }
        /*
          echo"all data is: <pre>"; print_r($allData);
          echo"Sequenced checkpoints: <pre>"; print_r($checkPointWiseData);
          echo"Reversed array:<pre>"; print_r(array_reverse($checkPointWiseData)); exit();
          return $checkPointWiseData; */
    }

    public function getDistinctVehicleIdFromAllData($allData) {
        $tempArr = array_unique(array_column($allData, 'vehicleid'));
        return array_values(array_intersect_key($allData, $tempArr));
    }

    public function generateVehicleWiseRouteData(array $vehicles, array $checkpointsData, $startDate, $endDate, $customerno, $tat, $isAPI = 0) {
        $getAvailableVehiclesForReport = [];
        foreach ($vehicles AS $key => $value) {
            if ($this->isVehcileStartedFromFirstCheckPoint($value['vehicleid'], $checkpointsData[0]['checkpointid'], $startDate, $endDate, $customerno) == true || $isAPI) {
                $getAvailableVehiclesForReportData = $this->generateRouteWiseReport($value, $checkpointsData, $startDate, $endDate, $customerno, $tat, $isAPI);
                if (isset($getAvailableVehiclesForReportData) && !empty($getAvailableVehiclesForReportData)) {
                    $getAvailableVehiclesForReport[] = $getAvailableVehiclesForReportData;
                }
            }
        }
        if (!isset($getAvailableVehiclesForReport) || empty($getAvailableVehiclesForReport)) {
            return NULL;
        } else {
            return $getAvailableVehiclesForReport;
        }
    }

    /* FUnction for generating live tracking report starts here */

    public function generateVehicleWiseRouteDataLive(array $vehicles, array $checkpointsData, $customerno, $tat) {
        $getAvailableVehiclesForReport = [];
        foreach ($vehicles AS $key => $value) {
            /*  if($this->isVehcileStartedFromFirstCheckPoint($value['vehicleid'],$checkpointsData[0]['checkpointid'],$startDate,$endDate,$customerno))
              { */
            $getAvailableVehiclesForReport[] = $this->generateRouteWiseReportLive($value, $checkpointsData, $customerno, $tat);
            /*  } */
        }
        return $getAvailableVehiclesForReport;
    }

    /* FUnction for generating live tracking reporst ends here */

    public function isVehcileStartedFromFirstCheckPoint($vehcileId, $checkpointId, $startDate, $endDate, $customerno) {
        $path = "sqlite:" . RELATIVE_PATH_DOTS . "customer/$customerno/reports/chkreport.sqlite";
//        $path = "sqlite: ".$RELATIVE_PATH_DOTS."customer/$customerno/reports/chkreport.sqlite";
        if (isset($startDate) && isset($endDate)) {
            $Query = "  SELECT  chkrepid
                        FROM    V" . $vehcileId . "
                        WHERE   (chkid = " . $checkpointId . ")
                        AND     (date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')
                        AND     (status = 1)
                        LIMIT   1";
        } else {
            $Query = "  SELECT  chkrepid
                        FROM    V" . $vehcileId . "
                        WHERE   (chkid = " . $checkpointId . ")
                        AND     (status = 1)
                        LIMIT   1";
        }
//        echo $path;
        //        echo $Query;
        //        die();
        try {
            $db = new PDO($path);
            /* $result = $db->query($Query); */
            $sth = $db->prepare($Query);
            if ($sth) {
                $sth->execute();
                $result = $sth->fetchAll();
                if (count($result) > 0) {
                    //echo"<br>In true";
                    return true;
                } else {
                    //echo"<br>In false";
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo "<br>Exception is: " . $e;
            exit();
        }
    }

    public function generateRouteWiseReport($vehcileId, $checkPointData, $startDate, $endDate, $customerno, $tat, $isAPI = 0) {
        /*  echo"Vehicle Data is:<pre>"; print_r($vehcileId); */
        // echo"Checkpoints data is: <pre>"; print_r($checkPointData);     exit();
        $startDateTime = '';
        $returnResultData = [];
        $checkPointId = '';
        $path = "sqlite:" . RELATIVE_PATH_DOTS . "customer/$customerno/reports/chkreport.sqlite";
        try {
            /* $checkPointStoppageCount=0; */
            $startDataTime = '';
            $checkPointDataCount = count($checkPointData);
            $vehcileCurrentStatus = $this->getVehicleCurrentStatus($vehcileId['vehicleid']);
            $ids = 0;
            if (!empty($checkPointData)) {
                $ids = $checkPointData[0]['checkpointid'];
                if ($isAPI) {
                    $startDate = $this->getActualTimeOfArriaval($vehcileId['vehicleid'], NULL, NULL, $ids, $customerno);
                    $endDate = date('Y-m-d');
                }
            }
            $getTripDataForVehicle = $this->getTripDataForVehicle($path, $vehcileId['vehicleid'], $startDate, $endDate, $ids, $tat, $isAPI);
            $getTripDataForVehicle = (isset($getTripDataForVehicle) && !empty($getTripDataForVehicle)) ? array_values($getTripDataForVehicle) : NULL;
            $getUnitId = $this->getUnitId($vehcileId['vehicleid'], $customerno);
            $CrossCheckDate = '';
            if (!empty($getTripDataForVehicle)) {
                /* New code starts here */
                if ($isAPI) {

                    $vehicleCurrentLocation = $this->getVehicleCurrentVehicleLocation($vehcileCurrentStatus[0]->devicelat, $vehcileCurrentStatus[0]->devicelong);
                    $lastETD = '';
                    foreach ($getTripDataForVehicle AS $tripKey => $tripValue) {
                        $CrossCheckDate = $tripValue['date'];
                        $cumulativeKM = 0;
                        $lastChkRemark = '';
                        foreach ($checkPointData as $key2 => $value2) {
                            $routeDetails = array();
                            $routeDetails['checkpointid'] = $value2['checkpointid'];
                            $routeDetails['checkpointname'] = $value2['cname'];
                            if (isset($value2['kmFromLastCheckpoint']) && is_numeric($value2['kmFromLastCheckpoint'])) {
                                $cumulativeKM = $cumulativeKM + $value2['kmFromLastCheckpoint'];
                                $routeDetails['kmFromLastCheckpoint'] = $cumulativeKM;
                            } else {
                                $routeDetails['kmFromLastCheckpoint'] = 'NA';
                            }
                            if ($key2 == 0) { // From here vehicle starts, there won't be halt time,estimated arrival, if started late then display started late
                                $totalDuration = 0;
                                $checkPointStoppageCount = 0;
                                $startDataTime = $tripValue['date'];
                                $routeDetails['eta'] = 'NA';
                                $startDataForStoppageCount = $tripValue['date'];
                                $routeDetails['eht'] = 'NA';
                                $routeDetails['ata'] = 'NA';
                                $routeDetails['atd'] = date("d-m-Y H:i", strtotime($tripValue['date']));
                                if ($value2['etd'] == '00:00:00') {
                                    $routeDetails['etd'] = 'NA';
                                } else {
                                    $routeDetails['etd'] = date('d-m-Y H:i', strtotime(date('Y-m-d', strtotime($routeDetails['atd'])) . ' ' . $value2['etd']));
                                }
                                if ($routeDetails['etd'] != '' && $routeDetails['etd'] != 'NA') {
                                    $lastETD = $routeDetails['etd'];
                                }
                                $routeDetails['aht'] = 'NA';
                            } else {
                                if ($key2 == $checkPointDataCount - 1) { // At this checkpoint vehcile stops, there won't be halt time,estimated departure, if arrived late then late arrival
                                    $sqliteDataa = $this->getDataFromSqlite($path, $vehcileId['vehicleid'], $startDate, $endDate, $value2['checkpointid'], 0, '', $CrossCheckDate);

                                    if (isset($sqliteDataa[0]['date'])) {
                                        $routeDetails['etd'] = 'NA';
                                        $routeDetails['eht'] = 'NA';
                                        $routeDetails['ata'] = date("d-m-Y H:i", strtotime($sqliteDataa[0]['date'])); //$sqliteDataa[0]['date'];
                                        //$routeDetails ['ata']  = date("d-m-Y H:i", strtotime($sqliteDataa[0]['date']));
                                        $endDataForStoppageCount = $sqliteDataa[0]['date'];
                                        $routeDetails['atd'] = 'NA';
                                        $routeDetails['aht'] = 'NA';
                                        if ($value2['eta'] == '00:00:00') {
                                            $routeDetails['etd'] = 'NA';
                                        } else {
                                            if ($routeDetails['ata'] != '' && $routeDetails['ata'] != 'NA') {
                                                $routeDetails['etd'] = date('d-m-Y H:i', strtotime(date('Y-m-d', strtotime($routeDetails['ata'])) . ' ' . $value2['eta']));
                                            } else {
                                                $tempETD = date('Y-m-d', strtotime($lastETD)) . ' ' . $value2['eta'];
                                                if (strtotime($tempETD) > strtotime($lastETD)) {
                                                    $routeDetails['etd'] = $tempETD;
                                                } else {
                                                    $routeDetails['etd'] = date('d-m-Y H:i', strtotime(date('Y-m-d', strtotime($tempETD)) . ' ' . $value2['eta'] . ' +1 day'));
                                                }
                                            }
                                        }
                                        if ($routeDetails['etd'] != '' && $routeDetails['etd'] != 'NA') {
                                            $lastETD = $routeDetails['etd'];
                                        }
                                        $routeDetails['tat'] = $tat;
                                        $actualTat = $this->getActalTat($startDataTime, $sqliteDataa[0]['date']);
                                        $routeDetails['atat'] = abs($actualTat);
                                        $routeDetails['Remark'] = $this->getRemarkFinal($actualTat, $tat);
                                    } else {
                                        //$routeDetails['etd'] = 'NA';
                                        $routeDetails['eht'] = 'NA';
                                        if (isset($sqliteDataa['date']) && $sqliteDataa['date'] != '' && $sqliteDataa['date'] != null) {
                                            $routeDetails['ata'] = date("d-m-Y H:i", strtotime($sqliteDataa['date']));
                                        } else {
                                            $routeDetails['ata'] = 'NA';
                                        }
                                        if ($value2['eta'] == '00:00:00') {
                                            $routeDetails['etd'] = 'NA';
                                        } else {
                                            if ($routeDetails['ata'] != '' && $routeDetails['ata'] != 'NA') {
                                                $routeDetails['etd'] = date('d-m-Y H:i', strtotime(date('Y-m-d', strtotime($routeDetails['ata'])) . ' ' . $value2['eta']));
                                            } else {
                                                $tempETD = date('Y-m-d', strtotime($lastETD)) . ' ' . $value2['eta'];
                                                if (strtotime($tempETD) > strtotime($lastETD)) {
                                                    $routeDetails['etd'] = $tempETD;
                                                } else {
                                                    $routeDetails['etd'] = date('d-m-Y H:i', strtotime(date('Y-m-d', strtotime($tempETD)) . ' ' . $value2['eta'] . ' +1 day'));
                                                }
                                            }
                                        }
                                        if ($routeDetails['etd'] != '' && $routeDetails['etd'] != 'NA') {
                                            $lastETD = $routeDetails['etd'];
                                        }
                                        $endDataForStoppageCount = $sqliteDataa['date'];
                                        $routeDetails['atd'] = 'NA';
                                        $routeDetails['aht'] = 'NA';
                                    }
                                } else {
                                    $sqliteData = $this->getDataFromSqlite($path, $vehcileId['vehicleid'], $startDate, $endDate, $value2['checkpointid'], 0, '', $CrossCheckDate);
                                    if ($sqliteData != null) {
                                        $sqliteData1 = $this->getDataFromSqlite($path, $vehcileId['vehicleid'], $startDate, $endDate, $value2['checkpointid'], 1, '', $CrossCheckDate);
                                        if ($sqliteData1 != null) {
                                            $estimatedHaltTime = $this->getTimeDiff($value2['eta'], $value2['etd']);
                                            $routeDetails['eht'] = $estimatedHaltTime;

                                            if ($this->getActalTat($sqliteData[0]['date'], $CrossCheckDate) > $tat + 5) {
                                                $routeDetails['ata'] = 'NA'; /* date("d-m-Y H:i",strtotime($sqliteData[0]['date'])); *//* date("d-m-Y H:i", strtotime($this->getActualTimeOfArriaval($vehcileId['vehicleid'],$startDate,$endDate,$value2['checkpointid'],$customerno))); */
                                                $routeDetails['atd'] = 'NA';

                                                $routeDetails['aht'] = 'NA'; /* date("d-m-Y H:i",strtotime($sqliteData1[0]['date'])); *//* date("d-m-Y H:i", strtotime($this->getActualTimeOfDeparture($vehcileId['vehicleid'],$startDate,$endDate,$value2['checkpointid'],$customerno))); */
                                            } else {
                                                $routeDetails['ata'] = date("d-m-Y H:i", strtotime($sqliteData[0]['date'])); /* date("d-m-Y H:i", strtotime($this->getActualTimeOfArriaval($vehcileId['vehicleid'],$startDate,$endDate,$value2['checkpointid'],$customerno))); */
                                                $routeDetails['atd'] = date("d-m-Y H:i", strtotime($sqliteData1[0]['date'])); /* date("d-m-Y H:i", strtotime($this->getActualTimeOfDeparture($vehcileId['vehicleid'],$startDate,$endDate,$value2['checkpointid'],$customerno))); */
                                                //$haltTime = $this->getHaltTime($vehcileId['vehicleid'],$startDate,$endDate,$value2['checkpointid'],$customerno);
                                                $haltTime = $this->getTimeDiffNew(date("d-m-Y H:i", strtotime($sqliteData1[0]['date'])), date("d-m-Y H:i", strtotime($sqliteData[0]['date']))); //$this->getHaltTimeNew($sqliteData1[0]['date'],$sqliteData[0]['date']);
                                                $routeDetails['aht'] = $haltTime;
                                                $totalDuration = $totalDuration + strtotime($haltTime);
                                            }
                                            if ($value2['eta'] == '00:00:00') {
                                                $routeDetails['etd'] = 'NA';
                                            } else {
                                                if ($routeDetails['ata'] != '' && $routeDetails['ata'] != 'NA') {
                                                    $routeDetails['etd'] = date('d-m-Y H:i', strtotime(date('Y-m-d', strtotime($routeDetails['ata'])) . ' ' . $value2['eta']));
                                                } else {
                                                    $tempETD = date('Y-m-d', strtotime($lastETD)) . ' ' . $value2['eta'];
                                                    if (strtotime($tempETD) > strtotime($lastETD)) {
                                                        $routeDetails['etd'] = $tempETD;
                                                    } else {
                                                        $routeDetails['etd'] = date('d-m-Y H:i', strtotime(date('Y-m-d', strtotime($tempETD)) . ' ' . $value2['eta'] . ' +1 day'));
                                                    }
                                                }
                                            }
                                            if ($routeDetails['etd'] != '' && $routeDetails['etd'] != 'NA') {
                                                $lastETD = $routeDetails['etd'];
                                            }
                                            $checkPointStoppageCount++;
                                        } else {
                                            $estimatedHaltTime = $this->getTimeDiff($value2['eta'], $value2['etd']);
                                            $routeDetails['eht'] = $estimatedHaltTime;
                                            $actualTimeOfArriaval = $this->getActualTimeOfArriaval($vehcileId['vehicleid'], $startDate, $endDate, $value2['checkpointid'], $customerno);
                                            if (isset($actualTimeOfArriaval) && $actualTimeOfArriaval != '' && $actualTimeOfArriaval != '--') {
                                                $routeDetails['ata'] = date("d-m-Y H:i", strtotime($actualTimeOfArriaval));
                                            } else {
                                                $routeDetails['ata'] = 'NA';
                                            }

                                            if ($value2['eta'] == '00:00:00') {
                                                $routeDetails['etd'] = 'NA';
                                            } else {
                                                if ($routeDetails['ata'] != '' && $routeDetails['ata'] != 'NA') {
                                                    $routeDetails['etd'] = date('d-m-Y H:i', strtotime(date('Y-m-d', strtotime($routeDetails['ata'])) . ' ' . $value2['eta']));
                                                } else {
                                                    $tempETD = date('Y-m-d', strtotime($lastETD)) . ' ' . $value2['eta'];
                                                    if (strtotime($tempETD) > strtotime($lastETD)) {
                                                        $routeDetails['etd'] = $tempETD;
                                                    } else {
                                                        $routeDetails['etd'] = date('d-m-Y H:i', strtotime(date('Y-m-d', strtotime($tempETD)) . ' ' . $value2['eta'] . ' +1 day'));
                                                    }
                                                }
                                            }
                                            if ($routeDetails['etd'] != '' && $routeDetails['etd'] != 'NA') {
                                                $lastETD = $routeDetails['etd'];
                                            }
                                            $routeDetails['atd'] = 'NA';
                                            $routeDetails['aht'] = 'NA';
                                            $routeDetails['Remark'] = '';
                                            $checkPointStoppageCount++;
                                            $haltTime = isset($haltTime) ? $haltTime : 0;
                                            $totalDuration = $totalDuration + strtotime($haltTime);
                                        }
                                    } else {
                                        if ($value2['eta'] == '00:00:00') {
                                            $routeDetails['etd'] = 'NA';
                                        } else {
                                            $tempETD = date('Y-m-d', strtotime($lastETD)) . ' ' . $value2['eta'];
                                            if (strtotime($tempETD) > strtotime($lastETD)) {
                                                $routeDetails['etd'] = $tempETD;
                                            } else {
                                                $routeDetails['etd'] = date('d-m-Y H:i', strtotime(date('Y-m-d', strtotime($tempETD)) . ' ' . $value2['eta'] . ' +1 day'));
                                            }
                                        }
                                        if ($routeDetails['etd'] != '' && $routeDetails['etd'] != 'NA') {
                                            $lastETD = $routeDetails['etd'];
                                        }
                                        $estimatedHaltTime = $this->getTimeDiff($value2['eta'], $value2['etd']);
                                        $routeDetails['eht'] = $estimatedHaltTime;
                                        $routeDetails['ata'] = 'NA';
                                        $routeDetails['atd'] = 'NA';
                                        $routeDetails['aht'] = 'NA';
                                    }
                                }
                            }
                            $routeDetails['isVisited'] = 0;
                            if ($routeDetails['ata'] != 'NA' || $routeDetails['atd'] != 'NA') {
                                $routeDetails['isVisited'] = 1;
                            }
                            $routeDetails['currentLocation'] = 'NA';
                            $routeDetails['lastupdated'] = 'NA';
                            if ($key2 == 0) {
                                $actualTime = $routeDetails['atd'];
                            } else {
                                $actualTime = $routeDetails['ata'];
                            }
                            if ($routeDetails['etd'] != 'NA' && $routeDetails['etd'] != '' && $actualTime != 'NA' && $actualTime != '') {
                                if (strtotime($routeDetails['etd']) < strtotime($actualTime)) {
                                    $timeText = $this->getHumanReadableTimeDifference($routeDetails['etd'], $actualTime);
                                    $routeDetails['remark'] = 'Delayed by ' . $timeText;
                                    $routeDetails['flag'] = '1';
                                } elseif (strtotime($routeDetails['etd']) > strtotime($actualTime)) {
                                    $timeText = $this->getHumanReadableTimeDifference($actualTime, $routeDetails['etd']);
                                    $routeDetails['remark'] = 'Early By ' . $timeText;
                                    $routeDetails['flag'] = '2';
                                } else {
                                    $routeDetails['remark'] = 'On Time';
                                    $routeDetails['flag'] = 'NA';
                                }
                            } else {
                                $routeDetails['remark'] = 'NA';
                                $routeDetails['flag'] = 'NA';
                            }
                            if (isset($routeDetails['remark']) && $routeDetails['remark'] != 'NA') {
                                $lastChkRemark = $routeDetails['remark'];
                            }
                            $returnResultData['routeDetails'][] = $routeDetails;
                        }
                    }
                    if (isset($returnResultData['routeDetails']) && !empty($returnResultData['routeDetails'])) {
                        $lastChkVisitId = NULL;
                        foreach ($returnResultData['routeDetails'] AS $routeKey => $route) {
                            if ($route['isVisited'] == '1') {
                                $lastChkVisitId = $routeKey;
                            }
                        }
                    }
                    if (isset($lastChkVisitId)) {
                        $lastChkVisitId = $lastChkVisitId + 1;
                    } else {
                        $lastChkVisitId = 0;
                    }
                    $currentLocationRemark = '';
                    if (isset($checkPointData[$lastChkVisitId])) {
                        $etaByAPI = $this->getEtaByApi($vehcileCurrentStatus[0]->devicelat, $vehcileCurrentStatus[0]->devicelong, $checkPointData[$lastChkVisitId]['cgeolat'], $checkPointData[$lastChkVisitId]['cgeolong']);
                        $timeText = '';
                        $timeText = $this->getHumanReadableTimeDifference($vehcileCurrentStatus[0]->lastupdated, date('Y-m-d H:i:s'));
                        if ($timeText != '') {
                            $timeText = ',(' . $timeText . ' ago)';
                        }
                        if (isset($etaByAPI['min']) && $etaByAPI['min'] != -1) {
                            $d = floor($etaByAPI['min'] / 1440);
                            $h = floor(($etaByAPI['min'] - $d * 1440) / 60);
                            $m = $etaByAPI['min'] - ($d * 1440) - ($h * 60);
                        }
                        $timeToReach = '';
                        if (isset($d) && $d > 0) {
                            $timeToReach = $d . ' Days ';
                        }
                        if (isset($h) && $h > 0) {
                            $timeToReach .= $h . ' Hours ';
                        }
                        if (isset($m) && $m > 0) {
                            $timeToReach .= $m . ' Minutes ';
                        }
                        if ($timeToReach != '') {
                            $timeToReach .= 'to reach';
                        }
                        $currentLocationRemark .= $lastChkRemark;
                        $currentLocationRemark .= PHP_EOL . 'Arriving ' . $checkPointData[$lastChkVisitId]['cname'] . '.';
                        $currentLocationRemark .= ' ' . $etaByAPI['km'] . 'km Remaining';
                        $currentLocationRemark .= PHP_EOL . $timeToReach . '' . $timeText;
                    }
                    if (count($returnResultData['routeDetails']) > $lastChkVisitId) {
                        $currLocationArray = array(0 => Array('checkpointid' => 'NA'
                                , 'checkpointname' => 'NA'
                                , 'kmFromLastCheckpoint' => 'NA'
                                , 'eta' => 'NA'
                                , 'etd' => 'NA'
                                , 'eht' => 'NA'
                                , 'ata' => 'NA'
                                , 'atd' => 'NA'
                                , 'aht' => 'NA'
                                , 'isVisited' => 'NA'
                                , 'currentLocation' => $vehicleCurrentLocation
                                , 'lastupdated' => $vehcileCurrentStatus[0]->lastupdated
                                , 'remark' => $currentLocationRemark
                                , 'flag' => $returnResultData['routeDetails'][$lastChkVisitId]['flag']
                        ));
                        array_splice($returnResultData['routeDetails'], $lastChkVisitId, 0, $currLocationArray);
                    } else {
                        $currLocationArray = Array('checkpointid' => 'NA'
                            , 'checkpointname' => 'NA'
                            , 'kmFromLastCheckpoint' => 'NA'
                            , 'eta' => 'NA'
                            , 'etd' => 'NA'
                            , 'eht' => 'NA'
                            , 'ata' => 'NA'
                            , 'atd' => 'NA'
                            , 'aht' => 'NA'
                            , 'isVisited' => 'NA'
                            , 'currentLocation' => $vehicleCurrentLocation
                            , 'lastupdated' => $vehcileCurrentStatus[0]->lastupdated
                            , 'remark' => $currentLocationRemark
                            , 'flag' => isset($returnResultData['routeDetails'][($lastChkVisitId - 1)]['flag']) ? $returnResultData['routeDetails'][($lastChkVisitId - 1)]['flag'] : ''
                        );
                        array_push($returnResultData['routeDetails'], $currLocationArray);
                    }
                } else {
                    foreach ($getTripDataForVehicle AS $tripKey => $tripValue) {
                        $CrossCheckDate = $tripValue['date'];
                        $originalEndDate = $endDate;
                        if (isset($getTripDataForVehicle[($tripKey+1)])) {
                            $endDate = date('Y-m-d', strtotime($getTripDataForVehicle[($tripKey+1)]['date']));
                        } else {
                            $endDate = $originalEndDate;
                        }
                        foreach ($checkPointData as $key2 => $value2) {
                            if ($key2 == 0) { // From here vehicle starts, there won't be halt time,estimated arrival, if started late then display started late
                                $totalDuration = 0;
                                $checkPointStoppageCount = 0;

                                $startDataTime = $tripValue['date'];
                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_arrival'] = '--';
                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_departure'] = /* date("H:i",strtotime($value2['etd'])); */$value2['etd'];
                                $startDataForStoppageCount = $tripValue['date'];
                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_halt_time'] = '--';
                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_arrival'] = '--';

                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_departure'] = date("d-m-Y H:i", strtotime($tripValue['date']));
                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_halt_time'] = '--';
                            } else {
                                if ($key2 == $checkPointDataCount - 1) { // At this checkpoint vehcile stops, there won't be halt time,estimated departure, if arrived late then late arrival
                                    $sqliteDataa = $this->getDataFromSqlite($path, $vehcileId['vehicleid'], $startDate, $endDate, $value2['checkpointid'], 0, '', $CrossCheckDate);

                                    if ($sqliteDataa != null) {
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_arrival'] = /* date("H:i",strtotime($value2['eta'])); */$value2['eta'];
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_departure'] = '--';
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_halt_time'] = '--';
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_arrival'] = date("d-m-Y H:i", strtotime($sqliteDataa[0]['date'])); //$sqliteDataa[0]['date'];
                                        //$returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']] ['actual_time_of_arrival']  = date("d-m-Y H:i", strtotime($sqliteDataa[0]['date']));
                                        $endDataForStoppageCount = $sqliteDataa[0]['date'];
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_departure'] = '--';
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_halt_time'] = '--';
                                        if ($value2['eta'] == '00:00:00') {
                                            //$returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']] ['summary']['Test'] = 'Here-0';
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['Remark'] = 'ETA is missing';
                                        }
                                        /*  else
                                          { */
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['tat'] = $tat;
                                        $actualTat = $this->getActalTat($startDataTime, $sqliteDataa[0]['date']);
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['atat'] = abs($actualTat);
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['Remark'] = $this->getRemarkFinal($actualTat, $tat);

                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['Test'] = 'Here-0';
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['tat'] = $tat;
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['atat'] = abs($actualTat);
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['Remark'] = $this->getRemarkFinal($actualTat, $tat);
                                        /*  $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']] ['summary']['checkPointStoppageCount'] = $checkPointStoppageCount; */
                                        $enrouteStoppageCount = $this->getStoppageCountFromSqlite($getUnitId, $startDataForStoppageCount, $endDataForStoppageCount, $vehcileId['vehicleid'], $vehcileId['vehicleno'], $checkPointDataCount);

                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['checkPointStoppageCount'] = $enrouteStoppageCount['enrouteStoppageCount']; //$this->getStoppageCountFromSqlite($getUnitId,$startDataForStoppageCount,$endDataForStoppageCount,$vehcileId['vehicleid'],$vehcileId['vehicleno'],$checkPointDataCount);//$this->getStoppageCountFromSqlite($getUnitId,$startDate,$endDate);
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['enrouteStoppageTime'] = $enrouteStoppageCount['enrouteStoppageTime'];
                                        if ($totalDuration == 0) {
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['totalDuration'] = '--';
                                        } else {
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['totalDuration'] = date("h:i:s", strtotime($totalDuration));
                                        }

                                        /*  } */
                                    } else {
                                        //echo"<br> In Else";
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_arrival'] = /* date("H:i",strtotime($value2['eta'])); */$value2['eta'];
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_departure'] = '--';
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_halt_time'] = '--';
                                        /* $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']] ['actual_time_of_arrival']  = $sqliteDataa['date'];  */
                                        if (isset($sqliteDataa['date']) && $sqliteDataa['date'] != '' && $sqliteDataa['date'] != null) {
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_arrival'] = date("d-m-Y H:i", strtotime($sqliteDataa['date']));
                                        } else {
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_arrival'] = '--';
                                        }

                                        $endDataForStoppageCount = $sqliteDataa['date'];
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_departure'] = '--';
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_halt_time'] = '--';

                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['Test'] = 'Here-1';
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['tat'] = $tat;
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['atat'] = '';
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['Remark'] = 'Trip is not completed yet';
                                        //$returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']] ['summary']['checkPointStoppageCount'] = $checkPointStoppageCount;
                                        $enrouteStoppageCount = $this->getStoppageCountFromSqlite($getUnitId, $startDataForStoppageCount, $endDataForStoppageCount, $vehcileId['vehicleid'], $vehcileId['vehicleno'], $checkPointDataCount);
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['checkPointStoppageCount'] = $enrouteStoppageCount['enrouteStoppageCount']; //$this->getStoppageCountFromSqlite($getUnitId,$startDataForStoppageCount,$endDataForStoppageCount,$vehcileId['vehicleid'],$vehcileId['vehicleno'],$checkPointDataCount);//$this->getStoppageCountFromSqlite($getUnitId,$startDate,$endDate);
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['enrouteStoppageTime'] = $enrouteStoppageCount['enrouteStoppageTime'];
                                        if ($totalDuration == 0) {
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['totalDuration'] = '--';
                                        } else {
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['summary']['totalDuration'] = date("h:i:s", strtotime($totalDuration));
                                        }
                                    }
                                } else {
                                    $sqliteData = $this->getDataFromSqlite($path, $vehcileId['vehicleid'], $startDate, $endDate, $value2['checkpointid'], 0, '', $CrossCheckDate);
                                    if ($sqliteData != null) {
                                        $sqliteData1 = $this->getDataFromSqlite($path, $vehcileId['vehicleid'], $startDate, $endDate, $value2['checkpointid'], 1, '', $CrossCheckDate);
                                        if ($sqliteData1 != null) {
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_arrival'] = /* date( "h:i", strtotime($value2['eta'])); */$value2['eta'];
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_departure'] = /* date( "h:i", strtotime($value2['etd'])); */$value2['etd'];
                                            $estimatedHaltTime = $this->getTimeDiff($value2['eta'], $value2['etd']);
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_halt_time'] = $estimatedHaltTime;

                                            if ($this->getActalTat($sqliteData[0]['date'], $CrossCheckDate) > $tat + 5) {
                                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_arrival'] = '--'; /* date("d-m-Y H:i",strtotime($sqliteData[0]['date'])); *//* date("d-m-Y H:i", strtotime($this->getActualTimeOfArriaval($vehcileId['vehicleid'],$startDate,$endDate,$value2['checkpointid'],$customerno))); */
                                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_departure'] = '--';

                                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_halt_time'] = '--'; /* date("d-m-Y H:i",strtotime($sqliteData1[0]['date'])); *//* date("d-m-Y H:i", strtotime($this->getActualTimeOfDeparture($vehcileId['vehicleid'],$startDate,$endDate,$value2['checkpointid'],$customerno))); */
                                            } else {
                                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_arrival'] = date("d-m-Y H:i", strtotime($sqliteData[0]['date'])); /* date("d-m-Y H:i", strtotime($this->getActualTimeOfArriaval($vehcileId['vehicleid'],$startDate,$endDate,$value2['checkpointid'],$customerno))); */
                                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_departure'] = date("d-m-Y H:i", strtotime($sqliteData1[0]['date'])); /* date("d-m-Y H:i", strtotime($this->getActualTimeOfDeparture($vehcileId['vehicleid'],$startDate,$endDate,$value2['checkpointid'],$customerno))); */
                                                //$haltTime = $this->getHaltTime($vehcileId['vehicleid'],$startDate,$endDate,$value2['checkpointid'],$customerno);
                                                $haltTime = $this->getTimeDiffNew(date("d-m-Y H:i", strtotime($sqliteData1[0]['date'])), date("d-m-Y H:i", strtotime($sqliteData[0]['date']))); //$this->getHaltTimeNew($sqliteData1[0]['date'],$sqliteData[0]['date']);
                                                if (!isset($haltTime)) {
                                                    $haltTime = 0;
                                                }
                                                $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_halt_time'] = $haltTime;
                                                $totalDuration = $totalDuration + strtotime($haltTime);
                                            }

                                            $checkPointStoppageCount++;
                                        } else {
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_arrival'] = /* date( "h:i", strtotime($value2['eta'])); */$value2['eta'];
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_departure'] = /* date( "h:i", strtotime($value2['etd'])); */$value2['etd'];
                                            $estimatedHaltTime = $this->getTimeDiff($value2['eta'], $value2['etd']);
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_halt_time'] = $estimatedHaltTime;
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_arrival'] = date("d-m-Y H:i", strtotime($this->getActualTimeOfArriaval($vehcileId['vehicleid'], $startDate, $endDate, $value2['checkpointid'], $customerno)));
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_departure'] = '--';
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_halt_time'] = '--';
                                            $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['Remark'] = '';
                                            $checkPointStoppageCount++;
                                            if (!isset($haltTime)) {
                                                $haltTime = 0;
                                            }
                                            $totalDuration = $totalDuration + strtotime($haltTime);
                                        }
                                    } else {
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_arrival'] = $value2['eta'];
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_time_of_departure'] = $value2['etd'];
                                        $estimatedHaltTime = $this->getTimeDiff($value2['eta'], $value2['etd']);
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['estimated_halt_time'] = $estimatedHaltTime;
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_arrival'] = '--';
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_time_of_departure'] = '--';
                                        $returnResultData[$tripKey][$vehcileId['vehicleno']][$value2['cname']]['actual_halt_time'] = '--';
                                    }
                                }
                            }
                        }
                        //echo"DAta interval :<pre>"; print_r($returnResultData);  exit();
                    }
                }
            } else {
                if ($isAPI && isset($checkPointData) && !empty($checkPointData)) {
                    foreach ($checkPointData as $key3 => $value3) {
                        $routeCheckpointArr = Array('checkpointid' => $value3['checkpointid']
                            , 'checkpointname' => $value3['cname']
                            , 'kmFromLastCheckpoint' => $value3['kmFromLastCheckpoint']
                            , 'eta' => 'NA'
                            , 'etd' => 'NA'
                            , 'eht' => 'NA'
                            , 'ata' => 'NA'
                            , 'atd' => 'NA'
                            , 'aht' => 'NA'
                            , 'isVisited' => 'NA'
                            , 'currentLocation' => 'NA'
                            , 'lastupdated' => 'NA'
                            , 'remark' => 'NA'
                            , 'flag' => 'NA'/* $returnResultData['routeDetails'][$lastChkVisitId]['flag'] */
                        );
                        $returnResultData['routeDetails'][] = $routeCheckpointArr;
                    }
                } else {
                    return null;
                }
            }
            /* New code ends here */

            return $returnResultData;
        } catch (PDOException $e) {
            echo "<br>Exception is: " . $e; //exit();
        }
    }

    /* FUnction for generating route wise live reports starts here */

    public function generateRouteWiseReportLive($vehcileId, $checkPointData, $customerno, $tat) {
        //echo"Vehicle Data is:<pre>"; print_r($vehcileId); //exit();
        //echo"Checkpoints data is: <pre>"; print_r($checkPointData);
        $startDateTime = '';
        $returnResultData = [];
        $checkPointId = '';
        /* $totalDuration = 0; */
        //echo"Check Point id string is: ".rtrim($checkPointId,','); exit();
        $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
        try {
            /* $checkPointStoppageCount=0; */
            $startDataTime = '';

            $checkPointDataCount = count($checkPointData);

            //$getTripDataForVehicle = $this->getTripDataForVehicle($path,$vehcileId['vehicleid'],$startDate,$endDate,$checkPointData[0]['checkpointid']);

            $getUnitId = $this->getUnitId($vehcileId['vehicleid']);
            //echo"<br>Unit no we got is: ".$getUnitId;
            // $tripCount = count($getTripDataForVehicle);
            $CrossCheckDate = '';
            //echo"<br>Trip count is: ".$tripCount; //exit();

            /* New code starts here */
            /* foreach($getTripDataForVehicle AS $tripKey=>$tripValue)
              { */
            //$CrossCheckDate = $tripValue['date'];
            $vehcileCurrentStatus = $this->getVehicleCurrentStatus($vehcileId['vehicleid']);
            //echo"<pre>"; print_r($checkPointData); exit();
            //echo"<pre>"; print_r($vehcileCurrentStatus); exit();
            $isVehicleLocationFound = 0;
            $isVehicleAtSource = 0;
            $isVehicleAtMiddleOfCheckPoint = 0;
            $vehicleCurrentLocation = $this->getVehicleCurrentVehicleLocation($vehcileCurrentStatus[0]->devicelat, $vehcileCurrentStatus[0]->devicelong);
            /*  echo"checkpoint data is: <pre>";
              print_r($checkPointData); exit(); */
            foreach ($checkPointData as $key2 => $value2) {
                $returnResultData[$vehcileId['vehicleid']][$vehcileId['vehicleno']]['currentLocation'] = $vehicleCurrentLocation;
                $returnResultData[$vehcileId['vehicleid']][$vehcileId['vehicleno']][$value2['cname']]['summary']['tat'] = $tat;
                /* New code starts here */
                if ($key2 == 0) {
                    if (($value2['checkpointid'] == @$vehcileCurrentStatus[0]->checkpointId) && (@$vehcileCurrentStatus[0]->chkpoint_status == 0) /*  && (@$vehcileCurrentStatus[0]->routeDirection==1) */) {
                        $returnResultData[$vehcileId['vehicleid']][$vehcileId['vehicleno']][$value2['cname']]['ETA'] = 'Vehicle is in current checkpoint';
                        $isVehicleLocationFound = 1;
                    } elseif (($value2['checkpointid'] == @$vehcileCurrentStatus[0]->checkpointId) && (@$vehcileCurrentStatus[0]->chkpoint_status == 1) /* && (@$vehcileCurrentStatus[0]->routeDirection==1) */) {
                        $returnResultData[$vehcileId['vehicleid']][$vehcileId['vehicleno']][$value2['cname']]['ETA'] = 'Visited';
                        $isVehicleLocationFound = 1;
                    } else {
                        $returnResultData[$vehcileId['vehicleid']][$vehcileId['vehicleno']][$value2['cname']]['ETA'] = 'Visited';
                        $isVehicleLocationFound = 0;
                    }
                } else {
                    if (($value2['checkpointid'] == @$vehcileCurrentStatus[0]->checkpointId) && (@$vehcileCurrentStatus[0]->chkpoint_status == 0) /* && (@$vehcileCurrentStatus[0]->routeDirection==1) */) {
                        $returnResultData[$vehcileId['vehicleid']][$vehcileId['vehicleno']][$value2['cname']]['ETA'] = 'Vehicle is in current checkpoint';
                        $isVehicleLocationFound = 1;
                    } elseif (($value2['checkpointid'] == @$vehcileCurrentStatus[0]->checkpointId) && (@$vehcileCurrentStatus[0]->chkpoint_status == 1) /* && (@$vehcileCurrentStatus[0]->routeDirection==1) */) {
                        $returnResultData[$vehcileId['vehicleid']][$vehcileId['vehicleno']][$value2['cname']]['ETA'] = 'Visited';
                        $isVehicleLocationFound = 1;
                    } else {
                        if ($isVehicleLocationFound == 1) {
                            $etaByAPI = $this->getEtaByApi($vehcileCurrentStatus[0]->devicelat, $vehcileCurrentStatus[0]->devicelong, $value2['cgeolat'], $value2['cgeolong']);
                            $returnResultData[$vehcileId['vehicleid']][$vehcileId['vehicleno']][$value2['cname']]['ETA'] = $etaByAPI['datetime'];
                            $isVehicleLocationFound = 1;
                        } else {
                            $returnResultData[$vehcileId['vehicleid']][$vehcileId['vehicleno']][$value2['cname']]['ETA'] = 'Visited';
                        }
                    }
                }
                /* New code ends here */
            }

            /* New code ends here */

            return $returnResultData;
        } catch (PDOException $e) {
            echo "<br>Exception is: " . $e; //exit();
        }
    }

    /* FUnction for generating route wise live reports ends here */

    public function getTimeDiff($actualTime, $expectedTime) {
        $hrsMIn = 0;
        $actualTime = new DateTime($actualTime);
        $expectedTime = new DateTime($expectedTime);
        $interval = $expectedTime->diff($actualTime);
        $hrs = $interval->h;
        $hrsMIn = $hrs . ":" . $interval->i;
        return $hrsMIn;

        // return round((strtotime($actualTime) - strtotime($expectedTime))/3600, 1);
    }

    public function getTimeDiffNew($actualTime, $expectedTime) {
        //return round((strtotime($actualTime) - strtotime($expectedTime))/3600, 2);
        $datetime1 = new DateTime($expectedTime);
        $datetime2 = new DateTime($actualTime);
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%h') . ":" . $interval->format('%i');
        //return round(abs(strtotime($expectedTime) - strtotime($actualTime)) / 60,2);
    }

    public function getRemark($actualtime, $estimatedTime) {
        $actualTimeArray = explode(" ", $actualtime);
        $actualTime = $actualTimeArray[1];
        return $this->timeCompare($actualTime, $estimatedTime);
    }

    public function getRemarkFinal($actualTat, $tat) {
        if (abs($actualTat) > $tat) {
            /* return 'Late by '.abs(abs($actualTat)-$tat).' HRS'; */
            return 'Delayed';
        } elseif (abs($actualTat) == $tat) {
            return 'Ontime';
        } else {
            return 'Early';
        }
    }

    public function timeCompare($time1, $time2) {
        $strToTime1 = strtotime($time1);
        $strToTime2 = strtotime($time2);
        $timeDifference = $strToTime2 - $strToTime1;
        if ($timeDifference < 0) {
            return 'Late by ' . $this->getTimeDiff($time1, $time2);
        } elseif ($timeDifference == 0) {
            return 'On Time';
        } else {
            return 'Early by ' . $this->getTimeDiff($time1, $time2);
        }
    }

    public function getActualTimeOfArriaval($vehcileId, $startDate, $endDate, $checkpointid, $customerno) {
        $path = "sqlite:" . RELATIVE_PATH_DOTS . "customer/$customerno/reports/chkreport.sqlite";
        if (isset($startDate) && isset($endDate)) {
            $Query = "  select  * 
                        from    V" . $vehcileId . " 
                        WHERE   (chkid = " . $checkpointid . ") 
                        AND     (`date` BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') 
                        AND     (status=0) 
                        ORDER BY `date` desc";
        } else {
            $Query = "  select  * 
                        from    V" . $vehcileId . " 
                        WHERE   (chkid = " . $checkpointid . ") 
                        AND     (status=0) 
                        ORDER BY `date` desc";
        }
        $db = new PDO($path);
        $sth = $db->prepare($Query);
        $sth->execute();
        $result = $sth->fetchAll();
        if (count($result) > 0) {
            return $result[0]['date'];
        } else {
            return '--';
        }
    }

    public function getActualTimeOfDeparture($vehcileId, $startDate, $endDate, $checkpointid, $customerno) {
        $path = "sqlite:" . RELATIVE_PATH_DOTS . "customer/$customerno/reports/chkreport.sqlite";
        $Query = "select * from V" . $vehcileId . " WHERE (chkid = " . $checkpointid . ") AND (date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') AND (status=1) order by date desc";
        $db = new PDO($path);
        $sth = $db->prepare($Query);
        $sth->execute();
        $result = $sth->fetchAll();
        if (count($result) > 0) {
            return $result[0]['date'];
        } else {
            return '--';
        }
    }

    public function getHaltTime($vehcileId, $startDate, $endDate, $checkpointid, $customerno) {
        $path = "sqlite:" . RELATIVE_PATH_DOTS . "customer/$customerno/reports/chkreport.sqlite";
        $Query = "select * from V" . $vehcileId . " WHERE (chkid = " . $checkpointid . ") AND (date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') AND (status=0)";
        $db = new PDO($path);
        $sth = $db->prepare($Query);
        $sth->execute();
        $result = $sth->fetchAll();
        if (count($result) > 0) {
            $actualTimeOfArrivalArray = explode(" ", $result[0]['date']);
            $actualTimeOfArrival = $actualTimeOfArrivalArray[1];
        } else {
            $actualTimeOfArrival = '--';
        }

        $Query2 = "select * from V" . $vehcileId . " WHERE (chkid = " . $checkpointid . ") AND (date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') AND (status=1)";
        $sth2 = $db->prepare($Query2);
        $sth2->execute();
        $result2 = $sth2->fetchAll();

        if (count($result2) > 0) {
            $actualTimeOfDepartureArray = explode(" ", $result2[0]['date']);
            $actualTimeOfDeparture = $actualTimeOfDepartureArray[1];
        } else {
            $actualTimeOfDeparture = '--';
        }

        if ($actualTimeOfArrival != '--' && $actualTimeOfDeparture != '--') {
            return $this->getTimeDiff($actualTimeOfArrival, $actualTimeOfDeparture);
        } else {
            return '--';
        }
    }

    public function getDataFromSqlite($path, $vehcileId, $startDate, $endDate, $checkPointId, $status, $orderByClause, $compareDate) {
        $db = new PDO($path);
        /* $query = "select * from V" . $vehcileId. " WHERE (date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') AND (chkid =".$checkPointId.") AND (status='".$status."') AND (date>='".$compareDate."')".$orderByClause." order by date asc LIMIT 1"; */
        if (isset($startDate) && isset($endDate)) {
            $query = "  select  *
                        from    V" . $vehcileId . "
                        WHERE   (date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')
                        AND     (chkid =" . $checkPointId . ")
                        AND     (status='" . $status . "')
                        AND     (date>='" . $compareDate . "')
                        order by date ASC
                        LIMIT 1";
        } else {
            $query = "  select  *
                        from    V" . $vehcileId . "
                        WHERE   (chkid =" . $checkPointId . ")
                        AND     (status='" . $status . "')
                        AND     (date>='" . $compareDate . "')
                        order by date ASC
                        LIMIT 1";
        }
        //echo"<br>Query is: ".$query;
        /* if($status==null)
          {
          $query = "select * from V" . $vehcileId. " WHERE (date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') AND (chkid =".$checkPointId.")";
          }
          else
          {
          $query = "select * from V" . $vehcileId. " WHERE (date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') AND (chkid =".$checkPointId.") AND (status='".$status."')";
          } */
//        echo"<br>Query is:- ".$query; exit(); 
        $sth = $db->prepare($query);
        $sth->execute();
        $result = $sth->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return null;
        }
    }

    public function getTripDataForVehicle($path, $vehicleid, $startDate, $endDate, $checkpointid, $tat, $isAPI) {
        $db = new PDO($path);
        if (isset($startDate) && isset($endDate)) {
            $query = "  SELECT  chkrepid
                                , chkid
                                , chktype
                                , status
                                , `date`
                        FROM    V" . $vehicleid . "
                        WHERE   (date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')
                        AND     chkid IN (" . $checkpointid . ")
                        AND     (status = '1')
                        ORDER BY date ASC";
        } else {
            $query = "  SELECT  chkrepid
                                , chkid
                                , chktype
                                , status
                                , `date`
                        FROM    V" . $vehicleid . "
                        WHERE   chkid IN (" . $checkpointid . ")
                        AND     (status = '1')
                        ORDER BY date ASC";
        }


        $sth = $db->prepare($query);
        $sth->execute();
        $result = $sth->fetchAll();
        $resultArray = [];
        $tempStatus = '';
        $tempDate = '';
//        prettyPrint($result); die();
        if (count($result) > 0) {
            foreach ($result AS $key => $value) {
                if ($key == 0) {
                    $tempStatus = $value['status'];
                    $tempDate = date_format(date_create($value['date']), "Y-m-d");
                    $resultArray[$key]['chkrepid'] = $value['chkrepid'];
                    $resultArray[$key]['chkid'] = $value['chkid'];
                    $resultArray[$key]['chktype'] = $value['chktype'];
                    $resultArray[$key]['status'] = $value['status'];
                    $resultArray[$key]['date'] = $value['date'];
                } else {
                    if (($tempStatus != $value['status']) /* && ($tempDate!=date_format(date_create($value['date']),"Y-m-d")) */) {
                        $tempStatus = $value['status'];
                        $tempDate = date_format(date_create($value['date']), "Y-m-d");

                        $resultArray[$key]['chkrepid'] = $value['chkrepid'];
                        $resultArray[$key]['chkid'] = $value['chkid'];
                        $resultArray[$key]['chktype'] = $value['chktype'];
                        $resultArray[$key]['status'] = $value['status'];
                        $resultArray[$key]['date'] = $value['date'];
                    } else {
                        if ($tempDate != date_format(date_create($value['date']), "Y-m-d")) {
                            //echo"<br><br>3";
                            //echo"<br>Temp Status is: ".$tempStatus.' and current status is: '.$value['status']." AND temp date is: ".$tempDate." curent loop date: ".date_format(date_create($value['date']),"Y-m-d")."<br><br>";
                            $tempStatus = $value['status'];
                            $tempDate = date_format(date_create($value['date']), "Y-m-d");

                            $resultArray[$key]['chkrepid'] = $value['chkrepid'];
                            $resultArray[$key]['chkid'] = $value['chkid'];
                            $resultArray[$key]['chktype'] = $value['chktype'];
                            $resultArray[$key]['status'] = $value['status'];
                            $resultArray[$key]['date'] = $value['date'];
                        }
                    }
                }
            }
            if ($isAPI) {
                return $resultArray;
            }
            //return $resultArray;
            // Removing trips where tat is less than provided tat
            $finalArray = [];
            $roundTat = $tat * 2;
            /* $resultArrayCount = count($resultArray); */
            $resultArrayCount = count($result);
            foreach ($result AS $key => $value) {
                if ($key < $resultArrayCount - 1) {
                    if (@$this->getActalTat($result[$key + 1]['date'], $value['date']) >= $roundTat) {
                        $finalArray[$key]['chkrepid'] = $value['chkrepid'];
                        $finalArray[$key]['chkid'] = $value['chkid'];
                        $finalArray[$key]['chktype'] = $value['chktype'];
                        $finalArray[$key]['status'] = $value['status'];
                        $finalArray[$key]['date'] = $value['date'];
                    } else {
                        continue;
                    }
                }
                if ($key == $resultArrayCount - 1) {
                    $finalArray[$key]['chkrepid'] = $value['chkrepid'];
                    $finalArray[$key]['chkid'] = $value['chkid'];
                    $finalArray[$key]['chktype'] = $value['chktype'];
                    $finalArray[$key]['status'] = $value['status'];
                    $finalArray[$key]['date'] = $value['date'];
                }
            }

            //echo"Data got is: <pre>"; print_r($finalArray); //exit();
            return $finalArray;
        } else {
            return false;
        }
    }

    public function getVehicleCheckpointVisitedTime($path, $vehicleid, $checkpointid, $lastCheckpointVisitDateTime = NULL) {
        $todaysDate = date('Y-m-d H:i:s');
        if (isset($lastCheckpointVisitDateTime)) {
            $query = "  SELECT  `date`
                        FROM    V" . $vehicleid . "
                        WHERE     (chkid =" . $checkpointid . ")
                        AND     (status = '1')
                        AND     `date` > $lastCheckpointVisitDateTime
                        AND     `date` > $todaysDate
                        ORDER BY date DESC
                        LIMIT 1";
        } else {
            $query = "  SELECT  `date`
                        FROM    V" . $vehicleid . "
                        WHERE     (chkid =" . $checkpointid . ")
                        AND     `date` > $todaysDate
                        AND     (status = '1')
                        ORDER BY date DESC
                        LIMIT 1";
        }
        try {
            $db = new PDO($path);
            $sth = $db->prepare($query);
            if ($sth) {
                $sth->execute();
                $result = $sth->fetchAll();
                $resultArray = [];
                $tempStatus = '';
                $tempDate = '';
                if (count($result) > 0) {
                    foreach ($result AS $key => $value) {
                        return $value['date'];
                    }
                } else {
                    return FALSE;
                }
            }
        } catch (PDOException $e) {
            echo "<br>Exception is: " . $e;
            exit();
        }
    }

    public function generateResultInOrderOfCheckPoints($path, $vehcileId, $startDate, $endDate, $checkPointData) {
        $returnDataArray = [];
        $db = new PDO($path);
        foreach ($checkPointData AS $key => $value) {
            $Query = "select * from V" . $vehcileId . " WHERE date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND chkid =" . $value['checkpointid'];
            $sth = $db->prepare($Query);
            $sth->execute();
            $result = $sth->fetchAll();
            $returnDataArray[] = $result;
            /* $checkPointId.= $value['checkpointid'].','; */
        }
        /* echo"Data in ordered is: <pre>";
          print_r($returnDataArray);
          exit(); */
        return $returnDataArray;
    }

    public function getActalTat($startDataTime, $endDateTime) {
        //echo"<br>startDataTime: ".$startDataTime." endDateTime: ".$endDateTime;
        $hourdiff = round((strtotime($startDataTime) - strtotime($endDateTime)) / 3600, 1);
        return floor($hourdiff);

        /*  $format = 'Y-m-d H:i:s';
          $date1 = DateTime::createFromFormat($format, $startDataTime);
          $date2 = DateTime::createFromFormat($format, $endDateTime);

          //echo "Format: $format; " . $date->format('Y-m-d') . "n";
          if($date1->format('Y-m-d') > $date2->format('Y-m-d'))
          {
          $hourdiff = round((strtotime($startDataTime) - strtotime($endDateTime))/3600, 1);
          return floor($hourdiff);
          }
          else
          {
          return 'Invalidate Dates';
          } */
    }

    public function getStoppageCountFromSqlite($unitno, $startDate, $endDate, $vehicleid, $vehicleno, $checkPointDataCount) {
        $customerno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : 0;
        $chkManager = new CheckpointManager($customerno);
        $arrCheckpoints = $chkManager->getcheckpointsforcustomer();
        //echo"Start Date is: ".$startDate." and end date is: ".$endDate;

        /* Seperating time from datetime starts here */
        $startDateTime = new DateTime($startDate);
        $startTime = $startDateTime->format('H:i');

        if ($endDate != '' || $endDate != null) {
            $endDateTime = new DateTime($endDate);
            $endTime = $endDateTime->format('H:i');
        } else {
            $endTime = '23:59';
        }

        /* Seperating time from datetime ends here */

        $totaldays = $this->gendays($startDate, $endDate);
        $deviceid = $this->getDeviceIdFromUnitNumber($unitno);
        $days = array();
//        $customerno = $_SESSION['customerno'];
        // $unitno = getunitno($deviceid);
        $count = count($totaldays);
        $endelement = end($totaldays);
        $totaldaysArray = array_values($totaldays);
        $firstelement = array_shift($totaldaysArray);
        /* $Shour = '00:00';
          $Ehour = '23:59'; */
        $Shour = $startTime;
        $Ehour = $endTime;
        $holdtime = 60;
        if (isset($totaldays)) {
            foreach ($totaldays as $userdate) {
                if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        //Date Check Operations
                        $data = null;
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            $location = "sqlite:" . $location;
                            $data = $this->getstoppage_fromsqlite($location, $deviceid, 60, $Shour, null, $userdate, $arrCheckpoints);
                        }
                        if ($data != null && count($data) > 0) {
                            $days = array_merge($days, $data);
                        }
                    }
                } elseif ($count > 1 && $userdate == $endelement) {
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        //Date Check Operations
                        $data = null;
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            $location = "sqlite:" . $location;
                            $data = $this->getstoppage_fromsqlite($location, $deviceid, $holdtime, null, $Ehour, $userdate, $arrCheckpoints);
                        }
                        if ($data != null && count($data) > 0) {
                            $days = array_merge($days, $data);
                        }
                    }
                } elseif ($count == 1) {
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        //Date Check Operations
                        $data = null;
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            $location = "sqlite:" . $location;
                            $data = $this->getstoppage_fromsqlite($location, $deviceid, $holdtime, $Shour, $Ehour, $userdate, $arrCheckpoints);
                        }
                        if ($data != null && count($data) > 0) {
                            $days = array_merge($days, $data);
                        }
                    } else {
                        echo 'File Does not exist';
                    }
                } else {
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        //Date Check Operations
                        $data = null;
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        if (file_exists($location)) {
                            $location = "sqlite:" . $location;
                            $data = $this->getstoppage_fromsqlite($location, $deviceid, $holdtime, null, null, $userdate, $arrCheckpoints);
                        }
                        if ($data != null && count($data) > 0) {
                            $days = array_merge($days, $data);
                        }
                    }
                }
            }
        }

        if (count($days) == 0) {
            $time = '0';
        } else {
            /* $enrouteStoppageTime = strtotime('00:00:00'); */
            $enrouteStoppageTime = 0;
            foreach ($days as $key => $value) {
                $enrouteStoppageTime = $value['stoppageEnrouteTime'] + $enrouteStoppageTime;
            }

            /* $time = date( "h:i", $enrouteStoppageTime ) ;  */
            $minutesdiff = floor($enrouteStoppageTime / 60);
            if (floor($minutesdiff / 60) < 10) {
                $hourdiff = "0" . floor($minutesdiff / 60);
            } else {
                $hourdiff = floor($minutesdiff / 60);
            }
            if (floor($minutesdiff % 60) < 10) {
                $hourremainder = "0" . floor($minutesdiff % 60);
            } else {
                $hourremainder = floor($minutesdiff % 60);
            }
            $minutesdiff = $hourdiff . ":" . $hourremainder;
            $time = $minutesdiff;
        }

        $convertedStartDate = date('d-m-Y', strtotime($startDate));
        $convertedEndDate = date('d-m-Y', strtotime($endDate));

        /*  if(@count($days)-@count($checkPointDataCount) <=0)
          {
          $countDays = 0;
          }
          else
          {
          $countDays = @count($days)-@count($checkPointDataCount);
          } */

        $htmlContent['enrouteStoppageCount'] = '<a href="http://speed.elixiatech.com/modules/reports/reports.php?id=15&vehicleid=' . $vehicleid . '&vehicleno=' . $vehicleno . '&deviceid=' . $deviceid . '&startDate=' . $convertedStartDate . '&endDate=' . $convertedEndDate . '&Shour=' . $Shour . '&Ehour=' . $Ehour . '&interval=60" target="_blank">' . /* $countDays */count($days) . '</a>';

        $htmlContent['enrouteStoppageTime'] = $time;
        return $htmlContent;
    }

    public function getstoppage_fromsqlite($location, $deviceid, $holdtime, $Shour, $Ehour, $userdate, $arrCheckpoints) {
        $devices = array();
        $query = "SELECT devicehistory.lastupdated, vehiclehistory.odometer, vehiclehistory.vehicleno, devicehistory.devicelat, vehiclehistory.vehicleid,
            devicehistory.devicelong, unithistory.unitno
            from devicehistory
            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F' AND devicehistory.devicelat <> '0.000000' AND devicehistory.devicelong <> '0.000000'";

        if ($Shour != null) {
            $query .= " AND devicehistory.lastupdated > '$userdate $Shour'";
        }
        if ($Ehour != null) {
            $query .= " AND devicehistory.lastupdated < '$userdate $Ehour'";
        }
        $query .= "  ORDER BY devicehistory.lastupdated ASC";
        try {
            $database = new PDO($location);
            $result = $database->query($query);
            if (isset($result) && $result != "") {
                $lastupdated = "";
                $lastodometer = "";
                $pusharray = 1;
                foreach ($result as $row) {
                    if ($lastodometer == "") {
                        $lastodometer = $row["odometer"];
                        $lastupdated = $row['lastupdated'];
                        $device = new stdClass();
                        $device->vehicleid = $row['vehicleid'];
                        $device->vehicleno = $row['vehicleno'];
                        $device->starttime = $row['lastupdated'];
                        $device->deviceid = $row['vehicleid'];
                        $device->customerno = $_SESSION['customerno'];
                        $device->lat = $row['devicelat'];
                        $device->lng = $row['devicelong'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $chk = $this->getChkRealy($row['devicelat'], $row['devicelong'], $arrCheckpoints);
                        if (!empty($chk)) {
                            $device->isCheckPointFound = 1;
                        } else {
                            $device->isCheckPointFound = 0;
                        }
                    }
                    /* Condition For Odometer Reset */
                    if ($row["odometer"] < $lastodometer) {
                        $max = $this->GetOdometerMax($row['lastupdated'], $row["unitno"]);
                        $row["odometer"] = $max + $row["odometer"];
                    }
                    if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > $holdtime && $row["odometer"] - $lastodometer < 100 && $pusharray == 1) {
                        $device->deviceid = $row['vehicleid'];
                        $device->customerno = $_SESSION['customerno'];
                        $lastodometer = $row["odometer"];
                        $lastupdated = $row['lastupdated'];
                        $device->lat = $row['devicelat'];
                        $device->lng = $row['devicelong'];
                        $chk = $this->getChkRealy($row['devicelat'], $row['devicelong'], $arrCheckpoints);
                        if (!empty($chk)) {
                            $device->isCheckPointFound = 1;
                        } else {
                            $device->isCheckPointFound = 0;
                        }
                        //$device->reason = getStoppageReasonPerLoc($device);
                        $pusharray = 0;
                    } else {
                        if (($row["odometer"] - $lastodometer) > 25) {
                            if ($pusharray == 0) {
                                $device->endtime = $row['lastupdated'];
                                $devices[] = $device;
                            }
                            $lastodometer = $row["odometer"];
                            $lastupdated = $row['lastupdated'];
                            $device = new stdClass();
                            $device->vehicleid = $row['vehicleid'];
                            $device->vehicleno = $row['vehicleno'];
                            $device->starttime = $row['lastupdated'];
                            $device->deviceid = $row['vehicleid'];
                            $device->customerno = $_SESSION['customerno'];
                            $device->devicelat = $row['devicelat'];
                            $device->devicelong = $row['devicelong'];
                            $device->lat = $row['devicelat'];
                            $device->lng = $row['devicelong'];
                            $chk = $this->getChkRealy($row['devicelat'], $row['devicelong'], $arrCheckpoints);
                            if (!empty($chk)) {
                                $device->isCheckPointFound = 1;
                            } else {
                                $device->isCheckPointFound = 0;
                            }
                            //$device->reason = getStoppageReasonPerLoc($device);
                            $pusharray = 1;
                        }
                    }
                }
                if ($pusharray == 0) {
                    $device->endtime = $row['lastupdated'];
                    $device->deviceid = $row['vehicleid'];
                    $devices[] = $device;
                }
            }
        } catch (PDOException $e) {
            die($e);
        }

        /* Returning devices with  isCheckPointFound=0 */
        $newDevicesArray = [];
        $stoppageEnrouteTime = 0;
        foreach ($devices as $key => $value) {
            if ($value->isCheckPointFound == 0) {
                $secdiff = strtotime($value->endtime) - strtotime($value->starttime);
                $minutesdiff = floor($secdiff / 60);
                if (floor($minutesdiff / 60) < 10) {
                    $hourdiff = "0" . floor($minutesdiff / 60);
                } else {
                    $hourdiff = floor($minutesdiff / 60);
                }
                if (floor($minutesdiff % 60) < 10) {
                    $hourremainder = "0" . floor($minutesdiff % 60);
                } else {
                    $hourremainder = floor($minutesdiff % 60);
                }
                $minutesdiff = $hourdiff . ":" . $hourremainder;

                //$stoppageEnrouteTime = $secdiff + $stoppageEnrouteTime;

                $newDevicesArray[$key]['vehicleid'] = $value->vehicleid;
                $newDevicesArray[$key]['vehicleno'] = $value->vehicleno;
                $newDevicesArray[$key]['starttime'] = $value->starttime;
                $newDevicesArray[$key]['deviceid'] = $value->deviceid;
                $newDevicesArray[$key]['customerno'] = $value->customerno;
                $newDevicesArray[$key]['devicelat'] = $value->devicelat;
                $newDevicesArray[$key]['devicelong'] = $value->devicelong;
                $newDevicesArray[$key]['lat'] = $value->lat;
                $newDevicesArray[$key]['lng'] = $value->lng;
                $newDevicesArray[$key]['isCheckPointFound'] = $value->isCheckPointFound;
                $newDevicesArray[$key]['endtime'] = $value->endtime;
                $newDevicesArray[$key]['stoppageEnrouteTime'] = $secdiff;
            }
        }

        //return $devices;
        //echo"Data is: <pre>"; print_r($newDevicesArray);
        return $newDevicesArray;
    }

    public function getUnitId($vehcileId, $customerno = NULL) {
        $custno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : $customerno;
        $um = new UnitManager($custno);
        $unitno = $um->getUnitId($vehcileId);
        return $unitno;
    }

    public function gendays($STdate, $EDdate) {
        $TOTALDAYS = Array();
        $STdate = date("Y-m-d", strtotime($STdate));
        $EDdate = date("Y-m-d", strtotime($EDdate));
        while (strtotime($STdate) <= strtotime($EDdate)) {
            $TOTALDAYS[] = $STdate;
            $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
        }
        return $TOTALDAYS;
    }

    public function getDeviceIdFromUnitNumber($unitno) {
        $custno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : 0;
        $um = new UnitManager($custno);
        $deviceId = $um->getDeviceIdFromUnitNumber($unitno);
        return $deviceId;
    }

    public function GetOdometerMax($date, $unitno) {
        $sqlitedate = date('Y-m-d', strtotime($date));
        $customerno = $_SESSION['customerno'];

        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$sqlitedate.sqlite";
        if ($_SESSION['role_modal'] == 'elixir') {
            //echo $location;
        }
        $ODOMETER = 0;
        if (file_exists($location)) {
            //echo"exists for: ".$location."<br>";
            try {
                $path = "sqlite:$location";
                $db = new PDO($path);
                $query = "SELECT max(odometer) as odometerm from vehiclehistory where lastupdated < '" . $date . "' limit 1";
                //echo $query."<br/>";
                $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
                $ODOMETER = $result[0]['odometerm'];
            } catch (Exception $e) {
                //echo"not exists for: ".$location."<br>";
            }
            /* $path = "sqlite:$location";
              $db = new PDO($path);
              $query = "SELECT max(odometer) as odometerm from vehiclehistory where lastupdated < '" . $date . "' limit 1";
              echo $query."<br/>";
              $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
              $ODOMETER = $result[0]['odometerm']; */
        } else {
            /* echo"not exists for: ".$location."<br>"; */
        }
        return $ODOMETER;
    }

    public function getVehicleCurrentStatus($vehcileId) {
        $custno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : 0;
        $vehicleManagerObject = new VehicleManager($custno);
        $result = $vehicleManagerObject->getVehicleCurrentStatus($vehcileId);
        return json_decode(json_encode($result, true));
    }

    public function getVehicleCurrentVehicleLocation($lat, $lng) {
        $address = NULL;
        $customerno = (!isset($this->_Customerno)) ? $_SESSION['customerno'] : $this->_Customerno;
        $GeoCoder_Obj = new GeoCoder($customerno);
        $address = $GeoCoder_Obj->get_location_bylatlong($lat, $lng);
        return $address;
    }

    public function getEtaByApi($deviceLat, $deviceLng, $destinationLat, $destinationLng) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => BASE_PATH . "/modules/api/vts/api.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "action=getRouteDistanceAndTime&jsonreq=%7B%20%22userkey%22%20%3A%20%2215bba9b7e1c061a49774b052cdddb2008b9be7a9%22%2C%22origin%22%20%3A%20%7B%22lat%22%3A%22" . $deviceLat . "%22%2C%22lng%22%3A%22" . $deviceLng . "%22%7D%2C%22destination%22%20%3A%7B%22lat%22%3A%22" . $destinationLat . "%22%2C%22lng%22%3A%22" . $destinationLng . "%22%7D%7D&undefined=",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "Postman-Token: 44ca2c7f-daa0-4b96-937a-36e75f887ce6",
                "cache-control: no-cache"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            //echo "cURL Error #:" . $err;
            return 'API is down';
        } else {
            $decodedJsonData = json_decode($response, true);
            if (isset($decodedJsonData['Result']['min']) && $decodedJsonData['Result']['min'] != '-1') {
                $time = new DateTime();
                $time->add(new DateInterval('PT' . $decodedJsonData['Result']['min'] . 'M'));
                $time_stamp['datetime'] = $time->format('Y-m-d H:i:s');
                $time_stamp['km'] = isset($decodedJsonData['Result']['km']) ? $decodedJsonData['Result']['km'] : NULL;
                $time_stamp['min'] = isset($decodedJsonData['Result']['min']) ? $decodedJsonData['Result']['min'] : NULL;
                return $time_stamp;
            } else {
                return NULL;
            }
        }
    }

    public function convertMinutesToHours($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    /* Function to check whether the vehicle is in checkpoint or not starts here */

    public function getChkRealy($lat, $long, $arrCheckpoints) {
        $chks = array();
        foreach ($arrCheckpoints as $checkpoint) {
            $distance = $this->calculate($lat, $long, $checkpoint->cgeolat, $checkpoint->cgeolong); //echo "<br/>";
            if ($distance < $checkpoint->crad) {
                $chk = new stdClass();
                $chk->cgeolat = $checkpoint->cgeolat;
                $chk->cgeolong = $checkpoint->cgeolong;
                $chk->cname = $checkpoint->cname;
                $chk->crad = $checkpoint->crad;
                $chks[] = $chk;
            }
        }
        return $chks;
    }

    public function calculate($devicelat, $devicelong, $cgeolat, $cgeolong) {
        //Earth's mean radius in km
        $ERadius = 6371;
        //Difference between devicelatlong and checkpointlatlong
        $diffLat = $this->rad($cgeolat - $devicelat);
        $diffLong = $this->rad($cgeolong - $devicelong);
        //Converting between devicelatlong to radians
        $devlat_rad = $this->rad($devicelat);
        $devlong_rad = $this->rad($cgeolat);
        //Calculation Using Haversine's formula
        //Applying Haversine formula
        $a = sin($diffLat / 2) * sin($diffLat / 2) + cos($devlat_rad) * cos($devlong_rad) * sin($diffLong / 2) * sin($diffLong / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        //Distance
        $diffdist = $ERadius * $c;
        return $diffdist;
    }

    public function rad($x) {
        return $x * pi() / 180;
    }

    /* Function to check whether the vehicle is in chekpoint or not ends here */

    public function getCheckPointWiseDataForReverseRoute($customerNo, $route) {
        $QUERY = 'CALL fetch_data_for_route_wise_report_checkpoints(' . $route . ',' . $this->_Customerno . ')';
        /* $QUERY = 'CALL '.speedConstants::SP_FETCH_DATA_FOR_ROUTE_WISE_REPORT.'('.$route.','.$this->_Customerno.')';  */
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $dataArray = [];
            $allData = $this->_databaseManager->get_recordSet();
            return $allData;
        }
    }

    public function getRouteByName($routeName) {
        $arrRoute = array();
        $Query = "SELECT routeId,routename FROM route WHERE customerno=%d AND routename='%s' and isdeleted = 0";
        $SQL = sprintf($Query, $this->_Customerno, $routeName);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $arrRoute = $this->_databaseManager->get_recordSet();
        }
        return $arrRoute;
    }

    public function routeMapping($objRoute) {
        $date = new Date();
        $today = $date->MySQLNow();
        //$routeid, $routename, $routearray, $vehiclearray, $userid, $chkDetails, $routeTat = null, $routeType = null
        /* Get all route checkpoints */
        $checkpoint_array = $this->get_all_checkpointid_forroute($objRoute->routeId);
        /* Get all vehicles associated with route */
        $vehicles_Array = $this->getvehiclesforroute($objRoute->routeId);
        /* Delete from routeman on basis of routeid */
        $Query = "DELETE FROM routeman WHERE routeid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($objRoute->routeId), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        /* Delete vehicle mapping against the route */
        $Query = "DELETE FROM vehiclerouteman WHERE  routeid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($objRoute->routeId), $this->_Customerno);

        $Query = "DELETE FROM vehiclerouteman WHERE  vehicleid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($objRoute->vehicleId), $this->_Customerno);

        $this->_databaseManager->executeQuery($SQL);
        if (is_array($vehicles_Array) && count($vehicles_Array) > 0 && !empty($vehicles_Array)) {
            foreach ($vehicles_Array as $vehicles) {
                if ($vehicles != '') {
                    foreach ($checkpoint_array as $checkpoint) {
                        if ($checkpoint != '') {
                            $CheckQuery = "UPDATE checkpointmanage SET isdeleted=1 WHERE checkpointid=%d AND vehicleid=%d AND customerno=%d AND isdeleted=0 LIMIT 1";
                            $checkpointsQuery = sprintf($CheckQuery, Sanitise::Long($checkpoint->checkpointid), Sanitise::Long($vehicles->vehicleid), $this->_Customerno);
                            $this->_databaseManager->executeQuery($checkpointsQuery);
                        }
                    }
                }
            }
        }
        $Query_1 = "INSERT INTO routeman (`routeid`,`checkpointid`,`sequence`,`eta`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','%s','%d','%d','0','%s')";
        //prettyPrint($objRoute->routeDetails);
        foreach ($objRoute->routeDetails as $data) {
            $SQL_1 = sprintf($Query_1, Sanitise::Long($objRoute->routeId), Sanitise::Long($data->chkId), Sanitise::Long($data->sequence), Sanitise::String($data->etaTime), $this->_Customerno, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
            $this->_databaseManager->executeQuery($SQL_1);
            $CheckQuery = "SELECT vehicleid FROM `checkpointmanage` WHERE checkpointid=%d AND vehicleid=%d AND customerno=%d AND isdeleted=0";
            $checkpointsQuery = sprintf($CheckQuery, Sanitise::Long($data->chkId), Sanitise::Long($objRoute->vehicleId), $this->_Customerno);
            $this->_databaseManager->executeQuery($checkpointsQuery);
            if ($this->_databaseManager->get_rowCount() == 0) {
                $Query = "INSERT INTO checkpointmanage (`checkpointid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d','%d','1','%d','0')";
                $SQL = sprintf($Query, Sanitise::Long($data->chkId), Sanitise::Long($objRoute->vehicleId), $this->_Customerno, Sanitise::Long($_SESSION['userid']));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        $Query = "INSERT INTO vehiclerouteman (`routeid`,`vehicleid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','%d','0','%s')";
        $SQL = sprintf($Query, Sanitise::Long($objRoute->routeId), Sanitise::Long($objRoute->vehicleId), $this->_Customerno, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
        return $objRoute->routeId;
    }

    public function getHumanReadableTimeDifference($time2, $time1 = NULL) {

        if (!isset($time1)) {
            $time1 = date('Y-m-d H:i:s');
        }
        // If not numeric then convert timestamps
        if (!is_int($time1)) {
            $time1 = strtotime($time1);
        }
        if (!is_int($time2)) {
            $time2 = strtotime($time2);
        }

        // If time1 > time2 then swap the 2 values
        if ($time1 > $time2) {
            list( $time1, $time2 ) = array($time2, $time1);


            // Set up intervals and diffs arrays
            $intervals = array('year', 'month', 'day', 'hour', 'minute', 'second');
            $diffs = array();

            foreach ($intervals as $interval) {
                // Create temp time from time1 and interval
                $ttime = strtotime('+1 ' . $interval, $time1);
                // Set initial values
                $add = 1;
                $looped = 0;
                // Loop until temp time is smaller than time2
                while ($time2 >= $ttime) {
                    // Create new temp time from time1 and interval
                    $add++;
                    $ttime = strtotime("+" . $add . " " . $interval, $time1);
                    $looped++;
                }

                $time1 = strtotime("+" . $looped . " " . $interval, $time1);
                $diffs[$interval] = $looped;
            }

            $count = 0;
            $times = array();
            foreach ($diffs as $interval => $value) {
                // Add value and interval if value is bigger than 0
                if ($value > 0) {
                    if ($value != 1) {
                        $interval .= "s";
                    }
                    // Add value and interval to times array
                    $times[] = $value . " " . $interval;
                    $count++;
                }
            }

            // Return string with times
            return implode(" ", $times);
        } else {
            return '';
        }
    }

}

// end class