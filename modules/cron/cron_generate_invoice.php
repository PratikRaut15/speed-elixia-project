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
$customer_inovoiceOnHold = array();     //Invoice not to be generated for these customers.
$holdObj = new stdClass();
$holdObj->customerNo = 0;
$holdObj->statusId = 1; // Customer on hold.
$customer_inovoiceOnHold = $cronm->getInvoiceHoldStatus($holdObj);
$customer_inovoiceOnHold=implode_all(",",$customer_inovoiceOnHold); //Custom function
$customernos = $cronm->getMonthlySubscCust(1);
$ledgers = $cronm->getLedgerOfCustomer($customernos);

$x = 1;
echo '<b>Monthly :</b><br>';
if (isset($ledgers)) {
    $table = "<table border = 1><th>Sr. No.</th><th>Customerno</th><th>Ledger ID</th><th>Invoice No.</th><th>Start Date</th><th>End Date</th><th>Expiry Date</th><th>No of Devices</th><th>Subscription Price</th><th>Amount</th><th>Tax Amount</th><th>State</th><th></th>";
    foreach ($ledgers as $ledgerid) {
        $details = array();
        $details = $cronm->getInvoiceData($ledgerid);
        $invoiceno = '';
        if ($details != NULL) {
            $datetime = date('Y-m-d H:i:s');
            if($details['currentMonthInv']==0){
                $start_date = date('Y-m-d', strtotime($details['end_date'] . "+1 days")); //Default process
            }
            if($details['currentMonthInv']==1){
                $start_date = date('Y-m-01', strtotime($datetime)); // Custom process 
            }
            $end_date = date("Y-m-t", strtotime($start_date));
            $expiry_date = date('Y-m-d', strtotime('+1 months', strtotime($datetime)));

            $taxname = "4";
            if ($details['state_code'] == 27) {
                $tax_percent = 0.09;
                $tax_cgst = round($details['total'] * $tax_percent);
                $tax_sgst = round($details['total'] * $tax_percent);
                $tax_igst = 0;
                $inv_amt = round($details['total'] + $tax_cgst + $tax_sgst);
                $gst = 'sgst';
            } else {
                $tax_percent = 0.18;
                $tax_cgst = 0;
                $tax_sgst = 0;
                $tax_igst = round($details['total'] * $tax_percent);
                $inv_amt = round($details['total'] + $tax_igst);
                $gst = 'igst';
            }

            if ($details['customerno'] < 10) {
                $invoiceno .= 'ESS' . '0' . $details['customerno'];
            } else {
                $invoiceno .= 'ESS' . $details['customerno'];
            }
            if ($ledgerid < 10) {
                $invoiceno .= '0' . $ledgerid . $details['invoiceid'];
            } else {
                $invoiceno .= $ledgerid . $details['invoiceid'];
            }
            if ($details['renewal'] == -3) {
                $lease = 'Lease';
            } else {
                $lease = '';
            }
            $table.="<tr><td>" . $x . "</td><td>" . $details['customerno'] . "</td><td>" . $ledgerid . "</td><td>" . $invoiceno . "</td><td>" . $start_date . "</td><td>" . $end_date . "</td><td>" . $expiry_date . "</td><td>" . $details['count1'] . "</td><td>" . $details['unit_msp'] . "</td><td>" . $inv_amt . "</td><td>" . ( $tax_cgst + $tax_sgst + $tax_igst ) . "</td><td>" . $details['state'] . "</td><td>" . $lease . "</td></tr>";
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
                                ,`start_date`
                                ,`end_date`
                                ,`inv_expiry`
                                ,`product_id`
                                ,`timestamp`
                                ,`quantity`
                                ,`is_mail_sent`)
                                VALUES('%s',%d,%d,'%s',%d,'pending',%d,'%d',%d,%d,%d,'%s','%s','%s',2,'%s',%d,0);", Sanitise::String($invoiceno)
                    , Sanitise::Long($details['customerno'])
                    , Sanitise::Long($ledgerid)
                    , Sanitise::Date($today)
                    , Sanitise::Long($inv_amt)
                    , Sanitise::Long($inv_amt)
                    , Sanitise::String($taxname)
                    , Sanitise::Long($tax_cgst)
                    , Sanitise::Long($tax_sgst)
                    , Sanitise::Long($tax_igst)
                    , Sanitise::Date($start_date)
                    , Sanitise::Date($end_date)
                    , Sanitise::Date($expiry_date)
                    , Sanitise::DateTime($datetime)
                    , Sanitise::Long($details['count1']));

//            $db->executeQuery($SQL);
            $invoiceid = $db->get_insertedId();
            $devices = implode(',', $details['deviceid']);
            $SQL1 = sprintf("   UPDATE `devices`
                                SET     `start_date` = '%s'
                                        ,`end_date` = '%s'
                                        ,`expirydate` = '%s'
                                        ,`invoiceno` = '%s'
                                WHERE   `deviceid` IN (%s);"
                    , $start_date, $end_date, $expiry_date, $invoiceno, $devices);
//            $db->executeQuery($SQL1);

            $vehicles = $details['vehicleid'];
            foreach ($vehicles as $data) {
                $SQL = sprintf("INSERT INTO `invoice_vehicle_mapping`(`invoiceid`
                                ,`vehicleid`
                                ,`vehicleno`
                                ,`uid`
                                ,`createdon`
                                ,`isdeleted`)
                                VALUES(%d,%d,'%s',%d,'%s',0);", Sanitise::Long($invoiceid)
                        , Sanitise::Long($data['vehicleid'])
                        , Sanitise::String($data['vehicleno'])
                        , Sanitise::Long($data['uid'])
                        , Sanitise::DateTime($datetime));

//                $db->executeQuery($SQL);
            }
        } else {
//            echo "No Invoice Details Found For - " . $ledgerid . "</br>";
        }
    }
    $table.="</table>";
    echo $table;
}

