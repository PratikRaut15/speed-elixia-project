<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='district.php?id=1'>Add ".$_SESSION['district']."</a></li>";
    else
        echo "<li><a href='district.php?id=1'>Add ".$_SESSION['district']."</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='district.php?id=2'>View ".$_SESSION['district']."</a></li>";
    else
        echo "<li><a href='district.php?id=2'>View ".$_SESSION['district']."</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='district.php?id=4&districtid=$_GET[districtid]'>Edit ".$_SESSION['district']."</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='district.php?id=1'>Add ".$_SESSION['district']."</a></li>";
        echo "<li><a href='district.php?id=2'>View ".$_SESSION['district']."</a></li>";        /*echo "<li><a href='route.php?id=4'>Edit route</a></li>";*/
    }
?>
</ul>
<?php
require_once 'district_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1){
    include_once 'pages/adddistrict.php';
}
else if($_GET['id']==2){
    include_once 'pages/viewdistricts.php';
}
else if($_GET['id']==4){
    include_once 'pages/editdistrict.php';
}
?>