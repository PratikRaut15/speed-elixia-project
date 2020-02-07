<?php
/**
 * Edit Consignor form
 */
$consdata = consignor_edit($_SESSION['customerno'],$_SESSION['userid'],$consrid);

if(isset($consdata))
    {
        foreach($consdata as $row)
        {
            $consrid = $row['consrid'];
            $consigneename = $row['consignorname'];
            $phone = $row['phone'];
            $email = $row['email'];
        }
    }



?>
<br/>
<div class='container'>
    <center>
        <form name="consignorformedit" id="consignorformedit" method="POST" action="trips.php?pg=editconsigneer&consrid=<?php echo $consrid;?>" onsubmit="editconsignordata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Consignor </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Consignor Name <span class="mandatory">*</span></td><td><input type="text" name="consigneername" id="consigneername" value="<?php echo $consigneename; ?>" required></td></tr>
            <tr><td class='frmlblTd'>Email </td><td><input type="text" name="cremail" id="cremail" value="<?php echo $email; ?>" ></td></tr>
            <tr><td class='frmlblTd'>Phone No. </td><td><input type="text" name="crphone" id="crphone"  value="<?php echo $phone;?>"></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="consignorsubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
            <input type="hidden" name="consrid" id="consrid" value="<?php echo $consrid; ?>">
    </form>
    </center>
</div>
