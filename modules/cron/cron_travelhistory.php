<?php
require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/CommunicationQueueManager.php';
require '../../lib/bo/UserManager.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GeoCoder.php';

class VODatacap {}
$newdateformat = date('jS F Y');

//$cm = new CustomerManager();
//$customernos = $cm->getcustomernos();
//if(isset($customernos))
//{
//    foreach($customernos as $thiscustomerno)
//    {
        $thiscustomerno = '30';
        $vehiclemanager = new VehicleManager($thiscustomerno);
        $vehicles = $vehiclemanager->get_all_vehicles_with_drivers();
        if(isset($vehicles))
            {
                foreach($vehicles as $vehicle )
                {

            $message ="";
            $message .= "<html><head></head><body><table id='search_table_2' border='1'>
                          <thead>
                            <tr>
                                <th id='formheader' colspan='100%'>Travel History Report For ".$vehicle->vehicleno." For $newdateformat </th>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Start Time</td>
                                <td>End Time</td>
                                <td>Start Location *</td>
                                <td>End Location *</td>
                                <td>Duration [Hours:Minutes]</td>
                                <td>Distance [KM]</td>
                                <td>Cumulative Distance [KM]</td>
                                <td>Status</td>
                            </tr>
                            </thead>
                        <tbody>";


                        $userdate = '2013-11-01';
                        $geocode = '1';
                    $location = "../../customer/$thiscustomerno/unitno/$vehicle->unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location))
                    {
                        $location = "sqlite:".$location;
                        $devicedata = getdatafromsqlite( $vehicle->vehicleid, $location);
                        $days = processtraveldata($devicedata);
//                        if($lastday!=NULL)
//                            $days = array_merge($days,$lastday);
                    }

                    if (isset($days) && count($days)>0)
                    {
                        dispalytraveldata($days,$vehicle->vehicleid,$vehicle->unitno,$geocode);
                    }



            $message .="</body></html>";
            require_once("dompdf/dompdf_config.inc.php");
            $dompdf = new DOMPDF();
            $dompdf->load_html($message);
            $dompdf->render();
            $pdf = $dompdf->output();
            $file_location = $_SERVER['DOCUMENT_ROOT']."/speed/customer/$thiscustomerno/unitno/$vehicle->unitno/pdf/$vehicle->vehicleid.pdf";
            file_put_contents($file_location,$pdf);
//            $cqm = new CommunicationQueueManager();
//            $cvo = new VOCommunicationQueue();
//
//            $cvo->message = $message;
//
//            //Email
//
//            $cvo->subject = "Travel History Report For ".ucfirst($thisdevice->vehicleid)." For $newdateformat";
//            $cvo->phone = "";
//            $cvo->type = 0;
//            $cvo->customerno = $thiscustomerno;
//            $cvo->email = 'zatakia.ankit@gmail.com';
//            $cqm->InsertQ($cvo);
//
//            $um = new UserManager();
//            $users = $um->getusersforcustomer($thiscustomerno);
//                if(isset($users))
//                {
//                    foreach($users as $user)
//                    {
//                        if($user->thistemail == '1'){
//                            $cvo->email = $user->email;
//                            $cqm->InsertQ($cvo);
//                        }
//                    }
//                }
            }
        }
//    }
//}


function getdatafromsqlite( $vehicleid, $location)
{
    $customerno = $_SESSION['customerno'];
    $devices2;
    $lastrow;
    try
    {
        $database = new PDO($location);
        $query = "SELECT devicehistory.deviceid, devicehistory.devicelat,
        devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
        devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
        INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
        WHERE vehiclehistory.vehicleid=$vehicleid
        ORDER BY devicehistory.lastupdated DESC Limit 0,1";

        $result = $database->query($query);
        $lastrow;
        if(isset($result) && $result != "")
        foreach ($result as $row)
        {
            $lastdevice = new VODevices();
            $lastdevice->deviceid = $row['deviceid'];
            $lastdevice->devicelat = $row['devicelat'];
            $lastdevice->devicelong = $row['devicelong'];
            $lastdevice->ignition = $row['ignition'];
            $lastdevice->status = $row['status'];
            $lastdevice->lastupdated = $row['lastupdated'];
            $lastdevice->odometer = $row['odometer'];
            $lastrow = $lastdevice;
        }

        $query = "SELECT vehiclehistory.vehicleno,devicehistory.deviceid, devicehistory.devicelat,
        devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
        devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
        INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
        WHERE vehiclehistory.vehicleid=$vehicleid
        ORDER BY devicehistory.lastupdated ASC";

        $result = $database->query($query);
        $devices2 = array();
        $laststatus;
        if(isset($result) && $result != "")
        foreach ($result as $row)
        {
            if (!isset($laststatus) || $laststatus != $row['ignition'])
            {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleno = $row['vehicleno'];
                $device->devicelat = $row['devicelat'];
                $device->devicelong = $row['devicelong'];
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $device->odometer = $row['odometer'];
                $laststatus = $row['ignition'];
                $devices2[] = $device;
            }
        }
    }
    catch (PDOException $e)
    {
        die($e);
    }
    $sqlitedata[] = $devices2;
    $sqlitedata[] = $lastrow;
    return $sqlitedata;
}

