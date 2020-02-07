<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");

class testing {
    
}
$teamid = GetLoggedInUserId();
$rid = GetLoggerdInrid();
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();
global $db;
$SQL = sprintf("SELECT customerno, customercompany FROM ".DB_PARENT.".customer");
$db->executeQuery($SQL);
$customer = Array();
if ($db->get_rowCount() > 0){
    while ($row = $db->get_nextRow())
    {
        $testing = new testing();
        $testing->customerno = $row["customerno"];
        $testing->customername = $row["customerno"]."( ".$row['customercompany']." )";
        $customer[] = $testing;        
    }    
}
$crmid=$rid;
$msg="";
if(isset($_POST["changecrm"])){
  $crm =$_POST["crm"];
  if($crm!="0"){
      $crmid = $crm;
  }else{
      $msg="Please change crm manager";
      $crmid = $rid;
  }
}

if(IsHead()){
     $crm =$_POST["crm"];
     if($crm==""){
     $SQL = sprintf("SELECT *,(CASE WHEN devicetype=1 THEN 'Basic' WHEN devicetype= 2 THEN 'Advanced'  ELSE '-' END)as devicestatus from ".DB_PARENT.".new_sales  where orderdate>'2018-07-01'
     order by orderid desc");
     }
    else{
        $sql = sprintf("select * from relationship_manager where rid =".$crm);
        $db->executeQuery($sql);
            if ($db->get_rowCount() > 0){
                while ($row = $db->get_nextRow())   
                {
                    $rid = $row["rid"];
                }
               $SQL = sprintf("SELECT *,(CASE WHEN devicetype=1 THEN 'Basic' WHEN devicetype= 2 THEN 'Advanced'  ELSE '-' END)as devicestatus from ".DB_PARENT.".new_sales where rel_manager=".$rid."  order by orderid desc");
                
            }else{
                $status='none';
            }
    }
}
else{
   $sql = sprintf("select * from ".DB_PARENT.".relationship_manager where rid =".$crmid);
    $db->executeQuery($sql);
    if ($db->get_rowCount() > 0) 
    {
        while ($row = $db->get_nextRow())   
        {
            $rid = $row["rid"];
        }
         $SQL = sprintf("SELECT *,(CASE WHEN devicetype=1 THEN 'Basic' WHEN devicetype= 2 THEN 'Advanced'  ELSE '-' END)as devicestatus from ".DB_PARENT.".new_sales where rel_manager=".$rid." where orderdate>'2018-07-01' order by orderid desc");
    }else{
        $status='none';
    }
}
$db->executeQuery($SQL);

function get_teamname($id){
  $db = new DatabaseManager();
  $SQL = sprintf("select name from ".DB_PARENT.".team where teamid=".$id);
  $db->executeQuery($SQL);
   while ($row = $db->get_nextRow())   
   {
       $teamname = $row["name"];
   }
  return $teamname;
}

function get_customername($id,$oid){
  $db = new DatabaseManager();
  if($id=='-1')
  {
      $SQL = sprintf("select new_customer from ".DB_PARENT.".new_sales where orderid=".$oid);
      $db->executeQuery($SQL);
      while ($row = $db->get_nextRow())   
      {
          $customercompany = $row["new_customer"]."(New Customer)";
      }  
  }
  else
  {
  $SQL = sprintf("select customercompany from ".DB_PARENT.".customer where customerno=".$id);
  $db->executeQuery($SQL);
   while ($row = $db->get_nextRow())   
   {
       $customercompany = $row["customercompany"];
   }
  } 
  return $customercompany;
}


function get_relmanager($rid){
    $db = new DatabaseManager();
    $SQL = sprintf("select * from ".DB_PARENT.".relationship_manager where rid=".$rid);
    $db->executeQuery($SQL);
    while ($row = $db->get_nextRow())   
    {
     $manager_name = $row["manager_name"];
     $teamid=$row["teamid"];
    }
    return $manager_name;
}

$x=0;
$dispdetails = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow())
    {
        $pending = $row['deviceqty']-$row['install_device_qty'];
       
        $x++;
        $order = new testing();
        $order->orderid = $row['orderid'];
        $orderdate1 = $row['orderdate'];
        $order->orderdate = date('d-m-Y', strtotime($orderdate1));
        $order->teamname = get_teamname($row['teamid']);
        $order->teamid = $row['teamid'];
        $order->customerno = $row['customerno'];
        $order->customername = get_customername($row['customerno'], $row['orderid']);
        if($row['rel_manager'] == ''){
            $row['rel_manager'] = 0;
        }
        $order->relmanager = get_relmanager($row['rel_manager']);
        $order->deviceqty = $row['deviceqty'];
        $order->installdeviceqty = $row['install_device_qty'];
        $order->pendingdevice = $row['deviceqty']-$row['install_device_qty'];
        $order->devicetype = $row['devicetype'];
        $order->contactno = $row['contactno'];
        $order->devicestatus = $row['devicestatus'];
        $order->contact_person = $row['contact_person'];
        $order->remark = $row['remark'];
        $order->x = $x;
        $dispdetails[] = $order;
    
    }
}
$dg = new objectdatagrid($dispdetails);
$dg->AddColumn("Sr No", "x");
$dg->AddColumn("Order Date", "orderdate");
$dg->AddColumn("Company", "customername");
$dg->AddColumn("Relation Manager", "relmanager");
$dg->AddColumn("Total Installation","deviceqty");
$dg->AddColumn("Pending Installation","pendingdevice");
$dg->AddColumn("Device Type", "devicestatus");
$dg->AddColumn("Contact Name", "contact_person");
$dg->AddColumn("Contact Number", "contactno");
$dg->AddColumn("Remark", "remark");
$dg->AddColumn("Created By", "teamname");
$dg->AddRightAction("Edit / Delete", "../../images/edit.png", "modifycrmorder.php?oid=%d");
$dg->SetNoDataMessage("No Sales Orders");
$dg->AddIdColumn("orderid");



