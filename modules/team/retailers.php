<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
    include_once("../../lib/system/Date.php");


// See if we need to save a new one.
class retailers{
    
}
date_default_timezone_set("Asia/Calcutta");                             
$today = date("Y-m-d H:i:s");
if(IsDealer())
{
$message="";
 $db = new DatabaseManager();
 $tname=""; 
 $tphone="";
 $temail="";
 $tvehicleid="";
 $address="";
 $uready="0";
if(isset($_POST["tname"]) && isset($_POST["uready"]) && isset($_POST["temail"]) && isset($_POST["tphone"]))
{
    $uready = GetSafeValueString($_POST["uready"], "string");
    $tname = GetSafeValueString($_POST["tname"], "string");
    $tphone = GetSafeValueString($_POST["tphone"], "string");
    $temail = GetSafeValueString($_POST["temail"], "string");
    $tvehicleid = GetSafeValueString($_POST["tvehicleid"], "string");
    $toverspeed1 = GetSafeValueString($_POST["over_sp"], "string");
    $address = GetSafeValueString($_POST["addr"], "string");
    $installdate1 = $_POST["installdate"];
    $installdate = date("Y-m-d",strtotime($installdate1));
    $today_date = date("Y-m-d");    
    $tomorrow = date("Y-m-d", time()+86400); 
    $day_after_tomorrow =  date('Y-m-d', strtotime('tomorrow + 1 day'));
    if($toverspeed1=="other"){
         $toverspeed = GetSafeValueString($_POST["toverspeed"], "string");
    }elseif(empty($toverspeed1)){
            $toverspeed = 10;
    }else{
         $toverspeed = $toverspeed1;
    }
    $dist_id = $_SESSION['sessionteamid'];
    $sql = sprintf("Select * from ".DB_PARENT.".`retailer_details` where email='%s'",$temail);
    $db->executeQuery($sql);
    
    if($db->get_affectedRows() >0 )
    {
        $message="This Email id is already taken, please choose another.";
    //}elseif(empty($tname)&&empty($temail)&&empty($tphone)&&empty($tvehicleid)&&empty($address)){
    }elseif(empty($tname)&&empty($temail)&&empty($tphone)&&empty($tvehicleid)){
        $message="Please enter mandatory fields.";
    }elseif($installdate == $today_date){
           $message="Please select date day after tommorow.";
    }elseif($installdate==$tomorrow){
           $message="Please select date day after tommorow.";
    }elseif($installdate < $day_after_tomorrow){
           $message="Please select date day after tommorow.";
    }elseif($uready==0){
        $message="Please select Unit No.";
    }else{
        $sql_unitno=  mysql_query("select `uid`,`teamid` from `unit` where uid=$uready AND `teamid`!=-1");
        $unit = mysql_fetch_array($sql_unitno);
        $teamid = $unit['teamid'];
        $uid = $unit['uid'];
        
    if($teamid==-1){
        $message="Already assigned allot this device";
    }
    $sql = sprintf("INSERT INTO ".DB_PARENT.".`retailer_details` (
    `name` ,
    `email` ,
    `phone` ,
    `address`,
    `vehiclenumber`,
    `installdate`,
    `unitid`,    
    `dealer_id` ,
    `overspeed`,
    `timestamp`
    )
    VALUES (
     '%s', '%s', '%d','%s','%s','%s','%s', '%s', '%s','%s');",$tname,$temail,$tphone,$address,$tvehicleid,$installdate,$uready,$dist_id,$toverspeed, Sanitise::DateTime($today));
        $db->executeQuery($sql);
        $sql = sprintf("UPDATE `unit` SET `teamid` ='%d' WHERE uid=%d LIMIT 1",-1,$uid);
        $db->executeQuery($sql);  
    }
}
 
 $sqlunit = sprintf("SELECT unit.unitno, unit.uid,trans_status.status FROM unit INNER JOIN ".DB_PARENT.".trans_status ON trans_status.id = unit.trans_statusid WHERE unit.teamid !=-1 AND unit.teamid=".$_SESSION['sessionteamid']." AND trans_statusid IN (18)");
     $db->executeQuery($sqlunit);
    $readyunits = Array();
    if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $unit = new retailers();
        $unit->unitno = $row["unitno"]."[ ".$row["status"]." ]";
        $unit->uid = $row["uid"];        
        $readyunits[] = $unit;        
    }    
}

  $SQL = sprintf("select rd.name, rd.phone, rd.address, rd.email, rd.vehiclenumber,rd.installdate, rd.unitid, rd.unitid, rd.overspeed, rd.uid, u.unitno from ".DB_PARENT.".retailer_details as rd INNER JOIN unit as u ON u.uid = rd.unitid where rd.dealer_id =".$_SESSION['sessionteamid']);
 
$db->executeQuery($SQL);

