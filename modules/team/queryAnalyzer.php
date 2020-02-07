<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '4096M');
set_time_limit(0);
include("header.php");
include_once '../../lib/system/DatabaseManager.php';
if (isset($_POST['query'])) {
    $customername = $_POST['customername'];
    $vehicleno = $_POST['vehicleno'];
    $customerno = $_POST['customerno'];
    $unitno = $_POST['unitno'];
    $unitid = $_POST['unitid'];
    $SDate = $_POST["startdate"];
    $EDate = $_POST["enddate"];
    $Shour = $_POST["STime"];
    $Ehour = $_POST["ETime"];
    $totaldays = gendays_cmn($SDate, $EDate);
    $count1 = count($totaldays);
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];


    $queue = $queues = array();
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $lastday = new_travel_data($customerno, $unitno, $userdate, $count1, $firstelement, $endelement, $Shour, $Ehour);
            if ($lastday != null) {
                $queue = array_merge($queue, $lastday);
            }
        }
    }
    if (isset($queue) && !empty($queue)) {
        $queues = $queue;

        function cmp($a, $b) {
            $a = strtotime($a->lastupdated);
            $b = strtotime($b->lastupdated);
            if ($a == $b) {
                return 0;
            }
            return ($a > $b) ? -1 : 1;
        }

        usort($queues, "cmp");
    } else {
        $error = "Data Not available";
    }
}
function gendays_cmn($STdate, $EDdate) {
    $TOTALDAYS = Array();
    $STdate = date("Y-m-d", strtotime($STdate));
    $EDdate = date("Y-m-d", strtotime($EDdate));
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $TOTALDAYS[] = $STdate;
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
    }
    return $TOTALDAYS;
}

function new_travel_data($customerno, $unitno, $userdate, $count, $firstelement, $endelement, $Shour, $Ehour) {
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
    if (!file_exists($location)) {
        return null;
    }
    if (filesize($location) == 0) {
        return null;
    }
    $location = "sqlite:" . $location;
    if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
        $devicedata = getdatafromsqliteTimebased($location, $userdate, $Shour, null);
    } elseif ($count > 1 && $userdate == $endelement) {
        $devicedata = getdatafromsqliteTimebased($location, $userdate, null, $Ehour);
    } elseif ($count == 1) {
        $devicedata = getdatafromsqliteTimebased($location, $userdate, $Shour, $Ehour);
    } else {
        $devicedata = getdatafromsqliteTimebased($location, $userdate, null, null);
    }
    if ($devicedata != null) {
        return $devicedata;
    } else {
        return null;
    }
}

