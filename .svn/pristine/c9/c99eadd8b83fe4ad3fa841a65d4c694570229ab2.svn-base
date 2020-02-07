jQuery(function(){
    
   // jQuery("input[name=pickupdate]").datepicker({format: "dd-mm-yyyy",autoclose:true});
    jQuery("#areaname").autocomplete({ 
        source: "ajax.php?action=areaauto&limit=20",minLength: 2,
        select: function( event, ui ) {jQuery('#areaid').val(ui.item.id);}
    });
   
    jQuery("#zonename").autocomplete({ 
        source: "ajax.php?action=zoneauto&limit=30",minLength: 2,
        select: function( event, ui ) {jQuery('#zoneid').val(ui.item.id);}
    });
    
});
function addOrders(){
    jQuery('#pageloaddiv').show();
    var data = jQuery("#addorders").serializeArray();
    var opts = {};
    jQuery(data).each(function(index,allvalue){
        var main = {};
        main[allvalue.name] = allvalue.value;
        jQuery.extend(opts, main);
    });
    var final = JSON.stringify(opts);
    jQuery.ajax({url: '../../deliveryapi/delivery_v2.php',type:'POST',data:'bill='+final,async:false,success:function(response){
            var obj = jQuery.parseJSON(response);
            jQuery('#pageloaddiv').hide();var color='green';
            if(obj.Status=='Failure'){jQuery('#ajaxstatus').html(obj.Error);color='red';}
            else{
                jQuery('#ajaxstatus').html('Order Added successfully');
                jQuery('#addorders  input,textarea').each(function(){if(jQuery(this).attr('type')!='submit'){jQuery(this).val('');}});
            }
            jQuery("#ajaxstatus").css('color',color);
            jQuery("#ajaxstatus").show();
        }
    });
    jQuery('body').click(function(){jQuery('#ajaxstatus').hide();});
}

function editOrders(){
    jQuery('#pageloaddiv').show();
    var data = jQuery("#editorders").serializeArray();
    var opts = {};
    jQuery(data).each(function(index,allvalue){
        var main = {};
        main[allvalue.name] = allvalue.value;
        jQuery.extend(opts, main);
    });
    var final = JSON.stringify(opts);
    jQuery.ajax({url: '../../deliveryapi/delivery_v2.php',type:'POST',data:'edit='+final,async:false,success:function(response){
            var obj = jQuery.parseJSON(response);
            jQuery('#pageloaddiv').hide();var color='green';
            if(obj.Status=='Failure'){jQuery('#ajaxstatus').html(obj.Error);color='red';}
            else{
                jQuery('#ajaxstatus').html('Order Edited successfully');
                jQuery('#addorders  input,textarea').each(function(){if(jQuery(this).attr('type')!='submit'){jQuery(this).val('');}});
            }
            jQuery("#ajaxstatus").css('color',color);
            jQuery("#ajaxstatus").show();
        }
    });
    jQuery('body').click(function(){jQuery('#ajaxstatus').hide();});
}

function addpayment(){
   // jQuery('#pageloaddiv').show();
    var payment = jQuery("#payment").val();
    var amt = parseInt(jQuery("#inp_amount").val());
    var chk = jQuery("#inp_chkno").val();
    var acc = jQuery("#inp_accno").val();
    var bank = jQuery("#inp_bank").val();
    var branch = jQuery("#inp_branch").val();
    var reason = jQuery("#inp_reason").val();
    var total = jQuery("#total_amount").val();
    var redeem = parseInt(jQuery("#redeem_limit").val());
    
    if(jQuery("#paid_amount").val()!=''){
       var pamt = parseInt(jQuery("#paid_amount").val()); 
    }else{
        var pamt = 0;
    }
    
    if(jQuery("#re_amount").val()!=''){
       var reamt = parseInt(jQuery("#re_amount").val()); 
    }else{
        var reamt = 0;
    }
    
    var vamt = parseInt(amt) + parseInt(pamt);  
    var redeemamt = parseInt(amt) + parseInt(reamt);  
    //alert(redeemamt) ; 
      
      
    if(payment === '000'){
        jQuery('#sel').show();
        jQuery("#sel").fadeOut(5000);
    }else if((payment == '0' || payment == '2' || payment == '1') && amt == ''){
        jQuery('#pamount').show();
        jQuery("#pamount").fadeOut(5000);
    }else if((payment == '0') && amt != '' && (vamt > total)){
        jQuery('#pamountnot').show();
        jQuery("#pamountnot").fadeOut(5000);
    }else if((payment == '1') && amt != '' && (vamt > total)){
        jQuery('#pamountnot').show();
        jQuery("#pamountnot").fadeOut(5000);
    }
    else if(( payment == '2') && amt != '' && (redeemamt > redeem)){
        jQuery('#pamountnotredeem').show();
        jQuery("#pamountnotredeem").fadeOut(5000);
    }
    else if( payment == '3' && amt=='' ){
        jQuery('#pamount').show();
        jQuery("#pamount").fadeOut(5000);
    }
    else if( payment == '3' && amt != '' && (vamt > total)){
        jQuery('#pamountnot').show();
        jQuery("#pamountnot").fadeOut(5000);
    }
    else if( payment == '3' && chk=='' ){
        jQuery('#pchkno').show();
        jQuery("#pchkno").fadeOut(5000);
    }
    else if( payment == '3' && acc=='' ){
        jQuery('#paccno').show();
        jQuery("#paccno").fadeOut(5000);
    }
    else if( payment == '3' && bank=='' ){
        jQuery('#pbank').show();
        jQuery("#pbank").fadeOut(5000);
    }
    else if( payment == '3' && branch=='' ){
        jQuery('#pbranch').show();
        jQuery("#pbranch").fadeOut(5000);
    }
    else if(payment == '4' && reason =='' ){
        jQuery('#preason').show();
        jQuery("#preason").fadeOut(5000);
    }
    else{
        var data = jQuery("#addpayment").serialize();
        //console.log(data);
        jQuery.ajax({
           url:'assign_ajax.php',
           type:'POST',
           data:data,
           async:false,
           success:function(response){
               var color='green';
               if(response === 'ok'){
                   jQuery('#ajaxstatus').html('Payment Added successfully');
                   
               }else{
                   jQuery('#ajaxstatus').html('Payment Not Added, Please Try Again');color='red';
               }
               jQuery("#ajaxstatus").css('color',color);
               jQuery("#ajaxstatus").show();
               
           }
           
        });
    }
    
    
    
}