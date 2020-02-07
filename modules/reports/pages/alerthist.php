<script type="text/javascript">
    function getAlertHistReport() {
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#alertHistForm").serialize();
        var vehno = jQuery('#vehicleid option:selected').html();
        var typeText = jQuery('#alerttype option:selected').html();
        var chkText = jQuery('#chkid option:selected').html();
        var fncText = jQuery('#fenceid option:selected').html();
        jQuery.ajax({
            url: "alerthist_ajax.php",
            type: 'POST',
            data: data + '&vehno=' + vehno + '&typeText=' + typeText + '&chkText=' + chkText + '&fncText=' + fncText,
            success: function (result) {
                jQuery("#centerDiv").html(result);
            },
            complete: function () {
                jQuery('#pageloaddiv').hide();
            }
        });
    }
</script>
<form action="reports.php?id=12" method="POST" id="alertHistForm" onsubmit="getAlertHistReport();return false;">
<?php
if ($_SESSION['switch_to'] == 3) {
	if (isset($_SESSION['Warehouse'])) {
		$vehicle = $_SESSION['Warehouse'];
		$vehicles = $_SESSION['Warehouse'] . "s";
	} else {
		$vehicle = 'Warehouse';
		$vehicles = 'Warehouses';
	}
} else {
	$vehicle = 'Vehicle';
	$vehicles = 'Vehicles';
}

$title = 'Alert History';
include 'panels/alerthist.php';
$status = get_all_alerttype();
if ($_SESSION['switch_to'] == 3) {
	$devices = getwarehouses();
} else {
	$devices = getvehicles();
}
$checkpoints = get_all_chk();
$fences = get_all_fence();

$statusopt = "";
foreach ($status as $type) {
	if ($type->id == 16 && $_SESSION['use_door_sensor'] == 0) {
		continue;
	}
	$statusopt .= "<option value='$type->id'>" . ucfirst($type->status) . "</option>";
}

$devicesopt = "";
foreach ($devices as $device) {
	$devicesopt .= "<option value = '$device->vehicleid'>$device->vehicleno</option>";
}

$chkopt = "";
if (isset($checkpoints)) {
	foreach ($checkpoints as $checkpoint) {
		$chkopt .= "<option value='$checkpoint->checkpointid'>$checkpoint->cname</option>";
	}
}

$fenceopt = "";
if (isset($fences)) {
	foreach ($fences as $fence) {
		$fenceopt .= "<option value='$fence->fenceid'>$fence->fencename</option>";
	}
}
if (isset($_SESSION['ecodeid'])) {
	?>
        <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
        <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
        <?php
}
?>
    <tr>
        <td>Type</td>
        <td><?php echo $vehicle ?> No.</td>
        <td></td>
        <td>Date</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td>
            <select id="alerttype" name="alerttype" >
                <option value="-1">All Types</option>
                <?php echo $statusopt; ?>
            </select>
        </td>

        <td>
            <select id="vehicleid" name="vehicleid" >
                <option value="">All                                                                                                                                                 <?php echo $vehicles ?></option>
                <?php echo $devicesopt; ?>
            </select>
        </td>
        <td>
            <select id="chkid" name="chkid" style="display:none;">
                <option value="">All Checkpoints</option>
                <?php echo $chkopt ?>
            </select>
            <select id="fenceid" name="fenceid" style="display:none;">
                <option value="">All Fences</option>
                <?php echo $fenceopt; ?>
            </select>
        </td>
        <td>
            <input id="SDate" name="SDate" type="text" value="<?php echo date('d-m-Y'); ?>" required/>
        </td>
        <td><input type="submit" data="g-button g-button-submit" class="btn  btn-primary" value="Get Report" name="submit"></td>
        <td>
            <a href='javascript:void(0)' onclick="get_pdfreportAlerthist(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['switch_to']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlalerthist(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['switch_to']; ?>);
                    return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a href='javascript:void(0)' onclick="standardized_print('<?php echo $title; ?>');"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
        </td>
    </tr>
    <tr></tr>
</tbody>
</table>
</form>
<br/><br/>
<center id='centerDiv'></center>
