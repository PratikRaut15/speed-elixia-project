jQuery(function(){
    jQuery('body').click(function(){jQuery('#ajaxstatus').hide();});
    var auto = jQuery('#forAuto').val();
    if(auto=='City'){
        jQuery("#citynameauto").autocomplete({
            source: "mobility_ajax.php?action=cityauto",minLength: 1,
            select: function( event, ui ) {jQuery('#cityid').val(ui.item.id);}
        });
    }
    jQuery('#styleBuble').css({"visibility":"hidden"});
        jQuery('.bubbleclose').click(function(){
        jQuery('#styleBuble').css({"visibility":"hidden"});
    });
    
    jQuery('#skills').click(function(){
        jQuery("#styleBuble").css({"visibility":"visible"});
    });
    
    jQuery("#newcat").css('display','none');
    jQuery("#alternateaddress").css('display','none');
    jQuery("#membershipcode").css('display','none');
    jQuery("#membershipamount").css('display','none');
    jQuery("#membershipvalidity").css('display','none');
    jQuery("input[name=entry_date]").datepicker({dateFormat: "yy-mm-dd",autoclose:true});
    jQuery("input[name=membervalidity]").datepicker({dateFormat: "dd-mm-yy",autoclose:true});
    jQuery("input[name=cdob]").datepicker({dateFormat: "dd-mm-yy",autoclose:true});
    jQuery("input[name=cannivrsry]").datepicker({dateFormat: "dd-mm-yy",autoclose:true});
    jQuery("input[name=expdate]").datepicker({dateFormat: "dd-mm-yy",autoclose:true});
    //jQuery("#clocation").html("<option value='0'>No Location</option>"); 
    jQuery(".multiselect").multiselect();
    jQuery("#sp_type_individual").css('display','none');
    jQuery("#sp_type_loc").css('display','none');
    jQuery("#sp_type_group").css('display','none');
    jQuery("#sp_type_city").css('display','none');
    jQuery("input[name$='spectype']").click(function(){
        var spc_type = jQuery(this).val();
        if(spc_type==1){
            jQuery("#sp_type_individual").css('display','table-row');
            jQuery("#sp_type_loc").css('display','none');
            jQuery("#sp_type_group").css('display','none');
            jQuery("#sp_type_city").css('display','none');
        }else if(spc_type==2){
            jQuery("#sp_type_individual").css('display','none');
            jQuery("#sp_type_loc").css('display','table-row');
            jQuery("#sp_type_group").css('display','none');
            jQuery("#sp_type_city").css('display','none');
        }else if(spc_type==3){
            jQuery("#sp_type_individual").css('display','none');
            jQuery("#sp_type_loc").css('display','none');
            jQuery("#sp_type_group").css('display','table-row');
            jQuery("#sp_type_city").css('display','none');
        }
        else if(spc_type==4){
            jQuery("#sp_type_individual").css('display','none');
            jQuery("#sp_type_loc").css('display','none');
            jQuery("#sp_type_group").css('display','none');
            jQuery("#sp_type_city").css('display','table-row');
        }
    });
    
    //edit discount type
    
    jQuery("input[name$='spectype1']").click(function() {
        var spc_type = jQuery(this).val();
        if(spc_type==1){
            jQuery("#sp_type_individual1").css('display','table-row');
            jQuery("#sp_type_loc1").css('display','none');
            jQuery("#sp_type_group1").css('display','none');
            jQuery("#sp_type_city1").css('display','none');
        }else if(spc_type==2){
            jQuery("#sp_type_individual1").css('display','none');
            jQuery("#sp_type_loc1").css('display','table-row');
            jQuery("#sp_type_group1").css('display','none');
            jQuery("#sp_type_city1").css('display','none');
        }else if(spc_type==3){
            jQuery("#sp_type_individual1").css('display','none');
            jQuery("#sp_type_loc1").css('display','none');
            jQuery("#sp_type_group1").css('display','table-row');
            jQuery("#sp_type_city1").css('display','none');
        }else if(spc_type==4){
            jQuery("#sp_type_individual1").css('display','none');
            jQuery("#sp_type_loc1").css('display','none');
            jQuery("#sp_type_group1").css('display','none');
            jQuery("#sp_type_city1").css('display','table-row');
        }
    });
    
    
    function split( val ) {
        return val.split( /,\s*/ );
    }
    
    function extractLast(term) {
        return split( term ).pop();
    }
    
    idsc = new Array();
    jQuery("#ind_client").autocomplete({
        source: "mobility_ajax.php?action=clientauto",
        minLength: 2,
        select: function( event, ui ){
        var terms = split(this.value);
        terms.pop();
        terms.push(ui.item.value);
        terms.push( "");
        this.value = terms.join( ", ");
        return false;
        }
    });
    
    idsc1 = new Array();
    jQuery("#ind_client1").autocomplete({
        source: "mobility_ajax.php?action=clientauto",
        minLength: 2,
        select: function( event, ui ) {
        var terms1 = split(this.value);
        idsc1.push(ui.item.id);
        terms1.pop();
        terms1.push(ui.item.value);
        terms1.push( "");
        this.value = terms1.join( ", ");
        return false;
        }
    });
    
    
    
    jQuery("#location").autocomplete({
        source: "mobility_ajax.php?action=locationauto",minLength: 1,
        select: function( event, ui) {
            jQuery('#locid').val(ui.item.id);
        }
    });
    
    jQuery("#branchname").autocomplete({
        source: "mobility_ajax.php?action=groupauto",minLength: 1,
        select: function( event, ui) {
            jQuery('#grpid').val(ui.item.id);
        }
    });
    
    jQuery("#cityname").autocomplete({
        source: "mobility_ajax.php?action=cityauto",minLength: 1,
        select: function( event, ui) {
            jQuery('#cityid').val(ui.item.id);
        }
    });
    
    jQuery("#servicecat").autocomplete({
        source: "mobility_ajax.php?action=catauto",minLength: 1,
        select: function( event, ui) {
            jQuery('#catid').val(ui.item.id);
        }
    });
    
  
   
});
function addpackdata(){
    var formdata = jQuery("#addpackageform").serialize();
    if(is_empty('membershipcode')|| is_empty('amount')|| is_empty('membervalidity')){show_error('Please Enter Package details.');return false;}
    ajax_request(formdata+"&action=addpackage",'addpackageform');
}
function editpackdata(){
    var formdata = jQuery("#editpackageform").serialize();
    if(is_empty('membershipcode')|| is_empty('amount')|| is_empty('membervalidity')){show_error('Please Enter Package details.');return false;}
    ajax_request(formdata+"&action=editpackage",'editpackageform');
}
function addcatdata(){
    var formdata = jQuery("#addcatform").serialize();
    if(is_empty('catname')){show_error('Please Enter Category.');return false;}
    ajax_request(formdata+"&action=addcat",'addcatform');
}
function editcatdata(){
    var formdata = jQuery("#editcatform").serialize();
    
    if(is_empty('catname')){show_error('Please Enter Category.');return false;}
    ajax_request(formdata+"&action=editcat",'ee');
}
function citydata(){
    var formdata = jQuery("#citymasterform").serialize();
    if(is_empty('cityname')){show_error('Please enter city name.');return false;}
    ajax_request(formdata+"&action=citymaster",'citymasterform');
}
function editcitydata(){
    var formdata = jQuery("#cityeditmasterform").serialize();
    if(is_empty('cityname')){show_error('Please enter city name.');return false;}
    ajax_request(formdata+"&action=editcitymaster",'ee');
}
function deleteCity(a){
   ajax_request("&id="+a+"&action=delcity",'ff');  
   location.reload(); 
}

