
<form action="reports.php?id=19" method="POST">
<?php
include 'panels/fuelreport.php';
if(!isset($_POST['STdate'])) { $StartDate = getdate_IST();} else { $StartDate = strtotime ($_POST['STdate']);}
if(!isset($_POST['EDdate'])) { $EndDate = $StartDate; } else { $EndDate = strtotime ($_POST['EDdate']);}        

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
?>
    <tr>
        <td>
            <select id="deviceid" name="deviceid" required>
            <option value=''>Select Vehicle</option>
            <option value = 'All' selected ='selected'>All Vehicles</option>
            <?php echo $selectedvehicle;?>
            </select>
        </td>
        <td>Start Date</td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo date('d-m-Y',$StartDate);?>" required/>
        </td>
        
        
        <td class="td">End Date</td>
        <td class="td"><input id="EDate" name="EDdate" type="text" value="<?php echo date('d-m-Y',$EndDate);?>" required/>
        </td>
      
        
        
        <td><input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
        <input type="hidden" name="report" value="Fuel"/>
    </tr>
    <tr id="Temperature" class="tr" style="display: none;"></tr>
</tbody>
</table>
</form>
<br>
<div id="graph_div">
<div id="chart_div"></div>
<?php 
if(isset($_POST['STdate']) && isset($_POST['EDdate']))
{
    //include '../../lib/system/utilities.php';
    $STdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['STdate'], 'string')));
    $EDdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['EDdate'], 'string')));
    $vehicleid = GetSafeValueString($_POST['deviceid'], 'string');
    $datediffcheck = date_SDiff($STdate, $EDdate);
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
    else
    {
        if($vehicleid == 'All' ){
            $reports = getdailyreport($STdate, $EDdate);
        } 
        elseif ($vehicleid != 'Áll') {
            $reports = getdailyreport_byID($STdate,$EDdate,$vehicleid);
        }
        
         
        if(isset($reports))
        {
            include 'panels/fuelconsumptionrep.php';
            include 'displayfuelconsumption.php';
        }
        else
            echo "<script type='text/javascript'>
                    jQuery('#error').show();jQuery('#error').fadeOut(3000);
                </script>";
    }
}
?>
</div>
