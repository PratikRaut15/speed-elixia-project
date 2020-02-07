// JavaScript Document

function collector()
{
	
	do_submit("suf1_custom="+$("suf1_custom").value+
			   "&suf1="+$("suf1").value+
			   "&suf2_custom="+$("suf2_custom").value+
			   "&suf2="+$("suf2").value+
			   "&callextra1_custom="+$("callextra1_custom").value+
			   "&callextra1="+$("callextra1").value+
			   "&callextra2_custom="+$("callextra2_custom").value+
			    "&callextra2="+$("callextra2").value+
			   "&cliextra_custom="+$("cliextra_custom").value+
			   "&cliextra="+$("cliextra").value);	
}
function do_submit (data)
{
	
		
var params2= data;
		new Ajax.Request('ajaxpusher.php',
		{
			parameters: params2,
			onSuccess: function(transport)
			{
					var statuscheck = transport.responseText;
					if(statuscheck == "ok")
					{
					alert("Details ahve been changed");
					
					
					}
					else
					{
					               
					}
			},
			onComplete: function()
			{
				//location.reload();
			}
			
			
			
		
		
		});
		


}