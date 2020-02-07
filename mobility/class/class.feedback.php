<?php

class feedback{


var $feedbackquestion;

var $Form;
var $db;
var $tabName;
var $feedbackquesionid;




function __construct(){

$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
$this->validity = new ClsJSFormValidation();
$this->Form = new ValidateForm();
$this->updatetrackee("feedback","");	

}

function getlastupdatedby($userid)
{
$sql="select * from ".TBL_ADMIN_USER." where userid=".$userid." ";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
$row= $this->db->fetch_array($resultpages) ;
echo $row['username'];
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

	function addclient($runat)
	{
		switch($runat){
		case 'local' :
		if(count($_POST)>0 and $_POST['submit']=='Submit'){
			extract($_POST);
			$this->feedbackquestion = $feedbackquestion;
			
		}
		$FormName = "frm_add_client";
		$ControlNames=array("feedbackquestion"=>array('feedbackquestion',"''","Please Enter Feedback Name","span_cname"));
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Add Feedback</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
			<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="feedback.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="feedback.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
					<a href="feedback.php?index=add">
						<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:100px" />
						</a>
					</td>
				</tr>
				</tbody>
			</table>
			<?php } ?>
	</div>	
	</div>
	<br class="clear"/>
	<br class="clear"/>
					<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
							<table  class="data" width="100%">
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<th >Feedback Question:</th>
										<td><input  type="text" value="" name="feedbackquestion" id="feedbackquestion" size="100" />
										
										<span id="span_cname"></span></td>
									</tr>
									
									
									<tr>
									<td colspan="2"><input type="submit" name="submit" value="Submit" 
									onclick="return <?php echo $ValidationFunctionName;?>()">
									&nbsp;
									<input type="button" onclick="javascript: history.go(-1); return false" 
									name="cancel" value="Cancel" />
									</td>
									</tr>		
							</table>
						
						
						</form>
					
					</div>
	
	
	</div>
	
	
	
	
	<?php
	
	break;
			case 'server' :	
			extract($_POST);
							// local variables
							
							$this->feedbackquestion=$feedbackquestion;
							
							$return =true;
							 //server side validation
							$this->updatetrackee("feedback",$trackeeid);	
							if($this->Form->ValidField($feedbackquestion,'empty','Feedback name is empty')==false)
							$return =false;
							if($this->getfeedback_question_count()>=5)
							{
							$_SESSION['error_msg'] = '5 feedback question already created';
								?>
								<script type="text/javascript">
								window.location = "feedback.php"
								</script>
								
								<?php
							$return =false;
							}
							
									if($return){
												$insert_sql_array = array();
												$insert_sql_array['feedbackquestion'] = $this->feedbackquestion;
												
												$insert_sql_array['userid'] = $_SESSION['user_id'];
												$insert_sql_array['customerno'] = $_SESSION['customerno'];
												
												$this->db->insert(TBL_FEEDBACKQUESTIONS,$insert_sql_array);
												$_SESSION['msg'] = 'Feedback has been Successfully created';
												$qid= $this->db->last_insert_id();
												?>
												<script type="text/javascript">
												window.location = "feedback.php?index=addoption&feedbackquestionid=<?php echo $qid;?>"
												</script>
												
												<?php
												
												exit();
										
										} else {
										
												echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
												$this->addclient('local');
										}
									break;
									default 	: 
									echo "Wrong Parameter passed";
									}
	}
function getfeedback_question_count(){

$sql="select * from ".TBL_FEEDBACKQUESTIONS." where customerno='".$_SESSION['customerno']."' and isdeleted=0 ";
$sql.=" order by feedbackquestionid desc ";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);
$x=0;
while($row= $this->db->fetch_array($resultpages))
{
$x++;
}

return $x;
}
function getfeedback_option_count($qid){

$sql="select * from ".TBL_FEEDBACKOPTIONS." where customerno='".$_SESSION['customerno']."' and isdel=0 and fquestionid=".$qid." ";

$resultpages= $this->db->query($sql,__FILE__,__LINE__);
$x=0;
while($row= $this->db->fetch_array($resultpages))
{
$x++;
}

return $x;
}
	
	
	function deleteupdate($question_id)
	{
		ob_start();
		
		$update_array = array();
		$update_array['isdeleted'] = '1';
		$update_array['userid'] = $_SESSION['user_id'];
		
		$this->db->update(TBL_FEEDBACKQUESTIONS,$update_array,'feedbackquestionid',$question_id);
		
		$_SESSION['msg']='Record has been deleted successfully';
		$this->updatetrackee("service",$trackeeid);
		
		?>
		<script type="text/javascript">
		window.location = "feedback.php"
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	
	function deleteoption($optionid,$qid)
	{
		if($this->getfeedback_option_count($qid)>1)
		{
			ob_start();
			$update_array = array();
			$update_array['isdel'] = 1;
			$this->db->update(TBL_FEEDBACKOPTIONS,$update_array,'feedbackoptionid',$optionid);
			$_SESSION['msg']='Record has been deleted successfully';
			$this->updatetrackee("service",$trackeeid);
		}else{
			$_SESSION['error_msg']='Ouestion needs to have alteast one option';
		}
			?>
			<script type="text/javascript">
			window.location = "feedback.php"
			</script>
			<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	}	
function addoption($runat,$feedbackquesionid)
	{
	$this->feedbackquesionid=$feedbackquesionid;
		switch($runat){
		case 'local' :
		if(count($_POST)>0 and $_POST['submit']=='Submit'){
			extract($_POST);
			$this->feedbackquestion = $feedbackquestion;
			
		}
		$FormName = "frm_add_client";
		$ControlNames=array("optionname"=>array('optionname',"''","Please Enter Feedback option","span_cname"));
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Add options</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
			<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="feedback.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="feedback.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
					<a href="feedback.php?index=add">
						<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:100px" />
						</a>
					</td>
				</tr>
				</tbody>
			</table>
			<?php } ?>
	</div>	
	</div>
	<br class="clear"/>
	<br class="clear"/>
					<div class="content">
						<form method="post"  action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
							<table  class="data" width="100%" >
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<th width="10%" >Feedback Option:</th>
										<td width="40%">
										<div id="domt" style="float:left; width:500px;">
										<input  type="text" value="" name="optionname[]" id="optionname" size="50" />
										
										<div>
									  <span id="span_cname"></span></td>
										<td width="10%"><a href="javascript:void(0);" onclick="domclick()">Add more</a></td>
									</tr>
									
									
									<tr>
									<td colspan="3"><input type="submit" name="submit" value="Submit" 
									onclick="return <?php echo $ValidationFunctionName;?>()">
									&nbsp;
									<input type="button" onclick="javascript: history.go(-1); return false" 
									name="cancel" value="Cancel" />
									</td>
									</tr>		
							</table>
						
						
						</form>
					
					</div>
	
	
	</div>
	
	
	
	
	<?php
	
	break;
			case 'server' :	
			extract($_POST);
							// local variables
							
							$this->optionname=$optionname;
							
							$return =true;
							 //server side validation
							
								
							if($this->Form->ValidField(count($optionname),'empty','Feedback option is empty')==false)
							$return =false;
									if($return){
												$insert_sql_array = array();
												$this->updatetrackee("feedback",$trackeeid);	
												//print_r($optionname);
												for($i=0;$i<count($optionname);$i++)
												{
												$insert_sql_array['optionname'] = $this->optionname[$i];
												$insert_sql_array['fquestionid'] = $this->feedbackquesionid;
												$insert_sql_array['userid'] = $_SESSION['user_id'];
												$insert_sql_array['customerno'] = $_SESSION['customerno'];
												$this->db->insert(TBL_FEEDBACKOPTIONS,$insert_sql_array);
												
												
												}
												
												$_SESSION['msg'] = 'Feedback option has been Successfully created';
												?>
												<script type="text/javascript">
												window.location = "feedback.php"
												</script>
												<?php
												exit();
										
										} else {
												echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
												$this->addclient('local');
										}
									break;
									default 	: 
									echo "Wrong Parameter passed";
									}
	}





