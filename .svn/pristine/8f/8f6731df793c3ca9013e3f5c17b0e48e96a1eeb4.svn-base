jQuery(document).ready(function () {  
      jQuery('#startdate').datepicker({
          dateFormat: "dd-mm-yy",
          language: 'en',
          autoclose: 1,
          startDate: Date()
      });

      jQuery('#enddate').datepicker({
          dateFormat: "dd-mm-yy",
          language: 'en',
          autoclose: 1,
          startDate: Date()
      });

});
function getSalesUser(){
        jQuery("#salesuser").autocomplete({
            source: "dailySalesReport_function.php?salesusername=getsalesuser",
            select: function (event, ui) {
                jQuery(this).val(ui.item.name);
                jQuery('#salesuserid').val(ui.item.teamid);
                jQuery('#salesusername').val(ui.item.name);
            }
        });
    }

function submitForm(){
    var salesuser   = $("#salesuser").val();
    var startdate   = $("#startdate").val();
    var enddate     = $("#enddate").val();
 
    var startdatearr = startdate.split("-");;
    var newstartdate = startdatearr[2]+"-"+startdatearr[1]+"-"+startdatearr[0];
    var enddatearr = enddate.split("-");
    var newenddate = enddatearr[2]+"-"+enddatearr[1]+"-"+enddatearr[0];

    var startDay    = new Date(newstartdate);
    var endDay      = new Date(newenddate);
    var millisecondsPerDay = 1000 * 60 * 60 * 24;
    var millisBetween      = endDay.getTime() - startDay.getTime();
    var days               = millisBetween / millisecondsPerDay;

    var gridDiv = document.getElementById('result');
    var dateDiff    = Math.floor(days)
      formdata        = $("#salesreport").serialize();

      if(salesuser!= '' && startdate != '' && enddate != ''){
      if(dateDiff < 0){
            alert("End date can not be smaller than start date")
            return false;
        }
        else{
            $('#loading-image').show();
             $.ajax({
                    url: "dailySalesReport_function.php", 
                    data : "&dailysalesdata="+formdata,
                    type: "POST",
                    cache:false,
                    success: function(finalData){
                      $("#result").html('');
                       var customColumns = [];       
                       var result = [];   
                       var gridOptions = [];   
                        result          = JSON.parse(finalData);
                        customColumns   = generateColumns(result);
                      /*  gridOptions.api.sizeColumnsToFit();
                        gridOptions.api.setColumnDefs(customColumns);
                        gridOptions.api.setRowData(result);*/
                         var gridOptions = {
                        columnDefs: customColumns,
                        rowData: result,
                        enableFilter: true,
                        enableSorting: true,
                        suppressContextMenu: true,
                        suppressMenuMainPanel: true,
                        suppressMenuColumnPanel: true,
                        pagination          : true,
                        paginationPageSize  : 10,
                        floatingFilter:true,
                      };
                        //$("#result").html(finalData);
                         new agGrid.Grid(gridDiv, gridOptions);
                      },
                      complete: function(){
                        $('#loading-image').hide();
                      }
                });
       }
    }
    else{
        alert("All fields are mandatory");
        return false;
    }
}

  function generateColumns(data) {
    let columnDefinitions = [];
    data.map(object => {
      Object
        .keys(object)
        .map(key => {
          let mappedColumn = {
            headerName: key.toUpperCase(),
            field: key,
            filter: 'agTextColumnFilter',
            width:(key == 'srno') ? 80 : (key == 'pipelineid') ? 80 : 100,
          }
          columnDefinitions.push(mappedColumn);
        })
    })
    //Remove duplicate columns
    columnDefinitions = columnDefinitions.filter((column, index, self) =>
      index === self.findIndex((colAtIndex) => (
        colAtIndex.field === column.field
      ))
    )
    return columnDefinitions;
}
function getBooleanValue(cssSelector) {
    return document.querySelector(cssSelector).checked === true;
}
