jQuery(document).ready(function() {
    var oTable = jQuery('#assignOrders').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "orders_ajax.php",
        "order": [[ 0, "desc" ]],
        "iDisplayLength": 50,
        "aLengthMenu": [[50, 100, 500,  10000000], [50, 100, 500, "All"]],
    });
    
    
    /* Filter */
    jQuery("thead input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, jQuery("thead input").index(this) );
    } );
    
} );
