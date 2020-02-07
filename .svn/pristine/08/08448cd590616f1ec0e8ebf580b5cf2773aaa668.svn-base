<form action="reports.php?id=5" method="POST">
<?php
include 'panels/vehiclehist.php';
if(!isset($_POST['STdate'])) { $StartDate = getdate_IST();} else { $StartDate = strtotime ($_POST['STdate']);}
if(!isset($_POST['EDdate'])) { $EndDate = $StartDate; } else { $EndDate = strtotime ($_POST['EDdate']);}        
if(!isset($_POST['STime'])) { $stime = "00:00"; } else { $stime = $_POST['STime']; }
if(!isset($_POST['ETime'])) { $etime = "23:59"; } else { $etime = $_POST['ETime']; }
$devices = getvehicles();
$selectedvehicle = "";
foreach ($devices as $device) 
{
    if(isset($_POST['deviceid']) && $device->vehicleid == $_POST['deviceid'])
    {
        $selectedvehicle .= "<option value = '$device->vehicleid' selected = 'selected'>$device->vehicleno</option>";
    }
    else
    {
        $selectedvehicle .= "<option value = '$device->vehicleid'>$device->vehicleno</option>";
    }
}

$houropt = "";
if($_POST['report'] == 'Temperature')
    {
        for($i=0;$i<=23;$i++)
        {
            if($i<10)
                $houropt .= "<option value ='0$i:00:00'>0$i</option>";
            else
                $houropt .= "<option value = '$i:00:00'> $i</option>";
        }
    }
else {
        for($i=0;$i<=23;$i++)
        {
            if($i<10)
                $houropt .= "<option value ='0$i:00:00'>0$i</option>";
            else
                $houropt .= "<option value = '$i:00:00'> $i</option>";
        }
    }

$select = "";
if(isset($_POST['report']))
{
    switch ($_POST['report'])
    {
          case 'Mileage':
              $select .= '<option value="Mileage" selected="selected">Distance Report</option>';
              $select .= '<option value="Utilization">Utilization Report</option>';
              $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
              $select .= '<option value="FenceConflict">FenceConflict Report</option>';
              $select .= '<option value="Speed">Avg Speed & Distance Travelled Report</option>';
              $select .= '<option value="TemperatureDaily">Temperature Report</option>';
              $select .= '<option value="Temperature">Temperature Hourly Report</option>';
              break;
          case 'Utilization':
              $select .= '<option value="Mileage">Distance Report</option>';
              $select .= '<option value="Utilization" selected="selected">Utilization Report</option>';
              $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
              $select .= '<option value="FenceConflict">FenceConflict Report</option>';
              $select .= '<option value="Speed">Avg Speed & Distance Travelled Report</option>';
              $select .= '<option value="TemperatureDaily">Temperature Report</option>';
              $select .= '<option value="Temperature">Temperature Hourly Report</option>';
              break;
          case 'Overspeed':
              $select .= '<option value="Mileage">Distance Report</option>';
              $select .= '<option value="Utilization">Utilization Report</option>';
              $select .= '<option value="Overspeed" selected="selected">Overspeeding Incident Report</option>';
              $select .= '<option value="FenceConflict">FenceConflict Report</option>';
              $select .= '<option value="Speed">Avg Speed & Distance Travelled Report</option>';
              $select .= '<option value="TemperatureDaily">Temperature Report</option>';
              $select .= '<option value="Temperature">Temperature Hourly Report</option>';
              break;
          case 'FenceConflict':
              $select .= '<option value="Mileage">Distance Report</option>';
              $select .= '<option value="Utilization">Utilization Report</option>';
              $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
              $select .= '<option value="FenceConflict" selected="selected">FenceConflict Report</option>';
              $select .= '<option value="Speed">Avg Speed & Distance Travelled Report</option>';
              $select .= '<option value="TemperatureDaily">Temperature Report</option>';
              $select .= '<option value="Temperature">Temperature Hourly Report</option>';
              break;
          case 'Speed':
              $select .= '<option value="Mileage">Distance Report</option>';
              $select .= '<option value="Utilization">Utilization Report</option>';
              $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
              $select .= '<option value="FenceConflict">FenceConflict Report</option>';
              $select .= '<option value="Speed" selected="selected">Avg Speed & Distance Travelled Report</option>';
              $select .= '<option value="TemperatureDaily">Temperature Report</option>';
              $select .= '<option value="Temperature">Temperature Hourly Report</option>';
              break;
          case 'Temperature':
              $select .= '<option value="Mileage">Distance Report</option>';
              $select .= '<option value="Utilization">Utilization Report</option>';
              $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
              $select .= '<option value="FenceConflict">FenceConflict Report</option>';
              $select .= '<option value="Speed">Avg Speed & Distance Travelled Report</option>';
              $select .= '<option value="TemperatureDaily">Temperature Report</option>';
              $select .= '<option value="Temperature" selected="selected">Temperature Hourly Report</option>';
              break;
          case 'TemperatureDaily':
              $select .= '<option value="Mileage">Distance Report</option>';
              $select .= '<option value="Utilization">Utilization Report</option>';
              $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
              $select .= '<option value="FenceConflict">FenceConflict Report</option>';
              $select .= '<option value="Speed">Avg Speed & Distance Travelled Report</option>';
              $select .= '<option value="TemperatureDaily" selected="selected">Temperature Report</option>';
              $select .= '<option value="Temperature">Temperature Hourly Report</option>';
              break;
      }
}
else
{
    $select .= '<option value="Mileage">Distance Report</option>';
    $select .= '<option value="Utilization">Utilization Report</option>';
    $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
    $select .= '<option value="FenceConflict">FenceConflict Report</option>';
    $select .= '<option value="Speed">Avg Speed & Distance Travelled Report</option>';
    $select .= '<option value="TemperatureDaily">Temperature Report</option>';
    $select .= '<option value="Temperature">Temperature Hourly Report</option>';
}
  
