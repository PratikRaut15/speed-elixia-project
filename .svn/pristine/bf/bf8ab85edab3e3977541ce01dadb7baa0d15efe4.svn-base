<?php
include 'reports_common_functions.php';
$yesterday = $date = date("d-m-Y", strtotime("-1 days"));
$sdate = retval_issetor($_POST['SDate'], $yesterday); //date - replace with sdate
$edate = retval_issetor($_POST['EDate'], $yesterday);
$title = 'Inactive Device Report';
$subTitle = array(
    "From Date: $sdate",
    "End Date: $edate",
);
$dailyReportChangeDate = "20-02-2015";
$dailyReportChangeDate = strtotime($dailyReportChangeDate);
$startdate = strtotime($sdate);
$enddate = strtotime($edate);
$changedate = "2016-03-03";
echo table_header($title, $subTitle);
?>

<style>
    .newTable td{
        text-align: center;
    }
</style>
<table class="table newTable" style="width: 70%;">
    <thead>
        <tr>
            <th>#</th>
            <th>Vehicle No</th>
            <th>Start Time</th>
            <th>End Time</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $datediffcheck = date_SDiff($sdate, $edate);
        if ($datediffcheck <= 30) {
            $DATA = get_inactive_device($sdate, $edate);
            if ($DATA != NULL) {
                $x = 1;
                foreach ($DATA as $data) {
                    if($data['end_time'] != '0000-00-00 00:00:00'){
                        $endtime = date('d-m-Y H:i',  strtotime($data['end_time']));
                    }  else {
                        $endtime = '';
                    }
                    echo '<tr><td>' . $x . '</td><td>' . $data['vehicleno'] . '</td><td>' . date('d-m-Y H:i',  strtotime($data['start_time'])) . '</td><td>' . $endtime . '</td></tr>';
                    $x++;
                }
            }  else {
                echo '<tr><td colspan="4">Data not available</td></tr>';
            }
        } else {
            echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
        }
        ?>
    </tbody>
</table>

