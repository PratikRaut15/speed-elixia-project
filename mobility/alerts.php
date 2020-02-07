<?php

require_once("class/config.inc.php");
require_once("class/class.homePage.php");
require_once("class/class.alerts.php");
require_once("class/class.Authentication.php");
$_SESSION['page']="home";
$ajax=new PHPLiveX();







$page = new basic_page();
$homepage = new HomePage();
$notify = new Notification();
$auth =new Authentication();
$alrt = new Alerts();
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
$page -> setExtJavaScripts17('');
$page -> setExtJavaScripts18('js/prototype.js');
$page -> setExtJavaScripts19('js/alerts.js');

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

<div ><?php $notify->Notify();?></div>
<pre>
<?php


 $catch=$alrt->getdata_alerts();
 
 ?>
</pre>

<br class="clear" />
<div class="onecolumn">
	<div class="header">
		<span>depart sms alerts</span>
		
	</div>
	<br class="clear"/>
	<div class="content">
            <div>
                <form>
                    <table class="data" width="100%">
                        <tr><th>options</th><td colspan="2">
                                <select id="insertoptions"><option value="###Trackeename###">trackee</option>
                                    <option value="###Clientname###">client</option>
									<option value="###Services###">services</option>
									<option value="###Finalbill###">finalbill</option>
									<option value="###Discount###">discount</option>
								
									
									
									
									</select>&nbsp;&nbsp;<input type="button" value="Insert" name="insert" onclick="clickinsertsms();" ></td></tr>
                        
                        <tr><th>Msg</th><td><textarea id="msg" name="msg" rows="3" cols="50" ><?php echo $catch['departsms'];?></textarea></td></tr>
                        
                        <tr><th></th><td><input type="button" value="Update" name="update" onclick="submitsmsdata();"></td></tr>
                        
                    </table>
                    
                    
                    
                </form>
                
                
                
                
                
            </div>
	
	
	
	
	
	
	
	</div>
		
</div>

<div class="onecolumn">
	<div class="header">
		<span>depart Email alerts</span>
		
	</div>
	<br class="clear"/>
	<div class="content">
            <div>
                <form>
                    <table class="data" width="100%">
                        <tr><th>options</th><td colspan="2">
                                <select id="insertoptions2"><option value="###Trackeename###">trackee</option>
                                     <option value="###Clientname###">client</option>
									<option value="###Services###">services</option>
									<option value="###Finalbill###">finalbill</option>
									<option value="###Discount###">discount</option>
								
									</select>&nbsp;&nbsp;<input type="button" value="Insert" name="insert" onclick="clickinsertsms2();" ></td></tr>
                        <tr><th>Subject</th><td><input type="text" id="subject2" name="subject2" size="40" value="<?php echo $catch['departemailh']; ?>" /></td></tr>
                        <tr><th>Msg</th><td><textarea id="msg2" class="wysiwyg" name="msg2" rows="5" cols="150" ><?php echo $catch['departemailm']; ?></textarea></td></tr>
                        
                        <tr><th></th><td><input type="button" value="Update" name="update" onclick="submitsmsdata2();"></td></tr>
                        
                    </table>
                    
                    
                    
                </form>
                
                
                
                
                
            </div>
	
	
	
	
	
	
	
	</div>
		
</div>
<div class="onecolumn">
	<div class="header">
		<span>Thankyou alerts</span>
		
	</div>
	<br class="clear"/>
	<div class="content">
            <div>
                <form>
                    <table class="data" width="100%">
                        <tr><th>options</th><td colspan="2">
                                <select id="insertoptions3"><option value="###Trackeename###">trackee</option>
                                     <option value="###Clientname###">client</option>
									<option value="###Services###">services</option>
									<option value="###Finalbill###">finalbill</option>
									<option value="###Discount###">discount</option></select>&nbsp;&nbsp;<input type="button" value="Insert" name="insert" onclick="clickinsertsms3();" ></td></tr>
                        
                        <tr><th>Msg</th><td><textarea id="msg3" name="msg3" rows="3" cols="50" ><?php echo $catch['thanksms'];?></textarea></td></tr>
                        
                        <tr><th></th><td><input type="button" value="Update" name="update" onclick="submitsmsdata3();"></td></tr>
                        
                    </table>
                    
                    
                    
                </form>
                
                
                
                
                
            </div>
	
	
	
	
	
	
	
	</div>
		
</div>
<div class="onecolumn">
	<div class="header">
		<span>thankyou email alerts</span>
		
	</div>
	<br class="clear"/>
	<div class="content">
            <div>
                <form>
                    <table class="data" width="100%">
                        <tr><th>options</th><td colspan="2">
                                <select id="insertoptions4"><option value="###Trackeename###">trackee</option>
                                     <option value="###Clientname###">client</option>
									<option value="###Services###">services</option>
									<option value="###Finalbill###">finalbill</option>
									<option value="###Discount###">discount</option>
									<option value="###Feedback###">feedback</option>
									</select>&nbsp;&nbsp;<input type="button" value="Insert" name="insert" onclick="clickinsertsms4();" ></td></tr>
                        <tr><th>Subject</th><td><input type="text" id="subject4" name="subject4" size="40" value="<?php echo $catch['thankemailh'];?>" /></td></tr>
                        <tr><th>Msg</th><td><textarea id="msg4" class="wysiwyg" name="msg4" rows="5" cols="150" ><?php echo $catch['thankemailm'];?></textarea></td></tr>
                        
                        <tr><th></th><td><input type="button" value="Update" name="update" onclick="submitsmsdata4();"></td></tr>
                        
                    </table>
                    
                    
                    
                </form>
                
                
                
                
                
            </div>
	
	
	
	
	
	
	
	</div>
		
</div>
<div class="onecolumn">
	<div class="header">
		<span>Panic sms</span>
		
	</div>
	<br class="clear"/>
	<div class="content">
            <div>
                <form>
                    <table class="data" width="100%">
                        <tr><th>options</th><td colspan="2">
                                <select id="insertoptions5">
								<option value="###Trackeename###">trackee</option>
                                     
								
									</select>&nbsp;&nbsp;<input type="button" value="Insert" name="insert" onclick="clickinsertsms5();" ></td></tr>
                        <tr><th>Number</th><td><input type="text" id="subject5" name="subject5" size="40" value="<?php echo $catch['panich'];?>" /></td></tr>
                        <tr><th>Msg</th><td><textarea id="msg5" name="msg5" rows="5" cols="150" ><?php echo $catch['panicm'];?></textarea></td></tr>
                        
                        <tr><th></th><td><input type="button" value="Update" name="update" onclick="submitsmsdata5();"></td></tr>
                        
                    </table>
                    
                    
                    
                </form>
                
                
                
                
                
            </div>
	
	
	
	
	
	
	
	</div>
		
</div>
<?php 





$page -> displayPageBottom();
?>