<?php

include_once '../sales/sales_function.php';

if (isset($_POST['STdate']) && $_POST['STdate'] != ""){
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";

    $srid = $_POST['srlist'];
    $prevdate = $_POST['prevdate'];
    $userid = $_POST['userid'];
    $customerno = $_POST['customerno'];
    $srarr = $_POST['srarr'];
    $startdate = $_POST['STdate'];
    $enddate = $_POST['STdate'];
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

        //include 'pages/panels/secsalescallheader.php';

        // $datecheck = datediff($startdate, $enddate);
        // $datediffcheck = date_SDiff($startdate, $enddate);
        echo $getcalldata = getCallSummaryData($data);
           
        
    }
} 
?>

