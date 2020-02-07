<?php
if(isset($_POST)){

    include 'route_functions.php';

        $routeid = GetSafeValueString($_POST['routeid'],"string");
        $startdate = GetSafeValueString($_POST['STdate'],"string");
        $enddate = GetSafeValueString($_POST['EDdate'],"string");
        if($routeid ==''){
            echo '<script>jQuery("#error4").show();jQuery("#error4").fadeOut(3000);</script>';
        }
        elseif(strtotime($startdate) > strtotime($enddate)){
             echo '<script>jQuery("#error3").show();jQuery("#error3").fadeOut(3000);</script>';
        }
        else
        {
            $routeid = GetSafeValueString($routeid,"string");
            $SDdate = date('Y-m-d 00:00:00', strtotime($startdate));
            $EDdate = date('Y-m-d 23:59:00', strtotime($enddate));
            $checkpoints = getcheckpoints($routeid);
            $lastelement = end($checkpoints);
            $firstelement = array_shift(array_values($checkpoints));

            $reports = route_trip_report($SDdate, $EDdate, $firstelement->checkpointid, $lastelement->checkpointid, $routeid);

            $title = 'Route Trip Report';
            $subTitle = array("Route Id: $routeid", "Start Date: $startdate", "End Date: $enddate");
            $columns = array(
                'Sr.No', 'Vehicle No.',
                'Route Distance (km)',
                'Distance Travelled(km)',
                'Start Time',
                'End Time',
                'Actual Travel Time(HH :MM)',
                'Standard Travel Time(HH :MM)',
                'Delay Time(HH :MM)'
            );
            echo table_header($title, $subTitle, $columns, true);

            if(isset($reports))
            {
                    $i=1;
                    foreach ($reports as $thisreport)
                    {
                        if(!$thisreport){
                            continue;
                        }
                      foreach($thisreport as $report)
                      {
                          ?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $report->vehicleno;?></td>
                                <td><?php echo $report->routedistance; ?></td>
                                <td><?php echo $report->distancetravelled;?></td>
                                <td><?php echo convertDateToFormat($report->starttime,speedConstants::DEFAULT_DATETIME);?></td>
                                <td><?php echo convertDateToFormat($report->endtime,speedConstants::DEFAULT_DATETIME);?></td>
                                <td><?php echo m2h($report->actualtime);?></td>
                                <td><?php echo m2h($report->stdtime);?></td>
                                <td>
                                    <?php
                                    if($report->actualtime > $report->stdtime) {
                                        $delay = $report->actualtime - $report->stdtime;
                                        if($delay != ''){
                                           echo m2h($delay);
                                        }
                                        else{
                                            echo "N/A";
                                        }

                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php
                      }
                    }
                    ?>

                <?php
                }
                else{
                    echo "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr>";
                    echo '<script>jQuery("#error2").show();jQuery("#error2").fadeOut(3000);</script>';
                }
                echo "</tbody></table>";
            }
    }




?>
