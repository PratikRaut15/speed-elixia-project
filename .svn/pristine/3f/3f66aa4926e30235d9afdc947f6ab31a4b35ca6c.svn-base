<?php
include 'checkpoint_functions_modal.php';

if(isset($_REQUEST['delid']))
{
    deletechk($_REQUEST['delid']);
    //header("location: checkpoint.php?id=2");
}
else if(isset($_REQUEST['chkId']))
{
    modifychk($_REQUEST);
    //header("location: checkpoint.php?id=2");
}
else if(isset($_REQUEST['checkpointtovehicle']))
{
    addchktovehicle($_REQUEST);
    //header("location: checkpoint.php?id=2");
}
else if(isset($_REQUEST))
{
    addchk($_REQUEST);
}

?>
