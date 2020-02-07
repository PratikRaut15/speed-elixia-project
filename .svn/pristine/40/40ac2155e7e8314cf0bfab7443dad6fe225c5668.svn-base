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
<div class="container-fluid">
    
    <h3>Orders</h3><br/>
    
    <?php $today = date('Y-m-d'); ?>
    <div style='font-size:13px;float:left;margin:0px; text-align: left;  font-weight:bold;' >
        <ul>
            <li>AC: Accuracy</li>
            <li><div class="squareLbl" style="background:#70DB70" ></div>: Order Delivered</li>
            <li><div class="squareLbl" style="background:#FFB2B2"></div>: Order Cancelled</li>
        </ul>
         <a href='javascript:void(0)' onclick="html2xls(<?php  echo $_SESSION['customerno']; ?>, <?php  echo $_SESSION['userid']; ?>);return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
         
        <!--
        <a href='javascript:void(0)' onclick="get_pdfreport(<?php  echo $_SESSION['customerno']; ?>, <?php  echo $_SESSION['userid']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
        <a href='javascript:void(0)' onclick="get_pdfreport();"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
           
            <a href='javascript:void(0)' onclick="get_stoppage_print('<?php  echo $title; ?>');"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
        -->
    </div><br/>
    <table class='display' id="assignOrders" >
        <thead>
            <tr>
                <td><input type="text" class='search_init' name='order_id' id='order_id' style='width:30px;'/></td>
                <!--
                <td><input type="text" class='search_init' name='accuracy' style='width:10px;'/></td>
                -->
                <td><input type="text" class='search_init' name='zone' style='width:50px;'/></td>
                <td><input type="text" class='search_init' name='area' style='width:100px;'/></td>
                <td><input type="text" class='search_init' name='flat' style='width:30px;'/></td>
                <td><input type="text" class='search_init' name='building' style='width:30px;'/></td>
                <td><input type="text" class='search_init' name='city' style='width:50px;'/></td>
                <td><input type="text" class='search_init' name='landmark' style='width:80px;'/></td>
                <td><input type="text" class='search_init' name='slot' style='width:10px;'/></td>
                <td><input type="text" class='search_init' name='delivery_date' id='DelDate' style='width:80px;' value='<?php echo $today;?>'/></td>
                <td><input type="text" class='search_init' name='order_date' id='OrderDate' style='width:80px;' /></td>
                <td>
                    <select class='search_init' id='orderStatus' name='is_delivered' style='width:30px;'>
                        <option value=''>--select--</option>
                        <option value="1-0">Delivered</option>
                        <option value="0-0">Ongoing</option>
                        <option value="0-1">Cancelled</option>
                    </select>
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th>Bill No.</th>
                <!--
                <th>AC</th>
                -->
                <th>Zone</th>
                <th>Area</th>
                <th>Flat</th>
                <th>Building</th>
                <th>City</th>
                <th>Landmark</th>
                <th>Slot</th>
                <th>Delivery Date</th>
                <th>Order Date</th>
                <th>Status</th>
                <th style="background: none ; width: 100px;">Action</th>
                <th>Route id</th>
                <th>Id</th>
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
        <input type="hidden" value='<?php echo $_SESSION['customerno'];?>' id='customerno'/>
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
var today = "<?php echo $today;?>";
</script>