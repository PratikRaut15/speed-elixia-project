<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/system/Date.php';
include_once 'lib/model/VOClient.php';

class ClientManager extends VersionedManager
{
    public function ClientManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveClient($client)
    {
        if(!isset($client->clientid))
        {
            $this->InsertClient($client);
        }
        else
        {
            $this->UpdateClient($client);
        }
    }    
    
    private function UpdateClient($client)
    {
        $SQL = sprintf( "Update client
                        Set `clientname`='%s',
                        `add1`='%s',
                        `add2`='%s',
                        `phoneno`='%s',
                        `city`='%s',
                        `state`='%s',                        
                        `zip`='%s',
                        `maincontact`='%s',
                        `email`='%s',
                        `extra`='%s',                        
                        `iscall`='%d',                                                
                        `userid`='%d'                        
                        WHERE clientid = %d AND customerno = %d",
                        Sanitise::String( $client->clientname),
                        Sanitise::String( $client->add1),
                        Sanitise::String( $client->add2),
                        Sanitise::String( $client->phone),
                        Sanitise::String( $client->city),
                        Sanitise::String( $client->state),                
                        Sanitise::String( $client->zip),                
                        Sanitise::String( $client->contact), 
                        Sanitise::String( $client->email),
                        Sanitise::String( $client->cliextra),                
                        Sanitise::Long( $client->iscall),                                
                        Sanitise::Long( $client->userid),                
                        Sanitise::Long( $client->clientid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    private function InsertClient($client)
    {
        $SQL = sprintf( "INSERT INTO client
                        (`clientname`,`add1`,`add2`,`phoneno`
                        ,`city`,`state`,`zip`,`maincontact`,`customerno`,`email`,`userid`,`isdeleted`,`extra`,`iscall`) VALUES
                        ( '%s','%s','%s','%s','%s','%s','%s','%s','%d','%s','%d',0, '%s', '%d')",
        Sanitise::String($client->clientname),
        Sanitise::String($client->add1),
        Sanitise::String($client->add2),
        Sanitise::String($client->phone),
        Sanitise::String($client->city),
        Sanitise::String($client->state),
        Sanitise::String($client->zip),                
        Sanitise::String($client->maincontact),
                $this->_Customerno,
        Sanitise::String($client->email),
        Sanitise::Long($client->userid),
        Sanitise::String($client->cliextra),
        Sanitise::Long($client->iscall));
        $this->_databaseManager->executeQuery($SQL);
        $client->clientid = $this->_databaseManager->get_insertedId();        
    }    
    
    public function getclients($iscall)
    {
        $clients = Array();
        $Query = sprintf("SELECT *, client.email as semail FROM `client`
            INNER JOIN user ON user.userid = client.userid
            WHERE client.customerno=%d AND client.isdeleted = 0 AND client.iscall=%d",
            $this->_Customerno, Sanitise::Long($iscall));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $client = new VOClient();
                $client->clientid = $row['clientid'];            
                $client->clientname = $row['clientname'];            
                $client->add1 = $row['add1'];            
                $client->add2 = $row['add2'];            
                $client->phone = $row['phoneno'];            
                $client->city = $row['city'];            
                $client->state = $row['state'];            
                $client->zip = $row['zip'];                            
                $client->maincontact = $row['maincontact'];                            
                $client->email = $row['semail'];                                            
                $client->username = $row['username'];    
                $client->extra = $row['extra'];    
				      
                $clients[] = $client;
            }
            return $clients;            
        }
        return null;                        
    }    
    
    public function getclient($clientid, $iscall)
    {
        $Query = sprintf("SELECT * FROM `client` left outer join `client_type` on `client_type`.type_id= `client`.type_id  WHERE `client`.customerno=%d AND `client`.clientid=%d AND `client`.isdeleted = 0 AND `client`.iscall=%d",
            $this->_Customerno, Sanitise::Long($clientid), Sanitise::Long($iscall));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $client = new VOClient();
                $client->clientid = $row['clientid'];            
                $client->clientname = $row['clientname'];            
                $client->add1 = $row['add1'];            
                $client->add2 = $row['add2'];            
                $client->phone = $row['phoneno'];            
                $client->city = $row['city'];            
                $client->state = $row['state'];            
                $client->zip = $row['zip'];                            
                $client->maincontact = $row['maincontact'];                            
                $client->email = $row['email'];                                            
                $client->extra = $row['extra'];
				if($row['type_name']!=null){
				$client->type_name =$row['type_name'];
				}else{
				$client->type_name =$row['type_name'];
				}
				if($row['type_id']!=null){
					$client->type_id=$row['type_id'];
				}else{
					$client->type_id="1";
				}
				if($row['ischargable']!=null){
				$client->ischargable =$row['ischargable'];
				}else{
				$client->ischargable ="1";
				}
				$client->form_type_id =$row['form_type_id'];
				
				
				                                                          
            }
            return $client;            
        }
        return null;                        
    }        
    
    public function getclientsfromphrase($phrase, $iscall) 
    {
        $clients = Array();
        if(isset($phrase))
        {
      		$phrasesearch .= " AND CONCAT(client.phoneno, client.clientname) LIKE '%".$phrase."%'   ";
		}        
      	$Query = sprintf("SELECT *   FROM `client` WHERE client.customerno=%d AND client.isdeleted = 0 AND client.iscall=%d ",
        $this->_Customerno, Sanitise::Long($iscall));
          $Query.=$phrasesearch;
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $client = new VOClient();
                $client->id = $row['clientid'];
                $client->name = $row['phoneno']."-".$row['clientname'];
				$clients[] = $client;
            }
            return $clients;            
        }
        return null;
    }            
    
    public function getclientsfromextraphrase($phrase, $iscall) 
    {
        $clients = Array();
        if(isset($phrase))
        {
            $phrasesearch .= sprintf( " AND (client.extra LIKE '%%%s%%')",$phrase);
        }        
        $Query = sprintf("SELECT * FROM `client` WHERE client.customerno=%d AND client.isdeleted = 0 AND client.iscall=%d",
            $this->_Customerno, Sanitise::Long($iscall));
        $Query.=$phrasesearch;
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $client = new VOClient();
                $client->id = $row['clientid'];
                $client->name = $row['extra'];
                $clients[] = $client;
            }
            return $clients;            
        }
        return null;
    }                
    
    public function DeleteClient($clientid)
    {
        $SQL = sprintf( "Update client
                        Set `isdeleted`=1
                        WHERE customerno = %d AND clientid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($clientid));
        $this->_databaseManager->executeQuery($SQL);                                                                        
    }

    
}