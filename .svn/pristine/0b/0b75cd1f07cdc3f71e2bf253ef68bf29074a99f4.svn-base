<?php

class remarks{


var $remarkname;
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
$this->updatetrackee("remark",$trackeeid);

}

function getlastupdatedby($userid)
{
$sql="select * from ".TBL_ADMIN_USER." where userid=".$userid." ";
$resultpages= $this->db->query($sql,__FILE__,__LINE__);    
$row= $this->db->fetch_array($resultpages) ;
echo $row['username'];
}

function deleteupdate($remarkid)
{
ob_start();
$update_array = array();
$update_array['isdeleted'] = '1';
$this->db->update(TBL_REMARKS,$update_array,'remarkid',$remarkid);
$_SESSION['msg']='Record has been deleted successfully';
$this->updatetrackee("service",$trackeeid);
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
	function addremark($runat)
	{
		switch($runat){
		case 'local' :
		if(count($_POST)>0 and $_POST['submit']=='Submit'){
			extract($_POST);
			$this->remarkname = $remarkname;
			
		}
		$FormName = "frm_add_client";
		$ControlNames=array("remarkname"=>array('remarkname',"''","Please Enter Remarks Name","span_cname"));
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Add Remarks</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
			<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="remarks.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="remarks.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
					<a href="remarks.php?index=add">
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
										<th >Remarks:</th>
										<td><input  type="text" value="" name="remarkname" id="remarkname" />
										<span id="span_cname"></span></td>
									</tr>
									
									
									<tr>
									<td colspan="2"><input type="submit" name="submit" value="Submit" 
									onclick="return <?php echo $ValidationFunctionName;?>()">
									&nbsp;
									<input type="button" onclick="javascript: history.go(-1); return false" 
									name="Cancel" value="cancel" />
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
							
							$this->remarkname=$remarkname;
						
							$this->updatetrackee("remark",$trackeeid);
							$return =true;
							 //server side validation
								
							if($this->Form->ValidField($remarkname,'empty','Remarks name is empty')==false)
							$return =false;
									if($return){
												$insert_sql_array = array();
												$insert_sql_array['remarkname'] = $this->remarkname;
													$insert_sql_array['userid']=$_SESSION['user_id'];
												$insert_sql_array['customerno']=$_SESSION['customerno'];
												$this->db->insert(TBL_REMARKS,$insert_sql_array);
												$user_id = $this->db->last_insert_id();
											
												$_SESSION['msg'] = 'Remarks has been Successfully created';
												?>
												<script type="text/javascript">
												window.location = "remarks.php"
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
	function showAllRemarksfInfo()
		{
			$sql="select * from ".TBL_REMARKS." where  isdeleted=0 and customerno=".$_SESSION['customerno']." ";
			$sql.=" order by remarkid desc ";
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
		<span>Remarks List</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
					<tbody>
					<tr>
					<td>
					<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:100px"/>
					</td>
					<td><a href="remarks.php?index=search">
					<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
					</a>
					<td>
					<a href="remarks.php?index=add">
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
									<th width="10%">Remarks Name</th>
									
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
									<td title="<?php echo $row['remarkname'];?>"><?php echo $row['remarkname'];?></td>
									
								<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									
									<td><a href="remarks.php?index=View&remarkid=<?php echo $row['remarkid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
									| <a href="remarks.php?index=edit&remarkid=<?php echo $row['remarkid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<a href="javascript: void(0);" title="delete" class="help" 
									onclick="javascript: if(confirm('Do u want to delete this record?')) { productinfo.deleteupdate('<?php echo $row['remarkid'];?>',{}) }; return false;" >
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
								<a href="remarks.php">&laquo;&laquo;</a>
								<a href="remarks.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="remarks.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="remarks.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="remarks.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="remarks.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="remarks.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="remarks.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="remarks.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="remarks.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="remarks.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="remarks.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="remarks.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>
		
		<div align="right">Total Pages - <?php echo $lastpage;?></div>
		<div align="right">Total Records - <?php echo $numpages;?></div>
		
		</div>
		</div>
		
		<?php 
		
		}

// view client 
function RemarksView($remarkid)
{
	$this->remarkid=$remarkid;
	$sql="select * from ".TBL_REMARKS." where remarkid='".$this->remarkid."' and customerno=".$_SESSION['customerno']."";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Remarks Detail</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
		<table width="300px" cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
				<td><a href="remarks.php">
				<input type="button" id="chart_bar2" name="chart_bar2" class="left_switch" value="View All" style="width:100px"/>
				</a></td>
				<td><a href="remarks.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a></td>
				<td>
					<a href="remarks.php?index=add">
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
				<tr>
				<th>Remarks Name:  </th>
				<td><?php echo $row['remarkname'];?> </td>
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
	$this->remarkid=$id;
	switch($runat){
	case 'local' :
			if(count($_POST)>0 and $_POST['submit']=='Submit'){
				extract($_POST);
				$this->remarkname = $remarkname;
			}
			$FormName = "frm_addstud";
			$ControlNames=array("remarkname"=>array('remarkname',"''","Please enter Clinet name. ","span_cname"));
			$ValidationFunctionName="CheckAddstudentValidity";
			$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
			echo $JsCodeForFormValidation;
			
			
			
			$sql="select * from ".TBL_REMARKS." where remarkid='".$this->remarkid."'";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result);
		?>
		<div class="onecolumn">
		<div class="header">
		<span>Edit Remark</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
				<td><a href="remarks.php">
				<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
				</a>
				</td>
				<td><a href="remarks.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a>
				<td>
					<a href="remarks.php?index=add">
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
				<th >Remarks Name:</th>
				<td><input  type="text" value="<?php echo $row['remarkname']; ?>" name="remarkname" id="remarkname" />
				<span id="span_cname"></span></td>
				</tr>
				
				
				<tr>
				<td colspan="2">
				<input type="submit" name="submit" value="Submit" 
				onclick="return <?php echo $ValidationFunctionName;?>()">
				&nbsp;
				<input type="button" onclick="javascript: history.go(-1); return false" 
				name="Cancel" value="Cancel" /></td>
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
		$this->remarkname=$remarkname;
		$this->updatetrackee("remark",$trackeeid);
		// server side validation
		$return =true;
		if($return){
		$update_sql_array = array();
		$update_sql_array['remarkname'] = $this->remarkname;
		$update_sql_array['userid']=$_SESSION['user_id'];
		$update_sql_array['customerno']=$_SESSION['customerno'];
		$this->db->update(TBL_REMARKS,$update_sql_array,'remarkid',$this->remarkid);
		$_SESSION['msg']='remark has been updated successfully';
		
		?>
		<script type="text/javascript">
		window.location = "remarks.php"
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
$sql_1="delete from ".TBL_REMARKS." where remarkid='".$this->id."'";
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
		<form action="remarks.php?index=search" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				<tr>
				<th>Name Of Remarks : </th>
				<td><input   type="text" value="<?php echo $_REQUEST['remarkname'];?>" id="remarkname" name="remarkname" /></td>
				
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



function SearchRecord($remarkname='',$phoneno='')
{
$sql="select * from ".TBL_REMARKS." where isdeleted=0 and customerno=".$_SESSION['customerno']." ";
if($remarkname)
{
$sql .= " and remarkname = '".$remarkname."'";
}

$sql.=" order by remarkid desc ";
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
<td><a href="remarks.php">
<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
</a>
</td>
<td>
<a href="remarks.php?index=search">
<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
</a>
</td>
<td>
					<a href="remarks.php?index=add">
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

<form id="form_data" name="form_data" action="" method="post">
						<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">  
						
						<thead>
						
									<tr>
									<th width="5%">SNO.</th>
									<th width="10%">Remarks Name</th>
									
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
									<td title="<?php echo $row['remarkname'];?>"><?php echo $row['remarkname'];?></td>
									
									<td title=""><?php echo $this->getlastupdatedby($row['userid']);?></td>
									
									<td><a href="remarks.php?index=View&remarkid=<?php echo $row['remarkid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
									| <a href="remarks.php?index=edit&remarkid=<?php echo $row['remarkid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
									<a href="javascript: void(0);" title="delete" class="help" 
									onclick="javascript: if(confirm('Do u want to delete this record?')) { productinfo.deleteupdate('<?php echo $row['remarkid'];?>',{}) }; return false;" >
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
								<a href="remarks.php">&laquo;&laquo;</a>
								<a href="remarks.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="remarks.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="remarks.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="remarks.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="remarks.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="remarks.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="remarks.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="remarks.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="remarks.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="remarks.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="remarks.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="remarks.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
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