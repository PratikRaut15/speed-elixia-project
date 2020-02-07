<tbody>
<?php

if(!empty($reports)){
    $summary_data = generate_dist_td_data($reports, $SDate, $EDdate);
}
else{
    echo "<tr><td style='text-align:center' colspan='100%'>No data</td></tr>";
}
$highest_dist = isset($summary_data[0]) ? $summary_data[0] : '';
$highest_dist_vehno = isset($summary_data[1]) ? $summary_data[1] : '';
$total_wkend_dist = isset($summary_data[2]) ? $summary_data[2] : '';
?>
</tbody>
</table>
</div>
<div style="width:45%;" class="container">
<table class="table newTable">
    <thead>
        <tr><th colspan="10">Statistics</th></tr>
    </thead>
    <tbody>
        <tr><td colspan="10" style="text-align:center;"><?php echo "Highest distance travelled: $highest_dist, Vehicle No.: $highest_dist_vehno"; ?></td></tr>
        <tr><td colspan="10" style="text-align:center;"><?php echo "Total distance travelled on weekends: $total_wkend_dist Kms"; ?></td></tr>
    </tbody>
</table>
</div>