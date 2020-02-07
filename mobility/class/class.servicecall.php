<?php
require_once("class/class.checklist.php");
class servicecall{

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
		var $trackeeid;
		var $serviceid_ava;
		var $warning1time;



	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		$this->checklist_obj = new checklist();
		$this->dataobj=new date();
		$_SESSION['page']="service";
	}
	
	
	function selectstatus(){
		$sql="select * from ".TBL_FLOW." where 1 ";
		$resultpages= $this->db->query($sql,__FILE__,__LINE__); 
		?>
			<select name="status"  >
				<option value="0">select services</option>
				<?php while($row= $this->db->fetch_array($resultpages)){ ?>
				<option value="<?php echo $row['serviceflowid']?>" ><?php echo $row['name']; ?></option>  
				<?php } ?>
			</select>   
		<?php 
	}
	
	
	function selectservices(){
		$sql="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."' ";
		$sql.=" order by servicelistid desc ";
		$resultpages= $this->db->query($sql,__FILE__,__LINE__); 
		?>
			<select name="select" id="enterprisebox" >
				<option value="0">select services</option>
				<?php while($row= $this->db->fetch_array($resultpages)){ ?>
				<option value="<?php echo $row['servicelistid']?>" ><?php echo $row['servicename']; ?></option>  
				<?php  } ?>
			</select>    
		<?php 
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
	
	
		
	function addservices()
	{
		$sql="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."' and isdeleted=0  ";
		$sql.=" order by servicename asc ";
		$resultpages= $this->db->query($sql,__FILE__,__LINE__); ?>
		<select name="select" id="enterprisebox" >
			<option value="">select services</option>
			<?php while($row= $this->db->fetch_array($resultpages)){ ?>
			<option value="<?php echo $row['servicelistid']?>" ><?php echo $row['servicename']; ?></option>  
			<?php }	?>
		</select>   
		<input  type="button" value="Add" onclick="selectcalled();" />
		<?php 
	}

	function addservicesedit()
	{
		$sql="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."'  and isdeleted=0 ";
		$sql.=" order by servicelistid desc ";
		$resultpages= $this->db->query($sql,__FILE__,__LINE__); ?>
			<select name="select" id="enterprisebox" >
				<option value="0">select services</option>
				<?php while($row= $this->db->fetch_array($resultpages)){?>
				<option value="<?php echo $row['servicelistid']?>" ><?php echo $row['servicename']; ?></option>  
				<?php } ?>
			</select>    
		<input  type="button" value="Add" onclick="selectcallededit();" />		
		<?php 
	}
	function get_cleint_type()
	{
	
									$sql0="select * from ".TBL_CLIENT_TYPE." where customerno='".$_SESSION['customerno']."' ";
									$sql0.=" order by type_id desc ";
									$resultpages0= $this->db->query($sql0,__FILE__,__LINE__); 
									?>
									<option value="">select type</option>
									
									<?php 
									while($row0= $this->db->fetch_array($resultpages0)){
									?>
									<option  value="<?php echo $row0['type_id']?>" ><?php echo $row0['type_name']; ?></option>  
									<?php  
									} 
									
	
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
		$ControlNames=array("clientname"=>array('clientname',"''","Please Enter Client Name","span_clientname"),
												"phonenumber"=>array('phonenumber',"'numeric'","Please Enter phone","span_phone"),
												"to"=>array('to',"''","Please select a trackee","span_trackee"),
												"address1"=>array('address1',"''","Please enter address","span_address1"),
												"address2"=>array('address2',"''","Please enter address","span_address1"),
												"stime"=>array('stime',"''","Please enter the date","span_stime"),
												"enterprisebox"=>array('enterprisebox',"''","Please select a service","span_service"),
												"txtcity"=>array('txtcity',"''","Please enter a city","span_txtcity")
												
												
							
							);
		
		if($_SESSION['customerno']==14){
		$ControlNames[]=array('trackno',"''","Please Enter tracking","span_tracking");
		}
		if($_SESSION['customerno']!=14){
		$ControlNames[]=array('type_id_show',"''","Please select a type","span_type_id");
		
		}
		if($_SESSION['use_forms']==1){
		$ControlNames[]=array('form_type_id',"''","Please Select form type","span_form_type");
		}
		
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
							<a href="servicecalls.php">
							<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
							</a>
						</td>
						<td>
							<a href="servicecalls.php?index=search">
							<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
							</a>
						</td>
						<td>
						<a href="servicecalls.php?index=add">
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
					<form method="post"  id="formclient"  name="<?php echo $FormName?>" >
								<!--hidden fields-->
								<input name="clientid" type="hidden" id="clientid" value="0" />   
								<input name="ischargable" type="hidden" id="ischargable" value="1" /> 
								<input type="hidden" id="type_id_show" name="type_id_show"  />
								<input type="hidden" id="form_type" name="form_type" value="<?php echo $_SESSION['use_forms']; ?>" />
								<!--hidden fields-->
					
							<table class="data" width="100%">
							<tr>
								<th>search </th>
								<td>
								<input name="phonename" type="text" id="phonename" size="30" maxlength="12" onkeyup="dis_check();" placeholder='Phone No or name' />
								<div class="SAYT"><div id="clientbox"></div></div>
								</td>
							</tr>
							<tr>
								<th>phone no&nbsp;&nbsp;<d class='red'>*</d>&nbsp;:</th>
								<td>
								<input name="phonenumber" type="text" id="phonenumber" size="30" maxlength="12"  placeholder='Phone No' />
								Sms alert  Required<input id="smsr" name="smsr" type="checkbox" checked>
								<span id="span_phone" class="span_phone" ></span></td>
							</tr>
							<tr>
								<th>client name&nbsp;&nbsp;<d class='red'>*</d>&nbsp;:</th>
								<td><input name="clientname" type="text" id="clientname" size="30" maxlength="30" onkeyup="showcreate();" placeholder='Client Name' />
								<span id="manclientname" class="mandatory" style="display:none;">Please enter a client name.</span> 
								<span id="span_clientname" class="span_clientname" ></span>     
								</td>
							</tr>
							<?php if($_SESSION['customerno']!=14){  ?>
							<tr id="client_type_add"   >
								<th>client type &nbsp;&nbsp;<d class='red'>*</d>&nbsp;</th>
								<td>
									<select name="type_id" id="type_id_test" >
									<?php $this->get_cleint_type(); ?>
									</select>
									<span  id="span_type_id"  ></span>
									</td>
							</tr>
							<tr id="client_type_show" style="display:none" <?php if($_SESSION['customerno']==14){ echo 'style="display:none;"';}  ?>>
								<th>client type &nbsp;&nbsp;<d class='red'>*</d>&nbsp;</th>
								<td><span id="type_id_show_span"></span></td>
							</tr>
							<?php } ?>
							<?php if($_SESSION['use_forms']==1){ ?>
							<tr>
								<th>form type &nbsp;&nbsp;<d class='red'>*</d>&nbsp;</th>
								<td><select id="form_type_id" name="form_type_id" ><?php $this->selectforms(0);?> </select> <span  id="span_form_type"  ></span></td>
							</tr>
							<?php } ?>
							
							<tr>
								<th>Address&nbsp;&nbsp;<d class='red'>*</d>&nbsp;:</th>
								<td><input name="address1" type="text" id="address1" size="50" maxlength="50" onkeyup="showmodify();" placeholder="Address line 1"/><br/>
								<input name="address2" type="text" id="address2" size="50" maxlength="50"  onkeyup="showmodify();" placeholder="Address line 2"/>
								<span id="span_address1" class="span_address1" ></span></td>
							</tr>
							<tr>
								<th>City:</th>
								<td><input name="txtcity" type="text" id="txtcity" size="20" maxlength="20" placeholder="City" />
								<span id="span_txtcity" class="span_txtcity" ></span></td>
							</tr>
							<tr>
								<th>State:</th>
								<td><input name="txtstate" type="text" id="txtstate" size="30" maxlength="30" placeholder="State" /></td>
							</tr>
							<tr>
								<th>Zip:</th>
								<td><input name="txtzip" type="text" id="txtzip" size="10" maxlength="10" placeholder="Zip" /></td>
							</tr>                      
							<tr>
								<th>Contact Person:</th>
								<td><input name="contactperson" type="text" id="contactperson" size="20" maxlength="20" onkeyup="showmodify();" placeholder="Contact Person" /></td>
							</tr>
							<tr>
								<th>Email:  </th>
								<td> <input  type="text" id="email" name="email" placeholder="Email"   />
								<span id="span_email" class="span_email" ></span></td>
							</tr>                                                        
							<?php if(isset($_SESSION['ClientExtra'])){?>
							<tr>
								<th><?php echo $_SESSION['ClientExtra']; ?>: </th>
								<td><input value="" name="extra" id="extra" type="text">					</td>
							</tr><?php } ?>
							<tr>
								<th>Services&nbsp;&nbsp;<d class='red'>*</d>&nbsp;:</th>
								<td><?php $this->addservices() ;?>
									<span id="span_service"></span>
									<div id="domt"></div>
									<table  class="data"   width="100%">
									<thead>
									<tr>
									<th width="24%">Service Name</th>
									<th width="37%">Expected Time</th>
									<th width="30%">Price</th>
									<th width="9%"></th>
									</tr>
									</thead>
									<tbody id="servilistul"></tbody>
									</table>
								</td>
							</tr>
					<tr>
					<th></th>
					<td>
					<table width="100%" class="data">
					<tfoot>
					<tr >
					<td width="24%"></td>
					<td width="37%"  class="bold" >Service Amount </td>
					<td width="30%" id="service_amount" class="bold" >0</td>
					<td width="9%" ><input type="hidden" value="0" id="totalamount" name="totalamount" /></td>
					</tr>
					<tr>
					<td></td>
					<td class="bold" >Visiting charges</td>
					<td id="visiting_charges_html" class="bold">
					<input type="text" value="<?php echo "".VISITING_CHARGES.""; ?>" name="visiting_charges" id="visiting_charges" onkeyup="on_changes_visiting_charges();"  />
					
					</td>
					<td></td>
					</tr>
					<tr>
					<td></td>
					<td  class=" bold" >Total Amount </td>
					<td id="net_amount_html" class=" bold"> </td>
					<td><input type="hidden"  id="net_amount" name="net_amount" /></td>
					</tr>
					
					
					</tfoot>
					
					
					
					</table>
					
					
					
					</td>
					
					</tr>
					<tr>
					<th>Discount</th>
					<td>
					<table width="100%" class="data">
					
					
					<tr>
					
					
					<td  width="24%"  style="border-bottom:none;">
					<input name="dcode" type="text" id="dcode" onkeyup="javascript:dis_check();" placeholder="Discount code">
					<input type="hidden" id="d_act" value="add" /><br />
					<span id="span_code" class="span_code"></span>  
					<span id="dis_status"></span>                                                  
					</td>
					<td  width="37%" class=" bold" style="border-bottom:none;">discount amount</td>
					<td width="30%"  style="border-bottom:none;">
					<input name="damt" type="text" id="damt" readonly="true" placeholder="Discount amount">
					<input name="dis_id" type="hidden" id="dis_id" value="0">
					
					<span id="span_amt" class="span_amt"></span>                                                    
					</td>
					<td width="9%"  style="border-bottom:none;"></td>
					</tr>
					
					
					</table>
					
					</td>
					
					</tr>
					<tr>
					<th class="green_font bold"> </th>
					<td>
					<table width="100%" class="data">
					<tr>
					<td  width="24%" style="border-bottom:none;" > </td>
					<td  width="37%"  class="green_font bold" style="border-bottom:none;">net amount</td>
					<td width="30%" class="green_font bold"  style="border-bottom:none;">Rs. <span  id="net_amount_after_discount">0</span></td>
					<td width="9%"  style="border-bottom:none;"></td>
					</tr>
					
					
					</table>
					
					</td>
					
					</tr>
					<?php  if(isset($_SESSION['SerUserField1'])){?>
					<tr>
					<th><?php echo $_SESSION['SerUserField1']; ?>:</th>
					<td><div class="SAYT"><div id="userfieldbox1">
					<input id="seruf1" type="text" maxlength="30" size="30" name="seruf1" issaytactive="1" title="" listtype="seruf1" autocomplete="off" />
					<img id="seruf1_icon" class="SAYTicon" title="This is a SAYT box, Search As You Type" src="../images/SAYTIcon.png" /></div>
					</div>
					</td>
					</tr>                
					<?php } ?>                 
					<!--  User Field 2  -->
					<?php  if(isset($_SESSION['SerUserField2'])){?>
					<tr>
					<th><?php echo $_SESSION['SerUserField2']; ?>:</th>
					<td>
					<div class="SAYT"><div id="userfieldbox2">
					<input id="seruf2" type="text" maxlength="30" size="30" name="seruf2" issaytactive="1" title="" listtype="seruf2" autocomplete="off" />
					
					<img id="seruf2_icon" class="SAYTicon" title="This is a SAYT box, Search As You Type" src="../images/SAYTIcon.png" />
					</div>
					</div>
					</td>
					</tr>                
					<?php } ?>                                    
					<tr>
					<th>tracking no : </th>
					<td colspan="5"><input name="trackno" type="text" id="trackno">
					
					<span id="span_tracking" class="span_tracking"></span>                                                    
					</td>
					</tr>
					<?php  if(isset($_SESSION['CallExtra1'])){ ?>
					<tr>
					<th><?php echo $_SESSION['CallExtra1']; ?>:</th>
					<td colspan="5"><input name="callextra1" type="text" id="callextra1">
					
					<span id="span_tracking" class="span_tracking"></span>                                                    
					</td>
					</tr>
					<?php }
					if(isset($_SESSION['CallExtra2'])){ 
					?>
					<tr>
					<th><?php echo $_SESSION['CallExtra2']; ?>:</th>
					<td colspan="5"><input name="callextra2" type="text" id="callextra2">
					
					<span id="span_tracking" class="span_tracking"></span>                                                    
					</td>
					</tr>
					<?php } ?>
					<tr>
					<th>Start Timings :</th>
					<td><input type="text" class="datepicker_dis" name="stime" id="stime" style="width:300px"  value="<?php echo date("Y-m-d");?>"> 
					Time hours
					<select name="shours">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					</select>
					minutes 
					<select name="smin">
					<option value="00">00</option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
					<option value="50">50</option>
					</select>
					<select name="sampm">
					<option value="am">am</option>
					<option value="pm">pm</option>
					</select>
					<span id="span_stime" class="span_stime"></span>    
					</td>
					</tr>
					
					
					<tr>
					<th>Trackee <d   style=" width:10px;color:#FF0000; font-size:14px; padding:5px;">*</d>  :</th>
					<td><?php $this->selectcheckbox(); ?>
					<span id="span_trackee" class="span_trackee"></span>    
					</td>
					</tr>
					
					
					<tr>
					<td></td>
					<td>
					
					<div id="hidecreate">                              
					<input type="submit" name="submit" id="Create" value="Submit" onclick="return postdata();">                      
					</div>                          
					<div style="display:none;" id="hidemodify">                                                            
					<input type="submit" name="submit" id="Modify" value="Submit" onclick="return postdata();">                                                                             
					<input name="modifyclient" type="hidden" id="modifyclient" value="0" />
					
					</div>
					</td>
					</tr>
					</table>
						
					
        	</form>
			 
			
			
			<script type="text/javascript">
			function postdata(){
			
					if(CheckAddNewsValidity()==true)
					{
					
					
						var r=confirm("Do you want to modify the client ?");
						if (r==true)
						{
							jQuery("#modifyclient").val(2);
							return true;
						}
						else
						{
							jQuery("#modifyclient").val(3);
							jQuery("#formclient").submit();
							
							return true;
						}
						
					}else{
					
					return false;
					}
			
			}
			
			
			
			
			</script>
			
			
			
					
					</div>
	
	
	</div>
	
	
	
	
	<?php
	
	break;
			case 'server' :	
			extract($_POST);
					if($this->check_clientno($phonenumber)==true){
					$modifyclient=2;
					}
						
		    
                    $this->clientname=$clientname;
                    $this->clientid=$clientid;
                    $this->address1=$address1;
                    $this->address2=$address2;
                    $this->txtcity=$txtcity;
                    $this->txtstate=$txtstate;
                    $this->txtzip=$txtzip;
                    $this->contactperson=$contactperson;
					
					
                    $this->to=$to;
                    $this->email=$email;
                    $this->emailr=$emailr;
                    $this->totalamount=$totalamount;
                    $this->phonenumber=$phonenumber;
                    $this->smsr=$smsr;
                    
                    $this->servicelist=$servicelist;
                    
                    $this->seruf1=$seruf1;
                    $this->seruf2=$seruf2;
                    $this->trackno=$trackno;
                    $this->phonenumber=$phonenumber;
                    
                    $this->stime=$stime;
                    $this->shours=$shours;
                    $this->sampm=$sampm;
                    
                   
                    
                    $this->etime=$etime;
                    $this->ehours=$ehours;
                    $this->eampm=$eampm;
                    
                    $this->chksig=$chksig;
                    $this->modifyclient=$modifyclient;
                  
                   $this->visiting_charges=$visiting_charges;
                   
                   
                  
                    
                    if($sampm=="am")
					{
						$slottime=  $stime." ";
						if($shours==12){$shours="00";}
                     	$slottime.= ($shours).":".$smin.":00";   
					}else if($sampm=="pm"){

						if($shours==12)
						{
						if($shours==12){$shours="00";}	
						$arr=explode("-",$stime);
						$str1=$arr[2]; 
						$slottime.=$arr[0]."-".$arr[1]."-".$arr[2]."";
						}else{
						$slottime=  $stime." ";
						
						}
						
                   		 $slottime.= " ".($shours+12).":".$smin.":00";
                    }
                    
                    
							$eslottime=$etime;
								if( $eampm=="am"){
								$eslottime.= ($ehours).":00:00";   
								}  else {
								$eslottime.= ($ehours +12).":00:00";
								}
							
							
							
						
                 
					
					if(count($servicelist)==0)
					{
						if($this->Form->ValidField($servicelist,'empty','Error :Services  is not selected ')==false){
						$return =false;
						}
					}
					
					$return =true;
					if($this->Form->ValidField($clientname,'empty','Record name is empty')==false)
					$return =false; 
					
							if($to>0){
							$this->updatetrackeebyid("service",$to);
							}
							

                if($return){
                    
									if($clientid!=0)
									{
														
														// section updated the client record;
														if($modifyclient==2)
														{
															$update_client = array();
															$update_client['clientname'] = $this->clientname;
															$update_client['add1'] = $this->address1;
															$update_client['add2'] = $this->address2;
															$update_client['phoneno'] = $this->phonenumber;
															$update_client['city'] = $this->txtcity;
															$update_client['email'] = $email;
															$update_client['state'] = $this->txtstate;
															$update_client['zip'] = $txtzip;
															$update_client['maincontact'] = $contactperson;
															$update_client['extra'] = $extra;
															$update_client['type_id'] = $type_id_show;
															$update_client['form_type_id'] = $form_type_id;
															
															$this->db->update(TBL_CLIENT,$update_client,'clientid', $clientid);
														}
														
														// indert sercall data;
																												
														$service_call_data = array();
														$service_call_data['clientname'] = $this->clientname;
														$service_call_data['email'] = $email;
														$service_call_data['add1'] = $this->address1;
														$service_call_data['add2'] = $this->address2;
														$service_call_data['phoneno'] = $this->phonenumber;
														$service_call_data['contactperson'] = $contactperson;
														$service_call_data['uf1'] = $this->seruf1;
														$service_call_data['email'] = $email;
														$service_call_data['uf2'] = $this->seruf2;
														$service_call_data['clientid'] = $this->clientid;
														$service_call_data['trackeeid'] = $to;
														$service_call_data['timeslot_start']=$this->dataobj->add_hours($slottime,0);
														$service_call_data['timeslot_end']=$this->dataobj->add_hours($eslottime,0);
														$service_call_data['totalbill']=$totalamount;
																												
														// discount ;
														
														if($dis_id>0){
																$service_call_data['dis_id'] = $dis_id;
																$service_call_data['is_web'] = "1";
																$service_call_data['discount_code']=$dcode;
																
															if($damt!=""){
																$service_call_data['discount_amount']=$damt;
															}else{
																$service_call_data['discount_amount']="0";
															}
														}
														
														
														// email and sms notification
														$service_call_data['isemail'] = '1';
														if($smsr!=""){
															$service_call_data['issms'] = '1';
														}
														
														$service_call_data['sigreqd'] = '1';   
														$service_call_data['trackingno'] = $trackno;
														$service_call_data['timesdelay'] = '0';
														$service_call_data['callextra1'] = $callextra1;
														$service_call_data['visiting_charges'] = $visiting_charges;
														if($_SESSION['customerno']==14){$ischargable=1;}
														$service_call_data['ischargable'] = $ischargable;
														$service_call_data['createdon'] = date('Y-m-d H:i:s');
														
														// constants;
														$service_call_data['customerno'] = $_SESSION['customerno'];
														$service_call_data['userid'] = $_SESSION['user_id'];
														$service_call_data['branchid'] = $_SESSION['branchid'];
														
														$this->db->insert(TBL_SERVICECALL,$service_call_data);
														
														// last inserted service id;
														$last_service_id=$this->db->last_insert_id();
														
														// inserting services to services in service manages;
														for($i=0;$i<count($servicelist);$i++)
														{
															if($to==-1){$to=="0";}
															$insert_services['trackeeid']=$to;
															$insert_services['servicecallid']=$last_service_id;
															$insert_services['servicelistid']=$servicelist[$i];
															$insert_services['customerno']=$_SESSION['customerno'];
															$insert_services['userid']=$_SESSION['user_id'];
															$insert_services['iscreatedby']="0";
															$this->db->insert(TBL_SERVICEMAN,$insert_services);
														
														}
						   		
									}else{
									
									
															$insert_client = array();
															$insert_client['clientname'] = $this->clientname;
															$insert_client['add1'] = $this->address1;
															$insert_client['add2'] = $this->address2;
															$insert_client['phoneno'] = $this->phonenumber;
															$insert_client['city'] = $this->txtcity;
															$insert_client['state'] = $this->txtstate;
															$insert_client['zip'] = $txtzip;
															$insert_client['maincontact'] = $contactperson;
															$insert_client['email'] = $email;
															$insert_client['type_id'] = $type_id_show;
															$insert_client['form_type_id'] = $form_type_id;
															
															$insert_client['iscall']='1';
															$insert_client['extra'] = $extra;
															$insert_client['branchid'] = $_SESSION['branchid'];
															$insert_client['customerno'] = $_SESSION['customerno'];
															$insert_client['userid']=$_SESSION['user_id'];
															$this->db->insert(TBL_CLIENT,$insert_client); 
															// getting the last client id 
															$this->clientid=$clientid=$this->db->last_insert_id();
															
														
														
														
															$service_call_data = array();
															$service_call_data['clientname'] = $this->clientname;
															$service_call_data['add1'] = $this->address1;
															$service_call_data['add2'] = $this->address2;
															$service_call_data['phoneno'] = $this->phonenumber;
															$service_call_data['contactperson'] = $contactperson;
															$service_call_data['email'] = $email;
															$service_call_data['uf1'] = $this->seruf1;
															$service_call_data['uf2'] = $this->seruf2;
															$service_call_data['clientid'] = $clientid;
															$service_call_data['trackeeid'] = $to;
															$service_call_data['timeslot_start']=$this->dataobj->add_hours($slottime,0);
															$service_call_data['timeslot_end']=$this->dataobj->add_hours($eslottime,0);
															$service_call_data['totalbill']=$totalamount;
															$service_call_data['branchid'] = $_SESSION['branchid'];
															// discounts
															if($dis_id>0){
															$service_call_data['dis_id'] = $dis_id;
															$service_call_data['is_web'] = "1";
															$service_call_data['discount_code']=$dcode;
															if($damt!=""){
															$service_call_data['discount_amount']=$damt;
															}else{
															$service_call_data['discount_amount']="0";
															
															}
															}
															
															$service_call_data['sigreqd'] = '1';   
															
															$service_call_data['trackingno'] = $trackno;
															$service_call_data['createdon'] = date('Y-m-d H:i:s');
															$service_call_data['customerno'] = $_SESSION['customerno'];
															
															$service_call_data['isemail'] = '1';
															
															if($smsr!=""){
															$service_call_data['issms'] = '1';
															}
															
															$service_call_data['userid'] = $_SESSION['user_id'];
															$service_call_data['timesdelay'] = '0';
															$service_call_data['visiting_charges'] = $visiting_charges;
															if($_SESSION['customerno']==14){$ischargable=1;}
															$service_call_data['ischargable'] = $ischargable;
															$this->db->insert(TBL_SERVICECALL,$service_call_data);
															// last inserted service id;
															$last_service_id=$this->db->last_insert_id();
														
															for($i=0;$i<count($servicelist);$i++)
															{
																$insert_services['servicecallid']=$last_service_id;
																$insert_services['servicelistid']=$servicelist[$i];
																$insert_services['iscreatedby']="0";
																
																if($to==-1){$to==0;}
																$insert_services['trackeeid']=$to;
																$insert_services['userid']=$_SESSION['user_id'];
																$insert_services['customerno']=$_SESSION['customerno'];
																$this->db->insert(TBL_SERVICEMAN,$insert_services);
															}
															
									}
                   			
							 $this->update_endtime_by_sid($last_service_id,$slottime);
							
						  	$_SESSION['msg'] = 'Service has been Successfully created';
							?>
							<script type="text/javascript">
							window.location = "servicecalls.php"
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















































	function get_clientname($clientid)
	{
		$sql="select * from ".TBL_CLIENT." where customerno='".$_SESSION['customerno']."' and clientid='".$clientid."' ";
		$sql.=" order by clientid desc ";
		$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
		$row= $this->db->fetch_array($resultpages) ;
		return $row['clientname'];
	}
	
	function check_clientno($phoneno)
	{
	
	$sql="select * from ".TBL_CLIENT." where customerno='".$_SESSION['customerno']."' and phoneno='".$phoneno."' ";
	$sql.=" order by clientid desc ";
	$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
	$row= $this->db->fetch_array($resultpages) ;
	 $ret=true;
		if($row['clientname']==""){
		 $ret=false;
		}
	return $ret;
	}


// show all clients
	function showAllServicefInfo()
		{
		  $x=1;
		  if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
		  $branch_clause=" and branchid=".$_SESSION['branchid'];
		  }
			$sql="select * from ".TBL_SERVICECALL." where   customerno=".$_SESSION['customerno']."".$branch_clause;
			$sql.=" order by serviceid desc ";
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
		<span>Service calls List</span>
		<div class="switch" style="width:300px">
		
				<table width="300px" cellpadding="0" cellspacing="0">
					<tbody>
					<tr>
					<td>
					<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:100px"/>
					</td>
					<td><a href="servicecalls.php?index=search">
					<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
					</a>
					</td>
					<td><a href="servicecalls.php?index=add">
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
          <?php 
		if($_SESSION['groups'] == 'Superadmin')
		{
		?>
          <?php } ?>
  <form id="form_data" name="form_data" action="" method="post">
    <table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
      
      <thead>
        
        <tr>
          <th width="5%">SNO.</th>
									  <th width="10%">Client Name</th>
									  <th>branch:  </th>
									  <th>Trackee:  </th>
									  
									
									  <th>timeslot start</th>
									  <th>timeslot end</th>
									  
									  <th>status</th>
									  
									<th width="15%">action</th>
		  </tr>
        
        </thead>
      <tbody>
        <?php 	
                                                                      
									while($row= $this->db->fetch_array($result))
									{
									?>
        <tr>
          
          <td><?php echo $x;?></td>
									  <td title="<?php echo $this->get_clientname($row['clientid']);?>"><?php echo $this->get_clientname($row['clientid']); ?></td>
									  
									<td><?php echo $this->branch_by_id($row['branchid']); ?></td>
									  <td><?php echo $this->get_trackee($row['trackeeid']);?> </td>
									  <td><?php echo date("d-M-y h:i A",strtotime($row['timeslot_start']));?> </td>
									  <td><?php echo date("d-M-y h:i A",strtotime($row['timeslot_end']));?> </td>
										
									  <?php $this->displayactions($row['status'],$row['serviceid'],$row['trackeeid'],$row['isdeleted']);?>
          
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
								    <a href="servicecalls.php">&laquo;&laquo;</a>
								    <a href="servicecalls.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								    <?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								    <a href="servicecalls.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								    <?php } ?>
								    <?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								    <a href="servicecalls.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								    <?php } } ?>
								    
								    <?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								    <a href="servicecalls.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								    <?php } ?>
								    
								    <?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								    <a href="servicecalls.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								    <?php } ?>
								    
								    <a href="servicecalls.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								    
								    <?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								    <a href="servicecalls.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								    <?php } ?>
								    <?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								    <a href="servicecalls.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								    <?php } ?>
								    
								    <?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								    <a href="servicecalls.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								    <?php } } ?>
								    <?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								    <a href="servicecalls.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								    <?php } ?>
								    
								    <a href="servicecalls.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								    <a href="servicecalls.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>	      </div>
		  
		<div align="right">Total Pages - <?php echo $lastpage;?></div>
		  <div align="right">Total Records - <?php echo $numpages;?></div>
		  
		</div></div>
		
		<?php 
		
		}

// view client 
                
                
	function select_tracees_mapped()
	{
		$sql="select trackeeid from ".TBL_DEVICE." where customerno='".$_SESSION['customerno']."'";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$str=array();
		$x=0;
			while($row= $this->db->fetch_array($result))
			{
				$str[$x++]=$row['trackeeid'];
			}
		return  implode(",",$str);
	}

	function selectcheckbox()
	{
			if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
				$branch_clause=" and branchid=".$_SESSION['branchid'];
			}
		$cat= $this->select_tracees_mapped();
		$sql="select * from ".TBL_TRACKEE." where trackeeid in (".$cat.") and customerno='".$_SESSION['customerno']."'".$branch_clause;
		$result= $this->db->query($sql,__FILE__,__LINE__);
		?>
		<select id="to" name="to">
				<option value="">Select Trackee</option>
				<?php 
				$x=1;
				while($row= $this->db->fetch_array($result)){
				?><option value=" <?php echo @$row['trackeeid']; ?>"><?php echo @$row['tname'];?></option>
				<?php } ?>
		</select>
		<?php 
	}
function selectcheckboxbyid($trackeeid)
{
if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
$branch_clause=" and branchid=".$_SESSION['branchid'];
}
$cat= $this->select_tracees_mapped();
$sql="select * from ".TBL_TRACKEE." where trackeeid in (".$cat.") and customerno='".$_SESSION['customerno']."'".$branch_clause;
$result= $this->db->query($sql,__FILE__,__LINE__);
?>
<select id="to" name="to">
<option value="-1">Select Trackee</option>
<?php 
$x=1;
while($row= $this->db->fetch_array($result)){
?><option  <?php if($row['trackeeid']==$trackeeid){echo "selected";} ?> value="<?php echo @$row['trackeeid']; ?>"><?php echo @$row['tname'];?></option>
<?php } ?>
</select>

<?php 
}
function ServiceView($serviceid)
{
$this->serviceid=$serviceid;

$sql="select * ,".TBL_CLIENT.".city ,".TBL_CLIENT.".state ,".TBL_CLIENT.".zip,".TBL_SERVICECALL.".*,".TBL_CLIENT_TYPE.".* 

 
 
 from ".TBL_SERVICECALL."  
  inner join ".TBL_CLIENT." on ".TBL_CLIENT.".clientid =".TBL_SERVICECALL.".clientid
  left outer join ".TBL_CLIENT_TYPE." on ".TBL_CLIENT_TYPE.".type_id =".TBL_CLIENT.".type_id  ";
  
 if($_SESSION['use_forms']==1){
			$sql.=" left outer join ".TBL_FORM_TYPE." on ".TBL_CLIENT.".form_type_id =".TBL_FORM_TYPE.". form_type_id";
			
			} 
$sql.="  where serviceid='".$this->serviceid."'  and ".TBL_SERVICECALL.".customerno=".$_SESSION['customerno']."";



$result= $this->db->query($sql,__FILE__,__LINE__);
$row1= $this->db->fetch_array($result);
//echo "<pre>";
//print_r($row1);
$row=$row1;


if($row['status']==9){
foreach ($row as &$value) {
    $value = "CANCELLED";
}
$row['isdeleted']=1;
}

//print_r($row);

?>
<div class="onecolumn">
<div class="header">
<span>Service Detail</span>
<div class="switch" style="width:300px">
<table width="300px" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><a href="servicecalls.php">
<input type="button" id="chart_bar2" name="chart_bar2" class="left_switch" value="View All" style="width:100px"/>
</a></td>
<td><a href="servicecalls.php?index=search">
<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
</a></td>
<td><a href="servicecalls.php?index=add">
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
<form action="" method="post" enctype="multipart/form-data" name="<?php echo $FormName?>" id="<?php echo $FormName?>" >
<table class="data" width="100%">
<tr>
<th>Client Name: </th>
<td><?php echo $this->get_clientname($row1['clientid']);?> </td>
<th>Contact: </th>
<td><?php echo $row['contactperson'];?></td>
</tr>

<tr>
<?php if($_SESSION['customerno']==14){ ?>
<tr>
<th>client type</th>
<td><?php echo ucfirst($row1['type_name']); ?></td>
<?php if($_SESSION['use_forms']==1){ ?>
<th>form type</th>
<td><?php echo $row['form_type_name'];?></td>
<?php } ?>	
</tr>
<?php } ?>

<th>Address:  </th>
<td><?php echo $row['add1'];?> </td>
<th>Address:  </th>
<td><?php echo $row['add2'];?> </td>

</tr>
<tr>
<th colspan="1">City:  </th>
<td colspan="3"><?php echo $row['city'];?> </td>
</tr>
<tr>
<th>state : </th>
	<td><?php echo $row['state'];?></td>
	
	

	
	
	<th>Zip : </th>
	<td><?php echo $row['zip'];?></td>
	</tr>
<tr>
  <td></tr></td></tr>
<tr>
				<th>Email: </th>
				<td><?php echo $row['email'];?></td>
				
				<th>phone no:  </th>
				<td><?php echo $row1['phoneno'];?> </td>
		</tr>
				<tr>
				<th>starttime slot </th>
				<td><?php echo date("d-M-y h:i A",strtotime($row1['timeslot_start']));?> </td>
				
				<th>Endtime  slot </th>
				<td><?php echo date("d-M-y h:i A",strtotime($row1['timeslot_end']));?> </td>
				</tr>
				<tr>
				<th>trackee:  </th>
				<td><?php echo $this->get_trackee($row1['trackeeid']);?> </td>
				
				<th>trackingno:  </th>
				<td><?php echo $row['trackingno'];?> </td>
				</tr>
				<tr>
				<?php if(isset($_SESSION['SerUserField1'])){ ?>
				
				<th ><?php echo $_SESSION['SerUserField1'] ;?> details:</th>
				<td><?php echo $row['uf1']; ?>
				</td>
				
				
				
				<?php } ?>
				<?php if(isset($_SESSION['SerUserField2'])){ ?>
				
				<th ><?php echo $_SESSION['SerUserField2']; ?> details:</th>
				<td><?php echo $row['uf2']; ?>	</td>
				<?php } ?>
				</tr>
				<tr>
				<?php if(isset($_SESSION['CallExtra1'])){ ?>
				
				<th ><?php echo $_SESSION['CallExtra1'] ;?> :</th>
				<td><?php echo $row['callextra1']; ?>
				</td>
				
				
				
				<?php } ?>
				<?php if(isset($_SESSION['CallExtra2'])){ ?>
				
				<th ><?php echo $_SESSION['CallExtra2']; ?>:</th>
				<td><?php echo $row['callextra2']; ?>	</td>
				<?php } ?>
				</tr>
				
				
</table>





<table class="data" width="100%">
<tr > 

<td colspan="4"><?php $this->getservicelisttable($row1['serviceid']);?></td>
</tr>
<tr>
<th colspan="4">Bill description</th>
</tr>
<tr>
<td colspan="3"> service amount: </td>
<td style="text-align:right; padding-left:180px; " ><?php echo $row['totalbill'];?> </td>
</tr>
<tr>
<td></td>
<td ></td>

<td>visiting charges: </td>
<td style="text-align:right; padding-left:180px; " ><?php echo $row['visiting_charges'];?> </td>
</tr>
<tr>
<td>discount code: </td>
<td ><?php echo $row['discount_code'];?> </td>

<td>discount amount: </td>
<td style="text-align:right; padding-left:180px; " >-<?php echo $row['discount_amount'];?> </td>
</tr>

<tr>
<th colspan="3" class='bold'> net amount:</th>
<td style="text-align:right; "><d style="font-size:16px; color:#2E5B1E; " class='bold'><?php if(($row['totalbill']-$row['discount_amount']+$row['visiting_charges'])>0){ echo ($row['totalbill']-$row['discount_amount']+$row['visiting_charges']);}else{echo "0";}?> </d></td>
</tr>
</table>
<?php if($row['isdeleted']==0){ ?>
<table class="data" width="100%">
<tr>
<td>
<table class="data" width="100%">
<tr><th colspan="2">quality parameters </th></tr>

																	
																	

<tr>
<th>deteted from original</th>
<td id="red"><?php if($row['serviceid']!=""){ echo $this->get_services_by_service_id("DELETED_ORIGINAL","red",$row['serviceid'],"Deleted from Original","false","true");}?></td>

</tr>
<tr>
<th>Upgrade Amt</th>
<td id="green"><?php if($row['serviceid']!=""){echo $this->get_services_by_service_id("UPGRADES","green",$row['serviceid'],"Upgrades","false","true");}?></td>

</tr>
<tr>
<th>downgrade amt</th>
<td id="red"><?php if($row['serviceid']!=""){  echo $this->get_services_by_service_id("DOWNGRADES","red",$row['serviceid'],"Downgrades","false","true");}?></td>

</tr>
<tr>
<th>Start  time deviation</th>
<td><?php if($row['serviceid']!=""){ $this->starttime_deviation($row['serviceid']);}?> </td>

</tr>
<tr>
<th>Service time deviation</th>
<td><?php if($row['serviceid']==""){}else{$this->endtime_deviation($row['serviceid']);} ?></td>

</tr>
<tr>
<th>Delay (min)</th>
<td><?php echo $row['timesdelay']*WARNING2TIME; ?></td>

</tr>

</table>


</td>
<td>
<table class="data" width="100%">
<tr><th colspan="2">timings </th></tr>
<tr>
<th>Depart time</th>
<td><?php if  ($row['status']==7||$row['status']==6||$row['status']==2||$row['status']==3||$row['status']==4 ||$row['status']==5 ||$row['status']==8 ){echo date("d-M-y h:i A",strtotime($row['departtime'])); }else{echo "0";}?></td>
</tr>
<tr>
<th>start time</th>
<td><?php  if($row['status']==7||$row['status']==6||$row['status']==3||$row['status']==4 ||$row['status']==5 ||$row['status']==8){ echo date("d-M-y h:i A",strtotime($row['starttime'])) ;}else{echo "0";}?></td>
</tr>
<tr>
<th>Expected end time </th>
<td><?php if($row['status']>2){ echo date("d-M-y h:i A",strtotime($row['expected_endtime']));}else{echo "0";} ?></td>
</tr>
<tr>
<th>wrapping time </th>
 <td><?php  if($row['status']>2 && $row['warning1']!="0000-00-00 00:00:00"){ echo date("d-M-y h:i A",strtotime($row['warning1']));}else {echo 'NA';}?> </td>
</tr>
<tr>
<th>End time</th>
<td><?php if($row['status']==4 ||$row['status']==5){ echo date("d-M-y h:i A",strtotime($row['endtime'])) ;}else{echo "0";}  ?></td>
</tr>
<tr>
<th>Close time</th>
<td><?php if($row['status']==5){ echo date("d-M-y h:i A",strtotime($row['closedon']));}else{echo "0";} ?></td>
</tr>


</table>


</td>

</tr>

</table>
<table class="data" width="100%">
<tr>

</tr>
<tr>
<td><div><?php if($row['serviceid']!=""){$this->get_services_by_service_id("DELETED_ORIGINAL","red",$row['serviceid'],"Deleted from Original","true","false");}?></div></td>
<td><div><?php if($row['serviceid']!=""){$this->get_services_by_service_id("UPGRADES","green",$row['serviceid'],"Upgrades","true","false");}?></div></td>
<td><div><?php if($row['serviceid']!=""){$this->get_services_by_service_id("DOWNGRADES","red",$row['serviceid'],"Downgrades","true","false");}?></div></td>

</tr>

</table>
<?php 
if($row['status']==5 && ($row['trackeeid']!=0 || $row['trackeeid']!=-1)){
if($row['serviceid']!=""){$this->generate_feedback_result($row['serviceid']);}

?>

<table class="data" width="100%">
<tr>
<th>
Remark 
</th>
</tr>
<tr>
<td><?php $this->get_remark($row['remarkid']); ?>
</td>
</tr>
<tr>
<th>
Signature 
</th>
</tr>
<tr>
<td><?php $this->generate_imageurl($row['serviceid'],$row['trackeeid']); ?>
</td>
</tr>
</table>
<?php 
}

}
?>


</form>
</div>
</div>
<br class="clear"/>
<?php
$this->view_payments($serviceid);
$this->checklist_obj->get_result_by_serviceid($serviceid);	
}

