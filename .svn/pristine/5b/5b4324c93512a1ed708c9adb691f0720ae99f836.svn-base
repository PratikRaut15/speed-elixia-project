<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/Sanitise.php");

$did = GetSafeValueString(isset($_GET["did"]) ? $_GET["did"] : $_POST["did"], "long");
// See if we need to save a new one.
//$message="";
$db = new DatabaseManager();

$message = "";
$pinvoice = "";
$pono = "";
$podate = "";
$dinstalldate = "";
$invdate = "";
//$deferdate = "";

if (!empty($_POST)) {
    $changetype = GetSafeValueString($_POST["changetype"], "long");
    $today = date("Y-m-d");
    if ($changetype == 1) {
        $pinvoice = GetSafeValueString($_POST["pinvoice"], "string");
        $pono = GetSafeValueString($_POST["pono"], "string");
        $podate = GetSafeValueString($_POST["cpodate"], "string");
        $dinstalldate = GetSafeValueString($_POST["dinstalldate"], "string");
        $invdate = GetSafeValueString($_POST['invdate'], "string");
        $did = GetSafeValueString($_POST["did"], "string");
        $cno = GetSafeValueString($_POST["cno"], "string");


        $podate = date('Y-m-d', strtotime($podate));
        $dinstalldate = date('Y-m-d', strtotime($dinstalldate));
        $invdate = date('Y-m-d', strtotime($invdate));
        $SQL = sprintf("UPDATE devices SET device_invoiceno='" . $pinvoice . "', installdate='" . $dinstalldate . "', po_no='" . $pono . "', po_date='" . $podate . "', inv_generatedate='" . $invdate . "' where deviceid=$did");
        $db->executeQuery($SQL);
        header("Location: pending_invoice.php?cno= $cno");
    } else if ($changetype == 2) {
        $defdate = GetSafeValueString(date("Y-m-d", strtotime($_POST["defdate"])), "string");
        $SQL1 = sprintf("UPDATE devices SET inv_deferdate = '%s' WHERE deviceid = %d", $defdate, $did);
        $db->executeQuery($SQL1);
        if ($today == $defdate) {
            $SQL2 = sprintf("UPDATE devices SET inv_device_priority = 1 WHERE deviceid = %d", $did);
            $db->executeQuery($SQL2);
        }
        header("Location: pending_invoice.php?cno= $cno");
    }
}
$sql = sprintf("Select * from `devices` where deviceid=%d", $did);
$db->executeQuery($sql);
if ($db->get_rowCount() > 0) {
    $row = $db->get_nextRow();

    $dinstalldate = $row["installdate"];
    $invoiceno = $row["device_invoiceno"];
    $invdate = $row['inv_generatedate'];
    $po_no = $row["po_no"];
    $podate = $row["po_date"];
    $cust = $row["customerno"];
    $dedate = $row["inv_deferdate"];
}


include("header.php");
?>

