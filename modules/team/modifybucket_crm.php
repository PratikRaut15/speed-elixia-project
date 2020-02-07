<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/datagrid.php");
$_scripts[] = "../../scripts/jquery.min.js";
$_scripts[] = "../../scripts/team/modifycustomer.js";
$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");

$bucketid = GetSafeValueString(isset($_GET["id"]) ? $_GET["id"] : $_POST["id"], "long");

$db = new DatabaseManager();

class testing {

}

$timeslot_array = Array();
$SQL = sprintf("SELECT tsid, timeslot FROM " . DB_PARENT . ".sp_timeslot");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $timeslot = new testing();
        $timeslot->tsid = $row["tsid"];
        $timeslot->timeslot = $row["timeslot"];
        $timeslot_array[] = $timeslot;
    }
}

$SQL = sprintf("SELECT team.teamid, team.name FROM " . DB_PARENT . ".team");
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

// See if we need to save a new one.
$message = "";
$db = new DatabaseManager();

if (isset($_POST["save"])) {
    $customerno = GetSafeValueString($_POST["customerno"], "string");
    $created_by = GetSafeValueString($_POST["createdby"], "string");
    $priorityid = GetSafeValueString($_POST["spriority"], "string");
    $vehicleid = GetSafeValueString($_POST["vehicleid"], "string");
    $location = GetSafeValueString($_POST["location"], "string");
    $timeslot = GetSafeValueString($_POST["stimeslot"], "string");
    $purposeid = GetSafeValueString($_POST["spurpose"], "string");
    $details = GetSafeValueString($_POST["details"], "string");
    $creason = GetSafeValueString($_POST["creason"], "string");
    $coordinator = GetSafeValueString($_POST["scoordinator"], "string");
    $apt_date = GetSafeValueString($_POST["apt_date"], "string");
    $apt_date = date("Y-m-d", strtotime($apt_date));
    $coname = GetSafeValueString($_POST["coname"], "string");
    $cophone = GetSafeValueString($_POST["cophone"], "string");
    $docketid = $_POST["docketid"];


    if ($coname != "") {
        $SQL = sprintf("INSERT INTO " . DB_PARENT . ".contactperson_details (
        `typeid` ,`person_name`,`cp_phone1`, `customerno`, `insertedby`, `insertedon`)
        VALUES (
        3, '%s', '%s', '%d', '%d', '%s')", $coname, $cophone, $customerno, $created_by, Sanitise::DateTime($today));
        $db->executeQuery($SQL);
        $coordinator = $db->get_insertedId();
    }


    $reschedule_date = GetSafeValueString($_POST["reschedule_date"], "string");
    $reschedule_date = date("Y-m-d", strtotime($reschedule_date));

    $sstatus = GetSafeValueString($_POST["sstatus"], "string");

    if ($sstatus == 0) {
        $SQL = sprintf("UPDATE bucket SET apt_date = '" . $apt_date . "', coordinatorid = " . $coordinator . ", priority = " . $priorityid . ", location = '" . $location . "', timeslotid = " . $timeslot . ", purposeid = " . $purposeid . ", details = '" . $details . "', status=" . $sstatus . ", create_timestamp = '" . Sanitise::DateTime($today) . "' where bucketid=" . $bucketid);
        $db->executeQuery($SQL);
    }

    if ($sstatus == 5) {
        $SQL = sprintf("UPDATE bucket SET status=" . $sstatus . ", cancelled_timestamp = '" . Sanitise::DateTime($today) . "', cancellation_reason='" . $creason . "' where bucketid=" . $bucketid);
        $db->executeQuery($SQL);
    }

    if ($sstatus == 1) {
       $SQL = sprintf("UPDATE bucket SET status=" . $sstatus . ", reschedule_date='" . $reschedule_date . "',reschedule_timestamp = '" . Sanitise::DateTime($today) . "' where bucketid=" . $bucketid);
        $db->executeQuery($SQL);

       $SQLunit = sprintf("INSERT INTO bucket (
        `apt_date` ,`customerno`,`created_by`, `priority`, `vehicleid`, `location`, `timeslotid`, `purposeid`, `details`, `coordinatorid`, `create_timestamp`, status,docketid,prevBucketId)
        VALUES (
        '%s', '%d', '%d', '%d', '%d', '%s', '%d','%d','%s','%d','%s',0,'%d','%d')", $reschedule_date, $customerno, $created_by, $priorityid, $vehicleid, $location, $timeslot, $purposeid, $details, $coordinator, Sanitise::DateTime($today),$docketid,$bucketid);
        $db->executeQuery($SQLunit);
    }

    header("Location: bucketlist_crm.php");
}

