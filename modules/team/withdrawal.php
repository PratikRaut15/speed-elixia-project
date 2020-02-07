<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

//include_once("../../lib/system/Date.php");

$db = new DatabaseManager();

$SQL = sprintf("SELECT  teamid
                        ,name
                FROM    team");
$db->executeQuery($SQL);
$teams = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $team['teamid'] = $row['teamid'];
        $team['name'] = $row['name'];
        $teams[] = $team;
    }
}

$SQL = sprintf("SELECT  id
                        ,name
                FROM    bank");
$db->executeQuery($SQL);
$banks = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $bank['id'] = $row['id'];
        $bank['name'] = $row['name'];
        $banks[] = $bank;
    }
}

if (isset($_POST['submitwithdrawal'])) {
    $vendorid = GetSafeValueString($_POST['vid'], "string");
    $type = GetSafeValueString($_POST['withdr_type'], "string");
    $amount = GetSafeValueString($_POST['withdr_amt'], "string");
    $responsible = GetSafeValueString($_POST['withdr_responsible'], "string");
    $chequeno = GetSafeValueString($_POST['withdr_chequeno'], "string");
    $bank = GetSafeValueString($_POST['withdr_bank'], "string");
    $bankid = GetSafeValueString($_POST['rec_bank'], "string");
    $status = 3;
    $todaysdate = date('Y-m-d H:i:s');

    $pdo = $db->CreatePDOConn();

    $sp_params = "'" . $todaysdate . "'"
            . ",'" . $vendorid . "'"
            . ",'" . GetLoggedInUserId() . "'"
            . ",'" . $chequeno . "'"
            . ",'" . $bank . "'"
            . ",'" . $amount . "'"
            . ",'" . $type . "'"
            . ",'" . $status . "'"
            . ",'" . $responsible . "'"
            . ",'" . $bankid . "'"
            . ",@is_executed";

    $queryCallSP = "CALL " . speedConstants::SP_INSERT_BANK_WITHDRAWAL . "($sp_params)";

    $arrResult = $pdo->query($queryCallSP);

    $outputParamsQuery = "SELECT    @is_executed AS is_executed";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
    $db->ClosePDOConn($pdo);

    if ($outputResult['is_executed'] == 1) {
        header("location:withdrawal.php?msg=success");
    } else {
        header("location:withdrawal.php?msg=fail");
    }
}

function getvendor_detail() {
    $db = new DatabaseManager();
    $vendors = Array();
    $SQL = sprintf("SELECT id,vendorname FROM " . DB_PARENT . ".vendor");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row2 = $db->get_nextRow()) {
            $vendor = new stdClass();
            $vendor->id = $row2['id'];
            $vendor->vendorname = $row2['vendorname'];
            $vendors[] = $vendor;
        }
        return $vendors;
    }
    return false;
}