// show all clients
	function showAllFeedbackfInfo()
		{
			$sql="select * from ".TBL_FEEDBACKQUESTIONS." where customerno='".$_SESSION['customerno']."' and isdeleted=0";
			$sql.=" order by feedbackquestionid desc ";
			$resultpages= $this->db->query($sql,__FILE__,__LINE__);
				if($_REQUEST['pg'])
				{
				$st= ($_REQUEST['pg'] - 1) * 10;
				$sql.=" limit ".$st.",10 ";	
				$x=(($_REQUEST['pg'] - 1)*10)+1;
				$pgr=$_REQUEST['pg'];
				}
				if($_REQUEST['pg'] == '')
				{
				$sql.=" limit 0,10 ";
				$x=1;
				$pgr=1;
				}	
			$result= $this->db->query($sql,__FILE__,__LINE__);
		?>
		
		<div class="onecolumn">
		<div class="header">
		<span>Feedback List</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
					<tbody>
					<tr>
					<td>
					<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:100px"/>
					</td>
					<td><a href="feedback.php?index=search">
					<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
					</a>
					</td>
					<td><a href="feedback.php?index=add">
					<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:100px" />
					</a>
					</td>
					</tr>
					</tbody>
				
				</table>
		<?php } ?>
		</div>		
		</div>
		
		
		<br class="clear"/>
		
		<div class="content">
		
						<form id="form_data" name="form_data" action="" method="post">
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="50%">Feedback Name</th>
								
									<th width="8%">Last Modified by</th>
									<th width="18%">Action</th>
									</tr>
						
						</thead>
						<tbody>
									<?php 		
									while($row= $this->db->fetch_array($result))
									{
									?>
									<tr>
									
									<td><?php echo $x;?></td>
									<td title="<?php echo $row['feedbackquestion'];?>"><?php echo $row['feedbackquestion'];?></td>
									
									<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									
									<td><a href="feedback.php?index=View&feedbackquestionid=<?php echo $row['feedbackquestionid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
								<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
								<?php if($row['feedbackquestionid']!=6){ ?>
									| <a href="feedback.php?index=edit&feedbackquestionid=<?php echo $row['feedbackquestionid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<a href="feedback.php?index=addoption&feedbackquestionid=<?php echo $row['feedbackquestionid'];?>" title="Add Options" class="help">
									<img src="images/options.png" width="15px" height="15px" /></a> |
										<a href="javascript: void(0);" title="delete" class="help" 
										onclick="javascript: if(confirm('Do u want to delete this record?')) { feedbackinfo.deleteupdate('<?php echo $row['feedbackquestionid'];?>',{}) }; return false;" >
										<img src="images/icon_delete.png" width="15px" height="15px" />
										</a>
									
									
									<?php }} ?></td> 
									</tr>			
									<?php 
									$x++;
									}
									?>
						</tbody>	
						</table>
						<br class="clear"/>
						</form>
								<div class="pagination">
								<?php
								$numpages= $this->db->num_rows($resultpages);
								$tmppage = $numpages/10;
								$remndr=$numpages%10;
								if($remndr >= 1)
								{
								$t1=explode('.',$tmppage);
								$lastpage = $t1[0]+1;
								}
								else
								{ $lastpage = $numpages/10; }
								?>
								<a href="feedback.php">&laquo;&laquo;</a>
								<a href="feedback.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="feedback.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="feedback.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="feedback.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="feedback.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="feedback.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="feedback.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="feedback.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="feedback.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="feedback.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="feedback.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="feedback.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>
		
		<div align="right">Total Pages - <?php echo $lastpage;?></div>
		<div align="right">Total Records - <?php echo $numpages;?></div>
		
		</div>
		</div>
		
		<?php 
		
		}

