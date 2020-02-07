<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
//$_scripts[] = "../../scripts/trash/prototype.js";
$_scripts[] = "../../scripts/jquery.min.js";
 
include("header.php");

class testing{
    
}

date_default_timezone_set("Asia/Calcutta");                             
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();
$message="";


$details = Array();
$data = new testing();        
$data->teamid = 0;
$totalgoodunits = 0;
$totalbadunits = 0;
$totalgoodsimcards = 0;
$totalbadsimcards = 0;
$data->teamname = "Elixia Tech";        
$data->x = 1;                        
$details[] = $data;        

$db = new DatabaseManager();
$SQL = sprintf("SELECT teamid, name FROM ".DB_PARENT.".team");
$x = 2;
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $data = new testing();        
        $data->teamid = $row["teamid"];
        $data->teamname = $row["name"];        
        $data->x = $x;                        
        $details[] = $data;        
        $x++;
    }    
}

if(isset($details))
{
    foreach($details as $thisdetail)
    {
        $SQL = sprintf("SELECT count(*) as totalcount FROM unit WHERE unit.trans_statusid IN (1,2,4,9,18) AND unit.teamid = %d",$thisdetail->teamid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow())
            {
                    $thisdetail->totalcount = $row["totalcount"];
                    $totalgoodunits+= $thisdetail->totalcount;
            }    
        }
    }
}

if(isset($details))
{
    foreach($details as $thisdetail)
    {
        $SQL = sprintf("SELECT count(*) as totalcount FROM simcard WHERE simcard.trans_statusid IN (11,19) AND simcard.teamid  = %d",$thisdetail->teamid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow())
            {
                $thisdetail->totalcountsim = $row["totalcount"];
                $totalgoodsimcards+= $thisdetail->totalcountsim;
            }    
        }
    }
}

if(isset($details))
{
    foreach($details as $thisdetail)
    {
        $SQL = sprintf("SELECT count(*) as totalcount FROM simcard WHERE simcard.trans_statusid IN (12,15,21) AND simcard.teamid  = %d",$thisdetail->teamid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow())
            {
                $thisdetail->totalcountsimbad = $row["totalcount"];
                $totalbadsimcards+= $thisdetail->totalcountsimbad;
            }    
        }
    }
}

if(isset($details))
{
    foreach($details as $thisdetail)
    {
        $SQL = sprintf("SELECT count(*) as totalcount FROM unit WHERE unit.trans_statusid IN (3,17,20) AND unit.teamid = %d",$thisdetail->teamid);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow())
            {
                $thisdetail->totalcountbadunits = $row["totalcount"];
                $totalbadunits+=$thisdetail->totalcountbadunits;
            }    
        }
    }
}

$SQL = sprintf("SELECT team.teamid, unit.trans_statusid, team.name, count(*) as totalcount, unit.teamid FROM unit LEFT OUTER JOIN ".DB_PARENT.".team ON team.teamid = unit.teamid WHERE unit.trans_statusid IN (7)");
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $data = new testing();
        $data->teamname = "Gone for Repair";
        $data->teamid = -1;
        $data->totalcountbadunits = $row["totalcount"];
        $totalbadunits+=$thisdetail->totalcountbadunits;        
        $data->totalcountsim = 0;
        $data->totalcountsimbad = 0;        
        $data->totalcount = 0;
        $data->x = $x;
        $details[] = $data;        
        $x++;
    }    
}

$dg = new objectdatagrid( $details );
$dg->AddColumn("Elixir", "teamname");
$dg->AddColumn("Good Units", "totalcount");
$dg->AddColumn("Bad Units", "totalcountbadunits");
$dg->AddColumn("Good Simcards", "totalcountsim");
$dg->AddColumn("Bad Simcards", "totalcountsimbad");
$dg->AddAction("View", "../../images/unit.png", "unitdetails.php?tid=%d");
$dg->AddRightAction("View", "../../images/simcard.png", "simdetails.php?tid=%d");
$dg->SetNoDataMessage("No Data");
$dg->AddIdColumn("teamid");

?>
<br/>