function deleteloc(a){
   ajax_request("&id="+a+"&action=delloc",'ff');  
   location.reload(); 
}
function deleteserv(a){
   ajax_request("&id="+a+"&action=delsc",'ff');  
   location.reload(); 
}
function deleteclient(a){
   ajax_request("&id="+a+"&action=delclient",'ff');  
   location.reload();  
}
function deletetrackie(a){
   ajax_request("&id="+a+"&action=deltrack",'ff');  
   location.reload();  
}
function deleteservicecall(a){
   ajax_request("&id="+a+"&action=delcall",'ff');  
   location.reload();  
}
function deleteDiscount(a){
   ajax_request("&id="+a+"&action=deldiscount",'ff');  
   location.reload(); 
}
function deleteFeedback(a){
   ajax_request("&id="+a+"&action=delfeedback",'ff');  
   location.reload(); 
}
function deleteCategory(a){
   ajax_request("&id="+a+"&action=delcat",'ff');  
   location.reload(); 
}
function deletePackage(a){
   ajax_request("&id="+a+"&action=delpckg",'ff');  
   location.reload(); 
}
function addService(){
    var formdata = jQuery("#svlform").serialize();
    if(is_empty('servicename') || is_empty('cost') || is_empty('expTime')){show_error('Please fill the mandatory fields.');return false;}
    ajax_request(formdata+"&action=addService",'svlform');
}

