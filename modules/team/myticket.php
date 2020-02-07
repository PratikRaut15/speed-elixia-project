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

class testing {
    
}

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$todaydate = date("Y-m-d");

$db = new DatabaseManager();
$SQL = sprintf("SELECT  customerno
                        , customercompany 
                FROM    " . DB_ELIXIATECH . ".customer");
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

$SQL = sprintf("SELECT  team.teamid
                        , team.name 
                FROM    " . DB_ELIXIATECH . ".team 
                where   member_type=1 
                ORDER BY name asc");
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

$createby = $_SESSION["sessionteamid"];
$userteamid = $_SESSION["sessionteamid"];

// See if we need to save a new one.
include("header.php");

//functions 

function get_customername($id) {
    $db = new DatabaseManager();
    $SQL = sprintf("select  customercompany 
                    from    " . DB_ELIXIATECH . ".customer 
                    where   customerno = %d", Sanitise::Long($id));
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $customercompany = $row["customercompany"];
    }
    return $customercompany;
}

function get_teamname($id) {
    $db = new DatabaseManager();
    $SQL = sprintf("select  name 
                    from    " . DB_ELIXIATECH . ".team 
                    where   teamid = %d", Sanitise::Long($id));
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $teamname = $row["name"];
    }
    return $teamname;
}

function last_assign_to($ticketid) {
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  allot_to AS last_allot_to
                        ,(CASE  WHEN status=1 THEN 'Inprogress' 
                                WHEN status= 2 THEN 'Closed' 
                                WHEN status= 3 THEN 'Pipeline' 
                                WHEN status= 4 THEN 'On Hold' 
                                WHEN status= 5 THEN 'Waiting for Client' 
                                WHEN status= 6 THEN 'Resolved' 
                                ELSE 'Open' END)as ticketstatus 
                        FROM " . DB_ELIXIATECH . ".`sp_ticket_details` 
                where ticketid=" . $ticketid . " 
                ORDER BY `sp_ticket_details`.`uid`  DESC 
                limit 1");
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $last_allot_to = $row["last_allot_to"];
    }
    $last_assign_name = get_teamname($last_allot_to);
    return $last_assign_name;
}

function last_assign_id($ticketid) {
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  allot_to as last_allot_to
                            ,(CASE  WHEN status=1 THEN 'Inprogress' 
                                    WHEN status= 2 THEN 'Closed' 
                                    WHEN status= 3 THEN 'Pipeline' 
                                    WHEN status= 4 THEN 'On Hold' 
                                    WHEN status= 5 THEN 'Waiting for client' 
                                    WHEN status= 6 THEN 'Resolved' 
                                    WHEN status= 7 THEN 'Reopen'  
                                    ELSE 'Open' END)as ticketstatus 
                    FROM    " . DB_ELIXIATECH . ".`sp_ticket_details` 
                    where   ticketid = %d 
                    ORDER BY `sp_ticket_details`.`uid`  DESC 
                    limit 1", Sanitise::Long($ticketid));
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $last_allot_to = $row["last_allot_to"];
    }
    //$last_assign_name = get_teamname($last_allot_to);
    return $last_allot_to;
}

function last_assign_status($ticketid) {
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  allot_to as last_allot_to
                            ,(CASE  WHEN status = 1 THEN 'Inprogress' 
                                    WHEN status = 2  THEN 'Closed'  
                                    WHEN status = 3 THEN 'Pipeline' 
                                    WHEN status = 4 THEN 'On Hold' 
                                    WHEN status = 5 THEN 'Waiting for Client' 
                                    WHEN status = 6 THEN 'Resolved' 
                                    WHEN status = 7 THEN 'Reopen' 
                                    ELSE  'Open' END)as ticketstatus 
                    FROM    " . DB_ELIXIATECH . ".`sp_ticket_details` 
                    where   ticketid = %d 
                    ORDER BY `sp_ticket_details`.`uid` DESC 
                    limit   1", Sanitise::Long($ticketid));
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $ticketstatus = $row["ticketstatus"];
    }

    return $ticketstatus;
}

function last_assign_status_id($ticketid) {
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  allot_to as last_allot_to
                            ,status 
                    FROM    " . DB_ELIXIATECH . ".`sp_ticket_details` 
                    where   ticketid = %d 
                    ORDER BY `sp_ticket_details`.`uid` DESC 
                    limit   1", Sanitise::Long($ticketid));
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $ticketstatus = $row["status"];
    }

    return $ticketstatus;
}
?>


