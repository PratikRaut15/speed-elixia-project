<br/>
<body style="overflow-x: scroll;">
<form action="route_dashboard.php" method="POST">
<?php
    $client_login = 0;
    $ecode_startdate = '01-01-1970';
    $ecode_enddate = date('d-m-Y');
    $client_codeObj = new stdClass();
    if(isset($_SESSION['ecodeid'])){
        $client_login = 1;
        $ecode_id = $_SESSION['ecodeid'];

        if(isset($_SESSION['startdate'])){
            $ecode_startdate = date("d-m-Y",strtotime($_SESSION['startdate']));
        }
        if(isset($_SESSION['enddate'])){
            $ecode_enddate = date("d-m-Y",strtotime($_SESSION['enddate']));
        }

        $client_codeObj->code = $ecode_id;
        $client_codeObj->startdate = date("Y-m-d",strtotime($ecode_startdate));
        $client_codeObj->enddate = date("Y-m-d",strtotime($ecode_enddate));
    }
    
    $sdate =date('d-m-Y');
    if (!isset($_POST['STime'])) {$stime = "00:00";} else { $stime = $_POST['STime'];}
    if (!isset($_POST['ETime'])) {$etime = "23:59";} else { $etime = $_POST['ETime'];}
    if (!isset($_POST['SDate'])) {$sdate =date('d-m-Y'); } else { $sdate = $_POST['SDate'];}
   
?>
<table><tr>
    <td>Date</td>
    <td>
    <input id="S_Date" name="SDate" type="text" value="<?php echo $sdate; ?>" required/></td>
    </td>
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
<div id="abc" style="overflow-x: scroll;" >
<table class="table  table-bordered table-striped dTableR dataTable"  style="width:99%;">
    <thead>
