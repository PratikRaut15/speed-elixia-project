var table;
var tableId = 'approvetransaction';
jQuery(document).ready(function () {
    
    var approveidparam = GetParameterValues('id');
    if (approveidparam == 2) {
        $('#btnApprove').attr('disabled', 'disabled');// disable approve button
        //Load Datatable
        var dataString = "work=view_approvetransaction";
        LoadDataTable(dataString);

        // Handle click on "check_all" control
        $("#check_all").click(function (e) {
            var isCheckAllCtrlChecked = this.checked;
            /*
             var rowcollection = table.$(".call-checkbox", {"page": "all"});
             rowcollection.each(function (index, htmlElement) {
             if (isCheckAllCtrlChecked) {
             $(htmlElement).attr("checked", true);
             } else {
             $(htmlElement).attr("checked", false);fshowtask
             }
             });
             */
            if (isCheckAllCtrlChecked) {
                table.rows({page: 'current'}).select();
            } else {
                table.rows({page: 'current'}).deselect();
            }
            e.stopPropagation();
        });

        // Handle row selection event
        $('#' + tableId + '').on('select.dt deselect.dt', function (e, api, items) {
            if (e.type === 'select') {
                $('tr.selected input[type="checkbox"]', api.table().container()).attr('checked', true);
            } else {
                $('tr:not(.selected) input[type="checkbox"]', api.table().container()).attr('checked', false);
            }

            // Update state of "Select all" control
            updateDataTableCheckAllCtrl(table);
        });

        // Handle table draw event so that check all box is reset to not checked
        $('#' + tableId + '').on('draw.dt', function () {
            if (table) {
                // Update state of "Select all" control
                updateDataTableCheckAllCtrl(table);
            }
        });

        $("#btnApprove").click(function () {
            var ids = [];
            var rowcollection = table.$(".call-checkbox:checked", {"page": "all"});
            for (var i = 0; i < rowcollection.length; i++) {
                ids.push($(rowcollection[i]).val());
            }
            //console.log(ids);
            jQuery("#check_approval").val(ids);
            var count_id = ids.length;
            var invoiceamtarr = [];
            var totalinvoice = 0;
            for (var i = 0; i < ids.length; i++) {
                var invoiceamtarr = ids[i].split('-');
                totalinvoice += parseFloat(invoiceamtarr[3]);
            }
            
            var htmldata = "You are About to Approve/Reject " + count_id + " Transactions<br>Total invoice amount :" + totalinvoice;
            jQuery("#NoOfApproval").html(htmldata);
        });
    }
});

function getFilteredApproval() {
    var data = jQuery("#approval_form").serialize();
    var dataString = "work=view_approvetransaction&filter=1&" + data;
    LoadDataTable(dataString);
}

function update_vehicle_category() {
    var sel_category = jQuery('#sel_category').val();

    var data = "sel_category=" + sel_category;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route.php",
        data: data,
        cache: false,
        success: function (html) {
            window.location.reload();
        }
    });
}

function show_data_for_approvals(main_id) {
    jQuery('#mini_modal').modal('show');

    jQuery.ajax({
        type: "POST",
        url: "route_ajax.php",
        data: {main_id: main_id, work: 'pull_history'},
        success: function (data) {
            jQuery("#mini_modal .modal-body").html(data);

        }
    });

}

function show_acc_data_for_approvals(acc_id) {
    jQuery('#mini_modal').modal('show');
    jQuery.ajax({
        type: "POST",
        url: "route_ajax.php",
        data: {acc_id: acc_id, work: 'pull_acc_history'},
        success: function (data) {
            jQuery("#mini_modal .modal-body").html(data);

        }
    });
}

