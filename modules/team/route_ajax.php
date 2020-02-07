<?php
error_reporting(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');
include_once "session.php";
include_once "loginorelse.php";
include_once "../../constants/constants.php";
include_once "db.php";
include_once "../../lib/components/gui/datagrid.php";
include_once '../../lib/autoload.php';
include_once "../../lib/system/DatabaseManager.php";
include_once "../../lib/system/Sanitise.php";
include '../../lib/bo/simple_html_dom.php';

class testing {

}

// -------------------------------------------------------------------- Pull for Allotment ------------------------------------------------------ //
$todaysdate = date('Y-m-d H:i:s');
if (isset($_REQUEST['uteamid'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $teamid = GetSafeValueString($_REQUEST['uteamid'], "string");
    $db = new DatabaseManager();
    if ($teamid != '0' && !empty($teamid)) {
        $SQL = sprintf("SELECT unit.unitno, unit.uid FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (18,20) AND unit.teamid=%d AND unit.unitno LIKE '%s'", $teamid, $term);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $data = Array();
            while ($row = $db->get_nextRow()) {
                $json["value"] = $row["unitno"];
                $json['uid'] = $row["uid"];
                array_push($data, $json);
            }
            $data = json_encode($data);
        }
        echo $data;
    }
}

if (isset($_REQUEST['steamid'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $teamid = GetSafeValueString($_REQUEST['steamid'], "string");
    $db = new DatabaseManager();

    if ($teamid != '0' && !empty($teamid)) {
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=%d AND simcard.simcardno LIKE '%s'", $teamid, $term);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $data = Array();
            while ($row = $db->get_nextRow()) {
                $json["value"] = $row["simcardno"];
                $json['sid'] = $row["simid"];
                array_push($data, $json);
            }
            $data = json_encode($data);
        }
        echo $data;
    }
}

if (isset($_POST['uallotted']) && isset($_POST['simteamid'])) {
    $unitid = GetSafeValueString($_POST['uallotted'], "string");
    $teamid = GetSafeValueString($_POST['simteamid'], "string");
    $simcardid_compare = 0;
    $db = new DatabaseManager();

    $status = "<select name='simallotted'>";

    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM devices INNER JOIN simcard ON simcard.id = devices.simcardid INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE devices.simcardid <> 0 AND devices.uid = " . $unitid . " LIMIT 1");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcardid_compare = $row["simid"];
            $status .= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
        }
    }

    $status .= "<option value='0'>No Simcard</option>";
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
    $db->executeQuery($SQL);
    $units = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            if ($row["simid"] != $simcardid_compare) {
                $status .= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status .= "</select>( Allotted Simcard List with selected Elixir)";
    echo $status;
}

// -------------------------------------------------------------------- Pull for Return ------------------------------------------------------ //

if (isset($_REQUEST['uteamid_returnall'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $teamid = GetSafeValueString($_REQUEST['uteamid_returnall'], "string");
    $db = new DatabaseManager();

    if ($teamid != '0' && !empty($teamid)) {
        $SQL = sprintf("SELECT unit.unitno, unit.uid FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (18,20) AND unit.teamid=%d AND unit.unitno LIKE '%s'", $teamid, $term);

        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $data = Array();
            while ($row = $db->get_nextRow()) {
                $json["value"] = $row["unitno"];
                $json['uid'] = $row["uid"];
                array_push($data, $json);
            }
            $data = json_encode($data);
        }
        echo $data;
    }
}

if (isset($_REQUEST['steamid_all'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $teamid = GetSafeValueString($_REQUEST['steamid_all'], "string");
    $db = new DatabaseManager();

    if ($teamid != '0' && !empty($teamid)) {
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=%d AND simcard.simcardno LIKE '%s'", $teamid, $term);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $data = Array();
            while ($row = $db->get_nextRow()) {
                $json["value"] = $row["simcardno"];
                $json['sid'] = $row["simid"];
                array_push($data, $json);
            }
            $data = json_encode($data);
        }
        echo $data;
    }
}

if (isset($_POST['uallotted_all']) && isset($_POST['simteamid_all'])) {
    $unitid = GetSafeValueString($_POST['uallotted_all'], "string");
    $teamid = GetSafeValueString($_POST['simteamid_all'], "string");
    $simcardid_compare = 0;
    $db = new DatabaseManager();

    $status = "<select name='simallotted_all' id='simallotted_all'>";

    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM devices INNER JOIN simcard ON simcard.id = devices.simcardid INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE devices.simcardid <> 0 AND devices.uid = " . $unitid . " LIMIT 1");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcardid_compare = $row["simid"];
            $status .= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
        }
    }

    $status .= "<option value='0'>No Simcard</option>";
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
    $db->executeQuery($SQL);
    $units = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            if ($row["simid"] != $simcardid_compare) {
                $status .= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status .= "</select>( Allotted / Bad Simcard List with selected Elixir)";
    echo $status;
}

// -------------------------------------------------------------------- Pull for Replace ------------------------------------------------------ //

if (isset($_POST['uteamid_replace'])) {
    $teamid = GetSafeValueString($_POST['uteamid_replace'], "string");
    $customerno = GetSafeValueString($_POST['customer_id'], "string");
    $db = new DatabaseManager();

    $status .= "<select name='ureplacenewunit' id='ureplacenewunit'><option value='0'>No Unit</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (18,20) AND unit.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status .= "<option value='" . $row["uid"] . "'>" . $row["unitno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
        $SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (22) AND customerno=" . $customerno);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status .= "<option value='" . $row["uid"] . "'>" . $row["unitno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status .= "</select>( Allotted Device List with selected Elixir)";
    echo $status;
}

if (isset($_POST['steamid_replace'])) {
    $teamid = GetSafeValueString($_POST['steamid_replace'], "string");
    $db = new DatabaseManager();

    $status .= "<select name='ureplacenewsimcard' id='ureplacesimcard'><option value='0'>No Simcard</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status .= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status .= "</select>( Allotted Simcard List with selected Elixir)";
    echo $status;
}

// -------------------------------------------------------------------- Pull for Replace Both------------------------------------------------------ //

if (isset($_POST['uteamid_replace_both'])) {
    $teamid = GetSafeValueString($_POST['uteamid_replace_both'], "string");
    $db = new DatabaseManager();

    $status .= "<select name='uallotted_replace_both' id='uallotted_replace_both' onChange='pullsimcard_from_unit_replace_both();'><option value='0'>No Unit</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (18,20) AND unit.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status .= "<option value='" . $row["uid"] . "'>" . $row["unitno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status .= "</select>( Allotted Device List with selected Elixir)";
    echo $status;
}
if (isset($_REQUEST["unit_repl"])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $teamid = GetSafeValueString($_REQUEST['unit_repl'], "string");
    $db = new DatabaseManager();
    if ($teamid != '0' && !empty($teamid)) {
        $SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (18,20) AND unit.teamid=%d AND unit.unitno LIKE '%s'", $teamid, $term);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $data = Array();
            while ($row = $db->get_nextRow()) {
                $json["value"] = $row["unitno"];
                $json['uid'] = $row["uid"];
                array_push($data, $json);
            }
            $data = json_encode($data);
        }
        echo $data;
    }
}
if (isset($_REQUEST["simcard_repl"])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $teamid = GetSafeValueString($_REQUEST['simcard_repl'], "string");
    $unitid = GetSafeValueString($_REQUEST['unitid'], "string");
    $db = new DatabaseManager();
    $simcardid_compare = 0;
    if (isset($teamid) && isset($unitid)) {
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM devices INNER JOIN simcard ON simcard.id = devices.simcardid INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE devices.simcardid <> 0 AND simcard.simcardno LIKE '%s' AND devices.uid = %d LIMIT 1", $term, $unitid);
        $db->executeQuery($SQL);
        $data = array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $simcardid_compare = $row["simid"];
                $json["value"] = $row["simcardno"] . "[ " . $row["status"] . " ]";
                $json['sid'] = $row["simid"];
                array_push($data, $json);
            }
        }
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.simcardno LIKE '%s' AND simcard.teamid=%d", $term, $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                if ($row["simid"] != $simcardid_compare) {
                    $json["value"] = $row["simcardno"] . "[ " . $row["status"] . " ]";
                    $json['sid'] = $row["simid"];
                    array_push($data, $json);
                }
            }
            $data = json_encode($data);
        }
        echo $data;
    } elseif (isset($teamid)) {
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $data = array();
            while ($row = $db->get_nextRow()) {
                $simcardid_compare = $row["simid"];
                $json["value"] = $row["simcardno"] . "[ " . $row["status"] . " ]";
                $json['sid'] = $row["simid"];
                array_push($data, $json);
            }
            $data = json_encode($data);
        }
        echo $data;
    }
}

if (isset($_POST['steamid_replace_both'])) {
    $teamid = GetSafeValueString($_POST['steamid_replace_both'], "string");
    $db = new DatabaseManager();

    $status .= "<select name='simallotted_replace_both' id='simallotted_replace_both'><option value='0'>No Simcard</option>";
    if ($teamid != '0') {
        $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
        $db->executeQuery($SQL);
        $units = Array();
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $status .= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status .= "</select>( Allotted Simcard List with selected Elixir)";
    echo $status;
}

if (isset($_POST['uallotted_replace_both']) && isset($_POST['simteamid_replace_both'])) {
    $unitid = GetSafeValueString($_POST['uallotted_replace_both'], "string");
    $teamid = GetSafeValueString($_POST['simteamid_replace_both'], "string");
    $simcardid_compare = 0;
    $db = new DatabaseManager();

    $status = "<select name='simallotted_replace_both' id='simallotted_replace_both'>";

    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM devices INNER JOIN simcard ON simcard.id = devices.simcardid INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE devices.simcardid <> 0 AND devices.uid = " . $unitid . " LIMIT 1");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $simcardid_compare = $row["simid"];
            $status .= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
        }
    }

    $status .= "<option value='0'>No Simcard</option>";
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (19,21) AND simcard.teamid=" . $teamid);
    $db->executeQuery($SQL);
    $units = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            if ($row["simid"] != $simcardid_compare) {
                $status .= "<option value='" . $row["simid"] . "'>" . $row["simcardno"] . "[ " . $row["status"] . " ]</option>";
            }
        }
    }
    $status .= "</select>( Allotted Simcard List with selected Elixir)";
    echo $status;
}

