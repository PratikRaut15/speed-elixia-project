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


function sequence_print(printTitle){
    var data = jQuery('#centerDiv').html();
    var printStyle = '<style type="text/css" >.newTableSubHeader {float: left;width: 50%;}.newTableSubHeaderLeft {float: left;padding: 0 0 5px 2%;text-align: left;width: 55%;}.newTableSubHeader {float: left;width: 50%;}.newTableSubHeaderRight {float: right;padding: 0 2% 5px 0;text-align: left;width: 50%;word-wrap: break-word;}.table {margin-bottom: 18px;width: 100%;font-family: Arial,Helvetica,sans-serif;font-size: 14px;}.table th {background: none repeat scroll 0 0 #cccccc;color: #3b3b3b;}newTable, .newTable th, .newTable td {border: 1px solid;}table {background-color: transparent;border-collapse: collapse;border-spacing: 0;max-width: 100%;}</style>';
    var mywindow = window.open('', 'Print', '');
    mywindow.document.write('<html><head><title>'+printTitle+'</title>');
    mywindow.document.write(printStyle);
    mywindow.document.write('</head><body><center>');
    mywindow.document.write(data);
    mywindow.document.write('</center></body></html>');
    mywindow.print();
    mywindow.close();
}
function html2xlsSequence(){
    var vehicleid = jQuery('#vehicleid').val();
    var sid = jQuery('#slotid').val();
    var date = jQuery('#entrytime').val();
    window.open('savexls_R.php?report=orderSequence&vid='+vehicleid+'&slotid='+sid+'&date='+date,'_blank');
}