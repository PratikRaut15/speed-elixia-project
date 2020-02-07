<?php

include_once 'make_functions.php';

if(isset($_POST['addmake']))
{
    
    add_make($_POST['makename']);
    header("location: make.php?id=2");
}

if(isset($_POST['editmakedetails']))
{
    
    edit_make($_POST['makeid'],$_POST['makename']);
    header("location: make.php?id=2");
}

if(isset($_GET['did']))
{
    $delmake = delmake($_GET['did']);
    header("location: make.php?id=2");
}
?>
