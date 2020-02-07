<?php
/**
 * Edit Client master form
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);

$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:salesengage.php?pg=view-client');
}
$getclientdata = $sales->getclientdata_byid($id);
//print_r($getclientdata);

$dob =  $getclientdata[0]['dob'];
$newdobDate = date("d-m-Y", strtotime($dob));

if($newdobDate=="00-00-0000" || $newdobDate=='30-11--0001'){
    $newdobDate1 = date('d-m-Y');
}else{
    $newdobDate1 = date("d-m-Y", strtotime($dob));    
}
?>
<br/>
<div class='container'>
    <center>
    <form name="editclientmasterform" id="editclientmasterform" method="POST"  onsubmit="editclientdata();return false;">
    <table class='table table-condensed'>
        <thead>
            <tr>
                <td colspan="100%" class="tdnone">
                    <div>
                        <a href="salesengage.php?pg=view-client" class="backtextstyle">Back To Client View</a>
                    </div>
                </td>
            </tr>
            <tr><th colspan="100%" >Update Client </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Name <span class="mandatory">*</span></td><td><input type="text" name="clname" value='<?php echo $getclientdata[0]['clientname']; ?>' required></td></tr>
            <tr><td class='frmlblTd'>Address </td><td><textarea name="caddress" id="caddress"><?php echo $getclientdata[0]['address']; ?></textarea></td></tr>
            <tr><td class='frmlblTd'>Email <span class="mandatory">*</span></td><td><input type='text' name='cemail' id='cemail'  value='<?php echo $getclientdata[0]['cemail']; ?>' style="width:100%;"><br><span style="font-size: 9px; font-weight: bold; ">You can add multiple email ids with Comma separated.<br/>For eg:- test@gmail.com,test1@gmail.com</span></td></tr>
            <tr><td class='frmlblTd'>Mobile No. <span class="mandatory">*</span></td><td><input type='text' name='cmobile' id='cmobile' value='<?php echo $getclientdata[0]['mobileno'];?>'></td></tr>
            <tr><td class='frmlblTd'>Birth Date</td><td><input type='text' name='cbirthdate'  id='cbirthdate' value='<?php echo $newdobDate1;?>'></td></tr>
            <tr><td colspan="100%" class='frmlblTd'>
                    <input type="submit" name="clientsubmit" value="Update" class='btn btn-primary'>  
                </td></tr>
        </tbody>
    </table>
        <input type='hidden' name='clientid' id='clientid' value='<?php echo $getclientdata[0]['id']; ?>'>
    </form>
    </center>
</div>