function getdatafromsqliteTimebased($location, $userdate, $Shour, $Ehour, $customerno = '') {

    if ($Shour == null && isset($Ehour)) {
        try {
            $database = new PDO($location);

            $query = "SELECT dh.devicelat,dh.devicelong,dh.lastupdated,dh.inbatt,dh.status,dh.ignition,dh.powercut,dh.tamper,gpsfixed,dh.`online/offline`,dh.gsmstrength,dh.gsmregister,dh.gprsregister,dh.swv,dh.hwv,unithistory.analog1,unithistory.analog2,unithistory.analog3,unithistory.analog4,unithistory.digitalio,unithistory.commandkey,unithistory.commandkeyval,vehiclehistory.extbatt,vehiclehistory.odometer,vehiclehistory.curspeed from devicehistory as dh
                            INNER JOIN unithistory ON unithistory.lastupdated = dh.lastupdated
                            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = dh.lastupdated
                            WHERE dh.lastupdated <= '$userdate $Ehour'
                            ORDER by dh.lastupdated DESC";

            $result = $database->query($query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $Datacap = new stdClass();
                    $Datacap->devicelat = $row['devicelat'];
                    $Datacap->devicelong = $row['devicelong'];
                    $Datacap->lastupdated = date("d-m-Y H:i:s", strtotime($row["lastupdated"]));
                    $Datacap->inbatt = $row['inbatt'];
                    $Datacap->status = $row['status'];
                    $Datacap->ignition = $row['ignition'];
                    $Datacap->powercut = $row['powercut'];
                    $Datacap->tamper = $row['tamper'];
                    $Datacap->gpsfixed = $row['gpsfixed'];
                    $Datacap->onoff = $row['online/offline'];
                    $Datacap->gsmstrength = $row['gsmstrength'];
                    $Datacap->gsmregister = $row['gsmregister'];
                    $Datacap->gprsregister = $row['gprsregister'];
                    $Datacap->swv = $row['swv'];
                    $Datacap->hwv = $row['hwv'];
                    $Datacap->analog1 = $row['analog1'];
                    $Datacap->analog2 = $row['analog2'];
                    $Datacap->analog3 = $row['analog3'];
                    $Datacap->analog4 = $row['analog4'];
                    $Datacap->digitalio = $row['digitalio'];
                    $Datacap->commandkey = $row['commandkey'];
                    $Datacap->commandkeyval = $row['commandkeyval'];
                    $Datacap->extbatt = $row['extbatt'];
                    $Datacap->odometer = $row['odometer'];
                    $Datacap->curspeed = $row['curspeed'];
                    $datas[] = $Datacap;
                }
            }
        } catch (PDOException $e) {
            die($e);
        }
    } elseif (isset($Shour) && $Ehour == null) {
        try {
            $database = new PDO($location);


            $query = "SELECT dh.devicelat,dh.devicelong,dh.lastupdated,dh.inbatt,dh.status,dh.ignition,dh.powercut,dh.tamper,gpsfixed,dh.`online/offline`,dh.gsmstrength,dh.gsmregister,dh.gprsregister,dh.swv,dh.hwv,unithistory.analog1,unithistory.analog2,unithistory.analog3,unithistory.analog4,unithistory.digitalio,unithistory.commandkey,unithistory.commandkeyval,vehiclehistory.extbatt,vehiclehistory.odometer,vehiclehistory.curspeed from devicehistory as dh
                            INNER JOIN unithistory ON unithistory.lastupdated = dh.lastupdated
                            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = dh.lastupdated
                            WHERE dh.lastupdated >= '$userdate $Shour'
                            ORDER by dh.lastupdated DESC";

            $result = $database->query($query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $Datacap = new stdClass();
                    $Datacap->devicelat = $row['devicelat'];
                    $Datacap->devicelong = $row['devicelong'];
                    $Datacap->lastupdated = date("d-m-Y H:i:s", strtotime($row["lastupdated"]));
                    $Datacap->inbatt = $row['inbatt'];
                    $Datacap->status = $row['status'];
                    $Datacap->ignition = $row['ignition'];
                    $Datacap->powercut = $row['powercut'];
                    $Datacap->tamper = $row['tamper'];
                    $Datacap->gpsfixed = $row['gpsfixed'];
                    $Datacap->onoff = $row['online/offline'];
                    $Datacap->gsmstrength = $row['gsmstrength'];
                    $Datacap->gsmregister = $row['gsmregister'];
                    $Datacap->gprsregister = $row['gprsregister'];
                    $Datacap->swv = $row['swv'];
                    $Datacap->hwv = $row['hwv'];
                    $Datacap->analog1 = $row['analog1'];
                    $Datacap->analog2 = $row['analog2'];
                    $Datacap->analog3 = $row['analog3'];
                    $Datacap->analog4 = $row['analog4'];
                    $Datacap->digitalio = $row['digitalio'];
                    $Datacap->commandkey = $row['commandkey'];
                    $Datacap->commandkeyval = $row['commandkeyval'];
                    $Datacap->extbatt = $row['extbatt'];
                    $Datacap->odometer = $row['odometer'];
                    $Datacap->curspeed = $row['curspeed'];
                    $datas[] = $Datacap;
                }
            }
        } catch (PDOException $e) {
            die($e);
        }
    } elseif (isset($Shour) && isset($Ehour)) {
        try {
            $database = new PDO($location);


            $query = "SELECT dh.devicelat,dh.devicelong,dh.lastupdated,dh.inbatt,dh.status,dh.ignition,dh.powercut,dh.tamper,gpsfixed,dh.`online/offline`,dh.gsmstrength,dh.gsmregister,dh.gprsregister,dh.swv,dh.hwv,unithistory.analog1,unithistory.analog2,unithistory.analog3,unithistory.analog4,unithistory.digitalio,unithistory.commandkey,unithistory.commandkeyval,vehiclehistory.extbatt,vehiclehistory.odometer,vehiclehistory.curspeed from devicehistory as dh
                            INNER JOIN unithistory ON unithistory.lastupdated = dh.lastupdated
                            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = dh.lastupdated
                            WHERE dh.lastupdated >= '$userdate $Shour' AND dh.lastupdated <= '$userdate $Ehour'
                            ORDER by dh.lastupdated DESC";

            $result = $database->query($query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $Datacap = new stdClass();
                    $Datacap->devicelat = $row['devicelat'];
                    $Datacap->devicelong = $row['devicelong'];
                    $Datacap->lastupdated = date("d-m-Y H:i:s", strtotime($row["lastupdated"]));
                    $Datacap->inbatt = $row['inbatt'];
                    $Datacap->status = $row['status'];
                    $Datacap->ignition = $row['ignition'];
                    $Datacap->powercut = $row['powercut'];
                    $Datacap->tamper = $row['tamper'];
                    $Datacap->gpsfixed = $row['gpsfixed'];
                    $Datacap->onoff = $row['online/offline'];
                    $Datacap->gsmstrength = $row['gsmstrength'];
                    $Datacap->gsmregister = $row['gsmregister'];
                    $Datacap->gprsregister = $row['gprsregister'];
                    $Datacap->swv = $row['swv'];
                    $Datacap->hwv = $row['hwv'];
                    $Datacap->analog1 = $row['analog1'];
                    $Datacap->analog2 = $row['analog2'];
                    $Datacap->analog3 = $row['analog3'];
                    $Datacap->analog4 = $row['analog4'];
                    $Datacap->digitalio = $row['digitalio'];
                    $Datacap->commandkey = $row['commandkey'];
                    $Datacap->commandkeyval = $row['commandkeyval'];
                    $Datacap->extbatt = $row['extbatt'];
                    $Datacap->odometer = $row['odometer'];
                    $Datacap->curspeed = $row['curspeed'];
                    $datas[] = $Datacap;
                }
            }
        } catch (PDOException $e) {
            die($e);
        }
    } elseif ($Shour == null && $Ehour == null) {
        try {
            $database = new PDO($location);


            $query = "SELECT dh.devicelat,dh.devicelong,dh.lastupdated,dh.inbatt,dh.status,dh.ignition,dh.powercut,dh.tamper,gpsfixed,dh.`online/offline`,dh.gsmstrength,dh.gsmregister,dh.gprsregister,dh.swv,dh.hwv,unithistory.analog1,unithistory.analog2,unithistory.analog3,unithistory.analog4,unithistory.digitalio,unithistory.commandkey,unithistory.commandkeyval,vehiclehistory.extbatt,vehiclehistory.odometer,vehiclehistory.curspeed from devicehistory as dh
                            INNER JOIN unithistory ON unithistory.lastupdated = dh.lastupdated
                            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = dh.lastupdated
                            ORDER by dh.lastupdated DESC";

            $result = $database->query($query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $Datacap = new stdClass();
                    $Datacap->devicelat = $row['devicelat'];
                    $Datacap->devicelong = $row['devicelong'];
                    $Datacap->lastupdated = date("d-m-Y H:i:s", strtotime($row["lastupdated"]));
                    $Datacap->inbatt = $row['inbatt'];
                    $Datacap->status = $row['status'];
                    $Datacap->ignition = $row['ignition'];
                    $Datacap->powercut = $row['powercut'];
                    $Datacap->tamper = $row['tamper'];
                    $Datacap->gpsfixed = $row['gpsfixed'];
                    $Datacap->onoff = $row['online/offline'];
                    $Datacap->gsmstrength = $row['gsmstrength'];
                    $Datacap->gsmregister = $row['gsmregister'];
                    $Datacap->gprsregister = $row['gprsregister'];
                    $Datacap->swv = $row['swv'];
                    $Datacap->hwv = $row['hwv'];
                    $Datacap->analog1 = $row['analog1'];
                    $Datacap->analog2 = $row['analog2'];
                    $Datacap->analog3 = $row['analog3'];
                    $Datacap->analog4 = $row['analog4'];
                    $Datacap->digitalio = $row['digitalio'];
                    $Datacap->commandkey = $row['commandkey'];
                    $Datacap->commandkeyval = $row['commandkeyval'];
                    $Datacap->extbatt = $row['extbatt'];
                    $Datacap->odometer = $row['odometer'];
                    $Datacap->curspeed = $row['curspeed'];
                    $datas[] = $Datacap;
                }
            }
        } catch (PDOException $e) {
            die($e);
        }
    }
    return $datas;
}

