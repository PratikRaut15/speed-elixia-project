<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/utilities.php");
// See if we need to save a new one.
if (IsAdmin() || IsHead()) {
    $message = "";
    if (isset($_POST["vendorname"]) && isset($_POST["vphone"]) && isset($_POST["vemail"])) {
        $db = new DatabaseManager();
        $vendorname = GetSafeValueString($_POST["vendorname"], "string");
        $vendortype = GetSafeValueString($_POST["vendortype"], "string");
        $vphone = GetSafeValueString($_POST["vphone"], "string");
        $vemail = GetSafeValueString($_POST["vemail"], "string");
        $vaddress = GetSafeValueString($_POST["vaddress"], "string");
        $servicetax = GetSafeValueString($_POST["servicetax"], "string");
        $panno = GetSafeValueString($_POST["panno"], "string");
        $vat = GetSafeValueString($_POST["vat"], "string");
        $cst = GetSafeValueString($_POST["cst"], "string");
        $sql = sprintf("INSERT INTO " . DB_PARENT . ".`team_vendor`(
            `vendor_name`,
            `vendor_type`,
            `vendor_phone`,
            `vendor_email`,
            `vendor_address`,
            `servicetax_no`,
            `panno`,
            `vatno`,
            `cstno`
            )VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s');", $vendorname, $vendortype, $vphone, $vemail, $vaddress, $servicetax, $panno, $vat, $cst);
        $db->executeQuery($sql);
        $vendorid = $db->get_insertedId();
        //$vemail = 'ganeshp@elixiatech.com';  // comment after test
        if ($vemail != "") {
            $subject = "V0" . $vendorid . "Vendor Added";
            $to = array($vemail);
            $strBCCMailIds = "sanketsheth@elixiatech.com";
            $strBCCMailIds = '';
            $attachmentFilePath = "";
            $attachmentFileName = "";
            $strCCMailIds = 'accounts@elixiatech.com';
            $strCCMailIds = '';
            $custommsg = "
            <h3>" . $subject . "</h3>
            <table>
                <tr><td>Vendor ID : </td><td>V0" . isset($vendorid) ? $vendorid : '' . "</td></tr>
                <tr><td>Vendor Name : </td><td>" . isset($vendorname) ? $vendorname : '' . "</td></tr>
                <tr><td>Vendor Type : </td><td>" . isset($vendortype) ? $vendortype : '' . "</td></tr>
                <tr><td>Vendor Contact No : </td><td>" . isset($vphone) ? $vphone : '' . "</td></tr>
                <tr><td>Vendor Email Id : </td><td>" . isset($vemail) ? $vemail : '' . "</td></tr>
                <tr><td>Vendor Address : </td><td>" . isset($vaddress) ? $vaddress : '' . "</td></tr>
                <tr><td>Service Tax : </td><td>" . isset($servicetax) ? $servicetax : '' . "</td></tr>
                <tr><td>PAN No : </td><td>" . isset($panno) ? $panno : '' . "</td></tr>
                <tr><td>VAT No : </td><td>" . isset($vat) ? $vat : '' . "</td></tr>
                <tr><td>CST No : </td><td>" . isset($cst) ? $cst : '' . "</td></tr>
            </table>";
            $message = $custommsg;
            $isEmailSent = sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
            if ($isEmailSent == 1) {
                $message = "Email send";
            }else{
                $message = "Email not send";
            }
        }
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT CONCAT('V0',vendorid) as void, vendorid,vendor_name,vendor_phone,vendor_email,vendor_address FROM " . DB_PARENT . ".team_vendor where isdeleted=0");
    $db->executeQuery($SQL);

    $dg = new datagrid($db->getQueryResult());

    $dg->AddAction("View/Edit", "../../images/edit.png", "editvendor.php?vid=%d");
    $dg->AddColumn("Vendorid", "void");
    $dg->AddColumn("Name", "vendor_name");
    $dg->AddColumn("Phone", "vendor_phone");
    $dg->AddColumn("Email", "vendor_email");
    $dg->AddColumn("Address", "vendor_address");
    $dg->SetNoDataMessage("No vendor.");
    $dg->AddIdColumn("vendorid");
    $dg->AddRightAction("Add PO", "../../images/add.png", "addpo.php?vid=%d");
    include("header.php");
    ?>
    <div class="panel">
        <div class="paneltitle" align="center">Add New Vendor</div>
        <div class="panelcontents">
            <form method="post" action="vendors.php" name="vendorform" id="vendorform" onsubmit="return ValidateForm();
                    return false;">
                      <?php echo($message); ?>
                <table width="100%">
                    <tr>
                        <td>Vendor Name <span style="color:red;">*</span></td><td><input id="vendorname" name = "vendorname" type="text"></td>
                    </tr>
                    <tr>
                        <td>Vendor Type </td><td><input id="vendortype" name = "vendortype" type="text"></td>
                    </tr>
                    <tr>
                        <td>Vendor Phone</td><td><input name = "vphone" id="vphone" type="text" onkeypress="return onlyNos(event, this);" maxlength="12"></td>
                    </tr>
                    <tr>
                        <td>Vendor Email</td><td><input name = "vemail" id="vemail" onblur="checkEmail()" type="text"></td>
                    </tr>
                    <tr>
                        <td>Vendor Address</td><td><textarea name="vaddress" id="vaddress"></textarea></td>
                    </tr>
                    <tr>
                        <td>Service Tax</td><td><input type="text" name="servicetax" id="servicetax"></td>
                    </tr>
                    <tr>
                        <td>PAN No </td><td><input type="text" name="panno" id="panno"></td>
                    </tr>
                    <tr>
                        <td>VAT</td><td><input type="text" name="vat" id="vat"></td>
                    </tr>
                    <tr>
                        <td>CST</td><td><input type="text" name="cst" id="cst"></td>
                    </tr>

                </table>
                <input type="submit" id="submitpros" name="submitpros" value="Save New Vendor"/>
            </form>
        </div>
    </div>
    <br/>
    <div class="panel">
        <div class="paneltitle" align="center">The Vendor List</div>
        <div class="panelcontents">
            <?php $dg->Render(); ?>
        </div>
    </div>
    <br/>
    <?php
    include("footer.php");
}
?>

<script>
    function ValidateForm() {
        var vendorname = $("#vendorname").val();
        var vphone = $("#vphone").val();
        var vemail = $("#vemail").val();
        var vaddress = $("#vaddress").val();
        if (vendorname == "") {
            alert("Please enter name");
            return false;
        } else if (vphone == "") {
            alert("Please enter contact number");
            return false;
        } else if (vemail == "") {
            alert("Please enter email id");
            return false;
        } else if (vaddress == "") {
            alert("Please enter vendor address.");
            return false;
        } else {
            $("#vendorform").submit();
        }
    }

    function checkEmail() {
        var email = $("#vemail").val();
        var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        if (pattern.test(email)) {
            return true;
        } else {
            alert("Enter valid email id");
            return false;
        }
    }



    function onlyNos(e, t) {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            }
            else if (e) {
                var charCode = e.which;
            }
            else {
                return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        catch (err) {
            alert(err.Description);
        }
    }


</script>
