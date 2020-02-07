function editAccountCust() {
    var name = jQuery("#invname").val();
    var cno = jQuery("#cid").val();
    if (name == '') {
        jQuery("#error_name").show();
        jQuery("#error_name").fadeOut(6000);
    } else {
        var data = jQuery("#edit_cust").serialize();
        var dataString = 'work=edit_acc_cust&' + data;
        jQuery.ajax({
            type: "POST",
            url: "inview_address.php",
            cache: false,
            data: dataString,
            success: function () {
                jQuery("#edit_cust_succ").show();
                jQuery("#edit_cust_succ").fadeOut(3000);
                jQuery('#edit_cust')[0].reset();
                var url = 'modifycustomer.php?cid=' + cno;
                jQuery(location).attr('href', url);
            },
            error: function () {
                jQuery("#fail_edit_cust").show();
                jQuery("#fail_edit_cust").fadeOut(6000);
            }
        });
    }
}