//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Quartely Subscription"> 

$customernos = $cronm->getMonthlySubscCust(3);
$ledgers = $cronm->getLedgerOfCustomer($customernos);
$today = date('Y-m-d');
$x = 1;
echo "<br><br><b>Quarterly :</b><br>";
if (isset($ledgers)) {
    $table = "<table border = 1><th>Sr. No.</th><th>Customerno</th><th>Ledger ID</th><th>Invoice No.</th><th>Start Date</th><th>End Date</th><th>Expiry Date</th><th>No of Devices</th><th>Subscription Price</th><th>Amount</th><th>Tax Amount</th><th>State</th><th></th>";
    foreach ($ledgers as $ledgerid) {

        $details = array();
        $details = $cronm->getInvoiceData($ledgerid);

        $invoiceno = '';
        if ($details != NULL) {
            if ($details['customerno'] < 10) {
                $invoiceno .= 'ESS' . '0' . $details['customerno'];
            } else {
                $invoiceno .= 'ESS' . $details['customerno'];
            }
            if ($ledgerid < 10) {
                $invoiceno .= '0' . $ledgerid . $details['invoiceid'];
            } else {
                $invoiceno .= $ledgerid . $details['invoiceid'];
            }

            $datetime = date('Y-m-d H:i:s');
            $start_date = date('Y-m-d', strtotime($details['end_date'] . "+1 days"));
            $end_date = date("Y-m-t", strtotime('+2 month', strtotime($start_date)));
            $expiry_date = date('Y-m-d', strtotime('+1 months', strtotime($datetime)));
            $taxname = "4";

            if ($details['state_code'] == 27) {
                $tax_percent = 0.09;
                $tax_cgst = round($details['total'] * 3 * $tax_percent);
                $tax_sgst = round($details['total'] * 3 * $tax_percent);
                $tax_igst = 0;
                $inv_amt = round(( $details['total'] * 3 ) + $tax_cgst + $tax_sgst);
                $gst = 'sgst';
            } else {
                $tax_percent = 0.18;
                $tax_cgst = 0;
                $tax_sgst = 0;
                $tax_igst = round($details['total'] * 3 * $tax_percent);
                $inv_amt = round(( $details['total'] * 3 ) + $tax_igst);
                $gst = 'igst';
            }
            if ($details['renewal'] == -3) {
                $lease = 'Lease';
            } else {
                $lease = '';
            }
            $table.="<tr><td>" . $x . "</td><td>" . $details['customerno'] . "</td><td>" . $ledgerid . "</td><td>" . $invoiceno . "</td><td>" . $start_date . "</td><td>" . $end_date . "</td><td>" . $expiry_date . "</td><td>" . $details['count1'] . "</td><td>" . $details['unit_msp'] . "</td><td>" . $inv_amt . "</td><td>" . ( $tax_cgst + $tax_sgst + $tax_igst ) . "</td><td>" . $details['state'] . "</td><td>" . $lease . "</td></tr>";
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
                                ,`start_date`
                                ,`end_date`
                                ,`inv_expiry`
                                ,`product_id`
                                ,`timestamp`
                                ,`quantity`
                                ,`is_mail_sent`)
                                VALUES('%s',%d,%d,'%s',%d,'pending',%d,'%d',%d,%d,%d,'%s','%s','%s',2,'%s',%d,0);", Sanitise::String($invoiceno)
                    , Sanitise::Long($details['customerno'])
                    , Sanitise::Long($ledgerid)
                    , Sanitise::Date($today)
                    , Sanitise::Long($inv_amt)
                    , Sanitise::Long($inv_amt)
                    , Sanitise::String($taxname)
                    , Sanitise::Long($tax_cgst)
                    , Sanitise::Long($tax_sgst)
                    , Sanitise::Long($tax_igst)
                    , Sanitise::Date($start_date)
                    , Sanitise::Date($end_date)
                    , Sanitise::Date($expiry_date)
                    , Sanitise::DateTime($datetime)
                    , Sanitise::Long($details['count1']));

//            $db->executeQuery($SQL);
            $invoiceid = $db->get_insertedId();            
            $devices = implode(',', $details['deviceid']);
            $SQL1 = sprintf("   UPDATE `devices`
                                SET     `start_date` = '%s'
                                        ,`end_date` = '%s'
                                        ,`expirydate` = '%s'
                                        ,`invoiceno` = '%s'
                                WHERE   `deviceid` IN (%s);"
                    , $start_date, $end_date, $expiry_date, $invoiceno, $devices);
//            $db->executeQuery($SQL1);

            $vehicles = $details['vehicleid'];
            foreach ($vehicles as $data) {
                $SQL = sprintf("INSERT INTO `invoice_vehicle_mapping`(`invoiceid`
                                ,`vehicleid`
                                ,`vehicleno`
                                ,`uid`
                                ,`createdon`
                                ,`isdeleted`)
                                VALUES(%d,%d,'%s',%d,'%s',0);", Sanitise::Long($invoiceid)
                        , Sanitise::Long($data['vehicleid'])
                        , Sanitise::String($data['vehicleno'])
                        , Sanitise::Long($data['uid'])
                        , Sanitise::DateTime($datetime));
//                $db->executeQuery($SQL);
            }
        } else {
            echo "No Invoice Details Found For ledger- " . $ledgerid . "</br>";
        }
    }
    $table.="</table>";
    echo $table;
}
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Six Month Subscription">  

