<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
$_scripts[] = "../../scripts/jquery.min.js";
 
include("header.php");

class testing{
    
}

date_default_timezone_set("Asia/Calcutta");                             
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();
$message="";

// ------------------------------------------------------  Test Unit ------------------------------------------------------ //

if(isset($_POST["utest"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");             
    $unitno = GetSafeValueString($_POST["utesting"], "string");     
    $result = GetSafeValueString($_POST["utestingresult"], "string");
    $device_location = GetSafeValueString($_POST["device_location"], "int");     

    $SQL = sprintf("UPDATE unit SET trans_statusid= '%s',comments = '%s',unit_location_box_number='%d' where uid=%d",$result, $comments,$device_location,$unitno);
    $db->executeQuery($SQL);
    
    $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (1, '%d', '%s', 0, '%s', %d, '%s','%s','%s','%s','%s')",
    $unitno, GetLoggedInUserId(), Sanitise::DateTime($today),$result, "Tested","","","",$comments);
    $db->executeQuery($SQLunit);


    
}

// ------------------------------------------------------  Test Simcard ------------------------------------------------------ //
if(isset($_POST["stest"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");                 
    $simcardno = GetSafeValueString($_POST["stesting"], "string");     
    $result = GetSafeValueString($_POST["stestingresult"], "string");     
    
    $SQL = sprintf("UPDATE simcard SET trans_statusid= '%s',comments = '%s' where id=%d",$result,$comments, $simcardno);
    $db->executeQuery($SQL);
    
    $SQLsim = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    1, '%d', '%s', 1, '%s', %d, '%s','%s','%s','%s','%s')",
    $simcardno, GetLoggedInUserId(), Sanitise::DateTime($today),$result, "Suspected","","","",$comments);
    $db->executeQuery($SQLsim);                        
}


/////////////////////////////Bad unit submit issues////////////////////////////////////////////////////////////////
$badmsg ="";
if(isset($_POST["badunit"]))
{
    $comments = GetSafeValueString($_POST["issues"], "string");                         
    $badunit = GetSafeValueString($_POST["badtesting"], "string");   
    
    if(empty($comments))
    {
        $badmsg="Please enter issue for bad Unit";
    }
    else
    {
        $SQL = sprintf('UPDATE unit SET issue="'.$comments.'" where uid='.$badunit);
        $db->executeQuery($SQL);    
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(IsHead() || IsService())
{
$SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (1,4,17) ORDER BY trans_status.status asc, unit.uid asc");
$db->executeQuery($SQL);
$units = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $unit = new testing();
        $unit->unitno = $row["unitno"]."[ ".$row["status"]." ]";
        $unit->uid = $row["uid"];        
        $units[] = $unit;        
    }    
}

$SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (2) ORDER BY `unit`.`unitno` DESC");
$db->executeQuery($SQL);
$readyunits = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $unit = new testing();
        $unit->unitno = $row["unitno"]."[ ".$row["status"]." ]";
        $unit->uid = $row["uid"];        
        $readyunits[] = $unit;        
    }    
}

$SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (12) order by simcard.simcardno asc");
$db->executeQuery($SQL);
$badsimcards = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $simcard = new testing();
        $simcard->simcardno  = $row["simcardno"]."[ ".$row["status"]." ]";
        $simcard->id = $row["simid"];        
        $badsimcards[] = $simcard;        
    }    
}

$SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (11) ORDER BY `simcard`.`simcardno` ASC");
$db->executeQuery($SQL);
$activatedsimcards = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $simcard = new testing();
        $simcard->simcardno  = $row["simcardno"]."[ ".$row["status"]." ]";
        $simcard->id = $row["simid"];        
        $activatedsimcards[] = $simcard;        
    }    
}

$SQL = sprintf("SELECT team.teamid, team.name FROM ".DB_PARENT.".team ORDER BY name asc");
$db->executeQuery($SQL);
$team_allot_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $team = new testing();
        $team->teamid  = $row["teamid"];
        $team->name = $row["name"];        
        $team_allot_array[] = $team;        
    }    
}
//////////////////////Bad Unit list///////////////////////

$db = new DatabaseManager();
$SQL = sprintf("SELECT unit.unitno, unit.uid, trans_status.status FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (3) ORDER BY `unit`.`unitno` ASC ");
$db->executeQuery($SQL);
$badunits = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $unit = new testing();
        $unit->unitno = $row["unitno"]."[ ".$row["status"]." ]";
        $unit->uid = $row["uid"];        
        $badunits[] = $unit;        
    }    
}


?>

<!------------------------------------------------------  Test Unit ------------------------------------------------------>
    <div class="panel">
    <div class="paneltitle" align="center">
        Testing</div>
    <div class="panelcontents">
    <form method="post" name="myform" id="myform" action="invtesting.php" enctype="multipart/form-data">
        <h3>Testing Units</h3>                        
    <table width="50%">
         <tr>
        <td>Unit No. </td>
        <td><select name="utesting">
                <?php
                foreach($units as $thisunit)
                {
                ?>
                <option value="<?php echo($thisunit->uid); ?>"><?php echo($thisunit->unitno); ?></option>
                <?php
                }
                ?>
            </select>
            ( Fresh / Repaired / Suspected Device List)
        </td>
        </tr>

         <tr>
        <td>Result </td>
        <td><select name="utestingresult">
                <option value="2">Ready</option>
                <option value="3">Bad</option>                
            </select>
        </td>
        </tr>
         <tr>
                        <td>Device Location<span style='color:red;'> *</span></td>
                        <td><select name = "device_location" id="device_location">
                        </select></td>  
                        <td><label name="device_location_label" id="device_location_label"></label></td>          
        </tr>   
        <tr>
        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>            
        </tr>        
        
    </table>

    <div><input type="submit" id="utest" name="utest" value="Submit"/></div>
    </form>
    <hr/>
