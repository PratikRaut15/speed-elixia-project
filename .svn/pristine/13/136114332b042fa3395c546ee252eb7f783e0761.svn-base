<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.alerts.php");
require_once("class/class.trackee.php");
$_SESSION['page']="track";
$ajax=new PHPLiveX();

$page = new basic_page();
$homepage = new HomePage();
$notify = new Notification();
$alrt = new Alerts();
$trackeeobj = new trackee();
$page->auth->CheckAdminlogin();

$ajax->AjaxifyObjects(array("alrt"));  

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
$page -> setExtJavaScripts15('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
$page -> setExtJavaScripts16('');
$page -> setExtJavaScripts17('js/routehist.js');
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

$homepage->DisplayshortCutMent('');

$homepage->shortcutNotification();

$ajax->Run('liveX/phplivex.js');
extract($_REQUEST);
?>
<div><?php $notify->Notify();?></div>



<br class="clear" />
<div class="onecolumn">
	<div class="header">
		<span>Route History</span>
		
	</div>
	<br class="clear"/>
	<div class="content">
	
<table class="data" width="100%">
<thead>
<tr>
<th id="formheader" colspan="100%">Route History</th>
</tr>
</thead>
<tbody>
<tr>
<td colspan="100%">
<span id="error" name="error" style="display: none;">Already in progress. Please Refresh to start again</span>
<span id="error2" name="error2" style="display: none;">Data Not Available</span>
<span id="error3" name="error3" style="display: none;">Please Check The Dates</span>
</td>
</tr>
<tr>
<td>
<?php 
    $trackeeobj->selectmappedtrackees();
?>
</td>
<td>Start Date</td>
<td>
<input id="SDate" name="SDate" type="text"   class="datepicker1" value="<?php echo date("Y-m-d");?>" required/>
</td>
<td></td>
<td>Start Hour</td>
<td>
<select name='Shour' id='Shour'>
<option value ='00'>00</option><option value ='01'>01</option><option value ='02'>02</option><option value ='03'>03</option><option value ='04'>04</option><option value ='05'>05</option><option value ='06'>06</option><option value ='07'>07</option><option value ='08'>08</option><option value ='09'>09</option><option value = '10'> 10</option><option value = '11'> 11</option><option value = '12'> 12</option><option value = '13'> 13</option><option value = '14'> 14</option><option value = '15'> 15</option><option value = '16'> 16</option><option value = '17'> 17</option><option value = '18'> 18</option><option value = '19'> 19</option><option value = '20'> 20</option><option value = '21'> 21</option><option value = '22'> 22</option><option value = '23'> 23</option>    </select>
</td>
<td>End Date</td>
<td>
<input id="EDate" name="EDate" type="text" class="datepicker2" value="<?php echo date("Y-m-d");?>" required/>
</td>
<td></td>
<td>End Hour</td>
<td>
<select name='Ehour' id='Ehour'>
<option value ='00'>00</option><option value ='01'>01</option><option value ='02'>02</option><option value ='03'>03</option><option value ='04'>04</option><option value ='05'>05</option><option value ='06'>06</option><option value ='07'>07</option><option value ='08'>08</option><option value ='09'>09</option><option value = '10'> 10</option><option value = '11'> 11</option><option value = '12'> 12</option><option value = '13'> 13</option><option value = '14'> 14</option><option value = '15'> 15</option><option value = '16'> 16</option><option value = '17'> 17</option><option value = '18'> 18</option><option value = '19'> 19</option><option value = '20'> 20</option><option value = '21'> 21</option><option value = '22'> 22</option><option value = '23'> 23</option><option selected = 'selected' value = '24'>24</option>        </select>
</td>
</tr>
<tr>
<td colspan="100%" align="center"><input type="button" value="Submit" name="submit" onclick="getroutehist();">&nbsp;<input type="button" value="Refresh" name="Refresh" onclick="refreshrh();"></td>
</tr>
<tr></tr>
</tbody>
</table>

<div id="map" style="width:1280px; height:500px;"></div>
</div>
</div>



<?php 





$page -> displayPageBottom();
?>


