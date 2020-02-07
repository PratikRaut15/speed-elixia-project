jQuery(document).ready(function() {
    var sortColumn = 0;
    var oTable = jQuery('#zoneMaster').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "masters_ajax.php?of=zone",
        "order": [[ sortColumn, "asc" ]],
        "iDisplayLength": 50,
        "aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
        "aoColumns": [
            null,
            null,
            
        ],
        
    });
    
    /* Filter */
    jQuery("thead input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, jQuery("thead input").index(this) );
    } );
    
    var oTableArea = jQuery('#areaMaster').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "masters_ajax.php?of=area",
        "order": [[ sortColumn, "asc" ]],
        "iDisplayLength": 50,
        "aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
        "aoColumns": [
            null,
            null,
            null,
            null,
            null,
            null,
        ],
        
    });
    
    /* Filter */
    jQuery("thead input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTableArea.fnFilter( this.value, jQuery("thead input").index(this) );
    } );
    
    var oTable = jQuery('#slotMaster').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "masters_ajax.php?of=slot",
        "order": [[ 0, "asc" ]],
        "iDisplayLength": 50,
        "aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
        "aoColumns": [
            null,
            null,
            
        ],
        
    });
    
    /* Filter */
    jQuery("thead input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, jQuery("thead input").index(this) );
    } );
    
    
    
} );
