<br/>
<form action="route_dashboard.php" method="POST">
<?php
    if (!isset($_POST['STime'])) {$stime = "00:00";} else { $stime = $_POST['STime'];}
    if (!isset($_POST['ETime'])) {$etime = "23:59";} else { $etime = $_POST['ETime'];}
?>
<table><tr>
    <td>Start Hour</td>
    <td>
        <input id="STime" name="STime" type="text" class="input-mini" data-date="<?php echo $stime; ?>" />
    </td>
    <td>End Hour</td>
    <td>
        <input id="ETime" name="ETime" type="text" class="input-mini" data-date2="<?php echo $etime; ?>" />
    </td>
        <td><input type="submit"   class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
    </tr>
    </table>
<br/>
<table class="table  table-bordered table-striped dTableR dataTable"  style=" width:99%">
    <thead>
<?php
    $shops = getshopscount();
    if (isset($shops)) {
        echo "<tr>";
        echo "<td><b>Route</b></td>";
        echo "<td><b>Vehicle No.</b></td>";
        for ($i = 0; $i < $shops; $i++) {
            if ($i == 0) {
                $startPoint = 'MFPL';
                if ($_SESSION['customerno'] == 95) {
                    $startPoint = 'Shop 0';
                }
                echo "<td style='text-align:center;'><b>" . $startPoint . "</b></td>";
            } else {
                echo "<td style='text-align:center;'><b>Shop $i</b></td>";
            }
            echo "<td colspan='3'></td>";
        }
        echo "<td colspan='4' style='text-align:center;'><b>Status</b></td>";
        echo "<td style='text-align:center;'><b>ETA Status</b></td></tr></thead>";
        echo "<tbody>";
        $routelist = getroutes_list();
        if (isset($routelist) && !empty($routelist)) {
            foreach ($routelist as $route_list) {
                $vehicles = getvehicles_latlng($route_list->routeid);
                if (isset($vehicles)) {
                    foreach ($vehicles as $vehicle) {
                        echo "<tr><td><b>$route_list->routename</b></td>";
                        echo "<td><b>$vehicle->vehicleno</b></td>";
                        $routeid = get_routeid($vehicle->vehicleid);
                        $routes = getroutes($vehicle->vehicleid);
                        $routecount = count($routes);
                        $status = '';
                        if ($vehicle->ignition == '1') {
                            $color = 'green';
                        } else {
                            $color = 'grey';
                        }
                        if (isset($routes)) {
                            $lastelement = end($routes);
                            $f_element = array_values($routes);
                            $firstelement = array_shift($f_element);
                            $k = 0;
                            $z = 0;
                            $l = 0;
                            $image = vehicleimage($vehicle);
                            echo "<input type='hidden' id='latlong" . $vehicle->vehicleid . "' value='" . $vehicle->devicelat . "," . $vehicle->devicelong . "'/>
                            <input type='hidden' id='vehicleimage" . $vehicle->vehicleid . "' value='" . $image . "'/>
                            <input type='hidden' id='vehicle$vehicle->vehicleid' value='$vehicle->vehicleno'>";
                            $reportsequences_desc = report_desc_seq_new($vehicle->vehicleid, $_SESSION['customerno'], $firstelement->checkpointid, $lastelement->checkpointid, $stime, $etime);
                          if($reportsequences_desc != 0){
                            $lastarray = array_slice($reportsequences_desc, 0, 1);
                            $lastarray = isset($lastarray[0]) ? $lastarray[0] : null;
                            $reportsequences_desc = array_reverse($reportsequences_desc);
                            $datacount = count($reportsequences_desc);
                        }
                            if (isset($reportsequences_desc) && !empty($reportsequences_desc) && $reportsequences_desc != 0) {
                                foreach ($reportsequences_desc as $reportsequence) {
                                    $flag = 0;
                                    $proceed = 0;
                                    //print_r( $routes[$z]);
                                    if (isset($reportsequence) && !empty($reportsequence) && $reportsequence->chkid != $routes[$z]->checkpointid) {
                                        $counter = 0;
                                        $flag = 1;
                                        for ($q = $z; $q < $routecount; $q++) {
                                            if ($reportsequence->chkid != $routes[$q]->checkpointid) {
                                                $counter++;
                                            } elseif ($reportsequence->chkid == $routes[$q]->checkpointid) {
                                                if ($counter != 0) {
                                                    for ($c = 0; $c < $counter; $c++) {
                                                        $currentchkpt = getlatlng_chkpt($routes[$z]->checkpointid, $vehicle->vehicleid);
                                                        echo "<td style='text-align:center;'>
                                                        <i class='fa fa-times route-tooltip-top' original-title='Checkpoint Name: $currentchkpt->checkpointname <br/> ETA Status : $currentchkpt->etaStatus'></i></td>
                                                        <td></td><td></td><td></td>";
                                                        $z++;
                                                    }
                                                }
                                                break;
                                            }
                                        }
                                    }
                                    if ($reportsequence->chkid == $routes[$z]->checkpointid && $reportsequence->status == 0) {
                                        $chkpt = getlatlng_chkpt($reportsequence->chkid, $vehicle->vehicleid);
                                        if ($vehicle->ignition == '1') {
                                            $invariable = "<td style='text-align:center;'>
                                            <a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                            <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Checkpoint: $chkpt->checkpointname
                                            <br> Speed: $vehicle->curspeed <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . "
                                            <br/> ETA Status : $chkpt->etaStatus
                                            <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td><td></td><td></td><td></td>";
                                        } else {
                                            $invariable = "<td style='text-align:center;'>
                                            <a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                            <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Checkpoint: $chkpt->checkpointname
                                            <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . "
                                            <br/> ETA Status : $chkpt->etaStatus
                                            <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td><td></td><td></td><td></td>";
                                        }
                                        $l++;
                                    } elseif ($reportsequence->chkid == $routes[$z]->checkpointid && $reportsequence->status == 1 && isset($routes[$z + 1]->checkpointid)
                                        && !isset($reportsequences_desc[$k + 1]->chkid) && !isset($reportsequences_desc[$k + 1]->status)) {
                                        $currentchkpt = getlatlng_chkpt($reportsequence->chkid, $vehicle->vehicleid);
                                        $nextchkpt = getlatlng_chkpt($routes[$z + 1]->checkpointid, $vehicle->vehicleid);
                                        $d = distance($currentchkpt->lat, $currentchkpt->long, $nextchkpt->lat, $nextchkpt->long, 'K');
                                        $x = distance($vehicle->devicelat, $vehicle->devicelong, $nextchkpt->lat, $nextchkpt->long, 'K');
                                        if (strpos($currentchkpt->etaStatus, 'Delayed') !== false) {
                                            $checkcolor = 'red';
                                        } else if (strpos($currentchkpt->etaStatus, 'Early') !== false) {
                                            $checkcolor = 'blue';
                                        } else {
                                            $checkcolor = 'green';
                                        }
                                        $invariable = "<td style='text-align:center;'>
                                        <i class='fa fa-check fa-lg route-tooltip-top' original-title='Vehicle no: $vehicle->vehicleno
                                        <br> Checkpoint: $currentchkpt->checkpointname <br> Speed: $vehicle->curspeed <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . "
                                        <br/> ETA Status : $currentchkpt->etaStatus
                                        <br> Distance: $x ' style='color:$checkcolor;'></i></td>";
                                        if ($x <= (1 / 3) * $d) {
                                            $invariable .= "<td style='text-align:center;'>
                                            <a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                            <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed
                                            <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated)) . " <br> Distance: $x from $nextchkpt->checkpointname
                                            <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td><td></td><td></td>";
                                        } elseif ($x <= (2 / 3) * $d) {
                                            $invariable .= "<td></td><td style='text-align:center;'>
                                            <a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                            <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed
                                            <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated)) . " <br> Distance: $x from $nextchkpt->checkpointname
                                            <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td><td></td>";
                                        } elseif ($x <= $d && $x >= (2 / 3) * $d) {
                                            $invariable .= "<td></td><td></td><td style='text-align:center;'>
                                            <a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                            <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed
                                            <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated)) . " <br> Distance: $x from $nextchkpt->checkpointname
                                            <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td>";
                                        } else {
                                            $invariable .= "<td></td><td></td><td style='text-align:center;'>
                                            <a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                            <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed
                                            <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated)) . " <br> Distance: $x from $nextchkpt->checkpointname
                                            <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td>";
                                        }
                                        $l++;
                                    } elseif ($reportsequence->chkid == $routes[$z]->checkpointid && $reportsequence->status == 1 && $reportsequence->chkid != $lastelement->checkpointid) {
                                        $chkpt = getlatlng_chkpt($reportsequence->chkid, $vehicle->vehicleid);
                                        if (strpos($chkpt->etaStatus, 'Delayed') !== false) {
                                            $checkcolor = 'red';
                                        } else if (strpos($chkpt->etaStatus, 'Early') !== false) {
                                            $checkcolor = 'blue';
                                        } else {
                                            $checkcolor = 'green';
                                        }
                                        $invariable = "<td style='text-align:center;'>
                                        <i class='fa fa-check fa-lg route-tooltip-top' original-title='Vehicle no: $vehicle->vehicleno
                                        <br> Checkpoint: $chkpt->checkpointname <br> Speed: $vehicle->curspeed
                                        <br/> ETA Status : $chkpt->etaStatus
                                        <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . " ' style='color:$checkcolor;'></i></td>
                                        <td></td><td></td><td></td>";
                                        $l++;
                                    } elseif ($reportsequence->chkid == $routes[$z]->checkpointid && $reportsequence->status == 1 && $reportsequence->chkid == $lastelement->checkpointid) {
                                        $chkpt = getlatlng_chkpt($reportsequence->chkid, $vehicle->vehicleid);
                                        if (strpos($chkpt->etaStatus, 'Delayed') !== false) {
                                            $checkcolor = "red";
                                        } else if (strpos($chkpt->etaStatus, 'Early') !== false) {
                                            $checkcolor = 'blue';
                                        } else {
                                            $checkcolor = "green";
                                        }
                                        $invariable = "<td style='text-align:center;'>
                                        <i class='fa fa-check fa-lg route-tooltip-top' original-title='Vehicle no: $vehicle->vehicleno
                                        <br> Checkpoint: $chkpt->checkpointname <br> Speed: $vehicle->curspeed
                                        <br/> ETA Status : $chkpt->etaStatus
                                        <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . " ' style='color:$checkcolor;'></i></td>
                                        <td></td><td></td><td></td>";
                                    } elseif ($reportsequence->chkid == $routes[$z]->checkpointid && ($reportsequence->chkid == $lastelement->checkpointid) && $reportsequence->status == 1 && isset($reportsequences_desc[$k + 1]->chkid)) {
                                        $proceed = 1;
                                    }
                                    if ($proceed == 1) {
                                        $n = 0;
                                        for ($j = $k; $j < $datacount; $j++) {
                                            if ($reportsequences_desc[$j]->chkid == $firstelement->checkpointid && $reportsequences_desc[$j]->status == '0') {
                                                $n++;
                                                $status = 'reached';
                                            }
                                        }
                                        $currentchkpt = getlatlng_chkpt($reportsequence->chkid, $vehicle->vehicleid);
                                        if (strpos($currentchkpt->etaStatus, 'Delayed') !== false) {
                                            $checkcolor = "red";
                                        } else if (strpos($currentchkpt->etaStatus, 'Early') !== false) {
                                            $checkcolor = 'blue';
                                        }else {
                                            $checkcolor = "green";
                                        }
                                        if (isset($status) && $status == 'reached') {
                                            $invariable = "<td style='text-align:center;'><i class='fa fa-check fa-lg route-tooltip-top' original-title='Vehicle no: $vehicle->vehicleno
                                            <br> Checkpoint: $currentchkpt->checkpointname <br> Speed: $vehicle->curspeed
                                            <br/> ETA Status : $currentchkpt->etaStatus
                                            <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . " ' style='color:$checkcolor;'></i></td>
                                            <td></td><td></td><td></td>";
                                        } else {
                                            $nextchkpt = getlatlng_chkpt($firstelement->checkpointid, $vehicle->vehicleid);
                                            $d = distance($currentchkpt->lat, $currentchkpt->long, $nextchkpt->lat, $nextchkpt->long, 'K');
                                            $x = distance($vehicle->devicelat, $vehicle->devicelong, $nextchkpt->lat, $nextchkpt->long, 'K');
                                            $invariable = "<td style='text-align:center;'><i class='fa fa-check fa-lg route-tooltip-top' original-title='Vehicle no: $vehicle->vehicleno
                                            <br> Checkpoint: $currentchkpt->checkpointname <br> Speed: $vehicle->curspeed
                                            <br/> ETA Status : $currentchkpt->etaStatus
                                            <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . "
                                            <br> Distance: $x ' style='color:$checkcolor;'></i></td>";
                                            if ($x <= (1 / 3) * $d) {
                                                $invariable .= "<td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                                <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed
                                                <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . " <br> Distance: $x from $nextchkpt->checkpointname
                                                <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td><td></td><td></td>";
                                            } elseif ($x <= (2 / 3) * $d) {
                                                $invariable .= "<td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                                <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed
                                                <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . " <br> Distance: $x from $nextchkpt->checkpointname
                                                <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td><td></td>";
                                            } elseif ($x <= $d && $x >= (2 / 3) * $d) {
                                                $invariable .= "<td></td><td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                                <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed
                                                <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . " <br> Distance: $x from $nextchkpt->checkpointname
                                                <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td>";
                                            } else {
                                                $invariable .= "<td></td><td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                                <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed
                                                <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated)) . " <br> Distance: $x from $nextchkpt->checkpointname
                                                <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td>";
                                            }
                                        }
                                    }
                                    if (isset($reportsequences_desc[$k + 1]) && $reportsequence->chkid != $reportsequences_desc[$k + 1]->chkid && $flag == 0) {
                                        echo $invariable;
                                        $z++;
                                        if ($reportsequence->chkid == $lastelement->checkpointid || $z == $shops) {
                                            break;
                                        }
                                    } elseif ($z == $routecount && $status != 'reached') {
                                        break;
                                    }
                                    $k++;
                                }
                            }
                            $notvisitedshops = $routecount - $z;
                            for ($l = 1; $l <= $notvisitedshops; $l++) {
                                $currentchkpt = getlatlng_chkpt($routes[$z]->checkpointid, $vehicle->vehicleid);
                                echo "<td style='text-align:center;'><i class='fa fa-times route-tooltip-top' original-title='Checkpoint Name: $currentchkpt->checkpointname <br/> ETA Status : $currentchkpt->etaStatus'></i></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                $z++;
                            }
                            $cols = $shops - $z;
                            for ($j = 1; $j <= $cols; $j++) {
                                if ($status == 'reached') {
                                    echo "<td style='text-align:center;'>N/A</td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                } else {
                                    echo "<td style='text-align:center;'>---</td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                }
                            }
                            if ($status == 'reached') {
                                echo "<td><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed
                                <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated)) . " <br> Temp 1 :
                                <br> Temp 2 : ' style='color:$color;'></i><a/></td><td colspan='3' style='text-align:center;'>Cycle Complete</td>";
                            } elseif (isset($lastarray) && $lastarray->chkid == $firstelement->checkpointid) {
                                echo "<td><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno
                                <br> Speed: $vehicle->curspeed <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated)) . "
                                <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i><a/></td><td colspan='3' style='text-align:center;'>Cycle Complete</td>";
                            } else {
                                echo "<td><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'>
                                <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno
                                <br> Speed: $vehicle->curspeed <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated)) . "
                                <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i><a/></td><td colspan='3' style='text-align:center;'></td>";
                            }
                            $status = getChkEtaStatus($vehicle->vehicleid);
                            $statusString = "";
                            if (isset($status)) {
                                $statusString = $status->checkpointname . " " . $status->etaStatus;

                            }
                            echo "<td  style='text-align:center;'>$statusString</td>";
                            echo "</tr>";
                        } else {
                            echo "<td colspan='100%' style='text-align:center;'>No Route Added</td>
                            </tr>";
                        }
                        $z = '';
                    }
                } else {
                 /*   echo "<tr>
                        <td><b>$route_list->routename</b></td><td colspan='100%' style='text-align:center;'>No Vehicles Added</td>
                        </tr>";*/
                }
            }
        }
        echo "</tbody>";
    } else {
        echo "<tr>
            <td colspan='100%' style='text-align:center;'>No Route</td>
        </tr>";
    }
?>
</table>
</form>
<a href="#x" class="overlay" id="route_popup"></a>
<div class="popup">
    <div id="map_route"></div>
    <div id="info" align="center"></div>
    <a class="close1" href="#close"></a>
</div>
