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

class SCVF1Manager extends VersionedManager
{
    public function SCVF1Manager($customerno)
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
    
    public function pullmasterchange($trackeeid)
    {
        $Query = sprintf("SELECT *
                        FROM `trackee` 
                        where customerno = %d AND trackeeid=%d",
            $this->_Customerno, Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $trackee = new json_disc();
                $trackee->pushremarks = $row["pushremarks"];
                $trackee->pushfeedback = $row["pushfeedback"];
                $trackee->pushservicelist = $row["pushservicelist"];
                $trackee->pushform = $row["pushform"];                
            }
            return $trackee;
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
    
    public function getbranchid($trackeeid)
    {
        $services = Array();
        $Query = sprintf("SELECT branchid
                        FROM `trackee` 
                        where customerno = %d AND isdeleted = 0 AND trackeeid = %d",
            $this->_Customerno, Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                return $row["branchid"];
            }
        }
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
                   $clientname = $row["clientname"];
                   $tname = $row["tname"];
                   $tbranchid = $row["branchid"];
               }
           }
        $thisarray->serviceid;
        $clientname = mysql_real_escape_string($clientname);                                                
        $message = $tname." has changed the call for ".$clientname;
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                       (`customerno`,`message`,`isnotified`,`status`,`isshown`,`branchid`) VALUES
                       ( '%d','%s',%d,8,0,%d)",
        $this->_Customerno, $message,0,  $tbranchid);        
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
                        servicecall.discount_code, servicecall.discount_amount, servicecall.status, discount.dis_percent, discount.isupgrade, discount.ispercent,
                        servicecall.ischargable, servicecall.visiting_charges, client.form_type_id
                        FROM `servicecall` 
                        INNER JOIN client ON client.clientid = servicecall.clientid 
                        LEFT OUTER JOIN discount ON servicecall.dis_id = discount.dis_id
                        where servicecall.customerno = %d AND servicecall.trackeeid=%d AND servicecall.status IN (0,1,2,3,9,11) AND servicecall.cancelled_view = 0",
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
                if($row["dis_percent"] == null)
                {                    
                    $call->dis_percent = 0;                    
                }
                else
                {
                    $call->dis_percent = $row["dis_percent"];                    
                }
                if($row["ispercent"] == null)
                {                    
                    $call->ispercent = 0;                    
                }
                else
                {
                    $call->ispercent = $row["ispercent"];                    
                }
                if($row["isupgrade"] == null)
                {                    
                    $call->isupgrade = 0;                    
                }
                else
                {
                    $call->isupgrade = $row["isupgrade"];                    
                }                
                $call->visitingcharges = $row["visiting_charges"];
                $call->ischargeable = $row["ischargable"];
                $call->formtype = $row["form_type_id"];
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
        $Query = sprintf("SELECT status
                        FROM `servicecall`
                        where customerno = %d AND serviceid=%d",
            $this->_Customerno,
            Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		

        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {        
                if($row["status"] != 9)
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
                    $clientname = mysql_real_escape_string($clientname);                                        
                    $message = $tname." has departed to ".$clientname." at ". Sanitise::DateTime($departtime);
                    $today = date("Y-m-d H:i:s");        
                    $SQL = sprintf( "INSERT INTO notifications
                                    (`customerno`,`message`,`isnotified`,`isshown`,`status`,`branchid`) VALUES
                                    ( '%d','%s',%d,0,2,%d)",
                    $this->_Customerno, $message,0, $tbranchid);        
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
            }
        }
    }               
    
    public function setwarning($serviceid, $timestamp, $type)
    {
        if($type != 3)
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
            $clientname = mysql_real_escape_string($clientname);                                                
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
                            (`customerno`,`message`,`isnotified`,`isshown`,`status`,`branchid`) VALUES
                            ( '%d','%s',%d,0,%d,%d)",
            $this->_Customerno, $message,0,  $status, $tbranchid);        
            $this->_databaseManager->executeQuery($SQL);
        }
        else
        {
                $SQL = sprintf( "Update servicecall
                                Set `status` = 11
                                WHERE customerno = %d AND serviceid = %d",
                                $this->_Customerno,                         
                                Sanitise::Long($serviceid));  
                $this->_databaseManager->executeQuery($SQL);                           
        }

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
        $clientname = mysql_real_escape_string($clientname);                                                
        $message = $tname." has closed the call for ".$clientname." at ". Sanitise::DateTime($closedon);
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                            (`customerno`,`message`,`isnotified`,`isshown`,`status`,`branchid`) VALUES
                        ( '%d','%s',%d,0,5,%d)",
        $this->_Customerno, $message,0,$tbranchid);        
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
                        (`customerno`,`message`,`isnotified`,`isshown`,`status`,`branchid`) VALUES
                        ( '%d','%s',%d,0,10,'%d')",
        $this->_Customerno, $notifymessage,0,  $tbranchid);        
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
        $clientname = mysql_real_escape_string($clientname);                
        $message = $tname." started the call for ".$clientname." at ". Sanitise::DateTime($starttime);
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`isshown`,`status`,`branchid`) VALUES
                        ( '%d','%s',%d,0,3,%d)",
        $this->_Customerno, $message,0,  $tbranchid);        
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
        $clientname = mysql_real_escape_string($clientname);
        $message = $tname." ended the call for ".$clientname." at ". Sanitise::DateTime($endtime);        
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO notifications
                        (`customerno`,`message`,`isnotified`,`isshown`,`status`,`branchid`) VALUES
                        ( '%d','%s',%d,0,4,%d)",
        $this->_Customerno, $message,0,  $tbranchid);        
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
    
    public function pushservices($thisarray, $trackeeid, $totalbill, $changed, $discountf)
    {
        $today = date("Y-m-d H:i:s");        
        if($thisarray->id == 0)
        {
            $SQL = sprintf( "INSERT INTO servicemanage
                            (`servicelistid`, `servicecallid`, `isdeleted`, `customerno`, `iseditedby`, `iscreatedby`, `timestamp`,`trackeeid`,`sqlite_timestamp`) VALUES
                            ( '%d','%d','%d','%d',1,'%d','%s','%d','%s')",
            Sanitise::String($thisarray->servicelistid),
            Sanitise::String($thisarray->serviceid),
            Sanitise::String($thisarray->isdeleted),    
            $this->_Customerno,
            Sanitise::Long($thisarray->createdby),
            Sanitise::String($today),
            Sanitise::Long($trackeeid),        
            Sanitise::DateTime($thisarray->datepushed));                    
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
                                `timestamp`='%s',
                                `sqlite_timestamp`='%s'                                
                                WHERE customerno = %d AND id = %d",
                                Sanitise::Long($thisarray->servicelistid),
                                 Sanitise::Long($thisarray->serviceid),
                                 Sanitise::Long($thisarray->isdeleted),
                                 Sanitise::Long($trackeeid),
                                 Sanitise::Long($isedited),
                                 Sanitise::Long($thisarray->createdby),
                                 Sanitise::String($today),
                                Sanitise::DateTime($thisarray->datepushed),
                                $this->_Customerno,                         
                                Sanitise::Long($thisarray->id));
            $this->_databaseManager->executeQuery($SQL);                                                                
        }
        
        $SQL = sprintf( "Update servicecall
                        Set `totalbill`='%f',`discount_amount`='%f',`status` = %d                        
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::Float($totalbill),
                        Sanitise::Float($discountf),                
                        Sanitise::Long($changed),
                        Sanitise::Long($thisarray->serviceid),                
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);  
        
        return $thisarray;
    }

    public function pushpartialpayment($serviceid, $thisarray)
    {
        $query = sprintf("SELECT sqlitepaymentid FROM payment WHERE serviceid = %d AND customerno = %d AND sqlitepaymentid = %d", Sanitise::Long($serviceid), $this->_Customerno, $thisarray->paymentid);
        $this->_databaseManager->executeQuery($query);		
        if ($this->_databaseManager->get_rowCount() == 0) 
        {        
            $SQL = sprintf( "INSERT INTO payment
                            (`serviceid`, `type`, `is_partial`, `partial_amt`, `chequeno`, `accountno`, `branch`, `customerno`, `sqlitepaymentid`, `reason`, `is_web`, `sqlite_timestamp`, `bank`) VALUES
                            ( '%d','%d','%d','%f','%d','%d','%s','%d','%d','%s',0, '%s', '%s')",
            Sanitise::Long($serviceid),
            Sanitise::Long($thisarray->type),
            Sanitise::Long($thisarray->is_partial),    
            Sanitise::Float($thisarray->partial_amt),
            Sanitise::Long($thisarray->chequeno),
            Sanitise::Long($thisarray->accountno),
            Sanitise::String($thisarray->branch),                
            $this->_Customerno,
            Sanitise::Long($thisarray->paymentid),        
            Sanitise::String($thisarray->reason),                    
            Sanitise::DateTime($thisarray->datepushed), 
            Sanitise::Long($thisarray->bank));                                
            $this->_databaseManager->executeQuery($SQL);            

            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function pushformresult($thisarray)
    {
        $SQL = sprintf( "INSERT INTO checklist_active_data
                        (`dtid`, `ftid`, `value`, `servicecall_id`, `customerno`, `sqlite_timestamp`) VALUES
                        ( '%d','%d','%s','%d','%d','%s')",
        Sanitise::Long($thisarray->dtid),
        Sanitise::Long($thisarray->formid),
        Sanitise::String($thisarray->result),    
        Sanitise::Long($thisarray->serviceid),
        $this->_Customerno,
        Sanitise::DateTime($thisarray->datepushed));                                
        $this->_databaseManager->executeQuery($SQL);            
        return true;
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
    public function update_visits($serviceid,$times_stamp)
    {
            $Query = sprintf("SELECT clientid
            FROM `servicecall`
            where customerno = %d AND serviceid=%d",
            $this->_Customerno,
            Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);
        $clientid=0;
        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $clientid = $row["clientid"];
            }
        }
        if($clientid!=0)
        {
            $Query = sprintf("SELECT serviceid FROM `servicecall` where customerno = %d AND clientid=%d",$this->_Customerno,Sanitise::Long($clientid));
            $this->_databaseManager->executeQuery($Query);
            $totalcalls =0;
            while($row = $this->_databaseManager->get_nextRow())
            {
                $totalcalls++;
            }
            if($totalcalls==1)
            {
                $SQL = sprintf( "Update client Set `first_visit`='%s',`last_visit`='%s' ,has_visit=1 WHERE clientid = %d AND customerno = %d",Sanitise::DateTime($times_stamp),
                    Sanitise::DateTime($times_stamp),Sanitise::Long($clientid),$this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
            }
            elseif($totalcalls>1)
            {
                $SQL = sprintf( "Update client Set `last_visit`='%s',has_visit=1 WHERE clientid = %d AND customerno = %d",
                    Sanitise::DateTime($times_stamp),Sanitise::Long($clientid),$this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
            }
        }
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
        $Query = sprintf("SELECT status
                        FROM `servicecall`
                        where customerno = %d AND serviceid=%d",
            $this->_Customerno,
            Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		

        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {        
                if($status == 9 || $row["status"] == 9)
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
                else
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
                    $clientname = mysql_real_escape_string($clientname);                                        
                    $message = $tname." has viewed the call for ".$clientname;
                    $today = date("Y-m-d H:i:s");        
                    $SQL = sprintf( "INSERT INTO notifications
                                    (`customerno`,`message`,`isnotified`,`status`,`isshown`,`branchid`) VALUES
                                    ( '%d','%s',%d,1,0,%d)",
                    $this->_Customerno, $message,0, $tbranchid);        
                    $this->_databaseManager->executeQuery($SQL);                        
                }
            }
        }
    }
    
    public function settotalbill($serviceid, $totalbill, $discount, $code, $dis_id)
    {
        $SQL = sprintf( "Update servicecall
                        Set `totalbill`='%f',
                        `discount_code`='%s',
                        `discount_amount`='%f',
                        `is_web`=2,
                        `dis_id`=%d                        
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::Float($totalbill),                
                        Sanitise::String($code), 
                        Sanitise::Float($discount),                 
                        Sanitise::Long($dis_id),                                 
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
    
public function validate_discount($code,$client,$expiry, $action, $sid, $branchid)
{    
    $json_disc=array();
    if($code != "")
    {
        $sql= sprintf("select * from discount where isdeleted=0 and dis_code='%s' AND customerno = %d", Sanitise::String($code), $this->_Customerno);
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
                            if($client!=0 &&  $client!="")
                            {
                                if(in_array($this->check_client_with_branch($client), explode(",",$row['branchid'])))
                                {
                                    if($this->check_servicecall_with_code($client,$row['dis_id'], $sid, $action)==0)
                                    {
                                        $json_discobj->error=false;
                                        $json_discobj->dis_amt=$row['dis_amt'];
                                        $json_discobj->dis_id=$row['dis_id'];
                                        $json_discobj->ispercent=$row['ispercent'];                                
                                        $json_discobj->isupgrade=$row['isupgrade'];                                                                
                                        $json_discobj->dis_percent=$row['dis_percent'];                                                                                                
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
                                if(in_array($branchid, explode(",",$row['branchid'])) )
                                {
                                    $json_disc->error=false;
                                    $json_disc->dis_amt=$row['dis_amt'];
                                    $json_disc->dis_id=$row['dis_id'];
                                    $json_disc->dis_percent=$row['dis_percent'];
                                    $json_disc->ispercent=$row['ispercent'];
                                    $json_disc->isupgrade=$row['isupgrade'];
                                }
                                else
                                {
                                    $json_disc->error=true;
                                    $json_disc->error_msg="code not valid in the customers branch";
                                }
                            }
                        }
                        else
                        {
                            if($this->check_servicecall_with_code($client,$row['dis_id'], $sid, $action)==0 && ($this->check_discount_for_client($client)!=0 || $this->check_discount_for_client($client)!=""))
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
            }
        }
        else
        {
            $json_discobj->error=true;
            $json_discobj->error_msg="code does not exist in our data base";
        }        
    }
    else
    {
        $json_discobj->error=true;
        $json_discobj->error_msg="code is empty";        
    }
    $json_disc[] = $json_discobj;    
    return $json_disc;        
}

private function check_servicecall_with_code($client,$codeid, $sid, $action)
{
    $sql= sprintf("select count(*) as callcount from servicecall where customerno = %d AND clientid= %d and dis_id= %d", $this->_Customerno, Sanitise::Long($client), Sanitise::Long($codeid));
    if($action!="add" )
    {
        $sql.= sprintf(" and  not serviceid =%d",$sid);            
    }
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

public function check_discount_for_client($client)
{
    $sql= sprintf("select dis_id from discount where isdeleted=0 AND customerno = %d AND clientid= %d",  $this->_Customerno, Sanitise::Long($client));
    $this->_databaseManager->executeQuery($sql);		
    if ($this->_databaseManager->get_rowCount() > 0) 
    {
        while ($row = $this->_databaseManager->get_nextRow())            
        {
            return $row['dis_id'];                        
        }
    }
    return null;    
}

public function populated_data($payload,$servicecall_id,$ftid)
{
    if($payload!="" && $servicecall_id!="" && $this->_Customerno!="")
    {
        $payload=json_decode($payload);
        foreach($payload as $thisarray)
        {
            if($this->check_checklist_data_exist($thisarray->dtid,$this->_Customerno))
            {
                $SQL = sprintf( "Update checklist_active_data
                                Set `dtid`=%d,
                                `ftid`=%d,
                                `value`='%s',
                                `servicecall_id`=%d,
                                `customerno`=%d,
                                `sqlite_timestamp`='%s'                               
                                WHERE dtid = %d AND customerno = %d",
                                Sanitise::Long($thisarray->dtid),                
                                Sanitise::Long($ftid), 
                                Sanitise::String($thisarray->result),                 
                                Sanitise::Long($servicecall_id),
                                $this->_Customerno,
                                Sanitise::DateTime($thisarray->datepushed),
                                Sanitise::Long($thisarray->dtid), $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);                
            }
            else
            {                
                $SQL = sprintf( "INSERT INTO checklist_active_data
                                (`dtid`,`ftid`,`value`,`servicecall_id`,`customerno`,`sqlite_timestamp`) VALUES
                                ( '%d','%d','%s','%d','%d','%s')",
                                Sanitise::Long($thisarray->dtid),                
                                Sanitise::Long($ftid), 
                                Sanitise::String($thisarray->result),                 
                                Sanitise::Long($servicecall_id),
                                $this->_Customerno,
                                Sanitise::DateTime($thisarray->datepushed));                
                $this->_databaseManager->executeQuery($SQL);                
            }
         }
    }
}

function check_checklist_data_exist($dtid,$customerno)
{
    if(($dtid!==0 ||$dtid!=="")&&($customerno!==0 ||$customerno!==""))
    {
        $sql= sprintf("select count(*) as count from checklist_active_data where dtid = %d AND customerno = %d", $dtid, $this->_Customerno);
        $this->_databaseManager->executeQuery($sql);		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row['count']>0)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }    
}
}