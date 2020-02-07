var zonekey = 0;
var slotkey = 0;
var close_img = '<img class="clickimage" src="../../images/boxdelete.png">';

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

                    /*to store vehicleids in zone-slot array*/
                    var index = this.cellIndex;
                    zonekey = jQuery('#droptableid tr').first().find('th').eq(index).attr('id');
                    slotkey = jQuery(this).parent().find('td:first').attr('id');
                    var vehidVal = ui.draggable.attr('id').match(/\d+/);

                    //if(!checkIfExists(vehidVal, slotkey, zonekey)){

                    //if(checkIfExists(vehidVal, slotkey, zonekey)){

                        //jQuery(this).append("<div style='background-color:red;margin-bottom:5px;'>"+ui.draggable.text()+close_img+"</div>");


                    //}
                    //else{
                        if(vehArr[zonekey+'_'+slotkey]==undefined){
                            vehArr[zonekey+'_'+slotkey] = new Array();
                            vehArr[zonekey+'_'+slotkey].push(vehidVal);
                        }
                        else{
                            vehArr[zonekey+'_'+slotkey].push(vehidVal);
                        }
                        jQuery(this).append("<div style='background-color:#46B111;margin-bottom:5px;'>"+ui.draggable.text()+close_img+"</div>");
                    //}

                }
            }
            i++;

        });
    }
});
function checkIfExists(defvalue, slot, zonekey){
    var testVal = null;
    var total = 0;

    var userKey = zonekey+'_'+slot;
    jQuery.each(allZones, function(index, value){
        var customKey = 'z'+value+'_'+slot;

        testVal = vehArr[customKey];


        if(testVal!=undefined){
            jQuery.each(testVal, function(index2,value2){
                if(value2==defvalue[0]){
                    total++;
                }
            });

        }
    });

    if(total>0){return true;}else{return false;}
}

jQuery('.clickimage').live('click', function(){
    var rmslotkey = jQuery(this).parents().find('td:first').attr('id');
    var rmIndex = jQuery(this).parent().parent().index();
    var rmzonekey = jQuery('#droptableid tr').first().find('th').eq(rmIndex).attr('id');
    var divIndex = jQuery(this).parent().index();

    jQuery(this).parent().remove();

    if(vehArr[rmzonekey+'_'+rmslotkey]!=undefined){
        delete vehArr[rmzonekey+'_'+rmslotkey][divIndex];
    }
});

function prefillMap(){
var zoneIndex;
var zoneSlotId;
var keyval;
jQuery.each(allZones, function(index, value){
    zoneIndex = jQuery('#z'+value).index();
    jQuery.each(allAllSlot, function(indexS, valueS){
        zoneSlotId = 'z'+value+'_slot'+valueS;
        keyval = vehArr[zoneSlotId];
        if(keyval!=undefined){
            jQuery(keyval).each(function(indexLast, valueLast){
                jQuery('#slot'+valueS).parent().find('td').eq(zoneIndex).append("<div style='background-color:#46B111;margin-bottom:5px;'>"+allVehicles[valueLast[0]]['vehno']+close_img+"</div>");
            });
        }
    });
});

}


function checkRed(){
var error = true;
var checkColor;
var allDiv;
var color;
jQuery(".droppable").each(function() {
    checkColor = jQuery(this).find('div').css('background-color');
    if(checkColor!=undefined){
        allDiv = jQuery(this).find('div');
        jQuery(allDiv).each(function(){
            color = jQuery(this).css('background-color');
            if (color=="rgb(255, 0, 0)"){
                error = false;
                return false;
            }
        });
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
    alert('Please clear the issue. For eg: 1 vehicle cannot be in 2 zones of same time-slot or Same vehicle in single Zone/Slot.');return false;
}
var data = vehArr;

jQuery.ajax({
    url:"route_ajax.php",
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

