<?php
//session_destroy();   // destroy session data in storage
//$cookie_name = "user";
//$cookie_value = "John Doe";
//setcookie($cookie_name, $cookie_value, time() + time()+3600, "/");
//session_regenerate_id(true);
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
<script type="text/javascript">
    function tyrelist() {
        var category = jQuery("#category").val();
        if (category == 1)
        {
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
<form method="POST" name="approval_form" id="approval_form">
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

    $statuses = get_trans_approval_status();
    $statusopt = "";
    if (isset($statuses)) {
        foreach ($statuses as $status) {
            if (isset($_POST['statusid']) && $status->statusid == $_POST['statusid']) {
                $statusopt .= "<option value = '$status->statusid' selected = 'selected'>$status->name</option>";
            } else {
                $statusopt .= "<option value = '$status->statusid'>$status->name</option>";
            }
        }
    }


    $categoryopt = "";
    if (isset($_POST['category']) && $_POST['category'] == '0') {
        $categoryopt .= "<option value = '0' selected = 'selected'>Battery</option>";
    } else {
        $categoryopt .= "<option value = '0'>Battery</option>";
    }

    if (isset($_POST['category']) && $_POST['category'] == '1') {
        $categoryopt .= "<option value = '1' selected = 'selected'>Tyre</option>";
    } else {
        $categoryopt .= "<option value = '1'>Tyre</option>";
    }
    if (isset($_POST['category']) && $_POST['category'] == '2') {
        $categoryopt .= "<option value = '2' selected = 'selected'>Repair</option>";
    } else {
        $categoryopt .= "<option value = '2'>Repair</option>";
    }
    if (isset($_POST['category']) && $_POST['category'] == '3') {
        $categoryopt .= "<option value = '3' selected = 'selected'>Service</option>";
    } else {
        $categoryopt .= "<option value = '3'>Service</option>";
    }
    if (isset($_POST['category']) && $_POST['category'] == '4') {
        $categoryopt .= "<option value = '4' selected = 'selected'>Accident Claim</option>";
    } else {
        $categoryopt .= "<option value = '4'>Accident Claim</option>";
    }
    if (isset($_POST['category']) && $_POST['category'] == '5') {
        $categoryopt .= "<option value = '5' selected = 'selected'>Accessories</option>";
    } else {
        $categoryopt .= "<option value = '5'>Accessories</option>";
    }
//pull dealer 
    $alldealer = getdealers();
    $alldealeropt = "";

    foreach ($alldealer as $alldealers) {
        if (isset($_POST['trans_dealer']) && $alldealers->dealerid == $_POST['trans_dealer']) {
            $alldealeropt .= "<option value = '$alldealers->dealerid' selected = 'selected'>$alldealers->dealername</option>";
        } else {
            $alldealeropt .= "<option value = '$alldealers->dealerid'>$alldealers->dealername</option>";
        }
    }
    ?>
    <table>
        <tr>
            <td>Transaction ID</td>
            <td>
                <input id="transactionid" style="width: 150px;" name="transactionid" type="text" value="<?php
                if (isset($_POST['transactionid'])) {
                    echo $_POST['transactionid'];
                }
                ?>"/>
            </td>        
            <td>
                <select id="vehicleid" name="vehicleid" style="width: 150px;">
                    <option value="">Select Vehicle</option>
                    <?php echo $devicesopt; ?>
                </select>
            </td>
            <td>
                <select id="category" name="category" onchange="tyrelist();" style="width: 150px;">
                    <option value="-1">Select Category</option>
                    <?php echo $categoryopt; ?>
                </select>
            </td>
            <td>
                <select id="trans_dealer" name="trans_dealer" style="width: 150px;">
                    <option value="-1">Select Dealer</option>
                    <?php echo $alldealeropt; ?>
                </select>
            </td>
            <td>
                <select id="tyre_type" name="tyre_type" <?php if (isset($_POST['category']) && $_POST['category'] != '1') { ?> style="display: none;" <?php } ?>>
                    <option value="0" <?php
                    if ((isset($_POST['tyre_type']) && $_POST['tyre_type']) == '0') {
                        echo "selected";
                    }
                    ?>>Select type</option>
                    <option value="1" <?php
                    if ((isset($_POST['tyre_type']) && $_POST['tyre_type']) == '1') {
                        echo "selected";
                    }
                    ?>>Right front</option>
                    <option value="2" <?php
                    if ((isset($_POST['tyre_type']) && $_POST['tyre_type'] == '2')) {
                        echo "selected";
                    }
                    ?> >Right Back</option>
                    <option value="3" <?php
                    if ((isset($_POST['tyre_type']) && $_POST['tyre_type'] == '3')) {
                        echo "selected";
                    }
                    ?>>Left front</option>
                    <option value="4" <?php
                    if ((isset($_POST['tyre_type']) && $_POST['tyre_type'] == '4')) {
                        echo "selected";
                    }
                    ?>>Left Back</option>
                    <option value="5" <?php
                    if ((isset($_POST['tyre_type']) && $_POST['tyre_type'] == '5')) {
                        echo "selected";
                    }
                    ?>>Stepney</option>
                </select>


                <select id="parts_select" style="width: 150px;" name="parts_select" <?php if (isset($_POST['category']) && $_POST['category'] != '2') { ?> style="display: none;" <?php } ?>>
                    <option value="-1">Select Parts</option>
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
                <select id="statusid" name="statusid">
                    <option value="">Select Status</option>
                    <?php echo $statusopt; ?>
                </select>
            </td>
            <td><input type="button" class="g-button g-button-submit" value="Search" name="Filter"id ="filter_approval" onclick="getFilteredApproval();"></td>
        </tr>
        </tbody>
    </table>
</form>

<style type="text/css">
    #approvetransaction_filter{
        margin-right: 5px;
        #pageloaddiv {
            position: fixed;
            left: 0px;
            top: -80px;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background: url('../../images/progressbar.gif') no-repeat center center;
        }
    }
