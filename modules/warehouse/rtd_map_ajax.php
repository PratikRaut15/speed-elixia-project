<?php

include_once 'rtd_map_functions.php';
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
include '../../lib/bo/simple_html_dom.php';
include_once '../../lib/comman_function/reports_func.php';
//ini_set('memory_limit', '512M');
ob_start();
if (isset($_REQUEST['vehicleids'])) {
    getvehicle($_REQUEST['vehicleids']);
}
if (isset($_REQUEST['all']) && !isset($_REQUEST['export'])) {
    if ($_REQUEST['all'] != 1) {
        $vehicleids = $_REQUEST['all'];
    } else {
        $vehicleids = null;
    }
    if ($_SESSION['use_warehouse'] == 1) {
        /*
          if ($_SESSION['customerno'] == 177) {
          getvehicles_wh_fassos($vehicleids);
          } elseif ($_SESSION['customerno'] == 193) {
          getvehicles_wh_icelings($vehicleids);
          } else {
          getvehicles_wh($vehicleids);
          }
         */
        getvehicles_wh($vehicleids);
    }
}

if (isset($_REQUEST['export']) && isset($_REQUEST['all'])) {

    $data = getvehicles_wh(NULL, 'export');
    if (isset($data) && !empty($data)) {
        $data = array_reverse($data);
        $printdata = "";
        $count = 0;
        $subtitle = Array();

        foreach ($data as $row) {
            if ($count == 0) {
                $printdata.="<table>";
                $printdata.="<tr style='text-align:center;'><th>Sr No.</th>";
                if (isset($_SESSION["Warehouse"])) {
                    $printdata.="<th>" . $_SESSION["Warehouse"] . "</th>";
                } else {
                    $printdata.="<th >Warehouse</th>";
                }
                $printdata.="<th>Last Updated</th>";
                $printdata.="<th>Status</th>";
                if ($_SESSION['buzzer'] == 1) {
                    $printdata.="<th>Buzzer</th>";
                }
                if ($_SESSION['groupid'] == 0) {
                    if (isset($_SESSION['group'])) {
                        $printdata.="<th>" . $_SESSION['group'] . "</th>";
                    } else {
                        $printdata.="<th>Group Name</th>";
                    }
                    $printdata.="<th>Group Name</th>";
                }

                if ($_SESSION['Session_UserRole'] == 'elixir') {
                    $printdata.="<th>Unit No</th>";
                }
                if ($_SESSION['customerno'] == speedConstants::CUSTNO_NXTDIGITAL) {
                    $printdata.="<th>Location</th>";
                }
                if ($_SESSION['portable'] != '1') {

                    $printdata.="<th>Power</th>";
                    if ($_SESSION['use_loading'] == 1) {
                        $printdata.='<th >Load</th>';
                    }
                    if ($_SESSION['use_ac_sensor'] == 1) {
                        $printdata.="<th filter='false'>" . $_SESSION["digitalcon"] . "</th>";
                    }
                    if ($_SESSION['use_genset_sensor'] == 1) {
                        $printdata.="<th filter='false'>" . $_SESSION["digitalcon"] . "</th>";
                    }
                    if ($_SESSION['use_door_sensor'] == 1) {
                        $printdata.='<th >Door</th>  ';
                    }
                    if ($_SESSION['temp_sensors'] == 1) {
                        $printdata.='<th >Temperature</th>';
                    } elseif ($_SESSION['temp_sensors'] == 2) {
                        $printdata.='<th >' . $_SESSION['Temperature 1'] . '</th>';
                        $printdata.='<th >' . $_SESSION['Temperature 2'] . '</th>';
                    } elseif ($_SESSION['temp_sensors'] == 3) {
                        $printdata.='<th >' . $_SESSION['Temperature 1'] . '</th>';
                        $printdata.='<th >' . $_SESSION['Temperature 2'] . '</th>';
                        $printdata.='<th >' . $_SESSION['Temperature 3'] . '</th>';
                    } elseif ($_SESSION['temp_sensors'] == 4) {
                        $printdata.='<th >' . $_SESSION['Temperature 1'] . '</th>';
                        $printdata.='<th >' . $_SESSION['Temperature 2'] . '</th>';
                        $printdata.='<th >' . $_SESSION['Temperature 3'] . '</th>';
                        $printdata.='<th >' . $_SESSION['Temperature 4'] . '</th>';
                    }
                    if ($_SESSION['use_extradigital'] == 1) {
                        $printdata.='<th >' . $_SESSION['extradigitalstatus'] . '</th>';
                    }
                    if ($_SESSION['use_humidity'] == 1) {
                        $printdata.='<th >Humidity</th>';
                    }
                }
                $printdata.="</tr>";
            }
            $count+=1;
            $vehicle = exprtWarehouseDtls($row->cvehicleid);
            $printdata.="<tr><td>" . $count . "</td><td>" . $vehicle['0']->vehicleno . "</td><td>" . $row->clastupdated . "</td>";
            $status = $row->idleimage;
            if (strpos($status, "inactive.png")) {
                $printdata.="<td>Inactive</td>";
            } elseif (strpos($status, "on.png")) {
                $printdata.="<td>On</td>";
            } elseif (strpos($status, "conflict.png")) {
                $printdata.="<td>Conflict</td>";
            }
            if ($_SESSION['buzzer'] == 1) {
                if (isset($vehicle['0']->is_buzzer) && $vehicle['0']->is_buzzer == 0) {
                    $printdata.="<td>Off</td>";
                } elseif (isset($vehicle['0']->is_buzzer) && $vehicle['0']->is_buzzer == 1) {
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

            if ($_SESSION['Session_UserRole'] == 'elixir') {
                if (isset($vehicle['0']->unitno)) {
                    $printdata.='<td>' . $vehicle['0']->unitno . '</td>';
                }
            }
            if ($_SESSION['customerno'] == speedConstants::CUSTNO_NXTDIGITAL) {
                if (isset($row->location)) {
                    $location = str_replace('</br>', ' ', $row->location);
                    $printdata.='<td>' . $location . '</td>';
                }
            }
            if ($_SESSION['portable'] != '1') {
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
                    $printdata.="<td>$row->acsensor</td>";
                }
                if ($_SESSION['use_genset_sensor'] == 1) {
                    $printdata.="<td>$row->acsensor</td>";
                }
                if ($_SESSION['use_door_sensor'] == 1) {
//                    $door = str_replace('</br>', ' ', $row->doorsensor);
                    $printdata.="<td>" . strip_tags($row->doorsensor) . "</td>";
                }

                if ($_SESSION['temp_sensors'] == 1) {
                    if ($row->temp1on == 1) {
                        $printdata.="<td>" . strip_tags($row->temp1) . "</td>";
                    } else {
                        $printdata.="<td>Not Active</td>";
                    }
                } else if ($_SESSION['temp_sensors'] == 2) {
                    if ($row->temp1on == 1) {
                        $printdata.="<td>" . strip_tags($row->temp1) . "</td>";
                    } else {
                        $printdata.="<td>Not Active</td>";
                    }
                    if ($row->temp2on == 1) {
                        $printdata.="<td>" . strip_tags($row->temp2) . "</td>";
                    } else {
                        $printdata.="<td>Not Active</td>";
                    }
                } else if ($_SESSION['temp_sensors'] == 3) {
                    if ($row->temp1on == 1) {
                        $printdata.="<td>" . strip_tags($row->temp1) . "</td>";
                    } else {
                        $printdata.="<td>Not Active</td>";
                    }
                    if ($row->temp2on == 1) {
                        $printdata.="<td>" . strip_tags($row->temp2) . "</td>";
                    } else {
                        $printdata.="<td>Not Active</td>";
                    }
                    if ($row->temp3on == 1) {
                        $printdata.="<td>" . strip_tags($row->temp3) . "</td>";
                    } else {
                        $printdata.="<td>Not Active</td>";
                    }
                } else if ($_SESSION['temp_sensors'] == 4) {
                    if ($row->temp1on == 1) {
                        $printdata.="<td>" . strip_tags($row->temp1) . "</td>";
                    } else {
                        $printdata.="<td>Not Active</td>";
                    }
                    if ($row->temp2on == 1) {
                        $printdata.="<td>" . strip_tags($row->temp2) . "</td>";
                    } else {
                        $printdata.="<td>Not Active</td>";
                    }
                    if ($row->temp3on == 1) {
                        $printdata.="<td>" . strip_tags($row->temp3) . "</td>";
                    } else {
                        $printdata.="<td>Not Active</td>";
                    }
                    if ($row->temp4on == 1) {
                        $printdata.="<td>" . strip_tags($row->temp4) . "</td>";
                    } else {
                        $printdata.="<td>Not Active</td>";
                    }
                }
                if ($row->use_humidity == 1) {
                    if ($row->humidityon == 1) {
                        $printdata.="<td>" . strip_tags($row->humidity) . "</td>";
                    } else {
                        $printdata.="<td>" . strip_tags($row->humidity) . "</td>";
                    }
                }
            }
            $printdata.="</tr>";
        }
        $printdata.="</table>";

        $type = $_REQUEST['export'];

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
