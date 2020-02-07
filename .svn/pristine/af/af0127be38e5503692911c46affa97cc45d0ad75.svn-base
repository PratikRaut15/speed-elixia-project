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
include_once("../../lib/system/utilities.php");
include_once("../../lib/bo/TeamManager.php");
class testing {
    
}

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$todaydate = date("Y-m-d");
$createby = $_SESSION["sessionteamid"];
$db = new DatabaseManager();
$SQL = sprintf("SELECT customerno, customercompany FROM ". DB_ELIXIATECH .".customer");
$db->executeQuery($SQL);
$customer = Array();
if ($db->get_rowCount() > 0) {
   while ($row = $db->get_nextRow()) {
       $testing = new testing();
       $testing->customerno = $row["customerno"];
       $testing->customername = $row["customerno"] . "( " . $row['customercompany'] . " )";
       $customer[] = $testing;
   }
}
$SQL = sprintf("SELECT team.teamid, team.name FROM ". DB_ELIXIATECH .".team where member_type=1 ORDER BY name asc");
$db->executeQuery($SQL);
$team_allot_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $team = new testing();
        $team->teamid = $row["teamid"];
        $team->name = $row["name"];
        $team_allot_array[] = $team;
    }
}

function get_emailid_team($id) {
    $db = new DatabaseManager();
    $SQL = sprintf("select email from ". DB_ELIXIATECH .".team where teamid=" . $id);
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $allot_to_email = $row["email"];
    }
    return $allot_to_email;
}
function get_products(){
    $db=new DatabaseManager();
    $pdo = $db->CreatePDOConnForTech();
    $queryCallSP = "CALL " . speedConstants::SP_GET_PRODUCTS ;
    $products = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    //print_r($products);
    return $products;
    $db->ClosePDOConn($pdo);
}


function get_emailid($id) {
    if (!empty($id)) {
        $db = new DatabaseManager();
//    $allot_to_email = array();
         $SQL = sprintf("select email_id from ". DB_ELIXIATECH .".report_email_list where eid IN (" . $id . ")");
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $mail[] = $row["email_id"];
        }
        return $mail;
    } else {
        return NULL;
    }
}

function get_teamname($id) {
    if($id == 0 || $id == ''){
        return ' ';
    }else{
        $db = new DatabaseManager();
        $SQL = sprintf("select name from ". DB_ELIXIATECH .".team where teamid=" . $id);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $teamname = $row["name"];
        }
        return $teamname;
    }
    
}

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
    $sql = sprintf("select * from  ". DB_ELIXIATECH .".sp_tickettype where isdeleted = 0 ");
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

function gettimeslot() {
    $db = new DatabaseManager();
    $sql = sprintf("select * from  ". DB_ELIXIATECH .".sp_timeslot where isdeleted=0 ");
    $db->executeQuery($sql);
    $gettimeslot = array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $gettimeslot[] = array(
                'tsid' => $row['tsid'],
                'timeslot' => $row['timeslot']
            );
        }
        return $gettimeslot;
    }
    return NULL;
}

function gettimeslot_data($timeslotid) {
    $db = new DatabaseManager();
    $sql = sprintf("select * from  ". DB_ELIXIATECH .".sp_timeslot where tsid = " . $timeslotid . " AND isdeleted=0 ");
    $db->executeQuery($sql);
    $timeslotname = "";
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $timeslotname = $row['timeslot'];
        }
        return $timeslotname;
    }
    return NULL;
}

$gettimeslot = gettimeslot();
$createby = $_SESSION["sessionteamid"];

$message = "";
$ticket_title = "";
$ticketdesc = "";
$raiseondate = "";
$expecteddate = "";
$ticketmailid = "";
$tickettype = "";
$ticketcust = "";
$ticket_allot = "";

