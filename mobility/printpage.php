<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.alerts.php");

$ajax=new PHPLiveX();

$page = new basic_page();
$homepage = new HomePage();
$notify = new Notification();
$alrt = new Alerts();
$page->auth->CheckAdminlogin();

$ajax->AjaxifyObjects(array("alrt"));  

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle('Report');
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
$(function() {		
		$("#search_table")
		.tablesorter({widthFixed: true,  headers: { 7:{sorter: false} }})
		.tablesorterPager({container: $("#pager")});
	});

$(function() {		
		$("#search_table_2")
		.tablesorter({widthFixed: true,  headers: { 7:{sorter: false} }})
		.tablesorterPager({container: $("#pager_2")});
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

 
$page -> displayPrintPageTop();

// **********************Start html for content column ****************************//

//$homepage->DisplayshortCutMent('Home');

//$homepage->shortcutNotification();
?><body style="width:900px"><?php 
$ajax->Run('liveX/phplivex.js');
extract($_REQUEST);

?>
<p align="center">
<img src="images/cadd_logo_3.png" />
<!--<font color="#CCCCCC"><h1>Singhal Institute of CADD CENTRE & Engineering Services (P) Ltd.</h1></font>-->
</p>
<div id="div_print">
<a href="javascript: void(0);" onClick="javascript: document.getElementById('div_print').style.display='none'; document.getElementById('switch').style.display='none'; window.print();" title="Print this Page">Print</a>
</div>
<?php 
switch($index)
	{
		case 'personrecord' :
					 $alrt->SearchPersonRecordonpopup($name,$course,$branch,$sub_branch,$datefrom,$dateto,$status,$dob,$type);
			break;
	
		case 'paymenttobecollected' :
				$alrt->SearchPaymenttobeCollectedprintview($paydatefrom,$paydateto);
				?><div id="switch"></div><?php
			break;	
			
		case 'paymentcollected' :
				$alrt->SearchPaymentCollectedprintview($paydatefrom,$paydateto);
				?><div id="switch"></div><?php 
			break;
			
		default :			
					//$managemaster->showAllCourses();	
			break;		
	}

?>
</body>
</html>