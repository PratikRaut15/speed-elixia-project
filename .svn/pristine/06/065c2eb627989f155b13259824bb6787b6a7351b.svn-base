<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/Date.php");


class testing{
    
}
$vid = $_REQUEST["vid"];
if(!empty($vid)){ 
    $db = new DatabaseManager();
    $sql = sprintf("select * from ".DB_PARENT.".voucher where voucherid=".$vid);
    $db->executeQuery($sql);
    while ($row = $db->get_nextRow())
    {
        $ispaid = $row["ispaid"];        
    }    
    if($ispaid=='1'){
    header("location:addvoucher.php");
     exit;
    }
    if($ispaid=='2'){
    header("location:addvoucher.php");
     exit;
    }
}
if(empty($vid)){ 
    header("location:addvoucher.php");
    exit;
}

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$claimant = GetLoggedInUserId();
$db = new DatabaseManager();
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
$SQL = sprintf("SELECT customerno, customercompany FROM ".DB_PARENT.".customer");
$db->executeQuery($SQL);
$customer = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $testing = new testing();
        $testing->customerno = $row["customerno"];
        $testing->customername = $row["customerno"]."( ".$row['customercompany']." )";
        $customer[] = $testing;        
    }    
}
$SQL = sprintf("SELECT headid, headtype FROM ".DB_PARENT.".account_head");
$db->executeQuery($SQL);
$headdata = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $testing = new testing();
        $testing->headid = $row["headid"];
        $testing->headtype = $row['headtype'];
        $headdata[] = $testing;        
    }    
}
if(isset($_POST['updatevoucher']))
{
    $remark = $_POST["remark"];
    $count = count($_POST['head']);
    $voucherdate = $_POST["voucherdate"];
    $voucherdate1 = date("Y-m-d",strtotime($voucherdate));
    if(!empty($voucherdate1) && $voucherdate1!="1970-01-01"){
        $todaydate =$voucherdate1;   
    }else{
        $todaydate =date("Y-m-d");
    }
    $claimant = GetLoggedInUserId();
    $voucherid = $_POST["voucherid"];
    $editaction = $_POST["editaction"];
    
    $db = new DatabaseManager();
    if(isset($_POST["editaction"]) && $_POST["editaction"]=="delete")
    {
    // Delete, OK..
        $sql = sprintf("DELETE FROM ".DB_PARENT.".`voucher` WHERE voucherid=%d",$voucherid);
        $db->executeQuery($sql);
         header("Location: addvoucher.php");
        exit;
    }
    
    if(isset($_POST["editaction"]) && $_POST["editaction"]=="edit")
    {
            for($i=0; $i< $count; $i++){ 
                    $vdate = $_POST['vdate'][$i];
                    if(!empty($vdate) && $vdate!="1970-01-01"){
                        $datetest =  date("Y-m-d", strtotime($vdate));
                        $todaydate1 = $datetest;
                    }else{
                        $todaydate1 = date("Y-m-d");
                    }
                if($_POST['head'][$i]!='0' && $_POST['customer'][$i]!='0' && !empty($_POST['amount'][$i])){
                    $headid = $_POST['head'][$i];
                    $customer = $_POST['customer'][$i];
                    $amount = $_POST['amount'][$i];
                    $uid = $_POST['uid'][$i];
                    if($uid!='0'){
                    $SQL = sprintf("UPDATE ".DB_PARENT.".voucher SET headid = $headid, claimdate= '$todaydate', amount= $amount, customer =$customer where uid =$uid");    
                    $db->executeQuery($SQL);      
                    }else{
                    $sql = sprintf("INSERT INTO `voucher` (
                    `voucherid`,
                    `claimant` ,
                    `claimdate` ,
                    `voucherdate`,
                    `headid` ,
                    `customer` ,
                    `amount`,
                    `remarks`
                    )
                    VALUES (
                    '%d','%d','%s','%s','%d','%d','%d','%s');",$vid,$claimant,$todaydate,$todaydate1,$_POST['head'][$i],$_POST['customer'][$i],$_POST['amount'][$i],$_POST['remark']);
                    $db->executeQuery($sql);
                    }
               }

            }
        if(!empty($remark)){
            $SQL = sprintf("UPDATE ".DB_PARENT.".voucher SET `remarks` = '".$remark."' where voucherid =$vid");    
            $db->executeQuery($SQL);  
        }
        header("location:addvoucher.php"); 
    }
}
 $sql = sprintf("SELECT *  FROM ".DB_PARENT.".`voucher` WHERE `voucherid` =".$vid);
 $db->executeQuery($sql);
 if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $testing = new testing();
        $testing->uid = $row["uid"];
        $testing->voucherid = $row["voucherid"];
        $testing->claimant = $row['claimant'];
        $claimdate = $row['claimdate'];
        $testing->voucherdate = $row['voucherdate'];
        $testing->headid = $row['headid'];
        $testing->customer = $row['customer'];
        $testing->amount = $row['amount'];
        $remarks = $row['remarks'];
        $updatedata[] = $testing;        
    }
    }
    
    include("header.php");
?>

