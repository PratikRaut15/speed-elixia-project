<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #indentmaster_filter{display: none}
    .dataTables_length{display: none}
</style>
<div class='container'>
    <center>
        <h3>Trips</h3><br>
        <input type='hidden' id='forTable' value='productionMaster'/>
        <table class='display table table-bordered table-striped table-condensed' id="indentmaster" >
            <thead>
                <tr>
                    <td><input type="text" class='search_init' style="width:80%;" name='shipmentno'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' style="width:80%;" name='shipmentno'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' style="width:80%;" name='shipmentno'  autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='reference' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='status' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='statusdate' style="width:90%;" autocomplete="off"/></td>

                    <td><input type="text" class='search_init' name='vehiclerequirementdate' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='createdon' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='print' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='productdetails' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='estimatedcost' style="width:90%;" autocomplete="off"/></td>
                    <td><input type="text" class='search_init' name='vehicleoccupancy' style="width:90%;" autocomplete="off"/></td>

                </tr>
                <tr class='dtblTh'>
                    <th width="8%">Sr.No</th>
                    <th width="8%">Triplog No</th>
                    <th width="8%">Shipment No</th>
                    <th >Reference #</th>
                    <th >Status</th>
                    <th >Status Date</th>

                    <th >Vehicle Requirement Date</th>
                    <th >Created On</th>
                    <th >Print Consignor Note</th>
                    <th>Product Details</th>
                    <th >Expense Calculator</th>
                    <th >Vehicle Occupancy</th>

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
    array('srno' => '1',
        'triplogno' => 'TL0001',
        'shipmentno' => '1234TT',
        'reference' => 'R123456',
        'status' => 'Approved',
        'statusdate' => '24-April-2017',
        'vehiclerequirementdate' => '24-April-2017',
        'createdon' => '24-April-2017',
        'print' => '<a target="_blank" href="../../customer/2/LR/LR.PDF"><img src="../../images/pdf_icon.png"></i></a>',
        'productdetails' => '<a data-toggle="modal"  href="#productdetails"><img src="../../images/icon_pages.png" width="32px;" height="32px;"></i></a>',
        'estimatedcost' => '<a data-toggle="modal" href="#estimatedpop"><img src="../../images/icon_money.png"></i></a>',
        'vehicleoccupancy' => '<a data-toggle="modal" href="#vehocc"><img src="../../images/truckimg/truck_20.png" width="32px;" height="32px;"></i></a>',
        'view' => '<a href="#"><img src="../../images/history.png"></i></a>',
        'edit' => '<a href="#"><i class="icon-pencil"></i></a>',
        'createdby' => 'Dinesh Joil'),

    array('srno' => '2',
          'triplogno' => 'TL0002',
        'shipmentno' => 'S789001',
        'reference' => 'REF45712',
        'status' => 'Approved',
        'statusdate' => '29-April-2017',
        'vehiclerequirementdate' => '29-April-2017',
        'createdon' => '29-April-2017',
        'print' => '<a target="_blank" href="../../customer/2/LR/LR.PDF"><img src="../../images/pdf_icon.png"></i></a>',
        'productdetails' => '<a data-toggle="modal"  href="#productdetails"><img src="../../images/icon_pages.png" width="32px;" height="32px;"></i></a>',
        'estimatedcost' => '<a data-toggle="modal" href="#estimatedpop"><img src="../../images/icon_money.png"></i></a>',
        'vehicleoccupancy' => '<a data-toggle="modal" href="#vehocc"><img src="../../images/truckimg/truck_20.png" width="32px;" height="32px;"></i></a>',
        'view' => '<a href="#"><img src="../../images/history.png"></i></a>',
        'edit' => '<a href="#"><i class="icon-pencil"></i></a>',
        'createdby' => 'Dinesh Joil'),


    array('srno' => '3',
          'triplogno' => 'TL0003',
        'shipmentno' => 'S789002',
        'reference' => 'REF45745',
        'status' => 'Approved',
        'statusdate' => '05-May-2017',
        'vehiclerequirementdate' => '05-May-2017',
        'createdon' => '02-May-2017',
        'print' => '<a target="_blank" href="../../customer/2/LR/LR.PDF"><img src="../../images/pdf_icon.png"></i></a>',
        'productdetails' => '<a data-toggle="modal"  href="#productdetails"><img src="../../images/icon_pages.png" width="32px;" height="32px;"></i></a>',
        'estimatedcost' => '<a data-toggle="modal" href="#estimatedpop"><img src="../../images/icon_money.png"></i></a>',
        'vehicleoccupancy' => '<a data-toggle="modal" href="#vehocc"><img src="../../images/truckimg/truck_20.png" width="32px;" height="32px;"></i></a>',
        'view' => '<a href="#"><img src="../../images/history.png"></i></a>',
        'edit' => '<a href="#"><i class="icon-pencil"></i></a>',
        'createdby' => 'Dinesh Joil'),




);
echo json_encode($transporters);
?>;
    var tableId = 'indentmaster';
    var tableCols = [
        {"mData": "srno"}
        ,{"mData": "triplogno"}
        ,{"mData": "shipmentno"}
        , {"mData": "reference"}
        , {"mData": "status"}
        , {"mData": "statusdate"}


        , {"mData": "vehiclerequirementdate"}
        , {"mData": "createdon"}
        , {"mData": "print"}

        , {"mData": "productdetails"}
        , {"mData": "estimatedcost"}
        , {"mData": "vehicleoccupancy"}
    ];
