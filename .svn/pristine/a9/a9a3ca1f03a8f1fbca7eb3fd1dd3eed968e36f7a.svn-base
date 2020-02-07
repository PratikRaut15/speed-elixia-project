<?php
include_once '../../lib/bo/BackupManager.php';

set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$backupManager = new BackupManager();

$default_sytem = 'linux';
$envDir = 'speed';
$path = '/var/www/html/'.$envDir.'/customer/';
$months = 3;
//echo getcwd();
$deletezip = isset($_GET['deletezip']) ? $_GET['deletezip'] : false;
$backupObj = new stdClass();

function setParams($customer_id, $unitno, $system, $path, $months) {
    global $backupObj;
    $backupObj->customer_id = $customer_id;
    $backupObj->unitno = $unitno;
    $backupObj->system = $system;
    $backupObj->server_path = $path;
    $backupObj->s_c = $backupObj->server_path . $backupObj->customer_id;
    $backupObj->unitPath = $backupObj->server_path . $backupObj->customer_id . "/unitno/". $backupObj->unitno;
    $check = validate();
    //echo "</br>Validate returned :".var_dump($check);
    if($check){
        $backupObj->months_limit = $months;
        $backupObj->date_ext = $backupObj->customer_id . "_" . $unitno . "_" . date('M_d_Y') . ".zip";
        return true;
    }else{
        return false;
    }
}

function validate() {
    global $backupObj;
    $backupObj->errorMsg = '';
    if (!$backupObj->customer_id) {
        $backupObj->errorMsg = "Customer id is invalid";
        return false;
    }
    if (!(is_dir($backupObj->s_c))) {
        //echo $backupObj->s_c;
        $backupObj->errorMsg = "Customer directory does not exist";
        return false;
    }
    if (!(is_dir($backupObj->unitPath))) {
        $backupObj->errorMsg = "Unit directory does not exist";
        return false;
    }
    return true;
}

function set_command(){
    global $backupObj;
    if ($backupObj->system == 'linux') {
        $backupObj->cmd_zip = "zip -r  {$backupObj->server_path}{$backupObj->date_ext} $backupObj->unitPath";
        $backupObj->cmd_move = "mv {$backupObj->server_path}{$backupObj->date_ext} {$backupObj->s_c}/{$backupObj->date_ext}";
        $backupObj->cmd_delete = "rm ";
        $backupObj->delete_zip_path = "$backupObj->s_c/" . $backupObj->date_ext;

        $backupObj->unitno_p = $backupObj->unitPath;
        $backupObj->unitno_p_sub_s = $backupObj->unitno_p . "/sqlite/";
        $backupObj->unitno_p_sub_pdf = $backupObj->unitno_p . "/pdf/";
        $backupObj->unitno_p_sub_csv = $backupObj->unitno_p . "/csv/";
        $backupObj->history = $backupObj->s_c . "/history/";
        $backupObj->reports_csv = $backupObj->s_c . "/reports/csv/";
        $backupObj->reports_pdf = $backupObj->s_c . "/reports/pdf/";
    } else {
        /*
        $backupObj->cmd_zip = "xcopy {$backupObj->s_c} {$backupObj->server_path}{$backupObj->date_ext}_zipped /s /i";
        $backupObj->cmd_move = "move {$backupObj->server_path}{$backupObj->date_ext}_zipped {$backupObj->s_c}\\{$backupObj->date_ext}_zipped";
        $backupObj->cmd_delete = "del ";
        $backupObj->delete_zip_path = "$backupObj->s_c\\" . $backupObj->date_ext . ".txt";

        $backupObj->unitno_p = $backupObj->s_c . "\\unitno\\";
        $backupObj->unitno_p_sub_s = $backupObj->unitno_p . "#\\sqlite\\";
        $backupObj->unitno_p_sub_pdf = $backupObj->unitno_p . "#\\pdf\\";
        $backupObj->unitno_p_sub_csv = $backupObj->unitno_p . "#\\csv\\";
        $backupObj->history = $backupObj->s_c . "\\history\\";
        $backupObj->reports_csv = $backupObj->s_c . "\\reports\csv\\";
        $backupObj->reports_pdf = $backupObj->s_c . "\\reports\pdf\\";
        */
    }
}

