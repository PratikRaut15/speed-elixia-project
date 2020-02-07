<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
  #plantmaster_filter{display: none}
  .dataTables_length{display: none}
  .ajax_response_3{
    margin-left: 610px;
  }
</style>
<br/>
<div class='container' >
  <center>
    <form style='display:inline;width: 80%;' method="post" action="action.php?action=add-plant" >
      <div class="input-prepend ">
        <span class="add-on" style="text-align: left;">Factory Code </span>
        <input type="text" name="plantcode" id="plantcode" value="" maxlength="10"/>
        <span class="add-on" style="text-align: left;">Factory / Plant </span>
        <input type="text" name="plantname" id="plantname" value="" maxlength="50"/>
        <span class="add-on" style="text-align: left;"> Zone</span>
        <input type="text" name="zonename" id="zonename" value="" maxlength="25" autocomplete="off"/>
        <input type="hidden" name="zoneid" id="zoneid" value="" maxlength="50"/>
        <div id="display1" class="listlocation" style="margin-left: 50px; "></div>
        <input style='display:inline;' type='submit' class="btn  btn-primary" value='  Add Factory / Plant  '/>
      </div>
    </form>
  </center>
</div>
<hr/>
<div class='container' >
  <center>
    <input type='hidden' id='forTable' value='plantMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="plantmaster" style="width: 80%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' name='plant_name' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_name' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td colspan="2"></td>
        </tr>
        <tr class='dtblTh'>
          <th >Factory Code</th>
          <th >Factory / Plant</th>
          <th >Zone </th>
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
 var data = <?php echo json_encode($plants); ?>;
 var tableId = 'plantmaster';
 var tableCols = [
   {"mData": "factorycode"}
   , {"mData": "factoryname"}
   , {"mData": "zonename"}
   , {"mData": "edit"}
   , {"mData": "delete"}
 ];
</script>