<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #sharemaster_filter{display: none}
    .dataTables_length{display: none}
    .ajax_response_3{
        margin-left: 280px;      	
    }
    .ajax_response_4{
        margin-left: 500px;      	
    }
    .ajax_response_5{
        margin-left: 100px;      	
    }
</style>
<br/>
<div class='container' >
    <!--
    <div style="float:right;">
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addVehicelType();">Add Vehicle <img src="../../images/show.png"></button>
    </div>
    -->
    <center>
        <form style='display:inline;width: 90%;' method="post" action="action.php?action=add-share" >
            <div class="input-prepend ">
                <!--
                    <span class="add-on" style="text-align: ">Factory</span>
                <input type="text" style="width: 120px;" name="factory_name" id="factory_name" value="" maxlength="50" autocomplete="off"/>
                 <input type="hidden" name="factoryid" id="factoryid" value="" maxlength="50"/>
                 <div id="display" class="listlocation"></div> 
                --> 

                <span class="add-on" style="text-align: ">Factory</span>
                <input type="text" style="width: 120px;" name="factory_name" id="factory_name" value="" maxlength="50" autocomplete="off"/>
                <input type="hidden" name="factoryid" id="factoryid" value="" maxlength="50"/>
                <div id="display" class="listlocation"></div> 


                <span class="add-on" style="text-align: ">Zone</span>
                <input type="text" style="width: 120px;" name="zonename" id="zonename" value="" maxlength="50" autocomplete="off"/>
                <input type="hidden" name="zoneid" id="zoneid" value="" maxlength="50"/>
                <div id="display" class="listzone"></div>
                <span class="add-on" style="text-align: ">Transporter</span>
                <input type="text" style="width: 120px;" name="transportername" id="transporter_name" value="" maxlength="50" autocomplete="off"/>
                <input type="hidden" name="transporterid" id="transporterid" value="" maxlength="50"/>
                <div id="display" class="listlocation"></div> 
                <span class="add-on" style="text-align: ">Share %</span>
                <input type="text"  style="width: 90px;" name="sharepercent" id="sharepercent" value="" maxlength="6"/>
                <input style='display:inline;' type='submit' class="btn  btn-primary" value='  Add Share  '/>
            </div>
        </form>
    </center>

</div>    
<hr/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='shareMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="sharemaster" style="width: 90%;" >
            <thead>
                <tr>

                    <td><input type="text" class='search_init' name='sharepercent' style="width:90%;" autocomplete="off"/></td>

                    <td><input type="text" class='search_init' name='share_transporter' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='share_factory' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='share_zone' style="width:90%;" autocomplete="off"/></td>
                    <td colspan="2"></td>



                </tr>
                <tr class='dtblTh'>

                    <th >Factory </th>
                    <th >Zone </th>
                    <th >Transporter </th>
                    <th >Share</th>
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
    var data = <?php echo json_encode($shares); ?>;
    var tableId = 'sharemaster';
    var tableCols = [
        {"mData": "factoryname"}
        , {"mData": "zonename"}
        , {"mData": "transportername"}
        , {"mData": "sharepercent"}
        , {"mData": "edit"}
        , {"mData": "delete"}


    ];
</script>