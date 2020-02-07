<?php

class basic_page
{
  var $page_keywords;
  var $page_description;
  var $page_title;
  var $active_button;  // The active button for navagation (navagation section)
  var $inner_nav; // The active page for navagation
  var $css1; // normally main_style.css which is the style sheet that define the standard elements of all pages.
  var $css2;
  var $css3;
  var $css4;
  var $css5;
  var $css6;
  var $css7;
  var $css8;
  var $css9;
  var $css10;
  var $css11;
  var $css12;
  var $css13;
  var $css14;
  var $css15;
  var $page_style; // this should be used sparingly; Use external style sheets.
  var $ext_java_scripts1; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts2; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts3; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts4; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts5; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts6; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts7; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts8; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts9; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts10; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts11; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts12; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts13; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts14; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts15; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $custom_java_scripts;  /* the <script> tags are already printed.  This if for javascript functions */
  var $body_script; // Add an onLoad script into the <body> tag.  Should be in the form of 'onLoad="javascriptFunction()"'
  var $auth;	//Authentication variable
  var $access_rules;			// It stores the user group & their types in which user needs to be in to access this page (may be all or them or any of them)
  var $access_rules_type;		// any or all , it determines how rules condition should be applied
  var $user_navigation_buttons; // Buttons to display on top of the page
  var $username;		// username to show on top navigation pannel 
  var $button1_name;     // button for navigation pannel
  var $button1_link;     // button link for navigation pannel
  var $button2_name;     // button for navigation pannel
  var $button2_link;     // button link for navigation pannel
  var $sidetab1;
  var $sidetablink1;
  var $sidetab2;
  var $sidetablink2;
  var $sidetab3;
  var $sidetablink3;
  var $sidetab4;
  var $sidetablink4;
  var $sidetab5;
  var $sidetablink5;
  var $sidetab6;
  var $sidetablink6;
  var $sidetab7;
  var $sidetablink7;
  var $sidetab8;
  var $sidetablink8;
  var $sidetab9;
  var $sidetablink9;
  var $sidetab10;
  var $sidetablink10;
  
   function __construct()
   {
	 $this->auth=new Authentication();
   }
  // sets the meta-keywords for the new page
  function setPageKeywords($keywords)
  {
	$this->page_keywords = $keywords;
  }
  
  
  function setAccessRules($rule)
  {
  	$this->access_rules = $rule;
  }
  
  function setAccessRulesType($type)
  {
  	$this->access_rules_type = $type;
  }

  // sets the meta-description for the new page
  function setPageDescription($description)
  {
	$this->page_description = $description;
  }

  // sets the page title for the new page
  function setPageTitle($title)
  {
    $this->page_title = $title;
  }

  // sets the inner nav of selected page
  function setInnerNav($type)
  {
    $this->inner_nav = $type;
  }

  // sets active button for navagation
  function setActiveButton($ab)
  {
	$this->active_button = $ab;
  }
  
  

  // sets imported css.  #1 is the main_style.css
  function setImportCss1($css_1)
  {
    $this->css1 = $css_1;
  }
  
  // sets next css import file
  function setImportCss2($css_2)
  {
    $this->css2 = $css_2;
  }

  // sets next css import file
  function setImportCss3($css_3)
  {
    $this->css3 = $css_3;
  }

  // sets next css import file
  function setImportCss4($css_4)
  {
    $this->css4 = $css_4;
  }

  // sets next css import file
  function setImportCss5($css_5)
  {
    $this->css5 = $css_5;
  }
  
  function setImportCss6($css_6)
  {
    $this->css6 = $css_6;
  }
  
  function setImportCss7($css_7)
  {
    $this->css7 = $css_7;
  }
  
  function setImportCss8($css_8)
  {
    $this->css8 = $css_8;
  }
  
  function setImportCss9($css_9)
  {
    $this->css9 = $css_9;
  }
  
  function setImportCss10($css_10)
  {
    $this->css10 = $css_10;
  }
  
  function setImportCss11($css_11)
  {
    $this->css11 = $css_11;
  }
  
