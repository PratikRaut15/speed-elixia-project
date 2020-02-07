<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #vehcietypemaster_filter{display: none}
   .dataTables_length{display: none}
 .ajax_response_8{
        margin-left: 710px;      	
    }
</style>
<br/>
<div class='container' >
    <center>
        
        <form style='display:inline;width: 90%;' method="post" action="action.php?action=add-vehicle-type" >
        <div class="input-prepend ">
        <span class="add-on" style="text-align: left; width: 90px; ">Vehicle Code</span>
        <input type="text" name="vehiclecode" id="vehiclecode" value="" maxlength="20"/>
        <span class="add-on" style="text-align: left;">Vehicle Description</span>
        <input type="text" name="vehicledescription" id="vehicledescription" value="" maxlength="50"/>
        <span class="add-on" style="text-align: left;">Sku Type</span>
        <input type="text" name="type_name" id="type_name" value="" autocomplete="off" />
        <input type="hidden" name="typeid" id="typeid" value=""/>
        <br/><br/>
        <span class="add-on" style="text-align: left;width: 110px;">Vol In M3</span>
        <input type="text" name="volume_m" id="volume_m" value="" maxlength="10"/>
        <span class="add-on" style="text-align: left;width: 110px;">Weight In Tons</span>
        <input type="text" name="volume_kg" id="volume_kg" value="" maxlength="10"/>
        <input style='display:inline;' type='submit' class="btn  btn-primary" value='  Add Type  '/>
        </div>
     </form>
    </center>
</div>    
<hr/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='vehcietypeMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="vehcietypemaster" style="width: 90%;" >
        <thead>
            <tr>
                
                <td><input type="text" class='search_init' name='plant_name' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init'  name='plant_location' style="width:90%;" autocomplete="off"/></td>
                <td colspan="2"></td>
                
                
                
            </tr>
            <tr class='dtblTh'>
               
                <th >Code</th>
                <th >Description </th>
                <th >Type </th>
                <th >Volume In M3 </th>
                <th >Weight In Tons</th>
                <th >Edit</th>
                <th >Delete</th>
                
                
                <!--
                <th></th>
                -->
            </tr>
        </thead>
    </table>
 
    </center>
</div>
<script type='text/javascript'>
    var data = <?php echo json_encode($transporters); ?>;
    var tableId = 'vehcietypemaster';
    var tableCols = [
        {"mData": "vehiclecode"}
        , {"mData": "vehicledescription"}
        , {"mData": "type"}
        , {"mData": "volume"}
        , {"mData": "weight"}
        , {"mData": "edit"}
        , {"mData": "delete"}
    ];
</script>
