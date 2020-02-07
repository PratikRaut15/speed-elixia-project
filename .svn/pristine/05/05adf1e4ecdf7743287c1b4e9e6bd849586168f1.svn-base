/*

	Global values

*/


jQuery.noConflict();
var chartWidth = '650px';

var chartHeight = '240px';





/*

	Find element's Y axis position

*/



function findPosY(obj) 

{

	var curtop = 0;

	if (obj.offsetParent) 

	{

		while (1) 

		{

			curtop+=obj.offsetTop;

			if (!obj.offsetParent) 

			{

				break;

			}

			obj=obj.offsetParent;

		}

	} 

	else if (obj.y) 

	{

		curtop+=obj.y;

	}

		

	return curtop;

}



/*

	Find element's X axis position

*/



function findPosX(obj) 

{

	var curtop = 0;

	if (obj.offsetParent) 

	{

		while (1) 

		{

			curtop+=obj.offsetLeft;

			if (!obj.offsetParent) 

			{

				break;

			}

			obj=obj.offsetParent;

		}

	} 

	else if (obj.x) 

	{

		curtop+=obj.x;

	} 

	

	return curtop;

}



/*

	Setup chart from given table and type

*/



function setChart(tableId, type, wrapper)

{

	//clear existing chart before create new one

	jQuery(wrapper).html('');



	jQuery(tableId).visualize({

		type: type,

		width: chartWidth,

		height: chartHeight,

		colors: ['#7EC421', '#9FBAD4']

	}).appendTo(wrapper);

	

	//if IE then need to add refresh event

	if (navigator.appName == "Microsoft Internet Explorer")

	{

		jQuery('.visualize').trigger('visualizeRefresh');

	}

}



/*

	Setup notification badges for shortcut

*/

function setNotifications()

{

	// Setup notification badges for shortcut

	jQuery('#shortcut_notifications span').each(function() {

		if(jQuery(this).attr('rel') != '')

		{

			target = jQuery(this).attr('rel');

			

			if(jQuery('#' +target).length > 0)

			{

				var Ypos = findPosY(document.getElementById(target));

				var Xpos = findPosX(document.getElementById(target));

				

				jQuery(this).css('top', Ypos-24 +'px');

				jQuery(this).css('left', Xpos+60 +'px');

			}

		}

	});

	jQuery('#shortcut_notifications').css('display', 'block');

}



