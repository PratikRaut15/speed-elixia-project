<tbody>
<?php
$lastvehicle=Array();
$display;
if($ReportType == 'Genset')
{
$vehicles = GetGensetVehicles_SQLite();
}
else{
$vehicles = GetVehicles_SQLite();
}

if(isset($reports))
{
  $totalfuel = 0;
  $totaltravel = 0;
  foreach($reports as $report){
      ?>
        <tr>
            <td><?php echo $vehicles[$report->vehicleid]['vehicleno']; ?></td>
            <td><?php echo date('d-m', $report->date);?></td>
            <td><?php echo $report->totaldistancetravelled = $report->totaldistance/1000; ?></td>
            <?php $totaltravel += round($report->totaldistancetravelled,2); ?>
            <?php $report->consumedfuel = ($report->average!=0) ? $report->totaldistancetravelled / $report->average : 0; ?>
            <td><?php echo round($report->consumedfuel,2); ?></td>
            <?php $totalfuel += round($report->consumedfuel,2); ?>
        </tr>
      <?php
  }
  echo "<tr>
        <td colspan='2'>Total</td>
       <td>".$totaltravel."</td>
       <td>".$totalfuel."</td>
       </tr>";
}

?>
</tbody>
</table>
</div>