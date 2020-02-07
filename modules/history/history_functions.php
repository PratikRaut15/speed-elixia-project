<?php
include_once '../../lib/components/ajaxpage.inc.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function DeviceInfo($vehicleid=NULL)
{
    if($vehicleid==NULL) 
    {
        $vehicleid = GetSafeValueString($_GET['vid'], 'long');    
    }
    else
    {
        $vehicleid = GetSafeValueString($vehicleid, 'long');
    }
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $DEVICE = $devicemanager->DeviceInfo($vehicleid);
    return $DEVICE;
}
function GetAllDrivers()
{
    $customerno = $_SESSION['customerno'];
    $drivermanager = new DriverManager($customerno);
    $DRIVERS = $drivermanager->GetAllDrivers_SQLite();
    return $DRIVERS;
}
function tableheader($vehicleno,$unitno)
{
    $HEADER = "Vehicle No - $vehicleno, Unit No - $unitno";
    return $HEADER;
}
function GetMisc()
{
    $device = DeviceInfo();
    $data = GetHistoryFromSqlite($device);
    #$ROWS = '<tr><td colspan="100%">No Data Available</td></tr>';
    $ROWS='';
    if(isset($data))
    {
        foreach($data as $info)
        {
            $date=NULL;
            $date = new DateTime($info['lastupdated']);
            $ROWS .= '<tr><td>'.$date->format('d-M-Y H:i').'</td>';
            $reason = GetReasonInfo($info['status']);
            $ROWS .= "<td>$reason</td>";
            $DStatus = 'online';
            if($info['online/offline']==1) { $DStatus = 'offline';}
            $ROWS .= "<td>$DStatus</td>";
            $ROWS .= '<td>'.$info['analog3'].'</td>';
            $ROWS .= '<td>'.$info['analog4'].'</td>';
            $ROWS .= '<td>'.$info['commandkey'].'</td>';
            $ROWS .= '<td>'.$info['commandkeyval'].'</td></tr>';
        }
    }
    $tableheader = tableheader($device->vehicleno,$device->unitno);
    $TopPanel = "<div class='scrollTable'><table><thead><tr><th colspan='100%' id='formheader'>Misc Data - $tableheader</th></tr>";
    $TopPanel .= '<tr><td>Date Time</td><td>Reason</td><td>Data Status</td><td>Analog 3</td><td>Analog 4</td><td>CommandKey</td><td>CommandKeyValue</td></tr><tbody>';
    $BasePanel = '</tbody></table></div>';
    return $TopPanel.$ROWS.$BasePanel;
}
function GetDevice()
{
    $device = DeviceInfo();
    $data = GetHistoryFromSqlite($device);
    #$ROWS = '<tr><td colspan="100%">No Data Available</td></tr>';
    $ROWS = '';
//    $TempWhere = Temp($device->tempsen1, $device->tempsen2);
    if(isset($data))
    {
        foreach ($data as $info)
        {
            $date=NULL;
            $date = new DateTime($info['lastupdated']);
            $ROWS .= '<tr><td>'.$date->format('d-M-Y H:i').'</td>';
            $NW = 0;
            if($info['gsmstrength']<32) { $NW = (int)($info['gsmstrength']/31*100); }
            $ROWS .= '<td>'.$NW.'</td>';
            $ROWS .= '<td>'.($info['inbatt']/1000).'</td>';
            if($info['tamper']==1) {$B='YES';} else {$B='NO';}
            $ROWS .= '<td>'.$B.'</td>';
            if($info['powercut']==0) {$B='YES';} else {$B='NO';}
            $ROWS .= '<td>'.$B.'</td>';
            $ROWS .= '<td>Not Active</td>';
//            if($TempWhere!=0) 
 //           {
  //              if ($info[$TempWhere] !=NULL || $info[$TempWhere]!='') 
   //             {
            $ROWS .= '<td>'.$info['analog'.$device->tempsen1].'</td> C'; 
//                }
//                else { $ROWS.='<td></td>'; }
//            }
 //           else { $ROWS.='<td></td>'; }
            $ac = 'NOT ACTIVE';
            if($device->acsensor==1)
            {
                $ac = $info['digitalio'];
            }
            $ROWS .= "<td>$ac</td></tr>";
        }
    }
    $tableheader = tableheader($device->vehicleno,$device->unitno);
    $TopPanel = "<div class='scrollTable'><table><thead><tr><th colspan='100%' id='formheader'>Device Data - $tableheader</th></tr>";
    $TopPanel .= '<tr><td>Date Time</td><td>Network Strength %</td><td>Int Batt(V)</td><td>Tamper</td><td>Power Cut</td><td>Fuel Sensor</td>';
    $TopPanel .= '<td>Temperature</td><td>Gen Set</td></tr></thead><tbody>';
    $BasePanel = '</tbody></table></div>';
    return $TopPanel.$ROWS.$BasePanel;
}
function GetVehicle()
{
    $device = DeviceInfo();
	
    $data = GetHistoryFromSqlite($device);
    $drivers = GetAllDrivers();

    #$ROWS = '<tr><td colspan="100%">No Data Available</td></tr>';
    $ROWS = '';
    if(isset($data))
    {
			
        foreach ($data as $info)
        {
            $date=NULL;
            $date = new DateTime($info['lastupdated']);
            $driverid = $info['driverid'];
            $ROWS .= '<tr><td>'.$date->format('d-M-Y H:i').'</td>';
            $ROWS .= '<td>'.$drivers[$driverid]['drivername'].'<br>('.$drivers[$driverid]['driverphone'].')</td>';
            if ($info['ignition']==1) { $ig = 'ON'; } else { $ig = 'OFF';}
            
			$ROWS .= "<td>$ig</td><td>".$info['curspeed'].'</td><td>'.getdistance($device->unitno,$date->format('d-M-Y'),$info['odometer']).'</td><td>'.($info['extbatt']/100).'</td>';
            $FCall = '';
            $lat = round($info['devicelat'], 10);
            $long = round($info['devicelong'], 10);
            if ($info['devicelat']!='' && $info['devicelong']!='') { $FCall="pulllocation($lat,$long);";}
            $ROWS .= "<td><a href='#' onclick = '$FCall'>Track</a></td></tr>";
//			if($_SESSION['customerno']!=18){
//				$ROWS .= "<td><a href='#' onclick = '$FCall'>Track</a></td></tr>";
//			}else{
				$GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
				$address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);
			$ROWS.="<td>".$address."</td>";
//			}
        }
    }
    $tableheader = tableheader($device->vehicleno,$device->unitno);
    $TopPanel = "<div class='scrollTable'><table><thead><tr><th colspan='100%' id='formheader'>Vehicle Data - $tableheader</th></tr>";
    $TopPanel .="<tr><td colspan='100%'><span id='waitmessage' style='display: none;'>Getting Location Details Please Wait</span></td></tr>";
    $TopPanel .='<tr><td>DateTime</td><td>Driver</td><td>Ignition</td><td>Speed (km/hr)</td><td>Total Distance (km)</td><td>Ext Batt (V)</td><td>Location</td></tr><thead>';
    $BasePanel = '</thead></tbody></table></div>';
    return $TopPanel.$ROWS.$BasePanel;
	
}
function getdistance($unitno,$date,$odometer)
{
    $date = date("Y-m-d", strtotime($date));
    $totaldistance = 0;
    $lastodometer = $odometer;    
    $firstodometer = GetOdometer($date,$unitno,'ASC');
    if($lastodometer < $firstodometer)
    {
        $lastodometermax = GetOdometerMax($date,$unitno) ;
        $lastodometer = $lastodometermax + $lastodometer;
    }
    $totaldistance = $lastodometer - $firstodometer;
    if($totaldistance!=0)
        return $totaldistance/1000;
    return $totaldistance;
}
function GetOdometer($date,$unitno,$order)
{
    $date = substr($date, 0,11);
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    $ODOMETER = 0;
    if(file_exists($location))
    {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT odometer from vehiclehistory ORDER BY vehiclehistory.lastupdated $order LIMIT 0,1";
        $result = $db->query($query);
        foreach ($result as $row)
        {
            $ODOMETER = $row['odometer'];
        }
    }
    return $ODOMETER;
}
function GetOdometerMax($date,$unitno)
{
    $date = substr($date, 0,11);
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    $ODOMETER = 0;
    if(file_exists($location))
    {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT max(odometer) as odometerm from vehiclehistory";
        $result = $db->query($query);
        foreach ($result as $row)
        {
            $ODOMETER =  $row['odometerm'];
        }
    }
    return $ODOMETER;
    
}
function GetSim()
{
    $device = DeviceInfo();
    $data = GetHistoryFromSqlite($device);
    #$ROWS = '<tr><td colspan="100%">No Data Available</td></tr>';
    $ROWS = '';
    if(isset($data))
    {
        foreach ($data as $info)
        {
            $date=NULL;
            $date = new DateTime($info['lastupdated']);
            $ROWS .= '<tr><td>'.$date->format('d-M-Y H:i').'</td>';
            $NW = 0;
            if($info['gsmstrength']<32) { $NW = (int)($info['gsmstrength']/31*100); }
            $ROWS .= "<td>$NW %</td>";
            $GPS = 'Invalid';
            if ($info['gpsfixed']=='A') { $GPS = 'Valid';}
            $ROWS .= "<td>$GPS</td>";
            $GSMreg = 'Not Registered';
            if ($info['gsmregister']==1)
                $GSMreg =  'Registered Home Network';
            else if ($info['gsmregister']==2)
                $GSMreg ='Searching New Network<';
            else if ($info['gsmregister']==3)
                $GSMreg = 'Registration Denied';
            else if ($info['gsmregister']==4)
                $GSMreg = 'Unknown';
            else if ($info['gsmregister']==5)
                $GSMreg = 'Roaming';
            $ROWS .= "<td>$GSMreg</td>";
            $ROWS .= '<td>'.$info['gprsregister'].'</td></tr>';
        }
    }
    $tableheader = tableheader($device->vehicleno,$device->unitno);
    $TopPanel = "<div class='scrollTable'><table><thead><tr><th colspan='100%' id='formheader'>SIM Data - $tableheader</th></tr>";
    $TopPanel .= '<tr><td>Date Time</td><td>Network Strength %</td><td>GPS Available</td><td>GSM Register</td><td>GPRS Register</td>';
    $TopPanel .= '</tr></thead><tbody>';
    $BasePanel = '</tbody></table></div>';
    return $TopPanel.$ROWS.$BasePanel;
}
function getlocation($lat,$long)
{
    $latitude = GetSafeValueString($lat,'string');
    $longitude = GetSafeValueString($long,'string');
    $string = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false";
    $vehiclelocationdetails = json_decode(file_get_contents($string));
    echo $vehiclelocationdetails->results[0]->formatted_address;
}
function vehiclehistmapping($vehicleid)
{
    include '../common/map_common_functions.php';
    $device = DeviceInfo($vehicleid);
    $data = GetHistoryFromSqlite($device);
    $drivers = GetAllDrivers();
    $MappingData = DataForMapping($data,$drivers);
    $ajaxpage = new ajaxpage();
    class jsonop {}
    
    $finaloutput = array();
    if(isset($MappingData))
    {
        $length = count($MappingData);
        $counter = 0;
        foreach($MappingData as $thisdevice)
        {
            $counter++;
            $output = new jsonop();
            $date = new DateTime($thisdevice->lastupdated);
            $output->cgeolat = $thisdevice->devicelat;
            $output->cgeolong = $thisdevice->devicelong;
            $output->cname = $thisdevice->vehicleno;   
            $output->cdrivername = $thisdevice->drivername;
            $output->cdriverphone = $thisdevice->driverphone;    
            $output->cspeed = $thisdevice->curspeed;
            $output->clastupdated = $date->format('D d-M-Y H:i');
            $output->cvehicleid = $thisdevice->vehicleid;
            $thisdevice->type = $device->type;
            if($counter == 1)
                $output->image = '../../images/Sflag.png';
            else if($counter == $length)
                $output->image = '../../images/Eflag.png';
            else 
                $output->image = vehicleimageSqlite($thisdevice);
            $finaloutput[] = $output;
        }
    }
    $ajaxpage->SetResult($finaloutput);
    $ajaxpage->Render();
}
function GetTodaysDate()
{
    $ServerIST = new DateTime();
    $ServerIST->modify('810 minutes');
    return $ServerIST;
}
function GetHistoryFromSqlite($device)
{
    $Date = GetTodaysDate()->format('Y-m-d');
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/unitno/$device->unitno/sqlite/$Date.sqlite";
    if(file_exists($location))
    {
        $location = "sqlite:".$location;
        $db = new PDO($location);
        $query = 'SELECT * from devicehistory';
        $query .= ' INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated'; 
        $query .= ' INNER JOIN unithistory ON unithistory.lastupdated = vehiclehistory.lastupdated';
        $result = $db->query($query);
        return $result;
    }
    return NULL;
}
function DataForMapping($data,$drivers)
{
    $lastdistance = 0;
    $DATAMAP=array();
    if(isset($data))
    {
        foreach ($data as $info)
        {
            if($info['devicelat']!=0 && $info['devicelong']!=0)
            {
                if($info['ignition']=='1')
                {
                    if(($lastdistance+500)<$info['odometer'])
                    {
                        $driverid = $info['driverid'];
                        $device = new VODevices();
                        $device->vehicleno = $info['vehicleno'];
                        $device->vehicleid = $info['vehicleid'];
                        $device->deviceid = $info['deviceid'];
                        $device->devicelat = $info['devicelat'];
                        $device->devicelong = $info['devicelong'];
                        $device->drivername = $drivers[$driverid]['drivername'];
                        $device->driverphone = $drivers[$driverid]['driverphone'];
                        $device->curspeed = $info['curspeed'];
                        $device->lastupdated = $info['lastupdated'];
                        #$device->type = $info['type'];
                        $device->status = $info['status'];
                        $device->ignition = $info['ignition'];
                        $device->directionchange = $info['directionchange'];
                        $lastdistance = $info['odometer'];
                        $DATAMAP[] = $device;
                    }
                }
                else if($info['ignition']=='0' && $lastdistance!=$info['odometer'])
                {
                    if($info['curspeed']<0)
                    {
                        $device = new VODevices();
                        $device->vehicleno = $info['vehicleno'];
                        $device->vehicleid = $info['vehicleid'];
                        $device->deviceid = $info['deviceid'];
                        $device->devicelat = $info['devicelat'];
                        $device->devicelong = $info['devicelong'];
                        $device->drivername = $drivers[$driverid]['drivername'];
                        $device->driverphone = $drivers[$driverid]['driverphone'];
                        $device->curspeed = $info['curspeed'];
                        $device->lastupdated = $info['lastupdated'];
                        #$device->type = $info['type'];
                        $device->status = $info['status'];
                        $device->ignition = $info['ignition'];
                        $device->directionchange = $info['directionchange'];
                        $lastdistance = $info['odometer'];
                        $DATAMAP[] = $device;
                    }
                }
            }
        }
    }
    return $DATAMAP;
}
/*function Temp($analog1,$analog2)
{
    $TEMP = 0;
    
    if(isset($analog1)) { if($analog1) { $TEMP = 'analog'.$analog1; } }
    if(isset($analog2)) { if($analog2) { $TEMP = 'analog'.$analog2; } }
    
    return $TEMP;
}
 * 
 */
