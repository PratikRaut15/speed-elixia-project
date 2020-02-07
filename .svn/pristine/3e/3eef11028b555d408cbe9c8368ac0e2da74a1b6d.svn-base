<?php 
 if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
        $RELATIVE_PATH_DOTS = "../../";
    }
    include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
    include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
    include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';

    if (!isset($_SESSION)) {
        session_start();
        if (!isset($_SESSION['timezone'])) {
            $_SESSION['timezone'] = 'Asia/Kolkata';
        }
        date_default_timezone_set('' . $_SESSION['timezone'] . '');
    }

function getduration($EndTime, $StartTime) {
                                    $diff = strtotime($EndTime) - strtotime($StartTime);
                                    $years = floor($diff / (365 * 60 * 60 * 24));
                                    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                                    $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
                                    $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                                    return $hours * 60 + $minutes;
    }
function getPowerStatusData($vehicleid,$SDate,$EDate,$Shour, $Ehour){
    $error          = "<script>$('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $customerno     = $_SESSION['customerno'];
    $um             = new UnitManager($customerno);
    $vehicleid      = GetSafeValueString($vehicleid, 'string');
    $totaldays      = gendays_cmn($SDate, $EDate);
    $count          = count($totaldays);
    $endelement     = end($totaldays);
    $firstelement   = $totaldays[0];
    $unitno         = $um->getunitnofromdeviceid($vehicleid);
    $days           = array();

    if (isset($totaldays)) {
     foreach ($totaldays as $userdate) {
         $lastday = power_status_data($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $Shour, $Ehour);
         if ($lastday != null) {
             $days = array_merge($days, $lastday);
         }
     }
    }
    if (isset($days) && count($days) > 0) {
         include 'pages/panels/powerStatusRep.php';
         displayPowerStatusData($days, $vehicleid, $unitno);
    } else {
        echo $error;
    }
}