$customernos = $cronm->getMonthlySubscCust(6);
$ledgers = $cronm->getLedgerOfCustomer($customernos);
$today = date('Y-m-d');
$x = 1;
echo "<br><br><b>Six Month :</b><br>";
if (isset($ledgers)) {
    $table = "<table border = 1><th>Sr. No.</th><th>Customerno</th><th>Ledger ID</th><th>Invoice No.</th><th>Start Date</th><th>End Date</th><th>Expiry Date</th><th>No of Devices</th><th>Subscription Price</th><th>Amount</th><th>Tax Amount</th><th>State</th><th></th>";
    foreach ($ledgers as $ledgerid) {
        $details = array();
        $details = $cronm->getInvoiceData($ledgerid);
        $invoiceno = '';
        if ($details != NULL) {
            if ($details['customerno'] < 10) {
                $invoiceno .= 'ESS' . '0' . $details['customerno'];
            } else {
                $invoiceno .= 'ESS' . $details['customerno'];
            }
            if ($ledgerid < 10) {
                $invoiceno .= '0' . $ledgerid . $details['invoiceid'];
            } else {
                $invoiceno .= $ledgerid . $details['invoiceid'];
            }

            $datetime = date('Y-m-d H:i:s');
            $start_date = date('Y-m-d', strtotime($details['end_date'] . "+1 days"));
            $end_date = date("Y-m-t", strtotime('+5 month', strtotime($start_date)));
            $expiry_date = date('Y-m-d', strtotime('+1 months', strtotime($datetime)));
            $taxname = "4";

            if ($details['state_code'] == 27) {
                $tax_percent = 0.09;
                $tax_cgst = round($details['total'] * 6 * $tax_percent);
                $tax_sgst = round($details['total'] * 6 * $tax_percent);
                $tax_igst = 0;
                $inv_amt = round(( $details['total'] * 6 ) + $tax_cgst + $tax_sgst);
                $gst = 'sgst';
            } else {
                $tax_percent = 0.18;
                $tax_cgst = 0;
                $tax_sgst = 0;
                $tax_igst = round($details['total'] * 6 * $tax_percent);
                $inv_amt = round(( $details['total'] * 6 ) + $tax_igst);
                $gst = 'igst';
            }
            if ($details['renewal'] == -3) {
                $lease = 'Lease';
            } else {
                $lease = '';
            }
            $table.="<tr><td>" . $x . "</td><td>" . $details['customerno'] . "</td><td>" . $ledgerid . "</td><td>" . $invoiceno . "</td><td>" . $start_date . "</td><td>" . $end_date . "</td><td>" . $expiry_date . "</td><td>" . $details['count1'] . "</td><td>" . $details['unit_msp'] . "</td><td>" . $inv_amt . "</td><td>" . ( $tax_cgst + $tax_sgst + $tax_igst ) . "</td><td>" . $details['state'] . "</td><td>" . $lease . "</td></tr>";
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
                                ,`start_date`
                                ,`end_date`
                                ,`inv_expiry`
                                ,`product_id`
                                ,`timestamp`
                                ,`quantity`
                                ,`is_mail_sent`)
                                VALUES('%s',%d,%d,'%s',%d,'pending',%d,'%d',%d,%d,%d,'%s','%s','%s',2,'%s',%d,0);", Sanitise::String($invoiceno)
                    , Sanitise::Long($details['customerno'])
                    , Sanitise::Long($ledgerid)
                    , Sanitise::Date($today)
                    , Sanitise::Long($inv_amt)
                    , Sanitise::Long($inv_amt)
                    , Sanitise::String($taxname)
                    , Sanitise::Long($tax_cgst)
                    , Sanitise::Long($tax_sgst)
                    , Sanitise::Long($tax_igst)
                    , Sanitise::Date($start_date)
                    , Sanitise::Date($end_date)
                    , Sanitise::Date($expiry_date)
                    , Sanitise::DateTime($datetime)
                    , Sanitise::Long($details['count1']));

//            $db->executeQuery($SQL);
            $invoiceid = $db->get_insertedId();            
            $devices = implode(',', $details['deviceid']);
            $SQL1 = sprintf("   UPDATE `devices`
                                SET     `start_date` = '%s'
                                        ,`end_date` = '%s'
                                        ,`expirydate` = '%s'
                                        ,`invoiceno` = '%s'
                                WHERE   `deviceid` IN (%s);"
                    , $start_date, $end_date, $expiry_date, $invoiceno, $devices);
//            $db->executeQuery($SQL1);

            $vehicles = $details['vehicleid'];
            foreach ($vehicles as $data) {
                $SQL = sprintf("INSERT INTO `invoice_vehicle_mapping`(`invoiceid`
                                ,`vehicleid`
                                ,`vehicleno`
                                ,`uid`
                                ,`createdon`
                                ,`isdeleted`)
                                VALUES(%d,%d,'%s',%d,'%s',0);", Sanitise::Long($invoiceid)
                        , Sanitise::Long($data['vehicleid'])
                        , Sanitise::String($data['vehicleno'])
                        , Sanitise::Long($data['uid'])
                        , Sanitise::DateTime($datetime));

//                $db->executeQuery($SQL);
            }
        } else {
            echo "No Invoice Details Found For ledger- " . $ledgerid . "</br>";
        }
    }
    $table.="</table>";
    echo $table;
}
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Yearly Subscription"> 

