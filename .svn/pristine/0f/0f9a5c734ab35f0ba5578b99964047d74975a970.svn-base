jQuery(function () {

    var forTable = jQuery('#forTable').val();
    if (forTable == 'viewClient') {
        var sortColumn = 1;
        var oTable = jQuery('#viewclient').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=viewclient",
            "order": [[sortColumn, "desc"]], "iDisplayLength": 50, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
        });
    }
    else if (forTable == 'viewProduct') {
        var sortColumn = 2;
        var oTable = jQuery('#viewproduct').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=viewproduct",
            "order": [[sortColumn, "desc"]], "iDisplayLength": 50, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
        });
    }
    else if (forTable == 'viewStage') {
        var sortColumn = 2;
        var oTable = jQuery('#viewstage').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=viewstage",
            "order": [[sortColumn, "desc"]], "iDisplayLength": 50, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
        });
    }
    else if (forTable == 'viewLost') {
        var sortColumn = 2;
        var oTable = jQuery('#viewlost').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=viewlost",
            "order": [[sortColumn, "desc"]], "iDisplayLength": 50, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
        });
    }
     else if (forTable == 'viewSourceorder') {
        var sortColumn = 2;
        var oTable = jQuery('#viewsourceorder').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=viewsourceorder",
            "order": [[sortColumn, "desc"]], "iDisplayLength": 50, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
        });
    }
    else if (forTable == 'viewReminder') {
        var sortColumn = 2;
        var oTable = jQuery('#viewreminder').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=viewreminder",
            "order": [[sortColumn, "desc"]], "iDisplayLength": 50, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
        });
    }
    else if (forTable == 'viewOrder') {
        var sortColumn = 2;
        var oTable = jQuery('#vieworder').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=vieworder",
            "order": [[sortColumn, "desc"]], "iDisplayLength": 50, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
        });
    }
    else if (forTable == 'viewTemplate') {
        var sortColumn = 0;
        var oTable = jQuery('#viewtemplate').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=viewtemplate",
            "order": [[sortColumn, "desc"]], "iDisplayLength": 50, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
        });
    }
    

    if (forTable != undefined) {
        jQuery("thead input").keyup(function () {
            oTable.fnFilter(this.value, jQuery("thead input").index(this));
        });
    }
    jQuery('#entryDate').datepicker({format: "yyyy-mm-dd", autoclose: true, });
    jQuery('#entryDate').change(function () {
        oTable.fnFilter(this.value, jQuery("thead input").index(this));
    });

    jQuery("input[name=validity_date]").datepicker({dateFormat: "yy-mm-dd", autoclose: true});
    jQuery("input[name=validity_date]").change(function () {
        oTable.fnFilter(this.value, jQuery("thead input").index(this));
    });



});