<?php
include '../panels/header.php';
echo '<script src="https://code.jquery.com/jquery-1.12.0.js"  type="text/javascript"></script>';
echo '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false&key=' . GOOGLE_MAP_API_KEY . '></script>';
echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
echo "<script src='http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.9/markerwithlabel/src/markerwithlabel.js' type='text/javascript'></script>";
echo "<script src='http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/src/markerclusterer.js'></script> ";
echo "<script src='" . $_SESSION['subdir'] . "/scripts/routeMap.js' type='text/javascript'></script>";

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
date_default_timezone_set($_SESSION['timezone']);
?>
<div id="map" class="map" style="float:left;  height:450px"></div>
<div style="clear: both;">&nbsp;</div>
<div id="displaydata">
    <div style="float: left;top: 25px;">

    </div>

    <?php include '../panels/footer.php';?>