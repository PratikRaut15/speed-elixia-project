<?php
$title = 'Temperature Report';
$calculateddate = isset($_POST['calculateddate']) ? $_POST['calculateddate'] : "";
if ($calculateddate != "") {
    $sdate = $calculateddate;
}

if ($_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse'])) {
        $veh = $_SESSION['Warehouse'];
    } else {
        $veh = 'Warehouse';
    }
    $subTitle = array(
        "$veh: {$_POST['vehicleno']}",
        "Start Date: $STdate $stime",
        "End Date: $EDdate $etime",
        "Interval: $interval_p mins",
        "Temperature Limit(Max): $temp_max_limit &deg;C",
        "Temperature Limit(Min): $temp_min_limit &deg;C"
    );
} else {
    $subTitle = array(
        "Vehicle No: {$_POST['vehicleno']}",
        "Start Date: $STdate $stime",
        "End Date: $EDdate $etime",
        "Interval: $interval_p mins",
        "Temperature Limit(Max): $temp_max_limit &deg;C",
        "Temperature Limit(Min): $temp_min_limit &deg;C"
    );
}
echo table_header($title, $subTitle);
$deviceid = $_POST['deviceid'];
$unit = getunitdetails($deviceid);
if(isset($_POST['tempsel'])){
    $arr = explode("-", $_POST['tempsel']);
    $tempsel = $_POST['tempsel'];
}
else{
    $arr = explode("-", $_SESSION['temp_sensors']);
    $tempsel = $_SESSION['temp_sensors'];
}
?>


<br/><br/>

<div class="clearfix"></div>

<div class="container">
    <table id="tblTempReport" class='table newTable' >
        <thead>
            <tr>
                <th>Time</th>
                <?php if ($_SESSION['switch_to'] != 3) { ?>
                    <th>Location</th>
                <?php } ?>
                <?php

                if ($tempsel =="1-0" || $tempsel =="1") {
                        if($unit->n1 != 0){
                         $tname1 = getName_ByType($unit->n1);
                          echo "<th>".$tname1."</th>";
                    }
                    else{
                        echo "<th>Temperature 1 (&deg;C)</th>";
                    }
                }
                

                if(($tempsel == "1-all" && $arr[0] == 1 )){ 
                      if($unit->n1 != 0){
                         $tname1 = getName_ByType($unit->n1);
                          echo "<th>".$tname1."</th>";
                    }
                    else{
                        echo "<th>Temperature 1 (&deg;C)</th>";
                    }
                }
              
                if(($tempsel == "2-0" && $arr[0] == 2 &&  $arr[1]=="0")){ 
                    if($unit->n2 != 0){
                         $tname2 = getName_ByType($unit->n2);
                          echo "<th>".$tname2."</th>";
                    }
                    else{
                        echo "<th>Temperature 2 (&deg;C)</th>";
                    }
                }
                

                if(($tempsel == "2-all" && $arr[0] == 2 &&  $arr[1]=="all" ) || $tempsel =="2"){ 
                      if($unit->n1 != 0 && $unit->n2 != 0){
                         $tname1 = getName_ByType($unit->n1);
                         $tname2 = getName_ByType($unit->n2);
                          echo "<th>".$tname1."</th><th>".$tname2."</th>";
                    }
                    else{
                        echo "<th>Temperature 1 (&deg;C)</th><th>Temperature 2 (&deg;C)</th>";
                    }
                }

                if(($tempsel == "3-0" && $arr[0] == 3 &&  $arr[1]=="0" )){
                        if($unit->n1 != 0 && $unit->n2 != 0 && $unit->n3 != 0){
                         $tname3 = getName_ByType($unit->n3);
                          echo "<th>".$tname3."</th>";
                    }
                    else{
                        echo "<th>Temperature 3 (&deg;C)</th>";
                    }
                }
                if(($tempsel == "3-all" && $arr[0] == 3 &&  $arr[1]=="all" )){ 
                      if($unit->n1 != 0 && $unit->n2 != 0){
                         $tname1 = getName_ByType($unit->n1);
                         $tname2 = getName_ByType($unit->n2);
                         $tname3 = getName_ByType($unit->n3);
                          echo "<th>".$tname1."</th><th>".$tname2."</th><th>".$tname3."</th>";
                    }
                    else{
                        echo "<th>Temperature 1 (&deg;C)</th><th>Temperature 2 (&deg;C)</th><th>Temperature 3 (&deg;C)</th>";
                    }
                }
                if (($tempsel == "4-0" && $arr[0] == 4 &&  $arr[1]=="0" ) || $tempsel =="4"){
                    
                    $t4 = getName_ByType($unit->n4);
                    if ($t4 == '') {
                        $t4 = "Temperature 4";
                    }
                    echo "<th>" . $t4 . " (&deg;C)</th>";
                }

                if(($tempsel == "4-all" && $arr[0] == 4 &&  $arr[1]=="all" ) || $tempsel =="4"){ 
                      if($unit->n1 != 0 && $unit->n2 != 0){
                          $t1 = getName_ByType($unit->n1);
                    if ($t1 == '') {
                        $t1 = "Temperature 1";
                    }
                    $t2 = getName_ByType($unit->n2);
                    if ($t2 == '') {
                        $t2 = "Temperature 2";
                    }
                    $t3 = getName_ByType($unit->n3);
                    if ($t3 == '') {
                        $t3 = "Temperature 3";
                    }
                    $t4 = getName_ByType($unit->n4);
                    if ($t4 == '') {
                        $t4 = "Temperature 4";
                    }
                    echo "<th>" . $t1 . " (&deg;C)</th><th>" . $t2 . " (&deg;C)</th>";
                    echo "<th>" . $t3 . " (&deg;C)</th><th>" . $t4 . " (&deg;C)</th>";
                    }
                    else{
                        echo "<th>Temperature 1 (&deg;C)</th><th>Temperature 2 (&deg;C)</th><th>Temperature 3 (&deg;C)</th><th>Temperature 4 (&deg;C)</th>";
                    }
                }
                if ($_SESSION['use_ac_sensor'] == 1) {
                    echo "<th>AC Status</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
