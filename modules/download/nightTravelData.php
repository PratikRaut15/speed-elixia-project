<?php
include_once '../../lib/bo/DailyReportManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/PointLocationManager.php';
include_once '../reports/reports_common_functions.php';
define("DATEFORMAT_DMY", "dmy");
$customer =$customer_id;
//$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$objReportUser              = new stdClass();
/*$objReportUser->reportId  = $reportId;
$objReportUser->reportTime  = $today->format('H');
$objUserManager             = new UserManager();
$users                      = $objUserManager->getUsersForReport($objReportUser);
$customerUserArray          = cronCustomerUsers($users);*/
$objDeviceManager           = new DeviceManager(0);
$objUserManager             = new UserManager();

$message  	= '';
$sDate 		= date('Y-m-d');
$eDate 		= date('Y-m-d');
$date = date('d-m-Y');

$nightDriveDetails   =  $objDeviceManager->getNightDriveDetailsForReport($customer);
// $timestamp           = strtotime($today->format(speedConstants::DEFAULT_DATE));

if($type == 'pdf'){
	    require_once('../reports/html2pdf.php');
     $cat = getNightTravelReportPDF($customer_id, $view_data['report_date'],$nightDriveDetails,'pdf');
    if($cat != ''){
    	echo $cat;
    }
    else{
    	echo "Data Not Available";
    }
    $content = ob_get_clean();
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($customer_id . "_" . date("d-m-Y") . "_NightTravelReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
else {
    require_once '../../lib/bo/simple_html_dom.php';
    //$cat = gettempreportxls($customer_id, $view_data['report_date'], $view_data['report_date'], $vehicle->deviceid, $vehicle->vehicleno, $interval, $start_time, $end_time);
     $cat = getNightTravelReportPDF($customer_id, $view_data['report_date'],$nightDriveDetails,'xls');
 if($cat != ''){
    	echo $cat;
    }
    else{
    	echo "Data Not Available";
    }
    $xls_filename 	= str_replace(' ', '', $customer_id . "_" . date("d-m-Y") . "_NightTravelReport.xls");
    $content 		= ob_get_clean();
    $html 			= str_get_html($content);

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;
}


/*function findTimeDifference($start_date , $end_date)
{
  $d1= new DateTime($start_date);
  $d2= new DateTime($end_date);
  $interval= $d1->diff($d2);
  return ($interval->days * 24) + $interval->h;

}*/
?>