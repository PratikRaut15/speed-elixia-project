<?php

include_once("session.php");
include("loginorelse.php");
include_once("../../lib/system/utilities.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/utilities.php");
include_once '../../lib/system/Sanitise.php';

class InvAjax {
    
}

$db = new DatabaseManager();
$teamid = GetLoggedInUserId();
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");

$x = 0;
$inv = Array();
$veh = Array();

function PrepareSP($sp_name, $sp_params) {
    return "call " . $sp_name . "(" . $sp_params . ");";
}

if (isset($_POST['work']) && $_POST['work'] == 'mappedveh') {
    $cust_no = GetSafeValueString($_POST["cust_no"], "int");
    $ledgerid = GetSafeValueString($_POST['ledgerid'], "int");
    $pdo = $db->CreatePDOConn();
    $sp_params = "''"
            . ",'" . $cust_no . "'"
            . ",'" . $ledgerid . "'"
            . ",''"
    ;
    $QUERY = PrepareSP('get_ledger_veh_mapping', $sp_params);
    $res = $pdo->query($QUERY);
    if ($res) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $data = new InvAjax();
            $data->ledger_veh_mapid = $row['ledger_veh_mapid'];
            $data->vehicleid = $row['vehicleid'];
            $data->vehicleno = $row['vehicleno'];
            $data->ledgerid = $row['ledgerid'];
            $data->customerno = $row['customerno'];
            $inv[] = $data;
        }
    }
    $db->ClosePDOConn($pdo);
    //print_r($address);
    echo json_encode($inv);
} elseif (isset($_POST['work']) && $_POST['work'] == 'unmapveh') {
    $cust_grp = GetSafeValueString($_POST["cust_grp"], "int");

    $pdo = $db->CreatePDOConn();
    $sp_params = "''"
            . ",'" . $cust_grp . "'"
            . ",''"
            . ",''"
    ;
    $QUERY = PrepareSP('get_ledger_veh_mapping', $sp_params);
    $res = $pdo->query($QUERY);
    if ($res) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $data = new InvAjax();
            $data->vehicleid = $row['vehicleid'];
            $data->vehicleno = $row['vehicleno'];
            $inv[] = $data;
        }
    }
    $db->ClosePDOConn($pdo);
    unset($temp_arr);
    $dummy_condition = "D%";
    $SQL = sprintf("select v.vehicleid,v.vehicleno FROM vehicle v
          INNER JOIN unit u ON u.vehicleid = v.vehicleid AND u.unitno NOT LIKE '%s' 
          INNER JOIN devices d ON d.uid = u.uid
          INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14)
          WHERE v.customerno = '%d' AND v.isdeleted=0 ORDER BY v.vehicleno ASC",$dummy_condition,$cust_grp);
    //die();
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row1 = $db->get_nextRow()) {
            $data = new InvAjax();
            $data->vehicleid = $row1['vehicleid'];
            $data->vehicleno = $row1['vehicleno'];
            $veh[] = $data;
        }
    }
    if (!empty($inv)) {
        $diff = array_values(array_udiff($veh, $inv, 'compare_objects'));
    } else {
        $diff = $veh;
    }
    echo json_encode($diff);
} elseif (isset($_POST['work']) && $_POST['work'] == 'maptoledger') {
    $cust_no = GetSafeValueString($_POST["cust_no"], "int");
    $ledgerid = GetSafeValueString($_POST['ledger'], "int");
    $vehicles = Array();
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 11) == "to_vehicle_")
            $vehicles[] = substr($single_post_name, 11, 12);
    }
    $pdo = $db->CreatePDOConn();
    $sp_params = "''"
            . ",'" . $cust_no . "'"
            . ",'" . $ledgerid . "'"
            . ",'" . $teamid . "'"
            . ",'" . $today . "'"
    ;
    $QUERY = PrepareSP('delete_ledger_veh_mapping', $sp_params);
    $pdo->query($QUERY);
    $db->ClosePDOConn($pdo);
    if (!empty($vehicles)) {
        foreach ($vehicles as $vehicle) {
            $pdo1 = $db->CreatePDOConn();
            $sp_params1 = "'" . $vehicle . "'"
                    . ",'" . $ledgerid . "'"
                    . ",'" . $cust_no . "'"
                    . ",'" . $teamid . "'"
                    . ",'" . $today . "'"
                    . ",'" . $teamid . "'"
                    . ",'" . $today . "'"
            ;
            $QUERY1 = PrepareSP('insert_ledger_veh_mapping', $sp_params1);
            $pdo1->query($QUERY1);
            $db->ClosePDOConn($pdo1);
        }
    }
} elseif (isset($_POST['work']) && $_POST['work'] == 'getLedgerByCust') {
    $cust = GetSafeValueString($_POST['cid'], "int");
    $pdo = $db->CreatePDOConn();
    $sp_params = "''"
            . ",'" . $cust . "'"
            . ",''"
    ;
    $QUERY = PrepareSP('get_ledger_cust_mapping', $sp_params);
    $res = $pdo->query($QUERY);
    $details = Array();
    if ($res) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $DATA = new stdClass();
            $DATA->ledgerid = $row['ledgerid'];
            $DATA->ledgername = $row['ledgername'];
            $DATA->customerno = $row['customerno'];
            $details[] = $DATA;
        }
    }
    $db->ClosePDOConn($pdo);
    echo json_encode($details);
} else if (isset($_POST['work']) && $_POST['work'] == 'getpo') {
    $cust_no = GetSafeValueString($_POST["cust_no"], "int");
    //$cust = GetSafeValueString($_POST['acc_cust'], "string");
    $pdo = $db->CreatePDOConn();
    $sp_params = "'" . $cust_no . "'"
            . ",''"
    ;
    $QUERY = PrepareSP('get_po', $sp_params);
    $res = $pdo->query($QUERY);
    if ($res) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $data = new InvAjax();
            $data->poid = $row['poid'];
            $data->pono = $row['pono'];
            $data->description = $row['description'];
            $data->poamount = $row['poamount'];
            $data->customer = $row['customerno'];
            $inv[] = $data;
        }
    }
    $db->ClosePDOConn($pdo);
    //print_r($address);
    echo json_encode($inv);
} else if (isset($_POST['work']) && $_POST['work'] == 'renewal_price') {
    $renewalprice = 0;
    $cust_grp = GetSafeValueString($_POST["cust_grp"], "int");
    $duration = GetSafeValueString($_POST["duration"], "int");
    $SQL = sprintf("SELECT unit_msp,renewal FROM " . DB_PARENT . ".customer WHERE customerno = %d", $cust_grp);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $row = $db->get_nextRow();
        if ($duration == "-1") {
            if ($row['renewal'] == '-1' || $row['renewal'] == '-2' || $row['renewal'] == '-3') {
                $row['renewal'] = 0;
            }
            $renewal_duration = $row['renewal'];
        } else {
            $renewal_duration = $duration;
        }
        $unitprice = $row['unit_msp'];
        $renewalprice = $unitprice * $renewal_duration;
    }
    echo $renewalprice;
} else if (isset($_POST['work']) && $_POST['work'] == 'get_invoiceno') {
    $cust_no = GetSafeValueString($_POST["cust_grp"], "int");
    $invtype = GetSafeValueString($_POST["invtype"], "int");
    $ledgerid = GetSafeValueString($_POST['ledger'], "int");
    if ($ledgerid < 10) {
        $ledgerid = "0" . $ledgerid;
    }
    $query = "SELECT invoiceid FROM " . DB_PARENT . ".invoice ORDER BY invoiceid DESC LIMIT 1";
    $db->executeQuery($query);
    $row1 = $db->get_nextRow();
    $invid = $row1['invoiceid'];
    $inv_count = $invid + 1;
    if ($cust_no < 10) {
        $nclient = "0" . $cust_no;
    } else {
        $nclient = $cust_no;
    }
    if ($invtype == 1) {
        $invoiceno = "ES" . $nclient . $ledgerid . $inv_count . "A";
    } else {
        $invoiceno = "ES" . $nclient . $ledgerid . $inv_count;
    }

    echo $invoiceno;
} else if (isset($_POST['work']) && $_POST['work'] == 'get_renewaltext') {
    $statictext = "Elixia Speed Subscription Charges (Service Charges)<br>";
    $cust_grp = GetSafeValueString($_POST["cust_grp"], "int");
    $duration = GetSafeValueString($_POST["duration"], "int");
    $sdate = GetSafeValueString($_POST['sdate'], "string");
    $sql = sprintf("SELECT renewal FROM " . DB_PARENT . ".customer WHERE customerno = %d", $cust_grp);
    $db->executeQuery($sql);
    $row = $db->get_nextRow();
    if ($duration == "-1" || $duration == null) {
        $renewal = $row['renewal'];
    } else {
        $renewal = $duration;
    }
    $firstdate = date('d M Y', strtotime($sdate));

    if ($renewal != '-1' && $renewal != '-2' && $renewal != '-3' && $renewal != '' && $renewal != '0') {
        $enddate = date('d M Y', strtotime("+" . $renewal . " months", strtotime($firstdate)));
        $finaldate = date('d M Y', strtotime("-1 day", strtotime($enddate)));
        $text = "Valid From " . $firstdate . " To " . $finaldate;
    } else {
        $text = "Valid From " . $firstdate . " To ";
    }
    $finaltext = $statictext . $text;
    echo $finaltext;
} else if (isset($_POST['work']) && $_POST['work'] == 'prorata_price') {
    $cust_grp = GetSafeValueString($_POST["cust_grp"], "int");
    $invdate = GetSafeValueString($_POST['invdate'], "string");
    $SQL = sprintf("SELECT unit_msp,renewal FROM " . DB_PARENT . ".customer WHERE customerno = %d", $cust_grp);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $row = $db->get_nextRow();
        $renewalprice = $row['unit_msp'];
        $renewalperiod = $row['renewal'];
    }
    $onedayprice = ceil($renewalprice / 30);
    $lastdate = date("Y-m-t");
    $datediff = $lastdate - $invdate;
    if ($datediff > 0) {
        $totaldays = floor($datediff / (60 * 60 * 24));
        $prodataprice = $totaldays * $onedayprice;
    } else {
        $prodataprice = 0;
    }
    echo $prodataprice;
} else if (isset($_POST['work']) && $_POST['work'] == 'renewal_duration') {
    $renewalperiod = 1;
    $cust_grp = GetSafeValueString($_POST["cust_grp"], "int");
    $SQL = sprintf("SELECT renewal FROM " . DB_PARENT . ".customer WHERE customerno = %d", $cust_grp);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $row = $db->get_nextRow();
        $renewalperiod = $row['renewal'];
        if ($renewalperiod == '-1' || $renewalperiod == '-2' || $renewalperiod == '-3' || $renewalperiod == '0') {
            $renewalperiod = 1;
        }
    }
    echo $renewalperiod;
} else if (isset($_POST['work']) && $_POST['work'] == 'save_invoice') {
    $pdo = $db->CreatePDOConn();
    $form = $_POST;
    $custno = GetSafeValueString($form['custno'], "string");
    $invno = GetSafeValueString($form['invno'], "string");
    $type = GetSafeValueString($form['type'], "string");
    $po = GetSafeValueString($form['po'], "int");
    $productid = GetSafeValueString($form['productid'], "int");
    $invdate = date('Y-m-d', strtotime(GetSafeValueString($form['invdate'], "string")));
    $customer = GetSafeValueString($form['acc_customer'], "string");
    $invtype = GetSafeValueString($form['productid'], "int");
    $ledgerid = GetSafeValueString($form['ledgerid'], "int");
    $duedate1 = date('Y-m-d', strtotime("+1 month", strtotime($invdate)));
    $duedate = date('Y-m-d', strtotime("-1 day", strtotime($duedate1)));
    $duration = isset($form['duration']) ? GetSafeValueString($form['duration'], "string") : 0;
    $duration_custom = isset($form['duration_custom']) ? GetSafeValueString($form['duration_custom'], "string") : 0;
    $status = "Pending";
    $comment = GetSafeValueString($_POST['comment'], "string");

    $vehicles = Array();
    foreach ($form as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 11) == "to_vehicle_")
            $vehicles[] = substr($single_post_name, 11, 12);
    }

    $vcount = count($vehicles);

    $SQL = sprintf("SELECT unitprice,unit_msp,renewal,lease_duration,lease_price,customercompany FROM " . DB_PARENT . ".customer WHERE customerno = %d", $custno);

    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $row = $db->get_nextRow();
        $renewal_duration = $row['renewal'];
        $unitprice = $row['unit_msp'];
        if ($invtype == '1') {
            $unitprice = $row['unitprice'];
        }
        $customercompany = $row['customercompany'];
        $leaseduration = $row['lease_duration'];
        $leaseprice = $row['lease_price'];
    }
    if ($invtype == '2' || $invtype == '3') {// for renewal and lease
        if ($invtype == '3' && $duration == 0) {
            $duration = $duration_custom;
        }
        $sdate = GetSafeValueString($_POST['sdate'], "string");
        $firstdate = date('d M Y', strtotime($sdate));
        if ($leaseduration != '' && $leaseduration != '0' && $invtype == '3') {
            $enddate = date('Y-m-d', strtotime("+" . $leaseduration . " months", strtotime($firstdate)));
            $finaldate = date('Y-m-d', strtotime("-1 day", strtotime($enddate)));
        } else if ($invtype == '2' && $duration != '0') {
            $enddate = date('Y-m-d', strtotime("+" . $duration . " months", strtotime($firstdate)));
            $finaldate = date('Y-m-d', strtotime("-1 day", strtotime($enddate)));
        } else if ($invtype == '2' && $duration == '0' && $renewal != '-1' && $renewal != '-2' && $renewal != '-3' && $renewal != '' && $renewal != '0') {
            $enddate = date('Y-m-d', strtotime("+" . $renewal_duration . " months", strtotime($firstdate)));
            $finaldate = date('Y-m-d', strtotime("-1 day", strtotime($enddate)));
        }
        $quantity1 = isset($form['quantity1']) ? GetSafeValueString($form['quantity1'], "string") : 0;
        $price1 = isset($form['price1']) ? GetSafeValueString($form['price1'], "string") : 0;
        $totalline1 = $quantity1 * $price1;

        $quantity2 = isset($form['quantity2']) ? GetSafeValueString($form['quantity2'], "string") : 0;
        $price2 = isset($form['price2']) ? GetSafeValueString($form['price2'], "string") : 0;
        $totalline2 = $quantity2 * $price2;

        $quantity3 = isset($form['quantity3']) ? GetSafeValueString($form['quantity3'], "string") : 0;
        $price3 = isset($form['price3']) ? GetSafeValueString($form['price3'], "string") : 0;
        $totalline3 = $quantity3 * $price3;

        $quantity4 = isset($form['quantity4']) ? GetSafeValueString($form['quantity4'], "string") : 0;
        $price4 = isset($form['price4']) ? GetSafeValueString($form['price4'], "string") : 0;
        $totalline4 = $quantity4 * $price4;

        $quantity5 = isset($form['quantity5']) ? GetSafeValueString($form['quantity5'], "string") : 0;
        $price5 = isset($form['price5']) ? GetSafeValueString($form['price5'], "string") : 0;
        $totalline5 = $quantity5 * $price5;

        $subtotal = $totalline1 + $totalline2 + $totalline3 + $totalline4 + $totalline5;
        $taxname = "1";
        $tax_percent = 0.15;
        $tax = $subtotal * $tax_percent;
        $grandtotal = round($subtotal + $tax);
    } else if ($invtype == '10') { // others
        $quantityOther1 = isset($form['quantityOther1']) ? GetSafeValueString($form['quantityOther1'], "string") : 0;
        $priceOther1 = isset($form['priceOther1']) ? GetSafeValueString($form['priceOther1'], "string") : 0;
        $totalOther1 = $quantity1 * $price1;

        $quantityOther2 = isset($form['quantityOther2']) ? GetSafeValueString($form['quantityOther2'], "string") : 0;
        $priceOther2 = isset($form['priceOther2']) ? GetSafeValueString($form['priceOther2'], "string") : 0;
        $totalOther2 = $quantityOther2 * $priceOther2;

        $quantityOther3 = isset($form['quantityOther3']) ? GetSafeValueString($form['quantityOther3'], "string") : 0;
        $priceOther3 = isset($form['priceOther3']) ? GetSafeValueString($form['priceOther3'], "string") : 0;
        $totalOther3 = $quantityOther3 * $priceOther3;

        $quantityOther4 = isset($form['quantityOther4']) ? GetSafeValueString($form['quantityOther4'], "string") : 0;
        $priceOther4 = isset($form['priceOther4']) ? GetSafeValueString($form['priceOther4'], "string") : 0;
        $totalOther4 = $quantityOther4 * $priceOther4;

        $quantityOther5 = isset($form['quantityOther5']) ? GetSafeValueString($form['quantityOther5'], "string") : 0;
        $priceOther5 = isset($form['priceOther5']) ? GetSafeValueString($form['priceOther5'], "string") : 0;
        $totalOther5 = $quantityOther5 * $priceOther5;

        $subtotal = $totalOther1 + $totalOther2 + $totalOther3 + $totalOther4 + $totalOther5;
        $taxname = "1";
        $tax_percent = 0.15;
        $tax = $subtotal * $tax_percent;
        $grandtotal = round($subtotal + $tax);
    } else if ($invtype == '1') { // for device
        $acquantity = isset($form['acquantity']) ? GetSafeValueString($form['acquantity'], "string") : 0;
        $acprice = isset($form['acprice']) ? GetSafeValueString($form['acprice'], "string") : 0;
        $totalac = $acquantity * $acprice;

        $gensetquantity = isset($form['gensetquantity']) ? GetSafeValueString($form['gensetquantity'], "string") : 0;
        $gensetprice = isset($form['gensetprice']) ? GetSafeValueString($form['gensetprice'], "string") : 0;
        $totalgenset = $gensetquantity * $gensetprice;

        $doorquantity = isset($form['doorquantity']) ? GetSafeValueString($form['doorquantity'], "string") : 0;
        $doorprice = isset($form['doorprice']) ? GetSafeValueString($form['doorprice'], "string") : 0;
        $totaldoor = $doorquantity * $doorprice;

        $fuelquantity = isset($form['fuelquantity']) ? GetSafeValueString($form['fuelquantity'], "string") : 0;
        $fuelprice = isset($form['fuelprice']) ? GetSafeValueString($form['fuelprice'], "string") : 0;
        $totalfuel = $fuelquantity * $fuelprice;

        $tempquantity = isset($form['tempquantity']) ? GetSafeValueString($form['tempquantity'], "string") : 0;
        $tempprice = isset($form['tempprice']) ? GetSafeValueString($form['tempprice'], "string") : 0;
        $totaltemp = $tempquantity * $tempprice;

        $panicquantity = isset($form['panicquantity']) ? GetSafeValueString($form['panicquantity'], "string") : 0;
        $panicprice = isset($form['panicprice']) ? GetSafeValueString($form['panicprice'], "string") : 0;
        $totalpanic = $panicquantity * $panicprice;


        $buzzerprice = isset($form['buzzerprice']) ? GetSafeValueString($form['buzzerprice'], "string") : 0;
        $buzzerquantity = isset($form['buzzerquantity']) ? GetSafeValueString($form['buzzerquantity'], "string") : 0;
        $totalbuzzer = $buzzerprice * $buzzerquantity;

        $immoquantity = isset($form['immoquantity']) ? GetSafeValueString($form['immoquantity'], "string") : 0;
        $immoprice = isset($form['immopice']) ? GetSafeValueString($form['immopice'], "string") : 0;
        $totalimmo = $immoquantity * $immoprice;

        $commquantity = isset($form['commquantity']) ? GetSafeValueString($form['commquantity'], "string") : 0;
        $commprice = isset($form['commprice']) ? GetSafeValueString($form['commprice'], "string") : 0;
        $totalcomm = $commquantity * $commprice;

        $portquantity = isset($form['portquantity']) ? GetSafeValueString($form['portquantity'], "string") : 0;
        $portprice = isset($form['portprice']) ? GetSafeValueString($form['portprice'], "string") : 0;
        $totalport = $portquantity * $portprice;

        $totaldeviceprice = $vcount * $unitprice;
        $subtotal = $totaldeviceprice + $totalac + $totalgenset + $totaldoor + $totalfuel + $totaltemp + $totalpanic + $totalbuzzer + $totalimmo + $totalcomm + $totalport;

        /* VAT increased by 0.5% from 17 Sep, 2016 */
        $taxname = "2";
        if (JustDate($invdate) < '2016-09-17') {
            $tax_percent = 0.055;
        } elseif (JustDate($invdate) >= '2016-09-17') {
            $tax_percent = 0.060;
        }
        $tax = $subtotal * $tax_percent;
        $grandtotal = round($subtotal + $tax);
    }
    if ($isCreditnote == "1") {
        $tax = "-" . $tax;
        $grandtotal = "-" . $grandtotal;
    }

    // insert in proforma invoice table
    $sp_params = "'" . $invno . "'"
            . ",'" . $custno . "'"
            . ",'" . $ledgerid . "'"
            . ",'" . $customercompany . "'"
            . ",'" . $invdate . "'"
            . ",'" . $invtype . "'"
            . ",'" . $grandtotal . "'"
            . ",'" . $status . "'"
            . ",'" . $taxname . "'"
            . ",'" . $tax . "'"
            . ",'" . $duedate . "'"
            . ",'" . $comment . "'"
            . ",'" . $finaldate . "'"
            . ",'" . $po . "'"
            . ",'" . $productid . "'"
            . ",'" . GetSafeValueString($form['description1'], "string") . "'"
            . ",'" . GetSafeValueString($form['description2'], "string") . "'"
            . ",'" . GetSafeValueString($form['description3'], "string") . "'"
            . ",'" . GetSafeValueString($form['description4'], "string") . "'"
            . ",'" . GetSafeValueString($form['description5'], "string") . "'"
            . ",'" . GetSafeValueString($form['descriptionOther1'], "string") . "'"
            . ",'" . GetSafeValueString($form['descriptionOther2'], "string") . "'"
            . ",'" . GetSafeValueString($form['descriptionOther3'], "string") . "'"
            . ",'" . GetSafeValueString($form['descriptionOther4'], "string") . "'"
            . ",'" . GetSafeValueString($form['descriptionOther5'], "string") . "'"
            . ",'" . GetSafeValueString($form['quantity1'], "int") . "'"
            . ",'" . GetSafeValueString($form['price1'], "int") . "'"
            . ",'" . GetSafeValueString($form['quantity2'], "int") . "'"
            . ",'" . GetSafeValueString($form['price2'], "int") . "'"
            . ",'" . GetSafeValueString($form['quantity3'], "int") . "'"
            . ",'" . GetSafeValueString($form['price3'], "int") . "'"
            . ",'" . GetSafeValueString($form['quantity4'], "int") . "'"
            . ",'" . GetSafeValueString($form['price4'], "int") . "'"
            . ",'" . GetSafeValueString($form['quantity5'], "int") . "'"
            . ",'" . GetSafeValueString($form['price5'], "int") . "'"
            . ",'" . GetSafeValueString($form['quantityOther1'], "int") . "'"
            . ",'" . GetSafeValueString($form['priceOther1'], "int") . "'"
            . ",'" . GetSafeValueString($form['quantityOther2'], "int") . "'"
            . ",'" . GetSafeValueString($form['priceOther2'], "int") . "'"
            . ",'" . GetSafeValueString($form['quantityOther3'], "int") . "'"
            . ",'" . GetSafeValueString($form['priceOther3'], "int") . "'"
            . ",'" . GetSafeValueString($form['quantityOther4'], "int") . "'"
            . ",'" . GetSafeValueString($form['priceOther4'], "int") . "'"
            . ",'" . GetSafeValueString($form['quantityOther5'], "int") . "'"
            . ",'" . GetSafeValueString($form['priceOther5'], "int") . "'"
            . ",@isexecutedOut,@pi_idOut";

    $QUERY = PrepareSP('insert_proforma_invoice', $sp_params);
    $QUERY = str_replace('NULL', '', $QUERY);
    $arrResult = $pdo->query($QUERY)->fetchAll(PDO::FETCH_ASSOC);
    $db->ClosePDOConn($pdo);
    $outputParamsQuery = "SELECT @isexecutedOut AS is_executed,@pi_idOut AS pi_idOut";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
    if ($outputResult['is_executed'] == 1) {
        foreach ($vehicles as $vehicleid) {
            $SQL = sprintf("INSERT INTO `proforma_vehicle_mapping` (`pi_id`
                                        ,`vehicleid`
                                        ,`customerno`
                                        ,`created_by`
                                        ,`created_on`
                                        ,`isdeleted`) 
                        VALUES(%d,%d,%d,%d,'%s',0);", $outputResult['pi_idOut'], $vehicleid, $custno, $teamid, $today);
            $db->executeQuery($SQL);
        }
        echo 'success';
    } else {
        echo 'fail';
    }
} elseif (isset($_POST['work']) && $_POST['work'] == 'allotedveh') {
    $cust_no = GetSafeValueString($_POST["cust_no"], "int");
    $ledgerid = GetSafeValueString($_POST['ledgerid'], "int");
    $leaseVeh = Array();
    $allotveh = Array();
    $pdo1 = $db->CreatePDOConn();
    $QUERY1 = sprintf("SELECT vehicle.vehicleid,vehicle.vehicleno FROM vehicle
                        INNER JOIN unit ON vehicle.uid = unit.uid WHERE unit.onlease = 1 AND vehicle.customerno = %d AND vehicle.isdeleted = 0;", $cust_no);
    $leaseVehlist = $pdo1->query($QUERY1);
    if ($leaseVehlist->rowCount() > 0) {
        while ($row1 = $leaseVehlist->fetch(PDO::FETCH_ASSOC)) {
            $data1 = new InvAjax();
            $data1->vehicleid = $row1['vehicleid'];
            $data1->vehicleno = $row1['vehicleno'];
            $leaseVeh[] = $data1;
        }
    }
    $db->ClosePDOConn($pdo1);
    $pdo = $db->CreatePDOConn();
    $sp_params = "'"
            . ",'" . $cust_no . "'"
            . ",'" . $ledgerid . "'"
            . ",''"
    ;
    $QUERY = PrepareSP('get_ledger_veh_mapping', $sp_params);
    $res = $pdo->query($QUERY);
    if ($res->rowCount() > 0) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $data = new InvAjax();
            $data->ledger_veh_mapid = $row['ledger_veh_mapid'];
            $data->vehicleid = $row['vehicleid'];
            $data->vehicleno = $row['vehicleno'];
            $data->ledgerid = $row['ledgerid'];
            $data->customerno = $row['customerno'];
            $allotveh[] = $data;
        }
    }
    if (empty($leaseVeh)) {
        $inv['renewal'] = $allotveh;
        $inv['lease'] = $leaseVeh;
    } else {
        $diff = array_values(array_udiff($allotveh, $leaseVeh, 'compare_objects'));
        //$comm = array_values(array_uintersect($allotveh, $leaseVeh, 'common_objects'));
        $comm = array_values(array_udiff($allotveh, $diff, 'compare_objects'));
        $inv['renewal'] = $diff;
        $inv['lease'] = $comm;
    }
    $db->ClosePDOConn($pdo);
    //print_r($address);
    echo json_encode($inv);
} else if (isset($_POST['work']) && $_POST['work'] == 'get_leasetext') {
    $statictext = "Elixia Speed Subscription Charges<br>";
    $last_text = "<br>Lease";
    $sdate = GetSafeValueString($_POST['sdate'], "string");
    $cust_no = GetSafeValueString($_POST["custno"], "int");
    $sql = sprintf("SELECT lease_duration,lease_price FROM " . DB_PARENT . ".customer WHERE customerno = %d", $cust_no);
    $db->executeQuery($sql);
    $row = $db->get_nextRow();
    $leaseduration = $row['lease_duration'];
    $leaseprice = $row['lease_price'];
    $firstdate = date('d M Y', strtotime($sdate));

    if ($leaseduration != '' && $leaseduration != '0') {
        $enddate = date('d M Y', strtotime("+" . $leaseduration . " months", strtotime($firstdate)));
        $finaldate = date('d M Y', strtotime("-1 day", strtotime($enddate)));
        $text = "Valid From " . $firstdate . " To " . $finaldate;
    } else {
        $text = "Valid From " . $firstdate . " To ";
    }
    $finaltext = $statictext . $text . $last_text;
    echo $finaltext;
} else if (isset($_POST['work']) && $_POST['work'] == 'getlease_price') {
    $price = 0;
    $cust_no = GetSafeValueString($_POST["custno"], "int");
    $sql = sprintf("SELECT lease_duration,lease_price FROM " . DB_PARENT . ".customer WHERE customerno = %d", $cust_no);
    $db->executeQuery($sql);
    $row = $db->get_nextRow();
    $leaseduration = $row['lease_duration'];
    $leaseprice = $row['lease_price'];
    $price = $leaseduration * $leaseprice;
    echo $price;
} elseif (isset($_POST['work']) && $_POST['work'] == 'allotedvehnewdevice') {
    $cust_no = GetSafeValueString($_POST["cust_no"], "int");
    $ledgerid = GetSafeValueString($_POST['ledgerid'], "int");
    $newVeh = Array();
    $allotveh = Array();
    $pdo1 = $db->CreatePDOConn();
    $QUERY1 = sprintf(" SELECT  v.vehicleid
                                ,v.vehicleno
                                ,v.uid
                        FROM    vehicle v
                        INNER JOIN unit u ON v.uid = u.uid
                        INNER JOIN devices d ON d.uid = u.uid
                        WHERE   d.device_invoiceno='' 
                        AND     v.customerno = %d 
                        AND     v.isdeleted = 0;", $cust_no);
    $leaseVehlist = $pdo1->query($QUERY1);
    if ($leaseVehlist->rowCount() > 0) {
        while ($row1 = $leaseVehlist->fetch(PDO::FETCH_ASSOC)) {
            $data1 = new stdClass();
            $data1->vehicleid = $row1['vehicleid'];
            $data1->vehicleno = $row1['vehicleno'];
            $data1->uid = $row1['uid'];
            $newVeh[] = $data1;
        }
    }
    $db->ClosePDOConn($pdo1);
    $pdo = $db->CreatePDOConn();
    $sp_params = "''"
            . ",'" . $cust_no . "'"
            . ",'" . $ledgerid . "'"
            . ",''"
    ;
    $QUERY = PrepareSP('get_ledger_veh_mapping', $sp_params);
    $res = $pdo->query($QUERY);
    if ($res->rowCount() > 0) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $data = new InvAjax();
            $data->ledger_veh_mapid = $row['ledger_veh_mapid'];
            $data->vehicleid = $row['vehicleid'];
            $data->vehicleno = $row['vehicleno'];
            $data->uid = $row['uid'];
            $data->ledgerid = $row['ledgerid'];
            $data->customerno = $row['customerno'];
            $allotveh[] = $data;
        }
    }
    $diff = array_values(array_udiff($allotveh, $newVeh, 'compare_objects'));
    $comm = array_values(array_udiff($allotveh, $diff, 'compare_objects'));
    $db->ClosePDOConn($pdo);
    //print_r($address);
    if (empty($comm)) {
        $status = 0;
    } else {
        $status = $comm;
    }
    echo json_encode($status);
}

function compare_objects($a, $b) {
    return $a->vehicleid - $b->vehicleid;
}

function common_objects($a, $b) {
    return $a->vehicleid == $b->vehicleid;
}
