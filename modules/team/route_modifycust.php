<?php

include_once("../../lib/system/utilities.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/model/VODevices.php");
include_once("../user/new_alerts_func.php");
include_once "../../lib/bo/TeamManager.php";
$db = new DatabaseManager();
$teamid = GetLoggedInUserId();
$tm = new TeamManager();

$customerid = $_POST['customerid'];

    if (isset($_POST["add_notes"])) {
        $customer_notes = GetSafeValueString($_POST['customer_notes'], "string");
        $note_mail = GetSafeValueString($_POST['tags1'], "string");
        $note_mail = explode(',', $note_mail);
        $arrTo = array_unique(array_filter($note_mail));
        ////////////////Commercial Details insert -start ///////////////////////
        $today = date("Y-m-d H:i:s");
        $sql = sprintf("INSERT INTO " . DB_PARENT . ".`customer_notes` (
                    `details`,
                    `customerno` ,
                    `entrytime`,
                    `added_by`
                    )VALUES ('%s','%d','%s','%s');", $customer_notes, $customerid, $today, $teamid);
        $db->executeQuery($sql);

        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "Note";
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $customer_notes, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
    }

    if (isset($_POST["adduser"])) {
        $name = isset($_POST['name']) ? $_POST['name'] : "";
        $username = isset($_POST['username']) ? $_POST['username'] : "";
        $email = isset($_POST['email']) ? $_POST['email'] : "";
        $password = isset($_POST['password']) ? $_POST['password'] : "";
        $password1 = $password;
        $phoneno = isset($_POST['phoneno']) ? $_POST['phoneno'] : "";
        $role = isset($_POST['role']) ? $_POST['role'] : "Administrator";
        $roleid = isset($_POST['roleid']) ? $_POST['roleid'] : 5;
        $groupid = 0;
        $customerno = $_POST['customerno'];
        $modified_by = getelixirid($customerno);
        $date = new Date();
        $today = $date->MySQLNow();
        $userkey = generate_random_userkey();//For customer
        echo $SQL = sprintf("INSERT INTO " . DB_PARENT . ".user
                            (`customerno`,
                            `realname`,
                            `username`,
                            `password`,
                            `role`,
                            `roleid`,
                            `email`,
                            `phone`,
                            `userkey`,
                            `modifiedby`,
                            `isdeleted`,
                            `groupid`
                            )
                            VALUES ('%d','%s','%s',sha1('%s'),'%s',%d,'%s','%s','%s',%d,%d,%d)"
                , $customerno, $name, $username, $password, $role, $roleid, $email, $phoneno, $userkey, $modified_by, 0, 0);
        $db->executeQuery($SQL);

        $sendemail = SendEmailNewUser($email, $username, $password1);
    }

   if (isset($_POST["update_customer"])) {
    // print_r($_POST['invoice_month']); exit;
        // Attempting to save changes now.
        $custsms = GetSafeValueString($_POST["customersms"], "string");
        $custtelephonicalert = GetSafeValueString($_POST["customertelephonicalert"], "string");
        $custname = GetSafeValueString($_POST["customername"], "string");
        $custcompany = GetSafeValueString($_POST["customercompany"], "string");
        $cunitprice = GetSafeValueString($_POST["cunitprice"], "string");
        $cwarehouse_price = GetSafeValueString($_POST["cwarehouse_price"], "string");
        $cmsp = GetSafeValueString($_POST["cmsp"], "string");
        $custrenewal = GetSafeValueString($_POST['duration'], "string");
        $timezone = GetSafeValueString($_POST['timezone'], "string");
        $comdetails = GetSafeValueString($_POST['comdetails'], "string");
        $leaseduration = isset($_POST['leaseduration']) ? GetSafeValueString($_POST['leaseduration'], "int") : 0;
        $leaseprice = isset($_POST['leaseprice']) ? GetSafeValueString($_POST['leaseprice'], "int") : 0;
        $extended_usage = !empty($_POST['extended_usage']) ? GetSafeValueString($_POST['extended_usage'], "int") : 0;
        $invoice_month = isset($_POST['invoice_month']) ? $_POST['invoice_month'] : '';
        $industrytype = isset($_POST['industrytype']) ? $_POST['industrytype'] : 0;
        $salesid = isset($_POST['sales']) ? $_POST['sales'] : 0;
        $wmsp = isset($_POST['wmsp']) ? GetSafeValueString($_POST['wmsp'], "int") : 0;
        $invoice_status = 0;
        if (!isset($_POST["invoice_status"])) {
            $invoice_status = 0;
        }
        else
        {
            $invoice_status=1;
        }
    
        if ($leaseduration == '-1') {
            $leaseduration = GetSafeValueString($_POST['customlease'], "int");
        }
        // Use AC

        $cac = 1;
        if (!isset($_POST["cac"])) {
            $cac = 0;
        }

        // Use Genset
        $cgenset = 1;
        if (!isset($_POST["cgenset"])) {
            $cgenset = 0;
        }

        // Use Fuel
        $cfuel = 1;
        if (!isset($_POST["cfuel"])) {
            $cfuel = 0;
        }

        // Use Door
        $cdoor = 1;
        if (!isset($_POST["cdoor"])) {
            $cdoor = 0;
        }

        // Use Panic
        $cpanic = 1;
        if (!isset($_POST["cpanic"])) {
            $cpanic = 0;
        }

        // Use Buzzer
        $cbuzzer = 1;
        if (!isset($_POST["cbuzzer"])) {
            $cbuzzer = 0;
        }

        // Use Immobilizer
        $cimmo = 1;
        if (!isset($_POST["cimmobilizer"])) {
            $cimmo = 0;
        }

        // Use Mobility
        $mob = 1;
        if (!isset($_POST["cimobility"])) {
            $mob = 0;
        }

        // Use Sales Engage
        $csalesengage = 1;
        if (!isset($_POST["csalesengage"])) {
            $csalesengage = 0;
        }

        // Use Delivery
        $cdelivery = 1;
        if (!isset($_POST["cdelivery"])) {
            $cdelivery = 0;
        }

        // Use Routing
        $crouting = 1;
        if (!isset($_POST["crouting"])) {
            $crouting = 0;
        } else if ($cdelivery == 0) {
            $crouting = 0;
        }


        // Use Loading
        $cloading = 1;
        if (!isset($_POST["cloading"])) {
            $cloading = 0;
        }

        // Use GeoLocation
        $cgeolocation = 1;
        if (!isset($_POST["cgeolocation"])) {
            $cgeolocation = 0;
        }

        // Use Maintenance Module
        $cmaintenance = 1;
        if (!isset($_POST["cmaintenance"])) {
            $cmaintenance = 0;
        }

        // Use Traking
        $ctraking = 0;
        if (isset($_POST["ctracking"])) {
            $ctraking = 1;
        }

        // Use Portable Module
        $cportable = 1;
        if (!isset($_POST["cportable"])) {
            $cportable = 0;
        }

        // Use Heirarchy
        $cheirarchy = 1;
        if (!isset($_POST["cheirarchy"])) {
            $cheirarchy = 0;
        } else if ($cmaintenance == 0) {
            $cheirarchy = 0;
        }


        //Use ERP
        $cerp  = 0;
        if (isset($_POST['cerp'])) {
            $cerp = 1;
        }

        //Use Warehouse
        $cwarehouse = 0;
        if (isset($_POST['cwarehouse'])) {
            $cwarehouse = 1;
        }
        $ctrace = 0;
        if (isset($_POST['ctrace'])) {
            $ctrace = 1;
        }
        $cbooks = 0;
        if (isset($_POST['cbooks'])) {
            $cbooks = 1;
        }
        $ccrm = 0;
        if (isset($_POST['ccrm'])) {
            $ccrm = 1;
        }
        $cct = 0;
        if (isset($_POST['cct'])) {
            $cct = 1;
        }

        // Temperature Sensors
        $ctempsensor = GetSafeValueString($_POST["ctempsensor"], "long");
        $advanced_alert = (int) $_POST['advancedAlerts'];
        $custome_InvoiceStatus = 0;
        if (isset($_POST['custom_inv_gen'])) {
            $custome_InvoiceStatus = 1;
        }
        if (isset($custsms) && $custsms > 0) {
            $SMS_SQL = sprintf("UPDATE " . DB_PARENT . ".customer SET
                `totalsms`=`totalsms`+'%d',
                `smsleft`=`smsleft`+'%d',
                `sms_balance_alert`='0'
                WHERE `customerno`=%d", Sanitise::Long($custsms), Sanitise::Long($custsms), $customerid);
            
            $db->executeQuery($SMS_SQL);
        }

        $SQL = sprintf("UPDATE " . DB_PARENT . ".customer SET
                `customername`='%s',
                `industryid`='%d',
                `salesid`='%d',
                `customercompany`='%s',
                `total_tel_alert`=`total_tel_alert`+'%d',
                `tel_alertleft`=`tel_alertleft`+'%d',
                `use_msgkey`='%d',
                `use_geolocation`='%d',
                `use_tracking`='%d',
                `use_maintenance`='%d',
                `use_portable`='%d',
                `temp_sensors`='%d',
                `use_hierarchy`='%d',
                `use_advanced_alert`= '%d',
                `use_ac_sensor`= '%d',
                `use_genset_sensor`= '%d',
                `use_fuel_sensor`= '%d',
                `use_panic`= '%d',
                `use_buzzer`= '%d',
                `use_immobiliser`= '%d',
                `use_mobility`= '%d',
                `use_sales` = '%d',
                `use_erp` = '%d',
                `use_warehouse` = '%d',
                `use_books` = '%d',
                `use_controlTower` = '%d',
                `use_crm` = '%d',
                `use_trace` = '%d',
                `use_door_sensor`= '%d',
                `use_delivery`= '%d',
                `use_routing`= '%d',
                `unitprice`= '%s',
                `unit_msp`= '%s',
                `warehouse_price`= '%s',
                `warehouse_msp`= '%d',
                `renewal`= '%d',
                `timezone`='%d',
                `lease_duration`=%d,
                `lease_price`=%d,
                `commercial_details`='%s',
                `currentMonthInv`='%d',
                `invoiceHoldStatus`='%d',
                `extended_usage`='%d',
                `generate_invoice_month`='%d'
                WHERE customerno = %d ", $custname, $industrytype, $salesid, $custcompany, Sanitise::Long($custtelephonicalert), Sanitise::Long($custtelephonicalert), Sanitise::Long($cloading), Sanitise::Long($cgeolocation), Sanitise::Long($ctraking), Sanitise::Long($cmaintenance), Sanitise::Long($cportable), Sanitise::Long($ctempsensor), Sanitise::Long($cheirarchy), $advanced_alert, $cac, $cgenset, $cfuel, $cpanic, $cbuzzer, $cimmo, $mob, $csalesengage,$cerp,$cwarehouse,$cbooks,$cct,$ccrm,$ctrace, $cdoor, $cdelivery, $crouting, $cunitprice, $cmsp, $cwarehouse_price ,$wmsp, $custrenewal, $timezone, $leaseduration, $leaseprice, $comdetails,$custome_InvoiceStatus,$invoice_status,$extended_usage,$invoice_month,$customerid);
        // echo $SQL; exit;
        $db->executeQuery($SQL);
        

        if (isset($extended_usage) && !empty($extended_usage)) {

            $SQL=sprintf('SELECT deviceid,end_date from devices WHERE customerno = ' . $customerid);
            $db->executeQuery($SQL);
            $count=$db->get_rowCount();
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $device_id=$row['deviceid'];
                    $end_date=$row['end_date'];
                    $date_obj= new DateTime();
                    $date_obj->data=add($end_date,$extended_usage);
                    $new_end_date= $date_obj->data->format('Y-m-d');
                    $SQL= sprintf("UPDATE devices SET `end_date`='%s'  WHERE customerno = %d AND deviceid = %d " ,$new_end_date,$customerid,$device_id);
                    $db->executeQuery($SQL);
                }
            }
        
        }

        $SQL = sprintf('SELECT totalsms FROM ' . DB_PARENT . '.customer WHERE customerno = ' . $customerid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $totalsms = $row["totalsms"];
            }
        }

        if ($cmaintenance == 1 && $cheirarchy == 1) {
            $SQL = sprintf("select userid, username, role from user where customerno=" . $customerid . " and role = 'Administrator'");
            $db->executeQuery($SQL);
            if ($db->get_rowCount() > 0) {
                $userid_admin = array();
                while ($row = $db->get_nextRow()) {
                    $userid_admin[] = $row['userid'];
                }
                $users = implode(',', $userid_admin);
                $SQLT = sprintf("UPDATE user SET role ='Master', roleid=1 WHERE userid in (" . $users . ") and customerno=".$customerid."");
              $db->executeQuery($SQLT);
            }
        } 
        else {
            $SQL = sprintf("SELECT userid, username, role from user where customerno=" . $customerid . " and role = 'Master'");
            $db->executeQuery($SQL);
            if ($db->get_rowCount() > 0) {
                $userid_admin = array();
                while ($row = $db->get_nextRow()) {
                    $userid_admin[] = $row['userid'];
                }
                $users = implode(',', $userid_admin);
                $SQLT = sprintf("update user SET role ='Administrator', roleid=5 where userid in (" . $users . ") and customerno=" . $customerid . " ");
                $db->executeQuery($SQLT);
            }
        }


        $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
        `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`)
        VALUES (
        $customerid, 0, '%s', 2, '%s', 0, '%s', '%s', '%s', '%s')", GetLoggedInUserId(), Sanitise::DateTime($today), "SMS Added : " . $custsms . ". Total SMS : " . $totalsms, "", "", "");
        $db->executeQuery($SQLunit);

        //header("Location: customers.php");
        header("Location: modifycustomer.php?cid=$customerid");
    }


    function getCustomerDetails($customerid){
        global $db;
        $temp_customer = array();
        $SQL = sprintf("SELECT c.* from " . DB_PARENT . ".customer c where c.customerno = '%d' LIMIT 1 ", $customerid);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $customer = new customers();
            $customer->custname = $row["customername"];
            $customer->custcompany = $row["customercompany"];
            $customer->custphone = $row["customerphone"];
            $customer->custcell = $row["customercell"];
            $customer->custemail = $row["customeremail"];
            $customer->custsms = $row["totalsms"];
            $customer->custtelephonicalert = $row["total_tel_alert"];
            $customer->cloading = $row["use_msgkey"];
            $customer->cgeolocation = $row["use_geolocation"];
            $customer->cmaintenance = $row["use_maintenance"];
            $customer->cportable = $row["use_portable"];
            $customer->ctempsensor = $row["temp_sensors"];
            $customer->cheirarchy = $row["use_hierarchy"];
            $customer->ctraking = $row['use_tracking'];
            $customer->advanced_alert = $row["use_advanced_alert"];
            $customer->ac_sensor = $row["use_ac_sensor"];
            $customer->genset_sensor = $row["use_genset_sensor"];
            $customer->fuel_sensor = $row["use_fuel_sensor"];
            $customer->door_sensor = $row["use_door_sensor"];
            $customer->routing = $row["use_routing"];
            $customer->panic = $row["use_panic"];
            $customer->buzzer = $row["use_buzzer"];
            $customer->immobilizer = $row["use_immobiliser"];
            $customer->mobility = $row["use_mobility"];
            $customer->salesengage = $row["use_sales"];
            $customer->erp = $row["use_erp"];
            $customer->controlTower = $row["use_controlTower"];
            $customer->books = $row["use_books"];
            $customer->crm = $row["use_crm"];
            $customer->trace = $row["use_trace"];
            $customer->warehouse = $row["use_warehouse"];
            $customer->delivery = $row["use_delivery"];
            $customer->unitprice = $row["unitprice"];
            $customer->unit_msp = $row["unit_msp"];
             $customer->warehouse_price = $row["warehouse_price"];
            $customer->warehouse_msp = $row["warehouse_msp"];
            $customer->cust_renewal = $row["renewal"];
            $customer->t_zone = $row['timezone'];
            $customer->comdetails = $row['commercial_details'];
            $customer->lease_period = $row['lease_duration'];
            $customer->lease_price = $row['lease_price'];
            $customer->salesid = $row['salesid'];
            $customer->industryid = $row['industryid'];
            $customer->invoiceHold = $row['invoiceHoldStatus'];
            $customer->invoice_month = $row['generate_invoice_month'];
            if($row['invoiceHoldStatus']==''){
                $customer->invoiceHold = 0;
            }
            $customer->currentMonthInv = $row['currentMonthInv'];
            if($row['currentMonthInv']==''){
                $customer->invoiceHold = 0;
            }
            $temp_customer[] = $customer;
        }
        return $temp_customer[0];
    }

    function getDevices($customerid){
        $devices = array();
        $db = new DatabaseManager();
        $isGenset = 0;
        $sms_lock_url = "";
        $SQL = sprintf("SELECT devices.start_date, devices.end_date, devices.device_invoiceno, devices.invoiceno, unit.uid, devices.customerno, devices.expirydate, unit.unitno, trans_status.status, devices.lastupdated, devices.registeredon,
                    vehicle.vehicleno,vehicle.vehicleid,vehicle.sms_lock, unit.command, unit.setcom, devices.simcardid,unit.extra_digital,t1.transmitterno as transmitter1,t2.transmitterno as transmitter2
                    FROM devices
                    INNER JOIN unit ON unit.uid = devices.uid
                    LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
                    LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                    LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
                    LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
                    INNER JOIN trans_status ON trans_status.id = unit.trans_statusid
                    WHERE unit.customerno = %d
                    ORDER BY devices.lastupdated DESC,unit.trans_statusid ASC, devices.simcardid DESC,simcard.trans_statusid ASC",
                    $customerid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $x = 1;
           while ($row = $db->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row["uid"];
                $device->startdate = date("d-m-Y", strtotime($row["start_date"]));
                $device->enddate = date("d-m-Y", strtotime($row["end_date"]));
                $device->device_invoiceno = $row["device_invoiceno"];
                $device->customerno = $row["customerno"];
                $customerno = $row["customerno"];
                $device->invoiceno = $row["invoiceno"];
                $device->expirydate = date("d-m-Y", strtotime($row["expirydate"]));
                if ($row["uid"] == 0) {
                    $device->unitassigned = "Not yet";
                } else {
                    $device->unitassigned = $row["unitno"];
                }
                $device->device_status =$row["status"];
                $device->phone = $row["phone"];
                $device->lastupdated = date("d-m-Y h:i", strtotime($row["lastupdated"]));
                $device->registeredon = $row["registeredon"];
                $device->vehicleno = $row["vehicleno"];
                $device->sms_lock = $row["sms_lock"];
                $device->vehicleid = $row['vehicleid'];
                $device->teamid = $_SESSION['sessionteamid'];
                $sms_lock = $row["sms_lock"];
                if ($sms_lock == 1) {
                    $sms_lock_url = "<a href='javascript:void(0);' alt='SMS Lock ' title='SMS Lock' onclick='lockstatusvehicle(1," . $vehicleid . "," . $teamid . "," . $customerno . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/lock_green.png'/></a>";
                } else {
                    $sms_lock_url = "<a href='javascript:void(0);' alt='SMS Unlock ' title='SMS Unlock' onclick='lockstatusvehicle(0," . $vehicleid . "," . $teamid . "," . $customerno . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/lock_open_green.png'/></a>";
                }
                $device->smslockurlvehicle = $sms_lock_url;

                $device->x = $x;
                if ($row["setcom"] == 1) {
                    $device->setcom = "YES: " . $row["command"];
                } else {
                    $device->setcom = "NO";
                }
                $device->simcardid = $row["simcardid"];
                $device->extra_digital = $row['extra_digital'];
                if ($device->extra_digital == '1' || $device->extra_digital == '2') {
                    $isGenset++;
                }
                $device->isGenset = $isGenset;
                $device->geneset1 = $row['transmitter1'];
                $device->geneset2 = $row['transmitter2'];

                if (($device->extra_digital == '1' || $device->extra_digital == '2') && $device->genset1 == '') {
                    $device->geneset1 = "Not Assigned";
                } else if (($device->extra_digital == '1' || $device->extra_digital == '2') && $device->genset1 != '') {
                    $device->geneset1 = $row['transmitter1'];
                } else {
                    $device->geneset1 = "Not Available";
                }
                if ($device->extra_digital == '2' && $device->genset2 == '') {
                    $device->geneset2 = "Not Assigned";
                } else if ($device->extra_digital == '2' && $device->genset2 != '') {
                    $device->geneset2 = $row['transmitter2'];
                } else {
                    $device->geneset2 = "Not Available";
                }
                //echo  $device->geneset1;
                //$device->geneset = $device->geneset1. "-" .$device->geneset2;
                $devices[] = $device;
                $x++;
            }
        }
            return $devices;
    }

    function getCustomerNotes($customerid){
        $db = new DatabaseManager();
        $SQL = sprintf("SELECT * from " . DB_PARENT . ".customer_notes WHERE customerno = %d AND isdeleted=0 order by entrytime desc", $customerid);
        $db->executeQuery($SQL);
        $details = array();
        if ($db->get_rowCount() > 0) {
            $x = 1;
            while ($row = $db->get_nextRow()) {
                $comdetail = new stdClass();
                $comdetail->cdid = $row["cdid"];
                $comdetail->srno = $x;
                $comdetail->details = $row["details"];
                $comdetail->entrytime = date("d-m-Y H:i", strtotime($row["entrytime"]));
                $details[] = $comdetail;
                $x++;
            }
        }
        return $details;
    }

    function generate_random_userkey(){
        global $tm;
        $userkey=mt_rand();
        $userkey_check = $tm->check_unique_userkey($userkey);

        if($userkey_check){
            generate_random_userkey();
        }
        else{
            return $userkey;
        }
    }

    function getelixirid($customerno) {
        $db = new DatabaseManager();
        $SQL = sprintf("SELECT userid FROM " . DB_PARENT . ".user where role='elixir' AND customerno=" . $customerno);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $userid = $row["userid"];
            }
        }
        return $userid;
    }

    function SendEmailNewUser($mail, $username, $password) {
        // send email
        $arrTo = array($mail);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "New Registration Details";
        $message = file_get_contents('../../modules/emailtemplates/NewUserRegister.html');
        $message = str_replace("{{USERNAME}}", $username, $message);
        $message = str_replace("{{PASSWORD}}", $password, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isMailSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isMailSent;
    }

    function add($date_str, $months) {

            $date = new DateTime($date_str);

            // We extract the day of the month as $start_day
            $start_day = $date->format('j');

            // We add 1 month to the given date
            $date->modify("+{$months} month");

            // We extract the day of the month again so we can compare
            $end_day = $date->format('j');

            if ($start_day != $end_day)
            {
                // The day of the month isn't the same anymore, so we correct the date
                $date->modify('last day of last month');
            }

            return $date;
        }

?>