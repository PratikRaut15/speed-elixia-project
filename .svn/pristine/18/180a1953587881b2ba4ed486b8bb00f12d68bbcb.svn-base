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
    <center>
    <form id="addorders" method="POST" action="" onsubmit="addOrders();return false;" enctype='application/json'>
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Add Orders</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <input type="hidden" name="userkey" value='<?php echo $_SESSION['userkey']; ?>' >
            <input type="hidden" name="operation_mode" value='1' >
            <tr><td class='frmlblTd'>Bill No <span class="mandatory">*</span></td><td><input type="text" name="billno" required></td></tr>
            <tr><td class='frmlblTd'>Area <span class="mandatory">*</span></td><td><input type="text" name="areaname" id="areaname" required></td></tr>
            <input type='hidden' id='areaid' name='areaid' >
            <tr><td class='frmlblTd'>Address</td><td> <textarea name="address"></textarea></td></tr>
            <tr><td class='frmlblTd'>Building</td><td><input type="text" name="building" ></td></tr>
            <tr><td class='frmlblTd'>Street</td><td><input type="text" name="street" ></td></tr>
            <tr><td class='frmlblTd'>Landmark</td><td><input type="text" name="landmark" ></td></tr>
            <tr><td class='frmlblTd'>City</td><td><input type="text" name="city" ></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>