  function setImportCss12($css_12)
  {
    $this->css12 = $css_12;
  }
  
  function setImportCss13($css_13)
  {
    $this->css13 = $css_13;
  }
  
  function setImportCss14($css_14)
  {
    $this->css14 = $css_14;
  }
  
  function setImportCss15($css_15)
  {
    $this->css15 = $css_15;
  }
  

  // sets any additional css that the page requires
  function setPageStyle($style)
  {
	$this->page_style = $style;
  }

  // sets external java scripts that the page requires
  function setExtJavaScripts1($ext_custom_scripts)
  {
	$this->ext_java_scripts1 = $ext_custom_scripts;
  }

  function setExtJavaScripts2($ext_custom_scripts)
  {
	$this->ext_java_scripts2 = $ext_custom_scripts;
  }

  function setExtJavaScripts3($ext_custom_scripts)
  {
	$this->ext_java_scripts3 = $ext_custom_scripts;
  }

  function setExtJavaScripts4($ext_custom_scripts)
  {
	$this->ext_java_scripts4 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts5($ext_custom_scripts)
  {
	$this->ext_java_scripts5 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts6($ext_custom_scripts)
  {
	$this->ext_java_scripts6 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts7($ext_custom_scripts)
  {
	$this->ext_java_scripts7 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts8($ext_custom_scripts)
  {
	$this->ext_java_scripts8 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts9($ext_custom_scripts)
  {
	$this->ext_java_scripts9 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts10($ext_custom_scripts)
  {
	$this->ext_java_scripts10 = $ext_custom_scripts;
  }

  function setExtJavaScripts11($ext_custom_scripts)
  {
	$this->ext_java_scripts11 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts12($ext_custom_scripts)
  {
	$this->ext_java_scripts12 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts13($ext_custom_scripts)
  {
	$this->ext_java_scripts13 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts14($ext_custom_scripts)
  {
	$this->ext_java_scripts14 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts15($ext_custom_scripts)
  {
	$this->ext_java_scripts15 = $ext_custom_scripts;
  }	
   function setExtJavaScripts16($ext_custom_scripts)
  {
	$this->ext_java_scripts16 = $ext_custom_scripts;
  }	
   function setExtJavaScripts17($ext_custom_scripts)
  {
	$this->ext_java_scripts17 = $ext_custom_scripts;
  }
   function setExtJavaScripts18($ext_custom_scripts)
  {
	$this->ext_java_scripts18 = $ext_custom_scripts;
  }
   function setExtJavaScripts19($ext_custom_scripts)
  {
	$this->ext_java_scripts19 = $ext_custom_scripts;
  }
   function setExtJavaScripts20($ext_custom_scripts)
  {
	$this->ext_java_scripts20 = $ext_custom_scripts;
  }
   function setExtJavaScripts21($ext_custom_scripts)
  {
	$this->ext_java_scripts21 = $ext_custom_scripts;
  }
   function setExtJavaScripts22($ext_custom_scripts)
  {
	$this->ext_java_scripts22 = $ext_custom_scripts;
  }
   function setExtJavaScripts23($ext_custom_scripts)
  {
	$this->ext_java_scripts23 = $ext_custom_scripts;
  }
   function setExtJavaScripts24($ext_custom_scripts)
  {
	$this->ext_java_scripts24 = $ext_custom_scripts;
  }
  // sets internal java scripts functions that the page requires
  function setCustomJavaScripts($custom_scripts)
  {
	$this->custom_java_scripts = $custom_scripts;
  }

  // sets any onLoad javascripts in <body> tag
  function setBodyScript($script)
  {
	$this->body_script = ' '.$script;
  }


  function displayPageTop()
  {
    $this->printDocType();
	$this->printHTMLStart();
	$this->printHeadStart();
	$this->printCharEncod();
	$this->printTitle();
	$this->printMetaAuthor();
	$this->printMetaKeywords();
    $this->printMetaDesc();
	$this->printFOUC();
	$this->printMainStyle();
	$this->printPageStyle();
	//$this->printExtJavaScripts();
	//$this->printCustomJavaScripts();
	$this->printHeadEnd();
	$this->printBodyStart();
	$this->printHeader();
	$this->printSidebar();
	$this->printContentAreaStart();
  }
  
  function displayPageTopLogin()
  {
    $this->printDocType();
	$this->printHTMLStart();
	$this->printHeadStart();
	$this->printCharEncod();
	$this->printTitle();
	$this->printMetaAuthor();
	$this->printMetaKeywords();
    $this->printMetaDesc();
	$this->printFOUC();
	$this->printMainStyle();
	$this->printPageStyle();
	//$this->printExtJavaScripts();
	//$this->printCustomJavaScripts();
	$this->printHeadEnd();
	$this->printBodyStart();
  }
  
  function displayPrintPageTop()
  {
    $this->printDocType();
	$this->printHTMLStart();
	$this->printHeadStart();
	$this->printCharEncod();
	$this->printTitle();
	$this->printMetaAuthor();
	$this->printMetaKeywords();
    $this->printMetaDesc();
	$this->printFOUC();
	$this->printMainStyle();
	$this->printPageStyle();
	$this->printExtJavaScripts();
	$this->printCustomJavaScripts();
	$this->printHeadEnd();
	//$this->printBodyStart();
	//$this->printHeader();
	//$this->printSidebar();
	//$this->printContentAreaStart();
  }

  function displayPageBottom()
  {
  	$this->printContentAreaEnd();
	//$this->printInfoColumnEnd();
	$this->printFooter();
	
	$this->printGoogAna();
	$this->printBodyEnd();
	$this->printHTMLEnd();
	$this->printExtJavaScripts();
	$this->printCustomJavaScripts();
  }

  // display functions

  function printDoctype()
  {
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
  }
  
  function printHTMLStart()
  {
    echo '
<html>';
  }
  
  function printHeadStart()
  {
    echo '
<head>';
  }
  
  function printCharEncod()
  {
    echo '
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
  }
  
  function printTitle()
  {
    echo '
<title>'.$this->page_title.'</title>';
  }

  function printMetaAuthor()
  {
    echo '
<meta name="description" content="">';
  }
  
  function printMetaKeywords()
  {
    echo '
<meta name="keywords" content="'.$this->page_keywords.'" />';
  }

  function printMetaDesc()
  {
    echo '
<meta name="description" content="'.$this->page_description.'" />';
  }
    
  function printFOUC() // stops unstyled html from appear.  May be obsolete now.
  {
    echo '
<script type="text/javascript"></script>';
  }
  
  function printExtJavaScripts()
  {
     if ( !empty($this->ext_java_scripts) )
	 {
     	echo $this->ext_java_scripts;
     }
     if ( !empty($this->ext_java_scripts1) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts1.'"></script>';
     }
     if ( !empty($this->ext_java_scripts2) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts2.'"></script>';
     }
     if ( !empty($this->ext_java_scripts3) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts3.'"></script>';
     }
     if ( !empty($this->ext_java_scripts4) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts4.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts5) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts5.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts6) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts6.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts7) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts7.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts8) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts8.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts9) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts9.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts10) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts10.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts11) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts11.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts12) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts12.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts13) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts13.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts14) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts14.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts15) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts15.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts16) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts16.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts17) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts17.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts18) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts18.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts19) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts19.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts20) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts20.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts21) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts21.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts22) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts22.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts23) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts23.'"></script>';
     }
	 if ( !empty($this->ext_java_scripts24) )
	 {
     	echo '<script language="javascript" type="text/javascript" src="'.$this->ext_java_scripts24.'"></script>';
     }
  }
 
  function printCustomJavaScripts()
  {
     if ( !empty($this->custom_java_scripts) )
	 {
     echo '
<script language="javascript" type="text/javascript">'.
$this->custom_java_scripts.'
</script>';
     }
  }
  
  function printMainStyle()
  {
	// the first css are for the drop down navigation on the home page
	echo '
<style type="text/css" media="all">';
    if ( !empty($this->css1) ) echo '@import url('.$this->css1.');';
    if ( !empty($this->css2) ) echo '@import url('.$this->css2.');';
    if ( !empty($this->css3) ) echo '@import url('.$this->css3.');';
    if ( !empty($this->css4) ) echo '@import url('.$this->css4.');';
    if ( !empty($this->css5) ) echo '@import url('.$this->css5.');';
	if ( !empty($this->css6) ) echo '@import url('.$this->css6.');';
	if ( !empty($this->css7) ) echo '@import url('.$this->css7.');';
	if ( !empty($this->css8) ) echo '@import url('.$this->css8.');';
	if ( !empty($this->css9) ) echo '@import url('.$this->css9.');';
	if ( !empty($this->css10) ) echo '@import url('.$this->css10.');';
	if ( !empty($this->css11) ) echo '@import url('.$this->css11.');';
	if ( !empty($this->css12) ) echo '@import url('.$this->css12.');';
	if ( !empty($this->css13) ) echo '@import url('.$this->css13.');';
	if ( !empty($this->css14) ) echo '@import url('.$this->css14.');';
	if ( !empty($this->css15) ) echo '@import url('.$this->css15.');';
    echo '
</style>';
  }
  
  function printPageStyle()
  {
    if ( !empty($this->page_style) )
	{
    	echo '
<style type="text/css">'.
$this->page_style.'
</style>';
     }
  }

  function printHeadEnd()
  {
    echo '
</head>';
  }

  function printBodyStart()
  {
    echo '
<body'.$this -> body_script.'>
<div class="content_wrapper">
'; 
  }

  function printHeader()
  {
	echo '
	<div id="header">
		<div id="logo"  style="vertical-align:middle">
			<b><img src="images/one.jpg" /></b>
		</div>
		
		<div id="account_info">
		
			<img src="images/icon_online.png" alt="Online" class="mid_align"/>
			Hello <a  href="">'.$_SESSION[user_name].'<span id="branch_status"> | Branch:'.$_SESSION['branchname'].' </span> </a> | <a href="logout.php">Logout</a>
		</div>
		
			
		<div id="notification_main" onclick="notify();">
				<span id="n_count"></span><div  id="notifications" ></div>
               
		</div>	
		<div id="search">
		';
		?>
		
		<?php 
		
		echo '
		</div>
	</div>
	
	';
	
	}
	
	function printSidebar()
	{
		$this->DisplaySidebarMenu();	
	}
	
	function printContentAreaStart()
	{
		echo '
			<div id="content">
			<div id="newmsg" ></div>
				<div class="inner">';
	}
	
	function printContentAreaEnd()
	{
		echo '
</div>
	<br class="clear"/><br class="clear"/>';
	}
 
