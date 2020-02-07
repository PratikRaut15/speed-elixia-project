<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
//include_once("session.php");

include_once("../../lib/system/utilities.php");
include_once("../../lib/bo/CronManager.php");
include_once("../../lib/system/Sanitise.php");

$crmg = new CronManager();

if (isset($_POST['btn_submit'])) {
    $fh = fopen($_FILES['file']['tmp_name'], 'r+');
    $lines = array();
    if ($fh !== FALSE) {
        $x = 1;
        $table = '<table border="1" style="text-align:center;padding:3px;">';
        $table.='<tr><th>Sr No</th><th>Customer No</th><th>Customer Name</th><th>Ledger No</th><th>Ledger Name</th><th>Team Pending Payment</th><th>Tally Pending Payment</th><th>Difference</th></tr>';
        while (($row = fgetcsv($fh, 1000, ",")) !== FALSE) {
            $detail = $crmg->ledgerPendingAmt($row['0']);
            if ($detail != NULL) {
                $diff = $row['1'] - $detail['total'];
                if ($diff !== 0) {
                    $table.='<tr><td>' . $x . '</td><td>' . $detail['customerno'] . '</td><td>' . $detail['customercompany'] . '</td><td>' . $detail['ledgerid'] . '</td><td>' . $detail['ledgername'] . '</td><td>' . $detail['total'] . '</td><td>' . $row['1'] . '</td><td>' . $diff . '</td></tr>';
                    $x++;
                }
            }
        }
    }
}

include("header.php");
?>

<div class="panel">
    <div class="paneltitle" align="center">Payment Reconciliation</div>   
    <div class="panelcontents">
        <form action="tallyCompare.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <table>
                <tr><td>Select File</td><td><input type="file" name="file"></td></tr>
                <tr><td colspan="2" style="text-align: center;"><input type="submit" name="btn_submit" value="SUBMIT" /></td></tr>
            </table>
        </form>
    </div>
</div>

<center><div style="margin-top: 100px;"><?php
        if (isset($table)) {
            echo $table;
        } else {
            if (isset($_POST['btn_submit'])) {
                echo 'Data Not Available';
            }
        }
        ?></div></center>