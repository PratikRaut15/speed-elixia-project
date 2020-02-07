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
$roleid = 37;
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
$period = date('F Y', strtotime('-1 month'));
$today = date('Y-m-d', strtotime('-1 month')); // Means code is executing on 1st of new month; so look for previous month
$STdate = date('Y-m-01', strtotime($today)); // First day of the month.
$EDdate = date('Y-m-t', strtotime($today)); // Last day of the month.
$totalDays = gendays($STdate, $EDdate); // Get total days in this month; considering today it's the last day of current month.
$location = "../../../../customer/$customerno/reports/dailyreport.sqlite"; // Set path of SQLITE file

/**
 * @internal Get all groups for given customerno
 */
$param['roleid'] = $roleid;
$arrUsers = ($objGroupMgr->getUsers_by_customerno_role($param));
$arrUserGroupIds = array();
$html = file_get_contents('../../../emailtemplates/customer/' . $customerno . '/cronMonthlyDistanceAnalysis.html');
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
    $regional_userid = $value->regional_userid;
    $branch_id = $value->branch_id;

    $arrUserGroupIds[$regional_userid]['groups'][] = $branch_id;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['branch_id'] = $branch_id;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['branch_name'] = $value->branch_name;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['branch_userid'] = $value->branch_userid;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['branch_username'] = $value->branch_username;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['branch_userphone'] = $value->branch_userphone;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['branch_useremail'] = $value->branch_useremail;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['branch_userroleid'] = $value->branch_userroleid;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['branch_userrolename'] = $value->branch_userrolename;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['regional_userid'] = $value->regional_userid;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['regional_username'] = $value->regional_username;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['regional_phone'] = $value->regional_phone;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['regional_email'] = $value->regional_email;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['regional_code'] = $value->regional_code;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['regional_name'] = $value->regional_name;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['zonalIs'] = $value->zonalIs;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['zonal_code'] = $value->zonal_code;
    $arrUserGroupIds[$regional_userid]['groupdetail'][$branch_id]['zonal_name'] = $value->zonal_name;
}
//prettyPrint($arrUserGroupIds);
foreach ($arrUserGroupIds as $userid => $arrUserData) {
    $regional_userid = $userid;
    $phonearray = array();

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
    //print_r($options);die();
    $DATA = GetDailyReport_Data_All($location, $totalDays, $options);

    //prettyPrint($DATA);die();
    // Create Vehicle - date-wise - totaldistance array
    $cntr = 0;
    $temparr = array();
    if (!empty($DATA)) {
        foreach ($DATA as $key => $vehicleData) {
            $tbl1_data = '';
            $vehicles = '';
            $arr[$vehicleData->vehicleid]['vehicleno'] = $vehicleData->vehicleno;
            $arr[$vehicleData->vehicleid][$vehicleData->info_date] = $vehicleData->totaldistance;

            if (!in_array($vehicleData->vehicleid, $temparr)) {
                $groupid = $vehicleData->groupid;

                $cntr++;

                $branch_id = $arrUserData['groupdetail'][$groupid]['branch_id'];
                $branch_name = $arrUserData['groupdetail'][$groupid]['branch_name'];
                $branch_userid = $arrUserData['groupdetail'][$groupid]['branch_userid'];
                $branch_username = preg_replace('/[^A-Za-z0-9\- ]/', '', $arrUserData['groupdetail'][$groupid]['branch_username']);
                $branch_userphone = $arrUserData['groupdetail'][$groupid]['branch_userphone'];
                $branch_useremail = $arrUserData['groupdetail'][$groupid]['branch_useremail'];
                $regional_userid = $arrUserData['groupdetail'][$groupid]['regional_userid'];
                $regional_username = $arrUserData['groupdetail'][$groupid]['regional_username'];
                $regional_phone = $arrUserData['groupdetail'][$groupid]['regional_phone'];
                $regional_email = $arrUserData['groupdetail'][$groupid]['regional_email'];
                $regional_code = $arrUserData['groupdetail'][$groupid]['regional_code'];
                $regional_name = $arrUserData['groupdetail'][$groupid]['regional_name'];
                $zonalIs = $arrUserData['groupdetail'][$groupid]['zonalIs'];
                $zonal_code = $arrUserData['groupdetail'][$groupid]['zonal_code'];
                $zonal_name = $arrUserData['groupdetail'][$groupid]['zonal_name'];

                $tbl1_data = '<tr><td>' . $cntr . '</td>'
                . '<td><input type="hidden" name="tbl1_veh_id" value="' . $vehicleData->vehicleid . '" /> ' . $vehicleData->vehicleno . '</td>'
                    . '<td><input type="hidden" name="tbl1_branch_id" value="' . $branch_id . '" /> ' . $branch_name . '</td>'
                    . '<td><input type="hidden" name="tbl1_branch_userid" value="' . $branch_userid . '" /> ' . $branch_username . '</td>'
                    . '<td><input type="hidden" name="tbl1_regional_code" value="' . $regional_code . '" /> ' . $regional_name . '</td>'
                    . '<td><input type="hidden" name="tbl1_regional_userid" value="' . $regional_userid . '" /> ' . $regional_username . '</td>'
                    . '<td><input type="hidden" name="tbl1_zonal_code" value="' . $zonal_code . '" /> ' . $zonal_name . '</td>'
                    . '<td>' . $zonalIs . '</td>';

                $vehicles .= $vehicleData->vehicleno;

                $tbl = $tbl1_header1 . $tbl1_data . '</tr></table><br/>';
                $arr[$vehicleData->vehicleid]['vehicle_tbl1'] = $tbl;
                $arr[$vehicleData->vehicleid]['groupname'] = $vehicleData->groupname;
                $arr[$vehicleData->vehicleid]['groupid'] = $branch_id;
                $arr[$vehicleData->vehicleid]['branch_useremail'] = $branch_useremail;
                $arr[$vehicleData->vehicleid]['branch_username'] = $branch_username;
                $arr[$vehicleData->vehicleid]['branch_userphone'] = $branch_userphone;
                $arr[$vehicleData->vehicleid]['regional_username'] = $regional_username;
                $arr[$vehicleData->vehicleid]['regional_email'] = $regional_email;
                $arr[$vehicleData->vehicleid]['regional_phone'] = $regional_phone;
                $arr[$vehicleData->vehicleid]['vehicles'] = $vehicles;

                $temparr[] = $vehicleData->vehicleid;
            }
        }
        $tbl = '';
        $html_arr = array();

        // Create Display tables per vehicle
        foreach ($arr as $vehicleid => $distData) {
            $table = '';
            $tbl2_data = '';
            $totalDaysTravelled = $totalDaysActive = $totalDaysInactive = $totalDistanceTravelled = 0;
            $tbl2_data .= '<tr><td>Distance in Kms</td>';

            // Print distance table
            foreach ($totalDays as $date) {
                if (isset($distData[$date])) {
                    $tbl2_data .= '<td><input type="hidden" name="tbl2_veh_id" value="' . $vehicleid . '" />' . round($distData[$date] / 1000) . '</td>';

                    if ($distData[$date] > 0) {
                        $totalDaysTravelled++;
                        $totalDistanceTravelled += $distData[$date];
                    }
                } else {
                    $tbl2_data .= '<td>**N/A</td>';
                    $totalDaysInactive++;
                }
            }
            $totalDaysActive = count($totalDays) - $totalDaysInactive;
            $table .= $distData['vehicle_tbl1'];
            $table .= $tbl2_header1 . $tbl2_data . '</tr></table><br/>';
            $table .= '<p>Total Days Travelled:' . $totalDaysTravelled . '</p>';
            $table .= '<p>Total Days Vehicle Active:' . $totalDaysActive . '</p><br/><br/>';

            $html_arr[$userid][$distData['groupid']]['html'][] = $table;
            $html_arr[$userid][$distData['groupid']]['branch_useremail'] = $distData['branch_useremail'];
            $html_arr[$userid][$distData['groupid']]['branch_username'] = $distData['branch_username'];
            $html_arr[$userid][$distData['groupid']]['branch_userphone'] = $distData['branch_userphone'];
            $html_arr[$userid][$distData['groupid']]['branch_name'] = $distData['groupname'];
            $html_arr[$userid][$distData['groupid']]['regional_username'] = $distData['regional_username'];
            $html_arr[$userid][$distData['groupid']]['regional_email'] = $distData['regional_email'];
            $html_arr[$userid][$distData['groupid']]['regional_phone'] = $distData['regional_phone'];
            $html_arr[$userid][$distData['groupid']]['vehicles'] = $distData['vehicles'];
            die();
        }

        /**
         * @internal Send email to every branch manager/user - all vehicles in 1 mail under him
         */
        foreach ($html_arr[$userid] as $grpid => $grpdata) {
            $branch_tbl = '';
            $branch_tbl = implode('', $grpdata['html']);

            $branchmailParam = array(
                'today' => $today,
                'customerno' => $customerno,
                'username' => $grpdata['branch_username'],
                'phone' => $grpdata['branch_userphone'],
                'email' => $regional_email, //$grpdata['branch_useremail'], // for branch useremail
                'htmltable' => $branch_tbl,
                'regional_username' => $grpdata['regional_username'],
                'regional_phone' => $grpdata['regional_phone'],
                'regional_email' => $grpdata['regional_email'],
                'branch_name' => $grpdata['branch_name'],
                'vehicle_no' => $grpdata['vehicles'],
            );
            //sendMail($branchmailParam);

            $phonearray = array($grpdata['branch_userphone']);
            $smsParam = array(
                'customerno' => $customerno,
                'userid' => $userid,
                'phone' => $phonearray,
                'username' => $grpdata['branch_username'],
                'field1_type' => 'branch',
                'field1_value' => $grpdata['branch_name'],
            );
            //sendSms($smsParam);
            $tbl .= $branch_tbl;
        }

        /**
         * @internal Send email to regional manager/user
         */
        $mailParam = array(
            'today' => $today,
            'customerno' => $customerno,
            'username' => $regional_username,
            'phone' => $regional_phone,
            'email' => $regional_email,
            'htmltable' => $tbl,
            'regional_username' => $regional_username,
            'regional_phone' => $regional_phone,
            'regional_email' => $regional_email,
            'branch_name' => '',
            'vehicle_no' => '',
        );
        sendMail($mailParam);
        die();

        /**
         * @internal Send SMS to user for each vehicle under regional manager
         * @internal Onlyif phone number is available
         */
        $phonearray = array($regional_phone);
        $smsParam = array(
            'customerno' => $customerno,
            'userid' => $userid,
            'phone' => $phonearray,
            'username' => $regional_username,
            'field1_type' => 'region(s)',
            'field1_value' => '',
        );
        //sendSms($smsParam);
        // die(); // Execute only for single user regional manager
    } else {
        echo '<br/> no data for vehicles in group - ' . $strGroupId . ', of userid - ' . $userid . '<br/>';
    }
    //die();
}