if (isset($_POST["submitpros"])) {
    $ticket_title = $_POST["ticket_title"];
    $ticketcust = $_POST["customerno"];
    $tickettype = $_POST["tickettype"];
    $ticketdesc = htmlentities($_POST["ticketdesc"]);
    $ticket_allot = $_POST["allot_to"];
    $raiseondate = $_POST["raiseondate"];
    $raiseontime = $_POST["raiseontime"];

    $expecteddate = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($today))); 
    $ticketmailid = $_POST["sentoEmail"];
    $priority = $_POST["priority"];
    $prodId = $_POST['selectedProduct'];
    $sendticketmail = 1;
    $ccemail_arr = Array();
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 11) == "em_vehicle_") {
            $ccemail_arr[] = substr($single_post_name, 11);
        }
    }
    $ccemailids = implode(",", $ccemail_arr);

    $email_arr = Array();
    foreach ($_POST as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 12) == "em_vehicles_") {
            $email_arr[] = substr($single_post_name, 12);
        }
    }
    $ticketmailid = implode(",", $email_arr);

    if ($ticket_title == "" || $priority == "0" || $ticket_allot == "0" || $ticketdesc == "" || $ticketcust == "0" || $tickettype == "0") {
        $message = "All fields mandatory.";
    }
    if ($message == "") {
        if (!empty($expecteddate) && $expecteddate != "1970-01-01") {
            $datetest = date("Y-m-d", strtotime($expecteddate));
            $expecteddate = $datetest;

        }
        if (!empty($raiseondate) && $raiseondate != "1970-01-01") {
            $raiseondatetime = date("Y-m-d H:i:s", strtotime($raiseondate . " " . $raiseontime . ":00"));

        } else {
            $raiseondatetime = date("Y-m-d H:i:s");
        }
        $platform = 1;
        $obj = new stdClass();
        $obj->ticket_title = $ticket_title;
        $obj->tickettype = $tickettype;
        $obj->priority = $priority;
        $obj->ticketdesc = $ticketdesc;
        $obj->ticketcust = $ticketcust;
        $obj->todaysdate = Sanitise::DateTime($today);
        $obj->ticket_allot = $ticket_allot;
        $obj->raiseondatetime = Sanitise::DateTime($raiseondatetime);
        $obj->expectedCloseDate = $expecteddate;
        $obj->sendticketmail = $sendticketmail;
        $obj->ticketmailid = $ticketmailid;
        $obj->ccemailids = $ccemailids;
        $obj->createby = $createby;
        $obj->platform = $platform;
        $obj->prodId = $prodId;
        $obj->rel_mgr=$ticket_allot;
        $obj->ecdToBeUpdatedBy = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($today)));
        $tm = new TeamManager();
        $outputResult = $tm->add_ticket($obj);
        $data_mail = array(
            'tomail' => $outputResult['allottoemail'],
            'title' => $ticket_title,
            'ticket_type' => $outputResult['tickettypename'],
            'ticket_typeid' => $tickettype,
            'custid' => $ticketcust,
            'ecd' => $expecteddate,
            'priority' => $outputResult['priorityname'],
            'ticketid' => $outputResult['ticketid'],
            'sendmailstatus' => $sendticketmail,
            'ticket_desc' => $ticketdesc,
            'sendmailid' => $ticketmailid,
            'ticket_allot' => $ticket_allot,
            'ccemailids' => $ccemailids,
            'login_team_id' => GetLoggedInUserId(),
            'status' => "Open"
        );

        send_mail_allot_to($data_mail); //send mail to ticket allot to 
        //header("Location: ticket.php");
    }
}
// See if we need to save a new one.

$_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
$_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";
include("header.php");

