<?php
if (!isset($RELATIVE_PATH_DOTS) || $RELATIVE_PATH_DOTS == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
require_once $RELATIVE_PATH_DOTS . 'config.inc.php';
define('SP_GET_ROLE_MAPPING', 'get_role_mapping');
class UserManager {
    private $_databaseManager = null;
    private $_customerManager = null;
    public function __construct() {
        // Constructor
        if ($this->_databaseManager == null) {
            $this->_databaseManager = new DatabaseManager();
        }
    }

    public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

    public function authenticate($username, $password) {
        $authenticatedUser = null;
        $authenticationQuery = sprintf("SELECT `userid`, `customerno` , `username`, `visited`
          FROM `user` WHERE `username`='%s' AND `password`='%s' AND isdeleted=0 limit 1"
            , Validator::escapeCharacters($username)
            , Validator::escapeCharacters($password));
        $this->_databaseManager->executeQuery($authenticationQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $authenticatedUser = $this->get_user($row['customerno'], $row['userid']);
        }
        return $authenticatedUser;
    }

    public function check_for_login($username, $password) {
        $authenticatedUser = null;
        $pdo = $this->_databaseManager->CreatePDOConn();
        $today = date("Y-m-d H:i:s");
        $sp_params = "'" . $username . "'"
            . ",'" . $password . "'"
            . ",'" . $today . "'"
            . "," . '@usertype'
            . "," . '@userkeyparam'
            . "," . '@userauthtype';
        $QUERY = $this->PrepareSP('authenticate_for_login', $sp_params);
        $pdo->query($QUERY);
        $this->_databaseManager->ClosePDOConn($pdo);
        $OutQuery = "SELECT @usertype AS usertype, @userkeyparam AS userkeyparam, @userauthtype AS userauthtype";
        if ($result = $pdo->query($OutQuery)) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $usertype = $row['usertype'];
            $userkey = $row['userkeyparam'];
            $userauthtype = $row['userauthtype'];
        }
        $result2 = $pdo->query($QUERY)->fetch(PDO::FETCH_ASSOC);
        if (!empty($result2) && $usertype == 0) {
            $authenticatedUser = $this->get_user($result2['customerno'], $result2['userid']);
        } elseif (empty($result2) && $usertype == 1) {
            // check if user login with forgot password
            $authenticatedUser = array(
                'forgot_user' => 1,
                'forgot_userkey' => $userkey,
                'userauthtype' => $userauthtype
            );
        } else {
            $authenticatedUser = null;
        }
        return $authenticatedUser;
    }

    public function authenticate_accountswitch($userid) {
        $authenticatedUser = null;
        $authenticationQuery = sprintf("SELECT `userid`, `customerno` , `username`, `visited`,(Select groupman.isdeleted from groupman where groupman.userid = user.userid order by groupman.gmid desc limit 0,1) as grpdel
            FROM `user` WHERE userid=%d AND isdeleted=0 limit 1"
            , Validator::escapeCharacters($userid));
        $this->_databaseManager->executeQuery($authenticationQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            if ($row['grpdel'] == 1) {
                $authenticatedUser = 'grpdel';
            } else {
                $authenticatedUser = $this->get_user($row['customerno'], $row['userid']);
            }
            return $authenticatedUser;
        } else {
            return null;
        }
    }

    public function authenticate_elixiacode($ecodeid) {
        $code = null;
        $authenticationQuery = sprintf("SELECT customer.use_maintenance,customer.use_geolocation,customer.use_extradigital,customer.use_fuel_sensor,customer.use_genset_sensor,customer.use_ac_sensor,customer.customercompany,customer.customername,customer.use_humidity,customer.use_hierarchy,customer.use_tracking,elixiacode.id, elixiacode.customerno, customer.use_portable, customer.customerno, elixiacode.ecode, elixiacode.startdate, elixiacode.expirydate,elixiacode.days ,elixiacode.menuoption, customer.temp_sensors,customer.use_immobiliser,customer.use_buzzer,setting.use_checkpoint_settings,customer.use_warehouse From elixiacode
                                        INNER JOIN " . DB_PARENT . ".customer on customer.customerno = elixiacode.customerno
                                        LEFT  JOIN " . DB_PARENT . ".`setting` ON setting.customerno = customer.customerno
                                        WHERE elixiacode.ecode=%d AND elixiacode.isdeleted = 0 limit 1", Sanitise::Long($ecodeid));
        $this->_databaseManager->executeQuery($authenticationQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            if ($row) {
                $code = new VOElixiaCode();
                $code->id = $row['id'];
                $code->ecodeid = $row['ecode'];
                $code->customerno = $row['customerno'];
                $code->startdate = $row['startdate'];
                $code->enddate = $row['expirydate'];
                $code->menuoption = $row['menuoption'];
                $code->temp_sensors = $row['temp_sensors'];
                $code->use_portable = $row['use_portable'];
                $code->use_maintenance = $row['use_maintenance'];
                $code->use_geolocation = $row['use_geolocation'];
                $code->use_tracking = $row['use_tracking'];
                $code->use_hierarchy = $row['use_hierarchy'];
                $code->use_humidity = $row['use_humidity'];
                $code->customername = $row['customername'];
                $code->customercompany = $row['customercompany'];
                $code->use_ac_sensor = $row['use_ac_sensor'];
                $code->use_genset_sensor = $row['use_genset_sensor'];
                $code->use_fuel_sensor = $row['use_fuel_sensor'];
                $code->use_extradigital = $row['use_extradigital'];
                $code->use_checkpoint_settings = $row['use_checkpoint_settings'] == 1 ? 1 : 0;
                $code->use_immobiliser = $row['use_immobiliser'];
                $code->use_buzzer = $row['use_buzzer'];
                $code->use_warehouse = $row['use_warehouse'];
                $code->days = $row['days'];
                //$code->temp_sensors = $row['temp_sensors'];
            }
        }
        return $code;
    }

    public function get_heirarchy_id($customerno, $userid) {
        $user = null;
        $Query = sprintf("SELECT user.groupid,group.cityid,city.districtid,district.stateid,state.nationid FROM `user`
            INNER JOIN group ON group.groupid = user.groupid
            INNER JOIN city ON city.cityid = group.cityid
            INNER JOIN district ON district.districtid = city.districtid
            INNER JOIN state ON state.stateid = district.stateid
            where user.customerno=%d", Validator::escapeCharacters($customerno));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new VOUser();
                $user->groupid = $row['groupid'];
                $user->cityid = $row['cityid'];
                $user->districtid = $row['districtid'];
                $user->stateid = $row["stateid"];
                $user->nationid = $row['nationid'];
                return $user;
            }
        }
        return null;
    }

    public function get_user($customerid, $id) {
        $user = null;
        $userQuery = "  SELECT   u.userid
                                ,u.username
                                ,u.userkey
                                ,u.erpUserToken
                                ,u.realname
                                ,c.customercompany
                                ,u.role
                                ,u.roleid
                                ,u.email
                                ,u.password
                                ,u.phone
                                ,u.lastvisit
                                ,u.visited
                                ,u.mess_email
                                ,u.mess_sms
                                ,u.mess_telephone
                                ,u.mess_mobilenotification
                                ,u.speed_email
                                ,u.speed_sms
                                ,u.speed_telephone
                                ,u.speed_mobilenotification
                                ,u.power_email
                                ,u.power_sms
                                ,u.power_telephone
                                ,u.power_mobilenotification
                                ,u.tamper_email
                                ,u.tamper_sms
                                ,u.tamper_telephone
                                ,u.tamper_mobilenotification
                                ,u.chk_email
                                ,u.chk_sms
                                ,u.chk_telephone
                                ,u.chk_mobilenotification
                                ,u.ac_email
                                ,u.ac_sms
                                ,u.ac_telephone
                                ,u.ac_mobilenotification
                                ,u.ignition_email
                                ,u.ignition_sms
                                ,u.ignition_telephone
                                ,u.ignition_mobilenotification
                                ,u.aci_email
                                ,u.aci_sms
                                ,u.aci_telephone
                                ,u.aci_mobilenotification
                                ,c.aci_time
                                ,u.temp_email
                                ,u.temp_sms
                                ,u.temp_telephone
                                ,u.temp_mobilenotification
                                ,u.groupid
                                ,u.start_alert
                                ,u.stop_alert
                                ,u.heirarchy_id
                                ,u.fuel_alert_sms
                                ,u.fuel_alert_email
                                ,u.fuel_alert_telephone
                                ,u.fuel_alert_mobilenotification
                                ,u.fuel_alert_percentage
                                ,u.dailyemail
                                ,u.dailyemail_csv
                                ,u.harsh_break_sms
                                ,u.harsh_break_mail
                                ,u.harsh_break_telephone
                                ,u.harsh_break_mobilenotification
                                ,u.high_acce_sms
                                ,u.high_acce_mail
                                ,u.high_acce_telephone
                                ,u.high_acce_mobilenotification
                                ,u.sharp_turn_sms
                                ,u.sharp_turn_mail
                                ,u.sharp_turn_telephone
                                ,u.sharp_turn_mobilenotification
                                ,u.towing_sms
                                ,u.towing_mail
                                ,u.towing_telephone
                                ,u.towing_mobilenotification
                                ,u.panic_sms
                                ,u.panic_email
                                ,u.panic_telephone
                                ,u.panic_mobilenotification
                                ,u.immob_sms
                                ,u.immob_email
                                ,u.immob_telephone
                                ,u.immob_mobilenotification
                                ,u.door_sms
                                ,u.door_email
                                ,u.door_telephone
                                ,u.door_mobilenotification
                                ,u.delivery_vehicleid
                                ,u.tempinterval
                                ,u.igninterval
                                ,u.speedinterval
                                ,u.acinterval
                                ,u.doorinterval
                                ,u.hum_sms
                                ,u.hum_email
                                ,u.hum_telephone
                                ,u.hum_mobilenotification
                                ,u.huminterval
                                ,u.vehicle_movement_alert
                                ,u.refreshtime
                                ,u.customerno
                                ,u.isdeleted
                                ,u.isTempInrangeAlertRequired
                                ,u.isAdvTempConfRange
                                ,c.use_tracking
                                ,c.maintenance_limit
                                ,c.use_maintenance
                                ,c.use_hierarchy
                                ,c.use_delivery
                                ,c.use_msgkey
                                ,c.temp_sensors
                                ,c.use_portable
                                ,c.use_buzzer
                                ,c.use_immobiliser
                                ,c.use_freeze
                                ,c.use_door_sensor
                                ,c.use_ac_sensor
                                ,c.use_genset_sensor
                                ,c.use_fuel_sensor
                                ,c.use_routing
                                ,c.use_advanced_alert
                                ,c.use_panic
                                ,c.use_mobility
                                ,c.use_secondary_sales
                                ,c.use_sales
                                ,c.use_tms
                                ,c.use_immobiliser
                                ,c.use_extradigital
                                ,c.use_warehouse
                                ,c.use_pickup
                                ,c.use_trip
                                ,c.use_humidity
                                ,c.timezone
                                ,c.use_toggle_switch
                                ,c.multiauth
                                ,c.use_elixiadoc
                                ,c.rel_manager
                                ,c.isoffline
                                ,c.use_geolocation
                                ,rm.manager_name
                                ,rm.manager_email
                                ,rm.manager_mobile
                                ,tri.`interval` as trinterval
                                ,veh.`interval` as vehinterval
                                ,setting.`use_location_summary`
                                ,setting.`use_vehicle_type`
                                ,setting.`use_checkpoint_settings`
                                ,timezone.timediff
                                ,timezone.timezone as timezonename
                                ,g.groupname
                        FROM    `user` u
                        INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = u.customerno
                        LEFT JOIN " . DB_PARENT . ".relationship_manager rm on rm.rid = c.rel_manager
                        LEFT OUTER JOIN " . DB_PARENT . ".`tempreportinterval` as tri on tri.userid = u.userid
                        LEFT OUTER JOIN " . DB_PARENT . ".`vehrepinterval` as veh on veh.userid = u.userid
                        LEFT OUTER JOIN " . DB_PARENT . ".`setting` ON setting.customerno = u.customerno
                        LEFT OUTER JOIN " . DB_PARENT . ".`timezone` ON c.timezone = timezone.tid
                        LEFT OUTER JOIN " . DB_PARENT . ".`group` g ON u.groupid = g.groupid AND u.groupid != 0 and g.customerno = u.customerno
                        WHERE   u.customerno=%d
                        AND     u.userid='%d'
                        AND     u.isdeleted=0
                        AND     c.isoffline = 0
                        LIMIT   1";
        $userDetailsQuery = sprintf($userQuery, Validator::escapeCharacters($customerid), Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($userDetailsQuery);
        if ($row = $this->_databaseManager->get_nextRow()) {
            $user = new VOUser();
            $user->id = $row['userid'];
            $user->username = $row['username'];
            $user->userkey = $row['userkey'];
            // Added by Pratik Raut for ERP login
            $user->erpUserToken = $row['erpUserToken'];
            // Added by Pratik Raut for ERP login
            $user->realname = $row['realname'];
            $user->customercompany = $row["customercompany"];
            $user->role = $row['role'];
            $user->roleid = $row['roleid'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $user->phone = $row['phone'];
            $user->lastvisit = $row['lastvisit'];
            $user->visits = $row['visited'];
            $user->customerno = $row['customerno'];
            $user->mess_email = $row['mess_email'];
            $user->mess_sms = $row['mess_sms'];
            $user->mess_telephone = $row['mess_telephone'];
            $user->mess_mobile = $row['mess_mobilenotification'];
            $user->speed_email = $row['speed_email'];
            $user->speed_sms = $row['speed_sms'];
            $user->speed_telephone = $row['speed_telephone'];
            $user->speed_mobile = $row['speed_mobilenotification'];
            $user->power_email = $row['power_email'];
            $user->power_sms = $row['power_sms'];
            $user->power_telephone = $row['power_telephone'];
            $user->power_mobile = $row['power_mobilenotification'];
            $user->tamper_email = $row['tamper_email'];
            $user->tamper_sms = $row['tamper_sms'];
            $user->tamper_telephone = $row['tamper_telephone'];
            $user->tamper_mobile = $row['tamper_mobilenotification'];
            $user->chk_email = $row['chk_email'];
            $user->chk_sms = $row['chk_sms'];
            $user->chk_telephone = $row['chk_telephone'];
            $user->chk_mobile = $row['chk_mobilenotification'];
            $user->ac_email = $row['ac_email'];
            $user->ac_sms = $row['ac_sms'];
            $user->ac_telephone = $row['ac_telephone'];
            $user->ac_mobile = $row['ac_mobilenotification'];
            $user->ignition_email = $row['ignition_email'];
            $user->ignition_sms = $row['ignition_sms'];
            $user->ignition_telephone = $row['ignition_telephone'];
            $user->ignition_mobile = $row['ignition_mobilenotification'];
            $user->aci_email = $row['aci_email'];
            $user->aci_sms = $row['aci_sms'];
            $user->aci_telephone = $row['aci_telephone'];
            $user->aci_mobile = $row['aci_mobilenotification'];
            $user->aci_time = $row['aci_time'];
            $user->temp_email = $row['temp_email'];
            $user->temp_sms = $row['temp_sms'];
            $user->temp_telephone = $row['temp_telephone'];
            $user->temp_mobile = $row['temp_mobilenotification'];
            $user->groups = $row['groupid'];
            $user->start_alert_time = $row['start_alert'];
            $user->stop_alert_time = $row['stop_alert'];
            $user->use_tracking = $row['use_tracking'];
            $user->maintenance_limit = $row['maintenance_limit'];
            $user->use_maintenance = $row['use_maintenance'];
            $user->use_heirarchy = $row['use_hierarchy'];
            $user->use_delivery = $row['use_delivery'];
            $user->loading = $row['use_msgkey'];
            $user->temp_sensors = $row['temp_sensors'];
            $user->portable = $row['use_portable'];
            $user->buzzer = $row['use_buzzer'];
            $user->immobiliser = $row['use_immobiliser'];
            $user->freeze = $row['use_freeze'];
            $user->heirarchy_id = $row['heirarchy_id'];
            $user->fuel_alert_sms = $row['fuel_alert_sms'];
            $user->fuel_alert_email = $row['fuel_alert_email'];
            $user->fuel_alert_telephone = $row['fuel_alert_telephone'];
            $user->fuel_alert_mobile = $row['fuel_alert_mobilenotification'];
            $user->fuel_alert_percentage = $row['fuel_alert_percentage'];
            $user->dailyemail = $row['dailyemail'];
            $user->dailyemail_csv = $row['dailyemail_csv'];
            $user->harsh_break_sms = $row['harsh_break_sms'];
            $user->harsh_break_mail = $row['harsh_break_mail'];
            $user->harsh_break_telephone = $row['harsh_break_telephone'];
            $user->harsh_break_mobile = $row['harsh_break_mobilenotification'];
            $user->high_acce_sms = $row['high_acce_sms'];
            $user->high_acce_mail = $row['high_acce_mail'];
            $user->high_acce_telephone = $row['high_acce_telephone'];
            $user->high_acce_mobile = $row['high_acce_mobilenotification'];
            $user->sharp_turn_sms = $row['sharp_turn_sms'];
            $user->sharp_turn_mail = $row['sharp_turn_mail'];
            $user->sharp_turn_telephone = $row['sharp_turn_telephone'];
            $user->sharp_turn_mobile = $row['sharp_turn_mobilenotification'];
            $user->towing_sms = $row['towing_sms'];
            $user->towing_mail = $row['towing_mail'];
            $user->towing_telephone = $row['towing_telephone'];
            $user->towing_mobile = $row['towing_mobilenotification'];
            $user->panic_sms = $row['panic_sms'];
            $user->panic_email = $row['panic_email'];
            $user->panic_telephone = $row['panic_telephone'];
            $user->panic_mobile = $row['panic_mobilenotification'];
            $user->immob_sms = $row['immob_sms'];
            $user->immob_email = $row['immob_email'];
            $user->immob_telephone = $row['immob_telephone'];
            $user->immob_mobile = $row['immob_mobilenotification'];
            $user->use_door_sensor = $row['use_door_sensor'];
            $user->use_ac_sensor = $row['use_ac_sensor'];
            $user->use_genset_sensor = $row['use_genset_sensor'];
            $user->use_fuel_sensor = $row['use_fuel_sensor'];
            $user->use_routing = $row['use_routing'];
            $user->door_sms = $row['door_sms'];
            $user->door_email = $row['door_email'];
            $user->door_telephone = $row['door_telephone'];
            $user->door_mobile = $row['door_mobilenotification'];
            $user->use_advanced_alert = $row['use_advanced_alert'];
            $user->use_panic = $row['use_panic'];
            $user->use_mobility = $row['use_mobility'];
            $user->use_secondary_sales = $row['use_secondary_sales'];
            $user->use_sales = $row['use_sales'];
            $user->use_tms = $row['use_tms'];
            $user->use_immobiliser = $row['use_immobiliser'];
            $user->delivery_vehicleid = $row['delivery_vehicleid'];
            $user->use_extradigital = $row['use_extradigital'];
            $user->use_warehouse = $row['use_warehouse'];
            $user->use_pickup = $row['use_pickup'];
            $user->use_trip = $row['use_trip'];
            $user->use_humidity = $row['use_humidity'];
            $user->timezone = $row['timezone'];
            $user->tempinterval = $row['tempinterval'];
            $user->igninterval = $row['igninterval'];
            $user->speedinterval = $row['speedinterval'];
            $user->acinterval = $row['acinterval'];
            $user->doorinterval = $row['doorinterval'];
            $user->use_toggle = $row['use_toggle_switch'];
            $user->hum_sms = $row['hum_sms'];
            $user->hum_email = $row['hum_email'];
            $user->hum_telephone = $row['hum_telephone'];
            $user->hum_mobile = $row['hum_mobilenotification'];
            $user->huminterval = $row['huminterval'];
            $user->vehicle_movement_alert = $row['vehicle_movement_alert'];
            $user->refreshtime = $row['refreshtime'];
            $user->multiauth = $row['multiauth'];
            $user->use_elixiadoc = $row['use_elixiadoc'];
            $user->rel_manager = $row['rel_manager'];
            $user->manager_name = $row['manager_name'];
            $user->manager_email = $row['manager_email'];
            $user->manager_mobile = $row['manager_mobile'];
            $user->use_location_summary = $row['use_location_summary'] == 1 ? 1 : 0;
            $user->use_vehicle_type = $row['use_vehicle_type'] == 1 ? 1 : 0;
            $user->use_checkpoint_settings = $row['use_checkpoint_settings'] == 1 ? 1 : 0;
            if (isset($row['trinterval'])) {
                $user->trinterval = $row['trinterval'];
            }
            if (isset($row['vehinterval'])) {
                $user->vehinterval = $row['vehinterval'];
            }
            if (isset($row['isTempInrangeAlertRequired'])) {
                $user->isTempInrangeAlertRequired = $row['isTempInrangeAlertRequired'];
            }
            if (isset($row['isAdvTempConfRange'])) {
                $user->isAdvTempConfRange = $row['isAdvTempConfRange'];
            }
            $user->timediff = $row['timediff'];
            $user->timezonename = $row['timezonename'];
            $user->groupname = $row['groupname'];
            $user->use_geolocation = $row['use_geolocation'];
            return $user;
        }
        return null;
    }

    public function get_rolemapping($customerid, $userid, $moduleid) {
        $arrResult = null;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $userid . "'"
                . ",'" . $customerid . "'"
                . ",'" . $moduleid . "'";
            $queryCallSP = "CALL " . SP_GET_ROLE_MAPPING . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            //Log
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        return $arrResult;
    }

    public function get_usertms($id, $role, $customerno) {
        $user = null;
        $userDetailsQuery = sprintf("SELECT * FROM " . DB_TMS . ".`tmsmapping` as t
            where t.customerno=%d AND t.isdeleted=0 AND t.userid='%d' and t.role='%s' LIMIT 1", Validator::escapeCharacters($customerno), Validator::escapeCharacters($id), Validator::escapeCharacters($role));
        $this->_databaseManager->executeQuery($userDetailsQuery);
        if ($row = $this->_databaseManager->get_nextRow()) {
            $user = new VOUser();
            $user->tmsrole = $row['role'];
            $user->tmsid = $row['tmsid'];
            return $user;
        }
        return null;
    }

    public function get_stoppage_alerts($customerno, $userid) {
        $user = null;
        $Query = sprintf("SELECT * FROM `stoppage_alerts`
            where customerno=%d AND userid='%d' AND isdeleted = 0 LIMIT 1", Validator::escapeCharacters($customerno), Validator::escapeCharacters($userid));
        $this->_databaseManager->executeQuery($Query);
        if ($row = $this->_databaseManager->get_nextRow()) {
            $user = new VOUser();
            $user->safcsms = $row['is_chk_sms'];
            $user->safcemail = $row['is_chk_email'];
            $user->safctelephone = $row['is_chk_telephone'];
            $user->safcmobile = $row['is_chk_mobilenotification'];
            $user->saftsms = $row['is_trans_sms'];
            $user->saftemail = $row['is_trans_email'];
            $user->safttelephone = $row['is_trans_telephone'];
            $user->saftmobile = $row['is_trans_mobilenotification'];
            $user->safcmin = $row['chkmins'];
            $user->saftmin = $row['transmins'];
            return $user;
        }
        return null;
    }

    public function get_heir_details($customerno, $userid, $roleid) {
        $user = null;
        if ($roleid == '8') {
            $Query = sprintf("SELECT state.stateid, city.cityid, district.districtid FROM `user`
                INNER JOIN `group` ON `group`.groupid = user.groupid
                INNER JOIN city ON city.cityid = `group`.cityid
                INNER JOIN district ON district.districtid = city.districtid
                INNER JOIN state ON state.stateid = district.stateid
                where user.customerno=%d AND user.userid = %d LIMIT 1", Validator::escapeCharacters($customerno), Validator::escapeCharacters($userid));
        } elseif ($roleid == '4') {
            $Query = sprintf("SELECT state.stateid, city.cityid, district.districtid FROM `user`
                INNER JOIN city ON city.cityid = user.heirarchy_id
                INNER JOIN district ON district.districtid = city.districtid
                INNER JOIN state ON state.stateid = district.stateid
                where user.customerno=%d AND user.userid = %d LIMIT 1", Validator::escapeCharacters($customerno), Validator::escapeCharacters($userid));
        } elseif ($roleid == '3') {
            $Query = sprintf("SELECT state.stateid, district.districtid FROM `user`
                INNER JOIN district ON district.districtid = user.heirarchy_id
                INNER JOIN state ON state.stateid = district.stateid
                where user.customerno=%d AND user.userid = %d LIMIT 1", Validator::escapeCharacters($customerno), Validator::escapeCharacters($userid));
        } elseif ($roleid == '2') {
            $Query = sprintf("SELECT state.stateid FROM `user`
                INNER JOIN state ON state.stateid = user.heirarchy_id
                where user.customerno=%d AND user.userid = %d LIMIT 1", Validator::escapeCharacters($customerno), Validator::escapeCharacters($userid));
        } else {
            $Query = sprintf("SELECT state.stateid FROM `user`
                INNER JOIN state ON state.stateid = user.heirarchy_id
                where user.customerno=%d AND user.userid = %d LIMIT 1", Validator::escapeCharacters($customerno), Validator::escapeCharacters($userid));
        }
        $this->_databaseManager->executeQuery($Query);
        if ($row = $this->_databaseManager->get_nextRow()) {
            $user = new VOUser();
            if ($roleid == '2' || $roleid == '3' || $roleid == '4' || $roleid == '8') {
                $user->stateid = $row['stateid'];
            }
            if ($roleid == "3" || $roleid == '4' || $roleid == '8') {
                $user->districtid = $row['districtid'];
            }
            if ($roleid == '4' || $roleid == '8') {
                $user->cityid = $row['cityid'];
            }
            return $user;
        }
        return null;
    }

    public function get_custom($customerid) {
        $custom = null;
        $Query = sprintf("SELECT * FROM `customfield` where customerno=%d LIMIT 1", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($Query);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $custom = new stdClass();
            $custom->id = $row['cfid'];
            $custom->name = $row['name'];
            $custom->customname = $row['customname'];
            $custom->usecustom = $row["usecustom"];
            $custom->customerno = $row['customerno'];
            $custom->custom_id = $row['custom_id'];
            return $custom;
        }
        return null;
    }

    public function get_customfields($customerid) {
        $customs = array();
        $Query = sprintf("SELECT * FROM " . DB_PARENT . ".`customtype`");
        $this->_databaseManager->executeQuery($Query);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $custom = new stdClass();
            $custom->id = $row['id'];
            $custom->name = $row['name'];
            $customs[] = $custom;
        }
        return $customs;
        return null;
    }

    public function get_custom_byid($id, $customerid) {
        $custom = '';
        $Query = sprintf("SELECT * FROM `customfield` where custom_id=%d AND customerno=%d and usecustom=1", Validator::escapeCharacters($id), Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $custom = new stdClass();
                $custom->id = $row['cfid'];
                $custom->name = $row['name'];
                $custom->customname = $row['customname'];
                $custom->usecustom = $row["usecustom"];
                $custom->customerno = $row['customerno'];
                return $custom;
            }
        }
        return $custom;
    }

    public function store_custom_name($customerid, $custom, $customid) {
        $Query = sprintf("SELECT * FROM `customfield` where custom_id=%d AND customerno=%d LIMIT 1", Validator::escapeCharacters($customid), Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['usecustom'] == '1') {
                    $custom = $row['customname'];
                } else {
                    $custom = $custom;
                }
                return $custom;
            }
        }
        return $custom;
    }

    public function timezone_name($custom, $timeid) {
        $Query = sprintf("SELECT * FROM " . DB_PARENT . ".`timezone` where tid=%d LIMIT 1", Validator::escapeCharacters($timeid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $timezone = $row['timezone'];
            }
            return $timezone;
        } else {
            $timezone = $custom;
            return $timezone;
        }
    }

    public function timezone_name_cron($custom, $customerno) {
        $Query = sprintf("SELECT customer.timezone as tz, timezone.timezone as zone from " . DB_PARENT . ".customer
            inner join " . DB_PARENT . ".timezone on customer.timezone = timezone.tid
            where customer.customerno=%d", Validator::escapeCharacters($customerno));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $timezone = $row['zone'];
            }
            return $timezone;
        } else {
            $timezone = $custom;
            return $timezone;
        }
    }

    public function get_groupid_byuser($customerno, $userid) {
        $users = array();
        $Query = 'SELECT `groupid` FROM `user` WHERE customerno=%d AND userid=%d AND isdeleted=0';
        $userGroupQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($userGroupQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new VOUser();
                $user->groups = $row["groupid"];
                $users[] = $user;
            }
            return $users;
        }
        return null;
    }

    public function get_groups_fromuser($customerno, $userid) {
        $groups = array();
        $Query = '  SELECT  groupman.`groupid`
                            , `group`.`groupname`
                    FROM    `groupman`
                    left join `group` on `group`.groupid = `groupman`.groupid
                    WHERE   groupman.customerno = %d
                    AND     groupman.userid = %d
                    AND     groupman.isdeleted = 0';
        $userGroupQuery = sprintf($Query, Sanitise::Long($customerno), Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($userGroupQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $group = new stdClass();
                $group->groupid = $row["groupid"];
                $group->groupname = $row["groupname"];
                $groups[] = $group;
            }
            return $groups;
        }
        $group = new stdClass();
        $group->groupid = 0;
        $group->groupname = "";
        $groups[] = $group;
        return $groups;
    }

    public function get_user_groups_arr($customerno, $userid) {
        $groups = array();
        $Query = 'SELECT `groupid` FROM `groupman` WHERE customerno=%d AND userid=%d AND isdeleted=0';
        $userGroupQuery = sprintf($Query, Sanitise::Long($customerno), Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($userGroupQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $groups[] = $row["groupid"];
            }
            return $groups;
        }
        return $groups;
    }

    public function getusersforcustomer($customerid) {
        $users = array();
        $usersQuery = sprintf("SELECT * FROM `user` where customerno=%d AND isdeleted=0", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["realname"] != "Elixir") {
                    $user = new VOUser();
                    $user->userid = $row['userid'];
                    $user->username = $row['username'];
                    $user->role = $row['role'];
                    $user->roleid = $row['roleid'];
                    $user->email = $row['email'];
                    $user->phone = $row['phone'];
                    $user->realname = $row['realname'];
                    $user->lastvisit = $row['lastvisit'];
                    $user->visits = $row['visited'];
                    $user->userkey = $row['userkey'];
                    $user->customerno = $row['customerno'];
                    $user->mess_email = $row["mess_email"];
                    $user->mess_sms = $row["mess_sms"];
                    $user->fuel_alert_percentage = $row["fuel_alert_percentage"];
                    $user->dailyemail = $row['dailyemail'];
                    $user->dailyemail_csv = $row['dailyemail_csv'];
                    $users[] = $user;
                }
            }
            return $users;
        }
        return null;
    }

    public function getuseremailsforcustomer($customerid, $groupid) {
        $users = array();
        $usersQuery = sprintf("SELECT distinct email FROM `user`
                           LEFT OUTER JOIN groupman on groupman.userid = user.userid
                           where user.customerno=%d AND user.email<> '' AND user.groupid IN(0,%d) AND user.isdeleted=0", Validator::escapeCharacters($customerid), Validator::escapeCharacters($groupid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["realname"] != "Elixir") {
                    $user = new VOUser();
                    $user->email = $row['email'];
                    $users[] = $user;
                }
            }
            return $users;
        }
        return null;
    }

    public function getuseremailsforcustomeradmin($customerid) {
        $users = array();
        $usersQuery = sprintf("SELECT distinct email, role, realname,user.vehicle_movement_alert, user.groupid FROM `user`
                           Inner JOIN groupman on groupman.userid <> user.userid
                           where user.customerno=%d AND user.email<> '' AND user.roleid IN (5,33) AND user.isdeleted=0", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["realname"] != "Elixir") {
                    $user = new VOUser();
                    $user->email = $row['email'];
                    $user->realname = $row['realname'];
                    $user->vehicle_movement_alert = $row['vehicle_movement_alert'];
                    $users[] = $user;
                }
            }
            return $users;
        }
        return null;
    }

    public function getusersforcustomerbygrp($customerid) {
        $thisgroupid = $_SESSION["groupid"];
        $users = array();
        $groupids = array();
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            if ($_SESSION["roleid"] == "2" || $_SESSION["roleid"] == "3" || $_SESSION["roleid"] == "4") {
                $Query = "SELECT *,SHA1(user.userkey) as userkey1,
                user.groupid as usergroupid,
                user.heirarchy_id,
                user.userid,
                user.customerno,
                groupman.isdeleted as grpdel,
                groupman.groupid as grpid,
                userCreated.realname as createdBy,
                user.createdOn,
                userUpdated.realname as updatedBy,
                user.updatedOn
                FROM `user`
                    LEFT OUTER JOIN groupman on groupman.userid = user.userid
                    LEFT OUTER JOIN `group` ON `group`.groupid = groupman.groupid
                    LEFT OUTER JOIN user userCreated ON userCreated.userid = user.userid
                    LEFT OUTER JOIN user userUpdated ON userUpdated.userid = user.userid ";
                if ($_SESSION["roleid"] == '2') {
                    $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
                LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid
                LEFT OUTER JOIN `state` ON `state`.stateid = district.stateid ";
                }
                if ($_SESSION["roleid"] == '3') {
                    $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
                LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid ";
                }
                if ($_SESSION["roleid"] == '4') {
                    $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid ";
                }
                $Query .= " WHERE ";
                if ($_SESSION['groupid'] != '0') {
                    $Query .= " groupman.groupid = %d AND ";
                }
                $Query .= " user.customerno=%d AND user.isdeleted=0 ";
                if ($_SESSION["roleid"] == "2") {
                    $Query .= " AND user.roleid IN (2,3,4,8)";
                } elseif ($_SESSION["roleid"] == "3") {
                    $Query .= " AND user.roleid IN (3,4,8)";
                } elseif ($_SESSION["roleid"] == "4") {
                    $Query .= " AND user.roleid IN (4,8)";
                }
                if ($_SESSION['groupid'] == '0') {
                    $Query .= " Group By user.username";
                }
                if ($_SESSION['groupid'] == '0') {
                    $usersQuery = sprintf($Query, $customerid);
                } else {
                    $usersQuery = sprintf($Query, $thisgroupid, $customerid);
                }
                $heir_query = "";
                if ($_SESSION['roleid'] == '2') {
                    $heir_query = sprintf(" AND state.stateid = %d ", $_SESSION['heirarchy_id']);
                }
                if ($_SESSION['roleid'] == '3') {
                    $heir_query = sprintf(" AND district.districtid = %d ", $_SESSION['heirarchy_id']);
                }
                if ($_SESSION['roleid'] == '4') {
                    $heir_query = sprintf(" AND city.cityid = %d ", $_SESSION['heirarchy_id']);
                }
                $usersQuery .= $heir_query;
            } else {
                $Query = "SELECT *,SHA1(user.userkey) as userkey1,user.groupid as usergroupid,user.userid,user.heirarchy_id, user.customerno,groupman.isdeleted as grpdel,groupman.groupid as grpid,
                userCreated.realname as createdBy,
                user.createdOn,
                userUpdated.realname as updatedBy,
                user.updatedOn
                FROM `user`
                    LEFT OUTER JOIN groupman on groupman.userid = user.userid
                    LEFT OUTER JOIN user userCreated ON userCreated.userid = user.userid
                    LEFT OUTER JOIN user userUpdated ON userUpdated.userid = user.userid
                    WHERE user.customerno=%d AND user.isdeleted=0";
                if ($_SESSION['groupid'] == 0) {
                    $Query .= " Group By user.username";
                    $usersQuery = sprintf($Query, $customerid);
                } else {
                    $Query .= " AND groupman.groupid = %d Group By user.username";
                    $usersQuery = sprintf($Query, $customerid, $thisgroupid);
                }
            }
        } else {
            $Query = "SELECT *,SHA1(user.userkey) as userkey1,user.groupid as usergroupid,user.heirarchy_id, user.userid,user.customerno,groupman.isdeleted as grpdel,groupman.groupid as grpid,
                userCreated.realname as createdBy,
                user.createdOn,
                userUpdated.realname as updatedBy,
                user.updatedOn
            FROM `user`
                LEFT OUTER JOIN groupman on groupman.userid = user.userid
                LEFT OUTER JOIN user userCreated ON userCreated.userid = user.userid
                LEFT OUTER JOIN user userUpdated ON userUpdated.userid = user.userid
                WHERE user.customerno=%d AND user.isdeleted=0 ";
            if ($_SESSION['groupid'] == 0) {
                $Query .= " Group By user.username";
            }
            $usersQuery = sprintf($Query, $customerid);
        }
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($_SESSION['groupid'] != 0 && $row["realname"] != "Elixir") {
                    if (($row["grpid"] == $_SESSION['groupid'] && $row["grpdel"] == 0) || ($row["grpid"] == $_SESSION['groupid'] && $row["grpdel"] == NULL) || ($row["groupid"] == $_SESSION['groupid'] && $row["grpdel"] == 0) || ($row["groupid"] == $_SESSION['groupid'] && $row["grpdel"] == NULL) || ($row["groupid"] == 0 && $row["grpdel"] == NULL) || ($row["groupid"] == 0 && $row["grpdel"] == 0) || ($row["groupid"] == -1 && ($row["roleid"] == 15 || $row["roleid"] == 16 || $row["roleid"] == 17))) {
                        $user = new VOUser();
                        $user->userid = $row['userid'];
                        $user->username = $row['username'];
                        $user->heirarchy_id = $row['heirarchy_id'];
                        $user->roleid = $row['roleid'];
                        if ($row['roleid'] == 1) {
                            $user->role = $_SESSION['master'];
                        } elseif ($row['roleid'] == 2) {
                            $user->role = $_SESSION['statehead'];
                        } elseif ($row['roleid'] == 3) {
                            $user->role = $_SESSION['districthead'];
                        } elseif ($row['roleid'] == 4) {
                            $user->role = $_SESSION['cityhead'];
                        } elseif ($row['roleid'] == 8) {
                            $user->role = $_SESSION['branchhead'];
                            $user->heirarchy_id = $row['usergroupid'];
                        } else {
                            $user->role = $row['role'];
                        }
                        $user->email = $row['email'];
                        $user->phone = $row['phone'];
                        $user->realname = $row['realname'];
                        $user->lastvisit = $row['lastvisit'];
                        $user->visits = $row['visited'];
                        $user->userkey = $row['userkey'];
                        $user->userkey1 = $row['userkey1'];
                        $user->customerno = $row['customerno'];
                        $user->mess_email = $row["mess_email"];
                        $user->mess_sms = $row["mess_sms"];
                        $user->dailyemail = $row['dailyemail'];
                        $user->dailyemail_csv = $row['dailyemail_csv'];
                        $user->updatedBy = $row['updatedBy'];
                        $user->updatedOn = isset($row['updatedOn']) ? date('d/m/Y H:i:s', strtotime($row['updatedOn'])) : '';
                        $user->createdBy = $row['createdBy'];
                        $user->createdOn = isset($row['createdOn']) ? date('d/m/Y H:i:s', strtotime($row['createdOn'])) : '';
                        $users[] = $user;
                    }
                } elseif ($row["realname"] != "Elixir") {
                    $user = new VOUser();
                    $user->heirarchy_id = $row['heirarchy_id'];
                    $user->userid = $row['userid'];
                    $user->username = $row['username'];
                    $user->roleid = $row['roleid'];
                    $user->userkey1 = $row['userkey1'];
                    if ($row['roleid'] == 1) {
                        $user->role = isset($_SESSION['master']) ? $_SESSION['master'] : '';
                    } elseif ($row['roleid'] == 2) {
                        $user->role = $_SESSION['statehead'];
                    } elseif ($row['roleid'] == 3) {
                        $user->role = $_SESSION['districthead'];
                    } elseif ($row['roleid'] == 4) {
                        $user->role = $_SESSION['cityhead'];
                    } elseif ($row['roleid'] == 8) {
                        $user->role = $_SESSION['branchhead'];
                        $user->heirarchy_id = $row['usergroupid'];
                    } else {
                        $user->role = $row['role'];
                    }
                    $user->email = $row['email'];
                    $user->phone = $row['phone'];
                    $user->realname = $row['realname'];
                    $user->lastvisit = $row['lastvisit'];
                    $user->visits = $row['visited'];
                    $user->userkey = $row['userkey'];
                    $user->customerno = $row['customerno'];
                    $user->mess_email = $row["mess_email"];
                    $user->mess_sms = $row["mess_sms"];
                    $user->updatedBy = $row['updatedBy'];
                    $user->updatedOn = isset($row['updatedOn']) ? date('d/m/Y H:i:s', strtotime($row['updatedOn'])) : '';
                    $user->createdBy = $row['createdBy'];
                    $user->createdOn = isset($row['createdOn']) ? date('d/m/Y H:i:s', strtotime($row['createdOn'])) : '';
                    $user->dailyemail = $row['dailyemail'];
                    $user->dailyemail_csv = $row['dailyemail_csv'];
                    $users[] = $user;
                }
            }
