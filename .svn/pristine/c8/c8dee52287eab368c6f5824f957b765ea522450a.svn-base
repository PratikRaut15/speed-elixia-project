/*Date; 1st nov 2014, ak added*/
function assign_values_to_inputs(location,lat,long, ak_text){
    jQuery('#check_').html('');
    jQuery("#success").hide();
    jQuery("#before_success").show();
    jQuery("#cname_").val('');
    jQuery('#cadd_').val(location);
    jQuery('#latlong').val(lat+', '+long);
    jQuery('#lat_').val(lat);
    jQuery('#long_').val(long);
    if(ak_text!=undefined){
    jQuery('#addedSuccessA').val(ak_text);
    }
    var deviceid = jQuery('#vehicleid').val();
    jQuery('#getdevice_').val(deviceid);
    create_map_for_modal_report('');
}

function map_interface(location,lat,long,deviceid){
    jQuery('#check_').html('');
    jQuery("#success").hide();
    jQuery("#before_success").show();
    jQuery("#cname_").val('');
    jQuery('#cadd_').val(location);
    jQuery('#latlong').val(lat+', '+long);
    jQuery('#lat_').val(lat);
    jQuery('#long_').val(long);
    jQuery('#getdevice_').val(deviceid);
    jQuery("#customMapButton").trigger('click');
    create_map_for_modal_report('');
}

/*dt: 25th oct 2014, ak added below func for showing marker*/
    function initmap_new_for_report(lat, lng, id) {
        var edited_id = '';//"_"+id;
    var latlng = new google.maps.LatLng(lat, lng);
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });

    var mapOptions = {
        zoom: 15,
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
    map = new google.maps.Map(document.getElementById('map_chkpoint'+edited_id), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    
    var marker = new MarkerWithLabel({
        position: latlng,
        map: map,
        labelAnchor: new google.maps.Point(9, 45),
    });
    var circle = new google.maps.Circle({
        map: map,
        radius: 1000,
        fillColor: '#000000',
        strokeColor: '#000000',
        strokeweight: 1
    });
    circle.bindTo('center', marker, 'position');
}

function create_map_for_modal_report(id) {
    var edited_id = '';//'_'+id;
    jQuery('.post').css("padding", 0);
    jQuery('.entry').css("padding", 0);
    jQuery('#map_chkpoint'+edited_id).css("height", 250);
    jQuery('#map_chkpoint'+edited_id).css("width", 750);
    
    var str = jQuery("#latlong" + id).val();
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
    map = new google.maps.Map(document.getElementById('map_chkpoint'+edited_id), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    
    jQuery(".popup").show();
    jQuery(".popup").css({"opacity":"1","visibility":"visible","top":"75%"});
    jQuery('.close1').click(function(){ jQuery(".popup").hide();});
    jQuery('.close').click(function(){ jQuery(".popup").hide(); });
    initmap_new_for_report(partsOfStr[0], partsOfStr[1],id);
}
    
    
    function create_map_for_modal_report_onlymap(lat,longi) {
    var edited_id = '';//'_'+id;
    jQuery('.post').css("padding", 0);
    jQuery('.entry').css("padding", 0);
   
    //jQuery('#map_chkpoint'+edited_id).css("top", -150);
    jQuery('#map_chkpoint'+edited_id).css("height", 450);
    jQuery('#map_chkpoint'+edited_id).css("width", 750);
    
    
    var latlng = new google.maps.LatLng(lat, longi);
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
    map = new google.maps.Map(document.getElementById('map_chkpoint'+edited_id), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    
    jQuery(".popup").show();
    jQuery(".popup").css({"opacity":"1","visibility":"visible","top":"40%"});
    jQuery('.close1').click(function(){ jQuery(".popup").hide();});
    jQuery('.close').click(function(){ jQuery(".popup").hide(); });
    initmap_new_for_report(lat, longi,'');
}
/*ak changes ends*/