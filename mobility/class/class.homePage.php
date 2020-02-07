<?php
 /***********************************************************************************

Class Discription : This class will handle the creation and modification
					of User.
************************************************************************************/

class HomePage{
	
	 var $user_id;
	 var $first_name;
	 var $last_name;
	 var $email_id;
	 var $country_code;
	 var $area_code;
	 var $phone_no;
	 var $agree;
	 var $password;
	 var $db;
	 var $validity;
	 var $Form;
	 var $new_pass;
	 var $confirm_pass;
	 var $auth;
	 var $temppass;
	 var $mail_obj;
	 var $template;
	 private $adminuser;
	 private $adminpassword;
	 
	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		$this->auth=new Authentication();
	}
	
	function DisplayshortCutMent($headding)
	{
	//print_r($_SESSION);
		?>

			<h1><?php echo $headding;?></h1>
			
			<!-- Begin shortcut menu -->
			<ul id="shortcut">
    		<!--	<li>
    			  <a href="home.php" id="shortcut_home" title="Click me to open Home Page" class="help">
				    <img src="images/shortcut/home.png" alt="home"/><br/>
				    <strong>Home</strong>
				  </a>
				</li>-->
				<?php if($_SESSION['istrack']=="true"){ ?>
    			<li>
    			  <a href="track.php" title="Click me to open track" class="help">
				    <img src="images/icon_track.png" alt="track"/><br/>
				    <strong>Track</strong>
				  </a>
				</li>
					<?php }?>
					<?php if($_SESSION['isservice']=="true"){ ?>
				<li>
    			  <a href="servicecalls.php" title="Click me to open service calls" class="help">
				    <img src="images/service.png" alt="track"/><br/>
				    <strong>Service</strong>
				  </a>
				</li>
					<?php }?>
    	
				
				
				
				
				
  			</ul>
			<!-- End shortcut menu -->
			<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){
			$sql="select * from ".BRANCH." where customerno = ".$_SESSION['customerno'];
			$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
			 ?>
			<div id="branch">
			<select name="branch" id="branchbox" onchange="branch_change();"> 
			<option value="all" <?php if($_SESSION['branchid']=="all"){echo "selected";} ?>>all</option>
			<?php 
			while($row= $this->db->fetch_array($resultpages))
			{
					
			?>
			<option value="<?php echo $row['branchid'];?>" <?php if($_SESSION['branchid']== $row['branchid']){echo "selected";} ?>><?php echo $row['branchname'];?></option>
			<?php } ?>
			</select>
			</div>
			<script type="text/javascript">
			function branch_change(){
			
  			var e = document.getElementById("branchbox");
            var strOption = e.options[e.selectedIndex].value;
			var strkey = e.options[e.selectedIndex].text;
  			
  			
    
	
			jQuery.ajax({
                        type:"GET",
                        url:"ajaxpulls.php?work=17&branchid="+strOption,
                        async:true,
                        cache:false,
                        success:function(data){
                        // alert ("branch changed ");
						 jQuery("#branch_status").html(" | Branch:"+strkey.toUpperCase()); 
						 window.location.reload();
                        },
                        error:function(XMLHttpRequest,testStatus,errorThrown){



                       }
                    });
			
			}
			
			</script>
			<?php  } ?>
			<div id="panic" >
	
					<div  id="panic_header"> panic <div  id="panic_close" onclick="delete_panic();" ><img src="images/close.png"  /></div> </div>
					<div   id="panic_body">
					<ul id="panic_ul">
					
					
					
					</ul>
					
					
					</div>
					
					
					
					
					</div>
			
			<div id="notif-list" > 
			<table id="table_notif">
			<tbody id="notif-body">
			
			</tbody>
			</table>
			
			
			</div>
			<br class="clear"/>
                        
                        
		<?php 
		
	}
	
	function shortcutNotification()
	{
		?>
		<!-- Begin shortcut notification -->
			<!--<div id="shortcut_notifications">
				<span class="notification" rel="shortcut_home">10</span>
				<span class="notification" rel="shortcut_contacts">5</span>
				<span class="notification" rel="shortcut_posts">1</span>
			</div>-->
			<!-- End shortcut noficaton -->
		<?php 
	}
	
	function DisplaysubMenu($headding='')
	{
		?>
			<h1><?php echo $headding;?></h1>
			
			<!-- Begin shortcut menu -->
			<!--<ul id="shortcut">
    			<li>
    			  <a href="courses.php" id="shortcut_home" title="Click me to open Course Master" class="help">
				    <img src="images/shortcut/course.png" alt="Course"/><br/>
				    <strong>Courses</strong>
				  </a>
				</li>
    			<li>
    			  <a href="source_of_info.php" title="Click me to open Source of Info Master" class="help">
				    <img src="images/shortcut/sourse0finfo.png" alt="Sourse-of-info"/><br/>
				    <strong>Source</strong>
				  </a>
				</li>
				<li>
    			  <a href="college_info.php" title="Click me to open College Master" class="help">
				    <img src="images/shortcut/college.png" alt="Colleges"/><br/>
				    <strong>Colleges</strong>
				  </a>
				</li>
				<li>
    			  <a href="fees_email.php" title="Click me to open Fee email Master" class="help">
				    <img src="images/shortcut/email2.png" alt="Seminars"/><br/>
				    <strong>Fees Email</strong>
				  </a>
				</li>
  			</ul>-->
			<!-- End shortcut menu -->
			<br class="clear"/>
		<?php 
	}
	
	
}

?>