<?php

/**
 * Date: 29th oct 2014
 * Ak added, for: Links for Daily Email PDF / Email (Merge)
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

//date_default_timezone_set("Asia/Calcutta");

require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
require '../../lib/bo/VehicleManager.php';
include_once("class.phpmailer.php");

/* mail header details */
$cc = '';
$subject = 'Reports';
$from = 'noreply@elixiatech.com';
$headers = "From: " . $from . "\r\n";
$headers .= "CC:" . $cc . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
/**/

$serverpath = "http://www.speed.elixiatech.com";
//$serverpath = "http://localhost/elixiaspeed";
$download = $serverpath . "/modules/download/report.php?q=";

$cm = new CustomerManager();
$um = new UserManager();

//$customernos = $cm->getcustomernos();
$customernos = array(177);

$cur_date = date("d-m-Y");
$date = date('d-m-Y', strtotime("-1 day" . $cur_date));
$message = '';

if (isset($customernos)) {
 $total_mail = 0;
 $not_sent_mail = 0;

 foreach ($customernos as $thiscustomerno) {
  $timezone = $cm->timezone_name_cron('Asia/Kolkata', $thiscustomerno);
  date_default_timezone_set('' . $timezone . '');
  $customer_details = $cm->getcustomerdetail_byid($thiscustomerno);
  $vehiclemanager = new VehicleManager($thiscustomerno);
  $users = $um->getadminforcustomer($thiscustomerno);
  $custom = $um->get_custom($thiscustomerno);
  $customname = 'AC Sensor';
  if ($custom != null) {
   if ($custom->usecustom == 1 && $custom->custom_id == 1 && $custom->customname != '') {
    $customname = $custom->customname;
   }
  }

  if (isset($users)) {
   foreach ($users as $user) {

    $str_date = strtotime($cur_date);
    $summary_pdf_url = $download . "summary-pdf-$thiscustomerno-$user->userkey-$str_date";
    $summary_xls_url = $download . "summary-xls-$thiscustomerno-$user->userkey-$str_date";
    $genset_pdf_url = $download . "genset-pdf-$thiscustomerno-$user->userkey-$str_date&v=";
    $genset_xls_url = $download . "genset-xls-$thiscustomerno-$user->userkey-$str_date&v=";
    $travel_pdf_url = $download . "travel-pdf-$thiscustomerno-$user->userkey-$str_date&v=";
    $travel_xls_url = $download . "travel-xls-$thiscustomerno-$user->userkey-$str_date&v=";
    /* temperature pdf-csv url */
    $temp_pdf_url = $download . "temperature-pdf-$thiscustomerno-$user->userkey-$str_date&v=";
    $temp_xls_url = $download . "temperature-xls-$thiscustomerno-$user->userkey-$str_date&v=";
    /**/
    /* Warehouse  temperature pdf-csv url */
    $wh_temp_pdf_url = $download . "wh_temperature-pdf-$thiscustomerno-$user->userkey-$str_date&v=";
    $wh_temp_xls_url = $download . "wh_temperature-xls-$thiscustomerno-$user->userkey-$str_date&v=";
    /* Overspeed pdf-csv url */
    $overspeed_pdf_url = $download . "overspeed-pdf-$thiscustomerno-$user->userkey-$str_date&v=";
    $overspeed_xls_url = $download . "overspeed-xls-$thiscustomerno-$user->userkey-$str_date&v=";
    /**/


    if ($user->email != '' && ($user->dailyemail == '1' || $user->dailyemail_csv == '1')) {
     $encodekey = sha1($user->userkey);
     $to = $user->email;
//                    $to = "sanketsheth1@gmail.com";
     $pdf_header = $csv_header = $summary_pdf = $summary_csv = '';
     $os_pdf = $os_excel = '';
     $header_span = 1;
     $groupspan = 0;
     $groupclmn = "";
     if ($user->dailyemail == '1') {
      $pdf_header = '<td><b>PDF</b></td>';
      $header_span += 1;
      /* summary report pdf */
      $summary_path = $summary_pdf_url;
      $summary_pdf = '<td><a href="' . $summary_path . '" target="_blank">Download</a></td>';
      /**/
      /* overspeed report pdf */
      $os_path = $overspeed_pdf_url;
      $os_pdf = '<td><a href="' . $os_path . '" target="_blank">Download</a></td>';
      /**/
     }
     if ($user->dailyemail_csv == '1') {
      $csv_header = '<td><b>CSV</b></td>';
      $header_span += 1;
      /* summary report csv */
      $summary_path = $summary_xls_url;
      $summary_csv = '<td><a href="' . $summary_path . '" target="_blank">Download</a></td>';
      /**/
      /* overspeed report pdf */
      $os_path = $overspeed_xls_url;
      $os_excel = '<td><a href="' . $os_path . '" target="_blank">Download</a></td>';
      /**/
     }
     /* starts, link setup based on reports */


     /* starts, travel-history/genset-sensor report */
     $groups = $um->get_groups_fromuser($thiscustomerno, $user->userid);
     $travel_history_report = $sensor_report = '';
     $temp_report = '';
     $wh_temp_report = '';
     if (isset($groups)) {
      $groupspan++;
      $groupclmn = "<td><b>Group Name</b></td>";
      foreach ($groups as $thisgroup) {

       //$vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($thisgroup->groupid);
       if ($customer_details->use_tracking == 1) {
        $vehicles = $vehiclemanager->get_all_vehicles_by_group(array($thisgroup->groupid));
        if (isset($vehicles)) {
         foreach ($vehicles as $vehicle) {

          $pdf_h = $csv_h = $pdf_s = $csv_s = $pdf_t = $csv_t = '';

          if ($user->dailyemail == '1') {
           $t_h_location_pdf = $travel_pdf_url . $vehicle->vehicleid;
           $pdf_h = '<td><a href="' . $t_h_location_pdf . '" target="_blank">Download</a></td>';
           $gn_s_location_pdf = $genset_pdf_url . $vehicle->vehicleid;
           $pdf_s = '<td><a href="' . $gn_s_location_pdf . '" target="_blank">Download</a></td>';
           $temp_s_location_pdf = $temp_pdf_url . $vehicle->vehicleid;
           $pdf_t = '<td><a href="' . $temp_s_location_pdf . '" target="_blank">Download</a></td>';
          }
          if ($user->dailyemail_csv == '1') {
           $t_h_location_xls = $travel_xls_url . $vehicle->vehicleid;
           $csv_h = '<td><a href="' . $t_h_location_xls . '" target="_blank">Download</a></td>';
           $gn_s_location_xls = $genset_xls_url . $vehicle->vehicleid;
           $csv_s = '<td><a href="' . $gn_s_location_xls . '" target="_blank">Download</a></td>';
           $temp_s_location_xls = $temp_xls_url . $vehicle->vehicleid;
           $csv_t = '<td><a href="' . $temp_s_location_xls . '" target="_blank">Download</a></td>';
          }
          $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
          $travel_history_report .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $pdf_h . $csv_h . '</tr>';
          if ($customer_details->temp_sensors >= 1) {
           $temp_report .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $pdf_t . $csv_t . '</tr>';
          }
          if ($vehicle->acsensor == '1') {
           $sensor_report .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $pdf_s . $csv_s . '</tr>';
          }
         }
        }
       }

       if ($customer_details->use_warehouse) {
        $vehicles = $vehiclemanager->get_all_vehicles_by_group(array($thisgroup->groupid), $customer_details->use_warehouse);
        if (isset($vehicles)) {
         foreach ($vehicles as $vehicle) {

          $wh_pdf_t = $wh_csv_t = '';

          if ($user->dailyemail == '1') {
           $wh_temp_s_location_pdf = $wh_temp_pdf_url . $vehicle->vehicleid;
           $wh_pdf_t = '<td><a href="' . $wh_temp_s_location_pdf . '" target="_blank">Download</a></td>';
          }
          if ($user->dailyemail_csv == '1') {
           $wh_temp_s_location_xls = $wh_temp_xls_url . $vehicle->vehicleid;
           $wh_csv_t = '<td><a href="' . $wh_temp_s_location_xls . '" target="_blank">Download</a></td>';
          }
          $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';

          if ($customer_details->temp_sensors >= 1) {
           $wh_temp_report .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $wh_pdf_t . $wh_csv_t . '</tr>';
          }
         }
        }
       }
      }
     } else if ($groups == null) {
      $groupid = 0;
      //$vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($groupid);
      if ($customer_details->use_tracking == 1) {
       $vehicles = $vehiclemanager->get_all_vehicles_by_group(array($groupid));
       if (isset($vehicles)) {
        foreach ($vehicles as $vehicle) {

         $pdf_h = $csv_h = $pdf_s = $csv_s = $pdf_t = $csv_t = '';

         if ($user->dailyemail == '1') {
          $t_h_location_pdf = $travel_pdf_url . $vehicle->vehicleid;
          $pdf_h = '<td><a href="' . $t_h_location_pdf . '" target="_blank">Download</a></td>';
          $gn_s_location_pdf = $genset_pdf_url . $vehicle->vehicleid;
          $pdf_s = '<td><a href="' . $gn_s_location_pdf . '" target="_blank">Download</a></td>';
          $temp_s_location_pdf = $temp_pdf_url . $vehicle->vehicleid;
          $pdf_t = '<td><a href="' . $temp_s_location_pdf . '" target="_blank">Download</a></td>';
         }
         if ($user->dailyemail_csv == '1') {
          $t_h_location_xls = $travel_xls_url . $vehicle->vehicleid;
          $csv_h = '<td><a href="' . $t_h_location_xls . '" target="_blank">Download</a></td>';
          $gn_s_location_xls = $genset_xls_url . $vehicle->vehicleid;
          $csv_s = '<td><a href="' . $gn_s_location_xls . '" target="_blank">Download</a></td>';
          $temp_s_location_xls = $temp_xls_url . $vehicle->vehicleid;
          $csv_t = '<td><a href="' . $temp_s_location_xls . '" target="_blank">Download</a></td>';
         }
         $travel_history_report .= '<tr><td>' . $vehicle->vehicleno . '</td>' . $pdf_h . $csv_h . '</tr>';
         if ($customer_details->temp_sensors >= 1) {
          $temp_report .= '<tr><td>' . $vehicle->vehicleno . '</td>' . $pdf_t . $csv_t . '</tr>';
         }
         if ($vehicle->acsensor == '1') {
          $sensor_report .= '<tr><td>' . $vehicle->vehicleno . '</td>' . $pdf_s . $csv_s . '</tr>';
         }
        }
       }
      }
      if ($customer_details->use_warehouse == 1) {
       $vehicles = $vehiclemanager->get_all_vehicles_by_group(array($groupid), $customer_details->use_warehouse);
       if (isset($vehicles)) {
        foreach ($vehicles as $vehicle) {

         $wh_pdf_t = $wh_csv_t = '';

         if ($user->dailyemail == '1') {
          $wh_temp_s_location_pdf = $wh_temp_pdf_url . $vehicle->vehicleid;
          $wh_pdf_t = '<td><a href="' . $wh_temp_s_location_pdf . '" target="_blank">Download</a></td>';
         }
         if ($user->dailyemail_csv == '1') {
          $wh_temp_s_location_xls = $wh_temp_xls_url . $vehicle->vehicleid;
          $wh_csv_t = '<td><a href="' . $wh_temp_s_location_xls . '" target="_blank">Download</a></td>';
         }

         if ($customer_details->temp_sensors >= 1) {
          $wh_temp_report .= '<tr><td>' . $vehicle->vehicleno . '</td>' . $wh_pdf_t . $wh_csv_t . '</tr>';
         }
        }
       }
      }
     }
     /* ends, travel-history/genset-sensor report */

     /* ends, link set based on reports */

     /* starts, email body */
     $message = '
<html>
    <body>
        Dear ' . $user->realname . ' ,<br><p></p></br>
        Greetings from Elixia Tech!<br/><br/>
        Customer No: <b>' . $user->customerno . '</b><br/><br/>
        Please find the auto-generated Summary Report for all your vehicles. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';

     if ($user->dailyemail == '1') {
//                $message .=" For your security, the reports now are in a password-protected format. You can view it, save it for future reference or even print copies if needed.<br/>
//                To open the file, you will need to enter a password, which is a combination of first 3 characters of your registered company name along with the provided customer number.<br/><br/>
//                Examples are in the table below:<br/>
//                <table border=1>
//                    <tr><td>Company name</td><td>Customer no</td><td>Password(Lowercase only)</td></tr>
//                    <tr><td>Elixiatech</td><td>107</td><td>eli107</td></tr>
//                    <tr><td>Mr. Elix</td><td>107</td><td>mre107</td></tr>
//                    <tr><td>E X T</td><td>107</td><td>ext107</td></tr>
//                </table><br/><hr/>";
     }
     $message.="<center>Reports</center><hr/><br/>";
     if ($customer_details->use_tracking == 1) {
      $message .= '
        <table border=1>
            <tr align=center><td colspan="' . $header_span . '"><b>Summary Report</b></td></tr>
            <tr>' . $pdf_header . $csv_header . '</tr>
            <tr>' . $summary_pdf . $summary_csv . '</tr>
        </table><br/>';

      if ($os_pdf != '' || $os_excel != '') {
       $message .= '
        <table border=1>
            <tr align=center><td colspan="' . $header_span . '"><b>Overspeed Report</b></td></tr>
            <tr>' . $pdf_header . $csv_header . '</tr>
            <tr>' . $os_pdf . $os_excel . '</tr>
        </table><br/>';
      }
     }
     $fspan = $header_span + $groupspan;
     if ($temp_report != '') {
      $message .= '
        <table border=1>
            <tr align=center><td colspan="' . $fspan . '"><b>Vehicle Temperature Report</b></td></tr>
            <tr><td><b>Vehicle No</b></td>' . $groupclmn . $pdf_header . $csv_header . '</tr>
            ' . $temp_report . '
        </table><br/>';
     }
     if ($wh_temp_report != '') {
      $message .= '
        <table border=1>
            <tr align=center><td colspan="' . $fspan . '"><b>Warehouse Temperature Report</b></td></tr>
            <tr><td><b>Warehouse</b></td>' . $groupclmn . $pdf_header . $csv_header . '</tr>
            ' . $wh_temp_report . '
        </table><br/>';
     }

     if ($travel_history_report != '') {
      $message .= '
        <table border=1>
            <tr align=center><td colspan="' . $fspan . '"><b>Travel History Report</b></td></tr>
            <tr><td><b>Vehicle No</b></td>' . $groupclmn . $pdf_header . $csv_header . '</tr>
            ' . $travel_history_report . '
        </table><br/>';
     }
     if ($sensor_report != '') {
      $message .= '
        <table border=1>
            <tr align=center><td colspan="' . $fspan . '"><b>' . $customname . ' Report</b></td></tr>
            <tr><td><b>Vehicle No</b></td>' . $groupclmn . $pdf_header . $csv_header . '</tr>
            ' . $sensor_report . '
        </table><br/>';
     }


     $message .= '
        <br/><font size="smaller">To unsubscribe, please uncheck your alerts <a href="http://www.speed.elixiatech.com/modules/api/?action=login_userkey_unsub&key=' . $encodekey . '">here</a></font>
    </body>
</html>';
     echo "<br/><br/>" . $message; //die();
     /* email body ends here */

     /* email send process starts */
     /*
       $mail = new PHPMailer();
       $mail->IsMail();
       $mail->AddAddress($to);
       $mail->From = $from;
       $mail->FromName = "Elixia Speed";
       $mail->Sender = $from;
       $mail->Subject = $subject;
       $mail->Body = $message;
       $mail->IsHTML(true);
       $mail->AddBCC($cc);
       $mail->AddReplyTo($from, "Elixia Speed");
      *
      */

     /* email send process ends */

     /*
       if (!$mail->Send()) {
       $not_sent_mail++; //echo "Error sending: " . $mail->ErrorInfo;
       }
       $total_mail++;
      *
      */
    }
   }
  }
 }
}
echo "Total emails: $total_mail<br/>Total email sent: " . ($total_mail - $not_sent_mail) . "<br/>Total email not sent: $not_sent_mail<hr>";
// echo $message;
?>
