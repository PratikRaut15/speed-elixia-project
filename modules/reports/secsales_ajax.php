<?php

include_once '../sales/sales_function.php';


if (isset($_POST['action']) && $_POST['action'] == "callreport"){
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";

    $srid = $_POST['srlist'];
    $prevdate = $_POST['prevdate'];
    $userid = $_POST['userid'];
    $customerno = $_POST['customerno'];
    $srarr = $_POST['srarr'];
    $startdate = $_POST['STdate'];
    $enddate = $_POST['EDdate'];
    if ($srid != 0) {
        $data = array(
            'srid' => $srid,
            'prevdate' => $prevdate,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'userid' => $userid,
            'customerno' => $customerno,
            'srarr' => $srarr
        );

        include 'pages/panels/secsalescallheader.php';

        $datecheck = datediff($startdate, $enddate);
        $datediffcheck = date_SDiff($startdate, $enddate);
        if ($datecheck == 1) {
            if ($datediffcheck <= 30) {
                echo $getcalldata = getCallData_datewise($data, 'all');
            } else {
                echo $error2;
            }
        } elseif ($datecheck == 0) {
            echo $error1;
        } else {
            echo $error;
        }
        
    }
} elseif (isset($_POST['action']) && $_POST['action'] == "skuorderdetails") {
    $srid = $_POST['srlist'];
    $prevdate = $_POST['prevdate'];
    $action = $_POST['action'];
    $userid = $_POST['userid'];
    $customerno = $_POST['customerno'];
    $srarr = $_POST['srarr'];
    $stdate = $_POST['STdate'];
    $edate = $_POST['EDdate'];
    $suplist = $_POST['suplist'];
    $supid = $_POST['suplist'];
    $srlist = $_POST['srlist'];
    if ($srid != 0) {
        $data = array(
            'srid' => $srid,
            'prevdate' => $prevdate,
            'action' => $action,
            'userid' => $userid,
            'customerno' => $customerno,
            'srarr' => $srarr,
            'stdate' => $stdate,
            'edate' => $edate,
            'suplist'=>$suplist,
            'supid'=>$supid,
            'srlist'=>$srlist
        );
        include 'pages/panels/secsales_skuheader.php';
        echo $getcalldata = getStyleSRData($data, 'all');
    }
}
elseif(isset($_POST['action']) && $_POST['action'] == "salessummary"){
    $srid = $_POST['srlist'];
    $prevdate = $_POST['prevdate'];
    $action = $_POST['action'];
    $userid = $_POST['userid'];
    $customerno = $_POST['customerno'];
    $srarr = $_POST['srarr'];
    $startdate = $_POST['STdate'];
    $enddate = $_POST['EDdate'];
    $suplist = $_POST['suplist'];
    $srlist = $_POST['srlist'];
    $bysupervisor = isset($_POST['bysupervisor'])?$_POST['bysupervisor']:"0";
    if ($srid != 0) {
        $data = array(
            'srid' => $srid,
            'prevdate' => $prevdate,
            'action' => $action,
            'userid' => $userid,
            'customerno' => $customerno,
            'srarr' => $srarr,
            'stdate' => $startdate,
            'edate' => $enddate,
            'suplist'=>$suplist,
            'srlist'=>$srlist,
            'bysupervisor'=>$bysupervisor
        );
        include 'pages/panels/summary_reportheader.php';
        $datecheck = datediff($startdate, $enddate);
        $datediffcheck = date_SDiff($startdate, $enddate);
        if ($datecheck == 1) {
            if ($datediffcheck <= 30){
                echo $getcalldata = getSummaryReportdatewise($data, 'all');
            } else {
                echo $error2;
            }
        } elseif ($datecheck == 0) {
            echo $error1;
        } else {
            echo $error;
        }
    }
    
}else if (isset($_POST['action']) && $_POST['action'] == "getsrlist"){
    $supid = (int) ri($_REQUEST['supid']);
    $result = getsrbysupervisor($_REQUEST['customerno'],$supid);
    echo "<option value='-1'>ALL SR</option>";
    foreach ($result as $row) {
        echo "<option value='" . $row->userid . "'>" . $row->realname . "</option>";
    }
}
?>

