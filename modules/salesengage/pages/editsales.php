<?php
/**
 * Edit Sales master form
 */

require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);

$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:salesengage.php?pg=view-sales');
}
$getsalesdata = $sales->getsaleseditdata($id);
?>
<br/>
<div class='container'>
    <center>
    <form name="editsalesmasterform" id="editsalesmasterform" method="POST"  onsubmit="editsalesmdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Sales Manage </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>S R Name <span class="mandatory">*</span></td><td><input type="text" name="srname" id="srname" value="<?php if(!empty($getsalesdata[0]['srname'])){echo $getsalesdata[0]['srname'];} ?>" required></td></tr>
            <tr><td class='frmlblTd'>Email <span class="mandatory">*</span></td><td><input type="text" name="sremail" id="sremail" value="<?php if(!empty($getsalesdata[0]['sremail'])){echo $getsalesdata[0]['sremail'];} ?>" required></td></tr>
            <tr><td class='frmlblTd'>Phone <span class="mandatory">*</span></td><td><input type="text" name="srphone" id="srphone" value="<?php if(!empty($getsalesdata[0]['srphone'])){echo $getsalesdata[0]['srphone'];} ?>" ></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="srsubmit" value="Update" class='btn btn-primary'></td></tr>
            <input type="hidden" id="srid" name="srid" value="<?php if(!empty($getsalesdata[0]['id'])){echo $getsalesdata[0]['id'];} ?>">
        </tbody>
    </table>
    </form>
    </center>
</div>
