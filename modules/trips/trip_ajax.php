<?php
error_reporting(E_ALL);
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
require_once '../../modules/reports/reports_route_functions.php';
require_once 'trips_function.php';
$customerno = exit_issetor($_SESSION['customerno'], 'Please login');
$userid = exit_issetor($_SESSION['userid'], 'Please login');
$action = ri($_REQUEST['action']);
if (!defined('EFLEET_URL')) {
    if ($_SESSION['customerno'] == speedConstants::CUSTNO_SAFEANDSECURE) {
        define("EFLEET_URL", "http://115.124.100.86/GetTripLogDetails/service.asmx?WSDL");
    } elseif ($_SESSION['customerno'] == speedConstants::CUSTNO_RKFOODLANDS) {
        define("EFLEET_URL", "http://43.240.65.103/GetTripLogDetails/Service.asmx?WSDL");
    }
}
if ($action == 'addtripstatus') {
    $statusname = ri($_REQUEST['statusname']);
    if ($statusname == "") {
        failure('Please enter Status name');
        exit;
    } else {
        $mob = new Trips($customerno, $userid);
        if ($mob->is_status_exists($statusname)) {
            echo failure('Status already exists');
            exit;
        }
        $mob->add_statusdata($statusname);
        echo success('Status added sucessfully');
        exit;
    }
} elseif ($action == "getunloadingdata") {
    //statusid="+statusid+"&tripid="+tripid+"&sdate="+sdate+"&stime="+stime+"&action
    $statusid = $_REQUEST['statusid'];
    $tripid = $_REQUEST['tripid'];
    $sdate = $_REQUEST['sdate'];
    $stime = $_REQUEST['stime'];
    $enddate1 = $sdate . " " . $stime;
    $enddate = date("Y-m-d H:i:s", strtotime($enddate1));
    $mob = new Trips($_SESSION["customerno"], $_SESSION['userid']);
    $tripstartdetails = $mob->closedtripdetails_start($tripid);
    $startdate = $tripstartdetails[0]['tripstart_date'];
    $actualhrs = round((strtotime($enddate) - strtotime($startdate)) / (60 * 60));
    $data = array(
        "actualhrs" => $actualhrs
    );
    echo json_encode($data);
    exit;
} elseif ($action == "addtripdetails") {
    $data = array();
    $statusodometer = 0;
    $data['vehicleno'] = ri($_REQUEST['vehicleno'], '');
    $data['vehicleid'] = ri($_REQUEST['vehicleid']);
    $data['triplogno'] = ri($_REQUEST['triplogno']);
    $data['tripstatus'] = isset($_REQUEST['tripstatus']) ? ri($_REQUEST['tripstatus']) : 3;
    if ($_REQUEST['tripstatus'] == '') {
        $data['tripstatus'] = 3;
    } else {
        $data['tripstatus'] = ri($_REQUEST['tripstatus']);
    }
    $data['sdate'] = ri($_REQUEST['SDate'], date('Y-m-d'));
    $data['stime'] = isset($data['sdate']) ? ri($_REQUEST['STime']) : "";
    $data['statusdatetime'] = isset($data['sdate']) ? date('Y-m-d H:i:s', strtotime($data['sdate'] . " " . $data['stime'])) : date('Y-m-d H:i:s');
    $data['routename'] = ri($_REQUEST['routename'], '');
    $data['budgetedkms'] = ri($_REQUEST['budgetedkms'], '0');
    $data['budgetedhrs'] = ri($_REQUEST['budgetedhrs'], '0');
    $data['consignor'] = ri($_REQUEST['consignor'], '');
    $data['consignee'] = ri($_REQUEST['consignee'], '');
    $data['consignorid'] = ri($_REQUEST['consignorid'], '');
    $data['consigneeid'] = ri($_REQUEST['consigneeid'], '');
    $data['billingparty'] = ri($_REQUEST['billingparty'], '');
    $data['mintemp'] = ri($_REQUEST['mintemp'], '');
    $data['maxtemp'] = ri($_REQUEST['maxtemp'], '');
    $data['drivername'] = ri($_REQUEST['drivername'], '');
    $data['drivermobile1'] = ri($_REQUEST['drivermobile1'], '');
    $data['drivermobile2'] = ri($_REQUEST['drivermobile2'], '');
    $data['remark'] = ri($_REQUEST['remark'], '');
    $data['perdaykm'] = ri($_REQUEST['perdaykm'], '');
    $data['temp_sensors'] = ri($_SESSION['temp_sensors'], '');
    $etarrivaldate = ri($_REQUEST['etarrivaldate'], '');
    $data['materialtype'] = ri($_REQUEST['materialtype'], '0');
    $data['etarrivaldate'] = date('Y-m-d', strtotime($etarrivaldate));
    if ($data['etarrivaldate'] != '1970-01-01') {
        $data['etarrivaldate'] = $data['etarrivaldate'];
    } else {
        $data['etarrivaldate'] = '';
    }
    //Get data from From Unit Sqlite
    $objDate = new DateTime($data['sdate']);
    $currentDay = $objDate->format('Y-m-d');
    $objUnitManager = new UnitManager($customerno);
    $unitno = $objUnitManager->getunitno($data['vehicleid']);
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$currentDay.sqlite";
    $data['unitno'] = $unitno;
    $data['customerno'] = $customerno;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $statusodometerquery = "SELECT odometer FROM vehiclehistory WHERE time(lastupdated) >= '" . $data['stime'] . "' ORDER BY lastupdated ASC LIMIT 1;";
        $result = $db->query($statusodometerquery);
        $arrQueryResult = $result->fetchAll();
        $statusodometer = $arrQueryResult[0]['odometer'];
    }
    $data['statusodometer'] = $statusodometer;
    if ($data['vehicleno'] == "") {
        failure('Please enter vehicle No.');
        exit;
    } else {
        $mob = new Trips($customerno, $userid);
        if ($mob->is_tripend_check($data['vehicleid'])) {
            $vehicleno = $data["vehicleno"];
            echo failure('Vehicle ' . $vehicleno . ' trip not ended yet.');
            exit;
        }
        $mob->add_tripdetails($data);
        echo success('Trip Details added sucessfully');
        exit;
    }
} elseif ($action == "edittripdetails") {
    $data = array();
    $statusodometer = 0;
    $data['tripid'] = ri($_REQUEST['tripid']);
    $data['vehicleno'] = ri($_REQUEST['vehicleno']);
    $data['vehicleid'] = ri($_REQUEST['vehicleid']);
    $data['groupid'] = ri($_REQUEST['groupid']);
    $data['triplogno'] = ri($_REQUEST['triplogno']);
    $data['tripstatus'] = ri($_REQUEST['tripstatus']);
    $data['sdate'] = ri($_REQUEST['SDate']);
    $data['stime'] = ri($_REQUEST['STime']);
    $data['routename'] = ri($_REQUEST['routename']);
    $data['budgetedkms'] = ri($_REQUEST['budgetedkms']);
    $data['budgetedhrs'] = ri($_REQUEST['budgetedhrs']);
    $data['consignor'] = ri($_REQUEST['consignor']);
    $data['consignee'] = ri($_REQUEST['consignee']);
    $data['consignorid'] = ri($_REQUEST['consignorid']);
    $data['consigneeid'] = ri($_REQUEST['consigneeid']);
    $data['billingparty'] = ri($_REQUEST['billingparty']);
    $data['mintemp'] = ri($_REQUEST['mintemp']);
    $data['maxtemp'] = ri($_REQUEST['maxtemp']);
    $data['drivername'] = ri($_REQUEST['drivername']);
    $data['drivermobile1'] = ri($_REQUEST['drivermobile1']);
    $data['drivermobile2'] = ri($_REQUEST['drivermobile2']);
    $data['remark'] = ri($_REQUEST['remark']);
    $data['perdaykm'] = ri($_REQUEST['perdaykm']);
    $data['istripend'] = ri($_REQUEST['istripend']);
    $data['actualkms'] = ri($_REQUEST['actualkms']);
    $data['actualhrs'] = ri($_REQUEST['actualhrs']);
    $data['returnYard'] = ri($_REQUEST['returnYard']);
    $tripid = $_REQUEST['tripid'];
    $tripstatus = $_REQUEST['tripstatus'];
    $istripend = ri($_REQUEST['istripend']);
    $today = date('Y-m-d H:i:s');
    $etarrivaldate = ri($_REQUEST['etarrivaldate'], '');
    $data['materialtype'] = ri($_REQUEST['materialtype'], '0');
    $data['etarrivaldate'] = date('Y-m-d', strtotime($etarrivaldate));
    foreach ($_REQUEST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 9) == "to_group_") {
            $Users[] = substr($single_post_name, 9, 10);
        }
    }
    if (isset($_REQUEST['oldUsers']) && !empty($_REQUEST['oldUsers'])) {
        foreach ($_REQUEST['oldUsers'] as $oldUser) {
            $oldUsers[] = $oldUser;
        }
    }
    if (isset($Users) && !empty($Users)) {
        $data['users'] = $Users;
    } else {
        $data['users'] = "";
    }
    if (isset($oldUsers) && !empty($oldUsers)) {
        $data['oldUsers'] = $oldUsers;
    } else {
        $data['oldUsers'] = "";
    }
    if ($istripend == 1 && $tripstatus == 10) {
        $data['tripstatus'] = $tripstatus;
        $endtripdate = ri($_REQUEST['SDate']);
        $endtriptime = ri($_REQUEST['STime']);
        $statusdatetimeedit = $endtripdate . " " . $endtriptime;
        $statusdatetimeedit = date('Y-m-d H:i:s', strtotime($statusdatetimeedit));
        $mob = new Trips($customerno, $userid);
        $tripstartdata = $mob->closedtripdetails_start($tripid);
        $tripstart_date = $tripstartdata[0]['tripstart_date'];
        $actualhrs = round((strtotime($statusdatetimeedit) - strtotime($tripstart_date)) / (60 * 60));
        $data['actualhrs'] = $actualhrs;
    } elseif ($istripend == 1 && $tripstatus != 10) {
        $data['tripstatus'] = 10;
        $endtripdate = ri($_REQUEST['SDate']);
        $endtriptime = ri($_REQUEST['STime']);
        $statusdatetimeedit = $endtripdate . " " . $endtriptime;
        $statusdatetimeedit = date('Y-m-d H:i:s', strtotime($statusdatetimeedit));
        $mob = new Trips($customerno, $userid);
        $tripstartdata = $mob->closedtripdetails_start($tripid);
        $tripstart_date = $tripstartdata[0]['tripstart_date'];
        $actualhrs = round((strtotime($statusdatetimeedit) - strtotime($tripstart_date)) / (60 * 60));
        $data['actualhrs'] = $actualhrs;
    }
    $objDate = new DateTime($data['sdate']);
    $currentDay = $objDate->format('Y-m-d');
    $objUnitManager = new UnitManager($customerno);
    $unitno = $objUnitManager->getunitno($data['vehicleid']);
    $data['unitno'] = $unitno;
    $data['customerno'] = $customerno;
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$currentDay.sqlite";
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $statusodometerquery = "SELECT odometer FROM vehiclehistory WHERE time(lastupdated) >= '" . $data['stime'] . "' ORDER BY lastupdated ASC LIMIT 1;";
        $result = $db->query($statusodometerquery);
        if ($result) {
            $arrQueryResult = $result->fetchAll();
            $statusodometer = $arrQueryResult[0]['odometer'];
        } else {
            $statusodometer = 0;
        }
    }
    $data['statusodometer'] = $statusodometer;
    if ($customerno != '447') {
        if ($_REQUEST['vehicleno'] == "" || $data['tripid'] == "") {
            failure('Please enter vehicle no.');
            exit;
        } elseif ($_REQUEST['consignorid'] == "" || $_REQUEST['consigneeid'] == "") {
            failure('Please enter consignor or consignee name.');
            exit;
        } else {
            $mob = new Trips($customerno, $userid);
            $mob->edittripdetails($data);
            echo success('Trip Details updated sucessfully');
            exit;
        }
    } else {
        $mob = new Trips($customerno, $userid);
        $mob->edittripdetails($data);
        echo success('Trip Details updated sucessfully');
        exit;
    }
} elseif ($action == 'addconsignee') {
    $consigneename = ri($_REQUEST['consigneename']);
    $cemail = ri($_REQUEST['cemail']);
    $cphone = ri($_REQUEST['cphone']);
    $mob = new Trips($customerno, $userid);
    if ($consigneename == "") {
        echo failure('Please enter Consignee name');
        exit;
    } elseif ($mob->is_consignee_exists($consigneename)) {
        echo failure('Consignee already exists.');
        exit;
    } else {
        $mob->add_consigneedata($consigneename, $cemail, $cphone);
        echo success('Consignee added sucessfully');
        exit;
    }
} elseif ($action == 'addconsigneer') {
    $consigneename = ri($_REQUEST['consigneername']);
    $cemail = ri($_REQUEST['cremail']);
    $cphone = ri($_REQUEST['crphone']);
    $mob = new Trips($customerno, $userid);
    if ($consigneename == "") {
        echo failure('Please enter Consignor name');
        exit;
    } elseif ($mob->is_consignor_exists($consigneename)) {
        echo failure('Consigner already exists.');
        exit;
    } else {
        $mob->add_consignordata($consigneename, $cemail, $cphone);
        echo success('Consigor added sucessfully');
        exit;
    }
} elseif ($action == 'edittripstatus') {
    $statusname = ri($_REQUEST['statusname']);
    $statusid = (int) ri($_REQUEST['statusid']);
    if ($statusname == "" || $statusid == "") {
        failure('Please enter Status');
        exit;
    } else {
        $mob = new Trips($customerno, $userid);
        $mob->update_tripstatusdata($statusname, $statusid);
        echo success('Status Updated sucessfully');
        exit;
    }
} elseif ($action == 'editconsignee') {
    $consigneename = ri($_REQUEST['consigneename']);
    $cemail = ri($_REQUEST['cemail']);
    $cphone = ri($_REQUEST['cphone']);
    $consid = (int) ri($_REQUEST['consid']);
    if ($consigneename == "" || $consid == "") {
        failure('Please enter Consignee Name');
        exit;
    } else {
        $mob = new Trips($customerno, $userid);
        $mob->update_consignee($consigneename, $cemail, $cphone, $consid);
        echo success(' Consignee Updated sucessfully');
        exit;
    }
} elseif ($action == 'editconsignor') {
    $consigneename = ri($_REQUEST['consigneername']);
    $cemail = ri($_REQUEST['cremail']);
    $cphone = ri($_REQUEST['crphone']);
    $consid = (int) ri($_REQUEST['consrid']);
    if ($consigneename == "" || $consid == "") {
        failure('Please Enter Consignor Name');
        exit;
    } else {
        $mob = new Trips($customerno, $userid);
        $mob->update_consignor($consigneename, $cemail, $cphone, $consid);
        echo success(' Consignor Updated sucessfully');
        exit;
    }
} elseif ($action == 'triphistajax') {
    $id = (int) ri($_REQUEST['tripid']);
    $mob = new Trips($customerno, $userid);
    $resulteditdata = $mob->gettripdetailshistory($id);
    $data = array();
    if (isset($resulteditdata) && !empty($resulteditdata)) {
        foreach ($resulteditdata as $row) {
            $devicelat = $row->devicelat;
            $devicelong = $row->devicelong;
            $location = "";
            if ($devicelat != "" && $devicelong != "") {
                $location = getlocation($devicelat, $devicelong, $customerno);
            }
            $statustime = date(speedConstants::DEFAULT_DATETIME, strtotime($row->statusdate));
            $data[] = array(
                "tripstatus" => $row->tripstatus,
                "statustime" => $statustime,
                "location" => $location
            );
        }
    }
    echo success_json($data);
    exit;
} elseif ($action == 'deltripstatus') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Trips($customerno, $userid);
    $mob->delete_tripstatusdata($id);
    echo success('Trip Status Deleted sucessfully');
    exit;
} elseif ($action == 'delconsignee') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Trips($customerno, $userid);
    $mob->delete_consignee($id);
    echo success('Consignee Deleted sucessfully');
    exit;
} elseif ($action == 'delconsignor') {
    $id = (int) ri($_REQUEST['id']);
    $mob = new Trips($customerno, $userid);
    $mob->delete_consignor($id);
    echo success('Consignor Deleted sucessfully');
    exit;
} elseif ($action == 'consignorauto') {
    $q = ri($_REQUEST['term']);
    $mob = new Trips($customerno, $userid);
    $result = $mob->getconsignorautotdata($q);
    echo json_encode($result);
    exit;
} elseif ($action == 'consigneeauto') {
    $q = ri($_REQUEST['term']);
    $mob = new Trips($customerno, $userid);
    $result = $mob->getconsigneeautotdata($q);
    echo json_encode($result);
    exit;
} elseif ($action == 'addconsignorpop') {
    $consigneename = ri($_REQUEST['consrname']);
    $cemail = ri($_REQUEST['consremail']);
    $cphone = ri($_REQUEST['consrmobile']);
    $mob = new Trips($customerno, $userid);
    if ($consigneename == "") {
        echo failure('Please enter Consignor name');
        exit;
    } elseif ($mob->is_consignor_exists($consigneename)) {
        echo failure('Consigner already exists.');
        exit;
    } else {
        $mob = new Trips($customerno, $userid);
        $mob->add_consignordata($consigneename, $cemail, $cphone);
        echo success('Consigor added sucessfully');
        exit;
    }
} elseif ($action == 'addconsigeepop') {
    $consname = ri($_REQUEST['consname']);
    $consemail = ri($_REQUEST['consemail']);
    $consmobile = ri($_REQUEST['consmobile']);
    $mob = new Trips($customerno, $userid);
    if ($consname == "") {
        echo failure('Please enter Consignee name');
        exit;
    } elseif ($mob->is_consignee_exists($consname)) {
        echo failure('Consignee already exists.');
        exit;
    } else {
        $mob->add_consigneedata($consname, $consemail, $consmobile);
        echo success('Consignee added sucessfully');
        exit;
    }
} elseif ($action == 'addtripstatuspop') {
    $statusname = ri($_REQUEST['tripstatus']);
    if ($statusname == "") {
        failure('Please enter Status name');
        exit;
    } else {
        $mob = new Trips($customerno, $userid);
        if ($mob->is_status_exists($statusname)) {
            echo failure('Status already exists');
            exit;
        }
        $mob->add_statusdata($statusname);
        echo success('Status added sucessfully');
        exit;
    }
} elseif ($action == 'getunloadingtime') {
    try {
        $triplogno = $_REQUEST['triplogno'];
        $customerno = $_SESSION['customerno'];
        $userid = $_SESSION['userid'];
        $unloadingendStatusId = 0;
        $unloadenddate = '';
        $unloadendtime = '';
        $isInsufficientData = 0;
        $client = new SoapClient(EFLEET_URL);
        $params = new stdClass();
        $params->Triplogno = $triplogno;
        $result = $client->eFleet_GetTripLogCloserDetails($params)->eFleet_GetTripLogCloserDetailsResult;
        $xml = simplexml_load_string($result->any);
        if (isset($xml) && $xml->children()->count() === 1) {
            foreach ($xml->children() as $children) {
                $objTripDetails = GetTripDetailsFromXml($children);
                if ($objTripDetails->unloadingstart != "" && $objTripDetails->unloadingend != "") {
                    $objTripManager = new Trips($customerno, $userid);
                    $objTripDetails->customerno = $customerno;
                    $objTripDetails->userid = $userid;
                    $objTripDetails->triplogno = $triplogno;
                    $objTripDetails = GetExistingTripDetails($objTripDetails);
                    /* Update the trips table with unload start trip details */
                    $objTripManager->edittripdetails((array) $objTripDetails);
                } else {
                    $isInsufficientData = 1;
                }
            }
            if ($isInsufficientData === 0) {
                //Get unload end details
                //Unload end status id and timestamp
                $unloadingendStatusId = '10';
                $unloadstartdatetime = new DateTime($objTripDetails->unloadingend);
                $unloadenddate = $unloadstartdatetime->format('d-m-Y');
                $unloadendtime = $unloadstartdatetime->format('H:i');
                $arrUnload = array(
                    "unloadingendStatusId" => $unloadingendStatusId,
                    "unloadenddate" => $unloadenddate,
                    "unloadendtime" => $unloadendtime
                );
                echo success_json($arrUnload);
            } else {
                echo failure('Insufficient data. Please try later.');
            }
        } else {
            echo failure('No trip details found from Efleet');
        }
    } catch (SoapFault $e) {
        //echo "<pre>SoapFault: " . print_r($e, true) . "</pre>\n";
        $errormsg = "Soap Exception occured.<br/>";
        $errormsg .= "Faultcode: " . $e->faultcode . "<br/>";
        $errormsg .= "Faultstring: " . $e->getMessage() . "";
        echo failure('Efleet Web service down. Message: '+$errormsg);
    } catch (Exception $e) {
        echo failure('Unknown Exception occured.');
    }
} elseif ($action == 'getStoppgeDetails') {
    $objTripManager = new Trips($customerno, $userid);
    $request = new stdClass();
    $request->customerno = $customerno;
    $request->currentDate = date('Y-m-d');
    $inTransitVehicles = $objTripManager->getInTransitVehicles($request);
    //print_r($tripDashboardData);die();
    $vRequest = new stdClass();
    $vRequest->customerno = $customerno;
    $vRequest->currentDate = date('Y-m-d');
    $inTrasitStoppageCount = 0;
    if (isset($inTransitVehicles) && !empty($inTransitVehicles)) {
        foreach ($inTransitVehicles as $vehK => $vehV) {
            $vRequest->vehicleid = $vehV;
            $allVehicleData = get_vehicle_details($vRequest);
            $userdate = date('Y-m-d');
            $unitno = isset($allVehicleData['unitno']) ? $allVehicleData['unitno'] : 0;
            $deviceid = isset($allVehicleData['deviceid']) ? $allVehicleData['deviceid'] : 0;
            $holdtime = 30;
            $Shour = '';
            $Ehour = '';
            $k = 0;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getstoppage_fromsqlite_trips($location, $deviceid, $holdtime, $Shour, $Ehour, $userdate);
                $inTrasitStoppageCount += (isset($data) && is_array($data) && !empty($data)) ? 1 : 0;
            }
        }
    }
    echo success_json($inTrasitStoppageCount);
    //echo success('Status added sucessfully');
    exit;
} elseif (isset($_POST['tripstatus'])) {
    $customerno = $_SESSION['customerno'];
    $tripstatusArray = get_tripstatus($_SESSION['customerno'], $_SESSION['userid']);
    $optionVal = "";
    if (isset($tripstatusArray) && !empty($tripstatusArray)) {
        $optionVal .= "<option value='-1'>Select Status</option>";
        foreach ($tripstatusArray as $tripstatusKey => $tripstatusVal) {
            $optionVal .= "<option value='" . $tripstatusVal["statusid"] . "'>" . $tripstatusVal["tripstatus"] . "</option>";
        }
    }
    echo json_encode($optionVal);
} elseif (isset($_POST['tripsdetails'])) {
    $gettriprecords = get_viewtriprecords_list($_SESSION['customerno'], $_SESSION['userid']); //get triprecord in json format
    echo json_encode($gettriprecords);
} elseif (isset($_POST['edittripid'])) {
/*haversineGreatCircleDistance('18.983695','72.848957','19.07649100','73.09068400');
exit;*/
//'18.983695', '72.848957', '19.07649100', '73.09068400'
    $postData = array();
    $tripid = $_POST['edittripid'];
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $modalid = $_POST['modalid'];
    $tripstatusArray = get_tripstatus($customerno, $userid);
    $getedittripdata = tripdetailsedit($customerno, $userid, $tripid);
    $getTripStatusId = $getedittripdata[0]['tripstatusid'];
    $tripdroppointdata = get_trip_droppoints($tripid);
    $getstatushistory = gettriphistory_status($customerno, $userid, $tripid);
    $isTripEnd = 0;
    $getTripLrDetails = getTripLrDetails($_SESSION['customerno'], $_SESSION['userid'], $tripid, $isTripEnd);
    foreach ($getedittripdata as $row) {
        $row['editetarrivaldate'] = isset($row['etarrivaldate']) ? date('d-m-Y', strtotime($row['etarrivaldate'])) : "";
        $row['materialtype'] = isset($row['materialtype']) ? $row['materialtype'] : 0;
        $row['esttime'] = $row['estimatedtime'];
        $row['actualkm'] = $row['actualkms'];
        $row['editconsignor'] = $row['consignor'];
        $row['editconsignee'] = $row['consignee'];
        $row['editvehicleno'] = $row['vehicleno'];
        if (!is_null($row['statusdate'])) {
            $row['editSDate'] = date('d-m-Y', strtotime($row['statusdate']));
            $row['editSTime'] = date('H:i', strtotime($row['statusdate']));
        } else {
            $row['editSDate'] = '';
            $row['editSTime'] = '';
        }
        $new_getedittripdata[] = $row;
    }
    $postData['getedittripdata'] = $new_getedittripdata;
    $postData['statusOptionsVal'] = getTripStatusOptionsValues($tripstatusArray, $getTripStatusId);
    $postData['dropPointsTR'] = getDropPointsValues($tripdroppointdata);
    $postData['statusHistoryTR'] = getStatusHistoryValues($getstatushistory);
    $postData['groupsg'] = getmappedtripusers($tripid);
    $yardData = getYardList($tripid);
    $yardlistOptions = $yardData['arrYardList'];
    $checkpointparam = $yardData['checkpointparam'];
    $yardOptionVal = '';
    if (isset($yardlistOptions) && !empty($yardlistOptions)) {
        $yardOptionVal .= '<option value="0">Select Yard</option>';
        foreach ($yardlistOptions as $yardKey => $yardVal) {
            if ($checkpointparam != 0) {
                if ($yardVal['checkpointid'] == $checkpointparam) {
                    $yardOptionVal .= '<option value="' . $checkpointparam . '" selected=selected>' . $yardVal['cname'] . '</option>';
                } else {
                    $yardOptionVal .= '<option value="' . $checkpointparam . '">' . $yardVal['cname'] . '</option>';
                }
            } else {
                $yardOptionVal .= '<option value="' . $yardVal['checkpointid'] . '">' . $yardVal['cname'] . '</option>';
            }
        }
        $postData['toYardListOptions'] = $yardOptionVal;
    }
    if ($customerno == '447') {
        $postData['tripLrDetailsTR'] = getTripLrDetailsValues($getTripLrDetails);
    }
    echo json_encode($postData);
} elseif ($action == "tripsDashboardData") {
    $tripDashboard = new Trips($customerno, $userid);
    $request = new stdClass();
    $request->customerno = $_SESSION['customerno'];
    $request->currentDate = date('Y-m-d');
    $tripDashboardData = $tripDashboard->getDashboardTripDetails($request);
    echo json_encode($tripDashboardData);
} elseif ($action == "closedtripreport") {
    $sdate = $_POST['startdate'];
    $edate = $_POST['enddate'];
    $closedtriprecord = gettripreportdata($sdate, $edate);
    echo json_encode($closedtriprecord);
} else {
    echo failure('No action found');
    exit;
}
//<editor-fold defaultstate="collapsed" desc="Helper functions">
function GetTripDetailsFromXml($xmlChild) {
    $objTripDetails = new stdClass();
    $objTripDetails->unloadingstart = (string) $xmlChild->ReportUnloadingDate;
    $objTripDetails->unloadingend = (string) $xmlChild->UnloadingDate;
    return $objTripDetails;
}

