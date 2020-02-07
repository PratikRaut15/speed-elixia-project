<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/model/VOTrackee.php';
include_once 'lib/model/VOCheckpoint.php';

class TrackeeManager extends VersionedManager
{

    public function TrackeeManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveTrackee($trackee)
    {
        if(!isset($trackee->trackeeid))
        {
            $this->Insert($trackee);
        }
        else
        {
            $this->Update($trackee);
        }
    }

    private function Insert($trackee)
    {
        $SQL = sprintf( "INSERT INTO trackee
                        (`customerno`,`tname`,`ticonimage`) VALUES
                        ( '%d','%s','%s')",
        $this->_Customerno,
        Sanitise::String($trackee->tname),
        Sanitise::String($trackee->ticonimage));
        $this->_databaseManager->executeQuery($SQL);
        $trackee->trackeeid = $this->_databaseManager->get_insertedId();
    }
    
    public function InsertCM(&$checkpoints, $trackeeid)
    {
        if(isset($checkpoints))
        {
            foreach($checkpoints as $thischeckpoint)
            {
                $SQL = sprintf( "INSERT INTO checkpointmanage
                                (`customerno`,`trackeeid`,`checkpointid`) VALUES
                                ( '%d','%d','%d')",
                $this->_Customerno,
                Sanitise::Long($trackeeid),
                Sanitise::Long($thischeckpoint->checkpointid));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
    }    
    
    public function get_trackee($id) 
    {
        $trackee = null;
        $trackeeDetailsQuery = sprintf("SELECT * FROM `trackee` where customerno=%d AND `trackeeid`='%d' LIMIT 1",
                $this->_Customerno,
            Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($trackeeDetailsQuery);		
		
        if ($row = $this->_databaseManager->get_nextRow()) 
        {
            $trackee = new VOTrackee();
            $trackee->id = $row['trackeeid'];
            $trackee->tname = $row['tname'];
            $trackee->ticonimage = $row['ticonimage'];            
            return $trackee;            
        }
        return null;
    }
    
    public function gettrackeesforcustomer() 
    {
        $trackees = Array();
        $trackeesQuery = sprintf("SELECT * FROM `trackee` where customerno=%d AND isdeleted = 0",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($trackeesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $trackee = new VOTrackee();
                $trackee->trackeeid = $row['trackeeid'];
                $trackee->tname = $row['tname'];
                $trackee->ticonimage = $row['ticonimage'];                            
                $trackees[] = $trackee;
            }
            return $trackees;            
        }
        return null;
    }        
    
    public function getregisteredtrackeesforcustomer() 
    {
        $trackees = Array();
        $trackeesQuery = sprintf("SELECT * FROM `trackee` INNER JOIN devices ON devices.trackeeid = trackee.trackeeid where trackee.customerno=%d AND trackee.isdeleted = 0",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($trackeesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $trackee = new VOTrackee();
                $trackee->trackeeid = $row['trackeeid'];
                $trackee->tname = $row['tname'];
                $trackee->ticonimage = $row['ticonimage'];                            
                $trackees[] = $trackee;
            }
            return $trackees;            
        }
        return null;
    }            
    
    public function getcheckpointsCM($trackeeid) 
    {
        $checkpoints = Array();
        $cpQuery = sprintf("SELECT * FROM `checkpointmanage` INNER JOIN checkpoint ON checkpointmanage.checkpointid = checkpoint.checkpointid where checkpointmanage.customerno=%d AND checkpointmanage.trackeeid=%d",
            $this->_Customerno, Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($cpQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $checkpoint = new VOCheckpoint();
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->cadd1 = $row['cadd1'];            
                $checkpoint->cadd2 = $row['cadd2'];            
                $checkpoint->cadd3 = $row['cadd3'];            
                $checkpoint->ccity = $row['ccity'];            
                $checkpoint->cstate = $row['cstate'];            
                $checkpoint->czip = $row['czip'];            
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;            
        }
        return null;
    }        
    
    public function getfilteredtrackeesforcustomer() 
    {
        $trackees = Array();
        $trackeesQuery = sprintf("SELECT * FROM `trackee` INNER JOIN devices ON devices.trackeeid = trackee.trackeeid WHERE trackee.customerno=%d AND trackee.isdeleted = 0 GROUP BY trackee.tname",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($trackeesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $trackee = new VOTrackee();
                $trackee->trackeeid = $row['trackeeid'];
                $trackee->tname = $row['tname'];
                $trackee->ticonimage = $row['ticonimage'];                
                $trackees[] = $trackee;
            }
            return $trackees;            
        }
        return null;
    }        

    private function Update($trackee)
    {
        $SQL = sprintf( "Update trackee
                        Set `tname`='%s',
                        `ticonimage`='%s'
                        WHERE trackeeid = %d AND customerno = %d",
                        Sanitise::String( $trackee->tname),
                        Sanitise::String( $trackee->ticonimage),                
                        Sanitise::String( $trackee->trackeeid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }
    
    public function UpdateCM(&$checkpoints, $trackeeid)
    {
        $SQL = sprintf("DELETE FROM checkpointmanage where trackeeid = %d and customerno = %d",
                        Sanitise::Long($trackeeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
        
        if(isset($checkpoints))
        {
            foreach($checkpoints as $thischeckpoint)
            {
                $SQL = sprintf( "INSERT INTO checkpointmanage
                                (`customerno`,`trackeeid`,`checkpointid`) VALUES
                                ( '%d','%d','%d')",
                $this->_Customerno,
                Sanitise::Long($trackeeid),
                Sanitise::Long($thischeckpoint->checkpointid));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
    }    
    
    public function DeleteTrackee($trackeeid)
    {
        
        $SQL = sprintf( "Update trackee
                        Set `isdeleted`=1
                        WHERE trackeeid = %d AND customerno = %d",               
                        Sanitise::Long($trackeeid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
        
/*        $SQL = sprintf("DELETE FROM trackee where trackeeid = %d and customerno = %d",
                        Sanitise::Long($trackeeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
*/        
        $SQL = sprintf("SELECT trackeeid, deviceid FROM devices WHERE trackeeid = %d AND customerno = %d", Sanitise::Long($trackeeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        {
            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $deviceid = $row["deviceid"];
                    $SQL = sprintf("UPDATE devices SET trackeeid = 0 WHERE customerno = %d AND deviceid = %d",
                                    $this->_Customerno, $deviceid);
                    $this->_databaseManager->executeQuery($SQL);                                        

                }
            }        
        }
        $SQL = sprintf("SELECT trackeeid, fenceid FROM fence WHERE trackeeid = %d AND customerno = %d", Sanitise::Long($trackeeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        {
            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $fenceid = $row["fenceid"];
                    $SQL = sprintf("UPDATE fence SET trackeeid = 0 WHERE customerno = %d AND fenceid = %d",
                                    $this->_Customerno, $fenceid);
                    $this->_databaseManager->executeQuery($SQL);                                        

                }
            }        
        }    
    }
}