<?php
/**
 * View Location interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<br/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='Llocation'/>
        <table class='display table table-bordered table-striped table-condensed' id="viewlocatn" >
        <thead>
            <tr>
<!--                <td><input type="text" class='search_init' name='location_id' autocomplete="off"/></td>-->
                <td><input type="text" class='search_init' name='location' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='cityname' autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='entry_date' id='entryDate' autocomplete="off"/></td>
                <td></td>
            </tr>
            <tr class='dtblTh'>
<!--                <th>Location Id</th>-->
                <th>Location Name</th>
                <th>City Name</th>
                <th>Entry Date</th>
                  <th></th>
            </tr>
        </thead>
    </table>
    </center>
</div>