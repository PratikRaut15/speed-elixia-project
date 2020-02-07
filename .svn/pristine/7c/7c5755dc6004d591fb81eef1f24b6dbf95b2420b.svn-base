<script type="text/javascript">
function getOSReport(){
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#OSreportForm").serialize();
    jQuery.ajax({
        url:"overspeed_report_ajax.php",
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
<form action="reports.php?id=26" method="POST" id='OSreportForm' onsubmit="getOSReport();return false;">
<?php
$today = date('d-m-Y');
include 'panels/overspeed_report.php';
if(isset($_SESSION['ecodeid'])){
?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
<input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days'];?>" />
<?php
}
?>
    <tr>
        <td>Start Date</td>
        <td>End Date</td>
        <td>Report Type</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today;?>" required/></td>
        <td>
            <select id="report" name="report">
                <option value="Table">Tabular Report</option>
                <option value="Graph">Graphical Report</option>
            </select>
        </td>
        <td><input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
        <td>
            <a href='javascript:void(0)' onclick="get_pdfOverspeed_Report(<?php  echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['use_maintenance'];?>,<?php echo $_SESSION['use_hierarchy'];?>,<?php echo $_SESSION['groupid'];?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlsOverspeed_Report(<?php  echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['use_maintenance'];?>,<?php echo $_SESSION['use_hierarchy'];?>,<?php echo $_SESSION['groupid'];?>); return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
        </td>
    </tr>
</tbody>
</table>
</form>
<br/><br/>
<center id='centerDiv'></center>