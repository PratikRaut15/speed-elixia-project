<?php
//error_reporting(E_ALL);

include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Date.php");


$db = new DatabaseManager();
$brsid = $_GET['id'];
if (isset($_POST['editdeposit'])) {

    $customerno = GetSafeValueString($_POST['cid'], "string");
    $type = GetSafeValueString($_POST['dep_type'], "string");
    $amount = GetSafeValueString($_POST['dep_amt'], "string");
    $responsible = GetSafeValueString($_POST['dep_responsible'], "string");
    $chequeno = GetSafeValueString($_POST['dep_chequeno'], "string");
    $bank = GetSafeValueString($_POST['dep_bank'], "string");
    $bankid = GetSafeValueString($_POST['rec_bank'], "string");
    $status = GetSafeValueString($_POST['dep_status'], "string");

    $todaysdate = date('Y-m-d H:i:s');

    $SQL = sprintf("UPDATE  " . DB_PARENT . ".bank_reconc_stmt 
                    SET     customerno = %d
                            ,type = %d
                            ,amount = %d
                            ,responsible_id = %d
                            ,chequeno = '%s'
                            ,bank = '%s'
                            ,bank_id = %d
                            ,status = %d
                            ,userid = %d
                    WHERE   id = %d;", Sanitise::Long($customerno)
            , Sanitise::Long($type)
            , Sanitise::Long($amount)
            , Sanitise::Long($responsible)
            , Sanitise::String($chequeno)
            , Sanitise::String($bank)
            , Sanitise::Long($bankid)
            , Sanitise::Long($status)
            , Sanitise::Long(GetLoggedInUserId())
            , Sanitise::Long($brsid));

    $db->executeQuery($SQL);


    $SQLinsert = sprintf("INSERT INTO `bank_transaction_history` (`brs_id`
                        ,`timestamp`
                        ,`customerno`
                        ,`userid`
                        ,`chequeno`
                        ,`bank`
                        ,`amount`
                        ,`type`
                        ,`status`
                        ,`responsible_id`
                        ,`bank_id`)
                VALUES(%d,'%s',%d,%d,'%s','%s',%d,%d,%d,%d,%d);", Sanitise::Long($brsid)
            , Sanitise::DateTime($todaysdate)
            , Sanitise::Long($customerno)
            , Sanitise::Long(GetLoggedInUserId())
            , Sanitise::String($chequeno)
            , Sanitise::String($bank)
            , Sanitise::Long($amount)
            , Sanitise::Long($type)
            , Sanitise::Long($status)
            , Sanitise::Long($responsible)
            , Sanitise::Long($bankid));

    $db->executeQuery($SQLinsert);
    header("location:deposit.php");
}

