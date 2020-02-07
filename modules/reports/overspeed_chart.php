<?php
$chartrows = "";
$chartcol1;
$chartcol2;
$charttype = 'ColumnChart';
$chartcol1 = 'Overspeed Times';
$chartcount = count($vehiclereps);
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
          ['Vehicle No', 'Ovespeed Times'],
         <?php
         echo $chartrows;
         ?>
        ]);

        var options = {
          title: 'Overspeed Report',
          height:'100%',
          vAxis: {title: 'Vehicles',  titleTextStyle: {color: 'red'}},
          hAxis: {title: 'Number Of Times',  titleTextStyle: {color: 'red'}},
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