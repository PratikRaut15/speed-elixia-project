<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Date.php");

class testing{
    
}
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$todaydate =date("Y-m-d");

$db = new DatabaseManager();
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

$SQL = sprintf("SELECT team.teamid, team.name FROM ".DB_PARENT.".team where member_type=1 ORDER BY name asc");
$db->executeQuery($SQL);
$team_allot_array = Array();
if ($db->get_rowCount() > 0){
    while ($row = $db->get_nextRow())
    {
        $team = new testing();
        $team->teamid  = $row["teamid"];
        $team->name = $row["name"];        
        $team_allot_array[] = $team;        
    }    
}

$createby = $_SESSION["sessionteamid"];
// See if we need to save a new one.
include("header.php");

if(isset($_POST["tsearch"])){
       $gofun=$_POST["gofun"];
       $sticketcust = $_POST["sticketcust"];
       $spriority = $_POST["spriority"];
       $sticket_allot = $_POST["sticket_allot"];
       $sticketstatus = $_POST["sticketstatus"];
       $stickettype = $_POST["stickettype"];
       $createdby = $_POST["createdby"];
}



function getpriority() {
    $db = new DatabaseManager();
    $sql = sprintf("select * from ".DB_PARENT.".sp_priority where isdeleted=0 ");
    $db->executeQuery($sql);
    $prioritydata = array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $prioritydata[] = array(
                'prid' => $row['prid'],
                'priority' => $row['priority']
            );
        }
        return $prioritydata;
    }
    return NULL;
}
$getpriority = getpriority();



function gettickettype() {
    $db = new DatabaseManager();
    $sql = sprintf("select * from  ".DB_PARENT.".sp_tickettype where isdeleted=0 ");
    $db->executeQuery($sql);
    $gettickettype = array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $gettickettype[] = array(
                'typeid' => $row['typeid'],
                'tickettype' => $row['tickettype']
            );
        }
        return $gettickettype;
    }
    return NULL;
}

$gettickettype = gettickettype();



?>


<br>
<div class="panel">
<div class="paneltitle" align="center">Search Ticket</div>
    <div class="panelcontents">
        <form name="ticketsearchform" id="ticketsearchform" method="POST" action="searchticket.php">
        <table width="100%">
            <tr>
                <td style="text-align: right;"><b>By Customer :</b></td><td>
                <select name="sticketcust" id="ticketcust" style="width: 140px;">
                            <option value="0">Select a Customer</option>     
                            <?php
                            foreach($customer as $thiscustomer)
                            {
                            ?>
                                <option value="<?php echo($thiscustomer->customerno); ?>" <?php if($sticketcust == $thiscustomer->customerno){ echo "selected='selected'";}?>   ><?php echo($thiscustomer->customername); ?></option>
                            <?php
                            }
                            ?>
                </select>
                </td>
                <td style="text-align: right;"><b>Priority :</b></td>
                <td>
                <select name="spriority" id="spriority">
                    <option value="0">Select Priority</option>
<!--                    <option value="High" <?php if($spriority=='High'){ echo "selected='selected'";}?> >High</option>
                    <option value="Moderate" <?php if($spriority=='Moderate'){ echo "selected='selected'";}?> >Moderate</option>
                    <option value="Low" <?php if($spriority=='Low'){ echo "selected='selected'";}?> >Low</option>-->
                    
                    <?php
                    if (isset($getpriority))
                        foreach ($getpriority as $row) {
                    ?>
                            <option value="<?php echo $row['prid']; ?>" ><?php echo $row['priority']; ?></option>
                     <?php
                                }
                            ?>
                </select>
                </td>
                <td style="text-align: right;"><b>Ticket Type :</b></td>
                <td>
                <select name="stickettype" id="stickettype">
                    <option value="0">Select Type</option>
