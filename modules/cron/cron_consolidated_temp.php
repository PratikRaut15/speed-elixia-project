<?php

require "../../lib/system/utilities.php";
require '../../lib/bo/DeviceManager.php';
require '../../lib/bo/VehicleManager.php';
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
require '../../lib/bo/CommunicationQueueManager.php';
include_once '../../lib/model/TempConversion.php';

$cm = new CustomerManager();
$customernos = $cm->getcustomernos_for_temp();
$temp_conversion = new TempConversion;
if (isset($customernos)) {
    foreach ($customernos as $thiscustomer) {

        $count = 0;
        $message = "";

        $dm = new VehicleManager($thiscustomer->customerno);
        $min = $dm->getTimezoneDiffInMin($thiscustomer->customerno);
        if (!empty($min)) {
            $date = date('Y-m-d H:i:s', time() + $min);
            $date = date('Y-m-d H:i:s', strtotime($date) - 3600);
        } else {
            $date = date('Y-m-d H:i:s', time() - 3600);
        }
        //echo $thiscustomer->customerno;
        //echo $thiscustomer->temp_sensor;
        $devices = $dm->getvehicles_Temp_bycustomer($thiscustomer->customerno);
        //print_r($devices);die();
        if (isset($devices)) {
            $temp_conversion->unit_type = $devices->get_conversion;
            if (isset($devices->type) && $devices->type == 'Warehouse') {
                $temp_conversion->switch_to = 3;
            }
            if (isset($devices->use_humidity)) {
                $temp_conversion->use_humidity = $devices->use_humidity;
            }
            $message .= "<table border='1'>
                        <tr>
                            <th>Vehicle No</th>";
            if ($thiscustomer->temp_sensor == 1) {
                $message .="<th>Temperature 1</th>";
            } else if ($thiscustomer->temp_sensor == 2) {
                $message .="<th>Temperature 1</th><th>Temperature 2</th>";
            } else {
                $message .="<th>Temperature </th>";
            }
            $message.="<th>Temperature Recorded At</th>";
            $message.="<th>Group Name</th>";
            $message .="</tr>
                         ";

            foreach ($devices as $device) {

                $message.="<tr>";
                $message.="<td>$device->vehicleno</td>";
                if ($thiscustomer->temp_sensor == 1) {
                    $temp = 'Not Active';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_conversion);
                    } else
                        $temp = '-';

                    if ($temp != '-' && $temp != "Not Active")
                        $message.="<td>$temp <sup>0</sup>C</td>";
                    else
                        $message.="<td>$temp</td>";
                }else if ($thiscustomer->temp_sensor == 2) {
                    $temp1 = 'Not Active';
                    $temp2 = 'Not Active';

                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_conversion);
                    } else
                        $temp1 = '-';

                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_conversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_conversion);
                    } else
                        $temp2 = '-';

                    if ($temp1 != '-' && $temp1 != "Not Active") {
                        $message.="<td class='tempcl'>$temp1 <sup>0</sup>C</td>";
                    } else {
                        $message.="<td class='tempcl'>$temp1</td>";
                    }

                    if ($temp2 != '-' && $temp2 != "Not Active") {
                        $message.="<td class='tempc2'>$temp2 <sup>0</sup>C</td>";
                    } else {
                        $message.="<td class='tempc2'>$temp2</td>";
                    }
                } else {
                    $message .="<td>NA</td>";
                }
                $message.="<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($device->lastupdated)) . "</td>";
                if (empty($device->groupname)) {
                    $groupname = "N/A";
                } else {
                    $groupname = $device->groupname;
                }

                $message.="<td>" . $groupname . "</td>";
                $message.="</tr>";
            }



            $message .= "</table>";
            $message.="<br/><br/>";
            $message.=" Feel free to mail us at support@elixiatech.com with Customer No. on the Subject Line in case of any issues.<br/><br/>";
            $message.="Regards,<br/>";
            $message.="Support Team<br/>";
            $message.="Elixia Tech.";

            $message.="<br/><br/>";
            //echo $message;

            $um = new UserManager($thiscustomer->customerno);
            $users = $um->getuseremailsforcustomeradmin($thiscustomer->customerno);
            //print_r($users);echo "<br/>";
            if (isset($users)) {
                foreach ($users as $thisuser) {
                    if (isset($thisuser->email) && $thisuser->email != "") {

                        $cqm = new CommunicationQueueManager();
                        $cvo = new VOCommunicationQueue();
                        $cvo->email = $thisuser->email;
                        $cvo->realname = $thisuser->realname;

                        $cvo->subject = "Consolidated Temperature Report";
                        $cvo->premessage = "Dear  " . $cvo->realname . ",<br/><br> The following is the consolidated temperature report of all the installed devices.<br></br/> ";
                        $cvo->premessage.=" Customer No.: " . $thiscustomer->customerno . "<br></br/>";
                        if (sendMail($cvo->email, $cvo->subject, $cvo->premessage, $message) == false) {
                            //echo $message;echo "<br/>";
                        }
                    }
                }
            }
        }
    }
}

function sendMail($to, $subject, $premessage, $content) {

    $subject = $subject;
    $content = $premessage . $content;
//    echo $content;

    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;
}
