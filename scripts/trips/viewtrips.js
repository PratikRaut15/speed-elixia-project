function makeGridView(data, gridDiv, editModalId, customerno) {
    if (customerno == '447') {
        var columnDefs = [
            { headerName: 'Edit', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' }, cellRenderer: editRecord },
            { headerName: 'Vehicle No', field: 'vehicleno', filter: 'agTextColumnFilter', width: 100 },
            {
                headerName: 'Triplog No',
                field: 'triplogno',
                filter: 'agTextColumnFilter',
                width: 100,
                cellRenderer: function(params) {
                    return '<a href="javascript:void(0)" onclick="getLocationReport(&quot;21648&quot;,&quot;7835&quot;,&quot;19-09-2018&quot;,&quot;09:16&quot;,&quot;20-09-2018&quot;,&quot;10:39&quot;,&quot;30&quot;,&quot;MH 04  FD 9648&quot;);">' + params.data.triplogno + '</a>'
                },
                valueGetter: function isTripStatusLinkGetter(params) {
                    return params.data.tripLogVal;
                }
            },
            { headerName: 'Trip Status', field: 'tripstatus', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'LR Creation Time', field: 'varLrCreation', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'LR Delay Time', field: 'lrdelay', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Yard Checkout Time', field: 'varYardCheckout', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Yard Detention Time', field: 'varYardDetention', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Yard CheckIn Time', field: 'varYardCheckin', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Empty Return Deviation', field: 'varEmptyReturnDeviation', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Trip Start Date', field: 'startdate', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
        ];
    } else {
        var columnDefs = [
            { headerName: 'Edit', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' }, cellRenderer: editRecord },
            {
                headerName: '',
                field: 'map_view',
                cellRenderer: function(params) {
                    return '<a title="Trip History" target="_blank" href="trips.php?pg=trip_mapview"><img src="../../images/play_round.png"  style="cursor:pointer;height:20px;width:20px;"></img></a>';
                }
            },
            { headerName: 'Vehicle No', field: 'vehicleno', filter: 'agTextColumnFilter', width: 100 },
            {
                headerName: 'Triplog No',
                field: 'triplogno',
                filter: 'agTextColumnFilter',
                width: 100,
                cellRenderer: function(params) {
                    return '<a href="javascript:void(0)" onclick="getLocationReport(&quot;21648&quot;,&quot;7835&quot;,&quot;19-09-2018&quot;,&quot;09:16&quot;,&quot;20-09-2018&quot;,&quot;10:39&quot;,&quot;30&quot;,&quot;MH 04  FD 9648&quot;);">' + params.data.triplogno + '</a>'
                },
                valueGetter: function isTripStatusLinkGetter(params) {
                    return params.data.tripLogVal;
                }
            },
            { headerName: 'Trip Status', field: 'tripstatus', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Route Name', field: 'routename', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Budgeted Kms', field: 'budgetedkms', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Budgeted Hrs', field: 'budgetedhrs', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Consignor', field: 'consignorname', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Consignee', field: 'consigneename', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Billing Party', field: 'billingparty', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Min Temp', field: 'mintemp', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Max Temp', field: 'maxtemp', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Driver Name', field: 'drivername', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Driver Mob.1', field: 'drivermobile1', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Driver Mob.2', field: 'drivermobile2', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
            { headerName: 'Trip Start Date', field: 'startdate', filter: 'agTextColumnFilter', width: 100, filterParams: { newRowsAction: 'keep' } },
        ];
    }
    var gridOptions = {
        enableFilter: true,
        enableSorting: true,
        floatingFilter: true,
        rowSelection: 'multiple',
        enableColResize: true,
        rowGroupPanelShow: 'always',
        pivotPanelShow: 'always',
        rowData: data,
        animateRows: true,
        enableRangeSelection: true,
        columnDefs: columnDefs,
        pagination: true,
        paginationPageSize: 10,
        cacheQuickFilter: true,
        defaultColDef: {
            editable: false,
            enableRowGroup: true,
            enablePivot: true,
            enableValue: true
        }
    };
    new agGrid.Grid(gridDiv, gridOptions);
    var allColumnIds = [];
    gridOptions.columnApi.getAllColumns().forEach(function(column) {
        allColumnIds.push(column.colId);
    });
    gridOptions.columnApi.autoSizeColumns(allColumnIds);
    $("#exportExcelDataBttn").click(function() {
        gridOptions.api.exportDataAsExcel();
    })
    $("#exportCSVDataBttn").click(function() {
        gridOptions.api.exportDataAsCsv();
    })
    $('#page-size').change(function() {
        var value = document.getElementById('page-size').value;
        gridOptions.api.paginationSetPageSize(Number(value));
    });
    $('#filter-text-box').on('input', function() {
        gridOptions.api.setQuickFilter(document.getElementById('filter-text-box').value);
    });
    /*    document.addEventListener("DOMContentLoaded", function() {
        var eGridDiv = document.querySelector('#myGrid');
        new agGrid.Grid(eGridDiv, gridOptions);
    });*/
}
function editRecord(params) {
    return '<span onclick="openEditTripModal(' + params.data.tripid + ')"><img src="../../images/edit_black.png"></span>';
    //return '<a href="../trips/trips.php?pg=tripview&frm=editfrm&tripid='+params.data.tripid+'" ><img src="../../images/edit_black.png"></span>';
}
function openEditTripModal(tripId) {
    //var data = JSON.parse('{ "name":"John", "age":30, "city":"New York"}');;
    //window.open("../trips/trips.php?pg=editForm&tripid="+tripId+"","popupWindow", "toolbar=yes,scrollbars=yes,resizable=yes,width=1100,height=800");
    var postdata = { 'edittripid': tripId, 'modalid': editModalId };
    $.ajax({
        type: 'POST',
        url: 'trip_ajax.php',
        data: postdata,
        success: function(result) {
            var jsonDataArr = JSON.parse(result);
            jQuery('#' + editModalId).modal('show');
            $("#edittripform").attr("action", "trips.php?pg=tripview&frm=edit&tripid=" + tripId + "");
            $.each(jsonDataArr, function(index1, value1) {
                if (index1 == "getedittripdata") {
                    $.each(value1, function(index2, value2) {
                        $.each(value2, function(index3, value3) {
                            $("#edittripform").find('#' + index3).val(value3);
                        })
                    })
                } else if (index1 == "statusOptionsVal") {
                    $('#edittripstatus').html(value1);
                } else if (index1 == "toYardListOptions") {
                    $('#returnYard').html(value1);
                } else if (index1 == "statusHistoryTR") {
                    $("#tripHistoryDiv").html(value1);
                } else if (index1 == "dropPointsTR") {
                    $("#tripDropPointsDiv").html(value1);
                } else if (index1 == "tripLrDetailsTR") {
                    $("#tripLrDetailsDiv").html(value1);
                }
            });
        }
    })
}
function openAddTripForm(modalId) {
    $.ajax({
        type: 'POST',
        url: 'trip_ajax.php',
        data: 'tripstatus',
        success: function(result) {
            resultval = JSON.parse(result);
            $('#tripstatus').html(resultval);
        }
    })
    jQuery('#' + modalId).modal('show');
}
function relode_tripDetails() {
    $.ajax({
        type: 'POST',
        url: 'trip_ajax.php',
        data: 'tripsdetails',
        async: true,
        success: function(result) {
            //   makeGridView(result,'myGrid');
            //  alert("test").return false;
        }
    })
}