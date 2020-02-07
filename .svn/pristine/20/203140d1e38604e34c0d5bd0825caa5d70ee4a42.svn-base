jQuery(document).ready(function() {
     var odrStat = 5;
    var oTable = jQuery('#assignUserOrders').dataTable({
         "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax": "userorders_ajax.php",
        "iDisplayLength": 50,
        "aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
        "aoColumns": [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            { "bSortable": true },
            null,
            null
        ],
        "aoSearchCols": [
             null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            { "sSearch": today},
            null,
            null
           
        ],
        
        
       "fnDrawCallback": function( oSettings ){
           statusColor();
       },
        
    });
    function statusColor(){
        var rows = jQuery('tr');
        var rowVal = '';
        jQuery(rows).each(function(index,value){
            rowVal = jQuery(value).find('td:eq(7)').html();
            if(rowVal=='Cancelled'){jQuery(value).find('td:eq('+odrStat+')').parent().css('backgroundColor', '#FFB2B2');}
           else if(rowVal=='Picked Up'){jQuery(value).find('td:eq('+odrStat+')').parent().css('backgroundColor', '#70DB70');}
       });
    }
    /* Filter */
    jQuery('#DelDate').datepicker({format: "yyyy-mm-dd",autoclose:true});
    jQuery("thead input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, jQuery("thead input").index(this));
    });

    jQuery('#DelDate').change(function(){
        oTable.fnFilter( this.value, jQuery("thead input").index(this));
    });
});



function html2xls(customerno, userid){
    var txtpickupdate = jQuery("#DelDate").val();
    var txtorderid = jQuery("#txtorderid").val();
    var txtawbno = jQuery("#txtawbno").val();
    
    
    var dataString = '&txtawbno='+txtawbno+'&txtorderid='+escape(txtorderid)+'&txtpickupdate='+escape(txtpickupdate)+'&customerno='+customerno+'&userid='+userid+'&report=excelexport';
    window.location="savexls.php?"+dataString;
}