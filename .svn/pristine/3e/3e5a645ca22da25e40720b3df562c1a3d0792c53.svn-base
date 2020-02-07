<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/system/Date.php';
include_once 'lib/model/VOUF1.php';
include_once 'lib/model/VOUF2.php';

class UserFieldManager extends VersionedManager
{
    public function UserFieldManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveUF1($data)
    {
        if(!isset($data->ufid1))
        {
            $this->InsertUF1($data);
        }
        else
        {
            $this->UpdateUF1($data);
        }
    }        
    
    public function SaveUF2($data)
    {
        if(!isset($data->ufid2))
        {
            $this->InsertUF2($data);
        }
        else
        {
            $this->UpdateUF2($data);
        }
    }            

    private function UpdateUF1($data)
    {
        $SQL = sprintf( "Update userfield1
                        Set `fieldname1`='%s',`iscall`='%d',
                        `userid`='%d'
                        WHERE ufid1 = %d AND customerno = %d",
                        Sanitise::String( $data->fieldname1),
                        Sanitise::Long( $data->iscall),                
                        Sanitise::Long( $data->userid),                
                        Sanitise::String( $data->ufid1),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }    
    
    private function UpdateUF2($data)
    {
        $SQL = sprintf( "Update userfield2
                        Set `fieldname2`='%s',`iscall`='%d',
                        `userid`='%d'
                        WHERE ufid2 = %d AND customerno = %d",
                        Sanitise::String( $data->fieldname2),
                        Sanitise::Long( $data->iscall),                                
                        Sanitise::Long( $data->userid),                
                        Sanitise::String( $data->ufid2),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }        
    
    private function InsertUF1($data)
    {
        $SQL = sprintf( "INSERT INTO userfield1
                        (`fieldname1`,`customerno`,`userid`,`isdeleted`,`iscall`) VALUES
                        ( '%s','%d','%d',0,'%d')",
        Sanitise::String($data->fieldname1),
                $this->_Customerno,
        Sanitise::String($data->userid),
        Sanitise::Long($data->iscall));
        $this->_databaseManager->executeQuery($SQL);
        $data->ufid1 = $this->_databaseManager->get_insertedId();        
    }        
    
    private function InsertUF2($data)
    {
        $SQL = sprintf( "INSERT INTO userfield2
                        (`fieldname2`,`customerno`,`userid`,`isdeleted`,`iscall`) VALUES
                        ( '%s','%d','%d',0,'%d')",
        Sanitise::String($data->fieldname2),
                $this->_Customerno,
        Sanitise::String($data->userid),
        Sanitise::Long($data->iscall));
        $this->_databaseManager->executeQuery($SQL);
        $data->ufid2 = $this->_databaseManager->get_insertedId();        
    }            
    
    public function getufs1($iscall)
    {
        $ufs = Array();
        $Query = sprintf("SELECT * FROM `userfield1` 
            INNER JOIN user ON user.userid = userfield1.userid
            WHERE userfield1.customerno=%d AND userfield1.isdeleted = 0 AND userfield1.iscall=%d",
            $this->_Customerno, Sanitise::Long($iscall));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $uf = new VOUF1();
                $uf->customerno = $row['customerno'];            
                $uf->ufid1 = $row['ufid1'];            
                $uf->fieldname1 = $row['fieldname1']; 
                $uf->isdeleted = $row['isdeleted'];                 
                $uf->userid = $row['userid']; 
                $uf->username = $row["username"];
                $ufs[] = $uf;                
            }
            return $ufs;            
        }
        return null;                        
    }    
    
