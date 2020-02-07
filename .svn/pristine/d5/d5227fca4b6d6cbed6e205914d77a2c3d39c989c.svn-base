jQuery(document).ready(function() {
    jQuery('#entrytime').datepicker({format: "dd-mm-yyyy",autoclose:true,});
});

function getSequence(){
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var vid = jQuery('#vehicleid').val();
    var slotid = jQuery('#slotid').val();
    var date = jQuery('#entrytime').val();
    jQuery.ajax({
        url:"seq_ajax_new.php",
        type: 'POST',
        data: "vid="+vid+"&slotid="+slotid+"&date="+date,
        success:function(result){
            jQuery("#centerDiv").html(result);
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
    });
}