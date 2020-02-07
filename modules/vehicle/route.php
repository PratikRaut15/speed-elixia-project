<?php
include 'vehicle_functions.php';
if (isset($_GET['delvid'])) {
    delvehicle($_GET['delvid']);
    header("location: vehicle.php?id=2");
} elseif (isset($_POST['vehicleid'])) {
    if (isset($_POST['delete'])) {
        delvehicle($_POST['vehicleid']);
        header("location: vehicle.php?id=2");
    }
    $checkpoints = array();
    $fences = array();
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 14) == "to_checkpoint_") {
            $checkpoints[] = substr($single_post_name, 14, 25);
        }
    }
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 9) == "to_fence_") {
            $fences[] = substr($single_post_name, 9, 25);
        }
    }
    $n1 = isset($_POST['n1']) ? $_POST['n1'] : '0';
    $n2 = isset($_POST['n2']) ? $_POST['n2'] : '0';
    $n3 = isset($_POST['n3']) ? $_POST['n3'] : '0';
    $n4 = isset($_POST['n4']) ? $_POST['n4'] : '0';
    $min_temp3_limit = isset($_POST['min_temp3_limit']) ? $_POST['min_temp3_limit'] : '0';
    $max_temp3_limit = isset($_POST['max_temp3_limit']) ? $_POST['max_temp3_limit'] : '0';
    $min_temp4_limit = isset($_POST['min_temp4_limit']) ? $_POST['min_temp4_limit'] : '0';
    $max_temp4_limit = isset($_POST['max_temp4_limit']) ? $_POST['max_temp4_limit'] : '0';
    $temp1_allowance = isset($_POST['allowance_temp1']) ? $_POST['allowance_temp1'] : '0';
    $temp2_allowance = isset($_POST['allowance_temp2']) ? $_POST['allowance_temp2'] : '0';
    $temp3_allowance = isset($_POST['allowance_temp3']) ? $_POST['allowance_temp3'] : '0';
    $temp4_allowance = isset($_POST['allowance_temp4']) ? $_POST['allowance_temp4'] : '0';
    $batch = isset($_POST['batch']) ? $_POST['batch'] : NULL;
    $work_key = isset($_POST['work_key']) ? $_POST['work_key'] : NULL;
    $STime = isset($_POST['STime']) ? $_POST['STime'] : NULL;
    $SDate = isset($_POST['SDate']) ? $_POST['SDate'] : NULL;
    $tempObj = new stdClass();
    if (isset($_POST['Temperature1'])) {
        $tempObj->tempSenor1 = $_POST['Temperature1'];
    }
    if (isset($_POST['Temperature2'])) {
        $tempObj->tempSenor2 = $_POST['Temperature2'];
    }
    if (isset($_POST['Temperature3'])) {
        $tempObj->tempSenor3 = $_POST['Temperature3'];
    }
    if (isset($_POST['Temperature4'])) {
        $tempObj->tempSenor4 = $_POST['Temperature4'];
    }
    $dummybatch = isset($_POST['dummybatch']) ? $_POST['dummybatch'] : NULL;
    $sel_master = isset($_POST['sel_master']) ? $_POST['sel_master'] : NULL;
    $min_humidity_limit = isset($_POST['min_humidity_limit']) ? $_POST['min_humidity_limit'] : '0';
    $max_humidity_limit = isset($_POST['max_humidity_limit']) ? $_POST['max_humidity_limit'] : '0';
    $overspeed_limit = isset($_POST['overspeed_limit']) ? $_POST['overspeed_limit'] : '0';
    $average = isset($_POST['average']) ? $_POST['average'] : '0';
    $fuelcapacity = isset($_POST['fuelcapacity']) ? $_POST['fuelcapacity'] : '0';
    $min_temp1_limit = isset($_POST['min_temp1_limit']) ? $_POST['min_temp1_limit'] : '0';
    $max_temp1_limit = isset($_POST['max_temp1_limit']) ? $_POST['max_temp1_limit'] : '0';
    $min_temp2_limit = isset($_POST['min_temp2_limit']) ? $_POST['min_temp2_limit'] : '0';
    $max_temp2_limit = isset($_POST['max_temp2_limit']) ? $_POST['max_temp2_limit'] : '0';

    $tempObj->staticTemp1 = isset($_POST['chkStaticTemp1']) ? $_POST['chkStaticTemp1'] : '0';
    $tempObj->staticTemp2 = isset($_POST['chkStaticTemp2']) ? $_POST['chkStaticTemp2'] : '0';
    $tempObj->staticTemp3 = isset($_POST['chkStaticTemp3']) ? $_POST['chkStaticTemp3'] : '0';
    $tempObj->staticTemp4 = isset($_POST['chkStaticTemp4']) ? $_POST['chkStaticTemp4'] : '0';

    modifyvehicle($_POST['vehicleno'], $_POST['vehicleid'], $_POST['type'], $checkpoints, $fences, $_POST['groupid'], $overspeed_limit, $average, $fuelcapacity, $min_temp1_limit, $max_temp1_limit, $min_temp2_limit, $max_temp2_limit, $min_temp3_limit, $max_temp3_limit, $min_temp4_limit, $max_temp4_limit, $batch, $work_key, $STime, $SDate, $dummybatch, $sel_master, $min_humidity_limit, $max_humidity_limit, $n1, $n2, $n3, $n4, $temp1_allowance, $temp2_allowance, $temp3_allowance, $temp4_allowance, $tempObj);
    header("location: vehicle.php?id=2");
} elseif (isset($_POST)) {
    $checkpoints = array();
    $fences = array();
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 14) == "to_checkpoint_") {
            $checkpoints[] = substr($single_post_name, 14, 25);
        }
    }
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 9) == "to_fence_") {
            $fences[] = substr($single_post_name, 9, 25);
        }
    }
    $temp1_min = isset($_POST['min_temp1_limit']) ? $_POST['min_temp1_limit'] : '0';
    $temp1_max = isset($_POST['max_temp1_limit']) ? $_POST['max_temp1_limit'] : '0';
    $temp1_allowance = isset($_POST['allowance_temp1']) ? $_POST['allowance_temp1'] : '0';
    $temp2_min = isset($_POST['min_temp2_limit']) ? $_POST['min_temp2_limit'] : '0';
    $temp2_max = isset($_POST['max_temp2_limit']) ? $_POST['max_temp2_limit'] : '0';
    $temp2_allowance = isset($_POST['allowance_temp2']) ? $_POST['allowance_temp2'] : '0';
    $temp3_min = isset($_POST['min_temp3_limit']) ? $_POST['min_temp3_limit'] : '0';
    $temp3_max = isset($_POST['max_temp3_limit']) ? $_POST['max_temp3_limit'] : '0';
    $temp3_allowance = isset($_POST['allowance_temp3']) ? $_POST['allowance_temp3'] : '0';
    $temp4_min = isset($_POST['min_temp4_limit']) ? $_POST['min_temp4_limit'] : '0';
    $temp4_max = isset($_POST['max_temp4_limit']) ? $_POST['max_temp4_limit'] : '0';
    $temp4_allowance = isset($_POST['allowance_temp4']) ? $_POST['allowance_temp4'] : '0';
    $batch = isset($_POST['batch']) ? $_POST['batch'] : NULL;
    $work_key = isset($_POST['work_key']) ? $_POST['work_key'] : NULL;
    $STime = isset($_POST['STime']) ? $_POST['STime'] : NULL;
    $SDate = isset($_POST['SDate']) ? $_POST['SDate'] : NULL;
    $dummybatch = isset($_POST['dummybatch']) ? $_POST['dummybatch'] : NULL;
    $sel_master = isset($_POST['sel_master']) ? $_POST['sel_master'] : NULL;
    $min_humidity_limit = isset($_POST['min_humidity_limit']) ? $_POST['min_humidity_limit'] : 0;
    $max_humidity_limit = isset($_POST['max_humidity_limit']) ? $_POST['max_humidity_limit'] : 0;
    $tempObj = new stdClass();
    if (isset($_POST['Temperature1'])) {
        $tempObj->tempSenor1 = $_POST['Temperature1'];
    }
    if (isset($_POST['Temperature2'])) {
        $tempObj->tempSenor2 = $_POST['Temperature2'];
    }
    if (isset($_POST['Temperature3'])) {
        $tempObj->tempSenor3 = $_POST['Temperature3'];
    }
    if (isset($_POST['Temperature4'])) {
        $tempObj->tempSenor4 = $_POST['Temperature4'];
    }
    $tempObj->staticTemp1 = isset($_POST['chkStaticTemp1']) ? $_POST['chkStaticTemp1'] : '0';
    $tempObj->staticTemp2 = isset($_POST['chkStaticTemp2']) ? $_POST['chkStaticTemp2'] : '0';
    $tempObj->staticTemp3 = isset($_POST['chkStaticTemp3']) ? $_POST['chkStaticTemp3'] : '0';
    $tempObj->staticTemp4 = isset($_POST['chkStaticTemp4']) ? $_POST['chkStaticTemp4'] : '0';
    insertvehicle($_POST['vehicleno'], $_POST['type'], $checkpoints, $fences, $_POST['groupid'], $_POST['overspeed_limit'], $_POST['average'], $_POST['fuelcapacity'], $temp1_min, $temp1_max, $temp2_min, $temp2_max, $temp3_min, $temp3_max, $temp4_min, $temp4_max, $batch, $work_key, $STime, $SDate, $dummybatch, $sel_master, $min_humidity_limit, $max_humidity_limit, $temp1_allowance, $temp2_allowance, $temp3_allowance, $temp4_allowance, $tempObj);
    header("location: vehicle.php?id=2");
}
?>