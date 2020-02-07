<?php

include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
include_once '../../lib/comman_function/reports_func.php';
require_once '../reports/html2pdf.php';
require_once '../../lib/bo/simple_html_dom.php';
include_once("../../lib/system/DatabaseManager.php");

$cronm = new CronManager();
$db = new DatabaseManager();

ob_start();
if(isset($_POST['ledger_pdf_btn'])){

    $l_pdf = new stdClass();
    $l_pdf->fromdate=date("Y-m-d",strtotime($_POST['fromdate']));
    $l_pdf->todate=date("Y-m-d",strtotime($_POST['todate']));
    $l_pdf->ledgerid = $_POST['ledger_name'];
    $today = date("d-m-y");


}
    $ledgerdetails = array();
    $ledgerdetails = $cronm->getLedgerPDF($l_pdf);



    $ledgerDetailsArray = array(); 
    $ledgerDetailsArray_Invoice = array();  
    foreach($ledgerdetails[0] as &$record){
        $record['invoice_amount']=$record['inv_amt'];
        $record['invoice_amount_display']=number_format($record['inv_amt'],2);
        $record['start_date'] = $record['start_date'];
        $record['end_date'] = $record['end_date'];
        $record['quantity'] = $record["quantity"];
        $record['invoice_name'] = $record["invoiceno"];
        $record['gst_no'] = $record["gst_no"];
        $record['invoice_date']=date("d-M-Y",strtotime($record['inv_date']));
        $record['customerno'] = $record["customerno"];
        $record['address'] = $record["address"];
        
        $ledgername = $record['ledgername'];
        $custno = $record['customerno'];
        $gstno = $record['gst_no'];
        $cust_address = $record['address'];
       

        $ledgerDetailsArray_Invoice[]=$record;

    } 


    $ledgerDetailsArray_Payment = array();
    foreach($ledgerdetails[1] as &$record){
        $record['paid_amount']=$record['paid_amt'];
        $record['tds_amount'] = $record['tds_amt'];
        $record['paid_tds_amt'] = $record['total_paid_amt'];
        $record['paid_amount_display']=number_format($record['paid_amt'],2);
        $record['tds_amount_display'] = number_format($record['tds_amt'],2);
        $record['paid_tds_amt_display'] = number_format($record['total_paid_amt'],2);
        $record['payement_mode'] = $record['pay_mode'];
        $record['invoice_name'] = $record["invoiceno"];
        $record['paymentdate'] = date("d-M-Y",strtotime($record['paymentdate']));
        $record['chequeno'] = $record["cheque_no"];

        $ledgername = $record['ledgername'];
        $gstno = $record['gst_no'];
        $cust_address = $record['address'];

        $ledgerDetailsArray_Payment[]=$record;

    }

$opening_balance_details = $cronm->getOpeningBalance($l_pdf);
    foreach($opening_balance_details as $record){
        $opening_balance = round($record['Opening_Balance'],2);
        $opening_balance_1 = number_format(($record['Opening_Balance']),2);
    }

// $SQL =sprintf("SELECT (i.inv_amt - sum(CASE WHEN ipm.`paymentdate`<'%s' THEN ipm.`paid_amt` ELSE 0 END)) as Opening_Balance
// FROM invoice_payment_mapping ipm
// INNER JOIN invoice i on i.invoiceid= ipm.invoiceid 
// WHERE i.ledgerid = '%d'
// AND i.inv_date < '%s'",$l_pdf->fromdate,$l_pdf->ledgerid,$l_pdf->fromdate);


//$db->executeQuery($SQL);


if ($db->get_rowCount() > 0) {
  while ($row = $db->get_nextRow()) {
   $opening_balance = round($row['Opening_Balance'],2);
   $opening_balance_1 = number_format(($row['Opening_Balance']),2);
  }
}

if($opening_balance==''){
    $opening_balance_1 = 0;
}

$fromdate_1 = date("d-M-Y",strtotime($_POST['fromdate']));
$todate_1 = date("d-M-Y",strtotime($_POST['todate']));

$cust_address = wordwrap($cust_address,50,"<br>\n");