function GetExistingTripDetails($objTripDetails) {
    /* Get the existing trip details */
    $objTripManager = new Trips($objTripDetails->customerno, $objTripDetails->userid);
    $tripDetails = $objTripManager->gettripdetails(NULL, $objTripDetails->triplogno);
    $objTripDetails->tripid = isset($tripDetails['tripid']) ? $tripDetails['tripid'] : 0;
    $objTripDetails->vehicleno = isset($tripDetails['vehicleno']) ? $tripDetails['vehicleno'] : '';
    $objTripDetails->vehicleid = isset($tripDetails['vehicleid']) ? $tripDetails['vehicleid'] : 0;
    $objTripDetails->routename = isset($tripDetails['routename']) ? $tripDetails['routename'] : '';
    $objTripDetails->budgetedkms = isset($tripDetails['budgetedkms']) ? $tripDetails['budgetedkms'] : '';
    $objTripDetails->budgetedhrs = isset($tripDetails['budgetedhrs']) ? $tripDetails['budgetedhrs'] : '';
    $objTripDetails->actualkms = isset($tripDetails['actualkms']) ? $tripDetails['actualkms'] : '';
    $objTripDetails->actualhrs = isset($tripDetails['actualhrs']) ? $tripDetails['actualhrs'] : '';
    $objTripDetails->consignor = isset($tripDetails['consignor']) ? $tripDetails['consignor'] : '';
    $objTripDetails->consignee = isset($tripDetails['consignee']) ? $tripDetails['consignee'] : '';
    $objTripDetails->consignorid = isset($tripDetails['consignorid']) ? $tripDetails['consignorid'] : '';
    $objTripDetails->consigneeid = isset($tripDetails['consigneeid']) ? $tripDetails['consigneeid'] : '';
    $objTripDetails->billingparty = isset($tripDetails['billingparty']) ? $tripDetails['billingparty'] : '';
    $objTripDetails->mintemp = isset($tripDetails['mintemp']) ? $tripDetails['mintemp'] : '';
    $objTripDetails->maxtemp = isset($tripDetails['maxtemp']) ? $tripDetails['maxtemp'] : '';
    $objTripDetails->drivername = isset($tripDetails['drivername']) ? $tripDetails['drivername'] : '';
    $objTripDetails->drivermobile1 = isset($tripDetails['drivermobile1']) ? $tripDetails['drivermobile1'] : '';
    $objTripDetails->drivermobile2 = isset($tripDetails['drivermobile2']) ? $tripDetails['drivermobile2'] : '';
    $objTripDetails->remark = isset($tripDetails['remark']) ? $tripDetails['remark'] : '';
    $objTripDetails->perdaykm = isset($tripDetails['perdaykm']) ? $tripDetails['perdaykm'] : '';
    $objTripDetails->istripend = isset($tripDetails['is_tripend']) ? $tripDetails['is_tripend'] : '';
    //Unload start status and its timestamp for customer 206
    $objTripDetails->tripstatus = '9';
    $objTripDetails->statusdate = $objTripDetails->unloadingstart;
    $unloadstartdate = new DateTime($objTripDetails->unloadingstart);
    $objTripDetails->statusdatetime = $unloadstartdate->format('Y-m-d H:i:s');
    if ($objTripDetails->vehicleid > 0) {
        $objTripDetails->statusodometer = GetLoadingOdometer($objTripDetails->statusdatetime, $objTripDetails->vehicleid, $objTripDetails->customerno);
    } else {
        $objTripDetails->statusodometer = '0';
    }
    return $objTripDetails;
}