//QUERY for display
$Display = array();
$SQL = "SELECT  brs.id
                ,brs.vendorid
                ,v.vendorname
                ,brs.type
                ,brs.chequeno
                ,brs.bank
                ,brs.amount
                ,brs.`status`
                ,t.name
        FROM    " . DB_PARENT . ".bank_reconc_stmt brs 
        INNER JOIN vendor v ON v.id = brs.vendorid
        INNER JOIN team t ON t.teamid = brs.responsible_id
        WHERE   brs.vendorid IS NOT NULL 
        AND     brs.status NOT IN (1,2)
        AND     brs.is_deleted = 0
        ORDER BY brs.id DESC";
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    $x = 0;
    while ($row = $db->get_nextRow()) {
        $Datacap = new stdClass();
        $x++;
        $Datacap->id = $row['id'];
        if ($row['id'] < 10) {
            $Datacap->transid = "BRS00" . $row['id'];
        } else {
            $Datacap->transid = "BRS0" . $row['id'];
        }

        $Datacap->vendorid = $row['vendorid'];
        $Datacap->vendorname = $row['vendorname'];
        if ($row['type'] == 0) {
            $Datacap->type = 'Cash';
        } elseif ($row['type'] == 1) {
            $Datacap->type = 'Cheque';
        }
        if (!empty($row['chequeno'])) {
            $Datacap->chequeno = $row['chequeno'];
        } else {
            $Datacap->chequeno = 'NA';
        }
        if (!empty($row['bank'])) {
            $Datacap->bank = $row['bank'];
        } else {
            $Datacap->bank = 'NA';
        }
        $Datacap->amount = $row['amount'];
        if ($row['status'] == 3) {
            $Datacap->status = 'Generated';
        } elseif ($row['status'] == 4) {
            $Datacap->status = 'Dispatched';
        } elseif ($row['status'] == 5) {
            $Datacap->status = 'Cleared';
        }
        $Datacap->responsible = $row['name'];
        $Datacap->x = $x;
        $name = "'" . $Datacap->transid . "'";
        $Datacap->delete = '<img src="../../images/delete.png" onclick="deleteWithdraw(' . $row['id'] . ',' . $name . ')"></img>';
        $Display[] = $Datacap;
    }
}
$dg = new objectdatagrid($Display);
$dg->AddColumn("Sr.No", "x");
$dg->AddColumn("Transaction ID", "transid");
$dg->AddColumn("Vendor No", "vendorid");
$dg->AddColumn("Vendor Name", "vendorname");
$dg->AddColumn("Type", "type");
$dg->AddColumn("Cheque #", "chequeno");
$dg->AddColumn("Bank", "bank");
$dg->AddColumn("Amount(in Rs)", "amount");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Responsible", "responsible");
$dg->AddColumn("Delete", "delete");
$dg->AddRightAction("View", "../../images/edit.png", "withdrawal_edit.php?id=%d");
$dg->SetNoDataMessage("No Withdrawals");
$dg->AddIdColumn("id");


include("header.php");
?> 
<style>
    .recipientbox {
        border: 1px solid #999999;
        float: left;
        font-weight: 700;
        padding: 4px 27px;
        /*    width: 100px;*/

        float:left;
        -webkit-transition:all 0.218s;
        -webkit-user-select:none;
        background-color:#000;
        /*background-image:-webkit-linear-gradient(top, #4D90FE, #4787ED);*/
        border:1px solid #3079ED;
        color:#FFFFFF;
        text-shadow:rgba(0, 0, 0, 0.0980392) 0 1px;
        border:1px solid #DCDCDC;
        border-bottom-left-radius:2px;
        border-bottom-right-radius:2px;
        border-top-left-radius:2px;
        border-top-right-radius:2px;

        cursor:default;
        display:inline-block;
        font-size:11px;
        font-weight:bold;
        height:27px;
        line-height:27px;
        min-width:46px;
        padding:0 8px;
        text-align:center;

        border: 1px solid rgba(0, 0, 0, 0.1);
        color:#fff !important;
        font-size: 11px;
        font: bold 11px/27px Arial,sans-serif !important;
        vertical-align: top;
        margin-left:5px;
        margin-top:5px;
        text-align:left;
    }
    .recipientbox img {
        float:right;
        padding-top:5px;
    }

    #submitwithdrawal{
        background: #00a6b8;
    }
