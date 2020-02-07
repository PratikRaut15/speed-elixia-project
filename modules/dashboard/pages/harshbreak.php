<p class="txtCenter">Harsh Brake</p>
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
    $harshbreakVehicleWise = isset($dailyreportdata['harshbreakVehicleWise']) ? $dailyreportdata['harshbreakVehicleWise'] : null;
    if (isset($harshbreakVehicleWise)) {
        $zonedatacount = array(
            'green' => 0
            , 'yellow' => 0
            , 'red' => 0
            , 'totalvehicle' => 0
        );
        foreach ($harshbreakVehicleWise as $key => $data) {
            if ($data['harsh_break'] < 10) {
                $zonedatacount['green'] += 1;
            } else if ($data['harsh_break'] >= 10 && $data['harsh_break'] <= 20) {
                $zonedatacount['yellow'] += 1;
            } else if ($data['harsh_break'] > 20) {
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

<p class="txtCenter">Harsh Break: Top 5 </p>
<table id="tblInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
    <tr>
        <td><strong>Office Name</strong></td>
        <td><strong>Vehicle No</strong></td>
        <td><strong>Date Time</strong></td>
        <td><strong>No Of Harsh Break</strong></td>

    </tr>
    <?php
    $topharshbreakData = isset($dailyreportdata['topharshbreakData']) ? $dailyreportdata['topharshbreakData'] : null;
    if (isset($topharshbreakData)) {
        foreach ($topharshbreakData as $key => $data) {
            ?>
            <tr>
                <td><?php echo $data['branchname'] ?></td>
                <td><?php echo $data['vehicleno'] ?></td>
                <td><?php echo $data['max_harsh_break_date'] ?></td>
                <td><?php echo $data['max_harsh_break_count']; ?></td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='4'>No Data</td></tr>";
    }
    ?>
</table>

<p class="txtCenter">Harsh Break Number Split Zone-wise</p>
<table id="tblInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
    <tr>
        <td><strong>Zone Name</strong></td>
        <td class="greentd"><strong>Green Zone</strong></td>
        <td class="yellowtd"><strong>Yellow Zone</strong></td>
        <td class="redtd"><strong>Red Zone</strong></td>
    </tr>
    <?php
    $harshbreakZoneWise = array_reduce($harshbreakVehicleWise, function ($result, $currentItem) {
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
        if ($currentItem['harsh_break'] < 10) {
            $result[$currentItem['zoneid']]['greenvehiclecount'] += 1;
        } else if ($currentItem['harsh_break'] >= 10 && $currentItem['harsh_break'] <= 20) {
            $result[$currentItem['zoneid']]['yellowvehiclecount'] += 1;
        } else if ($currentItem['harsh_break'] > 20) {
            $result[$currentItem['zoneid']]['redvehiclecount'] += 1;
        }
        return $result;
    });
    //$harshbreakZoneWise = isset($dailyreportdata['harshbreakZoneWise']) ? $dailyreportdata['harshbreakZoneWise'] : null;
    if (isset($harshbreakZoneWise)) {
        foreach ($harshbreakZoneWise as $data) {
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
            Green Zone: Contains Vehicle that has applied harsh brake for less than 10 times in calendar month.</td>
    </tr>
    <tr>
        <td style="text-align: left;">
            Yellow Zone: Contains Vehicle that has applied harsh brake for equal to 10 or less than 20 times in calendar month.</td>
    </tr>
    <tr>
        <td style="text-align: left;">
            Red Zone: Contains Vehicle that has applied harsh brake for equal to or more than 20 times in calendar month.</td>
    </tr>
</table><br/>
