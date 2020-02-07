/*
jQuery(document).ready(function() {
    var sortColumn1= 13;
    var odrStat1 = 11;
    var oTable1 = jQuery('#assignOrders1').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "orders_ajax.php",
        "order": [[ sortColumn, "desc" ]],
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
            null,
            null,
            null,
            { "bSortable": false },
            null,
            null,
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
            null,
            null,
            null,
            null,
        ],
        "columnDefs": [
            {
                "targets": [ sortColumn ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ sortColumn-1 ],
                "visible": false,
                "searchable": false
            },
        ],
        "fnDrawCallback": function( oSettings ){statusColor();},
    });
    function statusColor1(){
        var rows = jQuery('tr');
        var rowVal = '';
        jQuery(rows).each(function(index,value){
            rowVal = jQuery(value).find('td:eq(10)').html();
            if(rowVal=='Cancelled'){jQuery(value).find('td:eq('+odrStat+')').parent().css('backgroundColor', '#FFB2B2');}
            else if(rowVal=='Delivered'){jQuery(value).find('td:eq('+odrStat+')').parent().css('backgroundColor', '#70DB70');}
        });
    }
    /* Filter */
/*
    jQuery("thead input1").keyup( function () {
        /* Filter on the column (the index) of this element */
/*        oTable.fnFilter( this.value, jQuery("thead input").index(this), function() {statusColor();} );
    });
    jQuery('#DelDate1').datepicker({format: "yyyy-mm-dd",autoclose:true,});
    jQuery('#DelDate1').change(function() {
        oTable.fnFilter( this.value, jQuery("thead input").index(this) );
    });
    
    jQuery('#OrderDate1').datepicker({format: "yyyy-mm-dd",autoclose:true,});
    jQuery('#OrderDate1').change(function() {
        oTable.fnFilter( this.value, jQuery("thead input").index(this) );
    });
    jQuery('#orderStatus1').change(function() {
        oTable.fnFilter( this.value, jQuery(this).parent().index());
    });
} );

*/

function getDevicekey(routeid){
    jQuery('#pageloaddiv').show();
    var dk=0;
    jQuery.ajax({url:"ajax.php",type: 'POST',async:false,data:'action=devicekey&routeid='+routeid,
        success:function(result){dk=result;},
        complete: function(){jQuery('#pageloaddiv').hide();}
    });
    return dk;
}
function viewOrder(obj){
    jQuery('#billNoSpn').html(obj.order_id); //bill no
    var custno = jQuery('#customerno').val();
    var devicekey = getDevicekey(obj.route);
    var signsrc = "../../customer/"+custno+"/"+devicekey+"/signature/"+obj.id+".jpeg"; //custno/devicekey/signature/orderid.jpeg
    var details = "<tr><td>Zone</td><td>"+obj.zonename+"</td></tr>";
    details += "<tr><td>Area</td><td>"+obj.areaname+"</td></tr>";
    details += "<tr><td>Address</td><td>"+obj.flat+","+obj.building+","+obj.landmark+", "+obj.city+"</td></tr>";
    details += "<tr><td>Delivery Date/Slot</td><td>"+obj.delivery_date+" / "+obj.slot+"</td></tr>";
    details += "<tr><td>Order Date</td><td>"+obj[10]+"</td></tr>";
    details += "<tr><td>Signature</td><td><img width='250' height='100' style='border:1px solid #000;' src='"+signsrc+"'></td></tr>";
    
    jQuery('#ordTbdy').html(details);
    
}

function get_pdfreport(customerno, userid){
var delivery_date = jQuery("#DelDate").val();
window.open('pdftest.php?customerno=' + customerno +'&userid='+userid+'&deldate='+delivery_date+'&report=travelhist','_blank')
} 

function html2xls(customerno, userid){
    var delivery_date = jQuery("#DelDate").val();
    var dataString = 'customerno=' + customerno +'&userid='+userid+'&deldate='+delivery_date+'&report=excelexport';
    window.location="savexls.php?"+dataString;
    
}

function get_stoppage_print(printTitle){
    var vehicleid=jQuery("#deviceid").val();
    var sdate=jQuery("#SDate").val();
    var edate=jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    
    if(vehicleid == ''){
        jQuery('#error3').show();jQuery('#error3').fadeOut(3000);
    }
    else if(datediff < 0){
        jQuery('#error1').show();jQuery('#error1').fadeOut(3000);
    }
    else if(datediff > 30){
        jQuery('#error2').show();jQuery('#error2').fadeOut(3000);
    }
    else if(datediff <= 30){
        standardized_print(printTitle);
    }
}
