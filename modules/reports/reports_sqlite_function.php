<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
        $RELATIVE_PATH_DOTS = "../../";
    }
    include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
    include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
    include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';

    if (!isset($_SESSION)) {
        session_start();
        if (!isset($_SESSION['timezone'])) {
            $_SESSION['timezone'] = 'Asia/Kolkata';
        }
        date_default_timezone_set('' . $_SESSION['timezone'] . '');
    }

function getunitnotemp($vehicleid) {
 $um = new UnitManager($_SESSION['customerno']);
 $unitno = $um->getuidfromvehicleid($vehicleid);
 return $unitno;
 }

function get_conversion($unitid) {
$um = new UnitManager($_SESSION['customerno']);
$deviceid = $um->get_conversionfromUnitno($unitid);
return $deviceid;
}



function getSqlitereport($STdate, $vehicleid, $STime = null, $ETime = null, $interval=null, $deviceid = null, $EDdate = NULL) {
        //$totaldays = gendays($STdate, $EDdate);

        //$finalReturn = '';
        /* For Graph */
        $days = array();
        $graph_days = array();
        $graph_days_temp1 = array();
        $graph_days_temp2 = array();
        $graph_days_temp3 = array();
        $graph_days_temp4 = array();
        $graph_days_ig = array();
        /*End code for graph*/


        $unit = getunitdetails($deviceid);
        $unitno = getunitnotemp($vehicleid);

        $get_conversion = get_conversion($unitno);

        $customerno = $_SESSION['customerno']; ///  loged User Number//
        // echo "customer no". $customerno; die;
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$STdate.sqlite";
        //echo "Date ".$STdate." Location ".$location." Vehicle ID".$vehicleid; die;
        $obj = new VehicleManager($customerno);
        $tempsenData = $obj->get_vehicle_details($vehicleid);

        $tempsen1 = $tempsenData->tempsen1;
        $tempsen2 = $tempsenData->tempsen2;
        $tempsen3 = $tempsenData->tempsen3;
        $tempsen4 = $tempsenData->tempsen4;

//        echo $temp1. $temp2. $temp3. $temp4; die;
        $DATA = null;
        if (file_exists($location)) {

           /*code for graph*/
                $data = null;
                $graph_data = null;
                $STdate = date("Y-m-d", strtotime($STdate));

                $f_STdate = $STdate . " " . $STime . ":00";
                if (isset($EDdate)) {
                    $EDdate = date("Y-m-d", strtotime($EDdate));
                    $f_EDdate = $EDdate . " " . $ETime . ":00";
                } else {
                    $EDdate = date("Y-m-d", strtotime($STdate));
                    $f_EDdate = $EDdate . " " . $ETime . ":00";
                }
                if (file_exists($location)) {
                    
                    $location1 = "sqlite:" . $location;
                    $return = gettempdata_fromsqlite($location1, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                    $data = $return[0];
                    $graph_data = $return[1];
                    $countGraph0 = count($graph_data['0']);
                    $countGraph1 = count($graph_data['1']);
                    $countGraph2 = count($graph_data['2']);
                    $countGraph3 = count($graph_data['3']);
                        //echo $countGraph1; die;
                    $graph_data_temp1 = (isset($graph_data[0]) && $countGraph0 > 0) ? $graph_data[0] : NULL;
                    $graph_data_temp2 = (isset($graph_data[1]) && $countGraph1 > 0) ? $graph_data[1] : NULL;
                    $graph_data_temp3 = (isset($graph_data[2]) && $countGraph2 > 0) ? $graph_data[2] : NULL;
                    $graph_data_temp4 = (isset($graph_data[3]) && $countGraph3 > 0) ? $graph_data[3] : NULL;
                    $graph_ig = $return['ig_graph'];
                }
                if (isset($data) && count($data) > 1) {
                    $days = array_merge($days, $data);
                }
                if (isset($graph_data_temp1) && count($graph_data_temp1) > 1) {
                    $graph_days_temp1 = array_merge($graph_days_temp1, $graph_data_temp1);
                    $graph_days_ig = array_merge($graph_days_ig, $graph_ig);

                    if (isset($graph_data_temp2) && count($graph_data_temp2) > 1) {
                        $graph_days_temp2 = array_merge($graph_days_temp2, $graph_data_temp2);
                    }
                    if (isset($graph_data_temp3) && count($graph_data_temp3) > 1) {
                        $graph_days_temp3 = array_merge($graph_days_temp3, $graph_data_temp3);
                    }
                    if (isset($graph_data_temp4) && count($graph_data_temp4) > 1) {
                        $graph_days_temp4 = array_merge($graph_days_temp4, $graph_data_temp4);
                    }
                }
           /*Code for graph*/     
           if (isset($days) && count($days) > 0) {
            if (isset($return['vehicleid'])) {
                $veh_temp_details = getunitdetailsfromvehidSqlite($return['vehicleid'], $customerno);
            }
            if (isset($tripmin)) {
                $unit->temp1_min = $tripmin;
                $unit->temp2_min = $tripmin;
                $unit->temp3_min = $tripmin;
                $unit->temp4_min = $tripmin;
            }
            if (isset($tripmax)) {
                $unit->temp1_max = $tripmax;
                $unit->temp2_max = $tripmax;
                $unit->temp3_max = $tripmax;
                $unit->temp4_max = $tripmax;
            }
            // print("<pre>"); print_r($finalreport); die;
        }
            
        $graph_days_final_temp1 = '';
        $graph_days_final_temp2 = '';
        $graph_days_final_temp3 = '';
        $graph_days_final_temp4 = '';
        $graph_ig_final = '';
        if (!empty($graph_days_temp1)) {
            $graph_days_final_temp1 = implode(',', $graph_days_temp1);
            $graph_ig_final = implode(',', $graph_days_ig);
            if (!empty($graph_days_temp2)) {
                $graph_days_final_temp2 = implode(',', $graph_days_temp2);
            }
            if (!empty($graph_days_temp3)) {
                $graph_days_final_temp3 = implode(',', $graph_days_temp3);
            }
            if (!empty($graph_days_temp4)) {
                $graph_days_final_temp4 = implode(',', $graph_days_temp4);
            }
        }
        $graph_days_final = array($graph_days_final_temp1, $graph_days_final_temp2, $graph_days_final_temp3, $graph_days_final_temp4);
        //print("<pre>"); print_r($graph_days_final); die;


    //return array($finalreport, $graph_days_final, $veh_temp_details, $graph_ig_final);
        //return array($graph_days_final, $graph_ig_final);
    /*$return =  array($finalreport, $graph_days_final, $veh_temp_details, $graph_ig_final);
print("<pre>"); print_r($return); die;*/









            //echo "Location"; die;
            //    $DATA = GetDailyReport_Data_All($location, $totaldays);
            $DATA = GetchangeSqliteReport_Data_All($location, $STdate, $options = array('islocation' => 1, 'customerno' => array(), 'vehicleid' => $vehicleid, 'deviceid' => $deviceid, 'groupid' => array(), 'STime'=>$STime, 'ETime'=>$ETime, 'interval'=>$interval,'unitno'=>$unitno),$EDdate);

            //print("<pre>"); print_r($DATA); die;
        
            $tempData = array();

            if (isset($DATA) && !empty($DATA)) {
                foreach ($DATA as $data) {
                    if (isset($data->analog1) && $data->analog1 != 0 && $data->analog1 != 1150) {
                        $tempconversion = new TempConversion();
                        $tempconversion->unit_type = $get_conversion;
                        $tempconversion->use_humidity = $_SESSION['use_humidity'];
                        $tempconversion->switch_to = $_SESSION['switch_to'];
                        //$s = "analog" . $vehicle->humidity;
                        $tempconversion->rawtemp = $data->analog1;
                        $temp1 = getTempUtil($tempconversion);
                    } else {
                        $temp1 = 0;
                    }
                    if (isset($data->analog2) && $data->analog2 != 0 && $data->analog2 != 1150) {
                        $tempconversion = new TempConversion();
                        $tempconversion->unit_type = $get_conversion;
                        $tempconversion->use_humidity = $_SESSION['use_humidity'];
                        $tempconversion->switch_to = $_SESSION['switch_to'];
                        $tempconversion->rawtemp = $data->analog2;
                        $temp2 = getTempUtil($tempconversion);
                    } else {
                        $temp2 = 0;
                    }
                    if (isset($data->analog3) && $data->analog3 != 0 && $data->analog3 != 1150) {
                        $tempconversion = new TempConversion();
                        $tempconversion->unit_type = $get_conversion;
                        $tempconversion->use_humidity = $_SESSION['use_humidity'];
                        $tempconversion->switch_to = $_SESSION['switch_to'];
                        $tempconversion->rawtemp = $data->analog3;
                        $temp3 = getTempUtil($tempconversion);
                    } else {
                        $temp3 = 0;
                    }
                    if (isset($data->analog4) && $data->analog4 != 0 && $data->analog4 != 1150) {
                        $tempconversion = new TempConversion();
                        $tempconversion->unit_type = $get_conversion;
                        $tempconversion->use_humidity = $_SESSION['use_humidity'];
                        $tempconversion->switch_to = $_SESSION['switch_to'];
                        $tempconversion->rawtemp = $data->analog4;
                        $temp4 = getTempUtil($tempconversion);
                    } else {
                        $temp4 = 0;
                    }
                    $std = new stdClass();
                    $std->temp1 = $temp1;
                    $std->temp2 = $temp2;
                    $std->temp3 = $temp3;
                    $std->temp4 = $temp4;

                    $std->vehicleid = $data->vehicleid;
                    $std->date = $data->date;
                    $std->uhid = $data->uhid;
                    $std->uid = $data->uid;
                    $std->lastupdated = $data->lastupdated;

                    $tempData[] = $std;
                }
                    $itemTempsen['tempsen1'] = $tempsen1;
                    $itemTempsen['tempsen2'] = $tempsen2;
                    $itemTempsen['tempsen3'] = $tempsen3;
                    $itemTempsen['tempsen4'] = $tempsen4;
                    //$tempData['tempsen1'] = $tempsen1;
                    //$tempData['tempsen2'] = $tempsen2;
                    //$tempData['tempsen3'] = $tempsen3;
                    //$tempData['tempsen4'] = $tempsen4; 
                    $finalReturn["tempsen"] = $itemTempsen;
                    $finalReturn['result'] = $tempData;
                $finalReturn =  array($finalReturn, $graph_days_final, $veh_temp_details = null, $graph_ig_final);    
                return $finalReturn;
            } else {
               return $finalReturn = "";
            }
        }

        //return $DATA;
    }

function GetchangeSqliteReport_Data_All($location, $STdate, $options = array(),$EDdate = NULL) {
                /**
                 * @since 25-09-2017 By Suman Sharma
                 * @internal $options['islocation'] = 0 means location details need to be fetched from google
                 * @internal $options['islocation'] = 1 means location details not required and can be null/empty
                 * @internal $options['groupid'] = array(1, 2, 3) or array(1);
                 * @internal $options = array('islocation' => 0, 'customerno' => array(98), 'groupid' => array());
                 * @todo Testing pending
                 */

// echo print("<pre>"); print_r($options); die;

                $vehicleid = isset($options['vehicleid']) ? $options['vehicleid'] : 0;
                $interval = isset($options['interval']) ? $options['interval'] : 15;
                $deviceid = isset($options['deviceid']) ? $options['deviceid'] : 0;
                $unitno = isset($options['unitno']) ? $options['unitno'] : 0;
                $groupid = (isset($_SESSION['groupid']) ? array($_SESSION['groupid']) : $options['groupid']);
                $customerno = (isset($_SESSION['customerno']) ? $_SESSION['customerno'] : $options['customerno']);
                $locationNotFound = '';
                
                $REPORT = array();
                
                $startdate = $STdate." ". $options['STime'].":00";
                if (isset($EDdate)) {
                    $enddate = $EDdate." ". $options['ETime'].":00";
                } else {
                    $enddate = $STdate." ". $options['ETime'].":00";
                }
                $totaldays = gendays($startdate, $enddate);
                
                if (isset($totaldays)) {
                    foreach ($totaldays as $userdate) {
                        //Date Check Operations
                        $data = null;
                        $STdate = date("Y-m-d", strtotime($startdate));
                        $stime = date("H:i", strtotime($startdate));
                        if ($userdate == $STdate) {
                            $f_STdate = $userdate . " " . $stime . ":00";
                        } else {
                            $f_STdate = $userdate . " 00:00:00";
                        }
                        $EDdate = date("Y-m-d", strtotime($enddate));
                        $etime = date("H:i", strtotime($enddate));
                        if ($userdate == $EDdate) {
                            $f_EDdate = $userdate . " " . $etime . ":00";
                        } else {
                            $f_EDdate = $userdate . " 23:59:59";
                        }
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                        
                        if ((isset($vehicleid) && $vehicleid != "") && (isset($options['STime']) && isset($options['ETime']))) {
                            $Query = "  SELECT  devicehistory.ignition AS ignition
                                                , devicehistory.lastupdated AS lastupdated
                                                , devicehistory.devicelat AS devicelat
                                                , devicehistory.devicelong AS devicelong
                                                , u.unitno AS unitno
                                                , u.vehicleid AS vehicleid
                                                , u.digitalio AS digitalio
                                                , u.analog1 AS analog1
                                                , u.analog2 AS analog2
                                                , u.analog3 AS analog3
                                                , u.analog4 AS analog4
                                                , v.odometer AS odometer
                                                , u.uid AS uid
                                                , u.uhid AS uhid
                                        FROM    devicehistory
                                        INNER JOIN (SELECT  unitno,vehicleid
                                                            ,digitalio
                                                            ,analog1
                                                            ,analog2
                                                            ,analog3
                                                            ,analog4
                                                            ,uid
                                                            ,uhid
                                                            ,lastupdated
                                                    FROM    unithistory
                                                    WHERE   uhid IN (SELECT vehiclehistoryid
                                                                    FROM    vehiclehistory
                                                                    GROUP BY strftime('%d-%m-%Y %H:%M' , lastupdated) HAVING odometer = MAX(odometer)
                                                                    )
                                                    ) AS u ON u.lastupdated = devicehistory.lastupdated
                                        INNER JOIN (SELECT  lastupdated,odometer
                                                    FROM    vehiclehistory
                                                    GROUP BY strftime('%d-%m-%Y %H:%M' , lastupdated) HAVING odometer = MAX(odometer)
                                                    ) as v ON devicehistory.lastupdated = v.lastupdated
                                        WHERE   devicehistory.lastupdated BETWEEN '$f_STdate' AND '$f_EDdate'
                                        AND     devicehistory.deviceid= $deviceid
                                        AND     (devicehistory.status IS NULL OR devicehistory.status!='F')
                                        " . $locationNotFound . "
                                        GROUP BY devicehistory.lastupdated";
                        } else {
                            $Query = "  SELECT  * 
                                        FROM    unithistory 
                                        WHERE   vehicleid = $vehicleid";
                        }
                        $path = "sqlite:$location";

                        $db = new PDO($path);
                        $result = $db->query($Query);
                        $lastupdated = '';
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new stdClass();
                               if ((!isset($lastupdated)) || (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval)) { 
                                        $Datacap->uid = $row['uid'];
                                        $Datacap->uhid = $row['uhid'];
                                        $Datacap->vehicleid = $row['vehicleid'];
                                        $Datacap->date = $STdate;
                                        $Datacap->analog1 = $row['analog1'];
                                        $Datacap->analog2 = $row['analog2'];
                                        $Datacap->analog3 = $row['analog3'];
                                        $Datacap->analog4 = $row['analog4'];
                                        $Datacap->lastupdated = $row['lastupdated'];
                                        $lastupdated = $row['lastupdated'];   
                                        $REPORT[] = $Datacap;
                                }
                            }
                        }
                        
                    }
                }
                return $REPORT;
            }

            /*end */


                                /**/
                                //error_reporting(E_NOTICE^E_ALL);
                                
