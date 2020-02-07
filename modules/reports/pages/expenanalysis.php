<script type="text/javascript">
function getDistanceReport(){
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#distancereportForm").serialize();
    jQuery.ajax({
        url:"distancereport_ajax.php",
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
<form action="reports.php?id=23" method="POST" id="distancereportForm" onsubmit="getDistanceReport();return false;">
<?php
$title = "Expense Analysis Report";
$date = date('d-m-Y');
include 'panels/expensereport.php';
if(isset($_SESSION['ecodeid'])){
?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
<?php
}
?>
    <tr>
        <td>Driver</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td></td>
    </tr>
    <tr>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $date; ?>" required/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $date; ?>" required/></td>
        <!--  <td>
            <select id="report" name="report">
                <option value="Table">Tabular Report</option>
                <option value="Graph">Graphical Report</option>
            </select>
        </td>-->
        <td>
            <input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport">
            <!--<a href='javascript:void(0)' onclick="get_pdfDistanceReport(<?php  echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['use_maintenance'];?>,<?php echo $_SESSION['use_hierarchy'];?>,<?php echo $_SESSION['groupid'];?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlsDistanceReport(<?php  echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['use_maintenance'];?>,<?php echo $_SESSION['use_hierarchy'];?>,<?php echo $_SESSION['groupid'];?>); return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a>-->
        </td>
    </tr>
</tbody>
</table>
</form>
<br/><br/>
<center id='centerDiv'></center>