function processtraveldata($devicedata)
{
    $devices2 = $devicedata[0];
    $lastrow = $devicedata[1];
    $data = Array();
    $datalen = count($devices2);
    if (isset($devices2) && count($devices2)>1)
    {
        foreach ($devices2 as $device)
        {
            $datacap = new VODatacap();

            $datacap->ignition = $device->ignition;

            $ArrayLength = count($data);

            if ($ArrayLength == 0)
            {
                $datacap->starttime = $device->lastupdated;
                $datacap->startlat = $device->devicelat;
                $datacap->startlong = $device->devicelong;
                $datacap->startodo = $device->odometer;
            }
            else if ($ArrayLength == 1)
            {
                //Filling Up First Array --- Array[0]
                $data[0]->endlat = $device->devicelat;
                $data[0]->endlong = $device->devicelong;
                $data[0]->endtime = $device->lastupdated;
                $data[0]->endodo = $device->odometer;
                $data[0]->distancetravelled = $data[0]->endodo / 1000 - $data[0]->startodo / 1000;
                $data[0]->duration = getduration($data[0]->endtime, $data[0]->starttime);

                $datacap->starttime = $data[0]->endtime;
                $datacap->startlat = $data[0]->endlat;
                $datacap->startlong = $data[0]->endlong;
                $datacap->startodo = $data[0]->endodo;
                $datacap->endtime = $lastrow->lastupdated;
                $datacap->endlat = $lastrow->devicelat;
                $datacap->endlong = $lastrow->devicelong;
                $datacap->endodo = $lastrow->odometer;
            }
            else
            {
                $last = $ArrayLength - 1;
                $data[$last]->endtime = $device->lastupdated;
                $data[$last]->endlat = $device->devicelat;
                $data[$last]->endlong = $device->devicelong;
                $data[$last]->endodo = $device->odometer;

                $data[$last]->duration = getduration($data[$last]->endtime, $data[$last]->starttime);

                $data[$last]->distancetravelled = $data[$last]->endodo / 1000 - $data[$last]->startodo / 1000;


                $datacap->starttime = $data[$last]->endtime;
                $datacap->startlat = $data[$last]->endlat;
                $datacap->startlong = $data[$last]->endlong;
                $datacap->startodo = $data[$last]->endodo;

                if ($datalen - 1 == $ArrayLength)
                {
                    $datacap->endtime = $lastrow->lastupdated;
                    $datacap->endlat = $lastrow->devicelat;
                    $datacap->endlong = $lastrow->devicelong;
                    $datacap->endodo = $lastrow->odometer;
                    $datacap->duration = getduration($datacap->endtime, $datacap->starttime);
                    $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
                    $datacap->ignition = $device->ignition;
                }
            }
            $data[] = $datacap;
        }
        if($data!=NULL && count($data)>0)
        {
            $optdata = optimizerep($data);
        }
        return $optdata;
    }
    else if(isset($devices2) && count($devices2)==1)
    {
        $datacap = new VODatacap();
        $datacap->starttime = $devices2[0]->lastupdated;
        $datacap->startlat = $devices2[0]->devicelat;
        $datacap->startlong = $devices2[0]->devicelong;
        $datacap->startodo = $devices2[0]->odometer;
        $datacap->endtime = $lastrow->lastupdated;
        $datacap->endlat = $lastrow->devicelat;
        $datacap->endlong = $lastrow->devicelong;
        $datacap->endodo = $lastrow->odometer;
        $datacap->duration = getduration($datacap->endtime, $datacap->starttime);
        $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
        $datacap->ignition = $devices2[0]->ignition;
        $data[] = $datacap;

        return $data;
    }
    else
    {
        echo"<script>$('error').show();jQuery('#error').fadeOut(3000);</script>";
    }
}

