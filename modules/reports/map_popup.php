<?php
function getChkRealy($lat, $long, $arrCheckpoints = null) {
    $chks = array();

    if (isset($arrCheckpoints) && !empty($arrCheckpoints) && $arrCheckpoints) {
        foreach ($arrCheckpoints as $checkpoint) {
            $distance = calculate($lat, $long, $checkpoint->cgeolat, $checkpoint->cgeolong); //echo "<br/>";
            if ($distance < $checkpoint->crad) {
                $chk = new stdClass();
                $chk->cgeolat = $checkpoint->cgeolat;
                $chk->cgeolong = $checkpoint->cgeolong;
                $chk->cname = $checkpoint->cname;
                $chk->crad = $checkpoint->crad;
                $chks[] = $chk;
            }
        }
    }
    return $chks;
}

function rad($x) {
    return $x * pi() / 180;
}

function calculate($devicelat, $devicelong, $cgeolat, $cgeolong) {
    //Earth's mean radius in km
    $ERadius = 6371;
    //Difference between devicelatlong and checkpointlatlong
    $diffLat = rad($cgeolat - $devicelat);
    $diffLong = rad($cgeolong - $devicelong);
    //Converting between devicelatlong to radians
    $devlat_rad = rad($devicelat);
    $devlong_rad = rad($cgeolat);
    //Calculation Using Haversine's formula
    //Applying Haversine formula
    $a = sin($diffLat / 2) * sin($diffLat / 2) + cos($devlat_rad) * cos($devlong_rad) * sin($diffLong / 2) * sin($diffLong / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    //Distance
    $diffdist = $ERadius * $c;
    return $diffdist;
}

?>
