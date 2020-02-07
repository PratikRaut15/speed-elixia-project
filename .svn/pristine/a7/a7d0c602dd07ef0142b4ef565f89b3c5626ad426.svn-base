<?php include 'pickup_functions.php'; ?>

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
    #assignOrders_filter{
        display:none;

    }
</style>
<div class="container-fluid">


    <div style="float:right;">
        <span style="float:left;">
            <div class="squareLbl" style="background:#70DB70" ></div><label style="float: left; padding-right: 5px;">: Order Picked Up</label>&nbsp;&nbsp;
            <div class="squareLbl" style="background:#FFB2B2"></div><label style="float: left;">: Order Cancelled</label>&nbsp;
        </span>
        <a style="float: left;" href='javascript:void(0)' onclick="html2xls(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>);
        return false;"> Export <img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
    </div>


    <?php $today = date('Y-m-d'); ?>
    <table class='display' id="assignOrders" >
        <thead>
            <tr>
                <td><input type="text" class='search_init' name='orderid' id="txtorderid" style='width:80px;'/></td>
                <td><input type="text" class='search_init' name='AWBno' id="txtawbno" style='width:80px;'/></td>
                <td><input type="hidden" class='search_init' name='timeslot' id="timeslot" style='width:80px;'/></td>
                <td><input type="text" class='search_init' name='pickupdate' id="DelDate" value="<?php echo $today; ?>" style='width:80px;'/></td>
                <td><input type="hidden" class='search_init' name='status' id="status" style='width:80px;'/></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th>Order ID.</th>
                <th>AWB No</th>
                <th>Time Slot</th>
                <th>Pickup Date</th>
                <th>Status</th>
               <th>Action</th>
            </tr>
        </thead>
    </table>
</div>


<script>
    var today = "<?php echo $today; ?>";
</script>
