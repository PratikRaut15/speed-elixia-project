<?php

$reportType = $_REQUEST['pg'];
$getReportDetails = getTripDetailsReport($reportType, $_SESSION["customerno"],$_SESSION['userid']);

echo $getReportDetails;
?>
<script type="text/javascript">
    function getStoppageReport(edate, etime, sdate, stime, deviceid, interval, vehicleno) {
    window.open('../reports/reports.php?id=15&sdate=' + sdate + '&stime=' + stime + '&edate=' + edate + '&etime=' + etime + '&deviceid=' + deviceid + '&interval=' + interval + '&vehicleno=' + vehicleno, '_blank');
}
</script>
