<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='location.php?id=1'>Add Location</a></li>";
    else
        echo "<li><a href='location.php?id=1'>Add Location</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='location.php?id=2'>View Location</a></li>";
    else
        echo "<li><a href='location.php?id=2'>View Location</a></li>";
    if($_GET['id']==3)
        echo"<li><a class='selected' href='location.php?id=3&geotestid=$_GET[geotestid]'>Edit Location</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='location.php?id=1'>Add Location</a></li>";
        echo "<li><a href='location.php?id=2'>View Location</a></li>";
    }
?>
</ul>
<?php
if(!isset($_GET['id']) || $_GET['id']==1){
include 'location_functions.php';
    include 'pages/addloc.php';
}
else if($_GET['id']==2){
    include 'location_functions.php';
    include 'pages/viewloc.php';
}
else if($_GET['id']==3){
include 'location_functions.php';
    include 'pages/editloc.php';
}
?>
