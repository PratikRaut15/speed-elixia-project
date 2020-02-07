<?php
if(isset($_GET['did']))
{
    $delaccessory = delaccessory($_GET['did']);
    header("location: accessories.php?id=2");
}
    $acc = getaccsbyid($_GET['tid']);
?>
<div  style="float:none; padding-left:41%;">
<form name="editaccessory" action="route.php" method="POST" id="editaccessory">
    <table id="floatingpanel">
        <thead>
        <tr>
            <th id="formheader" colspan="2">Accessory</th>
        </tr>
        </thead>
        <tr>
            <td colspan="2" id="perfectinfo" style="display: none; color: #00FF00">Accessory Added</td>
            <td colspan="2" id="problem" style="display: none; color: #FF0000">Please enter Accessory Name</td>    
            <td colspan="2" id="problem_2" style="display: none; color: #FF0000">Please enter Maximum Permissible Amount</td>                
        </tr>    
        <tr>
            <td>Accessory Name</td>
            <td><input type="text" name="editname" id="editname" value="<?php echo $acc->name; ?>" placeholder="Name"></td>
        </tr>
        <tr>
            <td>Maximum Permissible Amount</td>
            <td><input type="text" name="editamount" id="editamount" value="<?php echo $acc->max_amount; ?>" placeholder="Amount"></td>
        </tr>        
        <tfoot>
            <input type="hidden" name="accid" id="accid" value="<?php echo $_GET['tid']; ?>" />
        <tr>
            <td colspan="2" align="center"><input type="button" name="edituserdetails" class="btn  btn-primary" value="Modify" onclick="submitaccessory();"></td>
        </tr>
        </tfoot>
    </table>
</form>
</div>
<script>
function submitaccessory()
{
    if(jQuery("#editname").val() == "")
    {
        jQuery("#problem").show();
        jQuery("#problem").fadeOut(6000);                 
    }
    else if(jQuery("#editamount").val() == "")
    {
        jQuery("#problem_2").show();
        jQuery("#problem_2").fadeOut(6000);                 
    }    
    else
    {
        jQuery("#editaccessory").submit();
    }
}
</script>