<?php
require_once("class/class.geotag.php");
class trackeeextra{
    
}

class trackee{


var $tname;
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
$this->geotag_obj=new  geotag();

}

function select_tracees_mapped()
{
$sql="select trackeeid, devicelat, devicelong from ".TBL_DEVICE." where customerno='".$_SESSION['customerno']."'";
$result= $this->db->query($sql,__FILE__,__LINE__);
$str=array();
$x=0;
while($row= $this->db->fetch_array($result)){
    if($row['devicelat'] != 0 || $row['devicelong'] != 0)
    {
        $str[$x++]=$row['trackeeid'];
    }
}
return  implode(",",$str);
}

function selectmappedtrackees()
{
$cat= $this->select_tracees_mapped();
if(isset($cat) && $cat!=""){
$sql="select * from ".TBL_TRACKEE." where trackeeid in (".$cat.") and customerno=".$_SESSION['customerno']." and isdeleted=0";
$result= $this->db->query($sql,__FILE__,__LINE__);
?>
<select id="trackeeid" name="trackeeid">
<option value="-1">Select Trackee</option>
<?php 
$x=1;
while($row= $this->db->fetch_array($result)){
?><option value=" <?php echo @$row['trackeeid']; ?>"><?php echo @$row['tname'];?></option>
<?php } ?>
</select>
<?php 
}    
}

function selectcheckbox()
{

$cat= $this->select_tracees_mapped();
?>
<select id="to" onchange="addVehicle();" name="to">
<option value="-1">Select Trackee</option>
<?php
if(isset($cat) && $cat!="")
{
if($_SESSION['branchid']!="all"){
	  $branch_clause=" and branchid=".$_SESSION['branchid'];
	  }
$sql="select * from ".TBL_TRACKEE." where trackeeid in (".$cat.") and customerno=".$_SESSION['customerno']." and isdeleted=0 ".$branch_clause;
$result= $this->db->query($sql,__FILE__,__LINE__);
?>
<?php 
$x=1;
while($row= $this->db->fetch_array($result)){
?><option value=" <?php echo @$row['trackeeid']; ?>"><?php echo @$row['tname'];?></option>
<?php } ?>
<?php 
}
?>
</select>
<input type="button" onclick="addAllVehicles();" value="Add all">
<div id="vehicle_list" class="padding"></div>
<?php
}

function getallmappedtrackees()
{
    $cat= $this->select_tracees_mapped();  
	if(isset($cat) && $cat!=""){  
    $trackees = Array();
    $sql = "SELECT tname, trackeeid FROM ".TBL_TRACKEE." WHERE trackeeid in (".$cat.") and customerno=".$_SESSION['customerno']." and isdeleted=0";
    $result= $this->db->query($sql,__FILE__,__LINE__);
    while ($row = $this->db->fetch_array($result))            
    {
        $trackee = new trackeeextra();
        $trackee->trackeeid = $row['trackeeid'];        
        $trackee->tname = $row['tname'];
        $trackees[] = $trackee;
    }
    return $trackees;    
	}        
}

function getlantlong($trackeeid)
{
$sql="select * from ".TBL_DEVICE." where customerno='".$_SESSION['customerno']."' and trackeeid='".$trackeeid."'";
$result= $this->db->query($sql,__FILE__,__LINE__);  
$row= $this->db->fetch_array($result);

$laglong=array();
$laglong['lat']=$row['devicelat'];
$laglong['long']=$row['devicelong'];
$laglong['lastupdated']=$row['lastupdated'];
return $laglong;
}

function getdevicekey($trackeeid)
{
$devicekey=0;
if($trackeeid!=""){
$sql="select * from ".TBL_DEVICE." where customerno='".$_SESSION['customerno']."' and trackeeid='".trim($trackeeid)."'";
$result= $this->db->query($sql,__FILE__,__LINE__);  
$row= $this->db->fetch_array($result);
}
if($row['devicekey']!=""){
	$devicekey=$row['devicekey'];
}else{
	$devicekey=0;
}
return $devicekey;
}


