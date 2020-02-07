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

class feedbackresult{
    
}

class json_disc{
    
}

class ServiceCallV2Manager extends VersionedManager
{
    public function ServiceCallV2Manager($customerno)
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
         // chaning notification
        
        $Query = sprintf("SELECT client.clientname, trackee.tname,trackee.branchid
                           FROM `servicecall`
                           INNER JOIN trackee ON trackee.trackeeid = servicecall.trackeeid
                           INNER JOIN client ON client.clientid = servicecall.clientid
                           where servicecall.serviceid=%d AND servicecall.isdeleted = 0",
          Sanitise::Long($serviceid));
           $this->_databaseManager->executeQuery($Query);		

           if ($this->_databaseManager->get_rowCount() > 0) 
           {
               while ($row = $this->_databaseManager->get_nextRow())            
               {
                   $clientname = $row["clientname"];
                   $tname = $row["tname"];
                   $tbranchid = $row["branchid"];
               }
           }
        $thisarray->serviceid;
        $message = $tname." has changed the call for ".$clientname;
       $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                       (`customerno`,`message`,`isnotified`,`timestamp`,`status`,`isshown`,`branchid`) VALUES
                       ( '%d','%s',%d,'%s',8,0,%d)",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today),$tbranchid);        
        $this->_databaseManager->executeQuery($SQL);   
        // chaning notification
       
        
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
                        servicecall.discount_code, servicecall.discount_amount, servicecall.status 
                        FROM `servicecall` 
                        INNER JOIN client ON client.clientid = servicecall.clientid 
                        where servicecall.customerno = %d AND servicecall.trackeeid=%d AND servicecall.status IN (0,1,2,3,9) AND servicecall.cancelled_view = 0",
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
                $call->status = $row["status"];
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
        
