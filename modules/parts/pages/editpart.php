<?php
if (isset($_GET['did'])) {
    $delpart = delpart($_GET['did']);
    header("location: parts.php?id=2");
}
$part = getpartsbyid($_GET['pid']);
?>
<div  style="float:none; padding-left:29%;">
    <form name="editpart" action="route.php" method="POST" id="editpart">
        <table id="floatingpanel">
            <thead>
                <tr>
                    <th id="formheader" colspan="2"><h4>Update Part</h4></th>
                </tr>
            </thead>
            <tr>
                <td colspan="2" id="perfectinfo" style="display: none">Part Added</td>
                <td colspan="2" id="problem" style="display: none">Please enter name</td>    
            </tr>    
            <tr>
                <td>Part Name</td>
                <td><input type="text" name="editname" id="editname" size="60" value="<?php echo $part->part_name; ?>" placeholder="Name"></td>
            </tr>
            <tr>
                <td>Unit Amount</td>
                <td><input id="partamount" name="partamount" size="50" type="text" style="width:200px;" placeholder="0.00" value="<?php echo $part->unitamount; ?>" onkeypress="return isNumber(event)"></td>
            </tr>
            <tr>
                <td>Unit Discount</td>
                <td><input id="partdiscount" name="partdiscount" size="50" type="text" style="width:200px;" value="<?php echo $part->unitdiscount; ?>" placeholder="0.00" onkeypress="return isNumber(event)"></td>
            </tr>
            <tfoot>
            <input type="hidden" name="partid" id="partid" value="<?php echo $_GET['pid']; ?>"/>
            <tr>
                <td colspan="2" align="center"><input type="button" name="edituserdetails" class="btn  btn-primary" value="Update Parts Details" onclick="submitpart();"></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script>

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    function submitpart()
    {
        if (jQuery("#editname").val() == "")
        {
            jQuery("#problem").show();
            jQuery("#problem").fadeOut(3000);
        }
        else
        {
            jQuery("#editpart").submit();
        }
    }
</script>