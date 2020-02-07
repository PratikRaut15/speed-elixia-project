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

class Receive{}
$db = new DatabaseManager();
$sum =0;
$Report =Array();
if(isset($_POST['find']))
{
  if($_POST['crm']!="0" && $_POST['cid']!="0")
  {
      $crmid = GetSafeValueString($_POST['crm'], "string");
      $customerno = GetSafeValueString($_POST['cid'], "string");
      $SQL="SELECT i.invoiceno,i.customerno, i.clientname,i.inv_amt, i.paid_amt, i.pending_amt, customer.rel_manager FROM ".DB_PARENT.".invoice i
            INNER JOIN ".DB_PARENT.".customer ON i.customerno = customer.customerno 
            WHERE i.pending_amt NOT IN(0) AND i.customerno='$customerno' AND customer.rel_manager='$crmid' AND i.isdeleted=0";
  
      $db->executeQuery($SQL);
      
      if($db->get_rowCount()>0)
      {
          while($row = $db->get_nextRow())
          {
              $Data = new Receive();
              $Data->invno =$row['invoiceno'];
              $Data->cno =$row['customerno'];
              $Data->cname =$row['clientname'];
              $Data->invamt =$row['inv_amt'];
              $Data->paidamt =$row['paid_amt'];
              $Data->pendingamt =$row['pending_amt'];
              $sum=$sum + $row['pending_amt'];
              $Report[]=$Data;
          }
      }
  }
  
  if($_POST['crm']!="0" && $_POST['cid']=="0")
  {
      $crmid = GetSafeValueString($_POST['crm'], "string");
      $customerno = GetSafeValueString($_POST['cid'], "string");
      $SQL="SELECT i.invoiceno,i.customerno, i.clientname,i.inv_amt, i.paid_amt, i.pending_amt, customer.rel_manager FROM ".DB_PARENT.".invoice i
            INNER JOIN ".DB_PARENT.".customer ON i.customerno = customer.customerno 
            WHERE i.pending_amt NOT IN(0) AND customer.rel_manager='$crmid' AND i.isdeleted=0";
  
      $db->executeQuery($SQL);
      
      if($db->get_rowCount()>0)
      {
          while($row = $db->get_nextRow())
          {
              $Datas = new Receive();
              $Datas->invno =$row['invoiceno'];
              $Datas->cno =$row['customerno'];
              $Datas->cname =$row['clientname'];
              $Datas->invamt =$row['inv_amt'];
              $Datas->paidamt =$row['paid_amt'];
              $Datas->pendingamt =$row['pending_amt'];
              $sum=$sum + $row['pending_amt'];
              $Report[]=$Datas;
          }
      }
  }
  if($_POST['crm']=="0" && $_POST['cid']!="0")
   //only customerno is selected
  {
      $crmid = GetSafeValueString($_POST['crm'], "string");
      $customerno = GetSafeValueString($_POST['cid'], "string");
      $SQL="SELECT i.invoiceno,i.customerno, i.clientname,i.inv_amt, i.paid_amt, i.pending_amt, customer.rel_manager FROM ".DB_PARENT.".invoice i
            INNER JOIN ".DB_PARENT.".customer ON i.customerno = customer.customerno 
            WHERE i.pending_amt NOT IN(0) AND i.customerno='$customerno' AND i.isdeleted=0";
  
      $db->executeQuery($SQL);
      
      if($db->get_rowCount()>0)
      {
          while($row = $db->get_nextRow())
          {
              $Datacap = new Receive();
              $Datacap->invno =$row['invoiceno'];
              $Datacap->cno =$row['customerno'];
              $Datacap->cname =$row['clientname'];
              $Datacap->invamt =$row['inv_amt'];
              $Datacap->paidamt =$row['paid_amt'];
              $Datacap->pendingamt =$row['pending_amt'];
              $sum=$sum + $row['pending_amt'];
              $Report[]=$Datacap;
          }
      }
  }
  
    if($_POST['crm']=="-1")
      {
        $SQL ="SELECT sum(pending_amt) as tot from ".DB_PARENT.".invoice 
                inner join ".DB_PARENT.".customer on customer.customerno=invoice.customerno
                where invoice.customerno not in(0) AND isdeleted=0";
      
        $db->executeQuery($SQL);
      
        $row= $db->get_nextRow();
        $sum =$row['tot'];
      }
          }
