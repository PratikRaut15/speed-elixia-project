<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
//include_once("session.php");

include_once "../../lib/system/utilities.php";
include "loginorelse.php";
include_once "../../constants/constants.php";
include_once "db.php";
include_once "../../lib/system/DatabaseManager.php";
include_once "../../lib/system/Date.php";
include_once "../../lib/system/Sanitise.php";
include_once "../../lib/components/gui/objectdatagrid.php";
include_once "../../lib/model/VODevices.php";
include_once "../user/new_alerts_func.php";
include_once "route_modifycust.php";
include_once "../../lib/bo/TeamManager.php";

$_scripts[] = "../../scripts/jquery.min.js";
$_scripts[] = "../../scripts/team/modifycustomer.js";
$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";

class customers {
}

class VOLogin {
}

function PrepareSP($sp_name, $sp_params) {
    return "call " . $sp_name . "(" . $sp_params . ");";
}

$customerid = GetSafeValueString(isset($_GET["cid"]) ? $_GET["cid"] : $_POST["customerid"], "long");
$teamid = GetLoggedInUserId();
$db = new DatabaseManager();
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$todaysdate = date("Y-m-d");

$timezones = Array();
$timezones = $tm->get_timezone();

$industrytypes = Array();
$industrytypes = $tm->get_industry();

$saleslist = Array();
$saleslist = $tm->get_sales_person();

$customers = array();
$customers = getCustomerDetails($customerid);
// print_r($customers); exit;

include "header.php";
?>
<link rel="stylesheet" href="../../css/modifycustomer.css">
    <div class="panel">
        <?php
