<?php
class Authentication // Basic class for authentication
{
var $user_id;
var $user_name;
var $user_type;
var $db;	
var $groups=array();
var $adminuser;


		 function __construct()
		 {
			$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
			if(isset($_SESSION['user_name'])){
			$this->user_name=$_SESSION['user_name'];
			$this->user_id=$_SESSION['user_id'];
			$this->groups=$_SESSION['groups'];
			$this->user_type=$_SESSION['user_type'];
			}
		 }  
		 
		function setHttp_Referer($http_referer)
		{
			$_SESSION['http_referer'] =	'..'.$http_referer;		
		}
				  
		function Create_Session($user_name,$user_id,$groups,$user_type,$customeno,$branchid){
			$this->user_name=$user_name;
			$this->user_id=$user_id;
			$this->groups=$groups;
			$this->user_type=$user_type;
            $this->customeno=$customeno;
			$this->branchid=$branchid;
			$_SESSION['user_name'] = $this->user_name;
			$_SESSION['user_id'] = $this->user_id;
			$_SESSION['groups']= $this->groups;
			$_SESSION['user_type'] = $this->user_type;
			$_SESSION['customerno'] = $customeno;
			$_SESSION['branchid'] = $branchid;
			$_SESSION['msg']=$this->WelcomeMessage();
			$set_responce=$this->get_customer_fields($customeno);
			if(count($set_responce)>0){
			$_SESSION['use_forms']=$set_responce['use_forms'];
			}
			
			$this->create_session_anywhere();
		}
		
		function get_customer_fields($customeno)
		{
			$responce=array();
			if($customeno!="")
			{
				$sql="select * from ".TBL_CUSTOMER." where customerno=".$customeno."";
				$result= $this->db->query($sql,__FILE__,__LINE__);
				$row= $this->db->fetch_array($result);
				$responce['use_forms']=$row['use_forms'];
			}
			return $responce;
		}
		
		
		
