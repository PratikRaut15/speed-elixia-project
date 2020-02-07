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
      <h3>Billing</h3>
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
          <td></td>



        </tr>
        <tr class='dtblTh'>
          <th>Customer</th>
          <th >Shipment No</th>
          <th >CN No</th>
          <th >Distance Traveled</th>
          <th >Invoice amount</th>
          <th >Status</th>
          <th ></th>
          
        </tr>
      </thead>
    </table>

  </center>
</div>

<script type='text/javascript'>
    
    
  var data = <?php 
  
  $transporters = array(
        array('customer' => 'Elixia Tech Solutions', 'shipmentno' => '9151234','cnno'=>'CN1080','distancetravel'=>'500','invoiceamt'=>'10000','status'=>'Pending For Approval',  'pdf' => '<a href="#"><img src="../../images/pdf_icon.png"></img></a>'),
        array('customer' => 'Elixia Tech Solutions', 'shipmentno' => '9151235','cnno'=>'CN1500','distancetravel'=>'200','invoiceamt'=>'5000','status'=>'Approved', 'pdf' => '<a href="#"><img src="../../images/pdf_icon.png"></img></a>'),
        array('customer' => 'Elixia Tech Solutions', 'shipmentno' => '9151236','cnno'=>'CN2100','distancetravel'=>'700','invoiceamt'=>'15000','status'=>'Approved','pdf' => '<a href="#"><img src="../../images/pdf_icon.png"></img></a>')
    );
  echo json_encode($transporters); ?>;
  var tableId = 'skumaster';
  var tableCols = [
    {"mData": "customer"}
    , {"mData": "shipmentno"}
    , {"mData": "cnno"}
    , {"mData": "distancetravel"}
    , {"mData": "invoiceamt"}
    , {"mData": "status"}
    , {"mData": "pdf"}

  ];
  
  </script>
