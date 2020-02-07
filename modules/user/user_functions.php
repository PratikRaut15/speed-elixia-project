<?php

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once $RELATIVE_PATH_DOTS . "lib/comman_function/reports_func.php";
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
}
@date_default_timezone_set('' . $_SESSION['timezone'] . '');

function SetSession($user) {
    // Setting session variables
    $um = new UserManager();
    $_SESSION['multiauth'] = $user->multiauth;
    $_SESSION['Session_UserRole'] = $user->role;
    $_SESSION['userkey'] = $user->userkey;
    // Added by Pratik Raut for ERP Login
    $_SESSION['erpUserToken'] = $user->erpUserToken;
    // Added by Pratik Raut for ERP Login
    $_SESSION['userkey'] = $user->userkey;
    $_SESSION["realname"] = $user->realname;
    $_SESSION["phone"] = $user->phone;
    $_SESSION["email"] = $user->email;
    $_SESSION["portable"] = $user->portable;
    $_SESSION["buzzer"] = $user->buzzer;
    $_SESSION["immobiliser"] = $user->immobiliser;
    $_SESSION["freeze"] = $user->freeze;
    $_SESSION["veh_status"] = isset($user->rt_status_filter) ? $user->rt_status_filter : '';
    $_SESSION["veh_stoppage"] = isset($user->rt_stoppage_filter) ? $user->rt_stoppage_filter : '';
    $_SESSION["customerno"] = $user->customerno;
    $_SESSION["customercompany"] = $user->customercompany;
    $_SESSION["use_tracking"] = $user->use_tracking;
    $_SESSION["maintenance_limit"] = $user->maintenance_limit;
    $_SESSION["use_maintenance"] = $user->use_maintenance;
    $_SESSION["use_elixiadoc"] = $user->use_elixiadoc;
    $_SESSION["use_hierarchy"] = $user->use_heirarchy;
    $_SESSION["use_delivery"] = $user->use_delivery;
    $_SESSION["userid"] = $user->id;
    $_SESSION["use_loading"] = $user->loading;
    $_SESSION["temp_sensors"] = $user->temp_sensors;
    $_SESSION["use_door_sensor"] = $user->use_door_sensor;
    $_SESSION["use_ac_sensor"] = $user->use_ac_sensor;
    $_SESSION["use_fuel_sensor"] = $user->use_fuel_sensor;
    $_SESSION["use_genset_sensor"] = $user->use_genset_sensor;
    $_SESSION["use_routing"] = $user->use_routing;
    $_SESSION["use_extradigital"] = $user->use_extradigital;
    $_SESSION["use_warehouse"] = $user->use_warehouse;
    $_SESSION["use_mobility"] = $user->use_mobility;
    $_SESSION["use_secondary_sales"] = $user->use_secondary_sales;
    $_SESSION["use_sales"] = $user->use_sales;
    $_SESSION["use_pickup"] = $user->use_pickup;
    $_SESSION["use_tms"] = $user->use_tms;
    $_SESSION["use_trip"] = $user->use_trip;
    $_SESSION["use_humidity"] = $user->use_humidity;
    $_SESSION["use_toggle"] = $user->use_toggle;
    $_SESSION['refreshtime'] = $user->refreshtime;
    $_SESSION["visits_modal"] = $user->visits;
    $_SESSION["role_modal"] = $user->role;
    $_SESSION["username"] = $user->username;
    $_SESSION['Session_User'] = $user;
    $_SESSION["sessionauth"] = $user->role;
    $_SESSION["roleid"] = $user->roleid;
    $_SESSION["heirarchy_id"] = $user->heirarchy_id;
    $_SESSION["isTripuser"] = isset($user->isTripuser) ? $user->isTripuser : 0;

    /*Relation Manager Session*/
    $_SESSION["rel_manager"] = $user->rel_manager;
    $_SESSION["manager_name"] = $user->manager_name;
    $_SESSION["manager_email"] = $user->manager_email;
    $_SESSION["manager_mobile"] = $user->manager_mobile;
    $_SESSION["use_vehicle_type"] = $user->use_vehicle_type;
    $_SESSION["use_checkpoint_settings"] = $user->use_checkpoint_settings;

    $_SESSION["switch_to"] = 0;
    $_SESSION["groupid"] = $user->groups;
    $_SESSION["groupname"] = $user->groupname;
    $groupmanager = new GroupManager($user->customerno);
    if (isset($_SESSION["userid"]) && $_SESSION["groupid"] == 0) {
        $groupdata = $groupmanager->getmappedgroup($_SESSION["userid"]);
        if (isset($groupdata)) {
            $_SESSION["groupid"] = $user->groups;
        }
    }
    $_SESSION["digitalcon"] = $um->store_custom_name($user->customerno, 'Digital', 1);
    $_SESSION["administrator"] = $um->store_custom_name($user->customerno, 'Administrator', 2);
    $_SESSION["tracker"] = $um->store_custom_name($user->customerno, 'Tracker', 3);
    $_SESSION["master"] = $um->store_custom_name($user->customerno, 'Master', 4);
    $_SESSION["statehead"] = $um->store_custom_name($user->customerno, 'State Head', 5);
    $_SESSION["districthead"] = $um->store_custom_name($user->customerno, 'District Head', 6);
    $_SESSION["cityhead"] = $um->store_custom_name($user->customerno, 'City Head', 7);
    $_SESSION["branchhead"] = $um->store_custom_name($user->customerno, 'Branch Head', 8);
    $_SESSION["nation"] = $um->store_custom_name($user->customerno, 'Nation', 9);
    $_SESSION["state"] = $um->store_custom_name($user->customerno, 'State', 10);
    $_SESSION["district"] = $um->store_custom_name($user->customerno, 'District', 11);
    $_SESSION["city"] = $um->store_custom_name($user->customerno, 'City', 12);
    $_SESSION["group"] = $um->store_custom_name($user->customerno, 'Group', 13);
    $_SESSION["licno"] = $um->store_custom_name($user->customerno, 'License No', 14);
    $_SESSION["ref_number"] = $um->store_custom_name($user->customerno, 'Reference Number', 15);
    $_SESSION["ext_digital1"] = $um->store_custom_name($user->customerno, 'Digital-1', 16);
    $_SESSION["ext_digital2"] = $um->store_custom_name($user->customerno, 'Digital-2', 17);
    $_SESSION["ext_digital3"] = $um->store_custom_name($user->customerno, 'Digital-3', 18);
    $_SESSION["ext_digital4"] = $um->store_custom_name($user->customerno, 'Digital-4', 19);
    $_SESSION["Temperature 1"] = $um->store_custom_name($user->customerno, 'Temperature 1', 20);
    $_SESSION["Temperature 2"] = $um->store_custom_name($user->customerno, 'Temperature 2', 21);
    $_SESSION["Temperature 3"] = $um->store_custom_name($user->customerno, 'Temperature 3', 22);
    $_SESSION["Temperature 4"] = $um->store_custom_name($user->customerno, 'Temperature 4', 23);
    $_SESSION["extradigitalstatus"] = $um->store_custom_name($user->customerno, 'Status', 24);
    $_SESSION["Warehouse"] = $um->store_custom_name($user->customerno, 'Warehouse', 25);
    $_SESSION["timezone"] = $um->timezone_name('Asia/Kolkata', $user->timezone);
    $_SESSION["Driver"] = $um->store_custom_name($user->customerno, 'Driver', 26);
    $_SESSION["rtdHeaders"] = getRtdDashboardHeaders();
    $_SESSION["timediff"] = $user->timediff;
    $_SESSION["timezonename"] = $user->timezonename;
    $_SESSION["consignee_id"] = getConsigneeId($user->id);
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
    $log = new Log();
    if ($log->createlog($_SESSION['customerno'], "Logged In", $_SESSION['userid'])) {
    }
    return $_SESSION;
}

/* used for login and accoutn switch */

