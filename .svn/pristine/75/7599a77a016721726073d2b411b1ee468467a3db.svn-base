<?php
include_once("session.php");
include_once("loginorelse.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include("header.php");

$ledid = $_GET['ledid'];
$db = new DatabaseManager();
$pdo = $db->CreatePDOConn();
try {
    $sp_params1 = "'" . $ledid . "'"
            . ",''"
    ;
    $QUERY1 = $db->PrepareSP('get_ledger', $sp_params1);
    $ledgerList = $pdo->query($QUERY1);
    if ($ledgerList->rowCount() > 0) {
        $row = $ledgerList->fetch(PDO::FETCH_ASSOC);
        $ledgerid = $row['ledgerid'];
        $ledgername = $row['ledgername'];
        $add1 = $row['address1'];
        $add2 = $row['address2'];
        $add3 = $row['address3'];
        $state_code = $row['state_code'];
        $pan = $row['pan_no'];
        $gst = $row['gst_no'];
        $phone = $row['phone'];
        $email = $row['email'];
    }
    $db->ClosePDOConn($pdo);
} catch (Exception $e) {
    $status = "Caught exception: " . $e->getMessage();
}

$states = Array();
$SQL = sprintf("SELECT codeid,state FROM `state_gst_code`");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $state = new stdClass();
        $state->codeid = $row['codeid'];
        $state->state = $row['state'];
        $states[] = $state;
    }
}
?>
<style>
 table tr td {
    vertical-align: middle !important;
}   

</style>
<div class="panel">
    <div class="paneltitle" align="center">Add Account Ledger Detail</div> 
    <div class="panelcontents">
        <form method="post" name="editform_ledger" id="editform_ledger">
            <table>
                <tr>
                    <td id="editmsg_ledger" style="display: none;color: #FF0000;text-align: center"></td>
                </tr>
                <tr>
                    <td style="color: #FF0000"> * Marked field is Mandatory</td>
                </tr>
                <tr>
                    <td>Ledger Name<span style="color: #FF0000">*</span></td>
                    <td>
                        <input type ="text" name ="ledgername" id="ledgername" size="80" value="<?php echo isset($ledgername) ? $ledgername : ''; ?>"/>
                        <input type="hidden" name="ledgerid" id="ledgerid" value="<?php echo $ledid; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Address Line 1</td>
                    <td>
                        <input type ="text" name ="add1" id="add1" size="80" value="<?php echo isset($add1) ? $add1 : ''; ?>" maxlength="100"/>
                    </td>
                </tr>
                <tr>
                    <td>Address Line 2</td>
                    <td>
                        <input type ="text" name ="add2" id="add2" size="80" value="<?php echo isset($add2) ? $add2 : ''; ?>" maxlength="100"/>
                    </td>
                </tr>
                <tr>
                    <td>Address Line 3</td>
                    <td>
                        <input type ="text" name ="add3" id="add3" size="80" value="<?php echo isset($add3) ? $add3 : ''; ?>" maxlength="100"/>
                    </td>
                </tr>
                                <tr>
                    <td>GST No.</td>
                    <td>
                        <input type ="text" name ="gstno" id="gstno" value="<?php echo isset($gst) ? $gst : ''; ?>" maxlength="30" required onblur="gst_code();" style="text-transform:uppercase"/>
                        <input type="checkbox" name="gst_na_c" id="gst_na_c" value="1" 
                               onchange="gst_na();">N.A<br>
                    </td>
                </tr>
                <tr>
                    <td>State</td>
                    <td>
                        <select name ="state" id="state">
                            <option value="0">Select State</option>
                            <?php
                            foreach ($states AS $data) {
                                if ($state_code == $data->codeid) {
                                    echo '<option value="' . $data->codeid . '" selected>' . $data->state . '</option>';
                                } else {
                                    echo '<option value="' . $data->codeid . '">' . $data->state . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>PAN No.</td>
                    <td>
                        <input type ="text" name ="panno" id="panno" value="<?php echo isset($pan) ? $pan : ''; ?>" maxlength="30" style="text-transform:uppercase"/>
                    </td>
                </tr>

<!--                 <tr>
                    <td>CST No.</td>
                    <td>
                        <input type ="text" name ="cstno" id="cstno" value="<?php echo isset($cst) ? $cst : ''; ?>" maxlength="30"/>
                    </td>
                </tr>
                <tr>
                    <td>VAT No.</td>
                    <td>
                        <input type ="text" name ="vatno" id="vatno" value="<?php echo isset($vat) ? $vat : ''; ?>" maxlength="30"/>
                    </td>
                </tr>
                <tr>
                    <td>ST No.</td>
                    <td>
                        <input type ="text" name ="stno" id="stno" value="<?php echo isset($st) ? $st : ''; ?>" maxlength="30"/>
                    </td>
                </tr> -->
                <tr>
                    <td>Phone</td>
                    <td>
                        <input type ="text" name ="phno" id="phno" value="<?php echo isset($phone) ? $phone : ''; ?>" maxlength="10"/>
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>
                        <input type ="text" name ="email" id="email" size="30" value="<?php echo isset($email) ? $email : ''; ?>" maxlength="30"/>
                    </td>
                </tr>
            </table>
            <input type="button" id="edit_ledger_master" name="edit_ledger_master" class="btn btn-default" value="Edit Ledger Details" onclick="editLedger();">
        </form>
    </div>
</div>
<script>
  jQuery(document).ready(function () {
    if($("#gstno").val()=="N.A.")
    {   
        $("#gst_na_c").attr('checked',true);
        $("#panno").attr('readonly',false);
    }
    else{
         $("#gst_na_c").attr('checked',false);
         $("#panno").attr('readonly',true);

    }
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
<script>
    function editLedger() {
        var ledgername = jQuery("#ledgername").val();
        var gstno=jQuery("#gstno").val();
        var panno=jQuery("#panno").val();
        var r=new RegExp('[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9]{1}[Z]{1}[A-Z-0-9]{1}');
        var p=new RegExp('[A-Z]{5}[0-9]{4}[A-Z]{1}');

        var dataForm = jQuery("#editform_ledger").serialize();
        var dataString = "work=editLedger&" + dataForm;
        if (ledgername == '') {
            var data = 'Please Enter Ledger Name';
            jQuery("#editmsg_ledger").show();
            jQuery("#editmsg_ledger").append(data);
            jQuery("#editmsg_ledger").fadeOut(6000);
         $("html, body").animate({ scrollTop: 0 }, 1000);
        return false;
    } 

    else if($('#gst_na_c').is(':not(:checked)') && !r.test(gstno))
    {
        
        alert("Please Enter Correct Format of GST");
        return false;
    }

   else if($('#gst_na_c').is(':not(:checked)') && $("#gstno").val()==""){
        alert("GST information is mandatory.");
        return false;
    }

   else if($("#panno").val()!="" && !p.test(panno)){
        alert("Please Enter Correct Format of PAN.");
        return false;
    }

        else {
            jQuery.ajax({
                url: "ledger_ajax.php",
                type: 'POST',
                cache: false,
                data: dataString,
                dataType: 'html',
                success: function (html) {
                    jQuery("#editmsg_ledger").html('');
                    jQuery("#editmsg_ledger").show();
                    jQuery("#editmsg_ledger").append(html);
                    jQuery("#editmsg_ledger").fadeOut(3000);
                    window.location = 'ledger.php';
                }
            });
        }
    }

</script>