<?php

class servicelist{


var $servicename;
var $price;
var $expectedtime;
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
function getlastupdatedby($userid)
{
$sql="select * from ".TBL_ADMIN_USER." where userid=".$userid." ";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
$row= $this->db->fetch_array($resultpages) ;
echo $row['username'];
}
function deleteupdate($servicelistid)
{
ob_start();
$update_array = array();
$this->updatetrackee("list",$trackeeid);
$update_array['isdeleted'] = '1';
$this->db->update(TBL_SERVICELIST,$update_array,'servicelistid',$servicelistid);
$_SESSION['msg']='Record has been deleted successfully';
$this->updatetrackee("service",$trackeeid);
?>
<script type="text/javascript">
window.location = "service_list.php"
</script>
<?php

$html = ob_get_contents();
ob_end_clean();
return $html;
}

function getservisedetailsbyid($sid)
{
 
 $sql="select price,expectedtime from ".TBL_SERVICELIST." where servicelistid='".$sid."' and customerno='".$_SESSION['customerno']."'";
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
		$ControlNames=array("servicename"=>array('servicename',"''","Please Enter Service Name","span_cname"),
                    "price"=>array('price',"''","Please enter price. ","span_price"),
                    "expectedtime"=>array('expectedtime',"''","Please enter expected time. ","span_expectedtime")

                    );
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Add Service</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
			<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="service_list.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="service_list.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<a href="service_list.php?index=add">
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
	<br class="clear"/>
					<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
							<table  class="data" width="100%">
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<th >Service Name:</th>
										<td><input  type="text" value="" name="servicename" id="servicename" />
										<span id="span_cname"></span></td>
									</tr>
									<tr>
										<th >Price :</th>
										<td><input  type="text" value="" name="price" id="price"  size="50"/>
										<span id="span_price"></span></td>
									</tr>
									<tr>
										<th >Expected time :</th>
										<td><input  type="text" value="" name="expectedtime" id="expectedtime" size="50" />
										numerical value In minutes ,like 50min =50
										<span id="span_expectedtime"></span></td>
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
							
							$this->servicename=$servicename;
							$this->price=$price;
							$this->expectedtime=$expectedtime;
							$this->updatetrackee("list",$trackeeid);
							$return =true;
							 //server side validation
								$this->updatetrackee("list",$trackeeid);
							if($this->Form->ValidField($servicename,'empty','Service name is empty')==false)
							$return =false;
                                                        if($this->Form->ValidField($price,'empty','Service price is empty')==false)
							$return =false;
                                                        if($this->Form->ValidField($expectedtime,'empty','Expected time is empty')==false)
							$return =false;
									if($return){
												$insert_sql_array = array();
												$insert_sql_array['servicename'] = $this->servicename;
												$insert_sql_array['price'] = $this->price;
												$insert_sql_array['expectedtime'] = $this->expectedtime;
												$insert_sql_array['customerno'] = $_SESSION['customerno'];
												$insert_sql_array['userid'] = $_SESSION['user_id'];
												
												$this->db->insert(TBL_SERVICELIST,$insert_sql_array);
												$user_id = $this->db->last_insert_id();
											
												$_SESSION['msg'] = 'Service has been Successfully created';
												?>
												<script type="text/javascript">
												window.location = "service_list.php"
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
			$sql="select * from ".TBL_SERVICELIST." where isdeleted=0 and customerno=".$_SESSION['customerno']." ";
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
			$result= $this->db->query($sql,__FILE__,__LINE__);
		?>
		
		<div class="onecolumn">
		<div class="header">
		<span>Service List</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="service_list.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="service_list.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<a href="service_list.php?index=add">
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
									<th width="10%">Service Name</th>
									<th width="10%">price</th>
									<th width="10%">expectedtime</th>
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
									<td title="<?php echo $row['servicename'];?>"><?php echo $row['servicename'];?></td>
									<td title="<?php echo $row['price'];?>"><?php echo $row['price'];?></td>
									<td title="<?php echo $row['expectedtime'];?>"><?php echo $row['expectedtime'];?></td>
										<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									
									<td><a href="service_list.php?index=View&servicelist_id=<?php echo $row['servicelistid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
									| <a href="service_list.php?index=edit&servicelist_id=<?php echo $row['servicelistid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									
									<a href="javascript: void(0);" title="delete" class="help" 
									onclick="javascript: if(confirm('Do u want to delete this record?')) { serviceinfo.deleteupdate('<?php echo $row['servicelistid'];?>',{}) }; return false;" >
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
								<a href="service_list.php">&laquo;&laquo;</a>
								<a href="service_list.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="service_list.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="service_list.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="service_list.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="service_list.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="service_list.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="service_list.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="service_list.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="service_list.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="service_list.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="service_list.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="service_list.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>
		
		<div align="right">Total Pages - <?php echo $lastpage;?></div>
		<div align="right">Total Records - <?php echo $numpages;?></div>
		
		</div>
		</div>
		
		<?php 
		
		}

// view client 
function ServiceView($servicelist_id)
{
	$this->servicelist_id=$servicelist_id;
	$sql="select * from ".TBL_SERVICELIST." where servicelistid='".$this->servicelist_id."' and customerno=".$_SESSION['customerno']."";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Service Detail</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
		<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="service_list.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="service_list.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<a href="service_list.php?index=add">
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
				<th>Service Name:  </th>
				<td><?php echo $row['servicename'];?> </td>
				</tr>
				
				<tr>
				<th>Price:  </th>
				<td><?php echo $row['price'];?> </td>
				</tr>
				
				
				<tr>
				<th>Expected time: </th>
				<td><?php echo $row['expectedtime'];?> </td>
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
				$this->servicename = $servicename;
			}
			$FormName = "frm_addstud";
			$ControlNames=array("servicename"=>array('servicename',"''","Please enter service name. ","span_cname"));
			$ValidationFunctionName="CheckAddstudentValidity";
			$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
			echo $JsCodeForFormValidation;
			
			
			
			$sql="select * from ".TBL_SERVICELIST." where customerno='".$_SESSION['customerno']."' AND servicelistid='".$this->servicelistid."'";
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
						<a href="service_list.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="service_list.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
						<a href="service_list.php?index=add">
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
				<th >Service Name:</th>
				<td><input  type="text" value="<?php echo $row['servicename']; ?>" name="servicename" id="servicename" />
				<span id="span_cname"></span></td>
				</tr>
				<tr>
				<th >Price :</th>
				<td><input  type="text" value="<?php echo $row['price']; ?>" name="price" id="price"  size="50"/>
				<span id="span_price"></span></td>
				</tr>
				<tr>
				<th >Expected time :</th>
				<td><input  type="text" value="<?php echo $row['expectedtime']; ?>" name="expectedtime" id="expectedtime" size="50" />
				<span id="span_expectedtime"></span></td>
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
		$this->servicename=$servicename;
		$this->price=$price;
		$this->expectedtime=$expectedtime;
		$this->updatetrackee("list",$trackeeid);
		// server side validation
		$return =true;
		if($return){
		$update_sql_array = array();
		$update_sql_array['servicename'] = $this->servicename;
		$update_sql_array['price'] = $this->price;
		$update_sql_array['expectedtime'] = $this->expectedtime;
		$this->db->update(TBL_SERVICELIST,$update_sql_array,'servicelistid',$this->servicelistid);
		$_SESSION['msg']='client has been updated successfully';
		
		?>
		<script type="text/javascript">
		window.location = "service_list.php"
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
		<form action="service_list.php?index=search" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				<tr>
				<th>Name Of Service : </th>
				<td><input   type="text" value="<?php echo $_REQUEST['servicename'];?>" id="servicename" name="servicename" /></td>
				
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



function SearchRecord($servicename='')
{
$sql="select * from ".TBL_SERVICELIST." where  isdeleted=0 and customerno=".$_SESSION['customerno']." ";
if($servicename)
{
$sql .= " and servicename like '%".$servicename."%'";
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
<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
<table width="300px" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><a href="service_list.php">
<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
</a>
</td>
<td>
<input type="button" id="chart_line" name="chart_line" class="middle_switch " value="Search" style="width:100px" />
</td>
<td><a href="service_list.php?index=add">

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

<form id="form_data" name="form_data" action="" method="post">
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Service Name</th>
									<th width="8%">Price</th>
									<th width="8%">Expected time</th>
									
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
									<td title="<?php echo $row['servicename'];?>"><?php echo $row['servicename'];?></td>
									<td title="<?php echo $row['price'];?>"><?php echo $row['price'];?></td>
									<td title=""><?php echo $row['expectedtime'];?></td>
									
										<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									
									<td><a href="service_list.php?index=View&servicelist_id=<?php echo $row['servicelistid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
									| <a href="service_list.php?index=edit&servicelist_id=<?php echo $row['servicelistid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<a href="javascript: void(0);" title="delete" class="help" 
									onclick="javascript: if(confirm('Do u want to delete this record?')) { serviceinfo.deleteupdate('<?php echo $row['servicelistid'];?>',{}) }; return false;" >
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
								<a href="service_list.php">&laquo;&laquo;</a>
								<a href="service_list.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="service_list.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="service_list.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="service_list.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="service_list.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="service_list.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="service_list.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="service_list.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="service_list.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="service_list.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="service_list.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="service_list.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>

<div align="right">Total Pages - <?php echo $lastpage;?></div>
<div align="right">Total Records - <?php echo $numpages;?></div>

</div>

</div>

<?php 

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







}



?>