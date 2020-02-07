<?php

// $status   //live =0 & offline=1
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');
require_once 'sales_function.php';

$dm1 = new sales(170, 1274);
$allarea = $dm1->getarea_by_shoptable();
$areacount = count($allarea);

for ($i = 0; $i < $areacount; $i++) {
    echo"<br>------------------<br>";
    $updateseq = $dm1->update_sequence($allarea[$i]['areaid']);
    echo "<pre>";
    print_r($updateseq);
    echo "<br>";
}
?>
