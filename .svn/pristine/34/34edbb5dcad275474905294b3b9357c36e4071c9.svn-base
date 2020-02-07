<?php

class discount{


var $dis_id;
var $dis_amt;
var $dis_code;
var $expiry ;
var $is_mass ;
var $dis_category;
var $branchid;
var $Form;
var $db;





	function __construct(){
			$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
			$this->validity = new ClsJSFormValidation();
			$this->Form = new ValidateForm();	
	}

	function getlastupdatedby($userid)
	{
	$sql="select * from ".TBL_ADMIN_USER." where userid=".$userid."  ";
	$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
	$row= $this->db->fetch_array($resultpages) ;
	echo $row['username'];
	}

	function deleteupdate($dis_id)
	{
	ob_start();
	$update_array = array();
	$update_array['isdeleted'] = '1';
	$this->db->update(TBL_DISCOUNT,$update_array,'dis_id',$dis_id);
	$_SESSION['msg']='Record has been deleted successfully';
	
	?>
	<script type="text/javascript">
	window.location = "<?php echo $_SERVER['PHP_SELF']; ?>"
	</script>
	<?php
	
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
	}
// add client function 
	function adddiscount($runat)
	{
		switch($runat){
		case 'local' :
		if(count($_POST)>0 and $_POST['submit']=='Submit'){
			extract($_POST);
			$this->dis_code = $dis_code;
			
		}
		$FormName = "frm_addstud";
		$ControlNames=array(
								"dis_code"=>array('dis_code',"ALPHANUMERIC","Please Enter Discount code","span_dis_code"),
								
								"expiry"=>array('expiry',"Date","Please Enter expiry date ","span_expiry"),
								"category"=>array('category',"","Please select category ","span_category")
								
								
		
							);
		$ValidationFunctionName="CheckValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Add Discount</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
			<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="discount.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch " value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="discount.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
					<a href="discount.php?index=add">
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
									<tr  >
										<th width="30%">Category:</th>
										<td>
										<select name="category">
										<option value="">select</option>
										<?php 
										$sql="select * from ".TBL_DISCOUNT_TYPE." where customerno=".$_SESSION['customerno']." ";
										$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
										while($row= $this->db->fetch_array($resultpages)){
										?>
										<option value="<?php echo $row['dtype_id']; ?>"><?php echo $row['dt_name']; ?></option>
										<?php }?>
										
										


										</select>
										<span id="span_category"></span>
										</td>
									</tr>
									<tr>
										<th >Discount code:</th>
										<td><input  type="text"  name="dis_code" id="dis_code" />
										<span id="span_dis_code"></span></td>
									</tr>
									<tr>
										<th >discount remarks:</th>
										<td><textarea id="defination" name="defination"></textarea>
									</tr>
									<tr>
									<th>Discount type</th>
									<td>
									Rupees<input type="radio"  id="amountype_r"  name="amountype" value="0" onclick="javascript:jQuery('#amount_id').show();jQuery('#percent_id').hide();jQuery('#span_dis_amt').html('');" />
									Percentage<input type="radio"  id="amountype_p"  name="amountype"  value="1" onclick="javascript:jQuery('#amount_id').hide();jQuery('#percent_id').show(); jQuery('#span_dis_per').html('');" checked="checked" />
									</td>
									</tr>
									<tr id="amount_id" style="display:none;">
										<th >Discount amount :</th>
										<td><input type="text"  name="dis_amt" id="dis_amt" onkeyup="javascript:post_tax();"  />
										<span id="post_val_span"></span>
										<input type="hidden" name="post_tax_discount" id="posttax" />
										
										<span id="span_dis_amt"></span></td>
									</tr>
									<tr  id="percent_id">
										<th >Discount percent :</th>
										<td><input type="text"  name="dis_percent" id="dis_percent"  />
										<span id="span_dis_per"></span></td>
									</tr>
									<tr>
										<th >Expiry:</th>
										<td><input type="text"  name="expiry" id="expiry" class="datepicker1"/>
										<span id="span_expiry"></span></td>
									</tr>
									<tr>
										<th >Discount type:</th>
										<td>Specific<input type="radio" value="0"  name="is_mass" id="is_mass0" onclick="javascript:jQuery('#cname_sayt').show();jQuery('#Branch').hide()" />
											General<input type="radio"  value="1" name="is_mass" id="is_mass1"  onclick="javascript:jQuery('#cname_sayt').hide();jQuery('#Branch').show();" checked="checked" />
										<span id="span_dis_type"></span></td>
									</tr>
									
									
								
									<tr id="cname_sayt" style="display:none;">
										<th >Client phone no:</th>
										<td><input type="text"  name="clientno" id="clientno" onkeyup="sayt_client();" autocomplete="off"/>
										<span  id="span_clientno"></span>
										<div id="listview">
										<span id="clientname"></span>
										<ul id="sayt_list" style="text-decoration:none; font:Arial, Helvetica, sans-serif; ">
										</ul>
										</div>
										<input type="hidden"  name="clientid" id="clientid"  />
										</td>
									</tr>
									
									<tr id="Branch" >
										<th>Branch:</th>
										<td>
										<span class="unchecked">
										All <input type="checkbox" name=""  id="all" onclick="javascript:branch_togg();" checked="checked" />
										</span>
																			<?php 
									$sql="select * from ".BRANCH." where customerno=".$_SESSION['customerno']." ";
									$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
									while($row= $this->db->fetch_array($resultpages)){
									?>
									<?php echo $row['branchname']; ?>
									<input type="checkbox" name="branchlist[]" id="b_<?php echo $row['branchid']; ?>" class="brachlist"  checked="checked" value="<?php echo  $row['branchid']?>" onclick="calculate_toggel();"/>
									<?php }?>
									<span  id="span_brachlist"></span>
									</td>
									</tr>
									<tr>
									<th>Apply on   Upgrade:</th>
									<td><input type="checkbox" name="isupgrade"   value="1" checked="checked"  /></td>
									</tr>
									<tr>
									<td colspan="2"><input type="submit" name="submit" value="Submit" 
									onclick="javascript:return check_validation_for_add()">
									&nbsp;
									<input type="button" onclick="javascript: history.go(-1); return false" 
									name="Cancel" value="cancel" />
									</td>
									</tr>		
							</table>
						
						
						</form>
					<script>
					function post_tax(){
					//alert("called");
					var post_amt =jQuery('#dis_amt').val();
						
					 jQuery('#posttax').val(post_amt*1.1236);
					  jQuery('#post_val_span').html("Post tax discount amount applied will be = Rs."+post_amt*1.1236+" and will be rounder off to the nearest 5");
					
					
					}
					
					
					
					function branch_togg()
					{
						if(jQuery('.brachlist').is(':checked')==false)
						{
							jQuery('.brachlist').attr('checked','checked');
							jQuery('#all').attr('checked','checked');
						}else{
							jQuery('.brachlist').attr('checked','');
							jQuery('#all').attr('checked','');
						}
					}
					function calculate_toggel(){
					if(jQuery('.brachlist').is(':checked')!=false)
					{
					jQuery('#all').attr('checked','');
					}	
					
					}
					function check_amount_type(){
					var ret1=true;
					var ret2=true;
					
					
						if(jQuery("#amountype_r").is(':checked')==true)
						{
							if(jQuery("#dis_amt").val()=="" || jQuery("#dis_amt").val()==0){
										jQuery("#span_dis_amt").html("Please enter the amount");
										jQuery("#span_dis_amt").addClass("alert_error");
										ret1=false;
							}
						}
						if(jQuery("#amountype_p").is(':checked')==true)
						{
							if(jQuery("#dis_percent").val()=="" || jQuery("#dis_percent").val()==0 || jQuery("#dis_percent").val()>100){
										jQuery("#span_dis_per").html("Please enter the  percentage");
										jQuery("#span_dis_per").addClass("alert_error");
										ret1=false;
							}
						}
						
						if(ret2==true && ret1==true){
							return true;
						}else{
							return false;
						}
					
					}
					
					function check_validation_for_add(){
						var ret=false;
						
						if((CheckValidity()==true ) && (check_amount_type()==true ))
							{
								ret=true;
							}
							
						
							
						if(jQuery("#is_mass0").is(':checked')==true){
									if(jQuery("#clientid").val()==""){
										jQuery("#span_clientno").html("client not selected ");
										jQuery("#span_clientno").addClass("alert_error");
										ret=false;
									}else{
									if(ret==true){
										ret=true;
									}else{
										ret=false;
									}
										jQuery("#span_clientno").html("");
										jQuery("#span_clientno").removeClass("alert_error");
									}
						}else{
								if(jQuery('.brachlist:checked').size()>0){
								
									if(ret==true){
										ret=true;
									}else{
										ret=false;
									}
								jQuery("#span_brachlist").removeClass("alert_error");
								jQuery("#span_brachlist").html("");
									}else{
									jQuery("#span_brachlist").html("Branch not selected ");
									jQuery("#span_brachlist").addClass("alert_error");
								}
							
						}
						return ret;
					}
					</script>
					</div>
	
	
	</div>
	
	
	
	
	<?php
	
	break;
			case 'server' :	
			extract($_POST);
							// local variables
							
							$this->category=$category;
							$this->dis_code=$dis_code;
							$this->dis_amt=$post_tax_discount;
							
							$this->dis_percent=$dis_percent;
							
							$this->expiry=$expiry;
							$this->is_mass=$is_mass;
							$this->clientid=$clientid;
							$this->amountype=$amountype;
							$this->isupgrade=$isupgrade;
							$this->defination=$defination;
							
							if($is_mass=="1"){
							if(count($branchlist)>0){
								$this->branchid=implode(",",$branchlist);
							}
							}
										
							$sql="select * from ".TBL_DISCOUNT." where customerno=".$_SESSION['customerno']." and dis_code='".$this->dis_code."'";
							$result= $this->db->query($sql,__FILE__,__LINE__);
							if($this->db->num_rows($result)>0)
							{
								$_SESSION['error_msg'] = 'discount code  already exist. Please select another code';
								?>
								<script type="text/javascript">
												window.location = "discount.php"
								</script>
								<?php
								exit();
							}
							$return =true;
							 //server side validation
								
							
									if($return){
									
												$insert_sql_array = array();
												$insert_sql_array['dis_category'] = $this->category;
												$insert_sql_array['dis_code'] = $this->dis_code;
												$insert_sql_array['dis_amt'] = $this->dis_amt;
												$insert_sql_array['expiry'] = $this->expiry." 23:59:59";
												$insert_sql_array['is_mass'] = $this->is_mass;
												$insert_sql_array['clientid'] = $this->clientid;
												$insert_sql_array['branchid'] = $this->branchid;
												$insert_sql_array['dis_percent'] = $this->dis_percent;
												$insert_sql_array['defination'] = $this->defination;
												
												if($amountype==0)
												{
													$insert_sql_array['ispercent'] = "0";
												}else{
													$insert_sql_array['ispercent'] = "1";
												
												}
												if($isupgrade==0){
												$insert_sql_array['isupgrade']="0";
												}else{
												
												$insert_sql_array['isupgrade']="1";
												}
												
												$insert_sql_array['userid']=$_SESSION['user_id'];
												$insert_sql_array['customerno']=$_SESSION['customerno'];
												//echo "<pre>";
//												print_r($_REQUEST);
//												print_r($insert_sql_array);
//												echo "</pre>";
//												die();
												$this->db->insert(TBL_DISCOUNT,$insert_sql_array);
												$user_id = $this->db->last_insert_id();
											
												$_SESSION['msg'] = 'Discount has been Successfully created';
												?>
												<script type="text/javascript">
												window.location = "discount.php"
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
	function showAllDiscountfInfo()
		{
			$sql="select * from ".TBL_DISCOUNT." where  isdeleted=0 and customerno=".$_SESSION['customerno']." and isdeleted=0 ";
			$sql.=" order by dis_id desc ";
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
		<span>Discount List</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
					<tbody>
					<tr>
					<td>
					<input type="button" id="chart_bar" name="chart_bar" class="left_switch active " value="View All" style="width:100px"/>
					</td>
					<td><a href="discount.php?index=search">
					<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
					</a>
					<td>
					<a href="discount.php?index=add">
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
		<?php 
		if($_SESSION['groups'] == 'Superadmin')
		{
		?>
		<div align="right"> 
		
		<a href="#" onclick="table2CSV($('#search_table')); return false;"> 
		
		<img src="images/csv.png"  alt="Export to CSV"  title="Export to CSV" /> 
		
		</a> 
		
		</div>		
		<?php } ?>
						<form id="form_data" name="form_data" action="" method="post">
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Discount Name</th>
									
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
									<td style="text-transform:none;" title="<?php echo $row['dis_code'];?>"><?php echo $row['dis_code'];?></td>
									
								<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									
									<td><a href="discount.php?index=View&dis_id=<?php echo $row['dis_id'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
									| <a href="discount.php?index=edit&dis_id=<?php echo $row['dis_id'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<a href="javascript: void(0);" title="delete" class="help" 
									onclick="javascript: if(confirm('Do u want to delete this record?')) { discountinfo.deleteupdate('<?php echo $row['dis_id'];?>',{}) }; return false;" >
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
								<a href="discount.php">&laquo;&laquo;</a>
								<a href="discount.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="discount.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="discount.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="discount.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="discount.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="discount.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="discount.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="discount.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="discount.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="discount.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="discount.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="discount.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>
		
		<div align="right">Total Pages - <?php echo $lastpage;?></div>
		<div align="right">Total Records - <?php echo $numpages;?></div>
		
		</div>
		</div>
		
		<?php 
		
		}

	function show_json($q){
	
		if($q!=""){
		$push_arrr=array();	
			$sql="select * from ".TBL_CLIENT." where customerno=".$_SESSION['customerno']."";
				if($q!=""){
					$sql.=" and phoneno like '%".$q."%' order by clientid asc limit 0,5";
					}
				$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
				$jc_array=array();
					while($row= $this->db->fetch_array($resultpages)){
					$jc_array['clientid']=$row['clientid'];
					$jc_array['clientname']=$row['clientname'];
					$jc_array['phoneno']=$row['phoneno'];
					array_push($push_arrr,$jc_array);
					}
		}
	echo '{"result":'.json_encode($push_arrr).'}';
	}
	function show_json_byclient($cid){
	
		if($cid!=""){
		$push_arrr=array();	
			$sql="select * from ".TBL_CLIENT." where customerno=".$_SESSION['customerno']."";
				if($cid!=""){
					$sql.=" and clientid='".$cid."'";
					}
			
				$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
				$jc_array=array();
					while($row= $this->db->fetch_array($resultpages)){
					$jc_array['clientid']=$row['clientid'];
					$jc_array['clientname']=$row['clientname'];
					$jc_array['phoneno']=$row['phoneno'];
					array_push($push_arrr,$jc_array);
					}
		}
	echo '{"result":'.json_encode($push_arrr).'}';
	}

	function get_branchies($cid){
			if($cid!=""){
			$sql="select branchname from ".BRANCH." where customerno=".$_SESSION['customerno']."";
				if($cid!=""){
				$sql.=" and branchid in (".$cid.")";
				}
				$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
			
				while($row1= $this->db->fetch_array($resultpages)){
				$branchname.=$row1['branchname']."</br>";		
				}
			}
		echo $branchname;
	}
		function get_dis_type($cid){
			if($cid!=""){
			$sql="select * from ".TBL_DISCOUNT_TYPE." where customerno=".$_SESSION['customerno']."";
				if($cid!=""){
				$sql.=" and dtype_id =".$cid."";
				}
				$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
			
				while($row1= $this->db->fetch_array($resultpages)){
				$branchname.=$row1['dt_name'];		
				}
			}
		echo $branchname;
	}
		function get_clients($cid){
			if($cid!=""){
			$sql="select * from ".TBL_CLIENT." where customerno=".$_SESSION['customerno']."";
				if($cid!=""){
				$sql.=" and clientid=".$cid."";
				}
				$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
			
				while($row1= $this->db->fetch_array($resultpages)){
				$branchname.=$row1['clientname'];		
				}
			}
		echo $branchname;
	}



// view client 
function DiscountView($dis_id)
{
	$this->dis_id=$dis_id;
	$sql="select * from ".TBL_DISCOUNT." where dis_id='".$this->dis_id."' and customerno=".$_SESSION['customerno']."";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Discount Detail</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
		<table width="300px" cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
				<td><a href="discount.php">
				<input type="button" id="chart_bar2" name="chart_bar2" class="left_switch" value="View All" style="width:100px"/>
				</a></td>
				<td><a href="discount.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a></td>
				<td>
					<a href="discount.php?index=add">
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
		<table class="data" width="100%">
				<tr><td colspan="2">&nbsp;</td></tr>
				 <?php if($row['dis_category']!=0){
				?>
				<tr>
				<th>Category :  </th>
				<td>
				<?php echo $this->get_dis_type($row['dis_category']);?>
				 </td>
				</tr>
				 <?php }  ?>
				<tr>
				<th>Discount code:  </th>
				<td><?php echo $row['dis_code'];?> </td>
				</tr>
				<tr>
				<th>Discount Remarks</th>
				<td><?php echo $row['defination']; ?></td>
				</tr>
				<tr>
				<th>Discount amount:  </th>
				<td><?php if($row['ispercent']==0){echo $row['dis_amt'];}?> </td>
				</tr>
				<tr>
				<th>Discount percent:  </th>
				<td><?php if($row['ispercent']==1){ echo $row['dis_percent'];}?> </td>
				</tr>
				<tr>
				<th>Discount expiry:  </th>
				<td><?php echo $row['expiry'];?> </td>
				</tr>
				<tr>
				<th>applied on upgrades and downgrades:  </th>
				<td><?php if($row['isupgrade']==1){echo "yes";}else{echo "no";}?> </td>
				</tr>
				<tr>
				<th>Discount type:  </th>
				<td><?php if($row['is_mass']=="1"){ echo "General" ;}else{echo "Specific";}?> </td>
				</tr>
				
				<?php if($row['branchid']!="" && $row['is_mass']=="1" ){
				?>
				<tr>
				<th>Branch :  </th>
				<td>
				<?php echo $this->get_branchies($row['branchid']);?>
				 </td>
				</tr>
				 <?php }  ?>
				 <?php if($row['clientid']!=0 && $row['is_mass']=="0" ){
				?>
				<tr>
				<th>client :  </th>
				<td>
				<?php echo $this->get_clients($row['clientid']);?>
				 </td>
				</tr>
				 <?php }  ?>
				
				<tr>
				<th>Discount code  created on   </th>
				<td><?php echo $row['timestamp'];?> </td>
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
	$this->dis_id=$id;
	switch($runat){
	case 'local' :
			if(count($_POST)>0 and $_POST['submit']=='Submit'){
				extract($_POST);
				$this->dis_code = $dis_code;
			}
			$FormName = "frm_addstud";
			$ControlNames=array(
			
								"dis_code"=>array('dis_code',"ALPHANUMERIC","Please Enter Discount code","span_dis_code"),
								
								"expiry"=>array('expiry',"Date","Please Enter expiry date ","span_expiry"),
								"category"=>array('category',"","Please select category ","span_category")
			);
			$ValidationFunctionName="CheckValidity";
			$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
			echo $JsCodeForFormValidation;
			
			
			
			$sql="select * from ".TBL_DISCOUNT." where dis_id='".$this->dis_id."'";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result);
		?>
		<div class="onecolumn">
		<div class="header">
		<span>Edit Discount</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
				<td><a href="discount.php">
				<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
				</a>
				</td>
				<td><a href="discount.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a>
				<td>
					<a href="discount.php?index=add">
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
									<tr  >
										<th width="30%">Category:</th>
										<td>
										<select name="category">
										<option value="0">select</option>
										<?php 
										$sql="select * from ".TBL_DISCOUNT_TYPE." where customerno=".$_SESSION['customerno']." ";
										$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
										while($row1= $this->db->fetch_array($resultpages)){
										?>
										<option <?php if($row1['dtype_id']==$row['dis_category']){ echo "selected";} ?> value="<?php echo $row1['dtype_id']; ?>"><?php echo $row1['dt_name']; ?></option>
										<?php }?>
										
										


										</select>
										<span id="span_category"></span></td>
									</tr>
									<tr>
										<th >Discount code:</th>
										<td><input  type="text"  name="dis_code" id="dis_code" value="<?php echo $row['dis_code']; ?>" />
										<span id="span_dis_code"></span></td>
									</tr>
									<tr>
										<th >discount remarks:</th>
										<td><textarea id="defination" name="defination"><?php echo $row['defination']; ?></textarea>
									</tr>
									<tr>
									<th >Discount amount type :</th>
									<td>
									
									Rupees<input type="radio" value="0" id="amountype_r" value="0"  <?php if($row['ispercent']==0){echo 'checked="checked"';} ?> name="amountype" onclick="javascript:jQuery('#amount_id').show();jQuery('#percent_id').hide();jQuery('#span_dis_amt').html('');" />
									Percentage<input type="radio"  value="1" id="amountype_p"  name="amountype"  onclick="javascript:jQuery('#amount_id').hide();jQuery('#percent_id').show(); jQuery('#span_dis_per').html('');"  <?php if($row['ispercent']==1){echo 'checked="checked"';} ?> />
									</td>
									</tr>
									<tr id="amount_id"  <?php if($row['ispercent']==1){echo "style='display:none;'";} ?>>
										<th >Discount amount :</th>
										<td><input type="text"  name="dis_amt" id="dis_amt"   value="<?php echo $row['dis_amt']/1.1236 ?>" onkeyup="javascript:post_tax();"  />
										
										
										<span id="post_val_span"></span>
										<input type="hidden" name="post_tax_discount" id="posttax" />
										
										
										<span id="span_dis_amt"></span></td>
									</tr>
									<tr id="percent_id" <?php if($row['ispercent']==0){echo "style='display:none;'";} ?>>
										<th >Discount percent :</th>
										<td><input type="text"  name="dis_percent" id="dis_percent"   value="<?php echo $row['dis_percent'] ?>"/>
										<span id="span_dis_per"></span></td>
									</tr>
									<tr>
										<th >Expiry:</th>
										<td><input type="text"  name="expiry" id="expiry" class="datepicker1"  value="<?php echo date ("Y-m-d",strtotime($row['expiry'])); ?>"/>
										<span id="span_expiry"></span></td>
									</tr>
									<tr>
										<th >Discount type:</th>
										<td>Specific<input type="radio" value="0" <?php if($row['is_mass']=="0"){ echo "checked";} ?> name="is_mass" id="is_mass" onclick="javascript:jQuery('#cname_sayt').show();jQuery('#Branch').hide()" />
											General<input type="radio"  value="1" name="is_mass" id="is_mass" <?php if($row['is_mass']=="1"){ echo "checked";} ?> onclick="javascript:jQuery('#cname_sayt').hide();jQuery('#Branch').show();" />
										<span id="span_dis_type"></span></td>
									</tr>
									
								
									<tr id="cname_sayt" <?php if($row['is_mass']=="1"){?> style="display:none;" <?php } ?>>
										<th >Client phone no: </th>
										<td><input type="text"  name="clientno" id="clientno" onkeyup="sayt_client();"    />
										<div id="listview">
										<span id="clientname"><?php $this->get_clients($row['clientid']);?>	
										</span>
										<ul id="sayt_list" style="text-decoration:none; font:Arial, Helvetica, sans-serif; ">
										</ul>
										</div>
										<input type="hidden"  name="clientid" id="clientid" value="<?php $row['clientid'] ?>" />
										</td>
									</tr>
									
									<tr id="Branch"  <?php if($row['is_mass']=="0"){?> style="display:none;" <?php } ?>>
										<th>Branch:</th>
										<td>
										<span class="unchecked">
										All <input type="checkbox" name="" id="all"  onclick="javascript:branch_togg()" />
										</span>
										
									<?php 
									$sql="select * from ".BRANCH." where customerno=".$_SESSION['customerno']." ";
									$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
									while($row2= $this->db->fetch_array($resultpages)){
									?>
									<?php echo $row2['branchname']; ?>
									<?php $sbrid=explode(",",$row['branchid']);  ?>
									<input type="checkbox" name="branchlist[]"
									 <?php if(in_array($row2['branchid'],$sbrid)) {echo "checked='checked'";} ?> 
									 
									 class="brachlist" value="<?php echo $row2['branchid']; ?>" onclick="calculate_toggel()"/>
									<?php }?>
									</td>
									</tr>
									<tr>
									<th>Apply on   Upgrade:</th>
									<td><input type="checkbox" name="isupgrade" value="1"  <?php if($row['isupgrade']==1){ ?> checked="checked"  <?php } ?> /></td>
									</tr>
									<tr>
									<td colspan="2"><input type="submit" name="submit" value="Submit" 
									onclick="javascript:return check_validation_for_add()">
									&nbsp;
									<input type="button" onclick="javascript: history.go(-1); return false" 
									name="Cancel" value="cancel" />
									</td>
									</tr>		
							</table>
		</form>
		<script>
		
		function post_tax(){
			//alert("called");
			var post_amt =jQuery('#dis_amt').val();
			
			jQuery('#posttax').val(post_amt*1.1236);
			jQuery('#post_val_span').html("Post tax discount amount applied will be = Rs."+post_amt*1.1236+" and will be rounded to the nearest 5");
		
		
		}
		
		
		function calculate_toggel(){
					if(jQuery('.brachlist').is(':checked')!=false)
					{
					jQuery('#all').attr('checked','');
					}	
					
					}
					function check_amount_type(){
					var ret1=true;
					var ret2=true;
					
					
						if(jQuery("#amountype_r").is(':checked')==true)
						{
							if(jQuery("#dis_amt").val()=="" || jQuery("#dis_amt").val()==0){
										jQuery("#span_dis_amt").html("Please enter the amount" );
										jQuery("#span_dis_amt").addClass("alert_error");
										ret1=false;
							}
						}
						if(jQuery("#amountype_p").is(':checked')==true)
						{
							if(jQuery("#dis_percent").val()=="" || jQuery("#dis_percent").val()==0 || jQuery("#dis_percent").val()>100){
										jQuery("#span_dis_per").html("Please enter the percentage");
										jQuery("#span_dis_per").addClass("alert_error");
										ret1=false;
							}
						}
						
						if(ret2==true && ret1==true){
							return true;
						}else{
							return false;
						}
					
					}
					
					function check_validation_for_add(){
						var ret=false;
						
						if((CheckValidity()==true ) && (check_amount_type()==true ))
							{
								ret=true;
							}
							
						
							
						if(jQuery("#is_mass0").is(':checked')==true){
									if(jQuery("#clientid").val()==""){
										jQuery("#span_clientno").html("client not selected ");
										jQuery("#span_clientno").addClass("alert_error");
										ret=false;
									}else{
									if(ret==true){
										ret=true;
									}else{
										ret=false;
									}
										jQuery("#span_clientno").html("");
										jQuery("#span_clientno").removeClass("alert_error");
									}
						}else{
								if(jQuery('.brachlist:checked').size()>0){
								
									if(ret==true){
										ret=true;
									}else{
										ret=false;
									}
								jQuery("#span_brachlist").removeClass("alert_error");
								jQuery("#span_brachlist").html("");
									}else{
									jQuery("#span_brachlist").html("Branch not selected ");
									jQuery("#span_brachlist").addClass("alert_error");
								}
							
						}
						return ret;
					}
		
		function branch_togg()
					{
						if(jQuery('.brachlist').is(':checked')==false)
						{
							jQuery('.brachlist').attr('checked','checked');
							jQuery('#all').attr('checked','checked');
						}else{
							jQuery('.brachlist').attr('checked','');
							jQuery('#all').attr('checked','');
						}
					}
					function calculate_toggel(){
					if(jQuery('.brachlist').is(':checked')!=false)
					{
					jQuery('#all').attr('checked','');
					}	
					
					}
		
		</script>
		</div>
		</div>
		<br class="clear"/>
		<?php
		break;
		case 'server' :	
		extract($_POST);
		$this->category=$category;
		$this->dis_code=$dis_code;
		$this->dis_amt=$post_tax_discount;
		$this->expiry=$expiry;
		$this->is_mass=$is_mass;
		$this->clientid=$clientid;
		$this->dis_percent=$dis_percent;
		$this->amountype=$amountype;
		$this->isupgrade=$isupgrade;
		$this->defination=$defination;
		if(count($branchlist)>0){
		$this->branchid=implode(",",$branchlist);
		}
		// server side validation
				
			// unique check	
			$sql="select * from ".TBL_DISCOUNT." where customerno=".$_SESSION['customerno']." and  dis_code='".$this->dis_code."' and not dis_id =".$this->dis_id."";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($result)>0)
			{
				$_SESSION['error_msg'] = 'discount code  already exist. Please select another code';
				?>
				<script type="text/javascript">
								window.location = "discount.php"
				</script>
				<?php
				exit();
			}
		
		
		
		
		$return =true;
		if($return){
		$update_sql_array = array();
		$update_sql_array['dis_category'] = $this->category;
		$update_sql_array['dis_code'] = $this->dis_code;
		$update_sql_array['dis_amt'] = $this->dis_amt;
		$update_sql_array['expiry'] = $this->expiry." 23:59:59";;
		$update_sql_array['is_mass'] = $this->is_mass;
		$update_sql_array['clientid'] = $this->clientid;
		$update_sql_array['branchid'] = $this->branchid;
		$update_sql_array['userid']=$_SESSION['user_id'];
		$update_sql_array['customerno']=$_SESSION['customerno'];
		$update_sql_array['dis_percent']=$this->dis_percent;
		$update_sql_array['defination']=$this->defination;
		
		if($isupgrade==0){
		$update_sql_array['isupgrade']="0";
		}else{
		
		$update_sql_array['isupgrade']="1";
		}
		if($amountype==0){
		$update_sql_array['ispercent']="0";
		}else{
		
		$update_sql_array['ispercent']="1";
		}
		
		
		$this->db->update(TBL_DISCOUNT,$update_sql_array,'dis_id',$this->dis_id);
		$_SESSION['msg']='discount has been updated successfully';
		
		?>
		<script type="text/javascript">
		window.location = "discount.php"
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
$sql_1="delete from ".TBL_DISCOUNT." where dis_id='".$this->id."' customerno=".$_SESSION['customerno']."";
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
    <form action="discount.php?index=search" id="search_form" name="search_form" method="post">
        <table width="100%" class="data">
            <tr>
                <th>Name Of Discount :</th>
                <td>
                    <input type="text" value="<?php echo $_REQUEST['dis_code'];?>" id="dis_code" name="dis_code" />
                </td>
            </tr>
            <tr></tr>
            <tr>
                <th colspan="4">
                    <input type="submit" name="search" id="search" value="Search" />
                </th>
            </tr>
        </table>
    </form>
</div>
<br class="clear" />
<?php 
}



function SearchRecord($dis_code='')
{
$sql = "select * from ".TBL_DISCOUNT." where isdeleted=0 and customerno=".$_SESSION['customerno']." ";
if ($dis_code) {
    $sql.=" and dis_code = '".$dis_code."'";
}
$sql.= " order by dis_id desc ";
$resultpages = $this->db->query($sql,__FILE__, __LINE__);
if ($_REQUEST['pg']) {
    $st = ($_REQUEST['pg'] - 1) * 10;
    $sql.= " limit ".$st.",10 ";
    $x = (($_REQUEST['pg'] - 1) * 10) + 1;
    $pgr = $_REQUEST['pg'];
}
if ($_REQUEST['pg'] == '') {
    $sql.= " limit 0,10 ";
    $x = 1;
    $pgr = 1;
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
<td><a href="discount.php">
<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
</a>
</td>
<td>
<a href="discount.php?index=search">
<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search active" style="width:100px" />
</a>
</td>
<td>
					<a href="discount.php?index=add">
						<input type="button" id="chart_bar" name="chart_line" class="right_switch" value="Add New" style="width:100px" />
					</a>
					</td>

</tr>

</tbody>

</table>
<?php }?>
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
									<th width="10%">Discount Name</th>
									
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
									<td style="text-transform:none;" title="<?php echo $row['dis_code'];?>"><?php echo $row['dis_code'];?></td>
									
									<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									
									<td><a href="discount.php?index=View&dis_id=<?php echo $row['dis_id'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
									| <a href="discount.php?index=edit&dis_id=<?php echo $row['dis_id'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<a href="javascript: void(0);" title="delete" class="help" 
									onclick="javascript: if(confirm('Do u want to delete this record?')) { discountinfo.deleteupdate('<?php echo $row['dis_id'];?>',{}) }; return false;" >
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
								<a href="discount.php">&laquo;&laquo;</a>
								<a href="discount.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="discount.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="discount.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="discount.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="discount.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="discount.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="discount.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="discount.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="discount.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="discount.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="discount.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="discount.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>

<div align="right">Total Pages - <?php echo $lastpage;?></div>
<div align="right">Total Records - <?php echo $numpages;?></div>

</div>

</div>

<?php 

}


function validate_discount($code,$client,$expiry,$action,$sid)
{
$json_disc=array();
if($code!="")
{
	$sql="select * from ".TBL_DISCOUNT."  where isdeleted=0  and dis_code='".$code."' and customerno=".$_SESSION['customerno']."";
	$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
	$row1= $this->db->fetch_array($resultpages);
	if($row1['dis_id']!=""){
		if(strtotime($row1['expiry'])>=strtotime($expiry." 00:00:00")){
				if($row1['is_mass']!=0){
				//if 
						if($client!=0 &&  $client!="")
						{
									if(in_array($this->check_client_with_branch($client), explode(",",$row1['branchid'])) )
									{
															if($this->check_servicecall_with_code($client,$row1['dis_id'],$sid,$action)==0){
																$json_disc['error']=false;
																$json_disc['dis_amt']=$row1['dis_amt'];
																$json_disc['dis_id']=$row1['dis_id'];
																$json_disc['dis_percent']=$row1['dis_percent'];
																$json_disc['ispercent']=$row1['ispercent'];
																$json_disc['isupgrade']=$row1['isupgrade'];
																//echo "code is valid for client for once ";
															}else{
																$json_disc['error']=true;
																$json_disc['error_msg']="code already used ";
															}							
									}else{
									 $json_disc['error']=true;
									 $json_disc['error_msg']="code is not valid in the customer's branch" ;
									}
						
						
						}else{
									if($_SESSION['branchid']!="all" && in_array($_SESSION['branchid'], explode(",",$row1['branchid'])) )
									{
											$json_disc['error']=false;
											$json_disc['dis_amt']=$row1['dis_amt'];
											$json_disc['dis_id']=$row1['dis_id'];
											$json_disc['dis_percent']=$row1['dis_percent'];
											$json_disc['ispercent']=$row1['ispercent'];
											$json_disc['isupgrade']=$row1['isupgrade'];
											
									
									}else{
											$json_disc['error']=true;
											if($_SESSION['branchid']!="all"){
												$json_disc['error_msg']="code not valid in the customer's branch";
											}else{
												$json_disc['error_msg']="please select the branch";
											}
									}
											
						
						
						}
					
				
				//if 
				}else{
				//if   
					 if($this->check_servicecall_with_code($client,$row1['dis_id'],$sid,$action)==0 && ($this->check_discount_for_client($client)!=0 || $this->check_discount_for_client($client)!="")){
							$json_disc['error']=false;
							$json_disc['dis_amt']=$row1['dis_amt'];
							$json_disc['dis_id']=$row1['dis_id'];
					  }else{
							$json_disc['error']=true;
							$json_disc['error_msg']="code already used or not valid for customer ";
					  }
				//if 
				}
				
		}else{
		$json_disc['error']=true;
		$json_disc['error_msg']="code validity date  expired";	
		}
	
	}else{
	$json_disc['error']=true;
	$json_disc['error_msg']="code does not exist in our data base";
	}
}else{
$json_disc['error']=true;
$json_disc['error_msg']="code is empty";
}

echo json_encode($json_disc);
}
function check_servicecall_with_code($client,$codeid,$sid,$action)
{
$sql="select count(*) as callcount  from ".TBL_SERVICECALL." where clientid=".$client." and dis_id=".$codeid." and customerno=".$_SESSION['customerno']."";
if($action!="add" ){
$sql.=" and  not serviceid =".$sid."";
}
$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
$row1= $this->db->fetch_array($resultpages);
return $row1['callcount'];
}
function check_client_with_branch($client)
{
	if($client!=0 || $client!="" || $client!="undefined"){
		$sql="select branchid  from ".TBL_CLIENT." where clientid=".$client." and  customerno=".$_SESSION['customerno']."";
		$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
		$row1= $this->db->fetch_array($resultpages);
		return $row1['branchid'];
	}
}
function check_discount_for_client($client){
if($client!=0 || $client!="" || $client!="undefined"){
$sql="select * from ".TBL_DISCOUNT."  where isdeleted=0  and clientid=".$client."  and customerno=".$_SESSION['customerno']."";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
$row1= $this->db->fetch_array($resultpages);
return $row1['dis_id'];
}
}
function check_if_client_exist(){}
}
?>