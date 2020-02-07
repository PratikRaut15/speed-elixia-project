function submit_contact_form() {
    if($("#u_name").val()=='' || $("#email").val()=='')
    {
        $("#query").submit(false);
        
    }
    else {
   
    var data = $("#query").serialize();
    jQuery.ajax({
        type: "POST",
        url: "index_files/mail.php",
        cache: false,
        dataType: 'JSON',
        data: data+"&product="+prod_query,
        success: function (data) {
            //console.log(data);
            if (data.status == true) {
                $("#query_res_success").show();
                $("#query").hide();
            } else {
               $("#query_res_fail").show();
               $("#query").hide();
            }
        }
    });
}
}
function submit_newsletter() 
{
    var email = $("#email_newsletter").val();
    jQuery.ajax({
        type: "POST",
        url: "index_files/mail.php",
        cache: false,
        dataType: 'JSON',
        data: "email_ns="+email,
        success: function (data) {
            window.location.reload();
        }
    });
}
