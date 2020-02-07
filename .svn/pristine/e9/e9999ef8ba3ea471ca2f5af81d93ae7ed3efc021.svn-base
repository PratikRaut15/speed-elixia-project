<p class="txtCenter">Weekend Drive</p>
<br/>
<!--<strong>Total Number of Vehicles: <?php //echo $total_devices_installed;                                                                            ?>.</strong>-->
<table id="tblWeekendInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
  <tr>
    <td><strong>Total Vehicle</strong></td>
    <td class="greentd"><strong>Green Zone</strong></td>
    <td class="yellowtd"><strong>Yellow Zone</strong></td>
    <td class="redtd"><strong>Red Zone</strong></td>
  </tr>
  <?php
  //echo "<pre>";
  $weekendDriveVehicleWise = isset($dailyreportdata['weekendDriveVehicleWise']) ? $dailyreportdata['weekendDriveVehicleWise'] : null;
  if (isset($weekendDriveVehicleWise)) {
    $zonedatacount = array(
       'green' => 0
       , 'yellow' => 0
       , 'red' => 0
       , 'totalvehicle' => 0
    );
    foreach ($weekendDriveVehicleWise as $key => $data) {
      //$totalWeekendDistance = round($data['max_weekenddrive_distance'] / 1000, 2);
      if ($data['isRed'] > 0) {
        $zonedatacount['red'] += 1;
      } else if ($data['isRed'] == 0 && $data['isYellow'] > 0) {
        $zonedatacount['yellow'] += 1;
      } else if ($data['isRed'] == 0 && $data['isYellow'] == 0) {
        $zonedatacount['green'] += 1;
      }
      $zonedatacount['totalvehicle'] += 1;
    }
    ?>
    <tr>
      <td><?php echo $zonedatacount['totalvehicle'] ?></td>
      <td><?php echo $zonedatacount['green'] ?></td>
      <td><?php echo $zonedatacount['yellow'] ?></td>
      <td><?php echo $zonedatacount['red'] ?></td>
    </tr>
    <?php
  } else {
    echo "<tr><td colspan='4'>No Data</td></tr>";
  }
  ?>
</table>

<p class="txtCenter">Weekend Drive: Top 5 </p>
<table id="tblTopWDInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
  <tr>
    <td><strong>Office Name</strong></td>
    <td><strong>Vehicle No</strong></td>
    <td><strong>Date Time</strong></td>
    <td><strong>No Of Weekend Km Travelled</strong></td>

  </tr>
  <?php
  $topweekendDriveData = isset($dailyreportdata['topWeekendDriveData']) ? $dailyreportdata['topWeekendDriveData'] : null;
  if (isset($topweekendDriveData)) {
    foreach ($topweekendDriveData as $key => $data) {
      ?>
      <tr>
        <td><?php echo $data['branchname'] ?></td>
        <td><?php echo $data['vehicleno'] ?></td>
        <td><?php echo $data['max_weekenddrive_date'] ?></td>
        <td><?php echo round($data['max_weekenddrive_distance'] / 1000, 2); ?></td>
      </tr>
      <?php
    }
  } else {
    echo "<tr><td colspan='4'>No Data</td></tr>";
  }
  ?>
</table>

<p class="txtCenter">Weekend Drive Number Split Zone-wise</p>
<table id="tblInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
  <tr>
    <td><strong>Zone Name</strong></td>
    <td class="greentd"><strong>Green Zone</strong></td>
    <td class="yellowtd"><strong>Yellow Zone</strong></td>
    <td class="redtd"><strong>Red Zone</strong></td>
  </tr>
  <?php
  $weekendDriveZoneWise = array_reduce($weekendDriveVehicleWise, function ($result, $currentItem) {
    if (!isset($result[$currentItem['zoneid']])) {
      $result[$currentItem['zoneid']] = $currentItem;
      if (!isset($result[$currentItem['zoneid']]['greenvehiclecount'])) {
        $result[$currentItem['zoneid']]['greenvehiclecount'] = 0;
      }
      if (!isset($result[$currentItem['zoneid']]['yellowvehiclecount'])) {
        $result[$currentItem['zoneid']]['yellowvehiclecount'] = 0;
      }
      if (!isset($result[$currentItem['zoneid']]['redvehiclecount'])) {
        $result[$currentItem['zoneid']]['redvehiclecount'] = 0;
      }
    }
    /* old vehicle count
      $totalWeekendDistance = round($currentItem['max_weekenddrive_distance'] / 1000, 2);
      if ($totalWeekendDistance <= 25) {
      $result[$currentItem['zoneid']]['greenvehiclecount'] += 1;
      } else if ($totalWeekendDistance > 25 && $totalWeekendDistance < 45) {
      $result[$currentItem['zoneid']]['yellowvehiclecount'] += 1;
      } else if ($totalWeekendDistance >= 45) {
      $result[$currentItem['zoneid']]['redvehiclecount'] += 1;
      }
     *
     */

    if ($currentItem['isRed'] > 0) {
      $result[$currentItem['zoneid']]['redvehiclecount'] += 1;
    } else if ($currentItem['isRed'] == 0 && $currentItem['isYellow'] > 0) {
      $result[$currentItem['zoneid']]['yellowvehiclecount'] += 1;
    } else if ($currentItem['isRed'] == 0 && $currentItem['isYellow'] == 0) {
      $result[$currentItem['zoneid']]['greenvehiclecount'] += 1;
    }

    return $result;
  });
  //$weekendDriveZoneWise = isset($dailyreportdata['weekendDriveZoneWise']) ? $dailyreportdata['weekendDriveZoneWise'] : null;
  if (isset($weekendDriveZoneWise)) {
    foreach ($weekendDriveZoneWise as $data) {
      ?>
      <tr>
        <td><?php echo $data['zonename'] ?></td>
        <td><?php echo $data['greenvehiclecount'] ?></td>
        <td><?php echo $data['yellowvehiclecount'] ?></td>
        <td><?php echo $data['redvehiclecount'] ?></td>
      </tr>
      <?php
    }
  } else {
    echo "<tr><td colspan='4'>No Data</td></tr>";
  }
  ?>
</table>
<br/>
<table id="tblInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
  <tr>
    <td style="text-align: left;">
      Note:</td>
  </tr>
  <tr>
    <td style="text-align: left;">
      Green Zone: Contains Vehicle that has plied less than or equal to 25 Kms on Holidays / weekend in calendar month.</td>
  </tr>
  <tr>
    <td style="text-align: left;">
      Yellow Zone: Contains Vehicle that has plied for more than 25 Kms and less than 45 Kms on holidays / weekend in calendar month.</td>
  </tr>
  <tr>
    <td style="text-align: left;">
      Red Zone: Contains Vehicle that has plied for equal to or more than 45 Kms on holidays / weekend in calendar month.</td>
  </tr>
</table><br/>
