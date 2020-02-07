<?php

include_once '../../lib/system/VersionedManager.php';

class MappingManager extends VersionedManager {

 function __construct($customerno) {
  // Constructor.
  parent::__construct($customerno);
 }

 public function mapVehZoneSlot($data) {
  $values = array();
  foreach ($data as $arr) {
   $values[] = " ({$arr['customerno']}, {$arr['userid']}, '{$arr['entrytime']}', '{$arr['mapdate']}', {$arr['vehicleid']}, {$arr['zoneid']}, {$arr['slotid']}) ";
   $deleteQuery = "update routing_map set isdeleted=1 where customerno=$this->_Customerno  and isdeleted=0";
   $this->_databaseManager->executeQuery($deleteQuery);
  }
  $insert = implode(', ', $values);
  $insertQuery = "insert into routing_map(customerno, userid, entrytime, mapdate, vehicleid, zoneid, slotid) values $insert";
  $this->_databaseManager->executeQuery($insertQuery);
  $affected_row = $this->_databaseManager->get_affectedRows();
  if ($affected_row > 0) {
   return true;
  } else {
   return false;
  }
 }

 public function mapVehZoneSlot_pickup($data) {
  $values = array();
  foreach ($data as $arr) {
   $values[] = " ({$arr['customerno']}, {$arr['userid']}, '{$arr['entrytime']}', '{$arr['mapdate']}', {$arr['vehicleid']}, {$arr['zoneid']}, {$arr['slotid']}) ";
   $deleteQuery = "update pickup_map set isdeleted=1 where customerno=$this->_Customerno and zoneid={$arr['zoneid']} and slotid={$arr['slotid']} and isdeleted=0";
   $this->_databaseManager->executeQuery($deleteQuery);
  }
  $insert = implode(', ', $values);
  $insertQuery = "insert into pickup_map(customerno, userid, entrytime, mapdate, pickupboy, zoneid, slotid) values $insert";
  $this->_databaseManager->executeQuery($insertQuery);
  $affected_row = $this->_databaseManager->get_affectedRows();
  if ($affected_row > 0) {
   return true;
  } else {
   return false;
  }
 }

