<?php

require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/DeviceManager.php';
require_once '../../lib/bo/CustomerManager.php';
?>
<?php

$cm = new CustomerManager();
$customernos = Array(64);
$message = "<html>";
$message.="<head>
            <style type='text/css'>
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

            #tblVehicleCount
            ,#tblVehicleCount th
            ,#tblVehicleCount td{
                border-right:0;
                border-bottom:0;
                border-left:0;
                border-top:0;
                text-align:left;
                width: 40%;
            }
        </style>
        </head>
        <body>";

$message .= "Dated: " . date("d-M-Y");
$message .= "<br/><br/>";
$message .= "Dear Sir,";
$message .= "<br/><br/>";
$message .= "Please find the Informatics Summary for Company Vehicle as of " . date('M Y', strtotime("first day of last month"));
$message .= "<br/><br/>";
if (isset($customernos)) {
    foreach ($customernos as $thiscustomerno) {
        // Print Period
        $timezone = $cm->timezone_name_cron('Asia/Kolkata', $thiscustomerno);
        date_default_timezone_set('' . $timezone . '');
        ini_set('date.timezone', '' . $timezone . '');
        $mon = strtotime('last month', time());
        $period = date("M, Y", $mon);
        $month_start = strtotime('first day of last month', time());
        $month_end = strtotime('last day of last month', time());
        $dmonthstart = date("d-m-Y", $month_start);
        $dmonthend = date("d-m-Y", $month_end);

        $message.="<table><tr class='colHeading'>"
                . "<td><strong>Period</strong></td>"
                . "<td><strong>From</strong></td>"
                . "<td><strong>To</strong></td></tr>";
        $message.="<tr>"
                . "<td>$period</td>"
                . "<td>$dmonthstart</td>"
                . "<td>$dmonthend</td></tr>";
        $message.="</table>";
        $message.="<br/>";

        // Total No. of vehicles being tracked
        $dm = new DeviceManager($thiscustomerno);
        $totalno = $dm->gettotalnodevices($thiscustomerno);

        $message.="<table id='tblVehicleCount'><tr><td><strong>Total No. of Vehicles</strong></td>";
        $message.="<td><strong>$totalno</strong></td></tr>";
        $message.="</table>";
        // Zonewise Distribution of vehicles
        $zones = $dm->getzones($thiscustomerno);
        if (isset($zones)) {
            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 0; $i < 4; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnoveh($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 0; $i < 4; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnoveh($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";

            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 4; $i < 8; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnoveh($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 4; $i < 8; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnoveh($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";

            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 8; $i < 12; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnoveh($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 8; $i < 12; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnoveh($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";
        }

        // Vehicle Type
        $vehtype = $dm->getvehtype($thiscustomerno);
        $message.="<strong>Vehicle Type</strong> <br/>";
        $message.="<table><tr class='colHeading'>"
                . "<td><strong>New</strong></td>"
                . "<td><strong>Repossessed</strong></td>"
                . "<td><strong>Total</strong></td></tr>";
        $message.="<tr><td>" . ($vehtype->new + $vehtype->employee + $vehtype->undefined) . "</td>"
                . "<td>$vehtype->repossessed</td><td>$vehtype->total</td></tr>";
        $message.="</table>";
        $message.="<br/>";

        // Vehicle Purchase
        $vehpurpose = $dm->getvehpurpose($thiscustomerno);
        $message.="<strong>Vehicle Purpose</strong> <br/>";
        $message.="<table><tr class='colHeading'>"
                . "<td><strong>Branch</strong></td>"
                . "<td><strong>Employee CTC</strong></td>"
                . "<td><strong>HO</strong></td>"
                . "<td><strong>Regional Office</strong></td>"
                . "<td><strong>Zonal Office</strong></td>"
                . "<td><strong>Undefined</strong></td>"
                . "<td><strong>Total</strong></td></tr>";
        $message.="<tr><td>$vehpurpose->branch</td>"
                . "<td>$vehpurpose->ctc</td>"
                . "<td>$vehpurpose->ho</td>"
                . "<td>$vehpurpose->region</td>"
                . "<td>$vehpurpose->zone</td>"
                . "<td>$vehpurpose->undefined</td>"
                . "<td>$vehpurpose->total</td></tr>";
        $message.="</table>";
        $message.="<br/>";

        // Yearwise Manufacturing Date
        $manyear = $dm->getmanufacturingyear($thiscustomerno);
        $message.="<strong>Yearwise Manufacturing Date</strong><br/>";
        if (isset($manyear)) {
            $message.="<table><tr class='colHeading'>"
                    . "<td><strong>Manufacture Year</strong></td>"
                    . "<td><strong>No. of Vehicles</strong></td></tr>";
            foreach ($manyear as $thisyear) {
                $noofveh = $dm->getnovehmandate($thisyear->year, $thiscustomerno);
                $message.="<tr>";
                $message.= "<td>" . $thisyear->year . "</td>";
                $message.= "<td>" . $noofveh . "</td>";
                $message.= "</tr>";
            }
        }
        $message.="</table>";
        $message.="<br/>";

        // Yearwise Purchase Date
        $purchaseyear = $dm->getpurchaseyear($thiscustomerno);
        $message.="<strong>Yearwise Purchase Date</strong> <br/>";
        if (isset($purchaseyear)) {
            $message.="<table><tr class='colHeading'>"
                    . "<td><strong>Purchase Year</strong></td>"
                    . "<td><strong>No. of Vehicles</strong></td></tr>";
            foreach ($purchaseyear as $thisyear) {
                $noofveh = $dm->getnovehpurdate($thisyear->year, $thiscustomerno);
                $message.="<tr>";
                $message.= "<td>" . $thisyear->year . "</td>";
                $message.= "<td>" . $noofveh . "</td>";
                $message.= "</tr>";
            }
        }
        $message.="</table>";
        $message.="<br/>";


        // Registration
        $vehtype = $dm->getvehregistered($thiscustomerno);
        $message.="<table><tr class='colHeading'>"
                . "<td><strong>Vehicles Registered</strong></td>"
                . "<td><strong> Vehicles Not Registered</strong></td>"
                . "</tr>";
        $message.="<tr><td>$vehtype->registered</td>"
                . "<td>$vehtype->notregistered</td>"
                . "</tr>";
        $message.="</table>";
        $message.="<br/>";

        // Zonewise Not Registered
        if (isset($zones)) {
            $message.="<strong>Vehicles Not Registered</strong> <br/>";
            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 0; $i < 4; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnovehnotreg($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 0; $i < 4; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnovehnotreg($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";

            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 4; $i < 8; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnovehnotreg($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "<strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 4; $i < 8; $i++) {
                $noofveh = $dm->getnovehnotreg($zones[$i]->zoneid, $thiscustomerno);
                $message.="<td width='25%'>$noofveh</td>";
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";

            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 8; $i < 12; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnovehnotreg($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 8; $i < 12; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getnovehnotreg($zones[$i]->zoneid, $thiscustomerno);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";

            /*
              $message.="Vehicles Not Registered <br/>";
              $message.="<table><tr><td>Zone</td><td>No. of Vehicles</td></tr>";

              foreach($zones as $thiszone)
              {
              $noofveh = $dm->getnovehnotreg($thiszone->zoneid, $thiscustomerno);
              $message.="<tr><td>$thiszone->name</td><td>$noofveh</td></tr>";

              }
              $message.="</table>";
              $message.="<br/>";
             * 
             */
        }

        // Insurance
        $vehinsure = $dm->getvehinsured($thiscustomerno);
        $vehnotinsure = $dm->getveh_notinsured($thiscustomerno);
        $message.="<table><tr class='colHeading'>"
                . "<td><strong>Vehicles Insured</strong></td>"
                . "<td><strong>Vehicles Not Insured</strong></td>"
                . "</tr>";
        $message.="<tr><td>$vehinsure</td>"
                . "<td>$vehnotinsure</td>"
                . "</tr>";
        $message.="</table>";
        $message.="<br/>";

        // Zonewise Not Insured
        if (isset($zones)) {
            $message.="<strong>Vehicles Not Insured</strong> <br/>";
            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 0; $i < 4; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_notinsuredzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 0; $i < 4; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_notinsuredzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";

            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 4; $i < 8; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_notinsuredzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 4; $i < 8; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_notinsuredzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";

            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 8; $i < 12; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_notinsuredzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 8; $i < 12; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_notinsuredzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";
            /*
              $message.="Vehicles Not Insured <br/>";
              $message.="<table><tr><td>Zone</td><td>No. of Vehicles</td></tr>";

              foreach($zones as $thiszone)
              {
              $vehnoins = $dm->getveh_notinsuredzone($thiscustomerno,$thiszone->zoneid);
              $message.="<tr><td>$thiszone->name</td><td>$vehnoins</td></tr>";

              }
              $message.="</table>";
              $message.="<br/>";
             * 
             */
        }

        //insurance all sums
        $insum = $dm->getinsurance_sums($thiscustomerno);
        $message.="<table><tr class='colHeading'>"
                . "<td></td>"
                . "<td><strong>Premium Paid For Insurance</strong></td>"
                . "<td><strong>Total Insured Value</strong></td>"
                . "<td><strong>% of Insured Value</td></strong></tr>";
        $message.="<tr><td><strong>Total in INR</strong></td>"
                . "<td>$insum->premium</td>"
                . "<td>$insum->value</td>"
                . "<td>$insum->percent</td></tr>";
        $message.="</table>";
        $message.="<br/>";

        //insurance all sums
        //$insexp = $dm->getinsurance_expiry($thiscustomerno);   
        //print_r($insum);
        $message.="<strong>Insurance Expiring In Days</strong><br/>";

        $message.="<table>";
        $message.="<tr class='colHeading'>";
        $message.="<td>30</td>";
        $message.="<td>45</td>";
        $message.="<td>60</td>";
        $message.="<td>75</td>";
        $message.="<td>90</td>";
        $message.="<td>Above 90</td>";
        $message.="</tr>";

        $message.="<tr>";
        $message.="<td>" . $dm->getinsurance_expiry($thiscustomerno, 30) . "</td>";
        $message.="<td>" . $dm->getinsurance_expiry($thiscustomerno, 45) . "</td>";
        $message.="<td>" . $dm->getinsurance_expiry($thiscustomerno, 60) . "</td>";
        $message.="<td>" . $dm->getinsurance_expiry($thiscustomerno, 75) . "</td>";
        $message.="<td>" . $dm->getinsurance_expiry($thiscustomerno, 90) . "</td>";
        $message.="<td>" . $dm->getinsurance_expiry($thiscustomerno, 100) . "</td>";
        $message.="</tr>";

        $message.="</table>";
        $message.="<br/>";
        /*
          $message.="<table><tr><td>In Days</td><td>Count</td></tr>";
          $message.="<tr><td>30</td><td>".$dm->getinsurance_expiry($thiscustomerno,30)."</td></tr>";
          $message.="<tr><td>45</td><td>".$dm->getinsurance_expiry($thiscustomerno,45)."</td></tr>";
          $message.="<tr><td>60</td><td>".$dm->getinsurance_expiry($thiscustomerno,60)."</td></tr>";
          $message.="<tr><td>75</td><td>".$dm->getinsurance_expiry($thiscustomerno,75)."</td></tr>";
          $message.="<tr><td>90</td><td>".$dm->getinsurance_expiry($thiscustomerno,90)."</td></tr>";
          $message.="<tr><td>Above 90</td><td>".$dm->getinsurance_expiry($thiscustomerno,100)."</td></tr>";
          $message.="</table>";
          $message.="<br/>";
         * 
         */

        // Zonewise  Insurance Amount
        if (isset($zones)) {
            $message.="<strong>Total Insurance Amount</strong> <br/>";
            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 0; $i < 4; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_insureamtzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 0; $i < 4; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_insureamtzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";

            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 4; $i < 8; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_insureamtzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 4; $i < 8; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_insureamtzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";

            $message.="<table>";
            $message.="<tr class='colHeading'>";
            for ($i = 8; $i < 12; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_insureamtzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'><strong>" . $zones[$i]->name . "</strong></td>";
                }
            }
            $message.="</tr>";

            $message.="<tr>";
            for ($i = 8; $i < 12; $i++) {
                if (isset($zones[$i])) {
                    $noofveh = $dm->getveh_insureamtzone($thiscustomerno, $zones[$i]->zoneid);
                    $message.="<td width='25%'>$noofveh</td>";
                }
            }
            $message.="</tr>";
            $message.="</table>";
            $message.="<br/>";
            /*
              $message.="Total Insurance Amount <br/>";
              $message.="<table><tr><td>Zone</td><td>No. of Vehicles</td></tr>";

              foreach($zones as $thiszone)
              {
              $vehinsamt = $dm->getveh_insureamtzone($thiscustomerno,$thiszone->zoneid);
              $message.="<tr><td>$thiszone->name</td><td>$vehinsamt</td></tr>";

              }
              $message.="</table>";
              $message.="<br/>";
             * 
             */
        }
    }
    $message .= "Regards,<br/>Infrastructure & Services Team. ";
    $message .= "</body></html>";
    echo $message;
    /*
      If message string is longer than 78 characters to the mail function
      it would show unwanted exclamation marks.
      To avoid this, we need to divide them into chunks
      and add the content-transfer-encoding in mail header.
     */
    $message = chunk_split(base64_encode($message));
    $emails = Array();
    //$emails[] = 'sanketsheth1@gmail.com';
    //$emails[] = 'mrudang.vora@elixiatech.com';
    $emails[] = 'anthony.malcom@mahindra.com';


    $subject = "Monthly Administration Report " . date('M Y', strtotime("first day of last month")) . " PAN India Mahindra Office Vehicle";

    foreach ($emails as $email) {
        sendMail($email, $subject, $message);
    }
}

function sendMail($to, $subject, $content) {
    $subject = $subject;
    // Create email headers
    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= "Content-Transfer-Encoding: base64\r\n\r\n";

    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;
}
