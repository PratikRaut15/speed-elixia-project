jQuery(function () {

    var forTable = jQuery('#forTable').val();
//    if (forTable == 'viewTrips') {
//        var sortColumn = 1;
//        var oTable = jQuery('#viewtrips').dataTable({"processing": true,"serverSide": true, "ajax": "viewajax.php?of=viewtrips",
//            "order": [[sortColumn, "desc"]], "iDisplayLength": 2, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
//        });
//    } else 
    if (forTable == 'viewTripstatus') {
        var sortColumn = 1;
        var oTable = jQuery('#viewtripstatus').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=viewtripstatus",
            "order": [[sortColumn, "desc"]], "iDisplayLength": 50, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
        });
    } else if (forTable == 'viewConsignee') {
        var sortColumn = 3;
        var oTable = jQuery('#viewconsignee').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=viewconsignee",
            "order": [[sortColumn, "desc"]], "iDisplayLength": 50, "aLengthMenu": [[50, 100, 500, 10000000], [50, 100, 500, "All"]],
        });
    }else if (forTable == 'viewConsignor') {
        var sortColumn = 3;
        var oTable = jQuery('#viewconsignor').dataTable({"processing": true, "serverSide": true, "ajax": "viewajax.php?of=viewconsignor",
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