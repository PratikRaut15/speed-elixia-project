

<form action="reports.php?id=6" method="POST" id='chkHistForm' onsubmit="getchkHist();return false;">
    <?php
$title = 'Checkpoint Report';
$today = date('d-m-Y');
include 'panels/chkhist.php';
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
        <td>Vehicle No.</td>
        <td>Start Date</td>
        <td>Start Hour</td>
        <td>End Date</td>
        <td>End Hour</td>
        <td>Report Type</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td>
            <input  type="text" name="vehicleno" id="vehicleno" size="20" value="" autocomplete="off" placeholder="Enter Vehicle No" required>
            <input type="hidden" name="vehicleid" id="vehicleid" size="20" value=""/>
            <div id="display" class="listvehicle"></div>
        </td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00" /></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="ETime" name="ETime" type="text" class="input-mini" data-date2="23:59"/></td>
        <td>
            <select id="routetype" name="routetype" style="width:120px;" onchange="getSpecificCheckpoint();">
                <option value="0">All Allotted</option>
                <option value="1">Route Specific</option>
                <option value="2">Checkpoint Specific</option>
                <option value="3">Type Specific</option>
            </select>
        </td>
        <td id="chkSpecific" style="display: none;">
            <select id="chkId" name="chkId">
                <option value="0">Select Checkpoint</option>
            </select>
        </td>
        <td id="typeSpecific" style="display: none;">
            <select id="chktype" name="chktype">
                <option value="0">Select Checkpoint Type</option>
            </select>
        </td>
        <td><input type="submit" value="Get Report" class="g-button g-button-submit" name="GetReport"></td>
        <td>
            <a href='javascript:void(0)' onclick="export_CheckpointReport(<?php echo $_SESSION['customerno']; ?>, '<?php echo speedConstants::REPORT_PDF; ?>');"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="export_CheckpointReport(<?php echo $_SESSION['customerno']; ?>, '<?php echo speedConstants::REPORT_XLS; ?>');return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a>
            <!--
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
            -->
        </td>
    </tr>
</tbody>
</table>
</form>
<br/><br/>
<center id='centerDiv'></center>

<script>
    $(function () {
        $("#vehicleno").autoSuggest({
            ajaxFilePath: "autocomplete.php",
            ajaxParams: "dummydata=dummyData",
            autoFill: false,
            iwidth: "auto",
            opacity: "0.9",
            ilimit: "10",
            idHolder: "id-holder",
            match: "contains"
        });
    });

    function fill(Value, strparam) {
        jQuery('#vehicleno').val(strparam);
        jQuery('#vehicleid').val(Value);
        jQuery('#display').hide();
    }
    function getchkHist() {
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#chkHistForm").serialize();
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

    function getSpecificCheckpoint() {
        var routetype = jQuery("#routetype option:selected").val();
        if (routetype == 2) {
            jQuery("#typeSpecific").hide();
            jQuery("#chkSpecific").show();
            jQuery.ajax({
                url: "autocomplete.php?action=chkPtList",
                type: 'POST',
                data: '',
                success: function (result) {
                    /* jQuery("#chkId").append(result); */
                    jQuery("#chkId").html(result);
                }
            });
        } else if (routetype == 3) {
            jQuery("#chkSpecific").hide();
            jQuery("#typeSpecific").show();
            jQuery.ajax({
                url: "autocomplete.php?action=chkTypeList",
                type: 'POST',
                data: '',
                success: function (result) {
                    /* jQuery("#chktype").append(result); */
                    jQuery("#chktype").html(result);
                }
            });
        } else {
            jQuery("#chkSpecific").hide();
            jQuery("#typeSpecific").hide();
        }
    }

    function getSpeedReport(userkey, edate, etime, sdate, stime, deviceid, vehicleno) {
                window.open('../reports/reports.php?id=14&userkey=' + userkey + '&sdate=' + sdate + '&stime=' + stime + '&edate=' + edate + '&etime=' + etime + '&deviceid=' + deviceid + '&vehicleno=' + vehicleno, '_blank');
                }
</script>
