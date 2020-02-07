jQuery(document).ready(function () {
    pullalltransmitter();
    gettransmitterbystatus();
});


function pullalltransmitter()
{
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {work: 'alltransmitter'
        },
        dataType: 'html',
        success: function (html) {
            jQuery("#trans_ready").html('');
            jQuery("#trans_ready").append(html);
        }
    });
    return false;
}

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

function allot_transmitter() {
    var arr_uallotted = jQuery("#uallotteamid").val();
    var data_uallotted = arr_uallotted.split('-');
    var uallotted = data_uallotted[0];
    var transid = jQuery("#alltrans").val();
    var transno_data = jQuery("#alltrans option:selected").text();
    var transno_split = transno_data.split('[');
    var transno = transno_split[0];
    var comments = jQuery("#allotcomments").val();
    if (transid == 0) {
        alert("please select transmitter");
    } else {
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {work: 'allot_transmitter',
                allottedid: uallotted,
                transid: transid,
                transno: transno,
                comments: comments
            },
            dataType: 'html',
            success: function (res) {
                jQuery("#msg_allot").show();
                jQuery("#msg_allot").append(res);
                jQuery("#myform")[0].reset();
                jQuery("#msg_allot").fadeOut(3000);
            }
        });
    }

}

function reallot_transmitter() {
    var uteamid = jQuery("#uteamid_new").val();
    var arr_uallotted = jQuery("#uteamid_new").val();
    var data_uallotted = arr_uallotted.split('-');
    var uallotted = data_uallotted[0];
    var transid = jQuery("#transallotted").val();
    var transno_data = jQuery("#transallotted option:selected").text();
    var transno_split = transno_data.split('[');
    var transno = transno_split[0];
    var comments = jQuery("#reallotcomments").val();
    if (transid == '-1') {
        alert("please select transmitter");
    } else if (uteamid == '0') {
        alert("please select Allot To");
    } else {
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {work: 'reallot_transmitter',
                allottedid: uallotted,
                transid: transid,
                transno: transno,
                comments: comments
            },
            dataType: 'html',
            success: function (res) {
                jQuery("#msg_reallot").show();
                jQuery("#msg_reallot").append(res);
                jQuery("#myformreallot")[0].reset();
                jQuery("#msg_reallot").fadeOut(3000);
            }
        });
    }

}

function gettransmitterbystatus()
{
    var status = "1, 4, 6";
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {work: 'gettransmitterbystatus'
            , status: status
        },
        dataType: 'html',
        success: function (html) {
            jQuery("#trans_testing").html('');
            jQuery("#trans_testing").append(html);
        }
    });
    return false;
}

function testing()
{
    var result = jQuery("#trantestingresult").val();
    var transid = jQuery("#trans_sendrepair").val();
    var transno_data = jQuery("#trans_sendrepair option:selected").text();
    var transno_split = transno_data.split('[');
    var transno = transno_split[0];
    var comments = jQuery("#testcomments").val();
    if (transid == 0) {
        alert("Select Transmitter");
    } else if (transid == null) {
        alert("No Transmitter");
    } else {
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {work: 'trans_testing'
                , trans_status: result
                , transid: transid
                , transno: transno
                , comments: comments
            },
            dataType: 'html',
            success: function (html) {
                jQuery("#test_td").html('');
                jQuery("#test_td").append(html);
                window.location.reload();
            }
        });
    }
}
