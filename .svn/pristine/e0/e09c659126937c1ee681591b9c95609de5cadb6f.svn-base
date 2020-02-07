<?php 
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
    

set_time_limit(0);
session_start();
ob_start();
//date_default_timezone_set("Asia/Calcutta");
if(!isset($_SESSION['timezone'])){
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
date_default_timezone_set(''.$_SESSION['timezone'].'');
include_once 'informatics_functions.php';
?>
<div style="width:auto; height:30px;">
    <table style="width: auto; border:none;">
        <tr>
            <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
            <td style="width:420px; border:none;"><h3 style="text-transform:uppercase;">Informatics</h3><br /></td>
            <td style="width:230px;border:none;"><img src="../../images/elixia_logo_75.png"  /></td>
        </tr>
    </table>
</div><hr />
<style type='text/css'>
table, td { border: solid 1px  #999999; color:#000000; }
hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
</style>

<div class="entry" style='min-height:400px;'>

    <br/>
    <!-- starts, input table -->
    <?php
    $s_date = isset($_GET['SDate']) ? $_GET['SDate'] : date('d-m-Y');
    $e_date = isset($_GET['EDate']) ? $_GET['EDate'] : date('d-m-Y');
    ?>
    <!-- ends, input table -->
    <?php
    if(isset($_GET['to_get']) && $_GET['to_get'] == 'get_informatics_report'){
        include_once 'informatics_functions.php';
        if(validate_input($s_date,$e_date)){
            $start_date = date('Y-m-d', strtotime($s_date));
            $end_date = date('Y-m-d', strtotime($e_date));
            ?>
    
    <!-- Starts, Reports-->
    
    <div id='informaticsStart'>
        
       <!-- Starts, Installed devices -->
        <?php
        $def_chart_height = 200;
        $total_devices_installed = count($all_vehicles);//$devicemanager->get_all_devices_count($start_date, $end_date);
        $device_installed_display = ($total_devices_installed<=1) ? $total_devices_installed." Device" : $total_devices_installed." Devices";
        ?>
        <div style='float:left'>Total Installed Devices: <b><?php echo $device_installed_display; ?></b></div>
        <br/><hr>
        <!-- Ends, Installed devices -->
        
        <!-- Starts, Kms tracked -->
        <?php
        $kms_tracked = get_daily_report_data($start_date, $end_date);
        $overspeed_data = get_over_speed_data($start_date, $end_date);
        ?>
        <div style='float:left'>Total No. of kms tracked: <b><?php echo round($kms_tracked['total']).' km'; ?></b></div>
        <br/><br/>
        
        <div style='float:left'>
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'><!--border-collapse:collapse; -->
                <tbody>
                    <tr style='background-color:#CCCCCC;'><th colspan='2'>Distance Tracked [All Vehicles]</th></tr>
                    <tr style='background-color:#CCCCCC;'><th style='width:300px;height:auto;'>Vehicle No.</th><th style='width:300px;height:auto;'>Distance Tracked[KMS]</th></tr>
                    <?php
                    if(isset($kms_tracked['data'])){
                        $veh_names = explode(',',$kms_tracked['data'][0]);
                        $veh_data = explode(',',$kms_tracked['data'][1]);
                        array_pop($veh_names);
                        $i = 0;
                        foreach($veh_names as $veh){
                            $veh = str_replace('\'', '', $veh);
                            echo "<tr><td>".$veh."</td><td>".$veh_data[$i]."</td></tr>";
                            $i++;
                        }
                    }
                    else{
                        echo '<tr><td colspan="3">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table><br/><hr/>
            
        </div>
        
        <!-- Ends, Kms tracked -->
        
        <!-- Starts, Top speed reports -->
        <div style='float:left'>
            <div style='float:left;'>Top Speed:</div><br/>
            
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:300px;height:auto;'>Vehicle No</th>
                        <th style='width:150px;height:auto;'>Top Speed(Kmph)</th>
                        <th style='width:150px;height:auto;'>Time Duration</th>
                        <th style='width:150px;height:auto;'>Date</th>
                    </tr>
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
            </table><br>
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'><th colspan='4'>Top Speed [All Vehicles]</th></tr>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:300px;height:auto;'>Vehicle No.</th>
                        <th style='width:150px;height:auto;'>Date</th>
                        <th style='width:150px;height:auto;'>Duration[MM:SS]</th>
                        <th style='width:150px;height:auto;'>Top Speed[KMPH]</th>
                    </tr>
                    <?php
                    if(isset($overspeed_data['top_speed_all'])){
                        $veh_names = explode(',', $overspeed_data['top_speed_all'][0]);
                        $veh_data = explode(',', $overspeed_data['top_speed_all'][1]);
                        array_pop($veh_names);
                        
                        $i = 0;
                        foreach($veh_names as $veh){
                            $veh = str_replace('\'', '', $veh);
                            $veh = explode('(',$veh);
                            $veh[1] = str_replace(')', '', $veh[1]);
                            $veh[2] = str_replace(')', '', $veh[2]);
                            echo "<tr><td>".$veh[0]."</td><td>".$veh[1]."</td><td>".$veh[2]."</td><td>".$veh_data[$i]."</td></tr>";
                            $i++;
                        }
                    }
                    else{
                        echo '<tr><td colspan="4">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            
        </div><br><hr/>
        <!-- Ends, Top speed reports -->
        
        <!-- Starts, Distance travelled reports -->
        <div style='float:left'>
            <br/>
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'><th colspan='3'>Highest Distance Travelled</th></tr>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:300px;height:auto;'>Vehicle No.</th>
                        <th style='width:200px;height:auto;'>Distance Travelled[KMS]</th>
                        <th style='width:150px;height:auto;'>Date</th>
                    </tr>
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
            </table><br/>
            
            <div style='float:left;'>
                Average Distance Travelled(Per day): <b><?php if(isset($kms_tracked['avg_dis_trav'])){ echo round($kms_tracked['avg_dis_trav']); } else { echo 0;} ?>kms</b>
            </div><br>
            <div style='float:left;'>
                Average Distance Travelled(Per day & Vehicles): <b><?php if(isset($kms_tracked['avg_dis_trav_veh'])){ echo round($kms_tracked['avg_dis_trav_veh']); } else { echo 0;} ?>kms</b>
            </div>
            
        </div><br><hr/>
        <!-- Ends, Distance travelled reports -->
        
        <!-- Starts, Sundays Distance travelled on reports -->
        <div style='float:left'>
            <div style='float:left;'>
                Total no. of kms travelled on Sundays: <b><?php if(isset($kms_tracked['sun_dis_trav'])){ echo round($kms_tracked['sun_dis_trav']); } else { echo 0;} ?>kms</b>
            </div><br/>

            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <?php 
                    $unique_dates = (isset($kms_tracked['unique_dates'])) ? array_unique($kms_tracked['unique_dates']) : 0;
                    $unique_date_c = empty($unique_dates) ? 1 : count($unique_dates); 
                    ?>
                    <tr style='background-color:#CCCCCC;'><th colspan='<?php echo $unique_date_c+1; ?>'>Distance travelled(On Sundays)</th></tr>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:300px;height:auto;'>Vehicle No.</th>
                        <th style='width:600px;height:auto;' colspan="<?php echo $unique_date_c; ?>">Distance Travelled[KMS]-Datewise</th>
                    </tr>
                    <?php
                    
                    if(isset($kms_tracked['sun_dis_trav_data']['data'])){
                        
                        echo "<tr  style='background-color:#CCCCCC;'><th></th>";
                        foreach($unique_dates as $header_date){
                            echo "<th>$header_date</th>";
                        }
                        echo "</tr>";
                    
                        $s_data = $kms_tracked['sun_dis_trav_data']['data'];
                        foreach($s_data as $veh_name=>$veh_counts){
                            echo "<tr><td>$veh_name</td>";
                            foreach($unique_dates as $header_date){
                                $sun_count = isset($veh_counts[$header_date])? $veh_counts[$header_date] : 0;
                                echo "<td>$sun_count</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    else{
                        echo '<tr><td colspan="2">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            
        </div><br><hr/>
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
            </div><br>
            <div style='float:left;'>
                Sudden change of acceleration during day (7 am to 8 pm): <b><?php if(isset($overspeed_data['acc_day'])){ echo $overspeed_data['acc_day'];} else { echo 0;} ?></b>
            </div><br>
            <div style='float:left;'>
                Sudden change of acceleration during night (8 pm to 7 am): <b><?php if(isset($overspeed_data['acc_night'])){ echo $overspeed_data['acc_night'];} else { echo 0;} ?></b>
            </div><br>
            <!--<div style='float:left;'>
                Total no. of Sharp turn: <b><?php //if(isset($overspeed_data['sharp_turn'])){ echo $overspeed_data['sharp_turn'];} else { echo 0;} ?></b>
            </div><br clear='both'>-->
            <div style='float:left;'>
                Total no. of Towing: <b><?php if(isset($overspeed_data['towing'])){ echo $overspeed_data['towing'];} else { echo 0;} ?></b>
            </div><br clear='both'>
            
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'><th colspan='5'>Advanced Alerts</th></tr>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:150px;height:auto;'>Vehicle No.</th>
                        <th style='width:150px;height:auto;'>Harsh Break</th>
                        <th style='width:150px;height:auto;'>Acceleration(Day)</th>
                        <th style='width:150px;height:auto;'>Acceleration(Night)</th>
                        <!--<th style='width:150px;height:auto;'>Sharp Turn</th>-->
                        <th style='width:150px;height:auto;'>Towing</th>
                    </tr>
                    <?php
                    
                    if(isset($overspeed_data['custom_report'])){
                        
                        $veh = explode(',', $overspeed_data['custom_report']['vehicles']);
                        $harsh = explode(',', $overspeed_data['custom_report']['harsh_b']);
                        $accl_day = explode(',', $overspeed_data['custom_report']['accl_day']);
                        $accl_night = explode(',', $overspeed_data['custom_report']['accl_night']);
                        //$sharp_turn = explode(',', $overspeed_data['custom_report']['sharp_turn']);
                        $towing = explode(',', $overspeed_data['custom_report']['towing']);
                        array_pop($veh);
                        $i=0;
                        
                        foreach($veh as $veh_no){
                            $veh_no = str_replace('\'', "", $veh_no);
                            echo "<tr><td>$veh_no</td><td>".$harsh[$i]."</td><td>".$accl_day[$i]."</td><td>".$accl_night[$i]."</td><td>".$towing[$i]."</td></tr>";
                            $i++;
                        }
                    }
                    else{
                        echo '<tr><td colspan="5">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            
        </div><br><hr/>
        <!-- Ends, Advanced Alert reports -->
        
        
        <!-- Starts, Incidents Alerts reports -->
        <div style='float:left'>
            <div style='float:left;'>
                No. of incidents recorded: <b><?php $inc_recrd = get_comqueue_count($start_date, $end_date); echo $inc_recrd['total']; ?></b>
            </div><br>
            
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'><th colspan='8'>Alerts(Incidents)</th></tr>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:150px;height:auto;'>Vehicle No.</th>
                        <th style='width:80px;height:auto;'>AC Alert</th>
                        <th style='width:130px;height:auto;'>Checkpoint Alert</th>
                        <th style='width:100px;height:auto;'>Ignition Alert</th>
                        <th style='width:130px;height:auto;'>Overspeed Alert</th>
                        <th style='width:100px;height:auto;'>Tamper Alert</th>
                        <th style='width:100px;height:auto;'>Power Cut Alert</th>
                        <th style='width:130px;height:auto;'>Fence Conflict Alert</th>
                    </tr>
                    <?php
                    if(!empty($inc_recrd['data'])){

                        $ac_alert = $ch_alert = $ig_alert = $overspd_alert = $tamp_alert = $pwrcut_alert = $pwrcut_alert = $fence_alert = array();
                        $i = 0;
                        foreach($inc_recrd['data'] as $veh_name=>$single_type){
                            if(empty($veh_name)){
                                continue;
                            }
                            $ac_alert[$i] = isset($single_type['ac_alert']) ? $single_type['ac_alert'] : 0;
                            $ch_alert[$i] = isset($single_type['ch_alert']) ? $single_type['ch_alert'] : 0;
                            $ig_alert[$i] = isset($single_type['ig_alert']) ? $single_type['ig_alert'] : 0;
                            $overspd_alert[$i] = isset($single_type['overspd_alert']) ? $single_type['overspd_alert'] : 0;
                            $tamp_alert[$i] = isset($single_type['tamp_alert']) ? $single_type['tamp_alert'] : 0;
                            $pwrcut_alert[$i] = isset($single_type['pwrcut_alert']) ? $single_type['pwrcut_alert'] : 0;
                            $fence_alert[$i] = isset($single_type['fence_alert']) ? $single_type['fence_alert'] : 0;
                            
                            echo "<tr><td>$veh_name</td><td>$ac_alert[$i]</td><td>$ch_alert[$i]</td><td>$ig_alert[$i]</td><td>$overspd_alert[$i]</td>
                                    <td>$tamp_alert[$i]</td><td>$pwrcut_alert[$i]</td><td>$fence_alert[$i]</td></tr>";
                            $i++;

                        }
                        echo "<tr style='background-color:#CCCCCC;'><td>Total</td><td>".array_sum($ac_alert)."</td><td>".array_sum($ch_alert)."</td><td>".array_sum($ig_alert)."</td>
                                <td>".array_sum($overspd_alert)."</td><td>".array_sum($tamp_alert)."</td><td>".array_sum($pwrcut_alert)."</td><td>".array_sum($fence_alert)."</td></tr>";
                    }
                    else{
                        echo '<tr><td colspan="8">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            
        </div><br><hr/>
        <!-- Ends, Incidents Alerts reports -->
        
        <!-- Starts, Idle Time reports -->
        <div style='float:left'>
            <div style='float:left;'>
                Total Idle Time(All Vehicles): <b><?php if(isset($kms_tracked['total_idle_time'])){ echo $kms_tracked['total_idle_time'];} else { echo 0;} ?></b>
            </div><br>
            <div style='float:left;'>
                Total Running Time(All Vehicles): <b><?php if(isset($kms_tracked['total_running_time'])){ echo $kms_tracked['total_running_time'];} else { echo 0;} ?></b>
            </div><br>
            
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'><th colspan='3'>Idle Time</th></tr>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:300px;height:auto;'>Vehicle No.</th>
                        <th style='width:150px;height:auto;'>Idle Time</th>
                        <th style='width:150px;height:auto;'>Running Time</th>
                    </tr>
                    <?php
                    if(isset($kms_tracked['idle_time_data_final'])){
                        
                        $veh = explode(',', $kms_tracked['idle_time_data_final']['veh_names']);
                        $veh_idle_time = explode(',', $kms_tracked['idle_time_data_final']['veh_idle_time']);
                        $veh_running_time = explode(',', $kms_tracked['idle_time_data_final']['veh_running_time']);
                        array_pop($veh);
                        $i=0;

                        foreach($veh as $veh_data){
                            $veh_no = str_replace('\'', "", $veh_data);
                            echo "<tr><td>$veh_no</td><td>".round($veh_idle_time[$i])."</td><td>".round($veh_running_time[$i])."</td></tr>";
                            $i++;
                        }
                    }
                    else{
                        echo '<tr><td colspan="3">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div><br><hr/>
        <!-- Ends, Idle Time reports -->
        
        <!--Starts, vehicle-iginition=on and idle>=15-mins and at-same-place=true-->
        <div style='float:left'>
            
            <br>
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'><th colspan='2'>Idle and Ignition-on(15 mins) Report</th></tr>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:300px;height:auto;'>Vehicle No.</th>
                        <th style='width:300px;height:auto;'>Idle & Ignition on[Count]</th>
                    </tr>
                    <?php
                    if(isset($overspeed_data['idle_hold_ig_off_data'])){
                        
                        $veh = explode(',', $overspeed_data['idle_hold_ig_off_data']['veh']);
                        $count_data = explode(',', $overspeed_data['idle_hold_ig_off_data']['count']);
                        array_pop($veh);
                        $i=0;

                        foreach($veh as $veh_data){
                            $veh_no = str_replace('\'', "", $veh_data);
                            echo "<tr><td>$veh_no</td><td>$count_data[$i]</td></tr>";
                            $i++;
                        }
                    }
                    else{
                        echo '<tr><td colspan="2">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div><br><hr/>
        <!--Ends, vehicle-iginition=on and idle>=15-mins and at-same-place=true-->
        
        <!--Starts, vehicle-iginition=on and idle>=15-mins and AC=one and at-same-place=true-->
        <div style='float:left'>
            
            <br>
            
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'><th colspan='2'>Idle & Ignition & AC on Report</th></tr>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:300px;height:auto;'>Vehicle No.</th>
                        <th style='width:300px;height:auto;'>Idle & Ignition & AC on[Count]</th>
                    </tr>
                    <?php
                    if(isset($overspeed_data['idle_hold_ig_ac_data'])){
                        
                        $veh = explode(',', $overspeed_data['idle_hold_ig_ac_data']['veh']);
                        $count_data = explode(',', $overspeed_data['idle_hold_ig_ac_data']['count']);
                        array_pop($veh);
                        $i=0;

                        foreach($veh as $veh_data){
                            $veh_no = str_replace('\'', "", $veh_data);
                            echo "<tr><td>$veh_no</td><td>$count_data[$i]</td></tr>";
                            $i++;
                        }
                    }
                    else{
                        echo '<tr><td colspan="2">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div><br clear='both'><hr/>
        <!--Ends, vehicle-iginition=on and idle>=15-mins and AC=one and at-same-place=true-->
        
        
        <!--Starts, trip report: trips ended after 10 pm-->
        <div style='float:left'>
            
            <br>
            <?php $trip_report = generate_trip_report(null, null,'get_data_end','table'); ?>
            <div style='float:left;'>
                Trips Ended after 10 pm: <?php echo $trip_report['total']; ?>
            </div><br clear='both'>
            
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'><th colspan='2'>Trip Report</th></tr>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:300px;height:auto;'>Vehicle No.</th>
                        <th style='width:300px;height:auto;'>Trip[Count]</th>
                    </tr>
                    <?php
                    if($trip_report['total']!=0){
                        foreach($trip_report['data'] as $veh_no=>$veh_count){
                            echo "<tr><td>$veh_no</td><td>$veh_count</td></tr>";
                        }
                    }
                    else{
                        echo '<tr><td colspan="2">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div><br clear='both'><hr/>
        <!--Ends, trip report: trips ended after 10 pm-->
        
        <!--Starts, trip report: trips started after 9 pm-->
        <div style='float:left'>
            
            <br>
            <?php $trip_report_st = generate_trip_report(null, null,'get_data_start','table'); ?>
            <div style='float:left;'>
                Trips Started after 9 pm: <?php echo $trip_report_st['total']; ?>
            </div><br clear='both'>
            
            <table align='center' style='width: auto; font-size:13px; text-align:center;border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'><th colspan='2'>Trip Report</th></tr>
                    <tr style='background-color:#CCCCCC;'>
                        <th style='width:300px;height:auto;'>Vehicle No.</th>
                        <th style='width:300px;height:auto;'>Trip[Count]</th>
                    </tr>
                    <?php
                    if($trip_report_st['total']!=0){
                        foreach($trip_report_st['data'] as $veh_no=>$veh_count){
                            echo "<tr><td>$veh_no</td><td>$veh_count</td></tr>";
                        }
                    }
                    else{
                        echo '<tr><td colspan="2">No Data</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div><br clear='both'><hr/>
        <!--Ends, trip report: trips started after 9 pm-->
        
    </div>
    
    
    <?php
        }
    }
    ?>

</div>

<?php

$content = ob_get_clean();
//echo $content;die();
require_once('../reports/html2pdf.php');
try{
    $html2pdf = new HTML2PDF('L', 'A4', 'en');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->Output("InformaticsReport.pdf");
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
?>