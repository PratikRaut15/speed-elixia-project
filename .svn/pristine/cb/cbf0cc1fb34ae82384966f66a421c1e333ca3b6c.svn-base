<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.servicelist.php");

$ajax=new PHPLiveX();

$page = new basic_page();
$homepage = new HomePage();

$serviceinfo = new servicelist();
$notify = new Notification();

$ajax->AjaxifyObjects(array("serviceinfo"));  

$page->auth->CheckAdminlogin();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle('Service List Details');
$page -> setActiveButton('1');
//$page -> setInnerNav('');
$page -> setImportCss1('css/blue/screen.css');
$page -> setImportCss2('css/blue/datepicker.css');
$page -> setImportCss3('css/tipsy.css');
$page -> setImportCss4('js/visualize/visualize.css');
$page -> setImportCss5('js/jwysiwyg/jquery.wysiwyg.css');
$page -> setImportCss6('js/fancybox/jquery.fancybox-1.3.0.css');
$page -> setImportCss7('');
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
$page -> setExtJavaScripts12('js/disablerightclick.js');
$page -> setExtJavaScripts13('js/jquery.jclock.js');
$page -> setExtJavaScripts14('');
$page -> setExtJavaScripts15('');

$page -> setCustomJavaScripts('
$(function() {		
		$("#search_table")
		.tablesorter({widthFixed: true,  headers: { 7:{sorter: false} }})
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

$homepage->DisplayshortCutMent('');

$homepage->shortcutNotification();

$ajax->Run('liveX/phplivex.js');
extract($_REQUEST);
$notify->Notify();
?>
<div style="display: none;">
<div id="popupdiv" style="width:820px;height:300px;overflow:auto; vertical-align:middle; background-color:#FFFFFF" align="center"></div>
</div>

<?php 

	switch($index)
	{
		case 'add' :
						if(@$submit=="Submit")
						$serviceinfo->addclient('server');
						else
						$serviceinfo->addclient('local');	
			break;
		
		case 'search' :
						
						$serviceinfo->SearchRecord($servicename);
			break;	
		
		case 'edit' :
						if(@$submit=="Submit")
						$serviceinfo->editclient('server',$servicelist_id);
						else
						$serviceinfo->editclient('local',$servicelist_id);	
			break;	
		
		case 'delete' :
					$serviceinfo->deleteStudent($servicelist_id);	
			break;
			
		
		
		case 'View' :
					$serviceinfo->ServiceView($servicelist_id);	
			break;	
							
		case 'viewAll' :
					$serviceinfo->showAllServicefInfo();	
			break;
		default :			
					$serviceinfo->showAllServicefInfo();	
			break;		
	}
	
?>	
	
<?php
$page -> displayPageBottom();
?>