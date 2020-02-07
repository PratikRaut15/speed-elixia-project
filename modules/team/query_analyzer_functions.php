<?php

include_once '../../lib/system/DatabaseManager.php';


if (isset($_POST['query'])) {
    $customername = $_POST['customername'];
    $vehicleno = $_POST['vehicleno'];
    $customerno = $_POST['customerno'];
    $unitno = $_POST['unitno'];
    $unitid = $_POST['unitid'];
    $SDate = $_POST["startdate"];
    $EDate = $_POST["enddate"];
    $totaldays = gendays_cmn($SDate, $EDate);
    $totaldays = array_reverse($totaldays);
    $count1 = count($totaldays);
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];


    $queue = $queues = array();
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $lastday = new_travel_data($customerno, $unitno, $userdate, $count1, $firstelement, $endelement);
            if ($lastday != null) {
                $queue = array_merge($queue, $lastday);
               
            }
        }
    }
     echo json_encode($queue);

}
function gendays_cmn($STdate, $EDdate) {
    $TOTALDAYS = Array();
    $STdate = date("Y-m-d", strtotime($STdate));
    $EDdate = date("Y-m-d", strtotime($EDdate));
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $TOTALDAYS[] = $STdate;
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
    }
    return $TOTALDAYS;
}

function new_travel_data($customerno, $unitno, $userdate, $count, $firstelement, $endelement) {
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
    if (!file_exists($location)) {
        return null;
    }
    if (filesize($location) == 0) {
        return null;
    }
    $location = "sqlite:" . $location;
    $devicedata = getdatafromsqliteTimebased($location, $userdate);
    if ($devicedata != null) {
        return $devicedata;
    } else {
        return null;
    }
}

function getdatafromsqliteTimebased($location, $userdate) {
    try {
            $database = new PDO($location);

            $query = "SELECT * from data ORDER by insertedon desc";
            $result = $database->query($query);
            $data = array();
            $i=0;
            foreach ($result as $row) {
                $data[$i] = explode(',',  $row['data']);
                $i++;
            }
            $j=0;
            
            $Datacap =  array();
            if (isset($data) && $data != "") {
                foreach ($data as $row) {
                      
                    $Datacap[$j]['status']        =  $row[3];
                    $Datacap[$j]['commandkey']    =  $row[4];
                    $Datacap[$j]['commandkeyval'] =  $row[5];
                    $Datacap[$j]['ignition']      =  $row[6];
                    $Datacap[$j]['powercut']      =  $row[7];
                    $Datacap[$j]['tamper']        =  $row[8];
                    $Datacap[$j]['odometer']      =  $row[10];
                    $Datacap[$j]['curspeed']      =  $row[11];
                    $Datacap[$j]['gpsfixed']      =  $row[13];
                    $Datacap[$j]['devicelat']     =  $row[14];
                    $Datacap[$j]['devicelong']    =  $row[15];
                    $Datacap[$j]['lastupdated']   =  calculate_time($row[19],$row[18]);
                    $Datacap[$j]['gsmstrength']   =  $row[20];
                    $Datacap[$j]['gsmregister']   =  $row[21];
                    $Datacap[$j]['gprsregister']  =  $row[22];
                    $Datacap[$j]['inbatt']        =  $row[24];
                    $Datacap[$j]['extbatt']       =  $row[25];
                    $Datacap[$j]['digitalio']     =  $row[26];
                    $Datacap[$j]['analog1']       =  $row[27];
                    $Datacap[$j]['analog2']       =  $row[28];
                    $Datacap[$j]['analog3']       =  $row[29];
                    $Datacap[$j]['analog4']       =  $row[30];
                    $Datacap[$j]['hwv']           =  $row[31];
                    $Datacap[$j]['swv']           =  $row[32];
                    $Datacap[$j]['onoff']         =  $row[33];

                    $datas[] = $Datacap[$j];
                    $j++;
                }
            }
        } catch (PDOException $e) {
            die($e);
        }
    return $datas;
}

function calculate_time($Devicedate,$Devicetime){
    
    $date = $Devicedate."";
    $time = $Devicetime."";
    
    $date1= substr($date,0,2);
  

    $date2= substr($date,2,2);
   
    $date3 = substr($date,4,6);

    $time1= substr($time,0,2);

    $time2= substr($time,2,2);
   
    $time3 = substr($time,4,6);

    $final_date = $date3."-".$date2."-".$date1;
    $final_time = $time1.":".$time2.":".$time3;
    $datetime = new DateTime($final_date."T".$final_time, new DateTimeZone('Asia/Calcutta'));


    $userTimezone = new DateTimeZone('Asia/Calcutta');
    $gmtTimezone = new DateTimeZone('GMT');
    $myDateTime = new DateTime($final_date." ".$final_time, $gmtTimezone);

    $offset = $userTimezone->getOffset($myDateTime);
    $myInterval=DateInterval::createFromDateString((string)$offset . 'seconds');
    $myDateTime->add($myInterval);
    $result = $myDateTime->format('d-m-Y H:i:s');
    
    return $result;
}

$today = date("d-m-Y");
?>