$page= '<page>
    <div style="width:750px;">
        <table align="right" style="border:none;">
            <tr>
                <td style="width:328px;height:77px;border:none;text-align:right">
                    <img style="width:100%;height:80%;" src="../../images/logo.png">
                </td>
            </tr>
        </table>
    </div>
        {{CONTENT}}
    <page_footer>
             <div align="right">
            <span style="color:#000">Date of Generation:-  {{TODAY}}</span>
        </div>
        <div align="center"  style="background-color:#0C9BAF;">
            <span style="color:#FFFFFF">Elixia Tech Solutions Ltd.</span>
        </div>
        <div align="center"  style="background-color:#0C9BAF;">
            <span style="color:#FFFFFF">715, Neelkanth Corporate Park, Vidyavihar West, Mumbai - 400086.</span>
        </div>
        <div align="center" style="background-color:#0C9BAF;">
            <span style="color:#FFFFFF">Landline: +91 22 2513 7470/71  |  Email: sales@elixiatech.com  |  Website: www.elixiatech.com</span>
        </div>

    </page_footer>
</page>';
$pageTemplate = $page;
$LedgerTable =
        '<table align="center" style="width:50px;font-size:13px;border:2px solid #000;" cellpadding="0" cellspacing="0">  
         </table>';     

$cust_details='       
        <tr>
            <td colspan="2" style="border-bottom:2px solid #000;">
               <table style="border-collapse: collapse;border: 1px solid black;" class="upper">
                    <tr>
                        <td>TO,</td>
                        <td style="border-right:1px solid #000;"><b>{{CUSTOMERNAME}}</b></td>
                        <td>FROM,</td>
                         <td><b>Elixia Tech Solutions Ltd.</b></td>
                        
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td style="border-right:1px solid #000;"><b>{{CUSTOMER ADDRESS}}</b></td>
                        <td>Address</td>
                        <td><b>715, Neelkanth Corporate Park,<br>
                        Vidyavihar West, Mumbai - 400086.</b></td>
                    </tr>
                    <tr>
                        <td>GST</td>
                        <td style="border-right:1px solid #000;"><b>{{CUSTOMERs GST}}</b></td>
                        <td>GST</td>
                        <td><b>27AACCE7724Q1Z2</b></td>
                    </tr>
                    <tr>
                        <td>Customerno</td>
                        <td style="border-right:1px solid #000;"><b>{{CUSTOMER NO}}</b></td>
                    </tr>
                </table>
            </td>
        </tr>
                 <tr>
           <td style="border-bottom:2px solid #000;">
               <table style="border-collapse: collapse;width: 99%;text-align: center;" class="middle">
                    <tr >
                         <th style="text-align: center;width:100%;height:5%;"> <h4>{{FROM PERIOD}} to {{TO PERIOD}}</h4></th>
                   </tr>
                </table>
            </td>
        </tr>';

$invoices='';
        $invoices.=' <table align="center" style="width:650px;font-size:13px;border:2px solid #000;" cellpadding="0" cellspacing="0">

        <tr>
            <td>
                <table style="border-collapse: collapse;width:100%;" class="upper">
                    <thead>
                        <tr style="border-bottom:1px solid #000;">
                            <th style="border-bottom:2px solid #000;width:10%;">INVOICE DATE</th>
                            <th style="border-bottom:2px solid #000;width:20%;">PARTICULARS</th>
                            <th style="border-bottom:2px solid #000; border-right:1px solid #000;width:10%;text-align:right;">DEBIT AMOUNT</th>
                            <th style="border-bottom:2px solid #000;width:10%;">INVOICE DATE</th>
                            <th style="border-bottom:2px solid #000;width:14%;"></th>
                            <th style="border-bottom:2px solid #000;width:20%;">PARTICULARS</th>
                            <th style="border-bottom:2px solid #000;text-align:right;width:10%;">CREDIT AMOUNT</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            {{INVOICE_FOOTER}}
                        </tr>
                    </tfoot>
                    <tbody>
                            {{INVOICE_DETAILS_CUST}}
                    </tbody>
                </table>
                </td>
            </tr>
        </table>';

$closing_debit = "Closing Balance";
$closing_credit = "Closing Balance";


