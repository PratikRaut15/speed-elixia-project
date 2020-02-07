<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/model/VOFormatField.php';
include_once 'lib/model/VOFFParent.php';

class FormatFieldManager extends VersionedManager
{

    public function FormatFieldManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveFormatField($message)
    {
        if(!isset($message->id))
        {
            $this->Insert($message);
        }
        else
        {
            $this->Update($message);
        }
    }

    private function Insert($message)
    {
        $SQL = sprintf( "INSERT INTO formatfield
                        (`customerno`,`useformatted`,`formattedkey`,`formattedvalue`,`name`,`ffparentid`) VALUES
                        ( '%d','%d','%s','%s','%s','%d')",
        $this->_Customerno,
        Sanitise::Long($message->useformatted),
        Sanitise::String($message->formattedkey),
        Sanitise::String($message->formattedvalue),
        Sanitise::String($message->name),
        Sanitise::Long($message->ffparentid));
        $this->_databaseManager->executeQuery($SQL);
        $message->id = $this->_databaseManager->get_insertedId();
    }
    
    public function SaveFFParent($fp)
    {
        $SQL = sprintf( "INSERT INTO ffparent
                        (`customerno`,`ffparentname`) VALUES
                        ( '%d','%s')",
        $this->_Customerno,
        Sanitise::String($fp->name));
        $this->_databaseManager->executeQuery($SQL);
        $fp->id = $this->_databaseManager->get_insertedId();
    }    
    
    public function get_formattedfield($id) 
    {
        $message = null;
        $ffQuery = sprintf("SELECT * FROM `formatfield` WHERE customerno=%d AND id='%d' LIMIT 1",
                $this->_Customerno,
            Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($ffQuery);		
		
        if ($row = $this->_databaseManager->get_nextRow()) 
        {
            $message = new VOFormatField();
            $message->customerno = $row['customerno'];
            $message->formattedkey = $row['formattedkey'];
            $message->formattedvalue = $row['formattedvalue'];   
            $message->useformatted = $row["useformatted"];
            $message->name = $row["name"];            
            return $message;            
        }
        return null;
    }
    
    public function getformattedfieldsforcustomer() 
    {
        $fields = Array();
        $itemsQuery = sprintf("SELECT * FROM `formatfield` WHERE customerno=%d",
           $this->_Customerno);
        $this->_databaseManager->executeQuery($itemsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
            $message = new VOFormatField();
            $message->id = $row["id"];
            $message->customerno = $row['customerno'];
            $message->formattedkey = $row['formattedkey'];
            $message->formattedvalue = $row['formattedvalue'];   
            $message->useformatted = $row["useformatted"];
            $message->name = $row["name"];                        
            $fields[] = $message;
            }
            return $fields;            
        }
        return null;
    }        

    private function Update($message)
    {
        $SQL = sprintf( "Update formatfield
                        Set `useformatted`='%d',
                        `formattedkey`='%s',
                        `formattedvalue`='%s',
                        `ffparentid`='%d'                        
                        WHERE name = '%s' AND customerno = %d AND id=%d",
                        Sanitise::Long( $message->useformatted),
                        Sanitise::String( $message->formattedkey),
                        Sanitise::String( $message->formattedvalue),
                        Sanitise::Long( $message->ffparentid),                
                        Sanitise::String( $message->name),
                        $this->_Customerno,
                        Sanitise::Long( $message->id));
        $this->_databaseManager->executeQuery($SQL);
    }    
    
    public function getallzones() 
    {
        $zones = Array();
        $zonesQuery = sprintf("SELECT * FROM `ffparent` WHERE customerno=%d", $this->_Customerno);
        $this->_databaseManager->executeQuery($zonesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $zone = new VOFFParent();
                $zone->customerno = $row['customerno'];
                $zone->ffparentname = $row['ffparentname'];
                $zone->id = $row['id'];
                $zones[] = $zone;
            }
            return $zones;            
        }
        return null;
    }        
    
    public function DeleteZone($zoneid)
    {
        $SQL = sprintf( "DELETE FROM ffparent WHERE customerno = %d AND id = %d", $this->_Customerno, Sanitise::Long($zoneid));
        $this->_databaseManager->executeQuery($SQL);
        
        $SQL = sprintf( "DELETE FROM receivereports WHERE customerno = %d AND ffparentid = %d", $this->_Customerno, Sanitise::Long($zoneid));
        $this->_databaseManager->executeQuery($SQL);        
    }    
    
    public function updaterecreport($userid, $zoneid, $recreport)
    {
        if($recreport == 1)
        {
            $zonesQuery = sprintf("SELECT * FROM `receivereports` WHERE userid = %d AND ffparentid = %d AND customerno=%d", Sanitise::Long($userid), Sanitise::Long($zoneid), $this->_Customerno);
            $this->_databaseManager->executeQuery($zonesQuery);		
            if ($this->_databaseManager->get_rowCount() == 0) 
            {
                $SQL = sprintf( "INSERT INTO receivereports
                                (`customerno`,`ffparentid`,`userid`) VALUES
                                ( %d, %d, %d)",
                $this->_Customerno,
                Sanitise::Long($zoneid), Sanitise::Long($userid));
                $this->_databaseManager->executeQuery($SQL);
            }            
        }
        else
        {
            $zonesQuery = sprintf("SELECT * FROM `receivereports` WHERE userid = %d AND ffparentid = %d AND customerno=%d", Sanitise::Long($userid), Sanitise::Long($zoneid), $this->_Customerno);
            $this->_databaseManager->executeQuery($zonesQuery);		
            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                $SQL = sprintf( "DELETE FROM receivereports WHERE userid = %d AND customerno = %d AND ffparentid = %d", Sanitise::Long($userid), $this->_Customerno, Sanitise::Long($zoneid));
                $this->_databaseManager->executeQuery($SQL);                            
           }
        }
    }
}