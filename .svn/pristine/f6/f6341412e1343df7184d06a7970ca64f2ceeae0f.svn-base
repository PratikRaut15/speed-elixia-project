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
$urepairmsg="";
if(isset($_POST["urepair"]))
{
    $comments = GetSafeValueString($_POST["hiddenissue"], "string");
    $unitno = GetSafeValueString($_POST["urepairunit"], "string");
    $add7days = date('Y-m-d', strtotime('+7 days'));
    if(empty($comments)){
        $urepairmsg="No issue for this unit.";
    }
    else
    {
        $SQL = sprintf("UPDATE unit SET trans_statusid= 7, customerno = -1, comments = '%s',repairtat = '%s' where uid=%d",$comments,$add7days, $unitno);
        $db->executeQuery($SQL);

        $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
        `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
        VALUES (
        -1, '%d', '%s', 0, '%s', 7, '%s','%s','%s','%s','%s')",
        $unitno, GetLoggedInUserId(), Sanitise::DateTime($today), "Sent to Repair","","","",$comments);
        $db->executeQuery($SQLunit);
    }
}
$message="";
if(isset($_POST["teamrepair"]))
{
     $unitno = GetSafeValueString($_POST["uallotted"], "string");
     $comments = GetSafeValueString($_POST["comments"], "string");

    $add7days = date('Y-m-d', strtotime('+7 days'));
    if($unitno=="-1"){
     $message = "Please select Unit No.";
    }elseif(empty($comments)){
        $message = "Please enter issue.";
    }else{
    $SQL = sprintf("UPDATE unit SET trans_statusid= 7, customerno = -1,teamid = 0 ,issue = '%s',repairtat = '%s' where uid=%d",$comments,$add7days, $unitno);
    $db->executeQuery($SQL);

    $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    -1, '%d', '%s', 0, '%s', 7, '%s','%s','%s','%s','%s')",
    $unitno, GetLoggedInUserId(), Sanitise::DateTime($today), "Sent to Repair","","","",$comments);
    $db->executeQuery($SQLunit);
    }
}

if(isset($_POST["urepaired"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");
    $unitno = GetSafeValueString($_POST["urepairedunit"], "string");
    $repairdate = "0000-00-00";

    $SQL = sprintf("UPDATE unit SET trans_statusid= 4, customerno = 1, comments='%s', repairtat='%s' where uid=%d",$comments,$repairdate,$unitno);
    $db->executeQuery($SQL);

    $SQL1 = sprintf("UPDATE unit SET comments_repair ='',issue='' where uid= ".$unitno);
    $db->executeQuery($SQL1);


    $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    1, '%d', '%s', 0, '%s', 4, '%s','%s','%s','%s','%s')",
    $unitno, GetLoggedInUserId(), Sanitise::DateTime($today), "Receive Repaired","","","",$comments);
    $db->executeQuery($SQLunit);

}
$ureplacemsg="";
if(isset($_POST["ureplace"]))
{
    $comments = GetSafeValueString($_POST["comments"], "string");
    $unitno = GetSafeValueString($_POST["ureplaceunit"], "string");
    $devicetype = GetSafeValueString($_POST['device'], "string");
    //$sensor = GetSQLValueString($_POST['sensor'], "string");
    //////////sensors////////////////////////////////////////////
    $acesensor = GetSafeValueString($_POST['acsensor'], "string");
    $acdigitalopp = GetSafeValueString($_POST['acdigitalopp'], "string");
    $gensetsensor = GetSafeValueString($_POST['gensetsensor'], "string");
    $gensetdigitalopp = GetSafeValueString($_POST['gensetdigitalopp'], "string");
    $doorsensor = GetSafeValueString($_POST['doorsensor'], "string");
    $doordigitalopp = GetSafeValueString($_POST['doordigitalopp'], "string");
    $fuelsensor =  GetSafeValueString($_POST['fuelsensor'], "string");
    $fuelanalog =  GetSafeValueString($_POST['fuelanalog'],"string");
    ////////////////////////////////////////////////////////////////
    $tempsen = GetSafeValueString($_POST['tempsen'], "string");
    $analog1 = GetSafeValueString($_POST["panalog1"], "long");
    $analog2 = GetSafeValueString($_POST["panalog2"], "long");
    $panic = GetSafeValueString($_POST['panic'], "string");
    $buzzer = GetSafeValueString($_POST['buzzer'], "string");
    $immobilizer = GetSafeValueString($_POST['immobilizer'], "string");
    $twowaycom = GetSafeValueString($_POST['twowaycom'], "string");
    $portable = GetSafeValueString($_POST['portable'], "string");
    $punitno = GetSafeValueString($_POST["newunitno"], "string");
    $transmitno1 = GetSafeValueString($_POST['transmitno'],"string");

    if($punitno==""){
        $ureplacemsg ="Unit number should not be blank.";
    }
    $sql = sprintf("SELECT `unitno` FROM `unit` WHERE unitno='".$punitno."'");
    $db->executeQuery($sql);
    if($db->get_rowCount()>0)
    {
        $ureplacemsg = "Unit Number Already exists.";
    }

    if($ureplacemsg==""){

   if($fuelsensor=='4'){
       $fs='1';
   }else{
       $fs='0';
   }

    if($acesensor=='1'){
        $acs ='1';
    }else{
        $acs ='0';
    }
    if($acdigitalopp=='1' && $acesensor=='1'){
        $acopp =1;
    }else{
        $acopp =0;
    }

    if($gensetsensor=='2'){
        $gss =1;
    }else{
        $gss =0;
    }

    $transmitno="";

    if($gensetsensor=='2'){
        $transmitno = $transmitno1;
    }


    if($gensetdigitalopp=='1' && $gensetsensor=='2'){
        $gssopp =1;
    }else{
        $gssopp =0;
    }

    if($doorsensor=='3'){
        $dos =1;
    }else{
        $dos =0;
    }

    if($doordigitalopp=='1' && $doorsensor=='3'){
        $dooropp =1;
    }else{
        $dooropp =0;
    }

   if($devicetype == 1){
       $type_value = $type_value + 0;
   }
   if($devicetype == 2 && $acesensor==1){
       $type_value = $type_value + 1;
   }
   if($devicetype == 2 && $gensetsensor==2){
       $type_value = $type_value + 2;
   }
   if($devicetype == 2 && $doorsensor==3){
       $type_value = $type_value + 4;
   }

    if($devicetype == 2 && $tempsen == 1 && $analog1 != 0 )
    {
        $type_value = $type_value + 8;
    }
    if($devicetype == 2 && $tempsen == 2 && $analog1 !=0 && $analog2 != 0)
    {
        $type_value = $type_value + 16;
    }
   if($devicetype == 2 && $panic ==1){
       $type_value = $type_value + 32;
   }
   if($devicetype == 2 && $buzzer ==1){
       $type_value = $type_value + 64;
   }
   if($devicetype == 2 && $immobilizer ==1){
       $type_value = $type_value + 128;
   }
   if($devicetype == 2 && $twowaycom ==1){
       $type_value = $type_value + 256;
   }
    if($devicetype == 2 && $portable ==1){
       $type_value = $type_value + 512;
   }
   if($devicetype == 2 && $fuelsensor == 4 && $fuelanalog != 0){
       $type_value = $type_value + 1024;
   }


    $SQL = sprintf("UPDATE unit SET trans_statusid= 9, customerno = -1, comments='%s' where uid=%d",$comments, $unitno);
    $db->executeQuery($SQL);
    ///start
    $SQLUnit =sprintf("INSERT INTO unit(`customerno`,`unitno`,`trans_statusid`,`comments`) VALUES (1,'%s',1,'%s')", $punitno,$comments);

    $SQLunitADV = sprintf( "INSERT INTO unit (
    `customerno` ,`unitno`,`acsensor`, `is_ac_opp`,`gensetsensor`,`is_genset_opp`,`doorsensor`,`is_door_opp`, `trans_statusid`, `comments`,`transmitterno`,`digitalioupdated`)
    VALUES (
    1, '%s', '%d', '%d','%d','%d','%d','%d',1,'%s','%s','%s')",
    $punitno,$acs,$acopp,$gss,$gssopp,$dos,$dooropp, $comments,$transmitno,$today);

    if($devicetype == 1){
    $db->executeQuery($SQLUnit);
    }else{
    $db->executeQuery($SQLunitADV);
    }
    $unitid = $db->get_insertedId();

   // fuel temprature
    if($devicetype==2 && $fs==1 && $fuelanalog!=0){
        $SQL = sprintf("UPDATE unit SET fuelsensor=".$fuelanalog." where unitno='%s'",$punitno);
        $db->executeQuery($SQL);
    }

    // Temperature Sensor 1
     if($devicetype ==2 && $tempsen == 2 && $analog1!=0 && $analog2!=0)
     {
       $SQL = sprintf("UPDATE unit SET tempsen1=".$analog1.", tempsen2=".$analog2." where unitno='%s'",$punitno);
       $db->executeQuery($SQL);
     }else if($devicetype ==2 && $tempsen == 1 && $analog1 !=0){
         $SQL = sprintf("UPDATE unit SET tempsen1=".$analog1." where unitno='%s'",$punitno);
         $db->executeQuery($SQL);
    }

    // SET Unit Type Value
    $SQL = sprintf("UPDATE unit SET type_value='%s' WHERE unitno=%d", $type_value,$punitno);
    $db->executeQuery($SQL);
    // Panic
    if($devicetype == 2 && $panic == 1)
    {
        $SQL = sprintf("UPDATE unit SET is_panic=1 WHERE unitno='".$punitno."'");
        $db->executeQuery($SQL);
        $SQL = sprintf("UPDATE customer SET use_panic=1 WHERE customerno=1");
        $db->executeQuery($SQL);
    }

    // Buzzer
    if($devicetype == 2 && $buzzer == 1)
    {
        $SQL = sprintf("UPDATE unit SET is_buzzer=1 WHERE unitno= '".$punitno."'");
        $db->executeQuery($SQL);
        $SQL = sprintf("UPDATE customer SET use_buzzer=1 WHERE customerno=1");
        $db->executeQuery($SQL);
    }

    // Immobilizer
    if($devicetype == 2 && $immobilizer == 1)
    {
        $SQL = sprintf("UPDATE unit SET is_mobiliser=1 WHERE unitno= '".$punitno."'");
        $db->executeQuery($SQL);
        $SQL = sprintf("UPDATE customer SET use_immobiliser=1 WHERE customerno=1");
        $db->executeQuery($SQL);
    }
    // Two Way communication
    if($devicetype == 2 && $twowaycom == 1)
    {
        $SQL = sprintf("UPDATE unit SET is_twowaycom=1 WHERE unitno= '".$punitno."'");
        $db->executeQuery($SQL);

    }

    // Portable
    if($devicetype == 2 && $portable == 1)
    {
        $SQL = sprintf("UPDATE unit SET is_portable=1 WHERE unitno= '".$punitno."'");
        $db->executeQuery($SQL);
    }

    // Populate Devices
    $devicekey = mt_rand();

    $expiry = date('Y-m-d', strtotime('+1 year'));
    $SQL = sprintf( "INSERT INTO devices (
    `customerno` ,`devicekey`,`registeredon`,`uid`,`expirydate`)
    VALUES (
    1, '%s', '%s', '%d', '%s')",
    $devicekey, Sanitise::DateTime($today), $unitid, $expiry);
    $db->executeQuery($SQL);

    // Populate Vehicles
    $Query = "INSERT INTO vehicle (vehicleno,customerno, uid) VALUES ('%s',%d, %d)";
    //$SQL = sprintf($Query,'V'.$punitno,1, $unitid);
    $SQL = sprintf($Query,'Not Allocated',1, $punitno);
    $db->executeQuery($SQL);
    $vehicleid = $db->get_insertedId();
    // Update Unit
    $SQL = sprintf('UPDATE unit SET vehicleid=%d where unitno=%d',$vehicleid, $punitno);
    $db->executeQuery($SQL);

    // Populate Drivers
    $SQL = sprintf("INSERT INTO driver (drivername,driverlicno,customerno, vehicleid) VALUES ('%s','%s',%d,%d)",
    'Not Allocated',
    'LIC'.$punitno,
    1,
    $vehicleid);
    $db->executeQuery($SQL);
    $driverid = $db->get_insertedId();

    // Update Vehicle
    $SQL = sprintf('UPDATE vehicle SET driverid=%d where vehicleid=%d',$driverid, $vehicleid);
    $db->executeQuery($SQL);

    // Populate Event Alerts
    $SQL = sprintf("INSERT INTO eventalerts (vehicleid,overspeed, tamper, powercut, temp, ac, customerno) VALUES (%d,0,0,0,0,0,%d)",
            $vehicleid, 1);
    $db->executeQuery($SQL);

    // Populate Ignition Alert
    $SQL = sprintf("INSERT INTO ignitionalert (vehicleid,last_status, last_check, count, status, customerno) VALUES (%d,0,0,0,0,%d)",
            $vehicleid, 1);
    $db->executeQuery($SQL);

    // Populate AC Alert
    if($acesensor == 1 && $acdigitalopp !=0)
    {
        $SQL = sprintf("INSERT INTO acalerts (last_ignition, customerno, vehicleid, aci_status) VALUES (0,%d,%d,0)",
                1, $vehicleid);
        $db->executeQuery($SQL);
    }
    ///end

    // Populate genset Alert
//    if($genset == 1 && $gensetdigitalopp !=0)
//    {
//        $SQL = sprintf("INSERT INTO acalerts (last_ignition, customerno, vehicleid, aci_status) VALUES (0,%d,%d,0)",
//                1, $vehicleid);
//        $db->executeQuery($SQL);
//    }
    ///end

    // Populate Door Alert
//    if($doorsensor == 1 && $doordigitalopp !=0)
//    {
//        $SQL = sprintf("INSERT INTO acalerts (last_ignition, customerno, vehicleid, aci_status) VALUES (0,%d,%d,0)",
//                1, $vehicleid);
//        $db->executeQuery($SQL);
//    }
    ///end






    // Create unit directory
    $relativepath = "../..";
    if(!is_dir( $relativepath.'/customer/1/unitno/'.$punitno ))
    {
        // Directory doesn't exist.
        mkdir($relativepath.'/customer/1/unitno/'.$punitno,0777, true ) or die("Could not create directory");
    }

    if(!is_dir( $relativepath.'/customer/1/unitno/'.$punitno.'/sqlite' ))
    {
        // Directory doesn't exist.
        mkdir($relativepath.'/customer/1/unitno/'.$punitno.'/sqlite',0777, true ) or die("Could not create directory");
    }

    $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    -1, '%d', '%s', 0, '%s', 9, '%s', '%s', '%s', '%s', '%s')",
    $unitno, GetLoggedInUserId(), Sanitise::DateTime($today), "Replaced by: ".$punitno,"", "", "", $comments);
    $db->executeQuery($SQLunit);

    $SQL = sprintf('SELECT unitno FROM unit WHERE uid = '.$unitno);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $oldunitno = $row["unitno"];
        }
    }

    $SQLunit = sprintf( "INSERT INTO ".DB_PARENT.".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`, `comments`)
    VALUES (
    1, '%d', '%s', 0, '%s', 1, '%s', '%s', '%s', '%s', '%s')",
    $unitid, GetLoggedInUserId(), Sanitise::DateTime($today), "Replaced against: ".$oldunitno,"", "", "", $comments);
    $db->executeQuery($SQLunit);
}
}

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