</style>
<div class="container-fluid">
    <span id="approvalsuccess_msg" style="color:#007345;display: none;">Done Successfully</span>
    <div style="text-align: right;">
        <!--<a id="btnApprove" href="#approveModal" data-toggle="modal" class="btn btn-info code-dialog">Approve/Reject</a>-->
        <button id="btnApprove" type="button" class="btn btn-info btn-lg" data-toggle="modal" href="#approveModal">Approve/Reject</button>
    </div>
    <table class='display table table-bordered table-disable-hover select' id="approvetransaction" style="width:90%">
        <thead>
            <tr class="filterrow">
                <td><input type="hidden" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='input-filter' name='' style="width:90%;" /></td>
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
                <th data-sortable="false"><input type="checkbox" id="check_all"></th>           
                <th data-sortable="true">Transaction ID</th>      
                <th data-sortable="true">Vehicle No</th>             
                <th data-sortable="true">Category</th>   
                <th data-sortable="true"><?php echo($_SESSION['group']); ?></th>                
                <th data-sortable="true">Dealer Name</th>
                <th data-sortable="true">Quotation Amount</th>
                <th data-sortable="true">Invoice No.</th>
                <th data-sortable="true">Invoice Amount</th>
                <th data-sortable="true">Status</th>
                <th data-sortable="true">Approver</th>
                <th data-sortable="true">Sender</th>        
                <th data-sortable="true">Date of Submission</th>                
                <th data-sortable="true">Status Change Date</th>                        
                <th data-sortable="false">Approve / Reject</th>        
            </tr>
        </thead>     
    </table>
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
<div class="container">
    <!--Multiple Approval Modal -->
    <div class="modal fade" id="approveModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Approve Transactions</h4>
                </div>
                <div class="modal-body">
                    <span id="NoOfApproval" style="color: #FF0000;"></span>
                    <span id="approvalerror_note" style="color: #FF0000;display: none;">Note cannot be empty</span>
                    <span id="approvalerror_checkbox" style="color: #FF0000;display: none;">Please Tick A Checkbox</span>
                    <label style="font-weight: bold">Note</label>
                    <textarea name="note" id="note" rows="4" cols="50"></textarea>
                    <input type="hidden" name ="check_approval" id="check_approval" value=""/>
                </div>
                <div class="modal-footer">
                    <div style="text-align:center;">  
                        <input type="button" class='btn btn-primary' onclick="push_checkedapproval('1')" value="Approve"/>
                        <input type="button" class='btn btn-danger' onclick="push_checkedapproval('2')" value="Reject"/>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