function power_status_data($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $Shour, $Ehour) {
         $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
        if(!file_exists($location)) {
            return null;
        }
        if(filesize($location) == 0){
            return null;
        }
        $location = "sqlite:" . $location;
        $defaultStartTime = "00:00:00";
        $defaultEndTime = "23:59:59";
        if ($count == 1) {
            $devicedata = getpowerstatusdatafromsqlite($vehicleid, $location, $userdate, $Shour, $Ehour);
        } elseif ($count > 1 && $userdate == $firstelement) {
            $devicedata = getpowerstatusdatafromsqlite($vehicleid, $location, $userdate, $Shour, $defaultEndTime);
        } elseif ($count > 1 && $userdate == $endelement) {
            $devicedata = getpowerstatusdatafromsqlite($vehicleid, $location, $userdate, $defaultStartTime, $Ehour);
        } else {
            $devicedata = getpowerstatusdatafromsqlite($vehicleid, $location, $userdate, $defaultStartTime, $defaultEndTime);
        }
        if ($devicedata != null) {
            $lastday = processPowerStatusdata($devicedata, $unitno);
            return $lastday;
        } else {
            return null;
        }
    }
    function getpowerstatusdatafromsqlite($vehicleid, $location, $userdate, $Shour, $Ehour, $customerno = '') {
        $customerno = ($customerno != '') ? $customerno : $_SESSION['customerno'];
        $sqlitedata = array();
        $devices2;
        $lastrow;
     if (isset($Shour) && isset($Ehour)) {
            try {
                $database = new PDO($location);
                $query = "SELECT devicehistory.deviceid, devicehistory.devicelat,
                devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
                devicehistory.lastupdated, vehiclehistory.odometer ,vehiclehistory.curspeed
                FROM devicehistory
                INNER JOIN vehiclehistory ON vehiclehistory.vehiclehistoryid = devicehistory.id
                WHERE vehiclehistory.vehicleid=$vehicleid
                AND devicehistory.gpsfixed = 'A'
                AND devicehistory.lastupdated >= '$userdate $Shour' AND devicehistory.lastupdated <= '$userdate $Ehour'
                AND devicelat <> '0.000000' AND devicelong <> '0.000000'
                ORDER BY devicehistory.lastupdated DESC Limit 0,1";
                $result = $database->query($query);
                $lastrow;
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $lastdevice = new VODevices();
                        $lastdevice->deviceid = $row['deviceid'];
                        $lastdevice->devicelat = $row['devicelat'];
                        $lastdevice->devicelong = $row['devicelong'];
                        $lastdevice->ignition = $row['ignition'];
                        $lastdevice->status = $row['status'];
                        $lastdevice->lastupdated = $row['lastupdated'];
                        $lastdevice->odometer = $row['odometer'];
                        $lastdevice->speed = $row['curspeed'];
                        $lastrow = $lastdevice;
                    }
                }
                $query = "SELECT vehiclehistory.vehicleno,devicehistory.deviceid, devicehistory.devicelat,
                devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
                devicehistory.lastupdated, vehiclehistory.odometer ,vehiclehistory.curspeed
                FROM devicehistory
                INNER JOIN vehiclehistory ON vehiclehistory.vehiclehistoryid = devicehistory.id
                WHERE vehiclehistory.vehicleid=$vehicleid
                AND devicehistory.gpsfixed = 'A'
                AND devicehistory.lastupdated >= '$userdate $Shour' AND devicehistory.lastupdated <= '$userdate $Ehour'
                AND devicelat <> '0.000000' AND devicelong <> '0.000000' group by devicehistory.lastupdated
                ORDER BY devicehistory.lastupdated ASC";
                $result = $database->query($query);
                $devices2 = array();
                $laststatus;
                $previous_lastupdated = 0;
                $lastspeed = 0 ;
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $currtime = date('H:i', strtotime($row['lastupdated']));
           if (!isset($laststatus) || $laststatus != $row['ignition'] || ($row['ignition'] == 0
                                && (
                                    ($lastspeed == 0 && $row['curspeed'] > 0 && strtotime($previous_lastupdated) != strtotime($currtime))
                                    || ($lastspeed > 0 && $row['curspeed'] == 0 && strtotime($previous_lastupdated) != strtotime($currtime))
                                )
                            )
                        ) {
                            $device = new VODevices();
                            $device->deviceid     = $row['deviceid'];
                            $device->vehicleno    = $row['vehicleno'];
                            $device->ignition     = $row['ignition'];
                            $device->status       = $row['status'];
                            $device->lastupdated  = $row['lastupdated'];
                            $device->prev         = $previous_lastupdated;
                            $device->cur          = $currtime;
                            $laststatus           = $row['ignition'];
                            $previous_lastupdated = date('H:i', strtotime($row['lastupdated']));
                            $devices2[]           = $device;
                        } else {
                            $previous_lastupdated = date('H:i', strtotime($row['lastupdated']));
                        }
                    }
                }
            } catch (PDOException $e) {
                die($e);
            }
        }//
        if (isset($devices2) && !empty($devices2)) {
            $sqlitedata[] = $devices2;
        }
        if (isset($lastrow) && !empty($lastrow)) {
            $sqlitedata[] = $lastrow;
        }
        return $sqlitedata;
    }

    function processPowerStatusdata($devicedata, $unitno, $isApi = "0") {
        $devices2 = $devicedata[0];
        $lastrow = $devicedata[1];
        $data = array();
        $datalen = count($devices2);

        if (isset($devices2) && count($devices2) > 1) {
            foreach ($devices2 as $device) {
                $datacap = new stdClass();
                $datacap->ignition = $device->ignition;
                $arrayLength = count($data);

                if ($arrayLength == 0) {
                    $datacap->starttime = $device->lastupdated;
                    $datacap->unitno = $unitno;
                } elseif ($arrayLength == 1) {
                    $data[0]->endtime = $device->lastupdated;
                    $data[0]->duration = getduration($data[0]->endtime, $data[0]->starttime);
                    $datacap->starttime = $data[0]->endtime;
                    $datacap->unitno = $unitno;
                } else {
                    $last = $arrayLength - 1;
                    $data[$last]->endtime = $device->lastupdated;
                    $data[$last]->duration = getduration($data[$last]->endtime, $data[$last]->starttime);
                    $datacap->starttime = $data[$last]->endtime;
                    $datacap->unitno = $unitno;
                    if ($datalen - 1 == $arrayLength) {
                        $datacap->endtime = $lastrow->lastupdated;
                        $datacap->duration = getduration($datacap->endtime, $datacap->starttime);
                        $datacap->ignition = $device->ignition;
                    }
                }
                $data[] = $datacap;
            }
            if ($data != null && count($data) > 0) {
                //$optdata = optimizerep($data);
            }
            return $data;
        } elseif (isset($devices2) && count($devices2) == 1) {
            $datacap = new stdClass();
            $datacap->starttime = $devices2[0]->lastupdated;
            $datacap->speed = $lastrow->speed;
            $datacap->duration = getduration($datacap->endtime, $datacap->starttime);
            $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
            $datacap->ignition = $devices2[0]->ignition;
            $datacap->unitno = $unitno;
            $data[] = $datacap;
            return $data;
        } else {
            if ($isApi == "1") {
                return null;
            } else {
                echo "<script>$('error').show();jQuery('#error').fadeOut(3000);</script>";
            }
        }
    }

 function displayPowerStatusData($datarows, $vehicleid, $unitno) {
                  $t = 1;
                  $customerno    = isset($cust) ? $cust : $_SESSION['customerno'];
                  $runningtime   = 0;
                  $idletime      = 0;
                  $idle_ign_on   = 0;
                  $startdate     = $_POST['SDate'] . ' ' . $_POST['STime'];
                  $enddate       = $_POST['EDate'] . ' ' . $_POST['ETime'];
                 
                  $startdate     = date('Y-m-d H:i:s', strtotime($startdate));
                  $enddate       = date('Y-m-d H:i:s', strtotime($enddate));
                
                  $lastdate      = null;
                  $totalminute   = 0;
                  $finalReport   = '';
                  $totalOnTime=0;
                  $totalOffTime=0;
                  if (isset($datarows)){
                      $z = 0;
                      foreach ($datarows as $change) {
                        if(!isset($change->endtime)){
                          $change->endtime = $enddate;
                        }
                         $comparedate = date('d-m-Y', strtotime($change->endtime));
                          $today      = date('d-m-Y', strtotime('Now'));
                          if (strtotime($lastdate) != strtotime($comparedate)) {
                              if ($today == $comparedate) {
                                  $todays = date('Y-m-d');
                                  $todayhms = date('Y-m-d H:i:s');
                                  $to_time = strtotime("$todayhms");
                                  $from_time = strtotime("$todays 00:00:00");
                                  $totalminute = round(abs($to_time - $from_time) / 60, 2);
                              } else {
                                  $count = $t;
                              }
                              $finalReport .= "<tr><th align='center'  style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                              $lastdate = date('d-m-Y', strtotime($change->endtime));
                              $t++;
                              $i = 1;
                          }
                          //Removing Date Details From DateTime
                          $change->starttime    = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
                          $change->endtime      = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
                          $change->duration     = getduration($change->endtime,$change->starttime);
                          $finalReport .= "<td>" . $i++ . "<input type='hidden' id='vehicle" . $z . "' value='" . $vehicleid . "'>
                          <input type='hidden' id='unitno" . $z . "' value='" . $unitno . "'><input type='hidden' id='date" . $z . "' value='" . $lastdate . "'>
                          <input type='hidden' id='timestamp" . $z . "' value='" . $change->starttime . "," . $change->endtime . "'></td>";
                          $finalReport .= "<td>$change->starttime</td>";
                          $finalReport .= "<td>$change->endtime</td>"; 
                          $finalReport .= "<td>".hrs_mins($change->duration)."</td>";

                            if($change->ignition == 1){
                                  $finalReport .= "<td>Power On</td>";
                                  $totalOnTime += (strtotime($change->endtime)-strtotime($change->starttime));
                              } else {
                                    $finalReport .= "<td>Power Off</td>";                                    $totalOffTime += (strtotime($change->endtime)-strtotime($change->starttime));
                              }
                          $finalReport .= "</tr>";
                        }
                  }
                  $finalReport .= '</tbody>';
                  if($totalOnTime >0){
                    $totalOnTime 	= round($totalOnTime/3600,2);
                  }
                  else{
                     $totalOnTime   = "0";
                  }
                  
                if($totalOnTime >0){
                  $totalOffTime = round($totalOffTime/3600,2);
                }
                else{
                   $totalOffTime = "0";
                }
                  $finalReport .= "
                        </table>
                        <div class='container' style='width:45%;'>
                            <table class='table newTable' >
                            <thead>
                                <tr><th colspan = '10'>Statistics</th></tr>
                            </thead>
                            <tbody>
                                <tr><td style='text-align:center;' colspan = '10'>Total Power Off Time = $totalOnTime Hours</td></tr>
                                <tr><td style='text-align:center;' colspan = '10'>Total Power On Time = $totalOffTime Hours</td></tr>
                            </tbody>
                            </table>
                        </div>";
                  echo $finalReport;
    }

   function hrs_mins($val) {
      $hour = floor($val / 60);
      $minutes = ($val) % 60;
      if ($minutes < 10) {
          $minutes = "0" . $minutes;
      }
      $final = $hour . ":" . $minutes;
      return $final;
  }
