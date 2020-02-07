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

class Invfunc {

}

define('PAGE_CAPACITY', 55);
$db = new DatabaseManager();

$info = array();

function Prepare_SP($sp_name, $sp_params) {
    return "call " . $sp_name . "(" . $sp_params . ");";
}

function getVehicles($invid, $custno) {

    $db = new DatabaseManager();
    $SQL = "SELECT vehicle.vehicleid,vehicle.vehicleno,vehicle.invcustid,vehicle.customerno,vehicle.uid,devices.deviceid,devices.device_invoiceno,devices.invoiceno,
            invoice_customer.invoicename,invoice_customer.address1,invoice_customer.address2,invoice_customer.address3 FROM vehicle
            INNER JOIN devices ON vehicle.uid = devices.uid
            INNER JOIN unit ON unit.uid = vehicle.uid
            INNER JOIN " . DB_PARENT . ".invoice_customer ON invoice_customer.invcustid = vehicle.invcustid
            WHERE unit.trans_statusid =5 AND devices.device_invoiceno=''
            AND unit.onlease=0 AND vehicle.customerno =$custno AND vehicle.invcustid =$invid";

    $db->executeQuery($SQL);

    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $x++;
            $veh = new Invfunc();
            $veh->vehicleno = $row['vehicleno'];

            $info[] = $veh;
        }
        return $info;
    }
}

// covert to words
function getCount() {
    $db = new DatabaseManager();
    $vcount = $db->get_rowCount();
    return $vcount;
}

function convert_number_to_words($data) {

    $number = $data;
    $no = round($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null; // removed word 's' here if reqired then add here
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
        }
        else
            $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';
    $final = $result . "Rupees  " . $points;

    return $final;
}

