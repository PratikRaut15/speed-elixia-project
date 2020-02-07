<?php

class checkpoints{
var $clientname;
var $address1;
var $city;
var $phoneno;
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
function checfor_name($cname)
{
$sql="select * from ".TBL_CKPOINTS." where cname='".$cname."'";
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);
	if($row['cname']=="")
	{
		echo"ok";
	}else{
		echo"notok";
	}
}



function ajaxpush($cname,$cadd1,$cadd2,$cadd3,$ccity,$czip,$cstate,$radius,$cgeolat,$cgeolong){

	$insert_sql_array = array();
	$insert_sql_array['cname'] = $cname;
	$insert_sql_array['cadd1'] = $cadd1;
	$insert_sql_array['cadd2'] = $cadd2;
	$insert_sql_array['cadd3'] = $cadd3;
	$insert_sql_array['ccity'] = $ccity;
	$insert_sql_array['czip'] = $czip;
	$insert_sql_array['cstate'] = $cstate;
	$insert_sql_array['crad'] = $crad;
	$insert_sql_array['cgeolat'] = $cgeolat;
	$insert_sql_array['cgeolong'] = $cgeolong;
	$insert_sql_array['customerno'] = $_SESSION['customerno'];
	
	//print_r($insert_sql_array);
	$this->db->insert(TBL_CKPOINTS,$insert_sql_array);
	if($this->db->last_insert_id()!="")
	{echo "ok";}
	else
	{echo "notok";}


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
	<span>Add Service calls</span>
	<div class="switch" style="width:300px">
			<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="checkpoint.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="checkpoint.php?index=searchStudent">
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
	
					<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
					<table class="data" width="100%">
					<tr>
                    <th>Client Name:</th>
                    <td><div class="SAYT"><div id="clientbox"><input name="clientname" type="text" id="clientname" size="30" maxlength="30" onkeyup="showcreate();" /></div></div>
                    <span title="You must supply a client name" class="mandatory">*</span>          
                        <span id="manclientname" class="mandatory" style="display:none;">Please enter a client name.</span>                        
                        <input name="clientid" type="hidden" id="clientid" value="0" />                            
                    </td>
                  </tr>
                  <tr>
                    <th>Address:</th>
                    <td><input name="address1" type="text" id="address1" size="50" maxlength="50" onkeyup="showmodify();"/></td>
                    </tr>
                  <tr>
                    <td></td>
                    <td><input name="address2" type="text" id="address2" size="50" maxlength="50"  onkeyup="showmodify();"/></td>
                    </tr>
                  <tr>
                    <th>City:</th>
                    <td><input name="txtcity" type="text" id="txtcity" size="20" maxlength="20">
                      </td>
                  </tr>
                  <tr>
                    <th>State:</th>
                    <td><input name="txtstate" type="text" id="txtstate" size="30" maxlength="30">
                      </td>
                  </tr>
                  <tr>
                    <th>Zip:</th>
                    <td><input name="txtzip" type="text" id="txtzip" size="10" maxlength="10">
                      </td>
                  </tr>                      
                  <tr>
                    <th>Contact Person:</th>
                    <td><input name="contactperson" type="text" id="contactperson" size="20" maxlength="20" onkeyup="showmodify();"/></td>
                    </tr>
                  <tr>
                    <th>Email:</th>
                    <td><input name="email" type="text" id="email" size="30" maxlength="30">
                    <span id="manemail" class="mandatory" style="display:none;">Please enter a valid email address.</span>                                                                                
                      </td>
                  </tr>                                                        
                  <tr>                          
                    <th>Phone Number:</th>
                    <td><input name="phonenumber" type="text" id="phonenumber" size="10" maxlength="10" onkeyup="showmodify();"/></td>
                </tr>
                
                <!--  Client Extra Field   -->
                                  
                <!-- Extra Call Field 1  -->  
                                  
                <!-- Extra Call Field 2  -->  
                                  
                <!--  User Field 1   -->
                                  <tr>
                    <th>ABCD:</th>
                    <td><div class="SAYT"><div id="userfieldbox1"><input name="seruf1" type="text" id="seruf1" size="30" maxlength="30"/></div></div>
                    </td>
                  </tr>                
                                  
                <!--  User Field 2  -->
                                  <tr>
                    <th>PQRS:</th>
                    <td><div class="SAYT"><div id="userfieldbox2"><input name="seruf2" type="text" id="seruf2" size="30" maxlength="30"/></div></div>
                    </td>
                  </tr>                
                                                    
                  
                  <tr>
                    <th>Tracking No.:</th>
                    <td colspan="5"><input name="trackno" type="text" id="trackno" size="30">
                        (Automatically generated, if field left blank)</em>                        
                        <span id="mantrackno" class="mandatory" style="display:none;">Tracking number already exists.</span>                                                    
                    </td>
                  </tr>
                  <tr>
                    <th>Trackee:</th>
                    <td><select id="trackeeid" name="trackeeid">
                                        </select>
                      </td>
                  </tr>                  
                  <tr>
                    <th>Signature Required:</th>
                    <td> <input type="checkbox" name="chksig" id="chksig" checked>
                  </tr>                  
                  <tr>
                      <td></td>
                      <td>
                      <div id="hidecreate">                              
                      <input type="button" name="Create" id="Create" value="Create Client & Add Service Call" onclick="dosave();">                      
                      </div>                          
                      <div style="display:none;" id="hidemodify">                                                            
                      <input type="button" name="Modify" id="Modify" value="Modify Client & Add Service Call" onclick="domodify();">                                                                             
                        <input name="modifyclient" type="hidden" id="modifyclient" value="0" />                                                                                
                      </div>
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
							$this->address1=$address1;
							$this->city=$city;
							
							$return =true;
							 //server side validation
								
							if($this->Form->ValidField($clientname,'empty','Service name is empty')==false)
							$return =false;
									if($return){
												$insert_sql_array = array();
												$insert_sql_array['clientname'] = $this->clientname;
												$insert_sql_array['address1'] = $this->address1;
												$insert_sql_array['address2'] = $this->address2;
												$insert_sql_array['city'] = $this->city;
												$insert_sql_array['customerno'] = $_SESSION['customeno'];
												$insert_sql_array['userid'] = $_SESSION['user_id'];
												
												$this->db->insert(TBL_SERVICELIST,$insert_sql_array);
												$user_id = $this->db->last_insert_id();
											
												$_SESSION['msg'] = 'Service has been Successfully created';
												?>
												<script type="text/javascript">
												window.location = "checkpoint.php"
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
	function showAllchkpfInfo()
		{
			$sql="select * from ".TBL_CKPOINTS." where 1 ";
			$sql.=" order by checkpointid desc ";
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
		<span>Checkpoints List</span>
		<div class="switch" style="width:300px">
				<table width="300px" cellpadding="0" cellspacing="0">
					<tbody>
					<tr>
					<td>
					<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:100px"/>
					</td>
					<td><a href="checkpoint.php?index=searchStudent">
					<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
					</a>
					</td>
					<td><a href="checkpoint.php?index=addStudent">
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
									<th width="10%">Checkpoint Name</th>
									<th width="10%">Place</th>
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
									
									<td><?php echo $x++;?></td>
									<td title="<?php echo $row['cname'];?>"><?php echo $row['cname'];?></td>
									
									<td title=""><?php echo $row['ccity'];?></td>
									
									<td><a href="javascript: void(0);" title="delete the checkpoint" class="help" 
									onclick="javascript: if(confirm('Do u want to delete this checkpoint?')) { checkobj.unblockUser('<?php echo $row['checkpointid'];?>',{}) }; return false;" >
									<img src="images/icon_unblock.png" width="15px" height="15px" /></a>
									</td> 
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
								<a href="checkpoint.php">&laquo;&laquo;</a>
								<a href="checkpoint.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="checkpoint.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="checkpoint.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="checkpoint.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="checkpoint.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="checkpoint.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>
		
		<div align="right">Total Pages - <?php echo $lastpage;?></div>
		<div align="right">Total Records - <?php echo $numpages;?></div>
		
		</div>
		</div>
		
		<?php 
		
		}

// view client 
function ServiceView($student_id)
{
	$this->student_id=$student_id;
	$sql="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."' AND servicelistid='".$this->student_id."'";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Service Detail</span>
	<div class="switch" style="width:300px">
		<table width="300px" cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
				<td><a href="checkpoint.php">
				<input type="button" id="chart_bar2" name="chart_bar2" class="left_switch" value="View All" style="width:100px"/>
				</a></td>
				<td><a href="checkpoint.php?index=searchStudent">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a></td>
				<td><a href="checkpoint.php?index=addStudent">
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
				<th>Address:  </th>
				<td><?php echo $row['address1'];?> </td>
				</tr>
				
				
				<tr>
				<th>City: </th>
				<td><?php echo $row['city'];?> </td>
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
	$this->servicelistid=$id;
	switch($runat){
	case 'local' :
			if(count($_POST)>0 and $_POST['submit']=='Submit'){
				extract($_POST);
				$this->clientname = $clientname;
			}
			$FormName = "frm_addstud";
			$ControlNames=array("clientname"=>array('clientname',"''","Please enter service name. ","span_cname"));
			$ValidationFunctionName="CheckAddstudentValidity";
			$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
			echo $JsCodeForFormValidation;
			
			
			
			$sql="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."' AND servicelistid='".$this->servicelistid."'";
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
				<td><a href="checkpoint.php">
				<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
				</a>
				</td>
				<td><a href="checkpoint.php?index=searchStudent">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a>
				</td>
				<td><a href="checkpoint.php?index=addStudent">
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
				<th >Address :</th>
				<td><input  type="text" value="<?php echo $row['address1']; ?>" name="address1" id="address1"  size="50"/>
				<span id="span_address1"></span></td>
				</tr>
				<tr>
				<th >City :</th>
				<td><input  type="text" value="<?php echo $row['city']; ?>" name="city" id="city" size="50" />
				<span id="span_city"></span></td>
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
		$this->address1=$address1;
		$this->city=$city;
		
		// server side validation
		$return =true;
		if($return){
		$update_sql_array = array();
		$update_sql_array['clientname'] = $this->clientname;
		$update_sql_array['address1'] = $this->address1;
		$update_sql_array['city'] = $this->city;
		$this->db->update(TBL_SERVICELIST,$update_sql_array,'servicelistid',$this->servicelistid);
		$_SESSION['msg']='client has been updated successfully';
		
		?>
		<script type="text/javascript">
		window.location = "checkpoint.php"
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
$sql_1="delete from ".TBL_SERVICELIST." where servicelistid='".$this->id."'";
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
		<form action="checkpoint.php?index=searchStudent" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				<tr>
				<th>Name Of Service : </th>
				<td><input   type="text" value="<?php echo $_REQUEST['clientname'];?>" id="clientname" name="clientname" /></td>
				
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



function SearchRecord($clientname='')
{
$sql="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."' AND 1 ";
if($clientname)
{
$sql .= " and clientname like '%".$clientname."%'";
}

$sql.=" order by servicelistid desc ";
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
<td><a href="checkpoint.php">
<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
</a>
</td>
<td>
<input type="button" id="chart_line" name="chart_line" class="middle_switch active" value="Search" style="width:100px" />
</td>
<td><a href="checkpoint.php?index=addStudent">

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
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Client Name</th>
									<th width="8%">Address</th>
									<th width="8%">City</th>
									
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
									<td title="<?php echo $row['address1'];?>"><?php echo $row['address1'];?></td>
									<td title=""><?php echo $row['city'];?></td>
									
									<td title=""><?php echo $row['userid'];?></td>
									
									<td><a href="checkpoint.php?index=studentView&student_id=<?php echo $row['servicelistid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] != 'User') {?>
									| <a href="checkpoint.php?index=editStudent&student_id=<?php echo $row['servicelistid'];?>" title="Edit" class="help">
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
								<a href="checkpoint.php">&laquo;&laquo;</a>
								<a href="checkpoint.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="checkpoint.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="checkpoint.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="checkpoint.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="checkpoint.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="checkpoint.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="checkpoint.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>

<div align="right">Total Pages - <?php echo $lastpage;?></div>
<div align="right">Total Records - <?php echo $numpages;?></div>

</div>

</div>

<?php 

}





























































}



?>