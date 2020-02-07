</div>
</div>
<div style="clear: both;">&nbsp;</div>
</div>
</div>
</div>
</div>
<?php
$umgr = new UserManager();
$ticketType = $umgr->getalltickettype();
if(!isset($_SESSION['ecodeid'])){

$user = $umgr->get_user($_SESSION['customerno'], $_SESSION['userid']);
}
?>
<div>

</div>
<div id="footer">
    <p>&copy; 2012-<?php echo date('Y') ?> <a href="http://www.elixiatech.com/">Elixiatech.com</a>. All rights reserved.</p>
</div>



<script src="<?php echo $_SESSION['subdir']; ?>/table2csv/table2CSV.js" type="text/javascript"></script>
<?php if (!isset($_SESSION['username']) && !isset($_SESSION['ecodeid'])) { ?>
    <script src="<?php echo $_SESSION['subdir']; ?>/scripts/user.js" type="text/javascript"></script>
<?php }?>

<?php

include 'forjs.php';
if ($page == 'reports.php') {
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        $url = $page . "?id=" . $id;
    } else {
        $url = $page;
    }
} else {
    $url = $page;
}
if(!isset($_SESSION['ecodeid'])){

$today = date('Y-m-d H:i:s');
$um = new UserManager();
$pageid = $um->getPageMasterId($url);
$data = new stdClass();
$data->pageMasterId = $pageid;
$data->loginType = 0;
$data->customerno = $_SESSION['customerno'];
$data->todaysdate = $today;
$data->userid = $_SESSION['userid'];

if (isset($pageid)) {
    $um->InsertLoginHistory($data);
}
}
?>

<style>
    #supportDiv{
        height: 48px;
        width: 50px;
        display: none;
        cursor: pointer;
        background: #00a6b8 ;
    }
    #supportPanel{
        height: 260px;
        width: 300px;
        display: none;
        background: white;
        box-shadow: -4px 7px 21px 0px rgba(0,0,0,0.74);
        -webkit-box-shadow: -4px 7px 21px 0px rgba(0,0,0,0.74);
        -moz-box-shadow: -4px 7px 21px 0px rgba(0,0,0,0.74);
    }
    #successPanel{
        height: 200px;
        width: 300px;
        display: none;
        background: white;
    }
    .support{
        position: fixed;
        right:130px;
        bottom:0px;
        z-index: 1000;
        display: none;
        border-radius:10px 10px 0px 0px;
    }
    #supportClose{
        margin-left:170px;
        color: white;
        cursor: pointer;
        font-size: large;
    }
    #supportClose:hover{
        font-size: 25px;
    }
    #supportTable td{
        text-align: left;
    }
    #callback{
        margin-left: 10px;
    }
</style>
<script>

/* Displaying and hiding loader when ajax starts and completes*/
$(document).ready(function() {

  
});




jQuery('#overspeed_val').ajaxSend(function(event, jqxhr, settings) { 
    console.log('URL is:'+settings.url);
    console.log('URL is of type: '+ typeof settings.url);
    var requestedUrl = settings.url;
    if(settings.url.split("?")[0]=='searchfilter.php')
    {
        jQuery('#pageloaddiv').show(); 
    }
    else
    {
        jQuery('#pageloaddiv').hide();
    }
});
jQuery('#overspeed_val').ajaxStop(function() { 
    jQuery('#pageloaddiv').hide(); 
});

jQuery('#running_val').ajaxSend(function(event, jqxhr, settings) { 
    if(settings.url.split("?")[0]=='searchfilter.php')
    {
        jQuery('#pageloaddiv').show(); 
    }
    else
    {
        jQuery('#pageloaddiv').hide();
    }
});
jQuery('#running_val').ajaxStop(function() { 
    jQuery('#pageloaddiv').hide(); 
})

jQuery('#idle_ign_on').ajaxSend(function(event, jqxhr, settings) { 
    if(settings.url.split("?")[0]=='searchfilter.php')
    {
        jQuery('#pageloaddiv').show(); 
    }
    else
    {
        jQuery('#pageloaddiv').hide();
    } 
});
jQuery('#idle_ign_on').ajaxStop(function() { 
    jQuery('#pageloaddiv').hide(); 
})

jQuery('#idle_ign_off').ajaxSend(function(event, jqxhr, settings) { 
    if(settings.url.split("?")[0]=='searchfilter.php')
    {
        jQuery('#pageloaddiv').show(); 
    }
    else
    {
        jQuery('#pageloaddiv').hide();
    }
});
jQuery('#idle_ign_off').ajaxStop(function() { 
    jQuery('#pageloaddiv').hide(); 
})


jQuery('#inactive_val').ajaxSend(function(event, jqxhr, settings) { 
    if(settings.url.split("?")[0]=='searchfilter.php')
    {
        jQuery('#pageloaddiv').show(); 
    }
    else
    {
        jQuery('#pageloaddiv').hide();
    }
});
jQuery('#inactive_val').ajaxStop(function() { 
    jQuery('#pageloaddiv').hide(); 
})


jQuery('#inactive').ajaxSend(function(event, jqxhr, settings) { 
    if(settings.url.split("?")[0]=='searchfilter.php')
    {
        jQuery('#pageloaddiv').show(); 
    }
    else
    {
        jQuery('#pageloaddiv').hide();
    }
});
jQuery('#inactive').ajaxStop(function() { 
    jQuery('#pageloaddiv').hide(); 
})


