<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
set_time_limit(0);
if (!isset($_SESSION)) {
    session_start();
}
ob_start();
//date_default_timezone_set("Asia/Calcutta");
if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
date_default_timezone_set('' . $_SESSION['timezone'] . '');
include_once 'informatics_functions_new.php';
?>
<div style="width:auto; height:30px;">
    <table style="width: auto; border:none;">
        <tr>
            <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
            <td style="width:420px; border:none;"><h3 style="text-transform:uppercase;">Informatics</h3><br /></td>
            <td style="width:230px;border:none;"><img src="../../images/elixia_logo_75.png"  /></td>
        </tr>
    </table>
</div>

<hr />

<div class="entry" style='min-height:400px;'>

    <br/>
    <!-- starts, input table -->
    <?php
    $s_date = isset($_REQUEST['SDate']) ? $_REQUEST['SDate'] : date('d-m-Y');
    $e_date = isset($_REQUEST['EDate']) ? $_REQUEST['EDate'] : date('d-m-Y');
    $currentMonthName = date('F', strtotime($e_date));
    ?>
    <!-- ends, input table -->
    <?php
    if (isset($_REQUEST['to_get']) && $_REQUEST['to_get'] == 'get_informatics_report') {
        if (validate_input($s_date, $e_date)) {
            $start_date = date('Y-m-d', strtotime($s_date));
            $end_date = date('Y-m-d', strtotime($e_date));
            ?>
            <!-- Starts, Reports-->
            <style type='text/css'>
                table, td { border: solid 1px  #999999; color:#000000; }
                hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                #tblInfo{
                    text-align: center;
                    border-right:1px solid black;
                    border-bottom:1px solid black;
                    border-collapse:collapse;
                    font-family:Arial;
                    font-size: 10pt;
                    width: 1000px;
                }

                #tblInfo td, th{
                    border-left:1px solid black;
                    border-top:1px solid black;
                }

                .greentd{
                    background-color:#2cb62c;
                }
                .yellowtd{
                    background-color:#eff048;
                }
                .redtd{
                    background-color:#ee3d1d;
                }
            </style>

            <!-- Starts, Reports-->
            <div style="width:1000px;" id='informaticsStart'>

                <!-- Starts, Installed devices -->
                <?php
                //$total_devices_installed = count($all_vehicles); //$devicemanager->get_all_devices_count($start_date, $end_date);
                //$device_installed_display = ($total_devices_installed <= 1) ? $total_devices_installed . " Device" : $total_devices_installed . " Devices";
                ?>
            <!--                <div style='float:left'>Total Installed Devices: <b><?php // echo $device_installed_display;                                                       ?></b></div>
                <br/><hr>-->
                <!-- Ends, Installed devices -->

                <!-- Daily Reports Data -->
                <?php
                $dailyreportdata = isset($_SESSION["dailyreportdata"]) ? $_SESSION["dailyreportdata"] : get_daily_report_data($start_date, $end_date);
                ?>
                <table id="tblInfo" align='center' style='width: 1000px; font-size:13px; text-align:center;border:1px solid #000;'>
                    <tr>
                        <td colspan="7"><strong><?php echo $currentMonthName; ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="7"><strong>Overspeed</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Region</strong></td>
                        <td><strong>Zone</strong></td>
                        <td><strong>Office Name</strong></td>
                        <td><strong>Vehicle Number</strong></td>
                        <td class="greentd"><strong>Green</strong></td>
                        <td class="yellowtd"><strong>Yellow</strong></td>
                        <td class="redtd"><strong>Red</strong></td>
                    </tr>
                    <?php
                    $overspeeddataVehicleWise = isset($dailyreportdata['overspeedsumVehicleWise']) ? $dailyreportdata['overspeedsumVehicleWise'] : null;
                    if (isset($overspeeddataVehicleWise)) {
                        foreach ($overspeeddataVehicleWise as $data) {
                            $vehicledatacount = array(
                                'green' => 0
                                , 'yellow' => 0
                                , 'red' => 0
                            );
                            if ($data['overspeed'] <= 40) {
                                $vehicledatacount['green'] = $data['overspeed'];
                            }
                            else if ($data['overspeed'] > 40 && $data['overspeed'] <= 55) {
                                $vehicledatacount['yellow'] = $data['overspeed'];
                            }
                            else if ($data['overspeed'] > 55) {
                                $vehicledatacount['red'] = $data['overspeed'];
                            }
                            ?>
                            <tr>
                                <td><?php echo $data['regionname'] ?></td>
                                <td><?php echo $data['zonename'] ?></td>
                                <td><?php echo $data['branchname'] ?></td>
                                <td><?php echo $data['vehicleno'] ?></td>
                                <td><?php echo $vehicledatacount['green'] ?></td>
                                <td><?php echo $vehicledatacount['yellow'] ?></td>
                                <td><?php echo $vehicledatacount['red'] ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <tr><td colspan="7"><br/></td></tr>
                    <tr>
                        <td colspan="7"><strong><?php echo $currentMonthName; ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="7"><strong>Night Drive</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Region</strong></td>
                        <td><strong>Zone</strong></td>
                        <td><strong>Office Name</strong></td>
                        <td><strong>Vehicle Number</strong></td>
                        <td class="greentd"><strong>Green</strong></td>
                        <td class="yellowtd"><strong>Yellow</strong></td>
                        <td class="redtd"><strong>Red</strong></td>
                    </tr>
                    <?php
                    $nightDriveVehicleWise = isset($dailyreportdata['nightDriveVehicleWise']) ? $dailyreportdata['nightDriveVehicleWise'] : null;
                    if (isset($nightDriveVehicleWise)) {
                        foreach ($nightDriveVehicleWise as $data) {
                            $vehicledatacount = array(
                                'green' => 0
                                , 'yellow' => 0
                                , 'red' => 0
                            );
                            if ($data['night_drive'] < 10) {
                                $vehicledatacount['green'] = $data['night_drive'];
                            }
                            else if ($data['night_drive'] >= 10 && $data['night_drive'] <= 20) {
                                $vehicledatacount['yellow'] = $data['night_drive'];
                            }
                            else if ($data['night_drive'] > 20) {
                                $vehicledatacount['red'] = $data['night_drive'];
                            }
                            ?>
                            <tr>
                                <td><?php echo $data['regionname'] ?></td>
                                <td><?php echo $data['zonename'] ?></td>
                                <td><?php echo $data['branchname'] ?></td>
                                <td><?php echo $data['vehicleno'] ?></td>
                                <td><?php echo $vehicledatacount['green'] ?></td>
                                <td><?php echo $vehicledatacount['yellow'] ?></td>
                                <td><?php echo $vehicledatacount['red'] ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <tr><td colspan="7"><br/></td></tr>
                    <tr>
                        <td colspan="7"><strong><?php echo $currentMonthName; ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="7"><strong>Weekend / Holiday Trip</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Region</strong></td>
                        <td><strong>Zone</strong></td>
                        <td><strong>Office Name</strong></td>
                        <td><strong>Vehicle Number</strong></td>
                        <td class="greentd"><strong>Green</strong></td>
                        <td class="yellowtd"><strong>Yellow</strong></td>
                        <td class="redtd"><strong>Red</strong></td>
                    </tr>
                    <?php
                    $arrData = array();
                    $arrDates = array();
                    $weekendDriveVehicleWiseTotal = isset($dailyreportdata['weekendDriveVehicleWiseTotal']) ? $dailyreportdata['weekendDriveVehicleWiseTotal'] : null;
                    if (isset($weekendDriveVehicleWiseTotal)) {
                        foreach ($weekendDriveVehicleWiseTotal as $data) {
                            $isRed = 0;
                            $isYellow = 0;
                            $isGreen = 0;
                            foreach ($data['weekend_info'] as $weekdata) {
                                if ($weekdata->weekend_distance > 45) {
                                    $isRed +=1;
                                }
                                else if ($weekdata->weekend_distance <= 45 && $weekdata->weekend_distance > 25) {
                                    $isYellow +=1;
                                }
                                else if ($weekdata->weekend_distance < 25) {
                                    $isGreen +=1;
                                }
                                $arrDates[] = $weekdata->weekend_date;
                            }
                            $data['isRed'] = $isRed;
                            $data['isYellow'] = $isYellow;
                            $data['isGreen'] = $isGreen;
                            $arrData[] = $data;
                        }

                        foreach ($arrData as $data) {
                            ?>
                            <tr>
                                <td><?php echo $data['regionname'] ?></td>
                                <td><?php echo $data['zonename'] ?></td>
                                <td><?php echo $data['branchname'] ?></td>
                                <td><?php echo $data['vehicleno'] ?></td>
                                <td><?php echo $data['isGreen'] ?></td>
                                <td><?php echo $data['isYellow'] ?></td>
                                <td><?php echo $data['isRed'] ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <tr><td colspan="7"><br/></td></tr>

                    <tr>
                        <td colspan="7"><strong><?php echo $currentMonthName; ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="7"><strong>Weekend / Holiday Trip Detailed</strong></td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <table style='font-size:13px; text-align:center;border:1px solid #000;'>
                                <tr>
                                    <td><strong>Region</strong></td>
                                    <td><strong>Zone</strong></td>
                                    <td><strong>Office Name</strong></td>
                                    <td><strong>Vehicle Number</strong></td>
                                    <?php
                                    foreach (array_unique($arrDates) as $dates) {
                                        echo "<td><strong>" . date('d-m-Y', strtotime($dates)) . "</strong></td>";
                                    }
                                    ?>
                                </tr>
                                <tr><td colspan="8" class="redtd">Red Zone</td></tr>
                                <?php
                                foreach ($arrData as $data) {
                                    if ($data['isRed'] > 0) {
                                        ?>
                                        <tr>
                                            <td><?php echo $data['regionname'] ?></td>
                                            <td><?php echo $data['zonename'] ?></td>
                                            <td><?php echo $data['branchname'] ?></td>
                                            <td><?php echo $data['vehicleno'] ?></td>
                                            <?php
                                            foreach ($data['weekend_info'] as $weekdata) {
                                                echo "<td>" . $weekdata->weekend_distance . "</td>";
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                <tr><td colspan="8" class="yellowtd">Yellow Zone</td></tr>
                                <?php
                                foreach ($arrData as $data) {
                                    if ($data['isRed'] == 0 && $data['isYellow'] > 0) {
                                        ?>
                                        <tr>
                                            <td><?php echo $data['regionname'] ?></td>
                                            <td><?php echo $data['zonename'] ?></td>
                                            <td><?php echo $data['branchname'] ?></td>
                                            <td><?php echo $data['vehicleno'] ?></td>
                                            <?php
                                            foreach ($data['weekend_info'] as $weekdata) {
                                                echo "<td>" . $weekdata->weekend_distance . "</td>";
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                <tr><td colspan="8" class="greentd">Green Zone</td></tr>
                                <?php
                                foreach ($arrData as $data) {
                                    if ($data['isRed'] == 0 && $data['isYellow'] == 0 && $data['isGreen'] > 0) {
                                        ?>
                                        <tr>
                                            <td><?php echo $data['regionname'] ?></td>
                                            <td><?php echo $data['zonename'] ?></td>
                                            <td><?php echo $data['branchname'] ?></td>
                                            <td><?php echo $data['vehicleno'] ?></td>
                                            <?php
                                            foreach ($data['weekend_info'] as $weekdata) {
                                                echo "<td>" . $weekdata->weekend_distance . "</td>";
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>

                            </table>

                        </td>
                    </tr>
                    <tr><td colspan="7"><br/></td></tr>
                    <tr>
                        <td colspan="7"><strong><?php echo $currentMonthName; ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="7"><strong>Harsh Break</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Region</strong></td>
                        <td><strong>Zone</strong></td>
                        <td><strong>Office Name</strong></td>
                        <td><strong>Vehicle Number</strong></td>
                        <td class="greentd"><strong>Green</strong></td>
                        <td class="yellowtd"><strong>Yellow</strong></td>
                        <td class="redtd"><strong>Red</strong></td>
                    </tr>
                    <?php
                    $harshbreakVehicleWise = isset($dailyreportdata['harshbreakVehicleWise']) ? $dailyreportdata['harshbreakVehicleWise'] : null;
                    if (isset($harshbreakVehicleWise)) {
                        foreach ($harshbreakVehicleWise as $data) {
                            $vehicledatacount = array(
                                'green' => 0
                                , 'yellow' => 0
                                , 'red' => 0
                            );
                            if ($data['harsh_break'] < 10) {
                                $vehicledatacount['green'] = $data['harsh_break'];
                            }
                            else if ($data['harsh_break'] >= 10 && $data['harsh_break'] <= 20) {
                                $vehicledatacount['yellow'] = $data['harsh_break'];
                            }
                            else if ($data['harsh_break'] > 20) {
                                $vehicledatacount['red'] = $data['harsh_break'];
                            }
                            ?>
                            <tr>
                                <td><?php echo $data['regionname'] ?></td>
                                <td><?php echo $data['zonename'] ?></td>
                                <td><?php echo $data['branchname'] ?></td>
                                <td><?php echo $data['vehicleno'] ?></td>
                                <td><?php echo $vehicledatacount['green'] ?></td>
                                <td><?php echo $vehicledatacount['yellow'] ?></td>
                                <td><?php echo $vehicledatacount['red'] ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <tr><td colspan="7"><br/></td></tr>
                    <tr>
                        <td colspan="7"><strong><?php echo $currentMonthName; ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="7"><strong>Tracking Days</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Region</strong></td>
                        <td><strong>Zone</strong></td>
                        <td><strong>Office Name</strong></td>
                        <td><strong>Vehicle Number</strong></td>
                        <td class="greentd"><strong>Green</strong></td>
                        <td class="yellowtd"><strong>Yellow</strong></td>
                        <td class="redtd"><strong>Red</strong></td>
                    </tr>
                    <?php
                    $trackingDays = isset($dailyreportdata['trackingDays']) ? $dailyreportdata['trackingDays'] : null;

                    if (isset($trackingDays)) {
                        foreach ($trackingDays as $data) {
                            $vehicledatacount = array(
                                'green' => 0
                                , 'yellow' => 0
                                , 'red' => 0
                            );
                            if ($data['trackingdays'] > 28) {
                                $vehicledatacount['green'] = $data['trackingdays'];
                            }
                            else if ($data['trackingdays'] > 20 && $data['trackingdays'] <= 28) {
                                $vehicledatacount['yellow'] = $data['trackingdays'];
                            }
                            else if ($data['trackingdays'] < 20) {
                                $vehicledatacount['red'] = $data['trackingdays'];
                            }
                            ?>
                            <tr>
                                <td><?php echo $data['regionname'] ?></td>
                                <td><?php echo $data['zonename'] ?></td>
                                <td><?php echo $data['branchname'] ?></td>
                                <td><?php echo $data['vehicleno'] ?></td>
                                <td><?php echo $vehicledatacount['green'] ?></td>
                                <td><?php echo $vehicledatacount['yellow'] ?></td>
                                <td><?php echo $vehicledatacount['red'] ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <tr><td colspan="7"><br/></td></tr>
                    <tr>
                        <td colspan="7"><strong><?php echo $currentMonthName; ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="7"><strong>Excessive Acceleration</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Region</strong></td>
                        <td><strong>Zone</strong></td>
                        <td><strong>Office Name</strong></td>
                        <td><strong>Vehicle Number</strong></td>
                        <td class="greentd"><strong>Green</strong></td>
                        <td class="yellowtd"><strong>Yellow</strong></td>
                        <td class="redtd"><strong>Red</strong></td>
                    </tr>
                    <?php
                    $accelerationVehicleWise = isset($dailyreportdata['accelerationVehicleWise']) ? $dailyreportdata['accelerationVehicleWise'] : null;
                    if (isset($accelerationVehicleWise)) {
                        foreach ($accelerationVehicleWise as $data) {
                            $vehicledatacount = array(
                                'green' => 0
                                , 'yellow' => 0
                                , 'red' => 0
                            );
                            if ($data['sudden_acc'] <= 10) {
                                $zonedatacount['green'] = $data['sudden_acc'];
                            }
                            else if ($data['sudden_acc'] > 10 && $data['sudden_acc'] <= 20) {
                                $zonedatacount['yellow'] = $data['sudden_acc'];
                            }
                            else if ($data['sudden_acc'] > 20) {
                                $zonedatacount['red'] = $data['sudden_acc'];
                            }
                            ?>
                            <tr>
                                <td><?php echo $data['regionname'] ?></td>
                                <td><?php echo $data['zonename'] ?></td>
                                <td><?php echo $data['branchname'] ?></td>
                                <td><?php echo $data['vehicleno'] ?></td>
                                <td><?php echo $vehicledatacount['green'] ?></td>
                                <td><?php echo $vehicledatacount['yellow'] ?></td>
                                <td><?php echo $vehicledatacount['red'] ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
            <?php
        }
    }
    else if (isset($_REQUEST['to_get']) && $_REQUEST['to_get'] == 'get_summarized_report') {
        include_once 'informatics_ajax_new.php';
    }
    ?>

</div>

<?php
$content = ob_get_clean();
//echo $content;die();
require_once('../../vendor/autoload.php');
try {
    $html2pdf = new HTML2PDF('L', 'A4', 'en');
    $html2pdf->setTestTdInOnePage(false);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->Output("InformaticsReport.pdf");
}
catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
}
?>