function dispalytraveldata($datarows,$vehicleid,$unitno,$geocode)
{
    $runningtime = 0;
    $idletime = 0;
    $totaldistance = 0;
    $lastdate = NULL;
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    if (isset($datarows))
    {
	$z=0;
        foreach ($datarows as $change)
        {
            $comparedate = date('d-m-Y',strtotime($change->endtime));
            if(strtotime($lastdate) != strtotime($comparedate))
            {
                echo "<tr ><td align='center' colspan = '9' style='background: #CCCCCC;'>".date('d-m-Y',strtotime($change->endtime))."</td></tr>";
                $lastdate = date('d-m-Y',strtotime($change->endtime));
				$i=1;
            }

            $date = new Date();
            $currentdate = $date->add_hours(date("Y-m-d H:i:s"), 0);
            $currentdate = substr($currentdate, '0', 11);

            if($change->startlat==0)
            {
                $latlong = getlatlong($change->starttime, $change->endtime, $vehicleid, $unitno, 'asc');
                if(isset($latlong) && count($latlong)>0)
                {
                    $change->startlat = $latlong[0];
                    $change->startlong = $latlong[1];
                }
            }
            if($change->endlat==0)
            {
                $latlong = getlatlong($change->starttime, $change->endtime, $vehicleid, $unitno, 'desc');
                if(isset($latlong) && count($latlong)>0)
                {
                    $change->endlat = $latlong[0];
                    $change->endlong = $latlong[1];
                }
            }

            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME,strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME,strtotime($change->endtime));
			if ($change->ignition == 1)
            {
			 echo "<tr style='cursor:pointer;' style onclick='call_row(".++$z.")' id='".$z."' >";

			}else{

			 echo "<tr >";
			}

			echo "<td>".$i++."<input type='hidden' id='vehicle".$z."' value='".$vehicleid."'><input type='hidden' id='unitno".$z."' value='".$unitno."'><input type='hidden' id='date".$z."' value='".$lastdate."'><input type='hidden' id='timestamp".$z."' value='".$change->starttime.",".$change->endtime."'></td>";
            echo "<td>$change->starttime</td>";
            echo "<td>$change->endtime</td>";

            //Printing Location
            $location = getlocation($change->startlat, $change->startlong,$geocode);

            echo "<td>$location</td>";

            if($change->startlat != $change->endlat && $change->startlong != $change->endlong)
            {
                $location = getlocation($change->endlat, $change->endlong,$geocode);
                echo "<td>$location</td>";
            }
            else
            {
                echo "<td>Same Place</td>";
            }
            $hour = floor(($change->duration)/60);
            $minutes = ($change->duration)%60;
            if($minutes < 10)
            {
                $minutes = "0".$minutes;
            }
            $change->duration = $hour.":".$minutes;
            echo "<td>$change->duration</td>";
            if ($change->ignition == 0)
            {
                echo '<td></td>';
            }
            else
            {
                echo "<td>" . round($change->distancetravelled, 1) . "</td>";
                $totaldistance += round( $change->distancetravelled, 1);
            }
			echo "<td>".$totaldistance ."</td>";
            if ($change->ignition == 1)
            {
                echo "<td>Running</td>";
                $runningtime += $minutes + ($hour * 60);
            }
            else
            {
                echo "<td>Idle</td>";
                $idletime += $minutes + ($hour * 60);
            }

            echo "</tr>";
        }
    }
    echo '</tbody>';

   // require '../common/locationmessage.php';

   // echo'</table>';
    if (isset($totaldistance) && ($totaldistance) > 0)
    {
        if(isset($runningtime) && $runningtime != 0)
        {
            $AverageSpeed = (int) ($totaldistance / ($runningtime / 60));
        }
    }
    else
    {
        $AverageSpeed = 0;
    }
    $totaldistance = round($totaldistance, 1);

    $hour = floor(($runningtime)/60);
    $minutes = ($runningtime)%60;
    if($minutes < 10)
    {
        $minutes = "0".$minutes;
    }
    $runningtime = $hour.":".$minutes;

    $hour = floor(($idletime)/60);
    $minutes = ($idletime)%60;
    if($minutes < 10)
    {
        $minutes = "0".$minutes;
    }
    $idletime = $hour.":".$minutes;

    echo "

    <tfoot>
    <tr>
        <th colspan = '9'>Statistics</th>
    </tr>

    <tr><td colspan = '9'>Total Running Time = $runningtime Hours</td></tr>
    <tr><td colspan = '9'>Total Idle Time = $idletime Hours</td></tr>
    <tr><td colspan = '9'>Total Distance Travelled = $totaldistance km</td></tr>
    <tr><td colspan = '9'>Average Speed [Running Time] = $AverageSpeed km/hr</td></tr>
   </tfoot>
    </table>";
}