function GetReasonInfo($value)
{
    $REASON='';
    if($_SESSION['Session_UserRole']=='elixir')
    switch($value)
    {
        case 'A':
            $REASON='Normal Speed Periodic';
            break;
        case 'B':
            $REASON='Excess Speed Periodic';
            break;
        case 'C':
            $REASON='Back To Normal Speed';
            break;
        case 'D':
            $REASON='Excess Speed Start';
            break;
        case 'E':
            $REASON='Stop Mode';
            break;
        case 'F':
            $REASON='Motion Start';
            break;
        case 'G':
            $REASON='Motion Stop';
            break;
        case 'H':
            $REASON='GPS Fixed';
            break;
        case 'I':
            $REASON='Initialize';
            break;
        case 'J':
            $REASON='Ignition Status Change';
            break;
        case 'K':
            $REASON='Box Open';
            break;
        case 'L':
            $REASON='Transit In External Power';
            break;
        case 'M':
            $REASON='Dont Know';
            break;
        case 'N':
            $REASON='SOS Pressed';
            break;
        case 'O':
            $REASON='Change in Digital Input State';
            break;
        case 'P':
            $REASON='Response To Position Command';
            break;
        case 'Q':
            $REASON='Response To Parameter Change Command';
            break;
        case 'R':
            $REASON='Response To Parameter Query';
            break;
        case 'S':
            $REASON='Harsh Break';
            break;
        case 'T':
            $REASON='Immobilzed';
            break;
        case 'U':
            $REASON='GPS Failed';
            break;
        case 'W':
            $REASON='Sim Card Removed';
            break;
        case 'Z':
            $REASON='Error';
            break;
        default :
            echo $value;
    }
    else 
    {
        if($value=='A' || $value=='C')
            $REASON="Normal Speed";
        else if($value=='B' || $value=='D')
            $REASON="Excess Speed";
        else if($value=='E' || $value=='G')
            $REASON="Vehicle Idle";
        else if($value=='F')
            $REASON="Vehicle Started";
        else if($value=='H')
            $REASON="GPS Fixed";
        else if($value=='N')
            $REASON="SOS";
        else if($value=='S')
            $REASON="Harsh Break";
        else if($value=='J')
            $REASON="Ignition Status Change";
        else
            $REASON="OK";
    }
    return $REASON;
}
?>
