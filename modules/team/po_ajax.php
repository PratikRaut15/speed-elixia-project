<?php

//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/utilities.php");

class Po {
    
}

$teamid = GetLoggedInUserId();
$db = new DatabaseManager();
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");

function PrepareSP($sp_name, $sp_params) {
    return "call " . $sp_name . "(" . $sp_params . ");";
}

$x = 0;
$podata = Array();

if (isset($_POST['work']) && $_POST['work'] == 'add_po') {
    $customerid = GetSafeValueString($_POST['cid'], "string");
    $cust = GetSafeValueString($_POST['cust_po'], "string");
    $po_no = GetSafeValueString($_POST['po_no'], "string");
    $podate = GetSafeValueString($_POST['podate'], "string");
    if ($podate != '') {
        $podate = date("Y-m-d", strtotime($podate));
    }
    $poexp = GetSafeValueString($_POST['poexp'], "string");
    if ($poexp != '') {
        $poexp = date("Y-m-d", strtotime($poexp));
    }
    $poamt = GetSafeValueString($_POST['poamt'], "string");
    $podesc = GetSafeValueString($_POST['podesc'], "string");
    $pdo = $db->CreatePDOConn();
    $sp_params = "'" . $po_no . "'"
            . ",'" . $podate . "'"
            . ",'" . $poamt . "'"
            . ",'" . $poexp . "'"
            . ",'" . $podesc . "'"
            . ",'" . $customerid . "'"
            . ",'" . $teamid . "'"
            . ",'" . $today . "'"
            . ",'" . $teamid . "'"
            . ",'" . $today . "'"
    ;
    $QUERY = PrepareSP('insert_po', $sp_params);
    $pdo->query($QUERY);
    $db->ClosePDOConn($pdo);
} elseif (isset($_POST['work']) && $_POST['work'] == 'edit_po') {
    $poid = GetSafeValueString($_POST['poid'], "int");
    $customerid = GetSafeValueString($_POST['cid'], "string");
    $cust = GetSafeValueString($_POST['cust'], "string");
    $po_no = GetSafeValueString($_POST['po_no'], "string");
    $podate = GetSafeValueString($_POST['podate'], "string");
    if ($podate != '') {
        $podate = date("Y-m-d", strtotime($podate));
    }
    $poexp = GetSafeValueString($_POST['poexp'], "string");
    if ($poexp != '') {
        $poexp = date("Y-m-d", strtotime($poexp));
    }
    $poamt = GetSafeValueString($_POST['poamt'], "string");
    $podesc = GetSafeValueString($_POST['podesc'], "string");
    $pdo = $db->CreatePDOConn();
    $sp_params = "'" . $poid . "'"
            . ",'" . $po_no . "'"
            . ",'" . $podate . "'"
            . ",'" . $poamt . "'"
            . ",'" . $poexp . "'"
            . ",'" . $podesc . "'"
            . ",'" . $customerid . "'"
            . ",'" . $teamid . "'"
            . ",'" . $today . "'"
    ;
    $QUERY = PrepareSP('update_po', $sp_params);
    $pdo->query($QUERY);
    $db->ClosePDOConn($pdo);
} elseif (isset($_GET['work']) && $_GET['work'] == 'delete_po') {
    $customerid = GetSafeValueString($_GET['cust_grp'], "int");
    $poid = GetSafeValueString($_GET['delpoid'], "int");
    $pdo = $db->CreatePDOConn();
    $sp_params = "'" . $poid . "'"
            . "," . $customerid . ""
            . ",'" . $teamid . "'"
            . ",'" . $today . "'"
    ;
    $QUERY = PrepareSP('delete_po', $sp_params);
    $pdo->query($QUERY);
    $db->ClosePDOConn($pdo);
    header("Location: modifycustomer.php?cid=$customerid");
} elseif (isset($_POST['work']) && $_POST['work'] == 'view_po') {
    $cust_no = GetSafeValueString($_POST['cno'], "string");
    $pdo = $db->CreatePDOConn();
    $sp_params = "'" . $cust_no . "'"
            . ",''"
    ;
    $QUERY = PrepareSP('get_po', $sp_params);
    $res = $pdo->query($QUERY);
    if ($res) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $x++;
            $DATA = new Po();
            $DATA->poid = $row['poid'];
            $DATA->pono = $row['pono'];
            if ($row['podate'] == '' || $row['podate'] == '1970-01-01' || $row['podate'] == '0000-00-00') {
                $DATA->podate = '';
            } else {
                $DATA->podate = date("d-m-Y", strtotime($row['podate']));
            }
            $DATA->poamount = $row['poamount'];
            if ($row['poexpiry'] == '' || $row['poexpiry'] == '1970-01-01' || $row['poexpiry'] == '0000-00-00') {
                $DATA->poexpiry = '';
            } else {
                $DATA->poexpiry = date("d-m-Y", strtotime($row['poexpiry']));
            }
            $DATA->description = $row['description'];
            $DATA->customerno = $row['customerno'];
            $DATA->x = $x;
            $podata[] = $DATA;
        }

        //print_r($address);
    }
    $db->ClosePDOConn($pdo);
    //print_r($address);
    echo json_encode($podata);
}
