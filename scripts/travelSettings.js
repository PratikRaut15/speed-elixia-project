function submitTravelSettings(){
    var starttime 	= jQuery('#starttime').val();  
    var endtime 	= jQuery('#endtime').val();  
    var threshold 	= jQuery('#threshold').val();  
	var datastring 	= $("#travelsettingform").serialize();

    if(starttime != '' && endtime != '' && threshold != ''){                            
    		jQuery("#travelsettingform").submit();
    }
    else{
            alert("Nomenclature can not be blank");
            return false;
        } 
}