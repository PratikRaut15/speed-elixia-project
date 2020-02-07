<?php include 'route_dashboard_functions.php'; ?>
<div class="entry">
<center>

    <div id="rec" style="margin-top: 50px;">
     <table id="search_table">
    <thead class="fixedHeader">
    <tr>
        <th filter="false">SNo</th>
	<th filter="false"></th>
        <th filter="false">Vehicle No</th>
        <th filter="false">Route</th>

        <th filter="false">Start Location</th>
        <th filter="false">End Location</th>
        <th filter="false">Start Time</th>
        <th filter="false">Std ETA</th>
        <th filter="false">Est ETA</th>
        <th filter="false">ETA Satus</th>
        <th filter="false">Last Updated</th>
        <th filter="false">Current location</th>
        <th filter="false">Dist Covered(Km)</th>
        <th filter="false">Route Distance(Km)</th>
        <th filter="false">Checkpoint Crossed</th>
        <th filter="false">Time Of Crossed</th>
    </tr>
    </thead>
    <tbody class="scrollContent" >
    <?php
    $results = getvehicles_withroute();
    if(isset($results))
    {
        $i = 1;
        foreach($results as $result)
        {
            $start =  getStartLocation($result->routeid);
            $end =  getEndLocation($result->routeid);
            $starttime = getStartTime($result->vehicleid,$start->checkpointid);
            $stdtime = getStdTime($result->routeid);
            $lastchk = getLastCrossed($result->routeid,$result->vehicleid,$starttime->date);
            $distance = getRouteDistance($result->routeid);
            if($starttime->date !='' && $start->timetaken !=''){
          ?>
            <tr>
            <td><input type='hidden' id='latlong<?php echo $result->vehicleid;?>' value='<?php echo $result->devicelat.','.$result->devicelong;?>'/><a><?php echo $i++;?></a></td>
            <td><?php getimage($result->lastupdated_store, $result->stoppage_flag,$result->ignition,$result->type,$result->curspeed,$result->overspeed_limit,$result->stoppage_transit_time,$result->igstatus); ?></td>
            <td><?php echo $result->vehicleno;?></td>
            <td><?php echo $result->routename;?></td>

            <td><?php echo $start->cname;?></td>
            <td><?php echo $end->cname;?></td>
             <td><?php if($starttime->date != ''){ echo convertDateToFormat($starttime->date,speedConstants::DEFAULT_DATETIME); } ?></td>
            <td>
                <?php
                if($starttime->date !='')
                    {
                    echo $stdeta= convertDateToFormat($startTime->date."+ ".$stdtime.' minutes',speedConstants::DEFAULT_DATETIME);
                    }
                   ?>
            </td>
            <td>
              <?php
                $eta = convertDateToFormat($starttime->date.'+'.$lastchk->eta.' minutes',speedConstants::DEFAULT_DATETIME) ;
                if($lastchk->flag != 0)
                {
                    echo convertDateToFormat($lastchk->date,speedConstants::DEFAULT_DATETIME);
                }
                else if($lastchk->date > $eta && $lastchk->flag == 0)
                {

                   $difftime =  round((strtotime($lastchk->date)- strtotime($eta)) / 60) + $stdtime;
                       echo convertDateToFormat($starttime->date.'+'.$difftime.' minutes',speedConstants::DEFAULT_DATETIME);
               }
               else if( $lastchk->date < $eta && $lastchk->flag == 0 )
               {
                  $difftime =  round((strtotime($eta)- strtotime($lastchk->date)) / 60) - $stdtime;
                  echo convertDateToFormat($starttime->date.'-'.$difftime.' minutes',speedConstants::DEFAULT_DATETIME) ;
               }

             ?>
            </td>
            <td><?php
                $eta = convertDateToFormat($starttime->date.'+'.$lastchk->eta.' minutes',speedConstants::DEFAULT_DATETIME) ;

                if($lastchk->flag == 1){
                    echo "Completed"; echo"<br/>";
                    if(strtotime($lastchk->date) > strtotime($stdeta))
                    {
                         $difftime = floor((strtotime($lastchk->date)- strtotime($stdeta)) / 60) ;
                         if($difftime != 0)
                         echo "Delayed By ".$difftime. " mins";
                    }else{
                         $difftime =  floor((strtotime($stdeta)- strtotime($lastchk->date)) / 60);
                         if($difftime != 0)
                         echo "Early By ".$difftime. " mins";
                    }
                }
                else if($lastchk->chkid == $start->checkpointid)
                {
                    echo "On Time";
                }
                else if(strtotime($lastchk->date) > strtotime($eta))
                {
                     $difftime =  floor((strtotime($lastchk->date)- strtotime($eta)) / 60);
                     echo "Delayed By ".$difftime. " mins";
                }else{
                     $difftime =  floor((strtotime($eta)- strtotime($lastchk->date)) / 60);
                     echo "Early By ".$difftime. " mins";
                }
             ?></td>
            <td><?php echo  $diff = getduration(date('Y-m-d H:s:i', strtotime($result->lastupdated)));  ?></td>
            <td><?php echo $location = location($result->devicelat, $result->devicelong, $result->use_geolocation); ?></td>
            <td><?php echo  getDistanceCovered($result->vehicleid, $starttime->date, $lastchk->date, $result->odometer, $lastchk->chkid, $start->checkpointid, $lastchk->flag, $lastchk->crad); ?></td>

            <td><?php echo $distance; ?></td>
            <td><?php echo $lastchk->cname; ?></td>
            <td>
                <?php
                   if($lastchk->status == 0){
                        echo "Entered At </br>";
                    }
                    else{
                        echo "Left At </br>";
                    }
                    echo convertDateToFormat($lastchk->date,speedConstants::DEFAULT_DATETIME);

                ?>

            </td>
           </tr>
          <?php
        }
    }
    }
    ?>
    </tbody>
    </table>
    </div>
</center>
</div>
