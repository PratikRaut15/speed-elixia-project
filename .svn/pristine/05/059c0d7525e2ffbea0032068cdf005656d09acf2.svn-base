<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/DatabaseManager.php"); 
include_once("../../lib/system/Sanitise.php");
include("header.php");
?>
<div class="panel" style="width:100%;">
    <div class="paneltitle" align="center" style="width:100%;">Frozen & Lost Pipelines</div>
    <div id="myGrid" class="ag-theme-material" style="height:500px;width:100%;margin:0 auto;">
    </div>
</div>
<?php

if(!checkUserType(speedConstants::TEAM_DEPARTMENT_SALES,speedConstants::TEAM_ROLE_HEAD)){
    $queryadd = "AND sp.teamid =".GetLoggedInUserId()."";
}
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
                    WHERE   sp.isdeleted = 0 AND sp.stageid IN (9,10,12) %s 
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

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var details = <?php echo json_encode($details)?>;
    var gridOptions;
    columnDefs = [
        {cellRenderer:'editCellRenderer',width: 70  },
        {cellRenderer:'deleteCellRenderer',width: 80},
        {cellRenderer:'reviveCellRenderer',width: 80},
        {headerName:'Pipeline ID',field: 'pipelineidx'},
        {headerName:'Tepidity', field:'tepidity', cellRenderer:'tepidityRender'},
        {headerName:'Quantity', field:'quantity'},
        {headerName:'Company Name',field: 'company_name'},
        {headerName:'Stage', field:'stage' ,pivot: true},
        {headerName:'Prospect Identified On ',field: 'pipeline_date'},
        {headerName:'Responsible',field: 'assignto'},
        {headerName:'Created By',field: 'createdby'},
        {headerName:'Last Updated On',field: 'timestamp'},  
        {headerName:'Create Platform', field:'create_platform',cellRenderer:'createPlatformRender'},
        {headerName:'Update Platform', field:'update_platform',cellRenderer:'updatePlatformRender'},
    ];
    function deleteCellRenderer(params){
        return "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' target='_blank' onclick='deletepipeline(" +params.data.pipelineid+ ");'><img style='height:25px;padding:7px;' src='../../images/delete_icon_black.png'/></a>"
    }

    function editCellRenderer(params){
        return "<a href='modify_pipeline.php?pipelineid="+params.data.pipelineid+"' target='_blank' alt='Edit Mode' title='Mode' ><img style='height:25px;padding:7px;' src='../../images/edit_icon_black.png'/></a>"
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
        if(params.data.create_platform == 2){
            img = "grid-android.png";
        }else if(params.data.create_platform == 3){
            img = "grid-ios.png";
        }else{
            img = "grid-web.png";
        }
        return "<img style='height:20px;padding:7px;' src='../../images/"+img+"''>";
    }

    function updatePlatformRender(params){
        if(params.data.update_platform == 2){
            img = "grid-android.png";
        }else if(params.data.update_platform == 3){
            img = "grid-ios.png";
        }else{
            img = "grid-web.png";
        }
        return "<img style='height:20px;padding:7px;' src='../../images/"+img+"''>";
    }

    function reviveCellRenderer(params){
        //console.log(params);
        return "<a href='#' onclick='revivePipeline("+params.data.pipelineid+")'><img style='height:20px;padding:7px;' src='../../images/grid-revive.png' alt='Revive'>";
    }

    gridOptions = {
        enableFilter:true,
        enableSorting: true,
        rowData:details,
        suppressColumnVirtualisation:true,
        animateRows:true,
        columnDefs: columnDefs,
        components: {deleteCellRenderer : deleteCellRenderer,editCellRenderer : editCellRenderer, tepidityRender:tepidityRender,createPlatformRender:createPlatformRender,updatePlatformRender:updatePlatformRender,reviveCellRenderer:reviveCellRenderer},
        onGridReady: function(params) { 
            var allColumnIds = [];
            gridOptions.columnApi.getAllColumns().forEach(function(column) {
                allColumnIds.push(column.colId);
            });
            gridOptions.columnApi.autoSizeColumns(allColumnIds);
        }
    };
    var gridDiv = document.getElementById('myGrid');
    new agGrid.Grid(gridDiv,gridOptions);
    function revivePipeline(pipelineId){
        //console.log(pipelineId);
        var data = 'pipelineId='+pipelineId+'&revivePipeline=1';
        jQuery.ajax({
            url: 'pipeline_functions.php',
            type: 'POST',
            data:data,
            cache: false,
            success: function (res) {
                if (res == 'ok') {
                    window.location.reload();
                }
            }
        });
        
    }
</script>
<script src='../../scripts/pipeline/pipeline.js'></script>