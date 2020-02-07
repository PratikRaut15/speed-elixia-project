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
    header("location:crm_orders.php");
}else{
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();

$sql = sprintf("select * from ".DB_PARENT.".new_sales where orderid=".$orderid);
$db->executeQuery($sql);
if($db->get_rowCount()>0)
{
    while($row = $db->get_nextRow())
    {
        $orderid = $row["orderid"];
        $orderdate = $row["orderdate"];
        $customerno = $row["customerno"];
        $relmanager = $row["rel_manager"];
        $newcustomer = $row["new_customer"];
        $deviceqty = $row["deviceqty"];
        $install_device_qty = $row["install_device_qty"];
        $devicetype=$row["devicetype"];
        
    }
}
$total_pending = $deviceqty-$install_device_qty;
function get_customername($customerno, $orderid){
$db = new DatabaseManager();
if($customerno=='-1')
{
    $SQL = sprintf("select new_customer from ".DB_PARENT.".new_sales where orderid=".$orderid);
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow())   
    {
        $customercompany = $row["new_customer"]."(New Customer)";
    }  
}
else
{
$SQL = sprintf("select customercompany from ".DB_PARENT.".customer where customerno=".$customerno);
$db->executeQuery($SQL);
 while ($row = $db->get_nextRow())   
 {
     $customercompany = $row["customercompany"];
 }
} 
return $customercompany;
}

$custname = get_customername($customerno, $orderid);

if(isset($_POST["installdevice"])){
    $pending_install = $_POST["pending_install"];
    $deviceqty = $_POST["deviceqty"];
    if(empty($pending_install)){
        $message="Please enter total installed number.";
    }elseif($pending_install >$deviceqty) 
    {
        $message="Installed count should not be greater than Total installations.";
    }
    else
    {
        $SQL = sprintf("UPDATE ".DB_PARENT.".new_sales SET install_device_qty ='%d' WHERE orderid=%d",$pending_install,$orderid);
        $db->executeQuery($SQL);
        header("location:crm_orders.php");
    }
        
}
include("header.php");
?>
<div class="panel">
<div class="paneltitle" align="center">Update Installation Unit</div>
<div class="panelcontents">
    <form name="updateinstallunits" id="updateinstallunits" method="POST">
       <table width="450px">
           <tr><td><b>Customer :</b></td><td><?php echo $custname;?></td></tr>
           <tr><td><b>Order Date :</b></td><td><?php echo $orderdate;?></td></tr>
           <tr><td><b>Total Installation :</b></td><td><?php echo $deviceqty;?> <input type="hidden" name="deviceqty" id="deviceqty" value="<?php echo $deviceqty;?>"></td></tr>
           <tr><td><b>Pending Installation :</b></td><td><?php echo $total_pending; ?></td></tr>
           <tr><td><b> Total Installed  :</b></td><td><input type="text" name="pending_install" id="pending_install" value="<?php echo $install_device_qty;?>" onkeypress="return onlyNos(event,this);"/></td></tr>
           <tr><td>&nbsp;</td><td><input type="submit" name="installdevice" id="installdevice" value="Update Install Device"></td></tr>
       </table>
   </form>
</div>
</div>
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
   var devicetype = $('input:radio[name=device]:checked').val();
   var tempsen = $('input:radio[name=tempsen]:checked').val();
   var sensor = $("#sensor").val();
   var pdigital = $('input:checkbox[name=pdigital]:checked').val();
   var pdigitalopp = $('input:checkbox[name=pdigitalopp]:checked').val();
   var panic = $('input:checkbox[name=panic]:checked').val();
   var buzzer =$('input:checkbox[name=buzzer]:checked').val();
   var immobilizer = $('input:checkbox[name=immobilizer]:checked').val();
   var deviceqty = $("#deviceqty").val();
   var installdeviceqty = $("#installdeviceqty").val();
   
  
   
    if(change=='0'){
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
   }else if(devicetype == 2 && sensor==0 && panic!=1 && buzzer !=1 && immobilizer != 1 && (tempsen==1  || tempsen==2 )){
       alert("Please Select Advanced  Features");
       return false;
   }else if(devicetype == 2 && sensor!=0 && ( pdigital != 1 && pdigitalopp != 1)){
       alert("Please Select From Digital Or Is Opposite");
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