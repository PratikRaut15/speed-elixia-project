<?php include '../panels/header.php';?>
<style type="text/css">
    .ag-header-cell-label {
    text-overflow: clip;
    overflow: visible;
    white-space: normal;
}
</style>
<div class="entry">
    <link href='https://fonts.googleapis.com/css?family=Crimson+Text:400,700italic' rel='stylesheet' type='text/css'>
    <div>
        <div style="text-align: left; float: right;">
          <br/>
          <a href="../realtimedata/realtimedata.php"> Go To Old Realtime Data</a>
        </div>

        <div class="row-fluid">
            <div class="span4" style="margin-top:12px;position: relative;bottom: 15px; left:-15px;">
                <div>
                    <?php

                    if (isset($_SESSION['rel_manager']) && $_SESSION['rel_manager']!='') {
                        ?>
                        <div class="stick">
                            <ul>
                                <li>
                                    <a href="#">
                                        <p>Your Dedicated Relationship Manager is
                                            <span style="color: red;">  <?php echo $_SESSION['manager_name']; ?> </span>
                                            </br>Call On <span style="color: red;"><?php echo $_SESSION['manager_mobile'] ?> </span>
                                            or Mail @ <span style="color: red;"><?php echo $_SESSION['manager_email'] ?></span></p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="loaddata" class="span3">
            </div>
            <div  id="loaddata" class="span5"  >

            </div>
        </div>
    </div>


    <div id="myGrid" style="height:600px;text-align: left; padding-top: 10px;" class="ag-theme-balham"></div>
<div>
<?php
include_once('messageToDriver.php')
?>

<script type="text/javascript">



    var collist =<?php echo json_encode($_SESSION["rtdHeaders"]); ?>;
    columnDefinitions = [];
    jQuery.each(collist, function (i, list) {
        var cellredVal = 'renderHtml';
        var varCellClass = '';
        var varWidth ='';
        var varValueFormatter = '';
        var isEditable  = false;
        var isFilter = true;
        var textFilter  = 'agTextColumnFilter';
        var isField = list;
        if(list == "Speed(Km/hr)" || list == "Distance(In Kms)"){
          cellredVal  = 'agAnimateShowChangeCellRenderer';
          varCellClass = 'cell-number';
          varValueFormatter = 'numberFormatter';
        }
        if(list == "Speed(Km/hr)"){
          isField = "Speed";
        }
        if(list == "Distance(In Kms)"){
          isField = "Distance";
        }
        if(list == "Action" ){
          varWidth = 240;
        }
        if( list == "Status" || list == "Checkpoint"){
          varWidth = 180;
        }
        if( list == "Group" ){
          varWidth = 120;
        }
        if(list == "SrNo"){
          varWidth = 25;
        }
        if(list == "Image" || list == "View"  || list == "Digital" || list == "Door"){
          varWidth = 100;
        }
        if(list == 'Vehicle No'){
          isEditable = true;
        }
        if(list == "Vehicle No" || list == "Driver"  || list == "Unit"){
          isFilter = false;
        }


        let mappedColumn = {
            headerName: list,
            field: isField,
            width: varWidth,
            suppressMenu:true,
            suppressFilter:isFilter,
            //headerClass: 'grid-halign-left',
            cellClass: varCellClass,
            enableValue: true,
            valueFormatter: varValueFormatter,
            cellRenderer:cellredVal,
            editable: isEditable,
            filter:textFilter,

          }
          columnDefinitions.push(mappedColumn);
    });


     columnDefinitions = columnDefinitions.filter((column, index, self) =>
      index === self.findIndex((colAtIndex) => (
        colAtIndex.field === column.field
      ))
    )
    console.log(columnDefinitions);
    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");

</script>
<script src="../../scripts/rtddashboard/main.js"></script>
<script src="../../scripts/rtddashboard/mockServer.js"></script>
<script src="../../scripts/rtddashboard/viewportDatasource.js"></script>


</div>
<?php include '../panels/footer.php';?>
