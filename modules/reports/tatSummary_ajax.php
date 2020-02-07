<?php 
if (isset($_POST['getReport']) && ($_POST['getReport'] == 'routeSummary' || $_POST['getReport'] == 'routeSummaryLive' )) {
    
    include_once 'route_summary_functions.php';
    $routelist = isset($_POST['routelist']) ? $_POST['routelist'] : 0;
    $sdate = $_POST['STdate'];
    $edate = $_POST['EDdate'];
    $today = date("Y-m-d");

    if (strtotime($edate) > strtotime($today)) {
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";exit;
    }

    $datecheck = datediff_cmn($sdate, $edate);
    if ($datecheck == 1) {
        $datediffcheck = date_SDiff_cmn($sdate, $edate);
        if ($datediffcheck <= 30) {
            $STdate = GetSafeValueString($sdate, 'string');
            $EDdate = GetSafeValueString($edate, 'string');
            if($_POST['getReport'] == 'routeSummaryLive')
            {
                $title = 'Route wise ETA Report';
            }
            if($_POST['getReport'] == 'routeSummary')
            {
                $title = 'Route wise Tracking Report';
            }
            
            $subTitle = array(
                "Start Date: $STdate",
                "End Date: $EDdate",
            );
            $columns = array(
            );
            echo table_header($title, $subTitle, $columns, true);
            if ($routelist != 0) {
               /*  getTatSummaryRoute($routelist, $STdate, $EDdate); */
               if($_POST['getReport'] == 'routeSummaryLive')
               {
                 getTatSummaryRouteLiveTest($routelist, $STdate, $EDdate);
               }
               if($_POST['getReport'] == 'routeSummary')
               {
                 getTatSummaryRouteTest($routelist, $STdate, $EDdate);
               }
               
            } else {
               /*  getTatSummary1($STdate, $EDdate);  */
                getTatSummary1Test($STdate, $EDdate);
            }
        } else {
            echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
        }
    } elseif ($datecheck == 0) {
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    } else {
        echo "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    }
}
?>