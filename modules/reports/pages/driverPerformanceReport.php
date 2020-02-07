<script type="text/javascript">
function getDistanceReport(){
    var driverid = jQuery("#driverid").val();
    if (driverid == '' || driverid == undefined) {
    //alert(vehicleid);
        jQuery('#error8').show();
        jQuery('#error8').fadeOut(5000);
        return false;
    }
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#distancereportForm").serialize();
    jQuery.ajax({
        url:"driverperformance_ajax.php",
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
/* Auto dropdown for Vehicle No. */
$(function () {
    jQuery("#drivername").autocomplete({
        source: "autocomplete.php?action=driverDetails",
        minLength: 1,
        select: function (event, ui) {
            jQuery('#driverid').val(ui.item.driverid);
        }
    });
});
</script>
<?php
$display_date = date('d-m-Y');
$sdate1 = date('d-m-Y');
$edate1 = date('d-m-Y');
?>
<form action="reports.php?id=59" method="POST" id="distancereportForm" onsubmit="getDistanceReport();return false;">
<?php
$title = "Driver Performance Report";
$date = date('d-m-Y');
include 'panels/driverreport.php';
if(isset($_SESSION['ecodeid'])){
    $_SESSION['groupid'] = isset($_SESSION['groupid']) ? $_SESSION['groupid']: 0;
?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
<input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days'];?>" />
<?php
}
?>
    <tr>
        <td>Driver Name</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td></td>
    </tr>
    <tr>
        <td>
            <input  type="text" name="drivername" id="drivername" size="50" value="" autocomplete="off" placeholder="Enter Driver Name" required>
            <input type="hidden" name="driverid" id="driverid" size="20" value=""/>
        </td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $date; ?>" required/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $date; ?>" required/></td>
        <td>
            <input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport">
            <a href='javascript:void(0)' onclick="get_pdfDriverPerformanceReport(<?php  echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['use_maintenance'];?>,<?php echo $_SESSION['use_hierarchy'];?>,<?php echo $_SESSION['groupid'];?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlsDriverPerformanceReport(<?php  echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['use_maintenance'];?>,<?php echo $_SESSION['use_hierarchy'];?>,<?php echo $_SESSION['groupid'];?>); return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a>
        </td>
    </tr>
</tbody>
</table>
</form>
<br/><br/>
<center id='centerDiv'></center>
