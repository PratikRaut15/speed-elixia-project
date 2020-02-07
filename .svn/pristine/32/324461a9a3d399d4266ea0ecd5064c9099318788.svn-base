<?php
    include_once "session.php";
    include_once "cashflow_functions.php";

    include "header.php";
    $objCategory = new stdClass();
    $objCategory->categoryid = 0;
    $arrResultCategory = getCategory($objCategory);
    $categoryList = json_decode(json_encode($arrResultCategory));

    $objStatement = new stdClass();
    $objStatement->statementid = 0;
    $objStatement->transaction_datetime_from = '';
    $objStatement->transaction_datetime_to = '';
    $objStatement->transaction_type = 0;
    $objStatement->categoryid = 0;
    $arrResult = getBankStatement($objStatement);
    $statementList = json_decode(json_encode($arrResult));
    $statementList = array_slice($statementList, 0, 10); // returns "a", "b", and "c"
    foreach ($statementList as $thisdata) {
        if ($thisdata->transaction_type == 1) {
            $thisdata->transaction_entry = "DR";
        } else {
            $thisdata->transaction_entry = "CR";
        }

        if ($thisdata->enteredInTally == 0) {
            $thisdata->inTally = "<a href='javascript:void(0);' alt='Add To Tally ' title='Add To Tally' onclick='addToTally(1," . $thisdata->statementid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/add.png'/></a>";
        } else {
            $thisdata->inTally = "<a href='javascript:void(0);' alt='In Tally ' title='In Tally' ><img style='text-align:center; width:30px; height:30px;' src='../../images/adde.jpg'/></a>";
        }
    }

    $dg = new objectdatagrid($statementList);
    $dg->AddAction("View/Edit", "../../images/edit.png", "editbankstatement.php?statementid=%d");
    $dg->AddColumn("Transaction Time", "transaction_datetime");
    $dg->AddColumn("Details", "details");
    $dg->AddColumn("Remarks", "remarks");
    $dg->AddColumn("Type", "transaction_entry");
    $dg->AddColumn("Category", "category");
    $dg->AddColumn("Amount", "amount");
    $dg->AddColumn("In Tally", "inTally");
    $dg->AddColumn("Created On", "created_on");
    $dg->SetNoDataMessage("Data Not Available.");
    $dg->AddIdColumn("statementid");
    $dg->AddRightAction("Delete", "../../images/delete.png", "cashflow_functions.php?action=deleteBankStatement&statementid=%d");

    if (isset($categoryList)) {
        $categoryString = '';
        foreach ($categoryList as $category) {
            $categoryString .= "<option value='" . $category->categoryid . "'>" . $category->category . "</option>";
        }
    }

?>
<div class="panel">
    <div class="paneltitle" align="center">Add New Bank Statement</div>
        <div class="panelcontents">
            <form method="post" action="cashflow_functions.php?action=addBankStatement" name="frmBankStatement" id="frmBankStatement" onsubmit="return ValidateBankStatement();return false;">
                <table width="100%">
                    <tr>
                        <td>Transaction Datetime <span style="color:red;">*</span></td>
                        <td><input id="transaction_date" name = "transaction_date" type="text">
                        <input id="transaction_time" name = "transaction_time" type="text" class="input-mini"></td>
                    </tr>
                    <tr>
                        <td>Details <span style="color:red;">*</span></td>
                        <td>
                            <textarea name="details" id="details" cols="30" rows="2"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Remarks <span style="color:red;">*</span></td>
                        <td>
                            <textarea name="remarks" id="remarks" cols="30" rows="2"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Transaction Type <span style="color:red;">*</span></td>
                        <td>
                            <select name="transaction_type" id="transaction_type">
                                <option value="1">Debit</option>
                                <option value="2">Credit</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Category <span style="color:red;">*</span></td>
                        <td>
                            <select name="category" id="category">
                                <option value="0">Select Category</option>
                                <?php echo $categoryString; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Amount <span style="color:red;">*</span></td>
                        <td><input id="amount" name = "amount" type="text"></td>
                    </tr>
                </table>
                <input type="submit" id="submitpros" name="submitpros" value="Save Statement"/>
            </form>
        </div>
    </div>
</div>
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
        //$('#transaction_date').datepicker({format: "dd-mm-yyyy"});
        Calendar.setup(
            {
                inputField: "transaction_date", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger" // ID of the button
            });
        $('#transaction_time').timepicker({'timeFormat': 'H:i'});

    function ValidateBankStatement() {
        var transaction_date = $("#transaction_date").val();
        var transaction_time = $("#transaction_time").val();
        var details = $("#details").val();
        var remarks = $("#remarks").val();
        var transaction_type = $("#transaction_type").val();
        var category = $("#category").val();
        var amount = $("#amount").val();

        if (transaction_date == "") {
            alert("Please select transaction date");
            return false;
        }else if (transaction_time == "") {
            alert("Please select transaction time");
            return false;
        }else if (details == "") {
            alert("Please enter transaction details");
            return false;
        }else if (remarks == "") {
            alert("Please enter transaction remarks");
            return false;
        } else if (transaction_type == 0) {
            alert("Please select transaction type");
            return false;
        } else if (category == 0) {
            alert("Please select transaction category");
            return false;
        }else if (amount == "") {
            alert("Please enter transaction amount");
            return false;
        }else {
            $("#frmBankStatement").submit();
        }
    }

    function addToTally(setStatus, statementId) {

        jQuery.ajax({
        type: "POST",
        url: "cashflow_functions.php",
        cache: false,
        data: {
             statementid: statementId
            , action: 'addToTally'
        },
        success: function (res) {
            if(res){
                window.location.reload();
            }
        }
    });

    }
</script>
