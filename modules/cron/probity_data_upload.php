<?PHP

require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/CustomerManager.php';
require_once '../../lib/bo/DeviceManager.php';
require_once '../../lib/bo/VehicleManager.php';
require_once '../../lib/bo/UnitManager.php';
$today = date('d-m-y');
session_start();

set_time_limit(120);

class VOProbity {

}

$action = $_REQUEST['action'];
if ($action == 'addprobity') {
    $arr_p = array();
    $vehicleno = $_REQUEST['vehicleno'];
    $unitno = $_REQUEST['unitno'];
    $SDate = $_REQUEST['SDate'];
    $STime = $_REQUEST['STime'];
    $batch = $_REQUEST['batch'];
    $workey = $_REQUEST['workkey'];
    $sel_master = $_REQUEST['sel_master'];
    $customerno = $_REQUEST['customerno'];
    $today = date('Y-m-d H:i:s');
    $day = date('Y-m-d', strtotime($SDate));

    $_SESSION['customerno'] = $customerno;

    if (!empty($sel_master) && !empty($vehicleno) && !empty($unitno) && !empty($SDate) && !empty($STime) && !empty($batch) && !empty($workey)) {

        $probityformdata = array(
            'vehicleno' => $vehicleno,
            'unitno' => $unitno,
            'startdate' => $SDate,
            'starttime' => $STime,
            'batch' => $batch,
            'workey' => $workey,
            'selmaster' => $sel_master,
            'customerno' => $customerno
        );
        $vm = new VehicleManager($customerno);

        if ($sel_master != '0') {
            $static = $vm->get_static_sqlite($sel_master);
        }
        $locationstatic = "../../customer/$customerno/$static->master";


        if (!file_exists($locationstatic)) {
            $arr_p['message'] = $static->master . ".sqlite file not exists";
            $arr_p['status'] = "failure";
        } else {
            $vm = new VehicleManager($customerno);
            $insertprobity_formdata = $vm->insertprobityform_entry($probityformdata);
            $path = "sqlite:$locationstatic";
            $db = new PDO($path);
            $Query = "SELECT * FROM unithistory order by fid ASC";
            $result = $db->query($Query);

            if ($SDate != '' || $STime != '') {
                $starttime = $SDate . ' ' . $STime;
                $starttime = date('Y-m-d H:i:s', strtotime($starttime));
            }

            $record = new VOProbity();
            $i = 1;
            $datacount = array();

            foreach ($result as $row) {
                $datacount[] = $i;
                $record->uhid = $row['uhid'];
                $record->uid = $row['uid'];
                $record->fid = $row['fid'];
                $record->unitno = $unitno;
                $record->vehicleno = $vehicleno;
                $record->curspeed = getCurspeed($locationstatic, $row['lastupdated']);
                $record->firstodometer = getOdometerFirst($locationstatic);
                $record->lastodometer = getOdometer($locationstatic, $row['lastupdated']);
                $record->cgeolat = GetLat($locationstatic, $row['fid']);
                $record->cgeolong = GetLong($locationstatic, $row['fid']);
                $record->direction = GetDirection($locationstatic, $row['fid']);

                $addedlasttime = date('Y-m-d h:i:s', strtotime('+' . $record->fid . ' minutes', strtotime($starttime)));   //unitdate increment

                if (isset($record->fid)) {
                    $record->distance = ($record->lastodometer - $record->firstodometer) / 1000;
                    $record->datetime = date('m/d/Y h:i:s A', strtotime($addedlasttime));
                    $vm = new VehicleManager($customerno);
                    $record->manufacturer = 'ELIXITECH';
                    $record->workkey = $workey;
                    $record->batchno = $batch;
                    $record->createddate = date('m/d/Y h:i:s A', strtotime($addedlasttime));
                    $insertprobity_testingdata = $vm->insertprobitytestingdata($record);

                    // live data send by url
                    /*
                      $url = "http://203.129.224.98:8080/LiveWorks_Mumbai/LineDataCollector?vno=".urlencode($record->vehicleno) ."&lat=" . urlencode($record->cgeolat). "&long=" . urlencode($record->cgeolong) ."&workkey=" . urldecode($record->workkey) ."&imei=" . urlencode($record->unitno) ."&direction=" . urlencode($record->direction) ."&distance=" . urlencode($record->distance) ."&velocity=" . urlencode($record->curspeed) ."&datetime=" . urlencode($record->datetime) ."&loadno=" . urlencode($record->batchno)."&createddate=".urlencode($record->createddate)."&manufacurer=ELIXITECH";
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $url);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                      $result = curl_exec($ch);
                      //var_dump($result);
                      curl_close($ch);
                     *
                     */
                }

                $i++;
            }
            $arr_p['datacount'] = count($datacount);
            $arr_p['starttime'] = $starttime;
            $arr_p['message'] = " Sucessfully inserted";
            $arr_p['status'] = "sucess";
        }
    }
} elseif ($action == 'editaction') {
    //for edit probity add data from staticmaster to unitsqlite file
    $vehicleid = $_REQUEST['vehicleid'];
    $vehicleno = $_REQUEST['vehicleno'];
    $SDate = $_REQUEST['SDate'];
    $STime = $_REQUEST['STime'];
    $sel_master = $_REQUEST['sel_master'];
    $customerno = $_REQUEST['customerno'];
    $today = date('Y-m-d H:i:s');
    $um = new UnitManager($customerno);
    $unitno = $um->getunitno($vehicleid);
    $arr_p = array();
    $day = date('Y-m-d', strtotime($SDate));
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$day.sqlite";
    //$DB = new PDO($path);
    if (!file_exists($location)) {
        //die('if here');
      $unitnofile = '../../customer/' . $customerno . '/unitno/' . $unitno . '/';
        if (!file_exists($unitnofile)) {
            mkdir("../../customer/" . $customerno . "/unitno/" . $unitno, 0777);
            $sqlitefolder = '../../customer/' . $customerno . '/unitno/' . $unitno . '/sqlite/';
            if (!file_exists($sqlitefolder)) {
                mkdir("../../customer/" . $customerno . "/unitno/" . $unitno . "/sqlite", 0777);

            }
        }
        $path = "sqlite:$location";
        $db = new PDO($path);
        $tables='';
        $tables .= ' CREATE TABLE IF NOT EXISTS data (dataid INTEGER,data TEXT,client TEXT,PRIMARY KEY(dataid)); ';
	$tables .= ' CREATE TABLE IF NOT EXISTS unithistory (uhid INTEGER,uid INTEGER,unitno TEXT,customerno INTEGER,vehicleid INTEGER,';
	$tables .= ' analog1 TEXT,analog2 TEXT,analog3 TEXT,analog4 TEXT,digitalio TEXT,lastupdated datetime,commandkey TEXT,commandkeyval TEXT,PRIMARY KEY (uhid)); ';
	$tables .= ' CREATE TABLE IF NOT EXISTS devicehistory(id INTEGER,deviceid INTEGER,customerno INTEGER,devicelat TEXT,devicelong TEXT,';
	$tables .= ' lastupdated datetime,altitude INTEGER,directionchange TEXT,inbatt TEXT,hwv TEXT,swv TEXT,msgid TEXT, ';
	$tables .= ' uid INTEGER,status TEXT,ignition INTEGER,powercut INTEGER,tamper INTEGER,gpsfixed TEXT,`online/offline` INTEGER,gsmstrength INTEGER, ';
	$tables .= ' gsmregister INTEGER,gprsregister INTEGER,satv TEXT,PRIMARY KEY (id)); ';
	$tables .= ' CREATE TABLE IF NOT EXISTS vehiclehistory(vehiclehistoryid INTEGER,vehicleid INTEGER,vehicleno TEXT, ';
	$tables .= ' extbatt TEXT,odometer INTEGER,lastupdated datetime,curspeed INTEGER,customerno INTEGER,driverid INTEGER,uid TEXT,PRIMARY KEY (vehiclehistoryid)); ';
        $tables .= ' CREATE INDEX IF NOT EXISTS `fk_vehiclehistory` ON `vehiclehistory` (`lastupdated`); ';
        $tables .= ' CREATE INDEX IF NOT EXISTS `fk_unithistory` ON `unithistory` (`lastupdated`); ';
        $tables .= ' CREATE INDEX IF NOT EXISTS `fk_devicehistory` ON `devicehistory` (`lastupdated`); ';

        ChkSqlite($tables,$location);

        $vm = new VehicleManager($customerno);
        $insertrecords = $vm->insertprobity_entry($vehicleid, $vehicleno, $SDate, $STime, $sel_master, $customerno);
        $vehicledetails = $vm->getvehicledetail($unitno, $customerno);
        $uid = $vehicledetails['uid'];
        $deviceid = $vehicledetails['deviceid'];
        if ($sel_master != '0') {
            $static = $vm->get_static_sqlite($sel_master);
        }
        $locationstatic = "../../customer/$customerno/$static->master";

        $path = "sqlite:$locationstatic";
        $db = new PDO($path);
        $firstdate = $lastdate = '';
        $Query = "SELECT lastupdated FROM unithistory order by lastupdated ASC limit 1";
        $result = $db->query($Query);
        foreach ($result as $row) {
            $firstdate = $row['lastupdated'];
        }
        $Query = "SELECT lastupdated FROM unithistory order by lastupdated desc limit 1";
        $result = $db->query($Query);
        foreach ($result as $row) {
            $lastdate = $row['lastupdated'];
        }

        $time1 = strtotime($firstdate);
        $time2 = strtotime($lastdate);
        $timetoadd = $time2 - $time1;
        $minutes_to_add = round(abs($timetoadd) / 60);

        if ($SDate != '' || $STime != '') {
            $starttime = $SDate . ' ' . $STime;
            $unitstarttime = date('Y-m-d H:i:s', strtotime($starttime));
        }
        $time = new DateTime($unitstarttime);
        $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
        $unitenddate = $time->format('Y-m-d H:i:s');
        $path = "sqlite:$location";
        $db = new PDO($path);
       // $Query1 = "Delete FROM unithistory where lastupdated  between '" . $unitstarttime . "'  AND '" . $unitenddate . "' ";
        $Query1 = "Delete FROM unithistory ";
        //$Query2 = "Delete FROM vehiclehistory where lastupdated  between '" . $unitstarttime . "'  AND '" . $unitenddate . "' ";
        $Query2 = "Delete FROM vehiclehistory";
        //$Query3 = "Delete FROM devicehistory where lastupdated  between '" . $unitstarttime . "'  AND '" . $unitenddate . "' ";
        $Query3 = "Delete FROM devicehistory";
        $result1 = $db->query($Query1);
        $result2 = $db->query($Query2);
        $result3 = $db->query($Query3);
        $arr_p['unitstarttime'] = $unitstarttime;
        $arr_p['unitendtime'] = $unitenddate;

        $path = "sqlite:$locationstatic";
        $db = new PDO($path);
        $unitquery1 = "select * from unithistory order by lastupdated";
        $devicequery2 = "select * from devicehistory order by lastupdated";
        $vehiclequery3 = "select * from vehiclehistory order by lastupdated";

        $result1 = $db->query($unitquery1);
        if (isset($result1)) {
            $unithistcount = array();
            $i = 1;
            $path1 = "sqlite:$location";
            $db1 = new PDO($path1);
            foreach ($result1 as $row) {
                $uid = $uid;
                $unitno = $unitno;
                $vehicleid = $vehicleid;
                $analog1 = $row['analog1'];
                $analog2 = $row['analog2'];
                $analog3 = $row['analog3'];
                $analog4 = $row['analog4'];
                $digitalio = $row['digitalio'];
                $commandkey = $row['commandkey'];
                $commandkeyval = $row['commandkeyval'];
                //$msgkey = $row['msgkey'];
                //$flag = $row['flag'];
                //$fid = $row['fid'];

                $addedlasttime = date('Y-m-d H:i:s', strtotime('+' . $i . ' minutes', strtotime($unitstarttime)));   //unitdate increment
                $UnitQuery = "INSERT INTO unithistory (`uid`,`unitno`, `customerno`, `vehicleid`,`analog1`,`analog2`,`analog3`,`analog4`,`digitalio`,`lastupdated`,`commandkey`,`commandkeyval`)
                    VALUES ($uid ,'" . $unitno . "',  $customerno , $vehicleid , $analog1 , $analog2 , $analog3 , $analog4 , $digitalio ,'" . $addedlasttime . "', $commandkey , '" . $commandkeyval . "' ) ";
                $unitresult = $db1->query($UnitQuery);
                $unithistcount[] = $i;
                $i++;
            }
        }

        $result2 = $db->query($devicequery2);
        $devicecount = array();
        $i = 1;
        foreach ($result2 as $row) {
            $deviceid = $deviceid;
            $devicelat = $row['devicelat'];
            $devicelong = $row['devicelong'];
            $lastupdated = $row['lastupdated'];
            $altitude = $row['altitude'];
            $directionchange = $row['directionchange'];
            $inbatt = $row['inbatt'];
            $hwv = $row['hwv'];
            $swv = $row['swv'];
            $msgid = $row['msgid'];
            $uid = $uid;
            $status = $row['status'];
            $ignition = $row['ignition'];
            $powercut = $row['powercut'];
            $tamper = $row['tamper'];
            $gpsfixed = $row['gpsfixed'];
            $online_offline = $row['online/offline'];
            $gsmstrength = $row['gsmstrength'];
            $gsmsregister = $row['gsmregister'];
            $gprsregister = $row['gprsregister'];
            $satv = $row['satv'];
            $addedlasttime = date('Y-m-d H:i:s', strtotime('+' . $i . ' minutes', strtotime($unitstarttime)));
            $path2 = "sqlite:$location";
            $db2 = new PDO($path2);
            $deviceQuery = "INSERT INTO devicehistory (`deviceid`, `customerno`, `devicelat`, `devicelong`,`lastupdated`,`altitude`,`directionchange`,`inbatt`,`hwv`,`swv`,`msgid`,`uid`
            ,`status`,`ignition`,`powercut`,`tamper`,`gpsfixed`,`online/offline`,`gsmstrength`,`gsmregister`,`gprsregister`,`satv`)
                    VALUES (" . $deviceid . "," . $customerno . "," . $devicelat . "," . $devicelong . ",'" . $addedlasttime . "'," . $altitude . ",'" . $directionchange . "','" . $inbatt . "','" . $hwv . "','" . $swv . "','" . $msgid . "'," . $uid . "
            ,'" . $status . "'," . $ignition . "," . $powercut . "," . $tamper . ",'" . $gpsfixed . "'," . $online_offline . "," . $gsmstrength . "," . $gsmsregister . ",'" . $gprsregister . "'," . $satv . ") ";
            $deviceresult = $db2->query($deviceQuery);
            $devicecount[] = $i;
            $i++;
        }


        $result3 = $db->query($vehiclequery3);
        $vehiclescount = array();

        $i = 1;
        foreach ($result3 as $row) {
            $vehicleid = $vehicleid;
            $vehicleno = $vehicleno;
            $extbatt = $row['extbatt'];
            $odometer = $row['odometer'];
            $lastupdated = $row['lastupdated'];
            $curspeed = $row['curspeed'];
            $driverid = $row['driverid'];
            $uid = $uid;

            $addedlasttime = date('Y-m-d H:i:s', strtotime('+' . $i . ' minutes', strtotime($unitstarttime)));
            $path3 = "sqlite:$location";
            $db3 = new PDO($path3);
            $vehicleQuery = "INSERT INTO vehiclehistory (`vehicleid`,`vehicleno`,`extbatt`,`odometer`,`lastupdated`,`curspeed`,`customerno`,`driverid`,`uid`)
                     VALUES (" . $vehicleid . ",'" . $vehicleno . "'," . $extbatt . "," . $odometer . ",'" . $addedlasttime . "'," . $curspeed . "," . $customerno . "," . $driverid . "," . $uid . ") ";
            $vehicleresult = $db3->query($vehicleQuery);
            $vehiclescount[] = $i;
            $i++;
        }

        $arr_p['vehiclecount'] = count($vehiclescount);
        $arr_p['devicecount'] = count($devicecount);
        $arr_p['unitcount'] = count($unithistcount);
        $arr_p['message'] = $day . ".sqlite sucessfully updated";
        $arr_p['status'] = "sucess";

    } else {
        //die('else here');
        $vm = new VehicleManager($customerno);
        $insertrecords = $vm->insertprobity_entry($vehicleid, $vehicleno, $SDate, $STime, $sel_master, $customerno);
        $vehicledetails = $vm->getvehicledetail($unitno, $customerno);
        $uid = $vehicledetails['uid'];
        $deviceid = $vehicledetails['deviceid'];
        if ($sel_master != '0') {
            $static = $vm->get_static_sqlite($sel_master);
        }
        $locationstatic = "../../customer/$customerno/$static->master";

        $path = "sqlite:$locationstatic";
        $db = new PDO($path);
        $firstdate = $lastdate = '';
        $Query = "SELECT lastupdated FROM unithistory order by lastupdated ASC limit 1";
        $result = $db->query($Query);
        foreach ($result as $row) {
            $firstdate = $row['lastupdated'];
        }
        $Query = "SELECT lastupdated FROM unithistory order by lastupdated desc limit 1";
        $result = $db->query($Query);
        foreach ($result as $row) {
            $lastdate = $row['lastupdated'];
        }

        $time1 = strtotime($firstdate);
        $time2 = strtotime($lastdate);
        $timetoadd = $time2 - $time1;
        $minutes_to_add = round(abs($timetoadd) / 60);

        if ($SDate != '' || $STime != '') {
            $starttime = $SDate . ' ' . $STime;
            $unitstarttime = date('Y-m-d H:i:s', strtotime($starttime));
        }
        $time = new DateTime($unitstarttime);
        $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
        $unitenddate = $time->format('Y-m-d H:i:s');
        $path = "sqlite:$location";
        $db = new PDO($path);
        $Query1 = "Delete FROM unithistory where lastupdated  between '" . $unitstarttime . "'  AND '" . $unitenddate . "' ";
        //$Query1 = "Delete FROM unithistory ";
        $Query2 = "Delete FROM vehiclehistory where lastupdated  between '" . $unitstarttime . "'  AND '" . $unitenddate . "' ";
        //$Query2 = "Delete FROM vehiclehistory";
        $Query3 = "Delete FROM devicehistory where lastupdated  between '" . $unitstarttime . "'  AND '" . $unitenddate . "' ";
        //$Query3 = "Delete FROM devicehistory";
        $result1 = $db->query($Query1);
        $result2 = $db->query($Query2);
        $result3 = $db->query($Query3);
        $arr_p['unitstarttime'] = $unitstarttime;
        $arr_p['unitendtime'] = $unitenddate;

        $path = "sqlite:$locationstatic";
        $db = new PDO($path);
        $unitquery1 = "select * from unithistory order by lastupdated";
        $devicequery2 = "select * from devicehistory order by lastupdated";
        $vehiclequery3 = "select * from vehiclehistory order by lastupdated";

        $result1 = $db->query($unitquery1);
        if (isset($result1)) {
            $unithistcount = array();
            $i = 1;
            $path1 = "sqlite:$location";
            $db1 = new PDO($path1);
            foreach ($result1 as $row) {
                $uid = $uid;
                $unitno = $unitno;
                $vehicleid = $vehicleid;
                $analog1 = $row['analog1'];
                $analog2 = $row['analog2'];
                $analog3 = $row['analog3'];
                $analog4 = $row['analog4'];
                $digitalio = $row['digitalio'];
                $commandkey = $row['commandkey'];
                $commandkeyval = $row['commandkeyval'];
                //$msgkey = $row['msgkey'];
                //$flag = $row['flag'];
                //$fid = $row['fid'];

                $addedlasttime = date('Y-m-d H:i:s', strtotime('+' . $i . ' minutes', strtotime($unitstarttime)));   //unitdate increment
                $UnitQuery = "INSERT INTO unithistory (`uid`,`unitno`, `customerno`, `vehicleid`,`analog1`,`analog2`,`analog3`,`analog4`,`digitalio`,`lastupdated`,`commandkey`,`commandkeyval`)
                    VALUES ($uid ,'" . $unitno . "',  $customerno , $vehicleid , $analog1 , $analog2 , $analog3 , $analog4 , $digitalio ,'" . $addedlasttime . "', $commandkey , '" . $commandkeyval . "' ) ";
                $unitresult = $db1->query($UnitQuery);
                $unithistcount[] = $i;
                $i++;
            }
        }

        $result2 = $db->query($devicequery2);
        $devicecount = array();
        $i = 1;
        foreach ($result2 as $row) {
            $deviceid = $deviceid;
            $devicelat = $row['devicelat'];
            $devicelong = $row['devicelong'];
            $lastupdated = $row['lastupdated'];
            $altitude = $row['altitude'];
            $directionchange = $row['directionchange'];
            $inbatt = $row['inbatt'];
            $hwv = $row['hwv'];
            $swv = $row['swv'];
            $msgid = $row['msgid'];
            $uid = $uid;
            $status = $row['status'];
            $ignition = $row['ignition'];
            $powercut = $row['powercut'];
            $tamper = $row['tamper'];
            $gpsfixed = $row['gpsfixed'];
            $online_offline = $row['online/offline'];
            $gsmstrength = $row['gsmstrength'];
            $gsmsregister = $row['gsmregister'];
            $gprsregister = $row['gprsregister'];
            $satv = $row['satv'];
            $addedlasttime = date('Y-m-d H:i:s', strtotime('+' . $i . ' minutes', strtotime($unitstarttime)));
            $path2 = "sqlite:$location";
            $db2 = new PDO($path2);
            $deviceQuery = "INSERT INTO devicehistory (`deviceid`, `customerno`, `devicelat`, `devicelong`,`lastupdated`,`altitude`,`directionchange`,`inbatt`,`hwv`,`swv`,`msgid`,`uid`
            ,`status`,`ignition`,`powercut`,`tamper`,`gpsfixed`,`online/offline`,`gsmstrength`,`gsmregister`,`gprsregister`,`satv`)
                    VALUES (" . $deviceid . "," . $customerno . "," . $devicelat . "," . $devicelong . ",'" . $addedlasttime . "'," . $altitude . ",'" . $directionchange . "','" . $inbatt . "','" . $hwv . "','" . $swv . "','" . $msgid . "'," . $uid . "
            ,'" . $status . "'," . $ignition . "," . $powercut . "," . $tamper . ",'" . $gpsfixed . "'," . $online_offline . "," . $gsmstrength . "," . $gsmsregister . ",'" . $gprsregister . "'," . $satv . ") ";
            $deviceresult = $db2->query($deviceQuery);
            $devicecount[] = $i;
            $i++;
        }


        $result3 = $db->query($vehiclequery3);
        $vehiclescount = array();

        $i = 1;
        foreach ($result3 as $row) {
            $vehicleid = $vehicleid;
            $vehicleno = $vehicleno;
            $extbatt = $row['extbatt'];
            $odometer = $row['odometer'];
            $lastupdated = $row['lastupdated'];
            $curspeed = $row['curspeed'];
            $driverid = $row['driverid'];
            $uid = $uid;

            $addedlasttime = date('Y-m-d H:i:s', strtotime('+' . $i . ' minutes', strtotime($unitstarttime)));
            $path3 = "sqlite:$location";
            $db3 = new PDO($path3);
            $vehicleQuery = "INSERT INTO vehiclehistory (`vehicleid`,`vehicleno`,`extbatt`,`odometer`,`lastupdated`,`curspeed`,`customerno`,`driverid`,`uid`)
                     VALUES (" . $vehicleid . ",'" . $vehicleno . "'," . $extbatt . "," . $odometer . ",'" . $addedlasttime . "'," . $curspeed . "," . $customerno . "," . $driverid . "," . $uid . ") ";
            $vehicleresult = $db3->query($vehicleQuery);
            $vehiclescount[] = $i;
            $i++;
        }

        $arr_p['vehiclecount'] = count($vehiclescount);
        $arr_p['devicecount'] = count($devicecount);
        $arr_p['unitcount'] = count($unithistcount);
        $arr_p['message'] = $day . ".sqlite sucessfully updated";
        $arr_p['status'] = "sucess";
    }
} elseif ($action == 'deleteprobity') {
    //for edit probity add data from staticmaster to unitsqlite file
    $vehicleid = $_REQUEST['vehicleid'];
    $vehicleno = $_REQUEST['vehicleno'];
    $SDate = $_REQUEST['SDate'];
    $STime = $_REQUEST['STime'];
    $EDate = $_REQUEST['SDate'];
    $ETime = $_REQUEST['ETime'];
    $customerno = $_REQUEST['customerno'];
    $today = date('Y-m-d H:i:s');
    $um = new UnitManager($customerno);
    $unitno = $um->getunitno($vehicleid);
    $arr_p = array();
    $day = date('Y-m-d', strtotime($SDate));
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$day.sqlite";
    if (!file_exists($location)) {
        $arr_p['message'] = $day . ".sqlite file not exists";
        $arr_p['status'] = "failure";
    } else {
        $vm = new VehicleManager($customerno);
        // $insertrecords = $vm->insertprobity_entry($vehicleid, $vehicleno, $SDate, $STime, $sel_master, $customerno);
        $vehicledetails = $vm->getvehicledetail($unitno, $customerno);
        $uid = $vehicledetails['uid'];
        $deviceid = $vehicledetails['deviceid'];
        if ($SDate != '' || $STime != '') {
            $starttime = $SDate . ' ' . $STime;
            $unitstarttime = date('Y-m-d H:i:s', strtotime($starttime));
        }
        if ($EDate != '' || $ETime != '') {
            $endtime = $EDate . ' ' . $ETime;

            $unitendtime = date('Y-m-d H:i:59', strtotime($endtime));
        }

        $path = "sqlite:$location";
        $db = new PDO($path);
        $Query1 = "Delete FROM unithistory where lastupdated  between '" . $unitstarttime . "'  AND '" . $unitendtime . "' ";
        $Query2 = "Delete FROM vehiclehistory where lastupdated  between '" . $unitstarttime . "'  AND '" . $unitendtime . "' ";
        $Query3 = "Delete FROM devicehistory where lastupdated  between '" . $unitstarttime . "'  AND '" . $unitendtime . "' ";
        $result1 = $db->query($Query1);
        $result2 = $db->query($Query2);
        $result3 = $db->query($Query3);
        $arr_p['unitstarttime'] = $unitstarttime;
        $arr_p['unitendtime'] = $unitendtime;
        $arr_p['message'] = $day . ".sqlite sucessfully deleted data from " . $unitstarttime . " to " . $unitendtime;
        $arr_p['status'] = "sucess";
    }
}
echo json_encode($arr_p);


