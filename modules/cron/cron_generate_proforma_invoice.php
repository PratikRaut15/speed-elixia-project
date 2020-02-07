<?php

require_once '../../lib/autoload.php';
include_once '../../lib/system/utilities.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$cronm = new CronManager();
$db = new DatabaseManager();
$customernos = $cronm->getMonthlySubscCust();
$ledgers = $cronm->getLedgerOfCustomer($customernos);
$today = date('Y-m-d');

if (isset($ledgers)) {
    foreach ($ledgers as $ledgerid) {
        $details = array();
        $details = $cronm->getProformaData($ledgerid);
        $invoiceno = '';
        if ($details != NULL) {
            if ($details['customerno'] < 10) {
                $invoiceno .= 'PRO' . '0' . $details['customerno'];
            } else {
                $invoiceno .= 'PRO' . $details['customerno'];
            }
            if ($ledgerid < 10) {
                $invoiceno .= '0' . $ledgerid . $details['invoiceid'];
            } else {
                $invoiceno .= $ledgerid . $details['invoiceid'];
            }

            $datetime = date('Y-m-d H:i:s');
            $start_date = date('Y-m-d', strtotime($details['end_date'] . "+1 days"));
            $end_date = date("Y-m-t", strtotime($start_date));
            $expiry_date = date("Y-m-t", strtotime($today));
            $taxname = "ST";
            $tax_percent = 0.15;
            $tax = $details['total'] * $tax_percent;
            echo $SQL = sprintf("INSERT INTO `proforma_invoice`(`invoiceno`
                                    ,`customerno`
                                    ,`ledgerid`
                                    ,`inv_date`
                                    ,`inv_amt`
                                    ,`status`
                                    ,`pending_amt`
                                    ,`tax`
                                    ,`tax_amt`
                                    ,`start_date`
                                    ,`end_date`
                                    ,`payment_due_date`
                                    ,`product_id`
                                    ,`timestamp`
                                    ,`quantity`
                                    ,`is_taxed`
                                    ,`is_processed`) 
                            VALUES('%s',%d,%d,'%s',%d,'pending',%d,'%s',%d,'%s','%s','%s',2,'%s',%d,0,0);", Sanitise::String($invoiceno)
                    , Sanitise::Long($details['customerno'])
                    , Sanitise::Long($ledgerid)
                    , Sanitise::Date($today)
                    , Sanitise::Long($details['total'])
                    , Sanitise::Long($details['total'])
                    , Sanitise::String($taxname)
                    , Sanitise::Long($tax)
                    , Sanitise::Date($start_date)
                    , Sanitise::Date($end_date)
                    , Sanitise::Date($expiry_date)
                    , Sanitise::DateTime($datetime)
                    , Sanitise::Long($details['count1']));
            
            $db->executeQuery($SQL);
            echo "<br/>";
            $devices = implode(',', $details['deviceid']);
            $SQL1 = sprintf("UPDATE `devices`
                            SET     `start_date` = '%s'
                                    ,`end_date` = '%s'
                            WHERE   `deviceid` IN (%s);"
                , $start_date, $end_date, $devices);
            $db->executeQuery($SQL1);
        } else {
            echo "No Proforma Details Found For - " . $ledgerid . "<br/>";
        }
    }
}

$SQL = sprintf("UPDATE  `proforma_invoice`
                SET     `is_taxed` = 1
                WHERE   `customerno` IN (69,71,73,81,395,399,411,458)
                AND     inv_date LIKE '%s'", Sanitise::Date($today));
$db->executeQuery($SQL);

$SQL = sprintf("SELECT  invoiceid
                FROM    `invoice`
                ORDER BY invoiceid DESC
                LIMIT   1");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $lastid = $row['invoiceid'];
    }
}

$SQL = sprintf("SELECT  *
                FROM    `proforma_invoice`
                WHERE   `is_taxed` = 1
                AND     `is_processed` = 0");
$db->executeQuery($SQL);
$resultSet = $db->get_recordSet();
if (isset($resultSet) && count($resultSet) > 0) {

    foreach ($resultSet as $row) {
        $lastid = $lastid + 1;
        $invoiceno1 = 'ES';
        if ($row['customerno'] < 10) {
            $invoiceno1 .= '0' . $row['customerno'];
        } else {
            $invoiceno1 .= $row['customerno'];
        }
        if ($row['ledgerid'] < 10) {
            $invoiceno1 .= '0' . $row['ledgerid'] . $lastid;
        } else {
            $invoiceno1 .= $row['ledgerid'] . $lastid;
        }

        echo $QUERY = sprintf("  INSERT INTO invoice( `invoiceno`
                                                ,`inv_amt`
                                                ,`inv_date`
                                                ,`customerno`
                                                ,`ledgerid`
                                                ,`status`
                                                ,`pending_amt`
                                                ,`tax`
                                                ,`tax_amt`
                                                ,`paid_amt`
                                                ,`inv_expiry`
                                                ,`product_id`
                                                ,`start_date`
                                                ,`end_date`)
                                VALUES('%s',%d,'%s',%d,%d,'%s',%d,'%s',%d,0,'%s',%d,'%s','%s')"
            , Sanitise::String($invoiceno1)
            , Sanitise::Long($row['inv_amt'])
            , Sanitise::Date($row['inv_date'])
            , Sanitise::Long($row['customerno'])
            , Sanitise::Long($row['ledgerid'])
            , Sanitise::String('pending')
            , Sanitise::Long($row['inv_amt'])
            , Sanitise::String($row['tax'])
            , Sanitise::Long($row['tax_amt'])
            , Sanitise::Date($row['payment_due_date'])
            , Sanitise::Long($row['product_id'])
            , Sanitise::Date($row['start_date'])
            , Sanitise::Date($row['end_date']));
        $db->executeQuery($QUERY);
        echo "<br/>";
    }
}
?>
