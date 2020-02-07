<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");

$vid = $_REQUEST['vid'];


if (isset($vid) && !empty($vid)) {
// See if we need to save a new one.
    if (IsAdmin() || IsHead()) {

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

        $message = "";
        if (isset($_POST["submitpros"])) {
            $db = new DatabaseManager();
            $podate = GetSafeValueString($_POST["podate"], "string");
            $totalamount = GetSafeValueString($_POST["totalamount"], "string");
            $podate1 = date('Y-m-d', strtotime($podate));
            if ($podate == "") {
                $message = "Please select Date";
            } else {
                $sql = sprintf("INSERT INTO " . DB_PARENT . ".`vendor_po`(
                `vendorid`,
                `podate`,
                `totalamount`
                )VALUES(%d,'%s','%s');", $vid, $podate1, $totalamount);
                $db->executeQuery($sql);
                $lastid = $db->get_insertedId();
                $count = count($_POST['item']);
                $db = new DatabaseManager();
                $trmessage = "";
                for ($i = 0; $i < $count; $i++) {
                    if ($_POST['item'][$i] != '' && $_POST['unitprice'][$i] != "" && $_POST['quantity'][$i] != "" && $_POST['amount'][$i] != "") {
                        $sql1 = sprintf("INSERT INTO " . DB_PARENT . ".`vendor_item` (
                `poid`,
                `vendorid` ,
                `item`,
                `unitprice`,
                `quantity`,
                `amount`,
                `tax`
                )
                VALUES (
                '%d','%d','%s','%s','%s','%s','%s');", $lastid, $vid, $_POST['item'][$i], $_POST['unitprice'][$i], $_POST['quantity'][$i], $_POST['amount'][$i], $_POST['tax'][$i]);
                        $db->executeQuery($sql1);
                        $item = $_POST['item'][$i];
                        $unitprice = $_POST['unitprice'][$i];
                        $quantity = $_POST['quantity'][$i];
                        $amount = $_POST['amount'][$i];
                        $tax = $_POST['tax'][$i];
                        $trmessage .= "<tr><td>" . $item . "</td><td>" . $unitprice . "</td><td>" . $quantity . "</td><td>" . $amount . "</td><td>" . $tax . "</td></tr>";
                    }
                }


                if ($vendor_email != "") {
                    //$vemail = 'ganeshp@elixiatech.com';
                    $vemail = $vendor_email;
                    $subject = "P0" . $lastid . "Order Added Successfully.";
                    $to = array($vemail);
                    $strBCCMailIds = "sanketsheth@elixiatech.com";
                    $strBCCMailIds = '';
                    $attachmentFilePath = "";
                    $attachmentFileName = "";
                    //$strCCMailIds = 'accounts@elixiatech.com';
                    $strCCMailIds = '';
                    $custommsg = "<h3>" . $subject . "</h3><table>"
                            . " <tr> "
                            . " <td>PO Date </td><td>" . $podate . "</td>"
                            . " <td>Total Amount</td><td colspan=2>" . $totalamount . "</td></tr> " . $trmessage . "</table>";
                    $message = $custommsg;
                    $isEmailSent = sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
                    if ($isEmailSent == 1) {
                        $message = "Email send";
                    } else {
                        $message = "Email not send";
                    }
                }
            }
        }


        $db = new DatabaseManager();
        $SQL = "SELECT CONCAT('PO',vp.poid) as porid,vp.poid,tv.vendor_name,DATE_FORMAT(vp.podate,'%d/%m/%Y') AS orderdate,vp.item,vp.unitprice,vp.quantity,vp.amount,vp.tax,vp.totalamount  FROM " . DB_PARENT . ".vendor_po as vp  left join team_vendor as tv on tv.vendorid = vp.vendorid where vp.isdeleted=0 AND vp.vendorid=" . $vid;
        $db->executeQuery($SQL);

        $dg = new datagrid($db->getQueryResult());
        $dg->AddColumn("Poid", "porid");
        $dg->AddColumn("Vendor Name", "vendor_name");
        $dg->AddColumn("PO Date", "orderdate");
        $dg->AddColumn("Total Amount", "totalamount");
        $dg->SetNoDataMessage("No po orders.");
        $dg->AddIdColumn("poid");
        $dg->AddRightAction("Delete/Edit", "../../images/edit.png", "editpo.php?poid=%d");
        include("header.php");
        ?>
        <div class="panel">
            <div class="paneltitle" align="center">Add Purchase Order</div>
            <form method="post" action="addpo.php?vid=<?php echo $vid; ?>" name="addpoform" id="addpoform" onsubmit="return ValidateForm();
                    return false;">
                <div class="panelcontents">
                    <?php echo($message); ?>
                    <table width="100%">
                        <tr>
                            <td>Po Date <span style="color:red;">*</span></td><td><input id="podate" name = "podate" type="text"></td>
                        </tr>
                        <tr>
                            <td>Total Amount</td><td><input name = "totalamount" id="totalamount" maxlength="12" type="text"></td>
                        </tr>
                    </table>    
                    <div style="width:100%;">
                    <!--<div style="float:right;"><input type="button" onclick="addrow()" value="Add Row"></div><br/>-->
                        <table width="35%" border="1" id="myTable">
                            <tr><th>Item<span style="color:red;">*</span></th><th>Unit Price</th><th>Quantity</th><th>Amount</th><th>Tax</th><th><img alt="Add Row" src="../../images/show.png" onclick="addrow()" ></th></tr>
                            <?php
                            for ($i = 0; $i < 1; $i++) {
                                ?>
                                <tr>
                                    <td><input type='text' name='item[]' id='item' maxlength="50" /></td>
                                    <td><input type='text' name='unitprice[]' id='unitprice' maxlength="50" /></td>
                                    <td><input type='text' name='quantity[]' id='quantity' maxlength="50" /></td>
                                    <td><input type='text' name='amount[]' id='amount' maxlength="50" /></td>
                                    <td><input type='text' name='tax[]' id='tax' maxlength="50" /></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    <br/><br/>
                    <div style="text-align: left;">
                        <input type="submit" id="submitpros" name="submitpros" value="Add Order" style="background-color: green; color: white;"/>
                    </div>
            </form>
        </div>
        </div>
        <br/>
        <div class="panel">
            <div class="paneltitle" align="center">Orders</div>
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

        function addrow() {
            var table = document.getElementById("myTable");
            var row = table.insertRow(1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);

            cell1.innerHTML = "<input type='text' name='item[]' id='item' maxlength='50' />";
            cell2.innerHTML = "<input type='text' name='unitprice[]' id='unitprice' maxlength='50' />";
            cell3.innerHTML = "<input type='text' name='quantity[]' id='quantity' maxlength='50'/>";
            cell4.innerHTML = "<input type='text' name='amount[]' id='amount' maxlength='50' />";
            cell5.innerHTML = "<input type='text' name='tax[]' id='tax' maxlength='50' />";

        }


        function ValidateForm() {
            var podate = $("#podate").val();
            var item = $("#item").val();
            var unitprice = $("#unitprice").val();
            var quantity = $("#quantity").val();
            var amount = $("#amount").val();
            var tax = $("#tax").val();
            var totalamount = $("#totalamount").val();

            if (podate == "") {
                alert("Please select date");
                return false;
            } else if (item == "") {
                alert("Please enter item");
                return false;
            } else {
                $("#addpoform").submit();
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
        $("#podate").datepicker({dateFormat: "dd-mm-yy", language: 'en', autoclose: 1});

    </script>

<?php } ?>