 public function getVehZoneSlot_js_arr() {
  $groupQ = '';
  if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
   $grpid = (int) $_SESSION['groupid'];
   $groupQ = " AND v.groupid = $grpid ";
  }
  $selectQuery = "select a.* from routing_map as a
            left join vehicle as v on v.vehicleid=a.vehicleid and v.customerno=a.customerno
            where a.customerno=$this->_Customerno and a.isdeleted=0 $groupQ";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $data = array();
   while ($row = $this->_databaseManager->get_nextRow()) {
    $key = "z{$row['zoneid']}_slot{$row['slotid']}";
    $data[$key][] = array($row['vehicleid']);
   }
   return $data;
  } else {
   return false;
  }
 }

 public function getVehZoneSlot_js_arr_pickup() {
  $groupQ = '';
  if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
   $grpid = (int) $_SESSION['groupid'];
   $groupQ = " AND v.groupid = $grpid ";
  }
  $selectQuery = "select a.* from " . SPEEDDB . ".pickup_map as a
            left join user as u on u.userid=a.pickupboy and u.customerno=a.customerno
            where a.customerno=$this->_Customerno and a.isdeleted=0 $groupQ";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $data = array();
   while ($row = $this->_databaseManager->get_nextRow()) {
    $key = "z{$row['zoneid']}_slot{$row['slotid']}";
    $data[$key][] = array($row['pickupboy']);
   }
   return $data;
  } else {
   return false;
  }
 }

 public function getCustomerZones() {
  $groupQ = '';
  if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
   $grpid = (int) $_SESSION['groupid'];
   $groupQ = " AND a.groupid = $grpid ";
  }
  $selectQuery = "select zone_id,zonename,concat(start_lat,',',start_long) as startll from " . DB_DELIVERY . ".zone_master as a where a.customerno=$this->_Customerno and isdeleted=0 $groupQ";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $data = array();
   while ($row = $this->_databaseManager->get_nextRow()) {
    $data[$row['zone_id']] = array(
       'zname' => $row['zonename'],
       'startll' => $row['startll'],
        'zoneid' => $row['zone_id']
    );
   }
   return $data;
  } else {
   return null;
  }
 }

 public function getCustomerZones_pickup() {
  $groupQ = '';
  if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
   $grpid = (int) $_SESSION['groupid'];
   $groupQ = " AND a.groupid = $grpid ";
  }
  $selectQuery = "select zone_id,zonename,concat(start_lat,',',start_long) as startll from " . DB_PICKUP . ".zone_master as a where a.customerno=$this->_Customerno and isdeleted=0 $groupQ";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $data = array();
   while ($row = $this->_databaseManager->get_nextRow()) {
    $data[$row['zone_id']] = array(
       'zname' => $row['zonename'],
       'startll' => $row['startll']
    );
   }
   return $data;
  } else {
   return null;
  }
 }

 public function getCustomerAreas($areaname, $limit) {
  $groupQ = '';
  if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
   $grpid = (int) $_SESSION['groupid'];
   $groupQ = " AND z.groupid = $grpid ";
  }
  $areaname = Sanitise::String($areaname);
  $selectQuery = "select a.zone_id,a.area_id,a.areaname from " . DB_DELIVERY . ".area_master as a
            inner join " . DB_DELIVERY . ".zone_master as z on a.customerno=z.customerno and a.zone_id=z.zone_id and z.isdeleted=0
            where a.customerno=$this->_Customerno and a.isdeleted=0 $groupQ and a.areaname like '%$areaname%' limit $limit";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $data = array();
   while ($row = $this->_databaseManager->get_nextRow()) {
    $data[] = array(
       'id' => $row['area_id'],
       'value' => $row['areaname'],
    );
   }
   return $data;
  } else {
   return null;
  }
 }

 public function getCustomerPickupAreas($areaname, $limit) {
  $groupQ = '';
  if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
   $grpid = (int) $_SESSION['groupid'];
   $groupQ = " AND z.groupid = $grpid ";
  }
  $areaname = Sanitise::String($areaname);
  $selectQuery = "select a.zone_id,a.area_id,a.areaname from " . DB_PICKUP . ".area_master as a
            inner join " . DB_PICKUP . ".zone_master as z on a.customerno=z.customerno and a.zone_id=z.zone_id and z.isdeleted=0
            where a.customerno=$this->_Customerno and a.isdeleted=0 $groupQ and a.areaname like '%$areaname%' limit $limit";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $data = array();
   while ($row = $this->_databaseManager->get_nextRow()) {
    $data[] = array(
       'id' => $row['area_id'],
       'value' => $row['areaname'],
    );
   }
   return $data;
  } else {
   return null;
  }
 }

 public function getCustomer_Zones($zonename, $limit) {
  $groupQ = '';
  if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
   $grpid = (int) $_SESSION['groupid'];
   $groupQ = " AND z.groupid = $grpid ";
  }
  $zonename = Sanitise::String($zonename);
  $selectQuery = "select a.zone_id,a.zonename from " . DB_DELIVERY . ".zone_master as a
                    where a.customerno=$this->_Customerno and a.isdeleted=0 $groupQ and a.zonename like '%$zonename%' limit $limit";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $data = array();
   while ($row = $this->_databaseManager->get_nextRow()) {
    $data[] = array(
       'id' => $row['zone_id'],
       'value' => $row['zonename'],
    );
   }
   return $data;
  } else {
   return null;
  }
 }

 public function getCustomerPickupZones($zonename, $limit) {
  $groupQ = '';
  if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
   $grpid = (int) $_SESSION['groupid'];
   $groupQ = " AND z.groupid = $grpid ";
  }
  $zonename = Sanitise::String($zonename);
  $selectQuery = "select a.zone_id,a.zonename from " . DB_PICKUP . ".zone_master as a
                    where a.customerno=$this->_Customerno and a.isdeleted=0 $groupQ and a.zonename like '%$zonename%' limit $limit";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $data = array();
   while ($row = $this->_databaseManager->get_nextRow()) {
    $data[] = array(
       'id' => $row['zone_id'],
       'value' => $row['zonename'],
    );
   }
   return $data;
  } else {
   return null;
  }
 }

 public function getCustomerAreas_pickup($areaname, $limit) {
  $groupQ = '';
  if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
   $grpid = (int) $_SESSION['groupid'];
   $groupQ = " AND z.groupid = $grpid ";
  }
  $areaname = Sanitise::String($areaname);
  $selectQuery = "select a.zone_id,a.area_id,a.areaname from " . DB_PICKUP . ".area_master as a
            inner join " . DB_DELIVERY . ".zone_master as z on a.customerno=z.customerno and a.zone_id=z.zone_id and z.isdeleted=0
            where a.customerno=$this->_Customerno and a.isdeleted=0 $groupQ and a.areaname like '%$areaname%' limit $limit";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $data = array();
   while ($row = $this->_databaseManager->get_nextRow()) {
    $data[] = array(
       'id' => $row['area_id'],
       'value' => $row['areaname'],
    );
   }
   return $data;
  } else {
   return null;
  }
 }

 public function get_startll($vehid, $slot,$mapdate,$zoneid) {
    if(is_array($slot)){
       $slot = implode(",", $slot); 
    }
    if(is_array($zoneid)){
       $zoneid = implode(",", $zoneid); 
    }
    
    
    
  $selectQuery = "select a.zoneid, a.slotid, concat(b.start_lat,',',b.start_long) as startll from routing_map as a";
  $selectQuery .= " inner join  " . DB_DELIVERY . ".zone_master as b on a.zoneid=b.zone_id and a.customerno=b.customerno ";
  //$selectQuery .= " inner join  " . DB_DELIVERY . ".zone_master as b on a.zoneid=b.zone_id and a.customerno=b.customerno and b.isdeleted=0 ";
  //$selectQuery .= " where a.customerno=$this->_Customerno and a.vehicleid=$vehid and a.slotid=$slot and a.isdeleted=0";
  //$selectQuery .= " where a.customerno=$this->_Customerno and a.vehicleid=$vehid and a.slotid =$slot and a.mapdate='".$mapdate."' and a.zoneid =$zoneid";
  $selectQuery .= " where a.customerno=$this->_Customerno and a.vehicleid=$vehid and a.slotid IN( ".$slot.") and a.mapdate='".$mapdate."' and a.zoneid IN(".$zoneid.")";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   while ($row = $this->_databaseManager->get_nextRow()) {
    return $row['startll'];
   }
  } else {
   return null;
  }
 }

 public function getMatStartPoint($customerno) {
  $selectQuery = "select * from " . DB_DELIVERY . ".map_initialise where customerno = $customerno and isdeleted = 0";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   while ($row = $this->_databaseManager->get_nextRow()) {
    $start['lat'] = $row['startlat'];
    $start['long'] = $row['startlong'];
    $start['location'] = $row['location'];
    return $start;
   }
  } else {
   return null;
  }
 }

 public function get_startll_pickup($vehid, $slot) {
  $selectQuery = "select concat(b.start_lat,',',b.start_long) as startll from " . SPEEDDB . ".pickup_map as a";
  $selectQuery .= " inner join  " . DB_PICKUP . ".zone_master as b on 1=b.zone_id and a.customerno=b.customerno and b.isdeleted=0 ";
  $selectQuery .= " where a.customerno=$this->_Customerno and a.pickupboy=$vehid  and a.isdeleted=0";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   while ($row = $this->_databaseManager->get_nextRow()) {
    return $row['startll'];
   }
  } else {
   return null;
  }
 }

 public function getzone_slotVeh($slotid, $vehid) {
  $selectQuery = "select zoneid, slotid from routing_map as a ";
  $selectQuery .= " where a.customerno=$this->_Customerno and a.vehicleid=$vehid and a.slotid=$slotid and a.isdeleted=0";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   while ($row = $this->_databaseManager->get_nextRow()) {
    return $row['zoneid'];
   }
  } else {
   return null;
  }
 }

 public function Orders_by_vehicle($zoneid, $slotid, $date, $vehicleid) {
  $Query = "SELECT b.lat, b.longi, b.id, b.order_id  FROM " . DB_DELIVERY . ".order_route_sequence as a ";
  $Query .= " inner join " . DB_DELIVERY . ".master_orders as b on b.id=a.order_id";
  $Query .= " where a.vehicle_id=$vehicleid and b.customerno=$this->_Customerno and b.delivery_date='$date' and b.fenceid=$zoneid and b.slot=$slotid";
  $this->_databaseManager->executeQuery($Query);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $orders = array();
   while ($row = $this->_databaseManager->get_nextrow()) {
    $orders[$row['id']] = array(
       'lat' => $row['lat'],
       'longi' => $row['longi'],
       'vehicle_id' => $vehicleid,
       'order_id' => $row['id'],
       'cust_order_id' => $row['order_id'],
    );
   }
   return $orders;
  } else {
   return false;
  }
 }

 /* for localbanya */

 public function getHeatMap() {
  $selectQuery = "select pkey, lat,lng, shipping_address, area from " . DB_DELIVERY . ".localbanya_user_address";
  $this->_databaseManager->executeQuery($selectQuery);
  if ($this->_databaseManager->get_rowCount() > 0) {
   $data = array();
   while ($row = $this->_databaseManager->get_nextRow()) {
    $address = "{$row['pkey']}, {$row['shipping_address']}<br/> {$row['area']}";
    $data[] = array(
       'lat' => $row['lat'],
       'lng' => $row['lng'],
       'address' => $address
    );
   }
   return $data;
  } else {
   return null;
  }
 }

}