function redirection() {
    $page = '';
    if ($_SESSION['role_modal'] == "Supervisor") {
        $page = 'modules/sales/sales.php?pg=prisalesview';
        $_SESSION['switch_to'] = 6;
    } elseif ($_SESSION['role_modal'] == "Distributor") {
        $page = 'modules/sales/sales.php?pg=inventoryview';
        $_SESSION['switch_to'] = 6;
    } elseif ($_SESSION['role_modal'] == "ASM") {
        $page = 'modules/sales/sales.php?pg=prisalesview';
        $_SESSION['switch_to'] = 6;
    } elseif ($_SESSION['role_modal'] == "sales_representative") {
        $page = 'modules/sales/sales.php?pg=orderview';
        $_SESSION['switch_to'] = 6;
    } elseif ($_SESSION['role_modal'] == "sales_manager") {
        $page = 'modules/salesengage/salesengage.php?pg=view-order';
        $_SESSION['switch_to'] = 8;
    } elseif ($_SESSION['role_modal'] == "Viewer") {
        $page = 'modules/map/map.php';
    } else {
        if ($_SESSION['use_tracking'] == 1 && $_SESSION['role_modal'] == 'elixir' && ($_SESSION['customerno'] == 2 || $_SESSION['customerno'] == 116 || $_SESSION['customerno'] == 132)) {
            $page = 'modules/rtddashboard/viewport.php';
            $_SESSION['switch_to'] = 0;
        }if ($_SESSION['use_tracking'] == 1 && $_SESSION['role_modal'] == 'elixir') {
            $page = 'modules/vehicle/vehicle.php?id=2';
            $_SESSION['switch_to'] = 0;
        } elseif ($_SESSION['use_tracking'] == 1) {
            $page = 'modules/realtimedata/realtimedata.php';
            $_SESSION['switch_to'] = 0;
        } else {
            if ($_SESSION['use_delivery'] == 1) {
                $_SESSION['switch_to'] = 2;
                $page = 'modules/delivery/delivery.php';
            } elseif ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
                $_SESSION['switch_to'] = 1;
                switch_session(2);
                $page = "modules/transactions/transaction.php?id=2";
                //$page = "";
            } elseif ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '0') {
                $_SESSION['switch_to'] = 1;
                switch_session(2);
                //$page = 'modules/vehicle/vehicle.php?id=2';
                $page = '';
            } elseif ($_SESSION['use_warehouse'] == 1) {
                $_SESSION['switch_to'] = 3;
                $page = 'modules/warehouse/warehouse.php';
            } elseif ($_SESSION['use_routing'] == '1') {
                $_SESSION['switch_to'] = 4;
                $page = 'modules/routing/assign.php?id=3';
            } elseif ($_SESSION['use_mobility'] == '1') {
                $_SESSION['switch_to'] = 5;
                $page = 'modules/mobility/mobility.php';
            } elseif ($_SESSION['use_secondary_sales'] == '1') {
                $_SESSION['switch_to'] = 6;
                $page = 'modules/sales/sales.php?pg=dashboard';
            } elseif ($_SESSION['use_pickup'] == '1') {
                $_SESSION['switch_to'] = 7;
                $page = 'modules/pickup/pick.php?id=3';
            } elseif ($_SESSION['use_pickup'] == '1') {
                $_SESSION['switch_to'] = 10;
                $page = 'modules/pickupwow/pick.php?id=3';
            } elseif ($_SESSION['use_sales'] == '1') {
                $_SESSION['switch_to'] = 8;
                $page = 'modules/salesengage/salesengage.php?pg=view-order';
            } else {
                $page = 'modules/realtimedata/realtimedata.php';
                $_SESSION['switch_to'] = 0;
            }
        }
    }

    return $page;
}

/* used on switching module */

function redirect_page($id) {
    //for tracking
    switch_session(1);
    $page = '../../modules/realtimedata/realtimedata.php';
    //$page = '../../modules/reports/reports.php?id=50&action=getToggle';
    //for maintenance
    if ($id == 1) {
        if ($_SESSION['roleid'] == '2' || $_SESSION['roleid'] == '3' || $_SESSION['roleid'] == '4') {
            $page = '../../modules/approvals/approvals.php?id=2';
        }
        if ($_SESSION['roleid'] == '1' || $_SESSION['roleid'] == '10' || $_SESSION['roleid'] == '5') {
            $page = '../../modules/transactions/transaction.php?id=2';
        } else {
            $page = '../../modules/transactions/transaction.php?id=2';
        }
        switch_session(2);
    } elseif ($id == 2) {
        $page = '../../modules/delivery/delivery.php'; //for delivery
    } elseif ($id == 3) {
        $page = '../../modules/warehouse/warehouse.php'; // '../../modules/realtimedata/warehouse.php';//for warehouse
    } elseif ($id == 4) {
        $page = '../../modules/routing/assign.php?id=3'; //for routing
    } elseif ($id == 5) {
        $page = '../../modules/mobility/mobility.php'; //for mobility
    } elseif ($id == 6) {
        $page = '../../modules/sales/sales.php?pg=catview'; //for sales
    } elseif ($id == 7) {
        $page = '../../modules/pickup/pick.php?id=3'; //for pickup
    } elseif ($id == 10) {
        $page = '../../modules/pickupwow/pick.php?id=3'; //for pickupwow
    } elseif ($id == 8) {
        if ($_SESSION['roleid'] == '12') {
            $page = '../../modules/salesengage/salesengage.php?pg=view-order'; //for Sales engage
        }
        $page = '../../modules/salesengage/salesengage.php?pg=view-order'; //for Sales engage
    } elseif ($id == 12) {
        $page = '../../modules/secondarytms/sectms.php?pg=view-shipment'; //for secondary tms
    }
    return $page;
}

function switch_session($moduleid) {
    $usermanager = new UserManager();
    $userrole = $usermanager->get_rolemapping($_SESSION['customerno'], $_SESSION['userid'], $moduleid);
    if (isset($userrole) && !empty($userrole)) {
        $_SESSION['roleid'] = $userrole[0]['roleid'];
        $_SESSION['role_modal'] = $userrole[0]['role'];
        $_SESSION['Session_UserRole'] = $userrole[0]['role'];
        $_SESSION['sessionauth'] = $userrole[0]['role'];
        $_SESSION['role'] = $userrole[0]['role'];
    }
}

function pullcontract() {
    $user_groups = get_user_groups($_SESSION['customerno'], $_SESSION['userid'], 'csv');
    $devmanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devmanager->get_all_devices($user_groups);
    return $devices;
}

function getfilteredcontract_details($nation_id, $state_id, $district_id, $city_id, $group_id) {
    $devmanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devmanager->get_filtered_devices($nation_id, $state_id, $district_id, $city_id, $group_id);
    return $devices;
}

function getgroupname_hierarchy_contract($groupid) {
    if ($groupid == 0) {
        return array('groupname' => 'N/A', 'hierarchy' => 'N/A', 'group_code' => 'N/A');
    } else {
        $groupmanager = new GroupManager($_SESSION['customerno']);
        $group = $groupmanager->getgroupname($groupid);
        if (isset($group->groupname)) {
            $group->hierarchy = isset($group->hierarchy) ? $group->hierarchy : 'N/A';
            return array('groupname' => $group->groupname, 'hierarchy' => $group->hierarchy, 'group_code' => $group->code);
        } else {
            return array('groupname' => "N/A", 'hierarchy' => 'N/A', 'group_code' => 'N/A');
        }
    }
}

function getuser($uid = false) {
    $userid = ($uid) ? $uid : $_SESSION["userid"];
    $usermanager = new UserManager();
    $user = $usermanager->get_user($_SESSION['customerno'], $userid);
    return $user;
}

function get_stoppage_alerts($uid = false) {
    $userid = ($uid) ? $uid : $_SESSION["userid"];
    $usermanager = new UserManager();
    $user = $usermanager->get_stoppage_alerts($_SESSION['customerno'], $userid);
    return $user;
}

function getcustomfields() {
    $usermanager = new UserManager();
    $custom = $usermanager->get_customfields($_SESSION['customerno']);
    return $custom;
}

function getcustombyid_all($id) {
    $id = GetSafeValueString($id, 'long');
    $usermanager = new UserManager();
    $custom = $usermanager->get_custom_byid($id, $_SESSION['customerno']);
    return $custom;
}

function getcustom() {
    $usermanager = new UserManager();
    $custom = $usermanager->get_custom($_SESSION['customerno']);
    return $custom;
}

function modifyTalerts($form) {
    $user = new VOUser();
    $usermanager = new UserManager();
    $user->customerno = $_SESSION['customerno'];
    $user->userid = $_SESSION['userid'];
    $user->aci_sms = 1;
    if (!isset($form['acisms'])) {
        $user->aci_sms = 0;
    }
    $user->aci_email = 1;
    if (!isset($form['aciemail'])) {
        $user->aci_email = 0;
    }
    $user->aci_time = GetSafeValueString($form['aciselect'], 'long');
    $usermanager->modifyTalerts($user, $_SESSION['userid']);
}

