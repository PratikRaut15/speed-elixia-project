<?php
$eid = $_GET['eid'];
$objRole = new Hierarchy();
$objRole->customerno = $_SESSION['customerno'];
$objRole->ruleid = $eid;
$objRole->conditionid = '';
$rules = getTransactionRules($objRole);
//print_r($rules);


?>
<style>
    .approvelist{
        margin-left: 75px;
    }
</style>
<center>
    <form  class="" name="createvehicle" id="createvehicle" action="action.php?action=editrule" method="POST" style="width:70%;">
        <table style="width: 60%;">
            <tr>
                <td>Transaction Type</td>
                <td> <input type="text" name="role" id="role" placeholder="Transaction Type" value="<?php echo $rules[0][categoryname] ?>" readonly=""></td>
            </tr>
            <tr>
                <td>Condition Name</td>
                <td><input type="text" name="condition" id="condition" placeholder="Transaction Condition" value="<?php echo $rules[0][conditionname] ?>" readonly=""></td>
            </tr>
            <tr>
                <td>Minimum Value</td>
                <td><input type="text" name="minvalue" id="minvalue" placeholder="Minimum Value" value="<?php echo $rules[0][minval] ?>"></td>
            </tr>
            <tr>
                <td>Maximum Value</td>
                <td><input type="text" name="maxvalue" id="maxvalue" placeholder="Maximum Value" value="<?php echo $rules[0][maxval] ?>"></td>
            </tr>
            <tr>
                <td>Approver ID</td>
                <td><input type="text" name="approver1" readonly="" id="approver1" placeholder="Aprrover ID" value="<?php echo $rules[0][role] ?>" onkeyup="getApproverRole(1,1);">
                <div id="approverdisplay" class="approvelist"></div>
                </td>
            </tr>
            <tr>
                <td>Priority</td>
                <td><input type="sequenceno" name="sequenceno" id="role" placeholder="Priority" value="<?php echo $rules[0][sequenceno] ?>"></td>
            </tr>
            <tr>
            <input type="hidden" name="ruleid" id="ruleid" value="<?php echo $rules[0][ruleid]?>"/>
            <input type="hidden" name="approverid1" id="approverid1" value="<?php echo $rules[0][approverid]?>"/>
            <input type="hidden" name="conditionid" id="conditionid" value="<?php echo $rules[0][conditionid]?>"/>
                
                <td colspan="2"><input type="submit" value="Modify Rule" class="btn  btn-primary"></td>

            </tr>


        </table>   


    </form>
</center>