if (IsHead() || IsAdmin()) {
    ?>
        <!-- ************ MODIFY CUSTOMER ************ -->
        <div class="paneltitle" align="center">Update Customer - Credentials</div>
        <div class="panelcontents">
            <form method="post" name="updatecustomerform" id="updatecustomerform" enctype="multipart/form-data">
                <input type="hidden" name="customerid" value="<?php echo ($customerid); ?>"/>
                <table width="100%">
                    <tr>
                        <td>Real Name <span style="color:red;">*</span>
                        </td>
                        <td><input name="customername" id='customername' type="text" value="<?php echo ($customers->custname); ?>" /> </td>
                        <td>Company <span style="color:red;">*</span></td>
                        <td><input name="customercompany" id='customercompany' type="text"  value="<?php echo ($customers->custcompany); ?>" />
                        </td>
                    </tr>
                </table>
                <div class="paneltitle" align="center">Modules</div>
                    <table width="60%" align="center">
                        <tr>
                            <td>SMS Package: <b><?php echo ($customers->custsms); ?></b> + </td>
                            <td><input name="customersms" type="text" /></td>
                        </tr>
                        <tr>
                            <td>Telephonic Alerts Package: <b><?php echo ($customers->custtelephonicalert); ?></b> + </td>
                            <td><input name="customertelephonicalert" type="text" /></td>
                        </tr>
                        <tr>
                            <td>Subscription Invoice Status</td>
                            <td><input name="invoice_status" id="invoice_status" class="invoice_status" type="checkbox" <?php if ($customers->invoiceHold) {?> checked <?php }?> /></td>
                        </tr>
                        <tr>
                            <td>Tracking Module</td>
                            <td><input name="ctracking" id="ctracking" type="checkbox" <?php if ($customers->ctraking) {?> checked <?php }?> onclick="show_features();"/></td>
                        </tr>
                        <tr id="load_sensor" <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td>Load Sensor</td>
                            <td><input name="cloading" id="cloading" type="checkbox" <?php if ($customers->cloading) {?> checked <?php }?>/><br/>(Note: Point all the devices using this feature to 9990)</td>
                        </tr>
                        <tr id="reverse_geo" <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td>Reverse Geo-Location</td>
                            <td><input name="cgeolocation" id="cgeolocation" type="checkbox" <?php if ($customers->cgeolocation) {?> checked <?php }?>/><br/>(Note: Location will be pulled from Google Maps API)</td>
                        </tr>
                        <tr id="ac_tr"  <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="cac" id="cac" type="checkbox" <?php if ($customers->ac_sensor) {?> checked <?php }?>/>Use AC Sensor
                            </td>
                        </tr>
                        <tr id="genset_tr"  <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="cgenset" id="cgenset" type="checkbox" <?php if ($customers->genset_sensor == '1') {?> checked <?php }?>/>Use Genset Sensor</td>
                        </tr>
                        <tr id="fuel_tr"  <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="cfuel" id="cfuel" type="checkbox" <?php if ($customers->fuel_sensor == '1') {?> checked <?php }?>/>Use Fuel Sensor</td>
                        </tr>
                        <tr id="door_tr"  <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="cdoor" id="cdoor" type="checkbox" <?php if ($customers->door_sensor == 1) {?> checked <?php }?>/>Use Door Sensor</td>
                        </tr>

                        <tr id="portable_tr" <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td>Portable Module</td>
                            <td><input name="cportable" id="cportable" type="checkbox" <?php if ($customers->cportable == 1) {?> checked <?php }?>/></td>
                        </tr>
                        <tr id="temp_tr" <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td>Temperature Sensors</td>
                            <td><input type="radio" name="ctempsensor" value="0" <?php if ($customers->ctempsensor == 0) {?> checked <?php }?> >0 <input type="radio" name="ctempsensor" value="1" <?php if ($customers->ctempsensor == 1) {?> checked <?php }?>>1 <input type="radio" name="ctempsensor" value="2" <?php if ($customers->ctempsensor == 2) {?> checked <?php }?>>2
                            <input type="radio" name="ctempsensor" value="3" <?php if ($customers->ctempsensor == 3) {?> checked <?php }?>>3 <input type="radio" name="ctempsensor" value="4" <?php if ($customers->ctempsensor == 4) {?> checked <?php }?>>4</td>
                        </tr>
                        <tr id="advanced_tr" <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td>Advanced Alerts</td>
                            <td><input type="radio" name="advancedAlerts" value="0" <?php
if ($customers->advanced_alert == 0) {
        echo "checked";
    }
    ?> >No <input type="radio" name="advancedAlerts" value="1" <?php
if ($customers->advanced_alert == 1) {
        echo "checked";
    }
    ?>>Yes</td>
                        </tr>

                        <tr id="panic_tr" <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="cpanic" id="cpanic" type="checkbox" <?php
if ($customers->panic == 1) {
        echo "checked";
    }
    ?>/>Use Panic</td>
                        </tr>
                        <tr id="buzzer_tr" <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="cbuzzer" id="cbuzzer" type="checkbox" <?php
if ($customers->buzzer == 1) {
        echo "checked";
    }
    ?>/>Use Buzzer</td>
                        </tr>
                        <tr id="immobilizer_tr" <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="cimmobilizer" id="cimmobilizer" type="checkbox" <?php
if ($customers->immobilizer == 1) {
        echo "checked";
    }
    ?>/>Use Immobilizer</td>
                        </tr>
                        <tr id="mobility_tr" <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="cimobility" id="cimobility" type="checkbox" <?php
if ($customers->mobility == 1) {
        echo "checked";
    }
    ?>/>Use Mobility</td>
                        </tr>

                        <tr id="salesengage_tr" <?php if (!$customers->ctraking) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="csalesengage" id="csalesengage" type="checkbox" <?php
if ($customers->salesengage == 1) {
        echo "checked";
    }
    ?>/>Use Sales Engage</td>
                        </tr>

                        <tr>
                            <td>Maintenance Module</td>
                            <td><input name="cmaintenance" id="cmaintenance" type="checkbox" <?php if ($customers->cmaintenance == 1) {?> checked <?php }?>  onclick="show_heirarchy();"/></td>
                        </tr>
                        <tr id="heir_tr" <?php if (!$customers->cmaintenance) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="cheirarchy" id="cheirarchy" type="checkbox" <?php if ($cheirarchy == 1) {?> checked <?php }?>/>Use Hierarchy</td>
                        </tr>

                        <tr>
                            <td>Delivery Module</td>
                            <td><input name="cdelivery" id="cdelivery" type="checkbox" <?php if ($customers->delivery == 1) {?> checked <?php }?> onclick="show_routing();"/></td>
                        </tr>
                        <tr id="routing_tr" <?php if (!$customers->delivery) {?> style="display:none" <?php }?>>
                            <td></td>
                            <td><input name="crouting" id="crouting" type="checkbox" <?php if ($customers->routing == 1) {?> checked <?php }?>/>Use Routing</td>
                        </tr>
                        <tr>
                            <td>ERP Module</td>
                            <td><input name="cerp" id="cerp" type="checkbox" <?php if ($customers->erp == 1) {?> checked <?php }?>/></td>
                            <td>Warehouse</td>
                            <td><input name="cwarehouse" id="cwarehouse" type="checkbox" <?php if ($customers->warehouse == 1) {?> checked <?php }?>/></td>
                        </tr>
                        <tr>
                            <td>Trace</td>
                            <td><input name="ctrace" id="ctrace" type="checkbox" <?php if ($customers->trace == 1) {?> checked <?php }?>/></td>
                            <td>Control Tower</td>
                            <td><input name="cct" id="cct" type="checkbox" <?php if ($customers->controlTower == 1) {?> checked <?php }?>/></td>
                        </tr>
                        <tr>
                            <td>Books</td>
                            <td><input name="cbooks" id="cbooks" type="checkbox" <?php if ($customers->books == 1) {?> checked <?php }?>/></td>
                            <td>CRM</td>
                            <td><input name="ccrm" id="ccrm" type="checkbox" <?php if ($customers->erp == 1) {?> checked <?php }?>/></td>
                        </tr>
                        <tr>
                            <td>RADAR</td>
                            <td><input name="cradar" id="cradar" type="checkbox" <?php if ($customers->trace == 1) {?> checked <?php }?>/></td>
                        </tr>

                        <tr>
                            <td>Unit Price</td>
                            <td><input type="text" value="<?php echo $customers->unitprice; ?>" name="cunitprice" id="cunitprice"/></td>
                        </tr>
                        <tr>
                            <td>Unit Monthly Subscription Price</td>
                            <td><input type="text" value="<?php echo $customers->unit_msp; ?>" name="cmsp" id="cmsp"/></td>
                        </tr>
                        <tr>
                            <td>Warehouse Price</td>
                            <td><input type="text" value="<?php echo $customers->warehouse_price; ?>" name="cwarehouse_price" id="cwarehouse_price"/></td>
                        </tr>
                        <tr>
                            <td>Subscription Charges for Warehouse</td>
                            <td><input type="text" value="<?php echo $customers->warehouse_msp; ?>" name="wmsp" id="wmsp"/></td>
                        </tr>
                        
                        <tr>
                            <td>Subscription Renewal Period</td>
                            <td><input type="radio" name="duration" id="duration" value="1" <?php if ($customers->cust_renewal == 1) {?> checked <?php }?>/>1 Month
                            <input type="radio" name="duration" id="duration" value="3" <?php if ($customers->cust_renewal == 3) {?> checked <?php }?>/>3 Months
                            <input type="radio" name="duration" id="duration" value="6" <?php if ($customers->cust_renewal == 6) {?> checked <?php }?>/>6 Months
                            <input type="radio" name="duration" id="duration" value="12"<?php if ($customers->cust_renewal == 12) {?> checked <?php }?>/>1 Year
                            <input type="radio" name="duration" id="duration" value="-3"<?php if ($customers->cust_renewal == -3) {?> checked <?php }?>/>Lease
                            <input type="radio" name="duration" id="duration" value="-1"<?php if ($customers->cust_renewal == -1) {?> checked <?php }?>/>Demo
                            <input type="radio" name="duration" id="duration" value="-2"<?php if ($customers->cust_renewal == -2) {?> checked <?php }?>/>Closed</td>
                        </tr>

                        <tr>
                            <td>Extended usage</td>
                            <td><input type="text" value="<?php echo $customers->extended_usage; ?>" name="extended_usage" id="extended_usage"/></td>
                        </tr>
                        <tr>
                            <td>Generate Invoice for: </td>
                            <td>
                                <select name="invoice_month" id="invoice_month">
                                 <?php if($customers->invoice_month=='next') { ?>
                                  <option value="1">Next</option>
                                  <option value="2">Previous</option>
                                  <option value="3">Same</option>
                                 <?php } else if($customers->invoice_month=='previous') { ?>
                                  <option value="2">Previous</option>
                                  <option value="3">Same</option>
                                  <option value="1">Next</option>
                                 <?php } else if($customers->invoice_month=='same') { ?>
                                  <option value="3">Same</option>
                                  <option value="2">Previous</option>
                                  <option value="1">Next</option>
                                 <?php } else { ?>
                                  <option value="o">Select Invoice Month</option>
                                  <option value="1">Next</option>
                                  <option value="2">Previous</option>
                                  <option value="3">Same</option>
                                  <?php } ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tr_lease" style="display: none;">
                            <td>Lease Period</td>
                            <td>
                            <input type="radio" name="leaseduration" id="leaseduration" value="3" <?php if ($customers->lease_period == 3) {?> checked <?php }?>/>3 Months
                            <input type="radio" name="leaseduration" id="leaseduration" value="6" <?php if ($customers->lease_period == 6) {?> checked <?php }?>/>6 Months
                            <input type="radio" name="leaseduration" id="leaseduration" value="12"<?php if ($customers->lease_period == 12) {?> checked <?php }?>/>1 Year
                            <input type="radio" name="leaseduration" id="leaseduration" value="-1"<?php if ($customers->lease_period != 12 && $customers->lease_period != 6 && $customers->lease_period != 3 && $customers->lease_period != 0) {?> checked <?php }?> onclick="showLeaseCustom();"/>Custom
                            <input type="number" name="customlease" id="customlease" size="3" style="display: none;width:30px;" value="<?php
if ($customers->lease_period == 12 || $customers->lease_period == 6 || $customers->lease_period == 3) {
        echo "0";
    } else {
        echo $customers->lease_period;
    }
    ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Current Month Invoice</td>
                            <td><input type="checkbox" name="custom_inv_gen" id="custom_inv_gen" <?php if ($customers->currentMonthInv) {?> checked <?php }?>/></td>
                        </tr>
                        <tr class="tr_lease" style="display: none;">
                            <td>Lease Price</td>
                            <td><input type="text" name="leaseprice" id="leaseprice" value="<?php echo $customers->lease_price; ?>"/></td>
                        </tr>
                        <tr>
                            <td>Commercial Details</td>
                            <td><textarea name="comdetails" style='width:300px;' id ='comdetails'><?php echo $customers->comdetails; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Timezone </td>
                            <td>
                            <select name="timezone" id="timezone">
                            <option value="0">Select Timezone</option>
                            <?php
if (isset($timezones)) {
        foreach ($timezones as $time) {
            ?>
                            <option value="<?php echo $time->tid; ?>" <?php
if ($time->tid == $customers->t_zone) {
                echo 'selected';
            }
            ?> ><?php echo $time->zone; ?></option>
                            <?php
}
    }
    ?>
                            </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Industry type </td>
                            <td>
                            <select name="industrytype" id="industrytype">
                            <option value="0">Select Industry</option>
                            <?php
if (isset($industrytypes)) {
        foreach ($industrytypes as $ind) {
            ?>
                            <option value="<?php echo $ind->industryid; ?>" <?php
if ($ind->industryid == $customers->industryid) {
                echo 'selected';
            }
            ?> ><?php echo $ind->industry_type; ?></option>
                            <?php
}
    }
    ?>
                            </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Sales Manager </td>
                            <td>
                            <select name="sales" id="sales">
                            <option value="0">Select Sales Person</option>
                            <?php
if (isset($saleslist)) {
        foreach ($saleslist as $sales) {
            ?>
                            <option value="<?php echo $sales->teamid; ?>" <?php
if ($sales->teamid == $customers->salesid) {
                echo 'selected';
            }
            ?> ><?php echo $sales->name; ?></option>
                            <?php
}
    }
    ?>
                            </select>
                            </td>
                        </tr>
                    </table>
                <div align="center"><input type="button" name="update_customer" id="update_customer" value="Save Changes" onclick="validform_update_cust();"/></div>
            </form>
            <div align="center" class="titleClass">ITEM MASTER DETAILS</div>
            <form name="item_master_details" id="item_master_details" class="item_master_class">
                <div name="temp_item_master_div" id="temp_item_master_div"></div>
                <img src="../../images/loader_elixia.gif" style="display: none;margin:0 45%;" id="loader_show">
                <input type="button" name="submit_item_master" id="submit_item_master" value="Submit" onclick="updateItemMaster();" style="margin: 0 45%;">
            </form>
        </div>
        <?php
}
?>
    <?php
