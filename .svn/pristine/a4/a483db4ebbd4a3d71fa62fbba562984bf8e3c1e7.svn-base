<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/DatabaseManager.php';
include_once 'lib/bo/CustomerManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/model/VOCommunicationQueue.php';
include_once 'lib/system/Date.php';

class CommunicationQueueManager
{
	private $_databaseManager = null;
	private $_customerManager = null;

    public function CommunicationQueueManager()
    {
        // Constructor.
        if ($this->_databaseManager == null) {
			$this->_databaseManager = new DatabaseManager();
		}
    }


    public function Insert($queue)
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
        Sanitise::Long($queue->senterror),                
        Sanitise::Long($queue->queueid),
        Sanitise::DateTime($queue->datecreated),
        Sanitise::Long($queue->customerno));        
        $this->_databaseManager->executeQuery($SQL);
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
    
    public function DeleteQueue($queueid)
    {
        $SQL = sprintf("DELETE FROM communicationqueue where id = %d",
                        Sanitise::Long($queueid));
        $this->_databaseManager->executeQuery($SQL);        
    }    
}
?>