// view client 
function FeedbackView($feedbackquestionid)
{
	$this->feedbackquestionid=$feedbackquestionid;
	$sql="select * from ".TBL_FEEDBACKQUESTIONS." where feedbackquestionid='".$this->feedbackquestionid."' and customerno=".$_SESSION['customerno']."";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Feedback Detail</span>
	<div class="switch" style="width:300px">
		<table width="300px" cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
				<td><a href="feedback.php">
				<input type="button" id="chart_bar2" name="chart_bar2" class="left_switch" value="View All" style="width:100px"/>
				</a></td>
				<td><a href="feedback.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a></td>
				<td><a href="feedback.php?index=add">
				<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:100px" />
				</a>
				</td>
			</tr>
			</tbody>
		</table>
	
	</div>		
	</div>
	<br class="clear"/>
	<div class="content">
	<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
		<table class="data" width="100%">
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
				<th width="28%">Feedback Name:  </th>
				<td width="72%"><?php echo $row['feedbackquestion'];?> </td>
				</tr>
				<tr>
				<th>Feedback options:  </th>
				<td>
				<?php $this->getalloptions($this->feedbackquestionid,"");?>
				</td>
				</tr>
		</table>
	</form>
	</div>
	</div>
	<br class="clear"/>
	<?php
}

