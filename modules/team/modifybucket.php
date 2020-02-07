<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/datagrid.php");
$_scripts[] = "../../scripts/jquery.min.js";
//$_scripts[] = "../../scripts/team/modifycustomer.js";
$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");

$bucketid = GetSafeValueString(isset($_GET["id"]) ? $_GET["id"] : $_POST["id"], "long");

if (IsHead()) {
    $msg = "<P>You are an authorized user</p>";
} else {
    header("Location: index.php");
    exit;
}

$db = new DatabaseManager();

class testing {
    
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
if (isset($_POST["id"])) {
    $teamid = GetSafeValueString($_POST["uteamid"], "string");
    $customerno = GetSafeValueString($_POST["customerno"], "string");
    $created_by = GetSafeValueString($_POST["createdby"], "string");
    $priorityid = GetSafeValueString($_POST["priorityid"], "string");
    $vehicleid = GetSafeValueString($_POST["vehicleid"], "string");
    $location = GetSafeValueString($_POST["location"], "string");
    $timeslot = GetSafeValueString($_POST["timeslotid"], "string");
    $purposeid = GetSafeValueString($_POST["purposeid"], "string");
    $details = GetSafeValueString($_POST["details"], "string");
    $comment = GetSafeValueString($_POST["comment"], "string");
    $creason = GetSafeValueString($_POST["creason"], "string");
    $coordinator = GetSafeValueString($_POST["coordinatorid"], "string");

    $reschedule_date = GetSafeValueString($_POST["reschedule_date"], "string");
    $reschedule_date = date("Y-m-d", strtotime($reschedule_date));

    $sstatus = GetSafeValueString($_POST["sstatus"], "string");
    $docketid = $_POST["docketid"];
    
    if ($sstatus == 4) {
        echo $SQL = sprintf("UPDATE bucket SET fe_id= " . $teamid . ",status=" . $sstatus . ", fe_assigned_timestamp = '" . Sanitise::DateTime($today) . "' where bucketid=" . $bucketid);
        $db->executeQuery($SQL);
    }

    if ($sstatus == 5) {
        $SQL = sprintf("UPDATE bucket SET status=" . $sstatus . ", cancelled_timestamp = '" . Sanitise::DateTime($today) . "', cancellation_reason='" . $creason . "' where bucketid=" . $bucketid);
        $db->executeQuery($SQL);
    }

    if ($sstatus == 1) {
        $SQL = sprintf("UPDATE bucket SET status=" . $sstatus . ", reschedule_date='" . $reschedule_date . "',reschedule_timestamp = '" . Sanitise::DateTime($today) . "',remarks = '" .$comment . "' where bucketid=" . $bucketid);
        $db->executeQuery($SQL);

      $SQLunit = sprintf("INSERT INTO bucket (
        `apt_date` ,`customerno`,`created_by`, `priority`, `vehicleid`, `location`, `timeslotid`, `purposeid`, `details`, `coordinatorid`, `create_timestamp`, status,docketid,prevBucketId)
        VALUES (
        '%s', '%d', '%d', '%d', '%d', '%s', '%d','%d','%s','%d','%s',0,'%d','%d')", $reschedule_date, $customerno, $created_by, $priorityid, $vehicleid, $location, $timeslot, $purposeid, $details, $coordinator, Sanitise::DateTime($today),$docketid,$bucketid);
    $db->executeQuery($SQLunit);

    }

    header("Location: bucketlist.php");
}

$sql = sprintf("SELECT b.docketid,b.bucketid,b.fe_id, b.apt_date, b.customerno, c.customercompany, b.priority, v.vehicleno, b.location, sp.timeslot, b.purposeid, b.details, cp.person_name, cp.cp_phone1, t.name, b.created_by, b.vehicleid, b.coordinatorid, b.timeslotid, b.vehicleno as vehno, b.vehicleid
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
        if ($row['purposeid'] == 1) {
            $purpose = "New Installation";
        }
        if ($row['purposeid'] == 2) {
            $purpose = "Repair";
        }
        if ($row['purposeid'] == 3) {
            $purpose = "Removal";
        }
        if ($row['purposeid'] == 4) {
            $purpose = "Replacement";
        }
        if ($row['purposeid'] == 5) {
            $purpose = "Reinstall";
        }
        $purposeid = $row['purposeid'];
        $details = $row["details"];
        $coordinatorid = $row['coordinatorid'];
        $person_name = $row['person_name'];
        $person_phone = $row['cp_phone1'];
        $created_by = $row["name"];
        $vehicleid = $row['vehicleid'];
        $docketid = $row['docketid'];
        $feId = $row['fe_id'];
    }
} else {
    header("Location: bucketlist.php");
    exit;
}

include("header.php");
?>
<div class="panel"> 
    <div class="paneltitle" align="center">Update Bucket List</div>
    <div class="panelcontents">
        <form method="post" id="form1" action="modifybucket.php">
