<style type="text/css">
    .ag-body-container{
text-align:left;}
</style>
<?php
    if(isset($_GET['delid']) && $_GET['delid']) {
        deluser($_GET['delid']);
    }
    if ($_SESSION['switch_to'] == '1') {
        $users = get_deleted_users();
        $mid = 2;
    } else {
        $users = get_deleted_users();
        $mid = 1;
    }
    $objRole = new Hierarchy();
    $objRole->roleid = '';
    $objRole->parentroleid = '';
    $objRole->moduleid = $mid;
    $objRole->customerno = $_SESSION['customerno'];
    $roles = getRolesByCustomer($objRole);
?>
  <?php

  if (isset($users) && count($users) > 0) {
        echo "<p>Userkey would be used in APIs</p>";
        $count=0;
        foreach ($users as $user) {
            $count ++;
            $action = "";
            $user->srno = $count;
            if (isset($user->realname)) {
                $user->realname = cleanNonPritableChars($user->realname);
            }
            if (isset($user->username)) {
                $user->username = cleanNonPritableChars($user->username);
            }
            if ($_SESSION['switch_to'] == '1') {
                /* For Hierarchy Maintenanace Hierarchy */
                $user->role = $user->rolename;
            }
            if (isset($user->role)) {
                 $user->role =$user->role;
            }
            if (isset($user->userkey1)) {
               $user->userkey1 =$user->userkey1;
            }
            if ($_SESSION['switch_to'] == '1') {
                if (isset($user->parentuser)) {
                    $user->parentuser= $user->parentuser;
                }
            }
            if (isset($user->email)) {
                $user->email = $user->email;
            }
            if (isset($user->phone)) {
                $user->phone = $user->phone;
            }
            $action .=  "<a href = 'users.php?id=4&uid=".$user->userid."'>
                            <i class='icon-book'> </i></a>";
           
            $user->action = $action;
            
        }
    }
?>
<?php if($_SESSION['customerno']==64){ ?>
        <div class="table" style="width:1000px;margin:0 auto;align:left;text-align: right;">
            <span width="100%" colspan="100%" style="text-align:left;">
                <a onclick="exportToMaintenanceUsers('xls')" href="javascript:void(0)">
                    <img class="exportIcons" title="Export to Excel" alt="Export to Excel" src="../../images/xls.gif">
                </a>
            </span>
        </div>
<?php } ?>
<div id="myGrid" class="ag-theme-balham" style="height:500px;width:1000px;margin:0 auto;border: 1px solid gray"></div>

<script>
    function exportToMaintenanceUsers(type) {
        var customerno  = "<?php echo $_SESSION['customerno']; ?>";
        var userid      = "<?php echo $_SESSION['userid']; ?>";
        var roleid      = "<?php echo $_SESSION['roleid']; ?>";
        var dataString  = "export=" + type + "&customerno=" + customerno + "&userid=" + userid + "&roleid=" + roleid + "&report=maintenanceuser";
        window.open("../reports/savexls.php?" + dataString, '_blank');
    }
</script>
<script src="https://unpkg.com/ag-grid-enterprise@18.1.1/dist/ag-grid-enterprise.min.js"></script>
<script src="../../scripts/speedAgGrid.js"></script>
<script>
var gridDiv = document.getElementById('myGrid');
var gridData = <?php echo safe_json_encode($users) ?>;
gridColumns = [
    {headerName:'Actions', field: 'realname', cellRenderer:'editCellRenderer', width:100},
    {headerName:'Sr.No.', field: 'srno',width:100},
    {headerName:'Name', field: 'realname',width: 150,filter: 'agTextColumnFilter'},
    {headerName:'Username',field: 'username',width:150,filter: 'agTextColumnFilter'},
    {headerName:'Role',field: 'role',width:150,filter: 'agTextColumnFilter'},
    {headerName:'UserKey',field: 'userkey1',width: 150,filter: 'agTextColumnFilter'},
    {headerName:'Email', field:'email',width:200,filter: 'agTextColumnFilter'},
    {headerName:'Phone',field: 'phone',width:150,filter: 'agTextColumnFilter'},
    {headerName:'Updated By',field: 'updatedBy',width:150,filter: 'agTextColumnFilter'},
    {headerName:'Updated On',field: 'updatedOn',width:150,filter: 'agTextColumnFilter'},
    {headerName:'Created By',field: 'createdBy',width:150,filter: 'agTextColumnFilter'},
    {headerName:'Created On',field: 'createdOn',width:150,filter: 'agTextColumnFilter'}

    /*{headerName: 'View', cellRenderer:'editCellRenderer1',width: 70,suppressFilter:true}*/
];

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


var gridCustomOptions = {paginationPageSize:25, pagination:true, suppressColumnVirtualisation:true, onGridReadypagination:false,onGridReadytest,components};

gridOptions = createAgGrid(gridDiv, gridColumns, gridData, gridCustomOptions);

console.log(gridOptions);

</script>
