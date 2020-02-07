/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function checkname(test){
    var lastup = test;
    var cname = jQuery("#cname_"+lastup).val();
    var customer = jQuery("#customer").val();
    var userid = jQuery("#userid").val();
    //alert(customer);
    if(cname == ''){
        jQuery("#check_"+lastup).hide();
    }
    if(cname !== ''){
        jQuery.ajax({
           type: "POST",
           url:"../reports/checkname.php",
           data:"customer="+customer+"&userid="+userid+"&cname="+cname+"&test="+lastup,
           success:function(html){
               jQuery("#check_"+lastup).show();
               jQuery("#check_"+lastup).html(html);
           }
        });
    }
}

function createcheck(test){
    
    var ak_check = test;
    if(test==undefined){
        test='';
    }
    var lastup = test;
    var cname = jQuery("#cname_"+lastup).val();
     
    if(cname == ''){
        jQuery('#checkpointarray_'+lastup).show();
    }
    var cadd = jQuery("#cadd_"+lastup).val();
    var clat = jQuery("#lat_"+lastup).val();
    var clong = jQuery("#long_"+lastup).val();
    var vehicle = jQuery("#getdevice_"+lastup).val();
    var customer = jQuery("#customer").val();
    var userid = jQuery("#userid").val();
    var STime_pop = jQuery("#STime_pop_"+lastup).val();
    
    if(cname !== ''){
        jQuery.ajax({
            type: "POST",
            url: "../reports/createcheck.php",
            data: "vehicle="+vehicle+"&customer="+customer+"&userid="+userid+"&cname="+cname+"&clat="+clat+"&clong="+clong+"&cadd="+cadd+"&STime_pop="+STime_pop,
            success: function(html){
                if(ak_check==undefined){
                    jQuery("#success").show();
                    jQuery("#success").html(html);
                    jQuery("#before_success").hide();
                    var add_text = jQuery('#addedSuccessA').val();
                    if(add_text!=''){
                        jQuery("#added_"+add_text).show();
                        jQuery("#add_"+add_text).hide();
                    }
                }
                else{
                    jQuery("#test_"+lastup).html(html);
                }
                
                jQuery('#added_'+lastup).show();
                jQuery('#add_'+lastup).hide();
            }
        });  
     }
}