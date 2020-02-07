<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #proposedindentskumaster_filter{display: none}
    .dataTables_length{display: none}
</style>
<br/>
<div class='container' >
   
    <div style="float:right;">
        <a href="tms.php?pg=view-proposed-indent"> <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;">Back To Proposed Indents</button></a>
    </div>
    
   
    
</div>    

<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='productionMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="proposedindentskumaster" >
            <thead>
                <tr>
                    <td><input type="text" class='search_init' style="width:90%;" name='delievry_id'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='factory' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='depot' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='vehicle' style="width:90%;" autocomplete="off"/></td>
                   
                </tr>
                <tr class='dtblTh'>
                    <th >SKU Code</th>
                    <th >SKU Description</th>
                    <th >No Of Units</th>
                    <th >Weight</th>
                    <th >Volume</th>
                    

                    <!--
                    <th></th>
                    -->
                </tr>
            </thead>
        </table>

    </center>
</div>
<?php
//print_r($proposed_indents_sku);
?>
<script type='text/javascript'>
    var data = <?php echo json_encode($proposed_indents_sku); ?>;
    var tableId = 'proposedindentskumaster';
    var tableCols = [
         {"mData": "skucode"}
        , {"mData": "sku_description"}
        , {"mData": "no_of_units"}
        , {"mData": "weight"}
        , {"mData": "volume"}
        
        
       
    ];
</script>