function jsonpusher_all()
{
    $cat= $this->select_tracees_mapped();
	if(isset($cat) && $cat!=""){
	
	
	
		  if($_SESSION['branchid']!="all"){
		  $branch_clause=" and branchid=".$_SESSION['branchid'];
		  }
    $sql="select * from ".TBL_TRACKEE." where trackeeid in (".$cat.") and customerno='".$_SESSION['customerno']."' " .$branch_clause;
    $result= $this->db->query($sql,__FILE__,__LINE__);
   $resarray=array();
   $x=0;
    while($row= $this->db->fetch_array($result)){
       
        $getlatlong =$this->getlantlong($row['trackeeid']);
        
        $resarray['cgeolat']= $getlatlong['lat'] ;
        $resarray['cgeolong']= $getlatlong['long'] ;
        $resarray['cname']= $row['tname'];
        $resarray['clastupdated']= $getlatlong['lastupdated'];
        $resarray['clastupdated']="images/pin.png";
        
        
        if($x!=0){$str.=",";}
        $str.=json_encode($resarray);
        $x++;
     }
   
    $jsonarray=array();
    $jsonarray['success']="true";
    $jsonarray['errorcode']=0;
    $jsonarray['errormessage']="";
    $jsonarray['result']= $str;
    
    $jmain='{"success" :"true","errorcode":0,"errormessage":"","result":['.$str.']}';
    echo $jmain; 
	}
   }
function get_status($trackeeid)
{
$status="";
$appendstr="";
if($trackeeid!="" && $trackeeid!=0)
{
$sql="select * from ".TBL_SERVICECALL." where customerno=".$_SESSION['customerno']." and trackeeid=".$trackeeid." and isdeleted=0 and status in (3,4,6,7)";
$result= $this->db->query($sql,__FILE__,__LINE__);
$x=0;
while($row= $this->db->fetch_array($result)){
$appendstr="Client name:".$row['clientname'];
$status="BUSY";
}
}
return array ($appendstr,$status);
}
function jsonpusher_selected($deviceids)
{
    $idarray = explode(",", $deviceids);      
    
//    $cat= $this->select_tracees_mapped();
    if(isset($idarray))
    {
           $x=0;                    
        foreach($idarray as $trackeeid)
        {
            if(isset($trackeeid) && $trackeeid != "")
            {
                $sql="select * from ".TBL_TRACKEE." where trackeeid = ".$trackeeid." and customerno='".$_SESSION['customerno']."'";
                $result= $this->db->query($sql,__FILE__,__LINE__);
               $resarray=array();
                while($row= $this->db->fetch_array($result)){

                    $getlatlong =$this->getlantlong($row['trackeeid']);
					$status_arr=$this->get_status($row['trackeeid']);
                    $resarray['cgeolat']= $getlatlong['lat'] ;
                    $cgeolat = $resarray['cgeolat'];
                    $resarray['cgeolong']= $getlatlong['long'] ;
                    $cgeolong = $resarray['cgeolong'];
                    $resarray['cname']= $row['tname'];
                    $lastupdated = strtotime($getlatlong['lastupdated']);
                    $today = date("Y-m-d H:i:s");        
                    $today = strtotime($today);
                   //$resarray['lastupdated'] =  $lastupdated;
					$resarray['lastupdated'] = $this->diff($today, $lastupdated);
					$resarray['clientname']=$status_arr['0'];
					$resarray['status']=$status_arr['1'];
					    
                    $geotag =$this->geotag_obj->get_location_bylatlong($cgeolat,$cgeolong);
                    $resarray['geotag'] = $geotag;                                
                    $resarray['clastupdated']="images/pin.png";
                    if($getlatlong['lat'] != 0 || $getlatlong['long'] != 0)
                    {
                        if($x!=0){$str.=",";}
                        $str.=json_encode($resarray);
                        $x++;
                    }
                 }
            }
        }
    }
   
    $jsonarray=array();
    $jsonarray['success']="true";
    $jsonarray['errorcode']=0;
    $jsonarray['errormessage']="";
    $jsonarray['result']= $str;
    
    $jmain='{"success" :"true","errorcode":0,"errormessage":"","result":['.$str.']}';
    echo $jmain; 
   }

