<?php
//error_reporting(E_ALL);

include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Date.php");

$display = '';

$db = new DatabaseManager();
$SQL = sprintf("SELECT  brs.id
                        ,brs.customerno 
                        ,c.customercompany
                        ,brs.amount 
                        ,brs.status 
                FROM    " . DB_PARENT . ".bank_reconc_stmt brs
                INNER JOIN customer c ON c.customerno = brs.customerno
                WHERE brs.status IN (1,2)");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    $x = 1;
    $deposit_total = 0;
    $deposit_table = "<table>
                        <thead><tr><th>S.No</th><th>Transaction No.</th><th>Customer No.</th><th>Customer Name</th><th>Amount</th><th>Status</th></tr></thead>
                        <tbody>";
    while ($row2 = $db->get_nextRow()) {
        if ($row2['amount'] > 0) {
            $deposit_total+=$row2['amount'];
        }
        if ($row2['id'] < 10) {
            $transno = 'BRS00' . $row2['id'];
        } else {
            $transno = 'BRS0' . $row2['id'];
        }
        $status = ($row2 == 1) ? 'Received' : 'Deposited';
        $deposit_table.="<tr><th>" . $x . "</th><th>" . $transno . "</th><th>" . $row2['customerno'] . "</th><th>" . $row2['customercompany'] . "</th><th>" . $row2['amount'] . "</th><th>" . $status . "</th></tr>";
    }
    $deposit_table.="</tbody></table>";
}

$SQL = sprintf("SELECT  brs.id
                        ,brs.vendorid 
                        ,v.vendorname
                        ,brs.amount 
                        ,brs.status 
                FROM    " . DB_PARENT . ".bank_reconc_stmt brs
                INNER JOIN vendor v ON v.id = brs.vendorid
                WHERE brs.status IN (3,4)");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    $x = 1;
    $withdraw_total = 0;
    $withdraw_table = "<table>
                        <thead><tr><th>S.No</th><th>Transaction No.</th><th>Vendor No.</th><th>Vendor Name</th><th>Amount</th><th>Status</th></tr></thead>
                        <tbody>";
    while ($row2 = $db->get_nextRow()) {
        if ($row2['amount'] > 0) {
            $withdraw_total+=$row2['amount'];
        }
        if ($row2['id'] < 10) {
            $transno = 'BRS00' . $row2['id'];
        } else {
            $transno = 'BRS0' . $row2['id'];
        }
        $status = ($row2 == 3) ? 'Received' : 'Dispatched';
        $withdraw_table.="<tr><th>" . $x . "</th><th>" . $transno . "</th><th>" . $row2['vendorid'] . "</th><th>" . $row2['vendorname'] . "</th><th>" . $row2['amount'] . "</th><th>" . $status . "</th></tr>";
    }
    $withdraw_table.="</tbody></table>";
}

$final_amt = $deposit_total - $withdraw_total;
$display.="<div style='padding-left:150px;margin-top:20px;'>
                <span>Statement Date : ".date('d/m/Y')."</span><br>
                <span>Bank Ending Balance : ".$final_amt."/-</span>
            </div>";
$display.=' <div class="panel" style="margin-top:-50px;">
                <div class="paneltitle" align="center">Less:Outstanding Deposits - ' . $deposit_total . '/-</div> 
                <div class="panelcontents">' . $deposit_table . '</div>
            </div><br><br>';

$display.=' <div class="panel">
                <div class="paneltitle" align="center">Add:Outstanding Withdrawals - ' . $withdraw_total . '/-</div> 
                <div class="panelcontents">' . $withdraw_table . '</div>
            </div><br>';

$display.='<div style="padding-left:150px;margin-top:70px;">
                <span>Bank Ending Balance : '.$final_amt.'/-</span>
            </div>';
include("header.php");
?>
<style>
    table{
        text-align: center;
        border-right:1px solid #ccc;
        border-bottom:1px solid #ccc;
        border-collapse:collapse;
        font-family:Arial;
        font-size: 10pt;
        width: 80%;
        margin: auto;
    }

    td, th{
        border-left:1px solid #ccc;
        border-top:1px solid #ccc;
        padding:5px;
    }
    thead tr{
        background: gray;
        color: white;
    }
    .paneltitle{
        background: lightgray;
        color: black;
    }
    .panel {
        border-radius: 10px;
    }
    .paneltitle{
        border-radius: 10px 10px 0px 0px;
    }
</style>
<div>
    <div style="text-align: center;margin-top: 30px;"><h4>BANK RECONCILIATION STATEMENT</h4></div>
    <?php 
        echo $display; 
        if($final_amt < 0){
            echo '<div style="text-align: center;margin-top: 30px;color:red;"><h3>OUT OF BALANCE</h4></div>';
        }  else {
            echo '<div style="text-align: center;margin-top: 30px;color:green;"><h3>BALANCED</h4></div>';
        }
    ?>
</div>

