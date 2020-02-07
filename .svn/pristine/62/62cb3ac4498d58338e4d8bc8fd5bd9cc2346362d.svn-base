<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/system/Date.php';
include_once '../../lib/model/VODevices.php';
include_once '../../lib/model/VOCustomFields.php';
include_once '../../lib/model/VOCommunicationQueue.php';

class GeneralManager extends VersionedManager
{
    public function GeneralManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
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
                $device->customerno = $row['customerno'];    
                $device->trackeeid = $row["trackeeid"];
                $device->itemdel = $row["itemdelivery"];
                $device->messaging = $row["messaging"];  
                $device->pushitems = $row["pushitems"];
                $device->pushmessages = $row["pushmessages"];
                $device->pushservice = $row["pushservice"];
                $device->pushservicelist = $row["pushservicelist"];                
                $device->pushremarks = $row["pushremarks"];                
                $device->pushfeedback = $row["pushfeedback"];                                
                $device->pushform = $row["pushform"];                                                
                $device->service = $row["service"];
            }
            return $device;            
        }
        return null;        
    }                   
    
    public function getcustomfields() 
        {
            $customs = Array();
            $Query = sprintf("SELECT * FROM `customfield` where customerno=%d",$this->_Customerno);
            $this->_databaseManager->executeQuery($Query);		

            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $custom = new VOCustomFields();
                    $custom->customfieldid = $row['customfieldid'];
                    $custom->defaultname = $row['defaultname'];
                    $custom->usecustom = $row['usecustom'];
                    $custom->customname = $row['customname'];
                    $customs[] = $custom;
                }
                return $customs;            
            }
            return null;
        }

        public function markcustomread($trackeeid)
        {
            $SQL = sprintf( "Update trackee
                            Set `pushcustom`=0
                            WHERE customerno = %d AND trackeeid = %d",
                            $this->_Customerno,                         
                            Sanitise::Long($trackeeid));
            $this->_databaseManager->executeQuery($SQL);                                        
        }   

        public function updatepushes($trackeeid)
        {
            $SQL = sprintf( "Update trackee
                            Set `pushcustom`=1, `pushservice`=1, `pushremarks`=1, `pushfeedback`=1, `pushservicelist`=1
                            WHERE customerno = %d AND trackeeid = %d",
                            $this->_Customerno,                         
                            Sanitise::Long($trackeeid));
            $this->_databaseManager->executeQuery($SQL);                                        
        }           
        
        public function checkcustom($trackeeid) 
        {
            $customs = Array();
            $Query = sprintf("SELECT pushcustom FROM `trackee` where trackeeid=%d AND customerno=%d",Sanitise::Long($trackeeid),$this->_Customerno);
            $this->_databaseManager->executeQuery($Query);		

            if ($this->_databaseManager->get_rowCount() == 1) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    if($row['pushcustom'] == 1)
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
                return false;            
            }
            return false;
        }        
        
    private function Insert($queue)
    {
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO communicationqueue
                        (`type`,`email`,`phone`,`subject`,`message`,`datecreated`,`customerno`) VALUES
                        ( '%d','%s','%s','%s','%s','%s','%d')",
        Sanitise::Long($queue->type),                
        Sanitise::String($queue->email),                
        Sanitise::String($queue->phone),
        Sanitise::String($queue->subject),
        Sanitise::String($queue->message),
        Sanitise::DateTime($today),
        Sanitise::Long($queue->customerno));        
        $this->_databaseManager->executeQuery($SQL);
    }            
}