// edit client
function ClientView($student_id)
{
$this->student_id=$student_id;
$sql="select * from ".TBL_CLIENT." where clientid='".$this->student_id."' and customerno=".$_SESSION['customerno']."";
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);
?>


	
	

	
	<th>state : </th>
	<td><?php echo $row['state'];?></td>
	
	

	
	
	<th>Zip : </th>
	<td><?php echo $row['zip'];?></td>
	</tr>
	
	

<?php
}

function editclient ($runat,$id)
{
//	$this->serviceid=$id;
	
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
			
			
			
			 $sql="select * from ".TBL_SERVICECALL." inner join ".TBL_CLIENT." on ".TBL_CLIENT.".clientid =".TBL_SERVICECALL.".clientid 
				left outer join ".TBL_CLIENT_TYPE." on ".TBL_CLIENT.".type_id =".TBL_CLIENT_TYPE.". type_id ";
			if($_SESSION['use_forms']==1){
			$sql.=" left outer join ".TBL_FORM_TYPE." on ".TBL_CLIENT.".form_type_id =".TBL_FORM_TYPE.". form_type_id";
			
			}
			$sql.=" where  ".TBL_SERVICECALL.".serviceid='".$id."' and  ".TBL_SERVICECALL.".isdeleted=0";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result);
			$this->trackeeid=$row['trackeeid'];
			
			//edit call check if status is >3
			if($row['status']>=3 && $_SESSION['user_type']!="Master"){
			$_SESSION['error_msg']='Call has already started !';
			?>
			<script type="text/javascript">
			window.location = "servicecalls.php";
			</script>
			<?php 
			}




			?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
			updatetotal();
			});
			</script>
		<div class="onecolumn">
		<div class="header">
		<span>Edit Service call details</span>
		<div class="switch" style="width:300px">
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
				<td><a href="servicecalls.php">
				<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
				</a>
				</td>
				<td><a href="servicecalls.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a>
				</td>
				<td><a href="servicecalls.php?index=add">
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
		<form action="" method="post" enctype="multipart/form-data" name="<?php echo $FormName?>" id="<?php echo $FormName?>" >
			
			<input type="hidden" name="trackid" value="<?php echo $row['trackeeid'];?>" />
			<input name="ischargable" type="hidden" id="ischargable" value="<?php echo $row['ischargable'];?>" />   
			<input type="hidden" id="form_type" name="form_type" value="<?php echo $_SESSION['use_forms']; ?>" />	
			
			
				
		<table  class="data" width="100%">
				<tr>
				<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
				<th >Client Name: </th>
				<td title="<?php  echo $this->get_clientname($row['clientid']);?>"><?php echo $this->get_clientname($row['clientid']);?></td>
				
				
				<td></td></td>
				</tr>
				<?php if($_SESSION['customerno']!=14){ ?>
				<?php if($_SESSION['use_forms']==1){ ?>
					<tr>
						<th>form type</th>
						<td><?php echo $row['form_type_name'];?></td>
					</tr>
					<?php } ?>		
				<tr>
				<th>Client Type:  </th>
				<td><?php echo $row['type_name'];?> </td>
				</tr>									
				<?php } ?>
				<tr>
				<th>Address:  </th>
				<td><?php echo $row['add1'];?> </td>
				</tr>
				<tr>
				<th>Address:  </th>
				<td><?php echo $row['add2'];?> </td>
				</tr>
				<tr>
				<th>state:  </th>
				<td><?php echo $row['state'];?> </td>
				</tr>
				<tr>
				<th>zip:  </th>
				<td><?php echo $row['zip'];?> </td>
				</tr>
				
				
				<tr>
				<th>Contact: </th>
				<td><?php echo $row['contactperson'];?></td>
				</tr>
				<tr>
				<th>Email: </th>
				<td><?php echo $row['email'];?></td>
				</tr>
				<tr>
				<th>phone no:  </th>
				<td><?php echo $row['phoneno'];?> </td>
				</tr>
				<tr>
				<th>starttime slot </th>
				<td><?php echo $row['timeslot_start'];?> </td>
				</tr>
				<tr>
				<th>Endtime  slot </th>
				<td><?php echo $row['timeslot_end'];?> </td>
				</tr>
				
				
				<?php if(isset($_SESSION['SerUserField1'])){ ?>
				<tr>
				<th ><?php echo $_SESSION['SerUserField1'] ;?> details:</th>
				<td><?php echo $row['uf1']; ?>				</td>
				</tr>
				
				
				<?php } ?>
				<?php if(isset($_SESSION['SerUserField2'])){ ?>
				<tr>
				<th ><?php echo $_SESSION['SerUserField2']; ?> details:</th>
				<td><?php echo $row['uf2']; ?>				</td>
				</tr>
				
				
				<?php } ?>
				<?php if(isset($_SESSION['CallExtra1'])){ ?>
				<tr>
				<th ><?php echo $_SESSION['CallExtra1'] ;?> details:</th>
				<td><?php echo $row['callextra1']; ?>				</td>
				</tr>
				
				
				<?php } ?>
				<?php if(isset($_SESSION['CallExtra2'])){ ?>
				<tr>
				<th ><?php echo $_SESSION['CallExtra2']; ?> details:</th>
				<td><?php echo $row['callextra2']; ?>				</td>
				</tr>
				
				
				<?php } ?>
				<tr>
				<th>trackingno:  </th>
				<td><?php echo $row['trackingno'];?> </td>
				</tr>
			
				 <tr>
                    <th>Trackee: </th>
                    <td><?php if($row['trackeeid']>0){  $this->selectcheckboxbyid($row['trackeeid']); }else { $this->selectcheckbox();} ?>
					<span id="span_trackee" class="span_trackee"></span> 
                   </td>
          </tr>
					
				  <tr>
                    <th>Services</th>
                    <td>
                        <?php $this->addservicesedit() ;?>
						<div id="domt"> </div>
							<div id="domte"> </div>
						<span id="span_service" class="span_service"></span>   
                        <table  class="data"   width="100%">
                            <thead><tr><th>Service Name</th><th>Expected Time</th><th>Price</th><th></th></tr>
                            </thead>
                            <tbody id="servilistul">
							<?php if($row['serviceid']){$this->getservicelist($row['serviceid']);} ?>
							
							</tbody>
                            <tfoot>
									<tr >
										<td style="border-top:#666666 1px solid;"></td>
										<td class="bold" style="border-top:#666666 1px solid;">Service Amount </td>
										<td id="service_amount" class="bold" style="border-top:#666666 1px solid;">0</td>
										<td style="border-top:#666666 1px solid;"><input type="hidden" value="<?php echo $row['totalbill'];?>" id="totalamount" name="totalamount" /></td>
									</tr>
									<tr>
										<td></td>
										<td class="bold" >Visiting charges</td>
										<td id="visiting_charges_html" class="bold">
										<input type="text" value="<?php echo $row['visiting_charges'];?>" name="visiting_charges" id="visiting_charges" onkeyup="on_changes_visiting_charges();"  />
										
										</td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td  class="bold" >total  Amount </td>
										<td id="net_amount_html" class=" bold"> </td>
										<td><input type="hidden"  id="net_amount" name="net_amount" /></td>
									</tr>
							
							
							
							
							</tfoot>
                      </table>
                    </td>
                  </tr>
				  
				   <tr>
							<th>Discount Code:</th>
							<td>
									<table width="100%" class="data">
									<tr>
										<td width="49%"><input name="dcode" type="text" id="dcode"  value="<?php echo $row['discount_code']?>" onkeyup="javascript:dis_check();">
										<input type="hidden" id="clientid" name="clientid" value="<?php echo $row['clientid']?>" />
										<input type="hidden" id="d_act" value="edit" />
										<input type="hidden" id="sid" value="<?php echo $row['serviceid']?>" />
										<br />
										<span id="dis_status"></span>       
										<span id="span_code" class="span_code"></span> </td>
										<td width="20%"  class=" bold">Discount Amount</td>
										<td width="30%"><input name="damt" type="text" id="damt"  value="<?php echo $row['discount_amount']?>" readonly="true" >
										<input name="dis_id" type="hidden" id="dis_id"  value="<?php echo $row['dis_id']?>">            
										<span id="span_amt" class="span_amt"></span> </td>
										<td width="9%" ></td>
									</tr>
									</table>
							</td>
                  </tr>
				  
				  
				  <tr>
					<th class="green_font bold"> </th>
					<td>
					<table width="100%" class="data">
								<tr>
										<td  width="49%" style="border-bottom:none;" > </td>
										<td  width="20%"  class="green_font bold" style="border-bottom:none;">net amount</td>
										<td width="30%" class="green_font bold"  style="border-bottom:none;">Rs. <span  id="net_amount_after_discount">0</span></td>
										<td width="9%"  style="border-bottom:none;"></td>
								</tr>
									
					
					</table>
					
					</td>
					
					</tr>
				  
				  <tr>
                      <th>Start Timings</th>
                      <td><b><?php echo "selected time slot :  ".date($row['timeslot_start']); ?></b>
					 
					  
					  <br />
					  <input id="date_s" name="date_s" value="<?php echo $row['timeslot_start'];?>"  type="hidden"/>
					   Check to Modify timeslot:
                      <input type="checkbox" name="modifydate" id="modifydate" onclick="javascript:dis_check();">
                      <br />
					  <input type="text" class="datepicker_dis" name="stime" id="stime" style="width:300px" value="<?php echo date("Y-m-d",strtotime( $row['timeslot_start']));?>"> 
					  Time hours 
                          <select name="shours">
                              
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              
                              
                              
                              
                              
                              
                        </select>minutes
						    <select name="smin">
                              
                              <option value="00">00</option>
                              <option value="10">10</option>
                              <option value="20">20</option>
                              <option value="30">30</option>
                              <option value="40">40</option>
                              <option value="50">50</option>
                              
                              
                              
                              
                              
                              
                              
                        </select>
                       <select name="sampm">
                              
                              <option value="am">am</option>
                              <option value="pm">pm</option>
                                                  
                              
                              
                              
                        </select>
				  </td>
                      
          </tr>
				 
				
				
				<tr>
				  <td colspan="2">
				  <input type="hidden" name="apply_d" id="apply_d" value="1" />
				  <input type="submit" name="submit" value="Submit" 
				onclick="return postedit();" />
				&nbsp;
				<input type="button" onclick="javascript: history.go(-1); return false" 
				name="cancel" value="Cancel" /></td>
		  </tr>
		</table>
		</form>
		<script type="text/javascript" >
		function postedit(){
			
					var code=jQuery("#dcode").val();
					var amt=jQuery("#damt").val();
					var tamt=jQuery("#totalamount").val();
					var ret= true;
					var trackee=jQuery("#to").val();
					
					if(trackee==""){
					
					jQuery("#span_trackee").addClass("alert_error");jQuery("#span_trackee").html("please select trackee");ret= false;
					}
					 
					if(tamt<=0 && jQuery("#ischargable").val()>0){
					jQuery("#span_service").addClass("alert_error");
					jQuery("#span_service").html("please select a service");ret= false;
					}	
					if((amt=="" ||amt=="0") && code==""){

							

					}else{
									/*if(code==""){
									if(amt!="" ||amt!="0"){
										jQuery("#span_code").addClass("alert_error");jQuery("#span_code").html("please enter the code");ret= false;
									}
									 
									}else if(amt=="" ||amt=="0"){
									if(code!=""){jQuery("#span_amt").addClass("alert_error");jQuery("#span_amt").html("please enter the amount");ret= false;}
									
									}*/
					 }
					
	
		
		return ret
		}
		
		
			
		</script>
		</div>
		</div>
		<br class="clear"/>
		<?php
		
		break;
		case 'server' :	
				extract($_REQUEST);
		
								$this->totalamount=$totalamount;
								$this->to=$to;
								$this->Servicelist=$Servicelist;
								$this->visiting_charges=$visiting_charges;
		
		
								///date valuse changes 
								$this->stime=$stime;
								$this->shours=$shours;
								$this->sampm=$sampm;
			
								if($modifydate==on){		
								if($sampm=="am")
									{
										$slottime=  $stime." ";
										if($shours==12){$shours="00";}
										$slottime.= ($shours).":".$smin.":00";   
									}else if($sampm=="pm"){
								
										if($shours==12)
										{
										if($shours==12){$shours="00";}	
										$arr=explode("-",$stime);
										$str1=$arr[2]; 
										$slottime.=$arr[0]."-".$arr[1]."-".$arr[2]."";
										}else{
										$slottime=  $stime." ";
										
										}
										
										 $slottime.= " ".($shours+12).":".$smin.":00";
									}
									
								}
		
		
									// server side validation
									$return =true;
									$sql2="select * from ".TBL_SERVICECALL."  where serviceid='".$id."'";
									$result2= $this->db->query($sql2,__FILE__,__LINE__);
									$row2= $this->db->fetch_array($result2);
									
									//edit call check if status is >3
									if($row2['status']>=3 && $_SESSION['user_type']!="Master"){
									
									 $return=false;
									$_SESSION['error_msg']='Call has already started !';
									?>
									<script type="text/javascript">
									window.location = "servicecalls.php";
									</script>
									<?php 
									}
		
		if($return){
		
		$update_sql_array = array();
		$update_sql_array['totalbill'] = $totalamount;
		$update_sql_array['visiting_charges'] = $visiting_charges;
		$update_sql_array['ischargable'] = $ischargable;
		
		$update_sql_array['branchid'] = $_SESSION['branchid'];
				

				if($dis_id>0){
					$update_sql_array['dis_id'] = $dis_id;
					$update_sql_array['is_web'] = "1";
					$update_sql_array['discount_code']=$dcode;
						if($damt!=""){
							$update_sql_array['discount_amount']=$damt;
						}else{
							$update_sql_array['discount_amount']="0";
						
						}
				}else{
					$update_sql_array['discount_code']="";
					$update_sql_array['discount_amount']="0";
					$update_sql_array['dis_id'] = "0";
				}
		
		
		
		
		$update_sql_array['trackeeid'] = $this->to;
						if($modifydate==on){
							$update_sql_array['timeslot_start']=$slottime;
						}
						
						
						
						
						if($to==0)
						{
						
						}else{
								if( $to==$trackid){
									$this->updatetrackeebyid("service",$to);
								}else {
									$this->updatetrackeebyid("service",$to);
									$this->updatetrackeebyid("service",$trackid);
									$update_sql_array['status'] = '0';
								}
						
						}
	
						for($i=0;$i<count($delete_service);$i++)
						{
							 	$sql22="Update ".TBL_SERVICEMAN." set isdeleted=1 where servicelistid=".$delete_service[$i]."";
								$result22= $this->db->query($sql22,__FILE__,__LINE__);
						}
						
						for($i=0;$i<count($servicelistedit);$i++)
						{
								$pusharray['servicecallid']=$serviceid;
								$pusharray['servicelistid']=$servicelistedit[$i];
								$pusharray['customerno']=$_SESSION['customerno'];
								$pusharray['timestamp']=date("Y-m-d H:i:s");
								if($to==-1){$to==0;}
								$pusharray['trackeeid']=$to;
								$pusharray['userid']=$_SESSION['user_id'];
								$pusharray['iscreatedby']="0";
								$this->db->insert(TBL_SERVICEMAN,$pusharray);
						}
		
								
		$this->db->update(TBL_SERVICECALL,$update_sql_array,'serviceid',$id);
		if($modifydate==on){
		 $this->update_endtime_by_sid($id,$slottime);
		 }else{
		 $this->update_endtime_by_sid($id,$date_s);
		 }
		$_SESSION['msg']='Call has been updated successfully';
		
		
		
		
		
		
		?>
		<script type="text/javascript">
	
		window.location = "servicecalls.php";
		
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
			
			
			$sql_1="delete from ".TBL_SERVICECALL." where serviceid='".$this->id."'";
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
		<form action="servicecalls.php?index=search" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				<tr>
				<th>Client name: </th>
				<td><input   type="text" value="<?php echo $_REQUEST['clientname'];?>" id="clientname" name="clientname" /></td>
				<th>Trackee : </th>
				<td><?php $this->selectcheckbox(); ?></td>
				
				
				</tr>
				<tr>
				<th>Apointment Date From: </th>
				<td><input   type="text" value="" id="appointmentdate" name="appointmentdate" class="datepicker1" /></td>
				<th>Created on from: </th>
				<td><input   type="text" value="" id="datecreated" name="datecreated" class="datepicker2" /></td>
				
				</tr>
				<tr>
				<th>Apointment Date to: </th>
				<td><input   type="text" value="" id="appointmentdatet" name="appointmentdatet" class="datepicker3" /></td>
				<th>Created on to: </th>
				<td><input   type="text" value="" id="datecreatedt" name="datecreatedt" class="datepicker4" /></td>
				
				</tr>
				<tr>
				
				<th>Status : </th>
				<td><?php $this->selectstatus(); ?></td>
				<th>Tracking no : </th>
				<td><input   type="text" value="" id="trackingno" name="trackingno"></td>
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



function SearchRecord($clientname='',$to='',$appointmentdate='',$datecreated='',$appointmentdatet='',$datecreatedt='',$status='',$trackingno)
{
if($appointmentdate!="" && $appointmentdatet!="" ){
if(strtotime($appointmentdatet)<strtotime($appointmentdate)){
$appointmentdatet=$appointmentdate;
}
if(strtotime($appointmentdatet)==strtotime($appointmentdate)){

 
$newendtimeslot = strtotime('+ 1 Day', strtotime($appointmentdate));
 $appointmentdatet = date("Y-m-d",$newendtimeslot);
}

}
if($datecreated!="" && $datecreatedt!="" ){
if(strtotime($datecreatedt)<strtotime($datecreated)){
$datecreatedt=$datecreated;
}
if(strtotime($datecreatedt)==strtotime($datecreated)){

 $newendtimeslot = strtotime('+ 1 Day', strtotime($datecreated));
 $datecreatedt = date("Y-m-d",$newendtimeslot);
}
}
if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0" ){
$branch_clause=" and branchid=".$_SESSION['branchid'];
}
if($to<=0){$to="";}
$sql="select * from ".TBL_SERVICECALL." where 1 and customerno=".$_SESSION['customerno']."".$branch_clause;
if($clientname)
{
$sql .= " and clientname like '%".$clientname."%'";
}
if($status)
{
$sql .= " and status = ".$status."";
}
if($to)
{
$sql .= " and trackeeid = ".$to."";
}
if($appointmentdate)
{
$sql .= " and timeslot_start > '".$appointmentdate."'";
}
if($appointmentdatet)
{
$sql .= " and timeslot_start < '".$appointmentdatet."'";
}
if($datecreated)
{
$sql .= " and createdon > '".$datecreated."'";
}
if($datecreatedt)
{
$sql .= " and createdon < '".$datecreatedt."'";
}
if($trackingno)
{
$sql .= " and trackingno = '".$trackingno."'";
}


$sql.=" order by serviceid desc ";
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
<td><a href="servicecalls.php">
<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
</a>
</td>
<td>
<input type="button" id="chart_line" name="chart_line" class="middle_switch active" value="Search" style="width:100px" />
</td>
<td><a href="servicecalls.php?index=add">

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


						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table_2">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Client Name</th>
									<th>Trackee:  </th>
									
									<th>timeslot start</th>
									<th>timeslot end</th>
									<th>status</th>
									
									<th width="10%">action</th>
									</tr>
						
						</thead>
						<tbody>
									<?php 	
                                                                      
									while($row= $this->db->fetch_array($result))
									{
									?>
									<tr>
									
									<td><?php echo $x;?></td>
									<td title="<?php echo $this->get_clientname($row['clientid']);?>"><?php echo $this->get_clientname($row['clientid']); ?></td>
									
									
									<td><?php echo $this->get_trackee($row['trackeeid']);?> </td>
									<td><?php echo date("d-M-y h:i A",strtotime($row['timeslot_start']));?> </td>
									<td><?php echo date("d-M-y h:i A",strtotime($row['timeslot_end']));?> </td>
									<?php $this->displayactions($row['status'],$row['serviceid'],$row['trackeeid'],$row['isdeleted']);?>
									
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
								<a href="servicecalls.php">&laquo;&laquo;</a>
								<a href="servicecalls.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="servicecalls.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="servicecalls.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="servicecalls.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="servicecalls.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="servicecalls.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="servicecalls.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="servicecalls.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="servicecalls.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="servicecalls.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="servicecalls.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="servicecalls.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>	</div>

<div align="right">Total Pages - <?php echo $lastpage;?></div>
<div align="right">Total Records - <?php echo $numpages;?></div>

</div>

</div>

<?php 

}
function get_trackee($trackeeid)
{
$sql="select * from ".TBL_TRACKEE." where trackeeid=".$trackeeid." and isdeleted=0";
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);
echo $row['tname'];
}


