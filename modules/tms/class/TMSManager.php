<?php

require_once '../../lib/system/DatabaseTMSManager.php';

/**
 * class of TMS-module
 */
class TMS extends DatabaseTMSManager {

  public function __construct($customerno, $userid) {
    parent::__construct($customerno, $userid);
    $this->customerno = $customerno;
    $this->userid = $userid;
    $this->todaysdate = date("Y-m-d H:i:s");
  }

  //
  // <editor-fold defaultstate="collapsed" desc="ZONE Functions">
  public function insert_zone(Zone $objZone) {
    $currentzoneid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objZone->zonename . "'"
       . "," . $objZone->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentzoneid";
      $query = $this->PrepareSP(constants::SP_INSERT_ZONE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentzoneid');
      $result = mysql_fetch_assoc($outputVars);
      $currentzoneid = $result["@currentzoneid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentzoneid;
  }

  public function update_zone(Zone $objZone) {
    $noOfRowAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objZone->zoneid . ""
       . ",'" . $objZone->zonename . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_ZONE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowAffected;
  }

  public function delete_zone(Zone $objZone) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objZone->zoneid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_ZONE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_zones(Zone $objZone) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objZone->customerno . "'"
       . ",'" . $objZone->zoneid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_ZONES, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_zones_test(Zone $objZone) {
    $recordset = array();
    try {
      //Prepare parameters
      $sp_params = "'" . $objZone->customerno . "'"
       . ",'" . $objZone->zoneid . "'";
      //$query = $this->PrepareSP(constants::SP_GET_ZONES, $sp_params);
      $query = "select * from zone where zone.customerno=%d";
      $query .=" order by zoneid DESC ";
      $sql = sprintf($query, $objZone->customerno);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_zone_filter(Zone $objZone) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objZone->customerno . "'"
       . ",'" . $objZone->zoneid . "'";
      $query = "select * from zone where zone.customerno=%d and zonename LIKE '%s'";
      $query .=" and isdeleted=0 order by zoneid DESC ";
      $sql = sprintf($query, $objZone->customerno, $objZone->zonename);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
      //print_r($recordset);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNC__);
    }
    return $recordset;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="DEPOT Functions">
  public function insert_depot(Depot $objDepot) {
    $currentdepotid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objDepot->depotcode . "'"
       . ",'" . $objDepot->depotname . "'"
       . "," . $objDepot->zoneid . ""
       . ",'" . $objDepot->multidrop . "'"
       . "," . $objDepot->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'"
       . "," . "@currentdepotid";
      $query = $this->PrepareSP(constants::SP_INSERT_DEPOT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentdepotid');
      $result = mysql_fetch_assoc($outputVars);
      $currentdepotid = $result["@currentdepotid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentdepotid;
  }

  public function update_depot(Depot $objDepot) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objDepot->depotcode . "'"
       . ",'" . $objDepot->depotname . "'"
       . "," . $objDepot->zoneid . ""
       . "," . $objDepot->depotid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_DEPOT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_depot(Depot $objDepot) {
    $noOfRowAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objDepot->depotid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_DEPOT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowAffected;
  }

  public function get_depots(Depot $objDepot) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objDepot->customerno . "'"
       . ",'" . $objDepot->zoneid . "'"
       . ",'" . $objDepot->depotid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_DEPOTS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_mapped_depots(Depot $objDepot) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objDepot->customerno . "'"
       . ",'" . $objDepot->depotid . "'";
      $this->PrepareSP(constants::SP_GET_MAPPED_DEPOTS, $sp_params);
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_MAPPED_DEPOTS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function delete_mapped_depots(Depot $objDepot) {
    $noOfRowAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objDepot->depotid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_MAPPED_DEPOT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowAffected;
  }

  public function get_depots_test(Depot $objDepot) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objDepot->customerno . "'"
       . ",'" . $objDepot->zoneid . "'";
      $join .= " left join zone on depot.zoneid=zone.zoneid";
      $query = "select *, depot.zoneid as zoneid from depot " . $join . " where depot.customerno=%d";
      if ($objDepot->zoneid != '') {
        $query .= " and zoneid=$objDepot->zoneid";
      }
      $query .=" order by depotid DESC ";
      $sql = sprintf($query, $objDepot->customerno);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_depots_filter(Depot $objDepot) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objDepot->customerno . "'"
       . ",'" . $objDepot->zoneid . "'"
       . ",'" . $objDepot->depotname . "'";
      $join .= " left join zone on depot.zoneid=zone.zoneid";
      $query = "select *, depot.zoneid as zoneid from depot " . $join . " where depot.customerno=%d and depotname LIKE '%s' and multidrop=0 AND depot.isdeleted = 0 AND zone.isdeleted = 0";
      if ($objDepot->zoneid != '') {
        $query .= " and zoneid=$objDepot->zoneid";
      }
      $query .=" order by depotid DESC ";
      $sql = sprintf($query, $objDepot->customerno, $objDepot->depotname);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
      //print_r($recordset);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_depots_like(Depot $objDepot) {
    $recordset = array();
    $join = '';
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $query = "select depotid from depot where depot.customerno=%d and depotname = '%s' AND isdeleted = 0";
      $query .=" order by depotid DESC ";
      $sql = sprintf($query, $objDepot->customerno, $objDepot->depotname);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function insert_multidepot_mapping(Depot $objDepot) {
    $currentdepotid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objDepot->depotid . "'"
       . ",'" . $objDepot->factoryid . "'"
       . ",'" . $objDepot->multidropid . "'"
       . "," . $objDepot->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_INSERT_MULTIDEPOT_MAPPING, $sp_params);
      $queryResult = $this->executeQuery($query);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentdepotid;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="MASTER LOCATION Functions">
  public function insert_location(Location $objLocation) {
    $currentlocationid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLocation->locationname . "'"
       . "," . $objLocation->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentlocationid";
      $query = $this->PrepareSP(constants::SP_INSERT_LOCATION, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentlocationid');
      $result = mysql_fetch_assoc($outputVars);
      $currentlocationid = $result["@currentlocationid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentlocationid;
  }

  public function update_location(Location $objLocation) {
    $noOfRowAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLocation->locationname . "'"
       . ",'" . $objLocation->locationid . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_LOCATION, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowAffected;
  }

  public function delete_location(Location $objLocation) {
    $noOfRowAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objLocation->locationid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_LOCATION, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowAffected;
  }

  public function get_locations(Location $objLocation) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objLocation->customerno . "'"
       . ",'" . $objLocation->locationid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_LOCATIONS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_locations_test(Location $objLocation) {
    $recordset = array();
    try {
      //Prepare parameters
      $sp_params = "'" . $objLocation->customerno . "'"
       . ",'" . $objLocation->locationid . "'";
      //$query = $this->PrepareSP(constants::SP_GET_ZONES, $sp_params);
      $query = "select * from location where location.customerno=%d";
      if ($objLocation->locationid != '') {
        $query .=" and location.locationid=$objLocation->locationid";
      }
      $query .=" order by locationid DESC ";
      $sql = sprintf($query, $objLocation->customerno);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_location_filter(Location $objLocation) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objLocation->customerno . "'"
       . ",'" . $objLocation->locationid . "'";
      //$query = $this->PrepareSP(constants::SP_GET_DEPOTS, $sp_params);
      $query = "select * from location " . $join . " where location.customerno=%d and locationname LIKE '%s'";
      $query .=" and isdeleted=0 order by locationid DESC ";
      $sql = sprintf($query, $objLocation->customerno, $objLocation->locationname);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
      //print_r($recordset);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_locations_like(Location $objLocation) {
    $recordset = array();
    $join = '';
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $query = "select locationid from location where location.customerno=%d and locationname = '%s' and isdeleted=0";
      $query .=" order by locationid DESC ";
      $sql = sprintf($query, $objLocation->customerno, $objLocation->locationname);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="FACTORY / PLANT Functions">
  public function insert_factory(Factory $objFactory) {
    $currentfactoryid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objFactory->factorycode . "'"
       . ",'" . $objFactory->factoryname . "'"
       . ",'" . $objFactory->zoneid . "'"
       . "," . $objFactory->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentfactoryid";
      $query = $this->PrepareSP(constants::SP_INSERT_FACTORY, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentfactoryid');
      $result = mysql_fetch_assoc($outputVars);
      $currentfactoryid = $result["@currentfactoryid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentfactoryid;
  }

  public function update_factory(Factory $objFactory) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objFactory->factorycode . "'"
       . ",'" . $objFactory->factoryname . "'"
       . "," . $objFactory->zoneid . ""
       . "," . $objFactory->factoryid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_FACTORY, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_factory(Factory $objFactory) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objFactory->factoryid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_FACTORY, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_factories(Factory $objFactory) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objFactory->customerno . "'"
       . ",'" . $objFactory->factoryid . "'"
       . ",'" . $objFactory->zoneid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_FACTORIES, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_factory_filter(Factory $objFactory) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objFactory->customerno . "'"
       . ",'" . $objFactory->factoryname . "'";
      //$query = $this->PrepareSP(constants::SP_GET_DEPOTS, $sp_params);
      $query = "select * from factory " . $join . " where factory.customerno=%d and factoryname LIKE '%s' AND isdeleted = 0";
      $query .=" order by factoryid DESC ";
      $sql = sprintf($query, $objFactory->customerno, $objFactory->factoryname);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
      //print_r($recordset);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_factories_like(Factory $objFactories) {
    $recordset = array();
    $join = '';
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $query = "select factoryid from factory where factory.customerno=%d and factoryname = '%s'";
      $sql = sprintf($query, $objFactories->customerno, $objFactories->factoryname);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_factory_officials(Factory $objFactory) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objFactory->customerno . "'"
       . ",'" . $objFactory->factoryid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_FACTORY_OFFICIALS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_multiple_factory_officials(Factory $objFactory) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objFactory->customerno . "'"
       . ",'" . $objFactory->factoryid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_MULTIPLE_FACTORY_OFFICIALS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_transporter_officials(Transporter $objFactory) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objFactory->customerno . "'"
       . ",'" . $objFactory->transporterid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_TRANSPORTER_OFFICIALS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="ROUTEMASTER Functions">
  public function insert_routemaster(RouteMaster $objRouteMaster) {
    $currentroutemasterid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objRouteMaster->routename . "'"
       . ",'" . $objRouteMaster->routedescription . "'"
       . ",'" . $objRouteMaster->fromlocationid . "'"
       . ",'" . $objRouteMaster->tolocationid . "'"
       . ",'" . $objRouteMaster->distance . "'"
       . ",'" . $objRouteMaster->travellingtime . "'"
       . "," . $objRouteMaster->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentroutemasterid";
      $query = $this->PrepareSP(constants::SP_INSERT_ROUTEMASTER, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentroutemasterid');
      $result = mysql_fetch_assoc($outputVars);
      $currentroutemasterid = $result["@currentroutemasterid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentroutemasterid;
  }

  public function update_routemaster(RouteMaster $objRouteMaster) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objRouteMaster->routemasterid . ""
       . ",'" . $objRouteMaster->routename . "'"
       . ",'" . $objRouteMaster->routedescription . "'"
       . "," . $objRouteMaster->fromlocationid . ""
       . ",'" . $objRouteMaster->tolocationid . "'"
       . ",'" . $objRouteMaster->distance . "'"
       . ",'" . $objRouteMaster->travellingtime . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_ROUTEMASTER, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_routemaster(RouteMaster $objRouteMaster) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objRouteMaster->routemasterid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_ROUTEMASTER, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_routemaster(RouteMaster $objRouteMaster) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objRouteMaster->customerno . "'"
       . ",'" . $objRouteMaster->routemasterid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_ROUTEMASTER, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_routemaster_like(RouteMaster $objRouteMaster) {
    $recordset = array();
    $join = '';
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $query = "select routemasterid from routemaster where routemaster.customerno=%d and routedescription = '%s' and isdeleted=0";
      $query .=" order by routemasterid DESC ";
      $sql = sprintf($query, $objRouteMaster->customerno, $objRouteMaster->routedescription);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_route_filter(RouteMaster $objRouteMaster) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objFactory->customerno . "'"
       . ",'" . $objFactory->routremasterid . "'";
      //$query = $this->PrepareSP(constants::SP_GET_DEPOTS, $sp_params);
      $query = "select * from routemaster " . $join . " where customerno=%d and routename LIKE '%s' AND isdeleted = 0";
      $query .=" order by routemasterid DESC ";
      $sql = sprintf($query, $objRouteMaster->customerno, $objRouteMaster->routename);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
      //print_r($recordset);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="CHECKPOINTS IN ROUTE Functions">
  public function insert_routecheckpoint(RouteCheckpoint $objRouteCheckpoint) {
    $currentroutecheckpointid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objRouteCheckpoint->routemasterid . "'"
       . ",'" . $objRouteCheckpoint->fromlocationid . "'"
       . ",'" . $objRouteCheckpoint->tolocationid . "'"
       . ",'" . $objRouteCheckpoint->distance . "'"
       . "," . $objRouteCheckpoint->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentroutecheckpointid";
      $query = $this->PrepareSP(constants::SP_INSERT_ROUTECHECKPOINT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentroutecheckpointid');
      $result = mysql_fetch_assoc($outputVars);
      $currentroutecheckpointid = $result["@currentroutecheckpointid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentroutecheckpointid;
  }

  public function update_routecheckpoint(RouteCheckpoint $objRouteCheckpoint) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objRouteCheckpoint->routecheckpointid . ""
       . "," . $objRouteCheckpoint->routemasterid . ""
       . "," . $objRouteCheckpoint->fromlocationid . ""
       . ",'" . $objRouteCheckpoint->tolocationid . "'"
       . ",'" . $objRouteCheckpoint->distance . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_ROUTECHECKPOINT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_routecheckpoint(RouteCheckpoint $objRouteCheckpoint) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objRouteCheckpoint->routecheckpointid . ""
       . ",'" . $this->routemasterid . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_ROUTECHECKPOINT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_routecheckpoint(RouteCheckpoint $objRouteCheckpoint) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objRouteCheckpoint->customerno . "'"
       . ",'" . $objRouteCheckpoint->routecheckpointid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_ROUTECHECKPOINT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="TRANSPORTER Functions">
  public function insert_transporter(Transporter $objTransporter) {
    $currenttransporterid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objTransporter->transportercode . "'"
       . ",'" . $objTransporter->transportername . "'"
       . ",'" . $objTransporter->transportermail . "'"
       . ",'" . $objTransporter->transportermobileno . "'"
       . "," . $objTransporter->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currenttransporterid";
      $query = $this->PrepareSP(constants::SP_INSERT_TRANSPORTER, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currenttransporterid');
      $result = mysql_fetch_assoc($outputVars);
      $currenttransporterid = $result["@currenttransporterid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currenttransporterid;
  }

  public function update_transporter(Transporter $objTransporter) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objTransporter->transportercode . "'"
       . ",'" . $objTransporter->transportername . "'"
       . ",'" . $objTransporter->transportermail . "'"
       . ",'" . $objTransporter->transportermobileno . "'"
       . ",'" . $objTransporter->transporterid . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_TRANSPORTER, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_transporter(Transporter $objTransporter) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objTransporter->transporterid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_TRANSPORTER, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_transporter(Transporter $objTransporter) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objTransporter->customerno . "'"
       . ",'" . $objTransporter->transporterid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_TRANSPORTERS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
      //print_r($arrResult);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_transporter_filter(Transporter $objTransporter) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objTransporter->customerno . "'"
       . ",'" . $objTransporter->transportername . "'";
      //$query = $this->PrepareSP(constants::SP_GET_DEPOTS, $sp_params);
      $query = "select * from transporter " . $join . " where transporter.customerno=%d and transportername LIKE '%s' AND isdeleted = 0";
      $query .=" order by transporterid DESC ";
      $sql = sprintf($query, $objTransporter->customerno, $objTransporter->transportername);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
      //print_r($recordset);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_transporter_filter_byZone(Transporter $objTransporter) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objTransporter->customerno . "'"
       . ",'" . $objTransporter->transportername . "'"
       . ",'" . $objTransporter->factoryid . "'"
       . ",'" . $objTransporter->zoneid . "'";
      $join = ' INNER JOIN transportershare as ts on ts.transporterid = transporter.transporterid and ts.factoryid=' . $objTransporter->factoryid . ' AND ts.zoneid=' . $objTransporter->zoneid . '';
      $query = "select transporter.transporterid, transporter.transportername from transporter " . $join . "
    where transporter.customerno=%d and transportername LIKE '%s' AND transporter.isdeleted = 0 and ts.isdeleted = 0";
      $query .=" order by transporterid DESC ";
      $sql = sprintf($query, $objTransporter->customerno, $objTransporter->transportername);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
      //print_r($recordset);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_transporter_indents(Transporter $objTransporter) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objTransporter->customerno . "'"
       . ",'" . $objTransporter->transporterid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_TRANSPORTER_INDENTS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_transporter_indent_count(Transporter $objTransporter) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objTransporter->customerno . "'"
       . ",'" . $objTransporter->transporterid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_TRANSPORTER_INDENT_COUNT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="TRANSPORTER SHARE AND ACTUAL SHARE IN EACH ZONE Functions">
  public function insert_transportershare(TransporterShare $objTransporterShare) {
    $currenttransportershareid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objTransporterShare->transporterid . "'"
       . ",'" . $objTransporterShare->factoryid . "'"
       . ",'" . $objTransporterShare->zoneid . "'"
       . ",'" . $objTransporterShare->sharepercent . "'"
       . "," . $objTransporterShare->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currenttransportershareid";
      $query = $this->PrepareSP(constants::SP_INSERT_TRANSPORTERSHARE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currenttransportershareid');
      $result = mysql_fetch_assoc($outputVars);
      $currenttransportershareid = $result["@currenttransportershareid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currenttransportershareid;
  }

  public function update_transportershare(TransporterShare $objTransporterShare) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objTransporterShare->transportershareid . ""
       . "," . $objTransporterShare->transporterid . ""
       . "," . $objTransporterShare->factoryid . ""
       . "," . $objTransporterShare->zoneid . ""
       . ",'" . $objTransporterShare->sharepercent . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_TRANSPORTERSHARE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_transportershare(TransporterShare $objTransporterShare) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objTransporterShare->transportershareid . ""
       /* Transporter id would be blank here as we don't want
        * all the records for this transporter to be deleted */
       . ",''"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_TRANSPORTERSHARE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_transportershare(TransporterShare $objTransporterShare) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objTransporterShare->customerno . "'"
       . ",'" . $objTransporterShare->transporterid . "'"
       . ",'" . $objTransporterShare->factoryid . "'"
       . ",'" . $objTransporterShare->zoneid . "'"
       . ",'" . $objTransporterShare->transportershareid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_TRANSPORTERSHARE, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
      //print_r($arrResult);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_transporteractualshare(TransporterActualShare $objTransporterActualShare) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objTransporterActualShare->customerno . "'"
       . ",'" . $objTransporterActualShare->transporterid . "'"
       . ",'" . $objTransporterActualShare->factoryid . "'"
       . ",'" . $objTransporterActualShare->zoneid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_TRANSPORTERACTUALSHARE, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
      //print_r($arrResult);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function update_transporteractualshare(TransporterActualShare $objTransporterActualShare) {
    $noOfRowsAffected = 0;
    /*
      The user defined percent share would never be updated from UI.
      Also, actshareid would not be used from UI to update any record.
      Both are used for calliing this SP internally from update_transportershare.
      Hence, both initialized as -1
     */
    $userDefinedPercentShare = -1;
    $actshareid = -1;
    try {
      //Prepare parameters
      $sp_params = "" . $objTransporterActualShare->transporterid . ""
       . "," . $objTransporterActualShare->factoryid . ""
       . "," . $objTransporterActualShare->zoneid . ""
       . "," . $objTransporterActualShare->shared_weight . ""
       . ",'" . $objTransporterActualShare->total_weight . "'"
       . ",'" . $userDefinedPercentShare . "'"
       . ",'" . $actshareid . "'"
       . "," . $this->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_TRANSPORTERACTUALSHARE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="VEHICLE Functions">
  public function insert_vehicle(Vehicle $objVehicle) {
    $currentvehicleid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objVehicle->transporterid . "'"
       . ",'" . $objVehicle->vehicletypeid . "'"
       . ",'" . $objVehicle->vehicleno . "'"
       . "," . $objVehicle->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentvehicleid";
      $query = $this->PrepareSP(constants::SP_INSERT_VEHICLE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentvehicleid');
      $result = mysql_fetch_assoc($outputVars);
      $currentvehicleid = $result["@currentvehicleid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentvehicleid;
  }

  public function update_vehicle(Vehicle $objVehicle) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objVehicle->vehicleid . "'"
       . ",'" . $objVehicle->vehicleno . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_VEHICLE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_vehicle(Vehicle $objVehicle) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objVehicle->vehicleid . ""
       . ",'" . $objVehicle->transporterid . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_VEHICLE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_vehicles(Vehicle $objVehicle) {
    //print_r($objVehicle);
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objVehicle->customerno . "'"
       . ",'" . $objVehicle->transporterid . "'"
       . ",'" . $objVehicle->vehicleid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_VEHICLES, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="VEHICLE TYPE Functions">
  public function insert_vehicletype(VehicleType $objVehicleType) {
    $currentvehicletypeid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objVehicleType->vehiclecode . "'"
       . ",'" . $objVehicleType->vehicledescription . "'"
       . "," . $objVehicleType->skutypeid . ""
       . "," . $objVehicleType->volumecapacity . ""
       . "," . $objVehicleType->weightcapacity . ""
       . "," . $objVehicleType->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentvehicletypeid";
      $query = $this->PrepareSP(constants::SP_INSERT_VEHICLETYPE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentvehicletypeid');
      $result = mysql_fetch_assoc($outputVars);
      $currentvehicletypeid = $result["@currentvehicletypeid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentvehicletypeid;
  }

  public function update_vehicletype(VehicleType $objVehicleType) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objVehicleType->vehiclecode . "'"
       . ",'" . $objVehicleType->vehicledescription . "'"
       . ",'" . $objVehicleType->skutypeid . "'"
       . ",'" . $objVehicleType->volumecapacity . "'"
       . ",'" . $objVehicleType->weightcapacity . "'"
       . ",'" . $objVehicleType->vehicletypeid . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_VEHICLETYPE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_vehicletype(VehicleType $objVehicleType) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objVehicleType->vehicletypeid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_VEHICLETYPE, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_vehicletypes(VehicleType $objVehicleType) {
    //print_r($objVehicle);
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objVehicleType->customerno . "'"
       . ",'" . $objVehicleType->vehicletypeid . "'"
       . ",'" . $objVehicleType->skutypeid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_VEHICLETYPES, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_vehicletype_filter(VehicleType $objVehicleType) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objVehicleType->customerno . "'"
       . ",'" . $objVehicleType->vehiclecode . "'";
      //$query = $this->PrepareSP(constants::SP_GET_DEPOTS, $sp_params);
      $query = "select * from vehicletype " . $join . " where vehicletype.customerno=%d and vehicledescription LIKE '%s' AND isdeleted = 0";
      $query .=" order by vehicletypeid DESC ";
      $sql = sprintf($query, $objVehicleType->customerno, $objVehicleType->vehiclecode);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
      //print_r($recordset);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_vehtype_filter(VehicleType $objVehicleType) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objVehicleType->customerno . "'"
       . ",'" . $objVehicleType->vehicledescription . "'"
       . ",'" . $objVehicleType->transporterid . "'"
       . ",'" . $objVehicleType->skutypeid . "'";
      //$query = $this->PrepareSP(constants::SP_GET_DEPOTS, $sp_params);
      $query = "select distinct(vehicletype.vehicletypeid),vehicletype.vehiclecode, vehicletype.vehicledescription, vehicletype.skutypeid, vehicletype.volume, vehicletype.weight from vehicletype
inner join vehtypetransmapping on vehtypetransmapping.vehicletypeid = vehicletype.vehicletypeid
where vehicletype.customerno=%d and vehicletype.skutypeid=%d and vehtypetransmapping.transporterid=%d and vehicledescription LIKE '%s' and vehtypetransmapping.isdeleted=0 and vehicletype.isdeleted=0";
      $query .=" order by vehicletype.vehicletypeid DESC ";
      $sql = sprintf($query, $objVehicleType->customerno, $objVehicleType->skutypeid, $objVehicleType->transporterid, $objVehicleType->vehicledescription);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
      //print_r($recordset);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function insert_vehtypetransporter_mapping(VehicleType $objVehicleType) {
    $currentvehicletypeid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objVehicleType->transporterid . "'"
       . ",'" . $objVehicleType->vehicletypeid . "'"
       . "," . $objVehicleType->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_INSERT_VEHTYPETRANSPORTER_MAPPING, $sp_params);
      $queryResult = $this->executeQuery($query);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    //return $currentvehicletypeid;
  }

  public function delete_vehtypetransporter_mapping(VehicleType $objVehicleType) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objVehicleType->vehtypemappingid . "'"
       . ",'" . $objVehicleType->transporterid . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_VEHTYPETRANSPORTER_MAPPING, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="DELIVERY DETAILS UPDATED BY FACTORY Functions">
  public function insert_factory_delivery(FactoryDeliveryDetails $objFactoryDeliveryDetails) {
    $currentfactorydeliveryid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objFactoryDeliveryDetails->factoryid . "'"
       . ",'" . $objFactoryDeliveryDetails->skuid . "'"
       . ",'" . $objFactoryDeliveryDetails->depotid . "'"
       . ",'" . $objFactoryDeliveryDetails->date_required . "'"
       . ",'" . $objFactoryDeliveryDetails->weight . "'"
       . "," . $objFactoryDeliveryDetails->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentfactorydeliveryid";
      $query = $this->PrepareSP(constants::SP_INSERT_FACTORY_DELIVERY, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentfactorydeliveryid');
      $result = mysql_fetch_assoc($outputVars);
      $currentfactorydeliveryid = $result["@currentfactorydeliveryid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentfactorydeliveryid;
  }

  public function update_factory_delivery(FactoryDeliveryDetails $objFactoryDeliveryDetails) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $objFactoryDeliveryDetails->isprocessed = 0;
      $sp_params = "'" . $objFactoryDeliveryDetails->fdid . "'"
       . ",'" . $objFactoryDeliveryDetails->factoryid . "'"
       . ",'" . $objFactoryDeliveryDetails->skuid . "'"
       . ",'" . $objFactoryDeliveryDetails->depotid . "'"
       . ",'" . $objFactoryDeliveryDetails->date_required . "'"
       . ",'" . $objFactoryDeliveryDetails->weight . "'"
       . "," . $this->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . ",'" . $objFactoryDeliveryDetails->isprocessed . "'";
      $query = $this->PrepareSP(constants::SP_UPDATE_FACTORY_DELIVERY, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_factory_delivery(FactoryDeliveryDetails $objFactoryDeliveryDetails) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objFactoryDeliveryDetails->fdid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_FACTORY_DELIVERY, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_factory_delivery(FactoryDeliveryDetails $objFactoryDeliveryDetails) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objFactoryDeliveryDetails->customerno . "'"
       . ",'" . $objFactoryDeliveryDetails->fdid . "'"
       . ",'" . $objFactoryDeliveryDetails->factoryid . "'"
       . ",'" . $objFactoryDeliveryDetails->depotid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_FACTORY_DELIVERY, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function insert_factory_delivery_history(FactoryDeliveryDetails $objFactoryDeliveryDetails) {
    $currentfactorydeliveryid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objFactoryDeliveryDetails->customerno . "'"
       . ",'" . $objFactoryDeliveryDetails->isprocessed . "'"
       . ",'" . $objFactoryDeliveryDetails->date_required . "'";
      $query = $this->PrepareSP(constants::SP_INSERT_FACTORY_DELIVERY_HISTORY, $sp_params);
      $queryResult = $this->executeQuery($query);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentfactorydeliveryid;
  }

  public function delete_factory_delivery_history(FactoryDeliveryDetails $objFactoryDeliveryDetails) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objFactoryDeliveryDetails->customerno . "'"
       . ",'" . $objFactoryDeliveryDetails->date_required . "'";
      $query = $this->PrepareSP(constants::SP_DELETE_FACTORY_DELIVERY_HISTORY, $sp_params);
      $queryResult = $this->executeQuery($query);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="PRODUCTION DETAILS UPDATED BY SALES Functions">
  public function insert_factory_production(FactoryProductionDetails $objFactoryProductionDetails) {
    $currentfpid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objFactoryProductionDetails->factoryid . "'"
       . ",'" . $objFactoryProductionDetails->skuid . "'"
       . ",'" . $objFactoryProductionDetails->weight . "'"
       . "," . $objFactoryProductionDetails->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentfpid";
      $query = $this->PrepareSP(constants::SP_INSERT_FACTORY_PRODUCTION, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentfpid');
      $result = mysql_fetch_assoc($outputVars);
      $currentfpid = $result["@currentfpid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentfpid;
  }

  public function update_factory_production(FactoryProductionDetails $objFactoryProductionDetails) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objFactoryProductionDetails->fpid . ""
       . "," . $objFactoryProductionDetails->factoryid . ""
       . "," . $objFactoryProductionDetails->skuid . ""
       . "," . $objFactoryProductionDetails->weight . ""
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_FACTORY_PRODUCTION, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_factory_production(FactoryProductionDetails $objFactoryProductionDetails) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objFactoryProductionDetails->fpid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_FACTORY_PRODUCTION, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_factory_production(FactoryProductionDetails $objFactoryProductionDetails) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objFactoryProductionDetails->customerno . "'"
       . ",'" . $objFactoryProductionDetails->fpid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_FACTORY_PRODUCTION, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="PROPOSED INDENT Functions">
  public function insert_proposed_indent(ProposedIndent $objProposedIndent) {
    $currentproposedindentid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objProposedIndent->factoryid . "'"
       . ",'" . $objProposedIndent->depotid . "'"
       . ",'" . $objProposedIndent->total_weight . "'"
       . ",'" . $objProposedIndent->total_volume . "'"
       . ",'" . $objProposedIndent->date_required . "'"
       . ",'" . $objProposedIndent->remark . "'"
       . "," . $objProposedIndent->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentproposedindentid";
      $query = $this->PrepareSP(constants::SP_INSERT_PROPOSED_INDENT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentproposedindentid');
      $result = mysql_fetch_assoc($outputVars);
      $currentproposedindentid = $result["@currentproposedindentid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentproposedindentid;
  }

  public function update_proposed_indent(ProposedIndent $objProposedIndent) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objProposedIndent->proposedindentid . ""
       . ",'" . $objProposedIndent->factoryid . "'"
       . ",'" . $objProposedIndent->depotid . "'"
       . ",'" . $objProposedIndent->total_weight . "'"
       . ",'" . $objProposedIndent->total_volume . "'"
       . ",'" . $objProposedIndent->isApproved . "'"
       . ",'" . $objProposedIndent->hasTransporterAccepted . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_PROPOSED_INDENT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function reject_proposed_indent($objProposedIndent) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objProposedIndent->proposedindentid . ""
       . ",'" . $objProposedIndent->transporterid . "'"
       . ",'" . $objProposedIndent->vehicletypeid . "'"
       . ",'" . $objProposedIndent->factoryid . "'"
       . ",'" . $objProposedIndent->depotid . "'"
       . ",'" . $objProposedIndent->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_GET_REJECT_PROPOSED_INDENT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_proposed_indent(ProposedIndent $objProposedIndent) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objProposedIndent->proposedindentid . ""
       . ",'" . $objProposedIndent->remark . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_PROPOSED_INDENT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_proposed_indent(ProposedIndent $objProposedIndent) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objProposedIndent->customerno . "'"
       . ",'" . $objProposedIndent->proposedindentid . "'"
       . ",'" . $objProposedIndent->factoryid . "'"
       . ",'" . $objProposedIndent->transporterid . "'"
       . ",'" . $objProposedIndent->date_required . "'"
       . ",'" . $objProposedIndent->isApproved . "'"
       . ",'" . $objProposedIndent->startdate . "'"
       . ",'" . $objProposedIndent->enddate . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_PROPOSED_INDENT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_pending_indent(ProposedIndent $objProposedIndent) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objProposedIndent->customerno . "'"
       . ",'" . $objProposedIndent->date_required . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_PENDING_INDENT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_autoreject_indent(ProposedIndent $objProposedIndent) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objProposedIndent->customerno . "'"
       . ",'" . $objProposedIndent->date_required . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_AUTOREJECT_INDENT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_distinct_transporters(ProposedIndent $objProposedIndent) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objProposedIndent->customerno . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_DISTINCT_TRANSPORTERS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_assigned_transporter(ProposedIndent $objProposedIndent) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objProposedIndent->customerno . "'"
       . ",'" . $objProposedIndent->proposedindentid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_ASSIGNED_TRANSPORTER, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_proposed_indent_sku_mapping(ProposedIndent $objProposedIndent) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objProposedIndent->customerno . "'"
       . ",'" . $objProposedIndent->proposedindentid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_PROPOSED_INDENT_SKU_MAPPING, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_transporter_proposed_indent(ProposedIndentTransporterMapping $objProposedTransporterIndent) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objProposedTransporterIndent->customerno . "'"
       . ",'" . $objProposedTransporterIndent->proposedindentid . "'"
       . ",'" . $objProposedTransporterIndent->proposed_transporterid . "'"
       . ",'" . $objProposedTransporterIndent->pitmappingid . "'"
       . ",'" . $objProposedTransporterIndent->startdate . "'"
       . ",'" . $objProposedTransporterIndent->enddate . "'";
      $this->PrepareSP(constants::SP_GET_TRANSPORTER_PROPOSED_INDENT, $sp_params);
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_TRANSPORTER_PROPOSED_INDENT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="PROPOSED INDENT Transporter Mapping Functions">
  public function insert_pitmapping(ProposedIndentTransporterMapping $objPITMapping) {
    $currentpitmappingid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objPITMapping->proposedindentid . "'"
       . ",'" . $objPITMapping->proposed_transporterid . "'"
       . ",'" . $objPITMapping->proposed_vehicletypeid . "'"
       . "," . $objPITMapping->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentpitmappingid";
      $query = $this->PrepareSP(constants::SP_INSERT_PIT_MAPPING, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentpitmappingid');
      $result = mysql_fetch_assoc($outputVars);
      $currentpitmappingid = $result["@currentpitmappingid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentpitmappingid;
  }

  public function update_pitmapping(ProposedIndentTransporterMapping $objPITMapping) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objPITMapping->pitmappingid . ""
       . ",'" . $objPITMapping->actual_vehicletypeid . "'"
       . ",'" . $objPITMapping->vehicleno . "'"
       . ",'" . $objPITMapping->drivermobileno . "'"
       . ",'" . $objPITMapping->isAccepted . "'"
       . ",'" . $objPITMapping->remarks . "'"
       . ",'" . $objPITMapping->isAutoRejected . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_PIT_MAPPING, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

// </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="PROPOSED INDENT SKU Mapping Functions">
  public function insert_piskumapping(ProposedIndentSkuMapping $objSkuMapping) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objSkuMapping->proposedindentid . ""
       . "," . $objSkuMapping->skuid . ""
       . "," . $objSkuMapping->no_of_units . ""
       . "," . $objSkuMapping->weight . ""
       . ",'" . $objSkuMapping->volume . "'"
       . ",'" . $objSkuMapping->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_INSERT_PROPOSED_INDENT_SKU_MAPPING, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="SKU Functions">
  public function insert_sku(Sku $objSku) {
    $currentskuid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objSku->skucode . "'"
       . ",'" . $objSku->sku_description . "'"
       . ",'" . $objSku->type . "'"
       . ",'" . $objSku->volume . "'"
       . ",'" . $objSku->weight . "'"
       . ",'" . $objSku->netgross . "'"
       . "," . $objSku->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentskuid";
      $query = $this->PrepareSP(constants::SP_INSERT_SKU, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentskuid');
      $result = mysql_fetch_assoc($outputVars);
      $currentskuid = $result["@currentskuid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentskuid;
  }

  public function update_sku(Sku $objSku) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objSku->skuid . ""
       . ",'" . $objSku->skucode . "'"
       . ",'" . $objSku->sku_description . "'"
       . ",'" . $objSku->typeid . "'"
       . ",'" . $objSku->volume . "'"
       . ",'" . $objSku->weight . "'"
       . ",'" . $objSku->netgross . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_SKU, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_sku(Sku $objSku) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objSku->skuid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_SKU, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_leftover_sku($objSku) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objSku->skuid . ""
       . ",'" . $this->customerno . "'";
      $query = $this->PrepareSP(constants::SP_DELETE_LEFTOVERDETAILS, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_sku(Sku $objSku) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objSku->customerno . "'"
       . ",'" . $objSku->skuid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_SKU, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_sku_like(Sku $objSku) {
    $recordset = array();
    $join = '';
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $query = "select skuid from sku where sku.customerno=%d and skucode = '%s' and isdeleted=0";
      $sql = sprintf($query, $objSku->customerno, $objSku->skucode);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_skutype_filter(Sku $objSku) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objSku->customerno . "'"
       . ",'" . $objSku->type . "'";
      //$query = $this->PrepareSP(constants::SP_GET_DEPOTS, $sp_params);
      $query = "select * from skutypes where customerno=%d and type LIKE '%s' AND isdeleted = 0";
      $sql = sprintf($query, $objSku->customerno, $objSku->type);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_sku_filter(Sku $objSku) {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $sp_params = "'" . $objSku->customerno . "'"
       . ",'" . $objSku->skucode . "'";
      //$query = $this->PrepareSP(constants::SP_GET_DEPOTS, $sp_params);
      $query = "select * from sku where customerno=%d and skucode LIKE '%s' AND isdeleted = 0";
      $sql = sprintf($query, $objSku->customerno, $objSku->skucode);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_sku_bytype(Sku $objSku) {
    $recordset = array();
    $join = '';
    try {
      $query = "select * from sku where customerno=%d and skucode LIKE '%s' AND skutypeid =%d  AND isdeleted = 0";
      $sql = sprintf($query, $objSku->customerno, $objSku->skucode, $objSku->typeid);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_skutype_like(Sku $objSku) {
    $recordset = array();
    $join = '';
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $query = "select tid from skutypes where skutypes.customerno=%d and type = '%s' and isdeleted=0";
      $sql = sprintf($query, $objSku->customerno, $objSku->type);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="INDENT SKU MAPPING Functions">
  public function insert_indent_sku_mapping(IndentSkuMapping $objIndentSkuMapping) {
    $current_indent_sku_mappingid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objIndentSkuMapping->indentid . "'"
       . ",'" . $objIndentSkuMapping->skuid . "'"
       . ",'" . $objIndentSkuMapping->no_of_units . "'"
       . "," . $objIndentSkuMapping->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@current_indent_sku_mappingid";
      $query = $this->PrepareSP(constants::SP_INSERT_INDENT_SKU_MAPPING, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @current_indent_sku_mappingid');
      $result = mysql_fetch_assoc($outputVars);
      $current_indent_sku_mappingid = $result["@current_indent_sku_mappingid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $current_indent_sku_mappingid;
  }

  public function update_indent_sku_mapping(IndentSkuMapping $objIndentSkuMapping) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "," . $objIndentSkuMapping->indent_sku_mappingid . ""
       . "," . $objIndentSkuMapping->indentid . ""
       . "," . $objIndentSkuMapping->skuid . ""
       . "," . $objIndentSkuMapping->no_of_units . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_INDENT_SKU_MAPPING, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_indent_sku_mapping(IndentSkuMapping $objIndentSkuMapping) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objIndentSkuMapping->indent_sku_mappingid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_INDENT_SKU_MAPPING, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="INDENT Functions">
  public function insert_indent(Indent $objIndent) {
    $currentindentid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objIndent->transporterid . "'"
       . ",'" . $objIndent->vehicleno . "'"
       . ",'" . $objIndent->proposedindentid . "'"
       . ",'" . $objIndent->proposed_vehicletypeid . "'"
       . ",'" . $objIndent->actual_vehicletypeid . "'"
       . ",'" . $objIndent->factoryid . "'"
       . ",'" . $objIndent->depotid . "'"
       . ",'" . $objIndent->date_required . "'"
       . ",'" . $objIndent->totalweight . "'"
       . ",'" . $objIndent->totalvolume . "'"
       . "," . $objIndent->customerno . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentindentid";
      $query = $this->PrepareSP(constants::SP_INSERT_INDENT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentindentid');
      $result = mysql_fetch_assoc($outputVars);
      $currentindentid = $result["@currentindentid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentindentid;
  }

  public function update_indent(Indent $objIndent) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "," . $objIndent->indentid . ""
       . "," . $objIndent->transporterid . ""
       . "," . $objIndent->vehicleid . ""
       . "," . $objIndent->proposedindentid . ""
       . "," . $objIndent->indent_sku_mappingid . ""
       . "," . $objIndent->vehicleid . ""
       . ",'" . $objIndent->shipmentno . "'"
       . ",'" . $objIndent->totalweight . "'"
       . ",'" . $objIndent->totalvolume . "'"
       . ",'" . $objIndent->isdelivered . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_UPDATE_INDENT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function edit_indent(Indent $objIndent) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objIndent->indentid . ""
       . "," . $objIndent->loadstatus . ""
       . ",'" . $objIndent->shipmentno . "'"
       . ",'" . $objIndent->remark . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_EDIT_INDENT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_indent(Indent $objIndent) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "" . $objIndent->indentid . ""
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . "";
      $query = $this->PrepareSP(constants::SP_DELETE_INDENT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function get_indent(Indent $objIndent) {
    $arrResult = null;
    try {
      //Prepare parameters
      $pdo = $this->CreatePDOConn();
      $sp_params = "'" . $objIndent->customerno . "'"
       . ",'" . $objIndent->indentid . "'"
       . ",'" . $objIndent->factoryid . "'"
       . ",'" . $objIndent->startdate . "'"
       . ",'" . $objIndent->enddate . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_INDENT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_indent_sku_mapping(Indent $objIndent) {
    $arrResult = null;
    try {
      //Prepare parameters
      $pdo = $this->CreatePDOConn();
      $sp_params = "'" . $objIndent->customerno . "'"
       . ",'" . $objIndent->indentid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_INDENT_SKU_MAPPING, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function getMultidepotLoad($objIndent) {
    $arrResult = null;
    try {
      //Prepare parameters
      $pdo = $this->CreatePDOConn();
      $sp_params = "'" . $objIndent->factoryidparam . "'"
       . ",'" . $objIndent->multidepotcombination . "'"
       . ",'" . $objIndent->custno . "'"
       . ",'" . $objIndent->daterequired . "'"
       . ",'" . $objIndent->todaysdate . "'"
       . ",'" . $objIndent->userid . "'";
      $query = $this->PrepareSP(constants::SP_GET_MULTIDEPOT_LOAD, $sp_params);
      $queryResult = $this->executeQuery($query);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  //</editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="Algorithm Functions">
  public function get_skuweight($objSKUWeight) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objSKUWeight->customerno . "'"
       . ",'" . $objSKUWeight->depotid . "'"
       . ",'" . $objSKUWeight->factoryid . "'"
       . ",'" . $objSKUWeight->daterequired . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_SKUWEIGHT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
      //print_r($arrResult);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_skuweight_factorydepot($objSKUWeight) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objSKUWeight->customerno . "'"
       . ",'" . $objSKUWeight->daterequired . "'"
       . ",'" . $objSKUWeight->factoryid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_SKUWEIGHT_FACTORYDEPOT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
      //print_r($arrResult);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_vehtypetransporter_mapping($objVehTypeTransMapping) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objVehTypeTransMapping->customerno . "'"
       . ",'" . $objVehTypeTransMapping->transporterid . "'";
      $this->PrepareSP(constants::SP_GET_VEHICLETYPE_TRANSPORTER_MAPPING, $sp_params);
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_VEHICLETYPE_TRANSPORTER_MAPPING, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
      //print_r($arrResult);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function insert_leftover($objLeftOver) {
    $currentleftoverid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLeftOver->factoryid . "'"
       . ",'" . $objLeftOver->depotid . "'"
       . ",'" . $objLeftOver->weight . "'"
       . ",'" . $objLeftOver->volume . "'"
       . ",'" . $objLeftOver->date . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@currentleftoverid";
      $query = $this->PrepareSP(constants::SP_INSERT_LEFTOVER, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @currentleftoverid');
      $result = mysql_fetch_assoc($outputVars);
      $currentleftoverid = $result["@currentleftoverid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentleftoverid;
  }

  public function insert_leftoverSkuMapping($objSKULeftOver) {
    $currentskuleftovermappingid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objSKULeftOver->leftOverId . "'"
       . ",'" . $objSKULeftOver->skuid . "'"
       . ",'" . $objSKULeftOver->noOfUnits . "'"
       . ",'" . $objSKULeftOver->totalWeight . "'"
       . ",'" . $objSKULeftOver->totalVolume . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . "," . $this->userid . ""
       . "," . "@current_leftover_sku_mappingid";
      $query = $this->PrepareSP(constants::SP_INSERT_LEFTOVER_SKU_MAPPING, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @current_leftover_sku_mappingid');
      $result = mysql_fetch_assoc($outputVars);
      $currentskuleftovermappingid = $result["@current_leftover_sku_mappingid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentskuleftovermappingid;
  }

// </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="LEFT Over Sku Functions">
  public function get_leftover_details($objleftover) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objleftover->customerno . "'"
       . ",'" . $objleftover->factoryid . "'"
       . ",'" . $objleftover->date . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_LEFTOVER_DETAILS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_leftover_sku($objleftover) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objleftover->customerno . "'"
       . ",'" . $objleftover->leftoverid . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_LEFTOVER_SKUS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, ($ex), $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function delete_leftover($objleftover) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objleftover->customerno . "'"
       . ",'" . $objleftover->leftoverid . "'";
      $query = $this->PrepareSP(constants::SP_DELETE_UNPROCESSED_LEFTOVERDETAILS, $sp_params);
      $queryResult = $this->executeQuery($query);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function insert_leftover_unprocessed_history($objleftover) {
    $currentfactorydeliveryid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objleftover->customerno . "'"
       . ",'" . $objleftover->date_required . "'";
      $query = $this->PrepareSP(constants::SP_INSERT_UNPROCESSED_LEFTOVERDETAILS_HISTORY, $sp_params);
      $queryResult = $this->executeQuery($query);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentfactorydeliveryid;
  }

  public function delete_leftover_unprocessed_history($objleftover) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objleftover->customerno . "'"
       . ",'" . $objleftover->date_required . "'";
      $query = $this->PrepareSP(constants::SP_DELETE_UNPROCESSED_LEFTOVERDETAILS, $sp_params);
      $queryResult = $this->executeQuery($query);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  // </editor-fold>
  //
 // <editor-fold defaultstate="collapsed" desc="Efficiency Functions">
  public function get_transporter_eff($effObj) {
    $arrResult = null;
    try {
      //Prepare parameters
      $pdo = $this->CreatePDOConn();
      $sp_params = "'" . $effObj->customerno . "'"
       . ",'" . $effObj->factoryid . "'"
       . ",'" . $effObj->depotid . "'"
       . ",'" . $effObj->transporterid . "'"
       . ",'" . $effObj->zoneid . "'"
       . ",'" . $effObj->typeid . "'"
       . ",'" . $effObj->startdate . "'"
       . ",'" . $effObj->enddate . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_TRANSPORTER_EFF, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_zone_eff($effObj) {
    $arrResult = null;
    try {
      //Prepare parameters
      $pdo = $this->CreatePDOConn();
      $sp_params = "'" . $effObj->customerno . "'"
       . ",'" . $effObj->factoryid . "'"
       . ",'" . $effObj->depotid . "'"
       . ",'" . $effObj->transporterid . "'"
       . ",'" . $effObj->zoneid . "'"
       . ",'" . $effObj->typeid . "'"
       . ",'" . $effObj->startdate . "'"
       . ",'" . $effObj->enddate . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_ZONE_EFF, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_transporter_zone_eff($effObj) {
    $arrResult = null;
    try {
      //Prepare parameters
      $pdo = $this->CreatePDOConn();
      $sp_params = "'" . $effObj->customerno . "'"
       . ",'" . $effObj->factoryid . "'"
       . ",'" . $effObj->depotid . "'"
       . ",'" . $effObj->transporterid . "'"
       . ",'" . $effObj->zoneid . "'"
       . ",'" . $effObj->typeid . "'"
       . ",'" . $effObj->startdate . "'"
       . ",'" . $effObj->enddate . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_TRANSPORTER_ZONE_EFF, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_factory_eff($effObj) {
    $arrResult = null;
    try {
      //Prepare parameters
      $pdo = $this->CreatePDOConn();
      $sp_params = "'" . $effObj->customerno . "'"
       . ",'" . $effObj->factoryid . "'"
       . ",'" . $effObj->depotid . "'"
       . ",'" . $effObj->transporterid . "'"
       . ",'" . $effObj->zoneid . "'"
       . ",'" . $effObj->typeid . "'"
       . ",'" . $effObj->startdate . "'"
       . ",'" . $effObj->enddate . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_FACTORY_EFF, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function get_daterequired_eff($effObj) {
    $arrResult = null;
    try {
      //Prepare parameters
      $pdo = $this->CreatePDOConn();
      $sp_params = "'" . $effObj->customerno . "'"
       . ",'" . $effObj->factoryid . "'"
       . ",'" . $effObj->depotid . "'"
       . ",'" . $effObj->transporterid . "'"
       . ",'" . $effObj->zoneid . "'"
       . ",'" . $effObj->typeid . "'"
       . ",'" . $effObj->startdate . "'"
       . ",'" . $effObj->enddate . "'";
      $this->PrepareSP(constants::SP_GET_DATEREQUIRED_EFF, $sp_params);
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_DATEREQUIRED_EFF, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  //</editor-fold>
  //
  // <editor-fold defaultstate="collapsed" desc="Vendor Payable Functions">
  public function get_billtype_filter() {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $query = "select * from bill_type where bill_type.customerno=%d and bill_type.isdeleted = 0";
      $sql = sprintf($query, $this->customerno);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_vehiclestype_filter() {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $query = "select * from vehicle_type_master where customerno=%d and isdeleted = 0";
      $sql = sprintf($query, $this->customerno);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_movement_filter() {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $query = "select * from skutypes where customerno=%d AND isdeleted = 0";
      $sql = sprintf($query, $this->customerno);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  public function get_payment_bucket() {
    $recordset = array();
    $join = '';
    try {
      //Prepare parameters
      $query = "select * from payment_bucket where customerno=%d AND isdeleted = 0";
      $sql = sprintf($query, $this->customerno);
      $queryResult = $this->executeQuery($sql);
      $recordset = $this->get_recordSet();
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $recordset;
  }

  function checkDeliveryNo_Exists($deliveryno) {
    $isExists = 0;
    try {
      //Prepare parameters
      $query = "select delivery_no from shipment_dump where delivery_no = %d and customerno=%d and isdeleted = 0";
      $sql = sprintf($query, $deliveryno, $this->customerno);
      $queryResult = $this->executeQuery($sql);
      $rowCount = $this->get_rowCount();
      if ($rowCount > 0) {
        $isExists = 1;
      }
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $isExists;
  }

  function insert_Shipment_data($shipmentdata) {
    $currentshipmentdumpid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $shipmentdata->deliveryno . "'"
       . ",'" . $shipmentdata->lrno . "'"
       . ",'" . $shipmentdata->shipmentno . "'"
       . ",'" . $shipmentdata->costdocumentno . "'"
       . ",'" . $shipmentdata->trucktype . "'"
       . ",'" . $shipmentdata->route . "'"
       . ",'" . $shipmentdata->vehicleno . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_INSERT_SHIPMENT_DUMP, $sp_params);
      $queryResult = $this->executeQuery($query);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentshipmentdumpid;
  }

  function checkDocumentNo_Exists($documentno) {
    $isExists = 0;
    try {
      //Prepare parameters
      $query = "select bill_no from payment_dump where bill_no = %d and customerno=%d and isdeleted = 0";
      $sql = sprintf($query, $documentno, $this->customerno);
      $queryResult = $this->executeQuery($sql);
      $rowCount = $this->get_rowCount();
      if ($rowCount > 0) {
        $isExists = 1;
      }
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $isExists;
  }

  function insert_Payment_data($paymentdata) {
    $currentpaymentdumpid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $paymentdata->vendorcode . "'"
       . ",'" . $paymentdata->billno . "'"
       . ",'" . $paymentdata->clearingdocno . "'"
       . ",'" . $paymentdata->clearingdate . "'"
       . ",'" . $paymentdata->refno . "'"
       . ",'" . $paymentdata->paymentstatus . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_INSERT_PAYMENT_DUMP, $sp_params);
      $queryResult = $this->executeQuery($query);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $currentpaymentdumpid;
  }

  function getDeliveryDetails($objlrCheck) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $this->customerno . "'"
       . ",'" . $objlrCheck->deliveryno . "'"
       . ",'" . $objlrCheck->lrno . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_SHIPMENT_DETAILS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function insert_Bill_Details_Draft($objBill) {
    $current_billid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objBill->billtypeid . "'"
       . ",'" . $objBill->invoice_location_id . "'"
       . ",'" . $objBill->depot_id . "'"
       . ",'" . $objBill->vendor_id . "'"
       . ",'" . $objBill->bill_no . "'"
       . ",'" . $objBill->bill_date . "'"
       . ",'" . $objBill->description . "'"
       . ",'" . $objBill->final_bill_amt . "'"
       . ",'" . $objBill->bill_received_date . "'"
       . ",'" . $objBill->bill_processed_date . "'"
       . ",'" . $objBill->bill_sent_date . "'"
       . ",'" . $objBill->grn_no . "'"
       . ",'" . $objBill->po_no . "'"
       . ",'" . $objBill->remarks_regarding_bill . "'"
       . ",'" . $objBill->remarks_regarding_settlement . "'"
       . ",'" . $objBill->due_days . "'"
       . ",'" . $objBill->billing_status . "'"
       . ",'" . $objBill->due_days . "'"
       . ",'" . $objBill->days_for_receiving_bills . "'"
       . ",'" . $objBill->process_days . "'"
       . ",'" . $objBill->custody . "'"
       . ",'" . $objBill->total_custody . "'"
       . ",'" . $objBill->payment_done . "'"
       . ",'" . $objBill->month_sent . "'"
       . ",'" . $objBill->year_sent . "'"
       . ",'" . $objBill->payment_bucket . "'"
       . ",'" . $objBill->payment_status . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'"
       . "," . "@current_billid";
      $query = $this->PrepareSP(constants::SP_INSERT_BILLPAYABLE_DETAILS_DRAFT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @current_billid');
      $result = mysql_fetch_assoc($outputVars);
      $current_billid = $result["@current_billid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $current_billid;
  }

  public function update_Bill_Details_Draft($objBill) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objBill->billid . "'"
       . ",'" . $objBill->billtypeid . "'"
       . ",'" . $objBill->invoice_location_id . "'"
       . ",'" . $objBill->depot_id . "'"
       . ",'" . $objBill->vendor_id . "'"
       . ",'" . $objBill->bill_no . "'"
       . ",'" . $objBill->bill_date . "'"
       . ",'" . $objBill->description . "'"
       . ",'" . $objBill->final_bill_amt . "'"
       . ",'" . $objBill->bill_received_date . "'"
       . ",'" . $objBill->bill_processed_date . "'"
       . ",'" . $objBill->bill_sent_date . "'"
       . ",'" . $objBill->grn_no . "'"
       . ",'" . $objBill->po_no . "'"
       . ",'" . $objBill->remarks_regarding_bill . "'"
       . ",'" . $objBill->remarks_regarding_settlement . "'"
       . ",'" . $objBill->due_days . "'"
       . ",'" . $objBill->billing_status . "'"
       . ",'" . $objBill->due_days . "'"
       . ",'" . $objBill->days_for_receiving_bills . "'"
       . ",'" . $objBill->process_days . "'"
       . ",'" . $objBill->custody . "'"
       . ",'" . $objBill->total_custody . "'"
       . ",'" . $objBill->payment_done . "'"
       . ",'" . $objBill->month_sent . "'"
       . ",'" . $objBill->year_sent . "'"
       . ",'" . $objBill->payment_bucket . "'"
       . ",'" . $objBill->payment_status . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_UPDATE_BILLPAYABLE_DETAILS_DRAFT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function update_Bill_Details($objBill) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objBill->billid . "'"
       . ",'" . $objBill->billtypeid . "'"
       . ",'" . $objBill->invoice_location_id . "'"
       . ",'" . $objBill->depot_id . "'"
       . ",'" . $objBill->vendor_id . "'"
       . ",'" . $objBill->bill_no . "'"
       . ",'" . $objBill->bill_date . "'"
       . ",'" . $objBill->description . "'"
       . ",'" . $objBill->final_bill_amt . "'"
       . ",'" . $objBill->bill_received_date . "'"
       . ",'" . $objBill->bill_processed_date . "'"
       . ",'" . $objBill->bill_sent_date . "'"
       . ",'" . $objBill->grn_no . "'"
       . ",'" . $objBill->po_no . "'"
       . ",'" . $objBill->remarks_regarding_bill . "'"
       . ",'" . $objBill->remarks_regarding_settlement . "'"
       . ",'" . $objBill->due_days . "'"
       . ",'" . $objBill->billing_status . "'"
       . ",'" . $objBill->due_days . "'"
       . ",'" . $objBill->days_for_receiving_bills . "'"
       . ",'" . $objBill->process_days . "'"
       . ",'" . $objBill->custody . "'"
       . ",'" . $objBill->total_custody . "'"
       . ",'" . $objBill->payment_done . "'"
       . ",'" . $objBill->month_sent . "'"
       . ",'" . $objBill->year_sent . "'"
       . ",'" . $objBill->payment_bucket . "'"
       . ",'" . $objBill->payment_status . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_UPDATE_BILLPAYABLE_DETAILS, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function insert_Lr_Details_Draft($objLr) {
    $current_lrid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLr->bill_id . "'"
       . ",'" . $objLr->delivery_no . "'"
       . ",'" . $objLr->lr_no . "'"
       . ",'" . $objLr->shipment_no . "'"
       . ",'" . $objLr->cost_document_no . "'"
       . ",'" . $objLr->truck_type . "'"
       . ",'" . $objLr->route . "'"
       . ",'" . $objLr->vehicle_no . "'"
       . ",'" . $objLr->indentid . "'"
       . ",'" . $objLr->vehicle_type . "'"
       . ",'" . $objLr->movement_type . "'"
       . ",'" . $objLr->cfa_cost . "'"
       . ",'" . $objLr->shipment_freight_bill . "'"
       . ",'" . $objLr->loading . "'"
       . ",'" . $objLr->unloading . "'"
       . ",'" . $objLr->loading_charges . "'"
       . ",'" . $objLr->unloading_charges . "'"
       . ",'" . $objLr->other_charges . "'"
       . ",'" . $objLr->multidrop_charges . "'"
       . ",'" . $objLr->toll_charges . "'"
       . ",'" . $objLr->permit_charges . "'"
       . ",'" . $objLr->charges_outword . "'"
       . ",'" . $objLr->gprs . "'"
       . ",'" . $objLr->noentry_charges . "'"
       . ",'" . $objLr->auto_charges . "'"
       . ",'" . $objLr->lr_charges . "'"
       . ",'" . $objLr->tt_penalty . "'"
       . ",'" . $objLr->any_deduction . "'"
       . ",'" . $objLr->total_delivery_amount . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'"
       . "," . "@current_lrid";
      $query = $this->PrepareSP(constants::SP_INSERT_LR_DETAILS_DRAFT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @current_lrid');
      $result = mysql_fetch_assoc($outputVars);
      $current_lrid = $result["@current_lrid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $current_lrid;
  }

  public function insert_Lr_Details($objLr) {
    $current_lrid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLr->bill_id . "'"
       . ",'" . $objLr->delivery_no . "'"
       . ",'" . $objLr->lr_no . "'"
       . ",'" . $objLr->shipment_no . "'"
       . ",'" . $objLr->cost_document_no . "'"
       . ",'" . $objLr->truck_type . "'"
       . ",'" . $objLr->route . "'"
       . ",'" . $objLr->vehicle_no . "'"
       . ",'" . $objLr->indentid . "'"
       . ",'" . $objLr->vehicle_type . "'"
       . ",'" . $objLr->movement_type . "'"
       . ",'" . $objLr->cfa_cost . "'"
       . ",'" . $objLr->shipment_freight_bill . "'"
       . ",'" . $objLr->loading . "'"
       . ",'" . $objLr->unloading . "'"
       . ",'" . $objLr->loading_charges . "'"
       . ",'" . $objLr->unloading_charges . "'"
       . ",'" . $objLr->other_charges . "'"
       . ",'" . $objLr->multidrop_charges . "'"
       . ",'" . $objLr->toll_charges . "'"
       . ",'" . $objLr->permit_charges . "'"
       . ",'" . $objLr->charges_outword . "'"
       . ",'" . $objLr->gprs . "'"
       . ",'" . $objLr->noentry_charges . "'"
       . ",'" . $objLr->auto_charges . "'"
       . ",'" . $objLr->lr_charges . "'"
       . ",'" . $objLr->tt_penalty . "'"
       . ",'" . $objLr->any_deduction . "'"
       . ",'" . $objLr->total_delivery_amount . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'"
       . "," . "@current_lrid";
      $query = $this->PrepareSP(constants::SP_INSERT_LR_DETAILS, $sp_params);
      $queryResult = $this->executeQuery($query);
      $outputVars = $this->executeQuery('SELECT @current_lrid');
      $result = mysql_fetch_assoc($outputVars);
      $current_lrid = $result["@current_lrid"];
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $current_lrid;
  }

  public function delete_Lr_Details_Draft($objLr) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLr->lrid . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_DELETE_LR_DETAILS_DRAFT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_Lr_Details($objLr) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLr->lrid . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_DELETE_LR_DETAILS, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_Bill_Draft($objLr) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLr->lrid . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_DELETE_BILL_DRAFT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function delete_Bill($objLr) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLr->lrid . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_DELETE_BILL, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  function get_Lr_Details_Draft($objLr) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objLr->lrid . "'"
       . ",'" . $objLr->billid . "'"
       . ",'" . $this->customerno . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_LR_DETAILS_DRAFT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  function get_Lr_Details($objLr) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objLr->lrid . "'"
       . ",'" . $objLr->billid . "'"
       . ",'" . $objLr->customerno . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_LR_DETAILS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function update_Lr_Details_Draft($objLr) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLr->lrid . "'"
       . ",'" . $objLr->delivery_no . "'"
       . ",'" . $objLr->lr_no . "'"
       . ",'" . $objLr->shipment_no . "'"
       . ",'" . $objLr->cost_document_no . "'"
       . ",'" . $objLr->truck_type . "'"
       . ",'" . $objLr->route . "'"
       . ",'" . $objLr->vehicle_no . "'"
       . ",'" . $objLr->indentid . "'"
       . ",'" . $objLr->vehicle_type . "'"
       . ",'" . $objLr->movement_type . "'"
       . ",'" . $objLr->cfa_cost . "'"
       . ",'" . $objLr->shipment_freight_bill . "'"
       . ",'" . $objLr->loading . "'"
       . ",'" . $objLr->unloading . "'"
       . ",'" . $objLr->loading_charges . "'"
       . ",'" . $objLr->unloading_charges . "'"
       . ",'" . $objLr->other_charges . "'"
       . ",'" . $objLr->multidrop_charges . "'"
       . ",'" . $objLr->toll_charges . "'"
       . ",'" . $objLr->permit_charges . "'"
       . ",'" . $objLr->charges_outword . "'"
       . ",'" . $objLr->gprs . "'"
       . ",'" . $objLr->noentry_charges . "'"
       . ",'" . $objLr->auto_charges . "'"
       . ",'" . $objLr->lr_charges . "'"
       . ",'" . $objLr->tt_penalty . "'"
       . ",'" . $objLr->any_deduction . "'"
       . ",'" . $objLr->total_delivery_amount . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_UPDATE_LR_DETAILS_DRAFT, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  public function update_Lr_Details($objLr) {
    $noOfRowsAffected = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLr->lrid . "'"
       . ",'" . $objLr->delivery_no . "'"
       . ",'" . $objLr->lr_no . "'"
       . ",'" . $objLr->shipment_no . "'"
       . ",'" . $objLr->cost_document_no . "'"
       . ",'" . $objLr->truck_type . "'"
       . ",'" . $objLr->route . "'"
       . ",'" . $objLr->vehicle_no . "'"
       . ",'" . $objLr->indentid . "'"
       . ",'" . $objLr->vehicle_type . "'"
       . ",'" . $objLr->movement_type . "'"
       . ",'" . $objLr->cfa_cost . "'"
       . ",'" . $objLr->shipment_freight_bill . "'"
       . ",'" . $objLr->loading . "'"
       . ",'" . $objLr->unloading . "'"
       . ",'" . $objLr->loading_charges . "'"
       . ",'" . $objLr->unloading_charges . "'"
       . ",'" . $objLr->other_charges . "'"
       . ",'" . $objLr->multidrop_charges . "'"
       . ",'" . $objLr->toll_charges . "'"
       . ",'" . $objLr->permit_charges . "'"
       . ",'" . $objLr->charges_outword . "'"
       . ",'" . $objLr->gprs . "'"
       . ",'" . $objLr->noentry_charges . "'"
       . ",'" . $objLr->auto_charges . "'"
       . ",'" . $objLr->lr_charges . "'"
       . ",'" . $objLr->tt_penalty . "'"
       . ",'" . $objLr->any_deduction . "'"
       . ",'" . $objLr->total_delivery_amount . "'"
       . ",'" . $this->customerno . "'"
       . ",'" . $this->todaysdate . "'"
       . ",'" . $this->userid . "'";
      $query = $this->PrepareSP(constants::SP_UPDATE_LR_DETAILS, $sp_params);
      $queryResult = $this->executeQuery($query);
      $noOfRowsAffected = $this->get_affectedRows();
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $noOfRowsAffected;
  }

  function get_Bill_Details_Draft($objLr) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objLr->billid . "'"
       . ",'" . $objLr->factoryid . "'"
       . ",'" . $this->customerno . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_BILL_DETAILS_DRAFT, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  function get_Bill_Details($objLr) {
    $arrResult = null;
    try {
      $pdo = $this->CreatePDOConn();
      //Prepare parameters
      $sp_params = "'" . $objLr->billid . "'"
       . ",'" . $objLr->factoryid . "'"
       . ",'" . $this->customerno . "'";
      $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_BILL_DETAILS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
      $this->ClosePDOConn($pdo);
    }
    catch (Exception $ex) {
      //Log
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $arrResult;
  }

  public function insert_BillTracker($objLr) {
    $current_billid = 0;
    try {
      //Prepare parameters
      $sp_params = "'" . $objLr->billid . "'"
       . ",'" . $objLr->customerno . "'";
      $query = $this->PrepareSP(constants::SP_INSERT_VENDOR_PAYABLE, $sp_params);
      $queryResult = $this->executeQuery($query);
    }
    catch (Exception $ex) {
      $log = new Log();
      $log->createlog($this->customerno, $ex, $this->userid, constants::TMS, __FUNCTION__);
    }
    return $current_billid;
  }

// <editor-fold>
  // <editor-fold defaultstate="collapsed" desc="Helper Functions">
  function getSMSCount($customerno) {
    $smscount = 0;
    $pdo = $this->CreatePDOConn();
    $sqlSmsGet = sprintf("SELECT smsleft FROM " . SPEEDDB . ".customer WHERE customerno=%d", $customerno);
    $arrResult = $pdo->query($sqlSmsGet)->fetch(PDO::FETCH_ASSOC);
    $this->ClosePDOConn($pdo);
    if (count($arrResult) == 1) {
      $smscount = $arrResult['smsleft'];
    }
    return $smscount;
  }

  function updateSMSCount($existingSMSCount, $smsmessage, $customerno) {
    $smsconsumed = 0;
    $smsleft = 0;
    $smslength = strlen($smsmessage);
    $divide = floor($smslength / constants::PER_SMS_CHARACTERS);
    $mod = $smslength % constants::PER_SMS_CHARACTERS;
    if ($mod > 0) {
      $smsconsumed = $divide + 1;
    } else if ($mod == 0) {
      $smsconsumed = $divide;
    }
    $smsleft = $existingSMSCount - $smsconsumed;
    $pdo = $this->CreatePDOConn();
    $sqlSmsUpdate = sprintf("UPDATE " . SPEEDDB . ".customer SET smsleft=%d WHERE customerno=%d", $smsleft, $customerno);
    $pdo->query($sqlSmsUpdate);
    $this->ClosePDOConn($pdo);
  }

  function insertSMSLog($phone, $message, $response, $proposedindentid, $issmsSent, $customerno, $todaysdate) {
    $smsid = 0;
    $pdo = $this->CreatePDOConn();
    $sp_params = "'" . $phone . "'"
     . ",'" . $message . "'"
     . ",'" . $response . "'"
     . ",'" . $proposedindentid . "'"
     . ",'" . $issmsSent . "'"
     . ",'" . $customerno . "'"
     . ",'" . $todaysdate . "'"
     . "," . '@smsid';
    $queryCallSP = $this->PrepareSP(constants::SP_INSERT_SMSLOG_TMS, $sp_params);
    $pdo->query($queryCallSP);
    $this->ClosePDOConn($pdo);
    $outputParamsQuery = "SELECT @smsid AS smsid";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
    if (count($outputResult) > 0) {
      $smsid = $outputResult['smsid'];
    }
    return $smsid;
  }

//</editor-fold>
}

?>