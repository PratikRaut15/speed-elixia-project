<?php
/**
 * Edit Consignee form
 */
$consdata = consignee_edit($_SESSION['customerno'],$_SESSION['userid'],$consid);
if(isset($consdata))
    {
        foreach($consdata as $row)
        {
            $consid = $row['consid'];
            $consigneename = $row['consigneename'];
            $phone = $row['phone'];
            $email = $row['email'];
        }
    }



?>
<br/>
<div class='container'>
    <center>
        <form name="consigneeformedit" id="consigneeformedit" method="POST" action="trips.php?pg=editconsignee&consid=<?php echo $consid;?>" onsubmit="editconsigneedata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Consignee </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Consignee Name <span class="mandatory">*</span></td><td><input type="text" name="consigneename" id="consigneename" value="<?php echo $consigneename; ?>" required></td></tr>
            <tr><td class='frmlblTd'>Email </td><td><input type="text" name="cemail" id="cemail" value="<?php echo $email; ?>" ></td></tr>
            <tr><td class='frmlblTd'>Phone No. </td><td><input type="text" name="cphone" id="cphone"  value="<?php echo $phone;?>"></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="consigneesubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
            <input type="hidden" name="consid" id="consid" value="<?php echo $consid; ?>">
    </form>
    </center>
</div>