<?php
    $shops = getshopscount();
    // print_r($shops);
    // die();
    $shopCount = $shops;
    
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
                if ($_SESSION['customerno'] == 682) {
                    $startPoint = 'Factory';
                }
                echo "<td style='text-align:center;'><b>" . $startPoint . "</b></td>";
            } else {
                echo "<td style='text-align:center;'><b>Shop $i</b></td>";
            }
            echo "<td colspan='3'></td>";
        }
    }
        echo "<td colspan='4' style='text-align:center;'><b>Status</b></td>";
        echo "<td style='text-align:center;'><b>ETA Status</b></td></tr></thead>";
        echo "<tbody>";
        $routelist = getroutes_list();
        $vehicleArray = array();
        $statusList = array();
        //$sqlite_check = 1; // IF PRESENT
        if (isset($routelist) && !empty($routelist) ) {
            foreach ($routelist as $route_list) {
                $vehicles = getvehicles_latlng($route_list->routeid);
                if (isset($vehicles) ) {
                    foreach ($vehicles as $vehicle) {

                        $image = vehicleimage($vehicle);
                        echo "<input type='hidden' id='latlong" . $vehicle->vehicleid . "' value='" . $vehicle->devicelat . "," . $vehicle->devicelong . "'/>
                        <input type='hidden' id='vehicleimage" . $vehicle->vehicleid . "' value='" . $image . "'/>
                        <input type='hidden' id='vehicle$vehicle->vehicleid' value='$vehicle->vehicleno'>";
                        $vehicleArray[$vehicle->vehicleid]['vehicleno'] = $vehicle->vehicleno;
                        $vehicleArray[$vehicle->vehicleid]['routename'] = $route_list->routename;
                        $vehicleArray[$vehicle->vehicleid]['ignition'] = $vehicle->ignition;
                        //check whether the vehicles have to strictly follow the route 
                        $followRouteSequence = $_SESSION['use_checkpoint_settings'];
                        $checkpoints  = get_chk_for_vehicle($vehicle->vehicleid,$followRouteSequence);
                        $routes = getroutes($vehicle->vehicleid);
                        //$routecount = count($routes);
                        $status = '';
                        if (isset($routes)) {
                            $lastelement = end($routes);
                            $f_element = array_values($routes);
                            $firstelement = array_shift($f_element);
                            $reportsequences_desc = report_no_seq($vehicle->vehicleid, $_SESSION['customerno'], $checkpoints, $stime, $etime,$sdate,$followRouteSequence,$client_codeObj);
                            $sqlite_checkpoint_array = array();  
                            $checkpoint_visited_counter=0;
   

                               foreach($reportsequences_desc as $sqlite_report){
                                    $sqlite_checkpoint_array[$checkpoint_visited_counter]['chkid']=$sqlite_report['chkid'];
                                    $sqlite_checkpoint_array[$checkpoint_visited_counter]['status']=$sqlite_report['status'];
                                    $checkpoint_visited_counter++;
                                }  
                                foreach($reportsequences_desc as $chk){
                                    $statusList[$vehicle->vehicleid][$chk['chkid']]['lastupdated'] = $chk['lastupdated'];
                                }
                                $chks = array();
                                //$routecount=count($routes);
                                foreach($routes as $route){
                                    $chks[]=$route->checkpointid;
                                }

                                $visitedList[$vehicle->vehicleid] = checkStatuses($vehicle->vehicleid,$chks,$sqlite_checkpoint_array);
                                $checkpointDetails = array();
                                foreach($visitedList as $vehicleId=>$visitedChkPts){

                                    foreach($visitedChkPts as $k=>$v){
                                        if($k=='status'){
                                            continue;
                                        }
                                        else{
                                            $temp =array();
                                            $temp = getlatlng_chkpt($k, $vehicleId);
                                            $checkpointDetails[$vehicleId][$k]['cName']=isset($temp->checkpointname)?$temp->checkpointname:'a';
                                            $checkpointDetails[$vehicleId][$k]['etaStatus']=isset($temp->etaStatus)?$temp->etaStatus:'';
                                            $checkpointDetails[$vehicleId][$k]['lastUpdated']=isset($temp->lastupdated)?$temp->lastupdated:'';
                                            $checkpointDetails[$vehicleId][$k]['speed']=isset($temp->curSpeed)?$temp->curSpeed:'';
                                        }
                                    }
                                } 
                            
                        }
                    } 
                }
            }

            if(!empty($visitedList)){
                foreach($visitedList as $vehicleId=>$checkpoints_array){
                    echo "<tr><td><b>".$vehicleArray[$vehicleId]['routename']."</b></td>";
                    echo "<td><b>".$vehicleArray[$vehicleId]['vehicleno']."</b></td>";
                    $ignition_status = $vehicleArray[$vehicle->vehicleid]['ignition'];
                    if($ignition_status==1){
                        $color_ignition ='green';
                    }
                    else{
                        $color_ignition ='grey';
                    }
                        $tdCounter = 0;
                    foreach($checkpoints_array as $checkpointId=>$visitedStatus){
                        if(isset($checkpointDetails[$vehicleId][$checkpointId]['cName'])){
                            if($checkpointId=='status'){
                                continue;
                            }
                            if($visitedStatus==1){
                                echo "<td style='text-align:center;border-bottom:1px solid green;'>
                                <a href='#route_popup' onclick='create_map_for_modal($vehicleId);' id='route_pop'>
                                <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: ".$vehicleArray[$vehicleId]['vehicleno']." <br> Checkpoint:". $checkpointDetails[$vehicleId][$checkpointId]['cName']."
                                <br> Speed: ".$checkpointDetails[$vehicleId][$checkpointId]['speed']." <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($statusList[$vehicleId][$checkpointId]['lastupdated'])) . "
                                <br/> ETA Status : ".$checkpointDetails[$vehicleId][$checkpointId]['etaStatus']."
                                <br> Temp 1 : <br> Temp 2 : 'style='color:$color_ignition;'></i></a></td><td style='border-bottom:1px solid green;'></td><td style='border-bottom:1px solid green;'></td><td style='border-bottom:1px solid green;'></td>";
                            }elseif($visitedStatus==2){
                                echo "<td style='text-align:center;border-bottom:1px solid green;'>
                                <a href='#route_popup' id='route_pop'>
                                <i class='fa fa-check route-tooltip-top'  original-title='Vehicle no: ".$vehicleArray[$vehicleId]['vehicleno']." <br> Checkpoint:". $checkpointDetails[$vehicleId][$checkpointId]['cName']."
                                <br> Speed: ".$checkpointDetails[$vehicleId][$checkpointId]['speed']." <br> Lastupdated: " . date(speedConstants::DEFAULT_TIME, strtotime($statusList[$vehicleId][$checkpointId]['lastupdated'])) . "
                                <br/> ETA Status : ".$checkpointDetails[$vehicleId][$checkpointId]['etaStatus']."
                                <br> Temp 1 : <br> Temp 2 : 'style='color:green;'></i></a></td><td style='border-bottom:1px solid green;'></td><td style='border-bottom:1px solid green;'></td><td style='border-bottom:1px solid green;'></td>";
                            }else{
                                echo "<td style='text-align:center;'>
                                <a href='#route_popup' id='route_pop'>
                                <i class='fa fa-times route-tooltip-top'  original-title='Vehicle no: ".$vehicleArray[$vehicleId]['vehicleno']." <br> Checkpoint:". $checkpointDetails[$vehicleId][$checkpointId]['cName']."
                                '></i></a></td><td></td><td></td><td></td>";
                            }
                        }
                        $tdCounter++;
                    }
                    $c=0;
                
                    for($c = 0; $c <= ($shopCount-$tdCounter); $c++){
                        echo "<td>---</td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                    }
                    echo "<td style='text-align:center;'>
                    <a href='#route_popup' onclick='create_map_for_modal($vehicleId);' id='route_pop'>
                    <i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: ".$vehicleArray[$vehicleId]['vehicleno']."<br> Speed:  <br> Lastupdated:<br/> ETA Status :
                    <br> Temp 1 : <br> Temp 2 : 'style='color:$color_ignition;'></i></a></td><td></td><td></td><td></td><td></td>";

                    echo "</tr>";
                }   
            }
            
            echo "</tbody>";
        }
        else {
            echo "<tr>
                <td colspan='100%' style='text-align:center;'>No Route</td>
            </tr>";
        }

?>
</table>
</div>
</form>
<a href="#x" class="overlay" id="route_popup"></a>
<div class="popup">
    <div id="map_route"></div>
    <div id="info" align="center"></div>
    <a class="close1" href="#close"></a>
</div>
<script>
jQuery(document).ready(function () {  
    //var clientLogin = <?php echo $client_login ?>;


        var startdate = '<?php echo $ecode_startdate ?>';
        var enddate = '<?php echo $ecode_enddate ?>';
        var sdate = '<?php echo $sdate ?>';
    
     jQuery('#S_Date').datepicker({
          dateFormat: "dd-mm-yy",
          language: 'en',
          autoclose: 1,
          startDate: sdate,
          minDate: startdate,
          maxDate: enddate  
      });   
    

    
    
 });
</script>