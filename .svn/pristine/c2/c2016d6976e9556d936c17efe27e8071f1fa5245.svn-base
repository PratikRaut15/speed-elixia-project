<script>
function getTripHistReport(){
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#tripHistForm").serialize();
    jQuery.ajax({
        url:"triphist_ajax.php",
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
$(function() {
    $("#vehicleno").autoSuggest({
        ajaxFilePath : "autocomplete.php",
	ajaxParams : "dummydata=dummyData",
	autoFill : false,
	iwidth : "auto",
	opacity : "0.9",
	ilimit : "10",
	idHolder : "id-holder",
	match : "contains"
    });
});

function fill(Value, strparam){
    jQuery('#vehicleno').val(strparam);
    jQuery('#vehicleid').val(Value);
    jQuery('#display').hide();

}
</script>
<form action="reports.php?id=8" method="POST" id='tripHistForm' onsubmit="getTripHistReport();return false;">
<?php
    $title = 'Trip Report';
    $today = date('d-m-Y');
    include 'panels/triphist.php';
    if (isset($_SESSION['ecodeid'])) {
    ?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
<input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days']; ?>" />
<input type="hidden" name="calculateddate" id="calculateddate" value="<?php echo date('d-m-Y',strtotime($_SESSION['codecalculateddate']));?>" />
<?php
    }
?>
    <tr>
        <td>Vehicle No.</td>
        <td>Start Date</td>
        <td>Start Hour</td>
        <td>End Date</td>
        <td>End Hour</td>
        <td colspan='2'></td>
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
        <td><input type="submit" value="Get Report" class="g-button g-button-submit" name="GetReport"></td>
        <td>
            <a href='javascript:void(0)' onclick="table2CSV(jQuery('#search_table_2')); return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a>
        </td>
    </tr>
    </tbody>
    </table>
</form>
<br/><br/>
<center id='centerDiv'></center>