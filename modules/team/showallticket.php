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
$SQL = sprintf("SELECT customerno, customercompany FROM ". DB_ELIXIATECH .".customer");
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

$SQL = sprintf("SELECT team.teamid, team.name FROM ". DB_ELIXIATECH .".team where member_type=1 ORDER BY name asc");
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
//print_r($_SESSION);
$createby = $_SESSION["sessionteamid"];
// See if we need to save a new one.
include("header.php");

function get_products(){
    $db=new DatabaseManager();
    $pdo = $db->CreatePDOConnForTech();
    $queryCallSP = "CALL " . speedConstants::SP_GET_PRODUCTS ;
    $products = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    //print_r($products);
    return $products;
    $db->ClosePDOConn($pdo);
}
$products = get_products();
function getpriority() {
    $db = new DatabaseManager();
    $sql = sprintf("select * from ". DB_ELIXIATECH .".sp_priority where isdeleted=0 ");
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
    $sql = sprintf("select * from  ". DB_ELIXIATECH .".sp_tickettype where isdeleted=0 ");
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



if(isset($_POST["tsearch"])){
       $gofun=$_POST["gofun"];
       $sticketcust = isset($_POST["sticketcust"]) ?$_POST["sticketcust"]:0 ;
       $spriority = $_POST["spriority"];
       $sticket_allot = $_POST["sticket_allot"];
       $sticketstatus = $_POST["sticketstatus"];
       $stickettype = $_POST["stickettype"];
       $createdby = $_POST["createdby"];
       $expirytickets = $_POST["expirytickets"]; 
}

if(isset($_POST['tsearchshowall'])){
       $gofun=$_POST["gofun"];
       $sticketcust = isset($_POST["sticketcust"]) ?$_POST["sticketcust"]:0 ;
       $spriority = $_POST["spriority"];
       $sticket_allot = $_POST["sticket_allot"];
       $sticketstatus = $_POST["sticketstatus"];
       $stickettype = $_POST["stickettype"];
       $createdby = $_POST["createdby"];
       $expirytickets = $_POST["expirytickets"]; 
}

?>
<br>
<div class="panel">
<div class="paneltitle" align="center">Search Tickets</div>
    <div class="panelcontents">
        <form name="ticketsearchform" id="ticketsearchform" method="POST" action="showallticket.php">
        <table width="100%">
            <tr>
                <td style="text-align: right;"><b>By Customer :</b></td><td>  
                    <input  type="text" name="icustomer" id="icustomer" size="25" value="<?php if (isset($_POST['icustomer'])) { echo $_POST['icustomer']; } ?>" autocomplete="on" placeholder="Enter Customer No Or Name" onkeyup="getCust()" />
                    <input type="hidden" name="sticketcust" id="ticketcust" value="<?php 
                    if (isset($_POST['sticketcust'])) { 
                        echo $_POST['sticketcust']; 
                    }else {
                        echo '0';
                    }
                    ?>"/>
                </td>
                <td style="text-align: right;"><b>Priority :</b></td>
                <td>
                <select name="spriority" id="spriority">
                    <option value="0">Select Priority</option>
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
                
                    
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="4">
                        <?php 
                            $chk='';
                            if(isset($expirytickets)){
                                $chk = "checked";
                            }
                        ?>
                        
                        <input type="checkbox" name="expirytickets" id="expirytickets" <?php echo $chk;?>  value="1" /> <b>Show Expired Ticket</b>
                    </td>
                    <td colspan="4" style="text-align:left;">
                        <input type="hidden" name="gofun" id="gofun" value="2">
                        <input type="submit" name="tsearch" id="tsearch" value="Search">
                        <input type="submit" name="tsearchshowall" id="tsearchshowall" value="Show All">
                        
                    </td>
            </tr>
        </table>
           </form>
    </div>
</div>
<br/>

<div class="panel">
<div class="paneltitle" align="center"> <?php if($gofun=="2"){ echo "All Tickets"; }else{ echo"Last 10 Tickets"; }?></div>
<div class="panelcontents">
<?php
$db = new DatabaseManager();


if($gofun=='2'){
    if($sticketcust=='0' && $sticketstatus==""&& $spriority=="0" && $sticket_allot=="0" && $stickettype=="0" && $createdby=="0" && $expirytickets==""){
        
    $sqltest = sprintf("select * from (select (CASE WHEN stde.status=1 THEN 'Inprogress' WHEN stde.status= 2 THEN 'Closed' WHEN stde.status= 3 THEN 'Reopen' ELSE 'Open' END)as ticketstatus,sttype.tickettype,stde.uid,st.ticketid, st.title,st.ticket_type,st.sub_ticket_issue,st.customerid,st.eclosedate, sp.priority as prname, st.priority,st.create_on_date ,st.create_by, stde.status,stde.allot_to, stde.allot_from, 
        st.prodId 
    from ". DB_ELIXIATECH .".sp_ticket_details stde left join ". DB_ELIXIATECH .".sp_ticket as st on st.ticketid = stde.ticketid left join ". DB_ELIXIATECH .".sp_tickettype as sttype on sttype.typeid = st.ticket_type left join ". DB_ELIXIATECH .".sp_priority as sp on sp.prid = st.priority  order by stde.uid desc ) as main group by main.ticketid ");    
    }else{
    $today = date('Y-m-d');    
    $where = array();
    $having = array();
    if($sticketcust!='0'&&$sticketcust!=""){
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
    
    if(isset($expirytickets) && $expirytickets==1){
        $where[] = " main.eclosedate < '".$today."'";
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

  $sqltest = sprintf("select * from (select (CASE WHEN stde.status=1 THEN 'Inprogress' WHEN stde.status= 2 THEN 'Closed' WHEN stde.status= 3 THEN 'Reopen' ELSE 'Open' END)as ticketstatus,sttype.tickettype, stde.uid,st.ticketid, st.title,st.ticket_type,st.sub_ticket_issue,st.customerid,st.eclosedate, sp.priority as prname, st.priority,st.create_on_date, st.create_by, stde.status,stde.allot_to, 
    st.prodId
from ". DB_ELIXIATECH .".sp_ticket_details stde left join ". DB_ELIXIATECH .".sp_ticket as st on st.ticketid = stde.ticketid left join ". DB_ELIXIATECH .".sp_tickettype as sttype on sttype.typeid = st.ticket_type  left join ". DB_ELIXIATECH .".sp_priority as sp on sp.prid = st.priority  order by stde.uid desc ) as main ".$where1." ". $finalwhere." group by main.ticketid".$having1."".$finalhaving);    
    }
}else{
  $sqltest = sprintf("select * from ( select  (CASE WHEN stde.status=1 THEN 'Inprogress' WHEN stde.status= 2 THEN 'Closed' WHEN stde.status= 3 THEN 'Reopen' ELSE 'Open' END)as ticketstatus,sttype.tickettype, stde.uid,st.ticketid, st.title,st.ticket_type,st.sub_ticket_issue,st.customerid,st.eclosedate, sp.priority as prname, st.priority,st.create_on_date, st.create_by, stde.status,stde.allot_to , 
    st.prodId
from ". DB_ELIXIATECH .".sp_ticket_details stde left join ". DB_ELIXIATECH .".sp_ticket as st on st.ticketid = stde.ticketid  left join ". DB_ELIXIATECH .".sp_tickettype as sttype on sttype.typeid = st.ticket_type left join ". DB_ELIXIATECH .".sp_priority as sp on sp.prid = st.priority order by stde.uid desc ) as main group by main.ticketid order by main.ticketid DESC limit 10");    
}
//echo $sqltest; 
$db->executeQuery($sqltest);

function get_customername($id){
$db = new DatabaseManager();
$SQL = sprintf("select customercompany from ". DB_ELIXIATECH .".customer where customerno=".$id);
$db->executeQuery($SQL);
 while ($row = $db->get_nextRow())   
 {
     $customercompany = $row["customercompany"];
 }
return $customercompany;
}
function get_teamname($id){
    if($id=='-1'){
        return "Customer";
    }
    $db = new DatabaseManager();
$SQL = sprintf("select name from ". DB_ELIXIATECH .".team where teamid=".$id);
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
                    $eclosedate = date(speedConstants::DEFAULT_DATE, strtotime($eclosedate1));
                    if ($eclosedate == date('d-m-Y','01-01-1970')){
                        $eclosedate ="N.A.";
                    }
                    $productId = $row['prodId'];

if ($priority == "Very High") {
    $color = "red";
    $fontcolor = "white";
} elseif ($priority == "High") {
    $color = "orange";
    $fontcolor = "white";
} elseif ($priority=="Medium") {
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
                    <td><?php echo $products[$productId-1]['prodName'];?></td>
                    <td>
                        <?php 
                        if($_SESSION['sessionteamid']==$allot_to_last || $_SESSION['sessionrole']=="Head"){
                        ?>
                        <a href="editTicket.php?ticketid=<?php echo $ticketid;?>" target="_blank"  >Edit</a>
                        <?php }?>
                    </td>
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


function getCust() {
        jQuery("#icustomer").autocomplete({
            source: "route_ajax.php?customername=getcust",
            select: function (event, ui) {
                /*clear selected value */
                jQuery(this).val(ui.item.value);
                jQuery('#ticketcust').val(ui.item.cid);
                return false;
            }
        });
    }   

    </script>