<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOGeofence.php';
include_once '../../lib/model/VOFence.php';

class GeofenceManager extends VersionedManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function SaveGeofence($geofence) { 
        //echo"geofence data: <pre>"; print_r($geofence); exit();
        if (!isset($geofence->geofenceid)) {
            $this->Insert($geofence);
        } else {
            //$this->Update($geofence);
        }
    }

    public function InsertFence($geofence,$polygonLatLongJson) {
        //$Query = "INSERT INTO fence (`customerno`,`fencename`,`userid`) VALUES (%d,'%s',%d)";
        $Query = "INSERT INTO checkpoint (`customerno`,`cname`,`userid`,`checkPointCategory`,`polygonLatLongJson`,`isdeleted`) VALUES (%d,'%s',%d,'2','".$polygonLatLongJson."','0')";
        $SQL = sprintf($Query, $this->_Customerno, Sanitise::String($geofence->fencename), Sanitise::Long($geofence->userid));
       /*  echo"SQL is: ".$SQL; exit(); */
        $this->_databaseManager->executeQuery($SQL);
        $geofence->fenceid = $this->_databaseManager->get_insertedId();

    }

    private function Insert($geofence) {
       /*  $Query = "INSERT INTO geofence (`customerno`,`fenceid`,`geolat`,`geolong`,`userid`) VALUES (%d,%d,'%f','%f',%d)"; */
       $Query = "UPDATE checkpoint set polygonLatLongJson='".$geofence->ploygonLatLongJsonArray."' WHERE checkpointid=".$geofence->fenceid." AND customerno=".$this->_Customerno;
      /*  $Query = "INSERT INTO checkpoint (`customerno`,`cname`,`userid`,`checkPointCategory`,`polygonLatLongJson`,`isdeleted`) VALUES (%d,'%s',%d,'2','".$polygonLatLongJson."','0')";
        $SQL = sprintf($Query, $this->_Customerno,
            Sanitise::Long($geofence->fenceid),
            Sanitise::Float($geofence->geolat),
            Sanitise::Float($geofence->geolong),
            Sanitise::Long($geofence->userid)); */
        $this->_databaseManager->executeQuery($Query);
    }

    public function modify_multiple_fences($fence_arr, $insert_arr, $userid,$dataArray) {

        foreach($dataArray as $key=>$value)
        {  //echo"<br>LatLongJSONArray: ".$value['ploygonLatLongJsonArray'];
            $Update_Q = "UPDATE checkpoint SET polygonLatLongJson='".$value['ploygonLatLongJsonArray']."' WHERE checkpointid =".$value['fenceid']." AND customerno = ".$this->_Customerno;
            $this->_databaseManager->executeQuery($Update_Q);
            //echo"<br>Query is: ".$Update_Q;
        }

        /* $fence_csv = implode(',', $fence_arr);
        $Update_Q = "UPDATE geofence SET isdeleted=1,userid=$userid WHERE fenceid in ($fence_csv) AND customerno = $this->_Customerno";
        $this->_databaseManager->executeQuery($Update_Q);

        $insert_csv = implode(',', $insert_arr);
        $Insert_Q = "INSERT INTO geofence (`customerno`,`fenceid`,`geolat`,`geolong`,`userid`) VALUES $insert_csv";
        $this->_databaseManager->executeQuery($Insert_Q); */
    }

    public function InsertFenceMan($geofence) {
        /* $Query = "INSERT INTO fenceman (`fenceid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d',%d,'0',%d,'0')"; */
        $Query = "INSERT INTO checkpointmanage (`checkpointid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d',%d,'0',%d,'0')";

        $SQL = sprintf($Query, Sanitise::Long($geofence->fenceid), Sanitise::Long($geofence->vehicle), $this->_Customerno, Sanitise::Long($geofence->userid));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function addfencetovehicle($fences, $vehicleid, $userid) {
       /*  $Query = "Update fenceman Set `isdeleted`=1 WHERE vehicleid = %d AND customerno = %d"; */
       $Query = "Update checkpointmanage Set `isdeleted`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

      /*   $Query = "INSERT INTO fenceman (`fenceid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d',%d,'0',%d,'0')"; */
      $Query = "INSERT INTO checkpointmanage (`checkpointid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d',%d,'0',%d,'0')";
        foreach ($fences as $fenceid) {
            $SQL = sprintf($Query, Sanitise::Long($fenceid), Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::Long($userid));
            $this->_databaseManager->executeQuery($SQL);
        }
    }

    public function delfenceman($fenceid) {
       /*  $Query = "Update fenceman Set `isdeleted`=1 WHERE fenceid = %d AND customerno = %d"; */
       $Query = "Update checkpointmanage Set `isdeleted`=1 WHERE checkpointid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($fenceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

    }

    public function get_added_vehicles_fence($fenceid) {
        $vehicles = Array();
      /*   $Query = 'select * from vehicle
            INNER JOIN fenceman ON fenceman.vehicleid = vehicle.vehicleid
            where fenceman.fenceid=%s AND fenceman.customerno=%s AND vehicle.customerno=%s AND vehicle.isdeleted=0 AND fenceman.isdeleted=0'; */
            $Query = 'select * from vehicle
            INNER JOIN checkpointmanage ON checkpointmanage.vehicleid = vehicle.vehicleid
            where checkpointmanage.checkpointid=%s AND checkpointmanage.customerno=%s AND vehicle.customerno=%s AND vehicle.isdeleted=0 AND checkpointmanage.isdeleted=0';
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }

        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, Sanitise::Long($fenceid), $this->_Customerno, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, Sanitise::Long($fenceid), $this->_Customerno, $this->_Customerno);
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

    public function get_geofence_from_fenceid($fenceid) { 
        $geofence = Array();
       /*  $Query = "SELECT * FROM `geofence` WHERE customerno=%d AND fenceid=%d AND isdeleted=0"; */
       $Query = "SELECT checkpointid,customerno,polygonLatLongJson FROM `checkpoint` WHERE customerno=%d AND checkpointid=%d AND isdeleted=0 AND checkPointCategory=2";
        $geofenceQuery = sprintf($Query, $this->_Customerno, Sanitise::String($fenceid)); 
        $_SESSION['query'] = $geofenceQuery;
        $this->_databaseManager->executeQuery($geofenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                /*$geofencepart = new VOGeofence();
                $geofencepart->geofenceid = $row['geofenceid'];
                $geofencepart->fenceid = $row['fenceid'];
                $geofencepart->customerno = $row['customerno'];
                $geofencepart->geolat = $row['geolat'];
                $geofencepart->geolong = $row['geolong'];
                $geofence[] = $geofencepart; */

                $geofencepart = new stdClass();
                $geofencepart->geofenceid = $row['checkpointid'];
                //$geofencepart->fenceid = $row['fenceid'];
                $geofencepart->customerno = $row['customerno'];
                $geofencepart->polygonLatLongJson = $row['polygonLatLongJson'];
                //$geofencepart->geolong = $row['geolong'];
                $geofence[] = $geofencepart;
            }
           
            return json_decode(json_encode($geofence));
        }
        return null;
    }

    public function getfencename($fenceid) {
        $Query = "SELECT fencename FROM `fence` WHERE customerno=%d AND fenceid=%d AND isdeleted=0";
        $geofenceQuery = sprintf($Query, $this->_Customerno, Sanitise::String($fenceid));
        $this->_databaseManager->executeQuery($geofenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row["fencename"];
            }
        }
        return null;
    }

    public function fencenamebyid($fenceid) {
        /* $Query = "SELECT fencename FROM `fence` WHERE customerno=%d AND fenceid=%d AND isdeleted=0"; */
        $Query = "SELECT cname FROM `checkpoint` WHERE customerno=%d AND checkpointid=%d AND isdeleted=0";
        $geofenceQuery = sprintf($Query, $this->_Customerno, Sanitise::String($fenceid));
        $this->_databaseManager->executeQuery($geofenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $geofencepart = new VOGeofence();
                $geofencepart->fencename = $row['cname'];
                return $geofencepart;
            }
        }
        return null;
    }

    public function getallfencenames() {
        $geofences = Array();
        $Query = "SELECT * FROM `fence` WHERE customerno=%d AND isdeleted=0";
        $geofenceQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($geofenceQuery);

        while ($row = $this->_databaseManager->get_nextRow()) {
            $geofencepart = new VOGeofence();
            $geofencepart->fencename = $row['fencename'];
            $geofencepart->fenceid = $row['fenceid'];
            $geofences[] = $geofencepart;
        }
        return $geofences;
    }

    public function updategeofence($fenceid, $fencename) {
       /*  $Query = "UPDATE `fence` SET `fencename`='%s' WHERE fenceid=%d AND customerno = %d AND isdeleted=0"; */
       $Query = "UPDATE `checkpoint` SET `cname`='%s' WHERE checkpointid=%d AND customerno = %d AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($fencename), Sanitise::String($fenceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

    }

    public function getfencefromname($fencename) {
        /* $Query = "SELECT fenceid FROM `fence` WHERE customerno=%d AND fencename='%s' AND isdeleted=0"; */
        $Query = "SELECT checkpointid FROM `checkpoint` WHERE customerno=%d AND cname='%s' AND isdeleted=0";
        $geofenceQuery = sprintf($Query, $this->_Customerno, Sanitise::String($fencename));
        $this->_databaseManager->executeQuery($geofenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row["checkpointid"];
            }
        }
        return null;
    }

    public function getfencefromnameid($fencename, $fenceid) {
       /*  $Query = "SELECT fenceid FROM `fence` WHERE fenceid!=%d AND customerno=%d AND fencename='%s' AND isdeleted=0"; */
       $Query = "SELECT checkpointid FROM `checkpoint` WHERE checkpointid!=%d AND customerno=%d AND cname='%s' AND isdeleted=0 AND checkPointCategory=2";
        $geofenceQuery = sprintf($Query, Sanitise::String($fenceid), $this->_Customerno, Sanitise::String($fencename));
        $this->_databaseManager->executeQuery($geofenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row["checkpointid"];
            }
        }
        return null;
    }

    public function getconflictstatus($fenceid) {
        $geofenceQuery = sprintf("SELECT conflictstatus FROM `fence` WHERE customerno=%d AND fenceid=%d AND isdeleted=0",
            $this->_Customerno, Sanitise::String($fenceid));
        $this->_databaseManager->executeQuery($geofenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row["conflictstatus"];
            }
        }
        return null;
    }

    public function getgeofencesforcustomer() {
        $geofence = Array();
        $Query = "SELECT * FROM `geofence` WHERE customerno=%d AND isdeleted=0";
        $geofenceQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($geofenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $geofencepart = new VOGeofence();
                $geofencepart->geofenceid = $row['geofenceid'];
                $geofencepart->fenceid = $row['fenceid'];
                $geofencepart->customerno = $row['customerno'];
                $geofencepart->geolat = $row['geolat'];
                $geofencepart->geolong = $row['geolong'];
                $geofence[] = $geofencepart;
            }
            return $geofence;
        }
        return null;
    }

    public function getdistinctfencenames() {
        $geofences = Array();
        $Query = "SELECT checkpointid,cname FROM `checkpoint` WHERE checkpoint.customerno=%d AND isdeleted=0 AND checkPointCategory=2";
        $fenceQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($fenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $geofence = new VOGeofence();
                $geofence->fencename = $row['cname'];
                $geofence->fenceid = $row['checkpointid'];
                $geofences[] = $geofence;
            }
            return $geofences;
        }
        return null;
    }

    public function markoutsidefence($fenceid, $vehicleid) {
        $Query = "Update fenceman Set conflictstatus=1 WHERE fenceid = %d AND vehicleid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($fenceid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markinsidefence($fenceid, $vehicleid) {
        $Query = "Update fenceman Set conflictstatus=0 WHERE fenceid = %d AND vehicleid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($fenceid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeleteGeofence($fenceid, $userid) {
        $Query = "UPDATE geofence SET isdeleted=1,userid=%d WHERE fenceid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($userid), Sanitise::long($fenceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        $Query = "UPDATE fence SET isdeleted=1,userid=%d WHERE fenceid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($userid), Sanitise::long($fenceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        $Query = "UPDATE fenceman SET isdeleted=1,userid=%d WHERE fenceid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($userid), Sanitise::long($fenceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeleteGeofenceData($fenceid, $userid) {
       /*  $Query = "DELETE FROM geofence WHERE fenceid=%d AND customerno = %d"; */
       $Query = "UPDATE checkpoint set isdeleted=1 WHERE checkpointid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($fenceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getvehicleforfence($fenceid) {
        $Query = "select * FROM fenceman WHERE fenceid=%d AND customerno=%d AND isdeleted=0";
        $fenceQuery = sprintf($Query, Sanitise::Long($fenceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($fenceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $fence = new VOFence();
                $fence->fenceid = $row['fenceid'];
                $fence->fencename = $row['fencename'];
            }
            return $fence;
        }
        return NULL;
    }

    public function getfences() {
        $fences = array();
       /*  $Query = "select * from fence WHERE customerno=%s AND isdeleted=0";
        $fenceQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($fenceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $fence = new VOFence();
                $fence->fenceid = $row['fenceid'];
                $fence->fencename = $row['fencename'];
                $fences[] = $fence;
            }
            return $fences;
        }
        return NULL; */

        /* New query starts here */

        $Query = "select checkpointid,cname,polygonLatLongJson from checkpoint WHERE customerno=%s AND isdeleted=0 AND checkPointCategory=2";
        $fenceQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($fenceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $fence = new VOFence();
                $fence->fenceid = $row['checkpointid'];
                $fence->fencename = $row['cname'];
                $fence->polygonLatLongJson = $row['polygonLatLongJson'];
                $fences[] = $fence;
            }
            return $fences;
        }
        return NULL;

        /* New query ends here */
    }

    public function getfences_with_bounds() {

        $fences = $this->getfences();
        if ($fences) {
            foreach ($fences as $thisfence) {
                $subs['fencid'] = $thisfence->fenceid;
                $subs['fencename'] = $thisfence->fencename;
                $subs['fence_bound'] = $thisfence->polygonLatLongJson;//$this->get_geofence_by_fenceid($thisfence->fenceid);
                $main_fence[] = $subs;
            }
            return $main_fence;
        }
        return NULL;
    }

    public function getfencesforvehicle($vehicleid) {
        $geofences = Array();
        $Query = "select * from fence
            INNER JOIN fenceman ON fenceman.fenceid = fence.fenceid
            INNER JOIN vehicle ON vehicle.vehicleid = fenceman.vehicleid
            where fenceman.vehicleid=%s AND fenceman.customerno=%s AND fence.isdeleted=0 AND fenceman.isdeleted=0";
        $fenceQuery = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($fenceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $geofence = new VOFence();
                $geofence->fenceid = $row['fenceid'];
                $geofence->fencename = $row['fencename'];
                $geofence->conflictstatus = $row['conflictstatus'];
                //$geofence->geofencelatlong =  $this->get_geofence_by_fenceid($row['fenceid']);
                $geofences[] = $geofence;
            }

            return $geofences;
        }
        return NULL;
    }

    public function DeleteFenceByVehicleid($fenceid, $vehicleid, $userid) {
        //$Query = "UPDATE geofence SET isdeleted='1',`userid`=%d WHERE `fenceid`=%d AND `customerno` = %d";
        //$SQL = sprintf($Query,Sanitise::long($userid),Sanitise::long($fenceid),$this->_Customerno);
        //$this->_databaseManager->executeQuery($SQL);

        $Query = "UPDATE fenceman SET isdeleted='1',`userid`=%d WHERE `fenceid`=%d AND  `vehicleid`=%d AND `customerno` = %d";
        $SQL = sprintf($Query, Sanitise::long($userid), Sanitise::String($fenceid), Sanitise::String($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_geofence_by_fenceid($fenceid) {
        $geofences = Array();

        $Query = "SELECT * FROM `geofence` WHERE customerno=%d AND fenceid=%d AND isdeleted=0";
        $geofenceQuery = sprintf($Query, $this->_Customerno, $fenceid);
        //$_SESSION['query'] = $geofenceQuery;
        $this->_databaseManager->executeQuery($geofenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $geofencepart = new VOGeofence();
                $geofencepart->geofenceid = $row['geofenceid'];
                $geofencepart->fenceid = $row['fenceid'];
                $geofencepart->customerno = $row['customerno'];
                $geofencepart->geolat = $row['geolat'];
                $geofencepart->geolong = $row['geolong'];
                $geofences[] = $geofencepart;
            }
            return $geofences;
        }

        return null;
    }

    public function get_customerfence_latLong() {
        $geofences = Array();
        $Query = "SELECT * FROM geofence WHERE customerno=%d AND isdeleted=0";
        $geofenceQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($geofenceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $fenceid = $row['fenceid'];
                $geofences[$fenceid][] = $row['geolat'] . ' ' . $row['geolong'];
            }
            return $geofences;
        }

        return null;
    }

    public function insertRouteFence($geofence) {
        $Query = "INSERT INTO routegeofence (`customerno`,`fenceid`,`geolat`,`geolong`,`latfloor`,`longfloor`,`userid`) VALUES (%d,%d,'%f','%f',%d,%d,%d)";
        $SQL = sprintf($Query, $geofence->customerNo,
            Sanitise::Long($geofence->routeFenceId),
            Sanitise::Float($geofence->lat),
            Sanitise::Float($geofence->lng),
            Sanitise::Long($geofence->lat),
            Sanitise::Long($geofence->lng),
            Sanitise::Long($geofence->userId));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getRouteFenceConflictStatus($lat, $long, $fenceid) {
        $isInsideFence = 0;
        $latint = floor($lat);
        $longint = floor($long);
        $LocQuery = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `geolat` ) * PI( ) /180 /2 ) , 2 ) +
        COS( " . $lat . " * PI( ) /180 ) * COS( `geolat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `geolong` ) * PI( ) /180 /2 ) , 2 ) ) )
        AS distance
        FROM " . DB_PARENT . ".routegeofence
        WHERE fenceid = " . $fenceid . "
        AND `latfloor` = " . $latint . "
        AND `longfloor` = " . $longint . "
        HAVING distance < 0.5
        AND customerno IN(" . $this->_Customerno . ")
        ORDER BY distance LIMIT 0,1 ";
        $geoloc_query = sprintf($LocQuery);
        $this->_databaseManager->executeQuery($geoloc_query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $isInsideFence = 1;
        }
        return $isInsideFence;
    }

    public function deleteFence($fenceid) {
        //$Query = "DELETE FROM `checkpoint` WHERE checkpointid=".$fenceid;
        //echo"Query is: ".$Query; exit();
        $Query = "UPDATE `checkpoint` SET `isdeleted`=1 WHERE checkpointid=".$fenceid /* AND customerno = %d AND isdeleted=0 */;
        //$SQL = sprintf($Query, /* Sanitise::String($fencename),  */Sanitise::String($fenceid)/* , $this->_Customerno */);
        $this->_databaseManager->executeQuery($Query);
        

    }
}
