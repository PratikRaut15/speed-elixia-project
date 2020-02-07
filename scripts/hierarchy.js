jQuery(function () {
    jQuery('body').click(function () {
        jQuery('#ajaxstatus').hide();
    });
    jQuery('.showtable').click(function () {
        jQuery('#ajaxBstatus').hide();
    });
    jQuery('#cbirthdate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#eocd').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#activitytime').datepicker({format: 'dd-mm-yyyy', autoclose: true});
});

function getTransactionCondition(listid, typeid) {
    var srh = jQuery("#condition" + listid).val();
    jQuery.ajax({
        type: "POST",
        url: "autocomplete.php",
        data: "dummydata=condition&q=" + srh + "&cnt=" + listid+ "&typeid=" + typeid,
        success: function (json) {
            if (listid > 0)
            {
                jQuery('#chkdisplay' + listid).show();
                jQuery("#chkdisplay" + listid).html(json);
            } else {
                jQuery('#chkdisplay').show();
                jQuery("#chkdisplay").html(json);
            }
        }
    });
}

function fillcondition(Value, codeparam, count)
{
    jQuery('#condition' + count).val(codeparam);
    jQuery('#conditionid' + count).val(Value);
    jQuery('#chkdisplay' + count).hide();
}

function getApproverRole(listid, typeid) {
    var srh = jQuery("#approver" + listid).val();
    //alert(listid);
    jQuery.ajax({
        type: "POST",
        url: "autocomplete.php",
        data: "dummydata=approve&q=" + srh + "&listid=" + listid+ "&typeid=" + typeid,
        success: function (json) {
            if (listid > 0)
            {
                jQuery('#approverdisplay' + listid).show();
                jQuery("#approverdisplay" + listid).html(json);
            } else {
                jQuery('#approverdisplay').show();
                jQuery("#approverdisplay").html(json);
            }
        }
    });
}

function fillapprover(Value, codeparam, count)
{
    jQuery('#approver' + count).val(codeparam);
    jQuery('#approverid' + count).val(Value);
    jQuery('#approverdisplay' + count).hide();
}

function getApprover_Role() {
    var srh = jQuery("#approver").val();
    //alert(listid);
    jQuery.ajax({
        type: "POST",
        url: "autocomplete.php",
        data: "dummydata=approve_list&q=" + srh,
        success: function (json) {
            
                jQuery('#approvelist').show();
                jQuery("#approvelist").html(json);
            
        }
    });
}

function fill_approver(Value, codeparam)
{
    jQuery('#approver').val(codeparam);
    jQuery('#approverid').val(Value);
    jQuery('#approverdisplay').hide();
}

function createCondition() {
    var conditionids = new Array();
    var approverids = new Array();
    var typeids = new Array();
    var minvalues = new Array();
    var maxvalues = new Array();
    var sequncenos = new Array();
    var custno = jQuery("#custno").val();
    var userid = jQuery("#userid").val();

    jQuery('.cls_conditionid').each(function () {
        conditionids.push(jQuery(this).val());
    })
    jQuery('.cls_approverid').each(function () {
        approverids.push(jQuery(this).val());
    })
    jQuery('.cls_typeid').each(function () {
        typeids.push(jQuery(this).val());
    })
    jQuery('.cls_minvalue').each(function () {
        minvalues.push(jQuery(this).val());
    })
    jQuery('.cls_maxvalue').each(function () {
        maxvalues.push(jQuery(this).val());
    })
    jQuery('.cls_sequnceno').each(function () {
        sequncenos.push(jQuery(this).val());
    })



    var data = jQuery('#createconditions').serialize();
    //alert(data);
    var datastring = "conditionids=" + conditionids +
            "&approverids=" + approverids +
            "&typeids=" + typeids +
            "&minvalues=" + minvalues +
            "&maxvalues=" + maxvalues +
            "&sequncenos=" + sequncenos +
            "&custno=" + custno +
            "&userid=" + userid;
    // Create-Proposed-Indent
    jQuery.ajax({
        type: "POST",
        url: "action.php?action=Create-Conditions",
        data: datastring,
        cache: false,
        datatype: "json",
        success: function (response)
        {
            var data = JSON.parse(response);
            //alert(data);
            jQuery("#saved").show();
            jQuery("#saved").fadeOut(5000);
            window.location.href = "condition.php?id=2";
        }
    });


}