<?php

include_once 'vehicle_functions.php';

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
    
    $vm = new VehicleManager($_SESSION['customerno']);
    $res = $vm->vehiclesequence_update($seq_array, $defaultseqarray);
    echo $res;
    return $res;
}
?>