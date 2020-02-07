<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Date.php");

class testing {
    
}

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$claimant = GetLoggedInUserId();

$db = new DatabaseManager();
$SQL = sprintf("SELECT team.teamid, team.name FROM " . DB_PARENT . ".team ORDER BY name asc");
$db->executeQuery($SQL);
$team_allot_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $team = new testing();
        $team->teamid = $row["teamid"];
        $team->name = $row["name"];
        $team_allot_array[] = $team;
    }
}
$SQL = sprintf("SELECT customerno, customercompany FROM " . DB_PARENT . ".customer");
$db->executeQuery($SQL);
$customer = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $testing = new testing();
        $testing->customerno = $row["customerno"];
        $testing->customername = $row["customerno"] . "( " . $row['customercompany'] . " )";
        $customer[] = $testing;
    }
}
$SQL = sprintf("SELECT headid, headtype FROM " . DB_PARENT . ".account_head");
$db->executeQuery($SQL);
$headdata = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $testing = new testing();
        $testing->headid = $row["headid"];
        $testing->headtype = $row['headtype'];
        $headdata[] = $testing;
    }
}
if (isset($_POST['addvoucher'])) {
    $count = count($_POST['head']);
    $claimant = GetLoggedInUserId();
    $db = new DatabaseManager();
    $voucherdate = $_POST["voucherdate"];
    $voucherdate1 = date("Y-m-d", strtotime($voucherdate));
    if (!empty($voucherdate1) && $voucherdate1 != "1970-01-01") {
        $todaydate = $voucherdate1;
    } else {
        $todaydate = date("Y-m-d");
    }

    $SQL = sprintf("SELECT distinct(MAX(voucherid)) as maxvoucherid FROM " . DB_PARENT . ".voucher");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $maxvoucherid = $row["maxvoucherid"];
            $voucherid = $maxvoucherid + 1;
        }
    } else {
        $voucherid = 1;
    }

    for ($i = 0; $i < $count; $i++) {
        $vdate = $_POST['vdate'][$i];
       
        if (!empty($vdate) && $vdate != "1970-01-01") {
            $datetest = date("Y-m-d", strtotime($vdate));
            $todaydate1 = $datetest;
        } else {
            $todaydate1 = date("Y-m-d");
        }
        if ($_POST['head'][$i] != '0' && $_POST['customer'][$i] != '0' && !empty($_POST['amount'][$i])) {
            $sql = sprintf("INSERT INTO " . DB_PARENT . ".`voucher` (
                `voucherid`,
                `claimant` ,
                `claimdate`,
                `voucherdate`,
                `headid` ,
                `customer` ,
                `amount`,
                `remarks`
                )
                VALUES (
                '%d','%d','%s','%s','%d', '%d','%d','%s');", $voucherid, $claimant, $todaydate, $todaydate1, $_POST['head'][$i], $_POST['customer'][$i], $_POST['amount'][$i], $_POST['remark']);
            $db->executeQuery($sql);
        }
    }
    header("location:addvoucher.php");
}
$SQL = sprintf("select voucherid, claimant,claimdate, sum(amount) as vamttotal,(CASE WHEN ispaid = 0 THEN 'Unpaid' WHEN ispaid=2 THEN 'P-Paid' ELSE 'Paid' END) as paymentstatus from " . DB_PARENT . ".voucher where claimant = '" . $claimant . "' group by voucherid order by voucherid desc");
$db->executeQuery($SQL);

function paidbalance($vid) {
    $db = new DatabaseManager();
    $SQL = sprintf("select sum(pay_amount) as payamount from  " . DB_PARENT . ".voucher_payment  where voucher_id=" . $vid);
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $payamount = $row["payamount"];
    }
    return $payamount;
}

$x = 0;
$dispdetails = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $x++;
        $user = new testing();
        $user->voucherid = $row['voucherid'];
        $user->claimdate = $row['claimdate'];
        $user->vamttotal = $row['vamttotal'];
        $user->pay_amount = paidbalance($row['voucherid']);
        $user->paymentstatus = $row['paymentstatus'];
        $user->x = $x;
        $dispdetails[] = $user;
    }
}
$dg = new objectdatagrid($dispdetails);
$db->executeQuery($SQL);
//$dg = new datagrid( $db->getQueryResult());
$dg->AddColumn("Sr.No", "x");
$dg->AddColumn("Voucher Id", "voucherid");
$dg->AddColumn("Claim Date", "claimdate");
$dg->AddColumn("Voucher Amount", "vamttotal");
$dg->AddColumn("Paid Amount", "pay_amount");
$dg->AddColumn("Status", "paymentstatus");
$dg->SetNoDataMessage("No Voucher");
$dg->AddAction("View/Print", "../../images/history.png", "print_voucher.php?vid=%d");
$dg->AddRightAction("Edit", "../../images/edit.png", "editvoucher.php?vid=%d");
$dg->AddIdColumn("voucherid");

include("header.php");
?>


