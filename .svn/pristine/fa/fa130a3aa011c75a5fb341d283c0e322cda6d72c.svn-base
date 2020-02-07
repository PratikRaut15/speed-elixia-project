<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/system/Date.php';
include_once 'lib/model/VOElixiaCode.php';
include_once 'constants/constants.php';

class ElixiaCodeManager extends VersionedManager
{

    public function ElixiaCodeManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveElixiaCode($elixiacode)
    {
        $today = date("Y-m-d H:i:s");        
        $today = date("Y-m-d H:i:s");
        $SQL = sprintf( "INSERT INTO elixiacode
                        (`customerno`,`trackeeid`,`validity`,`ecode`,`datecreated`) VALUES
                        ( '%d','%d','%d','%d','%s')",
        $this->_Customerno,
        Sanitise::Long($elixiacode->trackeeid),
        Sanitise::Long($elixiacode->validity),
        Sanitise::Long($elixiacode->elixiacode),
                Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
        $elixiacode->id = $this->_databaseManager->get_insertedId();
    }
    
    public function getelixiacodesforcustomer() 
    {
        $codes = Array();
        $elixiacodesQuery = sprintf("SELECT e.*, t.tname, Now() as currentdate FROM `elixiacode` e INNER JOIN trackee t ON e.trackeeid=t.trackeeid WHERE e.customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($elixiacodesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $code = new VOElixiaCode();
                $code->id = $row['id'];
                $code->customerno = $row['customerno'];
                $code->datecreated = $row['datecreated'];                            
                $code->elixiacode = $row['ecode'];            
                $code->trackeeid = $row['trackeeid'];
                $code->tname = $row["tname"];
                $code->validity = $row['validity']. " minutes";    
                $today = $row["currentdate"];
                $date = new Date();
                $today = $date->add_hours(date("Y-m-d H:i:s", strtotime($today)), 0);
                $checkstatus = $this->checkvalidity($row["datecreated"], $row["validity"], $today);
                if($checkstatus == true)
                {
                    $code->status = "Valid";
                }
                else
                {
                    $code->status = "Expired";
                }
                $codes[] = $code;
            }
            return $codes;            
        }
        return null;
    }        
    
    public function checkvalidity($datecreated, $validity, $currentdate)
    {
        $initialdate = strtotime($datecreated);
        $realtime = strtotime($currentdate);
        $minutes = strtotime("-".$validity." minutes", $realtime);
        if($initialdate < $minutes)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function gettrackeeid_from_elixiacode($ecode) 
    {
        $elixiacodesQuery = sprintf("SELECT trackeeid FROM `elixiacode` where ecode=%d",
            Sanitise::Long($elixiacode->ecode));
        $this->_databaseManager->executeQuery($elixiacodesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $code = new VOElixiaCode();
                $code->trackeeid = $row['trackeeid'];            
                return $code;            
            }            
        }
        return null;
    }            

    public function DeleteElixiaCode($id)
    {
        $SQL = sprintf("DELETE FROM elixiacode where id = %d and customerno = %d",
                        Sanitise::Long($id), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }
}