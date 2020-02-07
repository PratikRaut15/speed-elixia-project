<?php
$title = 'Sqlite Report';

//echo table_header($title, $subTitle);
?>

<?php
$SDate = $STdate;
$STdate = date('d-m-Y', strtotime($STdate));
$t_columns = '';
$colspan = 0;

if($colspan>15){
    echo "<style>.newTable th, .newTable td{padding:3px;}</style>";
    echo '<div class="container-fluid" >'; //style="overflow:scroll;overflow-y:hidden;"
}
else{
    echo '<div class="container">';
}
?>


 <table id='search_table_2' class="table newTable">
    <thead>
        <tr><th colspan="<?php echo $_SESSION['temp_sensors']+1; ?>"> Vehicle No. <?php echo "<b>".$vehicleno."</b>" ?></th></tr>
    <tr>
        <th>Last Updated</th>
        <?php
                if ($_SESSION['temp_sensors'] == 1) {
                    echo "<th>Temperature (&deg;C)</th>";
                }
                if ($_SESSION['temp_sensors'] == 2) {
                    echo "<th>Temperature 1 (&deg;C)</th><th>Temperature 2 (&deg;C)</th>";
                }
                if ($_SESSION['temp_sensors'] == 3) {
                    echo "<th>Temperature 1 (&deg;C)</th><th>Temperature 2 (&deg;C)</th>";
                    echo "<th>Temperature 3 (&deg;C)</th>";
                }
                if ($_SESSION['temp_sensors'] == 4) {
                     echo "<th>Temperature 1 (&deg;C)</th><th>Temperature 2 (&deg;C)</th>";
                    echo "<th>Temperature 3 (&deg;C)</th><th>Temperature 4 (&deg;C)</th>";
                    /*$t1 = getName_ByType($unit->n1);
                    if ($t1 == '') {
                        $t1 = 'Temperature 1';
                    }
                    $t2 = getName_ByType($unit->n2);
                    if ($t2 == '') {
                        $t2 = 'Temperature 1';
                    }
                    $t3 = getName_ByType($unit->n3);
                    if ($t3 == '') {
                        $t3 = 'Temperature 1';
                    }
                    $t4 = getName_ByType($unit->n4);
                    if ($t4 == '') {
                        $t4 = 'Temperature 1';
                    }
                    echo "<th>" . $t1 . " (&deg;C)</th><th>" . $t2 . " (&deg;C)</th>";
                    echo "<th>" . $t3 . " (&deg;C)</th><th>" . $t4 . " (&deg;C)</th>";*/
                }
                ?>  
        

    </tr>
    <!-- <tr class='tableSub' >
    
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    
    </tr> -->
    </thead>
