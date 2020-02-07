<?php

if (!isset($RELATIVE_PATH_DOTS) || $RELATIVE_PATH_DOTS == "") {
    $RELATIVE_PATH_DOTS = "../../";
}


if (!class_exists('UserManager'))
    require_once $RELATIVE_PATH_DOTS . 'lib\bo\UserManager.php';

if (!function_exists('loginwithuserkey')) {
    function loginwithuserkey($userkey, $param = '')
    {
        $userkey = GetSafeValueString($userkey, "string");
        $um = new UserManager();
        $user = $um->authenticate_userkey($userkey, true);



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
        } else
            return false;
        /* changes ends here*/
    }
}

if (!function_exists('SetSession')) {
    function SetSession($user)
    {
        // Setting session variables
        $um = new UserManager();
        $_SESSION['multiauth'] = $user->multiauth;
        $_SESSION['Session_UserRole'] = $user->role;
        $_SESSION['userkey'] = $user->userkey;
        // Added By Pratik Raut for ERP Login
        if (!empty($user->erpUserToken)) {
            $_SESSION['erpUserToken'] = $user->erpUserToken;
        }
        // Added By Pratik Raut for ERP Login
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
}

if (!function_exists('getRtdDashboardHeaders')) {
    function getRtdDashboardHeaders()
    {
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
}

if (!function_exists('getConsigneeId')) {
    function getConsigneeId($userId)
    {
        $userManagerObject = new UserManager();
        return $userManagerObject->getConsigneeId($userId)[0];
    }
}