<!------------------------------------------------------  Test Simcard ------------------------------------------------------>
    <form method="post" name="myform" id="myform" action="invtesting.php" enctype="multipart/form-data">
        <h3>Testing Simcards</h3>                
    <table width="50%">
         <tr>
        <td>Sim Card No. </td>
        <td><select name="stesting">
                <?php
                foreach($badsimcards as $thiscard)
                {
                ?>
                <option value="<?php echo($thiscard->id); ?>"><?php echo($thiscard->simcardno); ?></option>
                <?php
                }
                ?>
            </select>
            ( Bad Sim Card List)
        </td>
        </tr>
        
         <tr>
        <td>Result </td>
        <td><select name="stestingresult">
                <option value="11">Activate</option>
                <option value="15">Apply for Disconnection</option>                
            </select>
        </td>
        </tr>
        
        <tr>
        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>            
        </tr>        
        
    </table>

    <div><input type="submit" id="stest" name="stest" value="Submit"/></div>
    </form>
    <hr>
    <form method="post" name="myform" id="myform" action="invtesting.php" enctype="multipart/form-data">
        <h3>Issues</h3>                
        <?php if(!empty($badmsg)){ echo"<span style='color:red;font-size:12px;'>".$badmsg."</span>"; }?>
    <table width="50%">
         <tr>
        <td>Bad Unit List <span style="color:red; font-size:12px;">*</span></td>
        <td><select name="badtesting">
              <?php
                foreach($badunits as $thisunit)
                {
                ?>
                <option value="<?php echo($thisunit->uid); ?>"><?php echo($thisunit->unitno); ?></option>
                <?php
                }
                ?>
            </select>
            ( Bad Device List)
        </td>
        </tr>
        <tr>
        <td>Issue <span style="color:red; font-size:12px;">*</span></td><td><input name = "issues" id="issues" type="text"></td>            
        </tr>        
        
    </table>

    <div><input type="submit" id="badunit" name="badunit" value="Submit"/></div>
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
$(document).ready(function () {    
        jQuery.ajax({
        type: "POST",
        url: "route_ajax.php",
        data: "get_device_location=1",
            success: function(data){
                var data=JSON.parse(data);
                //<-------- add this line
                $('#device_location').html("");
                $('#device_location').append('<option value = "0">'+"Select Device"+'</option>');
                  //<-------- add this line
                $.each(data ,function(i,text){

                    $('#device_location').append('<option value = '+text.unit_location_box_number+'> '+text.location_name+' - '+text.device_name+'</option>');
                    
                });
            }
        });
     });

function pullunit()
{
    var uteamid = jQuery('#uteamid').val();        
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {uteamid : uteamid},
        dataType: 'html',
        success: function(html){
            jQuery("#uready_td").html('');
            jQuery("#uready_td").append(html);
            
            // Pull Simcards
            pullsimcards();
        }
    });
    return false;       
}

function pullsimcards()
{
    var steamid = jQuery('#uteamid').val();        
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {steamid : steamid},
        dataType: 'html',
        success: function(html){
            jQuery("#simready_td").html('');
            jQuery("#simready_td").append(html);            
        }
    });
    return false;           
}

function pullsimcard_from_unit()
{
    var uallotted = jQuery('#uallotted').val(); 
    var simteamid = jQuery('#uteamid').val();        
    
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {uallotted : uallotted, simteamid : simteamid},
        dataType: 'html',
        success: function(html){
            jQuery("#simready_td").html('');
            jQuery("#simready_td").append(html);            
        }
    });
    return false;           
}

// -------------------------------------------- Pull for Return ------------------------------------------- //
function pullallunit()
{
    var uteamid = jQuery('#uteamid_returnall').val();        
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {uteamid_returnall : uteamid},
        dataType: 'html',
        success: function(html){
            jQuery("#uready_all_td").html('');
            jQuery("#uready_all_td").append(html);
            
            // Pull Simcards
            pullallsimcards();
        }
    });
    return false;       
}

function pullallsimcards()
{
    var steamid = jQuery('#uteamid_returnall').val();        
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {steamid_all : steamid},
        dataType: 'html',
        success: function(html){
            jQuery("#simready_all_td").html('');
            jQuery("#simready_all_td").append(html);            
        }
    });
    return false;           
}

function pullallsimcard_from_unit()
{
    var uallotted = jQuery('#uallotted_all').val(); 
    var simteamid = jQuery('#uteamid_returnall').val();        
    
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {uallotted_all : uallotted, simteamid_all : simteamid},
        dataType: 'html',
        success: function(html){
            jQuery("#simready_all_td").html('');
            jQuery("#simready_all_td").append(html);            
        }
    });
    return false;           
}

function  ValidateForm(){
    
    var uteamid =$("#uteamid").val();
    var uteamid_new = $("#uteamid_new").val();
    if(uteamid =="0"){
        alert("Please select Allot From");
    }else if(uteamid_new=="0"){
        alert("Please select Allot To");
    }else{
          $("#myformreallot").submit();
    }
}

function ValidateForm2(){
    var uteamid_returnall = $("#uteamid_returnall").val();
    if(uteamid_returnall==""){
        alert("Please select Return From.");
        return false;
    }else{
          $("#myformreturn").submit();
    }
    
    
}

</script>