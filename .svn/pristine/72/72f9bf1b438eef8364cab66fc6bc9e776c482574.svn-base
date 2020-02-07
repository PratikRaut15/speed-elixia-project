
<?php

    if(isset($reports)){
       $date = date("Y-m-d", strtotime($_POST['STdate']));
       //print_r($d);
       //print("<pre>"); print_r($reports);die;
        foreach($reports[0]['result'] as $report){
            $i=1;
            $lastupdated = convertDateToFormat($report->lastupdated);
            $forDate = date("Y-m-d", strtotime($lastupdated));
            ?>
            <tr>

            <td><?php echo $lastupdated; ?></td>

            <?php
            $datarow = "";
            $datarow1 = "";
            $datarow2 = "";
            $datarow3 = "";
            $datarow4 = "";

            $datarow1Input = "";
            $datarow2Input = "";
            $datarow3Input = "";
            $datarow4Input = "";
            switch ($_SESSION['temp_sensors']) {
                case 4:
                    if($reports[0]['tempsen']['tempsen4'] != 0){
                        $analog4 = 'temp'.$reports[0]['tempsen']['tempsen4'];
                        $analogid = "analog".$reports[0]['tempsen']['tempsen4'];

                    /*$datarow4 .="<td contenteditable='true' onBlur=\"saveToDatabase(this,'$analogid', '$report->uhid' ,'$report->date' ,'$report->vehicleid','$report->uid')\">". $report->$analog4 ."</td>";*/
                    $datarow4 .="<td>". $report->$analog4 ."</td>";
                    $datarow4Input.= "<td><input type='text' class='input-mini'></td>";
                }else{
                    $datarow4.= "<td></td>";
                    $datarow4Input.= "<td></td>";
                }
                    
                case 3:
                if($reports[0]['tempsen']['tempsen3'] != 0){
                $analog3 = 'temp'.$reports[0]['tempsen']['tempsen3'];
                $analogid = "analog".$reports[0]['tempsen']['tempsen3'];
              // $datarow3 .= "<td  class =". $analog3 .">".$report->$analog3."</td>";
               /*$datarow3 .="<td contenteditable='true' onBlur=\"saveToDatabase(this,'$analogid', '$report->uhid' ,'$report->date' ,'$report->vehicleid','$report->uid')\">". $report->$analog3 ."</td>";*/

               $datarow3 .="<td>". $report->$analog3 ."</td>";
               $datarow3Input.= "<td><input type='text'></td>";
                  }else{
                    $datarow3 .= "<td></td>";
                    $datarow3Input.= "<td></td>";
                  }  
                case 2:
                if($reports[0]['tempsen']['tempsen2'] != 0){
                   //echo $reports['tempsen']['tempsen2']; die;
                $analog2 = 'temp'.$reports[0]['tempsen']['tempsen2'];
                $analogid = "analog".$reports[0]['tempsen']['tempsen2'];
                // result look like for $dataForpost-  analog1_912_2018-03-07_10171_8722
                $dataForPost = $analogid."_".$report->uhid."_".$forDate."_".$report->vehicleid."_".$report->uid;
                //echo "data for post - ".$dataForPost; die;

                /*$datarow2 .="<td contenteditable='true' onBlur=\"saveToDatabase(this,'$analogid', '$report->uhid' ,'$report->date' ,'$report->vehicleid','$report->uid')\">". $report->$analog2 ."</td>";*/
                $datarow2 .="<td>". $report->$analog2 ."</td>";
                $datarow2Input.= "<td>
                                <input type='text' class='input-mini' name='".$analogid."[]'>
                                <input type='hidden' name='h".$analogid."[]' value='$dataForPost'>
                                </td>";

                }else{
                    $datarow2 .= "<td></td>";
                    $datarow2Input.= "<td></td>";
                  }

                case 1:
                if($reports[0]['tempsen']['tempsen1'] != 0){
                $analog1 = 'temp'.$reports[0]['tempsen']['tempsen1'];
                $analogid = "analog".$reports[0]['tempsen']['tempsen1'];
                // result look like for $dataForpost-  analog1_912_2018-03-07_10171_8722
                $dataForPost = $analogid."_".$report->uhid."_".$forDate."_".$report->vehicleid."_".$report->uid;
                /*$datarow1 .="<td contenteditable='true' onBlur=\"saveToDatabase(this,'$analogid', '$report->uhid' ,'$report->date' ,'$report->vehicleid','$report->uid')\">". $report->$analog1 ."</td>";*/
                
                $datarow1 .="<td>". $report->$analog1 ."</td>";
                $datarow1Input.= "<td>
                                    <input type='text' class='input-mini' name='".$analogid."[]'>
                                    <input type='hidden' name='h".$analogid."[]' value='$dataForPost'>
                                  </td>";

                }else{
                    $datarow1 .= "<td></td>";
                    $datarow1Input.= "<td></td>";
                  }
                    break;
                }

                $datarow = $datarow1.$datarow1Input.$datarow2.$datarow2Input.$datarow3.$datarow3Input.$datarow4.$datarow4Input;
                echo $datarow;
            ?>



<!-- -->
           <!--  <td contenteditable="true" onBlur="saveToDatabase(this,'analog1','<?php echo $report->uhid; ?>' ,'<?php echo $report->date; ?>' ,'<?php echo $report->vehicleid; ?>','<?php echo $report->uid; ?>')"><?php echo $report->temp1; ?></td>
           
           <td contenteditable="true" onBlur="saveToDatabase(this,'analog2','<?php echo $report->uhid; ?>' ,'<?php echo $report->date; ?>' ,'<?php echo $report->vehicleid; ?>','<?php echo $report->uid; ?>')"><?php echo $report->temp2; ?></td>
           
           <td contenteditable="true" onBlur="saveToDatabase(this,'analog3','<?php echo $report->uhid; ?>' ,'<?php echo $report->date; ?>' ,'<?php echo $report->vehicleid; ?>','<?php echo $report->uid; ?>')"><?php echo $report->temp3; ?></td>
           
           <td contenteditable="true" onBlur="saveToDatabase(this,'analog4','<?php echo $report->uhid; ?>' ,'<?php echo $report->date; ?>' ,'<?php echo $report->vehicleid; ?>','<?php echo $report->uid; ?>')"><?php echo $report->temp4; ?></td> -->

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
<input type="submit" name="update" value="Update">
</form>
