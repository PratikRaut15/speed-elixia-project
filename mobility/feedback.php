<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.feedback.php");

$ajax=new PHPLiveX();

$page = new basic_page();
$homepage = new HomePage();

$feedbackinfo = new feedback();
$notify = new Notification();

$ajax->AjaxifyObjects(array("feedbackinfo"));  

$page->auth->CheckAdminlogin();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle('feedback');
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

$script = '







';
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
						$feedbackinfo->addclient('server');
						else
						$feedbackinfo->addclient('local');	
			break;
		
		case 'search' :
						
						$feedbackinfo->SearchRecord($feedbackquestion);
			break;	
		
		case 'addoption' :
						if($submit=="Submit")
						$feedbackinfo->addoption('server',$feedbackquestionid);
						else
						$feedbackinfo->addoption('local',$feedbackquestionid);	
			break;	
		
		case 'edit' :
						if(@$submit=="Submit")
						$feedbackinfo->editclient('server',$feedbackquestionid);
						else
						$feedbackinfo->editclient('local',$feedbackquestionid);	
			break;	
		
		case 'delete' :
					$feedbackinfo->deleteStudent($feedbackquestionid);	
			break;
			
		
		
		case 'View' :
					$feedbackinfo->FeedbackView($feedbackquestionid);	
			break;	
							
		case 'viewAll' :
					$feedbackinfo->showAllFeedbackfInfo();	
			break;
		default :			
					$feedbackinfo->showAllFeedbackfInfo();	
			break;		
	}
	
?>	
	
<?php
$page -> displayPageBottom();
?>

<script type="text/javascript" >
function domclick(){

var contentID = document.getElementById('domt'); 
var box=document.createElement("input");
box.setAttribute("type","text");
box.setAttribute("name","optionname[]");

box.setAttribute("size","50");
contentID.appendChild(box);


}


var count
</script>