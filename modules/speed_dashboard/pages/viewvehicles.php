
<div id="search_table" class="container" style="margin-top:5px;">
    <div class="row-fluid"> 
        <script type='text/javascript' src='https://www.google.com/jsapi'></script>       
        <script type="text/javascript">
            // Load the Visualization API and the piechart package.
            google.load('visualization', '1.1', {'packages': ['corechart']});
            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart);
            // Callback that creates and populates a data table, 
            // instantiates the pie chart, passes in the data and
            // draws it.
            function drawChart() {
                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Status');
                data.addColumn('number', 'Number of Vehicles');
<?php $catch = get_piechart_data_for_vehicle_status(); ?>
                data.addRows([
                    ['Overspeed', <?php echo $catch['overspeed'] ?>],
                    ['Running', <?php echo $catch['running'] ?>], ['Idle-Ign On', <?php echo $catch['idleignon'] ?>],
                    ['Idle-Ign Off', <?php echo $catch['idleignoff'] ?>], ['Inactive', <?php echo $catch['inactive'] ?>],
                ]);

                // Set chart options
                var options = {'title': 'Vehicle Status',
                    'is3D': true,
                    'slices': {0: {color: '#FF0000'}, 1: {color: '#009900'}, 2: {color: '#0066FF'}, 3: {color: '#FF9900'}, 4: {color: '#707070'}},
                    'width': 350,
                    'height': 300,
                    pieSliceText: 'value',
                    tooltip: {
                        text: 'value'
                    }
                };


                // Create the data table.  
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.PieChart(document.getElementById('chart_div1'));
                chart.draw(data, options);

            }
        </script>


        <script type="text/javascript">
            // Load the Visualization API and the piechart package.
            google.load('visualization', '1.1', {'packages': ['corechart']});
            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart);
            // Callback that creates and populates a data table, 
            // instantiates the pie chart, passes in the data and
            // draws it.
            function drawChart() {


                var datachk = new google.visualization.DataTable();
                datachk.addColumn('string', 'Checkpoint Name');
                datachk.addColumn('number', 'Number of Vehicles');
                datachk.addRows([
<?php get_piechart_data_for_checkpoint(); ?>

                ]);

                // Set chart options
                var options_checkpoints = {'title': 'Checkpoint Status',
                    'is3D': true,
                    'width': 350,
                    'height': 300,
                    pieSliceText: 'value',
                    tooltip: {
                        text: 'value'
                    }
                };



                // Instantiate and draw our chart, passing in some options.
                var chartchk = new google.visualization.PieChart(document.getElementById('chart_div2'));
                chartchk.draw(datachk, options_checkpoints);
            }
        </script>

        <script type="text/javascript">
            // Load the Visualization API and the piechart package.
            google.load('visualization', '1.1', {'packages': ['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart);
            // Callback that creates and populates a data table, 
            // instantiates the pie chart, passes in the data and
            // draws it.
            function drawChart() {

<?php $idle = get_piechart_data_for_vehicle_idle(); ?>
                var dataidle = new google.visualization.DataTable();
                dataidle.addColumn('string', 'Status');
                dataidle.addColumn('number', 'Number of Vehicles');
                dataidle.addRows([
                    ['Not Idle', <?php echo $idle['notidle'] ?>],
                    ['Idle Since 0 hr', <?php echo $idle['zero'] ?>],
                    ['Idle Since 1 hr', <?php echo $idle['one'] ?>],
                    ['Idle Since 3 hrs', <?php echo $idle['three'] ?>],
                    ['Idle Since 5 hrs', <?php echo $idle['five'] ?>],
                    ['Idle Since 24 hrs', <?php echo $idle['twofour'] ?>]
                ]);

                // Instantiate our chart.
                var chartidle = new google.visualization.PieChart(document.getElementById('chart_div3'));
                // Set chart options
                var options_idle = {'title': 'Idle Vehicles', 'is3D': true, 'width': 350, 'height': 300, pieSliceText: 'value'};

                chartidle.draw(dataidle, options_idle);

            }
        </script>
        <!-- Dont Remove This Code Its used in real time slide map -->
        <script type="text/javascript">
            google.load("visualization", "1.1", {packages: ["gauge"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Fuel', 50]
                ]);
                var options = {
                    width: 320, height: 220,
                    redFrom: 0, redTo: 25,
                    minorTicks: 10,
                    max: 200
                };
                var chart = new google.visualization.Gauge(document.getElementById('chart_fuel1'));
                chart.draw(data, options);
            }
        </script>

        <div  class="span12">
            <center>
                <?php if (array_filter($idle)) { ?>
                    <?php if ($_SESSION['portable'] != '1') { ?>
                        <div id="chart_div1" style=" height: 300px; width: 300px; float: left; "></div>
                    <?php } ?>
                    <div id="chart_div2" style=" height: 300px; width: 300px; float: left;"></div>
                    <?php if ($_SESSION['portable'] != '1') { ?>
                        <div id="chart_div3" style=" height: 300px; width: 300px; float: left;"></div>
                    <?php } ?>
                <?php } else { ?>
                    <?php if ($_SESSION['portable'] != '1') { ?>
                        <div id="chart_div1" style=" height: 300px; " class="span5"></div>
                    <?php } ?>
                    <div id="chart_div2" style=" height: 300px; " class="span5"></div>
                <?php } ?>

                <div style="clear: both;"></div>
            </center>
        </div>
    </div>

    <hr />

    <div class="row border-bottom white-bg dashboard-header">

    </div>

</div>



</div>
