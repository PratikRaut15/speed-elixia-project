<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
$_scripts_custom[] = "../../scripts/team/ledger.js";

include("header.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();
//-----------State List-------
$db = new DatabaseManager();
$states = Array();
$SQL = sprintf("SELECT codeid,state FROM `state_gst_code`");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $state = new stdClass();
        $state->codeid = $row['codeid'];
        $state->state = $row['state'];
        $state->gst_code = $row['gst_code'];
        $states[] = $state;
    }
}



?>

<style>
textarea{
    box-shadow:2px 2px 2px #666;  
    width:300px !important;
}
</style>
<link rel="stylesheet" href="../../css/invoicePayment.css">

<div class="panel">
    <div class="paneltitle" align="center">Add Account Ledger Detail</div> 
    <div class="panelcontents">
        <span id="error_name" style="display: none;color: #FF0000;text-align: center">Please Enter Invoice Name</span>
        <span id="add_cust_succ" style="display: none;color: #00493a;text-align: center">Add Successfully</span>
        <span id="fail_add_cust" style="display: none;color: #FF0000;text-align: center">Some Error Has Occurred Try Again</span>
        <form method="post" name="form_ledger" id="form_ledger">
            <span id="msg_ledger" style="display: none;color: #FF0000;text-align: center"></span>
            <br>
                <div>
                        <label>Ledger Name<span style="color: #FF0000">*</span></label>  
                        <textarea type ="text" name ="ledgername" id="ledgername" required maxlength="100"/></textarea>

                        <label>Address Line 1</label>
                        <textarea type ="text" name ="add1" id="add1"  maxlength="100"/></textarea>
                </div>
                <div>
                        <label>Address Line 2</label>
                        <textarea type ="text" name ="add2" id="add2"  maxlength="100"/></textarea>
                    
                    
                        <label>Address Line 3</label>
                        <textarea type ="text" name ="add3" id="add3"  maxlength="100"/></textarea>
                </div>
                <div>
                    <label>GST No.<span style="color: #FF0000">*</span></label>
                    <input type ="text" name ="gstno" id="gstno" maxlength="30" required onblur="gst_code();" style="text-transform:uppercase" maxlength="15" />
                    <input  type="checkbox" name="gst_na_c" id="gst_na_c" value="1" 
                                   onchange="gst_na();">N.A
                    <label style="margin-left:5px;">State</label>
                      
                    <select name ="state" id="state" >
                                <option value="0">Select State</option>
                                <?php
                                foreach ($states AS $data) {
                                    echo '<option value="' . $data->codeid . '">' . $data->state . '</option>';
                                }
                                ?>
                    </select>
                    <label>PAN No.</label>
                    <input type ="text" name ="panno" id="panno" maxlength="30" style="text-transform:uppercase"  maxlength="10" />
                </div>
                

<!-- <div>CST No.</div>
        <div>
            <input type ="text" name ="cstno" id="cstno" maxlength="30"/>
        </div>
                
                
                    <div>VAT No.</div>
                    <div>
                        <input type ="text" name ="vatno" id="vatno" maxlength="30"/>
                    </div>
                
                
                    <div>ST No.</div>
                    <div>
                        <input type ="text" name ="stno" id="stno" maxlength="30"/>
</div>-->
            <div>
                <label>Phone</label>
                <input type ="text" name ="phno" id="phno" maxlength="10">
                <label>Email</label>
                <input type ="text" name ="email" id="email" size="30"/>
            </div>
            <input type="button" id="add_ledger_master" name="add_ledger_master" value="Add Ledger Details" onclick="addLedger();">
        </form>
    </div>

</div>
<?php 
    include_once('ledgerList.php');
?>
<br>

<script>   
  jQuery(document).ready(function () {
$("#panno").attr("readonly",true);
});
function gst_code(){
var gst_no = $('#gstno').val();
var res = gst_no.substring(0,1);
var res2 = gst_no.substring(1,2);
var res3 = gst_no.substring(2,12);
if(gst_no==''){
$("#state").val(0).change();
$("#panno").val("");
}
if(res==0){
$("#state").val(res2).change();
$("#panno").val(res3);
}
else{
$("#state").val(res+res2).change();
$("#panno").val(res3);  
}
}
function gst_na(){
   if($('#gst_na_c').is(":checked")){
    $("#state").val(0).change();
    $("#panno").val("");
    $('#gstno').val("N.A.");
    $('#gstno').attr("readonly",true);
    $("#panno").attr("readonly",false);
   }
   else{
    $('#gstno').val("");
    $("#panno").attr("readonly",true);
    $('#gstno').attr("readonly",false);
   }

}


</script>