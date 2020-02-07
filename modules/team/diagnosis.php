<?php
include_once("session.php");
include_once("../../lib/system/DatabaseManager.php");
$db = new DatabaseManager();

if (isset($_REQUEST['diagnosis']) && !empty($_REQUEST['unitno'])) {
    $unitno = $_REQUEST['unitno'];

    $SQL = "SELECT uid,customerno FROM unit WHERE unitno LIKE '" . $unitno . "' LIMIT 1";

    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $unitid = $row['uid'];
            $customerno = $row['customerno'];
        }
    }
    if (!empty($unitid)) {
        $SQL = "SELECT vehicleid,customerno FROM vehicle WHERE uid =" . $unitid . " LIMIT 1";
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $vehicleid = $row['vehicleid'];
                $vehicle_customerno = $row['customerno'];
            }
        }
    }
    if (!empty($unitid)) {
        $SQL = "SELECT simcardid,customerno FROM devices WHERE uid =" . $unitid . " LIMIT 1";

        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $device_simcardid = $row['simcardid'];
                $device_customerno = $row['customerno'];
            }
        }
    }
    if (!empty($device_simcardid)) {
        $SQL = "SELECT trans_statusid,customerno FROM simcard WHERE id =" . $device_simcardid . " LIMIT 1";
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $simcard_status = $row['trans_statusid'];
                $simcard_customerno = $row['customerno'];
            }
        }
    }
    if (!empty($vehicleid)) {
        $SQL = "SELECT customerno FROM driver WHERE vehicleid =" . $vehicleid . " LIMIT 1";

        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $driver_customerno = $row['customerno'];
            }
        }
    }
    if (!empty($vehicleid)) {
        $SQL = "SELECT customerno FROM ignitionalert WHERE vehicleid =" . $vehicleid . " LIMIT 1";

        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $ignitionalert_customerno = $row['customerno'];
            }
        }
    }
    if (!empty($vehicleid)) {
        $SQL = "SELECT customerno FROM eventalerts WHERE vehicleid =" . $vehicleid . " LIMIT 1";

        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $eventalerts_customerno = $row['customerno'];
            }
        }
    }
}
include("header.php");
?>

