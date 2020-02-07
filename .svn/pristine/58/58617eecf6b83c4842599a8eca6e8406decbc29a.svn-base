<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/model/VOGeofence.php';
include_once 'lib/model/VOFence.php';
include_once 'constants/constants.php';

class GeofenceManager extends VersionedManager
{

    public function GeofenceManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveGeofence($geofence)
    {
        if(!isset($geofence->geofenceid))
        {
            $this->Insert($geofence);
        }
        else
        {
            //$this->Update($geofence);
        }
    }

    public function InsertFence($geofence)
    {
        $SQL = sprintf( "INSERT INTO fence
                        (`customerno`,`fencename`) VALUES
                        ( '%d','%s')",
        $this->_Customerno,
        Sanitise::String($geofence->fencename));
        $this->_databaseManager->executeQuery($SQL);
        $geofence->fenceid = $this->_databaseManager->get_insertedId();
    }
    
    private function Insert($geofence)
    {
        $SQL = sprintf( "INSERT INTO geofence
                        (`customerno`,`fenceid`,`geolat`,`geolong`) VALUES
                        ( '%d','%d','%f','%f')",
        $this->_Customerno,
        Sanitise::Long($geofence->fenceid),
        Sanitise::Float($geofence->geolat),                
        Sanitise::Float($geofence->geolong));
        $this->_databaseManager->executeQuery($SQL);
    }
    
    public function get_geofence_from_fenceid($fenceid) 
    {
        $geofence = Array();
        $geofenceQuery = sprintf("SELECT * FROM `geofence` WHERE customerno=%d AND fenceid='%s'",
            $this->_Customerno, Sanitise::String($fenceid));
        $this->_databaseManager->executeQuery($geofenceQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $geofencepart = new VOGeofence();
                $geofencepart->geofenceid = $row['geofenceid'];
                $geofencepart->fenceid = $row['fenceid'];
                $geofencepart->customerno = $row['customerno'];                            
                $geofencepart->geolat = $row['geolat'];            
                $geofencepart->geolong = $row['geolong'];            
                $geofence[] = $geofencepart;
            }
            return $geofence;            
        }
        return null;
    }
        
    public function getfenceid_from_trackeeid($trackeeid) 
    {
        $geofenceQuery = sprintf("SELECT fenceid FROM `fence` WHERE customerno=%d AND trackeeid=%d",
            $this->_Customerno, Sanitise::String($trackeeid));
        $this->_databaseManager->executeQuery($geofenceQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                return $row["fenceid"];
            }
        }
        return null;
    }
    
    public function getfencename($fenceid) 
    {
        $geofenceQuery = sprintf("SELECT fencename FROM `fence` WHERE customerno=%d AND fenceid=%d",
            $this->_Customerno, Sanitise::String($fenceid));
        $this->_databaseManager->executeQuery($geofenceQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                return $row["fencename"];
            }
        }
        return null;
    }    

    public function getconflictstatus($fenceid) 
    {
        $geofenceQuery = sprintf("SELECT conflictstatus FROM `fence` WHERE customerno=%d AND fenceid=%d",
            $this->_Customerno, Sanitise::String($fenceid));
        $this->_databaseManager->executeQuery($geofenceQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                return $row["conflictstatus"];
            }
        }
        return null;
    }    
    
    public function getgeofencesforcustomer() 
    {
        $geofence = Array();
        $geofenceQuery = sprintf("SELECT * FROM `geofence` WHERE customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($geofenceQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $geofencepart = new VOGeofence();
                $geofencepart->geofenceid = $row['geofenceid'];
                $geofencepart->fenceid = $row['fenceid'];
                $geofencepart->customerno = $row['customerno'];                            
                $geofencepart->geolat = $row['geolat'];            
                $geofencepart->geolong = $row['geolong'];            
                $geofence[] = $geofencepart;
            }
            return $geofence;            
        }
        return null;
    }        
    
    public function getdistinctfencenames() 
    {
        $geofence = Array();
        $fenceQuery = sprintf("SELECT * FROM `fence` WHERE customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($fenceQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $geofencepart = new VOGeofence();
                $geofencepart->fencename = $row['fencename'];
                $geofencepart->fenceid = $row['fenceid'];     
                $geofencepart->trackeeid = $row['trackeeid'];                     
                $geofence[] = $geofencepart;
            }
            return $geofence;            
        }
        return null;
    }            

    public function mapfencetotrackee($fenceid, $trackeeid)
    {
        $SQL = sprintf( "Update fence
                        Set `trackeeid`=%d
                        WHERE fenceid = %d AND customerno = %d",
                        Sanitise::String($trackeeid),
                        Sanitise::String($fenceid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }
    
    public function demapfence($fenceid)
    {
        $SQL = sprintf( "Update fence
                        Set `trackeeid`=0
                        WHERE fenceid = %d AND customerno = %d",
                        Sanitise::String($fenceid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }    
    
    public function markoutsidefence($fenceid)
    {
        $SQL = sprintf( "Update fence
                        Set `conflictstatus`=1
                        WHERE fenceid = %d AND customerno = %d",
                        Sanitise::String($fenceid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }    
    
    public function markinsidefence($fenceid)
    {
        $SQL = sprintf( "Update fence
                        Set `conflictstatus`=0
                        WHERE fenceid = %d AND customerno = %d",
                        Sanitise::String($fenceid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }        
    
    public function DeleteGeofence($fenceid)
    {
        $SQL = sprintf("DELETE FROM geofence WHERE fenceid = %s and customerno = %d",
                        Sanitise::Long($fenceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        
        $SQLfence = sprintf("DELETE FROM fence WHERE fenceid = %s and customerno = %d",
                        Sanitise::Long($fenceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQLfence);        
    }
}