<?php
$chartrows = "";
$chartcol1;
$chartcol2;
$charttype = 'ColumnChart';
$chartcol1 = 'Distance Travelled [KM]';
foreach ($vehiclereps as $vehiclerep)
{
            
     $chartrows .= "['$vehiclerep[0]',$vehiclerep[1]],"; 
}
$chartrows = rtrim($chartrows,',');

if($chartrows){

?>
<script type="text/javascript">
    Array.prototype.reduce = undefined;
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Vehicle No', 'Distance Travelled(in Km)'],
         <?php
         echo $chartrows;
         ?>
        ]);

        var options = {
          title: 'Fuel Consumption',
          vAxis: {title: 'Vehicles',  titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.BarChart(document.getElementById('chart_divAll'));

        chart.draw(data, options);
      }
    </script>
    
 <?php
}
else{
    echo "<script type='text/javascript'>
                    jQuery('#error').show();jQuery('#error').fadeOut(3000);
                </script>";
}
?>