?>
    <tr>
        <td>
            <select id="deviceid" name="deviceid" required>
            <option value=''>Select Vehicle</option>
            <option value = 'All'>All Vehicles</option>
            <?php echo $selectedvehicle;?>
            </select>
        </td>
        <td>Start Date</td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo date('d-m-Y',$StartDate);?>" required/>
        </td>
<!--        <td class="time">Start Hour
        <input id="STime" name="STime" type="text" class="input-mini" data-date="<?php //echo $stime;?>" />-->
        </td>
        <?php 
        if($_POST['report'] == 'Temperature')
        {
        ?>
        <td class="tr" colspan="4">Start Hour
        <select name='Shour' id='Shour'>
        <?php echo $houropt;?>
    </select>
    </td>
    <?php }
    else {?>
        <td class="tr" colspan="4" style="display: none;">Start Hour
        <select name='Shour' id='Shour'>
        <?php echo $houropt;?>
    </select>
    </td>
    <?php } 
    if($_POST['report'] == 'Temperature' || $_POST['report'] == 'TemperatureDaily')
        {
    ?>
        <td style="display: none;" class="td">End Date</td>
        <td style="display: none;" class="td"><input id="EDdate" name="EDdate" type="text" value="<?php echo date('d-m-Y',$EndDate);?>" required/>
        </td>
        <td style="display: none;" class="td"><button id="EDate" class="g-button g-button-submit">...</button></td>
    <?php } 
    else{
    ?>
        <td class="td">End Date</td>
        <td class="td"><input id="EDate" name="EDdate" type="text" value="<?php echo date('d-m-Y',$EndDate);?>" required/>
        </td>
<!--        <td class="time">End Hour
            <input id="ETime" name="ETime" type="text" class="input-mini" data-date2="<?php //echo $etime;?>"/>-->
        </td>     
        <?php } ?>
        <td><select id="report" name="report"><?php echo $select;?></select></td>
        <?php //if($_POST['overspeed_limit'] != ''){ ?>
<!--        <td id="speed_limit">
            <?php //} else{ ?>
        <td id="speed_limit" style="display: none;"><?php //} ?>Overspeed Limit
        <select name='overspeed_limit' id='overspeed_limit'>
            <option value="">select speed limit</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
            <option value="60">60</option>
            <option value="70">70</option>
            <option value="80">80</option>
            <option value="90">90</option>
            <option value="100">100</option>
            <option value="110">110</option>
            <option value="120">120</option>
            <option value="130">130</option>
            <option value="140">140</option>
        </select>
        </td>-->
        <td><input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
    </tr>
    <tr id="Temperature" class="tr" style="display: none;"></tr>
