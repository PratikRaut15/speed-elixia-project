<?php
/**
 * View Service interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<br/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='Lservice'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewservice" >
        <thead>
            <tr>
                <td><input type="text" class='search_init' name='categoryname' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='servicename' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='cost' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='expectedtime' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='entry_date' id='entryDate' autocomplete="off"/></td>
                <td></td>
            </tr>
            <tr class='dtblTh'>
                <th>Category Name</th>
                <th>Service Name</th>
                <th>Cost</th>
                <th>Expected Time</th>
                <th>Entry Date</th>
                <th></th>
            </tr>
        </thead>
    </table>
    </center>
</div>