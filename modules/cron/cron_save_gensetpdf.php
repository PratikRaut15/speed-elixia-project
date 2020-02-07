<?php
set_time_limit(0);
require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../modules/reports/reports_common_functions.php';
require_once('../../modules/reports/html2pdf.php');


//$serverpath = $_SERVER['DOCUMENT_ROOT']."/speed";
$serverpath = "/var/www/html/speed";
$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {
        $um = new UserManager();
        $users = $um->getusersforcustomerforgenset($thiscustomerno);
        $cust_name = $cm->get_customer_company($thiscustomerno);
        $cust_pass = strtolower(substr(preg_replace('/[\s\.]/', '', $cust_name), 0,3)).$thiscustomerno;
        
        if(isset($users))
        {
            foreach($users as $user)
            {
        $ums = new UserManager($user->customerno);
        $groups = $ums->get_groups_fromuser($user->customerno, $user->userid);
        $vehiclemanager = new VehicleManager($user->customerno);
        $vehicles = Array();
        if(isset($groups))
        {
            foreach($groups as $group)
            {
                $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($group->groupid);            
                if(isset($vehicles))
                    {
                        foreach($vehicles as $vehicle )
                        {
                            $cat = substr($vehicle->vehicleno,-4);
                            //date_default_timezone_set("Asia/Calcutta"); 
                            $timezone = $cm->timezone_name_cron('Asia/Kolkata', $thiscustomerno);
                            date_default_timezone_set(''.$timezone.'');
                            $date1 = date("d-m-Y");
                            $date = date('d-m-Y',strtotime("-1 day".$date1));
                            ob_start();
                            $html = getgensetreportpdf($user->customerno, $date, $date, $vehicle->deviceid, $cat);
                            $content = ob_get_clean();
                                try
                                {
                                    $html2pdf = new HTML2PDF('L', 'A4', 'en');
                                     $html2pdf->pdf->SetProtection(array('print'), $cust_pass);
                                    $html2pdf->pdf->SetDisplayMode('fullpage');

                                   $html2pdf->writeHTML($content);
                                    //$html2pdf->Output('travelhistory_'.$cat.'.pdf');
                                    $actualpath = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/";
                                    if(!file_exists($actualpath)){
                                    $filename = '../../customer/'.$user->customerno.'/';
                                    if(!file_exists($filename)){
                                            mkdir("../../customer/" . $user->customerno, 0777);
                                            $unitnofolder = '../../customer/'.$user->customerno.'/unitno';
                                                if(!file_exists($unitnofolder))
                                                {
                                                mkdir("../../customer/".$user->customerno."/unitno", 0777);
                                                $filename1 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno;
                                                if(!file_exists($filename1))
                                                    {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno, 0777);
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                    }
                                                }
                                            }
                                    else {
                                            $unitnofolder = '../../customer/'.$user->customerno.'/unitno';
                                            if(!file_exists($unitnofolder))
                                            {
                                                mkdir("../../customer/".$user->customerno."/unitno", 0777);
                                                $filename1 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno;
                                                if(!file_exists($filename1))
                                                {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno, 0777);
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                }
                                                else
                                                {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                }
                                            }
                                            else{
                                                $filename1 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno;
                                                if(!file_exists($filename1))
                                                {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno, 0777);
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                }
                                                else
                                                {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                }
                                            }
                                        }
                                }
                                else if(file_exists($actualpath)){
                                            $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                }

                                }
                                catch(HTML2PDF_exception $e) {
                                    echo $e;
                                    exit;
                                }  
                            }
                        }
                    }
                }
                else if($groups == null){
                $groupid = 0;
                $vehicles = $vehiclemanager->get_all_groupedvehicles_with_drivers_for_pdf($groupid);            
                if(isset($vehicles))
                    {
                        foreach($vehicles as $vehicle )
                        {
                            $cat = substr($vehicle->vehicleno,-4);
                            //date_default_timezone_set("Asia/Calcutta");  
                            $timezone = $cm->timezone_name_cron('Asia/Kolkata', $thiscustomerno);
                            date_default_timezone_set(''.$timezone.'');
                            $date1 = date("d-m-Y");
                            $date = date('d-m-Y',strtotime("-1 day".$date1));
                            ob_start();
                            $html = getgensetreportpdf($user->customerno, $date, $date, $vehicle->deviceid, $cat);
                            $content = ob_get_clean();
                                try
                                {
                                    $html2pdf = new HTML2PDF('L', 'A4', 'en');
                                     $html2pdf->pdf->SetProtection(array('print'), $cust_pass);
                                    $html2pdf->pdf->SetDisplayMode('fullpage');

                                   $html2pdf->writeHTML($content);
                                    //$html2pdf->Output('travelhistory_'.$cat.'.pdf');
                                    $actualpath = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/";
                                    if(!file_exists($actualpath)){
                                    $filename = '../../customer/'.$user->customerno.'/';
                                    if(!file_exists($filename)){
                                            mkdir("../../customer/" . $user->customerno, 0777);
                                            $unitnofolder = '../../customer/'.$user->customerno.'/unitno';
                                                if(!file_exists($unitnofolder))
                                                {
                                                mkdir("../../customer/".$user->customerno."/unitno", 0777);
                                                $filename1 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno;
                                                if(!file_exists($filename1))
                                                    {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno, 0777);
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                    }
                                                }
                                            }
                                    else {
                                            $unitnofolder = '../../customer/'.$user->customerno.'/unitno';
                                            if(!file_exists($unitnofolder))
                                            {
                                                mkdir("../../customer/".$user->customerno."/unitno", 0777);
                                                $filename1 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno;
                                                if(!file_exists($filename1))
                                                {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno, 0777);
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                }
                                                else
                                                {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                }
                                            }
                                            else{
                                                $filename1 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno;
                                                if(!file_exists($filename1))
                                                {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno, 0777);
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                }
                                                else
                                                {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/pdf';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf", 0777);
                                                        $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                        }
                                                        else{
                                                            $pdffile = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/gensetreport_".$cat."_".$date.".pdf";
                                                            if(!file_exists($pdffile))
                                                            {
                                                                $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                                            }
                                                        }
                                                }
                                            }
                                        }
                                }
                                else if(file_exists($actualpath)){
                                            $html2pdf->Output($serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/pdf/".$cat."_".$date."_gensetreport.pdf", 'F');
                                }

                                }
                                catch(HTML2PDF_exception $e) {
                                    echo $e;
                                    exit;
                                }  
                            }
                        }
                    
                }
            }
        }
    }
}

?>