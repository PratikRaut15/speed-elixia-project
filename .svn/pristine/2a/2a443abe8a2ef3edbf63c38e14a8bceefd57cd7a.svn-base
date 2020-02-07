<?php
if(isset($_GET['vid'])){
$vehicleid = $_GET['vid'];
}
$unitno = $_GET['unitno'];
//echo $vehicleid.$_SESSION['customerno'];
if (!isset($_POST['SDate'])) {
    $Sdate = getdate_IST();
} else
    $Sdate = strtotime($_POST['SDate']);
?>
<form action="vehicle.php?id=5&unitno=<?php echo $unitno; ?>" method="POST">
    <input type="hidden" value="<?php echo $unitno; ?>" name="unitno">
    <table>
        <tr>
            <td>Please select date</td>
            <td><input id="SDate" name="SDate" type="text" value="<?php echo date('d-m-Y', $Sdate); ?>" required/></td>
            <td>
                <select id="count" name="count">
                    <option value="0" selected>Last 20 Records</option>
                    <option value="1">Last 50 Records</option>
                    <option value="2">All Records</option>
                </select>
            </td>
            <td colspan="100%"><input type="submit" data="g-button g-button-submit" class="btn  btn-primary" value="Submit" name="submit"></td>
        </tr>
    </table>
</form>
<?php
if (isset($_POST['SDate'])) {
    $count = GetSafeValueString($_POST['count'], 'string');
    $date = GetSafeValueString($_POST['SDate'], 'string');
    $dt = date("Y-m-d", strtotime($date));
    $unit = GetSafeValueString($_POST['unitno'], 'string');
    $location = "../../customer/" . $_SESSION['customerno'] . "/unitno/$unit/sqlite/$dt.sqlite";
    ?>
    <div class="tabbable" style="overflow-x: scroll;width:97%">
        <table class="table  table-bordered table-striped dTableR dataTable">
            <thead>
                <tr>
                    <th>Lat</th>
                    <th>Long</th>
                    <th>Last Updated</th>
                    <th>Int Batt (V)</th>
                    <th>Status</th>
                    <th>Ignition</th>
                    <th>Power Cut</th>
                    <th>Tamper</th>
                    <th>Gps fixed</th>
                    <th>Online/Offline</th>
                    <th>Gsm Strength</th>
                    <th>Gsm Register</th>
                    <th>Gprs Register</th>
                    <th>S/W Version</th>
                    <th>H/W Version</th>
                    <th>analog 1</th>
                    <th>analog 2</th>
                    <th>analog 3</th>
                    <th>analog 4</th>
                    <th>Digital I/O</th>
                    <th>Command Key</th>
                    <th>Command Key Value</th>
                    <th>Ext Batt</th>
                    <th>Odometer</th>
                    <th>Current Speed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $devhists = getdevicehist_sqlite($location, $count);
                if(isset($devhists)){
                foreach ($devhists as $devhist) {
                    echo "<tr><td>$devhist->devicelat</td>
                        <td>$devhist->devicelong</td>
                        <td>$devhist->lastupdated</td>
                        <td>$devhist->inbatt</td>
                        <td>$devhist->status</td>
                        <td>$devhist->ignition</td>
                        <td>$devhist->powercut</td>
                        <td>$devhist->tamper</td>
                        <td>$devhist->gpsfixed</td>
                        <td>$devhist->onoff</td>
                        <td>$devhist->gsmstrength</td>
                        <td>$devhist->gsmregister</td>
                        <td>$devhist->gprsregister</td>
                        <td>$devhist->swv</td>
                        <td>$devhist->hwv</td>  
                        <td>$devhist->analog1</td>
                        <td>$devhist->analog2</td>
                        <td>$devhist->analog3</td>
                        <td>$devhist->analog4</td>
                        <td>$devhist->digitalio</td>
                        <td>$devhist->commandkey</td>
                        <td>$devhist->commandkeyval</td>
                        <td>$devhist->extbatt</td>
                        <td>$devhist->odometer</td>
                    
                        <td>$devhist->curspeed</td>
                                  </tr>";
                }}
                ?>
            </tbody>
        </table>
    </div>
<?php } ?>