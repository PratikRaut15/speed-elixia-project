<script type="text/javascript">
function getFuelHist(){
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#FuelHistForm").serialize();
    var vehno = jQuery('#deviceid option:selected').html();
    jQuery.ajax({
        url:"fuelreport_ajax.php",
        type: 'POST',
        data: data+'&vehno='+vehno,
        success:function(result){
            jQuery("#centerDiv").html(result);
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
    });
}
</script>
<form action="reports.php?id=20" method="POST" id='FuelHistForm' onsubmit="getFuelHist();return false;">
<?php
$title = "Fuel History";
$date = date('d-m-Y');
$devices = getvehicles();
$selectedvehicle = "";
foreach ($devices as $device){
    $selectedvehicle .= "<option value = '$device->vehicleid'>$device->vehicleno</option>";
}
include 'panels/fuelreport.php';
if(isset($_SESSION['ecodeid'])){
    ?>
    <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
    <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
    <input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days'];?>" />
    <?php
}
?>
    <tr>
        <td>Vehicle No.</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td>
            <select id="deviceid" name="deviceid" required>
            <option value=''>Select Vehicle</option>
            <option value='All' selected='selected'>All Vehicles</option>
            <?php echo $selectedvehicle;?>
            </select>
        </td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $date; ?>" required/></td>
        <td class="td"><input id="EDate" name="EDdate" type="text" value="<?php echo $date; ?>" required/></td>
        <td><input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
        <td>
            <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a>
        </td>
    </tr>
</tbody>
</table>
</form>
<br/><br/>
<center id='centerDiv'></center>
