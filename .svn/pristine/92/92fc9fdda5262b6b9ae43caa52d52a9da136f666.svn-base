<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
    <tbody>
        <tr style='background-color:#CCCCCC;font-weight:bold;'>
            <td style='width:100px;height:auto;'>Time</td>
            <td style='width:400px;height:auto;'>Location</td>                        
            <td style='width:100px;height:auto;'>Fuel Consumed (In litre)</td>
        </tr>
        <?php
if($reports['status']==1){
    $totalfuel = 0;
    $last_date = null;
    foreach($reports['tbl_data'] as $report){
        $cur_date = date('d-m-Y',strtotime($report->date));
        if($cur_date!=$last_date){
            echo "<tr style='background-color:#d8d5d6;font-weight:bold;'><td colspan='3'>$cur_date</td></tr>";
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
}
else{
    echo "<tr><td colspan='3' style='text-align:center;font-weight:bold;'>{$reports['reason']}</td></tr>";
}
?>
    </tbody>
</table>

