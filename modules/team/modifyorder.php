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

$orderid = $_REQUEST["oid"];
if($orderid=="" || $orderid=="0"){
    header("location:orders.php");
}else{
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();
$SQL = sprintf("SELECT customerno,customercompany FROM ".DB_PARENT.".customer");
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



$sql = sprintf("select * from ".DB_PARENT.".new_sales where orderid =".$orderid);
$db->executeQuery($sql);
$editdatadisplay = Array();
if ($db->get_rowCount() > 0){
    while ($row = $db->get_nextRow())
    {
        $editdata = new testing();
        $editdata->orderid = $row["orderid"];
        $orderdate1 = $row["orderdate"];
        $editdata->orderdate2 = date('d-m-Y', strtotime($orderdate1));
        $editdata->teamid = $row["teamid"];
        $editdata->customerno = $row["customerno"];
        $editdata->new_customer = $row["new_customer"];
        $editdata->deviceqty = $row["deviceqty"];
        $editdata->installdeviceqty = $row["install_device_qty"];
        $editdata->devicetype = $row["devicetype"];
        $editdata->contactno = $row["contactno"];
        $editdata->contact_person = $row["contact_person"];
        $editdata->remark = $row["remark"];
        $editdata->acsensor = $row["acsensor"];
        $editdata->fuelsensor = $row["fuelsensor"];
        $editdata->gensetsensor = $row["gensetsensor"];
        $editdata->doorsensor = $row["doorsensor"];
        $editdata->tempsen = $row["tempsen"];
        $editdata->is_panic = $row["is_panic"];
        $editdata->is_buzzer = $row["is_buzzer"];
        $editdata->is_mobiliser = $row["is_mobiliser"];
        $editdata->is_portable = $row["is_portable"];
        $editdata->is_twowaycom = $row["is_twowaycom"];
        
        $editdatadisplay[] = $editdata;        
        //$analog = $editdata->tempsen1;
        //$analog2 = $editdata->tempsen2;
    }    
}
$message="";
$teamid = GetLoggedInUserId();
///edit order 
if(isset($_POST["oesubmit"]))
{
    $teamid = GetLoggedInUserId();
    $orderidh = $_POST['orderid'];
    $orderdate1 = $_POST["orderdate"];
    $orderdate = date('Y-m-d', strtotime($orderdate1));
    $ordercustomer =$_POST["ordercustomer"];
    $new_customer = $_POST["shownametext"];
    $deviceqty = $_POST["deviceqty"];
    $installdevice_qty= $_POST["installdeviceqty"];
    $totaldevicehide = $_POST["totaldevicehide"];
    $devicetype = $_POST["device"];
    $custname = $_POST["custname"];
    $contactno = $_POST["contactno"];
    $remark = $_POST["remark"];
    $acsensor = $_POST["acsensor"];
    $fuelsensor = $_POST["fuelsensor"];
    $gensetsensor = $_POST["gensetsensor"];
    $doorsensor = $_POST["doorsensor"];
    $tempsen = $_POST["tempsen"];
    $panic = $_POST["panic"];
    $buzzer = $_POST["buzzer"];
    $immobilizer = $_POST["immobilizer"];
    $portable= $_POST["portable"];
    $twowaycom = $_POST["twowaycom"];
    $type_value = 0;
    $editaction = $_POST["editaction"];
    $change = $_POST["change"];
    
    
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
    
    if(isset($_POST["editaction"]) && $_POST["editaction"]=="delete")
    {
    // Delete, OK..
        $sql = sprintf("DELETE FROM ".DB_PARENT.".`new_sales` WHERE orderid=%d LIMIT 1",$orderidh);
        $db->executeQuery($sql);
         header("Location: orders.php");
        exit;
    }
    if($change=='0'){
        $message = "Please select change option";
    }
    if($change=="instdeviceqty"){
        if($totaldevicehide < $installdevice_qty){
            $message = "Install device qty should not be greater than totaldevice qty";
        }
    }
    
    if($orderdate==""){
        $message = "Please select date";
    }elseif ($ordercustomer=='0'){
        $message ="Please select customer";
    }elseif($deviceqty==""||$deviceqty=="0"){
        $message ="Please enter device quantity";
    }elseif($custname==""){
        $message ="Please filled contact person name";
    }elseif($contactno==""){
        $message ="Please filled contact number";
    }elseif($remark==""){
        $message = "Please filled remark";
    }
    
    if($message=="")
    {
        switch($change)
        {
            case 'odate':
            {
                $sql = sprintf("UPDATE ".DB_PARENT.".`new_sales` SET `orderdate` ='%s' WHERE orderid=%d LIMIT 1",$orderdate,$orderidh);
                        $db->executeQuery($sql);            
                        header("Location: orders.php");
            }
            case 'customerid':
            {
               if($ordercustomer=='-1'){
                    $sql = sprintf("UPDATE ".DB_PARENT.".`new_sales` SET `customerno`=".$ordercustomer.",`rel_manager`='".$rel_manager."' ,`new_customer`='".$new_customer."' WHERE orderid=".$orderidh." LIMIT 1");
                    $db->executeQuery($sql);            
                    header("Location: orders.php");
                }
                else
                {
                    $sql = sprintf("UPDATE ".DB_PARENT.".`new_sales` SET `customerno` ='%d' WHERE orderid=%d LIMIT 1",$ordercustomer,$orderidh);
                    $db->executeQuery($sql);            
                    header("Location: orders.php");
                }
            }
            case 'deviceqty':
            {
                $sql = sprintf("UPDATE ".DB_PARENT.".`new_sales` SET `deviceqty` ='%d' WHERE orderid=%d LIMIT 1",$deviceqty,$orderidh);
                        $db->executeQuery($sql);            
                        header("Location: orders.php");
            }
            case 'instdeviceqty':
            {
                $sql = sprintf("UPDATE ".DB_PARENT.".`new_sales` SET `install_device_qty` ='%d' WHERE orderid=%d LIMIT 1",$installdevice_qty,$orderidh);
                        $db->executeQuery($sql);            
                        header("Location: orders.php");
                
            }    
            case 'custname':
            {
                $sql = sprintf("UPDATE ".DB_PARENT.".`new_sales` SET `contact_person` ='%s' WHERE orderid=%d LIMIT 1",$custname,$orderidh);
                        $db->executeQuery($sql);            
                        header("Location: orders.php");
            }
            case 'contactno':
            {
                $sql = sprintf("UPDATE ".DB_PARENT.".`new_sales` SET `contactno` ='%d' WHERE orderid=%d LIMIT 1",$contactno,$orderidh);
                
                      $db->executeQuery($sql);            
                      header("Location: orders.php");
            }
            case 'remark':
            {
                $sql = sprintf("UPDATE ".DB_PARENT.".`new_sales` SET `remark` ='%s' WHERE orderid=%d LIMIT 1",$remark,$orderidh);
                        $db->executeQuery($sql);            
                        header("Location: orders.php");
            }
            case 'devicetype':
            {
                
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
                if($devicetype == 2 && $tempsen == 1)
                 {
                     $type_value = $type_value + 8;
                 }
                 if($devicetype == 2 && $tempsen == 2)
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
                if($devicetype == 2 && $fuelsensor == 4){
                    $type_value = $type_value + 1024;
                }
                if($devicetype == 2 && $tempsen == 3 ){
                    $type_value = $type_value + 2048;
                }
                if($devicetype == 2 && $tempsen == 4 ){
                $type_value = $type_value + 4096;
                }
                
                if($devicetype==2 && $acsensor=='1'){
                    $acs =1;
                }
                else
                {
                    $acs =0;
                }
                if($devicetype==2  && $gensetsensor=='2'){
                    $gss =1;
                }
                else
                {
                    $gss =0;
                }
                if($devicetype==2  && $doorsensor=='3'){
                    $dos =1;
                }
                else
                {
                    $dos =0;
                }

                if($devicetype==2 && $fuelsensor=='4'){
                    $fs =1;
                }
                else
                {
                    $fs =0;
                }
                
                // SET Unit Type Value 
                  $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET type_value='%s' WHERE orderid=%d", $type_value,$orderidh);
                  $db->executeQuery($SQL);
                  // Panic
                  if($panic == 1)
                  {
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_panic=1 WHERE orderid= $orderidh");
                      $db->executeQuery($SQL);
                  }
                  else
                  {
                       $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_panic=0 WHERE orderid= $orderidh");
                      $db->executeQuery($SQL);
                  }
                  // Buzzer
                  if($buzzer == 1)
                  {
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_buzzer=1 WHERE orderid= $orderidh");
                      $db->executeQuery($SQL);
                  }else{
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_buzzer=0 WHERE orderid= $orderidh");
                      $db->executeQuery($SQL);
                  }

                  // Immobilizer
                  if($immobilizer == 1)
                  {
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_mobiliser=1 WHERE orderid= $orderidh");
                      $db->executeQuery($SQL);
                  }else{
                       $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_mobiliser=0 WHERE orderid= $orderidh");
                      $db->executeQuery($SQL);
                  }
                  
                  // Portable
                  if($portable == 1)
                  {
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_portable=1 WHERE orderid= $orderidh");
                      $db->executeQuery($SQL);
                  }else{
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_portable=0 WHERE orderid= $orderidh");
                      $db->executeQuery($SQL);
                  }
                    // Two way comm
                  if($twowaycom == 1)
                  {
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_twowaycom=1 WHERE orderid= $orderidh");
                      $db->executeQuery($SQL);
                  }else{
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET is_twowaycom=0 WHERE orderid= $orderidh");
                      $db->executeQuery($SQL);
                  }
                
                 // Temperature Sensor 2
                  if($devicetype==2 && $tempsen == 2)
                  {
                    $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET tempsen=2 where orderid='%s'",$orderidh);
                    $db->executeQuery($SQL);
                  }
                 
                  // Temperature Sensor 1
                  if($devicetype==2 && $tempsen == 1)
                  {
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET tempsen=1 where orderid='%s'",$orderidh);
                      $db->executeQuery($SQL);
                  }
                  // Temperature Sensor 3
                  if($devicetype==2 && $tempsen == 3)
                  {
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET tempsen=3 where orderid='%s'",$orderidh);
                      $db->executeQuery($SQL);
                  }
                  // Temperature Sensor 4
                  if($devicetype==2 && $tempsen == 4)
                  {
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET tempsen=4 where orderid='%s'",$orderidh);
                      $db->executeQuery($SQL);
                  }
                  if($devicetype==2 && ($tempsen==""|| $tempsen=='0')){
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET tempsen=0 where orderid='%s'",$orderidh);
                      $db->executeQuery($SQL);
                  }
                  
                  if($devicetype==1 && $tempsen!=""){
                      $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET tempsen=0 where orderid='%s'",$orderidh);
                      $db->executeQuery($SQL);
                  }
                    $sql = sprintf("UPDATE ".DB_PARENT.".`new_sales` SET `devicetype` =".$devicetype.", `acsensor`=".$acs.", `gensetsensor`=".$gss.", `doorsensor`=".$dos.", `fuelsensor`=".$fs." WHERE orderid=".$orderidh);
                    $db->executeQuery($sql);      
                    header("Location: orders.php");
                
            }
        }
    }
}

   

include("header.php");

?>
<div class="panel">
<div class="paneltitle" align="center">Update Order information</div>
<div class="panelcontents">
    <form name='modifyorderform' id='modifyorderform' method='POST' onsubmit="ValidateForm(); return false;"   enctype="multipart/form-data">
    <span style="color:red; font-size:12px;"><?php if(!empty($message)){ echo $message;}?></span>
        <table>
            <tr>
                <td><label>Change</label></td>
                <td>
                    <select name="change" id="change">
                        <option value="0">Change</option>
                        <option value="odate">Order Date</option>
                        <option value="customerid">Customer</option>
                        <option value="deviceqty">Device Quantity</option>
                        <option value="instdeviceqty">Install Device Qty</option>
                        <option value="devicetype">Device Type</option>
                        <option value="custname">Contact Person</option>
                        <option value="contactno">Contact No.</option>
                        <option value="remark">Remark</option>
                    </select>
                    
                </td>
            </tr>    
        <tr><td><label>Order Date </label></td><td><input type='text' name='orderdate' id='orderdate' value="<?php echo $editdata->orderdate2;?>"/></td></tr>
                <tr><td><label>Customer </label></td>
                    <td>
                     <select name="ordercustomer" id="ordercustomer" onchange="show_newcomp()" style='width: 200px;'>
                                <option value="0">Select a Customer</option>     
                                <option value="-1"  <?php if($editdata->customerno =='-1'){echo "selected";}?> >New Customer</option>
                                    <?php
                                    foreach($customer as $thiscustomer)
                                    {
                                        ?>
                                        <option value="<?php echo($thiscustomer->customerno); ?>" <?php if($editdata->customerno == $thiscustomer->customerno){echo "selected";}?> ><?php echo($thiscustomer->customername); ?></option>
                                        <?php
                                    }
                                    ?>
                            </select>
                                <div id="showtextname" <?php if($editdata->customerno =='-1') { echo "style='display:block';";}else{ echo "style='display:none';"; }?>>
                                    <input type="text" name="shownametext" id="shownametext" value="<?php echo $editdata->new_customer;?>"/>
                                </div>
                    </td>
                </tr>
                <tr><td><label>Total Device Qty</label></td><td><input type="text" name="deviceqty" id="deviceqty"  onkeypress="return onlyNos(event,this);" value="<?php  echo $editdata->deviceqty;?>"><input type="hidden" name="totaldevicehide" id="totaldevicehide" value="<?php echo $editdata->deviceqty;?>" ></td></tr>
                <tr><td><label>Total Device Installed Qty</label></td><td><input type="text" name="installdeviceqty" id="installdeviceqty"  onkeypress="return onlyNos(event,this);" value="<?php echo $editdata->installdeviceqty;?>"></td></tr>
         <tr>
            <td><label> Device Type</label></td>
            <td> <input name="device" id="device1" type="radio" value="1" <?php if($editdata->devicetype =='1'){echo "checked";}?>  /> &nbsp;Basic &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="device" id="device2" type="radio" value="2" <?php if($editdata->devicetype =='2'){echo "checked";}?>  /> &nbsp; Advanced &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
         <tr class="adv0">
            <td>Sensor</td>
            <td>
                
                <input name="acsensor" id="acsensor" type="checkbox" value="1" <?php if($editdata->acsensor==1){echo "checked";}?> />  &nbsp;  Ac Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                <input name="gensetsensor" id="gensetsensor" type="checkbox" <?php if($editdata->gensetsensor==1){echo "checked";}?> value="2" />  &nbsp;  Genset Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                <input name="doorsensor" id="doorsensor" type="checkbox" <?php if($editdata->doorsensor==1){echo "checked";}?>  value="3" />  &nbsp;  Door Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                <input name="fuelsensor" id="fuelsensor" type="checkbox" <?php if($editdata->fuelsensor==1){echo "checked";}?>  value="4" />  &nbsp;  Fuel Sensor   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                
            </td>
        </tr>
       
        <tr class="adv0">
            <td>Temperature </td>
            <td> 
                <input name="tempsen" id="tempsen" type="radio" value="0" <?php if($editdata->tempsen=='0'){ echo"checked"; }?>> None
                <input name="tempsen" id="tempsen1" type="radio" value="1" <?php if($editdata->tempsen=='1'){ echo"checked"; }?>/> 1 &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tempsen" id="tempsen2" type="radio" value="2" <?php if($editdata->tempsen=='2'){ echo"checked"; }?>/> 2 &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tempsen" id="tempsen3" type="radio" value="3" <?php if($editdata->tempsen=='3'){ echo"checked"; }?>/> 3 &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="tempsen" id="tempsen4" type="radio" value="4" <?php if($editdata->tempsen=='4'){ echo"checked"; }?>/> 4 &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr class="adv0">
            <td></td>
            <td>
                <input name="panic" id="panic" type="checkbox" value="1" <?php if($editdata->is_panic=='1'){echo"checked"; }?> /> Panic  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="buzzer" id="buzzer" type="checkbox" value="1" <?php if($editdata->is_buzzer=='1'){echo"checked"; }?> /> Buzzer  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="immobilizer" id="immobilizer" type="checkbox" value="1" <?php if($editdata->is_mobiliser=='1'){echo"checked"; }?> /> Immobilizer  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="portable" id="portable" type="checkbox" value="1" <?php if($editdata->is_portable=='1'){echo"checked"; }?> /> Portable  &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="twowaycom" id="twowaycom" type="checkbox" value="1" <?php if($editdata->is_twowaycom=='1'){echo"checked"; }?> /> Two way Communication  &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr><td><label>Contact Person Name </label></td><td><input type='text' name='custname' id='custname' value="<?php echo $editdata->contact_person;?>"/></td></tr>
        <tr><td><label>Contact Number </label></td><td><input type='text' name='contactno' id='contactno' value="<?php echo $editdata->contactno;?>" onkeypress="return onlyNos(event,this);"/></td></tr>
        <tr><td><label>Remark </label></td>
            <td>
                <textarea name='remark' id='remark'><?php  echo $editdata->remark;?></textarea>
                <input type="hidden" id="orderid" name="orderid" value="<?php echo $editdata->orderid;?>">
            </td></tr>
        <tr><td><label>Action :</label></td><td><input type="radio" name="editaction" value="edit" checked>Edit <input type="radio" name="editaction" value="delete">Delete</td></tr>
        <tr><td>&nbsp;</td><td><input type='submit' name='oesubmit' value='Update Order'/> </td></tr>
    </table>
</form>
</div>
</div>

<br/>

<br/>

<?php
}
include("footer.php");
?>

