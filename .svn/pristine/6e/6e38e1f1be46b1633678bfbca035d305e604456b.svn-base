<?php

    /**
     * used in http://localhost/elixiaspeed/modules/reports/reports.php?id=23
     * @param type $chartcount
     * @return type
     */
    function get_chartdim($chartcount) {
        $chartdim = 0;
        if ($chartcount <= 3) {
            $chartdim = 300;
        } elseif ($chartcount > 3 && $chartcount <= 5) {
            $chartdim = 400;
        } elseif ($chartcount > 5 && $chartcount <= 10) {
            $chartdim = $chartcount * 50;
        } elseif ($chartcount > 10) {
            $chartdim = $chartcount * 40;
        }
        return $chartdim;
    }

    function isWeekend($date) {
        return (date('N', strtotime($date)) >= 6);
    }

    function generate_dist_td_data($DATA, $SDate, $EDdate, $filetype = null) {

        $vm = new VehicleManager($_SESSION['customerno']);
        $highest_dist = 0;
        $highest_dist_vehno = '';
        $total_wkend_dist = 0;
        $all_data = array();
        $customer_vehicles = vehicles_array($vm->get_all_vehicles());

        foreach ($DATA as $report) {
            if (!array_key_exists($report->vehicleid, $customer_vehicles)) {
                continue;
            }
            $actual_dist = round(($report->totaldistance / 1000), 1);
            $info_date = $report->info_date;
            $all_data[$report->vehicleid][$info_date] = $actual_dist;
            if ($actual_dist > $highest_dist) {
                $highest_dist = $actual_dist;
                $high_details = array('date' => $info_date, 'vehid' => $report->vehicleid);
            }
        }
        $totaldays = gendays($SDate, $EDdate);
        $count = 1;
        foreach ($all_data as $vehicleid => $data) {
            $veh_number = $customer_vehicles[$vehicleid]['vehno'];
            if ($filetype == 'pdf') {
                if (count($totaldays) > 25) {
                    $veh_number = wordwrap($veh_number, 4, "<br>\n", TRUE);
                }
            }
            $total = array_sum($data);

            //echo "total data".$total; die;

            echo "<tr>";
            echo "<td style='text-align:right;' >$count</td>";
            echo "<td style='text-align:right;font-weight:bold;'>$veh_number</td>";
            foreach ($totaldays as $single_date) {
                if (isset($data[$single_date])) {
                    $total_wkend_dist += (int) $data[$single_date];
                }
                $high_style = '';
                if (isset($high_details)) {
                    if ($high_details['date'] == $single_date && $high_details['vehid'] == $vehicleid) {
                        $highest_dist_vehno = $veh_number;
                        $high_style = 'style="font-weight:bold;background:green !important;"';
                    }
                }
                $custom = isset($data[$single_date]) ? $data[$single_date] : 'N/A';
                if ($filetype == 'pdf') {
                    if (count($totaldays) > 25) {
                        $custom = wordwrap($custom, 3, "<br>\n", TRUE);
                    }
                }
                if (isWeekend($single_date)) {
                    if ($high_style !== '') {
                        echo "<td $high_style>$custom</td>";
                    } else {
                        echo "<td style='font-weight:bold;color:#D26717' >$custom</td>";
                    }
                } else {
                    echo "<td $high_style>$custom</td>";
                }
            }
            echo "<td style='text-align:right;font-weight:bold;'>$total</td>";
            echo "</tr>";
            $count++;
        }
        return array($highest_dist, $highest_dist_vehno, $total_wkend_dist);
    }

    function generate_veh_dist_td_data($DATA, $SDate, $EDdate, $filetype = null) {

        $vm = new VehicleManager($_SESSION['customerno']);
        $highest_dist = 0;
        $highest_dist_vehno = '';
        $total_wkend_dist = 0;
        $all_data = array();
        $customer_vehicles = vehicles_array($vm->get_all_vehicles());

        foreach ($DATA as $report) {
            if (!array_key_exists($report->vehicleid, $customer_vehicles)) {
                continue;
            }
            $actual_dist = round(($report->totaldistance / 1000), 1);
            $info_date = $report->info_date;
            $all_data[$report->vehicleid][$info_date] = $actual_dist;
            if ($actual_dist > $highest_dist) {
                $highest_dist = $actual_dist;
                $high_details = array('date' => $info_date, 'vehid' => $report->vehicleid);
            }
        }
        $totaldays = gendays($SDate, $EDdate);
        $count = 1;
        foreach ($all_data as $vehicleid => $data) {
            $veh_number = $customer_vehicles[$vehicleid]['vehno'];
            if ($filetype == 'pdf') {
                if (count($totaldays) > 25) {
                    $veh_number = wordwrap($veh_number, 15, "<br>\n", TRUE);
                }
            }
            $total = array_sum($data);

            $i = 0;
            //echo "total data".$total; die;
            foreach ($totaldays as $single_date) {
                //print("<pre>");
                //print_r($DATA);
                //echo $DATA[$i]->info_date;
                $Traveldate = date("d-M-Y", strtotime($single_date));
                //echo $single_date;
                //echo $single_date;

                echo "<tr>";
                // echo "<td style='text-align:right;' >$count</td>";
                echo "<td style='text-align:right; width:100px;'>$count</td>";
                // echo "<td style='text-align:right;font-weight:bold;'>$veh_number</td>";
                echo "<td style='text-align:right;font-weight:bold;'>" . $Traveldate . "</td>";

                $high_style = '';

                if (isset($high_details)) {
                    if ($high_details['date'] == $single_date && $high_details['vehid'] == $vehicleid) {
                        $highest_dist_vehno = $veh_number;
                        $high_style = 'style="font-weight:bold;background:green !important;"';
                    }
                }
                $custom = isset($data[$single_date]) ? $data[$single_date] : 'N/A';
                if ($filetype == 'pdf') {
                    if (count($totaldays) > 25) {
                        $custom; // = wordwrap($custom, 3, "<br>\n", TRUE);
                    }
                }
                if (isWeekend($single_date)) {
                    if ($high_style !== '') {
                        echo "<td $high_style>$custom</td>";
                    } else {
                        echo "<td style='font-weight:bold;color:#D26717'>$custom</td>";
                    }
                    $total_wkend_dist += (int) $custom;
                } else {
                    echo "<td $high_style>$custom</td>";
                }
                $i++;
                $count++;
                echo "</tr>";
            }

            echo "<tr><td colspan='2' style='text-align:right;font-weight:bold;'>Total</td><td>$total</td>";
            echo "</tr>";
            //die;
            //$count++;
        }
        return array($highest_dist, $highest_dist_vehno, $total_wkend_dist);
    }

    function generate_driverperformance_td_data($DATA, $SDate, $EDdate, $filetype = null) {
        $vm = new VehicleManager($_SESSION['customerno']);
        $highest_dist = 0;
        $highest_dist_vehno = '';
        $total_wkend_dist = 0;
        $all_data = array();
        $customer_vehicles = vehicles_array($vm->get_all_vehicles());
        foreach ($DATA as $report) {
            if (!array_key_exists($report->vehicleid, $customer_vehicles)) {
                continue;
            }
            $actual_dist = round(($report->totaldistance / 1000), 1);
            $info_date = $report->info_date;
            $all_data[$report->vehicleid][$info_date]['distance'] = $actual_dist;
            $all_data[$report->vehicleid][$info_date]['topSpeed'] = $report->topspeed;
            $all_data[$report->vehicleid][$info_date]['overspeed'] = $report->overspeed;
            $all_data[$report->vehicleid][$info_date]['sa'] = '';
            $all_data[$report->vehicleid][$info_date]['hb'] = '';
            if ($actual_dist > $highest_dist) {
                $highest_dist = $actual_dist;
                $high_details = array('date' => $info_date, 'vehid' => $report->vehicleid);
            }
        }
        $totaldays = gendays($SDate, $EDdate);
        $count = 1;
        foreach ($all_data as $vehicleid => $data) {
            $veh_number = $customer_vehicles[$vehicleid]['vehno'];
            if ($filetype == 'pdf') {
                if (count($totaldays) > 25) {
                   // $veh_number = wordwrap($veh_number, 15, "<br>\n", TRUE);
                }
            }
            $total = 0;
            $i = 0;
            foreach ($totaldays as $single_date) {
                $Traveldate = date("d-M-Y", strtotime($single_date));
                echo "<tr>";
                echo "<td style='text-align:right; width:100px;'>$count</td>";
                echo "<td style='text-align:right;font-weight:bold;'>" . $Traveldate . "</td>";
                $high_style = '';
                if (isset($high_details)) {
                    if ($high_details['date'] == $single_date && $high_details['vehid'] == $vehicleid) {
                        $highest_dist_vehno = $veh_number;
                        $high_style = '';
                    }
                }
                $custom = isset($data[$single_date]['distance']) ? $data[$single_date]['distance'] : 'N/A';
                if ($filetype == 'pdf') {
                    if (count($totaldays) > 25) {
                        $custom; // = wordwrap($custom, 3, "<br>\n", TRUE);
                    }
                }
                if (isWeekend($single_date)) {
                    if ($high_style !== '') {
                        echo "<td $high_style>$custom</td>";
                    } else {
                        echo "<td style=''>$custom</td>";
                    }
                    $total_wkend_dist += (int) $custom;
                } else {
                    echo "<td $high_style>$custom</td>";
                }
                $topSpeed = isset($data[$single_date]['topSpeed']) ? $data[$single_date]['topSpeed'] : 'N/A';
                $overspeed = isset($data[$single_date]['overspeed']) ? $data[$single_date]['overspeed'] : 'N/A';
                echo "<td style='text-align:right; width:100px;'>".$overspeed."</td>";
                echo "<td style='text-align:right; width:100px;'>".$topSpeed."</td>";
                echo "<td style='text-align:right; width:100px;'>0</td>";
                echo "<td style='text-align:right; width:100px;'>0</td>";
                $i++;
                $count++;
                echo "</tr>";
                $total += (int)$custom;
            }
            echo "<tr><td colspan='2' style='text-align:right;font-weight:bold;'>Total</td><td>$total</td><td></td><td></td><td></td><td></td>";
            echo "</tr>";
            //die;
            //$count++;
        }
        return array($highest_dist, $highest_dist_vehno, $total_wkend_dist);
    }

    function getdistancereportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
        $totaldays = gendays($STdate, $EDdate);
        $location = "../../customer/$customerno/reports/dailyreport.sqlite";
        if (file_exists($location)) {
            $DATA = GetDailyReport_Data_All_PDF_New($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
        }
        if (isset($DATA)) {
            $title = 'Distance Analysis Report';
            $subTitle = array(
                "Start Date: $STdate",
                "End Date: $EDdate",
                "Unit: Kilometers ",
            );

            if (!is_null($vgroupname)) {
                $subTitle[] = "Group-name: $vgroupname";
            }

            echo pdf_header($title, $subTitle);

            $SDate = $STdate;
            $STdate = date('d-m-Y', strtotime($STdate));
            $t_columns = '';
            $colspan = 0;
            while (strtotime($STdate) <= strtotime($EDdate)) {
                $disp_date = substr($STdate, 0, 5);
                if (count($totaldays) > 25) {
                    $disp_date = wordwrap($disp_date, 3, "<br>\n", TRUE);
                }
                $t_columns .= "<td>$disp_date</td>";
                $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                $colspan++;
            }
        ?>
        <div style='text-align:center;min-height: 30px;'>
            <span style='background:green;color:#FFF;'>Highest Distance</span>
            <span style='background:#D26717;color:#FFF;'>Weekend Travel Data</span>
        </div>
        <div>
            <table align="center"  id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse;'>


                <tr style="background-color: #CCCCCC;font-weight:bold;">
                    <td style="height: 30px; vertical-align: middle;">#</td>
                    <td style="height: 30px; vertical-align: middle;">Veh No</td>
                    <td style="height: 30px; vertical-align: middle;" colspan='<?php echo $colspan ?>' >Date</td>
                    <td style='height: 30px; vertical-align: middle;' >Total</td>
                </tr>
                <tr class='tableSub' >
                    <td></td>
                    <td></td>
        <?php echo $t_columns; ?>
                    <td></td>
                </tr>
        <?php
            if (isset($DATA)) {
                        $summary_data = generate_dist_td_data($DATA, $SDate, $EDdate, 'pdf');
                    }
                ?>
            </table><br/>

            <table align="center" style="width: auto;font-weight:bold;font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
                <tbody>
                    <tr><td colspan="2" style="background-color:#CCCCCC;width:600px;height:auto;">Statistics</td></tr>
                    <tr><td><?php echo "Highest distance travelled: {$summary_data[0]}, Vehicle No.: {$summary_data[1]}"; ?></td></tr>
                    <tr><td><?php echo "Total distance travelled on weekends: {$summary_data[2]} Kms"; ?></td></tr>
                </tbody>
            </table><br/>
        </div>
        <?php
            }
            }

            /* code for single vehicle report in pdf */

            function getvehicledistancereportpdf($vehicleno, $vehicleid, $customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
                //print("<pre>"); echo $customerno;
                $totaldays = gendays($STdate, $EDdate);
                $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                if (file_exists($location)) {
                    $DATA = GetDailyReport_Data_Vehicle_PDF($vehicleid, $location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
                    /*print("<pre>");
                print_r($DATA); die;*/
                }
                if (isset($DATA)) {
                    $title = 'Distance Analysis Report';
                    $subTitle = array(
                        "Start Date: $STdate",
                        "End Date: $EDdate",
                        "Unit: Kilometers ",
                        "Vehicle No: $vehicleno",

                    );

                    if (!is_null($vgroupname)) {
                        $subTitle[] = "Group-name: $vgroupname";
                    }

                    echo pdf_header($title, $subTitle);

                    $SDate = $STdate;
                    $STdate = date('d-m-Y', strtotime($STdate));
                    $t_columns = '';
                    $colspan = 0;
                    while (strtotime($STdate) <= strtotime($EDdate)) {
                        $disp_date = substr($STdate, 0, 5);
                        if (count($totaldays) > 25) {
                            $disp_date = wordwrap($disp_date, 3, "<br>\n", TRUE);
                        }
                        $t_columns .= "<td>$disp_date</td>";
                        $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                        $colspan++;
                    }
                ?>
        <div style='text-align:center;min-height: 30px;'>
            <span style='background:green;color:#FFF;'>Highest Distance</span>
            <span style='background:#D26717;color:#FFF;'>Weekend Travel Data</span>
        </div>
        <div>
            <style type="text/css">
             table tr th,td{
                   padding: 3px;

           }
                #td_border {border: 1px solid #000;}
            }
            </style>
            <table align="center" id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tr>
                    <th colspan="3" id="td_border"> Vehicle No.                                                                <?php echo "<b>" . $vehicleno . "</b>" ?></th>
                </tr>
                <tr>
                         <th id="td_border">Serial No</th>
                          <th id="td_border" >Date</th>
                        <th id="td_border">Distance in km.</th>

                    </tr>

        <?php
            if (isset($DATA)) {
                        $summary_data = generate_veh_dist_td_data($DATA, $SDate, $EDdate, 'pdf');

                        /*print("<pre>");
                    print_r($summary_data);
                    die;*/
                    }
                ?>
            </table><br/>
            <?php //die; ?>

            <table align="center" style="width:auto;font-weight:bold;font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
                <tbody>
                    <tr><td colspan="2" style="background-color:#CCCCCC;width:600px;height:auto;">Statistics</td></tr>
                    <tr><td><?php echo "Highest distance travelled: {$summary_data[0]}, Vehicle No.: {$summary_data[1]}"; ?></td></tr>
                    <tr><td><?php echo "Total distance travelled on weekends: {$summary_data[2]} Kms"; ?></td></tr>
                </tbody>
            </table><br/>
        </div>
        <?php
            }
            }

            /* end code for single vehicle report in pdf */

            function getdistancereportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
                $start_Date = $STdate;
                $totaldays = gendays($STdate, $EDdate);
                $start_date = $STdate;
                $end_date = $EDdate;
                $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                if (file_exists($location)) {
                    $DATA = GetDailyReport_Data_All_PDF_New($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
                }
                if (isset($DATA)) {
                    $SDate = $STdate;
                    $STdate = date('d-m-Y', strtotime($STdate));
                    $t_columns = '';
                    $colspan = 0;
                    while (strtotime($STdate) <= strtotime($EDdate)) {
                        $t_columns .= "<td style='width:40px;background-color: #CCCCCC;'>" . substr($STdate, 0, 5) . "</td>";
                        $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                        $colspan++;
                    }
                    $title = 'Distance Analysis Report';
                    $subTitle = array(
                        "Start Date: $start_Date",
                        "End Date: $EDdate",
                        "Unit: Kilometers ",
                    );
                    if (!is_null($vgroupname)) {
                        $subTitle[] = "Group-name: $vgroupname";
                    }
                    echo excel_header($title, $subTitle);
                ?>
        <div style="text-align: center;">

            <table>
                <tr>
                    <td style='background:green;color:#FFF;'>Highest Distance</td>
                    <td style='background:#D26717;color:#FFF;'>Weekend Travel Data</td>
                </tr>
            </table><br/>

            <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tr style="background-color: #CCCCCC; ">
                    <td style="height: 30px; width:50px;vertical-align: middle;">#</td>
                    <td style="height: 30px; width:50px;vertical-align: middle;">Vehicle No</td>
                    <td style="height: 30px; vertical-align: middle;" colspan='<?php echo $colspan ?>' >Date</td>
                    <td style='height: 30px; width:100px;vertical-align: middle;' >Total</td>
                </tr>
                <tr class='tableSub' >
                    <td></td>
                    <td></td>
        <?php echo $t_columns; ?>
                    <td></td>
                </tr>
        <?php
            $summary_data = generate_dist_td_data($DATA, $SDate, $EDdate);
                ?>
            </table><br/>
            <table align="center" style="width: auto;font-weight:bold;font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
                <tbody>
                    <tr><td colspan='10' style="background-color:#CCCCCC;height:auto;">Statistics</td></tr>
                    <tr><td colspan='10'><?php echo "Highest distance travelled: {$summary_data[0]}, Vehicle No.: {$summary_data[1]}"; ?></td></tr>
                    <tr><td colspan='10'><?php echo "Total distance travelled on weekends: {$summary_data[2]} Kms"; ?></td></tr>
                </tbody>
            </table><br/>

        </div>
        <?php
            }
            }

            /* Single Vehicle Excel Report*/

            function getVehicledistancereportcsv($vehicleid, $vehicleno, $customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
                $start_Date = $STdate;
                $totaldays = gendays($STdate, $EDdate);
                $start_date = $STdate;
                $end_date = $EDdate;
                $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                if (file_exists($location)) {
                    $DATA = GetDailyReport_Data_Vehicle_PDF($vehicleid, $location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
                }
                if (isset($DATA)) {
                    $SDate = $STdate;
                    $STdate = date('d-m-Y', strtotime($STdate));
                    $t_columns = '';
                    $colspan = 0;
                    while (strtotime($STdate) <= strtotime($EDdate)) {
                        $t_columns .= "<td style='width:40px;background-color: #CCCCCC;'>" . substr($STdate, 0, 5) . "</td>";
                        $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                        $colspan++;
                    }
                    $title = 'Vehicle Distance Analysis Report';
                    $subTitle = array(
                        "Start Date: $start_Date",
                        "End Date: $EDdate",
                        "Unit: Kilometers ",
                        "Vehicle No:$vehicleno",
                    );
                    if (!is_null($vgroupname)) {
                        $subTitle[] = "Group-name: $vgroupname";
                    }
                    echo excel_header($title, $subTitle);
                ?>
        <div style="text-align: center;">

            <table>
                <tr>
                    <td style='background:green;color:#FFF;'>Highest Distance</td>
                    <td style='background:#D26717;color:#FFF;'>Weekend Travel Data</td>
                </tr>
            </table><br/>

            <table align="center" id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tr>
                    <th colspan="3" id="td_border"> Vehicle No.                                                                <?php echo "<b>" . $vehicleno . "</b>" ?></th>
                </tr>
                <tr>
                         <th id="td_border">Serial No</th>
                          <th id="td_border" >Date</th>
                        <th id="td_border">Distance in km.</th>

                    </tr>

        <?php
            if (isset($DATA)) {
                        $summary_data = generate_driverperformance_td_data($DATA, $SDate, $EDdate, 'csv');

                        /*print("<pre>");
                    print_r($summary_data);
                    die;*/
                    }
                ?>
            </table><br/>


            <!-- <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tr style="background-color: #CCCCCC; ">
                    <td style="height: 30px; width:50px;vertical-align: middle;">#</td>
                    <td style="height: 30px; width:50px;vertical-align: middle;">Vehicle No</td>
                    <td style="height: 30px; vertical-align: middle;" colspan='<?php echo $colspan ?>' >Date</td>
                    <td style='height: 30px; width:100px;vertical-align: middle;' >Total</td>
                </tr>
                <tr class='tableSub' >
                    <td></td>
                    <td></td>
                    <?php echo $t_columns; ?>
                    <td></td>
                </tr> -->
        <?php
            //$summary_data = generate_dist_td_data($DATA, $SDate, $EDdate);
                ?>
            <!-- </table> --><br/>
            <table align="center" style="width: auto;font-weight:bold;font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
                <tbody>
                    <tr><td colspan='10' style="background-color:#CCCCCC;height:auto;">Statistics</td></tr>
                    <tr><td colspan='10'><?php echo "Highest distance travelled: {$summary_data[0]}, Vehicle No.: {$summary_data[1]}"; ?></td></tr>
                    <tr><td colspan='10'><?php echo "Total distance travelled on weekends: {$summary_data[2]} Kms"; ?></td></tr>
                </tbody>
            </table><br/>

        </div>
        <?php
            }
            }

            function generate_graph_data($DATA) {

                //print("<pre>"); print_r($DATA); die;

                $all_data = array();
                $chart_height = 200;

                foreach ($DATA as $report) {
                    //$date;
                    $actual_dist = round(($report->totaldistance / 1000), 2);

                    $all_data[$report->info_date] = $actual_dist;
                }

            //print_r("<pre>"); print_r($all_data); die;
                /*foreach ($DATA as $report) {
                $actual_dist = round(($report->totaldistance / 1000), 2);
                if (isset($all_data[$report->vehicleid])) {
                $all_data[$report->vehicleid] += $actual_dist;
                } else {
                $all_data[$report->vehicleid] = $actual_dist;
                }
                }*/

                $date = $total = '';
                // $vehs = $total = '';
                // echo $_SESSION['customerno'];die;
                $vm = new VehicleManager($_SESSION['customerno']);

                foreach ($all_data as $infoDate => $totalDist) {
                    $veh_no = $vm->get_vehicle_details($DATA[0]->vehicleid);

                    if (!isset($veh_no->vehicleno)) {
                        continue;
                    }
                    $date .= "'$infoDate', ";
                    $total .= "$totalDist, ";
                    $chart_height += 30;
                }

                //$vehs = "'2018-03-01', '2018-03-02', '2018-03-03', '2018-03-04', '2018-03-05', '2018-03-06', '2018-03-07', '2018-03-08'";
                //$total = "10.25, 6.73, 5.59, 0.34, 157.64, 186.7, 183.14, 41.44";

                //print_r("<pre>"); print_r($vehs); print_r("<pre>"); print_r($total); print_r("<pre>"); print_r($chart_height); die;

                //$array = array('date' => $date, 'data' => $total, 'height' => $chart_height);
                return array('date' => $date, 'data' => $total, 'height' => $chart_height);

                // print_r("<pre>"); print_r($array); die;
            }

            function getDriverPerformanceReportcsv($driverid, $drivername, $customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
                $start_Date = $STdate;
                $totaldays = gendays($STdate, $EDdate);
                $start_date = $STdate;
                $end_date = $EDdate;
                $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                if (file_exists($location)) {
                    $dm = new DriverManager($customerno);
                    $driverdetails = $dm->get_driver_with_vehicle($driverid);
                    $vehicleid = $driverdetails->vehicleid;
                    $DATA = GetDailyReport_Data_Vehicle_PDF($vehicleid, $location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
                }
                if (isset($DATA)) {
                    $SDate = $STdate;
                    $STdate = date('d-m-Y', strtotime($STdate));
                    $t_columns = '';
                    $colspan = 0;
                    while (strtotime($STdate) <= strtotime($EDdate)) {
                        $t_columns .= "<td style='width:40px;background-color: #CCCCCC;'>" . substr($STdate, 0, 5) . "</td>";
                        $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                        $colspan++;
                    }
                    $title = 'Driver Performance Report';
                    $subTitle = array(
                        "Start Date: $start_Date",
                        "End Date: $EDdate",
                        "Unit: Kilometers ",
                        "Drivername: $drivername "
                    );
                    if (!is_null($vgroupname)) {
                        $subTitle[] = "Group-name: $vgroupname";
                    }
                    echo excel_header($title, $subTitle);
                ?>
                <div style="text-align: center;">
                    <table>
                        <tr>
                            <td >SA : Sudden Acceleration</td>
                            <td >HB : Harsh Break</td>
                        </tr>
                    </table><br/>
                    <table align="center" id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>

                        <tr>
                            <th id="td_border">Serial No</th>
                            <th id="td_border" >Date</th>
                            <th id="td_border">Distance in km.</th>
                            <th id="td_border">Overspeed</th>
                            <th id="td_border">Topspeed in km/hr.</th>
                            <th id="td_border">SA</th>
                            <th id="td_border">HB</th>
                        </tr>
                        <?php
                        if (isset($DATA)) {
                            $summary_data = generate_driverperformance_td_data($DATA, $SDate, $EDdate, 'csv');
                        }
                        ?>
                    </table>
                    <br/>

            </div>
        <?php
            }
            }

        function getDriverPerformanceReportpdf($drivername, $driverid, $customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vgroupname = null) {
            $totaldays = gendays($STdate, $EDdate);
            $location = "../../customer/$customerno/reports/dailyreport.sqlite";
            if (file_exists($location)) {
                $dm = new DriverManager($customerno);
                $driverdetails = $dm->get_driver_with_vehicle($driverid);
                $vehicleid = $driverdetails->vehicleid;
                $DATA = GetDailyReport_Data_Vehicle_PDF($vehicleid, $location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
            }
            if (isset($DATA)) {
                $title = 'Driver Performance Report';
                $subTitle = array(
                    "Start Date: $STdate",
                    "End Date: $EDdate",
                    "Unit: Kilometers ",
                    "Drivername: $drivername "
                );
                if (!is_null($vgroupname)) {
                    $subTitle[] = "Group-name: $vgroupname";
                }
                echo pdf_header($title, $subTitle);
                $SDate = $STdate;
                $STdate = date('d-m-Y', strtotime($STdate));
                $t_columns = '';
                $colspan = 0;
                while (strtotime($STdate) <= strtotime($EDdate)) {
                    $disp_date = substr($STdate, 0, 5);
                    if (count($totaldays) > 25) {
                        $disp_date = wordwrap($disp_date, 3, "<br>\n", TRUE);
                    }
                    $t_columns .= "<td>$disp_date</td>";
                    $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                    $colspan++;
                }


            ?>
            <div style='text-align:center;min-height: 30px;'>
                <span >SA : Sudden Acceleration</span>
                <span >HB : Harh Break</span>
            </div>
            <div>
            <style type="text/css">
            table tr th,td{
                   padding: 3px;
            }
            #td_border {border: 1px solid #000;}
            </style>
            <table align="center" id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
            <tr>
                <th id="td_border">Serial No</th>
                <th id="td_border" >Date</th>
                <th id="td_border">Distance in km.</th>
                <th id="td_border">Overspeed</th>
                <th id="td_border">Topspeed in km/hr.</th>
                <th id="td_border">SA</th>
                <th id="td_border">HB</th>
            </tr>
            <?php
                if (isset($DATA)) {
                    $summary_data = generate_driverperformance_td_data($DATA, $SDate, $EDdate, 'pdf');
                }
            ?>
            </table><br/>

            </div>
        <?php
        }
        }
        ?>
