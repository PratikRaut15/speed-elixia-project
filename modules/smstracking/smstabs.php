<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='smstracking.php?id=1'>Add Sms Tracking</a></li>";
    else
        echo "<li><a href='smstracking.php?id=1'>Add Sms Tracking</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='smstracking.php?id=2'>View Sms Tracking</a></li>";
    else
        echo "<li><a href='smstracking.php?id=2'>View Sms Tracking</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='smstracking.php?id=4&did=$_GET[did]'>Edit Sms Tracking</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='smstracking.php?id=1'>Add Sms Tracking</a></li>";
        echo "<li><a href='smstracking.php?id=2'>View Sms Tracking</a></li>";
    }
?>
</ul>
<?php
if(!isset($_GET['id']) || $_GET['id']==1){
include 'sms_functions.php';
    include 'pages/addsms.php';
}
else if($_GET['id']==2){
    include 'sms_functions.php';
    include 'pages/viewsms.php';
}
else if($_GET['id']==4){
include 'sms_functions.php';
    include 'pages/editsms.php';
}
?>
