<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    $RELATIVE_PATH_DOTS = "../../../../";
    require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
    require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
    $message = "";
    $message .= "The following vehicles went inactive 10 min ago: <br/><br/>";
    $message .= "<table border = 1 style='text-align:center;'><tr><th>Sr No.</th><th>Vehicle No.</th><th>Unit No.</th><th>Last Updated</th></tr>";
    $x = 1;

    $arrUnits = array(
        '1901010025219',
        '1901010025243',
        '1844010024040',
        '1901010025151',
        '1901010024931',
        '1739010013870',
        '1901010024915',
        '1901010024923',
        '1901010025011',
        '1901010025128',
        '1901010024998',
        '1747010016496',
        '1737010013148',
        '1747010016462',
        '1845010024419',
        '1734010010356',
        '1844010024156',
        '1901010024881',
        '1747010016488',
        '1901010025235',
        '1901010025037',
        '1901010025060',
        '1901010025227',
        '1915010029096',
        '1845010024526',
        '1743010015802',
        '1737010012504',
        '1901010025144',
        '1901010025086',
        '1901010025185',
    );
    //print_r($arrUnits);die();
    $ServerDate = date("Y-m-d H:i:s");
    $ServerDateIST_Less10 = date("Y-m-d H:i:s", strtotime('-10 min'));
    $vehicleManager = new VehicleManager(577);
    //print_r($vehicleManager);die();
    $devices = $vehicleManager->getvehiclesforrtd_all_by_customer();
    //print_r($devices);die();
    if (isset($devices)) {
        foreach ($devices as $device) {
            //if (in_array($device->unitno,$arrUnits)) {
            if ($device->customerno == 577) {
                $lastupdated = date(speedConstants::DEFAULT_DATETIME, strtotime($device->lastupdated));
                if (strtotime($device->lastupdated) < strtotime($ServerDateIST_Less10)) {
                    $message .= "<tr><td>$x</td><td>$device->vehicleno</td><td>$device->unitno</td><td>$lastupdated</td></tr>";
                    $x++;
                }
            }
        }
    }

    if ($x == 1) {
        $message .= "<tr><td colspan=9>No Vehicles Found Inactive</td></tr>";
    }

    $message .= "</table>";
    $message .= "<br/><br/>";
    $message .= "Be proactive and take necessary actions ASAP.";
    $subject = "Licious Inactive Vehicle List - More Than 10 Min";
    echo ($message);


    if (sendMail('dinesht@elixiatech.com', $subject, $message) == false) {
    // Do nothing
    }

    if (sendMail('shrikants@elixiatech.com', $subject, $message) == false) {
    // Do nothing
    }

    /*
    if (sendMail('mihir@elixiatech.com', $subject, $message) == false) {
    // Do nothing
    }
    */

    function sendMail($to, $subject, $content) {
        $subject = $subject;

        $headers = "From: noreply@elixiatech.com\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        if (!@mail($to, $subject, $content, $headers)) {
            // message sending failed
            return false;
        }
        return true;
    }

?>

