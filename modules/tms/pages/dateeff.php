<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
  #dateeff_filter{display: none}
  .dataTables_length{display: none}
</style>
<br/>
<div class='container' >
  <center>
    <h3>Date Wise Placement Efficiency </h3>
    <form method="post" id="frmVendorEfficiency" action="<?php $_SERVER['PHP_SELF'] ?>">
      <table>
        <tr>
          <td>Start Date</td>
          <td>End Date</td>
          <td>SKU</td>
          <td>Factory</td>
          <td>Zone</td>
          <td></td>
        </tr>

        <tr>
          <td><input id="SDate" name="SDate" type="text" value="" /></td>
          <td><input id="EDate" name="EDate" type="text" value="" /></td>
          <td>
            <input type="text" style="width: 120px;" name="type_name" id="type_name" value="" maxlength="50" autocomplete="off"/>
            <input type="hidden" name="typeid" id="typeid" value="" maxlength="50"/>
          </td>
          <td><?php
            if (isset($_SESSION['factoryid']) && !empty($_SESSION['factoryid'])) {
             $objFactoty = new Factory();
             $objFactoty->customerno = $_SESSION["customerno"];
             $objFactoty->factoryid = $_SESSION['factoryid'];
             $plant = get_factory($objFactoty);
             ?>
             <input type="text"  name="factory_name" id="factory_name" value="<?php echo $plant[0][factoryname] ?>" maxlength="50" autocomplete="off" readonly=""/>
             <input type="hidden" name="factoryid" id="factoryid" value="<?php echo $_SESSION['factoryid'] ?>" maxlength="50"/>
             <?php
            } else {
             ?>
             <input type="text" name="factory_name" id="factory_name" value="" maxlength="50" autocomplete="off"/>
             <input type="hidden" name="factoryid" id="factoryid" value="" maxlength="50"/>
             <?php
            }
            ?></td>
          <td><input type="text" name="zonename" id="zonename" value="" maxlength="50" autocomplete="off"/>
            <input type="hidden" name="zoneid" id="zoneid" value="" maxlength="50"/></td>
        <input type="hidden" name="filter" id="filter" value="1"/></td>
        <td><input type="button" name="filterVendorEff" class="btn-primary" id="btn-filter-vendoreff" value="Search" onclick="ValidateVendorEfficiency();" /></td>
        </tr>
      </table>
    </form>
    <br/>
    <input type='hidden' id='forTable' value='plantMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="dateeff" style="width: 80%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' name='daterequired' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='vehiclesindentted' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='vehicleplaced' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='efficiency' style="width:90%;" autocomplete="off"/></td>
        </tr>
        <tr class='dtblTh'>
          <th >Date Required</th>
          <th >Vehicle Indented</th>
          <th >Vehicle Placed </th>
          <th>Placement Efficiency</th>
        </tr>
      </thead>
    </table>
  </center>
</div>

<script type='text/javascript'>
 var data = <?php echo json_encode($daterequiredEffArray); ?>;
 var tableId = 'dateeff';
 var tableCols = [
   {"mData": "requireddate"}
   , {"mData": "totalindent"}
   , {"mData": "placed"}
   , {"mData": "efficiency"}
 ];
</script>