function updateSqlite($column, $editval, $id, $date, $vehicleid, $uid) {
                                    //$STdate, $vehicleid) {

                                    //$totaldays = gendays($STdate, $EDdate);
                                    $unitno = getunitnotemp($vehicleid);

                                    $get_conversion = get_conversion($unitno);

                                    $customerno = $_SESSION['customerno']; ///  loged User Number//
                                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
                                    $DATA = null;
                                    if (file_exists($location)) {
                                        $tempData = array();

                                        if (isset($unitno) && !empty($unitno)) {
                                            $tempconversion = new TempConversion();
                                            $tempconversion->unit_type = $get_conversion;
                                            $tempconversion->use_humidity = $_SESSION['use_humidity'];
                                            $tempconversion->switch_to = $_SESSION['switch_to'];
                                            //$s = "analog" . $vehicle->humidity;
                                            $tempconversion->rawtemp = $editval;
                                            $analog = getAnalogUtil($tempconversion);

                                            $path = "sqlite:$location";

                                            $db = new PDO($path);

                                            $Query = "update unithistory set "
                                                . "$column='" . $analog
                                                . "' where uhid=" . $id;
                                            //echo
                                            $SQL = sprintf($Query);

                                            $db->exec('BEGIN IMMEDIATE TRANSACTION');
                                            $db->exec($SQL);
                                            $db->exec('COMMIT TRANSACTION');
                                            //return $msg = "Data Updated Successfully";
                                        }
                                    }

                                    //return $DATA;
                                }  

