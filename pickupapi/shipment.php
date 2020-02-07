<?php
//add array

$shiparr = array(
            'customerid'=>'11',
            'vendorid'=>'1',
            'shipmentno'=>'21',
    'userkey'=>'1271601539',
            'action'=>"add"
            );

$shiparr1 = array(
            'customerid'=>'12',
            'vendorid'=>'2',
            'shipmentno'=>'22',
    'userkey'=>'1271601539',
            'action'=>"add"
            );

$shiparr2 = array(
            'customerid'=>'13',
            'vendorid'=>'3',
            'shipmentno'=>'23',
    'userkey'=>'1271601539',
            'action'=>"add"
            );
$shiparr3 = array(
            'customerid'=>'14',
            'vendorid'=>'4',
            'shipmentno'=>'24',
    'userkey'=>'1271601539',
            'action'=>"add"
            );

$shiparr4 = array(
            'customerid'=>'15',
            'vendorid'=>'5',
            'shipmentno'=>'25',
    'userkey'=>'1271601539',
            'action'=>"add"
            );



//edit array
/**
$shiparr = array(
            'customerid'=>'12',
           'vendorid'=>'2',
           'shipmentno'=>'22',
           'pickupid'=>'4',
 * 'userkey'=>'1271601539',
           'action'=>"edit"
            );
*/

//delete array
/*
$shiparr = array(
            'shipmentno'=>'22',
 * 'userkey'=>'1271601539',
            'action'=>"delete"
            );
*/
$ship = array($shiparr,$shiparr1,$shiparr2,$shiparr3,$shiparr4);
foreach($ship as $k)
{
   echo $shipd = json_encode($k);
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
curl_setopt($ch, CURLOPT_POSTFIELDS,"ship=".urlencode($shipd)."");
$result = curl_exec($ch);
//var_dump($result);
echo "<pre>".$result;
//$final =  json_decode($result);
//echo "<pre>";print_r($final->item_details);
curl_close($ch);
}
?>
