<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");

$_scripts[] = "../../scripts/trash/prototype.js";

class testing {

}

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();

// UNIT PURCHASE

if (isset($_POST["usubmit"])) {
    $type_value = 0;
    $unitlist = GetSafeValueString($_POST["unitlist"], "string");
    $punitno = GetSafeValueString($_POST["punitno"], "string");
    $comments = GetSafeValueString($_POST["comments"], "string");
    $devicetype = GetSafeValueString($_POST['device'], "string");
    //////////sensors////////////////////////////////////////////
    $acesensor = GetSafeValueString($_POST['acsensor'], "string");
    $acdigitalopp = GetSafeValueString($_POST['acdigitalopp'], "string");
    $gensetsensor = GetSafeValueString($_POST['gensetsensor'], "string");
    $gensetdigitalopp = GetSafeValueString($_POST['gensetdigitalopp'], "string");
    $doorsensor = GetSafeValueString($_POST['doorsensor'], "string");
    $doordigitalopp = GetSafeValueString($_POST['doordigitalopp'], "string");
    $fuelsensor = GetSafeValueString($_POST['fuelsensor'], "string");
    $fuelanalog = GetSafeValueString($_POST['fuelanalog'], "string");
    ///////////sensorsend//////////////////////////////////////
    $transmitno1 = GetSafeValueString($_POST['transmitno'], "string");
    $tempsen = GetSafeValueString($_POST['tempsen'], "string");
    $analog1 = GetSafeValueString($_POST["panalog1"], "long");
    $analog2 = GetSafeValueString($_POST["panalog2"], "long");
    $analog3 = GetSafeValueString($_POST["panalog3"], "long");
    $analog4 = GetSafeValueString($_POST["panalog4"], "long");
    $humidity = GetSafeValueString($_POST["humidityanalog"], "long");
    $panic = GetSafeValueString($_POST['panic'], "string");
    $buzzer = GetSafeValueString($_POST['buzzer'], "string");
    $immobilizer = GetSafeValueString($_POST['immobilizer'], "string");
    $twowaycom = GetSafeValueString($_POST['twowaycom'], "string");
    $portable = GetSafeValueString($_POST['portable'], "string");
    $chalaan_no = GetSafeValueString($_POST['chalaan_no'], "string");
    $chalaan_date = date('Y-m-d', strtotime(GetSafeValueString($_POST['chalaan_date'], "string")));
    $vendor_no = GetSafeValueString($_POST['vendor_no'], "string");
    $vendor_date = date('Y-m-d', strtotime(GetSafeValueString($_POST['vendor_date'], "string")));
    $device_location = 0;

    if ($fuelsensor == '4') {
        $fs = '1';
    } else {
        $fs = '0';
    }

    if ($acesensor == '1') {
        $acs = '1';
    } else {
        $acs = '0';
    }
    if ($acdigitalopp == '1' && $acesensor == '1') {
        $acopp = 1;
    } else {
        $acopp = 0;
    }

    if ($gensetsensor == '2') {
        $gss = 1;
    } else {
        $gss = 0;
    }

    $transmitno = "";

    if ($gensetsensor == '2') {
        $transmitno = $transmitno1;
    }

    if ($gensetdigitalopp == '1' && $gensetsensor == '2') {
        $gssopp = 1;
    } else {
        $gssopp = 0;
    }

    if ($doorsensor == '3') {
        $dos = 1;
    } else {
        $dos = 0;
    }

    if ($doordigitalopp == '1' && $doorsensor == '3') {
        $dooropp = 1;
    } else {
        $dooropp = 0;
    }


    if ($devicetype == 1) {
        $type_value = $type_value + 0;
    }
    if ($devicetype == 2 && $acesensor == 1) {
        $type_value = $type_value + 1;
    }
    if ($devicetype == 2 && $gensetsensor == 2) {
        $type_value = $type_value + 2;
    }
    if ($devicetype == 2 && $doorsensor == 3) {
        $type_value = $type_value + 4;
    }
    if ($devicetype == 2 && $tempsen == 1 && $analog1 != 0) {
        $type_value = $type_value + 8;
    }
    if ($devicetype == 2 && $tempsen == 2 && $analog1 != 0 && $analog2 != 0) {
        $type_value = $type_value + 16;
    }

    if ($devicetype == 2 && $panic == 1) {
        $type_value = $type_value + 32;
    }
    if ($devicetype == 2 && $buzzer == 1) {
        $type_value = $type_value + 64;
    }
    if ($devicetype == 2 && $immobilizer == 1) {
        $type_value = $type_value + 128;
    }
    if ($devicetype == 2 && $twowaycom == 1) {
        $type_value = $type_value + 256;
    }
    if ($devicetype == 2 && $portable == 1) {
        $type_value = $type_value + 512;
    }
    if ($devicetype == 2 && $fuelsensor == 4 && $fuelanalog != 0) {
        $type_value = $type_value + 1024;
    }
    if ($devicetype == 2 && $tempsen == 3 && $analog1 != 0 && $analog2 != 0 && $analog3 != 0) {
        $type_value = $type_value + 2048;
    }
    if ($devicetype == 2 && $tempsen == 4 && $analog1 != 0 && $analog2 != 0 && $analog3 != 0 && $analog4 != 0) {
        $type_value = $type_value + 4096;
    }

    $pdo = $db->CreatePDOConn();

    $unitlist = array_filter(explode(',', $unitlist));

    if (!empty($unitlist) && is_array($unitlist)) {
        $successUnit = "";
        $failUnit = "";
        foreach ($unitlist as $punitno) {
            $sp_params = "'" . $punitno . "'"
                    . ",'" . $comments . "'"
                    . ",'" . $acs . "'"
                    . ",'" . $acopp . "'"
                    . ",'" . $gss . "'"
                    . ",'" . $gssopp . "'"
                    . ",'" . $dos . "'"
                    . ",'" . $dooropp . "'"
                    . ",'" . $today . "'"
                    . ",'" . $transmitno . "'"
                    . ",'" . $devicetype . "'"
                    . ",'" . $fs . "'"
                    . ",'" . $fuelanalog . "'"
                    . ",'" . $tempsen . "'"
                    . ",'" . $analog1 . "'"
                    . ",'" . $analog2 . "'"
                    . ",'" . $analog3 . "'"
                    . ",'" . $analog4 . "'"
                    . ",'" . $humidity . "'"
                    . ",'" . $type_value . "'"
                    . ",'" . $panic . "'"
                    . ",'" . $buzzer . "'"
                    . ",'" . $immobilizer . "'"
                    . ",'" . $twowaycom . "'"
                    . ",'" . $portable . "'"
                    . ",'" . $acesensor . "'"
                    . ",'" . $acdigitalopp . "'"
                    . ",'" . $chalaan_no . "'"
                    . ",'" . GetLoggedInUserId() . "'"
                    . ",'" . $chalaan_date . "'"
                    . ",'" . $vendor_no . "'"
                    . ",'" . $vendor_date . "'"
                    . ",'" . $device_location . "'"
                    . ",@is_executed";

            $queryCallSP = "CALL " . speedConstants::SP_PURCHASE_UNIT . "($sp_params)";

            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult["is_executed"] == 1) {
                // Create unit directory
                $relativepath = "../..";
                if (!is_dir($relativepath . '/customer/1/unitno/' . $punitno)) {
                    // Directory doesn't exist.
                    mkdir($relativepath . '/customer/1/unitno/' . $punitno, 0777, true) or die("Could not create directory");
                }

                if (!is_dir($relativepath . '/customer/1/unitno/' . $punitno . '/sqlite')) {
                    // Directory doesn't exist.
                    mkdir($relativepath . '/customer/1/unitno/' . $punitno . '/sqlite', 0777, true) or die("Could not create directory");
                }
                $successUnit.=$punitno . ",";
            } else {
                $failUnit.=$punitno . ",";
            }
        }
    }
    if (isset($successUnit) && $successUnit != "") {
        $message = "<span style='color:green;'>Unit number " . $successUnit . " successfully purchased.<span>";
    }if (isset($failUnit) && $failUnit != "") {
        $message.="<span style='color:red;'>Unit number " . $failUnit . " purchase failed.<span>";
    }
    header("Location: purchase.php?message=" . $message);
}

