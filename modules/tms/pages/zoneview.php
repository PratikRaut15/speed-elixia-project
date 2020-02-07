<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #zonemaster_filter{display: none}
   .dataTables_length{display: none}
</style>
<br/>
<div class='container' >
    <center>
        <form style='display:inline;' method="post" action="action.php?action=add-zone">
        <div class="input-prepend ">
            <span class="add-on" style="width: 150px; text-align: left;">Add Zone </span>
        <input type="text" name="zone_name" id="zone_name" style="width: 250px;" value="" maxlength="25"/>  
        <input style='display:inline;width: 120px;' type='submit' class="btn  btn-primary" value='  Add  '/>
        </div>
     </form>
    </center>
</div>    
<hr/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='zoneMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="zonemaster" style="width: 60%;" >
        <thead>
            <tr>
                
                <td><input type="text" class='search_init' name='zname' style="width:90%;" autocomplete="off"/></td>
        </tr>
            <tr class='dtblTh'>
                
                <th width="80%">Zone Name</th>
                <th>Edit</th>
                <th>Delete</th>
                
                
                <!--
                <th></th>
                -->
            </tr>
        </thead>
    </table>
    </center>
</div>
<script type='text/javascript'>
    var data = <?php echo json_encode($zones); ?>;
    var tableId = 'zonemaster';
    var tableCols = [
         {"mData": "zonename"}
        , {"mData": "edit"}
        , {"mData": "delete"}
        
       
    ];
</script>