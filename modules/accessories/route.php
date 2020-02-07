<?php
include 'accessories_functions.php';
if(isset($_POST['accid']))
{
    editaccessory($_POST['editname'],$_POST['editamount'], $_POST['accid']);
    header("location: accessories.php?id=2");
}
else if(isset($_POST) && !isset($_POST['accid']))
{
    addaccessory($_POST['accessoryname'], $_POST['amountname']);
    header("location: accessories.php?id=2");
}
?>
