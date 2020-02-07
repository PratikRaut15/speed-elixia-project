<?php
$factoryid = '';
$transporterid = '';
if (isset($_SESSION['factoryid']) && $_SESSION['factoryid'] != '') {
  $factoryid = $_SESSION['factoryid'];
}
if (isset($_SESSION['transporterid']) && $_SESSION['transporterid'] != '') {
  $transporterid = $_SESSION['transporterid'];
}
$customerno = $_SESSION['customerno'];
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
  #proposedindentmaster_filter{display: none}
  .dataTables_length{display: none}
</style>
<br/>
<div class='container' >
  <div style="float:right;">
    <?php if (!isset($_SESSION['transporterid']) && empty($_SESSION['transporterid'])) { ?>
      <a href="tms.php?pg=add-proposed-indent">  <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;">Add Proposed Indent <img src="../../images/show.png"></button></a>
    <?php } ?>
    Export To Excel<button  onclick="Export_Proposed_Indent('<?php echo $customerno ?>', '<?php echo $factoryid ?>', '<?php echo $transporterid ?>')" > <img src="../../images/xls.gif"></button>
  </div>
</div>
<hr/>
<div class='entry' >
  <center>
    <input type='hidden' id='forTable' value='productionMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="proposedindentmaster" style="width: 90%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' style="width:80%;" name='delievry_id'  autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='factory' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='depot' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='vehicle' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='skumaping' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='weight' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='weight' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
          <td></td>
        </tr>
        <tr>
          <th width="8%">ID</th>
          <th >Vehicle Requirement Date</th>
          <th >Factory</th>
          <th>Depot</th>
          <th >Zone</th>
          <th >Transporter </th>
          <th >Proposed Vehicle</th>
          <th>Plant Indent Remark</th>
          <th>Transporter Status</th>
          <th >Actual Vehicle</th>
          <th >VehicleNo </th>
          <th >DriverNo</th>
          <th >Transporter Remark</th>
          <th>Factory Status</th>
          <th>Shipment No</th>
          <th>Shipment Remark</th>
          <th>CreatedOn</th>
          <th>View</th>
        </tr>
      </thead>
    </table>
  </center>
</div>
<script type='text/javascript'>
  var data = <?php echo json_encode($proposed_indents); ?>;
  var tableId = 'proposedindentmaster';
  var tableCols = [
    {"mData": "proposedindentid"}
    , {"mData": "date_required"}
    , {"mData": "factoryname"}
    , {"mData": "depotname"}
    , {"mData": "zonename"}
    , {"mData": "transportername"}
    , {"mData": "proposedvehiclecode"}
    , {"mData": "piremark"}
    , {"mData": "status"}
    , {"mData": "actualvehiclecode"}
    , {"mData": "vehicleno"}
    , {"mData": "driverno"}
    , {"mData": "transporterremarks"}
    , {"mData": "factorystatus"}
    , {"mData": "shipmentno"}
    , {"mData": "remark"}
    , {"mData": "created_on"}
    , {"mData": "edit"}
  ];
</script>
