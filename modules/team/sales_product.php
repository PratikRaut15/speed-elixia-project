<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");

// See if we need to save a new one.
if (IsHead() || IsSales()) {
    $teamid = $_SESSION['sessionteamid'];
    $today = date("Y-m-d H:i:s");
    $message = "";
    if (isset($_POST["productname"])) {
        $db = new DatabaseManager();
        $productname = GetSafeValueString($_POST["productname"], "string");
        $sql = sprintf("Select * from " . DB_PARENT . ".`sales_product` where product_name='%s'", $stagename);
        $db->executeQuery($sql);
        if ($db->get_affectedRows() > 0) {
            $message = "That product is already added please add another.";
        } else {
            $sql = sprintf("INSERT INTO " . DB_PARENT . ".`sales_product` (
    `product_name` ,
    `timestamp` ,
    `teamid_creator`
    )
    VALUES (
     '%s', '%s', '%d'
    );", $productname, $today, $teamid);
            $db->executeQuery($sql);
        }
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT sp.productid,sp.product_name,sp.timestamp,t.name FROM " . DB_PARENT . ".sales_product as sp inner join team as t ON sp.teamid_creator = t.teamid where sp.isdeleted=0");
    $db->executeQuery($SQL);
    $details = array();
    if ($db->get_rowCount() > 0){
        $x = 1;
        $delete_url = "";
        while ($row = $db->get_nextRow()) {
            $userdetails = new stdClass();
            $productid = $row["productid"];
            $userdetails->srno = $x;
            $userdetails->productid = $productid;
            $userdetails->product_name = $row["product_name"];
            $userdetails->name = $row["name"];
            $userdetails->timestamp = $row["timestamp"];    
            $userdetails->stageid = $row["stageid"];
            $teamid = $_SESSION['sessionteamid'];
            $delete_url = "<a href='javascript:void(0);' alt='Delete product ' title='Product' onclick='deleteproduct(" . $productid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/delete.png'/></a>";
            $userdetails->delete_url = $delete_url;
            $details[] = $userdetails;
            $x++;
        }
    }

    $dg = new objectdatagrid($details);
    $dg->AddAction("View/Edit", "../../images/edit.png", "modify_product.php?productid=%d");
    $dg->AddColumn("Product Name", "product_name");
    $dg->AddColumn("Created By", "name");
    $dg->AddColumn("Created Time", "timestamp");
    $dg->AddColumn("Delete", "delete_url");
    $dg->SetNoDataMessage("No Product Added");
    $dg->AddIdColumn("productid");
    include("header.php");
    ?>
    <div class="panel">
        <div class="paneltitle" align="center">Product Master</div>
        <div class="panelcontents">
            <form method="post" action="sales_product.php" name="productform" id="productform" onsubmit="return ValidateForm();
                        return false;">
                      <?php echo($message); ?>
                <table width="100%">
                    <tr>
                        <td>Product Name <span style="color:red;">*</span></td><td><input id="productname" name = "productname" type="text"></td>
                    </tr>
                </table>
                <input type="submit" id="submitproduct" name="submitproduct" value="Add New Product"/>
            </form>
        </div>
    </div>
    <br/>
    <div class="panel">
        <div class="paneltitle" align="center">Product List</div>
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
        var productname = $("#productname").val();
        if (productname == "") {
            alert("Please enter productname");
            return false;
        } else {
            $("#productform").submit();
        }
    }

    function checkEmail() {
        var email = $("#temail").val();
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

    function deleteproduct(productid) {
        jQuery.ajax({
            type: "POST",
            url: "user_ajax.php",
            cache: false,
            data: {
                productid: productid
                , action: 'deleteproduct'
            },
            success: function (res) {
                if (res == 'ok') {
                    window.location.reload();
                }
            }
        });
    }

</script>