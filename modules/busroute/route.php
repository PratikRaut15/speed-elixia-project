<?php
include 'zone_functions.php';
if(isset($_GET['delid']))
{
    delzone($_GET['delid']);
    header('location: zone.php?id=2');
}
?>
