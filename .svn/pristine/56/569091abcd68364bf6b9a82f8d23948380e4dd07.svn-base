<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
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

class inventory {}

$db = new DatabaseManager();
$data = Array();
$srno = 1;

if(isset($_POST['sinvent']))
{
    $basic = GetSafeValueString($_POST['basic'],"string");
    $ac = GetSafeValueString($_POST['ac'],"string");
    $genset = GetSafeValueString($_POST['genset'],"string");       
    $singletemp = GetSafeValueString($_POST['singletemp'],"string");
    $doubletemp = GetSafeValueString($_POST['doubletemp'],"string");
    $threetemp = GetSafeValueString($_POST['threetemp'],"string");
    $fourtemp = GetSafeValueString($_POST['fourtemp'],"string");
    $door = GetSafeValueString($_POST['door'],"string");
    $fuelsensor = GetSafeValueString($_POST['fuelsensor'],"string");
    $panic = GetSafeValueString($_POST['panic'],"string");
    $buzzer = GetSafeValueString($_POST['buzzer'],"string");
    $immobilizer = GetSafeValueString($_POST['immobilizer'],"string");
    $portable = GetSafeValueString($_POST['portable'],"string");
    $twowaycom = GetSafeValueString($_POST['twowaycom'],"string");
    
    $result = $basic+$ac+$genset+$singletemp+$doubletemp+$door+$fuelsensor+$panic+$buzzer+$immobilizer+$portable+$twowaycom+$threetemp+$fourtemp;
    $stock = GetSafeValueString($_POST['stock'],"string");

     
    
    if($stock == 1)
    {
        $st = GetSafeValueString($_POST['stock'],"string");
        $tid = GetSafeValueString($_POST["uteamid"],"string");
    }
    else if($stock == '-1')
    {
        $st = '-1' ;
    }
    else if($stock == 'all')
    {
        $st = "";
    }
    else 
    {
       $st = $stock; 
    }
    
   
    
    if($st==1){
    $string = "&res=".$result."&stock=".$st."&uteamid=".$tid;
    }else if($stock=='all'){
       $string = "&res=".$result."&stock=".$stock;  
    }else{
        $string = "&res=".$result."&stock=".$st;
    }
       
    if ($st == "" && $result == 0 && isset($_POST['basic']) == 0)//---condition if no checkbox is selected---
    {
       $SQL = sprintf(" SELECT   *,unit.customerno, vehicle.vehicleno,simcard.simcardno,team.name,devices.uid as cid
                                ,devices.simcardid,simcard.simcardno 
                        FROM unit 
                        LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                        LEFT OUTER JOIN ".DB_PARENT.".team ON team.teamid = unit.teamid
                        LEFT OUTER JOIN devices ON devices.uid = unit.uid
                        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid WHERE unit.customerno NOT IN (-1,-2) ORDER BY unit.customerno DESC");
        //$db->executeQuery($SQL);     
    } elseif($st == ""){
       $SQL = sprintf(" SELECT  *,unit.customerno, vehicle.vehicleno,simcard.simcardno,team.name,devices.uid as cid
                                ,devices.simcardid,simcard.simcardno FROM unit 
                        LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                        LEFT OUTER JOIN ".DB_PARENT.".team ON team.teamid = unit.teamid
                        LEFT OUTER JOIN devices ON devices.uid = unit.uid
                        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid WHERE type_value = ".$result." AND unit.customerno NOT IN (-1,-2) ORDER BY unit.customerno DESC");
    }
    
    
    elseif($st == 1 && $result == 0 && isset($_POST['basic']) == 0){
        $SQL = sprintf("SELECT  *, unit.customerno, vehicle.vehicleno,simcard.simcardno,team.name,devices.uid as cid
                                ,devices.simcardid,simcard.simcardno 
                        FROM    unit 
                        LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                        LEFT OUTER JOIN ".DB_PARENT.".team ON team.teamid = unit.teamid
                        LEFT OUTER JOIN devices ON devices.uid = unit.uid
                        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid WHERE unit.customerno = ".$st." and unit.teamid=$tid" );
        //$db->executeQuery($SQL);
             
    }elseif($st == 1 ){
       $SQL = sprintf(" SELECT  *, unit.customerno, vehicle.vehicleno,simcard.simcardno,team.name,devices.uid as cid
                                ,devices.simcardid,simcard.simcardno 
                        FROM    unit 
                        LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                        LEFT OUTER JOIN ".DB_PARENT.".team ON team.teamid = unit.teamid
                        LEFT OUTER JOIN devices ON devices.uid = unit.uid
                        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid WHERE type_value = ".$result ." and unit.customerno = ".$st." and unit.teamid=$tid " );
        //$db->executeQuery($SQL);
             
    }
    elseif($result == 0 && isset($_POST['basic']) == 0){
      $SQL = sprintf("  SELECT  *,unit.customerno,vehicle.vehicleno,simcard.simcardno,team.name,devices.uid as cid,devices.simcardid
                                ,simcard.simcardno 
                        FROM    unit 
                        LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                        LEFT OUTER JOIN ".DB_PARENT.".team ON team.teamid = unit.teamid
                        LEFT OUTER JOIN devices ON devices.uid = unit.uid
                        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid WHERE  unit.customerno = ".$st );
        //$db->executeQuery($SQL);
             
    }
    else{
      $SQL = sprintf("  SELECT  *,unit.customerno,vehicle.vehicleno,simcard.simcardno,team.name,devices.uid as cid,devices.simcardid
                                ,simcard.simcardno 
                        FROM    unit 
                        LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                        LEFT OUTER JOIN ".DB_PARENT.".team ON team.teamid = unit.teamid
                        LEFT OUTER JOIN devices ON devices.uid = unit.uid
                        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid WHERE type_value = ".$result ." and unit.customerno = ".$st );
        //$db->executeQuery($SQL);
             
    }
    
        $db->executeQuery($SQL);
        
        if ($db->get_rowCount() > 0) 
        {
            while ($row = $db->get_nextRow())
            {
                $data = new inventory();
                //-----------------------------to find the type of device-------------------------------
                $category_array= Array();
                $category = (int) $row['type_value'];
                $binarycategory = sprintf("%08s",DecBin($category));
                    for($shifter=1;$shifter<=8103;$shifter=$shifter<<1)
            {
                    $binaryshifter = sprintf("%08s",DecBin($shifter));
                    if($category & $shifter)
                {
                 $category_array[]= $shifter;
                }
            }

                $data->srno = $srno;
                $data->vehicleno = $row["vehicleno"];        
                $data->customerno = $row["customerno"];        
                $data->unitno = $row["unitno"];                
                $data->simcardno = $row["simcardno"];                        
                $data->teamname = $row["name"];
                $data->unitid = $row["uid"];
                $data->unitprice = $row["unitprice"];

                //----------------------------------Display type of Unit------------------------------------
                if($row['type_value']!=0 && !in_array(0, $category_array)) {
                  $data->basic="Yes";  
                }
                else{
                  $data->basic="Yes";   
                }
                if(in_array(1, $category_array)) {
                  $data->ac="Yes";  
                }
                else{
                  $data->ac="No";   
                }
                if(in_array(4, $category_array)) {
                  $data->door="Yes";  
                }
                else{
                  $data->door="No";   
                }
                if(in_array(2, $category_array)) {
                  $data->genset="Yes";  
                }
                else{
                  $data->genset="No";   
                }
                if(in_array(8, $category_array)) {
                  $data->stemp="Yes";  
                }
                else{
                  $data->stemp="No";   
                }
                if(in_array(16, $category_array)) {
                  $data->dtemp="Yes";  
                }
                else{
                  $data->dtemp="No";   
                }
                if(in_array(2048, $category_array)) {
                  $data->ttemp="Yes";  
                }
                else{
                  $data->ttemp="No";   
                }
                if(in_array(4096, $category_array)) {
                  $data->ftemp="Yes";  
                }
                else{
                  $data->ftemp="No";   
                }
                if(in_array(32, $category_array)) {
                  $data->panic="Yes";  
                }
                else{
                  $data->panic="No";   
                }
                if(in_array(64, $category_array)) {
                  $data->buzzer="Yes";  
                }
                else{
                  $data->buzzer="No";   
                }
                if(in_array(128, $category_array)) {
                  $data->immo="Yes";  
                }
                else{
                  $data->immo="No";   
                }

                if(in_array(256, $category_array)) {
                  $data->twowaycom="Yes";  
                }
                else{
                  $data->twowaycom="No";   
                }

                if(in_array(512, $category_array)) {
                  $data->portable="Yes";  
                }
                else{
                  $data->portable="No";   
                }

                 if(in_array(1024, $category_array)) {
                  $data->fuel="Yes";  
                }
                else{
                  $data->fuel="No";   
                }

                $display[] = $data; 
                $srno++;
            }
        
        }
}else if($_GET['show']==1){
    $res = $_GET["res"];
    $st = $_GET["stock"];
    $teamid = $_GET["uteamid"];
    
    if($st==1){
        $string = "&res=".$res."&stock=".$st."&uteamid=".$teamid;
    }else if($st=='all'){
       $string = "&res=".$res."&stock=".$st;  
    }else{
        $string = "&res=".$res."&stock=".$st;
    }
    
    if($teamid=="0"){
    $sqland = " and unit.teamid=0";
    }else if($st=="all" && $teamid==""){
        $sqland = "";
    }else if($st=="-1" && $teamid==""){
        $sqland = "";
    }else if($st=='1' && $teamid!="0"){
        $sqland =" and unit.teamid=".$teamid; 
    }else if($st!="all" && $st!="-1" && $st!="1"){
        $sqland = "";
    }
    

    if($st!="all" || $st!="-1" || $st!="1"){
        $customerand = " and unit.customerno = ".$st; 
    }else if($st=="all"){
        $customerand=" AND unit.customerno NOT IN (-1,-2) ORDER BY unit.customerno DESC ";
    }
    
    
    
 $SQL = sprintf("SELECT *,unit.customerno,vehicle.vehicleno,simcard.simcardno,team.name,devices.uid as cid,devices.simcardid,simcard.simcardno FROM unit 
                             LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                             LEFT OUTER JOIN ".DB_PARENT.".team ON team.teamid = unit.teamid
                             LEFT OUTER JOIN devices ON devices.uid = unit.uid
                             LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid WHERE type_value = ".$res .$customerand.$sqland );
 $db->executeQuery($SQL);
        
        if ($db->get_rowCount() > 0) 
        {
            while ($row = $db->get_nextRow())
            {
                $data = new inventory();
                //-----------------------------to find the type of device-------------------------------
                $category_array= Array();
                $category = (int) $row['type_value'];
                $binarycategory = sprintf("%08s",DecBin($category));
                    for($shifter=1;$shifter<=8103;$shifter=$shifter<<1)
            {
                    $binaryshifter = sprintf("%08s",DecBin($shifter));
                    if($category & $shifter)
                {
                 $category_array[]= $shifter;
                }
            }

                $data->srno = $srno;
                $data->vehicleno = $row["vehicleno"];        
                $data->customerno = $row["customerno"];        
                $data->unitno = $row["unitno"];                
                $data->simcardno = $row["simcardno"];                        
                $data->teamname = $row["name"];
                $data->unitid = $row["uid"];
                $data->unitprice = $row["unitprice"];

                //----------------------------------Display type of Unit------------------------------------
                if($row['type_value']!=0 && !in_array(0, $category_array)) {
                  $data->basic="Yes";  
                }
                else{
                  $data->basic="Yes";   
                }
                if(in_array(1, $category_array)) {
                  $data->ac="Yes";  
                }
                else{
                  $data->ac="No";   
                }
                if(in_array(4, $category_array)) {
                  $data->door="Yes";  
                }
                else{
                  $data->door="No";   
                }
                if(in_array(2, $category_array)) {
                  $data->genset="Yes";  
                }
                else{
                  $data->genset="No";   
                }
                if(in_array(8, $category_array)) {
                  $data->stemp="Yes";  
                }
                else{
                  $data->stemp="No";   
                }
                if(in_array(16, $category_array)) {
                  $data->dtemp="Yes";  
                }
                else{
                  $data->dtemp="No";   
                }
                if(in_array(2048, $category_array)) {
                  $data->ttemp="Yes";  
                }
                else{
                  $data->ttemp="No";   
                }
                if(in_array(4096, $category_array)) {
                  $data->ftemp="Yes";  
                }else{
                  $data->ftemp="No";   
                }
                if(in_array(32, $category_array)) {
                  $data->panic="Yes";  
                }
                else{
                  $data->panic="No";   
                }
                if(in_array(64, $category_array)) {
                  $data->buzzer="Yes";  
                }
                else{
                  $data->buzzer="No";   
                }
                if(in_array(128, $category_array)) {
                  $data->immo="Yes";  
                }
                else{
                  $data->immo="No";   
                }

                if(in_array(256, $category_array)) {
                  $data->twowaycom="Yes";  
                }
                else{
                  $data->twowaycom="No";   
                }

                if(in_array(512, $category_array)) {
                  $data->portable="Yes";  
                }
                else{
                  $data->portable="No";   
                }

                 if(in_array(1024, $category_array)) {
                  $data->fuel="Yes";  
                }
                else{
                  $data->fuel="No";   
                }

                $display[] = $data; 
                $srno++;
            }
        
        }
        
        if(!empty($res)){
            $category_array1= Array();
            $category = (int) $row['type_value'];
            $binarycategory = sprintf("%08s",DecBin($category));
                    for($shifter=1;$shifter<=8103;$shifter=$shifter<<1)
            {
                    $binaryshifter = sprintf("%08s",DecBin($shifter));
                    if($category & $shifter)
                {
                 $category_array1[]= $shifter;
                }
            }
        }
}


//---------------datagrid to display results-----------
$dg = new objectdatagrid($display);
$dg->AddColumn("Sr. no.", "srno");
$dg->AddColumn("Customer No.", "customerno");
//$dg->AddColumn("Customer Name", "customername");
$dg->AddColumn("Vehicle No.", "vehicleno");
$dg->AddColumn("Elixir", "teamname");
$dg->AddColumn("Unit no", "unitno");
$dg->AddColumn("Simcard No.", "simcardno");
$dg->AddColumn("Basic","basic");
$dg->AddColumn("AC","ac");
$dg->AddColumn("Genset","genset");
$dg->AddColumn("Door","door");
$dg->AddColumn("Fuel","fuel");
$dg->AddColumn("Temperature 1","stemp");
$dg->AddColumn("Temperature 2","dtemp");
$dg->AddColumn("Temperature 3","ttemp");
$dg->AddColumn("Temperature 4","ftemp");

$dg->AddColumn("Panic","panic");
$dg->AddColumn("Buzzer","buzzer");
$dg->AddColumn("Two way comm","twowaycom");
$dg->AddColumn("Portable","portable");
$dg->AddColumn("Unit Price","unitprice");
$dg->SetNoDataMessage("No Search Results");
$dg->AddRightAction("View/Edit", "../../images/edit.png", "edit_type.php?id=%d".$string);
$dg->AddIdColumn("unitid");


$db = new DatabaseManager();
//-----------populate customerno list-------
function getcustomer_detail() {
        $db = new DatabaseManager();
        $customernos = Array();
        $SQL = sprintf("SELECT customerno,customername,customercompany FROM ".DB_PARENT.".customer WHERE customerno NOT IN(1,2)");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $customer = new inventory();
                $customer->customerno = $row['customerno'];
                $customer->customername = $row['customername'];
                $customer->customercompany = $row['customercompany'];
                $customernos[] = $customer;
            }
            return $customernos;
            //print_r($customernos);
            }
        return false;
    }
    
//-----------populate team members list--------------------------    
$SQL = sprintf("SELECT team.teamid, team.name FROM ".DB_PARENT.".team ORDER BY name asc");
$db->executeQuery($SQL);
$team_allot_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $team = new Inventory();
        $team->teamid  = $row["teamid"];
        $team->name = $row["name"];        
        $team_allot_array[] = $team;        
    }    
  
} 

?>

<!------------Inventory analysis Form---------------------->

<div class="panel">
    <div class="paneltitle" align="center">
        Inventory analysis</div>
    <div class="panelcontents">
       
        <form method="post" name="inventory_form" id="inventory_form" action="inventory.php" onsubmit="ValidateForm(); return false;"  enctype="multipart/form-data">
    <table width="65%">
        <tr>
            <td>Select Device Type:-</td>
        </tr>
      
        <tr>
            
            <td>
                <input name="basic" id="basic" type="checkbox" value="0" <?php if($_GET["show"]==1){ echo "checked";}?><?php if(isset($_POST['basic'])){ echo "checked" ; } ?> /> Basic  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="ac" id="ac" type="checkbox" value="1" <?php if($_GET["show"]=='1'){ if(in_array(1, $category_array)){ echo "checked";}} ?>  <?php if(isset($_POST['ac'])){ echo "checked" ; } ?> /> AC  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="genset" id="genset" type="checkbox" value="2" <?php if($_GET["show"]=='1'){ if(in_array(2, $category_array)){ echo "checked";}} ?>  <?php if(isset($_POST['genset'])){ echo "checked" ; } ?>/> Genset  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="fuelsensor" id="fuelsensor" type="checkbox" value="1024" <?php if($_GET["show"]=='1'){ if(in_array(1024, $category_array)){ echo "checked";}} ?> <?php if(isset($_POST['fuelsensor'])){ echo "checked" ; } ?>/> Fuel Sensor  &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <br/>
        <tr>
            <td><span>Temperature&nbsp;</span>
                <input name="singletemp" id="singletemp" type="checkbox" value="8" <?php if($_GET["show"]==1){ if(in_array(8, $category_array)){ echo "checked";} }?> <?php if(isset($_POST['singletemp'])){ echo "checked" ; } ?>/> 1  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="doubletemp" id="doubletemp" type="checkbox" value="16" <?php if($_GET["show"]==1){ if(in_array(16, $category_array)){ echo "checked";} }?> <?php if(isset($_POST['doubletemp'])){ echo "checked" ; } ?> /> 2  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="threetemp" id="threetemp" type="checkbox" value="2048" <?php if($_GET["show"]==1){ if(in_array(2048, $category_array)){ echo "checked";} }?> <?php if(isset($_POST['threetemp'])){ echo "checked" ; } ?> /> 3  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="fourtemp" id="fourtemp" type="checkbox" value="4096" <?php if($_GET["show"]==1){ if(in_array(4096, $category_array)){ echo "checked";} }?> <?php if(isset($_POST['fourtemp'])){ echo "checked" ; } ?> /> 4  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="door" id="door" type="checkbox" value="4" <?php if($_GET["show"]=='1'){ if(in_array(4, $category_array)){ echo "checked";}} ?> <?php if(isset($_POST['door'])){ echo "checked" ; } ?>/> Door  &nbsp;&nbsp;&nbsp;&nbsp
            </td>
        </tr>
        <tr>
            
            <td>
                <input name="panic" id="panic" type="checkbox" value="32" <?php if($_GET["show"]=='1'){ if(in_array(32, $category_array)){ echo "checked";}} ?> <?php if(isset($_POST['panic'])){ echo "checked" ; } ?>/> Panic  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="buzzer" id="buzzer" type="checkbox" value="64" <?php if($_GET["show"]=='1'){ if(in_array(64, $category_array)){ echo "checked";}} ?> <?php if(isset($_POST['buzzer'])){ echo "checked" ; } ?>/> Buzzer  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="immobilizer" id="immobilizer" type="checkbox" value="128" <?php if($_GET["show"]=='1'){ if(in_array(128, $category_array)){ echo "checked";}} ?> <?php if(isset($_POST['immobilizer'])){ echo "checked" ; } ?>/> Immobilizer  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="twowaycom" id="twowaycom" type="checkbox" value="256" <?php if($_GET["show"]=='1'){ if(in_array(256, $category_array)){ echo "checked";}} ?> <?php if(isset($_POST['twowaycom'])){ echo "checked" ; } ?> /> Two Way Communication  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="portable" id="portable" type="checkbox" value="512"  <?php if($_GET["show"]=='1'){ if(in_array(512, $category_array)){ echo "checked";}} ?><?php if(isset($_POST['portable'])){ echo "checked" ; } ?>/> Portable  &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
         <?php 
         if($_GET["show"]==1){
       ?>      
             <td>Stock In
            <select name="stock" id="stock" onchange="getElixir();">
           <!----     <option value="0">Select Option</option>---->
                <option value="1"<?php if($_GET["stock"]=='1'){ echo "selected" ; } ?>/>Inventory(In Office)</option>
                <option value="-1"<?php if($_GET["stock"]=='-1'){ echo "selected" ; } ?>/>Repair</option>
                <option value="all"<?php if($_GET["stock"]=='all'){ echo "selected" ; } ?>/>All Customers</option>
                <?php
                       
                        $cms = getcustomer_detail();
                       foreach($cms as $customer)
                       {
                ?> 
                <option value="<?php echo($customer->customerno);?>" <?php if($_GET['stock']== $customer->customerno){ echo "selected" ; } ?>><?php echo $customer->customerno;?> - <?php echo $customer->customercompany?></option>
                <?php
                        }
                ?>        
                </select>
            
        </td>
             
         <?php     
         }else{
         ?>   
        <td>Stock In 
            <select name="stock" id="stock" onchange="getElixir();">
           <!----     <option value="0">Select Option</option>---->
                <option value="1"<?php if(isset($_POST['stock']) && $_POST['stock'] == "1"){ echo "selected" ; } ?>/>Inventory(In Office)</option>
                <option value="-1"<?php if(isset($_POST['stock']) && $_POST['stock'] == "-1"){ echo "selected" ; } ?>/>Repair</option>
                <option value="all"<?php if(isset($_POST['stock'] )&& $_POST['stock'] == "all"){ echo "selected" ; } ?>/>All Customers</option>
                <?php
                       
                        $cms = getcustomer_detail();
                       foreach($cms as $customer)
                       {
                ?> 
                <option value="<?php echo($customer->customerno);?>" <?php if(isset($_POST['stock']) && $_POST['stock'] == $customer->customerno){ echo "selected" ; } ?>><?php echo $customer->customerno;?> - <?php echo $customer->customercompany?></option>
                <?php
                        }
                ?>        
                </select>
            
        </td>
         <?php }?>
        
        <?php 
        //uteamid
        
         if($_GET["show"]=='1' && $_GET["stock"]=='1'){
           ?>
             <td id="uteam_inv" <?php if($_GET['stock'] != "1"){ echo 'style="display:none"'; } ?>>Alloted To 
            <select name="uteamid"  >
                 <option value="0">Select Elixir</option>
                <?php
                foreach($team_allot_array as $thisteam)
                {
                ?>
                <option value="<?php echo($thisteam->teamid); ?>"<?php if($_GET['uteamid'] == $thisteam->teamid){ echo "selected" ; } ?> /><?php echo($thisteam->name); ?></option>
                <?php
                }
                ?>
            </select>
        </td>
        <?php     
         }else if($_GET["show"]=='1'){
          ?>   
              <td id="uteam_inv" <?php if($_GET['stock']!=1){ echo 'style="display:none"'; } ?>>Alloted To 
            <select name="uteamid"  >
                 <option value="0">Select Elixir</option>
                <?php
                foreach($team_allot_array as $thisteam)
                {
                ?>
                <option value="<?php echo($thisteam->teamid); ?>"<?php if($_GET['uteamid'] == $thisteam->teamid){ echo "selected" ; } ?> /><?php echo($thisteam->name); ?></option>
                <?php
                }
                ?>
            </select>
        </td>
        <?php     
             
         }else{
        ?>
        <td id="uteam_inv" <?php if(isset($_POST['stock']) && $_POST['stock'] != "1"){ echo 'style="display:none"'; } ?>>Alloted To 
            <select name="uteamid"  >
                 <option value="0">Select Elixir</option>
                <?php
                foreach($team_allot_array as $thisteam)
                {
                ?>
                <option value="<?php echo($thisteam->teamid); ?>"<?php if(isset($_POST['uteamid']) && $_POST['uteamid'] == $thisteam->teamid){ echo "selected" ; } ?> /><?php echo($thisteam->name); ?></option>
                <?php
                }
                ?>
            </select>
        </td>
         <?php } ?>
        </tr>
        </table>
         <div><input type="submit" id="sinvent" name="sinvent" value="Search"/></div>
    </form>
    </div>
    </div>
    <br/>
    <div class="panel">
    <div class="paneltitle" align="center"><?php if(isset($_POST["sinvent"]))?></div>
    <div class="panelcontents">
                <?php $dg->Render(); ?>
    </div>

    </div>
    
    <script type="text/javascript">
    function getElixir(){
        var stock = $("#stock").val();
        if(stock == 1){
             $("#uteam_inv").show();
        }else{
             $("#uteam_inv").hide();
        }
        
    }
    </script>      