		function generateform($runat)
		{
		
		switch($runat){
		case 'local' :
		 
	
		?>
		
		<form action=""  id="form2" name="from2" method="post">
		<table border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000" width="50%">
                
                <tr class="even">
                    <td>Customized List 1</td>
                    <td align="center"><input type="checkbox" name="suf1_custom" id="suf1_custom" <?php if(isset($_SESSION['SerUserField1'])){echo "checked";} ?>></td>
                    <td align="center"><input name="suf1"  type="text" maxlength="10" id="suf1" 
					value="<?php if(isset($_SESSION['SerUserField1'])){echo $_SESSION['SerUserField1'];} ?>" size="20" maxlength="20"/></td>                  
                </tr>
                <tr class="odd">
                    <td>Customized List 2</td>
                    <td align="center"><input type="checkbox" name="suf2_custom" id="suf2_custom" 
					<?php if(isset($_SESSION['SerUserField2'])){echo "checked";} ?>></td>
                    <td align="center"><input  name="suf2" type="text" maxlength="10" id="suf2" 
					value="<?php if(isset($_SESSION['SerUserField2'])){echo $_SESSION['SerUserField2'];} ?>" size="20" maxlength="20"/></td>                  
                </tr>                
                <tr class="even">
                    <td>Extra Field for Service Call</td>
                    <td align="center"><input type="checkbox" name="callextra1_custom" id="callextra1_custom"
					 <?php if(isset($_SESSION['CallExtra1'])){echo "checked";} ?>> </td>
                    <td align="center"><input  name="callextra1" type="text" maxlength="10" id="callextra1" 
					value="<?php if(isset($_SESSION['CallExtra1'])){echo $_SESSION['CallExtra1'];} ?>" size="20" maxlength="20"/></td>                  
                </tr>                                                
                <tr class="odd">
                    <td>Extra Field for Service Call</td>
                    <td align="center"><input type="checkbox" name="callextra2_custom" id="callextra2_custom"
					 <?php if(isset($_SESSION['CallExtra2'])){echo "checked";} ?> ></td>
                    <td align="center"><input  name="callextra2" type="text" maxlength="10" id="callextra2" 
					value="<?php if(isset($_SESSION['CallExtra2'])){echo $_SESSION['CallExtra2'];} ?>" size="20" maxlength="20"/></td>                  
                </tr>                                                                
                <tr class="even">
                    <td>Extra Field for Client</td>
                    <td align="center"><input type="checkbox" name="cliextra_custom" id="cliextra_custom"
					<?php if(isset($_SESSION['ClientExtra'])){echo "checked";} ?> ></td>
                    <td align="center"><input  name="cliextra" type="text" maxlength="10" id="cliextra" 
					value="<?php if(isset($_SESSION['ClientExtra'])){echo $_SESSION['ClientExtra'];} ?>" size="20" maxlength="20"/></td>                  
                </tr> 
				<tr>
					<td><input type="submit" value="Submit" name="Add" ></td>
				
				</tr>                               
        </table>
		</form>
		<?php
		break;
		case 'server' :
		echo "called";
		extract($_REQUEST);
		$this->changesettingsvars($suf1_custom,$suf1,$suf2_custom,$suf2,$callextra1_custom,$callextra1,$callextra2_custom,$callextra2,$cliextra_custom,$cliextra);
		$this->updatetrackee("custom",$trackeeid);

		}
		}
		
		
		function updatetrackee($type,$trackeeid){
		$update_sql_array=array();
		if($type=="feedback"){
		$update_sql_array['pushfeedback']='1';
		}
		if($type=="list"){
		$update_sql_array['pushservicelist']='1';
		}
		if($type=="remark"){
		$update_sql_array['pushremarks']='1';
		}
		if($type=="custom"){
		$update_sql_array['pushcustom']='1';
		}
		if($type=="service"){
		$update_sql_array['pushservice']='1';
		}
		if($type=="form"){
		$update_sql_array['pushform']='1';
		}                
		$this->db->update(TBL_TRACKEE,$update_sql_array,"customerno",$_SESSION['customerno']);
		}
		function create_session_anywhere(){
		
			  $sql="select * from customfield where customerno=".$_SESSION['customerno']."";
			
			$result= $this->db->query($sql,__FILE__,__LINE__);
			while($row= $this->db->fetch_array($result))
			{
			
				if($row['defaultname']=='SerUserField1'&& $row['usecustom']=='1'){
				$_SESSION['SerUserField1']= $row['customname'];
				
				}
				if($row['defaultname']=="SerUserField2"&& $row['usecustom']=='1'){
				$_SESSION['SerUserField2']= $row['customname'];
				}
				if($row['defaultname']=="ClientExtra" && $row['usecustom']=='1'){
				$_SESSION['ClientExtra']= $row['customname'];
				}
				if($row['defaultname']=="CallExtra2" && $row['usecustom']=='1'){
				$_SESSION['CallExtra2']= $row['customname'];
				}
				if($row['defaultname']=="CallExtra1" && $row['usecustom']=='1'){
				$_SESSION['CallExtra1']= $row['customname'];
				}
			
			
			
			}
			
		}
		function changesettingsvars($suf1_custom,$suf1,$suf2_custom,$suf2,$callextra1_custom,$callextra1,$callextra2_custom,$callextra2,$cliextra_custom,$cliextra)
		{
		
		
				if($suf1_custom=="on")
				{
				
					 $updatesql1="UPDATE customfield SET customname='".$suf1."',
					 usecustom='1' WHERE customerno='".$_SESSION['customerno']."' and defaultname='SerUserField1'";
					 $_SESSION['SerUserField1']= $suf1;
					
					 $this->db->query($updatesql1,__FILE__,__LINE__);
				}
				else
				{
				
					$updatesql1="UPDATE customfield SET customname='".$suf1."',
					 usecustom='0' WHERE customerno='".$_SESSION['customerno']."' and defaultname='SerUserField1'";
					unset($_SESSION['SerUserField1']); 
					 $this->db->query($updatesql1,__FILE__,__LINE__);
					 
				
				}
				if($suf2_custom=="on")
				{
					$updatesql2="UPDATE customfield SET customname='".$suf2."',
					 usecustom='1' WHERE customerno='".$_SESSION['customerno']."' and defaultname='SerUserField2'";
					$this->db->query($updatesql2,__FILE__,__LINE__);
					 $_SESSION['SerUserField2']= $suf2;
				}
				else
				{	$updatesql2="UPDATE customfield SET customname='".$suf2."',
					 usecustom='0' WHERE customerno='".$_SESSION['customerno']."' and defaultname='SerUserField2'";
					$this->db->query($updatesql2,__FILE__,__LINE__);
				unset($_SESSION['SerUserField2']); 
				}
				if($callextra1_custom=="on")
				{
					$updatesql3="UPDATE customfield SET customname='".$callextra1."',
					usecustom='1' WHERE customerno='".$_SESSION['customerno']."' and defaultname='CallExtra1'";
					$this->db->query($updatesql3,__FILE__,__LINE__);
					 $_SESSION['CallExtra1']= $callextra1;
				}
				else
				{	$updatesql3="UPDATE customfield SET customname='".$callextra1."',
					usecustom='0' WHERE customerno='".$_SESSION['customerno']."' and defaultname='CallExtra1'";
					$this->db->query($updatesql3,__FILE__,__LINE__);
					unset($_SESSION['CallExtra1']); 
				
				}
				if($callextra2_custom=="on")
				{
				
					
					$updatesql4="UPDATE customfield SET customname='".$callextra2."',
					usecustom='1' WHERE customerno='".$_SESSION['customerno']."' and defaultname='CallExtra2'";
					$this->db->query($updatesql4,__FILE__,__LINE__);
					 $_SESSION['CallExtra2']= $callextra2;
					
				}else
				{
					$updatesql4="UPDATE customfield SET customname='".$callextra2."',
					usecustom='0' WHERE customerno='".$_SESSION['customerno']."' and defaultname='CallExtra2'";
					$this->db->query($updatesql4,__FILE__,__LINE__);
					unset($_SESSION['CallExtra2']); 
				
				}
				
				if($cliextra_custom=="on")
				{
					$_SESSION['ClientExtra']= $cliextra;
					$updatesql5="UPDATE customfield SET customname='".$cliextra."',
					usecustom='1' WHERE customerno='".$_SESSION['customerno']."' and defaultname='ClientExtra'";
					$this->db->query($updatesql5,__FILE__,__LINE__);
					
				}else
				{
					$updatesql5="UPDATE customfield SET customname='".$cliextra."',
					usecustom='0' WHERE customerno='".$_SESSION['customerno']."' and defaultname='ClientExtra'";
					$this->db->query($updatesql5,__FILE__,__LINE__);
					unset($_SESSION['ClientExtra']); 
				}	
			
			$_SESSION['msg']="Your settings have been changed";
			?>
			
			<script type="text/javascript">
			window.location = "index.php"
			</script>
			<?php	
				
		
		}
		function Get_user_id()
		{
			return $this->user_id;
		}

