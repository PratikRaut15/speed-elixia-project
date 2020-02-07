<?php
/**
 * Edit Product master form
 */

require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);

$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:salesengage.php?pg=view-product');
}
$getproductdata = $sales->getproductdata_byid($id);
?>
<br/>
<div class='container'>
    <center>
    <form name="editproductmasterform" id="editproductmasterform" method="POST"  onsubmit="editproductdata();return false;">
    <table class='table table-condensed'>
        <thead>
            <tr>
                <td colspan="100%" class="tdnone">
                    <div>
                        <a href="salesengage.php?pg=view-product" class="backtextstyle">Back To Product View</a>
                    </div>
                </td>
            </tr>
            <tr><th colspan="100%" >Update Product </th></tr>
        </thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Product Name <span class="mandatory">*</span></td><td><input type="text" name="pname" value='<?php echo $getproductdata[0]['productname']; ?>' required></td></tr>
            <tr><td class='frmlblTd'>Unit Price </td><td><input type='number' name='unitprice' id='unitprice' value='<?php echo $getproductdata[0]['unitprice']; ?>'></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="productsubmit" value="Update" class='btn btn-primary'>
<!--            <a href="salesengage.php?pg=view-product">View Product</a>-->
                </td></tr>
        </tbody>
        <input type='hidden' name='prid' id='prid' value='<?php echo $getproductdata[0]['id'];?>'>
    </table>
    </form>
    </center>
</div>