function deleteInsertSqlite($data) {

//print("<pre>"); print_r($data); die;
        $date = $data['date'];
        $unitno = getunitnotemp($data['vehicleid']);

        $get_conversion = get_conversion($unitno);
        $fromDateTime = $date." ". $data['Stime'].":00";
        $toDateTime = $date." ". $data['Etime'].":00";

        $column = "analog".$data['analog'];

        $Min = $data['min'];
        $Max = $data['max'];

        $customerno = $_SESSION['customerno']; ///  loged User Number//
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $DATA = null;
        if (file_exists($location)) {
            $tempData = array();

                $path = "sqlite:$location";

                $db = new PDO($path);

                $Query = "select * from unithistory where (lastupdated >= '"
                    .$fromDateTime. "' AND lastupdated <='" . $toDateTime. "') AND uid=" . $data['uid'];
                $SQL = sprintf($Query);
                
               $rs = $db->query($SQL);


               // print("<pre>"); print_r($fetch); die;
               //$rowCount = (int) $rs->fetchColumn(); 
                
               $Min1 = $Min;
               $Max1 = $Max;
               //echo "Total rows ". $rowCount; die;
               /*$Query = "delete from unithistory where (lastupdated BETWEEN '"
                    .$fromDateTime. "' AND '" . $toDateTime. "') AND uid=" . $data['uid'];
               echo $SQL = sprintf($Query);
               $rs = $db->query($SQL);*/

            if (isset($unitno) && !empty($unitno)) {

                $std = new stdClass();
                $randomfloat = array();
                $co = 0;
                $flag =0;
            //for($i=1; $i<=$rowCount; $i++)
          $fetched=$rs->fetchAll();
           foreach ($fetched as $fetch) {
           // echo $fetch['uhid']; die;
           //while($fetch=$rs->fetchAll()){

                    if($flag == 0){
                            if($Min+(0.2) < $Max1){
                                $randomfloat = $Min + 0.2;
                                $Min = $randomfloat;
                                // $std->value = $randomfloat; 
                                $editval = $randomfloat;
                            }else{
                                $Min = $Min1;
                                $flag = 1;
                            }
                    }else{ 

                        if($Max-(0.2) > $Min1){
                                $randomfloat = $Max - 0.2;
                                $Max = $randomfloat;
                                //$std->value = $randomfloat; 
                                $editval = $randomfloat;
                        }else{
                            $Max = $Max1;
                            $flag = 0;
                        }

                    }// end else

                $tempconversion = new TempConversion();
                $tempconversion->unit_type = $get_conversion;
                $tempconversion->use_humidity = $_SESSION['use_humidity'];
                $tempconversion->switch_to = $_SESSION['switch_to'];
                //$s = "analog" . $vehicle->humidity;
                $tempconversion->rawtemp = $editval;
                $analog = getAnalogUtil($tempconversion);
                
                $path = "sqlite:$location";

                $db = new PDO($path);
                $Query = "update unithistory set "
                    . "$column='" . $analog
                    . "' where uhid=" . $fetch['uhid'];
                //echo
                $SQL = sprintf($Query);

                $db->exec('BEGIN IMMEDIATE TRANSACTION');
                $db->exec($SQL);
                $db->exec('COMMIT TRANSACTION');

            } // end  loop
            echo "Successfully Updated";
        }
        }else{ echo "File not exist"; }

        //return $DATA;
    }