<!--                    <option value="Operation" <?php if($stickettype=='Operation'){ echo "selected='selected'";}?>>Operations</option> 
                    <option value="Accounts" <?php if($stickettype=='Accounts'){ echo "selected='selected'";}?>>Accounts</option>
                    <option value="Software" <?php if($stickettype=='Software'){ echo "selected='selected'";}?>>Software</option> 
                    <option value="Support" <?php if($stickettype=='Support'){ echo "selected='selected'";}?>>Support</option>
                    <option value="Sales" <?php if($stickettype=='Sales'){ echo "selected='selected'";}?>>Sales</option>-->
                     <?php
                            if (isset($gettickettype)) {
                            foreach ($gettickettype as $row) {
                        ?>   
                            <option value="<?php echo $row['typeid']; ?>"><?php echo $row['tickettype']; ?></option>   
                            <?php
                                }
                            }
                            ?>
                    
                </select>
                </td>
                <td style="text-align: right;"><b>Allot To :</b></td>
                <td>
                   <select id="sticket_allot" name="sticket_allot">
                        <option value="0">Select Member</option>
                        <?php
                            foreach($team_allot_array as $teamallot)
                            {
                            ?>
                            <option value="<?php echo $teamallot->teamid; ?>" <?php if($sticket_allot == $teamallot->teamid){ echo "selected='selected'";}?> ><?php echo $teamallot->name; ?></option>
                            <?php
                            }
                            ?>
                    </select>  
                </td>
                <tr>
                <tr>   
                    <td colspan="3" style="text-align:right;"><b>Created By :</b></td>
                <td>
                   <select id="createdby" name="createdby">
                        <option value="0">Select Member</option>
                        <?php
                            foreach($team_allot_array as $teamallot)
                            {
                            ?>
                            <option value="<?php echo $teamallot->teamid; ?>" <?php if($createdby == $teamallot->teamid){ echo "selected='selected'";}?> ><?php echo $teamallot->name; ?></option>
                            <?php
                            }
                            ?>
                    </select>  
                </td>
                
                <td style="text-align: right;"><b>Status :</b></td>
                <td>
                    <select name="sticketstatus" id="sticketstatus">
                    <option value="">Select Status</option>
                    <option value="0" <?php if($sticketstatus=='0'){ echo "selected='selected'";}?>>Open</option>
                    <option value="1" <?php if($sticketstatus=='1'){ echo "selected='selected'";}?>>In Progress</option>
                    <option value="2" <?php if($sticketstatus=='2'){ echo "selected='selected'";}?>>Closed</option>
                    <option value="3" <?php if($sticketstatus=='3'){ echo "selected='selected'";}?>>Reopen</option>
                </select>
                </td>
                <td><input type="hidden" name="gofun" id="gofun" value="2">
                    <input type="submit" name="tsearch" id="tsearch" value="Search"></td>
            </tr>
        </table>
           </form>
    </div>
</div>
<br/>

<div class="panel">
<div class="paneltitle" align="center"> <?php if($gofun=="2"){ echo "Ticket List"; }else{ echo"Last 10 Tickets"; }?></div>
<div class="panelcontents">
<?php
$db = new DatabaseManager();