        $Query = sprintf("SELECT servicecall.clientname, trackee.tname,trackee.branchid, servicecall.isemail, servicecall.issms, servicecall.email, servicecall.phoneno, servicecall.totalbill, servicecall.discount_amount 
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
                $totalbill = $row["totalbill"]-$row["discount_amount"];
                $discount = $row["discount_amount"];   
                $tbranchid = $row["branchid"];   
            }
        }
        
        $services = "";
        $Query = sprintf("SELECT servicelist.servicename FROM servicemanage 
            INNER JOIN servicelist ON servicelist.servicelistid = servicemanage.servicelistid WHERE servicecallid = %d", Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($services == "")
                {
                    $services.=$row["servicename"];
                }
                else
                {
                    $services.=", ".$row["servicename"];                    
                }
            }
        }
        
        // Display notifications
        $message = $tname." has departed to ".$clientname." at ". Sanitise::DateTime($departtime);
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`,`status`,`branchid`) VALUES
                        ( '%d','%s',%d,'%s',0,2,%d)",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today),$tbranchid);        
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
                    elseif($thisarray == "Finalbill")
                    {
                        $notifymessage.=" ".$totalbill." ";
                    }
                    elseif($thisarray == "Discount")
                    {
                        $notifymessage.=" ".$discount." ";
                    }                    
                    elseif($thisarray == "Services")
                    {
                        $notifymessage.=" ".$services." ";
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
                    elseif($thisarray == "Finalbill")
                    {
                        $notifymessage.=" ".$totalbill." ";
                    }
                    elseif($thisarray == "Discount")
                    {
                        $notifymessage.=" ".$discount." ";
                    }                    
                    elseif($thisarray == "Services")
                    {
                        $notifymessage.=" ".$services." ";
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
        
        $Query = sprintf("SELECT servicecall.clientname, trackee.tname,trackee.branchid
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
                $tbranchid = $row["branchid"];  
            }
        }
        $status = 0;
        
        // Display notifications
        if($type == 1)
        {
            $message = $tname." will complete the call at ".$clientname." in 20 mins";
            $status = 6;            
        }
        else {
            $message = $tname." will be delayed at ".$clientname." by 15 mins";    
            $status = 7;                        
        }
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`,`status`,`branchid`) VALUES
                        ( '%d','%s',%d,'%s',0,%d,%d)",
        $this->_Customerno, $message,0, Sanitise::DateTime($today), $status, $tbranchid);        
        $this->_databaseManager->executeQuery($SQL);

    }               
    

    public function sendclosenotification($serviceid, $closedon)
    {
        $Query = sprintf("SELECT servicecall.clientname, trackee.tname,trackee.branchid, servicecall.isemail, servicecall.issms, servicecall.email, servicecall.phoneno,
                        servicecall.totalbill, servicecall.discount_amount
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
                $tbranchid = $row["branchid"];
                $smssend = $row["issms"];
                $emailsend = $row["isemail"];
                $email = $row["email"];
                $phone = $row["phoneno"];
                $totalbill = $row["totalbill"]-$row["discount_amount"];
                $discount = $row["discount_amount"];                                
            }
        }
        
        // Display notifications
        $message = $tname." has closed the call for ".$clientname." at ". Sanitise::DateTime($closedon);
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                            (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`,`status`,`branchid`) VALUES
                        ( '%d','%s',%d,'%s',0,5,%d)",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today),$tbranchid);        
        $this->_databaseManager->executeQuery($SQL);

        // Send Notifications
        $queue = new VOCommunicationQueue();        
        $queue->email = $email;                    
        $queue->phone = $phone;
        
        $services = "";
        $Query = sprintf("SELECT servicelist.servicename FROM servicemanage 
            INNER JOIN servicelist ON servicelist.servicelistid = servicemanage.servicelistid WHERE servicecallid = %d", Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($services == "")
                {
                    $services.=$row["servicename"];
                }
                else
                {
                    $services.=", ".$row["servicename"];                    
                }
            }
        }
        
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
                    elseif($thisarray == "Finalbill")
                    {
                        $notifymessage.=" ".$totalbill." ";
                    }
                    elseif($thisarray == "Discount")
                    {
                        $notifymessage.=" ".$discount." ";
                    }                    
                    elseif($thisarray == "Services")
                    {
                        $notifymessage.=" ".$services." ";
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

            $feedbackresult = Array();
            $Query = sprintf("SELECT fq.feedbackquestion, fo.optionname FROM `feedback_result` fr
                INNER JOIN feedbackoption fo ON fo.feedbackoptionid = fr.feedbackoptionid
                INNER JOIN feedbackquestions fq ON fq.feedbackquestionid = fr.feedbackqid
                where fr.customerno = %d AND fr.serviceid = %d",
                $this->_Customerno, Sanitise::Long($serviceid));
            $this->_databaseManager->executeQuery($Query);		

            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $feedback = new feedbackresult();
                    $feedback->question = $row["feedbackquestion"];
                    $feedback->answer = $row["optionname"];
                    $feedbackresult[] = $feedback;
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
                    elseif($thisarray == "Finalbill")
                    {
                        $notifymessage.=" ".$totalbill." ";
                    }
                    elseif($thisarray == "Discount")
                    {
                        $notifymessage.=" ".$discount." ";
                    }                    
                    elseif($thisarray == "Services")
                    {
                        $notifymessage.=" ".$services." ";
                    }   
                    elseif($thisarray == "Feedback")
                    {
                        $notifymessage.="<table border='1'><tr><th>Feedback Question</th><th>Feedback Answer</th></tr>";
                        foreach($feedbackresult as $thisfeedback)
                        {
                            $notifymessage.="<tr><td>".$thisfeedback->question."</td><td>".$thisfeedback->answer."</td></tr>";                                                   
                        }
                        $notifymessage.="</table><br/>";
                    }                                                                                                    
                    else
                    {
                        $notifymessage.=$thisarray;
                    }
                }
            }
            $queue->type = 0;
            $queue->subject = $alertsubjectemail;
            $queue->message = $notifymessage."<br/><br/> Powered by Elixia Tech.";    
            $queue->customerno = $this->_Customerno;            
            $this->Insert($queue);
        }                
    }
    
    public function setpanic($panictime, $trackeeid)
    {
        $Query = sprintf("SELECT *
                        FROM `alerts`
                        where customerno = %d AND alertname='panic'",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $message = $row["alertmsg"];
                $phone = $row["alertsubject"];                
            }
        }          

        $Query = sprintf("SELECT tname,branchid
                        FROM `trackee`
                        where trackeeid = %d AND customerno = %d",
        Sanitise::Long($trackeeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $tname = $row["tname"];
                $tbranchid = $row["branchid"];                
            }
        }          
        
        $notifymessage = "";                        

        $messagearray = explode("###", $message);
        if(isset($messagearray))
        {
            foreach($messagearray as $thisarray)
            {
                if($thisarray == "Trackeename")
                {
                    $notifymessage.=" ".$tname." ";
                }
                else
                {
                    $notifymessage.=$thisarray;
                }
            }
        }
        
        // Notifications
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`,`status`,`branchid`) VALUES
                        ( '%d','%s',%d,'%s',0,10,'%d')",
        $this->_Customerno, $notifymessage,0,  Sanitise::DateTime($today),$tbranchid);        
        $this->_databaseManager->executeQuery($SQL);                
        
        // Communication Queue
        $queue = new VOCommunicationQueue();        
        $queue->email = "";                    
        $queue->phone = $phone;
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
        
        $Query = sprintf("SELECT servicecall.clientname, trackee.tname,trackee.branchid
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
                $tbranchid = $row["branchid"];
            }
        }
        
        // Display notifications
        $message = $tname." started the call for ".$clientname." at ". Sanitise::DateTime($starttime);
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`,`status`,`branchid`) VALUES
                        ( '%d','%s',%d,'%s',0,3,%d)",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today),$tbranchid);        
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
        
        $Query = sprintf("SELECT servicecall.clientname, trackee.tname,trackee.branchid
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
                 $tbranchid = $row["branchid"];
            }
        }
        
        // Display notifications
        $message = $tname." ended the call for ".$clientname." at ". Sanitise::DateTime($endtime);
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`timestamp`,`isshown`,`status`,`branchid`) VALUES
                        ( '%d','%s',%d,'%s',0,4,%d)",
        $this->_Customerno, $message,0,  Sanitise::DateTime($today),$tbranchid);        
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
    
    public function pushservices($thisarray, $trackeeid, $totalbill, $changed)
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
                        Set `totalbill`='%f',`status` = %d                        
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::Float($totalbill),
                        Sanitise::Long($changed),
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
    
    public function setview($serviceid, $status)
    {
        if($status != 9)
        {
            $SQL = sprintf( "Update servicecall
                            Set `status`=1                       
                            WHERE serviceid = %d AND customerno = %d",
                            Sanitise::Long($serviceid),                
                            $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);

            $Query = sprintf("SELECT client.clientname, trackee.tname,trackee.branchid
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
                    $tbranchid = $row["branchid"];
                }
            }

            $message = $tname." has viewed the call for ".$clientname;
            $today = date("Y-m-d H:i:s");        
            $SQL = sprintf( "INSERT INTO notifications
                            (`customerno`,`message`,`isnotified`,`timestamp`,`status`,`isshown`,`branchid`) VALUES
                            ( '%d','%s',%d,'%s',1,0,%d)",
            $this->_Customerno, $message,0,  Sanitise::DateTime($today),$tbranchid);        
            $this->_databaseManager->executeQuery($SQL);                        
        }
        else
        {
            $SQL = sprintf( "Update servicecall
                            Set `cancelled_view`=1                       
                            WHERE serviceid = %d AND customerno = %d",
                            Sanitise::Long($serviceid),                
                            $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);            
            
            $Query = sprintf("SELECT trackeeid
                            FROM `servicecall`
                            where customerno = %d AND serviceid=%d",
                $this->_Customerno,
                Sanitise::Long($serviceid));
            $this->_databaseManager->executeQuery($Query);		

            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $trackeeid = $row["trackeeid"];
                }
            }
            
            $SQL = sprintf( "Update trackee
                            Set `pushservice`=1
                            WHERE customerno = %d AND trackeeid = %d",
                            $this->_Customerno,                         
                            Sanitise::Long($trackeeid));
            $this->_databaseManager->executeQuery($SQL);                                                    
        }
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
    
    public function getclientid($serviceid)
    {
        $Query = sprintf("SELECT clientid
                        FROM `servicecall` 
                        where customerno = %d AND isdeleted = 0 AND serviceid = %d",
            $this->_Customerno, Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() == 1) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                return $row["clientid"];
            }
        }
        return null;        
    }
    
public function validate_discount($code,$client,$expiry)
{    
    $json_disc=array();
    $sql= sprintf("select * from discount where isdeleted=0 and dis_code='%s' COLLATE latin1_general_cs AND customerno = %d", Sanitise::String($code), $this->_Customerno);
    $this->_databaseManager->executeQuery($sql);		
    if ($this->_databaseManager->get_rowCount() > 0) 
    {
        while ($row = $this->_databaseManager->get_nextRow())            
        {
            $json_discobj = new json_disc();            
            if($row['dis_id']!="")
            {
		if(strtotime($row['expiry'])>=strtotime($expiry." 00:00:00"))
                {
                    if($row['is_mass']!=0)
                    {
                        if(in_array($this->check_client_with_branch($client), explode(",",$row['branchid'])))
                        {
                            if($this->check_servicecall_with_code($client,$row['dis_id'])==0)
                            {
                                $json_discobj->error=false;
                                $json_discobj->dis_amt=$row['dis_amt'];
                                $json_discobj->dis_id=$row['dis_id'];
                            }
                            else
                            {
                                $json_discobj->error=true;
                                $json_discobj->error_msg="code already used ";
                            }							
                        }
                        else
                        {
                            $json_discobj->error=true;
                            $json_discobj->error_msg="code is not valid in the customer branch";
                        }
                    }
                    else
                    {
                        if($this->check_servicecall_with_code($client,$row['dis_id'])==0)
                        {
                            $json_discobj->error=false;
                            $json_discobj->dis_amt=$row['dis_amt'];
                            $json_discobj->dis_id=$row['dis_id'];
                        }
                        else
                        {
                            $json_discobj->error=true;
                            $json_discobj->error_msg="code already used ";
                        }
                    }				
		}
                else
                {
                    $json_discobj->error=true;
                    $json_discobj->error_msg="code validity date  expired";	
		}	
            }
            else
            {
                $json_discobj->error=true;
                $json_discobj->error_msg="code does not exist in our data base";
            }
            $json_disc[] = $json_discobj;
            return $json_disc;        
        }
    }
    return null;        
}

private function check_servicecall_with_code($client,$codeid)
{
    $sql= sprintf("select count(*) as callcount from servicecall where customerno = %d AND clientid= %d and dis_id= %d", $this->_Customerno, Sanitise::Long($client), Sanitise::Long($codeid));
    $this->_databaseManager->executeQuery($sql);		
    if ($this->_databaseManager->get_rowCount() > 0) 
    {
        while ($row = $this->_databaseManager->get_nextRow())            
        {
            return $row['callcount'];            
        }
    }
    return null;
}

private function check_client_with_branch($client)
{
    $sql= sprintf("select branchid  from client where customerno = %d AND clientid= %d",  $this->_Customerno, Sanitise::Long($client));
    $this->_databaseManager->executeQuery($sql);		
    if ($this->_databaseManager->get_rowCount() > 0) 
    {
        while ($row = $this->_databaseManager->get_nextRow())            
        {
            return $row['branchid'];                        
        }
    }
    return null;    
} 

}