function diff($Today,$lastupdated)
{
    
    $diff = abs($Today - $lastupdated); 

    $years   = floor($diff / (365*60*60*24)); 
    $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));            
    $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
    $minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
    $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
    if($days > 0)
    {
        $difference = date("D d-M-Y H:i",$lastupdated);
    }
    else
    {
        if($hours > 0)
        {
            $difference = $hours." hr ".$minutes." min ago";
        }
        elseif($minutes > 0)
        {
            $difference = $minutes." min ago";                
        }
        else
        {
            $difference = $seconds." sec ago";                                
        }
    }
    return $difference;
}

// add Trackee function 
	function addTrackee($runat)
	{
		switch($runat){
		case 'local' :
		if(count($_POST)>0 and $_POST['submit']=='Submit'){
			extract($_POST);
			$this->tname = $tname;
			
		}
		$FormName = "frm_add_Trackee";
		$ControlNames=array("tname"=>array('tname',"''","Please Enter  Name","span_cname")
		
			);
		$ValidationFunctionName="CheckAddNewsValidity";
		$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Add trackee</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
			<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<a href="createtrackee.php">
						<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
						</a>
					</td>
					<td>
						<a href="createtrackee.php?index=search">
						<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
						</a>
					</td>
					<td>
					<a href="createtrackee.php?index=add">
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
										<th >Name:</th>
										<td><input  type="text" value="" name="tname" id="tname" />
										<span id="span_cname"></span></td>
									</tr>
									<tr>
										<th>Branch</th>
										<td>
										 
										<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){
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
									
									
									
									
									
									
									<td colspan="2"><input type="submit" name="submit" value="Submit" 
									onclick="return <?php echo $ValidationFunctionName;?>()">
									&nbsp;
									<input type="button" onclick="javascript: history.go(-1); return false" 
									name="cancel" value="cancel" />
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
							
							$this->tname=$tname;
						
							$return =true;
							 //server side validation
							if( strlen(trim($tname))>12){
							$_SESSION['error_msg']="<li>The name is greater than twelve characters<li>";
							
							$return =false;
							}
							if($this->Form->ValidField($tname,'empty','TRACKEE name is empty')==false)
							$return =false;
									if($return){
												$insert_sql_array = array();
												$insert_sql_array['tname'] = $this->tname;
												$insert_sql_array['branchid'] = $branch;
												$insert_sql_array['ticonimage'] = "sample1t.png";
												$insert_sql_array['pushitems'] = '1';
												$insert_sql_array['pushmessages'] = '1';
												$insert_sql_array['pushservice'] ='1';
												$insert_sql_array['pushcustom'] = '1';
												$insert_sql_array['pushremarks'] = '1';
												$insert_sql_array['pushfeedback'] = '1';
                                                                                                $insert_sql_array['pushform'] = '1';
												$insert_sql_array['pushservicelist'] = '1';
											
												$insert_sql_array['customerno'] = $_SESSION['customerno'];
												$insert_sql_array['userid'] = $_SESSION['user_id'];
												
												$this->db->insert(TBL_TRACKEE,$insert_sql_array);
												$user_id = $this->db->last_insert_id();
											
												$_SESSION['msg'] = 'TRACKEE has been Successfully created';
												?>
												<script type="text/javascript">
												window.location = "createtrackee.php"
												</script>
												<?php
												exit();
										
										} else {
												echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
												$this->addTrackee('local');
												?>
												<script type="text/javascript">
												window.location = "createtrackee.php"
												</script>
												<?php
										}
									break;
									default 	: 
									echo "Wrong Parameter passed";
									}
	}