function getservicelist($serviceid)
{
$sql="select * from ".TBL_SERVICEMAN." where customerno=".$_SESSION['customerno']." and servicecallid=".$serviceid." and  isdeleted=0";
$result= $this->db->query($sql,__FILE__,__LINE__);
while($row= $this->db->fetch_array($result))
{
?>
<tr id="lids_<?php echo $row['id'];?>">
<?php $this->get_servicename($row['servicelistid'],$row['id']);?>
<?php 
}

}

function get_servicename($servicelistid,$id)
{
$sql="select * from ".TBL_SERVICELIST." where customerno=".$_SESSION['customerno']." and servicelistid=".$servicelistid." and isdeleted=0";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
$row= $this->db->fetch_array($resultpages) ;
?>

 <th><?php echo $row['servicename'];?></th><td><?php echo $row['expectedtime'];?></td><td><?php echo $row['price'];?></td><td><img id="rm" src="images/rem.png" onclick="removedomedit2(<?php echo $row['servicelistid'];?>,'<?php echo $row['price'];?>',<?php echo $id;?>)"/>

 
 
</td>
</tr>
<?php

}

function manageservices($manageid)
{
$sql="UPDATE ".TBL_SERVICEMAN." SET isdeleted=1 WHERE id=".$manageid." and isdeleted=0";
$this->db->query($sql,__FILE__,__LINE__); 
echo "done";
}