$length=0; 
if(count($ledgerDetailsArray_Invoice)>count($ledgerDetailsArray_Payment)){
    $length = count($ledgerDetailsArray_Invoice);
    
}
else{
    $length = count($ledgerDetailsArray_Payment);

}

$Array_Ledger = array();
$Array_Ledger_Temp = array();

$i=0;
while($i<$length){
    

    $Array_Ledger_Temp= array();
    if($i<count($ledgerDetailsArray_Invoice)){
        $Array_Ledger_Temp['invoices'] = $ledgerDetailsArray_Invoice[$i];
    }

    if($i<count($ledgerDetailsArray_Payment)){
        $Array_Ledger_Temp['payment'] = $ledgerDetailsArray_Payment[$i];
    }

    $i++;

    $Array_Ledger[] = $Array_Ledger_Temp;
    
}

$total_invoice_amount=0;
$total_payment_amount=0;


if($opening_balance!=0)
{
    $total_invoice_amount = $total_invoice_amount+$opening_balance;
}


$invoice_header ='<tr> 
                    <td><b>'.$fromdate_1.'</b></td>
                    <td>OPENING<br>BALANCE</td>
                    <td style="border-right:1px solid #000;font-size:12px;text-align:right;height:5%;width:8%;">'.$opening_balance_1.'</td>
                </tr>';
$invoice_footer='
            <td>
            </td>
            <td style="text-align:center;">
                <br>
                {{CLOSING_DEBIT}}
             </td>
            <td style="margin-right:10px;text-align: right;border-right: 1px solid #000;width:11%;">
                <br>
                <hr>
                {{TOTAL_INVOICE_AMOUNT}}
                <br>
                {{DUE_PAYMENT_DEBIT}}
                <br>
                <hr>
                {{FINAL_PAYMENT_DEBIT}}
            </td>
            <td>
            </td>
            <td>
            </td>
            <td style="text-align:center;">
                <br>
                {{CLOSING_CREDIT}}
            </td>
            <td style="text-align: right;width:10%;">
                <br>
                <hr>
                {{TOTAL_PAYMENT_AMOUNT}}
                <br>
                {{DUE_PAYMENT_CREDIT}}
                <br>
                <hr>
                {{FINAL_PAYMENT_CREDIT}}
            </td>';
$pages =  '';
$invoiceTemplate='
        <tr>
            <td>
                <table style="border-collapse: collapse;width:100%;" class="upper">
                    <thead>
                        <tr style="border-bottom:1px solid #000;width:100%;">
                            <th style="border-bottom:2px solid #000;width:10%;text-align:center;">DATE</th>
                            <th style="border-bottom:2px solid #000;width:15%;">PARTICULARS</th>
                            <th style="border-bottom:2px solid #000; border-right:1px solid #000;width:10%;text-align:center;">AMOUNT</th>
                            <th style="border-bottom:2px solid #000;width:10%;text-align:center;">DATE</th>
                            <th style="border-bottom:2px solid #000;width:10%;"></th>
                            <th style="border-bottom:2px solid #000;width:15%;">PARTICULARS</th>
                            <th style="border-bottom:2px solid #000;text-align:center;width:11%;">AMOUNT</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            {{INVOICE_FOOTER}}
                        </tr>
                    </tfoot>
                    <tbody>
                            {{INVOICE_HEADER}}
                            {{INVOICE_DETAILS_CUST}}
                    </tbody>
                </table>
                </td>
            </tr>';   
$message= '';
$invoice_details='';
$firstPageLimit = 11;
$rowLimit = $firstPageLimit;
$rowCount = 1;
$LedgerTableTemplate =
    '<table align="center" style="width:750px;font-size:13px;border:2px solid #000;" cellpadding="0" cellspacing="0">
    {{CUST_GENERAL_DETAILS}}  
    {{INVOICE TABLE}}
    </table>';   