////////////////////New customer orders code here////////////////////////////

$SQLnew = sprintf("SELECT *,(CASE WHEN devicetype=1 THEN 'Basic' WHEN devicetype= 2 THEN 'Advanced'  ELSE '-' END)as devicestatus from ".DB_PARENT.".new_sales where customerno ='-1' AND rel_manager='0' order by orderid desc");
$db->executeQuery($SQLnew);
$x=0;
$disp_new_details = Array();
if ($db->get_rowCount() > 0){
    while ($row = $db->get_nextRow())
    {
        $pending = $row['deviceqty']-$row['install_device_qty'];
        if($pending!='0'){
        $x++;
        $neworder = new testing();
        $neworder->orderid = $row['orderid'];
        $orderdate1 = $row['orderdate'];
        $neworder->orderdate = date('d-m-Y', strtotime($orderdate1));
        $neworder->teamname = get_teamname($row['teamid']);
        $neworder->teamid = $row['teamid'];
        $neworder->customerno = $row['customerno'];
        $neworder->customername = get_customername($row['customerno'], $row['orderid']);
        $neworder->relmanager = get_relmanager($row['rel_manager']);
        $neworder->deviceqty = $row['deviceqty'];
        $neworder->installdeviceqty = $row['install_device_qty'];
        $neworder->pendingdevice = $row['deviceqty']-$row['install_device_qty'];
        $neworder->devicetype = $row['devicetype'];
        $neworder->contactno = $row['contactno'];
        $neworder->devicestatus = $row['devicestatus'];
        $neworder->contact_person = $row['contact_person'];
        $neworder->remark = $row['remark'];
        $neworder->x = $x;
        $disp_new_details[] = $neworder;
    }
    }
}



