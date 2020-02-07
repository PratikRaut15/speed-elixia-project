<?php
//add array

$customerarr = array(
            'uid'=>'11',
             'name'=>'alok',
             'userkey'=>'1177311285',
             'action'=>"add"
            );
$customerarr1 = array(
            'uid'=>'12',

             'name'=>'Rahul',
             'userkey'=>'1177311285',
             'action'=>"add"
            );
$customerarr2 = array(
            'uid'=>'13',

             'name'=>'Abhishek',
             'userkey'=>'1177311285',
             'action'=>"add"
            );
$customerarr3 = array(
            'uid'=>'14',

             'name'=>'uday',
             'userkey'=>'1177311285',
             'action'=>"add"
            );
$customerarr4 = array(
            'uid'=>'15',

             'name'=>'Nilesh',
             'userkey'=>'1177311285',
             'action'=>"add"
            );



//edit array
/**
$customerarr = array(
            'uid'=>'14',
           'userkey'=>'1271601539',
            'name'=>'sunit',
             'action'=>"edit"
            );
*/
//delete array
/*
$customerarr = array(
            'uid'=>'15',
            'userkey'=>'1271601539',
             'action'=>"delete"
            );
*/
$cust = array($customerarr,$customerarr1,$customerarr2,$customerarr3,$customerarr4);
foreach($cust as $k)
{
   $od = json_encode($k);
   //print_r($od);
   $url = "http://speed.elixiatech.com/pickupapi/pickup.php";
   $ch = curl_init();
    /**
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    * **.
    */
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSLVERSION, 3);
curl_setopt($ch, CURLOPT_POSTFIELDS,"cust=".urlencode($od)."");
$result = curl_exec($ch);
//var_dump($result);
echo "<pre>".$result;
//$final =  json_decode($result);
//echo "<pre>";print_r($final->item_details);
curl_close($ch);
}
?>
