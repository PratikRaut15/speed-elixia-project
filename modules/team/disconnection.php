<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");

$_scripts[] = "../../scripts/trash/prototype.js";
 
include("header.php");

class testing{
    
}
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();

//disconnection
if(isset($_POST["discsim"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");         
    $simcardno = GetSafeValueString($_POST["discsimcard"], "string");     

    $SQL = sprintf("UPDATE simcard SET trans_statusid= 16, customerno = -1, comments ='%s' where id=%d",$comments,$simcardno);
    $db->executeQuery($SQL);
    
    $SQLsim = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    '-1', '%d', '%s', 1, '%s', 16, '%s','%s','%s','%s','%s')",
    $simcardno, GetLoggedInUserId(), Sanitise::DateTime($today), "Disconnected","","","",$comments);
    $db->executeQuery($SQLsim);                    
    
}

if(isset($_POST["react_sim"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");             
    $simcardno = GetSafeValueString($_POST["discsimcard"], "string");     

    $SQL = sprintf("UPDATE simcard SET trans_statusid= 11, customerno = 1, comments = '%s' where id=%d",$comments, $simcardno);
    $db->executeQuery($SQL);
    
    $SQLsim = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    1, '%d', '%s', 1, '%s', 11, '%s','%s','%s','%s','%s')",
    $simcardno, GetLoggedInUserId(), Sanitise::DateTime($today), "Disconnected","","","",$comments);
    $db->executeQuery($SQLsim);                    
    
}

$db = new DatabaseManager();
$SQL = sprintf("SELECT simcard.id as simid, simcard.simcardno, trans_status.status FROM simcard INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = simcard.trans_statusid WHERE trans_statusid IN (15) ORDER BY simcard.id asc");
$db->executeQuery($SQL);
$discsims = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $unit = new testing();
        $unit->simcardno = $row["simcardno"]."[ ".$row["status"]." ]";
        $unit->id = $row["simid"];        
        $discsims[] = $unit;        
    }    
}

if(!IsData())
{
    if(IsAdmin() || IsHead())
{
?>    
    <div class="panel">
    <div class="paneltitle" align="center">
        Disconnection</div>
    <div class="panelcontents">
        <form method="post" name="myform" id="myform" action="disconnection.php" enctype="multipart/form-data">
    <table width="50%">
        <tr>
        <td>Sim Card</td><td><select name="discsimcard">
                <?php
                foreach($discsims as $thiscard)
                {
                ?>
                <option value="<?php echo($thiscard->id); ?>"><?php echo($thiscard->simcardno); ?></option>
                <?php
                }
                ?>
            </select>
            ( Apply for Disconnection Simcard List)</td>            
        </tr>        

        <tr>
        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>            
        </tr>        
        
    </table>

    <div><input type="submit" id="discsim" name="discsim" value="Disconnect"/>&nbsp;<input type="submit" id="react_sim" name="react_sim" value="Reactivate"/></div>
    </form>

        <?php
}
}
?>
        
<br/>

<?php
include("footer.php");
?>