$today = date("d-m-Y");
?>
<div class="panel">
    <div class="paneltitle" align="center">Query Analyzer</div>
    <div class="panelcontents">
        <form name="queryAnalyzer" id="queryAnalyzer" action="queryAnalyzer.php" method="POST" onsubmit="return validate();">
            <table>
                <tr><td><?php
                        if (isset($error)) {
                            echo '<p><span style="color:red">' . $error . '</span></p>';
                        }
                        ?>
                        <span id="error" style="display: none;font-size: 15px;color: red;"></span>
                        <span id="error1" style="display: none;font-size: 15px;color: red;">Enter Correct Customer Number.</span></td>
                    <td><span id="error2" style="display: none;font-size: 15px;color: red;">Enter Correct Unit Number.</span></td>
                </tr>
                <tr>
                    <td>Customer Name</td>
                    <td><input  type="text" name="customername" id="customername" size="20" value="<?php if (isset($customername)) { echo $customername; } ?>" autocomplete="off" placeholder="Enter Customer Name"  onkeypress="getCustomer();"/>
                        <input type="hidden" id="customerno" name="customerno" value="<?php if (isset($customerno)) { echo $customerno; } ?>"/>
                    </td>
                    <td class="space"></td>
                    <td>Vehicle No</td>
                    <td>
                        <input  type="text" name="vehicleno" id="vehicleno" size="20" value="<?php if (isset($vehicleno)) { echo $vehicleno; } ?>" autocomplete="off" placeholder="Enter Vehicle No" onkeypress="getVehicle();"/></td>
                </tr>
                <tr>
                    <td>Unit No</td>
                    <td><input  type="text" name="unitno" id="unitno" size="20" value="<?php if (isset($unitno)) { echo $unitno; } ?>" autocomplete="off" placeholder="Enter Unit No" onkeypress="getUnit();"/>
                        <input type="hidden" name="unitid" id="unitid" value="<?php if (isset($unitid)) { echo $unitid; } ?>" />
                    </td>
                    <td class="space"></td>



                </tr>
                <tr>
                    <td>Start Date</td>
                    <td><input  type="text" name="startdate" id="startdate" size="10" value="<?php
                                if (isset($SDate)) {
                                    echo $SDate;
                                } else {
                                    echo $today;
                                }
                                ?>" /></td>
                    <td class="space"></td>
                    <td>Start Time</td>
                    <td><input id="STime" name="STime" type="text" class="input-mini" value="<?php
                        if (isset($Shour)) {
                            echo $Shour;
                        } else {
                            echo "00:00";
                        }
                                ?>" /></td>
                    <td class="space"></td>
                    <td>End Date</td>
                    <td><input  type="text" name="enddate" id="enddate" size="10" value="<?php
                        if (isset($EDate)) {
                            echo $EDate;
                        } else {
                            echo $today;
                        }
                        ?>" /></td><td></td>
                    <td class="space"></td>
                    <td>End Time</td>
                    <td><input id="ETime" name="ETime" type="text" class="input-mini" value="<?php
                        if (isset($Ehour)) {
                            echo $Ehour;
                        } else {
                            echo "23:59";
                        }
                        ?>"/></td>
                </tr>
                <tr><td></td>
                    <td><input type="submit" id="query" name="query"/></td>
        <?php if (isset($queues)) { ?>
                        <td><input type="button" title="Export Raw Data" id="dataQuery" name="dataQuery" value="" onclick="exportData(<?php
        if (isset($customerno) && isset($unitno)) {
            echo $customerno . ",'" . $unitno . "','" . $SDate . "','" . $EDate . "'";
        }
        ?>);"/></a></td>
