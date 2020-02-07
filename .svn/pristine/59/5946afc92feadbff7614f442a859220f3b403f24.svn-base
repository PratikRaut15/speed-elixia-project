<?php
$arrCheckpoints = get_all_checkpoint();
$checkpointList = "";
if (isset($arrCheckpoints)) {
    foreach ($arrCheckpoints as $check) {
        if (isset($_POST['checkpoint_start']) && $check->checkpointid == $_POST['checkpoint_start'] && $chktype == '1') {
            $checkpointList .= "<option selected='selected' value='$check->checkpointid'>$check->cname</option>";
        } else {
            $checkpointList .= "<option value='$check->checkpointid'>$check->cname</option>";
        }
    }
}
$title = 'Vehicle In Out Report';
$today = date('d-m-Y');
?>
<br/>
<form action="reports.php?id=62" method="POST" id='frmVehicleInOut' onsubmit="getChkVehicleInOutRepot();return false;">
    <?php

    include 'panels/vehicleInOut.php';
    if (isset($_SESSION['ecodeid'])) {
        ?>
        <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
        <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
        <input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days']; ?>" />
        <input type="hidden" name="calculateddate" id="calculateddate" value="<?php echo date('d-m-Y', strtotime($_SESSION['codecalculateddate'])); ?>" />
        <?php
    }
    ?>
    <tr>
        <td>Checkpoint</td>
        <td>Start Date</td>
        <td>Start Hour</td>
        <td>End Date</td>
        <td>End Hour</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td>
            <select id="chkptId" name="chkptId" style="width: 250px;" required>
                <option value=''>Select Start Checkpoint</option>
                <?php echo $checkpointList; ?>
            </select>
        </td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00" /></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="ETime" name="ETime" type="text" class="input-mini" data-date2="23:59"/></td>
        <td><input id="report" name="report" type="hidden" value="vehicleInOut" /></td>
        <td><input type="submit" value="Get Report" class="g-button g-button-submit" name="GetReport"></td>
        <td>
            <a href='javascript:void(0)' onclick="export_VehicleInOutReport(<?php echo $_SESSION['customerno']; ?>, '<?php echo speedConstants::REPORT_PDF; ?>');"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="export_VehicleInOutReport(<?php echo $_SESSION['customerno']; ?>, '<?php echo speedConstants::REPORT_XLS; ?>');return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a>

        </td>
    </tr>
</tbody>
</table>
</form>
<br/><br/>
<center id='centerDiv'></center>
<script>

    function getChkVehicleInOutRepot() {
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#frmVehicleInOut").serialize();
        jQuery.ajax({
            url: "chkhist_ajax.php",
            type: 'POST',
            data: data,
            success: function (result) {
                jQuery("#centerDiv").html(result);
            },
            complete: function () {
                jQuery('#pageloaddiv').hide();
            }
        });
    }

</script>
