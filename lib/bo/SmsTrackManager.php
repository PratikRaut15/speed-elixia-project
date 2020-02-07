<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOSmsTrack.php';
include_once '../../lib/system/Date.php';


class SmsTrackManager extends VersionedManager{
    
    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }
    
    public function get_all_simdata()
    {
        $simdatas = Array();
        $Query = "SELECT * FROM `smstrack` where customer_no=%d AND isdeleted=0";
        $simQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($simQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $simdata = new VOSmsTrack();
                $simdata->id = $row['id'];
                $simdata->name = $row['name'];
                $simdata->customer_no = $row['customer_no'];
                $simdata->phoneno = $row['phoneno'];
                $simdata->uid = $row['uid'];
                $simdatas[] = $simdata;
            }
            return $simdatas;            
        }
        return null;
    }  
    
    public function get_all_userphone()
    {
        $simdatas = Array();
        $Query = "SELECT * FROM ".DB_PARENT.".`user` where customerno=%d AND isdeleted=0";
        $simQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($simQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row['phone'] != ''){
                $simdata = new VOSmsTrack();
                //$simdata->id = $row['id'];
                $simdata->name = $row['realname'];
                $simdata->customer_no = $row['customerno'];
                $simdata->phoneno = $row['phone'];
                $simdata->id = $row['userid'];
                $simdatas[] = $simdata;
                }
            }
            return $simdatas;            
        }
        return null;
    } 
    
    public function get_simdata($trackid)
    {
        $Query = "SELECT * FROM `smstrack` where id=%d AND customer_no=%d AND  isdeleted=0";
        $simQuery = sprintf($Query, Sanitise::Long($trackid), $this->_Customerno);
        $this->_databaseManager->executeQuery($simQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $simdata = new VOSmsTrack();
                $simdata->id = $row['id'];
                $simdata->name = $row['name'];
                $simdata->customer_no = $row['customer_no'];
                $simdata->phoneno = $row['phoneno'];
                $simdata->uid = $row['uid'];
            }
            return $simdata;            
        }
        return null;
    }  
    
    public function get_userdata($trackid)
    {
        $Query = "SELECT * FROM ".DB_PARENT.".`user` where userid=%d AND customerno=%d AND isdeleted=0";
        $simQuery = sprintf($Query, Sanitise::Long($trackid), $this->_Customerno);
        $this->_databaseManager->executeQuery($simQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $simdata = new VOSmsTrack();
                $simdata->id = $row['userid'];
                $simdata->name = $row['realname'];
                $simdata->customer_no = $row['customerno'];
                $simdata->phoneno = $row['phone'];
            }
            return $simdata;            
        }
        return null;
    }  
    
    public function get_all_simphone()
    {
        $simdatas = Array();
        $Query = "SELECT phoneno FROM `smstrack` where customer_no=%d AND isdeleted=0";
        $simQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($simQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $simdata = new VOSmsTrack();
                $simdata->phoneno = $row['phoneno'];
                $simdatas[] = $simdata;
            }
            return $simdatas;            
        }
        return null;
    }
 
    public function add_smstrack($name, $phoneno, $userid)
    {
        $Query = "INSERT INTO smstrack (name,customer_no,phoneno,uid,isdeleted,timestamp) VALUES ('%s','%d','%s','%d','0','%s')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($name), $this->_Customerno, Sanitise::String($phoneno), Sanitise::Long($userid), Sanitise::DateTime($todays));
        $this->_databaseManager->executeQuery($SQL);
        //$route->routeid = $this->_databaseManager->get_insertedId();        
    }
 
    public function edit_smstrack($trackid, $name, $phoneno, $userid)
    {
        $Query = "UPDATE smstrack SET `name`= '%s', `phoneno`='%s', `uid` = %d, `timestamp`='%s' WHERE id=%d AND customer_no=%d";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($name), Sanitise::String($phoneno), Sanitise::Long($userid), Sanitise::DateTime($todays), Sanitise::Long($trackid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        //$route->routeid = $this->_databaseManager->get_insertedId();        
    }
 
    public function edit_userphone($userid, $name, $phoneno, $modifiedby)
    {
        $Query = "UPDATE user SET `realname`= '%s', `phone`='%s', `modifiedby` = %d WHERE userid=%d AND customerno=%d";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($name), Sanitise::String($phoneno), Sanitise::Long($modifiedby), Sanitise::Long($userid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        //$route->routeid = $this->_databaseManager->get_insertedId();        
    }
    
    public function DeleteSmsTrack($trackid, $userid)
    {
        $Query = "UPDATE smstrack SET isdeleted=1,uid=%d WHERE id=%d AND customer_no=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($trackid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL); 
        
    }
}