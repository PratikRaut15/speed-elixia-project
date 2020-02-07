<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==2)
        echo "<li><a class='selected' href='accessories.php?id=2'>View Accessories</a></li>";
    else
        echo "<li><a href='accessories.php?id=2'>View Accessories</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='accessories.php?id=4&tid=$_GET[tid]'>Edit Accessory</a></li>";
}
else
{
    echo "<li><a class='selected' href='accessories.php?id=1'>Add Accessory</a></li>";
    echo "<li><a href='accessories.php?id=2'>View Accessories</a></li>";
}
?>
</ul>
<?php
include 'accessories_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addaccessories.php';
else if($_GET['id']==2)
    include 'pages/viewaccessories.php';
else if($_GET['id']==4)
    include 'pages/editaccessories.php';
?>