function editService(){
    var formdata = jQuery("#editsvlform").serialize();
    if(is_empty('servicename') || is_empty('cost') || is_empty('expTime')){show_error('Please fill the mandatory fields.');return false;}
    ajax_request(formdata+"&action=editService",'ee');
}

function addtrackiedata(){
    var formdata = jQuery("#trackieform").serialize();
    if(is_empty('trackiename') || is_empty('phone') || is_empty('emailid')){ show_error('Please fill mandatory fields.');return false;}
    ajax_request(formdata+"&action=addtrackie",'trackieform');
}

function edittrackiedata(){
    var formdata = jQuery("#edittrackieform").serialize();
    if(is_empty('trackiename') || is_empty('phone') || is_empty('emailid')){ show_error('Please fill mandatory fields.');return false;}
    ajax_request(formdata+"&action=edittrackie",'ee');
}

function locationdata(){
    var formdata = jQuery("#locationmasterform").serialize();
    if(is_empty('citynameauto')){show_error('Please enter city name.');return false;}
    if(is_empty('locationname')){show_error('Please enter location name.');return false;}
    ajax_request(formdata+"&action=locationmaster",'locationmasterform');
}
function editlocationdata(){
    var formdata = jQuery("#editlocationmasterform").serialize();
    if(is_empty('citynameauto')){show_error('Please enter city name.');return false;}
    if(is_empty('locationname')){show_error('Please enter location name.');return false;}
    ajax_request(formdata+"&action=editlocationmaster",'ee');
}

function addClient(){
    var formdata = jQuery("#addclient").serialize();
    if(is_empty('clientno') ||is_empty('cmobile')||is_empty('cpassword')){show_error('Please fill mandatory fields.');return false;}
    ajax_request(formdata+"&action=addClient",'addclient');
}
function editClient(){
    var formdata = jQuery("#editclient").serialize();
    if(is_empty('clientno') || is_empty('cmobile')){show_error('Please fill mandatory fields.');return false;}
    ajax_request(formdata+"&action=editClient",'ee');
}

function addfeedback(){
    var formdata = jQuery("#addfeedbackform").serialize();
    if(is_empty('question')){show_error('Please fill mandatory fields.');return false;}
    ajax_request(formdata+"&action=addFeedback",'addfeedbackform');
}

function editfeedback(){
    var formdata = jQuery("#editfeedbackform").serialize();
    if(is_empty('question')){show_error('Please fill mandatory fields.');return false;}
    ajax_request(formdata+"&action=editFeedback",'ee');
}

function clearids(){
    var clientname = jQuery("#ind_client").val();
    //if(clientname==""){
        console.log(clientname);
        //jQuery("#clid").val('');
        //idsc= new
        
    //}
}