<?php echo($message); ?>
            <input type="hidden" name = "id" value="<?php echo($bucketid) ?>"/>
            <input type="hidden" name = "customerno" value="<?php echo($customerno) ?>"/>
            <input type="hidden" name = "createdby" value="<?php echo($createdby) ?>"/>
            <input type="hidden" name = "priorityid" value="<?php echo($priorityid) ?>"/>
            <input type="hidden" name = "vehicleid" value="<?php echo($vehicleid) ?>"/>
            <input type="hidden" name = "location" value="<?php echo($location) ?>"/>
            <input type="hidden" name = "timeslotid" value="<?php echo($timeslotid) ?>"/>
            <input type="hidden" name = "purposeid" value="<?php echo($purposeid) ?>"/>
            <input type="hidden" name = "details" value="<?php echo($details) ?>"/>
            <input type="hidden" name = "coordinatorid" value="<?php echo($coordinatorid) ?>"/>
            <input type="hidden" name = "docketid"  value="<?php echo ($docketid) ?>"/>

            <table width="100%">
                <tr><td>Bucket Id:</td><td><b><?php echo "B00" . $bucketid; ?></b></td></tr>
                <tr>
                    <td> Appointment Date: </td>
                    <td> <?php echo(date("d-m-Y", strtotime($apt_date))); ?> </td>
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
                    <td> <?php echo($priority); ?> </td>
                </tr>

                <tr>
                    <td> Vehicle No: </td>
                    <td> <?php echo($vehicleno); ?> </td>
                </tr>

                <tr>
                    <td> Location: </td>
                    <td> <?php echo($location); ?> </td>
                </tr>

                <tr>
                    <td> Time Slot: </td>
                    <td> <?php echo($timeslot); ?> </td>
                </tr>

                <tr>
                    <td> Purpose: </td>
                    <td><b><?php echo($purpose); ?></b> </td>
                </tr>

                <tr>
                    <td> Details: </td>
                    <td> <?php echo($details); ?> </td>
                </tr>

                <tr>
                    <td> Co-ordinator Name: </td>
                    <td> <?php echo($person_name); ?> </td>
                </tr>

                <tr>
                    <td> Co-ordinator Phone: </td>
                    <td> <?php echo($person_phone); ?> </td>
                </tr>

                <tr>
                    <td> Created By: </td>
                    <td> <?php echo($created_by); ?> </td>
                </tr>

                <tr>
                    <td>Status</td>
                    <td><select name="sstatus" id="sstatus" onchange="changeFunc(value);">
                            <option value="4" selected>Field Engineer Assignment</option>
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
                    <td> <input name="reschedule_date" id="reschedule_date" type="text" value="<?php echo $apt_date; ?>"/><button id="trigger10">...</button>
                   <td>Comment :</td><td><input id="comment" name="comment" type="text"/></td>
                    </td>
                </tr>       

                <tr id="c_reason" style="display: none;">
                    <td>Cancellation Reason</td> 
                    <td><input name = "creason" id="creason" type="text"></td>
                </tr>

                <tr id="fe">
                    <td>Field Engineer <span style="color:red;">*</span></td>
                    <td><select name="uteamid" id="uteamid">
                            <option value="0">Select a Field Engineer</option>
                            <?php
                            foreach ($team_allot_array as $thisteam) {
                                ?>
                                <option value="<?php echo($thisteam->teamid); ?>" <?php if($thisteam->teamid == $feId){echo "selected";} ?>><?php echo($thisteam->name); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>       
            </table>
            <input type="button" name="save" id="save" value="Save Details" onclick="submitBucket();" />
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
                button: "trigger10" // ID of the button
            });

    function changeFunc($i) {

        if ($i == 1)
        {
            $("#res_date").show();
            $("#c_reason").hide();
            $("#fe").hide();
            $("#un").hide();
            $("#si").hide();
        } else if ($i == 5)
        {
            $("#c_reason").show();
            $("#res_date").hide();
            $("#fe").hide();
            $("#un").hide();
            $("#si").hide();
        } else
        {
            $("#res_date").hide();
            $("#c_reason").hide();
            $("#fe").show();
            $("#un").show();
            $("#si").show();
        }

    }
</script>
<script>
 function submitBucket()
 {
    if($('#sstatus').val()==1){
        if($('#comment').val()==''){
            alert("Please enter comments");
            $('#form1').submit(false);
        }
        else{
        $('#form1').submit();
    }
    }
    else if($('#sstatus').val()==5){
        if($('#creason').val()==''){
            alert("Please enter Cancellation Reason");
           $('#form1').submit(false);
        }
        else{
        $('#form1').submit();
    }
    }
    else{
        $('#form1').submit();
    }
 }  

</script>