function get_power_status_report_pdf($vehicleid, $SDate, $EDate, $STime, $ETime, $customerno = '', $vgroupname = null){   		$devicemanager = new DeviceManager($customerno);
	    $vehicleno = $devicemanager->getvehiclenofromdeviceid($vehicleid);
	    $title = 'Power Status Report';
	    $subTitle = array(
	         "Vehicle No: $vehicleno",
	         "Start Date: $SDate $STime",
	         "End Date: $EDate $ETime",
	     );
	     if (!is_null($vgroupname)) {
	         $subTitle[] = "Group-name: $vgroupname";
	     }
	     $customer_details = null;
	     if (!isset($_SESSION['customerno'])) {
	         $cm = new CustomerManager($customerno);
	         $customer_details = $cm->getcustomerdetail_byid($customerno);
	     }
	     echo pdf_header($title, $subTitle, $customer_details);
	     get_power_report_data($vehicleid, $SDate, $EDate, $STime, $ETime,$customerno,'pdf');
	     return $vehicleno;
}
  function get_power_report_data($vehid, $SDate, $EDate, $Shour, $Ehour,$customerno, $report_type) {
                             $um = new UnitManager($customerno);
                             $vehicleid = GetSafeValueString($vehid, 'string');
                             $totaldays = gendays_cmn($SDate, $EDate);
                             $count = count($totaldays);
                             $endelement = end($totaldays);
                             $firstelement = $totaldays[0];
                             $unitno = $um->getunitnofromdeviceid($vehicleid);
                             $days = array();
                             if (isset($totaldays)) {
                                 foreach ($totaldays as $userdate) {
                                     $lastday = power_status_data($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $vehicleid, $Shour, $Ehour);
                                     if ($lastday != null) {
                                         $days = array_merge($days, $lastday);
                                     }
                                 }
                             }
                             if (isset($days) && count($days) > 0) {
                                 $STDate = $SDate . $Shour . ":00";
                                 $ETDate = $EDate . $Ehour . ":00";
                                 switch ($report_type) {
                                 case 'pdf':
                                     dispalyPowerStatusData_pdf($days, $vehicleid, $unitno,$STDate, $ETDate, $customerno);
                                     break;
                                 case 'excel':
                                     dispalyPowerStatusData_excel($customerno, $days, $vehicleid, $unitno,$STDate, $ETDate);
                                     break;
                                 }
                             }
 }
   function dispalyPowerStatusData_pdf($datarows, $vehicleid, $unitno,$STDate, $ETDate, $customerno) {

   echo '<table id="search_table_2" style="width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
   <tbody>';
         $lastdate = null;
         $today = date('d-m-Y', strtotime('Now'));
         $totalOnTime=0;
         $totalOffTime=0;
         if (isset($datarows)) {
             foreach ($datarows as $change) {
                 $comparedate = date('d-m-Y', strtotime($change->endtime));
                 if (strtotime($lastdate) != strtotime($comparedate)) {
                     if ($today == $comparedate) {
                         $todays = date('Y-m-d');
                         $todayhms = date('Y-m-d H:i:s');
                         $to_time = strtotime("$todayhms");
                         $from_time = strtotime("$todays 00:00:00");
                         $totalminute = round(abs($to_time - $from_time) / 60, 2);
                     }
                 
      echo '</tbody></table>';
 
        $lastdate = date('d-m-Y', strtotime($change->endtime));
              
    echo '<hr id="style-six" /><br/>';
    echo '<table  id="search_table_2" align="center" style="width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">';
     echo "<tbody> <tr style='background-color:#CCCCCC;font-weight:bold;'><td style='width:20px;' ></td><td style='width:100px;height:auto;'>Start Time</td><td style='width:100px;height:auto;'>End Time</td> <td style='width:100px;height:auto;'>Duration [HH:MM]</td><td style='width:50px;height:auto;'>Status</td></tr>
        <tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='9' >Date : $lastdate</td></tr>";
        $i = 1;    }
                    //Removing Date Details From DateTime
                    $change->starttime  = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
                    $change->endtime  = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
                    $duration       = hrs_mins($change->duration);
                    echo "<tr>";
                    echo "<td width='20px' >" . $i++ . "</td>";
                    echo "<td style='width:100px;height:auto;'>$change->starttime</td>";
                    echo "<td style='width:100px;height:auto;'>$change->endtime</td>";
                    echo "<td style='width:100px;height:auto;'>$duration</td>";
                    if($change->ignition == 1){
                      echo "<td style='width:100px;height:auto;'>Power On</td>";
                       $totalOnTime += (strtotime($change->endtime)-strtotime($change->starttime));
                    }
                    else{
                      echo "<td style='width:100px;height:auto;'>Power Off</td>";
                      $totalOffTime += (strtotime($change->endtime)-strtotime($change->starttime));
                    }
                    echo "</tr>";
                }
                echo "</tbody></table>";
            }
          
            //$totaldistance = round($totaldistance, 1);
            $totaltime    = round(abs(strtotime($ETDate) - strtotime($STDate)) / 60, 2);

            if($totalOnTime >0){
              $totalOnTime  = round($totalOnTime/3600,2);
            }
            else{
              $totalOnTime  = 0;
            }
            $totalOnTime=0;
            if($totalOffTime>0){
                $totalOffTime = round($totalOffTime/3600,2);
            }
            else{
              $totalOffTime = 0;
            }
          
          
         /*   $offtime = $totaltime - $runningtime - $idle_ign_on;
            $offtime = hrs_mins($offtime);
            $runningtime = hrs_mins($runningtime);
            $idletime = hrs_mins($idletime);
            $idle_ign_on = hrs_mins($idle_ign_on);*/
            echo "
                <div style='margin:15px;margin-right:60px;'>
               <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'><h4>Statistics</h4></td></tr>
                    <tr><td colspan = '9'>Total Running Time = $totalOnTime Hrs</td></tr>
                    <tr><td colspan = '9'>Total Idle Time = $totalOffTime Hrs</td></tr>
                </tbody>
          </table>
        </div>";  
        }

