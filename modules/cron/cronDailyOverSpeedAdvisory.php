<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';

$dailydate = date('Y-m-d', strtotime("- 1 day"));
$reportDate = new DateTime($dailydate);
$vehiclelistarray = array("8164",
    "13973",
    "13596",
    "8248",
    "8363",
    "10628",
    "7990",
    "8350",
    "8272",
    "8025",
    "8335",
    "8341",
    "8267",
    "8330",
    "8565",
    "7994",
    "8340",
    "8155",
    "8250",
    "8171",
    "8332",
    "8373",
    "8569",
    "8347",
    "8042",
    "8026",
    "8015",
    "8027",
    "8055",
    "8036",
    "8028",
    "8017",
    "8013",
    "8024",
    "8030",
    "8014",
    "8187",
    "8648",
    "8653",
    "8654",
    "8652",
    "8651",
    "8636",
    "8649",
    "8647",
    "8643",
    "8646",
    "8645",
    "5378",
    "8640",
    "8638",
    "7210",
    "7187",
    "7203",
    "7188",
    "7060",
    "7202",
    "7193",
    "7194",
    "7197",
    "7189",
    "15436",
    "15435",
    "7065",
    "8431",
    "8421",
    "8496",
    "8855",
    "8482",
    "8432",
    "8424",
    "8499",
    "8428",
    "8489",
    "8419",
    "8546",
    "8511",
    "11818",
    "9104",
    "2577",
    "902",
    "6946",
    "6949",
    "6950",
    "6947",
    "6977",
    "7015",
    "6941",
    "6942",
    "6943",
    "6951",
    "6933",
    "6945",
    "6959",
    "6957",
    "6991",
    "11644",
    "7993",
    "13745",
    "15536",
    "14788",
    "6944",
    "12165",
    "8486",
    "8854",
    "8903",
    "8494",
    "8051",
    "8507",
    "6934",
    "9389",
    "8492",
    "8631",
    "8633",
    "8627",
    "8632",
    "8635",
    "8629",
    "13746",
    "8630",
    "8415",
    "10540",
    "8148",
    "7983",
    "8165",
    "9297",
    "7998",
    "7958",
    "8375",
    "9296",
    "9105",
    "8275",
    "8362",
    "9278",
    "8366",
    "9228",
    "7962",
    "8054",
    "8005",
    "7984",
    "7961",
    "7975",
    "7968",
    "8273",
    "8158",
    "8326",
    "8291",
    "8290",
    "8864",
    "8264",
    "8254",
    "8547",
    "8280",
    "8161",
    "8324",
    "8262",
    "8151",
    "8268",
    "8258",
    "8334",
    "8269",
    "8954",
    "8298",
    "8525",
    "9294",
    "8276",
    "11177",
    "14859",
    "1120",
    "10572",
    "2488",
    "2579",
    "2570",
    "2727",
    "2574",
    "2455",
    "2616",
    "2631",
    "2619",
    "2494",
    "2673",
    "2632",
    "8958",
    "2625",
    "2629",
    "14320",
    "9318",
    "2623",
    "9397",
    "2627",
    "2666",
    "7516",
    "6059",
    "2665",
    "2671",
    "6641",
    "2445",
    "2454",
    "2458",
    "2476",
    "2542",
    "2451",
    "2481",
    "12989",
    "2633",
    "2725",
    "2668",
    "2661",
    "14699",
    "1122",
    "8211",
    "8011",
    "8008",
    "7259",
    "7261",
    "7099",
    "7241",
    "7246",
    "8858",
    "11088",
    "7020",
    "7078",
    "7027",
    "7256",
    "7248",
    "12119",
    "11923",
    "8867",
    "8860",
    "7255",
    "7263",
    "7245",
    "7371",
    "7192",
    "13972",
    "7201",
    "7076",
    "13975",
    "7084",
    "7068",
    "7236",
    "7252",
    "7254",
    "7250",
    "7262",
    "7244",
    "7260",
    "7247",
    "8866",
    "8917",
    "8856",
    "7361",
    "14862",
    "11089",
    "15844",
    "8023",
    "7970",
    "8010",
    "8052",
    "8007",
    "8029",
    "8057",
    "8423",
    "8430",
    "8422",
    "8417",
    "8416",
    "15444",
    "8434",
    "8426",
    "8420",
    "7982",
    "7773",
    "13977",
    "7768",
    "7818",
    "7839",
    "7769",
    "7815",
    "7842",
    "8940",
    "14245",
    "13072",
    "7771",
    "7772",
    "7838",
    "7775",
    "5350",
    "7841",
    "7749",
    "6324",
    "10922",
    "8274",
    "8006",
    "10884",
    "8289",
    "8003",
    "13594",
    "7981",
    "8150",
    "7995",
    "8149",
    "7989",
    "7987",
    "7964",
    "8157",
    "7960",
    "8265",
    "14858",
    "7977",
    "8038",
    "12513",
    "8342",
    "8039",
    "8510",
    "8508",
    "8502",
    "14126",
    "8641",
    "8500",
    "8501",
    "8503",
    "15441",
    "8506",
    "9109",
    "8589",
    "8531",
    "8605",
    "9068",
    "9077",
    "8585",
    "8534",
    "8522",
    "8583",
    "8530",
    "8607",
    "8536",
    "8535",
    "8520",
    "8587",
    "8596",
    "8594",
    "8600",
    "8857",
    "8529",
    "8603",
    "8490",
    "8598",
    "8532",
    "8518",
    "7098",
    "8609",
    "15438",
    "8526",
    "11169",
    "8597",
    "8610",
    "9115",
    "8611",
    "8604",
    "14992",
    "8626",
    "8602",
    "8493",
    "7973",
    "8046",
    "8002",
    "8009",
    "8048",
    "8019",
    "8043",
    "8040",
    "8348",
    "8022",
    "8047",
    "8179",
    "8049",
    "8050",
    "8018",
    "7980",
    "8037"
);
$customerno = 64;
$groupid = 0;
$userid = 0;
$useMaintenance = 1;
$useHierarchy = 1;