function send_mail_allot_to($data_mail) {
    $message = "";
    $messageTeam = "";
    $tomail = $data_mail['tomail'];
    $title = $data_mail['title'];
    $ticket_type = $data_mail['ticket_type'];
    $custid = $data_mail['custid'];
    $custname = get_customername($custid);
    $ecd = $data_mail['ecd'];
    $priority = $data_mail['priority'];
    $ticketid = "ET00" . $data_mail['ticketid'];
    $ticket_desc = $data_mail['ticket_desc'];
    $createby = $_SESSION["sessionteamid"];
    $sendmailid = $data_mail['sendmailid'];
    $sendmailstatus = $data_mail['sendmailstatus'];
    $createdname = get_teamname($createby);
    $allotToName = get_teamname($data_mail['ticket_allot']);
    $ticketstatus = $data_mail['status'];
    $ticket_typeid = $data_mail['ticket_typeid'];
    $ccemailids = $data_mail['ccemailids'];
    $toTeam = array($tomail);
    $subjectTeam = "Elixia Speed - Ticket No: " . $ticketid . "(Alloted to - " . $allotToName . " ) - Ticket For: " . ucfirst($ticket_type) . " (Customer No: " . $custid . ")";

    $strCCMailIds = "";
    $strBCCMailIds = "";
    $strTeamBCCMailIds = "sanketsheth1@gmail.com";
    $attachmentFilePath = "";
    $attachmentFileName = "";

    $messageTeam = "
        <table>
        <tr><td>Ticket No : </td><td>" . $ticketid . "</td></tr>
        <tr><td>Created By : </td><td>" . $createdname . "</td></tr>
        <tr><td>Expected Closure date : </td><td>" . date("d-M-Y", strtotime($ecd)) . "</td></tr>
        <tr><td>Title : </td><td>" . $title . "</td></tr>
        <tr><td>Customer : </td><td>" . $custname . "</td></tr>    
        <tr><td>Ticket Type : </td><td>" . $ticket_type . "</td></tr> 
        <tr><td>Priority : </td><td>" . $priority . "</td></tr>              
        <tr><td>Description :</td><td>" . $ticket_desc . "</td></tr>   
        <tr><td>Status :</td><td>" . $ticketstatus . "</td></tr>   
        </table>";

    $strCCMailId = get_emailid($ccemailids);
    if (!empty($strCCMailId)) {
        $strCCMailIds = "'" . implode(",", $strCCMailId) . "'";
    }

    if ($sendmailstatus == 1 && isset($sendmailid) && !empty($sendmailid)) {
         $sendmailid = get_emailid($sendmailid);
         $sendmailidtoteam = $sendmailid;
         $to = $sendmailid;
        $subject = "Elixia Speed - Support Ticket No: " . $ticketid . " - Ticket For: " . ucfirst($ticket_type) . " (Customer No: " . $custid . ")";
        $message .= "<h4> A new support ticket has been created for you. Kindly interact with Elixia team providing the ticket number for further assistance. </h4>";
        $message .= $messageTeam;

        sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
    }

    //Send Mail
    if (!empty($sendmailidtoteam)) {
        $messageTeam .= " Customer Email Id:";
        foreach ($sendmailidtoteam as $mailteam)
            $messageTeam .= $mailteam . ',';
    }

    sendMailUtil($toTeam, $strCCMailIds, $strTeamBCCMailIds, $subjectTeam, $messageTeam, $attachmentFilePath, $attachmentFileName);
}
?>
<style>
input[type="text"] {
    margin: 0; 
}
    .recipientbox {
        border: 1px solid #999999;
        float: left;
        font-weight: 700;
        padding: 4px 27px;
        /*    width: 100px;*/

        float:left;
        -webkit-transition:all 0.218s;
        -webkit-user-select:none;
        background-color:#000;
        /*background-image:-webkit-linear-gradient(top, #4D90FE, #4787ED);*/
        border:1px solid #3079ED;
        color:#FFFFFF;
        text-shadow:rgba(0, 0, 0, 0.0980392) 0 1px;
        border:1px solid #DCDCDC;
        border-bottom-left-radius:2px;
        border-bottom-right-radius:2px;
        border-top-left-radius:2px;
        border-top-right-radius:2px;

        cursor:default;
        display:inline-block;
        font-size:11px;
        font-weight:bold;
        height:27px;
        line-height:27px;
        min-width:46px;
        padding:0 8px;
        text-align:center;

        border: 1px solid rgba(0, 0, 0, 0.1);
        color:#fff !important;
        font-size: 11px;
        font: bold 11px/27px Arial,sans-serif !important;
        vertical-align: top;
        margin-left:5px;
        margin-top:5px;
        text-align:left;
    }
    .recipientbox img {
        float:right;
        padding-top:5px;
    }
    .labelwidth{
        width:200px;
    }
</style>

