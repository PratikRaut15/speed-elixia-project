<?php
$order = get_order_byid($_REQUEST['odid']);
$items = get_order_item_byid($_REQUEST['odid']);
$billing = get_billing_address($_REQUEST['odid']);
$shipping = get_shipping_address($_REQUEST['odid']);
$getstatus = get_status();
$routes = getroutes();

?>
<script>
jQuery(document).ready(function () {
			jQuery('#PTDate').datepicker({format: "dd-mm-yyyy",autoclose:true});

                        });



function changelist()
{
    var vehi = jQuery('#hstatus').val();
    var id = jQuery('#orderid').val();
    var cno = jQuery('#customerno').val();

                if(vehi != 2)
                    {
                        jQuery.ajax({
                        type: "GET",
                        url: "autocomplete.php",
                        data: "q="+vehi+"&id="+id+"&cno="+cno ,
                        success: function(json){
                            if(vehi == 2)
                                {
                                    jQuery("#askroute").show();
                                    jQuery("#askroute1").show();
                                    //jQuery("#hstatus").attr('disabled','disabled');
                                }else{
                                    location.reload();
                                }

                            }
                        });
                    }else if(vehi ==2){
                        jQuery("#askroute").show();
                        jQuery("#askroute1").show();
                    }
}

function changeroute()
{
    var vehi = jQuery('#hstatus').val();
    var route = jQuery('#route').val();
    var id = jQuery('#orderid').val();
    var cno = jQuery('#customerno').val();
    var pdate = jQuery("#PTDate").val();
    var stime = jQuery("#STime").val();
   //alert(route);
                jQuery.ajax({
                    type: "GET",
                    url: "autocomplete.php",
                    data: "q="+route+"&status="+vehi+"&id="+id+"&cno="+cno+"&pdate="+pdate+"&stime="+stime+"&route=1" ,
                    success: function(json){
                        location.reload();
                    }
            });
}


</script>
<style>
            .table td{ border: none; padding: 1px;}
            .table th{ border: none; padding: 1px;}
            .table1 td{ border: none; padding: 1px;line-height: 15px;}
