<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/model/VORecipient.php';

class RecipientManager extends VersionedManager
{

    public function RecipientManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveRecipient($recipient)
    {
        if(!isset($recipient->recipientid))
        {
            $this->Insert($recipient);
        }
        else
        {
            $this->Update($recipient);
        }
    }

    private function Insert($recipient)
    {
        $SQL = sprintf( "INSERT INTO recipient
                        (`customerno`,`rname`,`rphone`,`remail`) VALUES
                        ( '%d','%s','%s','%s')",
        $this->_Customerno,
        Sanitise::String($recipient->rname),
        Sanitise::String($recipient->rphone),
        Sanitise::String($recipient->remail));
        $this->_databaseManager->executeQuery($SQL);
        $recipient->recipientid = $this->_databaseManager->get_insertedId();
    }
    
    public function get_recipient($id) 
    {
        $recipient = null;
        $recipientDetailsQuery = sprintf("SELECT * FROM `recipient` where customerno=%d AND `recipientid`='%d' LIMIT 1",
                $this->_Customerno,
            Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($recipientDetailsQuery);		
		
        if ($row = $this->_databaseManager->get_nextRow()) 
        {
            $recipient = new VORecipient();
            $recipient->recipientid = $row['recipientid'];
            $recipient->rname = $row['rname'];
            $recipient->rphone = $row['rphone'];            
            $recipient->remail = $row['remail'];            
            return $recipient;            
        }
        return null;
    }
    
    public function getrecipientsforcustomer() 
    {
        $recipients = Array();
        $recipientsQuery = sprintf("SELECT * FROM `recipient` where customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($recipientsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $recipient = new VORecipient();
                $recipient->recipientid = $row['recipientid'];
                $recipient->rname = $row['rname'];
                $recipient->rphone = $row['rphone'];            
                $recipient->remail = $row['remail'];            
                $recipients[] = $recipient;
            }
            return $recipients;            
        }
        return null;
    }        

    private function Update($recipient)
    {
        $SQL = sprintf( "Update recipient
                        Set `rname`='%s',
                        `rphone`='%s',
                        `remail`='%s'                                                
                        WHERE reipientid = %d AND customerno = %d",
                        Sanitise::String( $recipient->rname),
                        Sanitise::String( $recipient->rphone),
                        Sanitise::String( $recipient->remail),                               
                        Sanitise::Long( $recipient->recipientid),                
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeleteRecipient($recipientid)
    {
        $SQL = sprintf("DELETE FROM recipient where recipientid = %d and customerno = %d",
                        Sanitise::Long($recipientid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }
}