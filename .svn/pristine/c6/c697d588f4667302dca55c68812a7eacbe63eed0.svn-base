<?php
    include_once "session.php";
    include_once "cashflow_functions.php";

    include "header.php";
    $objCategory = new stdClass();
    $objCategory->categoryid = $_GET['cid'];
    $arrResult = getCategory($objCategory);
    $b= json_decode(json_encode($arrResult));

?>
<div class="panel">
    <div class="paneltitle" align="center">Add New Category</div>
        <div class="panelcontents">
            <form method="post" action="cashflow_functions.php?action=editCategory" name="frmCategory" id="frmCategory" onsubmit="return ValidateCategoryForm();return false;">
                <table width="100%">
                    <tr>
                        <td>Category Name <span style="color:red;">*</span></td>
                        <td><input id="category" name = "category" type="text" value="<?php echo $b[0]->category;?>"></td>
                    </tr>
                </table>
                <input type="hidden" name="categoryid" id='categoryid' value="<?php echo $b[0]->categoryid;?>">
                <input type="submit" id="submitpros" name="submitpros" value="Save Category"/>
            </form>
        </div>
    </div>
</div>
<br/>

<br/>
<?php
    include "footer.php";
?>

<script type="text/javascript">
    function ValidateCategoryForm() {
        var category = $("#category").val();

        if (category == "") {
            alert("Please enter category name");
            return false;
        }  else {
            $("#frmCategory").submit();
        }
    }
</script>
