<tbody>
<?php
/*print("<pre>");
print_r($reports); die;
*/    if(isset($reports)){
       // echo $reports['tempsen2'];
        //prettyPrint($reports);
        foreach($reports[0]['result'] as $report){
            $i=1;
            $lastupdated = convertDateToFormat($report->lastupdated);

            ?>
            <tr>
            <td><?php echo $lastupdated; ?></td>
            <?php
            $datarow = '';
            $datarow1 = '';
            $datarow2 = '';
            $datarow3 = '';
            $datarow4 = '';
            switch ($_SESSION['temp_sensors']) {
                case 4:
                    if($reports[0]['tempsen']['tempsen4'] != 0){
                        $analog4 = 'temp'.$reports[0]['tempsen']['tempsen4'];
                    $datarow4.= "<td  class =". $analog4 .">".$report->$analog4."</td>";
                }else{
                    $datarow4.= "<td></td>";
                }
                    
                case 3:
                if($reports[0]['tempsen']['tempsen3'] != 0){
                $analog3 = 'temp'.$reports[0]['tempsen']['tempsen3'];
               $datarow3 .= "<td  class =". $analog3 .">".$report->$analog3."</td>";
                  }else{
                    $datarow3 .= "<td></td>";
                  }  
                case 2:
                if($reports[0]['tempsen']['tempsen2'] != 0){
                $analog2 = 'temp'.$reports[0]['tempsen']['tempsen2'];
                    $datarow2 .= "<td  class =". $analog2 .">".$report->$analog2."</td>";
                }else{
                    $datarow2 .= "<td></td>";
                  }

                case 1:
                if($reports[0]['tempsen']['tempsen1'] != 0){
                $analog1 = 'temp'.$reports[0]['tempsen']['tempsen1'];
                    $datarow1 .= "<td class =". $analog1 .">".$report->$analog1."</td>";
                }else{
                    $datarow1 .= "<td></td>";
                  }
                    break;
                }

                $datarow = $datarow1.$datarow2.$datarow3.$datarow4;
                echo $datarow;
            ?>
            
         
            <!-- onBlur="saveToDatabase(this,'analog4','<?php echo $report->uhid; ?>' ,'<?php echo $report->date; ?>' ,'<?php echo $report->vehicleid; ?>','<?php echo $report->uid; ?>')" -->
            </tr>
        <?php
        }
    }
    else{
        echo "<tr><td colspan='100%' style='text-align:center;'>No Data Found</td></tr>";
    }
    ?>

    </tbody>
</table>
</div>
