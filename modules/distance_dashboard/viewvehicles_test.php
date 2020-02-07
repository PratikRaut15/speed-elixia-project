<?php
include 'distance_dashboard_functions.php';
$cm = new CheckpointManager($_SESSION['customerno']);
?>



<?php

if(isset($_POST['GetReport']))
{
    
    $chkpt_start = $_POST['checkpoint_start'];
    $chkpt_end = $_POST['checkpoint_end'];
    
    
    
    $reports = getDistanceReport($chkpt_start, $chkpt_end);
    //print_r($report);
    $i = 1;
    
    echo json_encode($reports);exit;
    /*foreach ($reports as $report){
        
        
        ?>
                
            <script>
            var f_lt = <?php echo $report->lat;?>;
            var f_long = <?php echo $report->long;?>;
            var n_lt = <?php echo $report->start_lat;?>;
//            var n_long = <?php echo $report->start_long;?>;
            var count = <?php echo $i;?>;
            var vehicle = '<?php echo $report->vehicleno;?>';
             calculateDistances(f_lt, f_long, n_lt, n_long, count, vehicle);
            //alert(dist1);
            </script>
           <?php
            $i++;
    }*/
   

}

else if(isset($_POST['toDo'] )== 'refinearray'){
    $data1 = explode(',', $_POST['data1']);
    $data2 = explode(',', $_POST['data2']);
    $start = array();
    $end = array();
    foreach($data1 as $d)
    {
       $dt = explode('=', trim($d));
       //print_r($dt);die();
       if(preg_match('/km/', $dt[1])){
       $start[$dt[0]] = array((float)$dt[1],$dt[2],$dt[3]);
       }else{
           $dt[1] = $dt[1] / 1000;
           $start[$dt[0]] = array((float)$dt[1],$dt[2],$dt[3]);
       }
    }
    
    foreach($data2 as $d)
    {
       $dt = explode('=', trim($d));
       if(preg_match('/km/', $dt[1])){
       $end[$dt[0]] = array((float)$dt[1],$dt[2],$dt[3]);
       }else{
           $dt[1] = $dt[1] / 1000;
           $end[$dt[0]] = array((float)$dt[1],$dt[2],$dt[3]);
       }
    }
    
    asort($end);
    asort($start);
    ?>
<div style="width: 800px;">
    <div style="width: 390px; float: left;">
        <table>
            <thead>
                <tr>
                    <th colspan="100%"> Distance From <?php echo $name = $cm->getchkname($_POST['start'])  ?> </th>
                </tr>
                <tr>
                    <th style="width: 200px;">Vehicle No</th>
                    <th style="width: 135px;">Distance</th>
                    <th style="width: 135px;">Direction</th>
                    <th style="width: 200px;">Location</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $l=1;
                foreach($start as $key=>$val)
                {
                   if(($j == 10|| $j==20 || $j==30 || $j==40 ||  $j==50 || $j==60)){
                    echo "<tr>"; 
                    echo "<td>$key</td>";
                     echo "<td>".round($val[0],2)." km</td>";
                     echo "<td>".$val[1]; if($val[1]== 'Inbound'){ echo " <img class = '' src = '../../images/inbound.png' title='Inbound'>"; } else{ echo " <img class = '' src = '../../images/outbound.png' title='Outbound'>"; } echo " </td>";
                     echo "<td>".$val[2]."</td>";
                    echo "</tr>";
                    sleep (1);
                   }else{
                     echo "<tr>"; 
                    echo "<td>$key</td>";
                     echo "<td>".round($val[0],2)." km</td>";
                    echo "<td>".$val[1]; if($val[1]== 'Inbound'){ echo " <img class = '' src = '../../images/inbound.png' title='Inbound'>"; } else{ echo  " <img class = '' src = '../../images/outbound.png' title='Outbound'>"; } echo " </td>";
                     echo "<td>".$val[2]."</td>";
                    echo "</tr>";  
                   }
                    $l++;
                }
                ?>
            </tbody>
        </table>
        
    </div>
    <div style="width:390px; float:right;">
        <table>
            <thead>
                <tr>
                    <th colspan="100%"> Distance From <?php echo $name = $cm->getchkname($_POST['end'])  ?> </th>
                </tr>
                <tr>
                    <th style="width: 200px;">Vehicle No</th>
                    <th style="width: 135px;">Distance</th>
                    <th style="width: 135px;">Direction</th>
                    <th style="width: 200px;">Location</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $j = 1;
                foreach($end as $key=>$val)
                {
                   if(($j == 10|| $j==20 || $j==30 || $j==40 ||  $j==50 || $j==60)){
                    echo "<tr>"; 
                    echo "<td>$key</td>";
                     echo "<td>".round($val[0],2)." km</td>";
                     echo "<td>".$val[1]; if($val[1]== 'Inbound'){ echo " <img class = '' src = '../../images/inbound.png' title='Inbound'>"; } else{ echo " <img class = '' src = '../../images/outbound.png' title='Outbound'>"; } echo " </td>";
                   echo "<td>".$val[2]."</td>";
                    echo "</tr>";
                    sleep (1);
                   }else{
                     echo "<tr>"; 
                    echo "<td>$key</td>";
                     echo "<td>".round($val[0],2)." km</td>";
                     echo "<td>".$val[1]; if($val[1]== 'Inbound'){ echo " <img class = '' src = '../../images/inbound.png' title='Inbound'>"; } else{ echo " <img class = '' src = '../../images/outbound.png' title='Outbound'>"; } echo " </td>";
                     echo "<td>".$val[2]."</td>";
                    echo "</tr>";  
                   }
                    $j++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

    <?php
  
}
?>

