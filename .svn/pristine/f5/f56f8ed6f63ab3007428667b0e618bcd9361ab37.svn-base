<?php
/*

  Name          -       ManualDailyDistanceReport.php
  Description 	-	get back-dated day-wise distance travelled of passed unit
  Parameters	-	custno, unitno, sdate, edate
  Module	-       VTS
  Sub-Modules 	- 	Checkpoint Details IN Route Summary
  Created by	-	Shrikant Suryawanshi
  Created on	-	22 Mar, 2016
  Change details
  1)Updated by	-
  Updated on	-
  Reason	-
  2)

 */
require_once "../../lib/system/utilities.php";
require_once "../../lib/comman_function/reports_func.php";
require_once '../../lib/bo/UnitManager.php';

define("DATEFORMAT_YMD", "Y-m-d");

$customerno = $_REQUEST['custno'];
$unitno = $_REQUEST['unitno'];
$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$sdate = new DateTime($sdate);
$edate = new DateTime($edate);
$objUnitMgr = new UnitManager($customerno);
$unitdata = $objUnitMgr->getunitdetailsbyunitno($unitno);
$uid = isset($unitdata['uid']) ? $unitdata['uid'] : 0;
$data = array();
if ($uid != 0) {
    $uid = $unitdata['uid'];
    $startdate = $sdate->format(DATEFORMAT_YMD);
    $enddate = $edate->format(DATEFORMAT_YMD);
    $totaldays = gendays_cmn($startdate, $enddate);
    foreach ($totaldays as $userdate) {
        $singledaydata = getdailydistance($customerno, $uid, $unitno, $userdate);
        if (isset($singledaydata)) {
            $data = array_merge($data, $singledaydata);
        }
    }
}

function getdailydistance($customerno, $uid, $unitno, $userdate) {
    $devicedata = null;
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (!file_exists($location)) {
        return null;
    }
    if (filesize($location) == 0) {
        return null;
    }
    if (file_exists($location)) {
        $location = "sqlite:" . $location;
        $devicedata = getdistancefromsqlite($location, $uid, $unitno, $userdate);
    }
    return $devicedata;
}

function getdistancefromsqlite($location, $uid, $unitno, $userdate) {
    $sqlitedata = array();
    $dateparam = new DateTime($userdate);
    $tableName = "A" . $dateparam->format('dmy');
    try {
        $database = new PDO($location);
        $query = "SELECT totaldistance from $tableName WHERE uid=$uid LIMIT 1";
        $result = $database->query($query);
        if (isset($result) && $result != "")
            foreach ($result as $row) {
                $unitdetails = new stdClass();
                $unitdetails->date = $userdate;
                $unitdetails->unitno = $unitno;
                $unitdetails->totaldistance = $row['totaldistance'] / 1000;
                $sqlitedata[] = $unitdetails;
            }
    } catch (PDOException $e) {
        die($e);
    }
    return $sqlitedata;
}

function display_distance_data($data) {
    $distHtml = "";
    $totaldistance = 0;
    $html = "<table cellpadding='10' border='1'>";
    $html .= "<tr><td>Date</td><td>Distance</td></tr>";
    foreach ($data as $objDailyDistance) {
        $html .= "<tr>";
        $html .= "<td>" . $objDailyDistance->date . "</td>";
        $html .= "<td>" . $objDailyDistance->totaldistance . "</td>";
        $html .= "<tr>";
        $totaldistance += $objDailyDistance->totaldistance;
    }
    $html .= "</table>";
    $totalDistHtml = "<span>Total distance: $totaldistance km </span>";
    $distHtml .= $totalDistHtml . $html;
    return $distHtml;
}
?>
<table>
    <tbody>
        <?php
        if (!empty($data)) {
            $summary_data = display_distance_data($data);
            echo $summary_data;
        } else {
            echo "<tr><td style='text-align:center' colspan='100%'>No data</td></tr>";
        }
        ?>
    </tbody>
</table>

