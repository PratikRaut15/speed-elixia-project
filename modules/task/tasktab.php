<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1){
        echo "<li><a class='selected' href='task.php?id=1'>Add Task</a></li>";
        echo "<li><a href='task.php?id=2'>View Tasks</a></li>";
    }elseif($_GET['id']==2){
        echo "<li><a class='selected' href='task.php?id=2'>View Tasks</a></li>";
        echo "<li><a href='task.php?id=1'>Add Task</a></li>";
    }elseif($_GET['id']==4){
        echo "<li><a href='task.php?id=1'>Add Task</a></li>";
        echo "<li><a href='task.php?id=2'>View Tasks</a></li>";
        echo"<li><a class='selected' href='task.php?id=4&tid=$_GET[tid]'>Edit Task</a></li>";
    }else{
        echo "<li><a href='task.php?id=1'>Add Task</a></li>";
        echo "<li><a class='selected' href='task.php?id=2'>View Tasks</a></li>";
    }    
}
else
{
    echo "<li><a class='selected' href='task.php?id=1'>Add Task</a></li>";
    echo "<li><a href='task.php?id=2'>View Tasks</a></li>";
}
?>
</ul>
<?php
include 'task_functions.php';
if(!isset($_GET['id']) || $_GET['id']=='1'){
    include 'pages/addtask.php';
}elseif($_GET['id']=='2'){
    include 'pages/viewtasks.php';
}else if($_GET['id']=='4'){
    include 'pages/edittask.php';
}    
?>
