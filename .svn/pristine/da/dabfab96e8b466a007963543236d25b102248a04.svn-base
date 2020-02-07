<?php
/**
 * class of mobility-module
 */

class Mobility extends DatabaseMobilityManager{
    
    public function __construct($customerno,$userid){
        parent::__construct($customerno,$userid);
        $this->customerno = $customerno;
        $this->userid = $userid;
        $this->today = date("Y-m-d H:i:s");
    }
    
    public function insert_feedbackdata($question,$options){
        $count = count($options);
        $Query = "Insert into feedback_master (customerno,feedback_question,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query,Sanitise::String($question)); 
        $this->executeQuery($SQL);
        $feedbackid = $this->get_insertedId();
        
        if($count>0){
            for($i=0; $i < $count; $i++){
                if($options[$i]!="" || $options[$i]!=NULL){
                    $Query = "Insert into feedback_options (customerno,fid,options_value,entrytime,addedby) VALUES($this->customerno,%d,'%s','$this->today',$this->userid)";
                    $SQL = sprintf($Query,Sanitise::String($feedbackid),Sanitise::String($options[$i])); 
                    $this->executeQuery($SQL);
                }
            }
        }
    }
    
    public function update_feedbackdata($question,$options,$fid,$optid){
        $optcount = count($options);
        $Query = "update feedback_master set feedback_question='".$question."' ,updatedtime='".$this->today."', updated_by='".$this->userid."' where fid=".$fid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if($optcount > 0){
            for($i=0; $i<$optcount; $i++){
              
                if(!empty($optid[$i])){
                    $Query = "update feedback_options set options_value='".$options[$i]."' ,updatedtime='".$this->today."', updated_by='".$this->userid."' where oid=".$optid[$i];
                    $SQL = sprintf($Query);
                    $this->executeQuery($SQL);
                }else{
                    $Query = "Insert into feedback_options (customerno,fid,options_value,entrytime,addedby) VALUES($this->customerno,%d,'%s','$this->today',$this->userid)";                    
                    $SQL = sprintf($Query,Sanitise::String($fid),Sanitise::String($options[$i])); 
                    $this->executeQuery($SQL);
                }
            }
        }
    }

    /**
     * To add new city in city-master
     * @param string $cityname
     */
    public function insert_citydata($cityname){
        $Query = "Insert into city_master (customerno,cityname,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query,Sanitise::String($cityname));
        $this->executeQuery($SQL);
    }
    
    
     public function update_citydata($cityname,$cid){
        $Query = "update city_master set cityname='".$cityname."' ,updatedtime='".$this->today."', updated_by='".$this->userid."' where cityid=".$cid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }
    
    
    
