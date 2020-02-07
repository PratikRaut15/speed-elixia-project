<?php
if ($_SESSION['use_maintenance'] == 1 && $_SESSION['switch_to'] == 1) {
    $kind_arr = array('Bus', 'Truck', 'Car', 'SUV');
    $kind = '';
    if (isset($_REQUEST['vehSearch'])) {
        $kind = ri($_REQUEST['kind']);
        if ($kind != '' && !in_array($kind, $kind_arr)) {
            $kind = '';
        }
    }
    ?>
    <form action="" method="POST" >
        <table>
            <tr><td style="border: none">
                    <select name='kind' id="kind">
                        <option value=''>Select Kind</option>
                        <?php
                        foreach ($kind_arr as $kind_val) {
                            if ($kind == $kind_val) {
                                echo "<option value='$kind_val' selected>$kind_val</option>";
                            } else {
                                echo "<option value='$kind_val'>$kind_val</option>";
                            }
                        }
                        ?>
                    </select></td>
                <td style="border: none"><input type="submit" class="btn-primary" value="Search" name='vehSearch' />&nbsp;&nbsp;
                <a href='javascript:void(0)' onclick="xls_vehicledata(<?php echo $_SESSION['customerno'] ?>)"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a></td><br>
            </tr>
        </table>
    </form>

    <?php
} else {
    $kind = '';
}

require 'panels/viewvehicles.php';

$elixirRoleId = 1;
$masterRoleId = 1;
$stateRoleId = 2;
$zoneRoleId = 3;
$regionRoleId = 4;
$groupRoleId = 5;
switch ($_SESSION['customerno']) {
    case 63:
        $masterRoleId = 28;
        $zoneRoleId = 30;
        $regionRoleId = 31;
        break;
    case 64:
        $masterRoleId = 33;
        $zoneRoleId = 35;
        $regionRoleId = 36;
        break;
    case 118:
        $masterRoleId = 18;
        $stateRoleId = 19;
        $zoneRoleId = 20;
        $regionRoleId = 21;
        $groupRoleId = 22;
        break;
    case 167:
        $masterRoleId = 24;
        $zoneRoleId = 26;
        $regionRoleId = 27;
        break;
    default:
        $masterRoleId = 1;
        $zoneRoleId = 3;
        $regionRoleId = 4;
        break;
}
$vehicles = getvehicles($kind);
$x = 1;
$arrRolesWithEditRights = array($masterRoleId, $stateRoleId, $zoneRoleId, $regionRoleId);
if (isset($vehicles)) {
    foreach ($vehicles as $vehicle) {
        echo "<tr>";

        echo "<td>" . $x++ . "</td>";
        echo "<td>$vehicle->vehicleno</td>";
        if ($_SESSION['groupid'] != null) {
            echo "<td>$vehicle->groupname</td>";
        }
        if ($_SESSION['use_maintenance'] == '0' || $_SESSION['switch_to'] == '0') {
            if ($vehicle->unitno == "")
                echo "<td>Not Mapped</td>";
            else
                echo "<td>Mapped</td>";
        }
        else if ($_SESSION['use_maintenance'] == '1') {
            echo "<td>$vehicle->status_name</td>";
        }
        echo "<td>$vehicle->realname</td>";
        echo "<td>$vehicle->timestamp</td>";

//        if ($vehicle->main_status_id == '1') {
//            echo "<td><a href='vehicle.php?id=7&vidread=1&vid=$vehicle->vehicleid'><img src='../../images/view.png' alt='view'></img></td>";
//            echo "<td></td>";
//        } elseif ($vehicle->main_status_id == '2') {
//            echo "<td><a href='vehicle.php?id=7&vidread=1&vid=$vehicle->vehicleid'><img src='../../images/view.png' alt='view'></img></td>";
//            echo "<td><a href='#' onclick='get_pdfvehicle(" . $_SESSION['customerno'] . "," . $vehicle->vehicleid . ");'><img src='../../images/print.png'></img></a></td>";
//        } else {
            if ($_SESSION['use_maintenance'] == '0' || $_SESSION['switch_to'] == '0') {
                echo "<td><a href='probity.php?id=4&vid=$vehicle->vehicleid' alt='Edit probity' title='Edit probity data'><i class='icon-pencil'></i></a></td>";
                echo "<td><a href='probity.php?id=5&vid=$vehicle->vehicleid' alt='Delete probity data' title='Delete probity data' ><i class='icon-trash'></i></a></td>";
            } 

//            if ($_SESSION['role_modal'] == 'elixir' || $_SESSION["role_modal"] == 'Master') {
//                echo "<td><a href='route.php?delvid={$vehicle->vehicleid}' onclick='return confirm(\"Are you sure you want to delete?\");' ><i class='icon-trash'></i></a></td>";
//            } else if ($_SESSION['use_maintenance'] == '1') {
//                echo "<td></td>";
//            }
        //}
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='100%'  style='text-align:center;'>No Vehicles Created</td></tr>";
}
?>
</tbody>
</table>

<div class="modal hide" id="viewnotes">
    <form class="form-horizontal" id="notes_view">
        <fieldset>
            <div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h4 style="color:#0679c0">Notes</h4>
            </div>
            <div class="modal-body">
                <div  style="overflow-y:scroll; height:400px;">
                    <fieldset>
                        <div class="control-group">
                            <div class="input-prepend">
                                <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:90%">
                                    <thead>
                                        <tr>
                                            <th>Notes</th>                                            
                                            <th>Status</th>
                                            <th>Modified by</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody id="notes_body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div>
        </fieldset>
    </form>
</div>

<?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == '1') { ?>
    <div class="modal hide" id="vehTransHist" style='height:500px;width:80%;left:30%;color:#000'>
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h4 style="color:#0679c0">Transaction History</h4>

        </div>

        <div class="modal-body">
            <div style="float:right; width:50px; margin:5px;"><a href='javascript:void(0)' onclick="pdf_vehiclehistory(<?php echo $_SESSION['customerno']; ?>);
                        return f
                        alse;"><img src="../../images/pdf_icon.png" alt="Export to Pdf" class='exportIcons' title="Export to Pdf" /></a></div>
            <div style="float:right; width:50px; margin:5px;"><a href='javascript:void(0)' onclick="xls_vehiclehistory(<?php echo $_SESSION['customerno']; ?>);
                        return f
                        alse;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a></div><br>
            <div  style="overflow:auto; height:400px;" id='vehTransHistBody'>
            </div>
        </div>
    </div> 

    <div class="modal hide" id="viewuploads">
        <form class="form-horizontal" id="view_upload">
            <fieldset>
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">×</button>
                    <h4 style="color:#0679c0">Uploaded Files</h4>
                </div>
                <div class="modal-body">
                    <div  style="height:300px;">
                        <fieldset>
                            <div class="control-group">
                                <div class="input-prepend">
                                    <table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:100%">
                                        <thead>
                                            <tr>
                                                <th>Uploaded Files Name</th>
                                            </tr>
                                        </thead>
                                        <tbody id="upload_body">
                                        </tbody>
                                    </table>    
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>    
<?php } ?>