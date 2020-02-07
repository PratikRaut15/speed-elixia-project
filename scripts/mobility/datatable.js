jQuery(function(){
    
var forTable = jQuery('#forTable').val();
if(forTable=='Vcity'){
    var sortColumn = 1;
    var oTable = jQuery('#viewCity').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=city",
        "order": [[ sortColumn, "desc" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
}
else if(forTable=='Llocation'){
    var sortColumn = 2;
    var oTable = jQuery('#viewlocatn').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=location",
        "order": [[ sortColumn, "desc" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
}
else if(forTable=='Lservice'){
    var sortColumn = 4;
    var oTable = jQuery('#viewservice').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=service",
        "order": [[ sortColumn, "desc" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
}
else if(forTable=='Lclient'){
    var sortColumn = 6;
    var oTable = jQuery('#viewclient').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=client",
        "order": [[ sortColumn, "desc" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
}
else if(forTable=='Ltrackie'){
    var sortColumn = 4;
    var oTable = jQuery('#viewtrackie').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=trackie",
        "order": [[ sortColumn, "desc" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
}
else if(forTable=='Lcall'){
    var sortColumn = 4;
    var oTable = jQuery('#viewcall').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=servicecall",
        "order": [[ sortColumn, "desc" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
}

else if(forTable=='Disc'){
    var sortColumn = 4;
    var oTable = jQuery('#viewdiscount').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=viewdiscount",
        "order": [[ sortColumn, "desc" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
}
else if(forTable=='viewfeedback'){
    var sortColumn = 1;
    var oTable = jQuery('#viewfeedback').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=viewfeedback",
        "order": [[ sortColumn, "desc" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
}
else if(forTable=='viewCat'){
    var sortColumn = 1;
    var oTable = jQuery('#viewcat').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=viewcat",
        "order": [[ sortColumn, "desc" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
}
else if(forTable=='viewPack'){
    var sortColumn = 3;
    var oTable = jQuery('#viewpack').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=viewpack",
        "order": [[ sortColumn, "desc" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
}

if(forTable!=undefined){
    jQuery("thead input").keyup( function () {oTable.fnFilter( this.value, jQuery("thead input").index(this));});
}
jQuery('#entryDate').datepicker({format: "yyyy-mm-dd",autoclose:true,});
jQuery('#entryDate').change(function() {oTable.fnFilter( this.value, jQuery("thead input").index(this) );});

jQuery("input[name=validity_date]").datepicker({dateFormat: "yy-mm-dd",autoclose:true});
jQuery("input[name=validity_date]").change(function() {oTable.fnFilter( this.value, jQuery("thead input").index(this) );});



});