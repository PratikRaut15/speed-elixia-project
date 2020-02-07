<p class="txtCenter">Sudden Acceleration</p>
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
    $accelerationVehicleWise = isset($dailyreportdata['accelerationVehicleWise']) ? $dailyreportdata['accelerationVehicleWise'] : null;
    if (isset($accelerationVehicleWise)) {
        $zonedatacount = array(
            'green' => 0
            , 'yellow' => 0
            , 'red' => 0
            , 'totalvehicle' => 0
        );
        foreach ($accelerationVehicleWise as $key => $data) {
            if ($data['sudden_acc'] <= 10) {
                $zonedatacount['green'] += 1;
            } else if ($data['sudden_acc'] > 10 && $data['sudden_acc'] <= 20) {
                $zonedatacount['yellow'] += 1;
            } else if ($data['sudden_acc'] > 20) {
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
<br/>
<p class="txtCenter">Excessive Acceleration : Top 5 </p>
<table id="tblInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
    <tr>
        <td><strong>Office Name</strong></td>
        <td><strong>Vehicle No</strong></td>
        <td><strong>Date Time</strong></td>
        <td><strong>No Of Acceleration</strong></td>
    </tr>
    <?php
    $topaccelarationData = isset($dailyreportdata['topaccelarationData']) ? $dailyreportdata['topaccelarationData'] : null;
    if (isset($topaccelarationData) && !empty($topaccelarationData)) {
        foreach ($topaccelarationData as $key => $data) {
            ?>
            <tr>
                <td><?php echo $data['branchname'] ?></td>
                <td><?php echo $data['vehicleno'] ?></td>
                <td><?php echo $data['max_sudden_acc_date'] ?></td>
                <td><?php echo $data['max_sudden_acc_count'] ?></td>
            </tr>
            <?php
        }
    } else {
        echo '<tr><td colspan="4">No Data</td></tr>';
    }
    ?>
</table>
<br/>
<p class="txtCenter">Acceleration Number Split Zone-wise</p>
<table id="tblInfo" align='center' style='width: 90%; font-size:13px; text-align:center;border:1px solid #000;'>
    <tr>
        <td><strong>Zone Name</strong></td>
        <td class="greentd"><strong>Green Zone</strong></td>
        <td class="yellowtd"><strong>Yellow Zone</strong></td>
        <td class="redtd"><strong>Red Zone</strong></td>
    </tr>
    <?php
    $accelarationZoneWise = array_reduce($accelerationVehicleWise, function ($result, $currentItem) {
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
        if ($currentItem['sudden_acc'] < 10) {
            $result[$currentItem['zoneid']]['greenvehiclecount'] += 1;
        } else if ($currentItem['sudden_acc'] >= 10 && $currentItem['sudden_acc'] <= 20) {
            $result[$currentItem['zoneid']]['yellowvehiclecount'] += 1;
        } else if ($currentItem['sudden_acc'] > 20) {
            $result[$currentItem['zoneid']]['redvehiclecount'] += 1;
        }
        return $result;
    });
    //$accelarationZoneWise = isset($dailyreportdata['accelarationZoneWise']) ? $dailyreportdata['accelarationZoneWise'] : null;
    if (isset($accelarationZoneWise)) {
        foreach ($accelarationZoneWise as $data) {
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
        <td style="text-align: left;
            ">
            Note:</td>
    </tr>
    <tr>
        <td style="text-align: left;
            ">
            Green Zone: Contains Vehicle that have sudden acceleration less than equal to 10 times in a calendar month.</td>
    </tr>
    <tr>
        <td  style="text-align: left;
            ">
            Yellow Zone: Contains Vehicle that have sudden acceleration more than 10 times and less than equal to 20 times in a calendar month.</td>
    </tr>
    <tr>
        <td style="text-align: left;
            ">
            Red Zone: Contains Vehicle that have sudden acceleration more than 20 times in a calendar month.</td>
    </tr>
</table><br/>
