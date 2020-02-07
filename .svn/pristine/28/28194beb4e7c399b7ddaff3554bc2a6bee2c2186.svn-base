// jQuery(document).ready(function () {
//     getLedgerDetails();
// });
function addLedger() {
   
    var ledgername = jQuery("#ledgername").val();
    var gstno=jQuery("#gstno").val();
    var panno=jQuery("#panno").val();
    var g=new RegExp('[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9]{1}[Z]{1}[A-Z-0-9]{1}');
    var p=new RegExp('[A-Z]{5}[0-9]{4}[A-Z]{1}');

    var formdata = jQuery("#form_ledger").serialize();
    var dataString = "work=addLedger&" + formdata;
    if (ledgername == '') {
        var data = 'Please Enter Ledger Name';
        jQuery("#msg_ledger").show();
        jQuery("#msg_ledger").append(data);
        jQuery("#msg_ledger").fadeOut(5000);
        $("html, body").animate({ scrollTop: 0 }, 1000);
        return false;
    } 

    else if($("#panno").val()!="" && !p.test(panno)){
        alert("Please Enter Correct Format of PAN.");
        return false;
    }
     else if($('#gst_na_c').is(':not(:checked)') && $("#gstno").val()==""){
        alert("GST information is mandatory.");
        return false;
    }

   else if($('#gst_na_c').is(':not(:checked)') && !g.test(gstno))
    {
        
        alert("Please Enter Correct Format of GST");
        return false;
    }
    else {
        jQuery.ajax({
            url: "ledger_ajax.php",
            type: 'POST',
            cache: false,
            data: dataString,
            dataType: 'html',
            success: function (html) {
                if (html == 1) {
                    var data_exists = "Ledger Already Exists";
                    jQuery("#msg_ledger").html('');
                    jQuery("#msg_ledger").show();
                    jQuery("#msg_ledger").append(data_exists);
                    jQuery("#msg_ledger").fadeOut(3000);
                } else {
                    jQuery("#msg_ledger").html('');
                    jQuery("#msg_ledger").show();
                    jQuery("#msg_ledger").append(html);
                    jQuery("#msg_ledger").fadeOut(3000);
                    window.location.reload();
                }
            }
        });
    }
}

function details_ledger(data)
{
    var tags = '';
    jQuery(data).each(function (i, v) {
        tags += "<tr><td>" + v.x + "</td>";
        tags += "<td>" + v.ledgerid + "</td>";
        tags += "<td>" + v.ledgername + "</td>";
        tags += "<td>" + v.add1 + "</td>";
        tags += "<td>" + v.add2 + "</td>";
        tags += "<td>" + v.add3 + "</td>";
        tags += "<td>" + v.pan + "</td>";
        tags += "<td>" + v.gst + "</td>";
        tags += "<td>" + v.state + "</td>";
        tags += "<td>" + v.phone + "</td>";
        tags += "<td>" + v.email + "</td>";
        tags += "<td><a href='ledger_edit.php?ledid=" + v.ledgerid + "'><i class='icon-pencil'></i></a></td>";
        tags += "<td colspan = '2'><a href='ledger_ajax.php?work=deleteLedger&delledid=" + v.ledgerid + "'><i class='icon-trash'></i></a></td></tr>"
    });

    if (data.length === 0) {
        var emp = '';
        emp += "<tr>"
        emp += "<td colspan=100% style='text-align:center'>No Data Found</td>"
        emp += "</tr>"
        jQuery('#demo_viewledger').html(emp);

    }

    else {
        jQuery('#demo_viewledger').html(tags);
    }
}


