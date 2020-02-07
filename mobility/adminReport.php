<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.alerts.php");

$ajax=new PHPLiveX();

$page = new basic_page();
$homepage = new HomePage();
$notify = new Notification();
$alrt = new Alerts();

$ajax->AjaxifyObjects(array("alrt")); 

$page->auth->CheckAdminlogin();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle('Admin Reports');
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
$page -> setExtJavaScripts15('');

$page -> setCustomJavaScripts('
$(function() {		
		$("#search_table")
		.tablesorter({widthFixed: true,  headers: { 8:{sorter: false} }})
		.tablesorterPager({container: $("#pager")});
	});
	
$(function() {		
		$("#search_tabletobeoaid")
		.tablesorter({widthFixed: true,  headers: { 8:{sorter: false} }})
		.tablesorterPager({container: $("#pager22")});
	});	

 $(function($) {
      var options = {
        format: "%I:%M:%S %p", // 12-hour with am/pm 
		fontFamily: "Verdana, Times New Roman",
        fontSize: "20px",
		foreground: "gray",
		background: "#bfc6cf"
      }
      $(".jclock").jclock(options);
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

$homepage->DisplayshortCutMent('Admin Reports');

$homepage->shortcutNotification();
$ajax->Run('liveX/phplivex.js');
extract($_REQUEST);

?>
<div><?php $notify->Notify();?></div>
<div class="onecolumn">
							<div class="header">
								<span></span>
								
							<div class="switch" style="width:600px">
									<table width="600px" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td><a href="adminReport.php">
												<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="Payment Collected" style="width:200px"/>			
												</a>
											</td>
											<td><a href="paymenttobecollected.php">
												<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Payment to be Collected" style="width:200px" />
												</a>
											</td>
											<td><a href="walkinrecord.php">
												<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Student walkin Record" style="width:200px" /></a>
												
											</td>
										</tr>
									</tbody>
									</table>
								</div>	
							</div> 
							<br class="clear"/>
							<div class="content">
<?php
//$alrt->birthDayAlert();	

$alrt->SearchPaymentCollected($paydatefrom,$paydateto);

?>
</div></div>
<?php 
$page -> displayPageBottom();
?>