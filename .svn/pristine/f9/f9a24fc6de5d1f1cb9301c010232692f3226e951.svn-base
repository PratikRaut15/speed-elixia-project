<script type="text/javascript" src="createcheck.js"></script>
<script type="text/javascript">
    function getStoppageReport(){
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#stoppagereportForm").serialize();
        jQuery.ajax({
            url:"stoppageanalysis_ajax.php",
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
<form action="reports.php?id=51" method="POST" id='stoppagereportForm' onsubmit="getStoppageReport();return false;">
<?php
$title = "Stoppage Analysis";
$today = date('d-m-Y');
include 'panels/stoppageanalysis.php';
if(isset($_SESSION['ecodeid'])){
?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
<?php
}
?>
    <tr>
        <td>Start Date</td>
        <td>Start Hour</td>
        <td>End Date</td>
        <td>End Hour</td>
        <td>Min. Hold Time</td>
    </tr>
    <tr>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00" /></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today;?>" required/></td>
        <td><input id="ETime" name="ETime" type="text" class="input-mini" data-date2="23:59" /></td>
        <td>
            <select id="interval" name="interval" required>
                <option value="5" selected>0:05</option>
                <option value="30">0:30</option>
                <option value="60">1:00</option>
                <option value="120">2:00</option>
                <option value="180">3:00</option>
                <option value="240">4:00</option>
                <option value="300">5:00</option>
            </select> hrs
        </td>
        <td>
            <input type="submit"   class="g-button g-button-submit" value="Get Report" name="GetReport"/>
            <input type="hidden" name="groupid" id="groupid" value="<?php echo $_SESSION['groupid'];?>">
        </td>
        <td>
            <a href='javascript:void(0)' onclick="export_StoppageAnalysisReport(<?php  echo $_SESSION['customerno']; ?>,<?php  echo $_SESSION['userid']; ?>, '<?php echo speedConstants::REPORT_PDF; ?>');"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="export_StoppageAnalysisReport(<?php  echo $_SESSION['customerno']; ?>,<?php  echo $_SESSION['userid']; ?>, '<?php echo speedConstants::REPORT_XLS?>');return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a href='javascript:void(0)' onclick="standardized_print('<?php  echo $title; ?>');"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
        </td>
    </tr>
    </tbody>
    </table>
</form>
<br/><br/>
<center id='centerDiv'></center>
<div>
<a href="#x" class="" id="checkpt_popup" style="top:0;"></a>
<div class="popup" style='z-index: 1100; top:25%;' >
    <div id="map_chkpoint"></div>
    <div id="info" align="center"></div>
    <a class="close1" href="#close"></a>
</div>
</div>
<?php
$mail_function = "export_SummaryReport('" . $_SESSION['customerno'] . "','" . $_SESSION['userid'] . "','".speedConstants::REPORT_EMAIL."');";

include_once "mail_pop_up.php";
?>