function getservicelisttable($serviceid)
{

$sql="select * from ".TBL_SERVICEMAN." where customerno=".$_SESSION['customerno']." and servicecallid=".$serviceid." and  isdeleted=0 ";
$result= $this->db->query($sql,__FILE__,__LINE__);
$x=1;
?>
<table class="data" width="100%" >
<thead>
</thead>
<tr>
  <th>service name</th>

<th>expected time</th>
<th>price</th>
<td></thead></td>
<?php
while($row= $this->db->fetch_array($result))
{
  echo $this->get_servicenamebyid($row['servicelistid']);
}
?>
</tr>
</table>


<?php


}
function get_service_original($serviceid)
{
	
	$sql="select * from ".TBL_SERVICEMAN." where customerno=".$_SESSION['customerno']." and servicecallid=".$serviceid." and iscreatedby=0 ";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$x=1;
	?>
	<table class="data" width="100%" >
		
		<tr>
		<th>Original Service</th>
		
		
		<th>price</th>
		<td></thead></td>
			<?php
			while($row= $this->db->fetch_array($result))
			{
			  echo $this->get_servicename_by_id_green($row['servicelistid']);
			}
			?>
		</tr>
	</table>
	<?php
}

///this function returns all service depending on the paramenters passed by on call;

function get_services_by_service_id($type,$background,$serviceid,$name,$dom,$price)
{
	if($serviceid!=="")
	{
	$sql="select * from ".TBL_SERVICEMAN." where customerno=".$_SESSION['customerno']." and servicecallid=".$serviceid." ";
	
	if($type=="A_W_D_M" || $type=="DELETED_ORIGINAL")
	{
	
		$sql.=" and iscreatedby =0 AND 	isdeleted =1 and iseditedby=1";
	
	}else if($type=="A_M_E_M" || $type=="UPGRADES"){
	
		$sql.=" and iscreatedby =1 AND 	iseditedby IN (0,1) ";
	
	}else if($type=="A_M_D_M" || $type=="DOWNGRADES"){
	
		$sql.=" and iscreatedby =1 AND 	isdeleted =1 and iseditedby IN (0,1) ";
	
	}else if($type=="AWM" || $type=="ALL_ACTIVE"){
	
		$sql.=" and isdeleted =0";
	
	}
		$sql;
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$result_number= $this->db->query($sql,__FILE__,__LINE__);
		if($dom=="true"){
							?>`
							<table class="data" width="100%" >
							<thead>
							<tr>
							<th colspan="2"><?php echo $name; ?></th>
							</tr>
							</thead>
							<tr>
							<th>service name</th>
							<th>price</th>
							
							</tr>
							<?php
							
							
								if($background=="green"){
									$bg='id="green"';
								}elseif($background=="red"){
									$bg='id="red"';			
								}
			}else{
			$raw_name_array=array();
			
			$amount=0;
			}
			
			if($this->db->num_rows($result_number)==0){
				if($dom=="true"){
					?>
					<tr>
					<td colspan="2">No services</td>
					</tr>
					<?php 
				
				}else{
				$raw_array[]= " ";
				$amount=0;
				}
			
			}
			while($row= $this->db->fetch_array($result))
			{
					$sql1="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."' AND servicelistid=".$row['servicelistid']." and isdeleted=0  ";
					$resultpages1= $this->db->query($sql1,__FILE__,__LINE__);    
					$row1= $this->db->fetch_array($resultpages1) ;
					if($dom=="true"){
					?>
					<tr>
					<td><div <?php echo $bg ; ?>><?php echo $row1['servicename'];?></div></td>
					
					<td><div <?php echo $bg ; ?> ><?php echo $row1['price'];?></div></td>
					</tr>
					<?php 
					}else{
					
					$raw_array[]= $row1['servicename'];
					$amount+=$row1['price'];
					}
			}
			if($dom=="true"){
			?>
			</table>
			
		<?php
			}else{
				if($price=="false"){
			
				return $raw_array;
				
				}else{
				
				return $amount;
			
				}
			}
	}
}

function get_services_green($serviceid)
{
$sql="select * from ".TBL_SERVICEMAN." where customerno=".$_SESSION['customerno']." and servicecallid=".$serviceid." and iscreatedby=1 ";
$result= $this->db->query($sql,__FILE__,__LINE__);
$x=1;
?>
<table class="data" width="100%" >
<thead>
</thead>
<tr>
  <th>service name</th>
<th>price</th>
<td></thead></td>
<?php
while($row= $this->db->fetch_array($result))
{
  echo $this->get_servicename_by_id_green($row['servicelistid']);
}
?>
</tr>
</table>
<?php
}
function get_services_red($serviceid)
{

$sql1="select * from ".TBL_SERVICEMAN." where customerno=".$_SESSION['customerno']." and servicecallid=".$serviceid." and iscreatedby=0 and  isdeleted=1 and iseditedby=1";
$result1= $this->db->query($sql1,__FILE__,__LINE__);
$sql2="select * from ".TBL_SERVICEMAN." where customerno=".$_SESSION['customerno']." and servicecallid=".$serviceid." and iscreatedby=1 and  isdeleted=1 ";
$result2= $this->db->query($sql2,__FILE__,__LINE__);
$x=1;
?>
<table class="data" width="100%" >
<thead>
</thead>
<tr>
  <th>service name</th>


<th>price</th>
<td></thead></td>
<?php
while($row1= $this->db->fetch_array($result1))
{
  echo $this->get_servicenamebyidr($row1['servicelistid']);
}

while($row2= $this->db->fetch_array($result2))
{
  echo $this->get_servicenamebyidr($row2['servicelistid']);
}
?>
</tr>
</table>


<?php


}
	function get_servicenamebyid($servicelistid)
	{
		$sql="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."' AND servicelistid=".$servicelistid." and isdeleted=0  ";
		$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
		$row= $this->db->fetch_array($resultpages) ;
		?>
		<tr >
		<td><?php echo $row['servicename'];?></td>
		<td><?php echo $row['expectedtime'];?></td>
		<td><?php echo $row['price'];?></td>
		</tr>
		<?php 
	}
function get_servicename_by_id_green($servicelistid)
{
$sql="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."' AND servicelistid=".$servicelistid." and isdeleted=0  ";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
$row= $this->db->fetch_array($resultpages) ;
?>
<tr >
<td><div id="green"><?php echo $row['servicename'];?></div></td>

<td><div id="green"><?php echo $row['price'];?></div></td>
</tr>


<?php 
}
function get_servicenamebyidr($servicelistid)
{
$sql="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."' AND servicelistid=".$servicelistid." and isdeleted=0  ";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
$row= $this->db->fetch_array($resultpages) ;
?>
<tr >
<td><div id="red"><?php echo $row['servicename'];?></div></td>

<td><div id="red"><?php echo $row['price'];?></div></td>
</tr>


<?php 
}
function updatetrackee($type,$trackeeid){

$update_sql_array=array();

if($type=="service"){
$update_sql_array['pushservice']='1';
}
$this->db->update(TBL_TRACKEE,$update_sql_array,"customerno",$_SESSION['customerno']);
}

function updatetrackeebyid($type,$trackeeid){

$update_sql_array=array();

if($type=="service"){
$update_sql_array['pushservice']='1';
}
$this->db->update(TBL_TRACKEE,$update_sql_array,"trackeeid",$trackeeid);
}



function deleteupdate($serviceid)
{
            ob_start();
            $sql2="select * from ".TBL_SERVICECALL."  where serviceid='".$serviceid."'";
			$result2= $this->db->query($sql2,__FILE__,__LINE__);
			$row2= $this->db->fetch_array($result2);

            // reverting the visisted dates;
            $sql_3="select * from ".TBL_SERVICECALL." where clientid=".$row2['clientid']." ORDER BY serviceid DESC LIMIT 1,1";
            $result_3= $this->db->query($sql_3,__FILE__,__LINE__);
            $row_3= $this->db->fetch_array($result_3);
            $update_client_array = array();
            $update_client_array['last_visit']=$row_3['closedon'];
            $this->db->update(TBL_CLIENT,$update_client_array,'clientid',$row_3['clientid']);

			//edit call check if status is >3
			if($row2['status']>=3 && $_SESSION['user_type']!="Master")
            {
                $return=false;
                $_SESSION['error_msg']='Call has already started !';
                ?>
                    <script type="text/javascript">
                    window.location = "<?php echo $_SERVER['PHP_SELF']; ?>"
                    </script>
                <?php

			}else{
			
                    $update_array = array();
                    $update_array['isdeleted'] = '1';
                    $update_array['status'] = '9';
                    $update_array['add1'] = 'CANCELLED';
                    $update_array['add2'] = 'CANCELLED';
                    $update_array['discount_code'] = 'CANCELLED';
                    $update_array['contactperson'] = 'CANCELLED';
                    $update_array['uf1'] = 'CANCELLED';
                    $update_array['uf2'] = 'CANCELLED';
                    $update_array['callextra1'] = 'CANCELLED';
                    $update_array['callextra2'] = 'CANCELLED';
                    $update_array['email'] = 'CANCELLED';
                    $update_array['callextra2'] = 'CANCELLED';
                    $update_array['cancelled_view'] = '1';
                    $this->updatetrackeebyid("service",$row2['trackeeid']);
                    $this->db->update(TBL_SERVICECALL,$update_array,'serviceid',$serviceid);
                    $_SESSION['msg']='Record has been deleted successfully';

                    ?>
                    <script type="text/javascript">
                    window.location = "servicecalls.php";
                    </script>
                    <?php
            }
$html = ob_get_contents();
ob_end_clean();
return $html;
}


function getstatue($flowid)
{

	if($flowid){
		$sql="select * from ".TBL_FLOW." where serviceflowid=".$flowid." ";
		$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
		$row= $this->db->fetch_array($resultpages) ;
		return $row['name'];
	}
}

function displayactions($status,$serviceid,$trackeeid,$isdeleted){
	?>
	<td>
		<?php if($status!=0){echo $this->getstatue($status);}else{ if($trackeeid==0||$trackeeid==-1){echo "UNASSIGNED";}else{echo "ASSIGNED";}}?>
	</td>
	<td>
		<a href="servicecalls.php?index=View&amp;serviceid=<?php echo $serviceid;?>" title="View" class="help">
		<img src="images/icon_users.png" width="15px" height="15px" /></a>
								
				<?php 
				if($status==0 ||$status==1 ||$status==2  || $_SESSION['user_type']=="Master" ){ 
					?>
					|
					<a href="servicecalls.php?index=add_payment&service_id=<?php echo $serviceid;?>" title="add payments" class="help">
					<img src="images/icon_pages.png" width="15px" height="15px" /></a>|
		
					<a href="servicecalls.php?index=edit&amp;serviceid=<?php echo $serviceid;?>" title="Edit" class="help">
					<img src="images/icon_edit.png" width="15px" height="15px" /></a>|
					
					<a href="javascript: void(0);" title="delete" class="help" 
					onclick="javascript: if(confirm('Do u want to delete this record?')) { servicecall_master_info.deleteupdate('<?php echo $serviceid;?>',{}) }; return false;" >
					<img src="images/icon_delete.png" width="15px" height="15px" /></a>
					<?php 
				}
	 
	 ?></td>
	<?php
}

function dashboard_header()
{
if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
$branch_clause=" and branchid=".$_SESSION['branchid'];
}
 $sql="select * from ".TBL_SERVICECALL." where  customerno=".$_SESSION['customerno']." ".$branch_clause." and isdeleted=0 and timeslot_start >= DATE_SUB(NOW(), INTERVAL 24 HOUR) ";
$sql.=" order by serviceid desc ";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);
$total_calls=0;
$total_unassigned=0;
$total_assigned=0;
$progress=0;
$closed=0;
$open=0;
while($row= $this->db->fetch_array($resultpages)){
$total_calls++;
if($row['trackeeid']==0){
$total_unassigned++;
}else{
$total_assigned++;
}
if($row['status']==5){
$closed++;
}
if($row['status']==2||$row['status']==3||$row['status']==4||$row['status']==6||$row['status']==7 ||$row['status']==8){
$progress++;
}
if($row['status']==0 || $row['status']==1){
$open++;
}
}
?>
<table class="data" width="100%" style="text-align:center;">
<thead >
</thead>
<tr>
  <th>Total calls</th><th>Unassigned calls</th><th>Assigned calls</th><th>Open</th>
  <th>In Progress</th> <th>Closed</th>
<td></thead></td>
</tr>
<tbody  style="font-size:18px;">
</tbody>
<tr>
  <th><?php echo  $total_calls;?> </th><th><?php echo  $total_unassigned;?></th><th><?php echo  $total_assigned;?></th><th><?php echo  $open;?></th><th><?php echo  $progress;?></th> <th><?php echo  $closed;?></th>
<td></tbody></td>
</tr>
</table>
<?php
}


function dashboard_body(){
if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
$branch_clause=" and branchid=".$_SESSION['branchid'];
}
$sql="select * from ".TBL_TRACKEE." where customerno=".$_SESSION['customerno']."  and isdeleted=0 ".$branch_clause;
$result= $this->db->query($sql,__FILE__,__LINE__);

$x=1;

?>
<table class="data" width="100%">
  <thead>
	</thead>
  <tr>
    <th>Sno</th><th>name</th><th>status</th><th>calls closed</th> <th>calls pending</th><th>Available in (mins)</th>
	<td></thead></td>
  </tr>
  <tbody>
	<?php while($row= $this->db->fetch_array($result)){?>
	<tr>
		<td><?php echo $x++; ?></td>
		<td><?php echo $row['tname']; ?></td>
		<td><?php echo $this->calculate_status($row['trackeeid']) ;?></td>
		<td><?php echo $this->get_closedby($row['trackeeid'],'5') ;?></td>
		<td><?php echo $this->get_closedby($row['trackeeid'],'0,1') ;?></td>
		<td><?php echo $this->calculate_ava($row['trackeeid']) ;?></td>
	</tr>
	
	<?php }?>
  </tbody>
</table>



<?php
}


function get_closedby($trackeeid,$status){
if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
$branch_clause=" and branchid=".$_SESSION['branchid'];
}
$sql="select * from ".TBL_SERVICECALL." where  isdeleted=0 ".$branch_clause." and timeslot_start >= DATE_SUB(NOW(), INTERVAL 24 HOUR)  and  trackeeid=".$trackeeid." and status in (".$status.")";
$sql.=" order by serviceid desc ";
$result= $this->db->query($sql,__FILE__,__LINE__);
$x=0;
while($row= $this->db->fetch_array($result)){
$x++;
$this->serviceid_ava=$row['serviceid'];
$this->warning1time=$row['warning1'];
}
return $x;
}

function calculate_status($trackeeid){
$str="";
if($this->get_closedby($trackeeid,"3,4,6,7,8")>0){
$str="<img src='images/red.png'> BUSY ";

	}else if($this->get_closedby($trackeeid,"2")>0){
	$str="<img src='images/orange.png'> VISITING ";
	}else {
	$str="<img src='images/green.png'> FREE ";
	}
	return $str;

}
function calculate_ava($trackeeid){
$str="";
$this->serviceid_ava="";
if($this->get_closedby($trackeeid,"3")>0){
	
	if($this->serviceid_ava!=""){
	//echo $this->get_servicelist($this->serviceid_ava);
	echo $this->deviation_ava($this->serviceid_ava);
	
	
		
	}
	}else if($this->get_closedby($trackeeid,"4")>0){
	
	$str="One minute";
	}else if($this->get_closedby($trackeeid,"6")>0)
	{
	
	$this->warning1time;
	echo $this->warning_ava($this->serviceid_ava,"warning1");
	}
	else if($this->get_closedby($trackeeid,"7")>0)
	{
	
	$this->warning1time;
	echo $this->warning_ava($this->serviceid_ava,"warning2");
	}
	return $str;

}
function generat_code(){
$sql="select * from ".TBL_SERVICECALL." where customerno='".$_SESSION['customerno']."' AND 1" ;
$sql.= " order by serviceid desc";


$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);

echo "ELXSER_0".($row['serviceid']+1);
}

function generate_feedback_result($serviceid){
$sql="select * from ".TBL_FEEDBACKRESULTS." where serviceid=".$serviceid." and customerno=".$_SESSION['customerno']."  " ;
$result= $this->db->query($sql,__FILE__,__LINE__);

?>
<table class="data" width="100%">
<tr><th colspan="2">feedback</th></tr>
<?php 
while($row= $this->db->fetch_array($result))
{
?>
<tr><td><?php $this->getfeedback_question($row['feedbackqid']); ?></td>

<td><?php $this->getfeedback_option($row['feedbackoptionid']); ?></td></tr>
<?php } ?>
</table>
<?php 

}
function getfeedback_question($qid){
$sql="select * from ".TBL_FEEDBACKQUESTIONS." where feedbackquestionid=".$qid." and customerno=".$_SESSION['customerno']."  " ;
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);
echo $row['feedbackquestion'];

}

function getfeedback_option($opid){
$sql="select * from ".TBL_FEEDBACKOPTIONS." where feedbackoptionid=".$opid." and customerno=".$_SESSION['customerno']."  " ;
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);
echo $row['optionname'];
}
function upgrade_amt($callid)
{
$sql="select * from ".TBL_SERVICEMAN." where servicecallid=".$callid." and customerno=".$_SESSION['customerno']."" ;
$result= $this->db->query($sql,__FILE__,__LINE__);
$add=0;
$subs=0;
while($row=$this->db->fetch_array($result))
{

	if(($row['iscreatedby']==1) )
	{
	echo "";
	 $add+=$this->getservice_price($row['servicelistid']);
	}
	


}
if($add>0){
echo "<div id='green'>".$add."</div>";
}else{
echo ($add);
}

}
function downgrade_amt($callid)
{
$sql="select * from ".TBL_SERVICEMAN." where servicecallid=".$callid." and customerno=".$_SESSION['customerno']."" ;
$result= $this->db->query($sql,__FILE__,__LINE__);
$add=0;
$subs=0;
while($row=$this->db->fetch_array($result))
{

	if(($row['iscreatedby']==0 )&& ($row['iseditedby']==1)&& ($row['isdeleted']==1))
	{
	 $add+=$this->getservice_price($row['servicelistid']);
	}
	
	if(($row['iscreatedby']==1 )&& ($row['isdeleted']==1))
	{
	 $add+=$this->getservice_price($row['servicelistid']);
	}
	


}

if($add>0){
echo "<div id='red'>".$add."</div>";
}else{
echo ($add);
}
}
function getservice_price($listid){
 $sql="select * from ".TBL_SERVICELIST." where servicelistid=".$listid." and customerno=".$_SESSION['customerno']."" ;
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);
return $row['price'];
}
function getservice_time($listid){
 $sql="select * from ".TBL_SERVICELIST." where servicelistid=".$listid." and customerno=".$_SESSION['customerno']."" ;
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);
return $row['expectedtime'];
}



function generate_imageurl($serviceid,$trackeeid){
$sql="select * from ".TBL_DEVICE." where trackeeid=".$trackeeid." and customerno=".$_SESSION['customerno']."" ;
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);


echo $str="<img src='customer/".$_SESSION['customerno']."/".$row['devicekey']."/signature/service/".$serviceid.".jpeg' />";


}


function endtime_deviation($servicecall_id){
if($servicecall_id!=""){
$sql="select * from ".TBL_SERVICECALL." where customerno=".$_SESSION['customerno']." and serviceid=".$servicecall_id." and isdeleted=0" ;
 $sql.= " order by serviceid desc";
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);
//if($row['starttime']=="0000-00-00 00:00:00" || $row['endtime']=="0000-00-00 00:00:00"){
//echo "0";
//}else{
if($row['serviceid']!=""){
//echo (strtotime($row['endtime']) - strtotime($row['starttime']));

$absvalue= abs(strtotime($row['endtime']) - strtotime($row['starttime'])-($this->get_servicelist($row['serviceid'])*60));
$val=(strtotime($row['endtime']) - strtotime($row['starttime'])-($this->get_servicelist($row['serviceid'])*60));
if($val>0){$col="red";}else{$col="green";}
echo $this->dateDifference($absvalue,$col );
}

}
//}

}

function deviation_ava($servicecall_id){
$absvalue = 0;
$sql="select * from ".TBL_SERVICECALL." where customerno=".$_SESSION['customerno']." and serviceid=".$servicecall_id." and isdeleted=0" ;
 $sql.= " order by serviceid desc";
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);
//echo  $this->get_servicelist($row['serviceid']);
$absvalue= strtotime($this->dataobj->add_hours(date("Y-m-d H:i:s"),0)) - strtotime($row['starttime'])-($this->get_servicelist($row['serviceid'])*60);
if($absvalue < 0)
{
    $absvalue = abs($absvalue);
    echo $this->dateDifference($absvalue,"black");    
}
else
{
    echo "0 minutes";
}
}
function warning_ava($servicecall_id,$type){

$absvalue = 0;
$sql="select * from ".TBL_SERVICECALL." where customerno=".$_SESSION['customerno']." and serviceid=".$servicecall_id." and isdeleted=0" ;
 $sql.= " order by serviceid desc";
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);
//echo  $this->get_servicelist($row['serviceid']);
if($type=="warning1"){
$ctime=$row['warning1'];

$absvalue= strtotime($this->dataobj->add_hours(date("Y-m-d H:i:s"),0)) - strtotime($ctime)-20*60;
}else if($type=="warning2"){
$ctime=$row['starttime'];
$absvalue= strtotime($this->dataobj->add_hours(date("Y-m-d H:i:s"),0)) - strtotime($ctime)-($this->get_servicelist($row['serviceid'])*60)-(30*$row['timesdelay']*60);
}


if($absvalue < 0)
{
   $absvalue = abs($absvalue);
   echo $this->dateDifference($absvalue,"black");  
}
else
{
 echo "0 minutes";
      
}
}


function starttime_deviation($servicecall_id){
$sql="select * from ".TBL_SERVICECALL." where customerno=".$_SESSION['customerno']." and serviceid=".$servicecall_id."  and isdeleted=0" ;
 $sql.= " order by serviceid desc";
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);

if($row['starttime']=="0000-00-00 00:00:00"){
echo "0";
}else{
$absvalue= abs(strtotime($row['starttime']) - strtotime($row['timeslot_start']));
$val= strtotime($row['starttime']) - strtotime($row['timeslot_start']);
if($val>0){$col="red";}else{$col="green";}
echo $this->dateDifference($absvalue,$col );
}
}

function starttime_deviation_in_min($servicecall_id){
$sql="select * from ".TBL_SERVICECALL." where customerno=".$_SESSION['customerno']." and serviceid=".$servicecall_id."  and isdeleted=0" ;
 $sql.= " order by serviceid desc";
$result= $this->db->query($sql,__FILE__,__LINE__);
$row= $this->db->fetch_array($result);


$absvalue= abs(strtotime($row['starttime']) - strtotime($row['timeslot_start']));
$val= strtotime($row['starttime']) - strtotime($row['timeslot_start']);
if($val>0){$col="red";}else{$col="green";}
$minutes  = floor(($absvalue)/ 60); 

if($col=="red"){
echo "<div id='red'>-".$minutes."</div>";

}else if($col=="green"){
echo "<div id='green'>".$minutes."</div>";

}else{
echo $minutes;
}

}

function dateDifference($diff,$col)
  {
		 $str;
		  

            $years   = floor($diff / (365*60*60*24)); 
            $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));            
            $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
        
			$minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
            $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
            if($days > 0)
            {
                $str = $years." years";
            }
            else
            {
                if($hours > 0)
                {
                    $str.= $hours." hr ".$minutes." min ";
                }
                elseif($minutes > 0)
                {
                   $str.= $minutes." min ";                
                }
                else
                {
                   //$str.= $seconds." sec ago";                                
                }
            }
			if($col=="red"){
			echo "<div id='red'>".$str."</div>";
			
			}else if($col=="green"){
			echo "<div id='green'>".$str."</div>";
			
			}else{
			echo $str;
			}
			 
        } 



function get_servicelist($callid){
$sql="select * from ".TBL_SERVICEMAN." where servicecallid=".$callid." and customerno=".$_SESSION['customerno']." and isdeleted=0" ;
$add=0;
$result= $this->db->query($sql,__FILE__,__LINE__);
	while($row=$this->db->fetch_array($result))
	{
	$add+=$this->getservice_time($row['servicelistid']);
	} 
return  $add;
}


function get_endtime($servicecall_id,$date,$hs,$ampm,$emin){
if($ampm=="am"){
if($hs==12){$hs="00";}
$hs= ($hs);   
}  else if($ampm=="pm"){
$hs=($hs+12);
}

$min=$this->get_servicelist($servicecall_id);
$min=($min+$emin);
// 
$hours =floor($min/60);

// hours cal
if($hours>0)
{$H=$hours; $min=floor($min-$H*60);}
$I=$min;

$totalh=($hs+$H);
$endtimedate=$date." ".$totalh.":".$I.":00";
$update_array=array();
$update_array['timeslot_end'] = $endtimedate;

$this->db->update(TBL_SERVICECALL,$update_array,'serviceid',$servicecall_id);
}
 
function update_endtime_by_sid($servicecall_id,$datetime){
$min=$this->get_servicelist($servicecall_id);
$sql="Select * from ".TBL_SERVICECALL." where serviceid='".$servicecall_id."'";
$result= $this->db->query($sql,__FILE__,__LINE__);
$row=$this->db->fetch_array($result);
$starttime=$row['timeslot_start'];
		
$newendtimeslot = strtotime('+ '.$min.' minutes', strtotime($starttime));
$newendtimeslot = date("Y-m-d H:i:s",$newendtimeslot);


$update_array=array();
$update_array['timeslot_end'] =$newendtimeslot;
$update_array['expected_endtime'] =$newendtimeslot;

if($row['trackingno']=="")
{
$update_array['trackingno'] ="EMSR_".$_SESSION['customerno']."_".$row['serviceid'];
}

$this->db->update(TBL_SERVICECALL,$update_array,'serviceid',$servicecall_id);
}




function dashboard_servicecalls(){
?>
<table class="data" width="100%">
<thead>
<tr>
<th>tracking no.</th>
<th>clientname</th>
<th>phone</th>
<th>net amount</th>
<th>trackee</th>
<th>starttime</th>

<th>Expected endtime</th>
<th>status</th>


</tr>
</thead>
<tbody>

<?php
$x=1;
if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
$branch_clause=" and branchid=".$_SESSION['branchid'];
}
$sql="select * from ".TBL_SERVICECALL." where customerno=".$_SESSION['customerno']." ".$branch_clause." and  isdeleted=0 and timeslot_start >= DATE_SUB(NOW(), INTERVAL 24 HOUR) ";
$sql.=" order by timeslot_start desc ";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);
while($row= $this->db->fetch_array($resultpages))
{
?>
<tr>
<td><a href="servicecalls.php?index=View&amp;serviceid=<?php echo $row['serviceid'];?>"><?php echo $row['trackingno'];?></a></td>
<td><?php echo $row['clientname'];?></td>
<td><?php echo $row['phoneno'];?></td>
<td><?php  if(($row['totalbill']-$row['discount_amount']+$row['visiting_charges'])>0){echo ($row['totalbill']-$row['discount_amount']+$row['visiting_charges']);}else{ echo "0";}?></td>
<td><?php echo $this->get_trackee($row['trackeeid']);?></td>
<td><?php if($row['status']==0||$row['status']==1||$row['status']==2){ echo  date("d-M h:i A",strtotime($row['timeslot_start']));}else { echo  date("d-M h:i A",strtotime($row['starttime']));}?></td>
<td><?php 
if($row['status']==0||$row['status']==1||$row['status']==2||$row['status']==3||$row['status']==6||$row['status']==7 ||$row['status']==8){
	echo  date("d-M h:i A",strtotime($row['expected_endtime']));
}
else if($row['status']==4||$row['status']==5)
{
	echo date("d-M h:i A",strtotime($row['endtime']));
}


?></td>
<td><?php if($row['trackeeid']==0 ||$row['trackeeid']==-1){echo "UNASSIGNED" ;}else{
if($row['status']==0){echo "ASSIGNED";}else{ echo $this->getstatue($row['status']);}}  ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<?php
}




			function ReportRecord($appointmentdate='',$appointmentdatet='')
			{
					if($appointmentdate!="" && $appointmentdatet!="" )
					{
						if(strtotime($appointmentdatet)<strtotime($appointmentdate))
						{
							$appointmentdatet=$appointmentdate;
						}
						
						if(strtotime($appointmentdatet)==strtotime($appointmentdate))
						{
							$newendtimeslot = strtotime('+ 1 Day', strtotime($appointmentdate));
							$appointmentdatet = date("Y-m-d",$newendtimeslot);
						}else{
							$newendtimeslot = strtotime('+ 1 Day', strtotime($appointmentdatet));
							$appointmentdatet = date("Y-m-d",$newendtimeslot);
						}
					}
			
			
					if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
						$branch_clause=" and ".TBL_SERVICECALL.".branchid=".$_SESSION['branchid'];
					}
					
					$sql="select *,servicecall.branchid,servicecall.isdeleted AS scdeleted from ".TBL_SERVICECALL." 
							inner join ".TBL_CLIENT." on ".TBL_SERVICECALL.".clientid= ".TBL_CLIENT.".clientid 
							left outer join ".TBL_REMARKS." on ".TBL_SERVICECALL.".remarkid= ".TBL_REMARKS.".remarkid 
							where 1 and ".TBL_SERVICECALL.".status in (5,9) and ".TBL_SERVICECALL.".customerno=".$_SESSION['customerno']."".$branch_clause;
			
			
					if($appointmentdate)
					{
						$sql .= " and timeslot_start >='".$appointmentdate."'";
					}
					
					if($appointmentdatet)
					{
						$sql .= " and timeslot_start < '".$appointmentdatet."'";
					}
			
			
					$sql.=" order by serviceid desc ";
					if($appointmentdate!="" && $appointmentdatet!="")
                    {
                        $result= $this->db->query($sql,__FILE__,__LINE__);
                    }

			?>
			<div class="onecolumn">
				<div class="header">
					<span>Service Report</span>
				</div>
				<br class="clear"/>
			<?php $this->ReportBox(); ?>
				<div class="content">
			
				<div align="right"> 
					<a href="#" onclick="table2CSV(jQuery('#search_table_2'),'','Servicereport'); return false;">
						<img src="images/csv.png"  alt="Export to CSV"  title="Export to CSV" /> 
					</a> 
				</div>	
			
				<form id="form_data" name="form_data" action="" method="post">
								<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table_2">  
										<tr>
												<td width="2%">SNO.</td>
												<td width="10%">Date</td>
												<td width="10%">Day</td>
												<td width="5%">branch</td>
												<td width="5%">departtime</td>
												<td width="5%">Appointment start time</td>
												<td width="5%">Deviation from SST(min)</td>
												<td width="5%">Ideal service end time (min)</td>
												<td width="3%">Deviation from SET(min)</td>
												<td width="3%">wrapping time </td>
												<td width="3%">end time </td>
												<td width="3%">Total Delay (min)</td>
												<td width="5%">Client Name</td>
												<td width="5%">Phone</td>
												<td width="5%">Customer Type (Old or New)</td>
												<td width="3%">Trackee</td>
												<td width="5%">Travelling time (min)</td>
												<td width="5%">Feedback</td>
												<td width="5%">Service list</td>
												<td width="5%">Deleted from Original</td>
												<td width="5%">Upgrade Grade List</td>
												<td width="5%">DOWN Grade List </td>
												<td width="2%">Original AMT</td>
												<td width="2%">Original deleted AMT</td>
												<td width="5%">Upgrade AMT</td>
												<td width="5%">Down Grade AMT</td>
												<td width="2%">Discount</td>
												<td width="2%">Visiting charges</td>
												<td width="2%">net</td>
												<td width="5%">status</td>
										</tr>
									
												<?php 	
												   $x=1;                               
												while(@$row= $this->db->fetch_array($result))
												{
													
															?>
															<tr>
															
																	<td><?php echo $x;  if($_SESSION[user_name]=="elixir2"||$_SESSION[user_name]=="elixir1"){ echo "s#".$row['serviceid'];}?></td>
																	<td><?php echo date("d-m-y",strtotime($row['timeslot_start'])); ?></td>
																	<td><?php echo date("D",strtotime($row['timeslot_start'])); ?></td>
																	<td><?php echo $this->branch_by_id($row['branchid']); ?></td>
																	<td><?php echo date("h:i A",strtotime($row['departtime'])); ?></td>
																	<td><?php if($row["status"] == 9) echo "0"; else echo date("h:i A",strtotime($row['timeslot_start'])); ?></td>
																	<td><?php if($row["status"] == 9) echo "0"; else echo  $this->starttime_deviation_in_min($row['serviceid']);?> </td>
																	<td>
																		<?php 
																			$min=$this->get_servicelist($row['serviceid']);
																			$newendtimeslot = strtotime('+ '.$min.' minutes', strtotime($row['starttime']));
																			if($row["status"] == 9)
																				{ 
																					echo "0";
																				}else{
																					 echo $newendtimeslot = date(" h:i A",$newendtimeslot); 
																				}
																		?>
																	</td>
																	<td>
																		<?php 
																			$endt=strtotime($row['endtime']);
																			$min=$this->get_servicelist($row['serviceid']);	
																			$newendtimeslot = strtotime('+ '.$min.' minutes', strtotime($row['starttime']));
																			$dtime=floor(($newendtimeslot-$endt)/60);
																			if($dtime<0){
																			
																					if($row["status"] == 9)
																					{
																						echo "0"; 
																					}else{
																						echo "<div id='red'>".$dtime."</div>";
																					}
																			
																			}else if($dtime>0){
																				
																					if($row["status"] == 9)
																					{
																						echo "0";
																					} else{
																						echo "<div id='green'>".abs($dtime)."</div>";
																					}
																			
																			}else{
																				
																					if($row["status"] == 9)
																					{
																						echo "0";
																					}else{
																						echo abs($dtime);
																					}
																			}
																		?> 
																	</td>
																	<td><?php echo date("h:i A",strtotime($row['warning1'])); ?></td>
																	<td><?php echo date("h:i A",strtotime($row['endtime'])); ?></td>
																	<td><?php if($row["status"] == 9) echo "0"; else echo ($row['timesdelay']*WARNING2TIME);?> </td>
																	<td><?php echo $row['clientname']; ?></td>
																	<td><?php echo $row['phoneno']; ?></td>
																	<td><?php echo $this->old_new_client($row['clientid']); ?></td>
																	<td><?php echo $this->get_trackee($row['trackeeid']);?> </td>
																	<td>
																		<?php
																			$dev=strtotime($row['starttime'])-strtotime($row['departtime']);
																			if($row["status"] == 9)
																			{
																				echo "0";
																			}else{
																				echo  floor((abs($dev))/ 60);
																			} 
																	
																		?>
																	</td>
																	<td><?php if($row["status"] == 9) echo "N/A"; else echo $this->get_feedbackpoints($row['serviceid']);?> </td>
																	<td><?php echo $this->get_services($row['serviceid']);?></td>
																	<td><?php echo  implode(",",$this->get_services_by_service_id("DELETED_ORIGINAL","red",$row['serviceid'],"Deleted from Original","false","false"));?></td>
																	<td><?php echo  implode(",",$this->get_services_by_service_id("UPGRADES","green",$row['serviceid'],"Upgrades","false","false")); ?></td>
																	<td><?php echo  implode(",",$this->get_services_by_service_id("DOWNGRADES","red",$row['serviceid'],"Downgrades","false","false")); ?></td>
																	
																	
																	
																	
																	<td><?php echo $this->get_servicesamts($row['serviceid']);?> </td>
																	<td><?php echo $this->get_services_by_service_id("DELETED_ORIGINAL","red",$row['serviceid'],"Deleted from Original","false","true");?></td>
																	<td><?php echo $this->get_services_by_service_id("UPGRADES","green",$row['serviceid'],"Upgrades","false","true");?></td>
																	<td><?php echo $this->get_services_by_service_id("DOWNGRADES","red",$row['serviceid'],"Downgrades","false","true");?></td>
																	
																	
																	<td><?php if($row['discount_amount']=="" ){echo "0";}else{echo $row['discount_amount'];}?> </td>
																	<td><?php echo $row['visiting_charges'] ;?> </td>
																	<td> <?php if( ($row['totalbill']-$row['discount_amount']+$row['visiting_charges']) > 0){
																	
																	  echo  $row['totalbill'] - $row['discount_amount']+$row['visiting_charges'] ;}else{ echo "0";};?> </td>
																	<td ><?php if($row["status"] == 9) {$this->getstatue($row["status"]); }else {echo $row['remarkname'];}?></td>
															</tr>			
															<?php 
															$x++;
													                                                                        
												}
												?>
												<tr>
												<td colspan="23">Note: Negative value implies delay while positive value implies earlier than expected for deviation columns.</td>
												</tr>
										
									</table>
									<br class="clear"/>
				</form>
			
			
			
			
			</div>
			
			</div>
			
			<?php 
			
			}

	function old_new_client($clientid){
		if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0")
		{
			$branch_clause=" and ".TBL_SERVICECALL.".branchid=".$_SESSION['branchid'];
		}
		$sql="select count(*) from ".TBL_SERVICECALL."  where 1 and ".TBL_SERVICECALL.".customerno=".$_SESSION['customerno']." and clientid=".$clientid." ".$branch_clause;
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$l=0;
		$array=array();
		$row=$this->db->fetch_array($result);
		if($row[0]==1)
		{
			echo "new";
		}else{
			echo "old";
		}
	}

function ReportBox()
{
$FormName = "frm_add_client";
		$ControlNames=array("appointmentdate"=>array('appointmentdate',"''","Please Enter date","span_datefrom"),
							"appointmentdatet"=>array('appointmentdatet',"''","Please Enter date","span_dateto")
																							
							
							);
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;



?>
<div id="searchboxbg">
		<form action="servicecalls.php?index=servicereport" id="search_form" name="<?php echo $FormName?>" method="post">
				<table width="100%" class="data">
				
				<tr>
				<th>Apointment Date From: </th>
				<td><input   type="text" value="" id="appointmentdate" name="appointmentdate" class="datepicker1" />
				<span id="span_datefrom"></span>
				
				</td>
				
				</tr>
				<tr>
				<th>Apointment Date to: </th>
				<td><input   type="text" value="" id="appointmentdatet" name="appointmentdatet" class="datepicker3" />
				<span id="span_dateto"></span>
				</td>
				
				</tr>
				
				<tr>
				<th colspan="4"><input type="submit" name="search" id="search" value="Search"  onclick="return CheckAddNewsValidity();"/></th>
				</tr>	
				</table>
		</form>
</div>
<br class="clear"/>
<?php 
}
function FeedbackBox()
{
$FormName = "frm_add_client";
		$ControlNames=array("appointmentdate"=>array('appointmentdate',"''","Please Enter date","span_datefrom"),
							"appointmentdatet"=>array('appointmentdatet',"''","Please Enter date","span_dateto")
																							
							
							);
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
?>
<div id="searchboxbg">
		<form action="servicecalls.php?index=feedbackreport" id="search_form" name="<?php echo $FormName?>" method="post">
				<table width="100%" class="data">
				
				<tr>
				<th>Apointment Date From: </th>
				<td><input   type="text" value="" id="appointmentdate" name="appointmentdate" class="datepicker1" />
				<span id="span_datefrom"></span>
				</td>
				
				</tr>
				<tr>
				<th>Apointment Date to: </th>
				<td><input   type="text" value="" id="appointmentdatet" name="appointmentdatet" class="datepicker3" />
				<span id="span_dateto"></span>
				</td>
				
				</tr>
				
				<tr>
				<th colspan="4"><input type="submit" name="search" id="search" value="Search" onclick="return CheckAddNewsValidity();" /></th>
				</tr>	
				</table>
		</form>
</div>
<br class="clear"/>
<?php 
}
function customerBox()
{
$FormName = "frm_add_client";
		$ControlNames=array("d_from"=>array('d_from',"''","Please Enter date","span_datefrom"),
							"visit_type"=>array('visit_type',"''","Please type","span_dateto")
																							
							
							);
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
?>
<div id="searchboxbg">
		<form action="servicecalls.php?index=customerreport" id="search_form" name="<?php echo $FormName?>" method="post">
				<table width="100%" class="data">
				
				<tr>
				<th>First Visit: <input type="radio" name="visit_type" id="visit_type" value="fv" /></th>
				<th>Last Visit: <input type="radio" name="visit_type" id="visit_type" value="lv"  /><span id="span_dateto"></span></th>
				
				<th>From: </th>
				<td>
				<input type="text" class="datepicker1" name="d_from" id="d_from"  />
				<span id="span_datefrom"></span>
				</td>
				
				
				
				
				</tr>
				
				<tr>
				<th colspan="4"><input type="submit" name="search" id="search" value="Search" onclick="return CheckAddNewsValidity();" /></th>
				</tr>	
				</table>
		</form>
</div>
<br class="clear"/>
<?php 
}
function trackeeBox()
{

$FormName = "frm_add_client";
		$ControlNames=array("appointmentdate"=>array('appointmentdate',"''","Please Enter date","span_datefrom"),
							"appointmentdatet"=>array('appointmentdatet',"''","Please Enter date","span_dateto")
																							
							
							);
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
?>
<div id="searchboxbg">
		<form action="servicecalls.php?index=therapistreport" id="search_form"  name="<?php echo $FormName?>" method="post">
				<table width="100%" class="data">
				
				<tr>
				<th>Apointment Date From: </th>
				<td><input   type="text" value="" id="appointmentdate" name="appointmentdate" class="datepicker1" />
				<span id="span_datefrom"></span>
				</td>
				
				</tr>
				<tr>
				<th>Apointment Date to: </th>
				<td><input   type="text" value="" id="appointmentdatet" name="appointmentdatet" class="datepicker3" />
				<span id="span_dateto"></span>
				</td>
				
				</tr>
				
				<tr>
				<th colspan="4"><input type="submit" name="search" id="search" value="Search" onclick="return CheckAddNewsValidity();" /></th>
				</tr>	
				</table>
		</form>
</div>
<br class="clear"/>
<?php 
}

function get_upgrade($callid){
$sql="select * from ".TBL_SERVICEMAN." Inner join servicelist on ".TBL_SERVICELIST.".servicelistid=".TBL_SERVICEMAN.".servicelistid where ".TBL_SERVICEMAN.".servicecallid=".$callid." and ".TBL_SERVICEMAN.".customerno=".$_SESSION['customerno']."  and ".TBL_SERVICEMAN.".iscreatedby=1" ;
$result= $this->db->query($sql,__FILE__,__LINE__);
$l=0;
$array=array();
while($row=$this->db->fetch_array($result)){
$array[$l++]=$row['servicename'];

}

echo implode(",",$array);
}
function get_upgradeamts($callid){
$sql="select price from ".TBL_SERVICEMAN." Inner join servicelist on ".TBL_SERVICELIST.".servicelistid=".TBL_SERVICEMAN.".servicelistid where ".TBL_SERVICEMAN.".servicecallid=".$callid." and ".TBL_SERVICEMAN.".customerno=".$_SESSION['customerno']." and  ".TBL_SERVICEMAN.".isdeleted=0 and ".TBL_SERVICEMAN.".iseditedby=1" ;
$result= $this->db->query($sql,__FILE__,__LINE__);

$l=0;
$array=array();
while($row=$this->db->fetch_array($result)){
$arr+=$row['0'];

}
echo $arr;
}
function get_dpgrade($callid){
$sql="select ".TBL_SERVICEMAN.".servicelistid ,".TBL_SERVICEMAN.".iscreatedby , ".TBL_SERVICEMAN.".iseditedby , ".TBL_SERVICEMAN.".isdeleted , ".TBL_SERVICELIST.".servicename from ".TBL_SERVICEMAN." Inner join servicelist on ".TBL_SERVICELIST.".servicelistid=".TBL_SERVICEMAN.".servicelistid where ".TBL_SERVICEMAN.".servicecallid=".$callid." and ".TBL_SERVICEMAN.".customerno=".$_SESSION['customerno']." " ;
$result= $this->db->query($sql,__FILE__,__LINE__);
$l=0;
$array=array();
while($row=$this->db->fetch_array($result)){
if($row['iscreatedby']==0 && $row['iseditedby']==1 && $row['status']=9==1){
$array[$l++]=$row['servicename'];
}
if($row['iscreatedby']==1 && $row['status']=9){
$array[$l++]=$row['servicename'];
}


}
 echo implode(",",$array);
 
}

function get_dpgradeamts($callid){
$sql="select price from ".TBL_SERVICEMAN." Inner join servicelist on ".TBL_SERVICELIST.".servicelistid=".TBL_SERVICEMAN.".servicelistid where ".TBL_SERVICEMAN.".servicecallid=".$callid." and ".TBL_SERVICEMAN.".customerno=".$_SESSION['customerno']." and ".TBL_SERVICEMAN.".isdeleted=1 and iseditedby=1" ;
$result= $this->db->query($sql,__FILE__,__LINE__);

$l=0;
$array=array();
while($row=$this->db->fetch_array($result)){
$arr+=$row['0'];

}
echo $arr;
}


function get_services($callid){
 $sql="select ".TBL_SERVICEMAN.".servicelistid ,".TBL_SERVICEMAN.".iscreatedby , ".TBL_SERVICEMAN.".iseditedby , ".TBL_SERVICEMAN.".isdeleted , ".TBL_SERVICELIST.".servicename from ".TBL_SERVICEMAN." Inner join servicelist on ".TBL_SERVICELIST.".servicelistid=".TBL_SERVICEMAN.".servicelistid where ".TBL_SERVICEMAN.".servicecallid=".$callid." and ".TBL_SERVICEMAN.".customerno=".$_SESSION['customerno']." " ;
$result= $this->db->query($sql,__FILE__,__LINE__);

$l=0;
$array=array();
while($row=$this->db->fetch_array($result)){
if($row['iscreatedby']==0 && $row['iseditedby']==0 && $row['isdeleted']==0){
$array[$l++]=$row['servicename'];
}
if($row['iscreatedby']==0 && $row['iseditedby']==1 && $row['isdeleted']==1){
$array[$l++]=$row['servicename'];
}
}

 echo implode(",",$array);
}


function get_servicesamts($callid){
$sql="select ".TBL_SERVICEMAN.".servicelistid ,".TBL_SERVICEMAN.".iscreatedby , ".TBL_SERVICEMAN.".iseditedby , ".TBL_SERVICEMAN.".isdeleted , ".TBL_SERVICELIST.".price  from ".TBL_SERVICEMAN." Inner join servicelist on ".TBL_SERVICELIST.".servicelistid=".TBL_SERVICEMAN.".servicelistid where ".TBL_SERVICEMAN.".servicecallid=".$callid." and ".TBL_SERVICEMAN.".customerno=".$_SESSION['customerno']." " ;
$result= $this->db->query($sql,__FILE__,__LINE__);

$l=0;

while($row=$this->db->fetch_array($result)){
if($row['iscreatedby']==0 && $row['iseditedby']==0 && $row['isdeleted']==0){
$arr+=$row['price'];
}
if($row['iscreatedby']==0 && $row['iseditedby']==1 && $row['isdeleted']==1){
$arr+=$row['price'];
}

}
echo $arr;
}

function get_feedbackpoints($serviceid){
	$sql="select * from ".TBL_FEEDBACKRESULTS." inner join ".TBL_FEEDBACKOPTIONS."
	on  ".TBL_FEEDBACKOPTIONS.".feedbackoptionid=".TBL_FEEDBACKRESULTS.".feedbackoptionid  
	where ".TBL_FEEDBACKRESULTS.".feedbackqid=6 and ".TBL_FEEDBACKRESULTS.".serviceid=".$serviceid."" ;
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row=$this->db->fetch_array($result);
	echo $row['optionname'];
}

function get_remark($remarkid){
	$sql="select * from ".TBL_REMARKS." where  remarkid=".$remarkid."" ;
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row=$this->db->fetch_array($result);
	echo $row['remarkname'];
}


function master_screen_json()
{
	$status=false;
	$marray= array();
	$slist=array();
	$tlist=array();
	
	if(isset($_SESSION['customerno'])){$status=true;}
	
	
	if(isset($_SESSION['customerno'])){
	if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
		$branch_clause=" and ".TBL_SERVICECALL.".branchid=".$_SESSION['branchid'];
		}
		$sql_service="select serviceid,clientname,".TBL_SERVICECALL.".trackeeid,status ,".TBL_TRACKEE.".tname , ".TBL_FLOW.".name,".TBL_SERVICECALL.".phoneno,devicelat,devicelong
		
		 from ".TBL_SERVICECALL."
		left outer join ".TBL_FLOW." on ".TBL_SERVICECALL.".status= ".TBL_FLOW.".serviceflowid
		left outer join ".TBL_TRACKEE." on ".TBL_SERVICECALL.".trackeeid= ".TBL_TRACKEE.".trackeeid
		left outer join ".TBL_DEVICE." on ".TBL_SERVICECALL.".trackeeid= ".TBL_DEVICE.".trackeeid
		
		 where  ".TBL_SERVICECALL.".customerno=".$_SESSION['customerno']." and ".TBL_SERVICECALL.".isdeleted=0 ".$branch_clause." and status in (0,1,2,3,4,6,7,8) and timeslot_start > '".date("Y-m-d")." 00:00:00' and  timeslot_start <= '".date("Y-m")."-".(date("d")+1)." 00:00:00'  ".$branch_clause." order by timeslot_start asc  ";
		$result= $this->db->query($sql_service,__FILE__,__LINE__);
		$i=0;
		while($row=$this->db->fetch_row($result)){
		//echo "<pre>";
		//print_r($row);
				// data validations for null values
				if($row['3']==0){$sstatus="OPEN";}else{$sstatus=$row['5'];}
				if($row['2']==0){$strackee=null;}else{$strackee=$row['4'];}
				// push data in array
				$slist[$i]['status']=$sstatus;
				$slist[$i]['sid']=$row['0'];
				$slist[$i]['trackee']=$strackee;
				$slist[$i]['clientname']=$row['1'];
				$slist[$i]['phone']=$row['6'];
				$slist[$i]['trackee_id']=$row['2'];
				$slist[$i]['lat']=$row['7'];
				$slist[$i]['long']=$row['8'];
				
			
			$i++;
			
		}
	if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
	$branch_clause=" and ".TBL_TRACKEE.".branchid=".$_SESSION['branchid'];
	}
	$slq="Select
	".TBL_TRACKEE.".trackeeid,".TBL_TRACKEE.".tname,devicelat,devicelong,lastupdated
	from ".TBL_DEVICE."  
	inner join ".TBL_TRACKEE." on ".TBL_DEVICE.".trackeeid= ".TBL_TRACKEE.".trackeeid 
	where ".TBL_TRACKEE.".isdeleted=0 and  ".TBL_DEVICE.".customerno=".$_SESSION['customerno']." and devicelat!=0 and devicelong!=0  ".$branch_clause;
	$result= $this->db->query($slq,__FILE__,__LINE__);
	$carray=array();
	$i=0;
		while($row=$this->db->fetch_row($result)){
			//print_r($row);
			$carray=$this->mtrackee_status_by_sid($row['0']);
			$tlist[$i]['trackeeid']=$row[0];
			$tlist[$i]['trackee']=$row[1];
			$tlist[$i]['lat']=$row[2];
			$tlist[$i]['long']=$row[3];
			$lup_str="";
			$diff=abs(strtotime($row[4])-strtotime(date("Y-m-d H:i:s")));
			$years   = floor($diff / (365*60*60*24)); 
            $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));            
            $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
        	$minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
            $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
			if($years>0){
			$lup_str.=$years." years "; 
			}
			if($months>0){
			$lup_str.=$years." months ";
			}
			if($days>0){
			$lup_str.=$days." days ";
			}
			if($hours>0){
			$lup_str.=$hours." hours ";
			}
			if($minutes>0){
			$lup_str.=$minutes." min ";
			}
			if($seconds>0){
			$lup_str.=$seconds." sec ";
			}
			$lup_str.=" ago.";
			
			//$tlist[$i]['lastupdated']=$row[4];
			$tlist[$i]['lastupdated']=$lup_str;
			$tlist[$i]['status']=$carray['status'];
			$tlist[$i]['clientname']=$carray['clientname'];
			
			$i++;
		}
	
	// value assignment of main array 
	}
        if(isset($_SESSION['customerno'])){
        $arr_catch=$this->json_get_notification_async();    
        }
	
	$marray['status']=$status;
	$marray['servicelist']=$slist;
	$marray['tlist']=$tlist;
	$marray['panik']=$arr_catch[1];
	$marray['notif']=null;
	
	//=$arr_catch[0];
	
	// encode jason 
	echo json_encode($marray);

}




		function mtrackee_status_by_sid($tid)
		{
		
			if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
			$branch_clause=" and ".TBL_SERVICECALL.".branchid=".$_SESSION['branchid'];
			}
			 $sql="select * from ".TBL_SERVICECALL." 
					
					left outer join ".TBL_FLOW." on ".TBL_SERVICECALL.".status= ".TBL_FLOW.".serviceflowid
					
					where  trackeeid=".$tid." and customerno=".$_SESSION['customerno']." and isdeleted=0 ".$branch_clause." 
					and status in (3,4,6,7,8) and timeslot_start > '".date("Y-m-d")." 00:00:00' and  
					timeslot_start <= '".date("Y-m")."-".(date("d")+1)." 00:00:00' ";
			
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row=$this->db->fetch_array($result);
			//print_r($row);
			if($row['status']==0){$sstatus="OPEN";}else{$sstatus=$row['status'];}
			if($row['clientname']==""){$cname="null";}else{$cname=$row['clientname'];}
			$r=array();
			$r['clientname']=$cname;
			$r['status']=$sstatus;
			
			
			return $r;
		}


	   function json_get_notification_async(){
					 
				$sqlupdate="select * from ".TBL_NOTIF." left outer join  ".TBL_FLOW."  
					on ".TBL_FLOW.".serviceflowid =".TBL_NOTIF.".status  where customerno=".$_SESSION['customerno']."   ";   
					 $record = $this->db->query($sqlupdate,__FILE__,__LINE__);
					 $x=0;
					 $y=0;
					 $res=array();
					 $p_msg=array();
					 while($row2 = $this->db->fetch_row($record))
					 {
						//print_r($row2);
						if($row2['1']==10){
						
							if($row2['6']==0){
							$p_msg[$y]['msg']=$row2['3'];
							$p_msg[$y]['msg_type']=$row2['8'];
							$p_msg[$y]['msg_time']="<span id='fl'>".date("h:i:s A ",strtotime($row2['5']))."</span>";
							$p_msg[$y]['id']=$row2['0'];
							$y++;
							
							}
						}else{
						
							
							$res[$x]['msg']=$row2['3'];
							$res[$x]['msg_type']=$row2['8'];
							$res[$x]['id']=$row2['0'];
							$x++;
							$this->isupdated($row2['0']);
							
							
						}
						 
							 
					 }
					
					 if(count($p_msg)==0){$p_msg=null;}
					 $ret_a[0]=$res;
					 $ret_a[1]=$p_msg;
					
					 
					   return $ret_a;
										 
				   }
					
			
		function isupdated($notifid){
			$sql="UPDATE ".TBL_NOTIF." SET isshown=1 WHERE customerno=".$_SESSION['customerno']." and notifid=".$notifid."";
			$resultpages= $this->db->query($sql,__FILE__,__LINE__);
		}
		
		function isupdatedpanic(){
			$sql="UPDATE ".TBL_NOTIF." SET isshown=1 WHERE customerno=".$_SESSION['customerno']." and status=10";
			$resultpages= $this->db->query($sql,__FILE__,__LINE__);
		}
		
		function master_screen_popup_data($servicecallid,$tid,$name){
				if($servicecallid!="" && $tid!=""){
			
						 $name." ";
						// $this->calculate_status($tid) ;
						$ava= $this->calculate_ava($tid);
							if($ava!="")
								{
								echo  "<br>Available in(mins) : ".$ava;
								}
						if($_SESSION['branchid']!="all" &&  $_SESSION['branchid']!="0"){
						$branch_clause=" and ".TBL_SERVICECALL.".branchid=".$_SESSION['branchid'];
						}
						$sql_service="select serviceid,clientname,name,timeslot_start,totalbill,expected_endtime,status,starttime from ".TBL_SERVICECALL." 
						left outer join ".TBL_FLOW." on ".TBL_SERVICECALL.".status= ".TBL_FLOW.".serviceflowid where trackeeid=".$tid." and  ".TBL_SERVICECALL.".customerno=".$_SESSION['customerno']."  
						and ".TBL_SERVICECALL.".isdeleted=0 
						".$branch_clause."
						 and timeslot_start > '".date("Y-m-d")." 00:00:00' and  
						timeslot_start <= '".date("Y-m")."-".(date("d")+1)." 00:00:00' order by status asc  ";
						
						$result= $this->db->query($sql_service,__FILE__,__LINE__);
						$i=0;
						
						
						?>
						
						<table  style="text-align:center; font-size:12px; text-transform:capitalize;" width="100%">
						<tr class="thead" >
						<th >Client Name</th>
						<th>Bill</th>
						<th>Start time</th>
						<th>End time</th>
						<th>Status</th>
						</tr>
						
						<?php 
						while($row=$this->db->fetch_array($result)){
								if($row['status']==0){$sstatus="OPEN";}else{$sstatus=$row['name'];}
							
								?>
								<tr>
								<td style="text-align:left;">
								<a style="color:#000000; text-decoration:none;" target="_blank" href="servicecalls.php?index=View&amp;serviceid=<?php echo $row['serviceid'];?>">
								<?php echo $row['clientname'];?>								</a>								</td>
								<td><?php echo $row['totalbill'];?></td>
								<td><?php  if($row['status']==7||$row['status']==6||$row['status']==3||$row['status']==4 ||$row['status']==5 ||$row['status']==8)
									{ echo date("h:i A",strtotime($row['starttime'])) ;}else{echo date("h:i A",strtotime($row['timeslot_start'])) ;}?>
								</td>
								<td><?php if($row['status']>2){ echo date("h:i A",strtotime($row['expected_endtime']));}else{echo "0";} ?></td>
								<td><?php echo $sstatus ;?></td>
								</tr>
								<?php 
						}
						?>
						
						</table>
						
						<?php
						
						$this->trackee_history($servicecallid);
			
				// else for data not valid
				}else{
		
			echo "no data found";
		
			}
		}
		
		function trackee_history($sid){
		if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
		$branch_clause=" and ".TBL_SERVICECALL.".branchid=".$_SESSION['branchid'];
		}
		 $sql="select * from  ".TBL_SERVICECALL." where customerno=".$_SESSION['customerno']." ".$branch_clause."  and clientid =
		 (select clientid from  ".TBL_SERVICECALL." where serviceid=".$sid." ) order by serviceid desc limit 0,5 ";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		?>
	
		<table  style="text-align:center; font-size:12px; text-transform:capitalize;" width="100%">
						<tr class="thead" >
							<th >Date</th>
							<th>Amt</th>
						</tr>
		<?php
	
		while($row=$this->db->fetch_array($result)){
		?>
		<tr>
		<td style="text-align:none;" width="50%"><?php echo date("d-m-Y",strtotime($row['timeslot_start']));?></td>
		<td  width="50%"><?php echo $row['totalbill'];?></td>
		</tr>
		<?php
		}
		?>
		</table>
		
		<?php
		}

		//customer report
		function customer_reports($visit_type,$d_from,$search)
		{
			?>
			<div class="onecolumn">
			<div class="header">
			<span>Client Report</span>
			</div>
			<br class="clear"/>
			
			<?php $this->customerBox(); ?>
			<?php 
		if($search=="Search"){
			if($_SESSION['branchid']!="all" &&  $_SESSION['branchid']!="0"){
				$branch_clause=" and ".TBL_CLIENT.".branchid=".$_SESSION['branchid'];
			}
			$slq="select * from ".TBL_CLIENT." where customerno=".$_SESSION['customerno']." ".$branch_clause."  and isdeleted=0  ";
			if($visit_type=="fv" && $d_from!="" )
			{
				$slq.=" and first_visit>='".$d_from." 00:00:00' ";
			}else if($visit_type=="lv" && $d_from!=""){
				$slq.=" and last_visit>='".$d_from." 00:00:00' ";
			}
		$slq.=" and has_visit=1 order by clientid asc  ";
		$result= $this->db->query($slq,__FILE__,__LINE__);
		$x=1;
		?>
		<div class="content">
		<div align="right"> 
		<a href="#" onclick="table2CSV(jQuery('#search_table_2'),'','Customer'); return false;">
		<img src="images/csv.png"  alt="Export to CSV"  title="Export to CSV" /> 
		</a> 
		</div>	

				<table id="search_table_2" class="data" width="95%" style="font-size:92%">
				<tr>
				<td>Sno</td>
				<td>branch</td>
				<td>Client Name</td>
				<td>Phone No</td>
				<td>email</td>
				<td>address1</td>
				<td>address2</td>
				
				<td>city</td>
				<td>first visit</td>
				<td>second last visit</td>
				<td>second last therapist</td>
				<td>last visit</td>
				<td>last therapist</td>
				<td>days since last service(days)</td>
				
				<td>total visits</td>
				<td>ave freq(days)</td>
				<td>average spent(Net Amount)</td>
				</tr>
						<?php while($row=$this->db->fetch_array($result)){
						 $ct_arr=$this->customer_servicecall_data($row['clientid'],date("m",strtotime($d_from." 00:00:00")),date("Y",strtotime($d_from." 00:00:00")));
						
					
							if($ct_arr['data']!=0){
									?>
									<tr>
										<td><?php echo $x++;  echo "#".$ct_arr['data'];?></td>
										<td width="2%"><?php echo  $this->branch_by_id($row['branchid']); ?></td>
										<td width="2%"><?php echo  $row['clientname']; ?></td>
										<td><?php echo  $row['phoneno']; ?></td>
										<td><?php echo  $row['email']; ?></td>
										<td><?php echo  $row['add1']; ?></td>
										<td><?php echo  $row['add2']; ?></td>
										<td><?php echo  $row['city']; ?></td>
										<td><?php echo  $row['first_visit']; ?></td>
										<td><?php echo $ct_arr['secondlast_visit']; ?></td>
										<td><?php echo $ct_arr['secondlast_trackee']; ?></td>
										<td><?php echo  $row['last_visit']; ?></td>
										<td><?php echo $ct_arr['lasttrackee']; ?></td>
										<td><?php echo $ct_arr['lastcalldays']; ?></td>
										<td><?php echo $ct_arr['call']; ?></td>
										<?php 
										$diff=strtotime($row['last_visit'])-strtotime($row['first_visit']);
										if($ct_arr['data']!=0){
										$days= abs(($diff)/ (60*60*24))/$ct_arr['data'];
										}
										
										?>
										<td><?php echo round($days,1); ?></td>
										<td><?php echo round($ct_arr['average'],2); ?></td>
										
									</tr>
									<?php 
							} 
						}
						?>
		  </table>
		<br class="clear"/>
		</div>
		</div>
		<?php
		} 
		}
		function get_calls_for_cusromer($clientid,$month,$year){
		$sql1="SELECT *  from ".TBL_SERVICECALL." left outer  join ".TBL_TRACKEE."  on ".TBL_TRACKEE.".trackeeid= ".TBL_SERVICECALL.".trackeeid 
			 where  ".TBL_SERVICECALL.".customerno=".$_SESSION['customerno']." and clientid=".$clientid." ".$branch_clause;
			
			$count=0;
			$rt_array1=array();
			$sql1.=" and `createdon` between  '".$year."-".($month-1)."-01 00:00:00' and  '".$year."-".($month-1)."-31 23:59:59'";
			$sql1.=" and status=5 order by serviceid desc ";
		$result1= $this->db->query($sql1,__FILE__,__LINE__);	
		while($row1=$this->db->fetch_array($result1))
			 	{
				$count++;
						if($count==1){
						$rt_array1['lastvisit']=$row1['endtime'];
						$rt_array1['lasttrackee']=$row1['tname'];
						
						}
				
				}
				return $rt_array1;
		}
			

		function customer_servicecall_data($clientid,$month,$year)
		{
			if(isset($clientid) && $clientid!="")
			{
				if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0")
				{
					$branch_clause=" and ".TBL_SERVICECALL.".branchid=".$_SESSION['branchid'];
				}
				
				$sql="SELECT *  from ".TBL_SERVICECALL." left outer  join ".TBL_TRACKEE."  on ".TBL_TRACKEE.".trackeeid= ".TBL_SERVICECALL.".trackeeid 
				where  ".TBL_SERVICECALL.".customerno=".$_SESSION['customerno']." and clientid=".$clientid." ".$branch_clause;
				//$sql.=" and `createdon` between  '".$year."-".($month)."-01 00:00:00' and  '".$year."-".($month)."-31 23:59:59'";
				 $sql.=" and status=5 order by serviceid desc ";
				$result= $this->db->query($sql,__FILE__,__LINE__);	
				$rt_array=array();
				$sum=0;
				$count=0;
				// for median condition 1
				$c1=0;
				$c2=0;
				$c3=0;
			
			// for condition 
			
			
			 while($row=$this->db->fetch_array($result))
			 	{
				
				// counter for call for cileint id , is zero when no calls 
				$count++;
				if($count==1)
				{
					// last month details
					$rt_array['lastvisit']=$row['endtime'];
					$rt_array['lasttrackee']=$row['tname'];
				}
					$net1=0;
					$net1=$row['totalbill']-$row['discount_amount']+$row['visiting_charges'];
					if($net1>00){$sum+=$net1;}
					
					
					$rt_array['firstvisit']=$row['starttime'];
				
				if($count==2){
					$catch_array=$this->get_calls_for_cusromer($clientid,$month,$year);
					$rt_array['secondlast_visit']=$row['closedon'];
					 	$rt_array['secondlast_trackee']=$row['tname'];
					
				}
				
				$mtime=date("H:i:s",strtotime($row['expected_endtime']));
				if(strtotime($mtime)<=strtotime("12:00:00")){
					$c1++;
				}else if(strtotime($mtime)>strtotime("12:00:00") && strtotime($mtime)<strtotime("17:00:00")){
					$c2++;
				}else if(strtotime($mtime)>strtotime("17:00:00")) {
					$c3++;
				}
					$diff=strtotime($row['endtime'])-strtotime(date("Y-m-d")." 00:00:00");
					$days+= abs(floor(($diff)/ (60*60*24)));
					// date calculation 
				}
				
				if($count!=0){
					$maxval=max($c3,$c2,$c1);
					if($maxval==$c1){
						$rt_array['median']="before 12";
					}else if($maxval==$c2){
						$rt_array['median']="in between (12,5)";
					}else if($maxval==$c3){
						$rt_array['median']="after 5";;
					}
					$diff2=strtotime($rt_array['lastvisit'])-strtotime(date("Y-m-d")." 00:00:00");
					$ldays= abs(floor(($diff2)/ (60*60*24)));
					$rt_array['lastcalldays']=$ldays;
				}
				$rt_array['sum']=$sum;
				$rt_array['call']=$count;
				if($sum>0){
					$rt_array['average']=$sum/$count;
				}
				if($rt_array['average']==""){
					$rt_array['average']=0;
				}
				if($rt_array['lastvisit']==""){
					$rt_array['lastvisit']="NA";
				}
				
				if($rt_array['firstvisit']==""){
					$rt_array['firstvisit']="NA";
				}
				if($rt_array['firstvisit']!="" && $rt_array['lastvisit']!=""){
				$diff=strtotime($rt_array['lastvisit'])-strtotime($rt_array['firstvisit']);
				$days= abs(($diff)/ (60*60*24));
				}
				if($days!=0 && $count!=0){
					 $rt_array['freqdays']=$days;
				}
				$rt_array['data']=$count;
				return $rt_array;
			}
		 
		
	}


	function trackeereports($appointmentdate,$appointmentdatet){
	if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
		$branch_clause=" and ".TBL_TRACKEE.".branchid=".$_SESSION['branchid'];
	}
	$slq="select * from ".TBL_TRACKEE." where customerno=".$_SESSION['customerno']."  and  isdeleted=0 ".$branch_clause." order by trackeeid ";
	if($appointmentdate!="" && $appointmentdatet!="")
	{
		$result= $this->db->query($slq,__FILE__,__LINE__);
	}
	$x=1;
	?>
		<div class="onecolumn">
		<div class="header">
		<span>Therapist Report</span>
		</div>
		<br class="clear"/>
		<br class="clear"/>
		<?php $this->trackeeBox(); ?>
		<div class="content">
		<div align="right"> 
		<a href="#" onclick="table2CSV(jQuery('#search_table_2'),'','Therapistreport'); return false;">
		<img src="images/csv.png"  alt="Export to CSV"  title="Export to CSV" /> 
		</a> 
		</div>	

				<table id="search_table_2" class="data" width="95%" style="font-size:92%">
				<?php 
				
				if(strtotime($appointmentdate.'00:00:00')>strtotime($appointmentdatet.'00:00:00'))
				{
				$appointmentdatet=$appointmentdate;
				}
				if($appointmentdate!= "" && $appointmentdatet !=""  )
				{		
				?>
				<tr>
					<td  colspan="19">Date from : <?php echo $appointmentdate; ?>
					Date to : <?php echo $appointmentdatet; ?>
					</td>
				</tr>
				<?php 
				}
				?>
					<tr>
						<td>Sno</td>
						<td>Name</td>
						<td>branch</td>
						<td>Total Appointments</td>
						<td>Original downgraded Appointments</td>
						<td>Upgraded Appointments</td>
						<td>Downgraded Appointments </td>
						<td>Original Amount</td>
						<td>Original Downgrade Amount</td>
						<td>Upgrade Amount</td>
						<td>Downgrade Amount</td>
						<td>No. of times discount given</td>
						<td>Total Discount </td>
						<td>Total Revenue Earned</td>
						
						<!--<td>Net Amount </td>-->
						<td>Highest Bill Amount</td>
						<td>Highest Original Downgrade Amount</td>
						<td>Highest Upgrade Amount</td>
						<td>Highest Downgrade Amount</td>
						<td>Appointments started on or before time</td>
						<td>Avg. Deviation from Starting time</td>
						<td>Appointments finished on or before time</td>
						<td>Avg. service time deviation</td>
						
						<td>Avg. Rating (0-3) from customer</td>
					</tr>
					<?php while(@$row=$this->db->fetch_array($result)){ ?>
					<tr>
						<td><?php echo $x++; ?></td>
						<td><?php echo $row['tname']; ?></td>
						<td><?php echo $this->branch_by_id($row['branchid']); ?></td>
							<?php $cat_array=$this->trackeereports_servicecall_data($row['trackeeid'],$appointmentdate,$appointmentdatet); ?>
						<td><?php  echo $cat_array['count']; ?></td>
						<td><?php  echo $cat_array['odflag']; ?></td>
						<td><?php  echo $cat_array['uflag']; ?></td>
						<td><?php  echo $cat_array['dflag']; ?></td>
						<td><?php  echo $cat_array['wup']; ?></td>
						<td><?php  echo $cat_array['odgraded_amount']; ?></td>
						<td><?php  echo $cat_array['upgraded_amount']; ?></td>
						<td><?php  echo $cat_array['dgraded_amount']; ?></td>
						<td><?php  echo $cat_array['discount_counter']; ?></td>
						<td><?php  echo $cat_array['discount']; ?></td>
						<td><?php  echo $cat_array['total_revenue']; ?></td>
						<td><?php  echo $cat_array['highest_bill_amount']; ?></td>
						
						<td><?php  echo $cat_array['highest_original_down_grade_amount']; ?></td>
						<td><?php  echo $cat_array['highest_upgrade_amount']; ?></td>
						<td><?php  echo $cat_array['highest_down_grade_amount']; ?></td>
						<td><?php  echo $cat_array['count_ontime_start']; ?></td>
						<td><?php  echo intval($cat_array['average_deviation_start']); ?></td>
						<td><?php  echo $cat_array['count_on_or_before_time']; ?></td>
						<td><?php  echo intval($cat_array['average_deviation_end']); ?></td>
							<?php  $catcfeedback=$cat_array['customer_feedback']; ?>
						<td><?php  echo round($cat_array['feedback_rating_avg'],1); ?></td>
					</tr>
					
					
					<?php }?>
					<tr>
						<td colspan="19" >
						Note: Call deviation are only calculated for delays
						</td>
					
					</tr>
					<tr>
						<td colspan="19">
						Note: Only the calls which have been closed are considered for this report 
						</td>
					</tr>
				</table>
		<br class="clear"/>
		</div>
		</div>
		<?php
		} 
	
		function trackeereports_servicecall_data($trackeeid,$appointmentdate,$appointmentdatet){
		if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
		$branch_clause=" and ".TBL_SERVICECALL.".branchid=".$_SESSION['branchid'];
		}
		  $slq="select * from ".TBL_SERVICECALL." where customerno=".$_SESSION['customerno']." and isdeleted=0 and not totalbill=0 and status=5 and trackeeid=".$trackeeid."  ".$branch_clause;
		
		if(strtotime($appointmentdate.'00:00:00')>strtotime($appointmentdatet.'00:00:00'))
		{
		$appointmentdatet=$appointmentdate;
		}
		$slq.=" and  createdon between '".$appointmentdate." 00:00:00' and '".$appointmentdatet." 23:59:59' ";
		
		
		  $slq.=" order by serviceid asc  " ;
		
		
		$result= $this->db->query($slq,__FILE__,__LINE__);
		$count=0;
		$isupgrade_count=0;
		$isupgraded_amount=0;
		$isdowngrade_count=0;
		$isdowngrade_amount=0;
		$totalgrand=0;
		$count_ontime_start=0;
		$average_deviation_start=0;
		$average_deviation_start_count=0;
		$count_on_or_before_time=0;
		$average_deviation_end=0;
		$average_deviation_end_count=0;
		$count_callback_by_manager=0;
		$dicsount_counter=0;
		$discount=0;	
		$feedback_array=array();
		$highest_bill_amount=array();
		$highest_upgrade_amount =array();
		$highest_down_grade_amount=array();
		$highest_original_down_grade_amount=array();
		$feedback_rating_avg=0;
		$feedback_avg_counter=0;
			$retarray=array();
			while($row=$this->db->fetch_array($result)){ 
			$catch_slarray=$this->trackeereport_manage_data($row['serviceid']);
			
			$upgrade_without_ud+=$catch_slarray['rev_wup'];
				// condition for is original deleted 
				
				if($catch_slarray['isoriginaldowngraded']=="true"){
				$isoriginaldowngrade_count++;
				$isoriginaldowngrade+=$catch_slarray['isoriginaldowngraded_amount'];
				array_push($highest_original_down_grade_amount,$catch_slarray['isoriginaldowngraded_amount']);
				}
				
				
				// condition of upgrads
				if($catch_slarray['isupgraded']=="true"){
				$isupgrade_count++;
				$isupgraded_amount+=$catch_slarray['isupgraded_amount'];
				array_push($highest_upgrade_amount,$catch_slarray['isupgraded_amount']);
				}
				// condition of Downgrads
				if($catch_slarray['isdowngraded']=="true"){
				$isdowngrade_count++;
				$isdowngrade+=$catch_slarray['isdowngraded_amount'];
				array_push($highest_down_grade_amount,$catch_slarray['isdowngraded_amount']);
				}
				// condition of appointment started on time 
				if(strtotime($row['timeslot_start'])>=strtotime($row['starttime'])){
				$count_ontime_start++;
				}
				// average deviation start time 
			
				if((strtotime($row['timeslot_start']) )< (strtotime($row['starttime']))){
					if(strtotime($row['starttime'])-strtotime($row['timeslot_start'])>0){
						$average_deviation_start_count+=1;
						
						$avg+=(strtotime($row['starttime'])-strtotime($row['timeslot_start']))/60;
							if(($avg > 0) && ($average_deviation_start_count >0)){
							$average_deviation_start=$avg/$average_deviation_start_count;
							
							}
					}
				}
				
				// code for expected end time 
				$min=$this->get_servicelist($row['serviceid']);
				$starttime=$row['starttime'];
				$newendtimeslot = strtotime('+ '.$min.' minutes', strtotime($starttime));
				$newendtimeslot = date("Y-m-d H:i:s",$newendtimeslot);
				// code for expected end time 
				
				// count for on or before time 
				if(strtotime($newendtimeslot)>=strtotime($row['endtime'])){
				$count_on_or_before_time++;
				
				}
				//Avg. service time deviation avg (+(expected_endtime(cal)<end time)) =average_deviation_end
				
				if(strtotime($newendtimeslot)<strtotime($row['endtime'])){
				
					if((strtotime($row['endtime'])-strtotime($newendtimeslot))>0){
					$average_deviation_end_count++;
					$avg_end+=abs((strtotime($newendtimeslot)-strtotime($row['endtime']))/60);
					$average_deviation_end=$avg_end/$average_deviation_end_count;
					}
				}
				//$feedbackarray=array();
				  $feedbackarray=$this->trackeereport_feedback_q1_data($row['serviceid']);
				// print_r($feedbackarray);
				if($feedbackarray!=0){
				//echo "true";
				  $feedback_rating_avg+=intval($feedbackarray);
				 $feedback_avg_counter++;
				}
				
				// callback by manager
				
				if($this->trackeereport_feedback_q2_data($row['serviceid'])==33){
				$count_callback_by_manager++;
				}
				
			$discount+=	$row['discount_amount'];
			if($row['discount_amount']!=0){$discount_counter++;}
			$totalgrand+=$row['totalbill'];
			array_push($highest_bill_amount,$row['totalbill']);
			$count++;
			}
		
		$retarray['count']= $count;
		$retarray['wup']= $upgrade_without_ud;
		$retarray['uflag']= $isupgrade_count;
		$retarray['upgraded_amount']=$isupgraded_amount;
		// new column original deteied 
		
		$retarray['odflag']= $isoriginaldowngrade_count;
		$retarray['odgraded_amount']=$isoriginaldowngrade;
		
		
		// new column original deleted 
	
	
	
		$retarray['dflag']= $isdowngrade_count;
		$retarray['dgraded_amount']=$isdowngrade;
		$retarray['total_revenue']=$totalgrand;
		$retarray['count_ontime_start']=$count_ontime_start;
		$retarray['average_deviation_start']=$average_deviation_start;
		$retarray['count_on_or_before_time']=$count_on_or_before_time;
		$retarray['average_deviation_end']=$average_deviation_end;
		$retarray['average_deviation_end']=$average_deviation_end;
		$retarray['customer_feedback']=$feedback_array;
		$retarray['count_callback_by_manager']=$count_callback_by_manager;
		$retarray['discount']=$discount;
		$retarray['discount_counter']=$discount_counter;
		
		if(count($highest_bill_amount)!=0){
		$retarray['highest_bill_amount']=max($highest_bill_amount);
		}
		if(count($highest_upgrade_amount)!=0){
		$retarray['highest_upgrade_amount']=max($highest_upgrade_amount);
		}
		if(count($highest_down_grade_amount)!=0){
		$retarray['highest_down_grade_amount']=max($highest_down_grade_amount);
		}
		if(count($highest_original_down_grade_amount)!=0){
		$retarray['highest_original_down_grade_amount']=max($highest_original_down_grade_amount);
		}
		
		
		
		if($feedback_avg_counter!=0){
		$retarray['feedback_rating_avg']=$feedback_rating_avg/$feedback_avg_counter;
		}else{
		$retarray['feedback_rating_avg']=0;
		}	
		
		
		
		
		return $retarray;
		}
		
		
		function trackeereport_manage_data($servicecallid){
		
		 $slq="select *, ".TBL_SERVICEMAN.".isdeleted AS scdeleted from ".TBL_SERVICEMAN." inner join ".TBL_SERVICELIST."  on  ".TBL_SERVICEMAN.".servicelistid= ".TBL_SERVICELIST.".servicelistid 
		where ".TBL_SERVICEMAN.".customerno=".$_SESSION['customerno']."  and servicecallid=".$servicecallid." ";
		$result= $this->db->query($slq,__FILE__,__LINE__);
		$count=0;
		$retarray=array();
		$original_deleted_flag="false";
		$original_deleted_amount=0;
		$upflag="false";
		$downflag="false";
		$downamount=0;
		$totalr_with_ud=0;
			while($row=$this->db->fetch_array($result)){ 
			// condition of revenue for upgare and downgrade 
			if(($row['iscreatedby']==0 && $row['scdeleted']==0 && $row['iseditedby']==0)||($row['iscreatedby']==0 && $row['scdeleted']==1 && $row['iseditedby']==1))
			{
				$retarray['sid']=$row['servicecallid'];
				$totalr_with_ud+=$row['price'];
				//echo "as".$row['price']."<br>";
				}
				
				
				
				// new coulmn added for deleted original ;
				// and iscreatedby =0 AND 	isdeleted =1 and iseditedby=1; iscreatedby =0 AND 	isdeleted =1 and iseditedby=1
	
				if($row['scdeleted']==1  &&  $row['iscreatedby']==0 && $row['iseditedby']==1){
				$retarray['sid']=$row['servicecallid'];
				$original_deleted_flag="true";
				$original_deleted_amount+=$row['price'];
				
				}
				
				
				
				
				//and iscreatedby =1 AND 	isdeleted =0 and iseditedby IN (0,1) ;
				// previous condition if($row['iseditedby']==1  && $row['iscreatedby']==1){};
				
				if($row['iscreatedby']==1){
				$retarray['sid']=$row['servicecallid'];
				$upflag="true";
				$upamount+=$row['price'];
				//echo "u".$row['price']."<br>";
				}
				//previous condition if($row['scdeleted']==1 && $row['iseditedby']==1  ){ } ;
				//iscreatedby =1 AND 	isdeleted =1 and iseditedby IN (0,1); 
				
				if($row['scdeleted']==1  && $row['iscreatedby']==1 ){
				
				
				$retarray['sid']=$row['servicecallid'];
				$downflag="true";
				$downamount+=$row['price'];
				//echo "d".$row['price']."<br>";
				}
			}
		
		$retarray['rev_wup']=$totalr_with_ud;
		$retarray['isupgraded']=$upflag;
		$retarray['isupgraded_amount']=$upamount;
		$retarray['isdowngraded']=$downflag;
		$retarray['isdowngraded_amount']=$downamount;
		$retarray['isoriginaldowngraded']=$original_deleted_flag;
		$retarray['isoriginaldowngraded_amount']=$original_deleted_amount;
		
		return $retarray;
		}

		function trackeereport_feedback_q1_data($serviceid){
		
		 $slq="select * from ".TBL_FEEDBACKRESULTS." left outer join ".TBL_FEEDBACKOPTIONS." on ".TBL_FEEDBACKRESULTS.".feedbackoptionid=".TBL_FEEDBACKOPTIONS.".feedbackoptionid where ".TBL_FEEDBACKRESULTS.".customerno=".$_SESSION['customerno']."  and serviceid=".$serviceid." ";
		$ret=0;
			$result= $this->db->query($slq,__FILE__,__LINE__);
			while($row=$this->db->fetch_array($result))
			{
			if($row['feedbackqid']==6){
				if($row['feedbackoptionid']==52){$ret= 0;}
				if($row['feedbackoptionid']==53){$ret= 1;}
				if($row['feedbackoptionid']==54){$ret= 2;}
				if($row['feedbackoptionid']==55){$ret= 3;}
							
			
			}
			}
			
		return $ret;
		}
		function trackeereport_feedback_q2_data($serviceid){
		
		 $slq="select * from ".TBL_FEEDBACKRESULTS." where customerno=".$_SESSION['customerno']."  and serviceid=".$serviceid." ";
			$ret=-1;
			$result= $this->db->query($slq,__FILE__,__LINE__);
			while($row=$this->db->fetch_array($result))
			{
			if($row['feedbackqid']==8){
			$ret=$row['feedbackoptionid'];
			}
			}
			
		return $ret;
		}


		



	function refresh(){
	$sql_1="delete from ".TBL_SERVICECALL." where trackeeid=18 ";
	echo $this->db->query($sql_1,__FILE__,__LINE__);
	$sql_1="delete from ".TBL_SERVICEMAN." where trackeeid=18 ";
	echo $this->db->query($sql_1,__FILE__,__LINE__);
	$sql_1="delete from ".TBL_FEEDBACKRESULTS." where trackeeid=18 ";
	echo $this->db->query($sql_1,__FILE__,__LINE__);
	
	}

		function feedback_reports($appointmentdate,$appointmentdatet)
		{
			if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
				$branch_clause=" and ".TBL_SERVICECALL.".branchid=".$_SESSION['branchid'];
			}
			if($appointmentdate!="" && $appointmentdatet!="" )
			{
				if(strtotime($appointmentdatet)<strtotime($appointmentdate))
				{
					$appointmentdatet=$appointmentdate;
				}
				
				if(strtotime($appointmentdatet)==strtotime($appointmentdate))
				{
					$newendtimeslot = strtotime('+ 1 Day', strtotime($appointmentdate));
					$appointmentdatet = date("Y-m-d",$newendtimeslot);
				}else{
					$newendtimeslot = strtotime('+ 1 Day', strtotime($appointmentdatet));
					$appointmentdatet = date("Y-m-d",$newendtimeslot);
				}
			}
			$slq="select  *, ".TBL_SERVICECALL.".branchid  from   ".TBL_SERVICECALL." 
				inner join ".TBL_CLIENT." on ".TBL_SERVICECALL.".clientid= ".TBL_CLIENT.".clientid
				left outer join ".TBL_TRACKEE." on ".TBL_TRACKEE.".trackeeid=".TBL_SERVICECALL.".trackeeid
				where ".TBL_CLIENT.".customerno=".$_SESSION['customerno']." ".$branch_clause."
				and ".TBL_CLIENT.".isdeleted=0 and ".TBL_SERVICECALL.".status=5 ";
				
			if($appointmentdate)
			{
				$slq .= " and timeslot_start >='".$appointmentdate."'";
			}
			
			if($appointmentdatet)
			{
				$slq .= " and timeslot_start < '".$appointmentdatet."'";
			}	
			if($appointmentdate!="" && $appointmentdatet!=""){
				$result= $this->db->query($slq,__FILE__,__LINE__);
			}
			
			$slq.="order by ".TBL_CLIENT.".clientid asc ";
			$x=1;
			?>
			<div class="onecolumn">
			<div class="header">
			<span>Feedback Report</span>
			</div>
			<br class="clear"/>
			<br class="clear"/>
			<?php $this->FeedbackBox(); ?>
			<div class="content">
			<div align="right"> 
			<a href="#" onclick="table2CSV(jQuery('#search_table_2'),'','Feedback'); return false;">
			<img src="images/csv.png"  alt="Export to CSV"  title="Export to CSV" /> 
			</a> 
			</div>	
			
					<table id="search_table_2" class="data" width="95%" style="font-size:92%">
					<tr>
						<td>Sno</td>
						<td>Date</th>
						<td>Day</th>
						<td>Appointment Time</td>
						<td>Client</td>
						<td>Trackee</td>
						<td>branch</td>
						<td>Phone No</td>
						<td>service list</td>
						<td>Discount Code</td>
						<td>Discount Amount</td>
						<td>Final bill(after discount)</td>
						<?php 
						$sqlx="select * from ".TBL_FEEDBACKQUESTIONS." where customerno =".$_SESSION['customerno']." and isdeleted=0";
						$resultx= $this->db->query($sqlx,__FILE__,__LINE__);
							while(@$rowx=$this->db->fetch_array($resultx)){
							?>
							<td><?php echo $rowx['feedbackquestion']; ?></td>
						
								
							<?php 
							}
						
						?>
					</tr>
							<?php while(@$row=$this->db->fetch_array($result)){
							
							?>
							<tr>
								<td><?php echo $x++; ?></td>
								<td><?php echo  date("d-m-y",strtotime($row['starttime'])); ?></td>
								<td><?php echo  date("D",strtotime($row['starttime'])); ?></td>
								<td><?php echo  date("h:i A",strtotime($row['timeslot_start'])); ?></td>
								<td width="2%"><?php echo  $row['clientname']; ?></td>
								<td width="2%"><?php echo  $row['tname']; ?></td>
								
								<td><?php echo $this->branch_by_id($row['branchid']); ?></td>
								<td><?php echo  $row['phoneno']; ?></td>
								<td><?php if($row['serviceid']!=""){$this->get_services($row['serviceid']);} ?></td>
								<td><?php echo  $row['discount_code']; ?></td>
								<td><?php echo  $row['discount_amount']; ?></td>
								<td><?php  
										if(($row['totalbill']-$row['discount_amount']+$row['visiting_charges'])>0){
											echo  $row['totalbill']-$row['discount_amount']+$row['visiting_charges'];
										}else{
											echo "0";
										} ?></td>
										<?php $catcharr=$this->feedbackdata($row['serviceid']);  ?>
								
								<td><?php echo  $catcharr['rating']; ?></td>
								<td><?php echo $catcharr['ontime']; ?></td>
								<td><?php echo  $catcharr['call']; ?></td>
								
								
							</tr>
							<?php 
							} ?>
			  </table>
			<br class="clear"/>
			</div>
			</div>
			<?php
		} 
		function feedbackdata($serviceid){
		 $sql="select * from  ".TBL_FEEDBACKRESULTS." 	INNER join ".TBL_FEEDBACKQUESTIONS." on ".TBL_FEEDBACKQUESTIONS.".feedbackquestionid= ".TBL_FEEDBACKRESULTS.".feedbackqid
		INNER join ".TBL_FEEDBACKOPTIONS." on ".TBL_FEEDBACKOPTIONS.".feedbackoptionid= ".TBL_FEEDBACKRESULTS.".feedbackoptionid where serviceid=".$serviceid;
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$retarray=array();
		
			while($row=$this->db->fetch_array($result)){
			
				if($row['feedbackqid']==7){
					$retarray['ontime']=$row['optionname'];
				}
				if($row['feedbackqid']==6){
					$retarray['rating']=$row['optionname'];
				}
				if($row['feedbackqid']==8){
					$retarray['call']=$row['optionname'];
				}
			
			}
				
		return $retarray;
		}
		function branch_by_id($branchid){
		$sql="select * from ".BRANCH." where branchid=".$branchid." AND customerno = ".$_SESSION['customerno'];
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($result);
		return $row['branchname'];
		}


		/*********updation of Clinet fists visist ***************/
		function update_client_table_for_first_last_visist(){
			$sql="select * from ".TBL_CLIENT." where 1";
			$result= $this->db->query($sql,__FILE__,__LINE__);
				while($row=$this->db->fetch_array($result))
				{
					$slq_2="select * from ".TBL_SERVICECALL." where clientid=".$row['clientid']." and status=5  order by endtime asc ";
					$result_2= $this->db->query($slq_2,__FILE__,__LINE__);
					$i=0;
					$u_ay=array();
					$val2="0";
					while($row_2=$this->db->fetch_array($result_2))
					{
							$i++;
							if($i==1)
							{
							$first_visit=$row_2['createdon'];
							$val2="1";
							}
						
						$last_visit=$row_2['createdon'];
						
					}
					
					$u_ay['has_visit']=$val2;
					$u_ay['first_visit'] = $first_visit;
					$u_ay['last_visit'] = $last_visit;
					$this->db->update(TBL_CLIENT,$u_ay,'clientid',$row['clientid']);
				}
	
		}
		
		
		
		
		
		
		
function add_payments($runat,$servicecallid)
{
	$this->servicecallid=$servicecallid;
	switch($runat){
	case 'local' :
			
			
			
			
			$sql="select * from ".TBL_SERVICECALL." where serviceid='".$this->servicecallid."'";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result);
			
			$sql2="select * from ".TBL_PAYMENTS." where serviceid='".$this->servicecallid."'";
			$result2= $this->db->query($sql2,__FILE__,__LINE__);
			$total_amount_bypartial=0;
			while($row2= $this->db->fetch_array($result2))
			{
				$total_amount_bypartial+=$row2['partial_amt'];
			
			}
			
			
		?>
		<div class="onecolumn">
		<div class="header">
		<span>Add payments</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="servicecalls.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="servicecalls.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<a href="servicecalls.php?index=add">
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
		<div class="content" >
		<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
				
		<table  class="data" width="100%">
									<tr>
										<td colspan="100%">&nbsp;</td>
									</tr>
									<tr>
										<th width="15%" >Payment type:</th>
										<td colspan="100%" >
										<select name="type" id="type" onchange="payment_selection();" >
										<option> select </option>
										<option value="0">Cash</option>
										<option value="1">Card</option>
										<option value="2">Cheque</option>
										<option value="3">Skip</option>
										<option value="4">complete</option>
																				
										</select>
										
										<script>
										function payment_selection(){
											
											jQuery(".opt").hide();
											if(jQuery("#type").val()!=""){
											var payment_type=jQuery("#type").val();
											
												if(payment_type==0){
													jQuery("#cash").show();
												}
												if(payment_type==2){
													jQuery("#cash").show();
													jQuery("#cheque").show();
												}
												if(payment_type==3){
													jQuery("#skip").show();
												}
												if(payment_type==4){
												
												jQuery("#complete").show();
												}
												
											}
										} 
											function check_partial_amount() {
													var total_amount_bypartial = jQuery("#total_amount_bypartial").val();
													var partial_amt = jQuery("#partial_amt").val();
													
													var net = jQuery("#net").val();
												
												
												
												if (partial_amt < 0){
													jQuery("#amount_error").html("Amount cannot be negative");
													jQuery("#amount_error").fadeIn(500);
													jQuery("#amount_error").fadeOut(5000);
												
												}
												if (net-total_amount_bypartial < partial_amt) {
													jQuery("#amount_error").fadeIn(500);
													jQuery("#amount_error").fadeOut(5000);
													jQuery("#partial_amt").val("");
												}
											}
										</script>											
									  <span id="span_fname"></span></td>
									</tr>
									<tr  id="cash" class="opt" style="display:none;">
										<th>Amount</th>
										<td colspan="100%"><input  name="partial_amt" id="partial_amt"  type="text" onkeyup="check_partial_amount()"  />
										<span id="amount_error" style="display:none;" class="red">Amount cannot be greated than the total paid amount</span>
										
										</td>
									</tr>
									<tr id="cheque" class="opt" style="display:none;">
										<th>cheque no</th>
									  <td width="15%"><input  name="chequeno" id="chequeno"  type="text" /></td>
										<th width="12%">account no</th>
									  <td width="17%"><input  name="accountno" id="accountno"  type="text" /></td>
										<th width="12%">branch</th>
									  <td width="29%"><input  name="branch" id="branchname"  type="text" /></td>
									</tr>
									<tr id="skip" class="opt" style="display:none;">
									<th>reason</th>
									<td>
									<textarea id="reason" name="reason">
									</textarea>
									</td>
									</tr>
									<tr id="complete" class="opt" style="display:none;">
										<th>Amount</th>
										<td colspan="100%">
											<input readonly="true" value="<?php echo ($row['totalbill']+$row['visiting_charges']); ?>"  name="net" id="net"  type="hidden" />
											<input readonly="true" value="<?php echo ($row['totalbill']+$row['visiting_charges']-$total_amount_bypartial); ?>"  name="full_amt" id="full_amt"  type="text" />
											<input name="total_amount_bypartial" id="total_amount_bypartial"  type="hidden"  value="<?php echo $total_amount_bypartial; ?>" />
										</td>
									</tr>
									
									</tr>
									<tr>
									<td colspan="2">
									<input type="submit" name="submit" value="Submit" 
									>
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
								$this->type=$type;
								$this->partial_amt=$partial_amt;
								$this->chequeno=$chequeno;
								$this->accountno=$accountno;
								$this->branch=$branch;
								$this->reason=$reason;
								
								$return =true;
								if($return){
												// TBL_FORM_META
												
												$insert_meta_sql_array = array();
												$insert_meta_sql_array['type'] = $this->type;	
												$insert_meta_sql_array['partial_amt'] = $this->partial_amt;	
												$insert_meta_sql_array['chequeno'] = $this->chequeno;	
												$insert_meta_sql_array['accountno'] = $this->accountno;	
												$insert_meta_sql_array['branch'] = $this->branch;	
												$insert_meta_sql_array['reason'] = $this->reason;
												$insert_meta_sql_array['is_partial'] = "1";	
												$insert_meta_sql_array['is_web'] = "1";	
												$insert_meta_sql_array['serviceid'] = $servicecallid;
																							
												$insert_meta_sql_array['customerno'] = $_SESSION['customerno'];
												
												$this->db->insert(TBL_PAYMENTS,$insert_meta_sql_array);
											
												$_SESSION['msg']='payment has been added successfully';
						
												?>
												<script type="text/javascript">
												window.location = "servicecalls.php"
												</script>
												<?php
												exit();
								} else {
										echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
										$this->add_payments('local',$servicecallid);
								}
		break;
				default 	: 
				echo "Wrong Parameter passed";
		}
}
function view_payments($servicecallid)
{
	$this->servicecallid=$servicecallid;
	
		
			
			$sql2="select * from ".TBL_PAYMENTS." where serviceid='".$this->servicecallid."' order by paymentid desc ";
			$result2= $this->db->query($sql2,__FILE__,__LINE__);
			$total_amount_bypartial=0;
			
			
			
		?>
		<div class="onecolumn">
		<div class="header">
		<span>view payments</span>
				
		</div>
		<br class="clear"/>
		<div class="content">
		<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
				
		<table  class="data" width="100%">
		<tr>
		<th width="4%">type</th>
		<th width="9%">Amount</th>
		<th width="12%">cheque no</th>
		<th width="12%">account no</th>
		<th width="12%">branch</th>
		
		<th width="32%">reason </th>
		<th width="12%">payment done by </th>
		<th width="9%"> payment date</th>
		</tr>
		<?php
			while($row2= $this->db->fetch_array($result2))
			{
			?>
			<tr>
			
			
			<td><?php 
					switch($row2['type']){
					case "0" :
							echo "Cash";
					break ;
					case "1" :
							echo "Card";
					break ;
					case "2" :
							echo "Cheque";
					break ;
					case "3" :
							echo "Skip";
					break ;
					case "4" :
							echo "complete";
					break ;
					
					};
			
			
			 ?></td>
			
			
		
			
			<td><?php echo $row2['partial_amt']; ?></td>
			
			<?php  if($row2['type']==2){ ?>
			
			<td><?php echo $row2['chequeno']; ?></td>
			
			
			
			<td><?php echo $row2['accountno']; ?></td>
			<td><?php echo $row2['branch']; ?></td>
			
			<?php }else{ ?>
			<td></td>
			<td></td>
			<td></td>
			<?php } ?>
			
			<td><?php echo $row2['reason']; ?></td>
			
			
			<td width="6%"><?php if($row2['is_web']==1){ echo "Web"; }else{ echo "Mobile";}; ?></td>
			
			<td width="4%"><?php echo date("d-m-Y",strtotime($row2['timestamp'])); ?></td>
			</tr>



			<?php 
			}
		
		 ?>
		
		
		
		</table>
		</form>
		
		</div>
		</div>
		<br class="clear"/>
		<?php
}

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
}
?>