function modifyEalerts($form) {
    $user = new VOUser();
    $usermanager = new UserManager();
    $user->mess_sms = 1;
    if (!isset($form["messsms"])) {
        $user->mess_sms = 0;
    }
    $user->mess_email = 1;
    if (!isset($form["messemail"])) {
        $user->mess_email = 0;
    }
    $user->speed_sms = 1;
    if (!isset($form["speedsms"])) {
        $user->speed_sms = 0;
    }
    $user->speed_email = 1;
    if (!isset($form["speedemail"])) {
        $user->speed_email = 0;
    }
    $user->power_sms = 1;
    if (!isset($form["powersms"])) {
        $user->power_sms = 0;
    }
    $user->power_email = 1;
    if (!isset($form["poweremail"])) {
        $user->power_email = 0;
    }
    $user->tamper_sms = 1;
    if (!isset($form["tampersms"])) {
        $user->tamper_sms = 0;
    }
    $user->tamper_email = 1;
    if (!isset($form["tamperemail"])) {
        $user->tamper_email = 0;
    }
    $user->chk_sms = 1;
    if (!isset($form["chksms"])) {
        $user->chk_sms = 0;
    }
    $user->chk_email = 1;
    if (!isset($form["chkemail"])) {
        $user->chk_email = 0;
    }
    $user->ac_sms = 1;
    if (!isset($form["acsms"])) {
        $user->ac_sms = 0;
    }
    $user->ac_email = 1;
    if (!isset($form["acemail"])) {
        $user->ac_email = 0;
    }
    $user->ignition_sms = 1;
    if (!isset($form["igsms"])) {
        $user->ignition_sms = 0;
    }
    $user->ignition_email = 1;
    if (!isset($form["igemail"])) {
        $user->ignition_email = 0;
    }
    $user->temp_sms = 1;
    if (!isset($form["tempsms"])) {
        $user->temp_sms = 0;
    }
    $user->temp_email = 1;
    if (!isset($form["tempemail"])) {
        $user->temp_email = 0;
    }
    $user->userid = $_SESSION['userid'];
    $user->customerno = $_SESSION['customerno'];
    $usermanager->modifyalerts($user, $_SESSION['userid']);
}

function modify_alerts_ajax() {
    $usermanager = new UserManager();
    $user->realname = $name;
    $user->role = $role;
    $user->userid = $_SESSION['userid'];
    $user->email = $email;
    $user->phone = $phoneno;
    $user->customerno = $_SESSION['customerno'];
    $usermanager->SaveUser($user, $_SESSION['userid']);
    echo 'ok';
}

/* dt: 3rd oct 14, for total km tracking */

function get_km_tracked($data1) {
    $usermanager = new UserManager;
    $getKm = $usermanager->get_total_km_tracked($data1);
    return $getKm;
}

function get_alerts_generated() {
    $usermanager = new UserManager;
    $getAlert = $usermanager->get_total_alerts();
    return $getAlert;
}

/* dt: 21st oct 14, ak added below func, to for sending forgot password email */

function send_forgot_pass_mail($to, $subject, $content) {
    include_once "../cron/class.phpmailer.php";
    $mail = new PHPMailer();
    $mail->IsMail();
    $mail->AddAddress($to);
    $mail->From = "noreply@elixiatech.com";
    $mail->FromName = "Elixia Speed";
    $mail->Sender = "noreply@elixiatech.com";
    $mail->Subject = $subject;
    $mail->Body = $content;
    $mail->IsHtml(true);
    if ($mail->Send()) {
        // message sending failed
        return true;
    }
    return false;
}

/* dt: 21st oct 14, ak edited below func, to clear bug */

function generatepass($username) {
    $user = GetSafeValueString($username, 'string');
    $usermanager = new UserManager;
    $getuser = $usermanager->check_if_user_exists($user);

    if (!empty($getuser)) {
        if ($getuser['userfound'] == 1 && $getuser['otp'] != '-1') {
            if (!filter_var($user, FILTER_VALIDATE_EMAIL)) {
                //if not username is not email id then get email id
                $usrdata = $usermanager->getuserdata_from_username($username);
                $to = $usrdata['email'];
            } else {
                $to = $user;
            }
            $subject = 'Elixia Password Reset';
            $content = "This email is in response to a request to reset the password for you.
            <table border='1' cellpadding='0' cellspacing='0'><col width='130'><col width='80'><tr><td>Username</td><td>"
                . $user . "</td></tr><tr><td>Otp Password</td><td>" . $getuser['otp'] . "</td></tr></table><br/>
            Please Note Your Otp Password is Valid for " . $getuser['otpvalidupto'] . "<br/>
            Please contact an elixir if you have any questions or comments.<br/>
            -The Elixia Team";
            if (send_forgot_pass_mail($to, $subject, $content)) {
                echo 'ok';
                exit;
            } else {
                echo 'unableemail';
                exit;
            }
        } elseif ($getuser['userfound'] == 1 && $getuser['otp'] == '-1') {
            if (!filter_var($user, FILTER_VALIDATE_EMAIL)) {
                //if not username is not email id then get email id
                $usrdata = $usermanager->getuserdata_from_username($username);
                $to = $usrdata['email'];
            } else {
                $to = $user;
            }
            $subject = 'Elixia Password Reset';
            $content = "This email is in response to a request to reset the password for you.
            You have exceed the number of attempts for reset password. <br/>
            Now,your Account has been locked. <br/>
            So please try again after 24 Hr.<br/>
            Please contact an elixir if you have any questions or comments.<br/>
            -The Elixia Team";

            if (send_forgot_pass_mail($to, $subject, $content)) {
                echo 'ok';
                exit;
            } else {
                echo 'unableemail';
                exit;
            }
        } else {
            echo 'noemail';
            exit;
        }
    } else {
        echo 'notok';
        exit;
    }
}

function modifyuser($name, $email, $phoneno, $role) {
    $usermanager = new UserManager();
    $user['realname'] = $name;
    $user['email'] = $email;
    $user['phone'] = $phoneno;
    //$user->role = $role;
    $user['customerno'] = $_SESSION['customerno'];
    $usermanager->UpdateAccountInfo($user, $_SESSION['userid']);
    echo 'ok';
}

function modifyuser_modal($email, $phoneno) {
    $usermanager = new UserManager();
    $user = new stdClass();
    $user->userid = $_SESSION['userid'];
    $user->email = $email;
    $user->phone = $phoneno;
    $user->customerno = $_SESSION['customerno'];
    $usermanager->UpdateEmailPass($user, $_SESSION['userid']);
    echo 'ok';
}

function chkandchangepasswd($oldpwd, $newpasswd) {
    $usermanager = new UserManager();
    $user = $usermanager->get_user($_SESSION['customerno'], $_SESSION['userid']);
    $oldpwd = GetSafeValueString($oldpwd, "string");
    $oldpwd = sha1($oldpwd);
    if ($oldpwd == $user->password) {
        if ($newpasswd == "") {
            echo ("newempty");
        } else {
            $username = $_SESSION['username'];
            $newpass = GetSafeValueString($newpasswd, "string");
            $usermanager->setnewpassword($newpass, $username);
            echo ("ok");
        }
    } else {
        echo ("notok");
    }
}

function chkandchangepasswd_modal($newpasswd) {
    $usermanager = new UserManager();
    $user = $usermanager->get_user($_SESSION['customerno'], $_SESSION['userid']);
    if ($newpasswd == "") {
        echo ("newempty");
    } else {
        $username = $_SESSION['username'];
        $newpass = GetSafeValueString($newpasswd, "string");
        $usermanager->setnewpassword($newpass, $username);
        echo ("ok");
    }
}

