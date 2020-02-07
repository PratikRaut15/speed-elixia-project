<?php echo $date_picker = "<script src='" . $_SESSION['subdir'] . "/scripts/datetime/bootstrap-datepicker.min.js'></script>"; ?>
<?php
$expid = $_GET['expid'];
?>

<script>
    jQuery(function () {

        jQuery('#expdate').datepicker({format: "dd-mm-yyyy", autoclose: true});
    });
    jQuery(document).click(function () {
        jQuery('#ajaxstatus').html('');
    });

</script>
<?php
/**
 * Expense Master form
 */
$editdata = getexp_data($expid,$_SESSION['customerno']);
$driverlist = getdrivers_allocated();
$categorylist = get_allcategory($_SESSION['customerno']);
?>

<style>
    #catform{
        width:50%;
    }
</style>
<br/>
<div class='container'>
    <center>
        <form name="expform" id="expform" method="POST" action="expense.php?id=2" onsubmit="editexpense();
                return false;">
            <table class='table table-condensed' style="width:50%">
                <thead><tr><th colspan="100%" >Expense Edit</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus" style="text-align: center;"></td></tr>
                    <tr>
                        <td class='frmlblTd'> Driver Name </td>
                        <td>
                            <select name="driver" id="driver">
                                <option value="0">Select</option>
                                <?php
                                if (isset($driverlist)) {
                                    foreach ($driverlist as $row) {
                                        ?>
                                        <option value="<?php echo $row->driverid; ?>" <?php if( $editdata[0]['driverid']==$row->driverid){echo"selected"; }?>   ><?php echo $row->drivername; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'> Category</td>
                        <td>
                            <select name="category" id="category">
                                <option value="0">Select</option>
                                <?php
                                if (isset($categorylist)) {
                                    foreach ($categorylist as $row) {
                                        ?>
                                        <option value="<?php echo $row['categoryid']; ?>" <?php if( $editdata[0]['categoryid']==$row['categoryid']){echo"selected"; }?>  ><?php echo $row['categoryname']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </td>  
                    </tr>
                    <tr>
                        <td class='frmlblTd'> Amount</td> 
                        <td>
                            <input type="text" name="amount" id="amount" value="<?php echo $editdata[0]['amount'];?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'> Expense Date</td> 
                        <td>
                            <input type="text" name="expdate" id="expdate" value="<?php echo date('d-m-Y',strtotime($editdata[0]['expence_date']));?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="rightalign" colspan="1"> <span class="add-on">Add photo</span></td>
                        <td colspan="3">
                            <input type="file" name="file1" id="file1"> <input type="hidden" name="extension" id="extension"/>
                        </td>
                    </tr>

                    <tr><td></td><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Edit Expense" class='btn btn-primary'></td></tr>
                <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
                <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['userid']; ?>">
                <input type="hidden" name="expid" id="expid" value="<?php echo $expid; ?>">
                <input type="hidden" name="vehicleid" id="vehicleid" value="<?php echo $editdata[0]['vehicleid']; ?>">
                </tbody>
            </table>
        </form>
    </center>
</div>


<script>

    function editexpense() {
        var driverid = jQuery("#driver").val();
        var categoryid = jQuery("#category").val();
        var amount = jQuery("#amount").val();
        var expdate = jQuery("#expdate").val();
        var customerno = jQuery("#customerno").val();
        var userid = jQuery("#userid").val();
        var expid = jQuery("#expid").val();
        if (driverid == 0) {
            jQuery('#ajaxstatus').html('Please select driver.');
            jQuery('#ajaxstatus').css('color', 'red');
            return false;
        } else if (categoryid == 0) {
            jQuery('#ajaxstatus').html('Please select catgory.');
            jQuery('#ajaxstatus').css('color', 'red');
            return false;
        } else if (amount == "") {
            jQuery('#ajaxstatus').html('Please enter amount.');
            jQuery('#ajaxstatus').css('color', 'red');
            return false;
        } else if (expdate == "") {
            jQuery('#ajaxstatus').html('Please select expensedate.');
            jQuery('#ajaxstatus').css('color', 'red');
            return false;
        }else{
             jQuery.ajax({
                type: "POST",
                url: "../expmanage/expense_ajax.php",
                data: "expid="+expid+"&driverid=" + driverid + "&categoryid=" + categoryid + "&amount="+amount+"&userid="+userid+"&customerno="+customerno+"&expdate="+expdate+"&action=editexpense",
                success: function (result) {
                    
                }
            });
        }
    }
</script>
