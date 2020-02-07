<?php
/**
 * Edit Stage master form
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno, $userid);

$id = $_GET['id'];
if ($id == "" || $id == "0") {
    header('location:salesengage.php?pg=view-lost');
}
$getlostdata = $sales->getlostdata_byid($id);
?>
<br/>
<div class='container'>
    <center>
            <form name="editlostmasterform" id="editlostmasterform" method="POST"  onsubmit="editlostdata();return false;">
            <table class='table table-condensed'>
                <thead>
                    <tr>
                        <td colspan="100%" class="tdnone">
                            <div>
                                <a href="salesengage.php?pg=view-lost" class="backtextstyle"> Back To Lost View </a>
                            </div>
                        </td>
                    </tr>
                    <tr><th colspan="100%" >Update Lost Reason </th></tr>
                </thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr><td class='frmlblTd'>Reason <span class="mandatory">*</span></td><td><input type="text" name="lostreason" id='lostreason' value='<?php echo $getlostdata[0]['lostname']; ?>' required></td></tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="lostreasonsubmit" value="Update" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
            <input type='hidden' id='lostreasonid' name='lostreasonid' value='<?php echo $getlostdata[0]['id']; ?>'>
        </form>
    </center>
</div>
