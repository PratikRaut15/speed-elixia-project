<?php
include_once("../../session.php");
include_once("../../db.php");
include_once("../../lib/system/utilities.php");
include_once("../../lib/system/Validator.php");
include_once("../../lib/model/VODevices.php");
include_once("../../lib/system/DatabaseManager.php");
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/system/Date.php';

class VOTrack
{
    
}

class TrackManager
{
    public function checklogin($username, $password)
    {
        $today = date("Y-m-d H:i:s");        
        
        $SQL = sprintf("select * from `user` where username = '%s' AND password = '%s' limit 1"
                        , Validator::escapeCharacters($username), Validator::escapeCharacters($password));
        $db = new DatabaseManager();
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
                $row = $db->get_nextRow();
                $track = new VOTrack();
                $track->userkey = $row["userkey"];
                $track->customerno = $row["customerno"];
                $track->userid = $row["userid"];
                
                $SQLUpdate = sprintf("UPDATE user SET lastvisit = '%s', visited = visited+1 WHERE userid = %d AND customerno = %d"
                                , Sanitise::DateTime($today)                
                                , Validator::escapeCharacters($track->userid)                        
                                , Validator::escapeCharacters($track->customerno));
                $db = new DatabaseManager();
                        $db->executeQuery($SQLUpdate);
                
            return $track;
                    
        }
        return null;        
    }   
    
    public function pulltrackees($customerno)
    {
        $trackees = Array();
        $SQL = sprintf("select * from `trackee` 
                        INNER JOIN devices ON devices.trackeeid = trackee.trackeeid
                        where trackee.customerno = %d AND trackee.isdeleted = 0 AND devices.isregistered = 1"
                        , Validator::escapeCharacters($customerno));
        $db = new DatabaseManager();
                $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) 
        {
            while ($row = $db->get_nextRow())
            {
                $track = new VOTrack();
                $track->customerno = $row["customerno"];
                $track->trackeename = $row["tname"];
                $track->trackeeid = $row["trackeeid"];
                $trackees[] = $track;
            }
            return $trackees;
        }
        return null;
    }           
    
    public function pulldetails($customerno, $trackeeid)
    {
        $SQL = sprintf("select * from `devices` 
                        where customerno = %d AND trackeeid = %d"
                        , Validator::escapeCharacters($customerno)
                        , Validator::escapeCharacters($trackeeid));        
        $db = new DatabaseManager();
                $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) 
        {
            while ($row = $db->get_nextRow())
            {
                $track = new VOTrack();
                $track->customerno = $row["customerno"];
                $track->lastupdated = $row["lastupdated"];
                $track->devicelat = $row["devicelat"];
                $track->devicelong = $row["devicelong"];                
            }
            return $track;
        }
        return null;
    }               
}