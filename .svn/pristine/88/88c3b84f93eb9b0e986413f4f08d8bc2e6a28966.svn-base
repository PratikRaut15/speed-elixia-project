<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
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
<p style="font-size: 20px;">Elixia Job Openings</p>
<div id="jobOpeningsGrid" class="ag-theme-blue" style="height:200px;width:1000px;margin:0 auto;">
  
</div>
</div>
</form>
</body>
<script>
  jQuery(document).ready(function () {  
      getJobOpenings();
  });
  function getJobOpenings(){
    jQuery.ajax({
            type: "POST",
            url: "route_team.php",
            data: "fetch_jobOpening=1",
            success: function(data){
                var result=JSON.parse(data);
                gridOptions.api.setRowData(result);
            }
    });
  }
  function deleteJobOpening(id) {
        var result = "";
        result = confirm("Are you sure you want to delete the prospect ?");
        if (result == true) {
            jQuery.ajax({
                url: "route_team.php",
                type: 'POST',
                cache: false,
                data: "delete_jobOpening=1&careerId="+id,
                success: function (result) {
                    var response = JSON.parse(result);
                    if(response==1){
                      alert("Job Opening Deleted Successfully.");
                      getJobOpenings();
                    }else{
                      alert("Please try again.");
                    }
                }
            });
            return true;
        } else {
            return false;
        }   
}
</script>
<script src="https://unpkg.com/ag-grid-enterprise@17.0.0/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
  
    // function deleteCellRenderer(params){
    //     return "<a href='javascript:void(0);' alt='Delete Mode' title='Mode' onclick='deletepipeline(" +params.data.pipelineid+ ");'><img style='text-align:center; width:20px; height:20px;' src='../../images/delete.png'/></a>"
    // }

    function editCellRenderer(params){
        return "<a href='editCareers.php?careerId="+params.data.ecId+"'><img style='text-align:center; width:20px; height:20px;' src='../../images/edit.png'/></a>"
    }

    function deleteCellRenderer(params){
            return "<a href='javascript:void(0);' alt='Delete' title='Delete' target='_blank' onclick='deleteJobOpening("+params.data.ecId+");'><img style='height:12px;' src='../../images/delete.png'/></a>"
    }

  
       
                          columnDefs = [
                          
                          {headerName:'Department',field: 'department',width:150},
                          {headerName:'Company Role',field: 'companyRole',width:200},
                          {headerName:'Location',field: 'location',width:150},
                          {headerName:'Experience',field: 'experience',width:150},
                          {headerName:'Date',field: 'date',width:170},
                          {headerName: 'Edit', cellRenderer:'editCellRenderer',width:70},
                          {headerName:'Delete',cellRenderer:'deleteCellRenderer',width: 80,}
                        ];
                        
                                  var gridOptions;
                                  gridOptions = {
                                  enableFilter:true,
                                  enableSorting: true,
                                  animateRows:true,
                                  columnDefs: columnDefs,
                                  masterDetail: true,
                                  components: {deleteCellRenderer : deleteCellRenderer,editCellRenderer : editCellRenderer},
                            };
                             
                             //gridOptions.rowHeight = 20;
                             var gridDiv = document.getElementById('jobOpeningsGrid');
                             new agGrid.Grid(gridDiv,gridOptions); 
</script>


</html>
