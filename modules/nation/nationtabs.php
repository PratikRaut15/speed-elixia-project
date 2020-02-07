<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='nation.php?id=1'>Add ".$_SESSION['nation']."</a></li>";
    else
        echo "<li><a href='nation.php?id=1'>Add ".$_SESSION['nation']."</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='nation.php?id=2'>View ".$_SESSION['nation']."</a></li>";
    else
        echo "<li><a href='nation.php?id=2'>View ".$_SESSION['nation']."</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='nation.php?id=4&nationid=$_GET[nationid]'>Edit ".$_SESSION['nation']."</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='nation.php?id=1'>Add ".$_SESSION['nation']."</a></li>";
        echo "<li><a href='nation.php?id=2'>View ".$_SESSION['nation']."</a></li>";        /*echo "<li><a href='route.php?id=4'>Edit route</a></li>";*/
    }
?>
</ul>
<?php
require_once 'nation_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1){
    include_once 'pages/addnation.php';
}
else if($_GET['id']==2){
    include_once 'pages/viewnations.php';
}
else if($_GET['id']==4){
    include_once 'pages/editnation.php';
}
?>