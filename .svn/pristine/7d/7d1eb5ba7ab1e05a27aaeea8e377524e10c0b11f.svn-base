<script>
$(function() {
    $("#vehicleno").autoSuggest({
        ajaxFilePath : "autocomplete.php", 
	ajaxParams : "dummydata=extradigital", 
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
        url:"extrasensorhist_ajax.php",
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
$title = 'Extra Digital Sensor History';
$today = date('d-m-Y');
include 'panels/extrasensorhist.php';

if(isset($_SESSION['ecodeid'])){
?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
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
            <select name="extra" id="extra">
                <option value="000|Select">Select Digital</option>
                <?php if($_SESSION['ext_digital1']!=''){ ?>                 
                <option value="1|<?php echo $_SESSION['ext_digital1'];?>"><?php echo $_SESSION['ext_digital1'];?></option>
                <?php } ?>
                <?php if($_SESSION['ext_digital2']!=''){ ?>                 
                <option value="2|<?php echo $_SESSION['ext_digital2'];?>"><?php echo $_SESSION['ext_digital2'];?></option>
                <?php } ?>
                <?php if($_SESSION['ext_digital3']!=''){ ?>                 
                <option value="4|<?php echo $_SESSION['ext_digital3'];?>"><?php echo $_SESSION['ext_digital3'];?></option>
                <?php } ?>
                <?php if($_SESSION['ext_digital4']!=''){ ?>                 
                <option value="8|<?php echo $_SESSION['ext_digital4'];?>"><?php echo $_SESSION['ext_digital4'];?></option>
                <?php } ?>
                
            </select>
        </td>
        <td>
            <input type="submit"   class="g-button g-button-submit" value="Get Report" name="GetReport">
            <a href='javascript:void(0)' onclick="get_pdfreportExtra(<?php echo $_SESSION['customerno']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlsextra(<?php  echo $_SESSION['customerno']; ?>);return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a href='javascript:void(0)' onclick="get_extra_print('<?php  echo $title; ?>');"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
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
