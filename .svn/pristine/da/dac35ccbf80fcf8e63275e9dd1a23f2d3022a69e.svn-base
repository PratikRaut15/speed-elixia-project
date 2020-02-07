<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");

$vid = $_REQUEST['vid'];
if (isset($vid) && !empty($vid)) {
    $db = new DatabaseManager();
    $SQL1 = sprintf("select vendorid,vendor_name,vendor_type,vendor_phone,vendor_email,vendor_address,servicetax_no,panno,cstno,vatno from team_vendor where isdeleted=0 AND vendorid=" . $vid);
    $db->executeQuery($SQL1);
    if ($db->get_rowCount() > 0) {
        $row = $db->get_nextRow();
        $vendor_name = $row["vendor_name"];
        $vendor_type = $row["vendor_type"];
        $vendor_phone = $row["vendor_phone"];
        $vendor_email = $row["vendor_email"];
        $vendor_address = $row["vendor_address"];
        $servicetax_no = $row["servicetax_no"];
        $panno = $row["panno"];
        $cstno = $row["cstno"];
        $vatno = $row["vatno"];
    }

// See if we need to save a new one.
    if (IsAdmin() || IsHead()) {
        if (isset($_POST['submitpros'])){

            $vendor_name1 = $_POST['vendorname'];
            $vendor_type1 = $_POST['vendortype'];
            $vendor_phone1 = $_POST['vphone'];
            $vendor_email1 = $_POST['vemail'];
            $vendor_address1 = $_POST['vaddress'];
            $servicetax_no1 = $_POST['servicetax'];
            $panno1 = $_POST['panno'];
            $cstno1 = $_POST["cst"];
            $vatno1 = $_POST["vat"];
            $db = new DatabaseManager();
            $SQLUpdate = sprintf("update team_vendor set vendor_name='" . $vendor_name1 . "',vendor_type='" . $vendor_type1 . "',vendor_phone='" . $vendor_phone1 . "',vendor_email='" . $vendor_email1 . "' ,vendor_address='" . $vendor_address1 . "',servicetax_no = '" . $servicetax_no1 . "',panno = '" . $panno1 . "',cstno = '" . $cstno1 . "',vatno='" . $vatno1 . "'  where vendorid=" . $vid);
            $db->executeQuery($SQLUpdate);
        }


        if (isset($_POST["submitdel"])) {
            $vid = $_POST['vid'];

            $SQLDelete = sprintf("update team_vendor set isdeleted=1  where vendorid=" . $vid);
            $db->executeQuery($SQLDelete);

            $SQLDelete1 = sprintf("update vendor_po set isdeleted=1  where vendorid=" . $vid);
            $db->executeQuery($SQLDelete1);
            
            header("Location: vendors.php");
            exit;
        }



        include("header.php");
        ?>
        <div class="panel">
            <div class="paneltitle" align="center">Edit Vendor Details</div>
            <div class="panelcontents">
                <form method="post" action="editvendor.php?vid=<?php echo $vid; ?>" name="vendorformedit" id="vendorformedit" onsubmit="return ValidateForm();
                                return false;">
                      <?php echo($message); ?>
                    <table width="100%">
                        <tr>
                            <td>Vendor Name <span style="color:red;">*</span></td>
                            <td><input id="vendorname" name = "vendorname" type="text" value="<?php echo $vendor_name; ?>"></td>
                        </tr>
                        <tr>
                            <td>Vendor Type </td>
                            <td><input id="vendortype" name = "vendortype" type="text" value="<?php echo $vendor_type; ?>"></td>
                        </tr>
                        <tr>
                            <td>Vendor Phone</td>
                            <td><input name = "vphone" id="vphone" type="text" onkeypress="return onlyNos(event, this);" maxlength="12" value="<?php echo $vendor_phone; ?>" ></td>
                        </tr>
                        <tr>
                            <td>Vendor Email</td><td><input name = "vemail" id="vemail" onblur="checkEmail()" type="text" value="<?php echo $vendor_email; ?>"  ></td>
                        </tr>
                        <tr>
                            <td>Vendor Address</td><td><textarea name="vaddress" id="vaddress"><?php echo $vendor_address; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Service Tax</td><td><input type="text" name="servicetax" id="servicetax" value="<?php echo $servicetax_no; ?>"></td>
                        </tr>
                        <tr>
                            <td>PAN No </td><td><input type="text" name="panno" id="panno" value="<?php echo $panno; ?>"></td>
                        </tr>
                        <tr>
                            <td>VAT</td><td><input type="text" name="vat" id="vat" value="<?php echo $vatno; ?>"></td>
                        </tr>
                        <tr>
                            <td>CST</td><td><input type="text" name="cst" id="cst" value="<?php echo $cstno; ?>"></td>
                        </tr>
                    </table>
                    <input type="hidden" name="vid" id="vid" value="<?php echo $vid; ?>">
                    <input type="submit" id="submitpros" name="submitpros" value="Edit Vendor" style="background-color: green; color:white;"/>
        <!--                    <input type="submit" id="submitdel" name="submitdel" onclick="deletevendorf(<?php echo $vid; ?>);" value="Delete Vendor" style="background-color: red; color:white;" />-->
                    <input type="submit" id="submitdel" name="submitdel" value="Delete Vendor" style="background-color: red; color:white;"/>
                </form>
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
                $("#vendorformedit").submit();
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

        function deletevendorf(id) {
            alert("id" + id);
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

<?php } ?>