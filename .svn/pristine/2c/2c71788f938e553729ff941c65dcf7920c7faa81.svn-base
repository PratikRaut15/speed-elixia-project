<?php echo $date_picker = "<script src='" . $_SESSION['subdir'] . "/scripts/datetime/bootstrap-datepicker.min.js'></script>"; ?>
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
        <form name="expform" id="expform" method="POST" action="expense.php?id=2" onsubmit="addexpense();
                return false;">
            <table class='table table-condensed' style="width:50%">
                <thead><tr><th colspan="100%" >Expense Add</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus" style="text-align: center;"></td></tr>
                    <tr>
                        <td class='frmlblTd'> Driver Name <span class="mandatory">*</span></td>
                        <td>
                            <select name="driver" id="driver">
                                <option value="0">Select</option>
                                <?php
                                if (isset($driverlist)) {
                                    foreach ($driverlist as $row) {
                                        ?>
                                        <option value="<?php echo $row->driverid; ?>"><?php echo $row->drivername; ?></option>
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
                                        <option value="<?php echo $row['categoryid']; ?>"><?php echo $row['categoryname']; ?></option>
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
                            <input type="text" name="amount" id="amount"/>
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'> Expense Date</td> 
                        <td>
                            <input type="text" name="expdate" id="expdate"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="rightalign" colspan="1"> <span class="add-on">Add photo</span></td>
                        <td colspan="3">
                            <input type="file" name="file1" id="file1"> <input type="hidden" name="extension" id="extension"/>
                        </td>
                    </tr>

                    <tr><td></td><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Add Expense" class='btn btn-primary'></td></tr>
                <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno']; ?>">
                <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['userid']; ?>">
                </tbody>
            </table>
        </form>
    </center>
</div>


<script>

    function addexpense(){
        var driverid = jQuery("#driver").val();
        var categoryid = jQuery("#category").val();
        var amount = jQuery("#amount").val();
        var expdate = jQuery("#expdate").val();
        var customerno = jQuery("#customerno").val();
        var userid = jQuery("#userid").val();
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
                data: "driverid=" + driverid + "&categoryid=" + categoryid + "&amount="+amount+"&userid="+userid+"&customerno="+customerno+"&expdate="+expdate+"&action=addexpense",
                success: function (result) {
                     if(result){
                        window.location = "../expmanage/expensemng.php?id=2";
                    }
                }
            });
        }
    }
</script>
