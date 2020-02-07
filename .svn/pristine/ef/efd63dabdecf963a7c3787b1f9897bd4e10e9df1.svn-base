jQuery(document).ready(function () {
  if(typeof data != 'undefined'){
    CreateDataTable(data, tableId, tableCols);
  }
});

function CreateDataTable(data, tableId, tableCols)
{
    var sortColumn = 0;
    var scrollparam = false;
    if(tableId == 'proposedindentmaster'){
        scrollparam = true;
    }
    
    var oTable = jQuery('#' + tableId + '').dataTable({
        "processing": true,
        "aaData": data,
        "aoColumns": tableCols,
        "order": [sortColumn, "asc"],
        "iDisplayLength": 20,
        "scrollX": scrollparam,
        "stateSave": true,
        "fnInitComplete": function() {
               var oSettings = jQuery('#' + tableId + '').dataTable().fnSettings();
               for ( var i=0 ; i<oSettings.aoPreSearchCols.length ; i++ ){
                   if(oSettings.aoPreSearchCols[i].sSearch.length>0){
                       jQuery("thead input")[i].value = oSettings.aoPreSearchCols[i].sSearch;
                       jQuery("thead input")[i].className = "";
                   }
               }
           },
        "fnDrawCallback": function( oSettings ){statusColor();},
    });
    //Add filter columns
    jQuery("thead input").keyup(function () {
        oTable.fnFilter(this.value, jQuery("thead input").index(this));
    });
    
}

function statusColor(){
        var odrStat = 8;
        var rows = jQuery('tr');
        var rowVal = '';
        jQuery(rows).each(function(index,value){
            rowVal = jQuery(value).find('td:eq(8)').html();
            //alert(rowVal);
            if(rowVal=='Indent Rejected By Transporter'){ jQuery(value).find('td:eq('+odrStat+')').css('backgroundColor', '#FE4042');}
            else if(rowVal=='Indent Confirmed By Transporter'){jQuery(value).find('td:eq('+odrStat+')').css('backgroundColor', '#70DB70');}
            else if(rowVal=='Indent Not Confirmed. Auto Rejected By System'){jQuery(value).find('td:eq('+odrStat+')').css('backgroundColor', '#FE4042');}
            else if(rowVal=='Indent Expired'){jQuery(value).find('td:eq('+odrStat+')').css('backgroundColor', '#FEA048');}
            else if(rowVal=='Awaiting Response'){jQuery(value).find('td:eq('+odrStat+')').css('backgroundColor', '#FFDF00');}
        });
    }
    
/*
 
 jQuery(function(){
 
 
 
 
 var forTable = jQuery('#forTable').val();
 if(forTable=='depotMaster'){
 var sortColumn = 1;
 var oTable = jQuery('#depotmaster').dataTable({"processing": true,"serverSide": true,"ajax": "viewajax.php?of=depotMaster",
 
 "order": [[ sortColumn, "ASC" ]],"iDisplayLength": 50,"aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
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
 */