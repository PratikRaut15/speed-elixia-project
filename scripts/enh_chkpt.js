// For Checkpoint
var gmapsinited = false;
var gmapsinitedsender = false;
var geocodeinited = false;
var geocodeinitedsender = false;
var map;
var mapsender;
var geocoder;
var geocodersender;
var eviction_list = [];
var counter = 2;
var markerlatlng;

// For Fence
var fcounter = 0;
var drawingManager;
var selectedShape;
//var colors = ['#000000', '#0099FF', '#AC020F'];
var colors = ['#000000'];
var selectedColor;
var colorButtons = {};
var fencename;
var latitudes = "";
var longitudes = "";

//var counter = 0;
// Style
var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];

jQuery.noConflict();
jQuery(document).ready(function () {
	var browserHeight = jQuery(window).height();
	jQuery('.post').css("padding", 0);
	jQuery('.entry').css("padding", 0);
	jQuery("#gc-topnav2").draggable();
	jQuery('#map_chkpoint').css("height", browserHeight - 250);
});

// jQuery ok

function create_map_for_modal(id) {
    jQuery('#addcheckpoint').modal('hide');
    periodic = false;
    var browserHeight = jQuery(window).height();
    var browserWidth = jQuery(window).width();
    jQuery('.post').css("padding", 0);
    jQuery('.entry').css("padding", 0);
    jQuery("#gc-topnav22").draggable();
    jQuery('#map_chkpoint').css("height", browserHeight - 250);
    jQuery('#map_chkpoint').css("width", browserWidth - 250);
    var str = jQuery("#latlong" + id).val();
			jQuery('#gc-topnav22').css("position", "absolute");
			jQuery('#gc-topnav22').css("top", "70%");
			jQuery('#gc-topnav22').css("left", "2%");
        jQuery('#gc-topnav22').css("display", "block");
        jQuery('#gc-topnav22').css("z-index", "1000");
	jQuery("#gc-topnav22").draggable();
	
    var partsOfStr = str.split(',');
    var latlng = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    var mapOptions = {
        zoom: 12,
        center: latlng,
        panControl: true,
        streetViewControl: false,
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
        },
        styles: styles
    };
    map = new google.maps.Map(document.getElementById('map_chkpoint'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    jQuery(".popup").show();
    jQuery(".overlay").show();
    jQuery('#toggler1').hide();
    showformForCreateCheckpt();
    jQuery("#chkRadField").show();
    jQuery("#locateinp").css("display", "none");
                jQuery("#chkName").show();
                jQuery("#chkptname").show();
    initmap_new(partsOfStr[0], partsOfStr[1]);
    markerlatlng = latlng;
}

function VehicleForCheckpoint() {
	var vehicleid = jQuery('#vehicleid1').val();
	if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
		var selected_name = jQuery('#vehicleid1 option:selected').text();
		
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' + vehicleid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
		jQuery('#vehicle_list1').append(div);
		jQuery(div).append(remove_image);
	}
	jQuery('#vehicleid1').val(0);
}


function VehicleForCheckpoint_byId(vehicleid, selected_name) {
	//var vehicleid = jQuery('#vehicleid1').val();
	if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
		//var selected_name = jQuery('#vehicleid1 option:selected').text();
		
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' + vehicleid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
		jQuery('#vehicle_list1').append(div);
		jQuery(div).append(remove_image);
	}
	jQuery('#vehicleno').val('');
}

function removeVehicle(vehicleid) {
	jQuery('#to_vehicle_div_' + vehicleid).remove();
}

function AddedVehicleForCheckpoint() {
	var vehicleid = jQuery('#chk_vehid').val();
	var selected_name = jQuery('#vehicleid1 option[value=' + vehicleid + ']').text();
	if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' + vehicleid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
		jQuery('#vehicle_list1').append(div);
		jQuery(div).append(remove_image);
	}
	jQuery('#vehicleid1').val(0);
}

function addallvehicleForCheckpoint() {
 jQuery("#vehicleid1 option").each(function (index, element) {
        jQuery("#vehicleid1").val(jQuery(element).val());
        VehicleForCheckpoint();
    });	
	
	
	
}

