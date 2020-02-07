<?php

if (isset($_REQUEST['addreason'])) {
    $ordersarr = array(
        'userkey' => 1408773752,
        'reason' => "TEs Test ETs"
    );

    $odr = array($ordersarr);


    foreach ($odr as $k) {
      echo  $od = json_encode($k);
        $url = "http://speed.elixiatech.com/deliveryapi/reasons_v1.php";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_POSTFIELDS, "addreason=" . urlencode($od) . "");
        $result = curl_exec($ch);
        echo "<pre>" . $result;
        curl_close($ch);
    }
}

if (isset($_REQUEST['editreason'])) {
    $ordersarr = array(
        'userkey' => 1408773752,
        'reasonid' => '2',
        'reason' => 'Test'
    );
    $odr = array($ordersarr);


    foreach ($odr as $k) {
       echo $od = json_encode($k);
        $url = "http://localhost/elixiaspeed/deliveryapi/reasons_v1.php";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_POSTFIELDS, "editreason=" . urlencode($od) . "");
        $result = curl_exec($ch);
        echo "<pre>" . $result;
        curl_close($ch);
    }
}

if (isset($_REQUEST['deletereason'])) {
    $ordersarr = array(
        'userkey' => 1408773752,
        'reasonid' => '2'
    );
    $odr = array($ordersarr);


    foreach ($odr as $k) {
       echo $od = json_encode($k);
        $url = "http://localhost/elixiaspeed/deliveryapi/reasons_v1.php";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_POSTFIELDS, "deletereason=" . urlencode($od) . "");
        $result = curl_exec($ch);
        echo "<pre>" . $result;
        curl_close($ch);
    }
}
?>
