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



    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getPLAnalysis" && isset($_POST['customerno'])) {
        $todaysdate = date("Y-m-d");
        $customername = $_POST['customername'];
        $customerno = $_POST['customerno'];
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $customerno . "'"
        . ",'" . $todaysdate . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_PROFIT_LOSS_ANALYSIS . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        if(isset($arrResult)) {
            //$arrResult['tolat']
        }

    }

?>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Profit And Loss Analysis</div>
    <div class="panelcontents">
        <form method="post" id='frmPLAnalysis' action="profitLossAnalysis.php?action=getPLAnalysis" >
            <table>
                <tr>
                    <td>Customer </td>
                    <td>
                        <input  type="text" name="customername" id="customername" size="20" value="<?php
                                                                                                       if (isset($customername)) {
                                                                                                           echo $customername;
                                                                                                   }
                                                                                                   ?>" autocomplete="off" placeholder="Enter Customer Name"  onkeypress="getCustomer();"/>
                        <input type="hidden" id="customerno" name="customerno" value="<?php
                                                                                          if (isset($customerno)) {
                                                                                              echo $customerno;
                                                                                      }
                                                                                      ?>"/>
                    </td>
                    <td>
                        <input type="button" name="search" value="Search" onclick='frmValidate();' />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<br/>
<br/>

<div class="panel">
    <div class="paneltitle" align="center">Statement</div>
    <div class="panelcontents">
        <?php
        if(isset($arrResult) && isset($customerno)) {
        ?>

            <table style="border:1px solid #ccc; width: 80%" cellspacing="5px;" cellpadding="5px;">
                <tr>
                    <td style="width: 25%;">Total Mapped Units</td>
                    <td style="width: 25%;"><?php echo $arrResult['totalUnitCount'];?></td>

                    <td style="width: 25%;">Total Mapped Simcard</td>
                    <td style="width: 25%;"><?php echo $arrResult['totalSimcardCount'];?></td>
                </tr>
                <tr>
                    <td style="width: 25%;">Total Payment Due</td>
                    <td style="width: 25%;"><?php echo $arrResult['totalPaymentDue'];?></td>

                    <td style="width: 25%;"></td>
                    <td style="width: 25%;"></td>
                </tr>
                <tr><td colspan="4"><hr/></td></tr>
                <tr>
                    <td colspan="2" style="text-align: center;">Expense</td>
                    <td  colspan="2" style="text-align: center;">Income</td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%;"></td>
                                <td style="width: 50%;">(Amount In Rs.)</td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Indirect Expense 2013-2014</td>
                                <td style="width: 50%;"><?php echo $arrResult['varTotalIndirectExp_13_14'];?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Indirect Expense 2014-2015</td>
                                <td style="width: 50%;"><?php echo $arrResult['varTotalIndirectExp_14_15'];?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Indirect Expense 2015-2016</td>
                                <td style="width: 50%;"><?php echo $arrResult['varTotalIndirectExp_15_16'];?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Indirect Expense 2016-2017</td>
                                <td style="width: 50%;"><?php echo $arrResult['varTotalIndirectExp_16_17'];?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Device Cost</td>
                                <td style="width: 50%;"><?php echo $arrResult['totalUnitCost'];?></td>
                            </tr>
                            <tr>
                                <td>Simcard Cost</td>
                                <td><?php echo $arrResult['totalSimcardCost'];?></td>
                            </tr>


                            <tr>
                                <td>Material Cost</td>
                                <td><?php echo $arrResult['totalMaterialCost'];?></td>
                            </tr>


                            <tr>
                                <td>Indirect Expense</td>
                                <td><?php echo $arrResult['totalOtherIndirectExp'];?></td>
                            </tr>
                            <tr>
                                <td>Total Expense </td>
                                <td><?php echo $arrResult['totalExpense'];?></td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%;"></td>
                                <td style="width: 50%;">(Amount In Rs.)</td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Total Payment Collected</td>
                                <td style="width: 50%;"><?php echo $arrResult['totalPaymentCollected'];?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td colspan="4"><hr/></td></tr>
                <tr>
                    <td>Net Profit</td>
                    <td><?php echo $arrResult['plDiff'];?></td>
                </tr>
                <tr>
                    <td>Net Profit In %</td>
                    <td><?php echo $arrResult['varCustomerProfit'];?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><?php echo $arrResult['plStatus'];?></td>
                </tr>
            </table>


            <!--

            <table style="border:1px solid #ccc; width: 80%" cellspacing="5px;" cellpadding="5px;">
                <tr>
                    <td style="width: 70%;">Total Mapped Units</td>
                    <td style="width: 30%;"><?php echo $arrResult['totalUnitCount'];?></td>
                </tr>
                <tr>
                    <td>Unit Cost</td>
                    <td><?php echo $arrResult['totalUnitCost'];?></td>
                </tr>
                <tr>
                    <td>Total Mapped Simcard</td>
                    <td><?php echo $arrResult['totalSimcardCount'];?></td>
                </tr>
                <tr>
                    <td>Simcard Cost</td>
                    <td><?php echo $arrResult['totalSimcardCost'];?></td>
                </tr>
                <tr>
                    <td>Total Expense </td>
                    <td><?php echo $arrResult['totalExpense'];?></td>
                </tr>
                <tr>
                    <td>Total Payment Collected</td>
                    <td><?php echo $arrResult['totalPaymentCollected'];?></td>
                </tr>
                <tr>
                    <td>Payment Difference</td>
                    <td><?php echo $arrResult['plDiff'];?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><?php echo $arrResult['plStatus'];?></td>
                </tr>
            </table>
            -->
        <?php
        } elseif (isset($customerno)) {
            echo "Data Not Available";
        } else {
            echo "Please Select Customer";
        }
        ?>

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
