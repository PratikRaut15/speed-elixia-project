<?php
/**
 * View Sales Manage  interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<br/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='viewSales'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewsales">
        <thead>
            <tr>
                <td><input type="text" class='search_init' name='srname' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='sremail' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='srphone' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='entry_date' id='entryDate' autocomplete="off"/></td>
                <td></td>
            </tr>
            <tr class='dtblTh'>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Entry Date</th>
                <th></th>
            </tr>
        </thead>
    </table>
    </center>
</div>