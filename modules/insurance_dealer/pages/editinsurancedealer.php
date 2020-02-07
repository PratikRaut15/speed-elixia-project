<?php
    $idealer = getInsdealerByid($_GET['insdealerid']);
?>
<div  style="float:none; padding-left:41%;">
    <form name="edit_insurancedealer" action="route.php" method="POST" id="edit_insurancedealer" onsubmit="return submitinsdeal();">
    <table id="floatingpanel">
        <thead>
        <tr>
            <th id="formheader" colspan="2">Insurance Dealer</th>
        </tr>
        </thead>
        <tr>
            <td colspan="2" id="perfectinfo" style="display: none">Insurance Dealer Name Added</td>
            <td colspan="2" id="problem" style="display: none">Insurance Dealer Name</td>    
        </tr>    
        <tr>
            <td>Insurance Dealer Name</td>
            <td><input type="text" id="insdealername" size="60" name="insdealername" value="<?php echo $idealer->ins_dealername; ?>" placeholder="Insurance Company Name"></td>
        </tr>
        <tfoot>
            <input type="hidden" name="insdealerid" id="insdealerid" name ="insdealerid" value="<?php echo $idealer->ins_dealerid; ?>" />
        <tr>
            <td colspan="2" align="center"><input type="submit" name="editinsurancedealer" class="btn  btn-primary" value="Modify" onclick="submitinsdeal();"></td>
        </tr>
        </tfoot>
    </table>
</form>
</div>
<script>
function submitinsdeal()
{
    if(jQuery("#insdealername").val() == "")
    {
        jQuery("#problem").show();
        jQuery("#problem").fadeOut(3000);                 
    }
    else
    {
        jQuery("#edit_insurancedealer").submit();
    }
}
</script>