<?php
    //error_reporting(E_ALL ^ E_STRICT);
    //ini_set('display_errors', 'On');
    
    //$code_start = microtime(true);
    set_time_limit(0);
    //date_default_timezone_set("Asia/Calcutta");
    session_start();
    if(!isset($_SESSION['timezone'])){
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
    date_default_timezone_set(''.$_SESSION['timezone'].'');
    $s_date = isset($_POST['SDate']) ? $_POST['SDate'] : date('d-m-Y');
    $e_date = isset($_POST['EDate']) ? $_POST['EDate'] : date('d-m-Y');
    
    if(isset($_POST['to_get']) && $_POST['to_get'] == 'get_informatics_report'){
        
        include_once 'informatics_functions.php';
        if(validate_input($s_date,$e_date)){
            $start_date = date('Y-m-d', strtotime($s_date));
            $end_date = date('Y-m-d', strtotime($e_date));
            ?>
    <!-- Starts, Reports-->
    
    <div style='width:75%' id='informaticsStart'>
        
        <div style='float:right'>
            <a href="<?php echo 'informatics_pdf_table.php?SDate='.$start_date.'&EDate='.$end_date.'&to_get=get_informatics_report'?>" target="_blank"><img title="Export to PDF" class="exportIcons" alt="Export to PDF" src="../../images/pdf_icon.png"></a>
            <a href="<?php echo 'informatics_excel_table.php?SDate='.$start_date.'&EDate='.$end_date.'&to_get=get_informatics_report'?>" target="_blank"><img title="Export to Excel" class="exportIcons" alt="Export to Excel" src="../../images/xls.gif"></a>
        </div><br clear='both'><hr>
        
        <!-- Starts, Installed devices -->
        <?php
        $def_chart_height = 200;
        $total_devices_installed = count($all_vehicles);//$devicemanager->get_all_devices_count($start_date, $end_date);
        $s = ($total_devices_installed<=1) ? "" : "s";
        ?>
        <div style='float:left'>Total Installed Devices: <b><?php echo "$total_devices_installed Device$s"; ?></b></div>
        <br/><hr>
        <!-- Ends, Installed devices -->
        
        
        <!-- Starts, Kms tracked -->
        <?php
        $kms_tracked = get_daily_report_data($start_date, $end_date);
        $distance_tracked_height = $def_chart_height + (isset($kms_tracked['data'][2]) ? $kms_tracked['data'][2] : 0);
        $overspeed_data = get_over_speed_data($start_date, $end_date);
        ?>
        <div style='float:left'>Total No. of kms tracked: <b><?php echo round($kms_tracked['total']).' km'; ?></b></div>
        <br/><br/>
        
        <div style='float:left'>
            <div id="container" style="min-width: 910px; min-height: <?php echo $distance_tracked_height; ?>px; margin: 0 auto;"></div>
        </div>
        <br clear='both'><hr>
        <!-- Ends, Kms tracked -->
        
        
        <!-- Starts, Top speed reports -->
        <div style='float:left'>
            <div style='float:left;'>Top Speed:</div><br clear="both">
            <div style='float:left;'>
            <table>
                <thead>
                    <tr><th>Vehicle No</th><th>Top Speed(Kmph)</th><th>Date</th><th>Time Duration</th></tr>
                </thead>
                <tbody>
                    <tr>
                    <?php 
                    if(isset($overspeed_data['top_speed'])){ 
                        $top_speed = $overspeed_data['top_speed'];
                        echo  "<td>".$top_speed[0]."</td><td><b>".$top_speed[1]."</b></td><td>".$top_speed[2]."</td><td>".$top_speed[3]."</td>";
                    }
                    else{
                        echo '<td colspan="4">No Data</td>';
                    } 
                    ?>
                    </tr>
                </tbody>
                    
            </table>
            </div><br clear='both'>
            <?php
            if(isset($overspeed_data['top_speed_all'])){
                $os_speed_veh = $overspeed_data['top_speed_all'][0];
                $os_speed_kmph = $overspeed_data['top_speed_all'][1];
                $top_speed_height = $def_chart_height + (isset($overspeed_data['top_speed_all'][2]) ? $overspeed_data['top_speed_all'][2] : 0);
            }
            ?>
            <div id="container_inv_veh" style="min-width: 910px; min-height: <?php echo $top_speed_height; ?>px; margin: 0 auto;"></div>
        </div><br clear='both'><hr/>
        <!-- Ends, Top speed reports -->
        
        <!-- Starts, Distance travelled reports -->
        <div style='float:left'>
            <div style='float:left;'>Highest Distance Travelled:</div><br clear="both">
            <div style='float:left'>
            <table>
                <thead>
                    <tr><th>Vehicle No.</th><th>Distance Travelled</th><th>Date</th></tr>
                </thead>
                <tbody>
                    <?php
                    if(isset($kms_tracked['highest_dis_trav'])){
                        $h_d = $kms_tracked['highest_dis_trav'];
                        $distance_q = round($h_d[1]);
                        echo "<tr><td>".$h_d[0]."</td><td><b>".$distance_q."</b></td><td>".$h_d[2]."</td></tr>";
                    }
                    else{
                        echo '<tr><td colspan="3">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            </div><br clear='both'><br/>
            
            <div style='float:left;'>
                Average Distance Travelled(Per day): <b><?php if(isset($kms_tracked['avg_dis_trav'])){ echo round($kms_tracked['avg_dis_trav']); } else { echo 0;} ?>kms</b>
            </div><br clear='both'>
            <div style='float:left;'>
                Average Distance Travelled(Per day & Vehicles): <b><?php if(isset($kms_tracked['avg_dis_trav_veh'])){ echo round($kms_tracked['avg_dis_trav_veh']); } else { echo 0;} ?>kms</b>
            </div><br clear='both'>
            
        </div><br clear='both'><hr/>
        <!-- Ends, Distance travelled reports -->
        
        <!-- Starts, Sundays Distance travelled on reports -->
        <div style='float:left'>
            <div style='float:left;'>
                Total no. of kms travelled on Sundays: <b><?php if(isset($kms_tracked['sun_dis_trav'])){ echo round($kms_tracked['sun_dis_trav']); } else { echo 0;} ?>kms</b>
            </div><br clear='both'><br/>
            <?php
            $unique_dates = isset($kms_tracked['unique_dates']) ? $kms_tracked['unique_dates'] : 0;
            
            if(isset($kms_tracked['sun_dis_trav_data']['data'])){
                $s_data = $kms_tracked['sun_dis_trav_data']['data'];
                
                $vehname_sunday = $veh_json = '';
                $data = array();
                $sunday_height = $def_chart_height;
                $initial_height = 30;
                foreach($s_data as $veh_name=>$veh_counts){
                    $vehname_sunday .= "'$veh_name',";
                    foreach($unique_dates as $header_date){
                        $sun_count = isset($veh_counts[$header_date])? $veh_counts[$header_date] : 0;
                        $data[$header_date][] = $sun_count;
                    }
                    $sunday_height += $initial_height;
                }
                
                foreach($data as $veh_dates=>$js){
                    $js = implode(',', $js);
                    $veh_json .= "{ name: '$veh_dates', data: [$js]},";
                }
            }
            ?>
            <div id="container_sunday" style="min-width: 910px; min-height: <?php echo $sunday_height; ?>px; margin: 0 auto;"></div>
            
        </div><br clear='both'><hr/>
        <!-- Ends, Sundays Distance travelled on reports -->
        
        <!-- Starts, Advanced Alert reports -->
        <div style='float:left;'>
            <?php
            if(isset($overspeed_data['custom_report']['vehicles'])){
                $advanced_alert_height = $overspeed_data['custom_report']['height']+$def_chart_height;
            }
            ?>
            <div style='float:left;'>
                Total no. of harsh breaks: <b><?php if(isset($overspeed_data['harsh_break'])){ echo $overspeed_data['harsh_break'];} else { echo 0;} ?></b>
            </div><br clear='both'>
            <div style='float:left;'>
                Sudden change of acceleration during day (7 am to 8 pm): <b><?php if(isset($overspeed_data['acc_day'])){ echo $overspeed_data['acc_day'];} else { echo 0;} ?></b>
            </div><br clear='both'>
            <div style='float:left;'>
                Sudden change of acceleration during night (8 pm to 7 am): <b><?php if(isset($overspeed_data['acc_night'])){ echo $overspeed_data['acc_night'];} else { echo 0;} ?></b>
            </div><br clear='both'>
            <!--<div style='float:left;'>
                Total no. of Sharp turn: <b><?php //if(isset($overspeed_data['sharp_turn'])){ echo $overspeed_data['sharp_turn'];} else { echo 0;} ?></b>
            </div><br clear='both'>-->
            <div style='float:left;'>
                Total no. of Towing: <b><?php if(isset($overspeed_data['towing'])){ echo $overspeed_data['towing'];} else { echo 0;} ?></b>
            </div><br clear='both'>
            <div id="container_custom_report" style="min-width: 910px; min-height: <?php echo $advanced_alert_height; ?>px; margin: 0 auto;"></div>
            
        </div><br clear='both'><hr/>
        <!-- Ends, Advanced Alert reports -->
        
        <!-- Starts, Incidents Alerts reports -->
        <div style='float:left'>
            <div style='float:left;'>
                No. of incidents recorded: <b><?php $inc_recrd = get_comqueue_count($start_date, $end_date); echo $inc_recrd['total']; ?></b>
            </div><br clear='both'>
                    <?php
                    if(!empty($inc_recrd['data'])){
                        $init_height = 0;
                        $def_height = 60;
                        $vehname_g = $ac_alert_g = $ch_alert_g = $ig_alert_g = $overspd_alert_g = $tamp_alert_g = $pwrcut_alert_g = $fence_alert_g = '';
                        foreach($inc_recrd['data'] as $veh_name=>$single_type){
                            $ac_alert = isset($single_type['ac_alert']) ? $single_type['ac_alert'] : 0;
                            $ch_alert = isset($single_type['ch_alert']) ? $single_type['ch_alert'] : 0;
                            $ig_alert = isset($single_type['ig_alert']) ? $single_type['ig_alert'] : 0;
                            $overspd_alert = isset($single_type['overspd_alert']) ? $single_type['overspd_alert'] : 0;
                            $tamp_alert = isset($single_type['tamp_alert']) ? $single_type['tamp_alert'] : 0;
                            $pwrcut_alert = isset($single_type['pwrcut_alert']) ? $single_type['pwrcut_alert'] : 0;
                            $fence_alert = isset($single_type['fence_alert']) ? $single_type['fence_alert'] : 0;
                            
                            $vehname_g .= "'$veh_name', ";
                            $ac_alert_g .= $ac_alert.",";
                            $ch_alert_g .= $ch_alert.",";
                            $ig_alert_g .= $ig_alert.",";
                            $overspd_alert_g .= $overspd_alert.",";
                            $tamp_alert_g .= $tamp_alert.",";
                            $pwrcut_alert_g .= $pwrcut_alert.",";
                            $fence_alert_g .= $fence_alert.",";
                            
                            $init_height += $def_height;
                        }
                        $inci_alert_height = $def_chart_height+$init_height;
                        $ac_alert_count = array_sum(explode(',', $ac_alert_g));
                        $ch_alert_count = array_sum(explode(',', $ch_alert_g));
                        $ig_alert_count = array_sum(explode(',', $ig_alert_g));
                        $overspd_alert_count = array_sum(explode(',', $overspd_alert_g));
                        $tamp_alert_count = array_sum(explode(',', $tamp_alert_g));
                        $pwrcut_alert_count = array_sum(explode(',', $pwrcut_alert_g));
                        $fence_alert_count = array_sum(explode(',', $fence_alert_g));
                        
                    }
                    ?>
            <div id="container_inci" style="min-width: 910px; min-height: <?php echo $inci_alert_height; ?>px; margin: 0 auto;"></div>
        </div><br clear='both'><hr/>
        <!-- Ends, Incidents Alerts reports -->
        
        <!-- Starts, Idle Time reports -->
        <div style='float:left'>
            <?php
            if(isset($kms_tracked['idle_time_data_final'])){
                $idle_data = $kms_tracked['idle_time_data_final'];
            }
            $idle_height = $def_chart_height + (isset($kms_tracked['idle_time_data_final']['idle_height'])?$kms_tracked['idle_time_data_final']['idle_height']:0);
            ?>
            <div style='float:left;'>
                Total Idle Time(All Vehicles): <b><?php if(isset($kms_tracked['total_idle_time'])){ echo $kms_tracked['total_idle_time'];} else { echo 0;} ?></b>
            </div><br clear='both'>
            <div style='float:left;'>
                Total Running Time(All Vehicles): <b><?php if(isset($kms_tracked['total_running_time'])){ echo $kms_tracked['total_running_time'];} else { echo 0;} ?></b>
            </div><br clear='both'>
            <div id="container_idle_time" style="min-width: 910px; min-height: <?php echo $idle_height; ?>px; margin: 0 auto;"></div>
        </div><br clear='both'><hr/>
        <!-- Ends, Idle Time reports -->
        
        <!--Starts, vehicle-iginition=on and idle>=15-mins and at-same-place=true-->
        <?php
        $idle_hold_ig_off_height = $def_chart_height + (isset($overspeed_data['idle_hold_ig_off_data']['height']) ? $overspeed_data['idle_hold_ig_off_data']['height'] : 0);
        ?>
        <div style='float:left'>
            <br clear='both'>
            <div id="container_idle_time_mins" style="min-width: 910px; min-height: <?php echo $idle_hold_ig_off_height; ?>px; margin: 0 auto;"></div>
        </div><br clear='both'><hr/>
        <!--Ends, vehicle-iginition=on and idle>=15-mins and at-same-place=true-->
        
        <!--Starts, vehicle-iginition=on and idle>=15-mins and AC=one and at-same-place=true-->
        <?php
        $idle_hold_ig_off_ac_height = $def_chart_height + (isset($overspeed_data['idle_hold_ig_ac_data']['height']) ? $overspeed_data['idle_hold_ig_ac_data']['height'] : 0);
        ?>
        <div style='float:left'>
            
            <div style='float:left;'>
                Idle and Ignition-on(15 mins) and AC-On Report:
            </div><br clear='both'>
            
            <div id="container_idle_ac_on" style="min-width: 910px; min-height: <?php echo $idle_hold_ig_off_ac_height; ?>px; margin: 0 auto;"></div>
        </div><br clear='both'><hr/>
        <!--Ends, vehicle-iginition=on and idle>=15-mins and AC=one and at-same-place=true-->
        
        <!--Starts, trip report: trips ended after 10 pm-->
        <div style='float:left'>
            
            <?php $trip_report = generate_trip_report(null, null,'get_data_end'); ?>
            <div style='float:left;'>
                Trips Ended after 10 pm: <?php echo $trip_report['total']; ?>
            </div><br clear='both'>
            
            <div id="container_trips_10" style="min-width: 910px; min-height: <?php echo $trip_report['trip_hght']; ?>px; margin: 0 auto;"></div>
        </div><br clear='both'><hr/>
        <!--Ends, trip report: trips ended after 10 pm-->
        
        <!--Starts, trip report: trips started after 9 pm-->
        <div style='float:left'>
            
            <?php
            $trip_report_st = generate_trip_report(null, null,'get_data_start');
            ?>
            <div style='float:left;'>
                Trips Started after 9 pm: <?php echo $trip_report_st['total']; ?>
            </div><br clear='both'>
            
            <div id="container_trips_9" style="min-width: 910px; min-height: <?php echo $trip_report_st['trip_hght']; ?>px; margin: 0 auto;"></div>
        </div><br clear='both'><hr/>
        <!--Ends, trip report: trips started after 9 pm-->
        
        
    </div>

    <!-- Ends, Reports-->
<script type="text/javascript">
    jQuery(function () {
        
        <?php if(isset($kms_tracked['data'])){ ?>
    jQuery('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Distance tracked'
        },
        subtitle: {
            text: 'All Vehicles'
        },
        xAxis: {
            categories: [<?php echo $kms_tracked['data'][0]; ?>],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Distance (KM)',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' KM'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 62,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Distance',
            data: [<?php echo $kms_tracked['data'][1]; ?>]
        }
        ]
    });
        <?php 
        }
        else{
            echo "jQuery('#container').html('No data for Chart generation');";
        }
        
        if(isset($overspeed_data['top_speed_all'])){
            ?>
                    
    jQuery('#container_inv_veh').highcharts({
        chart: {
            type: 'line',
            inverted: true
        },
        title: {
            text: 'Top Speed',
            x: -20 //center
        },
        subtitle: {
            text: 'All Vehicles',
            x: -20
        },
        xAxis: {
            categories: [<?php echo $os_speed_veh; ?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Speed (KMPH)',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' KMPH'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 62,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        colors: ['#D46916'],
        series: [{
            name: 'Speed (KMPH)',
            data: [<?php echo $os_speed_kmph; ?>]
        }]
    });
            <?php
        }
        else{
            echo "jQuery('#container_inv_veh').html('No data for Chart generation');";
        }
        
        if(!empty($inc_recrd['data'])){
            ?>
jQuery('#container_inci').highcharts({
        chart: {
            type: 'bar',
            inverted: true
        },
        title: {
            text: 'Alerts(Incidents)'
        },
        xAxis: {
            categories: [<?php echo $vehname_g;?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Alerts-Incidents(Number)',
                align: 'high'
            },
            labels: { overflow: 'justify' },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        labels: {
            
            items: [{
                html: 'Total Alerts',
                style: {
                    left: '450px',
                    top: '35px',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                }
            }]
        },
        plotOptions: {
            series: {
                pointWidth: 8
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 42,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        series: [
        {
            name: 'AC Alert',
            data: [<?php echo $ac_alert_g; ?>]
        }, {
            name: 'Checkpoint Alert',
            data: [<?php echo $ch_alert_g; ?>]
        }, {
            name: 'Ignition Alert',
            data: [<?php echo $ig_alert_g; ?>],
         }, {
            name: 'Overspeed Alert',
            data: [<?php echo $overspd_alert_g; ?>]
        }, {
            name: 'Tamper Alert',
            data: [<?php echo $tamp_alert_g; ?>]
        },{
            name: 'Power Cut Alert',
            data: [<?php echo $pwrcut_alert_g; ?>]
        },{
            name: 'Fence Conflict Alert',
            data: [<?php echo $fence_alert_g; ?>]
        },
        
        {
            type: 'pie',
            name: 'Total consumption',
            data: [{
                name: 'AC Alert',
                y: <?php echo $ac_alert_count; ?>,
                color: Highcharts.getOptions().colors[0]
            }, {
                name: 'Checkpoint Alert',
                y: <?php echo $ch_alert_count; ?>,
                color: Highcharts.getOptions().colors[1]
            },
            {
                name: 'Ignition Alert',
                y: <?php echo $ig_alert_count; ?>,
                color: Highcharts.getOptions().colors[2]
            },
            {
                name: 'Overspeed Alert',
                y: <?php echo $overspd_alert_count; ?>,
                color: Highcharts.getOptions().colors[3]
            },
            {
                name: 'Tamper Alert',
                y: <?php echo $tamp_alert_count; ?>,
                color: Highcharts.getOptions().colors[4]
            },
            {
                name: 'Power Cut Alert',
                y: <?php echo $pwrcut_alert_count; ?>,
                color: Highcharts.getOptions().colors[5]
            },
            {
                name: 'Fence Conflict Alert',
                y: <?php echo $fence_alert_count; ?>,
                color: Highcharts.getOptions().colors[6]
            }
            ],
            center: [500, 80],
            size: 100,
            showInLegend: false,
            dataLabels: {
                enabled: false
            }
        }
    ]
    })                    
 
          <?php
        }
        else{
            echo "jQuery('#container_inv_inci').html('No data for Chart generation');";
        }
        
        if(isset($kms_tracked['sun_dis_trav_data']['data'])){
            ?>
 jQuery('#container_sunday').highcharts({
        chart: { type: 'bar' },
        title: { text: 'Distance travelled(On Sundays)' },
        xAxis: {
            categories: [<?php echo $vehname_sunday;?>],
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Distance travelled on sundays (KM)',
                align: 'high'
            },
            labels: { overflow: 'justify' },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
                
        tooltip: { valueSuffix: ' KMS' },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 42,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        
        plotOptions: {
            series: {
                stacking: 'normal',
            }
            
        },
        series: [ <?php echo $veh_json; ?>]
    });                    
            <?php
        }
        else{
            echo "jQuery('#container_sunday').hide();";
        }
        
        if(isset($idle_data)){
            ?>
    jQuery('#container_idle_time').highcharts({
        chart: {
            type: 'area',
            inverted: true
        },
        title: {
            text: 'Idle Time'
        },
        subtitle: {
            text: 'All Vehicles'
        },
        xAxis: {
            categories: [<?php echo $idle_data['veh_names'];?>],
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            }
        },
        yAxis: {
            title: {
                text: 'Percent(%)',
                align: 'high'
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b> ({point.y:,.0f} Hours)<br/>',
            shared: true
        },
        plotOptions: {
            area: {
                stacking: 'percent',
                lineColor: '#ffffff',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#ffffff'
                }
            }
        },
        series: [{
            name: 'Idle Time',
            data: [<?php echo $idle_data['veh_idle_time']; ?>]
        }, {
            name: 'Running Time',
            data: [<?php echo $idle_data['veh_running_time']; ?>]
        }]
    });
 
            <?php
        }
        else{
            echo "jQuery('#container_idle_time').html('No data for Chart generation');";
        }
        
        if(isset($overspeed_data['custom_report'])){
            $custom_data = $overspeed_data['custom_report'];
            
            ?>
    jQuery('#container_custom_report').highcharts({
        chart: {
            type: 'area',
            inverted: true
        },
        title: { text: 'Advanced Alerts' },
        xAxis: {
            allowDecimals: false,
            categories: [<?php echo '"",'.$custom_data['vehicles'];?>]
        },
        yAxis: {
            title: {
                text: 'Customized Report (Number)',
                align: 'high'
            },
        },
        plotOptions: {
            area: {
                stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 1,
                //pointStart: 1,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        series: [{
            name: 'Harsh Breaks',
            data: [0, <?php echo $custom_data['harsh_b']; ?>]
        }, {
            name: 'Acceleration(Day)',
            data: [0, <?php echo $custom_data['accl_day']; ?>]
        },{
            name: 'Acceleration(Night)',
            data: [0, <?php echo $custom_data['accl_night']; ?>]
        },/*{
            name: 'Sharp Turn',
            data: [0, <?php //echo $custom_data['sharp_turn']; ?>]
        },*/{
            name: 'Towing',
            data: [0, <?php echo $custom_data['towing']; ?>]
        }]
    });                    
        <?php
        }
        else{
            echo "jQuery('#container_custom_report').html('No data for Chart generation');";
        }
        if(isset($overspeed_data['idle_hold_ig_off_data'])){
            
        ?>
                    
    jQuery('#container_idle_time_mins').highcharts({
        chart: {
            type: 'line',
            inverted: true
        },
        title: {
            text: 'Idle and Ignition-on(15 mins) Report',
            x: -20 //center
        },
        xAxis: {
            categories: [<?php echo $overspeed_data['idle_hold_ig_off_data']['veh']; ?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Count',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 42,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        colors: ['#F15C80'],
        series: [{
            name: 'Count(Number)',
            data: [<?php echo $overspeed_data['idle_hold_ig_off_data']['count']; ?>]
        }]
    });
            <?php
        }
        else{
            echo "jQuery('#container_idle_time_mins').html('No data for Chart generation');";
        }
        
if(isset($overspeed_data['idle_hold_ig_ac_data'])){
        ?>
                    
    jQuery('#container_idle_ac_on').highcharts({
        chart: {
            type: 'line',
            inverted: true
        },
        title: {
            text: 'Idle and Ignition-on(15 mins) and AC-On Report',
            x: -20 //center
        },
        xAxis: {
            categories: [<?php echo $overspeed_data['idle_hold_ig_ac_data']['veh']; ?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Count',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 42,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        colors: ['#F15C80'],
        series: [{
            name: 'Count(Number)',
            data: [<?php echo $overspeed_data['idle_hold_ig_ac_data']['count']; ?>]
        }]
    });
            <?php
        }
        else{
            echo "jQuery('#container_idle_ac_on').html('No data for Chart generation');";
        }
        
        if($trip_report['total']!=0){
        ?>
    jQuery('#container_trips_10').highcharts({
        chart: {
            type: 'bar'
        },
         colors: ['#F7A35C'],
        title: {
            text: 'Trip Report'
        },
        subtitle: {
            text: 'All Vehicles'
        },
        xAxis: {
            categories: [<?php echo $trip_report['vehicles']; ?>],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Count',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ''
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 62,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Count',
            data: [<?php echo $trip_report['count']; ?>]
        }
        ]
    });
    
        <?php
        }
        else{
            echo "jQuery('#container_trips_10').html('No data for Chart generation');";
        }
        
        if($trip_report_st['total']!=0){
        ?>
    jQuery('#container_trips_9').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Trip Report'
        },
        colors:['#90ED7D'],
        subtitle: {
            text: 'All Vehicles'
        },
        xAxis: {
            categories: [<?php echo $trip_report_st['vehicles']; ?>],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Count',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ''
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 62,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Count',
            data: [<?php echo $trip_report_st['count']; ?>]
        }
        ]
    });
    
        <?php
        }
        else{
            echo "jQuery('#container_trips_9').html('No data for Chart generation');";
        }
        ?>                
});
</script>
    

    <?php
        }
    }
    else{
        echo "Please select Dates";
    }
    
    //$code_end = microtime(true);
    //$code_execute_time = $code_end-$code_start;
    //echo "<script>alert('Process Time: $code_execute_time');</script>";
    ?>
