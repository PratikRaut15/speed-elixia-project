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
				$Query .= " AND `group`.groupid =%d";
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
			$Query = "SELECT groupid,groupname,`group`.cityid FROM `group` "
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

	public function getallgroupsbysequence() {
		$groups = Array();
		if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
			$Query = "SELECT groupid,groupname,,sequence,cityid FROM `group` "
				. "where customerno=%d AND isdeleted=0 order by sequence DESC";
			$groupQuery = sprintf($Query, $this->_Customerno);
		} else {
			$Query = "SELECT groupid,groupname,sequence,`group`.cityid FROM `group` "
				. "where customerno=%d AND isdeleted=0 order by sequence DESC";
			 $groupQuery = sprintf($Query, $this->_Customerno);
		}
		$this->_databaseManager->executeQuery($groupQuery);
		if ($this->_databaseManager->get_rowCount() > 0) {
			while ($row = $this->_databaseManager->get_nextRow()) {
				$group = new VOGroup();
				$group->groupid = $row['groupid'];
				$group->groupname = $row['groupname'];
				$group->cityid = $row['cityid'];
				$group->sequence = $row['sequence'];
				$groups[] = $group;
			}
			return $groups;
		}
		return NULL;
	}

	public function getallgroups_detail() {
		$groups = Array();
		$Query = "SELECT `group`.groupid,groupname,`group`.cityid,`group`.code,`group`.cityid,`group`.timestamp,user.realname "
			. " FROM `group` left Outer JOIN user on user.userid = `group`.userid"
			. " where `group`.customerno=%d AND `group`.isdeleted=0 order by groupname ASC";
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
		$Query = "SELECT `group`.groupid,groupname,`group`.cityid,`group`.code,`group`.cityid,`group`.timestamp,user.realname "
			. " FROM `group` left Outer JOIN user on user.userid = `group`.userid"
			. " where `group`.customerno=%d AND `group`.isdeleted=0 order by groupname ASC";
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
		$Query = "SELECT * FROM probity_master where isdeleted=0 AND customerno IN(0,$this->_Customerno) order by pmid  ";
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
		$date = new Date();
		$todays = $date->MySQLNow();
		$Query = "INSERT INTO `group` (groupname,cityid,code,address,customerno,userid,isdeleted,timestamp,createdOn,updatedOn) VALUES ('%s',%d,'%s','%s','%d','%d','0','%s','%s','%s')";

		$SQL = sprintf($Query, Sanitise::String($groupname), Sanitise::Long($cityid), Sanitise::String($code), Sanitise::String($address), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays), Sanitise::DateTime($todays), Sanitise::DateTime($todays));

		$this->_databaseManager->executeQuery($SQL);
		$groupid = $this->_databaseManager->get_insertedId();

		/*To insert data in group_history */
		$historyQuery = "INSERT INTO `group_history` (groupid,groupname,cityid,code,customerno,userid,isdeleted,timestamp,createdOn,updatedOn) VALUES (%d,'%s',%d,'%s','%s','%d','0','%s','%s','%s')";

		$historySQL = sprintf($historyQuery, Sanitise::Long($groupid), Sanitise::String($groupname), Sanitise::Long($cityid), Sanitise::String($code), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays), Sanitise::DateTime($todays), Sanitise::DateTime($todays));

		$this->_databaseManager->executeQuery($historySQL);

		$cityusers = Array();
		$districtusers = Array();
		$stateusers = Array();
		// if ($_SESSION["use_maintenance"] == '1' && $_SESSION['use_hierarchy'] == '1') {
		//     $cityusers = $this->getusers_for_heirarchy(4, $cityid);
		//     $districtid = $this->getdistrict_from_city($cityid);
		//     $districtusers = $this->getusers_for_heirarchy(3, $districtid);
		//     $stateid = $this->getstate_from_district($districtid);
		//     $stateusers = $this->getusers_for_heirarchy(2, $stateid);
		//     foreach ($cityusers as $thisuser) {
		//         $Query = "INSERT INTO `groupman` (`groupid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','0','%s')";
		//         $date = new Date();
		//         $today = $date->MySQLNow();
		//         $SQL = sprintf($Query, Sanitise::Long($groupid), $this->_Customerno, Sanitise::Long($thisuser->userid), Sanitise::DateTime($today));
		//         $this->_databaseManager->executeQuery($SQL);
		//     }
		//     foreach ($districtusers as $thisuser) {
		//         $Query = "INSERT INTO `groupman` (`groupid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','0','%s')";
		//         $date = new Date();
		//         $today = $date->MySQLNow();
		//         $SQL = sprintf($Query, Sanitise::Long($groupid), $this->_Customerno, Sanitise::Long($thisuser->userid), Sanitise::DateTime($today));
		//         $this->_databaseManager->executeQuery($SQL);
		//     }
		//     foreach ($stateusers as $thisuser) {
		//         $Query = "INSERT INTO `groupman` (`groupid`,`customerno`,`userid`,`isdeleted`,`timestamp`) VALUES (%d,'%d','%d','0','%s')";
		//         $date = new Date();
		//         $today = $date->MySQLNow();
		//         $SQL = sprintf($Query, Sanitise::Long($groupid), $this->_Customerno, Sanitise::Long($thisuser->userid), Sanitise::DateTime($today));
		//         $this->_databaseManager->executeQuery($SQL);
		//     }
		// }
		$vehicleArray = explode(",", $grouparray);
		foreach ($vehicleArray as $vehicleid) {
			if ($vehicleid != '') {
				$Query = "UPDATE `vehicle` SET `groupid` = %d WHERE `vehicleid` = %d AND customerno = %d";
				$SQL = sprintf($Query, Sanitise::Long($groupid), Sanitise::Long($vehicleid), $this->_Customerno);
				$this->_databaseManager->executeQuery($SQL);
			}
		}

		/*To get HO of the customer #64
        .....Consider roleid as 33 i.e. Head Office*/
		if ($this->_Customerno == '64') {
			$roleid = 33;
			$subject = "New Group has been added successfully";
			$this->createMailBody($roleid, $subject, $userid, $groupname);
		}
	}

	public function edit_group($groupid, $grouparray, $groupname, $code, $userid, $cityid) {

		$date = new Date();
		$today = $date->MySQLNow();
		$Query = "UPDATE `vehicle` SET `groupid` = 0,userid=%d WHERE groupid=%d AND customerno=%d";
		$SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($groupid), $this->_Customerno);
		$this->_databaseManager->executeQuery($SQL);
		$Query = "UPDATE `vehicleusermapping` SET `isdeleted` = 1 WHERE `groupid` = %d AND `customerno`=%d";
		$SQL = sprintf($Query, Sanitise::Long($groupid), $this->_Customerno);
		//echo PHP_EOL.$SQL;
		$this->_databaseManager->executeQuery($SQL);

		$Query = "UPDATE `group` SET code='%s', groupname='%s',userid=%d ,cityid= %d, updatedOn='%s'
                WHERE groupid=%d AND customerno=%d";
		$SQL = sprintf($Query, Sanitise::String($code), Sanitise::String($groupname), Sanitise::Long($userid), Sanitise::Long($cityid), Sanitise::DateTime($today), Sanitise::Long($groupid), $this->_Customerno);
		$this->_databaseManager->executeQuery($SQL);

		/*To insert data in group_history */
		$historyQuery = "INSERT INTO `group_history` (groupid,groupname,cityid,code,customerno,userid,isdeleted,timestamp,createdOn,updatedOn) VALUES (%d,'%s',%d,'%s','%s','%d','%d','0','%s','%s')";
		$date = new Date();
		$todays = $date->MySQLNow();
		$historySQL = sprintf($historyQuery, Sanitise::Long($groupid), Sanitise::String($groupname), Sanitise::Long($cityid), Sanitise::String($code), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays), Sanitise::DateTime($todays), Sanitise::DateTime($todays));
		$this->_databaseManager->executeQuery($historySQL);
		if ($this->_Customerno == '64') {
			$roleid = 33;
			$subject = 'Group has been edited successfully';
			//$this->createMailBody($roleid, $subject, $userid, $groupname);
		}
		$vehicleArray = explode(",", $grouparray);
		foreach ($vehicleArray as $vehicleid) {
			if ($vehicleid != '') {
				$Query = "UPDATE `vehicle` SET `groupid` = %d WHERE `vehicleid` = %d AND customerno = %d";
				$SQL = sprintf($Query, Sanitise::Long($groupid), Sanitise::Long($vehicleid), $this->_Customerno);
				//echo PHP_EOL.$SQL;
				$this->_databaseManager->executeQuery($SQL);

				$Query = "UPDATE `vehicleusermapping` SET `isdeleted` = 0 WHERE `vehicleid` = %d AND `groupid` = %d AND `customerno`=%d";
				$SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($groupid), $this->_Customerno);
				//echo PHP_EOL.$SQL;
				$this->_databaseManager->executeQuery($SQL);
			}
		}
	}

	public function getgroupsbyuserid($userid) {
		$groups = Array();
		$Query = "select `group`.groupid,`group`.groupname from `group`
        INNER JOIN groupman ON groupman.groupid = `group`.groupid
        INNER JOIN user ON user.userid = groupman.userid
        where groupman.userid=%s AND groupman.customerno=%s AND `group`.isdeleted=0 AND groupman.isdeleted=0 order by `group`.groupname ASC";
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

	public function getusersbytripid($tripid) {
		$groups = Array();
		$Query = "select `tripusers`.addeduserid, `user`.realname from `tripusers`
        INNER JOIN user ON user.userid = tripusers.addeduserid
        where tripusers.tripid=%d AND `tripusers`.isdeleted=0 order by `user`.realname ASC";
		//$Query = "SELECT groupid,groupname FROM `group` where userid=%d AND customerno=%d AND isdeleted=0";
		$groupQuery = sprintf($Query, Sanitise::Long($tripid));
		$this->_databaseManager->executeQuery($groupQuery);
		if ($this->_databaseManager->get_rowCount() > 0) {
			while ($row = $this->_databaseManager->get_nextRow()) {
				$group = new VOGroup();
				$group->groupid = $row['addeduserid'];
				$group->groupname = $row['realname'];
				$groups[] = $group;
			}
			// print("<pre>");print_r($groups); die;
			return $groups;
		}
		return NULL;
	}

	public function DeleteGroup($groupid, $userid) {
		$date = new Date();
		$todays = $date->MySQLNow();
		$Query = "UPDATE `group` SET isdeleted=1,userid=%d,updatedOn='%s' WHERE groupid=%d AND customerno=%d";
		$SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::DateTime($todays), Sanitise::Long($groupid), $this->_Customerno);
		$this->_databaseManager->executeQuery($SQL);

		/*select */
		$selquery = "SELECT * FROM `group` WHERE groupid = " . $groupid . "";
		$SQL = sprintf($selquery, Sanitise::Long($groupid));
		$this->_databaseManager->executeQuery($SQL);
		if ($this->_databaseManager->get_rowCount() > 0) {
			while ($row = $this->_databaseManager->get_nextRow()) {
				$groupname = $row['groupname'];
				$cityid = $row['cityid'];
				$code = $row['code'];
				$customerno = $row['customerno'];
				$userid = $row['userid'];
			}
		}

		/*To insert data in group_history */
		$historyQuery = "INSERT INTO `group_history` (groupid,groupname,cityid,code,customerno,userid,isdeleted,timestamp,createdOn,updatedOn) VALUES (%d,'%s',%d,'%s',%d,%d,1,'%s','%s','%s')";

		$historySQL = sprintf($historyQuery, Sanitise::Long($groupid), Sanitise::String($groupname), Sanitise::Long($cityid), Sanitise::String($code), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays), Sanitise::DateTime($todays), Sanitise::DateTime($todays));

		$this->_databaseManager->executeQuery($historySQL);
		if ($this->_Customerno == '64') {
			/*send mail to HO*/
			$roleid = 33;
			$subject = 'Group has been deleted successfully';
			$this->createMailBody($roleid, $subject, $userid, $groupname);
		}
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
			$groupCondition = " and `group`.groupname = '$groupname' ";
		} elseif ($groupid != null) {
			$groupCondition = " and `group`.groupid = $groupid ";
		}
		if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
			$Query = "SELECT `group`.groupid,`group`.groupname,`group`.code as group_code,`group`.address as group_address, `group`.cityid FROM `group`
      where `group`.customerno=%d $groupCondition AND `group`.isdeleted=0";
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

	public function searchgroupname($groupname = null) {
		$groupCondition = '';
		$groupname = trim($groupname);
		if (isset($groupname)) {
                    $groupCondition = " and `group`.groupname LIKE '%$groupname%' ";
		}
		if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
                    $Query = "  SELECT  `group`.groupid
                                        ,`group`.groupname
                                        ,`group`.code as group_code
                                        ,`group`.address as group_address
                                        , `group`.cityid 
                                FROM    `group`
                                WHERE   `group`.customerno = %d 
                                $groupCondition 
                                AND     `group`.isdeleted=0";
                    $groupQuery = sprintf($Query, $this->_Customerno);
                    $this->_databaseManager->executeQuery($groupQuery);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        $groups = array();
                        while ($row = $this->_databaseManager->get_nextRow()) {
                                $group = new VOGroup();
                                $group->groupid = $row['groupid'];
                                $group->groupname = $row['groupname'];
                                $group->cityid = $row['cityid'];
                                $group->code = $row['group_code'];
                                $group->address = $row['group_address'];
                                $group->hierarchy = "";
                                $groups[]=$group;
                        }
                        return $groups;
                    }
                    return NULL;
		} else {
                    $Query = "  SELECT  groupid
                                        ,groupname
                                        ,cityid
                                        ,code
                                        ,address 
                                FROM    `group` 
                                WHERE   customerno = %d 
                                $groupCondition 
                                AND     isdeleted = 0";
                    $groupQuery = sprintf($Query, $this->_Customerno);
                    $this->_databaseManager->executeQuery($groupQuery);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        $groups = array();
                        while ($row = $this->_databaseManager->get_nextRow()) {
                                $group = new VOGroup();
                                $group->groupid = $row['groupid'];
                                $group->groupname = $row['groupname'];
                                $group->cityid = $row['cityid'];
                                $group->code = $row['code'];
                                $group->address = $row['address'];
                                $groups[]=$group;
                        }
                        return $groups;
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
		$Query = "select vehicle.vehicleid,vehicle.vehicleno from vehicle
        inner join unit on vehicle.uid = unit.uid
        inner join devices on vehicle.uid = devices.uid
        where vehicle.groupid in(%s) AND vehicle.customerno='%d' AND vehicle.isdeleted=0 $vehiclekind";

		$vehiclesQuery = sprintf($Query, Sanitise::string($groupid), $this->_Customerno);
		$this->_databaseManager->executeQuery($vehiclesQuery);
		/*Added empty check for vehicles*/
		//if(!empty($this->_databaseManager->get_rowCount())){
		if ($this->_databaseManager->get_rowCount() > 0) {
			while ($row = $this->_databaseManager->get_nextRow()) {
				$vehicle = new stdClass();
				$vehicle->vehicleid = $row['vehicleid'];
				$vehicle->vehicleno = $row['vehicleno'];
				$vehicles[] = $vehicle;
			}
			return $vehicles;
		}
		//}
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

	public function getRegions() {
		$selectQuery = "SELECT cityid,name,districtid FROM city WHERE customerno=%d";
		$regionQuery = sprintf($selectQuery, $this->_Customerno);

		$this->_databaseManager->executeQuery($regionQuery);
		if ($this->_databaseManager->get_rowCount() > 0) {
			$regionList = Array();
			$regions = Array();
			while ($row = $this->_databaseManager->get_nextRow()) {
				$regionList['regionId'] = $row['cityid'];
				$regionList['regionName'] = $row['name'];
				$regionList['zoneId'] = $row['districtid'];
				$regions[] = $regionList;
			}

			return $regions;
		}
	}

	public function getZoneList($regionId = '') {
		if (isset($regionId) && !empty($regionId)) {
			$selectZoneQuery = "SELECT district.districtid as zoneId,district.name as zoneName
                            FROM district LEFT JOIN city ON district.districtid = city.districtid
                            WHERE city.cityid =%d";
			//AND customerno=%d";
			$zoneQuery = sprintf($selectZoneQuery, Sanitise::Long($regionId));

			$this->_databaseManager->executeQuery($zoneQuery);
			$zoneName = Array();
			if ($this->_databaseManager->get_rowCount() > 0) {
				while ($row = $this->_databaseManager->get_nextRow()) {
					$zoneName['zoneName'] = $row['zoneName'];
					$zoneName['zoneId'] = $row['zoneId'];
				}
				echo json_encode($zoneName);
			}
		} else {
			$selectZoneQuery = "SELECT district.districtid as zoneId,district.name as zoneName
                                FROM district
                                ";
			//AND customerno=%d";
			$zoneQuery = sprintf($selectZoneQuery);

			$this->_databaseManager->executeQuery($zoneQuery);
			$zoneName = Array();
			$zoneList = Array();
			if ($this->_databaseManager->get_rowCount() > 0) {
				while ($row = $this->_databaseManager->get_nextRow()) {
					$zoneName['zoneName'] = $row['zoneName'];
					$zoneName['zoneId'] = $row['zoneId'];
					$zoneList[] = $zoneName;
				}
				return $zoneList;
			}
		}
	}

	public function insertIntoRegions($postDataArray) {

		$date = new Date();
		$todays = $date->MySQLNow();
		$userId = $_SESSION['userid'];
		$insertQuery = "INSERT INTO `city` (districtid, code,name, customerno, userid, isdeleted, `timestamp`,createdOn,updatedOn)
        VALUES (%d, '%s', '%s', %d, %d, %d,'%s','%s','%s')";

		$SQL = sprintf($insertQuery, Sanitise::Long($postDataArray['regionZoneId']), Sanitise::String($postDataArray['regionCode']), Sanitise::String($postDataArray['regionName']), Sanitise::Long($this->_Customerno), Sanitise::Long($userId), 0, Sanitise::DateTime($todays), Sanitise::DateTime($todays), Sanitise::DateTime($todays));

		$this->_databaseManager->executeQuery($SQL);

		$regionid = $this->_databaseManager->get_insertedId();

		/*send mail to HO*/
		$roleid = 33;
		$subject = 'New region has been created successfully';
		$this->createMailBody($roleid, $subject, $userid, $groupname);

		/*Insert into city_history*/
		$insertHistQuery = "INSERT INTO `city_history` (cityid,districtid, code,name, customerno, userid, isdeleted, `timestamp`,createdOn,updatedOn)
            VALUES (%d,%d, '%s', '%s', %d, %d, %d,'%s','%s','%s')";

		$insertHistSQL = sprintf($insertHistQuery, Sanitise::Long($regionid), Sanitise::Long($postDataArray['regionZoneId']), Sanitise::String($postDataArray['regionCode']), Sanitise::String($postDataArray['regionName']), Sanitise::Long($this->_Customerno), Sanitise::Long($userId), 0, Sanitise::DateTime($todays), Sanitise::DateTime($todays), Sanitise::DateTime($todays));

		$this->_databaseManager->executeQuery($insertHistSQL);
		echo json_encode('success');
	}

	function insertIntoZones($postDataArray) {
		$date = new Date();
		$todays = $date->MySQLNow();
		$userId = $_SESSION['userid'];

		$insertQuery = "INSERT INTO `district`(stateid,code,name,customerno,userid, isdeleted, `timestamp`,createdOn,updatedOn)
        VALUES (%d, '%s','%s', %d, %d, %d,'%s','%s','%s')";

		$SQL = sprintf($insertQuery, Sanitise::Long($postDataArray['state']), Sanitise::String($postDataArray['zoneCode']), Sanitise::String($postDataArray['zoneName']), Sanitise::Long($this->_Customerno), Sanitise::Long($userId), 0, Sanitise::DateTime($todays), Sanitise::DateTime($todays), Sanitise::DateTime($todays));

		$this->_databaseManager->executeQuery($SQL);
		$zoneid = $this->_databaseManager->get_insertedId();

		/*send mail to HO*/
		$roleid = 33;
		$subject = 'New zone has been created successfully';
		$this->createMailBody($roleid, $subject, $userid, $groupname);

		/*insert into district_history*/
		$insertQuery = "INSERT INTO `district_history`(districtid,stateid,code,name,customerno,userid, isdeleted, `timestamp`,createdOn,updatedOn)
                VALUES (%d,%d, '%s','%s', %d, %d, %d,'%s','%s','%s')";

		$SQL = sprintf($insertQuery, Sanitise::Long($zoneid), Sanitise::Long($postDataArray['state']), Sanitise::String($postDataArray['zoneCode']), Sanitise::String($postDataArray['zoneName']), Sanitise::Long($this->_Customerno), Sanitise::Long($userId), 0, Sanitise::DateTime($todays), Sanitise::DateTime($todays), Sanitise::DateTime($todays));
		echo json_encode('success');
	}

	function getStates() {
		$selectStateQuery = "SELECT state.stateid as stateId,state.name as stateName
                             FROM state";
		$stateQuery = sprintf($selectStateQuery);
		$this->_databaseManager->executeQuery($stateQuery);
		$zoneName = Array();
		$zoneList = Array();
		if ($this->_databaseManager->get_rowCount() > 0) {
			while ($row = $this->_databaseManager->get_nextRow()) {
				$stateName['stateName'] = $row['stateName'];
				$stateName['stateId'] = $row['stateId'];
				$stateList[] = $stateName;
			}
			return $stateList;
		}
	}

	/*Group details required to display in edit group for customer number 64*/
	function getAllGroupDetails($groupid) {
		$selectgroupdetails = "SELECT `group`.groupid,`group`.groupname, `group`.code as groupcode,`group`                  .cityid,`city`.districtid
                               FROM  `group` LEFT JOIN `city` ON `group`.cityid = `city`.cityid
                               WHERE `group`.customerno = %d AND `group`.groupid = %d";
		$groupQuery = sprintf($selectgroupdetails, Sanitise::Long($this->_Customerno), Sanitise::Long($groupid));
		$this->_databaseManager->executeQuery($groupQuery);
		if ($this->_databaseManager->get_rowCount() > 0) {
			while ($row = $this->_databaseManager->get_nextRow()) {
				$groupDetails['groupid'] = $row['groupid'];
				$groupDetails['groupname'] = $row['groupname'];
				$groupDetails['regionid'] = $row['cityid'];
				$groupDetails['zoneid'] = $row['districtid'];
				$groupDetails['groupcode'] = $row['groupcode'];
			}
			return $groupDetails;
		}
	}

	function createMailBody($roleid, $subject, $userid, $groupname) {
		/*To get HO of the customer #64 */
		/*To find username of the user id who is logged in*/
		$selectUserQuery = "SELECT realname FROM user WHERE userid = %d ";
		$userSql = sprintf($selectUserQuery, Sanitise::Long($userid));

		$this->_databaseManager->executeQuery($userSql);
		if ($this->_databaseManager->get_rowCount() > 0) {
			while ($row = $this->_databaseManager->get_nextRow()) {
				$username = $row['realname'];
			}
		}
		/*.....Consider roleid as 33 i.e. Head Office*/

		$selectHOQuery = "SELECT email,realname,userid FROM user WHERE roleid = %d ";
		$hoSql = sprintf($selectHOQuery, Sanitise::Long($roleid));
		$this->_databaseManager->executeQuery($hoSql);
		$users = array();
		$usersArray = array();
		if ($this->_databaseManager->get_rowCount() > 0) {
			while ($row = $this->_databaseManager->get_nextRow()) {
				$userlist[] = $row['email'];
				$users['username'] = $row['realname'];
				$usersArray[] = $users;
			}
		}
		$message = '';

		foreach ($usersArray as $key => $values) {
			$placehoders['{{username}}'] = $values['username'];
			$placehoders['{{title}}'] = $subject;
			$placehoders['{{content}}'] = $subject . "To" . $groupname . "By " . $username;
			$html = file_get_contents('../emailtemplates/customer/64/addGroupTemplate.html');

			foreach ($placehoders as $key => $val) {
				$html = str_replace($key, $val, $html);
			}
			$message .= $html;
			if ($message != '') {
				$attachmentFilePath = '';
				$attachmentFileName = '';
				$CCEmail = '';
				$BCCEmail = '';

				$isMailSent = sendMailUtil($userlist, $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
				if ($isMailSent == 1) {
					echo $message . "<br/>";
				}
			}
			$message = '';
		}
	}

	public function getGroupHistoryLogs($obj) {
		$uiList = ["groupname", "code", "branch_region"];
		$arrResult = array();
		$db = new DatabaseManager();
		$pdo = $db->CreatePDOConn();
		$sp_params = "'" . $_SESSION['customerno'] . "'" .
		",'" . $obj->groupId . "'" .
		",'" . date("Y-m-d", strtotime($obj->start_date)) . "'" .
		",'" . date("Y-m-d", strtotime($obj->end_date)) . "'" .
		",'" . $obj->total_records . "'";
		$queryCallSP = "CALL " . speedConstants::SP_GET_GROUP_LOGS . "(" . $sp_params . ")";
		$arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
		$finalArray = array();
		foreach ($arrResult as $row) {
			$data['groupname'] = $row['groupname'];
			$data['code'] = $row['code'];
			$data['branch_region'] = $row['branch_region'];
			$data['insertedBy'] = $row['realname'];
			$data['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['createdOn']));
			$finalArray[] = $data;
		}
		$finalArray = array_reverse($finalArray);
		foreach ($finalArray as $k => &$record) {
			foreach ($record as $column => &$value) {
				if (in_array($column, $uiList)) {
					$record[$column . "ui"] = $value;
					if (isset($finalArray[$k - 1][$column])) {
						if (isset($finalArray[$k - 1][$column]) && $finalArray[$k - 1][$column] != $value) {
							$record[$column . "ui"] = "<font color='green'>" . $value . "</font>";
							$finalArray[$k - 1][$column . "ui"] = "<font color='red'>" . $finalArray[$k - 1][$column] . "</font>";
						}
					}
				}
			}
		}
		$finalArray = array_reverse($finalArray);
		return $finalArray;
	}

	/*
	* Changes Made By : Pratik Raut
	* Date : 27-09-2019
	* change : Created a Function for group sequence
	*/  
	public function updateGroupSequenceNo($seq_array, $defaultseqarray){
		if (!in_array("", $seq_array)) {
			$i = 0;
			foreach (array_reverse($seq_array) as $key => $value) {
				$i++;
				//$sql = "call update_sequenceno($value,$i)";
				 $sql = "update `group` set sequence= '" . $i . "' where groupid='" . $value . "'";
				$test = sprintf($sql);
				$this->_databaseManager->executeQuery($test);
			}
		}
		if (!in_array("", $defaultseqarray)) {
			foreach ($defaultseqarray as $key => $value) {
				$i = 0;
				//$sql = "call update_sequenceno($value,$i)";
				$sql = "update `group` set sequence= 0 where groupid='" . $value . "'";
				$test2 = sprintf($sql);
				$this->_databaseManager->executeQuery($test2);
			}
		}
		return 'ok';
	}

	/*
	 * Changes ends Here
	 */
}
