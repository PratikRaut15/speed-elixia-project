<?php 
if(isset($_POST['STdate']) && isset($_POST['EDdate'])){
    
    include 'reports_common_functions.php';
    
    $today = date("Y-m-d");
    $sdate = $_POST['STdate'];
    $newsdate = date("Y-m-d", strtotime($sdate));
    $edate = $_POST['EDdate'];
    $extra = explode('|', $_POST['extra']);
    $extraid = $extra[0];
    $extraval = $extra[1];
    $newedate = date("Y-m-d", strtotime($edate));
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    $error3 = "<script>jQuery('#error7').show();jQuery('#error7').fadeOut(3000)</script>";
    $error4 = "<script>jQuery('#error8').show();jQuery('#error8').fadeOut(3000)</script>";
    $error5 = "<script>jQuery('#error9').show();jQuery('#error9').fadeOut(3000)</script>";
    $error6 = "<script>jQuery('#error10').show();jQuery('#error10').fadeOut(3000)</script>";
    $error7 = "<script>jQuery('#error11').show();jQuery('#error11').fadeOut(3000)</script>";
    $datecheck = datediff($_POST['STdate'], $_POST['EDdate']);
    $deviceid = GetSafeValueString($_POST['deviceid'], 'long');
    $datediffcheck = date_SDiff($newsdate, $newedate);
    $extra_digital = getextra($deviceid);
   
    if(strtotime($edate) > strtotime($today)){
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";exit;
    }
    
    if(isset($_SESSION['eocdeid'])){
        $startdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['s_start'], 'string')));
        $enddate = date('Y-m-d',strtotime(GetSafeValueString($_POST['e_end'], 'string')));
        if(strtotime($_POST['STdate']) < strtotime($startdate) || strtotime($_POST['EDdate']) > strtotime($enddate)){
            echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
        }
    }
    if($extraid=='000'){
        echo $error3;
    }
    else if($datecheck==1){
        if($datediffcheck <= 30){
        $STdate = GetSafeValueString($_POST['STdate'], 'string');
        $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
        
        include 'pages/panels/extrasensorhistrep.php';
        getextragensetreport($STdate, $EDdate, $deviceid,$extraid,$extraval);
        }
        else{
            echo $error2;
        }
    }
    else if($datecheck==0)
    {
        echo $error1;
    }
    
    else
    {
        echo $error;
    }
}
?>