if($gofun=='2'){
    if($sticketcust=='0' && $sticketstatus==""&& $spriority=="0" && $sticket_allot=="0" && $stickettype=="0" && $createdby=="0"){
    $sqltest = sprintf("select * from (select (CASE WHEN stde.status=1 THEN 'Inprogress' WHEN stde.status= 2 THEN 'Closed' WHEN stde.status= 3 THEN 'Reopen' ELSE 'Open' END)as ticketstatus,sttype.tickettype,stde.uid,st.ticketid, st.title,st.ticket_type,st.sub_ticket_issue,st.customerid,st.eclosedate, sp.priority as prname, st.priority,st.create_on_date ,st.create_by, stde.status,stde.allot_to 
from ".DB_PARENT.".sp_ticket_details stde left join ".DB_PARENT.".sp_ticket as st on st.ticketid = stde.ticketid left join ".DB_PARENT.".sp_tickettype as sttype on sttype.typeid = st.ticket_type left join ".DB_PARENT.".sp_priority as sp on sp.prid = st.priority  order by stde.uid desc ) as main group by main.ticketid ");    
    }else{
        
    $where = array();
    $having = array();
    if($sticketcust!='0'){
        $where[] = " main.customerid=".$sticketcust;
    }
    if($spriority!="0"){
        $where[] = " main.priority='".$spriority."'";
    }
    if($stickettype!="0"){
        $where[] = " main.ticket_type='".$stickettype."'";
    }
    if($createdby!="0"){
        $where[] = " main.create_by='".$createdby."'";
    }
    
    if($sticket_allot!="0"){
         $having[] = " main.allot_to=".$sticket_allot;
    }
    if($sticketstatus!=""){
         $having[] = " main.status=".$sticketstatus;
    }
    
    $finalwhere = implode(" and " , $where);
    $finalhaving = implode(" and " ,$having);
    if(count($where)>0){
        $where1="where";
    }else{
        $where1="";
    }
     if(count($having)>0){
        $having1=" having";
    }else{
        $having1="";
    }

 $sqltest = sprintf("select * from (select (CASE WHEN stde.status=1 THEN 'Inprogress' WHEN stde.status= 2 THEN 'Closed' WHEN stde.status= 3 THEN 'Reopen' ELSE 'Open' END)as ticketstatus,sttype.tickettype, stde.uid,st.ticketid, st.title,st.ticket_type,st.sub_ticket_issue,st.customerid,st.eclosedate, sp.priority as prname, st.priority,st.create_on_date, st.create_by, stde.status,stde.allot_to 
from ".DB_PARENT.".sp_ticket_details stde left join ".DB_PARENT.".sp_ticket as st on st.ticketid = stde.ticketid left join ".DB_PARENT.".sp_tickettype as sttype on sttype.typeid = st.ticket_type  left join ".DB_PARENT.".sp_priority as sp on sp.prid = st.priority  order by stde.uid desc ) as main ".$where1." ". $finalwhere." group by main.ticketid".$having1."".$finalhaving);    
    }
}else{
    
$sqltest = sprintf("select * from ( select  (CASE WHEN stde.status=1 THEN 'Inprogress' WHEN stde.status= 2 THEN 'Closed' WHEN stde.status= 3 THEN 'Reopen' ELSE 'Open' END)as ticketstatus,sttype.tickettype, stde.uid,st.ticketid, st.title,st.ticket_type,st.sub_ticket_issue,st.customerid,st.eclosedate, sp.priority as prname, st.priority,st.create_on_date, st.create_by, stde.status,stde.allot_to 
from ".DB_PARENT.".sp_ticket_details stde left join ".DB_PARENT.".sp_ticket as st on st.ticketid = stde.ticketid  left join ".DB_PARENT.".sp_tickettype as sttype on sttype.typeid = st.ticket_type left join ".DB_PARENT.".sp_priority as sp on sp.prid = st.priority order by stde.uid desc ) as main group by main.ticketid order by main.ticketid DESC limit 10");    
}

$db->executeQuery($sqltest);

function get_customername($id){
$db = new DatabaseManager();
$SQL = sprintf("select customercompany from ".DB_PARENT.".customer where customerno=".$id);
$db->executeQuery($SQL);
 while ($row = $db->get_nextRow())   
 {
     $customercompany = $row["customercompany"];
 }
return $customercompany;
}
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


?>

<table border="1" width="100%">
    <tr>
        <th>Ticket Id</th>
        <th>Title</th>
<!--        <th>Ticket Type</th>-->
        <th>Issue</th>
        <th>Assign To</th>
        <th>Status</th>
        <th>Customer</th>
        <th>Expected Close date</th>
        <th>Priority</th>
        <th>Create On</th>
        <th>Create By</th>
        <th>Edit</th>
    </tr>
<?php

