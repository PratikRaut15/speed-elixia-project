<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='city.php?id=1'>Add ".$_SESSION['city']."</a></li>";
    else
        echo "<li><a href='city.php?id=1'>Add ".$_SESSION['city']."</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='city.php?id=2'>View ".$_SESSION['city']."</a></li>";
    else
        echo "<li><a href='city.php?id=2'>View ".$_SESSION['city']."</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='city.php?id=4&cityid=$_GET[cityid]'>Edit ".$_SESSION['city']."</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='city.php?id=1'>Add ".$_SESSION['city']."</a></li>";
        echo "<li><a href='city.php?id=2'>View ".$_SESSION['city']."</a></li>";        /*echo "<li><a href='route.php?id=4'>Edit route</a></li>";*/
    }
?>
</ul>
<?php
require_once 'city_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1){
    include_once 'pages/addcity.php';
}
else if($_GET['id']==2){
    include_once 'pages/viewcities.php';
}
else if($_GET['id']==4){
    include_once 'pages/editcity.php';
}
?>