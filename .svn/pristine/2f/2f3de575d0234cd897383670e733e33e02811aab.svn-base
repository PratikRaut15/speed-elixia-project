<?php

include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
include_once '../../lib/comman_function/reports_func.php';
require_once '../reports/html2pdf.php';
require_once '../../lib/bo/simple_html_dom.php';
ob_start();
$cronm = new CronManager();

$creditnote = $cronm->getCreditNoteData($_GET['credit_note_id']);
// echo "<pre>";
// print_r($creditnote["invoiceno"]); exit;
// $customers = $cronm->getTaxInvoiceForErp($_GET['invoiceid']);

 $customers = $cronm->getTaxInvoice($creditnote["invoiceno"]);
// $id=8585;
// $customers = $cronm->getTaxInvoice($id);

//  echo "<pre>";
// print_r($customers); exit;
$invoice["totalAmount"]=$creditnote["credit_amount"];

$start_date = date('d M Y', strtotime($customers['start_date']));
$end_date = date('d M Y', strtotime($customers['end_date']));

// if(isset($customers['start_date'])&&($customers['start_date']!='0000-00-00')){
//     $invoice["description"].='<br>'.$start_date.' to '.$end_date;
// }
//$customers['codeid']=27;
if ($customers != NULL) {
    $start_date = date('d M Y', strtotime($customers['start_date']));
    $end_date = date('d M Y', strtotime($customers['end_date']));

    $type = 'Credit Note';
    $startdate = date_create($customers['start_date']);
    $enddate = date_create($customers['end_date']);
    
    $objDiff = date_diff($startdate, $enddate);
    $month = round(($objDiff->y *12) + $objDiff->m + ($objDiff->d / 30));

    $alltotal = $invoice["totalAmount"];

    $description = "";
    if ($customers['product_id'] == 1) {
        $description = 'Elixia Speed GPS Wireless Basic Unit <br> Warranty Upto  ' . $end_date;
    } elseif ($customers['product_id'] == 2) {
        $hsn = "SAC";
        $hsn_code = "4400452";
        $hsn_code = "998313";
        $description = 'Elixia Speed Subscription Charges (Service Charges)';
        $description = wordwrap($description,33,"<br>\n");
        // $description .= '<br>Validity <br>' . $start_date . ' to ' . $end_date . $lease;
    }
    $description = wordwrap($description,60,"<br>\n");
    $grandamt = 0;
    if ($customers['codeid'] != 27) {
        $igst_amt = round($invoice["totalAmount"] * 0.18);
        $igst_amt = round($alltotal * 0.18);
        $grandamt = round($alltotal + $igst_amt);
        $sgt_heading = '<td colspan="2" style="border-right:1px solid #000;border-bottom: 1px solid #000;width: 180px;">IGST</td>';

        $gst_sub_head = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;width:10%;">Rate</td>
                    <td style="border-bottom: 1px solid #000;width:10%;">Amt.</td>';

        $gst_cell = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">18%</td>
                <td style="border-bottom: 1px solid #000;" class="center">' . $igst_amt . '</td>';

        $gst_total = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;"></td>
                    <td  style="border-bottom: 1px solid #000;"  class="center">' . ($igst_amt) . '</td>';
    } else {
        $cgst_amt = round($invoice["totalAmount"] * 0.09);
        $sgst_amt = round($invoice["totalAmount"] * 0.09);

        $cgst_amt = round($alltotal * 0.09);
        $sgst_amt = round($alltotal * 0.09);
        $grandamt = round($alltotal + $cgst_amt + $sgst_amt);;
        $sgt_heading = '<td colspan="2" style="border-right:1px solid #000;border-bottom: 1px solid #000;width:90px;">CGST</td>
                    <td colspan="2" style="border-right:1px solid #000;border-bottom: 1px solid #000;width:90px;">SGST</td>';

        $gst_sub_head = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;">Rate</td>
                    <td style="border-right:1px solid #000;border-bottom: 1px solid #000;">Amt.</td>
                    <td style="border-right:1px solid #000;border-bottom: 1px solid #000;">Rate</td>
                    <td style="border-bottom: 1px solid #000;">Amt.</td>';

        $gst_cell = '<td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">9%</td>
                <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">' . $cgst_amt . '</td>
                <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">9%</td>
                <td  style="border-bottom: 1px solid #000;" class="center">' . $sgst_amt . '</td>';

        $gst_total = '<td style="border-right:1px solid #000;border-bottom: 1px solid #000;"></td>
                        <td style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">' . ($cgst_amt) . '</td>
                        <td style="border-right:1px solid #000;border-bottom: 1px solid #000;"></td>
                        <td style="border-bottom: 1px solid #000;"  class="center">' . ($sgst_amt) . '</td>';
    }

    $towords = ucwords(convert_number_to_words($grandamt));

    $towords = wordwrap($towords,50,"<br>\n");
    $x = 0;
    $invoiceRow = "";

    $invoiceRow = '<tr>
                    <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;height: 100px;" class="center">1</td>
                    <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;padding-left:10px" >{{DESCRIPTION}}</td>
                    <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{HSN_VAL}}</td>
                    <td  style="border-right:1px solid #000;border-bottom: 1px solid #000;" class="center">{{TAX_VAL}}</td>
                    {{GST_VAL}}
                </tr>';
    


    $hsn = "SAC";

    $message = file_get_contents('../emailtemplates/creditnoteTemplate.html');

    $message = str_replace("{{INVOICE_ROW}}", $invoiceRow, $message);
    

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
    $message = str_replace("{{CREDITNOTENO}}", $creditnote['credit_note_no'], $message);
    $message = str_replace("{{CREDITNOTEDATE}}", date('d M Y', strtotime($creditnote['requested_date'])), $message);
    $message = str_replace("{{INVOICEDATE}}", date('d M Y', strtotime($customers['inv_date'])), $message);
    $message = str_replace("{{INVOICEDUEDATE}}", date('d M Y', strtotime($customers['inv_expiry'])), $message);


    $message = str_replace("{{ALLTOTAL}}", $alltotal, $message);
    $message = str_replace("{{GRANDAMT}}", $grandamt, $message);
    $message = str_replace("{{INWORDAMT}}", $towords, $message);
    $message = str_replace("{{PARTYGSTNO}}", $customers['gst_no'], $message);
    $message = str_replace("{{PARTYPANNO}}", $customers['pan_no'], $message);
    $message = str_replace("{{PARTYSTATE}}", $customers['state'], $message);
    $message = str_replace("{{PARTYSTATECODE}}", $customers['codeid'], $message);

    $message = str_replace("{{GST_HEADING}}", $sgt_heading, $message);
    $message = str_replace("{{GST_SUB_HEAD}}", $gst_sub_head, $message);

    $message = str_replace("{{GST_VAL}}", $gst_cell, $message);

    $message = str_replace("{{GST_TOTAL}}", $gst_total, $message);

    $message = str_replace("{{DESCRIPTION}}", $description, $message);
    $message = str_replace("{{HSN}}", $hsn, $message);
    $message = str_replace("{{HSN_VAL}}", $hsn_code, $message);
    $message = str_replace("{{UNIT}}", '', $message);


    $message = str_replace("{{TAX_VAL}}", $invoice["totalAmount"], $message);
    $message = str_replace("{{TAX_VAL}}", $invoice["totalAmount"], $message);
    echo $message;
    //die();
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