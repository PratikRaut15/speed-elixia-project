<?php
include_once '../../modules/enh_checkpoint/enh_checkpoint_functions.php';

if(isset($_REQUEST['delid']))
{
    delete_enhchk($_REQUEST['delid']);
    header("location: enh_checkpoint.php?id=2");
}
else if(isset($_REQUEST['enh_chkId']))
{
    modify_enhchk($_REQUEST);
}
else if(isset($_REQUEST))
{
    add_enh_chk($_REQUEST);
}
?>
