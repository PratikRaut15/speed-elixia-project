<?php
/**
 * View City interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<br/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='Vcity'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewCity" >
        <thead>
            <tr>
<!--                <td><input type="text" class='search_init' name='city_id' autocomplete="off"/></td>-->
                <td><input type="text" class='search_init' name='cityname' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='entry_date' id='entryDate' autocomplete="off"/></td>
                <td></td>
            </tr>
            <tr class='dtblTh'>
<!--                <th>City Id</th>-->
                <th>City Name</th>
                <th>Entry Date</th>
                <th></th>
            </tr>
        </thead>
    </table>
    </center>
</div>