</style>
<div class="panel">
    <div class="paneltitle" align="center">Add Withdrawals</div> 
    <div class="panelcontents">
        <form method="post" name="withdrawalform" id="withdrawalform" action="withdrawal.php" onsubmit="ValidateForm();
                return false;" enctype="multipart/form-data">
            <div style="height:20px;">
                <span id="general_success" style="display:none; color: #00FF00">Data Added Successfully.</span> 
                <span id="vid_error" style="display:none; color: red">Select correct vendor.</span> 
                <span id="type_err" style="display:none; color: red">Select correct type.</span> 
                <span id="amount_err" style="display:none; color: red">Amount field should not be empty.</span> 
                <span id="respons_err" style="display:none; color: red">Select correct responsible person.</span> 
                <span id="cheque_err" style="display:none; color: red">Cheque No field should not be empty.</span> 
                <span id="bank_err" style="display:none; color: red">Bank field should not be empty.</span> 
                <span id="bank_id_err" style="display:none; color: red">Select correct bank.</span> 
                <span id="fail_msg" style="display:none; color: red">Insertion failed.</span>
            </div>
            <table width="100%">
                <tr><td>Vendor</td>
                    <td>    
                        <select name="vid" id="vid" style="width:200px;">
                            <option value="0">Select vendor</option>
                            <?php
                            $cms = getvendor_detail();
                            foreach ($cms as $vendor) {
                                ?> 
                                <option  value="<?php echo($vendor->id); ?>" >
                                    <?php echo $vendor->vendorname; ?>
                                </option>
                                <?php
                            }
                            ?> 
                        </select>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td>
                        <select id="withdr_type" name="withdr_type">
                            <option value="-1">Select type</option>
                            <option value="0">Cash</option>
                            <option value="1">Cheque</option>
                        </select>
                    </td>
                    <td>Amount (in Rs)</td>
                    <td><input type="text" id="withdr_amt" name="withdr_amt" /></td>
                    <td>Responsible</td>
                    <td>
                        <select id="withdr_responsible" name="withdr_responsible">
                            <option value="-1">Select Team Member</option>
                            <?php
                            foreach ($teams as $data) {
                                echo '<option value="' . $data['teamid'] . '">' . $data['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Cheque No.</td>
                    <td><input type="text" id="withdr_chequeno" name="withdr_chequeno" /></td>
                    <td>Bank</td>
                    <td><input type="text" id="withdr_bank" name="withdr_bank" /></td>
                    <td>Withdraw from</td>
                    <td>
                        <select id="rec_bank" name="rec_bank">
                            <option value="-1">Select bank</option>
                            <?php
                            foreach ($banks as $data) {
                                echo '<option value="' . $data['id'] . '">' . $data['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" id="submitwithdrawal" name="submitwithdrawal" class="btn btn-primary" value="SUBMIT">
                    </td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Bank Outstandings</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>

</div>
<br/>
<?php
include("footer.php");
?>
<script>
    $(document).ready(function () {

<?php
if ($_REQUEST['msg'] == 'success') {
    echo 'jQuery("#success_msg").show();';
    echo 'jQuery("#success_msg").fadeOut(6000);';
} elseif ($_REQUEST['msg'] == 'fail') {
    echo 'jQuery("#fail_msg").show();';
    echo 'jQuery("#fail_msg").fadeOut(6000);';
}
?>
    });

    function ValidateForm() {
        var vid = jQuery('#vid').val();
        var type = jQuery('#withdr_type').val();
        var amount = jQuery('#withdr_amt').val();
        var responsible = jQuery('#withdr_responsible').val();
        var chequeno = jQuery('#withdr_chequeno').val();
        var bank = jQuery('#withdr_bank').val();
        var bank_id = jQuery('#rec_bank').val();

        if (vid < 1)
        {
            jQuery("#vid_error").show();
            jQuery("#vid_error").fadeOut(6000);
        } 
        if (type == '0')
        {
            if (amount == '')
            {
                jQuery("#amount_err").show();
                jQuery("#amount_err").fadeOut(6000);
                return false;
            }
        } else if (type == '1') {
            if (chequeno == '')
            {
                jQuery("#cheque_err").show();
                jQuery("#cheque_err").fadeOut(6000);
                return false;
            }
            if (bank == '')
            {
                jQuery("#bank_err").show();
                jQuery("#bank_err").fadeOut(6000);
                return false;
            }
            if (amount == '')
            {
                jQuery("#amount_err").show();
                jQuery("#amount_err").fadeOut(6000);
                return false;
            }
        } else {
            jQuery("#type_err").show();
            jQuery("#type_err").fadeOut(6000);
            return false;
        }
        if (responsible < 1)
        {
            jQuery("#respons_err").show();
            jQuery("#respons_err").fadeOut(6000);
            return false;
        }
        if (bank_id < 1)
        {
            jQuery("#bank_id_err").show();
            jQuery("#bank_id_err").fadeOut(6000);
            return false;
        } else {
            jQuery("#withdrawalform").submit()
        }
    }

    function deleteWithdraw(id, name) {
        if (confirm('Do you want to delete transaction "' + name + '"?')) {
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: {deleteDeposit: id},
                dataType: 'html',
                success: function (html) {
                    if (html == 'Success') {
                        alert("Successfully deleted.");
                        window.location.reload();
                    } else {
                        alert("Failed");
                        return false;
                    }
                }
            });
        } else {
            return false;
        }
    }
</script>