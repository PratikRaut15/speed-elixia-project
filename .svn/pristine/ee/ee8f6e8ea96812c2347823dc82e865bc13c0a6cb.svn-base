<?php

include_once("session.php");
include_once("loginorelse.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");

class Autocompleteteam {
    
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'FindLedger') {
    if ($_REQUEST['term'] != '') {
        $searchString = $_REQUEST['term'];
    }
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $ledger = array();
    $sp_params1 = "''"
            . ",'" . $searchString . "'"
    ;
    $QUERY1 = $db->PrepareSP('get_ledger', $sp_params1);
    $ledgerList = $pdo->query($QUERY1);
    if ($ledgerList->rowCount() > 0) {
        while ($row = $ledgerList->fetch(PDO::FETCH_ASSOC)) {
            $data = new Autocompleteteam();
            $data->value = $row['ledgername'];
            $data->ledgerid = $row['ledgerid'];           
            $ledger[] = $data;
        }
    }
    $db->ClosePDOConn($pdo);
    echo json_encode($ledger);
}elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'invoice_unmapveh') {
    if ($_REQUEST['term'] != '') {
        $searchString = trim($_REQUEST['term']);
    }
    $db = new DatabaseManager();
   $custno = GetSafeValueString($_REQUEST["custno"], "int");

    $pdo = $db->CreatePDOConn();
    $sp_params = "''"
            . ",'" . $custno . "'"
            . ",''"
            . ",'".$searchString."'"
    ;
    $QUERY = $db->PrepareSP('get_ledger_veh_mapping', $sp_params);
    $mapvehList = $pdo->query($QUERY);
    if ($mapvehList->rowCount() > 0) {
        while ($row = $mapvehList->fetch(PDO::FETCH_ASSOC)) {
            $data = new Autocompleteteam();
            $data->vehicleid = $row['vehicleid'];
            $data->value = $row['vehicleno'];
            $inv[] = $data;
        }
    }
    $db->ClosePDOConn($pdo);
    unset($temp_arr);
    $vehicleSearch = '%'.$searchString.'%';
    $SQL = sprintf("select vehicleid,vehicleno FROM vehicle WHERE customerno = %d AND isdeleted=0 AND vehicleno LIKE '%s' ORDER BY vehicleno ASC", $custno,$vehicleSearch);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row1 = $db->get_nextRow()) {
            $data = new Autocompleteteam();
            $data->vehicleid = $row1['vehicleid'];
            $data->value = $row1['vehicleno'];
            $veh[] = $data;
        }
    }
    if (!empty($inv)) {
        $diff = array_values(array_udiff($veh, $inv, 'compare_objects'));
    } else {
        $diff = $veh;
    }
    echo json_encode($diff);
}

function compare_objects($a, $b) {
    return $a->vehicleid - $b->vehicleid;
}
?>