if($db->get_rowCount()>0)
{
    while ($row = $db->get_nextRow())   
                {
                    $ticketid  = $row["ticketid"];
                    $title = $row["title"];
                    $ticket_type = $row["tickettype"];
                    $sub_ticket_issue = $row["sub_ticket_issue"];
                    $customerid =$row["customerid"];
                    $eclosedate1 = $row["eclosedate"];
                    $priority = $row["prname"];
                    $create_on_date1 = $row["create_on_date"];
                    $create_by = $row["create_by"];
                    $status = $row["status"];
                    $ticket_status = $row["ticketstatus"];
                    $allot_to_last = $row["allot_to"]; 
                    $create_on_date = date('d-m-Y H:i:s', strtotime($create_on_date1));
                    $eclosedate = date('d-m-Y', strtotime($eclosedate1));
                 
                            
if($priority=="High"){
    $color="red";
    $fontcolor="white";
}  elseif ($priority=="Moderate") {
    $color="yellow";
    $fontcolor="black";
    
}elseif($priority=="Low"){
    $color="green";
    $fontcolor="white";
}else{
    $color="none";
    $fontcolor="none";
}

if($sub_ticket_issue==""){
    $sub_ticket_issue1='-';
}else{
    $sub_ticket_issue1=$sub_ticket_issue;
}
?>
                <tr style="text-align: center;">
                    <td><?php echo $ticketid; ?></td>
                    <td><?php echo $title;?></td>
                    <td><?php echo $ticket_type;?></td>
<!--                    <td><?php echo $sub_ticket_issue1;?></td>-->
                    <td><?php echo get_teamname($allot_to_last);?></td>
                    <td><?php echo $ticket_status;?></td>
                    <td><?php echo get_customername($customerid);?></td>
                    <td><?php echo $eclosedate;?></td>
                    <td style="background-color: <?php echo $color;?>; color:<?php echo $fontcolor;?>"><?php echo $priority; ?></td>
                    <td><?php echo $create_on_date;?></td>
                    <td><?php echo get_teamname($create_by);?></td>
                    <td><a href="edit_ticket.php?tid=<?php echo $ticketid;?>">Edit</a></td>
                </tr>
<?php               
                }
}
else
{
    echo"<tr><td colspan='12' style='text-align:center;'><b>No Tickets</b></td></tr>";
}
?>
</table>
</div>
</div>
<br/>
<?php
include("footer.php");

?>

<script>
    $(document).ready(function(){
        $( "#tickettype" ).change(function() {
            var tickettype = $("#tickettype").val();
            if(tickettype=="Operation"){
                $("#operations").css("display","block");
                $("#software").css("display","none");    
            }
            else if(tickettype=="Software"){
                $("#software").css("display","block");
                $("#operations").css("display","none");
            }else{
                $("#operations").css("display","none");    
                $("#software").css("display","none");    
            }
        });
        $('#expecteddate').datepicker({
            format: "dd-mm-yyyy",
            language:  'en',
            autoclose: 1,
             startDate: Date()

        }); 
    
    });

function ValidateForm(){
    var ticket_title = $("#ticket_title").val();
    var tickettype =$("#tickettype").val();
    var temail =$("#temail").val();
    var role = $("#role").val();
    var tlogin = $("#tlogin").val();
    var tpassword = $("#tpassword").val();
    var ticketcust = $("#ticketcust").val();
    var ticketdesc = $("#ticketdesc").val();
    var ticket_allot =$("#ticket_allot").val();
    var priority = $("#priority").val();
  if(ticket_title==""){
        alert("Please enter ticket title."); 
        return false;
    }else if(tickettype=="0"){
        alert("Please select Ticket type.");
        return false;
    }else if(tickettype!="0"){
        if(tickettype=="operation"){
         var operations =$("#operations").val();
         if(operations=='0'){
             alert("Please select operations");
             return false;
         }   
            
        }else if(tickettype=="software"){
             var software =$("#software").val();
             if(software=='0'){
                 alert("Please select software options.");
                 return false;
             }
        }
    }else if(ticketcust=="0"){
        alert("Please select customer");
        return false;
    }else if(ticketdesc==""){
        alert("Please enter ticket description");
        return false;
    }else if(ticket_allot=="0"){
        alert("Please select allot to");
        return false;
    }else if(expecteddate==""){
        alert("Please select date");
        return false;
    }else if(priority =="0"){
        alert("Please select Priority");
        return false;
    }else{
        $("#ticketform").submit();
    }

}

 function checkEmail(){	
        var ticketmailid = $("#ticketmailid").val();
        var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        var sendticketmail = document.getElementById("sendticketmail").checked = false;
        if(sendticketmail==false){
            alert("Please check send mail checkbox");
            return false;
        }else if(pattern.test(ticketmailid)){         
	 return true;
         }else{   
         alert("Enter valid email id");   
         return false;
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