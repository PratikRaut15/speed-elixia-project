
<form action="reports.php?id=50" method="POST" id='toggleSwitchForm' onsubmit="getToggleSwitchReport();
        return false;">
          <?php
          $title = 'Toggle Switch Report';
          $today = date('d-m-Y');
          include 'panels/toggleswitchreppanel.php';
          if (isset($_SESSION['ecodeid'])) {
              ?>
        <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
        <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
        <?php
    }
    ?>
    <tr>
        <td>Vehicle No.</td>
        <td>Start Date</td>
        <td>Start Hour</td>
        <td>End Date</td>
        <td>End Hour</td>
        <td colspan='1'></td>
    </tr>
    <tr>
        <td>
            <input  type="text" name="vehicleno" id="vehicleno" size="20" value="" autocomplete="off" placeholder="Enter Vehicle No" />
            <input type="hidden" name="vehicleid" id="vehicleid" size="20" value="0"/>
            <input type="hidden" name="groupid" id="groupid" value="<?php echo $_SESSION['groupid']; ?>"/>
            <div id="display" class="listvehicle"></div>
        </td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo ($_SESSION['customerno'] == '18') ? $today : ''; ?>" required/></td>
        <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00" /></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo ($_SESSION['customerno'] == '18') ? $today : ''; ?>" required/></td>
        <td><input id="ETime" name="ETime" type="text" class="input-mini" data-date2="23:59"/></td>
        <td><input type="submit" value="Get Report" class="g-button g-button-submit" name="GetReport12"></td>

        <td>
            <a href='javascript:void(0)' onclick="export_ToggleHistoryReport(<?php echo $_SESSION['customerno']; ?>,'PDF');">
                <img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" />
            </a>
            <a href='javascript:void(0)' onclick="export_ToggleHistoryReport(<?php echo $_SESSION['customerno']; ?>,'XLS');return false;">
                <img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" />
            </a>
            <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)">
                <img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png">
            </a>
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'>
                <img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" />
            </a>
        </td>

    </tr>
</tbody>
</table>
</form>
<br/><br/>
<center id='centerDiv'></center>
<?php
$mail_function = "export_ToggleHistoryReport('" . $_SESSION['customerno'] . "','EMAIL');";
//$mail_function = "export_ToggleHistoryReport(" . $_SESSION['customerno'] . ",EMAIL);";
include_once "mail_pop_up.php";
?>
<script>

    function getToggleSwitchReport() {
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#toggleSwitchForm").serialize();
        jQuery.ajax({
            url: "toggleswitch_ajax.php",
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
    jQuery('document').ready(function () {

        var action = GetParameterValues('action');
        if (action == 'getToggle') {
            getToggleSwitchReport();
        }

        /* Auto dropdown for vehicle no */
        jQuery("#vehicleno").autocomplete({
            source: "autocomplete.php?action=dummyData",
            minLength: 1,
            select: function (event, ui) {
                jQuery('#vehicleid').val(ui.item.vehicleid);
            }
        });

    });

    function getRouteHistReport(userkey, vehicleid, edate, etime, sdate, stime, deviceid, vehicleno) {
        window.open('../reports/reports.php?userkey=' + userkey + '&vehicleid=' + vehicleid + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&etime=' + etime + '&deviceid=' + deviceid + '&vehicleno=' + vehicleno, '_blank');
    }

</script>