<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOCommunicationQueue.php';
include_once '../../lib/system/Date.php';

class CommunicationQueueManager
{
	private $_databaseManager = null;
	private $_customerManager = null;

    function __construct()
    {
        // Constructor.
        if ($this->_databaseManager == null) {
			$this->_databaseManager = new DatabaseManager();
		}
    }

    public function InsertQ($queue)
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

    public function Insert($type,$email,$phone=Null,$subject,$message,$customerno=null)
    {
        $today = date("Y-m-d H:i:s");
        $SQL = sprintf( "INSERT INTO communicationqueue
                        (`type`,`email`,`phone`,`subject`,`message`,`datecreated`,`customerno`) VALUES
                        ( '%d','%s','%s','%s','%s','%s','%d')",
        Sanitise::Long($type),                
        Sanitise::String($email),                
        Sanitise::String($phone),
        Sanitise::String($subject),
        Sanitise::String($message),
        Sanitise::DateTime($today),
        Sanitise::Long($customerno));        
        $this->_databaseManager->executeQuery($SQL);
    }
    
    public function InsertHistory($queue)
    {
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO communicationhistory
                        (`type`,`email`,`phone`,`subject`,`message`,`datesent`,`confirmation`,`sent_error`,`queueid`,`datecreated`,`customerno`) VALUES
                        ( '%d','%s','%s','%s','%s','%s','%d','%d','%d','%s','%d')",
        Sanitise::Long($queue->type),                
        Sanitise::String($queue->email),                
        Sanitise::String($queue->phone),
        Sanitise::String($queue->subject),
        Sanitise::String($queue->message),
        Sanitise::DateTime($today),                
        Sanitise::Long($queue->confirmation),                
        Sanitise::Long($queue->senderror),                
        Sanitise::Long($queue->queueid),
        Sanitise::DateTime($queue->datecreated),
        Sanitise::Long($queue->customerno));        
        $this->_databaseManager->executeQuery($SQL);
    }    
    
    public function GetHistory($customerno)
    {
        $queue = Array();
//        $date = new Date();
//        $today = $date->add_hours(date("Y-m-d H:i:s"), 11.5);
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = '%'.date("Y-m-d", $dt).'%';
        $queueQuery = sprintf("SELECT * FROM `communicationhistory` WHERE `customerno` = %d AND `datecreated` NOT LIKE '%s'", Sanitise::Long($customerno), Sanitise::String($date));
        $this->_databaseManager->executeQuery($queueQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->id = $row['id'];
                $singlequeue->queueid = $row['queueid'];
                $singlequeue->type = $row['type'];
                $singlequeue->email = $row['email'];
                $singlequeue->phone = $row['phone'];
                $singlequeue->subject = $row['subject'];                
                $singlequeue->message = $row['message'];                
                $singlequeue->datecreated = $row['datecreated'];                   
                $singlequeue->datesent = $row['datesent'];                      
                $singlequeue->confirmation = $row['confirmation'];                    
                $singlequeue->sent_error = $row['sent_error'];       
                $singlequeue->customerno = $row['customerno'];
                $queue[] = $singlequeue;
            }
            return $queue;            
        }
        return null; 
    } 
    
    public function DeleteHistory($customerno)
    {
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = '%'.date("Y-m-d", $dt).'%';
        $queueQuery = sprintf("DELETE FROM `communicationhistory` WHERE `customerno` = %d AND `datecreated` NOT LIKE '%s'", Sanitise::Long($customerno), Sanitise::String($date));
        $this->_databaseManager->executeQuery($queueQuery);		
	
    } 
    
    public function getqueue()
    {
        $queue = Array();
        $queueQuery = sprintf("SELECT * FROM `communicationqueue`");
        $this->_databaseManager->executeQuery($queueQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->id = $row['id'];
                $singlequeue->type = $row['type'];
                $singlequeue->email = $row['email'];
                $singlequeue->phone = $row['phone'];
                $singlequeue->subject = $row['subject'];                
                $singlequeue->message = $row['message'];                
                $singlequeue->datecreated = $row['datecreated'];       
                $singlequeue->customerno = $row['customerno'];
                $queue[] = $singlequeue;
            }
            return $queue;            
        }
        return null;        
    }

    public function getnotifs($customerno, $type)
    {
        $queue = Array();
        $queueQuery = sprintf("SELECT * FROM `notifications` WHERE customerno = %d AND isnotified = 0 AND type = %d", Sanitise::Long($customerno), Sanitise::Long($type));
        $this->_databaseManager->executeQuery($queueQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->notif = $row['notification'];                
                $queue[] = $singlequeue;
            }
            return $queue;            
        }
        return null;        
    }

    public function getalerts($customerno)
    {
        $today = date("Y-m-d H:i:s");
        $time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        $queue = Array();
	
        $devicesQuery = "SELECT comqueue.message FROM `comqueue`"; 
        
        if($_SESSION['groupid'] != 0){
        $devicesQuery .= "INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid ";
        $devicesQuery .= "WHERE comqueue.customerno = %d AND comqueue.is_shown = '0' AND vehicle.groupid=%d AND `timeadded` BETWEEN '%s' and '%s'";
        $queueQuery = sprintf($devicesQuery, Sanitise::Long($customerno), Sanitise::Long($_SESSION['groupid']), Sanitise::DateTime($time), Sanitise::DateTime($today));
        }
        else{
        $devicesQuery .= "WHERE customerno = %d AND `is_shown` = '0' AND `timeadded` BETWEEN '%s' and '%s'";
        $queueQuery = sprintf($devicesQuery, Sanitise::Long($customerno), Sanitise::DateTime($time), Sanitise::DateTime($today));
        }
        $this->_databaseManager->executeQuery($queueQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->notif = $row['message'];                
                $queue[] = $singlequeue;
            }
            return $queue;            
        }
        return null;        
    }

    public function getnews($notid)
    {
        $queueQuery = sprintf("SELECT notification FROM `notifications` WHERE notid = %d", Sanitise::Long($notid));
        $this->_databaseManager->executeQuery($queueQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->notif = $row['notification'];                
            }
            return $singlequeue;            
        }
        return null;        
    }

    public function getcompany($id)
    {
        $queueQuery = sprintf("SELECT customercompany FROM `customer` WHERE customerno = %d AND customerno <> 1", Sanitise::Long($id));
        $this->_databaseManager->executeQuery($queueQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->company = $row['customercompany'];                
            }
            return $singlequeue;            
        }
        return null;        
    }
    
    public function getrandnews()
    {
        $queue = Array();
        $queueQuery = sprintf("SELECT notid FROM `notifications` WHERE type = 1");
        $this->_databaseManager->executeQuery($queueQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $queue[] = $row["notid"];
            }
            return $queue;            
        }
        return null;        
    }

    public function getrandtips()
    {
        $queue = Array();
        $queueQuery = sprintf("SELECT notid FROM `notifications` WHERE type = 2");
        $this->_databaseManager->executeQuery($queueQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $queue[] = $row["notid"];
            }
            return $queue;            
        }
        return null;        
    }

    public function getrandlinks()
    {
        $queue = Array();
        $queueQuery = sprintf("SELECT notid FROM `notifications` WHERE type = 3");
        $this->_databaseManager->executeQuery($queueQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $queue[] = $row["notid"];
            }
            return $queue;            
        }
        return null;        
    }
    
    public function getrandcust()
    {
        $queue = Array();
        $queueQuery = sprintf("SELECT customerno FROM `customer`");
        $this->_databaseManager->executeQuery($queueQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $queue[] = $row["customerno"];
            }
            return $queue;            
        }
        return null;        
    }    
    
    public function setnotif($customerno, $type)
    {
        $SQL = sprintf("Update notifications Set isnotified = 1 WHERE customerno = %d AND type = %d", Sanitise::Long($customerno), Sanitise::Long($type));
        $this->_databaseManager->executeQuery($SQL);                
    }   
    
    public function setalert($customerno)
    {
        $SQL = sprintf("Update comqueue Set is_shown = 1 WHERE customerno = %d", Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);                
    }
    
    
    
    public function DeleteQueue($queueid)
    {
        $SQL = sprintf("DELETE FROM communicationqueue where id = %d",
                        Sanitise::Long($queueid));
        $this->_databaseManager->executeQuery($SQL);        
    }    
}
?>