function checklogin($username, $password, $isDealer = null) {
    $username = trim(GetSafeValueString($username, "string"));
    $password = trim(GetSafeValueString($password, "string"));
    $arrResult = array('status' => 'notok', 'userkey' => '', 'authType' => '');
    $um = new UserManager();
    $user = $um->check_for_login($username, $password);
    if (is_array($user) && $user['forgot_user'] == '1') {
        $arrResult = array(
            'status' => 'forgot_pass'
            , 'userkey' => $user['forgot_userkey']
            , 'authType' => $user['userauthtype']
            , 'otpSent' => ''
        );
    } else {
        $initday = -1;
        // Check contractvalidity
        if (isset($user) && $user->use_tracking == 1) {
            $dm = new DeviceManager($user->customerno);
            $devices = $dm->checkforvalidity();
            if (isset($devices)) {
                foreach ($devices as $thisdevice) {
                    $todaydate = date("Y-m-d", strtotime($thisdevice->today));
                    $days = checkvalidity($thisdevice->expirydate, $todaydate);
                    if ($days >= 0) {
                        $initday = $days;
                    }
                }
            }
        }
        $use = (isset($user) &&
            ($user->use_pickup == 1 || $user->use_maintenance == 1 || $user->use_delivery == 1 || $user->use_routing == 1 || $user->use_mobility == 1 || $user->use_secondary_sales == 1 || $user->use_sales == 1 || $user->use_warehouse == 1)
        );
        if ($initday >= 0 || ($use)) {
            $otpSent = "No";
            if ($user->multiauth == 1) {
                $status = multiauthRequest($user->id, $user->customerno);
                if ($status == 'smsSent') {
                    $otpSent = "Yes";
                } elseif ($status == 'phoneNotAvailable') {
                    $otpSent = "phoneNotAvailable";
                } elseif ($status == 'limitExceeded') {
                    $otpSent = "limitExceeded";
                } elseif ($status == 'userLocked') {
                    $otpSent = "userLocked";
                } elseif ($status == 'noSmsBalance') {
                    $otpSent = "noSmsBalance";
                }
            }
            $arrResult = array(
                'status' => 'ok'
                , 'userkey' => $user->userkey
                , 'authType' => $user->multiauth
                , 'otpSent' => $otpSent
            );
        }
    }
    echo json_encode($arrResult);
}

function checkvalidity($expirydate, $currentdate) {
    //date_default_timezone_set("Asia/Calcutta");
    $realtime = strtotime($currentdate);
    $expirytime = strtotime($expirydate);
    $diff = $expirytime - $realtime;
    return $diff;
}

function login($username, $password, $authType, $otp) {
    /* if($_POST['rememberme']=='on')
    setrawcookie('elixia', $_POST['username'],time()+60*60*24);
    else
     */
    $arrUserLogin = array('status' => 'UserNotLogged', 'userDetails' => '');
    $username = GetSafeValueString($username, "string");
    $password = GetSafeValueString($password, "string");
    $um = new UserManager();
    $user = $um->authenticate($username, $password);
    if (isset($user)) {
        $isTripuser = $um->getTripuserDetail($user->id);
        $user->isTripuser = $isTripuser;
        $arrUserLogin = array('status' => 'UserLogged', 'userDetails' => $user);
        if ($authType == 1 && $otp != '') {
            $validationStatus = $um->validateOtpFor2WayAuthentication($user->id, $otp);
            if (isset($validationStatus) && $validationStatus == 1) {
                $arrUserLogin = array('status' => 'UserLogged', 'userDetails' => $user);
            } else {
                $arrUserLogin = array('status' => 'otpNotValid', 'userDetails' => $user);
            }
        } elseif ($authType == 1 && $otp == '') {
            $arrUserLogin = array('status' => 'otpNotValid', 'userDetails' => $user);
        }
    }
    @session_start();
    return $arrUserLogin;
}

function account_switch($userid) {
    /* if($_POST['rememberme']=='on')
    setrawcookie('elixia', $_POST['username'],time()+60*60*24);
    else
     */
    $logout = new Log();
    if ($logout->createlog($_SESSION['customerno'], "Logged Out", $_SESSION['userid'])) {
        session_unset();
        session_destroy();
    }
    $userid = GetSafeValueString($userid, "string");
    $um = new UserManager();
    $user = $um->authenticate_accountswitch($userid);
    $um->updatevisit($user->customerno, $user->id);
    @session_start();
    SetSession($user);
}

function elixiacode_login($ecodeid) {
    $today = date('Y-m-d h:i:s');
    $ecodeid = GetSafeValueString(trim($ecodeid), "string");
    $um = new UserManager();
    $elixiacode = $um->authenticate_elixiacode($ecodeid);
    //print_r($elixiacode);die();
    if (!isset($elixiacode)) {
        header("Location: ../../index.php");
    } else {
        if (strtotime($today) > strtotime($elixiacode->enddate)) {
            header("Location: ../../index.php");
        } else {
            $_SESSION['e_id'] = $elixiacode->id;
            $_SESSION['ecodeid'] = $elixiacode->ecodeid;
            $_SESSION['customerno'] = $elixiacode->customerno;
            $_SESSION['startdate'] = $elixiacode->startdate;
            $_SESSION['enddate'] = $elixiacode->enddate;
            $_SESSION['menuoption'] = $elixiacode->menuoption;
            $_SESSION['temp_sensors'] = $elixiacode->temp_sensors;
            $_SESSION['portable'] = $elixiacode->use_portable;
            $_SESSION['switch_to'] = 0;
            $_SESSION['use_maintenance'] = $elixiacode->use_maintenance;
            $_SESSION['use_geolocation'] = $elixiacode->use_geolocation;
            $_SESSION['use_tracking'] = $elixiacode->use_tracking;
            $_SESSION['use_hierarchy'] = $elixiacode->use_hierarchy;
            $_SESSION['use_humidity'] = $elixiacode->use_humidity;
            $_SESSION['customername'] = $elixiacode->customername;
            $_SESSION['customercompany'] = $elixiacode->customercompany;
            $_SESSION['use_ac_sensor'] = $elixiacode->use_ac_sensor;
            $_SESSION['use_genset_sensor'] = $elixiacode->use_genset_sensor;
            $_SESSION['use_fuel_sensor'] = $elixiacode->use_fuel_sensor;
            $_SESSION['use_extradigital'] = $elixiacode->use_extradigital;
            $_SESSION['use_checkpoint_settings'] = $elixiacode->use_checkpoint_settings;
            $_SESSION['use_warehouse'] = $elixiacode->use_warehouse;
            $_SESSION['days'] = $elixiacode->days;
            $_SESSION['immobiliser'] = $elixiacode->use_immobiliser;
            $_SESSION['buzzer'] = $elixiacode->use_buzzer;
            $_SESSION['digitalcon'] = 'Genset';
            $_SESSION['refreshtime'] = 1;
            //$_SESSION["temp_sensors"] = $elixiacode->temp_sensors;
            if (isset($elixiacode->days) && $elixiacode->days > 0) {
                $date = date('Y-m-d');
                $days = $elixiacode->days;
                $calculateddate = date('Y-m-d', strtotime($date . ' - ' . $days . ' days'));
                $_SESSION['codecalculateddate'] = $calculateddate;
            } else {
                $_SESSION['codecalculateddate'] = NULL;
            }
            //$_SESSION['username'] = $elixiacode->ecodeid;
        }
    }
}

function updatesession($setsession) {
    $_SESSION['chk_maintenance'] = $setsession;
}

function removesession($setsession) {
    $_SESSION['chk_maintenance'] = '';
}

function updatestateid($state) {
    $um = new UserManager();
    if (isset($_POST['state'])) {
        $_SESSION['stateid'] = $state;
    }
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $stateid3 = $_SESSION['stateid'];
    $update = $um->changestate($stateid3, $userid, $customerno);
}

function updategroupid($groupid) {
    $groupid = GetSafeValueString($groupid, "string");
    $um = new UserManager();
    $update = $um->updategroup($_SESSION["customerno"], $_SESSION["userid"], $groupid);
    $user = $um->get_user($_SESSION["customerno"], $_SESSION["userid"]);
    $groupmanager = new GroupManager($user->customerno);
    $groupdata = $groupmanager->getmappedgroup($_SESSION["userid"]);
    if ($user->visits == 0) {
        $um->updatevisit($user->customerno, $user->id);
        $user->visits = 1;
    }
    // Setting session variables
    $_SESSION['Session_UserRole'] = $user->role;
    $_SESSION["role_modal"] = $user->role;
    $_SESSION["realname"] = $user->realname;
    $_SESSION["customerno"] = $user->customerno;
    $_SESSION["use_maintenance"] = $user->use_maintenance;
    $_SESSION["userid"] = $user->id;
    $_SESSION["visits_modal"] = $user->visits;
    $_SESSION["username"] = $user->username;
    $_SESSION['Session_User'] = $user;
    $_SESSION["sessionauth"] = $user->role;
    $_SESSION["roleid"] = $user->roleid;
    $_SESSION["heirarchy_id"] = $user->heirarchy_id;
    if (isset($groupdata)) {
        $_SESSION["groupid"] = $user->groups;
    } else {
        $_SESSION["groupid"] = 0;
    }
    $_SESSION["digitalcon"] = $um->store_custom_name($user->customerno, 'Digital', 1);
}

