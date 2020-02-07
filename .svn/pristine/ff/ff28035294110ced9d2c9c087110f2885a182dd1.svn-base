<?php

include 'tms_function.php';

$customerno = 116;
$userid = 485;
$datereminder = date("d-m-Y");
$userid = $objTms = new TMS($customerno, $userid);

$objIndent = new ProposedIndent();
$objIndent->customerno = $customerno;
$transporters = $objTms->get_distinct_transporters($objIndent);

$html = "<html>";
$head = "<head>
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

            span{
                font-weight:bold;
            }


        </style>
        </head>
        <body>";
$footer = "</body></html>";


$table_header = "<table border='1'>
    <tr>
        <th class='colHeading'>Proposed Indent ID</th>
        <th class='colHeading'>Factory</th>
        <th class='colHeading'>Depot</th>
        <th class='colHeading'>Transporter</th>
        <th class='colHeading'>Proposed Vehicle</th>
        <th class='colHeading'>Vehicle Requirement Date</th>
    </tr>";
$table_footer = "</table><br/>"
?>

<?php

//print_r($transporters);
foreach ($transporters as $transporter) {
  $proposed_Indents = array();
  $proposedIndents = array();
  $transporterEmailArr = array();
  $transporterPhoneArr = array();
  $email_Transporter = array();
  $objTransporter = new Transporter();
  $objTransporter->customerno = $customerno;
  $objTransporter->transporterid = $transporter['proposed_transporterid'];
  $email_Transporter = get_transporter_officials($objTransporter);
  if (isset($email_Transporter) && !empty($email_Transporter)) {
    foreach ($email_Transporter as $emailTransporter) {
      if (isset($emailTransporter['email']) && trim($emailTransporter['email']) != '') {
        $transporterEmailArr[] = trim($emailTransporter['email']);
      }
      if (isset($emailTransporter['phone']) && trim($emailTransporter['phone']) != '') {
        $transporterPhoneArr[] = $emailTransporter['phone'];
      }
    }
  }

  if (isset($transporterEmailArr) && !empty($transporterEmailArr)) {
    //echo $transporter['proposed_transporterid'];echo "----";
    //print_r($transporterEmailArr);

    $objTransporter = new Transporter();
    $objTransporter->customerno = $customerno;
    $objTransporter->transporterid = $transporter['proposed_transporterid'];
    $proposedIndents = $objTms->get_transporter_indents($objTransporter);

    $message = '';
    $openIndent = '';
    $rejectIndent = '';
    $openIndentRows = '';
    $rejectIndentRows = '';
    $AcceptedIndentRows = '';
    $factoryEmail = '';
    $factoryemails = array();
    $factoryOfficialArr = array();
    $factoryEmailArr = array();
    foreach ($proposedIndents as $indent) {
      $updated_date = date('d-m-Y', strtotime($indent['updated_on']));
      if ($indent['isAccepted'] == 0 && $indent['isAutoRejected'] == 0) {
        $openIndent = '<span>Indents Awaiting Response</span> <br/>';
        $openIndentRows .= "<tr>
                                  <td>" . $indent['proposedindentid'] . "</td>
                                  <td>" . $indent['factoryname'] . "</td>
                                  <td>" . $indent['depotname'] . "</td>
                                  <td>" . $indent['transportername'] . "</td>
                                  <td>" . $indent['proposedvehiclecode'] . " - " . $indent['proposedvehicledescription'] . "</td>
                                  <td>" . date('d-m-Y', strtotime($indent['date_required'])) . "</td>
                                  </tr>";
      } else if ($indent['isAccepted'] == -1 && (strtotime($datereminder) == strtotime($updated_date))) {

        $rejectIndent = '<span>Rejected Indents </span><br/>';
        $rejectIndentRows .= "<tr>
                                  <td>" . $indent['proposedindentid'] . "</td>
                                  <td>" . $indent['factoryname'] . "</td>
                                  <td>" . $indent['depotname'] . "</td>
                                  <td>" . $indent['transportername'] . "</td>
                                  <td>" . $indent['proposedvehiclecode'] . " - " . $indent['proposedvehicledescription'] . "</td>
                                  <td>" . date('d-m-Y', strtotime($indent['date_required'])) . "</td>
                                  </tr>";
      } else if ($indent['isAccepted'] == 1 && (strtotime($datereminder) == strtotime($updated_date))) {

        $AcceptedIndent = '<span>Transporter Approved Indents </span><br/>';
        $AcceptedIndentRows .= "<tr>
                                  <td>" . $indent['proposedindentid'] . "</td>
                                  <td>" . $indent['factoryname'] . "</td>
                                  <td>" . $indent['depotname'] . "</td>
                                  <td>" . $indent['transportername'] . "</td>
                                  <td>" . $indent['proposedvehiclecode'] . " - " . $indent['proposedvehicledescription'] . "</td>
                                  <td>" . date('d-m-Y', strtotime($indent['date_required'])) . "</td>
                                  </tr>";
      }
      if (!in_array($indent['factoryid'], $factoryOfficialArr)) {
        $factoryOfficialArr[] = $indent['factoryid'];
      }
    }

    $subject = "Indent Summary for Mondelez ";
    if ($openIndentRows != '' || $rejectIndentRows != '' || $AcceptedIndentRows != '') {
      $message .= '';
      $message .=$html;
      $message .=$head;
      $message .='Hi<br/>';
      $message .='Please follow Vehicle requirement schedule as below: <br/><br/>';
      if ($openIndentRows != '') {
        $message .= $openIndent;
        $message .= $table_header;
        $message .= $openIndentRows;
        $message .= $table_footer;
      }
      if ($rejectIndentRows != '') {
        $message .= $rejectIndent;
        $message .= $table_header;
        $message .= $rejectIndentRows;
        $message .= $table_footer;
      }
      if ($AcceptedIndentRows != '') {
        $message .= $AcceptedIndent;
        $message .= $table_header;
        $message .= $AcceptedIndentRows;
        $message .= $table_footer;
      }
      $message .= constants::Portallink;
      $message .= constants::Thanks;
      $message .= constants::CompanyName;
      $message .= constants::CompanyImage;
      $message .=$footer;
    }

    if (isset($factoryOfficialArr) && !empty($factoryOfficialArr)) {
      $factoryOfficialIds = implode(',', $factoryOfficialArr);
      $objFactory = new Factory();
      $objFactory->customerno = $customerno;
      $objFactory->factoryid = $factoryOfficialIds;
      $factoryemails = get_multiple_factory_officials($objFactory);
      if (isset($factoryemails) && !empty($factoryemails)) {
        foreach ($factoryemails as $emailTransporter) {
          if (isset($emailTransporter['email']) && trim($emailTransporter['email']) != '') {
            $factoryEmailArr[] = trim($emailTransporter['email']);
          }
        }
      }
    }
    $factoryEmail = implode(',', $factoryEmailArr);
    $AdminEmail = constants::adminemail;

    $CCEmail = $factoryEmail . "," . $AdminEmail;
    if ($message != '') {
      if (!empty($transporterEmailArr)) {
        $attachmentFilePath = '';
        $attachmentFileName = '';
        $BCCEmail = '';
        sendMailPHPMAILER($transporterEmailArr, $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName);
      }
    }


    //echo "</br>";
    //echo "</br>";
  }

  if (isset($transporterPhoneArr) && !empty($transporterPhoneArr)) {
    //echo $transporter['proposed_transporterid'];echo "----";
    //print_r($transporterPhoneArr);

    $msgcontent = '';
    $objTransporter = new Transporter();
    $objTransporter->customerno = $customerno;
    $objTransporter->transporterid = $transporter['proposed_transporterid'];
    $proposed_Indents = $objTms->get_transporter_indent_count($objTransporter);
    //$msgTransporter = "Hi," . "\r\n" . $indent['factoryname'] . " to " . $indent['depotname'] . " " . $indent['proposedvehiclecode'] . "-" . $indent['proposedvehicledescription'] . " on " . date('d-m-Y', strtotime($indent['date_required'])) . ".\r\n" . constants::CompanyName;
    $msg = '';

    foreach ($proposed_Indents as $pro_indent) {
      $msgcontent.=$pro_indent['factoryname'] . '-' . $pro_indent['indentcount'] . "\r\n";
    }
    if ($msgcontent != '') {
      $msg .="FactoryWise Indents :" . "\r\n";
      $msg .=$msgcontent . "\r\n";
      $msg .=constants::CompanyName;
    }
    //echo $msg;
    if ($msg != '') {
      $smscount = getSMSCount($customerno);
      if ($smscount > 0) {
        echo $isSMSSent = sendSMS($transporterPhoneArr, $msg, $response);
        if ($isSMSSent) {
          updateSMSCount($smscount, $msg, $customerno);
          $todaysdate = date("Y-m-d H:i:s");
          foreach ($transporterPhoneArr as $phone) {
            $smsLogId = insertSMSLog($phone, $msg, $response, $indent['proposedindentid'], $isSMSSent, $customerno, $todaysdate);
          }
        }
      }
    }

    //echo "</br>";
    //echo "</br>";
  }
}
?>