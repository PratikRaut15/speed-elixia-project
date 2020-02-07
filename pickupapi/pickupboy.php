<?php
//add array
$pcbarr = array(
            'pincode'=>'400604',
            'userid'=>'1',
            'action'=>"add"
            );

$pcbarr1 = array(
            'pincode'=>'400075',
            'userid'=>'2',
            'action'=>"add"
            );

$pcbarr2 = array(
            'pincode'=>'400608',
            'userid'=>'3',
            'action'=>"add"
            );

$pcbarr3 = array(
            'pincode'=>'400607',
            'userid'=>'4',
            'action'=>"add"
            );

$pcbarr4 = array(
            'pincode'=>'400606',
            'userid'=>'5',
            'action'=>"add"
            );

//edit array
//$pcbarr = array(
//            'uid'=>'3', 
//            'pincode'=>'400075',
//            'userid'=>'1',
//            'action'=>"edit"
//            );

////delete array
//$pcbarr = array(
//            'userid'=>'1',
//            'action'=>"delete"
//            );

$pcb = array($pcbarr,$pcbarr1,$pcbarr2,$pcbarr3,$pcbarr4);
foreach($pcb as $k)
{
   $pbd = json_encode($k);
   //print_r($od);
   $url = "http://localhost/elixiaspeed/pickupapi/pickup.php";
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
curl_setopt($ch, CURLOPT_POSTFIELDS,"pcb=".urlencode($pbd)."");
$result = curl_exec($ch);
//var_dump($result);
echo "<pre>".$result;
//$final =  json_decode($result);
//echo "<pre>";print_r($final->item_details);
curl_close($ch);   
}
?>