function updatesel_status($sel_status) {
    $selstatus = GetSafeValueString($sel_status, "string");
    //$um = new UserManager();
    //$update = $um->updatesel_status( $_SESSION["customerno"], $_SESSION["userid"], $selstatus);
    $_SESSION["veh_status"] = $selstatus;
}

function updatesel_status_onchange($statusid) {
    $selstatus = GetSafeValueString($statusid, "string");
    //$um = new UserManager();
    //$update = $um->updatesel_status( $_SESSION["customerno"], $_SESSION["userid"], $selstatus);
    $_SESSION["veh_status"] = $selstatus;
}

function updatesel_stoppage($sel_stoppage) {
    $selstoppage = GetSafeValueString($sel_stoppage, "string");
    //$um = new UserManager();
    //$update = $um->updatesel_stoppage( $_SESSION["customerno"], $_SESSION["userid"], $selstoppage);
    $_SESSION["veh_stoppage"] = $selstoppage;
}

function getVehcileno($vehicleid) {
    $um = new UserManager($_SESSION['customerno']);
    $vehicleno = $um->getVehicleName($vehicleid);
    echo $vehicleno;
}

function getVehcilenoByCustomer($vehicleid, $customerno) {
    $vm = new VehicleManager($customerno);
    $vehicleno = $vm->getVehicleNameByCustomer($vehicleid, $customerno);
    echo $vehicleno;
}

function contract_details($customerno) {
    $um = new DeviceManager($customerno);
    $details = $um->pullcontractdetails($customerno);
    return $details;
}

function id_details($customerno) {
    $um = new DeviceManager($customerno);
    $details = $um->pullids($customerno);
    return $details;
}

function set_ids($ids, $customerno) {
    $um = new DeviceManager($customerno);
    $details = $um->setids($ids, $customerno);
    return $details;
}

function invoice_details($customerno) {
    $um = new DeviceManager($customerno);
    $details = $um->pullinvoicedetails($customerno);
    return $details;
}

function invoicefiltered_details($nation_id, $state_id, $district_id, $city_id, $group_id) {
    $um = new DeviceManager($_SESSION['customerno']);
    $details = $um->pullfiltered_invoicedetails($nation_id, $state_id, $district_id, $city_id, $group_id);
    return $details;
}

function service_details($vehicleid, $customerno) {
    $um = new DeviceManager($customerno);
    $details = $um->pullservicedetails($vehicleid, $customerno);
    return $details;
}

function getnations($userid) {
    $nationmanager = new NationManager($_SESSION['customerno']);
    $nations = $nationmanager->get_all_nations($userid);
    return $nations;
}

function getstates($userid) {
    $statemanager = new StateManager($_SESSION['customerno']);
    $states = $statemanager->get_all_states($userid);
    return $states;
}

function getdistricts($userid) {
    $districtmanager = new DistrictManager($_SESSION['customerno']);
    $districts = $districtmanager->get_all_districts($userid);
    return $districts;
}

function getcities() {
    $CityManager = new CityManager($_SESSION['customerno']);
    $cities = $CityManager->get_all_cities($_SESSION['userid']);
    return $cities;
}

function getgroups() {
    $GroupManager = new GroupManager($_SESSION['customerno']);
    $groups = $GroupManager->getallgroups();
    return $groups;
}

function update_newpwd_byforgotpass($newpasswd, $userkey) {
    $newpasswd = trim(GetSafeValueString($newpasswd, "string"));
    $newpasswd = sha1($newpasswd);
    $userkey = trim(GetSafeValueString($userkey, "string"));
    $um = new UserManager();
    $user = $um->update_newpwd($newpasswd, $userkey);
    return $user;
}

function loginwithuserkey($userkey) {
    $userkey = GetSafeValueString($userkey, "string");
    $um = new UserManager();
    $user = $um->authenticate_userkey($userkey);
    /*
    Changes Made By : Pratik Raut
    Date : 23-09-2019
    Change :  return response
     */
    if (!empty($user)) {
        $um->updatevisit($user->customerno, $user->id);
        $um->updateLogin($user->customerno, $user->id);

        // Setting session variables
        SetSession($user);
        return true;
    } else {
        return false;
    }

    /* changes ends here*/
}

function loginwithuserkey_map($userkey) {
    $userkey = GetSafeValueString($userkey, "string");
    $um = new UserManager();
    $user = $um->authenticate_userkey_map($userkey);
    $um->updatevisit($user->customerno, $user->id);
    //$um->updateLogin($user->customerno, $user->id);

    // Setting session variables
    SetSession($user);
}

function getloginhistoryreport($sdate, $edate, $stime, $etime) {
    if (!isset($stime) && !isset($etime)) {
        $stime = "00:00:00";
        $etime = "23:59:59";
    }
    $totaldays = gendays($sdate, $edate);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];
    $um = new UserManager();
    $loginhistdata = $um->getloginhistorydata($sdate, $stime, $edate, $etime, $_SESSION['customerno']);
    if ($loginhistdata != NULL && count($loginhistdata) > 0) {
        $finalreport = create_loginhistoryhtml($loginhistdata);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data Found</td></tr></tbody></table></div>";
    }
    echo $finalreport;
}

function getloginhistorypdf($customerno, $sdate, $edate, $stime = NULL, $etime = NULL, $vgroupname = null) {
    $finalreport = '';
    if (!isset($stime) && !isset($etime)) {
        $stime = "00:00:00";
        $etime = "23:59:59";
    }
    $totaldays = gendays($sdate, $edate);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];
    $um = new UserManager();
    $loginhistdata = $um->getloginhistorydata($sdate, $stime, $edate, $etime, $_SESSION['customerno']);
    if ($loginhistdata != NULL && count($loginhistdata) > 0) {
        $title = 'Login History';
        $subTitle = array(
            "Start Date: $sdate {$stime}",
            "End Date: $edate {$etime}"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }

        $finalreport = pdf_header($title, $subTitle, NULL, NULL);
        $finalreport .= "<table id='search_table_2' align='center' style='width: auto; font-size:13px;
        text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_loginhistorypdf($loginhistdata, $customerno);
    }
    echo $finalreport;
}

function getloginhistorycsv($customerno, $sdate, $edate, $stime = NULL, $etime = NULL, $vgroupname = null) {
    $finalreport = '';
    if (!isset($stime) && !isset($etime)) {
        $stime = "00:00:00";
        $etime = "23:59:59";
    }
    $totaldays = gendays($sdate, $edate);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];
    $um = new UserManager();
    $loginhistdata = $um->getloginhistorydata($sdate, $stime, $edate, $etime, $_SESSION['customerno']);
    if ($loginhistdata != NULL && count($loginhistdata) > 0) {
        $title = 'Login History';
        $subTitle = array(
            "Start Date: $sdate {$stime}",
            "End Date: $edate {$etime}"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = excel_header($title, $subTitle, NULL, NULL);
        $finalreport .= "<table id='search_table_2' align='center' style='width: auto; font-size:13px;
        text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_loginhistorypdf($loginhistdata, $customerno);
    }
    echo $finalreport;
}

function create_loginhistorypdf($loginhistdata, $customerno) {
    $display = '';
    if (isset($loginhistdata)) {
        $i = 1;
        $display .= "</tbody></table>";
        $display .= "<table id='search_table_2' align='center' style='width: auto; font-size:14px; text-align:center;border-collapse:collapse;'>
                <tbody>";
        $display .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
        $display .= "<td>Sr.No</td>";
        $display .= "<td>Real Name</td>";
        $display .= "<td>User Name</td>";
        $display .= "<td>Role</td>";
        $display .= "<td>Login with</td>";
        $display .= "<td>Datetime</td>";
        $display .= "</tr>";
        foreach ($loginhistdata as $row) {
            $date = $row['timestamp'];
            $timeval = "";
            switch ($customerno) {
                case 97:
                    $timeval = date("d-M-Y ,g:i:s a", strtotime($date) - 12600);
                    break;
                default:
                    $timeval = date("d-M-Y ,g:i:s a", strtotime($date));
                    break;
            }
            $display .= "<tr>";
            $display .= "<td>" . $i . "</td>";
            $display .= "<td>" . $row['realname'] . "</td>";
            $display .= "<td>" . $row['username'] . "</td>";
            $display .= "<td>" . $row['role'] . "</td>";
            $display .= "<td>" . $row['machine'] . "</td>";
            $display .= "<td>" . $timeval . "</td>";
            $display .= "</tr>";
            $i++;
        }
    }
    $display .= '</tbody></table>';
    return $display;
}

