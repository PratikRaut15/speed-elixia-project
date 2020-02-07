<?php
/**
 * Category master form
 */
?>
<br/>
<div class='container'>
    <center>
    <form name="addcatform" id="addcatform" method="POST" action="mobility.php?pg=add-category" onsubmit="addcatdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Category Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Category Name <span class="mandatory">*</span></td><td><input type="text" name="catname" id="catname" required></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="categorysubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
