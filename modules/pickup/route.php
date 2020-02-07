<?php
include 'task_functions.php';
if(isset($_POST['taskid']))
{
    edittask($_POST['editname'], $_POST['taskid']);
    header("location: task.php?id=2");
}
else if(isset($_POST) && !isset($_POST['taskid']))
{
    addtask($_POST['taskname']);
    header("location: task.php?id=2");
}
?>
