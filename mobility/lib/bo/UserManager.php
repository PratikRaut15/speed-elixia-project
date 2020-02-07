<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/DatabaseManager.php';
include_once 'lib/bo/CustomerManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/system/Date.php';
include_once 'lib/model/VOUser.php';

class UserManager
{
	private $_databaseManager = null;
	private $_customerManager = null;

    public function UserManager()
    {
        // Constructor
        if ($this->_databaseManager == null) {
			$this->_databaseManager = new DatabaseManager();
		}
    }

    public function authenticate($username, $password)
    {
        $authenticatedUser = null;
        $authenticationQuery = sprintf("SELECT `userid`, `customerno` 
            FROM `user` WHERE `username`='%s' AND `password`=sha1('%s') limit 1"
            , Validator::escapeCharacters($username)
            , Validator::escapeCharacters($password));
        $this->_databaseManager->executeQuery($authenticationQuery);
        if ($this->_databaseManager->get_rowCount() > 0) 
        {            
            $row = $this->_databaseManager->get_nextRow();
            $authenticatedUser = $this->get_user($row['customerno'],$row['userid']);
            return $authenticatedUser;                        
        } 
        else 
        {
            return null;
        }		
    }

    public function get_user($customerid,$id) 
    {
        $user = null;
        $userDetailsQuery = sprintf("SELECT * FROM `user` INNER JOIN customer ON customer.customerno = user.customerno where user.customerno=%d AND `userid`='%d' LIMIT 1",
            Validator::escapeCharacters($customerid),
            Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($userDetailsQuery);		
		
        if ($row = $this->_databaseManager->get_nextRow()) 
        {
            $user = new VOUser();
            $user->id = $row['userid'];
            $user->username = $row['username'];
            $user->realname = $row['realname'];
            $user->role = $row['role'];            
            $user->email = $row['email'];
            $user->password = $row['password'];            
            $user->phone = $row['phone'];            
            $user->lastvisit = $row['lastvisit'];
            $user->visits = $row['visited'];
            $user->customerno = $row['customerno'];          
            $user->finditems = $row['finditems'];
            $user->itemdel = $row['itemdelivery'];            
            $user->fencing = $row['fencing'];
            $user->elixiacode = $row['elixiacode'];
            $user->messaging = $row['messaging'];
            $user->dateadded = $row['dateadded'];
            $user->service = $row['service'];            
            return $user;            
        }
        return null;
    }
    
    public function getusersforcustomer($customerid) 
    {
        $users = Array();
        $usersQuery = sprintf("SELECT * FROM `user` where customerno=%d",
            Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row["realname"] != "Elixir")
                {
                    $user = new VOUser();
                    $user->id = $row['userid'];
                    $user->username = $row['username'];
                    $user->role = $row['role'];
                    $user->email = $row['email'];        
                    $user->phone = $row['phone'];                        
                    $user->realname = $row['realname'];
                    $user->lastvisit = $row['lastvisit'];
                    $user->visits = $row['visited'];
                    $user->customerno = $row['customerno'];            
                    $user->mess_email = $row["mess_email"];
                    $user->mess_sms = $row["mess_sms"];                    
                    $users[] = $user;
                }
            }
            return $users;            
        }
        return null;
    }    
    
    public function getunfilteredusersforcustomer($customerid) 
    {
        $users = Array();
        $usersQuery = sprintf("SELECT * FROM `user` where customerno=%d",
            Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row["role"] == "Administrator")
                {
                    $user = new VOUser();
                    $user->id = $row['userid'];
                    $user->username = $row['username'];
                    $user->role = $row['role'];
                    $user->email = $row['email'];        
                    $user->phone = $row['phone'];                        
                    $user->realname = $row['realname'];
                    $user->lastvisit = $row['lastvisit'];
                    $user->visits = $row['visited'];
                    $user->customerno = $row['customerno'];            
                    $user->mess_email = $row["mess_email"];
                    $user->mess_sms = $row["mess_sms"];                    
                    $users[] = $user;
                }
            }
            return $users;            
        }
        return null;
    }        
    
    public function getallusers() 
    {
        $users = Array();
        $usersQuery = sprintf("SELECT * FROM `user`");
        $this->_databaseManager->executeQuery($usersQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $user = new VOUser();
                $user->id = $row['userid'];
                $user->username = $row['username'];
                $user->role = $row['role'];
                $user->email = $row['email'];        
                $user->phone = $row['phone'];                        
                $user->realname = $row['realname'];
                $user->lastvisit = $row['lastvisit'];
                $user->visits = $row['visited'];
                $user->customerno = $row['customerno'];            
                $users[] = $user;
            }
            return $users;            
        }
        return null;
    }        

    public function updatevisit($customerno, $userid )
    {
        $today = date("Y-m-d H:i:s");        
        $sql =sprintf("UPDATE user SET lastvisit='%s', visited=visited+1 where userid = %d AND customerno= %d LIMIT 1", Sanitise::DateTime($today), $userid, $customerno);
        $this->_databaseManager->executeQuery($sql);
    }
    
    public function SaveUser($user)
    {
        if(!isset($user->userid))
        {
            $this->Insert($user);
        }
        else
        {
            $this->Update($user);
        }
    }

    private function Insert($user)
    {
        $userkey = mt_rand();            
        $SQL = sprintf( "INSERT INTO user
                        (`customerno`,`realname`,`username`,`password`,`role`,`email`,`phone`,`userkey`) VALUES
                        ( '%d','%s','%s',sha1('%s'),'%s','%s','%s','%s')",
        Sanitise::Long($user->customerno),                
        Sanitise::String($user->realname),                
        Sanitise::String($user->username),
        Sanitise::String($user->password),
        Sanitise::String($user->role),
        Sanitise::String($user->email),
        Sanitise::String($user->phone),
        Sanitise::String($userkey));        
        $this->_databaseManager->executeQuery($SQL);
        $user->userid = $this->_databaseManager->get_insertedId();
    }
    
    private function Update($user)
    {
        if(strlen(trim($user->password)) > 0)
        {
        $SQL = sprintf( "Update user
                        Set `realname`='%s',
                        `password`=sha1('%s'),
                        `role`='%s',
                        `email`='%s',                        
                        `phone`='%s'                                                
                        WHERE userid = %d AND customerno = %d",
                        Sanitise::String( $user->realname),
                        Sanitise::String( $user->password),
                        Sanitise::String( $user->role),
                        Sanitise::String( $user->email),                
                        Sanitise::String( $user->phone),                                
                        Sanitise::Long( $user->userid),
                        Sanitise::Long( $user->customerno));
        
        }
        else
        {
        $SQL = sprintf( "Update user
                        Set `realname`='%s',
                        `role`='%s',
                        `email`='%s',                        
                        `phone`='%s'                                                                        
                        WHERE userid = %d AND customerno = %d",
                        Sanitise::String( $user->realname),
                        Sanitise::String( $user->role),
                        Sanitise::String( $user->email),                
                        Sanitise::String( $user->phone),                                
                        Sanitise::Long( $user->userid),
                        Sanitise::Long( $user->customerno));
        }
        $this->_databaseManager->executeQuery($SQL);
    }
    
    public function DeleteUser($userid, $customerno)
    {
        $SQL = sprintf("DELETE FROM user where userid = %d and customerno = %d",
                        Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);        
    }    
}
?>