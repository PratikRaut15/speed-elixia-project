<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #leftover_filter{display: none}
    .dataTables_length{display: none}
</style>
<br/>
<hr/>
<div class='entry'>
    <center>
        <table class='display table table-bordered table-striped table-condensed' id="leftover">
            <thead>
                <tr>

                    <td><input type="text" class='search_init' style="width:50px;"  name='depotcode' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='depotcode' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='depotcode' autocomplete="off"/></td>
                    
                    <td><input type="text" class='search_init' name='depotcode' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='depotname' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='depotzone' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='depotzone' autocomplete="off"/></td>
                    
                    <td colspan="2"></td>
                    
                </tr>
                <tr class='dtblTh'>
                    <th style="width: 50px;">Sr.No</th>
                    <th >Factory</th>
                    <th >Depot</th>
                    <th >Weight</th>
                    <th >Volume</th>
                    <th >Vehicle Requirement Date</th>
                    <th >Created On</th>
                    <th >Edit</th>
                    <th >Delete</th>
                </tr>
            </thead>

        </table>
    </center>
</div>
<script type='text/javascript'>
    var data = <?php echo json_encode($leftover_sku); ?>;
    var tableId = 'leftover';
    var tableCols = [
        {"mData": "leftoverid"}
        , {"mData": "factoryname"}
        , {"mData": "depotname"}
        , {"mData": "weight"}
        , {"mData": "volume"}
        , {"mData": "daterequired"}
        , {"mData": "created_on"}
        , {"mData": "edit"}
        , {"mData": "delete"}
    ];
</script>