<br/>
<div class="panel">
    <div class="paneltitle" align="center">Tickets Assigned - By Customers</div>
    <div class="panelcontents">
        <?php
        $crmid = $_SESSION["sessionteamrid"];
        $db = new DatabaseManager();

        if ($crmid == "") {
            $SQL = sprintf("select  * 
                    from    ( select    (CASE WHEN stde.status=1 THEN 'Inprogress' 
                                            WHEN stde.status= 2 THEN 'Closed' 
                                            WHEN stde.status= 3 THEN 'Reopen' 
                                            ELSE 'Open' END)as ticketstatus
                                        , stde.uid,st.ticketid
                                        , st.title
                                        , st.ticket_type
                                        , sttype.tickettype
                                        , st.sub_ticket_issue
                                        , st.customerid
                                        , st.eclosedate
                                        , st.priority
                                        , sp.priority as prname
                                        , st.create_on_date
                                        , st.create_by
                                        , stde.status,stde.allot_to 
                            from    " . DB_ELIXIATECH . ".sp_ticket_details stde 
                            left join " . DB_ELIXIATECH . ".sp_ticket as st on st.ticketid = stde.ticketid 
                            left join " . DB_ELIXIATECH . ".sp_tickettype as sttype on sttype.typeid = st.ticket_type 
                            left join " . DB_ELIXIATECH . ".sp_priority as sp on sp.prid = st.priority  
                            order by stde.uid desc ) as main 
                    group by main.ticketid having main.allot_to=" . $userteamid . " AND main.create_by=-1 AND main.status IN (0,1,3) 
                    order by main.ticketid desc");
        } else {
            $SQL = sprintf("select  * 
                            from    (select (CASE   WHEN stde.status=1 THEN 'Inprogress' 
                                                    WHEN stde.status= 2 THEN 'Closed' 
                                                    WHEN stde.status= 3 THEN 'Pipeline' 
                                                    WHEN stde.status= 4 THEN 'On Hold' 
                                                    WHEN stde.status= 5 THEN 'Waiting for client' 
                                                    WHEN stde.status= 6 THEN 'Resolved' 
                                                    WHEN stde.status= 7 THEN 'Reopen' 
                                                    ELSE 'Open' END)as ticketstatus
                                            , stde.uid,st.ticketid
                                            , st.title
                                            , st.ticket_type
                                            , sttype.tickettype
                                            , st.created_type
                                            , st.sub_ticket_issue
                                            , st.customerid
                                            , st.eclosedate
                                            , st.priority
                                            , sp.priority as prname
                                            , st.create_on_date
                                            , st.create_by
                                            , stde.status
                                            , stde.allot_to 
                                    from    " . DB_ELIXIATECH . ".sp_ticket_details stde 
                                    left join " . DB_ELIXIATECH . ".sp_ticket as st on st.ticketid = stde.ticketid 
                                    left join " . DB_ELIXIATECH . ".sp_tickettype as sttype on sttype.typeid = st.ticket_type 
                                    left join " . DB_ELIXIATECH . ".sp_priority as sp on sp.prid = st.priority  
                                    order by stde.uid desc ) as main 
                            group by main.ticketid 
                            having  main.created_type=1 
                            AND     main.create_by = -1 
                            AND     main.status IN (0,1,3) 
                            order by main.ticketid desc");
        }

        $db->executeQuery($SQL);
        ?>

        <table border="1" width="100%">
            <tr>
                <th>Ticket Id</th>
                <th>Title</th>
                <th>Ticket Type</th>
        <!--        <th>Issue</th>-->
                <th>Assign To</th>
                <th>Status</th>
                <th>Customer</th>
                <th>Expected Close date</th>
                <th>Priority</th>
                <th>Create On</th>
                <!--<th>Create By</th>-->
                <th>Edit</th>
            </tr>
            <?php
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $ticketid = $row["ticketid"];
                    $title = $row["title"];
                    $ticket_type = $row["tickettype"];
                    //$sub_ticket_issue = $row["sub_ticket_issue"];
                    $customerid = $row["customerid"];
                    $eclosedate1 = $row["eclosedate"];
                    $priority = $row["prname"];
                    $create_on_date1 = $row["create_on_date"];
                    $create_by = $row["create_by"];
                    $create_on_date = date('d-m-Y H:i:s', strtotime($create_on_date1));
                    $eclosedate = date('d-m-Y', strtotime($eclosedate1));

                    if ($priority == "High") {
                        $color = "orange";
                        $fontcolor = "white";
                    } elseif ($priority == "Medium") {
                        $color = "yellow";
                        $fontcolor = "black";
                    } elseif ($priority == "Low") {
                        $color = "green";
                        $fontcolor = "white";
                    } elseif($priority == "Very Low") {
                        $color = "grey";
                        $fontcolor = "black";
                    }elseif ($priority == "Very High") {
                        $color = "red";
                        $fontcolor = "black";
                    }  else {
                        $color = "none";
                        $fontcolor = "none";
                    }

                    if ($sub_ticket_issue == "") {
                        $sub_ticket_issue1 = '-';
                    } else {
                        $sub_ticket_issue1 = $sub_ticket_issue;
                    }

                    $last_id = last_assign_id($ticketid);
//if($last_id==$userteamid){
//    $last_status = last_assign_status_id($ticketid);
//    if($last_status!="2"){
                    ?>
                    <tr style="text-align: center;">
                        <td><?php echo $ticketid; ?></td>
                        <td><?php echo $title; ?></td>
                        <td><?php echo $ticket_type; ?></td>
        <!--                    <td><?php // echo $sub_ticket_issue1; ?></td>-->
                        <td><?php echo last_assign_to($ticketid); ?></td>
                        <td><?php echo last_assign_status($ticketid); ?></td>
                        <td><?php echo get_customername($customerid); ?></td>
                        <td><?php echo $eclosedate; ?></td>
                        <td style="background-color: <?php echo $color; ?>; color:<?php echo $fontcolor; ?>"><?php echo $priority; ?></td>
                        <td><?php echo $create_on_date; ?></td>
        <!--                    <td><?php // echo get_teamname($create_by); ?></td>-->
                        <td><a href="editTicket.php?ticketid=<?php echo $ticketid; ?>">Edit</a></td>
                    </tr>
                    <?php
//    }
//}
                }
            } else {
                echo"<tr><td colspan='12' style='text-align:center;'><b>No Tickets</b></td></tr>";
            }
            ?>
        </table>
    </div>
