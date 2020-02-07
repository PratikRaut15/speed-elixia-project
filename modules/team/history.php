<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

class unit {
    
}

//Datagtrid

$id = GetSafeValueString(isset($_GET["id"]) ? $_GET["id"] : $_POST["id"], "long");
$display = array();
include("header.php");

$db = new DatabaseManager();
$SQL = sprintf("SELECT th.allot_teamid, team_allot_teamid.name as allot_name, th.customerno, t.status, th.transaction, team_teamid.name, th.trans_time, th.thid, th.simcardno, th.invoiceno, th.expirydate, unit.type_value FROM " . DB_PARENT . ".trans_history th LEFT OUTER JOIN " . DB_PARENT . ".trans_status t ON t.id = th.statusid INNER JOIN unit ON unit.uid = th.unitid INNER JOIN " . DB_PARENT . ".team team_teamid ON team_teamid.teamid = th.teamid LEFT OUTER JOIN " . DB_PARENT . ".team team_allot_teamid ON team_allot_teamid.teamid = th.allot_teamid WHERE th.type=0 AND unitid = %d ORDER BY trans_time DESC", $id);

$db->executeQuery($SQL);
$history = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $device = new unit();
        if ($row["allot_teamid"] == 0) {
            $device->status = $row["status"];
        } else {
            $device->status = $row["status"] . ": " . $row["allot_name"];
        }
        $device->customerno = $row["customerno"];
        $device->transaction = $row["transaction"];
        $device->name = $row["name"];
        $device->trans_time = date("d-m-Y H:i", strtotime($row["trans_time"]));
        $device->thid = $row["thid"];
        $device->simcardno = $row["simcardno"];
        $device->invoiceno = $row["invoiceno"];
        if ($row["expirydate"] == "0000-00-00") {
            $device->expirydate = "";
        } else {
            $device->expirydate = date("d-m-Y", strtotime($row["expirydate"]));
        }
        $history[] = $device;
    }
}
//echo '<pre>';
//print_r($history);
$dg = new objectdatagrid($history);
$dg->AddColumn("Transaction", "transaction");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Customer #", "customerno");
$dg->AddColumn("Simcard #", "simcardno");
$dg->AddColumn("Invoice #", "invoiceno");
$dg->AddColumn("Expiry", "expirydate");
$dg->AddColumn("Date", "trans_time");
$dg->AddColumn("Done by", "name");
$dg->SetNoDataMessage("No History");
$dg->AddIdColumn("thid");

//------------------------------------to display type of unit query-----------------------------------
$SQL = sprintf("SELECT unitno, customerno, type_value,trans_statusid FROM unit WHERE uid = %d", $id);
$db->executeQuery($SQL);

if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $data = new unit();
        //-----------------------------to find the type of device-------------------------------
        $category_array = Array();
        $category = (int) $row['type_value'];
        $binarycategory = sprintf("%08s", DecBin($category));
        for ($shifter = 1; $shifter <= 3000; $shifter = $shifter << 1) {
            $binaryshifter = sprintf("%08s", DecBin($shifter));
            if ($category & $shifter) {
                $category_array[] = $shifter;
            }
        }
        //print_r($category_array);
        $data->customerno = $row["customerno"];
        $data->unitno = $row["unitno"];
        $data->trans_statusid = $row["trans_statusid"];
        $trans_status = $row["trans_statusid"];
        //$data->unitid = $row["uid"];
        //----------------------------------Display type of Unit------------------------------------
        if ($row['type_value'] != 0 && !in_array(0, $category_array)) {
            $data->basic = "Yes";
        } else {
            $data->basic = "Yes";
        }
        if (in_array(1, $category_array)) {
            $data->ac = "Yes";
        } else {
            $data->ac = "No";
        }
        if (in_array(4, $category_array)) {
            $data->door = "Yes";
        } else {
            $data->door = "No";
        }
        if (in_array(2, $category_array)) {
            $data->genset = "Yes";
        } else {
            $data->genset = "No";
        }
        if (in_array(8, $category_array)) {
            $data->stemp = "Yes";
        } else {
            $data->stemp = "No";
        }
        if (in_array(16, $category_array)) {
            $data->dtemp = "Yes";
        } else {
            $data->dtemp = "No";
        }
        if (in_array(32, $category_array)) {
            $data->panic = "Yes";
        } else {
            $data->panic = "No";
        }
        if (in_array(64, $category_array)) {
            $data->buzzer = "Yes";
        } else {
            $data->buzzer = "No";
        }
        if (in_array(128, $category_array)) {
            $data->immo = "Yes";
        } else {
            $data->immo = "No";
        }

        if (in_array(1024, $category_array)) {
            $data->fuel = "Yes";
        } else {
            $data->fuel = "No";
        }

        if (in_array(512, $category_array)) {
            $data->portable = "Yes";
        } else {
            $data->portable = "No";
        }

        if (in_array(256, $category_array)) {
            $data->twowaycom = "Yes";
        } else {
            $data->twowaycom = "No";
        }

        $display[] = $data;
    }
}

//-----datagrid parameters for display type of error-----------------

$dt = new objectdatagrid($display);

$dt->AddColumn("Customer No.", "customerno");
//$dt->AddColumn("Elixir", "teamname");
$dt->AddColumn("Unit no", "unitno");
//$dt->AddColumn("Simcard No.", "simcardno");
$dt->AddColumn("Basic", "basic");
$dt->AddColumn("AC", "ac");
$dt->AddColumn("Genset", "genset");
$dt->AddColumn("Door", "door");
$dt->AddColumn("Fuel", "fuel");
$dt->AddColumn("Single Temperature", "stemp");
$dt->AddColumn("Double Temperature", "dtemp");
$dt->AddColumn("Panic", "panic");
$dt->AddColumn("Buzzer", "buzzer");
$dt->AddColumn("Immobilizer", "immo");
$dt->AddColumn("Two way communication", "twowaycom");
$dt->AddColumn("Portable", "portable");

