<?php

include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
include_once '../../lib/comman_function/reports_func.php';
require_once '../reports/html2pdf.php';
require_once '../../lib/bo/simple_html_dom.php';

$cronm = new CronManager();

$userkey=SHA1(($_GET['userkey']));

$customers = $cronm->getTaxInvoice($_GET['invoiceid']);

$VehType = $cronm->getInvoiceVehicle($_GET['invoiceid']);
 
if ($VehType != NULL && $customers['userkey']==$userkey) {
    $arrWarehouses = array();
    $arrVehicles = array();
    $qty_vehicle = 0;
    $qty_warehouse = 0;
    foreach ($VehType as $objVehDetail) {
        if ($objVehDetail->kind == "Warehouse") {
            $arrWarehouses[] = (array) $objVehDetail;
        } else {
            $arrVehicles[] = (array) $objVehDetail;
        }
    }

    $qty_warehouse = count($arrWarehouses);
    $qty_vehicle = count($arrVehicles);

    $start_date = date('d M Y', strtotime($customers['start_date']));
    $end_date = date('d M Y', strtotime($customers['end_date']));

    $type = 'TAX';
    $startdate = date_create($customers['start_date']);
    $enddate = date_create($customers['end_date']);
    
    $objDiff = date_diff($startdate, $enddate);
    $month = round(($objDiff->y *12) + $objDiff->m + ($objDiff->d / 30));
    
    $unit_msp = $customers['unit_msp'];
    $warehouse_msp = 0;
    if (isset($customers['warehouse_msp']) && $customers['warehouse_msp'] > 0) {
        $warehouse_msp = $customers['warehouse_msp'];
    }
    $price_warehouse = 0;
    $price_vehicle = 0;
    
    
    /*
    $month = date_diff($startdate, $enddate)->format('%m');
    $days = date_diff($startdate, $enddate)->format('%d');
    $month = ($month <= 0) ? 1 : $month;
    $month = ($days > 25) ? $month + 1: $month;
    if ($month != 1) {
        $price_vehicle = $unit_msp * ($month - 1);
        $price_warehouse = ($qty_warehouse > 0) ? $warehouse_msp * ($month - 1) : 0;
    } else {
        $price_vehicle = $unit_msp * ($month);
        $price_warehouse = ($qty_warehouse > 0) ? $warehouse_msp * ($month) : 0;
    }
    */
    
    $price_vehicle = $unit_msp * ($month);
    $price_warehouse = ($qty_warehouse > 0) ? $warehouse_msp * ($month) : 0;

    $discount = $customers['discount'];
    $quantity = $customers['quantity'];
    $tax_type = $customers['tax'];

    $taxable_val_vehicle = 0;
    $total_vehicle = $price_vehicle * $qty_vehicle;
    $taxable_val_vehicle = $total_vehicle - $discount;

    $taxable_val_warehouse = 0;
    $total_warehouse = $price_warehouse * $qty_warehouse;
    $taxable_val_warehouse = $total_warehouse - $discount;

    $taxable_val = $taxable_val_vehicle + $taxable_val_warehouse;

    if ($customers['renewal'] == -3) {
        $lease = '<br>LEASE';
    } else {
        $lease = '';
    }
    $alltotal = $total_vehicle + $total_warehouse;

    $description = "";

    if ($customers['product_id'] == 1) {
        $hsn = "HSN";
        $hsn_code = "85171110";
        $description = 'Elixia Speed GPS Wireless Basic Unit <br> Warranty Upto  ' . $end_date;
    } elseif ($customers['product_id'] == 2) {
        $hsn = "SAC";
        //$hsn_code = "4400452";
        $hsn_code = "998313";
        $description = 'Elixia Speed Subscription Charges (Service Charges)';
        $description = wordwrap($description,33,"<br>\n");
        $description .= '<br>Validity <br>' . $start_date . ' to ' . $end_date . $lease;
    }
    $description = wordwrap($description,60,"<br>\n");
    $grandamt = 0;
    if ($customers['codeid'] != 27) {
        $igst_amt_vehicle = round($total_vehicle * 0.18);
        $igst_amt_warehouse = round($total_warehouse * 0.18);
        $igst_amt = round($alltotal * 0.18);
        $grandamt = round($alltotal + $igst_amt);
        $sgt_heading = '<td colspan="2" style="border-right:1px solid #000;border-bottom: 1px solid #000;width: 160px;">IGST</td>';

        $gst_sub_head = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;width:10%;">Rate</td>
                    <td style="border-bottom: 1px solid #000;width:10%;">Amt.</td>';

        $gst_cell_vehicle = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">18%</td>
                <td style="border-bottom: 1px solid #000;" class="center">' . $igst_amt_vehicle . '</td>';
        $gst_cell_warehouse = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">18%</td>
                <td style="border-bottom: 1px solid #000;" class="center">' . $igst_amt_warehouse . '</td>';

        $gst_total = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;"></td>
                    <td  style="border-bottom: 1px solid #000;"  class="center">' . ($igst_amt_vehicle + $igst_amt_warehouse) . '</td>';
    } else {
        $cgst_amt_vehicle = round($total_vehicle * 0.09);
        $sgst_amt_vehicle = round($total_vehicle * 0.09);
        $cgst_amt_warehouse = round($total_warehouse * 0.09);
        $sgst_amt_warehouse = round($total_warehouse * 0.09);

        $cgst_amt = round($alltotal * 0.09);
        $sgst_amt = round($alltotal * 0.09);
        $grandamt = round($alltotal + $cgst_amt + $sgst_amt);
        $sgt_heading = '<td colspan="2" style="border-right:1px solid #000;border-bottom: 1px solid #000;width:80px;">CGST</td>
                    <td colspan="2" style="border-bottom: 1px solid #000;width: 80px;">SGST</td>';

        $gst_sub_head = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;width:5%;">Rate</td>
                    <td style="border-right:1px solid #000;border-bottom: 1px solid #000;width:5%;">Amt.</td>
                    <td style="border-right:1px solid #000;border-bottom: 1px solid #000;width:5%;">Rate</td>
                    <td style="border-bottom: 1px solid #000;width:5%;">Amt.</td>';

        $gst_cell_vehicle = '<td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">9%</td>
                <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">' . $cgst_amt_vehicle . '</td>
                <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">9%</td>
                <td  style="border-bottom: 1px solid #000;" class="center">' . $sgst_amt_vehicle . '</td>';
        $gst_cell_warehouse = '<td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">9%</td>
                <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">' . $cgst_amt_warehouse . '</td>
                <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">9%</td>
                <td  style="border-bottom: 1px solid #000;" class="center">' . $sgst_amt_warehouse . '</td>';

        $gst_total = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;"></td>
                        <td style="border-right:1px solid #000;border-bottom: 1px solid #000;">' . ($cgst_amt_vehicle + $cgst_amt_warehouse) . '</td>
                        <td style="border-right:1px solid #000;border-bottom: 1px solid #000;"></td>
                        <td style="border-bottom: 1px solid #000;"  class="center">' . ($sgst_amt_vehicle + $sgst_amt_warehouse) . '</td>';
    }

    $towords = ucwords(convert_number_to_words($grandamt));
    $page_vehcount = 50;
    $vcount = count($VehType);
    $pages = ceil($vcount / $page_vehcount);
    $tempArray = array();
    $tempArray = $VehType;
    $x = 0;
    $vehTable = '';
    for ($i = 0; $i < $pages; $i++) {
        $start_count = $i * $page_vehcount;
        $VehType = array_slice($VehType, $start_count, $page_vehcount);
        $vehTable .= '<page>
                        <div style="text-align:center;">
                            <span style="font-weight: bold">Invoice Details</span>
                        </div>';

        if (!empty($VehType)) {
            $vehTable .= '<table style="font-size:17px;width:1250px;border:2px solid #000;text-align: center;" cellpadding="0" cellspacing="0">
                           <tr>
                           <th style="width:10%;border:1px solid black;"> Sr.no </th>
                           <th style="width:50%;border:1px solid black;">Vehicle No / Warehouse</th>
                           </tr>';

            foreach ($VehType as $objVehicle) {
                $x++;
                $vehTable .= ' <tr>
                                <td style="width:10%;border:1px solid black;">' .
                $x . '</td>
                                    <td style="width:50%;border:1px solid black;">' .
                $objVehicle->vehicleno . '
                                    </td>
                                </tr>';
            }

            $vehTable .= '  </table>';
        }

        $VehType = $tempArray;
        $vehTable .= '</page>';
    }
    $vehicleRow = "";
    $warehouseRow = "";
    if ($qty_vehicle > 0) {
        $vehicleRow = '<tr>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;height: 100px;width: 5%;" class="center">1</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;width: 15%;">{{DESCRIPTION}} - Vehicles</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{HSN_VAL}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{QTY_VEHICLE}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{RATE_VEHICLE}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{TOTAL_VEHICLE}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{DISCOUNT}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{TAX_VAL_VEHICLE}}</td>
                        {{GST_VAL_VEHICLE}}
                    </tr>';
    }

    if ($qty_warehouse > 0) {
        if ($qty_vehicle == 0) {
            $warehouseRow = '<tr>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;height: 100px;width: 5%;" class="center">1</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;width: 15%;">{{DESCRIPTION}} - Warehouses</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{HSN_VAL}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{QTY_WAREHOUSE}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{RATE_WAREHOUSE}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{TOTAL_WAREHOUSE}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{DISCOUNT}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{TAX_VAL_WAREHOUSE}}</td>
                        {{GST_VAL_WAREHOUSE}}
                    </tr>';
        } else {
            $warehouseRow = '<tr>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;height: 100px;width: 5%;" class="center">2</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;width: 15%;">{{DESCRIPTION}} - Warehouses</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{HSN_VAL}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{QTY_WAREHOUSE}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{RATE_WAREHOUSE}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{TOTAL_WAREHOUSE}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{DISCOUNT}}</td>
                        <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{TAX_VAL_WAREHOUSE}}</td>
                        {{GST_VAL_WAREHOUSE}}
                    </tr>';
        }
    }

    $message = file_get_contents('../emailtemplates/taxInvoiceTemplate.html');
    if ($qty_vehicle > 0) {
        $message = str_replace("{{VEHICLE_ROW}}", $vehicleRow, $message);
    }
    else {
        $message = str_replace("{{VEHICLE_ROW}}", "", $message);
    }
    if ($qty_warehouse > 0) {
        $message = str_replace("{{WAREHOUSE_ROW}}", $warehouseRow, $message);
    }
    else {
        $message = str_replace("{{WAREHOUSE_ROW}}", "", $message);
    }
    $message = str_replace("{{INVOICETYPE}}", $type, $message);
    $message = str_replace("{{LEDGERNAME}}", $customers['ledgername'], $message);
    if (!empty($customers['address1'])) {
        $message = str_replace("{{ADDRESS1}}", $customers['address1'], $message);
    } else {
        $message = str_replace("{{ADDRESS1}}", NULL, $message);
    }
    if (!empty($customers['address2'])) {
        $message = str_replace("{{ADDRESS2}}", $customers['address2'], $message);
    } else {
        $message = str_replace("{{ADDRESS2}}", NULL, $message);
    }
    if (!empty($customers['address3'])) {
        $message = str_replace("{{ADDRESS3}}", $customers['address3'], $message);
    } else {
        $message = str_replace("{{ADDRESS3}}", NULL, $message);
    }
    $message = str_replace("{{INVOICENO}}", $customers['invoiceno'], $message);
    $message = str_replace("{{INVOICEDATE}}", date('d M Y', strtotime($customers['inv_date'])), $message);
    $message = str_replace("{{INVOICEDUEDATE}}", date('d M Y', strtotime($customers['inv_expiry'])), $message);

    $message = str_replace("{{QTY_VEHICLE}}", $qty_vehicle, $message);
    $message = str_replace("{{QTY_WAREHOUSE}}", $qty_warehouse, $message);

    $message = str_replace("{{TOTAL_VEHICLE}}", $total_vehicle, $message);
    $message = str_replace("{{TOTAL_WAREHOUSE}}", $total_warehouse, $message);

    $message = str_replace("{{ALLTOTAL}}", $alltotal, $message);
    $message = str_replace("{{GRANDAMT}}", $grandamt, $message);
    $message = str_replace("{{INWORDAMT}}", $towords, $message);
    $message = str_replace("{{PARTYGSTNO}}", $customers['gst_no'], $message);
    $message = str_replace("{{PARTYPANNO}}", $customers['pan_no'], $message);
    $message = str_replace("{{PARTYSTATE}}", $customers['state'], $message);
    $message = str_replace("{{PARTYSTATECODE}}", $customers['codeid'], $message);

    $message = str_replace("{{GST_HEADING}}", $sgt_heading, $message);
    $message = str_replace("{{GST_SUB_HEAD}}", $gst_sub_head, $message);

    $message = str_replace("{{GST_VAL_VEHICLE}}", $gst_cell_vehicle, $message);
    $message = str_replace("{{GST_VAL_WAREHOUSE}}", $gst_cell_warehouse, $message);

    $message = str_replace("{{GST_TOTAL}}", $gst_total, $message);

    $message = str_replace("{{DISCOUNT}}", $discount, $message);
    $message = str_replace("{{DESCRIPTION}}", $description, $message);
    $message = str_replace("{{HSN}}", $hsn, $message);
    $message = str_replace("{{HSN_VAL}}", $hsn_code, $message);
    $message = str_replace("{{UNIT}}", '', $message);

    $message = str_replace("{{RATE_VEHICLE}}", $price_vehicle, $message);
    $message = str_replace("{{RATE_WAREHOUSE}}", $price_warehouse, $message);

    $message = str_replace("{{TAX_VAL}}", $taxable_val, $message);
    $message = str_replace("{{TAX_VAL_VEHICLE}}", $taxable_val_vehicle, $message);
    $message = str_replace("{{TAX_VAL_WAREHOUSE}}", $taxable_val_warehouse, $message);
    if (isset($vehTable)) {
        $message = str_replace("{{VEHTABLE}}", $vehTable, $message);
    }
    echo $message;

    $content = ob_get_clean();
    try {
        $html2pdf = new HTML2PDF('P', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($customers['invoiceno'] . "_Invoice.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else {
    echo "Invalid invoice number";
}

function display($data) {
    echo $data;
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
            $str[] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred :
            $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else {
            $str[] = null;
        }
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
    "." . $words[$point / 10] . " " .
    $words[$point = $point % 10] : '';
    $final = $result . "Rupees  " . $points;

    return $final;
}

?>