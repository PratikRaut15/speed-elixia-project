<?php
/**
 * View Trackie interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<br/>
<div class='container-fluid' >
    <center>
        <input type='hidden' id='forTable' value='Ltrackie'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewtrackie" >
        <thead>
            <tr>
<!--                <td><input type="text" class='search_init' autocomplete="off"/></td>-->
                <td><input type="text" class='search_init' autocomplete="off"/></td>    
                <td><input type="text" class='search_init' autocomplete="off"/></td>
                <td><input type="text" class='search_init' autocomplete="off"/></td>
                <td><input type="text" class='search_init' autocomplete="off"/></td>
                <td><input type="text" class='search_init' id='entryDate' name="entry_date" autocomplete="off"/></td>
                <td></td>
            </tr>
            <tr class='dtblTh'>
<!--                <th>Trackie Id</th>-->
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Entry Date</th>
                <th></th>
            </tr>
        </thead>
    </table>
    </center>
</div>