$dt->SetNoDataMessage("No History");
$dt->AddIdColumn("id");

//--------------to display purchase details---------
$purchase = Array();
$SQL2 = sprintf("SELECT * FROM " . DB_PARENT . ".chalaan WHERE uid = %d", $id);

$db->executeQuery($SQL2);

if ($db->get_rowCount() > 0) {
    while ($row2 = $db->get_nextRow()) {
        $DATA = new unit();
        $DATA->chalid = $row2['chalid'];
        $DATA->chalaan_no = $row2['chalaan_no'];
        if ($row2['chalaan_date'] == '0000-00-00' || $row2['chalaan_date'] == '' || $row2['chalaan_date'] == '1970-01-01') {
            $DATA->chalaan_date = '';
        } else {
            $DATA->chalaan_date = date("d-m-Y", strtotime($row2['chalaan_date']));
        }
        $DATA->vendor_invno = $row2['vendor_invno'];
        if ($row2['vendor_invdate'] == '0000-00-00' || $row2['vendor_invdate'] == '' || $row2['vendor_invdate'] == '1970-01-01') {
            $DATA->vendor_invdate = '';
        } else {
            $DATA->vendor_invdate = date("d-m-Y", strtotime($row2['vendor_invdate']));
        }
        $purchase[] = $DATA;
    }
}
//-----datagrid parameters for display purchase detail -----------------

$ds = new objectdatagrid($purchase);

$ds->AddColumn("Chaalan No.", "chalaan_no");
$ds->AddColumn("Chaalan Date", "chalaan_date");
$ds->AddColumn("Vendor Invoice No.", "vendor_invno");
$ds->AddColumn("Vendor Invoice Date", "vendor_invdate");
$ds->AddRightAction("View/Edit", "../../images/edit.png", "editchaalan.php?chid=%d");
$ds->SetNoDataMessage("No History");
$ds->AddIdColumn("chalid");

$SQL3 = "SELECT id,status FROM " . DB_PARENT . ".trans_status WHERE type=0";
$db->executeQuery($SQL3);
$status = array();
if ($db->get_rowCount() > 0) {
    $detail = array();
    while ($row3 = $db->get_nextRow()) {
        $detail['id'] = $row3['id'];
        $detail['status'] = $row3['status'];
        $status[] = $detail;
    }
}

if (isset($_POST['updateStatus'])) {
    $today = date("Y-m-d H:i:s");
    $teamid = $_SESSION['sessionteamid'];
    $uid = $_REQUEST['id'];
    $new_status = $_POST['status'];
    foreach ($display as $data) {
        $old_status = $data->trans_statusid;
    }
    $SQL4 = sprintf("UPDATE " . DB_PARENT . ".unit SET trans_statusid=%d WHERE uid=%d", $new_status, $uid);
    $db->executeQuery($SQL4);
    $SQL4 = sprintf("INSERT INTO " . DB_PARENT . ".status_change_log(id,old_status,new_status,type,updated_on,updated_by) VALUES(%d,%d,%d,0,'%s',%d)", $uid, $old_status, $new_status, $today, $teamid);
    $db->executeQuery($SQL4);
}

//-------Log history of status changed---------
$SQL6 = sprintf("SELECT scl_id,old_status,new_status,updated_on,updated_by FROM status_change_log WHERE id = %d AND type=0", $id);

$db->executeQuery($SQL6);
$statuslog = array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $data = new stdClass();
        $data->scl_id = $row['scl_id'];
        $data->old_status = $row['old_status'];
        $data->new_status = $row['new_status'];
        $data->updated_on = $row['updated_on'];
        $data->updated_by = $row['updated_by'];
        $statuslog[] = $data;
    }
}
//echo '<pre>';
//print_r($statuslog); die();
//-----datagrid parameters for display type of error-----------------

$dr = new objectdatagrid($statuslog);
$dr->AddColumn("Old Status", "old_status");
$dr->AddColumn("New Status", "new_status");
$dr->AddColumn("Updated On", "updated_on");
$dr->AddColumn("Updated By", "updated_by");
$dr->SetNoDataMessage("No Log Details");
$dr->AddIdColumn("scl_id");
?>
<br/>
<?php if ($trans_status != 5 && $trans_status != 6) { ?>
    <div class="panel">
        <div class="paneltitle" align="center">Change Status of Device # <?php echo $data->unitno; ?></div>
        <div class="panelcontents" align="center">
            <form method="POST" action="history.php?id=<?php echo $id; ?>">
                Status change to <select name="status">
                    <?php
                    foreach ($status as $data) {
                        echo "<option value=" . $data['id'] . ">" . $data['status'] . "</option>";
                    }
                    ?> </select><br>
                <input type="submit" value="Update" name="updateStatus">
                </select>
            </form>
        </div>
    </div>
    <?php
}
?>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Type of Unit # <?php echo $data->unitno; ?></div>
    <div class="panelcontents">
        <?php $dt->Render(); ?>
    </div>

    <br/>
</div>
<br/>
<?php
if (IsHead()) {
    ?>
    <div class="panel">
        <div class="paneltitle" align="center">Purchase Details For Unit # <?php echo $data->unitno; ?></div>
        <div class="panelcontents">
            <?php $ds->Render(); ?>
        </div>

        <br/>
    </div>
    <?php
}
?>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">History for Unit # <?php echo $data->unitno; ?></div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>

</div>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">History for Status change of # <?php echo $data->unitno; ?></div>
    <div class="panelcontents">
        <?php $dr->Render(); ?>
    </div>

</div>
<?php
include("footer.php");
?>