    public function getufs2($iscall)
    {
        $ufs = Array();
        $Query = sprintf("SELECT * FROM `userfield2` 
            INNER JOIN user ON user.userid = userfield2.userid
            WHERE userfield2.customerno=%d AND userfield2.isdeleted = 0 AND userfield2.iscall=%d",
            $this->_Customerno, Sanitise::Long($iscall));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $uf = new VOUF2();
                $uf->customerno = $row['customerno'];            
                $uf->ufid2 = $row['ufid2'];            
                $uf->fieldname2 = $row['fieldname2']; 
                $uf->isdeleted = $row['isdeleted'];                 
                $uf->userid = $row['userid'];           
                $uf->username = $row["username"];                
                $ufs[] = $uf;                
            }
            return $ufs;            
        }
        return null;                        
    }    
        
    public function getuf1($ufid1, $iscall)
    {
        $Query = sprintf("SELECT * FROM `userfield1` WHERE customerno=%d AND ufid1=%d AND isdeleted = 0 AND iscall=%d",
            $this->_Customerno, Sanitise::Long($ufid1), Sanitise::Long($iscall));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $uf = new VOUF1();
                $uf->customerno = $row['customerno'];            
                $uf->ufid1 = $row['ufid1'];            
                $uf->fieldname1 = $row['fieldname1']; 
                $uf->isdeleted = $row['isdeleted'];                 
                $uf->userid = $row['userid'];                                 
            }
            return $uf;            
        }
        return null;                        
    }            
    
    public function getuf2($ufid2, $iscall)
    {
        $Query = sprintf("SELECT * FROM `userfield2` WHERE customerno=%d AND ufid2=%d AND isdeleted = 0 AND iscall=%d",
            $this->_Customerno, Sanitise::Long($ufid2), Sanitise::Long($iscall));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $uf = new VOUF2();
                $uf->customerno = $row['customerno'];            
                $uf->ufid2 = $row['ufid2'];            
                $uf->fieldname2 = $row['fieldname2']; 
                $uf->isdeleted = $row['isdeleted'];                 
                $uf->userid = $row['userid'];                                 
            }
            return $uf;            
        }
        return null;                        
    }                
    
    public function getuf1fromphrase($phrase, $iscall) 
    {
        $clients = Array();
        if(isset($phrase))
        {
            $phrasesearch .= sprintf( " AND (userfield1.fieldname1 LIKE '%%%s%%')",$phrase);
        }        
        $Query = sprintf("SELECT * FROM `userfield1` WHERE userfield1.customerno=%d AND userfield1.isdeleted = 0 AND userfield1.iscall=%d",
            $this->_Customerno, Sanitise::Long($iscall));
        $Query.=$phrasesearch;
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $client = new VOUF1();
                $client->id = $row['ufid1'];
                $client->name = $row['fieldname1'];
                $clients[] = $client;
            }
            return $clients;            
        }
        return null;
    }                    
    
    public function getuf2fromphrase($phrase, $iscall) 
    {
        $clients = Array();
        if(isset($phrase))
        {
            $phrasesearch .= sprintf( " AND (userfield2.fieldname2 LIKE '%%%s%%')",$phrase);
        }        
        $Query = sprintf("SELECT * FROM `userfield2` WHERE userfield2.customerno=%d AND userfield2.isdeleted = 0 AND userfield2.iscall=%d",
            $this->_Customerno, Sanitise::Long($iscall));
        $Query.=$phrasesearch;
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $client = new VOUF2();
                $client->id = $row['ufid2'];
                $client->name = $row['fieldname2'];
                $clients[] = $client;
            }
            return $clients;            
        }
        return null;
    }                    
        
    public function DeleteUF1($ufid1)
    {
        $SQL = sprintf( "Update userfield1
                        Set `isdeleted`=1
                        WHERE customerno = %d AND ufid1 = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($ufid1));
        $this->_databaseManager->executeQuery($SQL);                                                                        
    }    
    
    public function DeleteUF2($ufid2)
    {
        $SQL = sprintf( "Update userfield2
                        Set `isdeleted`=1
                        WHERE customerno = %d AND ufid2 = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($ufid2));
        $this->_databaseManager->executeQuery($SQL);                                                                        
    }        
    
}