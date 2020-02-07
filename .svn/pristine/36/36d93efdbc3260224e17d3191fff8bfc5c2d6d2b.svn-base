<?php

class VODevices {
    
}

class api {

    var $status;
    var $status_time;

    // construct
    function __construct() {
        $this->db = new database(DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
        $this->dbspeed = new database(DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD, DATABASE_SPEED);
    }
  ////////////////////// Customer - add/edit/delete //////////////////////////////////////////// 
    function add_pickup_customer($customers){
       $result = array();
       $today= date("Y-m-d H:i:s");
       $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey='" . $customers->userkey."'";
        $res = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($res);
        $customers->customer_no = $row['customerno'];
        
       $sql = "INSERT INTO " . DATABASE_NAME . ".master_customer(uid, name, customer_no,timestamp,userkey)VALUES('$customers->uid','$customers->name','$customers->customer_no','$today','$customers->userkey')";
       $this->db->query($sql, __FILE__, __LINE__);
       $lastid = $this->db->last_insert_id();
        if($lastid!=''){
          $result['Status'] = 'Success';
          $result['action'] = $customers->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
    
    function edit_pickup_customer($customers){
       $result = array();
       $today= date("Y-m-d H:i:s");
       $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey='" . $customers->userkey."'";
        $res = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($res);
        $customers->customer_no = $row['customerno'];
        
       $sql = "update ". DATABASE_NAME .".master_customer set name= '".$customers->name."' where customer_no=".$customers->customer_no." AND uid=".$customers->uid;    
       $lastid = $this->db->query($sql, __FILE__, __LINE__);
        if($lastid > 0){
          $result['Status'] = 'Success';
          $result['action'] = $customers->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
    
      function delete_pickup_customer($customers){
        $result = array();
        $today= date("Y-m-d H:i:s");
        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey='" . $customers->userkey."'";
        $res = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($res);
        $customers->customer_no = $row['customerno'];
        
        $sql = "update ". DATABASE_NAME .".master_customer set isdeleted=1  where customer_no=".$customers->customer_no." AND uid=".$customers->uid;    
        $lastid = $this->db->query($sql, __FILE__, __LINE__);
        
        if($lastid>0){
          $result['Status'] = 'Success';
          $result['action'] = $customers->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
    
//////////////vendor-add/edit/delete/////////////////
    function add_pickup_vendor($vendor){
       $result = array();
       $today= date("Y-m-d H:i:s");
       
       $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey='" . $vendor->userkey."'";
        $res = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($res );
        $vendor->customer_no = $row['customerno'];
        
       $sql = "INSERT INTO " . DATABASE_NAME . ".master_vendor(vendorid, name, address,pincode,customer_no, userkey,timestamp)VALUES('$vendor->vendorid','$vendor->name','$vendor->address','$vendor->pincode','$vendor->customer_no','$vendor->userkey','$today')";
       $this->db->query($sql, __FILE__, __LINE__);
       $lastid = $this->db->last_insert_id();
        if($lastid!=''){
          $result['Status'] = 'Success';
          $result['action'] = $vendor->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
    
    function edit_pickup_vendor($vendor){
       $result = array();
       $today= date("Y-m-d H:i:s");
       $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey='" . $vendor->userkey."'";
        $res = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($res);
        $vendor->customer_no = $row['customerno'];
        
       $sql = "update ". DATABASE_NAME .".master_vendor set name= '".$vendor->name."',address='".$vendor->address."',pincode='".$vendor->pincode."'  where customer_no=".$vendor->customer_no." AND vendorid=".$vendor->vendorid;    
       $lastid = $this->db->query($sql, __FILE__, __LINE__);
        if($lastid > 0){
          $result['Status'] = 'Success';
          $result['action'] = $vendor->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
    
    function delete_pickup_vendor($vendor){
        $result = array();
        $today= date("Y-m-d H:i:s");
        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey='" . $vendor->userkey."'";
        $res = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($res);
        $vendor->customer_no = $row['customerno'];
        
        $sql = "update ". DATABASE_NAME .".master_vendor set isdeleted=1  where customer_no=".$vendor->customer_no." AND vendorid=".$vendor->vendorid;    
        $lastid = $this->db->query($sql, __FILE__, __LINE__);
        
        if($lastid>0){
          $result['Status'] = 'Success';
          $result['action'] = $vendor->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
    
    //////////////pickupboy-add/edit/delete/////////////////
     function add_pickup_pcb($pcb){
       $result = array();
       $today= date("Y-m-d H:i:s");
       $sql = "INSERT INTO " . DATABASE_NAME . ".master_pickupboy(pincode,userid)VALUES('$pcb->pincode','$pcb->userid')";
       $this->db->query($sql, __FILE__, __LINE__);
       $lastid = $this->db->last_insert_id();
        if($lastid!=''){
          $result['Status'] = 'Success';
          $result['action'] = $pcb->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
    
    function edit_pickup_pcb($pcb){
       $result = array();
       $today= date("Y-m-d H:i:s");
       $sql = "update ". DATABASE_NAME .".master_pickupboy set pincode= '".$pcb->pincode."' where userid='".$pcb->userid."'";    
       $lastid = $this->db->query($sql, __FILE__, __LINE__);
        if($lastid > 0){
          $result['Status'] = 'Success';
          $result['action'] = $pcb->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
    
    function delete_pickup_pcb($pcb){
        $result = array();
        $today= date("Y-m-d H:i:s");
        $sql = "update ". DATABASE_NAME .".master_pickupboy set isdeleted=1  where userid='".$pcb->userid."'";    
        $lastid = $this->db->query($sql, __FILE__, __LINE__);
        
        if($lastid>0){
          $result['Status'] = 'Success';
          $result['action'] = $pcb->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
         //////////////pickupboy shipment-add/edit/delete/////////////////
    function add_pickup_shipment($ship){
        $result1 = array();
        
        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey='" . $ship->userkey."'";
        $res = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($res);
        $ship->customer_no = $row['customerno'];
        
        
        $Query = "SELECT pincode FROM ". DATABASE_NAME.".master_vendor WHERE vendorid=".$ship->vendorid;
        $result = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($result);
        $pincode = $row['pincode'];
        
        $Querypid = "SELECT userid FROM ". DATABASE_NAME.".master_pickupboy WHERE pincode='".$pincode."'";
        $result2 = $this->dbspeed->query($Querypid, __FILE__, __LINE__);
        $row1 = $this->dbspeed->fetch_array($result2);
        $pickupid = $row1['userid'];
        if($pickupid!=""){
            $pickupid1 = $pickupid;
        }
        else{
            $pickupid1 =0;
        }
        $sql = "INSERT INTO " . DATABASE_NAME . ".master_shipment(customerid,vendorid,shipmentno,pickupid, customer_no, userkey )VALUES('$ship->customerid','$ship->vendorid','$ship->shipmentno','$pickupid1','$ship->customer_no','$ship->userkey')";
        $this->db->query($sql, __FILE__, __LINE__);
        $lastid = $this->db->last_insert_id();
        if($lastid!=''){
           $result1['Status'] = 'Success';
           $result1['action'] = $ship->action;
        }else{
            $result1['Status'] = 'Failure';
        }
        return $result1;
    }
    
    
     function edit_pickup_shipment($ship){
       $result = array();
       $today= date("Y-m-d H:i:s");
       
       $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey='" . $ship->userkey."'";
        $res = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($res);
        $ship->customer_no = $row['customerno'];
        
        
       $sql = "update ". DATABASE_NAME .".master_shipment set pickupid= '".$ship->pickupid."' where shipmentno='".$ship->shipmentno."'";    
       $lastid = $this->db->query($sql, __FILE__, __LINE__);
        if($lastid > 0){
          $result['Status'] = 'Success';
          $result['action'] = $ship->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
    
    function delete_pickup_shipment($ship){
        $result = array();
        $today= date("Y-m-d H:i:s");
        
        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey='" . $ship->userkey."'";
        $res = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($res);
        $ship->customer_no = $row['customerno'];
        
        
        $sql = "update ". DATABASE_NAME .".master_shipment set isdeleted=1  where shipmentno='".$ship->shipmentno."'";    
        $lastid = $this->db->query($sql, __FILE__, __LINE__);
        if($lastid>0){
          $result['Status'] = 'Success';
          $result['action'] = $ship->action;
        }else{
           $result['Status'] = 'Failure';
        }
        return $result;
    }
    
}

?>