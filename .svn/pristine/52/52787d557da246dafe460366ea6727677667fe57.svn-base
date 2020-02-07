<?php
include 'reports_fuelefficiency_functions.php';
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    $STdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['STdate'], 'string')));
    $EDdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['EDdate'], 'string')));

    $chktype = GetSafeValueString($_POST['chktype'], 'string');
    if ($chktype == '2') {
        $chkpt_start = GetSafeValueString($_POST['checkpointtype_start'], 'string');
        $chkpt_end = GetSafeValueString($_POST['checkpointtype_end'], 'string');
        $chkpt_via = GetSafeValueString($_POST['checkpointtype_via'], 'string');
        $checkword = 'Checkpoint Types';
    } elseif ($chktype == '1' || $chktype == "") {
        $chkpt_start = GetSafeValueString($_POST['checkpoint_start'], 'string');
        $chkpt_end = GetSafeValueString($_POST['checkpoint_end'], 'string');
        $chkpt_via = GetSafeValueString($_POST['checkpoint_via'], 'string');
        $checkword = 'Checkpoints';
    }

    $datediffcheck = date_SDiff($STdate, $EDdate);
    $ReportType = GetSafeValueString($_POST['report'], 'string');
    $vehicleid = GetSafeValueString($_POST['vehicleid'], 'int');
    $vehicleno = GetSafeValueString($_POST['vehicleno'], 'string');
    if ($vehicleno == '') {
        $vehicleid = 0;
    }
    $vehicleid = ($vehicleid != '') ? $vehicleid : 0;

    if (strtotime($STdate) > strtotime($EDdate)) {
        echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    } elseif (isset($_SESSION['ecodeid'])) {
        /* Client Code Validation */
        $validation = clientCodeValidation($_POST['s_start'], $_POST['e_end'], $_POST['days'], $STdate, $EDdate);
        if (isset($validation) && !empty($validation)) {
            if ($validation['isError'] == 1) {
                echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
                die();
            } else {
                $STdate = date('d-m-Y', strtotime($validation['reportStartDate']));
                $EDdate = date('d-m-Y', strtotime($validation['reportEndDate']));
                echo "<script>jQuery('#SDate').val('" . $STdate . "');</script>";
                echo "<script>jQuery('#EDate').val('" . $EDdate . "');</script>";
            }
        }
        if ($datediffcheck <= 30) {
            if ($ReportType == 'trip') {
                $reports = getdailyreport_check($STdate, $EDdate, $_POST['STime'], $_POST['ETime'], $chkpt_start, $chkpt_end, $chkpt_via, $vehicleid, $chktype);
                if (isset($reports)) {
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <td colspan="100%">
                                    Between <?php echo $checkword . ' <strong>' . get_checkpointname($chkpt_start, $chktype); ?> To <?php echo get_checkpointname($chkpt_end, $chktype); ?>
                                    <?php
                                    if (!empty($chkpt_via)) {
                                        echo " Via " . get_checkpointname($chkpt_via, $chktype);
                                    }
                                    ?>
                                    </strong> From <?php echo date("M j Y", strtotime($STdate)); ?> To <?php echo date("M j Y", strtotime($EDdate)); ?>
                                </td>
                            </tr>
                            <tr>
                                <?php
                                if ($chktype == '2') {
                                    echo '<td>Sr No</td>';
                                }
                                ?>
                                <td>Vehicle No</td>
                                <?php
                                if ($chktype == '2') {
                                    echo '<td>Checkpoint Route</td>';
                                }
                                ?>
                                <td>Distance Travelled (Km)</td>
                                <td>Fuel Consumed (Lt)</td>
                                <td>Time Taken[Hours :Minutes]</td>
                                <td>Idle Time[Hours :Minutes]</td>
                                <td>Start Date</td>
                                <td>End Date</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($reports as $thisvehicle) {
                                foreach ($thisvehicle as $report) {
                                    ?>
                                    <tr>
                                        <?php
                                        if ($chktype == '2') {
                                            echo '<td>' . $i . '</td>';
                                        }
                                        ?>
                                        <td><?php echo $report->vehicleno; ?></td>
                                        <?php
                                        if ($chktype == '2') {
                                            echo '<td>' . $report->route . '</td>';
                                        }
                                        ?>
                                        <td><?php
                                            if ($report->distance != '0') {
                                                echo $report->distance;
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $report->fuelconsume; ?></td>
                                        <td>
                                            <?php
                                            if ($report->distance != '0') {
                                                echo m2h(getduration($report->enddate, $report->startdate));
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($report->idletime != '') {
                                                echo m2h($report->idletime);
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo convertDateToFormat($report->startdate, speedConstants::DEFAULT_DATETIME); ?></td>
                                        <td><?php echo convertDateToFormat($report->enddate, speedConstants::DEFAULT_DATETIME); ?></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                            <tr>
                                <td colspan="100%">
                                    <strong>Note</strong> : Idle Time is the total duration when the vehicle has stopped more than 5 mins during the journey.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                }
            } elseif ($ReportType == 'vehi') {
                $reports1 = getdailyreport_check_by_vehicle($STdate, $EDdate, $_POST['STime'], $_POST['ETime'], $chkpt_start, $chkpt_end, $chkpt_via, $vehicleid, $chktype);
                if (isset($reports1)) {
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <td colspan="100%">
                                    Between <?php echo $checkword . '  <strong>' . get_checkpointname($chkpt_start, $chktype); ?> To <?php echo get_checkpointname($chkpt_end, $chktype); ?>
                                    <?php
                                    if (!empty($chkpt_via)) {
                                        echo " Via " . get_checkpointname($chkpt_via, $chktype);
                                    }
                                    ?>
                                    </strong> From <?php echo date("M j Y", strtotime($STdate)); ?> To <?php echo date("M j Y", strtotime($EDdate)); ?> </td>
                            </tr>
                            <tr>
                                <?php
//                                if ($chktype == '2') {
//                                    echo '<td>Sr No</td>';
//                                }
                                ?>
                                <td>Vehicle no</td>
                                <?php
                                if ($chktype == '2') {
                                    echo '<td>Checkpoint Route</td>';
                                }
                                ?>
                                <td>Trip</td>
                                <td>Avg. Distance Travelled (Km)</td>
                                <td>Avg. Fuel Consumed (Lt)</td>
                                <td>Avg. Time Taken[Hours :Minutes]</td>
                                <td>Avg. Idle Time[Hours :Minutes]</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        }
                        foreach ($reports1 as $thisvehicle) {
                            $new_report = array();
                            $i = 1;
                            $route = '';
                            foreach ($thisvehicle as $report) {

                                $vehicleçheck = $report->vehicleno;
                                $tripcount = 1;
                                if ($vehiclecheck == $report->vehicleno) {
                                    if ($chktype == '2') {
                                        $new_report['srno'] = $i;
                                        $new_report['route'] = $report->route;
                                    }
                                    $new_report['vehicleno'] = $report->vehicleno;
                                    $new_report['distance'] += $report->distance;
                                    if ($report->fuelconsume > 0) {
                                        $new_report['fuelconsume'] += $report->fuelconsume;
                                    } else {
                                        $new_report['fuelconsume'] += "N/A";
                                    }
                                    $new_report['idletime'] += $report->idletime;
                                    $new_report['timetaken'] += getduration($report->enddate, $report->startdate);
                                    $new_report['startdate'] = $STdate;
                                    $new_report['enddate'] = $EDdate;
                                    $new_report['trip'] += $tripcount;
                                    $tripcount += 1;
                                    $i++;
                                }
                            }
                            if (!empty($new_report)) {
                                ?>
                                <tr>
                                    <?php
//                                    if ($chktype == '2') {
//                                        echo '<td>' . $new_report['srno'] . '</td>';
//                                    }
                                    ?>
                                    <td><?php echo $new_report['vehicleno']; ?></td>
                                    <?php
//                                    if ($chktype == '2') {
//                                        echo '<td>' . $new_report['route'] . '</td>';
//                                    }
                                    ?>
                                    <td><?php echo $new_report['trip']; ?></td>
                                    <td>
                                        <?php
                                        if ($new_report['distance'] != '0') {
                                            echo round($new_report['distance'] / $new_report['trip'], 2);
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($new_report['fuelconsume'] > 0) {
                                            echo $new_report['fuelconsume'] / $new_report['trip'];
                                        } else {
                                            echo $new_report['fuelconsume'] = "N/A";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($new_report['distance'] != '0') {
                                            echo m2h($new_report['timetaken'] / $new_report['trip']);
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($new_report['idletime'] != '') {
                                            echo m2h($new_report['idletime'] / $new_report['trip']);
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>
                                    </td>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <tr>
                            <td colspan="100%">
                                <strong>Note</strong> : Idle Time is the total duration when the vehicle has stopped more than 5 mins during the journey.
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php
            }
        } else {
            echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
        }
    } else {
        if ($datediffcheck <= 30) {
            if ($ReportType == 'trip') {
                $reports = getdailyreport_check($STdate, $EDdate, $_POST['STime'], $_POST['ETime'], $chkpt_start, $chkpt_end, $chkpt_via, $vehicleid, $chktype);
                if (isset($reports) && !empty($reports)) {
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <td colspan="100%">Between <?php echo $checkword . ' <strong>' . get_checkpointname($chkpt_start, $chktype); ?> To <?php echo get_checkpointname($chkpt_end, $chktype); ?><?php
                                    if (!empty($chkpt_via)) {
                                        echo " Via " . get_checkpointname($chkpt_via, $chktype);
                                    }
                                    ?></strong> From <?php echo date("M j Y", strtotime($STdate)); ?> To <?php echo date("M j Y", strtotime($EDdate)); ?> </td>
                            </tr>
                            <tr>
                                <?php
                                if ($chktype == '2') {
                                    echo '<td>Sr No</td>';
                                }
                                ?>
                                <td>Vehicle No</td>
                                <?php
                                if ($chktype == '2') {
                                    echo '<td>Checkpoint Route</td>';
                                }
                                ?>
                                <td>Distance Travelled (Km)</td>
                                <td>Fuel Consumed (Lt)</td>
                                <td>Time Taken[Hours :Minutes]</td>
                                <td>Idle Time[Hours :Minutes]</td>
                                <td>Start Date</td>
                                <td>End Date</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($reports as $thisvehicle) {
                                $i = 1;
                                if ($thisvehicle) {
                                    foreach ($thisvehicle as $report) {
                                        ?>
                                        <tr>
                                            <?php
                                            if ($chktype == '2') {
                                                echo '<td>' . $i . '</td>';
                                            }
                                            ?>
                                            <td><?php echo $report->vehicleno; ?></td>
                                            <?php
                                            if ($chktype == '2') {
                                                echo '<td>' . $report->route . '</td>';
                                            }
                                            ?>
                                            <td><?php
                                                if ($report->distance != '0') {
                                                    echo $report->distance;
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $report->fuelconsume; ?></td>
                                            <td>
                                                <?php
                                                if ($report->distance != '0') {
                                                    echo m2h(getduration($report->enddate, $report->startdate));
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($report->idletime != '') {
                                                    echo m2h($report->idletime);
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo convertDateToFormat($report->startdate, speedConstants::DEFAULT_DATETIME); ?>
                                            <td><?php echo convertDateToFormat($report->enddate, speedConstants::DEFAULT_DATETIME); ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                }
                            }
                            ?>
                            <tr>
                                <td colspan="100%"><strong>Note</strong> : Idle Time is the total duration when the vehicle has stopped more than 5 mins during the journey.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                }
            } elseif ($ReportType == 'vehi') {
                $reports1 = getdailyreport_check_by_vehicle($STdate, $EDdate, $_POST['STime'], $_POST['ETime'], $chkpt_start, $chkpt_end, $chkpt_via, $vehicleid, $chktype);
                if (isset($reports1) && !empty($reports1)) {
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <td colspan="100%">Between <?php echo $checkword . '  <strong>' . get_checkpointname($chkpt_start, $chktype); ?> To <?php echo get_checkpointname($chkpt_end, $chktype); ?><?php
                                    if (!empty($chkpt_via)) {
                                        echo " Via " . get_checkpointname($chkpt_via, $chktype);
                                    }
                                    ?> </strong> From <?php echo date("M j Y", strtotime($STdate)); ?> To <?php echo date("M j Y", strtotime($EDdate)); ?> </td>
                            </tr>
                            <tr>
                                <?php
//                                if ($chktype == '2') {
//                                    echo '<td>Sr No</td>';
//                                }
                                ?>
                                <td>Vehicle no</td>
                                <?php
//                                if ($chktype == '2') {
//                                    echo '<td>Checkpoint Route</td>';
//                                }
                                ?>
                                <td>Trip</td>
                                <td>Avg. Distance Travelled (Km)</td>
                                <td>Avg. Fuel Consumed (Lt)</td>
                                <td>Avg. Time Taken[Hours :Minutes]</td>
                                <td>Avg. Idle Time[Hours :Minutes]</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        }
                        foreach ($reports1 as $thisvehicle) {
                            $new_report = array();
                            if (isset($thisvehicle) && !empty($thisvehicle)) {
                                $distance = 0;
                                $fuelconsume = 0;
                                $idletime = 0;
                                $timetaken = 0;
                                $trip = 0;
                                $i = 1;
                                foreach ($thisvehicle as $report) {
                                    $vehicleçheck = isset($report->vehicleno) ? $report->vehicleno : "";
                                    $tripcount = 1;
                                    if ($vehicleçheck == $report->vehicleno) {
                                        if ($chktype == '2') {
                                            $new_report['srno'] = $i;
                                            $new_report['route'] = $report->route;
                                        }
                                        $new_report['vehicleno'] = $report->vehicleno;
                                        $distance += $report->distance;
                                        $new_report['distance'] = $distance;
                                        $fuelconsume += $report->fuelconsume;
                                        if ($report->fuelconsume > 0) {
                                            $new_report['fuelconsume'] = $fuelconsume;
                                        } else {
                                            $new_report['fuelconsume'] = "N/A";
                                        }
                                        $idletime += $report->idletime;
                                        $new_report['idletime'] = $idletime;
                                        $timetaken += getduration($report->enddate, $report->startdate);
                                        $new_report['timetaken'] = $timetaken;
                                        $new_report['startdate'] = $STdate;
                                        $new_report['enddate'] = $EDdate;
                                        $trip += $tripcount;
                                        $new_report['trip'] = $trip;
                                        $tripcount += 1;
                                    }
                                }
                            }
                            if (!empty($new_report)) {
                                ?>
                                <tr>
                                    <?php
//                                    if ($chktype == '2') {
//                                        echo '<td>' . $new_report['srno'] . '</td>';
//                                    }
                                    ?>

                                    <td><?php echo $new_report['vehicleno']; ?></td>
                                    <?php
//                                    if ($chktype == '2') {
//                                        echo '<td>' . $new_report['route'] . '</td>';
//                                    }
                                    ?>
                                    <td><?php echo $new_report['trip']; ?></td>
                                    <td>
                                        <?php
                                        if ($new_report['distance'] != '0') {
                                            echo round($new_report['distance'] / $new_report['trip'], 2);
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                        if ($new_report['fuelconsume'] > 0) {
                                            echo $new_report['fuelconsume'] / $new_report['trip'];
                                        } else {
                                            echo $new_report['fuelconsume'] = "N/A";
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                        if ($new_report['distance'] != '0') {
                                            echo m2h($new_report['timetaken'] / $new_report['trip']);
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                        if ($new_report['idletime'] != '') {
                                            echo m2h($new_report['idletime'] / $new_report['trip']);
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>

                                    </td>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <tr> <td colspan="100%"><strong>Note</strong> : Idle Time is the total duration when the vehicle has stopped more than 5 mins during the journey.</td></tr>
                    </tbody>
                </table>
                <?php
            }
        } else {
            echo "<script type='text/javascript'>
                jQuery('#error4').show();jQuery('#error4').fadeOut(3000);
                </script>";
        }
    }
}
?>
