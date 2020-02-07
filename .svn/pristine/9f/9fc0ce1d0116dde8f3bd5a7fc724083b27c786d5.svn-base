<?php

    $make = getmakeid($_GET['mid']);
?>
<div  style="float:none; padding-left:41%;">
<form name="editmake" action="route.php" method="POST" id="editmake">
    <table id="floatingpanel">
        <thead>
        <tr>
            <th id="formheader" colspan="2">Edit Make</th>
        </tr>
        </thead>
        <tr>
            <td colspan="2" id="perfectinfo" style="display: none"> Vehicle Make Added</td>
            <td colspan="2" id="problem" style="display: none;color: #FF0000;">Please enter make name</td>    
        </tr>    
        <tr>
            <td>Make Name</td>
            <td><input type="text" name="makename" id="makename" size="60" value="<?php echo $make ?>" placeholder="Name"></td>
        </tr>
        <tfoot>
            <input type="hidden" name="makeid" id="makeid" value="<?php echo $_GET['mid']; ?>" />
        <tr>
            <td colspan="2" align="center"><input type="submit" name="editmakedetails" class="btn  btn-primary" value="Modify" onclick="submitmake();"></td>
        </tr>
        </tfoot>
    </table>
</form>
</div>
<script>
function submitmake()
{
    if(jQuery("#editname").val() == "")
    {
        jQuery("#problem").show();
        jQuery("#problem").fadeOut(3000);                 
    }
    else
    {
        jQuery("#editmake").submit();
    }
}
</script>