function sendSms($smsParam) {
    /**
     * @internal $smsParam = array(
    'customerno' => $customerno,
    'userid' => $userid,
    'phone' => $phonearray,
    'username' => $username,
    'field1_type' => $field1_type,
    'field1_value' => $field1_value,
    );
     */
    extract($smsParam);
    if (!empty($phone)) {
        $message = "
Dear " . $username . ",\r\n
Cumulative Distance Travelled Report for your $field1_type $field1_value has been sent to your e-mail.\r\n
Vehicle Support Team
I&S -HO";
//        echo strlen($message);
        $customermanager = new CustomerManager();
        $smsStatus = new SmsStatus();

        $smsStatus->customerno = $customerno;
        $smsStatus->userid = $userid;
        $smsStatus->vehicleid = 0;
        $smsStatus->mobileno = $phone[0];
        $smsStatus->message = $message;
        $smsStatus->cqid = 0;

        $smscount = $customermanager->getSMSStatus($smsStatus);
        if ($smscount == 0) {
            $isSMSSent = sendSMSUtil($phone, $message, $response, $smsUrl = SMS_URL);
            if ($isSMSSent == 1) {
                $moduleid = 1;
                echo 'message send to ' . $field1_type . ' user - ' . $phone[0] . '<br/>';
                echo $message;
                $customermanager->sentSmsPostProcess($customerno, $phone, $message, $response, $isSMSSent, $userid, 0, $moduleid);
            }
        }
    }
}

