<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");


// Get Userid
$userid = $_GET['cid']; 


//Datagtrid
$db = new DatabaseManager();
//$SQL = sprintf("SELECT c.totalsms, c.customerno, c.customername, c.smsleft, c.customercompany FROM customer c");
 $SQL = "select login_history.userid,user.realname,user.username, customer.customerno, customer.customername, date_format(login_history.timestamp,'%d-%M-%Y %H:%i') as timestamp, 
   CASE 
   WHEN login_history.type = 1 THEN
     'Mobile'
   ELSE
    'Web'
   END 
   as machine
from ".DB_PARENT.".login_history
inner join user on user.userid = login_history.userid
inner join ".DB_PARENT.".customer on customer.customerno = login_history.customerno
where login_history.userid = $userid order by timestamp DESC";
$db->executeQuery($SQL);

$dg = new datagrid( $db->getQueryResult() );
//$dg->AddAction("View/Edit", "../../images/edit.png", "modifycustomer.php?cid=%d");

$dg->AddColumn("Customer No#", "customerno");
$dg->AddColumn("Customer Name", "customername");
$dg->AddColumn("Realname #", "realname");
$dg->AddColumn("Username", "username");
$dg->AddColumn("Login Type", "machine");

$dg->AddColumn("Date Time", "timestamp");
//$dg->AddRightAction("View", "../../images/history.png", "login_hist.php?cid=%d");
$dg->SetNoDataMessage("No Customer");
$dg->AddIdColumn("userid");

$_scripts[] = "../../scripts/trash/prototype.js";
 
include("header.php");

// Get Username From Userid
$SQL = sprintf("select realname,username from user where userid=%d", $userid);
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $username = $row["username"];
        $realname = $row["realname"];
    }    
}

?>

<br/>

<div class="panel">
<div class="paneltitle" align="center">Login History For  - <?php echo $realname; echo " (". $username .")";?></div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>
<br/>

<?php
include("footer.php");
?>

