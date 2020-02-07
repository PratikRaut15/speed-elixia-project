<?php
require_once "mobility_function.php";
/**
 * Edit City master form
 */
$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:mobility.php?pg=add-city');
}
$mob = new Mobility($_SESSION['customerno'], $_SESSION['userid']);
$citydata = $mob->getcitydata_byid($id);
?>
<br/>
<div class='container' >
    <center>
    <form name="cityeditmasterform" id="cityeditmasterform" method="POST" action="mobility.php?pg=view-city" onsubmit="editcitydata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update City Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'><input type="hidden" name="cid" id="cid" value="<?php echo $id;?>">City Name <span class="mandatory">*</span></td><td><input type="text" name="cityname" value="<?php echo $citydata[0]['cityname'];?>" required></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="citysubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