function create_loginhistoryhtml($loginhistdata) {
    $display = "";
    if (isset($loginhistdata)) {
        $i = 1;
        foreach ($loginhistdata as $row) {
            $date = $row['timestamp'];
            $timeval = "";
            switch ($_SESSION['customerno']) {
                case 97:
                    $timeval = date("d-M-Y ,g:i:s a", strtotime($date) - 12600);
                    break;
                default:
                    $timeval = date("d-M-Y ,g:i:s a", strtotime($date));
                    break;
            }
            $display .= "<tr>";
            $display .= "<td>" . $i . "</td>";
            $display .= "<td>" . $row['realname'] . "</td>";
            $display .= "<td>" . $row['username'] . "</td>";
            $display .= "<td>" . $row['role'] . "</td>";
            $display .= "<td>" . $row['machine'] . "</td>";
            $display .= "<td>" . $row['page_name'] . "</td>";
            $display .= "<td>" . $timeval . "</td>";
            $display .= "</tr>";
            $i++;
        }
    }
    $display .= '</table>';
    return $display;
}

//function gendays($STdate, $EDdate) {
//    $TOTALDAYS = array();
//    $STdate = date("Y-m-d", strtotime($STdate));
//    $EDdate = date("Y-m-d", strtotime($EDdate));
//    while (strtotime($STdate) <= strtotime($EDdate)) {
//        $TOTALDAYS[] = $STdate;
//        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
//    }
//    return $TOTALDAYS;
//}

function GetAllPhones($rolename) {
    $um = new UserManager();
    $datas = $um->getAllROle($rolename);
    $display = "";
    if (!empty($datas)) {
        $display = "<table border='1'><tr><th>Sr No.</th><th>UserName</th><th>Mobile No</th></tr>";
        $x = 1;
        foreach ($datas as $data) {
            $display .= "<tr><td>" . $x++ . "</td><td>" . $data->realname . "</td><td>" . $data->phone . "</td></tr>";
        }
        $display .= "</table>";
    } else {
        $display .= "No Numbers Available";
    }
    echo $display;
}

function check2WayAuthUser($userid) {
    $userAuth = array();
    $userManager = new UserManager();
    $userAuth = $userManager->check2WayAuthUser($userid);
    return $userAuth;
}

function multiauthRequest($userid, $customerno) {
    $status = "notok";
    $usermanager = new UserManager;
    $cm = new CustomerManager();
    $smsStatus = new SmsStatus();
    $getuser = $usermanager->check2WayAuthUser($userid);
    if (!empty($getuser)) {
        $response = '';
        $message = "OTP: " . $getuser['otp'] . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($getuser['otpvalidupto']));
        $smsStatus->customerno = $customerno;
        $smsStatus->userid = $userid;
        $smsStatus->vehicleid = 0;
        $smsStatus->mobileno = $getuser['userphone'];
        $smsStatus->message = $message;
        $smsStatus->cqid = 0;
        $smsstat = $cm->getSMSStatus($smsStatus);
        if ($smsstat == 0) {
            if ($getuser['userphone'] != '' && $getuser['otp'] != '-1') {
                $isSMSSent = sendSMSUtil($getuser['userphone'], $message, $response);
                if ($isSMSSent == 1) {
                    $cm->sentSmsPostProcess($customerno, $getuser['userphone'], $message, $response, $isSMSSent, $userid, 0, 1);
                    $status = "smsSent";
                } else {
                    $status = "smsNotSent";
                }
            } elseif ($getuser['userphone'] != '' && $getuser['otp'] == '-1') {
                $status = "limitExceeded";
            } else {
                $status = "phoneNotAvailable";
            }
        } elseif ($smsstat == -2) {
            $status = "userLocked";
        } elseif ($smsstat == -3) {
            $status = "noSmsBalance";
        }
    }
    return $status;
}

function getInstallDeviceDetails($objRequest) {
    $arrDevicesData = array();
    $tableHeader = '';
    $dataTableHeader = '';
    $tableRows = '';
    //prettyPrint($objRequest);
    $objCustomerManager = new CustomerManager();
    $objDeviceManager = new DeviceManager($objRequest->customerNo);
    $customer_details = $objCustomerManager->getcustomerdetail_byid($objRequest->customerNo);

    if ($objRequest->reportId == 1) {
        $title = 'Install Device Report';
    } elseif ($objRequest->reportId == 2) {
        $title = 'Capex Payment Report';
    } elseif ($objRequest->reportId == 3) {
        $title = 'Inventory Report';
    } elseif ($objRequest->reportId == 4) {
        $title = 'Inactive Vehicle Report';
    } elseif ($objRequest->reportId == 5) {
        $title = 'Device In / Out Warranty';
    } elseif ($objRequest->reportId == 6) {
        $title = 'Monthly Device activity Report';
    }
    $subTitle = array();
    if ($objRequest->reportType == speedConstants::REPORT_PDF) {
        $tableHeader .= pdf_header($title, $subTitle, $customer_details);
    } elseif ($objRequest->reportType == speedConstants::REPORT_XLS) {
        $tableHeader .= excel_header($title, $subTitle, $customer_details);
    } else {
        $tableHeader .= table_header($title, $subTitle);
    }
    $dataTableHeader .= processInstallDeviceHeader($objRequest->reportId);
    $arrDevicesData['tableHeader'] = $tableHeader;
    $arrDevicesData['dataTableHeader'] = $dataTableHeader;
    $arrDevicesData['tableRows'] = "<tr ><td colspan='100%' style='text-align:center;'>File Not Exists</td></tr>";
    $arrDeviceList = array();
    if (isset($objRequest->deviceList)) {
        foreach ($objRequest->deviceList as $device) {
            $poDate = '';
            if ($device->podate != '0000-00-00' && $device->podate != '1970-01-01') {
                $poDate = date("d-M-Y", strtotime($device->podate));
            }
            $installDate = '';
            if ($device->installdate != '0000-00-00') {
                $installDate = date("d-M-Y", strtotime($device->installdate));
            }
            $tatPeriod = '';
            if ($poDate != '' && $installDate != '') {
                $tatPeriod = date_SDiff($installDate, $poDate);
            }
            $warrantyEndDate = '';
            if ($device->warranty != '0000-00-00') {
                $warrantyEndDate = date("d-M-Y", strtotime($device->warranty));
            } elseif ($installDate != '') {
                $warrantyEndDate = date("d-M-Y", strtotime($installDate . '+365 days'));
            }

            $installationAddress = "NA";
            if (($objRequest->reportId == 1 || $objRequest->reportId == 2 || $objRequest->reportId == 5) && $device->installlat != 0 && $device->installlng != 0) {
                $objGeoCoder = new GeoCoder($objRequest->customerNo);
                $installationAddress = $objGeoCoder->get_location_bylatlong($device->installlat, $device->installlng);
            }

            /* Check For Vehicle Current Status */
            $status = 'In Use';
            $ServerIST = new DateTime();
            $ServerIST->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated);
            if ($lastupdated < $ServerIST) {
                $status = "Not In Use";
            }

            $deviceAddress = "NA";

            $activeCount = 0;
            $notActiveCount = 0;
            $totalWorkDays = 0;

            if ($objRequest->reportId == 6) {
                /* Vehicle Current Location */
                if ($device->devicelat != 0 && $device->devicelong != 0) {
                    $objGeoCoder = new GeoCoder($objRequest->customerNo);
                    $deviceAddress = $objGeoCoder->get_location_bylatlong($device->devicelat, $device->devicelong);
                }

                /* Calculate Tracking Days */
                $STdate = new DateTime('first day of this month');
                $EDdate = new DateTime();
                $totaldays = gendays($STdate->format(speedConstants::DATE_Ymd), $EDdate->format(speedConstants::DATE_Ymd));
                $totalWorkDays = count($totaldays);
                foreach ($totaldays as $day) {
                    $date = date('Y-m-d', strtotime($day));
                    $location = "../../customer/" . $customer_details->customerno . "/unitno/" . $device->unitno . "/sqlite/$date.sqlite";
                    if (file_exists($location)) {
                        $activeCount += 1;
                    } else {
                        $notActiveCount += 1;
                    }
                }
            }

            $objReport = new stdClass();
            $objReport->groupName = $device->groupname;
            $objReport->groupCode = $device->code;
            $objReport->regionName = $device->city;
            $objReport->zoneName = $device->district;
            $objReport->vehicleNo = $device->vehicleno;
            $objReport->unitNo = $device->unitno;
            $objReport->simcardNo = $device->phone;
            $objReport->poNumber = $device->pono;
            $objReport->poDate = $poDate;
            $objReport->installDate = $installDate;
            $objReport->installationAddress = $installationAddress;
            $objReport->deviceAddress = $deviceAddress;
            $objReport->status = $status;
            $objReport->tatPeriod = $tatPeriod;
            $objReport->warrantyStartDate = $installDate;
            $objReport->warrantyEndDate = $warrantyEndDate;
            $objReport->totalWorkDays = $totalWorkDays;
            $objReport->activeCount = $activeCount;
            $objReport->notActiveCount = $notActiveCount;
            if ($objRequest->reportId == 2) {
                $objBill = new stdClass();
                $objBill->customerNo = $objRequest->customerNo;
                $objBill->deviceInvoiceNo = $device->device_invoiceno;
                $billDetails = $objDeviceManager->getInvoiceBillDetails($objBill);
                $objReport->billNumber = $device->device_invoiceno;
                $objReport->billAmount = '';
                $objReport->billDate = '';
                $objReport->billSubmissionDate = '';
                $objReport->billPaymentDate = '';
                if (isset($billDetails) && !empty($billDetails)) {
                    $objReport->billNumber = $device->device_invoiceno;
                    $objReport->billAmount = $billDetails[0]->invoiceAmount;
                    $objReport->billDate = $billDetails[0]->invoiceDate;
                    $objReport->billSubmissionDate = $billDetails[0]->invoiceDate;
                    $objReport->billPaymentDate = $billDetails[0]->paymentDate;
                }
            }
            if ($objRequest->reportId == 3 || $objRequest->reportId == 4) {
                $objBill = new stdClass();
                $objBill->unitid = $device->uid;
                $objBill->customerno = $objRequest->customerNo;
                $objBill->statusid = 5;
                $billDetails = getDeviceTransHistory($objBill);

                $objReport->installationType = $billDetails['installationType'];
                $objReport->remark = $billDetails['remark'];
                $objReport->transferDate = $billDetails['transferredDate'];
                $objReport->transferHistory = $billDetails['transferHistory'];
                if ($billDetails['transferHistory'] != '') {
                    $objReport->transferHistory = implode('<br/>', $billDetails['transferHistory']);
                }
            }

            $arrDeviceList[] = $objReport;
        }
    }
    if (isset($arrDeviceList) && !empty($arrDeviceList)) {
        $tableRows .= processInstallDeviceRows($arrDeviceList, $objRequest->reportId);
        $arrDevicesData['tableRows'] = $tableRows;
    }
    return $arrDevicesData;
}