/*	function printInfoColumnEnd()
	{
		echo '
	</div>';
	}*/
	
	function printFooter()
	{
	echo '
	<div id="footer">
			&copy; Copyright 2013 by <a href="" target="_blank">Elixia tech solution</a>
		</div>
	</div>
</div>';
	}
	
	
  
  function printGoogAna() // google analytics code
  {
	echo '';
  }

  function printBodyEnd()
  {
	echo '
</body>';
  }
  
  function printHTMLEnd()
  {
    echo '
</html>';
  }


  function CheckAuthorization()
  {
  	$this->auth->CheckAuthorization($this->access_rules, $this->access_rules_type);
  }
  
  function UserNavigation()
  {
  	?>
  		<div id="user-nav">
        <ul>
		  <?php if(($this->auth->checkAdminAuthentication())){ ?>
          <li>Logged in as <strong><?php echo 'Administrator'; /*echo $this->username;*/?></strong></li>
		  <?php } ?>
          <li><a href="<?php echo $this->button1_link;?>"><?php echo $this->button1_name;?></a> | </li>
		  <?php if(($this->auth->checkAdminAuthentication())){ ?>
          <li><a href="<?php echo $this->button2_link;?>"><?php echo $this->button2_name;?></a></li>
		  <?php } ?>
        </ul>
      </div>
	 <?php  

  }
  
  function DisplaySidebarMenu()
  {
   $page = basename($_SERVER['PHP_SELF']);
  
 
  	?>
	
	
	<a href="javascript:;" id="show_menu">&raquo; Show Menu</a>
	<div id="left_menu">
		<a href="javascript:;" id="hide_menu">Hide Menu &laquo;</a>
		<ul id="main_menu">
			<li>
		<a  href="javascript:;"  onClick="window.open('masterscreen.php','mywindow','width=1300px,height=900px')"  ><img src="images/master.png" alt="Posts"/>masterscreen</a>
		</li>
			
		<?php  if($_SESSION['page']=="track" || $_SESSION['page']=="home"){
		
		if($_SESSION['istrack']=="true"){
		
		?>	
		
		<li>
		<a href="track.php"><img src="images/maps1.png" alt="Posts"/>Maps</a>
		</li>
		
		<li>
		<a href="routereports.php"><img src="images/pin.png" alt="Posts"/>Route History</a>
		
		</li>                        
				
		
		<?php } 
			
		
		}else if($_SESSION['page']=="service") { 
		
		if($_SESSION['isservice']=="true" || $_SESSION['page']=="home"){
		
		
		?>
			<li><a href="home.php"><img src="images/icon_home.png" alt="Home"/>Dashboard</a></li>                        
		<li>
				<a href=""><img src="images/icon_service.png" alt="Posts"/>Service call</a>
				<ul>
					<li><a href="servicecalls.php?index=add">Add new</a></li>
					<li><a href="servicecalls.php">View and Edit</a></li>
					<li><a href="servicecalls.php?index=search">Search</a></li>
				</ul>	
			</li>	
			<?php if($_SESSION['user_type']!="Manager"){
			?>
			
			<li>
				<a href=""><img src="images/line_chart.png" alt="Posts"/>Reports</a>
				<ul>
					
					<li><a href="servicecalls.php?index=servicereport">Service Report</a></li>
					<li><a href="servicecalls.php?index=customerreport">Customer Report</a></li>
					<li><a href="servicecalls.php?index=therapistreport">Therapist Report</a></li>
					<li><a href="servicecalls.php?index=feedbackreport">feedback Report</a></li>
				</ul>
			</li>
			<?php }?>
			
			<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master" || $_SESSION['user_type']=="Manager"|| $_SESSION['user_type']=="Supervisor"){?>
			<li>
				<a href=""><img src="images/servicelist.png" alt="Posts"/>Service list</a>
				<ul>
				<?php  if($_SESSION['user_type']!="Manager" && $_SESSION['user_type']!="Supervisor" ) {?>
					<li><a href="service_list.php?index=add">Add new</a></li>
				<?php  } ?>
					<li><a href="service_list.php">View and Edit</a></li>
					<li><a href="service_list.php?index=search">Search</a></li>
				</ul>	
			</li>
			<li>
				<a href=""><img src="images/icon_feedback.png" alt="Posts"/>Feedback</a>
				<ul>
				<?php if($_SESSION['user_type']!="Manager" && $_SESSION['user_type']!="Supervisor") {?>
					<li><a href="feedback.php?index=add">Add new</a></li>
					<?php } ?>
					<li><a href="feedback.php">View and Edit</a></li>
					<li><a href="feedback.php?index=search">Search</a></li>
				</ul>	
			</li>
			
			<li>
				<a href=""><img src="images/iconremarks.png" alt="Posts"/>Remarks</a>
				<ul>
				<?php if($_SESSION['user_type']!="Manager" && $_SESSION['user_type']!="Supervisor") {?>
					<li><a href="remarks.php?index=add">Add new</a></li>
					<?php } ?>
					<li><a href="remarks.php">View and Edit</a></li>
					<li><a href="remarks.php?index=search">Search</a></li>
				</ul>	
			</li>
			
			<li>
				<a href=""><img src="images/icon_student.png" alt="Posts"/>Client Details</a>
				<ul>
				<?php if($_SESSION['user_type']!="Manager" && $_SESSION['user_type']!="Supervisor") {?>
					<li><a href="client_details.php?index=add">Add new</a></li>
					<?php } ?>
					<li><a href="client_details.php">View and Edit</a></li>
					<li><a href="client_details.php?index=search">Search</a></li>
				</ul>	
			</li>
			
			
			<?php if(isset($_SESSION['SerUserField1'])){?>
			<li>
				<a href=""><img src="images/iconproduct1.png" alt="Posts"/><?php echo $_SESSION['SerUserField1'];?> Details</a>
				<ul>
				<?php if($_SESSION['user_type']!="Manager" && $_SESSION['user_type']!="Supervisor") {?>
					<li><a href="product_details1.php?index=add">Add new</a></li>
					<?php } ?>
					<li><a href="product_details1.php">View and Edit</a></li>
					<li><a href="product_details1.php?index=search">Search</a></li>
				</ul>	
			</li>
			<?php } ?>
			<?php if(isset($_SESSION['SerUserField2'])){?>
			<li>
				<a href=""><img src="images/iconproduct2.png" alt="Posts"/><?php echo $_SESSION['SerUserField2'];?> Details</a>
				<ul>
				<?php if($_SESSION['user_type']!="Manager" && $_SESSION['user_type']!="Supervisor") {?>
					<li><a href="product_details2.php?index=add">Add new</a></li>
					<?php } ?>
					<li><a href="product_details2.php">View and Edit</a></li>
					<li><a href="product_details2.php?index=search">Search</a></li>
				</ul>	
			</li>
			<?php } ?>
			
			
			
			
			<?php }?>
			<?php  } 
                        
                        
                        }
                        ?>	
			
			<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master" || $_SESSION['user_type']=="Manager" ||$_SESSION['user_type']=="Supervisor"){?>
			
			<li>
				<a href=""><img src="images/trackee.png" alt="Users"/>Trackee</a>
				<ul>
					<?php if($_SESSION['user_type']!="Manager" && $_SESSION['user_type']!="Supervisor") {?>
					
					<li><a href="createtrackee.php?index=add">Add trackee</a></li>
					<?php } ?>
					<li><a href="createtrackee.php">View all</a></li>
					
					
					
				</ul>	
			</li>
			
			<?php if($_SESSION['user_type']!="Manager" && $_SESSION['user_type']!="Supervisor") {?>
			<li>
				<a href=""><img src="images/icon_users.png" alt="Users"/>Users</a>
				<ul>
					
					<li><a href="createUser.php">Add new user</a></li>
					<li><a href="editUser.php">Edit user</a></li>
					
				</ul>	
			</li>
			<?php }?>
			<?php }?>
			<?php
			if($_SESSION['isservice']=="true" || $_SESSION['page']=="home"){
			if($_SESSION['use_forms']=='1'){
			
			 ?>
			<li>
				<a href=""><img src="images/checklist.png" alt="Posts"/>checklist</a>
				<ul>
					<li><a href="checklist.php?index=add">Add new</a></li>
					<li><a href="checklist.php">View and Edit</a></li>
					
				</ul>	
			</li>
			
			<?php } } ?>
			
			
			
			
			<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){?>
			<?php 
			if($_SESSION['isservice']=="true" || $_SESSION['page']=="home"){
			 ?>
			<li>
				<a href=""><img src="images/discount.png" alt="Posts"/>discount</a>
				<ul>
					<li><a href="discount.php?index=add">Add new</a></li>
					<li><a href="discount.php">View and Edit</a></li>
					<li><a href="discount.php?index=search">Search</a></li>
				</ul>	
			</li>
			
			<?php } ?>
			<li>
				<a href=""><img src="images/settings.png" alt="Users"/>Settings</a>
				<ul>
					<li><a href="ChangePassword.php">change password</a></li>
					<li><a href="mapdevices.php">Device manager</a></li>
					<li><a href="settingsvars.php">customize</a></li>
                                <li><a href="alerts.php">alerts</a></li>
					
				</ul>	
			</li>
			<?php  } ?>
		</ul>
		<br class="clear"/>
		
		<!-- Begin left panel calendar -->
		<div id="calendar"></div>
		<!-- End left panel calendar -->
		<div class="jclock" style="width: 210px;"></div>	
		
	</div>
	<?php 
  }

}
?>