function updateSqliteDataToDatabase($data) {
                //$STdate, $vehicleid) {
    /*$count1 = count($data['analog1']);
    $count2 = count($data['analog2']);
    echo "count1 = ".$count1." and count2 = ". $count2;
    die;*/
    $EmptyTestArray1 = array_filter($data['analog1']);
    $EmptyTestArray2 = array_filter($data['analog2']);
    $EmptyTestArray3 = array_filter($data['analog3']);
    $EmptyTestArray4 = array_filter($data['analog4']);

    if(!empty($EmptyTestArray1)){
        //echo "yes"; 
        updateTosqlite($data['analog1'], $data['hanalog1']);
        //print("<pre>"); print_r($data['analog1']);
    }
    if(!empty($EmptyTestArray2)){
        //echo "yes2"; 
        updateTosqlite($data['analog2'], $data['hanalog2']);
        //print("<pre>"); print_r($data['analog1']);
    }
    if(!empty($EmptyTestArray3)){
        //echo "yes"; 
        updateTosqlite($data['analog3'], $data['hanalog3']);
        //print("<pre>"); print_r($data['analog1']);
    }
    if(!empty($EmptyTestArray4)){
        //echo "yes2"; 
        updateTosqlite($data['analog4'], $data['hanalog4']);
        //print("<pre>"); print_r($data['analog1']);
    }
    //die;

                //$totaldays = gendays($STdate, $EDdate);

}  

