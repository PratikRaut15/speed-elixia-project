<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
$uid = GetSafeValueString(isset($_GET["sid"])? $_GET["sid"]:$_POST["sid"], "long");

if (IsDealer()) {
	$msg = "<P>You are an authorized user</p>";
} else {
	header( "Location: index.php");
	exit;
}

// See if we need to save a new one.
$message="";
$db = new DatabaseManager();

if(isset($_POST["editaction"]) && $_POST["editaction"]=="delete")
{
    $unitid = $_POST["unitid"];
    $dealerid = $_POST["dealerid"];
    // Delete, OK..
        $sql = sprintf("UPDATE `unit` SET `teamid` ='%d' WHERE uid=%d LIMIT 1",$dealerid,$unitid);
        $db->executeQuery($sql);  
        
        $sql = sprintf("DELETE FROM ".DB_PARENT.".`retailer_details` WHERE uid=%d LIMIT 1",$uid);
        $db->executeQuery($sql);
        header("Location: retailers.php");
        exit;
}

if(isset($_POST["save"]))
{
   
    $tname = GetSafeValueString($_POST["tname"], "string");
    $tphone = GetSafeValueString($_POST["tphone"], "string");
    $temail = GetSafeValueString($_POST["temail"], "string");
    $tvehicleid = GetSafeValueString($_POST["tvehicleid"], "string");
    $toverspeed = GetSafeValueString($_POST["toverspeed"], "string");
    $change = GetSafeValueString($_POST["change"], "string");    
    $address = GetSafeValueString($_POST["addr"], "string");   
    
    // Change the password if it's set.
    
    
switch($change)
{
    case 'name':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`retailer_details` SET `name` ='%s' WHERE uid=%d LIMIT 1",$tname,$uid);
                $db->executeQuery($sql);            
                header("Location: retailers.php");
    }
    case 'email':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`retailer_details` SET `email` = '%s' WHERE uid=%d LIMIT 1",$temail,$uid);
                $db->executeQuery($sql);            
                header("Location: retailers.php");
     }
     case 'phone':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`retailer_details` SET `phone` ='%s' WHERE uid=%d LIMIT 1",$tphone,$uid);
                $db->executeQuery($sql);            
                header("Location: retailers.php");
    }
    case 'address':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`retailer_details` SET `address` ='%s' WHERE uid=%d LIMIT 1",$address,$uid);
                $db->executeQuery($sql);            
                header("Location: retailers.php");
    }
   
      case 'vehicleno':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`retailer_details` SET `vehiclenumber` = '%s' WHERE uid=%d LIMIT 1",$tvehicleid,$uid);
                $db->executeQuery($sql);            
                header("Location: retailers.php");
     }
    
    case 'overspeed':
    {
        $sql = sprintf("UPDATE ".DB_PARENT.".`retailer_details` SET `overspeed` ='%s' WHERE uid=%d LIMIT 1",$toverspeed,$uid);
                $db->executeQuery($sql);            
                header("Location: retailers.php");
    }
 }
 
    
}
$sql = sprintf("Select *
from ".DB_PARENT.".`retailer_details`
where uid='%d'",$uid);
$db->executeQuery($sql);

if($db->get_rowCount()>0)
{
    while($row = $db->get_nextRow())
    {
        $tname = $row["name"];
        $temail = $row["email"];
        $tphone = $row["phone"];
        $tvehicleno = $row["vehiclenumber"];
        $tdid = $row["distributor_id"];
        $toverspeed = $row["overspeed"];
        $address = $row["address"];
        $unitid = $row["unitid"];
        $dealer_id =$row["dealer_id"];
    }
}
else
{
    header("Location: retailers.php");
    exit;
}

include("header.php");
?>
<script>
    function deleteteam()
    {
        if(confirm("Delete this Customer?"))
        {
            $("form1").submit();
        }
    }
</script>
<div class="panel">
<div class="paneltitle" align="center">Update Customer </div>
<div class="panelcontents">
    <form method="post" id="form1" name="form1" onsubmit="return ValidateForm(); return false;" action="modifyretailer.php">
<?php if(!empty($message)){
    echo"<span style='color:red; font-size:10px;'>".$message."</span>";
} ?>
<input type="hidden" name = "sid" value="<?php echo($uid) ?>"/>
<table width="100%">
    <tr>
        <td>Change</td>
        <td><select name ="change">
                <option value ="name">Name</option>
                <option value ="email">Email</option>
                <option value ="phone">Phone</option>
                <option value ="address">Address</option>
                <option value ="vehicleno">Vehicle No.</option>
                <option value ="overspeed">Over Speed</option>
            </select>
        </td>
    </tr>
    <tr>
         <td>Name <span style="color:red;">*</span></td><td><input id="tname" name = "tname" type="text" value="<?php echo $tname;?>"></td>
    </tr>
    <tr>
        <td>Email <span style="color:red;">*</span></td><td><input id="temail" name = "temail" type="text"value="<?php echo $temail;?>"></td>
    </tr>
    <tr>
        <td>Mobile No. <span style="color:red;">*</span></td><td><input id="tphone" maxlength="10" name = "tphone" type="text" value="<?php echo $tphone; ?>"></td>
    </tr>
     <tr>
        <td>Address </td><td><textarea style="width:150px;" name="addr" id="addr"><?php echo $address;?></textarea></td>
    </tr>
    <tr>
        <td>Vehicle No. <span style="color:red;">*</span></td><td><input id="vid" name = "tvehicleid" type="text" value="<?php echo $tvehicleno;?>"></td>
    </tr>
    <tr>
        <td>Over Speed</td><td><input id="vid" name = "toverspeed" type="text" value="<?php echo $toverspeed;?>">
            <input type="hidden" name="unitid" id="unitid" value="<?php echo $unitid;?>"/>
            <input type="hidden" name="dealerid" id="dealerid" value="<?php echo $dealer_id;?>"/>
            
        </td>
    </tr>
</table>
<input type="submit" name="save" value="Save Customer"/>
Action</td><td><input type="radio" name="editaction" value="edit" checked>Edit <input type="radio" name="editaction" value="delete">Delete

</form>
</div>
</div>

<?php 
include("footer.php");
?>
<script>
function ValidateForm(){
         var tname = $("#tname").val();
         var uready = $("#uready").val();
         var emailid = $("#temail").val();
         var tphone =$("#tphone").val();
         var vid = $("#vid").val();
        // var addr = $("#addr").val();
    if(uready == 0){
      alert("Please select Unit number.");
      return false;
  }else if(tname==""){
      alert("Please enter Name.");
      return false;
  }else if(emailid==""){
      alert("Please enter email id");
      return false;
  }else if(tphone==""){
      alert("Please enter your mobile no.");
      return false;
  }else if(vid==""){
       alert("Please enter vehicle number");
          return false;
  }
//  else if(addr==""){
//      alert("Please enter your address.");
//      return false;
//  }
  else
        if(emailid!=""){
      var errmsg = checkEmail(emailid);
      if(errmsg==2){
          alert("Enter valid email id");
          return false;
      }
      if(errmsg==1){
          return true;
      }
      return false;
  }else {
       $("#form1").submit();
   }
  }
  
  
   function checkEmail(email){	
    var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
    var msg ;
        if(pattern.test(email)){         
	 msg=1;
         }else{   
            msg=2;
        }
        return msg;
}

</script>