function GetLoadingOdometer($loadendDate, $vehicleid, $customerno) {
    //Get data from From Unit Sqlite
    $statusodometer = 0;
    $objLoadEndDate = new DateTime($loadendDate);
    $loadEndDay = $objLoadEndDate->format('Y-m-d');
    $loadEndTime = $objLoadEndDate->format('H:m:s');
    $objUnitManager = new UnitManager($customerno);
    $unitno = $objUnitManager->getunitno($vehicleid);
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$loadEndDay.sqlite";
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $statusodometerquery = "SELECT odometer FROM vehiclehistory WHERE time(lastupdated) >= '" . $loadEndTime . "' ORDER BY lastupdated ASC LIMIT 1;";
        $result = $db->query($statusodometerquery);
        if ($result) {
            $arrQueryResult = $result->fetchAll();
            $statusodometer = $arrQueryResult[0]['odometer'];
        }
    }
    return $statusodometer;
}

function get_vehicle_details($Request) {
    $VehicleManager = new VehicleManager($Request->customerno);
    $arrVehicle = $VehicleManager->get_vehicle_details($Request->vehicleid);
    return (array) $arrVehicle;
}

function GetOdometerMax345($date, $unitno) {
    $sqlitedate = date('Y-m-d', strtotime($date));
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$sqlitedate.sqlite";
    if ($_SESSION['role_modal'] == 'elixir') {
        //echo $location;
    }
    $ODOMETER = 0;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT max(odometer) as odometerm from vehiclehistory where lastupdated < '" . $date . "' limit 1";
        //echo $query."<br/>";
        $result = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $ODOMETER = $result[0]['odometerm'];
    }
    return $ODOMETER;
}

