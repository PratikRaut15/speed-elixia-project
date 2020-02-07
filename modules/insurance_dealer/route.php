<?php
include_once 'insurance_dealer_functions.php';
if (isset($_POST['addinsurancedealer'])) {
    addInsDealer($_POST['insurance_dealer_name']);
    header("location: insurance_dealer.php?id=2");
} elseif (isset($_POST['editinsurancedealer'])) {
    editInsDealer($_POST);
    header("location: insurance_dealer.php?id=2");
} elseif (isset($_GET['work']) && $_GET['work'] == 'delete') {
    deleteInsDealer($_GET['insdelid']);
    header("location: insurance_dealer.php?id=2");
}