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
    $dg = new objectdatagrid($statementList);
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getBankStatement") {
        $objStatement = new stdClass();
        $objStatement->statementid = 0;
        $objStatement->transaction_datetime_from = ($_POST['fromdate'] != '') ? date(speedConstants::DATE_Ymd, strtotime($_POST['fromdate'])) : '';
        $objStatement->transaction_datetime_to = ($_POST['todate'] != '') ? date(speedConstants::DATE_Ymd, strtotime($_POST['todate'])) : '';
        $objStatement->transaction_type = isset($_POST['transaction_type']) ? $_POST['transaction_type'] : 0;
        $objStatement->categoryid = isset($_POST['category']) ? $_POST['category'] : 0;
        $objStatement->teamid = GetLoggedInUserId();
        //print_r($objStatement);
        $arrFilterBankStatementResult = getBankStatement($objStatement);
        $statementList = json_decode(json_encode($arrFilterBankStatementResult));
        foreach ($statementList as $thisdata) {
            if ($thisdata->transaction_type == 1) {
                $thisdata->transaction_entry = "DR";
            } else {
                $thisdata->transaction_entry = "CR";
            }
        }
        $dg = new objectdatagrid($statementList);
        //$dg->AddAction("View/Edit", "../../images/edit.png", "editbankstatement.php?statementid=%d");
        //$dg->AddColumn("Statement Id", "statementid");
        $dg->AddColumn("Transaction Time", "transaction_datetime");
        $dg->AddColumn("Details", "details");
        $dg->AddColumn("Remarks", "remarks");
        $dg->AddColumn("Type", "transaction_entry");
        $dg->AddColumn("Category", "category");
        $dg->AddColumn("Amount", "amount");
        $dg->AddColumn("Created On", "created_on");
        $dg->SetNoDataMessage("Data Not Available.");
        $dg->AddIdColumn("statementid");
        //$dg->AddRightAction("Delete", "../../images/delete.png", "cashflow_functions.php?action=deleteBankStatement&statementid=%d");

    } else {
        $dg->SetNoDataMessage("");
    }

?>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Bank Statement</div>
    <div class="panelcontents">
        <form method="post" action="bankStatementGenericReport.php?action=getBankStatement" >
            <table>

                <tr>
                    <td>From Date </td>
                    <td> <input name="fromdate" id="fromdate" type="text" value=""/
                    </td>

                    <td>To Date </td>
                    <td> <input name="todate" id="todate" type="text" value=""/>
                    </td>
                    <td> Type </td>
                    <td>
                            <select name="transaction_type" id="transaction_type">
                                <option value="0">Select Transaction Type</option>
                                <option value="1">Debit</option>
                                <option value="2">Credit</option>
                            </select>
                        </td>
                    <td>Category <span style="color:red;">*</span></td>
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
        <?php $dg->Render();?>
    </div>
</div>
<br/>

<?php
    include "footer.php";
?>
<script type="text/javascript">
    $('#fromdate').datepicker({
        format: "dd-mm-yyyy",
        language: 'en',
        autoclose: 1,
        startDate: Date()
    });
    $('#todate').datepicker({
        format: "dd-mm-yyyy",
        language: 'en',
        autoclose: 1,
        startDate: Date()
    });
</script>
