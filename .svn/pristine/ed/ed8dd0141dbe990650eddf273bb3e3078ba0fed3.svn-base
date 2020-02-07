<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.alerts.php");
require_once("class/class.checkpoints.php");

$ajax=new PHPLiveX();

$page = new basic_page();
$homepage = new HomePage();
$notify = new Notification();
$alrt = new Alerts();
$checkobj = new checkpoints();
$page->auth->CheckAdminlogin();

$ajax->AjaxifyObjects(array("checkobj"));  

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
$page -> setExtJavaScripts13('js/disablerightclick.js');
$page -> setExtJavaScripts14('js/jquery.jclock.js');
$page -> setExtJavaScripts15('js/getchk.js');
$page -> setExtJavaScripts16('http://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyBVx5UoY0689mvm0dOUzmm_Wso0UPicCSc');
$page -> setExtJavaScripts17('js/checkpoints.js');
$page -> setExtJavaScripts18('js/prototype.js');
//$page -> setExtJavaScripts19('http://maps.googleapis.com/maps/api/js?sensor=false');

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
		<span>Check points</span>
		<div class="switch" style="width:500px">
			<table width="500px" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<input type="button" id="tab1" name="tab1" class="left_switch" value="Create Checkpoints" style="width:250px"/>
					</td>
					<td>
						<input type="button" id="tab2" name="tab2" class="right_switch" value="View Checkpoints" style="width:250px"/>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear"/>
	
	<div class="content">
	
	<div id="tab1_content" class="tab_content hide">
	<table class="data" width="100%">
    
    <tbody>
    <tr>
        <td colspan="10">
            <span id="samename" style="display:none;">Checkpoint Name already exists.</span>            
            <span id="checkpointname" style="display:none;">Please enter a Checkpoint Name.</span>
            <span id="radius" style="display:none;">Please enter a Radius.</span>
            <span id="latlong" style="display:none;">Please locate and place a checkpoint.</span>
        </td>
    </tr>     
	 <tr>
        <td>Name</td>
        <td>
            <input type="text" name="chkN" id="chkN"  autofocus>
            <span class="mandatory">*</span>
        </td>
        <td>Address</td>
        <td><input type="text" name="chkA" id="chkA" ></td>
        <td>Road</td>
        <td><input type="text" name="chkRN" id="chkRN" ></td>
        <td>Town</td>
        <td><input type="text" name="chkT" id="chkT"></td>
        <td>City</td>
        <td><input type="text" name="chkC" id="chkC" ></td>
    </tr>
    <tr>
        <td>State</td>
        <td><input type="text" name="chkS" id="chkS" ></td>
        <td>Zipcode</td>
        <td><input type="text" name="chkZC" id="chkZC" ></td>
        <td colspan="2"><input type="button" value="Locate" onclick="locate();"></td>
        <td id = "chkRadTd" style="display: none;" colspan="2">Radius</td>
        <td id = "chkRadField" style="display: none;" colspan="2">
            <input type="text" name="chkRad" id="chkRad" >
        </td>
		<td colspan="4"></td>
		
		
    </tr>
    <tr>
        <td colspan="10">
		<input type="button" value="Clear" onclick="clearfields()">
            <input type="hidden" id="cgeolat" name="cgeolat" required>
            <input type="hidden" id="cgeolong" name="cgeolong" required>
            <input type="button" value="Create" onclick="submitcheckpoint();">&nbsp;
            
        </td>
    </tr>
    </tbody>
</table>
<div id="map" style="width:inherit; height:inherit;"></div>		
			
		</div>
		<div id="tab2_content" class="tab_content hide">
			
			<p>
				<?php $checkobj->showAllchkpfInfo();?>
			</p>
			
		</div>
		

</div>



<?php 





$page -> displayPageBottom();
?>