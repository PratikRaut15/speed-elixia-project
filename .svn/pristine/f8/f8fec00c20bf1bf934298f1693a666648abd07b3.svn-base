<?php

class checklist{

	
	var $Form;
	var $db;
	var $tabName;




		function __construct(){
			$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
			$this->validity = new ClsJSFormValidation();
			$this->Form = new ValidateForm();	
		}
		
		function getlastupdatedby($userid)
		{
			$sql="select * from ".TBL_ADMIN_USER." where userid=".$userid." ";
			$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
			$row= $this->db->fetch_array($resultpages) ;
			echo $row['username'];
		}
		
		function deleteupdate($ftid)
		{
			ob_start();
			$update_array = array();
			$this->updatetrackee("form",$trackeeid);
			$update_array['isdeleted'] = '1';
			$this->db->update(TBL_FORM,$update_array,'ftid',$ftid);
			$_SESSION['msg']='Record has been deleted successfully';
			?>
			<script type="text/javascript">
			window.location = "checklist.php"
			</script>
			<?php
			
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}

		function getservisedetailsbyid($sid)
		{
			$sql="select price,expectedtime from ".TBL_SERVICELIST." where ftid='".$sid."' and customerno='".$_SESSION['customerno']."'";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result);   
			$array1=array();
			if($row['price']!=""){
				$array1['price']=$row['price'];
			}else{
				$array1['price']=0;
			}
		
			$array1['expectedtime']=$row['expectedtime'];
		
		echo json_encode($array1);    
		}
		
		
