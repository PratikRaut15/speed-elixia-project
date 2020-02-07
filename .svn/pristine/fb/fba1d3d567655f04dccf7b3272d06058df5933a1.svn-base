<?php

include_once("../../lib/system/utilities.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/model/VODevices.php");
include_once("../user/new_alerts_func.php");
include_once "../../lib/bo/TeamManager.php";
$db = new DatabaseManager();
$teamid = GetLoggedInUserId();
$tm = new TeamManager();

$customerid = $_POST['customerid'];


    function get_creditNote($credit_note_id){
        global $db;
        $temp_customer = array();
        $SQL = sprintf("SELECT  
                cn.*
                ,c.customercompany
                ,c.customerno
                ,i.invoiceno
                ,cn.invoiceno as invoiceid
                ,l.ledgername
        FROM " . DB_PARENT . ".credit_note cn
        LEFT OUTER JOIN " . DB_PARENT . ".customer c ON c.customerno = cn.customerno 
        LEFT OUTER JOIN " . DB_PARENT . ".invoice i ON cn.invoiceno = i.invoiceid
        LEFT OUTER JOIN " . DB_PARENT . ".ledger l ON cn.ledgerid = l.ledgerid
        where cn.credit_note_id ='%d'
        ORDER BY cn.credit_note_id DESC  LIMIT 1 ", $credit_note_id);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $customer = new CreditNoteEdit();
            $customer->customerno = $row["customerno"];
            $customer->customercompany = $row['customerno'] . " - " . $row['customercompany'];
            $customer->credit_note_id = $row["credit_note_id"];
            $customer->credit_amount = $row["credit_amount"];
            $customer->reason = $row["reason"];
            $customer->status = $row["status"];
            $customer->requested_date = $row["requested_date"];
            $customer->approved_date = $row["approved_date"];
            $customer->invoiceno = $row["invoiceno"];
            $customer->invoiceid = $row["invoiceid"];
            $customer->ledgerid = $row["ledgerid"];
            $customer->ledgername = $row["ledgername"];
            $customer->invoice_amount = $row["invoice_amount"];
            $customer->invoice_date = $row["invoice_date"];
            $temp_customer[] = $customer;
        }
        return $temp_customer[0];
    }

?>