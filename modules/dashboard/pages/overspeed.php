<p class="txtCenter">Overspeed</p>
<br/>
<!--<strong>Total Number of Vehicles: <?php // echo $total_devices_installed; ?>.</strong>-->
<table id="tblInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
    <tr>
        <td><strong>Total Vehicle</strong></td>
        <td class="greentd"><strong>Green Zone</strong></td>
        <td class="yellowtd"><strong>Yellow Zone</strong></td>
        <td class="redtd"><strong>Red Zone</strong></td>

    </tr>
    <?php
    $overspeeddataVehicleWise = isset($dailyreportdata['overspeedsumVehicleWise']) ? $dailyreportdata['overspeedsumVehicleWise'] : null;
    if (isset($overspeeddataVehicleWise)) {
        $zonedatacount = array(
            'green' => 0
            , 'yellow' => 0
            , 'red' => 0
            , 'totalvehicle' => 0
        );
        foreach ($overspeeddataVehicleWise as $key => $data) {
            if ($data['overspeed'] <= 40) {
                $zonedatacount['green'] += 1;
            } else if ($data['overspeed'] > 40 && $data['overspeed'] <= 55) {

                $zonedatacount['yellow'] += 1;
            } else if ($data['overspeed'] > 55) {

                $zonedatacount['red'] += 1;
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

<p class="txtCenter">Highest Speed Recorded: Top 5 </p>
<table id="tblInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
    <tr>
        <td><strong>Office Name</strong></td>
        <td><strong>Vehicle No</strong></td>
        <td><strong>Date Time</strong></td>
        <td><strong>Speed [km/hr]</strong></td>

    </tr>
    <?php
    $topspeedData = isset($dailyreportdata['overspeedTopSpeed']) ? $dailyreportdata['overspeedTopSpeed'] : null;
    if (isset($topspeedData)) {
        foreach ($topspeedData as $key => $data) {
            ?>
            <tr>
                <td><?php echo $data['branchname'] ?></td>
                <td><?php echo $data['vehicleno'] ?></td>
                <td><?php echo $data['max_topspeed_date'] ?></td>
                <td><?php echo $data['max_topspeed'] ?></td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='4'>No Data</td></tr>";
    }
    ?>
</table>

<p class="txtCenter">Zone Wise Overspeed Count</p>

<table id="tblInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
    <tr>
        <td><strong>Zone Name</strong></td>
        <td class="greentd"><strong>Green Zone</strong></td>
        <td class="yellowtd"><strong>Yellow Zone</strong></td>
        <td class="redtd"><strong>Red Zone</strong></td>

    </tr>
    <?php
    /* Overspeed Data - Zonewise Count */
    $overspeeddataZoneWise = array_reduce($overspeeddataVehicleWise, function ($result, $currentItem) {
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
        if ($currentItem['overspeed'] <= 40) {
            $result[$currentItem['zoneid']]['greenvehiclecount'] += 1;
        } else if ($currentItem['overspeed'] > 40 && $currentItem['overspeed'] <= 55) {
            $result[$currentItem['zoneid']]['yellowvehiclecount'] += 1;
        } else if ($currentItem['overspeed'] > 55) {
            $result[$currentItem['zoneid']]['redvehiclecount'] += 1;
        }
        return $result;
    });
    //$overspeeddataZoneWise = isset($dailyreportdata['overspeedsumZoneWise']) ? $dailyreportdata['overspeedsumZoneWise'] : null;
    if (isset($overspeeddataZoneWise)) {
        foreach ($overspeeddataZoneWise as $data) {
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
            Green Zone: Contains Vehicle that have over speed less than equal to 40 times in a calendar month.</td>
    </tr>
    <tr>
        <td style="text-align: left;">
            Yellow Zone: Contains Vehicle that have over speed more than 40 times and less than equal to 55 times in a calendar month.</td>
    </tr>
    <tr>
        <td style="text-align: left;">
            Red Zone: Contains Vehicle that have over speed more than 55 times in a calendar Month.</td>
    </tr>
</table>
<br/>
