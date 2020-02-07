<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
    require_once "../../lib/system/utilities.php";
    require_once '../../lib/autoload.php';
    $reportDate = date('d-m-Y', strtotime("- 1 day")); //date('d-m-Y', strtotime("- 1 day"))
    $sqliteDay = date('Y-m-d', strtotime("- 1 day")); //date('d-m-Y', strtotime("- 1 day"))
    $objCustomer = new CustomerManager();
    //$customers = $objCustomer->getcustomernos_tracking();
    $customers = array(135);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Stoppage Report</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style type="text/css">
            body{
                font-family:Arial;
                font-size: 11pt;
            }
            table{
                text-align: center;
                border-right:1px solid black;
                border-bottom:1px solid black;

                border-collapse:collapse;
                font-family:Arial;
                font-size: 10pt;
                width: 60%;
            }

            td, th{
                border-left:1px solid black;
                border-top:1px solid black;
            }

            .colHeading{
                background-color: #D6D8EC;
            }

            span{
                font-weight:bold;
            }
        </style>
    </head>
    <body>
    <?php
        if (isset($customers)) {
            foreach ($customers as $thiscustomerno) {
                $objVehicleManager = new VehicleManager($thiscustomerno);
                $objUserManager = new UserManager();
                $timezone = $objCustomer->timezone_name_cron('Asia/Kolkata', $thiscustomerno);
                date_default_timezone_set('' . $timezone . '');

                $customerDetails = $objCustomer->getcustomerdetail_byid($thiscustomerno);
                $arrUsers = $objUserManager->getadminforcustomer($thiscustomerno);
                $min = $objVehicleManager->getTimezoneDiffInMin($thiscustomerno);
                if (!empty($min)) {
                    $date = date('Y-m-d H:i:s', time() + $min);
                    $date = date('Y-m-d H:i:s', strtotime($date) - 3600);
                } else {
                    $date = date('Y-m-d H:i:s', time() - 3600);
                }
                if ($arrUsers) {
                    foreach ($arrUsers as $user) {
                        $message = "";
                        $arrGroupIds = array();
                        $groups = $objUserManager->get_groups_fromuser($thiscustomerno, $user->userid);
                        if (isset($groups)) {
                            foreach ($groups as $group) {
                                $arrGroupIds[] = $group->groupid;
                            }
                        } else {
                            $arrGroupIds[] = 0;
                        }
                        $devices = $objVehicleManager->get_all_vehicles_by_group($arrGroupIds);
                        $arrCustomersData = array();
                        if (isset($devices) && !empty($devices)) {
                            foreach ($devices as $device) {
                                $location = "../../customer/" . $thiscustomerno . "/unitno/" . $device->unitno . "/sqlite/" . $sqliteDay . ".sqlite";
                                if (file_exists($location)) {
                                    //$location = "sqlite:" . $location;
                                    $objReport = new stdClass();
                                    $objReport->location = $location;
                                    $objReport->deviceId = $device->deviceid;
                                    $objReport->reportDate = $sqliteDay;
                                    $objReport->startTime = "00:00:00";
                                    $objReport->endTime = "23:59:59";
                                    $objReport->interval = 360;
                                    $arrData = getStoppageReport($objReport);
                                    if (isset($arrData) && !empty($arrData)) {
                                        $result['status'] = 1;
                                        $result['vehicleNo'] = $device->vehicleno;
                                        $result['vehicleData'] = $arrData;
                                        $arrCustomersData[] = $result;
                                    } else {
                                        $result['status'] = 0;
                                        $result['vehicleNo'] = $device->vehicleno;
                                        $result['vehicleData'] = "";
                                        $arrCustomersData[] = $result;
                                    }
                                } else {
                                    $result['status'] = 0;
                                    $result['vehicleNo'] = $device->vehicleno;
                                    $result['vehicleData'] = "";
                                    $arrCustomersData[] = $result;
                                }
                            }
                        }
                        //print_r($arrCustomersData);
                        if (isset($arrCustomersData) && !empty($arrCustomersData)) {
                            $message .= "<table>";
                            $message .= "<tr>";
                            $message .= "<th colspan='4' class='colHeading'> Stoppage Report </th>";
                            $message .= "</tr>";
                            foreach ($arrCustomersData as $data) {
                                $message .= "<tr><th colspan='4' class='colHeading'>" . $data['vehicleNo'] . "</th></tr>";
                                if ($data['status'] == 1) {
                                    $message .= "<tr>";
                                    $message .= "<th class='colHeading'>Start Time</th>";
                                    $message .= "<th class='colHeading'>End Time</th>";
                                    $message .= "<th class='colHeading'>Location</th>";
                                    $message .= "<th class='colHeading'>Hold Time [HH:MM]</th>";
                                    $message .= "</tr>";
                                    foreach ($data['vehicleData'] as $row) {
                                        $GeoCoder_Obj = new GeoCoder($customerDetails->customerno);
                                        $stoppageLocation = $GeoCoder_Obj->get_location_bylatlong($row->devicelat, $row->devicelong);
                                        $secdiff = strtotime($row->endtime) - strtotime($row->starttime);
                                        $minutesdiff = floor($secdiff / 60);
                                        if (floor($minutesdiff / 60) < 10) {
                                            $hourdiff = "0" . floor($minutesdiff / 60);
                                        } else {
                                            $hourdiff = floor($minutesdiff / 60);
                                        }
                                        if (floor($minutesdiff % 60) < 10) {
                                            $hourremainder = "0" . floor($minutesdiff % 60);
                                        } else {
                                            $hourremainder = floor($minutesdiff % 60);
                                        }
                                        $minutesdiff = $hourdiff . ":" . $hourremainder;
                                        $message .= "<tr>";
                                        $message .= "<td>" . date(speedConstants::DEFAULT_TIME, strtotime($row->starttime)) . "</td>";
                                        $message .= "<td>" . date(speedConstants::DEFAULT_TIME, strtotime($row->endtime)) . "</td>";
                                        $message .= "<td>" . $stoppageLocation . "</td>";
                                        $message .= "<td>" . $minutesdiff . "</td>";
                                        $message .= "</tr>";
                                    }

                                } else {
                                    $message .= "<tr><td colspan='4'>Data Not Available / Vehicle Has Not Exceeded Hold Time Of 6 Hours.</td></tr>";
                                }
                            }
                            $message .= "</table><br/>";
                        }
                        if (isset($user->email) && $user->email != "" && !empty($arrCustomersData)) {
                            $subject = "Stoppage Report for " . $reportDate;
                            $premessage = "Dear  " . $user->realname . ",<br/><br> The following is the stoppage report with more than 6 hour hold time of installed GPS devices in your vehicles. Feel free to mail us at support@elixiatech.com in case of any issues.<br></br/> ";
                            $arrToMailIds = (array) $user->email;
                            $strCCMailIds = '';
                            $strBCCMailIds = 'sshrikanth@elixiatech.com';
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
    ?>
    </body>
</html>
<?php
    function getStoppageReport($objReport) {
        $location = "sqlite:" . $objReport->location;
        $devices = array();
        $query = "SELECT devicehistory.lastupdated, vehiclehistory.odometer, vehiclehistory.vehicleno, devicehistory.devicelat, vehiclehistory.vehicleid, devicehistory.devicelong
            FROM devicehistory INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $objReport->deviceId AND devicehistory.status!='F'
            AND devicehistory.lastupdated > '$objReport->reportDate $objReport->startTime'
            AND devicehistory.lastupdated < '$objReport->reportDate $objReport->endTime'
            ORDER BY devicehistory.lastupdated ASC";
        try
        {
            $database = new PDO($location);
            $result = $database->query($query);
            if (isset($result) && $result != "") {
                $lastupdated = "";
                $lastodometer = "";
                $pusharray = 1;
                foreach ($result as $row) {
                    if ($lastodometer == "") {
                        $lastodometer = $row["odometer"];
                        $lastupdated = $row['lastupdated'];
                        $device = new stdClass();
                        $device->vehicleid = $row['vehicleid'];
                        $device->vehicleno = $row['vehicleno'];
                        $device->starttime = $row['lastupdated'];
                        $device->deviceid = $row['vehicleid'];
                    }
                    if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > $objReport->interval && $row["odometer"] - $lastodometer < 100 && $pusharray == 1) {
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->deviceid = $row['vehicleid'];
                        $lastodometer = $row["odometer"];
                        $lastupdated = $row['lastupdated'];
                        $pusharray = 0;
                    } else {
                        if ($row["odometer"] - $lastodometer > 25) {
                            if ($pusharray == 0) {
                                $device->endtime = $row['lastupdated'];
                                $devices[] = $device;
                            }
                            $lastodometer = $row["odometer"];
                            $lastupdated = $row['lastupdated'];
                            $device = new stdClass();
                            $device->vehicleid = $row['vehicleid'];
                            $device->vehicleno = $row['vehicleno'];
                            $device->starttime = $row['lastupdated'];
                            $device->deviceid = $row['vehicleid'];
                            $pusharray = 1;
                        }
                    }
                }
                if ($pusharray == 0) {
                    $device->endtime = $row['lastupdated'];
                    $device->deviceid = $row['vehicleid'];
                    $devices[] = $device;
                }
            }
        } catch (PDOException $e) {
            die($e);
        }
        return $devices;
    }

?>
