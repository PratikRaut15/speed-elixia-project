<?php
include_once '../../modules/busroute/checkpoint_functions.php';

if(isset($_REQUEST['delid']))
{
    deletechk($_REQUEST['delid']);
    header("location: czone.php?id=2");
}
else if(isset($_REQUEST['chkId']))
{
    modifychk($_REQUEST);
    header("location: czone.php?id=2");
}
else if(isset($_REQUEST))
{
    addchk($_REQUEST);
    header("location: czone.php?id=2");
}
?>