<div class="panel">
    <div class="paneltitle" align="center">Update Pending Invoice </div>
    <div class="panelcontents">
        <form method="post" id="invform" name="invform">

            <div>
                <span id="invoiceno_error" style='display:none;color:red; font-size:12px;'> Enter Device Invoice No </span>
                <span id="insdate_error" style='display:none;color:red; font-size:12px;'> Enter Install Date</span>
                <span id="invdate_error" style='display:none;color:red; font-size:12px;'> Enter Invoice Generation Date</span>
                <span id="change_error" style='display:none;color:red; font-size:12px;'> Please Select Change Type</span>
                <span id="defer_error" style='display:none;color:red; font-size:12px;'> Enter Deferred Date</span>
            </div>
            <br/>

            <input type="hidden" name = "did" value="<?php echo($did) ?>"/>
            <table width="50%">
                <input type="hidden" name="cno" id="cno" value="<?php echo $cust; ?>"
                       <tr>
                    <td>
                        Change
                    </td>
                    <td>
                        <select id="changetype" name="changetype">
                            <option value="0">Select Change Type</option>
                            <option value="1">Invoice Details</option>
                            <option value="2">Deferred Date</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td> Device Invoice No.</td>
                    <td> <input  type="text" name="pinvoice" id="pinvoice" value="<?php echo $invoiceno; ?>"/>
                    </td>
                </tr>
                <?php
                if ($dinstalldate == "0000-00-00" || $dinstalldate == "1970-01-01") {
                    $dinstalldate = "";
                } else {
                    $dinstalldate = date('d-m-Y', strtotime($dinstalldate));
                }
                ?>
                <tr>
                    <td>Device Install Date </td>
                    <td> <input name="dinstalldate" id="dinstalldate" type="text" value="<?php echo $dinstalldate ?>"/><button id="trigger1">...</button>
                    </td>
                </tr>
                <?php
                if ($invdate == "0000-00-00" || $invdate == "1970-01-01") {
                    $invdate = "";
                } else {
                    $invdate = date('d-m-Y', strtotime($invdate));
                }
                ?>
                <tr>
                    <td>Invoice Generation Date </td>
                    <td> <input name="invdate" id="invdate" type="text" value="<?php echo $invdate; ?>"/><button id="trigger3">...</button>
                    </td>
                </tr>
                <tr>
                    <td>PO No.</td><td><input id="pono" name = "pono" type="text" value="<?php echo $po_no; ?>"></td>
                </tr>

                <?php
                if ($podate == "0000-00-00" || $podate == "1970-01-01") {
                    $podate = "";
                } else {
                    $podate = date('d-m-Y', strtotime($podate));
                }
                ?>
                <tr>
                    <td>PO Date </td>
                    <td> <input type="text" name="cpodate" id="cpodate" value="<?php echo $podate; ?>"/><button id="trigger2">...</button>
                    </td>
                </tr> 
                <?php
                if ($dedate == "0000-00-00" || $dedate == "1970-01-01") {
                    $dedate = "";
                } else {
                    $dedate = date('d-m-Y', strtotime($dedate));
                }
                ?>
                <tr>
                    <td>Deferred Date </td>
                    <td> <input type="text" name="defdate" id="defdate" value="<?php echo $dedate; ?>"/><button id="trigger4">...</button>
                    </td>
                </tr> 



            </table>
            <input type="button" name="save" value="Save Invoice" onclick="ValidateForm()"/>
        </form>
    </div>
</div>

<?php
include("footer.php");
?>
<script type="text/javascript">
    Calendar.setup(
            {
                inputField: "cpodate", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger2" // ID of the button
            });

    Calendar.setup(
            {
                inputField: "dinstalldate", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger1" // ID of the button
            });
    Calendar.setup(
            {
                inputField: "invdate", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger3" // ID of the button
            });
    Calendar.setup(
            {
                inputField: "defdate", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger4" // ID of the button
            });
    function ValidateForm()
    {
        var invno = jQuery("#pinvoice").val();
        var insdate = jQuery("#dinstalldate").val();
        var invdate = jQuery("#invdate").val();
        var change = jQuery("#changetype").val();
        var defdate = jQuery("#defdate").val();
        if (change == 0) {
            jQuery("#change_error").show();
            jQuery("#change_error").fadeOut(2000);
        }
        else if (invno == "" && change == 1)
        {

            jQuery("#invoiceno_error").show();
            jQuery("#invoiceno_error").fadeOut(2000);
        }
        else if (insdate == "" && change == 1)
        {
            jQuery("#insdate_error").show();
            jQuery("#insdate_error").fadeOut(2000);
        }
        else if (invdate == "" && change == 1)
        {
            jQuery("#invdate_error").show();
            jQuery("#invdate_error").fadeOut(2000);
        }
        else if (defdate == "" && change == 2) {
            defer_error
            jQuery("#defer_error").show();
            jQuery("#defer_error").fadeOut(2000);
        }
        else
        {
            jQuery("#invform").submit();
        }
    }
</script>