function updateTosqlite($analogs, $hanalog){
    $i=0;
    foreach ($analogs as $analog) {
        //print_r($analog);
        if($analog !=""){
            $hanalogArray = explode("_", $hanalog[$i]);
            //print_r($hanalogArray);
            $column = $hanalogArray['0'];
            $id = $hanalogArray['1'];
            $date = $hanalogArray['2'];
            $vehicleid = $hanalogArray['3'];
            
            $unitno = getunitnotemp($vehicleid);

                    $get_conversion = get_conversion($unitno);

                    $customerno = $_SESSION['customerno']; ///  loged User Number//
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
                    $DATA = null;
                    if (file_exists($location)) {
                        $tempData = array();

                        if (isset($unitno) && !empty($unitno)) {
                            $tempconversion = new TempConversion();
                            $tempconversion->unit_type = $get_conversion;
                            $tempconversion->use_humidity = $_SESSION['use_humidity'];
                            $tempconversion->switch_to = $_SESSION['switch_to'];
                            //$s = "analog" . $vehicle->humidity;
                            // $tempconversion->rawtemp = $editval;
                            $tempconversion->rawtemp = $analog;
                            $analog = getAnalogUtil($tempconversion);

                            $path = "sqlite:$location";

                            $db = new PDO($path,'','',array(PDO::ATTR_PERSISTENT => true));
//                            $relativepath = '../../';
//                            $permit = 0777;
//                            if (file_exists($relativepath.'customer'.$customerno.'/unitno/'.$unit->unitno)) {
//                                chmod($relativepath . '/customer/' . $customerno.'/unitno/'.$unit->unitno, $permit);
//                            }
//                            if (file_exists($relativepath.'customer'.$customerno.'/unitno/'.$unit->unitno.'/sqlite')) {
//                                chmod($relativepath.'customer'.$customerno.'/unitno/'.$unit->unitno.'/sqlite/'.$date.'.sqlite', $permit);
//                            }

                            $Query = "update unithistory set "
                                . "$column='" . $analog
                                . "' where uhid=" . $id;
                            //echo
                            $SQL = sprintf($Query);

                            $db->exec('BEGIN IMMEDIATE TRANSACTION');
                            $db->exec($SQL);
                            $db->exec('COMMIT TRANSACTION');
                            //return $msg = "Data Updated Successfully";
                        }
                    }
        }
        
    $i++;
    }

}


