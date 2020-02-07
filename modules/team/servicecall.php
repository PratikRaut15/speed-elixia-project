<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

class testing{
    
}

$data = Array();
$srno = 1;
//Datagtrid
$db = new DatabaseManager();
if(isset($_POST["scsearch"]))
{    
    $customerno = GetSafeValueString($_POST["customerno"], "string");
    $teamid = GetSafeValueString($_POST["teamid"], "string");

    $unitno = GetSafeValueString($_POST["unitno"], "string");     
    $simcardno = GetSafeValueString($_POST["simcardno"], "string");     
    $comments = GetSafeValueString($_POST["comments"], "string");     
    $vehicleno = GetSafeValueString($_POST["vehicleno"], "string");     

    $startdate = GetSafeValueString($_POST["startdate"], "string");     
    $enddate = GetSafeValueString($_POST["enddate"], "string");     

    $sqlcustomer = "";
    if($customerno != '0' && $customerno != '')
    {
        $sqlcustomer = sprintf(" AND customer.customerno = %d ",$customerno);
    }

    $sqlteam = "";
    if($teamid != '0')
    {
        $sqlteam = sprintf(" AND team.teamid = %d ",$teamid);
    }

    $sqlunitno = "";
    if($unitno != '')
    {
        $sqlunitno = sprintf(" AND oldunit.unitno LIKE '%s' ||  newunit.unitno LIKE '%s'",$unitno,$unitno);
    }

    $sqlsimcard = "";
    if($simcardno != '')
    {
        $sqlsimcard = sprintf(" AND (oldsimcard.simcardno LIKE '%s' ||  newsimcard.simcardno LIKE '%s')",$simcardno,$simcardno);
    }

    $sqlvehicle = "";
    if($vehicleno != '')
    {
        $sqlvehicle = sprintf(" AND (newvehicle.vehicleno LIKE '%s' || oldvehicle.vehicleno LIKE '%s' )","%".$vehicleno."%","%".$vehicleno."%");
    }

    $sqlcomments = "";
    if($comments != '')
    {
        $sqlcomments = sprintf(" AND th.remark LIKE '%s' ","%".$comments."%");
    }
    
    $sqldate = "";
    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));
    if($startdate != '' && $enddate != '')
    {
        $sqldate = sprintf("DATE(th.createdon) BETWEEN '%s' AND '%s' ",$startdate,$enddate);
    }
    
    $type = GetSafeValueString($_POST["type"], "string");     
   
    if($type != '0')
    {
        $sqltype = sprintf(" AND th.`transtypeid`=".$type);
    }
    
    $SQL = sprintf("SELECT b.type as btype, th.transid, team.name, customer.customercompany, customer.customerno
                                ,oldunit.unitno as oldunit
                                ,newunit.unitno as newunit
                                ,oldvehicle.vehicleno as oldvehicle
                                ,newvehicle.vehicleno as newvehicle
                                ,oldsimcard.simcardno as oldsim
                                ,newsimcard.simcardno as newsim
                                ,th.remark
                                ,trans_type.`type`
                                ,th.createdon
                                ,created.name as createdby
                    FROM 	`trans_history_new` th
                    INNER JOIN  team ON team.teamid=th.teamid
                    INNER JOIN  team created ON created.teamid=th.createdby
                    INNER JOIN trans_type ON trans_type.id=th.`transtypeid`
                    INNER JOIN bucket_status b ON b.id=th.`bucketstatusid`                    
                    LEFT JOIN 	unit oldunit ON (oldunit.uid=th.oldunitid)
                    LEFT JOIN 	unit newunit ON (newunit.uid=th.newunitid)
                    LEFT JOIN 	vehicle oldvehicle ON (oldvehicle.vehicleid=th.oldvehicleid)
                    LEFT JOIN 	vehicle newvehicle ON (newvehicle.vehicleid=th.newvehicleid)
                    LEFT JOIN 	simcard oldsimcard ON (oldsimcard.id=th.oldsimcardid)
                    LEFT JOIN 	simcard newsimcard ON (newsimcard.id=th.newsimcardid)
                    INNER JOIN customer ON th.customerno = customer.customerno");
    $SQL.=" WHERE ";
    $SQL.= $sqldate;
    $SQL.= $sqlcustomer;
    $SQL.= $sqlteam;    
    $SQL.= $sqlunitno;
    $SQL.= $sqlsimcard;
    $SQL.= $sqlvehicle;
    $SQL.= $sqlcomments;
    $SQL.= $sqltype;    
    $SQL.=" ORDER BY team.name, th.createdon DESC";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $data = new testing();
            $data->srno = $srno;
            $data->transid = "SC00".$row["transid"] ;            
            $data->customerno = $row["customerno"];
            $data->company = $row["customercompany"];        
            $data->fename = $row["name"];        
            $data->type = $row["type"];                    
            $data->oldvehicle = $row["oldvehicle"];                
            $data->newvehicle = $row["newvehicle"];                        
            $data->oldunit = $row["oldunit"];           
            $data->newunit = $row["newunit"];   
            $data->oldsim = $row["oldsim"];           
            $data->newsim = $row["newsim"];   
            $data->comments = $row["remark"];     
            $data->status = $row["btype"];     
            $data->trans_date = date("d-m-Y", strtotime($row["createdon"]));                           
            $devices[] = $data; 
            $srno++;
        }    
    
    }
}
else
{
    $SQL = sprintf("SELECT  b.type as btype, th.transid, team.name, customer.customercompany, customer.customerno
                            , oldunit.unitno as oldunit
                            , newunit.unitno as newunit
                            , oldvehicle.vehicleno as oldvehicle
                            , newvehicle.vehicleno as newvehicle
                            , oldsimcard.simcardno as oldsim
                            , newsimcard.simcardno as newsim
                            , th.remark
                            , trans_type.`type`
                            , th.createdon
                            , created.name as createdby
                    FROM 	`trans_history_new` th
                    INNER JOIN  team ON team.teamid=th.teamid
                    INNER JOIN  team created ON created.teamid=th.createdby
                    INNER JOIN trans_type ON trans_type.id=th.`transtypeid`
                    INNER JOIN bucket_status b ON b.id=th.`bucketstatusid`                    
                    LEFT JOIN 	unit oldunit ON (oldunit.uid=th.oldunitid)
                    LEFT JOIN 	unit newunit ON (newunit.uid=th.newunitid)
                    LEFT JOIN 	vehicle oldvehicle ON (oldvehicle.vehicleid=th.oldvehicleid)
                    LEFT JOIN 	vehicle newvehicle ON (newvehicle.vehicleid=th.newvehicleid)
                    LEFT JOIN 	simcard oldsimcard ON (oldsimcard.id=th.oldsimcardid)
                    LEFT JOIN 	simcard newsimcard ON (newsimcard.id=th.newsimcardid)
                    INNER JOIN customer ON th.customerno = customer.customerno
                    ORDER BY team.name, th.createdon DESC LIMIT 10");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
            $data = new testing();
            $data->srno = $srno;
            $data->transid = "SC00".$row["transid"] ;            
            $data->customerno = $row["customerno"];
            $data->company = $row["customercompany"];        
            $data->fename = $row["name"];        
            $data->type = $row["type"];                    
            $data->oldvehicle = $row["oldvehicle"];                
            $data->newvehicle = $row["newvehicle"];                        
            $data->oldunit = $row["oldunit"];           
            $data->newunit = $row["newunit"];   
            $data->oldsim = $row["oldsim"];           
            $data->newsim = $row["newsim"];   
            $data->comments = $row["remark"];     
            $data->status = $row["btype"];     
            $data->trans_date = date("d-m-Y", strtotime($row["createdon"]));                           
            $devices[] = $data; 
            $srno++;
        }    
    }
}

