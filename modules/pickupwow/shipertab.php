<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='shiper.php?id=1'>Add Shipper</a></li>";
    else
        echo "<li><a href='shiper.php?id=1'>Add Shipper</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='shiper.php?id=2'>View Shipper</a></li>";
    else
        echo "<li><a href='shiper.php?id=2'>View Shipper</a></li>";
    if($_GET['id']==3)
        echo"<li><a class='selected' href='shiper.php?id=3&vid=$_GET[vid]'>Edit Shippers</a></li>";
}
else
{
    echo "<li><a class='selected' href='shiper.php?id=1'>Add Shipper</a></li>";
    echo "<li><a href='shiper.php?id=2'>View Shipper</a></li>";
}
?>
</ul>
<?php
include 'pickup_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addshiper.php';
else if($_GET['id']==2)
    include 'pages/viewshipers.php';
else if($_GET['id']==3)
    include 'pages/editshiper.php';
?>
