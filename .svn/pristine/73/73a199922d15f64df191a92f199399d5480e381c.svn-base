<?php
set_time_limit(0);
require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
include '../../lib/bo/simple_html_dom.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../modules/reports/reports_common_functions.php';


//$serverpath = $_SERVER['DOCUMENT_ROOT']."/speed";
$serverpath = "/var/www/html/speed";
$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {

//$thiscustomerno = '30';
        $um = new UserManager();
        $users = $um->getusersforcustomerforgensetcsv($thiscustomerno);
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
                            $html = getgensetreportcsv($user->customerno, $date, $date, $vehicle->deviceid, $cat);
                            $content = ob_get_clean();
                            $xls_filename = $cat."_".date("d-m-Y").".xls";
                            $html = str_get_html($content);
                            $actualpath = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/";
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
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
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
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                }
                                                else
                                                {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                }
                                            }
                                            else{
                                                $filename1 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno;
                                                if(!file_exists($filename1))
                                                {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno, 0777);
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                }
                                                else
                                                {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                }
                                            }
                                        }
                                }
                                else if(file_exists($actualpath)){
                            $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                            $fp = fopen($directory, "w");
                            fwrite($fp, $html);

                            fclose($fp);
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
                            $html = getgensetreportcsv($user->customerno, $date, $date, $vehicle->deviceid, $cat);
                            $content = ob_get_clean();
                            $xls_filename = $cat."_".date("d-m-Y").".xls";
                            $html = str_get_html($content);
                            $actualpath = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/";
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
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
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
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                }
                                                else
                                                {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                }
                                            }
                                            else{
                                                $filename1 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno;
                                                if(!file_exists($filename1))
                                                {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno, 0777);
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                }
                                                else
                                                {
                                                        $filename2 = '../../customer/'.$user->customerno.'/unitno/'.$vehicle->unitno.'/csv';
                                                        if(!file_exists($filename2))
                                                        {
                                                        mkdir("../../customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv", 0777);
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                        else{
                                                        $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                                                        $fp = fopen($directory, "w");
                                                        fwrite($fp, $html);

                                                        fclose($fp);
                                                        }
                                                }
                                            }
                                        }
                                }
                                else if(file_exists($actualpath)){
                            $directory = $serverpath."/customer/".$user->customerno."/unitno/".$vehicle->unitno."/csv/".$cat."_".$date."_genset.xls";
                            $fp = fopen($directory, "w");
                            fwrite($fp, $html);

                            fclose($fp);
                                }
                            
                            }
                        }
                    
                }
            }
        }
    }
}
?>