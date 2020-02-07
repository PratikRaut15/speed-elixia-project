<?php
/**
 * Reminder master form
 */
?>
<br/>
<div class='container'>
    <center>
    <form name="remindermasterform" id="remindermasterform" method="POST"  onsubmit="addreminderdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Reminder Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Reminder Name <span class="mandatory">*</span></td><td><input type="text" name="rname" required></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="remindersubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
