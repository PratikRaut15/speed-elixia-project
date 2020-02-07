<?php
$customerno = exit_issetor($_SESSION['customerno']);
$orderid = exit_issetor($_GET['oid']);

$dm = new DeliveryManager($customerno);
$details = $dm->getOrderDetails($orderid);
$payments = $dm->getOrderPayments($orderid);
$getstatus = $dm->getstatus();
?>
<style type="text/css">
#ajaxstatus{text-align:center;font-weight:bold;display:none}
.mandatory{color:red;font-weight:bold;}
//.editorders table{width:50%;}
.editorders .frmlblTd{text-align:center}
.editorders .cen{
    align:center;
}
</style>
<script>
    function changelist()
{
    var vehi = jQuery('#hstatus').val();
    var id = jQuery('#orderid').val();
    var cno = jQuery('#customerno').val();


                    {
                        jQuery.ajax({
                        type: "GET",
                        url: "autocomplete.php",
                        data: "q="+vehi+"&id="+id+"&cno="+cno ,
                        success: function(json){

                                    location.reload();


                            }
                        });
                    }
}
</script>
<br/>
<div class='container' >
    <center>
    <form id="editorders" method="POST" action="" onsubmit="editOrders();return false;" enctype='application/json'>
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Edit Order  <span style="float: right;"><a href='assign.php?id=4&oid=<?php echo $orderid;?>'>Update Location</a></span></th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <input type="hidden" name="userkey" value='<?php echo $_SESSION['userkey']; ?>' >
            <input type="hidden" name="operation_mode" value='1' >
            <tr>
                <td class='frmlblTd'>Bill No <span class="mandatory">*</span></td><td>
                    <input type="text" name="billno" value="<?php echo $details->orderid;?>" required>
                <input type="hidden" name="orderid" id="orderid" value="<?php echo $details->id;?>" required>
                </td>
                <td class='frmlblTd'>Area <span class="mandatory">*</span></td><td>
                    <input type="text" name="areaname" id="areaname" value="<?php echo $details->area;?>" required>
                    <input type='hidden' id='areaid' name='areaid' value="<?php echo $details->areaid;?>" >
                    </td>
            </tr>



            <tr><td class='frmlblTd'>Address</td><td> <textarea name="address"><?php echo $details->address?></textarea></td>
            <td class='frmlblTd'>Flat No</td><td><input type="text" name="flat" value="<?php echo $details->flatno;?>" ></td>
            </tr>

            <tr><td class='frmlblTd'>Building</td><td><input type="text" name="building" value="<?php echo $details->building;?>" ></td>
            <td class='frmlblTd'>Street</td><td><input type="text" name="street" value="<?php echo $details->street;?>" ></td>
            </tr>

            <tr><td class='frmlblTd'>Landmark</td><td><input type="text" name="landmark" value="<?php echo $details->landmark;?>" ></td>
            <td class='frmlblTd'>City</td><td><input type="text" name="city" value="<?php echo $details->city;?>" ></td>
            </tr>

            <tr><td class='frmlblTd'>Pincode</td><td><input type="text" name="pincode" value="<?php echo $details->pincode;?>" ></td><td class='frmlblTd'>Slot</td><td><input type="text" name="slot" value="<?php echo $details->slot;?>" ></td>
            </tr>

            <tr><td class='frmlblTd'>Delivery Date</td><td>
                    <?php $deliverydate = date('d-m-Y', strtotime($details->deliverydate)); ?>
                    <input type="text" name="delivery_date" value="<?php echo $deliverydate;?>" id="SDate" ></td>
            <td class='frmlblTd'>Order Date</td><td>
                    <?php $orderdate = date('d-m-Y', strtotime($details->orderdate)); ?>
                    <input type="text" name="orderdate" value="<?php echo $orderdate;?>" readonly="" ></td>
            </tr>
            <tr>
            <td class='frmlblTd'>Status</td>
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
            <td></td>
            <td></td>
        </tr>

        <tr><td colspan="100%" class='frmlblTd cen' style="text-align: center;" ><input type="submit" value="Modify" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <table class='table table-condensed'>
            <thead>
                <tr>
                    <th colspan="100%"> View Payment</th>
                </tr>
                <tr>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Cheque No</th>
                    <th>Account No</th>
                    <th>Branch</th>
                    <th>Reason</th>
                    <th>Payment Done By</th>
                    <th>Payment Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($payments)){
                    foreach($payments as $payment){
                        ?>
                    <tr>
                        <td><?php
                        if($payment->type == '0'){
                            echo "Cash";
                        }else if($payment->type == '1'){
                            echo "Card";
                        }else if($payment->type == '2'){
                            echo "Redeem";
                        }
                        else if($payment->type == '3'){
                            echo "Cheque";
                        }
                        else if($payment->type == '4'){
                            echo "Skip With Reason";
                        }
                        else if($payment->type == '5'){
                            echo "Complete";
                        }
                        ?></td>
                        <td><?php echo $payment->amount ?></td>
                        <td><?php echo $payment->chequeno ?></td>
                        <td><?php echo $payment->accountno ?></td>
                        <td><?php echo $payment->branch ?></td>
                        <td><?php echo $payment->reason ?></td>
                        <td><?php
                        if($payment->paymentby == '0'){
                            echo "Web";
                        }else if($payment->paymentby =='1'){
                            echo "Mobile";
                        }
                        ?></td>
                        <td><?php echo date(speedConstants::DEFAULT_DATETIME, strtotime($payment->paymentdate)); ?></td>
                    </tr>
                        <?php
                    }

                }else{
                    ?>
                <tr>
                    <td colspan="100%" style="text-align: center;"> No Payments</td>
                </tr>
                   <?php
                }
                ?>

                </tbody>

        </table>

         <div style="height: 250px; overflow-x: auto; width: 60%;">
            <table class="table" style="width: 95%; text-align: center;">
                <tr id="formheader">
                    <th colspan="100%" >History</th>
                </tr>
                <tr id="formheader">
                <th width="10%">Sr.No</th>
                <th>Status</th>

                <th width="25%">Date & Time</th>
            </tr>

            <?php
            $K=1;
            $history = $dm->gethistory($orderid);
            $cnt = count($history) + 1;
            if(isset($history))
            {
                foreach($history as $his)
                {
                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $K++;?></td>
                    <td style="text-align: center;"><?php echo $his->status?></td>

                    <td style="text-align: center;"><?php echo convertDateToFormat($his->timestamp,speedConstants::DEFAULT_DATETIME) ;?></td>
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
    </center>
</div>
