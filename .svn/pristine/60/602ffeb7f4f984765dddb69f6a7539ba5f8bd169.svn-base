<?php
$max_columns = 7;
$max_row = 2000;
$column_names = array(
   'A' => 'skucode',
   'B' => 'description',
   'C' => 'type',
   'D' => 'volume',
   'E' => 'weight',
   'F' => 'netgross'
);
$valid_file = array('xls', 'xlsx');
$valid_size = 2000000;
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<script>
  var valid_file_xls =<?php echo json_encode($valid_file); ?>;
  var valid_size_xls =<?php echo $valid_size; ?>;
</script>
<style>
  #skumaster_filter{display: none}
  .dataTables_length{display: none}
  .ajax_response_8{
    margin-left: 640px;
  }
</style>
<br/>


<div class='container' >
  <center>
    <input type='hidden' id='forTable' value='vehicleMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="skumaster" style="width: 90%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' style="width:90%;" name='plant_id'  autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_name' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td colspan="2"></td>



        </tr>
        <tr class='dtblTh'>
          <th>Sr.No</th>
          <th>Company Code</th>
          <th >Company Name</th>
          <th >No. Of Drop Points</th>
          <th >No. Of Users</th>
          <th >Created By</th>
          <th >Created On</th>
          <th >Edit</th>
          <th >Delete</th>

        </tr>
      </thead>
    </table>

  </center>
</div>

<script type='text/javascript'>


  var data = <?php

  $transporters = array(
        array('srno' => '1','code' => 'CT001', 'company' => 'Suyog Logistics','type'=>'DRY','drop'=>'15','user'=>'2', 'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00','edit' => '<a href="sectms.php?pg=editcustomer"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '2','code' => 'CT002', 'company' => 'Aruna Travels','type'=>'REFER','drop'=>'26','user'=>'7', 'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00','edit' => '<a href="sectms.php?pg=editcustomer"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '3','code' => 'CT003', 'company' => 'Sanghvi Transline ','type'=>'DRY','drop'=>'31','user'=>'17', 'createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00','edit' => '<a href="sectms.php?pg=editcustomer"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>'),
        array('srno' => '4','code' => 'CT004', 'company' => 'Chheda Cargo','type'=>'DRY','drop'=>'8','user'=>'9','createdby' => 'Dinesh Joil', 'createdon' => '2017-05-08 13:15:00','edit' => '<a href="sectms.php?pg=editcustomer"><i class="icon-pencil"></i></a>', 'delete' => '<a href="#"><i class="icon-trash"></i></a>')

    );
  echo json_encode($transporters); ?>;
  var tableId = 'skumaster';
  var tableCols = [
    {"mData": "srno"}
    ,{"mData": "code"}
    , {"mData": "company"}
    , {"mData": "drop"}
    , {"mData": "user"}


    , {"mData": "createdby"}
    , {"mData": "createdon"}
    , {"mData": "edit"}
    , {"mData": "delete"}


  ];

  function gettype(value){
      var data1=value.value;
      if(data1 == 1){
          jQuery('#temp').hide();
      }else{
          jQuery('#temp').show();
      }
  }
</script>
