<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.user.php");

$page = new basic_page();
$notify = new Notification();
$homepage = new HomePage();
$user = new User();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle('Admin Login');
$page -> setActiveButton('1');
//$page -> setInnerNav('');
$page -> setImportCss1('css/blue/screen.css');
//$page -> setImportCss2('css/blue/datepicker.css');
//$page -> setImportCss3('css/tipsy.css');
//$page -> setImportCss4('js/visualize/visualize.css');
//$page -> setImportCss5('js/jwysiwyg/jquery.wysiwyg.css');
//$page -> setImportCss6('js/fancybox/jquery.fancybox-1.3.0.css');
//$page -> setImportCss7('css/tipsy.css');


$page -> setExtJavaScripts1('js/jquery.js'); // might not need
$page -> setExtJavaScripts2('js/jquery-ui.js');
//$page -> setExtJavaScripts3('js/jquery.img.preload.js');
//$page -> setExtJavaScripts4('js/hint.js');
//$page -> setExtJavaScripts5('js/visualize/jquery.visualize.js');
//$page -> setExtJavaScripts6('js/jwysiwyg/jquery.wysiwyg.js');
//$page -> setExtJavaScripts7('js/fancybox/jquery.fancybox-1.3.0.js');
//$page -> setExtJavaScripts8('js/jquery.tipsy.js');
//$page -> setExtJavaScripts9('js/custom_blue.js');
//$page -> setExtJavaScripts10('js/jquery.jclock.js');

$page -> setCustomJavaScripts('');
//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '';

$page -> setPageStyle($page_style);

$script = 'class="login"';
$page -> setBodyScript($script);

// set side Menu array -- only 10 menues per page allowed

 
$page -> displayPageTopLogin();

// **********************Start html for content column ****************************//

//$homepage->DisplayshortCutMent('Home');

//$homepage->shortcutNotification();

// **********************Start html for content column ****************************//

$notify->Notify();
extract($_REQUEST);
$notify->Notify();
if(!($page->auth->checkAdminAuthentication())){
	if(@$submit=="Login")
		$user->AdminLogin("server");
	else
		$user->AdminLogin("local");
} else {
	?>
	<script language="javascript">window.location='home.php'</script>
	<?php
}



$page -> displayPageBottom();
?>