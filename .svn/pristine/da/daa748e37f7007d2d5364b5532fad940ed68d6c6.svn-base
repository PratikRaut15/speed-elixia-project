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


	function selectforms($select_form_id){
		$sql="select * from ".TBL_FORM_TYPE." where customerno='".$_SESSION['customerno']."' ";
		$sql.=" order by form_type_id desc ";
		$resultpages= $this->db->query($sql,__FILE__,__LINE__); 
		?>
		
				<option value="">select form type</option>
				<?php while($row= $this->db->fetch_array($resultpages)){ ?>
				<option  <?php if($select_form_id==$row['form_type_id']){ echo 'selected="selected"';} ?>   value="<?php echo $row['form_type_id']?>" ><?php echo $row['form_type_name']; ?></option>  
				<?php  } ?>
		<?php 
	}
function getlastupdatedby($userid)
{
$sql="select * from ".TBL_ADMIN_USER." where userid=".$userid." ";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
$row= $this->db->fetch_array($resultpages) ;
echo $row['username'];
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
	<div class="switch" style="width:300px"><?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="master"){ ?>
	
			<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="client_details.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="client_details.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:100px" />
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
									<?php if(isset($_SESSION['ClientExtra'])){?>
									<tr>
											<th><?php echo $_SESSION['ClientExtra']; ?> : </th>
											<td><input value="" name="extra" id="extra" type="text" />
											<span id="span_designation"></span></td>
									</tr>
									
									<?php } ?>
									<tr>
									
									
									
									<tr>
									<th>Phone no.: </th>
									<td><input value="" name="phoneno" id="phoneno" type="text" />
									<span id="span_extension_no."></span></td>
									</tr>
									<tr>
									<th>Branch</th>
										<td>
										
										<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="master"){
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
									<th>type</th>
										<td>
										
										<?php 
										$sql="select * from ".TBL_CLIENT_TYPE." where customerno = ".$_SESSION['customerno'];
										$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
										?>
										
										<select name="type_id"  > 
										<option value="0">select</option>
										<?php 
										while($row= $this->db->fetch_array($resultpages))
										{
										
										?>
										<option value="<?php echo $row['type_id'];?>" <?php if($_SESSION['type_id']== $row['type_id']){echo "selected";} ?>><?php echo $row['type_name'];?></option>
										<?php } ?>
										</select>
										
										
										</td>
									</tr>
									<?php if($_SESSION['use_forms']==1){ ?>
									<tr>
									<th>form type</th>
									<td><select id="form_type_id" name="form_type_id" ><?php $this->selectforms(0);?> </select> <span  id="span_form_type"  ></span></td>
									</tr>
									<?php } ?>
									<tr>
									<th>Expiry date</th>
									<td><input type="text" class="datepicker1" name="expiry_date" id="expiry_date"  /> </td>
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
							
							$this->clientname=$clientname;
							$this->add1=$add1;
							$this->add2=$add2;
							$this->phoneno=$phoneno;
							$this->city = $city;
							$this->state=$state;
							$this->zip=$zip;
							$this->email=$email;
							$this->maincontact=$maincontact;
							$this->branch=$branch;
							$this->expiry_date=$expiry_date;
							$this->type_id=$type_id;
							
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
												$insert_sql_array['customerno'] = $_SESSION['customerno'];
												$insert_sql_array['userid'] = $_SESSION['user_id'];
												$insert_sql_array['branchid'] = $this->branch;
												$insert_sql_array['form_type_id'] = $form_type_id;
												 if(isset($_SESSION['ClientExtra'])){
												 $insert_sql_array['extra'] = $extra;
												 
												 }
												if($_SESSION['customerno']!="14"){
												$insert_sql_array['expiry_date'] = $this->expiry_date;
												$insert_sql_array['type_id'] = $this->type_id;
												
												}
												
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
	function showAllClientfInfo()
		{
			if($_SESSION['branchid']!="all"){
			//$branch_clause=" and branchid=".$_SESSION['branchid'];
			}
			$sql="select *,".TBL_CLIENT.".userid as cuserid from ".TBL_CLIENT." left outer join ".TBL_CLIENT_TYPE." on ".TBL_CLIENT_TYPE.".type_id=".TBL_CLIENT.".type_id where 1 and ".TBL_CLIENT.".isdeleted=0 and ".TBL_CLIENT.".customerno=".$_SESSION['customerno']." ".$branch_clause;
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
		<div class="switch" style="width:300px"><?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
					<tbody>
					<tr>
					<td>
					<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:100px"/>
					</td>
					<td><a href="client_details.php?index=search">
					<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
					</a>
					</td>
					<td><a href="client_details.php?index=add">
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
		<br class="clear"/>
		
		<div class="content">
		
						<form id="form_data" name="form_data" action="" method="post">
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Client Name</th>
									<th>branch</th>
									<th width="8%">City</th>
									<th width="8%">Zip</th>
									<th width="8%">Phone</th>
									<th width="10%">Email</th>
									<?php
									if($_SESSION['customerno']!="14"){
									?>
									<th width="8%">expiry date</th>
									<th width="10%">type </th>
									
									<?php } ?>
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
									<td title="<?php echo $row['clientname'];?>"><?php echo $row['clientname'];?></td>
									<td><?php echo $this->branch_by_id($row['branchid']); ?></td>
									<td title="<?php echo $row['city'];?>"><?php echo $row['city'];?></td>
									<td title=""><?php echo $row['zip'];?></td>
									<td title=""><?php echo $row['phoneno'];?></td>
									<td title="<?php echo $row['email'];?>"><?php echo $row['email'];?></td>
									
									<?php
									if($_SESSION['customerno']!="14"){
									?>
									<td>
									<?php 
									echo $row['expiry_date'];
									?>
									</td>
									<td>
									<?php 
									echo $row['type_name'];
									?>
									</td>
									<?php 
									}
									?>
									
									</td>
									
									
									<td title=""><?php echo $this->getlastupdatedby($row['cuserid']);?></td>
									
									<td><a href="client_details.php?index=View&id=<?php echo $row['clientid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="master"){ ?>
									| <a href="client_details.php?index=edit&id=<?php echo $row['clientid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<a href="javascript: void(0);" title="delete" class="help" 
onclick="javascript: if(confirm('Do u want to delete this record?')) { clientinfo.deleteupdate('<?php echo $row['clientid'];?>',{}) }; return false;" >
<img src="images/icon_delete.png" width="15px" height="15px" />
</a>
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
function ClientView($id)
{
	$this->id=$id;
	$sql="select *,".TBL_CLIENT.".userid as cuserid from ".TBL_CLIENT." left outer join ".TBL_CLIENT_TYPE." on ".TBL_CLIENT_TYPE.".type_id=".TBL_CLIENT.".type_id  where ".TBL_CLIENT.".clientid='".$this->id."' and ".TBL_CLIENT.".customerno=".$_SESSION['customerno']."";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Client Detail</span>
	<div class="switch" style="width:300px"><?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="master"){ ?>
		<table width="300px" cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
				<td><a href="client_details.php">
				<input type="button" id="chart_bar2" name="chart_bar2" class="left_switch" value="View All" style="width:100px"/>
				</a></td>
				<td><a href="client_details.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a></td>
				<td><a href="client_details.php?index=add">
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
	<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
		<table class="data" width="100%">
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
				<th>Client Name:  </th>
				<td><?php echo $row['clientname'];?> </td>
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
				<th>City:  </th>
				<td><?php echo $row['city'];?> </td>
				</tr>
				<tr>
				<th>state:  </th>
				<td><?php echo $row['state'];?> </td>
				</tr>
				<tr>
				<th>Zip : </th>
				<td><?php echo $row['zip'];?></td>
				</tr>
				<tr>
				<th>Main Contact: </th>
				<td><?php echo $row['maincontact'];?></td>
				</tr>
				
				<tr>
				<th>Email : </th>
				<td><?php echo $row['email'];?></td>
				</tr>
				<tr>
				<th>phoneno : </th>
				<td><?php echo $row['phoneno'];?></td>
				</tr>
				<tr>
				<th>branch : </th>
				<td><?php echo $this->branch_by_id($row['branchid']);?></td>
				</tr>
				
				<?php if(isset($_SESSION['ClientExtra'])){?>
				<tr>
				<th><?php echo $_SESSION['ClientExtra'];?> : </th>
				<td><?php echo $row['extra'];?></td>
				</tr>
				
				
				<?php } ?>
				
				<tr>
				<th>type : </th>
				<td><?php echo $row['type_name'];?></td>
				</tr>
				<tr>
				<th>expiry : </th>
				<td> <?php  if($row['expiry_date']!="0000-00-00 00:00:00"){echo $row['expiry_date'];}else{ echo "Na";}?></td>
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

function deleteupdate($clientid)
{
ob_start();
$update_array = array();
$update_array['isdeleted'] = '1';
$this->db->update(TBL_CLIENT,$update_array,'clientid',$clientid);
$_SESSION['msg']='Record has been deleted successfully';
?>
<script type="text/javascript">
window.location = "client_details.php"
</script>
<?php
$html = ob_get_contents();
ob_end_clean();
return $html;
}
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
			
			
			
			$sql="select * from ".TBL_CLIENT." where clientid='".$this->clientid."'";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result);
		?>
		<div class="onecolumn">
		<div class="header">
		<span>Edit </span>
		<div class="switch" style="width:300px"><?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
				<td><a href="client_details.php">
				<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
				</a>
				</td>
				<td><a href="client_details.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a>
				</td>
				<td><a href="client_details.php?index=add">
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
							<th>Branch</th>
								<td>
								
								<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="master"){
								$sql2="select * from ".BRANCH." where customerno = ".$_SESSION['customerno'];
								$resultpages2= $this->db->query($sql2,__FILE__,__LINE__);    
								?>
								
								<select name="branch"  > 
								
								<?php 
								while($row2= $this->db->fetch_array($resultpages2))
								{
								
								?>
								<option value="<?php echo $row2['branchid'];?>" <?php if($_SESSION['branchid']== $row['branchid']){echo "selected";} ?>><?php echo $row2['branchname'];?></option>
								<?php } ?>
								</select>
								
								<?php } ?>
								</td>
							</tr>
				<?php if(isset($_SESSION['ClientExtra'])){?>
									<tr>
											<th><?php echo $_SESSION['ClientExtra']; ?>: </th>
											<td> <input  name="extra" id="extra" type="text"  value="<?php echo $row['extra']; ?>" /> 
											<span id="span_designation"></span></td>
									</tr>
									
									<?php } ?>
				<tr>
			
									<tr>
									<th>type</th>
										<td>
										
										<?php 
										$sql3="select * from ".TBL_CLIENT_TYPE." where customerno = ".$_SESSION['customerno'];
										$resultpages3= $this->db->query($sql3,__FILE__,__LINE__);    
										?>
										
										<select name="type_id"  > 
										<option value="0">select</option>
										<?php 
										while($row3= $this->db->fetch_array($resultpages3))
										{
										
										?>
										<option value="<?php echo $row3['type_id'];?>" <?php if($row3['type_id']== $row['type_id']){echo "selected";} ?>><?php echo $row3['type_name'];?></option>
										<?php } ?>
										</select>
										
										
										</td>
									</tr>
									<?php if($_SESSION['use_forms']==1){ ?>
									<tr>
									<th>form type</th>
									<td><select id="form_type_id" name="form_type_id" ><?php $this->selectforms($row['form_type_id']);?> </select> <span  id="span_form_type"  ></span></td>
									</tr>
									<?php } ?>
									<tr>
									<th>Expiry date</th>
									<td><input type="text" class="datepicker1" name="expiry_date" id="expiry_date" value="<?php if($row['expiry_date']!="0000-00-00 00:00:00"){echo date("Y-m-d",strtotime($row['expiry_date']));}else{echo date("Y-m-d");}  ?>"  /> </td>
									</tr>
									
								
				
				
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
		$this->add1=$add1;
		$this->add2=$add2;		
                $this->city=$city;
		$this->state=$state;
		$this->maincontact=$maincontact;
		$this->email=$email;
		$this->phoneno=$phoneno;
		$this->expiry_date=$expiry_date;
		$this->type_id=$type_id;
		// server side validation
		$return =true;
		if($return){
		$update_sql_array = array();
		$update_sql_array['clientname'] = $this->clientname;
		$update_sql_array['zip'] = $this->zip;
		$update_sql_array['add1'] = $this->add1;
		$update_sql_array['add2'] = $this->add2;                
		$update_sql_array['city'] = $this->city;
		$update_sql_array['state'] = $this->state;
		$update_sql_array['maincontact'] = $this->maincontact;
		$update_sql_array['email'] = $this->email;
		$update_sql_array['phoneno'] = $this->phoneno;
		$update_sql_array['branchid'] = $branch;
		$update_sql_array['form_type_id'] = $form_type_id;
		
		
		$update_sql_array['customerno'] = $_SESSION['customerno'];
		$update_sql_array['userid'] = $_SESSION['user_id'];
		if(isset($_SESSION['ClientExtra'])){
			$update_sql_array['extra'] = $extra;
		
		}
												 
		if($_SESSION['customerno']!="14"){
			$update_sql_array['expiry_date'] = $this->expiry_date;
			$update_sql_array['type_id'] = $this->type_id;
		
		}
	
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
function branch_by_id($branchid){
		$sql="select * from ".BRANCH." where branchid=".$branchid."";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($result);
		return $row['branchname'];
		}
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
		<form action="client_details.php?index=search" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				<tr>
				<th>Name Of Client : </th>
				<td><input   type="text" value="<?php echo $_REQUEST['clientname'];?>" id="clientname" name="clientname" /></td>
				<th>Phoneno : </th>
				<td><input value="" name="phoneno" id="phoneno" type="text" />
				<span id="span_function"></span></td>
				</tr>
				<tr>
				<th>branch : </th>
				<td>
				
				<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="master"){
				$sql="select * from ".BRANCH." where customerno = ".$_SESSION['customerno'];
				$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
				?>
				<select name="branch" id="branchbox" > 
				<option value="all" >all</option>
				<?php 
				while($row= $this->db->fetch_array($resultpages))
				{
				
				?>
				<option value="<?php echo $row['branchid'];?>"  ><?php echo $row['branchname'];?></option>
				<?php } ?>
				</select>
				<?php } ?>
				</td>
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



function SearchRecord($clientname='',$phoneno='',$branch='')
{

$sql="select * from ".TBL_CLIENT." where 1  and isdeleted=0 and customerno=".$_SESSION['customerno']." ";
if($clientname)
{
$sql .= " and clientname like '%".$clientname."%'";
}
if($phoneno)
{
$sql .= " and phoneno  like '%".$phoneno."%'";
}
if($branch!="all" && $branch!=""){
$sql .=" and branchid=".$branch;
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
<div class="switch" style="width:300px"><?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="master"){ ?>
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
<td><a href="client_details.php?index=add">

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





<br class="clear"/>

<?php $this->StockSearchBox(); ?>

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
									$x=1;	
									while($row= $this->db->fetch_array($result))
									{
									?>
									<tr>
									
									<td><?php echo $x;?></td>
									<td title="<?php echo $row['clientname'];?>"><?php echo $row['clientname'];?></td>
									<td title="<?php echo $row['city'];?>"><?php echo $row['city'];?></td>
									<td title=""><?php echo $row['zip'];?></td>
									<td title=""><?php echo $row['phoneno'];?></td>
									<td title="<?php echo $row['email'];?>"><?php echo $row['email'];?></td>
									<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									
									<td><a href="client_details.php?index=View&id=<?php echo $row['clientid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="master"){ ?>
									| <a href="client_details.php?index=edit&id=<?php echo $row['clientid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<a href="javascript: void(0);" title="delete" class="help" 
onclick="javascript: if(confirm('Do u want to delete this record?')) { clientinfo.deleteupdate('<?php echo $row['clientid'];?>',{}) }; return false;" >
<img src="images/icon_delete.png" width="15px" height="15px" />
</a>
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



function get_clients($query)
{
$sql="SELECT * from ".TBL_CLIENT." where clientname LIKE '%".$query."%' and customerno=".$_SESSION['customerno']."";
$result= $this->db->query($sql,__FILE__,__LINE__);
$x=1;
$row= $this->db->fetch_array($result);
//echo json_encode($row);
$jsonarray= array();
$x=0;
echo "[";

$i=0;

while($row= $this->db->fetch_array($result))

{

echo '{"key": "'.$row['clientid'].'", "value": "'.$row['clientname'].'"},';

}

echo "]";

//$append.='{"cid":"'.$row['clientid'].'"';
//$append.=',"clientname" : "'.$row['clientname'].'" }';
//echo '{"cliendata":['.$append.']}';
}





}



?>