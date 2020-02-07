<?php include_once 'pickup_functions.php'; ?>
<?php
$oid = $_REQUEST['oid'];
$pickup = getpickup();
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
<div class='container' >
    <center> <!-- changepickup -->
    <form enctype="multipart/form-data" method="POST" id="addorders"  >

        <span style="display: none;" id="name_error"> Please Select Pickup Boy </span>
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Edit Order</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <input type="hidden" name="userkey" value='<?php echo $_SESSION['userkey']; ?>' >
            <input type="hidden" name="operation_mode" value='1' >
            <tr><td class='frmlblTd'>Order No. <span class="mandatory">*</span></td><td><input type="text" name="orderid" id="orderid" placeholder="Order ID" value="<?php echo $order->orderid;?>"  readonly=""></td></tr>
            <tr><td class='frmlblTd'>Customer <span class="mandatory">*</span></td><td><input type="text" name="customer" id="customer" value="<?php echo $order->customername;?>" readonly="" placeholder="Customer"></td></tr>
            <input type='hidden' id='areaid' name='areaid' >
            <tr><td class='frmlblTd'>Vendor</td><td> <input type="text" name="vendor" id="vendor" value="<?php echo $order->vendorname;?>"readonly=""  placeholder="Vendor"></td></tr>
            <tr><td class='frmlblTd'>Fulfillment ID</td><td><input type="text" name="fulfill" id="fullfill" value="<?php echo $order->fulfillmentid;?>" readonly="" placeholder="Fulfillment ID"></td></tr>
            <tr><td class='frmlblTd'>AWB No</td><td><input type="text" name="awbno" id="awbno" value="<?php echo $order->awbno;?>" readonly="" placeholder="AWB No"></td></tr>
            <tr><td class='frmlblTd'>Shipper</td><td><input type="text" name="shipper" id="shipper" value="<?php echo $order->sname;?>" readonly="" placeholder="Shipper"></td></tr>
            <tr><td class='frmlblTd'>Pickupboy</td>
                <td>
            <select name="pickupboyid" id="pickupboyid">
                <option value="00">Select Pickup Boy</option>
                <?php
                if(isset($pickup) && !empty($pickup)){
                    foreach($pickup as $pick){
                        ?>
                <option value="<?php echo $pick->pid;?>" <?php if($pick->pid == $order->pid) { echo "selected"; } ?> ><?php echo $pick->name;?></option>
                    <?php
                    }
                }
                ?>
            </select>
            </td>
            </tr>
            
            <tr><td class='frmlblTd'>Order Status</td><td><select name="status" id="pickupboyid">
                <option value="0" <?php if($order->status == '0') { echo "selected"; } ?> >Ongoing</option>
                <option value="1" <?php if($order->status == '1') { echo "selected"; } ?>>Picked Up</option>
                <option value="2" <?php if($order->status == '2') { echo "selected"; } ?>>Cancelled</option>
                
            </select></td></tr>
            
            <?php
            $file = "../../customer/".$_SESSION['customerno']."/pickup/signature/".$oid.".jpg";
            if(file_exists($file)){
                ?>
           <tr><td class='frmlblTd'>Shipper</td><td><img src="<?php echo $file;?>" name=sign" width="150px" height="75px" style="border: 1px solid #000;"/></td></tr>
            
            
                <?php
            }
            ?>
            
           <?php
            $photo = "../../customer/".$_SESSION['customerno']."/pickup/photo/".$oid."/";
            
        if(is_dir($photo)){
            $files = scandir($photo);
           
                foreach ($files as $file){
                    if($file != ".." && $file !='.'){ ?>
                <tr><td class='frmlblTd'>Photo</td> <td>
                    <img alt="photo" width="150px;" height="80px;" src="<?php echo $photo.$file;?>" style="border:1px solid #ccc; padding: 5px;"/>
                </td></tr>
                     
                    <?php }
                }
                
        }
            ?>
           
            
            
            <tr><td class='frmlblTd'></td><td><input type="button" value="Modify Order" id="adddealerbtn" class="btn btn-primary" onclick="changepickup();">
            <input type="hidden" value="<?php echo $order->oid?>" id="oid" name="oid" ></td></tr>
            
            
        </tbody>
    </table>
    </form>
    </center>
</div>