// show all Trackees
	function showAllTrackeefInfo()
		{
		if($_SESSION['branchid']!="all" && $_SESSION['branchid']!="0"){
		$branch_clause=" and branchid=".$_SESSION['branchid'];
		}
			$sql="select * from ".TBL_TRACKEE." where 1 and customerno=".$_SESSION['customerno']." ".$branch_clause;
			$sql.=" order by trackeeid desc ";
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
		<span>Trackee List</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
					<tbody>
					<tr>
					<td>
					<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:100px"/>
					</td>
					<td><a href="createtrackee.php?index=search">
					<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
					</a>
					</td>
					<td><a href="createtrackee.php?index=add">
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
									<th width="10%"> Name</th>
									<th width="10%"> branch</th>
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
									<td title="<?php echo $row['tname'];?>"><?php echo $row['tname'];?></td>
									<td><?php echo $this->branch_by_id($row['branchid']); ?></td>
									<td><a href="createtrackee.php?index=View&trackeeid=<?php echo $row['trackeeid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] == 'Master' || $_SESSION['user_type'] == 'Administrator') {?>
									| <a href="createtrackee.php?index=edit&trackeeid=<?php echo $row['trackeeid'];?>" title="Edit" class="help">
									<img src="images/icon_edit.png" width="15px" height="15px" /></a> 
									<?php
									 } ?></td> 
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
								<a href="createtrackee.php">&laquo;&laquo;</a>
								<a href="createtrackee.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="createtrackee.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="createtrackee.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="createtrackee.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="createtrackee.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="createtrackee.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>
		
		<div align="right">Total Pages - <?php echo $lastpage;?></div>
		<div align="right">Total Records - <?php echo $numpages;?></div>
		
		</div>
		</div>
		
		<?php 
		
		}

// view Trackee 

function branch_by_id($branchid){
		$sql="select * from ".BRANCH." where branchid=".$branchid." AND customerno = ".$_SESSION['customerno'];
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($result);
		return $row['branchname'];
		}
function TrackeeView($trackeeid)
{
	$this->trackeeid=$trackeeid;
	$sql="select * from ".TBL_TRACKEE." where trackeeid=".$this->trackeeid." and customerno=".$_SESSION['customerno']." and isdeleted=0";
	$result= $this->db->query($sql,__FILE__,__LINE__);
	$row= $this->db->fetch_array($result);
	?>
	<div class="onecolumn">
	<div class="header">
	<span>Trackee Detail</span>
	<div class="switch" style="width:300px">
	<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
		<table width="300px" cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
				<td><a href="createtrackee.php">
				<input type="button" id="chart_bar2" name="chart_bar2" class="left_switch" value="View All" style="width:100px"/>
				</a></td>
				<td><a href="createtrackee.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a></td>
				<td><a href="createtrackee.php?index=add">
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
				<th>Name:  </th>
				<td><?php echo $row['tname'];?> </td>
				</tr>
				
				
				</tr>
		</table>
	</form>
	</div>
	</div>
	<br class="clear"/>
	<?php
}

// edit Trackee

