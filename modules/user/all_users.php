<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once "../../lib/bo/UserManager.php";

$class = new UserManager;
$all_users = $class->getValidCustomers();


$i=0;
foreach($all_users as $single){
    $i++;
    echo $single['email'].", ";
    
}


?>