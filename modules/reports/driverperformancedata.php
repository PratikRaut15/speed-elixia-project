<tbody>
<?php
if(!empty($reports)){
    $summary_data = generate_driverperformance_td_data($reports, $SDate, $EDdate);
}
else{
    echo "<tr><td style='text-align:center' colspan='100%'>No data</td></tr>";
}
?>
</tbody>
</table>
</div>
<div style="width:45%;" class="container">
</div>
