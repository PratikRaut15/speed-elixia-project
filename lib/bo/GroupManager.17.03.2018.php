<?php

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}

include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';

class GroupManager extends VersionedManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function get_all_groups() {
        $Groups = Array();
        if ($_SESSION['use_hierarchy'] == 0) {
            $Query = "SELECT `group`.groupid, `group`.groupname FROM `group` WHERE customerno= %d AND `group`.isdeleted=0";
            if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
                $Query .= " AND group.groupid =%d";
            }

            if (isset($_SESSION['groupid']) != 0) {
                $GroupsQuery = sprintf($Query, $this->_Customerno, $_SESSION["groupid"]);
            } else {
                $GroupsQuery = sprintf($Query, $this->_Customerno);
            }
        } else {
            $Query = "SELECT `group`.groupid, `group`.groupname FROM `group`
      where `group`.customerno=%d AND `group`.isdeleted=0 ORDER BY `group`.groupname ASC";
            $GroupsQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($GroupsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Group = new VOGroup();
                $Group->groupid = $row['groupid'];
                $Group->groupname = $row['groupname'];
                $Groups[] = $Group;
            }
            return $Groups;
        }
        return null;
    }

    public function getallgroups() {
        $groups = Array();
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT groupid,groupname,cityid FROM `group` "
                    . "where customerno=%d AND isdeleted=0 order by groupname ASC";
            $groupQuery = sprintf($Query, $this->_Customerno);
        } else {
            $Query = "SELECT groupid,groupname,group.cityid FROM `group` "
                    . "where customerno=%d AND isdeleted=0 order by groupname ASC";
            $groupQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($groupQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $group = new VOGroup();
                $group->groupid = $row['groupid'];
                $group->groupname = $row['groupname'];
                $group->cityid = $row['cityid'];
                $groups[] = $group;
            }
            return $groups;
        }
        return NULL;
    }

    public function getallgroups_detail() {
        $groups = Array();
        $Query = "SELECT group.groupid,groupname,group.cityid,group.code,group.cityid,group.timestamp,user.realname "
                . " FROM `group` left Outer JOIN user on user.userid = group.userid"
                . " where group.customerno=%d AND group.isdeleted=0 order by groupname ASC";
        $groupQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($groupQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $group = new VOGroup();
                $group->groupid = $row['groupid'];
                $group->groupname = $row['groupname'];
                $group->groupcode = $row['code'];
                $group->realname = $row['realname'];
                if ($row['timestamp'] == '0000-00-00 00:00:00') {
                    $group->timestamp = "";
                } else {
                    $group->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp']));
                }
                $groups[] = $group;
            }
            return $groups;
        }
        return NULL;
    }

    public function getallgroups_detail_hierarchy() {
        $groups = Array();
        $Query = "SELECT group.groupid,groupname,group.cityid,group.code,group.cityid,group.timestamp,user.realname "
                . " FROM `group` left Outer JOIN user on user.userid = group.userid"
                . " where group.customerno=%d AND group.isdeleted=0 order by groupname ASC";
        $groupQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($groupQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $group = new VOGroup();
                $group->groupid = $row['groupid'];
                $group->groupname = $row['groupname'];
                $group->groupcode = $row['code'];
                $group->realname = $row['realname'];
                if ($row['timestamp'] == '0000-00-00 00:00:00') {
                    $group->timestamp = "";
                } else {
                    $group->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp']));
                }
                $groups[] = $group;
            }
            return $groups;
        }
        return NULL;
    }

    public function get_work_master() {
        $Groups = Array();
        echo $Query = "SELECT * FROM probity_master where isdeleted=0 AND customerno IN(0,$this->_Customerno) order by pmid  ";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Group = new VOGroup();
                $Group->pmid = $row['pmid'];
                $Group->workkey = $row['workkey'];
                $Group->workkey_name = $row['workkey_name'];
                $Group->work_master = $row['work_master'];
                $Groups[] = $Group;
            }
            return $Groups;
        }
        return null;
    }

    public function getvehicleforgroup($groupid) {
        $vehicles = Array();
        $Query = "select vehicleid from vehicle where groupid=%s AND customerno=%d AND isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($groupid), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = $row['vehicleid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return NULL;
    }

    public function getvehiclenoforgroup($vehicleid) {
        $Query = "select * from vehicle where vehicleid=%s AND customerno=%s AND isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOGroup();
                //$checkpoint->checkpointid = $row['checkpointid'];
                $vehicle->vehicleno = $row['vehicleno'];
                //$checkpoint->vehicleid = $row['vehicleid'];
            }
            return $vehicle->vehicleno;
        }
        return NULL;
    }

    public function getmappedgroup($userid) {
        $Query = "SELECT * FROM `group` where customerno=%d AND isdeleted=0 limit 1";
        $GroupsQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Group = new VOGroup();
                $Group->groupid = $row['groupid'];
                $Group->groupname = $row['groupname'];
                //$Groups[] = $Group;
            }
            return $Group;
        }
        return null;
    }

    public function getusers_for_heirarchy($roleid, $id) {
        $users = Array();
        if ($roleid == 8) {
            $Query = "SELECT userid FROM  `user`  where roleid=%d AND groupid=%d AND customerno=%d AND isdeleted=0";
            $GroupsQuery = sprintf($Query, $roleid, $id, $this->_Customerno);
        } else {
            $Query = "SELECT userid FROM  `user`  where roleid=%d AND heirarchy_id=%d AND customerno=%d AND isdeleted=0";
            $GroupsQuery = sprintf($Query, $roleid, $id, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($GroupsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Group = new VOGroup();
                $Group->userid = $row['userid'];
                $users[] = $Group;
            }
            return $users;
        }
        return null;
    }

    public function getdistrict_from_city($cityid) {
        $Query = "SELECT districtid FROM `city` where cityid=%d AND customerno=%d LIMIT 1";
        $GroupsQuery = sprintf($Query, $cityid, $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['districtid'];
            }
        }
        return null;
    }

    public function getstate_from_district($districtid) {
        $Query = "SELECT stateid FROM `district` where districtid=%d AND customerno=%d LIMIT 1";
        $GroupsQuery = sprintf($Query, $districtid, $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['stateid'];
            }
        }
        return null;
    }

    public function add_group($groupname, $grouparray, $cityid, $code, $address, $userid) {
        $Query = "INSERT INTO `group` (groupname,cityid,code,address,customerno,userid,isdeleted,timestamp) VALUES ('%s',%d,'%s','%s','%d','%d','0','%s')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($groupname), Sanitise::Long($cityid), Sanitise::String($code), Sanitise::String($address), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays));
        $this->_databaseManager->executeQuery($SQL);
        $group->groupid = $this->_databaseManager->get_insertedId();
        $cityusers = Array();
        $districtusers = Array();
        $stateusers = Array();
        if ($_SESSION["use_maintenance"] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $cityusers = $this->getusers_for_heirarchy(4, $cityid);
            $districtid = $this->getdistrict_from_city($cityid);
            $districtusers = $this->getusers_for_heirarchy(3, $districtid);
            $stateid = $this->getstate_from_district($districtid);
            $stateusers = $this->getusers_for_heirarchy(2, $stateid);
            foreach ($cityusers as $thisuser) {
                $Query = "INSERT INTO `groupman` (`groupid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','0','%s')";
                $date = new Date();
                $today = $date->MySQLNow();
                $SQL = sprintf($Query, Sanitise::Long($group->groupid), $this->_Customerno, Sanitise::Long($thisuser->userid), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
            foreach ($districtusers as $thisuser) {
                $Query = "INSERT INTO `groupman` (`groupid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','0','%s')";
                $date = new Date();
                $today = $date->MySQLNow();
                $SQL = sprintf($Query, Sanitise::Long($group->groupid), $this->_Customerno, Sanitise::Long($thisuser->userid), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
            foreach ($stateusers as $thisuser) {
                $Query = "INSERT INTO `groupman` (`groupid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','0','%s')";
                $date = new Date();
                $today = $date->MySQLNow();
                $SQL = sprintf($Query, Sanitise::Long($group->groupid), $this->_Customerno, Sanitise::Long($thisuser->userid), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        $vehicleArray = explode(",", $grouparray);
        foreach ($vehicleArray as $vehicleid) {
            if ($vehicleid != '') {
                $Query = "UPDATE `vehicle` SET `groupid` = %d WHERE `vehicleid` = %d AND customerno = %d";
                $SQL = sprintf($Query, Sanitise::Long($group->groupid), Sanitise::Long($vehicleid), $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
            }
        }
    }

    public function edit_group($groupid, $grouparray, $groupname, $code, $userid) {
        $date = new Date();
        $today = $date->MySQLNow();
        $Query = "UPDATE `vehicle` SET `groupid` = 0,userid=%d WHERE groupid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($groupid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE `group` SET code='%s', groupname='%s',userid=%d WHERE groupid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($code), Sanitise::String($groupname), Sanitise::Long($userid), Sanitise::Long($groupid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $vehicleArray = explode(",", $grouparray);
        foreach ($vehicleArray as $vehicleid) {
            if ($vehicleid != '') {
                $Query = "UPDATE `vehicle` SET `groupid` = %d WHERE `vehicleid` = %d AND customerno = %d";
                $SQL = sprintf($Query, Sanitise::Long($groupid), Sanitise::Long($vehicleid), $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
            }
        }
    }

    public function getgroupsbyuserid($userid) {
        $groups = Array();
        $Query = "select group.groupid,group.groupname from `group`
        INNER JOIN groupman ON groupman.groupid = group.groupid
        INNER JOIN user ON user.userid = groupman.userid
        where groupman.userid=%s AND groupman.customerno=%s AND group.isdeleted=0 AND groupman.isdeleted=0 order by group.groupname ASC";
        //$Query = "SELECT groupid,groupname FROM `group` where userid=%d AND customerno=%d AND isdeleted=0";
        $groupQuery = sprintf($Query, Sanitise::Long($userid), $this->_Customerno);
        $this->_databaseManager->executeQuery($groupQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $group = new VOGroup();
                $group->groupid = $row['groupid'];
                $group->groupname = $row['groupname'];
                $groups[] = $group;
            }
            return $groups;
        }
        return NULL;
    }

    public function DeleteGroup($groupid, $userid) {
        $Query = "UPDATE `group` SET isdeleted=1,userid=%d WHERE groupid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($groupid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $date = new Date();
            $today = $date->MySQLNow();
            $Query = "UPDATE `groupman` SET isdeleted = '1',timestamp='%s' WHERE groupid=%d AND customerno=%d";
            $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::Long($groupid), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
        }
        $Query = "UPDATE `vehicle` SET `groupid` = 0,userid=%d WHERE groupid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($groupid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getgroupname($groupid, $groupname = null) {
        $groupCondition = '';
        $groupname = trim($groupname);
        if ($groupid == null && $groupname != null) {
            $groupCondition = " and group.groupname = '$groupname' ";
        } elseif ($groupid != null) {
            $groupCondition = " and group.groupid = $groupid ";
        }
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT group.groupid,group.groupname,group.code as group_code,group.address as group_address, group.cityid FROM `group`
      where group.customerno=%d $groupCondition AND group.isdeleted=0";
            $groupQuery = sprintf($Query, $this->_Customerno);
            $this->_databaseManager->executeQuery($groupQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $group = new VOGroup();
                    $group->groupid = $row['groupid'];
                    $group->groupname = $row['groupname'];
                    $group->cityid = $row['cityid'];
                    $group->code = $row['group_code'];
                    $group->address = $row['group_address'];
                    $group->hierarchy = "";
                }
                return $group;
            }
            return NULL;
        } else {
            $Query = "SELECT groupid,groupname,cityid,code,address FROM `group` where customerno=%d $groupCondition AND isdeleted=0";
            $groupQuery = sprintf($Query, $this->_Customerno);
            $this->_databaseManager->executeQuery($groupQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $group = new VOGroup();
                    $group->groupid = $row['groupid'];
                    $group->groupname = $row['groupname'];
                    $group->cityid = $row['cityid'];
                    $group->code = $row['code'];
                    $group->address = $row['address'];
                }
                return $group;
            }
            return NULL;
        }
    }

    public function getVehicles_ByGroup($groupid, $isWarehouse = null) {
        $vehicles = Array();
        $vehiclekind = '';
        if ($isWarehouse == null) {
            $vehiclekind = ' and vehicle.kind <> "Warehouse"';
        } else {
            $vehiclekind = ' and vehicle.kind = "Warehouse"';
        }
        $Query = "select vehicleid,vehicleno from vehicle where groupid in(%s) AND customerno=%d AND isdeleted=0 $vehiclekind";
        $vehiclesQuery = sprintf($Query, Sanitise::string($groupid), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new stdClass();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return NULL;
    }

    public function getUsers_by_customerno_role($param) {
        /**
         * @author Modified by Suman Sharma <suman.elixiatech@gmail.com>
         * @param array of string $param['groupid'] = array(1) or $param['groupid'] = array(1,2,3)
         */
        $roleid = isset($param['roleid']) ? 37/*$param['roleid']*/ : $_SESSION['roleid'];
        /* $Query = "SELECT user.userid,user.realname as role_username,user.phone,user.roleid,user.role,user.email as role_useremail,
          user.heirarchy_id, (select u.realname from user as u where u.userid=user.heirarchy_id) as zonalIs,
          g.groupid, g.groupname, c.code as citycode, c.name as region, d.code as districtcode, d.name as zone
          FROM user
          INNER JOIN groupman gm on gm.userid = user.userid
          INNER JOIN `group` g on g.groupid = gm.groupid
          INNER JOIN city c on c.cityid = g.cityid
          INNER JOIN district d on d.districtid = c.districtid
          WHERE user.customerno in (%s) and user.roleid in (%s)
          and user.isdeleted=0 and gm.isdeleted=0 and g.isdeleted=0 and c.isdeleted=0 and d.isdeleted=0
          ORDER BY `user`.`role` DESC";
         */

        $Query = "SELECT user.userid as branch_userid,user.realname as branch_username,user.phone as branch_userphone,
            user.email as branch_useremail,user.roleid as branch_userroleid,user.role as branch_userrolename,
            user.heirarchy_id as regional_userid,
            (select u.realname from user as u where u.userid=user.heirarchy_id) as regional_username,
            (select u.phone from user as u where u.userid=user.heirarchy_id) as regional_phone,
            (select u.email from user as u where u.userid=user.heirarchy_id) as regional_email,
            (select u1.realname from user as u1 where u1.userid = (select u2.heirarchy_id from user as u2 where u2.userid = user.heirarchy_id)) as zonalIs,
            g.groupid as branch_id, g.groupname as branch_name, c.code as regional_code, c.name as regional_name,
            d.code as zonal_code,
            d.name as zonal_name
            FROM user
                INNER JOIN groupman gm on gm.userid = user.userid
                INNER JOIN `group` g on g.groupid = gm.groupid
                INNER JOIN city c on c.cityid = g.cityid
                INNER JOIN district d on d.districtid = c.districtid
            WHERE user.customerno in (%s) and user.roleid in (%s)
            and user.isdeleted=0 and gm.isdeleted=0 and g.isdeleted=0 and c.isdeleted=0 and d.isdeleted=0
            ORDER BY `user`.`role` DESC";

        $userQuery = sprintf($Query, $this->_Customerno, Sanitise::string($roleid));
        $this->_databaseManager->executeQuery($userQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                $user->branch_userid = $row['branch_userid'];
                $user->branch_username = $row['branch_username'];
                $user->branch_userphone = $row['branch_userphone'];
                $user->branch_useremail = $row['branch_useremail'];
                $user->branch_userroleid = $row['branch_userroleid'];
                $user->branch_userrolename = $row['branch_userrolename'];
                $user->branch_id = $row['branch_id'];
                $user->branch_name = $row['branch_name'];
                $user->regional_userid = $row['regional_userid'];
                $user->regional_username = $row['regional_username'];
                $user->regional_phone = $row['regional_phone'];
                $user->regional_email = $row['regional_email'];
                $user->regional_code = $row['regional_code'];
                $user->regional_name = $row['regional_name'];
                $user->zonalIs = $row['zonalIs'];
                $user->zonal_code = $row['zonal_code'];
                $user->zonal_name = $row['zonal_name'];
                /*
                $user->userid = $row['userid'];
                $user->role_username = $row['role_username'];
                $user->phone = $row['phone'];
                $user->roleid = $row['roleid'];
                $user->role = $row['role'];
                $user->role_useremail = $row['role_useremail'];
                $user->heirarchy_id = $row['heirarchy_id'];
                $user->zonalIs = $row['zonalIs'];
                $user->groupid = $row['groupid'];
                $user->groupname = $row['groupname'];
                $user->citycode = $row['citycode'];
                $user->region = $row['region'];
                $user->districtcode = $row['districtcode'];
                $user->zone = $row['zone'];
                 */
                $users[] = $user;
            }
            return $users;
        }
        return NULL;
    }

}