$dn = new objectdatagrid($disp_new_details);
$dn->AddColumn("Sr No", "x");
$dn->AddColumn("Order Date", "orderdate");
$dn->AddColumn("Company", "customername");
$dn->AddColumn("Relation Manager", "relmanager");
$dn->AddColumn("Total Installation","deviceqty");
$dn->AddColumn("Pending Installation","pendingdevice");
$dn->AddColumn("Device Type", "devicestatus");
$dn->AddColumn("Contact Name", "contact_person");
$dn->AddColumn("Contact Number", "contactno");
$dn->AddColumn("Remark", "remark");
$dn->AddColumn("Created By", "teamname");
$dn->AddRightAction("Edit / Delete", "../../images/edit.png", "modifycrmorder.php?oid=%d");
$dn->SetNoDataMessage("No Sales Orders");
$dn->AddIdColumn("orderid");


include("header.php");
?>
<br/>
<?php 
if($status!="none"){

?>
<style>
.panel{
    width:1224px !important;
}
.paneltitle{
    width: 1208px !important;
}
</style>
<div class="panel">
  <div class="paneltitle" align="center">New Customer Orders List</div>
  <div class="panelcontents">

  <?php
  $dn->Render(); 
  ?>
  </div>
</div>
<br>
<div class="panel">
    <div class="paneltitle" align="center">Crm Orders List</div>
    <div class="panelcontents" style="height: 40px;">
        <?php
          $SQL = sprintf("SELECT * FROM ".DB_PARENT.".relationship_manager");
          $db->executeQuery($SQL);
          $relmanager = Array();
          if($db->get_rowCount() > 0){
            while ($row = $db->get_nextRow()){
                $testing = new testing();
                $testing->rid = $row["rid"];
                $testing->manager_name = $row["manager_name"];
                $relmanager[] = $testing;        
            }    
          }
        ?>
        <form name="changecrm" id="crmmanager" method="POST" action="crm_orders.php">
            
            <table align="center" cellpadding="5">
            <?php
            if(!empty($msg)){echo "<tr><td colspan='4' style='text-align:center; font-size:12px; color:red;'>".$msg."</td></tr>";}
            ?>
                <tr>
                <td> <label>Change CRM </label> </td>
                <td>
                    <select name="crm" id="crm">
                       <option value="0">Select a Manager</option>     
                                        <?php
                                        foreach($relmanager as $thismanager)
                                        {
                                        ?>
                                        <option value="<?php echo($thismanager->rid); ?>" <?php if($crmid == $thismanager->rid){ echo "selected='selected'";}?>><?php echo($thismanager->manager_name); ?></option>
                                        <?php
                                            }
                                        ?> 
                    </select>
                </td>
                 <td><input type="submit" name="changecrm" id="changecrm" value="Change"></td>
             </tr>
            </table>
        </form>
    </div>
    <br/>
    <?php 
        $crm =$_POST["crm"];
        if(IsHead()){
                if($crm==""){
                    $SQL = sprintf("SELECT * FROM ".DB_PARENT.".relationship_manager");
                }else{
                    $SQL = sprintf("SELECT * FROM ".DB_PARENT.".relationship_manager where rid=".$crm);
                }
        }
        else{
            if($crm!=""){
                  $SQL = sprintf("SELECT * FROM ".DB_PARENT.".relationship_manager where rid=".$crm);
            }
            else if($rid!=""){
                $SQL = sprintf("SELECT * FROM ".DB_PARENT.".relationship_manager where rid=".$rid);
            }
            else{
                 $SQL = sprintf("SELECT * FROM ".DB_PARENT.".relationship_manager where rid=".$rid);
            }
        }
        $db->executeQuery($SQL);
        $relmanager1 = Array();
        if($db->get_rowCount() > 0){
          while ($row = $db->get_nextRow()){
              $testing = new testing();
              $testing->rid = $row["rid"];
              $testing->manager_name = $row["manager_name"];
              $relmanager1[] = $testing;        
          }    
        }
     

        function getpending_count($rid1){
            $db = new DatabaseManager();
            $SQL = sprintf("SELECT  `deviceqty`- `install_device_qty` as `pendingdevice`,(CASE WHEN devicetype=1 THEN 'Basic' WHEN devicetype= 2 THEN 'Advanced'  ELSE '-' END)as devicestatus from ".DB_PARENT.".new_sales where rel_manager=".$rid1." order by orderid desc");
            $db->executeQuery($SQL);
            if($db->get_rowCount() > 0){
            $pendingdevice = Array();
            while ($row = $db->get_nextRow())
            {
                $pendingdevice[] =  $row['pendingdevice'];
            }
            $pending =  array_sum($pendingdevice);
            if($pending=="" ||$pending=="0"){
                $pendings = 0;
            }else{
                $pendings = $pending;
            }
            } 
            return $pendings;
        }
    ?>
    <table align="center" cellpadding="5" border="1">
            <tr><th>CRM Manager</th><th> Pending Installation</th></tr>
             <?php
                                        foreach($relmanager1 as $thismanager1)
                                        {
                                        ?>
                                       <tr><td><b><?php echo $thismanager1->manager_name;?></b></td><td><?php echo  getpending_count($thismanager1->rid);?></td></tr>
                                        <?php
                                            }
                                        ?> 
    </table>
    <br>
    <!-- <?php $dg->Render(); ?> -->
    <div id="myGrid" class="ag-theme-blue" style="height:500px;width:100%;margin:0 auto;border: 1px solid gray">
    </div>
</div>
<br/>

<?php
}
include("footer.php");
?>

