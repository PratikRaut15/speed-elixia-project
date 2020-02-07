<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/system/utilities.php';

class ExceptionManager extends VersionedManager{
    
    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }
        
    public function safe_exception_data($data){
        
        $clean_data = array();
        $clean_data['userid'] = $data['userid'];
        $clean_data['send_sms'] = ($data['send_sms']==='on') ? 1 : 0;
        $clean_data['send_email'] = ($data['send_email']==='on') ? 1 : 0;
        $clean_data['send_telephone'] = ($data['send_telephone']==='on') ? 1 : 0;
        $clean_data['send_mobilenotification'] = ($data['send_mobilenotification']==='on') ? 1 : 0;
        $clean_data['start_checkpoint_id'] = (int)$data['start_checkpoint_id'];
        $clean_data['end_checkpoint_id'] = (int)$data['end_checkpoint_id'];
        $clean_data['report_type'] = GetSafeValueString($data['report_type'], 'string');
        $clean_data['report_type_condition'] = GetSafeValueString($data['report_type_condition'], 'string');
        $clean_data['report_type_val'] = (float)$data['report_type_val'];
        
        return $clean_data;
    }
    
    public function check_exception($c){
        
        $query = 'select exception_id from trip_exception_alerts where customerno=%d and userid=%d and  start_checkpoint_id=%d and end_checkpoint_id=%d 
                and report_type="%s" and report_type_condition="%s" and report_type_val=%.2f and is_deleted=0';
        $select_query = sprintf($query, $this->_Customerno, $c['userid'], $c['start_checkpoint_id'], $c['end_checkpoint_id'], 
                $c['report_type'], $c['report_type_condition'], $c['report_type_val']);
        
        $this->_databaseManager->executeQuery($select_query);
        if ($this->_databaseManager->get_rowCount() > 0){
            return true;
        }
        
    }
    
    public function add_exception_alert($data){
        
        $c = $this->safe_exception_data($data); //clean data
        
        if($this->check_exception($c)){
            return array(false, 'result'=>'Same alert already exists');
        }
        foreach($data['vehicles'] as $vehid){
            $query = 'insert into trip_exception_alerts values(null, %d, %d, %d,%d,%d ,%d, %d, %d, "%s", "%s", %.2f, %d, 0, 0, now(), null)';
             $insert_query = sprintf($query,
             $this->_Customerno, 
             $c['userid'], 
             $c['send_sms'], 
             $c['send_email'], 
             $c['send_telephone'], 
             $c['send_mobilenotification'], 
             $c['start_checkpoint_id'], 
             $c['end_checkpoint_id'], 
             $c['report_type'], 
             $c['report_type_condition'], 
             $c['report_type_val'], 
             $vehid);

            $this->_databaseManager->executeQuery($insert_query);
        }
        
        return array(true, 'result'=>'Added Successfully');
        
    }

    public function get_exception($userid){
        
        $query = 'select * from trip_exception_alerts where customerno=%d and userid=%d and is_deleted=0';
        $select_query = sprintf($query, $this->_Customerno, $userid);
        
        $this->_databaseManager->executeQuery($select_query);
        if ($this->_databaseManager->get_rowCount() > 0){
            $data = array();
            while($row = $this->_databaseManager->get_nextRow()){
                $data[] = array(
                    'exception_id' => $row['exception_id'],
                    'send_sms' => $row['send_sms'],
                    'send_email' => $row['send_email'],
                    'send_telephone' => $row['send_telephone'],
                    'start_checkpoint_id' => $row['start_checkpoint_id'],
                    'end_checkpoint_id' => $row['end_checkpoint_id'],
                    'report_type' => $row['report_type'],
                    'report_type_condition' => $row['report_type_condition'],
                    'report_type_val' => $row['report_type_val'],
                    'vehicleid' => $row['vehicleid']
                );
                
            }
            return array(true, 'result'=>$data);
        }
        else{
            return array(false, 'result'=>'No Data');
        }
        
    }
    
    public function update_exception($userid, $exception_id){
        
        $query = 'update trip_exception_alerts set deleted_by=%d,is_deleted=1 where exception_id=%d and customerno=%d';
        $update_query = sprintf($query, $userid, $exception_id, $this->_Customerno);
        
        $this->_databaseManager->executeQuery($update_query);
        
        return array(true, 'result'=>'Updated Successfully');
        
    }
    
    public function update_exception_userid($del_id, $userid){
        
        $query = 'update trip_exception_alerts set deleted_by=%d,is_deleted=1 where userid=%d and customerno=%d';
        $update_query = sprintf($query, $userid, $del_id, $this->_Customerno);
        
        $this->_databaseManager->executeQuery($update_query);
        
        return array(true, 'result'=>'Updated Successfully');
        
    }

    public function get_exception_userwise(){
        
        $query = 'select a.*, b.email, b.phone, b.realname, b.userkey from trip_exception_alerts as a left join '.DB_PARENT.'.user as b on a.userid=b.userid where a.is_deleted=0 and (a.send_sms=1 or a.send_email=1) order by a.userid';
        
        $this->_databaseManager->executeQuery($query);
        
        if ($this->_databaseManager->get_rowCount() > 0){
            $data = array();
            while($row = $this->_databaseManager->get_nextRow()){
                $userid = $row['userid'];
                $data[$userid][] = array(
                    'customerno' => $row['customerno'],
                    'exception_id' => $row['exception_id'],
                    'send_sms' => $row['send_sms'],
                    'send_email' => $row['send_email'],
                    'start_checkpoint_id' => $row['start_checkpoint_id'],
                    'end_checkpoint_id' => $row['end_checkpoint_id'],
                    'report_type' => $row['report_type'],
                    'report_type_condition' => $row['report_type_condition'],
                    'report_type_val' => $row['report_type_val'],
                    'vehicleid' => $row['vehicleid'],
                    'trip_endtime_flag' => $row['trip_endtime_flag'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                    'realname' => $row['realname'],
                    'userkey' => sha1($row['userkey'])
                );
            }
            return $data;
        }
        else{
            return null;
        }
        
    }
    
    public function update_flag($excep_id, $enddate){
        
        $query = "update trip_exception_alerts set trip_endtime_flag='$enddate' where exception_id=$excep_id";
        
        $this->_databaseManager->executeQuery($query);
        
        return true;
        
    }
    
}

?>