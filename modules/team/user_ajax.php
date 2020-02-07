<?php

include_once("session.php");
include_once("loginorelse.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/utilities.php");
set_time_limit(0);
extract($_REQUEST);

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");

$db = new DatabaseManager();
$pdo = $db->CreatePDOConn();
$timestamp = date('Y-m-d H:i:s');

if ($action == "smslockstatus") {
    $vehicleid = 0;
    $userid = $_REQUEST['userid'];
    $is_success = 0;
    if ($lockstatus == 1) {
        // unlock 
        $lock = 0;
        $smscount = 0;
        $SQL = sprintf("update  " . DB_PARENT . ".user set sms_lock=0, sms_count =0 where userid=" . $userid);
        $db->executeQuery($SQL);
        $SQL1 = sprintf("update  " . DB_PARENT . ".smslocklog set updatedby=" . $teamid . ", updatedon='" . $timestamp . "' where userid=" . $userid);
        $db->executeQuery($SQL1);
        $is_success = 1;
    } elseif ($lockstatus == 0) {
        // lock
        $SQL3 = sprintf("select updatedby
                        from " . DB_PARENT . ".smslocklog 
                        where   userid =" . $userid . " 
                        ORDER BY logid DESC 
                        LIMIT   1");

        $db->executeQuery($SQL3);

        if ($db->get_rowCount() > 0) {
            while ($row1 = $db->get_nextRow()) {
                $updatedby = $row1["updatedby"];
            }
        }
        if (!empty($updatedby)) {
            $lock = 1;
            $SQL = sprintf("update  " . DB_PARENT . ".user set sms_lock=" . $lock . " where userid=" . $userid);
            $db->executeQuery($SQL);

            $sql = sprintf("INSERT INTO " . DB_PARENT . ".`smslocklog`( 
                            `customerno`,
                            `userid`,
                            `vehicleid`,
                            `createdby`,
                            `createdon`
                            )VALUES (%d,%d,%d,%d,'%s');", $customerid, $userid, $vehicleid, $teamid, $timestamp);
            $db->executeQuery($sql);
            $is_success = 2;
        }
    }
    if ($is_success != 0) {
        //send sms sanket or customer admin 
        $SQL1 = sprintf("select name from " . DB_PARENT . ".team where teamid =" . $teamid);
        $db->executeQuery($SQL1);
        if ($db->get_rowCount() > 0) {
            while ($row1 = $db->get_nextRow()) {
                $teamname = $row1["name"];
            }
        }

        $SQL = sprintf("select u.realname,u.username,u.email,c.customercompany,c.customername from " . DB_PARENT . ".user as u INNER JOIN customer as c on u.customerno = c.customerno where userid=" . $userid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $realname = $row["realname"];
                $username = $row["username"];
                $email = $row["email"];
                $customercompany = $row["customercompany"];
                $customername = $row["customername"];
            }
        }
        // send email
        $sanket = "sanketseth1@gmail.com";
        $ganesh = "ganeshpapde702@gmail.com";
        // $arrTo = array($email,$sanket,$ganesh);
        $arrTo = array($ganesh);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "";
        if ($lock == 0) {  //unlock 
            $subject = "Sms unlocked for user " . $realname;
        } else {   // lock 
            $subject = "Sms locked for user " . $realname;
        }
        $datetime = date('d-m-Y H:i:s', strtotime($today));
        $userdetails = $subject . "<br><br> Customer company Name : " . $customercompany . "<br><br> Customerno : " . $customerid . "<br><br> Updated By : " . $teamname . "<br><br> Updated time : " . $datetime;
        $message = file_get_contents('../../modules/emailtemplates/sms_lock_team.html');
        $vehicledetails = "";
        $message = str_replace("{{USERDETAILS}}", $userdetails, $message);
        $message = str_replace("{{VEHICLEDETAILS}}", $vehicledetails, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        echo "ok";
    }
} else if ($action == "smslockstatusvehicle") {
    $vehicleid = $_REQUEST['vehicleid'];
    $db = new DatabaseManager();
    $userid = 0;
    $is_success = 0;
    if ($lockstatusvehicle == 1) {  // unlock 
        $lockvehicle = 0;
        $smscount = 0;
        $SQL = sprintf("update  " . DB_PARENT . ".vehicle set sms_lock =" . $lockvehicle . ", sms_count=" . $smscount . " where vehicleid=" . $vehicleid);
        $db->executeQuery($SQL);
        $SQL1 = sprintf("update  " . DB_PARENT . ".smslocklog set updatedby=" . $teamid . ", updatedon='" . $timestamp . "' where vehicleid=" . $vehicleid);
        $db->executeQuery($SQL1);
        $is_success = 1;
    } else {  // lock
        $SQL3 = sprintf("select updatedby from " . DB_PARENT . ".smslocklog where vehicleid =" . $vehicleid . " ORDER BY logid DESC LIMIT 1");
        $db->executeQuery($SQL3);
        if ($db->get_rowCount() > 0) {
            while ($row1 = $db->get_nextRow()) {
                $updatedby = $row1["updatedby"];
            }
        }
        if (!empty($updatedby)) {
            $lockvehicle = 1;
            $SQL = sprintf("update  " . DB_PARENT . ".vehicle set sms_lock =" . $lockvehicle . " where vehicleid=" . $vehicleid);
            $db->executeQuery($SQL);

            $sql = sprintf("INSERT INTO " . DB_PARENT . ".`smslocklog`( 
                            `customerno`,
                            `userid`,
                            `vehicleid`,
                            `createdby`,
                            `createdon`
                            )VALUES (%d,%d,%d,%d,'%s');", $customerid, $userid, $vehicleid, $teamid, $timestamp);
            $db->executeQuery($sql);
            $is_success = 2;
        }
    }
    if ($is_success != 0) {
        $SQL1 = sprintf("select name from " . DB_PARENT . ".team where teamid =" . $teamid);
        $db->executeQuery($SQL1);
        if ($db->get_rowCount() > 0) {
            while ($row1 = $db->get_nextRow()) {
                $teamname = $row1["name"];
            }
        }

        $SQL2 = sprintf("select c.customername,c.customercompany,v.vehicleno from " . DB_PARENT . ".vehicle as v INNER JOIN " . DB_PARENT . ".customer as c on c.customerno = v.customerno where v.isdeleted=0 AND v.vehicleid = " . $vehicleid . " AND v.customerno =" . $customerid);
        $db->executeQuery($SQL2);
        if ($db->get_rowCount() > 0) {
            while ($row2 = $db->get_nextRow()) {
                $vehicleno = $row2["vehicleno"];
                $customername = $row2["customername"];
                $customercompany = $row2["customercompany"];
            }
        }

        $SQL = sprintf("select realname,username,email from " . DB_PARENT . ".user where roleid=5 AND isdeleted=0 AND customerno=" . $customerid);
        $db->executeQuery($SQL);
        $emailArr = array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $emailArr[] = $row["email"];
            }
        }
        $emalstr = implode(',', $emailArr);

        // send email
        $sanket = "sanketseth1@gmail.com";
        // $ganesh = "ganeshpapde702@gmail.com";
        // $arrTo = array($emalstr,$sanket,$ganesh);
        $arrTo = array($sanket);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        if ($lock == 0) {  //unlock 
            $subject = "Sms unlocked for vehicle " . $vehicleno;
        } else {   // lock 
            $subject = "Sms locked for vehicle " . $vehicleno;
        }
        $datetime = date('d-m-Y H:i:s', strtotime($today));
        $userdetails = $subject . "<br><br> Customer company Name : " . $customercompany . "<br><br> Customerno : " . $customername . "<br><br> Updated By : " . $teamname . "<br><br> Updated time : " . $datetime;
        $message = file_get_contents('../../modules/emailtemplates/sms_lock_team.html');
        $vehicledetails = "";
        $message = str_replace("{{USERDETAILS}}", $userdetails, $message);
        $message = str_replace("{{VEHICLEDETAILS}}", $vehicledetails, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        echo "ok";
    }
} else if ($action == "deletestage") { // delete sales stage
    $db = new DatabaseManager();
    $SQL = sprintf("update  " . DB_PARENT . ".sales_stage set isdeleted =1 where stageid=" . $stageid);
    $db->executeQuery($SQL);
    echo "ok";
} else if ($action == "deletesource") {  // delete sales source
    $db = new DatabaseManager();
    $SQL = sprintf("update  " . DB_PARENT . ".sales_source set isdeleted =1 where 	sourceid=" . $sourceid);
    $db->executeQuery($SQL);
    echo "ok";
} else if ($action == "deleteproduct") {  // delete sales product 
    $db = new DatabaseManager();
    $SQL = sprintf("update  " . DB_PARENT . ".sales_product set isdeleted =1 where productid =" . $productid);
    $db->executeQuery($SQL);
    echo "ok";
} else if ($action == "deleteindustry") {  // delete sales industry type 
    $db = new DatabaseManager();
    $SQL = sprintf("update  " . DB_PARENT . ".sales_industry_type set isdeleted =1 where industryid=" . $industryid);
    $db->executeQuery($SQL);
    echo "ok";
} else if ($action == "deletemode") {  // delete sales mode 
    $db = new DatabaseManager();
    $SQL = sprintf("update  " . DB_PARENT . ".sales_mode set isdeleted =1 where modeid=" . $modeid);
    $db->executeQuery($SQL);
    echo "ok";
} else if ($action == "deleteuser") {  // delete user delete sales flow 
    $db = new DatabaseManager();
    $SQL = sprintf("update  " . DB_PARENT . ".sales_contact set isdeleted =1 where contactid=" . $contactid);
    $db->executeQuery($SQL);
    echo "ok";
} else if ($action == "deletepipeline") {  // delete pipeline record
    $db = new DatabaseManager();
    echo $SQL = sprintf("update  " . DB_PARENT . ".sales_pipeline set isdeleted =1 where pipelineid=" . $pipelineid);
    echo "pipelineid ".$pipelineid;
    $db->executeQuery($SQL);
    echo "ok";
} else if ($action == "deletereminder") {  // delete reminder record
    $db = new DatabaseManager();
    $SQL = sprintf("update  " . DB_PARENT . ".sales_reminder set isdeleted =1 where reminderid=" . $reminderid);
    $db->executeQuery($SQL);
    echo "ok";
}else if($action == "addPipelineUser"){
    $usermessage = "";
    if ($username!= "" && $userphone != "" && $useremail) {
        $db = new DatabaseManager();
        $pipelineid = $userpipelineid;
        $username = $username;
        $userdesignation = $userdesignation;
        $userphone = $userphone;
        $useremail = $useremail;

        $SQL = sprintf("SELECT * FROM " . DB_PARENT . ".sales_contact where pipelineid=" . $pipelineid . " AND name='" . $username . "' AND phone ='" . $userphone . "' AND email='" . $useremail . "' AND isdeleted=0");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $usermessage = "User Already exists.";
        }
        $sqlContactQuery1 = sprintf("INSERT INTO " . DB_PARENT . ".`sales_contact` (`pipelineid` ,
                                            `designation` ,
                                            `name` ,
                                            `phone` ,
                                            `email` ,
                                            `timestamp` ,
                                            `teamid_creator`)
                                    VALUES (%d,'%s','%s','%s','%s','%s',%d);"
                , Sanitise::Long($pipelineid)
                , Sanitise::String($userdesignation)
                , Sanitise::String($username)
                , Sanitise::String($userphone)
                , Sanitise::String($useremail)
                , Sanitise::DateTime($today)
                , Sanitise::Long(GetLoggedInUserId()));
        $db->executeQuery($sqlContactQuery1);
        echo "ok";
    }   
}else if($action == "addPipelineReminder"){
    if ($reminderdatetime != "" && $content != "" && $contact!= "") {
        $stime = GetSafeValueString($STime, "string");
        $reminderdate = GetSafeValueString($reminderdatetime, "string");
        $rdate = $reminderdate . " " . $stime . ":00";
        $rdatetime = date('Y-m-d H:i:s', strtotime($rdate));
        $content = GetSafeValueString($content, "string");
        $userpipelineid = GetSafeValueString($userpipelineid, "string");
        $teamidsales = isset($teamidsales) ? GetSafeValueString($teamidsales, "string") : GetLoggedInUserId();

        $sqlQueryreminder = sprintf("INSERT INTO " . DB_PARENT . ".`sales_reminder`(`reminder_datetime`,
                                            `content`,
                                            `pipelineid`,
                                            `contactid`,
                                            `timestamp`,
                                            `teamid_creator`)
                                    VALUES ('%s','%s',%d,%d,'%s',%d);", Sanitise::DateTime($rdatetime)
                , Sanitise::String($content)
                , Sanitise::Long($userpipelineid)
                , Sanitise::Long($teamidsales)
                , Sanitise::DateTime($today)
                , Sanitise::Long(GetLoggedInUserId()));
        $db->executeQuery($sqlQueryreminder);
        echo "ok";
    }
}
?>
