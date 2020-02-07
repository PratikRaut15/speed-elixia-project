<?php
/**
 * Feedback master form
 */
?>
<br/>
<div class='container' >
    <center>
    <form name="addfeedbackform" id="addfeedbackform" method="POST" action="mobility.php?pg=add-feedback" onsubmit="addfeedback();return false;">
    <table class='table table-condensed' id='feedback'>
        <thead><tr><th colspan="100%" >Add Feedback</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Question <span class="mandatory">*</span></td><td><input type="text" name="question" id='question' style='width:100%;' required></td></tr>
            <tr><td class='frmlblTd'>Options</td><td><input type="text" name="option[]" id='option'></td></tr>
            <tr><td class='frmlblTd'>&nbsp;</td><td><a href="javascript:void(0);" onclick='addrow()'>Add Options</a></td>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="feedbacksubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
<script>
function addrow(){
    var table = document.getElementById("feedback");
    var row = table.insertRow(4);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    cell1.innerHTML = "&nbsp;";
    cell2.innerHTML = "<input type='text' name='option[]' id='option'>";
}

function deleterow() {
    document.getElementById("feedback").deleteRow(0);
}

</script>

