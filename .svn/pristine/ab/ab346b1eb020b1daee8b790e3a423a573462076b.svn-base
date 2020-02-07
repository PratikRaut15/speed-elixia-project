<?php 
if(isset($_POST['getReport']) && $_POST['getReport']=='routeSummary'){
    
    include_once 'route_summary_functions.php';
    
    $sdate = $_POST['STdate'];
    $edate = $_POST['EDdate'];
    $today = date("Y-m-d");
    
    if(strtotime($edate) > strtotime($today)){
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";exit;
    }
    
    $datecheck = datediff_cmn($sdate, $edate);
    if($datecheck==1){
        $datediffcheck = date_SDiff_cmn($sdate, $edate);
        if($datediffcheck <= 30){
            $STdate = GetSafeValueString($sdate, 'string');
            $EDdate = GetSafeValueString($edate, 'string');
            $title = 'Route Summary Report';
            $subTitle = array(
                "Start Date: $STdate",
                "End Date: $EDdate"
            );
            $columns = array(
                'Sr No.',
                'Vehicle No',
                'Current City',
                'Driver Name',
                'Cell No',
                'Route',
                'Start',
                'End',
                'ENROUTE',
            );
            echo table_header($title, $subTitle, $columns, true);
            getRouteSummary($STdate, $EDdate);
        }
        else{
            echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
        }
    }
    else if($datecheck==0){
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    }
    else{
        echo "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    }
}
?>
