<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/model/VOCheckpoint.php';
include_once 'constants/constants.php';

class CheckpointManager extends VersionedManager
{

    public function CheckpointManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveCheckpoint($checkpoint)
    {
        if(!isset($checkpoint->checkpointid))
        {
            $this->Insert($checkpoint);
        }
        else
        {
            $this->Update($checkpoint);
        }
    }

    private function Insert($checkpoint)
    {
        $SQL = sprintf( "INSERT INTO checkpoint
                        (`customerno`,`recipientid`,`cname`,`attraction`,`cadd1`
                        ,`cadd2`,`cadd3`,`ccity`,`cstate`,`czip`,`cgeolat`,`cgeolong`,`ciconimage`) VALUES
                        ( '%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%f','%f','%s')",
        $this->_Customerno,
        Sanitise::Long($checkpoint->recipientid),
        Sanitise::String($checkpoint->cname),
        Sanitise::String($checkpoint->attraction),                
        Sanitise::String($checkpoint->cadd1),
        Sanitise::String($checkpoint->cadd2),
        Sanitise::String($checkpoint->cadd3),
        Sanitise::String($checkpoint->ccity),
        Sanitise::String($checkpoint->cstate),
        Sanitise::String($checkpoint->czip),
        Sanitise::Float($checkpoint->cgeolat),
        Sanitise::Float($checkpoint->cgeolong),
        Sanitise::String($checkpoint->ciconimage));
        $this->_databaseManager->executeQuery($SQL);
        $checkpoint->checkpointid = $this->_databaseManager->get_insertedId();
    }
    
    public function get_checkpoint($id) 
    {
        $checkpoint = null;
        $checkpointDetailsQuery = sprintf("SELECT * FROM `checkpoint` where customerno=%d AND `checkpointid`='%d' LIMIT 1",
                $this->_Customerno,
            Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($checkpointDetailsQuery);		
		
        if ($row = $this->_databaseManager->get_nextRow()) 
        {
            $checkpoint = new VOCheckpoint();
            $checkpoint->checkpointid = $row['checkpointid'];
            $checkpoint->cname = $row['cname'];
            $checkpoint->attraction = $row['attraction'];            
            $checkpoint->recipientid = $row['recipientid'];            
            $checkpoint->cadd1 = $row['cadd1'];            
            $checkpoint->cadd2 = $row['cadd2'];            
            $checkpoint->cadd3 = $row['cadd3'];            
            $checkpoint->ccity = $row['ccity'];            
            $checkpoint->cstate = $row['cstate'];            
            $checkpoint->czip = $row['czip'];            
            $checkpoint->cgeolat = $row['cgeolat'];                        
            $checkpoint->cgeolong = $row['cgeolong'];            
            $checkpoint->ciconimage = $row['ciconimage'];                                    
            return $checkpoint;            
        }
        return null;
    }
    
    public function getcheckpointsforcustomer() 
    {
        $checkpoints = Array();
        $checkpointsQuery = sprintf("SELECT * FROM `checkpoint` where customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $checkpoint = new VOCheckpoint();
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->attraction = $row['attraction'];                            
                $checkpoint->recipientid = $row['recipientid'];            
                $checkpoint->cadd1 = $row['cadd1'];            
                $checkpoint->cadd2 = $row['cadd2'];            
                $checkpoint->cadd3 = $row['cadd3'];            
                $checkpoint->ccity = $row['ccity'];            
                $checkpoint->cstate = $row['cstate'];            
                $checkpoint->czip = $row['czip'];            
                $checkpoint->cgeolat = $row['cgeolat'];                        
                $checkpoint->cgeolong = $row['cgeolong'];            
                $checkpoint->ciconimage = $row['ciconimage'];
                //Adding Individual Address Details to a single variable
                if($checkpoint->cadd1!=NULL)
                {
                    $checkpoint->completeaddress = $checkpoint->cadd1;
                }
                if($checkpoint->cadd2!=NULL)
                {
                    $checkpoint->completeaddress .= " ".$checkpoint->cadd2;
                }
                if($checkpoint->cadd3!=NULL)
                {
                    $checkpoint->completeaddress .= " ".$checkpoint->cadd3;
                }
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;            
        }
        return null;
    }        
    
    public function getfilteredcheckpointsforcustomer($trackeeid) 
    {
        $checkpoints = Array();
        $checkpointsQuery = sprintf("SELECT * FROM `checkpointmanage` INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid WHERE checkpointmanage.customerno=%d AND checkpointmanage.trackeeid=%d",
            $this->_Customerno, Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($checkpointsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $checkpoint = new VOCheckpoint();
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->attraction = $row['attraction'];                            
                $checkpoint->recipientid = $row['recipientid'];            
                $checkpoint->cadd1 = $row['cadd1'];            
                $checkpoint->cadd2 = $row['cadd2'];            
                $checkpoint->cadd3 = $row['cadd3'];            
                $checkpoint->ccity = $row['ccity'];            
                $checkpoint->cstate = $row['cstate'];            
                $checkpoint->czip = $row['czip'];            
                $checkpoint->cgeolat = $row['cgeolat'];                        
                $checkpoint->cgeolong = $row['cgeolong'];            
                $checkpoint->ciconimage = $row['ciconimage'];                                    
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;            
        }
        return null;
    }            

    private function Update($checkpoint)
    {
        $SQL = sprintf( "Update checkpoint
                        Set `recipientid`='%d',
                        `cname`='%s',
                        `attraction`='%s',                        
                        `cadd1`='%s',
                        `cadd2`='%s',
                        `cadd3`='%s',
                        `ccity`='%s',
                        `cstate`='%s',
                        `czip`='%s',                        
                        `cgeolat`='%f',                        
                        `cgeolong`='%f',                        
                        `ciconimage`='%s'                                                
                        WHERE checkpointid = %d AND customerno = %d",
                        Sanitise::Long( $checkpoint->recipientid),
                        Sanitise::String( $checkpoint->cname),
                        Sanitise::String( $checkpoint->attraction),                
                        Sanitise::String( $checkpoint->cadd1),
                        Sanitise::String( $checkpoint->cadd2),
                        Sanitise::String( $checkpoint->cadd3),
                        Sanitise::String( $checkpoint->ccity),                
                        Sanitise::String( $checkpoint->cstate),                
                        Sanitise::String( $checkpoint->czip),                                
                        Sanitise::Float( $checkpoint->cgeolat),
                        Sanitise::Float( $checkpoint->cgeolong),                
                        Sanitise::String( $checkpoint->ciconimage),                                
                        Sanitise::Long( $checkpoint->checkpointid),                
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeleteCheckpoint($checkpointid)
    {
        $SQL = sprintf("DELETE FROM checkpoint where checkpointid = %d and customerno = %d",
                        Sanitise::Long($checkpointid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }
}