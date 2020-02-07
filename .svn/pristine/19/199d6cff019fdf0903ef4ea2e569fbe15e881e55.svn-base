<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/DeviceManager.php';
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/CommunicationQueueManager.php';
require '../../lib/bo/VehicleManager.php';
require '../../lib/bo/UserManager.php';

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
                if($thisdevice->status!='H' || $thisdevice->status!='F')
                {
                    if(isset($thisdevice->analog1_sen) && $thisdevice->analog1_sen=='1' && $thisdevice->temp!='0')
                    {
                        $tempcheck = checktemp($thisdevice->temp,$thisdevice->maxtemp,$thisdevice->mintemp);
                        // AC Sensor
                        if($tempcheck != '0' && $thisdevice->temp_status == 0)
                        {
                            // Populate communication queue
                            $um = new UserManager();
                            $users = $um->getunfilteredusersforcustomer($thiscustomerno);
                            if(isset($users))
                            {
                                foreach($users as $thisuser)
                                {
                                $ums = new UserManager($thisuser->customerno);
                                $groups = $ums->get_groups_fromuser($thisuser->customerno, $thisuser->id);
                                $encodekey = sha1($thisuser->userkey);
                                $dates = new Date();
                                $hms = $dates->return_hour(date("H:i:s"), 0);
                            if(strtotime($thisuser->start_alert_time) < strtotime($thisuser->stop_alert_time))
                            {
                            if(strtotime($thisuser->start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->stop_alert_time))
                              {
                                    if(isset($groups))
                                    {
                                        foreach($groups as $group)
                                        {
                                            $vehiclemanager = new VehicleManager($thiscustomerno);
                                            $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisdevice->vehicleid);    
                                            if(isset($vehicles)){
                                                            if(isset($thisuser->temp_email) && $thisuser->temp_email == 1)
                                                            {
                                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                                {
                                                                    $date = new Date();
                                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                    $cqm = new CommunicationQueueManager();
                                                                    $cvo = new VOCommunicationQueue();
                                                                    $cvo->email = $thisuser->email;
                                                                    $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                    $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                                                    if($tempcheck=='1,1')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was above specified range at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                    }
                                                                    else if($tempcheck=='-1,0')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was below specified range at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                    }
                                                                    $cvo->subject = "Temperature Status";
                                                                    $cvo->phone = "";                                            
                                                                    $cvo->type = 0;
                                                                    $cvo->customerno = $thiscustomerno;                                            
                                                                    $cqm->InsertQ($cvo);                                                                       
                                                                }    
                                                            }
                                                            if(isset($thisuser->temp_sms) && $thisuser->temp_sms == 1)
                                                            {
                                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                                {
                                                                    $date = new Date();
                                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                    $cqm = new CommunicationQueueManager();
                                                                    $cvo = new VOCommunicationQueue();
                                                                    $cvo->phone = $thisuser->phone;
                                                                    $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                    $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                                    if($tempcheck=='1,1')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was above specified range at ".$hourminutes.". Powered by Elixia Tech.";
                                                                    }
                                                                    else if($tempcheck=='-1,0')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was below specified range at ".$hourminutes.". Powered by Elixia Tech.";
                                                                    }
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
                                                            if(isset($thisuser->temp_email) && $thisuser->temp_email == 1)
                                                            {
                                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                                {
                                                                    $date = new Date();
                                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                    $cqm = new CommunicationQueueManager();
                                                                    $cvo = new VOCommunicationQueue();
                                                                    $cvo->email = $thisuser->email;
                                                                    $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                    $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                                                    if($tempcheck=='1,1')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was above specified range at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                    }
                                                                    else if($tempcheck=='-1,0')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was below specified range at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                    }
                                                                    $cvo->subject = "Temperature Status";
                                                                    $cvo->phone = "";                                            
                                                                    $cvo->type = 0;
                                                                    $cvo->customerno = $thiscustomerno;                                            
                                                                    $cqm->InsertQ($cvo);                                                                       
                                                                }    
                                                            }
                                                            if(isset($thisuser->temp_sms) && $thisuser->temp_sms == 1)
                                                            {
                                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                                {
                                                                    $date = new Date();
                                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                    $cqm = new CommunicationQueueManager();
                                                                    $cvo = new VOCommunicationQueue();
                                                                    $cvo->phone = $thisuser->phone;
                                                                    $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                    $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                                    if($tempcheck=='1,1')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was above specified range at ".$hourminutes.". Powered by Elixia Tech.";
                                                                    }
                                                                    else if($tempcheck=='-1,0')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was below specified range at ".$hourminutes.". Powered by Elixia Tech.";
                                                                    }
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
                            else if(strtotime($thisuser->start_alert_time) > strtotime($thisuser->stop_alert_time))
                            {
                              if(strtotime($thisuser->stop_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->start_alert_time))
                              {      
                                //echo 'Start Time is Greater Than Stop Time<br>not valid'.$thisuser->id.'_'.$thisuser->realname.'_'.$thiscustomerno.'<br><br>';
                              }
                              else
                              {
                                    if(isset($groups))
                                    {
                                        foreach($groups as $group)
                                        {
                                            $vehiclemanager = new VehicleManager($thiscustomerno);
                                            $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisdevice->vehicleid);    
                                            if(isset($vehicles)){
                                                            if(isset($thisuser->temp_email) && $thisuser->temp_email == 1)
                                                            {
                                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                                {
                                                                    $date = new Date();
                                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                    $cqm = new CommunicationQueueManager();
                                                                    $cvo = new VOCommunicationQueue();
                                                                    $cvo->email = $thisuser->email;
                                                                    $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                    $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                                                    if($tempcheck=='1,1')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was above specified range at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                    }
                                                                    else if($tempcheck=='-1,0')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was below specified range at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                    }
                                                                    $cvo->subject = "Temperature Status";
                                                                    $cvo->phone = "";                                            
                                                                    $cvo->type = 0;
                                                                    $cvo->customerno = $thiscustomerno;                                            
                                                                    $cqm->InsertQ($cvo);                                                                       
                                                                }    
                                                            }
                                                            if(isset($thisuser->temp_sms) && $thisuser->temp_sms == 1)
                                                            {
                                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                                {
                                                                    $date = new Date();
                                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                    $cqm = new CommunicationQueueManager();
                                                                    $cvo = new VOCommunicationQueue();
                                                                    $cvo->phone = $thisuser->phone;
                                                                    $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                    $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                                    if($tempcheck=='1,1')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was above specified range at ".$hourminutes.". Powered by Elixia Tech.";
                                                                    }
                                                                    else if($tempcheck=='-1,0')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was below specified range at ".$hourminutes.". Powered by Elixia Tech.";
                                                                    }
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
                                                            if(isset($thisuser->temp_email) && $thisuser->temp_email == 1)
                                                            {
                                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                                {
                                                                    $date = new Date();
                                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                    $cqm = new CommunicationQueueManager();
                                                                    $cvo = new VOCommunicationQueue();
                                                                    $cvo->email = $thisuser->email;
                                                                    $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                    $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                                                    if($tempcheck=='1,1')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was above specified range at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                    }
                                                                    else if($tempcheck=='-1,0')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was below specified range at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                    }
                                                                    $cvo->subject = "Temperature Status";
                                                                    $cvo->phone = "";                                            
                                                                    $cvo->type = 0;
                                                                    $cvo->customerno = $thiscustomerno;                                            
                                                                    $cqm->InsertQ($cvo);                                                                       
                                                                }    
                                                            }
                                                            if(isset($thisuser->temp_sms) && $thisuser->temp_sms == 1)
                                                            {
                                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                                {
                                                                    $date = new Date();
                                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                    $cqm = new CommunicationQueueManager();
                                                                    $cvo = new VOCommunicationQueue();
                                                                    $cvo->phone = $thisuser->phone;
                                                                    $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                    $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                                    if($tempcheck=='1,1')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was above specified range at ".$hourminutes.". Powered by Elixia Tech.";
                                                                    }
                                                                    else if($tempcheck=='-1,0')
                                                                    {
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature[$thisdevice->analog4 C] was below specified range at ".$hourminutes.". Powered by Elixia Tech.";
                                                                    }
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
                        $dm->marktempon($thisdevice->vehicleid);
                    }    

                    else if($tempcheck == '0' && $thisdevice->temp_status == 1)
                    {
                        // Populate communication queue
                        $um = new UserManager();
                        $users = $um->getunfilteredusersforcustomer($thiscustomerno);
                        if(isset($users))
                        {
                            foreach($users as $thisuser)
                            {
                                $ums = new UserManager($thisuser->customerno);
                                $groups = $ums->get_groups_fromuser($thisuser->customerno, $thisuser->id);
                                $encodekey = sha1($thisuser->userkey);
                                $dates = new Date();
                                $hms = $dates->return_hour(date("H:i:s"), 0);
                            if(strtotime($thisuser->start_alert_time) < strtotime($thisuser->stop_alert_time))
                            {
                            if(strtotime($thisuser->start_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->stop_alert_time))
                              {
                                    if(isset($groups))
                                    {
                                        foreach($groups as $group)
                                        {
                                            $vehiclemanager = new VehicleManager($thiscustomerno);
                                            $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisdevice->vehicleid);    
                                            if(isset($vehicles)){
                                                                if(isset($thisuser->temp_email) && $thisuser->temp_email == 1)
                                                                {
                                                                    if(isset($thisuser->email) && $thisuser->email != "")
                                                                    {
                                                                        $date = new Date();
                                                                        $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                        $cqm = new CommunicationQueueManager();
                                                                        $cvo = new VOCommunicationQueue();
                                                                        $cvo->email = $thisuser->email;
                                                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                        $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature Normal at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                        $cvo->subject = "Temperature Status";
                                                                        $cvo->phone = "";
                                                                        $cvo->type = 0;
                                                                        $cvo->customerno = $thiscustomerno;                                            
                                                                        $cqm->InsertQ($cvo);                                                                       
                                                                    }    
                                                                }   
                                                                if(isset($thisuser->temp_sms) && $thisuser->temp_sms == 1)
                                                                {
                                                                    if(isset($thisuser->phone) && $thisuser->phone != "")
                                                                    {
                                                                        $date = new Date();
                                                                        $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                        $cqm = new CommunicationQueueManager();
                                                                        $cvo = new VOCommunicationQueue();
                                                                        $cvo->phone = $thisuser->phone;
                                                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                        $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                                        $cvo->message = $vehicle->vehicleno." - Temperature Normal at ".$hourminutes.". Powered by Elixia Tech.";
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
                                                                if(isset($thisuser->temp_email) && $thisuser->temp_email == 1)
                                                                {
                                                                    if(isset($thisuser->email) && $thisuser->email != "")
                                                                    {
                                                                        $date = new Date();
                                                                        $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                        $cqm = new CommunicationQueueManager();
                                                                        $cvo = new VOCommunicationQueue();
                                                                        $cvo->email = $thisuser->email;
                                                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                        $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature Normal at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                        $cvo->subject = "Temperature Status";
                                                                        $cvo->phone = "";
                                                                        $cvo->type = 0;
                                                                        $cvo->customerno = $thiscustomerno;                                            
                                                                        $cqm->InsertQ($cvo);                                                                       
                                                                    }    
                                                                }   
                                                                if(isset($thisuser->temp_sms) && $thisuser->temp_sms == 1)
                                                                {
                                                                    if(isset($thisuser->phone) && $thisuser->phone != "")
                                                                    {
                                                                        $date = new Date();
                                                                        $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                        $cqm = new CommunicationQueueManager();
                                                                        $cvo = new VOCommunicationQueue();
                                                                        $cvo->phone = $thisuser->phone;
                                                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                        $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                                        $cvo->message = $vehicle->vehicleno." - Temperature Normal at ".$hourminutes.". Powered by Elixia Tech.";
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
                            else if(strtotime($thisuser->start_alert_time) > strtotime($thisuser->stop_alert_time))
                            {
                              if(strtotime($thisuser->stop_alert_time) <= strtotime($hms) && strtotime($hms) <= strtotime($thisuser->start_alert_time))
                              {      
                                //echo 'Start Time is Greater Than Stop Time<br>not valid'.$thisuser->id.'_'.$thisuser->realname.'_'.$thiscustomerno.'<br><br>';
                              }
                              else
                              {
                                    if(isset($groups))
                                    {
                                        foreach($groups as $group)
                                        {
                                            $vehiclemanager = new VehicleManager($thiscustomerno);
                                            $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisdevice->vehicleid);    
                                            if(isset($vehicles)){
                                                                if(isset($thisuser->temp_email) && $thisuser->temp_email == 1)
                                                                {
                                                                    if(isset($thisuser->email) && $thisuser->email != "")
                                                                    {
                                                                        $date = new Date();
                                                                        $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                        $cqm = new CommunicationQueueManager();
                                                                        $cvo = new VOCommunicationQueue();
                                                                        $cvo->email = $thisuser->email;
                                                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                        $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature Normal at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                        $cvo->subject = "Temperature Status";
                                                                        $cvo->phone = "";
                                                                        $cvo->type = 0;
                                                                        $cvo->customerno = $thiscustomerno;                                            
                                                                        $cqm->InsertQ($cvo);                                                                       
                                                                    }    
                                                                }   
                                                                if(isset($thisuser->temp_sms) && $thisuser->temp_sms == 1)
                                                                {
                                                                    if(isset($thisuser->phone) && $thisuser->phone != "")
                                                                    {
                                                                        $date = new Date();
                                                                        $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                        $cqm = new CommunicationQueueManager();
                                                                        $cvo = new VOCommunicationQueue();
                                                                        $cvo->phone = $thisuser->phone;
                                                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                        $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                                        $cvo->message = $vehicle->vehicleno." - Temperature Normal at ".$hourminutes.". Powered by Elixia Tech.";
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
                                                                if(isset($thisuser->temp_email) && $thisuser->temp_email == 1)
                                                                {
                                                                    if(isset($thisuser->email) && $thisuser->email != "")
                                                                    {
                                                                        $date = new Date();
                                                                        $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                        $cqm = new CommunicationQueueManager();
                                                                        $cvo = new VOCommunicationQueue();
                                                                        $cvo->email = $thisuser->email;
                                                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                        $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                                                        $cvo->message = $vehicle->vehicleno." - Temperature Normal at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                                        $cvo->subject = "Temperature Status";
                                                                        $cvo->phone = "";
                                                                        $cvo->type = 0;
                                                                        $cvo->customerno = $thiscustomerno;                                            
                                                                        $cqm->InsertQ($cvo);                                                                       
                                                                    }    
                                                                }   
                                                                if(isset($thisuser->temp_sms) && $thisuser->temp_sms == 1)
                                                                {
                                                                    if(isset($thisuser->phone) && $thisuser->phone != "")
                                                                    {
                                                                        $date = new Date();
                                                                        $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                                        $cqm = new CommunicationQueueManager();
                                                                        $cvo = new VOCommunicationQueue();
                                                                        $cvo->phone = $thisuser->phone;
                                                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                                                        $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);

                                                                        $cvo->message = $vehicle->vehicleno." - Temperature Normal at ".$hourminutes.". Powered by Elixia Tech.";
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
                            $dm->marktempoff($thisdevice->vehicleid);
                       }                                        
                    }
                }
            }
        }
    }
}
function checktemp($temp,$max,$min)
{
    $temp = round((($temp-1150)/4.45),2);
    if($temp<$max && $temp>$min)
    {
        return '0';
    }
    else if($temp>$max || $temp==$max)
    {
        return '1,1';
    }
    elseif ($temp<$min)
    {
        return '-1,0';
    }
}
?>