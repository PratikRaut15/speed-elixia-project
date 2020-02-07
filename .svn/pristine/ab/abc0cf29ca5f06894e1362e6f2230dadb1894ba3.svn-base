<style type="text/css">
    .ag-body-viewport{text-align: left;}
</style>
<?php
$kind = '';
if ($_SESSION['use_maintenance'] == 1 && $_SESSION['switch_to'] == 1) {
    $kind_arr = array('Bus', 'Truck', 'Car', 'SUV');
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
}
//require 'panels/viewvehicles.php';
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
        $action = "";
        $x++;
        $vehicle->sr_no = $x;
        if ($_SESSION['groupid'] != null) {
            $vehicle->groupname = $vehicle->groupname;
        }
        if ($_SESSION['use_maintenance'] == '0' || $_SESSION['switch_to'] == '0') {
            if ($vehicle->unitno == "") {
                $vehicle->status_name = "Not Mapped";
            } else {
                $vehicle->status_name = "Mapped";
            }
        } elseif ($_SESSION['use_maintenance'] == '1') {
            $vehicle->status_name = $vehicle->status_name;
        }
        if ($_SESSION['role_modal'] == 'elixir') {
            $vehicle->vehicleNo = "<a href='../realtimedata/vehicleDashboard.php?vid=$vehicle->vehicleid' target='_blank'>" . $vehicle->vehicleno . "</a>";
        } else {
            $vehicle->vehicleNo = $vehicle->vehicleno;
        }

        $vehicle->modified_by = $vehicle->realname;
        $vehicle->modified_on = date("d-m-Y H:i:s", strtotime($vehicle->timestamp));
        if ($vehicle->main_status_id == '1' && in_array($_SESSION['roleid'], $arrRolesWithEditRights)) {
            if ($_SESSION['use_maintenance'] == '1') {
                $action .= " <a href='vehicle.php?id=7&vidread=0&vid=$vehicle->vehicleid' ><i class='icon-pencil'></i></a>";
            }
        } elseif ($vehicle->main_status_id == '1') {
            $action .= " <a href='vehicle.php?id=7&vidread=1&vid=$vehicle->vehicleid'><img src='../../images/view.png' alt='view'></img>";
        } elseif ($vehicle->main_status_id == '2') {
            $action .= " <a href='vehicle.php?id=7&vidread=1&vid=$vehicle->vehicleid'><img src='../../images/view.png' alt='view'></img>";
            $action .= " <a href='#' onclick='get_pdfvehicle(" . $_SESSION['customerno'] . "," . $vehicle->vehicleid . ");'><img src='../../images/print.png'></img></a>";
        } else {
            if ($_SESSION['use_maintenance'] == '0' || $_SESSION['switch_to'] == '0') {
                $action .= " <a href='vehicle.php?id=4&vid=$vehicle->vehicleid' ><i class='icon-pencil'></i></a>";
            } elseif ($_SESSION['use_maintenance'] == '1') {
                $action .= " <a href='vehicle.php?id=7&vidread=0&vid=$vehicle->vehicleid' ><i class='icon-pencil'></i></a>";
            }
            if ($_SESSION['role_modal'] == 'elixir' || $_SESSION["role_modal"] == 'Master') {
                $action .= " |  <a href='route.php?delvid={$vehicle->vehicleid}' onclick='return confirm(\"Are you sure you want to delete?\");' ><i class='icon-trash'></i></a>";
            } elseif ($_SESSION['use_maintenance'] == '1') {
                // echo "<td></td>";
            }
        }
        if ($_SESSION["role_modal"] == 'elixir') {
            $action .= " |  <a href='vehicle.php?id=5&vid=$vehicle->vehicleid&unitno=$vehicle->unitno'><i title='View History' class='icon-eye-open'></i>";
        }
        if ($_SESSION['customerno'] == 64 && $_SESSION['role_modal'] == 'elixir' || $_SESSION["role_modal"] == 'Master' || $_SESSION["role_modal"] == 'Head Office') {
            $action .= " |  <a href='vehicle.php?id=9&vid=$vehicle->vehicleid'><i title='Vehicle Log' class='icon-book'></i></a>";
        }

        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == '1') {
            $action .= " |  <a  href='javascript:void(0);' onclick='getnotes($vehicle->vehicleid)'><img src='../../images/history.png'></img>";
            $action .= " |  <a href='javascript:void(0);' onclick='viewTransHistory($vehicle->vehicleid,$vehicle->customerno);' >History</a>";
            $action .= " |  <a href='javascript:void(0);' onclick='viewUploads($vehicle->vehicleid);' ><i class='icon-file'></i></a>";
        }
        $vehicle->reports = ' <a title="Travel History" onclick="travelhistopen(' . $vehicle->vehicleid . ');"><img src="../../images/history-512.png"  style="cursor:pointer;height:20px;width:20px;"></img></a>';
        $vehicle->reports .= ' | <a title="Route History" onclick="routehistopen(' . $vehicle->vehicleid . ');"><img src="../../images/play_round.png"  style="cursor:pointer;height:20px;width:20px;"></img></a>';
        $vehicle->action = $action;
        $veh_status = getVehicleStatus($vehicle->stoppage_flag, $vehicle->stoppage_transit_time, $vehicle->lastupdated, $vehicle->gpsfixed);
        $vehicle->vehicleStatus = $veh_status;
    }
}
?>
<?php if ($_SESSION['customerno'] == 64) {?>
        <div class="table" style="width:1000px;margin:0 auto;align:left;text-align: right;">
            <span width="100%" colspan="100%" style="text-align:left;">
                <a onclick="exportToMaintenanceUsers('xls')" href="javascript:void(0)">
                    <img class="exportIcons" title="Export to Excel" alt="Export to Excel" src="../../images/xls.gif">
                </a>
            </span>
        </div>
<?php }?>
<div id="myGrid" class="ag-theme-balham" style="height:500px;width:75%;border: 1px solid gray"></div>
<script src="https://unpkg.com/ag-grid-enterprise@18.1.1/dist/ag-grid-enterprise.min.js"></script>
<script src="../../scripts/speedAgGrid.js"></script>
<script>
    function exportToMaintenanceUsers(type) {
        var customerno = "<?php echo $_SESSION['customerno']; ?>";
        var userid = "<?php echo $_SESSION['userid']; ?>";
        var roleid = "<?php echo $_SESSION['roleid']; ?>";
        var dataString = "export=" + type + "&customerno=" + customerno + "&userid=" + userid + "&roleid=" + roleid + "&report=vehiclereport";
        window.open("../reports/savexls.php?" + dataString, '_blank');
    }
    var gridDiv = document.getElementById('myGrid');
    var gridData = <?php echo json_encode($vehicles) ?>;
    var switch_to = <?php echo json_encode($_SESSION['switch_to']) ?>;
    var addpermission = <?php echo json_encode($addpermission) ?>;
    var edit_permission = <?php echo json_encode($edit_permission) ?>;
    var delete_permission = <?php echo json_encode($delete_permission) ?>;
    gridColumns = [
        {headerName:'Actions', field: 'realname', width:150, cellRenderer:'editCellRenderer'},
        {headerName:'Vehicle No', field: 'vehicleno', cellRenderer:'vehicleRenderer'},
        {headerName:'GPS Status', field: 'vehicleStatus'},
        {headerName:'Group',field: 'groupname',filter: 'agTextColumnFilter'},
        {headerName:'Status',field: 'status_name', width:150, filter: 'agTextColumnFilter'},
        {headerName:'Reports',field: 'reports', width:150, cellRenderer:'reportsRenderer' },
        {headerName:'Last Modified By',field: 'modified_by',filter: 'agTextColumnFilter'},
        {headerName:'Last Modified At', field:'modified_on',filter: 'agTextColumnFilter'}
        /*{headerName: 'View', cellRenderer:'editCellRenderer1',width: 70,suppressFilter:true}*/
    ];
    function editCellRenderer(params){
        return params.data.action;
    }
    function vehicleRenderer(params){
        return params.data.vehicleNo;
    }
    function reportsRenderer(params){
        return params.data.reports;
    }
    /*function editCellRenderer1(params){
    return "<a href='ledger_hist.php?lid="+params.data.userid+"' alt='Edit Mode' target='_blank'><img style='text-align:center; width:20px; height:20px;' src='../../images/history.png'/></a>";
    }*/
    function onGridReadytest(){
        var allColumnIds = [];
        gridOptions.columnApi.getAllColumns().forEach(function(column) {
            allColumnIds.push(column.colId);
        });
        gridOptions.columnApi.autoSizeColumns(allColumnIds);
    }
    var components = {editCellRenderer:editCellRenderer, vehicleRenderer:vehicleRenderer, reportsRenderer:reportsRenderer};
    var gridCustomOptions = {paginationPageSize:25, pagination:true,components, onGridReadypagination:false,onGridReadytest};
 //console.log(gridData);
gridOptions = createAgGrid(gridDiv, gridColumns, gridData, gridCustomOptions);
function routehistopen(vehicleid) {
    window.open("../reports/reports.php?vehicleid=" + vehicleid, "_blank");
}

function travelhistopen(vehicleid) {
    window.open("../reports/reports.php?id=2&vehicleid=" + vehicleid, "_blank");
}
</script>
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
<?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == '1') {?>
    <div class="modal hide" id="vehTransHist" style='height:500px;width:80%;left:30%;color:#000'>
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h4 style="color:#0679c0">Transaction History</h4>
        </div>
        <div class="modal-body">
            <div style="float:right; width:50px; margin:5px;"><a href='javascript:void(0)' onclick="pdf_vehiclehistory(<?php echo $_SESSION['customerno']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to Pdf" class='exportIcons' title="Export to Pdf" /></a></div>
            <div style="float:right; width:50px; margin:5px;"><a href='javascript:void(0)' onclick="xls_vehiclehistory(<?php echo $_SESSION['customerno']; ?>);"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a></div><br>
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
<?php }?>