<?php
if (isset($_POST['SDate'])) {
	include 'reports_common_functions.php';

	$Date = GetSafeValueString($_POST['SDate'], 'string');
	$type = GetSafeValueString($_POST['alerttype'], 'string');
	$vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
	$checkpointid = GetSafeValueString($_POST['chkid'], 'string');
	$fenceid = GetSafeValueString($_POST['fenceid'], 'string');
	if (isset($_SESSION['ecodeid'])) {
		$startdate = date('d-m-Y', strtotime(GetSafeValueString($_POST['s_start'], 'string')));
		$enddate = date('d-m-Y', strtotime(GetSafeValueString($_POST['e_end'], 'string')));
	}

	$vehno = retval_issetor($_POST['vehno'], 'All Vehicles'); //typeText
	$typeText = retval_issetor($_POST['typeText'], 'All Types');
	$chkText = retval_issetor($_POST['chkText'], 'All Checkpoint');
	$fncText = retval_issetor($_POST['fncText'], 'All Fences');

	include_once 'pages/panels/alerthistrep.php';
	if (isset($_SESSION['ecodeid'])) {
		if ($Date < $startdate || $Date > $enddate) {
			echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
		} else {
			getalerthist($Date, $type, $vehicleid, $checkpointid, $fenceid);
		}
	} else {
		getalerthist($Date, $type, $vehicleid, $checkpointid, $fenceid);
	}
}
?>
