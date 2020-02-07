<?php
error_reporting(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
//$_scripts[] = "../../scripts/trash/prototype.js";
$_scripts[] = "../../scripts/jquery.min.js";

include("header.php");

class testing {

}

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();
$message = "";

// ------------------------------------------------------  Return Bad Units with Simcards  ------------------------------------------------------ //
if (isset($_POST["ureturn"])) {
    $teamid = GetSafeValueString($_POST["uteamid_returnall"], "string");
    $comments = GetSafeValueString($_POST["comments"], "string");
    $simcardid = GetSafeValueString($_POST["reSimAllNo"], "string");
    $unitid = GetSafeValueString($_POST["reUnitAllNo"], "string");

    if ($teamid == '0') {
        $message = "<span style='color:red;'>Please select return form name</span>";
    }
    if ($simcardid !== '' && isset($simcardid)) {
        $simcardid = explode(',', $simcardid);
        foreach ($simcardid as $sims) {
            $SQL = sprintf("UPDATE  `simcard`
                            SET     `trans_statusid` = 12
                                    , `teamid` = 0
                                    , `comments` = '%s'
                            WHERE   id = %d", Sanitise::String($comments), Sanitise::Long($sims));
            $db->executeQuery($SQL);

            $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (`customerno`
                                            ,`simcard_id`
                                            ,`teamid`
                                            , `type`
                                            , `trans_time`
                                            , `statusid`
                                            , `transaction`
                                            , `simcardno`
                                            , `invoiceno`
                                            , `expirydate`
                                            , `allot_teamid`
                                            , `comments`)
                                VALUES (1,%d,%d,1,'%s',%d, '%s','%s','%s','%s',%d,'%s')", Sanitise::Long($sims)
                    , Sanitise::Long(GetLoggedInUserId())
                    , Sanitise::DateTime($today)
                    , 12
                    , "Simcard Returned"
                    , ""
                    , ""
                    , ""
                    , Sanitise::Long($teamid)
                    , Sanitise::String($comments));
            $db->executeQuery($SQLunit);
        }
    }

    if (!empty($unitid) && isset($unitid)) {
        $unitid = explode(',', $unitid);
        foreach ($unitid as $uid) {
            $SQL = sprintf("UPDATE  unit
                            SET     trans_statusid= 17
                                    , teamid = 0
                                    , comments = '%s'
                            where   uid = %d", Sanitise::String($comments), Sanitise::Long($uid));
            $db->executeQuery($SQL);

            $SQL = sprintf('UPDATE  devices
                            SET     simcardid = 0
                            WHERE   uid = %d', Sanitise::Long($uid));
            $db->executeQuery($SQL);

            $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (`customerno`
                                            ,`unitid`
                                            ,`teamid`
                                            , `type`
                                            , `trans_time`
                                            , `statusid`
                                            , `transaction`
                                            , `simcardno`
                                            , `invoiceno`
                                            , `expirydate`
                                            , `allot_teamid`
                                            , `comments`)
                                VALUES (1,%d,%d, 0, '%s', %d, '%s','%s','%s','%s',%d, '%s')", Sanitise::Long($uid)
                    , Sanitise::Long(GetLoggedInUserId())
                    , Sanitise::DateTime($today)
                    , 17
                    , "Unit Returned"
                    , ""
                    , ""
                    , ""
                    , Sanitise::Long($teamid)
                    , Sanitise::String($comments));
            $db->executeQuery($SQLunit);
        }
    }

//    insert into allotment history
//    $SQLhis = sprintf("INSERT INTO " . DB_PARENT . ".allotment_history (
//                       assignerid, receverid, unitid, simid, stage, comments, insertedby, insertedon)
//                        VALUES(
//                        '%s', '%s', %d, %d, '%s', '%s', '%s', '%s')", $teamname, GetLoginUser(), $unitid, $simcardid, "Return", $comments, GetLoginUser(), Sanitise::DateTime($today));
//    $db->executeQuery($SQLhis);
}



// ------------------------------------------------------  Allot Unit and Simcard ------------------------------------------------------ //
if (isset($_POST["uallot"])) {
    $teamid = GetSafeValueString($_POST["uteamid"], "string");
    $comments = GetSafeValueString($_POST["comments"], "string");
    $simcardid1 = GetSafeValueString($_POST["allSimNo"], "string");
    $unitid = GetSafeValueString($_POST["allUnitNo"], "string");

    if ($simcardid1 !== '' && isset($simcardid1)) {
        $simcardid1 = explode(',', $simcardid1);
        foreach ($simcardid1 as $sims) {
            $SQL = sprintf("UPDATE  simcard
                            SET     trans_statusid = 19
                                    , teamid = %d
                                    , comments = '%s'
                            WHERE   id = %d", Sanitise::Long($teamid), Sanitise::String($comments), Sanitise::Long($sims));
            $db->executeQuery($SQL);

            $sql = sprintf("SELECT  simcardno
                            FROM    simcard
                            WHERE   id = %d", Sanitise::Long($sims));
            $db->executeQuery($sql);

            while ($row = $db->get_nextRow()) {
                $simcardno = $row["simcardno"];
            }

            $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (`customerno`
                                            , `simcard_id`
                                            , `teamid`
                                            , `type`
                                            , `trans_time`
                                            , `statusid`
                                            , `transaction`
                                            , `simcardno`
                                            , `invoiceno`
                                            , `expirydate`
                                            , `allot_teamid`
                                            , `comments`)
                                VALUES (1,%d,%d, 1, '%s', %d, '%s','%s','%s','%s',%d,'%s')", Sanitise::Long($sims)
                    , Sanitise::Long(GetLoggedInUserId())
                    , Sanitise::DateTime($today)
                    , 19
                    , "Simcard Allotment"
                    , Sanitise::String($simcardno)
                    , ""
                    , ""
                    , Sanitise::Long($teamid)
                    , Sanitise::String($comments));
            $db->executeQuery($SQLunit);
        }
    }
    if ($unitid !== '' && isset($unitid)) {
        $unitid = explode(',', $unitid);
        foreach ($unitid as $uid) {
            $SQL = sprintf("UPDATE  unit
                            SET     trans_statusid = 18
                                    , teamid = %d
                                    ,comments = '%s'
                                    ,unit_location_box_number=-1
                            WHERE   uid = %d", Sanitise::Long($teamid), Sanitise::String($comments), Sanitise::Long($uid));
            $db->executeQuery($SQL);


            $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (`customerno`
                                            ,`unitid`
                                            ,`teamid`
                                            , `type`
                                            , `trans_time`
                                            , `statusid`
                                            , `transaction`
                                            , `invoiceno`
                                            , `expirydate`
                                            , `allot_teamid`
                                            , `comments`)
                                VALUES (1,%d,'%s',0,'%s',%d,'%s','%s','%s',%d,'%s')", Sanitise::Long($uid)
                    , Sanitise::Long(GetLoggedInUserId())
                    , Sanitise::DateTime($today)
                    , 18
                    , "Device Allotment"
                    , ""
                    , ""
                    , Sanitise::Long($teamid)
                    , Sanitise::String($comments));
            $db->executeQuery($SQLunit);
        }
    }
//    if ($simcardid != '0' && $unitid != '0') {
//        $SQL = sprintf('UPDATE devices SET simcardid=' . $simcardid . ' where uid=' . $unitid);
//        $db->executeQuery($SQL);
//
//        $sql = sprintf("select simcardno from simcard where id=" . $simcardid);
//        $db->executeQuery($sql);
//        $vids = array();
//        while ($row = $db->get_nextRow()) {
//            $simcardno = $row["simcardno"];
//        }
//    } else {
//        $SQL = sprintf('UPDATE devices SET simcardid=0 where uid=' . $unitid);
//        $db->executeQuery($SQL);
//    }
    // insert into allotment history
//    $SQLhis = sprintf("INSERT INTO " . DB_PARENT . ".allotment_history (
//                       assignerid, receverid, unitid, simid, stage, comments, insertedby, insertedon)
//                        VALUES(
//                        '%s', '%s', %d, %d, '%s', '%s', '%s', '%s')", GetLoginUser(), $teamname, $unitid, $simcardid, "Allot", $comments, GetLoginUser(), Sanitise::DateTime($today));
//    $db->executeQuery($SQLhis);
}

// ------------------------------------------------------  Re-Allot Unit and Simcard ------------------------------------------------------ //
if (isset($_POST["ureallot"])) {
    $teamid = GetSafeValueString($_POST["uteamid_new"], "string");
    $comments = GetSafeValueString($_POST["comments"], "string");
    $simcardid = GetSafeValueString($_POST["reAllSimNo"], "string");

    $unitid = GetSafeValueString($_POST["reAllUnitNo"], "string");
    $unitid = explode(',', $unitid);
    $from_teamdata = explode("-", GetSafeValueString($_POST["uteamid"], "string"));
    $from_teamid = $from_teamdata[0];
    $from_teamname = $from_teamdata[1];

    if ($teamid != 0) {
        if ($simcardid !== '' && isset($simcardid)) {
            $simcardid = explode(',', $simcardid);
            foreach ($simcardid as $sims) {
                $SQL = sprintf("UPDATE  simcard
                                SET     trans_statusid = 19
                                        , teamid = %d
                                        ,comments='%s'
                                WHERE   id = %d", Sanitise::Long($teamid)
                        , Sanitise::String($comments)
                        , Sanitise::Long($sims));
                $db->executeQuery($SQL);

                $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (`customerno`
                                            ,`simcard_id`
                                            ,`teamid`
                                            , `type`
                                            , `trans_time`
                                            , `statusid`
                                            , `transaction`
                                            , `simcardno`
                                            , `invoiceno`
                                            , `expirydate`
                                            , `allot_teamid`
                                            , `comments`)
                                    VALUES (1,%d,%d, 1, '%s', %d, '%s','%s','%s','%s',%d,'%s')", Sanitise::Long($sims)
                        , Sanitise::Long(GetLoggedInUserId())
                        , Sanitise::DateTime($today)
                        , 19
                        , "Simcard Re-Allotment"
                        , ""
                        , ""
                        , ""
                        , Sanitise::Long($teamid)
                        , Sanitise::String($comments));
                $db->executeQuery($SQLunit);
            }
        }
        if ($unitid !== '' && isset($unitid)) {
            foreach ($unitid as $uid) {
                $SQL = sprintf("UPDATE  `unit`
                                SET     `trans_statusid`= 18
                                        , teamid=%d
                                        , comments='%s'
                                WHERE   uid = %d", Sanitise::Long($teamid)
                        , Sanitise::String($comments)
                        , Sanitise::Long($uid));
                $db->executeQuery($SQL);
                $SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (`customerno`
                                            ,`unitid`
                                            ,`teamid`
                                            , `type`
                                            , `trans_time`
                                            , `statusid`
                                            , `transaction`
                                            , `simcardno`
                                            , `invoiceno`
                                            , `expirydate`
                                            , `allot_teamid`
                                            , `comments`)
                                    VALUES (1,%d,%d,0,'%s', %d, '%s','%s','%s','%s',%d,'%s')", Sanitise::Long($uid)
                        , Sanitise::Long(GetLoggedInUserId())
                        , Sanitise::DateTime($today)
                        , 18
                        , "Device Re-Allotment"
                        , ""
                        , ""
                        , ""
                        , Sanitise::Long($teamid)
                        , Sanitise::String($comments));
                $db->executeQuery($SQLunit);
            }
        }
    }
}

if (isset($_POST["assignSim"])) {
    $unitid = $_POST['unitassign'];
    $simid = $_POST['simassign'];
    $SQL = sprintf("UPDATE " . DB_PARENT . ".devices SET simcardid= %d where uid=%d", $simid, $unitid);
    $db->executeQuery($SQL);

    $SQLhis = sprintf("INSERT INTO " . DB_PARENT . ".allotment_history (
                       assignerid,unitid, simid, stage, insertedby, insertedon)
                        VALUES(
                        '%s', %d, %d, '%s', '%s', '%s')", GetLoginUser(), $unitid, $simid, "Allot", GetLoginUser(), Sanitise::DateTime($today));
    $db->executeQuery($SQLhis);
}
if (isset($_POST['removeSimcard'])) {
    $unitid = $_POST['unitRemove'];
    $SQL = sprintf("UPDATE " . DB_PARENT . ".devices SET simcardid= 0 where uid=%d", $unitid);
    $db->executeQuery($SQL);
    $messageRemove = "Successfully Removed";
}
if (IsHead() || IsService()) {

//    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (12) order by simcard.simcardno asc");
//    $db->executeQuery($SQL);
//    $badsimcards = Array();
//    if ($db->get_rowCount() > 0) {
//        while ($row = $db->get_nextRow()) {
//            $simcard = new testing();
//            $simcard->simcardno = $row["simcardno"] . "[ " . $row["status"] . " ]";
//            $simcard->id = $row["simid"];
//            $badsimcards[] = $simcard;
//        }
//    }

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
//////////////////////Bad Unit list///////////////////////
//    $db = new DatabaseManager();
//    $SQL = sprintf("SELECT unit.unitno, unit.uid, trans_status.status FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (3) ORDER BY `unit`.`unitno` ASC ");
//    $db->executeQuery($SQL);
//    $badunits = Array();
//    if ($db->get_rowCount() > 0) {
//        while ($row = $db->get_nextRow()) {
//            $unit = new testing();
//            $unit->unitno = $row["unitno"] . "[ " . $row["status"] . " ]";
//            $unit->uid = $row["uid"];
//            $badunits[] = $unit;
//        }
//    }
    ?>
    <!------------------------------------------------------  Allot Unit and Simcard ------------------------------------------------------>
    <div class="panel">
        <div class="paneltitle" align="center">
            Allotment</div>
        <div class="panelcontents">
            <form method="post" name="myform" id="myform" action="invallotment.php" enctype="multipart/form-data">
                <h3>Allot to Team</h3>
                <table width="50%">

                    <tr>
                        <td>Elixir</td>
                        <td><select name="uteamid" onchange="unit();">
                                <?php
                                foreach ($team_allot_array as $thisteam) {
                                    ?>
                                    <option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Unit No. </td>
                        <td><input  type="text" name="unitno" id="unitno" size="20" value="" autocomplete="off" placeholder="Enter Unit No" onkeyup="refreshFun()" />
                            ( Ready Device List)
                            <div id="listUnit" ></div>
                            <input type='hidden' name='allUnitNo' id="allUnitNo" required />
                        </td>
                    </tr>

                    <tr>
                        <td>Sim Card No. </td>
                        <td><input  type="text" name="simno" id="simno" size="20" value="" autocomplete="off" placeholder="Enter SimCard No" onkeyup="refreshSim()" />
                            ( Activated Sim Card List)
                            <div id="listSim"></div>
                            <input type='hidden' name='allSimNo' id="allSimNo" required />

                        </td>
                    </tr>

                    <tr>
                        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
                    </tr>

                </table>

                <div><input type="submit" id="uallot" name="uallot" value="Allot"/></div>
            </form>
            <hr/>

            <!------------------------------------------------------  Re-Allot Unit and Simcard ------------------------------------------------------>
            <form method="post" name="myformreallot" id="myformreallot" action="invallotment.php" onsubmit="ValidateForm(); return false;" enctype="multipart/form-data">
                <h3>Re-Allot to Team</h3>
                <table width="50%">
                    <tr>
                        <td>Allot From</td>
                        <td><select name="uteamid1" id="uteamid1" onChange="pullunit();">
                                <option value="0">Select an Elixir</option>
                                <?php
                                foreach ($team_allot_array as $thisteam) {
                                    ?>
                                    <option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    <input type="hidden" id="ReAllotTeam" name="ReAllotTeam"/>
                    </tr>

                    <tr>
                        <td>Unit No. </td>
                        <td id="uready_td"><input  type="text" name="unitnumber" id="unitnumber" size="20" value="" autocomplete="off" placeholder="Enter Unit No" onkeyup="getUnit()" />
                            ( Allotted Device List with selected Elixir)
                            <div id="reListUnit" ></div>
                            <input type='hidden' name='reAllUnitNo' id="reAllUnitNo" required />
                        </td>
                    </tr>

                    <tr>
                        <td>Sim Card No. </td>
                        <td id="simready_td"><input  type="text" name="simnumber" id="simnumber" size="20" value="" autocomplete="off" placeholder="Enter Simcard No" onkeyup="getSim()" />
                            ( Allotted Simcard List with selected Elixir)
                            <div id="reListSim" ></div>
                            <input type='hidden' name='reAllSimNo' id="reAllSimNo" required />
                        </td>
                    </tr>
                    <tr><td></td><td>---</td></tr>
                    <tr>
                        <td>Allot To</td>
                        <td><select name="uteamid_new" id="uteamid_new">
                                <option value="0">Select an Elixir</option>
                                <?php
                                foreach ($team_allot_array as $thisteam) {
                                    ?>
                                    <option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
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

                <div><input type="submit" id="ureallot" name="ureallot" value="Re-Allot"/></div>
            </form>
        </div>
    </div>


    <!------------------------------------------------------  Return Units with Simcards ------------------------------------------------------>
    <div class="panel">
        <div class="paneltitle" align="center">
            Return</div>
        <div class="panelcontents">
            <?php
            if (!empty($message)) {
                echo $message;
            }
            ?>
            <form method="post" name="myformreturn" id="myformreturn" onsubmit="ValidateForm2(); return false;" action="invallotment.php" enctype="multipart/form-data">
                <h3>Return Units and Simcards</h3>

                <table width="50%">

                    <tr>
                        <td>Return From</td>
                        <td><select name="uteamid_returnall" id="uteamid_returnall" onChange="pullallunit();">
                                <option value="0">Select an Elixir</option>
                                <?php
                                foreach ($team_allot_array as $thisteam) {
                                    ?>
                                    <option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <input type="hidden" id="ReturnTeam" name="ReturnTeam"/>
                        </td>
                    </tr>

                    <tr>
                        <td>Unit No. </td>
                        <td id="uready_all_td"><input  type="text" name="uallotted_all" id="uallotted_all" size="20" value="" autocomplete="off" placeholder="Enter Unit No" onkeyup="getUnitNo()" />
                            ( Allotted / Bad Device List with selected Elixir)
                            <div id="reUnitList" ></div>
                            <input type='hidden' name='reUnitAllNo' id="reUnitAllNo" required />
                        </td>
                    </tr>

                    <tr>
                        <td>Sim Card No. </td>
                        <td id="simready_all_td"><input  type="text" name="simallotted_all" id="simallotted_all" size="20" value="" autocomplete="off" placeholder="Enter Simcard No" onkeyup="getSimNo()" />
                            ( Allotted / Bad Simcard List with selected Elixir)
                            <div id="reSimList" ></div>
                            <input type='hidden' name='reSimAllNo' id="reSimAllNo" required />
                        </td>
                    </tr>

                    <tr>
                        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
                    </tr>

                </table>

                <div><input type="submit" id="ureturn" name="ureturn" value="Return"/></div>
            </form>
        </div>
    </div>
    <div class="panel">
        <div class="paneltitle" align="center">
            Assign</div>
        <div class="panelcontents">
            <span id="errorU" style="display: none;color: red">Enter correct Unit Number.</span>
            <span id="errorS" style="display: none;color: red">Enter correct Simcard Number.</span>
            <form method="post" name="myformassign" id="myformassign" onsubmit="return validate();" action="invallotment.php" enctype="multipart/form-data">
                <h3>Assign Simcard to Device</h3>
                <table width="50%">
                    <tr>
                        <td>Unit Number</td>
                        <td><input  type="text" name="unitnotassign" id="unitnotassign" size="20" value="" autocomplete="off" placeholder="Enter Unit No" onkeyup="getUnitNotAssign()" />
                            <input type='hidden' name='unitassign' id="unitassign" required /></td>
                    </tr>
                    <tr>
                        <td>Simcard Number</td>
                        <td><input  type="text" name="simnotassign" id="simnotassign" size="20" value="" autocomplete="off" placeholder="Enter Simcard No" onkeyup="getSimNotAssign()" />
                            <input type='hidden' name='simassign' id="simassign" required /></td></td>
                    </tr>
                </table>

                <div><input type="submit" id="assignSim" name="assignSim" value="assign"/></div>
            </form>
            <hr/>
            <form method="post" name="myformRemove" id="myformRemove" onsubmit="return validateRemForm();" action="invallotment.php" enctype="multipart/form-data">
                <h3>Remove Simcard from Device</h3>
                <span id="removesuccess" style="display:none;color: green;"><?php if (isset($messageRemove)) {
                        echo $messageRemove;
                    } ?></span>
                <span id="errorUnit" style="display: none;color: red">Enter correct Unit Number.</span>
                <br><table width="50%">
                    <tr>
                        <td>Unit Number</td>
                        <td><input  type="text" name="unitToRemove" id="unitToRemove" size="20" value="" autocomplete="off" placeholder="Enter Unit No" onkeyup="getUnitToRemove()" />
                            <input type='hidden' name='unitRemove' id="unitRemove" required /></td>
                    </tr>
                </table>
                <div><input type="submit" id="removeSimcard" name="removeSimcard" value="Remove Sim"/></div>
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

<script>

// -------------------------------------------- Pull for Allotment ------------------------------------------- //
    function unit() {

        $('#listUnit').empty();
        $('#listSim').empty();
        $('#allUnitNo').val('');
        $('#allSimNo').val('');
    }
    function pullunit()
    {
        $('#reListUnit').empty();
        $('#reListSim').empty();
        $('#reAllUnitNo').val('');
        $('#reAllSimNo').val('');
        var arr = jQuery('#uteamid1').val();
        var data = arr.split('-');
        var uteamid = data[0];
        $('#ReAllotTeam').val(uteamid);
    }

    function pullsimcard_from_unit()
    {
        var arr_uallotted = jQuery('#uallotted').val();
        var data_uallotted = arr_uallotted.split('-');
        var uallotted = data_uallotted[0];

        var arr_simteamid = jQuery('#uteamid').val();
        var data_simteamid = arr_simteamid.split('-');
        var simteamid = data_simteamid[0];

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {uallotted: uallotted, simteamid: simteamid},
            dataType: 'html',
            success: function (html) {
                jQuery("#simready_td").html('');
                jQuery("#simready_td").append(html);
            }
        });
        return false;
    }

// -------------------------------------------- Pull for Return ------------------------------------------- //
    function pullallunit()
    {
        $('#reUnitList').empty();
        $('#reSimList').empty();
        $('#reUnitAllNo').val('');
        $('#reSimAllNo').val('');
        var arr_uteamid = jQuery('#uteamid_returnall').val();
        var data_uteamid = arr_uteamid.split('-');
        var uteamid = data_uteamid[0];
        $('#ReturnTeam').val(uteamid);
//        jQuery.ajax({
//            url: "route_ajax.php",
//            type: 'POST',
//            cache: false,
//            data: {uteamid_returnall: uteamid},
//            dataType: 'html',
//            success: function (html) {
//                jQuery("#uready_all_td").html('');
//                jQuery("#uready_all_td").append(html);
//
//                // Pull Simcards
//                pullallsimcards();
//            }
//        });
//        return false;
    }

//    function pullallsimcards()
//    {
//        var arr_steamid = jQuery('#uteamid_returnall').val();
//        var data_steamid = arr_steamid.split('-');
//        var steamid = data_steamid[0];
//
//        jQuery.ajax({
//            url: "route_ajax.php",
//            type: 'POST',
//            cache: false,
//            data: {steamid_all: steamid},
//            dataType: 'html',
//            success: function (html) {
//                jQuery("#simready_all_td").html('');
//                jQuery("#simready_all_td").append(html);
//            }
//        });
//        return false;
//    }

    function pullallsimcard_from_unit()
    {
        var arr_uallotted = jQuery('#uallotted_all').val();
        var data_uallotted = arr_uallotted.split('-');
        var uallotted = data_uallotted[0];

        var arr_simteamid = jQuery('#uteamid_returnall').val();
        var data_simteamid = arr_simteamid.split('-');
        var simteamid = data_simteamid[0];

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {uallotted_all: uallotted, simteamid_all: simteamid},
            dataType: 'html',
            success: function (html) {
                jQuery("#simready_all_td").html('');
                jQuery("#simready_all_td").append(html);
            }
        });
        return false;
    }

    function  ValidateForm() {

        var uteamid = $("#uteamid").val();
        var uteamid_new = $("#uteamid_new").val();
        if (uteamid == "0") {
            alert("Please select Allot From");
        } else if (uteamid_new == "0") {
            alert("Please select Allot To");
        } else {
            $("#myformreallot").submit();
        }
    }

    function ValidateForm2() {
        var uteamid_returnall = $("#uteamid_returnall").val();
        if (uteamid_returnall == 0) {
            alert("Please select Return From.");
            return false;
        } else {
            $("#myformreturn").submit();
        }


    }
    function refreshFun() {
        jQuery("#unitno").autocomplete({
            source: "route_ajax.php?auto=getUnit",
            select: function (event, ui) {
                insertUnitDiv(ui.item.value, ui.item.uid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }
    function insertUnitDiv(selected_Unit, unitids) {
        $("#allUnitNo").val(function (i, val) {
            if (!val.includes(unitids)) {
                return val + (!val ? '' : ',') + unitids;
            } else {
                return val;
            }
        });

        if (unitids != "" && jQuery('#em_vehicle_div_' + unitids).val() == null) {
            var div = document.createElement('div');
            div.id = "contain";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';

            remove_image.onclick = function () {
                removeUnitDiv(selected_Unit, unitids);
            };

            div.className = 'recipientbox';
            div.id = 'em_vehicle_div_' + unitids;
            div.innerHTML = '<span>' + selected_Unit + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + unitids + '" value="' + unitids + '"/>';
            jQuery("#listUnit").append(div);
            jQuery(div).append(remove_image);
        }
    }
    function removeUnitDiv(unit, unitids) {
        var rep = "," + unitids;
        $("#allUnitNo").val($("#allUnitNo").val().replace(rep, ""));
        $("#allUnitNo").val($("#allUnitNo").val().replace(unitids, ""));
        $('#em_vehicle_div_' + unitids).remove();
        console.log($("#allUnitNo").val());
    }

    function refreshSim() {

        jQuery("#simno").autocomplete({
            source: "route_ajax.php?autoSim=getSim",
            select: function (event, ui) {
                insertSimDiv(ui.item.value, ui.item.simid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }
    function insertSimDiv(selected_Sim, simids) {
        $("#allSimNo").val(function (i, val) {
            if (!val.includes(simids)) {
                return val + (!val ? '' : ',') + simids;
            } else {
                return val;
            }
        });

        if (simids != "" && jQuery('#em_vehicle_div_' + simids).val() == null) {
            var div = document.createElement('div');
            div.id = "contain1";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';

            remove_image.onclick = function () {
                removeSimDiv(selected_Sim, simids);
            };

            div.className = 'recipientbox';
            div.id = 'em_vehicle_div_' + simids;
            div.innerHTML = '<span>' + selected_Sim + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + simids + '" value="' + simids + '"/>';
            jQuery("#listSim").append(div);
            jQuery(div).append(remove_image);
        }
    }
    function removeSimDiv(unit, simids) {
        var rep = "," + simids;
        $("#allSimNo").val($("#allSimNo").val().replace(rep, ""));
        $("#allSimNo").val($("#allSimNo").val().replace(simids, ""));
        $('#em_vehicle_div_' + simids).remove();
        console.log($("#allSimNo").val());
    }

    function getUnit() {
        var data = $('#ReAllotTeam').val();

        jQuery("#unitnumber").autocomplete({
            source: "route_ajax.php?uteamid=" + data,
            select: function (event, ui) {
                insertUnit(ui.item.value, ui.item.uid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }
    function insertUnit(selected_Sim, uid) {
        $("#reAllUnitNo").val(function (i, val) {
            if (!val.includes(uid)) {
                return val + (!val ? '' : ',') + uid;
            } else {
                return val;
            }
        });

        if (uid != "" && jQuery('#em_vehicle_div_' + uid).val() == null) {
            var div = document.createElement('div');
            div.id = "contain1";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';

            remove_image.onclick = function () {
                removeUnit(selected_Sim, uid);
            };

            div.className = 'recipientbox';
            div.id = 'em_vehicle_div_' + uid;
            div.innerHTML = '<span>' + selected_Sim + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + uid + '" value="' + uid + '"/>';
            jQuery("#reListUnit").append(div);
            jQuery(div).append(remove_image);
        }
    }
    function removeUnit(unit, uid) {
        var rep = "," + uid;
        $("#reAllUnitNo").val($("#reAllUnitNo").val().replace(rep, ""));
        $("#reAllUnitNo").val($("#reAllUnitNo").val().replace(uid, ""));
        $('#em_vehicle_div_' + uid).remove();
        console.log($("#reAllUnitNo").val());
    }

    function getSim() {
        var data = $('#ReAllotTeam').val();

        jQuery("#simnumber").autocomplete({
            source: "route_ajax.php?steamid=" + data,
            select: function (event, ui) {
                insertSim(ui.item.value, ui.item.sid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }
    function insertSim(selected_Sim, sid) {
        $("#reAllSimNo").val(function (i, val) {
            if (!val.includes(sid)) {
                return val + (!val ? '' : ',') + sid;
            } else {
                return val;
            }
        });

        if (sid != "" && jQuery('#em_vehicle_div_' + sid).val() == null) {
            var div = document.createElement('div');
            div.id = "contain1";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';

            remove_image.onclick = function () {
                removeSim(selected_Sim, sid);
            };

            div.className = 'recipientbox';
            div.id = 'em_vehicle_div_' + sid;
            div.innerHTML = '<span>' + selected_Sim + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + sid + '" value="' + sid + '"/>';
            jQuery("#reListSim").append(div);
            jQuery(div).append(remove_image);
        }
    }
    function removeSim(unit, sid) {
        var rep = "," + sid;
        $("#reAllSimNo").val($("#reAllSimNo").val().replace(rep, ""));
        $("#reAllSimNo").val($("#reAllSimNo").val().replace(sid, ""));
        $('#em_vehicle_div_' + sid).remove();
        console.log($("#reAllSimNo").val());
    }

    function getUnitNo() {
        var data = $('#ReturnTeam').val();

        jQuery("#uallotted_all").autocomplete({
            source: "route_ajax.php?uteamid_returnall=" + data,
            select: function (event, ui) {
                insertUnitNo(ui.item.value, ui.item.uid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }
    function insertUnitNo(selected_Sim, uid) {
        $("#reUnitAllNo").val(function (i, val) {
            if (!val.includes(uid)) {
                return val + (!val ? '' : ',') + uid;
            } else {
                return val;
            }
        });

        if (uid != "" && jQuery('#em_vehicle_div_' + uid).val() == null) {
            var div = document.createElement('div');
            div.id = "contain1";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';

            remove_image.onclick = function () {
                removeUnitNo(selected_Sim, uid);
            };

            div.className = 'recipientbox';
            div.id = 'em_vehicle_div_' + uid;
            div.innerHTML = '<span>' + selected_Sim + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + uid + '" value="' + uid + '"/>';
            jQuery("#reUnitList").append(div);
            jQuery(div).append(remove_image);
        }
    }
    function removeUnitNo(unit, uid) {
        var rep = "," + uid;
        $("#reUnitAllNo").val($("#reUnitAllNo").val().replace(rep, ""));
        $("#reUnitAllNo").val($("#reUnitAllNo").val().replace(uid, ""));
        $('#em_vehicle_div_' + uid).remove();
        console.log($("#reUnitAllNo").val());
    }

    function getSimNo() {
        var data = $('#ReturnTeam').val();

        jQuery("#simallotted_all").autocomplete({
            source: "route_ajax.php?steamid_all=" + data,
            select: function (event, ui) {
                insertSimNo(ui.item.value, ui.item.sid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }
    function insertSimNo(selected_Sim, sid) {
        $("#reSimAllNo").val(function (i, val) {
            if (!val.includes(sid)) {
                return val + (!val ? '' : ',') + sid;
            } else {
                return val;
            }
        });

        if (sid != "" && jQuery('#em_vehicle_div_' + sid).val() == null) {
            var div = document.createElement('div');
            div.id = "contain1";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';

            remove_image.onclick = function () {
                removeSimNo(selected_Sim, sid);
            };

            div.className = 'recipientbox';
            div.id = 'em_vehicle_div_' + sid;
            div.innerHTML = '<span>' + selected_Sim + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + sid + '" value="' + sid + '"/>';
            jQuery("#reSimList").append(div);
            jQuery(div).append(remove_image);
        }
    }
    function removeSimNo(unit, sid) {
        var rep = "," + sid;
        $("#reSimAllNo").val($("#reSimAllNo").val().replace(rep, ""));
        $("#reSimAllNo").val($("#reSimAllNo").val().replace(sid, ""));
        $('#em_vehicle_div_' + sid).remove();
        console.log($("#reSimAllNo").val());
    }
    function getUnitNotAssign() {
        jQuery("#unitnotassign").autocomplete({
            source: "route_ajax.php?notassignsim=getunit",
            select: function (event, ui) {
                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#unitassign').val(ui.item.uid);
                return false;
            }
        });
    }
    function validate() {
        var unit = jQuery('#unitassign').val();
        var sim = jQuery('#simassign').val();
        if (unit == '' || unit == '0') {
            jQuery('#errorU').show();
            jQuery('#errorU').fadeOut(3000);
            return false;
        } else if (sim == '' || sim == '0') {
            jQuery('#errorS').show();
            jQuery('#errorS').fadeOut(3000);
            return false;
        } else {
            jQuery('#myformassign').submit();
        }
    }
    function validateRemForm() {
        var unit = jQuery('#unitRemove').val();
        if (unit == '' || unit == '0') {
            jQuery('#errorUnit').show();
            jQuery('#errorUnit').fadeOut(3000);
            return false;
        } else {
            jQuery('#myformRemove').submit();
        }
    }
    function getSimNotAssign() {
        jQuery("#simnotassign").autocomplete({
            source: "route_ajax.php?assignsim=getunit",
            select: function (event, ui) {
                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#simassign').val(ui.item.sid);
                return false;
            }
        });
    }
    function getUnitToRemove() {
        jQuery("#unitToRemove").autocomplete({
            source: "route_ajax.php?removeSim=getunit",
            select: function (event, ui) {
                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#unitRemove').val(ui.item.uid);
                return false;
            }
        });
    }

    jQuery(document).ready(function () {
        jQuery('#removesuccess').show();
        jQuery('#removesuccess').fadeOut(6000);
    });
</script>
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
    #mail_pop{
        position: absolute;
        top:300px;
    }
</style>
