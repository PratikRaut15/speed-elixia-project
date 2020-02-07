<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
$date_issue = ((time() >= strtotime("2014-12-01")) || $_SESSION['role_modal']!=='elixir');
if($date_issue){
    $display_date = date('d-m-Y');
}
else{
    $display_date = "01-12-2014";
}

?>
<script type="text/javascript" src="createcheck.js"></script>
<script>
    <?php if(!$date_issue){ ?>
    jQuery(function(){
        jQuery('#SDate').datepicker({ 
             format: "dd-mm-yyyy",
             startDate: "<?php echo $display_date;?>"
        });
    });
    <?php } ?>
    function getTowingReport(){
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#towingreportForm").serialize();
        jQuery.ajax({
            url:"towing_report_ajax.php",
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
<script src="../../scripts/highcharts/js/highcharts.js" type='text/javascript'></script>
<script src="../../scripts/highcharts/js/modules/exporting.js"></script>
<script src="../../scripts/highcharts/js/modules/data.js"></script>
<script src="../../scripts/highcharts/js/modules/drilldown.js"></script>
<form action="reports.php?id=36" method="POST" id="towingreportForm" onsubmit="getTowingReport();return false;">
<table>
    <thead>
        <tr><th id="formheader" colspan="100%">Towing Analysis</th></tr>
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
<?php
if(isset($_SESSION['ecodeid'])){
?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
<?php
}
?>
    <tr>
        <td>Start Date</td>
        <td>End Date</td>
    </tr>
    <tr>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $display_date;?>" required/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $display_date;?>" required/></td>
        <td><input type="submit"   class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
        <td>
            <a href='javascript:void(0)' onclick="get_advanced_pdfreport('towing');"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="get_advanced_xlsreport('towing');return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a href='javascript:void(0)' onclick="get_advanced_print('towing');"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
        </td>
    </tr>
    </tbody>
    </table>
</form>
<br><br>
<center id='centerDiv'>
</center>
<?php /*dt: 24th nov 14, ak added, for mail pop-up*/ ?>
<div id='mail_pop' class='modal hide in' style='display:none;' >
    <div class='modal-header'>
        <button class='close' data-dismiss='modal'>×</button>
        <h4 style='color:#0679c0'>Mail Report</h4>
    </div>
    <div class='modal-body'>
        <span id='mailStatus'></span><br/>
        <form>
        <span class='add-on' style='color:#000000'>To(Email)</span>&nbsp;<input type='text' name='sentoEmail' id="sentoEmail" required />
        <span class='add-on' style='color:#000000'>PDF</span>&nbsp;<input type='radio' name='emailtype' value='pdf' checked/>
        <span class='add-on' style='color:#000000'>Excel</span>&nbsp;<input type='radio' name='emailtype' value='excel'/><br clear='both'>
        <div style='width:15%;border:4px;'><input type="submit"   onclick='send_advanced_mail(<?php  echo $_SESSION['customerno']; ?>, "towingMail");return false;' class="g-button g-button-submit" value="Send" name="sendMail"></div>
        </form>
    </div> 
</div>
<?php /**/ ?>
