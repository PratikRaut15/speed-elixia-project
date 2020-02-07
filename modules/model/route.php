<?php

include_once 'model_functions.php';
if (isset($_POST['makeid'])) {
    $makeid = GetSafeValueString($_POST['makeid'], "string");
    $modeldata = getmodelofmake($makeid);
    echo json_encode($modeldata);
}

if (isset($_POST['addmodel'])) {
    $modelname = GetSafeValueString($_POST['modelname'], "string");
    $make_id = GetSafeValueString($_POST['make_id'], "string");
    add_model($modelname, $make_id);
    header("location: model.php?id=2");
}
if (isset($_GET['delmodelid'])) {
    $modelid = $_GET['delmodelid'];
    $delmake = del_model($modelid);
    header("location: model.php?id=2");
}

if (isset($_POST['editmodeldetails'])) {
    $modelname = GetSafeValueString($_POST['modelname'], "string");
    $modelid = GetSafeValueString($_POST['modelid'], "string");
    $editmodel = edit_model($modelname, $modelid);
    header("location: model.php?id=2");
}
?>