$cust_details='     
                <tr>
                    <td colspan="2" style="border-bottom:2px solid #000;width:100%;">
                       <table style="border-collapse: collapse;border: 1px solid black;" class="upper">
                            <tr style="padding-bottom:10px;">
                                <td>TO,</td>
                                <td style="border-right:1px solid #000;height:20px;"><b>{{CUSTOMERNAME}}</b></td>
                                <td>FROM,</td>
                                 <td style="height:20px;"><b>Elixia Tech Solutions Ltd.</b></td>
                                
                            </tr>
                            <tr style="padding-bottom:10px;">
                                <td>Address</td>
                                <td style="border-right:1px solid #000;height:20px;"><address><b>{{CUSTOMER ADDRESS}}</b></address></td>
                                <td>Address</td>
                                <td style="height:20px;padding-left:5px;"><address><b>715, Neelkanth Corporate Park,<br>
                                Vidyavihar West, Mumbai-400 086.</b></address></td>
                            </tr>
                            <tr style="padding-bottom:10px;">
                                <td>GST</td>
                                <td style="border-right:1px solid #000;height:20px;"><b>{{CUSTOMERs GST}}</b></td>
                                <td>GST</td>
                                <td style="height:20px;"><b>27AACCE7724Q1Z2</b></td>
                            </tr>
                            <tr style="padding-bottom:10px;">
                                <td>CustomerNo </td>
                                <td style="border-right:1px solid #000;height:20px;padding-left:5px;"><b> #{{CUSTOMER NO}}</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                   <td style="border-bottom:2px solid #000;">
                       <table style="border-collapse: collapse;width:99%;height:10%;text-align: center;" class="middle">
                            <tr >
                                 <td style="text-align:center;padding:0 250px 0 250px;"><h4>{{FROM PERIOD}} to {{TO PERIOD}}</h4></td>
                           </tr>
                        </table>
                    </td>
                </tr>';  
                // echo "<pre>";