$customernos = $cronm->getMonthlySubscCust(12);
$ledgers = $cronm->getLedgerOfCustomer($customernos);
$today = date('Y-m-d');
$x = 1;
echo "<br><br><b>Yearly :</b><br>";
if (isset($ledgers)) {
    $table = "<table border = 1><th>Sr. No.</th><th>Customerno</th><th>Ledger ID</th><th>Invoice No.</th><th>Start Date</th><th>End Date</th><th>Expiry Date</th><th>No of Devices</th><th>Subscription Price</th><th>Amount</th><th>Tax Amount</th><th>State</th><th></th>";
    foreach ($ledgers as $ledgerid) {
        $details = array();
        $details = $cronm->getInvoiceData($ledgerid);

        $invoiceno = '';
        if ($details != NULL) {
            if ($details['customerno'] < 10) {
                $invoiceno .= 'ESS' . '0' . $details['customerno'];
            } else {
                $invoiceno .= 'ESS' . $details['customerno'];
            }
            if ($ledgerid < 10) {
                $invoiceno .= '0' . $ledgerid . $details['invoiceid'];
            } else {
                $invoiceno .= $ledgerid . $details['invoiceid'];
            }

            $datetime = date('Y-m-d H:i:s');
            $start_date = date('Y-m-d', strtotime($details['end_date'] . "+1 days"));
            $end_date = date("Y-m-t", strtotime('+11 month', strtotime($start_date)));
            $expiry_date = date('Y-m-d', strtotime('+1 months', strtotime($datetime)));
            $taxname = "4";
            if ($details['state_code'] == 27) {
                $tax_percent = 0.09;
                $tax_cgst = round($details['total'] * 12 * $tax_percent);
                $tax_sgst = round($details['total'] * 12 * $tax_percent);
                $tax_igst = 0;
                $inv_amt = round(( $details['total'] * 12 ) + $tax_cgst + $tax_sgst);
                $gst = 'sgst';
            } else {
                $tax_percent = 0.18;
                $tax_cgst = 0;
                $tax_sgst = 0;
                $tax_igst = round($details['total'] * 12 * $tax_percent);
                $inv_amt = round(( $details['total'] * 12 ) + $tax_igst);
                $gst = 'igst';
            }
            if ($details['renewal'] == -3) {
                $lease = 'Lease';
            } else {
                $lease = '';
            }
            $table.="<tr><td>" . $x . "</td><td>" . $details['customerno'] . "</td><td>" . $ledgerid . "</td><td>" . $invoiceno . "</td><td>" . $start_date . "</td><td>" . $end_date . "</td><td>" . $expiry_date . "</td><td>" . $details['count1'] . "</td><td>" . $details['unit_msp'] . "</td><td>" . $inv_amt . "</td><td>" . ( $tax_cgst + $tax_sgst + $tax_igst ) . "</td><td>" . $details['state'] . "</td><td>" . $lease . "</td></tr>";
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
                                ,`start_date`
                                ,`end_date`
                                ,`inv_expiry`
                                ,`product_id`
                                ,`timestamp`
                                ,`quantity`
                                ,`is_mail_sent`)
                                VALUES('%s',%d,%d,'%s',%d,'pending',%d,'%d',%d,%d,%d,'%s','%s','%s',2,'%s',%d,0);", Sanitise::String($invoiceno)
                    , Sanitise::Long($details['customerno'])
                    , Sanitise::Long($ledgerid)
                    , Sanitise::Date($today)
                    , Sanitise::Long($inv_amt)
                    , Sanitise::Long($inv_amt)
                    , Sanitise::String($taxname)
                    , Sanitise::Long($tax_cgst)
                    , Sanitise::Long($tax_sgst)
                    , Sanitise::Long($tax_igst)
                    , Sanitise::Date($start_date)
                    , Sanitise::Date($end_date)
                    , Sanitise::Date($expiry_date)
                    , Sanitise::DateTime($datetime)
                    , Sanitise::Long($details['count1']));

//            $db->executeQuery($SQL);
            $invoiceid = $db->get_insertedId();                        
            $devices = implode(',', $details['deviceid']);
            $SQL1 = sprintf("   UPDATE `devices`
                                SET     `start_date` = '%s'
                                        ,`end_date` = '%s'
                                        ,`expirydate` = '%s'
                                        ,`invoiceno` = '%s'
                                WHERE   `deviceid` IN (%s);"
                    , $start_date, $end_date, $expiry_date, $invoiceno, $devices);
//            $db->executeQuery($SQL1);

            $vehicles = $details['vehicleid'];
            foreach ($vehicles as $data) {
                $SQL = sprintf("INSERT INTO `invoice_vehicle_mapping`(`invoiceid`
                                ,`vehicleid`
                                ,`vehicleno`
                                ,`uid`
                                ,`createdon`
                                ,`isdeleted`)
                                VALUES(%d,%d,'%s',%d,'%s',0);", Sanitise::Long($invoiceid)
                        , Sanitise::Long($data['vehicleid'])
                        , Sanitise::String($data['vehicleno'])
                        , Sanitise::Long($data['uid'])
                        , Sanitise::DateTime($datetime));

//                $db->executeQuery($SQL);
            }
        } else {
            echo "No Invoice Details Found For ledger- " . $ledgerid . "</br>";
        }
    }
    $table.="</table>";
    echo $table;
}
//</editor-fold>
//$SQL = sprintf("UPDATE  `proforma_invoice`
//                SET     `is_taxed` = 1
//                WHERE   `customerno` IN (69,71,73,81,395,399,411,458)
//                AND     inv_date LIKE '%s'", Sanitise::Date($today));
//$db->executeQuery($SQL);
//
//$SQL = sprintf("SELECT  invoiceid
//                FROM    `invoice`
//                ORDER BY invoiceid DESC
//                LIMIT   1");
//$db->executeQuery($SQL);
//if ($db->get_rowCount() > 0) {
//    while ($row = $db->get_nextRow()) {
//        $lastid = $row['invoiceid'];
//    }
//}
//
//$SQL = sprintf("SELECT  *
//                FROM    `proforma_invoice`
//                WHERE   `is_taxed` = 1
//                AND     `is_processed` = 0");
//$db->executeQuery($SQL);
//$resultSet = $db->get_recordSet();
//if (isset($resultSet) && count($resultSet) > 0) {
//
//    foreach ($resultSet as $row) {
//        $lastid = $lastid + 1;
//        $invoiceno1 = 'ES';
//        if ($row['customerno'] < 10) {
//            $invoiceno1 .= '0' . $row['customerno'];
//        } else {
//            $invoiceno1 .= $row['customerno'];
//        }
//        if ($row['ledgerid'] < 10) {
//            $invoiceno1 .= '0' . $row['ledgerid'] . $lastid;
//        } else {
//            $invoiceno1 .= $row['ledgerid'] . $lastid;
//        }
//
//        echo $QUERY = sprintf("  INSERT INTO invoice( `invoiceno`
//                                                ,`inv_amt`
//                                                ,`inv_date`
//                                                ,`customerno`
//                                                ,`ledgerid`
//                                                ,`status`
//                                                ,`pending_amt`
//                                                ,`tax`
//                                                ,`tax_amt`
//                                                ,`paid_amt`
//                                                ,`inv_expiry`
//                                                ,`product_id`
//                                                ,`start_date`
//                                                ,`end_date`)
//                                VALUES('%s',%d,'%s',%d,%d,'%s',%d,'%s',%d,0,'%s',%d,'%s','%s')"
//            , Sanitise::String($invoiceno1)
//            , Sanitise::Long($row['inv_amt'])
//            , Sanitise::Date($row['inv_date'])
//            , Sanitise::Long($row['customerno'])
//            , Sanitise::Long($row['ledgerid'])
//            , Sanitise::String('pending')
//            , Sanitise::Long($row['inv_amt'])
//            , Sanitise::String($row['tax'])
//            , Sanitise::Long($row['tax_amt'])
//            , Sanitise::Date($row['payment_due_date'])
//            , Sanitise::Long($row['product_id'])
//            , Sanitise::Date($row['start_date'])
//            , Sanitise::Date($row['end_date']));
//        $db->executeQuery($QUERY);
//        echo "<br/>";
//    }
//}
?>