class testing {
}

if (IsHead() || IsAdmin() || IsCRM()) {
    $db = new DatabaseManager();
    $customerno = $_GET['cid'];
    $cpdatas = Array();
    $cpdatas = $tm->get_contactTypeMaster();

    /* Add Account Customer */
    ?>
        <!-- -------------Form Add Additional Details----------------------- -->
        <div class="panel">
            <div class="paneltitle" align="center">Subscription Invoice Status</div>
                <div class="panelcontents">
                    <form name="invoice_holdForm" id="invoice_holdForm">
                        <label id="current_status" name="current_status"></label>
                        <label for="status" href="#" title="Status Information" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Set Hold to stop invoice generation for this customer." style="float: right;"><i class="material-icons">info</i></label>
                        <div class="switch-field" >
                            <input type="radio" id="switch_left" name="statusType" value="0" />
                            <label for="switch_left">Free</label>
                            <input type="radio" id="switch_right" name="statusType" value="1" />
                            <label for="switch_right">Hold</label>
                        </div>
                        <input type="hidden" name="customerno" id="customerno" value="<?php echo $customerno; ?>">
                        <input type="button" name="submit_status" id="submit_status" value="Submit" onclick="updateSubsInvoiceStatus();" style="margin: 0 45%;">
                    </form>
                </div>
            <div class="paneltitle" align="center">Add Additional Details Of Customer No. <?php echo $customerno ?></div>
            <div class="panelcontents">
                <span id="err_type" style="display: none;color: #FF0000"> Please Select Type Of Person</span>
                <span id="err_personname" style=" display: none;color: #FF0000">Enter Person Name </span>
                <span id="err_email" style="display: none;color: #FF0000">Enter Primary Email</span>
                <span id="err_phone" style="display: none;color: #FF0000">Enter Primary Phone No.</span>
               <form method="post" name="detailsform" id="detailsform" action="contactdetails_ajax.php" onsubmit="return validate_contactdetails();">
                    <table>
                        <tr>
                            <td><input type="hidden" name="cid" id="cid" value="<?php echo $customerno; ?>"></td>
                        </tr>
                        <tr><td>Type</td>
                            <td>
                                <select id="type" name="type">
                                    <option value='0'>Select Person Type</option>
                                    <?php foreach ($cpdatas as $datas) {
        ?>
                                        <option value='<?php echo $datas->typeid; ?>'>
                                            <?php echo $datas->persontype; ?>
                                        </option>
                                    <?php }?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Person Name</td>
                            <td><input type="text" name="person" id="person"></td>
                        </tr>
                        <tr>
                            <td>Primary Email</td>
                            <td><input type="text" name="email1" id="email1"></td>
                        </tr>
                        <tr>
                            <td>Secondary Email</td>
                            <td><input type="text" name="email2" id="email2"></td>
                        </tr>
                        <tr>
                            <td>Primary Phone No.</td>
                            <td><input type="text" name="phone1" id="phone1"></td>
                        </tr>
                        <tr>
                            <td>Secondary Phone No.</td>
                            <td><input type="text" name="phone2" id="phone2"></td>
                        </tr>
                        <tr>
                            <td><input type="submit" class="btn btn-primary"name="add_details" id="add_details" value="Add Details"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <br/>
        <div class="panel">
            <div class="paneltitle" align="center">View/Edit Additional Details</div>
            <div class="panelcontents">
                <table border="1" width="100%">
                    <tr>
                        <th>Sr No.</th>
                        <th>Person Name</th>
                        <th>Person Type</th>
                        <th>Primary Email</th>
                        <th>Secondary Email</th>
                        <th>Primary Phone No.</th>
                        <th>Secondary Phone No.</th>
                        <th>Edit</th>
                        <th>Delete</th>

                    </tr>
                    <tbody id="demo">

                    </tbody>
                </table>
            </div>
        </div>
        <br/>
        <div class="panel">
            <div class="paneltitle" align="center">Allot Ledger</div>
            <div class="panelcontents">
                <form method="post" name="form_ledgermap" id="form_ledgermap">
                    <table>
                        <tr>
                            <td id="msg_mapledger" style="display: none;color: #FF0000;text-align: center"></td>
                        </tr>
                        <tr>
                            <td>Ledger Name</td>
                            <td><input type="text" name="ledgername" id="ledgername" size="50"/></td>
                        <input type="hidden" name="ledgerid" id="ledgerid"/>
                        <input type="hidden" name="cust_ledger" id="cust_ledger" value="<?php echo $customerno; ?>"/>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    </table>
                    <input type="button" id="map_ledger" name="map_ledger" class="btn btn-default" value="Allot Ledger" onclick="Mapledger();">
                </form>
            </div>
        </div>
        <br/>
        <div class="panel">
            <div class="paneltitle" align="center">List Of Ledger For Customer No <?php echo $customerid; ?></div>
            <div class="panelcontents">
                <table border="1" width="100%">
                    <tr>
                        <th>Sr No.</th>
                        <th>Ledger Id</th>
                        <th>Ledger Name</th>
                        <th>Address Line1</th>
                        <th>Address Line2</th>
                        <th>Address Line3</th>
                        <th>PAN No.</th>
                        <th>CST No.</th>
                        <th>VAT No.</th>
                        <th>ST No.</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Delete</th>
                        <th>Generate Invoice</th>

                    </tr>
                    <tbody id="demo_mappedledger" style="text-align: center;">

                    </tbody>
                </table>
            </div>
        </div>
        <br/>
        <div class="panel">
            <div class="paneltitle" align="center">Map Invoice Vehicles</div>
            <div class="panelcontents">
                <a href="ledger_mapvehicle.php?cno=<?php echo $customerno; ?>"><button class="btn btn-info">Add/View Map Vehicle</button></a>
            </div>
        </div>
        <br/>
        <!-- ------------------ add Po details--------------------------------- -->
        <?php
if (IsSales() || IsHead()) {
        ?>
            <div class="panel">
                <div class="paneltitle" align="center">Add PO Details</div>
                <div class="panelcontents">
                    <span id="error_cust" style="display: none;color: #FF0000;text-align: center">Please Select Customer</span>
                    <span id="add_po_succ" style="display: none;color: #00493a;text-align: center">Add Successfully</span>
                    <span id="fail_add_po" style="display: none;color: #FF0000;text-align: center">Some Error Has Occurred Try Again</span>
                    <form method="post" name="add_po" id="add_po">
                        <table>
                            <tr><td>Customer No</td>
                                <td>
                                    <input type ="text" name ="cust_grp" id="cust_grp" value="<?php echo $customerid; ?>-<?php echo $customers->custcompany; ?>" size="30" readonly/>
                                </td>
                            <input type ="hidden" name ="cid" id="cid" value="<?php echo $cno; ?>"/>
                            </tr>
                            <tr>
                                <td>PO Number</td>
                                <td>
                                    <input type ="text" name ="po_no" id="po_no">
                                </td>
                            </tr>
                            <tr>
                                <td>PO Date</td>
                                <td>
                                    <input type ="text" name ="podate" id="podate"/><button id="trigger10">...</button>
                                </td>
                            </tr>
                            <tr>
                                <td>PO Expiry</td>
                                <td>
                                    <input type ="text" name ="poexp" id="poexp"/><button id="trigger11">...</button>
                                </td>
                            </tr>
                            <tr>
                                <td>PO Amount</td>
                                <td>
                                    <input type ="text" name ="poamt" id="poamt"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>
                                    <textarea name ="podesc" id="podesc" cols="30" rows="5"></textarea>
                                </td>
                            </tr>
                        </table>
                        <input type="button" id="add_po" name="add_po" class="btn btn-default" value="Add PO Details" onclick="addAccountpo();">
                    </form>
                </div>
            </div>
            <br>
            <div class="panel">
                <div class="paneltitle" align="center">View PO Details</div>
                <div class="panelcontents">
                    <table border="1" width="100%">
                        <tr>
                            <th>Sr No.</th>
                            <th>PO Number</th>
                            <th>PO Date</th>
                            <th>PO Expiry</th>
                            <th>PO Amount</th>
                            <th>Description</th>
                            <th>Edit</th>
                            <th>Delete</th>

                        </tr>
                        <tbody id="demo_po" style="text-align: center;">

                        </tbody>
                    </table>
                </div>
            </div>
            <br/>

            <?php
}
    ?>
        <?php
}
//Datagtrid
$devices = array();
$isGenset = 0;
$sms_lock_url = "";
$devices = getDevices($customerid);

