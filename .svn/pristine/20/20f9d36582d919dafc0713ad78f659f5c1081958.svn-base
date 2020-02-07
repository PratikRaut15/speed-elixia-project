<tbody>
<?php
$lastvehicle=Array();
$display;
if($ReportType == 'Genset'){
$vehicles = GetGensetVehicles_SQLite();
}
else{
$vehicles = GetVehicles_SQLite();
}

if(isset($reports))
{
    $firstdate = $SDate;
    foreach($reports as $report)
    {
        $getin = true;        
        if(isset($lastvehicle))
        {
            foreach($lastvehicle as $thisvehicle)
            {
                if($thisvehicle==$report->vehicleid)
                {
                    $getin = false;
                }
            }
        }
        if($vehicles[$report->vehicleid]['vehicleno'] == NULL){
            //echo 'Yess'.$report->vehicleid;
                    $getin = false;
        }
        if($getin == true)
        {
            $total = 0;
            $CompareDate = strtotime($firstdate);
            $SDatetemp = $firstdate;                            
            echo '<tr>';
            $id = $report->vehicleid;
            echo '<td>'.$vehicles[$id]['vehicleno'].'</td>';
            
            foreach($reports as $variablerep)
            {
               
                if($report->vehicleid == $variablerep->vehicleid)
                {
                    while($CompareDate<=$variablerep->date)
                    {
                        if($CompareDate<$variablerep->date && $CompareDate!=$variablerep->date)
                        {
                            echo "<td>N/A</td>";
                        }
                        else if($CompareDate==$variablerep->date)
                        {
                            switch ($ReportType)
                            {
                                case 'Mileage':
                                    $variablerep->totaldistancetravelled = $variablerep->totaldistance/1000;
                                    echo "<td>$variablerep->totaldistancetravelled</td>";
                                    $total += $variablerep->totaldistancetravelled;
                                    break;
                                case 'IdleTime':
                                    if($variablerep->idletime!=0)
                                        echo "<td>".m2h($variablerep->idletime)."</td>";
                                    else
                                    {
                                        $variablerep->idletime = 1440;
                                        echo "<td>".m2h($variablerep->idletime)."</td>";
                                    }
                                    $total += $variablerep->idletime;
                                    break;
                                case 'Genset':
                                    if($variablerep->genset!=0)
                                        echo "<td>".m2h($variablerep->genset)."</td>";
                                    else
                                    {
                                        $variablerep->genset = 0;
                                        echo "<td>".m2h($variablerep->genset)."</td>";
                                    }
                                    $total += $variablerep->genset;
                                    break;
                                case 'Overspeed':
                                    echo "<td>$variablerep->overspeed</td>";
                                    $total += $variablerep->overspeed;
                                    break;
                                case 'FenceConflict':
                                    echo "<td>$variablerep->fenceconflict</td>";
                                    $total += $variablerep->fenceconflict;
                                    break;
                                 case 'Location':
                                    echo "<td>$variablerep->location</td>";
                                   // $total += $variablerep->fenceconflict;
                                    break;
                                case 'Fuel':
                                     $variablerep->totaldistancetravelled = $variablerep->totaldistance/1000;
                                    $variablerep->consumedfuel = $variablerep->totaldistancetravelled / $variablerep->average ;
                                    echo "<td>".  round($variablerep->consumedfuel,2)."</td>";
                                     $total += $variablerep->consumedfuel;
                                default:
                                    break;
                            }
                        }
                        $SDatetemp = date("Y-m-d",strtotime($SDatetemp." + 1 day"));
                        $CompareDate = strtotime($SDatetemp);
                    }
                }
            }
            while($CompareDate<=strtotime($EDdate))
            {
                echo '<td>N/A</td>';
                $SDatetemp = date("Y-m-d",strtotime($SDatetemp." + 1 day"));
                $CompareDate = strtotime($SDatetemp);
            }
            if($ReportType=='IdleTime' || $ReportType=='Genset')
            {
                echo "<td><b>".m2h($total)."</b></td>";
            }
            else if($ReportType=='Mileage')
            {
                echo "<td><b>$total</b></td>";
            }
            else if($ReportType=='Fuel')
            {
                echo "<td><b>".  round($total,2)."</b></td>";
            }
            else if($ReportType=='Location')
            {
               //echo "<td><b>$location</b></td>";
            }
            else
                echo "<td><b>$total</b></td>";
            echo "</tr>";
        }
        $lastvehicle[] = $report->vehicleid;        
    }
}

?>
</tbody>
</table>
</div>