function ChkSqlite($tables,$location)
{
    $path = "sqlite:$location";
    $db = new PDO($path);
    $db->exec('BEGIN IMMEDIATE TRANSACTION');
    $db->exec($tables);
    $db->exec('COMMIT TRANSACTION');
}



function getOdometer($location, $date) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM vehiclehistory where lastupdated >= '$date' Order by lastupdated ASC Limit 1 ";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['odometer'];
        }
    } else {
        return 0;
    }
}

function getVehicle($location, $uid) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT vehicleno FROM vehiclehistory  limit 1";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['vehicleno'];
        }
    } else {
        return 0;
    }
}

function getCurspeed($location, $lastupdated) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT curspeed FROM vehiclehistory where lastupdated >= '$lastupdated' order by lastupdated ASC limit 1";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['curspeed'];
        }
    } else {
        return 0;
    }
}

function getOdometerFirst($location) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM vehiclehistory Order by vehiclehistoryid ASC Limit 1 ";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['odometer'];
        }
    } else {
        return 0;
    }
}

function GetLat($location, $did) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM devicehistory where did = '$did' Limit 1 ";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['devicelat'];
        }
    } else {
        return 0;
    }
}

function GetLong($location, $did) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM devicehistory where did = '$did'  Limit 1 ";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['devicelong'];
        }
    } else {
        return 0;
    }
}

function GetDirection($location, $did) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM devicehistory where did = '$did'  Limit 1 ";

    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['directionchange'];
        }
    } else {
        return 0;
    }
}
?>

