<!DOCTYPE html>
<html lang="en">
<head>
</head>

<?php
include_once("session.php");
include_once("loginorelse.php");
include_once("../../lib/bo/DocketManager.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
date_default_timezone_set("Asia/Calcutta");
$teamid = GetLoggedInUserId();
include("header.php");
$tm=new TeamManager();
$db = new DatabaseManager();

?>


<body>
<form name="ANALYSIS" id="ANALYSIS" method="POST">
<div id="Unmapped_Vehicle_Div" style="text-align: center;margin:50px auto;font-size: 20px">
<p style="font-size: 20px;">Elixia Job Applications</p>
<div id="jobOpeningsGrid" class="ag-theme-blue" style="height:200px;width:1200px;margin:0 auto;">
</div>
</div>
</form>
</body>
    <script src="https://unpkg.com/ag-grid-enterprise@18.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>
  jQuery(document).ready(function () {  
      getJobOpenings();
  });
  function getJobOpenings(){
    jQuery.ajax({
            type: "POST",
            url: "route_team.php",
            data: "fetch_job_applications=1",
            success: function(data){
                var result=JSON.parse(data);
                //console.log(result);
                gridOptions.api.setRowData(result);
            }
    });
  }

</script>

<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
  
    // function resumeCellRenderer(params){
    //     return "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' onclick='deletepipeline(" +params.data.pipelineid+ ");'><img style='text-align:center; width:20px; height:20px;' src='../../images/delete.png'/></a>"
    // }

 

    function resumeCellRenderer(params){
            return "<a href='"+params.data.resume+"' download>Download Resume</a>"
    }

  
       
                          columnDefs = [
                          
                        {headerName:'Department',field: 'department',width:150,filter: 'agTextColumnFilter'},
                          {headerName:'Company Role',field: 'companyRole',width:180,filter: 'agTextColumnFilter'},
                          {headerName:'Name',field: 'name',width:150,filter: 'agTextColumnFilter'},
                          {headerName:'Mobile',field: 'mobile',width:120,filter: 'agTextColumnFilter'},
                          {headerName:'Email',field: 'email',width:200,filter: 'agTextColumnFilter'},
                          {headerName:'Applied On',field: 'date',width:150,sort: 'desc',filter: 'agTextColumnFilter'},
                          {headerName:'Resume',cellRenderer:'resumeCellRenderer',width: 150,suppressFilter:true}
                        ];
                        
                                  var gridOptions;
                                  gridOptions = {
                                  enableFilter:true,
                                  enableSorting: true,
                                  animateRows:true,
                                  enableFilter:true,
                                  floatingFilter:true,
                                  columnDefs: columnDefs,
                                  masterDetail: true,

                                  components: {resumeCellRenderer : resumeCellRenderer},
                                  onGridReady: function(params) { 
                                    params.api.sizeColumnsToFit();
                                  }
                            };
                             
                             //gridOptions.rowHeight = 20;
                             var gridDiv = document.getElementById('jobOpeningsGrid');
                             new agGrid.Grid(gridDiv,gridOptions); 
</script>


</html>
