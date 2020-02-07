<?php
/**
 * Functions of mobility-module
 */

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

$Mpath = '';
if(defined('Mpath')){
    $Mpath = Mpath;
}

require_once $Mpath.'../../config.inc.php';
require_once $Mpath.'../../lib/system/Log.php';
require_once $Mpath.'../../lib/system/Sanitise.php';
require_once $Mpath.'../../lib/system/DatabaseMobilityManager.php';
require_once $Mpath.'class/MobilityManager.php';
require_once $Mpath.'../../lib/comman_function/reports_func.php';

$rename = 'stylist';
$weeklyoff_arr = array(
    1=>'Sunday',
    2=>'Monday',
    3=>'Tuesday',
    4=>'Wednesday',
    5=>'Thursday',
    6=>'Friday',
    7=>'Saturday'
);
$status_colors = array(
    0 => array('Requested', '#DDC6FF'),
    1 => array('Created', '#B6E7FF'),
    2 => array('Accepted', '#8F81A5', '#fff'),
    3 => array('Allotted', '#53D500'),
    4 => array('Departed', '#FFD090'),
    5 => array('Arrived', '#FFAA81'), //#FFDDC6
    6 => array('Busy', '#FF4D6C'),
    7 => array('Ended', '#FF7EB6'),
    8 => array('Closed', '#FFAFAC'),
);
$start_hour = 8;
$max_hour = 23;

if(!isset($_SESSION)){
    session_start();
}


function auto_allocate_trackie($obj,$sdate){
    $data = $obj->trackies_service_details($sdate);
    
    $data_op = array();
    foreach($data['order'] as $key=>$val){
        $data_op[$key] = $data[0][$key];
    }
    
    foreach($data_op as $tid=>$dat_arr){
        if(is_null($dat_arr[0]['clientid'])){
            return $tid;
        }
        else{
            $count = count($dat_arr);
            foreach($dat_arr as $indi_data){
                
                //echo date('Y-m-d H:i', $strtotime);
                $strtotime = strtotime($indi_data['sdate']) + ($indi_data['totaltime'])*60 + (31*60); //sevicetime+totaltime+30 mins
                
                if($count==1){
                    if(strtotime($sdate) >= $strtotime){
                        return $tid;
                    }
                }
            }
        }
        
    }
    
    return false;
    
}
?>