function editTrackee ($runat,$id)
{
	$this->trackeeid=$id;
	switch($runat){
	case 'local' :
			if(count($_POST)>0 and $_POST['submit']=='Submit'){
				extract($_POST);
				$this->tname = $tname;
			}
			$FormName = "frm_addstud";
			$ControlNames=array("tname"=>array('tname',"''","Please enter Clinet name. ","span_cname"));
			$ValidationFunctionName="CheckaddValidity";
			$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
			echo $JsCodeForFormValidation;
			
			
			
			$sql="select * from ".TBL_TRACKEE." where trackeeid=".$this->trackeeid." and customerno=".$_SESSION['customerno']."  and isdeleted=0";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$row= $this->db->fetch_array($result);
		?>
		<div class="onecolumn">
		<div class="header">
		<span>Edit</span>
		<div class="switch" style="width:300px">
		<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){ ?>
				<table width="300px" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
				<td><a href="createtrackee.php">
				<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
				</a>
				</td>
				<td><a href="createtrackee.php?index=search">
				<input type="button" id="chart_line" name="chart_line" class="middle_switch" value="Search" style="width:100px" />
				</a>
				</td>
				<td><a href="createtrackee.php?index=add">
				<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:100px" />
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
		<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
				
		<table  class="data" width="100%">
				<tr>
				<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
				<th >Trackee Name:</th>
				<td><input  type="text" value="<?php echo $row['tname']; ?>" name="tname" id="tname" />
				<span id="span_cname"></span></td>
				</tr>
				<tr>
							<th>Branch</th>
								<td>
								
								<?php if($_SESSION['user_type']=="Administrator" || $_SESSION['user_type']=="Master"){
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
				<td colspan="2">
				<input type="submit" name="submit" value="Submit" 
				onclick="return <?php echo $ValidationFunctionName;?>()">
				&nbsp;
				<input type="button" onclick="javascript: history.go(-1); return false" 
				name="cancel" value="cancel" /></td>
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
		$this->tname=$tname;
		$return=true;
		if($return==true){
		$update_sql_array=array();
		$update_sql_array['tname']=$this->tname;
		$update_sql_array['branchid']=$branch;
		$this->db->update(TBL_TRACKEE,$update_sql_array,'trackeeid',$this->trackeeid);
		$_SESSION['msg']='trackee has been updated successfully';
		
		?>
		<script type="text/javascript">
		window.location = "createtrackee.php"
		</script>
		<?php
		exit();
		} else {
		echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
		$this->editTrackee('local');
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
		<form action="createtrackee.php?index=search" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				<tr>
				<th>Name Of trackee : </th>
				<td><input   type="text" value="<?php echo $_REQUEST['tname'];?>" id="tname" name="tname" /></td>
				
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



function SearchRecord($tname='')
{
$sql="select * from ".TBL_TRACKEE." where customerno=".$_SESSION['customerno']."  and isdeleted=0";
if($tname)
{
$sql .= " and tname = '".$tname."'";
}

$sql.=" order by trackeeid desc ";
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
<td><a href="createtrackee.php">
<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:100px"/>
</a>
</td>
<td>
<input type="button" id="chart_line" name="chart_line" class="middle_switch active" value="Search" style="width:100px" />
</td>
<td><a href="createtrackee.php?index=add">

<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:100px" />

</a>

</td>

</tr>

</tbody>

</table>
<?php  }  ?>
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
									<th width="10%"> Name</th>
								
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
									<td title="<?php echo $row['tname'];?>"><?php echo $row['tname'];?></td>
									
									
									<td><a href="createtrackee.php?index=View&trackeeid=<?php echo $row['trackeeid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] != 'User') {?>
									| <a href="createtrackee.php?index=edit&trackeeid=<?php echo $row['trackeeid'];?>" title="Edit" class="help">
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
								<a href="createtrackee.php">&laquo;&laquo;</a>
								<a href="createtrackee.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
								<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
								<a href="createtrackee.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } ?>
								<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
								if(($pgr-3) >= 1){
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
								<?php } } ?>
								
								<?php $temp0=$pgr-2;
								if($temp0 >= 1) {				
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
								<?php } ?>
								
								<?php $temp1=$pgr-1;
								if($temp1 >= 1) {				
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
								<?php } ?>
								
								<a href="createtrackee.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
								
								<?php $temp2=$pgr+1;
								if($temp2 <= $lastpage) {				
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
								<?php } ?>
								<?php $temp3=$pgr+2;
								if($temp3 <= $lastpage) {				
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
								<?php } ?>
								
								<?php if($pgr == 1 || $pgr == 2) { 
								if(($pgr+3) <= $lastpage) {
								?>
								<a href="createtrackee.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
								<?php } } ?>
								<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
								<a href="createtrackee.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
								<?php } ?>
								
								<a href="createtrackee.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
								<a href="createtrackee.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
								</div>

<div align="right">Total Pages - <?php echo $lastpage;?></div>
<div align="right">Total Records - <?php echo $numpages;?></div>

</div>

</div>

<?php 

}


















}



?>