
function getstate()
{
    var nation_id = jQuery("#nationid").val();
    if(jQuery("#nationid").val() == "")
    {
        jQuery("#nation_error").show();
        jQuery("#nation_error").fadeOut(3000);                 
    }
    else
    {
                jQuery.ajax({
                    url: "../vehicle/route_ajax.php",
                    type: 'POST',
                    cache: false,
                    data: {nation_id : nation_id},
                    dataType: 'html',
                    success: function(html){
                        jQuery("#stateid").empty();                        
                        jQuery("#stateid").append(html);
                    }
                });
                return false;		
                         
    }
}

function getdistrict()
{
    var state_id = jQuery("#stateid").val();
    if(jQuery("#stateid").val() == "")
    {
        jQuery("#state_error").show();
        jQuery("#state_error").fadeOut(3000);                 
    }
    else
    {
                jQuery.ajax({
                    url: "../vehicle/route_ajax.php",
                    type: 'POST',
                    cache: false,
                    data: {state_id : state_id},
                    dataType: 'html',
                    success: function(html){
						jQuery("#districtid").empty();                                                
						jQuery("#districtid").append(html);
                    }
                });
                return false;		
                         
    }
}

function getcity()
{
    var district_id = jQuery("#districtid").val();
    if(jQuery("#districtid").val() == "")
    {
        jQuery("#district_error").show();
        jQuery("#district_error").fadeOut(3000);                 
    }
    else
    {
                jQuery.ajax({
                    url: "../vehicle/route_ajax.php",
                    type: 'POST',
                    cache: false,
                    data: {district_id : district_id},
                    dataType: 'html',
                    success: function(html){
						jQuery("#cityid").empty();                                                                        
						jQuery("#cityid").append(html);
                    }
                });
                return false;		
                         
    }
}

	function checkgroup()
	{
		
                var grouparray=jQuery('#column2').sortable('toArray');  
                var role=jQuery('#roleid').val();  
                var city=jQuery('#cityid').val(); 

               
		if(jQuery("#groupname").val() == "")
		{
			jQuery("#namecomp").show();
			jQuery("#namecomp").fadeOut(3000);                 
		}                                  
/*                else if(grouparray == "")
		{
			jQuery("#grouparray").show();
			jQuery("#grouparray").fadeOut(3000);                 
		}                                 
*/                
                else if(city == "")
		{
			jQuery("#city_error").show();
			jQuery("#city_error").fadeOut(3000);   
		}
                else if(jQuery('#districtid').val() == "")
		{
			jQuery("#district_error").show();
			jQuery("#district_error").fadeOut(3000);   
		}
                else if(jQuery('#stateid').val() == "")
		{
			jQuery("#state_error").show();
			jQuery("#state_error").fadeOut(3000);   
		}
                else if(jQuery('#nationid').val() == "")
		{
			jQuery("#nation_error").show();
			jQuery("#nation_error").fadeOut(3000);   
		}                
                else
                    {
                        var params = "groupn=" + encodeURIComponent(jQuery("#groupname").val());
						
						
						
	jQuery.ajax({
        type: "POST",
        url: "group_ajax.php",
        async: true,
        cache: false,
		data:params,
        success: function (statuscheck) {
                            if(statuscheck == "ok")
                            {
                                submitgroup(); 
                                
                            }
                            else
                            {
                                jQuery("#samename").show();
                                jQuery("#samename").fadeOut(3000);                
                            }
			
		}
});
					
                    }
	} 

function submitgroup()
{
    var role=jQuery('#roleid').val();  
    var city=jQuery('#cityid').val();  
    var code=jQuery('#code').val();  
    if(jQuery('#address').val() != 'undefined' && jQuery('#address').val() != null){
        var address=jQuery('#address').val(); 
    }
    else{
        var address=''; 
    }
    
    //$(ui.item).find('h2').click();
    //var sortorder='';
    var groupname = jQuery("#groupname").val();
    var grouparray=jQuery('#column2').sortable('toArray');                              
    var datastring = "groupname=" + groupname + "&grouparray=" + grouparray + "&cityid=" + city + "&code=" + code + "&address=" + address;
            
    jQuery.ajax({
					type: "POST",
					url: "group_ajax.php",
					data: datastring,
					cache: false,
					success: function(html)
					{ 
                        jQuery("#saved").show();
                        jQuery("#saved").fadeOut(3000);
                       // window.location.href = "group.php?id=2";
					}
			});   

}
                                                
function modifygroupchk()
	{
            if(jQuery('#groupname').val()=="")
            {
            alert("Enter Group Name");       
        }
        else{
    var grouparray=jQuery('#column2').sortable('toArray');
                var role=jQuery('#roleid').val();  
                var city=jQuery('#cityid').val();  
                                    
/*                if(grouparray == "")
		{
			jQuery("#grouparray").show();
			jQuery("#grouparray").fadeOut(3000);                 
		}                                
*/                
                if(city == "")
		{
			jQuery("#city_error").show();
			jQuery("#city_error").fadeOut(3000);   
		}
                else if(jQuery('#districtid').val() == "")
		{
			jQuery("#district_error").show();
			jQuery("#district_error").fadeOut(3000);   
		}
                else if(jQuery('#stateid').val() == "")
		{
			jQuery("#state_error").show();
			jQuery("#state_error").fadeOut(3000);   
		}
                else if(jQuery('#nationid').val() == "")
		{
			jQuery("#nation_error").show();
			jQuery("#nation_error").fadeOut(3000);   
		}                                
        else
            {
              //  $("input").change(function(){
                    modifygroup(); 
               //  });
               
            }
	}
    }
        