    public function getcitydata_byid($id){
        
        $Query = "SELECT * FROM city_master WHERE customerno=$this->customerno AND cityid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
         while ($row = $this->get_nextRow())            
           {
               $citydata[] = array(
                   'id' => $row['cityid'],
                   'cityname' => $row['cityname'],
                );
            }
            return $citydata;
        }
        return null;
    }
    
    public function getfeedbackdata_byid($id){
        
        $Query = "SELECT * FROM feedback_master WHERE customerno=$this->customerno AND fid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
         while ($row = $this->get_nextRow())            
           {
               $questiondata[] = array(
                   'id' => $row['fid'],
                   'question' => $row['feedback_question'],
                );
            }
            return $questiondata;
        }
        return null;
    }
    
    
    public function getoptiondata($fid){
        
        $Query = "SELECT * FROM feedback_options WHERE customerno=$this->customerno AND fid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($fid));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
         while ($row = $this->get_nextRow())            
           {
               $optionsdata[] = array(
                   'id' => $row['oid'],
                   'options_val' => $row['options_value'],
                );
            }
            return $optionsdata;
        }
        return null;
    }
    
    
    /**
     * to check if city name exists in city-master
     * @param string $cityname
     * @return boolean
     */
    public function is_city_exists($cityname){
        $Query = "SELECT cityid FROM city_master WHERE customerno=$this->customerno AND cityname='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($cityname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
    }
    
        /**
     * to check if city name exists in city-master
     * @param string $cityname
     * @return boolean
     */
   
    
    public function is_cityid_exists($cityid){
        $SQL = "SELECT cityid FROM city_master WHERE customerno=$this->customerno AND cityid=$cityid AND isdeleted=0";
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
           
    }

     /**
     * to check if location name exists in location-master
     * @param string $location
     * @return boolean
     */
    public function is_location_exists($locationname,$cityid){
        $Query = "SELECT location FROM location_master WHERE customerno=$this->customerno AND location='%s' and cityid=%d AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($locationname), $cityid);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
           
    }
    
    /**
     * To add new location in location-master
     * @param string $locationname
     */
    public function insert_locationdata($cityid,$locationname){
        $Query = "Insert into location_master (location,customerno,cityid,entrytime,addedby) VALUES('%s',$this->customerno,%d,'$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($locationname), $cityid,$this->userid);
        $this->executeQuery($SQL);
    }
    
     public function update_locationdata($cityid,$lname,$locid){
        $Query = "update location_master set cityid='".$cityid."',location='".$lname."',updatedtime='".$this->today."', updated_by='".$this->userid."' where locid=".$locid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }
    
    
    public function getlocationdata_byid($id){
        $Query = "SELECT lm.locid, lm.cityid,cm.cityname,lm.location FROM location_master as lm left join city_master as cm on cm.cityid = lm.cityid WHERE  lm.customerno=$this->customerno AND lm.locid='%d' AND lm.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
         while ($row = $this->get_nextRow())            
           {
               $locdata[] = array(
                   'locid' => $row['locid'],
                   'cityid' => $row['cityid'],
                   'cityname' => $row['cityname'],
                   'location' => $row['location'],
                );
            }
            return $locdata;
        }
        return null;
    }
    /**
     * to check if trackie  emailid exists in trackie-master
     * @param string $emailid
     * @return boolean
     */
    public function is_trackie_email_exists($email){
        $Query = "SELECT email FROM trackie WHERE customerno=$this->customerno AND email='%s' AND isdeleted=0";
        $SQL = sprintf($Query,$email);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * To add new trackie in trackie-master
     * @param string $trackiename,$phone,$email,$address,$weeklyoff
     */
    function insert_trackiedata($trackiename,$phone,$email,$address,$weeklyoff,$locid,$uncheckids){
        $Query = "Insert into trackie (customerno,name,phone,email,address,weekly_off,locid,entrytime,addedby)VALUES($this->customerno,'%s',%d,'%s','%s','%s',%d,'$this->today',$this->userid)";
        $SQL = sprintf($Query,Sanitise::String($trackiename),$phone,Sanitise::String($email),Sanitise::String($address),$weeklyoff,$locid); 
        $this->executeQuery($SQL);
        $trackieid = $this->get_insertedId(); 
        $uncheckids1 = array_filter($uncheckids);
        if(!empty($uncheckids1)){
            foreach ($uncheckids1 as $key => $value){
                $Query = "Insert into exceptional_services (customerno,trackieid,serviceid,entrytime,addedby)VALUES ($this->customerno,%d,%d,'$this->today',$this->userid)";
                $SQL = sprintf($Query,$trackieid,$value); 
                $this->executeQuery($SQL);
            }
        }
    }
    
    function update_trackiedata($trackid,$trackiename,$phone,$email,$address,$weeklyoff1,$uncheckids){
        $Query = "SELECT serviceid FROM exceptional_services WHERE customerno=$this->customerno AND trackieid='%s' AND isdeleted=0";
        $SQL = sprintf($Query,$trackid);
        $this->executeQuery($SQL);
        if($this->get_rowCount() > 0){
            $oldserviceid = array();
            while ($row = $this->get_nextRow())            
            {
               $oldserviceid[] =  $row['serviceid'];
            }
        }
            
            if(!empty($oldserviceid)){
            $test1 = array_filter(array_diff($uncheckids,$oldserviceid));  //insert
            $test2 = array_filter(array_diff($oldserviceid,$uncheckids));  //delete
                if(!empty($test1)){
                        $this->insertserviceids($test1,$trackid);
                }
                if(!empty($test2)){
                    $this->Delserviceids($test2,$trackid);
                }
            }else{
                $this->insertserviceids($uncheckids,$trackid);
            }    
            
        $weeklyoff = implode (",", $weeklyoff1); 
        $Query = "update trackie set name='".$trackiename."',phone='".$phone."',email='".$email."',address='".$address."', weekly_off='".$weeklyoff."',updatedtime='".$this->today."', updated_by='".$this->userid."' where trackid=".$trackid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }
    
    public function Delserviceids($test2,$trackid){
        foreach ($test2 as $key => $value){
            $Query1 = "update exceptional_services set isdeleted=1, updatedtime='".$this->today."', updated_by='".$this->userid."' where trackieid='".$trackid."' AND serviceid=".$value;
            $SQL1 = sprintf($Query1);
            $this->executeQuery($SQL1);
        }  
    }
    
    public function insertserviceids($serviceids,$trackid){
        foreach ($serviceids as $key => $value){
            $Query = "Insert into exceptional_services (customerno,trackieid,serviceid,entrytime,addedby)VALUES ($this->customerno,%d,%d,'$this->today',$this->userid)";
            $SQL = sprintf($Query,$trackid,$value); 
            $this->executeQuery($SQL);
        }  
    }
    
    public function gettrackiedata_byid($id){
        $Query = "SELECT t.trackid,t.name,t.phone,t.email,t.address,t.weekly_off,lm.location, lm.locid FROM trackie as t left join location_master as lm ON lm.locid = t.locid WHERE t.customerno= $this->customerno AND t.trackid='%d' AND t.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
         while ($row = $this->get_nextRow())            
           {
               $trackdata[] = array(
                   'trackid' => $row['trackid'],
                   'name' => $row['name'],
                   'phone' => $row['phone'],
                   'email' => $row['email'],
                   'address' => $row['address'],
                   'weekly_off' => $row['weekly_off'],
                   'locid' => $row['locid'],
                   'location' => $row['location']
                );
            }
            return $trackdata;
        }
        return null;
    }
    
     public function getcatdata(){
       $getdata= array();
       $Query = "SELECT cid,category_name FROM service_category WHERE customerno=$this->customerno AND isdeleted=0";
       $SQL = sprintf($Query);
       $this->executeQuery($SQL);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
               $getdata[] = array(
                   'id' => $row['cid'],
                   'value' => $row['category_name'],
                );
            }
            return $getdata;
        }    
        return null;
    }
    
    /**
     * City like query
     * @param string $q
     * @return type
     */
    public function getcitydata($q){
       $getdata= array();
       $lq= "%$q%";
       $Query = "SELECT cityid,cityname FROM city_master WHERE customerno=$this->customerno AND cityname LIKE '%s' AND isdeleted=0";
       $SQL = sprintf($Query,Sanitise::String($lq));
       $this->executeQuery($SQL);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
               $getdata[] = array(
                   'id' => $row['cityid'],
                   'value' => $row['cityname'],
                );
            }
            return $getdata;
        }    
        return null;
    }
    
    /**
     * To check if service-name already exists
     * @param string $sname
     * @return boolean
     */
    public function is_service_exists($sname){
        $Query = "SELECT serviceid FROM service_list WHERE customerno=$this->customerno AND service_name='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($sname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * To insert service
     * @param string $sdata
     */
    public function add_service($sdata){
        extract($sdata);
        if($addnewcat==1){
                $Query = "Insert into service_category (customerno,category_name,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
                $SQL = sprintf($Query,Sanitise::String($newcat));
                $this->executeQuery($SQL);
              $catid = $this->get_insertedId();
        }else{
                $catid = $cid;
        }
        
        $Query = "Insert into service_list (customerno,cid,service_name,cost,expected_time, entrytime, addedby)
            VALUES($this->customerno,%d,'%s',%f,%d, '$this->today', $this->userid)";
        $SQL = sprintf($Query,Sanitise::String($catid),Sanitise::String($service_name), Sanitise::Float($cost), $expTime);
        $this->executeQuery($SQL);
    }   
    
     public function update_service($sdata){
        extract($sdata);
        if($addnewcat==1){
                $Query = "Insert into service_category (customerno,category_name,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
                $SQL = sprintf($Query,Sanitise::String($newcat));
                $this->executeQuery($SQL);
                $cid = $this->get_insertedId();
        }else{
                $cid = $catid;
        }
        
        $Query = "update service_list set service_name='".$service_name."',cid='".$cid."',cost='".$cost."', expected_time='".$expTime."',updatedtime='".$this->today."', updated_by='".$this->userid."' where serviceid=".$serviceid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    } 
    
    
    public function getservicedata_byid($id){
        $Query = "SELECT sl.serviceid, sl.service_name, sl.cost, sl.expected_time,sl.cid, sc.category_name FROM service_list as sl left join service_category as sc on sc.cid = sl.cid  WHERE  sl.customerno=$this->customerno AND sl.serviceid='%d' AND sl.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
         while ($row = $this->get_nextRow())            
           {
               $servicedata[] = array(
                   'serviceid' => $row['serviceid'],
                   'service_name' => $row['service_name'],
                   'cost' => $row['cost'],
                   'expected' => $row['expected_time'],
                   'catid'=>$row['cid'],
                   'category_name'=>$row['category_name']
                   
                );
            }
            return $servicedata;
        }
        return null;
    }
    
    public function getservice_bycat($cid){
        $Query = "SELECT sl.serviceid, sl.service_name,sl.cost,sl.expected_time FROM service_list as sl";
        $Query .= " inner join service_category as sc on sc.cid = sl.cid  and sc.isdeleted=0";
        $Query .= " WHERE  sl.customerno=$this->customerno AND sc.cid='%d' AND sl.isdeleted=0";
        
        $SQL = sprintf($Query, Sanitise::String($cid));
        $this->executeQuery($SQL);
        
        if($this->get_rowCount() > 0) {
            $servicedata = array();
            while ($row = $this->get_nextRow()){
                $servicedata[] = array(
                   'id' => $row['serviceid'],
                   'value' => $row['service_name'],
                   'scost'=>$row['cost'],
                   'exptime'=>$row['expected_time']
                );
            }
            return $servicedata;
        }
        return null;
    }

    /**
     * Common method for big forms
     * @param type $sdata
     * @return type
     */
    public function insert_arr($sdata){
        $clmns = array();
        $values = array();
        $defc = array('customerno','entrytime','addedby');
        $defv = array($this->customerno,"'".$this->today."'",$this->userid);
        foreach($sdata as $clmn=>$dat){
            $clmns[] = $clmn;
            if(is_null($dat[0])){
                $values[] = "'".$dat[1]."'";
            }
            else{
                $values[] = "'".Sanitise::$dat[0]($dat[1])."'";
            }
        }
        $clmns = array_merge($clmns, $defc);
        $values = array_merge($values, $defv);
        $cclmns = implode(',',$clmns);
        $cvalues = implode(',',$values);
        return array('clms'=>$cclmns, 'cval'=>$cvalues);
    }
    /**
     * Add new client
     * @param array $sdata
     */
    public function add_client($sdata,$address){
       $d = $this->insert_arr($sdata);
       $SQL = "insert into client({$d['clms']}) values({$d['cval']})";
       $this->executeQuery($SQL);
       $clientid = $this->get_insertedId();
       $this->add_address($address,$clientid);
    }
    
    public function add_address($address,$clientid){
        extract($address);
        $Query = "Insert into client_address (clientid,flatno,building,society,landmark,locationid,cityid,entrytime,added_by)
            VALUES('%s','%s','%s','%s','%s',%d,%d,'$this->today', $this->userid)";
        $SQL = sprintf($Query, Sanitise::String($clientid),Sanitise::String($flatno),Sanitise::String($building),Sanitise::String($society),Sanitise::String($landmark),Sanitise::String($locationid),Sanitise::String($cityid));
        $this->executeQuery($SQL);
        
        if($alternateaddress=='1'){
        $Query = "Insert into client_address (clientid,flatno,building,society,landmark,locationid,cityid,entrytime,added_by)
        VALUES('%s','%s','%s','%s','%s',%d,%d,'$this->today', $this->userid)";
        $SQL = sprintf($Query, Sanitise::String($clientid),Sanitise::String($flatno1),Sanitise::String($building1),Sanitise::String($society1),Sanitise::String($landmark1),Sanitise::String($locationid1),Sanitise::String($cityid1));
        $this->executeQuery($SQL);
        }
        
    }
    
     public function update_client($sdata,$address){
        extract($sdata);
        extract($address);
         
        if($password!=""){
            $password1 = sha1($password);
            $passwordupdate = ",password='".$password1."'";
        }else{
            $passwordupdate="";
        }
        $dob1 = date("Y-m-d", strtotime($dob));
        $anniversary1 = date("Y-m-d", strtotime($anniversary));
        if($is_member==1){
            $is_member='1';
          
        }else{
            $is_member='0';
        }
        
        $Query = "update client set notes ='".$notes."', is_member='".$is_member."',pckgid='".$pckgid."',clientno='".$clientno."',client_name='".$client_name."',dob='".$dob1."',anniversary='".$anniversary1."',mobile='".$mobile."',phone='".$phone."',email='".$email."',groupid='".$groupid."',min_billing='".$min_billing."',updatedtime='".$this->today."', updated_by='".$this->userid."' ".$passwordupdate." where clientid=".$clientid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        
        if($addressid!='0'|| $addressid!=''){
            $Query = "update client_address set flatno='".$flatno."',building='".$building."',society='".$society."',landmark='".$landmark."',locationid='".$locationid."',cityid='".$cityid."',updatedtime='".$this->today."', updated_by='".$this->userid."' where client_add_id=".$addressid;
            $SQL = sprintf($Query);
            $this->executeQuery($SQL);
        }
        
        if($alternateaddress==1){
            if ($addressid1=='0'||empty($addressid1)){
                $Query = "Insert into client_address (clientid,flatno,building,society,landmark,locationid,cityid,entrytime,added_by)
                VALUES('%s','%s','%s','%s','%s',%d,%d,'$this->today', $this->userid)";
                $SQL = sprintf($Query, Sanitise::String($clientid),Sanitise::String($flatno1),Sanitise::String($building1),Sanitise::String($society1),Sanitise::String($landmark1),Sanitise::String($locationid1),Sanitise::String($cityid1));
                $this->executeQuery($SQL); 
            }else{
                $Query = "update client_address set flatno='".$flatno1."',building='".$building1."',society='".$society1."',landmark='".$landmark1."',locationid='".$locationid1."',cityid='".$cityid1."',updatedtime='".$this->today."', updated_by='".$this->userid."' where client_add_id=".$addressid1;
                $SQL = sprintf($Query);
                $this->executeQuery($SQL);
            }
        }
    }
    
    public function getclientaddress_byid($clientid){
        $Query = "SELECT * FROM client_address  WHERE  clientid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($clientid));
        $this->executeQuery($SQL);
        if ($this->get_rowCount()>0){
            while ($row = $this->get_nextRow())            
            {
                $clientdata[] = array(
                     'client_add_id'=>$row['client_add_id'],
                     'clientid'=>$row['clientid'],
                    'flatno'=>$row['flatno'],
                    'building'=>$row['building'],
                    'society'=>$row['society'],
                    'landmark'=>$row['landmark'],
                    'locationid'=>$row['locationid'],
                    'cityid'=>$row['cityid'],
                 );
            }
            return $clientdata;
        }
            return null;
    }
    
    public function getclientdata_byid($clientid){
        $Query = "SELECT * FROM client as c left join package_master as pm on pm.pckgid =c.pckgid  WHERE  c.customerno=$this->customerno AND c.clientid='%d' AND c.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($clientid));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
         while ($row = $this->get_nextRow())            
           {
               $clientdata[] = array(
                   'clientid' => $row['clientid'],
                   'clientno' => $row['clientno'],
                   'client_name' => $row['client_name'],
                   'dob' => $row['dob'],
                   'anniversary' => $row['anniversary'],
                   'mobile' => $row['mobile'],
                   'phone' => $row['phone'],
                   'email' => $row['email'],
                   'groupid' => $row['groupid'],
                   'min_billing' => $row['min_billing'],
                   'password'=>$row['password'],
                   'is_member'=>$row['is_member'],
                   'pckgid'=>$row['pckgid'],
                   'amount'=>$row['amount'],
                   'validity'=>$row['validity'],
                   'notes'=>$row['notes']
                   
                );
            }
            return $clientdata;
        }
        return null;
    }
    
    public function is_clientno_exists($clientno){
        $Query = "SELECT clientid FROM client WHERE customerno=$this->customerno AND clientno='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($clientno));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
    }
    
     public function is_clientemail_exists($cemail){
        $Query = "SELECT clientid FROM client WHERE customerno=$this->customerno AND email='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($cemail));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
    }
    
    public function get_timeline($date){
        $date = date('Y-m-d', strtotime($date));
        $Query = "SELECT a.trackid,a.name,a.weekly_off FROM trackie as a ";
        $Query .= "  WHERE a.customerno=$this->customerno and a.isdeleted=0";
        
        $SQL = $Query;
        $this->executeQuery($SQL);
        
        if ($this->get_rowCount() > 0){
            $getdata = array();
            while ($row = $this->get_nextRow())            
            {
               $getdata[] = array(
                   'tid' => $row['trackid'],
                   'tname' => ucfirst($row['name']),
                   'weeklyoff'=>$row['weekly_off']
                );
            }
            return $getdata;
        }    
        return null;
    }
    
    public function getclients_auto($q){
       $getdata = array();
       $lq = Sanitise::String($q);
       $Query = "SELECT clientid,client_name  as client,mobile FROM client";
       $Query .= " WHERE customerno=$this->customerno AND (client_name LIKE '%$lq%' or mobile like '%$lq%') AND isdeleted=0 ";
       $Query .= " limit 15";
       
       $this->executeQuery($Query);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
               $getdata[] = array(
                   'id' => $row['clientid'],
                   //'value' => $row['client']
                    'value' => $row['clientid'].'-'.$row['client'],
                );
            }
            return $getdata;
        }    
        return null;
    }
    
    public function getcity_auto($q){
       $getdata = array();
       $lq = Sanitise::String($q);
       $Query = "SELECT cityid, cityname FROM city_master";
       $Query .= " WHERE customerno=$this->customerno AND (cityname LIKE '%$lq%') AND isdeleted=0 ";
       
       $this->executeQuery($Query);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
               $getdata[] = array(
                   'id' => $row['cityid'],
                   'value' => $row['cityname']
                );
            }
            return $getdata;
        }    
        return null;
    }
    
    public function getclients_details($cid){
       $Query = "SELECT a.*,b.*,concat(c.location,', ',d.cityname) as loccity FROM client as a ";
       $Query .= "  left join client_address as b on a.clientid=b.clientid and b.isdeleted=0 ";
       $Query .= "  left join location_master as c on a.customerno=c.customerno and b.locationid=c.locid ";
       $Query .= "  left join city_master as d on a.customerno=d.customerno and c.cityid=d.cityid ";
       $Query .= " WHERE a.customerno=$this->customerno AND a.clientid=$cid AND a.isdeleted=0 ";
       
       $this->executeQuery($Query);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
               $add_arr = array($row['flatno'],$row['building'],$row['society'],$row['landmark'],$row['loccity']);
               $address[] = array($row['client_add_id'],implode(', ', array_filter($add_arr)));
               $getdata = array(
                   'cno' => $row['clientno'],
                   'cid' => $row['clientid'],
                   'cname' => $row['client_name'],
                   'mobile'=>$row['mobile'],
                   'phone'=>$row['phone'],
                   'email'=>$row['email'],
                );
            }
            $getdata['address'] = $address;
            return $getdata;
        }    
        return null;
    }
    
    //api get client details 
    public function chk_getclients_details($cid){
        $Query = " select a.clientid,a.groupid,ca.locationid,ca.cityid from client as a  ";
        $Query .= " left join client_address as ca on ca.clientid=a.clientid AND ca.isdeleted = 0";
        $Query .= " where a.clientid =%d AND a.customerno=$this->customerno AND a.isdeleted=0 ";
        $SQL = sprintf($Query,Sanitise::String($cid));
        $this->executeQuery($SQL);
        
        if($this->get_rowCount() > 0){
           $locid=array();
            while ($row = $this->get_nextRow())            
            {
                $locid[]= $row['locationid']; 
                $cityid[] = $row['cityid'];
                $clientdata = array(
                    'clientid' => $row['clientid'],
                    'groupid' => $row['groupid'],
                    'locid' => $locid,
                    'cityid' => $cityid
                );
            }
            return $clientdata;
        }else{
           return null;
       }
    }
    
    public function client_history($cid,$limit=null,$api=false){
        $getdata = array();
        $Query = "SELECT e.discount_code,a.client_add_id, a.entrytime, a.scid, d.name, a.service_date, a.service_status, GROUP_CONCAT(concat(c.serviceid,'-', b.quantity) SEPARATOR ',') as serviceids, GROUP_CONCAT(c.service_name SEPARATOR ',') as services, sum(c.cost) as tcost, sum(c.expected_time) as ttime, a.entrytime, a.updatedtime FROM service_call as a ";
        $Query .= " left join service_ordered as b on a.scid=b.scid and b.isdeleted=0";
        $Query .= " left join service_list as c on b.serviceid=c.serviceid ";
        $Query .= " left join trackie as d on a.trackieid=d.trackid ";
        $Query .= " left join discount_master as e on a.discountid=e.discountid ";
        $Query .= " WHERE a.clientid=$cid and a.customerno=$this->customerno AND a.isdeleted=0 ";
        $Query .= " group by a.scid ";
        if($limit){
            $Query .= " limit $limit ";
        }
        
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0){
            while ($row = $this->get_nextRow())
            {
                if($api=='login'){
                    $discode = ($row['discount_code']!=0) ? $row['discount_code'] : 'NA';
                    $getdata[] = array(
                        'request_no' => $row['scid'],
                        'bkgdate' => date('j-n-Y H:i', strtotime($row['entrytime'])),
                        'sdate' => date('j-n-Y H:i', strtotime($row['service_date'])),
                        'tcost'=>$row['tcost'],
                        'ttime'=>$row['ttime'],
                        'services'=>$row['serviceids'],
                        'status'=>$row['service_status'],
                        'address_id'=>$row['client_add_id'],
                        'dcode' => $discode
                    );
                }
                else if($api=='histApi'){
                    $getdata[] = array(
                        'request_no' => $row['scid'],
                        'status'=>$row['service_status']
                    );
                }
                else{
                    $getdata[] = array(
                    'sid' => $row['scid'],
                    'tname' => $row['name'],
                    'sdate' => $row['service_date'],
                    'fdate' => date('D, d M H:i', strtotime($row['service_date'])),
                    'services'=>$row['services'],
                    'tcost'=>$row['tcost'],
                    'ttime'=>$row['ttime'],
                    'entrytime'=>$row['entrytime'],
                    'updtetime'=>$row['updatedtime'],
                    'status'=>$row['service_status']
                 );
                }
             }
             return $getdata;
        }    
        return $getdata;
    }

    public function getservice_auto($q){
       $getdata = array();
       $lq = Sanitise::String($q);
       $Query = "SELECT serviceid, service_name, cost, expected_time FROM service_list";
       $Query .= " WHERE customerno=$this->customerno AND isdeleted=0 AND service_name LIKE '%$lq%'  ";
       
       $this->executeQuery($Query);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
               $getdata[] = array(
                   'id' => $row['serviceid'],
                   'value' => $row['service_name'],
                   'scost'=>$row['cost'],
                   'exptime'=>$row['expected_time']
                );
            }
            return $getdata;
        }    
        return null;
    }
    
    public function getservice_list(){
       $getdata = array();
       $Query = "SELECT serviceid, service_name, cost, expected_time FROM service_list";
       $Query .= " WHERE isdeleted=0 order by serviceid desc ";
       
       $this->executeQuery($Query);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
               $getdata[] = array(
                   'id' => $row['serviceid'],
                   'value' => $row['service_name'],
                   'scost'=>$row['cost'],
                   'exptime'=>$row['expected_time']
                );
            }
            return $getdata;
        }    
        return null;
    }
    
     public function getservice_listbyid($id){
       $getdata = array();
       $Query = "SELECT serviceid FROM exceptional_services";
       $Query .= " WHERE isdeleted=0 AND trackieid=$id";
       
       $this->executeQuery($Query);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
           $getdata[$row['serviceid']] = array(
                   'id' => $row['serviceid']
                );
            }
            return $getdata;
        }    
        return null;
    }
    
    
    public function gettrackie_auto($q){
       $getdata = array();
       $lq = Sanitise::String($q);
       $Query = "SELECT trackid, name FROM trackie";
       $Query .= " WHERE customerno=$this->customerno AND isdeleted=0 AND name LIKE '%$lq%'  ";
       
       $this->executeQuery($Query);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
               $getdata[] = array(
                   'id' => $row['trackid'],
                   'value' => $row['name'],
                );
            }
            return $getdata;
        }    
        return null;
    }
    
    public function chk_all_services_exists($sids){
        $quantity = array_count_values($sids);
        $usids = array_unique($sids);
        $csv_s = implode(',',$usids);
        $allsvc = Sanitise::String($csv_s);
        $Query = "SELECT serviceid, expected_time FROM service_list";
        $Query .= " WHERE customerno=$this->customerno AND isdeleted=0 AND serviceid in ($allsvc)  ";

        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0){
            $count = 0;
            $totaltime = 0;
            while ($row = $this->get_nextRow()){
                $totaltime += ($row['expected_time']*$quantity[$row['serviceid']]);
                $count++;
            }
            
            if(count($usids)==$count){
                return $totaltime; //total time for services
            }
            
        }    
        return false;
    }
    
    public function insert_service_call($data){
        $Query = "Insert into service_call (customerno,clientid,trackieid,service_status,client_add_id, discountid, service_date, entrytime, addedby)
            VALUES($this->customerno,{$data['clid']}, {$data['tkid']}, {$data['statusid']}, {$data['addid']}, '".$data['discid']."', '{$data['svcdate']}',  '$this->today', $this->userid)";
        
        $this->executeQuery($Query);
        $lid = $this->get_insertedId();
        if(!$lid){
            return false;
        }
        $arrq = '';
        foreach($data['svid'] as $serviceid){
            $arrq[] = " ($lid, $serviceid, {$data['quantity'][$serviceid]}) ";
        }
        $cq = implode(', ', $arrq);
        $Query2 = "Insert into service_ordered (scid,serviceid,quantity) VALUES $cq";
        $this->executeQuery($Query2);
        $lid2 = $this->get_insertedId();
        
        if(!$lid2){
            return false;
        }
        return $lid; //insert-id of service_call
    }
    
    public function update_service_call($callid, $data){
        $Query = "update service_call set clientid={$data['clid']}, trackieid={$data['tkid']}, service_status={$data['statusid']}";
        $Query .= " ,client_add_id={$data['addid']}, discountid={$data['discid']}, service_date='{$data['svcdate']}', updatedtime='$this->today', updated_by=$this->userid";
        $Query .= " where customerno=$this->customerno and scid=$callid";
        
        $this->executeQuery($Query);
        
        $delQuery = "update service_ordered set isdeleted=1,updatedtime='$this->today',updated_by=$this->userid where scid=$callid";
        $this->executeQuery($delQuery);
        
        $arrq = '';
        foreach($data['svid'] as $serviceid){
            $arrq[] = " ($callid, $serviceid, {$data['quantity'][$serviceid]}) ";
        }
        $cq = implode(', ', $arrq);
        $Query2 = "Insert into service_ordered (scid,serviceid,quantity) VALUES $cq";
        $this->executeQuery($Query2);
        return true;
    }
    
    public function trackie_timeline($inpDate){
        $getdata = array();
        $tlDate = date('Y-m-d', strtotime($inpDate));
        $Query = "SELECT a.discountid,h.amount, h.percentage, e.flatno, e.building, e.society, e.landmark, f.location, g.cityname, a.service_status, client_name, GROUP_CONCAT(c.service_name SEPARATOR ',') as services, a.scid,a.clientid,d.mobile,a.trackieid, a.service_date, hour(a.service_date) as shour , minute(a.service_date) as sminute, sum(c.expected_time*b.quantity) as total_time, sum(c.cost) cost";
        $Query .= " FROM service_call as a";
        $Query .= " left join service_ordered as b on a.scid=b.scid and b.isdeleted=0";
        $Query .= " left join service_list as c on b.serviceid=c.serviceid ";
        $Query .= " left join client as d on a.clientid=d.clientid ";
        $Query .= " left join client_address as e on e.clientid=a.clientid and e.client_add_id=a.client_add_id and e.isdeleted=0 ";
        $Query .= "  left join location_master as f on f.locid=e.locationid ";
        $Query .= "  left join city_master as g on g.cityid=f.cityid ";
        $Query .= "  left join discount_master as h on h.discountid=a.discountid and h.isdeleted=0 ";
        $Query .= " WHERE a.customerno=$this->customerno AND a.isdeleted=0 and date(a.service_date)='$tlDate'  and b.soid is not null";
        $Query .= " group by a.scid ";

        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0){
            while ($row = $this->get_nextRow())
            {
                $lc = "<br/>{$row['location']}, {$row['cityname']}";
                $add_arr = array($row['flatno'],$row['building'],$row['society'],$row['landmark'],$lc);
                $address = implode(', ', array_filter($add_arr));
                
                $disc = null;
                if($row['discountid']){
                    if($row['amount']!='00:00' && $row['amount']!=null){$disc = array('val'=>$row['amount'],'isamount' => 1, 'unit' =>'Rs');}
                    else{$disc = array('val'=>$row['percentage'],'isamount' => 1, 'unit'=>'%'); }
                }

                $getdata[$row['trackieid']][$row['shour']][$row['sminute']] = array(
                    'scid' => $row['scid'],
                    'cname' => $row['client_name'],
                    'clientid' => $row['clientid'],
                    'totaltime' => $row['total_time'],
                    'sdate' => $row['service_date'],
                    'sminute' => $row['sminute'],
                    'shour' => $row['shour'],
                    'services' => $row['services'],
                    'status' => $row['service_status'],
                    'address' => $address,
                    'tcost'=>$row['cost'],
                    'mobile'=>$row['mobile'],
                    'discount'=>$disc
                 );
            }
            
            return $getdata;
        }    
        return null;
    }
    
    
    public function delete_citydata($id){
       $Query = "update city_master set isdeleted=1 WHERE cityid='%d'";
       $SQL = sprintf($Query, Sanitise::String($id));
       $this->executeQuery($SQL);
    }
    
    public function delete_locdata($id){
       $Query = "update location_master set isdeleted=1 WHERE locid='%d'";
       $SQL = sprintf($Query, Sanitise::String($id));
       $this->executeQuery($SQL);
    }
    
      public function delete_servicecaldata($id){
       $Query = "update service_list set isdeleted=1 WHERE serviceid='%d'";
       $SQL = sprintf($Query, Sanitise::String($id));
       $this->executeQuery($SQL);
    }
    
    public function delete_clientdata($id){
       $Query = "update client set isdeleted=1 WHERE clientid='%d'";
       $SQL = sprintf($Query, Sanitise::String($id));
       $this->executeQuery($SQL);
       
       $Query1 = "update client_address set isdeleted=1 WHERE clientid='%d'";
       $SQL1 = sprintf($Query1, Sanitise::String($id));
       $this->executeQuery($SQL1);
       
    }
    public function delete_trackdata($id){
       $Query = "update trackie set isdeleted=1 WHERE trackid='%d'";
       $SQL = sprintf($Query, Sanitise::String($id));
       $this->executeQuery($SQL);
    }
    public function delete_servicecalldata($id){
       $Query = "update service_call set isdeleted=1 WHERE scid='%d'";
       $SQL = sprintf($Query, Sanitise::String($id));
       $this->executeQuery($SQL);
    }
    
    public function delete_discountdata($id){
       $Query = "update discount_master set isdeleted=1 WHERE discountid='%d'";
       $SQL = sprintf($Query, Sanitise::String($id));
       $this->executeQuery($SQL);
       
       $Query1 = "update discount_specific set isdeleted=1 WHERE discount_id='%d'";
       $SQL1 = sprintf($Query1, Sanitise::String($id));
       $this->executeQuery($SQL1);
    }
    
    public function delete_feedbackdata($id){
       $Query = "update feedback_master set isdeleted=1 WHERE fid='%d'";
       $SQL = sprintf($Query, Sanitise::String($id));
       $this->executeQuery($SQL);
       
       $Query1 = "update options set isdeleted=1 WHERE fid='%d'";
       $SQL1 = sprintf($Query1, Sanitise::String($id));
       $this->executeQuery($SQL1);
    }
    public function delete_categorydata($id){
       $Query = "update service_category set isdeleted=1 WHERE cid='%d'";
       $SQL = sprintf($Query, Sanitise::String($id));
       $this->executeQuery($SQL);
       
       $Query1 = "update options set isdeleted=1 WHERE fid='%d'";
       $SQL1 = sprintf($Query1, Sanitise::String($id));
       $this->executeQuery($SQL1);
    }
    public function delete_packagedata($id){
       $Query = "update package_master set isdeleted=1 WHERE pckgid='%d'";
       $SQL = sprintf($Query, Sanitise::String($id));
       $this->executeQuery($SQL);
    }
    public function is_email_exists($email){
        $Query = "SELECT clientid FROM client WHERE email='%s' and customerno=$this->customerno and isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($email));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
    }
    
    public function is_mobile_exists($mobile){
        $Query = "SELECT clientid FROM client WHERE mobile='%s' and customerno=$this->customerno and isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($mobile));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
    }
    
    public function add_api_client($email,$phone,$password,$smscode,$name){
        $userkey = mt_rand();
        $pwd_encry = Sanitise::String($password);
        $Query = "Insert into client (customerno,email,mobile,temp_password,userkey,otp_number, entrytime, client_name)
            VALUES($this->customerno,'%s','%s','%s','%s','%s', '$this->today', '%s')";
        $SQL = sprintf($Query, Sanitise::String($email),Sanitise::String($phone),$pwd_encry,$userkey,$smscode, Sanitise::String($name));
        $this->executeQuery($SQL);
    }
    
    public function checkotp($phone,$otp){
        $Query = "SELECT userkey, clientid  FROM client WHERE mobile='%s' AND otp_number='%s' and customerno=$this->customerno";
        $SQL = sprintf($Query, Sanitise::String($phone),Sanitise::String($otp));
        
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()){
               return array(
                   'userkey'=>$row['userkey'],
                   'clientid'=>$row['clientid']
                );
            }
        }
        else{
            return null;
        }
    }
    
    public function set_otp_verfied($cid){
        $SQL = "update client set is_otp_verified=1 WHERE clientid=$cid";
        $this->executeQuery($SQL);
    }
    
    public function update_password($cid){
        $SQL = "update client set password=temp_password WHERE clientid=$cid";
        $this->executeQuery($SQL);
    }
    
    public function getdata_fromlogin($mobile,$password){
       $pass_encry = Sanitise::String($password);
       $Query = "SELECT a.*,b.flatno,b.area,b.building,b.society,b.landmark,b.pincode,b.location_name,b.city_name FROM client as a ";
       $Query .= "  left join client_address as b on a.clientid=b.clientid and b.isdeleted=0 ";
       $Query .= " WHERE a.mobile='".Sanitise::String($mobile)."' AND a.password='".$pass_encry."'and a.customerno=$this->customerno AND a.isdeleted=0";
       
       $this->executeQuery($Query);
       if ($this->get_rowCount() > 0){
           $address = array();
           while ($row = $this->get_nextRow())            
           {
               if($row['city_name'] != null)
               {
                   $address[] = array(
                       'flatno' => $row['flatno'],
                       'building' => $row['building'],
                       'street' => $row['society'],
                       'landmark' => $row['landmark'],
                       'pincode' => $row['pincode'],
                       'area' => $row['area'],
                       'location' => $row['location_name'],
                       'city' => $row['city_name']
                   );
               }
                
               $getdata = array(
                   'clientid' => $row['clientid'],
                   'cname' => $row['client_name'],
                   'mobile'=>$row['mobile'],
                   'email'=>$row['email'],
                   'userkey'=>$row['userkey'],
                   'otp_verified'=>$row['is_otp_verified']
                );
            }
            $getdata['address'] = $address;
            return $getdata;
        }    
        return null;
    }
    
    public function update_api_client($mobile,$password,$smscode){
        $Query = "update client set  temp_password='%s', password='', otp_number='$smscode' ";
        $Query .= " WHERE mobile='%s' and customerno=$this->customerno and isdeleted=0";
        
        $SQL = sprintf($Query, Sanitise::String($password), Sanitise::String($mobile));
        $this->executeQuery($SQL);
    }
    
    public function client_by_key($userkey){
        $Query = "SELECT customerno, clientid  FROM client WHERE userkey='$userkey' and isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($userkey));
        
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()){
               return array(
                   'customerno'=>$row['customerno'],
                   'clientid'=>$row['clientid']
                );
            }
        }
        else{
            return null;
        }
    }
    
    public function set_customer_user($customerno, $userid){
        $this->customerno = $customerno;
        $this->userid = $userid;
    }
    
    public function getcity(){
        $Query = "SELECT * FROM city_master WHERE isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
         while ($row = $this->get_nextRow())            
           {
               $citydata[] = array(
                   'id' => $row['cityid'],
                   'cityname' => $row['cityname']
                );
            }
            return $citydata;
        }
        return null;
    }
    
    public function get_locations_bycityid($cid){
        $Query = "SELECT locid,location  FROM location_master WHERE customerno=$this->customerno AND cityid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($cid));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
         while ($row = $this->get_nextRow())            
           {
             $locdata[]=array(
                  'locid' => $row['locid'],
                 'location' => $row['location']
             );
           }
           return $locdata;
        }
        return NULL;
    }
    
    public function get_package_bypid($pid){
        $Query = "SELECT pckgid,package_code,amount,validity FROM package_master WHERE customerno=$this->customerno AND pckgid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($pid));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
         while ($row = $this->get_nextRow())            
           {
             $expdate = date("d-m-Y", strtotime($row['validity']));
             
             $pckgdata[]=array(
                'pckgid' => $row['pckgid'],
                'package_code' => $row['package_code'],
                'amount' => $row['amount'],
                'validity' => $expdate
             );
           }
           return $pckgdata;
        }
        return NULL;
    }
    
    
    public function getgroupsbyuserid_client($userid)
    {        
        $groups = Array();
        $Query = "select group.groupid,group.groupname from ".SPEEDDB.".`group` 
            INNER JOIN ".SPEEDDB.".groupman ON groupman.groupid = group.groupid
            INNER JOIN ".SPEEDDB.".user ON user.userid = groupman.userid
            where groupman.userid=%s AND groupman.customerno=%s AND group.isdeleted=0 AND groupman.isdeleted=0 order by group.groupname ASC";
        $groupQuery = sprintf($Query, Sanitise::Long($userid),$this->customerno);
        
        $this->executeQuery($groupQuery);
               
        if ($this->get_rowCount() > 0) 
        {
            while ($row = $this->get_nextRow())            
            { 
                $group = new VOGroup();    
                $group->groupid = $row['groupid']; 
                //$group->groupgmid = $row['gmid'];     
                $group->groupname = $row['groupname'];         
                $groups[] = $group;
            }
        
            return $groups;
        }
        return NULL;
    }
    
    public function getpackage(){
        $query = "select * from package_master where isdeleted=0";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
         while ($row = $this->get_nextRow())            
           {
             $pkgdata[]=array(
                  'pckgid' => $row['pckgid'],
                 'package_code' => $row['package_code']
             );
           }
           return $pkgdata;
        }
        return NULL;
    }
    
     public function is_disccode_exists($discode){
        $SQL = "SELECT discount_code FROM discount_master WHERE customerno=$this->customerno AND discount_code='$discode' AND isdeleted=0";
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
    }
    
    public function insert_discdata($disccode,$expdate1,$disctype,$disctype_value,$spec_type,$clid1,$locid,$cityid,$grpid){
         $clid2 = array_map('trim',$clid1);    
         $clid = array_unique($clid2);
         $countid =  count($clid);
        $amount="";
        $percentage="";
        $expdate = date("Y-m-d", strtotime($expdate1));
        if($disctype==1){
            $amount = $disctype_value;
        }
        elseif ($disctype==2) {
           $percentage = $disctype_value;
        }
        elseif($disctype!=1 || $disctype!=2){
            $amount="";
            $percentage="";
        }
        
        $Query = "Insert into discount_master (customerno,discount_code,amount,percentage,expiry_date,entrytime,addedby) VALUES($this->customerno,'%s','%s','%s','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query,Sanitise::String($disccode),Sanitise::String($amount),Sanitise::String($percentage),Sanitise::String($expdate));
        $this->executeQuery($SQL);
        $disid =  $this->get_insertedId(); 
        
        if(!empty($clid)){
            $clid2 = array_map('trim',$clid1);     
            $clid = array_unique($clid2);
            $countid =  count($clid);
            foreach ($clid as $key => $value){
                $clids = current(explode('-',$value));
                $Query = "Insert into discount_specific (customerno,discount_id,clientid,locationid,cityid,branchid,entrytime,addedby) VALUES($this->customerno,'%s','%s','%s','%s','%s','$this->today',$this->userid)";
                $SQL = sprintf($Query,Sanitise::String($disid),Sanitise::String($clids),Sanitise::String($locid),Sanitise::String($cityid),Sanitise::String($grpid));
                $this->executeQuery($SQL);
            }
        }
        
        if($locid!=""|| $cityid!=""||$grpid!=""){
            $Query = "Insert into discount_specific (customerno,discount_id,clientid,locationid,cityid,branchid,entrytime,addedby) VALUES($this->customerno,'%s','%s','%s','%s','%s','$this->today',$this->userid)";
            $SQL = sprintf($Query,Sanitise::String($disid),'0',Sanitise::String($locid),Sanitise::String($cityid),Sanitise::String($grpid));
            $this->executeQuery($SQL);
        }    
    }
    
   
     public function update_discdata($discountid,$disccode,$expdate1,$disctype,$disctype_value,$clid1,$locid,$cityid,$grpid){
        $clid2 = array_map('trim',$clid1);     
        $clid = array_unique($clid2);
        $amount="";
        $percentage="";
        $expdate = date("Y-m-d", strtotime($expdate1));
        if($disctype==1){
            $amount = $disctype_value;
        }
        elseif ($disctype==2) {
           $percentage = $disctype_value;
        }
        elseif($disctype!=1 || $disctype!=2){
            $amount="";
            $percentage="";
        }
        
        $Query = "update discount_master set discount_code='".$disccode."',amount='".$amount."', percentage='".$percentage."', updatedtime='".$this->today."', updated_by='".$this->userid."' where discountid=".$discountid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        //for client id update -start
        if(!empty($clid)){
            $clids= array();
            foreach ($clid as $key => $value){
              $clids[] = current(explode('-',$value));
            }
            $Query = "SELECT clientid FROM discount_specific WHERE discount_id='%s' AND isdeleted=0";
            $SQL = sprintf($Query,Sanitise::String($discountid));
            $this->executeQuery($SQL);
            if($this->get_rowCount() > 0){ 
                $oldclientid = array();
                while ($row = $this->get_nextRow())            
                {
                    $oldclientid[] =$row['clientid'];
                }
                
                $test1 = array_filter(array_diff($clids,$oldclientid));
                $test2 = array_filter(array_diff($oldclientid,$clids));
                
                if(!empty($test1)){
                    $this->insertclids($test1,$discountid);
                }
                if(!empty($test2)){
                   $this->Delclids($test2,$discountid);
                }
            }else{
                $this->insertclids($clids,$discountid);
            }
        }
            
        //for client id update -end
        if($locid!=""|| $cityid!=""||$grpid!=""){
            $Query1 = "update discount_specific set clientid='0',locationid='".$locid."',cityid='".$cityid."', branchid='".$grpid."', updatedtime='".$this->today."', updated_by='".$this->userid."' where discount_id=".$discountid;
            $SQL1 = sprintf($Query1);
            $this->executeQuery($SQL1);
        }
    }
    
    public function Delclids($test2,$discountid){
        foreach ($test2 as $key => $value){
            $Query1 = "update discount_specific set isdeleted=1,locationid='0',cityid='0', branchid='0', updatedtime='".$this->today."', updated_by='".$this->userid."' where clientid='".$value."' AND discount_id=".$discountid;
            $SQL1 = sprintf($Query1);
            $this->executeQuery($SQL1);
        }  
    }
    
    public function insertclids($clids,$discountid){
        foreach ($clids as $key => $value){
            $Query = "Insert into discount_specific (customerno,discount_id,clientid,locationid,cityid,branchid,entrytime,addedby) VALUES($this->customerno,'%s','%s','%s','%s','%s','$this->today',$this->userid)";
            $SQL = sprintf($Query,Sanitise::String($discountid),Sanitise::String($value),'0','0','0');
            $this->executeQuery($SQL);
        }  
    }
    
    public function trackies_service_details($sdate){
        $service_date = date('Y-m-d', strtotime($sdate));
        $Query = "select a.trackid, a.name, a.weekly_off, b.clientid, b.service_date, sum(d.expected_time) as totaltime from trackie as a";
        $Query .= " left join service_call as b on b.trackieid=a.trackid and b.customerno=a.customerno and date(b.service_date)='$service_date' and b.isdeleted=0 ";
        $Query .= " left join service_ordered as c on b.scid=c.scid and c.isdeleted=0 ";
        $Query .= " left join service_list as d on c.serviceid=d.serviceid and d.isdeleted=0 ";
        $Query .= " where a.customerno=$this->customerno and a.isdeleted=0 ";
        $Query .= " group by b.scid ";
        
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0){
            $t_order = array();
            $locdata = array();
            while ($row = $this->get_nextRow())            
            {
                $locdata[$row['trackid']][] = array(
                    'name' => $row['name'],
                    'weekly_off' => $row['weekly_off'],
                    'sdate' => $row['service_date'],
                    'clientid' => $row['clientid'],
                    'totaltime' => $row['totaltime']
                );
             
                if(isset($t_order[$row['trackid']])){
                    $t_order[$row['trackid']] += 1;
                }
                else{
                    $t_order[$row['trackid']] = is_null($row['clientid']) ? 0 : 1;
                }
            }
            asort($t_order);
            return array($locdata,'order'=>$t_order);
        }
        return false;
        
    }
    
    public function getlocationdata($q){
       $getdata= array();
       $lq= "%$q%";
       $Query = "SELECT locid,location FROM location_master WHERE customerno=$this->customerno AND location LIKE '%s' AND isdeleted=0";
       $SQL = sprintf($Query,Sanitise::String($lq));
       $this->executeQuery($SQL);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
               $getdata[] = array(
                   'id' => $row['locid'],
                   'value' => $row['location'],
                );
            }
            return $getdata;
        }    
        return null;
    }
    
     public function getgroupdata($q){
       $getdata= array();
       $lq= "%$q%";
         $Query = "select group.groupid,group.groupname from ".SPEEDDB.".`group` 
            INNER JOIN ".SPEEDDB.".groupman ON groupman.groupid = group.groupid
            INNER JOIN ".SPEEDDB.".user ON user.userid = groupman.userid
            where group.groupname LIKE '%s' AND groupman.userid=%s AND groupman.customerno=%s AND group.isdeleted=0 AND groupman.isdeleted=0 order by group.groupname ASC";
        $SQL = sprintf($Query,Sanitise::String($lq),$this->userid,$this->customerno);
       $this->executeQuery($SQL);
       if ($this->get_rowCount() > 0){
           while ($row = $this->get_nextRow())            
           {
               $getdata[] = array(
                   'id' => $row['groupid'],
                   'value' => $row['groupname'],
                );
            }
            return $getdata;
        }    
        return null; 
    }
    
      public function getdiscountdata_byid($did){
        $Query = "SELECT * FROM discount_master as dm  WHERE  dm.customerno=$this->customerno AND dm.discountid='%d' AND dm.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($did));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
         while ($row = $this->get_nextRow())      
           {
               $expdate = date('d-m-Y', strtotime($row['expiry_date']));
               $discdata[] = array(
                   'disccountid' => $row['discountid'],
                   'discount_code' => $row['discount_code'],
                   'amount' => $row['amount'],
                   'percentage' => $row['percentage'],
                   'expiry_date' => $expdate
                );
            }
            return $discdata;
        }
        return null;
    }
    
    public function getspdiscount_byid($did){
        $Query = "SELECT loc.location,ds.discount_id,ds.clientid,ds.cityid,cm.cityname,ds.locationid,ds.branchid,c.client_name FROM  discount_specific ds  LEFT JOIN client as c ON c.clientid= ds.clientid LEFT JOIN location_master as loc ON loc.locid = ds.locationid LEFT JOIN city_master as cm ON cm.cityid = ds.cityid "
                . " WHERE  ds.customerno=$this->customerno AND ds.discount_id='%d' AND ds.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($did));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
         while ($row = $this->get_nextRow())      
           {
             $branchid = $row['branchid'];
             $groupname="";
             if($branchid !=0){
             $query = "select * from ".SPEEDDB.".group where groupid=%d";
             $SQL = sprintf($query,Sanitise::String($branchid));
             $this->executeQuery($SQL);
                if($this->get_rowCount() > 0){
                while ($row = $this->get_nextRow())      
                {
                    $groupname = $row['groupname'];
                }
            }
            }
            if($row['clientid']==0)
            {
                $clientnameid = "";
            }else{
                $clientnameid = $row['clientid'].'-'.$row['client_name'];
            }    
               $discdata[] = array(
                   'disccountid' => $row['discount_id'],
                   'clientid' => $row['clientid'],
                   'locationid' => $row['locationid'],
                   'branchid' => $row['branchid'],
                   'branchname' => $groupname,
                   'client_name' => $clientnameid,
                   'location' => $row['location'],
                   'cityid'=>$row['cityid'],
                   'cityname'=>$row['cityname']
                );
            }
            return $discdata;
        }
        return null;
    }
    
    public function chkdiscountid($discountid,$clientdata){
        $Query ="select dm.expiry_date,dm.discountid, dm.amount, dm.percentage, ds.dsid, ds.locationid,ds.cityid, ds.clientid, ds.branchid from discount_master as dm
        left join discount_specific as ds ON ds.discount_id = dm.discountid and ds.isdeleted=0 
        where dm.discount_code ='%s' and dm.isdeleted=0 and dm.customerno=$this->customerno";
        $SQL = sprintf($Query,Sanitise::String($discountid));
        $this->executeQuery($SQL);
        $today = date('Y-m-d');
        if ($this->get_rowCount() > 0){
            $discdata = null;
            while ($row = $this->get_nextRow())            
            {
               
               if(!is_null($row['dsid'])){
                   if($row['clientid'] !=0 && $row['clientid']!=$clientdata['clientid']){
                       continue;
                   }
                   elseif($row['locationid']!=0 && !in_array($row['locationid'], $clientdata['locid'])){
                       continue;
                   }
                   elseif($row['cityid']!=0 && !in_array($row['cityid'], $clientdata['cityid'])){
                       continue;
                   }
                   elseif($row['branchid']!=0 && !in_array($row['branchid'], $clientdata['groupid'])){
                       continue;
                   }
                   
               }
                
                
               if($row['amount']!='0.00' && $row['amount']!=null){$discvalue = $row['amount'];$isamount = 1;}
               else{$discvalue = $row['percentage'];$isamount = 0;}
               
               $discdata = array(
                   'disccountid' => $row['discountid'],
                   'discvalue' => $discvalue,
                   'isamount' => $isamount,
                   'datestatus'=>''
                );
               
                if(strtotime($row['expiry_date']) < strtotime($today)){
                    $discdata['datestatus'] = 'error';break;
                }
                
           }
           return $discdata;
        }
        return null;
    }
    
    function update_service_status($scid,$stat){
        $Query = "update service_call  set service_status=$stat where scid=$scid and customerno=$this->customerno";
        $this->executeQuery($Query);
        return true;
    }
    
    function delete_service($scid){
        $Query = "update service_call  set isdeleted=1 where scid=$scid and customerno=$this->customerno";
        $this->executeQuery($Query);
        return true;
    }
    
    public function is_question_exists($question){
        $Query = "SELECT fid FROM feedback_master WHERE customerno=$this->customerno AND feedback_question='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($question));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        }
        else{
            return false;
        }
    }
    
    public function chk_trackie_availability($tkid,$sdate,$stme,$callid=null){
        $this_start_time = strtotime($sdate.' '.$stme);
        
        $Query = "SELECT b.soid, a.scid, a.service_date, sum(c.expected_time) as totaltime FROM `service_call` as a ";
        $Query .= " left join service_ordered as b on a.scid=b.scid and b.isdeleted=0 ";
        $Query .= " left join service_list as c on c.serviceid=b.serviceid  and c.isdeleted=0 ";
        $Query .= " where a.trackieid=$tkid and date(a.service_date) = '$sdate' and a.isdeleted=0";
        if(!is_null($callid)){
            $Query .= " and a.scid!=$callid ";
        }
        $Query .= " group by a.scid";
        
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()){
                $start_time = strtotime($row['service_date']);
                $end_time = strtotime($row['service_date'])+($row['totaltime']*60);
                
                if($start_time<=$this_start_time && $this_start_time<=$end_time){
                    return false;
                }
            }
            return true;
        }
        else{
            return true;
        }
    }
  
    public function getservices_bycatid($catid){
       $Query = "SELECT serviceid, service_name, cost, expected_time FROM service_list";
       $Query .= " WHERE customerno=$this->customerno AND cid='$catid' AND isdeleted=0 ";
       $this->executeQuery($Query);
       if ($this->get_rowCount() > 0){
           $getdata = array();
           while ($row = $this->get_nextRow())
           {
               $getdata[] = array(
                   'id' => $row['serviceid'],
                   'value' => $row['service_name'],
                   'scost'=>$row['cost'],
                   'exptime'=>$row['expected_time']
                );
            }
            return $getdata;
        }    
        return null;
    }
    
    
    
    public function is_category_exists($catname){
        $Query = "SELECT cid FROM service_category WHERE customerno=$this->customerno AND category_name='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($catname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function insert_catdata($catname){
        $Query = "Insert into service_category (customerno,category_name,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query,Sanitise::String($catname));
        $this->executeQuery($SQL);
    }
    
    public function insert_catdata_service($catname){
        $Query = "Insert into service_category (customerno,category_name,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query,Sanitise::String($catname));
        $this->executeQuery($SQL);
        return  $this->get_insertedId();
    }
    
    function update_catdata($catid,$catname){
        $Query = "update service_category set category_name='".$catname."' ,updatedtime='".$this->today."', updated_by='".$this->userid."' where cid=$catid and customerno=$this->customerno";
        $this->executeQuery($Query);
        return true;
    }
    
    function getcatdata_byid($cid){
        $Query = "SELECT cid,category_name FROM service_category WHERE customerno=$this->customerno AND cid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($cid));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
         while ($row = $this->get_nextRow())            
           {
             $catdata[]=array(
                  'cid' => $row['cid'],
                 'category_name' => $row['category_name']
             );
           }
           return $catdata;
        }
        return NULL;
    }
    
    public function add_client_address($add,$cid){
        $Query = "Insert into client_address (addressandroidid,clientid,flatno,building,society,landmark,pincode,location_name,city_name,entrytime)";
        $Query .= " VALUES('%s',$cid,'%s','%s','%s','%s','%s','%s','%s','$this->today')";
        
        $SQL = sprintf($Query,  Sanitise::String($add['addressid']),Sanitise::String($add['flatno']),Sanitise::String($add['building']),Sanitise::String($add['society']),Sanitise::String($add['landmark']),Sanitise::String($add['pincode']),Sanitise::String($add['loc']),Sanitise::String($add['city']));
        $this->executeQuery($SQL);
        $disid =  $this->get_insertedId();
        
        return $disid;
    }
    
    public function get_client_by_mobile($mobile){
        $Query = "SELECT client_name,email,password FROM client WHERE customerno=$this->customerno AND isdeleted=0 and mobile='%s'";
        $SQL = sprintf($Query, Sanitise::String($mobile));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
            while ($row = $this->get_nextRow())            
            {
                $ispassword = ($row['password']!='') ? 1 : 0;
                $client=array(
                    'name' => $row['client_name'],
                    'email' => $row['email'],
                    'ispassword' => $ispassword
                );
            }
            return $client;
        }
        return NULL;
    }
    
    public function add_temp_cl($city,$loc=''){
        $Query = "Insert into temp_city_loc (location_name,city_name,entrytime)";
        $Query .= " VALUES('%s','%s', '$this->today')";
        
        $SQL = sprintf($Query,Sanitise::String($loc),Sanitise::String($city));
        $this->executeQuery($SQL);
        
        return  $this->get_insertedId();
        
    }
    
    public function cancel_aptmnt($clientid, $callid){
        $Query = "update service_call set isdeleted=1 where clientid=$clientid and scid=$callid";
        $this->executeQuery($Query);
    }
    
    public function getserviceordered($id){
        $query = "select sc.category_name,so.serviceid,sl.service_name,sl.cost,sl.expected_time,sl.cid, so.scid from service_ordered as so  
        left join service_list as sl on sl.serviceid = so.serviceid    
        left join service_category as sc on sc.cid = sl.cid
        WHERE so.isdeleted=0 and so.scid='%s'";
        $SQL = sprintf($query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
            while ($row = $this->get_nextRow())            
            {
                $serviceorder[]=array(  
                    'catname' => $row['category_name'],
                    'service_name' => $row['service_name'],
                    'cost' => $row['cost'],
                    'expected_time' => $row['expected_time'],
                );
            }
            return $serviceorder;
        }
        return NULL;
    }
    
    public function getservicecalldata($id){
        $query = "select ds.branchid,ds.locationid,ds.cityid,ds.clientid,tr.name as trackname,lm.location,cm.cityname,ca.flatno,ca.building,ca.society,ca.landmark,ca.pincode,ca.area,ca.location_name,ca.city_name,ca.locationid, c.clientno,c.clientno,c.client_name,c.dob,c.mobile,c.phone,c.email,c.is_member,sc.scid, sc.trackieid, sc.service_status, sc.client_add_id, sc.discountid, sc.service_date "
                . "from service_call as sc  left join client as c  on c.clientid = sc.clientid "
                . " left join client_address as ca  on ca.client_add_id = sc.client_add_id "
                . " left join city_master as cm on cm.cityid = ca.cityid"
                . " left join location_master as lm on lm.locid = ca.locationid "
                . "left join discount_specific as ds on ds.discount_id = sc.discountid"
                . " left join trackie as tr on tr.trackid = sc.trackieid where sc.scid='%s'";
        $SQL = sprintf($query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
            while ($row = $this->get_nextRow())            
            {
                $servicedata[]=array(  
                    'discountid' => $row['discountid'],
                    'clientid' => $row['clientid'],
                    'cityid'=>$row['cityid'],
                    'locationid'=>$row['locationid'],
                    'groupid'=>$row['branchid'],
                    'client_name'=>$row['client_name'],
                    'cmobile'=>$row['mobile'],
                    'is_member'=>$row['is_member'],
                    'cphone'=>$row['phone'],
                    'cityname'=>$row['city_name'],
                    'cemail'=>$row['email'],
                    'tname'=>$row['trackname'],
                    'dob'=>$row['dob']
                );
            }
            return $servicedata;
        }
        return NULL;
    }
    
    public function get_service_details($id){
        $query = "select sc.scid,sc.clientid, sc.client_add_id, sc.service_status, sc.discountid, dm.discount_code, GROUP_CONCAT(sl.service_name SEPARATOR ',') as snames, sc.service_date, ";
        $query .= " GROUP_CONCAT(sl.cost SEPARATOR ',') as scosts,  GROUP_CONCAT(so.serviceid SEPARATOR ',') as serviceids, GROUP_CONCAT(so.quantity SEPARATOR ',') AS sqnties, GROUP_CONCAT(sl.expected_time SEPARATOR ',') AS stime from service_call as sc ";
        $query .= " inner join service_ordered as so on so.scid=sc.scid and so.isdeleted=0";
        $query .= " inner join service_list as sl on sl.serviceid=so.serviceid and sl.isdeleted=0";
        $query .= " left join discount_master as dm on sc.discountid!=0 and dm.discountid=sc.discountid and dm.isdeleted=0";
        $query .= " where sc.scid=$id and sc.isdeleted=0 group by sc.scid";
        
        $this->executeQuery($query);
        if ($this->get_rowCount() > 0){
            $servicedata = array();
            while ($row = $this->get_nextRow())
            {
                $services = array(
                    'serviceids' => explode(',',$row['serviceids']),
                    'snames' => explode(',',$row['snames']),
                    'sqnties' => explode(',',$row['sqnties']),
                    'scosts' => explode(',',$row['scosts']),
                    'stime' => explode(',',$row['stime'])
                );
                $servicedata = array(
                    'scid' => $row['scid'],
                    'clientid' => $row['clientid'],
                    'cur_address' => $row['client_add_id'],
                    'callTime' => date('H:i', strtotime($row['service_date'])),
                    'sstatus' => $row['service_status'],
                    'discid' => $row['discountid'],
                    'discode' => $row['discount_code'],
                    'services' => $services,
                );
            }
            return $servicedata;
        }
        return NULL;
    }

    public function chkdiscountid1($discountid,$clientid,$cityid,$locationid,$groupid){
        $Query ="select dm.expiry_date,dm.discountid,dm.discount_code, dm.amount, dm.percentage, ds.dsid, ds.locationid,ds.cityid, ds.clientid, ds.branchid from discount_master as dm
        left join discount_specific as ds ON ds.discount_id = dm.discountid and ds.isdeleted=0 
        where dm.discountid ='%s' and dm.isdeleted=0 and dm.customerno=$this->customerno";
        $SQL = sprintf($Query,Sanitise::String($discountid)); 
        $this->executeQuery($SQL);
        $today = date('Y-m-d');
        if ($this->get_rowCount() > 0){
            $discdata = null;
            while ($row = $this->get_nextRow())            
            {
               
               if(!is_null($row['dsid'])){
                   if($row['clientid'] !=0 && $row['clientid']!=$clientid){
                       continue;
                   }
                   elseif($row['locationid']!=0 && !in_array($row['locationid'], $locationid)){
                       continue;
                   }
                   elseif($row['cityid']!=0 && !in_array($row['cityid'],$cityid)){
                       continue;
                   }
                   elseif($row['branchid']!=0 && !in_array($row['branchid'],$groupid)){
                       continue;
                   }
                   
               }
                
               if($row['amount']!='0.00' && $row['amount']!=null){$discvalue = $row['amount'];$isamount = 1;}
               else{$discvalue = $row['percentage'];$isamount = 0;}
               
               $discdata = array(
                   'disccountid' => $row['discountid'],
                   'discamt' => $row['amount'],
                   'percentage' => $row['percentage'],
                   'disccode'=>$row['discount_code'],
                   'expdate'=>$row['expiry_date']
                  
                );
               
                if(strtotime($row['expiry_date']) < strtotime($today)){
                    $discdata['datestatus'] = 0;
                }else{
                    $discdata['datestatus'] = 1;
                }
                
           }
           return $discdata;
        }
        return null;
    }
    
    public function is_membershipcode_exists($membercode){
           $SQL = "SELECT package_code FROM package_master WHERE customerno=$this->customerno AND package_code='".$membercode."' AND isdeleted=0";
           $this->executeQuery($SQL);
           if ($this->get_rowCount() > 0){
               return true;
           }else{
               return false;
           }
    }
           
    public function insert_membercodedata($membershipcode,$amount,$membervalidity){
        $member_validity1 = date("Y-m-d", strtotime($membervalidity));
        $Query = "Insert into package_master (customerno,package_code,amount,validity,entrytime,addedby)VALUES($this->customerno,'%s','%s','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query,Sanitise::String($membershipcode),Sanitise::String($amount),Sanitise::String($member_validity1)); 
        $this->executeQuery($SQL);
    }    
    
    public function update_membercodedata($pckgid,$membershipcode,$amount,$membervalidity){
        $member_validity1 = date("Y-m-d", strtotime($membervalidity));
        $Query = "update package_master set package_code='".$membershipcode."',amount='".$amount."',validity='".$member_validity1."',updatedtime='".$this->today."', updated_by='".$this->userid."' where pckgid=".$pckgid;
        $SQL = sprintf($Query); 
        $this->executeQuery($SQL);
    }
    
      public function getpackagedata_byid($id){
        
        $Query = "SELECT * FROM package_master WHERE customerno=$this->customerno AND pckgid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
         while ($row = $this->get_nextRow())            
           {
               $packagedata[] = array(
                   'pckgid' => $row['pckgid'],
                   'package_code' => $row['package_code'],
                   'amount'=>$row['amount'],
                   'validity'=>$row['validity']
                );
            }
            return $packagedata;
        }
        return null;
    }
    
    public function chk_trackie_exeption_services($sids,$tkid){
        $sids1 = implode(',', $sids);
        $Query = "SELECT service_name FROM `exceptional_services` as es left join  service_list as sl on sl.serviceid = es.serviceid WHERE es.`trackieid` ='%s' AND es.serviceid IN (%s) AND es.isdeleted=0 AND es.customerno = $this->customerno";
        $sql = sprintf($Query,  Sanitise::string($tkid), Sanitise::String($sids1));
        $this->executeQuery($sql);
        if($this->get_rowCount()>0){
            while ($row = $this->get_nextRow())            
            {
                $exp_services[] = $row['service_name'];
            }
            return $exp_services;
        }else{
            return null;
        }    
    }
    
    public function chk_trackie_holiday($tkid,$sdte,$weeklyoff_arr){
        $ids  = $this->get_trackie_weeklyoffdata($tkid);
        $status = 0;
        
        if(isset($ids[0]['weeklyoff']) && $ids[0]['weeklyoff']!=''){
            $weekids = explode(',', $ids[0]['weeklyoff']);

            $week_name = array();
            foreach($weekids as $val){
                $week_name[] = $weeklyoff_arr[$val];
            }
            $timestamp = strtotime($sdte);
            $day = date('l', $timestamp);
            if(in_array($day, $week_name)){
               $status = 1; 
            }
        }
        return $status;
    }
    
    
    public function get_trackie_weeklyoffdata($id){
        $Query = "SELECT a.trackid,a.name,a.weekly_off FROM trackie as a ";
        $Query .= "  WHERE a.trackid =$id AND a.customerno=$this->customerno and a.isdeleted=0";
        $SQL = $Query; 
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0){
            $getdata = array();
            while ($row = $this->get_nextRow())            
            {
               $getdata[] = array(
                   'tid' => $row['trackid'],
                   'tname' => $row['name'],
                   'weeklyoff'=>$row['weekly_off']
                );
            }
            return $getdata;
        }    
        return null;
    }
}
?>

