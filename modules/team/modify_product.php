<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");

// See if we need to save a new one.
if (IsHead() || IsSales()) {
    $db = new DatabaseManager();
    $teamid = $_SESSION['sessionteamid'];
    $today = date("Y-m-d H:i:s");
    $productid = $_GET['productid'];
    $message = "";
    if($productid==""){
      header("location:sales_product.php");  
    }
        $sql1 = sprintf("Select * from " . DB_PARENT . ".`sales_product` where isdeleted=0 AND  productid=%d", $productid);
        $db->executeQuery($sql1);
        if ($db->get_affectedRows() > 0) {
            while ($row = $db->get_nextRow()) {
                $productidval = $row["productid"];
                $productnameval = $row["product_name"];
                $timestamp = $row['timestamp'];
            }
        }
        
        if (isset($_POST["productname"]) && $_POST["productname"]!=""){
            $db = new DatabaseManager();
            $productname1= GetSafeValueString($_POST["productname"], "string");
            $productid1 = GetSafeValueString($_POST["productid"], "string");
            $sql = "update " . DB_PARENT . ".`sales_product` set `product_name`='".$productname1."' where productid=".$productid1;
            $db->executeQuery($sql);
            header("location:sales_product.php");
        }
        include("header.php");
        ?>
        <div class="panel">
            <div class="paneltitle" align="center">Product Update</div>
            <div class="panelcontents">
                <form method="post" action="modify_product.php?productid=<?php echo $productid;?>" name="modifyproductform" id="modifyproductform" onsubmit="return ValidateForm();
                        return false;">
        <?php echo($message); ?>
                    <table width="100%">
                        <tr>
                            <td>Product Name <span style="color:red;">*</span></td><td><input id="productname" name = "productname" value="<?php echo $productnameval;?>" type="text"></td>
                        </tr>
                    </table>
                    <input type="hidden" id="productid" name="productid" value="<?php echo $productidval; ?>">
                    <input type="submit" name="updateproduct" id="updateproduct" value="Update Product"/>
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
        var sourcename = $("#sourcename").val();
        if (sourcename == "") {
            alert("Please enter sourcename");
            return false;
        } else {
            $("#modifysourceform").submit();
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


</script>