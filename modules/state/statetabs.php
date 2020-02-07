<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='state.php?id=1'>Add ".$_SESSION["state"]."</a></li>";
    else
        echo "<li><a href='state.php?id=1'>Add ".$_SESSION["state"]."</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='state.php?id=2'>View ".$_SESSION["state"]."</a></li>";
    else
        echo "<li><a href='state.php?id=2'>View ".$_SESSION["state"]."</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='state.php?id=4&stateid=$_GET[stateid]'>Edit ".$_SESSION["state"]."</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='state.php?id=1'>Add ".$_SESSION["state"]."</a></li>";
        echo "<li><a href='state.php?id=2'>View ".$_SESSION["state"]."</a></li>";        /*echo "<li><a href='route.php?id=4'>Edit route</a></li>";*/
    }
?>
</ul>
<?php
require_once 'state_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1){
    include_once 'pages/addstate.php';
}
else if($_GET['id']==2){
    include_once 'pages/viewstates.php';
}
else if($_GET['id']==4){
    include_once 'pages/editstate.php';
}
?>