function is_empty(name){if(jQuery("input[name="+name+"]").val()==''){return true;}return false;}
function ajax_request(data,fid){
    jQuery('#pageloaddiv').show();
    jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data: data,
        success:function(result){
            var obj = jQuery.parseJSON(result);
            if(obj.Status === "Success"){show_success(obj.Msg,fid);}else{show_error(obj.Error);}
        },
        complete: function(){jQuery('#pageloaddiv').hide(); }
    });
}
function show_error(text){jQuery("#ajaxstatus").html(text);jQuery("#ajaxstatus").css('color','red');jQuery("#ajaxstatus").show();}
function show_success(text,fid){ jQuery("#ajaxstatus").html(text); jQuery("#ajaxstatus").css('color','green'); jQuery("#ajaxstatus").show(); jQuery('#'+fid+' input,textarea').each(function(){ if(jQuery(this).attr('type')!='submit'){ jQuery(this).val('');}});}

function pullcity1(){
    var city = jQuery("#ccity1").val();
    data ="action=getlocations&cid="+city;
    jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data: data,
        success:function(result){
          jQuery("#clocation1").html(result);
        },
        complete: function(){
           // jQuery('#pageloaddiv').hide();
        }
    });
}

function pullcity(){
    var city = jQuery("#ccity").val();
    data ="action=getlocations&cid="+city;
    jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data: data,
        success:function(result){
          jQuery("#clocation").html(result);
        },
        complete: function(){
           // jQuery('#pageloaddiv').hide();
        }
    });
}

function pullpackage(){
    var pckgid = jQuery("#membershipcd").val();
    if(pckgid==0){
        jQuery("#membershipamount").css('display','none');
        jQuery("#membershipvalidity").css('display','none');
    }else{
    data ="action=getpck&pid="+pckgid;
    jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data: data,
        success:function(result){
           var obj = jQuery.parseJSON(result);
           var amount = obj[0].amount;
           var validity = obj[0].validity;
           jQuery("#membershipamount").css('display','table-row');
           jQuery("#membershipvalidity").css('display','table-row');
           jQuery("#amount").val(amount);
           jQuery("#mvalidity").val(validity);
        },
        complete: function(){
           // jQuery('#pageloaddiv').hide();
        }
    });
}
}

function pullpackage1(){
    var pckgid = jQuery("#membershipcd").val();
    if(pckgid==0){
        jQuery("#membershipamount1").css('display','none');
        jQuery("#membershipvalidity1").css('display','none');
    }else{
    data ="action=getpck&pid="+pckgid;
    jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data: data,
        success:function(result){
           var obj = jQuery.parseJSON(result);
           var amount = obj[0].amount;
           var validity = obj[0].validity;
           jQuery("#membershipamount1").css('display','table-row');
           jQuery("#membershipvalidity1").css('display','table-row');
           jQuery("#amount").val(amount);
           jQuery("#mvalidity").val(validity);
        },
        complete: function(){
           // jQuery('#pageloaddiv').hide();
        }
    });
}
}

jQuery.fn.multiselect = function() {
    jQuery(this).each(function() {
        var checkboxes = jQuery(this).find("input:checkbox");
        checkboxes.each(function() {
            var checkbox = jQuery(this);
            // Highlight pre-selected checkboxes
            if (checkbox.prop("checked"))
                checkbox.parent().addClass("multiselect-on");
 
            // Highlight checkboxes that the user selects
            checkbox.click(function() {
                if (checkbox.prop("checked"))
                    checkbox.parent().addClass("multiselect-on");
                else
                    checkbox.parent().removeClass("multiselect-on");
            });
        });
    });
};

function addDiscount(){
    var formdata = jQuery("#adddiscount").serialize();
    var disccode = jQuery("#disccode").val();
    if(disccode.length<3){ show_error('Please enter discountcode more than 2 characters.');return false;}
    if(is_empty('disccode')){show_error('Please enter discount code.');return false;}
    ajax_request(formdata+"&action=adddiscount",'adddiscount');
    //setTimeout(function(){location.reload(); },2000);
}

