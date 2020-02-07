<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
  #billmaster_filter{display: none}
  .dataTables_length{display: none}
</style>
<br/>
<div class='entry' >
  <div style="float:right;">
    <?php if (!isset($_SESSION['transporterid']) && empty($_SESSION['transporterid'])) { ?>
      <a href="tms.php?pg=bills">  <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;">Add Bill <img src="../../images/show.png"></button></a>
    <?php } ?>
  </div>
  <center>
    <table class='display table table-bordered table-striped table-condensed' id="billmaster" style="width: 100%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' name='bill_no' autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='bill_date' autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='invoice_location' autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='depot_location' autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='vendor' autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='final_bill_amount' autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='final_bill_amount' autocomplete="off"/></td>

        </tr>
        <tr class='dtblTh'>
          <th>Bill No</th>
          <th>Bill Date</th>
          <th>Invoice Location</th>
          <th>Depot Location</th>
          <th>Vendor</th>
          <th>Final Bill Amount</th>
          <th>Last Saved</th>
          <th>View</th>
          <th>Edit</th>
          <th>Delete</th>
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
<br/>
<script type='text/javascript'>
  var data = <?php echo json_encode($billDetails); ?>;
  var tableId = 'billmaster';
  var tableCols = [
    {"mData": "bill_no"}
    , {"mData": "bill_date"}
    , {"mData": "invoice_location"}
    , {"mData": "depotname"}
    , {"mData": "vendor"}
    , {"mData": "final_bill_amount"}
    , {"mData": "savedon"}
    , {"mData": "view"}
    , {"mData": "edit"}
    , {"mData": "delete"}
  ];
</script>