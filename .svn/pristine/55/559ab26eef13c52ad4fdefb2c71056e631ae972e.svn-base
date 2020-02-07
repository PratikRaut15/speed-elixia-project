<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

class testing {
    
}
$teamid = GetLoggedInUserId();
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();
global $db;
$SQL = sprintf("SELECT customerno, customercompany FROM ".DB_PARENT.".customer");
$db->executeQuery($SQL);
$customer = Array();
if ($db->get_rowCount() > 0){
    while ($row = $db->get_nextRow())
    {
        $testing = new testing();
        $testing->customerno = $row["customerno"];
        $testing->customername = $row["customerno"]."( ".$row['customercompany']." )";
        $customer[] = $testing;        
    }    
}



if(IsHead()){
    $SQL = sprintf("SELECT *,(CASE WHEN devicetype=1 THEN 'Basic' WHEN devicetype= 2 THEN 'Advanced'  ELSE '-' END)as devicestatus from ".DB_PARENT.".new_sales  order by orderid desc");
}
else
{
    $SQL = sprintf("SELECT *,(CASE WHEN devicetype=1 THEN 'Basic' WHEN devicetype= 2 THEN 'Advanced'  ELSE '-' END)as devicestatus from ".DB_PARENT.".new_sales where teamid=".$teamid." order by orderid desc");
}
$db->executeQuery($SQL);

function get_teamname($id){
$db = new DatabaseManager();
$SQL = sprintf("select name from ".DB_PARENT.".team where teamid=".$id);
$db->executeQuery($SQL);
 while ($row = $db->get_nextRow())   
 {
     $teamname = $row["name"];
 }
return $teamname;
}

function get_customername($id,$oid){
$db = new DatabaseManager();
if($id=='-1')
{
    $SQL = sprintf("select new_customer from ".DB_PARENT.".new_sales where orderid=".$oid);
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow())   
    {
        $customercompany = $row["new_customer"]."(New Customer)";
    }  
}
else
{
$SQL = sprintf("select customercompany from ".DB_PARENT.".customer where customerno=".$id);
$db->executeQuery($SQL);
 while ($row = $db->get_nextRow())   
 {
     $customercompany = $row["customercompany"];
 }
} 
return $customercompany;
}


$x=0;
$dispdetails = Array();
 if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $pending = $row['deviceqty']-$row['install_device_qty'];
        if($pending!='0'){
        $x++;
        $order = new testing();
        $order->orderid = $row['orderid'];
        $orderdate1 = $row['orderdate'];
        $order->orderdate = date('d-m-Y', strtotime($orderdate1));
        $order->teamname = get_teamname($row['teamid']);
        $order->teamid = $row['teamid'];
        $order->customerno = $row['customerno'];
        $order->customername = get_customername($row['customerno'], $row['orderid']);
        $order->deviceqty = $row['deviceqty'];
        $order->installdeviceqty = $row['install_device_qty'];
        $order->pendingdevice = $row['deviceqty']-$row['install_device_qty'];
        $order->devicetype = $row['devicetype'];
        $order->contactno = $row['contactno'];
        $order->devicestatus = $row['devicestatus'];
        $order->contact_person = $row['contact_person'];
        $order->remark = $row['remark'];
        $order->x = $x;
        $dispdetails[] = $order;
    }
    }

}
$dg = new objectdatagrid($dispdetails);
$dg->AddColumn("Sr No", "x");
$dg->AddColumn("Order Date", "orderdate");
$dg->AddColumn("Company", "customername");
$dg->AddColumn("Total installation Qty","deviceqty");
$dg->AddColumn("Pending installation Qty","pendingdevice");
$dg->AddColumn("Device Type", "devicestatus");
$dg->AddColumn("Contact Name", "contact_person");
$dg->AddColumn("Contact Number", "contactno");
$dg->AddColumn("Created By", "teamname");
$dg->AddRightAction("Edit / Delete", "../../images/edit.png", "modifyorder.php?oid=%d");
$dg->SetNoDataMessage("No Sales Orders");
$dg->AddIdColumn("orderid");


