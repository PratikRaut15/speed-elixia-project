<?php
/**
 * Add Product In Order master form
 */
?>
<br/>
<div class='container'>
    <center>
    <form name="productinorderform" id="#productinorderform" method="POST"  onsubmit="add_productinorder();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Product In Order Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'> <span class="mandatory">*</span></td><td><input type="text" name="rname" required></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="remindersubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
