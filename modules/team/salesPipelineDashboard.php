<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php"); 
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/bo/pipelineManager.php");
include("header.php");

?>
<div class="panel" style="width:80%;margin-bottom: 100px">
    <div class="paneltitle" align="center" style="width:100%;">Sales Analysis by SR</div>
    <div id="statsGrid" class="ag-theme-material" style="height:500px;width:100%;margin:0 auto;">
    </div>
</div>
<div class="panel" style="width:80%;">
    <div class="paneltitle" align="center" style="width:100%;">Sales Representatives</div>
    <div id="myGrid" class="ag-theme-material" style="height:500px;width:100%;margin:0 auto;">
    </div>
</div>
<?php
    function time_range($date) {
        $ts = strtotime($date);
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
        return array(date('Y-m-d 00:00:00', $start),date('Y-m-01 00:00:00'),date('Y-m-d H:i:s'));
    }
    //echo "<pre>"; 
    $today = date ("Y-m-d");
    $dates = time_range($today);
    $obj = new stdClass();
    $obj->weekStart = $dates[0];
    $obj->monthStart = $dates[1];
    $obj->today = $dates[2];
    $obj->teamid = 0;
    if((!checkUserType(speedConstants::TEAM_DEPARTMENT_SALES,speedConstants::TEAM_ROLE_HEAD))&&(!checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT,speedConstants::TEAM_ROLE_HEAD))){
        $queryadd = "AND sp.teamid =".GetLoggedInUserId();
        $obj->teamid = GetLoggedInUserId();
    }
    $pM = new pipelineManager();
    $data = $pM->fetchSRStats($obj);

    $db = new DatabaseManager();
    $SQL = sprintf("SELECT  sp.pipelineid
                            ,sp.pipeline_date
                            ,sp.company_name
                            ,sp.sourceid
                            ,sp.productid
                            ,sp.industryid
                            ,sp.modeid
                            ,sp.teamid
                            ,sp.location
                            ,sp.remarks
                            ,sp.stageid
                            ,sp.timestamp
                            ,sp.loss_reason
                            ,st.stage_name
                            ,t.name as createdby
                            ,te.name as allotTo
                            ,tpdt.tepidityName as tepidity
                            ,sp.quantity
                            ,sp.create_platform
                            ,sp.update_platform
                    FROM    " . DB_PARENT . ".sales_pipeline as sp 
                    LEFT JOIN sales_stage st ON st.stageid = sp.stageid 
                    LEFT JOIN ".DB_ELIXIATECH.".sales_tepidity tpdt ON tpdt.tepidityId = sp.tepidity
                    LEFT JOIN ".DB_ELIXIATECH.".team as t ON sp.teamid_creator = t.teamid 
                    LEFT JOIN ".DB_ELIXIATECH.".team as te ON sp.teamid = te.teamid
                    WHERE   sp.isdeleted = 0 AND sp.stageid NOT IN (9,10) %s 
                    ORDER BY pipelineid DESC", Sanitise::String($queryadd));
    $db->executeQuery($SQL);
    $details = array();
    if ($db->get_rowCount() > 0) {
        $x = 1;
        $delete_url = "";
        while ($row = $db->get_nextRow()) {
            $userdetails = new stdClass();
            $userdetails->pipelineidx = "P00" . $row["pipelineid"];
            $userdetails->srno = $x;
            $userdetails->stage = $row["stage_name"];
            $userdetails->pipeline_date = date('d-m-Y', strtotime($row["pipeline_date"]));
            $userdetails->company_name = $row["company_name"];
            $userdetails->location = $row["location"];
            $userdetails->remarks = $row["remarks"];
            $userdetails->timestamp = $row["timestamp"];
            $userdetails->assignto =$row["loss_reason"];
            $userdetails->pipelineid = $row["pipelineid"];
            $userdetails->createdby = $row["createdby"];
            $userdetails->assignto =$row["allotTo"];
            $teamid = $_SESSION['sessionteamid'];
            $userdetails->tepidity = $row["tepidity"]; 
            $userdetails->quantity = $row["quantity"];
            $userdetails->create_platform = $row["create_platform"];
            $userdetails->update_platform = $row["update_platform"];
            $delete_url = "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' onclick='deletepipeline(" . $pipelineid . ");'><img style='text-align:center; width:20px; height:20px;' src='../../images/delete_icon_black.png'/></a>";
            $userdetails->delete_url = $delete_url;
            $details[] = $userdetails;
            $x++;
        }
    }
    ?>

<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>
    var img;
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var stats = <?php echo json_encode($data)?>;
    //console.log(stats);
    var details = <?php echo json_encode($details)?>;
    var gridOptions;
    var statsCols = [
        {headerName:'Name',field:'name',enableRowGroup:true,pinned: 'left'},
        {headerName:'Customers Onboarded',children:[
                {headerName:'This week',field:'WonWeek',},
                {headerName:'This month',field:'WonMonth', },
            ]
        },
        {headerName:'New Prospects Identified',children:[
                {headerName:'This week',field:'NewWeek', },
                {headerName:'This month',field:'NewMonth',}
            ]
        },
        {headerName:'Customers Lost & Frozen',children:[
                {headerName:'This week',field:'FrozenWeek', },
                {headerName:'This month',field:'FrozenMonth', },
            ],
        },
        {headerName:'PoC',children:[
                {headerName:'Ongoing Demos',field:'Demo'}
            ] 
        },
        {headerName:'Stage Aged(Auto Freeze)',children:[
                {headerName:'This week',field:'SAWeek',},
                {headerName:'This month',field:'SAMonth', },
            ]
        },
        {headerName:'Leads',children:[
                {headerName:'Hot leads',field:'HotLeads',},
                {headerName:'Warm leads',field:'WarmLeads', },
                {headerName:'Cold leads',field:'ColdLeads', },
            ]
        },
    ]
gridOptionsStats = {
    enableFilter:true,
    groupIncludeFooter: true,
    enableSorting: true,
    rowData:stats,
    animateRows:true,
    groupUseEntireRow:true,
    suppressColumnVirtualisation:true,
    columnDefs: statsCols,
    
    // components: {tepidityRender:tepidityRender,createPlatformRender:createPlatformRender,updatePlatformRender:updatePlatformRender},
    onRowGroupOpened: function(params){
        //console.log("row group opened");

        var allColumnIds = [];
        gridOptions.columnApi.getAllColumns().forEach(function(column) {
            allColumnIds.push(column.colId);
        });
        gridOptions.columnApi.autoSizeColumns(allColumnIds);
        //console.log(allColumnIds);
    },
    // onGridReady: function(params) {

    // },
};
    columnDefs = [
        {headerName:'Pipeline ID',field: 'pipelineidx'},
        {headerName:'Quantity', field:'quantity'},
        {headerName:'Company Name',field: 'company_name'},
        {headerName:'SR',field: 'assignto', rowGroup:true,enableRowGroup:true, pinned: 'left'},
        {headerName:'Tepidity', field:'tepidity',cellRenderer:'tepidityRender', rowGroup:true,enableRowGroup:true},
        {headerName:'Stage', field:'stage' ,pivot: true,enableRowGroup:true},
        {headerName:'Prospect Identified On ',field: 'pipeline_date'},
        {headerName:'Created By',field: 'createdby',enableRowGroup:true},
        {headerName:'Last Updated On',field: 'timestamp'},
        {headerName:'Create Platform', field:'create_platform',cellRenderer:'createPlatformRender'},
        {headerName:'Update Platform', field:'update_platform',cellRenderer:'updatePlatformRender'},
    ];

    function deleteCellRenderer(params){
        // return "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' target='_blank' onclick='deletepipeline(" +params.data.pipelineid+ ");'><img style='text-align:center; width:20px; height:20px;' src='../../images/delete_icon_black.png'/></a>"
    }

    function editCellRenderer(params){
        // return "<a href='modify_pipeline.php?pipelineid="+params.data.pipelineid+"' target='_blank' alt='Edit Mode' title='Mode' ><img style='text-align:center; width:20px; height:20px;' src='../../images/edit_icon_black.png'/></a>"
    }

    function tepidityRender(params){
        //console.log(params);
        if(!params.node.allChildrenCount){
            if(params.data.tepidity == 'Hot'){
                return "<div style='background-color:Red;color:white'><strong><center>"+params.data.tepidity+"</center></strong></div>";
            }else if(params.data.tepidity == 'Warm'){
                return "<div style='background-color:Orange;color:white'><strong><center>"+params.data.tepidity+"</center></strong></div>";
            }else if(params.data.tepidity == 'Cold'){
                return "<div style='background-color:blue;color:white'><strong><center>"+params.data.tepidity+"</center></strong></div>";
            }else{
                return "<div style='background-color:Grey;color:white'><strong><center>"+params.data.tepidity+"</center></strong></div>";
            }
        }else if(params.value){
            //console.log(params.value);
            if(params.value == 'Hot'){
                return "<strong style='background-color:Red;color:white'><center>"+params.value+"</center></strong>";
            }else if(params.value == 'Warm'){
                return "<strong style='background-color:Orange;color:white'><center>"+params.value+"</center></strong>";
            }else if(params.value == 'Cold'){
                return "<strong style='background-color:blue;color:white'><center>"+params.value+"</center></strong>";
            }else{
                return "<strong style='background-color:Grey;color:white'><center>"+params.value+"</center></strong>";
            }
        }
        
    }

    function createPlatformRender(params){
        if(!params.node.allChildrenCount){
            if(params.data.create_platform == 2){
                img = "grid-android.png";
            }else if(params.data.create_platform == 3){
                img = "grid-ios.png";
            }else{
                img = "grid-web.png";
            }
            return "<img height=20px src='../../images/"+img+"''>";
        }
    }
    function updatePlatformRender(params){
        if(!params.node.allChildrenCount){
            if(params.data.update_platform == 2){
                img = "grid-android.png";
            }else if(params.data.update_platform == 3){
                img = "grid-ios.png";
            }else{
                img = "grid-web.png";
            }
            return "<img height=20px src='../../images/"+img+"''>";  
        }

    }
    gridOptions = {
        enableFilter:true,
        enableSorting: true,
        rowData:details,
        animateRows:true,
        groupUseEntireRow:true,
        suppressColumnVirtualisation:true,
        rowGroupPanelShow: 'always',
        columnDefs: columnDefs,
        
        components: {tepidityRender:tepidityRender,createPlatformRender:createPlatformRender,updatePlatformRender:updatePlatformRender},
        onRowGroupOpened: function(params){
            //console.log("row group opened");
            gridOptions.api.sizeColumnsToFit();
            // var allColumnIds = [];
            // gridOptions.columnApi.getAllColumns().forEach(function(column) {
            //     allColumnIds.push(column.colId);
            // });
            // gridOptions.columnApi.autoSizeColumns(allColumnIds);
            //console.log(allColumnIds);
        },
        onGridReady: function(params) {
            gridOptions.api.sizeColumnsToFit();
        },
    };
    var gridDiv = document.getElementById('myGrid');
    new agGrid.Grid(gridDiv,gridOptions);
    gridDiv = document.getElementById('statsGrid');
    new agGrid.Grid(gridDiv,gridOptionsStats);
</script>
<script src='../../scripts/pipeline/pipeline.js'></script>