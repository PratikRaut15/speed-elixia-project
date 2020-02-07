<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';

$dailydate = date('Y-m-d');
$customerno = 577;
$reportDate = new DateTime($dailydate);
$arrUnits = array(
    '1901010025219',
    '1901010025243',
    '1844010024040',
    '1901010025151',
    '1901010024931',
    '1739010013870',
    '1901010024915',
    '1901010024923',
    '1901010025011',
    '1901010025128',
    '1901010024998',
    '1747010016496',
    '1737010013148',
    '1747010016462',
    '1845010024419',
    '1734010010356',
    '1844010024156',
    '1901010024881',
    '1747010016488',
    '1901010025235',
    '1901010025037',
    '1901010025060',
    '1901010025227',
    '1915010029096',
    '1845010024526',
    '1743010015802',
    '1737010012504',
    '1901010025144',
    '1901010025086',
    '1901010025185',
);

//echo date('H:i:s', strtotime(93705));
//die();
if (isset($arrUnits) && !empty($arrUnits)) {
    foreach ($arrUnits as $unit) {
        $unitno = $unit;
        $date = $dailydate;
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        if (file_exists($location)) {
            //echo "test";
            $arrData = DataFromSqlite($location);
            //print_r($arrData);
            if (isset($arrData) && $arrData) {
                foreach ($arrData as $data) {
                    $arrElements = explode(',', $data->data);
                    //print_r($arrElements);
                    $time = date('H:i:s', strtotime($arrElements['18'])); //$arrElements['18'];
                    $date = date('Y-m-d', strtotime($arrElements['19'])); //$arrElements['19'];

                    $lastDateTime = $date . " " . $time;
                    $lastDateTime = date('Y-m-d H:i:s',strtotime('+330 minutes', strtotime($lastDateTime)));
                    //$data->insertedon = date($data->insertedon, strtotime('+330 minutes', 0));

                    $to_time = strtotime($data->insertedon);
                    $from_time = strtotime($lastDateTime);
                    $timediff = round(abs($to_time - $from_time) / 60, 2);
                    if ($timediff > 10 && $arrElements['33'] == 0) {
                        $subject = "Wrong Data - ". $unitno;
                        $message = "We have received the data for Unit No ".$unitno." at ".$data->insertedon." of time ".$lastDateTime."";
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        $CCEmail = "";
                        $BCCEmail = 'software@elixiatech.com';
                        $mailid = "dinesht@elixiatech.com";
                        $isMailSent = sendMailUtil(array($mailid), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                        if (isset($isMailSent)) {
                            echo $message;
                        } else {
                            echo "Test";
                        }
                    }
                }
            }
        }
        //die();
    }
}

function DataFromSqlite($location) {
    $PATH = "sqlite:$location";
    $Query = 'select * from data order by dataid DESC limit 1';
    $DRMS = array();
    $lastig;
    try {
        $db = new PDO($PATH);
        $result = $db->query($Query);
        foreach ($result as $row) {
            $DRM = new stdClass();
            $DRM->dataid = $row['dataid'];
            $DRM->data = $row['data'];
            $DRM->client = $row['client'];
            $DRM->insertedon = $row['insertedon'];
            $DRMS[] = $DRM;
        }
    } catch (PDOException $e) {
        $DRMS = 0;
    }
    return $DRMS;
}
