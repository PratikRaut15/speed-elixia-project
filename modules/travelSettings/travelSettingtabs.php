<?php
include 'travelSettings_functions.php';
if(!isset($_GET['id']) || $_GET['id'] == 1) {
    include 'pages/addTravelSettings.php';
} else if ($_GET['id'] == 2) {
    include 'pages/viewTravelSettings.php';
} else if ($_GET['id'] == 3) {
    include 'pages/editTravelSettings.php';
}
?>