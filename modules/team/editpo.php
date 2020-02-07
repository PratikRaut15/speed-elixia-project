<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");

$poid = $_REQUEST['poid'];


if (isset($poid) && !empty($poid)) {

    $db = new DatabaseManager();
    $SQL1 = sprintf("select vp.poid,tv.vendor_name,tv.vendor_email,vp.vendorid,vp.podate,vp.totalamount from vendor_po as vp left join team_vendor as tv on tv.vendorid = vp.vendorid  where vp.isdeleted=0 AND vp.poid=" . $poid);
    $db->executeQuery($SQL1);
    if ($db->get_rowCount() > 0) {
        $row = $db->get_nextRow();
        $poid = $row["poid"];
        $vendor_name = $row["vendor_name"];
        $vendor_email = $row["vendor_email"];
        $podate = date('d-m-Y', strtotime($row["podate"]));
        $vendorid = $row["vendorid"];
        $totalamount = $row["totalamount"];
    }


//     $db = new DatabaseManager();
//    $SQL1 = sprintf("select * from vendor_item where isdeleted=0 AND poid=" . $poid);
//    $db->executeQuery($SQL1);
//    if ($db->get_rowCount() > 0) {
//        $row = $db->get_nextRow();
//        $poid = $row["poid"];
//        $podate = date('d-m-Y', strtotime($row["podate"]));
//        $vendorid = $row["vendorid"];
//        $item = $row["item"];
//        $unitprice = $row["unitprice"];
//        $quantity = $row["quantity"];
//        $amount = $row["amount"];
//        $tax = $row["tax"];
//    }
// See if we need to save a new one.
    if (IsAdmin() || IsHead()) {
        $message = "";

        if (isset($_POST["submitpros"])) {
            $db = new DatabaseManager();
            $podate = GetSafeValueString($_POST["podate"], "string");
            $totalamount = GetSafeValueString($_POST["totalamount"], "string");
            $podate1 = date('Y-m-d', strtotime($podate));
            if ($podate == "") {
                $message = "Please select Date";
            } else {
                $sql = sprintf("update " . DB_PARENT . ".`vendor_po` set podate='" . $podate1 . "' AND totalamount ='" . $totalamount . "' where poid=" . $poid);
                $db->executeQuery($sql);

                $sql = sprintf("update " . DB_PARENT . ".`vendor_item` set isdeleted=1 where poid=" . $poid);
                $db->executeQuery($sql);

                $count = count($_POST['item']);
                $db = new DatabaseManager();
                $trmessage = '';
                for ($i = 0; $i < $count; $i++) {
                    if ($_POST['item'][$i] != '' && $_POST['unitprice'][$i] != "" && $_POST['quantity'][$i] != "" && $_POST['amount'][$i] != "") {
                        $sql1 = sprintf("INSERT INTO " . DB_PARENT . ".`vendor_item`(
                `poid`,
                `vendorid`,
                `item`,
                `unitprice`,
                `quantity`,
                `amount`,
                `tax`
                )
                VALUES ('%d','%d','%s','%s','%s','%s','%s');", $poid, $vid, $_POST['item'][$i], $_POST['unitprice'][$i], $_POST['quantity'][$i], $_POST['amount'][$i], $_POST['tax'][$i]);
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
                    $vemail =$vendor_email;
                    $subject = "P0" . $lastid . "Order Added Successfully.";
                    $to = array($vemail);
                    $strBCCMailIds = "sanketsheth@elixiatech.com";
                    $strBCCMailIds = '';
                    $attachmentFilePath = "";
                    $attachmentFileName = "";
                    $strCCMailIds = 'accounts@elixiatech.com';
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



        if (isset($_POST["deletepo"])) {
            $poid = $_POST["poidh"];
            $vendorid = $_POST["vendorid"];
            $db = new DatabaseManager();
            $SQLUpdate1 = sprintf("update vendor_po set isdeleted=1 where poid=" . $poid);
            $db->executeQuery($SQLUpdate1);
            $SQLUpdate1 = sprintf("update vendor_item set isdeleted=1 where poid=" . $poid);
            $db->executeQuery($SQLUpdate1);
            header("Location: vendors.php?vid=" . $vendorid);
            exit;
        }



        include("header.php");
        ?>
        <div class="panel">
            <div class="paneltitle" align="center">Edit Purchase Order Details</div>
            <div class="panelcontents">
                <form method="post" action="editpo.php?poid=<?php echo $poid; ?>" name="editpoform" id="editpoform" onsubmit="return ValidateForm();
                        return false;">
                          <?php echo($message); ?>
                    <table width="100%">
                        <tr>
                            <td>Po Date <span style="color:red;">*</span></td><td><input id="podate" name = "podate" value="<?php echo $podate; ?>" type="text"></td>
                        </tr>
                        <tr>
                            <td>Total Amount</td><td><input name = "totalamount" id="totalamount" maxlength="12" type="text" value="<?php echo $totalamount; ?>"></td>
                        </tr>
                    </table>


                    <div style="width:100%;">
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
                    <div align="center">
                        <?php
                        $data = array();
                        $db = new DatabaseManager();
                        $SQL1 = sprintf("select vendorid,poid,item,unitprice,quantity,amount,tax from vendor_item where isdeleted=0 AND poid=" . $poid);
                        $db->executeQuery($SQL1);
                        if ($db->get_rowCount() > 0) {
                            ?>   
                            <table width="35%" border="1">
                                <tr><td colspan="6" style="text-align: center;"><h4> Order History List </h4></td></tr>
                                <tr>

                                    <td>Poid</td>
                                    <td>Item</td>
                                    <td>Unit Price</td>
                                    <td>Quantity</td>
                                    <td>Amount</td>
                                    <td>tax</td>
                                </tr>
                                <?php
                                while ($row = $db->get_nextRow()) {
                                    $item = $row["item"];
                                    $poid = $row["poid"];
                                    $unitprice = $row["unitprice"];
                                    $quantity = $row["quantity"];
                                    $amount = $row["amount"];
                                    $tax = $row["tax"];

                                    echo"<tr>";
                                    echo "<td>" . $poid . "</td>";
                                    echo "<td>" . $item . "</td>";
                                    echo"<td>" . $unitprice . "</td>";
                                    echo"<td>" . $quantity . "</td>";
                                    echo "<td>" . $amount . "</td>";
                                    echo"<td>" . $tax . "</td>";
                                    echo "</tr>";
                                }
                                echo "</table>";
                            }
                            ?>

                    </div>



                    <input type="hidden" name="poidh" id="poidh" value="<?php echo $poid; ?>">
                    <input type="hidden" name="vendorid" id="vendorid" value="<?php echo $vendorid; ?>">

                    <input type="submit" id="submitpros" name="submitpros" value="Edit Order " style="background-color: green; color: white;"/>
                    <input type="submit" id="deletepo" name="deletepo" value="Delete Order " style="background-color: red; color: white;"/>
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
                $("#editpoform").submit();
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



        $("#podate").datepicker({dateFormat: "dd-mm-yy", language: 'en', autoclose: 1});

    </script>

<?php } ?>