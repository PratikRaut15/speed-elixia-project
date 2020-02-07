<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/model/VODevices.php';
include_once("lib/system/Date.php");

class DeviceManager extends VersionedManager
{
    public function DeviceManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }
    
    public function get_all_devices() 
    {
        $devices = Array();
        $devicesQuery = sprintf("SELECT *, Now() as today FROM `devices` where customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->trackeeid = $row['trackeeid'];                
                if($row["isregistered"] == 1)
                {
                    $device->status = "Valid";
                }
                else
                {
                    $device->status = "Expired";
                }
                $device->today = $row["today"];
                $device->contract = $row["contract"];
                $device->registrationapprovedon = $row["registrationapprovedon"];                
                $devices[] = $device;
            }
            return $devices;            
        }
        return null;
    }        
    
    public function getregistereddevices() 
    {
        $devices = Array();
        $devicesQuery = sprintf("SELECT *, Now() as today FROM `devices` where isregistered = 1 AND customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row['trackeeid'] != 0)
                {
                    $device = new VODevices();
                    $device->deviceid = $row['deviceid'];
                    $device->devicekey = $row['devicekey'];
                    $device->trackeeid = $row['trackeeid'];                
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];                                
                    $device->today = $row["today"];
                    $device->contract = $row["contract"];
                    $device->registrationapprovedon = $row["registrationapprovedon"];
                    $devices[] = $device;
                }
            }
            return $devices;            
        }
        return null;
    }        
    
    public function getregistereddevicesfordisplay() 
    {
        $devices = Array();
        $devicesQuery = sprintf("SELECT * FROM `devices`
            INNER JOIN trackee ON trackee.trackeeid = devices.trackeeid
            where devices.isregistered = 1 AND devices.customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row['trackeeid'] != 0)
                {
                    $device = new VODevices();
                    $device->id = $row['deviceid'];
                    $device->devicekey = $row['devicekey'];
                    $device->tname = $row["tname"];
                    $device->contract = $row["contract"]." months";
                    $device->registrationapprovedon = date("F d,Y",strtotime($row["registrationapprovedon"]));
                    $device->lastupdated = date("M j, g:i a",strtotime($row["lastupdated"]));                    
                    if($row["contract"] == 1)
                    {
                        $device->contractend = strtotime(date("Y-m-d", strtotime($row["registrationapprovedon"])) . " + ".$row["contract"]." month");
                        $device->contractend =date("F d,Y",$device->contractend);
                    }
                    else
                    {
                        $device->contractend = strtotime(date("Y-m-d", strtotime($row["registrationapprovedon"])) . " + ".$row["contract"]." months");
                        $device->contractend =date("F d,Y",$device->contractend);                        
                    }
                    $devices[] = $device;
                }
            }
            return $devices;            
        }
        return null;
    }            
    
    public function getregistereddevicesforlogin() 
    {
        $devices = Array();
        $devicesQuery = sprintf("SELECT *, Now() as today FROM `devices` where isregistered = 1 AND customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->trackeeid = $row['trackeeid'];                
                $device->devicelat = $row['devicelat'];
                $device->devicelong = $row['devicelong'];                                
                $device->today = $row["today"];
                $device->contract = $row["contract"];
                $device->registrationapprovedon = $row["registrationapprovedon"];
                $devices[] = $device;
            }
            return $devices;            
        }
        return null;
    }            
    
    public function gettinfodate($trackeeid, $fromdate, $todate, $fromtime, $totime) 
    {
        $fromdate = MakeMySQLDate($fromdate);
        $todate = MakeMySQLDate($todate);
        $startdate = $fromdate." ".$fromtime.":00:00";
        $enddate = $todate." ".$totime.":00:00";
        $devices = Array();
        $lat_next = 0;
        $long_next = 0;
        $devicesQuery = sprintf("SELECT * FROM `devicehistory` where trackeeid=%d AND customerno=%d AND lastupdated BETWEEN '%s' AND '%s' ORDER BY lastupdated DESC",
            Sanitise::Long($trackeeid), $this->_Customerno, Sanitise::String($startdate), Sanitise::String($enddate));
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->trackeeid = $row['trackeeid'];                
                $device->devicelat = $row['devicelat'];
                $device->devicelong = $row['devicelong'];                                
                $device->lastupdated = $row["lastupdated"];
                $device->latnext = $lat_next;
                $device->longnext = $long_next;
                $devices[] = $device;
                $lat_next = $row['devicelat'];
                $long_next = $row['devicelong'];
            }
            return $devices;            
        }
        return null;
    }            
        
    public function getdevicefromtrackee($trackeeid) 
    {
        $devicesQuery = sprintf("SELECT *, Now() as today FROM `devices` where trackeeid = %d AND customerno=%d",
            Sanitise::String($trackeeid),    
            $this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->trackeeid = $row['trackeeid'];                
                $device->devicelat = $row['devicelat'];
                $device->devicelong = $row['devicelong'];       
                $device->lastupdated = $row['lastupdated'];
                $date = new Date();
                
                $today = $date->add_hours($row['today'], 0);                        
                $device->today = $today;
            }
            return $device;            
        }
        return null;
    }            
    
    public function mapdevicetotrackee($deviceid, $trackeeid)
    {
        $SQL = sprintf( "Update devices
                        Set `trackeeid`=%d
                        WHERE deviceid = %d AND customerno = %d",
                        Sanitise::Long($trackeeid),
                        Sanitise::Long($deviceid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }
    
    public function demapdevice($deviceid)
    {
        $SQL = sprintf( "Update devices
                        Set `trackeeid`=0
                        WHERE deviceid = %d AND customerno = %d",
                        Sanitise::Long($deviceid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }        
    
    public function deregister($deviceid)
    {
        $SQL = sprintf( "Update devices
                        Set `isregistered`=0,
                        `androidid`=''
                        WHERE deviceid = %d AND customerno = %d",
                        Sanitise::Long($deviceid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }            
}