<?php }
?>
                </tr>
            </table>
        </form>
        <?php if (!isset($error) && isset($queues)) { ?>
            <form name="pushcommand" id="pushcommand">
                <table>
                    <tr><th colspan="3" style="text-align:left;">Push Command from Server</th></tr>
                    <tr><td colspan="3"><input type="hidden" name="scustomerno" id="scustomerno" value="<?php echo $customerno; ?>"/>
                            <input type="hidden" name="sunitid" id="sunitid" value="<?php echo $unitid; ?>"/></td></tr>
                    <tr><td>Command</td><td class="space"></td><td><input type="text" name="scommand" id="scommand" /></td></tr>
                    <tr><td>Comments</td><td class="space"></td><td><input type="text" name="scomment" id="scomment" /></td></tr>
                    <tr><td class="space"></td><td colspan="2"><input type="button" name="save" value="Set" onclick="return checkform()"/></td></tr>
                </table>
            </form>
<?php } ?>
    </div>
</div>
<?php if (isset($queues) && !isset($error)) { ?>
    <br/><br/>
    <div class="tabbable" style="overflow-x: scroll;width:100%;">
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
                foreach ($queues as $devhist) {


                    echo "<tr>";
                    $devicelat = isset($devhist->devicelat) ? $devhist->devicelat : "&nbsp;";
                    echo "<td>" . $devicelat . "</td>";

                    $devicelong = isset($devhist->devicelong) ? $devhist->devicelong : "&nbsp;";
                    echo "<td>" . $devicelong . "</td>";

                    $lastupdated = isset($devhist->lastupdated) ? $devhist->lastupdated : "&nbsp;";
                    echo "<td>" . $lastupdated . "</td>";

                    $inbatt = isset($devhist->inbatt) ? $devhist->inbatt : "&nbsp;";
                    echo "<td>" . $inbatt . "</td>";

                    $status = isset($devhist->status) ? $devhist->status : "&nbsp;";
                    echo "<td>" . $status . "</td>";

                    if ($devhist->ignition == 0) {
                        echo "<td>OFF</td>";
                    } elseif ($devhist->ignition == 1) {
                        echo "<td>ON</td>";
                    } else {
                        echo "<td> </td>";
                    }

                    if ($devhist->powercut == 0) {
                        echo "<td style='background:#ffb3b3;'>CUT</td>";
                    } elseif ($devhist->powercut == 1) {
                        echo "<td>NORMAL</td>";
                    } else {
                        echo "<td> </td>";
                    }

                    if ($devhist->tamper == 1) {
                        echo "<td style='background:#ffb3b3;'>TAMPERED</td>";
                    } elseif ($devhist->tamper == 0) {
                        echo "<td>NORMAL</td>";
                    } else {
                        echo "<td> </td>";
                    }

                    if ($devhist->gpsfixed == 1) {
                        echo "<td style='background:#ffb3b3;'>VOID</td>";
                    } elseif ($devhist->gpsfixed == 0) {
                        echo "<td>FIXED</td>";
                    } else {
                        echo "<td> </td>";
                    }

                    if ($devhist->onoff == 1) {
                        echo "<td style='background:#ffb3b3;'>OFFLINE</td>";
                    } elseif ($devhist->onoff == 0) {
                        echo "<td>ONLINE</td>";
                    } else {
                        echo "<td> </td>";
                    }

                    $gsmstrength = isset($devhist->gsmstrength) ? $devhist->gsmstrength : "&nbsp;";
                    echo "<td>" . $gsmstrength . "</td>";

                    $gsmregister = isset($devhist->gsmregister) ? $devhist->gsmregister : "&nbsp;";
                    echo "<td>" . $gsmregister . "</td>";

                    $gprsregister = isset($devhist->gprsregister) ? $devhist->gprsregister : "&nbsp;";
                    echo "<td>" . $gprsregister . "</td>";

                    $swv = isset($devhist->swv) ? $devhist->swv : "&nbsp;";
                    echo "<td>" . $swv . "</td>";

                    $hwv = isset($devhist->hwv) ? $devhist->hwv : "&nbsp;";
                    echo "<td>" . $hwv . "</td>";

                    $analog1 = isset($devhist->analog1) ? $devhist->analog1 : "&nbsp;";
                    echo "<td>" . $analog1 . "</td>";

                    $analog2 = isset($devhist->analog2) ? $devhist->analog2 : "&nbsp;";
                    echo "<td>" . $analog2 . "</td>";

                    $analog3 = isset($devhist->analog3) ? $devhist->analog3 : "&nbsp;";
                    echo "<td>" . $analog3 . "</td>";

                    $analog4 = isset($devhist->analog4) ? $devhist->analog4 : "&nbsp;";
                    echo "<td>" . $analog4 . "</td>";

                    if ($devhist->digitalio == 1) {
                        echo "<td>ON</td>";
                    } elseif ($devhist->digitalio == 0) {
                        echo "<td>OFF</td>";
                    } else {
                        echo "<td> </td>";
                    }

                    $commandkey = isset($devhist->commandkey) ? $devhist->commandkey : "&nbsp;";
                    echo "<td>" . $commandkey . "</td>";

                    $commandkeyval = isset($devhist->commandkeyval) ? $devhist->commandkeyval : "&nbsp;";
                    echo "<td>" . $commandkeyval . "</td>";

                    $extbatt = isset($devhist->extbatt) ? $devhist->extbatt : "&nbsp;";
                    echo "<td>" . $extbatt . "</td>";

                    $odometer = isset($devhist->odometer) ? $devhist->odometer : "&nbsp;";
                    echo "<td>" . $odometer . "</td>";

                    $curspeed = isset($devhist->curspeed) ? $devhist->curspeed : "&nbsp;";
                    echo "<td>" . $curspeed . "</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>
<?php
include("footer.php");
?>
<script>
    function validate() {
        var customerno = $('#customernoname').val();
        var unitno = $('#unitno').val();
        if (customerno == '' || customerno == '0') {
            $('#error1').fadeIn(2000);
            $('#error1').fadeOut(2000);
            return false;
        } else if (unitno == '' || unitno == '') {
            $('#error2').fadeIn(2000);
            $('#error2').fadeOut(2000);
            return false;
        } else {
            $('#queryAnalyzer').submit();
        }
    }
    function getUnit() {
        var data = $('#customerno').val();

        jQuery("#unitno").autocomplete({
            source: "route_ajax.php?unitno=" + data,
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery("#unitid").val(ui.item.uid);
                jQuery("#vehicleno").val(ui.item.vehicleno);
//                return false;
            }
        });
    }
    function getVehicle() {
        var data = $('#customerno').val();
        jQuery("#vehicleno").autocomplete({
            source: "route_ajax.php?vehicleno=" + data,
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery('#unitno').val(ui.item.uid);
                jQuery('#unitid').val(ui.item.unitid);
//                return false;
            }
        });
    }
    function getCustomer() {
        jQuery("#customername").autocomplete({
            source: "route_ajax.php?customername=getcustomer",
            select: function (event, ui) {
                jQuery(this).val(ui.item.value);
                jQuery('#customerno').val(ui.item.cid);
//                return false;
            }
        });
    }
    function exportData(customerno, unitno, sdate, edate) {
        window.open("route_ajax.php?cno=" + customerno + "&uno=" + unitno + "&sdate=" + sdate + "&edate=" + edate, '_blank');
    }
    function checkform() {
        var data = jQuery('#pushcommand').serialize();
        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php",
            cache: false,
            data: data,
            success: function (msg) {
                jQuery('#error').html(msg);
                $('#error').fadeIn(3000);
                $('#error').fadeOut(3000);
                return false;
            }
        });
    }
    $("#startdate").datepicker({dateFormat: 'dd-mm-yy'});
    $("#enddate").datepicker({dateFormat: 'dd-mm-yy'});

    $('#STime').timepicker({'timeFormat': 'H:i'});
    $('#ETime').timepicker({'timeFormat': 'H:i'});

</script>
<style>
    #dataQuery{
        background: url(../../images/xls.gif);
        /*border: 1px solid black;*/
        display: block;
        height: 33px;
        width: 33px;
    }
    .space{
        width: 10px;
    }
</style>
