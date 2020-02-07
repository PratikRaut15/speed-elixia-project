<?php
include 'vehicle_functions.php';
if(isset($_GET['delvid']))
{
    delvehicle($_GET['delvid']);
    header("location: vehicle.php?id=2");
}
else if(isset($_POST['vehicleid']))
{
    if($_POST['delete'])
    {
        delvehicle($_POST['vehicleid']);
        header("location: vehicle.php?id=2");
    }
    $checkpoints = array();
    $fences = array();
    foreach($_POST as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 14) == "to_checkpoint_")
            $checkpoints[] = substr($single_post_name, 14, 25);
    }
    foreach($_POST as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 9) == "to_fence_")
            $fences[] = substr($single_post_name, 9, 25);
    }
    modifyvehicle($_POST['vehicleno'], $_POST['vehicleid'], $_POST['type'], $checkpoints,$fences,$_POST['groupid'],$_POST['overspeed_limit'],$_POST['min_temp1_limit'],$_POST['max_temp1_limit'],$_POST['min_temp2_limit'],$_POST['max_temp2_limit']);
    header("location: vehicle.php?id=2");
}
else if(isset($_POST))
{
    $checkpoints = array();
    $fences = array();
    foreach($_POST as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 14) == "to_checkpoint_")
        {
            $checkpoints[] = substr($single_post_name, 14, 25);
        }
    }
    foreach($_POST as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 9) == "to_fence_")
            $fences[] = substr($single_post_name, 9, 25);
        
    }
    insertvehicle($_POST['vehicleno'],$_POST['type'],$checkpoints,$fences,$_POST['groupid'],$_POST['overspeed_limit'],$_POST['min_temp1_limit'],$_POST['max_temp1_limit'],$_POST['min_temp2_limit'],$_POST['max_temp2_limit']);
    header("location: vehicle.php?id=3");
}
?>