function getStoppageReasonPerLoc($objReason) {
    $objDeviceManager = new DeviceManager($objReason->customerno);
    $reason = $objDeviceManager->getStoppageReasonPerLoc($objReason);
    return $reason;
}

function getTripStatusOptionsValues($tripstatusArray, $getTripStatusId) {
    $optionVal = '';
    $optionVal .= "<option value='-1'>Select Status</option>";
    foreach ($tripstatusArray as $tripstatusKey => $tripstatusVal) {
        if ($tripstatusVal['statusid'] == $getTripStatusId) {
            $optionVal .= '<option value=" ' . $tripstatusVal['statusid'] . '" selected>' . $tripstatusVal['tripstatus'] . '  </option>';
        } else {
            $optionVal .= '<option value="' . $tripstatusVal['statusid'] . '" >' . $tripstatusVal['tripstatus'] . '</option>';
        }
    }
    return $optionVal;
}

function getDropPointsValues($tripdroppointdata) {
    $dropPointsTR = "<table style='padding-top: 10px;width:100%;' class='table newTable'><tr><th colspan='6'>Drop Point Details</th></tr><tr><th width='50px;'>Sr.No</th><th>Drop Point</th><th>Date Time</th><th>Yard Check-Out Time</th><th>Droppoint Check-In Time</th><th>Time-Difference From Yard To Droppoint.(In Hours)</th></tr>";
    if (isset($tripdroppointdata) && !empty($tripdroppointdata)) {
        foreach ($tripdroppointdata as $droppointKey => $droppointval) {
            $dropPointsTR .= "<tr><td>" . ($droppointKey + 1) . "</td>";
            $dropPointsTR .= "<td>" . location_cmn($droppointval['lat'], $droppointval['lng'], 1, $_SESSION['customerno']) . "</td>";
            $dropPointsTR .= "<td>" . date(speedConstants::DEFAULT_TIMESTAMP, strtotime($droppointval['createdonParam'])) . "</td>";
            $dropPointsTR .= "<td>" . $droppointval['checkpoint_timestamp'] . "</td>";
            $dropPointsTR .= "<td>" . $droppointval['createdonParam'] . "</td>";
            $timeDiff = findTimeDifference($droppointval['checkpoint_timestamp'], $droppointval['createdonParam'], 'hour');
            $dropPointsTR .= "<td>" . $timeDiff . "</td></tr>";
        }
    } else {
        $dropPointsTR .= "<tr><td colspan='6'>No Data Found</td></tr></table>";
    }
    return $dropPointsTR;
}

