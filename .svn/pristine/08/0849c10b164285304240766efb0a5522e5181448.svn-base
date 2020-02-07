<?php
ini_set('max_execution_time', 900);
include 'rtd_map_functions.php';
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
include '../../lib/bo/simple_html_dom.php';
include_once '../../lib/comman_function/reports_func.php';
//ini_set('memory_limit', '512M');
ob_start();

//echo"data at server fethced<pre>"; print_r($_POST); exit();

if(isset($_POST['action']) && $_POST['action']==='updateVehicleCommonStatus' && isset($_POST['vehicleId']) && isset($_POST['vehicleStatusId']))
{
    updateVehicleCommonStatus($_POST['vehicleId'],$_POST['vehicleStatusId']);
}
if (isset($_REQUEST['vehicleids'])) {
    getvehicle($_REQUEST['vehicleids']);
}
if (isset($_REQUEST['all']) && !isset($_REQUEST['export'])) {
    getvehicles();
}

if (isset($_REQUEST['export']) && isset($_REQUEST['all'])) { 
    $type = $_REQUEST['export'];
    $data = getvehicles("export");

    if (isset($data) && !empty($data)) {
        //$data = array_reverse($data);
        $printdata = "";
        $count = 0;
        $subtitle = Array();
        foreach ($data['result'] as $row) {
            if ($count == 0) {
                $printdata.="<table>";
                $printdata.="<tr style='text-align:center;'><th>Sr No.</th>";
                if ($_SESSION['customerno'] == 212) {
                    if (isset($_SESSION["Warehouse"])) {
                        $printdata.="<th >" . $_SESSION["Warehouse"] . "</th>";
                    } else {
                        $printdata.="<th >Warehouse</th>";
                    }
                } else {
                    $printdata.="<th >Vehicle No</th>";
                }
                $printdata.="<th>Last Updated</th>";
                if ($_SESSION['buzzer'] == 1) {
                    $printdata.="<th>Buzzer</th>";
                }
                if ($_SESSION['immobiliser'] == 1) {
                    $printdata.="<th>Immobiliser</th>";
                }
                if ($_SESSION['freeze'] == 1) {
                    $printdata.='<th>Freeze</th>';
                }
                if ($_SESSION['groupid'] == 0) {
                    $printdata.="<th>Group Name</th>";
                }
                if ($_SESSION['portable'] != '1') {
                    $printdata.="<th>Status</th>";
                }
                $printdata.="<th>Driver Name</th><th>Driver Number</th>";
                if ($_SESSION['Session_UserRole'] == 'elixir') {
                    $printdata.="<th>Unit No</th>";
                }
                $printdata.="<th>Location</th>";
                $printdata.="<th>Checkpoint</th>";
                $printdata.="<th>Route</th>";
                if ($_SESSION['portable'] != '1') {
                    $printdata.="<th>Speed</th>";
                    if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                        $printdata.="<th>Distance</th>";
                    }
                    $printdata.="<th>Power</th>";
                    if ($_SESSION['use_loading'] == 1) {
                        $printdata.="<th>Load</th>";
                    }
                    if ($_SESSION['use_ac_sensor'] == 1) {
                        $printdata.="<th>" . $_SESSION["digitalcon"] . "</th>";
                    }
                    if ($_SESSION['use_genset_sensor'] == 1) {
                        $printdata.="<th>" . $_SESSION["digitalcon"] . "</th>";
                    }
                    if ($_SESSION['use_door_sensor'] == 1) {
                        $printdata.="<th>Door</th>";
                    }
                    if ($row->temp_sensors == 1) {
                        $printdata.="<th>Temperature</th>";
                    } elseif ($row->temp_sensors == 2) {
                        $printdata.="<th>Temperature 1</th>";
                        $printdata.="<th>Temperature 2</th>";
                    } elseif ($row->temp_sensors == 3) {
                        $printdata.="<th>Temperature 1</th>";
                        $printdata.="<th>Temperature 2</th>";
                        $printdata.="<th>Temperature 3</th>";
                    } elseif ($row->temp_sensors == 4) {
                        $printdata.="<th>Temperature 1</th>";
                        $printdata.="<th>Temperature 2</th>";
                        $printdata.="<th>Temperature 3</th>";
                        $printdata.="<th>Temperature 4</th>";
                    }
                    if ($_SESSION['use_extradigital'] == 1) {
                        $printdata.="<th>Genset 1</th>";
                        $printdata.="<th>Genset 2</th>";
                    }
                    if ($_SESSION['use_humidity'] == 1) {
                        $printdata.="<th>Humidity</th>";
                    }
                }
                $printdata.="</tr>";
            }
            $count+=1;
            $vehicle = exprtVhclDtls($row->cvehicleid);
            $printdata.="<tr><td>" . $count . "</td><td>" . $vehicle['0']->vehicleno . "</td><td>" . $row->clastupdated . "</td>";
            if ($_SESSION['buzzer'] == 1) {
                if (isset($vehicle['0']->is_buzzer) && $vehicle['0']->is_buzzer == 0) {
                    $printdata.="<td>Off</td>";
                } elseif (isset($vehicle['0']->is_buzzer) && $vehicle['0']->is_buzzer == 1) {
                    $printdata.="<td>On</td>";
                }
            }
            if ($_SESSION['immobiliser'] == 1) {
                if (isset($vehicle['0']->is_mobiliser) && $vehicle['0']->is_mobiliser == 0) {
                    $printdata.="<td>Off</td>";
                } elseif (isset($vehicle['0']->is_mobiliser) && $vehicle['0']->is_mobiliser == 1) {
                    $printdata.="<td>On</td>";
                }
            }
            if ($_SESSION['freeze'] == 1) {
                if (isset($vehicle['0']->is_freeze) && $vehicle['0']->is_freeze == 0) {
                    $printdata.="<td>Off</td>";
                } elseif (isset($vehicle['0']->is_freeze) && $vehicle['0']->is_freeze == 1) {
                    $printdata.="<td>On</td>";
                }
            }

            if ($_SESSION['groupid'] == 0) {
                if ($vehicle['0']->groupid == 0) {
                    $printdata.="<td>Ungrouped</td>";
                } else {
                    $printdata.="<td>" . $vehicle['0']->groupname . "</td>";
                }
            }
            if ($type == 'pdf') {
                $printdata.="<td>" . wordwrap($row->status, 10, PHP_EOL) . "</td>";
            } else {
                $printdata.="<td>" . strip_tags($row->status) . "</td>";
            }

            $printdata.="<td>" . $vehicle['0']->drivername . "</td>";
            if (strpos($vehicle['0']->driverphone, '8888888888') !== false || strpos($vehicle['0']->driverphone, '2222222222') !== false) {
                $printdata.="<td>-</td>";
            } else {
                $printdata.="<td>" . $vehicle['0']->driverphone . "</td>";
            }
            if ($_SESSION['Session_UserRole'] == 'elixir') {
                if (isset($vehicle['0']->unitno)) {
                    $printdata.='<td>' . $vehicle['0']->unitno . '</td>';
                }
            }
            if (isset($row->location)) {
                if ($type == 'pdf') {
                    $printdata.='<td>' . wordwrap($row->location, Location_Wrap, PHP_EOL) . '</td>';
                } else {
                    $printdata.='<td>' . strip_tags($row->location) . '</td>';
                }
            }
            if (isset($vehicle['0']->cname)) {
                if ($vehicle['0']->chkpoint_status == 0) {
                    $printdata.="<td>IN-" . $vehicle['0']->cname . "</td>";
                } elseif ($vehicle['0']->chkpoint_status == 1) {
                    $printdata.="<td>OUT-" . $vehicle['0']->cname . "</td>";
                }
            } else {
                $printdata.="<td>NA</td>";
            }
            
            if(isset($row->routeDirection))
            {
                $printdata.="<td>".$row->routeDirection."</td>";
            }
            else
            {
                $printdata.="<td>NA</td>";
            }

            if (isset($row->cspeed)) {
                $printdata.='<td>' . $row->cspeed . '</td>';
            }
            if (isset($row->totaldist)) {
                $printdata.='<td>' . $row->totaldist . '</td>';
            }
            if (isset($row->pc)) {
                $powercut = $row->pc;
                if (strpos($powercut, 'on') !== false) {
                    $printdata.="<td>ON</td>";
                } elseif (strpos($powercut, 'off') !== false) {
                    $printdata.="<td>OFF</td>";
                }
            }
            if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                // Load Status
                if ($_SESSION['use_loading'] == 1) {
                    if ($row->msgkey == 1) {
                        $printdata.="<td>Loading</td>";
                    } elseif ($row->msgkey == 2) {
                        $printdata.="<td>Unloading</td>";
                    } else {
                        $printdata.="<td>Normal</td>";
                    }
                }
            }
            if ($_SESSION['use_ac_sensor'] == 1) {
                if ($type == 'pdf') {
                    $printdata.="<td>" . wordwrap($row->acsensor, 10, PHP_EOL) . "</td>";
                } else {
                    $printdata.="<td>" . strip_tags($row->acsensor) . "</td>";
                }
            }
            if ($_SESSION['use_genset_sensor'] == 1) {
                if ($type == 'pdf') {
                    $printdata.="<td>" . wordwrap($row->acsensor, 10, PHP_EOL) . "</td>";
                } else {
                    $printdata.="<td>" . strip_tags($row->acsensor) . "</td>";
                }
            }
            if ($_SESSION['use_door_sensor'] == 1) {
                if ($type == 'pdf') {
                    $printdata.="<td>" . wordwrap($row->doorsensor, 10, PHP_EOL) . "</td>";
                } else {
                    $printdata.="<td>" . strip_tags($row->doorsensor) . "</td>";
                }
            }

            if ($row->temp_sensors == 1) {
                if ($row->temp1on == 1) {
                    $printdata.="<td>$row->temp1</td>";
                } else {
                    $printdata.="<td>Not Active</td>";
                }
            } else if ($row->temp_sensors == 2) {
                if ($row->temp1on == 1) {
                    $printdata.="<td>$row->temp1</td>";
                } else {
                    $printdata.="<td>Not Active</td>";
                }
                if ($row->temp2on == 1) {
                    $printdata.="<td>$row->temp2</td>";
                } else {
                    $printdata.="<td>Not Active</td>";
                }
            } else if ($row->temp_sensors == 3) {
                if ($row->temp1on == 1) {
                    $printdata.="<td>$row->temp1</td>";
                } else {
                    $printdata.="<td>Not Active</td>";
                }
                if ($row->temp2on == 1) {
                    $printdata.="<td>$row->temp2</td>";
                } else {
                    $printdata.="<td>Not Active</td>";
                }
                if ($row->temp3on == 1) {
                    $printdata.="<td>$row->temp3</td>";
                } else {
                    $printdata.="<td>Not Active</td>";
                }
            } else if ($row->temp_sensors == 4) {
                if ($row->temp1on == 1) {
                    $printdata.="<td>$row->temp1</td>";
                } else {
                    $printdata.="<td>Not Active</td>";
                }
                if ($row->temp2on == 1) {
                    $printdata.="<td>$row->temp2</td>";
                } else {
                    $printdata.="<td>Not Active</td>";
                }
                if ($row->temp3on == 1) {
                    $printdata.="<td>$row->temp3</td>";
                } else {
                    $printdata.="<td>Not Active</td>";
                }
                if ($row->temp4on == 1) {
                    $printdata.="<td>$row->temp4</td>";
                } else {
                    $printdata.="<td>Not Active</td>";
                }
            }
            if ($_SESSION['use_extradigital'] == 1) {
                $printdata.="<td>" . $row->genset1 . "</td>";
                $printdata.="<td>" . $row->genset2 . "</td>";
            }
            if ($row->use_humidity == 1) {
                if ($row->humidityon == 1) {
                    $printdata.="<td>" . strip_tags($row->humidity) . "</td>";
                } else {
                    $printdata.="<td>" . strip_tags($row->humidity) . "</td>";
                }
            }
            $printdata.="</tr>";
        }
        $printdata.="</table>";

        if ($type == 'xls') {
            $header = excel_header("Realtime Data", $subtitle);
            echo $header;
            $cat = display($printdata);
            $content = ob_get_clean();
            $html = str_get_html($content);
            $xls_filename = str_replace(' ', '', "realtimedata_" . date("d-m-Y  H:i:s") . ".xls");
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$xls_filename");

            //echo $html;
            echo $content;
        } elseif ($type == 'pdf') {
            require_once('../reports/html2pdf.php');
            try {
                $header = pdf_header("Realtime Data", $subtitle);
                echo $header;
                $cat = display($printdata);
                $content = ob_get_clean();
                $html2pdf = new HTML2PDF('L', 'A4', 'en');
                $html2pdf->pdf->SetDisplayMode('fullpage');

                $html2pdf->writeHTML($content);
                $html2pdf->Output("realtimedata_" . date("d-m-Y") . ".pdf");
            } catch (HTML2PDF_exception $e) {
                echo $e;
                exit;
            }
        }
    } else {
        echo "<script>(function() {  alert('Data Not Available.'); window.close(); }());</script>";
    }
}

function display($data) {
    echo $data;
}

?>
