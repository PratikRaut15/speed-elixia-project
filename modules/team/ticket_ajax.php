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
if (isset($_POST["ticketid"])) {
    $ticketid = $_POST["ticketid"];
    $status = 7;
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT * FROM " . DB_PARENT . ".`sp_ticket_details` where ticketid=" . $ticketid . " ORDER BY `sp_ticket_details`.`uid`  DESC limit 1");
    $db->executeQuery($SQL);
    
    while ($row = $db->get_nextRow()) {
        $desc = $row["description"];
        $createby = $row["allot_to"];
    }

    $sql = sprintf("INSERT INTO " . DB_PARENT . ".`sp_ticket_details`(
    `ticketid` ,
    `description`,
    `allot_to`,
    `status`,
    `create_by`,
    `create_on_time` 
    )
    VALUES (
     '%d', '%s', '%d', '%d','%d','%s'
    );", $ticketid, $desc, $createby, $status, $createby, Sanitise::DateTime($today));
    
    $db->executeQuery($sql);
    
    echo "sucess";
    exit;
}
//get crm names
//get customer wise vehicle details
if (isset($_POST['cno'])) {
    $cust_no = GetSafeValueString($_POST['cno'], "string");
    $info = Array();
    $SQL = "SELECT * FROM vehicle WHERE customerno =$cust_no AND isdeleted=0";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $inc = new testing();
            $inc->vehicleid = $row['vehicleid'];
            $inc->vehicleno = $row['vehicleno'];
            $info[] = $inc;
        }
    }
    echo json_encode($info);
    exit;
}




if (isset($_REQUEST['action']) && $_REQUEST["action"] == "teamdata") {
    if ($_REQUEST['term'] != '') {
        $searchString = trim($_REQUEST['term']);
        $teamemailsearch = '%' . $searchString . '%';
    }
    $info = Array();
    $db = new DatabaseManager();
    $SQL = sprintf("select * FROM " . DB_PARENT . ".team WHERE email LIKE '%s' ", $teamemailsearch);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $inc = new testing();
            $inc->value = $row['email'];
            $inc->teamid = $row['teamid'];
            $info[] = $inc;
        }
    }
    echo json_encode($info);
    exit;
}

function get_teamname($id) {
    $db = new DatabaseManager();
    $SQL = sprintf("select name from " . DB_PARENT . ".team where teamid=" . $id);
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $teamname = $row["name"];
    }
    return $teamname;
}

function get_crmname($id) {
    $db = new DatabaseManager();
    $SQL = sprintf("select * from " . DB_PARENT . ".relationship_manager where rid=" . $id);
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow()) {
        $teamname = $row["manager_name"];
    }
    return $teamname;
}

$cid = $_POST["cid"];
if ($cid != '0' && $cid != "") {
    $sql = sprintf("select rel_manager from " . DB_PARENT . ".customer where customerno=" . $cid);
    $db->executeQuery($sql);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $rel_manager = $row["rel_manager"];
        }
        if ($rel_manager != "") {
            $getname = get_crmname($rel_manager);
        } else {
            $getname = '0';
        }
        echo $getname;
    }
} else {
    header("location:ticket.php");
}
?>