$j=0;                
if(isset($Array_Ledger) && !empty($Array_Ledger))
{   
   foreach($Array_Ledger as $k=>$record)
   {    
            $invoice_details .= "<tr>";
            if(isset($Array_Ledger[$j]['invoices']) && !empty($Array_Ledger[$j]['invoices'])) {


                if($record['invoices']['start_date']=='' || $record['invoices']['end_date']==''){
                    $invoice_details .= '                          
                                    <td style="width:15%;"><b>'.$record['invoices']['invoice_date'].'</b></td>
                                    <td style="width:19%;"><b>'. wordwrap($record['invoices']['invoice_name'],12,"<br>\n").'</b><br/>Qty:'.$record['invoices']['quantity'].'</td>
                                    <td style="border-right:1px solid #000;height:5%;text-align:right;width:10%;padding-right:5px;">'.$record['invoices']['invoice_amount_display'].'</td>';
                }
                else{

                            $subs_start = date("d-m-y",strtotime($record['invoices']['start_date']));  //Subscription Start
                            $subs_end  = date("d-m-y",strtotime($record['invoices']['end_date'])); //Subscription End

                            $invoice_details .= '                           
                                    <td style="width:15%;"><b>'.$record['invoices']['invoice_date'].'</b></td>
                                    <td style="width:19%;"><b>'. wordwrap($record['invoices']['invoice_name'],12,"<br>\n").'</b><br/>Qty:'.$record['invoices']['quantity'].'<br>('.$subs_start.' to '.$subs_end.')</td>
                                    <td style="border-right:1px solid #000;height:5%;text-align:right;width:10%;padding-right:5px;">'.$record['invoices']['invoice_amount_display'].'</td>';
                    }
                 $total_invoice_amount += $record['invoices']['invoice_amount'];
            }
            else{
                    
                     $invoice_details .= '<td style="width:15%;"></td>
                                    <td style="width:20%;"></td>
                                    <td style="border-right:1px solid"></td>';
            }

            if(isset($Array_Ledger[$j]['payment']) && !empty($Array_Ledger[$j]['payment'])){
                    if($record['payment']['paid_amount']>0){
                        if($record['payment']['tds_amount']>0){
                            if($record['payment']['payement_mode']!='Cheque')
                            {
                                $invoice_details .= '
                                                <td style="text-align:left;width:10%;"><b>'.$record['payment']['paymentdate'].'</b></td>
                                                <td style="font-size:10px;text-align:center;width:15%;">Against:- <br/><b>'.$record['payment']['invoice_name'].'</b></td>
                                                <td style="font-size:11px;text-align:left;width:18%;"><b>'.$record['payment']['payement_mode'].':- </b>'.$record['payment']['paid_amount_display'].'<br><b>TDS:- </b>'.$record['payment']['tds_amount_display'].'</td>
                                                <td style="text-align:right;height:5%;width:10%;padding-right:5px;">'.$record['payment']['paid_tds_amt_display']. '</td>
                                                ';
                            }
                            else{                  
                                $invoice_details .=  '
                                                <td style="text-align:left;width:10%;"><b>'.$record['payment']['paymentdate'].'</b></td>
                                                <td style="font-size:10px;height:5%;text-align:center;width:15%;">Against:- <br/><b>'.$record['payment']['invoice_name'].'</b></td>
                                                <td style="font-size:11px;text-align:left;width:18%;"><b>'.$record['payment']['payement_mode'].':- </b>'.$record['payment']['paid_amount_display'].'<br><b>CqNo:- </b>'.$record['payment']['chequeno'].'<br><b>TDS:- </b>'.$record['payment']['tds_amount_display'].'</td>
                                                <td style="text-align:right;height:5%;width:10%;padding-right:5px;">'.$record['payment']['paid_tds_amt_display']. '</td>
                                                ';
                            }
                        }
                        else{
                            
                            if($record['payment']['payement_mode']!='Cheque'){
                                $invoice_details .= '
                                                <td style="text-align:left;width:10%;"><b>'.$record['payment']['paymentdate'].'</b></td>
                                                <td style="font-size:10px;text-align:center;width:15%;">Against:- <br/><b>'.$record['payment']['invoice_name'].'</b></td>
                                                <td style="font-size:11px;text-align:left;width:18%;"><b>'.$record['payment']['pay_mode'].':- </b>'.$record['payment']['paid_amount_display'].'</td>
                                                <td style="text-align:right;height:5%;width:10%;padding-right:5px;">'.$record['payment']['paid_tds_amt_display'].'</td>
                                                ';
                            }
                            else{                  
                                $invoice_details .= '
                                    <td style="text-align:left;width:10%;"><b>'.$record['payment']['paymentdate'].'</b></td>
                                    <td style="font-size:10px;height:5%;text-align:center;width:15%;">Against:- <br/><b>'.$record['payment']['invoice_name'].'</b></td>
                                    <td style="font-size:11px;text-align:left;width:18%;"><b>'.$record['payment']['payement_mode'].':- </b>'.$record['payment']['paid_amount_display'].'<br><b>CqNo:- </b>'.$record['payment']['chequeno'].'</td>
                                    <td style="text-align:right;height:5%;width:5%;padding-right:5px;">'.$record['payment']['paid_tds_amt_display']. '</td>
                                    ';

                            }
                        }
                        
                    }
                  $total_payment_amount +=$record['payment']['paid_tds_amt'];   
            }
            else{
                  $invoice_details .= '<td style="width:10%;"></td>
                                <td style="font-size:11px;height:5%;text-align:center;width:14%;"></td>
                                <td style="font-size:11px;text-align:left;width:20%;"></td>
                                <td style="text-align:right;height:5%;width:10%;"></td>';
                }
                $invoice_details .= "</tr>";

                // print_r($invoice_details);
                // print_r($j);
                $j++;
               
               

                $invoices = $invoiceTemplate;
                $ledgerpage = $LedgerTableTemplate;
                if($rowCount == $rowLimit){
                //page
                    if($rowLimit==$firstPageLimit){
                    //first page
                        if($opening_balance>0){
                            
                            $invoices = str_replace("{{INVOICE_HEADER}}", $invoice_header, $invoices);
                        }
                        $invoices = str_replace("{{INVOICE_HEADER}}","",$invoices);  
                        $cust_details = str_replace("{{CUSTOMERNAME}}", $ledgername, $cust_details);
                        $cust_details = str_replace("{{CUSTOMER ADDRESS}}", $cust_address, $cust_details);
                        $cust_details = str_replace("{{CUSTOMERs GST}}", $gstno, $cust_details);
                        $cust_details = str_replace("{{FROM PERIOD}}", $fromdate_1, $cust_details);
                        $cust_details = str_replace("{{TO PERIOD}}", $todate_1, $cust_details);
                        $cust_details = str_replace("{{CUSTOMER NO}}", $custno, $cust_details);
                        $cust_details = str_replace("{{TODAY}}", $today, $cust_details);
                        $ledgerpage = str_replace("{{CUST_GENERAL_DETAILS}}", $cust_details, $ledgerpage);
                        $rowLimit = 15;
                    }
                    else{                
                            $ledgerpage = str_replace("{{CUST_GENERAL_DETAILS}}", "", $ledgerpage);
                        }
                //middle page
                    $page = '';
                    $invoice='';
                    $rowCount  = 1 ;
                    $invoice = str_replace("{{INVOICE_DETAILS_CUST}}", $invoice_details, $invoices);
                    $ledgerpage = str_replace("{{INVOICE TABLE}}", $invoice, $ledgerpage);
                    $invoice_details='';
                    $page = str_replace("{{CONTENT}}", $ledgerpage, $pageTemplate);
                    $page = str_replace("{{INVOICE_HEADER}}", "", $page);
                    $page = str_replace("{{INVOICE_FOOTER}}", "", $page);
                    $page = str_replace("{{TODAY}}", $today, $page);
                    $pages .= $page;
                }
                
                if(++$k == count($Array_Ledger))
                {
                     //last page
                    if($total_invoice_amount > $total_payment_amount){
                        $due_amount=0;
                        $due_amount=$total_invoice_amount-$total_payment_amount;
                        $final_payment_credit = $total_payment_amount+$due_amount;
                        $final_payment_debit =$total_payment_amount+$due_amount;

                        $due_amount = number_format($due_amount,2);
                        $final_payment_credit = number_format($final_payment_credit,2);
                        $final_payment_debit = number_format($final_payment_debit,2);
                        $total_payment_amount = number_format($total_payment_amount,2);
                        $total_invoice_amount = number_format($total_invoice_amount,2);

                        $invoice_footer = str_replace("{{DUE_PAYMENT_CREDIT}}", $due_amount, $invoice_footer);
                        $invoice_footer = str_replace("{{DUE_PAYMENT_DEBIT}}","", $invoice_footer);
                        $invoice_footer = str_replace("{{FINAL_PAYMENT_CREDIT}}",$final_payment_credit, $invoice_footer);
                        $invoice_footer = str_replace("{{FINAL_PAYMENT_DEBIT}}",$final_payment_debit,$invoice_footer);
                        $invoice_footer = str_replace("{{CLOSING_CREDIT}}",$closing_credit,$invoice_footer);
                        $invoice_footer = str_replace("{{CLOSING_DEBIT}}","",$invoice_footer);

                        $invoice_footer = str_replace("{{TOTAL_INVOICE_AMOUNT}}",$total_invoice_amount,$invoice_footer);
                        $invoice_footer = str_replace("{{TOTAL_PAYMENT_AMOUNT}}",$total_payment_amount,$invoice_footer); 
                    }
                   
                    else if($total_invoice_amount < $total_payment_amount){
                        $due_amount=0;
                        $due_amount=$total_payment_amount-$total_invoice_amount;
                        $final_payment_credit = $total_payment_amount+$due_amount;
                        $final_payment_debit =$total_payment_amount+$due_amount;

                        $due_amount = number_format($due_amount,2);
                        $final_payment_credit = number_format($final_payment_credit,2);
                        $final_payment_debit = number_format($final_payment_debit,2);
                        $total_payment_amount = number_format($total_payment_amount,2);
                        $total_invoice_amount = number_format($total_invoice_amount,2);

                        $invoice_footer = str_replace("{{DUE_PAYMENT_DEBIT}}",$due_amount, $invoice_footer);
                        $invoice_footer = str_replace("{{DUE_PAYMENT_CREDIT}}","", $invoice_footer);
                        $invoice_footer = str_replace("{{FINAL_PAYMENT_CREDIT}}",$final_payment_credit, $invoice_footer);
                        $invoice_footer = str_replace("{{FINAL_PAYMENT_DEBIT}}",$final_payment_debit,$invoice_footer);
                        $invoice_footer = str_replace("{{CLOSING_DEBIT}}",$closing_debit,$invoice_footer);
                        $invoice_footer = str_replace("{{CLOSING_CREDIT}}","",$invoice_footer);

                        $invoice_footer = str_replace("{{TOTAL_INVOICE_AMOUNT}}",$total_invoice_amount,$invoice_footer);
                        $invoice_footer = str_replace("{{TOTAL_PAYMENT_AMOUNT}}",$total_payment_amount,$invoice_footer); 
                    }
                    
                    else{
                        $final_payment_credit = $total_payment_amount;
                        $final_payment_debit =$total_payment_amount;

                        $final_payment_credit = number_format($final_payment_credit,2);
                        $final_payment_debit = number_format($final_payment_debit,2);
                        $total_payment_amount = number_format($total_payment_amount,2);
                        $total_invoice_amount = number_format($total_invoice_amount,2);

                        $invoice_footer = str_replace("{{DUE_PAYMENT_DEBIT}}","", $invoice_footer);
                        $invoice_footer = str_replace("{{DUE_PAYMENT_CREDIT}}","", $invoice_footer);
                        $invoice_footer = str_replace("{{FINAL_PAYMENT_CREDIT}}",$final_payment_credit, $invoice_footer);
                        $invoice_footer = str_replace("{{FINAL_PAYMENT_DEBIT}}",$final_payment_debit,$invoice_footer);
                        $invoice_footer = str_replace("{{CLOSING_DEBIT}}","",$invoice_footer);
                        $invoice_footer = str_replace("{{CLOSING_CREDIT}}","",$invoice_footer);

                        $invoice_footer = str_replace("{{TOTAL_INVOICE_AMOUNT}}",$total_invoice_amount,$invoice_footer);
                        $invoice_footer = str_replace("{{TOTAL_PAYMENT_AMOUNT}}",$total_payment_amount,$invoice_footer); 
                    }
                    //echo $message ;

                    if($rowLimit == $firstPageLimit){
                    //also the first page
                        if($opening_balance>0){
                            $invoices = str_replace("{{INVOICE_HEADER}}", $invoice_header, $invoices);
                            }
                            $cust_details = str_replace("{{CUSTOMERNAME}}", $ledgername, $cust_details);
                            $cust_details = str_replace("{{CUSTOMER ADDRESS}}", $cust_address, $cust_details);
                            $cust_details = str_replace("{{CUSTOMERs GST}}", $gstno, $cust_details);
                            // $cust_details = str_replace("{{LEDGER No.}}", $ledgerno, $cust_details);
                            $cust_details = str_replace("{{FROM PERIOD}}", $fromdate_1, $cust_details);
                            $cust_details = str_replace("{{TO PERIOD}}", $todate_1, $cust_details);
                            $cust_details = str_replace("{{CUSTOMER NO}}", $custno, $cust_details);
                            $cust_details = str_replace("{{TODAY}}", $today, $cust_details);
                            $ledgerpage = str_replace("{{CUST_GENERAL_DETAILS}}", $cust_details, $ledgerpage);
                            $rowLimit = 15;
                    }
                    $page = '';
                    $invoice='';
                    $rowCount  = 1 ;
                    $invoice = str_replace("{{INVOICE_DETAILS_CUST}}", $invoice_details, $invoices);
                    $invoice = str_replace("{{INVOICE_FOOTER}}", $invoice_footer, $invoice);
                    $invoice = str_replace("{{INVOICE_HEADER}}", " ", $invoice);             
                    $ledgerpage = str_replace("{{CUST_GENERAL_DETAILS}}", "", $ledgerpage);
                    $ledgerpage = str_replace("{{INVOICE TABLE}}", $invoice, $ledgerpage);
                    $invoice_details='';
                    $page = str_replace("{{CONTENT}}", $ledgerpage, $pageTemplate);
                    $page = str_replace("{{TODAY}}", $today, $page);
                    $pages .= $page;
                }
                            

    $rowCount++;        
   }
        
}

echo $pages;
//die();
$content = ob_get_clean();
    try {
        $html2pdf = new HTML2PDF('P', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        
        $html2pdf->writeHTML($content);// set auto page breaks
       
        $html2pdf->Output($ledgername."_Ledger.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

?>