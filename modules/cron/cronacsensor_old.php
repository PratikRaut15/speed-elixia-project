<?php
//set_time_limit(0);
require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/DeviceManager.php';
require_once '../../lib/bo/CustomerManager.php';
require_once '../../lib/bo/CommunicationQueueManager.php';
require_once '../../lib/bo/VehicleManager.php';
require_once '../../lib/bo/ComHistoryManager.php';
require_once '../../lib/bo/UserManager.php';

$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {
        $dm = new DeviceManager($thiscustomerno);
        $devices = $dm->getalldevicesformonitoring();
        if(isset($devices))
        {
            foreach($devices as $thisdevice)
            { 
                $getin = true;
                if($thisdevice->digitalio == 0 && $thisdevice->is_ac_opp == 0)
                {
                    $getin = true;
                }
                if($thisdevice->digitalio == 0 && $thisdevice->is_ac_opp == 1)
                {
                    $getin = false;
                }
                if($thisdevice->digitalio == 1 && $thisdevice->is_ac_opp == 0)
                {
                    $getin = false;
                }
                if($thisdevice->digitalio == 1 && $thisdevice->is_ac_opp == 1)
                {
                    $getin = true;
                }                
                if($thisdevice->status!='H' || $thisdevice->status!='F')
                {
                    // AC Sensor
                    if($getin && $thisdevice->ac_status == 1 && $thisdevice->acsensor == 1)
                    {   
                        // Populate communication queue
                        $um = new UserManager();
                        $users = $um->getunfilteredusersforcustomer($thiscustomerno);
                    // Alert
                    $date = new Date();
                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                    $alert = new ComHistoryManager($thiscustomerno);
                    $alertvo = new VOComHistory();
                    $status = $alert->get_status_id('acsensor');
                    $alertvo->statusid = $status->statusid;
                    $alertvo->statustype = '1';
                    $vehiclemanager = new VehicleManager($thiscustomerno);
                    $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                    $alertvo->message = $vehicle->vehicleno." has AC switched off at ".$hourminutes.". Powered by Elixia Tech.";
                    $alertvo->vehicleid = $vehicle->vehicleid;
                    $alertvo->checkpointid = "";
                    $alertvo->fenceid = "";
                    $alertvo->customerno = $thiscustomerno;
                    $alert->InsertAlert($alertvo); 
                        if(isset($users))
                        {
                            foreach($users as $thisuser)
                            {
                                //$ums = new UserManager($thisuser->customerno);
                                $groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
                                $encodekey = sha1($thisuser->userkey);
                                $dates = new Date();
                                $hms = $dates->return_hour(date("H:i:s"), 0);
                           if(strtotime($thisuser->start_alert_time) < strtotime($thisuser->stop_alert_time))
                           {
                            if(strtotime($thisuser->start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->stop_alert_time))
                              {      //echo 'valid';
                                if(isset($groups))
                                {
                                    foreach($groups as $group)
                                    {
                                        $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisdevice->vehicleid);    
                                        if(isset($vehicles)){
                                        if(isset($thisuser->ac_email) && $thisuser->ac_email == 1)
                                        {
                                            if(isset($thisuser->email) && $thisuser->email != "")
                                            {
                                                //$date = new Date();
                                                //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                $cqm = new CommunicationQueueManager();
                                                $cvo = new VOCommunicationQueue();
                                                $cvo->email = $thisuser->email;
                                                //$vehiclemanager = new VehicleManager($thiscustomerno);
                                                //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                $cvo->message = $vehicle->vehicleno." has AC switched off at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                $cvo->subject = "AC Status";
                                                $cvo->phone = "";
                                                $cvo->type = 0;
                                                $cvo->customerno = $thiscustomerno;
                                                $cqm->InsertQ($cvo);                                                                       
                                            }    
                                        }
                                        if(isset($thisuser->ac_sms) && $thisuser->ac_sms == 1)
                                        {
                                            if(isset($thisuser->phone) && $thisuser->phone != "")
                                            {
                                                //$date = new Date();
                                                //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                $cqm = new CommunicationQueueManager();
                                                $cvo = new VOCommunicationQueue();
                                                $cvo->phone = $thisuser->phone;
                                                //$vehiclemanager = new VehicleManager($thiscustomerno);
                                                //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                $cvo->message = $vehicle->vehicleno." has AC switched off at ".$hourminutes.". Powered by Elixia Tech.";
                                                $cvo->subject = "";
                                                $cvo->email = "";
                                                $cvo->type = 1;
                                                $cvo->customerno = $thiscustomerno;                                        
                                                $cqm->InsertQ($cvo);                                                                       
                                            }    
                                        }
                                        }
                                    }
                                }
                                else{
                                if(isset($thisuser->ac_email) && $thisuser->ac_email == 1)
                                {
                                    if(isset($thisuser->email) && $thisuser->email != "")
                                    {
                                        //$date = new Date();
                                        //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                        $cqm = new CommunicationQueueManager();
                                        $cvo = new VOCommunicationQueue();
                                        $cvo->email = $thisuser->email;
                                        //$vehiclemanager = new VehicleManager($thiscustomerno);
                                        //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                        $cvo->message = $vehicle->vehicleno." has AC switched off at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                        $cvo->subject = "AC Status";
                                        $cvo->phone = "";
                                        $cvo->type = 0;
                                        $cvo->customerno = $thiscustomerno;
                                        $cqm->InsertQ($cvo);                                                                       
                                    }    
                                }
                                if(isset($thisuser->ac_sms) && $thisuser->ac_sms == 1)
                                {
                                    if(isset($thisuser->phone) && $thisuser->phone != "")
                                    {
                                        //$date = new Date();
                                        //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                        $cqm = new CommunicationQueueManager();
                                        $cvo = new VOCommunicationQueue();
                                        $cvo->phone = $thisuser->phone;
                                        //$vehiclemanager = new VehicleManager($thiscustomerno);
                                        //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                        $cvo->message = $vehicle->vehicleno." has AC switched off at ".$hourminutes.". Powered by Elixia Tech.";
                                        $cvo->subject = "";
                                        $cvo->email = "";
                                        $cvo->type = 1;
                                        $cvo->customerno = $thiscustomerno;                                        
                                        $cqm->InsertQ($cvo);                                                                       
                                    }    
                                }
                                }
                              }
                              else{
                                    //echo 'not valid';
                              }
                           }
                           else if(strtotime($thisuser->start_alert_time) > strtotime($thisuser->stop_alert_time)){
                            if(strtotime($thisuser->stop_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->start_alert_time))
                              {      
                                //echo 'Start Time is Greater Than Stop Time<br>not valid'.$thisuser->id.'_'.$thisuser->realname.'_'.$thiscustomerno.'<br><br>';
                              }
                            else
                              {      //echo 'valid';
                                if(isset($groups))
                                {
                                    foreach($groups as $group)
                                    {
                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                        $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisdevice->vehicleid);    
                                        if(isset($vehicles)){
                                        if(isset($thisuser->ac_email) && $thisuser->ac_email == 1)
                                        {
                                            if(isset($thisuser->email) && $thisuser->email != "")
                                            {
                                                //$date = new Date();
                                                //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                $cqm = new CommunicationQueueManager();
                                                $cvo = new VOCommunicationQueue();
                                                $cvo->email = $thisuser->email;
                                                //$vehiclemanager = new VehicleManager($thiscustomerno);
                                                //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                $cvo->message = $vehicle->vehicleno." has AC switched off at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                $cvo->subject = "AC Status";
                                                $cvo->phone = "";
                                                $cvo->type = 0;
                                                $cvo->customerno = $thiscustomerno;
                                                $cqm->InsertQ($cvo);                                                                       
                                            }    
                                        }
                                        if(isset($thisuser->ac_sms) && $thisuser->ac_sms == 1)
                                        {
                                            if(isset($thisuser->phone) && $thisuser->phone != "")
                                            {
                                                //$date = new Date();
                                                //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                $cqm = new CommunicationQueueManager();
                                                $cvo = new VOCommunicationQueue();
                                                $cvo->phone = $thisuser->phone;
                                                //$vehiclemanager = new VehicleManager($thiscustomerno);
                                                //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                $cvo->message = $vehicle->vehicleno." has AC switched off at ".$hourminutes.". Powered by Elixia Tech.";
                                                $cvo->subject = "";
                                                $cvo->email = "";
                                                $cvo->type = 1;
                                                $cvo->customerno = $thiscustomerno;                                        
                                                $cqm->InsertQ($cvo);                                                                       
                                            }    
                                        }
                                        }
                                    }
                                }
                                else{
                                if(isset($thisuser->ac_email) && $thisuser->ac_email == 1)
                                {
                                    if(isset($thisuser->email) && $thisuser->email != "")
                                    {
                                        //$date = new Date();
                                        //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                        $cqm = new CommunicationQueueManager();
                                        $cvo = new VOCommunicationQueue();
                                        $cvo->email = $thisuser->email;
                                        //$vehiclemanager = new VehicleManager($thiscustomerno);
                                        //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                        $cvo->message = $vehicle->vehicleno." has AC switched off at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                        $cvo->subject = "AC Status";
                                        $cvo->phone = "";
                                        $cvo->type = 0;
                                        $cvo->customerno = $thiscustomerno;
                                        $cqm->InsertQ($cvo);                                                                       
                                    }    
                                }
                                if(isset($thisuser->ac_sms) && $thisuser->ac_sms == 1)
                                {
                                    if(isset($thisuser->phone) && $thisuser->phone != "")
                                    {
                                        //$date = new Date();
                                        //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                        $cqm = new CommunicationQueueManager();
                                        $cvo = new VOCommunicationQueue();
                                        $cvo->phone = $thisuser->phone;
                                        //$vehiclemanager = new VehicleManager($thiscustomerno);
                                        //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                        $cvo->message = $vehicle->vehicleno." has AC switched off at ".$hourminutes.". Powered by Elixia Tech.";
                                        $cvo->subject = "";
                                        $cvo->email = "";
                                        $cvo->type = 1;
                                        $cvo->customerno = $thiscustomerno;                                        
                                        $cqm->InsertQ($cvo);                                                                       
                                    }    
                                }
                                }
                              }
                           }
                            }
                        }
                        $dm->markacoff($thisdevice->vehicleid);
                    }    

                    else if($getin && $thisdevice->ac_status == 0 && $thisdevice->acsensor == 1)
                    {   
                        // Populate communication queue
                        $um = new UserManager();
                        $users = $um->getunfilteredusersforcustomer($thiscustomerno);
                        // Alert
                        $date = new Date();
                        $hourminutes = $date->return_hours($thisdevice->lastupdated);
                        $alert = new ComHistoryManager($thiscustomerno);
                        $alertvo = new VOComHistory();
                        $status = $alert->get_status_id('acsensor');
                        $alertvo->statusid = $status->statusid;
                        $alertvo->statustype = '0';
                        $vehiclemanager = new VehicleManager($thiscustomerno);
                        $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                        $alertvo->message = $vehicle->vehicleno." now has AC switched on at ".$hourminutes.". Powered by Elixia Tech.";
                        $alertvo->vehicleid = $vehicle->vehicleid;
                        $alertvo->checkpointid = "";
                        $alertvo->fenceid = "";
                        $alertvo->customerno = $thiscustomerno;
                        $alert->InsertAlert($alertvo);  
                        if(isset($users))
                        {
                            foreach($users as $thisuser)
                            {
                                //$ums = new UserManager($thisuser->customerno);
                                $groups = $um->get_groups_fromuser($thisuser->customerno, $thisuser->id);
                                $encodekey = sha1($thisuser->userkey);
                                $dates = new Date();
                                $hms = $dates->return_hour(date("H:i:s"), 0);
                           if(strtotime($thisuser->start_alert_time) < strtotime($thisuser->stop_alert_time))
                           {
                            if(strtotime($thisuser->start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->stop_alert_time))
                              {       //echo 'valid';
                                if(isset($groups))
                                {
                                    foreach($groups as $group)
                                    {
                                        $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisdevice->vehicleid);    
                                            if(isset($vehicles)){
                                            if(isset($thisuser->ac_email) && $thisuser->ac_email == 1)
                                            {
                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                {
                                                    //$date = new Date();
                                                    //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->email = $thisuser->email;
                                                    //$vehiclemanager = new VehicleManager($thiscustomerno);
                                                    //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                                    $cvo->message = $vehicle->vehicleno." now has AC switched on at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                    $cvo->subject = "AC Status";
                                                    $cvo->phone = "";
                                                    $cvo->type = 0;
                                                    $cvo->customerno = $thiscustomerno;                                        
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                            if(isset($thisuser->ac_sms) && $thisuser->ac_sms == 1)
                                            {
                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                {
                                                    //$date = new Date();
                                                    //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->phone = $thisuser->phone;
                                                    //$vehiclemanager = new VehicleManager($thiscustomerno);
                                                    //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                    $cvo->message = $vehicle->vehicleno." now has AC switched on at ".$hourminutes.". Powered by Elixia Tech.";
                                                    $cvo->subject = "";
                                                    $cvo->email = "";
                                                    $cvo->type = 1;
                                                    $cvo->customerno = $thiscustomerno;                                        
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                        }
                                    }
                                }
                                else{
                                if(isset($thisuser->ac_email) && $thisuser->ac_email == 1)
                                {
                                    if(isset($thisuser->email) && $thisuser->email != "")
                                    {
                                        //$date = new Date();
                                        //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                        $cqm = new CommunicationQueueManager();
                                        $cvo = new VOCommunicationQueue();
                                        $cvo->email = $thisuser->email;
                                        //$vehiclemanager = new VehicleManager($thiscustomerno);
                                        //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                        $cvo->message = $vehicle->vehicleno." now has AC switched on at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                        $cvo->subject = "AC Status";
                                        $cvo->phone = "";
                                        $cvo->type = 0;
                                        $cvo->customerno = $thiscustomerno;                                        
                                        $cqm->InsertQ($cvo);                                                                       
                                    }    
                                }
                                if(isset($thisuser->ac_sms) && $thisuser->ac_sms == 1)
                                {
                                    if(isset($thisuser->phone) && $thisuser->phone != "")
                                    {
                                        //$date = new Date();
                                        //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                        $cqm = new CommunicationQueueManager();
                                        $cvo = new VOCommunicationQueue();
                                        $cvo->phone = $thisuser->phone;
                                        //$vehiclemanager = new VehicleManager($thiscustomerno);
                                        //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                        $cvo->message = $vehicle->vehicleno." now has AC switched on at ".$hourminutes.". Powered by Elixia Tech.";
                                        $cvo->subject = "";
                                        $cvo->email = "";
                                        $cvo->type = 1;
                                        $cvo->customerno = $thiscustomerno;                                        
                                        $cqm->InsertQ($cvo);                                                                       
                                    }    
                                }
                            }
                           }
                           }
                           else if(strtotime($thisuser->start_alert_time) > strtotime($thisuser->stop_alert_time)){
                            if(strtotime($thisuser->stop_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->start_alert_time))
                              {
                                //not valid
                              }
                            else{       //echo 'valid';
                                if(isset($groups))
                                {
                                    foreach($groups as $group)
                                    {
                                        $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisdevice->vehicleid);    
                                            if(isset($vehicles)){
                                            if(isset($thisuser->ac_email) && $thisuser->ac_email == 1)
                                            {
                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                {
                                                    //$date = new Date();
                                                    //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->email = $thisuser->email;
                                                    //$vehiclemanager = new VehicleManager($thiscustomerno);
                                                    //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                                    $cvo->message = $vehicle->vehicleno." now has AC switched on at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                    $cvo->subject = "AC Status";
                                                    $cvo->phone = "";
                                                    $cvo->type = 0;
                                                    $cvo->customerno = $thiscustomerno;                                        
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                            if(isset($thisuser->ac_sms) && $thisuser->ac_sms == 1)
                                            {
                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                {
                                                    //$date = new Date();
                                                    //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->phone = $thisuser->phone;
                                                    //$vehiclemanager = new VehicleManager($thiscustomerno);
                                                    //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                    $cvo->message = $vehicle->vehicleno." now has AC switched on at ".$hourminutes.". Powered by Elixia Tech.";
                                                    $cvo->subject = "";
                                                    $cvo->email = "";
                                                    $cvo->type = 1;
                                                    $cvo->customerno = $thiscustomerno;                                        
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                        }
                                    }
                                }
                                else{
                                if(isset($thisuser->ac_email) && $thisuser->ac_email == 1)
                                {
                                    if(isset($thisuser->email) && $thisuser->email != "")
                                    {
                                        //$date = new Date();
                                        //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                        $cqm = new CommunicationQueueManager();
                                        $cvo = new VOCommunicationQueue();
                                        $cvo->email = $thisuser->email;
                                        //$vehiclemanager = new VehicleManager($thiscustomerno);
                                        //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                        $cvo->message = $vehicle->vehicleno." now has AC switched on at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                        $cvo->subject = "AC Status";
                                        $cvo->phone = "";
                                        $cvo->type = 0;
                                        $cvo->customerno = $thiscustomerno;                                        
                                        $cqm->InsertQ($cvo);                                                                       
                                    }    
                                }
                                if(isset($thisuser->ac_sms) && $thisuser->ac_sms == 1)
                                {
                                    if(isset($thisuser->phone) && $thisuser->phone != "")
                                    {
                                        //$date = new Date();
                                        //$hourminutes = $date->return_hours($thisdevice->lastupdated);
                                        $cqm = new CommunicationQueueManager();
                                        $cvo = new VOCommunicationQueue();
                                        $cvo->phone = $thisuser->phone;
                                        //$vehiclemanager = new VehicleManager($thiscustomerno);
                                        //$vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                        $cvo->message = $vehicle->vehicleno." now has AC switched on at ".$hourminutes.". Powered by Elixia Tech.";
                                        $cvo->subject = "";
                                        $cvo->email = "";
                                        $cvo->type = 1;
                                        $cvo->customerno = $thiscustomerno;                                        
                                        $cqm->InsertQ($cvo);                                                                       
                                    }    
                                }
                            }
                           }
                           }
                          }
                        }
                        $dm->markacon($thisdevice->vehicleid);
                    }                                        
                }
            }
        }
    }
}

?>
