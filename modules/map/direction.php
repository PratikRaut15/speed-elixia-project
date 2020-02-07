<?php
//print_r($_REQUEST);
$origin = $_REQUEST['origin'];
$destination = $_REQUEST['destination'];
$file_to_send = "https://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$destination&key=" . GOOGLE_MAP_API_KEY;
$data = file_get_contents($file_to_send);
$arrData = json_decode($data);
//echo "<pre>";
print_r($arrData->routes[0]->overview_polyline->points);
/*
$datalog = $arrData->routes[0]->legs[0]->steps;
$string = array();
foreach ($datalog as $key => $row) {
//print_r($row);
//die
$cord = $row->start_location->lat . "," . $row->start_location->lng;
$string[] = $cord;
if ($key !== 0) {
$cord = $row->end_location->lat . "," . $row->end_location->lng;
$string[] = $cord;
}
}
echo implode("|", $string);
 */
?>