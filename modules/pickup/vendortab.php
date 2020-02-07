<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='vendor.php?id=1'>Add Vendor</a></li>";
    else
        echo "<li><a href='vendor.php?id=1'>Add Vendor</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='vendor.php?id=2'>View Vendor</a></li>";
    else
        echo "<li><a href='vendor.php?id=2'>View Vendor</a></li>";
    if($_GET['id']==3)
        echo"<li><a class='selected' href='vendor.php?id=3&vid=$_GET[vid]'>Edit Vendor</a></li>";
}
else
{
    echo "<li><a class='selected' href='vendor.php?id=1'>Add Vendor</a></li>";
    echo "<li><a href='vendor.php?id=2'>View Vendor</a></li>";
}
?>
</ul>
<?php
include 'pickup_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addvendor.php';
else if($_GET['id']==2)
    include 'pages/viewvendors.php';
else if($_GET['id']==3)
    include 'pages/editvendor.php';
?>
