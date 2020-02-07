<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once '../../lib/bo/VehicleManager.php';
//include_once '../../modules/realtimedata/rtd_functions.php';
// 
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();

class VOList {
    
}

$vid = $_REQUEST['vid'];
$uid = $_REQUEST['uid'];
$cno = $_REQUEST['cno'];

$SQL = sprintf("SELECT vehicleno FROM vehicle where vehicleid=%d", $vid);
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $vehicleno = $row["vehicleno"];
    }
}
$SQL = sprintf("SELECT remark,alterremark,issue_type FROM unit where unitno=%d", $uid);
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $remark = $row["remark"];
        $alterremark = $row["alterremark"];
        $issue_type = $row["issue_type"];
    }
}

if (isset($_POST["usubmit"])) {
    $remark1 = GetSafeValueString($_POST["remark1"], "string");
    $remark = GetSafeValueString($_POST["remark"], "string");
    $customerno = GetSafeValueString($_POST["customerno"], "string");
    $vehicleid = GetSafeValueString($_POST["vehicleid"], "string");
    $unitno = GetSafeValueString($_POST["unitid"], "string");
    $issue = GetSafeValueString($_POST["issue"], "string");
    
    $SQL = sprintf("Update unit SET remark='%s', alterremark='%s',issue_type='%d' where unitno=%d and vehicleid=%d and customerno=%d ", $remark1, $remark, $issue, $unitno, $vehicleid, $customerno);
    $db->executeQuery($SQL);
    header("Location: inactive.php");
}
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<?php
$_scripts[] = "../../scripts/prototype.js";
//$_scripts[] = "../../scripts/jquery-1.7.2.min.js";
//$_scripts[] = "../../scripts/jquery-ui-1.8.13.custom.min.js";
//$_scripts[] = "../../scripts/jQueryRotate.2.1.js";
$_scripts[] = "../../scripts/jquery-latest.js";
$_scripts[] = "../../scripts/tablesorter/jquery.tablesorter.js";



include("header.php");
?>


<!---------------------------------------------------   Add Remark Form  --------------------------------------------------------------> 

<div class="panel">
    <div class="paneltitle" align="center">Add Remark For Vehicle No. - <?php echo $vehicleno; ?></div>
    <div class="panelcontents">
<?php
//display_vehicledata_all_inactive(getvehicles_all_inactive());
?>

        <form method="post" name="myform" id="myform" action="addremark.php" onsubmit="return ValidateForm(); return false;" enctype="multipart/form-data">
            <table width="40%">
                <tr>
                    <td colspan="2"><h3> Remark </h3></td>
                </tr>
                <tr>
<!--                    <td>
                        <select name="remark1" id="remark1">
                            <option value="0"> Select Remark</option>
                            <?php
                            $SQL = sprintf("SELECT * FROM remarks order by id ASC");
                            $db->executeQuery($SQL);
                            if ($db->get_rowCount() > 0) {
                                while ($row = $db->get_nextRow()) {
                                    if($remark == $row['id']){
                                   echo "<option value='".$row['id']."' selected='' >".$row['name']."</option>";
                                    }
                                    else{
                                      echo "<option value='".$row['id']."' >".$row['name']."</option>";  
                                    }
                                }
                            }
                            ?>
                        </select>
                    </td>            -->
                </tr>
                <tr><td>
                        <input type="radio" name="issue" value="1" <?php echo ($issue_type=='1')?'checked':'' ?>/>Customer issue <input type="radio" name="issue" <?php echo ($issue_type=='2')?'checked':'' ?> value="2"/> Elixia issue </td></tr>
<!--                <tr><td><br/>OR <br/></td></tr>-->
                <tr>
                    <td>
                        <textarea style="width: 500px;" rows="5" id="remark" name="remark"><?php if ($alterremark != '') {
                                echo $alterremark;
                            } ?></textarea>
                    </td>
                </tr>
                <input type="hidden" name="vehicleid" id="vehicleid" value="<?php echo $vid; ?>"/>
                <input type="hidden" name="unitid" id="unitid+" value="<?php echo $uid; ?>"/>
                <input type="hidden" name="customerno" id="customerno" value="<?php echo $cno; ?>"/>


            </table>
            <br/>
            <div><input type="submit" id="usubmit" name="usubmit" value="Save"/></div>
        </form>



    </div>

</div>   


<?php
include("footer.php");
?>
<script>
    jQuery.noConflict();
    jQuery(document).ready(function() 
    { 
	
        jQuery("#myTable").tablesorter(); 
    } 
); 

function ValidateForm(){
    if($('input[name=issue]:checked').length<=0)
    {
     alert("Please check issue");
     return false;
    }
}
    function add_remark(vehicleid,customerno)
    {
     
        var data="vehicleid="+vehicleid+"&customerno="+customerno;
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route.php",
            data: data,
            cache: false,
            success: function (json) {
                //alert(json);
                jQuery('#add_remark').show();
                jQuery("#header-2").html('Remark For Vehicle No.- '+json);  
            }
        });
    }

</script>