function getalloptions($feedbackquestionid,$status)
{
 
$sql="select * from ".TBL_FEEDBACKOPTIONS." where fquestionid='".$this->feedbackquestionid."' and isdel=0 and customerno=".$_SESSION['customerno']."";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	//$row= $this->db->fetch_array($result);
	$i=1;
	while($row= $this->db->fetch_array($result))
	{								
	?>
	Option  <?php echo $i++." :&nbsp; ".$row['optionname']; ?>  
	<?php if($status=="edit"){  ?>
	<a href="javascript: void(0);" title="delete" class="help" 
										onclick="javascript: if(confirm('Do u want to delete this record?')) {  feedbackinfo.deleteoption('<?php echo $row['feedbackoptionid'];?>',<?php echo $feedbackquestionid; ?>,{}) }; return false;" >
										<img src="images/icon_delete.png" width="15px" height="15px" />
	<?php 	} ?>								</a>
	<br />
	<?php
	}
}
// edit client

function editclient ($runat,$id)
{
	$this->feedbackquestionid=$id;
	switch($runat){
	case 'local' :
			if(count($_POST)>0 and $_POST['submit']=='Submit'){
				extract($_POST);
				$this->feedbackquestion = $feedbackquestion;
			}
			$FormName = "frm_addstud";
			$ControlNames=array("feedbackquestion"=>array('feedbackquestion',"''","Please enter Question. ","span_cname"));
			$ValidationFunctionName="CheckAddstudentValidity";
			$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
			echo $JsCodeForFormValidation;
			
			
			
			$sql="select * from ".TBL_FEEDBACKQUESTIONS." where feedbackquestionid='".$this->feedbackquestionid."'";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result);
		?>
		<div class="onecolumn">
		<div class="header">
		<span>Edit feedback</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
				<td><a href="feedback.php">
				<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
				</a>
				</td>
				<td><a href="feedback.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a>
				</td>
				<td><a href="feedback.php?index=add">
				<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:100px" />
				</a>
				</td>
				</tr>
				</tbody>
				</table>
				<?php } ?>
		</div>		
		</div>
		<br class="clear"/>
		<div class="content">
		<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
				
		<table  class="data" width="100%">
				<tr>
				<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
				<th >Feedback Question:</th>
				<td><input  type="text" value="<?php echo $row['feedbackquestion']; ?>" name="feedbackquestion" id="feedbackquestion" size="100" />
				<span id="span_cname"></span></td>
				</tr>
				<tr>
				<th>options</th>
				<td><?php $this->getalloptions($this->feedbackquestionid,"edit");?></td>
				</tr>
				<tr>
				<td colspan="2">
				<input type="submit" name="submit" value="Submit" 
				onclick="return <?php echo $ValidationFunctionName;?>()">
				&nbsp;
				<input type="button" onclick="javascript: history.go(-1); return false" 
				name="cancel" value="Cancel" /></td>
				</tr>
		</table>
		</form>
		
		</div>
		</div>
		<br class="clear"/>
		<?php
		break;
		case 'server' :	
		extract($_POST);
		$this->feedbackquestion=$feedbackquestion;
		$this->price=$price;
		$this->expectedtime=$expectedtime;
		$this->updatetrackee("feedback",$trackeeid);	
		// server side validation
		$return =true;
		if($return){
		$update_sql_array = array();
		$update_sql_array['feedbackquestion'] = $this->feedbackquestion;
		
		$this->db->update(TBL_FEEDBACKQUESTIONS,$update_sql_array,'feedbackquestionid',$this->feedbackquestionid);
		$_SESSION['msg']='client has been updated successfully';
		
		?>
		<script type="text/javascript">
		window.location = "feedback.php"
		</script>
		<?php
		exit();
		} else {
		echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
		$this->editclient('local');
		}
		break;
		default 	: 
		echo "Wrong Parameter passed";
	}
}

// delete function 

