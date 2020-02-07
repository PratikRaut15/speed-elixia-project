<?php
if(!isset($_SESSION))
    session_start();
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../";
}


include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
//include_once $RELATIVE_PATH_DOTS . "lib/comman_function/reports_func.php";
include_once $RELATIVE_PATH_DOTS . "lib/bo/GroupManager.php";


if (!isset($_SESSION['customerno'])) {
    echo('Session expired. Please login');
    exit;
}
$action = $_REQUEST['action'];
if ($action == 'sequenceupdate') {
    $seq_array='';
    $defaultseqarray = '';
    $seq = $_REQUEST['seq'];
    $defaultseq = $_REQUEST['defaultseq'];
    $seq_array = explode(',', $seq);
    $defaultseqarray = explode(',', $defaultseq);
    
    $gm = new GroupManager($_SESSION['customerno']);
    
    $res = $gm->updateGroupSequenceNo($seq_array, $defaultseqarray);
    echo $res;
    return $res;
}
?>