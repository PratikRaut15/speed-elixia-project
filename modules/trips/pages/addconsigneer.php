<?php
/**
 * Consignor add Master form
 */
?>
<br/>
<div class='container'>
    <center>
    <form name="consignorform" id="consignorform" method="POST" action="trips.php?pg=addconsigneer" onsubmit="addconsigneerdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Add Consignor </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Consignor Name <span class="mandatory">*</span></td><td><input type="text" name="consigneername" id="consigneername" required></td></tr>
            <tr><td class='frmlblTd'>Email </td><td><input type="text" name="cremail" id="cremail" ></td></tr>
            <tr><td class='frmlblTd'>Phone No. </td><td><input type="text" name="crphone" id="crphone" ></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="consigneersubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