// SIMCARD PURCHASE
$msg = "";
// if (isset($_POST["ssubmit"])) {
//     print_r($_POST);
//     die();
//     $simList = GetSafeValueString($_POST["simlist"], "string");
//     $comments = GetSafeValueString($_POST["comments"], "string");
//     $psimcardno = GetSafeValueString($_POST["psimcardno"], "string");
//     $vendorid = GetSafeValueString($_POST["pvendor"], "long");
//     $pdo = $db->CreatePDOConn();

//     $simList = array_filter(explode(',', $simList));
//     print_r($simList);
//     die();
//     if (!empty($simList) && is_array($simList)) {
//         foreach ($simList as $simNo) {
//             $sp_params =
//                       "'" . $simNo . "'"
//                     . ",'" . $vendorid . "'"
//                     . ",'" . $comments . "'"
//                     . ",'" . GetLoggedInUserId() . "'"
//                     . ",'" . $today . "'";
//            echo $queryCallSP = "CALL " . speedConstants::SP_PURCHASE_SIM . "($sp_params)";
//            die();
//             $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
//             $db->ClosePDOConn($pdo);
//         }
//     }

// }
// Purchase Transmitter
if (isset($_POST["transsubmit"])) {
    $msg = '';
    $pdo = $db->CreatePDOConn();
    $today = date("Y-m-d H:i:s");
    $loginid = GetLoggedInUserId();
    $comments = GetSafeValueString($_POST["comments"], "string");
    $transno = GetSafeValueString($_POST["transmitterno"], "string");
    $db = new DatabaseManager();
    $sql = sprintf("select * from transmitter where transmitterno=" . $transno);
    $db->executeQuery($sql);

    if ($db->get_rowCount() > 0) {
        $msg = "Transmitter no. is already exists";
    } else {
        $sp_params1 = "'" . $transno . "'"
                . ",'" . 0 . "'"
                . ",'" . 0 . "'"
                . ",'" . 1 . "'"
                . ",'" . $today . "'"
                . ",'" . $loginid . "'"
                . ",'" . $comments . "'"
        ;
        $QUERY1 = $db->PrepareSP('insert_transmitter', $sp_params1);
        $pdo->query($QUERY1);
    }
    $db->ClosePDOConn($pdo);
}

