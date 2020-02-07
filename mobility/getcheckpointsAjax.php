<?php
//include_once 'session/sessionhelper.php';
//include_once("constants/constants.php");
//include_once("lib/system/utilities.php");
//include_once("lib/components/ajaxpage.inc.php");
//$dontredirect=true;// This is set so we don't ask for login details...
//$ajaxpage = new ajaxpage();
//$customerno = GetCustomerno();

//if (IsAdmin() || IsTracker()) {
//	$msg = "<P>You are an authorized user</p>";
//} else {
//	header( "Location: index.php");
//	exit;
//}

//class jsonop
//{
    // Empty class!
//}

//include_once("lib/bo/CheckpointManager.php");
//$cm = new CheckpointManager($customerno);
//$checkpoints = $cm->getcheckpointsforcustomer();
//$finaloutput = array();
//if(isset($checkpoints))
//{
//    foreach($checkpoints as $thischeckpoint)
//    {
//        $output = new jsonop();
//        $output->cgeolat = $thischeckpoint->cgeolat;
//        $output->cgeolong = $thischeckpoint->cgeolong;
//        $output->ciconimage = $thischeckpoint->ciconimage;
//        $output->cname = $thischeckpoint->cname;        
//        $finaloutput[] = $output;
//    }
//}
//$ajaxpage->SetResult($finaloutput);
//$ajaxpage->Render();
?>
{"success":true,"errorcode":0,"errormessage":"","result":[]}