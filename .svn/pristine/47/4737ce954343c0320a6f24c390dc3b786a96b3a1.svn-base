<?php
include_once '../../lib/autoload.php';
$um = new UserManager();
$users = $um->getusersforcustomerbytype(2, 17, 443);
$useradmin = $um->getadministrator(2);
$users1 = array_merge($users, $useradmin);
$arraylist = json_decode(json_encode($users1), True);
$finalArray = array_unique($arraylist, SORT_REGULAR);
echo "<pre>";
print_r($finalArray);
?>