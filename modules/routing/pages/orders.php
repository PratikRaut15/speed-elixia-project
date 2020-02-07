<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables.css">
<style>
    table tr:hover td{
        background: inherit !important;
    }
    .squareLbl{
        min-height: 15px;
        min-width: 15px;
        float:left;
    }
</style>
<?php
$data = pullorders();
?>
<div class="container-fluid">
    <?php $today = date('Y-m-d'); ?>
    <div style='font-size:13px;float:left;margin:0px; text-align: left;  font-weight:bold;' >
        <ul>
            <li>AC: Accuracy</li>
            <li><div class="squareLbl" style="background:#70DB70" ></div>: Order Delivered</li>
            <li><div class="squareLbl" style="background:#FFB2B2"></div>: Order Cancelled</li>
        </ul>
<!--  <a href='javascript:void(0)' onclick="html2xls(<?php echo $_SESSION['customerno']; ?>, <?php echo $_SESSION['userid']; ?>);return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>-->
    </div><br/>
    <table class='display' id="assignOrders" style="width:70%">
        <thead>
            <tr>
                <td><input type="text" class='search_init' name='order_id' id='order_id' style='width:30px;'/></td>
                <!--<td><input type="text" class='search_init' name='accuracy' style='width:10px;'/></td>-->
                <td><input type="text" class='search_init' name='zone' style='width:50px;'/></td>
                <td><input type="text" class='search_init' name='area' style='width:100px;'/></td>
                <td><input type="text" class='search_init' name='signlocation' style='width:100px;'/></td>
<!--            <td><input type="text" class='search_init' name='flat' style='width:30px;'/></td>
                <td><input type="text" class='search_init' name='building' style='width:30px;'/></td>
                <td><input type="text" class='search_init' name='city' style='width:50px;'/></td>
                <td><input type="text" class='search_init' name='landmark' style='width:80px;'/></td>-->
                <td><input type="text" class='search_init' name='slot' style='width:10px;'/></td>
                <td><input type="text" class='search_init' name='delivery_date' id='DelDate' style='width:80px;' value='<?php echo $today; ?>'/></td>
<!--            <td><input type="text" class='search_init' name='signlocation' style='width:50px;'/></td>-->
                <td><input type="text" class='search_init' name='order_date' id='OrderDate' style='width:80px;' /></td>
                <td><input type="text" class='search_init' name='is_delivered' id='orderStatus' style='width:80px;' /></td>
<!--            <td><input type="text" class='search_init' name='deliveryboy' id='deliveryboy' style='width:80px;' /></td>-->

                <td><input type="text" class='search_init' name='' id='deliveryboy' style='width:80px;' /></td>
                <td></td>
            </tr>
            <tr>
                <th>Bill No.</th>
                <!--
                <th>AC</th>
                -->
                <th>Zone</th>
                <th>Area</th>
                <th>Sign location</th>
<!--                <th>Flat</th>
                <th>Building</th>
                <th>City</th>-->
<!--                <th>Landmark</th>-->
                <th>Slot</th>
                <th>Delivery Date</th>
<!--               <th>Sign location</th>-->
                <th>Order Date</th>
                <th>Status</th>
                <th>Delivery Boy</th>

<!--                <th>Photo</th>-->
                <th style="background: none ; width: 100px;">Action</th>
                <!--<th>Route id</th>
                <th>Id</th>-->
            </tr>
        </thead>
    </table>

    <!-- order modal starts-->
    <div class="modal fade" id="vOrderModal" tabindex="-1" role="dialog" aria-labelledby="vOrderModalLabel" aria-hidden="true" >
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        <span class="sr-only"></span>
                    </button>
                    <h4 class="modal-title">Bill No. - <span id="billNoSpn"></span></h4>
                </div>
                <input type="hidden" value='<?php echo $_SESSION['customerno']; ?>' id='customerno'/>
                <div class="modal-body" style="min-height: 200px; max-height: 400px; width:500px;">


                    <table class="table table-condensed" >
                        <thead><tr><th colspan="100%">Order Details</th></tr></thead>

                        <tbody id="ordTbdy">

                        </tbody>

                    </table>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id='popClose'>Close</button>
                </div>

            </div>
        </div>
    </div>
    <!-- order modal ends -->

</div>
<script>
    var today = "<?php echo $today; ?>";
</script>



<script>
    jQuery(document).ready(function () {
        jQuery('#DelDate').datepicker({format: "yyyy-mm-dd", autoclose: true, });

        jQuery('#OrderDate').datepicker({format: "yyyy-mm-dd", autoclose: true, });

        var sortColumn = 10;

        var data = <?php echo json_encode($data); ?>;
        var tableId = 'assignOrders';
        var tableCols = [
            {"mData": "order_id"}
            , {"mData": "zonename"}
            , {"mData": "areaname"}
            , {"mData": "signlocation"}
//      , {"mData": "flat"}
//      , {"mData": "building"}
//      , {"mData": "city"}
//      , {"mData": "landmark"}
            , {"mData": "slot"}
            , {"mData": "delivery_date"}
            //, {"mData": "signlocation"}
            , {"mData": "orderdate"}
            , {"mData": "statusname"}
            , {"mData": "delboyname"}

//        , {"mData": "photo"}
            , {"mData": "editlink"}
        ];
        CreateDataTable(data, tableId, tableCols);
    });


    function CreateDataTable(data, tableId, tableCols)
    {
        var sortColumn = 0;
        var oTable = jQuery('#' + tableId + '').dataTable({
            "processing": true,
            "aaData": data,
            "aoColumns": tableCols,
            "order": [[0, "desc"]],
            "iDisplayLength": 200,
            "bLengthChange": false, //used to hide the property
            "bFilter": true,
            "dom": 'Bfrtip',
            "stateSave": true,
            buttons: [
                {
                    extend: 'csvHtml5',
                    title: 'orderreport'
                }
            ],
            "fnDrawCallback": function (oSettings) {
                statusColor2();
            },
            "fnInitComplete": function () {
                var oSettings = jQuery('#' + tableId + '').dataTable().fnSettings();
                for (var i = 0; i < oSettings.aoPreSearchCols.length; i++) {
                    if (oSettings.aoPreSearchCols[i].sSearch.length > 0) {
                        jQuery("thead input")[i].value = oSettings.aoPreSearchCols[i].sSearch;
                        jQuery("thead input")[i].className = "";
                    }
                }
            },
        });

        //Add filter columns
        jQuery("thead input").keyup(function () {
            oTable.fnFilter(this.value, jQuery("thead input").index(this));
        });

        jQuery('#DelDate').change(function () {
            oTable.fnFilter(this.value, jQuery("thead input").index(this));
        });

        jQuery('#OrderDate').change(function () {
            oTable.fnFilter(this.value, jQuery("thead input").index(this));
        });

        jQuery('#orderStatus').change(function () {
            //alert(jQuery(this).parent().index()); return false;
            oTable.fnFilter(this.value, jQuery(this).parent().index());
        });
    }

    function statusColor2() {
        var odrStat = 7;
        var rows = jQuery('tr');
        var rowVal = '';
        jQuery(rows).each(function (index, value) {
            rowVal = jQuery(value).find('td:eq(7)').html();
            if (rowVal == 'Cancelled') {
                jQuery(value).find('td:eq(' + odrStat + ')').parent().css('backgroundColor', '#FFB2B2');
            }
            else if (rowVal == 'Delivered') {
                jQuery(value).find('td:eq(' + odrStat + ')').parent().css('backgroundColor', '#70DB70');
            }
        });
    }


</script>



