<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include("../../lib/bo/TeamManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");

class Credit_Note {
    
}

function display($data) {
    echo $data;
}
$db = new DatabaseManager();

//QUERY for display
$Display = array();
$SQL = "SELECT  cn.*
                ,c.customercompany
                ,c.customerno
                ,i.invoiceno
                ,team.name
        FROM " . DB_PARENT . ".credit_note cn
        LEFT OUTER JOIN " . DB_PARENT . ".customer c ON c.customerno = cn.customerno 
        LEFT OUTER JOIN " . DB_PARENT . ".invoice i ON cn.invoiceno = i.invoiceid
        LEFT OUTER JOIN " . DB_PARENT . ".team  ON cn.created_by = team.teamid
        ORDER BY cn.credit_note_id DESC";
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $Datacap = new Credit_Note();
        //$x++;
        $Datacap->credit_note_id = $row['credit_note_id'];
        $Datacap->credit_note_no = $row['credit_note_no'];
        $Datacap->credit_amount = $row['credit_amount'];
        $Datacap->reason = $row['reason'];
        $Datacap->status = $row['status'];
        $Datacap->requested_date = date("d-m-Y", strtotime($row['requested_date']));
        $Datacap->approved_date = date("d-m-Y", strtotime($row['approved_date']));
        $Datacap->customercompany = $row['customercompany'];
        $Datacap->invoiceno = $row['invoiceno'];
        $Datacap->customerno = $row['customerno'];
        $Datacap->invoice_amount = $row['invoice_amount'];
        $Datacap->created_by = $row['name'];
        // $Datacap->invoice_date =  date("d-m-Y", strtotime($row['invoice_date']));
        if ($row['status'] =='approved') {
           $Datacap->generate = '<a href="../download/credit_note.php?credit_note_id=' . $row['credit_note_id'] . '"><img src="../../images/pdf_icon.png"></img></a>';
           // $dg->AddRightAction("Download PDF", "../../images/pdf_icon.png", "../download/credit_note.php?credit_note_id=%d");
        }
        
        if (isset($row['invoice_date']) && $row['invoice_date'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->invoice_date = date("d-m-Y", strtotime($row['invoice_date']));
        }
        else
        {
          $Datacap->invoice_date='NA';
        }
        if (isset($row['requested_date']) && $row['requested_date'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->requested_date = date("d-m-Y", strtotime($row['requested_date']));
        }
        else
        {
          $Datacap->requested_date='NA';
        }
         if (isset($row['approved_date']) && $row['approved_date'] != '0000-00-00') {// to provide empty date if not inserted
            $Datacap->approved_date = date("d-m-Y", strtotime($row['approved_date']));
        }
        else
        {
          $Datacap->approved_date='NA';
        }
        
        $Display[] = $Datacap;
    }
}

$dg = new objectdatagrid($Display);
$dg->AddColumn("Sr.No", "credit_note_id");
$dg->AddColumn("Credit Note No", "credit_note_no");
$dg->AddColumn("Customer No", "customerno");
$dg->AddColumn("Customer Name", "customercompany");
$dg->AddColumn("Invoice No", "invoiceno");
$dg->AddColumn("Invoice Amount", "invoice_amount");
$dg->AddColumn("Credit Amount", "credit_amount");
$dg->AddColumn("Reason", "reason");
$dg->AddColumn("Status", "status");
$dg->AddColumn("Invoice Date", "invoice_date");
$dg->AddColumn("Requested Date", "requested_date");
$dg->AddColumn("Approved Date", "approved_date");
$dg->AddColumn("Created By", "created_by");
$dg->AddColumn("Created On", "requested_date");
$dg->AddColumn("", "generate");
// $dg->AddColumn("Download", " ");
if (IsAdmin()) {
    $dg->AddColumn("", "generate");
}
foreach ($Display as $key => $value) {
   // print_r($value->status);
  if(strval($value->status)!= 'requested')
{
  // echo 123;
  $approved[]=$value->status;
 
}
}



// $dg->AddRightAction("Download PDF", "../../images/pdf_icon.png", "../download/credit_note.php?credit_note_id=%d");
$dg->AddAction("View/Edit", "../../images/edit.png", "creditnote_edit.php?credit_note_id=%d");

