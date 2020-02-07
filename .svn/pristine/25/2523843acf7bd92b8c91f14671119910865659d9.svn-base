<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "session.php";
include "loginorelse.php";
include_once "db.php";
include_once "../../constants/constants.php";
include_once "../../lib/system/Sanitise.php";
include_once "../../lib/system/DatabaseManager.php";
include_once "../../lib/system/Date.php";
include_once "../../lib/bo/TimeSheetManager.php";


$taskId = 0;
$taskFlag = 0;
$teamId = GetLoggedInUserId();
$today = date('Y-m-d H:i:s');
$time = 0;
$result = array();
$tMapId = 0;
if (isset($_POST['estimated_time'])) {
    $time = explode(":", $_POST['estimated_time']);
    $sum = 0;
    $i = 0;
    $m = 3600;
    foreach ($time as $t) {
        $sum += $t * $m;
        $m /= 60;
        $i++;
        if ($i == 2) {
            break;
        }
    }
    $time = $sum;
}

if (isset($_POST['existing_task'])) {
    $_POST['new_task'] = 1;
}
if (isset($_POST['new_task'])) {
    if ($taskFlag == 0) {
        $tm = new TimeSheetManager();
        $userList = array();
        $userTeamIds = array();
        if ($_POST['department'] == 1) {
            if (isset($_POST['assignTo'])) {
                $userList[] = $_POST['assignTr'];
                $userTeamIds[] = $_POST['assignTo'];
                $teamId = $_POST['assignTo'];
            } else {
                $userList[] = isset($_POST['devTr']) ? $_POST['devTr'] : 0;
                $userTeamIds[] = $_POST['developer'];
                $userList[] = isset($_POST['tesTr']) ? $_POST['tesTr'] : 0;
                $userTeamIds[] = $_POST['tester'];
                $userList[] = isset($_POST['migTr']) ? $_POST['migTr'] : 0;
                $userTeamIds[] = $_POST['migrator'];
            }
        } else {
            $userList[] = $_POST['assignTr'];
            $userTeamIds[] = $_POST['assignTo'];
            $teamId = $_POST['assignTo'];
        }
        $productList = array();
        $productList = isset($_POST['productList']) ? explode(',', substr($_POST['productList'], 1)) : 0;
        $customerList = isset($_POST['customerList']) ? explode(',', substr($_POST['customerList'], 1)) : 0;

        $taskObj = new stdClass();
        $taskObj->customerno = $_POST['customerno'];
        $taskObj->product = $_POST['product'];
        $taskObj->department = $_POST['department'];
        $taskObj->estimated_time = $time;
        $taskObj->task_name = $_POST['task_name'];
        $taskObj->task_description = $_POST['task_description'];
        $taskObj->status = '';
        if (!isset($_POST['existing_task'])) {
            $result = $tm->insertTask($taskObj);
            $taskId = $result['taskId'];
            if ($taskId > 0) {
                $taskFlag = 1;
            }
        } else {
            $taskId = $_POST['taskId'];
            $tMapId = $_POST['tMapId'];
            $result['closePreviousTask'] = $tm->closePreviousTask($tMapId, $teamId);
        }

        // if(isset($_POST['editTimesheet'])&&$_POST['editTimesheet']==1){

        // }
        $result['member'] = $tm->insertMemberMapping($userList, $taskId);
        $result['product'] = $tm->insertProductMapping($productList, $taskId);
        $result['customer'] = $tm->insertCustomerMapping($customerList, $taskId);
        $result['taskMapping'] = $tm->insertTaskMapping($taskId, $userTeamIds, $userList, $result['product']['pMapId'], $result['customer']['cMapId']);
        $taskFlag = 1;
    }
}

if (isset($_POST['fetch_timeSheet'])) {
    $tm = new TimeSheetManager();
    $result = $tm->fetchTimeSheet($_POST['teamId'], $_POST['date']);
}

