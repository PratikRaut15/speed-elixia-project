<?php
$chartrows = "";
$chartcol1;
$chartcol2;
$charttype = 'ColumnChart';
$chartcol1 = 'Fuel Consumption In Litre';
$chartcount = count($vehiclereps);
foreach ($vehiclereps as $vehiclerep)
{
            
     $chartrows .= "['$vehiclerep[0]',$vehiclerep[1],$vehiclerep[2]],"; 
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
          ['Vehicle No', 'Fuel Consumption In Litre', 'Fuel Refilled In Litre'],
         <?php
         echo $chartrows;
         ?>
        ]);

        var options = {
          title: 'Fuel Consumption',
          height:'100%',
          vAxis: {title: 'Vehicles',  titleTextStyle: {color: 'red'}},
          hAxis: {title: 'Consumption In Litre',  titleTextStyle: {color: 'red'}},
          chartArea:{top:30,width:"50%",height:"75%"},
          legend:'bottom'
        };

        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

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
