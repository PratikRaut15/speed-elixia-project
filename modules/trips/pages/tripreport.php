<?php

if(isset($_POST['filtertype'])){
  $sdate = $_POST['SDate'];
  $edate = $_POST['EDate'];
} 
else {
  $sdate = date("m/d/Y");
  $edate = date("m/d/Y");
}
  $closedtriprecord = gettripreportdata($sdate, $edate);

?>
   <style type="text/css">
        #fuel_report_filter{
            margin-right: 5px;
        }

        #pageloaddiv {
            position: fixed;
            left: 0px;
            top: -80px;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background: url('../../images/progressbar.gif') no-repeat center center;
        }
    </style>
<div class='container' style="width:96%;">
  <center>
    <input type='hidden' id='forTable' value='viewTripsReport'/>
    <h3>Closed Trip Report </h3>

    <form method="post" id="frmctrip" action="<?php //$_SERVER['PHP_SELF'] ?>">
      <table>
        <tr>
          <td>Start Date</td>
          <td>End Date</td>
          <td colspan="2"></td>
        </tr>
        <tr> <td><input id="SDate" name="SDate" type="text" value="<?php echo $sdate; ?>"/></td>
          <td><input id="EDate" name="EDate" type="text" value="<?php echo $edate; ?>" /></td>
        <input type="hidden" name="filtertype" id="filtertype" value="1">
        <td><input type="button" name="closedtrip" class="btn-primary" id="btn-filter-vendoreff" value="Get Data" onclick="closedtripreport(<?php echo $customerno;?>);" /></td>
        <td>
          <a href='javascript:void(0)' onclick="get_pdfclosetripReport(<?php echo $_SESSION['customerno']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
           <a href='javascript:void(0)' onclick="html2xlsclosetripReport(<?php echo $_SESSION['customerno']; ?>);
              return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>  -
                <!--  <span>
            <button id="exportExcelReportDataBttn">Export to Excel</button>
            <button id="exportCSVReportDataBttn">Export to Csv</button>
        </span> -->
        </td>
        </tr>
      </table>
    </form>
       <span style="margin-right:80%;">
            <label>Page Size</label>
            <select id="page-size">
                <option value="10" selected>10</option>
                <option value="100">100</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
            </select>
        </span>
            <div id="pageloaddiv" style='display:none;'></div>

    <div id="viewtripsreport" style="height:500px;width:100%;text-align: left;margin-top:0px" class="ag-theme-balham"></div>
  </center>
</div>
<script>
var gridOptions ;
  var customerno  = <?php echo $_SESSION['customerno']; ?>;    
    var gridDiv = document.getElementById('viewtripsreport');
    if(customerno == '447'){ 
      var columnDefs  = [

                          {headerName:'History',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}, cellRenderer:viewHistory},
                          {headerName:'Vehicle No',field: 'vehiclenos',filter: 'agTextColumnFilter',width:100},
                          {headerName:'Triplog No',field: 'triplogno',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'},cellRenderer: function(params) {
                            return '<a href="javascript:void(0)" onclick="getLocationReport(&quot;21648&quot;,&quot;7835&quot;,&quot;19-09-2018&quot;,&quot;09:16&quot;,&quot;20-09-2018&quot;,&quot;10:39&quot;,&quot;30&quot;,&quot;MH 04  FD 9648&quot;);">'+ params.data.triplogno+'</a>'
                          },valueGetter: function isTripStatusLinkGetter(params) {
                            return params.data.tripLogVal;
                          }},                          
                          {headerName:'LR Creation Time',field: 'lrCreationTime',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Trip Start Date',field: 'startdate',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'LR Delay Time',field: 'lrDelayTime',filter: 'agTextColumnFilter',width:100,filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Yard Checkout Time',field: 'yardCheckout',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Yard Detention Time',field: 'yardDetention',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Yard CheckIn Time',field: 'yardCheckin',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Empty Return Deviation',field: 'emptyReturnDeviation',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Trip End Date',field: 'enddate',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Remark',field: 'remark',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Updated On',field: 'updated_on',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Updated By',field: 'realname',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                        ];

    }
    else{
var columnDefs  = [
                          
                          {headerName:'History',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}, cellRenderer:viewHistory },
                          {headerName:'Vehicle No',field: 'vehiclenos',filter: 'agTextColumnFilter',width:100},
                          {headerName:'Triplog No',field: 'triplogno',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'},cellRenderer: function(params) {
                            return '<a href="javascript:void(0)" onclick="getLocationReport(&quot;21648&quot;,&quot;7835&quot;,&quot;19-09-2018&quot;,&quot;09:16&quot;,&quot;20-09-2018&quot;,&quot;10:39&quot;,&quot;30&quot;,&quot;MH 04  FD 9648&quot;);">'+ params.data.triplogno+'</a>'
                          },valueGetter: function isTripStatusLinkGetter(params) {
                            return params.data.tripLogVal;
                          }},
                          {headerName:'Trip StartDate',field: 'startdate',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Route Name',field: 'routename',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Budgeted Kms',field: 'budgetedkms',filter: 'agTextColumnFilter',width:100,filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Budgeted Hrs',field: 'budgetedhrs',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Consignor',field: 'consignorname',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Consignee',field: 'consigneename',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Billing Party',field: 'billingparty',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Min Temp',field: 'mintemp',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Max Temp',field: 'maxtemp',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Actual Hrs',field: 'actualhrs',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Actual Hrs',field: 'actualkms',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Driver Name',field: 'drivername',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Driver Mob.1',field: 'drivermobile1',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Trip end Date On',field: 'statusdate',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Remark',field: 'remark',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Updated On',field: 'updated_on',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                          {headerName:'Updated By',field: 'realname',filter: 'agTextColumnFilter',width:100, filterParams:{newRowsAction: 'keep'}},
                        ];
    }

