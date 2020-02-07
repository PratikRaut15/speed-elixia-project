<?php
include_once 'lib/model/VOCustomer.php';
include_once 'lib/system/Validator.php';
include_once 'lib/system/DatabaseManager.php';
include_once 'lib/system/Date.php';
include_once 'lib/system/Sanitise.php';

class CustomerManager {

    private $_databaseManager = null;

    function CustomerManager()
    {
        if ($this->_databaseManager == null)
        {
            $this->_databaseManager = new DatabaseManager();
        }
    }

    public function customerAccepted($id)
    {
        $SQL = sprintf("SELECT agreedby, agreeddate FROM customer WHERE customerno= %d AND (agreedby <> '')", $id);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getcustomerimages($id)
    {
        $SQL = sprintf("SELECT c.logoimage, c.bannerimage FROM customer c
                        WHERE customerno = %d", $id);
        $this->_databaseManager->executeQuery($SQL);
        if ($row = $this->_databaseManager->get_nextRow())
        {
            $gotcustomer = new VOCustomer();
            $gotcustomer->logoimage = $row["logoimage"];
            $gotcustomer->bannerimage = $row["bannerimage"];
            return $gotcustomer;
        }
        return false;
    }
    
    public function getcustomerinfo($id)
    {
        $SQL = sprintf("SELECT * FROM customer c
                        WHERE customerno = %d", $id);
        $this->_databaseManager->executeQuery($SQL);
        if ($row = $this->_databaseManager->get_nextRow())
        {
            $gotcustomer = new VOCustomer();
            $gotcustomer->customername = $row["customername"];
            $gotcustomer->customercompany = $row["customercompany"];
            $gotcustomer->customeradd1 = $row["customeradd1"];
            $gotcustomer->customeradd2 = $row["customeradd2"];
            $gotcustomer->customercity = $row["customercity"];
            $gotcustomer->customerstate = $row["customerstate"];
            $gotcustomer->customerzip = $row["customerzip"];
            $gotcustomer->customerphone = $row["customerphone"];                        
            $gotcustomer->customercell = $row["customercell"];                                    
            $gotcustomer->customeremail = $row["customeremail"];                                                
            return $gotcustomer;
        }
        return false;
    }    
    
    public function acceptCustomer($id, $username)
    {
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf("UPDATE customer SET agreedby='%s', agreeddate='%s' WHERE customerno=%d", $username, Sanitise::DateTime($today), $id);
        $this->_databaseManager->executeQuery($SQL);
    }
    
    public function getcustomernos()
    {
        $customernos = Array();
        $SQL = sprintf("SELECT customerno FROM customer");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $customerno = $row["customerno"];
                $customernos[] = $customerno;            
            }                
            return $customernos;            
        }
        return false;
    }    
    
    public function ModifyCustomer($customer)
    {
        $SQL = sprintf("UPDATE customer 
            SET customername='%s', 
            customercompany='%s',
            customeradd1='%s',
            customeradd2='%s',
            customercity='%s',
            customerstate='%s',
            customerzip='%s',
            customerphone='%s',
            customeremail='%s',
            customercell='%s'           
            WHERE customerno=%d",  
                $customer->customername,
                $customer->customercompany,
                $customer->customeradd1,
                $customer->customeradd2,
                $customer->customercity,
                $customer->customerstate,
                $customer->customerzip,
                $customer->customerphone,
                $customer->customeremail,
                $customer->customercell,
                $customer->customerno);
        $this->_databaseManager->executeQuery($SQL);
    }    
}
?>