function processInstallDeviceHeader($reportId) {
    $tableHeader = "";
    $tableHeader .= "<tr>";
    $tableHeader .= "<th>Sr.No</th>";
    $tableHeader .= "<th>Branch_Name</th>";
    $tableHeader .= "<th>Branch_Code</th>";
    $tableHeader .= "<th>Region_Name</th>";
    $tableHeader .= "<th>Zone_Name</th>";
    $tableHeader .= "<th>Vehicle_Number</th>";

    if ($reportId == 1 || $reportId == 2 || $reportId == 5) {
        $tableHeader .= "<th>PO_Number</th>";
        $tableHeader .= "<th>PO_Date</th>";
    }

    if ($reportId == 1) {
        $tableHeader .= "<th>PO_Installation_Date</th>";
        $tableHeader .= "<th>Installation Address</th>";
        $tableHeader .= "<th>Request_Fulfillment/TAT Period</th>";
    }
    if ($reportId == 2) {
        $tableHeader .= "<th>Installation Address</th>";
        $tableHeader .= "<th>Installation Date</th>";
        $tableHeader .= "<th>Bill Number</th>";
        $tableHeader .= "<th>Bill Amount</th>";
        $tableHeader .= "<th>Bill Date</th>";
        $tableHeader .= "<th>Bill Submission Date</th>";
        $tableHeader .= "<th>Bill Payment Date</th>";
    }
    if ($reportId == 3) {
        $tableHeader .= "<th>Installation Type</th>";
        $tableHeader .= "<th>Vehicle Status</th>";
        $tableHeader .= "<th>Device Number</th>";
        $tableHeader .= "<th>Mobile Number</th>";
        $tableHeader .= "<th>Remarks</th>";
    }

    if ($reportId == 4) {
        $tableHeader .= "<th>Vehicle Status</th>";
        $tableHeader .= "<th>Device Number</th>";
        $tableHeader .= "<th>Mobile Number</th>";
        $tableHeader .= "<th>Installation Date</th>";
        $tableHeader .= "<th>Transferred Date</th>";
        $tableHeader .= "<th>Status</th>";
        $tableHeader .= "<th>History</th>";
        $tableHeader .= "<th>Remarks</th>";
    }

    if ($reportId == 5) {
        $tableHeader .= "<th>Installation Address</th>";
        $tableHeader .= "<th>Warranty Start Date</th>";
        $tableHeader .= "<th>Warranty END Date</th>";
    }

    if ($reportId == 6) {
        $tableHeader .= "<th>Vehicle Address</th>";
        $tableHeader .= "<th>Vehicle Status</th>";
        $tableHeader .= "<th>Device Active/Inactive</th>";
    }

    $tableHeader .= "</tr>";
    return $tableHeader;
}

function processInstallDeviceRows($deviceData, $reportId) {
    $tableRows = "";
    $srNo = 1;
    foreach ($deviceData as $device) {
        $tableRows .= "<tr>";
        $tableRows .= "<td>" . $srNo++ . "</td>";
        $tableRows .= "<td>" . $device->groupName . "</td>";
        $tableRows .= "<td>" . $device->groupCode . "</td>";
        $tableRows .= "<td>" . $device->regionName . "</td>";
        $tableRows .= "<td>" . $device->zoneName . "</td>";
        $tableRows .= "<td>" . $device->vehicleNo . "</td>";
        if ($reportId == 1 || $reportId == 2 || $reportId == 5) {
            $tableRows .= "<td>" . $device->poNumber . "</td>";
            $tableRows .= "<td>" . $device->poDate . "</td>";
        }

        if ($reportId == 1) {
            $tableRows .= "<td>" . $device->installDate . "</td>";
            $tableRows .= "<td>" . $device->installationAddress . "</td>";
            $tableRows .= "<td>" . $device->tatPeriod . "</td>";
        }
        if ($reportId == 2) {
            $tableRows .= "<td>" . $device->installationAddress . "</td>";
            $tableRows .= "<td>" . $device->installDate . "</td>";
            $tableRows .= "<td>" . $device->billNumber . "</td>";
            $tableRows .= "<td>" . $device->billAmount . "</td>";
            $tableRows .= "<td>" . $device->billDate . "</td>";
            $tableRows .= "<td>" . $device->billSubmissionDate . "</td>";
            $tableRows .= "<td>" . $device->billPaymentDate . "</td>";
        }

        if ($reportId == 3) {
            $tableRows .= "<td>" . $device->installationType . "</td>";
            $tableRows .= "<td>" . $device->status . "</td>";
            $tableRows .= "<td>" . $device->unitNo . "</td>";
            $tableRows .= "<td>" . $device->simcardNo . "</td>";
            $tableRows .= "<td>" . $device->remark . "</td>";
        }
        if ($reportId == 4) {
            $tableRows .= "<td>" . $device->status . "</td>";
            $tableRows .= "<td>" . $device->unitNo . "</td>";
            $tableRows .= "<td>" . $device->simcardNo . "</td>";
            $tableRows .= "<td>" . $device->installDate . "</td>";
            $tableRows .= "<td>" . $device->transferDate . "</td>";
            $tableRows .= "<td></td>";
            $tableRows .= "<td>" . $device->transferHistory . "</td>";
            $tableRows .= "<td>" . $device->remark . "</td>";
        }
        if ($reportId == 5) {
            $tableRows .= "<td>" . $device->installationAddress . "</td>";
            $tableRows .= "<td>" . $device->warrantyStartDate . "</td>";
            $tableRows .= "<td>" . $device->warrantyEndDate . "</td>";
        }
        if ($reportId == 6) {
            $tableRows .= "<td>" . $device->deviceAddress . "</td>";
            $tableRows .= "<td>" . $device->status . "</td>";
            $tableRows .= "<td>" . $device->activeCount . "/" . $device->notActiveCount . "</td>";
        }

        $tableRows .= "</tr>";
    }
    return $tableRows;
}