function remove_addedVehicle(vehicleid) {
	jQuery('#to_vehicle_div_' + vehicleid).remove();
}

function addcheckpointtovehicle() {
	var checkpointid = jQuery('#checkpointid').val();
	
	if (checkpointid > -1 && jQuery('#to_chkpt_div_' + checkpointid).val() == null) {
		var selected_name = jQuery('#checkpointid option:selected').text();
		
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeChkpt(checkpointid);
		};
		div.className = 'chkptbox';
		div.id = 'to_chkpt_div_' + checkpointid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="c_list_element" name="to_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
		jQuery('#chkpt_list').append(div);
		jQuery(div).append(remove_image);
	}
	jQuery('#checkpointid').val(0);
}

function addallCheckpointForVehicle() {
	
	
	 jQuery("#checkpointid option").each(function (index, element) {
        jQuery("#checkpointid").val(jQuery(element).val());
      
		var checkpointid = jQuery('#checkpointid').val();
		if (checkpointid > -1 && jQuery('#to_chkpt_div_' + checkpointid).val() == null) {
			var selected_name = jQuery(" #checkpointid option:selected").text();
			
			var div = document.createElement('div');
			var remove_image = document.createElement('img');
			remove_image.src = "../../images/boxdelete.png";
			remove_image.className = 'clickimage';
			remove_image.onclick = function () {
				removeChkpt(checkpointid);
			};
			div.className = 'chkptbox';
			div.id = 'to_chkpt_div_' + checkpointid;
			div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="c_list_element" name="to_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
			jQuery('#chkpt_list').append(div);
			jQuery(div).append(remove_image);
		}
	
    });
	
	
	jQuery('checkpointid').val(0);
}

function removeChkpt(checkpointid) {
	jQuery('#to_chkpt_div_' + checkpointid).remove();
}


// enh checkpoint js starts here
   			var count = 1;
   			var nRows = 2;
   			
   			function addRow() {
   				
 				var tBody = document.getElementById('theBody');
 				var newRow = document.createElement('tr');
 				var col1 = document.createElement('td');
 				var col2 = document.createElement('td');
 				var col3 = document.createElement('td');
 				var col4 = document.createElement('td');
 				var col5 = document.createElement('td');
 				var rA = document.createElement('a');
   
 				newRow.setAttribute('id', 'n' + count);
   				
   		    	   rA.setAttribute('href', 'javascript:removeRow(\'n' + count + '\');');
 				rA.appendChild(document.createTextNode('Remove'));
                var email = document.createElement("input");
                email.type = "text";
                email.name = "email" + count;
                email.id = "email" + count;
                email.className = "email";
                email.size = "30";
                email.placeholder = "Email Id";
                var phone = document.createElement("input");
                phone.type = "text";
                phone.name = "phoneno" + count;
                phone.id = "phoneno" + count;
                phone.className = "phone";
                phone.size = "30";
                phone.placeholder = "Phone No";
 		 	   col1.appendChild(document.createTextNode('Email'));
 			    col2.appendChild(email);
 		 	   col3.appendChild(document.createTextNode('Phone no'));
 			    col4.appendChild(phone);
   				col5.appendChild(rA);
   
   				newRow.appendChild(col1);
   				newRow.appendChild(col2);
   				newRow.appendChild(col3);
   				newRow.appendChild(col4);
   				newRow.appendChild(col5);
   
   				tBody.appendChild(newRow);
   
   				count++;
   				nRows++;
   	
   			}
   
   			function removeRow(rowId) {
 				var tBody = document.getElementById('theBody');
   				
 				tBody.removeChild(document.getElementById(rowId));
   				nRows--;
   			}
