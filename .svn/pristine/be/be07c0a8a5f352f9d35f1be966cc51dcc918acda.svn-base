<?php
 /***********************************************************************************

Class Discription : This class will handle the creation and modification
					of User.
************************************************************************************/

class User{
	
	 var $userid;
	 var $user;
	 var $type;
	 var $password;
	 var $db;
	 var $validity;
	 var $Form;
	 var $new_pass;
	 var $confirm_pass;
	 var $auth;
	 var $adminuser;
	 var $branch;
	 
	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		$this->auth=new Authentication();
		$this->dataobj=new date();
	}
	

	
	function AdminLogin($runat){
		switch($runat){
			case 'local' :
							if(count($_POST)>0 and $_POST['submit']=='Login'){
								extract($_POST);
								$this->adminuser = $adminuser;
							}
							$FormName = "form_login";
							$ControlNames=array("adminuser"			=>array('adminuser',"''","Please enter User Name","spanadminuser"),
												"adminpassword"			=>array('adminpassword',"''","Please enter Password","spanadminpassword")
												);
	
							$ValidationFunctionName="CheckLoginValidity";
						
							@$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
							echo $JsCodeForFormValidation;
							?>
	
			
			<div id="login_wrapper">
			<div >
			<p align="center">
			<center><font color="#CCCCCC"><h1>	<b>Elixia Mobility</b></h1></font></center>
			
			</p>
			<p>
			<br class="clear" />
			</p>
			</div>
				<div id="login_top_window">
					<img src="images/blue/top_login_window.png" alt="top window"/>
				</div>
				<div id="login_body_window">
				<div class="inner">
				 <h3>Login</h3>
				
					<form method="post" action="" enctype="multipart/form-data" id="" name="<?php echo $FormName ?>" >							
							
							<p>
							<input type="text" name="adminuser" value="<?php echo @$adminuser ;?>" style="width:285px" 
							title="Username" />
							<span id="spanadminuser"></span>
							</p>
									
							<p>
							<input type="password" name="adminpassword" style="width:285px" title="******" value="" />
							<span id="spanadminpassword"></span>
							</p>
							
							<p style="margin-top:50px">		
							<input type="submit" onclick="return"							id="submit" name="submit" value="Login" class="Login" style="margin-right:5px" />
							
							</p>
						
						</form>
					</div>
					
					</div>
				
				<div id="login_footer_window">
					<img src="images/blue/footer_login_window.png" alt="footer window"/>
				</div>
			
				<div id="login_reflect">
					<img src="images/blue/reflect.png" alt="window reflect"/>
				</div>
				
					
			</div>		
							<?php
							break;
			case 'server' :
							
						extract($_POST);
						 $this->adminuser = $adminuser;
						 $this->adminpassword = $adminpassword;
                                               
						//server side validation
						 $return =true;
						if($this->Form->ValidField($this->adminuser,'empty','Please Enter User Name')==false)
							echo $return =false;
						if($this->Form->ValidField($this->adminpassword,'empty','Please Enter Your Password')==false)
							echo $return =false;
						 $return; 
						if($return){
						
							$sql = "select * from ".TBL_ADMIN_USER." where username='".$adminuser."'";
							$record = $this->db->query($sql,__FILE__,__LINE__);
							$row = $this->db->fetch_array($record);
                                                       
							if($this->adminuser == $row['username'] and sha1($this->adminpassword) == $row['password'])
								{
									if($row['status'] == 'block')
									{
									$_SESSION['error_msg']='User is Blocked Please Contact Administrator ...';
									?>
									<script type="text/javascript">
									window.location="index.php";
									</script>
									<?php
									exit();
									}
									else
									{
									$this->userid= $row['userid'];
									$this->groups= $row['role'];
									$this->user_type= $row['role'];
                                    $this->customerno= $row['customerno'];
									$this->branchid= $row['branchid'];
									
									$this->auth->Create_Session($this->adminuser,$this->userid,$this->groups,$this->user_type,$row['customerno'],$row['branchid']);
									$this->branch_name($row['branchid']);
									$this->get_modules();
									$this->lastlogin($this->userid);
									?>
									<script type="text/javascript">
									window.location="index.php";
									</script>
									<?php
									exit();
									}
								}
								else
								{
									$_SESSION['error_msg']='Invalid username or password, please try again ...';
								}
							?>
							<script type="text/javascript">
							window.location="<?php echo $_SERVER['PHP_SELF'] ?>";
							</script>
							<?php
							exit();
							}
							else
							{
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->AdminLogin('local');
							}
						break;
			default : echo 'Wrong Paramemter passed';
		}
	}
	function branch_name($branchid){
	if($branchid!=0 && $branchid!="" && $branchid!="All" ){
		$sql = "select * from ".BRANCH." where branchid=".$branchid." and customerno=".$_SESSION['customerno']."";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($record);
		$_SESSION['branchname']=ucfirst($row['branchname']);
		}else{
		$_SESSION['branchname']="All";
		}
	}
	
	function changePassword($runat)
	{
		switch($runat){
			case 'local' :
							
							$FormName = "frm_changePw";
							$ControlNames=array("oldpassword"			=>array('oldpassword',"''","Please enter User Name","spanoldpassword"),
												"password"			=>array('password',"''","Please enter Password","spanpassword"),
												"repassword"			=>array('repassword',"RePassword","Password Donot Match","spanrepassword",'password')
												);
	
							$ValidationFunctionName="CheckPWValidity";
						
							$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
							echo $JsCodeForFormValidation;
							
							?>
							<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName ?>" >							
							<div class="onecolumn">
							<div class="header">
								<span>Change Password</span>
							</div>
							<br class="clear"/>
							<div class="content">
							<table align="center" cellpadding="0" cellspacing="5">
								<tr>
									<th>Old Password</th>
									<th>:</th>
									<td><input type="password" name="oldpassword" value="" />
									<span id="spanoldpassword"></span></td>
								</tr>
								<tr><td colspan="3">&nbsp;</td></tr>
								<tr>
									<th>New Password</th>
									<th>:</th>
									<td><input type="password" name="password" id="password" value="" />
									<span id="spanpassword"></span></td>
								</tr>
								
								<tr>
									<th>Re-Type Password</th>
									<th>:</th>
									<td><input type="password" name="repassword" id="repassword" value="" />
									<span id="spanrepassword"></span></td>
								</tr>
								<tr>
									<td colspan="2">&nbsp;</td>
									<td><input type="submit"  name="submit" id="submit" onclick="return <?php echo $ValidationFunctionName ?>();" value="Change" /></td>
								</tr>
							</table>
							<div id="chart_wrapper" class="chart_wrapper"></div>
							</div>
							</div>
							</form>
							<?php
							break;
			case 'server' :
							
						extract($_POST);
						
						//server side validation
						$return =true;
						if($this->Form->ValidField($oldpassword,'empty','Please Enter User Name')==false)
							$return =false;
						if($this->Form->ValidField($password,'empty','Please Enter Your Password')==false)
							$return =false;
						if($this->Form->ValidField($repassword,'empty','Password Donot Match')==false)
							$return =false;
						
							
						if($return){
							$sql = "select * from ".TBL_ADMIN_USER." where userid='".$_SESSION['user_id']."'";
							$record = $this->db->query($sql,__FILE__,__LINE__);
							$row = $this->db->fetch_array($record);
							
							if(sha1($oldpassword) == $row['password'])
								{
									$update_sql_array = array();
									$update_sql_array['password'] = sha1($password);
									
									$this->db->update(TBL_ADMIN_USER,$update_sql_array,'userid',$_SESSION['user_id']);
									$_SESSION['msg']='Password Changed Successfully';
									?>
									<script type="text/javascript">
									window.location="home.php";
									</script>
									<?php
									exit();
								}
								else
								{
									$_SESSION['msg']='Old password do not match, please try again ...';
								}
							?>
							<script type="text/javascript">
							window.location="ChangePassword.php";
							</script>
							<?php
							exit();
							}
							else
							{
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->changePassword('local');
							}
						break;
			default : echo 'Wrong Paramemter passed';
		}
	}
	
	function CreateUser($runat)
	{
		switch($runat){
			case 'local':
						
						$FormName = "frm_addUser";
						$ControlNames=array("user"			=>array('user',"''","Please enter Username","span_user"),
											"password"			=>array('password',"Password","Please enter Password","span_password"),
											"repassword"			=>array('repassword',"RePassword","Password Do not match! ","span_repassword",'password'),
											"type"			=>array('type',"''","Please select user type","span_type")
											);

						$ValidationFunctionName="CheckUserValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						?>
							<div class="onecolumn">
							<div class="header">
								<span>Create Tracker</span>
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName;?>" >
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
							<tr>
								
							</tr>
							<tr>
								<th width="30%">Fullname</th>
								<td width="63%"><input type="text" name="realname" value="" />
							  <span id="span_user"></span></td>
							</tr>
							<tr>
								<th width="30%">Username</th>
								<td><input type="text" name="username" value="" />
								<span id="span_user"></span></td>
							</tr>
							<tr>
								<th>Password</th>
								<td><input type="password" name="password" />
								<span id="span_password"></span></td>
							</tr>
							<tr>
								<th>Re-Password</th>
								<td><input type="password" name="repassword" />
								<span id="span_repassword"></span></td>
							</tr>
							<tr>
								<th width="30%">Email</th>
								<td><input type="text" name="email" value="" />
								<span id="span_user"></span></td>
							</tr>
							<tr>
								<th width="30%">Phone</th>
								<td><input type="text" name="phone" value="" />
								<span id="span_user"></span></td>
							</tr>
							<tr>
								<th>Type</th>
								<td>
								<select name="type" id="type" >
								<option value="Administrator">Administrator</option>
								<option value="Manager">Manager</option>
								<option value="Supervisor">Supervisor</option>
								<option value="tracker">Tracker</option>
								<option value="Master">Master</option>
								
								
								</select>
								<span id="span_type"></span></td>
							</tr>
							<tr>
							<th>Branch</th>
								<td>
								
								<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){
								$sql="select * from ".BRANCH." where customerno = ".$_SESSION['customerno'];
								$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
								?>
								
								<select name="branch"  > 
								
								<?php 
								while($row= $this->db->fetch_array($resultpages))
								{
								
								?>
								<option value="<?php echo $row['branchid'];?>" <?php if($_SESSION['branchid']== $row['branchid']){echo "selected";} ?>><?php echo $row['branchname'];?></option>
								<?php } ?>
								</select>
								
								<?php } ?>
								</td>
							</tr>
						<tr>
								<th>&nbsp;</th>
								
								<td width="7%"><input type="submit" name="submit" value="Submit" 
								 id="submit" onclick="return <?php echo $ValidationFunctionName?>();"/></td>
							</tr>
						</table> 
						</form>
						</div>
						</div>
						<?php 
						break;
			case 'server':
							extract($_POST);
							$this->realname=$realname;
							$this->username=$username;
							$this->email=$email;
							$this->phone=$phone;
							$this->username = $username;
							$this->password = $password;
							$this->type = $type;
							$this->branch = $branch;
							
							
							//server side validation
							$return =true;
							if($this->Form->ValidField($username,'empty','User field is Empty or Invalid')==false)
								$return =false;
							if($this->Form->ValidField($password,'empty','Password name field is Empty or Invalid')==false)
								$return =false;	
							if($this->Form->ValidField($type,'empty','Type name field is Empty or Invalid')==false)
								$return =false;
							
							$sql="select * from ".TBL_ADMIN_USER." where username='".$this->username."'";
							$result= $this->db->query($sql,__FILE__,__LINE__);
							if($this->db->num_rows($result)>0)
							{
								$_SESSION['error_msg'] = 'User already exist. Please select another username';
								?>
								<script type="text/javascript">
									window.location = "createUser.php"
								</script>
								<?php
								exit();
							}
								
							if($return){
							
							$insert_sql_array = array();
							$insert_sql_array['realname'] = $this->realname;
							$insert_sql_array['username'] = $this->username;
							$insert_sql_array['email'] = $this->email;
							$insert_sql_array['phone'] = $this->phone;
							$insert_sql_array['password'] = sha1($this->password);
							$insert_sql_array['role'] = $this->type;
							$insert_sql_array['customerno'] = $_SESSION['customerno'];
							$insert_sql_array['branchid'] = $this->branch;
							
							$this->db->insert(TBL_ADMIN_USER,$insert_sql_array);
							
							$_SESSION['msg'] = 'User has been created Successfully';
							
							?>
							<script type="text/javascript">
								window.location = "createUser.php"
							</script>
							<?php
							exit();
							
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->CreateUser.('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
		}
	}
	function showAllUser()
	{
		
		$sql="select * from ".TBL_ADMIN_USER." where customerno=".$_SESSION['customerno']."";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=1;
		?>
		<div class="onecolumn">
				<div class="header">
					<span>All User</span>
					
				<div class="switch" style="width:300px">
						<table width="300px" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:150px"/>
								</td>
								
								<td><a href="createUser.php">
									<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:150px" />
									</a>
								</td>
							</tr>
						</tbody>
						</table>
					</div>	
				</div>
				<br class="clear"/>
				<div class="content">
			<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">  
			<thead>
			<tr>
				<th>S.No.</th>
				<th>User</th>
				<th>Type</th>
				<th>Phone no.</th>
				<th>Email</th>
				<th>Action</th>
			</tr>
			</thead>
			<tbody>
				<?php 		
				while($row= $this->db->fetch_array($result))
				{
				if($row['realname']!="Elixir"){	
				?>
				<tr>
					<td><?php echo $x;?></td>
					<td><?php echo $row['username'];?></td>
					<td><?php echo $row['role'];?></td>
					<td><?php echo $row['phone'];?></td>
					<td><?php echo $row['email'];?></td>
					<td> <a class="help" href="editUser_account.php?userid=<?php echo $row['userid'];?>" title="Edit" >
					<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					
					<?php if($row['status'] != 'block') {?>
					<a href="javascript: void(0);" title="Block " class="help" 
					onclick="javascript: if(confirm('Do u want to Block this User?')) { user.blockUser('<?php echo $row['userid'];?>',{}) }; return false;" >
					<img src="images/icon_block.png" width="15px" height="15px" /></a>
					<?php } 
					else
					{?>
					<a href="javascript: void(0);" title="Un-Block Ures" class="help" 
					onclick="javascript: if(confirm('Do u want to Un-Block this User?')) { user.unblockUser('<?php echo $row['userid'];?>',{}) }; return false;" >
					<img src="images/icon_unblock.png" width="15px" height="15px" /></a>
					<?php }	?>
					</td>
				</tr>			
				<?php 
					$x++;
				}
			
				}
				?>
			</tbody>	
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
			</form>
			</div>
			</div>
		<?php 
	}
	
	function editUser($userid)
	{
	
			
			$this->userid=$userid;
			$FormName = "frm_editUser";
			$ControlNames=array("username"			=>array('username',"''","Please enter Username","span_user"),
								"password"		=>array('password',"Password","Please enter Password","span_password"),
								"repassword"	=>array('repassword',"RePassword","Password do not match","span_repassword",'password'),
								"type"			=>array('type',"''","Please select user type","span_type")
								);
	
			$ValidationFunctionName="CheckeditUserValidity";
		
			$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
			echo $JsCodeForFormValidation;
			
			$sql="select * from ".TBL_ADMIN_USER." where userid='".$userid."'";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result)
			?>
			<form method="post" action="editUser_account.php?index=update_user&userid=<?php echo $this->userid;?>" enctype="multipart/form-data" name="<?php echo $FormName;?>" >
			<table class='data'>
				<tr><td colspan="3"><h2>Edit User</h2></td></tr>
				<tr>
								<th width="30%">Fullname</th>
								<td width="63%"><input type="text" name="realname" value="<?php echo $row['realname']; ?>" />
							  <span id="span_user"></span></td>
							</tr>
							<tr>
								<th width="30%">Username</th>
								<td><input type="text" name="username" value="<?php echo $row['username']; ?>"  />
								<span id="span_user"></span></td>
							</tr>
							<tr>
								<th>Password</th>
								<td><input type="password" name="password" />
								<span id="span_password"></span></td>
							</tr>
							<tr>
								<th>Re-Password</th>
								<td><input type="password" name="repassword" />
								<span id="span_repassword"></span></td>
							</tr>
							<tr>
								<th width="30%">Email</th>
								<td><input type="text" name="email" value="<?php echo $row['email']; ?>"  />
								<span id="span_user"></span></td>
							</tr>
							<tr>
								<th width="30%">Phone</th>
								<td><input type="text" name="phone" value="<?php echo $row['phone']; ?>"   />
								<span id="span_user"></span></td>
							</tr>
					<tr>		
					<th width="30%">type</th>
					<td><select name="type" id="type" >
						<option value="">-Select-</option>
						<option value="Master" <?php if($row['role'] == 'Master') { echo 'selected="selected"';} ?> >Master</option>
						<option value="administrator" <?php if($row['role'] == 'Administrator') { echo 'selected="selected"';} ?> >Administrator</option>
						<option value="Supervisor" <?php if($row['role'] == 'Supervisor') { echo 'selected="selected"';} ?> >Supervisor</option>
						<option value="Manager"  <?php if($row['role'] == 'Manager') { echo 'selected="selected"';} ?> >Manager</option>
						<option value="tracker" <?php if($row['role'] == 'Tracker') { echo 'selected="selected"';} ?> >Tracker</option>
					</select>
					<span id="span_type"></span></td>
				</tr>
				<tr>
					<td>
					
					<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){
					$sql="select * from ".BRANCH." where customerno = ".$_SESSION['customerno'];
					$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
					?>
					
					<select name="branch" id="branchbox" onchange="branch_change();"> 
					
					<?php 
					while($row= $this->db->fetch_array($resultpages))
					{
					
					?>
					<option value="<?php echo $row['branchid'];?>" <?php if($_SESSION['branchid']== $row['branchid']){echo "selected";} ?>><?php echo $row['branchname'];?></option>
					<?php } ?>
				</select>
				
				<?php } ?>
				</td>
				</tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><input type="submit" name="upbtn" value="Update" 
							 onclick="return <?php echo $ValidationFunctionName;?>()"></td>
				</tr>
			</table> 
			</form>
			<?php 
				
	}

	
	function updateUser($userid,$realname,$username,$password,$email,$type,$phone)
	{	
		
		
		
		$update_array = array();
		
		$update_array['realname'] = $realname;
		$update_array['username'] = $username;
		$update_array['password'] = sha1($password);
		$update_array['email'] = $email;
		$update_array['role'] = $type;
		$update_array['phone'] = $phone;
		
		$this->db->update(TBL_ADMIN_USER,$update_array,'userid',$userid);
		
		$_SESSION['msg']='User has been Updated successfully';
		
		?>
		<script type="text/javascript">
			window.location = "editUser.php"
		</script>
		<?php
	
	}
	
	function blockUser($userid)
	{
		ob_start();
		
		$update_array = array();
		$update_array['status'] = 'block';
		
		$this->db->update(TBL_ADMIN_USER,$update_array,'userid',$userid);
		
		$_SESSION['msg']='User has been Blocked successfully';
		
		?>
		<script type="text/javascript">
			window.location = "editUser.php"
		</script>
		<?php
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function unblockUser($userid)
	{
		ob_start();
		
		$update_array = array();
		$update_array['status'] = '';
		
		$this->db->update(TBL_ADMIN_USER,$update_array,'userid',$userid);
		
		$_SESSION['msg']='User has been Un-Blocked successfully';
		
		?>
		<script type="text/javascript">
			window.location = "editUser.php"
		</script>
		<?php
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	function updatedata($type,$msg,$header,$alertname)
        {
		
          
            if($type=="sms")
            {
                 $sql_sms="UPDATE ".TBL_ALERTS." SET alertmsg='".addslashes($msg)."',userid=".$_SESSION['user_id']." WHERE customerno=".$_SESSION['customerno']." and alerttype='sms' and alertname='".$alertname."' ";
                $this->db->query($sql_sms,__FILE__,__LINE__);
               

            }else if($type=="email") {
			
			$at='email';
			if($alertname=="panic"){$at='sms';}
			
              echo $sql_sms="UPDATE ".TBL_ALERTS." SET alertmsg='".addslashes($msg)."',alertsubject='".addslashes($header)."',userid=".$_SESSION['user_id']." WHERE customerno=".$_SESSION['customerno']." and alerttype='".$at."' and alertname='".$alertname."' ";
               
			     $this->db->query($sql_sms,__FILE__,__LINE__);
            }
                 
        }
	function deleteUser($userid)
	{
		ob_start();
		
		$sql="delete from ".TBL_ADMIN_USER." where userid='".$userid."'";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$_SESSION['msg']='User has been Deleted successfully';
		
		?>
		<script type="text/javascript">
			window.location = "editUser.php"
		</script>
		<?php
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	function lastlogin($userid)
	{
	
	$sql="select * from ".TBL_ADMIN_USER." where userid='".$userid."' and customerno=".$_SESSION['customerno']."";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
	$visit=$row['visited'];
	$update_array = array();
	
		$update_array['lastvisit'] = $this->dataobj->add_hours(date("Y-m-d H:i:s"),0);
		$update_array['visited'] = ($visit+1);
		
		$this->db->update(TBL_ADMIN_USER,$update_array,'userid',$userid);
	
	
	}
	
	function get_modules(){
	$sql="select * from ".TBL_CUSTOMER." where customerno=".$_SESSION['customerno']."";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
		if($row['istrack']=="0"){
			$_SESSION['istrack']="false";	
		}else{
			$_SESSION['istrack']="true";
		}
		if($row['isservice']=="0"){
			$_SESSION['isservice']="false"	;
		}else{
			$_SESSION['isservice']="true";
		}
	
	
	}
	function branchupdate($branchid){
	$_SESSION['branchid']=$branchid;
	}
}
?>