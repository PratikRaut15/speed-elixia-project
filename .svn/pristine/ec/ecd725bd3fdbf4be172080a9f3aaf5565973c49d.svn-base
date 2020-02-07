<br/></br><table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:80%">
    <thead>
<?php
    $shops = getshopscount();
    if(isset($shops))
    {
        echo "<tr>";
        echo "<td><b>Route Name</b></td>";
        echo "<td><b>Vehicle No.</b></td>";
        for($i=0;$i<$shops;$i++)
        {
            if($i == 0)
            echo "<td style='text-align:center;'><b>MFPL</b></td>";
            else
            echo "<td style='text-align:center;'><b>Shop $i</b></td>";
            echo "<td colspan='3'></td>";
        }
        echo "<td colspan='4' style='text-align:center;'><b>Status</b></td></tr></thead>";
        echo "<tbody>";
        $routelist = getroutes_list();
        if(isset($routelist)){
            foreach($routelist as $route_list){
        $vehicles = getvehicles_latlng($route_list->routeid);
            if(isset($vehicles)){
                foreach($vehicles as $vehicle){
            echo "<tr><td><b>$route_list->routename</b></td>";
                echo "<td><b>$vehicle->vehicleno</b></td>";
                $routeid = get_routeid($vehicle->vehicleid);
                $routes = getroutes($vehicle->vehicleid);
                $routecount = count($routes);
                //$routes = array_reverse($routes);    // remove this line after testing ----- important
                //print_r($routes);
                                $status = '';
                if($vehicle->ignition == '1')
                    $color = '#0193CC';
                else
                    $color = 'grey';
                    if(isset($routes)){
                        //echo $routeid.'_'.$firstelement->checkpointid.'_'.$lastelement->checkpointid.'_'.$vehicle->vehicleid.'<br>';
                        $lastelement = end($routes);
                        $firstelement = array_shift(array_values($routes));
                        //$firstelement->checkpointid = '353';
                        $k = 0;
                        $z = 0;
                        $image = vehicleimage($vehicle);
                        echo "<input type='hidden' id='latlong".$vehicle->vehicleid."' value='".$vehicle->devicelat.",".$vehicle->devicelong."'/><input type='hidden' id='vehicleimage".$vehicle->vehicleid."' value='".$image."'/><input type='hidden' id='vehicle$vehicle->vehicleid' value='$vehicle->vehicleno'>";
                        //$l = 0;
                        //echo $firstelement->checkpointid.'_'.$lastelement->checkpointid.'<br>';
                                $reportsequences_desc = report_desc_seq($vehicle->vehicleid, $_SESSION['customerno'],$firstelement->checkpointid,$lastelement->checkpointid);
                                $lastarray = array_slice($reportsequences_desc, 0, 1);
                                $lastarray = $lastarray[0];
                                $reportsequences_desc = array_reverse($reportsequences_desc);
                                print_r($reportsequences_desc);
                                echo "<br>$vehicle->vehicleid<br>";
                                print_r($routes);
                                echo "<br><br><br>";
                                $datacount = count($reportsequences_desc);
                                if(isset($reportsequences_desc)){
                                    foreach($reportsequences_desc as $reportsequence){
                                        $proceed = 0;
//                                        if($reportsequence->chkid == $routes[$z+1]->checkpointid){
//                                            $currentchkpt = getlatlng_chkpt($routes[$z]->checkpointid);
//                                            echo "<td style='text-align:center;'><i class='fa fa-times route-tooltip-top' original-title='Checkpoint Name: $currentchkpt->checkpointname '></i></td><td></td><td></td><td></td>";
//                                            $z++;
//                                        }
                                            for($z=$z;$z<=$shops;$z++)
                                            {
                                            echo $reportsequence->chkid.'_'. $routes[$z]->checkpointid.'<br>';
                                                if($reportsequence->chkid != $routes[$z]->checkpointid){
                                                 echo "<td style='text-align:center;'>aa<i class='fa fa-times route-tooltip-top' original-title='Checkpoint Name: $currentchkpt->checkpointname '></i></td><td></td><td></td><td></td>";
                                                }
                                                else{
                                                    break;
                                                }
                                                if($z == $shops-1)
                                                    break;
                                            }
                                        //echo $reportsequence->chkid .'_'. $routes[$z]->checkpointid .'_'. $reportsequence->status .'_'. isset($routes[$z+1]->checkpointid) .'_'. $reportsequences_desc[$k+1]->chkid .'_'. $reportsequences_desc[$k+1]->status.'<br>';
                                            if($reportsequence->chkid == $routes[$z]->checkpointid && $reportsequence->status == 0){
                                                //$invariable = "<td>. ".$routes[$z]->checkpointid." ".$reportsequences->chkid." ".$reportsequences->id."</td><td></td><td></td><td></td>";
                                                $chkpt = getlatlng_chkpt($reportsequence->chkid);
                                                if($vehicle->ignition == '1')
                                                $invariable = "<td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Checkpoint: $chkpt->checkpointname <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated))." <br> Temp 1 : <br> Temp 2 : ' style='color:blue;'></i></a></td><td></td><td></td><td></td>";
                                                else
                                                $invariable = "<td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Checkpoint: $chkpt->checkpointname <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated))." <br> Temp 1 : <br> Temp 2 : ' style='color:grey;'></i></a></td><td></td><td></td><td></td>";
                                                $l++;
                                            }
                                            elseif($reportsequence->chkid == $routes[$z]->checkpointid && $reportsequence->status == 1 && isset($routes[$z+1]->checkpointid) && !isset($reportsequences_desc[$k+1]->chkid) && !isset($reportsequences_desc[$k+1]->status)){
                                                //$invariable = "<td><i class='icon-check'></i> ".$routes[$z]->checkpointid." ".$reportsequence->chkid." ".$reportsequence->id." </td><td></td><td></td><td></td>";
                                                //print_r($vehicle);
                                                $currentchkpt = getlatlng_chkpt($reportsequence->chkid);
                                                $nextchkpt = getlatlng_chkpt($routes[$z+1]->checkpointid);
    //                                            print_r($currentchkpt);
    //                                            print_r($nextchkpt);
                                                //echo $vehicle->devicelat, $vehicle->devicelong;
                                                $d = distance($currentchkpt->lat, $currentchkpt->long, $nextchkpt->lat, $nextchkpt->long, 'K');
                                                //echo '<br>';
                                                $x = distance($vehicle->devicelat, $vehicle->devicelong, $nextchkpt->lat, $nextchkpt->long, 'K');
                                                $invariable = "<td style='text-align:center;'><i class='fa fa-check fa-lg route-tooltip-top' original-title='Vehicle no: $vehicle->vehicleno <br> Checkpoint: $currentchkpt->checkpointname <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated))." <br> Distance: $x ' style='color:green;'></i></td>";
                                                if($x <= (1/3)*$d)
                                                $invariable .= "<td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated))." <br> Distance: $x from $nextchkpt->checkpointname <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td><td></td><td></td>";
                                                else if($x <= (2/3)*$d)
                                                $invariable .= "<td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated))." <br> Distance: $x from $nextchkpt->checkpointname <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td><td></td>";
                                                else if($x <= $d && $x >= (2/3)*$d)
                                                $invariable .= "<td></td><td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated))." <br> Distance: $x from $nextchkpt->checkpointname <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td>";
                                                else
                                                $invariable .= "<td></td><td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated))." <br> Distance: $x from $nextchkpt->checkpointname <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td>";
                                                $l++;
                                            }
                                            else if($reportsequence->chkid == $routes[$z]->checkpointid && $reportsequence->status == 1 && $reportsequence->chkid != $lastelement->checkpointid){
                                                $chkpt = getlatlng_chkpt($reportsequence->chkid);
                                                //$invariable = "<td><i class='icon-check'></i> ".$routes[$z]->checkpointid." ".$reportsequence->chkid." ".$reportsequence->id." </td><td></td><td></td><td></td>";
                                                $invariable = "<td style='text-align:center;'><i class='fa fa-check fa-lg route-tooltip-top' original-title='Vehicle no: $vehicle->vehicleno <br> Checkpoint: $chkpt->checkpointname <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated))." ' style='color:green;'></i></td><td></td><td></td><td></td>";
                                                $l++;
                                            }
                                            elseif($reportsequence->chkid == $routes[$z]->checkpointid && ($reportsequence->chkid == $lastelement->checkpointid) && $reportsequence->status == 1 && isset($reportsequences_desc[$k+1]->chkid)){
                                                //echo $reportsequence->chkid.'_'.$lastelement->checkpointid;
                                                    $proceed = 1;
                                            }
                                            else{
                                                //echo $reportsequence->chkid.'_'.$routes[$z]->checkpointid.'_'.$reportsequences_desc[$k+1]->chkid.'<br>';
                                                //$invariable = "<td>X ".$routes[$z]->checkpointid." ".$reportsequence->chkid." ".$reportsequence->id." $reportsequence->chkid $reportsequence->id</td><td></td><td></td><td></td>";
    //                                            if($reportsequence->chkid == $firstelement->checkpointid){
    //                                                $invariable = "<td colspan='100%' style='text-align:center;'>Cycle Complete</td>";
    //                                            }
    //                                            else{
                                                $currentchkpt = getlatlng_chkpt($reportsequence->chkid);
                                                $nextchkpt = getlatlng_chkpt($routes[$z+1]->checkpointid);
                                                $missedchkpt = getlatlng_chkpt($routes[$z]->checkpointid);
                                                $d = distance($currentchkpt->lat, $currentchkpt->long, $nextchkpt->lat, $nextchkpt->long, 'K');

                                                $x = distance($vehicle->devicelat, $vehicle->devicelong, $nextchkpt->lat, $nextchkpt->long, 'K');
                                                if($reportsequence->chkid == $firstelement->checkpointid){$status = 'reached';}
                                                if($k == $datacount-1 && $reportsequence->chkid == $firstelement->checkpointid){
                                                    $invariable = "<td style='text-align:center;'><i class='fa fa-times route-tooltip-top' original-title='Checkpoint Name: $missedchkpt->checkpointname'></i></td>";
                                                    $invariable .= "<td></td><td></td><td></td>";
                                                }
                                                else if($k == $datacount-1){
                                                    $invariable = "<td style='text-align:center;'><i class='fa fa-times route-tooltip-top' original-title='Checkpoint Name: $missedchkpt->checkpointname'></i></td>";
                                                    $invariable .= "<td></td><td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated))." <br> Distance: $x from $nextchkpt->checkpointname <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td>";
                                                }
//                                                else if($z == $routecount-1){
//                                                    $invariable = "<td style='text-align:center;'><i class='fa fa-times route-tooltip-top' original-title='Checkpoint Name: $missedchkpt->checkpointname'></i></td>";
//                                                    $invariable .= "<td></td><td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated))." <br> Distance: $x from $nextchkpt->checkpointname <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td>";
//                                                }
                                                else
                                                    $invariable = "<td style='text-align:center;'><i class='fa fa-times route-tooltip-top' original-title='Checkpoint Name: $missedchkpt->checkpointname'></i></td><td></td><td></td><td></td>";
    //                                            }
    //                                            for($z=$z;$z<=$shops;$z++)
    //                                            {
    //                                            echo $reportsequence->chkid.'_'. $routes[$z]->checkpointid.'<br>';
    //                                                if($reportsequence->chkid != $routes[$z]->checkpointid){
    //                                                 echo "<td>X $j ".$routes[$z]->checkpointid." ".$reportsequences_desc[$l]->chkid." ".$reportsequences_desc[$l]->id." $reportsequence->chkid $reportsequence->id</td><td></td><td></td><td></td>";
    //                                                }
    //                                            }
                                            }

                                            if($proceed == 1){
                                                $n = 0;
                                                for($j=$k;$j<=$datacount;$j++){
                                                    if($reportsequences_desc[$j]->chkid == $firstelement->checkpointid && $reportsequences_desc[$j]->status == '0'){
    //                                                    echo $reportsequences_desc[$j]->id.'_'.$j.'_'.$datacount.'_'.$k;
    //                                                    echo $n.'<br>';
                                                        $n++;
                                                    $status = 'reached';
                                                    }
                                                }
                                                $currentchkpt = getlatlng_chkpt($reportsequence->chkid);
                                                if(isset($status) && $status == 'reached'){
                                                $invariable = "<td style='text-align:center;'><i class='fa fa-check fa-lg route-tooltip-top' original-title='Vehicle no: $vehicle->vehicleno <br> Checkpoint: $currentchkpt->checkpointname <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated))." ' style='color:green;'></i></td><td></td><td></td><td></td>";
                                                }
                                                else{
                                                $nextchkpt = getlatlng_chkpt($firstelement->checkpointid);
                                                $d = distance($currentchkpt->lat, $currentchkpt->long, $nextchkpt->lat, $nextchkpt->long, 'K');
                                                //echo '<br>';
                                                $x = distance($vehicle->devicelat, $vehicle->devicelong, $nextchkpt->lat, $nextchkpt->long, 'K');
                                                $invariable = "<td style='text-align:center;'><i class='fa fa-check fa-lg route-tooltip-top' original-title='Vehicle no: $vehicle->vehicleno <br> Checkpoint: $currentchkpt->checkpointname <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated))." <br> Distance: $x ' style='color:green;'></i></td>";
                                                if($x <= (1/3)*$d)
                                                $invariable .= "<td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated))." <br> Distance: $x from $nextchkpt->checkpointname <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td><td></td><td></td>";
                                                else if($x <= (2/3)*$d)
                                                $invariable .= "<td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated))." <br> Distance: $x from $nextchkpt->checkpointname <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td><td></td>";
                                                else if($x <= $d && $x >= (2/3)*$d)
                                                $invariable .= "<td></td><td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated))." <br> Distance: $x from $nextchkpt->checkpointname <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td>";
                                                else
                                                $invariable .= "<td></td><td></td><td style='text-align:center;'><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($reportsequence->lastupdated))." <br> Distance: $x from $nextchkpt->checkpointname <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i></a></td>";
                                                }
                                            }

                                            if($reportsequence->chkid != $reportsequences_desc[$k+1]->chkid){
                                                //echo $k.'<br>';
                                                echo $invariable;
                                                $z++;
                                                if($reportsequence->chkid == $lastelement->checkpointid || $z == $shops)
                                                    break;

                                            }
                                            else if($z == $shops-1 && $status != 'reached'){
                                                $z++;
                                            break;}
                                            else if($z == $routecount && $status != 'reached'){
                                            break;
                                            }
                                                $k++;
                                    }
                                }
                            $cols = $shops-$z;
                            for($j=1;$j<=$cols;$j++)
                            {
                                if($status == 'reached'){
                                    echo "<td style='text-align:center;'>N/A</td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                }
                                else{
                                    echo "<td style='text-align:center;'>---</td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                }
                            }
                            if($status == 'reached'){
                                echo "<td><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated))." <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i><a/></td><td colspan='3' style='text-align:center;'>Cycle Complete</td>";
                            }
                            else if($lastarray->chkid == $firstelement->checkpointid){
                                echo "<td><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated))." <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i><a/></td><td colspan='3' style='text-align:center;'>Cycle Complete</td>";
                            }
                            else{
                                echo "<td><a href='#route_popup' onclick='create_map_for_modal($vehicle->vehicleid);' id='route_pop'><i class='fa fa-circle route-tooltip-top'  original-title='Vehicle no: $vehicle->vehicleno <br> Speed: $vehicle->curspeed <br> Lastupdated: ".date(speedConstants::DEFAULT_TIME, strtotime($vehicle->lastupdated))." <br> Temp 1 : <br> Temp 2 : ' style='color:$color;'></i><a/></td><td colspan='3' style='text-align:center;'></td>";
                            }
                            echo "</tr>";
                    }
                    else{
                    echo "<td colspan='100%' style='text-align:center;'>No Route Added</td>
                    </tr>";
                    }
                    //exit;
                }
            }
            else{
            echo "<tr>
                <td><b>$route_list->routename</b></td><td colspan='100%' style='text-align:center;'>No Vehicles Added</td>
            </tr>";
            }
            }
        }
        echo "</tbody>";
    }
    else{
        echo "<tr>
            <td colspan='100%' style='text-align:center;'>No Route</td>
        </tr>";
    }
?>
</table>

<a href="#x" class="overlay" id="route_popup"></a>
<div class="popup">
    <div id="map_route"></div>
    <div id="info" align="center"></div>
    <a class="close1" href="#close"></a>
</div>
