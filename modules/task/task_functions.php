<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/autoload.php';
if (!isset($_SESSION)) {
    session_start();
}
function addtask($data) {
    $taskmanager = new TaskManager($_SESSION['customerno']);
    $taskmanager->add_task($data, $_SESSION['userid']);
}
function edittask($data, $taskid) {
    $taskmanager = new TaskManager($_SESSION['customerno']);
    $taskmanager->edit_task($data, $_SESSION['userid']);
}
function gettask() {
    $TaskManager = new TaskManager($_SESSION['customerno']);
    $tasks = $TaskManager->get_all_task();
    return $tasks;
}

function deltask($taskid) {
    $TaskManager = new TaskManager($_SESSION['customerno']);
    $TaskManager->DeleteTask($taskid, $_SESSION['userid']);
}

function gettasksbyid($id) {
    $TaskManager = new TaskManager($_SESSION['customerno']);
    $tasks = $TaskManager->get_task($id);
    return $tasks;
}
?>