		function Get_user_name()
		{
			return $this->user_name;
		}
		
		function Get_group()
		{
			return $this->groups;
		}
		
		function Get_user_type()
		{
			return $this->user_type;
		}
		
		function Destroy_Session(){
    		unset($_SESSION['user_name']); 
			unset($_SESSION['user_id']); 
			unset($_SESSION['groups']); 
			unset($_SESSION['user_type']); 
			unset($_SESSION['http_referer']); 
			session_destroy();
			$_SESSION['msg']='You have logged out successfully';
			?>
			<script type="text/javascript">
			window.location="index.php";
			</script>
			<?php }
		
		function checkAuthentication()
		{
			//check for the valid login
			if(isset($_SESSION['user_name']))
			return true;
			else return false;
		}
		
		function Checklogin()
		{
			$this->setHttp_Referer($_SERVER['REQUEST_URI']);  
			if(!$this->checkAuthentication()){
			$_SESSION['error_msg']='Please login here first..';
			$this->GotoLogin();
			exit();
			}
		
		
		}
		
		function GotoLogin()
		{
			?>
				<script type="text/javascript">
				window.location='login.php';
				</script>
			<?php }

		function checkAdminAuthentication()
		{
			//check for the valid login
			if(isset($_SESSION['user_name']))
			return true;
			else return false;
		}
		
		function CheckAdminlogin()
		{
			$this->setHttp_Referer($_SERVER['REQUEST_URI']); 
			$this->check_modules(); 
			if(!$this->checkAdminAuthentication()){
			$_SESSION['error_msg']='Please login here first..';
			$this->GotoAdminLogin();
			exit();
			}
			
		
		}

		function GotoAdminLogin()
		{
			?>
				<script type="text/javascript">
				window.location='index.php';
				</script>
			<?php }

		function SendToRefrerPage()
		{	
			if($_SERVER['HTTP_REFERER']==''){
			?>
			<script type="text/javascript">
			  window.location='home.php';
			  </script>
			<?php
			}
			else
			{
			?>
				<script type="text/javascript">
				window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>';
				</script>
			<?php }		
			exit();
		}
		
		function CheckAuthorization($access_rules,$access_rules_type,$returnValue=false)
		{
			//check for the group access
			$access=true;
			$search_array = array('first' => 1, 'second' => 4);
			foreach($access_rules as $key => $value)
			{
				if (array_key_exists($key, $this->groups))
				{
					if($value!=$this->groups[$key])
					{
						$access=false;
						if($access_rules_type=='all')
						break;
					}
					else
					{
						$access=true;
						if($access_rules_type=='any')
						break;
					}
				}
				else
				{
						$access=false;
						if($access_rules_type=='all')
						break;
				}
			}
			
			if(!$access and !$returnValue)
			{
				$_SESSION['error_msg']='oops !! Your are not authorised to access this page, Please contact Administrator.';
				$this->SendToRefrerPage();
			}
			else
			return $access;
		}
		
		function WelcomeMessage()
		{
			return "Welcome ".$this->user_name." , You have logged in successfully.. ";
		
		}
		function check_modules(){
		if(isset($_SESSION['customerno'])){
		if($_SESSION['page']=="service"){
				if($_SESSION['isservice']=="false"){
				
					$_SESSION['error_msg']='oops !! Your are not authorised to access this page, Please contact Administrator.';
					$this->SendToRefrerPage();
					exit();
					
				}
			}	
			if($_SESSION['page']=="track"){
				if($_SESSION['istrack']=="false"){
				
					$_SESSION['error_msg']='oops !! Your are not authorised to access this page, Please contact Administrator.';
					$this->SendToRefrerPage();
					
					exit();
				}
			}
		
		
		}
			
			
		
		
		}
		
		
		
	}	
?>
