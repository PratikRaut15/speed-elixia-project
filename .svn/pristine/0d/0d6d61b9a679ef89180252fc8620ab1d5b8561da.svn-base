<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/system/Date.php';
include_once 'lib/model/VOServiceCall.php';

class ServiceCallManager extends VersionedManager
{
    public function ServiceCallManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveServiceCall($service)
    {
        if(!isset($service->serviceid))
        {
            $this->Insert($service);
        }
        else
        {
            $this->Update($service);
        }
    }
    
    private function Update($service)
    {
        $SQL = sprintf( "Update servicecall
                        Set `contactperson`='%s',
                        `phoneno`='%s',
                        `add1`='%s',
                        `add2`='%s',
                        `clientid`='%d',
                        `trackeeid`='%d',
                        `sigreqd`='%d',
                        `devicekey`='%s', 
                        `userid`='%s',                         
                        `trackingno`='%s',
                        `uf1`='%s',
                        `uf2`='%s',
                        `callextra1`='%s',
                        `callextra2`='%s',
                        `userid`='%d',                        
                        `isdeleted`=0                        
                        WHERE serviceid = %d AND customerno = %d",
                        Sanitise::String( $service->contactperson),
                        Sanitise::String( $service->phoneno),
                        Sanitise::String( $service->add1),
                        Sanitise::String( $service->add2),
                        Sanitise::Long( $service->clientid),
                        Sanitise::Long( $service->trackeeid),    
                        Sanitise::Long( $service->sigreqd),       
                        Sanitise::String( $service->devicekey), 
                        Sanitise::Long( $service->userid),                 
                        Sanitise::String( $service->trackingno),
                        Sanitise::String( $service->uf1),
                        Sanitise::String( $service->uf2),
                        Sanitise::String( $service->extra1),
                        Sanitise::String( $service->extra2),    
                        Sanitise::Long( $service->userid),                    
                        Sanitise::Long( $service->serviceid),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        if(isset($service->oldtrackeeid) && ($service->oldtrackeeid != $service->trackeeid))
        {        
            $SQL = sprintf( "Update trackee
                            Set `pushservice`=1
                            WHERE customerno = %d AND trackeeid = %d",
                            $this->_Customerno,                         
                            Sanitise::Long($service->trackeeid));
            $this->_databaseManager->executeQuery($SQL);                                                        

            $SQL = sprintf( "Update trackee
                            Set `pushservice`=1
                            WHERE customerno = %d AND trackeeid = %d",
                            $this->_Customerno,                         
                            Sanitise::Long($service->oldtrackeeid));
            $this->_databaseManager->executeQuery($SQL);                                                                    
        }
    }
    
    private function Insert($service)
    {
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO servicecall
                        (`customerno`,`clientid`,`trackingno`
                        ,`userid`,`trackeeid`,`createdon`,`sigreqd`,`contactperson`,`phoneno`,`add1`,`add2`,`devicekey`,`isdeleted`,`callextra1`,`callextra2`,`uf1`,`uf2`) VALUES
                        ( '%d','%d','%s','%d','%d','%s','%d','%s','%s','%s','%s','%s',0,'%s','%s','%s','%s')",
        $this->_Customerno,
        Sanitise::String($service->clientid),
        Sanitise::String($service->trackingno),
        Sanitise::Long($service->userid),
        Sanitise::Long($service->trackeeid),
        Sanitise::DateTime($today),                
        Sanitise::Long($service->sigreqd),
        Sanitise::String($service->contactperson),                
        Sanitise::String($service->phoneno),
        Sanitise::String($service->add1),                
        Sanitise::String($service->add2),
        Sanitise::String($service->devicekey),
        Sanitise::String($service->extra1),                
        Sanitise::String($service->extra2),
        Sanitise::String($service->uf1),
        Sanitise::String($service->uf2));
        $this->_databaseManager->executeQuery($SQL);
        $service->serviceid = $this->_databaseManager->get_insertedId();
        
        $SQL = sprintf( "Update trackee
                        Set `pushservice`=1
                        WHERE customerno = %d AND trackeeid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($service->trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                                
    }
    
    public function get_call($serviceid)
    {
        $Query = sprintf("SELECT *, 
            servicecall.phoneno as phonenumber, 
            client.phoneno as clientphone,
            client.add1 as clientaddress1,
            client.add2 as clientaddress2,
            servicecall.add1 as calladdress1,
            servicecall.add2 as calladdress2,
            client.extra as cliextra,
            servicecall.uf1 as uf1,
            servicecall.uf2 as uf2,
            servicecall.callextra1 as serextra1,
            servicecall.callextra2 as serextra2
            FROM `servicecall` 
                INNER JOIN client ON client.clientid = servicecall.clientid 
                INNER JOIN trackee ON trackee.trackeeid = servicecall.trackeeid
                LEFT OUTER JOIN remarks ON remarks.remarkid = servicecall.remarkid
                WHERE servicecall.customerno=%d AND servicecall.serviceid=%d AND servicecall.isdeleted = 0",
            $this->_Customerno, Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $call = new VOServiceCall();
                $call->serviceid = $row['serviceid'];            
                $call->customerno = $row['customerno'];            
                $call->clientid = $row['clientid'];            
                $call->trackingno = $row['trackingno'];            
                $call->userid = $row['userid'];            
                $call->trackeeid = $row['trackeeid'];            
                $call->createdon = $row['createdon'];            
                $call->sigreqd = $row['sigreqd'];            
                $call->signature = $row['signature'];            
                $call->closedon = $row['closedon'];
                if($row['contactperson'] != "")
                {
                    $call->contactperson = $row['contactperson'];            
                }
                else
                {
                    $call->contactperson = $row['maincontact'];                                
                }
                if($row['calladdress1'] != "")
                {
                    $call->add1 = $row['calladdress1'];            
                }
                else
                {
                    $call->add1 = $row['clientaddress1'];                                
                }                                
                if($row['calladdress1'] != "")
                {
                    $call->add2 = $row['calladdress2'];            
                }
                else
                {
                    $call->add2 = $row['clientaddress2'];                                
                }                                
                if($row['phonenumber'] != "")
                {
                    $call->phoneno = $row['phonenumber'];            
                }
                else
                {
                    $call->phoneno = $row['clientphone'];                                
                }                                                
                $call->add1 = $row['add1'];            
                $call->add2 = $row['add2'];           
                $call->clientname = $row['clientname'];
                $call->tname = $row['tname'];
                $call->devicekey = $row['devicekey'];
                $call->cliextra = $row['cliextra'];
                $call->uf1 = $row['uf1'];
                $call->uf2 = $row['uf2'];
                $call->extra1 = $row['serextra1'];                
                $call->extra2 = $row['serextra2'];     
                $call->city = $row['city'];
                $call->state = $row['state'];
                $call->zip = $row['zip'];
                $call->email = $row['email'];
                $call->remarkname = $row['remarkname'];
            }
            return $call;            
        }
        return null;                        
    }        

    public function getopencalls()
    {
        $calls = Array();
        $Query = sprintf("SELECT * FROM `servicecall` 
            INNER JOIN client ON client.clientid = servicecall.clientid 
            INNER JOIN trackee ON trackee.trackeeid = servicecall.trackeeid
            INNER JOIN user ON user.userid = servicecall.userid
            WHERE servicecall.customerno=%d AND servicecall.status <> 5 AND servicecall.isdeleted = 0",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $call = new VOServiceCall();
                $call->serviceid = $row['serviceid'];            
                $call->customerno = $row['customerno'];            
                $call->clientid = $row['clientid'];            
                $call->trackingno = $row['trackingno'];            
                $call->userid = $row['userid'];            
                $call->trackeeid = $row['trackeeid'];            
                $call->createdon = $row['createdon'];
                if($row['sigreqd'] == 1)
                {
                    $call->sigreqd = "Yes";                                
                }
                else
                {
                    $call->sigreqd = "No";                                                    
                }
                $call->signature = $row['signature'];            
                $call->closedon = $row['closedon'];                            
                $call->contactperson = $row['contactperson'];            
                $call->phoneno = $row['phoneno'];                            
                $call->add1 = $row['add1'];            
                $call->add2 = $row['add2'];                                        
                $call->clientname = $row['clientname'];
                $call->trackeename = $row['tname'];
                $call->username = $row['username'];
                $call->cliextra = $row['extra'];
                $call->uf1 = $row['uf1'];
                $call->uf2 = $row['uf2'];
                $calls[] = $call;
            }
            return $calls;            
        }
        return null;                        
    }        
    
