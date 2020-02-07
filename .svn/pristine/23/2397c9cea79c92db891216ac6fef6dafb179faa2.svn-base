<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #leftover_filter{display: none}
    .dataTables_length{display: none}
    .ajax_response_3{
 margin-left: 600px;       	
}

.ajax_response_4{
  margin-left: 540px;      	
}
 </style>
<br/>
<hr/>
<div class='container' >
    <center>

        <table class='display table table-bordered table-striped table-condensed' id="leftover" style="width: 100%;" >
            <thead>
                <tr>
                    
                    
                    <td><input type="text" class='search_init' name='depotcode' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='depotcode' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='depotcode' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='depotname' autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='depotzone' autocomplete="off"/></td>
                    
                </tr>
                <tr class='dtblTh'>
                    
                    <th >Sku</th>
                    <th >Sku Description</th>
                    <th >No Of Units</th>
                    <th >Total Weight</th>
                    <th >Total Volume</th>
               </tr>
            </thead>
            <tbody id="prec">
                <tr >
                    <td></td>

                </tr>
            </tbody>            
            <tfoot>
            </tfoot>
        </table>
    </center>
</div>
<script type='text/javascript'>
    var data = <?php echo json_encode($leftover_sku); ?>;
    var tableId = 'leftover';
    var tableCols = [
        {"mData": "sku"}
        , {"mData": "sku_decsription"}
        , {"mData": "no_of_units"}
        , {"mData": "weight"}
        , {"mData": "volume"}
        
    ];
</script>