var table;
var tableId = 'busRouteDetails';
jQuery(document).ready(function () {
        //Load Datatable
        var dataString = "viewBusRoutes=1";
        LoadDataTable(dataString);
});
function LoadDataTable(dataString) {
    jQuery('#' + tableId + '_processing').show();
    jQuery('#' + tableId + '_processing').css("visibility", "visible");
    jQuery('#pageloaddiv').show();
    jQuery.ajax({
        url: 'route_ajax.php',
        type: 'POST',
        data: dataString,
        success: function (result) {
            var transactionsList = jQuery.parseJSON(result);
            var tableCols = [
                {"mData": "vehicleno"}
                , {"mData": "busStopId"}
                , {"mData": "name"}
                , {"mData": "sequence"}
            ];
            table = CreateDataTable(transactionsList, tableId, tableCols, 20);
        },
        complete: function () {
            jQuery('#' + tableId + '_processing').css("visibility", "hidden");
            jQuery('#' + tableId + '_processing').hide();
            jQuery('#pageloaddiv').hide();
        }
    });
}
function CreateDataTable(data, tableId, tableCols, pageLength) {
    var oTable = $('#' + tableId + '').DataTable({
        "language": {

            "zeroRecords": "No records found",
            "info": "Showing page _PAGE_ of _PAGES_ (_TOTAL_ out of _MAX_ Bus Stops)",
            //"infoEmpty": "Showing page _PAGE_ of _PAGES_ of _MAX_ transactions",
            "infoEmpty": "Unable to find Bus Stops",
            //"infoFiltered": "(filtered from _MAX_ total records)"
            "infoFiltered": ""
        },
        "columnDefs": [
            {
                targets: 0,
                orderable: false,
                searchable: false,
                className: 'dt-body-center',

            }
        ],
        "select": {
            style: 'multi'
        },
        "dom": '<"top"ip>rt<"bottom"ip><"clear">',
        "stateSave": true,
        "responsive": true,
        //"orderCellsTop": true,
        "destroy": true,
        "processing": true,
        "paging": true,
        "data": data,
        "columns": tableCols,
        "order": [],
        "pageLength": pageLength,
        "emptyTable": "No data found",
        "initComplete": function () {
            //TODO: To find replacement of legacy aoPreSearchCols
            var oSettings = $('#' + tableId + '').dataTable().fnSettings();
            for (var i = 0; i < oSettings.aoPreSearchCols.length; i++) {
                if (oSettings.aoPreSearchCols[i].sSearch.length > 0) {
                    $("thead input")[i].value = oSettings.aoPreSearchCols[i].sSearch;
                    $("thead input")[i].className = "";
                }
            }
        }
    });
    //Add filter columns
    $("thead input").keyup(function () {
        oTable.columns($('#' + tableId + '' + ' thead input').index(this)).search($(this).val()).draw();
    });
    return oTable;
}