</style>
<div>

 <table class="table" style="width: 90%;  ">
     <tbody>
         <tr>
             <td colspan="3" style="text-align: center;"><h3>Order ID - <?php echo $order->order_id;?></h3></td>
         </tr>
         <tr>
             <td width="33%">
                 <table class="table1">
                 <tr>
                     <td colspan="2" ><strong>Billing Details</strong></td>
                 </tr>
                 <tr>
                     <td colspan="2" ></td>
                 </tr>
                 <tr>
                     <td width="40%">Name</td>
                     <td width="60%"><?php echo $billing->fullname; ?></td>
                 </tr>
                 <tr>
                     <td>Address</td>
                     <td><?php echo $billing->address;?></td>
                 </tr>
                 <tr>
                     <td>City</td>
                     <td><?php echo $billing->city; ?></td>
                 </tr>
                 <tr>
                     <td>State</td>
                     <td><?php echo $billing->state; ?></td>
                 </tr>
                 <tr>
                     <td>Country</td>
                     <td><?php echo $billing->country; ?></td>
                 </tr>
                 <tr>
                     <td>Zip</td>
                     <td><?php echo $billing->pincode; ?></td>
                 </tr>
                 <tr>
                     <td>Phone</td>
                     <td><?php echo $billing->phone; ?></td>
                 </tr>
                 </table>
             </td>
             <td width="33%"></td>
             <td width="33%">
                <table class="table1">
                 <tr>
                     <td colspan="2" ><strong>Shipping Details</strong></td>
                 </tr>
                 <tr>
                     <td colspan="2" ></td>
                 </tr>
                 <tr>
                     <td width="30%">Name</td>
                     <td width="70%"><?php echo $shipping->fullname; ?></td>
                 </tr>
                 <tr>
                     <td>Address</td>
                     <td><?php echo $shipping->address;?></td>
                 </tr>
                 <tr>
                     <td>City</td>
                     <td><?php echo $shipping->city; ?></td>
                 </tr>
                 <tr>
                     <td>State</td>
                     <td><?php echo $shipping->state; ?></td>
                 </tr>
                 <tr>
                     <td>Country</td>
                     <td><?php echo $shipping->country; ?></td>
                 </tr>
                 <tr>
                     <td>Zip</td>
                     <td><?php echo $shipping->pincode; ?></td>
                 </tr>
                 <tr>
                     <td>Phone</td>
                     <td><?php echo $shipping->phone; ?></td>
                 </tr>
                 </table>
             </td>

             </td>
         </tr>
     </tbody>
 </table>
    <hr/>
    <?php
    if(!empty($items)){ ?>
        <div><h5>Product Details</h5></div>
    <table class="search_table" style="width: 90%;">

        <tr class="fixedHeader">
                <th>Sr.No</th>
                <th>Product Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        <?php
        $j=1;
        foreach($items as $item)
        {
            ?>
            <tr>
                <td><?php echo $j++;?></td>
                <td><?php echo $item->product_name?></td>
                <td><?php echo $item->price?>.00</td>
                <td><?php echo $item->quantity?></td>
                <td><?php echo $item->total?>.00</td>
            </tr>
            <?php
        }
        ?>
    </table>
    <hr/>
    <table class="table" style="width: 85%;  ">
        <tr>
            <td width="55%"></td>
            <td width="15%">Sub Total</td>
            <td width="20%" style="text-align: right;"><?php echo $order->sub_total;?>.00</td>

        </tr>
         <tr>
            <td width="55%"></td>
            <td width="15%">Taxes Total</td>
            <td width="20%" style="text-align: right;"><?php echo $order->taxes_total;?>.00</td>

        </tr>
         <tr>
            <td width="55%"></td>
            <td width="15%">Discount Total</td>
            <td width="20%" style="text-align: right;"><?php echo $order->discount_total;?>.00</td>
         </tr>
         <tr>
            <td width="55%"></td>
            <td width="15%">Total</td>
            <td width="20%" style="text-align: right;"><?php echo $order->total;?>.00</td>
         </tr>
    </table>
    <hr/>
    <?php }
    ?>


    <div><h5>Shipment Details</h5></div>
    <table class="table" style="width: 90%;  ">

        <?php
        if($order->sales_type !='')
        {
            ?>
            <tr>
            <td width="20%">Sales Type</td>
            <td><?php
            if($order->sales_type == '0')
            {
                echo "JDE";
            }else{
                echo "Non JDE";
            }
            ?>

            </td>
            </tr>
            <?php
        }

        if($order->ref_number !='')
        {
            ?>
             <tr>
            <td width="20%">Reference Number</td>
            <td><?php echo $order->ref_number;?></td>
        </tr>
            <?php
        }
        ?>

        <tr>
            <td width="20%">Provider</td>
            <td><?php echo $order->provider;?></td>
        </tr>
         <tr>
            <td>Tracking No</td>
            <td><?php echo $order->trackingno;?></td>
        </tr>
         <tr>
            <td>Price</td>
            <td><?php echo $order->shipprice;?></td>
        </tr>
        <tr>
            <td>Transporter Name</td>
            <td><?php echo $order->transporter_name;?></td>
        </tr>
        <tr>
            <td>LR No</td>
            <td><?php echo $order->lr_no;?></td>
        </tr>
        <tr>
            <td>LR Date</td>
            <td><?php echo $order->lr_date;?></td>
        </tr>
    </table>
    <hr/>
    <form method="post" action="<?php $_SERVER['PHP_SELF'];?>">
    <table class="table" style="width: 90%;  ">

        <tr>
            <td colspan="100%" style="text-align: center;"><h5>History</h5></td>
        </tr>

        <tr>
            <td width="20%">Status</td>
            <?php if($order->status != '5') { ?>
            <td>
                <input type="hidden" name="orderid" id="orderid" value="<?php echo $order->id;?>"/>
                <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno'];?>"/>
                <select name="hstatus" id="hstatus" onchange="changelist();">
                    <option value="0">Select Status</option>
                   <?php
                   if(isset($getstatus))
                   {
                       foreach($getstatus as $status)
                       {
                       ?>
                    <option value="<?php echo $status->statusid;?>" <?php if($order->status == $status->statusid) { echo "selected";  } ?>><?php echo $status->status;?></option>
                        <?PHP

                        }
                   }
                   ?>
                </select>
            </td>
            <?php } else { ?>
            <td>Delivered</td>
            <?php  } ?>
        </tr>

        <?php
        if($order->route == '')
        {
            echo '<tr id="askroute" style="display:none;">';
        }else if($order->status != '2'){
            echo '<tr id="askroute" style="display:none;">';
        }else{
            echo '<tr id="askroute" >';
        }
        ?>
        <td width="20%">Route</td>
            <td>

                <select name="route" id="route">
                    <option value="0">Select Route</option>
                   <?php
                   foreach($routes as $route)
                   {
                       ?>
                    <option value="<?php echo $route->routeid;?>" <?php if($route->routeid == $order->route) { echo "selected";  } ?>><?php echo $route->routename;?></option>
                       <?PHP

                   }
                   ?>
                </select>
            </td>
        </tr>

        <?php
        if($order->route == '' || $order->status != '2')
        {
            echo '<tr id="askroute1" style="display:none;">';
        }else{
            echo '<tr id="askroute1" >';
        }
        ?>
        <td>Time For Delivery</td>
        <td>
            <?php
            if($order->eta  !='0000-00-00 00:00:00')
            {
                ?>
                <input id="PTDate" name="PTDate" type="text" class="input-small" value="<?php echo date("d-m-Y", strtotime($order->eta)); ?>"/>
            <input id="STime" name="STime" type="text" class="input-small" value="<?php echo date("H:i", strtotime($order->eta)); ?>" size="7"/>
                <?php
            }
            else{
                ?>
                <input id="PTDate" name="PTDate" type="text" class="input-small" value="<?php echo date("d-m-Y"); ?>"/>
            <input id="STime" name="STime" type="text" class="input-small" value="" size="7"/>
                <?php
            }
            ?>
            <input type="button" id="updateroute" name="updateroute" class="btn btn-primary" value="Save" onclick="changeroute();" style="margin-top: -10px;" />
        </td>

        </tr>

        <?php if($order->status == '5')
        {

        ?>
        <tr>
            <td>Route</td>
            <td><?php echo get_route($order->route); ?></td>
        </tr>
        <tr>
            <td>Signature</td>
            <td>
                <img src="../../customer/<?php echo $_SESSION['customerno']?>/<?php get_devicekey($order->route);?>/signature/<?php echo $order->id?>.jpeg"  width="250" height="100" style="border:1px solid #000;"  />
            </td>
        </tr>

        <?php } ?>


    </table>
        <div style="height: 200px; overflow-x: auto; width: 60%;">
            <table class="table" style="width: 95%; text-align: center;">
                <tr id="formheader">
                <th width="10%">Sr.No</th>
                <th>Status</th>
                <th>Route</th>
                <th width="25%">Date & Time</th>
            </tr>

            <?php
            $K=1;
            $history = get_history($order->id);
            $cnt = count($history) + 1;
            if(isset($history))
            {
                foreach($history as $his)
                {
                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $K++;?></td>
                    <td style="text-align: center;"><?php echo $his->status?></td>
                    <td style="text-align: center;"><?php
                    if($his->route == 0)
                    {
                        echo "N/A";
                    }
                    else{
                        echo get_route($his->route);
                    }
                    ?></td>
                    <td style="text-align: center;"><?php echo convertDateToFormat($his->timestamp,speedConstants::DEFAULT_DATETIME);?></td>
                    </tr>
                    <?PHP
                }
            }else
            {
                echo "<tr><td colspan='100%' style='text-align:center;'>History Not Available.</td></tr>";
            }
            ?>

        </table>
        </div>
    </form>
</div>
