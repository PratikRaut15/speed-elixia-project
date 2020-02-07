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
        if(isset($vehicles[$report->vehicleid]['vehicleno']) == NULL){
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
                        if($CompareDate<$variablerep->date && $CompareDate!=$variablerep->date){
                            echo "<td>N/A</td>";
                        }
                        else if($CompareDate==$variablerep->date){
                            echo "<td>$variablerep->fenceconflict</td>";
                            $total += $variablerep->fenceconflict;
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
            echo "<td><b>".  $total."</b></td>";
            echo "</tr>";
        }
        $lastvehicle[] = $report->vehicleid;        
    }
}

?>
</tbody>
</table>
</div>