</div>
<br/>
<div class="panel">
    <div class="paneltitle" align="center">Tickets Assigned - By Elixir</div>
    <div class="panelcontents">
        <?php
        $db = new DatabaseManager();
        $SQL = sprintf("select * from ( select  (CASE   WHEN stde.status=1 THEN 'Inprogress' 
                                                WHEN stde.status= 2 THEN 'Closed' 
                                                WHEN stde.status= 3 THEN 'Pipeline' 
                                                WHEN stde.status= 4 THEN 'On Hold' 
                                                WHEN stde.status= 5 THEN 'Waiting for client' 
                                                WHEN stde.status= 6 THEN 'Resolved' 
                                                WHEN stde.status= 7 THEN 'Reopen' 
                                                ELSE 'Open' END)as ticketstatus
                                        , stde.uid,st.ticketid
                                        , st.title
                                        ,st.ticket_type
                                        ,sttype.tickettype
                                        ,st.sub_ticket_issue
                                        ,st.customerid
                                        ,st.eclosedate
                                        ,st.priority
                                        ,sp.priority as prname
                                        ,st.create_on_date
                                        ,st.create_by
                                        ,stde.status,stde.allot_to 
                                from " . DB_ELIXIATECH . ".sp_ticket_details stde 
                                left join " . DB_ELIXIATECH . ".sp_ticket as st on st.ticketid = stde.ticketid 
                                left join " . DB_ELIXIATECH . ".sp_tickettype as sttype on sttype.typeid = st.ticket_type 
                                left join " . DB_ELIXIATECH . ".sp_priority as sp on sp.prid = st.priority   
                                order by stde.uid desc ) as main 
                group by main.ticketid having main.allot_to= %d 
                AND     main.create_by<> -1 
                AND     main.status <> 2 
                order by main.eclosedate asc, main.priority asc, main.ticketid asc", Sanitise::Long($userteamid));

        $db->executeQuery($SQL);
        ?>

        <table border="1" width="100%">
            <tr>
                <th>Ticket Id</th>
                <th>Title</th>
                <th>Ticket Type</th>
        <!--        <th>Issue</th>-->
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
            if ($db->get_rowCount() > 0) {
                while ($row = $db->get_nextRow()) {
                    $ticketid = $row["ticketid"];
                    $title = $row["title"];
                    $ticket_type = $row["tickettype"];
                    //  $sub_ticket_issue = $row["sub_ticket_issue"];
                    $customerid = $row["customerid"];
                    $eclosedate1 = $row["eclosedate"];
                    $priority = $row["prname"];
                    $create_on_date1 = $row["create_on_date"];
                    $create_by = $row["create_by"];
                    $create_on_date = date('d-m-Y H:i:s', strtotime($create_on_date1));
                    $eclosedate = date('d-m-Y', strtotime($eclosedate1));

                    if ($priority == "High") {
                        $color = "orange";
                        $fontcolor = "white";
                    } elseif ($priority == "Medium") {
                        $color = "yellow";
                        $fontcolor = "black";
                    } elseif ($priority == "Low") {
                        $color = "green";
                        $fontcolor = "white";
                    } elseif($priority == "Very Low") {
                        $color = "grey";
                        $fontcolor = "black";
                    }elseif ($priority == "Very High") {
                        $color = "red";
                        $fontcolor = "black";
                    }  else {
                        $color = "none";
                        $fontcolor = "none";
                    }

                    if ($sub_ticket_issue == "") {
                        $sub_ticket_issue1 = '-';
                    } else {
                        $sub_ticket_issue1 = $sub_ticket_issue;
                    }

                    $last_id = last_assign_id($ticketid);
//if($last_id==$userteamid){
//    $last_status = last_assign_status_id($ticketid);
//    if($last_status!="2"){
                    ?>
                    <tr style="text-align: center;">
                        <td><?php echo $ticketid; ?></td>
                        <td><?php echo $title; ?></td>
                        <td><?php echo $ticket_type; ?></td>
        <!--                    <td><?php //echo $sub_ticket_issue1; ?></td>-->
                        <td><?php echo last_assign_to($ticketid); ?></td>
                        <td><?php echo last_assign_status($ticketid); ?></td>
                        <td><?php echo get_customername($customerid); ?></td>
                        <td><?php echo $eclosedate; ?></td>
                        <td style="background-color: <?php echo $color; ?>; color:<?php echo $fontcolor; ?>"><?php echo $priority; ?></td>
                        <td><?php echo $create_on_date; ?></td>
                        <td><?php echo get_teamname($create_by); ?></td>
                        <td><a href="editTicket.php?ticketid=<?php echo $ticketid; ?>">Edit</a></td>
                    </tr>
                    <?php
//    }
//}
                }
            } else {
                echo"<tr><td colspan='12' style='text-align:center;'><b>No Tickets</b></td></tr>";
            }
            ?>
        </table>
    </div>
