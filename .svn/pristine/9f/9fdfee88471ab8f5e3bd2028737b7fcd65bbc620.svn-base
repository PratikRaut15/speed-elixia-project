function submitcheckpointtype()
{
    var chk = jQuery("#chktype").val();
    if (chk == "")
    {
        alert("Please enter checkpoint type.");
    } else
    {

        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php",
            async: true,
            data: {
                chktypeN: chk
            },
            cache: false,
            success: function (statuscheck) {

                if (statuscheck == "ok")
                {
                    jQuery("#createchktype").submit();
                } else
                {
                    alert("Checkpoint Type Already exists.");
                }
            }
        });
    }
}