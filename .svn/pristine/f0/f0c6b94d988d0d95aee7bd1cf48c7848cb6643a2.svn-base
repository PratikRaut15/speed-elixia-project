<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/bo/CommunicationQueueManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/UserManager.php';

include_once 'files/calculate_timediff.php';
include_once 'files/send_alerts.php';

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
                if($thisdevice->acsensor == 1)
                {
                    if($thisdevice->status!='H' || $thisdevice->status!='F')
                    {   
                        // AC Sensor
                        if($thisdevice->aci_status == 0)
                        {
                            $ac_info = $dm->getacinfo($thisdevice->vehicleid);
                            
                            if($ac_info==NULL)
                            {
                                $dm->addacinfo($thisdevice);
                            }
                            
                            else if($thisdevice->ignition==0 && $ac_info->last_ignition==0)
                            {
                                if($ac_info->firstcheck=='0000-00-00 00:00:00')
                                {
                                    $dm->updateacinfo($thisdevice->lastupdated,$thisdevice->ignition,$thisdevice->deviceid);
                                }
                                else 
                                {
                                    if(diff($thisdevice->lastupdated, $ac_info->firstcheck)>$ac_info->aci_time)
                                    {
                                        $message = "AC has been ON since $ac_info->aci_time minutes while ignition was off";
                                        $um = new UserManager();
                                        $users = $um->getunfilteredusersforcustomer($thiscustomerno);
                                
                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                        $vehicle = $vehiclemanager->get_vehicle_with_driver($thisdevice->vehicleid);
                                    
                                        if(isset($users))
                                        {
                                            foreach($users as $thisuser)
                                            {
                                            $ums = new UserManager($thisuser->customerno);
                                            $groups = $ums->get_groups_fromuser($thisuser->customerno, $thisuser->id);
                                                if(isset($groups))
                                                {
                                                    foreach($groups as $group)
                                                    {
                                                        $vehiclemanager = new VehicleManager($thiscustomerno);
                                                        $vehicles = $vehiclemanager->get_groupedvehicle_for_cron($group->groupid,$thisdevice->vehicleid);    
                                                        if(isset($vehicles)){
                                                                            if(isset($thisuser->aci_email) && $thisuser->aci_email == 1)
                                                                            {
                                                                                if(isset($thisuser->email) && $thisuser->email != "")
                                                                                {
                                                                                    sendemail($thisuser->email,$thiscustomerno,$message,$vehicle,'AC Status',$thiscustomerno);

                                                                                }
                                                                            }
                                                                            if(isset($thisuser->ac_sms) && $thisuser->ac_sms == 1)
                                                                            {
                                                                                if(isset($thisuser->phone) && $thisuser->phone != "")
                                                                                {
                                                                                    sendmessage($thisuser->phone,$thiscustomerno,$message,$vehicle,$thiscustomerno);
                                                                                }
                                                                            }
                                                        }
                                                    }
                                                }
                                            else{
                                                    if(isset($thisuser->aci_email) && $thisuser->aci_email == 1)
                                                    {
                                                        if(isset($thisuser->email) && $thisuser->email != "")
                                                        {
                                                            sendemail($thisuser->email,$thiscustomerno,$message,$vehicle,'AC Status',$thiscustomerno);

                                                        }
                                                    }
                                                    if(isset($thisuser->ac_sms) && $thisuser->ac_sms == 1)
                                                    {
                                                        if(isset($thisuser->phone) && $thisuser->phone != "")
                                                        {
                                                            sendmessage($thisuser->phone,$thiscustomerno,$message,$vehicle,$thiscustomerno);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        $dm->updateacistatus($thisdevice->deviceid,1);
                                    }
                                }
                            }
                            else
                            {
                                $dm->updateacinfo($thisdevice->lastupdated,$thisdevice->ignition,$thisdevice->deviceid);
                                $dm->updateacistatus($thisdevice->deviceid,0);
                            }
                        }
                    
                        if($thisdevice->aci_status==1 && $thisdevice->ignition==1)
                        {
                            $dm->updateacistatus($thisdevice->deviceid,0);
                        }
                    }
                }
            }
        }
    }
}