<?php
// error_reporting(0);
// error_reporting(E_ALL ^ E_STRICT);
// ini_set('display_errors', 'On');

include_once "session.php";
include_once "../../lib/system/utilities.php";
include "loginorelse.php";
include_once "../../constants/constants.php";
include_once "db.php";
include_once "../../lib/system/DatabaseManager.php";
include_once "../../lib/system/Date.php";
include_once "../../lib/system/Sanitise.php";

class Migrate {
}
$db = new DatabaseManager();

function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d') {
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while ($current <= $last) {
        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}

function check_filexists($date_arr, $oldcno, $oldunit) {
    $file_arr = array();
    foreach ($date_arr as $dates_arr) {
        if (file_exists('../../customer/' . $oldcno . '/unitno/' . $oldunit . '/sqlite/' . $dates_arr . '.sqlite')) {
            $res = 'ok';
        } else {
            $file_arr[] = $dates_arr;
        }
    }
    return $file_arr;
}

if (isset($_POST['Move'])) {
    /*
    print_r($_POST);
    $srcfile='../../customer/96/unitno/900386/sqlite/2015-08-03.sqlite';
    $dstfile='../../customer/64/unitno/900386/sqlite/2015-08-03.sqlite';
    //mkdir(dirname($dstfile), 0777, true);
    $tt=copy($srcfile, $dstfile);
    //var_dump($tt);
    //var_dump(file_exists($dstfile));
    //var_dump(chmod($dstfile,0777));
    //die();
    $path = "sqlite:$dstfile";
    $db= new PDO($path);

    $SQL="UPDATE unithistory SET customerno=";
    $result = $db->query($SQL);

    foreach($result as $row)
    {
    print_r($row);
    }

    die();
     *
     */

    $oldcno = trim(GetSafeValueString($_POST['oldcust'], "String"));
    $newcno = trim(GetSafeValueString($_POST['newcust'], "String"));
    $startdt = GetSafeValueString($_POST['SDate'], "String");
    $enddt = GetSafeValueString($_POST['EDate'], "String");
    $newunit = $_POST['newun'];
    $oldunit = $_POST['oldun'];

    $SQLunit = sprintf("SELECT u.uid,u.unitno,u.customerno,v.vehicleid,v.vehicleno,v.driverid,d.deviceid FROM unit as u
                INNER JOIN vehicle v ON u.uid=v.uid
                INNER JOIN devices d ON u.uid=d.uid
                WHERE u.unitno='%s'", $newunit);

    $db->executeQuery($SQLunit);
    if ($db->get_rowCount() > 0) {
        $row = $db->get_nextRow();
        $newcustomerno = $newcno;
        $uid = $row['uid'];
        $deviceid = $row['deviceid'];
        $vehid = $row['vehicleid'];
        $vehno = $row['vehicleno'];
        $driverid = $row['driverid'];

        $date_arr = date_range($startdt, $enddt); // get date from date range

        $ispresent = check_filexists($date_arr, $oldcno, $oldunit); //check files which exists

        if (!empty($ispresent)) {
            $_SESSION['fileerrMsg'] = implode(",", $ispresent) . " files not found";
        }
        ///////////////////////////////////Copy sqlite file from source to Destination//////////////////

        $copy_arr = array();
        $fail_cp = array();
        foreach ($date_arr as $date_arrs) {
            $srcfile = '../../customer/' . $oldcno . '/unitno/' . $oldunit . '/sqlite/' . $date_arrs . '.sqlite';
            if (!file_exists($srcfile)) {
                continue;
            }
            $dstfolder = '../../customer/' . $newcno . '/unitno/' . $newunit . '/sqlite';

            if (!file_exists($dstfolder)) {
                mkdir($dstfolder, 0777, true);
            }
            //var_dump(chmod($dstfile,0777));
            if (file_exists($dstfolder)) {
                $dstfile = '../../customer/' . $newcno . '/unitno/' . $newunit . '/sqlite/' . $date_arrs . '.sqlite';
                if (file_exists($dstfile)) // if old file exists dont copy
                {
                    continue;
                }
                copy($srcfile, $dstfile);
                $copy_arr[] = $date_arrs;
            } else {
                $fail_cp[] = $date_arrs;
            }
        }

        if (!empty($fail_cp)) {
            $_SESSION['copyerrMsg'] = implode(",", $fail_cp) . " files not copied to Customer No " . $newcno;
        }

        if (!empty($copy_arr)) {
            $error_arr = array();
            foreach ($copy_arr as $copies) {
                $path = '';
                $destfile = '../../customer/' . $newcno . '/unitno/' . $newunit . '/sqlite/' . $copies . '.sqlite';
                if (file_exists($destfile)) {
                    //chmod($destfile,0777);
                    $path = "sqlite:$destfile";
                    $db = new PDO($path);
                    try {
                        $SQLun = sprintf("UPDATE unithistory SET uid=%d,unitno='%s',customerno=%d ,vehicleid=%d", $uid, $newunit, $newcustomerno, $vehid);
                        $resultun = $db->query($SQLun);

                        $SQLve = sprintf("UPDATE vehiclehistory SET customerno=%d ,vehicleid=%d,vehicleno='%s',driverid=%d,uid=%d", $newcustomerno, $vehid, $vehno, $driverid, $uid);
                        $resultve = $db->query($SQLve);

                        $SQLde = sprintf("UPDATE devicehistory SET customerno=%d,deviceid=%d,uid=%d", $newcustomerno, $deviceid, $uid);
                        $resultde = $db->query($SQLde);
                    } catch (PDOException $exception) {
                        $error_arr[] = $exception->getMessage() . $copy_arr . "in file";
                        continue;
                    }
                }
            }
        }

        if (!empty($error_arr)) {
            $_SESSION['updateerrMsg'] = implode(",", $error_arr);
        } elseif (empty($error_arr)) {
            $_SESSION['updateerrMsg'] = "Updated successfully";
        } else {
            $_SESSION['updateerrMsg'] = "Some error has occured";
        }
    } else {
        $error_msg = "Given data not found, Try again";

        echo '<script language="javascript">';
        echo "alert('$error_msg')";
        echo '</script>';
    }

    if (isset($_POST['refresh'])) {
        header("location:migrate.php");
    }
}
include "header.php";
?>

<!-- migrate sqlite files from old customer to new customer -->
<div class="panel">
<div class="paneltitle" align="center">Recover Old Unit Data</div>
<div class="panelcontents">
    <div>
        <form method="POST">
            <input type="submit" name="refresh" id="refresh" value="Refresh" />
        </form>
    </div>
    <form method="POST" name="migrate" id="migrate" action="migrate.php">
        <table>
            <tr>
                <td>
                    Start Date
                </td>
                <td>
                    <input type="text" name="SDate" id="SDate" required><button id="trigger5">...</button>
                </td>
            </tr>
            <tr>
                <td>
                    End Date
                </td>
                <td>
                    <input type="text" name="EDate" id="EDate" required><button id="trigger6">...</button>
                </td>
            </tr>
            <tr>
                <td>
                     Old Unit No.
                </td>
                <td>
                    <input type="text" name="oldun" id="oldun" required>
                </td>


            </tr>
            <tr>
                <td>
                     Old Customer No.
                </td>
                <td>
                    <input type="text" name="oldcust" id="oldcust" required>
                </td>


            </tr>
            <tr>
                <td>
                     New Unit No.
                </td>
                <td>
                    <input type="text" name="newun" id="newun" required>
                </td>


            </tr>
            <tr>
                <td>
                     New Customer No.
                </td>
                <td>
                    <input type="text" name="newcust" id="newcust" required>
                </td>


            </tr>
        </table>
        <div> <input type="submit" id="Move" name="Move" value="Recover"/></div>
    </form>

</div>
</div>

<div class="panel">
<div class="paneltitle" align="center">Details</div>
<div class="panelcontents">
    <div id="fileerrMsg" style="font-weight: bold;color: #FF0000">
            <?php if (!empty($_SESSION['fileerrMsg'])) {echo $_SESSION['fileerrMsg'];}?>
        </div>
        <?php unset($_SESSION['fileerrMsg']);?>
    <div id="copyerrMsg "style="font-weight: bold;color: #FF0000">
            <?php if (!empty($_SESSION['copyerrMsg'])) {echo $_SESSION['copyerrMsg'];}?>
        </div>
        <?php unset($_SESSION['copyerrMsg']);?>
     <div id="updateerrMsg "style="font-weight: bold;color: #FF0000">
            <?php if (!empty($_SESSION['updateerrMsg'])) {echo $_SESSION['updateerrMsg'];}?>
        </div>
        <?php unset($_SESSION['updateerrMsg']);?>
<?php
include "footer.php";
?>

<script>

Calendar.setup(
{
    inputField : "SDate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger5" // ID of the button
});

Calendar.setup(
{
    inputField : "EDate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger6" // ID of the button
});

</script>