<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOAlert.php';
include_once '../../lib/system/Date.php';

class ComHistoryManager extends VersionedManager{
    
    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function InsertAlert($queue)
    {
        $today = date("Y-m-d H:i:s");
        $SQL = sprintf( "INSERT INTO ".DB_PARENT.".comhistory
                        (`customerno`,`userid`,`comtype`,`type`,`status`,`lat`,`long`,`vehicleid`,`timeadded`,`timesent`) VALUES
                        ( '%d','%d','%d','%d','%d','%d','%d','%d','%s','%s')",
        Sanitise::Long($queue->customerno),
        Sanitise::Long($queue->userid),
        Sanitise::Long($queue->comtype),
        Sanitise::Long($queue->type),
        Sanitise::Long($queue->status),                
        Sanitise::Long($queue->lat),               
        Sanitise::Long($queue->long),               
        Sanitise::Long($queue->vehicleid),
        Sanitise::DateTime($today),      
        Sanitise::DateTime($today));        
        $this->_databaseManager->executeQuery($SQL);
    }
    
    public function get_status_id($status)
    {
        $Query = "SELECT id FROM ".DB_PARENT.".status WHERE status='%s'";
        $alertQuery = sprintf($Query, Sanitise::String($status));
        $this->_databaseManager->executeQuery($alertQuery);		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $alertvo = new VOAlert();
                $alertvo->statusid = $row['id'];
            }
            return $alertvo;            
        }
        return null;
    }
}
?>