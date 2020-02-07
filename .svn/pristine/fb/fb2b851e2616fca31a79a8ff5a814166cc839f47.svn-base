<script type="text/javascript" src="createcheck.js"></script>
<script type="text/javascript">
/* showing reports of particular sensor. This will invoked from RTD page   */
<?php 
 if(isset($_GET['STdate']) && isset($_GET['EDdate']) && isset($_GET['deviceid']) && isset($_GET['vehicleno']))
 {
     ?>
     
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    //var data = jQuery("#doorHistForm").serialize();
    var STdate = '<?php echo $_GET['STdate']; ?>';
    var EDdate = '<?php echo $_GET['EDdate']; ?>';
    var deviceid = '<?php echo $_GET['deviceid']; ?>';
    var vehicleno = '<?php echo $_GET['vehicleno']; ?>';

    /* jQuery("#vehicleno").val(vehicleno);
    jQuery("#deviceid").val(deviceid); */
    jQuery('#vehicleno').val(vehicleno);
    jQuery('#deviceid').val(deviceid);
    jQuery('#display').hide();
    
    function fill(vehicleno, deviceid){ console.log("In fill function");
    jQuery('#vehicleno').val(vehicleno);
    jQuery('#deviceid').val(deviceid);
    jQuery('#display').hide();
}
    

    jQuery.ajax({
        url:"doorHist_ajax.php",
        type: 'POST',
        data: {STdate:STdate,EDdate:EDdate,deviceid:deviceid,vehicleno:vehicleno},
        success:function(result){
            jQuery("#centerDiv").html(result);
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
    });
  /*   jQuery('#vehicleno').val(vehicleno);
    jQuery('#deviceid').val(deviceid); */
<?php     
 }
?>
/*  */
function getDoorHist(){
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#doorHistForm").serialize();
    jQuery.ajax({
        url:"doorHist_ajax.php",
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
jQuery(function() {
    $("#vehicleno").autoSuggest({
    ajaxFilePath : "autocomplete.php",
    ajaxParams : "dummydata=doorSensor",
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
    jQuery('#deviceid').val(Value);
    jQuery('#display').hide();
}
</script>
<form action="reports.php?id=39" method="POST" id='doorHistForm' onsubmit="getDoorHist();return false;">
<?php
    $title = "Door Sensor History";
    $today = date('d-m-Y');
    if ($_SESSION['switch_to'] == 3) {
        if (isset($_SESSION['Warehouse'])) {
            $vehicle = $_SESSION['Warehouse'];
        } else {
            $vehicle = 'Warehouse';
        }
    } else {
        $vehicle = 'Vehicle No.';
    }
    include 'panels/doorHist.php';
    if (isset($_SESSION['ecodeid'])) {
    ?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
<?php
    }
?>
    <tr>
        <td><?php echo $vehicle; ?></td>
        <td>Start Date</td>
        <td>End Date</td>
        <td></td>
    </tr>
    <tr>
        <td>
            <input  type="text" name="vehicleno" id="vehicleno" size="20" value="" autocomplete="off" placeholder="Enter <?php echo $vehicle; ?>" required>
            <input type="hidden" name="deviceid" id="deviceid" size="20" value=""/>
            <div id="display" class="listvehicle"></div>
        </td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td>
            <input type="submit"   class="g-button g-button-submit" value="Get Report" name="GetReport">
            <a href='javascript:void(0)' onclick="get_pdfreportDoor();"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlsDoor();return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a href='javascript:void(0)' onclick="standardized_print('<?php echo $title; ?>');"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
        </td>
    </tr>
    </tbody>
    </table>
</form>
<br><br>
<center id='centerDiv'></center>
<?php
    $mail_function = "send_doorHist_mail(" . $_SESSION['customerno'] . ");";
    include_once "mail_pop_up.php";
?>