if (isset($devices)) {
    foreach ($devices as $thisdevice) {
        $db = new DatabaseManager();
        $SQL = sprintf("SELECT * FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE simcard.id = %d", $thisdevice->simcardid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $thisdevice->simcardno = $row["simcardno"] . " [ " . $row["status"] . " ]";
            }
        } else {
            $thisdevice->simcardno = "Demapped";
        }
    }
}
?>
    <hr>
    <div class="panel">
       <div class="paneltitle" align="center">Device List</div>

           <div id="DevicesGrid" class="ag-theme-bootstrap" style="height:700px;width:100%;margin:0 auto;border: 1px solid gray">
   </div>
   </div>

    <br/>

    <div class="panel">
        <div class="paneltitle" align="center">Notes</div>
        <div class="panelcontents">

            <form method="post"  enctype="multipart/form-data" id="cust_notes" name="cust_notes">
                <input type="hidden" name="customerid" value="<?php echo ($customerid); ?>"/>
                <table width="40%">
                    <tr>
                        <td>Notes</td><td><textarea name="customer_notes" style='width:300px;' id ='customer_notes'></textarea></td>
                    </tr>
                    <tr>
                        <td>Send Email to</td>
                        <td>
                            <input  type="text" name="tags" id="tags" size="20" value="" autocomplete="off" placeholder="Enter email id"  />
                            <input  type="hidden" name="tags1"  id="tags1">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div id="listvehicle1" ></div>
                        </td>
                    </tr>
                </table>
                <input type="button" name="add_notes" value="Add Notes" onclick="add_cust_notes();" />
            </form>
        </div>
    </div>
    <?php
