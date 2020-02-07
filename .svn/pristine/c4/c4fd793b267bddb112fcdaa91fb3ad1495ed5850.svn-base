<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VODriver.php';

class DriverManager extends VersionedManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function check_username($username) {
        $drivers = Array();
        $Query = "SELECT * FROM `driver` WHERE username='%s' AND driver.isdeleted=0 ";
        $driversQuery = sprintf($Query, $username);
        $this->_databaseManager->executeQuery($driversQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            return "found";
        } else {
            return "notfound";
        }
    }
    public function get_all_drivers(){
        $drivers = Array();
        $Query = "SELECT * FROM `driver` where customerno=%d AND driver.isdeleted=0";
        $driversQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($driversQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $driver = new VODriver();
                $driver->driverid = $row['driverid'];
                $driver->drivername = $row['drivername'];
                $driver->driverlicno = $row['driverlicno'];
                $driver->driverphone = $row['driverphone'];
                $drivers[] = $driver;
            }
            return $drivers;
        }
        return null;
    }
    /*
    public function get_all_drivers_licence_exp_data($userid, $driverid){
    $drivers = Array();
    $Query = "SELECT d.licence_validity,d.driverlicno,d.drivername,d.vehicleid,u.username,u.realname,u.email,u.phone,da.userid, da.driverid, da.alertstatus,da.sendby_email, da.sendby_sms,da.send_before FROM `driver_alerts` as da
    left join driver as d on d.driverid = da.driverid AND d.userid = da.userid left join user as u on u.userid = da.userid  where da.userid = %d AND da.driverid=%d";
    $driversQuery = sprintf($Query,$userid,$driverid);
    $this->_databaseManager->executeQuery($driversQuery);

    if ($this->_databaseManager->get_rowCount() > 0)
    {
    while ($row = $this->_databaseManager->get_nextRow())
    {
    $driver = new stdClass();
    $driver->driverid = $row['driverid'];
    $driver->licence_validity = $row['licence_validity'];
    $driver->alertstatus = $row['alertstatus'];
    $driver->sendby_email = $row['sendby_email'];
    $driver->sendby_sms = $row['sendby_sms'];
    $driver->send_before = $row['send_before'];
    $driver->email = $row['email'];
    $driver->phone = $row['phone'];
    $driver->realname = $row['realname'];
    $driver->drivername = $row['drivername'];
    $driver->driverlicno = $row['driverlicno'];
    $driver->vehicleid = $row['vehicleid'];
    $drivers[] = $driver;
    }
    return $drivers;
    }
    return null;

    }
     *
     */

    public function get_all_drivers_allocated() {
        $drivers = Array();
        $Query = "SELECT * FROM `driver` where customerno=%d AND drivername <> 'Not Allocated'  AND driver.isdeleted=0";
        $driversQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($driversQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $driver = new VODriver();
                $driver->driverid = $row['driverid'];
                $driver->drivername = $row['drivername'];
                $driver->driverlicno = $row['driverlicno'];
                $driver->driverphone = $row['driverphone'];
                $drivers[] = $driver;
            }
            return $drivers;
        }
        return null;
    }

    public function serachAllocatedDriver($searchString) {
        $drivers = Array();
        $driverString = '';
        $Query = "SELECT * FROM `driver` where customerno=%d AND drivername <> 'Not Allocated'  AND driver.isdeleted=0 AND drivername LIKE '%s' ";
        $driversQuery = sprintf($Query, $this->_Customerno, Sanitise::String($searchString));
        $this->_databaseManager->executeQuery($driversQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $driver = new VODriver();
                $driver->driverid = $row['driverid'];
                $driver->drivername = $row['drivername'];
                $driver->driverlicno = $row['driverlicno'];
                $driver->driverphone = $row['driverphone'];
                $drivers[] = $driver;
            }
            return $drivers;
        }
        return null;
    }

    #Usage For History Module -- Vehicle Data
    public function GetAllDrivers_SQLite() {
        $DRIVERS = Array();
        $Query = "SELECT drivername,driverphone,driverid FROM `driver` where customerno=%d AND isdeleted = 0";
        $driversQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($driversQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $id = $row['driverid'];
                $DRIVERS[$id]['id'] = $row['driverid'];
                $DRIVERS[$id]['drivername'] = $row['drivername'];
                $DRIVERS[$id]['driverphone'] = $row['driverphone'];
            }
            return $DRIVERS;
        }
        return null;
    }

    public function get_all_drivers_with_vehicles() {
        $drivers = Array();
        $Query = "SELECT *,driver.driverid FROM driver
            LEFT OUTER JOIN vehicle ON driver.vehicleid = vehicle.vehicleid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid ";
        if (isset($_SESSION["roleid"]) && $_SESSION["roleid"] == '2') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
                LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid
                LEFT OUTER JOIN `state` ON `state`.stateid = district.stateid ";
        }
        if (isset($_SESSION["roleid"]) && $_SESSION["roleid"] == '3') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
                LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid ";
        }
        if (isset($_SESSION["roleid"]) && $_SESSION["roleid"] == '4') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid ";
        }
        $Query .= " WHERE driver.customerno =%d AND driver.isdeleted=0";
        if (isset($_SESSION["groupid"]) && $_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }

        if (isset($_SESSION["groupid"]) &&  $_SESSION['groupid'] != 0) {
            $driversQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $driversQuery = sprintf($Query, $this->_Customerno);
        }
        $heir_query = "";
        if (isset($_SESSION["roleid"]) && $_SESSION['roleid'] == '2') {
            $heir_query = sprintf(" AND state.stateid = %d ", $_SESSION['heirarchy_id']);
        }
        if (isset($_SESSION["roleid"]) && $_SESSION['roleid'] == '3') {
            $heir_query = sprintf(" AND district.districtid = %d ", $_SESSION['heirarchy_id']);
        }
        if (isset($_SESSION["roleid"]) && $_SESSION['roleid'] == '4') {
            $heir_query = sprintf(" AND city.cityid = %d ", $_SESSION['heirarchy_id']);
        }
        $driversQuery .= $heir_query;

        $this->_databaseManager->executeQuery($driversQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $driver = new VODriver();
                $driver->driverid = $row['driverid'];
                $driver->drivername = $row['drivername'];
                $driver->driverlicno = $row['driverlicno'];
                $driver->driverphone = $row['driverphone'];
                $driver->vehicleid = $row['vehicleid'];
                $driver->vehicleno = $row['vehicleno'];
                $drivers[] = $driver;
            }
            return $drivers;
        }
        return null;
    }

    public function get_all_drivers_with_vehicles_allocated() {
        $drivers = Array();
        $Query = "SELECT *,driver.driverid FROM driver
            LEFT OUTER JOIN vehicle ON driver.vehicleid = vehicle.vehicleid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid ";
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
        $Query .= " WHERE driver.customerno =%d AND driver.drivername <> 'Not Allocated'  AND driver.isdeleted=0"; //AND driver.userkey<>0
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }

        if ($_SESSION['groupid'] != 0) {
            $driversQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $driversQuery = sprintf($Query, $this->_Customerno);
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
        $driversQuery .= $heir_query;

        $this->_databaseManager->executeQuery($driversQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $driver = new VODriver();
                $driver->driverid = $row['driverid'];
                $driver->drivername = $row['drivername'];
                $driver->driverlicno = $row['driverlicno'];
                $driver->driverphone = $row['driverphone'];
                $driver->vehicleid = $row['vehicleid'];
                $driver->vehicleno = $row['vehicleno'];
                $drivers[] = $driver;
            }
            return $drivers;
        }
        return null;
    }
    /*
    public function get_driveralert_by_id($driverid){
    $driver_alert = Array();
    $Query = "select * from driver_alerts where driverid=%d AND userid=%d";
    $driveralertQuery = sprintf($Query,$driverid,$_SESSION['userid']);
    $this->_databaseManager->executeQuery($driveralertQuery);
    if ($this->_databaseManager->get_rowCount() > 0)
    {
    while ($row = $this->_databaseManager->get_nextRow())
    {
    $driveralert = new stdClass();
    $driveralert->driverid = $row['userid'];
    $driveralert->customerno = $row['customerno'];
    $driveralert->alertstatus = $row['alertstatus'];
    $driveralert->send_bymail = $row['sendby_email'];
    $driveralert->send_bysms = $row['sendby_sms'];
    $driveralert->send_before = $row['send_before'];
    $driver_alert[]= $driveralert;
    }
    return $driver_alert;
    }
    return null;
    }
     *
     */

    public function get_driver_with_vehicle($driverid) {
        $Query = "SELECT *,driver.driverid AS thisdriverid FROM driver
            LEFT OUTER JOIN vehicle ON driver.vehicleid = vehicle.vehicleid
            WHERE driver.customerno =%d and driver.driverid=%s AND driver.isdeleted=0";
        $driversQuery = sprintf($Query, $this->_Customerno, Sanitise::String($driverid));
        $this->_databaseManager->executeQuery($driversQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $driver = new VODriver();
                $driver->driverid = $row['thisdriverid'];
                $driver->drivername = $row['drivername'];
                $driver->driverlicno = $row['driverlicno'];
                $driver->driverphone = $row['driverphone'];
                $driver->vehicleid = $row['vehicleid'];
                $driver->vehicleno = $row['vehicleno'];
                $driver->type = $row['kind'];
                $birthdate = date('d-m-Y', strtotime($row['birthdate']));
                $LicvalidDate = date('d-m-Y', strtotime($row['licence_validity']));
                $driver->birthdate = $birthdate;
                $driver->licence_validity = $LicvalidDate;
                $driver->age = $row['age'];
                $driver->bloodgroup = $row['bloodgroup'];
                $driver->licence_issue_auth = $row['licence_issue_auth'];
                $driver->local_address = $row['local_address'];
                $driver->local_contact = $row['local_contact'];
                $driver->local_contact_mob = $row['local_contact_mob'];
                $driver->emergency_contact1 = $row['emergency_contact1'];
                $driver->emergency_contact2 = $row['emergency_contact2'];
                $driver->emergency_contact_no1 = $row['emergency_contact_no1'];
                $driver->emergency_contact_no2 = $row['emergency_contact_no2'];
                $driver->native_address = $row['native_address'];
                $driver->native_contact = $row['native_contact'];
                $driver->native_contact_mob = $row['native_contact_mob'];
                $driver->native_emergency_contact1 = $row['native_emergency_contact1'];
                $driver->native_emergency_contact2 = $row['native_emergency_contact2'];
                $driver->native_emergency_contact_no1 = $row['native_emergency_contact_no1'];
                $driver->native_emergency_contact_no2 = $row['native_emergency_contact_no2'];
                $driver->previous_employer = $row['previous_employer'];
                $driver->service_period = $row['service_period'];
                $driver->service_contact_person = $row['service_contact_person'];
                $driver->service_contact_no = $row['service_contact_no'];
                $driver->uploadfilename = $row['upload'];
                $driver->usrname = $row['username'];
                $driver->pass = $row['password'];
                $driver->tripmail = $row['trip_email'];
                $driver->tripphone = $row['trip_phone'];
                $driver->userkey = $row['userkey'];

            }
            return $driver;
        }
        return null;
    }

    /*public function add_driver($drivername,$driverlicno,$driverphone,$userid)
    {
    $SQL = sprintf("INSERT INTO driver (drivername,driverlicno,driverphone,customerno,isdeleted,userid) VALUES ('%s','%s','%s',%d,0,%d)",
    Sanitise::String($drivername),
    Sanitise::String($driverlicno),
    Sanitise::String($driverphone),
    $this->_Customerno,
    Sanitise::Long($userid));
    $this->_databaseManager->executeQuery($SQL);
    }*/
    public function add_driver($addv, $userid) {

        $birthdate = date('Y-m-d', strtotime($addv->birthdate));
        $LicvalidDate = date('Y-m-d', strtotime($addv->LicvalidDate));
        $SQL = sprintf("INSERT INTO driver (`drivername`, `driverlicno`, `driverphone`, `customerno`,  `isdeleted`, `userid`, `birthdate`, `age`, `bloodgroup`, `licence_validity`, `licence_issue_auth`, `local_address`, `local_contact`, `local_contact_mob`, `emergency_contact1`,`emergency_contact_no1`, `emergency_contact2`, `emergency_contact_no2`,  `native_address`, `native_contact`, `native_contact_mob`, `native_emergency_contact1`,`native_emergency_contact_no1`, `native_emergency_contact2`,`native_emergency_contact_no2`, `previous_employer`, `service_period`, `service_contact_person`, `service_contact_no`,`upload`,`username`,`password`,`userkey`,`trip_email`,`trip_phone`)VALUES ('%s', '%s', '%s', %d, 0, %d, '%s', %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s','%s', '%s','%s', '%s', '%s','%s', '%s','%s', '%s','%s', '%s',%d,'%s','%s','%s','%s','%s','%s','%s','%s')
        ", Sanitise::String($addv->drivername),
            Sanitise::String($addv->driverlicno),
            Sanitise::String($addv->driverphone),
            $this->_Customerno,
            Sanitise::Long($userid),
            Sanitise::String($birthdate),
            Sanitise::String($addv->age),
            Sanitise::String($addv->bloodgroup),
            Sanitise::String($LicvalidDate),
            Sanitise::String($addv->lic_issue_auth),
            Sanitise::String($addv->localadd),
            Sanitise::String($addv->loc_tel_no),
            Sanitise::String($addv->loc_mob_no),
            Sanitise::String($addv->emergency_contact_name1),
            Sanitise::String($addv->emergency_contact_no1),
            Sanitise::String($addv->emergency_contact_name2),
            Sanitise::String($addv->emergency_contact_no2),
            Sanitise::String($addv->native_addr),
            Sanitise::String($addv->nat_tel_no),
            Sanitise::String($addv->nat_mob_no),
            Sanitise::String($addv->natemergency_contact_name1),
            Sanitise::String($addv->natemergency_contact_no1),
            Sanitise::String($addv->natemergency_contact_name2),
            Sanitise::String($addv->natemergency_contact_no2),
            Sanitise::String($addv->pre_employer),
            Sanitise::String($addv->service_period),
            Sanitise::String($addv->oldservice_contact_name),
            Sanitise::String($addv->oldservice_contact),
            Sanitise::String($addv->filename1),
            Sanitise::String($addv->username),
            Sanitise::String($addv->pass),
            Sanitise::String($addv->userkey),
            Sanitise::String($addv->mail),
            Sanitise::String($addv->phno));
        $this->_databaseManager->executeQuery($SQL);
        $driverid = $this->_databaseManager->get_insertedId();
        /*

        if($addv->driveralert=="yes"){
        $driverstatus= 1;
        }else if($addv->driveralert=="no"){
        $driverstatus= 0;
        }else{
        $driverstatus= 0;
        }
        if($addv->email=='email'){
        $emailstatus=1;
        }else{
        $emailstatus=0;
        }

        if($addv->sms=='sms'){
        $smsstatus=1;
        }else{
        $smsstatus=0;
        }
        $alertdays = $addv->alertdays;
        //$userid
        //$this->_Customerno
        $chkquery = "select * from driver_alerts where userid=%d AND driverid=%d";
        $driver_check_Query = sprintf($chkquery, Sanitise::String($userid),Sanitise::String($driverid));
        $this->_databaseManager->executeQuery($driver_check_Query);
        if ($this->_databaseManager->get_rowCount() > 0)
        {
        $Query = "UPDATE driver_alerts SET alertstatus=%d,sendby_email=%d,sendby_sms=%d, send_before= %d WHERE driverid=%d AND userid=%d";
        $SQL = sprintf($Query, Sanitise::Long($driverstatus),Sanitise::Long($emailstatus),Sanitise::Long($smsstatus),$alertdays,Sanitise::Long($driverid),Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($SQL);
        }else{
        $SQL = sprintf("INSERT INTO driver_alerts (userid,driverid,customerno,alertstatus,sendby_email,sendby_sms,send_before) VALUES ('%d','%d','%d',%d,%d,%d,%d)",
        Sanitise::String($userid),
        Sanitise::String($driverid),
        $this->_Customerno,
        Sanitise::String($driverstatus),
        Sanitise::String($emailstatus),
        Sanitise::String($smsstatus),
        Sanitise::Long($alertdays));
        $this->_databaseManager->executeQuery($SQL);
        }
         *
         */
        $response = $driverid;
        echo $response;
    }

    public function deldriver($driverid, $userid) {
        $Query = "UPDATE driver SET isdeleted=1,userid=%d WHERE driverid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($driverid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE vehicle SET driverid=0,userid=%d WHERE driverid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($driverid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        return "ok";
    }

    /* public function moddriver($driverid,$drivername,$drivelicno,$drivephoneno, $userid)
    {
    $Query = "UPDATE driver SET drivername='%s',driverlicno='%s',driverphone='%s',userid=%d
    WHERE driverid=%d AND customerno=%d";
    $SQL = sprintf($Query,  Sanitise::String($drivername),  Sanitise::String($drivelicno),  Sanitise::String($drivephoneno), Sanitise::Long($userid),$driverid,$this->_Customerno);
    $this->_databaseManager->executeQuery($SQL);
    }
     */
    public function moddriver($moddv, $userid) {
        if ($moddv->isDriverApp == 1) {
            $userkey = $moddv->userkey;
            $email = $moddv->mail;
            $role = "Driver";
            $roleid = "47";
            if ($this->exists_userkey_user($moddv->userkey)==1){
                $Query = "UPDATE " . DB_PARENT . ".`user` SET realname='%s', username ='%s', password='%s', role = '%s', roleid= %d, email='%s', phone='%s', delivery_vehicleid= %d  WHERE userkey = '%s' AND customerno = %d AND isdeleted=0";
                $SQL = sprintf($Query,
                    Sanitise::String($moddv->drivername)
                    , Sanitise::String($moddv->username)
                    , Sanitise::String($moddv->pass)
                    , Sanitise::String($role)
                    , Sanitise::Long($roleid)
                    , Sanitise::String($moddv->mail)
                    , Sanitise::String($moddv->phno)
                    , $moddv->vehicleid
                    , Sanitise::String($moddv->userkey)
                    , $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
            } else {
                if(!$this->exists_username_user($username)){
                $userkey = mt_rand();
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
                            `delivery_vehicleid`)
                            VALUES
                            ( '%d','%s','%s','%s','%s',%d,'%s','%s','%s',%d)"
                    , Sanitise::Long($this->_Customerno)
                    , Sanitise::String($moddv->drivername)
                    , Sanitise::String($moddv->username)
                    , Sanitise::String($moddv->pass)
                    , $role
                    , Sanitise::Long($roleid)
                    , Sanitise::String($moddv->mail)
                    , Sanitise::String($moddv->phno)
                    , Sanitise::String($userkey)
                    , $moddv->vehicleid
                );
                $this->_databaseManager->executeQuery($SQL);
                $user_userid = $this->_databaseManager->get_insertedId();

                $Query1 = "select * from user where customerno =" . $this->_Customerno . " and userid = " . $user_userid . " and isdeleted=0";
                $userQuery = sprintf($Query1);
                $this->_databaseManager->executeQuery($userQuery);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    $row = $this->_databaseManager->get_nextRow();
                    $userkey = $row['userkey'];
                }
              }else{
                $Query = "UPDATE " . DB_PARENT . ".`user` SET realname='%s', username ='%s', password='%s', role = '%s', roleid= %d, email='%s', phone='%s', delivery_vehicleid= %d  WHERE userkey = '%s' AND customerno = %d AND isdeleted=0";
                $SQL = sprintf($Query,
                    Sanitise::String($moddv->drivername)
                    , Sanitise::String($moddv->username)
                    , Sanitise::String($moddv->pass)
                    , Sanitise::String($role)
                    , Sanitise::Long($roleid)
                    , Sanitise::String($moddv->mail)
                    , Sanitise::String($moddv->phno)
                    , $moddv->vehicleid
                    , Sanitise::String($moddv->userkey)
                    , $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
              }
            }
        }

        $birthdate = date('Y-m-d', strtotime($moddv->birthdate));
        $LicvalidDate = date('Y-m-d', strtotime($moddv->LicvalidDate));
        $Query = "UPDATE `driver` SET userkey='%s', drivername='%s', driverlicno='%s', driverphone='%s', userid=%d, birthdate='%s',age=%d,bloodgroup='%s',licence_validity='%s',licence_issue_auth='%s', local_address='%s',local_contact='%s',local_contact_mob='%s',emergency_contact1='%s',emergency_contact2='%s',emergency_contact_no1='%s', emergency_contact_no2='%s', native_address='%s',native_contact='%s',native_contact_mob='%s',native_emergency_contact1='%s',native_emergency_contact2='%s', native_emergency_contact_no1='%s',native_emergency_contact_no2='%s',previous_employer='%s',service_period=%d,service_contact_person='%s', service_contact_no='%s', upload='%s', username='%s', password='%s',trip_email='%s',trip_phone='%s' WHERE `driverid`=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($userkey), Sanitise::String($moddv->drivername),
            Sanitise::String($moddv->driverlicno), Sanitise::String($moddv->driverphone), Sanitise::Long($userid), Sanitise::String($birthdate),
            Sanitise::String($moddv->age), Sanitise::String($moddv->bloodgroup), Sanitise::String($LicvalidDate),
            Sanitise::String($moddv->lic_issue_auth), Sanitise::String($moddv->localadd), Sanitise::String($moddv->loc_tel_no), Sanitise::String($moddv->loc_mob_no),
            Sanitise::String($moddv->emergency_contact_name1), Sanitise::String($moddv->emergency_contact_name2),
            Sanitise::String($moddv->emergency_contact_no1), Sanitise::String($moddv->emergency_contact_no2),
            Sanitise::String($moddv->native_addr), Sanitise::String($moddv->nat_tel_no), Sanitise::String($moddv->nat_mob_no),
            Sanitise::String($moddv->natemergency_contact_name1), Sanitise::String($moddv->natemergency_contact_name2),
            Sanitise::String($moddv->natemergency_contact_no1), Sanitise::String($moddv->natemergency_contact_no2),
            Sanitise::String($moddv->pre_employer), Sanitise::String($moddv->service_period), Sanitise::String($moddv->oldservice_contact_name),
            Sanitise::String($moddv->oldservice_contact), Sanitise::String($moddv->filename1), Sanitise::String($moddv->username),
            Sanitise::String($moddv->pass), Sanitise::String($moddv->mail), Sanitise::String($moddv->phno), Sanitise::Long($moddv->driverid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $response = $moddv->driverid;
        echo $response;
    }

    public function exists_userkey_user($userkey) {
        $Query1 = "select * from user where customerno =" . $this->_Customerno . " AND userkey = '" . $userkey . "' and isdeleted=0";
        $userQuery = sprintf($Query1);
        $this->_databaseManager->executeQuery($userQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function exists_username_user($username) {
        $Query1 = "select * from user where customerno =" . $this->_Customerno . " AND username = '" . $username . "' and isdeleted=0";
        $userQuery = sprintf($Query1);
        $this->_databaseManager->executeQuery($userQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function mapdrivertovehicle($vehicleid, $driverid, $userid) {

        $todaysDate = DATE(speedConstants::DEFAULT_TIMESTAMP);
        $varOldVehicleId = 0;

        $sql = "SELECT vehicleid FROM driver WHERE driverid = %d AND customerno = %d";
        $SQL = sprintf($sql, Sanitise::Long($driverid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $driver = new VODriver();
                $varOldVehicleId = $row['vehicleid'];
            }
        }


        $Query = "Update vehicle Set `driverid`=%d, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($driverid), Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        $Query = "Update driver Set `vehicleid`=%d, userid=%d WHERE driverid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($driverid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        /*$varOldVehicleId is Not 0*/
        if(isset($varOldVehicleId) && $varOldVehicleId != 0){
            $Query = "INSERT INTO driverVehicleHistoricMapping(driverId, vehicleId, customerNo, createdBy, createdOn)VALUES(%d,%d,%d,%d,'%s')";
            $SQL = sprintf($Query, Sanitise::Long($driverid), Sanitise::Long($varOldVehicleId), $this->_Customerno, Sanitise::Long($userid), Sanitise::DATETIME($todaysDate));
            $this->_databaseManager->executeQuery($SQL);
        }


    }

    public function demapdriver($vehicleid, $userid) {
        $Query = "Update driver Set `vehicleid`= 0, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        $Query = "Update vehicle Set `driverid`= 0, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getdriverfromvehicles($driverid) {
        $Query = "SELECT * FROM `vehicle` where vehicle.driverid = %d AND vehicle.customerno=%d and vehicle.isdeleted=0";
        $driverQuery = sprintf($Query, Sanitise::String($driverid), $this->_Customerno);
        $this->_databaseManager->executeQuery($driverQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $driver = new VODriver();
                $driver->vehicleid = $row['vehicleid'];
                $driver->driverid = $row['driverid'];
            }
            return $driver;
        }
        return null;
    }

    public function drivermappinglist($driverstring) {
        $getdata = array();
        $Query = "SELECT * FROM `driver` where drivername LIKE '%s' AND customerno=%d and isdeleted=0";
        $driverQuery = sprintf($Query, Sanitise::String($driverstring), $this->_Customerno);
        $this->_databaseManager->executeQuery($driverQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['driverid'],
                    'value' => $row['drivername'],
                );
            }
            return $getdata;
        }
        return null;
    }
}
