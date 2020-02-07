<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #transportermaster_filter{display: none}
   .dataTables_length{display: none}
</style>
<br/>
<div class='container' >
    <center>
        
        <form style='display:inline;width: 80%;' method="post" action="action.php?action=add-transporter" >
        <div class="input-prepend ">
        <span class="add-on" style="text-align: left;width: 120px;">Transporter Code</span>
        <input type="text" style="width: 210px;" name="transportercode" id="transportercode" value="" maxlength="20"/>
        <span class="add-on" style="text-align: left;width: 120px;">Transporter Name</span>
        <input type="text" style="width: 210px;" name="transportername" id="transportername" value="" maxlength="50"/>
        <br/><br/>
        <span class="add-on" style="text-align: left;width: 120px;">Email</span>
        <input type="text" name="email" id="email" value="" maxlength="50"/>
        <span class="add-on" style="text-align: left;width: 120px;">Mobile</span>
        <input type="text" name="mobile" id="mobile" value="" maxlength="50"/>
        <input style='display:inline;' type='submit' class="btn  btn-primary" value='  Add Transporter  '/>
        </div>
     </form>
    </center>
</div>    
<hr/>
<div class='container' >
    <center>
        <input type='hidden' id='forTable' value='transporterMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="transportermaster" style="width: 80%;" >
        <thead>
            <tr>
               
                <td><input type="text" class='search_init' name='t_code' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='t_name' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='t_email' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='search_init' name='t_mobile' style="width:90%;" autocomplete="off"/></td>
                <td ></td>
                <td ></td>
                
                
                
            </tr>
            <tr class='dtblTh'>
               
                <th >Code </th>
                <th >Transporter </th>
                <th >Email </th>
                <th >Mobile </th>
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
    var tableId = 'transportermaster';
    var tableCols = [
        {"mData": "transportercode"}
        , {"mData": "transportername"}
        , {"mData": "transportermail"}
        , {"mData": "transportermobileno"}
        , {"mData": "edit"}
        , {"mData": "delete"}
    ];
</script>