$details = array();
$details = getCustomerNotes($customerid);
?>
    <div class="panel">
        <div class="panelcontents">
            <?php
$dg = new objectdatagrid($details);
$dg->AddColumn("Sr. No", "srno");
$dg->AddColumn("Notes", "details");
$dg->AddColumn("Timestamp", "entrytime");
$dg->SetNoDataMessage("No Notes");
$dg->AddIdColumn("cdid");
$dg->Render();
?>
        </div>
    </div>

    <br/>
    <!--Add user by default its role - Administartor  -->
    <div class="panel">
        <div class="paneltitle" align="center">Add User</div>
        <div class="panelcontents">
            <form method="post" name="adduserform" id="adduserform">
                <div style=" width:100%;">
                    <table border="0">
                        <tr>
                            <td width="50%"><b>Name <span style="color: red;">*</span></b> </td> <td  width="50%"><input type="text" name="name" id="name" placeholder="Name" ></td>
                        </tr>
                        <tr>
                            <td width="50%"><b>User name <span style="color: red;">*</span></b></td> <td  width="50%"><input type="text" name="username" id="username" placeholder="Username"></td>
                        </tr>
                        <tr>
                            <td width="50%"><b>Email <span style="color: red;">*</span></b> </td> <td  width="50%"><input type="text" name="email" id="email" placeholder="Email id"></td>
                        </tr>
                        <tr>
                            <td width="50%"><b>Password <span style="color: red;">*</span></b></td> <td  width="50%"><input type="password" name="password" id="password"></td>
                        </tr>
                        <tr>
                            <td width="50%"><b>Phone No</b></td> <td  width="50%"> <b>+91</b><input type="text" name="phoneno" id="phoneno" placeholder="9870288657"></td>
                        </tr>
                        <tr>
                            <td width="50%"><b>Role</b></td>
                            <td width="50%">
                                <select name="role" id="role">
                                    <option id="Administrator" rel="5" value="Administrator">Administrator</option>
                                </select>
                                <input type="hidden" name="roleid" id="roleid" value="5">
                                <input type="hidden" name="customerno" id="customerno" value="<?php echo $customerid; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="100%"><input style="text-align: center;" type="button" name="adduser" id="adduser" value="Create User" onclick="validform_adduser();"></td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
    <?php