$objUserManager = new UserManager();
$groupmanager = new GroupManager($customerno);
$objVehicleMgr = new VehicleManager($customerno);

$users = $objUserManager->maintenance_userslist($customerno, null, 37);

prettyPrint($users);
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
                if ($thisdata->overspeed > 0 && $thisdata->topspeed >= $thisdata->overspeed_limit && in_array($thisdata->vehicleid, $vehiclelistarray) && $objVehicleMgr->isUserVehicleMappingExists($thisuser->userid, $thisdata->vehicleid)) {
                    //prettyPrint($thisdata);

                    if ($customerno == 64) {
                        $heirarchyDetails = $objUserManager->vehicleHeirarchy($customerno, $thisuser->userid, $thisdata->vehicleid);
                        //print_r($heirarchyDetails);echo $heirarchyDetails[0]['regionalUserSAP'];die();
                    }

                    $message = '';
                    $reportPeriod = $reportDate->format(speedConstants::DEFAULT_DATE);

                    $subject = "Office Vehicle " . $thisdata->vehicleno . " Over Speed Advisory " . $reportPeriod;

                    $placehoders['{{REALNAME}}'] = $thisuser->realname;
                    $placehoders['{{REPORT_DATE}}'] = $reportPeriod;
                    $placehoders['{{CUSTOMER}}'] = $customerno;
                    $placehoders['{{SUBJECT}}'] = $subject;
                    $placehoders['{{VEHICLENUMBER}}'] = $thisdata->vehicleno;

                    $placehoders['{{BRANCHNAME}}'] = $heirarchyDetails[0]['branchname'];
                    $placehoders['{{REGIONNAME}}'] = $heirarchyDetails[0]['regionname'];
                    $placehoders['{{ZONENAME}}'] = $heirarchyDetails[0]['zonename'];
                    $placehoders['{{OVERSPEED}}'] = $thisdata->overspeed;
                    $html = file_get_contents('../emailtemplates/cronDailyOverSpeedAdvisory.html');
                    foreach ($placehoders as $key => $val) {
                        $html = str_replace($key, $val, $html);
                    }

                    /* Send SMS To Branch User*/
                    //$message = "OTP: " . $otpparam . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($validuptodate));
                    $smsMessage = "Dear " . $thisuser->realname . "\r\n, Speed Advisory!\r\n Vehicle Number " . $thisdata->vehicleno . " has Over Speed for " . $thisdata->overspeed . " times on " . $reportPeriod . ".\r\n Please follow speed limits for company vehicles and stay safe.\r\n Regards, \r\n Vehicle Management Team- I&S HO.";
                    $response = '';
                    $thisuser->phoneno = $thisuser->phone;

                    if (isset($thisuser->phoneno) && !empty($thisuser->phoneno)) {
                        $isSMSSent = 0; //sendSMSUtil(array($thisuser->phoneno), $smsMessage, $response);
                        $moduleid = 1;
                        if ($isSMSSent == 1) {
                            $objCustomer = new CustomerManager();
                            $smsId = $objCustomer->sentSmsPostProcess($customerno, array($thisuser->phoneno), $message, $response, $isSMSSent, $userid, $thisdata->vehicleid, $moduleid);
                        }
                    }

                    /* Send Email To Branch User and Regional And Zonal Users in CC*/
                    $message .= $html;
                    $attachmentFilePath = '';
                    $attachmentFileName = '';
                    $CCEmail = '' . $thisuser->regionalUserSAPEmail . ', kanade.akash@mahindra.com';
                    //$CCEmail = 'kanade.akash@mahindra.com';
                    $BCCEmail = 'software@elixiatech.com';
                    $mailid = $thisuser->email;
                    $isMailSent = sendMailUtil(array($mailid), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                    if (isset($isMailSent)) {
                        echo $message;
                    } else {
                        echo "Test";
                    }
                }
            }
            //prettyPrint($arrOverspeed);
        }
    }
}
//</editor-fold>