function get_pdfdata($POST) {
    $custno = GetSafeValueString($_POST['custno'], "string");
    $po = GetSafeValueString($_POST['po'], "int");
    $ledgerid = GetSafeValueString($_POST['ledgerid'], "string");
    $invid = GetSafeValueString($_POST['invcid'], "string");
    $invno = GetSafeValueString($_POST['invno'], "string");
    $invdate = date('d M Y', strtotime(GetSafeValueString($_POST['invdate'], "string")));
    $ac = GetSafeValueString($_POST['ac'], "string");
    $acquantity = GetSafeValueString($_POST['acquantity'], "string");
    $acprice = GetSafeValueString($_POST['acprice'], "string");
    $genset = GetSafeValueString($_POST['genset'], "string");
    $gensetquantity = GetSafeValueString($_POST['gensetquantity'], "string");
    $gensetprice = GetSafeValueString($_POST['gensetprice'], "string");
    $door = GetSafeValueString($_POST['door'], "string");
    $doorquantity = GetSafeValueString($_POST['doorquantity'], "string");
    $doorprice = GetSafeValueString($_POST['doorprice'], "string");
    $fuel = GetSafeValueString($_POST['fuel'], "string");
    $fuelquantity = GetSafeValueString($_POST['fuelquantity'], "string");
    $fuelprice = GetSafeValueString($_POST['fuelprice'], "string");
    $temp = GetSafeValueString($_POST['temp'], "string");
    $tempquantity = GetSafeValueString($_POST['tempquantity'], "string");
    $tempprice = GetSafeValueString($_POST['tempprice'], "string");
    $panic = GetSafeValueString($_POST['panic'], "string");
    $panicquantity = GetSafeValueString($_POST['panicquantity'], "string");
    $panicprice = GetSafeValueString($_POST['panicprice'], "string");
    $buzzer = GetSafeValueString($_POST['buzzer'], "string");
    $buzzerprice = GetSafeValueString($_POST['buzzerprice'], "string");
    $buzzerquantity = GetSafeValueString($_POST['buzzerquantity'], "string");
    $immo = GetSafeValueString($_POST['immo'], "string");
    $immoquantity = GetSafeValueString($_POST['immoquantity'], "string");
    $immoprice = GetSafeValueString($_POST['immopice'], "string");
    $comm = GetSafeValueString($_POST['comm'], "string");
    $commquantity = GetSafeValueString($_POST['commquantity'], "string");
    $commprice = GetSafeValueString($_POST['commprice'], "string");
    $port = GetSafeValueString($_POST['port'], "string");
    $portquantity = GetSafeValueString($_POST['portquantity'], "string");
    $portprice = GetSafeValueString($_POST['portprice'], "string");
    $customer = GetSafeValueString($_POST['acc_customer'], "string");
    $misc1quantity = GetSafeValueString($_POST['misc1quantity'], "string");
    $misc1price = GetSafeValueString($_POST['misc1price'], "string");
    $misc2quantity = GetSafeValueString($_POST['misc2quantity'], "string");
    $misc2price = GetSafeValueString($_POST['misc2price'], "string");
    $invtype = GetSafeValueString($_POST['invtype'], "int");
    $description1 = GetSafeValueString($_POST['description1'], "string");
    $description2 = GetSafeValueString($_POST['description2'], "string");
    $description3 = GetSafeValueString($_POST['description3'], "string");
    $description4 = GetSafeValueString($_POST['description4'], "string");
    $description5 = GetSafeValueString($_POST['description5'], "string");

    $descriptionOther1 = GetSafeValueString($_POST['descriptionOther1'], "string");
    $descriptionOther2 = GetSafeValueString($_POST['descriptionOther2'], "string");
    $descriptionOther3 = GetSafeValueString($_POST['descriptionOther3'], "string");
    $descriptionOther4 = GetSafeValueString($_POST['descriptionOther4'], "string");
    $descriptionOther5 = GetSafeValueString($_POST['descriptionOther5'], "string");
    $duedate = date('d M Y', strtotime("+30 days", strtotime($invdate)));
    $misc1 = GetSafeValueString($_POST['misc1'], "string");
    $misc2 = GetSafeValueString($_POST['misc2'], "string");
    $vcount = 0;
    $vehicles = Array();
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 11) == "to_vehicle_")
            $vehicles[] = substr($single_post_name, 11, 12);
    }

    $db = new DatabaseManager();
    if ($invtype == '3') {// for credit note
        $invtype = GetSafeValueString($_POST['crtype'], "int");
        $isCreditnote = 1;
    }
    $display = Array();

    if ($invtype == '1' || $invtype == '5') {
        if ($description1 != '') {
            $disp = new Invfunc();
            $disp->type = $description1;
            $disp->quan = GetSafeValueString($_POST['quantity1'], "string");
            $disp->price = GetSafeValueString($_POST['price1'], "string");
            $display[] = $disp;
        }
        if ($description2 != '') {
            $disp = new Invfunc();
            $disp->type = $description2;
            $disp->quan = GetSafeValueString($_POST['quantity2'], "string");
            $disp->price = GetSafeValueString($_POST['price2'], "string");
            $display[] = $disp;
        }
        if ($description3 != '') {
            $disp = new Invfunc();
            $disp->type = $description3;
            $disp->quan = GetSafeValueString($_POST['quantity3'], "string");
            $disp->price = GetSafeValueString($_POST['price3'], "string");
            $display[] = $disp;
        }
        if ($description4 != '') {
            $disp = new Invfunc();
            $disp->type = $description4;
            $disp->quan = GetSafeValueString($_POST['quantity4'], "string");
            $disp->price = GetSafeValueString($_POST['price4'], "string");
            $display[] = $disp;
        }
        if ($description5 != '') {
            $disp = new Invfunc();
            $disp->type = $description5;
            $disp->quan = GetSafeValueString($_POST['quantity5'], "string");
            $disp->price = GetSafeValueString($_POST['price5'], "string");
            $display[] = $disp;
        }
        $tax_type = "Add: SERVICE TAX @ 14%";
        $tax_percent = 0.14;
    }
    elseif ($invtype == '2') {
        if ($descriptionOther1 != '') {
            $disp = new Invfunc();
            $disp->type = $descriptionOther1;
            $disp->quan = GetSafeValueString($_POST['quantityOther1'], "string");
            $disp->price = GetSafeValueString($_POST['priceOther1'], "string");
            $display[] = $disp;
        }
        if ($descriptionOther2 != '') {
            $disp = new Invfunc();
            $disp->type = $descriptionOther2;
            $disp->quan = GetSafeValueString($_POST['quantityOther2'], "string");
            $disp->price = GetSafeValueString($_POST['priceOther2'], "string");
            $display[] = $disp;
        }
        if ($descriptionOther3 != '') {
            $disp = new Invfunc();
            $disp->type = $descriptionOther3;
            $disp->quan = GetSafeValueString($_POST['quantityOther3'], "string");
            $disp->price = GetSafeValueString($_POST['priceOther3'], "string");
            $display[] = $disp;
        }
        if ($descriptionOther4 != '') {
            $disp = new Invfunc();
            $disp->type = $descriptionOther4;
            $disp->quan = GetSafeValueString($_POST['quantityOther4'], "string");
            $disp->price = GetSafeValueString($_POST['priceOther4'], "string");
            $display[] = $disp;
        }
        if ($descriptionOther5 != '') {
            $disp = new Invfunc();
            $disp->type = $descriptionOther5;
            $disp->quan = GetSafeValueString($_POST['quantityOther5'], "string");
            $disp->price = GetSafeValueString($_POST['priceOther5'], "string");
            $display[] = $disp;
        }
        $tax_type = "Add: SERVICE TAX @ 14%";
        $tax_percent = 0.14;
    }
    else {
        if ($ac == 1) {
            $disp = new Invfunc();
            $disp->type = "AC Sensor";
            $disp->quan = $acquantity;
            $disp->price = $acprice;
            $display[] = $disp;
        }
        if ($genset == 1) {
            $disp = new Invfunc();
            $disp->type = "Genset Sensor";
            $disp->quan = $gensetquantity;
            $disp->price = $gensetprice;
            $display[] = $disp;
        }
        if ($door == 1) {
            $disp = new Invfunc();
            $disp->type = "Door Sensor";
            $disp->quan = $doorquantity;
            $disp->price = $doorprice;
            $display[] = $disp;
        }
        if ($temp == 1) {
            $disp = new Invfunc();
            $disp->type = "Temperature Sensor";
            $disp->quan = $tempquantity;
            $disp->price = $tempprice;
            $display[] = $disp;
        }
        if ($fuel == 1) {
            $disp = new Invfunc();
            $disp->type = "Fuel Sensor";
            $disp->quan = $fuelquantity;
            $disp->price = $fuelprice;
            $display[] = $disp;
        }
        if ($buzzer == 1) {
            $disp = new Invfunc();
            $disp->type = "Buzzer";
            $disp->quan = $buzzerquantity;
            $disp->price = $buzzerprice;
            $display[] = $disp;
        }
        if ($panic == 1) {
            $disp = new Invfunc();
            $disp->type = "Panic";
            $disp->quan = $panicquantity;
            $disp->price = $panicprice;
            $display[] = $disp;
        }
        if ($immo == 1) {
            $disp = new Invfunc();
            $disp->type = "Immobilizer";
            $disp->quan = $immoquantity;
            $disp->price = $immoprice;
            $display[] = $disp;
        }
        if ($comm == 1) {
            $disp = new Invfunc();
            $disp->type = "Two Way Communication";
            $disp->quan = $commquantity;
            $disp->price = $commprice;
            $display[] = $disp;
        }
        if ($port == 1) {
            $disp = new Invfunc();
            $disp->type = "Portable Device";
            $disp->quan = $portquantity;
            $disp->price = $portprice;
            $display[] = $disp;
        }
        if ($misc1 == 1) {
            $disp = new Invfunc();
            $disp->type = GetSafeValueString($_POST['misc1text'], "string");
            $disp->quan = $misc1quantity;
            $disp->price = $misc1price;
            $display[] = $disp;
        }
        if ($misc2 == 1) {
            $disp = new Invfunc();
            $disp->type = GetSafeValueString($_POST['misc2text'], "string");
            $disp->quan = $misc2quantity;
            $disp->price = $misc2price;
            $display[] = $disp;
        }

        /* VAT increased by 0.5% from 17 Sep, 2016 */
        if (JustDate($invdate) < '2016-09-17') {
            $tax_type = "Add: MVAT @ 5.5%";
            $tax_percent = 0.055;
        }
        elseif (JustDate($invdate) >= '2016-09-17') {
            $tax_type = "Add: MVAT @ 6.0%";
            $tax_percent = 0.060;
        }
    }

    $pdo = $db->CreatePDOConn();
    $sp_params1 = "'" . $custno . "'"
            . ",'" . $po . "'"
    ;
    $QUERY1 = Prepare_SP('get_po', $sp_params1);
    $row1 = $pdo->query($QUERY1)->fetch(PDO::FETCH_ASSOC);
    $data1 = new Invfunc();
    $data1->pono = $row1['pono'];
    if ($data1->pono == '') {
        $data1->pono = "NA";
    }
    if ($row1['podate'] == '0000-00-00' || $row1['podate'] == '1970-01-01' || !isset($row1['podate'])) {
        $data1->podate = 'NA';
    }
    else {
        $data1->podate = date("d-m-Y", strtotime($row1['podate']));
    }

    $db->ClosePDOConn($pdo);

    $pdo2 = $db->CreatePDOConn();
    $sp_params2 = "'" . $ledgerid . "'"
            . ",''"
    ;
    $QUERY2 = Prepare_SP('get_ledger', $sp_params2);
    $row2 = $pdo2->query($QUERY2)->fetch(PDO::FETCH_ASSOC);

    $inv = new Invfunc();
    $inv->ledgerid = $row2['ledgerid'];
    $inv->ledgername = $row2['ledgername'];
    $inv->add1 = $row2['address1'];
    $inv->add2 = $row2['address2'];
    $inv->add3 = $row2['address3'];
    $inv->st_no = $row2['st_no'];
    $inv->vat_no = $row2['vat_no'];
    $inv->cst_no = $row2['cst_no'];
    $db->ClosePDOConn($pdo2);

    if (!empty($vehicles)) {
        foreach ($vehicles as $vehid) {
            $SQL = sprintf("SELECT vehicle.vehicleid
                    ,vehicle.vehicleno
                    ,vehicle.customerno
                    ,customer.unitprice
                    ,customer.unit_msp
                    ,devices.warrantyexpiry as device_exp
                    ,devices.expirydate as renewal_exp
                    ,vehicle.kind
                    FROM vehicle
                        INNER JOIN devices ON vehicle.uid = devices.uid
                        INNER JOIN unit ON unit.uid = vehicle.uid
                        INNER JOIN " . DB_PARENT . ".customer ON customer.customerno=vehicle.customerno
                        WHERE  vehicle.vehicleid=%d
                        AND vehicle.customerno=%d", $vehid, $custno);

            $db->executeQuery($SQL);

            if ($db->get_rowCount() > 0) {
                $vcount++;
                $row = $db->get_nextRow();
                $veh = new Invfunc();
                $veh->vehicleno = $row['vehicleno'];
                $veh->vehicleid = $row['vehicleid'];
                $veh->unitp = $row['unitprice'];
                if ($row['device_exp'] == '0000-00-00' || $row['device_exp'] == '1970-01-01') {
                    $veh->device_exp = '';
                }
                else {
                    $veh->device_exp = date("d M Y", strtotime($row['device_exp']));
                }
                if ($row['renewal_exp'] == '0000-00-00' || $row['renewal_exp'] == '1970-01-01') {
                    $veh->renewal_exp = '';
                }
                else {
                    $veh->renewal_exp = date("d-m-Y", strtotime($row['renewal_exp']));
                }
                $veh->renewal_price = $row['unit_msp'];
                $veh->kind = $row['kind'];
                $data[] = $veh;
            }
        }
    }
    $vehcount = count($data);
    $VehType = array_filter($data, function($vehElement) {
        return $vehElement->kind != "Warehouse";
    });
    $VehType = array_values($VehType);
    $WehType = array_filter($data, function($vehElement) {
        return $vehElement->kind == "Warehouse";
    });
    $WehType = array_values($WehType);
    $res = Array();

    $finaldata = '';
    $finaldata.='
<style type="text/css">
table
{
   border-spacing: 0;
}
table td, table th
{
    padding: 0;
}
.tab {
    left: 12em;
   }
  hr {
    display: block;
    height: 2px;
    border: 0;
    border-top: 1px solid #0C9BAF;
    margin: 1em 0;
    padding: 0;
}
</style>
<page>
<div style="width:700px;">
<table align="right" style="border:none;">
	<tr>
		<td style="width:328px;height:77px;border:none;text-align:right">
			<img style="width:100%;height:100%;" src="../../images/elixiatech-logo.png"/>
		</td>
    </tr>
</table>
</div>
<span style="font: bold 100% sans-serif; letter-spacing: 0.3em; text-align: center; text-transform: uppercase;">';
    if (isset($isCreditnote)) {
        $finaldata.='<h2>CREDIT NOTE</h2>';
    }
    else {
        $finaldata.='<h2>TAX INVOICE</h2>';
    }
    $finaldata.='</span>
<br/>
<table align="center" style="width:600px;font-size:13px;border:2px solid #000;" cellpadding="0" cellspacing="0">

                <tr>
                    <td style="width:50% ;border-right:2px solid #000;border-bottom:1px solid #000;">
                        ' . $inv->ledgername . ' <br> ' . $inv->add1 . ' <br> ' . $inv->add2 . ' <br> ' . $inv->add3 . '
                    </td>
                    <td style="width:50%;border-bottom:1px solid #000;">
                    <table>
                    <tr>
                    <td>
                    INVOICE NO: ' . $invno . ' </td>
                     <td> DATE: ' . $invdate . '</td>
                     </tr>
                        <tr>
                        <td>
                        P.O.NO: ' . $data1->pono . '</td>
                        <td>DATE: ' . $data1->podate . '</td>
                        </tr>
                        <tr>
                        <td>Due Date:  ' . $duedate . '</td>
                        </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                <td colspan="100">

                <table style="width:100%; font-size:14px;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:10%;border:1px solid #000;text-align:center">
                            SR.No
                        </td>
                        <td style="width:50%;border:1px solid #000;text-align:center">
                            Description
                        </td>
                        <td style="width:15%;border:1px solid #000;text-align:center">
                            Quantity (Nos.)
                        </td>
                        <td style="width:15%;border:1px solid #000;text-align:center">
                            Rate (Rs.)
                        </td>
                        <td style="width:10%;border:1px solid #000;text-align:center">
                            Total<br>
                            Amount
                        </td>
                    </tr>';
    if ($invtype == "0") {


        $finaldata.='<tr  style="height: 250px;vertical-align: top;">
                        <td style="width:10%;border:1px solid #000;text-align:center">
                            1
                        </td>
                        <td style="width:50%;border:1px solid #000;height:100px;">
                             Elixia Speed GPS Wireless Basic Unit <br>';
        if ($vehcount <= '5') {
            $finaldata.='<table cellpadding = "4" cellspacing = "4">
                        <tr>
                            <th>Vehicle No</th>
                            <th>Warranty Upto</th>
                        </tr>';
            foreach ($data as $vehs) {
                $finaldata.= '<tr>
                          <td style="width:150px;">' . $vehs->vehicleno . '</td>
                          <td style="width:100px;">' . $vehs->device_exp . '</td>
                           </tr>';
            }
            $finaldata.='</table>';
        }
        $finaldata.='</td>

                        <td style="width:15%;border:1px solid #000;text-align:center" >
                            ' . $vcount . '
                        </td>
                        <td style="width:15%;border:1px solid #000;text-align:center">
                            ' . $veh->unitp . '
                        </td>
                        <td style="width:10%;border:1px solid #000;text-align:center">';
        $tamt = $veh->unitp * $vcount;
        $finaldata.= $tamt;
        $finaldata.='
                        </td>
                        </tr>';
        if (empty($display)) {
            $finaldata.='
                    <tr style="height: 40px;">
                        <td style="width:10%;height:40px;border:1px solid #000;text-align:center; ">

                        </td>
                        <td style="width:50%;height:40px;border:1px solid #000;text-align:center">

                        </td>
                        <td style="width:15%;height:40px;border:1px solid #000;text-align:center">

                        </td>
                        <td style="width:15%;height:40px;border:1px solid #000;text-align:center">

                        </td>
                        <td style="width:10%;height:40px;border:1px solid #000;text-align:center">

                        </td>
                    </tr>';
        }
        else {
            $sr = 1;
            $astotal = 0;
            foreach ($display as $displays) {
                $sr++;

                $finaldata.='
                    <tr style="height: 20px;">
                    <td style="width:10%;border:1px solid #000;text-align:center; ">
                         ' . $sr . '
                    </td>
                    <td style="width:50%;border:1px solid #000;text-align:center">
                        ' . $displays->type . '
                    </td>
                    <td style="width:15%;border:1px solid #000;text-align:center">
                        ' . $displays->quan . '
                    </td>
                    <td style="width:15%;border:1px solid #000;text-align:center">
                        ' . $displays->price . '
                    </td>
                    <td style="width:10%;border:1px solid #000;text-align:center">';
                $stotal = "";
                $stotal = $displays->quan * $displays->price;
                $astotal+=$stotal;
                $finaldata.=$stotal;
                $finaldata.='
                    </td>
                </tr>';
            }
        }
    }
    else {// For Renewals Other
        $sr = 0;
        $astotal = 0;
        $res = Array();
        foreach ($VehType as $vehs) {
            $res[] = $vehs->vehicleno;
        }
        $imp = implode(", ", $res);

        foreach ($display as $displays) {
            $sr++;

            $finaldata.='                        <tr style="height: 20px;vertical-align: top;">
                                    <td style="width:10%;border:1px solid #000;text-align:center; ">'
                    . $sr . '
                                    </td>
                                    <td style="width:50%;border:1px solid #000;text-align:left">
                                        ' . $displays->type;
            if ($sr == "1" && $invtype != '2' && $vehcount <= '5') {
                $finaldata.=' <br> (
                                           ' . $imp . '
                                            ) ';
            }
            $finaldata.='        </td>
                                    <td style="width:15%;border:1px solid #000;text-align:center">
                                        ' . $displays->quan . '
                                    </td>
                                    <td style="width:15%;border:1px solid #000;text-align:center">
                                        ' . $displays->price . '
                                    </td>
                                    <td style="width:10%;border:1px solid #000;text-align:center">';

            $stotal = "";
            $stotal = $displays->quan * $displays->price;
            $astotal+= $stotal;
            $stotal = $stotal == '0' ? "" : $stotal;
            $finaldata.= $stotal;

            $finaldata.='        </td>
                                </tr>';
        }

        $finaldata.='         <tr style="height:100px;">
                                <td style="width:10%;border:1px solid #000;text-align:center;height:100px;">

                                </td>
                                <td style="width:50%;border:1px solid #000;text-align:center;height:100px;">

                                </td>
                                <td style="width:15%;border:1px solid #000;text-align:center;height:100px;">

                                </td>
                                <td style="width:15%;border:1px solid #000;text-align:center;height:100px;">

                                </td>
                                <td style="width:10%;border:1px solid #000;text-align:center;height:100px;">

                                </td>
                            </tr>';
    }
    $finaldata.='
                    <tr>
                    <td style="width:15%; text-align: center;border:1px solid #000;" colspan="4"> Sub Total</td>
                    <td style="width:10%;text-align:right;border:1px solid #000;">';
    $alltotal = 0;
    isset($tamt) ? $tamt : 0;
    $alltotal = $tamt + $astotal;
    $finaldata.=$alltotal;
    $finaldata.='
                    </td>
                </tr>
                </table>
                </td>
                </tr>
                <tr>
                    <td colspan="100">
                        <!----------mvat table------------------------------------>
                        <table style="border:1px solid #000;width:100%; font-size:17px" cellpadding="0" cellspacing="0">
                            <tr style="height: 40px;text-align:center">
                                <td style="width:70%;text-align:center">
                                    ' . $tax_type . '
                                </td>
                                <td style="width:30%;text-align:right">';
    $vat = $alltotal * $tax_percent;

    $finaldata.=$vat;
    $finaldata.='
                                </td>
                            </tr>';
    if ($invtype == '1' || $invtype == '2' || $invtype == '5') {
        $finaldata.='                    <tr style="height: 25px;text-align:center">
                            <td style="width:70%;text-align:center">
                                Add: Swachh Bharat Cess @ 0.5%
                            </td>
                            <td style="width:30%;text-align:right">';
        $swachhtax = $alltotal * 0.005;
        $finaldata.= $swachhtax;
        $finaldata.='                    </td>
                        </tr>';
    }

    if ($invtype == '1' || $invtype == '2' || $invtype == '5') {
        $finaldata.='<tr style="height: 25px;text-align:center">
                            <td style="width:70%;text-align:center">
                                Add: Krishi Kalyan Cess @ 0.5%
                            </td>
                            <td style="width:30%;text-align:right">';
        $kktax = $alltotal * 0.005;
        $finaldata.= $kktax;
        $finaldata.='</td></tr>';
    }


    $finaldata.='   </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="100">
                        <!------convert number to words------------------------>
                        <table style="border:1px solid #000;width:100%; font-size:13px" cellpadding="0" cellspacing="0">
                            <tr style="height: 40px">
                                <td style="width:80%">
                                    <b>TOTAL AMOUNT:</b>
                                </td>
                                <td style="width:5%">
                                    <b>Rs.</b>
                                </td>
                                <td style="width:15%;text-align: right">
                                    <b>';
    isset($swachhtax) ? $swachhtax : 0;
    isset($kktax) ? $kktax : 0;

    $grandamt = round($alltotal + $vat + $swachhtax + $kktax);
    $finaldata.=$grandamt;
    $finaldata.='
                                     </b>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:80%"><b>Rupees ';
    $towords = ucwords(convert_number_to_words($grandamt));
    $finaldata.=$towords;
    $finaldata.='
                                      Only
                                        </b>
                                </td>
                                <td></td>
                                <td style="width:10%;text-align:right">
                                    <b>E. & O.E.</b>
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>
                <tr>
                    <td colspan="100%">
                        <table style="border:1px solid #000;width:82%;" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="width:60%; border-right: 2px solid #000;">
                                    <!-------note table----------------->
                                    <table style="width:100%;font-weight:bold;font-size:12.3px" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="border-bottom: 2px solid #000;">
                                        <u>Note:</u><br>
                                        1. <span style="font-size:123%;">Cheque should be issued in the name of Elixia Tech Solutions Ltd.</span><br>
                                        2. Subject to Mumbai Jurisdiction<br>
                                        3. Devices once sold will not be taken back<br>
                                        4. For device, our responsibility ceases when the installation has been completed<br>
                                        5. Installation & implementation will be done only after amount is credited<br>
                                        6. Software is issued subject to terms & conditions specified on the web application

                                            </td>

                                        </tr>
                                        <tr>
                                            <td style="width:60%">
                                                <!----------------------Party service tax etc---------------------------->
                                                <table style=" width:100%; font-size:13.5px" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td>
                                                            <b>PARTY SERVICE TAX NO:-' . $inv->st_no . '</b><br>
                                                            <b>PARTY VAT TIN NO:-' . $inv->vat_no . '</b><br>
                                                            <b>PARTY CST TIN NO:-' . $inv->cst_no . '</b>

                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width:40%;padding:2px;" >
                                    <!----------online details table------------------------>
                                    <table style="width:82%; height:100%;font-weight:bold;font-size:13.5px" cellpadding="0" cellspacing="0" >
                                        <tr>
                                            <td style="text-align:center">
                                            <u>ONLINE TRANSFER DETAILS</u>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Elixia Tech Solutions Ltd.<br>
                                                Bank Name: IDBI Bank<br>
                                                Branch: Ghatkopar (East), Mumbai<br>
                                                A/C No: 0033102000014650<br>
                                                IFSC Code: IBKL0000033<br>
                                                MICR Code: 400259008<br>
                                                Address: Rupa Plaza, Ground Floor,<br>
                                                Jawahar Road, Plot No 4, TPS 1,<br>
                                                Near LIC Building, Ghatkopar (East),<br>
                                                Mumbai, Maharashtra 400077<br/>

                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>
                        </td>
                        </tr>

                        <tr>
                        <td colspan="100%" style="border:1px solid #000;">

                            <b>CIN NO:-U72300MH2011PTC219715</b>
                        </td>
                        </tr>

                        <tr>
                        <td colspan="100%">

                        <!-------main frame--------------------->
                        <table style="border:1px solid #000;width:80%;" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="width:60%;border-right:2px solid #000">
                                    <!--------vat st cst tax table---------------------------->
                                    <table style="width:100%; font-size:13px" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="border-bottom:2px solid #000">
                                                <b>SERVICE TAX NO: AACCE7724QSD001</b><br>
                                                <b>VAT TIN No: 27055259309V w.e.f. 17-5-13</b><br>
                                                <b>CST TIN No:  27055259309C w.e.f. 17-5-13</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:60%">
                                                <!------------------------------disclaimer table---------------------------------->
                                                <table style="width:100%;font-weight:bold;font-size:10px" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td>
                                                            I/We hereby certify that my/our registration certificate under the Maharashtra Value Added Tax Act, 2002<br>
                                                            is in force on the date on which the sale of the goods specified in this Tax Invoice is made by me/us and<br>
                                                            that the transaction of sale covered by this Tax Invoice has been effected by me/us and it shall be <br>
                                                            accounted for in the turnover of sales while filing of return and the due tax, if any, payable on the same<br>
                                                            has been paid or shall be paid

                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width:40%;vertical-align:top;">
                                    <!----------------------signature table---------------------------------------->
                                    <table style="width:100%; font-size:13px" cellpadding="0" cellspacing="0">

                                        <tr>
                                            <td style="text-align:center;font-weight:bold;">
                                                For Elixia Tech Solutions Ltd.
                                            </td>
                                        </tr>
                                        <tr style="height:60px;">
                                            <td style="height:60px;">
                                                    <img alt="" src="../../images/stampsign1.png">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:center;vertical-align:bottom;">
                                                Authorized Signatory
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        </td>
                        </tr>
            </table>';
    $finaldata .='
	<page_footer>';
    $finaldata .= '<div align="center"  style="background-color:#0C9BAF;">
                    <span style="color:#FFFFFF">Elixia Tech Solutions Ltd.</span>
                </div>';
    $finaldata .= '<div align="center"  style="background-color:#0C9BAF;">
					<span style="color:#FFFFFF">715, Neelkanth Corporate Park, Vidyavihar West, Mumbai - 400086.</span>
                </div>';
    $finaldata .= '<div align="center" style="background-color:#0C9BAF;">
                    <span style="color:#FFFFFF">Landline: +91 22 2513 7470/71  |  Email: sales@elixiatech.com  |  Website: www.elixiatech.com</span>
                </div>';
    $finaldata .= '</page_footer></page>'
    ;
    if ($vehcount > '5' && $invtype != '2' && !empty($VehType)) {
        $page_vehcount = PAGE_CAPACITY;
        $vcount = count($VehType);
        $pages = ceil($vcount / $page_vehcount);
        $tempArray = array();
        $tempArray = $VehType;
        $x = 0;
        for ($i = 0; $i < $pages; $i++) {
            $start_count = $i * $page_vehcount;
            $VehType = array_slice($VehType, $start_count, $page_vehcount);
            $finaldata.='<page>
            <div style="text-align:center;">
            <span style="font-weight: bold">Invoice Details</span>
            </div>';
            if ($invtype == '0') {
                $finaldata.='<table style="font-size:17px;width:1250px;border:2px solid #000;text-align: center;" cellpadding="0" cellspacing="0">
                            <tr>
                            <th style="width:10%;"> Sr.no </th>
                            <th style="width:30%;">Vehicle No</th>
                            <th style="width:20%;">Warranty Expiry</th>
                            </tr>';
                foreach ($VehType as $vehs) {
                    $x++;
                    $finaldata.='<tr>
                                    <td style="width:10%;">' .
                            $x . '</td>
                                    <td style="width:30%;">' .
                            $vehs->vehicleno . '
                                    </td>
                                    <td style="width:20%;">' .
                            $vehs->device_exp . '
                                    </td>
                                </tr>';
                }

                $finaldata.='</table>';
            }

            if ($invtype == '1' || $invtype == '5') {
                if (!empty($VehType)) {
                    $finaldata.='<table style="font-size:17px;width:1250px;border:2px solid #000;text-align: center;" cellpadding="0" cellspacing="0">
                           <tr>
                           <th style="width:10%;"> Sr.no </th>
                           <th style="width:50%;">Vehicle No</th>
                           </tr>';

                    foreach ($VehType as $vehs) {
                        $x++;
                        $finaldata.=' <tr>
                                <td style="width:10%;">' .
                                $x . '</td>
                                    <td style="width:50%;">' .
                                $vehs->vehicleno . '
                                    </td>
                                </tr>';
                    }

                    $finaldata.='  </table>';
                }
            }
            $VehType = $tempArray;
            $finaldata.='</page>';
        }
    }
