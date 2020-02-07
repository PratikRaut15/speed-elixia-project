<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.alerts.php");
require_once("class/class.devices.php");
$_SESSION['page']="home";
$ajax=new PHPLiveX();

$page = new basic_page();
$homepage = new HomePage();
$notify = new Notification();
$alrt = new Alerts();
$device_obj = new devices();
$page->auth->CheckAdminlogin();

$ajax->AjaxifyObjects(array("alrt"));  

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle('Map Devices');
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
$page -> setExtJavaScripts13('js/disablerightclick.js');
$page -> setExtJavaScripts14('js/jquery.jclock.js');
//$page -> setExtJavaScripts15('https://maps.googleapis.com/maps/api/js?key=AIzaSyCqInDK10QshlbDp_KOJbG0EGh-Q17AMCQ&sensor=false');
$page -> setExtJavaScripts16('');
$page -> setExtJavaScripts17('js/devicemapper.js');
$page -> setExtJavaScripts18('js/prototype.js');
$page -> setExtJavaScripts19('');

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

jQuery(function($) {
      var options = {
        format: "%I:%M:%S %p", // 12-hour with am/pm 
		fontFamily: "Verdana, Times New Roman",
        fontSize: "20px",
		foreground: "gray",
		background: "#bfc6cf"
      }
      jQuery(".jclock").jclock(options);
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
<script type="text/javascript">
jQuery(document).ready(function() {
loaded();
});
</script>
<div ><?php $notify->Notify();?></div>




<br class="clear" />
<div class="onecolumn">
	<div class="header">
		<span>Device Manager</span>
		
	</div>
	<br class="clear"/>
		<div class="content">
		<table class="data" width="100%">
		<tr>
		<th width="44%">devices</th>
		<th width="17%"></th>
		<th width="39%">trackee</th>
		</tr>
		<tr valign="top">
		<td valign="top"><div id="deviceslist"><?php $device_obj->devices();?></div></td>
		<td valign="top">
		<input id="btnMapper" type="button" disabled="" onclick="mapselection()" value="Assign" name="btnMapper">
		<input id="btnDemap" type="button" onclick="demap()" value="De-map" name="btnDemap">
		</td>
		<td valign="top"><div id="trackeelist"><?php $device_obj->trackee();?></div></td>
		
		
		</tr>
		
		
		</table>
		
		
		
		
		
		
		
		
			
		
		</div>



<?php 





$page -> displayPageBottom();
?>