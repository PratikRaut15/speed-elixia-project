<?php include 'assign_function.php'; ?>
<?php
$status = $_GET['q'];
$id = $_GET['id'];
$customerno = $_GET['cno'];
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
