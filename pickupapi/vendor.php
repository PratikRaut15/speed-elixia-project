<?php
//add array

$vendorarr = array(
            'vendorid'=>'1',
            'name' => 'abc',
             'address'=>'ghatkopar',
             'pincode'=>'400075',
             'userkey'=>'1271601539',
             'action'=>"add"
            );

$vendorarr1 = array(
            'vendorid'=>'2',
            'name' => 'def',
             'address'=>'mulund',
             'pincode'=>'400604',
             'userkey'=>'1271601539',
             'action'=>"add"
            );
$vendorarr2 = array(
            'vendorid'=>'3',
            'name' => 'ghi',
             'address'=>'thane',
             'pincode'=>'400606',
             'userkey'=>'1271601539',
             'action'=>"add"
            );
$vendorarr3 = array(
            'vendorid'=>'4',
            'name' => 'jkl',
             'address'=>'vashi',
             'pincode'=>'400607',
             'userkey'=>'1271601539',
             'action'=>"add"
            );
$vendorarr4 = array(
            'vendorid'=>'5',
            'name' => 'mno',
             'address'=>'thane',
             'pincode'=>'400608',
             'userkey'=>'1271601539',
             'action'=>"add"
            );


//edit array
/**
$vendorarr = array(
            'vendorid'=>'1',
            'name' => 'Abhishek Patil',
             'address'=>'Ghatkopar',
             'pincode'=>'400075',
             'userkey'=>'1271601539',
            'action'=>"edit"
           );
*/
//delete array
/**
$vendorarr = array(
            'vendorid'=>'1',
           'userkey'=>'1271601539',
            'action'=>"delete"
            );

*/
$vend = array($vendorarr,$vendorarr1,$vendorarr2,$vendorarr3,$vendorarr4);
foreach($vend as $k)
{
   echo $vd = json_encode($k);
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
curl_setopt($ch, CURLOPT_POSTFIELDS,"vendor=".urlencode($vd)."");
$result = curl_exec($ch);
//var_dump($result);
echo "<pre>".$result;
//$final =  json_decode($result);
//echo "<pre>";print_r($final->item_details);
curl_close($ch);
}
?>
