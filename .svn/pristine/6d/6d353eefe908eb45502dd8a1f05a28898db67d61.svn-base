<script type="text/javascript">
    function getrouteSummary(){
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#routeSummaryForm").serialize();
        jQuery.ajax({
            url:"routeSummary_ajax.php",
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
<form action="reports.php?id=40" method="POST" id='routeSummaryForm' onsubmit="getrouteSummary();return false;">
    <input type="hidden" name='getReport' value='routeSummary'/>
<?php
$title = "Route Summary Report";
$today = date('d-m-Y');
?>
<table>
    <thead>
    <tr>
        <th id="formheader" colspan="100%"><?php echo $title; ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="100%">
            <span id="error" name="error" style="display: none;">Data Not Available</span>
            <span id="error1" name="error1" style="display: none;">Please Check The Date</span>
            <span id="error2" name="error2" style="display: none;">Please Select Dates With Difference Of Not More Than 30 Days</span>
            <span id="error6" name="error6" style="display: none;">Please Select Date Between  <?php echo date('Y-m-d', strtotime( $_SESSION['startdate']));?> AND <?php echo date('Y-m-d', strtotime($_SESSION['enddate']));?></span>
        </td>
    </tr>
    <tr>
        <td>Start Date</td>
        <td>End Date</td>
        <td></td>
    </tr>
    <tr>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td>
            <input type="submit"   class="g-button g-button-submit" value="Get Report" name="GetReport">
            <a href='javascript:void(0)' onclick="get_pdfreportRouteSmry();"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlsRouteSmry();return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <!--<a href='javascript:void(0)' onclick="standardized_print('<?php  echo $title; ?>');"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>-->
        </td>
    </tr>
    </tbody>
    </table>
</form>
<br><br>
<center id='centerDiv'></center>