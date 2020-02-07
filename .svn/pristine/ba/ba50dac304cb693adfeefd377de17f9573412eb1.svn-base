<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #indentmaster_filter{display: none}
    .dataTables_length{display: none}
</style>
<br/>

<div class='container'>

    <div style='clear:both'></div>
    <br>
    <center>
        <h3>Fuel Request Approval</h3><br>
        <input type='hidden' id='forTable' value='productionMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="indentmaster" >
            <thead>
                <tr>
                    <td><input type="text" class='search_init' style="width:80%;" name='triplogno'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' style="width:80%;" name='shipment'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' style="width:80%;" name='customer'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='addfuelcost' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='date' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='status' style="width:90%;" autocomplete="off"/></td>
                    <td></td>
                </tr>
                <tr class='dtblTh'>
                    <th width="8%">Triplog No</th>
                    <th width="8%">Shipment </th>
                    <th width="8%">Customer </th>
                    <th >Add Fuel Cost </th>
                    <th >Date</th>
                    <th >Status</th>
                    <th >Approve/Reject</th>
                </tr>
            </thead>
        </table>

    </center>
</div>


<?php
//$Date = date('y-m-d H:i:s');
//$date = date('Y-m-d H:i:s', strtotime($Date. ' + 2 days')); ;
?>
<script type='text/javascript'>
    var data = <?php
$transporters = array(
    array('triplogno' => 'TL0001',
        'shipment' => '9151234',
        'customer' => 'Suyog Logistics',
        'addfuelcost' => '1500',
        'date' => '01-05-2017',
        'status' => 'Pending For Apporval',
        'edit' => '<a href="#"><i class="icon-pencil"></i></a>'
        ),
    array('triplogno' => 'TL0002',
        'shipment' => '9151235',
        'customer' => 'Chheda Cargo',
        'addfuelcost' => '2600',
        'date' => '01-05-2017',
        'status' => 'Approved',
        'edit' => ''
        ),

    array('triplogno' => 'TL0003',
        'shipment' => '91578454',
        'customer' => 'Elixiatech2',
        'addfuelcost' => '3000',
        'date' => '03-05-2017',
        'status' => 'Pending For Apporval',
        'edit' => '<a href="#"><i class="icon-pencil"></i></a>'
        ),

    array('triplogno' => 'TL0004',
        'shipment' => '91554654',
        'customer' => 'Chheda Cargo',
        'addfuelcost' => '1000',
        'date' => '04-05-2017',
        'status' => 'Pending For Apporval',
        'edit' => '<a href="#"><i class="icon-pencil"></i></a>'
        ),

    array('triplogno' => 'TL0005',
        'shipment' => '9155465',
        'customer' => 'Sanghvi Transline ',
        'addfuelcost' => '2500',
        'date' => '06-05-2017',
        'status' => 'Pending For Apporval',
        'edit' => '<a href="#"><i class="icon-pencil"></i></a>'
        ),


    array('triplogno' => 'TL0006',
        'shipment' => '91565654',
        'customer' => 'Aruna Travels',
        'addfuelcost' => '5000',
        'date' => '07-05-2017',
        'status' => 'Pending For Apporval',
        'edit' => '<a href="#"><i class="icon-pencil"></i></a>'
        ),

    array('triplogno' => 'TL0007',
        'shipment' => '9159845',
        'customer' => 'Sanghvi Transline ',
        'addfuelcost' => '4500',
        'date' => '07-05-2017',
        'status' => 'Pending For Apporval',
        'edit' => '<a href="#"><i class="icon-pencil"></i></a>'
        ),
);
echo json_encode($transporters);
?>;
    var tableId = 'indentmaster';
    var tableCols = [
        {"mData": "triplogno"}
        , {"mData": "shipment"}
        , {"mData": "customer"}
        , {"mData": "addfuelcost"}
        , {"mData": "date"}
        , {"mData": "status"}
        , {"mData": "edit"}
    ];
</script>