<script type="text/javascript">
  $( document ).ready(function() {
      $("#showtextname").hide();
      var device = $('input:radio[name=device]:checked').val();
      if(device == 1){
          $(".adv").hide();
          $("#ac_sensor").hide();
          $("#double_temp").hide();
      }else{
          $(".adv").show();
          if($("#sensor").val() != '0' && device == 2)
          {
              $("#ac_sensor").show();    
          }
          if($('input:radio[name=tempsen]:checked').val() == 2 && device==2){
          $("#double_temp").show();
          }
      }
  });

  function show_newcomp(){
      
      var ordercustomer = $("#ordercustomer").val();
      if(ordercustomer=='-1')
      {
          $("#showtextname").show();
      }else{
          $("#showtextname").hide();
      }
  }


  function show_heirarchy(){
   
    if($("#cmaintenance").is(':checked'))
         $("#heir_tr").show()
      else
         $("#heir_tr").hide()
  }
  $(document).ready(function(){ 
          $('#orderdate').datepicker({
              format: "dd-mm-yyyy",
              language:  'en',
              autoclose: 1
          }); 
  });
 
  function device_type(){
    var device = $('input:radio[name=device]:checked').val();
    if( device == 1){
        $(".adv").hide();
         $("#ac_sensor").hide();
        $("#double_temp").hide();
    }else{
        $(".adv").show();
        if($("#sensor").val() != '0' && device==2)
        {
            $("#ac_sensor").show();    
        }
        if($('input:radio[name=tempsen]:checked').val() == 2 && device==2){
        $("#double_temp").show();
        }
    }
  }
  function sensor_show(){
       if($("#sensor").val() == '1' || $("#sensor").val() == '2' || $("#sensor").val() == '3')
       {
           $("#ac_sensor").show();
       }else{
           $("#ac_sensor").hide();
       }
  }

  function ValidateForm(){
   
   var ordercustomer = $("#ordercustomer").val();
   var custname =  $("#custname").val();
   var deviceqty = $("#deviceqty").val();
   var contactno = $("#contactno").val();
   var orderdate = $("#orderdate").val();
   var shownametext = $("#shownametext").val();
   var devicetype = $('input:radio[name=device]:checked').val();
   var tempsen = $('input:radio[name=tempsen]:checked').val();
   var sensor = $("#sensor").val();
   var pdigital = $('input:checkbox[name=pdigital]:checked').val();
   var pdigitalopp = $('input:checkbox[name=pdigitalopp]:checked').val();
   var panic = $('input:checkbox[name=panic]:checked').val();
   var buzzer =$('input:checkbox[name=buzzer]:checked').val();
   var immobilizer = $('input:checkbox[name=immobilizer]:checked').val();
   
   if(orderdate == ''){
       alert("Please select date");
       return false;
   }else if(ordercustomer=='0'){
       alert("Please select customer");
       return false;
   }else if(shownametext=="" && ordercustomer=='-1'){
           alert("Please enter new customer name");
           return false;
   }else if(deviceqty=="" || deviceqty=='0'){
       alert("Please enter device quantity.");
       return false;
   }else if(custname==""){
       alert("Please enter customer name");
       return false;
   }else if(contactno==""){
       alert("Please enter contact Number");
       return false;
   }else if(devicetype == 2 && sensor==0 && panic!=1 && buzzer !=1 && immobilizer != 1 && ((tempsen==1) || (tempsen==2))  ){
       alert("Please Select Advanced  Features");
       return false;
   }else if(devicetype == 2 && sensor!=0 && ( pdigital != 1 && pdigitalopp != 1)){
       alert("Please Select From Digital Or Is Opposite");
       return false;
   }else if(devicetype == 2 && tempsen ==""){
       alert("Please Select temperature single or double.");
       return false;
   }else{
       $("#orderform").submit();
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
        else { return true; }
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
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var details = <?php echo json_encode($dispdetails)?>;
    var gridOptions;
    columnDefs = [
        {headerName: 'Edit', cellRenderer:'editCellRenderer',width: 70,suppressFilter:true},
        // {headerName: 'Delete', cellRenderer:'deleteCellRenderer',width: 80},
        {headerName:'Order Date',field: 'orderdate',width:170,filter:'agDateColumnFilter', filterParams:{
        comparator:function (filterLocalDateAtMidnight, cellValue){
            var dateAsString = cellValue;
            var dateParts  = dateAsString.split("-");
            var cellDate = new Date(Number(dateParts[2]), Number(dateParts[1]) - 1, Number(dateParts[0]));

            if (filterLocalDateAtMidnight.getTime() === cellDate.getTime()) {
                return 0
            }

            if (cellDate < filterLocalDateAtMidnight) {
                return -1;
            }

            if (cellDate > filterLocalDateAtMidnight) {
                return 1;
            }
        }
        }},
        {headerName:'Company',field: 'customername',width:150,filter: 'agTextColumnFilter'},
        {headerName:'CRM',field: 'relmanager',width:120,filter: 'agTextColumnFilter'},
        {headerName:'Total Devices',field: 'deviceqty',width:150,filter: 'agTextColumnFilter'},
        {headerName:'Pending Devices',field: 'pendingdevice',width:150,filter: 'agTextColumnFilter'},
        {headerName:'Devies Type',field: 'devicestatus',width:120,filter: 'agTextColumnFilter'},
        {headerName:'Contact Person',field: 'contact_person',width:120,filter: 'agTextColumnFilter'},
        {headerName:'Contact Number',field: 'contactno',width:120,filter: 'agTextColumnFilter'},     
        {headerName:'Remark', field:'remark',width:170,filter: 'agTextColumnFilter'},
        {headerName:'Created By',field: 'teamname',width:120,filter: 'agTextColumnFilter'}
    ];
    // function deleteCellRenderer(params){
    //     return "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' onclick='deletepipeline(" +params.data.pipelineid+ ");'><img style='text-align:center; width:20px; height:20px;' src='../../images/delete.png'/></a>"
    // }
    function editCellRenderer(params){
        return "<a href='modifycrmorder.php?oid="+params.data.orderid+"' alt='Edit Mode' title='Mode' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
    }
    gridOptions = {
        enableFilter:true,
        enableSorting: true,
        floatingFilter:true,
        rowData:details,
        animateRows:true,
        columnDefs: columnDefs,

        components: {editCellRenderer : editCellRenderer
        }
    };
    var gridDiv = document.getElementById('myGrid');
    new agGrid.Grid(gridDiv,gridOptions);
</script>
