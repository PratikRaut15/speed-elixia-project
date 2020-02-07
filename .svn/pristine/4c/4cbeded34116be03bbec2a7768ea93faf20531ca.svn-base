<?php session_start(); if(!isset($_SESSION['customerno'])){die();}
include_once "reports_trip_functions.php";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Trip report</title>
<script  type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src='../../scripts/trip.js' type='text/javascript'></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqInDK10QshlbDp_KOJbG0EGh-Q17AMCQ&sensor=true"></script>
<script src="../../scripts/backup-pre-bootstrap/prototype.js" type="text/javascript"></script>
<script src="../../scripts/markerwithlabel.js" type="text/javascript"></script>
<link href="../../style/style.css" rel="stylesheet" type="text/css" media="screen" />
<?php 
extract($_REQUEST);
?>

</head>

<body onload="loaded()" >
START DATE :<?php echo $SDate; ?><br />
START TIME :<?php echo $Shour; ?><br />
END DATE :<?php echo $EDate; ?><br />
END TIME :<?php echo $Ehour; ?><br />
VEHICLE NO :<?php echo getvehicleno($vehicleid); ?><br />

<input id="SDate" name="SDate" type="hidden" value="<?php echo $SDate; ?>" required/>
<input id="Shour" name="Shour" type="hidden" value="<?php echo $Shour; ?>" required/>


<input id="EDate" name="EDate" type="hidden" value="<?php echo $EDate; ?>" required/>
<input id="Ehour" name="Ehour" type="hidden" value="<?php echo $Ehour; ?>" required/>
<input  id="vehicleid" name="vehicleid" type="hidden" value="<?php echo $vehicleid; ?>" required/>

<div id="map" style="width:1600px; height:2200px;"></div>
</body>
</html>