function getStatusHistoryValues($getstatushistory) {
    $statusHistoryTR = "";
    $statusHistoryTR .= "<table id='innertable' class='table newTable' style='width:100%;'>
                <tr><th colspan='3' class=''>Status History</th></tr>
                <tr><th width='30%'>Status</th><th width='40%'>Location</th><th width='30%'>Status Time</th></tr>";
    if (isset($getstatushistory)) {
        foreach ($getstatushistory as $row) {
            if (!is_null($row['statusdate'])) {
                $statusHistoryTR .= "<tr><td>" . $row['tripstatus'] . "</td><td>" . $row['location'] . "</td><td>" . date("d-m-Y G:ia", strtotime($row['statusdate'])) . "</td></tr>";
            } else {
                $statusHistoryTR .= "<tr><td>" . $row['tripstatus'] . "</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
            }
        }
    } else {
        $statusHistoryTR .= "<tr><td colspan='100%'> History Not Available</td></tr>";
    }
    $statusHistoryTR .= "</table>";
    return $statusHistoryTR;
}

function getTripLrDetailsValues($getTripLrDetails) {
    if (isset($getTripLrDetails[0]['varLrCreation']) && isset($getTripLrDetails[0]['varChitthiCreation'])) {
        $lrdelay = getTimeDiff($getTripLrDetails[0]['varLrCreation'], $getTripLrDetails[0]['varChitthiCreation']);
    } else {
        $lrdelay = "";
    }
    if (isset($getTripLrDetails) && !empty($getTripLrDetails)) {
        $tripLrDetailsTR = "<table style='padding-top: 10px;width:100%;' class='table newTable' id='tripLrDetails'><tr><th colspan='2'>Trip Details</th></tr>";
        $tripLrDetailsTR .= "<tr><td>Chitthi Creation Time</td><td>" . $getTripLrDetails[0]['varChitthiCreation'] . "</td></tr>";
        $tripLrDetailsTR .= "<tr><td>LR Creation Time</td><td>" . $getTripLrDetails[0]['varLrCreation'] . "</td></tr>";
        $tripLrDetailsTR .= "<tr><td>LR Delay Time</td><td>" . $lrdelay . "</td></tr>";
        $tripLrDetailsTR .= "<tr><td>Yard Check Out Time</td><td>" . $getTripLrDetails[0]['varYardCheckout'] . "</td></tr>";
        $tripLrDetailsTR .= "<tr><td>Yard Detention Time</td><td>" . $getTripLrDetails[0]['varYardDetention'] . "</td></tr>";
        $tripLrDetailsTR .= "<tr><td>Yard Check In Time</td><td>" . $getTripLrDetails[0]['varYardCheckin'] . "</td></tr><tr><td>Empty Return Deviation</td><td>" . $getTripLrDetails[0]['varEmptyReturnDeviation'] . "</td></tr>";
        $tripLrDetailsTR .= "</table>";
    } else {
        $tripLrDetailsTR = "<table style='padding-top: 10px;width:100%;' class='table newTable' id='tripLrDetails'><tr><th colspan='2'>Trip Details</th></tr><tr><td>No Data Found</td></t></table>";
    }
    return $tripLrDetailsTR;
}

//</editor-fold>
?>