<div class="panel">
    <div class="paneltitle" align="center">Add New Ticket</div>
    <div class="panelcontents">
        <form method="post" action="ticket.php" name="ticketform" method="POST" id="ticketform" onsubmit="return ValidateForm();
                return false;">
                  <?php echo "<span style='color:red; font-size:12px;'>" . $message . "</span>"; ?>
            <table width="80%">
                <tr> <td class="labelwidth">Title <span style="color:red;">*</span>:</td><td><textarea name="ticket_title" id="ticket_title" required style="width: 300px;"><?php echo $ticket_title; ?></textarea></td></tr>
                <tr><td class="labelwidth">Customer <span style="color:red;">*</span>:</td>
                    <td>
                        <input  type="text" name="customername" id="customername" size="30" value="" autocomplete="off" placeholder="Enter Customer Name or number"  onkeypress="getCustomer();"/>
                        <input type="hidden" id="customerno" name="customerno" value=""/>
                        <div id="crmmanager" style="display:none; margin-left:10px; width:auto;"></div>
                        <?php 
                        $products=get_products();
                        //print_r($products);
                        if(isset($products)){
                            echo "<select id='product'>";
                            foreach ($products as $product){
                                ?>
                                    <option value =<?php echo $product['prodId'];?>><?php echo $product['prodName']; ?></option>
                                <?php
                            }
                        }echo "</select>";
                        echo "<input type='hidden' name='selectedProduct' id='selectedProduct' value='1'></input>";
                        ?>
                    </td>
                </tr>

                <tr> <td class="labelwidth">Type <span style="color:red;">*</span>:</td>
                    <td>
                        <select name="tickettype" id="tickettype">
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
                </tr>

                <tr><td class="labelwidth">Description <span style="color:red;">*</span>:</td>
                    <td><textarea name="ticketdesc" id="ticketdesc" style="width: 300px;resize:none;" required><?php echo $ticketdesc; ?></textarea></td></tr>
                <tr><td class="labelwidth">Allot To <span style="color:red;">*</span>:</td><td>
                        <select id="ticket_allot" name="ticket_allot" id="ticket_allot" required>
                            <option value="0">Select Member</option>
                            <?php
                            foreach ($team_allot_array as $teamallot) {
                                ?>
                                <option value="<?php echo $teamallot->teamid; ?>" <?php
                                if ($ticket_allot == $teamallot->teamid) {
                                    echo "selected='selected'";
                                }
                                ?> ><?php echo $teamallot->name; ?></option>
                                        <?php
                                    }
                                    ?>
                        </select>
                        <input type='hidden' name='allot_to' id='allot_to'>


                    </td></tr>
                <tr><td class="labelwidth">Raise on date <span style="color:red;">*</span>:</td><td><input type="text" name="raiseondate" id="raiseondate" placeholder="dd-mm-yyyy" value="<?php echo $raiseondate; ?>" required><input id="raiseontime" name = "raiseontime" type="text" class="input-mini" required></td></td></tr>
                <tr><td><input type="hidden" name="expecteddate" id="expecteddate" placeholder="dd-mm-yyyy" value="<?php echo $expecteddate; ?>"></td></tr>
                <tr>
                    <td class="labelwidth">Send Mail To Customer <span style="color:red;">*</span>:</td>
                    <td>
                        <input type="text" name="ticketmailid" id="ticketmailid" value="" placeholder="Enter email id" onkeyup="getmailids()" >
                        <input type="button" style="float:right;margin-right: 40px;" class="g-button g-button-submit" onclick="insertMailId()" value="Add Mail Id" name="addMail">
                        <input type='hidden' name='sentoEmail' id="sentoEmail"/>
                    </td>
                </tr>
                <tr><td></td><td>
                        <div id="listemailids" ></div>
                    </td></tr>
                <tr><td class="labelwidth">CC :</td>
                    <td>
                        <input  type="text" name="ccmail" id="ccmail" size="20" value="" autocomplete="off" placeholder="Enter email id"  />
                        <input  type="hidden" name="cc_mail"  id="cc_mail" size="20">

                    </td></tr>
                <tr><td></td><td>
                        <div id="listccmailid" ></div>
                    </td></tr>
                <tr>
                    <td class="labelwidth">Priority :</td>
                    <td>
                        <select name="priority" id="priority">
                            <option value="0">Select Priority</option>
                            <?php
                            if (isset($getpriority))
                                foreach ($getpriority as $row) {
                                    ?>
                                    <option value="<?php echo $row['prid']; ?>" <?php
                                    if ($row['prid'] == 1) {
                                        echo "selected";
                                    }
                                    ?> ><?php echo $row['priority']; ?></option>
                                            <?php
                                        }
                                    ?>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="submit" id="submitpros" name="submitpros" value="Create Ticket"/>
        </form>
    </div>
