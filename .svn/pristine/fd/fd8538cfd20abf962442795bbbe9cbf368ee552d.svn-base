<?php

include_once("session.php");
include("loginorelse.php");
include("../../lib/bo/DocketManager.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
$_stylesheets[] = "bootstrap.css";
date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$teamid = GetLoggedInUserId();
include("header.php");
$dObj = new docketManager;
$result_tickets = $dObj->getTicket_Count($teamid);
$tickets = array();
$tickets_title[]=array(0=>'Status',1=>'Count');

foreach($result_tickets as $row){
$tickets[]=array(0=>$row['status'],1=>intval($row['Total']));
}
$tickets=array_merge($tickets_title,$tickets);
$sum_tickets = 0;

foreach($result_tickets as $num => $values) {
    $sum_tickets += $values[ 'Total' ];
}

$encodedTickets = json_encode($tickets);

$result_buckets = $dObj->getBucket_Count($teamid);
$buckets = array();
$buckets_title[]=array(0=>'Status',1=>'Count');

foreach($result_buckets as $row){
$buckets[]=array(0=>$row['status'],1=>intval($row['Total']));
}
$buckets=array_merge($buckets_title,$buckets);

$sum_buckets = 0;

foreach($result_buckets as $num => $values) {
    $sum_buckets += $values[ 'Total' ];
}
$encodedBuckets = json_encode($buckets);

$result_customers = $dObj->getCustomer_Count($teamid);
$customers = array();

$customer_title[]=array(0=>'prodName',1=>'Count');
foreach($result_customers as $row){
$customers[]=array(0=>$row['prodName'],1=>intval($row['total']));
}

$customers=array_merge($customer_title,$customers);
$sum_customers = 0;

foreach($result_customers as $num => $values) {
    $sum_customers += $values[ 'total' ];
}

$encodedCustomers = json_encode($customers);

?>
<html>
  <head>
    <link rel="stylesheet" href="../../css/docketStyle.css">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
      google.charts.load("current", {'packages':["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(
          <?php  echo $encodedTickets;?>);

    var colors =
    {
      'Open': '#3366CC',
      'In Progress': '#008B8B',
      'Closed': '#DAA520',
      'Pipeline': '#FF0019',
      'On Hold':'#228B22',
      'Waiting for Client': '#000000',
      'Resolved':'#343434',
      'Reopen':'#707070'
    };
  var slices = [];
  for (var i = 0; i < data.getNumberOfRows(); i++) {
    slices.push({
      color: colors[data.getValue(i, 0)]
    });
  }


        var options = {
          title: 'Ticket Status',
          legend: { position: 'right', alignment: 'start' },
          pieSliceText: 'label',
          pieHole:0.4,
          tooltip: { isHtml: true },
          chartArea: {width: 400, height: 250},
          slices: slices

        };

        var ticket_container = document.getElementById('ticketCount');
        var chart = new google.visualization.PieChart(ticket_container);
        chart.draw(data, options);
      }
</script>
<script type="text/javascript">
      google.charts.load("current", {'packages':["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(
          <?php  echo $encodedBuckets;?>);
        var colors = {
    'Open': '#3366CC',              //Blue
    'Reschedule': '#CC0066',        //Purple
    'Successful': '#228B22',        //Green
    'Unsuccessful': '#FF0019',      //Red
    'FE Assigned':'#008B8B',        //Cyan
    'Cancel': '#000000',            //Black
    'Incomplete':'#343434'          //Grey

  };
  var slices = [];
  for (var i = 0; i < data.getNumberOfRows(); i++) {
    slices.push({
      color: colors[data.getValue(i, 0)]
    });
  }
        var options = {
          title: 'Bucket Status',
          legend: { position: 'right', alignment: 'start' },
          pieSliceText: 'label',
          pieHole:0.4,
          tooltip: { isHtml: true },
          chartArea: {width: 400, height: 250},
          slices: slices
        };


  var bucket_container = document.getElementById('bucketCount');
  var chart = new google.visualization.PieChart(bucket_container);
  chart.draw(data, options);
      }
</script>
<script type="text/javascript">
      google.charts.load("current", {'packages':["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(
          <?php  echo $encodedCustomers;?>);
        var colors = {
    'Elixia Speed': '#00a6b8',
    'Elixia Trace': '#00a1af',
    'Elixia ERP': '#f15d3a',
    'Elixia Fleet': '#007cc2',
    'Elixia Docs':'#a83b7c',
    'Elixia Sales': '#00794b',
    'Elixia Books':'#262262',
    'Elixia Stock': '#a8855c',
    'Elixia Trip':'#343434',
    'Elixia Client': '#000000',
    'Elixia Driver':'#343434',
    'Elixia Monitor': '#4457a4'

  };
  var slices = [];
  for (var i = 0; i < data.getNumberOfRows(); i++) {
    slices.push({
      color: colors[data.getValue(i, 0)]
    });
  }
        var options = {
          title: 'Customers',
          legend: { position: 'right', alignment: 'start' },
          pieSliceText: 'label',
          pieHole:0.4,
          tooltip: { isHtml: true },
          chartArea: {width: 400, height: 250},
          slices: slices
        };


  var bucket_container = document.getElementById('customerCount');
  var chart = new google.visualization.PieChart(bucket_container);
  chart.draw(data, options);

      }
</script>


  </head>
  <body>
      <div class="col-md-4">
        <div id="ticketCount" style="width: 400px; height: 400px;"></div>
        <div id="center_ticket"></div>
      </div>
      <div class="col-md-4">
        <div id="bucketCount" style="width: 400px; height:400px;"></div>
        <div id="center_bucket"></div>
      </div>
      <div class="col-md-4">
        <div id="customerCount" style="width: 400px; height:400px;"></div>
        <div id="center_customer"></div>
      </div>
  </body>



<script>
  jQuery(document).ready(function () {
    var totalBuckets = <?php echo $sum_buckets ?>;
    $('#center_bucket').text(totalBuckets);


    var totalTickets = <?php echo $sum_tickets ?>;
    $('#center_ticket').text(totalTickets);

    var totalCustomers = <?php echo $sum_customers ?>;
    $('#center_customer').text(totalCustomers);
  });
</script> 


  <style>
  #center_ticket{
display:block;
width:240px;
height:240px;
text-align:center;
vertical-align: middle;
position: absolute;
top: 180px;  /* chartArea top  */
left: 20px; /* chartArea left */
font-size:24px;

}
 #center_bucket{
display:block;
width:240px;
height:240px;
text-align:center;
vertical-align: middle;
position: absolute;
top: 180px;  /* chartArea top  */
left: 20px; /* chartArea left */
font-size:24px;
}
 #center_customer{
display:block;
width:240px;
height:240px;
text-align:center;
vertical-align: middle;
position: absolute;
top: 180px;  /* chartArea top  */
left: 20px; /* chartArea left */
font-size:24px;
}
.overlay {
display:block;
width:240px;
height:240px;
text-align:center;
vertical-align: middle;
position: absolute;
top: 180px;  /* chartArea top  */
left: 20px; /* chartArea left */
font-size:24px;
}
</style>
</html>
