<?php

include_once "session.php";
include "loginorelse.php";
include_once "db.php";
include_once "../../constants/constants.php";
include_once "../../lib/components/gui/objectdatagrid.php";
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
include "header.php";
$db = new DatabaseManager();
$cronm = new CronManager();

$customers = $cronm->getAllCustomer();

?>
<style>
    table{
        font-size: 12px;
    }
    td{
        text-align: center;
    }
</style>

<?php
echo '<center>';
echo '<span><h3>Credit Day Report</h3></span><br>';
echo '<table border="1"><tr><th>Sr No</th><th>Customer No</th><th>Ledger No</th><th>Ledger Name</th><th>Average Credit Days</th></tr>';
$x=1;
foreach ($customers as $data) {
    $diff = $cronm->getAvgCreditDays($data['ledgerid']);
    echo '<tr><td>'.$x.'</td><td>'.$data['customerno'].'</td><td>'.$data['ledgerid'].'</td><td>'.$data['ledgername'].'</td><td>'.$diff.'</td></tr>';
    $x++;
}
echo '</table></center>';

include 'footer.php';
?>