function execute_backup_command(){
    global $backupObj;
    $zipped = exec($backupObj->cmd_zip);
    $moved = exec($backupObj->cmd_move);
    return $zipped . "<br/>" . $moved . "<br/>";
}

function execute_remove_command(){
    global $backupObj;
    $removed = '';
    $date_limit = date('Y-m-d', strtotime("-$backupObj->months_limit months"));

    /* single unitno folder deletion starts*/
    $removed .= remove_old_files($backupObj->unitno_p_sub_s, 'sqlite', '.sqlite');
    $removed .= remove_old_files($backupObj->unitno_p_sub_pdf, 'pdf', '_', 'unitno/num/pdf');
    $removed .= remove_old_files($backupObj->unitno_p_sub_csv, 'xls', '_', 'unitno/num/csv');

    /*unitno ends*/

    /*history starts*/
    $removed .= remove_old_files($backupObj->history, 'sqlite', '.sqlite');
    /*history ends*/

    /*reports starts*/
    $removed .= remove_old_files($backupObj->reports_csv, 'xls', '_summaryreport.xls');
    $removed .= remove_old_files($backupObj->reports_pdf, 'pdf', '_summaryreport.pdf');
    /*reports ends*/

    return $removed . '<br/>';
}

function remove_old_files($full_path, $ext, $replace, $replace_decider = ''){
    global $backupObj;
    $date_limit = date('Y-m-d', strtotime("-$backupObj->months_limit months"));
    if (is_dir($full_path)) {
        $files_present = scandir($full_path);
        $c = 0;
        foreach ($files_present as $single_file) {
            if (preg_match("/.$ext$/", $single_file)) {
                if ($replace_decider == "unitno/num/pdf" || $replace_decider == "unitno/num/csv") {
                    $file_date = explode($replace, $single_file);
                    $file_date = $file_date[1];
                } else {
                    $file_date = str_replace($replace, '', $single_file);
                }

                $file_date = date('Y-m-d', strtotime($file_date));
                if ($file_date < $date_limit) {
                    if (preg_match('/\s*/', $single_file)) {
                        if ($backupObj->system == 'linux') {
                            $cmd = $backupObj->cmd_delete . ' "' . $full_path . $single_file . '"';
                        } else {
                            $single_file = str_replace(' ', '*', $single_file);
                            $cmd = $backupObj->cmd_delete . ' /F "' . $full_path . $single_file . '"';
                        }
                    } else {
                        $cmd = $backupObj->cmd_delete . " " . $full_path . $single_file;
                    }
                    exec($cmd);
                    $c++;
                    //echo $c.'==='.$full_path.$single_file.'<br/>';
                }
            }
        }
        $return = "Folder: " . $full_path . ", $ext Files deleted: $c<br/>";
        return $return;
    }
}

function remove_zip($deletezip){
    global $backupObj;
    if ($deletezip == "true" && $backupObj->customer_id) {
        if (file_exists($backupObj->delete_zip_path)) {
            $cmd = "$backupObj->cmd_delete $backupObj->delete_zip_path";
            $return = exec($cmd);
            return "Zip File Deleted<br/>";
        } else {
            return "Zip File does not exist<br/>";
        }
    }
}
$limit = $_GET['limit'];
$customerNo = isset($_GET['customerNo'])?$_GET['customerNo']:0;
$units = $backupManager->fetchUnits($limit,$customerNo);
foreach($units as $unit){
    
    $executionStartTime =  microtime(true);
    $startTime =  date('Y-m-d H:i:s');
    $valid = setParams($unit['customerNo'], $unit['unitNo'], $default_sytem, $path, $months);
    //echo "</br>set params returned :".var_dump($valid);
    if($valid){
        set_command();
        echo "<br/> Command Set: <br/>";
        if ($deletezip != 'true') {
            echo execute_backup_command();
            echo execute_remove_command();
        }
        remove_zip($deletezip);    
    }
    $executionEndTime = microtime(true);
    $time = $executionEndTime - $executionStartTime;

    $executionStartTime = number_format($executionStartTime,6);
    $executionEndTime = number_format($executionEndTime,6);
    $time = number_format($time,6);
    $endTime = date('Y-m-d H:i:s');
    echo "time taken : ". $time." For unit : ".$backupObj->unitno."</br>";
    $backupManager->processBackup($unit['ubId'],$startTime,$endTime,$time,$backupObj->errorMsg);       
}
