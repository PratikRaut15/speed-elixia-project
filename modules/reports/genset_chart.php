<?php
$chartrows = "";
$chartcol1;
$chartcol2;
$charttype = 'ColumnChart';
$chartcol1 = $_SESSION["digitalcon"].' Usage in Hours : Minutes';
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
          ['Vehicle No', '<?php echo $_SESSION["digitalcon"];?> Usage in Hours : Minutes'],
         <?php
         echo $chartrows;
         ?>
        ]);

        var options = {
          title: '<?php echo $_SESSION["digitalcon"];?>  Usage',
          height:'100%',
          vAxis: {title: 'Vehicles',  titleTextStyle: {color: 'red'}},
          hAxis: {title: 'Time In Minutes',  titleTextStyle: {color: 'red'}},
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
