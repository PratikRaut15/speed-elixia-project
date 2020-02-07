<div class="container">
<table class="table newTable" >
<thead>
    <tr>
    <?php
foreach($columns as $s_columns){
    echo "<th>$s_columns</th>";
}
    ?>
    </tr>
</thead>
<tbody>
<?php

if($reports['status']==1){
    $totalfuel = 0;
    $last_date = null;
    foreach($reports['tbl_data'] as $report){
        $cur_date = date('d-m-Y',strtotime($report->date));
        if($cur_date!=$last_date){
            echo "<tr><th style='background:#d8d5d6' colspan='100%'>$cur_date</th></tr>";
        }
        echo "
        <tr>
            <td>$report->time</td>
            <td>$report->location</td>
            <td>$report->fuel_consumed</td>
        </tr>";
        $last_date = $cur_date;
        $totalfuel += $report->fuel_consumed;
        
    }
    //echo "<tr><td colspan='3' style='text-align:center;font-weight:bold;'>Total: $totalfuel</td></tr>";
}
else{
    echo "<tr><td colspan='3' style='text-align:center;font-weight:bold;'>{$reports['reason']}</td></tr>";
}
?>
</tbody>
</table>

<?php    
/*if(isset($totalfuel)){
    echo "
    <table class='table newTable'>
        <thead><tr><th colspan='100%'>Statistics</th></tr></thead>
        <tbody>
        <tr><td colspan='3' style='text-align:center;font-weight:bold;'>Total fuel consumed: $totalfuel %</td></tr>
        </tbody>
    </table>";
}*/
?>
</div>