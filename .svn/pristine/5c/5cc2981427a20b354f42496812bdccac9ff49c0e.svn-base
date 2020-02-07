<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.Manage_master.php");

$ajax=new PHPLiveX();

$page = new basic_page();
$homepage = new HomePage();

$managemaster = new ManageMaster();
$notify = new Notification();

$ajax->AjaxifyObjects(array("managemaster"));  

$page->auth->CheckAdminlogin();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle('Fee Email');
$page -> setActiveButton('1');
//$page -> setInnerNav('');
$page -> setImportCss1('css/blue/screen.css');
$page -> setImportCss2('css/blue/datepicker.css');
$page -> setImportCss3('css/tipsy.css');
$page -> setImportCss4('js/visualize/visualize.css');
$page -> setImportCss5('js/jwysiwyg/jquery.wysiwyg.css');
$page -> setImportCss6('js/fancybox/jquery.fancybox-1.3.0.css');
$page -> setImportCss7('css/tipsy.css');
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
$page -> setExtJavaScripts10('js/jquery.jclock.js');

$page -> setCustomJavaScripts('

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

$homepage->DisplayshortCutMent('Home');

$homepage->shortcutNotification();

$ajax->Run('liveX/phplivex.js');

extract($_REQUEST);
$notify->Notify();
?><br class="clear"/><?php 
	switch($index)
	{
		case 'addEmail' :
						if($submit=="Submit")
						$managemaster->addEmail('server');
						else
						$managemaster->addEmail('local');	
			break;
		
		case 'editEmail' :
						if($submit=="Submit")
						$managemaster->editEmail('server',$id);
						else
						$managemaster->editEmail('local',$id);	
			break;	
		
		case 'deleteYear' :
					$managemaster->deleteYear($id);	
			break;
							
		case 'viewAll' :
					$managemaster->showAllEmail();	
			break;
		default :			
					$managemaster->showAllEmail();	
			break;		
	}
	
?>	
	
<?php
$page -> displayPageBottom();
?>