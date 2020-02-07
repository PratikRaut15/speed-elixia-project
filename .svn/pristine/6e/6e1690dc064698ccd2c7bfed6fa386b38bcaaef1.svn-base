<?php
include_once("session.php");
include("loginorelse.php");
include("../../lib/bo/DocketManager.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
date_default_timezone_set("Asia/Calcutta");
$teamid = GetLoggedInUserId();
include("header.php");
$dm=new DocketManager();
$startdate=date('Y-m-d',strtotime('first day of january this year'));
$enddate=date('Y-m-d',strtotime('last day december'));

$ticketAnalysis = new stdClass();
$ticketAnalysis->startdate=$startdate;
$ticketAnalysis->enddate=$enddate;
$ticketAnalysis->teamId = "";
$ticketAnalysis->status = "";
$ticketAnalysisArray=$dm->getTicketAnalysis($ticketAnalysis); //TICKET ANALYSIS

$bucketAnalysis = new stdClass();
$bucketAnalysis->startdate=$startdate;
$bucketAnalysis->enddate=$enddate;
$bucketAnalysis->teamId = "";
$bucketAnalysis->status = "";
$bucketAnalysis->purpose = "";
$bucketAnalysisArray=$dm->getBucketAnalysis($bucketAnalysis); //BUCKET ANALYSIS
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
</head>
<body>
<form name="crm_analysis_ticket" id="crm_analysis_ticket" method="POST">
<!-- Enter Month: 
<select name='month_ticket' id='month_ticket'>
<option value=0>Select</option>
<option value=January>January</option>
<option value=Febraury>Febraury</option>
<option value=March>March</option>
<option value=April>April</option>
<option value=May>May</option>
<option value=June>June</option>
<option value=July>July</option>
<option value=August>August</option>
<option value=September>September</option>
<option value=October>October</option>
<option value=November>November</option>
<option value=December>December</option>
</select>
<select name="teamId_ticket" id="teamId_ticket">
</select>
<select name="status_ticket" id="status_ticket">
</select>
<input type="button" name="ticket_analysis" id="ticket_analysis" onclick="getAnalysis_ticket();" value="Submit" /> -->
<div id="Ticket_Div" style="text-align: center;margin:30px auto;">
<p style="font-size: 20px;">TICKET ANALYSIS </p>
<div id="ticketGrid" class="ag-theme-blue" style="height:200px;width:85%;margin:0 auto;">
</div>  
</div>
</form>
<form name="crm_analysis_bucket" id="crm_analysis_bucket" method="POST"><!-- 
Enter Month: 
<select name='month_bucket' id='month_bucket'>
<option value=0>Select</option>
<option value=January>January</option>
<option value=Febraury>Febraury</option>
<option value=March>March</option>
<option value=April>April</option>
<option value=May>May</option>
<option value=June>June</option>
<option value=July>July</option>
<option value=August>August</option>
<option value=September>September</option>
<option value=October>October</option>
<option value=November>November</option>
<option value=December>December</option>
</select>
<select name="teamId_bucket" id="teamId_bucket">
</select>
<select  name="status_bucket" id="status_bucket">
<option value=-1>Select Status</option>
<option value=1>Reschedule</option>
<option value=2>Successful</option>
<option value=3>Unsuccessful</option>
<option value=5>Cancel</option>
<option value=6>Incomplete</option>
</select>
<select name='OperationType' id='OperationType'>
<option value=-1>Select Puprose</option>
<option value=1>Installation</option>
<option value=2>Repair</option>
<option value=4>Replacement</option>
<option value=5>Reinstall</option>
<option value=3 >Removal</option>
</select>
<input type="button" name="analysis" id="analysis" onclick="getAnalysis_bucket();" value="Submit" /> -->
<div id="Bucket_Div" style="text-align: center;margin:30px auto;font-size: 20px">
<p style="font-size: 20px;">BUCKET ANALYSIS </p>
<div id="bucketGrid" class="ag-theme-blue" style="height:200px;width:85%;margin:0 auto;">
  
</div>
</div>
</form>
</body>
<script>
  var bucketGridOptions;
  var ticketGridOptions;
  var bucketCols = [
    {headerName:'Purpose',field: 'purpose'},
    {headerName:'Status',field: 'status'},
    {headerName:'CRM',field: 'CRM'},
    {headerName:'Count',field: 'count'},
    {headerName:'Week', field: 'week'},
    {headerName:'Month', field: 'month'}
  
];
  var ticketCols = [
    {headerName:'Status',field: 'status'},
    {headerName:'Ticket type',field: 'type'},
    {headerName:'CRM',field: 'CRM'},
    {headerName:'Count',field: 'count'},
    {headerName:'Week', field: 'week'},
    {headerName:'Month', field: 'month'}
    
];
var row = [];

  jQuery(document).ready(function () {
    jQuery.ajax({
      type: "POST",
      url: "Docket_functions.php",
      data: "get_crm=1",
      success: function(data){
        var data=JSON.parse(data);
        $('#teamId_ticket').html("");
        $('#teamId_ticket').append('<option value = '+"0"+'>'+"Select CRM"+'</option>');
        //<-------- add this line
        $.each(data ,function(i,text){
          $('#teamId_ticket').append('<option value = '+text.teamid+'>'+text.name+'</option>');
        });

        $('#teamId_bucket').html("");
        $('#teamId_bucket').append('<option value = '+"0"+'>'+"Select CRM"+'</option>');
        //<-------- add this line
        $.each(data ,function(i,text){
          $('#teamId_bucket').append('<option value = '+text.teamid+'>'+text.name+'</option>');
        });
      }
    });


   jQuery.ajax({
      type: "POST",
      url: "Docket_functions.php",
      data: "get_status=1",
      success: function(data){
        var data=JSON.parse(data);
        $('#status_ticket').html("");
        $('#status_ticket').append('<option value = '+"-1"+'>'+"Select Status"+'</option>');
        //<-------- add this line
        $.each(data ,function(i,text){
          $('#status_ticket').append('<option value = '+text.id+'>'+text.status+'</option>');
        });
      }
    });
   var bucketArray = <?php echo json_encode($bucketAnalysisArray);?> ;
   var ticketArray = <?php echo json_encode($ticketAnalysisArray);?> ;
   // $.each(bucketArray,function(i,row){

   // });
  bucketGridOptions = {
    enableFilter:true,
    enableSorting: true,
    rowData:bucketArray,
    groupIncludeFooter: true,
    animateRows: true,
    columnDefs: bucketCols,
    onGridReady: function(params) {
       params.api.sizeColumnsToFit();
    }
  };
  ticketGridOptions = {
    enableFilter:true,
    enableSorting: true,
    groupIncludeFooter: true,
    rowData:ticketArray,
    animateRows: true,
    columnDefs: ticketCols,
    onGridReady: function(params) {
       params.api.sizeColumnsToFit();
    }
  };

   // jQuery.ajax({
   //    type: "POST",
   //    url: "Docket_functions.php",
   //    data: "get_status_bucket=1",
   //    success: function(data){
   //      var data=JSON.parse(data);
   //      $('#status_bucket').html("");
   //      $('#status_bucket').append('<option value = '+"-1"+'>'+"Select Status"+'</option>');
   //      //<-------- add this line
   //      $.each(data ,function(i,text){
   //        $('#status_bucket').append('<option value = '+text.id+'>'+text.type+'</option>');
   //      });
   //    }
   //  });

 });
document.addEventListener('DOMContentLoaded', function() {
    var gridDiv = document.querySelector('#bucketGrid');
    new agGrid.Grid(gridDiv, bucketGridOptions);
    gridDiv = document.querySelector('#ticketGrid');
    new agGrid.Grid(gridDiv, ticketGridOptions);
    
});
agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
//  function getAnalysis_ticket(){
// if($("#month_ticket").val()==0){
//     alert("Enter a proper month");
//     return false;
// }
// var data = $("#crm_analysis_ticket").serialize();
// jQuery.ajax({
//       type: "POST",
//       url: "Docket_functions.php",
//       data: data+"&get_ticket_analysis=1",
//       success: function (response) {
//         console.log(response);//TICKET ANALYSIS
// }
// });
// }
// function getAnalysis_bucket(){
// if($("#month_bucket").val()==0){
//     alert("Enter a proper month");
//     return false;
// }
// var data = $("#crm_analysis_bucket").serialize();
// jQuery.ajax({
//       type: "POST",
//       url: "Docket_functions.php",
//       data: data+"&get_bucket_analysis=1",
//       success: function (response) {
//         console.log(response);//BUCKET ANALYSIS
// }
// });

// } 

</script>
</html>
