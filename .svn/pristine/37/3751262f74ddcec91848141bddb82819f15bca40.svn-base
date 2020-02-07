<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOAccessory.php';
include_once '../../lib/system/Date.php';


class AccessoryManager extends VersionedManager{
    
    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }
    
    public function get_all_accessories()
    {
        $accs = Array();
        $Query = "SELECT * FROM `accessory` where customerno in(0,%d) AND isdeleted=0";
        $GroupsQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $acc = new VOAccessory();
                $acc->id = $row['id'];
                $acc->name = $row['name'];
                $acc->max_amount = $row['max_amount'];                
                $acc->customerno = $row['customerno'];
                $accs[] = $acc;
            }
            return $accs;            
        }
        return null;
    }

    public function get_accessory($id)
    {
        $Query = "SELECT * FROM `accessory` where id=%d AND customerno in(0,%d) AND isdeleted=0";
        $GroupsQuery = sprintf($Query, Sanitise::Long($id), $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $acc = new VOAccessory();
                $acc->id = $row['id'];
                $acc->name = $row['name'];
                $acc->max_amount = $row['max_amount'];                
            }
            return $acc;            
        }
        return null;
    }
 
    public function add_accessory($accname,$accamount,$userid)
    {
        $Query = "INSERT INTO `accessory` (name,max_amount,customerno,userid,timestamp,isdeleted) VALUES ('%s',%d,'%d','%d','%s','0')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($accname),Sanitise::String($accamount), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays));
        $this->_databaseManager->executeQuery($SQL);
    }
    
    
    public function edit_accessory($id, $name,$amount, $userid)
    {
        $Query = "UPDATE `accessory` SET `name` = '%s',`max_amount` = '%d', userid=%d WHERE `id` = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::String($name), Sanitise::Long($amount), Sanitise::Long($userid), Sanitise::Long($id), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL); 
    }
    
      
    public function DeleteAccessory($accid, $userid)
    {
        $Query = "UPDATE `accessory` SET `isdeleted` = 1, userid=%d WHERE `id` = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($accid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL); 
    }
    
    
    
}