function getunitdetails($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getunitdetailsfromdeviceid($deviceid);
    return $unitno;
}


    function gettempdata_fromsqlite($location, $deviceid, $interval, $startdate, $enddate, $graph = false, $unit = null, $customerno = null) {
        $devices = array();
        $graph_devices = array();
        $graph_devices_temp1 = array();
        $graph_devices_temp2 = array();
        $graph_devices_temp3 = array();
        $graph_devices_temp4 = array();
        $graph_ignition = array();
        $last_ignition = null;
        $customerno = isset($customerno) ? $customerno : $_SESSION['customerno'];
        if (!isset($unit)) {
            $um = new UnitManager($customerno);
            $unit = $um->getunitdetailsfromdeviceid($deviceid);
        }
        $locationNotFound = '';
        if ($unit->kind != "Warehouse") {
            $locationNotFound = " AND devicelat <> '0.000000' AND devicelong <> '0.000000'";
        }
        $unit_type = $unit->get_conversion;
        $totaldays = gendays($startdate, $enddate);
        
        if (isset($totaldays)) {
            foreach ($totaldays as $userdate) {
                $lastupdated = '';
                //Date Check Operations
                $data = null;
                $STdate = date("Y-m-d", strtotime($startdate));
                $stime = date("H:i", strtotime($startdate));
                if ($userdate == $STdate) {
                    $f_STdate = $userdate . " " . $stime . ":00";
                } else {
                    $f_STdate = $userdate . " 00:00:00";
                }
                $EDdate = date("Y-m-d", strtotime($enddate));
                $etime = date("H:i", strtotime($enddate));
                if ($userdate == $EDdate) {
                    $f_EDdate = $userdate . " " . $etime . ":00";
                } else {
                    $f_EDdate = $userdate . " 23:59:59";
                }
                $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
                $query = "  SELECT  devicehistory.ignition
                            , devicehistory.lastupdated
                            , devicehistory.devicelat
                            , devicehistory.devicelong
                            , unithistory.unitno 
                            , unithistory.vehicleid
                            , unithistory.digitalio
                            , unithistory.analog1
                            , unithistory.analog2
                            , unithistory.analog3
                            , unithistory.analog4
                            , v.odometer
                    FROM    devicehistory
                    INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
                    INNER JOIN vehiclehistory as v ON devicehistory.lastupdated = v.lastupdated
                    WHERE   devicehistory.lastupdated BETWEEN '$f_STdate' AND '$f_EDdate' 
                    AND     devicehistory.deviceid= $deviceid 
                    AND     (devicehistory.status IS NULL OR devicehistory.status!='F') 
                    " . $locationNotFound . " 
                    GROUP BY devicehistory.lastupdated";
                
                try {
                    $permit = 0777;
                    $relativepath = '../../';
                    
                    // if (file_exists($relativepath.'customer/'.$customerno)) {
                    //     chmod($relativepath . '/customer/' . $customerno, $permit);
                    // }
                    if (file_exists($relativepath.'customer'.$customerno.'/unitno/'.$unit->unitno)) {
                        chmod($relativepath . '/customer/' . $customerno.'/unitno/'.$unit->unitno, $permit);
                    }
                    if (file_exists($relativepath.'customer'.$customerno.'/unitno/'.$unit->unitno.'/sqlite/'.$userdate.'.sqlite')) {
                        chmod($relativepath.'customer'.$customerno.'/unitno/'.$unit->unitno.'/sqlite/'.$userdate.'.sqlite', $permit);
                    }
                    $location1 = "sqlite:" . $location;
                    $database = new PDO($location1);
                    $query1 = $query . " ORDER BY devicehistory.lastupdated ASC"; 
                    $result = $database->query($query1);

                    if (isset($result) && $result != "") {
                        $total = 0;
                        foreach ($result as $row) {
                            if (!isset($vehicleid)) {
                                $vehicleid = $row['vehicleid'];
                            }
                            $total++;
                            $device = new VODevices();

                            if ((!isset($lastupdated)) || (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval)) {
                                $device->unitno = $row['unitno'];
                                $device->analog1 = $row['analog1'];
                                $device->analog2 = $row['analog2'];
                                $device->analog3 = $row['analog3'];
                                $device->analog4 = $row['analog4'];
                                $device->starttime = $row['lastupdated'];
                                $device->endtime = $row['lastupdated'];
                                $device->devicelat = $row['devicelat'];
                                $device->devicelong = $row['devicelong'];
                                $device->digitalio = $row['digitalio'];
                                $device->odometer = $row['odometer'];
                                $devices[] = $device;
                                if ($graph) {
                                    $temp_val1 = set_temp_graph_data_sqlite($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false, 1);
                                    $temp_val2 = set_temp_graph_data_sqlite($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false, 2);
                                    $temp_val3 = set_temp_graph_data_sqlite($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false, 3);
                                    $temp_val4 = set_temp_graph_data_sqlite($row['lastupdated'], $unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false, 4);
                                    if (!is_null($temp_val1)) {
                                        $graph_devices_temp1[] = $temp_val1;
                                    }
                                    if (!is_null($temp_val2)) {
                                        $graph_devices_temp2[] = $temp_val2;
                                    }
                                    if (!is_null($temp_val3)) {
                                        $graph_devices_temp3[] = $temp_val3;
                                    }
                                    if (!is_null($temp_val4)) {
                                        $graph_devices_temp4[] = $temp_val4;
                                    }
                                }
                                $lastupdated = $row['lastupdated'];
                            }
                            if ($last_ignition != $row['ignition']) {
                                if (isset($ig_lastupdated)) {
                                    $graph_ignition[] = set_temp_graph_data_sqlite($row['lastupdated'], $unit_type, null, null, null, null, null, "#$last_ignition#");
                                }
                                $graph_ignition[] = set_temp_graph_data_sqlite($row['lastupdated'], $unit_type, null, null, null, null, null, "#{$row['ignition']}#");
                                $last_ignition = $row['ignition'];
                                $ig_lastupdated = $row['lastupdated'];
                            }
                        }
                    }
                } catch (PDOException $e) {
                    die($e);
                }
            }
            //$graph_ignition[] = set_temp_graph_data_sqlite($row['lastupdated'], null, null, null, null, null, "#{$row['ignition']}#");
            $graph_devices = array($graph_devices_temp1, $graph_devices_temp2, $graph_devices_temp3, $graph_devices_temp4);
        }
        
        $vehicleid = isset($vehicleid) ? $vehicleid : 0;
        if ($graph) {
            return array($devices, $graph_devices, 'vehicleid' => $vehicleid, 'ig_graph' => $graph_ignition);
        } else {
            return array($devices, 'vehicleid' => $vehicleid);
        }
    }


    function set_temp_graph_data_sqlite($updated_date, $unit_type, $unit, $analog1, $analog2, $analog3, $analog4, $only_date = false, $tempSelected = 1) {
        $str_ch = strtotime($updated_date);
        $yr = date('Y', $str_ch);
        $mth = date('m', $str_ch) - 1;
        $day = date('d', $str_ch);
        $hour = date('H', $str_ch);
        $mins = date('i', $str_ch);
        $temp = null;
        $tempconversion = new TempConversion();
        $tempconversion->unit_type = $unit_type;
        $tempconversion->use_humidity = $_SESSION['use_humidity'];
        $tempconversion->switch_to = $_SESSION['switch_to'];
        if ($only_date) {
            return "[Date.UTC($yr, $mth, $day, $hour, $mins), $only_date]";
        }
        $temp1 = null;
        $temp2 = null;
        $temp3 = null;
        $temp4 = null;
        switch ($_SESSION['temp_sensors']) {
        case 4:
            $s = "analog" . $unit->tempsen4;
            if ($unit->tempsen4 != 0 && $$s != 0) {
                $tempconversion->rawtemp = $$s;
                $temp4 = getTempUtil($tempconversion);
            }
        case 3:
            $s = "analog" . $unit->tempsen3;
            if ($unit->tempsen3 != 0 && $$s != 0) {
                $tempconversion->rawtemp = $$s;
                $temp3 = getTempUtil($tempconversion);
            }
        case 2:
            $s = "analog" . $unit->tempsen2;
            if ($unit->tempsen2 != 0 && $$s != 0) {
                $tempconversion->rawtemp = $$s;
                $temp2 = getTempUtil($tempconversion);
            }
        case 1:
            $s = "analog" . $unit->tempsen1;
            if ($unit->tempsen1 != 0 && $$s != 0) {
                $tempconversion->rawtemp = $$s;
                $temp1 = getTempUtil($tempconversion);
            }
        default:
            $s = "analog" . $unit->tempsen1;
            if ($unit->tempsen1 != 0 && $$s != 0) {
                $tempconversion->rawtemp = $$s;
                $temp1 = getTempUtil($tempconversion);
            }
        }
        if ($tempSelected == 1) {
            $temp = $temp1;
        } elseif ($tempSelected == 2) {
            $temp = $temp2;
        } elseif ($tempSelected == 3) {
            $temp = $temp3;
        } elseif ($tempSelected == 4) {
            $temp = $temp4;
        } else {
            $temp = $temp1;
        }

        if (!is_null($temp) && $temp != '0' && $temp != '-' && $temp < NORMAL_MAX_TEMP && $temp > NORMAL_MIN_TEMP) {
            get_min_temperature($temp);
            get_max_temperature($temp);
            return "[Date.UTC($yr, $mth, $day, $hour, $mins), $temp]";
        } else {
            return null;
        }
    }

    function get_min_temperature($temp, $get_data = false) {
        static $min_temp = 50;
        if (!is_null($temp) && !$get_data && $temp < $min_temp) {
            $min_temp = round($temp);
        }
        return $min_temp;
    }

    function get_max_temperature($temp, $get_data = false) {
        static $max_temp;
        if (!$get_data && $temp > $max_temp) {
            $max_temp = round($temp);
        }
        return $max_temp;
    }

     /* get min and max temp-limit for this customer */

                                function get_min_max_temp($tempselect, $return, $temp_sensors = null) {
                                    $sess_temp_sensors = ($temp_sensors != null) ? $temp_sensors : $_SESSION['temp_sensors'];
                                    $temp_max_limit = 7;
                                    $temp_min_limit = 0;
                                    if ($sess_temp_sensors == 4) {
                                        if (isset($tempselect) && $tempselect == 1) {
                                            $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
                                            $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
                                        }
                                        if (isset($tempselect) && $tempselect == 2) {
                                            $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : $temp_max_limit;
                                            $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : $temp_min_limit;
                                        }
                                        if (isset($tempselect) && $tempselect == 3) {
                                            $temp_max_limit = isset($return->temp3_max) ? $return->temp3_max : $temp_max_limit;
                                            $temp_min_limit = isset($return->temp3_min) ? $return->temp3_min : $temp_min_limit;
                                        }
                                        if (isset($tempselect) && $tempselect == 4) {
                                            $temp_max_limit = isset($return->temp4_max) ? $return->temp4_max : $temp_max_limit;
                                            $temp_min_limit = isset($return->temp4_min) ? $return->temp4_min : $temp_min_limit;
                                        }
                                    }
                                    if ($sess_temp_sensors == 3) {
                                        if (isset($tempselect) && $tempselect == 1) {
                                            $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
                                            $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
                                        }
                                        if (isset($tempselect) && $tempselect == 2) {
                                            $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : $temp_max_limit;
                                            $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : $temp_min_limit;
                                        }
                                        if (isset($tempselect) && $tempselect == 3) {
                                            $temp_max_limit = isset($return->temp3_max) ? $return->temp3_max : $temp_max_limit;
                                            $temp_min_limit = isset($return->temp3_min) ? $return->temp3_min : $temp_min_limit;
                                        }
                                    }
                                    if ($sess_temp_sensors == 2) {
                                        if (isset($tempselect) && $tempselect == 1) {
                                            $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
                                            $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
                                        }
                                        if (isset($tempselect) && $tempselect == 2) {
                                            $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : $temp_max_limit;
                                            $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : $temp_min_limit;
                                        }
                                    }
                                    if ($sess_temp_sensors == 1) {
                                        $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
                                        $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
                                    }
                                    return array('temp_max_limit' => $temp_max_limit, 'temp_min_limit' => $temp_min_limit);
                                }

    function getunitdetailsfromvehidSqlite($deviceid, $customerno = null) {
        $customerno = isset($customerno) ? $customerno : $_SESSION['customerno'];
        $um = new UnitManager($customerno);
        $unitno = $um->getunitdetailsfromvehid($deviceid);
        return $unitno;
    }
    
    function gendays($STdate, $EDdate) {
        $TOTALDAYS = array();
        $STdate = date("Y-m-d", strtotime($STdate));
        $EDdate = date("Y-m-d", strtotime($EDdate));
        while (strtotime($STdate) <= strtotime($EDdate)) {
            $TOTALDAYS[] = $STdate;
            $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
        }
        return $TOTALDAYS;
    }

?>