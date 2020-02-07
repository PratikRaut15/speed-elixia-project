<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #indentskumaster_filter{display: none}
    .dataTables_length{display: none}
</style>
<br/>
<div class='container' >
    <!--
    <div style="float:right;">
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addVehicelType();">Add Vehicle <img src="../../images/show.png"></button>
    </div>
    -->
</div>    
<hr/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='productionMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="indentskumaster" >
            <thead>
                <tr>
                    
                    <td><input type="text" class='search_init' name='factory' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='vehicle' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='vehicle' style="width:90%;" autocomplete="off"/></td>
                    
               
                </tr>
                <tr class='dtblTh'>
                    <th >Indent ID </th>
                    <th >SKU </th>
                    <th >SKU Description</th>
                    <th >No Of Units</th>
                    
               
                </tr>
            </thead>
        </table>

    </center>
</div>
<script type='text/javascript'>
    var data = <?php echo json_encode($indents_sku); ?>;
    var tableId = 'indentskumaster';
    var tableCols = [
         {"mData": "indentid"}
        , {"mData": "skucode"}
        , {"mData": "sku_description"}
        , {"mData": "no_of_units"}
       
    ];
</script>