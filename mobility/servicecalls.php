<?php
 
require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.servicecall.php");

require_once("class/Date.php");

$ajax=new PHPLiveX();

$page = new basic_page();
$homepage = new HomePage();

$servicecall_master_info = new servicecall();
$notify = new Notification();

$ajax->AjaxifyObjects(array("servicecall_master_info"));  
$page -> setActiveButton('1');
$page->auth->CheckAdminlogin();
//$page -> setInnerNav('');
$page -> setPageTitle('Service Calls Details');
$page -> setImportCss1('css/blue/screen.css');

$page -> setImportCss2('css/blue/datepicker.css');

$page -> setImportCss3('css/tipsy.css');

$page -> setImportCss4('js/visualize/visualize.css');

$page -> setImportCss5('js/jwysiwyg/jquery.wysiwyg.css');

$page -> setImportCss6('js/fancybox/jquery.fancybox-1.3.0.css');

$page -> setImportCss7('css/SAYT.css');

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

//$page -> setExtJavaScripts12('js/disablerightclick.js');

$page -> setExtJavaScripts13('js/jquery.jclock.js');

$page -> setExtJavaScripts14('js/forms_services.js');

$page -> setExtJavaScripts15('js/prototype.js');

$page -> setExtJavaScripts16('js/SAYTlookup.js');
//$page -> setExtJavaScripts17('js/SAYTlookup.js');





$page -> setCustomJavaScripts('

jQuery(function() {		

		jQuery("#search_table")

		.tablesorter({widthFixed: true,  headers: { 7:{sorter: false} }})

	});



 jQuery(function(jQuery) {

      var options = {

        format: "%I:%M:%S %p", // 12-hour with am/pm 

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
						if($submit=="Submit")
						$servicecall_master_info->addclient('server');
						else
						$servicecall_master_info->addclient('local');	
			break;
		
		case 'search' :
						
						$servicecall_master_info->SearchRecord($clientname,$to,$appointmentdate,$datecreated,$appointmentdatet,$datecreatedt,$status,$trackingno);
			break;	
		case 'servicereport' :
					
					$servicecall_master_info->ReportRecord($appointmentdate,$appointmentdatet);
		break;	
		
		case 'customerreport' :
					
					$servicecall_master_info->customer_reports($visit_type,$d_from,$search);
		break;	
		
		case 'therapistreport' :
					
					$servicecall_master_info->trackeereports($appointmentdate,$appointmentdatet);
		break;	
		
		case 'feedbackreport' :
					
					$servicecall_master_info->feedback_reports($appointmentdate,$appointmentdatet);
		break;	
		
		
		case 'add_payment' :
						if(@$submit=="Submit")
						{
						
						$servicecall_master_info->add_payments('server',$service_id);
						
						}else{
						$servicecall_master_info->add_payments('local',$service_id);	
						}
			
			break;	
			
		case 'view_payment' :
						
						$servicecall_master_info->view_payments($service_id);	
						
			break;	
		
		case 'edit' :
						if(@$submit=="Submit")
						{
						
						$servicecall_master_info->editclient('server',$serviceid);
						
						}else{
						$servicecall_master_info->editclient('local',$serviceid);	
						}
			
			break;	
		
		case 'delete' :
					$servicecall_master_info->deleteStudent($serviceid);	
			break;
			
		
		
		case 'View' :
					$servicecall_master_info->ServiceView($serviceid);	
			break;	
							
		case 'viewAll' :
					$servicecall_master_info->showAllServicefInfo();	
			break;
		default :			
					$servicecall_master_info->showAllServicefInfo();	
			break;		
	}
	
?>	
	
<?php
$page -> displayPageBottom();
?>