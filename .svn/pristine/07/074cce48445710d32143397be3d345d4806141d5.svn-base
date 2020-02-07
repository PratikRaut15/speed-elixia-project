<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
/**
 * @internal File inclusion
 */
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
require_once '../../../reports/reports_common_functions.php';
require_once '../../../reports/distancereport_functions.php';

/**
 * @internal Variable declaration
 */
$all_data = $arrgpIds = array();
$arrcustomerno = array(64);
$customerno = $arrcustomerno[0];
$highest_dist = $total_wkend_dist = 0;
$high_details = $highest_dist_vehno = $tbl = $tbl1_header1 = $tbl2_header1 = '';

/**
 * @internal Class instantiation
 */
$crnmanager = new CronManager();
$objGroupMgr = new GroupManager($customerno);
$vm = new VehicleManager($customerno);

/**
 * @internal Number of days setting for report
 */

$today = date('Y-m-d', strtotime('-1 month')); // Means code is executing on 1st of new month; so look for previous month
$STdate = date('Y-m-01', strtotime($today)); // First day of the month.
$EDdate = date('Y-m-t', strtotime($today)); // Last day of the month.
$totalDays = gendays($STdate, $EDdate); // Get total days in this month; considering today it's the last day of current month.
$location = "../../../../customer/$customerno/reports/dailyreport.sqlite"; // Set path of SQLITE file

/**
 * @internal Get all groups for given customerno
 */
$param['roleid'] = 36;
$arrUsers = ($objGroupMgr->getUsers_by_customerno_role($param));
$arrUserGroupIds = '';
$html = file_get_contents('../../../emailtemplates/customer/'.$customerno.'/suman-template.html');
$tbl = $tbl1_header1 = $tbl2_header1 = $tbl1_data = $tbl2_data = '';

$tbl1_header1 = "<table><tr>"
        . "<th>S.No</th>"
        . "<th>Vehicle Number</th>"
        . "<th>Branch Allotted</th>"
        . "<th>Custodian</th>"
        . "<th>Region</th>"
        . "<th>Regional I&S</th>"
        . "<th>Zone</th>"
        . "<th>Zonal I&S</th>"
        . "</tr>";

$tbl2_header1 = '<table><tr><th>Month Date</th>';

foreach ($totalDays as $day) {
    $tbl2_header1 .= '<th>' . date('l, F d, Y', strtotime($day)) . '</th>';
}
$tbl2_header1 .= '</tr>';

foreach ($arrUsers as $key => $value) {
    $arrUserGroupIds[$value->userid]['groups'][] = $value->groupid;
    $arrUserGroupIds[$value->userid]['username'] = $value->role_username;
    $arrUserGroupIds[$value->userid]['role_useremail'] = $value->role_useremail;
    $arrUserGroupIds[$value->userid]['zonalIs'] = $value->zonalIs;
    $arrUserGroupIds[$value->userid]['role_username'] = $value->role_username;
}

foreach ($arrUserGroupIds as $userid => $arrUserData) {

    echo 'Send mail to:' . $arrUserData['role_useremail'] . ' <br/>';

    $table = ''; // Get new html 
    $arr = array();

    /**
     * @internal Create comma-separated string of all groups for 1 customerno at a time
     */
    $strGroupId = implode(',', $arrUserData['groups']);

    /**
     * @internal Get date-wise totaldistance travelled by each vehicle every customerno
     */
    $options = array('islocation' => 1, 'customerno' => array($customerno), 'groupid' => array($strGroupId));
    $DATA = GetDailyReport_Data_All($location, $totalDays, $options);

    // Create Vehicle - date-wise - totaldistance array
    $cntr = 0;
    $temparr = array();

    foreach ($DATA as $key => $vehicleData) {

        $tbl1_data = '';
        $arr[$vehicleData->vehicleid]['vehicleno'] = $vehicleData->vehicleno;
        $arr[$vehicleData->vehicleid][$vehicleData->info_date] = $vehicleData->totaldistance;

        if (!in_array($vehicleData->vehicleid, $temparr)) {
            $vehicleData->vehicleid;
            $cntr++;
            $tbl1_data = '<tr><td>' . $cntr . '</td>'
                    . '<td><input type="hidden" name="tbl1_veh_id" value="' . $vehicleData->vehicleid . '" /> ' . $vehicleData->vehicleno . '</td>'
                    . '<td>' . $vehicleData->groupname . '</td>'
                    . '<td> </td>'
                    . '<td>' . $vehicleData->cityname . '</td>'
                    . '<td>' . $arrUserData['role_username'] . '</td>'
                    . '<td>' . $vehicleData->districtname . '</td>'
                    . '<td>' . $arrUserData['zonalIs'] . '</td>';
            $tbl = $tbl1_header1 . $tbl1_data . '</tr></table><br/>';
            $arr[$vehicleData->vehicleid]['vehicle_tbl1'] = $tbl;

            $temparr[] = $vehicleData->vehicleid;
        }
    }
    $tbl = '';

    // Create Display tables per vehicle
    foreach ($arr as $vehicleid => $distData) {
        $tbl2_data = '';
        $tbl2_data .= '<tr><td>Distance in Kms</td>';
        // Print distance table
        foreach ($totalDays as $date) {
            if (isset($distData[$date])) {
                $tbl2_data .= '<td><input type="hidden" name="tbl2_veh_id" value="' . $vehicleid . '" />' . ($distData[$date] /1000) . '</td>';
            } else {
                $tbl2_data .= '<td>N/A</td>';
            }
        }

        $tbl .= $distData['vehicle_tbl1'];
        $tbl .= $tbl2_header1 . $tbl2_data . '</tr></table><br/><br/>';
    }

    $html = file_get_contents('../../../emailtemplates/customer/'.$customerno.'/suman-template.html');
    $html = str_replace("{{USER}}", $arrUserData['role_username'], $html);
    $html = str_replace("{{TABLE}}", $tbl, $html);

    echo $html;
	$mailTo = array('ANTHONY.MALCOM@mahindra.com');
	$strCCMailIds = "";
	$strBCCMailIds = "mrudang.vora@elixiatech.com";
	$subject = "Cumulative Monthly Distance Travelled Report " .  date('M Y', strtotime($today));
	$attachmentFilePath = "";
	$attachmentFileName = "";
	$isTemplatedMessage = 1;
	$isMailSent = sendMailUtil($mailTo, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);


    die(); // Execute only for single user
}


//$isMailSent = sendMailUtil($mailTo, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
?>
