<?php

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once 'class/clsPatients.php';
$action = exit_issetor($_REQUEST['action'], json_encode(array('result' => 'failure')));
$hasLL = exit_issetor($_REQUEST['hasLL'], json_encode(array('result' => 'failure')));
if ($action == 'getHeatData') {
    $objClsPatients = new clsPatients();
    if ($hasLL == 0) {
        $arrDetails = array();
        $arrDetails = $objClsPatients->getMissingLatLong();
        $maxLoops = 3;
        $noOfLoops = 0;
        while (count($arrDetails) > 0 && ($noOfLoops++ <= $maxLoops)) {
            $objClsPatients->GetLatLong($arrDetails);
            unset($arrDetails);
            $arrDetails = $objClsPatients->getMissingLatLong();
        }
    }
    $heatmap = $objClsPatients->getHeatMap();
    echo json_encode($heatmap);
}

function exit_issetor(&$var, $default = false) {
    if (isset($var)) {
        return $var;
    } else {
        exit($default);
    }
}

?>