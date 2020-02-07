<?php session_start(); if(!isset($_SESSION['customerno'])){die();}
include_once "reports_travel_functions.php";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Trip report</title>
<script  type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<?php 
extract($_REQUEST);
?>

</head>
<script type='text/javascript' src='<?php echo $_SESSION['subdir']."/scripts/trip.js"; ?>'></script>
<?php 
$geocode = '1';
$SDate = $_REQUEST['SDate'];
$EDate = $_REQUEST['EDate'];
$Shour = $_REQUEST['Shour'];
$Ehour = $_REQUEST['Ehour'];
$vehicleid = $_REQUEST['vehicleid'];
?>
START DATE :<?php echo $SDate; ?><br />
START TIME :<?php echo $Shour; ?><br />
END DATE :<?php echo $EDate; ?><br />
END TIME :<?php echo $Ehour; ?><br />
<?php
 $devicemanager = new DeviceManager($_SESSION['customerno']);
$vehicleno = $devicemanager->getvehiclenofromdeviceid($vehicleid);
?>
VEHICLE NO :<?php echo $vehicleno; ?><br />
<?php
$cat=get_travel_history_report_trip($_SESSION['customerno'],$vehicleid,$SDate,$EDate,$Shour,$Ehour,$geocode);
echo $content = ob_get_clean(); 
?>
</html>