function dispalyPowerStatusData_excel($customerno, $datarows, $vehicleid, $unitno,$STDate, $ETDate) {

                  $report = "";
                  $report .= "<table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
                  $report .= "<tbody>";
                  $t = 1;
                  $totalOnTime=0;
                  $totalOffTime=0;
                  $lastdate = null;
                  $totalminute = 0;
                  $today = date('d-m-Y', strtotime('Now'));
                  if (isset($datarows)) {
                      foreach ($datarows as $change) {
                          $comparedate = date('d-m-Y', strtotime($change->endtime));
                          if (strtotime($lastdate) != strtotime($comparedate)) {
                              if ($today == $comparedate) {
                                  $todays = date('Y-m-d');
                                  $todayhms = date('Y-m-d H:i:s');
                                  $to_time = strtotime("$todayhms");
                                  $from_time = strtotime("$todays 00:00:00");
                                  $totalminute = round(abs($to_time - $from_time) / 60, 2);
                              }
                              $lastdate = date('d-m-Y', strtotime($change->endtime));
                              $report .= "</tbody></table>";
                              $report .= "<table  id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;' >";
                              $report .= "<tbody>";
                              $report .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
                              $report .= "<td style='width:20px;' ></td>";
                              $report .= "<td style='width:50px;height:auto;'>Start Time</td>";
                              $report .= "<td style='width:50px;height:auto;'>End Time</td>";
                              $report .= "<td style='width:100px;height:auto;'>Duration [HH:MM]</td>";
                              $report .= "<td style='width:50px;height:auto;'>Status</td>";
                              $report .= "</tr>";
                              $report .= "<tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='9' >Date" . $lastdate . "</td></tr>";
                              $i = 1;
                          }
                          //Removing Date Details From DateTime
                          $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
                          $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
                          $duration = hrs_mins($change->duration);
                       
                          $customChekpoint = '';
                      
                          $report .= "<tr>";
                          $report .= "<td  width='20px' >" . $i++ . "</td>";
                          $report .= "<td style='width:50px;height:auto;'>$change->starttime</td>";
                          $report .= "<td style='width:50px;height:auto;'>$change->endtime</td>";
                        
                          $report .= "<td style='width:100px;height:auto;'>$duration</td>";
                          if ($change->ignition == 1) {
                              $report .= "<td style='width:50px;height:auto;'>Power On</td>";
                       $totalOnTime += (strtotime($change->endtime)-strtotime($change->starttime));
                          } elseif ($change->ignition == 0) {
                              $report .= "<td style='width:50px;height:auto;'>Power Off</td>";
                       $totalOffTime += (strtotime($change->endtime)-strtotime($change->starttime));
                          } 
                          $report .= "</tr>";
                      }
                      $report .= "</tbody></table>";
                  }
                
                  //$totaldistance = round($totaldistance, 1);
                  $totaltime = round(abs(strtotime($ETDate) - strtotime($STDate)) / 60, 2);
                  $totalOnTime  = round($totalOnTime/3600,2);
                $totalOffTime = round($totalOffTime/3600,2);
                  $report .= "<hr style='margin-top:20px;'>
                <div style=';margin:15px;margin-right:60px;'>
                    <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tbody>
                            <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'><h4>Statistics</h4></td></tr>
                            <tr><td colspan = '9'>Total Running Time = $totalOnTime Hrs</td></tr>
                            <tr><td colspan = '9'>Total Idle Time = $totalOffTime Hrs</td></tr>
                        </tbody>
                  </table>
                </div>";
                  echo $report;
              }
?>