</script>


<div class="modal hide" id="estimatedpop" role="dialog" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Expense Calculator</h4>
            </div>
            <div class="modal-body">
                <div class="clear"></div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Vehicle </label>
                   <label>MH04 1234 </label>

                </div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Average </label>
                   <label>9 Km/Ltr </label>

                </div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">From </label>
                     <label>Mumbai </label>
                </div>
                <div class="clear"></div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">To </label>
                    <label>Pune, Nashik </label>
                </div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Distance </label>
                    <label>149KM </label>
                </div>
                <div class="clear"></div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Fuel Cost </label>
                    <label> 8000 Rs </label>
                </div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Toll Cost </label>
                    <label>1000 Rs </label>
                </div>
                <div class="clear"></div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">AC Fuel Cost </label>
                    <label>2000Rs </label>

                </div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Way side cost</label>
                    <input type="text" id="distance" name="distance" value="" readonly  maxlength="50" value='' >
                </div>
                <div class="clear"></div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Total Cost</label>
                    <label>12000</label>
                </div>
                <div style="width:50%; float:left;">
                    <button class="btn-secondary">Send For Approval</button>
                </div>

            </div>
            <div class="modal-footer">
                <div class="clear"></div>
                <div>
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal hide" id="productdetails" role="dialog" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Product Details</h4>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <th>Product Code</th>
                        <th>Produst Name</th>
                        <th>Quantity</th>
                        <th>Weight (In Kgs)</th>
                        <th>From</th>
                        <th>To</th>
                    </tr>
                    <tr>
                        <td>324688</td>
                        <td>Caramello crumb</td>
                        <td>150</td>
                        <td>200</td>
                        <td>Mumbai</td>
                        <td>Pune</td>
                    </tr>

                    <tr>
                        <td>324705</td>
                        <td>MANGO FLAVOUR</td>
                        <td>50</td>
                        <td>60</td>
                        <td>Mumbai</td>
                        <td>Pune</td>
                    </tr>


                    <tr>
                        <td>323564</td>
                        <td>CDM SILK RAORANGE PEEL145G COFE TRT PRMO</td>
                        <td>25</td>
                        <td>120</td>
                        <td>Mumbai</td>
                        <td>Pune</td>
                    </tr>

                    <tr>
                        <td>10001604</td>
                        <td>25% BOURNVILLE LIQUOR</td>
                        <td>5</td>
                        <td>250</td>
                        <td>Mumbai</td>
                        <td>Pune</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <div class="clear"></div>
                <div>
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal hide" id="vehocc" role="dialog" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Vehicle Occupancy</h4>
            </div>
            <div class="modal-body">

                <img src="../../images/truckimg/truck_60.png" >
            </div>
            <div class="modal-footer">
                <div class="clear"></div>
                <div>
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
