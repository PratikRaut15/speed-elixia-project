<?php
include_once("session.php");
include("loginorelse.php");
include_once("../constants/constants.php");
include_once("../db.php");
include_once("../lib/components/gui/datagrid.php");
include_once("../lib/system/DatabaseManager.php");


//Datagtrid
$db = new DatabaseManager();
$SQL = sprintf("SELECT c.customerno, c.customername, c.customeremail, c.customerphone, c.customercell, c.dateadded, st.name as soldby FROM customer c INNER JOIN team st ON st.teamid = c.teamid");
$db->executeQuery($SQL);

$dg = new datagrid( $db->getQueryResult() );
$dg->AddAction("View/Edit", "../images/edit.png", "checkpayment.php?cid=%d");
$dg->AddColumn("Name", "customername");
$dg->AddColumn("Phone", "customerphone");
$dg->AddColumn("Cell", "customercell");
$dg->AddColumn("Date Added (yyyy-mm-dd)", "dateadded");
$dg->AddColumn("Email", "customeremail");
$dg->AddColumn("Sold by", "soldby");
$dg->SetNoDataMessage("No Customer");
$dg->AddIdColumn("customerno");

include("header.php");
?>
<div class="panel">
<div class="paneltitle" align="center">Customer List</div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>
<br/>
<?php
include("footer.php");
?>