$SQL = sprintf("SELECT * FROM vendor");
$db->executeQuery($SQL);
$vendors = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $vendor = new testing();
        $vendor->name = $row["vendorname"];
        $vendor->id = $row["id"];
        $vendors[] = $vendor;
    }
}
include("header.php");
// ---------------------------------------- Unit Purchase Form  -------------------------------------------
if (IsHead()) {
    ?>
<style>
 .clickimage{
     width: 10px;
    height: 10px;
    padding-top: 8px !important;
    float: right;
    padding-left: 15px;
    }
</style>
    <div class="panel">
        <div class="paneltitle" align="center">
            New Purchase</div>
        <div class="panelcontents">

            <form method="post" name="myform" id="myform" onsubmit="ValidateForm();
                    return false;"  enctype="multipart/form-data">
                <table width="50%">
                    <tr>
                        <td colspan="2"><h3>Device</h3></td>
                    </tr>
    <?php
    if (!empty($_REQUEST['message'])) {
        echo"<tr><td colspan='2'>" . $_REQUEST['message'] . "</td></tr>";
    }
    ?>
                    <tr>
                        <td>Unit No.<span style='color:red;'>*</span></td>
                        <td><input name = "punitno" id="punitno" type="text"><input type="hidden" name="unitlist" id="unitlist" /></td>
                    </tr>
                    <tr><td></td><td><div id="listunitno" ></div></td></tr>
                    <tr>
                        <td>Type</td>
                        <td> <input name="device" id="device" type="radio" value="1" checked="" onclick="device_type();"/> &nbsp;Basic &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="device" id="device" type="radio" value="2" onclick="device_type();" /> &nbsp; Advanced &nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>
                    <tr class="adv">
                        <td>Sensor</td>
                        <td>
                            <input name="acsensor" id="acesensor" type="checkbox" value="1" />  &nbsp;  Ac Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="acdigitalopp" id="acdigitalopp" type="checkbox" value='1'/>  &nbsp;  Is Opposite? <br/>


                            <input name="gensetsensor" id="gensetsensor" type="checkbox" value="2" onclick="gettransno()" />  &nbsp;  Genset Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="gensetdigitalopp" id="gensetdigitalopp" type="checkbox" value='1'/>  &nbsp;  Is Genset Opposite?<br/>

                            <div id="transno" style="display:none;">
                                <label>Transmitter No.</label>
                                <input type="text" id="transmitno" name="transmitno"/>
                            </div>
                            <input name="doorsensor" id="doorsensor" type="checkbox" value="3" />  &nbsp;  Door Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="doordigitalopp" id="doordigitalopp" type="checkbox" value='1'/>  &nbsp;  Is Door Opposite? <br/>
                            <input type="checkbox" name="fuelsensor" id="fuelsensor" value="4"  onclick="fuelcheckbox()">&nbsp; Fuel Sensor
                        </td>

                    </tr>
                    <tr id= "fuelanalogtd" style="display: none;">
                        <td><label>Fuel Analog</label></td>
                        <td>
                            <select name="fuelanalog" id="fuelanalog">
                                <option value="0">Select Output</option>
                                <option value="1">Analog 1</option>
                                <option value="2">Analog 2</option>
                                <option value="3">Analog 3</option>
                                <option value="4">Analog 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="adv">
                        <td>Temperature </td>
                        <td>
                            <input name="tempsen" id="tempsen" style="float:left;" type="radio" value="0"  onclick="temp_show();"/> <span  style="width:32px; float:left;">None</span>
                            <input name="tempsen" id="tempsen1" style="float:left;" type="radio" value="1"  onclick="temp_show();"/> <span  style="width:20px; float:left;"> 1 </span>  &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="tempsen" style="float:left;" id="tempsen2" type="radio" value="2" onclick="temp_show();"/> <span  style="width:20px; float:left;"> 2 </span> &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="tempsen" style="float:left;" id="tempsen3" type="radio" value="3" onclick="temp_show();"/> <span  style="width:20px; float:left;"> 3 </span> &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="tempsen" style="float:left;" id="tempsen4" type="radio" value="4" onclick="temp_show();"/> <span  style="width:20px; float:left;"> 4 </span> &nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>

                    <tr id="advnone">
                        <td id="adv1" style="display: none;">Analog Sensor 1 </td>
                        <td><select name="panalog1" id="panalog1" style="display: none;"onchange="singleTemp();">
                                <option value="0">Select Output</option>
                                <option value="1">Analog 1</option>
                                <option value="2">Analog 2</option>
                                <option value="3">Analog 3</option>
                                <option value="4">Analog 4</option>
                            </select>
                        </td>
                    </tr>

                    <tr id="double_temp" style="display: none;">
                        <td>Analog Sensor 2 </td>
                        <td><select name="panalog2" id="panalog2" onchange="doubleTemp();">
                                <option value="0">Select Output</option>
                                <option value="1">Analog 1</option>
                                <option value="2">Analog 2</option>
                                <option value="3">Analog 3</option>
                                <option value="4">Analog 4</option>
                            </select>
                        </td>
                    </tr>

                    <tr id="3_temp" style="display: none;">
                        <td>Analog Sensor 3 </td>
                        <td><select name="panalog3" id="panalog3" onchange="tripleTemp();">
                                <option value="0">Select Output</option>
                                <option value="1">Analog 1</option>
                                <option value="2">Analog 2</option>
                                <option value="3">Analog 3</option>
                                <option value="4">Analog 4</option>
                            </select>
                        </td>
                    </tr>

                    <tr id="4_temp" style="display: none;">
                        <td>Analog Sensor 4 </td>
                        <td><select name="panalog4" id="panalog4" onchange="quadTemp();">
                                <option value="0">Select Output</option>
                                <option value="1">Analog 1</option>
                                <option value="2">Analog 2</option>
                                <option value="3">Analog 3</option>
                                <option value="4">Analog 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="adv">
                        <td>Humidity </td>
                        <td>
                            <input name="humidity_div" id="humidity_div" style="float:left;" type="radio" value="0"  onclick="humidity_show();"/> <span  style="width:32px; float:left;">Yes</span>
                        </td>
                    </tr>
                    <tr id="humidity" style="display: none;">
                        <td>Analog Sensor</td>
                        <td><select name="humidityanalog" id="humidityanalog"  onchange="humidityCheck();">
                                <option value="0">Select Output</option>
                                <option value="1">Analog 1</option>
                                <option value="2">Analog 2</option>
                                <option value="3">Analog 3</option>
                                <option value="4">Analog 4</option>
                            </select>
                        </td>
                    </tr>

                    <tr class="adv">
                        <td></td>
                        <td>
                            <input name="panic" id="panic" type="checkbox" value="1" /> Panic  &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="buzzer" id="buzzer" type="checkbox" value="1" /> Buzzer  &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="immobilizer" id="immobilizer" type="checkbox" value="1" /> Immobilizer  &nbsp;&nbsp;&nbsp;&nbsp;<br>
                            <input name="twowaycom" id="twowaycom" type="checkbox" value="1" /> Two Way Communication  &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="portable" id="portable" type="checkbox" value="1" /> Portable  &nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td>Chalaan No.</td><td><input type="text" name = "chalaan_no" id="chalaan_no" ></td>
                    </tr>
                    <tr>
                        <td>Chalaan Date</td>
                        <td><input type="text" name = "chalaan_date" id="chalaan_date" ><button  id="trigger1">...</button></td>
                    </tr>
                    <tr>
                        <td>Vendor Invoice No.</td><td><input type="text" name = "vendor_no" id="vendor_no" ></td>
                    </tr>
                    <tr>
                        <td>Vendor Invoice Date</td>
                        <td><input type="text" name = "vendor_date" id="vendor_date" ><button  id="trigger2">...</button></td>
                    </tr>
                    <tr>
                        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
                    </tr>

                </table>

                <div><input type="submit" id="usubmit" name="usubmit" value="Purchase Unit"/></div>
            </form>
            <hr/>

            <form  name="myform1" id="myform1" enctype="multipart/form-data" method="POST">
                <table width="40%">
                    <tr>
                        <td colspan="2"><h3>Sim Card</h3></td>
                    </tr>
    <?php
    if (!empty($msg)) {
        echo"<tr><td colspan='2'><span style='color:red; font-size:12px;'>" . $msg . "</span></td></tr>";
    }
    ?>
                    <tr>
                        <td>Sim Card No. <span style='color:red;'>*</span></td><td><input name = "psimcardno" id="psimcardno" type="text"></td>
                        <input type="hidden" name="simlist" id="simlist" /></td>
                    </tr>
                    <tr><td></td><td><div id="listSimNo" ></div></td></tr>
                    <tr>
                        <td>Vendor </td>
                        <td><select name="pvendor" id="pvendor">
                                 <option value="0">Please Select Vendor</option>
                                <?php
                                foreach ($vendors as $thisvendor) {
                                    ?>
                                    <option value="<?php echo($thisvendor->id); ?>"><?php echo($thisvendor->name); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
                    </tr>
                </table>
                <div>
                    <input type="button" id="ssubmit" name="ssubmit" value="Purchase Sim Card" onclick="ValidateForm1();"/></div>
            </form>
            <!---------------------------- purchase transmitter ---------------------------->
            <hr/>

            <form  name="transform" id="transform"  onsubmit="ValidateTransForm();
                    return false;" method="POST">
                <table width="40%">
                    <tr>
                        <td colspan="2"><h3>Transmitter</h3></td>
                    </tr>
    <?php
    if (!empty($msg)) {
        echo"<tr><td colspan='2'><span style='color:red; font-size:12px;'>" . $msg . "</span></td></tr>";
    }
    ?>
                    <tr>
                    <span id="error_trans1" style='color:red; font-size:12px;display: none;'>Please enter Transmitter No.</span>
                    </tr>
                    <tr>
                        <td>Transmitter No. <span style='color:red;'>*</span></td><td><input name = "transmitterno" id="transmitterno" type="text"></td>
                    </tr>
                    <tr>
                        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
                    </tr>
                </table>
                <div><input type="submit" id="transsubmit" name="transsubmit" value="Purchase Transmitter"/></div>
            </form>
        </div>
    </div>
    <?php
}
?>
<br/>
<?php
include("footer.php");
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
    .labelwidth{
        width:200px;
    }
</style>
<script type="text/javascript">
    Calendar.setup(
            {
                inputField: "chalaan_date", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger1" // ID of the button
            });
    Calendar.setup(
            {
                inputField: "vendor_date", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger2" // ID of the button
            });
    $(document).ready(function () {
        $("#transno").hide();
        var device = $('input:radio[name=device]:checked').val();
        if (device == 1) {
            $(".adv").hide();
            $("#ac_sensor").hide();
            $("#double_temp").hide();
        } else {
            $(".adv").show();
            if ($("#sensor").val() != '0' && device == 2)
            {
                $("#ac_sensor").show();
            }
            if ($('input:radio[name=tempsen]:checked').val() == 2 && device == 2) {
                $("#double_temp").show();
            }
        }



    });

    function fuelcheckbox() {

        if ($('input:checkbox[name=fuelsensor]:checked').val() == 4) {
            $("#fuelanalogtd").show();
        } else {
            $("#fuelanalogtd").hide();
        }
    }


    function device_type() {
        var device = $('input:radio[name=device]:checked').val();
        if (device == 1) {
            $(".adv").hide();
            $("#adv1").hide();
            $("#panalog1").hide();
            $("#ac_sensor").hide();
            $("#fuelanalogtd").hide();
            $("#double_temp").hide();
        } else {
            $(".adv").show();
            if ($("#sensor").val() != '0' && device == 2)
            {
                $("#ac_sensor").show();
            }
            if ($('input:radio[name=tempsen]:checked').val() == 2 && device == 2) {
                $("#double_temp").show();
            }
        }
    }
    function sensor_show()
    {
        if ($("#sensor").val() == '1' || $("#sensor").val() == '2' || $("#sensor").val() == '3')
        {
            $("#ac_sensor").show();
        } else {
            $("#ac_sensor").hide();
        }
    }
    function temp_show() {
        if ($('input:radio[name=tempsen]:checked').val() == 0) {
            $("#double_temp").hide();
            $("#panalog1").hide();
            $("#advnone").hide();
            $("#adv1").hide();
            $("#3_temp").hide();
            $("#4_temp").hide();

        } else if ($('input:radio[name=tempsen]:checked').val() == 1) {
            $("#adv1").show();
            $("#advnone").show();
            $("#panalog1").show();
            $("#double_temp").hide();
            $("#3_temp").hide();
            $("#4_temp").hide();
        } else if ($('input:radio[name=tempsen]:checked').val() == 2) {
            $("#adv1").show();
            $("#advnone").show();
            $("#panalog1").show();
            $("#double_temp").show();
            $("#3_temp").hide();
            $("#4_temp").hide();
        } else if ($('input:radio[name=tempsen]:checked').val() == 3) {
            $("#adv1").show();
            $("#advnone").show();
            $("#panalog1").show();
            $("#double_temp").show();
            $("#3_temp").show();
            $("#4_temp").hide();
        } else if ($('input:radio[name=tempsen]:checked').val() == 4) {
            $("#adv1").show();
            $("#advnone").show();
            $("#panalog1").show();
            $("#double_temp").show();
            $("#3_temp").show();
            $("#4_temp").show();
        } else {
            $("#double_temp").hide();
            $("#panalog1").hide();
            $("#adv1").hide();
        }
    }

    function humidity_show(){
         if ($('input:radio[name=humidity_div]:checked').val() == 0) {
            $("#humidity").show();
        } else {
            $("#humidity").hide();
        }
    }

    function singleTemp() {
        var temp1 = $("#panalog1").val();
        var humidty = $("#humidityanalog").val();
        if (temp1 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog1').val(0);
        }

    }

    function doubleTemp() {
        var temp1 = $("#panalog1").val();
        var temp2 = $("#panalog2").val();
        var humidty = $("#humidityanalog").val();
        if (temp1 == temp2) {
            alert("Please Select Different Analog Output");
            $('#panalog2').val(0);
        }
        else if (temp1 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog1').val(0);
        }
        else if (temp2 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog2').val(0);
        }
    }

    function tripleTemp() {
        var temp1 = $("#panalog1").val();
        var temp2 = $("#panalog2").val();
        var temp3 = $("#panalog3").val();
        var humidty = $("#humidityanalog").val();
        if (temp1 == temp2) {
            alert("Please Select Different Analog Output");
            //alert("test");
            $('#panalog2').val(0);
        } else if (temp1 == temp3) {
            alert("Please Select Different Analog Output");
            $('#panalog3').val(0);
        } else if (temp2 == temp3) {
            alert("Please Select Different Analog Output");
            $('#panalog3').val(0);
        }
         else if (temp1 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog1').val(0);
        }
        else if (temp2 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog2').val(0);
        }
        else if (temp3 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog3').val(0);
        }
    }

    function quadTemp() {
        var temp1 = $("#panalog1").val();
        var temp2 = $("#panalog2").val();
        var temp3 = $("#panalog3").val();
        var temp4 = $("#panalog4").val();
        var humidty = $("#humidityanalog").val();

        if (temp1 == temp2) {
            alert("Please Select Different Analog Output For Double Temperature");
            $('#panalog2').val(0);
        } else if (temp1 == temp3) {
            alert("Please Select Different Analog Output");
            $('#panalog3').val(0);
        } else if (temp1 == temp4) {
            alert("Please Select Different Analog Output");
            $('#panalog4').val(0);
        } else if (temp2 == temp3) {
            alert("Please Select Different Analog Output");
            $('#panalog3').val(0);
        } else if (temp2 == temp4) {
            alert("Please Select Different Analog Output");
            $('#panalog4').val(0);
        } else if (temp3 == temp4) {
            alert("Please Select Different Analog Output");
            $('#panalog4').val(0);
        }
        else if (temp1 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog1').val(0);
        }
        else if (temp2 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog2').val(0);
        }
        else if (temp3 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog3').val(0);
        }
        else if (temp4 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog4').val(0);
        }
    }

    function humidityCheck() {
        var temp1 = $("#panalog1").val();
        var temp2 = $("#panalog2").val();
        var temp3 = $("#panalog3").val();
        var temp4 = $("#panalog4").val();

        var humidty = $("#humidityanalog").val();
         if (temp1 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog1').val(0);
        }
         else if (temp2 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog2').val(0);
        }
        else if (temp3 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog3').val(0);
        }
        else if (temp4 == humidty) {
            alert("Please Select Different Analog Output");
            $('#panalog4').val(0);
        }
    }

    function ValidateForm1() {
            var sim_list= $("#simlist").val();
            var vendor = $("#pvendor").val();
            var data = $('#myform1').serialize();

            if(sim_list==''){
                alert("Please Enter Simcard Number");
                return false;
            }
            else if(vendor==0 || vendor==''){
                alert("Please Select Vendor");
                return false;
            }
            else{
            $.ajax({
                type: 'POST',
                url: 'route_ajax.php',
                data: data+"&ssubmit=1",
                success: function (response) {
                    alert("Sim Purchased successfully");
                    window.location.reload();
                }

            });
            }
    }

    function ValidateForm() {
        var punitno = $("#punitno").val();
        var devicetype = $('input:radio[name=device]:checked').val();
        var tempsen = $('input:radio[name=tempsen]:checked').val();
        var analog1 = $("#panalog1").val();
        var analog2 = $("#panalog2").val();
        var acsensor = $('input:checkbox[name=acsensor]:checked').val() ? 1 : 0;
        var gensetsensor = $('input:checkbox[name=gensetsensor]:checked').val() ? 1 : 0;
        var doorsensor = $('input:checkbox[name=doorsensor]:checked').val() ? 1 : 0;
        var fuelsensor = $('input:checkbox[name=fuelsensor]:checked').val() ? 1 : 0;
        var acdigitalopp = $('input:checkbox[name=acdigitalopp]:checked').val() ? 1 : 0;
        var gensetdigitalopp = $('input:checkbox[name=gensetdigitalopp]:checked').val() ? 1 : 0;
        var doordigitalopp = $('input:checkbox[name=doordigitalopp]:checked').val() ? 1 : 0;
        var panic = $('input:checkbox[name=panic]:checked').val() ? 1 : 0;
        var buzzer = $('input:checkbox[name=buzzer]:checked').val() ? 1 : 0;
        var immobilizer = $('input:checkbox[name=immobilizer]:checked').val() ? 1 : 0;
        var twowaycom = $('input:checkbox[name=twowaycom]:checked').val() ? 1 : 0;
        var portable = $('input:checkbox[name=portable]:checked').val() ? 1 : 0;
        var fuelanalog = $("#fuelanalog").val();

        if (acsensor == '0' && gensetsensor == "0" && doorsensor == "0" && fuelsensor == "0" && tempsen == "0") {
            var sensor = 0;
        } else {
            var sensor = 1;
        }

        if (panic == '0' && buzzer == '0' && immobilizer == '0' && twowaycom == '0' && portable == '0') {
            var types = 0;
        } else {
            var types = 1;
        }

        if (punitno == '')
        {
            alert("Please Enter Unit No");
            return false;
        } else if (devicetype == 2 && (sensor == 0 && types == 0)) {
            alert("Please Select advanced features");
            return false;
        }
        else
        {
            $("#myform").submit();

        }
    }

    function gettransno() {
        $("#gensetsensor").attr("checked") ? $("#transno").show() : $("#transno").hide();
    }
    function ValidateTransForm() {
        var trans = jQuery("#transmitterno").val();
        if (trans == '') {
            jQuery("#error_trans1").show();
            jQuery("#error_trans1").fadeOut(3000);
        } else {

            jQuery("#transform").submit();
        }
    }

    $("#punitno").keyup(function (event) {
        if (event.keyCode == 13) {
            insertEmailDiv(this.value, this.value);
            //jQuery(this).val("");
        }
    });

    function insertEmailDiv(selected_name, eid) {
        if (eid != "" && jQuery('#em_vehicle_div_' + eid).val() == null) {
            var data = "check_unit=" + selected_name;
            jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: data,
                cache: false,
                success: function (json) {
                    if (json == 'ok') {
                        var div = document.createElement('div');
                        div.id = "contain";

                        var remove_image = document.createElement('img');
                        remove_image.src = "../../images/boxdelete.png";
                        remove_image.className = 'clickimage';

                        remove_image.onclick = function () {
                            removeEmailDiv(selected_name, eid);
                        };

                        div.className = 'recipientbox';
                        div.id = 'em_vehicle_div_' + eid;
                        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicles_' + eid + '" value="' + eid + '"/>';
                        jQuery("#listunitno").append(div);
                        jQuery(div).append(remove_image);
                        var unitlist = jQuery('#unitlist').val();

                        jQuery('#unitlist').val(unitlist + "," + selected_name);

                    } else {
                        alert("Existing Unit Number");
                        $("#punitno").val();
                    }
                }
            });
        } else {
            alert("Same Unit Number");
            $("#punitno").val();
        }
    }
    function removeEmailDiv(selected_name, eid) {
        var rep = "," + selected_name;
        jQuery('#em_vehicle_div_' + eid).remove();
        $("#unitlist").val($("#unitlist").val().replace(rep, ""));
        $("#unitlist").val($("#unitlist").val().replace(selected_name, ""));
        $('#to_email_div_' + eid).remove();
        console.log($("#unitlist").val());
    }

    $("#psimcardno").keyup(function (event) {

        if (event.keyCode == 13) {
            insertSimcardDiv(this.value, this.value);
            jQuery(this).val("");
        }

    });

    function insertSimcardDiv(selected_name, eid) {
        jQuery("#simlist").val(function (i, val) {
        if (!val.includes(eid)) {
        return val + (!val ? '':  ',') + eid;
        }
        else {
        return val;
        }
        });

        if (eid != "" && jQuery('#em_simcard_div_' + eid).val() == null) {
            var data = "check_sim=" + selected_name;
            jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: data,
                cache: false,
                success: function (json) {
                    if (json == 'ok') {
                        var div = document.createElement('div');
                        div.id = "contain";

                        var remove_image = document.createElement('img');
                        remove_image.src = "../../images/boxdelete.png";
                        remove_image.className = 'clickimage';

                        remove_image.onclick = function () {
                            removeSimcardDiv(selected_name, eid);
                        };

                        div.className = 'recipientbox';
                        div.id = 'em_simcard_div_' + eid;
                        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="s_list_element" name="em_simcard_div_' + eid + '" value="' + eid + '"/>';
                        jQuery("#listSimNo").append(div);
                        jQuery(div).append(remove_image);
                    } else {
                        alert("Existing Sim Number");
                        $("#psimcardno").val();
                    }
                }
            });
        }
        else {
            alert("Same Sim Number");
            $("#psimcardno").val();
        }
    }

    function removeSimcardDiv(selected_name, eid) {
    var rep = "," + eid;
    $("#simlist").val($("#simlist").val().replace(rep, ""));
    $("#simlist").val($("#simlist").val().replace(eid, ""));
    $('#myform1').find('#em_simcard_div_' + eid).remove();
    }
</script>
