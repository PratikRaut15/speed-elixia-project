<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.alerts.php");
require_once("class/class.servicecall.php");

require_once("class/Date.php");
$_SESSION['page']="home";

$ajax=new PHPLiveX();

$page = new basic_page();
$homepage = new HomePage();
$notify = new Notification();
$alrt = new Alerts();
$page->auth->CheckAdminlogin();
$scalls= new servicecall();

$ajax->AjaxifyObjects(array("alrt"));  

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle('Home');
$page -> setActiveButton('1');
//$page -> setInnerNav('');
$page -> setImportCss1('css/blue/screen.css');
$page -> setImportCss2('css/blue/datepicker.css');
$page -> setImportCss3('css/tipsy.css');
$page -> setImportCss4('js/visualize/visualize.css');
$page -> setImportCss5('js/jwysiwyg/jquery.wysiwyg.css');
$page -> setImportCss6('js/fancybox/jquery.fancybox-1.3.0.css');
$page -> setImportCss7('tablesorter/pager/jquery.tablesorter.pager.css');
$page -> setImportCss8('');
$page -> setImportCss9('');
$page -> setImportCss10('');

$page -> setExtJavaScripts1('js/jquery.js'); // might not need
$page -> setExtJavaScripts2('js/jquery-ui.js');
$page -> setExtJavaScripts3('js/jquery.img.preload.js');
$page -> setExtJavaScripts4('js/hint.js');
$page -> setExtJavaScripts5('js/visualize/jquery.visualize.js');
$page -> setExtJavaScripts6('js/jwysiwyg/jquery.wysiwyg.js');
$page -> setExtJavaScripts7('js/fancybox/jquery.fancybox-1.3.0.js');
$page -> setExtJavaScripts8('js/jquery.tipsy.js');
$page -> setExtJavaScripts9('js/custom_blue.js');
$page -> setExtJavaScripts10('table2csv/table2CSV.js');
$page -> setExtJavaScripts11('tablesorter/jquery.tablesorter.js');
$page -> setExtJavaScripts12('tablesorter/pager/jquery.tablesorter.pager.js');
//$page -> setExtJavaScripts13('js/disablerightclick.js');
$page -> setExtJavaScripts14('js/jquery.jclock.js');
$page -> setExtJavaScripts15('');

$page -> setCustomJavaScripts('
jQuery(function() {		
		jQuery("#search_table")
		.tablesorter({widthFixed: true,  headers: { 7:{sorter: false} }})
		.tablesorterPager({container: jQuery("#pager")});
	});

jQuery(function() {		
		jQuery("#search_table_2")
		.tablesorter({widthFixed: true,  headers: { 7:{sorter: false} }})
		.tablesorterPager({container: jQuery("#pager_2")});
	});	

 jQuery(function(jQuery) {
      var options = {
        format: "%I:%M:%S %p", // 12-hour with am/pm 
		fontFamily: "Verdana, Times New Roman",
        fontSize: "20px",
		foreground: "gray",
		background: "#bfc6cf"
      }
      jQuery(".jclock").jclock(options);
    });	
	jQuery(document).ready(function() {
	jQuery(".tab_content").addClass("hide");

		jQuery("#tab2_content").removeClass("hide");
	

});
jQuery(document).ready(function() {
setTimeout("dashboard_update();",2000);
});

');
//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
';

$page -> setPageStyle($page_style);

$script = '';
$page -> setBodyScript($script);

// set side Menu array -- only 10 menues per page allowed

 
$page -> displayPageTop();

// **********************Start html for content column ****************************//

$homepage->DisplayshortCutMent('Home');

$homepage->shortcutNotification();

$ajax->Run('liveX/phplivex.js');
extract($_REQUEST);

?>
<div><?php $notify->Notify();?></div>



<?php if($_SESSION['isservice']=="true"){ ?>
<input type="button" onclick="dashboard_update();" id="tabx" name="tabx" value="Refresh"  style="float:right;"/>
<br class="clear" />
		<div class="onecolumn">
		<div class="header">
		<span> Summary </span> <div style="float:left; padding-top:15px; padding-left:5px; color:#172B39;">Updated at:  </div> <div id="updated_at" style="color:#172B39;float:left;padding-top:15px;"><?php echo date("d-M-y h:i A"); ?></div>
		<div class="switch" style="width:500px">
		<table width="500px" cellpadding="0" cellspacing="0">
		<tbody>
		<tr>
		<td>
		<input type="button" id="tab2" name="tab2" class="left_switch" value="Service Calls Data" style="width:250px"/>
		</td>
		<td>
		<input type="button" id="tab1" name="tab1" class="right_switch" value="Trackee Data" style="width:250px"/>
		</td>
		
		</tr>
		</tbody>
		</table>
		</div>
		</div>
		<br class="clear"/>
		
		<div class="content">
		
		<div id="headerref">
		<?php  $scalls->dashboard_header(); ?>
		
		</div>
		
		
		
		<div id="tab2_content" class="tab_content hide">
		
		
		<div id="serviceerref">
		<?php  $scalls->dashboard_servicecalls(); ?>
		</div>
		
		</div>
		<div id="tab1_content" class="tab_content hide">
		
		
		<div id="dashref">
		<?php  $scalls->dashboard_body(); ?>
		</div>
		
		
		
		
		</div>
		
		
		</div>
		</div>


<?php }?>
<?php 





$page -> displayPageBottom();
?>