function getlocation($lat,$long,$geocode)
{

    $key = $lat.$long;
    if(!isset($GLOBALS[$key]))
    {
			if($lat !='0' && $long!='0')
			{
				if($_SESSION['Session_UserRole']=="elixir" && $geocode=="2"){

						$API = "http://www.elixiapolice.com/location.php?lat=".$lat."&long=".$long."";
						$location = json_decode(file_get_contents($API."&sensor=false"));
						@$address = $location->results[0]->formatted_address;
						if($location->status == "OVER_QUERY_LIMIT")
						{

									$address="Google temporary unavailable";

						}

				}else{



						$GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
						@$address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);


				}

			}
        $output = $address;
        $GLOBALS[$key] = $address;
    }
    else
    {
        $output = $GLOBALS[$key];
    }
    return $output;
}

function get_location_bylatlong($lat,$long)
    {

        $Query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( ".$lat."- `lat` ) * PI( ) /180 /2 ) , 2 ) +
		 COS( ".$lat." * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( ".$long." - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
		 AS distance FROM geotest HAVING distance <10 ORDER BY distance LIMIT 0,1 ";
        $geolocation_query = sprintf($Query);
        $this->_databaseManager->executeQuery($geolocation_query);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                if($row['distance']>1 ){
					$location_string = round($row['distance'], 2)." Km from ".$row['location'].", ".$row['city'].", ".$row['state'];
				}else{
					$location_string= "Near ".$row['location'].", ".$row['city'].", ".$row['state'];
				}

            }
            return $location_string;
        }else{
			return "google temporarily down";
		}
        return null;
    }

function getduration($EndTime, $StartTime)
{
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($idleduration / (365 * 60 * 60 * 24));
    $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    return $hours * 60 + $minutes;
}

function optimizerep($data)
{
    $datarows = array();
    $ArrayLength = count($data);
    $currentrow = $data[0];
    $a = 0;
    while ($currentrow != $data[$ArrayLength - 1]) {
        $i = 1;
        while (($i + $a) < $ArrayLength - 1 && $data[$i + $a]->duration < 3) {
            $i+=2;
        }
        $currentrow->endtime = $data[$i + $a - 1]->endtime;
        $currentrow->endlat = $data[$i + $a - 1]->endlat;
        $currentrow->endlong = $data[$i + $a - 1]->endlong;
        $currentrow->endodo = $data[$i + $a - 1]->endodo;
        $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
        $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
        $datarows[] = $currentrow;
        if (($a + $i) <= $ArrayLength - 1) {
            $currentrow = $data[$i + $a];
        }
        $a += $i;
        if (($a) >= $ArrayLength - 1)
            $currentrow = $data[$ArrayLength - 1];
    }
    if($datarows!=NULL)
    {
        $checkop = end($datarows);
        $checkup = end($data);
        if ($checkop->endtime != $checkup->endtime)
        {
            $currentrow->starttime = $checkop->endtime;
            $currentrow->startlat = $checkop->endlat;
            $currentrow->startlong = $checkop->endlong;
            $currentrow->startodo = $checkop->endodo;

            $currentrow->endtime = $checkup->endtime;
            $currentrow->endlat = $checkup->endlat;
            $currentrow->endlong = $checkup->endlong;
            $currentrow->endodo = $checkup->endodo;
            $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;

            $datarows[] = $currentrow;
        }
    }
    else
    {
        $currentrow = end($data);
        $currentrow->endlat = $currentrow->startlat;
        $currentrow->endlong = $currentrow->startlong;
        $currentrow->endtime = date('Y-m-d',  strtotime($currentrow->starttime));
        $currentrow->endtime .= " 23:59:59";
        $currentrow->endodo = $currentrow->startodo;;
        $currentrow->duration = getduration($currentrow->endtime, $currentrow->starttime);
        $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
        $datarows[] = $currentrow;
    }
    return $datarows;
}

?>
