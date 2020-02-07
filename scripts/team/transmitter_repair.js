jQuery(document).ready(function () {
    gettransmitterinrepair();
});

function gettransmitterbyteam() {
    var uteamid = $("#uteamid").val();
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {work: 'transmitter_byteamid',
            allottedid: uteamid
        },
        dataType: 'html',
        success: function (html) {
            jQuery("#trans_td").html('');
            jQuery("#trans_td").append(html);
        }
    });
}

function gettransmitterinrepair() {
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {work: 'inRepairtransmitter'
        },
        dataType: 'html',
        success: function (html) {
            jQuery("#transrepair_td").html('');
            jQuery("#transrepair_td").append(html);
        }
    });
}