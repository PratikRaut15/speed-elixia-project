<?php
require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/DeviceManager.php';
require_once '../../lib/bo/ComQueueManager.php';
require_once '../../lib/bo/CronManager.php';
require_once '../../lib/bo/PointLocationManager.php';
require_once 'files/calculatedist.php';
require_once 'files/push_sqlite.php';

$cm = new CronManager();
$cqm = new ComQueueManager();
$devices = $cm->getalldevices();
if(isset($devices))
{
    foreach($devices as $thisdevice)
    {      
        // Tamper
        if($thisdevice->tamper == 1 && $thisdevice->tamperstatus == 0)
        {            
            // Insert in Queue
            $cvo = new VOComQueue();
            $cvo->customerno = $thisdevice->customerno;
            $cvo->lat = $thisdevice->devicelat;
            $cvo->long = $thisdevice->devicelong;            
            $cvo->message = $thisdevice->vehicleno." underwent tampering";
            $cvo->type = 7;
            $cvo->status = 0;            
            $cvo->vehicleid = $thisdevice->vehicleid;                                    
            $cqm->InsertQ($cvo);  
            $cm->marktampered($thisdevice->vehicleid, $thisdevice->customerno);
        }    
        elseif($thisdevice->tamper == 0 && $thisdevice->tamperstatus == 1)
        {
            // Insert in Queue
            $cvo = new VOComQueue();
            $cvo->customerno = $thisdevice->customerno;
            $cvo->lat = $thisdevice->devicelat;
            $cvo->long = $thisdevice->devicelong;            
            $cvo->message = $thisdevice->vehicleno." was back to normal";
            $cvo->type = 7;
            $cvo->status = 1;            
            $cvo->vehicleid = $thisdevice->vehicleid;                                    
            $cqm->InsertQ($cvo);              
            $cm->markuntampered($thisdevice->vehicleid, $thisdevice->customerno);
        }                                        
        
        // Power Cut
        if($thisdevice->powercut == 0 && $thisdevice->powercut_status == 0)
        {            
            // Insert in Queue
            $cvo = new VOComQueue();
            $cvo->customerno = $thisdevice->customerno;
            $cvo->lat = $thisdevice->devicelat;
            $cvo->long = $thisdevice->devicelong;             
            $cvo->message = $thisdevice->vehicleno." underwent power cut";
            $cvo->type = 6;
            $cvo->status = 0;            
            $cvo->vehicleid = $thisdevice->vehicleid;                                    
            $cqm->InsertQ($cvo);  
            $cm->markpowercut($thisdevice->vehicleid, $thisdevice->customerno);
        }    
        elseif($thisdevice->powercut == 1 && $thisdevice->powercut_status == 1)
        {
            // Insert in Queue
            $cvo = new VOComQueue();
            $cvo->customerno = $thisdevice->customerno;
            $cvo->lat = $thisdevice->devicelat;
            $cvo->long = $thisdevice->devicelong;  
            $cvo->message = $thisdevice->vehicleno." regained power";
            $cvo->type = 6;
            $cvo->status = 1;            
            $cvo->vehicleid = $thisdevice->vehicleid;                                    
            $cqm->InsertQ($cvo);  
            $cm->markpowerin($thisdevice->vehicleid, $thisdevice->customerno);
        }                                                
        
        // Overspeed
        if($thisdevice->status == "B" && $thisdevice->overspeed_status == 0)
        {            
            // Insert in Queue
            $cvo = new VOComQueue();
            $cvo->customerno = $thisdevice->customerno;
            $cvo->lat = $thisdevice->devicelat;
            $cvo->long = $thisdevice->devicelong;   
            $cvo->message = $thisdevice->vehicleno." oversped";
            $cvo->type = 5;
            $cvo->status = 0;            
            $cvo->vehicleid = $thisdevice->vehicleid;                                    
            $cqm->InsertQ($cvo);   
            $cm->markoverspeeding($thisdevice->vehicleid, $thisdevice->customerno);
        }    
        elseif($thisdevice->status == "C" && $thisdevice->overspeed_status == 1)
        {
            // Insert in Queue
            $cvo = new VOComQueue();
            $cvo->customerno = $thisdevice->customerno;
            $cvo->lat = $thisdevice->devicelat;
            $cvo->long = $thisdevice->devicelong;             
            $cvo->message = $thisdevice->vehicleno." was running normal";
            $cvo->type = 5;
            $cvo->status = 1;            
            $cvo->vehicleid = $thisdevice->vehicleid;                                    
            $cqm->InsertQ($cvo);   
            $cm->marknormalspeeding($thisdevice->vehicleid, $thisdevice->customerno);
        }  
        
        // AC Sensor
        if($thisdevice->acsensor == 1 && ($thisdevice->status!='H' && $thisdevice->status!='F'))
        {
            if($thisdevice->digitalio == 0 && $thisdevice->ac_status == 0)
            {             
                // Insert in Queue
                $cvo = new VOComQueue();
                $cvo->customerno = $thisdevice->customerno;
                $cvo->lat = $thisdevice->devicelat;
                $cvo->long = $thisdevice->devicelong;      
                if($thisdevice->is_ac_opp == 0)
                {
                    $cvo->message = $thisdevice->vehicleno." has AC switched on";
                    $cvo->status = 1;                            
                }
                else
                {
                    $cvo->message = $thisdevice->vehicleno." has AC switched off";                    
                    $cvo->status = 0;                                
                }
                $cvo->type = 1;
                $cvo->vehicleid = $thisdevice->vehicleid;                                    
                $cqm->InsertQ($cvo);  
                $cm->markacon($thisdevice->vehicleid, $thisdevice->customerno);                
            }
            else if($thisdevice->digitalio == 1 && $thisdevice->ac_status == 1)
            { 
                // Insert in Queue
                $cvo = new VOComQueue();
                $cvo->customerno = $thisdevice->customerno;
                $cvo->lat = $thisdevice->devicelat;
                $cvo->long = $thisdevice->devicelong;            
                if($thisdevice->is_ac_opp == 1)
                {
                    $cvo->message = $thisdevice->vehicleno." has AC switched on";
                    $cvo->status = 1;                            
                }
                else
                {
                    $cvo->message = $thisdevice->vehicleno." has AC switched off";                    
                    $cvo->status = 0;                                
                }
                $cvo->type = 1;
                $cvo->vehicleid = $thisdevice->vehicleid;                                    
                $cqm->InsertQ($cvo);     
                $cm->markacoff($thisdevice->vehicleid, $thisdevice->customerno);                
            }
        }
        
        //Checkpoint
        $chkpts = $cm->getallchkpforcronbyvehicleid($thisdevice->vehicleid);
        if(isset($chkpts))
        {
            foreach($chkpts as $thischkpt)
            {
                $devicelat = $thisdevice->devicelat;
                $devicelong = $thisdevice->devicelong;
                $cgeolat = $thischkpt->cgeolat;
                $cgeolong = $thischkpt->cgeolong;
                $crad = (float)$thischkpt->crad;
                $distance = calculate($devicelat, $devicelong, $cgeolat, $cgeolong);
                if($distance>=$crad && $thischkpt->conflictstatus == 0)
                { 
                        // Insert in Queue
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;            
                        $cvo->message = $thisdevice->vehicleno." left ".$thischkpt->cname;
                        $cvo->type = 2;
                        $cvo->status = 0;            
                        $cvo->vehicleid = $thisdevice->vehicleid; 
                        $cvo->chkid = $thischkpt->checkpointid;                                    
                        $cqm->InsertQChk($cvo);     
                        $cm->markoutsidechk($thischkpt->cmid, $thisdevice->customerno);
                ChkSqlite($thisdevice->customerno, $thischkpt->checkpointid, 1, $thisdevice->lastupdated, $thisdevice->vehicleid);
                }
                else if($distance<$crad && $thischkpt->conflictstatus == 1)
                { 
                        // Insert in Queue
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;                  
                        $cvo->message = $thisdevice->vehicleno." entered ".$thischkpt->cname;
                        $cvo->type = 2;
                        $cvo->status = 1;            
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->chkid = $thischkpt->checkpointid;                                       
                        $cqm->InsertQChk($cvo);     
                        $cm->markinsidechk($thischkpt->cmid, $thisdevice->customerno);
                ChkSqlite($thisdevice->customerno, $thischkpt->checkpointid, 0, $thisdevice->lastupdated, $thisdevice->vehicleid);
                }
            }
        }
        
        //fence
        $fences = $cm->getallgeofencesforcronbyvehicleid($thisdevice->vehicleid);
        if(isset($fences))
        {
            $polygon = array();
            $pointLocation = new PointLocation();
            $points = array($thisdevice->devicelat." ".$thisdevice->devicelong);
            foreach($fences as $fence)
                    {
                    $conflictstatus = $fence->conflictstatus;
                    $geofence = $cm->get_geofence_from_fenceid($fence->fenceid);
                        if(isset($geofence))
                        {
                            foreach($geofence as $thisgeofence)
                            {
                                $polygon[] = $thisgeofence->geolat." ".$thisgeofence->geolong;
                            }
                            foreach($points as $point)
                            {
                                if($pointLocation->checkPointStatus($point, $polygon) == "outside" && $conflictstatus == 0)
                                { 
                                    // Insert in Queue
                                    $cvo = new VOComQueue();
                                    $cvo->customerno = $thisdevice->customerno;
                                    $cvo->lat = $thisdevice->devicelat;
                                    $cvo->long = $thisdevice->devicelong;            
                                    $cvo->message = $thisdevice->vehicleno." was out of ".$fence->fencename;
                                    $cvo->type = 3;
                                    $cvo->status = 0;            
                                    $cvo->vehicleid = $thisdevice->vehicleid; 
                                    $cvo->fenceid = $fence->fenceid;                                    
                                    $cqm->InsertQFence($cvo);     
                                    $cm->markoutsidefence($fence->fenceid, $thisdevice->vehicleid, $thisdevice->customerno);
                                }
                                else if($pointLocation->checkPointStatus($point, $polygon) == "inside" && $conflictstatus == 1)
                                { 
                                    // Insert in Queue
                                    $cvo = new VOComQueue();
                                    $cvo->customerno = $thisdevice->customerno;
                                    $cvo->lat = $thisdevice->devicelat;
                                    $cvo->long = $thisdevice->devicelong;                       
                                    $cvo->message = $thisdevice->vehicleno." was in ".$fence->fencename;
                                    $cvo->type = 3;
                                    $cvo->status = 1;            
                                    $cvo->vehicleid = $thisdevice->vehicleid; 
                                    $cvo->fenceid = $fence->fenceid;                                    
                                    $cqm->InsertQFence($cvo);     
                                    $cm->markinsidefence($fence->fenceid, $thisdevice->vehicleid, $thisdevice->customerno);
                                }
                            }
                        }
                    }
        }
    }
}

?>
