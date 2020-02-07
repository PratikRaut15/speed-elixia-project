function submitNomens()
{
    var nomenaName = jQuery('#nomensName').val();  
    if(nomenaName != ''){                            
            var datastring = "nomensName=" + nomenaName ;
            //console.log(datastring);return false;
            jQuery.ajax({
        					type: "POST",
        					url: "nomenclature_ajax.php",
        					data: datastring,
        					cache: false,
        					success: function(result)
        					{ 
                                if(result == ''){
                                    alert("Nomenclature Is Already Exist.");
                                    return false;
                                }
                                else{
                                 /*   jQuery("#saved").show();
                                    jQuery("#saved").fadeOut(3000);*/
                                   window.location.href = "nomenclature.php?id=2";
                                }
        					}
        			});  
    }
    else{
            alert("Nomenclature can not be blank");
            return false;
        } 
}

function updateNomens(nid){
    var nomenaName  =   jQuery('#nomensName').val(); 
    if(nomenaName != ""){                             
        var datastring  = "nomensName=" + nomenaName + "&nid=" +nid;
       jQuery.ajax({
                        type: "POST",
                        url: "nomenclature_ajax.php",
                        data: datastring,
                        cache: false,
                        success: function(html)
                        {   
                             window.location.href = "nomenclature.php?id=2";
                        }
            });  
    }
    else{
             alert("Nomenclature can not be blank");
            return false;
    }
}