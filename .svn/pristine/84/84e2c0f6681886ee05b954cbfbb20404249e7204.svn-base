<?php
/**
 * Edit Order Source master form
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);

$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:salesengage.php?pg=view-sourceorder');
}
$getsourcedata = $sales->getsourceorder_byid($id);
?>
<br/>
<div class='container'>
    <center>
    <form name="editsourceordform" id="editsourceordform" method="POST"  onsubmit="editordersourcedata();return false;">
    <table class='table table-condensed'>
        <thead>
            <tr>
                <td colspan="100%" class="tdnone">
                    <div>
                        <a href="salesengage.php?pg=view-sourceorder" class="backtextstyle"> Back To Order Source View</a>
                    </div>
                </td>
            </tr>
            <tr><th colspan="100%" >Update Order Source </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr>
                <td class='frmlblTd'>Order Source <span class="mandatory">*</span></td>
                <td><input type="text" name="order_source" id='order_source' value='<?php echo $getsourcedata[0]['sourceorder'];?>' required></td>
            </tr>
            <tr>
                <td colspan="100%" class='frmlblTd'><input type="submit" name="stagesubmit" value="Update" class='btn btn-primary'>
<!--                <a href="salesengage.php?pg=view-sourceorder">View Source order</a>-->
                </td>
            </tr>
        </tbody>
    </table>
        <input type='hidden' id='srcordid' name='srcordid' value='<?php echo  $getsourcedata[0]['id'];?>'>
    </form>
    </center>
</div>
