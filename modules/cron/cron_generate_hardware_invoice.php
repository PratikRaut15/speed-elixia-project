<?php

require_once '../../lib/autoload.php';
include_once '../../lib/system/utilities.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$cronm = new CronManager();
$db = new DatabaseManager();
$today = date('Y-m-d');

//<editor-fold defaultstate="collapsed" desc="Monthly Subscription"> 
date_default_timezone_set("Asia/Calcutta");
$ledgers = $cronm->getAllLedger();       //List of customer whose 
                                         //device_invoiceno is null
 
$x = 1;
//echo '<b>Hardware Invoices:</b><br>';
if (isset($ledgers)) 
{
    $table = "<table border = 1><th>Sr. No.</th><th>Customerno</th><th>Ledger ID</th><th>Invoice No.</th><th>Expiry Date</th><th>No of Devices</th><th>Unit Price</th><th>Amount</th><th>Tax Amount</th><th>State</th><th></th>";
    foreach ($ledgers as $ledgerid) 
    {
        $details = array();
        $details = $cronm->getCustomerHardwareInvoice($ledgerid);
        
        $invoiceno = '';
        if ($details != NULL) 
        {
            $datetime = date('Y-m-d H:i:s');
            $expiry_date = date('d-m-Y', strtotime('+30 days', strtotime($datetime)));

            $taxname = "4";
            if ($details['state_code'] == 27) 
            {
                $tax_percent = 0.09;
                $tax_cgst = round($details['total'] * $tax_percent);
                $tax_sgst = round($details['total'] * $tax_percent);
                $tax_igst = 0;
                $inv_amt = round($details['total'] + $tax_cgst + $tax_sgst);
                $gst = 'sgst';
            } 
            else 
            {
                $tax_percent = 0.18;
                $tax_cgst = 0;
                $tax_sgst = 0;
                $tax_igst = round($details['total'] * $tax_percent);
                $inv_amt = round($details['total'] + $tax_igst);
                $gst = 'igst';
            }

            if ($details['customerno'] < 10) 
                {
                    $invoiceno .= 'ESG' . '0' . $details['customerno'];
                }
            else 
                {
                    $invoiceno .= 'ESG' . $details['customerno'];
                }
            if ($ledgerid < 10)
                {
                    $invoiceno .= '0' . $ledgerid . $details['invoiceid'];
                } 
            else 
                {
                    $invoiceno .= $ledgerid . $details['invoiceid'];
                }
            $table.="<tr><td>" . $x . "</td><td>" . $details['customerno'] . "</td><td>" . $ledgerid . "</td><td>" . $invoiceno . "</td><td>" . $expiry_date . "</td><td>" . $details['count1'] . "</td><td>" . $details['unitprice'] . "</td><td>" . $inv_amt . "</td><td>" . ( $tax_cgst + $tax_sgst + $tax_igst ) . "</td><td>" . $details['state'] . "</td></tr>" ;
            $x++;
         $SQL = sprintf("INSERT INTO `invoice`(`invoiceno`
                                ,`customerno`
                                ,`ledgerid`
                                ,`inv_date`
                                ,`inv_amt`
                                ,`status`
                                ,`pending_amt`
                                ,`tax`
                                ,`cgst`
                                ,`sgst`
                                ,`igst`
                                ,`inv_expiry`
                                ,`product_id`
                                ,`timestamp`
                                ,`quantity`
                                ,`is_mail_sent`)
                                VALUES('%s',%d,%d,'%s',%d,'pending',%d,'%d',%d,%d,%d,'%s',1,'%s',%d,0);", Sanitise::String($invoiceno)
                    , Sanitise::Long($details['customerno'])
                    , Sanitise::Long($ledgerid)
                    , Sanitise::Date($today)
                    , Sanitise::Long($inv_amt)
                    , Sanitise::Long($inv_amt)
                    , Sanitise::String($taxname)
                    , Sanitise::Long($tax_cgst)
                    , Sanitise::Long($tax_sgst)
                    , Sanitise::Long($tax_igst)
                    , Sanitise::Date($expiry_date)
                    , Sanitise::DateTime($datetime)
                    , Sanitise::Long($details['count1']));

            //$db->executeQuery($SQL);
            $invoiceid = $db->get_insertedId();

            $devices = implode(',', $details['deviceid']);
            $SQL1 = sprintf("   UPDATE  `devices`
                                SET     `device_invoiceno` = '%s',
                                        `expirydate`='%s'
                                WHERE   `deviceid` IN (%s);"
                                , $invoiceno,$expirydate,$devices);
           //$db->executeQuery($SQL1);

        }
         
        else 
            {
                                   //echo "No Invoice Details Found For - " . $ledgerid . "</br>";
            }
    }
    $table.="</table>";
    echo $table;
}


?>
