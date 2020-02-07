<?php
include_once ('busrouteFunctions.php');
?>
<div id="container">
<?php
$arrStudent = getAllStudentsSortedByDistance();
//prettyPrint($arrStudent);
$arrStudentMaster = array();
if (isset($arrStudent)) {
	foreach ($arrStudent as $student) {
		$arrStudentMaster[] = $student->studentId;
	}
}
//prettyPrint($arrStudentMaster);
$distanceInMeter = 0;
$cgeolat = 0;
$cgeolong = 0;
$arrBus = array();
$arrFinal = array();
$i= 1;
//print_r($arrStudent);
foreach ($arrStudent as $student) {
	$objData  =  new stdClass();
	$crad = 100;
	if ($cgeolat == 0 && $cgeolong == 0) {
		$cgeolat = $student->lat;
		$cgeolong = $student->lng;
	}
	$distance = calculate($student->lat, $student->lng, $cgeolat, $cgeolong);
	$dist = round($distance*1000);
	//echo $student->studentId ." == > ". $dist ."<br/>";
	$objData->studentId = $student->studentId;
	$objData->lat = $student->lat;
	$objData->lng = $student->lng;
	$objData->distanceFromStop = round($dist/1000,2);
	$objData->distanceFromSchool = $student->distance;
	if( $objData->distanceFromSchool < 5 ){
		$objData->zone = 1;
	} else if($objData->distanceFromSchool >= 5 && $objData->distanceFromSchool < 10) {
		$objData->zone = 2;
	}
	else if($objData->distanceFromSchool >= 10 && $objData->distanceFromSchool < 15) {
		$objData->zone = 3;
	}
	else if($objData->distanceFromSchool >= 15 && $objData->distanceFromSchool < 20) {
		$objData->zone = 4;
	}
	else if($objData->distanceFromSchool >= 20 && $objData->distanceFromSchool < 25) {
		$objData->zone = 5;
	}else{
		$objData->zone = 6;
	}
	$objData->accuracy = $student->accuracy;
	$objData->address = $student->address;
	if ($dist < 100) {
		$arrBus[] = $objData;
		$cgeolat = $cgeolat;
		$cgeolong = $cgeolong;
	}else{
		$cgeolat = $student->lat;
		$cgeolong = $student->lng;
		$objData->distanceFromStop = 0;
		if (!empty($arrBus)) {
			$arrFinal[] = $arrBus;
			unset($arrBus);
		}
		$arrBus[] = $objData;
	}
	$i++;

}
//prettyPrint($arrFinal);
if (isset($arrFinal) && !empty($arrFinal)) {
	$i = 0;
	foreach ($arrFinal as $row) {
		//prettyPrint($row[0]);
		$busStopId = 0;
		$busStopId = insertBusStop($row[0]);
		if ($busStopId != 0) {
			foreach ($row as $data) {
				$data->busStopId = $busStopId;
				insertBusStopStudentMapping($data);
			}
		}

	}
}
?>
</div>