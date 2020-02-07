<?php
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

class testing{
    
}

date_default_timezone_set("Asia/Calcutta");                             
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();
$message="";

// ------------------------------------------------------  Return Bad Units with Simcards  ------------------------------------------------------ //
if(isset($_POST["ureturn"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");         
    $simcardid = GetSafeValueString($_POST["simallotted_all"], "string");   
    $unitid = GetSafeValueString($_POST["uallotted_all"], "string");     
    $teamid = GetSafeValueString($_POST["uteamid_returnall"], "long");
    
    if($teamid=='0'){
     $message ="<span style='color:red;'>Please select return form name</span>";   
    }
    if($simcardid != '0')
    {
        $SQL = sprintf("UPDATE simcard SET trans_statusid= 12, teamid=0, comments='%s' where id=%d",$comments, $simcardid);
        $db->executeQuery($SQL);
    }
    
    if($unitid != '0')
    {
        $SQL = sprintf("UPDATE unit SET trans_statusid= 17, teamid=0, comments = '%s' where uid=%d",$comments, $unitid);
        $db->executeQuery($SQL);
    
        $SQL = sprintf('UPDATE devices SET simcardid=0 where uid='.$unitid);
        $db->executeQuery($SQL);                    
    }
    
    if($simcardid != '0')
    {
        $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
        `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`)
        VALUES (
        1, '%d', '%s', 1, '%s', %d, '%s','%s','%s','%s',%d,'%s')",
        $simcardid, GetLoggedInUserId(), Sanitise::DateTime($today),12, "Simcard Returned","","","",$teamid,$comments);
        $db->executeQuery($SQLunit);    
    }
    
    if($unitid != '0')
    {
        $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
        `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`)
        VALUES (
        1, '%d', '%s', 0, '%s', %d, '%s','%s','%s','%s',%d, '%s')",
        $unitid, GetLoggedInUserId(), Sanitise::DateTime($today),17, "Unit Returned","","","",$teamid, $comments);
        $db->executeQuery($SQLunit);        
    }
}

// ------------------------------------------------------  Test Unit ------------------------------------------------------ //