<script type="text/javascript">

$( document ).ready(function() {
   // $("#showtextname").css("display",'none');
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
function show_newcomp(){
    
    var ordercustomer = $("#ordercustomer").val();
    if(ordercustomer=='-1')
    {
        $("#showtextname").css("display",'block');
    }else{
        $("#showtextname").css("display",'none');
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
    if( device == 1){
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


function ValidateForm(){
   var change = $("#change").val(); 
   var ordercustomer = $("#ordercustomer").val();
   var custname =  $("#custname").val();
   var contactno = $("#contactno").val();
   var orderdate = $("#orderdate").val();
   var devicetype= $('input:radio[name=device]:checked').val();
   //var advance = $('input:checkbox[name=device2]:checked').val()?1:0;
   var tempsen = $('input:radio[name=tempsen]:checked').val();
   var acsensor = $('input:checkbox[name=acsensor]:checked').val()?1:0;
   var tempsen = $('input:radio[name=tempsen1]:checked').val();
   var fuelsensor = $('input:checkbox[name=fuelsensor]:checked').val()?1:0;
   var gensetsensor = $('input:checkbox[name=gensetsensor]:checked').val()?1:0;
   var doorsensor = $('input:checkbox[name=doorsensor]:checked').val()?1:0;
   var panic = $('input:checkbox[name=panic]:checked').val();
   var buzzer =$('input:checkbox[name=buzzer]:checked').val();
   var immobilizer = $('input:checkbox[name=immobilizer]:checked').val();
   var twowaycom = $('input:checkbox[name=twowaycom]:checkbox').val();
   var portable = $('input:checkbox[name=portable]:checkbox').val();
   var deviceqty = $("#deviceqty").val();
   var installdeviceqty = $("#installdeviceqty").val();
   
    if( advance=='1'&& acsensor!='0' && gensetsensor!="0" && doorsensor!="0" && fuelsensor!="0"){
      var sensor=1; 
   }else{
       var sensor=0;
   }
    
    if(devicetype==""){
       alert("Please select device type");
       return false;
   }else if(change=='0'){
     alert("Please select Change option");   
     return false;
    }else if(orderdate ==""){
       alert("Please select date");
       return false;
    }else if(ordercustomer=='0'){
       alert("Please select customer");
       return false;
   }else if(change=="instdeviceqty" && deviceqty < installdeviceqty){
       alert("Install device qty not greater than total device qty");
       return false;
   }else if(custname==""){
       alert("Please enter customer name");
       return false;
   }else if(contactno==""){
       alert("Please enter contact Number");
       return false;
   }else{
       $("#modifyorderform").submit();
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