function push_status(main_id, status) {
    var notes = jQuery('#notes').val(); 
    if (notes == "") {
        jQuery("#error_msg").html("Note cannot be empty");
    } else {
        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php",
            data: {main_id: main_id, work: 'push_status', note: notes, status: status},
            success: function () {
                jQuery('#mini_modal').modal('hide');
                //location.reload();
                window.location.href = "approvals.php?id=2";
                //alert("test");
            }
        });
    }
}

function push_acc_status(main_id, status) {
    var note = jQuery("#note").val();

    if (note == "") {
        jQuery("#error_msg").html("Note cannot be empty");

    } else {

        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php",
            data: {main_id: main_id, work: 'push_acc', note: note, status: status},
            success: function () {
                jQuery('#mini_modal').modal('hide');
                //location.reload(); 
                window.location.href = "approvals.php?id=2";
            }
        });
    }
}

function print_battery_closed(maintenanceid, vehicleid) {
    window.open('pdftest.php?report=transaction&vehicleid=' + vehicleid + '&maintenanceid=' + maintenanceid, '_blank')
}

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
                {"mData": "approval_chkdata"}
                , {"mData": "trans"}
                , {"mData": "vehicleno"}
                , {"mData": "category"}
                , {"mData": "group"}
                , {"mData": "dname"}
                , {"mData": "quote_amount"}
                , {"mData": "invno"}
                , {"mData": "invoice_amount"}
                , {"mData": "statusname"}
                , {"mData": "role"}
                , {"mData": "sender"}
                , {"mData": "submit_date"}
                , {"mData": "timestamp"}
                , {"mData": "edit"}
            ];
            table = CreateDataTable(transactionsList, tableId, tableCols, 10);
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
            "lengthMenu": "Show _MENU_ entries",
            "zeroRecords": "No records found",
            "info": "Showing page _PAGE_ of _PAGES_ (_TOTAL_ out of _MAX_ transactions)",
            //"infoEmpty": "Showing page _PAGE_ of _PAGES_ of _MAX_ transactions",
            "infoEmpty": "Unable to find transactions",
            //"infoFiltered": "(filtered from _MAX_ total records)"
            "infoFiltered": ""
        },
        "columnDefs": [
            {
                targets: 0,
                orderable: false,
                searchable: false,
                className: 'dt-body-center',
                render: function (data, type, full, meta) {
                    return '<input type="checkbox" class="call-checkbox" value="' + $('<div/>').text(data).html() + '">';
                }
            }
        ],
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

function updateDataTableCheckAllCtrl(table) {
    var $table = table.table().container();
    var $chkbox_all = $('tbody input[type="checkbox"]', $table);
    var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[type="checkbox"]', $table).get(0);

    // If none of the checkboxes are checked
    if ($chkbox_checked.length === 0) {
        chkbox_select_all.checked = false;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
        //disable approval button if no checkbox is checked
        $('#btnApprove').attr('disabled', 'disabled');
    } else if ($chkbox_checked.length === $chkbox_all.length) {
        // If all of the checkboxes are checked
        chkbox_select_all.checked = true;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
        //enable approval button on checkbox check
        $('#btnApprove').removeAttr('disabled');
    } else {
        // If some of the checkboxes are checked
        chkbox_select_all.checked = true;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = true;
        }
        //enable approval button on checkbox check
        $('#btnApprove').removeAttr('disabled');
    }
}

function push_checkedapproval(action) {
    var approval_array = jQuery("#check_approval").val();
    var notes = jQuery("#note").val();
    if (notes == '') {
        jQuery("#approvalerror_note").show();
        jQuery("#approvalerror_note").fadeOut(3000);
    } else if (approval_array.length == 0) {
        jQuery("#approvalerror_checkbox").show();
        jQuery("#approvalerror_checkbox").fadeOut(3000);
    } else {
        var dataString = "work=multiple_approval&action=" + action + "&approvaldata=" + approval_array + "&note=" + notes;
        jQuery.ajax({
            url: 'route_ajax.php',
            type: 'POST',
            data: dataString,
            success: function () {
                window.location.href = "approvals.php?id=2";
                jQuery("#approvalsuccess_msg").show();
                //jQuery("#approvalsuccess_msg").fadeOut(2000);
            }
        });
    }
}


