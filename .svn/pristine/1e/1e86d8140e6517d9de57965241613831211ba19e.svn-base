<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOTask.php';
include_once '../../lib/system/Date.php';


class TaskManager extends VersionedManager{
    
    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }
    
    public function get_all_task()
    {
        $Tasks = Array();
        $Query = "SELECT * FROM `task` where customerno in(0,%d) AND isdeleted=0 ORDER BY task_name ASC";
        $GroupsQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $Task = new VOTask();
                $Task->id = $row['id'];
                $Task->task_name = $row['task_name'];
                $Task->unitamount = $row['unitamount'];
                $Task->unitdiscount = $row['unitdiscount'];
                $Task->customerno = $row['customerno'];
                $Tasks[$row['id']] = $Task;
            }
            return $Tasks;            
        }
        return null;
    }
    
    public function get_task($taskid)
    {
        $Query = "SELECT * FROM `task` where id=%d AND customerno in(0,%d) AND isdeleted=0";
        $GroupsQuery = sprintf($Query, Sanitise::Long($taskid), $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $Task = new VOTask();
                $Task->id = $row['id'];
                $Task->task_name = $row['task_name'];
                $Task->unitamount = $row['unitamount'];
                $Task->unitdiscount = $row['unitdiscount'];
            }
            return $Task;            
        }
        return null;
    }
 
    public function add_task($data,$userid)
    {
        $taskname = isset($data['taskname'])?$data['taskname']:"";
        $taskamount = isset($data['taskamount'])?$data['taskamount']:"0";
        $taskdiscount = isset($data['taskdiscount'])?$data['taskdiscount']:"0";
        
        $Query = "INSERT INTO `task` (task_name,unitamount,unitdiscount,customerno,userid,timestamp,isdeleted) VALUES ('%s','%s','%s','%d','%d','%s','0')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($taskname), Sanitise::String($taskamount), Sanitise::String($taskdiscount), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays));
        $this->_databaseManager->executeQuery($SQL);
        return $this->_databaseManager->get_insertedId();
    }
    public function taskname_exists($taskname)
    {
        $Query = "select task_name from  `task` where task_name='%s' and isdeleted=0 and customerno=$this->_Customerno";
        $SQL = sprintf($Query, Sanitise::String($taskname));
        $this->_databaseManager->executeQuery($SQL);
        
        if($this->_databaseManager->get_rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function edit_task($data, $userid)
    {
        $taskname = isset($data['editname'])?$data['editname']:"";
        $taskamount = isset($data['taskamount'])?$data['taskamount']:"0";
        $taskdiscount = isset($data['taskdiscount'])?$data['taskdiscount']:"0";
        $taskid = $data['taskid'];
        $Query = "UPDATE `task` SET `task_name` = '%s', `unitamount` = '%s',`unitdiscount` = '%s', userid=%d WHERE `id` = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::String($taskname),Sanitise::String($taskamount),Sanitise::String($taskdiscount), Sanitise::Long($userid), Sanitise::Long($taskid), $this->_Customerno); 
        $this->_databaseManager->executeQuery($SQL); 
    }
    
      
    public function DeleteTask($taskid, $userid)
    {
        $Query = "UPDATE `task` SET `isdeleted` = 1, userid=%d WHERE `id` = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($taskid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL); 
        
    }
    
    public function gettaskby_maintenanceid($main_id)
    {
        $task_arr=array();
        $Query = "SELECT * FROM `maintenance_tasks` 
                  INNER JOIN task ON maintenance_tasks.partid = task.id
                  where mid=%d";
        $SQL= sprintf($Query, Sanitise::Long($main_id));
        $this->_databaseManager->executeQuery($SQL);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                //$part = new VOTask();
                $task = $row['partid'];
                $task_name = $row['task_name'];
                $unit_price = $row['amount'];
                $quantity = $row['qty'];
                $total = $row['total'];
                $task_arr[]=$task_name."-"." U : ".$unit_price.", Q : ".$quantity.", T : ".$total;
            }           
        }

        //$ta=implode(",",$task_arr);
        //return $ta;
        return $task_arr;
    }
    
    
    
}