jQuery('#w_on').ajaxSend(function(event, jqxhr, settings) { 
    if(settings.url.split("?")[0]=='searchfilter.php')
    {
        jQuery('#pageloaddiv').show(); 
    }
    else
    {
        jQuery('#pageloaddiv').hide();
    }
});
jQuery('#w_on').ajaxStop(function(event, jqxhr, settings) { 
    jQuery('#pageloaddiv').hide(); 
})


jQuery('#conflict').ajaxSend(function(event, jqxhr, settings) { 
    if(settings.url.split("?")[0]=='searchfilter.php')
    {
        jQuery('#pageloaddiv').show(); 
    }
    else
    {
        jQuery('#pageloaddiv').hide();
    } 
});
jQuery('#conflict').ajaxStop(function() { 
    jQuery('#pageloaddiv').hide(); 
})

jQuery('#seconds').ajaxSend(function(event, jqxhr, settings) { 
    if(settings.url.split("?")[0]=='searchfilter.php')
    {
        jQuery('#pageloaddiv').show(); 
    }
    else
    {
        jQuery('#pageloaddiv').hide();
    }
});
jQuery('#seconds').ajaxStop(function() { 
    jQuery('#pageloaddiv').hide(); 
}) 


/* $(function() {
    jQuery(document).bind('ajaxComplete', function() {
        jQuery("#pageloaddiv").hide();
  });

  jQuery(document).bind('ajaxStart', function() {
        jQuery("#pageloaddiv").show();
  });

}); */


/* jQuery(document).ready(function() {
  // Global ajax cursor change
  jQuery(document)
    .ajaxStart(function () {
        jQuery("#pageloaddiv").show();
    })
    .ajaxComplete(function () {
        jQuery("#pageloaddiv").hide();
    });
}); */
/* */

    jQuery(function () {
        jQuery("#supportDiv").animate({height: 'toggle'}, 350);
    });
    jQuery("#supportClose").click(function () {
        jQuery("#supportPanel").animate({bottom: "-100%"}, 700);
        jQuery("#supportDiv").show();
        return false;
    });
    function openSupport() {

        jQuery('#supportDiv').hide();
        jQuery("#supportPanel").show();
        jQuery("#formPanel").show();
        jQuery('#desc').val('');
        jQuery('#type').val(0);
        jQuery("#successPanel").hide();
        jQuery("#supportPanel").animate({bottom: "0"}, 700);

}
    function addsupport() {
        var desc = jQuery('#desc').val();
        var type = jQuery('#type').val();
        var phone = jQuery('#phone').val();
        var email = jQuery('#email').val();
        var customerno = jQuery('#customerno').val();
        var userid = jQuery('#userid').val();
        if (desc == '') {
            alert("Enter Issue Description");
            jQuery('#desc').focus();
        } else if (type < 1) {
            alert('Select correct type.');
            jQuery('#type').focus();
        } else if (!(/^\d{10}$/.test(phone))) {
            if (confirm('Incorrect or Empty Number.Do you want to enter mobile number?')) {
                window.location.href = '../user/accinfo.php';
            } else {
                $("#supportPanel").animate({bottom: "-100%"}, 700);
                jQuery("#supportDiv").show();
            }
        } else if (!(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email))) {
            if (confirm('Incorrect or Empty Email.Do you want to enter mail id?')) {
                window.location.href = '../user/accinfo.php';
            } else {
                $("#supportPanel").animate({bottom: "-100%"}, 700);
                jQuery("#supportDiv").show();
            }
        } else {
            var formdata = jQuery('#addTicketForm').serialize();
            jQuery.ajax({
                type: "POST",
                url: "../../modules/support/route.php",
                data: "test=quickTicket&" + formdata,
                success: function (page) {
                    if (page == 'ok') {
                        jQuery('#formPanel').hide();
                        jQuery('#successPanel').show();
                    } else {
                        jQuery('#messageImg').attr('src','../../images/fail.png');
                        jQuery('#formPanel').hide();
                        jQuery('#messageText span').text('Ticket Generation Failed');
                        jQuery('#successPanel').show();
                    }
                }
            });
        }
    }
    function typeChange() {
        jQuery('#type_text').val(jQuery('#type').find("option:selected").text());
    }
</script>





<!-- begin freshchat code -->
<script src="https://wchat.freshchat.com/js/widget.js" async></script>
<script src="../../scripts/freshchat.js"></script>
<script>
    <?php
   if(!isset($_SESSION['ecodeid'])){
        $user=new UserManager();
    ?>
   var extid =  "<?php echo $_SESSION['userid']; ?>";
   var realname = "<?php echo $_SESSION['realname']; ?>";
   var customercompany = "<?php echo $_SESSION['customercompany']; ?>";
   var email = "<?php echo $_SESSION['email']; ?>";
   var phone = "<?php echo $_SESSION['phone']; ?>";
   var restid = "<?php echo $user->getRestoreId($_SESSION['userid']);?>";
   //freshchat(extid,realname,customercompany,email,phone,restid);

   
</script>
<!-- end freshchat code -->
<!-- end #footer -->
<?php } ?>
</body>


</html>