$dg->SetNoDataMessage("No Credit Note");
$dg->AddIdColumn("credit_note_id");
// $dg->enable_export('EXCEL');

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");

$SQL = sprintf("SELECT team.teamid, team.name FROM ".DB_PARENT.".team order by name asc");
$db->executeQuery($SQL);
$team_allot_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $team = new Credit_Note();
        $team->teamid  = $row["teamid"];
        $team->name = $row["name"];        
        $team_allot_array[] = $team;        
    }    
}

// $SQL = sprintf("SELECT invoice.invoiceid, team.name FROM ".DB_PARENT.".team order by name asc");
// $db->executeQuery($SQL);
// $team_allot_array = Array();
// if ($db->get_rowCount() > 0) {
//     while ($row = $db->get_nextRow())
//     {
//         $team = new Credit_Note();
//         $team->teamid  = $row["teamid"];
//         $team->name = $row["name"];        
//         $team_allot_array[] = $team;        
//     }    
// }

include("header.php");
?>
<!-- <div class="panel">
    <div class="paneltitle" align="center">Search</div>
<div class="panelcontents">    
<form method="post" action="servicecall.php"  enctype="multipart/form-data">
    <table width="100%" align="center">

        <tr>
            <td colspan="2">Customer <input  type="text" name="customer" id="customer" size="25" value="<?php if(isset($customerno)){ echo $customerno; } ?>" autocomplete="off" placeholder="Enter Customer No Or Name" onkeyup="getCust()" />
                <input type="hidden" name="customerno" id="customerno" />
            </td>

            <td>Invoice No <select name="inv_no" id="inv_no">
                <option value="0" selected>Invoice No</option>
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

        <td>Created By <select name="teamid" id="teamid">
                <option value="0" selected>Created By</option>
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
</div>  -->
<link rel="stylesheet" href="../../css/invoicePayment.css">
<div class="panel">
  <div class="paneltitle" align="center">Credit Note</div> 
  <div class="panelcontents">
    <div class="center">
      <form name="credit_note" id="credit_note">
      <label>Customer</label>
        <input type="text" name="customername" id="customername" size="30" value="" autocomplete="on" placeholder="Enter Customer Name or number" onkeypress="getCustomer();"/>
        <label>Ledger</label>
         <select name="ledger_name" id="ledger_name">
              <option value="0">Select Ledger</option>
            </select> 
        <label>Invoice Number</label>
            <select name="invoiceno" id="invoiceno">
              <option value="0">Select Invoice Number</option>
            </select> 
          <label>Invoice Amount</label>
          <input type="text" name="inv_amount" id="inv_amount"  autocomplete="off" value="" readonly />
          <div>
          <label>Invoice Date</label>
         <input type="text" name="inv_date" id="inv_date"  autocomplete="off" value="" readonly />
          <label>Credit Amount</label>
          <input type="text" name="credit_amount" id="credit_amount" size="30" value="" autocomplete="off" placeholder="Enter Credit Amount"/>
          <label>Reason</label>
          <input type="text" name="reason" id="reason" size="30" value="" autocomplete="off" placeholder="Enter Reason"/>
          <label>Status</label>
            <select name="status" id="status">
              <!-- <option value="0">Select status</option> -->
              <option value="1" selected >Requested</option>
              <!-- <option value="2">Approved</option>
              <option value="3">Reject</option> -->
            </select> 
          <!-- <label>Status</label>
          <input type="text" name="status" id="status" size="30" value="" autocomplete="off" placeholder="Enter Reason" onkeypress="getStatus();"/> -->
        </div>
        <input type="hidden" name="customerno" id="customerno" value=""/>
         <input type="hidden" name="inv_amnt" id="inv_amnt" value=""/>
        <input type="button" name="submit" id="submit" value="Generate Credit Note" onclick="submitCreditNote();" style="margin-left:40%;"/>
           
      </form>
    </div>
  </div>
</div>
<div class="panel">
    <div class="paneltitle" align="center">Credit Note List</div>
    <div class="panelcontents">
        <?php $dg->Render(); ?>
    </div>

</div>
<br/>
<?php include("footer.php"); ?> 

<script type="text/javascript">
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
<script src='../../scripts/team/credit_note.js'></script>