$db = new DatabaseManager();
$SQL = sprintf("SELECT  unit.unitno, trans_status.status, unit.uid FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE trans_statusid IN (7) ORDER BY unit.unitno, unit.uid ASC");
$db->executeQuery($SQL);
$urunits = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $unit = new testing();
        $unit->unitno = $row["unitno"]."[ ".$row["status"]." ]";
        $unit->uid = $row["uid"];
        $urunits[] = $unit;
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

if(!IsData())
{
?>
<!-- Repair -->
    <div class="panel">
    <div class="paneltitle" align="center">
        Repair</div>
    <div class="panelcontents">
        <?php
        if(!empty($urepairmsg)){
            echo "<span style='color:red; font-size:12px;'>".$urepairmsg."</span>";
        }
        ?>
        <form method="post" name="myform" id="myform" action="repair.php" enctype="multipart/form-data">
    <table width="40%">
        <tr>
            <td colspan="2"><h3>Send to Repair</h3></td>
            </tr>
         <tr>
        <td>Unit No. </td>
        <td><select name="urepairunit" id="urepairunit" onChange="pullissues();">
                <option value="0">Select Unit</option>
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
            <td>Issue :</td><td><div id="issuedisp"></div> <input type="text" id="hiddenissue" name="hiddenissue"/></td>
        </tr>

    </table>

    <div><input type="submit" id="urepair" name="urepair" value="Send"/></div>
    </form>
        <hr/>
        <!-- team to repair start-->
         <form method="post" name="myformttr" id="myformttr" onsubmit="ValidateForm1(); return false;" action="repair.php" enctype="multipart/form-data">
         <h3>Team to repair</h3>
        <?php if(!empty($message)){echo "<span id='msg' style='color:red; font-size:12px;'>".$message."</span>";}?>
    <table width="50%">
         <tr>
        <td>From <span style='color:red;'>*</span></td>
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
        <td>Unit No.<span style='color:red;'>*</span> </td>
        <td id="uready_td"><select id="uallotted" name="uallotted"><option value="-1">No Units</option></select>
            ( Allotted Device List with selected Elixir)
        </td>
        </tr>
        <tr>
        <td>Issue<span style="color:red;">*</span></td><td><input name = "comments" id="comments" type="text"></td>
        </tr>

    </table>

    <div><input type="submit" id="teamrepair" name="teamrepair" value="Send"/></div>
    </form>

        <!--team to repair -end -->
        <hr/>
    <form method="post" name="myform" id="myform" action="repair.php" enctype="multipart/form-data">
    <table width="40%">
            <td colspan="2"><h3>Receive Repaired</h3></td>
         <tr>
        <td>Unit No. </td>
        <td><select name="urepairedunit">
                <?php
                foreach($urunits as $thisunit)
                {
                ?>
                <option value="<?php echo($thisunit->uid); ?>"><?php echo($thisunit->unitno); ?></option>
                <?php
                }
                ?>
            </select>
            ( Under Repair Device List)
        </td>
        </tr>

        <tr>
        <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
        </tr>

    </table>

    <div><input type="submit" id="urepaired" name="urepaired" value="Receive"/></div>
    </form>

<hr/>
    <form method="post" name="myformrbd" id="myformrbd" onsubmit="return ValidateForm();"  action="repair.php" enctype="multipart/form-data">
    <table width="40%">
            <td colspan="2"><h3>Replace Bad Devices</h3>
            <?php if(!empty($ureplacemsg)){ echo"<span style='color:red; font-size:12px;'>".$ureplacemsg."</span>";}?>
            </td>
         <tr>
        <td>Old Unit No. </td>
        <td><select name="ureplaceunit">
                <?php
                foreach($urunits as $thisunit)
                {
                ?>
                <option value="<?php echo($thisunit->uid); ?>"><?php echo($thisunit->unitno); ?></option>
                <?php
                }
                ?>
            </select>
            ( Under Repair Device List)
        </td>
        </tr>

        <tr>
        <td>New Unit No. </td>
        <td><input name = "newunitno" id="newunitno" type="text"></td>
        </tr>
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


                <input name="gensetsensor" id="gensetsensor" type="checkbox" value="2" onclick="gettransno()"  />  &nbsp;  Genset Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                <input name="tempsen" id="tempsen1" style="float:left;" type="radio" value="1"  onclick="temp_show();"/> <span id="tempsen4" style="width:105px; float:left;">Single Temperature </span> &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tempsen" id="tempsen2" style="float:left;" type="radio" value="2" onclick="temp_show();"/> <span id="tempsen3" style="width:110px; float:left;<?php echo $fuel_show;?>">Double Temperature </span>  &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>

         <tr id="advnone">
        <td id="adv1" style="display: none;">Analog Sensor 1 </td>
        <td><select name="panalog1" id="panalog1" style="display: none;">
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
            <td>Comments</td><td><input name = "comments" id="comments" type="text"></td>
        </tr>
    </table>
    <div><input type="submit" id="ureplace" name="ureplace" value="Replace"/></div>
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
    $( document ).ready(function() {
        $("#transno").hide();
    var device = $('input:radio[name=device]:checked').val();
    if(device == 1){
        $(".adv").hide();
        $("#ac_sensor").hide();
        $("#double_temp").hide();
    }else{
        $(".adv").show();
        if($("#sensor").val() != '0' && device == 2)
        {
            $("#ac_sensor").show();
        }
        if($('input:radio[name=tempsen]:checked').val() == 2 && device==2){
        $("#double_temp").show();
        }
    }

});

function fuelcheckbox(){

     if($('input:checkbox[name=fuelsensor]:checked').val() == 4){
        $("#fuelanalogtd").show();
     }else{
         $("#fuelanalogtd").hide();
     }
}


function device_type(){
    var device = $('input:radio[name=device]:checked').val();
    if( device == 1){
        $("#panalog1").hide();
        $("#adv1").hide();
        $("#canalog").hide();
        $("#fuelanalogtd").hide();
        $(".adv").hide();
         $("#ac_sensor").hide();
        $("#double_temp").hide();
    }else{
        $(".adv").show();
        if($("#sensor").val() != '0' && device==2)
        {
            $("#ac_sensor").show();
        }
        if($('input:radio[name=tempsen]:checked').val() == 2 && device==2){
        $("#double_temp").show();
        }
    }
}
function sensor_show()
{
       if($("#sensor").val() == '1' || $("#sensor").val() == '2' || $("#sensor").val() == '3')
       {
           $("#ac_sensor").show();
       }else{
           $("#ac_sensor").hide();
       }
}

function temp_show(){
    if($('input:radio[name=tempsen]:checked').val() == 0){
        $("#double_temp").hide();
        $("#panalog1").hide();
        $("#advnone").hide();
        $("#adv1").hide();

    }else if($('input:radio[name=tempsen]:checked').val() == 1){
         $("#adv1").show();
        $("#advnone").show();
        $("#panalog1").show();
        $("#double_temp").hide();
    }else if($('input:radio[name=tempsen]:checked').val() == 2){
            $("#adv1").show();
            $("#advnone").show();
            $("#panalog1").show();
            $("#double_temp").show();
    }else{
        $("#double_temp").hide();
        $("#panalog1").hide();
            $("#adv1").hide();
    }
}


function doubleTemp(){
    var temp1 = $("#panalog1").val();
    var temp2 = $("#panalog2").val();

    if(temp1 == temp2){
        alert("Please Select Different Analog Output For Double Temperature");
        $('#panalog2').val(0);
    }
}


function ValidateForm(){
    var punitno = $("#newunitno").val();
   var devicetype = $('input:radio[name=device]:checked').val();
   var tempsen = $('input:radio[name=tempsen]:checked').val();
   var analog1 = $("#panalog1").val();
   var analog2 = $("#panalog2").val();
   var fuelanalog = $("#fuelanalog").val();
   var acsensor = $('input:checkbox[name=acsensor]:checked').val()?1:0;
   var gensetsensor = $('input:checkbox[name=gensetsensor]:checked').val()?1:0;
   var doorsensor = $('input:checkbox[name=doorsensor]:checked').val()?1:0;
   var fuelsensor = $('input:checkbox[name=fuelsensor]:checked').val()?1:0;
   var acdigitalopp = $('input:checkbox[name=acdigitalopp]:checked').val()?1:0;
   var gensetdigitalopp = $('input:checkbox[name=gensetdigitalopp]:checked').val()?1:0;
   var doordigitalopp = $('input:checkbox[name=doordigitalopp]:checked').val()?1:0;
   var panic = $('input:checkbox[name=panic]:checked').val()?1:0;
   var buzzer =$('input:checkbox[name=buzzer]:checked').val()?1:0;
   var immobilizer = $('input:checkbox[name=immobilizer]:checked').val()?1:0;
   var twowaycom = $('input:checkbox[name=twowaycom]:checked').val()?1:0;
   var portable = $('input:checkbox[name=portable]:checked').val()?1:0;
   if(acsensor==0 && gensetsensor==0 && doorsensor==0 && fuelsensor==0){
      var sensor=0;
   }else{
       var sensor=1;
   }

    if(panic=='0' && buzzer=='0' && immobilizer=='0' && twowaycom=='0' && portable=='0'){
       var types=0;
   }else{
       var types=1;
   }

   if(punitno==""){
       alert("Please Enter New Unit No.");
       return false;
   }else if(devicetype==2 && (sensor==0 && types == 0)){
     alert("Please Select advanced features");
     return false;
    }else if(acdigitalopp=='1' && acsensor=='0'){
       alert("Please Select From AC Sensor Or Is AC Opposite");
       return false;
   }else if(gensetdigitalopp=='1' && gensetsensor=='0'){
       alert("Please Select From Genset Sensor Or Is Genset Opposite");
       return false;
   }else if(doordigitalopp=='1' && doorsensor=='0'){
       alert("Please Select From  Door Sensor Or Is  Door Opposite");
       return false;
   }else if(fuelsensor=='1' && fuelanalog=='0'){
       alert("Please Select fuelanalog");
       return false;
   }else if(devicetype=='2' && tempsen=='1' && analog1=='0'){
       alert("Please Select Analog Sensor 1");
       return false;
   }else if(devicetype=='2' && tempsen=='2' && analog1=='0' && analog2=='0'){
       alert("Please Select Analog Sensor 1 & 2");
       return false;
   } else if(devicetype == '2' && tempsen == '2' && ((analog1!= 0 && analog2 == 0) || (analog1==0 && analog2!=0))){
       alert("Please Select Both Analog Sensor For Double Temperature.");
       return false;
   }else if(devicetype=='2' && fuelsensor=='1'&& (tempsen =='1' || tempsen=='2')&& (fuelanalog==analog1 || fuelanalog==analog2)){
       alert("Please Select another Analog Sensor For fuelsenor.");
       return false;
    }else{
         $("#myformrbd").submit();
    }
}
function pullunit()
{
    jQuery("#msg").hide();
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

function pullissues()
{
    jQuery("#msg").hide();
    var urepairunitid = jQuery('#urepairunit').val();
    if(urepairunitid=='0'){
        alert("Please select unit");
    }else{
            jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {urepairuid_issues : urepairunitid},
            dataType: 'html',
            success: function(html){
                jQuery("#issuedisp").html('');
                jQuery("#hiddenissue").val('');
                jQuery("#issuedisp").append(html);
                jQuery("#hiddenissue").val(html);
            }
        });
        return false;
    }
}


function gettransno(){
    $("#gensetsensor").attr("checked") ? $("#transno").show() : $("#transno").hide();
}

</script>
