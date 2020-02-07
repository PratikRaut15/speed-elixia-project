<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/system/Date.php';
include_once 'lib/model/VOMessage.php';
include_once 'lib/model/VOMessageManage.php';
include_once 'lib/system/utilities.php';

class MessageManager extends VersionedManager
{
    public $type = array("user"=>1, "trackee"=>2, "Elixir"=>3);
    public $rectype = array("user"=>1, "trackee"=>2, "Elixir"=>3);    
    public $status = array("unread"=>0, "read"=>1, "deleted"=>2);    

    public function MessageManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }
    
    public function getMessagesForUser( $userid, $sent_messages = false, $messageid = null)
    {
        $messages = array();
        if(isset($messageid))
        {
            $SQL_ext = " AND m.messageid=$messageid";
        }
        if($sent_messages == true)
        {
            $SQL = sprintf("SELECT m.*, u.realname FROM messages m INNER JOIN user u ON m.senderid = u.userid WHERE m.customerno = %d $SQL_ext AND m.senderid = %d ORDER BY m.datecreated DESC", $this->_Customerno, GetSafeValueString($userid, "long"));
            $this->_databaseManager->executeQuery($SQL);            
            $sent = "messages_sent";
        }
        else
        {
            $SQL = sprintf("SELECT m.*, u.realname, t.tname, mm.status FROM messages m INNER JOIN messagemanage mm ON m.messageid = mm.messageid LEFT OUTER JOIN trackee t ON t.trackeeid = m.senderid LEFT OUTER JOIN user u ON m.senderid = u.userid WHERE m.customerno = %d AND mm.userid = %d AND mm.rectype=1 $SQL_ext  ORDER BY mm.status, m.datecreated DESC", $this->_Customerno, GetSafeValueString($userid, "long"));
            $this->_databaseManager->executeQuery($SQL);                        
            $sent = "messages_notsent";
        }
        
        while ($row = $this->_databaseManager->get_nextRow())
        {
            $single_message = new VOMessage();
            $single_message->customerno = $row["customerno"];
            $single_message->id = $row["messageid"];            
            $single_message->subject = $row["subject"];
            $single_message->datecreated = $row["datecreated"];
            if($row["type"] == 1)
            {
                $single_message->sender = $row["realname"];                    
            }
            else
            {
                $single_message->sender = $row["tname"];                                    
            }
            $single_message->messageid = $row["messageid"]; 
            $single_message->sentmessage = $sent;
            $single_message->message = $row["message"];
            if($row["status"] == 0)
            {
                $single_message->status = "Unread";
            }
            else
            {
                $single_message->status = "Read";                
            }
            $single_message->simpledatecreated = date("F j, Y, g:i a", strtotime($single_message->datecreated));
            $messages[] = $single_message;            
        }
        // Get Recipients
        $this->getrecipients($messages);
        return $messages;
    }
    
    
    public function getrecipients($messages)
    {
        $recipientliststring = "";
        if(isset($messages))
        {
            foreach($messages as $thismessage)
            {
                $SQL = sprintf("SELECT u.realname FROM messagemanage mm INNER JOIN user u ON u.userid = mm.userid WHERE mm.messageid = %d AND mm.rectype=1", $thismessage->messageid);
                $this->_databaseManager->executeQuery($SQL);
                if ($this->_databaseManager->get_rowCount() > 0) 
                {
                    while ($row = $this->_databaseManager->get_nextRow())            
                    {
                        $recipientliststring = $row["realname"];
                        if($row['realname']!='Elixir')
                        {
                            if($thismessage->recipientliststring == "")
                            {
                                $thismessage->recipientliststring = $recipientliststring;
                            }
                            else
                            {
                                $thismessage->recipientliststring.= ", " .$recipientliststring;                            
                            }
                        }
                    }
                }
                
                $SQL = sprintf("SELECT u.tname FROM messagemanage mm INNER JOIN trackee u ON u.trackeeid = mm.userid WHERE mm.messageid = %d AND mm.rectype=2", $thismessage->messageid);
                $this->_databaseManager->executeQuery($SQL);
                if ($this->_databaseManager->get_rowCount() > 0) 
                {
                    while ($row = $this->_databaseManager->get_nextRow())            
                    {
                        $recipientliststring = $row["tname"];
                        if($thismessage->recipientliststring == "")
                        {
                            $thismessage->recipientliststring = $recipientliststring;
                        }
                        else
                        {
                            $thismessage->recipientliststring.= ", " .$recipientliststring;                            
                        }
                    }
                }                
            }
        }
    }
    
    public function save( &$message_vo, $userid = null)
    {
        if ($message_vo->id > 0)
        {
                $SQL = sprintf("UPDATE messagemanage SET status=%d WHERE userid='%s' AND messageid=%d AND rectype=%d",
                        GetSafeValueString($message_vo->status, "long"),
                        GetSafeValueString($userid, "string"),
                        GetSafeValueString($message_vo->id, "long"),
                        GetSafeValueString($message_vo->rectype, "long"));
                $this->_databaseManager->executeQuery($SQL);
        }
        else
        {
            $today = date("Y-m-d H:i:s");        
            $SQL = sprintf("INSERT INTO messages (subject, message, datecreated, senderid, type, customerno) VALUES ('%s', '%s', '%s', %d, %d, %d)", 
                    GetSafeValueString($message_vo->subject, "string"),
                    GetSafeValueString($message_vo->message, "string"),
                    Sanitise::DateTime($today),                    
                    GetSafeValueString($message_vo->senderid, "long"),
                    GetSafeValueString($message_vo->type, "long"),
                    $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            $message_vo->id = @mysql_insert_id();
            
            if ($message_vo->id > 0)
            {
                if(isset($message_vo->recipientuserids))
                {
                    foreach($message_vo->recipientuserids as $thisrecipientid)
                    {
                        $SQL = sprintf("INSERT INTO messagemanage (customerno, userid, messageid, status, rectype) VALUES (%d, %d, %d,%d, %d)", 
                            $this->_Customerno,
                            GetSafeValueString($thisrecipientid, "long"),
                            GetSafeValueString($message_vo->id, "long"),
                            GetSafeValueString($message_vo->status, "long"),
                            (int)$this->rectype['user']);
                        $this->_databaseManager->executeQuery($SQL);
                    }
                }
                
                if(isset($message_vo->recipienttrackeeids))
                {
                    foreach($message_vo->recipienttrackeeids as $thistrackeeid)
                    {
                        $SQL = sprintf("INSERT INTO messagemanage (customerno, userid, messageid, status, rectype) VALUES (%d, %d, %d,%d, %d)", 
                            $this->_Customerno,
                            GetSafeValueString($thistrackeeid, "long"),
                            GetSafeValueString($message_vo->id, "long"),
                            GetSafeValueString($message_vo->status, "long"),
                            (int)$this->rectype['trackee']);
                        $this->_databaseManager->executeQuery($SQL);
                        
                        $SQL = sprintf( "Update trackee
                                        Set `pushmessages`=1
                                        WHERE customerno = %d AND trackeeid = %d",
                                        $this->_Customerno,                         
                                        Sanitise::Long($thistrackeeid));
                        $this->_databaseManager->executeQuery($SQL);                                                
                        
                    }
                }                
            }
        }
    }    
    
    public function getPendingCount($userid, $rectype)
    {
        $count = 0;
        $SQL = sprintf("SELECT * FROM messagemanage WHERE userid = %d AND rectype = %d AND status=0", $userid, $rectype);            
        $this->_databaseManager->executeQuery($SQL);                                
        while ($row = $this->_databaseManager->get_nextRow())
        {
            $count++;
        }
        return $count;        
    }
    
    public function getMessage($id)
    {
        $Query = sprintf("SELECT * FROM `messages` WHERE customerno=%d AND messageid='%d' LIMIT 1",
                $this->_Customerno,
                Sanitise::Long($id));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($row = $this->_databaseManager->get_nextRow()) 
        {
            $message = new VOMessage();
            $message->senderid = $row['senderid'];
            $message->type = $row['type'];
            return $message;            
        }
        return null;        
    }
    
    public function deletemessage($userid,$id)
    {
        $Query = sprintf("DELETE FROM messagemanage WHERE userid = %d AND customerno = %d AND messageid = %d",$userid,$this->_Customerno,$id);
        $this->_databaseManager->executeQuery($Query);		        
    }
}