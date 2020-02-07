<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #proposedtransporter_filter{display: none}
    .dataTables_length{display: none}
</style>
<br/>
  
<hr/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='productionMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="proposedtransporter" >
            <thead>
                <tr>
                    <td><input type="text" class='search_init' style="width:90%;" name='delievry_id'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='factory' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                </tr>
                <tr class='dtblTh'>
                    <th >Proposed Indent ID </th>
                    <th >Transporter </th>
                    <th >Factory </th>
                    <th >Depot </th>
                    <th >Proposed Vehicle Type </th>
                    <th >Actual Vehicle Type </th>
                    <th >Vehicle No</th>
                    <th >Driver Mobile No</th>
                    <th >Vehicle Requirement Date</th>
                    <th >Created On</th>
                    <th >View</th>
                    <th >Edit</th>
                    <th >Delete</th>
                </tr>
            </thead>
        </table>
    </center>
</div>
<script type='text/javascript'>
    var data = <?php echo json_encode($proposed_indents_sku); ?>;
    var tableId = 'proposedtransporter';
    var tableCols = [
        {"mData": "proposedindentid"}
        , {"mData": "transportername"}
        , {"mData": "factoryname"}
        , {"mData": "depotname"}
        , {"mData": "proposedvehiclecode"}
        , {"mData": "actualvehiclecode"}
        , {"mData": "vehicleno"}
        , {"mData": "drivermobileno"}
        , {"mData": "daterequired"}
        , {"mData": "created_on"}
        , {"mData": "view"}
        , {"mData": "edit"}
        , {"mData": "delete"}
    ];
</script>
