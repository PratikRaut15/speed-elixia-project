<?php

error_reporting(0);
include_once '../../lib/system/utilities.php';
include_once '../../lib/autoload.php';
include_once '../reports/reports_stoppage_functions.php';
include_once '../reports/route_summary_functions.php';
require_once '../reports/html2pdf.php';
require_once '../../lib/bo/simple_html_dom.php';

$user_details = $umanager->get_user($customer_id, $user_id);
$_SESSION['report_gen_user'] = $user_details->username;
$geocode = '1';
$vehiclemanager = new VehicleManager($customer_id);
$routemanager = new RouteManager($customer_id);
$chkpointManager = new CheckpointManager($customer_id);

$objUserManager = new UserManager();
$content = ob_start();
$date = date('Y-m-d', strtotime($date));
$objDateStart = new DateTime($date);
$objDateEnd = new DateTime($date);
$objDateStart->modify('first day of this month');
$objDateEnd->modify('last day of this month');

$sqliteDay = date('Y-m-d', strtotime($date)); //date('d-m-Y', strtotime("- 1 day"))
$prev_monthend_date = $objDateEnd->format('Y-m-d');
$prev_monthstart_date = $objDateStart->format('Y-m-d');
$year_month = $objDateStart->format('Y-m');

//die();
//chkreport sqlite
//$location = "../../customer/$customerno/reports/chkreport.sqlite";
//$path = "sqlite:$location";