</div>

<br>
<div class="panel">
    <div class="paneltitle" align="center">Ticket List</div>
    <div class="panelcontents">
        <?php

        function get_customername($id) {
            $db = new DatabaseManager();

       $SQL = sprintf("select customercompany from ". DB_ELIXIATECH .".customer where customerno=" . $id);

            $db->executeQuery($SQL);
            while ($row = $db->get_nextRow()) {
                $customercompany = $row["customercompany"];
            }
            return $customercompany;
        }
        $db = new DatabaseManager();
        $tm=new TeamManager();
        $arrResult = $tm->getTickets($createby);
        ?>

        <table border="1" width="100%">
            <tr>
                <th>Ticket Id</th>
                <th>Title</th>
                <th>Ticket Type</th>
<!--            <th>Issue</th>-->
                <th>Assign To</th>
                <th>Status</th>
                <th>Customer</th>
                <th>E.C.D</th>
                <th>Priority</th>
                <th>Create On</th>
                <th>Create By</th>
                <th>Product</th>
                <th>Edit</th>
            </tr>
            <?php
            if (count($arrResult) > 0) {
               foreach($arrResult as $row) {
                // echo "<pre>";
                // print_r($row);
                    $ticketid = $row["ticketid"];
                    $title = $row["title"];
                    $ticket_type = $row["tickettype"];
                    // $sub_ticket_issue = $row["sub_ticket_issue"];
                    $customerid = $row["customerid"];
                    $customercompany = $row['customercompany'];
                    $eclosedate1 = $row["eclosedate"];
                    $priority = $row["priority"];
                    $create_on_date1 = $row["create_on_date"];
                    $create_by = $row["create_by_name"];
                    $status = $row["status"];
                    $closeby = $row["closeby"];
                    $ticket_status = $row["ticketStatus"];
                    $allot_to_last = $row["allot_to_name"];
                    $allot_from_last = $row["allot_from_name"];
                    $create_on_date = date('d-m-Y H:i:s', strtotime($create_on_date1));
                    $eclosedate = date('d-m-Y', strtotime($eclosedate1));
                    if ($eclosedate == '01-01-1970' OR $eclosedate == '30-11--0001'){
                        $eclosedate ="N.A.";
                    }
                    $productId=$row["prodId"];
                    if ($priority == "Very High") {
                        $color = "red";
                        $fontcolor = "white";
                    } elseif ($priority == "High") {
                        $color = "orange";
                        $fontcolor = "white";
                    } elseif ($priority == "Medium") {
                        $color = "yellow";
                        $fontcolor = "black";
                    } elseif ($priority == "Low") {
                        $color = "green";
                        $fontcolor = "white";
                    } else {
                        $color = "none";
                        $fontcolor = "none";
                    }

                    if ($sub_ticket_issue == "") {
                        $sub_ticket_issue1 = '-';
                    } else {
                        $sub_ticket_issue1 = $sub_ticket_issue;
                    }
                    ?>
                    <tr style="text-align: center;">
                        <td><?php echo $ticketid; ?></td>
                        <td><?php echo $title; ?></td>
                        <td><?php echo $ticket_type; ?></td>       
                        <td><?php echo $allot_to_last; ?></td>
                        <td><?php
                            if ($ticket_status == "Closed") {
                                echo $ticket_status . " by =>" . $allot_from_last;
                            } else {
                                echo $ticket_status;
                            }
                            ?></td>
                        <td><?php if (isset($customerid)) { echo $customercompany; } else { echo ''; } ?></td>
                        <td><?php echo $eclosedate; ?></td>
                        <td style="background-color: <?php echo $color; ?>; color:<?php echo $fontcolor; ?>"><?php echo $priority; ?></td>
                        <td><?php echo $create_on_date; ?></td>
                        <td><?php if (isset($create_by)) { echo $create_by; } else { echo ''; } ?></td>
                        <td><?php echo $products[$productId-1]['prodName'];?></td>
                        <td>
                            <?php
                            if ($ticket_status != "Closed") {
                                ?>
                                <a href="edit_ticket.php?tid=<?php echo $ticketid; ?>">Edit</a></td>
                        <?php } ?>
                    </tr>
                    <?php
                }
            } else {
                echo"<tr><td colspan='12' style='text-align:center;'><b>No Tickets</b></td></tr>";
            }
            ?>
        </table>
    </div>
