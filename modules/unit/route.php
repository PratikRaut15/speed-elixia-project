<?php
include 'unit_functions.php';
if(isset($_POST['uid']) && isset($_POST['phoneno']))
{
    modifyunit($_POST['uid'], $_POST['phoneno']);
    header('location: unit.php');
}
?>