function getDeviceTransHistory($objDevice) {
    $history = array();
    $transferHistory = array();
    $history['installationType'] = 'New';
    $history['remark'] = '';
    $history['transferredDate'] = '';
    $history['transferHistory'] = '';
    $objDeviceManager = new DeviceManager($objDevice->customerno);
    $deviceHistory = $objDeviceManager->getDeviceTransHistory($objDevice);
    if (isset($deviceHistory) && !empty($deviceHistory)) {
        $firstRecord = reset($deviceHistory);
        $lastRecord = end($deviceHistory);
        $recordCount = count($deviceHistory);
        if ($recordCount > 1) {
            $history['installationType'] = 'Transferred';
            if ($lastRecord->trans_time != '0000-00-00 00:00:00') {
                $history['transferredDate'] = date('d-m-Y', strtotime($lastRecord->trans_time));
            }
            foreach ($deviceHistory as $device) {
                if ($device->trans_time != $firstRecord->trans_time && $device->trans_time != $lastRecord->trans_time) {
                    $transferHistory[] = date('d-M-Y', strtotime($device->trans_time));
                }
            }
        }
        //prettyPrint($transferHistory);
        $history['transferHistory'] = $transferHistory;
        $history['remark'] = $lastRecord->comments;
    }
    return $history;
}

function getRestoreId($objChat) {
    $restoreId = 0;
    $objUserManager = new UserManager();
    $restoreId = $objUserManager->getRestoreId($objChat);
    return $restoreId;
}

function setRestoreId($objChat) {
    $objUserManager = new UserManager();
    $objUserManager->setRestoreId($objChat);
}

function delete_adv_temp_range($vehicleid) {
    $usermanager = new UserManager();
    $result = $usermanager->delete_adv_temp_range($vehicleid);
    return $result;
}

function add_adv_temp($data) {
    $form = new stdClass();
    $form->temp_sensors = GetSafeValueString($data['temp_sensors'], 'long');
    $form->vehicleid = GetSafeValueString($data['vehicle_ids'], 'long');
    $form->userid = GetSafeValueString($data['userids'], 'long');
    $form->customerno = GetSafeValueString($data['customernos'], 'long');
    $form->sms = new stdClass();
    $form->email = new stdClass();
    for ($i = 0; $i < $form->temp_sensors; $i++) {
        $temp_min_sms = 'temp' . ($i + 1) . '_min_sms';
        $temp_max_sms = 'temp' . ($i + 1) . '_max_sms';
        $temp_min_email = 'temp' . ($i + 1) . '_min_email';
        $temp_max_email = 'temp' . ($i + 1) . '_max_email';
        $form->sms->$temp_min_sms = GetSafeValueString($data[$temp_min_sms], 'string');
        $form->sms->$temp_max_sms = GetSafeValueString($data[$temp_max_sms], 'string');
        $form->email->$temp_min_email = GetSafeValueString($data[$temp_min_email], 'string');
        $form->email->$temp_max_email = GetSafeValueString($data[$temp_max_email], 'string');
    }
    if ($form->vehicleid > 0) {
        $usermanager = new UserManager();
        $result = $usermanager->add_adv_temp($form);
        return $result;
    } else {
        return '-1';
    }
}

function check_temp_range($data) {
    $form = new stdClass();
    $form->vehicleid = GetSafeValueString($data['vehicle_ids'], 'long');
    $form->nomen = GetSafeValueString($data['nomen'], 'long');
    $form->customerno = GetSafeValueString($data['customernos'], 'long');

    $temp_min_sms = 'temp' . $form->nomen . '_min_sms';
    $temp_max_sms = 'temp' . $form->nomen . '_max_sms';
    $temp_min_email = 'temp' . $form->nomen . '_min_email';
    $temp_max_email = 'temp' . $form->nomen . '_max_email';
    $form->$temp_min_sms = GetSafeValueString($data[$temp_min_sms], 'long');
    $form->$temp_max_sms = GetSafeValueString($data[$temp_max_sms], 'long');
    $form->$temp_min_email = GetSafeValueString($data[$temp_min_email], 'long');
    $form->$temp_max_email = GetSafeValueString($data[$temp_max_email], 'long');

    if ($form->vehicleid > 0) {
        $usermanager = new UserManager();
        $result = $usermanager->check_temp_range($form);
        return $result;
    } else {
        return '-1';
    }
}

function get_edit_advance_temp($vehicleid, $userid) {
    $usermanager = new UserManager();
    $result = $usermanager->get_edit_advance_temp($vehicleid, $userid);
    return $result;
}

function getRtdDashboardHeaders() {
    $arrHeaders = array();
    $arrHeaders[] = 'SrNo';
    $arrHeaders[] = 'Image';
    $arrHeaders[] = 'Action';

    $arrHeaders[] = 'Last Updated';
    if (isset($_SESSION['groupid']) && $_SESSION['groupid'] == 0) {
        $arrHeaders[] = 'Group';
    }
    $arrHeaders[] = 'Status';
    $arrHeaders[] = 'Vehicle No';
    $arrHeaders[] = 'Driver';
    if (isset($_SESSION['Session_UserRole']) && $_SESSION['Session_UserRole'] == 'elixir') {
        $arrHeaders[] = 'Unit';
    }
    $arrHeaders[] = 'Location';
    $arrHeaders[] = 'Checkpoint';
    $arrHeaders[] = 'Route';
    $arrHeaders[] = 'Speed(Km/hr)';
    $arrHeaders[] = 'Distance(In Kms)';
    if (isset($_SESSION['use_loading']) && $_SESSION['use_loading'] == 1) {
        $arrHeaders[] = 'Load';
    }
    if (isset($_SESSION['use_ac_sensor']) && $_SESSION['use_ac_sensor'] == 1) {
        $arrHeaders[] = $_SESSION["digitalcon"];
    }
    if (isset($_SESSION['use_genset_sensor']) && $_SESSION['use_genset_sensor'] == 1) {
        $arrHeaders[] = $_SESSION["digitalcon"];
    }
    if (isset($_SESSION['use_door_sensor']) && $_SESSION['use_door_sensor'] == 1) {
        $arrHeaders[] = 'Door';
    }
    if ($_SESSION['temp_sensors'] == 1) {
        $arrHeaders[] = 'Temperature';
    } elseif ($_SESSION['temp_sensors'] == 2) {
        $arrHeaders[] = $_SESSION['Temperature 1'];
        $arrHeaders[] = $_SESSION['Temperature 2'];
    } elseif ($_SESSION['temp_sensors'] == 3) {
        $arrHeaders[] = $_SESSION['Temperature 1'];
        $arrHeaders[] = $_SESSION['Temperature 2'];
        $arrHeaders[] = $_SESSION['Temperature 3'];
    } elseif ($_SESSION['temp_sensors'] == 4) {
        $arrHeaders[] = $_SESSION['Temperature 1'];
        $arrHeaders[] = $_SESSION['Temperature 2'];
        $arrHeaders[] = $_SESSION['Temperature 3'];
        $arrHeaders[] = $_SESSION['Temperature 4'];
    }
    if ($_SESSION['use_extradigital'] == 1) {
        $arrHeaders[] = 'Genset 1';
        $arrHeaders[] = 'Genset 2';
    }
    if ($_SESSION['use_humidity'] == 1) {
        $arrHeaders[] = 'Humidity';
    }
    $arrHeaders[] = 'View';
    return $arrHeaders;
}

function loginwitheliciacode($userkey) {
    elixiacode_login($userkey);
}

function getConsigneeId($userId) {
    $userManagerObject = new UserManager();
    return $userManagerObject->getConsigneeId($userId)[0];
}

?>