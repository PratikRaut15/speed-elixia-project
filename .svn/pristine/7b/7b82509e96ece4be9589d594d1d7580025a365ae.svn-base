<?php

class Client{


var $clientname;
var $add1;
var $add2;
var $phoneno;
var $city;
var $state;
var $zip;
var $email;
var $maincontact;
var $Form;
var $db;
var $tabName;




function __construct(){

$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
$this->validity = new ClsJSFormValidation();
$this->Form = new ValidateForm();	

}


function selectcheckbox()
{
$sql="select * from ".TBL_CKPOINTS." where customerno='".$_SESSION['customerno']."'"";
$result= $this->db->query($sql,__FILE__,__LINE__);
?>
<select id="to" class="shortdd" onchange="addTrackee();" name="to">
<option value="-1">Select Trackee</option>
<?php 
while($row= $this->db->fetch_array($result)){
?><option value="<?php echo $x; ?>"><?php echo $row['cname'];?></option>
<?php } ?>
</select>
<?php 
}

// add client function 
	function addclient($runat)
	{
		switch($runat){
		case 'local' :
		if(count($_POST)>0 and $_POST['submit']=='Submit'){
			extract($_POST);
			$this->clientname = $clientname;
			
		}
		$FormName = "frm_add_client";
		$ControlNames=array("clientname"=>array('clientname',"''","Please Enter Client Name","span_cname"));
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Add Client</span>
	<div class="switch" style="width:300px">
			<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="client_details.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="client_details.php?index=searchStudent">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:100px" />
					</td>
				</tr>
				</tbody>
			</table>
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
										<th >Client Name:</th>
										<td><input  type="text" value="" name="clientname" id="clientname" />
										<span id="span_cname"></span></td>
									</tr>
									<tr>
										<th >Address line1 :</th>
										<td><input  type="text" value="" name="add1" id="add1"  size="50"/>
										<span id="span_add1"></span></td>
									</tr>
									<tr>
										<th >Address line2 :</th>
										<td><input  type="text" value="" name="add2" id="add2" size="50" />
										<span id="span_add2"></span></td>
									</tr>
									<tr>
										<th >City :</th>
										<td><input  type="text" value="" name="city" id="city" />
										<span id="span_add2"></span></td>
									</tr>
									<tr>
										<th >State :</th>
										<td><input  type="text" value="" name="state" id="state" />
										<span id="span_state"></span></td>
									</tr>
									<tr>
										<th >Zipcode :</th>
										<td><input  type="text" value="" name="zip" id="zip" />
										<span id="span_state"></span></td>
									</tr>
									
									<tr>
										<th >Main Contact :</th>
										<td><input  type="text" value="" name="maincontact" id="maincontact" />
										<span id="span_maincontact"></span></td>
									</tr>
									
									<tr>
											<th>Email address : </th>
											<td><input value="" name="email" id="email" type="text" />
											<span id="span_designation"></span></td>
									</tr>
									<tr>
									
									
									
									<tr>
									<th>Phone no.: </th>
									<td><input value="" name="phoneno" id="phoneno" type="text" />
									<span id="span_extension_no."></span></td>
									</tr>
									
									<tr>
									<td colspan="2"><input type="submit" name="submit" value="Submit" 
									onclick="return <?php echo $ValidationFunctionName; ?>();">
									&nbsp;
									<input type="button" onclick="javascript: history.go(-1); return false" 
									name="cancel" value="Cancle" />
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
							
							$this->clientname=$clientname;
							$this->add1=$add1;
							$this->add2=$add2;
							$this->phoneno=$phoneno;
							$this->city = $city;
							$this->state=$state;
							$this->zip=$zip;
							$this->email=$email;
							$this->maincontact=$maincontact;
							
							$return =true;
							 //server side validation
								
							if($this->Form->ValidField($clientname,'empty','Client name is empty')==false)
							$return =false;
									if($return){
												$insert_sql_array = array();
												$insert_sql_array['clientname'] = $this->clientname;
												$insert_sql_array['add1'] = $this->add1;
												$insert_sql_array['add2'] = $this->add2;
												$insert_sql_array['phoneno'] = $this->phoneno;
												$insert_sql_array['city'] = $this->city;
												$insert_sql_array['state'] = $this->state;
												$insert_sql_array['zip'] = $this->zip;
												$insert_sql_array['email'] = $this->email;
												$insert_sql_array['maincontact'] = $this->maincontact;
												$insert_sql_array['iscall'] = '1';
												$insert_sql_array['customerno'] = $_SESSION['customeno'];
												$insert_sql_array['userid'] = $_SESSION['user_id'];
												
												$this->db->insert(TBL_CLIENT,$insert_sql_array);
												$user_id = $this->db->last_insert_id();
											
												$_SESSION['msg'] = 'Client has been Successfully created';
												?>
												<script type="text/javascript">
												window.location = "client_details.php"
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
function branch_by_id($branchid){
$sql="select * from ".BRANCH." where branchid=".$branchid." AND customerno = ".$_SESSION['customerno'];
$result= $this->db->query($sql,__FILE__,__LINE__);
$row=$this->db->fetch_array($result);
return $row['branchname'];
}
	function showAllClientfInfo()
		{
		
			$sql="select * from ".TBL_CLIENT." where 1 and customerno=".$_SESSION['customerno']."";
			$sql.=" order by clientid desc ";
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
		<span>Client List</span>
		<div class="switch" style="width:300px">
				<table width="300px" cellpadding="0" cellspacing="0">
					<tbody>
					<tr>
					<td>
					<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:100px"/>
					</td>
					<td><a href="client_details.php?index=searchStudent">
					<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
					</a>
					</td>
					<td><a href="client_details.php?index=addStudent">
					<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:100px" />
					</a>
					</td>
					</tr>
					</tbody>
				
				</table>
		</div>		
		</div>
		
		
		
		<br class="clear"/>
		<br class="clear"/>
		
		<div class="content">
		
						<form id="form_data" name="form_data" action="" method="post">
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Client Name</th>
									<th width="8%">City</th>
									<th width="8%">Zip</th>
									<th width="8%">Phone</th>
									<th width="10%">Email</th>
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
									
									<td><?php echo $row['userid'];?></td>
									<td title="<?php echo $row['clientname'];?>"><?php echo $row['clientname'];?></td>
									<td title="<?php echo $row['city'];?>"><?php echo $row['city'];?></td>
									<td title=""><?php echo $row['zip'];?></td>
									<td title=""><?php echo $row['phone'];?></td>
									<td title="<?php echo $row['email'];?>"><?php echo $row['email'];?></td>
									<td title=""><?php echo $row['userid'];?></td>
									
									<td><a href="client_details.php?index=studentView&student_id=<?php echo $row['clientid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] != 'User') {?>
									| <a href="client_details.php?index=editStudent&student_id=<?php echo $row['clientid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<?php if($row['dispatch'] == 'no') { ?>
									<?php } 
									?>
									<?php } ?></td> 
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
								<a href="client_details.php">&laquo;&laquo;</a>
								<a href="client_details.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="client_details.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="client_details.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="client_details.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="client_details.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="client_details.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="client_details.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="client_details.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="client_details.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="client_details.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="client_details.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="client_details.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>
		
		<div align="right">Total Pages - <?php echo $lastpage;?></div>
		<div align="right">Total Records - <?php echo $numpages;?></div>
		
		</div>
		</div>
		
		<?php 
		
		}

// view client 
function ClientView($student_id)
{
	$this->student_id=$student_id;
	$sql="select * from ".TBL_CLIENT." where clientid='".$this->student_id."' and customerno=".$_SESSION['customerno']."";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Client Detail</span>
	<div class="switch" style="width:300px">
		<table width="300px" cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
				<td><a href="client_details.php">
				<input type="button" id="chart_bar2" name="chart_bar2" class="left_switch" value="View All" style="width:100px"/>
				</a></td>
				<td><a href="client_details.php?index=searchStudent">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a></td>
				<td><a href="client_details.php?index=addStudent">
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
				<th>Client Name:  </th>
				<td><?php echo $row['clientname'];?> </td>
				</tr>
				
				<tr>
				<th>City:  </th>
				<td><?php echo $row['city'];?> </td>
				</tr>
				
				<tr>
				<th>Address1 : </th>
				<td><?php echo $row['add1'];?> </td>
				</tr> 
				
				<tr>
				<th>Address2 : </th>
				<td><?php echo $row['add2'];?> </td>
				</tr> 
				<tr>
				
				<tr>
				<th>Main Contact: </th>
				<td><?php echo $row['maincontact'];?></td>
				</tr>
				
				<tr>
				<th>Email : </th>
				<td><?php echo $row['email'];?></td>
				</tr>
				
				<tr>
				<th>Zip : </th>
				<td><?php echo $row['zip'];?></td>
				</tr>
				
				</tr>
		</table>
	</form>
	</div>
	</div>
	<br class="clear"/>
	<?php
}

// edit client

function editclient ($runat,$id)
{
	$this->clientid=$id;
	switch($runat){
	case 'local' :
			if(count($_POST)>0 and $_POST['submit']=='Submit'){
				extract($_POST);
				$this->clientname = $clientname;
			}
			$FormName = "frm_addstud";
			$ControlNames=array("clientname"=>array('clientname',"''","Please enter Clinet name. ","span_cname"));
			$ValidationFunctionName="CheckAddstudentValidity";
			$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
			echo $JsCodeForFormValidation;
			
			
			
			$sql="select * from ".TBL_CLIENT." where clientid='".$this->clientid."' and customerno=".$_SESSION['customerno']."";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result);
		?>
		<div class="onecolumn">
		<div class="header">
		<span>Edit Student</span>
		<div class="switch" style="width:300px">
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
				<td><a href="client_details.php">
				<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
				</a>
				</td>
				<td><a href="client_details.php?index=searchStudent">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a>
				</td>
				<td><a href="client_details.php?index=addStudent">
				<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:100px" />
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
				
		<table  class="data" width="100%">
				<tr>
				<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
				<th >Client Name:</th>
				<td><input  type="text" value="<?php echo $row['clientname']; ?>" name="clientname" id="clientname" />
				<span id="span_cname"></span></td>
				</tr>
				<tr>
				<th >Address line1 :</th>
				<td><input  type="text" value="<?php echo $row['add1']; ?>" name="add1" id="add1"  size="50"/>
				<span id="span_add1"></span></td>
				</tr>
				<tr>
				<th >Address line2 :</th>
				<td><input  type="text" value="<?php echo $row['add2']; ?>" name="add2" id="add2" size="50" />
				<span id="span_add2"></span></td>
				</tr>
				<tr>
				<th >City :</th>
				<td><input  type="text" value="<?php echo $row['city']; ?>" name="city" id="city" />
				<span id="span_add2"></span></td>
				</tr>
				<tr>
				<th >State :</th>
				<td><input  type="text" value="<?php echo $row['state']; ?>" name="state" id="state" />
				<span id="span_state"></span></td>
				</tr>
				<tr>
				<th >Zipcode :</th>
				<td><input  type="text" value="<?php echo $row['zip']; ?>" name="zip" id="zip" />
				<span id="span_state"></span></td>
				</tr>
				<tr>
				<th >Main Contact :</th>
				<td><input  type="text" value="<?php echo $row['maincontact']; ?>" name="maincontact" id="maincontact" />
				<span id="span_maincontact"></span></td>
				</tr>
				<tr>
				<th>Email address : </th>
				<td><input value="<?php echo $row['email']; ?>" name="email" id="email" type="text" />
				<span id="span_designation"></span></td>
				</tr>
				<tr>
				<tr>
				<th>Phone no.: </th>
				<td><input value="<?php echo $row['phoneno']; ?>" name="phoneno" id="phoneno" type="text" />
				<span id="span_extension_no."></span></td>
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
		$this->clientname=$clientname;
		$this->zip=$zip;
		$this->city=$city;
		$this->state=$state;
		$this->maincontact=$maincontact;
		$this->email=$email;
		// server side validation
		$return =true;
		if($return){
		$update_sql_array = array();
		$update_sql_array['clientname'] = $this->clientname;
		$update_sql_array['zip'] = $this->zip;
		$update_sql_array['city'] = $this->city;
		$update_sql_array['state'] = $this->state;
		$update_sql_array['maincontact'] = $this->maincontact;
		$update_sql_array['email'] = $this->email;
		$this->db->update(TBL_CLIENT,$update_sql_array,'clientid',$this->clientid);
		$_SESSION['msg']='client has been updated successfully';
		
		?>
		<script type="text/javascript">
		window.location = "client_details.php"
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
$sql_1="delete from ".TBL_CLIENT." where clientid='".$this->id."'";
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
		<form action="client_details.php?index=searchStudent" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				<tr>
				<th>Name Of Client : </th>
				<td><input   type="text" value="<?php echo $_REQUEST['clientname'];?>" id="clientname" name="clientname" /></td>
				<th>Phoneno : </th>
				<td><input value="" name="phoneno" id="phoneno" type="text" />
				<span id="span_function"></span></td>
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



function SearchRecord($clientname='',$phoneno='')
{
$sql="select * from ".TBL_CLIENT." where 1 and customerno=".$_SESSION['customerno']."";
if($clientname)
{
$sql .= " and clientname = '".$clientname."'";
}
if($phoneno)
{
$sql .= " and phoneno '".$phoneno."%'";
}
$sql.=" order by clientid desc ";
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
<table width="300px" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><a href="client_details.php">
<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
</a>
</td>
<td>
<input type="button" id="chart_line" name="chart_line" class="middle_switch active" value="Search" style="width:100px" />
</td>
<td><a href="client_details.php?index=addStudent">

<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:100px" />

</a>

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

<form id="form_data" name="form_data" action="" method="post">
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Client Name</th>
									<th width="8%">City</th>
									<th width="8%">Zip</th>
									<th width="8%">Phone</th>
									<th width="10%">Email</th>
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
									
									<td><?php echo $row['userid'];?></td>
									<td title="<?php echo $row['clientname'];?>"><?php echo $row['clientname'];?></td>
									<td title="<?php echo $row['city'];?>"><?php echo $row['city'];?></td>
									<td title=""><?php echo $row['zip'];?></td>
									<td title=""><?php echo $row['phone'];?></td>
									<td title="<?php echo $row['email'];?>"><?php echo $row['email'];?></td>
									<td title=""><?php echo $row['userid'];?></td>
									
									<td><a href="client_details.php?index=studentView&student_id=<?php echo $row['clientid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] != 'User') {?>
									| <a href="client_details.php?index=editStudent&student_id=<?php echo $row['clientid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<?php if($row['dispatch'] == 'no') { ?>
									<?php } 
									?>
									<?php } ?></td> 
									</tr>			
									<?php 
									$x++;
									}
									?>
						</tbody>	
						</table>
						<br class="clear"/>
						</form>

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
								<a href="client_details.php">&laquo;&laquo;</a>
								<a href="client_details.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="client_details.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="client_details.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="client_details.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="client_details.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="client_details.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="client_details.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="client_details.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="client_details.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="client_details.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="client_details.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="client_details.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>

<div align="right">Total Pages - <?php echo $lastpage;?></div>
<div align="right">Total Records - <?php echo $numpages;?></div>

</div>

</div>

<?php 

}








}



?>