<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #indentmaster_filter{display: none}
    .dataTables_length{display: none}
</style>
<br/>
<hr/>
<div class='entry' >
    <center>
        <input type='hidden' id='forTable' value='productionMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="indentmaster" >
            <thead>
                <tr>
                    <td><input type="text" class='search_init' style="width:80%;" name='delievry_id'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='factory' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='vehicle' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='skumaping' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='weight' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
                </tr>
                <tr class='dtblTh'>
                    <th width="8%">Indent ID</th>
                    <th >Proposed Indent </th>
                    <th >Factory </th>
                    <th >Depot </th>
                    <th >Transporter </th>
                    <th >Proposed Vehicletype </th>
                    <th >Actual Vehicletype </th>
                    <th >Vehicle No</th>
                    <th >Weight </th>
                    <th >Volume</th>
                    <th >Vehicle Requirement Date</th>
                    <th >Created On</th>
                    <th >View</th>
                    <th >Edit</th>
                </tr>
            </thead>
        </table>

    </center>
</div>

<script type='text/javascript'>
    var data = <?php echo json_encode($indents); ?>;
    var tableId = 'indentmaster  ';
    var tableCols = [
        {"mData": "indentid"}
        , {"mData": "proposedindentid"}
        , {"mData": "factoryname"}
        , {"mData": "depotname"}
        , {"mData": "transportername"}
        , {"mData": "proposedvehiclecode"}
        , {"mData": "actualvehiclecode"}
        , {"mData": "vehicleno"}
        , {"mData": "weight"}
        , {"mData": "volume"}
        , {"mData": "daterequired"}
        , {"mData": "created_on"}
        , {"mData": "view"}
        , {"mData": "edit"}
    ];
</script>