function activetextbox()
{
    var tyretype = jQuery("#tyrerepair").val();
    if (tyretype == 2 || tyretype == 3) {
        jQuery(".txtsrno").css("display", "none");
        if (jQuery("#rf").prop("checked"))
        {
            jQuery("#oright_front").attr("readonly", false);
        }
        else {
            jQuery("#oright_front").attr("readonly", true);
        }

        if (jQuery("#lf").prop("checked"))
        {
            jQuery("#oleft_front").attr("readonly", false);
        }
        else {
            jQuery("#oleft_front").attr("readonly", true);
        }

        if (jQuery("#rb_out").prop("checked"))
        {
            jQuery("#oright_back_out").attr("readonly", false);
        }
        else {
            jQuery("#oright_back_out").attr("readonly", true);
        }

        if (jQuery("#lb_out").prop("checked"))
        {
            jQuery("#oleft_back_out").attr("readonly", false);
        }
        else {
            jQuery("#oleft_back_out").attr("readonly", true);
        }

        if (jQuery("#rb_in").prop("checked"))
        {
            jQuery("#oright_back_in").attr("readonly", false);
        }
        else {
            jQuery("#oright_back_in").attr("readonly", true);
        }
        if (jQuery("#lb_in").prop("checked"))
        {
            jQuery("#oleft_back_in").attr("readonly", false);
        }
        else {
            jQuery("#oleft_back_in").attr("readonly", true);
        }

        if (jQuery("#st").prop("checked"))
        {
            jQuery("#ostepney").attr("readonly", false);
        }
        else {
            jQuery("#ostepney").attr("readonly", true);
        }
    }
    else {
        if (jQuery("#rf").attr("checked"))
        {
            jQuery("#nright_front").css("display", "block");
            jQuery('#nright_front').removeAttr("readonly");
        }
        else {
            jQuery("#nright_front").css("display", "none");
        }
        if (jQuery("#rb_out").attr("checked"))
        {
            jQuery("#nright_back_out").css("display", "block");
            jQuery('#nright_back_out').removeAttr("readonly");
        }
        else {
            jQuery("#nright_back_out").css("display", "none");
        }
        if (jQuery("#rb_in").attr("checked"))
        {
            jQuery("#nright_back_in").css("display", "block");
            jQuery('#nright_back_in').removeAttr("readonly");
        }
        else {
            jQuery("#nright_back_in").css("display", "none");
        }

        if (jQuery("#lb_out").attr("checked"))
        {
            jQuery("#nleft_back_out").css("display", "block");
            jQuery('#nleft_back_out').removeAttr("readonly");
        }
        else {
            jQuery("#nleft_back_out").css("display", "none");
        }
        if (jQuery("#lf").attr("checked"))
        {
            jQuery("#nleft_front").css("display", "block");
            jQuery('#nleft_front').removeAttr("readonly");
        }
        else {
            jQuery("#nleft_front").css("display", "none");
        }
        if (jQuery("#lb_in").attr("checked"))
        {
            jQuery("#nleft_back_in").css("display", "block");
            jQuery('#nleft_back_in').removeAttr("readonly");
        }
        else {
            jQuery("#nleft_back_in").css("display", "none");
        }
        if (jQuery("#st").attr("checked"))
        {
            jQuery("#nstepney").css("display", "block");
            jQuery('#nstepney').removeAttr("readonly");
        }
        else {
            jQuery("#nstepney").css("display", "none");
        }
        if (jQuery("#newbatt_chkbox").attr("checked")) {
            jQuery("#new_battsrno").css("display", "block");
            jQuery('#new_battsrno').removeAttr("readonly");
        }
        else {
            jQuery("#new_battsrno").css("display", "none");
        }
    }
}






