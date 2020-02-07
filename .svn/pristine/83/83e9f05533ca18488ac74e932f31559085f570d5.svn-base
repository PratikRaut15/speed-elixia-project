<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';

$dailydate = date('Y-m-d', strtotime("- 1 day"));
$reportDate = new DateTime($dailydate);

$customerno = 64;
$groupid = 0;
$userid = 0;
$useMaintenance = 1;
$useHierarchy = 1;

$objUserManager = new UserManager();
$groupmanager = new GroupManager($customerno);
$objVehicleMgr = new VehicleManager($customerno);

$users = $objUserManager->maintenance_userslist($customerno, null, 37);

//prettyPrint($users);
if (isset($users) && !empty($users)) {
    foreach ($users as $thisuser) {
        $thisuser = (object) $thisuser;

        $arrGroupIds = array();
        $groups = $objUserManager->get_groups_fromuser($customerno, $thisuser->userid);
        if (isset($groups)) {
            foreach ($groups as $group) {
                $arrGroupIds[] = $group->groupid;
            }
        }

        $objDailyReport = new stdClass();
        $objDailyReport->customerno = $customerno;
        $objDailyReport->dailydate = $dailydate;
        $objDailyReport->groupIds = $arrGroupIds;
        $objDRM = new DailyReportManager($customerno);
        $Data = $objDRM->getDailyReportDetails($objDailyReport);
        $arrOverspeed = array();

        if (isset($Data)) {
            foreach ($Data as $thisdata) {
                $veh_no = $thisdata->vehicleno;
                $groupname = $thisdata->groupname;


                    //prettyPrint($thisdata);

                    if($customerno == 64){
                      $heirarchyDetails = $objUserManager->vehicleHeirarchy($customerno,$thisuser->userid,$thisdata->vehicleid);
                      //print_r($heirarchyDetails);echo $heirarchyDetails[0]['regionalUserSAP'];die();
                    }

                    /* Send SMS To Branch User*/
                    //$message = "OTP: " . $otpparam . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($validuptodate));
                    $smsMessage = "Dear " . $thisuser->realname . "\r\n,
                    Please download the insurance policy of the company vehicle allotted to you from the below mentioned link.\r\n
                    If any discrepancy kindly get in touch with regional I&S Team.\r\n
                    Open the link: " . $link . "\r\n Regards, \r\n HO- Infrastructure & Services Team\r\n Mahindra Finance";
                    $response = '';
                    $thisuser->phoneno = $thisuser->phone;

                    if (isset($thisuser->phoneno) && !empty($thisuser->phoneno)) {
                        $isSMSSent = 0;//sendSMSUtil(array($thisuser->phoneno), $smsMessage, $response);
                        $moduleid = 1;
                        if ($isSMSSent == 1) {
                            $objCustomer = new CustomerManager();
                            //$smsId = $objCustomer->sentSmsPostProcess($customerno, array($thisuser->phoneno), $message, $response, $isSMSSent, $userid, $thisdata->vehicleid, $moduleid);
                        }
                    }
            die();
            }
            //prettyPrint($arrOverspeed);
        }
    }
}
//</editor-fold>