function chksubmit()
{
    var email=jQuery("#email").val();
    var phone=jQuery("#phoneno").val();
    var vehiclearray = new Array();
    jQuery(".recipientbox").each(function() {
       vehiclearray.push(this.id);
    });
    var chkptarray = new Array();
    jQuery(".recipientbox").each(function() {
       chkptarray.push(this.id);
    });
    if(vehiclearray == ''){
                                    jQuery("#vehiclearray").show();
                                    jQuery("#vehiclearray").fadeOut(4000);
    }
    else if(chkptarray == ''){
                                    jQuery("#chkptarray").show();
                                    jQuery("#chkptarray").fadeOut(4000);
    }
    else
    {
        if(email != ''){
                        var atpos=email.indexOf("@");
                        var dotpos=email.lastIndexOf(".");
                        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
                        {
                        jQuery("#emailerror").show();
                        jQuery("#emailerror").fadeOut(3000);
                        }
                        else if(phone != ''){
                            phone = phone.replace(/[^0-9]/g, '');
                            if(phone.length != 10) { 
                            jQuery("#smserror").show();
                            jQuery("#smserror").fadeOut(3000);
                            }
                            else{
                            submitecodedata();
                            }
                        }
                        else{
                        submitecodedata();
                        }
            }
        else if(phone != ''){
            phone = phone.replace(/[^0-9]/g, '');
                        if(phone.length != 10) { 
                        jQuery("#smserror").show();
                        jQuery("#smserror").fadeOut(3000);
                        }
                        else{
                        submitecodedata();
                        }
            }
        else{
        submitecodedata();
        }
    }
//    else
//    {
//         jQuery("#error").show();
//        jQuery("#error").fadeOut(3000);
//    }
}

function submitecodedata()
{
    var emailarray = new Array();
    var emailstatus = 'true';
    var phonestatus = 'true';
    var checkpointid = jQuery("#checkpointid").val();
    var vehiclearray = new Array();
    jQuery(".recipientbox").each(function() {
       vehiclearray.push(this.id);
    });
    jQuery(".email").each(function() {
        var email = this.value;
        if(email != ''){
                        var atpos=email.indexOf("@");
                        var dotpos=email.lastIndexOf(".");
                        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
                        {
                        jQuery("#emailerror").show();
                        jQuery("#emailerror").fadeOut(3000);
                        emailstatus = 'false';
                        }
                        else{
                        emailarray.push(this.value);
                        }
            }
    });
    var phonearray = new Array();
    jQuery(".phone").each(function() {
        var phone = this.value;
        if(phone != ''){
                            phone = phone.replace(/[^0-9]/g, '');
                            if(phone.length != 10) { 
                            jQuery("#smserror").show();
                            jQuery("#smserror").fadeOut(3000);
                            phonestatus = 'false';
                            }
                            else{
                            phonearray.push(this.value);
                            }
                        }
    });
    if(checkpointid == ''){
                                    jQuery("#chkptarray").show();
                                    jQuery("#chkptarray").fadeOut(4000);
    }
    else if(vehiclearray == ''){
                                    jQuery("#vehiclearray").show();
                                    jQuery("#vehiclearray").fadeOut(4000);
    }
    else if(emailstatus == 'true' && phonestatus == 'true'){
    var data = jQuery('#chkptform').serialize();
    var datastring = data+'&emailarray='+emailarray+'&phonearray='+phonearray;
    jQuery.ajax({
                type: "POST",
                url: "route.php",
                data: datastring,
                cache: false,
                success: function(html)
                { 
                    window.location.href = 'enh_checkpoint.php?id=2';
                }
        });
    }
         
}

function editcheckpoints()
{
    var type = jQuery("#type").val();
    var comdet = jQuery("#comdet").val();
    var comdetstatus = 'true';
    if(type == 0){
        if(comdet != ''){
                        var atpos=comdet.indexOf("@");
                        var dotpos=comdet.lastIndexOf(".");
                        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=comdet.length)
                        {
                        jQuery("#emailerror").show();
                        jQuery("#emailerror").fadeOut(3000);
                        comdetstatus = 'false';
                        }
            }
    }
    else if(type == 1)
    {
        if(comdet != ''){
                            comdet = comdet.replace(/[^0-9]/g, '');
                            if(comdet.length != 10) { 
                            jQuery("#smserror").show();
                            jQuery("#smserror").fadeOut(3000);
                            comdetstatus = 'false';
                            }
                        }        
    }
    if(comdetstatus == 'true')
    {
        var data = jQuery('#chkptform').serialize();
        jQuery.ajax({
                    type: "POST",
                    url: "route.php",
                    data: data,
                    cache: false,
                    success: function(html)
                    { 
                        window.location.href = 'enh_checkpoint.php?id=2';
                    }
            });                 
    }
}
