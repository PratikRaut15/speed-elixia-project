<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/DeviceManager.php';
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/CommunicationQueueManager.php';
require '../../lib/bo/UserManager.php';
require '../../lib/bo/VehicleManager.php';

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
                $count = $thisdevice->ignition_status;
                if(strtotime($thisdevice->lastupdated)>strtotime($thisdevice->ignition_last_check))
                {
                    if($thisdevice->ignition_last_status == $thisdevice->ignition && $count<5)
                    {
                        $count += 1;
                        $dm->markignitionstatus($thisdevice,$count);
                    }
                    else
                    {
                        $count = 1;
                        $dm->markignitionstatus($thisdevice,$count);
                    }
                }
                $dm->changelastigcheck($thisdevice);
                
                // Ignition
                if($thisdevice->ignition == "1" && $count == 5 && $thisdevice->ignition_email_status=="0")
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
                                        if(isset($thisuser->ignition_email) && $thisuser->ignition_email == 1)
                                        {
                                            if(isset($thisuser->email) && $thisuser->email != "")
                                            {
                                                $date = new Date();
                                                $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                $cqm = new CommunicationQueueManager();
                                                $cvo = new VOCommunicationQueue();
                                                $cvo->email = $thisuser->email;
                                                $cvo->message = $thisdevice->vehicleno." turned Ignition On at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                $cvo->subject = "Ignition Status";
                                                $cvo->phone = "";
                                                $cvo->type = 0;
                                                $cvo->customerno = $thiscustomerno;                                    
                                                $cqm->InsertQ($cvo);                                                                       
                                            }    
                                        }
                                        //SMS
                                        if(isset($thisuser->ignition_sms) && $thisuser->ignition_sms == 1)
                                        {
                                            if(isset($thisuser->phone) && $thisuser->phone != "")
                                            {
                                                $date = new Date();
                                                $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                $cqm = new CommunicationQueueManager();
                                                $cvo = new VOCommunicationQueue();
                                                $cvo->phone = $thisuser->phone;

                                                $cvo->message = $thisdevice->vehicleno." turned Ignition On at ".$hourminutes.". Powered by Elixia Tech.";
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
                                    // Email
                                    if(isset($thisuser->ignition_email) && $thisuser->ignition_email == 1)
                                    {
                                        if(isset($thisuser->email) && $thisuser->email != "")
                                        {
                                            $date = new Date();
                                            $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                            $cqm = new CommunicationQueueManager();
                                            $cvo = new VOCommunicationQueue();
                                            $cvo->email = $thisuser->email;
                                            $cvo->message = $thisdevice->vehicleno." turned Ignition On at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                            $cvo->subject = "Ignition Status";
                                            $cvo->phone = "";
                                            $cvo->type = 0;
                                            $cvo->customerno = $thiscustomerno;                                    
                                            $cqm->InsertQ($cvo);                                                                       
                                        }    
                                    }
                                    //SMS
                                    if(isset($thisuser->ignition_sms) && $thisuser->ignition_sms == 1)
                                    {
                                        if(isset($thisuser->phone) && $thisuser->phone != "")
                                        {
                                            $date = new Date();
                                            $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                            $cqm = new CommunicationQueueManager();
                                            $cvo = new VOCommunicationQueue();
                                            $cvo->phone = $thisuser->phone;

                                            $cvo->message = $thisdevice->vehicleno." turned Ignition On at ".$hourminutes.". Powered by Elixia Tech.";
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
                                        if(isset($thisuser->ignition_email) && $thisuser->ignition_email == 1)
                                        {
                                            if(isset($thisuser->email) && $thisuser->email != "")
                                            {
                                                $date = new Date();
                                                $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                $cqm = new CommunicationQueueManager();
                                                $cvo = new VOCommunicationQueue();
                                                $cvo->email = $thisuser->email;
                                                $cvo->message = $thisdevice->vehicleno." turned Ignition On at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                $cvo->subject = "Ignition Status";
                                                $cvo->phone = "";
                                                $cvo->type = 0;
                                                $cvo->customerno = $thiscustomerno;                                    
                                                $cqm->InsertQ($cvo);                                                                       
                                            }    
                                        }
                                        //SMS
                                        if(isset($thisuser->ignition_sms) && $thisuser->ignition_sms == 1)
                                        {
                                            if(isset($thisuser->phone) && $thisuser->phone != "")
                                            {
                                                $date = new Date();
                                                $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                $cqm = new CommunicationQueueManager();
                                                $cvo = new VOCommunicationQueue();
                                                $cvo->phone = $thisuser->phone;

                                                $cvo->message = $thisdevice->vehicleno." turned Ignition On at ".$hourminutes.". Powered by Elixia Tech.";
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
                                    // Email
                                    if(isset($thisuser->ignition_email) && $thisuser->ignition_email == 1)
                                    {
                                        if(isset($thisuser->email) && $thisuser->email != "")
                                        {
                                            $date = new Date();
                                            $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                            $cqm = new CommunicationQueueManager();
                                            $cvo = new VOCommunicationQueue();
                                            $cvo->email = $thisuser->email;
                                            $cvo->message = $thisdevice->vehicleno." turned Ignition On at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                            $cvo->subject = "Ignition Status";
                                            $cvo->phone = "";
                                            $cvo->type = 0;
                                            $cvo->customerno = $thiscustomerno;                                    
                                            $cqm->InsertQ($cvo);                                                                       
                                        }    
                                    }
                                    //SMS
                                    if(isset($thisuser->ignition_sms) && $thisuser->ignition_sms == 1)
                                    {
                                        if(isset($thisuser->phone) && $thisuser->phone != "")
                                        {
                                            $date = new Date();
                                            $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                            $cqm = new CommunicationQueueManager();
                                            $cvo = new VOCommunicationQueue();
                                            $cvo->phone = $thisuser->phone;

                                            $cvo->message = $thisdevice->vehicleno." turned Ignition On at ".$hourminutes.". Powered by Elixia Tech.";
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
                    $dm->markignitionon($thisdevice->vehicleid);
                }    
                else if($thisdevice->ignition == "0" && $count == 5 && $thisdevice->ignition_email_status=="1")
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
                                            // Email
                                            if(isset($thisuser->ignition_email) && $thisuser->ignition_email == 1)
                                            {
                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                {
                                                    $date = new Date();
                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->email = $thisuser->email;
                                                    $cvo->message = $thisdevice->vehicleno." turned Ignition Off at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                    $cvo->subject = "Ignition Status";
                                                    $cvo->phone = "";
                                                    $cvo->type = 0;
                                                    $cvo->customerno = $thiscustomerno;                                    
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                            // SMS
                                            if(isset($thisuser->ignition_sms) && $thisuser->ignition_sms == 1)
                                            {
                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                {
                                                    $date = new Date();
                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->phone = $thisuser->phone;

                                                    $cvo->message = $thisdevice->vehicleno." turned Ignition Off at ".$hourminutes.". Powered by Elixia Tech.";
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
                                            // Email
                                            if(isset($thisuser->ignition_email) && $thisuser->ignition_email == 1)
                                            {
                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                {
                                                    $date = new Date();
                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->email = $thisuser->email;
                                                    $cvo->message = $thisdevice->vehicleno." turned Ignition Off at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                    $cvo->subject = "Ignition Status";
                                                    $cvo->phone = "";
                                                    $cvo->type = 0;
                                                    $cvo->customerno = $thiscustomerno;                                    
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                            // SMS
                                            if(isset($thisuser->ignition_sms) && $thisuser->ignition_sms == 1)
                                            {
                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                {
                                                    $date = new Date();
                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->phone = $thisuser->phone;

                                                    $cvo->message = $thisdevice->vehicleno." turned Ignition Off at ".$hourminutes.". Powered by Elixia Tech.";
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
                                            // Email
                                            if(isset($thisuser->ignition_email) && $thisuser->ignition_email == 1)
                                            {
                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                {
                                                    $date = new Date();
                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->email = $thisuser->email;
                                                    $cvo->message = $thisdevice->vehicleno." turned Ignition Off at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                    $cvo->subject = "Ignition Status";
                                                    $cvo->phone = "";
                                                    $cvo->type = 0;
                                                    $cvo->customerno = $thiscustomerno;                                    
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                            // SMS
                                            if(isset($thisuser->ignition_sms) && $thisuser->ignition_sms == 1)
                                            {
                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                {
                                                    $date = new Date();
                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->phone = $thisuser->phone;

                                                    $cvo->message = $thisdevice->vehicleno." turned Ignition Off at ".$hourminutes.". Powered by Elixia Tech.";
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
                                            // Email
                                            if(isset($thisuser->ignition_email) && $thisuser->ignition_email == 1)
                                            {
                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                {
                                                    $date = new Date();
                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->email = $thisuser->email;
                                                    $cvo->message = $thisdevice->vehicleno." turned Ignition Off at ".$hourminutes.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                    $cvo->subject = "Ignition Status";
                                                    $cvo->phone = "";
                                                    $cvo->type = 0;
                                                    $cvo->customerno = $thiscustomerno;                                    
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                            // SMS
                                            if(isset($thisuser->ignition_sms) && $thisuser->ignition_sms == 1)
                                            {
                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                {
                                                    $date = new Date();
                                                    $hourminutes = $date->return_hours($thisdevice->lastupdated);
                                                    $cqm = new CommunicationQueueManager();
                                                    $cvo = new VOCommunicationQueue();
                                                    $cvo->phone = $thisuser->phone;

                                                    $cvo->message = $thisdevice->vehicleno." turned Ignition Off at ".$hourminutes.". Powered by Elixia Tech.";
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
                    $dm->markignitionoff($thisdevice->vehicleid);
                }                                        
            }
        }
    }
}

?>