function modifygroup()
		{
                var role            = jQuery('#roleid').val();  
				var groupid         = jQuery("#groupid").val();
				var groupname       = jQuery("#groupname").val();
				var grouparray      = jQuery('#column2').sortable('toArray');
                var city            = jQuery('#cityid').val(); 
                var code			= jQuery('#code').val();    
                var address			= jQuery('#address').val(); 
             
				var datastring 		= "groupid=" + groupid + "&groupname=" + groupname + "&grouparray=" + 
										grouparray + "&cityid=" + city + "&code=" + code + "&address=" + 
										address;
                         
                        jQuery.ajax({
										type: "POST",
										url: "group_ajax.php",
										data: datastring,
										cache: false,
										success: function(html)
										{ 
                                            jQuery("#saved").show();
                                            jQuery("#saved").fadeOut(3000);
                                            window.location.href = "group.php?id=2";
										}
								});   

			}
jQuery.noConflict();
jQuery( 
	function(){
		jQuery('a.maxmin').click(
		function(){
			jQuery(this).parent().siblings('.dragbox-content').toggle();
		});

		jQuery('.column').sortable({
		connectWith: '.column',
		handle: 'h2',
		cursor: 'move',
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		opacity: 0.4,	
		stop: function(event, ui)
			{
				jQuery(ui.item).find('h2').click();
				var sortorder='';
				var itemorder=jQuery('#column2').sortable('serialize');
				var columnId=jQuery('#column2').attr('id');
				sortorder+=columnId+'='+itemorder.toString()+'&';
				//$('.column').each(function(){
				//	var itemorder=$(this).sortable('toArray');
				//	var columnId=$(this).attr('id');
				//	sortorder+=columnId+'='+itemorder.toString()+'&';
				//});
				sortorder = sortorder.substring(0, sortorder.length - 1)
				//alert(itemorder);

			}
		}).disableSelection();
	});



function getZone(id){
  var regionId = id;
            jQuery.ajax({
                    url: "../group/group_ajax.php",
                    type: 'POST',
                    cache: false,
                    data: {regionId : regionId},
                    dataType: 'html',
                    success: function(data){
                        jsonData =jQuery.parseJSON(data);
                          $("#selectedLabel_"+jsonData.zoneId).text(jsonData.zoneName).attr('selected','true');
                          $("#hiddenzone").val(jsonData.zoneId);
                      }
                });
                return false;      
}
function add_region(){
    var regionName     = $('#regionname').val();
    var regionCode     = $('#regioncode').val();
    var regionZoneId   = $('#regionZone').val();
    var regionZoneName = $('#selected_'+regionZoneId).html(); 

    if(regionName == ""){
        jQuery("#regionnamecomp").show();
            jQuery("#regionnamecomp").fadeOut(3000);    
    }
    else if(regionCode == ""){
            jQuery("#regioncodecomp").show();
            jQuery("#regioncodecomp").fadeOut(3000);    
    }
   else if(regionZoneId == ''){
            jQuery("#regionzonecomp").show();
            jQuery("#regionzonecomp").fadeOut(3000);    
    }
    else{
    var postData = {regionName:regionName, regionCode:regionCode,regionZoneId:regionZoneId,regionZoneName:regionZoneName };
       jQuery.ajax({
                    url: "../group/group_ajax.php",
                    type: 'POST',
                    cache: false,
                    data: {'addregion':postData},
                    dataType: 'json',
                    success: function(data){
                        if(data == 'success'){
                             $('#regionname').val('');
                             $('#regioncode').val('');
                             $("#regionZone option:selected").prop("selected", false)
                        jQuery("#regionnameadd").show();
                        jQuery("#regionnameadd").fadeOut(3000);
                        }      
                    }
                });
   }
}
function add_zone(){
    var zoneName    = $("#zonename").val();
    var zoneCode    = $("#zonecode").val();
    var state       = $("#state").val();

     if(zoneName == ""){
        jQuery("#zonenamecomp").show();
            jQuery("#zonenamecomp").fadeOut(3000);    
    }
    else if(zoneCode == ""){
            jQuery("#zonecodecomp").show();
            jQuery("#zonecodecomp").fadeOut(3000);    
    }
   else if(state == ''){
            jQuery("#statecomp").show();
            jQuery("#statecomp").fadeOut(3000);    
    }
    else{
    var postData    = {zoneName:zoneName, zoneCode:zoneCode, state:state};

       jQuery.ajax({
                    url : "../group/group_ajax.php",
                    type: 'POST',
                    cache: false,
                    data: {'addzone':postData},
                    dataType: 'json',
                    success: function(data){
                        if(data == 'success'){
                            $('#zonename').val('');
                            $('#zonecode').val('');
                            $("#state option:selected").prop("selected", false)
                            jQuery("#zonenameadd").show();
                            jQuery("#zonenameadd").fadeOut(3000);
                        }      
                    }
                });
   }
}
