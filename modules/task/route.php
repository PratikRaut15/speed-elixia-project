<?php

include 'task_functions.php';
if (isset($_POST['taskid'])){
    edittask($_POST, $_POST['taskid']);
    header("location:task.php?id=2");
} 
if (isset($_POST) && !isset($_POST['taskid'])&& $_POST['action'] != "deletetask") {
    addtask($_POST);
    header("location: task.php?id=2");
}
if (isset($_POST['action']) && $_POST['action'] == "deletetask"){
    deltask($_POST['id']);
    echo "ok";
}
?>