    public function DeleteCall($serviceid, $trackeeid)
    {
        $SQL = sprintf( "Update servicecall
                        Set `isdeleted`=1
                        WHERE customerno = %d AND serviceid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($serviceid));
        $this->_databaseManager->executeQuery($SQL);                                                                
        
        $SQL = sprintf( "Update trackee
                        Set `pushservice`=1
                        WHERE customerno = %d AND trackeeid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                                                
    }    
    
    public function gettrackingnos()
    {
        $trackingnos = Array();
        $Query = sprintf("SELECT trackingno FROM `servicecall` WHERE customerno=%d AND isdeleted = 0",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $call = new VOServiceCall();
                $call->trackingno = $row['trackingno'];            
                $trackingnos[] = $call;
            }
            return $trackingnos;            
        }
        return null;                        
    }
    
    public function getfilteredcallsforcustomer($seruf1 = null, $seruf2 = null, $callextra1 = null, $callextra2 = null, $cliextra = null, $clientname = null, $trackeeid = null, $remarkid = null, $isclosed = null, $fdate = null, $tdate = null, $dfdate = null, $dtdate = null)
    {
        $today = date("Y-m-d H:i:s");        
        $today = date("Y-m-d H:i:s", $today);
        
        $uf1search = "";
        if(isset($seruf1))
        {
            $uf1search .= sprintf( " AND (servicecall.uf1 LIKE '%%%s%%')",$seruf1);
        }

        $uf2search = "";
        if(isset($seruf2))
        {
            $uf2search .= sprintf( " AND (servicecall.uf2 LIKE '%%%s%%')",$seruf2);
        }

        $extra1search = "";
        if(isset($callextra1))
        {
            $extra1search .= sprintf( " AND (servicecall.callextra1 LIKE '%%%s%%')",$callextra1);
        }

        $extra2search = "";
        if(isset($callextra2))
        {
            $extra2search .= sprintf( " AND (servicecall.callextra2 LIKE '%%%s%%')",$callextra2);
        }

        $cliextrasearch = "";
        if(isset($cliextra))
        {
            $cliextrasearch .= sprintf( " AND (client.extra LIKE '%%%s%%')",$cliextra);
        }
        
        $clientnamesearch = "";
        if(isset($clientname))
        {
            $clientnamesearch .= sprintf( " AND (client.clientname LIKE '%%%s%%')",$clientname);
        }
        
/*        $delsearch = "";
        if(isset($isclosed))
        {
            $delsearch .= sprintf( " AND servicecall.isclosed=%d",$isclosed);
        }                                         
*/        
        $trackeesearch = "";
        if(isset($trackeeid))
        {
            $trackeesearch .= sprintf( " AND servicecall.trackeeid=%d",$trackeeid);
        }                                                 
        
        $remarksearch = "";
        if(isset($remarkid))
        {
            $remarksearch .= sprintf( " AND servicecall.remarkid=%d",$remarkid);
        }                                                         
                
        $fromdate = "";
        if(isset($fdate) && $fdate != "")
        {
        $fdate = $date->MakeMySQLDate($fdate);            
        $fdate= $fdate." 00:00:00";
        $fromdate .= sprintf( " AND servicecall.createdon BETWEEN '%s'",$fdate);
        }
        else
        {
        $fdate= "0000-00-00 00:00:00";
        $fromdate .= sprintf( " AND servicecall.createdon BETWEEN '%s'",$fdate);            
        }
        
        $todate = "";
        if(isset($tdate) && $tdate != "")
        {
            $tdate = $date->MakeMySQLDate($tdate);
            $tdate= $tdate." 23:59:59";            
            $todate .= sprintf( " AND '%s'",$tdate);
        }
        else
        {
            $todate .= sprintf( " AND '%s'",$today);            
        }

        if($isdelivered == 1)
        {
            $dfromdate = "";
            if(isset($dfdate) && $dfdate != "")
            {
            $dfdate = $date->MakeMySQLDate($dfdate);
            $dfdate= $dfdate." 00:00:00";
            $dfromdate .= sprintf( " AND servicecall.closedon BETWEEN '%s'",$dfdate);
            }
            else
            {
            $dfdate= "0000-00-00 00:00:00";
            $dfromdate .= sprintf( " AND servicecall.closedon BETWEEN '%s'",$dfdate);            
            }

            $dtodate = "";
            if(isset($dtdate) && $dtdate != "")
            {
                $dtdate = $date->MakeMySQLDate($dtdate);                                    
                $dtdate= $dtdate." 23:59:59";            
                $dtodate .= sprintf( " AND '%s'",$dtdate);
            }
            else
            {
                $dtodate .= sprintf( " AND '%s'",$today);            
            }
        }
        
        $calls = Array();
        $Query = sprintf("SELECT *, servicecall.phoneno as phonenumber, client.phoneno as clientphone FROM `servicecall`
            INNER JOIN trackee ON trackee.trackeeid = servicecall.trackeeid 
            INNER JOIN client ON servicecall.clientid = client.clientid
            LEFT OUTER JOIN remarks ON remarks.remarkid = servicecall.remarkid
            WHERE servicecall.customerno=%d AND servicecall.isdeleted = 0",
            $this->_Customerno);
        $Query.=$uf1search;
        $Query.=$uf2search;
        $Query.=$extra1search;
        $Query.=$extra2search;
        $Query.=$cliextrasearch;
        $Query.=$clientnamesearch;
        $Query.=$trackeesearch;  
        $Query.=$remarksearch;                  
//        $Query.=$delsearch;  
        $Query.=$fromdate;
        $Query.=$todate;
        $Query.=$dfromdate;
        $Query.=$dtodate;        
        $this->_databaseManager->executeQuery($Query);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $call = new VOServiceCall();
                $call->serviceid = $row['serviceid'];
                $call->clientname = $row['clientname'];
                if($row['contactperson'] != "")
                {
                    $call->maincontact = $row['contactperson'];            
                }
                else
                {
                    $call->maincontact = $row['maincontact'];                                
                }
                if($row['phonenumber'] != "")
                {
                    $call->phoneno = $row['phonenumber'];            
                }
                else
                {
                    $call->phoneno = $row['clientphone'];                                
                }                
                $call->trackingno = $row['trackingno'];            
                $call->userid = $row['userid'];            
                $call->trackeeid = $row['trackeeid'];            
                $call->sigreqd = $row['sigreqd'];
                if($row["sigreqd"] == 1)
                {
                    $call->signvalue = "Yes";
                }
                else
                {
                    $call->signvalue = "No";                    
                }
                $call->signature = $row['signature'];            
                $call->tname = $row['tname'];          
                $call->createdon = date("M j, g:i a", strtotime($row['createdon']));                
                if($row["status"] == 5)
                {
                    $call->isclosedstring = "Closed";
                    $diff = $this->checktimetaken($row["closedon"], $row["createdon"]);
                    $call->timetaken = $diff;
                }
                else
                {
                    $call->isclosedstring = "Open";                    
                    $call->timetaken = "";
                }
                if($row['closedon'] != "0000-00-00 00:00:00")
                    $call->closedon = date("M j, g:i a", strtotime($row['closedon']));
                else
                    $call->closedon = 'Pending';
                $calls[] = $call;
            }
            return $calls;            
        }
        return null;        
    }        
    
    private function checktimetaken($enddate, $startdate)
    {
        $enddate = strtotime($enddate);
        $startdate = strtotime($startdate);
        $diff = abs($enddate - $startdate); 

        $years   = floor($diff / (365*60*60*24)); 
        $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));            
        $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
        $minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
        $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
        if($months > 0)
        {
            return $months." mon ".$days." days";            
        }
        if($days > 0)
        {
            return $days." days ".$hours." hr";
        }
        else
        {
            if($hours > 0)
            {
                return $hours." hr ".$minutes." min ".$seconds." sec";
            }
            elseif($minutes > 0)
            {
                return $minutes." min ".$seconds." sec";                
            }
            else
            {
                return $seconds." sec";                                
            }
        }        
    }    
}