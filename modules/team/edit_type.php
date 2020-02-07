<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
$_scripts[] = "../../scripts/jquery.min.js";

$db = new DatabaseManager();
$deviceid = $_GET["id"];
$msg ="";

$result = $_GET["res"];    
$st =  $_GET["stock"];    
$tid = $_GET["uteamid"];

 if($st==1){
    $string = "&show=1&res=".$result."&stock=".$st."&uteamid=".$tid;
    }else if($st=='all'){
       $string = "&show=1&res=".$result."&stock=".$st;  
    }
    else{
        $string = "&show=1&res=".$result."&stock=".$st;
    }



if(isset($_POST["updateunit"]))
{
    // Populate Devices
    $type_value = 0;
    $customerno = GetSafeValueString($_POST["customerno"], "string");                                             
    $deviceid = GetSafeValueString($_POST["unitid"], "string");                            
    $devicetype = GetSafeValueString($_POST['device'], "string");
    //////////sensors////////////////////////////////////////////
    $acesensor = GetSafeValueString($_POST['acsensor'], "string");
    $acdigitalopp = GetSafeValueString($_POST['acdigitalopp'], "string");
    $gensetsensor = GetSafeValueString($_POST['gensetsensor'], "string");
    $gensetdigitalopp = GetSafeValueString($_POST['gensetdigitalopp'], "string");
    $doorsensor = GetSafeValueString($_POST['doorsensor'], "string");
    $doordigitalopp = GetSafeValueString($_POST['doordigitalopp'], "string");
    $fuelsensor = GetSafeValueString($_POST['fuelsensor'], "string");
    $fuelanalog =  GetSafeValueString($_POST['fuelanalog'],"string");
    ///////////sensorsend//////////////////////////////////////
    $tempsen = GetSafeValueString($_POST['tempsen'], "string");
    $analog1 = GetSafeValueString($_POST["canalog1"], "long");
    $analog2 = GetSafeValueString($_POST["canalog2"], "long");
    $analog3 = GetSafeValueString($_POST["canalog3"], "long");
    $analog4 = GetSafeValueString($_POST["canalog4"], "long");
    $panic = GetSafeValueString($_POST['panic'], "string");
    $buzzer = GetSafeValueString($_POST['buzzer'], "string");
    $immobilizer = GetSafeValueString($_POST['immobilizer'], "string");    
    $twowaycom = GetSafeValueString($_POST['twowaycom'], "string");
    $portable = GetSafeValueString($_POST['portable'], "string");
    $comments = GetSafeValueString($_POST["comments"], "string");
    
    $transmitno1 = GetSafeValueString($_POST['transmitno'],"string");
    $cunitprice = GetSafeValueString($_POST["unitprice"], "string");   
    
    if($acesensor=='1'){
        $acs ='1';
    }else{
        $acs ='0';
    }
    
    if($fuelsensor=='1' && $fuelanalog != 0){
        $fs = $fuelanalog;
    }else{
        $fs ='0';
    }
    
    if($acdigitalopp=='1' && $acesensor=='1'){
        $acopp =1;
    }else{
        $acopp =0;
    }  

    if($gensetsensor=='1'){
        $gss =1;
    }else{
        $gss =0;
    }
    
    $transmitno="";
    
    if($gensetsensor=='1'){
        $transmitno = $transmitno1;
    }
    
    if($gensetdigitalopp=='1' && $gensetsensor=='1'){
        $gssopp =1;
    }else{
        $gssopp =0;
    } 
    
    if($doorsensor=='1'){
        $dos =1;
    }else{
        $dos =0;
    }
    
    if($doordigitalopp=='1' && $doorsensor=='1'){
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
   if($devicetype == 2 && $gensetsensor==1){
       $type_value = $type_value + 2;
   }
   if($devicetype == 2 && $doorsensor==1){
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
   if($devicetype == 2 && $fuelsensor == 1 && $fuelanalog != 0){
       $type_value = $type_value + 1024;
   }
   if($devicetype == 2 && $tempsen == 3 && $analog1 !=0 && $analog2 != 0 && $analog3 != 0){
       $type_value = $type_value + 2048;
   }
   if($devicetype == 2 && $tempsen == 4 && $analog1 !=0 && $analog2 != 0 && $analog3 !=0 && $analog4 != 0){
       $type_value = $type_value + 4096;
   }
   
    // Temperature Sensor 1   
    $oldanalog1 = GetSafeValueString($_POST["oldanalog1"], "long");         
    $oldanalog2 = GetSafeValueString($_POST["oldanalog2"], "long"); 
    $oldanalog3 = GetSafeValueString($_POST["oldanalog3"], "long"); 
    $oldanalog4 = GetSafeValueString($_POST["oldanalog4"], "long"); 
    
    if($oldanalog1 != 0)
    {
        $SQL = sprintf("UPDATE unit SET tempsen1=0 where uid='%s'",$deviceid);
        $db->executeQuery($SQL);        
    }
    if($oldanalog2 != 0)
    {
       $SQL = sprintf("UPDATE unit SET tempsen2=0 where uid='%s'",$deviceid);
       $db->executeQuery($SQL);        
    } 
    if($oldanalog3 != 0)
    {
       $SQL = sprintf("UPDATE unit SET tempsen3=0 where uid='%s'",$deviceid);
       $db->executeQuery($SQL);        
    }
    if($oldanalog4 != 0)
    {
       $SQL = sprintf("UPDATE unit SET tempsen4=0 where uid='%s'",$deviceid);
       $db->executeQuery($SQL);        
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

    if($gensetsensor=='1'){
        $gss =1;
    }else{
        $gss =0;
    }
    
    if($gensetdigitalopp=='1' && $gensetsensor=='1'){
        $gssopp =1;
    }else{
        $gssopp =0;
    } 
    
    if($doorsensor=='1'){
        $dos =1;
    }else{
        $dos =0;
    }
    
    if($doordigitalopp=='1' && $doorsensor=='1'){
        $dooropp =1;
    }else{
        $dooropp =0;
    } 
    
    $SQL = sprintf("UPDATE unit SET  acsensor=%d, is_ac_opp=%d, gensetsensor=%d, is_genset_opp=%d, doorsensor=%d, is_door_opp=%d, fuelsensor=%d, transmitterno='".$transmitno."', comments = '".$comments."' where uid='%s'",$acs,$acopp,$gss,$gssopp,$dos,$dooropp,$fs,$deviceid);        
    $db->executeQuery($SQL);
     
    ///update unit price or monthly subscription price
    
    $SQL = sprintf("UPDATE unit SET unitprice='".$cunitprice."' where uid=".$deviceid);
        $db->executeQuery($SQL);    
    
    // Temperature Sensor 1
    if($devicetype ==2 && $tempsen == 4 && $analog1!=0 && $analog2!=0 && $analog3!=0 && $analog4!=0)
    {
      $SQL = sprintf("UPDATE unit SET tempsen1=".$analog1.", tempsen2=".$analog2.", tempsen3=".$analog3.", tempsen4=".$analog4." where uid='%s'",$deviceid);
      $db->executeQuery($SQL);
    }else if($devicetype ==2 && $tempsen == 3 && $analog1!=0 && $analog2!=0 && $analog3!=0)
    {
      $SQL = sprintf("UPDATE unit SET tempsen1=".$analog1.", tempsen2=".$analog2.", tempsen3=".$analog3." where uid='%s'",$deviceid);
      $db->executeQuery($SQL);
    }else if($devicetype ==2 && $tempsen == 2 && $analog1!=0 && $analog2!=0)
    {
      $SQL = sprintf("UPDATE unit SET tempsen1=".$analog1.", tempsen2=".$analog2." where uid='%s'",$deviceid);
      $db->executeQuery($SQL);
    }else if($devicetype ==2 && $tempsen == 1 && $analog1 !=0){
       $SQL = sprintf("UPDATE unit SET tempsen1=".$analog1." where uid='%s'",$deviceid);
        $db->executeQuery($SQL);
    }else{
        $SQL = sprintf("UPDATE unit SET tempsen1=0,tempsen2=0 where uid='%s'",$deviceid);
        $db->executeQuery($SQL);
    }
    
    // SET Unit Type Value 
    $SQL = sprintf("UPDATE unit SET type_value='%s' WHERE uid=%d", $type_value,$deviceid);
    $db->executeQuery($SQL);
    // Panic
    if($devicetype == 2 && $panic == 1)
    {
        $SQL = sprintf("UPDATE unit SET is_panic=1 WHERE uid= $deviceid");
        $db->executeQuery($SQL);
        $SQL = sprintf("UPDATE ".DB_PARENT.".customer SET use_panic=1 WHERE customerno=$customerno");
        $db->executeQuery($SQL);
    }else{
        $SQL = sprintf("UPDATE unit SET is_panic=0 WHERE uid= $deviceid");
        $db->executeQuery($SQL);
    }
    
    // Buzzer
    if($devicetype == 2 && $buzzer == 1)
    {
        $SQL = sprintf("UPDATE unit SET is_buzzer=1 WHERE uid= $deviceid");
        $db->executeQuery($SQL);
        $SQL = sprintf("UPDATE ".DB_PARENT.".customer SET use_buzzer=1 WHERE customerno=$customerno");
        $db->executeQuery($SQL);
    }else{
        $SQL = sprintf("UPDATE unit SET is_buzzer=0 WHERE uid= $deviceid");
        $db->executeQuery($SQL);
    }
    
    // Immobilizer
    if($devicetype == 2 && $immobilizer == 1)
    {
        $SQL = sprintf("UPDATE unit SET is_mobiliser=1 WHERE uid= $deviceid");
        $db->executeQuery($SQL);
        $SQL = sprintf("UPDATE ".DB_PARENT.".customer SET use_immobiliser=1 WHERE customerno=$customerno");
        $db->executeQuery($SQL);
    }else{
        $SQL = sprintf("UPDATE unit SET is_mobiliser=0 WHERE uid= $deviceid");
        $db->executeQuery($SQL); 
    }
    
     // Portable
    if($devicetype == 2 && $portable == 1)
    {
        $SQL = sprintf("UPDATE unit SET is_portable=1 WHERE uid= $deviceid");
        $db->executeQuery($SQL);
    }else{
        $SQL = sprintf("UPDATE unit SET is_portable=0 WHERE uid= $deviceid");
        $db->executeQuery($SQL); 
    }
    
      // Is two way communication
    if($devicetype == 2 && $twowaycom == 1)
    {
        $SQL = sprintf("UPDATE unit SET is_twowaycom=1 WHERE uid= $deviceid");
        $db->executeQuery($SQL);
    }else{
        $SQL = sprintf("UPDATE unit SET is_twowaycom=0 WHERE uid= $deviceid");
        $db->executeQuery($SQL); 
    }
    
    
    
    if($devicetype == 1)
    {
        $SQL = sprintf("UPDATE unit SET acsensor=0, is_ac_opp =0, doorsensor =0, gensetsensor=0, is_genset_opp =0, fuelsensor=0, is_door_opp =0, is_buzzer=0,is_panic=0,is_mobiliser=0 WHERE uid= $deviceid");
        $db->executeQuery($SQL);
    }
    
    $msg = "Updated Sucessfully";
    header("Location: inventory.php?$string");    
    
}

$SQL = sprintf("SELECT  unit.customerno,unit.is_ac_opp,unit.unitprice, unit.acsensor,unit.gensetsensor,unit.transmitterno
                        , unit.is_genset_opp, unit.doorsensor,unit.fuelsensor, unit.is_door_opp, unit.fuelsensor,unit.tempsen1
                        , unit.tempsen2,unit.tempsen3,unit.tempsen4, unit.vehicleid,unit.uid, unit.unitno, unit.acsensor
                        , unit.is_ac_opp, unit.doorsensor, unit.is_door_opp, unit.is_panic, unit.is_buzzer, unit.is_mobiliser
                        , unit.is_twowaycom, unit.is_portable, unit.type_value 
                FROM    unit 
                WHERE   unit.uid = '%d' LIMIT 1" ,$deviceid);
    $db->executeQuery($SQL);
    while($row = $db->get_nextRow())
{
        
    $uid = $row["uid"];
    $customerno = $row["customerno"];
    $unitno = $row["unitno"];
    $panic = $row['is_panic'];
    $buzzer = $row['is_buzzer'];
    $immobilizer = $row['is_mobiliser'];
    $twowaycom = $row['is_twowaycom'];
    $portable = $row['is_portable'];
    $type_value = $row['type_value'];
    $transmitterno = $row['transmitterno'];
    $unitprice = $row["unitprice"];
    
    $category_array= Array();

    $category = (int) $type_value;
    $binarycategory = sprintf("%08s",DecBin($category));
    for($shifter=1;$shifter<=8103;$shifter=$shifter<<1)
    {
        $binaryshifter = sprintf("%08s",DecBin($shifter));
        if($category & $shifter)
        {
             $category_array[]= $shifter;
        }
    }
    //print_r($category_array);
    $analog1 = $row["tempsen1"];
    $analog2 = $row["tempsen2"]; 
    $analog3 = $row["tempsen3"];
    $analog4 = $row["tempsen4"]; 
    $fuelsensor = $row["fuelsensor"];

   if(($row['is_ac_opp']=='1') && ($row['acsensor']=='1')){
        $dgacopp = 1;
    }else{
        $dgacopp = 0;
    }
    
    if(($row['is_genset_opp']=='1') && ($row['gensetsensor']=='1')){
        $dggensetopp = 1;
    }else{
        $dggensetopp = 0;
    }
    
    if(($row['is_door_opp']=='1') && ($row['doorsensor']=='1')){
        $dgdooropp = 1;
    }else{
        $dgdooropp = 0;
    }
    $did = $row["deviceid"];    
    
}

include("header.php");
?>

<!--------------------------------------update unit type form-------------------------------------->
<div class="panel">
<div class="paneltitle" align="center">Update Basic Information for <?php echo ($unitno); ?></div>
<?php
echo $msg;
?>
<div class="panelcontents">  
    
    <form method="post" name="myform" id="myform" onsubmit="return ValidateForm();">
    <input type ="hidden" name ="customerno" value ="<?php echo ($customerno); ?>" />
    <input type ="hidden" name ="unitid" value ="<?php echo ($uid); ?>"/>
    <table width="70%">
           <tr>
             <td>Type</td>
            <td> <input name="device" id="device" type="radio" value="1" <?php if(in_array(0, $category_array) || empty($category_array)){ echo "checked=''";} ?> onclick="device_type();"/> &nbsp;Basic &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="device" id="device" type="radio" value="2" <?php if(!in_array(0, $category_array) && !empty($category_array)){ echo "checked=''";} ?> onclick="device_type();" /> &nbsp; Advanced &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        
                     
        <tr class="adv">
            <td>Sensor</td>
            <td>
                <input name="acsensor" id="acesensor" type="checkbox" value="1" <?php if(in_array(1, $category_array)){ echo "checked";} ?> />  &nbsp;  Ac Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                <input name="acdigitalopp" id="acdigitalopp" type="checkbox" value='1' <?php if($dgacopp=='1'){ echo "checked";} ?> />  &nbsp;  Is Opposite? <br/>
            
            
                <input name="gensetsensor" id="gensetsensor" type="checkbox" onclick="gettransno()"  value="1" <?php if(in_array(2, $category_array)){ echo "checked";} ?> />  &nbsp;  Genset Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                <input name="gensetdigitalopp" id="gensetdigitalopp" type="checkbox" value='1' <?php if($dggensetopp=='1'){ echo "checked";} ?>/>  &nbsp;  Is Genset Opposite?<br/>
            
                <div id="transno" style="<?php if(in_array(2, $category_array)){echo "display:block;";}else{echo"display:none;";}?>">
                    <label>Transmitter No.</label>
                    <input type="text" id="transmitno" name="transmitno" value="<?php echo $transmitterno;?>"/>
                </div>
            
                <input name="doorsensor" id="doorsensor" type="checkbox" value="1" <?php if(in_array(4, $category_array)){ echo "checked";} ?>/>  &nbsp;  Door Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                <input name="doordigitalopp" id="doordigitalopp" type="checkbox" value='1'<?php if($dgdooropp=='1'){ echo "checked";} ?>/>  &nbsp;  Is Door Opposite?<br>
                <input type="checkbox" name="fuelsensor" id="fuelsensor" value="1" <?php if(in_array(1024, $category_array)){ echo "checked";} ?> onclick="fuelcheckbox()">&nbsp; Fuel Sensor
            </td>
        </tr>     
        <tr id= "fuelanalogtd"  <?php if(in_array(1024, $category_array)){ echo "style='display:block; width:55px;'";}else{echo "style='display:none;'";}  ?>>
            
            <td style="width:50px;"><label>Fuel Analog</label></td>
            <td style="width:50px;">
                <select name="fuelanalog" id="fuelanalog">
                <option value="0"<?php if($fuelsensor == 0){ echo "selected=''"; } ?>>Select Output</option>
                <option value="1" <?php if($fuelsensor == 1){ echo "selected=''"; } ?> >Analog 1</option>
                <option value="2" <?php if($fuelsensor == 2){ echo "selected=''"; } ?>>Analog 2</option>
                <option value="3"<?php if($fuelsensor == 3){ echo "selected=''"; } ?>>Analog 3</option>
                <option value="4"<?php if($fuelsensor == 4){ echo "selected=''"; } ?>>Analog 4</option>
            </select>
            </td>
        </tr>
        <tr class="adv">
            <td>Temperature </td>
           
            <td> 
                <input name="tempsen" id="tempsen" style="float:left;" type="radio" value="0" <?php if( empty($category_array)){ echo "checked";} ?> onclick="temp_show();"/> <span  style="width:32px; float:left;">None</span>  
                <input name="tempsen" id="tempsen1" style="float:left;" type="radio" value="1" <?php if(in_array(8, $category_array)){ echo "checked";} ?> onclick="temp_show();"/> <span  style="width:20px; float:left;"> 1 </span>  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tempsen" style="float:left;" id="tempsen2" type="radio" value="2" <?php if(in_array(16, $category_array)){ echo "checked";} ?> onclick="temp_show();"/> <span  style="width:20px; float:left;"> 2 </span> &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tempsen" style="float:left;" id="tempsen3" type="radio" value="3" <?php if(in_array(2048, $category_array)){ echo "checked";} ?> onclick="temp_show();"/> <span  style="width:20px; float:left;"> 3 </span> &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tempsen" style="float:left;" id="tempsen4" type="radio" value="4" <?php if(in_array(4096, $category_array)){ echo "checked";} ?> onclick="temp_show();"/> <span  style="width:20px; float:left;"> 4 </span> &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        
        
        <tr id="1_temp" style="<?php $type_array=array(8,16,2048,4096); if(count(array_intersect($type_array, $category_array)) == count($category_array)){ echo 'style=display:block;';}else{ echo 'style=display:none;';} ?>">
            <td>Analog Sensor 1 </td>
            <input type="hidden" name="oldanalog1" value="<?php echo($analog1); ?>">
            <td>
                <select name="canalog1" id="canalog1" <?php if($analog1 != 0){ echo 'style=display:block;';}else{ echo 'style=display:none;';}?>>
                <option value="0" <?php if($analog1 == 0){ echo "selected=''"; } ?>>Select Analog</option>                
                <option value="1" <?php if($analog1 == 1){ echo "selected=''"; } ?>>Analog 1</option>
                <option value="2" <?php if($analog1 == 2){ echo "selected=''"; } ?>>Analog 2</option>
                <option value="3" <?php if($analog1 == 3){ echo "selected=''"; } ?>>Analog 3</option>
                <option value="4" <?php if($analog1 == 4){ echo "selected=''"; } ?>>Analog 4</option>
                </select>
        </td>
        </tr>

        <tr id="2_temp" style="<?php $type_array=array(16,2048,4096); if(count(array_intersect($type_array, $category_array)) == count($category_array)){ echo 'style=display:block;';}else{ echo 'style=display:none;';} ?>">
            <td>Analog Sensor 2</td>
            <input type="hidden" name="oldanalog2" value="<?php echo($analog2); ?>">        
            <td>
                <select name="canalog2" id="canalog2" onchange="doubleTemp()">
                <option value="0" <?php if($analog2 == 0){ echo "selected=''"; } ?>>Select Analog</option>                
                <option value="1" <?php if($analog2 == 1){ echo "selected=''"; } ?>>Analog 1</option>
                <option value="2" <?php if($analog2 == 2){ echo "selected=''"; } ?>>Analog 2</option>
                <option value="3" <?php if($analog2 == 3){ echo "selected=''"; } ?>>Analog 3</option>
                <option value="4" <?php if($analog2 == 4){ echo "selected=''"; } ?>>Analog 4</option>
                </select>
            </td>
        </tr>
        
         <tr id="3_temp" style="<?php $type_array=array(2048,4096); if(count(array_intersect($type_array, $category_array)) == count($category_array)){ echo 'style=display:block;';}else{ echo 'style=display:none;';} ?>">
        <td>Analog Sensor 3 </td>
        <input type="hidden" name="oldanalog3" value="<?php echo($analog3); ?>"> 
        <td><select name="canalog3" id="canalog3" onchange="tripleTemp();">
                <option value="0" <?php if($analog3 == 0){ echo "selected=''"; } ?>>Select Analog</option>
                <option value="1" <?php if($analog3 == 1){ echo "selected=''"; } ?>>Analog 1</option>
                <option value="2" <?php if($analog3 == 2){ echo "selected=''"; } ?>>Analog 2</option>
                <option value="3" <?php if($analog3 == 3){ echo "selected=''"; } ?>>Analog 3</option>
                <option value="4" <?php if($analog3 == 4){ echo "selected=''"; } ?>>Analog 4</option>
            </select>
        </td>
        </tr>
        
        <tr id="4_temp" style="<?php $type_array=array(4096); if(count(array_intersect($type_array, $category_array)) == count($category_array)){ echo 'style=display:block;';}else{ echo 'style=display:none;';} ?>">
        <td>Analog Sensor 4 </td>
        <input type="hidden" name="oldanalog4" value="<?php echo($analog4); ?>"> 
        <td><select name="canalog4" id="canalog4" onchange="quadTemp();">
                <option value="0" <?php if($analog4 == 0){ echo "selected=''"; } ?>>Select Analog</option>
                <option value="1" <?php if($analog4 == 1){ echo "selected=''"; } ?>>Analog 1</option>
                <option value="2" <?php if($analog4 == 2){ echo "selected=''"; } ?>>Analog 2</option>
                <option value="3" <?php if($analog4 == 3){ echo "selected=''"; } ?>>Analog 3</option>
                <option value="4" <?php if($analog4 == 4){ echo "selected=''"; } ?>>Analog 4</option>
            </select>
        </td>
        </tr>
        
        <tr class="adv">
            <td></td>
            <td>
                <?php if(in_array(32, $category_array)){ ?>
                <input name="panic" id="panic" type="checkbox" value="1" <?php if($panic==1){ echo "checked=''"; }?>/> Panic  &nbsp;&nbsp;&nbsp;&nbsp;
              <?php } else{
                  ?>
                <input name="panic" id="panic" type="checkbox" value="1" /> Panic  &nbsp;&nbsp;&nbsp;&nbsp;
                <?php
              } ?>
                <?php if(in_array(64, $category_array)){ ?>
                <input name="buzzer" id="buzzer" type="checkbox" value="1" <?php if($buzzer==1){ echo "checked=''"; }?>  /> Buzzer  &nbsp;&nbsp;&nbsp;&nbsp;
                <?php } else{
                   ?>
               <input name="buzzer" id="buzzer" type="checkbox" value="1"  /> Buzzer  &nbsp;&nbsp;&nbsp;&nbsp
                <?php
                } ?>
                <?php if(in_array(128, $category_array)){ ?>
                <input name="immobilizer" id="immobilizer" type="checkbox" value="1" <?php if($immobilizer==1){ echo "checked=''"; }?> /> Immobilizer  &nbsp;&nbsp;&nbsp;&nbsp;
                <?php } else{
                    ?>
                <input name="immobilizer" id="immobilizer" type="checkbox" value="1" /> Immobilizer  &nbsp;&nbsp;&nbsp;&nbsp;
                <?php
                }
                ?>
                 <?php if(in_array(256,$category_array)){ ?>
                <input name="twowaycom" id="twowaycom" type="checkbox" value="1" <?php if($twowaycom=='1'){ echo "checked=''"; }?> /> Two way Communication  &nbsp;&nbsp;&nbsp;&nbsp;
                <?php }else{
                    ?>
                <input name="twowaycom" id="twowaycom" type="checkbox" value="1" /> Two way Communication  &nbsp;&nbsp;&nbsp;&nbsp;
                <?php
                }
                ?>
                 <?php if(in_array(512,$category_array)){ ?>
                <input name="portable" id="portable" type="checkbox" value="1" <?php if($portable=='1'){ echo "checked=''"; }?> /> Portable  &nbsp;&nbsp;&nbsp;&nbsp;
                <?php }else{
                    ?>
                <input name="portable" id="portable" type="checkbox" value="1" /> Portable  &nbsp;&nbsp;&nbsp;&nbsp;
                <?php
                }
?>
                
            </td>
        </tr>
         <tr>
            <td>Unit Price</td>
            <td>
                <input type="text" name="unitprice" id="unitprice" value="<?php echo $unitprice;?>">
            </td>
        </tr>
        <tr>
        <td>Comments</td><td><input name="comments" id="comments" type="text"></td>            
        </tr>        
        
    </table>
<input type="submit" name="updateunit" value="Update" />
</form>
</div>
</div>   



<script type ="text/javascript">
// ---------------------  Update Unit 


$( document ).ready(function() {
    
    var device = $('input:radio[name=device]:checked').val();
    if(device == 1){
        $(".adv").hide();
        $("#ac_sensor").hide();
        $("#1_temp").hide();
        $("#2_temp").hide();
        $("#3_temp").hide();
        $("#4_temp").hide();
    }else{
        $(".adv").show();
        if($("#sensor").val() != '0' && device == 2)
        {
           $("#ac_sensor").show();    
        }
        if(device==2){
            if($('input:radio[name=tempsen]:checked').val() == 1){
                $("#1_temp").show();
            }else if($('input:radio[name=tempsen]:checked').val() == 2){
                $("#2_temp").show();
            }else if($('input:radio[name=tempsen]:checked').val() == 3){
                $("#3_temp").show();
            }else if($('input:radio[name=tempsen]:checked').val() == 4){
                $("#4_temp").show();
            }
        }  
    }      
});

function fuelcheckbox(){
     if($('#fuelsensor').attr('checked'))
        {
            $("#fuelanalogtd").show();
     }
     else
     {
            $("#fuelanalogtd").hide();
     }
}


function device_type(){
    var device = $('input:radio[name=device]:checked').val();
    if( device == 1){
        $("#fuelanalogtd").hide();
        $(".adv").hide();
        $("#ac_sensor").hide();
        $("#1_temp").hide();
        $("#2_temp").hide();
        $("#3_temp").hide();
        $("#4_temp").hide();
    }else{
        $(".adv").show();
        if($("#sensor").val() != '0' && device==2)
        {
            $("#ac_sensor").show();    
        }
        if(device==2){
            if($('input:radio[name=tempsen]:checked').val() == 1){
                $("#1_temp").show();
                $("#2_temp").hide();
                $("#3_temp").hide();
                $("#4_temp").hide();
            }else if($('input:radio[name=tempsen]:checked').val() == 2){
                $("#1_temp").show();
                $("#2_temp").show();
                $("#3_temp").hide();
                $("#4_temp").hide();
            }else if($('input:radio[name=tempsen]:checked').val() == 3){
                $("#1_temp").show();
                $("#2_temp").show();
                $("#3_temp").show();
                $("#4_temp").hide();
            }else if($('input:radio[name=tempsen]:checked').val() == 4){
                $("#1_temp").show();
                $("#2_temp").show();
                $("#3_temp").show();
                $("#4_temp").show();
            }
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
        $("#2_temp").hide();
        $("#canalog1").hide();
        $("#1_temp").hide();
        $("#3_temp").hide();
        $("#4_temp").hide();
        $("#adv1").hide();
    }else if($('input:radio[name=tempsen]:checked').val() == 1){
        $("#adv1").show();
        $("#1_temp").show();
        $("#canalog1").show();
        $("#2_temp").hide();
        $("#3_temp").hide();
        $("#4_temp").hide();
    }else if($('input:radio[name=tempsen]:checked').val() == 2){
        $("#adv1").show();
        $("#1_temp").show();
        $("#canalog1").show();
        $("#2_temp").show();
        $("#3_temp").hide();
        $("#4_temp").hide();
    }else if($('input:radio[name=tempsen]:checked').val() == 3){
        $("#adv1").show();
        $("#advnone").show();
        $("#canalog1").show();
        $("#2_temp").show();
        $("#3_temp").show();
        $("#4_temp").hide();
    }else if($('input:radio[name=tempsen]:checked').val() == 4){
        $("#adv1").show();
        $("#advnone").show();
        $("#canalog1").show();
        $("#2_temp").show();
        $("#3_temp").show();
        $("#4_temp").show();
    }else{
        $("#2_temp").hide();
        $("#canalog1").hide();
        $("#adv1").hide();
    }
}

function doubleTemp(){
    
    var temp1 = $("#canalog1").val();
    var temp2 = $("#canalog2").val();
    
    if(temp1 == temp2){
        alert("Please Select Different Analog Output For Double Temperature");
        $('#canalog2').val(0);
    }
}
function tripleTemp(){
    var temp1 = $("#canalog1").val();
    var temp2 = $("#canalog2").val();
    var temp3 = $("#canalog3").val();
    
    if(temp1 == temp2){
        alert("Please Select Different Analog Output");
        //alert("test");
        $('#canalog2').val(0);
    }else if(temp1 == temp3){
        alert("Please Select Different Analog Output");
        $('#canalog3').val(0);
    }else if(temp2 == temp3){
        alert("Please Select Different Analog Output");
        $('#canalog3').val(0);
    }
}
function quadTemp(){
    var temp1 = $("#canalog1").val();
    var temp2 = $("#canalog2").val();
    var temp3 = $("#canalog3").val();
    var temp4 = $("#canalog4").val();
    
    if(temp1 == temp2){
        alert("Please Select Different Analog Output For Double Temperature");
        $('#canalog2').val(0);
    }else if(temp1 == temp3){
        alert("Please Select Different Analog Output");
        $('#canalog3').val(0);
    }else if(temp1 == temp4){
        alert("Please Select Different Analog Output");
        $('#canalog4').val(0);
    }else if(temp2 == temp3){
        alert("Please Select Different Analog Output");
        $('#canalog3').val(0);
    }else if(temp2 == temp4){
        alert("Please Select Different Analog Output");
        $('#canalog4').val(0);
    }else if(temp3 == temp4){
        alert("Please Select Different Analog Output");
        $('#canalog4').val(0);
    }
}
function gettransno(){
    $("#gensetsensor").attr("checked") ? $("#transno").show() : $("#transno").hide();
}

function ValidateForm(){
   var devicetype = $('input:radio[name=device]:checked').val();
   var tempsen = $('input:radio[name=tempsen]:checked').val();
   var analog1 = $("#canalog1").val();
   var analog2 = $("#canalog2").val();
   var analog3 = $("#canalog3").val();
   var analog4 = $("#canalog4").val();
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
   
   if(panic=='0' && buzzer=='0' && immobilizer=='0' && twowaycom=='0' && portable=='0' && acsensor==0 && gensetsensor==0 && doorsensor==0 && fuelsensor==0 && analog1==0 && analog2==0 && analog3==0 && analog4==0){
       var types=0;
   }else{
       var types=1;
   }

    if(devicetype==2 && types == 0){
     alert("Please Select advanced features or analog");
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
   }else if(devicetype=='2' && tempsen=='2' && (analog1=='0' || analog2=='0')){
       alert("Please Select All Analog Sensor For Two Temperature");
       return false;
   }else if(devicetype=='2' && tempsen=='3' && (analog1=='0' || analog2=='0' || analog3=='0')){
       alert("Please Select All Analog Sensor For Three Temperature");
       return false;
   }else if(devicetype=='2' && tempsen=='4' && (analog1=='0' || analog2=='0' || analog3=='0' || analog4=='0')){
       alert("Please Select All Analog Sensor For Four Temperature");
       return false;
   } else if(devicetype=='2' && fuelsensor=='1'&& (tempsen =='1' || tempsen=='2')&& (fuelanalog==analog1 || fuelanalog==analog2)){
       alert("Please Select another Analog Sensor For fuelsenor.");
       return false;
    }else{
         $("#myform").submit();
    }
}
</script>