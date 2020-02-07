<?php

/**
 * Functions of Sales engage-module
 */
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

$Mpath = '';
if (defined('Mpath')) {
    $Mpath = Mpath;
}

require_once $Mpath . '../../config.inc.php';
require_once $Mpath . '../../lib/system/Log.php';
require_once $Mpath . '../../lib/system/Sanitise.php';
require_once $Mpath . '../../lib/system/DatabaseSalesEngagementManager.php';
require_once $Mpath . 'class/SalesEngagementManager.php';
require_once $Mpath . '../../lib/comman_function/reports_func.php';
//include_once '../../images/196/vardamailfooter.jpeg';

if (!isset($_SESSION)) {
    session_start();
}

function sendMailWebSales_old($to, $subject, $content, $cc = NULL) {
    $subject = $subject;
    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;
}

function sendMailWebSales($to, $subject, $content, $cc = NULL) {
    include_once("../cron/class.phpmailer.php");
    $content .= '<br><img src="http://speed.elixiatech.com/images/196/vardamailfooter.jpeg" alt="Varda Logo" />';
    //$file = '../../images/196/vardamailfooter.jpeg';
    //$file = $path . "/" . $filename;
    $mail = new PHPMailer();
    $mail->IsMail();
    $mail->AddAddress($to);
    $mail->From = "nonreply@varda.in";
    $mail->FromName = "Varda";
    $mail->Sender = "nonreply@varda.in";
    $mail->Subject = $subject;
    $mail->Body = $content;
    $mail->IsHtml(true);
    //$mail->AddAttachment($file, 'Signature');
    /* email send process ends */
    $mail->IsHTML(true);
    //$mail->AddBCC($cc);
    $mail->AddReplyTo($from,"Varda");
//SEND Mail
    if ($mail->Send()) {
        return TRUE; // or use booleans here
    } else {
        return false;
    }
}

function sendSMSWebSales($phoneArray, $message, &$response) {
    $isSMSSent = false;
    $countryCode = "91";
    $arrPhone = array();
    if (is_array($phoneArray)) {
        foreach ($phoneArray as $phone) {
            $arrPhone[] = $countryCode . $phone;
        }
    } else {
        $arrPhone[] = $countryCode . $phoneArray;
    }
    $phone = implode(",", $arrPhone);
    $url = str_replace("{{PHONENO}}", urlencode($phone), SMS_URL_VARDA);
    $url = str_replace("{{MESSAGETEXT}}", urlencode($message), $url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    if ($response === false) {
        //echo 'Curl error: ' . curl_error($ch);
        $isSMSSent = false;
    } else {
        $isSMSSent = true;
    }
    curl_close($ch);
    return $isSMSSent;
}

function file_upload_validation_salesengage($valid_file, $valid_size) {
    if (!isset($_FILES['clientfile'])) {
        return "Please upload file.";
    }

    $file_ext = end((explode(".", $_FILES['clientfile']['name'])));
    if (!in_array($file_ext, $valid_file)) {
        return "Invalid file type.";
    }

    if ($_FILES['clientfile']['size'] > $valid_size) {
        return "File Size cannot cannot exceed 2 MB";
    }

    return null;
}

function get_excel_data_salesengage($file_path, $max_column, $max_row, $column_names) {

    $excel = PHPExcel_IOFactory::load($file_path);
    $worksheet = $excel->getActiveSheet();
    $data = array();

    foreach ($worksheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $column_count = 1;
        foreach ($cellIterator as $cell) {
            $row = $cell->getRow();
            $column = $cell->getColumn();

            if ($column_count > $max_column || $row > $max_row) {
                break;
            }
            $c_name = $column_names[$column];
            $value = $cell->getValue();
            $data[$row][$c_name] = $value;
            $column_count++;
        }
        if (!array_filter($data[$row])) {
            unset($data[$row]);
            continue;
        }
    }
    array_shift($data);
    return $data;
}

function upload_checkpoint_salesengage($all_form) {

    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $today = date("Y-m-d H:i:s");
    $sales = new Saleseng($customerno, $userid);
    $skipped = 0;
    $added = 0;
    $data = array();
    foreach ($all_form as $form) {
        $dob1 = date("Y-m-d", strtotime($form["dob"]));
        $salesdata = new stdClass();
        $salesdata->name = $form['name'];
        $salesdata->address = $form['address'];
        $salesdata->email = $form['email'];
        $salesdata->mobileno = $form['mobileno'];
        $salesdata->dob = $dob1;

        $sales->SaveClients($salesdata, $userid);

        $added++;
    }

    return array(
        'added' => $added,
        'skipped' => $skipped
    );
}

?>
