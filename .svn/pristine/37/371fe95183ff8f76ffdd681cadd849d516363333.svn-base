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
$page -> setPageTitle('Courses');
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
$page -> setExtJavaScripts10('');

$page -> setCustomJavaScripts('



$("#various2").fancybox({
				ajax : {
				    type	: "POST",
				    example	: "myexample=test"
				}
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
<div id="content">
		<div class="inner">
			<h1>Dashboard</h1>
<div><ul><li id="popupwindo"><a id="btn_modal" href="Addnews.php" title="khul ja siim siim">Ajax</a></li></ul></div>
</div></div>

<a id="ajaxpopup" href="#inline1" title="Lorem ipsum dolor sit amet"  onclick="javascript: managemaster.addCourse('local',
			{ target:'inline1'
						} ); return false;">Inline</a>
<div style="display: none;">
	<div id="inline1" style="width:800px;height:300px;overflow:auto; vertical-align:middle;" align="center">
	</div>
</div>
<?php 
	switch($index)
	{
		case 'addCourse' :
						if($submit=="Submit")
						$managemaster->addCourse('server');
						else
						$managemaster->addCourse('local');	
			break;
		
		case 'editCourse' :
						if($submit=="Submit")
						$managemaster->editCourse('server',$course_id);
						else
						$managemaster->editCourse('local',$course_id);	
			break;	
		
		case 'deleteCourse' :
					$managemaster->deleteCourse($course_id);	
			break;
			
		case 'viewAllCourses' :
					$managemaster->showAllCourses();	
			break;
			
		default :			
					$managemaster->showAllCourses();	
			break;		
	}
	
?>	
	
<?php
$page -> displayPageBottom();
?>