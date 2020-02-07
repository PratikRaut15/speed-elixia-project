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
function get_lat_lng_for_trip_summay($STdate, $unitno, $time, $customerno) {
    $order = 'asc'; //ak added to resolve error
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$STdate.sqlite";
    $ODOMETER = 0;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT odometer from vehiclehistory where lastupdated='" . $time . "' ORDER BY vehiclehistory.lastupdated $order LIMIT 0,1";
        $result = $db->query($query);
        foreach ($result as $row) {
            $ODOMETER = $row['odometer'];
        }
    }
    return $ODOMETER;
}

function pullreport_summary($STdate, $EDdate, $vehicleid, $unitno, $customerno, $checkpoints) {
    $first_checkpoint = reset($checkpoints);
    $last_checkpoint = end($checkpoints);
    $return = array();
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE date BETWEEN '$STdate 00:00:00' AND '$EDdate 23:59:59' order by chkrepid asc";
    /* echo "************$vehicleid************<br/>";*
    if($vehicleid==2461){
    foreach($checkpoints as $s){
    echo "$s==";
    }
    echo "<br/>";
    }
    /* */
    try {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            $i = 1;
            $first_trip = array();
            $return_trip = array();
            foreach ($result as $row) {
                //if($vehicleid==2694){
                //echo "$i---{$row["chkid"]}=={$row["status"]}==={$row["date"]}<br/>";
                //}
                if (retval_issetor($return[0]) == 'complete') {
                    $return[0] = '';
                    $first_trip = array();
                    $temp_data = $return_trip[$first_checkpoint][0];
                    $return_trip = array();
                    $first_trip[$first_checkpoint][0] = $temp_data; //array();
                }
                if (retval_issetor($return[0]) == 'left_end') {
                    if ($row["chkid"] == $first_checkpoint) {
                        $return[0] = 'complete';
                    }
                    $return_trip[$row["chkid"]][$row["status"]] = $row["date"];
                } else {
                    if ($row["chkid"] == $first_checkpoint) {
                        $first_trip[$row["chkid"]][$row["status"]] = $row["date"];
                        if ($row["status"] == 0) {
                            $return[0] = 'start';
                        } else {
                            $return[0] = 'left_start';
                        }
                    }
                    if (isset($return[0])) {
                        if ($row["chkid"] == $last_checkpoint) {
                            if ($row["status"] == 0) {
                                $return[0] = 'end';
                            } else {
                                $return[0] = 'left_end';
                            }
                        }
                        $first_trip[$row["chkid"]][$row["status"]] = $row["date"];
                    }
                }
                $i++;
            }
        }
    } catch (PDOException $e) {
        $CHKMS = 0;
    }
    if (!isset($return[0])) {
        $return[0] = 'not-Found';
    }
    if (!empty($first_trip)) {
        $return[1] = $first_trip;
    }
    if (!empty($return_trip)) {
        $return[2] = $return_trip;
    }
    /* if($vehicleid==2461){
    echo "<pre>";print_r($return);echo "</pre>";
    } */
    return $return;
}