jQuery(function(){ 

	

	// Preload images

	jQuery.preloadCssImages();







    // Find all the input elements with title attributes and add hint to it

    jQuery('input[title!=""]').hint();

    

    

    

    // Setup WYSIWYG editor

    jQuery('#wysiwyg').wysiwyg({

    	css : "css/wysiwyg.css"

    });
	jQuery('.wysiwyg').wysiwyg({

    	css : "css/wysiwyg.css"

    });
    

    

    

    // Setup slider menu (left panel)

    jQuery('#main_menu').accordion({

			collapsible: true,

			autoHeight: false

	});

	

	

	// Setup show and hide left panel

	jQuery('#hide_menu').click(function(){

		jQuery('#left_menu').hide();

		jQuery('#show_menu').show();

		jQuery('body').addClass('nobg');

		jQuery('#content').css('marginLeft', 30);

		jQuery('#wysiwyg').css('width', '97%');

		setNotifications();

	});

	

	jQuery('#show_menu').click(function(){

		jQuery('#left_menu').show();

		jQuery(this).hide();

		jQuery('body').removeClass('nobg');

		jQuery('#content').css('marginLeft', 240);

		jQuery('#wysiwyg').css('width', '97%');

		setNotifications();

	});

	

	

	// Setup click to hide to all alert boxes

	jQuery('.alert_warning').click(function(){

		jQuery(this).fadeOut('fast');

	});

	

	jQuery('.alert_info').click(function(){

		jQuery(this).fadeOut('fast');

	});

	

	jQuery('.alert_success').click(function(){

		jQuery(this).fadeOut('fast');

	});

	

	jQuery('.alert_error').click(function(){

		jQuery(this).fadeOut('fast');

	});

	

	

	

	

	// Setup modal window for all photos

	jQuery('.media_photos li a[rel=slide]').fancybox({

		padding: 0, 

		titlePosition: 'outside', 

		overlayColor: '#333333', 

		overlayOpacity: .2

	});

	

	

	

	

	// Setup charts example	

	

	// Chart bar type

	jQuery('#chart_bar').click(function(){

		setChart('table#graph_data', 'bar', '#chart_wrapper');

		

		//switch menu

		jQuery(this).parent().parent().find('td input').removeClass('active');

		jQuery(this).addClass('active');

	});

	

	

	// Chart area type

	jQuery('#chart_area').click(function(){

		setChart('table#graph_data', 'area', '#chart_wrapper');

		

		//switch menu

		jQuery(this).parent().parent().find('td input').removeClass('active');

		jQuery(this).addClass('active');

	});

	

	

	// Chart pie type

	jQuery('#chart_pie').click(function(){

		setChart('table#graph_data', 'pie', '#chart_wrapper');

		

		//switch menu

		jQuery(this).parent().parent().find('td input').removeClass('active');

		jQuery

		(this).addClass('active');

	});

	

	

	// Chart line type

	jQuery('#chart_line').click(function(){

		setChart('table#graph_data', 'line', '#chart_wrapper');

		

		//switch menu

		jQuery(this).parent().parent().find('td input').removeClass('active');

		jQuery(this).addClass('active');

	});

	

	

	

	//make table editable, refresh charts on blur	

	jQuery(function(){	

		jQuery('table#graph_data td')

			.click(function(){

				if( !jQuery(this).is('.input')  && jQuery(this).attr('class') != 'no_input hover'){

					jQuery(this).addClass('input')

						.html('<input type="text" value="'+ jQuery(this).text() +'" size="4" />')

						.find('input').focus()

						.blur(function(){

							//remove td class, remove input

							jQuery(this).parent().removeClass('input').html(jQuery(this).val() || 0);

							//update charts	

							jQuery('.visualize').trigger('visualizeRefresh');

						});					

				}

			})

			.hover(function(){ jQuery(this).addClass('hover'); },function(){ jQuery(this).removeClass('hover'); });

	});

	

	

	

	// Setup left panel calendar

	jQuery("#calendar").datepicker({

		nextText: '&raquo;',

		prevText: '&laquo;'

	});

	

	// Setup datepicker input

	jQuery(".datepicker").datepicker({

		nextText: '&raquo;',

		prevText: '&laquo;',

		showAnim: 'slideDown',

		dateFormat: 'yy-mm-dd',

		changeMonth: true,

		changeYear: true

	});

	jQuery(".datepicker1").datepicker({

		nextText: '&raquo;',

		prevText: '&laquo;',

		showAnim: 'slideDown',

		dateFormat: 'yy-mm-dd',

		changeMonth: true,

		changeYear: true

	});

	jQuery(".datepicker2").datepicker({

		nextText: '&raquo;',

		prevText: '&laquo;',

		showAnim: 'slideDown',

		dateFormat: 'yy-mm-dd',

		changeMonth: true,

		changeYear: true

	});
		jQuery(".datepicker3").datepicker({

		nextText: '&raquo;',

		prevText: '&laquo;',

		showAnim: 'slideDown',

		dateFormat: 'yy-mm-dd',

		changeMonth: true,

		changeYear: true

	});

	jQuery(".datepicker4").datepicker({

		nextText: '&raquo;',

		prevText: '&laquo;',

		showAnim: 'slideDown',

		dateFormat: 'yy-mm-dd',

		changeMonth: true,

		changeYear: true

	});
	jQuery(".datepicker_dis").datepicker({

		nextText: '&raquo;',

		prevText: '&laquo;',

		showAnim: 'slideDown',

		dateFormat: 'yy-mm-dd',

		changeMonth: true,
		onSelect: function() {
			if(jQuery("#d_act").val()=="edit"){
				if(jQuery("#modifydate").is(':checked')==true)
				{
            	dis_check();
				}
			}else{
				dis_check();
				}
			
        },

		changeYear: true

	});
	



	

	

	// Setup minimize and maximize window

	jQuery('.onecolumn .header span').click(function(){

		if(jQuery(this).parent().parent().children('.content').css('display') == 'block')

		{

			jQuery(this).css('cursor', 's-resize');

		}

		else

		{

			jQuery(this).css('cursor', 'n-resize');

		}

		

		jQuery(this).parent().parent().children('.content').slideToggle('fast');

	});

	

	jQuery('.twocolumn .header span').click(function(){

		if(jQuery(this).parent().parent().children('.content').css('display') == 'block')

		{

			jQuery(this).css('cursor', 's-resize');

		}

		else

		{

			jQuery(this).css('cursor', 'n-resize');

		}

		

		jQuery(this).parent().parent().children('.content').slideToggle('fast');

	});

	

	jQuery('.threecolumn .header span').click(function(){

		if(jQuery(this).parent().parent().children('.content').css('display') == 'block')

		{

			jQuery(this).css('cursor', 's-resize');

		}

		else

		{

			jQuery(this).css('cursor', 'n-resize');

		}

		

		jQuery(this).parent().children('.content').slideToggle('fast');

	});

	

	

	

	// Check or uncheck all checkboxes

	jQuery('#check_all').click(function(){

		if(jQuery(this).is(':checked'))

		{

			jQuery('form#form_data input:checkbox').attr('checked', true);

		}

		else

		{

			jQuery('form#form_data input:checkbox').attr('checked', false);

		}

	});

	

	

	

	// Setup notification badges for shortcut

	setNotifications();

	

	

	

	// Setup modal window link

	/*jQuery('#shortcut li a').fancybox({

		padding: 0, 

		titleShow: false, 

		overlayColor: '#333333', 

		overlayOpacity: .5

	});*/

	

	// Add tooltip to shortcut

	/*jQuery('#shortcut li a').tipsy({gravity: 's'});*/

	

	/*jQuery('#btn_modal').fancybox({

		padding: 0, 

		titleShow: false, 

		overlayColor: '#333333', 

		overlayOpacity: .5,

		href: 'Addnews.php'

	});*/

	

	jQuery('#popupwindo a').fancybox({

		padding: 0, 

		titleShow: false, 

		overlayColor: '#333333', 

		overlayOpacity: .5

	});

	

	// Add tooltip to shortcut

	jQuery('#popupwindo a').tipsy({gravity: 's'});

	

	jQuery('.ajaxpopup').fancybox({

		padding: 0, 

		titleShow: false, 

		overlayColor: '#333333', 

		overlayOpacity: .5

			});

	jQuery('.ajaxpopup').tipsy({gravity: 's'});

	

	// Setup tab contents

	

	// tab 1

	jQuery('#tab1').click(function(){

		//switch menu

		jQuery(this).parent().parent().find('td input').removeClass('active');

		jQuery(this).addClass('active');

		

		//show tab1 content

		jQuery('.tab_content').addClass('hide');

		jQuery('#tab1_content').removeClass('hide');

	});

	

	

	// tab 2

	jQuery("#tab2").click(function(){
								 
		//switch menu

		jQuery(this).parent().parent().find('td input').removeClass('active');

		jQuery(this).addClass('active');
	
		

		//show tab2 content

		jQuery('.tab_content').addClass('hide');

		jQuery('#tab2_content').removeClass('hide');

	});

	

	

	// tab 3

	jQuery('#tab3').click(function(){

		//switch menu

		jQuery(this).parent().parent().find('td input').removeClass('active');

		jQuery(this).addClass('active');

		

		//show tab3 content

		jQuery('.tab_content').addClass('hide');

		jQuery('#tab3_content').removeClass('hide');

	});

	

	

	

	// Add tooltip to edit and delete button

	jQuery('.help').tipsy({gravity: 's'});

	

	

	// Setup sortable to threecolumn div

	jQuery("#threecolumn").sortable({

		opacity: 0.6,

		connectWith: '.threecolumn_each',

		items: 'div.threecolumn_each'

	});

});



