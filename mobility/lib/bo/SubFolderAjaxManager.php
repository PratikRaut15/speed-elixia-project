<?php
include_once '../lib/system/Validator.php';
include_once '../lib/system/VersionedManager.php';
include_once '../lib/system/Sanitise.php';
include_once '../lib/system/Date.php';
include_once '../lib/model/VOCustomer.php';
include_once '../lib/model/VODevices.php';

class SubFolderAjaxManager extends VersionedManager
{
    public function ServiceManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }
    
    public function pulldatafromcustomer()
    {        
        $SQL = sprintf("SELECT c.customername, c.customercompany, c.customerphone, c.customeremail FROM customer c
                        WHERE customerno = %d", $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($row = $this->_databaseManager->get_nextRow())
        {
            $data = new VOCustomer();
            $data->name = $row["customername"];
            $data->company = $row["customercompany"];
            $data->phone = $row["customerphone"];
            $data->email = $row["customeremail"];
            return $data;
        }        
    }    
    
    public function pulldevicerate($deviceid)
    {        
        $SQL = sprintf("SELECT rate FROM devices
                        WHERE devicekey = %d AND customerno = %d", $deviceid, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($row = $this->_databaseManager->get_nextRow())
        {
            $data = new VODevices();
            $data->rate = $row["rate"];
            return $data;
        }
    }      
    
    public function getreceiptsamount()
    {
        $total = 0;
        $SQL = sprintf("SELECT amount FROM receipt WHERE approval = 1 AND type='License' AND customerno = %d",$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $total = $total + $row["amount"];
            }
            return $total;
        }
        return 0;
    }
    
    public function getpendingamount()
    {
        $total = 0;
        $SQL = sprintf("SELECT pendingamount FROM devices WHERE isregistered = 1 AND customerno = %d",$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $total = $total + $row["pendingamount"];
            }
            return $total;
        }
        return 0;
    }    
    
    public function getdevices()
    {
        $devices = array();
        $SQL = sprintf("SELECT * FROM devices WHERE customerno = %d",$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->actualstartdate = $row["registrationapprovedon"];
                $device->contract = $row["contract"];
                $device->rate = $row["rate"];
                $device->deviceid = $row["deviceid"];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }
    
    public function calculatepndgamt($startdate, $contract, $month, $rate, $year, $deviceid)
    {
            $datetime1 = strtotime($startdate);
            $datetime2 = strtotime($year."-".$month."-01");
            $diffmonth = date('n',$datetime2) + ((date('y',$datetime2) - date('y',$datetime1))*12) - date('n', $datetime1);
            if($diffmonth == 0)
            {
                $day1 = date('d',$datetime1);
                $pending = ($rate/30) * (30 - $day1);                
                $SQL = sprintf( "Update devices
                                Set `pendingamount`='%f'
                                WHERE deviceid = %d AND customerno = %d",
                                Sanitise::Float($pending),
                                Sanitise::Long($deviceid),
                                $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);                        
            }
            elseif($diffmonth > 0)
            {
                if($diffmonth != $contract)
                {
                    $day1 = date('d',$datetime1);
                    $pending = (($rate/30) * (30 - $day1)) + ($diffmonth * $rate);                
                    $SQL = sprintf( "Update devices
                                    Set `pendingamount`='%f'
                                    WHERE deviceid = %d AND customerno = %d",
                                    Sanitise::Float($pending),
                                    Sanitise::Long($deviceid),
                                    $this->_Customerno);
                    $this->_databaseManager->executeQuery($SQL);                                                        
                }
                else
                {
                    $day1 = date('d',$datetime1);
                    $pending = ($rate/30) * $day1;                
                    $SQL = sprintf( "Update devices
                                    Set `pendingamount`='%f'
                                    WHERE deviceid = %d AND customerno = %d",
                                    Sanitise::Float($pending),
                                    Sanitise::Long($deviceid),
                                    $this->_Customerno);
                    $this->_databaseManager->executeQuery($SQL);                                                                            
                }
            }
            else
            {
                $pending = 0;
                $SQL = sprintf( "Update devices
                                Set `pendingamount`='%f'
                                WHERE deviceid = %d AND customerno = %d",
                                Sanitise::Float($pending),
                                Sanitise::Long($deviceid),
                                $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);                                                                            
            }
    }
}