if (isset($_POST['fetch_task'])) {
    $tm = new TimeSheetManager();
    $teamId = isset($_POST['teamId']) ? $_POST['teamId'] : 0;
    $taskId = isset($_POST['taskId']) ? $_POST['taskId'] : 0;
    $departmentId = isset($_POST['departmentId']) ? $_POST['departmentId'] : 0;
    $result = $tm->getTeamTasks($teamId, $taskId, $departmentId);
}
if (isset($_POST['editTimesheet']) && $_POST['editTimesheet'] == 1) {
    $edit = 1;
    unset($_POST['updateTimesheet']);
    $teamId = $_POST['assignTo'];
    $trId = $_POST['assignTr'];
    $oldTaskId = $_POST['taskId'];
    $tsId = $_POST['tsId'];
    $tName = $_POST['task_name'];
    $tDesc = $_POST['task_description'];
    $tm = new TimeSheetManager();
    $result['editTimesheet']['rowsAffected'] = $tm->editTimesheet($tName,$tDesc,$tsId, $result['taskMapping']['tMapId'], $oldTaskId, $teamId, $time);
}

if (isset($_POST['updateTimesheet'])) {
    if ($edit == 1) {
        die();
    }
    $tm = new TimeSheetManager();
    if ($taskFlag == 0) {
        die();
    }
    $data = $tm->updateTimesheet($result['taskMapping']['tMapId'], $time, $teamId, $_POST['date']);
    if (isset($result)) {
        $result['timesheet']['rowsAffected'] = $data;
    } else {
        $result['timesheet']['rowsAffected'] = $data;
    }
}

if (isset($_POST['lockTimesheet'])) {
    $obj = new stdClass();
    $obj->duration = $_POST['duration'] * 3600;
    $obj->teamId = $_POST['teamId'];
    $obj->date = $_POST['date'];
    $obj->dayType = $_POST['dayType'];
    $tm = new TimeSheetManager();
    $result['lockTimesheet']['rowsAffected'] = $tm->lockTimesheet($obj);
}

if (isset($_REQUEST['get_customer'])) {
    $docket = new TimeSheetManager();
    $result = $docket->getCustomers($_REQUEST['term']);
    array_unshift($result, array("customerno" => 0, "customercompany" => "All Customers", "value" => "All customers"));
}

if (isset($_POST['fetchProducts'])) {
    $tm = new TimeSheetManager();
    $result = $tm->getProducts();
    array_unshift($result, array("prodId" => 0, "prodName" => "All Products", "value" => "All Products"));
}

if (isset($_REQUEST['teamList'])) {
    $tm = new TimeSheetManager();
    $result = $tm->getTeamList($_REQUEST['term']);
}

if (isset($_POST['teamRoles'])) {
    $tm = new TimeSheetManager();
    $result = $tm->getTeamRoles($_POST['departmentId']);
}

if (isset($_POST['submitRole'])) {
    $tm = new TimeSheetManager();
    $taskObj = new stdClass();
    $taskObj->roleid = $_POST['roleId'];
    $taskObj->teamid = $_POST['teamId'];
    $taskObj->departmentId = $_POST['departmentId'];
    $memberExists = $tm->addMember($taskObj->roleid, $taskObj->teamid, $taskObj->departmentId);
    if ($memberExists == 1) {
        $result['found'] = 1;
    } else {
        $result['found'] = 0;
    }
}

if (isset($_POST['new_memeber'])) {
    $tm = new TimeSheetManager();
    $taskObj = new stdClass();
    $taskObj->roleid = $_POST['role'];
    $taskObj->teamid = $_POST['teamId'];
    $memberExists = $tm->getTaskTeamMember($taskObj);
    if ($memberExists == 1) {
        $result = $tm->insert_member($taskObj);
    } else {
        $memberExists = 2;
    }
}
if (isset($_POST['fetchTeam'])) {
    $tm = new TimeSheetManager();
    $department = $_POST['department'];
    $teamId = isset($_POST['teamId']) ? $_POST['teamId'] : 0;
    $result = $tm->fetchTeam($department, $teamId);
}

if (isset($_POST['fetchStatus'])) {
    $tm = new TimeSheetManager();
    $result = $tm->getTaskStatus();
}
if (isset($_REQUEST['searchTask'])) {
    $tm = new TimeSheetManager();
    $result = $tm->searchTask($_REQUEST['term'], $_REQUEST['teamId'], $_REQUEST['trId']);
}
if(isset($_POST['getTime'])){
    $tm = new TimeSheetManager();
    $tsId = isset($_POST['tsId'])?$_POST['tsId']:null;
    $date = isset($_POST['date'])?$_POST['date']:null;
    $result = $tm->getTime($teamId,$tsId,$date);
    if( ($result + $time)  > 50400){
        $result = 1;
    }else{
        $result = 0;
    }
}   
if (isset($result)) {
    echo json_encode($result);
}
?>
