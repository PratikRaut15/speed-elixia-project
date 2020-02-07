<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
$_scripts_custom[] = "../../scripts/team/ledger_mapvehicle.js";
$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";
include("header.php");

class InvMap {
    
}

$db = new DatabaseManager();
//print_r($_POST);
$customerno = $_GET['cno'];

function PrepareSP($sp_name, $sp_params) {
    return "call " . $sp_name . "(" . $sp_params . ");";
}

$SQL = sprintf("SELECT customerno,customername,customercompany FROM ".DB_PARENT.".customer WHERE customerno=%d", $customerno);
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    $row = $db->get_nextRow();
    $cno = $row['customerno'];
    $customercompany = $row['customercompany'];
}
$pdo = $db->CreatePDOConn();
$sp_params = "''"
        . ",'". $customerno ."'"
        . ",''"
;
$QUERY = PrepareSP('get_ledger_cust_mapping', $sp_params);
$res = $pdo->query($QUERY);
$details = Array();
if ($res) {
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $DATA = new stdClass();
        $DATA->ledgerid = $row['ledgerid'];
        $DATA->ledgername = $row['ledgername'];
        $DATA->customerno = $row['customerno'];
        $details[] = $DATA;
    }
}
$db->ClosePDOConn($pdo);
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
    .listvehicle{
        width: 180px;
        background-color: #FFFFFF;
        position: absolute;
        z-index:999;
        min-height: 10px;
        max-height: 250px;
        overflow-y: auto;
        display: none;

    }
    .listvehicle li{
        list-style: none;
        padding: 3px;
        border: 1px solid #ccc;
    }
    .listvehicle  li:hover
    {
        background-color: #0193cc ;
        color:#fff;
    }
</style>
<div class="panel">
    <div class="paneltitle" align="center">Allot Vehicles To Ledger</div> 
    <div class="panelcontents">
        <span id="error_ledger" style="display: none;color: #FF0000;text-align: center">Please Select Ledger</span>
        <span id="error_vehicle" style="display: none;color: #FF0000;text-align: center">Please Allot A Vehicle</span>
        <span id="add_mapveh" style="display: none;color: #00493a;text-align: center">Add Successfully</span>
        <span id="fail_mapveh" style="display: none;color: #FF0000;text-align: center">Some Error Has Occurred Try Again</span>
        <form method="post" name="vmap" id="vmap">
            <table>
                <tr>
                    <td>Customerno</td>
                    <td>    
                        <input type ="text" name ="custno" id="custno" value="<?php echo $cno; ?>-<?php echo $customercompany; ?>" size="30" readonly/>
                    </td>
                <input type ="hidden" name ="cust_no" id="cust_no" value="<?php echo $customerno; ?>"/>
                <td>Ledger</td>
                <td>
                    <select name="ledger" id="ledger" style="width:200px;" onchange="getMappedveh()">
                        <option value="0">Select Ledger</option>
                        <?php
                        if (!empty($details)) {
                            foreach ($details as $detail) {
                                ?>
                                <option value="<?php echo $detail->ledgerid; ?>"><?php echo $detail->ledgerid; ?>-<?php echo $detail->ledgername ?></option>
                                <?php
                            }
                        }
                            ?>    
                        </select>
                </td>
                <!--
                <td>Allot Vehicles</td>
                <td>
                    <select name="unmapveh" id="unmapveh" style="width:200px;" onchange="AssignVehicle()">

                    </select>

                </td>
                -->
                <td>Allot Vehicles</td>
                <td>
                    <input  type="text" name="vehicleno" id="vehicleno" size="20" value="" autocomplete="off" placeholder="Enter Vehicle No" required />
                    <input  type="hidden" name="vehicleid" id="vehicleid" size="20">
                </td>
                <td>
                    <input type="button" class="btn-primary" name="addallveh" id="addallveh" value="Add All" onclick="mapAllVehicles();">
                </td>

                </tr>
                <tr>
                    <td colspan="100%"><div id="vehicle_list">

                        </div></td>
                        <td><input type="hidden" name="ismapveh" id="ismapveh" ></td>    
                </tr>
                <tr>
                <input type="hidden" name="maphiddencount" id="maphiddencount"/>
                <input type="hidden" name="tothiddencount" id="tothiddencount"/>
                    <td id="mappedvehcount" style="width: 150px;color:#FF0000"></td>
                    <td id="addedvehcount" style="width: 100px;color:#FF0000"></td>
                    <td id="totalvehcount" style="width: 150px;color:#00493a"></td>
                </tr>

            </table>
            <br>
            <input type ="button" name="mapveh" id="mapveh" class="btn-default" value="Map Vehicle" onclick="mapInvVeh();">
        </form>
    </div>
</div>


