//var viewtransactionDataTable;
var tableId = 'viewstudents';

jQuery(document).ready(function () {
    jQuery.ajax({
        url: 'route_ajax.php',
        type: 'POST',
        data: "work=viewstudent",
        success: function (result) {

            var sortColumn = 0;
            var transactionsList = jQuery.parseJSON(result);
//            console.log(transactionsList);

            var tableCols = [
                //{"mData": "srno"} To Do Serial No.
                {"mData": "studentId"}
                , {"mData": "firstName"}//studentName
                , {"mData": "lastName"}//studentName
                , {"mData": "enrollmentNo"}
                , {"mData": "groupname"}
                , {"mData": "board"}
                , {"mData": "grade"}
                , {"mData": "division"}
                , {"mData": "address"}
                , {"mData": "busStopAddress"}


//                , {"mData": "edit", "width": "10%"}

            ];
            viewtransactionDataTable = CreateDataTable(transactionsList, tableId, tableCols, 10);
        },
        complete: function () {
//            jQuery('#pageloaddiv').hide();
        }
    });
    function CreateDataTable(data, tableId, tableCols, pageLength) {

        var oTable = $('#' + tableId + '').DataTable({
            "language": {
                "lengthMenu": "Show _MENU_ entries",
                "zeroRecords": "No records found",
                "info": "Showing page _PAGE_ of _PAGES_ (_TOTAL_ out of _MAX_ transactions)",
                //"infoEmpty": "Showing page _PAGE_ of _PAGES_ of _MAX_ transactions",
                "infoEmpty": "Unable to find transactions",
                //"infoFiltered": "(filtered from _MAX_ total records)"
                "infoFiltered": ""
            },
//        "columnDefs": [
//            {
//                targets: 0,
//                orderable: false,
//                searchable: false,
//                className: 'dt-body-center',
//                render: function (data, type, full, meta) {
//                    return '<input type="checkbox" class="call-checkbox" value="' + $('<div/>').text(data).html() + '">';
//                }
//            }
//        ],
            "select": {
                style: 'multi'
            },
            "dom": '<"top"ip>rt<"bottom"ip<"lengthMenu"l>><"clear">',
            "stateSave": true,
            "responsive": true,
            //"orderCellsTop": true,
            "destroy": true,
            "processing": true,
            "paging": true,
            "data": data,
            "columns": tableCols,
            "order": [0],
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
});
