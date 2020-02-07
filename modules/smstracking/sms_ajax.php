<?php
include_once 'sms_functions.php';
if(isset($_REQUEST['phonen']))
{
    check_phone_no($_REQUEST['phonen']);
}
else if(isset($_REQUEST['name']))
{
    addsmstrack($_REQUEST['name'],$_REQUEST['phoneno']);
}
else if(isset($_REQUEST['editname']) && isset($_REQUEST['trackid']))
{
    editsmstrack($_REQUEST['trackid'],$_REQUEST['editname'],$_REQUEST['editphoneno']);
}
else if(isset($_REQUEST['editname']) && isset($_REQUEST['userid']))
{
    edituserphone($_REQUEST['userid'],$_REQUEST['editname'],$_REQUEST['editphoneno']);
}
?>