$db = new DatabaseManager();
$SQL = sprintf("select * from " . DB_PARENT . ".user WHERE customerno = %d AND isdeleted=0 order by userid desc", $customerid);
$db->executeQuery($SQL);
$details = array();
if ($db->get_rowCount() > 0) {
    $x = 1;
    $sms_lock_url = "";
    while ($row = $db->get_nextRow()) {
        $userdetails = new stdClass();
        $userdetails->srno = $x;
        $userdetails->userid = $row["userid"];
        $userdetails->realname = $row["realname"];
        $userdetails->username = $row["username"];
        $userdetails->email = $row["email"];
        $userdetails->phone = $row["phone"];
        $userdetails->role = $row["role"];
        $userdetails->userkey = $row["userkey"];
        $sms_lock = $row["sms_lock"];
        $userid = $row["userid"];
        $teamid = $_SESSION['sessionteamid'];
        if ($sms_lock == 1) {
            $sms_lock_url = "<a href='javascript:void(0);' alt='SMS Lock ' title='SMS Lock' onclick='lockstatus(1," . $userid . "," . $teamid . "," . $customerid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/lock_green.png'/></a>";
        } else {
            $sms_lock_url = "<a href='javascript:void(0);' alt='SMS Unlock ' title='SMS Unlock'  onclick='lockstatus(0," . $userid . "," . $teamid . "," . $customerid . ");'><img style='text-align:center; width:30px; height:30px;' src='../../images/lock_open_green.png'/></a>";
        }
        $userdetails->smslockurl = $sms_lock_url;
        $details[] = $userdetails;
        $x++;
    }
}
?>
    <div class="panel">
        <div class="panelcontents">
            <?php
$dg = new objectdatagrid($details);
$dg->AddColumn("Sr. No", "srno");
$dg->AddColumn("Userid", "userid");
$dg->AddColumn("realname", "realname");
$dg->AddColumn("Username", "username");
$dg->AddColumn("Email", "email");
$dg->AddColumn("Phone", "phone");
$dg->AddColumn("Role", "role");
$dg->AddColumn("UserKey", "userkey");
$dg->AddColumn("SMS Lock Status", "smslockurl");
$dg->SetNoDataMessage("No Users");
$dg->AddAction("View/Edit", "../../images/edit.png", "edituser.php?userid=%d");
$dg->AddIdColumn("userid");
$dg->Render();
?>
        </div>
    </div>

      <?php
include 'login_history_grid.php';
?>
    <br/>

    <?php
include "footer.php";
?>
<script>
    var customerid = <?php echo $customerid; ?>;
    <?php include_once '../../scripts/speedUtils.js';?>
