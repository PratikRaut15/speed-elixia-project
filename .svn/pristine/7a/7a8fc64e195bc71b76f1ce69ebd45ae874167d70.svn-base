<?php
// Delete this function
$chartrows = "";
$chartcol1;
$chartcol2;
switch ($ReportType)
{
    case 'Temperature':
        $charttype = 'LineChart';
        $chartcol1 = 'Temperature [C]';
        $chartcol2 = 'Temperature Conflict [C]';        
        foreach ($vehiclereps as $vehiclerep)
        {
            $vehiclerep[1] = $vehiclerep[1];
            $chartrows .= "[$vehiclerep[0],$vehiclerep[1]],";
        }
        break;
    case 'TemperatureDaily':
        $charttype = 'LineChart';
        $chartcol1 = 'Temperature [C]';
        $chartcol2 = 'Temperature Conflict [C]';  
        $showblue = true;                    
        foreach ($vehiclereps as $vehiclerep)
        {
            if($tempselect == "1")
            {
                if($vehiclerep[1] > $tempcontrol->min1 && $vehiclerep[1] < $tempcontrol->max1)
                {
                    $vehiclerep_new[1] = $vehiclerep[1];                    
                    if($vehiclerep[1] > $tempcontrol->min1 && $showblue == false)
                    {
                        $chartrows .= "[$vehiclerep[0],$vehiclerep_new[1],$vehiclerep_new[1]],";                           
                        $showblue = true;
                    }
                    else
                    {
                        $chartrows .= "[$vehiclerep[0],$vehiclerep_new[1],null],";                    
                        $showblue = true;
                    }                    
                }
                else
                {
                    $vehiclerep_new[2] = $vehiclerep[1];                                    
                    if($vehiclerep[1] < $tempcontrol->max1 && $showblue == true)
                    {
                        $chartrows .= "[$vehiclerep[0],$vehiclerep_new[2],null],";                    
                        $showblue = false;                        
                    }
                    else
                    {
                        $chartrows .= "[$vehiclerep[0],$vehiclerep_new[2],$vehiclerep_new[2]],";   
                        $showblue == true;
                    }
                }
            }
            elseif($tempselect == "2")
            {
                if($vehiclerep[1] > $tempcontrol->min2 && $vehiclerep[1] < $tempcontrol->max2)
                {
                    $vehiclerep_new[1] = $vehiclerep[1];                    
                    if($vehiclerep[1] > $tempcontrol->min2 && $showblue == false)
                    {
                        $chartrows .= "[$vehiclerep[0],$vehiclerep_new[1],$vehiclerep_new[1]],";                           
                        $showblue = true;
                    }
                    else
                    {
                        $chartrows .= "[$vehiclerep[0],$vehiclerep_new[1],null],";      
                        $showblue = true;
                    }
                       
                }
                else
                {
                    $vehiclerep_new[2] = $vehiclerep[1];                                    
                    if($vehiclerep[1] < $tempcontrol->max2 && $showblue == true)
                    {
                        $chartrows .= "[$vehiclerep[0],$vehiclerep_new[2],null],";                    
                        $showblue = false;                        
                    }
                    else
                    {
                        $chartrows .= "[$vehiclerep[0],$vehiclerep_new[2],$vehiclerep_new[2]],";   
                        $showblue == true;
                    }                    
                }                
            }
        }
        break;
}
$chartrows = rtrim($chartrows,',');
?>
<style> 
body{ text-align: center;}
#chart_div{width: 100%; margin: 0 auto; text-align: left;}
@media print {
    .myDivToPrint {
        background-color: white;
        height: 100%;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        margin: 0;
        padding: 15px;
        font-size: 14px;
        line-height: 18px;
    }
    #date{
        width: 90% !important;
        margin-left: 15%;
    }
}
</style>
<script type="text/javascript">
  Array.prototype.reduce = undefined;
</script>
<script type="text/javascript">

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
        if($ReportType=='Temperature')
        {
            //echo "data.addColumn('date','Date');";
            echo "data.addColumn('datetime', 'Time Of Day');";
            echo "data.addColumn('number', '$chartcol1');";
        }
        else if($ReportType=='TemperatureDaily')
        {
            //echo "data.addColumn('date','Date');";
            echo "data.addColumn('datetime', 'Date');";
            echo "data.addColumn('number', '$chartcol1');";
            echo "data.addColumn('number', '$chartcol2');";            
        }
        echo "data.addRows([$chartrows])";
        ?>
        

        // Set chart options
        <?php if($ReportType=='Temperature') {?>
        var options = {'title':'<?php echo "Temperature Hourly Report - Vehicle No ".$vehiclename." - From Date : ".date('jS M, Y', strtotime($STdate))." - To Date : ".date('jS M, Y', strtotime($EDdate));?> ',
                       'is3D': true,
                       'chartArea': {width: '70%', height: '70%'}
                   };
        
        <?php } else if($ReportType=='TemperatureDaily') {?>
        var options = {'title':'<?php echo "Temperature Daily Report - Vehicle No ".$vehiclename." - From Date : ".date('jS M, Y', strtotime($STdate))." - To Date : ".date('jS M, Y', strtotime($EDdate));?> ',
                       'is3D': true,
                       legend: { position: 'bottom' }
                   };
            <?php }?>
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.<?php echo $charttype;?>(document.getElementById('chart_div'));
        chart.draw(data, options);        
      }
</script>