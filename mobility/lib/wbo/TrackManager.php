<?php
include_once("../../../session.php");
include_once("../../../db.php");
include_once("../../../lib/system/utilities.php");
include_once("../../../lib/system/Validator.php");
include_once("../../../lib/model/VODevices.php");
include_once '../../../lib/system/VersionedManager.php';
include_once '../../../lib/system/Sanitise.php';
include_once '../../../lib/system/Date.php';

class TrackManager extends VersionedManager
{
    public function TrackManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    
    public function updatedevicedata($devicelat, $devicelong, $devicekey)
    {
        $date = new Date();
        $today = $date->add_hours(date("Y-m-d H:i:s"), 1);
        $SQL = sprintf( "Update `devices` SET `devicelat` =  %f, `devicelong` = %f, `lastupdated` = '%s' WHERE devicekey = %d", Sanitise::Float($devicelat), Sanitise::Float($devicelong), Sanitise::DateTime($today), Sanitise::String($devicekey));
        $this->_databaseManager->executeQuery($SQL);                                                
    }
    
    public function getdatafromdevicekey($devicekey) 
    {
        $deviceQuery = sprintf("SELECT * FROM `devices` INNER JOIN customer ON devices.customerno = customer.customerno INNER JOIN trackee ON trackee.trackeeid = devices.trackeeid where devices.devicekey = '%s' AND devices.customerno=%d",
            Sanitise::String($devicekey),    
            $this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->tname = $row['tname'];
                $device->deviceid = $row['deviceid'];
                $device->lastupdated = $row['lastupdated'];                
                $device->trackeeid = $row["trackeeid"];
            }
            return $device;            
        }
        return null;        
    }                       
}