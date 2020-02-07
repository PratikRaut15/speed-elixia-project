<?php
error_reporting(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once ("../../lib/system/utilities.php");
include_once ("../../lib/system/Sanitise.php");

$db = new DatabaseManager();
$chid = $_GET['chid'];

$SQL = sprintf("SELECT * FROM ".DB_PARENT.".chalaan WHERE chalid = %d", Sanitise::Long($chid));
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $ch_no = $row['chalaan_no'];
        if ($row['chalaan_date'] == '0000-00-00' || $row['chalaan_date'] == '' || $row['chalaan_date'] == '1970-01-01') {
            $ch_date = '';
        } else {
            $ch_date = date("d-m-Y", strtotime($row ['chalaan_date']));
        }
        $ven_no = $row['vendor_invno'];
        if ($row['vendor_invdate'] == '0000-00-00' || $row['vendor_invdate'] == '' || $row['vendor_invdate'] == '1970-01-01') {
            $ven_date = '';
        } else {
            $ven_date = date("d-m-Y", strtotime($row['vendor_invdate']));
        }
        $uid = $row['uid'];
        $chalid = $row['chalid'];
    }
}

if (isset($_POST['usubmit'])) {
    $chalaan_no = GetSafeValueString($_POST['chalaan_no'], "string");
    $chalaan_date = date('Y-m-d', strtotime(GetSafeValueString($_POST['chalaan_date'], "string")));
    $vendor_no = GetSafeValueString($_POST['vendor_no'], "string");
    $vendor_date = date('Y-m-d', strtotime(GetSafeValueString($_POST['vendor_date'], "string")));
    $chlid = GetSafeValueString($_POST['chlid'], "string");
    $unitid = GetSafeValueString($_POST['unitid'], "string");
    
    $SQL1 = sprintf("UPDATE ".DB_PARENT.".chalaan SET chalaan_no = %d ,chalaan_date = '%s', vendor_invno = '%s', vendor_invdate = '%s' WHERE uid = %d AND chalid = %d", 
            Sanitise::String($chalaan_no), Sanitise::Date($chalaan_date), Sanitise::String($vendor_no), Sanitise::Date($vendor_date), Sanitise::String($unitid), Sanitise::String($chlid));
    $db->executeQuery($SQL1);
    header('Location:history.php?id='.$unitid);
}
include("header.php");
?>
<div class="panel">
    <div class="paneltitle" align="center">
        Edit Purchase Details</div>
    <div class="panelcontents">

        <form method="post" name="myform" id="myform" action="editchaalan.php">
            <table width="50%">
                <tr>
                    <td><input type="hidden" name = "chlid" id="chlid" value="<?php echo $chalid; ?>"></td>
                    <td><input type="hidden" name = "unitid" id="unitid" value="<?php echo $uid; ?>"></td>
                </tr>
                <tr>
                    <td>Chalaan No.</td><td><input type="text" name = "chalaan_no" id="chalaan_no" value="<?php echo $ch_no; ?>" ></td>
                </tr>
                <tr>
                    <td>Chalaan Date</td>
                    <td><input type="text" name = "chalaan_date" id="chalaan_date" value="<?php echo $ch_date; ?>"><button  id="trigger1">...</button></td>
                </tr>
                <tr>
                    <td>Vendor Invoice No.</td><td><input type="text" name = "vendor_no" id="vendor_no" value="<?php echo $ven_no; ?>"></td>
                </tr>
                <tr>
                    <td>Vendor Invoice Date</td>
                    <td><input type="text" name = "vendor_date" id="vendor_date" value="<?php echo $ven_date; ?>"><button  id="trigger2">...</button></td>
                </tr>
                <tr>
            </table>

            <div><input type="submit" id="usubmit" name="usubmit" value="Edit Purchase Unit"/></div>
        </form>
    </div>
</div>
<script type="text/javascript">
    Calendar.setup(
            {
                inputField: "chalaan_date", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger1" // ID of the button
            });
    Calendar.setup(
            {
                inputField: "vendor_date", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger2" // ID of the button
            });
</script>