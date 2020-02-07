<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VODevices.php';
include_once '../../lib/system/Date.php';

class DeviceManager extends VersionedManager
{
    public function DeviceManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }
        
    public function getlastupdateddatefordevices($customerno)
    {
        $devices = array();
        $Query = "SELECT unit.unitno, devices.deviceid,vehicle.vehicleid,
            devices.uid, vehicle.vehicleno, devices.lastupdated, devices.customerno from devices
            INNER JOIN unit ON unit.uid = devices.uid            
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            where devices.customerno = %d AND unit.trans_statusid <> 10";
        $devicesQuery = sprintf($Query,$customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if($this->_databaseManager->get_rowCount()>0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleid = $row['vehicleid'];
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->vehicleno = $row['vehicleno'];
                $device->lastupdated = $row['lastupdated'];
                $device->customerno = $row['customerno'];
                $devices[] = $device;
            }
            return $devices;
        }
    }

    public function getlastupdateddatefordevicesreason($customerno)
    {
        $devices = array();
        $Query = "SELECT unit.unitno, devices.deviceid,vehicle.vehicleid,
            devices.uid, vehicle.vehicleno, devices.lastupdated, devices.customerno, devices.ignition, devices.powercut, devices.tamper, devices.gsmstrength, devices.gprsregister, customer.customercompany, devices.phone from devices
            INNER JOIN unit ON unit.uid = devices.uid            
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN customer ON devices.customerno = customer.customerno
            where devices.customerno = %d AND unit.trans_statusid <> 10";
        $devicesQuery = sprintf($Query,$customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if($this->_databaseManager->get_rowCount()>0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleid = $row['vehicleid'];
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->vehicleno = $row['vehicleno'];
                $device->lastupdated = $row['lastupdated'];
                $device->customerno = $row['customerno'];
                $device->ignition = $row['ignition'];
                $device->powercut = $row['powercut'];                
                $device->tamper = $row['tamper'];                                
                $device->gsmstrength = $row['gsmstrength'];                                                
                $device->gprsregister = $row['gprsregister'];                                                                
                $device->customercompany = $row['customercompany'];                                                                                
                $device->phone = $row['phone'];                                                                                                
                $devices[] = $device;
            }
            return $devices;
        }
    }
    
    public function get_all_devices() 
    {
        $devices = Array();
        $Query = "SELECT devices.deviceid,
            devices.devicekey,simcard.simcardno as phone,devices.expirydate,devices.uid,unit.unitno,
            registeredon, Now() as today FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid           
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            LEFT OUTER JOIN simcard ON devices.simcardid = simcard.id
            where devices.customerno=%d AND unit.trans_statusid <> 10";
        
        if($_SESSION['groupid'] != '0')
        $Query.=" AND vehicle.groupid =%d";
        
        if($_SESSION['groupid'] != '0')
        $devicesQuery = sprintf($Query,$this->_Customerno,$_SESSION['groupid']);
        else
        $devicesQuery = sprintf($Query,$this->_Customerno);
            
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->phone = $row['phone'];
                $device->today = $row["today"];
                $device->expirydate = $row["expirydate"];
                $device->registeredon = $row["registeredon"];                
                $devices[] = $device;
            }
            return $devices;            
        }
        return null;
    }
    
    public function get_device($uid) 
    {
        $devices = Array();
        $Query = "SELECT devices.deviceid,simcard.simcardno,
            devices.devicekey,devices.phone,devices.expirydate,devices.uid,unit.unitno,
            registeredon, Now() as today FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
            where devices.customerno=%d and devices.uid=%d AND unit.trans_statusid <> 10";
        $devicesQuery = sprintf($Query,$this->_Customerno,  Sanitise::Long($uid));
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->phone = $row['simcardno'];
                $device->today = $row["today"];
                $device->expirydate = $row["expirydate"];
                $device->registeredon = $row["registeredon"];                
            }
            return $device;            
        }
        return null;
    }
    
    public function getalldevicesformonitoring()
    {
        $devices = Array();
        $Query = "select d.deviceid, d.devicelat, d.devicelong,
            v.vehicleid, v.vehicleno, ea.overspeed AS overspeed_status,
            ea.tamper AS tamper_status, ea.powercut AS powercut_status, ea.ac AS ac_status,
            ia.status AS email_status, ea.temp AS temp_status, d.status, d.powercut, d.tamper,
            u.acsensor, u.digitalio, d.ignition, ia.count, ia.last_check, ia.last_status,
            d.lastupdated, d.uid, u.analog1_sen, u.analog1, a.artname, a.maxtemp, a.mintemp,
            u.analog2_sen, u.analog2, u.analog4, aa.aci_status, u.is_ac_opp
            FROM devices d
            INNER JOIN unit u ON u.uid = d.uid            
            INNER JOIN vehicle v on v.vehicleid = u.vehicleid
            INNER JOIN eventalerts ea ON ea.vehicleid = v.vehicleid
            INNER JOIN ignitionalert ia ON ia.vehicleid = v.vehicleid
            LEFT OUTER JOIN articlemanage am ON am.vehicleid = v.vehicleid
            LEFT OUTER JOIN article a ON a.artid = am.artid
            LEFT OUTER JOIN acalerts aa ON aa.vehicleid = v.vehicleid
            where d.customerno = %d AND unit.trans_statusid <> 10";
        $devicesQuery = sprintf($Query,$this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row['uid']>0)
                {
                    $device = new VODevices();
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->overspeedstatus = $row['overspeed_status'];
                    $device->tamperstatus = $row['tamper_status'];
                    $device->powercutstatus = $row['powercut_status']; 
                    $device->status = $row['status'];
                    $device->powercut = $row['powercut'];
                    $device->tamper = $row['tamper'];
                    $device->acsensor = $row['acsensor'];
                    $device->digitalio = $row['digitalio'];                    
                    $device->ac_status = $row['ac_status']; 
                    $device->ignition = $row['ignition'];
                    $device->ignition_status = $row['count'];
                    $device->ignition_last_status = $row['last_status'];
                    $device->ignition_last_check = $row['last_check'];
                    $device->ignition_email_status = $row['email_status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->uid = $row['uid'];
                    $device->analog1_sen = $row['analog1_sen'];
                    if($device->analog1_sen==1)
                    {
                        $device->temp = $row['analog1'];
                        $device->artname = $row['artname'];
                        $device->maxtemp = $row['maxtemp'];
                        $device->mintemp = $row['mintemp'];
                    }
                    $device->analog2_sen = $row['analog2_sen'];
                    if($device->analog2_sen==1)
                    {
                        $device->temp = $row['analog2'];
                        $device->artname = $row['artname'];
                        $device->maxtemp = $row['maxtemp'];
                        $device->mintemp = $row['mintemp'];
                    }
                    $device->analog4 = $row['analog4'];
                    $device->temp_status = $row['temp_status'];
                    $device->aci_status = $row['aci_status'];
                    $device->is_ac_opp = $row['is_ac_opp'];                    
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }
       
    public function getalldevicesformonitoringwithchk()
    {
        $devices = Array();
        $Query = "select devices.deviceid,devices.devicelat,devices.devicelong,
            vehicle.vehicleid,vehicle.vehicleno,checkpoint.cgeolat,checkpoint.cgeolong,
            checkpointmanage.conflictstatus,checkpoint.cname,checkpointmanage.cmid,checkpoint.crad,
            devices.uid, checkpoint.checkpointid, devices.lastupdated from devices
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN checkpointmanage ON checkpointmanage.vehicleid = vehicle.vehicleid
            INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid
            where devices.customerno = %d AND checkpointmanage.isdeleted = 0 AND unit.trans_statusid <> 10";
        $devicesQuery = sprintf($Query,$this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                if($row['uid']>0)
                {
                    $device = new VODevices();
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->cgeolat = $row['cgeolat'];
                    $device->cgeolong = $row['cgeolong'];
                    $device->conflictstatus = $row['conflictstatus'];
                    $device->checkpointid = $row['checkpointid'];
                    $device->cname = $row['cname'];
                    $device->cmid = $row['cmid'];
                    $device->crad = $row['crad'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }
    
    public function checkforvalidity() 
    {
        $devices = Array();
        $Query = "SELECT deviceid,expirydate, Now() as today FROM `devices` where customerno=%d";
        $devicesQuery = sprintf($Query,$this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->today = $row["today"];
                $device->expirydate = $row["expirydate"];
                $devices[] = $device;
            }
            return $devices;            
        }
        return null;
    }
    
    public function devicesformapping() 
    {
        $devices = Array();
         $Query = "SELECT * FROM `devices` 
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            where devices.customerno=%d AND unit.trans_statusid <> 10";
         
         if($_SESSION['groupid'] != '0')
        $Query.=" AND vehicle.groupid =%d";
         
         $Query.=" ORDER BY vehicle.vehicleno desc";
	
        if($_SESSION['groupid'] != '0')
        $devicesQuery = sprintf($Query,$this->_Customerno,$_SESSION['groupid']);
        else
        $devicesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                if($row['uid']>0)
                {
                    if($row['devicelat']>0 & $row['devicelong']>0)
                    {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['type'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $devices[] = $device;
                    }
                }
            }
            return $devices;            
        }
        return null;
    }
    
    public function devicesformapping_acsensor() 
    {
        $devices = Array();
        $Query = "SELECT * FROM `devices` 
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid <> 10 ORDER BY devices.lastupdated ASC";
        $devicesQuery = sprintf($Query,$this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                if($row['uid']>0)
                {
                    if($row['devicelat']>0 & $row['devicelong']>0)
                    {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['type'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $devices[] = $device;
                    }
                }
            }
            return $devices;            
        }
        return null;
    }
    
    public function devicesformappingwithecode($ecodeid) 
    {
        $devices = Array();
        $Query = "SELECT vehicle.vehicleno,vehicle.vehicleid,devices.deviceid,
            devices.devicelat,devices.devicelong,driver.drivername,driver.driverphone,vehicle.curspeed,
            devices.lastupdated,vehicle.type,devices.ignition,devices.status,devices.directionchange,
            devices.uid,elixiacode.expirydate FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            INNER JOIN ecodeman ON ecodeman.vehicleid = vehicle.vehicleid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            where elixiacode.ecode=%d AND unit.trans_statusid <> 10";
        $devicesQuery = sprintf($Query, Sanitise::long($ecodeid));
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                if($row['uid']>0)
                {
                    if($row['devicelat']>0 & $row['devicelong']>0)
                    {
                        $checkstatus = $this->checkvalidity($row["expirydate"]);
                        if($checkstatus == true)
                        {
                            $device->vehicleno = $row['vehicleno'];
                            $device->vehicleid = $row['vehicleid'];
                            $device->deviceid = $row['deviceid'];
                            $device->devicelat = $row['devicelat'];
                            $device->devicelong = $row['devicelong'];
                            $device->drivername = $row['drivername'];
                            $device->driverphone = $row['driverphone'];
                            $device->curspeed = $row['curspeed'];
                            $device->lastupdated = $row['lastupdated'];
                            $device->type = $row['type'];
                            $device->ignition = $row['ignition'];
                            $device->status = $row['status'];
                            $device->directionchange = $row['directionchange'];
                            $devices[] = $device;
                        }
                    }
                }
            }
            return $devices;            
        }
        return null;
    }
    
    public function checkvalidity($expirydate)
    {
        $today = date('Y-m-d H:i:s');
        $today = add_hours($today, 0);
        if(strtotime($today)<=strtotime($expirydate))
        {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
    
    public function deviceformapping($vehicleid) 
    {
        $Query = "SELECT * FROM `devices` 
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            where devices.customerno=%d AND vehicle.vehicleid=%d AND unit.trans_statusid <> 10 ORDER BY devices.lastupdated ASC";
        $devicesQuery = sprintf($Query,$this->_Customerno,Sanitise::long($vehicleid));
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                if($row['uid']>0)
                {
                    if($row['devicelat']>0 & $row['devicelong']>0)
                    {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['type'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $device->extbatt = $row["extbatt"];
                        $device->inbatt = $row["inbatt"];
                        $device->gsmstrength = $row["gsmstrength"];                        
                        return $device;
                    }
                }
            }
        }
        return null;
    }

    public function deviceformappings() 
    {
        $devices = Array();
        $Query = "SELECT *, devices.lastupdated as dlastupdated FROM `devices` 
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            where devices.customerno=%d AND unit.trans_statusid <> 10";
        
        if($_SESSION['groupid'] != '0')
        $Query.=" AND vehicle.groupid =%d";
        
        $Query.=" ORDER BY devices.lastupdated ASC";
        
        if($_SESSION['groupid'] != '0')
        $devicesQuery = sprintf($Query,$this->_Customerno,$_SESSION['groupid']);
        else
        $devicesQuery = sprintf($Query,$this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                if($row['uid']>0)
                {
                    if($row['devicelat']>0 & $row['devicelong']>0)
                    {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['dlastupdated'];
                        $device->type = $row['type'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $device->tamper = $row["tamper"];
                        $device->curspeed = $row["curspeed"];
                        $device->unitno = $row["unitno"];
                        $device->powercut = $row["powercut"];
                        $device->msgkey = $row["msgkey"];
                        $device->acsensor = $row["acsensor"];
                        $device->digitalio = $row["digitalio"];
                        $device->isacopp = $row["is_ac_opp"];
                        $device->analog1 = $row["analog1"];
                        $device->analog2 = $row["analog2"];
                        $device->analog1_sen = $row["analog1_sen"];
                        $device->analog2_sen = $row["analog2_sen"];                                                
                        $devices[] = $device;
                    }
                }
            }
        }
        return $devices;
    }
    
    public function deviceformappingforecode($vehicleid) 
    {
        $Query = "SELECT * FROM `devices` 
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            where vehicle.vehicleid=%d AND unit.trans_statusid <> 10 ORDER BY devices.lastupdated ASC";
        $devicesQuery = sprintf($Query,  Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                if($row['uid']>0)
                {
                    if($row['devicelat']>0 & $row['devicelong']>0)
                    {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['type'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $devices[] = $device;
                    }
                }
            }
            return $device;            
        }
        return null;
    }
    
    public function deviceforfence($fenceid) 
    {
        $devices = Array();
        $Query = "SELECT * FROM `devices` 
            INNER JOIN vehicle ON vehicle.uid = devices.uid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            INNER JOIN fenceman ON vehicle.vehicleid = fenceman.vehicleid
            INNER JOIN fence ON fenceman.fenceid = fence.fenceid
            where devices.customerno=%d and fence.fenceid=%d";
        $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($fenceid));
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                if($row['uid']>0)
                {
                    if($row['devicelat']>0 & $row['devicelong']>0)
                    {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['type'];
                        $devices[] = $device;
                    }
                }
            }
            return $devices;            
        }
        return null;
    }
 
    public function getdevicesforrtd()
    {
        $devices = Array();
        $Query = "SELECT unit.acsensor, vehicle.vehicleno, devices.phone, unit.unitno, 
            devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,
            unit.digitalio, devices.gsmstrength, devices.lastupdated, devices.registeredon,
            unit.analog1_sen,unit.analog2_sen,devices.deviceid FROM `devices`
            INNER JOIN vehicle ON vehicle.uid = devices.uid
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            WHERE devices.`customerno` =%d AND vehicle.isdeleted=0 AND unit.trans_statusid <> 10 ORDER BY devices.lastupdated DESC";
        $devicesQuery = sprintf($Query,$this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleno = $row['vehicleno'];
                $device->phone = $row['phone'];
                $device->unitno = $row['unitno'];
                $device->tamper = $row['tamper'];
                $device->powercut = $row['powercut'];
                $device->inbatt = $row['inbatt'];
                $device->analog1 = $row['analog1'];
                $device->analog2 = $row['analog2'];
                $device->digitalio = $row['digitalio'];
                $device->gsmstrength = $row['gsmstrength'];
                $device->acsensor = $row['acsensor'];
                $device->analog1_sen = $row['analog1_sen'];
                $device->analog2_sen = $row['analog2_sen'];
                if($row['lastupdated']!='0000-00-00 00:00:00')
                    $device->lastupdated = $row['lastupdated'];
                else
                    $device->lastupdated = $row['registeredon'];
                $devices[] = $device;
            }
            return $devices;            
        }
        return null;
    }
    
    public function getsimforrtd()
    {
        $devices = Array();
        $Query = "SELECT devices.deviceid, vehicle.vehicleno, 
            unit.unitno, devices.phone, devices.gpsfixed, devices.gsmstrength,
            devices.gsmregister, devices.gprsregister,devices.lastupdated,
            devices.registeredon FROM `devices`
            INNER JOIN vehicle ON vehicle.uid = devices.uid
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            WHERE devices.`customerno` =%d AND vehicle.isdeleted=0 AND unit.trans_statusid <> 10 ORDER BY devices.lastupdated DESC";
        $devicesQuery = sprintf($Query,$this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleno = $row['vehicleno'];
                $device->phone = $row['phone'];
                $device->unitno = $row['unitno'];
                $device->gpsfixed = $row['gpsfixed'];
                $device->gsmstrength = $row['gsmstrength'];
                $device->gsmregister = $row['gsmregister'];
                $device->gprsregister = $row['gprsregister'];
                if($row['lastupdated']!='0000-00-00 00:00:00')
                    $device->lastupdated = $row['lastupdated'];
                else
                    $device->lastupdated = $row['registeredon'];
                $devices[] = $device;
            }
            return $devices;            
        }
        return null;
    }
    
    public function getmiscforrtd()
    {
        $devices = Array();
        $Query = "SELECT devices.deviceid, vehicle.vehicleno, 
            unit.unitno, devices.phone, devices.status, devices.`online/offline`,
            unit.analog3, unit.analog4,unit.commandkey,unit.commandkeyval,
            devices.lastupdated, devices.registeredon FROM devices
            INNER JOIN vehicle ON vehicle.uid = devices.uid
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            WHERE devices.`customerno` =%d AND vehicle.isdeleted=0 AND unit.trans_statusid <> 10 ORDER BY devices.lastupdated DESC";
        $devicesQuery = sprintf($Query,$this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleno = $row['vehicleno'];
                $device->phone = $row['phone'];
                $device->unitno = $row['unitno'];
                $device->status = $row['status'];
                $device->online_offline  = $row['online/offline'];
                $device->analog3 = $row['analog3'];
                $device->analog4 = $row['analog4'];
                $device->commandkey = $row['commandkey'];
                $device->commandkeyval = $row['commandkeyval'];
                if($row['lastupdated']!='0000-00-00 00:00:00')
                    $device->lastupdated = $row['lastupdated'];
                else
                    $device->lastupdated = $row['registeredon'];
                $devices[] = $device;
            }
            return $devices;            
        }
        return null;
    }
    
    public function getmiscforhistory($vehicleid)
    {
        $devices = Array();
        $devicesQuery = sprintf("SELECT devicehistory.deviceid, vehicle.vehicleno,
            devices.phone, unithistory.unitno, devicehistory.status, devicehistory.`online/offline`,
            unithistory.analog3, unithistory.analog4, unithistory.digitalio, unithistory.commandkey,
            unithistory.commandkeyval, devicehistory.lastupdated, devicehistory.ignition
            FROM `devicehistory`
            LEFT OUTER JOIN devices ON devices.devicekey = devicehistory.devicekey
            LEFT OUTER JOIN vehicle ON vehicle.devicekey = devicehistory.devicekey
            LEFT OUTER JOIN unithistory ON unithistory.dhid = devicehistory.id
            WHERE devicehistory.`customerno` =%s AND vehicle.vehicleid =%s
            ORDER BY devicehistory.lastupdated ASC",
                $this->_Customerno,$vehicleid);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                if($row['ignition']=='0' && $row['status'] !='J')
                {
                    $device->deviceid = $row['deviceid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->phone = $row['phone'];
                    $device->unitno = $row['unitno'];
                    $device->status = 'E';
                    $device->online_offline  = $row['online/offline'];
                    $device->analog3 = $row['analog3'];
                    $device->analog4 = $row['analog4'];
                    $device->digitalio = $row['digitalio'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->commandkey = $row['commandkey'];
                    $device->commandkeyval = $row['commandkeyval'];
                    $devices[] = $device;
                }
                else
                {
                    $device->deviceid = $row['deviceid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->phone = $row['phone'];
                    $device->unitno = $row['unitno'];
                    $device->status = $row['status'];
                    $device->online_offline  = $row['online/offline'];
                    $device->analog3 = $row['analog3'];
                    $device->analog4 = $row['analog4'];
                    $device->digitalio = $row['digitalio'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->commandkey = $row['commandkey'];
                    $device->commandkeyval = $row['commandkeyval'];
                    $devices[] = $device;
                }
            }
            return array_reverse($devices);            
        }
        return null;
    }
       
    public function modunit($uid,$phoneno)
    {
        $SQL = sprintf("UPDATE devices SET phone='%s' WHERE uid=%d AND customerno=%d",
 Sanitise::String($phoneno), Sanitise::Long($uid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL); 
    }
    
    public function DeviceInfo($vehicleid)
    {
        $devicesQuery = sprintf("SELECT vehicle.vehicleno,unit.unitno,devices.customerno,devices.phone,unit.analog1_sen,
            unit.analog2_sen,unit.acsensor,vehicle.type FROM `devices`
            INNER JOIN vehicle ON vehicle.uid = devices.uid
            INNER JOIN unit ON devices.uid = unit.uid
            WHERE devices.customerno = %s AND vehicle.vehicleid=%s AND unit.trans_statusid <> 10", $this->_Customerno,$vehicleid);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->unitno = $row['unitno'];
                $device->phone = $row['phone'];
                $device->vehicleno = $row['vehicleno'];
                $device->analog1_sen = $row['analog1_sen'];
                $device->analog2_sen = $row['analog2_sen'];
                $device->acsensor = $row['acsensor'];
                $device->type = $row['type'];
                return $device;
            }            
        }
        return null;
    }       

    //Cron Queries
    //Overspeeding
    public function markoverspeeding($vehicleid)
    {
        $Query = "Update eventalerts Set `overspeed`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }        
    
    public function marknormalspeeding($vehicleid)
    {
        $Query = "Update eventalerts Set `overspeed`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query ,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    } 
    
    //Ignition
    public function markignitionstatus($device,$count)
    {
        $Query = "Update ignitionalert Set `count`=%d, `last_status` = %d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($count), Sanitise::Long($device->ignition),Sanitise::Long($device->vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }
    
    public function changelastigcheck($device)
    {
        $Query = "Update ignitionalert SET last_check = '%s' Where vehicleid =%d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::DateTime($device->lastupdated), Sanitise::String($device->vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markignitionon($vehicleid)
    {
        $Query = "Update ignitionalert Set `count`=0, status = 1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }
    
    public function markignitionoff($vehicleid)
    {
        $Query = "Update ignitionalert Set `count`=0, status = 0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    } 
    
    //PowerCut
    public function markpowercut($vehicleid)
    {
        $Query = "Update eventalerts Set `powercut`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }            

    public function markpowerin($vehicleid)
    {
        $Query = "Update eventalerts Set `powercut`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }                
    
    //Tampering
    public function marktampered($vehicleid)
    {
        $Query = "Update eventalerts Set `tamper`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }            

    public function markuntampered($vehicleid)
    {
        $Query = "Update eventalerts Set `tamper`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }
    
    //AC
    public function markacon($vehicleid)
    {
        $Query = "Update eventalerts Set `ac`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }            

    public function markacoff($vehicleid)
    {
        $Query = "Update eventalerts Set `ac`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }
    
    public function marktempon($vehicleid)
    {
        $Query = "Update eventalerts Set `temp`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }  
    
    public function marktempoff($vehicleid)
    {
        $Query = "Update eventalerts Set `temp`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query,Sanitise::Long($vehicleid),$this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);        
    }  
    
    public function getdeviceignitionstatusforhistory($deviceid,$date)
    {
        $devices = Array();
        $Query = "SELECT devicehistory.deviceid, devicehistory.ignition,
            devicehistory.status,devicehistory.lastupdated from devicehistory WHERE devicehistory.customerno = %d 
            AND devicehistory.deviceid= %d and DATE(devicehistory.lastupdated) between '%s' and '%s'
            ORDER BY devicehistory.lastupdated ASC";
        $devicesQuery = sprintf($Query,$this->_Customerno,$deviceid,$date,$date);
        $this->_databaseManager->executeQuery($devicesQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $devices[] = $device;
            }
            return $devices;            
        }
        return null;
    }
    
    //Travel Report
    public function getvehiclenofromdeviceid($vehicleid)
    {
        $Query = 'select vehicleno from vehicle WHERE vehicleid = %d';
        $deviceQuery = sprintf($Query,Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($deviceQuery);
        if($this->_databaseManager->get_rowCount()>0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                return $row['vehicleno'];;
            }
        }
        return NULL;
    }
      
    public function getacinfo($vehicleid)
    {
        $Query = 'SELECT * from acalerts INNER JOIN customer ON customer.customerno = acalerts.customerno where vehicleid=%d';
        $deviceQuery = sprintf($Query,  Sanitise::String($vehicleid));
        $this->_databaseManager->executeQuery($deviceQuery);
        if($this->_databaseManager->get_rowCount()>0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $device = new VODevices();
                $device->ac_sen_id = $row['acalertid'];
                $device->firstcheck = $row['firstcheck'];
                $device->last_ignition = $row['last_ignition'];
                $device->aci_time = $row['aci_time'];
                return $device;
            }
        }
        return NULL;
    }
    
    public function addacinfo($device)
    {
        $Query = "INSERT INTO ac_sensor (firstcheck,last_ignition,customerno,deviceid) VALUES ('%s',%d,%d,%d)";
        $deviceQuery = sprintf($Query,$device->lastupdated,$device->ignition,$this->_Customerno,$device->deviceid);
        $this->_databaseManager->executeQuery($deviceQuery);
    }

    public function updateacinfo($time,$ignition,$deviceid)
    {
        $Query = "UPDATE ac_sensor SET firstcheck='%s',last_ignition=%d where deviceid=%d and customerno=%d";
        $deviceQuery = sprintf($Query,$time,Sanitise::Long($ignition),Sanitise::Long($deviceid),  $this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
    }
    
    public function updateacistatus($deviceid,$status)
    {
        $Query = 'UPDATE devices SET aci_status=%d where deviceid=%d and customerno=%d';
        $deviceQuery = sprintf($Query,$status,$deviceid,  $this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
    }
    
    public function get_ac_data_for_date($date,$deviceid)
    {
        $Query = "SELECT unithistory.digitalio,devicehistory.ignition,devicehistory.lastupdated FROM devicehistory ";
        $Query .= " INNER JOIN unithistory ON unithistory.dhid = devicehistory.id";
        $Query .= " WHERE devicehistory.id = %d AND DATE(devicehistory.lastupdated) BETWEEN '%s' AND '%s' ";
        $Query .= " AND devicehistory.customerno = %d AND devicehistory.status!='H' AND devicehistory.status!='F' ";
        $Query .= " ORDER BY devicehistory.lastupdated ASC";
        $deviceQuery = sprintf($Query,$deviceid,$date,$date,$this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
        $devices = array();
        $laststatus = array();
        if($this->_databaseManager->get_rowCount()>0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                if (@$laststatus['digitalio']!=$row['digitalio'] || @$laststatus['ig'] != $row['ignition'])
                {
                    $device = new VODevices();
                    $device->digitalio = $row['digitalio'];
                    $device->ignition = $row['ignition'];
                    $device->lastupdated = $row['lastupdated'];
                    $laststatus['ig'] = $row['ignition'];
                    $laststatus['digitalio'] = $row['digitalio'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
    }
    
    public function get_last_row_ac_data_for_date($date,$deviceid)
    {
        $Query = "SELECT unithistory.digitalio,devicehistory.ignition,devicehistory.lastupdated FROM devicehistory";
        $Query .= " INNER JOIN unithistory ON unithistory.dhid = devicehistory.id";
        $Query .= " WHERE devicehistory.id = %d AND DATE(devicehistory.lastupdated) BETWEEN '%s' AND '%s' ";
        $Query .= " AND devicehistory.customerno = %d AND devicehistory.status!='H' AND devicehistory.status!='F'";
        $Query .= " ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $deviceQuery = sprintf($Query,$deviceid,$date,$date,$this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
        $devices = array();
        if($this->_databaseManager->get_rowCount()>0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $device = new VODevices();
                $device->digitalio = $row['digitalio'];
                $device->ignition = $row['ignition'];
                $device->lastupdated = $row['lastupdated'];
                $devices[] = $device;
            }
            return $devices;
        }
    }
    
    function get_unitno_by_vehicle_id($vehicleid){
    
        $sql="select unitno from unit where vehicleid=".$vehicleid."";
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                
                return $row['unitno'];
            }
        }else{
            
            return 0;
        }
        
    
    }
	function get_vehicle_by_vehicle_id($vehicleid){
    
        $sql="select vehicleno from vehicle where vehicleid=".$vehicleid."";
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                
                return $row['vehicleno'];
            }
        }else{
            
            return 0;
        }
        
    
    }
    
    
    
}