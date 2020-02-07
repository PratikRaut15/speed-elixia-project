var zonekey = 0;
var slotkey = 0;

if(fillTable==true){
    prefillMap();
}
jQuery('body').click(function(){
    jQuery('#ajxStatus').html('');
});

jQuery(".draggable" ).draggable({
    helper: "clone",
});

jQuery(".droppable" ).droppable({
    hoverClass: "activeTd", 
    drop: function( event, ui ) {
        var i=0;
        jQuery('td').hover(function(){
            if(i==0){
                if(this.id!='submitTd'){
                    var close_img = '<img class="clickimage" src="../../images/boxdelete.png">';
                    jQuery(this).html(ui.draggable.text()+close_img);
                    jQuery(this).css("background-color", "#46B111");
                    
                    /*to store vehicleids in zone-slot array*/
                    var index = this.cellIndex;
                    zonekey = jQuery('#droptableid tr').first().find('th').eq(index).attr('id');
                    slotkey = jQuery(this).parent().find('td:first').attr('id');
                    var vehidVal = ui.draggable.attr('id').match(/\d+/);
                    
                    if(!checkIfExists(vehidVal, slotkey, zonekey)){
                        vehArr[zonekey+'_'+slotkey] = vehidVal;
                    }
                    else{
                        jQuery(this).css("background-color", "red");
                        //jQuery(this).removeAttr('style');
                        //jQuery(this).html('');
                    }
                    //console.log(vehArr);
                    
                }
            }
            i++;
        });
    }
});
jQuery('.clickimage').live('click', function(){
    var rmslotkey = jQuery(this).parents().find('td:first').attr('id')
    var rmIndex = jQuery(this).parent().index();
    var rmzonekey = jQuery('#droptableid tr').first().find('th').eq(rmIndex).attr('id');
    
    jQuery(this).parent().removeAttr('style');
    jQuery(this).parent().html('');
    
    delete vehArr[rmzonekey+'_'+rmslotkey];
});

function prefillMap(){
var zoneIndex;
var zoneSlotId;
var keyval;
var htmlval = '<img class="clickimage" src="../../images/boxdelete.png">';
jQuery.each(allZones, function(index, value){
    zoneIndex = jQuery('#z'+value).index();
    jQuery.each(allAllSlot, function(indexS, valueS){
        zoneSlotId = 'z'+value+'_slot'+valueS;
        keyval = vehArr[zoneSlotId];
        if(keyval!=undefined){
            jQuery('#slot'+valueS).parent().find('td').eq(zoneIndex).html(allVehicles[keyval]['vehno']+htmlval);
            jQuery('#slot'+valueS).parent().find('td').eq(zoneIndex).css("background-color", "#46B111");
        }
    });
});
    
}

function checkIfExists(defvalue, slot, zonekey){
    var testVal = null;
    var total = 0;
    jQuery.each(allZones, function(index, value){
        var customKey = 'z'+value+'_'+slot;
        var userKey = zonekey+'_'+slot;
        testVal = vehArr[customKey];
        if(testVal!=undefined){
            //console.log(testVal[0]+'=='+defvalue[0]+'====custom: '+customKey+'--'+userKey);
            if(testVal[0]==defvalue[0] && customKey!=userKey){
                total++;
            }
        }
    });
    if(total>0){return true;}else{return false;}
}

function checkRed(){
var error = true;
jQuery(".droppable").each(function() {
    color = jQuery(this).css('background-color');
    if (color=="rgb(255, 0, 0)"){
      error = false;
      return false;
    }
});
return error;
}

function map_all(){

if(jQuery.isEmptyObject(vehArr)){
    alert('No vehicles assigned.');return false;
}
/*check if red color exists*/
if(!checkRed()){
    alert('Please clear the issue. For eg: 1 vehicle cannot be in 2 zones of same time-slot.');return false;
}
var data = vehArr;

jQuery.ajax({
    url:"assign_ajax.php",
    type: 'POST',
    data: {'vehmap':data, 'mapDate':defDate},
    success:function(result){
        jQuery('#ajxStatus').html(result);
        //jQuery("#centerDiv").html(result);
    },
    complete: function(){
        //jQuery('#pageloaddiv').hide();
    } 
});
}

