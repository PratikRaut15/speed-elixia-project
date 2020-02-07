<?php include_once 'pickup_functions.php'; ?>
<?php
$oid = $_GET['oid'];
$pickup = getpickup();
$pickupstatus = getpickupallstatus();
$getallslots = getpickupslots();
$order = getOrder($oid);
?>
<?php
/**
 * City master form
 */
?>
<style>
    #ajaxstatus{text-align:center;font-weight:bold;display:none}
    .mandatory{color:red;font-weight:bold;}
    #addorders table{width:50%;}
    #addorders .frmlblTd{text-align:center}    
</style>
<br/>
<div class='container'>
    <center> <!-- changepickup -->
        <form enctype="multipart/form-data" method="POST" id="addorders"  >

            <span style="display: none;" id="name_error"> Please Select Pickup Boy </span>
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Edit Order</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                <input type="hidden" name="userkey" value='<?php echo $_SESSION['userkey']; ?>' >
                <input type="hidden" name="operation_mode" value='1'>
                <tr><td class='frmlblTd'>Order Id <span class="mandatory">*</span></td><td><input type="text" name="orderid" id="orderid" placeholder="Order ID" value="<?php echo $order->orderid; ?>"  readonly=""></td></tr>
                <input type='hidden' id='areaid' name='areaid'>
                <tr><td class='frmlblTd'>AWB No</td><td><input type="text" name="awbno" id="awbno" value="<?php
                        if (isset($order->awbno)) {
                            echo $order->awbno;
                        }
                        ?>" readonly="" placeholder="AWB No"></td></tr>


                <tr>
                    <td class='frmlblTd'>From Address</td>
                    <td><textarea disabled name="fromaddress" id="fromaddress"><?php
                            if (isset($order->fromaddress)) {
                                echo $order->fromaddress;
                            }
                            ?></textarea></td>
                </tr>

                <tr>
                    <td class='frmlblTd'> To Address</td>
                    <td><textarea disabled name="toaddress" id="toaddress"><?php
                            if (isset($order->toaddress)) {
                                echo $order->toaddress;
                            }
                            ?></textarea></td>
                </tr>

                <tr><td  class='frmlblTd'>Customer Contact No.</td><td><input type="text" readonly  name="customerno" value="<?php echo $order->phone; ?>"/></td></tr>


                <tr><td class='frmlblTd'>Pickupboy</td>
                    <td>
                        <select name="pickupboyid" id="pickupboyid">
                            <option value="00">Select Pickup Boy</option>
                            <?php
                            if (isset($pickup) && !empty($pickup)) {
                                foreach ($pickup as $pick) {
                                    ?>
                                    <option value="<?php echo $pick->pid; ?>" <?php
                                    if (isset($order->pickupboyid)) {
                                        if ($pick->pid == $order->pickupboyid) {
                                            echo "selected";
                                        }
                                    }
                                    ?> ><?php echo $pick->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                        </select>
                    </td>
                </tr>

                <tr><td class='frmlblTd'>Tracking Status</td><td><select name="status" id="pickupboyid">
                            <option value="00">Select Status</option>
                            <?php
                            if (isset($pickupstatus) && !empty($pickupstatus)) {
                                foreach ($pickupstatus as $pickstatus) {
                                    ?>
                                    <option value="<?php echo $pickstatus->trackingstatusid; ?>" <?php
                                    if (isset($order->trackingstatusid)) { {
                                            if ($pickstatus->trackingstatusid == $order->trackingstatusid) {
                                                echo'selected';
                                            }
                                        }
                                    }
                                    ?> ><?php echo $pickstatus->trackingstatusname; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>          
                        </select></td></tr>

                <tr><td class='frmlblTd'>Time Slot</td><td><select name="timeslotid" id="timeslotid">
                            <option value="00">Select Timeslot</option>        
                            <?php
                            if (isset($getallslots) && !empty($getallslots)) {
                                foreach ($getallslots as $pickslots) {
                                    ?>
                                    <option value="<?php echo $pickslots->timeslotid; ?>" <?php
                                    if (isset($order->slotid)) { {
                                            if ($pickslots->timeslotid == $order->slotid) {
                                                echo'selected';
                                            }
                                        }
                                    }
                                    ?> ><?php echo $pickslots->timeslot; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>          
                        </select></td>
                </tr>
                <?php
                $shippingdetails = getshipping_details($oid, $order->userid);
                if (isset($shippingdetails) && !empty($shippingdetails)) {
                    $i = 0;
                    foreach ($shippingdetails as $row) {
                        $i++;
                        if (base64_encode(base64_decode($row->orderimage, true)) === $row->orderimage) {
                            ?>    
                            <tr>
                                <td class='frmlblTd'>Photo<?php echo $i; ?></td> <td>
                                    <?php
                                    echo '<img src="data:image/jpeg;base64,' . base64_decode($row->orderimage) . '" width="200px;" height="150px;" />';
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }  
                    
                }
                ?>              
                <tr><td class='frmlblTd'></td><td><input type="button" value="Modify Order" id="adddealerbtn" class="btn btn-primary" onclick="changepickup();">
                        <input type="hidden" value="<?php echo $order->orderid ?>" id="oid" name="oid" ></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>