$SQL = sprintf("SELECT  *
                FROM    bank");
$db->executeQuery($SQL);

$x = 0;
$banks = Array();
if ($db->get_rowCount() > 0) {
    while ($row3 = $db->get_nextRow()) {
        $bank['id'] = $row3['id'];
        $bank['name'] = $row3['name'];
        $banks[] = $bank;
    }
}
//----------to fetech payment details--------------------------------------- 
$SQLtrans = sprintf("SELECT     bt.transid
                                ,bt.brs_id
                                ,bt.customerno
                                ,bt.type
                                ,bt.chequeno
                                ,bt.bank
                                ,bt.amount
                                ,bt.status
                                ,bt.timestamp
                                ,c.customercompany
                                ,t.name
                    FROM    " . DB_PARENT . ".bank_transaction_history bt
                    INNER JOIN customer c ON c.customerno = bt.customerno
                    INNER JOIN team t ON t.teamid = bt.responsible_id
                    WHERE   bt.brs_id = %d
                    ORDER BY bt.transid DESC", Sanitise::Long($brsid));
$db->executeQuery($SQLtrans);
if ($db->get_rowCount() > 0) {
    while ($row1 = $db->get_nextRow()) {
        $x++;
        $trans = new stdClass();
        $trans->id = $row1['transid'];
        if ($row1['brs_id'] < 10) {
            $trans->brs_id = "BRS00" . $row1['brs_id'];
        } else {
            $trans->brs_id = "BRS0" . $row1['brs_id'];
        }
        $trans->customerno = $row1['customerno'];
        $trans->customercompany = $row1['customercompany'];
        if ($row1['type'] == 0) {
            $trans->type = 'Cash';
        } else {
            $trans->type = 'Cheque';
        }
        if (!empty($row['chequeno'])) {
            $trans->chequeno = $row1['chequeno'];
        } else {
            $trans->chequeno = 'NA';
        }
        if (!empty($row1['bank'])) {
            $trans->bank = $row1['bank'];
        } else {
            $trans->bank = 'NA';
        }
        $trans->amount = $row1['amount'];
        if ($row1['status'] == 1) {
            $trans->status = 'Received';
        } elseif ($row1['status'] == 2) {
            $trans->status = 'Deposited';
        } elseif ($row1['status'] == 5) {
            $trans->status = 'Cleared';
        }
        $trans->responsible = $row1["name"];
        $trans->timestamp = date('d-m-Y H:i', strtotime($row1['timestamp']));
        $trans->x = $x;
        $Report[] = $trans;
    }
}
$dg = new objectdatagrid($Report);
$dg->AddColumn("Sr No", "x");
$dg->AddColumn("Transaction ID", "brs_id");
$dg->AddColumn("Customer No", "customerno");
$dg->AddColumn("Customer Name", "customercompany");
$dg->AddColumn("Type", "type");
$dg->AddColumn("Cheque #", "chequeno");
$dg->AddColumn("Bank", "bank");
$dg->AddColumn("Amount", "amount");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Responsible", "responsible");
$dg->AddColumn("Timestamp", "timestamp");
$dg->SetNoDataMessage("No History");
$dg->AddIdColumn("id");

$SQL = sprintf("SELECT  * 
                FROM    " . DB_PARENT . ".bank_reconc_stmt 
                WHERE   id = %d AND is_deleted = 0", Sanitise::Long($brsid));
$db->executeQuery($SQL);
$row = $db->get_nextRow();

//-----------populate customerno list-------
function getcustomer_detail() {
    $db = new DatabaseManager();
    $customernos = Array();
    $SQL = sprintf("SELECT customerno,customername,customercompany FROM " . DB_PARENT . ".customer");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row2 = $db->get_nextRow()) {
            $customer = new stdClass();
            $customer->customerno = $row2['customerno'];
            $customer->customername = $row2['customername'];
            $customer->customercompany = $row2['customercompany'];
            $customernos[] = $customer;
        }
        return $customernos;
    }
    return false;
}

function getteam_detail() {
    $db = new DatabaseManager();
    $teams = Array();
    $SQL = sprintf("SELECT teamid,name FROM " . DB_PARENT . ".team");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row2 = $db->get_nextRow()) {
            $team = new stdClass();
            $team->teamid = $row2['teamid'];
            $team->name = $row2['name'];
            $teams[] = $team;
        }
        return $teams;
    }
    return false;
}