function deleteproduct($id)
{
ob_start();
$this->id=$id;
$sql_1="delete from ".TBL_FEEDBACKQUESTIONS." where feedbackquestionid='".$this->id."' and isdel=0 and customerno=".$_SESSION['customerno']."";
$this->db->query($sql_1,__FILE__,__LINE__);
$_SESSION['msg']='Record has been deleted successfully'.$id;;
?>
<script type="text/javascript">
window.location = "<?php echo $_SERVER['PHP_SELF']; ?>"
</script>
<?php
$html = ob_get_contents();
ob_end_clean();
return $html;
}


	function StockSearchBox()
	{
	?>
		<div id="searchboxbg">
		<form action="feedback.php?index=search" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				<tr>
				<th>Feedback question : </th>
				<td><input   type="text" value="<?php echo $_REQUEST['feedbackquestion'];?>" id="feedbackquestion" size="100" name="feedbackquestion" /></td>
				
				</tr>
				<tr>
				</tr>
				<tr>
				<th colspan="4"><input type="submit" name="search" id="search" value="Search" /></th>
				</tr>	
				</table>
		</form>
		</div>
	<br class="clear"/>
	<?php 
	}



function SearchRecord($feedbackquestion='')
{
$sql="select * from ".TBL_FEEDBACKQUESTIONS." where isdeleted=0 and customerno=".$_SESSION['customerno']." and 1 ";
if($feedbackquestion)
{
$sql .= " and feedbackquestion like '%".$feedbackquestion."%'";
}

$sql.=" order by feedbackquestionid desc ";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);
if($_REQUEST['pg'])
{
$st= ($_REQUEST['pg'] - 1) * 10;
$sql.=" limit ".$st.",10 ";	
$x=(($_REQUEST['pg'] - 1)*10)+1;
$pgr=$_REQUEST['pg'];
}
if($_REQUEST['pg'] == '')
{
$sql.=" limit 0,10 ";
$x=1;
$pgr=1;
}	
//echo $sql;
$result= $this->db->query($sql,__FILE__,__LINE__);
?>
<div class="onecolumn">
<div class="header">
<span>Search</span>
<div class="switch" style="width:300px">
<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
<table width="300px" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><a href="feedback.php">
<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
</a>
</td>
<td>
<input type="button" id="chart_line" name="chart_line" class="middle_switch active" value="Search" style="width:100px" />
</td>
<td><a href="feedback.php?index=add">

<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:100px" />

</a>
<?php } ?>
</td>

</tr>

</tbody>

</table>

</div>	



</div>

<br class="clear"/>





<br class="clear"/>

<?php $this->StockSearchBox(); ?>

<div class="content">


<form id="form_data" name="form_data" action="" method="post">
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Feedback Question</th>
									<th width="10%">last modified by</th>
									<th width="18%">Action</th>
									</tr>
						
						</thead>
						<tbody>
									<?php 		
									while($row= $this->db->fetch_array($result))
									{
									?>
									<tr>
									
									<td><?php echo $x;?></td>
									<td title="<?php echo $row['feedbackquestion'];?>"><?php echo $row['feedbackquestion'];?></td>
									<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									<td><a href="feedback.php?index=View&feedbackquestionid=<?php echo $row['feedbackquestionid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($row['feedbackquestionid']!=6){ ?>
									<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
									| <a href="feedback.php?index=edit&feedbackquestionid=<?php echo $row['feedbackquestionid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
								<a href="feedback.php?index=addoption&feedbackquestionid=<?php echo $row['feedbackquestionid'];?>" title="Add Options" class="help">
									<img src="images/options.png" width="15px" height="15px" /></a> |
									<a href="javascript: void(0);" title="delete" class="help" 
										onclick="javascript: if(confirm('Do u want to delete this record?')) { feedbackinfo.deleteupdate('<?php echo $row['feedbackquestionid'];?>',{}) }; return false;" >
										<img src="images/icon_delete.png" width="15px" height="15px" />
										</a>
									
									<?php } } ?></td> 
									</tr>			
									<?php 
									$x++;
									}
									?>
						</tbody>	
						</table>
						<br class="clear"/>
						</form>

<div class="pagination">
								<?php
								$numpages= $this->db->num_rows($resultpages);
								$tmppage = $numpages/10;
								$remndr=$numpages%10;
								if($remndr >= 1)
								{
								$t1=explode('.',$tmppage);
								$lastpage = $t1[0]+1;
								}
								else
								{ $lastpage = $numpages/10; }
								?>
								<a href="feedback.php">&laquo;&laquo;</a>
								<a href="feedback.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="feedback.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="feedback.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="feedback.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="feedback.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="feedback.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="feedback.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="feedback.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="feedback.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="feedback.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="feedback.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="feedback.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>

<div align="right">Total Pages - <?php echo $lastpage;?></div>
<div align="right">Total Records - <?php echo $numpages;?></div>

</div>

</div>

<?php 

}








}



?>