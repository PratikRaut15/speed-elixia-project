<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<?php
$deliverydata = getdelivery_details();
?>

<?php $today = date('Y-m-d'); ?>
<div class="container" width='70%'>
    <br>
    <h3>Delivery Report</h3>
    <table class='display' id="viewDelivery">
        <thead>
            <tr>
                <td><input type="text" class='search_init' name='orderid' id="orderid"/></td>
<!--            <td><input type="text" class='search_init' name='vehicleno' /></td>-->
                <td><input type="text" class='search_init' name='deliveryboy' /></td>
                <td><input type="text" class='search_init' name='slot' /></td>
                <td><input type="text" class='search_init' name='deliverylocation' /></td>
<!--            <td><input type="text" class='search_init' name='deliverytime' /></td>-->
                <td><input type="text" class='search_init' name='deliverytime' id='deliverytime' style='width:80px;' value='<?php echo $today; ?>'/></td>
            </tr>
            <tr>
                <th>Order Id</th>
<!--                <th>Vehicle No </th>-->
                <th>Delivery Boy</th>
                <th>Slot</th>
                <th>Delivery Location</th>
                <th>Delivery Time</th>
            </tr>
        </thead>

    </table>
</div>
<style>
    #viewDelivery_filter{ display: none; }
    div.dt-buttons{
        float:right;
    }
</style>
<script>
    function CreateDataTable(data, tableId, tableCols)
    {
        var sortColumn = 0;
        var oTable = jQuery('#' + tableId + '').dataTable({
            "processing": true,
            "aaData": data,
            "aoColumns": tableCols,
            "order": [0, "desc"],
            "iDisplayLength": 20,
            "bLengthChange": false, //used to hide the property
            "bFilter": true,
            "dom": 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'deliveryreport'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'deliveryreport'
                }
            ]
        });
        //Add filter columns
        jQuery("thead input").keyup(function () {
            oTable.fnFilter(this.value, jQuery("thead input").index(this));
        });
        
        jQuery('#deliverytime').change(function () {
            oTable.fnFilter(this.value, jQuery("thead input").index(this));
        });
    }
    jQuery(document).ready(function () {
        jQuery('#deliverytime').datepicker({format: "yyyy-mm-dd", autoclose: true, });

        

        var data = <?php echo json_encode($deliverydata); ?>;
        var tableId = 'viewDelivery';
        var tableCols = [
            {"mData": "orderid"}
//        , {"mData": "vehicleno"}
            , {"mData": "realname"}
            , {"mData": "slot"}
            , {"mData": "dellocation"}
            , {"mData": "delivery_time"}
        ];
        CreateDataTable(data, tableId, tableCols);
    });
</script>