// add client function 
	function addclient($runat)
	{
		switch($runat){
		case 'local' :
		if(count($_POST)>0 and $_POST['submit']=='Submit'){
			extract($_POST);
			$this->servicename = $servicename;
			
		}
		$FormName = "frm_add_client";
		$ControlNames=array("servicename"=>array('servicename',"''","Please Enter Checklist Name","span_cname"),
                    "price"=>array('price',"''","Please enter price. ","span_price"),
                    "expectedtime"=>array('expectedtime',"''","Please enter expected time. ","span_expectedtime")

                    );
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Add checklist</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
			<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="checklist.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="checklist.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<a href="checklist.php?index=add">
						<input type="button" id="chart_line" name="chart_line" class="right_switch " value="Add New" style="width:100px" />
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
										<th >checklist Name:</th>
										<td><input  type="text" value="" name="fname" id="fname" />
										<input  type="hidden" name="checklist_add" id="checklist_add"/>
										<span id="span_fname"></span></td>
									</tr>
									<tr>
										<th>form type
										</th>
										<td>
										<?php 
											$sql="select * from ".TBL_FORM_TYPE." where customerno=".$_SESSION['customerno']."";
											$result= $this->db->query($sql,__FILE__,__LINE__);
											while($row= $this->db->fetch_array($result))
											{
											?>
											<input type="checkbox" value="<?php echo $row['form_type_id']; ?>" name="form_type[]" id="form_type<?php echo $row['form_type_id']; ?>" />
											<?php echo $row['form_type_name'];  ?>
											<?php 
											}
										
											
										?>
										</td>
									</tr>
									<tr>
										<th >Cheklist meta data :</th>
										<td>
										<select id="type">
										<option value="0">-select-</option>
										<option value="1">textbox</option>
										<option value="2">checkbox</option>
										<option value="3">selectbox</option>
										
										</select>
										<input  type="button" id="add" value="add" name="add"  onclick="add_button();" />
                                          <table id="dom_generator" class="data" width="100%">
										
										</table>
										<div id='sequence'></div>
										<span id="span_price"></span></td>
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
								$this->fname=$fname;
								$this->textfield=$textfield;
								$this->selectbox=$selectbox;
								$this->checkbox=$checkbox;
								$this->option_list=$option_list;
								$this->option=$option;
							
							$return =true;
							 //server side validation
							if($this->Form->ValidField($fname,'empty','checklist name is empty')==false)
							$return =false;
                           
									if($return){
												//TBL_FORM
												$this->updatetrackee("form",$trackeeid);													
												$insert_sql_array = array();
												$insert_sql_array['fname'] = $this->fname;
												$insert_sql_array['form_type'] = implode(",",$form_type);
												$insert_sql_array['customerno'] = $_SESSION['customerno'];
												$insert_sql_array['userid'] = $_SESSION['user_id'];
												$this->db->insert(TBL_FORM,$insert_sql_array);
												$ftid = $this->db->last_insert_id();
												
												// TBL_FORM_META
												
												$insert_meta_sql_array = array();
												$insert_meta_sql_array['textfields'] = count($this->textfield);
												$insert_meta_sql_array['checkbox'] = count($this->checkbox);
												$insert_meta_sql_array['dropdown'] = count($this->selectbox);
												$insert_meta_sql_array['ftid'] = $ftid;												
												$insert_meta_sql_array['customerno'] = $_SESSION['customerno'];
												$insert_meta_sql_array['userid'] = $_SESSION['user_id'];
												$this->db->insert(TBL_FORM_META,$insert_meta_sql_array);
												$mdid = $this->db->last_insert_id();
												
												//TBL_FORM_DATA
												
												//text field
												
												
												
												$priority=1;
												// sequencing order 
												$i=$j=$k=0;
												$opc=0;
												ini_set('max_execution_time', 300);
												for($b=0;$b<count($sequence_order);$b++)
												{
													
													
													// text ends 
													if($sequence_order[$b]=="text"){
																$insert_text_sql_array = array();
																$insert_text_sql_array['mdid'] = $ftid;
																$insert_text_sql_array['lable'] = $textfield[$i];
																$insert_text_sql_array['type'] = "1";
																$insert_text_sql_array['priority'] = $priority++;
																$insert_text_sql_array['customerno'] = $_SESSION['customerno'];
																$insert_text_sql_array['userid'] = $_SESSION['user_id'];
																$this->db->insert(TBL_FORM_DATA,$insert_text_sql_array);
																$i++;
													}
													// text ends 
													
													if($sequence_order[$b]=="checkbox"){
																$insert_checkbox_sql_array = array();
																$insert_checkbox_sql_array['mdid'] = $ftid;
																$insert_checkbox_sql_array['lable'] = $checkbox[$j];
																$insert_checkbox_sql_array['type'] = "2";
																$insert_checkbox_sql_array['priority'] = $priority++;
																$insert_checkbox_sql_array['customerno'] = $_SESSION['customerno'];
																$insert_checkbox_sql_array['userid'] = $_SESSION['user_id'];
																$this->db->insert(TBL_FORM_DATA,$insert_checkbox_sql_array);
																$j++;
													}
													// check box ends here 
													if($sequence_order[$b]=="select"){
																$insert_dropdown_sql_array = array();
																$insert_dropdown_sql_array['mdid'] = $ftid;
																$insert_dropdown_sql_array['lable'] = $selectbox[$k];
																$insert_dropdown_sql_array['type'] = "3";
																$insert_dropdown_sql_array['priority'] = $priority++;
																$insert_dropdown_sql_array['customerno'] = $_SESSION['customerno'];
																$insert_dropdown_sql_array['userid'] = $_SESSION['user_id'];
																$this->db->insert(TBL_FORM_DATA,$insert_dropdown_sql_array);
																$dtid = $this->db->last_insert_id();
																
															$lf=$option_list[$k];
																
															
																for($f=0;$f<$lf;$f++){
																	if($option[$opc]!=""){
																		$insert_option_sql_array = array();
																		$insert_option_sql_array['dtid'] = $dtid;
																		$insert_option_sql_array['ftid'] = $ftid;
																		$insert_option_sql_array['option_name'] = $option[$opc++];
																		$insert_option_sql_array['customerno'] = $_SESSION['customerno'];
																		$insert_option_sql_array['userid'] = $_SESSION['user_id'];
																		$this->db->insert(TBL_FORM_OPTION,$insert_option_sql_array);
																		}
																	
																}
																$k++;
																
																	
															
														}
														
												
												}
												
												
												
																			
												
												$_SESSION['msg'] = 'Checklist has been Successfully created';
												?>
												<script type="text/javascript">
												window.location = "checklist.php"
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
	function showAllServicefInfo()
		{
			$sql="select * from ".TBL_FORM." where isdeleted=0 and customerno=".$_SESSION['customerno']." ";
			$sql.=" order by ftid asc ";
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
		<span>check List</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="checklist.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="checklist.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<a href="checklist.php?index=add">
						<input type="button" id="chart_line" name="chart_line" class="right_switch " value="Add New" style="width:100px" />
						</a>
					</td>
				</tr>
				</tbody>
			</table>
			<?php  } ?>
		</div>		
		</div>
		
		
		
		<br class="clear"/>
		
		<div class="content">
		<?php 
		if($_SESSION['groups'] == 'Superadmin')
		{
		?>
		<div align="right"> 
		 
		
		</div>		
		<?php } ?>
						<form id="form_data" name="form_data" action="" method="post">
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Checklist Name</th>
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
									<td title="<?php echo $row['fname'];?>"><?php echo $row['fname'];?></td>
									
										<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									
									<td><a href="checklist.php?index=View&checklist_id=<?php echo $row['ftid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
									| <a href="checklist.php?index=edit&checklist_id=<?php echo $row['ftid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									
									<a href="javascript: void(0);" title="delete" class="help" 
									onclick="javascript: if(confirm('Do u want to delete this record?')) { checklistinfo.deleteupdate('<?php echo $row['ftid'];?>',{}) }; return false;" >
									<img src="images/icon_delete.png" width="15px" height="15px" />
									</a>
									
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
								<a href="checklist.php">&laquo;&laquo;</a>
								<a href="checklist.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="checklist.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="checklist.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="checklist.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="checklist.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="checklist.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="checklist.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="checklist.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="checklist.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="checklist.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="checklist.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="checklist.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>
		
		<div align="right">Total Pages - <?php echo $lastpage;?></div>
		<div align="right">Total Records - <?php echo $numpages;?></div>
		
		</div>
		</div>
		
		<?php 
		
		}

// view client 
function ServiceView($checklist_id)
{
	$this->checklist_id=$checklist_id;
	$sql="select * from ".TBL_FORM."  where ftid='".$this->checklist_id."' and customerno=".$_SESSION['customerno']."";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Checklist Detail</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
		<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="checklist.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="checklist.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<a href="checklist.php?index=add">
						<input type="button" id="chart_line" name="chart_line" class="right_switch " value="Add New" style="width:100px" />
						</a>
					</td>
				</tr>
				</tbody>
			</table>
			<?php }?>
	
	</div>		
	</div>
	<br class="clear"/>
	<div class="content">
	<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
		<table class="data" width="100%">
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
				<th>Checklist Name:  </th>
				<td><?php echo $row['fname'];?> </td>
				</tr>
				
			<tr>
				<th>form type
				</th>
				<td> 
				
				<?php 
					$sql2="select * from ".TBL_FORM_TYPE." where customerno=".$_SESSION['customerno']."";
					$result2= $this->db->query($sql2,__FILE__,__LINE__);
					while($row2= $this->db->fetch_array($result2))
					{
					?>
					 <?php if(in_array($row2['form_type_id'],explode(",",$row['form_type']))){echo $row2['form_type_name'].',';} 
					}
				
					
				?>
				
				</td>
			</tr>
				
				
				<tr>
					<th>elements</th>
					<td>
					<table  class="data" width="100%">
					
					<?php
										
										$sql1="select * from ".TBL_FORM_DATA."  where mdid=".$row['ftid']." order by dtid asc ";
										$result1= $this->db->query($sql1,__FILE__,__LINE__);
										while($row1= $this->db->fetch_array($result1))
										{
											if($row1['type']==1){
											?>
											<tr><th><?php echo $row1['lable'];?></th><td>text field</td></tr>
											<?php
											}
											if($row1['type']==2){
											?>
											<tr><th><?php echo $row1['lable'];?></th><td>check box</td></tr>
											<?php
											
											}
											if($row1['type']==3){
											?>
											<tr><th><?php echo $row1['lable'];?></th><td>select box</td></tr>
											
											
											<?php
															$sql2="select * from ".TBL_FORM_OPTION."  where dtid=".$row1['dtid']." order by oid asc ";
															$result2= $this->db->query($sql2,__FILE__,__LINE__);
															while($row2= $this->db->fetch_array($result2))
															{
															
														?>
											
															<tr><th></th><td><?php echo $row2['option_name']; ?></td></tr>
															<?php  } ?>
											
											
											<?php
											
											}
										
										}
										?>
										</table>
						</td>
				</tr>
				
				
		</table>
	</form>
	</div>
	</div>
	<br class="clear"/>
	<?php
}

// edit client

function editclient($runat,$id)
{
	$this->ftid=$id;
	switch($runat){
	case 'local' :
			if(count($_POST)>0 and $_POST['submit']=='Submit'){
				extract($_POST);
				$this->servicename = $servicename;
			}
			$FormName = "frm_addstud";
			$ControlNames=array("servicename"=>array('servicename',"''","Please enter service name. ","span_cname"));
			$ValidationFunctionName="CheckAddstudentValidity";
			$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
			echo $JsCodeForFormValidation;
			
			
			
			$sql="select * from ".TBL_FORM." where ftid='".$this->ftid."'";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result);
		?>
		<div class="onecolumn">
		<div class="header">
		<span>Edit list</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="checklist.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="checklist.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<a href="checklist.php?index=add">
						<input type="button" id="chart_line" name="chart_line" class="right_switch " value="Add New" style="width:100px" />
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
										<th >checklist Name:</th>
										<td><input  type="text" value="<?php echo $row['fname'];?>" name="fname" id="fname"  />
										<input  type="hidden" name="checklist_edit" id="checklist_edit"/>
										<span id="span_fname"></span></td>
									</tr>
									
									<tr>
										<th>form type
										</th>
										<td> 
										
										<?php 
											$sql2="select * from ".TBL_FORM_TYPE." where customerno=".$_SESSION['customerno']."";
											$result2= $this->db->query($sql2,__FILE__,__LINE__);
											while($row2= $this->db->fetch_array($result2))
											{
											?>
											<input type="checkbox" <?php if(in_array($row2['form_type_id'],explode(",",$row['form_type']))){?>checked="checked" <?php } ?>  value="<?php echo $row2['form_type_id']; ?>" name="form_type[]" id="form_type<?php echo $row2['form_type_id']; ?>" />
											<?php echo $row2['form_type_name'];  ?>
											<?php 
											}
										
											
										?>
										
										</td>
									</tr>
									<tr>
										<th >Cheklist meta data :</th>
										<td>
										<select id="type">
										<option value="0">-select-</option>
										<option value="1">textbox</option>
										<option value="2">checkbox</option>
										<option value="3">selectbox</option>
										
										</select>
										<input  type="button" id="add" value="add" name="add"  onclick="add_button_edit();" />
										<table id="dom_generator" class="data" width="100%">
										<?php
										
										$sql1="select * from ".TBL_FORM_DATA."  where mdid=".$row['ftid']." order by dtid asc ";
										$result1= $this->db->query($sql1,__FILE__,__LINE__);
										$_total_elements=0;
										$_checkbox=0;
										$_textfield=0;
										$_selectbox=0;
										$_options=0;
										
										
										
										
										
										while($row1= $this->db->fetch_array($result1))
										{
												if($row1['type']==1){
												$_textfield++
												?>
												<tr id='<?php echo ++$_total_elements; ?>' >
													<th>text field</th>
													<td>
													<input type='text' value="<?php echo $row1['lable']; ?>" name='textfield[]' id='textfield<?php echo $_total_elements; ?>'/>
													</td>
													<td class="rem" id='d_<?php echo $_total_elements; ?>'>remove</td>
												</tr>
												<?php
												}
												if($row1['type']==2){
												$_checkbox++
												?>
												<tr id='<?php echo ++$_total_elements; ?>' >
													<th>checkbox</th>
													<td>
													<input type='text' value="<?php echo $row1['lable']; ?>" name='checkbox[]' id='checkbox<?php echo $_total_elements; ?>'/>
													</td>
													<td class="rem" id='d_<?php echo $_total_elements; ?>'>remove</td>
												</tr>
												<?php
												}
												if($row1['type']==3){
												$_selectbox++
												
												?>
												<tr id='<?php echo ++$_total_elements; ?>' >
													<th>selectbox</th>
													
													<td><input type='text' name='selectbox[]' value="<?php echo $row1['lable']; ?>" id='selectbox<?php echo $_total_elements; ?>'/></td>
													
													<td >
														<table class='data' id='p_o_<?php echo $_total_elements; ?>'>
														<?php
															$sql2="select * from ".TBL_FORM_OPTION."  where dtid=".$row1['dtid']." order by oid asc ";
															$result2= $this->db->query($sql2,__FILE__,__LINE__);
															$_total_elements_sub=0;
															while($row2= $this->db->fetch_array($result2))
															{
															 $_options++;
														?>
														<tr><td>options <?php  ++$_total_elements_sub; ?></td><td>
														<input type="text" value="<?php echo $row2['option_name']; ?>"  id="option" name="option[]" id="option"></td></tr>
														<?php }
														
														?>
														</table>
														
													
													<input type='hidden' name='option_list[]' value='<?php echo  $_total_elements_sub; ?>' id='options<?php echo $_total_elements; ?>'  >
													</td>
													
													<td class="op" id='o_<?php echo $_total_elements; ?>'>options</td>
													<td class="rem" id='d_<?php echo $_total_elements; ?>'>remove</td>
												</tr>
												<?php
												}
										
										 
										} ?>
										</table>
										<div id='sequence'>
										<?php $sql1="select * from ".TBL_FORM_DATA."  where mdid=".$row['ftid']." order by priority asc ";
										$result1= $this->db->query($sql1,__FILE__,__LINE__);
										while($row1= $this->db->fetch_array($result1))
										{
										?>
										<input id="sd_<?php echo $row1['priority']; ?>" type="hidden" value="<?php if($row1['type']=='1'){echo 'text';}else if($row1['type']=='2'){echo 'checkbox';}else if($row1['type']=='3'){echo 'select';}  ?>" name="sequence_order[]">
										<?php
										
										}
										?>
										
										</div>
										<span id="span_price"></span>
										
										</td>
									</tr>
									
									
									<tr>
									<td colspan="2">
									<input type="hidden" value="<?php echo  $_total_elements; ?>" id="_total_elements" name="_total_elements" />
									
									<input type="submit" name="submit" value="Submit" 
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
		<br class="clear"/>
		<?php
		break;
		case 'server' :	
		extract($_POST);
								$this->fname=$fname;
								$this->textfield=$textfield;
								$this->selectbox=$selectbox;
								$this->checkbox=$checkbox;
								$this->option_list=$option_list;
								$this->option=$option;
                                                                $this->updatetrackee("form",$trackeeid);	
								$return =true;
								if($return){
												$update_sql_array = array();
												$update_sql_array['fname'] = $this->fname;
												$update_sql_array['form_type'] = implode(",",$form_type);
												$this->db->update(TBL_FORM,$update_sql_array,'ftid',$this->ftid);
												
												// TBL_FORM_META
												$ftid=$this->ftid;
												
												$slq_edit_case2="delete  from ".TBL_FORM_OPTION." where ftid=".$this->ftid.""; 
												$this->db->query($slq_edit_case2,__FILE__,__LINE__);

												
												$slq_edit_case="delete  from ".TBL_FORM_DATA." where mdid=".$this->ftid.""; 
												$this->db->query($slq_edit_case,__FILE__,__LINE__);
												
												
												
												
												$priority=1;
												// sequencing order 
												$i=$j=$k=0;
												$opc=0;
												
												
												for($b=0;$b<count($sequence_order);$b++)
												{
													
													
													// text ends 
													if($sequence_order[$b]=="text"){
																$insert_text_sql_array = array();
																$insert_text_sql_array['mdid'] = $ftid;
																$insert_text_sql_array['lable'] = $textfield[$i];
																$insert_text_sql_array['type'] = "1";
																$insert_text_sql_array['priority'] = $priority++;
																$insert_text_sql_array['customerno'] = $_SESSION['customerno'];
																$insert_text_sql_array['userid'] = $_SESSION['user_id'];
																$this->db->insert(TBL_FORM_DATA,$insert_text_sql_array);
																$i++;
													}
													// text ends 
													
													if($sequence_order[$b]=="checkbox"){
																$insert_checkbox_sql_array = array();
																$insert_checkbox_sql_array['mdid'] = $ftid;
																$insert_checkbox_sql_array['lable'] = $checkbox[$j];
																$insert_checkbox_sql_array['type'] = "2";
																$insert_checkbox_sql_array['priority'] = $priority++;
																$insert_checkbox_sql_array['customerno'] = $_SESSION['customerno'];
																$insert_checkbox_sql_array['userid'] = $_SESSION['user_id'];
																$this->db->insert(TBL_FORM_DATA,$insert_checkbox_sql_array);
																$j++;
													}
													// check box ends here 
													if($sequence_order[$b]=="select"){
																$insert_dropdown_sql_array = array();
																$insert_dropdown_sql_array['mdid'] = $ftid;
																$insert_dropdown_sql_array['lable'] = $selectbox[$k];
																$insert_dropdown_sql_array['type'] = "3";
																$insert_dropdown_sql_array['priority'] = $priority++;
																$insert_dropdown_sql_array['customerno'] = $_SESSION['customerno'];
																$insert_dropdown_sql_array['userid'] = $_SESSION['user_id'];
																$this->db->insert(TBL_FORM_DATA,$insert_dropdown_sql_array);
																$dtid = $this->db->last_insert_id();
																
															$lf=$option_list[$k];
																
															
																for($f=0;$f<$lf;$f++){
																	if($option[$opc]!=""){
																		$insert_option_sql_array = array();
																		$insert_option_sql_array['dtid'] = $dtid;
																		$insert_option_sql_array['ftid'] = $ftid;
																		$insert_option_sql_array['option_name'] = $option[$opc++];
																		$insert_option_sql_array['customerno'] = $_SESSION['customerno'];
																		$insert_option_sql_array['userid'] = $_SESSION['user_id'];
																		$this->db->insert(TBL_FORM_OPTION,$insert_option_sql_array);
																		}
																	
																}
																$k++;
																
																	
															
														}
		
		}
		
		$_SESSION['msg']='client has been updated successfully';
		
		?>
		<script type="text/javascript">
		window.location = "checklist.php"
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



function StockSearchBox()
{
?>
<div id="searchboxbg">
		<form action="checklist.php?index=search" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				<tr>
				<th>Name Of Checklist : </th>
				<td><input   type="text" value="<?php echo $_REQUEST['fname'];?>" id="fname" name="fname" /></td>
				
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



function SearchRecord($fname='')
{
$sql="select * from ".TBL_FORM." where  isdeleted=0 and customerno=".$_SESSION['customerno']." ";
if($fname)
{
$sql .= " and fname like '%".$fname."%'";
}

$sql.=" order by ftid  desc ";
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
<td><a href="checklist.php">
<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
</a>
</td>
<td>
<input type="button" id="chart_line" name="chart_line" class="middle_switch " value="Search" style="width:100px" />
</td>
<td><a href="checklist.php?index=add">

<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:100px" />

</a>

</td>

</tr>

</tbody>

</table>
<?php  }?>
</div>	



</div>

<br class="clear"/>





<br class="clear"/>

<?php $this->StockSearchBox(); ?>

<div class="content">




<div align="right"> 


</div>	


<form id="form_data" name="form_data" action="" method="post">
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Checklist Name</th>
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
									<td title="<?php echo $row['fname '];?>"><?php echo $row['fname'];?></td>
									
										<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									
									<td><a href="checklist.php?index=View&checklist_id=<?php echo $row['ftid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
									| <a href="checklist.php?index=edit&checklist_id=<?php echo $row['ftid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<a href="javascript: void(0);" title="delete" class="help" 
									onclick="javascript: if(confirm('Do u want to delete this record?')) { checklistinfo.deleteupdate('<?php echo $row['ftid'];?>',{}) }; return false;" >
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
								<a href="checklist.php">&laquo;&laquo;</a>
								<a href="checklist.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="checklist.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="checklist.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="checklist.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="checklist.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="checklist.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="checklist.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="checklist.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="checklist.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="checklist.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="checklist.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="checklist.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>

<div align="right">Total Pages - <?php echo $lastpage;?></div>
<div align="right">Total Records - <?php echo $numpages;?></div>

</div>

</div>

<?php 

}
function updatetrackee($type,$trackeeid=null){
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



function get_result_by_serviceid($service_id){

$sql1="select * from ".TBL_FORM." where ftid in 
				(select distinct(ftid) from ".TBL_FORM_ACTIVE_DATA." where customerno=".$_SESSION['customerno']." and servicecall_id =".$service_id.") 
				and customerno=".$_SESSION['customerno']." order by ftid asc ";
$result1= $this->db->query($sql1,__FILE__,__LINE__);

$x=1;
?>

				

				
<div class="onecolumn">
				<div class="header">
					<span>Check list report</span>
					
						</div>
						<br class="clear"/>
						<div class="content">
				</div>
				
				<div class="content">
			<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">  
			<tbody>
				<?php 		
				while($row1= $this->db->fetch_array($result1))
				{
				
				
					
				?>
				<tr>
					
					<td>
					<div class="onecolumn">
				<div class="header">
					<span class="help" title="Click To View data"><?php echo $row1['fname']; ?></span>
					</div>
					<br class="clear"/>
					<div class="content" >
					<table class="data" width="100%">
					<?php
					$sql2="select  * from ".TBL_FORM_ACTIVE_DATA." 
					inner join ".TBL_FORM_DATA." on ".TBL_FORM_ACTIVE_DATA.".dtid=".TBL_FORM_DATA.".dtid 
					where 
					 
					".TBL_FORM_ACTIVE_DATA.".customerno=".$_SESSION['customerno']." and 
					 ".TBL_FORM_ACTIVE_DATA.".ftid=".$row1['ftid']." order by ".TBL_FORM_DATA.".dtid asc ";
					$result2= $this->db->query($sql2,__FILE__,__LINE__);
					//$row2= $this->db->fetch_array($result2);
					
					while($row2= $this->db->fetch_array($result2))
					{
					?>
						<tr>	
						
								<th><?php echo $row2['lable']; ?></th>
							<?php if($row2['type']=='1'){ ?>
								<td><?php echo $row2['value']; ?></td>
							<?php }elseif($row2['type']=='2'){ ?>
								<td><?php if($row2['value']==1){echo "Yes"; }else{echo "No";}?></td>
							<?php }elseif($row2['type']=='3'){?>
								<td>
									<?php
									$sql4="select * from ".TBL_FORM_OPTION." where dtid=".$row2['dtid']." and oid=".$row2['value']." ";
									$result4= $this->db->query($sql4,__FILE__,__LINE__);
									$row4= $this->db->fetch_array($result4);
									echo $row4['option_name'];
									
									
									 ?>
								</td>
								
								
							<?php } ?>
						</tr>
					

					<?php
					}
					?>
					<tr>
					<td  colspan="2">
								<?php 
								echo $row1['servicecall_id'];
								$this->generate_imageurl($row1['ftid'],$service_id);
								?>
								</td>
					
					</tr>
					 </table>
					</div></div>
					</td>
					
				</tr>			
				<?php 
				$x++;
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
function generate_imageurl($ftid,$serviceid){
$sql="select * from ".TBL_DEVICE." left outer join ".TBL_SERVICECALL."  on ".TBL_DEVICE.".trackeeid= ".TBL_SERVICECALL.".trackeeid  where serviceid=".$serviceid." and   ".TBL_DEVICE.".customerno=".$_SESSION['customerno'] ;
 $result= $this->db->query($sql,__FILE__,__LINE__);
 $row=$this->db->fetch_row($result);

$list = glob("customer/".$_SESSION['customerno']."/".$row['3']."/photos/service/".$serviceid."_".$ftid."_*.jpeg");
for($i=0;$i<count($list);$i++)
{
	echo $str="<img src='".$list[$i]."' style='border:5px solid #ccc; margin:5px;' />";
}



}

























}



?>