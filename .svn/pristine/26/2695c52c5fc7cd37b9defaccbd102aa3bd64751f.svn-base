<?php
// Delete this function

$chartrows = "";
$chartcol1;
$chartcol2;

$charttype = 'LineChart';
$chartcol1 = 'Fuel In Lt';
$chartcol2 = 'Fuel In Alert';
$showblue = true;
//$chartcol2 = 'Fuel Consumed In Lt';
foreach ($vehiclereps as $vehiclerep)
   {
        
        //$vehiclerep[2] = $vehiclerep[2];
        if($vehiclerep[1] > $percentage && $vehiclerep[1] <= $fuelcapacity[0]->fuelcapacity)
        {
                    $vehiclerep_new[1] = $vehiclerep[1];
                    if($vehiclerep[1] >= $percentage && $showblue == false)
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
                    if($vehiclerep[1] <= $percentage && $showblue == true)
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
        
        //$chartrows .= "[$vehiclerep[0],$vehiclerep[1]],";
   }
  $chartrows = rtrim($chartrows,',');
if($chartrows){
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
        
            echo "data.addColumn('datetime','Date');";
            echo "data.addColumn('number', '$chartcol1');";
            echo "data.addColumn('number', '$chartcol2');";
            echo "data.addRows([$chartrows])";
        ?>
        

        // Set chart options
        
        var options = {'title':'<?php echo " Fuel Consumption Report - Vehicle No ".$vehiclename." - From Date : ".date('jS M, Y', strtotime($STdate))." - To Date : ".date('jS M, Y', strtotime($EDdate));?> ',
                      curveType: 'function',                      
                       'chartArea': {width: '70%', height: '70%'},
                       minValue:'0'
                       
                   };
        
       
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.<?php echo $charttype;?>(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
</script>
<?php
}
else{
  echo "Data Not Available, Please Check The Dates And Avegare Of Vehicle Should Be Enterd";  
}
?>