<div id="container">
    <div class="panel">
        <form id="myDiagnosisFrom" name="myDiagnosisFrom" action="diagnosis.php" onsubmit="validate();">
            <div class="paneltitle" align="center">Diagnosis Unit</div>
            <div class="panelcontents">
                <table width="40%">

                    <tr>
                        <td>Enter Unit Number :</td>
                        <td><input type="text" name="unitno" id="unitno" maxlength="16" value="<?php
                            if (isset($unitno)) {
                                echo $unitno;
                            }
                            ?>" required/></td>

                        <td>
                            <input type="submit" name="diagnosis" id="diagnosos" value="Diagnosis"/>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <?php if (isset($_REQUEST['diagnosis']) && !empty($_REQUEST['unitno'])) { ?>
        <div id="cardTemplate">
            <div id="header">Result</div>
            <div id="content">
                <div style="padding:10px;text-align: center;">
                    <table id="dataTable" border="1" cellpadding="0" cellspacing="0">
                        <tr><th>Table Name</th><th>Status</th><th>Comments</th></tr>
                        <?php
                        $count = 0;
                        $comment = $display = '';
                        if (empty($unitid)) {
                            $comment.='Unitid,';
                            $count++;
                        }
                        if (empty($customerno)) {
                            $comment.='Customerno';
                            $count++;
                        }
                        ?>
                        <tr>
                            <td>Unit</td>
                            <td><?php
                                if ($count != 0) {
                                    echo 'Not Ok';
                                    $display = 'Incorrect ' . $comment;
                                } else {
                                    echo 'Ok';
                                }
                                ?></td>
                            <td><?php echo $display; ?></td>
                        </tr>

                        <tr><?php
                            $count = 0;
                            $comment = $display = '';
                            if (empty($vehicleid)) {
                                $comment.='Vehicleid,';
                                $count++;
                            }
                            if (empty($vehicle_customerno) || $vehicle_customerno != $customerno) {
                                $comment.='Customerno';
                                $count++;
                            }
                            ?>
                            <td>Vehicle</td>
                            <td><?php
                                if ($count != 0) {
                                    echo 'Not Ok';
                                    $display = 'Incorrect ' . $comment;
                                } else {
                                    echo 'Ok';
                                }
                                ?></td>
                            <td><?php echo $display; ?></td>
                        </tr>
                        <tr><?php
                            $count = 0;
                            $comment = $display = '';
                            if (empty($device_simcardid)) {
                                $comment.='Simcardid,';
                                $count++;
                            }
                            if (empty($device_customerno) || $device_customerno != $customerno) {
                                $comment.='Customerno';
                                $count++;
                            }
                            ?>
                            <td>Devices</td>
                            <td><?php
                                if ($count != 0) {
                                    echo 'Not Ok';
                                    $display = 'Incorrect ' . $comment;
                                } else {
                                    echo 'Ok';
                                }
                                ?></td>
                            <td><?php echo $display; ?></td>
                        </tr>
                        <tr><?php
                            $count = 0;
                            $comment = $display = '';
                            if (empty($simcard_status)) {
                                if ($simcard_customerno > 1 && ($simcard_status == 13 || $simcard_status == 14)) {
                                    
                                } else {
                                    $comment.='Trans_status,';
                                    $count++;
                                }
                            }
                            if (empty($simcard_customerno) || $simcard_customerno != $customerno) {
                                $comment.='customerno';
                                $count++;
                            }
                            ?>
                            <td>Simcard</td>
                            <td><?php
                                if ($count != 0) {
                                    echo 'Not Ok';
                                    $display = 'Incorrect ' . $comment;
                                } else {
                                    echo 'Ok';
                                }
                                ?></td>
                            <td><?php echo $display; ?></td>
                        </tr>
                        <tr><?php
                            $count = 0;
                            $comment = $display = '';
                            if (empty($driver_customerno) || $driver_customerno != $customerno) {
                                $comment.='Customerno';
                                $count++;
                            }
                            ?>
                            <td>Driver</td>
                            <td><?php
                                if ($count != 0) {
                                    echo 'Not Ok';
                                    $display = 'Incorrect ' . $comment;
                                } else {
                                    echo 'Ok';
                                }
                                ?></td>
                            <td><?php echo $display; ?></td>

                        </tr>
                        <tr><?php
                            $count = 0;
                            $comment = $display = '';
                            if (empty($ignitionalert_customerno) || $ignitionalert_customerno != $customerno) {
                                $comment.='Customerno';
                                $count++;
                            }
                            ?>
                            <td>Ignition Alert</td>
                            <td><?php
                                if ($count != 0) {
                                    echo 'Not Ok';
                                    $display = 'Incorrect ' . $comment;
                                } else {
                                    echo 'Ok';
                                }
                                ?></td>
                            <td><?php echo $display; ?></td>
                        </tr>
                        <tr><?php
                            $count = 0;
                            $comment = $display = '';

                            if (empty($eventalerts_customerno) || $eventalerts_customerno != $customerno) {
                                $comment.='Customerno';
                                $count++;
                            }
                            ?>
                            <td>Event Alert</td>
                            <td><?php
                                if ($count != 0) {
                                    echo 'Not Ok';
                                    $display = 'Incorrect ' . $comment;
                                } else {
                                    echo 'Ok';
                                }
                                ?></td>
                            <td><?php echo $display; ?></td>
                        </tr>


                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    function validate() {
        var unitno = jQuery('#unitno').val();
        if (unitno.match(/^[a-z0-9]+$/i)) {
            jQuery('#myDiagnosisFrom').submit();
        } else {
            alert("Wrong unit no");
        }
    }
</script>

<style>
    #dataTable{
        border-collapse:collapse;
        background: lightyellow;
        width:90%;
        margin-left: auto;
        margin-right: auto;
        font-size: 110%;

    }
    #cardTemplate{
        width: 700px;
        height: auto;
        border: 1px solid black;
        top: 80px;
        left: 20%;
    }
    #content{
        display: block;
        width: 100%;
        background: #8FC4E8;
    }
    #header{
        display: block;
        font-size: large;
        background: lightgray;
        padding: 10px;
        border-bottom: 1px solid black;
    }
    #container div{
        position: relative;
    }
</style>
