<?php
    include_once "session.php";
    include_once "cashflow_functions.php";

    include "header.php";
    $objCategory = new stdClass();
    $objCategory->categoryid = 0;
    $arrResultCategory = getCategory($objCategory);
    $categoryList = json_decode(json_encode($arrResultCategory));

    if (isset($categoryList)) {
        $categoryString = '';
        foreach ($categoryList as $category) {
            $categoryString .= "<option value='" . $category->categoryid . "'>" . $category->category . "</option>";
        }
    }


?>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Bank Statement</div>
    <div class="panelcontents">
        <form method="post" action="cashflow_functions.php?action=getBankStatement" >
            <table>

                <tr>
                    <td>From Date </td>
                    <td> <input name="fromdate" id="fromdate" type="text" value=""/><button id="trigger">...</button>
                    </td>

                    <td>To Date </td>
                    <td> <input name="todate" id="todate" type="text" value=""/><button id="trigger2">...</button>
                    </td>
                    <td> Type </td>
                    <td>
                            <select name="transaction_type" id="transaction_type">
                                <option value="0">Select Transaction Type</option>
                                <option value="1">Debit</option>
                                <option value="2">Credit</option>
                            </select>
                        </td>
                    <td>Categorty <span style="color:red;">*</span></td>
                        <td>
                            <select name="category" id="category">
                                <option value="0">Select Category</option>
                                <?php echo $categoryString; ?>
                            </select>
                        </td>
                    <td>
                        <input type="submit" name="search" value="Search" />
                    </td>
                </tr>
            </table>

        </form>
    </div>
</div>
<br/>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Bank Statement</div>
    <div class="panelcontents">
        <?php //$dg->Render();?>
    </div>
</div>
<br/>
<?php
    include "footer.php";
?>

