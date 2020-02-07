<?php
include_once("../session.php");
include_once("../db.php");
include_once("../lib/system/utilities.php");
include_once("../lib/system/Validator.php");
include_once("../lib/model/VODevices.php");
include_once("../lib/system/DatabaseManager.php");
include_once '../lib/system/Sanitise.php';
include_once '../lib/system/Date.php';

class RegistrationManager
{
    public function checkregistration($androidid)
    {
        $SQL = sprintf("SELECT * from `devices`
                        INNER JOIN trackee ON trackee.trackeeid = devices.trackeeid 
                        WHERE devices.androidid = '%s' limit 1"
                        , Validator::escapeCharacters($androidid));
        $db = new DatabaseManager();
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
                $row = $db->get_nextRow();
                $device = new VODevices();
                $device->deviceid = $row["deviceid"];
                $device->customerno = $row["customerno"];
                $device->devicename = $row["devicename"];
                $device->devicekey = $row["devicekey"];
                $device->isregistered = $row["isregistered"];
                $device->androidid = $row["androidid"];
                $device->trackeeid = $row["trackeeid"];
                $device->phoneno = $row["phoneno"];                                
                $device->devicelat = $row["devicelat"];
                $device->devicelong = $row["devicelong"];
                $device->lastupdated = $row["lastupdated"];                                
                $device->registeredon = $row["registeredon"];
                $device->registrationapprovedon = $row["registrationapprovedon"];
                $device->pushitems = $row["pushitems"];
                $device->pushmessages = $row["pushmessages"];
                return $device;
        }
        return null;        
    }   
    
    public function submitregistration($androidid, $devicekey)
    {
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf("select * from `devices` where devicekey = '%s' and isregistered = 0 limit 1"
                        , Validator::escapeCharacters($devicekey));
        $db = new DatabaseManager();
                $db->executeQuery($SQL);
        while ($row = $db->get_nextRow())
        {
            $device = new VODevices();
            $device->customerno = $row["customerno"];
            $device->devicekey = $row["devicekey"];            
        }
        if ($db->get_rowCount() > 0) {       
            $SQLUpdate = sprintf("UPDATE devices SET androidid = '%s', isregistered = 1, registrationapprovedon = '%s' WHERE devicekey = '%s'"
                            , Validator::escapeCharacters($androidid)
                            , Sanitise::DateTime($today)                    
                            , Validator::escapeCharacters($devicekey));
            $db = new DatabaseManager();
                    $db->executeQuery($SQLUpdate);
            return $device;
        }
        return null;
    }           
    
    public function updatedevicedata($devicelat, $devicelong, $devicekey)
    {
        $today = date("Y-m-d H:i:s");        
        $db = new DatabaseManager();
        $SQL = sprintf( "Update `devices` SET `devicelat` =  %f, `devicelong` = %f, `lastupdated` = '%s' WHERE devicekey = %d", Sanitise::Float($devicelat), Sanitise::Float($devicelong), Sanitise::DateTime($today), Sanitise::String($devicekey));
        $db->executeQuery($SQL);                                                

        $deviceQuery = sprintf("SELECT * FROM `devices` WHERE devicekey = %d",
            Sanitise::String($devicekey));
        $db->executeQuery($deviceQuery);		
		
        if ($db->get_rowCount() == 1) 
        {
            while ($row = $db->get_nextRow())            
            {
                // Do something
                $deviceid = $row["deviceid"];
                $customerno = $row["customerno"];
                $trackeeid = $row["trackeeid"];
                $devicelat = $row["devicelat"];
                $devicelong = $row["devicelong"];
                $lastupdated = $row["lastupdated"];                    
                $devicekey = $row["devicekey"];                                        
            }
        }
         $SQL = sprintf("INSERT INTO devicehistory (`deviceid`,`customerno`,`trackeeid`,`devicelat`,`devicelong`,`lastupdated`,`devicekey`) VALUES ('%d','%d','%d','%f','%f','%s','%s')",
             Sanitise::Long($deviceid), Sanitise::Long($customerno), Sanitise::Long($trackeeid), 
             Sanitise::Float($devicelat), Sanitise::Float($devicelong), Sanitise::String($lastupdated), Sanitise::String($devicekey));
        $db->executeQuery($SQL);       
    }
    
    public function getfreqdata($customerno)
    {
        $db = new DatabaseManager();
        $Query = sprintf("SELECT freqdata FROM customer WHERE customerno = %d",
            $customerno);
        $db->executeQuery($Query);		
		
        if ($db->get_rowCount() == 1) 
        {
            while ($row = $db->get_nextRow())            
            {
                $freqdata = $row["freqdata"];
                return $freqdata;            
            }
        }
        return null;                
    }    
}