<div class="panel">
    <div class="paneltitle" align="center">Add vouchers</div>
    <div style="margin:10px; float:left;">
        <table>
            <tr><td><label><b>Claimant</b> : <?php echo GetLoginUser(); ?></label></td></tr>
        </table>
    </div>
    <div style="float:right; margin:5px;">
        <?php echo"Date:" . date("d-m-Y"); ?>
        <input type="hidden" id="voucherdate" name="voucherdate" value="<?php echo date("d-m-Y"); ?>">
    </div>
    <div style="clear:both;"></div>
    <hr/>

    <div class="panelcontents"  align="center">
        <div style="width:100%;">
            <div style="float:right;"><input type="button" onclick="addrow()" value="Add Row"></div><br/>
            <form name="addvoucherform" method="POST"> 
                <table width="35%" border="1" id="myTable">
                    <tr><th>Account Head<span style="color:red;">*</span></th><th>For Customer <span style="color:red;">*</span></th><th>Amount <span style="color:red;">*</span></th><th>Date <span style="color:red;">*</span></th></tr>
                    <?php
                    for ($i = 0; $i < 5; $i++) {
                        ?>
                        <tr>
                            <td>
                                <select name="head[]" id="head">
                                    <option value="0">Select Head</option> 
                                    <?php
                                    foreach ($headdata as $thishead) {
                                        ?>
                                        <option value="<?php echo($thishead->headid); ?>"><?php echo($thishead->headtype); ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="customer[]" id="customer">
                                    <option value="0">Select a Customer</option>     
                                    <option value="-1"> -1 (Shrushti Repair)</option>     
                                    <?php
                                    foreach ($customer as $thiscustomer) {
                                        ?>
                                        <option value="<?php echo($thiscustomer->customerno); ?>"><?php echo($thiscustomer->customername); ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><input type='text' name='amount[]' id='amount' onkeypress="return onlyNos(event, this);" /></td>
                            <td><input id='voucherdate<?php echo $i; ?>' placeholder='dd-mm-yyyy' class="voucherdate" name = 'vdate[]' type='text'></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <br/>
                <div style="width:39%; float:left; margin-left:8%;">
                    Remark <textarea name='remark' id='remark'></textarea></br>
                    <input type="submit" name="addvoucher" id="addvoucher" value="Add Voucher"/>
                </div>
            </form>
            <div style="clear:both"></div>
        </div>
    </div>
</div>
<br/>
<div class="panel">
    <?php
    $sql = sprintf("select sum(amount)as  vamt from " . DB_PARENT . ".voucher where claimant=" . $claimant);
    $db->executeQuery($sql);
    while ($row = $db->get_nextRow()) {
        $vamt = $row["vamt"];
    }
    $sql = sprintf("select distinct(voucherid) from " . DB_PARENT . ".voucher where claimant=" . $claimant . " AND ispaid IN(1,2)");
    $db->executeQuery($sql);
    $vids = array();
    while ($row = $db->get_nextRow()) {
        $vids[] = $row["voucherid"];
    }

    $vids = implode(",", $vids);
    if (!empty($vids)) {
        $sql = sprintf("select sum(pay_amount) as paid from " . DB_PARENT . ".voucher_payment where voucher_id IN(" . $vids . ")");
        $db->executeQuery($sql);
        $vids = array();
        while ($row = $db->get_nextRow()) {
            $paid = $row["paid"];
        }
    }
//echo $paid."-".$vamt;
    $running_bal = $paid - $vamt;
//////////////////advanced balance calculation///////////////////////
    $db = new DatabaseManager();
    $sql = sprintf("select sum(`amount`) as advance_paid from " . DB_PARENT . ".`cash_received` where `advp_status`='1' AND `received_by`=" . $claimant);
    $db->executeQuery($sql);
    while ($row = $db->get_nextRow()) {
        $advance_paid1 = $row["advance_paid"];
    }

    $sql = sprintf("select distinct(voucherid) from " . DB_PARENT . ".voucher where claimant=" . $claimant);
    $db->executeQuery($sql);
    $voucherids = array();
    while ($row = $db->get_nextRow()) {
        $voucherids[] = $row["voucherid"];
    }
    $voucheridsall = implode(',', $voucherids);
    if (!empty($voucheridsall)) {
        $sql = sprintf("select sum(pay_amount) as paidamt from " . DB_PARENT . ".voucher_payment where voucher_id IN($voucheridsall) AND advpaid=1");
        $db->executeQuery($sql);
        while ($row = $db->get_nextRow()) {
            $paidamt = $row["paidamt"];
        }
    } else {
        $paidamt = 0;
    }
    $pay_amount_adv = $advance_paid1 - $paidamt;
    ?>
    <div class="paneltitle">Voucher List<span style="float:right;">Advance Balance:<?php echo $pay_amount_adv; ?> Running Bal: <?php echo $running_bal; ?></span></div>
    <div class="panelcontents">
        <span style="text-align: center;"><b>Note :</b> (Negative bal) - Company owes  <b>or</b> (Positive Bal) - Advance paid</span>
    </div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>
</div>


<br/>
<?php
include("footer.php");
?>

<script>
    function addrow() {
        var table = document.getElementById("myTable");
        var row = table.insertRow(1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        //cell1.innerHTML = "<button onclick='addrow()'>Add</button>"; 
        cell1.innerHTML = "<select name='head[]'><option value='0'>Select Head</option><?php
foreach ($headdata as $thishead) {
    echo "<option value='" . $thishead->headid . "'>" . $thishead->headtype . "</option>";
}
?></select>";
        cell2.innerHTML = "<select name=\"customer[]\" id=\"customer\"><option value=\"0\">Select a Customer</option> <option value=\"-1\"> -1 (Shrushti Repair)</option><?php foreach ($customer as $thiscustomer) { ?> <option value=\"<?php echo($thiscustomer->customerno); ?>\"><?php echo($thiscustomer->customername); ?></option><?php } ?></select>";
        cell3.innerHTML = "<input type='text' name='amount[]' id='amount'/>";
        cell4.innerHTML = "<input id='voucherdate' placeholder='dd-mm-yyyy'  name = 'vdate[]' class='voucherdate' type='text'>";

    }


    function onlyNos(e, t) {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            } else if (e) {
                var charCode = e.which;
            } else {
                return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        } catch (err) {
            alert(err.Description);
        }
    }
    $(".voucherdate").datepicker({ dateFormat: "dd-mm-yy",language: 'en',autoclose: 1 });
</script>