</script>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var details = <?php echo json_encode($devices) ?>;
    //console.log(details);
    var genSet =  <?php echo ($isGenset) ?>;
    var sms_lock = <?php echo ($sms_lock) ?>;
    var gridOptions;
    columnDefs = [
    {headerName: 'Edit', cellRenderer:'editCellRenderer',width:70,suppressFilter:true},
    {headerName: 'Inv Status', cellRenderer:'editCellRenderer3',width:100,suppressFilter:true},
    {headerName:'Unit',field: 'unitassigned',width:180,filter: 'agTextColumnFilter'},
    {headerName:'Status',field: 'device_status',width:150,filter: 'agTextColumnFilter'},
    {headerName:'Simcard',field: 'simcardno',width:120,filter: 'agTextColumnFilter'},
    {headerName:'Vehicle',field: 'vehicleno',width:170,filter: 'agTextColumnFilter'},
    {headerName:'Transmitter1', field:'geneset1',width: 100,filter: 'agTextColumnFilter'},
    {headerName:'Transmitter2', field:'geneset2',width: 100,filter: 'agTextColumnFilter'},
    {headerName:'Rec. Invoice',field: 'invoiceno',width: 150,filter: 'agTextColumnFilter'},
    {headerName:'Dev. Invoice',field: 'device_invoiceno',width: 150,filter: 'agTextColumnFilter'},
    {headerName:'Start Date',field: 'startdate',width: 120,filter: 'agNumberColumnFilter'},
    {headerName:'End Date',field: 'enddate',width: 100,filter: 'agNumberColumnFilter'},
    {headerName:'Expiry Date',field: 'expirydate',width: 120,filter: 'agNumberColumnFilter'},
    {headerName:'CMD', field:'setcom',width: 100,filter: 'agTextColumnFilter'},
    {headerName:'SMS Lock',cellRenderer:'editCellRenderer2',width:120,suppressFilter:true},
    {headerName: 'History', cellRenderer:'editCellRenderer1',width:100,suppressFilter:true}
    ];
    function editCellRenderer(params){
        return "<a href='pushcommand.php?id="+params.data.deviceid+"' alt='Edit Mode' title='Mode' target='_blank' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
    }
    function editCellRenderer1(params){
        return "<a href='history.php?id="+params.data.deviceid+"' alt='Edit Mode' title='Mode' target='_blank'><img style='text-align:center; width:20px; height:20px;' src='../../images/history.png'/></a>"
    }
    function editCellRenderer2(params){
        if(sms_lock==1){
             return "<a href='javascript:void(0);' alt='SMS Lock ' title='SMS Lock' onclick='lockstatusvehicle(1,"+params.data.vehicleid+","+params.data.teamid+","+params.data.customerno+");'><img style='text-align:center; width:20px; height:20px;' src='../../images/lock_green.png'/></a>";
        }
        else{
            return "<a href='javascript:void(0);' alt='SMS Unlock ' title='SMS Unlock' onclick='lockstatusvehicle(0,"+params.data.vehicleid+","+params.data.teamid+","+params.data.customerno+");'><img style='text-align:center; width:20px; height:20px;' src='../../images/lock_open_green.png'/></a>";
        }
    }
    // function editCellRenderer3(params){
    //     return "<input type='checkbox' name='invoice_status' id='invoice_status' class='invoice_status' <?php if ($customers->invoiceHold) {?> checked <?php }?> />";
    //     // return "<a href='history.php?id="+params.data.deviceid+"' alt='Edit Mode' title='Mode' target='_blank'><img style='text-align:center; width:20px; height:20px;' src='../../images/history.png'/></a>"
    // }
    gridOptions = {
    enableFilter:true,
    enableSorting: true,
    floatingFilter:true,
    rowData:details,
    animateRows:true,
    masterDetail: true,
    columnDefs: columnDefs,
    components: {editCellRenderer : editCellRenderer,
    editCellRenderer1 : editCellRenderer1,
    editCellRenderer2 : editCellRenderer2
    },
    };

    var gridDiv = document.getElementById('DevicesGrid');
    new agGrid.Grid(gridDiv,gridOptions);

    if(genSet==0){
        gridOptions.columnApi.setColumnVisible('geneset1',false);
        gridOptions.columnApi.setColumnVisible('geneset2',false);
    }