var gridOptions = {
                    enableFilter        : true,
                    enableSorting       : true,
                    floatingFilter      : true,
                    rowSelection        : 'multiple',
                    enableColResize     : true,
                    rowGroupPanelShow   : 'always',
                    pivotPanelShow      : 'always',
                    rowData             : [],
                    animateRows         : true,
                    enableRangeSelection: true,
                    columnDefs          : columnDefs,
                    pagination          : true,
                    paginationPageSize  : 10,
                    cacheQuickFilter    : true,
                    defaultColDef       : {
                                            editable        :false,
                                            enableRowGroup  :true,
                                            enablePivot     :true,
                                            enableValue     :true
                                          }
                };

  new agGrid.Grid(gridDiv,gridOptions);
  $("#exportExcelReportDataBttn").click(function(){
      gridOptions.api.exportDataAsExcel();
  })

  $("#exportCSVReportDataBttn").click(function(){
    gridOptions.api.exportDataAsCsv();
  })

  $('#page-size').change(function(){
      var value = document.getElementById('page-size').value;
      gridOptions.api.paginationSetPageSize(Number(value));
  });

  $('#filter-text-box').on('input',function(){
    gridOptions.api.setQuickFilter(document.getElementById('filter-text-box').value);
  });

$(document).ready(function(){
    var data          = <?php echo json_encode($closedtriprecord); ?>;
    var customerno    = <?php echo $_SESSION['customerno']; ?>;    
    var gridDiv       = document.getElementById('viewtripsreport');
    var allColumnIds  = [];

    gridOptions.columnApi.getAllColumns().forEach(function(column) {
        allColumnIds.push(column.colId);
    });

    gridOptions.columnApi.autoSizeColumns(allColumnIds);
   viewReport(gridOptions,data);
});

function closedtripreport(customerno){
    var startdate = jQuery('#SDate').val();
    var enddate   = jQuery('#EDate').val();
    var datediff  = daydiff(parseDateN(startdate), parseDateN(enddate));
   var gridDiv    = document.getElementById('viewtripsreport');
   var customerno = customerno;
     $("#pageloaddiv").show();
    if (startdate != '' && enddate == '') {
        alert("Please select both dates");
        return false;
    } else if (startdate == '' && enddate != '') {
        alert("Please select both dates");
        return false;
    } else if (datediff < 0 || datediff > 90 ) {
        alert("Uh Oh!! Please select correct dates.");
        return false;
    } else{
            var data = {action : 'closedtripreport' ,startdate : startdate ,enddate : enddate };
            jQuery.ajax({
            url  : "trip_ajax.php",
            type : 'POST',
            data : data,
            success: function (result){
                resultData      = jQuery.parseJSON(result);
                console.log(resultData);
                viewReport(gridOptions,resultData);
            },
            complete: function(){
              jQuery('#pageloaddiv').hide();
            }
            });
          }
}

function daydiff(first, second) {
    return (second - first) / (1000 * 60 * 60 * 24)
}

function parseDateN(str) {
    var mdy = str.split('/')
    // alert(mdy[1]-1); return false;
    return new Date(mdy[2], mdy[0], mdy[1] - 1);
}

function viewHistory(params){
  return '<span onclick = "histview('+params.data.tripid+')"><img src="../../images/history.png"></span>';
  //return '<a href="../trips/trips.php?pg=tripview&frm=editfrm&tripid='+params.data.tripid+'" ><img src="../../images/edit_black.png"></span>';
}
  
function viewReport(gridOptions,resultData) {
                   agGrid.simpleHttpRequest({url: 'https://raw.githubusercontent.com/ag-grid/ag-grid/master/packages/ag-grid-docs/src/olympicWinnersSmall.json'}).then(function(data) {
        gridOptions.api.setRowData(resultData);
    });
}//end viewreport

  function histview(a) {
    var url = "trips.php?pg=tripviewdata&tid=" + a;
    location.href = url;
  }
</script>