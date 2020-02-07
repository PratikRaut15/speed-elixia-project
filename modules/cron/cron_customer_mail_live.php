<?php
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
include_once 'files/dailyreport.php';
$apt_date = date('d-m-Y', strtotime("- 1 day")); //date('d-m-Y', strtotime("- 1 day"))
$apt_date_closed = date('dmy', strtotime("- 1 day")); //date('dmy', strtotime("- 1 day"))
$dailydate = date('Y-m-d', strtotime("- 1 day")); //date('dmy', strtotime("- 1 day"))

$inactiveDate = date('d-m-Y'); //date('dmy', strtotime("- 1 day"))
$objCustomer = new CustomerManager();
$customernos = $objCustomer->getcustomernos_tracking();
//$customernos = array(3);
if (isset($customernos)) {
    foreach ($customernos as $thiscustomerno) {
        $customerDetails = $objCustomer->getcustomerdetail_byid($thiscustomerno);
        $count = 0;
        $message = "";
        $objVehicleManager = new VehicleManager($thiscustomerno);
        $min = $objVehicleManager->getTimezoneDiffInMin($thiscustomerno);
        if (!empty($min)) {
            $date = date('Y-m-d H:i:s', time() + $min);
            $date = date('Y-m-d H:i:s', strtotime($date) - 3600);
        } else {
            $date = date('Y-m-d H:i:s', time() - 3600);
        }
        $objDailyReport = new stdClass();
        $objDailyReport->customerno = $thiscustomerno;
        $objDailyReport->dailydate = $dailydate;
        $devices = $objVehicleManager->getvehiclesforrtd_all_inactive_bycustomer($thiscustomerno);
        $activeCount = 0;
        $notActiveCount = 0;
        if (isset($devices)) {
            foreach ($devices as $active) {
                if ($active->lastupdated > $date) {
                    $activeCount++;
                } else {
                    $notActiveCount++;
                }
            }
        }
        $objDRM = new DailyReportManager($thiscustomerno);
        $Data = $objDRM->getDailyReportDetails($objDailyReport);
        $highestDistance = 0;
        $vehicleMoved = 0;
        $vehicleNotMoved = 0;
        $arrNotMoved = array();
        $arrOverspeed = array();
        if (isset($Data)) {
            foreach ($Data as $thisdata) {
                $veh_no = $thisdata->vehicleno;
                $groupname = $thisdata->groupname;
                if ($thisdata->overspeed > 0) {
                    $objOverspeed = new stdClass();
                    $objOverspeed->veh_no = $veh_no;
                    $objOverspeed->groupname = $groupname;
                    $objOverspeed->topspeed = $thisdata->topspeed;
                    $objOverspeed->location = location($thisdata->topspeed_lat, $thisdata->topspeed_long, $customerDetails->use_geolocation, $customerDetails->customerno);
                    $objOverspeed->topspeed_time = convertDateToFormat($thisdata->topspeed_time,speedConstants::DEFAULT_TIME);
                    $arrOverspeed[] = $objOverspeed;
                }
                if ($thisdata->totaldistance > 1000) {
                    $vehicleMoved++;
                } else {
                    $vehicleNotMoved++;
                    $objNotMoved = new stdClass();
                    $objNotMoved->veh_no = $veh_no;
                    $objNotMoved->groupname = $groupname;
                    $arrNotMoved[] = $objNotMoved;
                }
                if ($thisdata->totaldistance > $highestDistance) {
                    $highestDistance = $thisdata->totaldistance;
                    $highest_vehno = $veh_no;
                    $highest_groupname = $groupname;
                }
            }
        }
        $message .= "
            <table border=1 style='text-align:center;'>
            <tr>
            <th>Total Devices Installed</th>
            <th>Active Devices</th>
            <th>Vehicles Moved</th>
            <th>Vehicles Not Moved</th>
            <th>Inactive Devices</th>
            </tr>
            <tr>
            <td>" . count($devices) . "</td>
                <td>" . $activeCount . "</td>
                <td>" . $vehicleMoved . "</td>
                <td>" . $vehicleNotMoved . "</td>
                <td>" . $notActiveCount . "</td>
            </tr>
            </table><br/>";
        if (isset($arrOverspeed)) {
            $message .= "<table border=1  style='text-align:center;'>
                    <tr ><th colspan='7' style='background-color:#ccc;' > Overspeed Details for " . $apt_date . "</th></tr>
                    <tr><th>Vehicle No.</th><th>Group</th><th>Top Speed</th><th>Top Speed Location</th><th>Top Speed Time</th></tr>";
            foreach ($arrOverspeed as $vehicle) {
                $message .= "<tr><td>$vehicle->veh_no</td><td>$vehicle->groupname</td><td>$vehicle->topspeed</td><td>$vehicle->location</td><td>$vehicle->topspeed_time</td></tr>";
            }
            if (empty($arrOverspeed)) {
                $message .= "<tr><td colspan='7'>No Vehicles Found</td></tr>";
            }
            $message .= "</table><br/>";
        }
        if ($highestDistance > 0) {
            $message .= "<br/>";
            $message .= "<table border=1  style='text-align:center;'>
                        <tr ><th colspan='3' style='background-color:#ccc;' > Vehicle That Travelled Highest Distance on " . $apt_date . "</th></tr>
                        <tr><th>Vehicle No.</th><th>Group</th><th>Distance Travelled (in kms)</th></tr>";
            $message .= "<td>$highest_vehno</td><td>$highest_groupname</td><td align='center'>" . round(($highestDistance / 1000), 2) . "</td>";
            $message .= "</table><br/>";
        }
        if (isset($arrNotMoved)) {
            $message .= "<br/>";
            $message .= "<table border=1  style='text-align:center;'>
                        <tr ><th colspan='3' style='background-color:#ccc;' > Vehicles That Did Not Move on " . $apt_date . "</th></tr>";
            $message .= "<tr><th>Vehicle No.</th><th>Group</th></tr>";
            foreach ($arrNotMoved as $vehicle) {
                $message .= "<tr><td>$vehicle->veh_no</td><td>$vehicle->groupname</td></tr>";
            }
            if (empty($arrNotMoved)) {
                $message .= "<tr><td colspan='3'>No Vehicles Found</td></tr>";
            }
            $message .= "</table><br/>";
        }
        $message .= "<br/>";
        $message .= "<table border=1  style='text-align:center;'>
                    <tr ><th colspan='6' style='background-color:#ccc;' > Inactice Vehicle List for  " . $inactiveDate . "</th></tr>
                    <tr><th>Vehicle No.</th><th>Group</th><th>Inactive Since</th><th>Status</th></tr>";
        if (isset($devices)) {
            foreach ($devices as $device) {
                if ($device->lastupdated < $date) {
                    // Reason for Inactiveness
                    $reason = "";
                    if ($device->powercut == 0) {
                        $reason = "Power Cut";
                    } elseif (round($device->gsmstrength / 31 * 100) < 30) {
                        $reason = "Low Network";
                    } elseif ($device->gprsregister < 14) {
                        $reason = "Data Packet Inactive";
                    } elseif ($device->tamper == 1) {
                        $reason = "Tampered";
                    } else {
                        $reason = "Under Observation";
                    }
                    // Last Updated
                    $lastupdated = getduration_cron($device->lastupdated);
                    $device->groupname = ($device->groupname != '') ? $device->groupname : "Ungrouped";
                    $count++;
                    $message .= "<tr><td>$device->vehicleno</td><td>$device->groupname</td><td>$lastupdated</td><td>$reason</td></tr>";
                }
            }
            if (empty($devices)) {
                $message .= "<tr><td colspan='6'>No Vehicles Found</td></tr>";
            }
            $message .= "</table><br/>";
        }

        $um = new UserManager($thiscustomerno);
        $users = $um->getuseremailsforcustomeradmin($thiscustomerno);
        //print_r($users);echo "<br/>";
        if (isset($users)) {
            foreach ($users as $thisuser) {
                if (isset($thisuser->email) && $thisuser->email != "") {
                    $subject = "Vehicle Movement Report for " . $apt_date;
                    $premessage = "Dear  " . $thisuser->realname . ",<br/><br> The following is the status of installed GPS devices in your vehicles. Feel free to mail us at support@elixiatech.com in case of any issues.<br></br/> ";
                    $arrToMailIds = (array) $thisuser->email;
                    $strCCMailIds = '';
                    $strBCCMailIds = '';
                    $content = $premessage . $message;
                    $attachmentFilePath = '';
                    $attachmentFileName = '';
                    $isMailSent = sendMailUtil($arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $content, $attachmentFilePath, $attachmentFileName);
                    if (isset($isMailSent)) {
                        echo $content;
                    }
                }
            }
        }
    }

}
function getduration_cron($StartTime) {
    $EndTime = date('Y-m-d H:i:s');
    //echo $EndTime.'_'.$StartTime.'<br>';
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($idleduration / (365 * 60 * 60 * 24));
    $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    if ($years >= '1' || $months >= '1') {
        $diff = date('d-m-Y', strtotime($StartTime));
    } elseif ($days > 0) {
        $diff = $days . ' days ' . $hours . ' hrs ';
    } elseif ($hours > 0) {
        $diff = $hours . ' hrs and ' . $minutes . ' mins ';
    } elseif ($minutes > 0) {
        $diff = $minutes . ' mins ';
    } else {
        $seconds = strtotime($EndTime) - strtotime($StartTime);
        $diff = $seconds . ' sec';
    }
    return $diff;
}
function location($lat, $long, $usegeolocation, $customerno) {
    $address = null;
    $customerno = (!isset($customerno)) ? $_SESSION['customerno'] : $customerno;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}
?>
