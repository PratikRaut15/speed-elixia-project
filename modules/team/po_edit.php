<?php
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
$_scripts_custom[] = "../../scripts/team/po_edit.js";
include("header.php");

$db = new DatabaseManager();

function PrepareSP($sp_name, $sp_params) {
    return "call " . $sp_name . "(" . $sp_params . ");";
}

$poid = $_GET['poid'];
$cust = $_GET['cust'];

$pdo = $db->CreatePDOConn();
$sp_params =  "'" . $cust . "'"
        . ",'" . $poid . "'"
;
$QUERY = PrepareSP('get_po', $sp_params);
$row = $pdo->query($QUERY)->fetch(PDO::FETCH_ASSOC);

$pono = $row['pono'];
if ($row['podate'] == '' || $row['podate'] == '1970-01-01' || $row['podate'] == '0000-00-00') {
    $podate = '';
} else {
    $podate = date("d-m-Y", strtotime($row['podate']));
}
$poamount = $row['poamount'];
if ($row['poexpiry'] == '' || $row['poexpiry'] == '1970-01-01' || $row['poexpiry'] == '0000-00-00') {
    $poexpiry = '';
} else {
    $poexpiry = date("d-m-Y", strtotime($row['poexpiry']));
}
$description = $row['description'];
$customer = $row['customerno'];
$db->ClosePDOConn($pdo);

$SQL1 = sprintf("SELECT customerno,customername,customercompany FROM ".DB_PARENT.".scustomer WHERE customerno = %d", $cust);
$db->executeQuery($SQL1);
if ($db->get_rowCount() > 0) {
    $row1 = $db->get_nextRow();
    $cno = $row1['customerno'];
    $customercompany = $row1['customercompany'];
}
?>
<div class="panel">
    <div class="paneltitle" align="center">Edit PO Details</div> 
    <div class="panelcontents">
        <span id="error_pono" style="display: none;color: #FF0000;text-align: center">Please Enter PO Number</span>
        <span id="edit_po_succ" style="display: none;color: #00493a;text-align: center">Edit Successfully</span>
        <span id="fail_edit_po" style="display: none;color: #FF0000;text-align: center">Some Error Has Occurred Try Again</span>
        <form method="post" name="edit_po" id="edit_po">
            <table>
                <tr><td>Customer No</td>
                    <td>    
                        <input type ="text" name ="cust_grp" id="cust_grp" value="<?php echo $cno; ?>-<?php echo $customercompany; ?>" size="30" readonly/>
                    </td>
                <input type ="hidden" name ="cid" id="cid" value="<?php echo $cust; ?>"/>
                <input type ="hidden" name ="poid" id="poid" value="<?php echo $poid; ?>"/>
                </tr>
                <tr>
                    <td>PO Number</td>
                    <td>
                        <input type ="text" name ="po_no" id="po_no" value="<?php echo $pono; ?>">
                    </td>
                </tr>
                <tr>
                    <td>PO Date</td>
                    <td>
                        <input type ="text" name ="podate" id="podate" value="<?php echo $podate; ?>"/><button id="trigger10">...</button>
                    </td>
                </tr>
                <tr>
                    <td>PO Expiry</td>
                    <td>
                        <input type ="text" name ="poexp" id="poexp" value="<?php echo $poexpiry; ?>"/><button id="trigger11">...</button>
                    </td>
                </tr>
                <tr>
                    <td>PO Amount</td>
                    <td>
                        <input type ="text" name ="poamt" id="poamt" value="<?php echo $poamount; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>
                        <textarea name ="podesc" id="podesc" cols="30" rows="5"><?php echo $description; ?></textarea>
                    </td>
                </tr>
            </table>
            <input type="button" id="edit_po" name="edit_po" class="btn btn-default" value="Edit PO Details" onclick="editAccountpo();">
        </form>
    </div>
</div>