function getRouteSummary($STdate, $EDdate, $rep = '', $cust_id = '') {
    $customerno = ($cust_id == '') ? $_SESSION['customerno'] : $cust_id;
    $start_date = date("Y-m-d", strtotime($STdate));
    $end_date = date("Y-m-d", strtotime($EDdate));
    $cm = new CheckpointManager($customerno);
    $rm = new RouteManager($customerno);
    $all_checkpoints = $cm->get_customer_checkpoints($customerno);
    $route_details = $rm->get_route_details();
    $routes = $route_details[0];
    $route_names = $route_details[1];
    $vehicles = $route_details[2];
    $display = '';
    $check_details = array();
    $main = 0;
    foreach ($routes as $routeid => $route_data) {
        $route_name = $route_names[$routeid];
        $test = explode('-', $route_name);
        $start_route = isset($test[0]) ? $test[0] : '';
        $end_route = isset($test[1]) ? $test[1] : '';
        if (!isset($test[0]) || !isset($test[1])) {
            $start_route = $route_name[0];
            $end_route = $route_name[1];
        }
        $display .= "<tr style='background: #d8d5d6;font-weight: bold;text-align: center;'><td colspan='6' style='text-align:center'>Route: $route_name</td>"; //class='tableSub'
        $display .= "<td>$start_route</td>";
        $display .= "<td>$end_route</td>";
        $display .= "<td></td></tr>";
        $sr = 1;
        foreach ($route_data as $vehicleid => $checkpoints) {
            $unitno = $vehicles[$vehicleid]['unitno'];
            $summary_data = pullreport_summary($start_date, $end_date, $vehicleid, $unitno, $customerno, $checkpoints);
            $display .= "<tr>";
            $display .= "<td>$sr</td>";
            $display .= "<td>{$vehicles[$vehicleid]['vehno']}</td>";
            $display .= "<td width='200px;'>{$vehicles[$vehicleid]['city']}</td>";
            $display .= "<td width='200px;'>{$vehicles[$vehicleid]['driverno']}</td>";
            $display .= "<td>{$vehicles[$vehicleid]['cellno']}</td>";
            $display .= "<td>$route_name</td>";
            if ($summary_data[0] == 'start') {
                $display .= "<td style='background-color:green;font-weight:bold;'>YES</td>";
                $display .= "<td></td>";
                $display .= "<td></td>";
            } elseif ($summary_data[0] == 'end') {
                $display .= "<td></td>";
                $display .= "<td style='background-color:green;font-weight:bold;'>YES</td>";
                $display .= "<td></td>";
            } elseif ($summary_data[0] == 'left_start') {
                $display .= "<td><img src='../../images/right_trip.png' alt='---&gt;' /></td>";
                $display .= "<td></td>";
                $display .= "<td style='background-color:green;font-weight:bold;text-align:center'>IN TRANSIT</td>";
            } elseif ($summary_data[0] == 'left_end') {
                $display .= "<td></td>";
                $display .= "<td><img src='../../images/left_trip.png' alt='&lt;---' /></td>";
                $display .= "<td style='background-color:green;font-weight:bold;text-align:center'>IN TRANSIT</td>";
            } elseif ($summary_data[0] == 'complete') {
                $display .= "<td style='background-color:orange;font-weight:bold;'>Trip Complete</td>";
                $display .= "<td></td>";
                $display .= "<td></td>";
            } else {
                $display .= "<td></td>";
                $display .= "<td></td>";
                $display .= "<td></td>";
            }
            $display .= "</tr>";
            if (isset($summary_data[1])) {
                $firstloop = array();
                foreach ($summary_data[1] as $key => $val) {
                    $firstloop[] = $key;
                }
                $firstchkpt = reset($firstloop);
                $lastchkpt = end($firstloop);
                $check_details[$main] = "<tr><th colspan='100%' style='background:#cccccc'>{$vehicles[$vehicleid]['vehno']}</th></tr>
                <tr style='background: #d8d5d6;font-weight: bold;text-align: center;' ><td></td>"; // class='tableSub'
                foreach ($checkpoints as $s_chek) {
                    $c_name = $all_checkpoints[$s_chek];
                    $check_details[$main] .= "<td colspan=2>$c_name</td>";
                }
                $check_details[$main] .= '</tr>';
                $check_details[$main] .= "<tr><td>Status</td>";
                foreach ($checkpoints as $s_chek) {
                    $check_details[$main] .= "<td>IN</td>";
                    $check_details[$main] .= "<td>OUT</td>";
                }
                $check_details[$main] .= '</tr>';
                /* destination trip algo starts */
                if ($summary_data[0] == 'left_start') {
                    $check_details[$main] .= "<tr><td><img src='../../images/right_trip.png' alt='---&gt;'/></td>";
                } elseif ($summary_data[0] == 'complete') {
                    $check_details[$main] .= "<tr><td style='background-color:orange;font-weight:bold;'>Trip Complete</td>";
                } elseif ($summary_data[0] == 'start') {
                    $check_details[$main] .= "<tr><td style='background-color:green;font-weight:bold;'>YES(Start)</td>";
                } elseif ($summary_data[0] == 'left_end') {
                    $check_details[$main] .= "<tr><td><img src='../../images/left_trip.png' alt='&lt;---' /></td>";
                } elseif ($summary_data[0] == 'end') {
                    $check_details[$main] .= "<tr><td style='background-color:green;font-weight:bold;'>YES(End)</td>";
                } else {
                    $check_details[$main] .= "<tr><td></td>";
                }
                foreach ($checkpoints as $s_chek) {
                    if ($s_chek != $firstchkpt) {
                        $c_in_details = isset($summary_data[1][$s_chek][0]) ? cdate($summary_data[1][$s_chek][0], $rep) : ''; //checkpoint-in time
                    } else {
                        $c_in_details = '';
                    }
                    if ($s_chek == $lastchkpt) {
                        $firstlastoutdate = isset($summary_data[1][$s_chek][1]) ? $summary_data[1][$s_chek][1] : "";
                    }
                    $diff = strtotime(retval_issetor($summary_data[1][$s_chek][1])) >= strtotime(retval_issetor($summary_data[1][$s_chek][0]));
                    if ($s_chek != $lastchkpt) {
                        $c_end_details = (isset($summary_data[1][$s_chek][1]) && $diff) ? cdate($summary_data[1][$s_chek][1], $rep) : ''; //checkpoint-out time
                    } else {
                        $c_end_details = "";
                    }
                    $check_details[$main] .= "<td>" . $c_in_details . "</td>";
                    $check_details[$main] .= "<td style='width:70px;'>$c_end_details</td>";
                }
                $check_details[$main] .= '</tr>';
                /* destination trip algo ends */
                /* Return trip algo starts */
                if (isset($summary_data[2])) {
                    $check_details[$main] .= "<tr><td></td>";
                    $end_chk_id = end($checkpoints); //to store the end checkpoint-id
                    foreach ($checkpoints as $s_chek) {
                        if ($s_chek != $lastchkpt) {
                            $c_in_details = isset($summary_data[2][$s_chek][0]) ? cdate($summary_data[2][$s_chek][0], $rep) : '';
                        } else {
                            $c_in_details = '';
                        }
                        //if end-checkpoint, then show as blank
                        if ($s_chek != $firstchkpt) {
                            $c_end_details = (isset($summary_data[2][$s_chek][1])) ? cdate($summary_data[2][$s_chek][1], $rep) : '';
                        } else {
                            $c_end_details = "";
                        }
                        if ($s_chek == $lastchkpt) {
                            $c_end_details = isset($summary_data[2][$s_chek][1]) ? $summary_data[2][$s_chek][1] : $firstlastoutdate;
                        }
                        $check_details[$main] .= "<td>$c_in_details</td>";
                        $check_details[$main] .= "<td style='width:70px;'>$c_end_details</td>";
                    }
                    $check_details[$main] .= '</tr>';
                }
                /* Return trip algo ends */
            }
            $main++;
            $sr++;
        }
    }
    $display .= "</tbody></table>";
    $display .= "<br/>";
    if ($rep == 'pdf') {
        $display .= "<h2 style='text-align:center;'>Details</h2><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $display .= "<tbody>";
        //$display .= implode('<tr><td colspan="100%" style="height:10px"></td></tr>', $check_details);
        if (isset($check_details) && !empty($check_details)) {
            $display .= implode('<tr><td colspan="100%" style="height:10px"></td></tr>', $check_details);
        } else {
            $display .= '<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        }
    } else {
        $display .= "<h2>Details</h2><table class='table newTable'><tbody>";
        //$display .= implode('<tr><td colspan="100%"></td></tr>', $check_details);
        if (isset($check_details) && !empty($check_details)) {
            $display .= implode('<tr><td colspan="100%"></td></tr>', $check_details);
        } else {
            $display .= '<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        }
    }
    $display .= "</tbody></table>";
    echo $display;
}

function getRouteSummary_pdf($startDate, $endDate, $customerno, $vgroupname = null) {
    $datediffcheck = date_SDiff_cmn($startDate, $endDate);
    if ($datediffcheck > 31) {
        exit('Please check dates');
    }
    include_once "../../lib/bo/UserManager.php";
    $title = 'Route Summary Report';
    $subTitle = array(
        "Start Date: $startDate",
        "End Date: $endDate",
    );
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $cm = new CustomerManager($customerno);
    $customer_details = $cm->getcustomerdetail_byid($customerno);
    echo pdf_header($title, $subTitle, $customer_details);
    echo "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    echo "<tbody>";
    echo "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
    echo '<td style="width:auto;height:auto;">Sr No.</td>';
    echo '<td style="width:auto;height:auto;">Vehicle No</td>';
    echo '<td style="width:auto;height:auto;">Current City</td>';
    echo '<td style="width:auto;height:auto;">Driver Name</td>';
    echo '<td style="width:auto;height:auto;">Cell No</td>';
    echo '<td style="width:auto;height:auto;">Route</td>';
    echo '<td style="width:auto;height:auto;">Start</td>';
    echo '<td style="width:auto;height:auto;">End</td>';
    echo '<td style="width:auto;height:auto;">ENROUTE</td>';
    echo "</tr>";
    getRouteSummary($startDate, $endDate, 'pdf', $customerno);
}

function getRouteSummary_excel($startDate, $endDate, $customerno, $vgroupname = null) {
    $datediffcheck = date_SDiff_cmn($startDate, $endDate);
    if ($datediffcheck > 31) {
        exit('Please check dates');
    }
    include_once "../../lib/bo/UserManager.php";
    $title = 'Route Summary Report';
    $subTitle = array(
        "Start Date: $startDate",
        "End Date: $endDate",
    );
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $cm = new CustomerManager($customerno);
    $customer_details = $cm->getcustomerdetail_byid($customerno);
    echo excel_header($title, $subTitle, $customer_details);
    echo "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    echo "<tbody>";
    echo "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
    echo '<td style="width:auto;height:auto;">Sr No.</td>';
    echo '<td style="width:auto;height:auto;">Vehicle No</td>';
    echo '<td style="width:auto;height:auto;">Current City</td>';
    echo '<td style="width:auto;height:auto;">Driver Name</td>';
    echo '<td style="width:auto;height:auto;">Cell No</td>';
    echo '<td style="width:auto;height:auto;">Route</td>';
    echo '<td style="width:auto;height:auto;">Start</td>';
    echo '<td style="width:auto;height:auto;">End</td>';
    echo '<td style="width:auto;height:auto;">ENROUTE</td>';
    echo "</tr>";
    getRouteSummary($startDate, $endDate, 'excel', $customerno);
}

function getRouteTatSummary($STdate, $EDdate, $rep = '', $cust_id = '', $groupid) {
    $customerno = ($cust_id == '') ? $_SESSION['customerno'] : $cust_id;
    $start_date = date("Y-m-d", strtotime($STdate));
    $end_date = date("Y-m-d", strtotime($EDdate));
    $cm = new CheckpointManager($customerno);
    $rm = new RouteManager($customerno);
    $all_checkpoints = $cm->get_customer_checkpoints($customerno);
    $route_details = $rm->get_routedetails($groupid);
    $routes = $route_details[0];
    $route_names = $route_details[1];
    $vehicles = $route_details[2];
    $display = '';
    $check_details = array();
    $main = 0;
    foreach ($routes as $routeid => $route_data) {
        $route_name = $route_names[$routeid];
        $test = explode('-', $route_name);
        $start_route = isset($test[0]) ? $test[0] : '';
        $end_route = isset($test[1]) ? $test[1] : '';
        if (!isset($test[0]) || !isset($test[1])) {
            $start_route = $route_name[0];
            $end_route = $route_name[1];
        }
        $sr = 1;
        foreach ($route_data as $vehicleid => $checkpoints) {
            $unitno = $vehicles[$vehicleid]['unitno'];
            $summary_data = pullreport_summary($start_date, $end_date, $vehicleid, $unitno, $customerno, $checkpoints);
            if (isset($summary_data[1])) {
                $check_details[$main] = "
                <tr style='background: #d8d5d6;font-weight: bold;text-align: center;' ><td>Status</td><td>Vehicle No</td><td>Route</td><td>Norms</td>"; // class='tableSub'
                foreach ($checkpoints as $s_chek) {
                    @$c_name = $all_checkpoints[$s_chek];
                    $check_details[$main] .= "<td colspan=2>$c_name</td>";
                }
                $check_details[$main] .= "<td>Remark</td>";
                $check_details[$main] .= '</tr>';
                $check_details[$main] .= "<tr><td></td>";
                $check_details[$main] .= "<td>{$vehicles[$vehicleid]['vehno']}</td>";
                $check_details[$main] .= "<td>{$route_name}</td>";
                $check_details[$main] .= "<td></td>";
                foreach ($checkpoints as $s_chek) {
                    $check_details[$main] .= "<td>IN</td>";
                    $check_details[$main] .= "<td>OUT</td>";
                }
                $check_details[$main] .= "<td></td>";
                $check_details[$main] .= '</tr>';
                $check_details[$main] .= "<tr><td></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>STD</td>";
                foreach ($checkpoints as $s_chek) {
                    $details = $rm->getRouteChkDetails($routeid, $s_chek);
                    $eta = isset($details['eta']) ? date('H:i', strtotime($details['eta'])) : '';
                    $etd = isset($details['etd']) ? date('H:i', strtotime($details['etd'])) : '';
                    $check_details[$main] .= "<td>" . $eta . "</td>";
                    $check_details[$main] .= "<td>" . $etd . "</td>";
                }
                $check_details[$main] .= "<td></td>";
                $check_details[$main] .= '</tr>';
                /* destination trip algo starts */
                $tripComplete = "<td></td>";
                if ($summary_data[0] == 'left_start') {
                    $check_details[$main] .= "<tr><td><img src='../../images/right_trip.png' alt='---&gt;'/></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } elseif ($summary_data[0] == 'complete') {
                    $check_details[$main] .= "<tr>" . $tripComplete . "<td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                    $tripComplete = "<td style='background-color:orange;font-weight:bold;'>Trip Complete</td>";
                } elseif ($summary_data[0] == 'start') {
                    $check_details[$main] .= "<tr><td style='background-color:green;font-weight:bold;'>YES(Start)</td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } elseif ($summary_data[0] == 'left_end') {
                    $check_details[$main] .= "<tr><td><img src='../../images/left_trip.png' alt='&lt;---' /></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } elseif ($summary_data[0] == 'end') {
                    $check_details[$main] .= "<tr><td style='background-color:green;font-weight:bold;'>YES(End)</td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } else {
                    $check_details[$main] .= "<tr><td></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                }
                $routeStatus = '';
                foreach ($checkpoints as $s_chek) {
                    $details = $rm->getRouteChkDetails($routeid, $s_chek);
                    $c_in_details = isset($summary_data[1][$s_chek][0]) ? cdate($summary_data[1][$s_chek][0], 'xls') : ''; //checkpoint-in time
                    $diff = strtotime(retval_issetor($summary_data[1][$s_chek][1])) >= strtotime(retval_issetor($summary_data[1][$s_chek][0]));
                    $c_end_details = (isset($summary_data[1][$s_chek][1]) && $diff) ? cdate($summary_data[1][$s_chek][1], 'xls') : ''; //checkpoint-out time
                    $check_details[$main] .= "<td>" . cdate($c_in_details, $rep) . "</td>";
                    $check_details[$main] .= "<td>" . cdate($c_end_details, $rep) . "</td>";
                    if (isset($details) && !empty($details)) {
                        if ($c_end_details != '' && isset($details['etd'])) {
                            $expectedDate = date('Y-m-d', strtotime($c_end_details)) . " " . $details['etd'];
                            $routeStatus = getRouteRemark($c_end_details, $expectedDate);
                        } elseif ($c_in_details != '' && isset($details['eta'])) {
                            $expectedDate = date('Y-m-d', strtotime($c_in_details)) . " " . $details['eta'];
                            $routeStatus = getRouteRemark($c_in_details, $expectedDate);
                        }
                    }
                }
                $check_details[$main] .= "<td>" . $routeStatus . "</td>";
                $check_details[$main] .= '</tr>';
                /* destination trip algo ends */
                /* Return trip algo starts */
                if (isset($summary_data[2])) {
                    $check_details[$main] .= "<tr><td></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>STD</td>";
                    foreach ($checkpoints as $s_chek) {
                        $details = $rm->getRouteChkDetails($routeid, $s_chek);
                        $eta = isset($details['r_eta']) ? date('H:i', strtotime($details['r_eta'])) : '';
                        $etd = isset($details['r_etd']) ? date('H:i', strtotime($details['r_etd'])) : '';
                        $check_details[$main] .= "<td>" . $eta . "</td>";
                        $check_details[$main] .= "<td>" . $etd . "</td>";
                    }
                    $check_details[$main] .= "<td></td>";
                    $check_details[$main] .= '</tr>';
                    $check_details[$main] .= "<tr>" . $tripComplete . "<td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                    $end_chk_id = end($checkpoints); //to store the end checkpoint-id
                    $routeStatus = '';
                    foreach ($checkpoints as $s_chek) {
                        $details = $rm->getRouteChkDetails($routeid, $s_chek);
                        $c_in_details = isset($summary_data[2][$s_chek][0]) ? cdate($summary_data[2][$s_chek][0], 'xls') : '';
                        //if end-checkpoint, then show as blank
                        $c_end_details = (isset($summary_data[2][$s_chek][1]) && $s_chek != $end_chk_id) ? cdate($summary_data[2][$s_chek][1], 'xls') : '';
                        $check_details[$main] .= "<td>" . cdate($c_in_details, $rep) . "</td>";
                        $check_details[$main] .= "<td>" . cdate($c_end_details, $rep) . "</td>";
                        if (isset($details) && !empty($details)) {
                            if ($c_end_details != '' && isset($details['r_etd'])) {
                                $expectedDate = date('Y-m-d', strtotime($c_end_details)) . " " . $details['r_etd'];
                                $routeStatus = getRouteRemark($c_end_details, $expectedDate);
                            } elseif ($c_in_details != '' && isset($details['r_eta'])) {
                                $expectedDate = date('Y-m-d', strtotime($c_in_details)) . " " . $details['r_eta'];
                                $routeStatus = getRouteRemark($c_in_details, $expectedDate);
                            }
                        }
                    }
                    $check_details[$main] .= "<td>" . $routeStatus . "</td>";
                    $check_details[$main] .= '</tr>';
                }
                /* Return trip algo ends */
            }
            $main++;
            $sr++;
        }
    }
    $display .= "</tbody></table>";
    $display .= "<br/>";
    if ($rep == 'pdf') {
        $display .= "<h2 style='text-align:center;'>Details</h2><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $display .= "<tbody>";
        //$display .= implode('<br/>', $check_details);
        if (isset($check_details) && !empty($check_details)) {
            $display .= implode('', $check_details);
        } else {
            $display .= '<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        }
    } else {
        $display .= "<h2>Details</h2><table class='table newTable'><tbody>";
        //$display .= implode('<br/>', $check_details);
        if (isset($check_details) && !empty($check_details)) {
            $display .= implode('', $check_details);
        } else {
            $display .= '<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        }
    }
    $display .= "</tbody></table>";
    echo $display;
}

function getRouteTatSummary_pdf($startDate, $endDate, $customerno, $type, $groupid, $routelist, $vgroupname = NULL) {
    $datediffcheck = date_SDiff_cmn($startDate, $endDate);
    if ($datediffcheck > 31) {
        exit('Please check dates');
    }
    include_once "../../lib/bo/UserManager.php";
    $title = 'Routewise On Time Departure';
    $subTitle = array(
        "Start Date: $startDate",
        "End Date: $endDate",
    );
    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $cm = new CustomerManager($customerno);
    $customer_details = $cm->getcustomerdetail_byid($customerno);
    if ($type == 'pdf') {
        echo pdf_header($title, $subTitle, $customer_details);
    } elseif ($type == 'xls') {
        echo excel_header($title, $subTitle, $customer_details);
    }
    echo "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    echo "<tbody>";
    if ($routelist != 0) {
        //getTatSummaryRoute($routelist, $startDate, $endDate);
        getTatSummaryRouteTest($routelist, $startDate, $endDate);
    } else {
        //getTatSummary1($startDate, $endDate, $type, $customerno, $groupid);
        getTatSummaryRouteTest($routelist, $startDate, $endDate);
    }
}

function cdate($date, $type) {
    if ($type == 'pdf') {
        $date = str_replace(' ', '<br/>', $date);
        return "$date";
    } else {
        return $date;
    }
}

function getfirstrouteoutdate($vehicleid, $first_routeid, $customerno, $startdate, $enddate) {
    $data = array();
    $startdate = $startdate . " 00:00:00";
    $enddate = $enddate . " 23:59:59";
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $db = new PDO($path);
    $query = "SELECT chkid,status,date FROM V$vehicleid WHERE `date` between '" . $startdate . "' AND '" . $enddate . "' AND chkid = $first_routeid AND status = '1'";
    $result = $db->query($query);
    $data = array();
    foreach ($result as $row) {
        $data[] = array(
            'chkid' => $row['chkid'],
            'status' => $row['status'],
            'date' => $row['date'],
            'vehicleid' => $vehicleid,
        );
    }
    return $data;
}

function get_report_html_format($summarydata) {
    $routecounts = count($summarydata);
    $htmlTable = "<br><br><br><br><br>";
    $onwardth = "";
    $allvehicledata_count = "";
    for ($i = 0; $i < $routecounts; $i++) {
        $allvehicledata = $summarydata[$i]['alldata'];
        $daterangedatacount = 0;
        $allvehicledata_count = count($summarydata[$i]['alldata']);
        for ($j = 0; $j < $allvehicledata_count; $j++) {
            $daterangedata = $allvehicledata[$j]['daterangedates'];
            $daterangedatacount = count($allvehicledata[$j]['daterangedates']);
            $tripdata = $allvehicledata[$j]['tripdata'];
            $tripdata_count = count($allvehicledata[$j]['tripdata']);
        }
        $onwardth = $summarydata[$i]['tableTH']['onwardTH'];
        $onwardthcount = count($summarydata[$i]['tableTH']['onwardTH']);
        $returnth = $summarydata[$i]['tableTH']['returnTH'];
        $returnthcount = count($summarydata[$i]['tableTH']['returnTH']);
        $onwardfirstdata = $summarydata[$i]['tableTH']['onwardfirstdata'];
        $onwardFirstchkid = $onwardfirstdata->checkpointid;
        $onwardlastdata = $summarydata[$i]['tableTH']['onwardlastdata'];
        $onwardLastchkid = $onwardfirstdata->checkpointid;
        $returnfirstdata = $summarydata[$i]['tableTH']['returnfirstdata'];
        $returnFirstchkid = $returnfirstdata->checkpointid;
        $returnlastdata = $summarydata[$i]['tableTH']['returnlastdata'];
        $returnLastchkid = $returnlastdata->checkpointid;
        $thheading = array();
        $thheading_spendtime = array();
        //$onwardth = array_slice($onwardth, 1, -1);
        for ($thonward = 0; $thonward < $onwardthcount; $thonward++) {
            $thheading[] = "<th>" . $onwardth[$thonward]->cname . "</th>";
            //$thheading_spenttime[] = "<th> Time spent at <br>" . $onwardth[$thonward]->cname . "</th>";
        }
        $onwardth1 = array_slice($onwardth, 1, -1);
        $onwardth1count = count($onwardth1);
        for ($thonward1 = 0; $thonward1 < $onwardth1count; $thonward1++) {
            $thheading_spenttime[] = "<th> Time spent at <br>" . $onwardth1[$thonward1]->cname . "</th>";
        }
        $thheadingreturn = array();
        $thheadingreturn_spendtime = array();
        for ($threturn = 0; $threturn < $returnthcount; $threturn++) {
            $thheadingreturn[] = "<th>" . $returnth[$threturn]->cname . "</th>";
        }
        $returnth1 = array_slice($returnth, 1, -1);
        $returnth1count = count($returnth1);
        for ($threturn1 = 0; $threturn1 < $returnth1count; $threturn1++) {
            $thheadingreturn_spenttime[] = "<th> Time spent at<br>" . $returnth1[$threturn1]->cname . "</th>";
        }
        $chknamethfirst = implode("", $thheading);
        $chknamethfirst_spenttime = implode("", $thheading_spenttime);
        $chknamethlast = implode("", $thheadingreturn);
        $chknamethlast_spenttime = implode("", $thheadingreturn_spenttime);
        $totalHrs = "<th>Total Hrs</th>";
        $htmlTable .= "<table border=1' width='100%'   style='border-collapse: collapse;'>";
        $htmlTable .= "<tr><th>Routes</th><th colspan='100%'>Route Data</th></tr>";
        $htmlTable .= "<tr>";
        $htmlTable .= "<td style='vertical-align:center;'>{$summarydata[$i]['routename']}</td><td>";
        $htmlTable .= "<td>";
        $htmlTable .= "<table border=1' width='100%' style='border-collapse: collapse;'>";
        $htmlTable .= "<tr><th>Vehicle No</th>" . $chknamethfirst . $totalHrs . $chknamethfirst_spenttime . $chknamethlast . $totalHrs . $chknamethlast_spenttime . "</tr>";
        $hrsOnwardsstr = 0;
        $hrsReturnstr = 0;
        for ($k = 0; $k < $allvehicledata_count; $k++) {
            $daterangedata_count = count($allvehicledata[$k]['daterangedates']);
            $tripdata = $allvehicledata[$k]['tripdata'];
            $vehicleid = $allvehicledata[$k]['vehicleid'];
            $tripdata_count1 = count($allvehicledata[$k]['tripdata']);
            if ($k == 0) {
                $trcount = 1;
            } else {
                $trcount = 1;
            }
            $tripdatacount = $tripdata_count1 + $trcount;
            //$htmlTable .= "<tr><td rowspan='" . $tripdatacount . "'>" . $allvehicledata[$k]['vehicleno']."/"."/".$tripdata_count1."/".$allvehicledata[$k]['vehicleid'] . "</td>";
            $htmlTable .= "<tr><td rowspan='" . $tripdatacount . "'>" . $allvehicledata[$k]['vehicleno'] . "</td>";
            $htmlTable .= "</tr>";
            $onwardJ = "";
            $returnJ = "";
            $hrsOnwards = 0;
            $hrsReturn = 0;
            for ($trid = 0; $trid < $tripdata_count1; $trid++) {
                $tripdata = $allvehicledata[$k]['tripdata'];
                if (isset($tripdata)) {
                    $onwardJ = $tripdata[$trid]['onwardJourney'];
                    $totalhrsOnward = $tripdata[$trid]['diffhrsonward'];
                    $totalhrsreturn = $tripdata[$trid]['diffhrsreturn'];
                    $returnJ = $tripdata[$trid]['returnJourney'];
                    $leftarrData = array();
                    $hrsOnwardArr = array();
                    $spenttimeonward = array();
                    if (isset($onwardJ) && !empty($onwardJ)) {
                        foreach ($onwardJ as $row) {
                            $indate = $row['indate'];
                            $outdate = $row['outdate'];
                            $chkid = $row['chkid'];
                            $spenttime = isset($row['spenttimehrs']) ? $row['spenttimehrs'] : "00:00";
                            $hrsOnwardArr[] = $row['hours'];
                            if (!empty($indate)) {
                                $indate = $row['indate'];
                            } else {
                                $indate = "&nbsp;";
                            }
                            if (!empty($outdate)) {
                                $outdate = $row['outdate'];
                            } else {
                                $outdate = "&nbsp;";
                            }
                            if ($onwardFirstchkid == $chkid) {
                                $indate = "&nbsp;";
                            }
                            $leftarrData[] = "<td><table border=1><tr><th>IN</th><th>OUT</th></tr><tr><td>" . $indate . "</td><td>" . $outdate . "</td></tr></table></td>";
                            $spenttimeonward[] = "<td>" . $spenttime . "</td>";
                        }
                    }
                    $rightarrData = array();
                    $hrsReturnArr = array();
                    $spenttimereturn = array();
                    if (isset($returnJ) && !empty($returnJ)) {
                        foreach ($returnJ as $row1) {
                            $hrsReturnArr[] = $row1['hours'];
                            $spenttimer = $row1['spenttimehrs'];
                            $chkid = $row1['chkid'];
                            $spenttimer = isset($row1['spenttimehrs']) ? $row1['spenttimehrs'] : "00:00";
                            if (!empty($row1['indate'])) {
                                $indateRet = $row1['indate'];
                            } else {
                                $indateRet = "&nbsp;";
                            }
                            if (!empty($row1['outdate'])) {
                                $outdateRet = $row1['outdate'];
                            } else {
                                $outdateRet = "&nbsp;";
                            }
                            if ($returnFirstchkid == $chkid) {
                                $indateRet = "&nbsp;";
                            }
                            if ($returnLastchkid == $chkid) {
                                $outdateRet = "&nbsp;";
                            }
                            $rightarrData[] = "<td><table border=1><tr><th>IN</th><th>OUT</th></tr><tr><td>" . $indateRet . "</td><td style='height:30px;'>" . $outdateRet . "</td></tr></table></td>";
                            $spenttimereturn[] = "<td>" . $spenttimer . "</td>";
                        }
                    }
                    $spenttimeonward = array_slice($spenttimeonward, 1, -1);
                    $chknametdfirst = implode("", $leftarrData);
                    $spenttimeOnward = implode("", $spenttimeonward);
                    $chknametdlast = implode("", $rightarrData);
                    $spenttimereturn = array_slice($spenttimereturn, 1, -1);
                    $spenttimeReturn = implode("", $spenttimereturn);
                    $chknametdfirst = $chknametdfirst . "<td>" . $totalhrsOnward . "</td>" . $spenttimeOnward;
                    $chknametdlast = $chknametdlast . "<td>" . $totalhrsreturn . "</td>" . $spenttimeReturn;
                    $htmlTable .= "<tr>" . $chknametdfirst . $chknametdlast . "</tr>";
                }
            }
        }
        $htmlTable .= "</table>";
        $htmlTable .= "</td>";
        $htmlTable .= "</tr>";
        $htmlTable .= "</table>";
    }
    echo $htmlTable .= "<br>";
    return $htmlTable;
}

function getchkreportdata_report($vehicleid, $firstdate, $lastdate, $customerno) {
    $data = array();
    $startdate = $firstdate;
    $enddate = $lastdate;
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $db = new PDO($path);
    $query = "SELECT chkid,status,date FROM V$vehicleid WHERE `date` between '" . $startdate . "' AND '" . $enddate . "' order by date asc";
    $result = $db->query($query);
    $data = array();
    foreach ($result as $row) {
        $data[] = array(
            'chkid' => $row['chkid'],
            'status' => $row['status'],
            'date' => date('d-m-Y H:i:s', strtotime($row['date'])),
            'vehicleid' => $vehicleid,
        );
    }
    return $data;
}

function getTatSummary($STdate, $EDdate, $rep = '', $cust_id = '') {
    $customerno = ($cust_id == '') ? $_SESSION['customerno'] : $cust_id;
    $start_date = date("Y-m-d", strtotime($STdate));
    $end_date = date("Y-m-d", strtotime($EDdate));
    $cm = new CheckpointManager($customerno);
    $rm = new RouteManager($customerno);
    $all_checkpoints = $cm->get_customer_checkpoints($customerno);
    $route_details = $rm->get_routedetails($_SESSION['groupid']);
    $routes = $route_details[0];
    $route_names = $route_details[1];
    $vehicles = $route_details[2];
    $display = '';
    $check_details = array();
    $main = 0;
    foreach ($routes as $routeid => $route_data) {
        $route_name = $route_names[$routeid];
        $test = explode('-', $route_name);
        $start_route = isset($test[0]) ? $test[0] : '';
        $end_route = isset($test[1]) ? $test[1] : '';
        if (!isset($test[0]) || !isset($test[1])) {
            $start_route = $route_name[0];
            $end_route = $route_name[1];
        }
        $sr = 1;
        foreach ($route_data as $vehicleid => $checkpoints) {
            //if($vehicleid==2714){
            $unitno = $vehicles[$vehicleid]['unitno'];
            $summary_data = pullreport_summary($start_date, $end_date, $vehicleid, $unitno, $customerno, $checkpoints);
            if (isset($summary_data[1])) {
                /* echo "<pre>";
                print_r($summary_data); */
                $firstloop = array();
                foreach ($summary_data[1] as $key => $val) {
                    $firstloop[] = $key;
                }
                $firstchkpt = reset($firstloop);
                $lastchkpt = end($firstloop);
                $check_details[$main] = "<tr><th colspan='100%' style='background:#cccccc'>{$route_name} : {$vehicles[$vehicleid]['vehno']}</th></tr>
                <tr style='background: #d8d5d6;font-weight: bold;text-align: center;' ><td>Status</td><td>Vehicle No.</td><td>Route</td><td>Norms</td>"; // class='tableSub'
                foreach ($checkpoints as $s_chek) {
                    @$c_name = $all_checkpoints[$s_chek];
                    $check_details[$main] .= "<td colspan=2>$c_name</td>";
                }
                $check_details[$main] .= "<td>Remark</td>";
                $check_details[$main] .= '</tr>';
                $check_details[$main] .= "<tr><td></td>";
                $check_details[$main] .= "<td>{$vehicles[$vehicleid]['vehno']}</td>";
                $check_details[$main] .= "<td>{$route_name}</td>";
                $check_details[$main] .= "<td></td>";
                foreach ($checkpoints as $s_chek) {
                    $check_details[$main] .= "<td>IN</td>";
                    $check_details[$main] .= "<td>OUT</td>";
                }
                $check_details[$main] .= "<td></td>";
                $check_details[$main] .= '</tr>';
                $check_details[$main] .= "<tr><td></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>STD</td>";
                foreach ($checkpoints as $s_chek) {
                    $details = $rm->getRouteChkDetails($routeid, $s_chek);
                    $eta = isset($details['eta']) ? date('H:i', strtotime($details['eta'])) : '';
                    $etd = isset($details['etd']) ? date('H:i', strtotime($details['etd'])) : '';
                    $check_details[$main] .= "<td>" . $eta . "</td>";
                    $check_details[$main] .= "<td>" . $etd . "</td>";
                }
                $check_details[$main] .= "<td></td>";
                $check_details[$main] .= '</tr>';
                /* destination trip algo starts */
                $tripComplete = '<td></td>';
                if ($summary_data[0] == 'left_start') {
                    $check_details[$main] .= "<tr><td><img src='../../images/right_trip.png' alt='---&gt;'/></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } elseif ($summary_data[0] == 'complete') {
                    $check_details[$main] .= "<tr>" . $tripComplete . "<td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                    $tripComplete = "<td style='background-color:orange;font-weight:bold;'>Trip Complete</td>";
                } elseif ($summary_data[0] == 'start') {
                    $check_details[$main] .= "<tr><td style='background-color:green;font-weight:bold;'>YES(Start)</td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } elseif ($summary_data[0] == 'left_end') {
                    $check_details[$main] .= "<tr><td><img src='../../images/left_trip.png' alt='&lt;---' /></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } elseif ($summary_data[0] == 'end') {
                    $check_details[$main] .= "<tr><td style='background-color:green;font-weight:bold;'>YES(End)</td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } else {
                    $check_details[$main] .= "<tr><td></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td></td>";
                }
                $routeStatus = '';
                foreach ($checkpoints as $s_chek) {
                    $details = $rm->getRouteChkDetails($routeid, $s_chek);
                    if ($s_chek != $firstchkpt) {
                        $c_in_details = isset($summary_data[1][$s_chek][0]) ? cdate($summary_data[1][$s_chek][0], $rep) : ''; //checkpoint-in time
                    } else {
                        $c_in_details = '';
                    }
                    $diff = strtotime(retval_issetor($summary_data[1][$s_chek][1])) >= strtotime(retval_issetor($summary_data[1][$s_chek][0]));
                    if ($s_chek != $lastchkpt) {
                        $c_end_details = (isset($summary_data[1][$s_chek][1]) && $diff) ? cdate($summary_data[1][$s_chek][1], $rep) : ''; //checkpoint-out time
                    } else {
                        $c_end_details = '';
                    }
                    if ($s_chek == $lastchkpt) {
                        $firstlastoutdate = isset($summary_data[1][$s_chek][1]) ? $summary_data[1][$s_chek][1] : "";
                    }
                    $check_details[$main] .= "<td>" . $c_in_details . "</td>";
                    $check_details[$main] .= "<td>" . $c_end_details . "</td>";
                    if (isset($details) && !empty($details)) {
                        if ($c_end_details != '' && isset($details['etd'])) {
                            $expectedDate = date('Y-m-d', strtotime($c_end_details)) . " " . $details['etd'];
                            $routeStatus = getRouteRemark($c_end_details, $expectedDate);
                        } elseif ($c_in_details != '' && isset($details['eta'])) {
                            $expectedDate = date('Y-m-d', strtotime($c_in_details)) . " " . $details['eta'];
                            $routeStatus = getRouteRemark($c_in_details, $expectedDate);
                        }
                    }
                }
                $check_details[$main] .= "<td>" . $routeStatus . "</td>";
                $check_details[$main] .= '</tr>';
                /* destination trip algo ends */
                /* Return trip algo starts */
                if (isset($summary_data[2])) {
                    $check_details[$main] .= "<tr><td></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>STD</td>";
                    foreach ($checkpoints as $s_chek) {
                        $details = $rm->getRouteChkDetails($routeid, $s_chek);
                        $eta = isset($details['r_eta']) ? date('H:i', strtotime($details['r_eta'])) : '';
                        $etd = isset($details['r_etd']) ? date('H:i', strtotime($details['r_etd'])) : '';
                        $check_details[$main] .= "<td>" . $eta . "</td>";
                        $check_details[$main] .= "<td>" . $etd . "</td>";
                    }
                    $check_details[$main] .= "<td></td>";
                    $check_details[$main] .= '</tr>';
                    $check_details[$main] .= "<tr>" . $tripComplete . "<td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                    $end_chk_id = end($checkpoints); //to store the end checkpoint-id
                    $routeStatus = '';
                    foreach ($checkpoints as $s_chek) {
                        $details = $rm->getRouteChkDetails($routeid, $s_chek);
                        if ($s_chek != $lastchkpt) {
                            $c_in_details = isset($summary_data[2][$s_chek][0]) ? cdate($summary_data[2][$s_chek][0], $rep) : '';
                        } else {
                            $c_in_details = '';
                        }
                        //if end-checkpoint, then show as blank
                        if ($s_chek != $firstchkpt) {
                            $c_end_details = (isset($summary_data[2][$s_chek][1])) ? cdate($summary_data[2][$s_chek][1], $rep) : '';
                        } else {
                            $c_end_details = '';
                        }
                        if ($s_chek == $lastchkpt) {
                            $c_end_details = isset($summary_data[2][$s_chek][1]) ? $summary_data[2][$s_chek][1] : $firstlastoutdate;
                        }
                        $check_details[$main] .= "<td>$c_in_details</td>";
                        $check_details[$main] .= "<td>$c_end_details</td>";
                        if (isset($details) && !empty($details)) {
                            if ($c_end_details != '' && isset($details['r_etd'])) {
                                $expectedDate = date('Y-m-d', strtotime($c_end_details)) . " " . $details['r_etd'];
                                $routeStatus = getRouteRemark($c_end_details, $expectedDate);
                            } elseif ($c_in_details != '' && isset($details['r_eta'])) {
                                $expectedDate = date('Y-m-d', strtotime($c_in_details)) . " " . $details['r_eta'];
                                $routeStatus = getRouteRemark($c_in_details, $expectedDate);
                            }
                        }
                    }
                    $check_details[$main] .= "<td>" . $routeStatus . "</td>";
                    $check_details[$main] .= '</tr>';
                }
                /* Return trip algo ends */
            }
            $main++;
            $sr++;
            //}
        }
    }
    $display .= "</tbody></table>";
    $display .= "<br/>";
    if ($rep == 'pdf') {
        $display .= "<h2 style='text-align:center;'>Details</h2><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $display .= "<tbody>";
        //$display .= implode('<tr><td colspan="100%" style="height:10px"></td></tr>', $check_details);
        if (isset($check_details) && !empty($check_details)) {
            $display .= implode('<tr><td colspan="100%" style="height:10px"></td></tr>', $check_details);
        } else {
            $display .= '<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        }
    } else {
        $display .= "<h2>Details</h2><table class='table newTable'><tbody>";
        if (isset($check_details) && !empty($check_details)) {
            $display .= implode('<tr><td colspan="100%"></td></tr>', $check_details);
        } else {
            $display .= '<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        }
        $display .= "</tbody></table>";
        echo $display;
    }
}

function getTatSummaryRoute($route, $STdate, $EDdate, $rep = '', $cust_id = '') { 
    $customerno = ($cust_id == '') ? $_SESSION['customerno'] : $cust_id;
    $start_date = date("Y-m-d", strtotime($STdate));
    $end_date = date("Y-m-d", strtotime($EDdate));
    $cm = new CheckpointManager($customerno);
    $rm = new RouteManager($customerno);
    $sr = 1;
    //$all_checkpoints = $cm->get_customer_checkpoints($customerno);
    $route_details = $rm->get_routedetailsroute($route);
    $routes = $route_details[0];
    $route_names = $route_details[1];
    $getvehicledata = $route_details[2];
    $routeTat = $route_details[3];
    foreach ($route_names as $rtid => $routename) {
        $routename = $routename;
        $routeid = $rtid;
        $route_tat = $routeTat[$rtid];
    }
    $tableheader = "";
    $tableheaderOnward = "";
    $tableheaderReturn = "";
    $display = '';
    $check_details = array();
    $main = 0;
    $summarydata = array();
    $daterange_row_wisearr = array(); 
    /* echo"Vehicle Data:<pre>"; print_r($getvehicledata); exit(); */
    if (isset($getvehicledata)) {
        $datafirstroute = array();
        $checkpointsdata_byvehicles = array();
        foreach ($getvehicledata as $vehicleid => $vehicledata) {
            //if($vehicleid=='2733'){
           /*  $vehicleno = $rm->getvehiclenoforroute($vehicleid); */
            $vehicleno = $vehicledata['vehno'];
            $checkpoints_onwards_data = $rm->getroutebyvehicleid($vehicleid); // get checkpoints onwards data
            $tableheaderOnward = $checkpoints_onwards_data;
            $checkpoints_onwards_firstdata = reset($checkpoints_onwards_data);
            $checkpoints_onwards_enddata = end($checkpoints_onwards_data);
            $checkpoints_return_data = $rm->getroutebyvehicleidreverse($vehicleid); //  get return checkpoints data
            $tableheaderReturn = $checkpoints_return_data;
            $checkpoints_return_firstdata = reset($checkpoints_return_data);
            $checkpoints_return_enddata = end($checkpoints_return_data);
            $routecount = count($checkpoints_onwards_data);
            $first_chkid = $checkpoints_onwards_data[0]->checkpointid;
            $datafirstroute[$vehicleid]['firstdatedata'] = getfirstrouteoutdate($vehicleid, $first_chkid, $customerno, $start_date, $end_date);
            $getdatearr = $datafirstroute[$vehicleid]['firstdatedata'];
            $getdatearrcount = count($getdatearr);
            $firstdate = '';
            $lastdate = '';
            $tripArr = array();
            for ($i = 0; $i < $getdatearrcount; $i++) {
                $firstdate = $getdatearr[$i]['date'];
                $lastdate = isset($getdatearr[$i + 1]['date']) ? $getdatearr[$i + 1]['date'] : $end_date . " 23:59:59";
                $daterange_row_wisearr[] = array(
                    'vehicleid' => $vehicleid,
                    'firstdate' => $firstdate,
                    'lastdate' => $lastdate,
                );
                $routedata = getchkreportdata_report($vehicleid, $firstdate, $lastdate, $customerno);
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
                $breakid = 0;
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
                    $ArrinoutdateOnward[$chkpt]['sequence'] = "";
                    $countOnward = 0;
                    foreach ($testonArr as $row) {
                        if ($row['chkid'] == $chkpt && $row['status'] == 0 && $ArrinoutdateOnward[$chkpt]['indate'] == "") {
                            $ArrinoutdateOnward[$chkpt]['indate'] = $row['date'];
                        }
                        if ($row['chkid'] == $chkpt && $row['status'] == 1) {
                            $ArrinoutdateOnward[$chkpt]['outdate'] = $row['date'];
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
                $ArrinoutdateReturn = array();
                $dataIssetReturnFlag = 0;
                foreach ($checkpoints_return_data as $key => $val) {
                    $chkid = $val->checkpointid;
                    $ArrinoutdateReturn[$chkid]['indate'] = "";
                    $ArrinoutdateReturn[$chkid]['outdate'] = "";
                    $ArrinoutdateReturn[$chkid]['chkid'] = "";
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
                if ($dataIssetOnwardFlag != 0 && $dataIssetReturnFlag != 0) {
                    $tripArr[] = array(
                        'onwardJourney' => $ArrinoutdateOnward,
                        //'returnJourney' => $ArrinoutdateReturn,
                    );
                }
            }
            if (!empty($tripArr)) {
                $checkpointsdata_byvehicles[] = array(
                    'firstdata' => $onwardroutedata,
                    'lastdata' => $returnroutedata,
                    'daterangedates' => $daterange_row_wisearr,
                    'tripdata' => $tripArr,
                    'customerno' => $customerno,
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
            //}
        }
    }
    $summarydata[] = array(
        'routename' => $routename,
        'routeid' => $routeid,
        'alldata' => $checkpointsdata_byvehicles,
        'tableTH' => $tableheader,
    );
    //echo "<pre>"; print_r($summarydata);
    foreach ($summarydata as $row) {
        $route_name = $row['routename'];
        $routeid = $row['routeid'];
        $alldata = $row['alldata'];
        $tableth = $row['tableTH'];
    }
    $checkpoints = isset($tableth['onwardTH']) ? $tableth['onwardTH'] : "";
    if (isset($checkpoints)) {
        $z = 0;
        foreach ($alldata as $row2) {
            $vehicleno = $alldata[$z]['vehicleno'];
            $vehicleid = $alldata[$z]['vehicleid'];
            $daterangedates = $alldata[$z]['daterangedates'];
            $check_details[$main] = "<tr><th colspan='100%' style='background:#cccccc'>{$route_name} : {$vehicleno} -  RouteTat : {$route_tat}</th></tr>";
            $daterangedates = $alldata[$z]['daterangedates'];
            for ($i = 0; $i < count($daterangedates); $i++) {
                $tripdata = $alldata[$z]['tripdata'];
                $onwardJ = isset($tripdata[$i]['onwardJourney']) ? $tripdata[$i]['onwardJourney'] : NULL;
                $returnjourney = isset($tripdata[$i]['returnJourney']) ? $tripdata[$i]['returnJourney'] : NULL;
                $returnJ = isset($returnjourney) ? array_reverse($returnjourney) : NULL;
                $firstchkpt1 = !empty($onwardJ) ? reset($onwardJ) : NULL;
                $returnchkpt1 = isset($returnJ) ? reset($returnJ) : NULL;
                $onwardout = "";
                $returnin = "";
                $onwardout = $firstchkpt1['outdate'];
                $returnin = $returnchkpt1['indate'];
                $lastchkpt1 = !empty($onwardJ) ? end($onwardJ) : NULL;
                if (isset($onwardJ) && !empty($onwardJ)) {
                    $firstloop = array();
                    $firstchkpt = $firstchkpt1['chkid'];
                    $lastchkpt = $lastchkpt1['chkid'];
                    $check_details[$main] .= "<tr><td colspan='100%'></td></tr>"; // class='tableSub'
                    $check_details[$main] .= "<tr style='background: #d8d5d6;font-weight: bold;text-align: center;' ><td>Status</td><td>Vehicle No.</td><td>Route</td><td>Norms</td>"; // class='tableSub'
                    foreach ($checkpoints as $s_chek) {
                        @$c_name = $s_chek->cname;
                        $chkid = $s_chek->checkpointid;
                        $check_details[$main] .= "<td colspan=3>{$c_name}</td>";
                    }
                    $check_details[$main] .= "<td>Remark</td>";
                    $check_details[$main] .= '</tr>';
                    $check_details[$main] .= "<tr><td></td>";
                    $check_details[$main] .= "<td>{$vehicleno}</td>";
                    $check_details[$main] .= "<td>{$route_name}</td>";
                    $check_details[$main] .= "<td></td>";
                    foreach ($checkpoints as $s_chek) {
                        $check_details[$main] .= "<td>IN</td>";
                        $check_details[$main] .= "<td>ULT</td>";
                        $check_details[$main] .= "<td>OUT</td>";
                    }
                    $check_details[$main] .= "<td></td>";
                    $check_details[$main] .= '</tr>';
                    $check_details[$main] .= "<tr><td></td><td>{$vehicleno}</td><td>{$route_name}</td><td>STD</td>";
                    foreach ($checkpoints as $s_chek) {
                        @$c_name = $s_chek->cname;
                        $chkid = $s_chek->checkpointid;
                        $details = $rm->getRouteChkDetails($routeid, $chkid);
                        $eta = (isset($details['eta']) && $details['eta'] != '00:00:00') ? date('H:i', strtotime($details['eta'])) : '';
                        $etd = (isset($details['etd']) && $details['etd'] != '00:00:00') ? date('H:i', strtotime($details['etd'])) : '';
                        $ult = '';
                        if ($eta != '' && $etd != '') {
                            $ult = getTimeDiff($eta, $etd);
                        }
                        $check_details[$main] .= "<td>" . $eta . "</td>";
                        $check_details[$main] .= "<td>" . $ult . "</td>";
                        $check_details[$main] .= "<td>" . $etd . "</td>";
                    }
                    $check_details[$main] .= "<td></td>";
                    $check_details[$main] .= '</tr>';
                    $tripComplete = "<td></td>";
                    if ($onwardout != "" && $returnin != "") {
                        $check_details[$main] .= "<tr>" . $tripComplete . "<td>{$vehicleno}</td><td>{$route_name}</td><td></td>";
                        $tripComplete = "<td style='background-color:orange;font-weight:bold;'>Trip Complete</td>";
                    } else {
                        $check_details[$main] .= "<tr><td> </td><td>{$vehicleno}</td><td>{$route_name}</td><td>ACT</td>";
                    }
                    $routeStatus = '';
                    if (!empty($onwardJ)) {
                        foreach ($onwardJ as $onward) {
                            $chkid = $onward['chkid'];
                            $c_in_details = '';
                            $c_end_details = '';
                            $ault = '';
                            $lastChkIn = '';
                            $firstChkOut = '';
                            $details = $rm->getRouteChkDetails($routeid, $chkid);
                            if ($chkid != $firstchkpt) {
                                $c_in_details = isset($onward['indate']) ? cdate($onward['indate'], $rep) : ''; //checkpoint-in time
                            }
                            $diff = strtotime(retval_issetor($onward['outdate'])) >= strtotime(retval_issetor($onward['indate']));
                            if ($chkid != $lastchkpt) {
                                $c_end_details = (isset($onward['outdate']) && $diff) ? cdate($onward['outdate'], $rep) : ''; //checkpoint-out time
                            }
                            if ($chkid == $lastchkpt) {
                                $firstlastoutdate = isset($onward['outdate']) ? $onward['outdate'] : "";
                            }
                            if ($s_chek == $lastchkpt) {
                                $lastChkIn = isset($summary_data[2][$s_chek][0]) ? cdate($summary_data[2][$s_chek][0], $rep) : ''; //checkpoint-in time
                            }
                            if ($s_chek == $firstchkpt) {
                                $firstChkOut = (isset($summary_data[2][$s_chek][1]) && $diff) ? cdate($summary_data[2][$s_chek][1], $rep) : ''; //checkpoint-out time
                            }
                            if ($c_in_details != '' && $c_end_details != '') {
                                $ault = getTimeDiff($c_in_details, $c_end_details);
                            }
                            $check_details[$main] .= "<td>" . $c_in_details . "</td>";
                            $check_details[$main] .= "<td>" . $ault . "</td>";
                            $check_details[$main] .= "<td>" . $c_end_details . "</td>";
                            if ($firstChkOut != '' && $lastChkIn != '') {
                                //echo $firstChkOut."---".$lastChkIn."<br/>";
                                $routeStatus = getRouteRemarkInHrs($firstChkOut, $lastChkIn, $route_tat);
                            }
                        }
                    }
                    $check_details[$main] .= "<td>" . $routeStatus . "</td>";
                    $check_details[$main] .= '</tr>';
                    /* destination trip algo ends */
                    /* Return trip algo starts */
                    if (isset($returnJ) && !empty($onwardJ)) {
                        $check_details[$main] .= "<tr style='background: #d8d5d6;font-weight: bold;text-align: center;' ><td>Status</td><td>Vehicle No.</td><td>Route</td><td>Norms</td>"; // class='tableSub'
                        foreach ($checkpoints as $s_chek) {
                            @$c_name = $s_chek->cname;
                            $chkid = $s_chek->checkpointid;
                            $check_details[$main] .= "<td colspan=3>{$c_name}</td>";
                        }
                        $check_details[$main] .= "<td>Remark</td>";
                        $check_details[$main] .= '</tr>';
                        $check_details[$main] .= "<tr><td></td>";
                        $check_details[$main] .= "<td>{$vehicleno}</td>";
                        $check_details[$main] .= "<td>{$route_name}</td>";
                        $check_details[$main] .= "<td></td>";
                        foreach ($checkpoints as $s_chek) {
                            $check_details[$main] .= "<td>IN</td>";
                            $check_details[$main] .= "<td>ULT</td>";
                            $check_details[$main] .= "<td>OUT</td>";
                        }
                        $check_details[$main] .= "<td></td>";
                        $check_details[$main] .= '</tr>';
                        $check_details[$main] .= "<tr><td></td><td>{$vehicleno}</td><td>{$route_name}</td><td>STD</td>";
                        foreach ($checkpoints as $s_chek) {
                            @$c_name = $s_chek->cname;
                            $chkid = $s_chek->checkpointid;
                            $details = $rm->getRouteChkDetails($routeid, $chkid);
                            $eta = (isset($details['r_eta']) && $details['r_eta'] != '00:00:00') ? date('H:i', strtotime($details['r_eta'])) : '';
                            $etd = (isset($details['r_etd']) && $details['r_etd'] != '00:00:00') ? date('H:i', strtotime($details['r_etd'])) : '';
                            $ult = '';
                            if ($eta != '' && $etd != '') {
                                $ult = getTimeDiff($eta, $etd);
                            }
                            $check_details[$main] .= "<td>" . $eta . "</td>";
                            $check_details[$main] .= "<td>" . $ult . "</td>";
                            $check_details[$main] .= "<td>" . $etd . "</td>";
                        }
                        $check_details[$main] .= "<td></td>";
                        $check_details[$main] .= '</tr>';
                        $tripComplete;
                        $check_details[$main] .= "<tr><td></td><td>{$vehicleno}</td><td>{$route_name}</td><td>ACT</td>";
                        $end_chk_id = end($checkpoints); //to store the end checkpoint-id
                        $routeStatus = '';
                        foreach ($returnJ as $return) {
                            $chkid = $return['chkid'];
                            $details = $rm->getRouteChkDetails($routeid, $chkid);
                            $c_in_details = '';
                            $c_end_details = '';
                            $ault = '';
                            $lastChkIn = '';
                            $firstChkOut = '';
                            if ($chkid != $lastchkpt) {
                                $c_in_details = isset($return['indate']) ? cdate($return['indate'], $rep) : '';
                            }
                            //if end-checkpoint, then show as blank
                            if ($chkid != $firstchkpt) {
                                $c_end_details = (isset($return['outdate'])) ? cdate($return['outdate'], $rep) : '';
                            }
                            if ($chkid == $lastchkpt) {
                                $c_end_details = isset($return['outdate']) ? $return['outdate'] : $firstlastoutdate;
                            }
                            if ($s_chek == $lastchkpt) {
                                $lastChkIn = isset($summary_data[2][$s_chek][0]) ? cdate($summary_data[2][$s_chek][0], $rep) : ''; //checkpoint-in time
                            }
                            if ($s_chek == $firstchkpt) {
                                $firstChkOut = (isset($summary_data[2][$s_chek][1]) && $diff) ? cdate($summary_data[2][$s_chek][1], $rep) : ''; //checkpoint-out time
                            }
                            if ($c_in_details != '' && $c_end_details != '') {
                                $ault = getTimeDiff($c_in_details, $c_end_details);
                            }
                            $check_details[$main] .= "<td>$c_in_details</td>";
                            $check_details[$main] .= "<td>$ault</td>";
                            $check_details[$main] .= "<td>$c_end_details</td>";
                            if ($firstChkOut != '' && $lastChkIn != '') {
                                //echo $firstChkOut."---".$lastChkIn."<br/>";
                                $routeStatus = getRouteRemarkInHrs($firstChkOut, $lastChkIn, $route_tat);
                            }
                        }
                        $check_details[$main] .= "<td>" . $routeStatus . "</td>";
                        $check_details[$main] .= '</tr>';
                    }
                    /* Return trip algo ends */
                }
            }
            $z++;
            $main++;
            $sr++;
        }
    }
    $display .= "</tbody></table>";
    $display .= "<br/>";
    if ($rep == 'pdf') {
        $display .= "<h2 style='text-align:center;'>Details</h2><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $display .= "<tbody>";
        //$display .= implode('<tr><td colspan="100%" style="height:10px"></td></tr>', $check_details);
        if (isset($check_details) && !empty($check_details)) {
            $display .= implode('<tr><td colspan="100%" style="height:10px"></td></tr>', $check_details);
        } else {
            $display .= '<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        }
    } else {
        $display .= "<h2>Details</h2><table class='table newTable'><tbody>";
        if (isset($check_details) && !empty($check_details)) {
            $display .= implode('<tr><td colspan="100%"></td></tr>', $check_details);
        } else {
            $display .= '<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        }
        $display .= "</tbody></table>";
        echo $display;
    }
}

/* testing function for Route wise Tracking Report starts here */
function getTatSummaryRouteTest($route, $STdate, $EDdate, $rep = '', $cust_id = '')
{
    $customerno = ($cust_id == '') ? $_SESSION['customerno'] : $cust_id;
    $start_date = date("Y-m-d", strtotime($STdate));
    $end_date = date("Y-m-d", strtotime($EDdate));
    $checkPointManagerObject = new CheckpointManager($customerno);
    $routeManagerObject = new RouteManager($customerno);
    $dataFromRouteManager = $routeManagerObject->getRouteWiseData($route,$start_date,$end_date,$customerno);
    $data='';
    if(isset($dataFromRouteManager['checkPoints']) &&
        (@count($dataFromRouteManager['vehcile_wise_route_data'])>0) && ($dataFromRouteManager['vehcile_wise_route_data']!=false)
    )
    {
    
    $data.="<table style='display: block;overflow-x: auto;'><tbody>";

        /* Table rendering starts here */
        $display = '';
        
        $data.="<tr style='background: #d8d5d6;font-weight: bold;text-align: center;' >";
        $data.="<td>Vehicle No.</td>";
        foreach($dataFromRouteManager['checkPoints'] AS $key=>$value)
        {
                $data.="<td colspan='6'>".$value['cname']."</td>";
        }
        $data.="<td colspan='5'>Summary</td>";
        $data.="</tr>";
        $data.="<tr>";
        $data.="<th></th>";
        
        foreach($dataFromRouteManager['checkPoints'] AS $key=>$value)
        {
                $data.="<th>ETA</th>";
                $data.="<th>ETD</th>";
                $data.="<th>EHT</th>";
                $data.="<th>ATA</th>";
                $data.="<th>ATD</th>";
                $data.="<th>AHT</th>";
             
            
        }
        $data.="<th>ETAT</th>";
        $data.="<th>ATAT</th>";
        $data.="<th>Remark</th>";
        $data.="<th>Enroute Stoppage Count</th>";
        $data.="<th>Enroute Stoppage Time</th>";
      

        $data.="</tr>";
        $checkPointsDataCount = 0;
        if (isset($dataFromRouteManager['checkPoints']) && !empty($dataFromRouteManager['checkPoints'])) {
            $checkPointsDataCount = count($dataFromRouteManager['checkPoints']); 
        }
       /* Testing static data starts here */
       
       /* Testing static data ends here */
        //echo"data in renering is:<pre>"; print_r($dataFromRouteManager);
       if((@count($dataFromRouteManager['vehcile_wise_route_data'])>0) && ($dataFromRouteManager['vehcile_wise_route_data']!=false))
       {
           
            foreach($dataFromRouteManager['vehcile_wise_route_data'] AS $key=>$value)
            {
                foreach($value as $key1=>$value1)
                {
                   
                    foreach($value1 as $key2=>$value2)
                    {  
                        $data.="<td>".$key2."</td>";
                        foreach($value2 as $key3=>$value3)
                        {       
                                $data.="<td>".$value3['estimated_time_of_arrival']."</td>";
                                $data.="<td>".$value3['estimated_time_of_departure']."</td>";
                                $data.="<td>".$value3['estimated_halt_time']."</td>";
                                $data.="<td style='font-weight:bold;'>".$value3['actual_time_of_arrival']."</td>";
                                $data.="<td style='font-weight:bold;'>".$value3['actual_time_of_departure']."</td>";
                                if($value3['actual_halt_time']!='--')
                                {
                                    if(strtotime($value3['actual_halt_time']) > strtotime($value3['estimated_halt_time']))
                                    {
                                        $data.="<td style='background: red;color: #fff;font-weight:bold;'>".$value3['actual_halt_time']."</td>";
                                    }
                                    else
                                    {
                                        $data.="<td style='background: green;color: #fff;font-weight:bold;'>".$value3['actual_halt_time']."</td>";
                                    }
                                }
                                else
                                {
                                    $data.="<td style='font-weight:bold;'>".$value3['actual_halt_time']."</td>";
                                }
                                

                                // Testing strats here
                                if(array_key_exists('summary',$value3))
                                {
                                    $data.="<td>".$value3['summary']['tat']."</td>";
                                    $data.="<td>".$value3['summary']['atat']."</td>";

                                    
                                    if( //( preg_match("/Late/",$value2['summary']['Remark'])) || (preg_match("/not/",$value2['summary']['Remark'])) 
                                        $value3['summary']['Remark']==='Delayed')
                                    {
                                        $data.="<td style='background: red;color: #fff;'>".$value3['summary']['Remark']."</td>";
                                    }
                                    else if($value3['summary']['Remark']==='Early')
                                    {  
                                        $data.="<td style='background: yellow;color: #000;'>".$value3['summary']['Remark']."</td>";
                                    }
                                    else if(// (preg_match("/time/",$value2['summary']['Remark'])) 
                                        $value3['summary']['Remark']=='Ontime')
                                    {
                                        $data.="<td style='background: green;color: #fff;'>".$value3['summary']['Remark']."</td>";
                                    }
                                    else
                                    {
                                        
                                        $data.="<td>".$value3['summary']['Remark']."</td>";  
                                    }
                                    
                                    $data.="<td>".$value3['summary']['checkPointStoppageCount']."</td>";
                                    $data.="<td>".$value3['summary']['enrouteStoppageTime']."</td>";
                                   
                                }
                            
                                // Testing ends here 


                                
                        }
                    }
                    $data.="<tr>";
                   
                    
                    $data.="</tr>";
                }
            }
            
       }
       else
       {
            $data.="Data is not available";
       }
         
       $data .= "</tbody></table>";
       $data .= "<br/>";
        echo $data;
        
    }
    else
    {
        // $data.='<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        $data.='Data Not Available';
        echo $data;
        echo"";
    }
}    
/* testing function for Route wise Tracking Report ends here */



function getRouteRemark($actualTime, $expectedTime) {
    $remark = '';
    $actualTime = str_replace('<br/>', ' ', $actualTime);
    $expectedTime = str_replace('<br/>', ' ', $expectedTime);
    $actualTime = new DateTime($actualTime);
    $expectedTime = new DateTime($expectedTime);
    $interval = $expectedTime->diff($actualTime);
    $minutes = $interval->days * 24 * 60;
    $minutes += $interval->h * 60;
    $minutes += $interval->i;
    if ($minutes == 0) {
        $remark = "On Time";
    } elseif ($minutes > 0 && $interval->invert == 0) {
        $remark = "Delayed by " . $minutes . " minutes";
    } elseif ($minutes > 0 && $interval->invert == 1) {
        $remark = "Early by " . $minutes . " minutes";
    }
    return $remark;
}

function getRouteRemarkInHrs($actualTime, $expectedTime, $routeTat) {
    $remark = '';
    $actualTime = str_replace('<br/>', ' ', $actualTime);
    $expectedTime = str_replace('<br/>', ' ', $expectedTime);
    $actualTime = new DateTime($actualTime);
    $expectedTime = new DateTime($expectedTime);
    $interval = $expectedTime->diff($actualTime);
    //print_r($interval);
    $dayHrs = $interval->days * 24;
    $dayHrs += $interval->h;
    $hrsMin = $dayHrs . ":" . $interval->i;
    $diffTime = $dayHrs - $routeTat;
    $diffTime = $diffTime . ":" . $interval->i;
    if ($dayHrs == $routeTat) {
        $remark = "On Time";
    } elseif ($dayHrs > $routeTat && $interval->invert == 1) {
        $remark = "Delayed by " . $diffTime . " Hrs";
    } elseif ($dayHrs > $routeTat && $interval->invert == 0) {
        $remark = "Early by " . $diffTime . " Hrs";
    }
    return $remark;
}

function getTimeDiff($actualTime, $expectedTime) {
    $hrsMIn = 0;
    $actualTime = new DateTime($actualTime);
    $expectedTime = new DateTime($expectedTime);
    $interval = $expectedTime->diff($actualTime);
    $hrs = $interval->h;
    $hrsMIn = $hrs . ":" . $interval->i;
    return $hrsMIn;
}

function getTatSummary1($STdate, $EDdate, $rep = '', $cust_id = '') {
    $customerno = ($cust_id == '') ? $_SESSION['customerno'] : $cust_id;
    $start_date = date("Y-m-d", strtotime($STdate));
    $end_date = date("Y-m-d", strtotime($EDdate));
    $cm = new CheckpointManager($customerno);
    $rm = new RouteManager($customerno);
    $all_checkpoints = $cm->get_customer_checkpoints($customerno);
    $route_details = $rm->get_routedetails($_SESSION['groupid']);
    $routes = $route_details[0];
    $route_names = $route_details[1];
    $vehicles = $route_details[2];
    $routeTat = $route_details[3];
    $display = '';
    $check_details = array();
    $main = 0;
    foreach ($routes as $routeid => $route_data) {
        $route_name = $route_names[$routeid];
        $route_tat = $routeTat[$routeid];
        $test = explode('-', $route_name);
        $start_route = isset($test[0]) ? $test[0] : '';
        $end_route = isset($test[1]) ? $test[1] : '';
        if (!isset($test[0]) || !isset($test[1])) {
            $start_route = $route_name[0];
            $end_route = $route_name[1];
        }
        $sr = 1;
        foreach ($route_data as $vehicleid => $checkpoints) {
            //if($vehicleid==2714){
            $unitno = $vehicles[$vehicleid]['unitno'];
            $summary_data = pullReportSummary($start_date, $end_date, $vehicleid, $unitno, $customerno, $checkpoints);
            if (isset($summary_data[1])) {
                //echo "<pre>";
                //print_r($summary_data);
                $firstloop = array();
                foreach ($summary_data[1] as $key => $val) {
                    $firstloop[] = $key;
                }
                $firstChekcpointArr = array_slice($firstloop, 1, 1);
                $firstchkpt = reset($firstChekcpointArr); //reset($firstloop)-1;
                $lastchkpt = end($firstloop);
                $check_details[$main] = "<tr><th colspan='100%' style='background:#cccccc'>{$route_name} : {$vehicles[$vehicleid]['vehno']} - RouteTat (In Hrs) : {$route_tat}</th></tr>
                <tr style='background: #d8d5d6;font-weight: bold;text-align: center;' ><td>Status</td><td>Vehicle No.</td><td>Route</td><td>Norms</td>"; // class='tableSub'
                foreach ($checkpoints as $s_chek) {
                    @$c_name = $all_checkpoints[$s_chek];
                    $check_details[$main] .= "<td colspan=3>$c_name</td>";
                }
                $check_details[$main] .= "<td>Remark</td>";
                $check_details[$main] .= '</tr>';
                $check_details[$main] .= "<tr><td></td>";
                $check_details[$main] .= "<td>{$vehicles[$vehicleid]['vehno']}</td>";
                $check_details[$main] .= "<td>{$route_name}</td>";
                $check_details[$main] .= "<td></td>";
                foreach ($checkpoints as $s_chek) {
                    $check_details[$main] .= "<td>IN</td>";
                    $check_details[$main] .= "<td>ULT</td>";
                    $check_details[$main] .= "<td>OUT</td>";
                }
                $check_details[$main] .= "<td></td>";
                $check_details[$main] .= '</tr>';
                $check_details[$main] .= "<tr><td></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>STD</td>";
                foreach ($checkpoints as $s_chek) {
                    $details = $rm->getRouteChkDetails($routeid, $s_chek);
                    //echo $details['eta'];
                    $eta = (isset($details['eta']) && $details['eta'] != "00:00:00") ? date('H:i', strtotime($details['eta'])) : '';
                    $etd = (isset($details['etd']) && $details['etd'] != "00:00:00") ? date('H:i', strtotime($details['etd'])) : '';
                    $ult = '';
                    if ($eta != '' && $etd != '') {
                        $ult = getTimeDiff($eta, $etd);
                    }
                    $check_details[$main] .= "<td>" . $eta . "</td>";
                    $check_details[$main] .= "<td>" . $ult . " </td>";
                    $check_details[$main] .= "<td>" . $etd . "</td>";
                }
                $check_details[$main] .= "<td></td>";
                $check_details[$main] .= '</tr>';
                /* destination trip algo starts */
                $tripComplete = '<td></td>';
                if ($summary_data[0] == 'left_start') {
                    $check_details[$main] .= "<tr><td><img src='../../images/right_trip.png' alt='---&gt;'/></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } elseif ($summary_data[0] == 'complete') {
                    $check_details[$main] .= "<tr>" . $tripComplete . "<td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                    $tripComplete = "<td style='background-color:orange;font-weight:bold;'>Trip Complete</td>";
                } elseif ($summary_data[0] == 'start') {
                    $check_details[$main] .= "<tr><td style='background-color:green;font-weight:bold;'>YES(Start)</td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } elseif ($summary_data[0] == 'left_end') {
                    $check_details[$main] .= "<tr><td><img src='../../images/left_trip.png' alt='&lt;---' /></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } elseif ($summary_data[0] == 'end') {
                    $check_details[$main] .= "<tr><td style='background-color:green;font-weight:bold;'>YES(End)</td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                } else {
                    $check_details[$main] .= "<tr><td></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                }
                $routeStatus = '';
                $firstChkOut = '';
                $lastChkIn = '';
                foreach ($checkpoints as $s_chek) {
                    $details = $rm->getRouteChkDetails($routeid, $s_chek);
                    $c_in_details = '';
                    $c_end_details = '';
                    $ault = '';
                    if ($s_chek != $firstchkpt) {
                        $c_in_details = isset($summary_data[1][$s_chek][0]) ? cdate($summary_data[1][$s_chek][0], $rep) : ''; //checkpoint-in time
                    }
                    $diff = strtotime(retval_issetor($summary_data[1][$s_chek][1])) >= strtotime(retval_issetor($summary_data[1][$s_chek][0]));
                    if ($s_chek != $lastchkpt) {
                        $c_end_details = (isset($summary_data[1][$s_chek][1]) && $diff) ? cdate($summary_data[1][$s_chek][1], $rep) : ''; //checkpoint-out time
                    }
                    if ($s_chek == $firstchkpt) {
                        $firstChkOut = (isset($summary_data[1][$s_chek][1]) && $diff) ? cdate($summary_data[1][$s_chek][1], $rep) : ''; //checkpoint-out time
                    }
                    if ($s_chek == $lastchkpt) {
                        $lastChkIn = isset($summary_data[1][$s_chek][0]) ? cdate($summary_data[1][$s_chek][0], $rep) : ''; //checkpoint-in time
                    }
                    if ($s_chek == $lastchkpt) {
                        $firstlastoutdate = isset($summary_data[1][$s_chek][1]) ? $summary_data[1][$s_chek][1] : "";
                    }
                    if ($c_in_details != '' && $c_end_details != '') {
                        $ault = getTimeDiff($c_in_details, $c_end_details);
                    }
                    $check_details[$main] .= "<td>" . $c_in_details . "</td>";
                    $check_details[$main] .= "<td>" . $ault . "</td>";
                    $check_details[$main] .= "<td>" . $c_end_details . "</td>";
                    if ($firstChkOut != '' && $lastChkIn != '') {
                        //echo $firstChkOut."---".$lastChkIn."<br/>";
                        $routeStatus = getRouteRemarkInHrs($firstChkOut, $lastChkIn, $route_tat);
                    }
                }
                $check_details[$main] .= "<td>" . $routeStatus . "</td>";
                $check_details[$main] .= '</tr>';
                /* destination trip algo ends */
                /* Return trip algo starts */
                if (isset($summary_data[2])) {
                    $checkpoints = array_reverse($checkpoints);
                    $check_details[$main] .= "<tr><th colspan='100%' style='background:#cccccc'>{$route_name} : {$vehicles[$vehicleid]['vehno']} - RouteTat (In Hrs) : {$route_tat}</th></tr>
                    <tr style='background: #d8d5d6;font-weight: bold;text-align: center;' ><td>Status</td><td>Vehicle No.</td><td>Route</td><td>Norms</td>"; // class='tableSub'
                    foreach ($checkpoints as $s_chek) {
                        @$c_name = $all_checkpoints[$s_chek];
                        $check_details[$main] .= "<td colspan=3>$c_name</td>";
                    }
                    $check_details[$main] .= "<td>Remark</td>";
                    $check_details[$main] .= '</tr>';
                    $check_details[$main] .= "<tr><td></td>";
                    $check_details[$main] .= "<td>{$vehicles[$vehicleid]['vehno']}</td>";
                    $check_details[$main] .= "<td>{$route_name}</td>";
                    $check_details[$main] .= "<td></td>";
                    foreach ($checkpoints as $s_chek) {
                        $check_details[$main] .= "<td>IN</td>";
                        $check_details[$main] .= "<td>ULT</td>";
                        $check_details[$main] .= "<td>OUT</td>";
                    }
                    $check_details[$main] .= "<td></td>";
                    $check_details[$main] .= '</tr>';
                    $check_details[$main] .= "<tr><td></td><td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>STD</td>";
                    foreach ($checkpoints as $s_chek) {
                        $details = $rm->getRouteChkDetails($routeid, $s_chek);
                        $eta = (isset($details['r_eta']) && $details['r_eta'] != '00:00:00') ? date('H:i', strtotime($details['r_eta'])) : '';
                        $etd = (isset($details['r_etd']) && $details['r_etd'] != '00:00:00') ? date('H:i', strtotime($details['r_etd'])) : '';
                        $ult = '';
                        if ($eta != '' && $etd != '') {
                            $ult = getTimeDiff($eta, $etd);
                        }
                        $check_details[$main] .= "<td>" . $eta . "</td>";
                        $check_details[$main] .= "<td>" . $ult . "</td>";
                        $check_details[$main] .= "<td>" . $etd . "</td>";
                    }
                    $check_details[$main] .= "<td></td>";
                    $check_details[$main] .= '</tr>';
                    $check_details[$main] .= "<tr>" . $tripComplete . "<td>{$vehicles[$vehicleid]['vehno']}</td><td>{$route_name}</td><td>ACT</td>";
                    $end_chk_id = end($checkpoints); //to store the end checkpoint-id
                    $routeStatus = '';
                    foreach ($checkpoints as $s_chek) {
                        $details = $rm->getRouteChkDetails($routeid, $s_chek);
                        $c_in_details = '';
                        $c_end_details = '';
                        $ault = '';
                        $lastChkIn = '';
                        $firstChkOut = '';
                        if ($s_chek != $lastchkpt) {
                            $c_in_details = isset($summary_data[2][$s_chek][0]) ? cdate($summary_data[2][$s_chek][0], $rep) : '';
                        }
                        //if end-checkpoint, then show as blank
                        if ($s_chek != $firstchkpt) {
                            $c_end_details = (isset($summary_data[2][$s_chek][1])) ? cdate($summary_data[2][$s_chek][1], $rep) : '';
                        }
                        if ($s_chek == $lastchkpt) {
                            $c_end_details = isset($summary_data[2][$s_chek][1]) ? $summary_data[2][$s_chek][1] : $firstlastoutdate;
                        }
                        if ($s_chek == $lastchkpt) {
                            $lastChkIn = isset($summary_data[2][$s_chek][0]) ? cdate($summary_data[2][$s_chek][0], $rep) : ''; //checkpoint-in time
                        }
                        if ($s_chek == $firstchkpt) {
                            $firstChkOut = (isset($summary_data[2][$s_chek][1]) && $diff) ? cdate($summary_data[2][$s_chek][1], $rep) : ''; //checkpoint-out time
                        }
                        if ($c_in_details != '' && $c_end_details != '') {
                            $ault = getTimeDiff($c_in_details, $c_end_details);
                        }
                        $check_details[$main] .= "<td>$c_in_details</td>";
                        $check_details[$main] .= "<td>$ault</td>";
                        $check_details[$main] .= "<td>$c_end_details</td>";
                        if ($firstChkOut != '' && $lastChkIn != '') {
                            //echo $firstChkOut;
                            //echo $lastChkIn;
                            $routeStatus = getRouteRemarkInHrs($firstChkOut, $lastChkIn, $route_tat);
                        }
                    }
                    $check_details[$main] .= "<td>" . $routeStatus . "</td>";
                    $check_details[$main] .= '</tr>';
                }
                /* Return trip algo ends */
            }
            $main++;
            $sr++;
            //}
        }
    }
    $display .= "</tbody></table>";
    $display .= "<br/>";
    if ($rep == 'pdf') {
        $display .= "<h2 style='text-align:center;'>Details</h2><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $display .= "<tbody>";
        //$display .= implode('<tr><td colspan="100%" style="height:10px"></td></tr>', $check_details);
        if (isset($check_details) && !empty($check_details)) {
            $display .= implode('<tr><td colspan="100%" style="height:10px"></td></tr>', $check_details);
        } else {
            $display .= '<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        }
    } else {
        $display .= "<h2>Details</h2><table class='table newTable'><tbody>";
        if (isset($check_details) && !empty($check_details)) {
            $display .= implode('<tr><td colspan="100%"></td></tr>', $check_details);
        } else {
            $display .= '<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        }
        $display .= "</tbody></table>";
        echo $display;
    }
}

function pullReportSummary($STdate, $EDdate, $vehicleid, $unitno, $customerno, $checkpoints) {
    $firstCheckpoint = reset($checkpoints);
    $lastCheckpoint = end($checkpoints);
    $returnTripData = array();
    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
    $Query = "select * from V" . $vehicleid . " WHERE date BETWEEN '$STdate 00:00:00' AND '$EDdate 23:59:59' order by chkrepid asc";
    try {
        $db = new PDO($path);
        $result = $db->query($Query);
        if (isset($result) && $result != "") {
            $i = 1;
            $firstTrip = array();
            $returnTrip = array();
            foreach ($result as $row) {
                if (retval_issetor($returnTripData[0]) == 'Trip Complete') {
                    $returnTripData[0] = '';
                    $firstTrip = array();
                    $returnTrip = array();
                    if (isset($returnTrip[$firstCheckpoint][0])) {
                        $firstTrip[$firstCheckpoint][0] = $temp_data;
                    }
                }
                if (retval_issetor($returnTripData[0]) == 'Trip End Point Left') {
                    if ($row["chkid"] == $firstCheckpoint) {
                        $returnTripData[0] = 'Trip Complete';
                    }
                    //$returnTrip = $returnTripData;
                    $returnTrip[$row["chkid"]][$row["status"]] = $row["date"];
                } else {
                    if ($row["chkid"] == $firstCheckpoint) {
                        if ($row["status"] == 0) {
                            $returnTripData[0] = 'Trip Start Point';
                        } else {
                            $returnTripData[0] = 'Trip Start Point Left';
                        }
                        $firstTrip[0] = $returnTripData[0];
                        $firstTrip[$row["chkid"]][$row["status"]] = $row["date"];
                    }
                    if (isset($returnTripData[0])) {
                        if ($row["chkid"] == $lastCheckpoint) {
                            if ($row["status"] == 0) {
                                $returnTripData[0] = 'Trip End Point';
                            } else {
                                $returnTripData[0] = 'Trip End Point Left';
                            }
                            $returnTrip[0] = $returnTripData[0];
                        }
                        $firstTrip[$row["chkid"]][$row["status"]] = $row["date"];
                    }
                }
                $i++;
            }
        }
    } catch (PDOException $e) {
        $CHKMS = 0;
    }
    if (!isset($returnTripData[0])) {
        $returnTripData[0] = 'not-Found';
    }
    if (!empty($firstTrip) && $returnTripData[0] != 'Trip Start Point') {
        $returnTripData[1] = $firstTrip;
    }
    if (!empty($returnTrip) && $returnTripData[0] != 'Trip Start Point') {
        $returnTripData[2] = $returnTrip;
    }
    return $returnTripData;
}

/* testing function for Route wise Live Tracking Report starts here */
function getTatSummaryRouteLiveTest($route, $STdate, $EDdate, $rep = '', $cust_id = '')
{
    $customerno = ($cust_id == '') ? $_SESSION['customerno'] : $cust_id;
    $start_date = date("Y-m-d", strtotime($STdate));
    $end_date = date("Y-m-d", strtotime($EDdate));
    $checkPointManagerObject = new CheckpointManager($customerno);
    $routeManagerObject = new RouteManager($customerno);
    $dataFromRouteManager = $routeManagerObject->getRouteWiseData($route,$start_date,$end_date,$customerno,'liveReport');
    $data='';
    $data.="<table><tbody>";
    if(isset($dataFromRouteManager['checkPoints']))
    {
        /* Table rendering starts here */
        $display = '';
        
        $data.="<tr style='background: #d8d5d6;font-weight: bold;text-align: center;' >";
        $data.="<td>Vehicle No.</td>";
        $data.="<td>Current Location</td>";
        foreach($dataFromRouteManager['checkPoints'] AS $key=>$value)
        {
                $data.="<td colspan='1'>".$value['cname']."</td>";
        }
       /*  $data.="<td colspan='3'>Summary</td>"; */
        $data.="</tr>";
        $data.="<tr>";
        $data.="<th></th>";
        $data.="<th></th>";
        foreach($dataFromRouteManager['checkPoints'] AS $key=>$value)
        {
                $data.="<th>ETA</th>";
             /*    $data.="<th>ETD</th>";
                $data.="<th>EHT</th>";
                $data.="<th>ATA</th>";
                $data.="<th>ATD</th>";
                $data.="<th>AHT</th>"; */
               /*  $data.="<th>ETAT</th>"; 
                $data.="<th>ATAT</th>";
                $data.="<th>check Point Stoppage Count</th>";
                $data.="<th>Remark</th>"; */
            
        }
        /*  $data.="<th>ATAT</th>";
         $data.="<th>ETAT</th>";
         $data.="<th>Remark</th>"; */
       /* $data.="<th>Remark</th>";
        $data.="<th>Enroute Stoppage Count</th>"; */
        //$data.="<th>Total Duration</th>";

        $data.="</tr>";
        
       $checkPointsDataCount = count($dataFromRouteManager['checkPoints']); 
      // echo"PHP info: ".phpinfo(); exit();
       if(count($dataFromRouteManager['vehcile_wise_route_data'])>0)
       {
           //echo"<br> Rendering data is: <pre>"; print_r($dataFromRouteManager['vehcile_wise_route_data']); //exit();
            foreach($dataFromRouteManager['vehcile_wise_route_data'] AS $key=>$value)
            {
                foreach($value as $key1=>$value1)
                {
                     //echo"<br>Key is: ".$key1." and Value is: ".$value1;    // got here vehicle NO
                    foreach($value1 as $key2=>$value2)
                    {  
                        $data.="<td>".$key2."</td>";
                        $data.="<td>".$value2['currentLocation']."</td>"; 
                        
                        //echo"inner loop data:<pre>"; print_r($value2);
                        //$data.="<td>".$value2[0]['currentLocation']."</td>";
                        //echo"value2:<pre>";print_r($value2);
                        foreach($value2 as $key3=>$value3)
                        {         
                            if($key3!='currentLocation')
                            {
                                $data.="<td>".$value3['ETA']."</td>";
                            }
                            
                            
                        }
                        /* if(@array_key_exists('summary',$value3))
                        {
                                $data.="<td>".$value3['summary']['tat']."</td>";
                                $data.="<td>".$value3['summary']['tat']."</td>";
                                $data.="<td>".$value3['summary']['tat']."</td>";
                        } */
                    }
                    $data.="<tr>";
                    //$data.="<td>".$key1."</td>";
                    
                    $data.="</tr>";
                }
            }
            
       }
       else
       {
            $data.="Data is not available";
       }
         
       $data .= "</tbody></table>";
       $data .= "<br/>";
        echo $data;
        /* echo"Count is: ".count($dataFromRouteManager['vehcile_wise_route_data']);
        echo"route data is: <pre>"; print_r($dataFromRouteManager['vehcile_wise_route_data']);  */
    }
    else
    {
        $data.='<tr><td colspan="100%" style="text-align:center;">Data Not Available</td></tr>';
        echo"";
    }
}    
/* testing function for Route wise Live Tracking Report ends here */