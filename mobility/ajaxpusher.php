<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.Authentication.php");
require_once("class/class.servicecall.php");

$checkobj=new Authentication();
$serviceobj=new servicecall();

extract($_REQUEST);
print_r($_REQUEST);
$checkobj->changesettingsvars($suf1_custom,$suf1,$suf2_custom,$suf2,$callextra1_custom,$callextra1,$callextra2_custom,$callextra2,$cliextra_custom,$cliextra);
$serviceobj->updatetrackee("custom",$trackeeid);
?>