if(isset($_POST["utest"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");             
    $unitno = GetSafeValueString($_POST["utesting"], "string");     
    $result = GetSafeValueString($_POST["utestingresult"], "string");     

    $SQL = sprintf("UPDATE unit SET trans_statusid= '%s',comments = '%s' where uid=%d",$result, $comments, $unitno);
    $db->executeQuery($SQL);
    
    $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    1, '%d', '%s', 0, '%s', %d, '%s','%s','%s','%s','%s')",
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

// ------------------------------------------------------  Allot Unit and Simcard ------------------------------------------------------ //
if(isset($_POST["uallot"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");                     
    $simcardid = GetSafeValueString($_POST["sready"], "string");   
    $unitid = GetSafeValueString($_POST["uready"], "string");     
    $teamid = GetSafeValueString($_POST["uteamid"], "long");
    
    $SQL = sprintf("UPDATE simcard SET trans_statusid= 19, teamid='%s',comments = '%s' where id=%d",$teamid,$comments,$simcardid);
    $db->executeQuery($SQL);
    
    $SQL = sprintf("UPDATE unit SET trans_statusid= 18, teamid='%s',comments = '%s' where uid=%d",$teamid, $comments, $unitid);
    $db->executeQuery($SQL);

    if($simcardid != '0' && $unitid != '0')
    {
        $SQL = sprintf('UPDATE devices SET simcardid='.$simcardid.' where uid='.$unitid);
        $db->executeQuery($SQL);  
        
        $sql = sprintf("select simcardno from simcard where id=".$simcardid);
        $db->executeQuery($sql);
        $vids = array();
        while ($row = $db->get_nextRow())
        {
            $simcardno = $row["simcardno"];
        }
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
    $simcardid, GetLoggedInUserId(), Sanitise::DateTime($today),19, "Simcard Allotment",$simcardno,"","",$teamid,$comments);
    $db->executeQuery($SQLunit);    
    
    $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`)
    VALUES (
    1, '%d', '%s', 0, '%s', %d, '%s','%s','%s','%s',%d,'%s')",
    $unitid, GetLoggedInUserId(), Sanitise::DateTime($today),18, "Device Allotment",$simcardno,"","",$teamid, $comments);
    $db->executeQuery($SQLunit);        
}

// ------------------------------------------------------  Re-Allot Unit and Simcard ------------------------------------------------------ //
if(isset($_POST["ureallot"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");                         
    $simcardid = GetSafeValueString($_POST["simallotted"], "string");   
    $unitid = GetSafeValueString($_POST["uallotted"], "string");     
    $teamid = GetSafeValueString($_POST["uteamid_new"], "long");
    
    if($teamid != 0)
    {
        $SQL = sprintf("UPDATE simcard SET trans_statusid= 19, teamid='%s',comments='%s' where id=%d",$teamid,$comments,$simcardid);
        $db->executeQuery($SQL);

        $SQL = sprintf("UPDATE unit SET trans_statusid= 18, teamid='%s',comments='%s' where uid=%d",$teamid, $comments, $unitid);
        $db->executeQuery($SQL);

        if($unitid != '0' && $simcardid == '0')
        {
            $SQL = sprintf('UPDATE devices SET simcardid=0 where uid='.$unitid);
            $db->executeQuery($SQL);        
        }
        elseif($unitid == '0' && $simcardid != '0')
        {
            $SQL = sprintf('UPDATE devices SET simcardid=0 where simcardid='.$simcardid);
            $db->executeQuery($SQL);                    
        }
        elseif($unitid != '0' && $simcardid != '0')
        {
            $SQL = sprintf('UPDATE devices SET simcardid='.$simcardid.' where uid='.$unitid);
            $db->executeQuery($SQL);                    
        }

        $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
        `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`)
        VALUES (
        1, '%d', '%s', 1, '%s', %d, '%s','%s','%s','%s',%d,'%s')",
        $simcardid, GetLoggedInUserId(), Sanitise::DateTime($today),19, "Simcard Re-Allotment","","","",$teamid,$comments);
        $db->executeQuery($SQLunit);    

        $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
        `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `allot_teamid`, `comments`)
        VALUES (
        1, '%d', '%s', 0, '%s', %d, '%s','%s','%s','%s',%d,'%s')",
        $unitid, GetLoggedInUserId(), Sanitise::DateTime($today),18, "Device Re-Allotment","","","",$teamid,$comments);
        $db->executeQuery($SQLunit);        
    }
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
    <form method="post" name="myform" id="myform" action="testing.php" enctype="multipart/form-data">
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
        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>            
        </tr>        
        
    </table>

    <div><input type="submit" id="utest" name="utest" value="Submit"/></div>
    </form>
    <hr/>
<!------------------------------------------------------  Test Simcard ------------------------------------------------------>
    <form method="post" name="myform" id="myform" action="testing.php" enctype="multipart/form-data">
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
    <form method="post" name="myform" id="myform" action="testing.php" enctype="multipart/form-data">
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

<!------------------------------------------------------  Allot Unit and Simcard ------------------------------------------------------>
    <div class="panel">
    <div class="paneltitle" align="center">
        Allotment</div>
    <div class="panelcontents">
    <form method="post" name="myform" id="myform" action="testing.php" enctype="multipart/form-data">
        <h3>Allot to Team</h3>
    <table width="50%">

         <tr>
        <td>Elixir</td>
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
            ( Ready Device List)
        </td>
        </tr>

         <tr>
        <td>Sim Card No. </td>
        <td><select name="sready">
                    <option value="0">No Simcard</option>                
                <?php
                foreach($activatedsimcards as $thiscard)
                {
                ?>
                <option value="<?php echo($thiscard->id); ?>"><?php echo($thiscard->simcardno); ?></option>
                <?php
                }
                ?>
            </select>
            ( Activated Sim Card List)
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
    <form method="post" name="myformreallot" id="myformreallot" action="testing.php" onsubmit="ValidateForm(); return false;" enctype="multipart/form-data">
        <h3>Re-Allot to Team</h3>        
    <table width="50%">    
         <tr>
        <td>Allot From</td>
        <td><select name="uteamid" id="uteamid" onChange="pullunit();">
                <option value="0">Select an Elixir</option>
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
        <td id="uready_td"><select id="uallotted" name="uallotted"><option value="-1">No Units</option></select>
            ( Allotted Device List with selected Elixir)            
        </td>
        </tr>

         <tr>
        <td>Sim Card No. </td>
        <td id="simready_td"><select name="simallotted" id="simallotted"><option value="-1">No Simcards</option></select>
            ( Allotted Simcard List with selected Elixir)
        </td>
        </tr>
<tr><td></td><td>---</td></tr>        
         <tr>
        <td>Allot To</td>
        <td><select name="uteamid_new">
                <option value="0">Select an Elixir</option>
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
     <?php if(!empty($message)){echo $message;}?>   
    <form method="post" name="myformreturn" id="myformreturn" onsubmit="ValidateForm2(); return false;" action="testing.php" enctype="multipart/form-data">
    <h3>Return Units and Simcards</h3>        
    
    <table width="50%">

         <tr>
        <td>Return From</td>
        <td><select name="uteamid_returnall" id="uteamid_returnall" onChange="pullallunit();">
                <option value="0">Select an Elixir</option>
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
        <td id="uready_all_td"><select id="uallotted_all" name="uallotted_all"><option value="-1">No Units</option></select>
            ( Allotted / Bad Device List with selected Elixir)            
        </td>
        </tr>

         <tr>
        <td>Sim Card No. </td>
        <td id="simready_all_td"><select name="simallotted_all" id="simallotted_all"><option value="-1">No Simcards</option></select>
            ( Allotted / Bad Simcard List with selected Elixir)
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

<?php
}

$details = Array();
$data = new testing();        
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
        $data = new testing();        
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
        $data = new testing();
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

$dg = new objectdatagrid( $details );
$dg->AddColumn("Elixir", "teamname");
$dg->AddColumn("Good Units", "totalcount");
$dg->AddColumn("Bad Units", "totalcountbadunits");
$dg->AddColumn("Good Simcards", "totalcountsim");
$dg->AddColumn("Bad Simcards", "totalcountsimbad");
$dg->AddAction("View", "../../images/unit.png", "unitdetails.php?tid=%d");
$dg->AddRightAction("View", "../../images/simcard.png", "simdetails.php?tid=%d");
$dg->SetNoDataMessage("No Data");
$dg->AddIdColumn("teamid");

?>
<br/>

<div class="panel">
<div class="paneltitle" align="center">Team-Wise Allocation</div>
<div class="panelcontents">
<table width="100%">
<tr>
<td>    
<table align="center" border="1">
<tr>    
<td><font size="3">Total Good Units</font></td><td><font size="3"><?php echo ($totalgoodunits); ?></font></td></tr>
<tr>    
<td><font size="3">Total Bad Units</font></td><td><font size="3"><?php echo ($totalbadunits); ?></font></td></tr>
<tr>    
<td><b><font size="3">Total Units</font></b></td><td><b><font size="3"><?php echo ($totalgoodunits + $totalbadunits); ?></font></b></td></tr>
</table>
</td>
<td>
<table align="center" border="1">    
<tr>    
<td><font size="3">Total Good Simcards</font></td><td><font size="3"><?php echo ($totalgoodsimcards); ?></font></td></tr>
<tr>    
<td><font size="3">Total Bad Simcards</font></td><td><font size="3"><?php echo ($totalbadsimcards); ?></font></td></tr>
<tr>    
<td><b><font size="3">Total Simcards</font></b></td><td><b><font size="3"><?php echo ($totalgoodsimcards + $totalbadsimcards); ?></font></b></td></tr>
</table>
</td>
</tr>
</table>
<br/>    
<?php $dg->Render(); ?>
</div>

</div>

<?php
include("footer.php");
?>

<script>

// -------------------------------------------- Pull for Allotment ------------------------------------------- //

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