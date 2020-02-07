<?php
/**
 * Edit Location master form
 */
require_once "mobility_function.php";
$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:mobility.php?pg=add-location');
}
$mob = new Mobility($_SESSION['customerno'], $_SESSION['userid']);
$locdata = $mob->getlocationdata_byid($id);
?>
<br/>
<div class='container' >
    <center>
        <input type='hidden' id='forAuto' value='City'/>
    <form name="editlocationmasterform" id="editlocationmasterform" method="POST" action="mobility.php?pg=edit-location" onsubmit="editlocationdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Location Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr>
                <td class='frmlblTd'>City Name <span class="mandatory">*</span></td>
                <td>
                    <input type="text" id="citynameauto" name="citynameauto" value='<?php echo $locdata[0]['cityname'];?>' required>
                    <input type='hidden' id='cityid' name='cityid' value="<?php echo $locdata[0]['cityid'];?>">
                </td>
            </tr>
            <tr><td class='frmlblTd'>Location Name <span class="mandatory">*</span></td><td><input type="text" name="locationname" value="<?php echo $locdata[0]['location']?>" required></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="locationsubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="locid" id="locid" value="<?php echo $locdata[0]['locid'];?>">
    </form>
    </center>
</div>
