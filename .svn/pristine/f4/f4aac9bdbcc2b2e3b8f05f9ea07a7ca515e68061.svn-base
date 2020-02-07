<?php
if(isset($_GET['did']))
{
    $smstrack = getsmstrackbyid($_GET['did']);
}
else if(isset($_GET['userid']))
{
    $smstrack = getuserphonebyid($_GET['userid']);
}
?>
<div  style="float:none; padding-left:41%;">
<form name="editsmsform" id="editsmsform">
    <table id="floatingpanel">
        <thead>
        <tr>
            <th id="formheader" colspan="2">Sms Tracking</th>
        </tr>
        </thead>
        <tr>
            <td colspan="2" id="perfectinfo" style="display: none">Phone no. edited</td>
            <td colspan="2" id="samephone" style="display: none">Phone no. already exist</td>
            <td colspan="2" id="smserror" style="display: none">Phone no. should be 10 digit</td>
            <td colspan="2" id="problem" style="display: none">Please Retry</td>    
        </tr>    
        <tr>
            <td>Name</td>
            <td><input type="text" name="editname" id="editname" size="30" value="<?php echo $smstrack->name; ?>" placeholder="Name"></td>
        </tr>
        <tr>
            <td>Phone No</td>
            <td><input type="text" name="editphoneno" id="editphoneno" size="30" value="<?php echo $smstrack->phoneno; ?>" placeholder="Phone Number"></td>
        </tr>
        <tfoot>
<?php if(isset($_GET['did']))
        { ?>
            <input type="hidden" name="trackid" value="<?php echo $_GET['did']; ?>" />
<?php }
    else if(isset($_GET['userid']))
    {
?>
            <input type="hidden" name="userid" value="<?php echo $_GET['userid']; ?>" />
<?php } ?>
        <tr>
            <td colspan="2" align="center"><input type="button" name="edituserdetails" class="btn  btn-primary" value="Modify" onclick="checkphonenoedit();"></td>
        </tr>
        </tfoot>
    </table>
</form>
</div>