function sendMail($mailParam) {
    extract($mailParam);
    /**
     * @internal $mailParam = array(
    'today' => $today,
    'customerno' => $customerno,
    'username' => $username,
    'email' => $email,
    'htmltable' => $tbl,
    'regional_username' => $regional_username,
    'regional_phone' => $phone,
    'regional_email' => $regional_email,
    'branch_name' => $branch_name,
    'vehicle_no' => $vehicle_no
    );
     */
    $html = file_get_contents('../../../emailtemplates/customer/' . $customerno . '/cronMonthlyDistanceAnalysis.html');
    $html = str_replace("{{MAILTIME}}", date('d:m:Y H:i:s'), $html);
    $html = str_replace("{{USER}}", $username, $html);
    $html = str_replace("{{REGIONALInS}}", $regional_username, $html);
    $html = str_replace("{{REGIONALPHONE}}", $regional_phone, $html);
    $html = str_replace("{{REGIONALEMAIL}}", $regional_email, $html);
    $html = str_replace("{{TABLE}}", $htmltable, $html);

    //$mailTo = array($email);
    $mailTo = array('software@elixiatech.com');
    $strCCMailIds = "CHOGALE.SAMEER@mahindra.com";
    $strBCCMailIds = "";
    $subject = "Cumulative Monthly Distance Travelled Report for " . date('F Y', strtotime($today));
    if (!empty($branch_name)) {
        $subject .= ', Branch ' . $branch_name;
    }

    if (!empty($vehicle_no)) {
        $subject .= ', Vehicle(s) ' . $vehicle_no;
    }

    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;

    $isMailSent = sendMailUtil($mailTo, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    if ($isMailSent == 1) {
        echo '<br/><br/><br/> mail send to user - ' . $email;
        echo '<br/>Subject: ' . $subject;
        echo $html;
    }
}

?>
