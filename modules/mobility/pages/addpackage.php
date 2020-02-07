<?php
/**
 * Add Package master form
 */
?>
<br/>
<div class='container'>
    <center>
    <form name="addpackageform" id="addpackageform" method="POST" action="mobility.php?pg=add-package" onsubmit="addpackdata();return false;">
        <table class='table table-condensed'>
            <thead><tr><th colspan="100%" >Package Master</th></tr></thead>
            <tbody>
                <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                <tr><td class='frmlblTd'>Membership Code<span class="mandatory">*</span></td><td><input type="text" name="membershipcode" required></td></tr>
                <tr><td class='frmlblTd'>Amount <span class="mandatory">*</span></td><td><input type="text" name="amount" required></td></tr>
                <tr><td class='frmlblTd'>Validity <span class="mandatory">*</span></td><td><input type="text" name="membervalidity" required></td></tr>
                <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="citysubmit" value="Add" class='btn btn-primary'></td></tr>
            </tbody>
        </table>
    </form>
    </center>
</div>