// For Warehouse
    if ($invtype != '2' && !empty($WehType)) {
        $page_vehcount = PAGE_CAPACITY;
        $whcount = count($WehType);
        $pages = ceil($whcount / $page_vehcount);
        $whtempArray = array();
        $whtempArray = $WehType;
        $x = 0;
        for ($i = 0; $i < $pages; $i++) {
            $start_count = $i * $page_vehcount;
            $WehType = array_slice($WehType, $start_count, $page_vehcount);
            $finaldata.='<page>
            <div style="text-align:center;">
            <span style="font-weight: bold">Invoice Details</span>
            </div>';
            if ($invtype == '0') {

                if (!empty($WehType)) {
                    $finaldata.='
                                    <table style="font-size:17px;width:1250px;border:2px solid #000;text-align: center;" cellpadding="0" cellspacing="0">
                                        <tr>
                                        <th style="width:10%;"> Sr.no </th>
                                        <th style="width:30%;">WareHouse</th>
                                        <th style="width:20%;">Warranty Expiry</th>
                                        </tr>';

                    foreach ($WehType as $wehs) {
                        $x++;
                        $finaldata.='  <tr>
                                                <td style="width:10%;">' .
                                $x . '</td>
                                                <td style="width:30%;">' .
                                $wehs->vehicleno . '
                                                </td>
                                                <td style="width:20%;">' .
                                $wehs->device_exp . '
                                                </td>
                                            </tr>';
                    }

                    $finaldata.='   </table>';
                }
            }

            if ($invtype == '1' || $invtype == '5') {

                if (!empty($WehType)) {

                    $finaldata.='
                            <table style="font-size:17px;width:1250px;border:2px solid #000;text-align: center;" cellpadding="0" cellspacing="0">
                                <tr>
                                <th style="width:10%;"> Sr.no </th>
                                <th style="width:50%;">WareHouse</th>
                                </tr>';

                    foreach ($WehType as $wehs) {
                        $x++;
                        $finaldata.='
                                    <tr>
                                    <td style="width:10%;">' .
                                $x . '</td>
                                        <td style="width:50%;">' .
                                $wehs->vehicleno
                                . '</td>
                                    </tr>';
                    }

                    $finaldata.='</table>';
                }
            }
            $WehType = $whtempArray;
            $finaldata.='</page>';
        }
    }
    echo $finaldata;
}

?>
