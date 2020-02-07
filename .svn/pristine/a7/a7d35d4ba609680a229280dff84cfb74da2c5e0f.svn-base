<?php
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/datatables/jquery.dataTables_new.css" type="text/css" />';
echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
?>
<style>
    table.dataTable.select tbody tr,
    table.dataTable thead th:first-child {
        cursor: pointer;
    }
    .table-disable-hover.table tbody tr:hover td,
    .table-disable-hover.table tbody tr:hover th {
        background-color: inherit;
    }
    .table-disable-hover.table-striped tbody tr:nth-child(odd):hover td,
    .table-disable-hover.table-striped tbody tr:nth-child(odd):hover th {
        background-color: #f9f9f9;
    }
    .lengthMenu{
        margin: auto;
        width: 10%;
    }
</style>

<form method="POST" name="transaction_form" id="transaction_form">
    <?php
    $devices = getapproved_vehicles();
    $devicesopt = "";
    if (isset($devices)) {
        foreach ($devices as $device) {
            if (isset($_POST['vehicleid']) && $device->vehicleid == $_POST['vehicleid']) {
                $devicesopt .= "<option value = '$device->vehicleid' rel='$device->vehicleno' id = '$device->vehicleid' selected = 'selected'>$device->vehicleno</option>";
            } else {
                $devicesopt .= "<option value = '$device->vehicleid' id = '$device->vehicleid' rel='$device->vehicleno'>$device->vehicleno</option>";
            }
        }
    }
    $statuses = get_trans_status();
    $statusopt = "";
    if (isset($statuses)) {
        foreach ($statuses as $status) {
            if (isset($_POST['statusid']) && $status->statusid == $_POST['statusid']) {
                $statusopt .= "<option value = '$status->statusid' selected = 'selected'>$status->name</option>";
            }else{
                $statusopt .= "<option value = '$status->statusid'>$status->name</option>";
            }
        }
    }
    $categoryopt = "";
    $category = isset($_POST['category']) ? $_POST['category'] : 'nan';
    if ($category == '0') {
        $categoryopt .= "<option value = '0' selected = 'selected'>Battery</option>";
    } else {
        $categoryopt .= "<option value = '0'>Battery</option>";
    }
    if ($category == '1') {
        $categoryopt .= "<option value = '1' selected = 'selected'>Tyre</option>";
    } else {
        $categoryopt .= "<option value = '1'>Tyre</option>";
    }
    if ($category == '2') {
        $categoryopt .= "<option value = '2' selected = 'selected'>Repair</option>";
    } else {
        $categoryopt .= "<option value = '2'>Repair</option>";
    }
    if ($category == '3') {
        $categoryopt .= "<option value = '3' selected = 'selected'>Service</option>";
    } else {
        $categoryopt .= "<option value = '3'>Service</option>";
    }
    if ($category == '4') {
        $categoryopt .= "<option value = '4' selected = 'selected'>Accident Claim</option>";
    } else {
        $categoryopt .= "<option value = '4'>Accident Claim</option>";
    }
    if ($category == '5') {
        $categoryopt .= "<option value = '5' selected = 'selected'>Accessories</option>";
    } else {
        $categoryopt .= "<option value = '5'>Accessories</option>";
    }
    if ($category == '6') {
        $categoryopt .= "<option value = '6' selected = 'selected'>Fuel</option>";
    } else {
        $categoryopt .= "<option value = '6'>Fuel</option>";
    }
    $alldealer = getdealers();
    $alldealeropt = "";
    if (isset($alldealer)) {
        foreach ($alldealer as $alldealers) {
            if (isset($_POST['trans_dealer']) && $alldealers->dealerid == $_POST['trans_dealer']) {
                $alldealeropt .= "<option value = '$alldealers->dealerid' selected = 'selected'>$alldealers->dealername</option>";
            } else {
                $alldealeropt .= "<option value = '$alldealers->dealerid'>$alldealers->dealername</option>";
            }
        }
    }
    $dealerclmn = true;
    if (isset($_POST["Filter"])) {
        $transid = GetSafeValueString($_POST["transactionid"], "string");
        $vehicleid = GetSafeValueString($_POST["vehicleid"], "string");
        $categoryid = GetSafeValueString($_POST["category"], "string");
        $statusid = GetSafeValueString($_POST["statusid"], "string");
        $tyre = GetSafeValueString($_POST['tyre_type'], "string");
        $parts = GetSafeValueString($_POST['parts_select'], "string");
        $dealerid = GetSafeValueString($_POST['trans_dealer'], "string");
        if (($categoryid != '4' && $categoryid != '6') || $categoryid == '-1') {
            $maintances = getfilteredmaintanances($transid, $vehicleid, $categoryid, $statusid, $tyre, $parts, $dealerid);
        }
        if (($categoryid == '4' || $categoryid == '-1') && $dealerid == '-1') {
            $dealerclmn = false;
            $accidents = getfilteredaccidents($transid, $vehicleid, $statusid);
        }
        if (($categoryid == '6' || $categoryid == '-1')) {
            $fuels = getfilteredfuels($transid, $vehicleid, $dealerid);
        }
    } else {
        $maintances = getallmaintanances();
        $accidents = getallaccidents();
        $fuels = getallfuels();
    }
    ?>
    <table>
        <tr>
            <td>Transaction ID</td>
            <td>
                <input id="transactionid" name="transactionid" type="text" value="<?php
                if (isset($_POST['transactionid'])) {
                    echo $_POST['transactionid'];
                }
                ?>"/>
            </td>
            <td>
                <select id="vehicleid" name="vehicleid" style="width: 150px;">
                    <option value="">Select Vehicle</option>
                    <option value="1">All Vehicle</option>
                    <?php echo $devicesopt; ?>
                </select>
            </td>
            <td>
                <select id="category" name="category" onchange="tyrelist();">
                    <option value="-1">Select Category</option>
                    <option value="-2">All Category</option>
                    <?php echo $categoryopt; ?>
                </select>
            </td>
            <td>
                <select id="trans_dealer" name="trans_dealer" style="width: 150px;">
                    <option value="-1">Select Dealer</option>
                    <option value="1">All Dealer</option>
                    <?php echo $alldealeropt; ?>
                </select>
            </td>
            <td>
                <select id="tyre_type" name="tyre_type" <?php if (isset($_POST['category']) && $_POST['category'] != '1') { ?> style="display: none;" <?php } ?>>
                    <option value="">Select Tyre</option>
                    <option value="-1">All Tyre</option>
                    <option value="0" <?php
                    if (isset($_POST['tyre_type']) && $_POST['tyre_type'] == '0') {
                        echo "selected";
                    }
                    ?>>Select type</option>
                    <option value="1" <?php
                    if (isset($_POST['tyre_type']) && $_POST['tyre_type'] == '1') {
                        echo "selected";
                    }
                    ?>>Right front</option>
                    <option value="2" <?php
                    if (isset($_POST['tyre_type']) && $_POST['tyre_type'] == '2') {
                        echo "selected";
                    }
                    ?>>Right Back</option>
                    <option value="3" <?php
                    if (isset($_POST['tyre_type']) && $_POST['tyre_type'] == '3') {
                        echo "selected";
                    }
                    ?>>Left front</option>
                    <option value="4" <?php
                    if (isset($_POST['tyre_type']) && $_POST['tyre_type'] == '4') {
                        echo "selected";
                    }
                    ?>>Left Back</option>
                    <option value="5" <?php
                    if (isset($_POST['tyre_type']) && $_POST['tyre_type'] == '5') {
                        echo "selected";
                    }
                    ?>>Stepney</option>
                </select>
                <select id="parts_select" style="width: 200px;" name="parts_select" <?php if (isset($_POST['category']) && $_POST['category'] != '2') { ?> style="display: none;" <?php } ?>>
                    <option value="-1">Select Parts</option>
                    <option value="0">All Parts</option>
                    <?php
                    $parts = getpart();
                    if (isset($parts)) {
                        foreach ($parts as $part) {
                            if (isset($_POST['parts_select']) && $_POST['parts_select'] == $part->id) {
                                echo "<option value='$part->id' selected>$part->part_name</option>";
                            } else {
                                echo "<option value='$part->id'>$part->part_name</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <select id="statusid" name="statusid" style="width: 200px;">
                    <option value="">Select Status</option>
                    <option value="0">All Status</option>
                    <?php echo $statusopt; ?>
                </select>
            </td>
            <td style="padding-bottom:10px;"><input type="button" class="g-button g-button-submit" value="Search" name="Filter" id ="filter_transaction" onclick="getFilteredTransaction();"></td>
        </tr>
        </tbody>
    </table>
    <?php
    if ($_SESSION['roleid'] == $accountRoleId || $_SESSION['roleid'] == $masterRoleId){
        ?>
        <div style="text-align: right; margin: 20px 20px 0px 0px;">
            <button id="btnApprove" type="button" class="btn btn-info btn-lg" data-toggle="modal" href="#approveModal">Close Transactions</button>
        </div>
    <?php } ?>
    <input type="hidden" name="filtertxt" id="filtertxt" >
</form>
<?php
$elixirRoleId = 1;
$masterRoleId = 1;
$stateRoleId = 2;
$zoneRoleId = 3;
$regionRoleId = 4;
$groupRoleId = 5;
switch ($_SESSION['customerno']) {
    case 63:
        $masterRoleId = 28;
        $zoneRoleId = 30;
        $regionRoleId = 31;
        break;
    case 64:
        $masterRoleId = 33;
        $zoneRoleId = 35;
        $regionRoleId = 36;
        break;
    case 118:
        $masterRoleId = 18;
        $stateRoleId = 19;
        $zoneRoleId = 20;
        $regionRoleId = 21;
        $groupRoleId = 22;
        $accountRoleId = 42;
        break;
    case 167:
        $masterRoleId = 24;
        $zoneRoleId = 26;
        $regionRoleId = 27;
        break;
    default:
        $masterRoleId = 1;
        $zoneRoleId = 3;
        $regionRoleId = 4;
        break;
}
?>
<style>
    #viewtransaction_filter{
        margin-right: 5px;
    }
    #pageloaddiv {
        /*    display : block;
            position: fixed;
            left: 0px;
            top: -80px;
            width: 100%;
            height: 100%;
            background: url('../../images/progressbar.gif') no-repeat center center;
                background-color:#666;
            opacity : 0.4;*/
        background: rgba(0, 0, 0, 0) url("../../images/progressbar.gif") no-repeat fixed center center;
        height: 500px;
        left: 0;
        position: absolute;
        top: 25%;
        width: 100%;
    }
</style>
<div class="dashdiv" id="pageloaddiv" style='display:none;'></div>
<table class='display table table-bordered table-disable-hover select'  id="viewtransaction" style="width:90%">
    <thead>
        <tr class="filterrow">
            <td><input type="hidden" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="hidden" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
        </tr>
        <tr>
            <th data-sortable="false"><?php if ($_SESSION['roleid'] == $accountRoleId || $_SESSION['roleid'] == $masterRoleId) { ?><input  id="check_all" type="checkbox"/> <?php } ?> </th>
            <th data-sortable="true">Transaction ID</th>
            <th data-sortable="true">Meter Reading</th>
            <th data-sortable="true">Vehicle No</th>
            <th data-sortable="true">Category</th>
            <th data-sortable="true"><?php echo($_SESSION['group']); ?></th>
            <th data-sortable="true">Dealer Name</th>
            <th data-sortable="true">Quotation Amount</th>
            <th data-sortable="true">Invoice No.</th>
            <th data-sortable="true">Invoice Amount</th>
            <th data-sortable="true">Invoice Date</th>
            <th data-sortable="true">Vehicle Out Date</th>
            <th data-sortable="true">Status</th>
            <th data-sortable="true">Approver</th>
            <th data-sortable="true">Sender</th>
            <th data-sortable="true">Date of Submission</th>
            <th data-sortable="true">Status Change Date</th>
            <th data-sortable="false">Options</th>
        </tr>
    </thead>
</table>
<?php
$currentdate = getcurrentdate();
$today = date('d-m-Y', $currentdate);
?>
<div class="modal hide" id="editbattery" style="top:41%;">
    <form class="form-horizontal" id="getbattery_edit">
        <fieldset>
            <div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h4 style="color:#0679c0">Transaction Details</h4>
            </div>
            <div class="modal-body">
                <span id="vid_error" style="display: none;">Please Enter Vehicle In Date</span>
                <span id="vod_error" style="display: none;">Please Enter Vehicle Out Date</span>
                <span id="id_error" style="display: none;">Please Enter Invoice Generation Date</span>
                <span id="in_error" style="display: none;">Please Enter Invoice No</span>
                <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>
                <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>
                <?php if ($_SESSION['use_hierarchy'] == '1') { ?>
                    <span id="ofas_error" style="display: none;">Please Enter OFAS Number</span>
                <?php } else { ?>
                    <span id="ofas_error" style="display: none;">Please Enter Transaction Reference Number</span>
                <?php } ?>
                <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
                <div  style="overflow-y:scroll; height:320px;">
                    <fieldset>
                        <table class="table table-bordered table-striped">
                            <th colspan="2">Vehicle Details</th>
                            <tr>
                                <td width="50%">Vehicle No.</td>
                                <td><div id="batt_veh_no"></div></td>
                            </tr>
                            <tr>
                                <td>Branch</td><td><div id="batt_veh_branch"></div></td>
                            </tr>
                            <tr>
                                <td>GPS Odometer Reading</td><td><div id="batt_veh_odometer"></div></td>
                            </tr>
                            <tr>
                                <td>Vehicle Meter reading </td>
                                <td><div id="batt_meter_reading"></div></td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-striped">
                            <th colspan="2">Transaction Details</th>
                            <tr>
                                <td width="50%">Transaction ID</td><td><div id="batt_transid"></div></td>
                            </tr>
                            <tr><td>Category</td><td><div id="batt_category"></div></td></tr>
                            <tr>
                                <td>Dealer name </td>
                                <td><div id="batt_dealer"></div></td>
                            </tr>
                            <tr>
                                <td>Notes</td>
                                <td>
                                    <div id="batt_notes"></div>
                                </td>
                            <tr>
                                <td>Status</td>
                                <td><div id="batt_status"></div></td>
                            </tr>
                            </tr>
                            <tr id="trans_close_date" style="display:none;">
                                <td>Transaction Close Date</td>
                                <td>
                                    <div class="input-prepend" id="trans_close_date_value">
                                </td>
                            </tr>
                            <tr>
                                <td>Vehicle In Date</td>
                                <td><input id="batt_vehicle_in_date" name="batt_vehicle_in_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Vehicle Out Date</td>
                                <td><input id="batt_vehicle_out_date" name="batt_vehicle_out_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-striped">
                            <th colspan="2">Quotation Details</th>
                            <tr>
                                <td width="50%">Quotation Amount (INR)</td>
                                <td>
                                    <div id="batt_amount_quote"></div></td>
                            </tr>
                            <tr>
                                <td>Quotation File</td>
                                <td>
                                    <div class="input-prepend" id="battery_quotefile_view">
                                </td>
                            </tr>
                            <tr>
                                <td>Quotation Submission Date</td><td><div id="batt_submission_date"></div></td>
                            </tr>
                            <tr id="quotation_approval_note">
                                <td>Closed Note</td>
                                <td>
                                    <div id="quotation_approval_note_val"></div>
                                </td>
                            </tr>
                            <tr id="show_tyre_type">
                                <td>Tyre Type</td><td><div id="batt_tyre_type"></div></td>
                            </tr>
                            <tr id="show_parts">
                                <td>Parts Consumed</td><td><div id="batt_parts"></div></td>
                            </tr>
                            <tr id="show_tasks">
                                <td>Tasks Performed</td><td><div id="batt_tasks"></div></td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-striped">
                            <th colspan="2">Invoice Details</th>
                            <tr>
                                <td width="50%">Invoice Generation Date</td>
                                <td><input id="batt_invoice_date" name="batt_invoice_date" type="text" value="<?php echo date('d-m-Y', $currentdate); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Invoice Number</td>
                                <td><input type="text" name="batt_invoice_no" id="batt_invoice_no" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)"></td>
                            </tr>
                            <tr>
                                <td>Invoice Amount (INR)</td>
                                <td><input type="text" name="batt_amount_invoice" id="batt_amount_invoice" placeholder="e.g. 125" maxlength="10" size="8" >
                                    <input type="hidden" name="maintenance_edit_id" id="maintenance_edit_id">
                                    <input type="hidden" name="edit_vehicle_id" id="edit_vehicle_id">
                                    <input type="hidden" name="category_id" id="category_id">
                                </td>
                            </tr>
                            <tr id="invoice_file">
                                <td>Invoice File</td>
                                <td>
                                    <input type="file" title="Browse File" id="batt_file_for_invoice" name="batt_file_for_invoice" class="file-inputs">
                                    <div class="input-prepend" id="battery_invoicefile_view">
                                </td>
                            </tr>
                            <tr id="invoice_file_view" style="display:none;">
                                <td>Invoice File</td>
                                <td>
                                    <div class="input-prepend" id="battery_invoicefile_view">
                                </td>
                            </tr>
                            <tr id="ofasnumber_view" style="display:none;">
                                <?php
                                if ($_SESSION['use_hierarchy'] == '1') {
                                    ?>
                                    <td>OFAS Number</td>
                                    <?php
                                } else {
                                    ?>
                                    <td>Transaction Reference Number</td>
                                    <?php
                                }
                                ?>
                                <td>
                                    <div class="input-prepend" id="ofasnumber_view_value">
                                </td>
                            </tr>
                            <tr id="payment_approval_date" style="display:none;">
                                <td>Payment Approval Date</td>
                                <td>
                                    <div class="input-prepend" id="payment_approval_date_value">
                                </td>
                            </tr>
                            <tr id="payment_approval_note" style="display:none;">
                                <td>Payment Approval Note</td>
                                <td>
                                    <div class="input-prepend" id="payment_approval_note_value">
                                </td>
                            </tr>
                        </table>
                        <div class="control-group">
                            <div class="input-prepend " id="ofasnumberdiv">
                                <?php
                                if ($_SESSION['use_hierarchy'] == '1') {
                                    ?>
                                    <span class="add-on" style="color:#000000">OFAS Number</span>
                                <?php } else { ?>
                                    <span class="add-on" style="color:#000000">Transaction Reference Number</span>
                                <?php } ?>
                                <input type="text" name="ofasnumber" id="ofasnumber" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)">
                            </div>
                        </div>
                </div>
                <br/>
                <div class="control-group">
                    <div class="input-prepend ">
                        <input type="button" id="edit_save_battery" onclick="" value="Send for Final Payment Approval" class="btn btn-success">
                        <input type="button" id="edit_cancel_battery" onclick="" value="Send for Cancellation" class="btn btn-danger">
                        <input type="button" id="edit_close_battery" onclick="" value="Close Transaction" class="btn btn-success">
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<div class="modal hide" id="editaccident" style="top:41%;">
    <form class="form-horizontal" id="getaccident_edit">
        <fieldset>
            <div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h4 style="color:#0679c0">Accident Claim</h4>
            </div>
            <div class="modal-body">
                <?php
                if ($_SESSION['use_hierarchy'] == '1') {
                    ?>
                    <span id="acc_ofas_error" style="display: none;">Please Enter OFAS No.</span>
                <?php } else { ?>
                    <span id="acc_ofas_error" style="display: none;">Please Enter Transaction Reference No.</span>
                <?php } ?>
                <div  style="overflow-y:scroll; height:320px;">
                    <fieldset>
                        <table class="table table-bordered table-striped">
                            <th colspan="2">Vehicle Details</th>
                            <tr>
                                <td width="50%">Vehicle No.</td>
                                <td><div id="acc_veh_no"></div></td>
                            </tr>
                            <tr>
                                <td>Branch</td><td><div id="acc_veh_branch"></div></td>
                            </tr>
                            <tr>
                                <td>GPS Odometer Reading</td><td><div id="acc_veh_odometer"></div></td>
                            </tr>
                            <tr>
                                <td>Driver Name</td><td><div id="acc_drivername"></div></td>
                            </tr>
                            <tr>
                                <td>Driver License Validity From</td><td><div id="acc_driver_lic_from"></div></td>
                            </tr>
                            <tr>
                                <td>Driver License Validity To</td><td><div id="acc_driver_lic_to"></div></td>
                            </tr>
                            <tr>
                                <td>Type of License</td><td><div id="acc_license_type"></div></td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-striped">
                            <th colspan="2">Accident Details</th>
                            <tr>
                                <td width="50%">Transaction ID</td><td><div id="acc_transid"></div></td>
                            </tr>
                            <tr>
                                <td width="50%">Transaction Approval Date</td><td><div id="acc_trans_app_date"></div></td>
                            </tr>
                            <tr>
                                <td>Category</td><td><div id="acc_category"></div></td>
                            </tr>
                            <tr>
                                <td>Accident Date</td><td><div id="acc_date"></div></td>
                            </tr>
                            <tr>
                                <td>Accident Time</td><td><div id="acc_time"></div></td>
                            </tr>
                            <tr>
                                <td>Accident Location</td><td><div id="acc_location"></div></td>
                            </tr>
                            <tr>
                                <td>Third Party Injury / Property Damage</td><td><div id="acc_tpi"></div></td>
                            </tr>
                            <tr>
                                <td>Accident Description</td><td><div id="acc_description"></div></td>
                            </tr>
                            <tr>
                                <td>Name and Location of Workshop</td><td><div id="acc_workshop"></div></td>
                            </tr>
                            <tr>
                                <td>Report Sent to</td><td><div id="acc_report"></div></td>
                            </tr>
                            <tr>
                                <td>Estimated Loss Amount (INR)</td><td><div id="acc_estimated_loss"></div></td>
                            </tr>
                            <tr>
                                <td>Settlement Amount (INR)</td><td><div id="acc_settlement_amount"></div></td>
                            </tr>
                            <tr>
                                <td>Actual Repair Amount (INR)</td><td><div id="acc_repair_amount"></div></td>
                            </tr>
                            <tr>
                                <td>Amount Spent by Mahindra (INR)</td><td><div id="acc_mahindra_amount"></div></td>
                            </tr>
                            <tr>
                                <td>File Links</td><td><div id="accident_files_view"></div></td>
                            </tr>
                            <tr>
                                <td>Approval Note</td><td><div id="acc_approval_note"></div></td>
                            </tr>
                            <tr id="accident_ofasnumber_closed" style="display:none;">
                                <?php if ($_SESSION['use_hierarchy'] == '1') { ?>
                                    <td>OFAS Number</td><td><div id="accident_ofasnumber_value"></div></td>
                                <?php } else { ?>
                                    <td>Transaction Reference Number</td><td><div id="accident_ofasnumber_value"></div></td>
                                <?php } ?>
                            </tr>
                        </table>
                        <div class="control-group">
                            <div class="input-prepend " id="ofasnumberdiv">
                                <?php if ($_SESSION['use_hierarchy'] == '1') { ?>
                                    <span class="add-on" style="color:#000000">OFAS Number</span>
                                <?php } else { ?>
                                    <span class="add-on" style="color:#000000">Transaction Reference Number</span>
                                <?php } ?>
                                <input type="text" name="acc_ofasnumber" id="acc_ofasnumber" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)">
                            </div>
                        </div>
                </div>
                <br/>
                <div class="control-group">
                    <div class="input-prepend ">
                        <input type="button" id="edit_close_accident" onclick="" value="Close Transaction" class="btn btn-success">
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<div class="modal hide" id="mini_modal">
    <form method="POST" id="mini_modal_form">
        <fieldset>
            <div class="modal-header">
                <input type="hidden" id="vehicle_id" value=''>
                <button class="close" data-dismiss="modal">×</button>
                <h4 style="color:#0679c0">Transaction Details</h4>
            </div>
            <div class="modal-body">
            </div>
        </fieldset>
    </form>
