<?php
    include_once "session.php";
    include_once "cashflow_functions.php";

    include "header.php";
    $objCategory = new stdClass();
    $objCategory->categoryid = 0;
    $arrResult = getCategory($objCategory);
    $b= json_decode(json_encode($arrResult));
    $dg = new objectdatagrid($b);
    $dg->AddAction("View/Edit", "../../images/edit.png", "editcategory.php?cid=%d");
    $dg->AddColumn("Category Id", "categoryid");
    $dg->AddColumn("Category", "category");
    $dg->AddColumn("Created On", "created_on");
    $dg->SetNoDataMessage("Category Not Available.");
    $dg->AddIdColumn("categoryid");
    $dg->AddRightAction("Delete", "../../images/delete.png", "cashflow_functions.php?action=deleteCategory&cid=%d");

?>
<div class="panel">
    <div class="paneltitle" align="center">Add New Category</div>
        <div class="panelcontents">
            <form method="post" action="cashflow_functions.php?action=addCategory" name="frmCategory" id="frmCategory" onsubmit="return ValidateCategoryForm();return false;">
                <table width="100%">
                    <tr>
                        <td>Category Name <span style="color:red;">*</span></td>
                        <td><input id="category" name = "category" type="text"></td>
                    </tr>
                </table>
                <input type="submit" id="submitpros" name="submitpros" value="Save Category"/>
            </form>
        </div>
    </div>
</div>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Category List</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>
</div>
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
