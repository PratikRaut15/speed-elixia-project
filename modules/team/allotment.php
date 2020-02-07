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

class allotment{
    
}

date_default_timezone_set("Asia/Calcutta");                             
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();

// ------------------------------------------------------  Allot Unit and Simcard ------------------------------------------------------ //
if(isset($_POST["uallot"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");     
    $unitid = GetSafeValueString($_POST["uready"], "string");     
    $teamid = GetSafeValueString($_POST["uteamid"], "long");
    $simid = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status 
    FROM
    devices INNER JOIN simcard ON simcard.id = devices.simcardid INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid 
    WHERE devices.simcardid <> 0 AND 
    devices.uid = ".$unitid." LIMIT 1");
    $db->executeQuery($simid);
    if($db->get_rowCount()>0)
    {
        while($row = $db->get_nextRow())
        {
            $simcardid = $row["simid"];
            $simcardno = $row["simcardno"];
            $status = $row["status"];
        }
    }
        $SQL = sprintf("UPDATE simcard SET trans_statusid= 19, teamid='%s',comments = '%s' where id=%d",$teamid,$comments,$simcardid);
        $db->executeQuery($SQL);

        $SQL = sprintf("UPDATE unit SET trans_statusid= 18, teamid='%s',comments = '%s' where uid=%d",$teamid, $comments, $unitid);
        $db->executeQuery($SQL);

    if($simcardid != '0' && $unitid != '0')
    {
        $SQL = sprintf('UPDATE devices SET simcardid='.$simcardid.' where uid='.$unitid);
        $db->executeQuery($SQL);            
    }
    else
    {
        $SQL = sprintf('UPDATE devices SET simcardid=0 where uid='.$unitid);
        $db->executeQuery($SQL);                    
    }
    
    $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`)
    VALUES (
    1, '%d', '%s', 1, '%s', %d, '%s','%s','%s','%s',%d,'%s')",
    $simcardid, GetLoggedInUserId(), Sanitise::DateTime($today),19, "Simcard Allotment","","","",$teamid,$comments);
    $db->executeQuery($SQLunit);    
    
    $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`)
    VALUES (
    1, '%d', '%s', 0, '%s', %d, '%s','%s','%s','%s',%d,'%s')",
    $unitid, GetLoggedInUserId(), Sanitise::DateTime($today),18, "Device Allotment","","","",$teamid, $comments);
    $db->executeQuery($SQLunit);         
}

if(IsDistributor())
{

    $SQL = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE unit.teamid=".$_SESSION['sessionteamid']." AND trans_statusid IN (18)");
    $db->executeQuery($SQL);
    $readyunits = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $unit = new allotment();
            $unit->unitno = $row["unitno"]."[ ".$row["status"]." ]";
            $unit->uid = $row["uid"];        
            $readyunits[] = $unit;        
        }    
    }

    $SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno,trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (11)");
    $db->executeQuery($SQL);
    $activatedsimcards = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $simcard = new allotment();
            $simcard->simcardno  = $row["simcardno"]."[ ".$row["status"]." ]";
            $simcard->id = $row["simid"];        
            $activatedsimcards[] = $simcard;        
        }    
    }
    $dist_id = $_SESSION['sessionteamid'];
    $SQL = sprintf("SELECT team.teamid, team.name FROM ".DB_PARENT.".team where `role`='Dealer' AND `distributor_id`=".$dist_id);
    $db->executeQuery($SQL);
    $team_allot_array = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $team = new allotment();
            $team->teamid  = $row["teamid"];
            $team->name = $row["name"];        
            $team_allot_array[] = $team;        
        }    
    }

?>
<!------------------------------------------------------  Allot Unit and Simcard ------------------------------------------------------>
<div class="panel">
    <div class="paneltitle" align="center">
        Allotment</div>
    <div class="panelcontents">
    <form method="post" name="myform" id="myform" action="allotment.php" enctype="multipart/form-data">
        <h3>Allot to Dealers</h3>
    <table width="50%">

         <tr>
        <td>Dealers</td>
        <td><select name="uteamid">
                <?php
                foreach($team_allot_array as $thisteam)
                {
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
        <td><select name="uready">
                    <option value="0">No Unit</option>                                
                <?php
                foreach($readyunits as $thisunit)
                {
                ?>
                <option value="<?php echo($thisunit->uid); ?>"><?php echo($thisunit->unitno); ?></option>
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
    <div><input type="submit" id="uallot" name="uallot" value="Allot"/></div>
    </form>
    </div>
    </div>




<?php
}

$details = Array();
$data = new allotment();        
$data->teamid = 0;
$totalgoodunits = 0;
$totalbadunits = 0;
$totalgoodsimcards = 0;
$totalbadsimcards = 0;
$data->teamname = "Elixia Tech";        
$data->x = 1;                        
$details[] = $data;        

$db = new DatabaseManager();
$SQL = sprintf("SELECT teamid, name FROM ".DB_PARENT.".team");
$x = 2;
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $data = new allotment();        
        $data->teamid = $row["teamid"];
        $data->teamname = $row["name"];        
        $data->x = $x;                        
        $details[] = $data;        
        $x++;
    }    
}

if(isset($details))
{
    foreach($details as $thisdetail)
    {
        $SQL = sprintf("SELECT count(*) as totalcount FROM unit WHERE unit.trans_statusid IN (1,2,4,9,18) AND unit.teamid = %d",$thisdetail->teamid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow())
            {
                    $thisdetail->totalcount = $row["totalcount"];
                    $totalgoodunits+= $thisdetail->totalcount;
            }    
        }
    }
}

if(isset($details))
{
    foreach($details as $thisdetail)
    {
        $SQL = sprintf("SELECT count(*) as totalcount FROM simcard WHERE simcard.trans_statusid IN (11,19) AND simcard.teamid  = %d",$thisdetail->teamid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow())
            {
                $thisdetail->totalcountsim = $row["totalcount"];
                $totalgoodsimcards+= $thisdetail->totalcountsim;
            }    
        }
    }
}

if(isset($details))
{
    foreach($details as $thisdetail)
    {
        $SQL = sprintf("SELECT count(*) as totalcount FROM simcard WHERE simcard.trans_statusid IN (12,15,21) AND simcard.teamid  = %d",$thisdetail->teamid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow())
            {
                $thisdetail->totalcountsimbad = $row["totalcount"];
                $totalbadsimcards+= $thisdetail->totalcountsimbad;
            }    
        }
    }
}

if(isset($details))
{
    foreach($details as $thisdetail)
    {
        $SQL = sprintf("SELECT count(*) as totalcount FROM unit WHERE unit.trans_statusid IN (3,17,20) AND unit.teamid = %d",$thisdetail->teamid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow())
            {
                $thisdetail->totalcountbadunits = $row["totalcount"];
                $totalbadunits+=$thisdetail->totalcountbadunits;
            }    
        }
    }
}

    $SQL = sprintf("SELECT team.teamid, unit.trans_statusid, team.name, count(*) as totalcount, unit.teamid FROM unit LEFT OUTER JOIN ".DB_PARENT.".team ON team.teamid = unit.teamid WHERE unit.trans_statusid IN (7)");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $data = new allotment();
            $data->teamname = "Gone for Repair";
            $data->teamid = -1;
            $data->totalcountbadunits = $row["totalcount"];
            $totalbadunits+=$thisdetail->totalcountbadunits;        
            $data->totalcountsim = 0;
            $data->totalcountsimbad = 0;        
            $data->totalcount = 0;
            $data->x = $x;
            $details[] = $data;        
            $x++;
        }    
    }
?>
<?php
include("footer.php");
?>