$message="";
$orderdate1="";
$ordercustomer="";
$deviceqty="";
$custname="";
$contactno="";
$teamid = GetLoggedInUserId();
if(isset($_POST["osubmit"]))
{
    $teamid = GetLoggedInUserId();
    $orderdate1 = $_POST["orderdate"];
    $orderdate = date('Y-m-d', strtotime($orderdate1));
    $ordercustomer =$_POST["ordercustomer"];
    $shownametext = $_POST["shownametext"];
    $devicetype = $_POST["device"];
    $deviceqty = $_POST["deviceqty"];
    $custname = $_POST["custname"];
    $contactno = $_POST["contactno"];
    $remark = $_POST["remark"];
    $acsensor = $_POST["acsensor"];
    $gensetsensor = $_POST["gensetsensor"];
    $doorsensor = $_POST["doorsensor"];
    $fuelsensor = $_POST["fuelsensor"];
    $tempsen = $_POST["tempsen"];
    $panic = $_POST["panic"];
    $buzzer = $_POST["buzzer"];
    $immobilizer = $_POST["immobilizer"];
    $portable= $_POST["portable"];
    $twowaycom = $_POST["twowaycom"];
    $type_value = 0;
    
    

    function get_relmanager($ordercustomer)
    {
        global $db;
        if($ordercustomer !='-1'){
        $SQL = sprintf("select * from ".DB_PARENT.".customer where customerno=".$ordercustomer);
        $db->executeQuery($SQL);
            if ($db->get_rowCount() > 0) 
            {
                while ($row = $db->get_nextRow())   
                {
                    $rel_manager_id = $row["rel_manager"];
                }
            }
        }
        else
        {
            $rel_manager_id ='';
        }
        return $rel_manager_id;
    }
    $rel_manager = get_relmanager($ordercustomer);
    
    if($orderdate==""){
        $message = "Please select date";
    }elseif ($ordercustomer=='0'){
        $message ="Please select customer";
    }elseif($ordercustomer=='-1'){
        if($shownametext==""){
            $message ="Please enter new Customer Name";
        }
    }elseif(empty($deviceqty)){
        $message ="Please enter device quantity";
    }elseif($custname==""){
        $message ="Please filled contact person name";
    }elseif($devicetype=='1' && ($acsensor=="1" || $gensetsensor=="1" || $doorsensor=="1" || $fuelsensor=="1" || $tempsen=='1' || $tempsen=='2' || $tempsen=='3' || $tempsen=='4')){
         $message ="Please select advanced device type";
    }elseif($contactno==""){
        $message ="Please filled contact number";
    }elseif($remark==""){
        $message = "Please filled remark";
    }
    
    if($message=="")
    {
  
    if($fuelsensor=='4'){
        $fs=1;
    }else{
        $fs=0;
    }    
        
    if($acsensor=='1'){
        $acs =1;
    }
    else
    {
        $acs =0;
    }
    if($gensetsensor=='2'){
        $gss =1;
    }
    else
    {
        $gss =0;
    }
    if($doorsensor=='3'){
        $dos =1;
    }
    else
    {
        $dos =0;
    }

       if($devicetype == 1){
           $type_value = $type_value + 0;
       }
       if($devicetype == 2 && $sensor==1){
           $type_value = $type_value + 1;
       }
       if($devicetype == 2 && $sensor==2){
           $type_value = $type_value + 2;
       }
       if($devicetype == 2 && $sensor==3){
           $type_value = $type_value + 4;
       }
       if($devicetype == 2 && $tempsen == 1 )
       {
           $type_value = $type_value + 8;
       }
       if($devicetype == 2 && $tempsen == 2 )
       {
           $type_value = $type_value + 16;
       }
       if(($devicetype == 2 || $devicetype==1 )&& $panic ==1){
           $type_value = $type_value + 32;
       } 
       if(($devicetype == 2|| $devicetype==1) && $buzzer ==1){
           $type_value = $type_value + 64;
       } 
       if(($devicetype == 2 || $devicetype==1)&& $immobilizer ==1){
           $type_value = $type_value + 128;
       }
       if(($devicetype == 2 || $devicetype==1)&& $twowaycom ==1){
            $type_value = $type_value + 256;
       }
       if(($devicetype== 2|| $devicetype==1) && $portable ==1){
           $type_value = $type_value + 512;
       }
       if(($devicetype== 2 || $devicetype==1) && $fuelsensor ==4){
           $type_value = $type_value + 1024;
       }
       if($devicetype == 2 && $tempsen == 3 ){
            $type_value = $type_value + 2048;
        }
        if($devicetype == 2 && $tempsen == 4 ){
            $type_value = $type_value + 4096;
        }
   
      

      $SQLOrder =sprintf("INSERT INTO ".DB_PARENT.".new_sales(`orderdate`,`teamid`,`customerno`,`rel_manager`,`new_customer`,`deviceqty`,`devicetype`,`contactno`,`contact_person`,`remark`) VALUES ('%s','%d','%d','%d','%s','%d','%d','%d','%s','%s')",$orderdate,$teamid,$ordercustomer,$rel_manager,$shownametext,$deviceqty,$devicetype,$contactno,$custname,$remark);

    $SQLOrderADV = sprintf( "INSERT INTO ".DB_PARENT.".new_sales(`orderdate`,`teamid`,`customerno`,`rel_manager`,`new_customer`,`deviceqty`,`devicetype`,`contactno`,`contact_person`,`remark`,`acsensor`,`gensetsensor`,`doorsensor`,`fuelsensor`,`digitalioupdated`)
     VALUES ( '%s','%d','%d','%d','%s','%d','%d','%d','%s','%s','%d','%d','%d','%d','%s')",$orderdate,$teamid,$ordercustomer,$rel_manager,$shownametext,$deviceqty,$devicetype,$contactno,$custname,$remark,$acs,$gss,$dos,$fs, $today);

        if($devicetype == 1){
        $db->executeQuery($SQLOrder);    
        }else{
         $db->executeQuery($SQLOrderADV);    
        }
        $orderid = $db->get_insertedId();
        // Temperature Sensor 1,2,3,4
        if($devicetype ==2 && ($tempsen== 1 || $tempsen== 2 || $tempsen== 3 || $tempsen== 4))
        {
          $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET tempsen='%s' where orderid='%s'",$tempsen,$orderid);
          $db->executeQuery($SQL);
        }
        // SET Unit Type Value 
        $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET type_value='%s' WHERE orderid=%d", $type_value,$orderid);
        $db->executeQuery($SQL);
        
        // Panic
        if($panic == 1)
        {
            $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_panic=1 WHERE orderid= $orderid");
            $db->executeQuery($SQL);
        }

        // Buzzer
        if($buzzer == 1)
        {
            $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_buzzer=1 WHERE orderid= $orderid");
            $db->executeQuery($SQL);
        }

        // Immobilizer
        if($immobilizer == 1)
        {
            $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_mobiliser=1 WHERE orderid= $orderid");
            $db->executeQuery($SQL);
        }
        
        // Two way comm
        if($twowaycom == 1)
        {
            $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_twowaycom=1 WHERE orderid= $orderid");
            $db->executeQuery($SQL);
        }
        // portable
        if($portable == 1)
        {
            $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_portable=1 WHERE orderid= $orderid");
            $db->executeQuery($SQL);
        }
        
        header("location:orders.php");
    }
    
}
include("header.php");
?>
<style>
    .adv{
        display: none;
    }
</style>
<div class="panel">
<div class="paneltitle" align="center">Order Form</div>
<div class="panelcontents">
    <?php 
    if(!empty($message)){
        echo"<span style='color:red; font-size:12px;'>".$message."</span>";
    }
    ?>
    <form name='orderform' id='orderform' method='POST' onsubmit="return ValidateForm();" action="orders.php"  enctype="multipart/form-data">
    <table>
        <tr><td><label>Order Date </label></td><td><input type='text' name='orderdate' id='orderdate' value="<?php echo $orderdate1;?>"/></td></tr>
                <tr><td><label>Customer </label></td><td>
             <select name="ordercustomer" id="ordercustomer" onchange="show_newcomp()" style='width: 200px;'>
                        <option value="0">Select a Customer</option>     
                        <option value="-1">New Customer</option>
                            <?php
                            foreach($customer as $thiscustomer)
                            {
                                ?>
                                <option value="<?php echo($thiscustomer->customerno); ?>" <?php if($ordercustomer == $thiscustomer->customerno){echo "selected";}?> ><?php echo($thiscustomer->customername); ?></option>
                                <?php
                            }
                            ?>
                    </select>
                        
                    <div id="showtextname"><input type="text" name="shownametext" id="shownametext"/></div>
                        
                        
            </td></tr>
                <tr><td><label>Total Device Qty</label></td><td><input type="text" name="deviceqty" id="deviceqty" onkeypress="return onlyNos(event,this);" value="<?php echo $deviceqty;?>"></td></tr>
         <tr>
            <td> <label>Device Type</label></td>
            <td> <input name="device" id="device" type="radio" value="1" onclick="device_type();" checked=""/> &nbsp;Basic &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="device" id="device" type="radio" value="2" onclick="device_type();"/> &nbsp; Advanced &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr class="adv" >
            <td><label>Sensor</label></td>
            <td>
                <input name="acsensor" id="acsensor" type="checkbox" value="1" />  &nbsp;  Ac Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                <input name="gensetsensor" id="gensetsensor" type="checkbox" value="2" />  &nbsp;  Genset Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                <input name="doorsensor" id="doorsensor" type="checkbox" value="3" />  &nbsp;  Door Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                 <input name="fuelsensor" id="fuelsensor" type="checkbox" value="4" />  &nbsp;  Fuel Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                
            </td>
        </tr>
       
        <tr class="adv">
            <td><label>Temperature</label></td>
            <td>
                <input name="tempsen" id="tempsen" type="radio" value="0" /> None 
                <input name="tempsen" id="tempsen1" type="radio" value="1" />  1  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tempsen" id="tempsen2" type="radio" value="2"/> 2 &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tempsen" id="tempsen3" type="radio" value="3"/> 3 &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tempsen" id="tempsen4" type="radio" value="4"/> 4 &nbsp;&nbsp;&nbsp;&nbsp;
                
            </td>
        </tr>
        <tr class="adv">
            <td></td>
            <td>
                <input name="panic" id="panic" type="checkbox" value="1" /> Panic  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="buzzer" id="buzzer" type="checkbox" value="1" /> Buzzer  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="immobilizer" id="immobilizer" type="checkbox" value="1" /> Immobilizer  &nbsp;&nbsp;&nbsp;&nbsp;
                   <input name="twowaycom" id="twowaycom" type="checkbox" value="1" /> Two Way Communication  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="portable" id="portable" type="checkbox" value="1" /> Portable  &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr><td><label>Contact Person Name </label></td><td><input type='text' name='custname' id='custname' value="<?php echo $custname;?>"/></td></tr>
        <tr><td><label>Contact Number </label></td><td><input type='text' name='contactno' id='contactno' value="<?php echo $contactno;?>" onkeypress="return onlyNos(event,this);"/></td></tr>
        <tr><td><label>Remark </label></td>
            <td>
                <textarea name='remark' id='remark'><?php echo $remark;?></textarea>
            </td></tr>
        <tr><td>&nbsp;</td><td><input type='submit' name='osubmit' value='Submit'/></td></tr>
    </table>
</form>
</div>
</div>

<br/>

<?php
    if(IsHead()){
        $SQL = sprintf("SELECT `deviceqty`-`install_device_qty` as `pendingdevice`,(CASE WHEN devicetype=1 THEN 'Basic' WHEN devicetype= 2 THEN 'Advanced'  ELSE '-' END)as devicestatus from ".DB_PARENT.".new_sales  order by orderid desc");
    }else{
        $SQL = sprintf("SELECT `deviceqty`-`install_device_qty` as `pendingdevice`,(CASE WHEN devicetype=1 THEN 'Basic' WHEN devicetype= 2 THEN 'Advanced'  ELSE '-' END)as devicestatus from ".DB_PARENT.".new_sales where teamid=".$teamid." order by orderid desc");
    }
    $db->executeQuery($SQL);
    if($db->get_rowCount() > 0){
    $pendingdevice = Array();
    while ($row = $db->get_nextRow())
    {
        $pendingdevice[] =  $row['pendingdevice'];
    }
    $pending =  array_sum($pendingdevice);
    if($pending=="" ||$pending=="0"){
        $pendings = 0;
    }else{
        $pendings = $pending;
    }
    } 

    
?>
<div class="panel">
<div class="paneltitle" align="center">Orders List
<span style="text-align:right; float:right;"><?php echo "Pending Installation :&nbsp;".$pendings;?></span>
</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>
<br/>
<script type="text/javascript">

function show_newcomp(){
    
    var ordercustomer = $("#ordercustomer").val();
    if(ordercustomer=='-1')
    {
        $("#showtextname").show();
    }else{
        $("#showtextname").hide();
    }
}


function show_heirarchy()
{
   
    if($("#cmaintenance").is(':checked'))
         $("#heir_tr").show()
      else
         $("#heir_tr").hide()
     
     
}


$(document).ready(function(){ 
        $('#orderdate').datepicker({
            format: "dd-mm-yyyy",
            language:  'en',
            autoclose: 1
        }); 
    });
 
function device_type(){
    var device = $('input:radio[name=device]:checked').val();
    if(device == 1){
        $(".adv").hide();
    }else{
        $(".adv").show();
    }
}


function ValidateForm(){
   var ordercustomer = $("#ordercustomer").val();
   var custname =  $("#custname").val();
   var deviceqty = $("#deviceqty").val();
   var contactno = $("#contactno").val();
   var orderdate = $("#orderdate").val();
   var shownametext = $("#shownametext").val();
   var device = $('input:radio[name=device]:checked').val();
   var tempsen1 = $('input:checkbox[name=tempsen1]:checked').val()?1:0;
   var tempsen2 = $('input:checkbox[name=tempsen2]:checked').val()?1:0;
   var tempsen3 = $('input:checkbox[name=tempsen3]:checked').val()?1:0;
   var tempsen4 = $('input:checkbox[name=tempsen4]:checked').val()?1:0;
   var acsensor = $('input:checkbox[name=acsensor]:checked').val()?1:0;
   var gensetsensor = $('input:checkbox[name=gensetsensor]:checked').val()?1:0;
   var doorsensor = $('input:checkbox[name=doorsensor]:checked').val()?1:0;
   var fuelsensor = $('input:checkbox[name=fuelsensor]:checked').val()?1:0;
   var panic = $('input:checkbox[name=panic]:checked').val()?1:0;
   var buzzer =$('input:checkbox[name=buzzer]:checked').val()?1:0;
   var immobilizer = $('input:checkbox[name=immobilizer]:checked').val()?1:0;
   var twowaycom = $('input:checkbox[name=twowaycom]:checked').val()?1:0;
   var portable = $('input:checkbox[name=portable]:checked').val()?1:0;
   
   if(acsensor=='0' && gensetsensor=="0" && doorsensor=="0" && fuelsensor=="0"){
      var sensor=0; 
   }else{
       var sensor=1;
   }
  
   if(panic=='0' && buzzer=='0' && immobilizer=='0' && twowaycom=='0' && portable=='0'){
       var types=0;
   }else{
       var types=1;
   }
   
   if(tempsen1=='0' && tempsen2=="0" && tempsen3=="0" && tempsen4=="0"){
       var tempsen=0;
   }else{
       var tempsen=1;
   }
    /*if(device==2 && sensor==0 && types==0 && tempsen==0){
         alert("Please Select advanced features");
         return false;
    }else*/
   
    if(orderdate == ''){
       alert("Please select date");
       return false;
   }else if(device==""){
       alert("Please select device type");
       return false;
   }else if(ordercustomer=='0'){
       alert("Please select customer");
       return false;
   }else if(shownametext=="" && ordercustomer=='-1'){
           alert("Please enter new customer name");
           return false;
   }else if(deviceqty=="" || deviceqty=='0'){
       alert("Please enter device quantity.");
       return false;
   }else if(device==1 && (acsensor==1 || gensetsensor==1 || doorsensor==1 || fuelsensor==1)){
       alert("Please select advanced device type");
       return false;
   }else if(custname==""){
       alert("Please enter customer name");
       return false;
   }else if(contactno==""){
       alert("Please enter contact Number");
       return false;
   }else{
       $("#orderform").submit();
   }
}

function onlyNos(e, t) {
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    catch (err) {
        alert(err.Description);
    }
} 




</script>   
<?php
include("footer.php");
?>

 