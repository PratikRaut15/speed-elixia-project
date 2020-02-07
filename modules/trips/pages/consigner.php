<?php
/**
 * View Consignor interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    //#viewcategory_filter{display: none}
    .dataTables_length{display: none}
</style>    
<br/>

<div class='container' style="width:50%;" >
    <div style="float:right;">
    </div>
    <center>
        <input type='hidden' id='forTable' value='viewConsignor'/>
        <table class='table table-bordered ' id="viewconsignor" >
        <thead>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="width:50px;"></td>
                <td style="width:50px;"></td>
            </tr>
            <tr class='dtblTh'>
                <th>Consignor Name</th>
                <th>Email</th>
                <th>Phone No.</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
    </table>
    </center>
</div>


