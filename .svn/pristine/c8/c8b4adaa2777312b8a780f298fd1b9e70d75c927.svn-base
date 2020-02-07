<?php
/**
 * View Category interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    //#viewcategory_filter{display: none}
    .dataTables_length{display: none}
</style>    
<br/>

<div class='container' style="width:60%;" >
    <div style="float:right;">
    </div>
    <center>
        <input type='hidden' id='forTable' value='viewPrimarysales'/>
        <table class='table table-bordered ' id="viewprimarysales" >
        <thead>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
               <?php 
               if ($_SESSION['role_modal'] == "ASM" ||$_SESSION['role_modal']=="Supervisor" ||$_SESSION['role_modal']=="Administrator" || $_SESSION['role_modal']=="elixir" ) {
               ?>
                <td style="width:50px;"></td>
                <td style="width:50px;"></td>
                <?php   } ?>
                <td style="width:50px;"></td>
                <?php 
                if($_SESSION['role_modal']=='Sales_representative'){
                ?>
                <td style="width:50px;"></td>
                <?php
                }
                ?>
            </tr>
            <tr class='dtblTh'>
                <th>SR Name</th>
                <th>Delivery Date</th>
                <th>Order By</th>
                <th>Status</th>
                 <?php 
               if ($_SESSION['role_modal'] == "ASM" ||$_SESSION['role_modal']=="Supervisor" ||$_SESSION['role_modal']=="Administrator" || $_SESSION['role_modal']=="elixir" ) {
               ?>
                <th>Approve</th>
                <th>Reject</th>
               <?php } ?>
                <th>Edit</th>
                <?php 
                if($_SESSION['role_modal']=='sales_representative'){
                ?>
                <td style="width:50px;">Delete</td>
                <?php
                }
                ?>
            </tr>
        </thead>
    </table>
    </center>
</div>