<div class="panel">
<div class="paneltitle" align="center">Team-Wise Allocation</div>
<div class="panelcontents">
<table width="100%">
<tr>
<td>    
<table align="center" border="1">
<tr>    
<td><font size="3">Total Good Units</font></td><td><font size="3"><?php echo ($totalgoodunits); ?></font></td></tr>
<tr>    
<td><font size="3">Total Bad Units</font></td><td><font size="3"><?php echo ($totalbadunits); ?></font></td></tr>
<tr>    
<td><b><font size="3">Total Units</font></b></td><td><b><font size="3"><?php echo ($totalgoodunits + $totalbadunits); ?></font></b></td></tr>
</table>
</td>
<td>
<table align="center" border="1">    
<tr>    
<td><font size="3">Total Good Simcards</font></td><td><font size="3"><?php echo ($totalgoodsimcards); ?></font></td></tr>
<tr>    
<td><font size="3">Total Bad Simcards</font></td><td><font size="3"><?php echo ($totalbadsimcards); ?></font></td></tr>
<tr>    
<td><b><font size="3">Total Simcards</font></b></td><td><b><font size="3"><?php echo ($totalgoodsimcards + $totalbadsimcards); ?></font></b></td></tr>
</table>
</td>
</tr>
</table>
<br/>    
<?php $dg->Render(); ?>
</div>

</div>

<?php
include("footer.php");
?>

<script>

// -------------------------------------------- Pull for Allotment ------------------------------------------- //

function pullunit()
{
    var uteamid = jQuery('#uteamid').val();        
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {uteamid : uteamid},
        dataType: 'html',
        success: function(html){
            jQuery("#uready_td").html('');
            jQuery("#uready_td").append(html);
            
            // Pull Simcards
            pullsimcards();
        }
    });
    return false;		
}

function pullsimcards()
{
    var steamid = jQuery('#uteamid').val();        
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {steamid : steamid},
        dataType: 'html',
        success: function(html){
            jQuery("#simready_td").html('');
            jQuery("#simready_td").append(html);            
        }
    });
    return false;		    
}

function pullsimcard_from_unit()
{
    var uallotted = jQuery('#uallotted').val(); 
    var simteamid = jQuery('#uteamid').val();        
    
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {uallotted : uallotted, simteamid : simteamid},
        dataType: 'html',
        success: function(html){
            jQuery("#simready_td").html('');
            jQuery("#simready_td").append(html);            
        }
    });
    return false;		    
}

// -------------------------------------------- Pull for Return ------------------------------------------- //
function pullallunit()
{
    var uteamid = jQuery('#uteamid_returnall').val();        
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {uteamid_returnall : uteamid},
        dataType: 'html',
        success: function(html){
            jQuery("#uready_all_td").html('');
            jQuery("#uready_all_td").append(html);
            
            // Pull Simcards
            pullallsimcards();
        }
    });
    return false;		
}

function pullallsimcards()
{
    var steamid = jQuery('#uteamid_returnall').val();        
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {steamid_all : steamid},
        dataType: 'html',
        success: function(html){
            jQuery("#simready_all_td").html('');
            jQuery("#simready_all_td").append(html);            
        }
    });
    return false;		    
}

function pullallsimcard_from_unit()
{
    var uallotted = jQuery('#uallotted_all').val(); 
    var simteamid = jQuery('#uteamid_returnall').val();        
    
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {uallotted_all : uallotted, simteamid_all : simteamid},
        dataType: 'html',
        success: function(html){
            jQuery("#simready_all_td").html('');
            jQuery("#simready_all_td").append(html);            
        }
    });
    return false;		    
}

function  ValidateForm(){
    
    var uteamid =$("#uteamid").val();
    var uteamid_new = $("#uteamid_new").val();
    if(uteamid =="0"){
        alert("Please select Allot From");
    }else if(uteamid_new=="0"){
        alert("Please select Allot To");
    }else{
          $("#myformreallot").submit();
    }
}

function ValidateForm2(){
    var uteamid_returnall = $("#uteamid_returnall").val();
    if(uteamid_returnall==""){
        alert("Please select Return From.");
        return false;
    }else{
          $("#myformreturn").submit();
    }
    
    
}

</script>