<div class="panel">
    <div class="paneltitle" align="center">Edit vouchers</div>
    <div style="margin:10px; float:left;">
         <table>
             <tr><td><label><b>Claimant</b> : <?php echo GetLoginUser();?></label></td></tr>
         </table>
    </div>
    <div style="float:right; margin:5px;">
        <?php echo"Date:".date("d-m-Y");?>
    </div>
    <div style="clear:both;"></div>
    <hr/>
     
    <div class="panelcontents"  align="center">
       <div style="width:100%;">
          <div style="float:right;"><input type="button" onclick="addrow()" value="Add Row"></div><br/>
          <form name="addvoucherform" method="POST" onsubmit="return ValidateForm(); "> 
               <?php
              $datetest = date('d-m-Y', strtotime($claimdate));
               ?>
<!--               Voucher Date :<input id="voucherdate" placeholder='dd-mm-yyyy' value="<?php echo $datetest?>"  name = "voucherdate" type="text">  -->
           <table width="35%" border="1" id="myTable">
                <tr><th>Account Head<span style="color:red;">*</span></th><th>For Customer <span style="color:red;">*</span></th><th>Amount <span style="color:red;">*</span></th><th>Voucher Date <span style="color:red;">*</span></th></tr>
                <?php
                //for($i=0; $i< count($updatedata); $i++){
                foreach($updatedata as $row){
                    $datetest1 = date('d-m-Y', strtotime($row->voucherdate));
                ?>
                <tr>
                    <td>
                         <select name="head[]" id="head">
                        <option value="0">Select Head</option>                
                            <?php
                            foreach($headdata as $thishead)
                            {
                                if($thishead->headid == $row->headid)
                                {
                                ?>
                                <option selected="selected" value="<?php echo $row->headid; ?>"><?php echo($thishead->headtype); ?></option>
                                <?php
                                }else{
                                ?>
                                <option value="<?php echo($thishead->headid); ?>"><?php echo($thishead->headtype); ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="customer[]" id="customer">
                        <option value="0">Select a Customer</option>  
                        <option value="-1" <?php if($row->customer=='-1'){echo "selected='selected'";}?>> -1 (Shrushti Repair)</option>
                            <?php
                            foreach($customer as $thiscustomer)
                            {
                                 if($thiscustomer->customerno == $row->customer)
                                {
                                ?>
                                <option selected="selected" value="<?php echo($row->customer); ?>"><?php echo($thiscustomer->customername); ?></option>
                                <?php
                                }else{
                                ?>
                                <option value="<?php echo($thiscustomer->customerno); ?>"><?php echo($thiscustomer->customername); ?></option>
                                <?php
                                }
                            
                            }
                            ?>
                        </select>
                    </td>
                    <td><input type='text' name='amount[]' id='amount' value="<?php echo $row->amount;?>" onkeypress="return onlyNos(event,this);" /><input type="hidden" id="uid" name="uid[]" value="<?php echo $row->uid;?>"/></td>
                    <td><input placeholder='dd-mm-yyyy' class="voucherdate" name = 'vdate[]'  value="<?php echo $datetest1;?>" type='text'></td>
                </tr>
               <?php
               }
               ?>
            </table>
            <br/>
            <div style="width:39%; float:left; margin-left:8%;">
                Remark <textarea name='remark' id='remark'><?php echo $remarks;?></textarea></br>
                <div>Action<input type="radio" name="editaction" value="edit" checked>Edit <input type="radio" name="editaction" value="delete">Delete</div><br>
                <input type="hidden" name="voucherid" id="voucherid" value="<?php echo $vid;?>"/>
                <input type="submit" name="updatevoucher" id="updatevoucher" value="Update Voucher"/>
            </div>
            </form>
            <div style="clear:both"></div>
        </div>
    </div>
</div>
<br/>
<?php
include("footer.php");
?>

    <script>
        function ValidateForm()
        {
            var editaction = $("input[name=editaction]:checked").val();
            if (editaction =="delete")
            {
               var r = confirm("Are you sure you want to delete voucher details");
               if(r==true)
               {
                  return true;
               }
               else
               {
                  return false;
               }
            }
        }
        
        
        
        
function addrow() {
    var table = document.getElementById("myTable");
    var row = table.insertRow(1);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);
    //cell1.innerHTML = "<button onclick='addrow()'>Add</button>"; 
    cell1.innerHTML = "<select name='head[]'><option value='0'>Select Head</option><?php foreach ($headdata as $thishead) { echo "<option value='".$thishead->headid."'>".$thishead->headtype."</option>"; } ?></select>";
    cell2.innerHTML = "<select name=\"customer[]\" id=\"customer\"><option value=\"0\">Select a Customer</option><option value=\"-1\"> -1 (Shrushti Repair)</option><?php foreach($customer as $thiscustomer){ ?> <option value=\"<?php echo($thiscustomer->customerno); ?>\"><?php echo($thiscustomer->customername); ?></option><?php } ?></select>"; 
    cell3.innerHTML = "<input type='text' name='amount[]' id='amount'/><input type='hidden' name='uid[]' id='uid' value='0'/>";
    cell4.innerHTML = "<input class='voucherdate' placeholder='dd-mm-yyyy' name = 'vdate[]' onclick='showdatepicker();'  type='text'>";
    
}


function onlyNos(e,t){
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

 function showdatepicker(){
        $('.voucherdate').datepicker({
        format: "dd-mm-yyyy",
        language:  'en',
        autoclose: 1
    }); 
 }
   $(document).ready(function(){
    $('.voucherdate').datepicker({
        format: "dd-mm-yyyy",
        language:  'en',
        autoclose: 1
    }); 
});

</script>
