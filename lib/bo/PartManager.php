<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOTask.php';
include_once '../../lib/system/Date.php';


class PartManager extends VersionedManager{
    
    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }
    
    public function get_all_part()
    {
        $parts = Array();
        $Query = "SELECT * FROM `parts` where customerno in(0,%d) AND isdeleted=0 order by part_name ASC";
        $GroupsQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $part = new VOTask();
                $part->id = $row['id'];
                $part->part_name = $row['part_name'];
                $part->unitamount = $row['unitamount'];
                $part->unitdiscount = $row['unitdiscount'];
                $part->customerno = $row['customerno'];
                $parts[$row['id']] = $part;
            }
            return $parts;            
        }
        return null;
    }
    
    public function get_part($partid)
    {
        $Query = "SELECT * FROM `parts` where id=%d AND customerno in(0,%d) AND isdeleted=0";
        $GroupsQuery = sprintf($Query, Sanitise::Long($partid), $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $part = new VOTask();
                $part->id = $row['id'];
                $part->part_name = $row['part_name'];
                $part->unitamount = $row['unitamount'];
                $part->unitdiscount = $row['unitdiscount'];
            }
            return $part;            
        }
        return null;
    }
 
    public function add_part($data,$userid)
    {
        $partname =  isset($data['partname'])?$data['partname']:"";
        $partamount = isset($data['partamount'])?$data['partamount']:"0";
        $partdiscount = isset($data['partdiscount'])?$data['partdiscount']:"0";
        $Query = "INSERT INTO `parts` (part_name,unitamount,unitdiscount,customerno,userid,timestamp,isdeleted) VALUES ('%s','%s','%s','%d','%d','%s','0')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, trim(Sanitise::String($partname)),Sanitise::String($partamount),Sanitise::String($partdiscount), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays));
        $this->_databaseManager->executeQuery($SQL);
        return $this->_databaseManager->get_insertedId();
    }
    
    public function partname_exists($partname)
    {
        $Query = "select part_name from  `parts` where part_name='%s' and isdeleted=0 and customerno=$this->_Customerno";
        $SQL = sprintf($Query, Sanitise::String($partname));
        $this->_databaseManager->executeQuery($SQL);
        if($this->_databaseManager->get_rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    
    public function edit_part($data, $userid)
    {
        $editname = isset($data['editname'])?$data['editname']:"";
        $partamount = isset($data['partamount'])?$data['partamount']:"0";
        $partdiscount = isset($data['partdiscount'])?$data['partdiscount']:"0";
        $partid = isset($data['partid'])?$data['partid']:"0";
        
        $Query = "UPDATE `parts` SET `part_name` = '%s',`unitamount`='%s',`unitdiscount`='%s', userid=%d WHERE `id` = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::String($editname),Sanitise::String($partamount), Sanitise::String($partdiscount),Sanitise::Long($userid), Sanitise::Long($partid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL); 
    }
    
      
    public function DeletePart($partid, $userid)
    {
        $Query = "UPDATE `parts` SET `isdeleted` = 1, userid=%d WHERE `id` = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($partid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL); 
    }
    
    public function getpartsby_maintenanceid($main_id)
    {
        $part_arr=array();
        $Query = "SELECT * FROM `maintenance_parts` 
                  INNER JOIN parts ON maintenance_parts.partid = parts.id
                  where mid=%d";
       $GroupsQuery = sprintf($Query, Sanitise::Long($main_id));
        $this->_databaseManager->executeQuery($GroupsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                //$part = new VOTask();
                $part = $row['partid'];
                $part_name = $row['part_name'];
                $unit_price = $row['amount'];
                $quantity = $row['qty'];
                $total = $row['total'];
                $part_arr[]=$part_name."-"." U : ".$unit_price.", Q : ".$quantity.", T : ".$total;
            }           
        }
        
        //$pa=implode(",",$part_arr);
        //return $pa;
        return $part_arr;
    }
    
}