function editDiscount(){
    var formdata = jQuery("#editdiscount").serialize();
    var disccode = jQuery("#disccode").val();
    if(disccode.length<3){ show_error('Please enter discountcode more than 2 characters.');return false;}
    if(is_empty('disccode')){show_error('Please enter discount code.');return false;}
    ajax_request(formdata+"&action=editdiscount",'ee');
}


 jQuery('#membership').change(function () {
        if (jQuery('#membership').is(':checked') == true){
            jQuery("#membershipcode").css('display','table-row');
        }else{
            jQuery("#membershipcode").css('display','none');
            jQuery("#membershipamount").css('display','none');
            jQuery("#membershipvalidity").css('display','none');
        }
    });
 
jQuery('#membership1').change(function () {
        if (jQuery('#membership1').is(':checked') == true){
            jQuery("#membershipcode1").css('display','table-row');
        }else{
            jQuery("#membershipcode1").css('display','none');
            jQuery("#membershipamount1").css('display','none');
            jQuery("#membershipvalidity1").css('display','none');
            
        }
    });

 
    
 jQuery('#alternateaddress2').change(function () {
        if (jQuery('#alternateaddress2').is(':checked') == true) {
            jQuery("#alternateaddress").css('display','table-row');
        }else{
            jQuery("#alternateaddress").css('display','none');
        }
    });
    
    jQuery('#alternateaddress3').change(function () {
        if (jQuery('#alternateaddress3').is(':checked') == true) {
            jQuery("#alternateaddress1").css('display','table-row');
        }else{
            jQuery("#alternateaddress1").css('display','none');
        }
    });
    
    jQuery("#cash").css('display','none');  
    jQuery("#cash1").css('display','none');  
    jQuery("#chqno").css('display','none');  
    jQuery("#accountno").css('display','none');  
    jQuery("#branch").css('display','none'); 
    jQuery("#skip").css('display','none'); 
    jQuery("#complete").css('display','none'); 
    
    jQuery("#ptype").change(function(){
        if(jQuery(this).val()!=''){
            var ptype = jQuery("#ptype").val();
            //alert(ptype);
            if(ptype==0){
                jQuery("#cash").css('display','table-row');  
            }else{
                jQuery("#cash").css('display','none');  
            }
            
            if(ptype==1){
                jQuery("#cash").css('display','none');  
            }
            
            if(ptype==2){
                jQuery("#cash1").css('display','table-row');  
                jQuery("#chqno").css('display','table-row');  
                jQuery("#accountno").css('display','table-row');  
                jQuery("#branch").css('display','table-row');  
            }else{
                jQuery("#cash1").css('display','none');  
                jQuery("#chqno").css('display','none');  
                jQuery("#accountno").css('display','none');  
                jQuery("#branch").css('display','none'); 
            }
            
            if(ptype==3){
                jQuery("#skip").css('display','table-row');  
            }else{
                jQuery("#skip").css('display','none'); 
            }
            
            if(ptype==4){
                jQuery("#complete").css('display','table-row');  
            }else{
                jQuery("#complete").css('display','none'); 
            }
        }
    });
    
    
    jQuery('#addnewcat').change(function () {
        if (jQuery('#addnewcat').is(':checked') == true) {
           jQuery("#newcat").css('display','table-row');
        }else{
           jQuery("#newcat").css('display','none');
        }
    });
    var uncheckids = new Array();
    jQuery('#getuncheckvalues').click(function(){
         jQuery("input:checkbox:not(:checked)").each(function () {
            uncheckids.push(jQuery(this).val());
        });
        var storeids = uncheckids.join(",");
        jQuery("#uncheckedids").val(storeids);
        jQuery('#styleBuble').css({"visibility":"hidden"});
    });