$dg= new objectdatagrid($Report);
{
    $dg->AddColumn("Invoice No", "invno");
    $dg->AddColumn("Customer No", "cno");
    $dg->AddColumn("Client Name", "cname");
    $dg->AddColumn("Invoice Amount", "invamt");
    $dg->AddColumn("Paid Amount", "paidamt");
    $dg->AddColumn("Pending Amount", "pendingamt");
    $dg->SetNoDataMessage("No Result");
    $dg->AddIdColumn("id");
}
  
//----------------------------to populate crm name---------------------------------
function getcrm_detail() {
        $db = new DatabaseManager();
        $SQL = sprintf("SELECT * FROM ".DB_PARENT.".relationship_manager WHERE isdeleted=0");
        $db->executeQuery($SQL);
        $relmanager = Array();
        if($db->get_rowCount() > 0){
        while ($row = $db->get_nextRow())
            {
            $testing = new Receive();
            $testing->rid = $row["rid"];
            $testing->manager_name = $row["manager_name"];
            $relmanager[] = $testing;        
            }    
                return $relmanager;
                }
                    return false;
                }
//---------------------------------to populate customerno & name----------------------------------
function getcustomer_detail() {
        $db = new DatabaseManager();
        $customernos = Array();
        $SQL = sprintf("SELECT customerno,customername,customercompany FROM ".DB_PARENT.".customer");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $customer = new Receive();
                $customer->customerno = $row['customerno'];
                $customer->customername = $row['customername'];
                $customer->customercompany = $row['customercompany'];
                $customernos[] = $customer;
            }
            return $customernos;
            //print_r($customernos);
            }
        return false;
    }
    
    //$_scripts[] = "../../scripts/trash/prototype.js";
    include("header.php");
?>

<div class="panel">
        <div class="paneltitle" align="center">Receivables</div> 
        <div class="panelcontents">
            <form method="post" name="rform" id="rform" onsubmit="ValidateForm(); return false;">
                <div style="height:20px;">
                   <span id="error" style="display:none; color: #FF0000">Select Any One Option</span> 
                </div>
                <table width="80%">
                    <tr>
                        <td> <label>CRM </label> </td>
                        <td>
                            <select name="crm" id="crm">
                            <option value="0">Select a CRM</option>
                            <option value="-1" <?php if($crmid == "-1"){ echo "selected='selected'";}?>>All</option>
                                    <?php
                                    $crms= getcrm_detail();
                                    foreach($crms as $thismanager)
                                    {
                                    ?>
                                    <option value="<?php echo($thismanager->rid); ?>" <?php if($crmid == $thismanager->rid){ echo "selected='selected'";}?>><?php echo($thismanager->manager_name); ?></option>
                                    <?php
                                        }
                                    ?> 
                            </select>
                        </td>
                        <td><label>Client</label></td>
                        <td>    
                                <select name="cid" id="cid" style="width:200px;">
                                <option value="0">Select Client</option>
                                <?php
                       
                                    $cms = getcustomer_detail();
                                    foreach($cms as $customer)
                                    {
                                ?> 
                                <option value="<?php echo($customer->customerno);?>" <?php if($customerno== $customer->customerno) { echo "selected='selected'"; } ?> >
                                    <?php echo $customer->customerno;?> - <?php echo $customer->customercompany?>
                                </option>
                                <?php
                                     }
                                 ?> 
        
                                </select>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit"  name="find" id="find" class="btn btn-default" value="Search"></td>
                    </tr>
                </table>
            </form>
        </div>
</div>
<br/>
    <!----------------list---------------------------------------------->
    <div class="panel">
            <div class="panelcontents" align="right">
            
            <b>Total Pending Amount:&nbsp;<?php echo $sum;?></b>
            </div>
        <div class="paneltitle" align="center">Payment Details</div>
        <div class="panelcontents">
        <?php $dg->Render(); ?>
        </div>

        </div>
    <br/>
    <?php
      include("footer.php");
    ?>
   <script>
     
        function ValidateForm(){
            var cust_no =jQuery("#cid").val();
            var crm_no =jQuery("#crm").val();
            
            if(cust_no=="0" && crm_no=="0")
            {
                jQuery("#error").show();
                jQuery("#error").fadeOut(2000);
            }
            else
            {
                jQuery("#rform").submit();
            }
    }
    </script>