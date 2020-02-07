<?php include_once 'pickup_functions.php'; ?>
<?php
/**
 * City master form
 */

$getcustomer = getcustomers();
//print_r($getcustomer);

?>
<style>
#ajaxstatus{text-align:center;font-weight:bold;display:none}
.mandatory{color:red;font-weight:bold;}
#addorders table{width:50%;}
#addorders .frmlblTd{text-align:center}    
</style>
<br/>
<div class='container' >
    <center>
    <form id="addorders" method="POST" action="" onsubmit="addOrders();return false;" enctype='application/json'>
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Add Orders</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <input type="hidden" name="userkey" value='<?php echo $_SESSION['userkey']; ?>' >
            <input type="hidden" name="operation_mode" value='1' >
            <tr>
                <td class='frmlblTd'>Order Id <span class="mandatory">*</span></td>
                <td><input type="text" name="orderid" id="orderid" placeholder="Order ID"></td>
            </tr>
            <tr>
                <td class='frmlblTd'>Customer <span class="mandatory">*</span></td>
                <td><input type="text" name="customer" id="customer" placeholder="Customer">
                <?php 
//                    if(isset($getcustomer)){
//                        
//                    }
                ?>
                </td>
            </tr>
            <tr>
                <td class='frmlblTd'>Vendor</td>
                <td> <input type="text" name="vendor" id="vendor" placeholder="Vendor"></td>
            </tr>
            <tr>
                <td class='frmlblTd'>Fulfillment ID</td>
                <td><input type="text" name="fulfill" id="fullfill" placeholder="Fulfillment ID"></td>
            </tr>
            <tr>
                <td class='frmlblTd'>AWB No</td>
                <td><input type="text" name="awbno" id="awbno" placeholder="AWB No"></td>
            </tr>
            <tr>
                <td class='frmlblTd'>Shipper</td>
                <td><input type="text" name="shipper" id="shipper" placeholder="Shipper"></td>
            </tr>
            <tr><td class='frmlblTd'>Pickup Date</td><td><input type="text" name="pickupdate" placeholder="dd-mm-yyyy" class="datepicker" ></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>