include("header.php");
?>
<div class="panel">
    <div class="paneltitle" align="center">Edit Deposit Data</div> 
    <div class="panelcontents">
        <form method="post" name="editdepositForm" id="editdepositForm" onsubmit="ValidateForm();
                return false;">
            <div style="height:20px;">
                <span id="general_success" style="display:none; color: #00FF00">Updated Data Successfully.</span> 
                <span id="cust_err" style="display:none; color: red">Select correct customer.</span> 
                <span id="type_err" style="display:none; color: red">Select correct type.</span> 
                <span id="amount_err" style="display:none; color: red">Amount field should not be empty.</span> 
                <span id="respons_err" style="display:none; color: red">Select correct responsible person.</span> 
                <span id="cheque_err" style="display:none; color: red">Cheque No field should not be empty.</span> 
                <span id="bank_err" style="display:none; color: red">Bank field should not be empty.</span> 
                <span id="bank_id_err" style="display:none; color: red">Select correct bank.</span> 
                <span id="status_err" style="display:none; color: red">Select correct status.</span> 
                <span id="status_error" style="display:none; color: #FF0000">Updating failed.</span>
            </div>        
            <table width="90%">
                <tr>
                    <td>Transaction ID : </td>
                    <td>
                        <span><?php echo "BRS00" . $row['id']; ?></span>
                    </td>  
                    <td>Client</td>
                    <td>    
                        <select name="cid" id="cid" style="width:200px;">
                            <option value="0">Select Client</option>
                            <?php
                            $cms = getcustomer_detail();
                            foreach ($cms as $customer) {
                                ?> 
                                <option <?php
                                if ($row['customerno'] == $customer->customerno) {
                                    echo "selected";
                                }
                                ?> value="<?php echo($customer->customerno); ?>" >
                                    <?php echo $customer->customerno; ?> - <?php echo $customer->customercompany; ?>
                                </option>
                                <?php
                            }
                            ?> 
                        </select>
                    </td>
                    <td>Type</td>
                    <td>
                        <select id="dep_type" name="dep_type">
                            <option value="-1" <?php
                            if ($row['type'] != 0 && $row['type'] != 1) {
                                echo 'selected';
                            }
                            ?>>Select type</option>
                            <option value="0" <?php
                            if ($row['type'] == 0) {
                                echo 'selected';
                            }
                            ?>>Cash</option>
                            <option value="1" <?php
                            if ($row['type'] == 1) {
                                echo 'selected';
                            }
                            ?>>Cheque</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Deposit At</td>
                    <td>
                        <select name="rec_bank">
                            <option value="-1">Select bank</option>
                            <?php
                            $state = '';
                            foreach ($banks as $data) {
                                if ($data['id'] == $row['bank_id']) {
                                    $state = 'selected';
                                }
                                echo '<option value="' . $data['id'] . '" ' . $state . '>' . $data['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td>Amount(in Rs)</td>
                    <td><input type="text" id="dep_amt" name="dep_amt" value="<?php echo $row['amount']; ?>"/></td>
                    <td>Responsible</td>
                    <td>
                        <select name="dep_responsible">
                            <option value="0">Select Team member</option>
                            <?php
                            $tms = getteam_detail();
                            foreach ($tms as $team) {
                                ?> 
                                <option <?php
                                if ($row['responsible_id'] == $team->teamid) {
                                    echo "selected";
                                }
                                ?> value="<?php echo($team->teamid); ?>" >
                                        <?php echo $team->name; ?>
                                </option>
                                <?php
                            }
                            ?> 
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Cheque No</td>
                    <td><input type="text" id="dep_chequeno" name="dep_chequeno"  value="<?php echo $row['chequeno']; ?>"/></td>
                    <td>Bank</td>
                    <td><input type="text" id="dep_bank" name="dep_bank" value="<?php echo $row['bank']; ?>"/></td>
                    <td>Status</td>
                    <td>
                        <select id="dep_status" name="dep_status">
                            <option value="-1" <?php
                            if ($row['status'] != 1 && $row['status'] != 2 && $row['status'] != 5) {
                                echo 'selected';
                            }
                            ?>>Select type</option>
                            <option value="1" <?php
                            if ($row['status'] == 1) {
                                echo 'selected';
                            }
                            ?>>Received</option>
                            <option value="2" <?php
                            if ($row['status'] == 2) {
                                echo 'selected';
                            }
                            ?>>Deposited</option>
                            <option value="5" <?php
                            if ($row['status'] == 5) {
                                echo 'selected';
                            }
                            ?>>Cleared</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" id="editdeposit" style="background: #00a6b8;" name="editdeposit" class="btn btn-primary" value="Edit Deposit">
                    </td>
                    <td colspan="2"></td>
                </tr>
            </table>
            <input type="hidden" name="brs_id" value="<?php echo $brsid ?>">           

        </form>
    </div>
</div>

<br/>
<!----------------history list---------------------------------------------->
<div class="panel">
    <div class="paneltitle" align="center">Transaction History</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>

</div>
<br/>
<?php
include("footer.php");
?>
<script>



    function ValidateForm() {

        var customerno = jQuery('#cid').val();
        var type = jQuery('#dep_type').val();
        var amount = jQuery('#dep_amt').val();
        var responsible = jQuery('#dep_responsible').val();
        var chequeno = jQuery('#dep_chequeno').val();
        var bank = jQuery('#dep_bank').val();
        var bank_id = jQuery('#rec_bank').val();
        var status = jQuery('#dep_status').val();

        if (customerno < 1)
        {
            jQuery("#cust_err").show();
            jQuery("#cust_err").fadeOut(6000);
            return false;
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
        }
        if (status < 1) {
            jQuery("#status_err").show();
            jQuery("#status_err").fadeOut(6000);
            return false;
        } else {
            jQuery("#editdepositForm").submit();
        }
    }

</script>