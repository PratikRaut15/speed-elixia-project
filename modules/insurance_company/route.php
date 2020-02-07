<?php

require_once 'insurance_functions.php';

if (isset($_POST['addinsurance'])) {

    addinsurance($_POST['insurancename']);
    header("location:insurance.php?id=2");
}

if(isset($_REQUEST['insdid'])){
    delinsurance($_REQUEST['insdid']);
    header("location:insurance.php?id=2");
}

if(isset($_POST['editinsurancedetails'])){
    editinsurance($_POST['insurancename'],$_POST['insuranceid']);
    header("location:insurance.php?id=2");
    
}
?>