//            $users = convert_to_utf8($users);
            return $users;
        }
        return null;
    }

    public function getusersforcustomerbygrp_hierarchy($customerid, $groupid = null, $useMaintenance = null, $useHierarchy = null) {
        $groupid = isset($groupid) ? $groupid : $_SESSION["groupid"];
        $users = array();
        $groupids = array();
        $useMaintenance = isset($useMaintenance) ? $useMaintenance : $_SESSION['use_maintenance'];
        $useHierarchy = isset($useHierarchy) ? $useHierarchy : $_SESSION['use_hierarchy'];
        if ($useMaintenance == '1' && $useHierarchy == '1') {
            $Query = "SELECT SHA1(usertable.userkey) as encryptedUserkey,
                    usertable.userkey,
                    usertable.realname,
                    usertable.username,
                    usertable.groupid as usergroupid,
                    usertable.userid,
                    usertable.email,
                    usertable.phone,
                    usertable.heirarchy_id,
                    usertable. customerno,
                    groupman.isdeleted as grpdel,
                    groupman.groupid as grpid,
                    u.realname as parentusername,
                    u.phone as parentphone,
                    u.email as parentemail,
                    role.role as rolename,
                    usertable.roleid,
                    usertable.vehicle_movement_alert,
                    usertable.updatedBy,
                    usertable.updatedOn,
                    usertable.createdBy,
                    usertable.createdOn
                    FROM `user` as usertable
                    LEFT JOIN groupman on groupman.userid = usertable.userid
                    LEFT JOIN role on role.id = usertable.roleid
                    LEFT JOIN user as u on usertable.heirarchy_id = u.userid
                    WHERE usertable.customerno=%d AND usertable.isdeleted=0 ";
            if (isset($groupid) && $groupid != 0) {
                $Query .= " AND groupman.groupid = %d Group By usertable.username order by role.sequenceno ASC";
                $usersQuery = sprintf($Query, $customerid, $groupid);
            } else {
                $Query .= " Group By usertable.username order by role.sequenceno ASC";
                $usersQuery = sprintf($Query, $customerid);
            }
        } else {
            $Query = "SELECT *,SHA1(user.userkey) as encryptedUserkey,
                user.userkey,
                user.groupid as usergroupid,
                user.heirarchy_id,
                user.userid,
                user.customerno,
                groupman.isdeleted as grpdel,
                groupman.groupid as grpid,
                user.role as rolename,
                user.roleid,
                user.vehicle_movement_alert
                userUpdated.realname as updatedBy,
                user.updatedOn,
                userCreated.realname as createdBy,
                user.createdOn
                FROM `user`
                LEFT OUTER JOIN groupman on groupman.userid = user.userid
                LEFT OUTER JOIN userCreated ON userCreated.userid = user.userid
                LEFT OUTER JOIN userUpdated ON userUpdated.userid = user.userid
                WHERE user.customerno=%d AND user.isdeleted=0";
            if ($_SESSION['groupid'] == 0) {
                $Query .= " Group By user.username";
            }
            $usersQuery = sprintf($Query, $customerid);
        }
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new VOUser();
                $user->heirarchy_id = $row['heirarchy_id'];
                $user->userid = $row['userid'];
                $user->customerno = $row['customerno'];
                $user->realname = $row['realname'];
                $user->username = $row['username'];
                $user->rolename = $row['rolename'];
                $user->email = $row['email'];
                $user->phone = $row['phone'];
                $user->userkey = $row['userkey'];
                $user->userkey1 = $row['encryptedUserkey'];
                $user->parentuser = isset($row['parentusername']) ? $row['parentusername'] : '';
                $user->parentphone = isset($row['parentphone']) ? $row['parentphone'] : '';
                $user->parentemail = isset($row['parentemail']) ? $row['parentemail'] : '';
                $user->roleid = $row['roleid'];
                $user->vehicle_movement_alert = $row['vehicle_movement_alert'];
                $user->updatedBy = $row['updatedBy'];
                $user->updatedOn = isset($row['updatedOn']) ? date('d/m/Y H:i:s', strtotime($row['updatedOn'])) : '';
                $user->createdBy = $row['createdBy'];
                $user->createdOn = isset($row['createdOn']) ? date('d/m/Y H:i:s', strtotime($row['createdOn'])) : '';
                $users[] = $user;
            }
            return $users;
        }
        return null;
    }

    public function getDeletedUsers($customerid, $useMaintenance = null, $useHierarchy = null) {
        $users = array();
        $useMaintenance = isset($useMaintenance) ? $useMaintenance : $_SESSION['use_maintenance'];
        $useHierarchy = isset($useHierarchy) ? $useHierarchy : $_SESSION['use_hierarchy'];
        if ($useMaintenance == '1' && $useHierarchy == '1') {
            $Query = "SELECT SHA1(usertable.userkey) as encryptedUserkey,
                    usertable.userkey,
                    usertable.realname,
                    usertable.username,
                    usertable.groupid as usergroupid,
                    usertable.userid,
                    usertable.email,
                    usertable.phone,
                    usertable.heirarchy_id,
                    usertable. customerno,
                    groupman.isdeleted as grpdel,
                    groupman.groupid as grpid,
                    u.realname as parentusername,
                    u.phone as parentphone,
                    u.email as parentemail,
                    role.role as rolename,
                    usertable.roleid,
                    usertable.vehicle_movement_alert,
                    userCreated.realname as createdBy,
                    u.createdOn,
                    userUpdated.realname as updatedBy,
                    u.updatedOn
                    FROM `user` as usertable
                    LEFT JOIN groupman on groupman.userid = usertable.userid
                    LEFT JOIN role on role.id = usertable.roleid
                    LEFT JOIN user as u on usertable.heirarchy_id = u.userid
                    LEFT OUTER JOIN user userCreated ON userCreated.userid = u.userid
                    LEFT OUTER JOIN user userUpdated ON userUpdated.userid = u.userid
                    WHERE usertable.customerno=%d AND usertable.isdeleted=1 Group By usertable.username order by role.sequenceno ASC ";
            $usersQuery = sprintf($Query, $customerid);
        } else {
            $Query = "SELECT *,SHA1(user.userkey) as encryptedUserkey,
                user.userkey,
                user.groupid as usergroupid,
                user.heirarchy_id,
                user.userid,
                user.customerno,
                groupman.isdeleted as grpdel,
                groupman.groupid as grpid,
                user.role as rolename,
                user.roleid,
                user.vehicle_movement_alert,
                userCreated.realname as createdBy,
                user.createdOn,
                userUpdated.realname as updatedBy,
                user.updatedOn
                FROM `user`
                LEFT OUTER JOIN groupman on groupman.userid = user.userid
                LEFT OUTER JOIN user userCreated ON userCreated.userid = user.userid
                LEFT OUTER JOIN user userUpdated ON userUpdated.userid = user.userid
                WHERE user.customerno=%d AND user.isdeleted=1";
            $usersQuery = sprintf($Query, $customerid);
        }
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                foreach ($row as &$column) {
                    $column = mb_convert_encoding($column, "UTF-8", "auto");
                }
                $user = new stdClass();
                $user->heirarchy_id = $row['heirarchy_id'];
                $user->userid = $row['userid'];
                $user->customerno = $row['customerno'];
                $user->realname = $row['realname'];
                $user->username = $row['username'];
                $user->rolename = $row['rolename'];
                $user->email = $row['email'];
                $user->phone = $row['phone'];
                $user->userkey = $row['userkey'];
                $user->userkey1 = $row['encryptedUserkey'];
                $user->parentuser = isset($row['parentusername']) ? $row['parentusername'] : '';
                $user->parentphone = isset($row['parentphone']) ? $row['parentphone'] : '';
                $user->parentemail = isset($row['parentemail']) ? $row['parentemail'] : '';
                $user->roleid = $row['roleid'];
                $user->vehicle_movement_alert = $row['vehicle_movement_alert'];
                $user->updatedBy = $row['updatedBy'];
                $user->updatedOn = isset($row['updatedOn']) ? date('d/m/Y H:i:s', strtotime($row['updatedOn'])) : '';
                $user->createdBy = $row['createdBy'];
                $user->createdOn = isset($row['createdOn']) ? date('d/m/Y H:i:s', strtotime($row['createdOn'])) : '';
                $users[] = $user;
            }
            // $users = $this->utf8ize($users);
            return $users;
        }
        return null;
    }

    public function get_heirarchy_name($roleid, $heirarchy_id, $customerid) {
        $usersQuery = "";
        if ($heirarchy_id == 0) {
            return '';
        } elseif ($roleid == "1") {
            $usersQuery = sprintf("SELECT name as heirarchy_name FROM nation WHERE nationid = %d AND customerno = %d", Validator::escapeCharacters($heirarchy_id), Validator::escapeCharacters($customerid));
        } elseif ($roleid == "2") {
            $usersQuery = sprintf("SELECT name as heirarchy_name FROM state WHERE stateid = %d AND customerno = %d", Validator::escapeCharacters($heirarchy_id), Validator::escapeCharacters($customerid));
        } elseif ($roleid == "3") {
            $usersQuery = sprintf("SELECT name as heirarchy_name FROM district WHERE districtid = %d AND customerno = %d", Validator::escapeCharacters($heirarchy_id), Validator::escapeCharacters($customerid));
        } elseif ($roleid == "4") {
            $usersQuery = sprintf("SELECT name as heirarchy_name FROM city WHERE cityid = %d AND customerno = %d", Validator::escapeCharacters($heirarchy_id), Validator::escapeCharacters($customerid));
        } elseif ($roleid == "8") {
            $usersQuery = sprintf("SELECT groupname as heirarchy_name FROM `group` WHERE groupid = %d AND customerno = %d", Validator::escapeCharacters($heirarchy_id), Validator::escapeCharacters($customerid));
        }
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row["heirarchy_name"];
            }
        }
        return 0;
    }

    public function getadminforcustomer($customerid) {
        $users = array();
        $usersQuery = sprintf("SELECT * FROM `user` where user.customerno=%d AND user.isdeleted=0 Group By user.username", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new VOUser();
                $user->userid = $row['userid'];
                $user->username = $row['username'];
                $user->role = $row['role'];
                $user->roleid = $row['roleid'];
                $user->email = $row['email'];
                $user->phone = $row['phone'];
                $user->realname = $row['realname'];
                $user->lastvisit = $row['lastvisit'];
                $user->visits = $row['visited'];
                $user->userkey = $row['userkey'];
                $user->customerno = $row['customerno'];
                $user->mess_email = $row["mess_email"];
                $user->mess_sms = $row["mess_sms"];
                $user->dailyemail = $row['dailyemail'];
                $user->dailyemail_csv = $row['dailyemail_csv'];
                $user->vehicle_movement_alert = $row['vehicle_movement_alert'];
                $users[] = $user;
            }
            return $users;
        }
        return null;
    }

    public function getusersforcustomerfortravelhist($customerid) {
        $users = array();
        $usersQuery = sprintf("SELECT * FROM `user` where customerno=%d AND dailyemail=1 AND isdeleted=0", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["realname"] != "Elixir") {
                    $user = new VOUser();
                    $user->userid = $row['userid'];
                    $user->username = $row['username'];
                    $user->role = $row['role'];
                    $user->roleid = $row['roleid'];
                    $user->email = $row['email'];
                    $user->phone = $row['phone'];
                    $user->realname = $row['realname'];
                    $user->lastvisit = $row['lastvisit'];
                    $user->visits = $row['visited'];
                    $user->customerno = $row['customerno'];
                    $user->mess_email = $row["mess_email"];
                    $user->mess_sms = $row["mess_sms"];
                    $user->dailyemail = $row['dailyemail'];
                    $user->dailyemail_csv = $row['dailyemail_csv'];
                    $users[] = $user;
                }
            }
            return $users;
        }
        return null;
    }

    public function getusersforcustomerformaintenance($customerid, $vehicleid) {
        $users = array();
        $Query = "SELECT * FROM vehicle
                  INNER JOIN groupman on groupman.groupid = vehicle.groupid
                  INNER JOIN user on user.userid = groupman.userid
                  WHERE vehicle.customerno = %d AND vehicle.vehicleid = %d AND user.isdeleted = 0 and groupman.isdeleted=0";
        $usersQuery = sprintf($Query, $customerid, $vehicleid);
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["realname"] != "Elixir") {
                    $user = new VOUser();
                    $user->userid = $row['userid'];
                    $user->username = $row['username'];
                    $user->email = $row['email'];
                    $user->phone = $row['phone'];
                    $user->realname = $row['realname'];
                    $user->customerno = $row['customerno'];
                    $users[] = $user;
                }
            }
            return $users;
        }
        return null;
    }

    public function checkGroupman($customerno, $userid) {
        $grp = array();
        $QUERY = "SELECT * FROM groupman WHERE customerno=%d AND userid=%d AND isdeleted=0 ";
        $SQL = sprintf($QUERY, $customerno, $userid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $grp[] = $row['groupid'];
            }
        } else {
            $grp[] = 1;
        }
        return $grp;
    }

    public function getusersforcustomerfortravelhistcsv($customerid) {
        $users = array();
        $usersQuery = sprintf("SELECT * FROM `user` where customerno=%d AND dailyemail_csv=1 AND isdeleted=0", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["realname"] != "Elixir") {
                    $user = new VOUser();
                    $user->userid = $row['userid'];
                    $user->username = $row['username'];
                    $user->role = $row['role'];
                    $user->roleid = $row['roleid'];
                    $user->email = $row['email'];
                    $user->phone = $row['phone'];
                    $user->realname = $row['realname'];
                    $user->lastvisit = $row['lastvisit'];
                    $user->visits = $row['visited'];
                    $user->customerno = $row['customerno'];
                    $user->mess_email = $row["mess_email"];
                    $user->mess_sms = $row["mess_sms"];
                    $user->dailyemail_csv = $row['dailyemail_csv'];
                    $user->start_alert_time = $row['start_alert'];
                    $user->stop_alert_time = $row['stop_alert'];
                    $users[] = $user;
                }
            }
            return $users;
        }
        return null;
    }

    public function getusersforcustomerforgenset($customerid) {
        $users = array();
        $usersQuery = sprintf("SELECT * FROM `user` where customerno=%d AND dailyemail=1 AND isdeleted=0", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["realname"] != "Elixir") {
                    $user = new VOUser();
                    $user->userid = $row['userid'];
                    $user->username = $row['username'];
                    $user->role = $row['role'];
                    $user->roleid = $row['roleid'];
                    $user->email = $row['email'];
                    $user->phone = $row['phone'];
                    $user->realname = $row['realname'];
                    $user->lastvisit = $row['lastvisit'];
                    $user->visits = $row['visited'];
                    $user->customerno = $row['customerno'];
                    $user->mess_email = $row["mess_email"];
                    $user->mess_sms = $row["mess_sms"];
                    $user->dailyemail = $row['dailyemail'];
                    $user->dailyemail_csv = $row['dailyemail_csv'];
                    $users[] = $user;
                }
            }
            return $users;
        }
        return null;
    }

    public function getusersforcustomerforgensetcsv($customerid) {
        $users = array();
        $usersQuery = sprintf("SELECT * FROM `user` where customerno=%d AND dailyemail_csv=1 AND isdeleted=0", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["realname"] != "Elixir") {
                    $user = new VOUser();
                    $user->userid = $row['userid'];
                    $user->username = $row['username'];
                    $user->role = $row['role'];
                    $user->roleid = $row['roleid'];
                    $user->email = $row['email'];
                    $user->phone = $row['phone'];
                    $user->realname = $row['realname'];
                    $user->lastvisit = $row['lastvisit'];
                    $user->visits = $row['visited'];
                    $user->customerno = $row['customerno'];
                    $user->mess_email = $row["mess_email"];
                    $user->mess_sms = $row["mess_sms"];
                    $user->dailyemail = $row['dailyemail'];
                    $user->dailyemail_csv = $row['dailyemail_csv'];
                    $users[] = $user;
                }
            }
            return $users;
        }
        return null;
    }

    public function getunfilteredusersforcustomer($customerid) {
        $users = array();
        $usersQuery = sprintf("SELECT * FROM `user` where customerno=%d AND isdeleted=0", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["realname"] != "Elixir") {
                    $user = new VOUser();
                    $user->id = $row['userid'];
                    $user->username = $row['username'];
                    $user->role = $row['role'];
                    $user->roleid = $row['roleid'];
                    $user->email = $row['email'];
                    $user->phone = $row['phone'];
                    $user->realname = $row['realname'];
                    $user->lastvisit = $row['lastvisit'];
                    $user->visits = $row['visited'];
                    $user->userkey = $row['userkey'];
                    $user->customerno = $row['customerno'];
                    $user->mess_email = $row["mess_email"];
                    $user->mess_sms = $row["mess_sms"];
                    $user->speed_email = $row["speed_email"];
                    $user->speed_sms = $row["speed_sms"];
                    $user->power_email = $row["power_email"];
                    $user->power_sms = $row["power_sms"];
                    $user->tamper_email = $row["tamper_email"];
                    $user->tamper_sms = $row["tamper_sms"];
                    $user->chk_email = $row["chk_email"];
                    $user->chk_sms = $row["chk_sms"];
                    $user->ac_email = $row["ac_email"];
                    $user->ac_sms = $row["ac_sms"];
                    $user->aci_email = $row["aci_email"];
                    $user->aci_sms = $row["aci_sms"];
                    $user->ignition_email = $row["ignition_email"];
                    $user->ignition_sms = $row['ignition_sms'];
                    $user->temp_email = $row['temp_email'];
                    $user->temp_sms = $row['temp_sms'];
                    $user->groups = $row['groupid'];
                    $user->start_alert_time = $row['start_alert'];
                    $user->stop_alert_time = $row['stop_alert'];
                    $user->dailyemail = $row['dailyemail'];
                    $user->dailyemail_csv = $row['dailyemail_csv'];
                    $users[] = $user;
                }
            }
            return $users;
        }
        return null;
    }

    public function getusersforcustomerbytype($customerid, $type, $vehicleid = null) {
        $users = array();
        $queryString = " FROM user as a
                        left join vehiclewise_alert as b on a.userid=b.userid and a.customerno=b.customerno
                        inner join vehicle as v on v.vehicleid = b.vehicleid and v.customerno  = b.customerno
                        inner join unit as u on u.uid = v.uid and u.customerno = v.customerno
                        inner join devices as d on d.uid = u.uid and d.customerno = u.customerno";
        $queryCondition = " AND a.customerno =%d
                        AND b.customerno =%d
                        AND v.customerno =%d
                        AND u.customerno =%d
                        AND d.customerno =%d
                        and b.vehicleid= %d
                        AND a.isdeleted=0
                        AND b.isdeleted =0
                        and v.isdeleted=0";
        switch ($type) {
            case '1':
                $usersQuery = sprintf("SELECT a.*
                            , b.ac_active as new_active_status
                            , b.ac_starttime as start_time
                            , b.ac_endtime as end_time
                            " . $queryString . "
                            where  (a.ac_email != '0' OR a.ac_sms != '0' OR a.ac_telephone != '0' OR a.ac_mobilenotification !='0')
                            and b.ac_active=1
                            " . $queryCondition, $customerid, $customerid, $customerid, $customerid, $customerid, $vehicleid);
                break;
            case '2':
                $usersQuery = sprintf("SELECT *
                            FROM `user`
                            where (`chk_email` != '0' OR `chk_sms` != '0' OR `chk_telephone` != '0' OR `chk_mobilenotification`!='0')
                            AND customerno=%d
                            AND isdeleted=0", Validator::escapeCharacters($customerid));
                break;
            case '3':
                $usersQuery = sprintf("SELECT *
                            FROM `user`
                            where (`mess_email` != '0' OR `mess_sms` != '0' OR `mess_telephone` != '0' OR `mess_mobilenotification` !='0')
                            AND customerno=%d
                            AND isdeleted=0", Validator::escapeCharacters($customerid));
                break;
            case '4':
                $usersQuery = sprintf("SELECT a.*
                            , b.ignition_active as new_active_status
                            , b.ignition_starttime as start_time
                            , b.ignition_endtime as end_time
                            " . $queryString . "
                            where (a.ignition_email != '0' OR a.ignition_sms != '0' OR a.ignition_telephone != '0' OR a.ignition_mobilenotification!='0')
                            and b.ignition_active=1" . $queryCondition, $customerid, $customerid, $customerid, $customerid, $customerid, $vehicleid);
                break;
            case '5':
                $usersQuery = sprintf("SELECT a.*
                            , b.speed_active as new_active_status
                            , b.speed_starttime as start_time
                            , b.speed_endtime as end_time
                            " . $queryString . "
                            where (a.speed_email != '0' OR a.speed_sms != '0' OR a.speed_telephone != '0' OR a.speed_mobilenotification!='0')
                           and b.speed_active=1" . $queryCondition, $customerid, $customerid, $customerid, $customerid, $customerid, $vehicleid);
                break;
            case '6':
                $usersQuery = sprintf("SELECT a.*
                            , b.powerc_active as new_active_status
                            , b.powerc_starttime as start_time
                            , b.powerc_endtime as end_time
                            " . $queryString . "
                            where (a.power_email != '0' OR a.power_sms != '0' OR a.power_telephone != '0' OR a.power_mobilenotification!='0')
                            and b.powerc_active=1" . $queryCondition, $customerid, $customerid, $customerid, $customerid, $customerid, $vehicleid);
                break;
            case '7':
                $usersQuery = sprintf("SELECT a.*
                            , b.tamper_active as new_active_status
                            , b.tamper_starttime as start_time
                            , b.tamper_endtime as end_time
                            " . $queryString . "
                            where (a.tamper_email != '0' OR a.tamper_sms != '0' OR a.tamper_telephone != '0' OR a.tamper_mobilenotification!='0')
                            and b.tamper_active=1" . $queryCondition, $customerid, $customerid, $customerid, $customerid, $customerid, $vehicleid);
                break;
            case '8':
                $usersQuery = sprintf("SELECT a.*
                            , b.temp_active as new_active_status
                            , b.temp_starttime as start_time
                            , b.temp_endtime as end_time
                            , v.temp1_min
                            , v.temp2_min
                            , v.temp3_min
                            , v.temp4_min
                            " . $queryString . "
                            inner join tempSensorSpecificAlert as tsa on tsa.userid = a.userid and tsa.customerno = a.customerno and tsa.isdeleted=0
                            where (a.temp_email != '0' OR a.temp_sms != '0' OR a.temp_telephone != '0' OR a.temp_mobilenotification!='0')
                            and b.temp_active=1" . $queryCondition, $customerid, $customerid, $customerid, $customerid, $customerid, $vehicleid);
                break;
            case '15':
                $usersQuery = sprintf("SELECT a.*
                            , b.panic_active as new_active_status
                            , b.panic_starttime as start_time
                            , b.panic_endtime as end_time
                            " . $queryString . "
                            where (a.`panic_email` != '0' OR a.`panic_sms` != '0' OR a.`panic_telephone` != '0' OR a.`panic_mobilenotification`!='0')
                            AND b.panic_active=1" . $queryCondition, $customerid, $customerid, $customerid, $customerid, $customerid, $vehicleid);
                break;
            case '16':
                $usersQuery = sprintf("SELECT a.*
                            , b.door_active as new_active_status
                            , b.door_starttime as start_time
                            , b.door_endtime as end_time
                            " . $queryString . "
                            where (a.`door_email` != '0' OR a.`door_sms` != '0' OR a.`door_telephone` != '0' OR `door_mobilenotification`!='0')
                            AND b.door_active=1" . $queryCondition, $customerid, $customerid, $customerid, $customerid, $customerid, $vehicleid);
                break;
            case '17':
                $usersQuery = sprintf("select *
                            from user as u inner join freezelog as f on u.userid = f.createdby
                            where f.vehicleid = %d
                            AND u.customerno=%d
                            AND u.isdeleted=0
                            order by f.fid desc limit 1", $vehicleid, $customerid);
                break;
            case '18':
                /*  $usersQuery = sprintf("SELECT *
                FROM `user`
                where (`chk_email` != '0' OR `chk_sms` != '0' OR `chk_telephone` != '0' OR `chk_mobilenotification`!='0')
                AND customerno=%d
                 */
                $usersQuery = sprintf("SELECT *
                            FROM `user`
                            where (`checkpointwise_email_alert` != '0')
                            AND customerno=%d
                            AND isdeleted=0", Validator::escapeCharacters($customerid));
                break;
        }
        if (!isset($usersQuery)) {
            return null;
        }
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                //if ($row["realname"] != "Elixir") {
                $user = new VOUser();
                $user->id = $row['userid'];
                $user->username = $row['username'];
                $user->role = $row['role'];
                $user->roleid = $row['roleid'];
                $user->email = $row['email'];
                $user->phone = $row['phone'];
                $user->realname = $row['realname'];
                $user->lastvisit = $row['lastvisit'];
                $user->visits = $row['visited'];
                $user->userkey = $row['userkey'];
                $user->customerno = $row['customerno'];
                $user->groups = $row['groupid'];
                $user->mess_email = $row["mess_email"];
                $user->mess_sms = $row["mess_sms"];
                $user->mess_telephone = $row["mess_telephone"];
                $user->speed_email = $row["speed_email"];
                $user->speed_sms = $row["speed_sms"];
                $user->speed_telephone = $row["speed_telephone"];
                $user->speed_mobilenotification = $row["speed_mobilenotification"];
                $user->power_email = $row["power_email"];
                $user->power_telephone = $row["power_telephone"];
                $user->power_mobilenotification = $row["power_mobilenotification"];
                $user->power_sms = $row["power_sms"];
                $user->tamper_email = $row["tamper_email"];
                $user->tamper_sms = $row["tamper_sms"];
                $user->tamper_telephone = $row["tamper_telephone"];
                $user->tamper_mobilenotification = $row["tamper_mobilenotification"];
                $user->chk_email = $row["chk_email"];
                $user->chk_sms = $row["chk_sms"];
                $user->chk_telephone = $row["chk_telephone"];
                $user->chk_mobilenotification = $row["chk_mobilenotification"];
                $user->ac_email = $row["ac_email"];
                $user->ac_sms = $row["ac_sms"];
                $user->ac_telephone = $row["ac_telephone"];
                $user->ac_mobilenotification = $row["ac_mobilenotification"];
                $user->aci_email = $row["aci_email"];
                $user->aci_sms = $row["aci_sms"];
                $user->aci_telephone = $row["aci_telephone"];
                $user->aci_mobilenotification = $row["aci_mobilenotification"];
                $user->ignition_email = $row["ignition_email"];
                $user->ignition_sms = $row['ignition_sms'];
                $user->ignition_telephone = $row['ignition_telephone'];
                $user->ignition_mobilenotification = $row['ignition_mobilenotification'];
                $user->temp_email = $row['temp_email'];
                $user->temp_sms = $row['temp_sms'];
                $user->temp_telephone = $row['temp_telephone'];
                $user->temp_mobilenotification = $row['temp_mobilenotification'];
                $user->panic_email = $row['panic_email'];
                $user->panic_sms = $row['panic_sms'];
                $user->panic_telephone = $row['panic_telephone'];
                $user->panic_mobilenotification = $row['panic_mobilenotification'];
                $user->door_email = $row['door_email'];
                $user->door_sms = $row['door_sms'];
                $user->door_telephone = $row['door_telephone'];
                $user->door_mobilenotification = $row['door_mobilenotification'];
                $user->harsh_break_mobilenotification = $row['harsh_break_mobilenotification'];
                $user->high_acce_mobilenotification = $row['high_acce_mobilenotification'];
                $user->sharp_turn_mobilenotification = $row['sharp_turn_mobilenotification'];
                $user->fuel_alert_mobilenotification = $row['fuel_alert_mobilenotification'];
                /* Added for type=18 starts here */
                $user->checkpointwise_email_alert = $row['checkpointwise_email_alert'];
                $user->checkpointwise_sms_alert = $row['checkpointwise_sms_alert'];
                $user->checkpointwise_telephone_alert = $row['checkpointwise_telephone_alert'];
                $user->checkpointwise_mobilenotification_alert = $row['checkpointwise_mobilenotification_alert'];
                /* Added for type=18 ends here */
                $user->start_alert_time = $row['start_alert'];
                $user->stop_alert_time = $row['stop_alert'];
                $user->dailyemail = $row['dailyemail'];
                $user->dailyemail_csv = $row['dailyemail_csv'];
                $user->notification_status = $row['notification_status'];
                $user->gcmid = $row['gcmid'];
                if (isset($row['new_active_status'])) {
                    $user->new_active_status = $row['new_active_status'];
                    $user->alert_start_time = $row['start_time'];
                    $user->alert_end_time = $row['end_time'];
                }
                $user->isTmpInrngeAlrtRqrd = isset($row['isTempInrangeAlertRequired']) ? $row['isTempInrangeAlertRequired'] : 1;
                $user->isAdvTempConfRange = isset($row['isAdvTempConfRange']) ? $row['isAdvTempConfRange'] : 0;
                $user->temp1_min = isset($row['temp1_min']) ? $row['temp1_min'] : NULL;
                $user->temp2_min = isset($row['temp2_min']) ? $row['temp2_min'] : NULL;
                $user->temp3_min = isset($row['temp3_min']) ? $row['temp3_min'] : NULL;
                $user->temp4_min = isset($row['temp4_min']) ? $row['temp4_min'] : NULL;
                $users[] = $user;
                //}
            }
            return $users;
        }
        return null;
    }

    public function getallusers() {
        $users = array();
        $usersQuery = sprintf("SELECT * FROM `user` WHERE isdeleted = 0");
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new VOUser();
                $user->username = $row['username'];
                $users[] = $user;
            }
            return $users;
        }
        return null;
    }

    public function getCompanyName($customerno) {
        $Query = sprintf("SELECT customercompany FROM " . DB_PARENT . ".customer where customerno = %d LIMIT 1", Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($Query);
        $row = $this->_databaseManager->get_nextRow();
        return $row['customercompany'];
    }

    public function updatevisit($customerno, $userid) {
        $today = date("Y-m-d H:i:s");
        $sql = sprintf("UPDATE user SET lastvisit='%s', visited=visited+1 where userid = %d AND customerno= %d LIMIT 1", Sanitise::DateTime($today), $userid, $customerno);
        $this->_databaseManager->executeQuery($sql);
    }

    public function updateLogin($customerno, $userid) {
        $today = date("Y-m-d H:i:s");
        $Query = "INSERT INTO " . DB_PARENT . ".login_history(userid, customerno, type, timestamp) Values(%d, %d, %d, '%s')";
        $sql = sprintf($Query, $userid, $customerno, 0, Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($sql);
    }

    public function InsertLoginHistory($objUserDetails) {
        $logHistoryId = 0;
        $today = date('Y-m-d h:i:s');
        $objUserDetails->customerno = isset($objUserDetails->customerno) ? $objUserDetails->customerno : 0;
        $objUserDetails->userid = isset($objUserDetails->userid) ? $objUserDetails->userid : 0;
        try {
            $sp_params = "'" . $objUserDetails->pageMasterId . "'"
            . ",'" . $objUserDetails->loginType . "'"
            . ",'" . $objUserDetails->customerno . "'"
            . ",'" . $objUserDetails->todaysdate . "'"
            . ",'" . $objUserDetails->userid . "'"
                . "," . '@logHistoryId';
            $query = $this->_databaseManager->PrepareSP(speedConstants::SP_INSERT_LOGIN_HISTORY, $sp_params);
            $queryResult = $this->_databaseManager->executeQuery($query);
            $outputVars = $this->_databaseManager->executeQuery('SELECT @logHistoryId');
            $result = $this->_databaseManager->get_resultRow($outputVars);
            $logHistoryId = $result["@logHistoryId"];
        } catch (Exception $e) {
            $log = new Log();
            $log->createlog($objUserDetails->customerno, $e, $objUserDetails->userid, null, __FUNCTION__);
        }
        return $logHistoryId;
    }

    public function updategroup($customerno, $userid, $groupid) {
        $sql = sprintf("UPDATE user SET groupid='%s' where userid = %d AND customerno= %d LIMIT 1", $groupid, $userid, $customerno);
        $this->_databaseManager->executeQuery($sql);
    }

    public function changestate($stateid, $userid, $customerno) {
        $Query = 'UPDATE user SET stateid=%d where userid=%d and customerno=%d';
        $userQuery = sprintf($Query, $stateid, $userid, $customerno);
        $this->_databaseManager->executeQuery($userQuery);
    }

    public function updatesel_status($customerno, $userid, $selstatus) {
        $sql = sprintf("UPDATE user SET rt_status_filter=%d where userid = %d AND customerno= %d LIMIT 1", $selstatus, $userid, $customerno);
        $this->_databaseManager->executeQuery($sql);
    }

    public function updatesel_stoppage($customerno, $userid, $selstoppage) {
        $sql = sprintf("UPDATE user SET rt_stoppage_filter=%d where userid = %d AND customerno= %d LIMIT 1", $selstoppage, $userid, $customerno);
        $this->_databaseManager->executeQuery($sql);
    }

    public function SaveUser($user, $userid) {
        if (!isset($user->userid)) {
            $this->Insert($user, $userid);
        } else {
            $this->Update($user, $userid);
        }
    }

    private function Insert($user, $userid) {
        $grparr = array_values($user->groups);
        $firstgroup = array_shift($grparr);
        $date = new Date();
        $today = $date->MySQLNow();
        $userkey = mt_rand(100000, 2147483647);
        $SQL = sprintf("INSERT INTO user
                        (`customerno`,
                        `realname`,
                        `username`,
                        `password`,
                        `role`,
                        `roleid`,
                        `email`,
                        `phone`,
                        `userkey`,
                        `mess_sms`,
                        `mess_email`,
                        `mess_telephone`,
                        `mess_mobilenotification`,
                        `speed_sms`,
                        `speed_email`,
                        `speed_telephone`,
                        `speed_mobilenotification`,
                        `power_sms`,
                        `power_email`,
                        `power_telephone`,
                        `power_mobilenotification`,
                        `tamper_sms`,
                        `harsh_break_sms`,
                        `harsh_break_mail`,
                        `harsh_break_telephone`,
                        `harsh_break_mobilenotification`,
                        `high_acce_sms`,
                        `high_acce_mail`,
                        `high_acce_telephone`,
                        `high_acce_mobilenotification`,
                        `sharp_turn_sms`,
                        `sharp_turn_mail`,
                        `sharp_turn_telephone`,
                        `sharp_turn_mobilenotification`,
                        `towing_sms`,
                        `towing_mail`,
                        `towing_telephone`,
                        `towing_mobilenotification`,
                        `tamper_email`,
                        `tamper_telephone`,
                        `tamper_mobilenotification`,
                        `chk_sms`,
                        `chk_email`,
                        `chk_telephone`,
                        `chk_mobilenotification`,
                        `ac_sms`,
                        `ac_email`,
                        `ac_telephone`,
                        `ac_mobilenotification`,
                        `ignition_sms`,
                        `ignition_email`,
                        `ignition_telephone`,
                        `ignition_mobilenotification`,
                        `temp_sms`,
                        `temp_email`,
                        `temp_telephone`,
                        `temp_mobilenotification`,
                        `dailyemail`,
                        `dailyemail_csv`,
                        `start_alert`,
                        `stop_alert`,
                        `door_sms`,
                        `door_email`,
                        `door_telephone`,
                        `door_mobilenotification`,
                        `panic_sms`,
                        `panic_email`,
                        `panic_telephone`,
                        `panic_mobilenotification`,
                        `immob_sms`,
                        `immob_email`,
                        `immob_telephone`,
                        `immob_mobilenotification`,
                        `tempinterval`,
                        `igninterval`,
                        `speedinterval`,
                        `acinterval`,
                        `doorinterval`,
                        `modifiedby`,
                        `isdeleted`,
                        `groupid`,
                        `heirarchy_id`,
                        `delivery_vehicleid`,
                        `createdBy`,
                        `createdOn`)
                        VALUES
                        ( '%d','%s','%s',sha1('%s'),'%s',%d,'%s','%s','%s',%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,'%s','%s',%d, %d, %d, %d,%d,%d,%d,%d,%d,%d,%d,%d,%d,'%s','%s','%s','%s',%d,0,%d,%d, %d,%d,'%s')"
            , Sanitise::Long($user->customerno)
            , Sanitise::String($user->realname)
            , Sanitise::String($user->username)
            , Sanitise::String($user->password)
            , Sanitise::String($user->role)
            , Sanitise::Long($user->roleid)
            , Sanitise::String($user->email)
            , Sanitise::String($user->phone)
            , Sanitise::String($userkey)
            , Sanitise::Long($user->mess_sms)
            , Sanitise::Long($user->mess_email)
            , Sanitise::Long($user->mess_telephone)
            , Sanitise::Long($user->mess_mobile)
            , Sanitise::Long($user->speed_sms)
            , Sanitise::Long($user->speed_email)
            , Sanitise::Long($user->speed_telephone)
            , Sanitise::Long($user->speed_mobile)
            , Sanitise::Long($user->power_sms)
            , Sanitise::Long($user->power_email)
            , Sanitise::Long($user->power_telephone)
            , Sanitise::Long($user->power_mobile)
            , Sanitise::Long($user->tamper_sms)
            , Sanitise::Long($user->harsh_break_sms)
            , Sanitise::Long($user->harsh_break_mail)
            , Sanitise::Long($user->harsh_break_telephone)
            , Sanitise::Long($user->harsh_break_mobile)
            , Sanitise::Long($user->high_acce_sms)
            , Sanitise::Long($user->high_acce_mail)
            , Sanitise::Long($user->high_acce_telephone)
            , Sanitise::Long($user->high_acce_mobile)
            , Sanitise::Long($user->sharp_turn_sms)
            , Sanitise::Long($user->sharp_turn_mail)
            , Sanitise::Long($user->sharp_turn_telephone)
            , Sanitise::Long($user->sharp_turn_mobile)
            , Sanitise::Long($user->towing_sms)
            , Sanitise::Long($user->towing_mail)
            , Sanitise::Long($user->towing_telephone)
            , Sanitise::Long($user->towing_mobile)
            , Sanitise::Long($user->tamper_email)
            , Sanitise::Long($user->tamper_telephone)
            , Sanitise::Long($user->tamper_mobile)
            , Sanitise::Long($user->chk_sms)
            , Sanitise::Long($user->chk_email)
            , Sanitise::Long($user->chk_telephone)
            , Sanitise::Long($user->chk_mobile)
            , Sanitise::Long($user->ac_sms)
            , Sanitise::Long($user->ac_email)
            , Sanitise::Long($user->ac_telephone)
            , Sanitise::Long($user->ac_mobile)
            , Sanitise::Long($user->ignition_sms)
            , Sanitise::Long($user->ignition_email)
            , Sanitise::Long($user->ignition_telephone)
            , Sanitise::Long($user->ignition_mobile)
            , Sanitise::Long($user->temp_sms)
            , Sanitise::Long($user->temp_email)
            , Sanitise::Long($user->temp_telephone)
            , Sanitise::Long($user->temp_mobile)
            , Sanitise::Long($user->dailyemail)
            , Sanitise::Long($user->dailyemail_csv)
            , Sanitise::String($user->start_alert_time)
            , Sanitise::String($user->stop_alert_time)
            , $user->door_sms, $user->door_email
            , $user->door_telephone
            , $user->door_mobile
            , $user->panic_sms, $user->panic_email
            , $user->panic_telephone
            , $user->panic_mobile
            , $user->immob_sms
            , $user->immob_email
            , $user->immob_telephone
            , $user->immob_mobile
            , Sanitise::String($user->tempinterval)
            , Sanitise::String($user->igninterval)
            , Sanitise::String($user->speedinterval)
            , Sanitise::String($user->acinterval)
            , Sanitise::String($user->doorinterval)
            , Sanitise::Long($userid)
            , Sanitise::Long($firstgroup)
            , Sanitise::Long($user->h_id)
            , $user->delivery_vehicleid
            , $_SESSION['userid']
            , date("Y-m-d H:i:s"));
        $this->_databaseManager->executeQuery($SQL);
        $user->userid = $this->_databaseManager->get_insertedId();
        if (isset($user->userid)) {
            //add_new_exception($user->userid);
            add_vehicle_alerts($user->userid, $user->groups);
            //if ($user->customerno == 64) {
            //$moduleid = MODULE_FMS;
            if (isset($user->menuconfigarr) && !empty($user->menuconfigarr)) {
                $moduleid = 1;
                //default menus add here //
                $this->add_usermenu_mapping($user->userid, $moduleid, $user->customerno);
                //update menu details//
                $this->usermenu_active_mapping($user->userid, $user->menuconfigarr, $user->customerno);
                // }
            } else {
                //  $this->getRoleWiseMenuList($user);
            }
        }
        $vehicles = $this->pullvehicles($user->customerno);
        if (isset($vehicles)) {
            foreach ($vehicles as $thisvehicle) {
                if ($user->safcmin != "0" || $user->saftmin != "0") {
                    $Query = "INSERT INTO `stoppage_alerts` (`customerno`
                ,`userid`
                ,`is_chk_sms`
                ,`is_trans_sms`
                ,`is_chk_email`
                ,`is_trans_email`
                ,`is_chk_telephone`
                ,`is_chk_mobilenotification`
                ,`is_trans_telephone`
                ,`is_trans_mobilenotification`
                ,`chkmins`
                ,`transmins`
                ,`isdeleted`
                ,`vehicleid`
                ,`createdBy`
                ,`createdOn`)
                VALUES (%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,0,%d,%d,'%s')";
                    $SQL = sprintf($Query
                        , Sanitise::Long($user->customerno)
                        , Sanitise::Long($user->userid)
                        , Sanitise::Long($user->safcsms)
                        , Sanitise::Long($user->saftsms)
                        , Sanitise::Long($user->safcemail)
                        , Sanitise::Long($user->saftemail)
                        , Sanitise::Long($user->safctelephone)
                        , Sanitise::Long($user->safcmobile)
                        , Sanitise::Long($user->safttelephone)
                        , Sanitise::Long($user->saftmobile)
                        , Sanitise::Long($user->safcmin)
                        , Sanitise::Long($user->saftmin)
                        , Sanitise::Long($thisvehicle->vehicleid)
                        , $_SESSION['userid']
                        , date("Y-m-d H:i:s"));
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
        }
        if ($user->roleid != '1') {
            $Query = "INSERT INTO `groupman` (`userid`,`groupid`,`customerno`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','0','%s')";
            //echo $routearrays = $routearray;
            foreach ($user->groups as $group) {
                if ($group != '0') {
                    $SQL = sprintf($Query
                        , Sanitise::Long($user->userid)
                        , Sanitise::Long($group)
                        , $user->customerno
                        , Sanitise::DateTime($today));
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
        }
        /* Add vehicle and group in vehicleusermapping table for custom role */
        if (isset($user->vehicles) && !empty($user->vehicles)) {
            foreach ($user->vehicles as $vehicle) {
                if (!empty($vehicle) && $vehicle != 0 && $vehicle !== "") {
                    $groupid = $this->GetGroupIdFromVehicleid($vehicle, $user->customerno);
                    $Query = "INSERT INTO vehicleusermapping(vehicleid
                            ,groupid
                            ,userid
                            ,customerno
                            ,created_by
                            ,created_on)
                           VALUES (%d,%d,%d,%d,%d,'%s')";
                    $SQL = sprintf($Query
                        , Sanitise::Long($vehicle)
                        , Sanitise::Long($groupid)
                        , Sanitise::Long($user->userid)
                        , Sanitise::Long($user->customerno)
                        , Sanitise::Long($_SESSION['userid'])
                        , Sanitise::DateTime($today));
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
        }
        /* Set Cron Report and user mapping */
        if (isset($user->reports) && !empty($user->reports)) {
            $Query = "INSERT INTO userReportMapping(reportId,reportTime,isActivated,userid,customerno,created_by,created_on)VALUES(%d,%d,1,%d,%d,%d,'%s')";
            foreach ($user->reports as $report) {
                $SQL = sprintf($Query
                    , Sanitise::Long($report->reportId)
                    , Sanitise::Long($report->reportTime)
                    , Sanitise::Long($user->userid)
                    , Sanitise::Long($user->customerno)
                    , Sanitise::Long($_SESSION['userid'])
                    , Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        if (isset($user->temprepinterval)) {
            if ($user->temprepinterval > 0) {
                $Query = "INSERT INTO tempreportinterval(userid
                            ,customerno
                            ,`interval`
                            ,createdby
                            ,createdon)
                           VALUES (%d,%d,%d,%d,'%s')";
                $SQL = sprintf($Query
                    , Sanitise::Long($user->userid)
                    , Sanitise::Long($user->customerno)
                    , Sanitise::Long($user->temprepinterval)
                    , Sanitise::Long($_SESSION['userid'])
                    , Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        if (isset($user->vehrepinterval)) {
            if ($user->vehrepinterval > 0) {
                $Query = "INSERT INTO vehrepinterval(userid
                            ,customerno
                            ,`interval`
                            ,createdby
                            ,createdon)
                           VALUES (%d,%d,%d,%d,'%s')";
                $SQL = sprintf($Query
                    , Sanitise::Long($user->userid)
                    , Sanitise::Long($user->customerno)
                    , Sanitise::Long($user->vehrepinterval)
                    , Sanitise::Long($_SESSION['userid'])
                    , Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        if (isset($user->chkExUserMapping) && $user->chkExUserMapping != '') {
            $this->insertUserExceptionMapping($user);
        }
        $this->setUserAlert($user);
    }

    public function add_usermenu_mapping($userid, $moduleid, $customerno) {
        $today = date("Y-m-d H:i:s");
        $pdo = $this->_databaseManager->CreatePDOConn();
        //Prepare parameters
        $sp_params = "'" . $moduleid . "'"
            . ",'" . $customerno . "'"
            . ",'" . $today . "'"
            . ",'" . $userid . "'"
            . ",'" . $_SESSION['userid'] . "'";
        $queryCallSP = "CALL insert_default_menu(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        return true;
    }

    public function update_user_menumapping_reset($userid, $moduleid, $customerno) {
        $addval = 0;
        $editval = 0;
        $delval = 0;
        $Query = "UPDATE `usermenu_mapping` SET
                       isactive=0,
                       add_permission=%d,
                       edit_permission=%d,
                       delete_permission=%d
                       WHERE userid = %d AND customerno =%d";
        $MappingSQL = sprintf($Query, Sanitise::Long($addval), Sanitise::Long($editval), Sanitise::Long($delval), Sanitise::Long($userid), $customerno);
        $this->_databaseManager->executeQuery($MappingSQL);
        return true;
    }

    public function usermenu_active_mapping($userid, $menuconfigarr, $customerno) {
        $today = date("Y-m-d H:i:s");
        if (count($menuconfigarr) > 0) {
            foreach ($menuconfigarr as $row) {
                $menuid = $row->menuid;
                $addval = $row->addval;
                $editval = $row->editval;
                $delval = $row->delval;
                if ($addval != 0) {
                    $addval = 1;
                } else {
                    $addval = 0;
                }
                if ($editval != 0) {
                    $editval = 1;
                } else {
                    $editval = 0;
                }
                if ($delval != 0) {
                    $delval = 1;
                } else {
                    $delval = 0;
                }
                $Query = "UPDATE `usermenu_mapping` SET
                       isactive=1,
                       add_permission=%d,
                       edit_permission=%d,
                       delete_permission=%d
                       WHERE menuid=%d AND userid = %d AND customerno =%d";
                $MappingSQL = sprintf($Query, Sanitise::Long($addval), Sanitise::Long($editval), Sanitise::Long($delval), Sanitise::Long($menuid), Sanitise::Long($userid), $customerno);
                $this->_databaseManager->executeQuery($MappingSQL);
            }
            return true;
        }
    }

    private function Update($user, $userid) {
        $loggedInUserId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
        $today = date("Y-m-d H:i:s");
        $groupArr = array_values($user->groups);
        $firstgroup = array_shift($groupArr);
        if ($user->password != "") {
            $SQL = sprintf("Update user
            Set `realname`='%s',
            `password`=sha1('%s'),
            `username`='%s',
            `email`='%s',
            `phone`='%s',
            `modifiedby`=%d,
            `groupid`=%d,
            `heirarchy_id`=%d,
            `delivery_vehicleid`=%d,
            `updatedBy`=%d,
            `updatedOn`='%s'
            WHERE userid = %d AND customerno = %d"
                , Sanitise::String($user->realname)
                , Sanitise::String($user->password)
                , Sanitise::String($user->username)
                , Sanitise::String($user->email)
                , Sanitise::String($user->phone)
                , Sanitise::Long($userid)
                , Sanitise::Long($firstgroup)
                , Sanitise::Long($user->h_id)
                , $user->delivery_vehicleid
                , $loggedInUserId
                , $today
                , Sanitise::Long($user->userid)
                , Sanitise::Long($user->customerno));
        } elseif ($user->roleid != "") {
            $SQL = sprintf("Update user
            Set `realname`='%s',
            `role`='%s',
            `roleid`=%d,
            `username`='%s',
            `email`='%s',
            `phone`='%s',
            `modifiedby`=%d,
            `groupid`=%d,
            `heirarchy_id`=%d,
            `delivery_vehicleid`=%d,
            `updatedBy`=%d,
            `updatedOn`='%s'
            WHERE userid = %d AND customerno = %d"
                , Sanitise::String($user->realname)
                , Sanitise::String($user->role)
                , Sanitise::Long($user->roleid)
                , Sanitise::String($user->username)
                , Sanitise::String($user->email)
                , Sanitise::String($user->phone)
                , Sanitise::Long($userid)
                , Sanitise::Long($firstgroup)
                , Sanitise::Long($user->h_id)
                , $user->delivery_vehicleid
                , $loggedInUserId
                , $today
                , Sanitise::Long($user->userid)
                , Sanitise::Long($user->customerno));
        } else {
            $SQL = sprintf("Update user
            Set `realname`='%s',
            `username`='%s',
            `email`='%s',
            `phone`='%s',
            `modifiedby`=%d,
            `groupid`=%d,
            `heirarchy_id`=%d,
            `delivery_vehicleid`=%d,
            `updatedBy`=%d,
            `updatedOn`='%s'
            WHERE userid = %d AND customerno = %d"
                , Sanitise::String($user->realname)
                , Sanitise::String($user->username)
                , Sanitise::String($user->email)
                , Sanitise::String($user->phone)
                , Sanitise::Long($userid)
                , Sanitise::Long($firstgroup)
                , Sanitise::Long($user->h_id)
                , $user->delivery_vehicleid
                , $loggedInUserId
                , $today
                , Sanitise::Long($user->userid)
                , Sanitise::Long($user->customerno));
        }
        $this->_databaseManager->executeQuery($SQL);
        if (isset($user->menuconfigarr)) {
            //$moduleid = MODULE_FMS;
            $moduleid = 1;
            //default menus reset here //
            $result = $this->update_user_menumapping_reset($user->userid, $moduleid, $user->customerno);
            //update menu details//
            if ($result == true) {
                $this->usermenu_active_mapping($user->userid, $user->menuconfigarr, $user->customerno);
            }
        }
        if ($user->roleid != '1') {
            $GroupQuery = "UPDATE groupman SET isdeleted=1,timestamp='%s',updatedBy=%d,updatedOn='%s' WHERE userid = %d and customerno = %d AND isdeleted=0";
            $date = new Date();
            $today = $date->MySQLNow();
            $GROUPSQL = sprintf($GroupQuery, Sanitise::DateTime($today), $loggedInUserId, $today, Sanitise::Long($user->userid), $user->customerno);
            $this->_databaseManager->executeQuery($GROUPSQL);
            foreach ($user->groups as $group) {
                if ($group != '0') {
                    $GroupQuery = "INSERT INTO `groupman` (`userid`,`groupid`,`customerno`,`isdeleted`,`timestamp`,`createdBy`,`createdOn`) VALUES (%d,'%d','%d','0','%s',%d,'%s')";
                    $date = new Date();
                    $today = $date->MySQLNow();
                    $GROUPSQL = sprintf($GroupQuery, Sanitise::Long($user->userid), Sanitise::Long($group), $user->customerno, Sanitise::DateTime($today), $loggedInUserId, Sanitise::DateTime($today));
                    $this->_databaseManager->executeQuery($GROUPSQL);
                }
            }
            // if ($user->roleid == '43') {
            $vehicles = array();
            $query = "SELECT vehicleid FROM vehicleusermapping WHERE userid = " . $user->userid . " AND customerno = " . $user->customerno . " AND isdeleted = 0;";
            $this->_databaseManager->executeQuery($query);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $vehicles[] = $row['vehicleid'];
                }
            }
            if (isset($user->vehicles) && !empty($user->vehicles)) {
                foreach ($user->vehicles as $vehicle) {
                    // echo "comparing $vehicle to all vehicles"."</br>";
                    if (!empty($vehicle) && $vehicle != 0 && $vehicle !== "") {
                        // echo "checking : ".array_search($vehicle,$vehicles);
                        ///DO NOT CHANGE TRIPLE EQUAL TO IN THE NEXt LINE OR IT WILL STOP WORKING.
                        if (array_search($vehicle, $vehicles) === FALSE) {
                            $groupid = $this->GetGroupIdFromVehicleid($vehicle, $user->customerno);
                            $Query = "INSERT INTO vehicleusermapping(vehicleid
                                    ,groupid
                                    ,userid
                                    ,customerno
                                    ,created_by
                                    ,created_on
                                    ,updated_by
                                    ,updated_on)
                                VALUES (%d,%d,%d,%d,%d,'%s',%d,'%s')";
                            $SQL = sprintf($Query
                                , Sanitise::Long($vehicle)
                                , Sanitise::Long($groupid)
                                , Sanitise::Long($user->userid)
                                , Sanitise::Long($user->customerno)
                                , Sanitise::Long($_SESSION['userid'])
                                , Sanitise::DateTime($today)
                                , Sanitise::Long($_SESSION['userid'])
                                , Sanitise::DateTime($today)
                            );
                            $this->_databaseManager->executeQuery($SQL);
                            // echo "New vehicle : ";
                            // echo $vehicle."</br>";
                        } else {
                            unset($vehicles[array_search($vehicle, $vehicles)]);
                            // echo "existing vehicle : ";
                            // echo $vehicle."</br>";
                        }
                    }
                }
            }
            // echo "deleted vehicles : ";
            // print_r($vehicles);
            foreach ($vehicles as $vehicle) {
                $MappingQuery = "UPDATE vehicleusermapping SET isdeleted=1,updated_by=%d,updated_on='%s' WHERE userid = %d and vehicleid=%d and customerno = %d and isdeleted=0";
                $date = new Date();
                $today = $date->MySQLNow();
                $MappingSQL = sprintf($MappingQuery, $_SESSION['userid'], Sanitise::DateTime($today), Sanitise::Long($user->userid), $vehicle, $user->customerno);
                $this->_databaseManager->executeQuery($MappingSQL);
            }
            // }
        }
    }

    public function UpdateEmailPass($user, $userid) {
        $SQL = sprintf("Update user
                        Set `email`='%s',
                        `phone`='%s',
                        `modifiedby`=%d
                        WHERE userid = %d AND customerno = %d", Sanitise::String($user->email), Sanitise::String($user->phone), Sanitise::Long($userid), Sanitise::Long($user->userid), Sanitise::Long($user->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function UpdateAccountInfo($user, $userid) {
        $SQL = sprintf("Update user Set `email`='%s', `phone`='%s', `modifiedby`=%d WHERE userid = %d AND customerno = %d", Sanitise::String($user['email']), Sanitise::String($user['phone']), Sanitise::Long($userid), Sanitise::Long($userid), Sanitise::Long($user['customerno']));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeleteUser($userid, $customerno, $modifiedby) {
        $today = date('Y-m-d h:i:s');
        $loggedInUserId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
        $SQL = sprintf("UPDATE user SET isdeleted=1, modifiedby=%d,updatedBy=%d,updatedOn='%s' WHERE userid = %d and customerno = %d", Sanitise::Long($modifiedby), $loggedInUserId, $today, Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
        if ($customerno == 116) {
            $SQL = sprintf("UPDATE " . DB_TMS . ".tmsmapping SET isdeleted=1, updated_on='$today' WHERE userid = %d and customerno = %d", Sanitise::Long($userid), Sanitise::Long($customerno));
            $this->_databaseManager->executeQuery($SQL);
        }
        /* Delete Records From vehicleusermapping For Custom Role */
        $SQL = sprintf("UPDATE vehicleusermapping SET isdeleted=1 WHERE userid = %d and customerno = %d", Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function delete_user_from_groupman($userid) {
        $Query = "UPDATE groupman SET isdeleted=1,timestamp='%s' WHERE userid = %d and customerno = %d";
        $date = new Date();
        $today = $date->MySQLNow();
        //echo $routearrays = $routearray;
        $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::Long($userid), $user->customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function checkuser($username) {
        $usersQuery = sprintf("SELECT * FROM user WHERE username = '%s' AND isdeleted=0", Sanitise::String($username));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($row == $this->_databaseManager->get_nextRow()) {
            $user = new VOUser();
            $user->id = $row['userid'];
            $user->username = $row['username'];
            $user->realname = $row['realname'];
            $user->role = $row['role'];
            $user->roleid = $row['roleid'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $user->phone = $row['phone'];
            $user->lastvisit = $row['lastvisit'];
            $user->visits = $row['visited'];
            $user->customerno = $row['customerno'];
            #$user->dateadded = $row['dateadded'];
            return $user;
        }
        return null;
    }

    /* dt: 21st oct 2014, ak added, to check if username exists in user table */
    public function check_if_user_exists($username) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $usrforgot = array();
        $today = date('Y-m-d H:i:s');
        $sp_params = "'" . $username . "'"
            . ",'" . $today . "'"
            . "," . '@userexists'
        ;
        $QUERY = $this->PrepareSP('speed_forgot_password', $sp_params);
        $row1 = $pdo->query($QUERY)->fetch(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        $OutQuery = "SELECT @userexists AS userfound";
        if ($result = $pdo->query($OutQuery)) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $usrforgot['userfound'] = $row['userfound'];
        }
        if ($usrforgot['userfound'] == 1) {
            $usrforgot['otp'] = $row1['otpparam'];
            $usrforgot['otpvalidupto'] = $row1['otpvalidupto'];
            $usrforgot['useremail'] = $row1['useremail'];
            $usrforgot['userphone'] = $row1['userphone'];
            $usrforgot['customerno'] = $row1['custno'];
        }
        return $usrforgot;
    }

    public function setnewpassword($password, $username) {
        $SQL = sprintf("update user set chgpwd=1, password = sha1('%s') where username = '%s'", Sanitise::String($password), Sanitise::String($username));
        $this->_databaseManager->executeQuery($SQL);
    }

    //Event Based Alerts
    public function modifyalerts_all($user, $userid) {
        $SQL = sprintf("Update user
                        Set chgalert=1,
                        `mess_sms`=%d,
                        `mess_email`=%d,
                        `speed_sms`=%d,
                        `speed_email`=%d,
                        `power_sms`=%d,
                        `power_email`=%d,
                        `tamper_sms`=%d,
                        `harsh_break_sms`=%d,
                        `harsh_break_mail`=%d,
                        `high_acce_sms`=%d,
                        `high_acce_mail`=%d,
                        `sharp_turn_sms`=%d,
                        `sharp_turn_mail`=%d,
                        `towing_sms`=%d,
                        `towing_mail`=%d,
                        `tamper_email`=%d,
                        `chk_sms`=%d,
                        `chk_email`=%d,
                        `ac_sms`=%d,
                        `ac_email`=%d,
                        `ignition_sms`=%d,
                        `ignition_email`=%d,
                        `temp_sms`=%d,
                        `temp_email`=%d,
                        `dailyemail`=%d,
                        `dailyemail_csv`=%d,
                        `start_alert` = '%s',
                        `stop_alert` = '%s',
                        `door_sms` = %d,
                        `door_email` = %d,
                        `modifiedby`=%d
                        WHERE userid = %d AND customerno = %d", Sanitise::Long($user->mess_sms), Sanitise::Long($user->mess_email), Sanitise::Long($user->speed_sms), Sanitise::Long($user->speed_email), Sanitise::Long($user->power_sms), Sanitise::Long($user->power_email), Sanitise::Long($user->tamper_sms), Sanitise::Long($user->harsh_break_sms), Sanitise::Long($user->harsh_break_mail), Sanitise::Long($user->high_acce_sms), Sanitise::Long($user->high_acce_mail), Sanitise::Long($user->sharp_turn_sms), Sanitise::Long($user->sharp_turn_mail), Sanitise::Long($user->towing_sms), Sanitise::Long($user->towing_mail), Sanitise::Long($user->tamper_email), Sanitise::Long($user->chk_sms), Sanitise::Long($user->chk_email), Sanitise::Long($user->ac_sms), Sanitise::Long($user->ac_email), Sanitise::Long($user->ignition_sms), Sanitise::Long($user->ignition_email), Sanitise::Long($user->temp_sms), Sanitise::Long($user->temp_email), Sanitise::Long($user->dailyemail), Sanitise::Long($user->dailyemail_csv), Sanitise::String($user->start_alert_time), Sanitise::String($user->stop_alert_time), $user->door_sms, $user->door_email, Sanitise::Long($userid), Sanitise::Long($user->userid), Sanitise::Long($user->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    //Event Based Alerts
    public function modifyalerts($user, $userid) {
        $SQL = sprintf("Update user
                        Set chgalert=1,
                        `mess_sms`=%d,
                        `mess_email`=%d,
                        `mess_telephone`=%d,
                        `mess_mobilenotification`=%d,
                        `chk_sms`=%d,
                        `chk_email`=%d,
                        `chk_telephone`=%d,
                        `chk_mobilenotification`=%d,
                        `modifiedby`=%d
                        WHERE userid = %d AND customerno = %d"
            , Sanitise::Long($user->mess_sms)
            , Sanitise::Long($user->mess_email)
            , Sanitise::Long($user->mess_telephone)
            , Sanitise::Long($user->mess_mobile)
            , Sanitise::Long($user->chk_sms)
            , Sanitise::Long($user->chk_email)
            , Sanitise::Long($user->chk_telephone)
            , Sanitise::Long($user->chk_mobile)
            , Sanitise::Long($userid)
            , Sanitise::Long($user->userid)
            , Sanitise::Long($user->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    /* to modify advanced alerts */
    public function modify_advanced_alerts($user, $userid) {
        $SQL = sprintf(
            "Update user set
                `harsh_break_sms`=%d,
                `harsh_break_mail`=%d,
                `high_acce_sms`=%d,
                `high_acce_mail`=%d,
                `sharp_turn_sms`=%d,
                `sharp_turn_mail`=%d,
                `towing_sms`=%d,
                `towing_mail`=%d,
                `modifiedby`=%d
                WHERE userid = %d AND customerno = %d", Sanitise::Long($user->harsh_break_sms), Sanitise::Long($user->harsh_break_mail), Sanitise::Long($user->high_acce_sms), Sanitise::Long($user->high_acce_mail), Sanitise::Long($user->sharp_turn_sms), Sanitise::Long($user->sharp_turn_mail), Sanitise::Long($user->towing_sms), Sanitise::Long($user->towing_mail), Sanitise::Long($userid), Sanitise::Long($user->userid), Sanitise::Long($user->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    //Time Based Alerts
    public function modifyTalerts($user, $userid) {
        $SQL = sprintf("UPDATE user SET aci_sms = %d,aci_email=%d, modifiedby=%d
                        WHERE userid = %d AND customerno = %d", Sanitise::Long($user->aci_sms), Sanitise::Long($user->aci_email), Sanitise::Long($userid), Sanitise::Long($user->userid), Sanitise::Long($user->customerno));
        $this->_databaseManager->executeQuery($SQL);
        $Query = 'UPDATE ' . DB_PARENT . '.customer SET aci_time = %d where customerno=%d';
        $SQL = sprintf($Query, $user->aci_time, $user->customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    //new Cron Email set
    public function new_modifyCronalerts($user) {
        $SQL = sprintf("UPDATE user SET dailyemail=%d,dailyemail_csv=%d  WHERE userid = %d AND customerno = %d", Sanitise::Long($user->dailyemail), Sanitise::Long($user->dailyemail_csv), Sanitise::Long($user->userid), Sanitise::Long($user->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    // New vehicle movement alert
    public function new_modifyVehicleMovementalerts($user) {
        $SQL = sprintf("UPDATE user SET vehicle_movement_alert=%d WHERE userid = %d AND customerno = %d", Sanitise::Long($user->vmastatus), Sanitise::Long($user->userid), Sanitise::Long($user->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    //Cron Time Based
    public function modifyAlertsTimebased($user, $userid) {
        $SQL = sprintf("UPDATE user SET start_alert = '%s', stop_alert = '%s', modifiedby=%d
                        WHERE userid = %d AND customerno = %d", Sanitise::String($user->start_alert_time), Sanitise::String($user->stop_alert_time), Sanitise::Long($userid), Sanitise::Long($user->userid), Sanitise::Long($user->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    //Modify Custom Field
    public function modifycustomfield($user, $customerno) {
        $i = 1;
        for ($i = 1; $i <= 26; $i++) {
            $usersQuery = sprintf("SELECT * FROM customfield WHERE customerno = '%s' AND custom_id = '$i'", Sanitise::String($customerno));
            $this->_databaseManager->executeQuery($usersQuery);
            $usecustom1 = 'usecustom' . $i;
            $usecustom = $user->$usecustom1;
            $customname1 = 'customname' . $i;
            $customname = $user->$customname1;
            if ($usecustom != '' && $customname != '') {
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        $SQL = sprintf("UPDATE customfield SET usecustom = '%s', customname = '%s'
                        WHERE customerno = %d AND custom_id = '$i'", Sanitise::Long($usecustom), Sanitise::String($customname), Sanitise::Long($customerno));
                        $this->_databaseManager->executeQuery($SQL);
                    }
                } else {
                    $Query = "INSERT INTO `customfield` (`custom_id`,`customname`,`usecustom`,`customerno`,`date_modified`) VALUES (%d,'%s',%d,%d,'%s')";
                    $date = new Date();
                    $today = $date->MySQLNow();
                    $SQL = sprintf($Query, Sanitise::Long($i), Sanitise::String($customname), Sanitise::Long($usecustom), $customerno, Sanitise::DateTime($today));
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
        }
        $_SESSION["digitalcon"] = $this->store_custom_name($customerno, 'Digital', 1);
        $_SESSION["administrator"] = $this->store_custom_name($customerno, 'Administrator', 2);
        $_SESSION["tracker"] = $this->store_custom_name($customerno, 'Tracker', 3);
        $_SESSION["master"] = $this->store_custom_name($customerno, 'Master', 4);
        $_SESSION["statehead"] = $this->store_custom_name($customerno, 'State Head', 5);
        $_SESSION["districthead"] = $this->store_custom_name($customerno, 'District Head', 6);
        $_SESSION["cityhead"] = $this->store_custom_name($customerno, 'City Head', 7);
        $_SESSION["branchhead"] = $this->store_custom_name($customerno, 'Branch Head', 8);
        $_SESSION["nation"] = $this->store_custom_name($customerno, 'Nation', 9);
        $_SESSION["state"] = $this->store_custom_name($customerno, 'State', 10);
        $_SESSION["district"] = $this->store_custom_name($customerno, 'District', 11);
        $_SESSION["city"] = $this->store_custom_name($customerno, 'City', 12);
        $_SESSION["group"] = $this->store_custom_name($customerno, 'Group', 13);
        $_SESSION["licno"] = $this->store_custom_name($customerno, 'License No', 14);
        $_SESSION["ref_number"] = $this->store_custom_name($customerno, 'Reference Number', 15);
        $_SESSION["ext_digital1"] = $this->store_custom_name($customerno, 'Digital-1', 16);
        $_SESSION["ext_digital2"] = $this->store_custom_name($customerno, 'Digital-2', 17);
        $_SESSION["ext_digital3"] = $this->store_custom_name($customerno, 'Digital-3', 18);
        $_SESSION["ext_digital4"] = $this->store_custom_name($customerno, 'Digital-4', 19);
        $_SESSION["Temperature 1"] = $this->store_custom_name($customerno, 'Temperature 1', 20);
        $_SESSION["Temperature 2"] = $this->store_custom_name($customerno, 'Temperature 2', 21);
        $_SESSION["Temperature 3"] = $this->store_custom_name($customerno, 'Temperature 3', 22);
        $_SESSION["Temperature 4"] = $this->store_custom_name($customerno, 'Temperature 4', 23);
        $_SESSION["extradigitalstatus"] = $this->store_custom_name($customerno, 'Status', 24);
        $_SESSION["Warehouse"] = $this->store_custom_name($customerno, 'Warehouse', 25);
        $_SESSION["Driver"] = $this->store_custom_name($customerno, 'Driver', 26);
    }

    //Insert Custom Field
    public function insertcustomfield($usecustom, $customname, $customerno) {
        $Query = "INSERT INTO `customfield` (`name`,`customname`,`usecustom`,`customerno`,`date_modified`) VALUES ('%s','%s',%d,%d,'%s')";
        $date = new Date();
        $today = $date->MySQLNow();
        $SQL = sprintf($Query, 'Digital', Sanitise::String($customname), Sanitise::Long($usecustom), $customerno, Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function pullvehicles($customerid) {
        $vehicles = array();
        $sql = "select v.vehicleid
                from vehicle v
                Inner join unit on unit.uid = v.uid
                INNER JOIN devices on devices.uid = unit.uid
                WHERE v.customerno = %d AND unit.customerno=%d AND devices.customerno=%d AND unit.trans_statusid NOT IN(10,22) AND v.isdeleted=0";
        $Query = sprintf($sql, Validator::escapeCharacters($customerid), Validator::escapeCharacters($customerid), Validator::escapeCharacters($customerid));
        //echo "Query is: ".$Query; exit();
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function modify_stoppage_alerts($user, $userid, $vehicle) {
        $datetime = date("Y-m-d H:i:s");
        $Query = sprintf("SELECT id FROM stoppage_alerts WHERE customerno = %d AND userid = %d AND vehicleid = %d", Sanitise::Long($user->customerno), Sanitise::Long($user->userid), Sanitise::Long($vehicle->vehicleid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $SQL = sprintf("Update stoppage_alerts
                                    Set `is_chk_sms`=%d,
                                    `is_chk_email`=%d,
                                    `is_chk_telephone`=%d,
                                    `is_trans_sms`=%d,
                                    `is_trans_email`=%d,
                                    `is_trans_telephone`=%d,
                                    `chkmins`=%d,
                                    `transmins`=%d,
                                    `isdeleted`=0,
                                    `updatedBy`=%d,
                                    `updatedOn`='%s'
                                    WHERE userid = %d
                                    AND customerno = %d
                                    AND vehicleid = %d"
                , Sanitise::Long($user->safcsms)
                , Sanitise::Long($user->safcemail)
                , Sanitise::Long($user->safctelephone)
                , Sanitise::Long($user->saftsms)
                , Sanitise::Long($user->saftemail)
                , Sanitise::Long($user->safttelephone)
                , Sanitise::Long($user->safcmin)
                , Sanitise::Long($user->saftmin)
                , $_SESSION['userid']
                , $datetime
                , Sanitise::Long($user->userid)
                , Sanitise::Long($user->customerno)
                , Sanitise::Long($vehicle->vehicleid));
            $this->_databaseManager->executeQuery($SQL);
        } else {
            $Query = "INSERT INTO `stoppage_alerts` (`vehicleid`,
            `customerno`,
            `userid`,
            `is_chk_sms`,
            `is_trans_sms`,
            `is_chk_email`,
            `is_trans_email`,
            `chkmins`,
            `transmins`,
            `isdeleted`,
            `createdBy`,
            `createdOn`)
            VALUES (%d,%d,%d,%d,%d,%d,%d,%d,%d,0,%d,'%s')";
            $SQL = sprintf($Query, Sanitise::Long($vehicle->vehicleid), Sanitise::Long($user->customerno), Sanitise::Long($user->userid), Sanitise::Long($user->safcsms), Sanitise::Long($user->saftsms), Sanitise::Long($user->safcemail), Sanitise::Long($user->saftemail), Sanitise::Long($user->safcmin), Sanitise::Long($user->saftmin), $_SESSION['userid'], $datetime);
            $this->_databaseManager->executeQuery($SQL);
        }
    }

    public function modify_fuel_alerts($user, $userid) {
        $Query = "UPDATE user SET fuel_alert_sms=%d,
        fuel_alert_email=%d,
        fuel_alert_telephone=%d,
        fuel_alert_mobilenotification=%d,
        fuel_alert_percentage=%d
        WHERE userid=%d";
        $SQL = sprintf($Query, Sanitise::Long($user->fuel_alert_sms), Sanitise::Long($user->fuel_alert_email), Sanitise::Long($user->fuel_alert_telephone), Sanitise::Long($user->fuel_alert_mobile), Sanitise::Long($user->fuel_alert_percentage), Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function delete_stoppage_alerts($customerno, $userid, $user) {
        $datetime = date("Y-m-d H:i:s");
        $Query = 'UPDATE stoppage_alerts SET isdeleted=1, chkmins = 0, transmins = 0,`is_chk_sms`=%d,
                        `is_trans_sms`=%d,
                        `is_chk_email`=%d,
                        `is_trans_email`=%d,
                        `updatedBy`=%d,
                        `updatedOn`="%s" where customerno=%d AND userid=%d';
        $SQL = sprintf($Query, Sanitise::Long($user->safcsms), Sanitise::Long($user->saftsms), Sanitise::Long($user->safcemail), Sanitise::Long($user->saftemail), $_SESSION['userid'], $datetime, Sanitise::Long($customerno), Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getgroupsfromcityid($hid, $customerid) {
        $groups = array();
        $Query = sprintf("SELECT groupid FROM `group`
            where group.cityid = %d AND customerno=%d AND isdeleted=0", Validator::escapeCharacters($hid), Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $groups[] = $row["groupid"];
            }
            return $groups;
        }
        return null;
    }

    public function getgroupsfromdistrictid($hid, $customerid) {
        $groups = array();
        $Query = sprintf("SELECT groupid FROM `group`
            INNER JOIN city ON city.cityid = `group`.cityid
            where city.districtid = %d AND `group`.customerno=%d AND `group`.isdeleted=0", Validator::escapeCharacters($hid), Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $groups[] = $row["groupid"];
            }
            return $groups;
        }
        return null;
    }

    public function getgroupsfromstateid($hid, $customerid) {
        $groups = array();
        $Query = sprintf("SELECT groupid FROM `group`
            INNER JOIN city ON city.cityid = `group`.cityid
            INNER JOIN district ON district.districtid = `city`.districtid
            where district.stateid = %d AND `group`.customerno=%d AND `group`.isdeleted=0", Validator::escapeCharacters($hid), Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $groups[] = $row["groupid"];
            }
            return $groups;
        }
        return null;
    }

    public function getgroupsfromnationid($hid, $customerid) {
        $groups = array();
        $Query = sprintf("SELECT groupid FROM `group`
            INNER JOIN city ON city.cityid = `group`.cityid
            INNER JOIN district ON district.districtid = `city`.districtid
            INNER JOIN state ON state.stateid = `district`.stateid
            where state.nationid = %d AND `group`.customerno=%d AND `group`.isdeleted=0", Validator::escapeCharacters($hid), Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $groups[] = $row["groupid"];
            }
            return $groups;
        }
        return null;
    }

    public function getUserForAccountSwitch($userid) {
        $users = array();
        $Query = "SELECT account_switch.userid
                    ,account_switch.customerno
                    , customer.customercompany
                    , account_switch.childid
                    , (SELECT userkey FROM user where userid = account_switch.childid) as childuserkey
                    , (SELECT username FROM user where userid = account_switch.childid) as childusername
                    , (SELECT realname FROM user where userid = account_switch.childid) as childrealname
                    , (SELECT role FROM user where userid = account_switch.childid) as childuserrole
                    , (SELECT email FROM user where userid = account_switch.childid) as childuseremail
                    , (SELECT phone FROM user where userid = account_switch.childid) as childuserphone
                    , (SELECT notification_status FROM user where userid = account_switch.childid) as childnotificationstatus
                 FROM account_switch
                 INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = account_switch.customerno
                 INNER JOIN user on user.userid = account_switch.userid
                 WHERE account_switch.userid = %d and user.isdeleted=0 AND ((customer.use_tracking=1 AND customer.use_trace=1) OR customer.use_trace<>1)";
        $SQL = sprintf($Query, $userid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                $user->userid = $row['userid'];
                $user->customerno = $row['customerno'];
                $user->customercompany = $row['customercompany'];
                $user->childid = $row['childid'];
                $user->childuserkey = $row['childuserkey'];
                $user->childusername = $row['childusername'];
                $user->childrealname = $row['childrealname'];
                $user->childuserrole = $row['childuserrole'];
                $user->childuseremail = $row['childuseremail'];
                $user->childuserphone = $row['childuserphone'];
                $user->childnotificationstatus = $row['childnotificationstatus'];
                $users[] = $user;
            }
        }
        return $users;
    }

    public function getAllElixir($customerno) {
        $user = new VOUser();
        $uname = 'elixir' . $customerno;
        $Query = "SELECT userid, userkey FROM user WHERE username = '%s'";
        $SQL = sprintf($Query, $uname);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $user->userid = $row['userid'];
            $user->userkey = $row['userkey'];
        }
        return $user;
    }

    public function getAllElixirs() {
        $arrElixirs = array();
        $Query = "SELECT user.userid "
        . ", user.customerno "
        . ", user.userkey "
        . ", user.username "
        . ", user.realname "
        . ", user.role "
        . ", user.email "
        . ", user.phone "
        . ", user.notification_status "
        . ", customer.customercompany "
        . ", customer.temp_sensors"
        . " FROM user "
        . " INNER JOIN " . DB_PARENT . ".customer ON user.customerno = customer.customerno "
        . " WHERE user.role = '" . speedConstants::ROLE_ELIXIR . "' ORDER BY user.customerno;";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                $user->userid = $row['userid'];
                $user->customerno = $row['customerno'];
                $user->customercompany = $row['customercompany'];
                $user->temp_sensors = $row['temp_sensors'];
                $user->userkey = $row['userkey'];
                $user->username = $row['username'];
                $user->realname = $row['realname'];
                $user->email = $row['email'];
                $user->phone = $row['phone'];
                $user->role = $row['role'];
                $user->notification_status = $row['notification_status'];
                $arrElixirs[] = $user;
            }
        }
        return $arrElixirs;
    }

    public function get_total_km_tracked($data1_prev) {
        //date_default_timezone_set("Asia/Calcutta");
        //$prev = 26650000;
        $Query2 = "SELECT sum(odometer)/10000 as total FROM `vehicle`";
        $this->_databaseManager->executeQuery($Query2);
        $data1 = $this->_databaseManager->get_nextRow();
        $data1 = round($data1['total']);
        $data2 = $data1 - $data1_prev;
        // var_dump($data1);
        // var_dump($data2);
        $return = array($data1, $data2);
        return $return;
    }

    public function get_total_alerts() {
        //date_default_timezone_set("Asia/Calcutta");
        //$prev = 26650000;
        $Query2 = "SELECT max(cqid) as total FROM `comqueue`";
        $this->_databaseManager->executeQuery($Query2);
        $alert1 = $this->_databaseManager->get_nextRow();
        $alert1 = $alert1['total'];
        $alert2 = $alert1 - 100000;
        // var_dump($alert1);
        // var_dump($alert2);
        $return = array($alert1, $alert2);
        return $return;
    }

    /**
     * Get customers with email!='' and isdeleted=0 and !=elixir
     * @param type $customerno
     * @return array
     */
    public function getValidCustomers() {
        $users = array();
        $Query = "SELECT * FROM user WHERE email!='' and isdeleted=0 and role!='elixir'";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $userid = $row['userid'];
                $users[$userid] = array(
                    'email' => $row['email']
                );
            }
        }
        return $users;
    }

    public function get_all_users() {
        $users = array();
        $Query = "SELECT * FROM user WHERE isdeleted=0 and role!='elixir'";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $userid = $row['userid'];
                foreach ($row as $column_name => $value) {
                    if (is_int($column_name)) {
                        continue;
                    }
                    $users[$userid][$column_name] = $value;
                }
            }
        }
        return $users;
    }

    public function get_all_pickup($customerno) {
        $users = array();
        $Query = "SELECT * FROM user WHERE customerno=$customerno and isdeleted=0 and role='Pickup'";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $userid = $row['userid'];
                foreach ($row as $column_name => $value) {
                    if (is_int($column_name)) {
                        continue;
                    }
                    $users[$userid][$column_name] = $value;
                }
            }
        }
        return $users;
    }

    public function get_userid_bykey($user_key, $customer_id) {
        $SQL = "SELECT userid FROM user WHERE isdeleted=0 and customerno=%d and userkey='%s'";
        $Query = sprintf($SQL, Validator::escapeCharacters($customer_id), Validator::escapeCharacters($user_key));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['userid'];
            }
        }
        return null;
    }

    public function insert_viewed_report($data) {
        $timestamp = date('Y-m-d H:i:s');
        $Query = "INSERT INTO reports_viewed Values(null, %d, %d, %d, '%s', '%s', '%s', '$timestamp')";
        $sql = sprintf($Query, Validator::escapeCharacters($data['cust_id']), Validator::escapeCharacters($data['userid']), Validator::escapeCharacters($data['vehicleid']), Validator::escapeCharacters($data['report_date']), Validator::escapeCharacters($data['report_name']), Validator::escapeCharacters($data['file_type']));
        $this->_databaseManager->executeQuery($sql);
    }

    /**
     * Used in delivery apis
     */
    public function get_delivery_person_details($username, $password) {
        $SQL = "SELECT user.customerno,user.userkey,customer.customercompany,user.delivery_vehicleid,user.realname
        FROM user INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = user.customerno
        WHERE user.`isdeleted`=0 and user.`username`='%s' and user.`password`='%s'";
        $Query = sprintf($SQL, Validator::escapeCharacters($username), Validator::escapeCharacters($password));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data = array(
                    'customerno' => $row['customerno'],
                    'userkey' => $row['userkey'],
                    'company' => $row['customercompany'],
                    'vehicleid' => $row['delivery_vehicleid'],
                    'realname' => $row['realname'],
                    'result' => 'success'
                );
                return $data;
            }
        }
        $data = array('result' => 'failure');
        return $data;
    }

    /**
     * Used in secondary sales apis
     */
    public function get_person_details($username, $password) {
        $SQL = "SELECT u.userid,u.customerno,u.userkey,u.role,u.realname,c.customercompany
        FROM user as u  left join " . DB_PARENT . ".customer as c on c.customerno = u.customerno
        WHERE isdeleted=0  AND username='%s' and password='%s'";
        $Query = sprintf($SQL, Validator::escapeCharacters($username), Validator::escapeCharacters($password));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data = array(
                    'userkey' => $row['userkey'],
                    'realname' => $row['realname'],
                    'customercompany' => $row['customercompany'],
                    'role' => $row['role'],
                    'customerno' => $row['customerno'],
                    'userid' => $row['userid']
                );
            }
            return $data;
        }
        return NULL;
    }

    /**
     * Used in delivery apis, sec-sales-api for getting user details by key
     */
    public function get_person_details_by_key($userkey) {
        $SQL = "SELECT user.customerno,userid,role,roleid,delivery_vehicleid,use_erp,booksUserToken
        FROM user
        INNER JOIN " . DB_PARENT . ".customer on customer.customerno = user.customerno
        WHERE isdeleted=0 and userkey='%s'";
        $Query = sprintf($SQL, Validator::escapeCharacters($userkey));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data = array(
                    'customerno' => $row['customerno'],
                    'userid' => $row['userid'],
                    'role' => $row['role'],
                    'roleid' => $row['roleid'],
                    'vehicleid' => $row['delivery_vehicleid'],
                    'use_erp' => $row['use_erp'],
                    'booksUserToken' => $row['booksUserToken']
                );
                return $data;
            }
        }
        return null;
    }

    public function getuserdata_from_username($username) {
        $userdata = array();
        $SQL = "SELECT * FROM user WHERE isdeleted=0 and username='%s' ORDER BY userid DESC";
        $Query = sprintf($SQL, Validator::escapeCharacters($username));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $userdata['userid'] = $row['userid'];
            $userdata['customerno'] = $row['customerno'];
            $userdata['email'] = $row['email'];
            $userdata['phone'] = $row['phone'];
            return $userdata;
        } else {
            return null;
        }
    }

    public function update_newpwd($newpwd, $userkey) {
        $res = null;
        $pdo = $this->_databaseManager->CreatePDOConn();
        $today = date("Y-m-d H:i:s");
        $sp_params = "'" . $newpwd . "'"
            . ",'" . $userkey . "'"
            . ",'" . $today . "'"
        ;
        $QUERY = $this->PrepareSP('update_newforgotpassword', $sp_params);
        if ($result = $pdo->query($QUERY)) {
            $res = 1;
        }
        $this->_databaseManager->ClosePDOConn($pdo);
        return $res;
    }

    public function getuserlistformap($userstring, $userids) {
        $whereuser = '';
        $getdata = array();
        if (!empty($userids)) {
            $whereuser = " AND userid NOT IN(" . $userids . ")  ";
        }
        $Query = "SELECT * FROM `user` where realname LIKE '%s' AND customerno=%d and isdeleted=0 and role='Viewer'" . $whereuser;
        $userQuery = sprintf($Query, Sanitise::String($userstring), $_SESSION['customerno']);
        $this->_databaseManager->executeQuery($userQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['userid'],
                    'value' => $row['realname']
                );
            }
            return $getdata;
        }
        return null;
    }

    public function authenticate_userkey($userkey, $isApiUser = false) {
        $authenticatedUser = null;
        $authenticationQuery = sprintf("SELECT `userid`, `customerno` , `username`, `visited`
          FROM `user` WHERE sha1(userkey)='%s' AND isdeleted=0 limit 1"
            , Validator::escapeCharacters($userkey));
        $this->_databaseManager->executeQuery($authenticationQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $authenticatedUser = $this->get_user($row['customerno'], $row['userid']);
        }
        return $authenticatedUser;
    }

    public function authenticate_userkey_map($userkey) {
        $authenticatedUser = null;
        $authenticationQuery = sprintf("SELECT `userid`, `customerno` , `username`, `visited`
          FROM `user` WHERE sha1(`userkey`)='%s' AND isdeleted=0 limit 1"
            , Validator::escapeCharacters($userkey));
        $this->_databaseManager->executeQuery($authenticationQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $authenticatedUser = $this->get_user($row['customerno'], $row['userid']);
        }
        return $authenticatedUser;
    }

    public function getloginhistorydata($sdate, $stime, $edate, $etime, $customerno) {
        $data = array();
        $sdate = date('Y-m-d', strtotime($sdate));
        $edate = date('Y-m-d', strtotime($edate));
        $sdatetime = $sdate . " " . $stime;
        $edatetime = $edate . " " . $etime;
        $monthfrom = date('M', strtotime($sdate));
        $monthto = date('M', strtotime($edate));
        $year = date('Y', strtotime($sdate));
        $currdate = date('Y-m-d');
        if ($currdate == $sdate) {
            $Query = "SELECT user.role,user.realname,page_master.page_master_name,user.username,customer.customercompany,lh.created_on, CASE WHEN page_master.is_web=1 THEN 'Mobile' ELSE 'Website' END as machine from " . DB_PARENT . ".login_history_details as lh
            INNER JOIN user on user.userid = lh.created_by AND user.role<>'elixir'
            INNER JOIN page_master on page_master.page_master_id=lh.page_master_id
            INNER JOIN " . DB_PARENT . ".customer on customer.customerno = lh.customerno where lh.customerno=%d AND lh.`created_on` BETWEEN  '%s' AND '%s'";
            $loginQuery = sprintf($Query, $customerno, Sanitise::String($sdatetime), Sanitise::String($edatetime));
            $this->_databaseManager->executeQuery($loginQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $data[] = array(
                        'realname' => $row['realname'],
                        'username' => $row['username'],
                        'customercompany' => $row['customercompany'],
                        'timestamp' => $row['created_on'],
                        'machine' => $row['machine'],
                        'role' => $row['role'],
                        'page_name' => $row['page_master_name']
                    );
                }
                return $data;
            }
        } elseif ($monthfrom != $monthto) {
            return null;
        } else {
            $location = "../../customer/$customerno/history/$monthfrom$year.sqlite";
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $db = new PDO($path);
                $query = "SELECT userid,customerno,timestamp,phonetype FROM loginhistorynew WHERE timestamp between  '$sdatetime' AND '$edatetime' ORDER BY timestamp DESC";
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $status = $row['phonetype'];
                        $chkuser = $this->GetUserRole($row['userid']);
                        if ($chkuser->role != 'elixir' && $chkuser->role != '') {
                            if ($status == 1) {
                                $status = "Mobile";
                            } else {
                                $status = "Web";
                            }
                            $data[] = array(
                                'realname' => $chkuser->realname,
                                'username' => $chkuser->username,
                                'customercompany' => $chkuser->customercompany,
                                'timestamp' => $row['timestamp'],
                                'machine' => $status,
                                'role' => $chkuser->role
                            );
                        }
                    }
                }
            }
            if ($edate == $currdate) {
                $curdata = array();
                $sdatetime = $edate . " " . "00:00";
                $edatetime = $edate . " " . $etime;
                $Query = "select  user.role,user.realname,user.username,page_master.page_master_name,customer.customercompany,lh.created_on, CASE WHEN page_master.is_web=1 THEN 'Mobile' ELSE 'Website' END as machine
                    from login_history_details as lh
                inner join user on user.userid = lh.created_by AND user.role <> 'elixir'
                inner join page_master on page_master.page_master_id=lh.page_master_id
                inner join " . DB_PARENT . ".customer on customer.customerno = lh.customerno where lh.customerno=%d AND lh.`created_on` BETWEEN  '%s' AND '%s'";
                $loginQuery = sprintf($Query, $customerno, Sanitise::String($sdatetime), Sanitise::String($edatetime));
                $this->_databaseManager->executeQuery($loginQuery);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        $curdata[] = array(
                            'realname' => $row['realname'],
                            'username' => $row['username'],
                            'customercompany' => $row['customercompany'],
                            'timestamp' => $row['created_on'],
                            'machine' => $row['machine'],
                            'role' => $row['role'],
                            'page_name' => $row['page_master_name']
                        );
                    }
                }
                $data = array_merge($curdata, $data);
            }
            return $data;
        }
        return null;
    }

    public function GetUserRole($userid) {
        $SQL = "SELECT user.role,user.realname,user.username,customer.customercompany,customer.customerno from user
                Inner join " . DB_PARENT . ".customer on customer.customerno = user.customerno
                WHERE user.userid=%d";
        $Query = sprintf($SQL, $userid);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new stdClass();
                $vehicle->role = $row['role'];
                $vehicle->realname = $row['realname'];
                $vehicle->username = $row['username'];
                $vehicle->customercompany = $row['customercompany'];
                $vehicle->customerno = $row['customerno'];
                return $vehicle;
            }
        }
        return null;
    }

    public function GetGroupIdFromVehicleid($vehicleid, $customerno) {
        $Query = "select groupid from vehicle where vehicleid = %d and customerno=%d and isdeleted = 0";
        $SQL = sprintf($Query, $vehicleid, $customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $group[] = $row;
            }
            foreach ($group as $data) {
                return $data['groupid'];
            }
        }
    }

    public function getadministrator($customerid) {
        //for customers admin only
        $users = array();
        $usersQuery = sprintf("SELECT * FROM `user` where user.customerno=%d AND user.roleid = 5 AND  user.isdeleted=0 Group By user.username", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new VOUser();
                $user->id = $row['userid'];
                $user->username = $row['username'];
                $user->role = $row['role'];
                $user->roleid = $row['roleid'];
                $user->email = $row['email'];
                $user->phone = $row['phone'];
                $user->realname = $row['realname'];
                $user->lastvisit = $row['lastvisit'];
                $user->visits = $row['visited'];
                $user->userkey = $row['userkey'];
                $user->customerno = $row['customerno'];
                $user->groups = $row['groupid'];
                $user->mess_email = $row["mess_email"];
                $user->mess_sms = $row["mess_sms"];
                $user->mess_telephone = $row["mess_telephone"];
                $user->speed_email = $row["speed_email"];
                $user->speed_sms = $row["speed_sms"];
                $user->speed_telephone = $row["speed_telephone"];
                $user->speed_mobilenotification = $row["speed_mobilenotification"];
                $user->power_email = $row["power_email"];
                $user->power_telephone = $row["power_telephone"];
                $user->power_mobilenotification = $row["power_mobilenotification"];
                $user->power_sms = $row["power_sms"];
                $user->tamper_email = $row["tamper_email"];
                $user->tamper_sms = $row["tamper_sms"];
                $user->tamper_telephone = $row["tamper_telephone"];
                $user->tamper_mobilenotification = $row["tamper_mobilenotification"];
                $user->chk_email = $row["chk_email"];
                $user->chk_sms = $row["chk_sms"];
                $user->chk_telephone = $row["chk_telephone"];
                $user->chk_mobilenotification = $row["chk_mobilenotification"];
                $user->ac_email = $row["ac_email"];
                $user->ac_sms = $row["ac_sms"];
                $user->ac_telephone = $row["ac_telephone"];
                $user->ac_mobilenotification = $row["ac_mobilenotification"];
                $user->aci_email = $row["aci_email"];
                $user->aci_sms = $row["aci_sms"];
                $user->aci_telephone = $row["aci_telephone"];
                $user->aci_mobilenotification = $row["aci_mobilenotification"];
                $user->ignition_email = $row["ignition_email"];
                $user->ignition_sms = $row['ignition_sms'];
                $user->ignition_telephone = $row['ignition_telephone'];
                $user->ignition_mobilenotification = $row['ignition_mobilenotification'];
                $user->temp_email = $row['temp_email'];
                $user->temp_sms = $row['temp_sms'];
                $user->temp_telephone = $row['temp_telephone'];
                $user->temp_mobilenotification = $row['temp_mobilenotification'];
                $user->panic_email = $row['panic_email'];
                $user->panic_sms = $row['panic_sms'];
                $user->panic_telephone = $row['panic_telephone'];
                $user->panic_mobilenotification = $row['panic_mobilenotification'];
                $user->door_email = $row['door_email'];
                $user->door_sms = $row['door_sms'];
                $user->door_telephone = $row['door_telephone'];
                $user->door_mobilenotification = $row['door_mobilenotification'];
                $user->start_alert_time = $row['start_alert'];
                $user->stop_alert_time = $row['stop_alert'];
                $user->dailyemail = $row['dailyemail'];
                $user->dailyemail_csv = $row['dailyemail_csv'];
                $user->notification_status = $row['notification_status'];
                $user->gcmid = $row['gcmid'];
                $users[] = $user;
            }
            return $users;
        }
        return null;
    }

    public function getAllROle($rolename) {
        $array = array();
        $SQL = "SELECT * from user
                Inner join " . DB_PARENT . ".customer on customer.customerno = user.customerno
                WHERE user.role='%s' and user.realname not like 'Elixir' and user.phone<>''";
        $Query = sprintf($SQL, $rolename);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOUser();
                $vehicle->userid = $row['userid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->phone = $row['phone'];
                $vehicle->realname = $row['realname'];
                $array[] = $vehicle;
            }
            return $array;
        }
        return null;
    }

    public function getPageMasterId($page) {
        $SQL = sprintf("SELECT page_master_id FROM page_master WHERE page_url='%s'", $page);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            return $row['page_master_id'];
        }
        return null;
    }

    //<editor-fold defaultstate="collapsed" desc="User Aalert Reports">
    public function getReportsMaster($customerno) {
        $reportsMaster = array();
        $usersQuery = sprintf("SELECT reportId,is_warehouse,reportName FROM reportMaster WHERE customerno IN (0, " . $customerno . ") AND isdeleted = 0");
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $report = new stdClass();
                $report->reportId = $row['reportId'];
                $report->reportName = $row['reportName'];
                $report->is_warehouse = $row['is_warehouse'];
                $reportsMaster[] = $report;
            }
        }
        return $reportsMaster;
    }

    public function getUserReports($userid, $customerno) {
        $reportsMaster = array();
        $Query = "SELECT userReportId, urm.reportId as mappedUserId, rm.reportId, rm.reportName, rm.is_warehouse , urm.reportTime, urm.isActivated
        FROM reportMaster as rm
                    left OUTER JOIN userReportMapping as urm on rm.reportId = urm.reportId and urm.userid = %d and urm.customerno = %d and urm.isdeleted = 0
        WHERE  rm.isdeleted = 0 AND rm.customerno IN (0, " . $customerno . ")order by rm.reportId ASC";
        $usersQuery = sprintf($Query, $userid, $customerno);
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $report = new stdClass();
                $report->userReportId = $row['userReportId'];
                $report->mappedUserId = $row['mappedUserId'];
                $report->reportId = $row['reportId'];
                $report->reportName = $row['reportName'];
                $report->is_warehouse = $row['is_warehouse'];
                $report->reportTime = $row['reportTime'];
                $report->isActivated = $row['isActivated'];
                $reportsMaster[] = $report;
            }
        }
        return $reportsMaster;
    }

    public function updateUserReports($objUser) {
        $today = date('Y-m-d h:i:s');
        if (isset($objUser->reports)) {
            $Query = "Update userReportMapping SET isActivated = 0, updated_by=" . $objUser->created_by . ", updated_on='" . $today . "' where userid=" . $objUser->userid . " AND customerno = " . $objUser->customerno . " AND isdeleted = 0";
            $this->_databaseManager->executeQuery($Query);
            foreach ($objUser->reports as $report) {
                if ($report->reportId != 0) {
                    $Query = "SELECT reportId, isActivated From userReportMapping where reportid =%d AND  userid=%d AND customerno = %d AND isdeleted = 0";
                    $usersQuery = sprintf($Query, $report->reportId, $objUser->userid, $objUser->customerno);
                    $this->_databaseManager->executeQuery($usersQuery);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        $Query = "Update userReportMapping SET reportTime=" . $report->reportTime . ", isActivated = 1, updated_by=" . $objUser->created_by . ", updated_on='" . $today . "' where reportid =" . $report->reportId . " AND  userid=" . $objUser->userid . " AND customerno = " . $objUser->customerno . " AND isdeleted = 0";
                        $this->_databaseManager->executeQuery($Query);
                    } else {
                        $Query = "INSERT INTO userReportMapping(reportId,reportTime, isActivated, userid,customerno,created_by,created_on)VALUES(%d,%d,1,%d,%d,%d,'%s')";
                        $SQL = sprintf($Query
                            , Sanitise::Long($report->reportId)
                            , Sanitise::Long($report->reportTime)
                            , Sanitise::Long($objUser->userid)
                            , Sanitise::Long($objUser->customerno)
                            , Sanitise::Long($objUser->created_by)
                            , Sanitise::DateTime($today));
                        $this->_databaseManager->executeQuery($SQL);
                    }
                }
            }
        }
        if (isset($objUser->temprepinterval)) {
            $Query = "  SELECT  `trid`
                        FROM    `tempreportinterval`
                        WHERE   `userid` = %d
                        AND     `customerno` = %d
                        AND     `isdeleted` = 0";
            $usersQuery = sprintf($Query, $objUser->userid, $objUser->customerno);
            $this->_databaseManager->executeQuery($usersQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "  Update  `tempreportinterval`
                            SET     `interval` = " . $objUser->temprepinterval . "
                                    ,`updatedby` = " . $objUser->created_by . "
                                    ,`updatedon` ='" . $today . "'
                            WHERE   `userid` = " . $objUser->userid . "
                            AND     `customerno` = " . $objUser->customerno . "
                            AND     `isdeleted` = 0";
                $this->_databaseManager->executeQuery($Query);
            } else {
                $Query = "INSERT INTO `tempreportinterval`(`userid`, `customerno`, `interval`, `createdby`, `createdon`) VALUES (%d,%d,%d,%d,'%s')";
                $SQL = sprintf($Query
                    , Sanitise::Long($objUser->userid)
                    , Sanitise::Long($objUser->customerno)
                    , Sanitise::Long($objUser->temprepinterval)
                    , Sanitise::Long($objUser->userid)
                    , Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        if (isset($objUser->vehrepinterval)) {
            $Query = "  SELECT  `vrid`
                        FROM    `vehrepinterval`
                        WHERE   `userid` = %d
                        AND     `customerno` = %d
                        AND     `isdeleted` = 0";
            $usersQuery = sprintf($Query, $objUser->userid, $objUser->customerno);
            $this->_databaseManager->executeQuery($usersQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "  Update  `vehrepinterval`
                            SET     `interval` = " . $objUser->vehrepinterval . "
                                    ,`updatedby` = " . $objUser->created_by . "
                                    ,`updatedon` ='" . $today . "'
                            WHERE   `userid` = " . $objUser->userid . "
                            AND     `customerno` = " . $objUser->customerno . "
                            AND     `isdeleted` = 0";
                $this->_databaseManager->executeQuery($Query);
            } else {
                $Query = "INSERT INTO `vehrepinterval`(`userid`, `customerno`, `interval`, `createdby`, `createdon`) VALUES (%d,%d,%d,%d,'%s')";
                $SQL = sprintf($Query
                    , Sanitise::Long($objUser->userid)
                    , Sanitise::Long($objUser->customerno)
                    , Sanitise::Long($objUser->vehrepinterval)
                    , Sanitise::Long($objUser->userid)
                    , Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
    }

    public function getUsersForReportSecondarySales($objUser) {
        $users = array();
        $SQL = "SELECT urm.userid, urm.customerno, urm.reportId, urm.reportTime, u.email, u.realname, u.userkey, u.role, u.roleid
        FROM userReportMapping as urm
        INNER JOIN user as u on u.userid = urm.userid
        INNER JOIN customer c on c.customerno = u.customerno
        WHERE urm.reportId=%d AND urm.reportTime=%d AND urm.isActivated = 1 AND urm.isdeleted=0 AND u.isdeleted=0";
        $Query = sprintf($SQL, $objUser->reportId, $objUser->reportTime);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                $user->reportId = $row['reportId'];
                $user->reportTime = $row['reportTime'];
                $user->userid = $row['userid'];
                $user->email = $row['email'];
                $user->realname = $row['realname'];
                $user->userkey = $row['userkey'];
                $user->userrole = $row['role'];
                $user->roleid = $row['roleid'];
                $user->customerno = $row['customerno'];
                $users[] = $user;
            }
        }
        return $users;
    }

    public function getUsersForReport($objUser) {
        $users = array();
        $str = $objUser->reportTime . ' %';
        $SQL = "SELECT  urm.userid
                        , urm.customerno
                        , urm.reportId
                        , urm.reportTime
                        , u.email
                        , u.realname
                        , u.userkey
                        , u.role
                        , u.roleid
                        , VR.interval
                        , urm.iterativeReportHour
                FROM    userReportMapping as urm
                INNER JOIN user as u on u.userid = urm.userid
                INNER JOIN customer c on c.customerno = u.customerno
                LEFT JOIN vehrepinterval VR ON VR.userid = u.userid
                WHERE   urm.reportId = %d
                AND     (
                            (
                                urm.iterativeReportHour > 0
                                AND
                                (
                                    (%s (urm.reportTime + urm.iterativeReportHour) = 0 AND %d / (urm.reportTime + urm.iterativeReportHour) < 2)
                                    OR
                                    (%s (urm.reportTime + (2 * urm.iterativeReportHour)) = 0)
                                    OR
                                    (%s (urm.reportTime + (3 * urm.iterativeReportHour)) = 0)
                                    OR
                                    (%s (urm.reportTime + (4 * urm.iterativeReportHour)) = 0)
                                    OR
                                    (%s (urm.reportTime + (5 * urm.iterativeReportHour)) = 0)
                                    OR
                                    (%s (urm.reportTime + (6 * urm.iterativeReportHour)) = 0)
                                    OR
                                    (%s (urm.reportTime + (7 * urm.iterativeReportHour)) = 0)
                                    OR
                                    urm.reportTime = %d
                                )
                            )
                            OR
                            urm.reportTime = %d
                        )
                AND     urm.isActivated = 1
                AND     urm.isdeleted = 0
                AND     u.isdeleted=0";
        $Query = sprintf($SQL, $objUser->reportId, $str, $objUser->reportTime, $str, $str, $str, $str, $str, $str, $objUser->reportTime, $objUser->reportTime);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                $user->reportId = $row['reportId'];
                $user->reportTime = $row['reportTime'];
                $user->userid = $row['userid'];
                $user->email = $row['email'];
                $user->realname = $row['realname'];
                $user->userkey = $row['userkey'];
                $user->userrole = $row['role'];
                $user->roleid = $row['roleid'];
                $user->customerno = $row['customerno'];
                $user->interval = $row['interval'];
                $user->iterativeReportHour = $row['iterativeReportHour'];
                $users[] = $user;
            }
        }
        return $users;
    }

    public function getUsersForReportFuel() {
        $users = array();
        $SQL = "select u.userid,u.phone,u.fuel_alert_percentage,u.email,u.realname, u.userkey,u.role,u.roleid,u.realname,u.customerno "
            . " from user as u "
            . " left outer join " . DB_PARENT . ".`customer` as c ON c.customerno = u.customerno "
            . " where c.use_fuel_sensor=1 AND u.isdeleted=0 "
            . " ORDER BY `u`.`userid` DESC ";
        $Query = sprintf($SQL);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                if ($row['role'] != 'elixir') {
                    $user->userid = $row['userid'];
                    $user->email = $row['email'];
                    $user->phone = $row['phone'];
                    $user->realname = $row['realname'];
                    $user->userkey = $row['userkey'];
                    $user->userrole = $row['role'];
                    $user->roleid = $row['roleid'];
                    $user->customerno = $row['customerno'];
                    $user->fuel_alert_percentage = $row['fuel_alert_percentage'];
                    $users[] = $user;
                }
            }
        }
        return $users;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Checkpoint Exception">
    public function setUserAlert($objUser, $isModify = null) {
        $objAlert = new stdClass();
        $objAlert->alertId = 1;
        $objAlert->userId = $objUser->userid;
        $objAlert->customerno = $objUser->customerno;
        $objAlert->created_by = $_SESSION['userid'];
        $objAlert->today = date('Y-m-d h:i:s');
        $objAlert->alertTypeId = 0;
        if (isset($objUser->chkptExSms)) {
            $objAlert->alertTypeId = 1;
            $objAlert->isActive = $objUser->chkptExSms;
            if (isset($isModify)) {
                $this->modifyUserAlert($objAlert);
            } else {
                $this->insertUserAlert($objAlert);
            }
        }
        if (isset($objUser->chkptExEmail)) {
            $objAlert->alertTypeId = 2;
            $objAlert->isActive = $objUser->chkptExEmail;
            if (isset($isModify)) {
                $this->modifyUserAlert($objAlert);
            } else {
                $this->insertUserAlert($objAlert);
            }
        }
        if (isset($objUser->chkptExTelephone)) {
            $objAlert->alertTypeId = 3;
            $objAlert->isActive = $objUser->chkptExTelephone;
            if (isset($isModify)) {
                $this->modifyUserAlert($objAlert);
            } else {
                $this->insertUserAlert($objAlert);
            }
        }
        if (isset($objUser->chkptExMobile)) {
            $objAlert->alertTypeId = 4;
            $objAlert->isActive = $objUser->chkptExMobile;
            if (isset($isModify)) {
                $this->modifyUserAlert($objAlert);
            } else {
                $this->insertUserAlert($objAlert);
            }
        }
        /*
        Changes Made By : Pratik Raut
        Change : if isModify is empty then InsertUserAlert
        Date : 12-09-2019
         */
        if (empty($isModify)) {
            $objAlert->isActive = 1;
            $this->insertUserAlert($objAlert);
        }
        /*
    Changes Ends Here
     */
    }

    private function insertUserAlert($objAlert) {
        $Sql = "INSERT INTO userAlertMapping(userId, alertId, alertTypeId, isActive, customerno, created_by, created_on) Values(%d,%d,%d,%d,%d,%d,'%s')";
        $Query = sprintf($Sql, $objAlert->userId, $objAlert->alertId, $objAlert->alertTypeId, $objAlert->isActive, $objAlert->customerno, $objAlert->created_by, $objAlert->today);
        $this->_databaseManager->executeQuery($Query);
    }

    private function modifyUserAlert($objAlert) {
        $Sql = "UPDATE userAlertMapping SET isActive = %d, updated_by=%d, updated_on='%s' WHERE userId = %d AND alertId=%d AND customerno=%d and alertTypeId=%d and isdeleted=0";
        $Query = sprintf($Sql, $objAlert->isActive, $objAlert->created_by, $objAlert->today, $objAlert->userId, $objAlert->alertId, $objAlert->customerno, $objAlert->alertTypeId);
        $this->_databaseManager->executeQuery($Query);
    }

    private function insertUserExceptionMapping($objException) {
        $objException->today = date('Y-m-d h:i:s');
        $exceptions = isset($objException->chkExUserMapping) ? explode(",", $objException->chkExUserMapping) : null;
        $Query = "INSERT INTO `chkptExUserMapping` (`chkExId`,`userId`,`customerno`,`created_by`,`created_on`) VALUES (%d,%d,%d,%d,'%s')";
        if (isset($exceptions)) {
            foreach ($exceptions as $id) {
                $SQL = sprintf($Query
                    , Sanitise::Long($id)
                    , Sanitise::Long($objException->userid)
                    , Sanitise::Long($objException->customerno)
                    , $_SESSION['userid']
                    , $objException->today);
                $this->_databaseManager->executeQuery($SQL);
            }
        }
    }

    public function modifyUserExceptionMapping($objException) {
        $Query = "UPDATE chkptExUserMapping SET isdeleted=1, updated_by=%d, updated_on='%s' WHERE userId=%d AND customerno = %d";
        $SQL = sprintf($Query, $_SESSION['userid'], Sanitise::DateTime($objException->today), Sanitise::Long($objException->userid), Sanitise::Long($objException->customerno));
        $this->_databaseManager->executeQuery($SQL);
        $this->insertUserExceptionMapping($objException);
    }

    public function getUserAlertMapping($objUser) {
        $exceptions = array();
        $Query = "SELECT uam.alertMappingId, uam.userId, uam.alertId, uam.alertTypeId, uam.isActive
        FROM userAlertMapping as uam
        INNER JOIN user as u on u.userid = uam.userId
        WHERE  uam.userId = %d AND uam.customerno = %d AND uam.isdeleted = 0 AND u.isdeleted=0 order by uam.alertMappingId ASC, uam.alertTypeId ASC";
        $usersQuery = sprintf($Query, $objUser->userid, $objUser->customerno);
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $alert = new stdClass();
                $alert->alertMappingId = $row['alertMappingId'];
                $alert->userId = $row['userId'];
                $alert->alertId = $row['alertId'];
                $alert->alertTypeId = $row['alertTypeId'];
                $alert->isActive = $row['isActive'];
                $alert->status = "";
                if ($row['isActive'] == 1) {
                    $alert->status = "Checked";
                }
                $exceptions[] = $alert;
            }
        }
        return $exceptions;
    }

    public function getUserExceptionMapping($objUser) {
        $exceptions = array();
        $exCondition = '';
        $userExCondition = '';
        if (isset($objUser->exceptionId)) {
            $exCondition = " AND um.chkExId = " . $objUser->exceptionId;
        }
        if (isset($objUser->userid)) {
            $userExCondition = " AND um.userId = " . $objUser->userid;
        }
        $Query = "SELECT distinct(um.chkExId), um.userId, u.phone,u.email,u.gcmid, u.customerno
        FROM chkptExUserMapping as um
        INNER JOIN user as u on u.userid = um.userId
        WHERE um.customerno = %d " . $userExCondition . $exCondition . " AND um.isdeleted = 0 AND u.isdeleted=0";
        $usersQuery = sprintf($Query, $objUser->customerno);
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $exception = new stdClass();
                $exception->chkExId = $row['chkExId'];
                $exception->userId = $row['userId'];
                $exception->phone = $row['phone'];
                $exception->email = $row['email'];
                $exception->gcmid = $row['gcmid'];
                $exception->customerno = $row['customerno'];
                $exceptions[] = $exception;
            }
        }
        return $exceptions;
    }

    //</editor-fold>
    public function updateRefreshTime($refreshTime) {
        $Query = "UPDATE user set refreshtime=%d WHERE userid=%d";
        $SQL = sprintf($Query, $refreshTime, Sanitise::Long($_SESSION['userid']));
        $this->_databaseManager->executeQuery($SQL);
    }

    //<editor-fold defaultstate="collapsed" desc="2-Way Authentication">
    public function check2WayAuthUser($userid) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $usrforgot = array();
        $today = date('Y-m-d H:i:s');
        $sp_params = "'" . $userid . "'"
            . ",'" . $today . "'";
        $QUERY = $this->PrepareSP('multiauth_request', $sp_params);
        $row1 = $pdo->query($QUERY)->fetch(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        $usrforgot['userid'] = $userid;
        $usrforgot['otp'] = $row1['otpparam'];
        $usrforgot['otpvalidupto'] = $row1['otpvalidupto'];
        $usrforgot['userphone'] = $row1['userphone'];
        $usrforgot['customerno'] = $row1['custno'];
        return $usrforgot;
    }

    public function validateOtpFor2WayAuthentication($userid, $otp) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $validationStatus = 0;
        $today = date('Y-m-d H:i:s');
        $sp_params = "'" . $userid . "'"
            . ",'" . $otp . "'"
            . ",'" . $today . "'"
            . ",@validStatus";
        $QUERY = $this->PrepareSP('validate_Otp_2WayAuthentication', $sp_params);
        $row1 = $pdo->query($QUERY)->fetch(PDO::FETCH_ASSOC);
        $OutQuery = "SELECT @validStatus AS validStatus";
        if ($result = $pdo->query($OutQuery)) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $validationStatus = $row['validStatus'];
        }
        $this->_databaseManager->ClosePDOConn($pdo);
        return $validationStatus;
    }

    //</editor-fold>
    public function getAdminUser($customerno) {
        $getdata = array();
        $Query = "SELECT * FROM `user` where customerno=%d and isdeleted=0 and role='Administrator'";
        $userQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($userQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $getdata = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $getdata[] = array(
                    'email' => $row['email'],
                    'userid' => $row['userid'],
                    'userkey' => $row['userkey'],
                    'realname' => $row['realname'],
                    'username' => $row['username'],
                    'customerno' => $row['customerno']
                );
            }
            return $getdata;
        }
        return null;
    }

    public function add_usermenu_mapping_all($userid, $moduleid, $customerno) {
        $todaysdate = date("Y-m-d H:i:s");
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $moduleid . "'"
            . ",'" . $customerno . "'"
            . ",'" . $todaysdate . "'"
            . ",'" . $userid . "'"
            . ",'" . $userid . "'";
        $queryCallSP = $this->PrepareSP('insert_default_menu_all_customer', $sp_params);
        $pdo->query($queryCallSP);
        $this->_databaseManager->ClosePDOConn($pdo);
    }

    public function maintenance_userslist($customerno, $userid, $roleid) {
        $getdata = array();
        $roleid = 37;
        $Query = "select  u.userid,u.username, u.email, u.realname, u.phone,u.role, g.code AS branchcode, g.groupname AS branchname, c.code AS regioncode, c.name AS regionname,
        d.code AS zonecode, d.name AS zonename,vehicle_movement_alert,u.customerno
      ,(SELECT realname FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) AS regionalUserName
        ,(SELECT username FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) AS regionalUserSAP
        ,(SELECT email FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) AS regionalUserSAPEmail
        ,(SELECT phone FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) AS regionalUserSAPPhone
        , (SELECT realname FROM user WHERE userid = (SELECT heirarchy_id FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) and user.isdeleted=0) AS zonalUserName
        , (SELECT username FROM user WHERE userid = (SELECT heirarchy_id FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) and user.isdeleted=0) AS zonalUserSAP
        , (SELECT email FROM user WHERE userid = (SELECT heirarchy_id FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) and user.isdeleted=0) AS zonalUserSAPEmail
        , (SELECT phone FROM user WHERE userid = (SELECT heirarchy_id FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) and user.isdeleted=0) AS zonalUserSAPPhone
        from groupman gm
        inner join user u on u.userid = gm.userid
        INNER JOIN `group` g ON g.groupid = gm.groupid
        INNER JOIN city c ON c.cityid = g.cityid
        INNER JOIN district d ON d.districtid = c.districtid
        INNER JOIN state s ON s.stateid = d.stateid
        INNER JOIN nation n ON n.nationid = s.nationid
        WHERE gm.isdeleted = 0
        and g.customerno =" . $customerno . "
        and g.isdeleted = 0
        and u.customerno = " . $customerno . "
        and u.isdeleted = 0
        and u.roleid = " . $roleid . "
        order by u.email, branchname, regionname, zonename;";
        $userQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($userQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $getdata = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $getdata[] = array(
                    'userid' => $row['userid'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'realname' => $row['realname'],
                    'phone' => $row['phone'],
                    'role' => $row['role'],
                    'branchcode' => $row['branchcode'],
                    'branchname' => $row['branchname'],
                    'regioncode' => $row['regioncode'],
                    'regionname' => $row['regionname'],
                    'zonecode' => $row['zonecode'],
                    'zonename' => $row['zonename'],
                    'regionalUserName' => $row['regionalUserName'],
                    'regionalUserSAP' => $row['regionalUserSAP'],
                    'regionalUserSAPEmail' => $row['regionalUserSAPEmail'],
                    'regionalUserSAPPhone' => $row['regionalUserSAPPhone'],
                    'zonalUserSAP' => $row['zonalUserSAP'],
                    'zonalUserName' => $row['zonalUserName'],
                    'zonalUserSAPEmail' => $row['zonalUserSAPEmail'],
                    'zonalUserSAPPhone' => $row['zonalUserSAPPhone'],
                    'vehicle_movement_alert' => $row['vehicle_movement_alert'],
                    'customerno' => $row['customerno']
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getalltickettype() {
        $Query = "SELECT * FROM " . DB_PARENT . ".`sp_tickettype` where isdeleted = 0";
        $timeslotQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($timeslotQuery);
        $typedata = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $typedata[] = array(
                    'typeid' => $row['typeid'],
                    'tickettype' => $row['tickettype']
                );
            }
            return $typedata;
        }
        return NULL;
    }

    public function userlistComliance($customerno) {
        $Query = "SELECT * FROM " . SPEEDDB . ".`user` where  customerno = " . $customerno . " AND isdeleted = 0";
        $timeslotQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($timeslotQuery);
        $datadetails = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['realname'] != 'Elixir') {
                    $datadetails[] = array(
                        'userid' => $row['userid'],
                        'customerno' => $row['customerno'],
                        'stateid' => $row['stateid'],
                        'realname' => $row['realname'],
                        'username' => $row['username'],
                        'password' => $row['password'],
                        'role' => $row['role'],
                        'roleid' => $row['roleid'],
                        'phone' => $row['phone'],
                        'userkey' => $row['userkey'],
                        'emailid' => $row['email']
                    );
                }
            }
            return $datadetails;
        }
        return NULL;
    }

    public function getUserNomenclatureMapping($userid, $customerno) {
        $arrNomenDetails = NULL;
        $Query = "SELECT * FROM " . SPEEDDB . ".`userNomenClatureMapping` where  userid =" . $userid . " AND customerno = " . $customerno . " AND isdeleted = 0";
        $timeslotQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($timeslotQuery);
        $datadetails = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $objData = array(
                    "userid" => $row['userid'],
                    "nomenclatureid" => $row['nomenclatureid'],
                    "customerno" => $row['customerno']
                );
                $arrNomenDetails[] = $objData;
            }
        }
        return $arrNomenDetails;
    }

    public function setRestoreId($objChat) {
        $userid = $objChat->externalId;
        $restoreid = $objChat->restoreId;
        $custno = $objChat->customerno;
        $lastInsertedId = 0;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $todaysDate = today();
            $sp_params = "'" . $userid . "'"
                . ",'" . $restoreid . "'"
                . ",'" . $todaysDate . "'"
                . "," . $custno . "";
            $queryCallSP = "CALL " . speedConstants::SP_INSERT_CHATDETAILS . "($sp_params)";
            $pdo->query($queryCallSP);
            $result = $pdo->query('SELECT @lastInsertId AS lastInsertId')->fetch(PDO::FETCH_ASSOC);
            $lastInsertedChatId = $result["lastInsertId"];
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, ($ex), $this->userid, speedConstants::MODULE_VTS, __FUNCTION__);
        }
        return $userid;
    }

    public function getRestoreId($objChat) {
        $userid = $objChat;
        $restoreid = 0;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $userid . "'";
            $row1 = $queryCallSP = "CALL " . speedConstants::SP_FETCH_CHATDETAILS . "($sp_params)";
            $row1 = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            if ($row1 != FALSE) {
                $restoreid = $row1[0]['restore_id'];
            }
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, ($ex), $this->userid, speedConstants::MODULE_VTS, __FUNCTION__);
        }
        return $restoreid;
    }

    public function vehiclelist($customerno, $userid, $roleid) {
        $getdata = array();
        $Query = "SELECT v.vehicleid, vehicleno, u.userid, u.role, u.username, u.email
        ,g.groupid, g.code AS branchcode, g.groupname AS branchname,c.cityid AS regionid
        , c.code AS regioncode, c.name AS regionname, d.districtid as zoneid, d.code AS zonecode, d.name AS zonename
        ,(SELECT username FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) AS regionalUserSAP
        , (SELECT username FROM user WHERE userid = (SELECT heirarchy_id FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) and user.isdeleted=0) AS zonalUserSAP
        FROM `vehicle` v
        INNER JOIN unit un on un.uid = v.uid
        INNER JOIN devices de on un.uid = de.uid
        INNER JOIN `group` g ON g.groupid = v.groupid and g.customerno =" . $customerno . " and g.isdeleted = 0
        INNER JOIN city c ON c.cityid = g.cityid and c.isdeleted = 0
        INNER JOIN district d ON d.districtid = c.districtid and d.isdeleted=0
        INNER JOIN state s ON s.stateid = d.stateid and s.isdeleted= 0
        INNER JOIN nation n ON n.nationid = s.nationid and n.isdeleted=0
        LEFT JOIN vehicleusermapping vum on v.vehicleid = vum.vehicleid and vum.customerno =" . $customerno . " and vum.isdeleted = 0 AND vum.userid IN (SELECT userid from user where roleid in(37) )
        LEFT join user u on u.userid = vum.userid and u.customerno = " . $customerno . " and u.isdeleted = 0 and u.roleid in(37)
        WHERE v.customerno = " . $customerno . "
        and un.customerno = " . $customerno . "
        and de.customerno = " . $customerno . "
        and v.isdeleted=0
        ORDER BY `vehicleno`, coalesce(u.roleid, 0), branchname, regionname, zonename ASC";
        $userQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($userQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $getdata = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $getdata[] = array(
                    'vehicleid' => isset($row['vehicleid']) ? $row['vehicleid'] : '',
                    'vehicleno' => isset($row['vehicleno']) ? $row['vehicleno'] : '',
                    'userid' => isset($row['userid']) ? $row['userid'] : '',
                    'role' => isset($row['role']) ? $row['role'] : '',
                    'username' => isset($row['username']) ? $row['username'] : '',
                    'email' => isset($row['email']) ? $row['email'] : '',
                    'groupid' => isset($row['groupid']) ? $row['groupid'] : '',
                    'branchcode' => isset($row['branchcode']) ? $row['branchcode'] : '',
                    'branchname' => isset($row['branchname']) ? $row['branchname'] : '',
                    'regioncode' => isset($row['regioncode']) ? $row['regioncode'] : '',
                    'regionname' => isset($row['regionname']) ? $row['regionname'] : '',
                    'zonecode' => isset($row['zonecode']) ? $row['zonecode'] : '',
                    'zonename' => isset($row['zonename']) ? $row['zonename'] : '',
                    'regionalUserSAP' => isset($row['regionalUserSAP']) ? $row['regionalUserSAP'] : '',
                    'zonalUserSAP' => isset($row['zonalUserSAP']) ? $row['zonalUserSAP'] : ''
                );
            }
            //print_r($getdata); die();
            return $getdata;
        }
        return null;
    }

    public function getTripuserDetail($userid) {
        $getdata = array();
        $Query = "SELECT * FROM `tripusers` where addeduserid=%d and isdeleted=0 LIMIT 1";
        $userQuery = sprintf($Query, $userid);
        $isTripUser = 0;
        $this->_databaseManager->executeQuery($userQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $isTripUser = 1;
            }
        }
        return $isTripUser;
    }

    public function getVehicleName($vehicleid) {
        $Query = "SELECT vehicleno FROM vehicle WHERE vehicleid = %d ";
        $SQL = sprintf($Query, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
        $row = $this->_databaseManager->get_nextRow();
        $vehicleno = $row['vehicleno'];
        return $vehicleno;
    }

    public function delete_adv_temp_range($vehicleid) {
        $sql = sprintf("UPDATE  `advancetempalertrange`
                        SET     isdeleted = 1
                        WHERE   vehicleid = %d", $vehicleid);
        $this->_databaseManager->executeQuery($sql);
        echo '1';
    }

    public function add_adv_temp($data) {
        $Query = "  SELECT  t.vehicleid
                    FROM    advancetempalertrange as t
                    WHERE   t.vehicleid = %d
                    AND     t.userid = %d
                    AND     t.customerno = %d;";
        $SQL = sprintf($Query, $data->vehicleid, $data->userid, $data->customerno);
        $this->_databaseManager->executeQuery($SQL);
        $count = $this->_databaseManager->get_rowCount();
        $temp = (array) ($data->sms);
        foreach ((array) ($data->email) as $k => $v) {
            $temp[$k] = $v;
        }
        if ($count > 0) {
            $query = "  UPDATE  `advancetempalertrange`
                        SET     ";
            $i = 0;
            foreach ($temp as $key => $val) {
                if ($i == 0) {
                    $query .= $key . ' = ' . $val;
                } else {
                    $query .= ',' . $key . ' = ' . $val;
                }
                $i++;
            }
            $query .= " ,isdeleted = 0
                        ,updatedon = '%s'
                        WHERE   vehicleid = %d
                        AND     userid = %d
                        AND     customerno = %d;";
            $SQL = sprintf($query, date('Y-m-d H:i:s'), $data->vehicleid, $data->userid, $data->customerno);
        } else {
            foreach ($temp as $key => $val) {
                $keysString = "(`vehicleid`,`userid`,`customerno`,`" . implode("`,`", array_keys($temp)) . "`)";
                $valString = "(%d,%d,%d,'" . implode("', '", $temp) . "');";
            }
            $query = "INSERT INTO `advancetempalertrange` $keysString VALUES $valString";
            $SQL = sprintf($query, $data->vehicleid, $data->userid, $data->customerno);
            $eventQuery = "INSERT INTO advtempeventalerts(vehicleid,userid,customerno) VALUES(%d,%d,%d);";
            $eventSQL = sprintf($eventQuery, $data->vehicleid, $data->userid, $data->customerno);
            $this->_databaseManager->executeQuery($eventSQL);
        }
        $this->_databaseManager->executeQuery($SQL);
        echo '1';
    }

    public function check_temp_range($data) {
        $nomen = $data->nomen;
        $flag = 0;
        if ($nomen > 0) {
            $min = 'temp' . $nomen . '_min';
            $max = 'temp' . $nomen . '_max';
            $Query = "  SELECT  %s,%s
                        FROM    vehicle
                        WHERE   vehicleid = %d
                        AND     customerno = %d
                        LIMIT   1;";
            $SQL = sprintf($Query, $min, $max, $data->vehicleid, $data->customerno);
            $this->_databaseManager->executeQuery($SQL);
            $row = $this->_databaseManager->get_nextRow();
            if (!empty($row)) {
                $min_sms = 'temp' . $nomen . '_min_sms';
                $max_sms = 'temp' . $nomen . '_max_sms';
                $min_email = 'temp' . $nomen . '_min_email';
                $max_email = 'temp' . $nomen . '_max_email';
                $start = $row[$min];
                $end = $row[$max];
                if (isOverlap($start, $end, $data->$min_sms, $data->$max_sms)) {
                    $flag = 1;
                }
                if (isOverlap($start, $end, $data->$min_email, $data->$max_email)) {
                    $flag = 1;
                }
            }
        }
        return $flag;
    }

    public function get_edit_advance_temp($vehicleid, $userid) {
        $Query = '  SELECT  b.vehicleid
                            ,b.vehicleno
                            ,t.temp1_min_email
                            ,t.temp1_max_email
                            ,t.temp2_min_email
                            ,t.temp2_max_email
                            ,t.temp3_min_email
                            ,t.temp3_max_email
                            ,t.temp4_min_email
                            ,t.temp4_max_email
                            ,t.temp1_min_sms
                            ,t.temp1_max_sms
                            ,t.temp2_min_sms
                            ,t.temp2_max_sms
                            ,t.temp3_min_sms
                            ,t.temp3_max_sms
                            ,t.temp4_min_sms
                            ,t.temp4_max_sms
                    FROM    vehicle AS b
                    LEFT JOIN advancetempalertrange as t ON t.vehicleid = b.vehicleid AND t.isdeleted = 0
                    WHERE   b.customerno = %d
                    AND     t.userid = %d
                    AND     b.vehicleid = %d
                    AND     t.isdeleted = 0
                    AND     b.isdeleted = 0
                    LIMIT   1';
        $vehiclesQuery = sprintf($Query, $_SESSION['customerno'], $userid, $vehicleid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $vehicles = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle['vehicleid'] = $row['vehicleid'];
                $vehicle['vehicleno'] = $row['vehicleno'];
                $vehicle['temp1_min_email'] = isset($row['temp1_min_email']) ? $row['temp1_min_email'] : NULL;
                $vehicle['temp1_max_email'] = isset($row['temp1_max_email']) ? $row['temp1_max_email'] : NULL;
                $vehicle['temp2_min_email'] = isset($row['temp2_min_email']) ? $row['temp2_min_email'] : NULL;
                $vehicle['temp2_max_email'] = isset($row['temp2_max_email']) ? $row['temp2_max_email'] : NULL;
                $vehicle['temp3_min_email'] = isset($row['temp3_min_email']) ? $row['temp3_min_email'] : NULL;
                $vehicle['temp3_max_email'] = isset($row['temp3_max_email']) ? $row['temp3_max_email'] : NULL;
                $vehicle['temp4_min_email'] = isset($row['temp4_min_email']) ? $row['temp4_min_email'] : NULL;
                $vehicle['temp4_max_email'] = isset($row['temp4_max_email']) ? $row['temp4_max_email'] : NULL;
                $vehicle['temp1_min_sms'] = isset($row['temp1_min_sms']) ? $row['temp1_min_sms'] : NULL;
                $vehicle['temp1_max_sms'] = isset($row['temp1_max_sms']) ? $row['temp1_max_sms'] : NULL;
                $vehicle['temp2_min_sms'] = isset($row['temp2_min_sms']) ? $row['temp2_min_sms'] : NULL;
                $vehicle['temp2_max_sms'] = isset($row['temp2_max_sms']) ? $row['temp2_max_sms'] : NULL;
                $vehicle['temp3_min_sms'] = isset($row['temp3_min_sms']) ? $row['temp3_min_sms'] : NULL;
                $vehicle['temp3_max_sms'] = isset($row['temp3_max_sms']) ? $row['temp3_max_sms'] : NULL;
                $vehicle['temp4_min_sms'] = isset($row['temp4_min_sms']) ? $row['temp4_min_sms'] : NULL;
                $vehicle['temp4_max_sms'] = isset($row['temp4_max_sms']) ? $row['temp4_max_sms'] : NULL;
                array_push($vehicles, $vehicle);
            }
            return json_encode($vehicles);
        }
    }

    public function getSmsEmailTemp($userid, $vehicleid, $nomen) {
        $query = '  SELECT  temp%s_min_email
                            , temp%s_max_email
                            , temp%s_min_sms
                            , temp%s_max_sms
                    FROM    advancetempalertrange
                    WHERE   vehicleid = %d
                    AND     userid = %d
                    AND     isdeleted = 0
                    LIMIT   1';
        $sql = sprintf($query, Sanitise::String($nomen)
            , Sanitise::String($nomen)
            , Sanitise::String($nomen)
            , Sanitise::String($nomen)
            , Sanitise::Long($vehicleid)
            , Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle['temp' . $nomen . '_min_email'] = (int) ($row['temp' . $nomen . '_min_email']);
                $vehicle['temp' . $nomen . '_max_email'] = (int) ($row['temp' . $nomen . '_max_email']);
                $vehicle['temp' . $nomen . '_min_sms'] = (int) ($row['temp' . $nomen . '_min_sms']);
                $vehicle['temp' . $nomen . '_max_sms'] = (int) ($row['temp' . $nomen . '_max_sms']);
                return $vehicle;
            }
        }
        return null;
    }

    public function getUsersForReportPerCustomer($objUser) {
        $users = array();
        $SQL = "SELECT urm.userid, urm.customerno, urm.reportId, urm.reportTime, u.email, u.realname, u.userkey, u.role, u.roleid,VR.interval
        FROM userReportMapping as urm
        INNER JOIN user as u on u.userid = urm.userid
        INNER JOIN customer c on c.customerno = u.customerno
        LEFT JOIN vehrepinterval VR ON VR.userid = u.userid
        WHERE urm.reportId=%d
        AND urm.isActivated = 1 AND urm.customerNo=%d
        AND urm.isdeleted=0  AND u.isdeleted=0";
        $Query = sprintf($SQL, $objUser->reportId, $objUser->customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                $user->reportId = $row['reportId'];
                $user->reportTime = $row['reportTime'];
                $user->userid = $row['userid'];
                $user->email = $row['email'];
                $user->realname = $row['realname'];
                $user->userkey = $row['userkey'];
                $user->userrole = $row['role'];
                $user->roleid = $row['roleid'];
                $user->customerno = $row['customerno'];
                $user->interval = $row['interval'];
                $users[] = $user;
            }
        }
        return $users;
    }

    public function insert_email_for_newsLetter($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $today = date("Y-m-d H:i:s");
        $sp_params = "'" . $obj->email_id . "'"
        . ",'" . $obj->gu_id . "'"
            . ",'" . $today . "'"
            . "," . "@newsLetterId,@subscriptionId";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_INTO_NEWS_LETTER_SUBS . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @newsLetterId AS newsLetterId,@subscriptionId AS subscriptionId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        return $outputResult;
    }

    public function update_NewsLetter_Subscription($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $today = date("Y-m-d H:i:s");
        $sp_params = "'" . $obj->gu_ID . "'"
            . ",'" . $today . "'"
            . "," . "@isUpdated";
        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_NEWS_LETTER_SUBS . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isUpdated AS isUpdated";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        return $outputResult;
    }

    public function duplicate_guId($guidText) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $today = date("Y-m-d H:i:s");
        $sp_params = "'" . $guidText . "'"
            . "," . "@isExist";
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_GUID . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExist AS isExist";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        return $outputResult;
    }

    public function getAllCustomerElixir($customerno = null) {
        $users = array();
        $uname = "'%elixir%'";
        $Query = "Select c.customerno, customername, customercompany,userid,userkey
                  FROM user
                  INNER JOIN customer c on c.customerno = user.customerno
                  WHERE username LIKE %s and user.isdeleted=0";
        $SQL = sprintf($Query, $uname);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new VOUser();
                $user->customerno = $row['customerno'];
                $user->customername = $row['customername'];
                $user->customercompany = $row['customercompany'];
                $user->userid = $row['userid'];
                $user->userkey = $row['userkey'];
                $users[] = $user;
            }
        }
        return $users;
    }

    public function getUserHistoryLogs($obj) {
        $uiList = array();
        $uiList = [
            "realname", "email", "phone", "role",
            "dailyemail", "dailyemail_csv",
            "mess_email", "mess_sms", "mess_mobile",
            "chk_email", "chk_sms", "chk_mobile",
            "speed_email", "speed_sms", "speed_mobile",
            "power_email", "power_sms", "power_mobile",
            "tamper_email", "tamper_sms", "tamper_mobile",
            "ac_email", "ac_sms", "ac_mobile",
            "ignition_email", "ignition_sms", "ignition_mobile",
            "temp_email", "temp_sms", "temp_mobile",
            "start_alert_time", "stop_alert_time",
            "fuel_alert_email", "fuel_alert_sms", "fuel_alert_mobile", "fuel_alert_percentage",
            "harsh_break_mail", "harsh_break_sms", "harsh_break_mobile",
            "high_acce_sms", "high_acce_mail", "high_acce_mobile",
            "sharp_turn_sms", "sharp_turn_mail", "sharp_turn_mobile",
            "towing_sms", "towing_mail", "towing_mobile",
            "panic_sms", "panic_email", "panic_mobile",
            "immob_sms", "immob_email", "immob_mobile",
            "door_sms", "door_email", "door_mobile",
            "delivery_vehicleid",
            "tempinterval", "igninterval", "speedinterval", "acinterval", "doorinterval", "huminterval",
            "hum_sms", "hum_email", "hum_mobile",
            "vehicle_movement_alert", "trinterval", "vehinterval",
            "isTempInrangeAlertRequired", "isAdvTempConfRange"
        ];
        $arrResult = array();
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $_SESSION['customerno'] . "'" .
        ",'" . $obj->userId . "'" .
        ",'" . date("Y-m-d", strtotime($obj->start_date)) . "'" .
        ",'" . date("Y-m-d", strtotime($obj->end_date)) . "'" .
        ",'" . $obj->total_records . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_USER_LOGS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $finalArray = array();
        foreach ($arrResult as $row) {
            $data['basic_details']['realname'] = $row['realname'];
            $data['basic_details']['role'] = $row['role'];
            $data['basic_details']['email'] = $row['email'];
            $data['basic_details']['phone'] = $row['phone'];
            $data['basic_details']['groups'] = $row['groupid'];
            $data['basic_details']['dailyemail'] = $row['dailyemail'] == 1 ? "ON" : "OFF";
            $data['basic_details']['dailyemail_csv'] = $row['dailyemail_csv'] == 1 ? "ON" : "OFF";
            $data['basic_details']['createdOn'] = isset($row['createdOn']) ? date('d/m/Y H:i:s', strtotime($row['createdOn'])) : '';
            $data['basic_details']['createdBy'] = $row['createdBy'];
            $data['basic_details']['updatedOn'] = isset($row['updatedOn']) ? date('d/m/Y H:i:s', strtotime($row['updatedOn'])) : '';
            $data['basic_details']['updatedBy'] = $row['updatedBy'];
            $data['conflict']['mess_email'] = $row['mess_email'] == 1 ? "ON" : "OFF";
            $data['conflict']['mess_sms'] = $row['mess_sms'] == 1 ? "ON" : "OFF";
            $data['conflict']['mess_mobile'] = $row['mess_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['conflict']['chk_email'] = $row['chk_email'] == 1 ? "ON" : "OFF";
            $data['conflict']['chk_sms'] = $row['chk_sms'] == 1 ? "ON" : "OFF";
            $data['conflict']['chk_mobile'] = $row['chk_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['speed_email'] = $row['speed_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['speed_sms'] = $row['speed_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['speed_mobile'] = $row['speed_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['power_email'] = $row['power_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['power_sms'] = $row['power_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['power_mobile'] = $row['power_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['tamper_email'] = $row['tamper_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['tamper_sms'] = $row['tamper_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['tamper_mobile'] = $row['tamper_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['ac_email'] = $row['ac_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['ac_sms'] = $row['ac_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['ac_mobile'] = $row['ac_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['ignition_email'] = $row['ignition_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['ignition_sms'] = $row['ignition_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['ignition_mobile'] = $row['ignition_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['temp_email'] = $row['temp_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['temp_sms'] = $row['temp_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['temp_mobile'] = $row['temp_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['start_alert_time'] = $row['start_alert'];
            $data['vehicle_alerts']['stop_alert_time'] = $row['stop_alert'];
            $data['vehicle_alerts']['fuel_alert_sms'] = $row['fuel_alert_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['fuel_alert_email'] = $row['fuel_alert_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['fuel_alert_mobile'] = $row['fuel_alert_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['fuel_alert_percentage'] = $row['fuel_alert_percentage'];
            $data['vehicle_alerts']['harsh_break_sms'] = $row['harsh_break_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['harsh_break_mail'] = $row['harsh_break_mail'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['harsh_break_mobile'] = $row['harsh_break_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['high_acce_sms'] = $row['high_acce_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['high_acce_mail'] = $row['high_acce_mail'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['high_acce_mobile'] = $row['high_acce_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['sharp_turn_sms'] = $row['sharp_turn_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['sharp_turn_mail'] = $row['sharp_turn_mail'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['sharp_turn_mobile'] = $row['sharp_turn_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['towing_sms'] = $row['towing_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['towing_mail'] = $row['towing_mail'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['towing_mobile'] = $row['towing_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['panic_sms'] = $row['panic_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['panic_email'] = $row['panic_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['panic_mobile'] = $row['panic_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['immob_sms'] = $row['immob_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['immob_email'] = $row['immob_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['immob_mobile'] = $row['immob_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['door_sms'] = $row['door_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['door_email'] = $row['door_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['door_mobile'] = $row['door_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['delivery_vehicleid'] = $row['delivery_vehicleid'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['tempinterval'] = $row['tempinterval'];
            $data['vehicle_alerts']['igninterval'] = $row['igninterval'];
            $data['vehicle_alerts']['speedinterval'] = $row['speedinterval'];
            $data['vehicle_alerts']['acinterval'] = $row['acinterval'];
            $data['vehicle_alerts']['doorinterval'] = $row['doorinterval'];
            $data['vehicle_alerts']['hum_sms'] = $row['hum_sms'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['hum_email'] = $row['hum_email'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['hum_mobile'] = $row['hum_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['vehicle_alerts']['huminterval'] = $row['huminterval'];
            $data['basic_details']['vehicle_movement_alert'] = $row['vehicle_movement_alert'];
            if (isset($row['trinterval'])) {
                $data['vehicle_alerts']['trinterval'] = $row['trinterval'];
            }
            if (isset($row['vehinterval'])) {
                $data['vehicle_alerts']['vehinterval'] = $row['vehinterval'];
            }
            if (isset($row['isTempInrangeAlertRequired'])) {
                $data['vehicle_alerts']['isTempInrangeAlertRequired'] = $row['isTempInrangeAlertRequired'];
            }
            if (isset($row['isAdvTempConfRange'])) {
                $data['vehicle_alerts']['isAdvTempConfRange'] = $row['isAdvTempConfRange'];
            }
            $data['basic_details']['insertedBy'] = $row['inserted_by'];
            $data['basic_details']['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['insertedOn']));
            $data['vehicle_alerts']['insertedBy'] = $row['inserted_by'];
            $data['vehicle_alerts']['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['insertedOn']));
            $data['conflict']['insertedBy'] = $row['inserted_by'];
            $data['conflict']['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['insertedOn']));
            $finalArray[] = $data;
        }
        $db->ClosePDOConn($pdo);
        $finalArray = array_reverse($finalArray);
        foreach ($finalArray as $k => &$record) {
            if (isset($record['basic_details'])) {
                foreach ($record['basic_details'] as $column => &$value) {
                    if (in_array($column, $uiList)) {
                        $record['basic_details'][$column . "ui"] = $value;
                        if (isset($finalArray[$k - 1]['basic_details'][$column])) {
                            if (isset($finalArray[$k - 1]['basic_details'][$column]) && $finalArray[$k - 1]['basic_details'][$column] != $value) {
                                $record['basic_details'][$column . "ui"] = "<font color='green'>" . $value . "</font>";
                                $finalArray[$k - 1]['basic_details'][$column . "ui"] = "<font color='red'>" . $finalArray[$k - 1]['basic_details'][$column] . "</font>";
                            }
                        }
                    }
                }
            }
            if (isset($record['conflict'])) {
                foreach ($record['conflict'] as $column => &$value) {
                    if (in_array($column, $uiList)) {
                        // echo $column;
                        $record['conflict'][$column . "ui"] = $value;
                        if (isset($finalArray[$k - 1]['conflict'][$column])) {
                            if (isset($finalArray[$k - 1]['conflict'][$column]) && $finalArray[$k - 1]['conflict'][$column] != $value) {
                                // echo $column."ui";
                                $record['conflict'][$column . "ui"] = "<font color='green'>" . $value . "</font>";
                                $finalArray[$k - 1]['conflict'][$column . "ui"] = "<font color='red'>" . $finalArray[$k - 1]['conflict'][$column] . "</font>";
                            }
                        }
                    }
                }
            }
            if (isset($record['vehicle_alerts'])) {
                foreach ($record['vehicle_alerts'] as $column => &$value) {
                    if (in_array($column, $uiList)) {
                        // echo $column;
                        $record['vehicle_alerts'][$column . "ui"] = $value;
                        if (isset($finalArray[$k - 1]['vehicle_alerts'][$column])) {
                            if (isset($finalArray[$k - 1]['vehicle_alerts'][$column]) && $finalArray[$k - 1]['vehicle_alerts'][$column] != $value) {
                                $record['vehicle_alerts'][$column . "ui"] = "<font color='green'>" . $value . "</font>";
                                $finalArray[$k - 1]['vehicle_alerts'][$column . "ui"] = "<font color='red'>" . $finalArray[$k - 1]['vehicle_alerts'][$column] . "</font>";
                            }
                        }
                    }
                }
            }
            $previousRecord = $record;
        }
        $finalArray = array_reverse($finalArray);
        return $finalArray;
    }

    public function getUserStoppageAndVehicleMapLogs($obj) {
        $arrResult = array();
        $uiList = ["is_chk_sms", "is_chk_email", "is_chk_mobilenotification", "chkmins",
            "is_trans_email", "is_trans_sms", "is_trans_mobilenotification",
            "transmins", "vehicleno"
        ];
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $_SESSION['customerno'] . "'" .
        ",'" . $obj->userId . "'" .
        ",'" . date("Y-m-d", strtotime($obj->start_date)) . "'" .
        ",'" . date("Y-m-d", strtotime($obj->end_date)) . "'" .
        ",'" . $obj->total_records . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_USER_STOPPAGE_ALERTS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $queryCallSP = "CALL " . speedConstants::SP_GET_VEH_USER_LOGS . "(" . $sp_params . ")";
        $arrResult1 = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $finalArray = array();
        foreach ($arrResult as $row) {
            $data['is_chk_sms'] = $row['is_chk_sms'] == 1 ? "ON" : "OFF";
            $data['is_chk_email'] = $row['is_chk_email'] == 1 ? "ON" : "OFF";
            $date['is_chk_mobilenotification'] = $row['is_chk_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['chkmins'] = $row['chkmins'];
            $data['is_trans_email'] = $row['is_trans_email'] == 1 ? "ON" : "OFF";
            $data['is_trans_sms'] = $row['is_trans_sms'] == 1 ? "ON" : "OFF";
            $data['is_trans_mobilenotification'] = $row['is_trans_mobilenotification'] == 1 ? "ON" : "OFF";
            $data['transmins'] = $row['transmins'];
            $data['insertedBy'] = $row['insertedBy'];
            $data['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['insertedOn']));
            $finalArray['stoppage_alerts'][] = $data;
        }
        foreach ($arrResult1 as $row) {
            $data1['vehicleno'] = $row['vehicleno'];
            $data1['insertedBy'] = $row['realname'];
            $data1['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['created_on']));
            $data1['isdeleted'] = $row['isdeleted'];
            $finalArray['veh_userMapping'][] = $data1;
        }
        $db->ClosePDOConn($pdo);
        if (isset($finalArray['stoppage_alerts'])) {
            foreach ($finalArray['stoppage_alerts'] as $k => &$record) {
                foreach ($record as $column => &$value) {
                    if (in_array($column, $uiList)) {
                        $record[$column . "ui"] = $value;
                        if (isset($finalArray['stoppage_alerts'][$k - 1][$column])) {
                            if (isset($finalArray['stoppage_alerts'][$k - 1][$column]) && $finalArray['stoppage_alerts'][$k - 1][$column] != $value) {
                                $record[$column . "ui"] = "<font color='red'>" . $value . "</font>";
                                $finalArray['stoppage_alerts'][$k - 1][$column . "ui"] = "<font color='green'>" . $finalArray['stoppage_alerts'][$k - 1][$column] . "</font>";
                            }
                        }
                    }
                }
            }
        }
        if (isset($finalArray['veh_userMapping'])) {
            foreach ($finalArray['veh_userMapping'] as $k => &$record) {
                if ($record["isdeleted"] == 1) {
                    $record["vehiclenoui"] = "<font color='red'>" . $record["vehicleno"] . "</font>";
                } else {
                    $record["vehiclenoui"] = "<font color='green'>" . $record["vehicleno"] . "</font>";
                }
            }
        }
        return $finalArray;
    }

    public function vehicleHeirarchy($customerno, $userid, $vehicleid) {
        $getdata = array();
        $Query = "select  u.userid,u.username, u.email, u.realname, u.phone,u.role, g.code AS branchcode, g.groupname AS branchname, c.code AS regioncode, c.name AS regionname,
        d.code AS zonecode, d.name AS zonename,vehicle_movement_alert,u.customerno
        ,(SELECT realname FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) AS regionalUserName
        ,(SELECT username FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) AS regionalUserSAP
        ,(SELECT email FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) AS regionalUserSAPEmail
        ,(SELECT phone FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) AS regionalUserSAPPhone
        , (SELECT realname FROM user WHERE userid = (SELECT heirarchy_id FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) and user.isdeleted=0) AS zonalUserName
        , (SELECT username FROM user WHERE userid = (SELECT heirarchy_id FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) and user.isdeleted=0) AS zonalUserSAP
        , (SELECT email FROM user WHERE userid = (SELECT heirarchy_id FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) and user.isdeleted=0) AS zonalUserSAPEmail
        , (SELECT phone FROM user WHERE userid = (SELECT heirarchy_id FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) and user.isdeleted=0) AS zonalUserSAPPhone
        FROM vehicleusermapping vu
        INNER JOIN groupman gm on gm.groupid = vu.groupid and vu.vehicleid = " . $vehicleid . "
        inner join user u on u.userid = vu.userid
        INNER JOIN `group` g ON g.groupid = gm.groupid
        INNER JOIN city c ON c.cityid = g.cityid
        INNER JOIN district d ON d.districtid = c.districtid
        INNER JOIN state s ON s.stateid = d.stateid
        INNER JOIN nation n ON n.nationid = s.nationid
        where vu.isdeleted = 0
        and vu.vehicleid = " . $vehicleid . "
        and gm.isdeleted = 0
        and vu.customerno =" . $customerno . "
        and g.customerno =" . $customerno . "
        and g.isdeleted = 0
        and u.customerno = " . $customerno . "
        and u.isdeleted = 0
        order by u.email, branchname, regionname, zonename;";
        $userQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($userQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $getdata = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $getdata[] = array(
                    'userid' => $row['userid'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'realname' => $row['realname'],
                    'phone' => $row['phone'],
                    'role' => $row['role'],
                    'branchcode' => $row['branchcode'],
                    'branchname' => $row['branchname'],
                    'regioncode' => $row['regioncode'],
                    'regionname' => $row['regionname'],
                    'zonecode' => $row['zonecode'],
                    'zonename' => $row['zonename'],
                    'regionalUserName' => $row['regionalUserName'],
                    'regionalUserSAP' => $row['regionalUserSAP'],
                    'regionalUserSAPEmail' => $row['regionalUserSAPEmail'],
                    'regionalUserSAPPhone' => $row['regionalUserSAPPhone'],
                    'zonalUserSAP' => $row['zonalUserSAP'],
                    'zonalUserName' => $row['zonalUserName'],
                    'zonalUserSAPEmail' => $row['zonalUserSAPEmail'],
                    'zonalUserSAPPhone' => $row['zonalUserSAPPhone'],
                    'vehicle_movement_alert' => $row['vehicle_movement_alert'],
                    'customerno' => $row['customerno']
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getUserGroupMappingLogs($obj) {
        $uiList = ["groupname"];
        $arrResult = array();
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $_SESSION['customerno'] . "'" .
        ",'" . $obj->userId . "'" .
        ",'" . date("Y-m-d", strtotime($obj->start_date)) . "'" .
        ",'" . date("Y-m-d", strtotime($obj->end_date)) . "'" .
        ",'" . $obj->total_records . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_USER_GROUP_MAPPING . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $finalArray = array();
        foreach ($arrResult as $row) {
            $data1['groupname'] = $row['groupname'];
            $data1['insertedBy'] = $row['realname'];
            $data1['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['createdOn']));
            $data1['isdeleted'] = $row['isdeleted'];
            $finalArray[] = $data1;
        }
        $db->ClosePDOConn($pdo);
        if (isset($finalArray)) {
            foreach ($finalArray as $k => &$record) {
                if ($record["isdeleted"] == 1) {
                    $record["groupnameui"] = "<font color='red'>" . $record["groupname"] . "</font>";
                } else {
                    $record["groupnameui"] = "<font color='green'>" . $record["groupname"] . "</font>";
                }
            }
        }
        return $finalArray;
    }

    public function getConsigneeId($userId) {
        $query = 'SELECT consigneeid FROM user_consignee_mapping WHERE userid=' . $userId . ' AND isdeleted=0 LIMIT 1';
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $getdata = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $getdata[] = array(
                    $row['consigneeid']
                );
            }
            return $getdata[0];
        }
        return null;
    }

    public function getDistributorId($customerNo) {
        $userId = 0;
        $SQL = "SELECT user.userid
        FROM user
        INNER JOIN " . DB_PARENT . ".customer on customer.customerno = user.customerno
        WHERE isdeleted=0 and user.customerno='%s' limit 1";
        $Query = sprintf($SQL, Validator::escapeCharacters($customerNo));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $userId = $row['userid'];
            }
        }
        return $userId;
    }

    public function getAllusersByCustomerNo($customerNo) {
        $Query = " select username,userid,realname,email from `user` where (customerno=" . $customerNo . ") AND (role='Consignee')";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $getdata = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $getdata[] = array(
                    'username' => $row['username'],
                    'userid' => $row['userid'],
                    'realname' => $row['realname'],
                    'email' => $row['email']
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getMappedUsersVehicle($vehicleid, $customerNo) {
        //$Query = " select vehmapid,vehicleid,customerno from `vehicleusermapping` where (customerno=".$customerNo.") AND (vehicleid=".$vehicleid.")";
        $Query = "select
                    `user`.email,
                    vehicleusermapping.vehmapid,
                    vehicleusermapping.vehicleid,
                    vehicleusermapping.customerno,
                    vehicleusermapping.userid
                    from
                    vehicleusermapping
                    inner join
                    `user`
                    on
                    vehicleusermapping.userid = `user`.userid
                    where
                    (vehicleusermapping.customerno=" . $customerNo . ") AND
                    (vehicleusermapping.vehicleid=" . $vehicleid . ") AND
                    (vehicleusermapping.isdeleted='0')";
        //echo"Query is: ".$Query; exit();
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $getdata = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $getdata[] = array(
                    'vehmapid' => $row['vehmapid'],
                    'vehicleid' => $row['vehicleid'],
                    'customerno' => $row['customerno'],
                    'email' => $row['email'],
                    'userid' => $row['userid']
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getUsersForAlertReport($objUser) {
        $users = array();
        $str = $objUser->reportTime . ' %';
        $SQL = "SELECT  urm.userid
                        , urm.customerno
                        , urm.reportId
                        , urm.reportTime
                        , u.email
                        , u.realname
                        , u.userkey
                        , u.role
                        , u.roleid
                        , VR.interval
                        , urm.iterativeReportHour
                FROM    userReportMapping as urm
                INNER JOIN user as u on u.userid = urm.userid
                INNER JOIN customer c on c.customerno = u.customerno
                LEFT JOIN vehrepinterval VR ON VR.userid = u.userid
                LEFT JOIN groupman gm ON gm.userid = u.userid AND gm.isdeleted = 0
                WHERE   urm.reportId = %d
                AND     (
                            (
                                urm.iterativeReportHour > 0
                                AND
                                (
                                    (%s (urm.reportTime + urm.iterativeReportHour) = 0 AND %d / (urm.reportTime + urm.iterativeReportHour) < 2)
                                    OR
                                    (%s (urm.reportTime + (2 * urm.iterativeReportHour)) = 0)
                                    OR
                                    (%s (urm.reportTime + (3 * urm.iterativeReportHour)) = 0)
                                    OR
                                    (%s (urm.reportTime + (4 * urm.iterativeReportHour)) = 0)
                                    OR
                                    (%s (urm.reportTime + (5 * urm.iterativeReportHour)) = 0)
                                    OR
                                    urm.reportTime = %d
                                )
                            )
                        )
                AND     urm.isActivated = 1
                AND     urm.isdeleted = 0
                AND     u.isdeleted = 0";
        $Query = sprintf($SQL, $objUser->reportId, $str, $objUser->reportTime, $str, $str, $str, $str, $objUser->reportTime, $objUser->reportTime);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                $user->reportId = $row['reportId'];
                $user->reportTime = $row['reportTime'];
                $user->userid = $row['userid'];
                $user->email = $row['email'];
                $user->realname = $row['realname'];
                $user->userkey = $row['userkey'];
                $user->userrole = $row['role'];
                $user->roleid = $row['roleid'];
                $user->customerno = $row['customerno'];
                $user->interval = $row['interval'];
                $user->iterativeReportHour = $row['iterativeReportHour'];
                $users[] = $user;
            }
        }
        return $users;
    }

    public function getAllusersForCustomer($customerNo) {
        $arrData = array();
        $Query = " select username,userid,realname,email,phone from `user` where (customerno=" . $customerNo . ") AND isdeleted = 0";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            //$getdata = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $getdata = array(
                    'username' => $row['username'],
                    'userid' => $row['userid'],
                    'realname' => $row['realname'],
                    'email' => $row['email'],
                    'phone' => $row['phone']
                );
                $arrData[] = $getdata;
            }
            //return $getdata;
        }
        return $arrData;
    }
}