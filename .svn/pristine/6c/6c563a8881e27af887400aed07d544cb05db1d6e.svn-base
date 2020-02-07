<?php
include_once '../lib/system/Validator.php';
include_once '../lib/system/VersionedManager.php';
include_once '../lib/system/Sanitise.php';
include_once '../lib/system/Date.php';
include_once '../lib/model/VOMessageManage.php';
include_once '../lib/model/VODevices.php';
include_once '../lib/model/VOItem.php';
include_once '../lib/model/VOMessage.php';
include_once '../lib/model/VOUser.php';
include_once '../lib/model/VOFormatField.php';
include_once '../lib/model/VOCommunicationQueue.php';

class ServiceManager extends VersionedManager
{
    public function ServiceManager($customerno)
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
            }
            return $device;            
        }
        return null;
    }                        
    
    public function getunreadmessagecount_for_device($trackeeid) 
    {
        $count = 0;
        $SQL = sprintf("SELECT * FROM messagemanage WHERE userid = %d AND rectype = 2 AND status=0 AND customerno = %d", $trackeeid, $this->_Customerno);            
        $this->_databaseManager->executeQuery($SQL);                                
        while ($row = $this->_databaseManager->get_nextRow())
        {
            $count++;
        }
        return $count;        
    }            
    
    public function getundelivereditemscount_for_device($trackeeid) 
    {
        $count = 0;
        $SQL = sprintf("SELECT * FROM items WHERE trackeeid = %d AND isdelivered = 0 AND customerno = %d", $trackeeid, $this->_Customerno);            
        $this->_databaseManager->executeQuery($SQL);                                
        while ($row = $this->_databaseManager->get_nextRow())
        {
            $count++;
        }
        return $count;        
    }                
        
    public function getitemsfortrackee($trackeeid) 
    {
        $items = Array();
        $deviceQuery = sprintf("SELECT * FROM `items` where items.customerno = %d AND items.trackeeid=%d AND items.isdelivered = 0",
            $this->_Customerno,
            Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($deviceQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $item = new VOItem();
                $item->itemdesc = $row["itemdesc"];
                $item->itemname = $row["itemname"];
                $item->itemno = $row["itemno"];
                $item->trackingno = $row["trackingno"];
                $item->sigreqd = $row["sigreqd"];
                $item->recipientname = $row["recipientname"];
                $item->customerno = $row["customerno"];                
                $item->trackeeid = $row["trackeeid"];                                
                $items[] = $item;
            }
            return $items;            
        }
        return null;
    }         
    
    public function getmessagesfortrackee($trackeeid) 
    {
        $messages = Array();
        $Query = sprintf("SELECT m.*, mm.status, u.realname FROM messages m INNER JOIN messagemanage mm ON m.messageid = mm.messageid INNER JOIN user u ON m.senderid = u.userid WHERE m.customerno = %d AND mm.userid = %d AND mm.rectype=2 and m.type=1 ORDER BY m.datecreated DESC",
            $this->_Customerno,
            Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $single_message = new VOMessage();
                $single_message->customerno = $row["customerno"];
                $single_message->subject = $row["subject"];
                $single_message->datecreated = $row["datecreated"];
                $single_message->sender = $row["realname"];    
                $single_message->messageid = $row["messageid"]; 
                $single_message->message = $row["message"];
                $single_message->status = $row["status"]; 
                $single_message->trackeeid = $trackeeid;
                $messages[] = $single_message;
            }
            return $messages;            
        }
        return null;
    }             
    
    public function getsentmessagesfortrackee($trackeeid)
    {
        $messages = Array();
        $Query = sprintf("SELECT m.*, mm.*, u.realname FROM messages m INNER JOIN messagemanage mm ON m.messageid = mm.messageid INNER JOIN user u ON mm.userid = u.userid WHERE m.customerno = %d AND m.senderid = %d AND mm.rectype=1 and m.type=2 ORDER BY m.datecreated DESC",
            $this->_Customerno,
            Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $single_message = new VOMessage();
                $single_message->customerno = $row["customerno"];
                $single_message->subject = $row["subject"];
                $single_message->datecreated = $row["datecreated"];
                $single_message->receiver = $row["realname"];    
                $single_message->messageid = $row["messageid"]; 
                $single_message->message = $row["message"];
                $single_message->status = $row["status"]; 
                $single_message->trackeeid = $trackeeid;
                $messages[] = $single_message;
            }
            return $messages;            
        }
        return null;        
    }
    
    public function getsentmessagedetails($trackeeid, $messageid)
    {
        $messageQuery = sprintf("SELECT m.*, mm.*, u.realname FROM messages m INNER JOIN messagemanage mm ON m.messageid = mm.messageid INNER JOIN user u ON mm.userid = u.userid WHERE m.customerno = %d AND m.senderid = %d AND m.messageid=%d AND mm.rectype=1 and m.type=2",
            $this->_Customerno,
            Sanitise::Long($trackeeid),
            Sanitise::Long($messageid));
        $this->_databaseManager->executeQuery($messageQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $single_message = new VOMessage();
                $single_message->customerno = $row["customerno"];
                $single_message->subject = $row["subject"];
                $single_message->datecreated = $row["datecreated"];
                $single_message->receiver = $row["realname"];    
                $single_message->messageid = $row["messageid"]; 
                $single_message->message = $row["message"];
                $single_message->status = $row["status"]; 
                $single_message->trackeeid = $trackeeid;
            }
            return $single_message;            
        }
        return null;                
    }
    
    public function getalltrackers() 
    {
        $trackers = Array();
        $trackerQuery = sprintf("SELECT realname, userid, customerno FROM user WHERE customerno = %d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($trackerQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $tracker = new VOUser();
                $tracker->customerno = $row["customerno"];
                $tracker->realname = $row["realname"];
                $tracker->userid = $row["userid"];
                $trackers[] = $tracker;
            }
            return $trackers;            
        }
        return null;
    }                 
    
    public function getformattedmessages($parent) 
    {
        $keys = Array();
        $ffQuery = sprintf("SELECT * FROM formatfield WHERE customerno = %d AND ffparentid=%d",
            $this->_Customerno, Sanitise::Long($parent));
        $this->_databaseManager->executeQuery($ffQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row["useformatted"] == 1 && $row["formattedkey"] != "")
                {
                    $key = new VOFormatField();
                    $key->customerno = $row["customerno"];
                    $key->id = $row["id"];
                    $key->formattedkey = $row["formattedkey"];
                    $key->name = $row["name"];
                    $keys[] = $key;
                }
            }
            return $keys;            
        }
        return null;
    }                     
    
    public function getffparents() 
    {
        $keys = Array();
        $ffQuery = sprintf("SELECT * FROM ffparent WHERE customerno = %d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($ffQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                    $key = new VOFormatField();
                    $key->customerno = $row["customerno"];
                    $key->id = $row["id"];
                    $key->ffparentname = $row["ffparentname"];
                    $keys[] = $key;
            }
            return $keys;            
        }
        return null;
    }                         
    
    public function getffparentid($zone) 
    {
        $ffQuery = sprintf("SELECT * FROM ffparent WHERE customerno = %d AND ffparentname='%s'",
            $this->_Customerno, Sanitise::String($zone));
        $this->_databaseManager->executeQuery($ffQuery);		
		
        if ($this->_databaseManager->get_rowCount() == 1) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                return $row["id"];
            }
        }
        return null;
    }                             
    
    public function adminlogin($username, $password) 
    {
        $Query = sprintf("SELECT * FROM user WHERE customerno = %d AND role='Administrator' AND username = '%s' AND password = '%s'",
            $this->_Customerno, Sanitise::String($username), Sanitise::String($password));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() == 1) 
        {
            return true;
        }
        return null;
    }                                 
    
    public function updateitems($itemno, $deliverydate, $isdelivered, $customerno)
    {
        $signature = $itemno.".jpeg";
        $SQL = sprintf( "Update items
                        Set `deliverydate`='%s',
                        `isdelivered`=%d,
                        `signature`='%s'                        
                        WHERE itemno = %d AND customerno = %d",
                        Sanitise::String($deliverydate),
                        Sanitise::Long($isdelivered),
                        Sanitise::String($signature),
                        Sanitise::Long($itemno),                
                        $customerno);
        $this->_databaseManager->executeQuery($SQL);                
    }
    
    public function pushformattedmessage($zone, $parentid, $trackeeid, $value1=null, $value2=null, $value3=null, $value4=null)
    {
        if(!isset($value1))
        {
            $value1="";
        }
        if(!isset($value2))
        {
            $value2="";
        }
        if(!isset($value3))
        {
            $value3="";
        }
        if(!isset($value4))
        {
            $value4="";
        }        
        $today = date("Y-m-d H:i:s");        
        $SQL1 = sprintf( "Update formatfield Set `formattedvalue`='%s',`trackeeid`=%d, `reporteddate`='%s'                  
                        WHERE name = 'FF1' AND customerno = %d AND ffparentid=%d",
                        Sanitise::String($value1),Sanitise::Long($trackeeid), Sanitise::DateTime($today), $this->_Customerno, Sanitise::Long($parentid));
        $this->_databaseManager->executeQuery($SQL1);                
        $SQL2 = sprintf( "Update formatfield Set `formattedvalue`='%s',`trackeeid`=%d, `reporteddate`='%s'                
                        WHERE name = 'FF2' AND customerno = %d AND ffparentid=%d",
                        Sanitise::String($value2),Sanitise::Long($trackeeid), Sanitise::DateTime($today), $this->_Customerno, Sanitise::Long($parentid));
        $this->_databaseManager->executeQuery($SQL2);                
        $SQL3 = sprintf( "Update formatfield Set `formattedvalue`='%s',`trackeeid`=%d, `reporteddate`='%s'                  
                        WHERE name = 'FF3' AND customerno = %d AND ffparentid=%d",
                        Sanitise::String($value3),Sanitise::Long($trackeeid), Sanitise::DateTime($today), $this->_Customerno, Sanitise::Long($parentid));
        $this->_databaseManager->executeQuery($SQL3);                
        $SQL4 = sprintf( "Update formatfield Set `formattedvalue`='%s',`trackeeid`=%d, `reporteddate`='%s'                 
                        WHERE name = 'FF4' AND customerno = %d AND ffparentid=%d",
                        Sanitise::String($value4),Sanitise::Long($trackeeid), Sanitise::DateTime($today), $this->_Customerno, Sanitise::Long($parentid));
        $this->_databaseManager->executeQuery($SQL4);                
        
        // Create History
        $SQL = sprintf("INSERT INTO formatfieldhistory (customerno, ff1value, ff2value, ff3value, ff4value, trackeeid, reporteddate, ffparentid) VALUES (%d, '%s','%s','%s','%s',%d,'%s',%d)",
                $this->_Customerno,
                Sanitise::String($value1),
                Sanitise::String($value2),
                Sanitise::String($value3),
                Sanitise::String($value4),
                Sanitise::Long($trackeeid),
                Sanitise::DateTime($today),
                Sanitise::Long($parentid));
        $this->_databaseManager->executeQuery($SQL);
        
        $keys = Array();
        $ffQuery = sprintf("SELECT * FROM formatfield ff WHERE ff.customerno = %d AND ff.ffparentid=%d",
            $this->_Customerno, Sanitise::Long($parentid));
        $this->_databaseManager->executeQuery($ffQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row["useformatted"] == 1 && $row["formattedkey"] != "")
                {
                    $key = new VOFormatField();
                    $key->formattedkey = $row["formattedkey"];
                    $key->formattedvalue = $row["formattedvalue"];                    
                    $keys[] = $key;
                }
            }
        }        
        
        $Query = sprintf("SELECT tname FROM trackee t WHERE t.customerno = %d AND t.trackeeid = %d LIMIT 1",
            $this->_Customerno, Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $tname = $row["tname"];
            }
        }                
        
        if(isset($zone) && $zone != "")
        {
            $sql = sprintf("SELECT id FROM ffparent WHERE customerno=%d AND ffparentname ='%s'",$this->_Customerno, Sanitise::String($zone));
            $this->_databaseManager->executeQuery($sql);		            
            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    $zoneid = $row["id"];
                }
            }                            
        }
        
        
        $message = "<u>Format: <b>".$zone."</b></u><br/>";
        foreach($keys as $thiskey)
        {
            $message.= $thiskey->formattedkey." : <b>".$thiskey->formattedvalue."</b><br/>";
        }
        
        $subject = "Elixia Report by ". $tname;
                
        $SQLM = sprintf("INSERT INTO messages (subject, message, datecreated, senderid, type, customerno) VALUES ('%s', '%s', '%s', %d, 2, %d)", 
                GetSafeValueString($subject, "string"),
                GetSafeValueString($message, "string"),
                Sanitise::DateTime($today),
                GetSafeValueString($trackeeid, "long"),
                $this->_Customerno);
        $this->_databaseManager->executeQuery($SQLM);
        $messageid = @mysql_insert_id();                
        
        $users = Array();
        $Query = sprintf("SELECT * FROM receivereports WHERE customerno = %d AND ffparentid = %d",
            $this->_Customerno, Sanitise::Long($zoneid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {                
                $users[] = $row["userid"];
            }
        }                
        
        foreach($users as $thisuser)
        {
            $SQL = sprintf("INSERT INTO messagemanage (customerno, userid, messageid, status, rectype) VALUES (%d, %d, %d, 0, 1)",
                    $this->_Customerno,
                    Sanitise::Long($thisuser),
                    GetSafeValueString($messageid, "long"));
            $this->_databaseManager->executeQuery($SQL);                        

            $Query = sprintf("SELECT form_email, form_sms, email, phone FROM user WHERE userid=%d AND customerno=%d",
                Sanitise::Long($thisuser), $this->_Customerno);
            $this->_databaseManager->executeQuery($Query);		

            if ($this->_databaseManager->get_rowCount() > 0) 
            {
                while ($row = $this->_databaseManager->get_nextRow())            
                {
                    if($row["form_email"] ==1 && isset($row["email"]) && $row["email"] != "")
                    {
                        $send = new VOCommunicationQueue();
                        $send->type = 0;
                        $send->message = "<html><body>";
                        $send->message.= '<table style="border-color: #666;" cellpadding="10">';
                        $send->message.= "<tr style='background: #eee;'><td><strong>Format:</strong> </td><td><strong>".$zone."</strong></td></tr>";
                        foreach($keys as $thiskey)
                        {
                            $send->message.= "<tr><td>".$thiskey->formattedkey."</td><td><b>".$thiskey->formattedvalue."</b></td></tr>";
                        }
                        $send->message.="<tr><td colspan='2'>Powered by Elixia Tech.</td></tr>";
                        $send->message.= '</table>';                    
                        $send->message.= "</body></html>";                    
                        $send->email = $row["email"];
                        $send->phone = $row["phone"];
                        $send->subject = "Elixia Report by ".$tname;       
                        $send->customerno = $this->_Customerno;
                        $this->Insert($send);
                    }
                    if($row["form_sms"] ==1 && isset($row["phone"]) && $row["phone"] != "")
                    {
                        $send = new VOCommunicationQueue();
                        $send->type = 1;
                        $send->message = "Format: ".$zone."\n";
                        foreach($keys as $thiskey)
                        {
                            $send->message.= $thiskey->formattedkey." : ".$thiskey->formattedvalue."\n";
                        }                                  
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
    }    
    
    public function itemdelnotification($trackeeid, $itemno)
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

        $itemsQuery = sprintf("SELECT * FROM items WHERE itemno = %d AND customerno = %d", Sanitise::Long($itemno), $this->_Customerno);
        $this->_databaseManager->executeQuery($itemsQuery);		
		
        if ($this->_databaseManager->get_rowCount() == 1) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $userid = $row["userid"];
                $itemname = $row["itemname"];
                $trackingno = $row["trackingno"];
                $itemdesc = $row["itemdesc"];                
                $deliverydate = $row["deliverydate"];
            }
        }    
        
        $subject = "Item Delivery Report";
        $message = "Delivered by  <b>".$trackeename."</b><br/>";
        $message.= "Item name: <b>".$itemname."</b><br/>";
        $message.= "Tracking No: <b>".$trackingno."</b><br/>";
        $message.= "Description: <b>".$itemdesc."</b><br/>";
        $message.= "Date: <b>".$deliverydate."</b><br/>";
        
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

        $Query = sprintf("SELECT userid, email, phone, item_email, item_sms FROM user WHERE userid=%d AND customerno=%d",
                Sanitise::Long($userid), $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row["item_email"] ==1 && isset($row["email"]) && $row["email"] != "")
                {
                    $send = new VOCommunicationQueue();
                    $send->type = 0;
                    $send->message = "<html><body>";
                    $send->message.= '<table style="border-color: #666;" cellpadding="10">';
                    $send->message.= "<tr style='background: #eee;'><td colspan='2'><strong>Delivered by ".$trackeename."</strong> </td></tr>";
                    $send->message.= "<tr><td>Item Name</td><td><b>".$itemname."</b></td></tr>";
                    $send->message.= "<tr><td>Tracking No.</td><td><b>".$trackingno."</b></td></tr>";                    
                    $send->message.= "<tr><td>Description</td><td><b>".$itemdesc."</b></td></tr>";                                        
                    $send->message.= "<tr><td>Date</td><td><b>".$deliverydate."</b></td></tr>";                                                            
                    $send->message.="<tr><td colspan='2'>Powered by Elixia Tech.</td></tr>";
                    $send->message.= '</table>';                    
                    $send->message.= "</body></html>";                    
                    $send->email = $row["email"];
                    $send->phone = $row["phone"];
                    $send->subject = "Item Report by ".$trackeename;       
                    $send->customerno = $this->_Customerno;                    
                    $this->Insert($send);
                }
                if($row["item_sms"] ==1 && isset($row["phone"]) && $row["phone"] != "")
                {
                    $send = new VOCommunicationQueue();
                    $send->type = 1;
                    $send->message = "Item Name: ".$itemname."\n";
                    $send->message.= "Delivered by ".$trackeename." \n";                    
                    $send->message.= "Date: ".$deliverydate."\n";                    
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
    
    public function sendnotification($type, $trackeeid)
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
        
        $subject = $trackeename." Status Changed";
        if($type == 1)
        {
            $message = "Tracking has been activated by ".$trackeename."." ;
        }
        else
        {
            $message = "Tracking has been de-activated by ".$trackeename.".";                        
        }
        $today = date("Y-m-d H:i:s");        
        
        $SQLM = sprintf("INSERT INTO messages (subject, message, datecreated, senderid, type, customerno) VALUES ('%s', '%s', '%s', %d, 2, %d)", 
                GetSafeValueString($subject, "string"),
                GetSafeValueString($message, "string"),
                Sanitise::DateTime($today),
                GetSafeValueString($trackeeid, "long"),
                $this->_Customerno);
        $this->_databaseManager->executeQuery($SQLM);
        $messageid = @mysql_insert_id();                
        
        $users = Array();
        $Query = sprintf("SELECT userid, email, phone FROM user WHERE role='Administrator' AND customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $uservo = new VOUser();
                $uservo->userid = $row["userid"];
                $uservo->email = $row["email"];
                $uservo->phone = $row["phone"];
                $uservo->tracking = $row["tracking_act"];
                $users[] = $uservo;
            }
        }
        
        if(isset($users))
        {
            foreach($users as $thisuser)
            {            
                $SQL = sprintf("INSERT INTO messagemanage (customerno, userid, messageid, status, rectype) VALUES (%d, %d, %d, 0, 1)",
                        $this->_Customerno,
                        Sanitise::Long($thisuser->userid),
                        GetSafeValueString($messageid, "long"));
                $this->_databaseManager->executeQuery($SQL);                                                

                    $send = new VOCommunicationQueue();
                    
                    if(isset($thisuser->email) && $thisuser->email != "")
                    {
                        if(isset($thisuser->tracking) && $thisuser->tracking == 1)
                        {
                            $send->type = 0;
                            if($type == 1)
                            {
                                $send->message = "Tracking has been activated by ".$trackeename.".<br/>" ;
                            }
                            else
                            {
                                $send->message = "Tracking has been de-activated by ".$trackeename.".<br/>";                        
                            }
                            $send->message.="Powered by Elixia Tech.";
                            $send->email = $thisuser->email;
                            $send->phone = $thisuser->phone;
                            $send->subject = "Elixia Status for ".$trackeename;       
                            $send->customerno = $this->_Customerno;                            
                            $this->Insert($send);
                        }
                    }            
            }
        }
    }
    
    public function shutdownnotification($trackeeid)
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
        
        $subject = "Phone Switch Off by ". $trackeename;
        $message = "The tracking device has been switched off by ".$trackeename."." ;
        $today = date("Y-m-d H:i:s");        
        
        $SQLM = sprintf("INSERT INTO messages (subject, message, datecreated, senderid, type, customerno) VALUES ('%s', '%s', '%s', %d, 2, %d)", 
                GetSafeValueString($subject, "string"),
                GetSafeValueString($message, "string"),
                Sanitise::DateTime($today),
                GetSafeValueString($trackeeid, "long"),
                $this->_Customerno);
        $this->_databaseManager->executeQuery($SQLM);
        $messageid = @mysql_insert_id();                
        
        $users = Array();
        $Query = sprintf("SELECT userid, email, phone, switchoff_email, switchoff_sms FROM user WHERE role='Administrator' AND customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $uservo = new VOUser();
                $uservo->userid = $row["userid"];
                $uservo->email = $row["email"];
                $uservo->phone = $row["phone"];
                $uservo->switchoff_email = $row["switchoff_email"];
                $uservo->switchoff_sms = $row["switchoff_sms"];                
                $users[] = $uservo;
            }
        }
        
        if(isset($users))
        {
            foreach($users as $thisuser)
            {            
                $SQL = sprintf("INSERT INTO messagemanage (customerno, userid, messageid, status, rectype) VALUES (%d, %d, %d, 0, 1)",
                        $this->_Customerno,
                        Sanitise::Long($thisuser->userid),
                        GetSafeValueString($messageid, "long"));
                $this->_databaseManager->executeQuery($SQL);                                                

                $send = new VOCommunicationQueue();
                if(isset($thisuser->email) && $thisuser->email != "" && $thisuser->switchoff_email == 1)
                {
                    $send->type = 0;
                    $send->message = "The tracking device has been switched off by ".$trackeename.".<br/>" ;
                    $send->message.="Powered by Elixia Tech.";
                    $send->email = $thisuser->email;
                    $send->phone = $thisuser->phone;
                    $send->subject = "Phone Switch Off by ". $trackeename;       
                    $send->customerno = $this->_Customerno;                    
                    $this->Insert($send);
                }                                
                if(isset($thisuser->phone) && $thisuser->phone != "" && $thisuser->switchoff_sms == 1)
                {
                    $send->type = 1;
                    $send->message = "The tracking device has been switched off by ".$trackeename."." ;
                    $send->message.="Powered by Elixia Tech.";
                    $send->email = $thisuser->email;
                    $send->phone = $thisuser->phone;
                    $send->subject = ""; 
                    $send->customerno = $this->_Customerno;                    
                    $this->Insert($send);
                }                                                    
            }
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
    
    public function modifymessagestatusfortrackee($trackeeid, $messageid)
    {
        $SQL = sprintf( "Update messagemanage
                        Set `status`=1                        
                        WHERE messageid = %d AND customerno = %d AND userid = %d AND rectype = 2",
                        Sanitise::Long($messageid),
                        $this->_Customerno,                         
                        Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                
    }
    
    public function pushmessage($trackeeid, $trackerid, $subject, $message)
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
                Sanitise::Long($trackerid),
                GetSafeValueString($messageid, "long"));
        $this->_databaseManager->executeQuery($SQL);
        
        $Query = sprintf("SELECT userid, email, phone FROM user WHERE userid = %d AND customerno=%d",
                 Sanitise::Long($trackerid), $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                
                $send = new VOCommunicationQueue();
                if(isset($row["email"]) && $row["email"] != "")
                {
                    $send->type = 0;
                    $send->message = $message."<br/>Powered by Elixia Tech.";
                    $send->email = $row["email"];
                    $send->phone = $row["phone"];
                    $send->subject = "Elixia Police - Message from ".$trackeename;       
                    $send->customerno = $this->_Customerno;                                        
                    $this->Insert($send);
                }
            }
        }        
    }
    
    public function modifydelivery($trackeeid, $itemno)
    {
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "Update items
                        Set `isdelivered`=1,
                        `deliverydate` = '%s'
                        WHERE itemno = %d AND customerno = %d AND trackeeid = %d",
                        Sanitise::DateTime($today),
                        Sanitise::Long($itemno),
                        $this->_Customerno,                         
                        Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                        
    }    
    
    public function updatepushitems($trackeeid)
    {
        $SQL = sprintf( "Update trackee
                        Set `pushitems`=0
                        WHERE customerno = %d AND trackeeid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                        
    }        
    
    public function updatepushmessages($trackeeid)
    {
        $SQL = sprintf( "Update trackee
                        Set `pushmessages`=0
                        WHERE customerno = %d AND trackeeid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                        
    }            

    public function pushlocal($localdate, $latitude, $longitude, $devicekey, $deviceid, $trackeeid)
    {
        $SQL = sprintf("INSERT INTO devicehistory (deviceid, customerno, trackeeid, devicelat, devicelong, lastupdated, devicekey) VALUES (%d, %d, %d, %f, %f, '%s', '%s')",
                Sanitise::Long($deviceid),
                $this->_Customerno,
                Sanitise::Long($trackeeid),
                Sanitise::Float($latitude),
                Sanitise::Float($longitude),
                Sanitise::String($localdate),
                Sanitise::String($devicekey));
        $this->_databaseManager->executeQuery($SQL);        
    }    
}