</div>
<div style="clear:both;"></div>
<!--Multiple Approval Modal -->
<div class="modal hide" id="approveModal" role="dialog" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Closed Transactions</h4>
            </div>
            <div class="modal-body">
                <span id="NoOfApproval" style="color: #FF0000;"></span>
                <span id="approvalerror_note" style="color: #FF0000;display: none;">Note cannot be empty</span>
                <span id="approvalerror_checkbox" style="color: #FF0000;display: none;">Please Tick A Checkbox</span>
                <span id='closedsuccess_msg' style='color:green;'> <br>Closed transaction successfully.</span>
                <input type="hidden" name ="check_approval" id="check_approval" value=""/>
                <div class="clear"></div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Slip</label>
                    <input type="text" id="ofasnumber1" name="ofasnumber1" value=""  onkeypress="return nospecialchars(event)" maxlength="50" placeholder="e.g. 125867" >
                </div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Cheque No</label>
                    <input type="text" id="chequeno" name="chequeno" value=""  onkeypress="return nospecialchars(event)" maxlength="50" placeholder="e.g.000555" >
                </div>
                <div class="clear"></div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Cheque Amount</label>
                    <input type="text" id="chequeamt" name="chequeamt" value=""  onkeypress="return nospecialchars(event)" maxlength="50" placeholder="e.g. 124567" >
                </div>
                <div style="width:50%; float:left;">
                    <label style="font-weight: bold;">Cheque Date</label>
                    <input type="text" id="chequedate" name="chequedate">
                </div>

                <div class="clear"></div>
                <div style="width:100%; float:left;">
                    <label style="font-weight: bold;">Tds Amount</label>
                    <input type="text" id="tdsamt" name="tdsamt" value=""  onkeypress="return nospecialchars(event)" maxlength="50" placeholder="e.g. 200">
                </div>
                <div class="clear"></div>
                <div style="width:100%; float:left;">
                    <label style="font-weight: bold">Note</label>
                    <textarea name="note" id="note" rows="3" cols="40"></textarea>
                </div>
                <div class="clear"></div>
                <br>
                <div style="width:100%; float:right;">
                    <input type="button" class="btn btn-primary" onclick="push_closed_transaction('1')" value="Close Transactions">
                </div>
            </div>
            <div class="modal-footer">
                <div class="clear"></div>
                <div>
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function tyrelist() {
        var category = jQuery("#category").val();
        if (category == 1) {
            jQuery("#tyre_type").show();
            jQuery("#parts_select").hide();
        }
        else if (category == 2) {
            jQuery("#parts_select").show();
            jQuery("#tyre_type").hide();
        }
        else {
            jQuery("#tyre_type").hide();
            jQuery("#parts_select").hide();
        }
    }
</script>
