<script>
$(function() {
    $("#vehicleno").autoSuggest({
        ajaxFilePath : "autocomplete.php",
	   ajaxParams : "dummydata=doorSensor",
	   autoFill : false,
	   iwidth : "auto",
	   opacity : "0.9",
	   ilimit : "10",
	   idHolder : "id-holder",
	   match : "contains"
        });
    var hidVehId = jQuery('#vehicleid').val();
    if (hidVehId !== undefined || hidVehId !== '') {
        getDoorSensorReport();
    }
});

function fill(Value, strparam){
    jQuery('#vehicleno').val(strparam);
    jQuery('#vehicleid').val(Value);
    jQuery('#display').hide();
}
function getDoorSensorReport(){
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#powerStatusForm").serialize();
    jQuery.ajax({
        /*url:"doorsensor_ajax.php",*/
        url:"doorSensorsReport.php",
        type: 'POST',
        data: data,
        success:function(result){
            jQuery("#centerDiv").html(result);
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
    });
}

</script>

<form action="reports.php?id=67" method="POST" onsubmit="getDoorSensorReport();return false;" id="powerStatusForm">
<?php
$today = date('d-m-Y');
$title = "Door Sensor";
include 'panels/doorsensor.php';
$getvehicleid1="";
$getvehicleno = "";    
if (isset($_REQUEST['vehicleid'])) {
    $getvehicleid1 = $_REQUEST['vehicleid'];
    $vehicleDetails = getvehicle_byID($getvehicleid1);

    $getvehicleno = $vehicleDetails->vehicleno;
}

if(isset($_SESSION['ecodeid'])){
?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
<input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days'];?>" />
<input type="hidden" name="calculateddate" id="calculateddate" value="<?php echo date('d-m-Y',strtotime($_SESSION['codecalculateddate']));?>" />
<?php
}

 $SDate = "id='SDate'" ;
    $EDate = "id='EDate'" ;
    $STime = "id='STime'" ;
    $ETime = "id='ETime'" ;
    $vehicleno = "id='vehicleno'" ;
 if (isset($_SESSION['Warehouse'])) {
            $vehicle = $_SESSION['Warehouse'];
        } else {
            $vehicle = 'Warehouse';
        }
    ?>
    <tr>
	<td><?php echo $vehicle;?></td>
    <td>Door</td>
        <td>Start Date</td>
        <td>Start Hour</td>
        <td>End Date</td>
        <td>End Hour</td>
        <td></td>
    </tr>
    <tr>
        <td>
            <input  type="text" name="vehicleno" <?php echo $vehicleno; ?> size="20" value="<?php echo $getvehicleno; ?>" autocomplete="off" placeholder="Enter Vehicle No" required>
            <input type="hidden" name="vehicleid" id="vehicleid" size="20" value="<?php echo $getvehicleid1; ?>"/>
            <div id="display" class="listvehicle"></div>
        </td>
        <td>
            <select id="doorsensor" name="doorsensor">
                                             <option value="1">Door 1</option>
                                <option value="2">Door 2</option>
        </td>
        <td><input <?php echo $SDate; ?> name="SDate" type="text" value="<?php echo $today; ?>"/></td>
        <td><input <?php echo $STime; ?> name="STime" type="text" class="input-mini" data-date="00:00" /></td>
        <td><input <?php echo $EDate; ?> name="EDate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input <?php echo $ETime; ?> name="ETime" type="text" class="input-mini" data-date="23:59" /></td>
        <td>
            <?php
            if(isset($_SESSION['Session_UserRole']) && $_SESSION['Session_UserRole']=="elixir"){
            ?>
            <?php } ?>
            <input type="submit"  class="g-button g-button-submit" value="Get Report" name="GetReport">
            <a href='javascript:void(0)' onclick='getDoorSensorPDFReport("<?php echo $_SESSION['customerno']; ?>");'>
                <img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick='getDoorSensorExcelReport("<?php echo $_SESSION['customerno']; ?>");'>
                <img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
        </td>
    </tr>
    </tbody>
</table>
</form>
<br><br>
<center id='centerDiv'></center>
