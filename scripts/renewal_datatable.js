$(document).ready(function () {
  if(typeof data != 'undefined'){
    CreateDataTable(data, tableId, tableCols);
  }
});

function CreateDataTable(data, tableId, tableCols)
{
    var sortColumn = 0;
    var scrollparam = false;
    if(tableId == 'renewalreport'){
        scrollparam = true;
    }
    
    var oTable = $('#' + tableId + '').dataTable({
        "processing": true,
        "aaData": data,
        "aoColumns": tableCols,
        "order": [sortColumn, "asc"],
        "iDisplayLength": 20,
        "scrollX": scrollparam,
        "stateSave": true,
         dom: 'Bfrtip',
        buttons: [
            { 
                extend: 'csvHtml5',
                title: 'Renewal Report'
            } ,
            { 
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: 'Renewal Report'
            }    
        ],
        "fnInitComplete": function() {
               var oSettings = $('#' + tableId + '').dataTable().fnSettings();
               for ( var i=0 ; i<oSettings.aoPreSearchCols.length ; i++ ){
                   if(oSettings.aoPreSearchCols[i].sSearch.length>0){
                       $("thead input")[i].value = oSettings.aoPreSearchCols[i].sSearch;
                       $("thead input")[i].className = "";
                   }
               }
           }
        
    });
    //Add filter columns
    $("thead input").keyup(function () {
        oTable.fnFilter(this.value, $("thead input").index(this));
    });
    
}


    