$sql = sprintf("SELECT b.docketid,b.bucketid, b.apt_date, b.customerno, c.customercompany, b.priority, v.vehicleno, b.location, sp.timeslot, b.purposeid, b.details, cp.person_name, cp.cp_phone1, t.name, b.created_by, b.vehicleid, b.coordinatorid, b.timeslotid, b.vehicleno as vehno, b.vehicleid
FROM `bucket` b
INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = b.customerno
LEFT OUTER JOIN " . DB_PARENT . ".sp_timeslot sp ON sp.tsid = b.timeslotid
LEFT OUTER JOIN " . DB_PARENT . ".contactperson_details cp ON cp.cpdetailid = b.coordinatorid
LEFT OUTER JOIN " . DB_PARENT . ".team t ON t.teamid = b.created_by
LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
where b.bucketid='%d'", $bucketid);
$db->executeQuery($sql);

if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $bucketid = $row["bucketid"];
        $apt_date = $row["apt_date"];
        $customerno = $row["customerno"];
        $company = $row["customercompany"];
        $createdby = $row["created_by"];
        if ($row['priority'] == 1) {
            $priority = "High";
        }
        if ($row['priority'] == 2) {
            $priority = "Medium";
        }
        if ($row['priority'] == 3) {
            $priority = "Low";
        }
        $priorityid = $row['priority'];
        if ($row['vehicleid'] == 0) {
            $vehicleno = $row['vehno'];
        } else {
            $vehicleno = $row['vehicleno'];
        }
        $location = $row["location"];
        $timeslot = $row["timeslot"];
        $timeslotid = $row["timeslotid"];
        $purposeid = $row['purposeid'];
        $details = $row["details"];
        $coordinatorid = $row['coordinatorid'];
        $person_name = $row['person_name'];
        $person_phone = $row['cp_phone1'];
        $created_by = $row["name"];
        $vehicleid = $row['vehicleid'];
        $docketid = $row['docketid'];
    }
} else {
    header("Location: bucketlist_crm.php");
    exit;
}

