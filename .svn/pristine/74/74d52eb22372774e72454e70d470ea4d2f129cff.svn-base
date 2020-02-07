jQuery(document).ready(function () {
    getUnmapveh();
    var cid = jQuery("#cust_no").val();
    jQuery("#vehicleno").autocomplete({
        source: "autocomplete_team.php?action=invoice_unmapveh&custno=" + cid,
        minLength: 1,
        select: function (event, ui) {
            jQuery('#vehicleid').val(ui.item.vehicleid);
            AssignVehicle(ui.item.value, ui.item.vehicleid);
            /*clear selected value */
            jQuery(this).val("");
            return false;
        }

    });
});

function getUnmapveh() {
    var cid = jQuery("#cust_no").val();
    //to fetch vehicles
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {work: "unmapveh",
            cust_grp: cid
        },
        success: function (result) {
            var DATA = jQuery.parseJSON(result);
            //console.log(data);
            vehicle_details(DATA);
        }
    });
}

function Invcust_details(data)
{
    var detail = '';
    detail += "<option value=" + 0 + ">SelectOption</option>"
    jQuery(data).each(function (i, v) {
        detail += "<option value=" + v.invid + ">" + v.invcust + "</option>";

    });
    jQuery("#ciname").html(detail);
}

function vehicle_details(DATA)
{
    var vdetail = '';
    vdetail += "<option value=" + 0 + ">SelectOption</option>"
    jQuery(DATA).each(function (i, v) {
        vdetail += "<option value=" + v.vehicleid + ">" + v.vehicleno + "</option>";

    });
    jQuery("#unmapveh").html(vdetail);
}

function AssignVehicle(selected_name, vehicleid) {
    //var vehicleid = jQuery('#unmapveh').val();
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
        //var selected_name = jQuery("#unmapveh option:selected").text();
        //var selected_name = jQuery("#vehicleno").val();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function () {
            removeVehicle(vehicleid);
        };
        div.className = 'recipientbox';
        div.id = 'to_vehicle_div_' + vehicleid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
        jQuery("#vehicle_list").append(div);
        jQuery(div).append(remove_image);
        /* calculate total count*/
        var mapveh = jQuery("#maphiddencount").val();
        var totveh = jQuery("#tothiddencount").val();
//        var txt = 'Vehicles Added = ' + (parseInt(mapveh) + 1);
//        jQuery("#addedvehcount").html(txt);
        if (totveh == '') {
            jQuery("#maphiddencount").val(parseInt(mapveh) + 1);
            var totalvehtxt = 'Total Vehicle Count=' + jQuery("#maphiddencount").val();
            jQuery("#totalvehcount").html(totalvehtxt);
            jQuery("#tothiddencount").val(jQuery("#maphiddencount").val());
        } else {
            var totalvehtxt = 'Total Vehicle Count=' + (parseInt(totveh) + 1);
            jQuery("#totalvehcount").html(totalvehtxt);
            jQuery("#maphiddencount").val(parseInt(mapveh) + 1);
            jQuery("#tothiddencount").val(parseInt(totveh) + 1);
        }
    }
    //jQuery("#vno").val('');
}
function getMappedveh()
{
    var html_data = '';
    var cid = jQuery("#cust_no").val();
    var ledgerid = jQuery("#ledger").val();
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {work: "mappedveh",
            cust_no: cid,
            ledgerid: ledgerid
        },
        success: function (res) {
            var map = jQuery.parseJSON(res);
            //console.log(map);
            if (jQuery("#vehicle_list").html().length > 0) {
                jQuery("#vehicle_list").html("");
                jQuery("#ismapveh").val(1);
            }
            if (map.length == '0') {
                jQuery("#ismapveh").val(0);
                //jQuery("#addallveh").attr('disabled', false);
            } else {
                //jQuery("#addallveh").attr('disabled', true);
                jQuery("#ismapveh").val(1);
            }
            printMapvehicle(map);
            html_data = 'Allotted Vehicle Count =' + map.length;
            jQuery("#maphiddencount").val(map.length);
            jQuery("#mappedvehcount").html(html_data);
        }
    });
}

function printMapvehicle(map)
{
    var print = '';
    jQuery(map).each(function (i, v) {
        if (v.vehicleid > -1 && jQuery('#to_vehicle_div_' + v.vehicleid).val() == null) {
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = '../../images/boxdelete.png';
            remove_image.className = 'clickimage';
            remove_image.onclick = function () {
                removeVehicle(v.vehicleid);
            };
            div.className = 'recipientbox';
            div.id = 'to_vehicle_div_' + v.vehicleid;
            div.innerHTML = "<span>" + v.vehicleno + "</span><input type='hidden' class='v_list_element' name='to_vehicle_" + v.vehicleid + "' value= '" + v.vehicleid + "'/>";
            jQuery("#vehicle_list").append(div);
            jQuery(div).append(remove_image);

        }
    });
}
function removeVehicle(vehicleid) {
    var mapveh = jQuery("#maphiddencount").val();
    var totveh = jQuery("#tothiddencount").val();
    jQuery('#to_vehicle_div_' + vehicleid).remove();
//    var txt = 'Vehicles Added = ' + (parseInt(mapveh) - 1);
//    jQuery("#addedvehcount").html(txt);
    var totalvehtxt = 'Total Vehicle Count=' + (parseInt(totveh) - 1);
    jQuery("#totalvehcount").html(totalvehtxt);
    jQuery("#maphiddencount").val(parseInt(mapveh) - 1);
    jQuery("#tothiddencount").val(parseInt(totveh) - 1);

}

function mapInvVeh() {
    var ledger = jQuery("#ledger").val();
    var data = jQuery("#vmap").serialize();
    var dataString = "work=maptoledger&" + data;
    if (ledger == '0') {
        jQuery("#error_ledger").show();
        jQuery("#error_ledger").fadeOut(3000);
    } else if (jQuery("#vehicle_list").html() == '' && jQuery("#ismapveh").val() == 0) {
        jQuery("#error_vehicle").show();
        jQuery("#error_vehicle").fadeOut(3000);
    } else {
        jQuery.ajax({
            type: "POST",
            url: "invoice_ajax.php",
            cache: false,
            data: dataString,
            success: function () {
                jQuery("#add_mapveh").show();
                jQuery("#add_mapveh").fadeOut(3000);
                jQuery('#vmap')[0].reset();
                if (jQuery("#vehicle_list").html().length > 0) {
                    jQuery("#vehicle_list").html("");
                }
            },
            error: function () {
                jQuery("#fail_mapveh").show();
                jQuery("#fail_mapveh").fadeOut(6000);
            }
        });

    }
}

function mapAllVehicles() {
    var txt = '';
    var totalvehtxt = '';
    var totveh = 0;
    var mapveh = jQuery("#maphiddencount").val();
    var cid = jQuery("#cust_no").val();
    //to fetch vehicles
    jQuery.ajax({
        type: "POST",
        url: "invoice_ajax.php",
        cache: false,
        data: {work: "unmapveh",
            cust_grp: cid
        },
        success: function (result) {
            var data = jQuery.parseJSON(result);
            printMapvehicle(data);
            var vehcount = data.length;
            txt = 'Vehicles Added = ' + vehcount;
            jQuery("#addedvehcount").html(txt);
            totveh = parseInt(vehcount) + parseInt(mapveh);
            totalvehtxt = 'Total Vehicle Count=' + totveh;
            jQuery("#totalvehcount").html(totalvehtxt);
            jQuery("#tothiddencount").val(totveh);
        }

    });
}


