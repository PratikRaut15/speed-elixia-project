<?php include 'route_functions.php'; ?>
<?php
$chkid = $_POST['chkpt'];
$routeid = $_POST['routeid'];
$customerno = $_POST['customerno'];
$rm = new RouteManager($customerno);
$del = $rm->deleteRouteChk($chkid, $routeid);
echo $del;
     
?>