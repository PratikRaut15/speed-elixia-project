<?php

$ordersarr = array(
             'billno' => 'RT8748',
             'slot' => "1",
             'area'=>'Vidyavihar',
             'landmark'=>"Vidyavihar Railway Station",
             'address'=>"725, Neelkanth Business Park, Vidyavihar West, Mumbai 86",
             'userkey'=>983560282,
             'pincode'=>"400086"
            );


$odr = array($ordersarr);


foreach($odr as $k)
{
   $od = json_encode($k);
//print_r($od);
    $url = "http://www.speed.elixiatech.com/deliveryapi/delivery.php";

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
curl_setopt($ch, CURLOPT_POSTFIELDS,"bill=".  urlencode($od)."");
$result = curl_exec($ch);
//var_dump($result);
echo "<pre>".$result;
//$final =  json_decode($result);
//echo "<pre>";print_r($final->item_details);
curl_close($ch);


}

?>
