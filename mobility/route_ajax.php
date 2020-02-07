<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.checkpoints.php");
$checkobj=new checkpoints();


//print_r($_REQUEST);

if(isset($_POST['chkN'])&& $_POST['create']=="")
{
   
	$checkobj->checfor_name($_POST['chkN']);
}
else if($_POST['create'])
{	extract($_REQUEST);
	
	//print_r($_REQUEST);
	$checkobj->ajaxpush($chkN,$chkA,$chkRN,$chkT,$chkC,$chkZC,$chkS,$chkRad,$cgeolat,$cgeolong);

}
else if(isset ($_POST['d']))
{
    chk_eligibility($_POST['d']);
}
else if(isset ($_POST['d']) && isset ($_POST['ds']))
{
    mapchk($_POST['d'], $_POST['ds']);
}
else if(isset ($_POST['ds']))
{
    demapchk($_POST['ds']);
}
else if(isset($_GET['chk']) && $_GET['chk']=='all')
{
    chkformapping();
}
?>
