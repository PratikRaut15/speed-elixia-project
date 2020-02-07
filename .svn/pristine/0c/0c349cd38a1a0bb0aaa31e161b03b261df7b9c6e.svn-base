<?php

class reports{


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

}
// add client function 
	

// show all clients
	
// view client 

// edit client


function get_trackee()
{
$sql="select * from ".TBL_TRACKEE." where customerno=".$_SESSION['customerno']."";
$result= $this->db->query($sql,__FILE__,__LINE__);
?><?php
while($row= $this->db->fetch_array($result)){
?><option value="<?php echo $row['trackeeid']; ?>"><?php echo $row['tname']; ?></option><?php
}
?><?php
}

function StockSearchBox()
{
?>
<div id="searchboxbg">
		<form action="remarks.php?index=search" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				<tr>
				<th>trackee : </th>
				<td>
				<select >
				<?php $this->get_trackee();?>
				</select>
				</td><td>
				<input id="clientname" type="text" value="" maxlength="30" size="30" name="clientname" issaytactive="1" title="Start typing to Search. Up & Down to choose, Enter to select." listtype="services" autocomplete="off">
				</td>
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
$sql="select * from ".TBL_REMARKS." where 1 and customerno=".$_SESSION['customerno']."";
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
									
									<td title=""><?php echo $row['userid'];?></td>
									
									<td><a href="remarks.php?index=View&remarkid=<?php echo $row['remarkid'];?>" title="View" class="help">
									<img src="images/icon_users.png" width="15px" height="15px" /></a> 
									<?php if($_SESSION['user_type'] != 'User') {?>
									| <a href="remarks.php?index=edit&remarkid=<?php echo $row['remarkid'];?>" title="Edit" class="help">
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








}



?>