/////////////////// get transmitter//////////////////////
if (isset($_POST['work']) && $_POST['work'] == 'alltransmitter') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $sp_params1 = "''"
            . ",''"
            . ",'" . 0 . "'"
            . ",'" . 2 . "'"
            . ",'" . 0 . "'"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $res1 = $pdo->query($QUERY1);
    $status = "<select name = 'alltrans' id = 'alltrans'>";
    $status .= "<option value='0'>Select Transmitter</option>";
    if ($res1) {
        while ($row = $res1->fetch(PDO::FETCH_ASSOC)) {
            $status .= "<option value='" . $row["transmitterid"] . "'>" . $row["transmitterno"] . "[" . $row["status"] . "]" . "</option>";
        }
    }
    $status .= "</select>";
    $db->ClosePDOConn($pdo);
    echo $status;
} elseif (isset($_POST['work']) && $_POST['work'] == 'transmitter_byteamid') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $transmittertype = GetSafeValueString($_POST['transtypeid'], "int");
    $alloted_teamid = GetSafeValueString($_POST['allottedid'], "string");
    $custno = "0";
    $sp_params1 = "''"
            . ",''"
            . ",'" . $alloted_teamid . "'"
            . ",''"
            . ",'" . $custno . "'"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $res1 = $pdo->query($QUERY1);
    $status = "<select name='transallotted" . $transmittertype . "' id='transallotted" . $transmittertype . "'>";
    $status .= "<option value='0'>No Transmitter</option>";
    if ($res1) {
        while ($row = $res1->fetch(PDO::FETCH_ASSOC)) {
            $status .= "<option value='" . $row["transmitterid"] . "'>" . $row["transmitterno"] . "[" . $row["status"] . "]" . "</option>";
        }
    }
    $status .= "</select>";
    $db->ClosePDOConn($pdo);
    echo $status;
} elseif (isset($_POST['work']) && $_POST['work'] == 'allot_transmitter') {
    $loginid = GetLoggedInUserId();
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $alloted_teamid = GetSafeValueString($_POST['allottedid'], "string");
    $trans_status = '18';
    $transid = GetSafeValueString($_POST['transid'], "string");
    $transno = GetSafeValueString($_POST['transno'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $status = "Alloted Successfully";
    try {
        $sp_params1 = "'" . $transid . "'"
                . ",'" . $alloted_teamid . "'"
                . ",'" . $trans_status . "'"
                . ",'" . $comments . "'"
                . ",'" . 0 . "'"
                . ",'" . $todaysdate . "'"
                . ",'" . $loginid . "'"
        ;
        $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
        $pdo->query($QUERY1);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    echo $status;
} elseif (isset($_POST['work']) && $_POST['work'] == 'reallot_transmitter') {
    $loginid = GetLoggedInUserId();
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $alloted_teamid = GetSafeValueString($_POST['allottedid'], "string");
    $trans_status = '18';
    $transid = GetSafeValueString($_POST['transid'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $transno = GetSafeValueString($_POST['transno'], "string");
    $status = "Realloted Successfully";
    try {
        $sp_params1 = "'" . $transid . "'"
                . ",'" . $alloted_teamid . "'"
                . ",'" . $trans_status . "'"
                . ",'" . $comments . "'"
                . ",'" . 0 . "'"
                . ",'" . $todaysdate . "'"
                . ",'" . $loginid . "'"
        ;
        $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
        $pdo->query($QUERY1);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    echo $status;
} elseif (isset($_POST['work']) && $_POST['work'] == 'inRepairtransmitter') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $trans_status = "7";
    $custno = "-1";
    $sp_params1 = "''"
            . ",''"
            . ",''"
            . ",'" . $trans_status . "'"
            . ",'" . $custno . "'"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $res1 = $pdo->query($QUERY1);
    $status .= "<select name='transrepair' id='transrepair'>";
    if ($res1->rowCount() > 0) {
        while ($row = $res1->fetch(PDO::FETCH_ASSOC)) {
            $status .= "<option value='" . $row["transmitterid"] . "'>" . $row["transmitterno"] . "[" . $row["status"] . "]" . "</option>";
        }
    } else {
        $status .= "<option value = '0'> No Transmitters In Repair </option>";
    }
    $status .= "</select>";
    $status .= "(Under Repair Device List)";
    $db->ClosePDOConn($pdo);
    echo $status;
} elseif (isset($_POST['work']) && $_POST['work'] == 'gettransmitterbystatus') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $status = GetSafeValueString($_POST['status'], "string");
    $sp_params1 = "''"
            . ",''"
            . ",'" . 0 . "'"
            . ",'" . $status . "'"
            . ",''"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $res1 = $pdo->query($QUERY1);
    $html = '';
    $html .= "<select name='trans_sendrepair' id='trans_sendrepair'>";
    if ($res1->rowCount() > 0) {
        while ($row = $res1->fetch(PDO::FETCH_ASSOC)) {
            $html .= "<option value='" . $row["transmitterid"] . "'>" . $row["transmitterno"] . "[" . $row["status"] . "]" . "</option>";
        }
    } else {
        $html .= "<option value='0'>No Transmitters</option>";
    }
    $html .= "</select>";
    $html .= "(Fresh / Repaired / Suspected Device List)";
    $db->ClosePDOConn($pdo);
    echo $html;
} elseif (isset($_POST['work']) && $_POST['work'] == 'trans_testing') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $loginid = GetLoggedInUserId();
    $trans_status = GetSafeValueString($_POST['trans_status'], "string");
    $transid = GetSafeValueString($_POST['transid'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $transno = GetSafeValueString($_POST['transno'], "string");
    $status = "Updated Successfully";
    try {
        $sp_params1 = "'" . $transid . "'"
                . ",'" . 0 . "'"
                . ",'" . $trans_status . "'"
                . ",'" . $comments . "'"
                . ",'" . 0 . "'"
                . ",'" . $todaysdate . "'"
                . ",'" . $loginid . "'"
        ;
        $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
        $pdo->query($QUERY1);
        $db->ClosePDOConn($pdo);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    echo $status;
} elseif (isset($_POST['work']) && $_POST['work'] == 'sendTransmitterTorepair') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $sp_params1 = "''"
            . ",''"
            . ",''"
            . ",'" . 3 . "'"
            . ",'" . 0 . "'"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $res1 = $pdo->query($QUERY1);
    $status .= "<select name='transrepairsend' id='transrepairsend'>";

    if ($res1->rowCount() > 0) {
        while ($row = $res1->fetch(PDO::FETCH_ASSOC)) {
            $status .= "<option value='" . $row["transmitterid"] . "'>" . $row["transmitterno"] . "[" . $row["status"] . "]" . "</option>";
        }
    } else {
        $status .= "<option value = '0'> No Bad Transmitters </option>";
    }
    $status .= "</select>";
    $status .= "(Under Repair Device List)";
    $db->ClosePDOConn($pdo);
    echo $status;
} elseif (isset($_POST['work']) && $_POST['work'] == 'saveSendTorepair') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $loginid = GetLoggedInUserId();
    $transid = GetSafeValueString($_POST['transid'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $custno = "-1";
    $teamid = "0";
    $trans_status = "7";
    $status = "Updated Successfully";
    try {
        $sp_params1 = "'" . $transid . "'"
                . ",'" . $teamid . "'"
                . ",'" . $trans_status . "'"
                . ",'" . $comments . "'"
                . ",'" . $custno . "'"
                . ",'" . $todaysdate . "'"
                . ",'" . $loginid . "'"
        ;
        $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
        $pdo->query($QUERY1);
        $db->ClosePDOConn($pdo);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    $db->ClosePDOConn($pdo);
    echo $status;
} elseif (isset($_POST['work']) && $_POST['work'] == 'saveReceiverepair') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $loginid = GetLoggedInUserId();
    $transid = GetSafeValueString($_POST['transid'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $custno = "0";
    $teamid = "0";
    $trans_status = "4";
    $status = "Updated Successfully";
    try {
        $sp_params1 = "'" . $transid . "'"
                . ",'" . $teamid . "'"
                . ",'" . $trans_status . "'"
                . ",'" . $comments . "'"
                . ",'" . $custno . "'"
                . ",'" . $todaysdate . "'"
                . ",'" . $loginid . "'"
        ;
        $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
        $pdo->query($QUERY1);
        $db->ClosePDOConn($pdo);
    } catch (Exception $e) {
        $status = "Caught exception: " . $e->getMessage();
    }
    $db->ClosePDOConn($pdo);
    echo $status;
} elseif (isset($_POST['work']) && $_POST['work'] == 'saveReplacerepairtrans') {
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $loginid = GetLoggedInUserId();
    $transid = GetSafeValueString($_POST['transid'], "string");
    $newtransno = GetSafeValueString($_POST['newtransno'], "string");
    $comments = GetSafeValueString($_POST['comments'], "string");
    $custno = "0";
    $teamid = "0";
    $trans_status = "4";
    //check if transmitterno exists
    $sp_params1 = "''"
            . ",'" . $newtransno . "'"
            . ",''"
            . ",''"
            . ",''"
    ;
    $QUERY1 = $db->PrepareSP('get_transmitter', $sp_params1);
    $transList = $pdo->query($QUERY1);
    if ($transList->rowCount() > 0) {
        $status = "Transmitter Already Exists";
    } else {

        try {
            $status = "Updated Successfully";
            $pdo1 = $db->CreatePDOConn();

            $sp_params2 = "'" . $transid . "'"
                    . ",'" . $teamid . "'"
                    . ",'" . 9 . "'"
                    . ",'" . $comments . "'"
                    . ",'" . -1 . "'"
                    . ",'" . $todaysdate . "'"
                    . ",'" . $loginid . "'"
            ;
            $QUERY2 = $db->PrepareSP('update_transmitter', $sp_params2);
            $pdo1->query($QUERY2);
            $db->ClosePDOConn($pdo1);
            // insert new transmitter
            $pdo3 = $db->CreatePDOConn();
            $sp_params3 = "'" . $newtransno . "'"
                    . ",'" . $teamid . "'"
                    . ",'" . $custno . "'"
                    . ",'" . 1 . "'"
                    . ",'" . $todaysdate . "'"
                    . ",'" . $loginid . "'"
                    . ",'" . $comments . "'"
            ;
            $QUERY3 = $db->PrepareSP('insert_transmitter', $sp_params3);
            $pdo3->query($QUERY3);
            $db->ClosePDOConn($pdo3);
        } catch (Exception $e) {
            $status = "Caught exception: " . $e->getMessage();
        }
    }
    $db->ClosePDOConn($pdo);
    echo $status;
} elseif (isset($_POST['work']) && $_POST['work'] == 'allottrans') {
    //print_r($_POST);
    $status = 'Not Alloted';
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $loginid = GetLoggedInUserId();
    $unitid = GetSafeValueString($_POST['unitid'], "int");
    $vehid = GetSafeValueString($_POST['vehicleid'], "int");
    $trans1teamid = GetSafeValueString($_POST['trans1teamid'], "int");
    $trans2teamid = GetSafeValueString($_POST['trans2teamid'], "int");
    $transallotted1 = GetSafeValueString($_POST['transallotted1'], "string");
    $transallotted2 = GetSafeValueString($_POST['transallotted2'], "string");
    $trans2comments = GetSafeValueString($_POST['trans2comments'], "string");
    $trans1comments = GetSafeValueString($_POST['trans1comments'], "string");
    $custno = GetSafeValueString($_POST['customerid'], "string");
    $teamid = "0";
    $trans_status = "5";
    if ($transallotted1 != '' && $transallotted1 != '0') {
        try {
            $status = "Alloted Transmitter1 Successfully";
            $pdo1 = $db->CreatePDOConn();

            $sp_params1 = "'" . $transallotted1 . "'"
                    . ",'" . $teamid . "'"
                    . ",'" . $trans_status . "'"
                    . ",'" . $trans1comments . "'"
                    . ",'" . $custno . "'"
                    . ",'" . $todaysdate . "'"
                    . ",'" . $loginid . "'"
            ;
            $QUERY1 = $db->PrepareSP('update_transmitter', $sp_params1);
            $pdo1->query($QUERY1);
            $db->ClosePDOConn($pdo1);
        } catch (Exception $e) {
            $status = "Caught exception: " . $e->getMessage();
        }
        $SQL1 = sprintf("UPDATE vehicle SET transmitter1=%d WHERE vehicleid=%d AND customerno=%d", $transallotted1, $vehid, $custno);
        $db->executeQuery($SQL1);
    }
    if ($transallotted2 != '' && $transallotted2 != '0') {
        try {
            $status = "Alloted Transmitter 1&2 Successfully";
            $pdo2 = $db->CreatePDOConn();

            $sp_params2 = "'" . $transallotted2 . "'"
                    . ",'" . $teamid . "'"
                    . ",'" . $trans_status . "'"
                    . ",'" . $trans2comments . "'"
                    . ",'" . $custno . "'"
                    . ",'" . $todaysdate . "'"
                    . ",'" . $loginid . "'"
            ;
            $QUERY2 = $db->PrepareSP('update_transmitter', $sp_params2);
            $pdo2->query($QUERY2);
            $db->ClosePDOConn($pdo2);
        } catch (Exception $e) {
            $status = "Caught exception: " . $e->getMessage();
        }
        $SQL2 = sprintf("UPDATE vehicle SET transmitter2=%d WHERE vehicleid=%d AND customerno=%d", $transallotted2, $vehid, $custno);
        $db->executeQuery($SQL2);
    }
    $db->ClosePDOConn($pdo);
    echo $status;
}

if (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == "genaratecode") {
        $i = 0;
        $tmp = mt_rand(1, 9);
        do {
            $tmp .= mt_rand(0, 9);
        } while (++$i < 14);
        echo '0' . $tmp;
    }

    if ($_REQUEST['action'] == "purchasetrace") {
        $arr_p = array();
        $message = "";
        $punitno = $_REQUEST["unitnumber"];
        $devicetype = $_REQUEST["unittypeid"];
        $comments = $_REQUEST["comments"];
        $activation_code = $_REQUEST["activationcode"];

        if ($devicetype == 3) {
            $punitno = $_REQUEST["unitnumber"];
        } else {
            $punitno = '0' . $_REQUEST["unitnumber"];
        }

        $db = new DatabaseManager();
        $sql = sprintf("SELECT `unitno` FROM " . DB_Trace . ".`unit` WHERE unitno='" . $punitno . "'");
        $db->executeQuery($sql);
        if ($db->get_rowCount() > 0) {
            $message = "Unit Number Already exists.";
        } elseif ($message == "") {
            $SQLUnit = sprintf("INSERT INTO " . DB_Trace . ".`unit`(`customerno`,`unitno`,`trans_statusid`,`comments`,`unittypeid`,created_on) VALUES (1,'%s',1,'%s','%d','%s')", $punitno, $comments, $devicetype, $todaysdate);
            $db->executeQuery($SQLUnit);
            $unitid = $db->get_insertedId();
            // Populate Devices
            $devicekey = mt_rand();
            $expiry = date('Y-m-d', strtotime('+1 year'));
            $SQL = sprintf("INSERT INTO " . DB_Trace . ".devices (`customerno` ,`devicekey`,`registeredon`,`uid`,`expirydate`)VALUES (1, '%s', '%s', '%d', '%s')", $devicekey, Sanitise::DateTime($todaysdate), $unitid, $expiry);
            $db->executeQuery($SQL);

            // Populate Vehicles
            $Query = "INSERT INTO " . DB_Trace . ".vehicle (vehicleno,customerno, uid) VALUES ('%s',%d, %d)";
            $SQL = sprintf($Query, 'Not Allocated', 1, $unitid);
            $db->executeQuery($SQL);
            $vehicleid = $db->get_insertedId();

            //$activation_code = rand(1000,9000);
            // Update Unit
            $SQL = sprintf("UPDATE " . DB_Trace . ".unit SET activationcode ='%s', vehicleid=%d where uid=%d", $activation_code, $vehicleid, $unitid);
            $db->executeQuery($SQL);

            //Insert into trace unitcommand details

            $SQL = sprintf("INSERT INTO " . DB_Trace . ".unitCommandDetails (`uid` ,`customerno`,`created_on`)VALUES ('%d', '%d', '%s')", $unitid, 1, Sanitise::DateTime($todaysdate));
            $db->executeQuery($SQL);

            // Create unit directory
            $relativepath = "../../../trace";
            if (!is_dir($relativepath . '/customer/1/unitno/' . $punitno)) {
                // Directory doesn't exist.
                mkdir($relativepath . '/customer/1/unitno/' . $punitno, 0777, true) or die("Could not create directory");
            }

            if (!is_dir($relativepath . '/customer/1/unitno/' . $punitno . '/sqlite')) {
                // Directory doesn't exist.
                mkdir($relativepath . '/customer/1/unitno/' . $punitno . '/sqlite', 0777, true) or die("Could not create directory");
            }
        }

        if (!empty($message)) {
            $arr_p['status'] = "failure";
            $arr_p['message'] = $message;
        } else {
            $arr_p['status'] = "sucess";
            $arr_p['message'] = "Unit added sucessfully";
        }
        echo json_encode($arr_p);
    }
}
if (isset($_REQUEST['auto'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT unit.unitno, unit.uid FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (2) AND unit.unitno LIKE '%s'", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["unitno"];
            $json['uid'] = $row["uid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['autoSim'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (11) AND simcard.simcardno LIKE '%s'", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["simcardno"];
            $json['simid'] = $row["simid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['unitno'])) {
    $customernos = $_REQUEST['unitno'];
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  u.unitno
                            ,u.uid
                            ,v.vehicleno
                    FROM    unit u
                    INNER JOIN vehicle v ON v.vehicleid = u.vehicleid
                    WHERE   u.customerno = %d
                    AND     u.unitno LIKE '%s'", $customernos, $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["unitno"];
            $json['uid'] = $row["uid"];
            $json['vehicleno'] = $row["vehicleno"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['vehicleno'])) {
    $customernos = $_REQUEST['vehicleno'];
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT vh.vehicleno,unit.unitno,unit.uid FROM vehicle as vh INNER JOIN unit on unit.uid= vh.uid WHERE vh.customerno=%d AND vh.vehicleno LIKE '%s'", $customernos, $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["vehicleno"];
            $json['uid'] = $row["unitno"];
            $json['unitid'] = $row["uid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['customername'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT customercompany,customerno,unitprice,renewal FROM customer WHERE customercompany LIKE '%s' OR customerno LIKE '%s'", $term, $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["customerno"] . " - " . $row["customercompany"];
            $json['cid'] = $row["customerno"];
            $json['cname'] = $row["customercompany"];
            $json['unitprice'] = $row["unitprice"];
            $json['renewal'] = $row["renewal"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['ledgername'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $customerno = $_REQUEST['ledgername'];
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  l.ledgerid
                            ,l.ledgername
                            ,l.state_code
                    FROM    ledger l
                    INNER JOIN ledger_cust_mapping lc ON lc.ledgerid = l.ledgerid
                    WHERE   lc.customerno = %d
                    AND     lc.isdeleted = 0
                    AND     l.ledgername LIKE '%s'", Sanitise::Long($customerno), Sanitise::String($term));
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["ledgername"];
            $json['lid'] = $row["ledgerid"];
            $json['state_code'] = $row["state_code"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['notassignsim'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT ut.unitno,ut.uid FROM unit as ut INNER JOIN devices on devices.uid=ut.uid WHERE devices.simcardid=0 AND ut.unitno LIKE '%s'", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["unitno"];
            $json['uid'] = $row["uid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['assignsim'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno FROM simcard WHERE trans_statusid IN (11,19) AND simcard.simcardno LIKE '%s'", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["simcardno"];
            $json['sid'] = $row["simid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['removeSim'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT ut.unitno,ut.uid FROM unit as ut INNER JOIN devices on devices.uid=ut.uid WHERE devices.simcardid > 0 AND ut.unitno LIKE '%s'", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["unitno"];
            $json['uid'] = $row["uid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['receive_repaired'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  unit.unitno,unit.uid FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (7) AND unit.unitno LIKE '%s' ORDER BY unit.unitno, unit.uid ASC", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["unitno"];
            $json['uid'] = $row["uid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}

if (isset($_REQUEST['badDevice'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  unit.unitno,unit.uid,unit.issue FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (3) AND unit.unitno LIKE '%s' ORDER BY unit.unitno, unit.uid ASC", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["unitno"];
            $json['uid'] = $row["uid"];
            $json['issue'] = $row["issue"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}

if (isset($_REQUEST['disconnectSim'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (15) AND simcard.simcardno LIKE '%s' ORDER BY simcard.id asc", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["simcardno"];
            $json['sid'] = $row["simid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['testingUnits'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT unit.unitno, unit.uid FROM unit INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (1,4,17) AND unit.unitno LIKE '%s' ORDER BY unit.uid asc", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["unitno"];
            $json['uid'] = $row["uid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['badSim'])) {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno FROM simcard INNER JOIN " . DB_PARENT . ".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (12) AND simcard.simcardno LIKE '%s' order by simcard.simcardno asc", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["simcardno"];
            $json['sid'] = $row["simid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['action']) && $_REQUEST["action"] == "teamdata") {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT `email`,teamid FROM `team` WHERE `email` LIKE '%s' order by email asc", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["email"];
            $json["teamid"] = $row["teamid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['cno']) && isset($_REQUEST["uno"])) {
    $customerno = $_REQUEST['cno'];
    $unitno = $_REQUEST['uno'];
    $SDate = $_REQUEST['sdate'];
    $EDate = $_REQUEST['edate'];

    $totaldays = gendays_cmn($SDate, $EDate);
    $count1 = count($totaldays);
    if ($count1 == 1) {
        $location = "../../customer/" . $customerno . "/unitno/$unitno/sqlite/$totaldays[0].sqlite";
        if (file_exists($location)) {
            $path = "sqlite:$location";
            $db = new PDO($path);

            ob_start();
            $query = "SELECT * FROM data";
            $result = $db->query($query);
            $printdata = "<table><tr><th>Data Id</th><th>Data</th><th>Client</th><th>Inserted On</th></tr>";
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $printdata .= "<tr><td>" . $row['dataid'] . "</td><td>" . $row['data'] . "</td><td>" . $row['client'] . "</td><td>" . date('Y-m-d H:i:s',strtotime($row['insertedon'])). "</td></tr>";
                }
            }
            $printdata .= "</table>";
            $cat = display($printdata);
            $content = ob_get_clean();
            $html = str_get_html($content);
            $xls_filename = str_replace(' ', '', $unitno . '_' . date("d-M-Y", strtotime($totaldays[0])) . ".xls");
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$xls_filename");
            //echo $html;
            echo $content;
        } else {
            echo "error";
        }
    } elseif ($count1 > 1) {
        $printdata = "<table><tr><th>Data Id</th><th>Data</th><th>Client</th><th>Inserted On</th></tr>";
        foreach ($totaldays as $curdate) {
            $location = "../../customer/" . $customerno . "/unitno/$unitno/sqlite/$curdate.sqlite";
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $db = new PDO($path);

                ob_start();
                $query = "SELECT * FROM data";
                $result = $db->query($query);

                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $printdata .= "<tr><td>" . $row['dataid'] . "</td><td>" . $row['data'] . "</td><td>" . $row['client'] . "</td><td>" . date('Y-m-d H:i:s',strtotime($row['insertedon'])). "</td></tr>";
                    }
                }
            }
        }
        $printdata .= "</table>";
        $cat = display($printdata);
        $content = ob_get_clean();
        $html = str_get_html($content);
        $xls_filename = str_replace(' ', '', $unitno . '_' . date("d-M-Y", strtotime($curdate)) . ".xls");
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$xls_filename");
        //echo $html;
        echo $content;
    }
}

function gendays_cmn($STdate, $EDdate) {
    $TOTALDAYS = Array();
    $STdate = date("Y-m-d", strtotime($STdate));
    $EDdate = date("Y-m-d", strtotime($EDdate));
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $TOTALDAYS[] = $STdate;
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
    }
    return $TOTALDAYS;
}

if (isset($_REQUEST['action']) && $_REQUEST["action"] == "getteamdata") {
    $term = $_REQUEST['term'];
    $term = "%" . $term . "%";
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT `email`,teamid,name FROM `team` WHERE `name` LIKE '%s' order by name asc", $term);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        $data = Array();
        while ($row = $db->get_nextRow()) {
            $json["value"] = $row["email"];
            $json["teamid"] = $row["teamid"];
            array_push($data, $json);
        }
        $data = json_encode($data);
    }
    echo $data;
}
if (isset($_REQUEST['paymentDue']) && isset($_REQUEST["customerno"])) {
    $db = new DatabaseManager();
    $customerno = $_REQUEST['customerno'];
    $tableHeader = '';
    $tableHeader .= "<table>";
    $tableHeader .= "<tr>";
    $tableHeader .= "<th>Sr.No</th>";
    $tableHeader .= "<th>Invoice No</th>";
    $tableHeader .= "<th>Customer No</th>";
    $tableHeader .= "<th>Client Name</th>";
    $tableHeader .= "<th>Invoice Date</th>";
    $tableHeader .= "<th>Invoice Exp Date</th>";
    $tableHeader .= "<th>Invoice Amt</th>";
    $tableHeader .= "<th>Status</th>";
    $tableHeader .= "<th>Paid Amt</th>";
    $tableHeader .= "<th>Tax</th>";
    $tableHeader .= "<th>Tax Amt</th>";
    $tableHeader .= "<th>Payment Mode</th>";
    $tableHeader .= "<th>Pending Amt</th>";
    $tableHeader .= "<th>Payment Date</th>";
    $tableHeader .= "<th>TDS Amt</th>";
    $tableHeader .= "<th>Unpaid Amt</th>";
    $tableHeader .= "<th>Expiry Date</th>";
    $tableHeader .= "<th>Comment</th>";
    $tableHeader .= "</tr>";
    $tableRows = '';
    $SQL = "SELECT * FROM " . DB_PARENT . ".invoice WHERE customerno = " . $_REQUEST['customerno'] . " AND isdeleted=0 ORDER BY invoiceid DESC";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $invexpirydate = date("d-m-Y", strtotime("+30 days", strtotime($row['inv_date'])));
            $paydate = '';
            $inv_expirydate = '';
            if (isset($row['paymentdate']) && $row['paymentdate'] != '0000-00-00') {
                $paydate = date("d-m-Y", strtotime($row['paymentdate']));
            }
            if (isset($row['inv_expiry']) && $row['inv_expiry'] != '0000-00-00') {
                $inv_expirydate = date("d-m-Y", strtotime($row['inv_expiry']));
            }
            if ($paydate == '01-01-1970') {
                $paydate = '';
            }
            $tableRows .= "<tr>";
            $tableRows .= "<td>" . $row['invoiceid'] . "</td>";
            $tableRows .= "<td>" . $row['invoiceno'] . "</td>";
            $tableRows .= "<td>" . $row['customerno'] . "</td>";
            $tableRows .= "<td>" . $row['clientname'] . "</td>";
            $tableRows .= "<td>" . date('d-m-Y', strtotime($row['inv_date'])) . "</td>";
            $tableRows .= "<td>" . $invexpirydate . "</td>";
            $tableRows .= "<td>" . $row['inv_amt'] . "</td>";
            $tableRows .= "<td>" . $row['status'] . "</td>";
            $tableRows .= "<td>" . $row['paid_amt'] . "</td>";
            $tableRows .= "<td>" . $row['tax'] . "</td>";
            $tableRows .= "<td>" . $row['tax_amt'] . "</td>";
            $tableRows .= "<td>" . $row['pay_mode'] . "</td>";
            $tableRows .= "<td>" . $row['pending_amt'] . "</td>";
            $tableRows .= "<td>" . $paydate . "</td>";
            $tableRows .= "<td>" . $row['tds_amt'] . "</td>";
            $tableRows .= "<td>" . $row['unpaid_amt'] . "</td>";
            $tableRows .= "<td>" . $inv_expirydate . "</td>";
            $tableRows .= "<td>" . $row['comment'] . "</td>";
            $tableRows .= "</tr>";
        }
    }
    if ($tableRows == '') {
        $tableRows = "<tr><td>No Data</td></td>";
    }
    ob_start();
    $printdata = '';
    $printdata .= $tableHeader;
    $printdata .= $tableRows;
    $cat = display($printdata);
    $content = ob_get_clean();
    $html = str_get_html($content);
    $xls_filename = str_replace(' ', '', $customerno . '_Ledger_' . date("d-M-Y") . ".xls");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $content;
}
if (isset($_POST['scustomerno'])) {
    $scustomerno = GetSafeValueString($_POST["scustomerno"], "string");
    $sunitid = GetSafeValueString($_POST["sunitid"], "string");
    $scommand = GetSafeValueString($_POST["scommand"], "string");
    $scomment = GetSafeValueString($_POST["scomment"], "string");
    $db = new DatabaseManager();
    $pdo = $db->CreatePDOConn();
    $sp_params = "'" . $scomment . "'"
            . ",'" . $scommand . "'"
            . ",'" . $sunitid . "'"
            . ",'" . $scustomerno . "'"
            . ",'" . GetLoggedInUserId() . "'"
            . ",'" . $todaysdate . "'"
            . ",@is_executed";
    $queryCallSP = "CALL " . speedConstants::SP_PUSH_COMMAND_SERVER . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
    $db->ClosePDOConn($pdo);
    $outputParamsQuery = "SELECT @is_executed AS is_executed";
    $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
    if ($outputResult["is_executed"] == 1) {
        echo "Push command Successful.";
    } else {
        echo "Push Command Failed";
    }
}
if (isset($_REQUEST['ledgerPendingAmount'])) {
    $db = new DatabaseManager();
    $tableHeader = '';
    $tableHeader .= "<table>";
    $tableHeader .= "<tr>";
    $tableHeader .= "<th>LedgerId</th>";
    $tableHeader .= "<th>Ledger Name</th>";
    $tableHeader .= "<th>Customer No</th>";
    $tableHeader .= "<th>Client Name</th>";
    $tableHeader .= "<th>Company</th>";
    $tableHeader .= "<th>Invoice Amt</th>";
    $tableHeader .= "</tr>";
    $tableRows = '';
    $SQL = "SELECT
            l.ledgerid, l.ledgername, c.customerno, c.customername, c.customercompany,
            SUM(i.pending_amt) as pending_amount
            FROM " . DB_PARENT . ".customer c
            INNER JOIN " . DB_PARENT . ".ledger_cust_mapping lcm on lcm.customerno = c.customerno
            INNER JOIN " . DB_PARENT . ".ledger l on l.ledgerid = lcm.ledgerid
            INNER JOIN " . DB_PARENT . ".invoice i on i.ledgerid = l.ledgerid
            WHERE lower(i.status) = lower('pending')
            AND lcm.isdeleted = 0
            AND l.isdeleted = 0
            AND i.isdeleted = 0
            GROUP BY l.ledgerid
            ORDER BY c.customerno ASC";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $tableRows .= "<tr>";
            $tableRows .= "<td>" . $row['ledgerid'] . "</td>";
            $tableRows .= "<td>" . $row['ledgername'] . "</td>";
            $tableRows .= "<td>" . $row['customerno'] . "</td>";
            $tableRows .= "<td>" . $row['customername'] . "</td>";
            $tableRows .= "<td>" . $row['customercompany'] . "</td>";
            $tableRows .= "<td>" . $row['pending_amount'] . "</td>";
            $tableRows .= "</tr>";
        }
    }
    if ($tableRows == '') {
        $tableRows = "<tr><td>No Data</td></td>";
    }
    ob_start();
    $printdata = '';
    $printdata .= $tableHeader;
    $printdata .= $tableRows;
    $cat = display($printdata);
    $content = ob_get_clean();
    $html = str_get_html($content);
    $xls_filename = str_replace(' ', '', 'PendingInvoices_' . date("d-M-Y") . ".xls");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $content;
}
if (isset($_REQUEST['work']) && $_REQUEST['work'] == "getmail" && isset($_REQUEST['customerno'])) {

    $customerno = $_REQUEST['customerno'];
    $term = $_REQUEST['term'];
    $devicemanager = new DeviceManager($customerno);
    $mailIds = $devicemanager->getEmailList($term);
    echo $mailIds;
}

if (isset($_REQUEST['work']) && $_REQUEST['work'] == "getmailforTech" && isset($_REQUEST['customerno'])) {

    $customerno = $_REQUEST['customerno'];
    $term = $_REQUEST['term'];
    $devicemanager = new DeviceManager($customerno);
    $mailIds = $devicemanager->getEmailListforTech($term);
    echo $mailIds;
}


if (isset($_REQUEST['work']) && $_REQUEST['work'] == "insertmail" && isset($_REQUEST['dataTest']) && isset($_REQUEST['customerno1'])) {
    $emailText = $_REQUEST['dataTest'];
    $customerno = $_REQUEST['customerno1'];
    $devicemanager = new DeviceManager($customerno);
    $devices = $devicemanager->insertEmailId($emailText, $customerno);
    echo $devices;
}

if (isset($_REQUEST['work']) && $_REQUEST['work'] == "insertmailforTech" && isset($_REQUEST['dataTest']) && isset($_REQUEST['customerno1'])) {
    $emailText = $_REQUEST['dataTest'];
    $customerno = $_REQUEST['customerno1'];
    $devicemanager = new DeviceManager($customerno);
    $devices = $devicemanager->insertEmailIdforTech($emailText, $customerno);
    echo $devices;
}

if (isset($_REQUEST['cm_id'])) {
    $cm_id = GetSafeValueString($_REQUEST['cm_id'], "string");
    $db = new DatabaseManager();
    $SQL = sprintf("UPDATE `cash_memo` SET `approved` = 1 WHERE `cmid`=%d", $cm_id);
    $db->executeQuery($SQL);
    echo 'success';
}
if (isset($_REQUEST['generate_pdf'])) {
    $cm_id = GetSafeValueString($_REQUEST['generate_pdf'], "string");
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT * from cash_memo where cmid = %d", $cm_id);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $address = $row["address"];
            $cmno = $row["cash_memo_no"];
        }
        $cash_table = '
        <div style="width:700px;">
        <table align="left" border="1" style="border:none;">
            <tr><td colspan="9"></td></tr>
            <tr><td colspan="9"></td></tr>
            <tr><td colspan="9"></td></tr>
            <tr><td colspan="3"></td><td colspan="3"><b>CASH MEMO</b></td><td colspan="3"></td></tr>
            <tr><td colspan="5">' . $address . '</td><td>CASH MEMO NO:-</td><td>' . $cmno . '</td><td>DATE:</td><td>' . date('d-M-Y', strtotime($todaysdate)) . '</td></tr>
            <tr><td colspan="9"></td></tr>
            <tr><td>Sr. No.</td><td colspan="5">Description</td><td>Quantity (Nos.)</td><td>Rate (Rs.)</td><td>Total Amount</td></tr>';
    }
    $pdf_string = '';
    $SQL = sprintf("SELECT * from cash_memo_desc where cmid = %d ORDER BY id ASC", $cm_id);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $x = 1;
            $total = 0;
            $total = $row['quantity'] * $row['rate'];
            $pdf_string .= '<tr><td>' . $x . '</td><td colspan="5">' . $row['desc'] . '</td><td>' . $row['quantity'] . '</td><td>' . $row['rate'] . '</td><td>' . $total . '</td></tr>';
            $subtotal = $subtotal + $total;
            $x++;
        }
    }
    $pdf_string.='<tr><td colspan="9"></td></tr>';
    $pdf_string.='<tr><td></td><td colspan="5">SUBTOTAL</td><td></td><td></td><td>' . $subtotal . '</td></tr>';
    $pdf_string.='<tr><td colspan="9"></td></tr>';
    $pdf_string.='<tr><td colspan="2"><b>TOTAL AMOUNT:</b></td><td colspan="5"></td><td><b>Rs.</b></td><td><b>' . $subtotal . '</b></td></tr>';
    $pdf_string.='<tr><td colspan="8"><b>' . convertNumber($subtotal) . '</b></td><td><b>E. & O.E.</b></td></tr>';
    $pdf_string.='<tr><td colspan="6"></td><td colspan="3">For Elixia Tech Solutions Ltd.</td></tr>';
    $pdf_string.='<tr><td colspan="6"></td><td rowspan="2" colspan="3"></td></tr>';
    $pdf_string.='<tr><td colspan="6"></td></tr>';
    $pdf_string.='<tr><td colspan="6"></td><td colspan="3">Authorized Signatory</td></tr>';
    $cash_table.=$pdf_string;
    ob_start();
    $cat = display($cash_table);
    $content = ob_get_clean();
    $html = str_get_html($content);
    $xls_filename = str_replace(' ', '', "cash_memo" . date("d-m-Y  H:i:s") . ".xls");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $content;
}
if (isset($_REQUEST['pf_id'])) {
    $pf_id = GetSafeValueString($_REQUEST['pf_id'], "string");
    $db = new DatabaseManager();
    $SQL = sprintf("UPDATE `proforma_invoice` SET `approved` = 1 WHERE `pi_id`=%d", $pf_id);
    $db->executeQuery($SQL);
    echo 'success';
}
if (isset($_REQUEST['approvecreditnote'])) {
    $id = GetSafeValueString($_REQUEST['approvecreditnote'], "string");
    $db = new DatabaseManager();
    $SQL = sprintf("UPDATE `credit_note` SET `approved` = 1 WHERE `invoiceid`=%d", $id);
    $db->executeQuery($SQL);
    echo 'success';
}

if (isset($_REQUEST['approveinvoice'])) {
    $invoiceid = GetSafeValueString($_REQUEST['approveinvoice'], "string");
    $db = new DatabaseManager();
    $SQL = sprintf("UPDATE  `proforma_invoice` SET `approved` = 1 WHERE `pi_id` = %d", $invoiceid);
    $db->executeQuery($SQL);
    $query = sprintf("SELECT customerno,ledgerid,inv_date,finaldate FROM " . DB_PARENT . ".proforma_invoice WHERE pi_id = %d AND isdeleted = 0 ORDER BY pi_id DESC LIMIT 1", $invoiceid);
    $db->executeQuery($query);
    $row1 = $db->get_nextRow();
    $custno = $row1['customerno'];
    $ledgerid = $row1['ledgerid'];
    $invdate = $row1['inv_date'];
    $finaldate = $row1['finaldate'];
    if ($ledgerid < 10) {
        $ledgerid = "0" . $ledgerid;
    }
    $query = "SELECT invoiceid FROM " . DB_PARENT . ".invoice ORDER BY invoiceid DESC LIMIT 1";
    $db->executeQuery($query);
    $row1 = $db->get_nextRow();
    $invid = $row1['invoiceid'];
    $inv_count = $invid + 1;
    if ($custno < 10) {
        $nclient = "0" . $custno;
    } else {
        $nclient = $custno;
    }
    $invoiceno = "ES" . $nclient . $ledgerid . $inv_count;
    $SQLUnit = sprintf("INSERT INTO `invoice`(`invoiceno`
                                ,`customerno`
                                ,`ledgerid`
                                ,`clientname`
                                ,`inv_date`
                                ,`inv_amt`
                                ,`status`
                                ,`tax`
                                ,`tax_amt`
                                ,`paymentdate`
                                ,`tds_amt`
                                ,`inv_expiry`
                                ,`comment`
                                ,`isdeleted`
                                ,`product_id`
                                ,`proforma_id`)
                        select  '%s'
                                ,`customerno`
                                ,`ledgerid`
                                ,`clientname`
                                ,`inv_date`
                                ,`inv_amt`
                                ,`status`
                                ,`tax`
                                ,`tax_amt`
                                ,`paymentdate`
                                ,`tds_amt`
                                ,`inv_expiry`
                                ,`comment`
                                ,`isdeleted`
                                ,`product_id`
                                ,%d
                        FROM    proforma_invoice
                        WHERE   pi_id = %d", $invoiceno, $invoiceid, $invoiceid);

    $db->executeQuery($SQLUnit);

    $SQL = sprintf("SELECT `vehicleid` FROM `proforma_vehicle_mapping` WHERE pi_id = %d AND isdeleted = 0", $invoiceid);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row1 = $db->get_nextRow()) {
            $vid['vehicleid'] = $row1['vehicleid'];
            $vehicles[] = $vid;
        }
    }
    //update devices table
    foreach ($vehicles as $vehicle) {
        $SQL2 = sprintf("SELECT devices.deviceid FROM vehicle
                INNER JOIN unit ON vehicle.uid  = unit.uid
                INNER JOIN devices ON unit.uid = devices.uid
                WHERE vehicle.vehicleid = %d AND vehicle.customerno = %d", $vehicle, $custno);
        $db->executeQuery($SQL2);
        if ($db->get_rowCount() > 0) {
            while ($row1 = $db->get_nextRow()) {
                $did = $row1['deviceid'];
            }
        }
        if ($invtype == "0") {// device
            $SQL3 = sprintf('UPDATE devices SET device_invoiceno="' . $invoiceno . '",inv_generatedate="' . $invdate . '" WHERE deviceid=%d', $did);
            $db->executeQuery($SQL3);
        } else if ($invtype == "1") {// renewal
            if (!isset($finaldate) && $finaldate != '0000-00-00' && $finaldate != '1990-01-01') {
                $SQL4 = sprintf('UPDATE devices SET invoiceno="' . $invoiceno . '" WHERE deviceid=%d', $did);
            } else {
                $SQL4 = sprintf('UPDATE devices SET invoiceno="' . $invoiceno . '",expirydate="' . $finaldate . '" WHERE deviceid=%d', $did);
            }

            $db->executeQuery($SQL4);
        } else if ($invtype == "5") {//  lease
            if (!isset($finaldate) && $finaldate != '0000-00-00' && $finaldate != '1990-01-01') {
                $SQL5 = sprintf('UPDATE devices SET invoiceno="' . $invoiceno . '" WHERE deviceid=%d', $did);
            } else {
                $SQL5 = sprintf('UPDATE devices SET invoiceno="' . $invoiceno . '",expirydate="' . $finaldate . '" WHERE deviceid=%d', $did);
            }

            $db->executeQuery($SQL5);
        }
    }
    //check and create required folders on server to store attachment
    $dest = "../../customer";
    $dest1 = "../../customer/$custno";
    $dest2 = "../../customer/$custno/accounts";
    $dest3 = "../../customer/$custno/accounts/invoice";
    $dest4 = "../../customer/$custno/accounts/invoice/$ledgerid";
    if (!file_exists($dest1)) {
        mkdir($dest1, 0777, true);
    }
    if (!file_exists($dest2)) {
        mkdir($dest2, 0777, true);
    }
    if (!file_exists($dest3)) {
        mkdir($dest3, 0777, true);
    }
    if (!file_exists($dest4)) {
        mkdir($dest4, 0777, true);
    }
    // save invoice pdf
    if (isset($form)) {
        include_once 'invoice_genfunc.php';
        ob_start();
        $cat = get_pdfdata($form);
        $content = ob_get_clean();
        require_once("../../vendor/autoload.php");
        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');

            $html2pdf->writeHTML($content);
            $html2pdf->Output($dest4 . "/Invoice_$invno.pdf", 'F');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
        // save xls
//        $filename = "../../customer/$custno/accounts/invoice/$ledgerid/Invoice_$invno.xls";
//        $fp = fopen($filename, "w");
//        fwrite($fp, $content);
//        fclose($fp);
    }

    echo 'success';
}
if (isset($_REQUEST['get_invoiceno'])) {
    $cust_no = GetSafeValueString($_REQUEST['get_invoiceno'], "int");
    $ledgerid = GetSafeValueString($_REQUEST['ledgerid'], "int");
    $db = new DatabaseManager();
    if ($ledgerid < 10) {
        $ledgerid = "0" . $ledgerid;
    }
    $query = "SELECT invoiceid,invoiceno FROM " . DB_PARENT . ".credit_note ORDER BY invoiceid DESC LIMIT 1";
    $db->executeQuery($query);
    $row1 = $db->get_nextRow();
    $invid = $row1['invoiceid'];
    $invno = $row1['invoiceno'];
    $inv_count = $invid + 1;
    if ($cust_no < 10) {
        $nclient = "0" . $cust_no;
    } else {
        $nclient = $cust_no;
    }
    $invoiceno = "ES" . $nclient . $ledgerid . $inv_count . "CR";
    echo $invoiceno;
}
if (isset($_REQUEST['set_taxed_invoice'])) {
    $pf_id = GetSafeValueString($_REQUEST['set_taxed_invoice'], "string");
    $db = new DatabaseManager();
    $SQL = sprintf("UPDATE `proforma_invoice` SET `is_taxed` = 1,`approved` = 0 WHERE `pi_id`=%d", $pf_id);
    $db->executeQuery($SQL);
    echo 'success';
}

if (isset($_REQUEST['getLedgerOfCust'])) {
    $db = new DatabaseManager();
    $customerno = GetSafeValueString($_REQUEST['getLedgerOfCust'], "int");
    $SQL2 = sprintf("   SELECT  lc.ledgerid
                                ,l.ledgername
                        FROM    ledger_cust_mapping lc
                        INNER JOIN ledger l ON l.ledgerid = lc.ledgerid
                        WHERE   lc.customerno = %d AND lc.isdeleted = 0", $customerno);
    $db->executeQuery($SQL2);
    $ledger = array();
    if ($db->get_rowCount() > 0) {
        while ($row1 = $db->get_nextRow()) {
            $ledg['id'] = $row1['ledgerid'];
            $ledg['name'] = $row1['ledgername'];
            array_push($ledger, $ledg);
        }
        echo json_encode($ledger);
    } else {
        echo NULL;
    }
}

if (isset($_REQUEST['getLedgerPayment'])) {
    $db = new DatabaseManager();
    $ledgerid = GetSafeValueString($_REQUEST['getLedgerPayment'], "int");
    $SQL2 = sprintf("   SELECT  invoiceno
                                ,inv_date
                                ,inv_expiry
                                ,inv_amt
                                ,paymentdate
                        FROM    invoice
                        WHERE   ledgerid = %d
                        AND     status LIKE 'Paid'
                        AND     isdeleted = 0
                        AND     paymentdate IS NOT NULL
                        AND     paymentdate <> '0000-00-00'
                        ORDER BY invoiceid asc", $ledgerid);
    $db->executeQuery($SQL2);
    $ledger = array();
    if ($db->get_rowCount() > 0) {
        while ($row1 = $db->get_nextRow()) {
            $ledg['x1'] = (strtotime($row1['inv_date'])) * 1000;
            $ledg['x'] = (strtotime($row1['paymentdate'])) * 1000;
            $ledg['y'] = $row1['inv_amt'];
            $ledg['invoiceno'] = $row1['invoiceno'];
            $ledg['inv_date'] = date('d-m-Y', strtotime($row1['inv_date']));
            $ledg['inv_expiry'] = date('d-m-Y', strtotime($row1['inv_expiry']));
            $ledg['paymentdate'] = date('d-m-Y', strtotime($row1['paymentdate']));
            $diff = strtotime($row1['paymentdate']) - strtotime($row1['inv_date']);
            $diff = floor($diff / (60 * 60 * 24));
            $ledg['diff'] = $diff;
            array_push($ledger, $ledg);
        }
        echo json_encode($ledger);
    } else {
        echo NULL;
    }
}

if (isset($_POST['getExpenseReport'])) {
    $db = new DatabaseManager();
    $categoryid = GetSafeValueString($_REQUEST['getExpenseReport'], "int");
    $SQL2 = sprintf("   SELECT  SUM(amount) as total
                                ,transaction_datetime
                        FROM    `bank_statement`
                        WHERE   transaction_type = 1
                        AND     categoryid = %d
                        GROUP BY YEAR(transaction_datetime),MONTH(transaction_datetime)
                        ORDER BY transaction_datetime asc", $categoryid);
    $db->executeQuery($SQL2);
    $ledger = array();
    if ($db->get_rowCount() > 0) {
        while ($row1 = $db->get_nextRow()) {
            $ledg['x'] = (strtotime($row1['transaction_datetime'])) * 1000;
            $ledg['y'] = $row1['total'];
            array_push($ledger, $ledg);
        }
        echo json_encode($ledger);
    } else {
        echo NULL;
    }
}

if (isset($_POST['add_note'])) {
    $db = new DatabaseManager();
    $note = $_POST['add_note'];
    $ticketid = isset($_POST['ticket_note']) ? $_POST['ticket_note'] : 0;
    $create_by = isset($_SESSION["sessionteamid"]) ? $_SESSION["sessionteamid"] : 0;
    if ($create_by == 0) {
        echo 'Logout';
    } elseif ($ticketid <= 0) {
        echo 'wrongticket';
    } else {
        $SQL = sprintf("INSERT INTO " . DB_ELIXIATECH . ".sp_note (`ticketid`
                        , `note`
                        , `create_by`
                        , `is_customer`
                        , `create_on_date`)
                    VALUES (%d,'%s',%d,%d,'%s')", Sanitise::Long($ticketid)
                , Sanitise::String($note)
                , Sanitise::Long($create_by)
                , 0
                , Sanitise::DateTime($todaysdate));
        $db->executeQuery($SQL);
        echo 'Success';
    }
}
if(isset($_POST['pullNotes'])){
    $db = new DatabaseManager();
    $ticketid = $_POST['ticketid'];
    $db=new DatabaseManager();
    $pdo = $db->CreatePDOConnForTech();
    $sp_params = "'" .$ticketid. "'";
    $queryCallSP = "CALL " . speedConstants::SP_PULL_NOTES . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    if(!empty($arrResult)){
        echo json_encode($arrResult);
    }
}

if (isset($_POST['check_unit'])) {
    $db = new DatabaseManager();
    $unitno = GetSafeValueString($_POST['check_unit'], "string");
    $SQL2 = sprintf("   SELECT  unitno
                        FROM    unit
                        WHERE   unitno LIKE '%s'", Sanitise::String($unitno));
    $db->executeQuery($SQL2);
    if ($db->get_rowCount() > 0) {
        echo 'notok';
    } else {
        echo 'ok';
    }
}

if (isset($_POST['check_sim'])) {
    $db = new DatabaseManager();
    $simno = GetSafeValueString($_POST['check_sim'], "string");
    $SQL2 = sprintf("   SELECT  simcardno
                        FROM    simcard
                        WHERE   simcardno LIKE '%s'", Sanitise::String($simno));
    $db->executeQuery($SQL2);
    if ($db->get_rowCount() > 0) {
        echo 'notok';
    } else {
        echo 'ok';
    }
}

if (isset($_REQUEST['lockCustomer'])) {
    $customerno = GetSafeValueString($_REQUEST['lockCustomer'], "string");
    $state = GetSafeValueString($_REQUEST['state'], "string");
    $newstate = ($state == 0) ? 1 : 0;

    $db = new DatabaseManager();
    $SQL = sprintf("UPDATE  `customer`
                    SET     `isoffline` = %d
                    WHERE   `customerno` = %d", Sanitise::Long($newstate), Sanitise::Long($customerno));
    $db->executeQuery($SQL);
    if ($state == 0) {
        $SQL = sprintf("INSERT INTO " . DB_PARENT . ".customer_lock_log (`customerno`
                        , `teamid`
                        , `locked_on`
                        , `isdeleted`)
                    VALUES (%d,%d,'%s',0)", Sanitise::Long($customerno)
                , Sanitise::Long(GetLoggedInUserId())
                , Sanitise::DateTime($todaysdate));
        $db->executeQuery($SQL);
    } elseif ($state == 1) {
        $SQL = sprintf("UPDATE  `customer_lock_log`
                        SET     `unlocked_on` = '%s'
                        WHERE   `customerno` = %d
                        AND     `teamid` = %d
                        ORDER BY `id` DESC
                        LIMIT 1", Sanitise::DateTime($todaysdate)
                , Sanitise::Long($customerno)
                , Sanitise::Long(GetLoggedInUserId()));
        $db->executeQuery($SQL);
    }
    echo 'success';
}
if (isset($_REQUEST['deleteDeposit'])) {
    $db = new DatabaseManager();
    $id = GetSafeValueString($_REQUEST['deleteDeposit'], "string");
    $SQL = sprintf("UPDATE  `bank_reconc_stmt`
                    SET     `is_deleted` = 1
                    WHERE   `id` = %d;", Sanitise::Long($id));
    $db->executeQuery($SQL);
    echo 'Success';
}

if (isset($_REQUEST['getledgerpending'])) {
    $ledgerid = GetSafeValueString($_REQUEST['getledgerpending'], "int");
    $customerno = GetSafeValueString($_REQUEST['customerno'], "int");
    $db = new DatabaseManager();

    $SQL2 = sprintf("   SELECT  SUM(pending_amt) AS amount
                        FROM    invoice
                        WHERE   (ledgerid = %d OR customerno = %d)
                        AND     lower(status) LIKE 'pending'
                        AND     pending_amt <> 0
                        AND     isdeleted = 0", Sanitise::Long($ledgerid), Sanitise::Long($customerno));
    $db->executeQuery($SQL2);
    $ledger = array();
    if ($db->get_rowCount() > 0) {
        while ($row1 = $db->get_nextRow()) {
            $amount = $row1['amount'];
        }
        echo $amount;
    } else {
        echo 'null';
    }
}

//if (isset($_REQUEST['teamdetail'])) {
//    $term = $_REQUEST['term'];
//    $term = "%" . $term . "%";
//    $db = new DatabaseManager();
//    $SQL = sprintf("SELECT  teamid
//                            ,name
//                    FROM    team
//                    WHERE   name LIKE '%s'", Sanitise::String($term));
//    $db->executeQuery($SQL);
//    if ($db->get_rowCount() > 0) {
//        $data = Array();
//        while ($row = $db->get_nextRow()) {
//            $json["value"] = $row["name"];
//            $json['tid'] = $row["teamid"];
//            array_push($data, $json);
//        }
//        $data = json_encode($data);
//    }
//    echo $data;
//}

function display($data) {
    echo $data;
}

function convertNumber($number) {
    $no = round($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy',
        '80' => 'Eighty', '90' => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
        } else
            $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';
    return $result . "Only";
}

if(isset($_POST['get_device_location'])){
    $tm = new TeamManager();
    $device_location=$tm->fetch_DeviceLocation();
    echo json_encode($device_location);
}
if(isset($_POST['migrate_vehicle'])){
   
    $tm = new TeamManager();
    
    $vehicle_obj = new stdClass();
    $unitObj = new stdClass();

    $vehicle_obj->from_customer = $_POST['customerno_1'];
    $vehicle_obj->to_customer = $_POST['customerno_2'];


    $unitObj->to_customer = $_POST['customerno_2'];

    $unitArray=explode(',',$_POST['vehicleList']);


    $i=0;
    $j=0;
    $vehicle_list = array();
    foreach($unitArray as $unitno){
 
        if(!empty($unitno)){
            
            $vehicle_migrate = array();
            $vehicle_exists = array();

            $vehicle_obj->unit_no = $unitno;

            $unitObj->unit_no = $unitno;

           
            $unitExists=$tm->fetch_unit_withCustomer($unitObj);//Check if exists
            

            if($unitExists['is_exists']==0){
                
                $vehicle_migrate[$i]=$tm->insert_duplicate_vehicles($vehicle_obj);
                $vehicle_list['new_added'][$i]=$vehicle_migrate[$i];
                $i++;
            }
            else{

               
                $vehicle_exists[$j]=$unitExists['vehicleNo'];
                $vehicle_list['existing'][$j]=$vehicle_exists[$j];
                $j++;
            }
             
        }
    }
    echo json_encode($vehicle_list);
}

if(isset($_POST['action']) && $_POST['action'] == 'unmappedVehicles'){
    $tm = new TeamManager();
    $unmappedVehicles=$tm->getUnmappedvehicles($_POST['custno']);
    echo json_encode($unmappedVehicles);
}

if(isset($_POST['action']) && $_POST['action'] == 'getCustomerDetails'){
        $tm = new TeamManager();
        $customerDetails=$tm->fetch_customer_details($_POST['custno']);
        if ($customerDetails["renewal"] == 0) {
            $customerDetails["renewal"] = "Not Assigned";
        }
        if ($customerDetails["renewal"] == -2) {
            $customerDetails["renewal"] = "Closed";
        }
        if ($customerDetails["renewal"] == -3) {
            $customerDetails["renewal"] = "Lease";
        }
        if ($customerDetails["renewal"] == -1) {
            $customerDetails["renewal"] = "Demo";
        }
        if ($customerDetails["renewal"] == 1) {
            $customerDetails["renewal"] = "Monthly";
        }
        if ($customerDetails["renewal"] == 3) {
            $customerDetails["renewal"] = "Quarterly";
        }
        if ($customerDetails["renewal"] == 6) {
            $customerDetails["renewal"] = "Six Monthly";
        }
        if ($customerDetails["renewal"] == 12) {
            $customerDetails["renewal"] = "Yearly";
        }
        else{
             $customerDetails["renewal"] ='';
        }
        echo json_encode($customerDetails);
}

if(isset($_POST['action']) && $_POST['action'] == 'getDevicesInfo'){
    $tm = new TeamManager();
    $deviceInfo=$tm->fetch_devices_info($_POST['custno']);
    echo json_encode($deviceInfo);
}
if(isset($_POST['action']) && $_POST['action'] == 'fetch_unit'){
   
    $tm = new TeamManager();
    
    $unmappedVehicles=$tm->fetch_all_unitno($_POST['custno']);

    echo json_encode($unmappedVehicles);
}
if(isset($_POST['action']) && $_POST['action'] == 'fetch_unit_details'){
   
    $tm = new TeamManager();
    $unitObj = new stdClass();

    $unitObj->unitno = $_POST['unitno'];
    $unitObj->custno = $_POST['custno'];
    
    $unit_details=$tm->fetch_unit_details($unitObj);

    echo json_encode($unit_details);
}

if (isset($_POST["ssubmit"])) {

    $tm = new TeamManager();
    $simObj = new stdClass();

    $simObj->simList = GetSafeValueString($_POST["simlist"], "string");
    $simObj->comments = GetSafeValueString($_POST["comments"], "string");
    $simObj->vendorid = GetSafeValueString($_POST["pvendor"], "long");

    $sim_details=$tm->purchase_Sim($simObj);

    echo json_encode($sim_details);
 
}
if (isset($_POST["generate_invoice_link"])) {

    $tm = new TeamManager();
    $dateObj = new stdClass();

    $dateObj->date = date("Y-m-d",strtotime($_POST["invoice_date"]));

    $link_details=$tm->generate_invoice_links($dateObj);
    $final_array = array();
    foreach($link_details as $details){
        $details['link']='speed.elixiatech.com/modules/download/report.php?q=invoice-pdf-'.$details['customerno'].'-'.$details['userkey'].'-0&invoiceid='.$details['invoiceid'].'&userkey='.$details['userkey'].'';
        $final_array[]=$details;
    }
    echo json_encode($final_array);
 
}

if (isset($_POST['get_company_role'])) {
    $tm=new TeamManager();
    $arrResult=$tm->get_company_role($_POST['departmentId']);
    echo json_encode($arrResult);
}

if (isset($_POST['insert_item_master'])) {
    $tm=new TeamManager();

    $itemObj = new stdClass();
    $itemObj->itemName      = $_POST['item_name'];
    $itemObj->description   = $_POST['description'];
    $itemObj->hsnCode       = $_POST['hsn_code'];
    $arrResult=$tm->insert_item_master($itemObj);
    if($arrResult>0){
        $new_column = $tm->insert_column_itemMasterDetails($itemObj);
        echo json_encode($arrResult);
    }else{
        echo json_encode($arrResult);
    }
}

if (isset($_POST['fetch_item_master'])) {
    $tm=new TeamManager();

    $itemId = isset($_POST['item_id'])?$_POST['item_id'] : "";
    $arrResult=$tm->fetch_itemMaster($itemId);
    echo json_encode($arrResult);
}

if (isset($_POST['update_item_master'])) {
    $tm=new TeamManager();

    $itemObj = new stdClass();
    $itemObj->itemId        = $_POST['item_master_id'];
    $itemObj->itemName      = $_POST['item_name'];
    $itemObj->temp_itemName = $_POST['temp_item_name'];
    $itemObj->description   = $_POST['description'];
    $itemObj->hsnCode       = $_POST['hsn_code'];
    $arrResult=$tm->update_item_master($itemObj);

    if($itemObj->itemName!=$itemObj->temp_itemName && $arrResult==1){
        $modify_column = $tm->rename_column_itemMasterDetails($itemObj);
        echo json_encode($arrResult);
    }else{
        echo json_encode($arrResult);
    }
}

if (isset($_POST['fetch_item_master_details'])) {
    $tm=new TeamManager();
    $customerno= $_POST['customerno'];
    $arrResult=$tm->fetch_itemMasterDetails($customerno);
    echo json_encode($arrResult);
}

if (isset($_POST['insert_item_masterDetails'])) {
    $tm=new TeamManager();
    $columnArray = array();
    $final_array = array();
    $columnArray=$_POST;
    $itemObj = new stdClass();
    $itemObj->customerNo = $_POST['customerno'];



    foreach($columnArray as $key=>$val){
        if($key!='insert_item_masterDetails' && $key!='customerno'){
            $itemObj->columnName=$key;
            $itemObj->value=$val;
            $final_array[] = $tm->update_item_masterDetails($itemObj);
        }
    }
    echo json_encode($final_array);
}
if (isset($_POST['invoice_holdForm'])) {
    $tm=new TeamManager();
    $holdObj = new stdClass();
    $holdObj->customerNo = $_POST['customerno'];
    $holdObj->statusId = isset($_POST['statusType'])?$_POST['statusType']:0;
    $final_array = $tm->updateInvoiceHoldStatus($holdObj);
    echo json_encode($final_array);
}
if(isset($_POST['get_invoiceHold_status'])){
    $tm=new TeamManager();
    $holdObj = new stdClass();
    $holdObj->customerNo = $_POST['customerNo'];
    $holdObj->statusId = "";
    $arrResult=$tm->getInvoiceHoldStatus($holdObj);
    $arrResult = $arrResult['invoiceHoldStatus'];
    $arrResult = $arrResult[0];
    echo json_encode($arrResult);
}

if (isset($_REQUEST['device_export'])) {
    $db = new DatabaseManager();
    $tableHeader = '';
    $tableHeader .= "<table>";
    $tableHeader .= "<tr>";
    $tableHeader .= "<th>Customer No</th>";
    $tableHeader .= "<th>Customer Name</th>";
    $tableHeader .= "<th>Customer Company</th>";
    $tableHeader .= "<th>vehicle No</th>";
    $tableHeader .= "<th>Unit No</th>";
    $tableHeader .= "<th>Simcard No</th>";
    $tableHeader .= "<th>Renewal</th>";
    $tableHeader .= "<th>Installation Date</th>";
    $tableHeader .= "<th>Start Date</th>";
    $tableHeader .= "<th>End Date</th>";
    $tableHeader .= "<th>Expiry Date</th>";
    $tableHeader .= "</tr>";
    $tableRows = '';
    

    $SQL = "SELECT
            c.customername, c.customerno, d.installdate, d.start_date, d.end_date, d.expirydate, 
            vh.vehicleno, sc.simcardno, u.unitno, c.renewal, c.customercompany
            FROM " . DB_PARENT . ".devices d
            INNER JOIN " . DB_PARENT . ".customer  c on c.customerno = d.customerno AND c.renewal NOT IN (-1,-2)
            INNER JOIN " . DB_PARENT . ".vehicle vh on vh.uid = d.uid
            INNER JOIN " . DB_PARENT . ".simcard sc on sc.id=d.simcardid
            INNER JOIN " . DB_PARENT . ".unit u on u.vehicleid=vh.vehicleid AND u.trans_statusid in (5,6)
            group by d.deviceid
            ORDER BY c.customerno ASC";

           
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $tableRows .= "<tr>";
            $tableRows .= "<td>" . $row['customerno'] . "</td>";
            $tableRows .= "<td>" . $row['customername'] . "</td>";
            $tableRows .= "<td>" . $row['customercompany'] . "</td>";
            $tableRows .= "<td>" . $row['vehicleno'] . "</td>";
            $tableRows .= "<td>" . $row['unitno'] . "</td>";
            $tableRows .= "<td>" . $row['simcardno'] . "</td>";
            $tableRows .= "<td>" . $row['renewal'] . "</td>";
            $tableRows .= "<td>" . $row['installdate'] . "</td>";
            $tableRows .= "<td>" . $row['start_date'] . "</td>";
            $tableRows .= "<td>" . $row['end_date'] . "</td>";
            $tableRows .= "<td>" . $row['expirydate'] . "</td>";
            $tableRows .= "</tr>";
        }
    }
    if ($tableRows == '') {
        $tableRows = "<tr><td>No Data</td></td>";
    }
    ob_start();
    $printdata = '';
    $printdata .= $tableHeader;
    $printdata .= $tableRows;
    $cat = display($printdata);
    $content = ob_get_clean();
    $html = str_get_html($content);
    $xls_filename = str_replace(' ', '', 'DeviceIstallationDetails_' . date("d-M-Y") . ".xls");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $content;
}



?>
