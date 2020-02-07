<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/DeviceManager.php';
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/CommunicationQueueManager.php';
require '../../lib/bo/VehicleManager.php';
require '../../lib/bo/UserManager.php';
include_once '../../lib/bo/GeoCoder.php';

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
                $location = location($thisdevice->devicelat, $thisdevice->devicelong);               
                // OverSpeeding
                if($thisdevice->powercut == 0 && $thisdevice->powercutstatus == 0)
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
                                                if(isset($thisuser->power_email) && $thisuser->power_email == 1)
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
                                                        $cvo->message = $vehicle->vehicleno." underwent power cut at ".$hourminutes.".<br/>Location: ".$location.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                        $cvo->subject = "Power Cut Status";
                                                        $cvo->phone = "";
                                                        $cvo->type = 0;
                                                        $cvo->customerno = $thiscustomerno;                                    
                                                        $cqm->InsertQ($cvo);                                                                       
                                                    }    
                                                }
                                                if(isset($thisuser->power_sms) && $thisuser->power_sms == 1)
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

                                                        $cvo->message = $vehicle->vehicleno." underwent power cut at ".$hourminutes.". Powered by Elixia Tech.";
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
                                    if(isset($thisuser->power_email) && $thisuser->power_email == 1)
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
                                            $cvo->message = $vehicle->vehicleno." underwent power cut at ".$hourminutes.".<br/>Location: ".$location.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                            $cvo->subject = "Power Cut Status";
                                            $cvo->phone = "";
                                            $cvo->type = 0;
                                            $cvo->customerno = $thiscustomerno;                                    
                                            $cqm->InsertQ($cvo);                                                                       
                                        }    
                                    }
                                    if(isset($thisuser->power_sms) && $thisuser->power_sms == 1)
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

                                            $cvo->message = $vehicle->vehicleno." underwent power cut at ".$hourminutes.". Powered by Elixia Tech.";
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
                                                if(isset($thisuser->power_email) && $thisuser->power_email == 1)
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
                                                        $cvo->message = $vehicle->vehicleno." underwent power cut at ".$hourminutes.".<br/>Location: ".$location.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                        $cvo->subject = "Power Cut Status";
                                                        $cvo->phone = "";
                                                        $cvo->type = 0;
                                                        $cvo->customerno = $thiscustomerno;                                    
                                                        $cqm->InsertQ($cvo);                                                                       
                                                    }    
                                                }
                                                if(isset($thisuser->power_sms) && $thisuser->power_sms == 1)
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

                                                        $cvo->message = $vehicle->vehicleno." underwent power cut at ".$hourminutes.". Powered by Elixia Tech.";
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
                                    if(isset($thisuser->power_email) && $thisuser->power_email == 1)
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
                                            $cvo->message = $vehicle->vehicleno." underwent power cut at ".$hourminutes.".<br/>Location: ".$location.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                            $cvo->subject = "Power Cut Status";
                                            $cvo->phone = "";
                                            $cvo->type = 0;
                                            $cvo->customerno = $thiscustomerno;                                    
                                            $cqm->InsertQ($cvo);                                                                       
                                        }    
                                    }
                                    if(isset($thisuser->power_sms) && $thisuser->power_sms == 1)
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

                                            $cvo->message = $vehicle->vehicleno." underwent power cut at ".$hourminutes.". Powered by Elixia Tech.";
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
                    $dm->markpowercut($thisdevice->vehicleid);
                }    

                else if($thisdevice->powercut == 1 && $thisdevice->powercutstatus == 1)
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
                                            if(isset($thisuser->power_email) && $thisuser->power_email == 1)
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
                                                    $cvo->message = $vehicle->vehicleno." regained power at ".$hourminutes.".<br/>Location: ".$location.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                    $cvo->subject = "Power Cut Status";
                                                    $cvo->phone = "";
                                                    $cvo->type = 0;
                                                    $cvo->customerno = $thiscustomerno;                                    
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                            if(isset($thisuser->power_sms) && $thisuser->power_sms == 1)
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

                                                    $cvo->message = $vehicle->vehicleno." regained power at ".$hourminutes.". Powered by Elixia Tech.";
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
                                if(isset($thisuser->power_email) && $thisuser->power_email == 1)
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
                                                    $cvo->message = $vehicle->vehicleno." regained power at ".$hourminutes.".<br/>Location: ".$location.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                    $cvo->subject = "Power Cut Status";
                                                    $cvo->phone = "";
                                                    $cvo->type = 0;
                                                    $cvo->customerno = $thiscustomerno;                                    
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                            if(isset($thisuser->power_sms) && $thisuser->power_sms == 1)
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

                                                    $cvo->message = $vehicle->vehicleno." regained power at ".$hourminutes.". Powered by Elixia Tech.";
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
                                            if(isset($thisuser->power_email) && $thisuser->power_email == 1)
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
                                                    $cvo->message = $vehicle->vehicleno." regained power at ".$hourminutes.".<br/>Location: ".$location.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                    $cvo->subject = "Power Cut Status";
                                                    $cvo->phone = "";
                                                    $cvo->type = 0;
                                                    $cvo->customerno = $thiscustomerno;                                    
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                            if(isset($thisuser->power_sms) && $thisuser->power_sms == 1)
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

                                                    $cvo->message = $vehicle->vehicleno." regained power at ".$hourminutes.". Powered by Elixia Tech.";
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
                                if(isset($thisuser->power_email) && $thisuser->power_email == 1)
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
                                                    $cvo->message = $vehicle->vehicleno." regained power at ".$hourminutes.".<br/>Location: ".$location.".<br/> Powered by Elixia Tech.<br/><br/><br/> <font size='smaller'>To unsubscribe, please uncheck your alerts <a href='http://www.elixiatech.com/speed/modules/api/?action=login_userkey_unsub&key=$encodekey'>here</a> or call us on 25137470 / 71.</font>";
                                                    $cvo->subject = "Power Cut Status";
                                                    $cvo->phone = "";
                                                    $cvo->type = 0;
                                                    $cvo->customerno = $thiscustomerno;                                    
                                                    $cqm->InsertQ($cvo);                                                                       
                                                }    
                                            }
                                            if(isset($thisuser->power_sms) && $thisuser->power_sms == 1)
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

                                                    $cvo->message = $vehicle->vehicleno." regained power at ".$hourminutes.". Powered by Elixia Tech.";
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
                    $dm->markpowerin($thisdevice->vehicleid);
                }                                        
            }
        }
    }
}

function location($lat,$long)
{
    $address = NULL;
    if($lat !='0' && $long!='0')
    {
        if($_SESSION['customerno']==33 || $_SESSION['customerno']==43)
        {
            $API = "http://www.elixiatech.com/location.php?lat=".$lat."&long=".$long."";
            $location = json_decode(file_get_contents("$API&sensor=false"));
            @$address = "Near ".$location->results[0]->formatted_address;
            if($location->status == "OVER_QUERY_LIMIT")
            {
                $API = "http://www.manasprojects.com/location.php?lat=".$lat."&long=".$long."";
                $location = json_decode(file_get_contents("$API&sensor=false"));
                @$address = "Near ".$location->results[0]->formatted_address;
                if($location->results[0]->formatted_address==""){
					$GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
					$address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);
				}
            }
        }
        else
        {
            $GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
            $address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);
        }
    }
    return $address;
}

?>
