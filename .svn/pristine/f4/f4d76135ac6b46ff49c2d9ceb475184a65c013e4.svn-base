<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
$_scripts_custom[] = "../../scripts/team/invedit_address.js";
include("header.php");

$db = new DatabaseManager();

function PrepareSP($sp_name, $sp_params) {
    return "call " . $sp_name . "(" . $sp_params . ");";
}

$invid = $_GET['inid'];
$acc_customer = $_GET['acc_cust'];
// Fetch Data
$pdo = $db->CreatePDOConn();
$sp_params = "''"
        . ",''"
        . ",'" . $invid . "'"
;
$QUERY = PrepareSP('get_invoice_customer_address', $sp_params);
$row = $pdo->query($QUERY)->fetch(PDO::FETCH_ASSOC);
$invcustid = $row['invcustid'];
$invmane = $row['invoicename'];
$add1 = $row['address1'];
$add2 = $row['address2'];
$add3 = $row['address3'];
$pan = $row['pan_no'];
$cst = $row['cst_no'];
$vat = $row['vat_no'];
$st = $row['st_no'];
$phone = $row['phone'];
$email = $row['email'];
$acc_cust = $row['customer'];
$custno = $row['customergroup'];
$db->ClosePDOConn($pdo);

$SQL1 = sprintf("SELECT customerno,customername,customercompany FROM ".DB_PARENT.".customer WHERE customerno = %d", $custno);
$db->executeQuery($SQL1);
if ($db->get_rowCount() > 0) {
    $row1 = $db->get_nextRow();
    $cno = $row1['customerno'];
    $customercompany = $row1['customercompany'];
}

?>

<div class="panel">
    <div class="paneltitle" align="center">Edit Account Customer</div> 
    <div class="panelcontents">
        <span id="error_name" style="display: none;color: #FF0000;text-align: center">Please Enter Invoice Name</span>
        <span id="edit_cust_succ" style="display: none;color: #00493a;text-align: center">Edit Successfully</span>
        <span id="fail_edit_cust" style="display: none;color: #FF0000;text-align: center">Some Error Has Occurred Try Again</span>
        <form method="post" name="edit_cust" id="edit_cust">
            <table>
                <tr><td>Customergroup</td>
                    <td>    
                        <input type ="text" name ="cust_grp" id="cust_grp" value="<?php echo $cno; ?>-<?php echo $customercompany; ?>" size="30" readonly/>
                    </td>
                <input type ="hidden" name ="cid" id="cid" value="<?php echo $cno; ?>"/>
                <input type ="hidden" name ="invcustid" id="invcustid" value="<?php echo $invcustid; ?>"/>
                </tr>
                <tr><td>Customer</td>
                    <td>    
                        <input type ="text" name ="cust" id="cust" value="<?php echo $acc_cust;?>" readonly/>
                    </td>
                </tr>
                <tr>
                    <td>Invoice Client Name</td>
                    <td>
                        <input type ="text" name ="invname" id="invname" value="<?php echo $invmane; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Address Line 1</td>
                    <td>
                        <input type ="text" name ="add1" id="add1" size="50" value="<?php echo $add1; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Address Line 2</td>
                    <td>
                        <input type ="text" name ="add2" id="add2" size="50" value="<?php echo $add2; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Address Line 3</td>
                    <td>
                        <input type ="text" name ="add3" id="add3" size="50" value="<?php echo $add3; ?>">
                    </td>
                </tr>
                <tr>
                    <td>PAN No.</td>
                    <td>
                        <input type ="text" name ="panno" id="panno" value="<?php echo $pan; ?>">
                    </td>
                </tr>
                <tr>
                    <td>CST No.</td>
                    <td>
                        <input type ="text" name ="cstno" id="cstno" value="<?php echo $cst; ?>">
                    </td>
                </tr>
                <tr>
                    <td>VAT No.</td>
                    <td>
                        <input type ="text" name ="vatno" id="vatno" value="<?php echo $vat; ?>">
                    </td>
                </tr>
                <tr>
                    <td>ST No.</td>
                    <td>
                        <input type ="text" name ="stno" id="stno" value="<?php echo $st; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>
                        <input type ="text" name ="phno" id="phno" value="<?php echo $phone; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>
                        <input type ="text" name ="email" id="email" size="30" value="<?php echo $email; ?>">
                    </td>
                </tr>
            </table>
            <input type="button" id="edit_acc_cust" name="edit_acc_cust" class="btn btn-default" value="Edit Details" onclick="editAccountCust();">
        </form>
    </div>

</div>