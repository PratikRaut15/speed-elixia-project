<script>
$(function() {
    $("#vehicleno").autoSuggest({
        ajaxFilePath : "autocomplete.php", 
	ajaxParams : "dummydata=genset", 
	autoFill : false, 
	iwidth: "auto",
	opacity: "0.9",
	ilimit: "10",
	idHolder: "id-holder",
	match: "contains"
    });
});

function fill(Value, strparam){
    jQuery('#vehicleno').val(strparam);
    jQuery('#deviceid').val(Value);
    jQuery('#display').hide();
}
function getAcSensorHistReport(){
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#AcSensorHistForm").serialize();
    jQuery.ajax({
        url:"acsensorhist_ajax.php",
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
<form action="reports.php?id=7" method="POST" id="AcSensorHistForm" onsubmit="getAcSensorHistReport();return false;">
<?php
$title = $_SESSION["digitalcon"].' Sensor History';
$today = date('d-m-Y');
include 'panels/acsensorhist.php';

if(isset($_SESSION['ecodeid'])){
?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
<input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days'];?>" />
<input type="hidden" name="calculateddate" id="calculateddate" value="<?php echo date('d-m-Y',strtotime($_SESSION['codecalculateddate']));?>" />
<?php
}
?>
    <tr>
        <td>Vehicle</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td></td>
    </tr>
    <tr>
        <td>
            <input  type="text" name="vehicleno" id="vehicleno" size="20" value="" autocomplete="off" placeholder="Enter Vehicle No" required>
            <input type="hidden" name="deviceid" id="deviceid" size="20" value=""/>
            <div id="display" class="listvehicle"></div>
        </td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td>
            <input type="submit"   class="g-button g-button-submit" value="Get Report" name="GetReport">
            <a href='javascript:void(0)' onclick="get_pdfreportGenset(<?php echo $_SESSION['customerno']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlsgenset(<?php  echo $_SESSION['customerno']; ?>);return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a href='javascript:void(0)' onclick="get_acsensor_print('<?php  echo $title; ?>');"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
        </td>
    </tr>
    </tbody>
    </table>
</form>
<br><br>
<center id='centerDiv'></center>
<?php
$mail_function = "send_gensetHist_mail(".$_SESSION['customerno'].");";
include_once "mail_pop_up.php";
?>
