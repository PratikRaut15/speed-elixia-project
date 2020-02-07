<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style>
  #skumaster_filter{display: none}
  .dataTables_length{display: none}
  .ajax_response_8{
    margin-left: 640px;
  }
</style>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<div class='container' >
  <center>
      <h3>Trip Closure</h3>
    <input type='hidden' id='forTable' value='vehicleMaster'/>
    <table class='display table table-bordered table-striped table-condensed' id="skumaster" style="width: 90%;" >
      <thead>
        <tr>
          <td><input type="text" class='search_init' style="width:90%;" name='plant_id'  autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_name' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_name' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_name' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_name' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td><input type="text" class='search_init' name='plant_location' style="width:90%;" autocomplete="off"/></td>
          <td colspan="2"></td>



        </tr>
        <tr class='dtblTh'>
          <th>Trip Log No</th>
          <th >Shipment No</th>
          <th >Trip Date</th>
          <th >Vehcile No</th>
          <th >No Of Drop Points</th>
          <th >Budgeted Cost</th>
          <th >Budgeted Km</th>
          <th >Budgeted Hours</th>
          <th >Actual Cost</th>
          <th >Actual Km</th>
          <th >Actual Hours</th>
          <th >Details</th>
          <th >Close</th>


        </tr>
      </thead>
    </table>

  </center>
</div>

<script type='text/javascript'>


  var data = <?php

  $transporters = array(
        array('triplogno' => 'TL0001', 'shipmentno' => '9151234', 'tripdate' => '01-05-2017','vehicle' => 'MH 04 1451','drop' => '2','bc'=>'10000','bk'=>'200','bh'=>'4','ac'=>'12000','ak'=>'221', 'ah'=>'5', 'edit' => '<a href="#"><img src="../../images/history.png"></img></a>', 'delete' => '<button>Close</button>'),
        array('triplogno' => 'TL0002', 'shipmentno' => '9151235', 'tripdate' => '02-05-2017','vehicle' => 'MH 03 5466','drop' => '5','bc'=>'2500','bk'=>'250','bh'=>'6','ac'=>'4080','ak'=>'259', 'ah'=>'7', 'edit' => '<a href="#"><img src="../../images/history.png"></img></a>', 'delete' => '<button>Close</button>'),
        array('triplogno' => 'TL0003', 'shipmentno' => '91578454', 'tripdate' => '02-05-2017','vehicle' => 'MH 05 0451','drop' => '2','bc'=>'15651','bk'=>'400','bh'=>'8','ac'=>'17000','ak'=>'450','ah'=>'10',  'edit' => '<a href="#"><img src="../../images/history.png"></img></a>', 'delete' => '<button>Close</button>'),
        array('triplogno' => 'TL0004', 'shipmentno' => '91554654', 'tripdate' => '04-05-2017','vehicle' => 'MH 02 6504','drop' => '4','bc'=>'25647','bk'=>'375','bh'=>'8','ac'=>'19400','ak'=>'390','ah'=>'8',  'edit' => '<a href="#"><img src="../../images/history.png"></img></a>', 'delete' => '<button>Close</button>'),
        array('triplogno' => 'TL0005', 'shipmentno' => '9155465', 'tripdate' => '04-05-2017','vehicle' => 'MH 04 4510','drop' => '3','bc'=>'4512','bk'=>'225','bh'=>'5','ac'=>'6245','ak'=>'235','ah'=>'4',  'edit' => '<a href="#"><img src="../../images/history.png"></img></a>', 'delete' => '<button>Close</button>'),
        array('triplogno' => 'TL0006', 'shipmentno' => '91565654', 'tripdate' => '05-05-2017','vehicle' => 'MH 04 9850','drop' => '9','bc'=>'124587','bk'=>'500','bh'=>'12','ac'=>'90400','ak'=>'540','ah'=>'10',  'edit' => '<a href="#"><img src="../../images/history.png"></img></a>', 'delete' => '<button>Close</button>'),
        array('triplogno' => 'TL0007', 'shipmentno' => '9159845', 'tripdate' => '06-05-2017','vehicle' => 'MH 06 6507','drop' => '2','bc'=>'9856','bk'=>'250','bh'=>'6','ac'=>'9865','ak'=>'270','ah'=>'6',  'edit' => '<a href="#"><img src="../../images/history.png"></img></a>', 'delete' => '<button>Close</button>'),
        array('triplogno' => 'TL0008', 'shipmentno' => '9151245', 'tripdate' => '06-05-2017','vehicle' => 'MH 02 9512','drop' => '5','bc'=>'2154','bk'=>'280','bh'=>'7','ac'=>'3512','ak'=>'285','ah'=>'6',  'edit' => '<a href="#"><img src="../../images/history.png"></img></a>', 'delete' => '<button>Close</button>'),
        array('triplogno' => 'TL0009', 'shipmentno' => '9156541', 'tripdate' => '06-05-2017','vehicle' => 'MH 02 1592','drop' => '3','bc'=>'21654','bk'=>'600','bh'=>'15','ac'=>'19600','ak'=>'621','ah'=>'14',  'edit' => '<a href="#"><img src="../../images/history.png"></img></a>', 'delete' => '<button>Close</button>'),
        array('triplogno' => 'TL0010', 'shipmentno' => '91598745', 'tripdate' => '08-05-2017','vehicle' => 'MH 06 7530','drop' => '2','bc'=>'40000','bk'=>'700','bh'=>'20','ac'=>'43568','ak'=>'179','ah'=>'17',  'edit' => '<a href="#"><img src="../../images/history.png"></img></a>', 'delete' => '<button>Close</button>')
    );
  echo json_encode($transporters); ?>;
  var tableId = 'skumaster';
  var tableCols = [
    {"mData": "triplogno"}
    , {"mData": "shipmentno"}
    , {"mData": "tripdate"}
    , {"mData": "vehicle"}
    , {"mData": "drop"}
    , {"mData": "bc"}
    , {"mData": "bk"}
    , {"mData": "bh"}
    , {"mData": "ac"}
    , {"mData": "ak"}
    , {"mData": "ah"}
    , {"mData": "edit"}
    , {"mData": "delete"}


  ];

  </script>
