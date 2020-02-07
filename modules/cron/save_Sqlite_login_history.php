<?php
require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/CustomerManager.php';
require_once '../../lib/bo/UserManager.php';
require_once '../../lib/bo/ComQueueManager.php';
require_once 'files/push_sqlite.php';

set_time_limit(0);
ini_set('memory_limit', '256M');
ini_set('display_errors', 'On');
error_reporting(E_ALL);
//date_default_timezone_set("Asia/Calcutta");  
$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
if(isset($customernos))
{
    foreach($customernos as $thiscustomerno)
    {
//$thiscustomerno = '10';
        $timezone = $cm->timezone_name_cron('Asia/Kolkata', $thiscustomerno);
        date_default_timezone_set(''.$timezone.'');
        $cqm = new ComQueueManager($thiscustomerno);
        
        $loginhistory = $cqm->GetLoginHistory($thiscustomerno);
        if(isset($loginhistory))
        {
            foreach($loginhistory as $loginqueue)
            {
                $returns = '';
                $exec = Loginsqlite($loginqueue->logHistoryId,$loginqueue->page_master_id,$loginqueue->type, $loginqueue->customerno,$loginqueue->created_on,$loginqueue->created_by);
                //print_r($exec[1]);
                if($exec[1] != '' || $exec[2] != ''){
                    $returns = 'error';
                    break; 
                }
                $returns = 'delete';
            }
            if($returns == 'delete'){
            $cqm->DeleteLoginHistory($thiscustomerno);
            //echo 'Success';
            }
            else if($returns == 'error'){
                echo print_r($exec);
            }
        }
    }
}
