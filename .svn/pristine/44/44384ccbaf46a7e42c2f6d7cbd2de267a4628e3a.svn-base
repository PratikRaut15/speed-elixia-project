<?php
include 'account_functions.php';

if (isset($_POST['username'])) {
    checkusername($_POST['username']);
}
if (isset($_POST['roleid'])) {
    $objRole = new Hierarchy();
    $objRole->roleid = $_POST['roleid'];
    $objRole->moduleid = 1;
    $objRole->customerno = $_SESSION['customerno'];
    $users = getUsersForParentRole($objRole);

    if (isset($users) && !empty($users)) {
        echo "<option value='-1'>Select Parent</option>";
        foreach ($users as $user) {
            if (isset($_POST['parentid']) && $_POST['parentid'] == $user['userid']) {
                ?>  <option value="<?php echo $user['userid'] ?>"  selected=""><?php echo $user['realname'] ?> </option> <?php
            } else {
                ?><option value="<?php echo $user['userid'] ?>"><?php echo $user['realname'] ?> </option>
                <?php
            }
        }
    }
}
if (isset($_POST['groupparentid'])) {
    $objRole = new Hierarchy();
    $objRole->roleid = $_POST['groupparentid'];
    $objRole->moduleid = 1;
    $objRole->customerno = $_SESSION['customerno'];
    $groups = getUserGroups($objRole);
    if (isset($groups) && !empty($groups)) {
        echo "<option value='-1'>Select Groups</option>";
        foreach ($groups as $group) {
            ?>
            <option value="<?php echo $group['groupid'] ?>"><?php echo $group['groupname'] ?> </option> <?php
        }
    }
}
if (isset($_POST['work']) && $_POST['work'] == 'higherRoles') {
    $objRole = new Hierarchy();
    $objRole->roleid = $_POST['role'];
    $objRole->moduleid = 1;
    $objRole->customerno = $_SESSION['customerno'];
    $users = getHigherUsersForRole($objRole);
    if (isset($users) && !empty($users)) {
        echo "<option value='-1'>Select Higher Users</option>";
        foreach ($users as $user) {
            if (isset($_POST['parentid']) && $_POST['parentid'] == $user['userid']) {
                ?>  <option value="<?php echo $user['userid'] ?>" selected=""><?php echo $user['realname'] ?> </option> <?php
            } else {
                ?><option value="<?php echo $user['userid'] ?>"><?php echo $user['realname'] ?> </option>
                <?php
            }
        }
    }
}
if (isset($_POST['work']) && $_POST['work'] == 'pullVehiclesByGroup') {
    $isWarehouse = null;
    if ($_SESSION['switch_to'] == '3') {
        $isWarehouse = 1;
    }
    $groups = implode(',', $_POST['group']);
    $grpManager = new GroupManager($_SESSION['customerno']);
    $vehiclelist = $grpManager->getVehicles_ByGroup($groups, $isWarehouse);


    if (!isset($vehiclelist)) {
//       echo "<option value='0'>All Vehicles</option>";
    } else {
        echo "<option>Select Vehicles</option>";
        foreach ($vehiclelist as $row) {
            echo "<option value='" . $row->vehicleid . "'>" . $row->vehicleno . "</option>";
        }
    }
}
if (isset($_POST['work']) && $_POST['work'] == 'removeVehiclesByGroup') {
    $isWarehouse = null;
    if ($_SESSION['switch_to'] == '3') {
        $isWarehouse = 1;
    }
    $group_no = $_POST['group'];
    $grpManager = new GroupManager($_SESSION['customerno']);
    $vehiclelist = $grpManager->getVehicles_ByGroup($group_no, $isWarehouse);
    echo json_encode($vehiclelist);
}
if (isset($_POST['work']) && $_POST['work'] == 'modifyUserReports') {
    $details = $_POST;
    $reportList = $details['reportList'];
    $user = new VOUser();
    $Reports = array();
    $user->userid = GetSafeValueString($details["userid"], "string");
    $user->created_by = $_SESSION['userid'];
    $user->customerno = $_SESSION['customerno'];
    $reportList = explode(',', $reportList);
    foreach ($reportList as $reportId) {
        if ($details["reportTime_" . $reportId] != '-1') {
            $time = $details["reportTime_" . $reportId];
            $report = new stdClass();
            $report->reportId = $reportId;
            $report->reportTime = $time;
            $Reports[] = $report;
        }
    }
    $user->reports = $Reports;

    if (isset($details['temprepinterval']) && $details['temprepinterval'] > 0) {
        $user->temprepinterval = $details['temprepinterval'];
    }
    if (isset($details['vehrepinterval']) && $details['vehrepinterval'] > 0) {
        $user->vehrepinterval = $details['vehrepinterval'];
    }
    updateUserReports($user);
}
if (isset($_POST['action']) && $_POST['action'] == 'getmenus') {
    getmenus();
}
if (isset($_REQUEST['work']) && $_REQUEST['work'] == "getvehicle" && isset($_REQUEST['userid'])) {
    $userid = $_REQUEST['userid'];
    $term = $_REQUEST['term'];
    $vehicleids = getVehiclesByUser($userid,$term);
    echo $vehicleids;
}
?>
