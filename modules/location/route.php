<?php
include_once 'location_functions.php';

if(isset($_REQUEST['delid']))
{
    deletelocation($_REQUEST['delid']);
    header("location: location.php?id=2");
}
else if(isset($_REQUEST['geotestid']))
{
    modifylocation($_REQUEST);
    header("location: location.php?id=2");
}
else if(isset($_REQUEST))
{
    addlocation($_REQUEST);
    header("location: location.php?id=2");
}
?>
