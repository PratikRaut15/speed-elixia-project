<?php
include_once '../../../lib/system/Validator.php';
include_once '../../../lib/system/VersionedManager.php';
include_once '../../../lib/system/Sanitise.php';
include_once '../../../lib/system/Date.php';
include_once '../../../lib/model/VODevices.php';
include_once '../../../lib/model/VOServiceCall.php';
include_once '../../../lib/model/VORemark.php';
include_once '../../../lib/model/VOCommunicationQueue.php';
include_once '../../../lib/model/VOServicelist.php';
include_once '../../../lib/model/VOFeedback.php';

class servicemanage{
    
}

class feedbackmanage{
    
}

class ServiceCallManager extends VersionedManager
{
    public function ServiceCallManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function getdatafromdevicekey($devicekey) 
    {
        $deviceQuery = sprintf("SELECT * FROM `devices` INNER JOIN customer ON devices.customerno = customer.customerno INNER JOIN trackee ON trackee.trackeeid = devices.trackeeid where devices.devicekey = '%s' AND devices.customerno=%d",
            Sanitise::String($devicekey),    
            $this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->tname = $row['tname'];
                $device->deviceid = $row['deviceid'];
                $device->lastupdated = $row['lastupdated'];                
                $device->customerno = $row['customerno'];    
                $device->trackeeid = $row["trackeeid"];
                $device->itemdel = $row["itemdelivery"];
                $device->messaging = $row["messaging"];  
                $device->pushitems = $row["pushitems"];
                $device->pushmessages = $row["pushmessages"];
                $device->pushservice = $row["pushservice"];                
            }
            return $device;            
        }
        return null;        
    }                   
    
    public function getservices()
    {
        $services = Array();
        $Query = sprintf("SELECT *
                        FROM `servicelist` 
                        where customerno = %d AND isdeleted = 0",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $sl = new VOServicelist();
                $sl->servicelistid = $row["servicelistid"];
                $sl->servicename = $row["servicename"];
                $sl->price = $row["price"];
                $sl->expectedtime = $row["expectedtime"];
                $sl->customerno = $row["customerno"];                
                $services[] = $sl;
            }
            return $services;            
        }
        return null;        
    }

    public function pullservicelistids($serviceid)
    {
        $services = Array();
        $Query = sprintf("SELECT *
                        FROM `servicemanage` 
                        where servicecallid = %d AND customerno = %d AND isdeleted = 0",
                        Sanitise::Long($serviceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $sl = new VOServicelist();
                $sl->servicelistid = $row["servicelistid"];
                $services[] = $sl;
            }
            return $services;            
        }
        return null;        
    }
    
    
    public function updateexpectedendtime($serviceid, $newexpectedtime)
    {
        $Query = sprintf("SELECT timeslot_start
                        FROM `servicecall` 
                        where customerno = %d AND serviceid=%d",
            $this->_Customerno, Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $starttimeslot = $row['timeslot_start'];
            }
        }        
        $newendtimeslot = strtotime('+ '.$newexpectedtime.' minutes', strtotime($starttimeslot));
        $newendtimeslot = date("Y-m-d H:i:s",$newendtimeslot);
        $SQL = sprintf( "Update servicecall
                        Set `timeslot_end`='%s'
                        WHERE customerno = %d AND serviceid = %d",
                        Sanitise::DateTime($newendtimeslot),                
                        $this->_Customerno, Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($SQL);                                        
        
        // Expected End Time
        $Query = sprintf("SELECT starttime
                        FROM `servicecall` 
                        where customerno = %d AND serviceid=%d",
            $this->_Customerno, Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $starttime = $row['starttime'];
            }
        }        
        $newendtime = strtotime('+ '.$newexpectedtime.' minutes', strtotime($starttime));
        $newendtime = date("Y-m-d H:i:s",$newendtime);
        $SQL = sprintf( "Update servicecall
                        Set `expected_endtime`='%s'
                        WHERE customerno = %d AND serviceid = %d",
                        Sanitise::DateTime($newendtime),                
                        $this->_Customerno, Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($SQL);                                                        
    }

    public function setexpectedendtime($serviceid, $newexpectedtime)
    {
        // Expected End Time
        $Query = sprintf("SELECT starttime
                        FROM `servicecall` 
                        where customerno = %d AND serviceid=%d",
            $this->_Customerno, Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $starttime = $row['starttime'];
            }
        }        
        $newendtime = strtotime('+ '.$newexpectedtime.' minutes', strtotime($starttime));
        $newendtime = date("Y-m-d H:i:s",$newendtime);
        $SQL = sprintf( "Update servicecall
                        Set `expected_endtime`='%s'
                        WHERE customerno = %d AND serviceid = %d",
                        Sanitise::DateTime($newendtime),                
                        $this->_Customerno, Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($SQL);                                                                
    }
    
    public function pullexpectedtime($servicelistid)
    {
        $Query = sprintf("SELECT expectedtime
                        FROM `servicelist` 
                        where customerno = %d AND servicelistid=%d",
            $this->_Customerno, Sanitise::Long($servicelistid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                return $row['expectedtime'];
            }
        }
        return null;        
    }
        
    public function getopencalls($trackeeid) 
    {
        $calls = Array();
        $Query = sprintf("SELECT servicecall.clientname, servicecall.add1, servicecall.add2,
                        servicecall.phoneno, servicecall.userid, servicecall.serviceid, servicecall.contactperson,
                        servicecall.customerno, servicecall.createdon, servicecall.sigreqd, servicecall.trackingno,
                        servicecall.callextra1, servicecall.callextra2, servicecall.uf1, servicecall.uf2,
                        client.extra, servicecall.trackeeid, servicecall.totalbill, servicecall.timeslot_start, servicecall.timeslot_end,
                        servicecall.discount_code, servicecall.discount_amount
                        FROM `servicecall` 
                        INNER JOIN client ON client.clientid = servicecall.clientid
                        where servicecall.customerno = %d AND servicecall.trackeeid=%d AND servicecall.status < 4 AND servicecall.isdeleted = 0",
            $this->_Customerno,
            Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $call = new VOServiceCall();
                $call->serviceid = $row["serviceid"];
                $call->clientname = $row["clientname"];
                $call->contactperson = $row["contactperson"];
                $call->customerno = $row["customerno"];                
                $call->phoneno = $row["phoneno"];
                $call->add1 = $row["add1"];
                $call->add2 = $row["add2"];
                $call->createdon = $row["createdon"];                
                $call->sigreqd = $row["sigreqd"];                                
                $call->trackingno = $row["trackingno"];                                
                $call->extra1 = $row["callextra1"];                                
                $call->extra2 = $row["callextra2"];                                
                $call->uf1 = $row["uf1"];                                
                $call->uf2 = $row["uf2"];                                
                $call->cliextra = $row["extra"];
                $call->userid = $row['userid'];
                $call->trackeeid = $row['trackeeid'];
                $call->totalbill = $row['totalbill'];
                $starttime = date(" g:i a", strtotime($row['timeslot_start']));
                $call->startdate = date("F j", strtotime($row['timeslot_start']));                                
                $endtime = date(" g:i a", strtotime($row['timeslot_end']));                
                $call->preftime = "Between ".$starttime." and ".$endtime;
                $call->discountcode = $row["discount_code"];
                $call->discountamount = $row["discount_amount"];
                $calls[] = $call;
            }
            return $calls;            
        }
        return null;
    }         
    
    public function getfeedbackquestions() 
    {
        $fqs = Array();
        $Query = sprintf("SELECT *
                        FROM `feedbackquestions` 
                        where customerno = %d AND isdeleted = 0",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $fb = new VOFeedback();
                $fb->feedbackquestionid = $row["feedbackquestionid"];
                $fb->feedbackquestion = $row["feedbackquestion"];
                $fb->customerno = $row["customerno"];
                $fqs[] = $fb;
            }
            return $fqs;            
        }
        return null;
    }         
    
    public function getservicelist($serviceid) 
    {
        $calls = Array();
        $Query = sprintf("SELECT *
                        FROM `servicemanage` 
                        where customerno = %d AND servicecallid=%d AND isdeleted = 0",
            $this->_Customerno,
            Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $call = new servicemanage();
                $call->servicelistid = $row["servicelistid"];
                $call->manageid = $row["id"];                
                $calls[] = $call;
            }
            return $calls;            
        }
        return null;
    }             

    public function getfboptions($questionid) 
    {
        $fos = Array();
        $Query = sprintf("SELECT *
                        FROM `feedbackoption` 
                        where customerno = %d AND fquestionid=%d AND isdel = 0",
            $this->_Customerno,
            Sanitise::Long($questionid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $fo = new feedbackmanage();
                $fo->optionname = $row["optionname"];
                $fo->manageid = $row["feedbackoptionid"];                
                $fos[] = $fo;
            }
            return $fos;            
        }
        return null;
    }             
    
    public function checkpushservice($trackeeid) 
    {
        $customs = Array();
        $Query = sprintf("SELECT pushservice FROM `trackee` where trackeeid=%d AND customerno=%d",Sanitise::Long($trackeeid),$this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		

        if ($this->_databaseManager->get_rowCount() == 1) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row['pushservice'] == 1)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            return false;            
        }
        return false;
    }            

    public function updatepushservice($trackeeid)
    {
        $SQL = sprintf( "Update trackee
                        Set `pushservice`=0
                        WHERE customerno = %d AND trackeeid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                        
    }            
    
    public function getremarks() 
    {
        $remarks = Array();
        $Query = sprintf("SELECT *
                        FROM `remarks` 
                        where customerno = %d AND isdeleted = 0",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $remark = new VORemark();
                $remark->remarkid = $row["remarkid"];
                $remark->remarkname = $row["remarkname"];
                $remark->customerno = $row["customerno"];
                $remarks[] = $remark;
            }
            return $remarks;            
        }
        return null;
    }         

    public function checkpushremarks($trackeeid) 
    {
        $customs = Array();
        $Query = sprintf("SELECT pushremarks FROM `trackee` where trackeeid=%d AND customerno=%d",Sanitise::Long($trackeeid),$this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		

        if ($this->_databaseManager->get_rowCount() == 1) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row['pushremarks'] == 1)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            return false;            
        }
        return false;
    }            

    public function updatepushremarks($trackeeid)
    {
        $SQL = sprintf( "Update trackee
                        Set `pushremarks`=0
                        WHERE customerno = %d AND trackeeid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                        
    }                     
    
    public function setdepart($serviceid, $departtime)
    {
        // Update Service Call
        $SQL = sprintf( "Update servicecall
                        Set `departtime`='%s',
                        `status` = 2
                        WHERE customerno = %d AND serviceid = %d",
                        Sanitise::DateTime($departtime),
                        $this->_Customerno,                         
                        Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($SQL);     
        
        $Query = sprintf("SELECT servicecall.clientname, trackee.tname, servicecall.isemail, servicecall.issms, servicecall.email, servicecall.phoneno
                        FROM `servicecall`
                        INNER JOIN trackee ON trackee.trackeeid = servicecall.trackeeid
                        where servicecall.customerno = %d AND servicecall.serviceid=%d AND servicecall.isdeleted = 0",
            $this->_Customerno,
            Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $clientname = $row["clientname"];
                $tname = $row["tname"];            
                $smssend = $row["issms"];
                $emailsend = $row["isemail"];
                $email = $row["email"];
                $phone = $row["phoneno"];
            }
        }
        
        // Display notifications
        $message = "Depart: ".$tname." has departed for the service call generated for ".$clientname." at ". Sanitise::DateTime($departtime);
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`) VALUES
                        ( '%d','%s',%d,'%s',0)",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today));        
        $this->_databaseManager->executeQuery($SQL);

        // Send Notifications
        $queue = new VOCommunicationQueue();        
        $queue->email = $email;                    
        $queue->phone = $phone;
        
        // Send SMS
        if($smssend == 1)
        {
            $notifymessage = "";            
            $Query = sprintf("SELECT alertmsg FROM `alerts` where customerno = %d AND alertname = 'depart' AND alerttype = 'sms'",
                $this->_Customerno);
            $this->_databaseManager->executeQuery($Query);		

            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $alertmessagesms = $row["alertmsg"];
                }
            }
            
            $messagearray = explode("###", $alertmessagesms);
            if(isset($messagearray))
            {
                foreach($messagearray as $thisarray)
                {
                    if($thisarray == "Trackeename")
                    {
                        $notifymessage.=" ".$tname." ";
                    }
                    elseif($thisarray == "Clientname")
                    {
                        $notifymessage.=" ".$clientname." ";
                    }
                    else
                    {
                        $notifymessage.=$thisarray;
                    }
                }
            }
            
            $queue->type = 1;
            $queue->subject = "";
            if($this->_Customerno == 14)
            {
                $queue->message = $notifymessage;                                
            }
            else
            {
                $queue->message = $notifymessage." Powered by Elixia Tech.";                
            }
            $queue->customerno = $this->_Customerno;
            $this->Insert($queue);            
        }
        // Send Email
        if($emailsend == 1)
        {
            $notifymessage = "";                        
            $Query = sprintf("SELECT alertmsg, alertsubject FROM `alerts` where customerno = %d AND alertname = 'depart' AND alerttype = 'email'",
                $this->_Customerno);
            $this->_databaseManager->executeQuery($Query);		

            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $alertmessageemail = $row["alertmsg"];
                    $alertsubjectemail = $row["alertsubject"];
                }
            }
            
            $messagearray = explode("###", $alertmessageemail);
            if(isset($messagearray))
            {
                foreach($messagearray as $thisarray)
                {
                    if($thisarray == "Trackeename")
                    {
                        $notifymessage.=" ".$tname." ";
                    }
                    elseif($thisarray == "Clientname")
                    {
                        $notifymessage.=" ".$clientname." ";
                    }
                    else
                    {
                        $notifymessage.=$thisarray;
                    }
                }
            }
            $queue->type = 0;
            $queue->subject = $alertsubjectemail;
            $queue->message = $notifymessage." Powered by Elixia Tech.";            
            $queue->customerno = $this->_Customerno;            
            $this->Insert($queue);
        }        
    }               
    
    public function setwarning($serviceid, $timestamp, $type)
    {
        if($type == 1)
        {
            $SQL = sprintf( "Update servicecall
                            Set `warning1`='%s',
                            `status` = 6
                            WHERE customerno = %d AND serviceid = %d",
                            Sanitise::DateTime($timestamp),
                            $this->_Customerno,                         
                            Sanitise::Long($serviceid));
            $this->_databaseManager->executeQuery($SQL);                                                                            
            
            // Expected End Time
            $Query = sprintf("SELECT warning1
                            FROM `servicecall` 
                            where customerno = %d AND serviceid=%d",
                $this->_Customerno, Sanitise::Long($serviceid));
            $this->_databaseManager->executeQuery($Query);		
            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $starttime = $row['warning1'];
                }
            }        
            $newendtime = strtotime('+ 20 minutes', strtotime($starttime));
            $newendtime = date("Y-m-d H:i:s",$newendtime);
            $SQL = sprintf( "Update servicecall
                            Set `expected_endtime`='%s'
                            WHERE customerno = %d AND serviceid = %d",
                            Sanitise::DateTime($newendtime),                
                            $this->_Customerno, Sanitise::Long($serviceid));
            $this->_databaseManager->executeQuery($SQL);                                                                
            
        }
        else
        {
            $SQL = sprintf( "Update servicecall
                            Set `warning2`='%s',
                            `status` = 7, `timesdelay`= `timesdelay`+1
                            WHERE customerno = %d AND serviceid = %d",
                            Sanitise::DateTime($timestamp),
                            $this->_Customerno,                         
                            Sanitise::Long($serviceid));  
            $this->_databaseManager->executeQuery($SQL);   
            
            // Expected End Time
            $Query = sprintf("SELECT expected_endtime
                            FROM `servicecall` 
                            where customerno = %d AND serviceid=%d",
                $this->_Customerno, Sanitise::Long($serviceid));
            $this->_databaseManager->executeQuery($Query);		
            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $starttime = $row['expected_endtime'];
                }
            }        
            $newendtime = strtotime('+ 15 minutes', strtotime($starttime));
            $newendtime = date("Y-m-d H:i:s",$newendtime);
            $SQL = sprintf( "Update servicecall
                            Set `expected_endtime`='%s'
                            WHERE customerno = %d AND serviceid = %d",
                            Sanitise::DateTime($newendtime),                
                            $this->_Customerno, Sanitise::Long($serviceid));
            $this->_databaseManager->executeQuery($SQL);                                                                            
        }
        
        $Query = sprintf("SELECT servicecall.clientname, trackee.tname
                        FROM `servicecall`
                        INNER JOIN trackee ON trackee.trackeeid = servicecall.trackeeid
                        where servicecall.customerno = %d AND servicecall.serviceid=%d AND servicecall.isdeleted = 0",
            $this->_Customerno,
            Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $clientname = $row["clientname"];
                $tname = $row["tname"];            
            }
        }
        
        // Display notifications
        if($type == 1)
        {
            $message = "Warning: ".$tname." will complete the call at ".$clientname." in another 20 minutes";
        }
        else {
            $message = "Delay: ".$tname." will be delayed at ".$clientname." by 15 minutes";    
        }
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`) VALUES
                        ( '%d','%s',%d,'%s',0)",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today));        
        $this->_databaseManager->executeQuery($SQL);

    }               
    

    public function sendclosenotification($serviceid, $closedon)
    {
        $Query = sprintf("SELECT servicecall.clientname, trackee.tname, servicecall.isemail, servicecall.issms, servicecall.email, servicecall.phoneno
                        FROM `servicecall`
                        INNER JOIN trackee ON trackee.trackeeid = servicecall.trackeeid
                        where servicecall.customerno = %d AND servicecall.serviceid=%d AND servicecall.isdeleted = 0",
            $this->_Customerno,
            Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $clientname = $row["clientname"];
                $tname = $row["tname"];            
                $smssend = $row["issms"];
                $emailsend = $row["isemail"];
                $email = $row["email"];
                $phone = $row["phoneno"];
            }
        }
        
        // Display notifications
        $message = "Signature: ".$tname." has received a signature from ".$clientname." at ". Sanitise::DateTime($closedon);
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`) VALUES
                        ( '%d','%s',%d,'%s',0)",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today));        
        $this->_databaseManager->executeQuery($SQL);

        // Send Notifications
        $queue = new VOCommunicationQueue();        
        $queue->email = $email;                    
        $queue->phone = $phone;
        
        // Send SMS
        if($smssend == 1)
        {
            $notifymessage = "";            
            $Query = sprintf("SELECT alertmsg FROM `alerts` where customerno = %d AND alertname = 'thankyou' AND alerttype = 'sms'",
                $this->_Customerno);
            $this->_databaseManager->executeQuery($Query);		

            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $alertmessagesms = $row["alertmsg"];
                }
            }
            
            $messagearray = explode("###", $alertmessagesms);
            if(isset($messagearray))
            {
                foreach($messagearray as $thisarray)
                {
                    if($thisarray == "Trackeename")
                    {
                        $notifymessage.=" ".$tname." ";
                    }
                    elseif($thisarray == "Clientname")
                    {
                        $notifymessage.=" ".$clientname." ";
                    }
                    else
                    {
                        $notifymessage.=$thisarray;
                    }
                }
            }
            
            $queue->type = 1;
            $queue->subject = "";
            if($this->_Customerno == 14)
            {
                $queue->message = $notifymessage;                
            }
            else
            {
                $queue->message = $notifymessage." Powered by Elixia Tech.";                
            }
            $queue->customerno = $this->_Customerno;            
            $this->Insert($queue);            
        }
        // Send Email
        if($emailsend == 1)
        {
            $notifymessage = "";                        
            $Query = sprintf("SELECT alertmsg, alertsubject FROM `alerts` where customerno = %d AND alertname = 'thankyou' AND alerttype = 'email'",
                $this->_Customerno);
            $this->_databaseManager->executeQuery($Query);		

            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $alertmessageemail = $row["alertmsg"];
                    $alertsubjectemail = $row["alertsubject"];
                }
            }
            
            $messagearray = explode("###", $alertmessageemail);
            if(isset($messagearray))
            {
                foreach($messagearray as $thisarray)
                {
                    if($thisarray == "Trackeename")
                    {
                        $notifymessage.=" ".$tname." ";
                    }
                    elseif($thisarray == "Clientname")
                    {
                        $notifymessage.=" ".$clientname." ";
                    }
                    else
                    {
                        $notifymessage.=$thisarray;
                    }
                }
            }
            $queue->type = 0;
            $queue->subject = $alertsubjectemail;
            $queue->message = $notifymessage." Powered by Elixia Tech.";    
            $queue->customerno = $this->_Customerno;            
            $this->Insert($queue);
        }                
    }
    
    public function setstart($serviceid, $starttime)
    {
        $SQL = sprintf( "Update servicecall
                        Set `starttime`='%s',
                        `status` = 3
                        WHERE customerno = %d AND serviceid = %d",
                        Sanitise::DateTime($starttime),
                        $this->_Customerno,                         
                        Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($SQL);      
        
        $Query = sprintf("SELECT servicecall.clientname, trackee.tname
                        FROM `servicecall`
                        INNER JOIN trackee ON trackee.trackeeid = servicecall.trackeeid
                        where servicecall.customerno = %d AND servicecall.serviceid=%d AND servicecall.isdeleted = 0",
            $this->_Customerno,
            Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $clientname = $row["clientname"];
                $tname = $row["tname"];            
            }
        }
        
        // Display notifications
        $message = "Start: ".$tname." started the service call generated for ".$clientname." at ". Sanitise::DateTime($starttime);
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`) VALUES
                        ( '%d','%s',%d,'%s',0)",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today));        
        $this->_databaseManager->executeQuery($SQL);
        
    }                         

    public function setend($serviceid, $endtime)
    {
        $SQL = sprintf( "Update servicecall
                        Set `endtime`='%s',
                        `status` = 4
                        WHERE customerno = %d AND serviceid = %d",
                        Sanitise::DateTime($endtime),
                        $this->_Customerno,                         
                        Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($SQL);    
        
        $Query = sprintf("SELECT servicecall.clientname, trackee.tname
                        FROM `servicecall`
                        INNER JOIN trackee ON trackee.trackeeid = servicecall.trackeeid
                        where servicecall.customerno = %d AND servicecall.serviceid=%d AND servicecall.isdeleted = 0",
            $this->_Customerno,
            Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $clientname = $row["clientname"];
                $tname = $row["tname"];            
            }
        }
        
        // Display notifications
        $message = "End: ".$tname." completed the service call generated for ".$clientname." at ". Sanitise::DateTime($endtime);
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`) VALUES
                        ( '%d','%s',%d,'%s',0)",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today));        
        $this->_databaseManager->executeQuery($SQL);
        
    }                         
    
    public function updatepushfeedback($trackeeid)
    {
        $SQL = sprintf( "Update trackee
                        Set `pushfeedback`=0
                        WHERE customerno = %d AND trackeeid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                        
    }                             
    
    public function updatepushservices($trackeeid)
    {
        $SQL = sprintf( "Update trackee
                        Set `pushservicelist`=0
                        WHERE customerno = %d AND trackeeid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                        
    }                         
    
    public function setremark($remarkid, $serviceid)
    {
        $SQL = sprintf( "Update servicecall
                        Set `remarkid`=%d
                        WHERE customerno = %d AND serviceid = %d",
                        Sanitise::Long($remarkid),
                        $this->_Customerno,                         
                        Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($SQL);                                        
    } 
    
    public function modifycall($trackeeid, $serviceid, $remarkid)
    {
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "Update servicecall
                        Set `status`=5,
                        `closedon` = '%s',
                        `remarkid` = '%d'                        
                        WHERE serviceid = %d AND customerno = %d AND trackeeid = %d",
                        Sanitise::DateTime($today),
                        Sanitise::Long($remarkid),                
                        Sanitise::Long($serviceid),
                        $this->_Customerno,                         
                        Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                        
    }    
    
    public function pushfeedback($thisarray, $serviceid, $trackeeid)
    {
        $feedbackquery = sprintf("SELECT feedbackresultid FROM feedback_result WHERE trackeeid = %d AND customerno = %d AND serviceid = %d AND feedbackqid = %d", Sanitise::Long($trackeeid), $this->_Customerno, $serviceid, $thisarray->feedbackqid);
        $this->_databaseManager->executeQuery($feedbackquery);		
        if ($this->_databaseManager->get_rowCount() == 0) 
        {
            $SQL = sprintf( "INSERT INTO feedback_result
                            (`serviceid`, `feedbackoptionid`, `feedbackqid`, `trackeeid`, `customerno`) VALUES
                            ( '%d','%d','%d','%d','%d')",
            Sanitise::Long($serviceid),
            Sanitise::Long($thisarray->feedbackoptionid),
            Sanitise::Long($thisarray->feedbackqid),    
             Sanitise::Long($trackeeid),
            $this->_Customerno);        
            $this->_databaseManager->executeQuery($SQL);                                        
        }
    }
    
    public function callclosednotification($trackeeid, $serviceid)
    {
        $trackeeQuery = sprintf("SELECT tname FROM trackee WHERE trackeeid = %d AND customerno = %d", Sanitise::Long($trackeeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($trackeeQuery);		
		
        if ($this->_databaseManager->get_rowCount() == 1) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $trackeename = $row["tname"];
            }
        }

        $Query = sprintf("SELECT * FROM servicecall INNER JOIN client ON client.clientid = servicecall.clientid WHERE servicecall.serviceid = %d AND servicecall.customerno = %d", Sanitise::Long($serviceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() == 1) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $userid = $row["userid"];
                $trackingno = $row["trackingno"];
                $clientname = $row["clientname"];
                $closedon = $row["closedon"];
            }
        }    
        
        $subject = "Service Call Report";
        $message = "Closed by  <b>".$trackeename."</b><br/>";
        $message.= "Client: <b>".$clientname."</b><br/>";
        $message.= "Tracking No: <b>".$trackingno."</b><br/>";
        $message.= "Date: <b>".$closedon."</b><br/>";
        
        $today = date("Y-m-d H:i:s");        
        
        $SQLM = sprintf("INSERT INTO messages (subject, message, datecreated, senderid, type, customerno) VALUES ('%s', '%s', '%s', %d, 2, %d)", 
                GetSafeValueString($subject, "string"),
                GetSafeValueString($message, "string"),
                Sanitise::DateTime($today),
                GetSafeValueString($trackeeid, "long"),
                $this->_Customerno);
        $this->_databaseManager->executeQuery($SQLM);
        $messageid = @mysql_insert_id();                
        
        $SQL = sprintf("INSERT INTO messagemanage (customerno, userid, messageid, status, rectype) VALUES (%d, %d, %d, 0, 1)",
            $this->_Customerno,
            Sanitise::Long($userid),
            GetSafeValueString($messageid, "long"));
        $this->_databaseManager->executeQuery($SQL);                                                

        $Query = sprintf("SELECT userid, email, phone, call_email, call_sms FROM user WHERE userid=%d AND customerno=%d",
                Sanitise::Long($userid), $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row["call_email"] ==1 && isset($row["email"]) && $row["email"] != "")
                {
                    $send = new VOCommunicationQueue();
                    $send->type = 0;
                    $send->message = "<html><body>";
                    $send->message.= '<table style="border-color: #666;" cellpadding="10">';
                    $send->message.= "<tr style='background: #eee;'><td colspan='2'><strong>Closed by ".$trackeename."</strong> </td></tr>";
                    $send->message.= "<tr><td>Client</td><td><b>".$clientname."</b></td></tr>";
                    $send->message.= "<tr><td>Tracking No.</td><td><b>".$trackingno."</b></td></tr>";                    
                    $send->message.= "<tr><td>Date</td><td><b>".$closedon."</b></td></tr>";                                                            
                    $send->message.="<tr><td colspan='2'>Powered by Elixia Tech.</td></tr>";
                    $send->message.= '</table>';                    
                    $send->message.= "</body></html>";                    
                    $send->email = $row["email"];
                    $send->phone = $row["phone"];
                    $send->subject = "Service Call Report by ".$trackeename;     
                    $send->customerno = $this->_Customerno;
                    $this->Insert($send);
                }
                if($row["call_sms"] ==1 && isset($row["phone"]) && $row["phone"] != "")
                {
                    $send = new VOCommunicationQueue();
                    $send->type = 1;
                    $send->message = "Client: ".$clientname."\n";
                    $send->message.= "Closed by ".$trackeename." \n";                    
                    $send->message.= "Date: ".$closedon."\n";                    
                    $send->message.="Powered by Elixia Tech.";
                    $send->email = $row["email"];
                    $send->phone = $row["phone"];
                    $send->subject = "";       
                    $send->customerno = $this->_Customerno;                    
                    $this->Insert($send);
                }                
            }
        }        
    }            
    
    public function pushservices($thisarray, $trackeeid, $totalbill)
    {
        $today = date("Y-m-d H:i:s");        
        if($thisarray->id == 0)
        {
            $SQL = sprintf( "INSERT INTO servicemanage
                            (`servicelistid`, `servicecallid`, `isdeleted`, `customerno`, `iseditedby`, `iscreatedby`, `timestamp`,`trackeeid`) VALUES
                            ( '%d','%d','%d','%d',1,'%d','%s','%d')",
            Sanitise::String($thisarray->servicelistid),
            Sanitise::String($thisarray->serviceid),
            Sanitise::String($thisarray->isdeleted),    
            $this->_Customerno,
            Sanitise::Long($thisarray->createdby),
            Sanitise::String($today),
            Sanitise::Long($trackeeid));        
            $this->_databaseManager->executeQuery($SQL);            
            $thisarray->id = $this->_databaseManager->get_insertedId();            
        }
        else
        {
            if($thisarray->bytrackee == 1)
            {
                $isedited = 1;
            }
            else
            {
                $isedited = 0;
            }                        
            $SQL = sprintf( "Update servicemanage
                                Set `servicelistid`=%d,
                                `servicecallid`=%d,
                                `isdeleted`=%d,
                                `trackeeid`=%d,
                                `iseditedby`=%d,
                                `iscreatedby`=%d,                                
                                `timestamp`='%s'
                                WHERE customerno = %d AND id = %d",
                                Sanitise::Long($thisarray->servicelistid),
                                 Sanitise::Long($thisarray->serviceid),
                                 Sanitise::Long($thisarray->isdeleted),
                                 Sanitise::Long($trackeeid),
                                 Sanitise::Long($isedited),
                                 Sanitise::Long($thisarray->createdby),
                                 Sanitise::String($today),
                                $this->_Customerno,                         
                                Sanitise::Long($thisarray->id));
            $this->_databaseManager->executeQuery($SQL);                                                                
        }
        
        $SQL = sprintf( "Update servicecall
                        Set `totalbill`='%f'                       
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::Float($totalbill),
                        Sanitise::Long($thisarray->serviceid),                
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);  
        
        return $thisarray;
    }

    public function pushservicesdb($thisarray, $trackeeid)
    {
        $today = date("Y-m-d H:i:s");        
        if($thisarray->id == 0)
        {
            $SQL = sprintf( "INSERT INTO servicemanage
                            (`servicelistid`, `servicecallid`, `isdeleted`, `customerno`, `iseditedby`,`iscreatedby`, `timestamp`,`trackeeid`) VALUES
                            ( '%d','%d','%d','%d',1,'%s','%d')",
            Sanitise::String($thisarray->servicelistid),
            Sanitise::String($thisarray->serviceid),
            Sanitise::String($thisarray->isdeleted),    
            $this->_Customerno,
            Sanitise::Long($thisarray->createdby),                    
            Sanitise::String($today),
            Sanitise::Long($trackeeid));        
            $this->_databaseManager->executeQuery($SQL);            
        }
        else
        {
            if($thisarray->bytrackee == 1)
            {
                $isedited = 1;
            }
            else
            {
                $isedited = 0;
            }                                    
            $SQL = sprintf( "Update servicemanage
                            Set `servicelistid`=%d,
                            `servicecallid`=%d,
                            `isdeleted`=%d,
                            `trackeeid`=%d,
                            `iseditedby`=%d,
                            `iscreatedby`=%d,                                                            
                            `timestamp`='%s'
                            WHERE customerno = %d AND id = %d",
                            Sanitise::Long($thisarray->servicelistid),
                             Sanitise::Long($thisarray->serviceid),
                             Sanitise::Long($thisarray->isdeleted),
                             Sanitise::Long($trackeeid),
                             Sanitise::Long($isedited),   
                             Sanitise::Long($thisarray->createdby),                    
                             Sanitise::String($today),
                            $this->_Customerno,                         
                            Sanitise::Long($thisarray->id));
            $this->_databaseManager->executeQuery($SQL);                                                                
        }        
    }
    
    private function Insert($queue)
    {
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO communicationqueue
                        (`type`,`email`,`phone`,`subject`,`message`,`datecreated`,`customerno`) VALUES
                        ( '%d','%s','%s','%s','%s','%s','%d')",
        Sanitise::Long($queue->type),                
        Sanitise::String($queue->email),                
        Sanitise::String($queue->phone),
        Sanitise::String($queue->subject),
        Sanitise::String($queue->message),
        Sanitise::DateTime($today),
        Sanitise::Long($queue->customerno));        
        $this->_databaseManager->executeQuery($SQL);
    }                        
    
    public function updatecalls($serviceid, $closedon, $status, $starttime, $endtime, $departtime, $iscard)
    {
        $signature = $serviceid.".jpeg";
        $SQL = sprintf( "Update servicecall
                        Set `closedon`='%s',
                        `status`=%d,
                        `starttime`='%s',
                        `endtime`='%s',
                        `departtime`='%s',      
                        `iscard`=%d,                              
                        `signature`='%s'                        
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::DateTime($closedon),
                        Sanitise::Long($status),
                        Sanitise::DateTime($starttime),
                        Sanitise::DateTime($endtime),
                        Sanitise::DateTime($departtime),   
                        Sanitise::Long($iscard),                   
                        Sanitise::String($signature),
                        Sanitise::Long($serviceid),                
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);                
    }    

    public function updateendcall($serviceid, $status, $starttime, $endtime, $departtime)
    {
        $SQL = sprintf( "Update servicecall
                        Set `status`=%d,
                        `starttime`='%s',
                        `endtime`='%s',
                        `departtime`='%s'                       
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::Long($status),
                        Sanitise::DateTime($starttime),
                        Sanitise::DateTime($endtime),
                        Sanitise::DateTime($departtime),                
                        Sanitise::Long($serviceid),                
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);                
    }    
    
    public function updatestartcall($serviceid, $status, $starttime, $departtime)
    {
        $SQL = sprintf( "Update servicecall
                        Set `status`=%d,
                        `starttime`='%s',
                        `departtime`='%s'                       
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::Long($status),
                        Sanitise::DateTime($starttime),
                        Sanitise::DateTime($departtime),                
                        Sanitise::Long($serviceid),                
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);                
    }    
    
    public function updatedepartcall($serviceid, $status, $departtime)
    {
        $SQL = sprintf( "Update servicecall
                        Set `status`=%d,
                        `departtime`='%s'                       
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::Long($status),
                        Sanitise::DateTime($departtime),                
                        Sanitise::Long($serviceid),                
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);                
    }    
    
    public function updateviewcall($serviceid, $status)
    {
        $SQL = sprintf( "Update servicecall
                        Set `status`=%d
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::Long($status),
                        Sanitise::Long($serviceid),                
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);                
    }        
    
    public function setview($serviceid)
    {
        $SQL = sprintf( "Update servicecall
                        Set `status`=1                       
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::Long($serviceid),                
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        
        $Query = sprintf("SELECT client.clientname, trackee.tname
                        FROM `servicecall`
                        INNER JOIN trackee ON trackee.trackeeid = servicecall.trackeeid
                        INNER JOIN client ON client.clientid = servicecall.clientid
                        where servicecall.customerno = %d AND servicecall.serviceid=%d AND servicecall.isdeleted = 0",
            $this->_Customerno,
            Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $clientname = $row["clientname"];
                $tname = $row["tname"];                
            }
        }
        
        $message = "View: ".$tname." has viewed the service call generated for ".$clientname;
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`) VALUES
                        ( '%d','%s',%d,'%s')",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today));        
        $this->_databaseManager->executeQuery($SQL);
    }
    
    public function settotalbill($serviceid, $totalbill, $discount, $code)
    {
        $SQL = sprintf( "Update servicecall
                        Set `totalbill`='%f',
                        `discount_code`='%s',
                        `discount_amount`='%f'
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::Float($totalbill),                
                        Sanitise::String($code), 
                        Sanitise::Float($discount),                 
                        Sanitise::Long($serviceid), 
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }    
}