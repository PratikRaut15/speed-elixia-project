<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #indentmaster_filter{display: none}
    .dataTables_length{display: none}
</style>
<br/>

<div class='container'>
    <center>
    <div style='alignment-adjust: central;'>
        <h3>Fuel Request Apporval</h3>
        <table style=" border: none; width:50%" >
            <tr>
                <td>
                    <span class="add-on">Triplog No </span>
                </td>
                <td>
                    <input type="text" name="triplogno" id="triplogno" value=""/>
                </td>
                <td>
                    <span class="add-on">Shipment No </span>
                </td>
                <td>
                    <input type="text" name="shipmentno" id="shipmentno" value=""/>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="add-on">Customer </span>
                </td>
                <td>
                    <input type="text" name="customer" id="customer" value=""/>
                </td>
                <td>
                    <span class="add-on">Add Fuel Cost </span>
                </td>
                <td>
                     <input type="text" name="addfuelcost" id="addfuelcost" value=""/>
                </td>
            </tr>
            <tr> <td>
                    <span class="add-on">Date </span>
                </td>
                <td>
                    <input type="text" name="date" id="date" value=""/>
                </td>
             <tr>
            <tr>
                <td colspan="4"><button class='btn-secondary'>Send For Approval</button></td>

            </tr>
        </table>
    </div>
        </center>
    <div style='clear:both'></div>
    <br>

</div>


<?php
//$Date = date('y-m-d H:i:s');
//$date = date('Y-m-d H:i:s', strtotime($Date. ' + 2 days')); ;
?>
<script type='text/javascript'>
    var data = <?php
$transporters = array(
    array('triplogno' => '1234RR',
        'shipment' => 'R456565',
        'customer' => 'Elixiatech',
        'addfuelcost' => '5000',
        'date' => '20-April-2017',
        'status' => 'Pending For Apporval',
        ), array('triplogno' => '1234TT',
        'shipment' => 'R47775',
        'customer' => 'Elixiatech2',
        'addfuelcost' => '6000',
        'date' => '23-April-2017',
        'status' => 'Approved',
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
    ];
</script>
