<?php
/**
 * Edit Package master form
 */
require_once "mobility_function.php";
$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:mobility.php?pg=add-package');
}
$mob = new Mobility($_SESSION['customerno'],$_SESSION['userid']);
$getpackgdata = $mob->getpackagedata_byid($id);
$validity = $getpackgdata[0]['validity'];
$validity1 = date("d-m-Y", strtotime($validity));
if($validity1=="00-00-0000" || $validity1=='30-11--0001'){
    $validate2 = date('d-m-Y');
}else{
    $validate2 = date("d-m-Y", strtotime($validity));    
}

?>
<br/>
<div class='container'>
    <center>
        <form name="editpackageform" id="editpackageform" method="POST" action="mobility.php?pg=edit-package" onsubmit="editpackdata();return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Update Package Master</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr><td class='frmlblTd'>Membership Code<span class="mandatory">*</span></td><td><input type="text" name="membershipcode" value="<?php echo $getpackgdata[0]['package_code']; ?>" required></td></tr>
                    <tr><td class='frmlblTd'>Amount <span class="mandatory">*</span></td><td><input type="text" name="amount" value="<?php echo $getpackgdata[0]['amount']; ?>" required></td></tr>
                    <tr><td class='frmlblTd'>Validity <span class="mandatory">*</span></td><td><input type="text" name="membervalidity" value="<?php echo $validate2; ?>" required></td></tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="citysubmit" value="Add" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
            <input type="hidden" name="pckgid" id="pckgid" value="<?php echo $getpackgdata[0]['pckgid'];?>"/>
        </form>
    </center>
</div>
