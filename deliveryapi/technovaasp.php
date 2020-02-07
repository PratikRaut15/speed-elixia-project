<?php
$ordersarr = array(
             			 'ppd_no' => 'PD481',
             			 'userkey' => 1408773752,  // Provide By Elixiatech 1408773752
            			 'sales_type'=>"0",            // 0(zero) For JDE & 1(one) for Non JDE
			         'ref_number'=>"456454564456gf",
                                 /*
          			 'sold_to_name'=> "Vipul Patel",
           			 'sold_to_address'=>"725 Neelakanth Buisness Park",
             			 'sold_to_city'=>"Mumbai",       // Optional
            			 'sold_to_country'=>"India",    // Optional
            			 'sold_to_phone'=>"9214545689",     // Optional
            			 'sold_to_state'=>"Maharashtra",     // Optional
            			 'sold_to_zip'=>"400026",       // Optional
            			 'sold_to_code'=>"Sold_7485",       // Optional
                                 */
            			'ship_to_name'=> "Vipul Patel",
             			'ship_to_address'=>"625 Neelakanth Buisness Park",
             			//'ship_to_city'=>"Mumbai",     // Optional
             			//'ship_to_country'=>"India",  // Optional
            		 	'ship_to_phone'=>"9632587412",    // Optional
            		 	//'ship_to_state'=>"Maharashtra",    // Optional
             			//'ship_to_zip'=>"400025",      // Optional
             			'ship_to_code'=>"ship_7485",      // Optional
             			'production_details'=>"demo test demo text",      // Optional
             			'quantity'=>"4",      // Optional

             			'provider'=>'Technova',
             			'route_no'=>'21',  // Provide By Elixiatech
            			 'transporter_name'=>'Manish Dube',
             			//'lr_no'=>"50",
            			 //'lr_date'=>"2014-12-10 12:45:25",
             			'updated_date'=>"2014-12-12 10:15:25"
           		 );










$odr = array($ordersarr);


foreach($odr as $k)
{
  echo $od = json_encode($k);
//print_r($od);

$url = "http://speed.elixiatech.com/deliveryapi/delivery.php";

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
curl_setopt($ch, CURLOPT_POSTFIELDS,"pod=".  urlencode($od)."");
$result = curl_exec($ch);
//var_dump($result);
echo "<pre>".$result;
//$final =  json_decode($result);
//echo "<pre>";print_r($final->item_details);
curl_close($ch);
}

?>