</div>
<br/>


<script>
    function getCustomer() {
        jQuery("#customername").autocomplete({
            source: "route_ajax.php?customername=getcustomer",
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);
                
                jQuery.ajax({
                    type: "POST",
                    url: "ticket_ajax.php",
                    data: 'cid=' + ui.item.cid,
                    success: function (getname) {
                        if (getname != '0') {
                            jQuery("#crmmanager").css("display", "none");
                            jQuery("#crmmanager").css("display", "block");
                            jQuery("#crmmanager").html("<b>Crm Manager :</b>" + getname);
                        } else {
                            jQuery("#crmmanager").css("display", "none");
                        }
                    }
                });
//                return false;
            }
        });
    }
    jQuery(document).ready(function () {
        $('#allot_to').val($('#ticket_allot').val());
        jQuery("#customerno").change(function () {
            var ticketcust = jQuery("#customerno").val();

            if (ticketcust != 0) {
                jQuery.ajax({
                    type: "POST",
                    url: "ticket_ajax.php",
                    data: 'cid=' + ticketcust,
                    success: function (getname) {
                        
                        if (getname != '0') {
                            jQuery("#crmmanager").css("display", "block");
                            jQuery("#crmmanager").html("<b>Crm Manager :</b>" + getname);
                        } else {
                            jQuery("#crmmanager").css("display", "none");
                        }
                    }
                });
            }
        });

        jQuery('#raiseondate').datepicker({
            dateFormat: "dd-mm-yy",
            language: 'en',
            autoclose: 1,
            startDate: Date()
        });

        jQuery('#raiseontime').timepicker({'timeFormat': 'H:i'});

        jQuery('#expecteddate').datepicker({
            dateFormat: "dd-mm-yy",
            language: 'en',
            autoclose: 1,
            startDate: Date()
        });

        var customerno = jQuery("#customerno").val();
        jQuery("#ccmail").autocomplete({
            source: "route_ajax.php?work=getmail&customerno=" + customerno,
            select: function (event, ui) {
                insertCCMailDiv(ui.item.value, ui.item.eid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });

    function getCustomer() {
        jQuery("#customername").autocomplete({
            source: "route_ajax.php?customername=getcustomer",
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);
                
                jQuery.ajax({
                    type: "POST",
                    url: "ticket_ajax.php",
                    data: 'cid=' + ui.item.cid,
                    success: function (getname) {
                        if (getname != '0') {
                            jQuery("#crmmanager").css("display", "none");
                            jQuery("#crmmanager").css("display", "block");
                            jQuery("#crmmanager").html("<b>Crm Manager :</b>" + getname);
                        } else {
                            jQuery("#crmmanager").css("display", "none");
                        }
                    }
                });
//                return false;
            }
        });
    }

    function insertCCMailDiv(selected_name, emailid) {
        jQuery("#cc_mail").val(function (i, val) {
            if (!val.includes(emailid)) {
                return val + (!val ? '' : ',') + emailid;
            }
            else {
                return val;
            }
        });

        if (emailid != "" && jQuery('#em_vehicle_div_' + emailid).val() == null) {
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';
            remove_image.onclick = function () {
                removeCCEmailDiv(emailid);
            };
            div.className = 'recipientbox';
            div.id = 'em_vehicle_div_' + emailid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + emailid + '" value="' + emailid + '"/>';
            jQuery("#listccmailid").append(div);
            jQuery(div).append(remove_image);
        }
    }

    function removeCCEmailDiv(eid) {
        var rep = "," + eid;
        jQuery("#cc_mail").val(jQuery("#cc_mail").val().replace(rep, ""));
        jQuery("#cc_mail").val(jQuery("#cc_mail").val().replace(eid, ""));
        jQuery('#em_vehicle_div_' + eid).remove();
        console.log(jQuery("#cc_mail").val());
    }
    $('#ticket_allot').change(function(){
        $('#allot_to').val($('#ticket_allot').val());
    });
    function ValidateForm() {

        var ticket_title = jQuery("#ticket_title").val();
        var tickettype = jQuery("#tickettype").val();
        var temail = jQuery("#temail").val();
        var role = jQuery("#role").val();
        var tlogin = jQuery("#tlogin").val();
        var tpassword = jQuery("#tpassword").val();
        var ticketcust = jQuery("#ticketcust").val();
        var ticketdesc = jQuery("#ticketdesc").val();
        var ticket_allot = jQuery("#allot_to").val();
        var priority = jQuery("#priority").val();
        var expecteddate = jQuery("#expecteddate").val();
        var sentoEmail = jQuery("#sentoEmail").val();

        if (ticket_title == "") {
            alert("Please enter ticket title.");
            return false;
        } else if (ticketcust == 0) {
            alert("Please select customer");
            return false;
        } else if (sentoEmail == '') {
            alert("Please enter email id");
            return false;
        } else if (tickettype == 0) {
            alert("Please select Ticket type.");
            return false;
        } else if (jQuery('#message').val() == '') {
            alert("Please enter ticket description");
            return false;
        } else if (ticket_allot == 0) {
            alert("Please select allot to");
            return false;
        } else if (priority == 0) {
            alert("Please select Priority");
            return false;
        } else {
            jQuery("#ticketform").submit();
        }

    }


    function getmailids() {
        var data = '';
        data = jQuery('#customerno').val();
        if (data == '') {
            alert('Enter valid customer');
            jQuery("#customername").focus();
            return false;
        } else {
            jQuery("#ticketmailid").autocomplete({
                source: "route_ajax.php?work=getmailforTech&customerno=" + data,
                select: function (event, ui) {
                    insertEmailDiv(ui.item.value, ui.item.eid);
                    /*clear selected value */
                    jQuery(this).val("");
                    return false;
                }
            });
        }
    }

    function insertEmailDiv(selected_name, eid) {
        jQuery("#sentoEmail").val(function (i, val) {
            if (!val.includes(eid)) {
                return val + (!val ? '' : ',') + eid;
            }
            else {
                return val;
            }
        });

        if (eid != "" && jQuery('#em_vehicle_div_' + eid).val() == null) {
            var div = document.createElement('div');
            div.id = "contain";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';

            remove_image.onclick = function () {
                removeEmailDiv(eid);
            };

            div.className = 'recipientbox';
            div.id = 'em_vehicle_div_' + eid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicles_' + eid + '" value="' + eid + '"/>';
            jQuery("#listemailids").append(div);
            jQuery(div).append(remove_image);
        }
    }
    function removeEmailDiv(eid) {

        var rep = "," + eid;
        jQuery("#sentoEmail").val(jQuery("#sentoEmail").val().replace(rep, ""));
        jQuery("#sentoEmail").val(jQuery("#sentoEmail").val().replace(eid, ""));
        jQuery('#em_vehicle_div_' + eid).remove();
        console.log(jQuery("#sentoEmail").val());
    }
    function insertMailId() {
        var data = '';
        data = jQuery('#customerno').val();
        var emailid1;
        var emailText1 = document.getElementById("ticketmailid").value;
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

        if (!testEmail.test(jQuery("#ticketmailid").val())) {
            alert("Enter Valid Mail Id");
            return false;
        }
        else {
            jQuery.ajax({
                url: 'route_ajax.php?work=insertmailforTech&dataTest=' + emailText1 + '&customerno1=' + data,
                type: 'post',
                success: function (data1) {
                    insertEmailDiv(emailText1, data1);
                }
            });
            jQuery("#ticketmailid").val("");
        }
    }
    jQuery('#product').change(function () {
        $('#selectedProduct').val($('#product').val());
        //console.log($('#selectedProduct').val());
    });

</script>
<script src='../../scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>
<?php
include("footer.php");
?>