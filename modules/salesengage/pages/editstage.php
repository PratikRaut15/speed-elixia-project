<?php
/**
 * Edit Stage master form
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);

$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:salesengage.php?pg=view-stage');
}
$getstagedata = $sales->getstagedata_byid($id);
?>
<br/>
<div class='container'>
    <center>
    <form name="editstagemasterform" id="editstagemasterform" method="POST"  onsubmit="editstagedata();return false;">
    <table class='table table-condensed'>
        <thead>
            <tr>
                <td colspan="100%" class="tdnone">
                    <div>
                        <a href="salesengage.php?pg=view-stage" class="backtextstyle"> Back To Stage View </a>
                    </div>
                </td>
            </tr>
            <tr><th colspan="100%" >Update Stage </th></tr>
        </thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Stage Name <span class="mandatory">*</span></td><td><input type="text" name="stagename" id='stagename' value='<?php echo $getstagedata[0]['stagename'];?>' required></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="stagesubmit" value="Update" class='btn btn-primary'>
<!--                <a href="salesengage.php?pg=view-stage">View Stages</a>-->
                </td></tr>
        </tbody>
    </table>
        <input type='hidden' id='stageid' name='stageid' value='<?php echo  $getstagedata[0]['id'];?>'>
    </form>
    </center>
</div>