</tbody>
</table>
</form>
<br>
<div id="graph_div">
<div id="chart_div"></div>
<div id="chart_divAll" style="height: 2000px;"></div>
<?php 
if(isset($_POST['STdate']) && isset($_POST['EDdate']))
{
    //include '../../lib/system/utilities.php';
    $STdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['STdate'], 'string')));
    $EDdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['EDdate'], 'string')));
    $STHour = GetSafeValueString($_POST['Shour'], 'string');
    $vehicleid = GetSafeValueString($_POST['deviceid'], 'string');
    $ReportType = GetSafeValueString($_POST['report'], 'string');
    
    if($vehicleid == 'All' && $ReportType != 'Mileage' ){
       echo "<script>
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000)</script>"; 
    }
    
    if(strtotime($STdate)>strtotime($EDdate))
    {
        echo "<script>
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000)</script>";
    }
    else if($_POST['deviceid']=='Select Vehicle')
    {
        echo "<script>
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000)</script>";
    }
    else if($ReportType == 'Temperature')
    {
            $reports = gethourlyreportfortemp($STdate, $vehicleid,$STHour);
            if(isset($reports))
            {
                include 'vehicletempdata.php';
                if(isset($vehiclereps))
                {
                    include 'vehicletempchart.php';
                    if(isset($ReportType))
                        {
                            echo '<br><br><div align="center"><b>';
                            
                            echo '</b></div>';
                        }
                }
            }
            else
                echo "<script type='text/javascript'>
                        jQuery('#error').show();
                        jQuery('#error').fadeOut(3000);
                    </script>";

    }
    else if($ReportType == 'TemperatureDaily')
    {
            $reports = getdailyreportfortemp($STdate,$STdate, $vehicleid);
            if(isset($reports))
            {
                include 'vehicletempdata.php';
                if(isset($vehiclereps))
                {
                    include 'vehicletempchart.php';
                    if(isset($ReportType))
                        {
                            echo '<br><br><div align="center"><b>';
                            
                            echo '</b></div>';
                        }
                }
            }
            else
                echo "<script type='text/javascript'>
                        jQuery('#error').show();jQuery('#error').fadeOut(3000);
                    </script>";

    }
    else if($vehicleid=='All' && $ReportType == 'Mileage'){
        $reports = getdailyreport_All($STdate, $EDdate);
        
        //echo "<pre>".print_r($reports)."</pre>";
        
        if(isset($reports))
        {
            include 'vehiclerepdataall.php';
            if(isset($vehiclereps))
            {
                include 'vehiclerepchartall.php';
                
            }
    }
    else
            echo "<script type='text/javascript'>
                    jQuery('#error').show();jQuery('#error').fadeOut(3000);
                </script>";
    }
    else
    {
        $reports = getdailyreport($STdate, $EDdate, $vehicleid);
        if(isset($reports))
        {
            include 'vehiclerepdata.php';
            if(isset($vehiclereps))
            {
                include 'vehiclerepchart.php';
                if(isset($total))
                {
                    if(isset($ReportType))
                    {
                        echo '<br><br><div align="center"><b>';
                        if($ReportType=='Mileage')
                        {
                            echo "Total Distance = ".round($total/1000) ."KM</br>";
                            if(isset($counter) && $counter !=0)
                            {
                                echo "Avg = ".round(($total/1000)/($counter)) ."KM [".($counter) ."days]";
                            }
                        }
                        else if($ReportType=='Overspeed' || $ReportType=='FenceConflict')
                        {
                            echo "Total = $total Incidents<br>";
                            if(isset($counter) && $counter !=0)
                            {
                                echo "Avg = ".round($total/($counter))." Incidents";
                            }
                        }
                        echo '</b></div>';
                    }
                }
            }
        }
        else
            echo "<script type='text/javascript'>
                    jQuery('#error').show();jQuery('#error').fadeOut(3000);
                </script>";
    }
}
?>
</div>
<?php 
    if(isset($_POST['STdate']) && isset($_POST['EDdate'])){
?>
<input type="button" value="Print Graph" onclick="PrintElem('#graph_div')" />
<?php } ?>