$dg = new datagrid( $db->getQueryResult());
$dg->AddAction("View/Edit", "../../images/edit.png", "modifyretailer.php?sid=%d");
$dg->AddColumn("Name", "name");
$dg->AddColumn("Email", "email");
$dg->AddColumn("Phone", "phone");
$dg->AddColumn("Address", "address");
$dg->AddColumn("Vehicle No.", "vehiclenumber");
$dg->AddColumn("Install Date.", "installdate");
$dg->AddColumn("Unit No.", "unitno");
$dg->AddColumn("OverSpeed", "overspeed");
$dg->SetNoDataMessage("No Customer");
$dg->AddIdColumn("uid");
include("header.php");

?>
<div class="panel">
   
<div class="paneltitle" align="center">Add New Customer</div>
<div class="panelcontents">
    <form name="myform" id="myform" method="post" onsubmit="return ValidateForm(); return false;" action="retailers.php">
<?php if(!empty($message)){ echo("<span style='margin-left:50px; color:red; font-size:12px;'>".$message."</span>");} ?>
<table width="100%">
    <tr>
        <td>Unit No.<span style="color:red;">*</span> </td>
        <td><select name="uready" id="uready">
                    <option value="0">No Unit</option>                                
                <?php
                foreach($readyunits as $thisunit)
                {
                    if($thisunit->uid == $uready){
                        echo"<option value='$thisunit->uid' selected >$thisunit->unitno</option>";
                    }else{
                        echo"<option value='$thisunit->uid'>$thisunit->unitno</option>";
                    }
                }
                ?>
            </select>
           
        </td>
    </tr>
    <tr>
        <td>Name <span style="color:red;">*</span></td><td><input id="tname" name = "tname" type="text" value="<?php echo $tname;?>"></td>
    </tr>
    <tr>
        <td>Email <span style="color:red;">*</span></td><td><input id="temail" name = "temail" value="<?php echo $temail;?>" type="text"></td>
    </tr>
    <tr>
        <td>Mobile Number <span style="color:red;">*</span></td><td><input id="tphone" maxlength="10" value="<?php echo $tphone;?>" name = "tphone" onkeypress="return onlyNos(event,this);"type="text"></td>
    </tr>
    <tr>
        <td>Date of (install):</td><td><input id="installdate" placeholder='dd-mm-yyyy'  name = "installdate" type="text"></td>
    </tr>
    <tr>
        <td>Address </td><td><textarea style="width:150px;" name="addr" id="addr"><?php echo $address;?></textarea></td>
    </tr>
    <tr>
        <td>Vehicle Number<span style="color:red;">*</span></td><td><input id="vid" name = "tvehicleid" value="<?php echo $tvehicleid;?>" type="text"></td>
    </tr>
    
    <tr>
        <td>Over Speed</td>
        <td>
            <span style="float:left;"><input  type="radio" style="float:left;" name="over_sp" id="over_sp1" checked="checked" value="80"/><span style="float:left; width:25px; margin-right: 5px;"><label for="over_sp1">&nbsp;80</label></span></span>
            <span style="float:left;"><input type="radio"  style="float:left;" name="over_sp" id="over_sp2" value="100"/><span style="float:left; width:25px; margin-right: 5px;"><label for="over_sp2">&nbsp;100</label></span></span>
            <span style="float:left;"><input type="radio" style="float:left;" name="over_sp" id="over_sp3" value="120"/><span style="float:left; width:25px; margin-right: 5px;"><label for="over_sp3">&nbsp;120</label></span></span>
            <span style="float:left;"><input type="radio"  style="float:left;" name="over_sp" id="over_spother" value="other"/><span style="float:left; width:50px; margin-right: 5px;"><label for="over_spother">&nbsp;Other</label></span></span><br><input id="tooverspeed" name = "toverspeed" onkeypress="return onlyNos(event,this); style="display:none; float:left;" type="text"></td>
    </tr>
    

</table>
        <input type="submit" name="submitpros" id="submitpros" value="Save New Customer"/>
</form>
</div>
</div>
<br/>
<div class="panel">
<div class="paneltitle" align="center">Customer</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>
</div>
<br/>
<?php
include("footer.php");
}
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
    else if(emailid!=""){
        var errmsg = checkEmail(emailid);
        if(errmsg==2){
          alert("Enter valid email id");
          return false;
        }
        if(errmsg==1){
          return true;
        }
      return false;
    }
    else
    {
       $("#myform").submit();
    }
  }
   $(document).ready(function(){
       $("#tooverspeed").css("display","none");
       $("input[name='over_sp']").change(function(){
        if($(this).val() == "other")
        {
            $("#tooverspeed").css("display","block");
        }else{
            $("#tooverspeed").css("display","none");
        }
    });
    $('#installdate').datepicker({
        minDate:new Date(),
        format: "dd-mm-yyyy",
        language:  'en',
         weekStart: 1,
         todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        minView: 2,
        forceParse: 0,
        startDate:new Date()
    }); 
    
});
     
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