include("header.php");
?>
<div class="panel">
    <div class="paneltitle" align="center">Update Bucket List</div>
    <div class="panelcontents">
        <form method="post" id="form1" action="modifybucket_crm.php">
            <?php echo($message); ?>
            <input type="hidden" name = "id" value="<?php echo($bucketid) ?>"/>
            <input type="hidden" name = "customerno" value="<?php echo($customerno) ?>"/>
            <input type="hidden" name = "createdby" value="<?php echo($createdby) ?>"/>
            <input type="hidden" name = "vehicleid" value="<?php echo($vehicleid) ?>"/>
            <input type="hidden" name = "docketid"  value="<?php echo ($docketid) ?>"/>
            <table width="100%">
                <tr><td>Bucket Id:</td><td><b><?php echo "B00" . $bucketid; ?></b></td></tr>
                <tr>
                    <td> Appointment Date: </td>
                    <td> <input name="apt_date" id="apt_date" type="text" value="<?php echo(date("d-m-Y", strtotime($apt_date))); ?>"/><button id="trigger10">...</button></td>
                </tr>

                <tr>
                    <td> Customer No: </td>
                    <td> <?php echo($customerno); ?> </td>
                </tr>

                <tr>
                    <td> Company: </td>
                    <td> <?php echo($company); ?> </td>
                </tr>

                <tr>
                    <td> Priority: </td>
                    <td>                        <select name="spriority" id="spriority">
                            <option value="1" <?php if ($priority == "High") { ?> selected <?php } ?>>High</option>
                            <option value="2" <?php if ($priority == "Medium") { ?> selected <?php } ?>>Medium</option>
                            <option value="3" <?php if ($priority == "Low") { ?> selected <?php } ?>>Low</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td> Vehicle No: </td>
                    <td> <?php echo($vehicleno); ?> </td>
                </tr>

                <tr>
                    <td> Location: </td>
                    <td> <input name = "location" id="location" type="text" value="<?php echo($location); ?>"></td>
                </tr>

                <tr>
                    <td> Time Slot: </td>
                    <td>
                        <select name="stimeslot" id="stimeslot">
                            <option value="<?php echo($timeslotid); ?>"><?php echo($timeslot); ?></option>
                            <?php
                            foreach ($timeslot_array as $thistime) {
                                if ($thistime->tsid != $timeslotid) {
                                    ?>
                                    <option value="<?php echo($thistime->tsid); ?>"><?php echo($thistime->timeslot); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td> Purpose: </td>
                    <td> <select name="spurpose" id="spurpose">
                            <option value="1" <?php if ($purposeid == 1) { ?> selected <?php } ?>>New Installation</option>
                            <option value="2" <?php if ($purposeid == 2) { ?> selected <?php } ?>>Repair</option>
                            <option value="4" <?php if ($purposeid == 4) { ?> selected <?php } ?>>Replacement</option>
                            <option value="5" <?php if ($purposeid == 5) { ?> selected <?php } ?>>Reinstall</option>
                            <option value="3" <?php if ($purposeid == 3) { ?> selected <?php } ?>>Removal</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td> Details: </td>
                    <td> <input name = "details" id="details" type="text" value="<?php echo($details); ?>"></td>
                </tr>

                <tr>
                    <?php
                    $cp_array = Array();
                    $SQL = sprintf("SELECT cpdetailid, person_name FROM " . DB_PARENT . ".contactperson_details WHERE customerno = %d AND typeid = 3", $customerno);
                    $db->executeQuery($SQL);
                    if ($db->get_rowCount() > 0) {
                        while ($row = $db->get_nextRow()) {
                            $cp = new testing();
                            $cp->cpid = $row["cpdetailid"];
                            $cp->name = $row["person_name"];
                            $cp_array[] = $cp;
                        }
                    }
                    ?>
                    <td>Co-ordinator </td>
                    <td><select name="scoordinator" id="scoordinator">
                            <option value="<?php echo($cp->cpid); ?>"><?php echo($cp->name); ?></option>
                            <?php
                            foreach ($cp_array as $thiscp) {
                                if ($cp->cpid != $thiscp->cpid) {
                                    ?>
                                    <option value="<?php echo($thiscp->cpid); ?>"><?php echo($thiscp->name); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <br/> OR <br/>Co-ordinator Name&nbsp;<input name = "coname" id="coname" type="text"><br/> Co-ordinator Phone&nbsp;<input name = "cophone" id="cophone" type="text">
                    </td>
                </tr>

                <tr>
                    <td> Created By: </td>
                    <td> <?php echo($created_by); ?> </td>
                </tr>

                <tr>
                    <td>Status</td>
                    <td><select name="sstatus" id="sstatus" onchange="changeFunc(value);">
                            <option value="0" selected>Modify</option>
                            <option value="1" >Reschedule</option>
                            <option value="5" >Cancel</option>
                        </select>
                    </td>
                </tr>

                <tr id="res_date" style="display: none;">
                    <?php
                    $apt_date = date('d-m-Y', strtotime("+ 1 day"));
                    ?>
                    <td>Reschedule Date </td>
                    <td> <input name="reschedule_date" id="reschedule_date" type="text" value="<?php echo $apt_date; ?>"/><button id="trigger12">...</button>
                    </td>
                </tr>

                <tr id="c_reason" style="display: none;">
                    <td>Cancellation Reason</td>
                    <td><input name = "creason" id="creason" type="text"></td>
                </tr>

            </table>
            <input type="submit" name="save" value="Save Details" onclick="save_details();" />
        </form>
    </div>
</div>
<?php
include("footer.php");
?>
<script>
    Calendar.setup(
            {
                inputField: "reschedule_date", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger12" // ID of the button
            });

    Calendar.setup(
            {
                inputField: "apt_date", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger10" // ID of the button
            });

    function changeFunc($i) {

        if ($i == 1)
        {
            $("#res_date").show();
            $("#c_reason").hide();
        }
        else if ($i == 5)
        {
            $("#c_reason").show();
            $("#res_date").hide();
        }
        else
        {
            $("#res_date").hide();
            $("#c_reason").hide();
        }

    }

    jQuery(document).ready(function () {
        if(jQuery('#spurpose').val()==1){
            jQuery('#spurpose').prop('disabled', 'disabled');
        }
    });
    function save_details(){
        if(jQuery('#spurpose').val()==1){
            jQuery('#spurpose').prop('disabled', false);  //This function is made to save purposeid=1
                                                          // If disabled it sets the purposeid=0
        }
    }
</script>
