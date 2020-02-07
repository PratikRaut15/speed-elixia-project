function sayt_client() {
    var q = jQuery("#clientno").val();
    var collection = "";
    jQuery("#sayt_list").show();
    jQuery.ajax({
        type: "GET",
        url: "ajaxpulls.php?work=18&q=" + q,
        async: true,
        cache: false,
        success: function (data) {
            jQuery("#sayt_list").html(collection);
            var json = eval('(' + data + ')');
            jQuery.each(json.result, function (key, value) {
                collection += "<li id='c_" + value.clientid + "'>" + value.phoneno + "</li>";
                jQuery('#c_' + value.clientid).live('click', client_select);
                jQuery('#sayt_list').focus();
            });

            jQuery("#sayt_list").html(collection);

            jQuery('#sayt_list').focus();
        }
    });

}

function client_select() {
    var clientid = this.id;

    clientid = clientid.split("_");

    jQuery.ajax({
        type: "GET",
        url: "ajaxpulls.php?work=19&cid=" + clientid[1],
        async: true,
        cache: false,
        success: function (data) {
            var json = eval('(' + data + ')');
            jQuery.each(json.result, function (key, value) {
                jQuery("#clientno").val(value.phoneno);
                jQuery("#sayt_list").html();
                jQuery("#sayt_list").hide();
                jQuery("#clientname").html("Client: " + value.clientname);
                jQuery("#clientid").val(value.clientid);
            });
        }
    });


}