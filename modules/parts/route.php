<?php
include 'part_functions.php';
if(isset($_POST['partid']))
{
    editpart($_POST);
    header("location: parts.php?id=2");
}
if(isset($_POST) && !isset($_POST['partid']) && $_POST['action'] != "deletepart")
{
    addpart($_POST);
    header("location: parts.php?id=2");
}
if (isset($_POST['action']) && $_POST['action'] == "deletepart"){
    delpart($_POST['id']);
    echo "ok";
}
?>
