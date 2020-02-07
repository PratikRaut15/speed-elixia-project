<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
set_time_limit(0);
date_default_timezone_set("Asia/Calcutta");
include_once('../../lib/system/utilities.php');
include_once '../../lib/bo/CustomerManager.php';
$customerno = (int) $_REQUEST['customerno'];
$mail_type = isset($_REQUEST['mailType']) ? $_REQUEST['mailType'] : 'pdf';
$ext = ($mail_type=='pdf')?'.pdf':'.xls';
$reportType = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'PDF';
$report_name = isset($_REQUEST['report']) ? $_REQUEST['report'] : 'exit';
$filesuffix = $subject = '';
$mail_body = isset($_REQUEST['mail_content']) ? $_REQUEST['mail_content'] : 'Please find attached report.';
$serverpath = "../..";
$def_mail = 'sanketsheth@elixiatech.com';
$to = isset($_REQUEST['emailid']) ? $_REQUEST['emailid'] : $def_mail;
$arrToMailIds = explode(",", $to);
//Remove empty elements of an array
$arrToMailIds = array_filter($arrToMailIds);
$vehicleno = isset($_REQUEST['vehicleno']) ? str_replace(' ', '', $_REQUEST['vehicleno']) : '';

$subTitle = array();
$columns = array();

$savefile = 0;

/*Send Mail Report*/
$objEmail = new stdClass();
		$objEmail->arrToMailIds = $arrToMailIds;
		$objEmail->strBCCMailIds = '';
		$objEmail->strCCMailIds = '';
		
		$objEmail->mail_body = $mail_body;
		$objEmail->ext = $ext;

$cm = new CustomerManager($customerno);
$customer_details = $cm->getcustomerdetail_byid($customerno);

if ($_REQUEST['report'] == 'toggleswitchhistory') {
	ob_start();
	include_once 'reports_toggleswitch_functions.php';
	$filesuffix = "toggleswitchhistory";
	if($_REQUEST['vehicleno'] != ''){
		$subject = "Toggle Switch History for " . $_REQUEST['vehicleno'];
	}else{
		$subject = "Toggle Switch History";  
	}
	$title = getTitle("Trip Report");
	$subTitle = getSubtitle($_REQUEST);
	$columns = getColumns($reportType);

	$arrToggleSwitchDetails = isset($_SESSION['arrToggleSwitchDetails'])?$_SESSION['arrToggleSwitchDetails']:'';
	if($arrToggleSwitchDetails == ''){
		$arrToggleSwitchDetails = getToggleSwitchReport($_REQUEST['STdate'], $_REQUEST['EDdate'], $_REQUEST['STime'], $_REQUEST['ETime'], $_REQUEST['vehicleid'], $_REQUEST['groupid'], $customerno);
	}

	toggleswitchhistory_html($title,$subTitle,$columns,$customer_details,$arrToggleSwitchDetails,$reportType);   
	$content = ob_get_clean();

	if($reportType == 'EMAIL'){
		if ($filesuffix == '' && $subject == '') {
			echo "Please select Report";
			exit;
		}
		$file_name = ($vehicleno!='')?$vehicleno . "_" . date("d-m-Y") ."_". $filesuffix:date("d-m-Y") ."_". $filesuffix;
		$full_path = $serverpath . "/customer/" . $customerno . "/" . $file_name;
		$savefile = 1;
		renderReport($mail_type,$content,$full_path,$savefile); 
		$objEmail->subject = $subject;
		$objEmail->full_path = $full_path;
		$objEmail->file_name = $file_name;
		sendReport($objEmail);
	}else{
		$full_path = $filesuffix;
		renderReport($reportType,$content,$full_path,$savefile); 
	}
}

function sendReport($objEmail) {
	$isMailSent = sendMailUtil($objEmail->arrToMailIds, $objEmail->strCCMailIds,$objEmail->strBCCMailIds,$objEmail->subject, $objEmail->mail_body, $objEmail->full_path . $objEmail->ext, $objEmail->file_name . $objEmail->ext);
	if ($isMailSent) {
		echo "<span style='color:green;'>Mail sent</span>";
	}else{
		echo "<span style='color:red;'>One or more e-mail sending failed</span>";
	}	
}