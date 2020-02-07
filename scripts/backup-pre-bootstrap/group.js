
	function checkgroup()
	{
                var grouparray=jQuery('#column2').sortable('toArray');  
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
                else
                    {
                        var params = "groupn=" + encodeURIComponent(jQuery("#groupname").val());
                    new Ajax.Request('group_ajax.php',
                    {
                        parameters: params,
                        onSuccess: function(transport)
                        {
                            var statuscheck = transport.responseText;
                            if(statuscheck == "ok")
                            {
                                submitgroup(); 
                                
                            }
                            else
                            {
                                jQuery("#samename").show();
                                jQuery("#samename").fadeOut(3000);                
                            }
                        },
                        onComplete: function()
                        {
                        }
                    });
                    }
	} 

function submitgroup()
		{
				//$(ui.item).find('h2').click();
				//var sortorder='';
				var groupname = jQuery("#groupname").val();
				var grouparray=jQuery('#column2').sortable('toArray');
				var datastring = "groupname=" + groupname + "&grouparray=" + grouparray;
                                //alert(datastring);exit;
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
                                                
function modifygroupchk()
	{
            var grouparray=jQuery('#column2').sortable('toArray');
                                    
                if(grouparray == "")
		{
			jQuery("#grouparray").show();
			jQuery("#grouparray").fadeOut(3000);                 
		}
                else
                    {
                        modifygroup(); 
                    }
	} 
        
function modifygroup()
		{
				//$(ui.item).find('h2').click();
				//var sortorder='';
				var groupid = jQuery("#groupid").val();
				var groupname = jQuery("#groupname").val();
				var grouparray=jQuery('#column2').sortable('toArray');
				var datastring = "groupid=" + groupid + "&grouparray=" + grouparray + "&groupname=" + groupname;
                                //alert(datastring);exit;
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