</div>
<br/>

<br>
<?php
include("footer.php");
?>

<script>

    function reopen_ticket(id, status) {
        if (status == 1) {
            var vv = "cust=1&";
        } else {
            vv = "";
        }

        var rconf = confirm("Are you sure you want to open ticket");
        if (rconf == true) {
            $.ajax({
                type: "POST",
                url: "ticket_ajax.php",
                data: 'ticketid=' + id,
                success: function (msg) {
                    if (msg == 'sucess') {
                        //alert(msg);
                        window.location = "editTicket.php?&ticketid=" + id;
                    }
                }
            });
        }

    }




    $("#tickettype").change(function () {
        var tickettype = $("#tickettype").val();
        if (tickettype == "operation") {
            $("#operations").css("display", "block");
            $("#software").css("display", "none");
        }
        else if (tickettype == "software") {
            $("#software").css("display", "block");
            $("#support").css("display", "none");
            $("#accounts").css("display", "none");
            $("#operations").css("display", "none");
        } else {
            $("#operations").css("display", "none");
            $("#software").css("display", "none");
        }
    });


    $(document).ready(function () {

        $('#expecteddate').datepicker({
            format: "dd-mm-yyyy",
            language: 'en',
            autoclose: 1,
            startDate: Date()

        });

    });

    function ValidateForm() {
        var ticket_title = $("#ticket_title").val();
        var tickettype = $("#tickettype").val();
        var temail = $("#temail").val();
        var role = $("#role").val();
        var tlogin = $("#tlogin").val();
        var tpassword = $("#tpassword").val();
        var ticketcust = $("#ticketcust").val();
        var ticketdesc = $("#ticketdesc").val();
        var ticket_allot = $("#ticket_allot").val();
        var priority = $("#priority").val();
        if (ticket_title == "") {
            alert("Please enter ticket title.");
            return false;
        } else if (tickettype == "0") {
            alert("Please select Ticket type.");
            return false;
        } else if (tickettype != "0") {
            if (tickettype == "operation") {
                var operations = $("#operations").val();
                if (operations == '0') {
                    alert("Please select operations");
                    return false;
                }

            } else if (tickettype == "software") {
                var software = $("#software").val();
                if (software == '0') {
                    alert("Please select software options.");
                    return false;
                }
            }
        } else if (ticketcust == "0") {
            alert("Please select customer");
            return false;
        } else if (ticketdesc == "") {
            alert("Please enter ticket description");
            return false;
        } else if (ticket_allot == "0") {
            alert("Please select allot to");
            return false;
        } else if (expecteddate == "") {
            alert("Please select date");
            return false;
        } else if (priority == "0") {
            alert("Please select Priority");
            return false;
        } else {
            $("#ticketform").submit();
        }

    }

    function checkEmail() {
        var ticketmailid = $("#ticketmailid").val();
        var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
        var sendticketmail = document.getElementById("sendticketmail").checked = false;
        if (sendticketmail == false) {
            alert("Please check send mail checkbox");
            return false;
        } else if (pattern.test(ticketmailid)) {
            return true;
        } else {
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
            else {
                return true;
            }
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