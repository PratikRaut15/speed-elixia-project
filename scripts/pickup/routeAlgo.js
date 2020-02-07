jQuery(function(){
    var routeURL = document.location.pathname;
    jQuery("input[name=orderdate]").datepicker({format: "dd-mm-yyyy",autoclose:true});
    jQuery("input[name=orderdate]").change(function(){
        document.location = routeURL + "?id=5&d="+jQuery(this).val();
    });

});
function runRouteAlog(){
    jQuery('#pageloaddiv').show();
    jQuery.ajax({
        url: 'run_route_algo.php',
        type:'POST',
        data:'mapOrders=1&mapDate='+jQuery("input[name=orderdate]").val(),
        async:false,
        success:function(response){
             jQuery('#pageloaddiv').hide();
        }
    });
    alert('Orders Mapped Successfully');
    location.reload();
}

function orderMappingExcel(date){
    if(date==''){
        alert('Please select date.');
    }
    else{
        window.location='savexls_R.php?report=ordermapping&d='+date;
    }
}
