<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(-1);

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
$ticketid = $_REQUEST["tid"];
$cust = $_REQUEST['cust']; //customer edit ticket 
if ($cust == '1') {
    $customerview = 1;
} else {
    $customerview = 0;
}

if ($ticketid == "" || $ticketid == "0") {
    header("Location: ticket.php");
} else {

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT customerno, customercompany FROM  ". DB_ELIXIATECH .".customer");
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

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT id,status FROM  ". DB_ELIXIATECH .".ticket_status WHERE isdeleted = 0 AND id <> '7';");
    $db->executeQuery($SQL);
    $tstatus = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $status['id'] = $row["id"];
            $status['status'] = $row["status"];
            $tstatus[] = $status;
        }
    }

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT prid, priority FROM  ". DB_ELIXIATECH .".sp_priority where isdeleted=0");
    $db->executeQuery($SQL);
    $priorityarr = Array();
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $priorityarr[] = array(
                'prid' => $row['prid'],
                'priority' => $row['priority'],
            );
        }
    }



    $SQL = sprintf("SELECT team.teamid, team.name FROM  ". DB_ELIXIATECH .".team where member_type=1 ORDER BY name asc");
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

    $SQL = sprintf("select  st.ticketid
                            ,st.uid as creater
                            ,st.send_mail_to
                            ,st.send_mail_cc
                            ,st.send_mail_status
                            ,st.uid
                            ,st.title
                            ,sttype.tickettype as tickettypename
                            ,st.ticket_type
                            ,st.sub_ticket_issue
                            ,st.customerid
                            ,st.eclosedate
                            ,sp.prid ,sp.priority
                            ,st.create_on_date
                            , st.create_by
                            , st.created_type 
                    FROM     ". DB_ELIXIATECH .".sp_ticket as st 
                    left join  ". DB_ELIXIATECH .".sp_tickettype as sttype on sttype.typeid = st.ticket_type 
                    left join  ". DB_ELIXIATECH .".sp_priority as sp on sp.prid= st.priority 
                    where   st.ticketid=" . $ticketid);
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $ticket_id = $row["ticketid"];
        $ticketcreater = $row["creater"];
        $title = $row["title"];
        $ticket_type = $row["tickettypename"];
        $tickettypeid = $row["ticket_type"];
        $sub_ticket_issue = $row["sub_ticket_issue"];
        $customerid = $row["customerid"];
        $eclosedate1 = $row["eclosedate"];
        $priority = $row["priority"];
        $prid = $row["prid"];
        $create_on_date1 = $row["create_on_date"];
        $create_by = $row["create_by"];
        $create_on_date = date('d-m-Y H:i:s', strtotime($create_on_date1));
        $eclosedate = date('d-m-Y', strtotime($eclosedate1));
        $created_type = $row["created_type"];
//        $custuid = $row['uid'];  //customer ticket created user

        $send_mail_to = $row['send_mail_to'];
        $send_mail_cc = $row['send_mail_cc'];
    }
    $email_array = explode(',', $send_mail_to);
    $e_array = array();
    foreach ($email_array as $id) {
        $SQL = sprintf("SELECT email_id FROM  ". DB_ELIXIATECH .".report_email_list WHERE eid=%d", $id);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $earray["id"] = $id;
                $earray["emailid"] = $row['email_id'];
                $e_array[] = $earray;
            }
        }
    }
    $email_cc_array = explode(',', $send_mail_cc);
    $cc_array = array();
    foreach ($email_cc_array as $id) {
        $SQL = sprintf("SELECT email_id FROM  ". DB_ELIXIATECH .".report_email_list WHERE eid=%d", $id);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $earray["id"] = $id;
                $earray["emailid"] = $row['email_id'];
                $cc_array[] = $earray;
            }
        }
    }

    $SQL = sprintf("SELECT description, allot_to as last_allot_to,status FROM  ". DB_ELIXIATECH .".`sp_ticket_details` where ticketid=" . $ticketid . " ORDER BY `sp_ticket_details`.`uid`  DESC limit 1");
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $last_allot_to = $row['last_allot_to'];
        $ticketstatus = $row["status"];
        $description = $row["description"];
    }

    function get_teamname($id) {
        if(!isset($id)){
            return "N.A.";
        }
        $db = new DatabaseManager();
        $SQL = sprintf("select name from  ". DB_ELIXIATECH .".team where teamid=" . $id);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $teamname = $row["name"];
        }
        return $teamname;
    }

    function getUserMail($ticketid) {
        $db = new DatabaseManager();
        $SQL = sprintf("SELECT  email_id 
                        FROM    ". DB_ELIXIATECH .".report_email_list r 
                        LEFT OUTER JOIN  ". DB_ELIXIATECH .".sp_ticket t ON r.eid IN (t.send_mail_to) 
                        WHERE   t.ticketid = " . $ticketid);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $emailids = $row['email_id'];
        }
        return $emailids;
    }

    function get_userdetails($ticketcreater) {
        $data = array();
        $db = new DatabaseManager();
        $SQL = sprintf("select realname,email from user where userid=" . $ticketcreater);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $data[] = array(
                "realname" => $row["realname"],
                "email" => $row["email"]
            );
        }
        return $data;
    }

    function get_customername($id) {
        $db = new DatabaseManager();
        $SQL = sprintf("select customercompany from  ". DB_ELIXIATECH .".customer where customerno=" . $id);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $customercompany = $row["customercompany"];
        }
        return $customercompany;
    }

    function get_emailid($id) {
        $db = new DatabaseManager();
        $SQL = sprintf("select * from  ". DB_ELIXIATECH .".team where teamid=" . $id);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $allot_to_email = $row["email"];
        }
        return $allot_to_email;
    }

