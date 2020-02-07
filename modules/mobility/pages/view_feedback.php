<?php
/**
 * View Feedback interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<br/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='viewfeedback'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewfeedback" >
        <thead>
            <tr>
<!--                <td><input type="text" class='search_init' name='fid' autocomplete="off"/></td>-->
                <td><input type="text" class='search_init' name='feedback_question' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='entry_date' id='entryDate' autocomplete="off"/></td>
                <td></td>
            </tr>
            <tr class='dtblTh'>
<!--                <th>Feedback Id</th>-->
                <th>Questions</th>
                <th>Entry Date</th>
                <th></th>
            </tr>
        </thead>
    </table>
    </center>
</div>