$dg = new objectdatagrid( $devices );
$dg->AddColumn("Sr. no.", "srno");
$dg->AddColumn("Transaction ID", "transid");
$dg->AddColumn("Date", "trans_date");
$dg->AddColumn("Customer No.", "customerno");
$dg->AddColumn("Customer Name", "company");
$dg->AddColumn("Elixir", "fename");
$dg->AddColumn("Task", "type");
$dg->AddColumn("Old Vehicle No.", "oldvehicle");
$dg->AddColumn("New Vehicle No.", "newvehicle");
$dg->AddColumn("Old Unit", "oldunit");
$dg->AddColumn("New Unit", "newunit");
$dg->AddColumn("Old Simcard", "oldsim");
$dg->AddColumn("New Simcard", "newsim");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Remark", "comments");
$dg->SetNoDataMessage("No Details");
$dg->AddIdColumn("id");

$_scripts[] = "../../scripts/trash/prototype.js";
 
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

$SQL = sprintf("SELECT team.teamid, team.name FROM ".DB_PARENT.".team order by name asc");
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

include("header.php");

?>

<div class="panel">
    <div class="paneltitle" align="center">Service Call Search</div>
<div class="panelcontents">    
<form method="post" action="servicecall.php"  enctype="multipart/form-data">
    <table width="100%" align="center">

        <tr>
            <td colspan="2">Customer <input  type="text" name="customer" id="customer" size="25" value="<?php if(isset($customerno)){ echo $customerno; } ?>" autocomplete="off" placeholder="Enter Customer No Or Name" onkeyup="getCust()" />
                <input type="hidden" name="customerno" id="customerno" />
            </td>
        <td>Elixir <select name="teamid" id="teamid">
                <option value="0" selected>Select an Elixir</option>
                <?php
                foreach($team_allot_array as $thisteam)
                {
                    if($thisteam->teamid == $teamid)
                    {
                ?>
                <option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>                
                <?php
                        
                    }
                    else
                    {
                ?>
                <option value="<?php echo($thisteam->teamid); ?>"><?php echo($thisteam->name); ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </td>
        <td>Type <select name="type" id="type">
                <option value="0">Select Type</option>
                <option value="1" <?php if($type==1){ echo"selected"; } ?>>Registered Device</option>
                <option value="2" <?php if($type==2){ echo"selected"; } ?>>Removed Bad</option>
                <option value="3" <?php if($type==3){ echo"selected"; } ?>>Replaced Simcard</option>
                <option value="4" <?php if($type==4){ echo"selected"; } ?>>Replaced Unit</option>
                <option value="5" <?php if($type==5){ echo"selected"; } ?>>Replaced Both</option>
                <option value="6" <?php if($type==6){ echo"selected"; } ?>>Reinstalled</option>
                <option value="7" <?php if($type==7){ echo"selected"; } ?>>Repaired</option>
            </select>
        </td>        
        <td></td>        
        </tr>

        <tr>
        <td>Unit No.  <input name="unitno" id="unitno" type="text" value="<?php if(isset($unitno)) echo($unitno); ?>"/>
        </td>
        
        <td>Simcard No.  <input name="simcardno" id="simcardno" type="text" value="<?php if(isset($simcardno)) echo($simcardno); ?>"/>
        </td>
        
        <td>Comments  <input name="comments" id="comments" type="text" value="<?php if(isset($comments)) echo($comments); ?>"/>
        </td>
        
        <td>Vehicle No.  <input name="vehicleno" id="vehicleno" type="text" value="<?php if(isset($vehicleno)) echo($vehicleno); ?>"/>
        </td>
        </tr>            
        
        <tr>
        <td></td>            
        <td>Start Date  <input name="startdate" id="startdate" type="text" value="<?php if(isset($startdate)) { echo(date("d-m-Y", strtotime($startdate))); } else { echo date("d-m-Y"); } ?>" required/><button id="trigger2">...</button>
        </td>
        <td>End Date  <input name="enddate" id="enddate" type="text" value="<?php if(isset($enddate)) { echo(date("d-m-Y", strtotime($enddate))); } else { echo date("d-m-Y"); } ?>" required/><button id="trigger">...</button>
        </td>        
        <td></td>        
        </tr>            
        
    </table>
<div align="center"><input type="submit" name="scsearch" value="Search" /></div>
</form>
    
</div>
</div>    

<br/>
<div class="panel">
<div class="paneltitle" align="center"><?php if(isset($_POST["scsearch"])){ ?> Result for Search<?php } else { ?> Last 10 Service Calls<?php } ?></div>
<div class="panelcontents">
<?php $dg->Render(); ?>
</div>

</div>
<br/>

<?php
include("footer.php");
?>
<script>
Calendar.setup(
{
    inputField : "enddate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger" // ID of the button
});


Calendar.setup(
{
    inputField : "startdate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger2" // ID of the button
});

function getCust(){
    jQuery("#customer").autocomplete({
                    source: "route_ajax.php?customername=getcust",
                    select: function (event, ui) {

                        /*clear selected value */
                        jQuery(this).val(ui.item.value);
                        jQuery('#customerno').val(ui.item.cid);
                        return false;
                    }
                });
}
</script>    