jQuery(document).ready(function() {

	//need to set timeout 0.5 sec for cross browser rendering

	setTimeout('setChart(\'table#graph_data\', \'bar\', \'#chart_wrapper\')', 500);

	

	//Add ability to click link if href is not empty

	jQuery('#main_menu').find('li a').click(function(){

		if(jQuery(this).attr('href').length > 0)

		{

			location.href = jQuery(this).attr('href');

		}

	});

});







function ClickToPrint(id) {

//alert(ctrl);

var DocumentContainer = document.getElementById(id);

//alert(DocumentContainer);

var WindowObject = window.open('', "TrackHistoryData", "width=420,height=225,top=250,left=345,toolbars=no,scrollbars=no,status=no,resizable=no");

//alert(ctrl);

//alert(DocumentContainer);

WindowObject.document.write("<html><head><title>Report</title><link href=\"css/blue/screen.css\" rel=\"stylesheet\" type=\"text/css\" /><link href=\"css/tipsy.css\" rel=\"stylesheet\" type=\"text/css\" /><link href=\"js/visualize/visualize.css\" rel=\"stylesheet\" type=\"text/css\" /><link href=\"js/jwysiwyg/jquery.wysiwyg.css\" rel=\"stylesheet\" type=\"text/css\" /><script type=\"text/javascript\" src=\"js/jquery.js\"></script><script type=\"text/javascript\" src=\"js/jquery-ui.js\"></script><script type=\"text/javascript\" src=\"js/hint.js\"></script><script type=\"text/javascript\" src=\"js/visualize/jquery.visualize.js\"></script><script type=\"text/javascript\" src=\"js/jwysiwyg/jquery.wysiwyg.js\"></script><script type=\"text/javascript\" src=\"js/jquery.tipsy.js\"></script><script type=\"text/javascript\" src=\"js/custom_blue.js\"></script><script type=\"text/javascript\" src=\"js/jquery.img.preload.js\"></script></head>");

WindowObject.document.write(DocumentContainer.innerHTML);

WindowObject.document.write('</body></html>');

//alert(ctrl);

WindowObject.document.close();

WindowObject.focus();

WindowObject.print();

WindowObject.close();

} ;