if ($group_id == '-1') {
    die('no data here');
} else {
    $tableheader = "";
    $tableheaderOnward = "";
    $tableheaderReturn = "";
    $routename = $_GET['routename'];
    $routeid = $_GET['g'];
    $getvehicledata = $routemanager->getvehiclesforroute($routeid);

    $summarydata = array();
    $daterange_row_wisearr = array();
    if (isset($getvehicledata)) {
        $datafirstroute = array();
        $checkpointsdata_byvehicles = array();
        foreach ($getvehicledata as $vehicledata) {
            $vehicleid = $vehicledata->vehicleid;
            //if($vehicleid==3159){
            $vehicleno = $routemanager->getvehiclenoforroute($vehicleid);
            $checkpoints_onwards_data = $routemanager->getroutebyvehicleid($vehicledata->vehicleid); // get checkpoints onwards data

            $tableheaderOnward = $checkpoints_onwards_data;

            $checkpoints_onwards_firstdata = reset($checkpoints_onwards_data);
            $checkpoints_onwards_enddata = end($checkpoints_onwards_data);

            $checkpoints_return_data = $routemanager->getroutebyvehicleidreverse($vehicledata->vehicleid); //  get return checkpoints data
            $tableheaderReturn = $checkpoints_return_data;

            $checkpoints_return_firstdata = reset($checkpoints_return_data);
            $checkpoints_return_enddata = end($checkpoints_return_data);

            $routecount = count($checkpoints_onwards_data);
            $first_chkid = $checkpoints_onwards_data[0]->checkpointid;
            $datafirstroute[$vehicleid]['firstdatedata'] = getfirstrouteoutdate($vehicleid, $first_chkid, $customer_id, $prev_monthstart_date, $prev_monthend_date);
            $getdatearr = $datafirstroute[$vehicleid]['firstdatedata'];
            $getdatearrcount = count($getdatearr);
            $firstdate = '';
            $lastdate = '';
            $tripArr = array();
            for ($i = 0; $i < $getdatearrcount; $i++) {
                $firstdate = $getdatearr[$i]['date'];
                $lastdate = isset($getdatearr[$i + 1]['date']) ? $getdatearr[$i + 1]['date'] : $prev_monthend_date . " 23:59:59";

                $daterange_row_wisearr[] = array(
                    'vehicleid' => $vehicleid,
                    'firstdate' => $firstdate,
                    'lastdate' => $lastdate,
                );
                $routedata = getchkreportdata_report($vehicleid, $firstdate, $lastdate, $customer_id);

                $lastidonid = $checkpoints_onwards_enddata->checkpointid;
                $onwardroutedata = array();
                foreach ($checkpoints_onwards_data as $row) {
                    $onwardroutedata[$row->checkpointid] = array(
                        'chkname' => $row->cname,
                        'chkpointid' => $row->checkpointid,
                        'sequence' => $row->sequence,
                        'indate' => "",
                        'outdate' => "",
                        'spenttimehrs' => "",
                        'hours' => "",
                    );
                }

                $returnroutedata = array();
                foreach ($checkpoints_return_data as $routetdleft) {
                    $returnroutedata[$routetdleft->checkpointid] = array(
                        'chkname' => $routetdleft->cname,
                        'chkpointid' => $routetdleft->checkpointid,
                        'sequence' => $routetdleft->sequence,
                        'indate' => "",
                        'outdate' => "",
                        'spenttimehrs' => "",
                        'hours' => "",
                    );
                }

                $routedatacount = count($routedata);
                $testonArr = array();
                $testreturnArr = array();
                foreach ($routedata as $key => $val) {
                    if ($lastidonid == $val['chkid'] && $val['status'] == 1) {
                        $breakid = $key;
                        break;
                    }
                    $testonArr[$key] = array(
                        'chkid' => $val['chkid'],
                        'status' => $val['status'],
                        'date' => $val['date'],
                    );
                }
                for ($j = $breakid; $j < $routedatacount; $j++) {
                    $testreturnArr[$j] = array(
                        'chkid' => $routedata[$j]['chkid'],
                        'status' => $routedata[$j]['status'],
                        'date' => $routedata[$j]['date'],
                    );
                }

                $ArrinoutdateOnward = array();

                $dataIssetOnwardFlag = 0;
                foreach ($checkpoints_onwards_data as $key => $val) {
                    $chkpt = $val->checkpointid;
                    $ArrinoutdateOnward[$chkpt]['indate'] = "";
                    $ArrinoutdateOnward[$chkpt]['outdate'] = "";
                    $ArrinoutdateOnward[$chkpt]['chkid'] = "";
                    $ArrinoutdateOnward[$chkpt]['spenttimehrs'] = "";
                    $ArrinoutdateOnward[$chkpt]['hours'] = "";
                    $ArrinoutdateOnward[$chkpt]['sequence'] = "";
                    $countOnward = 0;
                    foreach ($testonArr as $row) {
                        if ($row['chkid'] == $chkpt && $row['status'] == 0 && $ArrinoutdateOnward[$chkpt]['indate'] == "") {
                            $ArrinoutdateOnward[$chkpt]['indate'] = $row['date'];
                        }
                        if ($row['chkid'] == $chkpt && $row['status'] == 1) {
                            $ArrinoutdateOnward[$chkpt]['outdate'] = $row['date'];
                        }
                        $hours = 0;
                        if ($ArrinoutdateOnward[$chkpt]['indate'] != "" && $ArrinoutdateOnward[$chkpt]['outdate'] != "") {
                            $diff = strtotime($ArrinoutdateOnward[$chkpt]['outdate']) - strtotime($ArrinoutdateOnward[$chkpt]['indate']);
                            $hours = floor($diff / 3600);
                            $minutes = floor(($diff / 60) % 60);
                            $seconds = $diff % 60;
                            $diff_in_hrs = $hours . ":" . $minutes;
                            $ArrinoutdateOnward[$chkpt]['spenttimehrs'] = $diff_in_hrs;
                            $ArrinoutdateOnward[$chkpt]['hours'] = $hours;
                        } else {
                            $ArrinoutdateOnward[$chkpt]['spenttimehrs'] = "";
                            $ArrinoutdateOnward[$chkpt]['hours'] = $hours;
                        }
                        $ArrinoutdateOnward[$chkpt]['chkid'] = $chkpt;

                        if ($countOnward++ == 1) {
                            continue;
                        } else {
                            if ($ArrinoutdateOnward[$chkpt]['indate'] != "" || $ArrinoutdateOnward[$chkpt]['outdate'] != "") {
                                $dataIssetOnwardFlag = 1;
                                $dataIssetOnwardFlag += $dataIssetOnwardFlag;
                            }
                        }
                    }

                    $ArrinoutdateOnward[$chkpt]['sequence'] = $val->sequence;
                }

                $diffHoursOnward = 0;
                $onwarddatetimefirst = reset($ArrinoutdateOnward);
                $outdateonwardfirst = $onwarddatetimefirst['outdate'];
                $onwarddatetimelast = end($ArrinoutdateOnward);
                $indateonwardlast = $onwarddatetimelast['indate'];
                if ($outdateonwardfirst != "" && $indateonwardlast != "") {
                    $day1 = strtotime($outdateonwardfirst);
                    $daytime1 = date('H:i:s', strtotime($outdateonwardfirst));

                    $day2 = strtotime($indateonwardlast);
                    $daytime2 = date('H:i:s', strtotime($indateonwardlast));

                    $before = $day1;
                    $after = $day2;
                    $diff = $after - $before;
                    // $diff is in seconds
                    $hours = floor($diff / 3600);
                    $minutes = floor(($diff - $hours * 3600) / 60);
                    $seconds = $diff - $hours * 3600 - $minutes * 60;
                    $diffHoursOnward = $hours . ":" . $minutes;
                }

                $ArrinoutdateReturn = array();

                $dataIssetReturnFlag = 0;
                foreach ($checkpoints_return_data as $key => $val) {
                    $chkid = $val->checkpointid;
                    $ArrinoutdateReturn[$chkid]['indate'] = "";
                    $ArrinoutdateReturn[$chkid]['outdate'] = "";
                    $ArrinoutdateReturn[$chkid]['chkid'] = "";
                    $ArrinoutdateReturn[$chkid]['spenttimehrs'] = "";
                    $ArrinoutdateReturn[$chkid]['hours'] = "";
                    $ArrinoutdateReturn[$chkid]['sequence'] = "";
                    $countReturn = 0;
                    foreach ($testreturnArr as $row1) {
                        if ($row1['chkid'] == $chkid && $row1['status'] == 0 && $ArrinoutdateReturn[$chkid]['indate'] = " ") {
                            //IN
                            $ArrinoutdateReturn[$chkid]['indate'] = $row1['date'];
                        }
                        if ($row1['chkid'] == $chkid && $row1['status'] == 1) {
                            //out
                            $ArrinoutdateReturn[$chkid]['outdate'] = $row1['date'];
                        }
                        $hours = 0;
                        if ($ArrinoutdateReturn[$chkid]['indate'] != "" && $ArrinoutdateReturn[$chkid]['outdate'] != "" && $ArrinoutdateReturn[$chkid]['outdate'] > $ArrinoutdateReturn[$chkid]['indate']) {
                            $diff = strtotime($ArrinoutdateReturn[$chkid]['outdate']) - strtotime($ArrinoutdateReturn[$chkid]['indate']);
                            $hours = floor($diff / 3600);
                            $minutes = floor(($diff / 60) % 60);
                            $seconds = $diff % 60;
                            $diff_in_hrs = $hours . ":" . $minutes;
                            $ArrinoutdateReturn[$chkid]['spenttimehrs'] = $diff_in_hrs;
                            $ArrinoutdateReturn[$chkid]['hours'] = $hours;
                        } else {
                            $ArrinoutdateReturn[$chkid]['spenttimehrs'] = "";
                            $ArrinoutdateReturn[$chkid]['hours'] = $hours;
                        }
                        $ArrinoutdateReturn[$chkid]['chkid'] = $chkid;

                        if ($countReturn++ == 1) {
                            continue;
                        } else {
                            if ($ArrinoutdateReturn[$chkid]['indate'] != "" || $ArrinoutdateReturn[$chkid]['outdate'] != "") {
                                $dataIssetReturnFlag = 1;
                                $dataIssetReturnFlag += $dataIssetReturnFlag;
                            }
                        }
                    }
                    $ArrinoutdateReturn[$chkid]['sequence'] = $val->sequence;
                }

                $diffHoursReturn = 0;
                $returndatetimefirst = reset($ArrinoutdateReturn);
                $outdatereturnfirst = $returndatetimefirst['outdate'];
                $returndatetimelast = end($ArrinoutdateReturn);
                $indatereturnlast = $returndatetimelast['indate'];
                if ($outdatereturnfirst != "" && $indatereturnlast != "") {
                    $day2 = strtotime($outdatereturnfirst);
                    $day1 = strtotime($indatereturnlast);
                    $before = $day2;
                    $after = $day1;
                    $diff = $after - $before;
                    // $diff is in seconds
                    $hours = floor($diff / 3600);
                    $minutes = floor(($diff - $hours * 3600) / 60);
                    $seconds = $diff - $hours * 3600 - $minutes * 60;
                    $diffHoursReturn = $hours . ":" . $minutes;
                }

                if ($dataIssetOnwardFlag != 0 && $dataIssetReturnFlag != 0) {
                    $tripArr[] = array(
                        'onwardJourney' => $ArrinoutdateOnward,
                        'returnJourney' => $ArrinoutdateReturn,
                        'diffhrsonward' => $diffHoursOnward,
                        'diffhrsreturn' => $diffHoursReturn,
                    );
                }
            }

            if (!empty($tripArr)) {
                $checkpointsdata_byvehicles[] = array(
                    'firstdata' => $onwardroutedata,
                    'lastdata' => $returnroutedata,
                    'daterangedates' => $daterange_row_wisearr,
                    'tripdata' => $tripArr,
                    'customerno' => $customer_id,
                    'vehicleid' => $vehicleid,
                    'vehicleno' => $vehicleno,
                );
            }

            $tableheader = array(
                'onwardTH' => $tableheaderOnward,
                'returnTH' => $tableheaderReturn,
                'onwardfirstdata' => $checkpoints_onwards_firstdata,
                'onwardlastdata' => $checkpoints_onwards_enddata,
                'returnfirstdata' => $checkpoints_return_firstdata,
                'returnlastdata' => $checkpoints_return_enddata,
            );
            //}
        }
    }
    $summarydata[] = array(
        'routename' => $routename,
        'routeid' => $routeid,
        'alldata' => $checkpointsdata_byvehicles,
        'tableTH' => $tableheader,
    );

    $html = get_report_html_format($summarydata);
}
$cat = trim($cat);
$content = ob_get_clean();
if ($type == 'pdf') {
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($vehicleno . "_" . $sqliteDay . "_stoppageanalysis.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else {
    $routename = str_replace(" ", "", $routename);
    $xls_filename = trim($routename) . "_routesummary_report.xls";
    $html = str_get_html($content);
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;
}
?>
