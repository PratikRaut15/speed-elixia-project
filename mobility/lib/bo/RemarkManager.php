<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/system/Date.php';
include_once 'lib/model/VORemark.php';

class RemarkManager extends VersionedManager
{

    public function RemarkManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveRemark($remark)
    {
        if(!isset($remark->remarkid))
        {
            $this->InsertRemark($remark);
        }
        else
        {
            $this->UpdateRemark($remark);
        }
    }        
    
    private function UpdateRemark($remark)
    {
        $SQL = sprintf( "Update remarks
                        Set `remarkname`='%s',
                        `userid`='%d',
                        `iscall`='%d'                        
                        WHERE remarkid = %d AND customerno = %d",
                        Sanitise::String( $remark->remarkname),
                        Sanitise::Long( $remark->userid),                
                        Sanitise::Long( $remark->iscall),                                
                        Sanitise::String( $remark->remarkid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        
        $SQL = sprintf( "Update trackee
                        Set `pushremarks`=1
                        WHERE customerno = %d",
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);                                                                        
    }    
    
    private function InsertRemark($remark)
    {
        $SQL = sprintf( "INSERT INTO remarks
                        (`remarkname`,`customerno`,`userid`,`isdeleted`,`iscall`) VALUES
                        ( '%s','%d','%d',0,'%d')",
        Sanitise::String($remark->remarkname),
                $this->_Customerno,Sanitise::String($remark->userid), Sanitise::Long($remark->iscall));
        $this->_databaseManager->executeQuery($SQL);
        
        $SQL = sprintf( "Update trackee
                        Set `pushremarks`=1
                        WHERE customerno = %d",
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);                                                                        
        
        $remark->remarkid = $this->_databaseManager->get_insertedId();        
    }        
        
    public function getremarks($iscall)
    {
        $remarks = Array();
        $Query = sprintf("SELECT * FROM `remarks` 
            INNER JOIN user ON user.userid = remarks.userid
            WHERE remarks.customerno=%d AND remarks.isdeleted = 0 AND remarks.iscall=%d",
            $this->_Customerno, Sanitise::Long($iscall));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $remark = new VORemark();
                $remark->username = $row['username'];                            
                $remark->remarkname = $row['remarkname'];            
                $remark->remarkid = $row['remarkid'];            
                $remarks[] = $remark;
            }
            return $remarks;            
        }
        return null;                        
    }    
    
    public function getremark($remarkid, $iscall)
    {
        $Query = sprintf("SELECT * FROM `remarks` WHERE customerno=%d AND remarkid=%d AND isdeleted = 0 AND iscall=%d",
            $this->_Customerno, Sanitise::Long($remarkid), Sanitise::Long($iscall));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $remark = new VORemark();
                $remark->remarkname = $row['remarkname'];            
                $remark->remarkid = $row['remarkid'];            
            }
            return $remark;            
        }
        return null;                        
    }            
    
    public function DeleteRemark($remarkid)
    {
        $SQL = sprintf( "Update remarks
                        Set `isdeleted`=1
                        WHERE customerno = %d AND remarkid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($remarkid));
        $this->_databaseManager->executeQuery($SQL);                                                                        
    }        
}