var  timestamp =null;
var timecount=null;
var fins=0;
var msgid=[];
var orderflag=0;
function async_notifier(){

    jQuery.ajax({
        type:"GET",
        url:"ajaxpulls.php?work=6&timestamp="+timestamp,
        async:true,
        cache:false,
        success:function(data){
            var json =eval('('+data+')');
           timestamp=json.timestamp;
           if(json.msg==null){
           jQuery("#n_count").html("<span id='mps'>"+json.count+"</span>");
               
           }else
               {
                 jQuery("#n_count").html("<span id='mps'>"+json.count+"</span>");
						if(json.msg!=null ){
						
							// addition of notifications 
									jQuery.each( json.msg, function( key, value ) {
																	
											var htmlstr=jQuery("#notif-body").html();
											if(jQuery("#trn_"+value.id).length==0){
												msgid.push(value.id);
												var payload="<tr id='trn_"+value.id+"'><td id='td_"+value.id+"' class='td-notif'>"+value.msg+"</td></tr>"
												
												if(orderflag==0){
													
												jQuery("#notif-body").append(jQuery(payload).hide('fast').fadeIn('15000'));
												}else{
													
													jQuery("#notif-body").prepend(jQuery(payload).hide('fast').fadeIn('15000'));
													}
											}
									});
									
									orderflag++;
									
							// deletion of  notifications
							
									if(msgid.length>15){
										var k;
											for(k=msgid.length;msgid.length>=15;k--){
												jQuery("#trn_"+msgid[k]).remove();
												msgid.splice(k, 1);
											}
									}
						
						}
					
						if(json.panic!="null"){
							
							jQuery("#panic").fadeIn(500);
							jQuery("#panic_ul").html("");
									jQuery.each( json.panic, function( key, value ) {
																	 
										jQuery("#panic_ul").append("<li>"+value.msg+value.msg_time+"</li>");
									});
						}else{
							
							 jQuery("#panic").fadeOut();
							}
							
						
				}
           
          
            
                   
                    setTimeout('async_notifier()',20000);
        },
        error:function(XMLHttpRequest,testStatus,errorThrown){
            
                    setTimeout('async_notifier()',15000);
        
       }
    });


    
}


jQuery(document).ready(function() {
async_notifier();


});


function delete_panic()
{
 jQuery("#panic").fadeOut();
jQuery.ajax({
        type:"GET",
        url:"ajaxpulls.php?work=16",
        async:true,
        cache:false,
        success:function(data){
       
        },
        error:function(XMLHttpRequest,testStatus,errorThrown){
            
         
        
       }
    });
}

function notify(){

jQuery.ajax({
        type:"GET",
        url:"ajaxpulls.php?work=8",
        async:true,
        cache:false,
        success:function(data){
         window.location = "shownotifications.php" 
        },
        error:function(XMLHttpRequest,testStatus,errorThrown){
            
         
        
       }
    });




}

function dashboard_update(){

jQuery.ajax({
        type:"GET",
        url:"ajaxpulls.php?work=11",
        async:true,
        cache:false,
        success:function(data){
         jQuery("#dashref").html(data);
		 
        },
        error:function(XMLHttpRequest,testStatus,errorThrown){
            
         
        
       }
    });
jQuery.ajax({
        type:"GET",
        url:"ajaxpulls.php?work=12",
        async:true,
        cache:false,
        success:function(data){
         jQuery("#headerref").html(data);
		 
        },
        error:function(XMLHttpRequest,testStatus,errorThrown){
            
         
        
       }
    });
jQuery.ajax({
        type:"GET",
        url:"ajaxpulls.php?work=13",
        async:true,
        cache:false,
        success:function(data){
         jQuery("#serviceerref").html(data);
		 
        },
        error:function(XMLHttpRequest,testStatus,errorThrown){
            
         
        
       }
    });
var d = new Date();

jQuery("#updated_at").html(d.toLocaleString());
setTimeout('dashboard_update()',20000);
}














