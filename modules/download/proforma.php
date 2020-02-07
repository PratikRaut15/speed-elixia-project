<?php

include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
include_once '../../lib/comman_function/reports_func.php';
require_once '../reports/html2pdf.php';
require_once '../../lib/bo/simple_html_dom.php';

$cronm = new CronManager();
$customers = $cronm->getProformaInvoice($_GET['pid']);
$VehType = $cronm->getLedgerVehicle($customers['ledgerid']);
if ($customers['is_taxed'] == 0) {
    $type = 'PROFORMA';
} else {
    $type = 'TAXED';
}

$price = $customers['unit_msp'];
$quantity = $customers['quantity'];
$tax_type = $customers['tax'];

$stotal = $quantity * $price;
$astotal = 0;
$astotal+= $stotal;

$alltotal = 0;
$tamt = 0;
isset($tamt) ? $tamt : 0;
$alltotal = $tamt + $astotal;
$tax_percent = 0.14;
$vat = $alltotal * $tax_percent;
$swachhtax = $alltotal * 0.005;
$kktax = $alltotal * 0.005;
isset($swachhtax) ? $swachhtax : 0;
isset($kktax) ? $kktax : 0;
$grandamt = round($alltotal + $vat + $swachhtax + $kktax);
$towords = ucwords(convert_number_to_words($grandamt));
$page_vehcount = 55;
$vcount = count($VehType);
$pages = ceil($vcount / $page_vehcount);
$tempArray = array();
$tempArray = $VehType;
$x = 0;
$vehTable = '';
for ($i = 0; $i < $pages; $i++) {
    $start_count = $i * $page_vehcount;
    $VehType = array_slice($VehType, $start_count, $page_vehcount);
    $vehTable.='<page>
                        <div style="text-align:center;">
                            <span style="font-weight: bold">Invoice Details</span>
                        </div>';

    if (!empty($VehType)) {
        $vehTable.='<table style="font-size:17px;width:1250px;border:2px solid #000;text-align: center;" cellpadding="0" cellspacing="0">
                           <tr>
                           <th style="width:10%;"> Sr.no </th>
                           <th style="width:50%;">Vehicle No</th>
                           </tr>';

        foreach ($VehType as $vehs) {
            $x++;
            $vehTable.=' <tr>
                                <td style="width:10%;">' .
                    $x . '</td>
                                    <td style="width:50%;">' .
                    $vehs . '
                                    </td>
                                </tr>';
        }

        $vehTable.='  </table>';
    }

    $VehType = $tempArray;
    $vehTable.='</page>';
}

$message = file_get_contents('../emailtemplates/invoiceTemplate.html');
$message = str_replace("{{INVOICETYPE}}", $type, $message);
$message = str_replace("{{LEDGERNAME}}", $customers['ledgername'], $message);
$message = str_replace("{{ADDRESS1}}", $customers['address1'], $message);
$message = str_replace("{{ADDRESS2}}", $customers['address2'], $message);
$message = str_replace("{{ADDRESS3}}", $customers['address3'], $message);
$message = str_replace("{{INVOICENO}}", $customers['invoiceno'], $message);
$message = str_replace("{{INVOICEDATE}}", date('d M Y', strtotime($customers['inv_date'])), $message);
$message = str_replace("{{INVOICEDUEDATE}}", date('d M Y', strtotime($customers['payment_due_date'])), $message);
$message = str_replace("{{STARTDATE}}", date('d M Y', strtotime($customers['start_date'])), $message);
$message = str_replace("{{ENDDATE}}", date('d M Y', strtotime($customers['end_date'])), $message);
$message = str_replace("{{QUANTITY}}", $quantity, $message);
$message = str_replace("{{PRICE}}", $price, $message);
$message = str_replace("{{STOTAL}}", $stotal, $message);
$message = str_replace("{{ALLTOTAL}}", $alltotal, $message);
$message = str_replace("{{VAT}}", $vat, $message);
$message = str_replace("{{SWACHH}}", $swachhtax, $message);
$message = str_replace("{{KRISHIK}}", $kktax, $message);
$message = str_replace("{{GRANDAMT}}", $grandamt, $message);
$message = str_replace("{{INWORDAMT}}", $towords, $message);
$message = str_replace("{{PARTYSTNO}}", $customers['st_no'], $message);
$message = str_replace("{{PARTYVATNO}}", $customers['vat_no'], $message);
$message = str_replace("{{PARTYCSTNO}}", $customers['cst_no'], $message);
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
            $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
        } else
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

?>