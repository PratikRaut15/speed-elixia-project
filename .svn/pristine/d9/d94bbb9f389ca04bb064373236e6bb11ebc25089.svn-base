<?php include 'delivery_functions.php'; ?>
<?php
$status = $_GET['q'];
$id = $_GET['id'];
$customerno = $_GET['cno'];
$pdate = $_GET['pdate'];
$stime = $_GET['stime'];
$dm = new DeliveryManager($customerno);

if(isset($_GET['route']))
{
    $dm->addRoute($status, $_GET['status'], $id, $pdate, $stime);
    
}
else
{
    $dm->addhistory($status, $id);
}
?>