<style type="text/css">
    .ag-body-container{
text-align:left;}
</style>
<?php
if (isset($_GET['delid']) && $_GET['delid']) {
	delgroup($_GET['delid']);
}
if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') {
	$groups = getgroupdetail_hierarchy($_SESSION['userid']);
//TODO: uncomment below and remove above line once SP is done
	//$groups = getgroupdetail($_SESSION['userid']);
} else {
	$groups = getmappedgroup($_SESSION['userid']);
}
if (isset($groups)) {
	foreach ($groups as $group) {
		if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') {
			$group->groupcode = $group->groupcode;
			$group->realname = $group->realname;
			$group->timestamp = $group->timestamp;
		}
		$group->action = "";
		if ($_SESSION['switch_to'] == 1) {
			if ($edit_permission == 1 || $_SESSION['role_modal'] == 1) {
				$group->action .= "<td><a href = 'group.php?id=4&did=" . $group->groupid . "&groupname=" . $group->groupname . "'><i class='icon-pencil'></i></a></td>";
			}
		} else {
			$group->action .= "<td><a href = 'group.php?id=4&did=" . $group->groupid . "&groupname=" . $group->groupname . "'><i class='icon-pencil'></i></a></td>";
		}

		if ($_SESSION['switch_to'] == 1) {
			if ($delete_permission == 1 || $_SESSION['role_modal'] == 1) {
				$group->action .= "<a href = 'group.php?id=2&delid=" . $group->groupid . "' onclick='return confirm('Are you sure you want to delete?');'><i class='icon-trash'></i></a>";

			} else {
				$group->action .= "<a href = 'group.php?id=2&delid=" . $group->groupid . "' onclick='return confirm('Are you sure you want to delete?');'><i class='icon-trash'></i></a>";
				if ($_SESSION['switch_to'] == 1) {
					if ($delete_permission == 1 || $_SESSION['role_modal'] == 1) {
						$group->action .= "<a href = 'group.php?id=2&delid=" . $group->groupid . "' onclick='return confirm('Are you sure you want to delete?');'><i class='icon-trash'></i></a>";
					}
				} else {
					$group->action .= "<a href = 'group.php?id=2&delid=" . $group->groupid . "' onclick='return confirm('Are you sure you want to delete?');'><i class='icon-trash'></i></a>";
				}
			}
		} else {
			$group->action .= "<a href = 'group.php?id=2&delid=" . $group->groupid . "' onclick='return confirm('Are you sure you want to delete?');'><i class='icon-trash'></i></a>";
		}
		if ($_SESSION['customerno'] == 64 && $_SESSION['role_modal'] == 'elixir' || $_SESSION["role_modal"] == 'Master' || $_SESSION["role_modal"] == 'Head Office') {
			if ($_SESSION['switch_to'] == 1) {
				if ($edit_permission == 1 || $_SESSION['role_modal'] == 1) {
					$group->action .= " |  <a href = 'group.php?id=5&did=" . $group->groupid . "'> <i class='icon-book' title='Group Logs'></i></a>";
				}
			} else {
				$group->action .= " |  <a href = 'group.php?id=5&did=$group->groupid'><i class='icon-book' title='Group Logs'></i></a>";
			}
		}
	}
}
?>
</tbody>
</table>
<div id="myGrid" class="ag-theme-balham" style="height:500px;width:400px;margin:0 auto;border: 1px solid gray"></div>
<script src="https://unpkg.com/ag-grid-enterprise@18.1.1/dist/ag-grid-enterprise.min.js"></script>
<script src="../../scripts/speedAgGrid.js"></script>
<script>
var gridDiv          = document.getElementById('myGrid');
var gridData         = <?php echo json_encode($groups) ?>;
var use_maintenance  = "<?php echo $_SESSION['use_maintenance']; ?>";
var switch_to        = "<?php echo $_SESSION['switch_to']; ?>";
var use_hierarchy    = "<?php echo $_SESSION['use_hierarchy']; ?>";

if(use_maintenance == 1 && switch_to == 1 && use_hierarchy == 1){
    $("#myGrid").css("width", "70%");
    gridColumns = [

        {headerName:'Actions', field: 'action', cellRenderer:'editCellRenderer', width: 100,suppressFilter:true},
        {headerName:'GroupName', field: 'groupname',width:100,filter: 'agTextColumnFilter'},
        {headerName:'Groupcode',field: 'groupcode',width:100,filter: 'agTextColumnFilter'},
        {headerName:'Last Modified By',field: 'realname',width:120,filter: 'agTextColumnFilter'},
        {headerName:'Last Modified On',field: 'timestamp',width: 150,filter: 'agTextColumnFilter'}
    ];
}
else{

    gridColumns = [
        {headerName:'GroupName', field: 'groupname',width:300,filter: 'agTextColumnFilter'},
        {headerName:'Actions', field: 'action', cellRenderer:'editCellRenderer', width:200,suppressFilter:true}
    ];
}

function editCellRenderer(params){
    return params.data.action;
}

function onGridReadytest(){
    var allColumnIds = [];
    gridOptions.columnApi.getAllColumns().forEach(function(column) {
        allColumnIds.push(column.colId);
    });
    gridOptions.columnApi.autoSizeColumns(allColumnIds);
}

var components = {editCellRenderer:editCellRenderer};
var gridCustomOptions = {paginationPageSize:25, pagination:true,components, onGridReadypagination:false,onGridReadytest};
gridOptions = createAgGrid(gridDiv, gridColumns, gridData, gridCustomOptions);
console.log(gridOptions);
</script>
