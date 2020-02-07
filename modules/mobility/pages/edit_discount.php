<?php
require_once "mobility_function.php";
/**
 * Edit Discount form
 */
$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:mobility.php?pg=add-discount');
}
$mob = new Mobility($_SESSION['customerno'], $_SESSION['userid']);
$getdiscountdata = $mob->getdiscountdata_byid($id);
$getspecific_discount = $mob->getspdiscount_byid($id);

$spc_count =  count($getspecific_discount);
$clientname1= array();
for($i=0; $i<$spc_count; $i++){
    $clientname1[] =  $getspecific_discount[$i]['client_name'];
}
$clientname = array_filter($clientname1);
$clientname1 = implode(",",$clientname);

$amount = $getdiscountdata[0]['amount'];
$percentage = $getdiscountdata[0]['percentage'];
$amt_value="";
if($amount!="0.00"){ 
 $amt_value = $amount;
}elseif ($percentage!="0.00") {
 $amt_value = $percentage;   
}
$cityid = $getspecific_discount[0]['cityid'];
$cityname =  $getspecific_discount[0]['cityname'];
$locationid =  $getspecific_discount[0]['locationid'];
$location =  $getspecific_discount[0]['location'];
$groupid =  $getspecific_discount[0]['branchid'];
$branchname = $getspecific_discount[0]['branchname'];
?>
<br/>
<div class='container'>
    <center>
    <form id="editdiscount" name="editdiscount" method="POST" action="mobility.php?pg=view-discount" onsubmit="editDiscount();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Discount</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                <tr><td class='frmlblTd'>Discount Code<span class="mandatory">*</span></td><td><input type="text" name="disccode" id="disccode"  value="<?php echo $getdiscountdata[0]['discount_code'];?>" required></td></tr>
                <tr><td class='frmlblTd'>Expiry Date </td><td><input type="text" name="expdate" value="<?php echo $getdiscountdata[0]['expiry_date']; ?>"></td></tr>
            <tr><td class='frmlblTd'>Discount Type</td><td><input type="radio" name="disctype" value="1" <?php if($amount!='0.00'){echo "checked='checked'"; }?>> Amount <input type="radio" name="disctype" value="2" <?php if($percentage!='0.00'){echo "checked='checked'"; }?>> Percentage </td></tr>
            <tr id="distype_val"><td class='frmlblTd'>&nbsp;</td><td><input type="text" name="disctype_value" step="0.01" value="<?php echo $amt_value;?>"></td></tr>
            <tr><td class='frmlblTd'>Specific Type</td>
                <td>
                    <input type="radio" name="spectype1" value="1" <?php if(!empty($clientname)){echo "checked='checked'";}?>> Individual User
                    <input type="radio" name="spectype1" value="2" <?php if($locationid!="0"){echo "checked='checked'";}?>> Location 
                    <input type="radio" name="spectype1" value="3" <?php if($groupid!="0"){echo "checked='checked'";}?> > Branch 
                    <input type="radio" name="spectype1" value="4" <?php if($cityid!="0"){echo "checked='checked'";}?> > City 
                </td></tr>
            
            <tr id="sp_type_individual1" <?php if(!empty($clientname)){ ?>style="display: table-row;" <?php }else{?>style="display:none;"<?php }?>><td class='frmlblTd'>&nbsp;</td>
                <td>
                    <input type='text' placeholder="Name" name="ind_client1" id="ind_client1" style="width: 100%;" value="<?php if(!empty($clientname)){echo $clientname1;}?>"/>
                </td>
            </tr>
            <tr id="sp_type_loc1" <?php if($locationid!="0"){ ?>style="display:table-row;"<?php }else{?>style="display:none;"<?php } ?>><td class='frmlblTd'>&nbsp;</td>
                <td>
                    <input type='text' placeholder="Location" name="location" id="location" value="<?php if(!empty($location)){echo $location;}?>"/>
                    <input type='hidden' id="locid" name="locid" value="<?php if($locationid!='0'){echo $lcoationid;}?>">
                </td>
            </tr>
            <tr id="sp_type_group1" <?php if($groupid!="0"){ ?>style="display: table-row;" <?php }else{?>style="display:none;"<?php }?>><td class='frmlblTd'>&nbsp;</td>
                <td>
                    <input type='text' placeholder="Branch" name="branchname" id="branchname" value="<?php if(!empty($branchname)){echo $branchname;}?>"/>
                    <input type='hidden' id="grpid" name="grpid" value="<?php if($groupid!='0'){echo $groupid;}?>">
                </td>
            </tr>
            <tr id="sp_type_city1" <?php if($cityid!="0"){ ?>style="display:table-row;"<?php }else{?>style="display:none;"<?php } ?>><td class='frmlblTd'>&nbsp;</td>
                <td>
                    <input type='text' placeholder="Location" name="cityname" id="cityname" value="<?php if(!empty($cityname)){echo $cityname;}?>"/>
                    <input type='hidden' id="cityid" name="cityid" value="<?php if($cityid!='0'){echo $cityid;}?>">
                </td>
            </tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="discountid" id="discountid" value="<?php echo $id;?>">
    </form>
    </center>
</div>
