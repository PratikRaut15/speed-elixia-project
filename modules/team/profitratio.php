<?php
    include_once "session.php";
    include "loginorelse.php";
    include_once "db.php";
    include_once "../../constants/constants.php";
    include_once "../../lib/system/Sanitise.php";
    include_once "../../lib/components/gui/objectdatagrid.php";
    include_once "../../lib/system/DatabaseManager.php";

    include "header.php";
    $db = new DatabaseManager();



    $data = Array();
    $arrResult = array();
    $statement = array();
    $todaysdate = date("Y-m-d");
    $SQL = sprintf("SELECT customercompany,customerno FROM customer WHERE renewal NOT IN (-1,-2)");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $data[] = $row;
        }
        foreach($data as $row) {
            $pdo = $db->CreatePDOConn();
            $sp_params = "'" . $row['customerno'] . "'"
            . ",'" . $todaysdate . "'";
            $queryCallSP = "CALL " . speedConstants::SP_GET_PROFIT_LOSS_ANALYSIS . "($sp_params)";
            $result = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $arrResult[] = (object)$result;
            $db->ClosePDOConn($pdo);
        }

    }

    //echo "<pre>";print_r($arrResult);echo "</pre>";

    //print_r($statement);
    $dg = new objectdatagrid($arrResult);
    $dg->AddColumn("Customer No", "varCustomerNo");
    $dg->AddColumn("Customer Company", "varCustomerCompany");
    $dg->AddColumn("Payment Collected", "totalPaymentCollected");
    $dg->AddColumn("Payment Due", "totalPaymentDue");
    $dg->AddColumn("Total Expense", "totalExpense");
    $dg->AddColumn("Profit Diff", "plDiff");
    $dg->AddColumn("Profit (In %)", "varCustomerProfit");
    $dg->SetNoDataMessage("Data Not Available.");
    $dg->AddIdColumn("varCustomerNo");



?>
<br/>

<div class="panel">
    <div class="paneltitle" align="center">Profit Ratio</div>
    <div class="panelcontents">
        <?php $dg->Render();?>
    </div>
</div>
<br/>

<?php
    include "footer.php";
?>
<script type="text/javascript">
function frmValidate() {
    var customername = jQuery("#customername").val();
    if(customername == '') {
        alert("Please Select Customer");
    } else {
        jQuery("#frmPLAnalysis").submit();
    }
}
function getCustomer() {
        jQuery("#customername").autocomplete({
            source: "route_ajax.php?customername=getcustomer",
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);
//                return false;
            }
        });
    }
</script>
