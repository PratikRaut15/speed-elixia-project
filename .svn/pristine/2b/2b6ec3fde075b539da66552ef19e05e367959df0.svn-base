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
        <thead><tr><th colspan="100%" > Order Details  </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <input type="hidden" name="userkey" value='<?php echo $_SESSION['userkey']; ?>' >
            <input type="hidden" name="operation_mode" value='1' >
            <tr>
                <td class='frmlblTd' width="20%">Bill No <span class="mandatory">*</span></td><td width="30%">
                    <?php echo $details->orderid;?>
                <input type="hidden" name="orderid" id="orderid" value="<?php echo $details->id;?>" required>
                </td>
                <td class='frmlblTd' width="20%">Area <span class="mandatory">*</span></td><td width="30%">
                   <?php echo $details->area;?>
                    <input type='hidden' id='areaid' name='areaid' value="<?php echo $details->areaid;?>" >
                    </td>
            </tr>



            <tr><td class='frmlblTd'>Address</td><td><?php echo $details->address?></td>
            <td class='frmlblTd'>Flat No</td><td><?php echo $details->flatno;?></td>
            </tr>

            <tr><td class='frmlblTd'>Building</td><td><?php echo $details->building;?></td>
            <td class='frmlblTd'>Street</td><td><?php echo $details->street;?></td>
            </tr>

            <tr><td class='frmlblTd'>Landmark</td><td><?php echo $details->landmark;?></td>
            <td class='frmlblTd'>City</td><td><?php echo $details->city;?></td>
            </tr>

            <tr><td class='frmlblTd'>Pincode</td><td><?php echo $details->pincode;?></td><td class='frmlblTd'>Slot</td><td><?php echo $details->slot;?></td>
            </tr>

            <tr><td class='frmlblTd'>Delivery Date</td><td>
                    <?php $deliverydate = date('d-m-Y', strtotime($details->deliverydate)); ?>
                    <?php echo $deliverydate;?></td>
            <td class='frmlblTd'>Order Date</td><td>
                    <?php $orderdate = date('d-m-Y', strtotime($details->orderdate)); ?>
                    <?php echo $orderdate;?></td>
            </tr>
            <tr>
            <td class='frmlblTd'>Status</td>
            <td colspan="3">
                <input type="hidden" name="orderid" id="orderid" value="<?php echo $order->id;?>"/>
                <input type="hidden" name="customerno" id="customerno" value="<?php echo $_SESSION['customerno'];?>"/>
                <?php if($details->status == '' ){
                    echo "Ongoing";
                }else{
                    echo $dm->getstatus_byname($details->status);
                }
                ?>
            </td>


        </tr>
        <?php
        $sign = "../../customer/".$_SESSION['customerno']."/routing/signature/".$details->id.".jpg";
        if(file_exists($sign)){
            ?>
        <tr>
            <td>Signature</td>
            <td colspan="3">
                <img alt="signature" width="150px;" height="80px;" src="<?php echo $sign;?>" style="border:1px solid #ccc; padding: 5px;" />
            </td>
        </tr>
        <?php
        }
        $photo = "../../customer/".$_SESSION['customerno']."/routing/photos/".$details->id."/";
        if(is_dir($photo)){
           ?>
        <tr>
            <td width="15%">Photos</td>
            <td colspan="3">
                <?php
                $files = scandir($photo);
                foreach ($files as $file){
                    if($file != ".." && $file !='.'){
                        //echo $file;
                        echo '<img alt="signature" width="150px;" height="80px;" src="'.$photo.$file.'" style="border:1px solid #ccc; padding: 5px;" margin-left:25px; />'; echo "&nbsp;&nbsp;";
                    }
                }
                ?>
            </td>
        </tr>
           <?php
        }

        ?>


         <!--
        <tr><td colspan="100%" class='frmlblTd cen' style="text-align: center;" ><input type="submit" value="Modify" class='btn btn-primary'></td></tr>
         -->
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


    </form>
    </center>
</div>
