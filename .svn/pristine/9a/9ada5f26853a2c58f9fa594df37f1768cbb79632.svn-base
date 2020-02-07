<ul id="tabnav">
<?php
if(isset($_GET['id']))
{
    if($_GET['id']==1)
        echo "<li><a class='selected' href='enh_route.php?id=1'> Add Enhanced route</a></li>";
    else
        echo "<li><a href='enh_route.php?id=1'>  Add Enhanced route</a></li>";
    if($_GET['id']==2)
        echo "<li><a class='selected' href='enh_route.php?id=2'> View Enhanced routes</a></li>";
    else
        echo "<li><a href='enh_route.php?id=2'> View Enhanced routes</a></li>";
    if($_GET['id']==4)
        echo"<li><a class='selected' href='enh_route.php?id=4&did=$_GET[did]'> Edit Enhanced route</a></li>";
    }
    else
    {
        echo "<li><a class='selected' href='enh_route.php?id=1'>  Add Enhanced route</a></li>";
        echo "<li><a href='enh_route.php?id=2'>  View Enhanced route</a></li>";        /*echo "<li><a href='route.php?id=4'>Edit route</a></li>";*/
    }
?>
</ul>
<?php
if(!isset($_GET['id']) || $_GET['id']==1){
include 'route_functions.php';
include 'checkpoint_route_functions.php';
include 'pages/addroute_new.php';
}
else if($_GET['id']==2){
include 'route_functions.php';
include 'pages/viewroutes_new.php';
}
else if($_GET['id']==4){
include 'route_functions.php';
include 'checkpoint_route_functions.php';
include 'pages/editroute_new.php';
}
else if($_GET['id']==6){
include 'route_functions.php';
include 'checkpoint_route_functions.php';
include 'pages/addroute_new.php';
}
else if($_GET['id']==7){
include 'route_functions.php';
include 'checkpoint_route_functions.php';
include 'pages/editroute_new.php';
}

?>
