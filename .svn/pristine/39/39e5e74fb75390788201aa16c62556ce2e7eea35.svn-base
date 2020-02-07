jQuery(document).ready(function () {
    Calendar.setup(
            {
                inputField: "podate", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger10" // ID of the button
            });

    Calendar.setup(
            {
                inputField: "poexp", // ID of the input field
                ifFormat: "%d-%m-%Y", // the date format
                button: "trigger11" // ID of the button
            });
});

function editAccountpo(){
    var pono = jQuery("#po_no").val();
    var cno = jQuery("#cid").val();
    if (pono == '') {
        jQuery("#error_pono").show();
        jQuery("#error_pono").fadeOut(6000);
    } else {
        var data = jQuery("#edit_po").serialize();
        var dataString = 'work=edit_po&' + data;
        jQuery.ajax({
            type: "POST",
            url: "po_ajax.php",
            cache: false,
            data: dataString,
            success: function () {
                jQuery("#edit_po_succ").show();
                jQuery("#edit_po_succ").fadeOut(3000);
                jQuery('#edit_po')[0].reset();
                var url = 'modifycustomer.php?cid=' + cno;
                jQuery(location).attr('href', url);
            },
            error: function () {
                jQuery("#edit_add_po").show();
                jQuery("#edit_add_po").fadeOut(6000);
            }
        });
    }
}