//////close ticket mail 
    function send_ticket_mail($close_data_mail) {
        $message = "";
        $tomail = $close_data_mail['tomail'];
        $closeby = $close_data_mail['closeby'];
        $ticket_desc = $close_data_mail['ticket_desc'];
        $created_datetime = $close_data_mail['created_datetime'];
        $status = $close_data_mail['status'];
        $ticketid = "ET00" . $close_data_mail['ticketid'];
        $title = $close_data_mail['title'];
        $customer = $close_data_mail['customer'];
        $ticket_type = $close_data_mail['ticket_type'];
        $allotToName = $close_data_mail['ticket_allot'];
        $customeremailids = $close_data_mail['customeremailids'];
        $sendemailcust = $close_data_mail['sendemailcust'];
        $priorityid = $close_data_mail['priorityid'];
        $note = $close_data_mail['note'];

        $toteam = array($tomail);
        if (!empty($close_data_mail['ccemailids'])) {
            $strCCMailIds = "'" . implode(',', $close_data_mail['ccemailids']) . "'";
        }
        $strBCCMailIds = "sanketsheth@elixiatech.com";
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $subject = "Elixia Speed - Ticket No: " . $ticketid . "(Alloted to - " . $allotToName . " ) - Ticket For: " . ucfirst($ticket_type) . " (Customer No: " . $customer . ")";
        $message = file_get_contents('../emailtemplates/ticketMailTemplate.html');
        $message = str_replace("{{SUBJECT}}", $subject, $message);
        $message = str_replace("{{TICKETID}}", $ticketid, $message);
        $message = str_replace("{{TICKETTITLE}}", $title, $message);
        $message = str_replace("{{TICKETSTATUS}}", get_status($status), $message);
        $message = str_replace("{{TICKETDESC}}", $ticket_desc, $message);
        $message = str_replace("{{NOTE}}", $note, $message);
        if ($status == 2) {
            $close_data = " <tr><td><b>Ticket Closed By :</b></td><td colspan='3'>" . $closeby . "</td></tr>
                            <tr><td><b>Closed date :</b></td><td colspan='3'>" . $created_datetime . "</td></tr>";
            $message = str_replace("{{CLOSEDATA}}", $close_data, $message);
        } else {
            $message = str_replace("{{CLOSEDATA}}", '', $message);
        }
        sendMailUtil($toteam, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);

        if ($sendemailcust == 1 && isset($customeremailids) && !empty($customeremailids)) {
            $to = $customeremailids;
            $subject = "Elixia Speed - Support Ticket No: " . $ticketid;
            sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
        }
    }

    function getnote($ticketid) {
        $db = new DatabaseManager();
        $SQL = sprintf("select  sn.note
                                ,sn.create_on_date 
                                ,t.name
                        from     ". DB_ELIXIATECH .".sp_note sn
                        INNER JOIN  ". DB_ELIXIATECH .".team t ON t.teamid = sn.create_by
                        where   sn.ticketid=" . $ticketid . " ORDER BY sn.noteid DESC");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            $data = "<table><tr><td>Sr No</td><td>Note</td><td>Time</td></tr>";
            $x = 1;
            while ($row = $db->get_nextRow()) {
                $data .= "<tr><td>" . $x . "</td><td>" . $row['note'] . "</td><td>" . $row['create_on_date'] . "</td></tr>";
                $x++;
            }
            $data.="</table>";
        }
        return $data;
    }

    function get_status($statusid) {
        $db = new DatabaseManager();
        $SQL = sprintf("select status from  ". DB_ELIXIATECH .".ticket_status where id=" . $statusid . " LIMIT 1");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $data = $row['status'];
            }
        }
        return $data;
    }

    $createby = $_SESSION["sessionteamid"];

    $message = "";

    if (isset($_POST["editticket"])) {
        $ticketid2 = $_POST["ticketid"];
        $ticket_title = $_POST["title"];
        $customerid = $_POST["ticketcust"];
        $ticket_allot = $_POST["ticket_allot"];
        $ticketdesc = $_POST["ticketdesc"];
        $ticketstatus = $_POST["ticketstatus"];
        $createdby = $_POST["createdby"]; //close mail send to created by mailid
        $expecteddate = $_POST["expecteddate"];
        $ticket_type = $_POST["ticket_type"];
        $sendemailcust = isset($_POST['sendemailcust']) ? $_POST['sendemailcust'] : 0;
        $customeremailids = $_POST['sentoEmail'];
        $ccemailids = $_POST['cc_email'];
        $priorityid = $_POST['priority'];
        $last_allot_to=$ticket_allot;
        $customeremailids = array_filter(explode(",", $customeremailids));

        $ccemailids = array_filter(explode(",", $ccemailids));
        
        if (!empty($expecteddate) && $expecteddate != "1970-01-01") {
            $datetest = date("Y-m-d", strtotime($expecteddate));
            $todaydate1 = $datetest;
        } else {
            $todaydate1 = date("Y-m-d");
        }

        $add_count = 0;
        if ($eclosedate1 != $todaydate1) {
            $add_count = 1;
        }

        if ($ticket_allot == '0' || $ticketstatus == '-1') {
            $message = "Please filled all mandatory fields";
            if ($created_type != 1) {
                header("location : edit_ticket.php?tid=$ticketid2");
            } else {
                header("location : edit_ticket.php?cust=1&tid=$ticketid2");
            }
        }
        if (empty($message)) {
            if ($createdby == -1) {
                $mail_sendto = getUserMail($ticketid2);
            } else {
                $mail_sendto = get_emailid($createdby);
            }
            $custname = get_customername($customerid);
            $createby = get_teamname($createby);
            $created_datetime = Sanitise::DateTime($today);
            $mail_data = array(
                'tomail' => $mail_sendto,
                'closeby' => $createby,
                'customer' => $customerid,
                'ticket_desc' => $ticketdesc,
                'created_datetime' => $created_datetime,
                'status' => $ticketstatus,
                'ticketid' => $ticketid2,
                'title' => $ticket_title,
                'ticket_type' => $ticket_type,
                'ticket_allot' => get_teamname($ticket_allot),
                'customeremailids' => $customeremailids,
                'sendemailcust' => $sendemailcust,
                'priorityid' => $priorityid,
                'ccemailids' => $ccemailids,
                'note' => getnote($ticketid2)
            );
            send_ticket_mail($mail_data);
            $tm=new TeamManager();
            $obj=new stdClass();
            $obj->priorityid=$priorityid;
            $obj->sendemailcust=$sendemailcust;
            $obj->customerid=$customerid;
            $obj->eclosedate=$todaydate1;
            $obj->addcount=$add_count;
            $obj->ccemailid=$ccemailids;
            $obj->ticketid=$ticketid2;
            $obj->created_type =$created_type;
            $obj->ticketdesc=$ticketdesc;
            $obj->ticketstatus=$ticketstatus;
            $obj->allotFrom=Sanitise::Long($_SESSION["sessionteamid"]);
            $obj->allotTo=$ticket_allot;
            $obj->createdby=$createdby;
            $obj->today=$created_datetime;
            $obj->docketId=0;
            $obj->prodId=0;
            $tm->updateTicket($obj);
        }
    }

    if (isset($ticket_id) && isset($customerid)) {
        $fileurl = "../../customer/" . $customerid . "/support/" . $ticket_id . "_support.zip";
    }

    $_scripts_custom[] = "../../scripts/autocomplete/jquery-ui.min.js";
    $_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";
    include("header.php");
    ?>

    <style>
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
        <div class="paneltitle" align="center">Edit <?php
            if ($customerview == 1) {
                echo "Customer";
            }
            ?> Ticket</div>
        <div class="panelcontents">
            <form method="post"  name="editticketform" id="editticketform" onsubmit="return ValidateForm();
                        return false;">
                      <?php echo "<span style='color:red; font-size:12px;'>" . $message . "</span>"; ?>
                <table width="100%" border="1">
                    <tr>
                        <td><b>Ticket Id :</b></td><td><?php echo $ticket_id; ?><input type="hidden" id="ticketid" name="ticketid" value="<?php echo $ticketid; ?>"></td> 
                        <td><b>Ticket Type :</b></td><td><?php echo $ticket_type; ?><input type="hidden" id="ticket_type" name="ticket_type" value="<?php echo $ticket_type; ?>"></td>
            <!--            <td><b>Issue :</b></td><td colspan="3"><?php
                        if ($sub_ticket_issue == "") {
                            echo"-";
                        } else {
                            echo $sub_ticket_issue;
                        }
                        ?></td>-->
                    </tr>
                    <tr>
                        <td>
                            <b>Expected Closure date</b> :</td><td><input type="text" name="expecteddate" id="expecteddate" placeholder="dd-mm-yyyy" value="<?php echo $eclosedate; ?>">
                        </td>
                    </tr>
                    <td><b>Priority : </b></td><td><?php echo $priority; ?></td>
                    <td><b> Created By :</b></td><td><?php
                        if ($created_type == 0) {
                            $creatername = get_teamname($create_by);
                        } else {
                            $creatername = get_customername($customerid);
                        }
                        echo $creatername;
                        ?> <input type="hidden" name="createdby" id="createdby" value="<?php echo $create_by; ?>"></td>
                    <td><b>Created Date :</b></td><td><?php echo $create_on_date; ?></td>
                    </tr>
                    <tr>
                        <td colspan="1"><b>Assign to :</b></td>
                        <td colspan="3"> 
                            <select id="ticket_allot" name="ticket_allot">
                                <option value="0" selected>Select Member</option>
                                <?php
                                foreach ($team_allot_array as $teamallot) {
                                    ?>
                                    <option value="<?php echo $teamallot->teamid; ?>" <?php
                                    if ($last_allot_to == $teamallot->teamid) {
                                        echo "selected='selected'";
                                    }
                                    ?>><?php echo $teamallot->name; ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                        </td>
                        <td colspan="1"><b>Status :</b></td>
                        <td colspan="3">
                            <select name="ticketstatus" id="ticketstatus">
                                <option value="-1">Select Status</option>
                                <?php
                                foreach ($tstatus as $data) {
                                    if ($ticketstatus == $data['id']) {
                                        echo '<option value=' . $data['id'] . ' selected="selected">' . $data['status'] . '</option>';
                                    } else {
                                        echo '<option value="' . $data['id'] . '">' . $data['status'] . '</option>';
                                    }
                                }
                                ?>
                            </select>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"><b>Send email to customer :</b></td>
                        <td colspan="2"> 

                            <input type="checkbox" name="sendemailcust" id="sendmailcust" onclick="checksendemail()" value="1" checked>
                            <input type="text" name="customeremailids" style="width:250px;" id="customeremailids" placeholder="Enter email id" onkeyup="getmailids()" >  
                            <input type='hidden' name='sentoEmail' id="sentoEmail" value="<?php
                            foreach ($e_array as $mail) {
                                echo $mail['emailid'] . ',';
                            }
                            ?>"/>
                            <br>

                            <div id="listemailids" ></div>

                        </td>
                        <td colspan="1"><b>Priority :</b></td>
                        <td colspan="2">
                            <select name="priority" id="priority">
                                <option value="0">Select</option>
                                <?php
                                if (isset($priorityarr)) {
                                    foreach ($priorityarr as $row) {
                                        ?>
                                        <option value="<?php echo $row['prid']; ?>"
                                        <?php
                                        if ($row['prid'] == $prid) {
                                            echo "selected";
                                        }
                                        ?>         
                                                ><?php echo $row['priority']; ?></option>   
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>

                        </td>
                    </tr>


                    <tr>
                        <td colspan="1"> <b>CC Email Id :</b></td>
                        <td colspan="7">
                            <input  type="text" name="ccmail" id="ccmail" size="20" value="" autocomplete="off" placeholder="Enter email id"  onkeyup="getccmailid()"/>
                            <input  type="hidden" name="cc_email"  id="cc_email" size="20" value="<?php
                            foreach ($cc_array as $mail) {
                                echo $mail['emailid'] . ',';
                            }
                            ?>">
                            <br>
                            <div id="listvehicle2"> </div> 
                        </td>
                    </tr>


                    <tr><td colspan="1"> <b>Customer :</b></td>
                        <td colspan="7">
                            <select name="ticketcust" id="ticketcust" style="width: 200px; float:left;" >

                                <?php
                                if ($customerview == 1) {
                                    echo"<option value=" . $customerid . ">" . get_customername($customerid) . "</option>";
                                } else {
                                    echo '<option value="0">Select a Customer</option>';
                                    foreach ($customer as $thiscustomer) {
                                        ?>
                                        <option value="<?php echo($thiscustomer->customerno); ?>" <?php
                                        if ($customerid == $thiscustomer->customerno) {
                                            echo "selected='selected'";
                                        }
                                        ?>   ><?php echo($thiscustomer->customername); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>
                        </td></tr>
                    <tr>
                        <td colspan="1"> <b>Title :</b></td>
                        <td colspan="7"><?php echo $title; ?> <input type="hidden" id="title" name="title" value="<?php echo $title; ?>"/></td>
                    </tr>
                    <?php
                    if ($customerview == 1) {
                        $userdetail = get_userdetails($ticketcreater);
                        if (isset($userdetail)) {
                            $createremail = isset($userdetail[0]["email"]) ? $userdetail[0]["email"] : "";
                            $createrrealname = $userdetail[0]["realname"];
                        }
                        ?>
                        <tr><td colspan="1"> <b>Customer Ticket Raised by :</b></td><td colspan="7"><?php echo $createrrealname . " (" . $createremail . ")"; ?></td></tr>
                    <?php } ?>

                    <?php
                    if ($customerview == '1' && !empty($_SESSION["sessionteamrid"])) {
                        ?>
                        <tr><td colspan="1"> <b>Expected Closed Date :</b></td><td colspan="7"><input type="text" name="expecteddate" id="expecteddate" placeholder="dd-mm-yyyy" value="<?php echo $eclosedate; ?>"></td></tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="1"><b>Description :</b></td><td colspan="4"><textarea <?php
                            if ($ticketstatus == '2') {
                                echo "disabled";
                            }
                            ?> name="ticketdesc" id="ticketdesc" style="width:300px"><?php echo $description; ?></textarea></td>
                                <?php
                                if (isset($fileurl)) {
                                    if (file_exists($fileurl)) {
                                        ?>
                                <td colspan="1"><b>Attachments :</b></td><td colspan="2"><center><a target="_blank" href="<?php echo $fileurl; ?>">Download File</a></center></td>
                            <?php
                        }
                    }
                    ?>
                    </tr>
                    <tr>
                        <td colspan="1"> &nbsp;</td><td colspan="7"><?php if ($ticketstatus != '2') { ?><input type="submit" name="editticket" id="editticket" value="Edit Ticket"><?php } ?></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <br/>

    <div class="panel">
        <div class="paneltitle" align="center">Add Note</div>
        <br>
        <table>
            <tr>
                <td width="10%">
                    <b>Add New Note :</b>
                </td>
                <td  colspan="6">
                    <textarea name="addnote" id="addnote" style="width: 400px; height:60px;" required></textarea>
                </td>

            <input type="hidden" value="<?php echo $last_allot_to; ?>" name="lastallotedid" id="lastallotedid"/>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="button" name="add_note" id="add_note" value="Add Note" onclick="add_note();">
                </td>
            </tr>
        </table>
    </div>
    <!-- Add note here  -->
    <br/>
    <div class="panel">
        <div class="paneltitle" align="center"><?php
            if ($customerview == 1) {
                echo "Customer";
            }
            ?> Notes History</div>
        <div class="panelcontents">
            <table id='notes_list' border=1 width="100%">
            <?php
            //$tm=new TeamManager();
            $arrResult = getnote($ticket_id);
            ?>   
            </table>    
            <br>

        </div>
    </div>
    <br/>
    <div class="panel">
        <div class="paneltitle" align="center"><?php
            if ($customerview == 1) {
                echo "Customer";
            }
            ?> Ticket Details</div>
        <div class="panelcontents">
            <table width="100%" border="1">
                <tr>
                    <th>Sr No.</th>
                    <th>Description</th>
                    <th>   Allot From => Allot To </th>
                    <th>Status</th>
                    <th>Last Modified</th>
                </tr>
                <?php
                $db = new DatabaseManager();
                $SQL = sprintf("select  uid
                                        ,ticketid
                                        ,description
                                        ,allot_from
                                        ,allot_to
                                        ,create_by
                                        ,created_type
                                        ,create_on_time
                                        ,(CASE  WHEN status= 1 THEN 'Inprogress' 
                                                WHEN status= 2 THEN 'Closed' 
                                                WHEN status= 3 THEN 'Pipeline' 
                                                WHEN status= 4 THEN 'On Hold' 
                                                WHEN status= 5 THEN 'Waiting for Client' 
                                                WHEN status= 6 THEN 'Resolved' 
                                                ELSE 'Open' END
                                            ) as ticketstatus 
                                FROM     ". DB_ELIXIATECH .".sp_ticket_details 
                                WHERE   ticketid=" . $ticketid . " order by uid desc");
                $db->executeQuery($SQL);
                $i = 1;
                while ($row = $db->get_nextRow()) {

                    $uid = $row["uid"];
                    $ticketid = $row["ticketid"];
                    $description = $row["description"];
                    $allot_to = $row["allot_to"];
                    $status = $row["ticketstatus"];
                    $allot_from = $row["allot_from"];
                    $create_type = $row["created_type"];
                    $create_on_date1 = $row["create_on_time"];
                    $create_ondate = date('d-m-Y H:i:s', strtotime($create_on_date1));

                    if ($create_type != 1) {
                        $teamname = get_teamname($allot_from);
                    } else {
                        $teamname = get_customername($customerid);
                    }
                    ?>    
                    <tr style="text-align: center;">
                        <td><?php echo $i; ?></td>
                        <td><?php echo $description; ?></td>
                        <td><?php
                            echo $teamname . "&nbsp;&nbsp;=>&nbsp;&nbsp; ";
                            ?>  
                            <?php echo get_teamname($allot_to); ?></td>
                        <td><?php echo $status; ?></td>
                        <td><?php echo $create_ondate; ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </table>
        </div>
    </div>

    <?php
    include("footer.php");
}
?>

<script>
    $(document).ready(function () {

        $('#expecteddate').datepicker({
            dateFormat: "dd-mm-yy",
            language: 'en',
            autoclose: 1,
            startDate: Date()

        });

<?php foreach ($e_array as $mail) { ?>
            var div = document.createElement('div');
            div.id = "contain";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';

            remove_image.onclick = function () {
                removeEmailDiv('<?php echo $mail['emailid']; ?>', <?php echo $mail['id']; ?>);
            };

            div.className = 'recipientbox';
            div.id = 'to_email_div_' + <?php echo $mail['id']; ?>;
            div.innerHTML = '<span>' + <?php echo "'" . $mail['emailid'] . "'"; ?> + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + <?php echo $mail['id']; ?> + '" value="' + <?php echo $mail['id']; ?> + '"/>';
            jQuery("#listemailids").append(div);
            jQuery(div).append(remove_image);
<?php } ?>

<?php foreach ($cc_array as $mail) { ?>
            var div = document.createElement('div');
            div.id = "contain";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';

            remove_image.onclick = function () {
                removeCCmailDiv('<?php echo $mail['emailid']; ?>', <?php echo $mail['id']; ?>);
            };

            div.className = 'recipientbox';
            div.id = 'cc_email_div_' + <?php echo $mail['id']; ?>;
            div.innerHTML = '<span>' + <?php echo "'" . $mail['emailid'] . "'"; ?> + '</span><input type="hidden" class="v_list_element" name="ed_vehicle_' + <?php echo $mail['id']; ?> + '" value="' + <?php echo $mail['id']; ?> + '"/>';
            jQuery("#listvehicle2").append(div);
            jQuery(div).append(remove_image);
<?php } ?>  displayNotes();
    });

    function getmailids() {
        var data = '';
        data = jQuery('#ticketcust').val();
        jQuery("#customeremailids").autocomplete({
            source: "route_ajax.php?work=getmail&customerno=" + data,
            select: function (event, ui) {
                insertEmailDiv(ui.item.value, ui.item.eid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }
    function getccmailid() {
        var data = '';
        data = jQuery('#ticketcust').val();
        jQuery("#ccmail").autocomplete({
            source: "route_ajax.php?work=getmail&customerno=" + data,
            select: function (event, ui) {
                insertCCEmailDiv(ui.item.value, ui.item.eid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }
    function insertEmailDiv(selected_name, eid) {
        $("#sentoEmail").val(function (i, val) {
            if (!val.includes(selected_name)) {
                return val + (!val ? '' : ',') + selected_name;
            }
            else {
                return val;
            }
        });

        if (eid != "" && jQuery('#to_email_div_' + eid).val() == null) {
            var div = document.createElement('div');
            div.id = "contain";

            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';

            remove_image.onclick = function () {
                removeEmailDiv(selected_name, eid);
            };

            div.className = 'recipientbox';
            div.id = 'to_email_div_' + eid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicle_' + eid + '" value="' + eid + '"/>';
            jQuery("#listemailids").append(div);
            jQuery(div).append(remove_image);
        }
    }

    function removeEmailDiv(name, eid) {
        var rep = "," + name;
        $("#sentoEmail").val($("#sentoEmail").val().replace(rep, ""));
        $("#sentoEmail").val($("#sentoEmail").val().replace(name, ""));
        $('#to_email_div_' + eid).remove();
        console.log($("#sentoEmail").val());
    }
    function insertCCEmailDiv(selected_name, vehicleid) {
        $("#cc_email").val(function (i, val) {
            if (!val.includes(selected_name)) {
                return val + (!val ? '' : ',') + selected_name;
            }
            else {
                return val;
            }
        });

        if (vehicleid != "" && jQuery('#cc_email_div_' + vehicleid).val() == null) {
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';
            remove_image.onclick = function () {
                removeCCmailDiv(selected_name, vehicleid);
            };
            div.className = 'recipientbox';
            div.id = 'cc_email_div_' + vehicleid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="ed_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
            jQuery("#listvehicle2").append(div);
            jQuery(div).append(remove_image);
        }
    }

    function removeCCmailDiv(name, vehicleid) {
        var rep = "," + name;
        $("#cc_email").val($("#cc_email").val().replace(rep, ""));
        $("#cc_email").val($("#cc_email").val().replace(name, ""));
        jQuery('#cc_email_div_' + vehicleid).remove();
    }

    function ValidateForm() {
        var ticketdesc = $("#ticketdesc").val();
        var ticket_allot = $("#ticket_allot").val();
        var status = $("#ticketstatus").val();
        var customeremailids = $("#sentoEmail").val();
        if (ticket_allot == "0") {
            alert("Please select allot to");
            return false;
        } else if (ticketdesc == '') {
            alert("Please enter description");
            return false;
        } else if (status == "-1") {
            alert("Please select Status");
            return false;
        } else if (($("#sendmailcust").prop('checked') == true) && customeremailids == '') {
            alert("Please enter email id");
            return false;
        } else {
            $("#editticketform").submit();
        }
    }


    function checksendemail() {
        if ($("#sendmailcust").is(':checked')) {
            $("#customeremailids").prop('disabled', false);
        }
        else {
            $("#customeremailids").val("");
            $("#customeremailids").prop('disabled', true);
        }
    }
    function add_note() {
        var note = jQuery('#addnote').val();
        if (note == '') {
            alert('Enter Note');
            return false;
        } else {
            var ticketid = jQuery('#ticketid').val();
            var data = "add_note=" + note + "&ticket_note=" + ticketid;
            jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: data,
                cache: false,
                success: function (json) {
                    if (json == 'Success') {
                        alert("Note added successfully");
                        var Table = document.getElementById("notes_list");
                        Table.innerHTML = "";
                        $('#addnote').val('');
                        displayNotes();
                    } else if (json == 'Logout') {
                        alert("User Logout");
                    } else if (json == 'wrongticket') {
                        alert("Wrong Ticket");
                    }
                    //window.location.reload(true);
                }
            });
        }
    }
    function displayNotes(json){
        var prodId=<?php echo speedConstants::PRODUCT_ID;?>;
        var ticketId=<?php echo $ticketid;?>;
        jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: "pullNotes=1&ticketid="+ticketId,
                cache: false,
                success: function (json) {
                    if (json.length > 0) {
                    var count = 0;
                    var trHTML = '<tr><th>NOTE ID</th><th>NOTE</th><th>CREATED BY</th></tr>';
                    var note=JSON.parse(json);
                    $.each(note, function (i, item) {
                        trHTML+='<tr><td style="text-align: center;">'+item.noteid+'</td>';
                        trHTML+='<td style="text-align: center;">'+item.note+'</td>';
                        trHTML+='<td style="text-align: center;">'+item.create_by+'</td>';
                        trHTML+='</tr>';
                    });
                    $('#notes_list').append(trHTML);
                    $('#dataTable th').css({'background': 'white', 'border-color': 'black', 'font-weight': 'bold'});
                    $('#dataTable td').css({'border-color': 'black'});
                } else {
                    $('#historyTable').html('History not available');
                }
                    //window.location.reload(true);
                }
            });
    }
</script>
<script src='../../scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>