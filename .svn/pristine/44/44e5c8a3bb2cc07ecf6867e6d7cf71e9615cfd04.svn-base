<?php

if (isset($_REQUEST['bill'])) {
    $ordersarr = array(
             'billno' => 'GT 8892',
             'slot' => "1",
             'flat'=>"Neelkanth Business Park",
             'building'=>"Neelkanth Business Park",
             'landmark'=>"Neelkanth Business Park",
             'street'=>'Neelkanth Business Park',
             'area'=>'Vidyavihar',
             'address'=>"Neelkanth Business Park, Vidyavihar",
             'userkey'=>1271601539,
             'pincode'=>"400086",
             'city'=>"Mumbai",
             'delivery_date'=>"2015-03-25",
             'operation_mode' => 0  //( 0 for testing and 1 for live data)
            );

        $odr = array($ordersarr);

        foreach($odr as $k)
        {
           echo $od = json_encode($k);
           $url = "http://localhost/elixiaspeed/deliveryapi/delivery_v2.php";
           $ch = curl_init();
        
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
}

if (isset($_REQUEST['edit'])) {
    $ordersarr = array(
             'billno' => 'GT 8888',
             'slot' => "1",
             'flat'=>"",
             'building'=>"",
             'landmark'=>"",
             'street'=>'Tejpal Scheme Road No.1 (Vile Parle East)',
             'area'=>'Tejpal Scheme Road No.1 (Vile Parle East)',
             'address'=>"",
             'userkey'=>1271601539,
             'pincode'=>"424316",
             'city'=>"Mumbai",
             'delivery_date'=>"2015-03-25",
             'operation_mode' => 0  //( 0 for testing and 1 for live data)
            );
    $odr = array($ordersarr);
    foreach($odr as $k)
    {
        echo $od = json_encode($k);
        //print_r($od);
        $url = "http://localhost/elixiaspeed/deliveryapi/delivery_v2.php";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"edit=".  urlencode($od)."");
        $result = curl_exec($ch);
        //var_dump($result);
        echo "<pre>".$result;
        //$final =  json_decode($result);
        //echo "<pre>";print_r($final->item_details);
        curl_close($ch);   
    }
    
}

if (isset($_REQUEST['cancel'])) {
    $ordersarr = array(
             'billno' => 'GT 8888',
             'userkey'=>1271601539,
             'operation_mode' => 0 
            );
    $odr = array($ordersarr);
    foreach($odr as $k)
    {
        echo $od = json_encode($k);
        //print_r($od);
        $url = "http://localhost/elixiaspeed/deliveryapi/delivery_v2.php";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"cancel=".  urlencode($od)."");
        $result = curl_exec($ch);
        //var_dump($result);
        echo "<pre>".$result;
        //$final =  json_decode($result);
        //echo "<pre>";print_r($final->item_details);
        curl_close($ch);   
    }
}








                      
?>