</script>
<script>
        jQuery(document).ready(function () {
            jQuery('#registermsg').fadeIn();
            jQuery('#registermsg').fadeOut(7000);

            get_item_master_details();
            get_invoiceHold_status();
            jQuery('[data-toggle="popover"]').popover();
            //login_history();

            var customer_no = <?php echo $customerid; ?>;

            jQuery("#tags").autocomplete({
                source: "route_ajax.php?action=teamdata",
                minLength: 1,
                select: function (event, ui) {
                    jQuery('#tags1').val(jQuery('#tags1').val() + ',' + ui.item.value);
                    AssignVehicle(ui.item.value, ui.item.teamid);
                    /*clear selected value */
                    jQuery(this).val("");
                    return false;
                }

            });

             /*
            Commented due to full utilization of process on terminate device
            if(customer_no != 135){
                jQuery.ajax({
                type: "POST",
                url: "route_team.php",
                data: "fetch_login_trend=1&customerno="+customer_no,
                success: function(result){
                    var response = JSON.parse(result);
                    var str = '';
                    str  = '<div class="loginTrend"><b>LOGIN TRENDS</b><br/>';
                    str += '<table id="loginTrend" border="1" align="center">'
                    str += '<tr><th>TIME</th><th>DAY</th><th>Total Logins</th></tr>';
                    str += '<tr><td>'+response.hour+'</td>';
                    str += '<td>'+response.day+'</td>';
                    str += '<td>'+response.total+'</td></tr>';
                    str += '</table>';
                    str += '</div>';
                    $("#loginKaButton").after(str);

                }
            });
            }
            */


        });

        function AssignVehicle(selected_name, vehicleid) {

            if (vehicleid != "" && jQuery('#em_vehicle_div_' + vehicleid).val() == null) {

                var div = document.createElement('div');
                var remove_image = document.createElement('img');
                remove_image.src = "../../images/boxdelete.png";
                remove_image.className = 'clickimage';
                remove_image.onclick = function () {
                    removeVehicle1(vehicleid, selected_name);
                };
                div.className = 'recipientbox';
                div.id = 'em_vehicle_div_' + vehicleid;
                div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
                jQuery("#listvehicle1").append(div);
                jQuery(div).append(remove_image);
            }
        }

        function removeVehicle1(vehicleid, mail) {
            var mail1 = ',' + mail;
            $('#em_vehicle_div_' + vehicleid).remove();
            $('#tags1').val(function (index, value) {
                return value.replace(mail1, '');
            });
        }

        function get_item_master_details(){
                $("#loader_show").show();
                var customerno = <?php echo $customerid; ?>;
                jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: "fetch_item_master_details=1&customerno="+customerno,
                success: function(data){
                result=JSON.parse(data);
                var str='<table name="item_master_div" id="item_master_div">';
                var str1 = '';
                var i = 0;
                //$("#loader_show").show();
                if(result!=undefined){
                $.each(result,function(key,value){

                if(key=='im_detailsId'){
                str += '';
                }
                else if(key=='customerno'){
                str1 += '<input type="hidden" name="'+key+'" id="'+key+'" value="'+value+'">';
                }
                else{
                if(i==0){
                str += '<tr>';
                }
                var label = key;
                label = label.toUpperCase();
                label = label.replace("_"," ");
                str += '<td class="item_master_table">'+label+'</td><td><input type="text" name="'+key+'" id="'+key+'" value="'+value+'"><td>';
                i++;
                }
                if(i==3){

                str+= '</tr>';
                i=0;
                }
                });
                $("#loader_show").hide();
                }
                str += '</table>';
                $("#temp_item_master_div").after(str);
                $("#temp_item_master_div").after(str1);
                }
                });
        }

        

        function updateSubsInvoiceStatus(){

        $("#invoice_status input:checkbox").change(function() {
             alert(1); return false;
        var ischecked= $(this).is(':checked');
        if(!ischecked)
        alert('uncheckd ' + $(this).val());
        }); return false;
            // var data = $("#invoice_status").serialize();
            // var checkBox = document.getElementById("invoice_status").val;
            var invoice_status = jQuery("#invoice_status").val();
            console.log(invoice_status); return false;
            // var invoice_status = jQuery("#invoice_status").val();
            var customer_no = <?php echo $customerid; ?>;
            alert(invoice_status); return false;
            jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: "invoice_holdForm=1&invoice_status"+invoice_status+"&customerno="+customer_no,
                success: function(result){
                    var response = JSON.parse(result);
                    if(response.includes("0")){
                        alert("Please Try Again.");
                    }else{
                        alert("Data Saved Successfully.");
                    }
                    get_invoiceHold_status();
                }
            });
        }
    // function updateSubsInvoiceStatus(){
    //         var data = $("#invoice_holdForm").serialize();
    //         jQuery.ajax({
    //             type: "POST",
    //             url: "route_ajax.php",
    //             data: "invoice_holdForm=1&"+data,
    //             success: function(result){
    //                 var response = JSON.parse(result);
    //                 if(response.includes("0")){
    //                     alert("Please Try Again.");
    //                 }else{
    //                     alert("Data Saved Successfully.");
    //                 }
    //                 get_invoiceHold_status();
    //             }
    //         });
    //     }
    function login_history(){
        var date = jQuery("#login_history_date").val();
        var customer_no = <?php echo $customerid; ?>;
         /*

         Commented due to full utilization of process on terminate device
        if(customer_no != 135){
            jQuery.ajax({
            type: "POST",
            url: "route_team.php",
            data: "fetch_login_history=1&date="+date+"&customerno="+customer_no,
            success: function(result){
                var response = JSON.parse(result);
                gridOptions_Login.api.setRowData(response);
            }
        });
        }
        */

    }

function validform_update_cust() {
    
    var customername = $("#customername").val();
    var customercompany = $("#customercompany").val();
    if (customername == "") {
        alert("Customer name not be blank.");
        return false;
    } else if (customercompany == "") {
        alert("Company name not be blank.");
        return false;
    } else {
       var data = $('#updatecustomerform').serialize();
       jQuery.ajax({
        type: "POST",
        url: "route_modifycust.php",
        cache: false,
        data: "update_customer=1&"+data,
        success: function (res) {
            console.log(res); return false;
            window.location.reload();
        }
    });
    }
}
function updateItemMaster(){
        var data = $("#item_master_details").serialize();
        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php",
            data: "insert_item_masterDetails=1&"+data,
            success: function(result){
                var response = JSON.parse(result);
                if(response.includes("0")){
                    alert("Please Try Again.");
                }else{
                    alert("Data Saved Successfully.");
                }
            }
        });
    }
function get_invoiceHold_status(){
            $("#loader_show").show();
            $('#switch_left').attr('disabled',false);
            $('#switch_right').attr('disabled',false);
            jQuery.ajax({
                        type: "POST",
                        url: "route_ajax.php",
                        data: "get_invoiceHold_status=1&customerNo="+customerid,
                        success: function(data){
                           $("#loader_show").hide();
                           result =JSON.parse(data);
                           if(result==1){
                            $("#switch_right").prop("checked","checked");
                            $('#switch_right').css('background-color','#DB4437');
                            $('#switch_right').attr('disabled',true);
                            $("#current_status").html("Current Status: <b>HOLD</b>");
                           }
                           if(result==0){
                            $("#switch_left").prop("checked","checked");
                            $('#switch_left').css('background-color','#0F9D58');
                            $('#switch_left').attr('disabled',true);
                            $("#current_status").html("Current Status: <b>FREE</b>");
                           }
                        }
            });
        }
</script>
<script>
   
</script>
