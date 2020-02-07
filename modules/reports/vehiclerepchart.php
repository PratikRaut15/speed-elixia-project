<?php
// Delete this function
function m2hextra($mins) 
{ 
    if ($mins < 0) 
    { 
        $min = Abs($mins); 
    }
    else
    { 
        $min = $mins; 
    } 
    $H = Floor($min / 60); 
    $M = ($min - ($H * 60)) / 100; 
    $hours = $H +  $M; 
    if ($mins < 0) 
    { 
        $hours = $hours * (-1); 
    } 
    $expl = explode(".", $hours); 
    $H = $expl[0]; 
    if (empty($expl[1])) { 
        $expl[1] = 00; 
    } 
    $M = $expl[1]; 
    if (strlen($M) < 2) { 
        $M = $M . 0; 
    } 
    $HOURS[0] = $H . "." . round($M/60); 
    $HOURS[1] = $H . ":" . $M; 
    return $HOURS; 
}
$chartrows = "";
$chartcol1;
$chartcol2;
switch ($ReportType)
{
    case 'Mileage':
        $charttype = 'ColumnChart';
        $chartcol1 = 'Distance Travelled [KM]';
        foreach ($vehiclereps as $vehiclerep)
        {
            $vehiclerep[1] = $vehiclerep[1];
            $chartrows .= "[$vehiclerep[0],$vehiclerep[1]],";
        }
        break;
    case 'Utilization':
        $charttype = 'PieChart';
        $runningtime = 0;
        $idletime = 0;
        foreach ($vehiclereps as $vehiclerep)
        {
            $idletime += $vehiclerep[1];
            $runningtime += $vehiclerep[2];
        }
        $idle = m2hextra($idletime);
        $running = m2hextra($runningtime);
        $chartrows .= "['IdleTime - $idle[1]',".$idle[0]."],";
        $chartrows .= "['RunningTime - $running[1]',".$running[0]."],";
        break;
    case 'Overspeed':
//        $charttype = 'LineChart';
//        $chartcol1 = 'OverSpeed Incidents';
//        foreach ($vehiclereps as $vehiclerep)
//        {
//            $chartrows .= "[$vehiclerep[0],$vehiclerep[1]],";
//        }
        $charttype = 'ColumnChart';
        $chartcol1 = 'OverSpeed Incidents';
        foreach ($vehiclereps as $vehiclerep)
        {
            $vehiclerep[1] = $vehiclerep[1];
            $chartrows .= "[$vehiclerep[0],$vehiclerep[1]],";
        }
        break;
    case 'FenceConflict':
        $charttype = 'LineChart';
        $chartcol1 = 'Fence Conflicts';
        foreach ($vehiclereps as $vehiclerep)
        {
            $chartrows .= "[$vehiclerep[0],$vehiclerep[1]],";
        }
        break;
    case 'Speed':
        $charttype = 'ComboChart';
        $chartcol1 = 'Total Distance Travelled [KM] [Left Axis]';
        $chartcol2 = 'Avg Speed [Running Time] [KM/HR] [Right Axis]';
        foreach ($vehiclereps as $vehiclerep)
        {
            $vehiclerep[1] = $vehiclerep[1];
            $chartrows .= "[$vehiclerep[0],$vehiclerep[1],$vehiclerep[2]],";
        }
        break;
}
$chartrows = rtrim($chartrows,',');
?>
<script type="text/javascript">
  Array.prototype.reduce = undefined;


      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart()
      {
        // Create the data table.
        var data = new google.visualization.DataTable();
        
       <?php
        if($ReportType=='Speed')
        {
            echo "data.addColumn('date','Date');";
            echo "data.addColumn('number', '$chartcol1');";
            echo "data.addColumn('number', '$chartcol2');";
        }
        else if($ReportType!='Utilization')
        {
            echo "data.addColumn('date','Date');";
            echo "data.addColumn('number', '$chartcol1');";
        }
        else
        {
            echo "data.addColumn('string','Activity');";
            echo "data.addColumn('number','Value');";
        }
        echo "data.addRows([$chartrows])";
        ?>
        

        // Set chart options
        <?php if($ReportType!='Speed') {?>
        var options = {'title':'<?php echo $ReportType." Report - Vehicle No ".$vehiclename." - From Date : ".date('jS M, Y', strtotime($STdate))." - To Date : ".date('jS M, Y', strtotime($EDdate));?> ',
                       'is3D': true,
                       'chartArea': {width: '70%', height: '70%'}
                   };
        
        <?php } else {?>
        var options = {'title':'<?php echo "Avg Speed & Distance Travelled Report - Vehicle No ".$vehiclename." - From Date : ".date('jS M, Y', strtotime($STdate))." - To Date : ".date('jS M, Y', strtotime($EDdate));?> ', 
                       'chartArea': {width: '70%', height: '70%'},
                       'seriesType': 'bars',
                       'series': {0:{targetAxisIndex:0},1: {type: "line",targetAxisIndex:1,visibleInLegend:true}},
                       'legend': {position: 'bottom'}
                   };
            <?php }?>
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.<?php echo $charttype;?>(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
</script>