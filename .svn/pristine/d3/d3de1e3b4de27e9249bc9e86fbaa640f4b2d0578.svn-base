<?php

class Alerts{
	
	var $course_id;
	var $course_name;
	var $subcategory_id;
	var $subcategory_name;
	var $validity;
	var $Form;
	var $db;
	var $tabName;
	
	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();	
	}
	
	function birthDayAlert()
	{
		
		$mnth = date('m');
		$day = date('d');
		
		$bdate_1 = '-'.$mnth.'-'.$day;
		
		//$sql_2="SELECT * FROM fwup_person_record WHERE MONTH(dob) = MONTH(DATE_ADD(CURDATE(),INTERVAL 2 MONTH))";
		$sql="select * from ".TBL_STUDENT_INFO." where dob like '____".$bdate_1."' ";
		$sql_2="select * from ".TBL_PERSON_RECORD." where dob like '____".$bdate_1."' ";
		
		/*$today = strtotime("Now");
		$leadDate = strtotime("+150 day", $today);
		$sql_2="SELECT * FROM ".TBL_PERSON_RECORD." WHERE dob Between DATE_FORMAT(NOW(),'%m%d') AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY), '%m%d')";*/
		
		if($_SESSION['groups'] != 'Superadmin')
		{
			$sql.=" and type = '".$_SESSION['groups']."' ";
		}
		
		if($_SESSION['groups'] != 'Superadmin')
		{
			$sql_2.=" and type = '".$_SESSION['groups']."' ";
		}
		
		//echo $sql.'<br>';
		//echo $sql_2;  
		
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$result_2= $this->db->query($sql_2,__FILE__,__LINE__);
		$x=1;
		?>
		
				<div class="header">
					<span>Student Birthday's</span>	
				</div>
				<br class="clear"/>
					
				
				<br class="clear"/>
				<div class="content">
			<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">  
			<thead>
			<tr>
				<th>S.No.</th>
				<th>Name</th>
				<th>BirthDay's</th>
				<th>Phone</th>
				<th>Status</th>
			</tr>
			</thead>
			<tbody>
				<?php 		
				while($row= $this->db->fetch_array($result))
				{
					
				?>
				<tr>
					<td><?php echo $x;?></td>
					<td><?php echo $row['student_name'];?></td>
					<td><?php echo date('d-m-Y', strtotime($row['dob']));?></td>
					<td><?php echo substr($row['phone'], 0, 11);?></td>
					<td>Student</td>
				</tr>			
				<?php 
				$x++;
				}
				while($row2= $this->db->fetch_array($result_2))
				{
				
				?>
				<tr>
					<td><?php echo $x;?></td>
					<td><?php echo $row2['name'];?></td>
					<td><?php echo date('d-m-Y', strtotime($row2['dob']));?></td>
					<td><?php echo $row2['phone'];?></td>
					<td>Follow up</td>
				</tr>			
				<?php 
				$x++;

				}
				if($this->db->num_rows($result_2)<1 && $this->db->num_rows($result)<1)
				{
				?>
				<tr><td colspan="5" align="center">No Birthday Today</td></tr>
				<?php 
				}
				?>
			</tbody>	
			</table>
			
			</form>
			</div>
		<?php 
	}
	
	function showpaymenthalfdiv()
	{
		$dt1 = date('Y-m-d');
			$b_date = explode('-',$dt1);
			$paydateto= date('Y-m-d');
			$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-15,$b_date[0]));
			$paydatefrom=$datess;
			
		
		$sql_2="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$sql_3="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$result_2= $this->db->query($sql_2,__FILE__,__LINE__);
		$x2=0;		
		while($row_2= $this->db->fetch_array($result_2))
		{
			$x2=$x2+$row_2['amount'];
		}
		
		$result_3= $this->db->query($sql_3,__FILE__,__LINE__);
		$x3=0;		
		while($row_3= $this->db->fetch_array($result_3))
		{
			$x3=$x3+$row_3['amount'];
		}
	
		$datess2 = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-30,$b_date[0]));
		$paydatefrom2=$datess2;
		$sql_4="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom2."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$sql_5="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom2."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$result_4= $this->db->query($sql_4,__FILE__,__LINE__);
		$x4=0;		
		while($row_4= $this->db->fetch_array($result_4))
		{
			$x4=$x4+$row_4['amount'];
		}
		
		$result_5= $this->db->query($sql_5,__FILE__,__LINE__);
		$x5=0;		
		while($row_5= $this->db->fetch_array($result_5))
		{
			$x5=$x5+$row_5['amount'];
		}
	
	
		$datess3 = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-60,$b_date[0]));
		$paydatefrom3=$datess3;
		$sql_6="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom3."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$sql_7="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom3."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$result_6= $this->db->query($sql_6,__FILE__,__LINE__);
		$x6=0;		
		while($row_6= $this->db->fetch_array($result_6))
		{
			$x6=$x6+$row_6['amount'];
		}
		
		$result_7= $this->db->query($sql_7,__FILE__,__LINE__);
		$x7=0;		
		while($row_7= $this->db->fetch_array($result_7))
		{
			$x7=$x7+$row_7['amount'];
		}
			
			
		$datess4 = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-180,$b_date[0]));
		$paydatefrom4=$datess4;
		$sql_8="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom4."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$sql_9="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom4."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$result_8= $this->db->query($sql_8,__FILE__,__LINE__);
		$x8=0;		
		while($row_8= $this->db->fetch_array($result_8))
		{
			$x8=$x8+$row_8['amount'];
		}
		
		$result_9= $this->db->query($sql_9,__FILE__,__LINE__);
		$x9=0;		
		while($row_9= $this->db->fetch_array($result_9))
		{
			$x9=$x9+$row_9['amount'];
		}	
		
		$datess5 = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-365,$b_date[0]));
		$paydatefrom5=$datess5;
		$sql_10="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom5."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$sql_11="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom5."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$result_10= $this->db->query($sql_10,__FILE__,__LINE__);
		$x10=0;		
		while($row_10= $this->db->fetch_array($result_10))
		{
			$x10=$x10+$row_10['amount'];
		}
		
		$result_11= $this->db->query($sql_11,__FILE__,__LINE__);
		$x11=0;		
		while($row_11= $this->db->fetch_array($result_11))
		{
			$x11=$x11+$row_11['amount'];
		}	
		?>
		
				<div class="header">
					<span>Payment Collected (in Rs.)</span>	
				</div>
				<br class="clear"/>
					
				
				<br class="clear"/>
				<div class="content">
			<form id="form_data" name="form_data" action="" method="post">
			<?php if($_SESSION['user_type'] != 'User') {?>
			<a href="javascript: void(0);" onClick="ClickToPrint('divToPrintAmountperdates');">Print</a>
			<?php } ?> 
			
			<div id="divToPrintAmountperdates">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">  
			<thead>
			<tr>
				<th>&nbsp;</th>
				<th>30 Days</th>
				<th>60 Days</th>
				<th>90 Days</th>
				<th>180 Days</th>
				<th>365 Days</th>
			</tr>
			</thead>
			<tbody>
				
				<tr>
					<th>CC</th>
					<td><?php echo $x3;	?></td>
					<td><?php echo $x5;	?></td>
					<td><?php echo $x7;	?></td>
					<td><?php echo $x9;	?></td>
					<td><?php echo $x11;?></td>
				</tr>			
				
				<tr>
					<th>DZ</th>
					<td><?php echo $x2;	?></td>
					<td><?php echo $x4;	?></td>
					<td><?php echo $x6;	?></td>
					<td><?php echo $x8;	?></td>
					<td><?php echo $x10;?></td>
				</tr>			
				
			</tbody>	
			</table>
			</div>
			</form>
			</div>
			<br />
		<?php 
	}
	
	function showFeeAlerts()
	{
		
		//echo 'yr : '.date('Y').'<br>';
		//echo 'mnth : '.date('m').'<br>';
		//echo 'day : '.date('d').'<br>';
		$dt1 = date('Y-m-d');
		
		$b_date = explode('-',$dt1);
		
		//print_r($b_date);
		
		$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]+15,$b_date[0]));
		
		//echo $datess.'<br>';
		
		$sql="select * from ".TBL_STUDENT_BATCH_INFO." where payment_due='yes' and next_payment_date between '".$dt1."' and '".$datess."' and dispatch='no' order by next_payment_date asc ";
		/*if($_SESSION['groups'] != 'Superadmin')
		{
			$sql.=" and type = '".$_SESSION['groups']."' ";
		}*/
		
		//echo $sql; 
		
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=1;
		?>
		<div class="onecolumn">
				<div class="header">
					<span>Student Due Fee Dates (for next 15 days) </span>
					
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
			<?php if($_SESSION['user_type'] != 'User') {?>
			<a href="javascript: void(0);" onClick="ClickToPrint('divToPrintfeedue');">Print</a>
			<?php } ?>
			
			<div id="divToPrintfeedue">
			  <table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table">
                <thead>
                  <tr>
                    <th width="7%">S.No.</th>
                    <th width="28%">Name</th>
                    <th width="16%">Due Date</th>
                    <th width="16%">Amount Due</th>
					<th width="14%">Phone</th>
					<th width="6%">Type</th>
                    <th width="13%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 	
				$k-0;
				$fee=0;	
				$feedz=0;
				$feecc=0;
				$cntdz=0;
				$cntcc=0;
				while($row= $this->db->fetch_array($result))
				{
					$sql_std="select * from ".TBL_STUDENT_INFO." where student_id='".$row['student_id']."' ";
					if($_SESSION['groups'] != 'Superadmin')
					{
						$sql_std.=" and type = '".$_SESSION['groups']."' ";
					}
					$result_std= $this->db->query($sql_std,__FILE__,__LINE__);
					$row_std= $this->db->fetch_array($result_std);
					
					if($row_std['student_name'] != '')
					{
				?>
                  <tr>
                    <td><?php echo $x;?></td>
                    <td><?php echo $row_std['student_name'];?></td>
                    <td><?php echo  date('d-m-Y', strtotime($row['next_payment_date']));
						
						$sql_isnt="select * from ".STD_INSTALLMENTS_INFO." where student_id='".$row['student_id']."' and paid='no'  and date = '".$row['next_payment_date']."'";
						$result_isnt= $this->db->query($sql_isnt,__FILE__,__LINE__);
						$row_isnt= $this->db->fetch_array($result_isnt);
						if($row_isnt['remark'] != '')
						{
						?>
						<img src="images/icon_star.png" title="<?php echo $row_isnt['remark'];?>" class="help" />
						<?php 
						}
					?></td>
                    <td><?php echo $this->getCurrentInstallment($row_std['student_id']);?></td>
					<td><?php echo $row_std['phone'];?></td>
					<td><?php echo $this->getStudentType($row_std['student_id']);
					$fee=$fee+$this->getCurrentInstallment($row_std['student_id']);
					if($this->getStudentType($row_std['student_id']) == 'DZ')
					{ 
					$feedz=$feedz+$this->getCurrentInstallment($row_std['student_id']);
					$cntdz++;
					}
					else
					{
					$feecc=$feecc+$this->getCurrentInstallment($row_std['student_id']);
					$cntcc++;
					}?></td>	
                    <td><a href="student_details.php?index=studentView&student_id=<?php echo $row_std['student_id'];?>" title="View" class="help"> <img src="images/icon_users.png" width="15px" height="15px" /></a>
                        <?php if($_SESSION['user_type'] != 'User') {?>
                      | <a href="student_details.php?index=editStudent&student_id=<?php echo $row_std['student_id'];?>" title="Edit" class="help"> <img src="images/icon_edit.png" width="15px" height="15px" /></a>
                      <?php } ?></td>
                  </tr>
                  <?php 
					$k++;
					$x++;
					}
				
				}
				if($k == 0)
				{
				?>
                  <tr>
                    <td colspan="7" align="center">No result</td>
                  </tr>
                  <?php 
				}
				?>
                </tbody>
              </table>
			  <br class="clear"/>
			<?php 
			$rowcnt= $x-1;
			
			?>
			<div id="pager" class="pager" <?php if($rowcnt<10) { echo 'style="display:none"';} ?> >

				<img src="tablesorter/pager/icons/first.png" class="first"/>

				<img src="tablesorter/pager/icons/prev.png" class="prev"/>

				<input type="text" class="pagedisplay"/>

				<img src="tablesorter/pager/icons/next.png" class="next"/>

				<img src="tablesorter/pager/icons/last.png" class="last"/>

				<select class="pagesize">

					<option selected="selected"  value="10">10</option>

					<option value="20">20</option>

					<option value="30">30</option>

					<option  value="40">40</option>

					<option  value="50">50</option>

					<option  value="60">60</option>

					<option  value="70">70</option>

					<option  value="80">80</option>

					<option  value="90">90</option>

					<option  value="100">100</option>

					<option  value="10000">All</option>

				</select>

			

			</div>
			
			<br class="clear"/>

			<?php
			 if($k != 0)
			 {
			 if($_SESSION['groups'] == 'Superadmin')
			 {
			 ?>
			 <h4>Total Unpaid Fees of <?php echo $x-1;?> Students is Rs. <?php echo $fee; ?>/- </h4>
			 <?php
			 }
			 if($feedz > 0){ ?>
			 <h4>Total Unpaid Fees of <?php echo $cntdz;?> DZ Students is Rs. <?php echo $feedz; ?>/- </h4>
			 <?php }
			 if($feecc > 0){ ?>
			 <h4>Total Unpaid Fees of <?php echo $cntcc;?> CC Students is Rs. <?php echo $feecc; ?>/- </h4>
			 <?php } ?>
			 <?php 
			 }
			 
			 
			 ?>
			</div>
			</form>
			</div>
			</div>
		<?php 
	}

	function getCurrentInstallment($student_id)
	{
		$sql="select * from ".STD_INSTALLMENTS_INFO." where student_id='".$student_id."' and paid='no' ";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$row= $this->db->fetch_array($result);
		
		return $row['amount'];
	}
	
	function getStudentType($student_id)
	{
		$sql="select * from ".TBL_STUDENT_INFO." where student_id='".$student_id."'";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$row= $this->db->fetch_array($result);
		
		return $row['type'];
	}
	
	function showFeeUnpaid()
	{
		
		//echo 'yr : '.date('Y').'<br>';
		//echo 'mnth : '.date('m').'<br>';
		//echo 'day : '.date('d').'<br>';
		$dt1 = date('Y-m-d');
		
		$b_date = explode('-',$dt1);
		
		//print_r($b_date);
		
		$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-1,$b_date[0]));
		
		//echo $datess.'<br>';
		
		$sql="select * from ".TBL_STUDENT_BATCH_INFO." where payment_due='yes' and next_payment_date between '1900-01-01' and '".$datess."' and dispatch='no' order by next_payment_date asc";
		/*if($_SESSION['groups'] != 'Superadmin')
		{
			$sql.=" and type = '".$_SESSION['groups']."' ";
		}*/
		
		//echo $sql; 
		
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=1;
		?>
		<div class="onecolumn">
				<div class="header">
					<span>Unpaid Fees (Due Date Passed)</span>
					
				</div>
				<br class="clear"/>
					
				
				<br class="clear"/>
				<div class="content">
				<?php 
				if($_SESSION['groups'] == 'Superadmin')
			 	{
				?>
				<div align="right"> 

				<a href="#" onclick="table2CSV($('#search_table_2')); return false;"> 

				<img src="images/csv.png"  alt="Export to CSV"  title="Export to CSV" /> 

				</a> 

				</div>	
				<?php } ?>
			<form id="form_data" name="form_data" action="" method="post">
			<?php if($_SESSION['user_type'] != 'User') {?>
			<a href="javascript: void(0);" onClick="ClickToPrint('divToPrintUnpaid');">Print</a>
			<?php } ?> 
			
			<div id="divToPrintUnpaid">
			<table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_table_2">  
			<thead>
			<tr>
				<th width="6%">S.No.</th>
				<th width="31%">Name</th>
				<th width="14%">Due Date</th>
				<th width="14%">Amount Due</th>
				<th width="17%">Phone</th>
				<th width="4%">Type</th>
				<th width="14%">Action</th>
			</tr>
			</thead>
			<tbody>
				<?php 	
				$k-0;	
				$fee = 0;
				while($row= $this->db->fetch_array($result))
				{
					$sql_std="select * from ".TBL_STUDENT_INFO." where student_id='".$row['student_id']."' ";
					if($_SESSION['groups'] != 'Superadmin')
					{
						$sql_std.=" and type = '".$_SESSION['groups']."' ";
					}
					$result_std= $this->db->query($sql_std,__FILE__,__LINE__);
					$row_std= $this->db->fetch_array($result_std);
					
					if($row_std['student_name'] != '')
					{
				?>
				<tr>
					<td><?php echo $x;?></td>
					<td><?php echo $row_std['student_name'];?></td>
					<td><?php echo  date('d-m-Y', strtotime($row['next_payment_date']));
						
						$sql_isnt="select * from ".STD_INSTALLMENTS_INFO." where student_id='".$row['student_id']."' and paid='no' ";
						$result_isnt= $this->db->query($sql_isnt,__FILE__,__LINE__);
						$row_isnt= $this->db->fetch_array($result_isnt);
						
						if($row_isnt['remark'] != '')
						{
						?>
						<img src="images/icon_star.png" title="<?php echo $row_isnt['remark'];?>" class="help" />
						<?php 
						}
					
					?></td>
					<td><?php echo $this->getCurrentInstallment($row_std['student_id']);?></td>
					<td><?php echo $row_std['phone'];?></td>
					<td><?php echo $this->getStudentType($row_std['student_id']);
					$fee=$fee+$this->getCurrentInstallment($row_std['student_id']);
					if($this->getStudentType($row_std['student_id']) == 'DZ')
					{ 
					$feedz=$feedz+$this->getCurrentInstallment($row_std['student_id']);
					$cntdz++;
					}
					else
					{
					$feecc=$feecc+$this->getCurrentInstallment($row_std['student_id']);
					$cntcc++;
					}?></td>
					<td><a href="student_details.php?index=studentView&student_id=<?php echo $row_std['student_id'];?>" title="View" class="help">

					<img src="images/icon_users.png" width="15px" height="15px" /></a> 

					<?php if($_SESSION['user_type'] != 'User') {?>

					| <a href="student_details.php?index=editStudent&student_id=<?php echo $row_std['student_id'];?>" title="Edit" class="help">

					<img src="images/icon_edit.png" width="15px" height="15px" /></a>

					<?php } ?></td>
				</tr>			
				<?php 
					$k++;
					$x++;
					}
				
				}
				if($k == 0)
				{
				?>
				<tr><td colspan="7" align="center">No result</td></tr>
				<?php 
				}
				?>
			</tbody>	
			</table>
			
			<br class="clear"/>
			<?php 
			$rowcnt= $x-1;
			
			?>
			<div id="pager_2" class="pager" <?php if($rowcnt<10) { echo 'style="display:none"';} ?> >

				<img src="tablesorter/pager/icons/first.png" class="first"/>

				<img src="tablesorter/pager/icons/prev.png" class="prev"/>

				<input type="text" class="pagedisplay"/>

				<img src="tablesorter/pager/icons/next.png" class="next"/>

				<img src="tablesorter/pager/icons/last.png" class="last"/>

				<select class="pagesize">
				
					<option selected="selected" value="10">10</option>

					<option value="20">20</option>

					<option value="30">30</option>

					<option  value="40">40</option>

					<option  value="50">50</option>

					<option  value="60">60</option>

					<option  value="70">70</option>

					<option  value="80">80</option>

					<option  value="90">90</option>

					<option  value="100">100</option>

					<option  value="10000">All</option>

				</select>
			</div>
			
			<br class="clear"/>
			<?php
			 if($k != 0)
			 {
			 if($_SESSION['groups'] == 'Superadmin')
			 {
			 ?>
			 <h4>Total Unpaid Fees of <?php echo $x-1;?> Students is Rs. <?php echo $fee; ?>/- </h4>
			 <?php
			 }
			 if($feedz > 0){ ?>
			 <h4>Total Unpaid Fees of <?php echo $cntdz;?> DZ Students is Rs. <?php echo $feedz; ?>/- </h4>
			 <?php }
			 if($feecc > 0){ ?>
			 <h4>Total Unpaid Fees of <?php echo $cntcc;?> CC Students is Rs. <?php echo $feecc; ?>/- </h4>
			 <?php } ?>
			 <?php 
			 }
			 ?>
			 </div>
			</form>
			</div>
			</div>
		<?php 
	}
	
	function PersonSearchBox()
	{
		?>
			
			<div id="searchboxbg">
			<form action="" id="search_form" name="search_form" method="post">
				<table width="100%" class="data">
				
				<tr>
				<th>Course :</th>
				<th><select name="course" id="course" style="width:130px"
					 onchange="javascript: document.getElementById('div_branch').innerHTML = '';
					  alrt.showBranches(this.value,{target:'div_branch'} )">
					 <option value="">-Select-</option>
					 <?php 
						$sql_course="select * from ".TBL_COURSE;
						$result_course= $this->db->query($sql_course,__FILE__,__LINE__);
						while($row_course= $this->db->fetch_array($result_course))
						{
						?>
						<option value="<?php echo $row_course['course_id'];?>"
						<?php if($_POST['course'] == $row_course['course_id']) { echo 'selected="selected"';} ?> >
						<?php echo $row_course['course_name'];?></option>
						<?php 
						}
					 ?>
					 </select>
				</th>
				<th>Branch :</th>
				<th> <span id="div_branch">
					 <select name="branch" id="branch" style="width:130px;"
					 onchange="javascript: document.getElementById('div_sub_branch').innerHTML = '';
										  alrt.showSubBranches(this.value,{target:'div_sub_branch'} )">
					 <option value="">-Select-</option>
					 <?php 
						$sql_branches="select * from ".TBL_BRANCH." where course_id='".$_POST['course']."'";
						$result_branches= $this->db->query($sql_branches,__FILE__,__LINE__);
						while($row_branches= $this->db->fetch_array($result_branches))
						{
						?>
						<option value="<?php echo $row_branches['branch_id'];?>"
						<?php if($_POST['branch'] == $row_branches['branch_id']) { echo 'selected="selected"';} ?> >
						<?php echo $row_branches['branch_name'];?></option>
						<?php 
						}
					 ?>
					 </select>
					 </span></th>
				</tr>
				
				<tr>
				<th>Software :</th>
				<th> <span id="div_sub_branch">
					 <select name="sub_branch" id="sub_branch" style="width:130px;">
					 <option value="">-Select-</option>
					 <?php 
						$sql_subbranch="select * from ".TBL_SUB_BRANCH." where branch_id='".$_POST['branch']."'";
						$result_subbranch= $this->db->query($sql_subbranch,__FILE__,__LINE__);
						while($row_subbranch= $this->db->fetch_array($result_subbranch))
						{
						?>
						<option value="<?php echo $row_subbranch['sub_branch_id'];?>"
						<?php if($_POST['sub_branch'] == $row_subbranch['sub_branch_id']) { echo 'selected="selected"';} ?> >
						<?php echo $row_subbranch['sub_branch_name'];?></option>
						<?php 
						}
					 ?>
					 </select>
					 </span>
				</th>
				<th colspan="2"></th>
				<!--<th>Type :</th>
				<td><select name="type" id="type" style="width:130px;">
					<option value="">-Select-</option>
					<option value="DZ" <?php if($_POST['type'] == 'DZ') { echo 'selected="selected"';} ?>>DZ</option>
					<option value="CC" <?php if($_POST['type'] == 'CC') { echo 'selected="selected"';} ?>>CC</option>
					</select>
				</td>-->
				</tr>
				<tr>
				<th>Walk-in Date From:</th>
				<th><input type="text" id="datefrom" name="datefrom" title="From" class="datepicker" value="<?php echo $_POST['datefrom'];?>" readonly="true" />
				<a href="javascript: void(0);" onclick="javascript: document.getElementById('datefrom').value='';"><img src="images/icon_cross.png"  alt="clear"  title="Clear Date" class="help" /></a></th>
				<th>Walk-in Date To:</th>
				<th><input type="text" id="dateto" name="dateto" title="To" class="datepicker"  value="<?php echo $_POST['dateto'];?>" readonly="true" />
				<a href="javascript: void(0);" onclick="javascript: document.getElementById('dateto').value='';"><img src="images/icon_cross.png"  alt="clear"  title="Clear Date" class="help" /></a></th>
				</tr>
				
				<!--<tr>
				
				<th>Date Of Birth :</th>
				<td><input type="text" id="dob" name="dob" title="Date of Birth" class="datepicker"  value="<?php echo $_POST['dob'];?>" readonly="true" />
				<a href="javascript: void(0);" onclick="javascript: document.getElementById('dob').value='';"><img src="images/icon_cross.png"  alt="clear"  title="Clear Date" class="help" /></a></td>
				</tr>-->
				
				<tr>
				<th colspan="4"><input type="submit" name="search" id="search" value="Search" /></th>
				</tr>
				</table>			
			</form>
			</div>
		
			<br class="clear"/>
		<?php 
	}
	
	function SearchPersonRecord($name='',$course='',$branch='',$sub_branch='',$datefrom='',$dateto='',$status='',$dob='',$type='')
	{
		
		if($datefrom == '')
		{
			$datefrom='1900-01-01';
		}	
		if($dateto == '')
		{
			$dateto='2999-12-31';
		}
		
		$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."' ";
		if($name)
		{
			$sql .= " and name like '".$name."%'";
		}
		if($course)
		{
			$sql .= " and course like '".$course."%'";
		}
		if($branch)
		{
			$sql .= " and branch like '".$branch."%'";
		}
		if($sub_branch)
		{
			$sql .= " and sub_branch like '".$sub_branch."%'";
		}
		if($type)
		{
			$sql .= " and type ='".$type."'";
		}
		if($dob)
		{
			$sql .= " and dob ='".$dob."'";
		}

		$result= $this->db->query($sql,__FILE__,__LINE__);
		$a= $this->db->num_rows($result);
		
		$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."' ";
		if($name)
		{
			$sql .= " and name like '".$name."%'";
		}
		if($course)
		{
			$sql .= " and course like '".$course."%'";
		}
		if($branch)
		{
			$sql .= " and branch like '".$branch."%'";
		}
		if($sub_branch)
		{
			$sql .= " and sub_branch like '".$sub_branch."%'";
		}
		if($type)
		{
			$sql .= " and type ='".$type."'";
		}
		if($dob)
		{
			$sql .= " and dob ='".$dob."'";
		}
		
		$sql .= " and status ='Follow Up'";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$b= $this->db->num_rows($result);
		
		$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."' ";
		if($name)
		{
			$sql .= " and name like '".$name."%'";
		}
		if($course)
		{
			$sql .= " and course like '".$course."%'";
		}
		if($branch)
		{
			$sql .= " and branch like '".$branch."%'";
		}
		if($sub_branch)
		{
			$sql .= " and sub_branch like '".$sub_branch."%'";
		}
		if($type)
		{
			$sql .= " and type ='".$type."'";
		}
		if($dob)
		{
			$sql .= " and dob ='".$dob."'";
		}
		
		$sql .= " and status ='Close'";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$c= $this->db->num_rows($result);
		
		$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."' ";
		if($name)
		{
			$sql .= " and name like '".$name."%'";
		}
		if($course)
		{
			$sql .= " and course like '".$course."%'";
		}
		if($branch)
		{
			$sql .= " and branch like '".$branch."%'";
		}
		if($sub_branch)
		{
			$sql .= " and sub_branch like '".$sub_branch."%'";
		}
		if($type)
		{
			$sql .= " and type ='".$type."'";
		}
		if($dob)
		{
			$sql .= " and dob ='".$dob."'";
		}
		
		$sql .= " and status ='Dead'";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$d= $this->db->num_rows($result);
		?>
		
			
			<form id="form_data" name="form_data" action="" method="post">
			<div class="onecolumn">
				<div class="header">
					<span>Walk-in Records Search List</span>
				</div>
				
				<br class="clear"/>
				<?php $this->PersonSearchBox(); ?>
				<div class="content">
			<div align="right">
			<div class="switch" style="width:200px">
						<table width="200px" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="Bar" style="width:50px"/>
								</td>
								<td>
									<input type="button" id="chart_area" name="chart_area" class="middle_switch" value="Area" style="width:50px"/>
								</td>
								<td>
									<input type="button" id="chart_pie" name="chart_pie" class="middle_switch" value="Pie" style="width:50px"/>
								</td>
								<td>
									<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Line" style="width:50px"/>
								</td>
							</tr>
						</tbody>
						</table>
					</div>
					</div>
					<?php if($_SESSION['user_type'] != 'User') {?>
					<a class="help" href="printpage.php?index=personrecord&name=<?php echo $name;?>&course=<?php echo $course;?>&branch=<?php echo $branch;?>&sub_branch=<?php echo $sub_branch;?>&datefrom=<?php echo $datefrom;?>&dateto=<?php echo $dateto; ?>&status=<?php echo $status;?>&dob=<?php echo $dob;?>&type=<?php echo $type;?>" target="_blank" title="Show Preview of report" >Pre-View</a>
					<?php }?>
					<div id="chart_wrapper" class="chart_wrapper"> </div>
					
					<div id="divToPrintperson">
					
				
						<table id="graph_data" class="data" rel="bar" cellpadding="0" cellspacing="0" width="100%">
						<caption>
						<?php 
						if($datefrom == '1900-01-01' && $dateto=='2999-12-31')
						echo 'track record all time';
						else
						echo 'track record from '.$datefrom.' to '.$dateto;
						?></caption>
						<thead>
							<tr>
								<td class="no_input">&nbsp;</td>
								<th>Total Walk-in</th>
								<th>Follow up</th>
								<th>Closed</th>
								<th>Dead</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>
								<th>CC</th>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC' ";
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$s= $this->db->num_rows($result);
								
								echo $s;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC'  and status ='Follow Up'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$t= $this->db->num_rows($result);
								
								echo $t;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC'  and status ='Close'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$u= $this->db->num_rows($result);
								
								echo $u;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC'  and status ='Dead'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$v= $this->db->num_rows($result);
								
								echo $v;
								?>
								</td>
							</tr>
							
							<tr>
								<th>DZ</th>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$w= $this->db->num_rows($result);
								
								echo $w;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'  and status ='Follow Up'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$x= $this->db->num_rows($result);
								
								echo $x;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'  and status ='Close'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$y= $this->db->num_rows($result);
								
								echo $y;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'  and status ='Dead'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$z= $this->db->num_rows($result);
								
								echo $z;
								?>
								</td>
							</tr>
							
						</tbody>
						</table>
						<table class="data" rel="bar" cellpadding="0" cellspacing="0" width="100%" align="center">
						<tr align="center">
						<th width="11%">Total</th>
						<td width="32%"><?php echo $a;?></td>
						<td width="23%"><?php echo $b;?></td>
						<td width="19%"><?php echo $c;?></td>	
						<td width="15%"><?php echo $d;?></td>	
						</tr>
						</table>
						</div>	
						</div>
					</form>
			<br class="clear"/>
			
			
			
		<?php 
	}
	
	function SearchPersonRecordonpopup($name='',$course='',$branch='',$sub_branch='',$datefrom='',$dateto='',$status='',$dob='',$type='')
	{
		
		if($datefrom == '')
		{
			$datefrom='1900-01-01';
		}	
		if($dateto == '')
		{
			$dateto='2999-12-31';
		}
		
		$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."' ";
		if($name)
		{
			$sql .= " and name like '".$name."%'";
		}
		if($course)
		{
			$sql .= " and course like '".$course."%'";
		}
		if($branch)
		{
			$sql .= " and branch like '".$branch."%'";
		}
		if($sub_branch)
		{
			$sql .= " and sub_branch like '".$sub_branch."%'";
		}
		if($type)
		{
			$sql .= " and type ='".$type."'";
		}
		if($dob)
		{
			$sql .= " and dob ='".$dob."'";
		}

		$result= $this->db->query($sql,__FILE__,__LINE__);
		$a= $this->db->num_rows($result);
		
		$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."' ";
		if($name)
		{
			$sql .= " and name like '".$name."%'";
		}
		if($course)
		{
			$sql .= " and course like '".$course."%'";
		}
		if($branch)
		{
			$sql .= " and branch like '".$branch."%'";
		}
		if($sub_branch)
		{
			$sql .= " and sub_branch like '".$sub_branch."%'";
		}
		if($type)
		{
			$sql .= " and type ='".$type."'";
		}
		if($dob)
		{
			$sql .= " and dob ='".$dob."'";
		}
		
		$sql .= " and status ='Follow Up'";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$b= $this->db->num_rows($result);
		
		$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."' ";
		if($name)
		{
			$sql .= " and name like '".$name."%'";
		}
		if($course)
		{
			$sql .= " and course like '".$course."%'";
		}
		if($branch)
		{
			$sql .= " and branch like '".$branch."%'";
		}
		if($sub_branch)
		{
			$sql .= " and sub_branch like '".$sub_branch."%'";
		}
		if($type)
		{
			$sql .= " and type ='".$type."'";
		}
		if($dob)
		{
			$sql .= " and dob ='".$dob."'";
		}
		
		$sql .= " and status ='Close'";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$c= $this->db->num_rows($result);
		
		$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."' ";
		if($name)
		{
			$sql .= " and name like '".$name."%'";
		}
		if($course)
		{
			$sql .= " and course like '".$course."%'";
		}
		if($branch)
		{
			$sql .= " and branch like '".$branch."%'";
		}
		if($sub_branch)
		{
			$sql .= " and sub_branch like '".$sub_branch."%'";
		}
		if($type)
		{
			$sql .= " and type ='".$type."'";
		}
		if($dob)
		{
			$sql .= " and dob ='".$dob."'";
		}
		
		$sql .= " and status ='Dead'";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$d= $this->db->num_rows($result);
		?>
		
			
			<form id="form_data" name="form_data" action="" method="post">
			<div class="onecolumn">
				<div class="header">
					<span>Walk-in Records List</span>
					<div class="switch" style="width:200px" id="switch">
						<table width="200px" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="Bar" style="width:50px"/>
								</td>
								<td>
									<input type="button" id="chart_area" name="chart_area" class="middle_switch" value="Area" style="width:50px"/>
								</td>
								<td>
									<input type="button" id="chart_pie" name="chart_pie" class="middle_switch" value="Pie" style="width:50px"/>
								</td>
								<td>
									<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Line" style="width:50px"/>
								</td>
							</tr>
						</tbody>
						</table>
					</div>
				</div>
				
				<br class="clear"/>
				
				<div class="content">
			
					<div id="chart_wrapper" class="chart_wrapper"> </div>
				
					
					<div id="divToPrintperson">
					
				
						<table id="graph_data" class="data" rel="bar" cellpadding="0" cellspacing="0" width="100%">
						<caption>
						<?php 
						if($datefrom == '1900-01-01' && $dateto=='2999-12-31')
						echo 'track record all time';
						else
						echo 'track record from '.$datefrom.' to '.$dateto;
						?></caption>
						<thead>
							<tr>
								<td class="no_input">&nbsp;</td>
								<th>Total Walk-in</th>
								<th>Follow up</th>
								<th>Closed</th>
								<th>Dead</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>
								<th>CC</th>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC' ";
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$s= $this->db->num_rows($result);
								
								echo $s;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC'  and status ='Follow Up'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$t= $this->db->num_rows($result);
								
								echo $t;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC'  and status ='Close'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$u= $this->db->num_rows($result);
								
								echo $u;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC'  and status ='Dead'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$v= $this->db->num_rows($result);
								
								echo $v;
								?>
								</td>
							</tr>
							
							<tr>
								<th>DZ</th>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$w= $this->db->num_rows($result);
								
								echo $w;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'  and status ='Follow Up'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$x= $this->db->num_rows($result);
								
								echo $x;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'  and status ='Close'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$y= $this->db->num_rows($result);
								
								echo $y;
								?>
								</td>
								<td>
								<?php 
									
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'  and status ='Dead'";
								
								if($name)
								{
									$sql .= " and name like '".$name."%'";
								}
								if($course)
								{
									$sql .= " and course like '".$course."%'";
								}
								if($branch)
								{
									$sql .= " and branch like '".$branch."%'";
								}
								if($sub_branch)
								{
									$sql .= " and sub_branch like '".$sub_branch."%'";
								}
								if($dob)
								{
									$sql .= " and dob ='".$dob."'";
								}
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$z= $this->db->num_rows($result);
								
								echo $z;
								?>
								</td>
							</tr>
							
						</tbody>
						</table>
						<table class="data" rel="bar" cellpadding="0" cellspacing="0" width="100%" align="center">
						<tr align="center">
						<th width="11%">Total</th>
						<td width="32%"><?php echo $a;?></td>
						<td width="23%"><?php echo $b;?></td>
						<td width="19%"><?php echo $c;?></td>	
						<td width="15%"><?php echo $d;?></td>	
						</tr>
						</table>
						</div>	
						</div>
					</form>
			<br class="clear"/>
			
			
			
		<?php 
	}


	function showBranches($course_id)
	{
		ob_start();
	?>
		<select name="branch" id="branch" style="width:130px;"
		 onchange="javascript: document.getElementById('div_sub_branch').innerHTML = '';
							  alrt.showSubBranches(this.value,{target:'div_sub_branch'} )">
		 <option value="">-Select-</option>
		 <?php 
			$sql_branches="select * from ".TBL_BRANCH." where course_id='".$course_id."'";
			$result_branches= $this->db->query($sql_branches,__FILE__,__LINE__);
			while($row_branches= $this->db->fetch_array($result_branches))
			{
			?>
			<option value="<?php echo $row_branches['branch_id'];?>">
			<?php echo $row_branches['branch_name'];?></option>
			<?php 
			}
		 ?>
		 </select>
	<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function showSubBranches($branch_id)
	{
		ob_start();
	?>
		<select name="sub_branch" id="sub_branch" style="width:130px;">
		 <option value="">-Select-</option>
		 <?php 
			$sql_subbranch="select * from ".TBL_SUB_BRANCH." where branch_id='".$branch_id."'";
			$result_subbranch= $this->db->query($sql_subbranch,__FILE__,__LINE__);
			while($row_subbranch= $this->db->fetch_array($result_subbranch))
			{
			?>
			<option value="<?php echo $row_subbranch['sub_branch_id'];?>">
			<?php echo $row_subbranch['sub_branch_name'];?></option>
			<?php 
			}
		 ?>
		 </select>
	<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function PaymntSearchBox()
	{
		?>
			
			<div id="searchboxbg">
			<form action="adminReport.php" id="search_form_paymnt" name="search_form_paymnt" method="post">
				<table width="100%" class="data">
				<?php
				$dt1 = date('Y-m-d');
		
				$b_date = explode('-',$dt1);
				$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-30,$b_date[0]));
				
				if(isset($_POST['paydatefrom']))
				{
					$d1 = $_POST['paydatefrom'];
				}
				else
				{
					$d1 = $datess;
				}
				
				if(isset($_POST['paydateto']))
				{
					$d2 = $_POST['paydateto'];
				}
				else
				{
					$d2 = date('Y-m-d');
				}
				?>
				<tr>
				<th>Date From:</th>
				<th><input type="text" id="paydatefrom" name="paydatefrom" title="From" class="datepicker" value="<?php echo $d1;?>" readonly="true" /></th>
				<th>Date To:</th>
				<th><input type="text" id="paydateto" name="paydateto" title="To" class="datepicker"  value="<?php echo $d2;?>" readonly="true" /></th>
				
				<th><input type="submit" name="search" id="search" value="Search" /></th>
				</tr>
				</table>			
			</form>
			</div>
		
			<br class="clear"/>
		<?php 
	}
	
	function SearchPaymentCollected($paydatefrom='',$paydateto='')
	{
		
		if($paydatefrom == '')
		{
			$dt1 = date('Y-m-d');
		
			$b_date = explode('-',$dt1);
			$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-30,$b_date[0]));
			
			$paydatefrom=$datess;
		}	
		if($paydateto == '')
		{
			$paydateto= date('Y-m-d');
		}
		
		$sql="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$sql_2="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$sql_2_newstd="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' and a.installment_num=1 ";
		
		$sql_3="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$sql_3_newstd="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' and a.installment_num=1 ";
		
		/*echo $sql.'<br>';
		echo $sql_2.'<br>';
		echo $sql_3.'<br>';*/
		
		$resultx= $this->db->query($sql,__FILE__,__LINE__);
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=0;		
		while($row= $this->db->fetch_array($result))
		{
			$x=$x+$row['amount'];
		}
		
		
		$result_2= $this->db->query($sql_2,__FILE__,__LINE__);
		$x2=0;		
		while($row_2= $this->db->fetch_array($result_2))
		{
			$x2=$x2+$row_2['amount'];
		}
		
		$result_2_newstd= $this->db->query($sql_2_newstd,__FILE__,__LINE__);
		$x2_newstd=0;		
		while($row_2_newstd= $this->db->fetch_array($result_2_newstd))
		{
			$x2_newstd=$x2_newstd+$row_2_newstd['amount'];
		}
		
		$result_3= $this->db->query($sql_3,__FILE__,__LINE__);
		$x3=0;		
		while($row_3= $this->db->fetch_array($result_3))
		{
			$x3=$x3+$row_3['amount'];
		}
		
		$result_3_newstd= $this->db->query($sql_3_newstd,__FILE__,__LINE__);
		$x3_newstd=0;		
		while($row_3_newstd= $this->db->fetch_array($result_3_newstd))
		{
			$x3_newstd=$x3_newstd+$row_3_newstd['amount'];
		}
		?>
		<div class="onecolumn">
				<div class="header">
					<span>Payment Collected</span>
				</div>
				<br class="clear"/>
					
				
				<br class="clear"/>
				<?php $this->PaymntSearchBox(); ?>
				<div class="content">
				
				
			<?php if($_SESSION['user_type'] != 'User') {?>
			<a href="printpage.php?index=paymentcollected&paydatefrom=<?php echo $paydatefrom;?>&paydateto=<?php echo $paydateto;?>" target="_blank" >Print-View</a>
			<?php } ?>
			
			<div id="divToPrintAmountt">
			<table class="data" width="100%" cellspacing="0" >  
			<tr>
				<th>Total Amount Collected :</th><td>Rs. <?php echo $x; ?> /-</td>
			</tr>
			<tr>
				<th>Amount Collected For DZ :</th>
				<td><div>Rs. <?php echo $x2; ?> /-</div>
					<div>From New Student: Rs.<?php echo $x2_newstd; ?> /-</div>
					<div>From Old Student: Rs.<?php echo $x2-$x2_newstd; ?> /-</div>
				</td>
			</tr>
			<tr>
				<th>Amount Collected For CC :</th>
				<td><div>Rs. <?php echo $x3; ?> /-</div>
					<div>From New Student: Rs.<?php echo $x3_newstd; ?> /-</div>
					<div>From Old Student: Rs.<?php echo $x3-$x3_newstd; ?> /-</div>
				</td>
			</tr>
			</table>
			</div>
			<br class="clear" />
			
			<form id="form_data" name="form_data" action="" method="post">
			
			
			<div id="divToPrintfeeduess">
			  <table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_tabletobeoaid">
                <thead>
                  <tr>
                    <th width="7%">S.No.</th>
                    <th width="28%">Name</th>
                    <th width="16%">Due Date</th>
                    <th width="16%">Amount Due</th>
					<th width="14%">Phone</th>
					<th width="6%">Type</th>
                    <th width="13%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 	
				$k=0;
				while($row= $this->db->fetch_array($resultx))
				{
				?>
                  <tr>
                    <td><?php echo $k+1;?></td>
                    <td><?php echo $row['student_name'];?></td>
                    <td><?php echo  date('d-m-Y', strtotime($row['date']));if($row['remark'] != ''){?><img src="images/icon_star.png" title="<?php echo $row['remark'];?>" class="help" /><?php }?></td>
                    <td><?php echo $this->getCurrentInstallment($row['student_id']);?></td>
					<td><?php echo $row['phone'];?></td>
					<td><?php echo $this->getStudentType($row['student_id']);?></td>	
                    <td><a href="student_details.php?index=studentView&student_id=<?php echo $row['student_id'];?>" title="View" class="help"> <img src="images/icon_users.png" width="15px" height="15px" /></a>
                        <?php if($_SESSION['user_type'] != 'User') {?>
                      | <a href="student_details.php?index=editStudent&student_id=<?php echo $row['student_id'];?>" title="Edit" class="help"> <img src="images/icon_edit.png" width="15px" height="15px" /></a>
                      <?php } ?></td>
                  </tr>
                  <?php 
					$k++;
				}
				if($k == 0)
				{
				?>
                  <tr>
                    <td colspan="7" align="center">No result</td>
                  </tr>
                  <?php 
				}
				?>
                </tbody>
              </table>
			  <br class="clear"/>
			<?php 
			$rowcnt= $k-1;
			
			?>
			<div id="pager22" class="pager" <?php if($rowcnt<10) { echo 'style="display:none"';} ?> >

				<img src="tablesorter/pager/icons/first.png" class="first"/>

				<img src="tablesorter/pager/icons/prev.png" class="prev"/>

				<input type="text" class="pagedisplay"/>

				<img src="tablesorter/pager/icons/next.png" class="next"/>

				<img src="tablesorter/pager/icons/last.png" class="last"/>

				<select class="pagesize">

					<option selected="selected"  value="10">10</option>

					<option value="20">20</option>

					<option value="30">30</option>

					<option  value="40">40</option>

					<option  value="50">50</option>

					<option  value="60">60</option>

					<option  value="70">70</option>

					<option  value="80">80</option>

					<option  value="90">90</option>

					<option  value="100">100</option>

					<option  value="10000">All</option>

				</select>

			

			</div>
			<div align="right">Total Records: <?php echo $k;?></div>
			<br class="clear"/>

			</div>
			</form>
						
			</div>
			</div>
		<?php 
	}
	
	function SearchPaymentCollectedprintview($paydatefrom='',$paydateto='')
	{
		
		if($paydatefrom == '')
		{
			$dt1 = date('Y-m-d');
		
			$b_date = explode('-',$dt1);
			$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-30,$b_date[0]));
			
			$paydatefrom=$datess;
		}	
		if($paydateto == '')
		{
			$paydateto= date('Y-m-d');
		}
		
		$sql="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$sql_2="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		$sql_3="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='yes' ";
		
		/*echo $sql.'<br>';
		echo $sql_2.'<br>';
		echo $sql_3.'<br>';*/
		
		$resultx= $this->db->query($sql,__FILE__,__LINE__);
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=0;		
		while($row= $this->db->fetch_array($result))
		{
			$x=$x+$row['amount'];
		}
		
		
		$result_2= $this->db->query($sql_2,__FILE__,__LINE__);
		$x2=0;		
		while($row_2= $this->db->fetch_array($result_2))
		{
			$x2=$x2+$row_2['amount'];
		}
		
		$result_3= $this->db->query($sql_3,__FILE__,__LINE__);
		$x3=0;		
		while($row_3= $this->db->fetch_array($result_3))
		{
			$x3=$x3+$row_3['amount'];
		}
		?>
		<div class="onecolumn">
				<div class="header">
					<span>Payment Collected from <?php echo $paydatefrom;?> to <?php echo $paydateto;?></span>
				</div>
				<br class="clear"/>

				<div class="content">


			<table class="data" width="100%" cellspacing="0" >  
			<tr>
				<th>Total Amount Collected :</th><td>Rs. <?php echo $x; ?> /-</td>
			</tr>
			<tr>
				<th>Amount Collected For DZ :</th><td>Rs. <?php echo $x2; ?> /-</td>
			</tr>
			<tr>
				<th>Amount Collected For CC :</th><td>Rs. <?php echo $x3; ?> /-</td>
			</tr>
			</table>

			<br class="clear" />
			
			<form id="form_data" name="form_data" action="" method="post">

			  <table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_tabletobeoaid">
                <thead>
                  <tr>
                    <th width="9%">S.No.</th>
                    <th width="33%">Name</th>
                    <th width="18%">Due Date</th>
                    <th width="17%">Amount Due</th>
					<th width="15%">Phone</th>
					<th width="8%">Type</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 	
				$k=0;
				while($row= $this->db->fetch_array($resultx))
				{
				?>
                  <tr>
                    <td><?php echo $k+1;?></td>
                    <td><?php echo $row['student_name'];?></td>
                    <td><?php echo  date('d-m-Y', strtotime($row['date']));if($row['remark'] != ''){?><img src="images/icon_star.png" title="<?php echo $row['remark'];?>" class="help" /><?php }?></td>
                    <td><?php echo $this->getCurrentInstallment($row['student_id']);?></td>
					<td><?php echo $row['phone'];?></td>
					<td><?php echo $this->getStudentType($row['student_id']);?></td>	
                  </tr>
                  <?php 
					$k++;
				}
				if($k == 0)
				{
				?>
                  <tr>
                    <td colspan="7" align="center">No result</td>
                  </tr>
                  <?php 
				}
				?>
                </tbody>
              </table>
			  <br class="clear"/>
			<?php 
			$rowcnt= $k-1;
			
			?>
			<br class="clear"/>

			</form>
						
			</div>
			</div>
		<?php 
	}
	
	function showGraph()
	{
	?>
	<!-- Begin graph window -->
			<div class="onecolumn">
				<div class="header">
					<span>Student Stats</span>
					<div class="switch" style="width:200px">
						<table width="200px" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="Bar" style="width:50px"/>
								</td>
								<td>
									<input type="button" id="chart_area" name="chart_area" class="middle_switch" value="Area" style="width:50px"/>
								</td>
								<td>
									<input type="button" id="chart_pie" name="chart_pie" class="middle_switch" value="Pie" style="width:50px"/>
								</td>
								<td>
									<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Line" style="width:50px"/>
								</td>
							</tr>
						</tbody>
						</table>
					</div>
				</div>
				<br class="clear"/>
				<div class="content">
				
					<div id="chart_wrapper" class="chart_wrapper"></div>
					<br class="clear"/>
					<br class="clear"/>
					<form id="form_data" name="form_data" action="" method="post">
					
						<table id="graph_data" class="data" rel="bar" cellpadding="0" cellspacing="0" width="100%">
						<caption>track reacord of Past 15 days</caption>
						<thead>
							<tr>
								<td class="no_input">&nbsp;</td>
								<th>Total Walk-in</th>
								<th>Follow up</th>
								<th>Closed</th>
								<th>Dead</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>
								<th>CC</th>
								<td>
								<?php 
									$dt1 = date('Y-m-d');
									$b_date = explode('-',$dt1);
									$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-15,$b_date[0]));
									$datefrom=$datess;
									$dateto= date('Y-m-d');
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC'";
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$a= $this->db->num_rows($result);
								
								echo $a;
								?>
								</td>
								<td>
								<?php 
									$dt1 = date('Y-m-d');
									$b_date = explode('-',$dt1);
									$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-15,$b_date[0]));
									$datefrom=$datess;
									$dateto= date('Y-m-d');
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC'  and status ='Follow Up'";
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$a= $this->db->num_rows($result);
								
								echo $a;
								?>
								</td>
								<td>
								<?php 
									$dt1 = date('Y-m-d');
									$b_date = explode('-',$dt1);
									$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-15,$b_date[0]));
									$datefrom=$datess;
									$dateto= date('Y-m-d');
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC'  and status ='Close'";
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$a= $this->db->num_rows($result);
								
								echo $a;
								?>
								</td>
								<td>
								<?php 
									$dt1 = date('Y-m-d');
									$b_date = explode('-',$dt1);
									$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-15,$b_date[0]));
									$datefrom=$datess;
									$dateto= date('Y-m-d');
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'CC'  and status ='Dead'";
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$a= $this->db->num_rows($result);
								
								echo $a;
								?>
								</td>
							</tr>
							
							<tr>
								<th>DZ</th>
								<td>
								<?php 
									$dt1 = date('Y-m-d');
									$b_date = explode('-',$dt1);
									$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-15,$b_date[0]));
									$datefrom=$datess;
									$dateto= date('Y-m-d');
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'";
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$a= $this->db->num_rows($result);
								
								echo $a;
								?>
								</td>
								<td>
								<?php 
									$dt1 = date('Y-m-d');
									$b_date = explode('-',$dt1);
									$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-15,$b_date[0]));
									$datefrom=$datess;
									$dateto= date('Y-m-d');
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'  and status ='Follow Up'";
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$a= $this->db->num_rows($result);
								
								echo $a;
								?>
								</td>
								<td>
								<?php 
									$dt1 = date('Y-m-d');
									$b_date = explode('-',$dt1);
									$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-15,$b_date[0]));
									$datefrom=$datess;
									$dateto= date('Y-m-d');
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'  and status ='Close'";
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$a= $this->db->num_rows($result);
								
								echo $a;
								?>
								</td>
								<td>
								<?php 
									$dt1 = date('Y-m-d');
									$b_date = explode('-',$dt1);
									$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]-15,$b_date[0]));
									$datefrom=$datess;
									$dateto= date('Y-m-d');
								$sql="select * from ".TBL_PERSON_RECORD." where date between '".$datefrom."' and '".$dateto."'  and type = 'DZ'  and status ='Dead'";
								
								$result= $this->db->query($sql,__FILE__,__LINE__);
								$a= $this->db->num_rows($result);
								
								echo $a;
								?>
								</td>
							</tr>
							
						</tbody>
						</table>
						<div id="chart_wrapper" class="chart_wrapper"></div>
					<!-- End bar chart table-->
					</form>
				</div>
			</div>
			<!-- End graph window -->
	
	<?php 
	}
	
	function emailReports($runat)
	{
	
		switch($runat){
			case 'local' :
			
			
		$sql="select * from ".TBL_PERSON_RECORD." where timestamp like '".date('Y-m-d')."%' and counneller_id='".$_SESSION['user_id']."'";
		$sql2="select * from ".TBL_STUDENT_INFO." where timestamp like '".date('Y-m-d')."%' and counneller_id='".$_SESSION['user_id']."' ";
		$sql3="select * from ".STD_INSTALLMENTS_INFO." where paid_date='".date('Y-m-d')."' and counneller_id='".$_SESSION['user_id']."' ";
		$sql4="SELECT * FROM ".TBL_REMARK." WHERE `timestamp` like '".date('Y-m-d')."%' and counneller_id='".$_SESSION['user_id']."' ";
	
		
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$totalwalkin= $this->db->num_rows($result);
		
		$result2= $this->db->query($sql2,__FILE__,__LINE__);
		$totalclosed= $this->db->num_rows($result2);
		
		$result3= $this->db->query($sql3,__FILE__,__LINE__);
		$totalinstallments= $this->db->num_rows($result3);
		
		$result4= $this->db->query($sql4,__FILE__,__LINE__);
		$totalfollowup= $this->db->num_rows($result4);
		
		$totalamount=0;
		while($row= $this->db->fetch_array($result3))
		{
			$totalamount=$totalamount+$row['amount'];
		}
	?>
		<div class="onecolumn">
		<div class="header">
			<span>Email Personal Report</span>
		</div>
		<br class="clear"/>
		<div class="content">
		<form id="email_data" name="email_data" action="" method="post">
		<table class="data" width="100%">
		<tr><th colspan="2">&nbsp;  </th></tr>
		<tr>
			<th width="28%">Total Walk in:</th>
			<td width="72%"><?php echo $totalwalkin;?>
			<input type="hidden" name="totalwalkin" id="totalwalkin" value="<?php echo $totalwalkin;?>" />
			</td>
		</tr> 
		<tr>
			<th>Total Closed Cases:</th><td><?php echo $totalclosed;?>
			<input type="hidden" name="totalclosed" id="totalclosed" value="<?php echo $totalclosed;?>" />
			</td>
		</tr>
		<tr>
			<th>Total Follow Up:</th><td><?php echo $totalfollowup;?>
			<input type="hidden" name="totalfollowup" id="totalfollowup" value="<?php echo $totalfollowup;?>" />
			</td>
		</tr>
		<tr>
			<th>Total Installments Collected:</th><td><?php echo $totalinstallments;?>
			<input type="hidden" name="totalinstallments" id="totalinstallments" value="<?php echo $totalinstallments;?>" />
			</td>
		</tr>
		<tr>
			<th>Total Amount Collected:</th><td><?php echo $totalamount;?>
			<input type="hidden" name="totalamount" id="totalamount" value="<?php echo $totalamount;?>" />
			</td>
		</tr>
		<tr>
			<th>Remark:</th><td><textarea name="remark" cols="65" rows="10"></textarea></td>
		</tr>
		<tr>
		   <th colspan="2"><input type="submit" name="submit" value="Send">
		   &nbsp;
		    <input type="button" onclick="javascript: history.go(-1); return false" name="cancel" value="Cancel" />
		   </th>
	   </tr>	
		</table>
		</form>
		</div></div>
	<?php 
			break;
			case 'server' :
			extract($_POST);
			
			$sql_name="select * from ".TBL_USER." where user_id='".$_SESSION['user_id']."'";
			$result_name= $this->db->query($sql_name,__FILE__,__LINE__);
			$row_name= $this->db->fetch_array($result_name);
			
			$sql_email="select * from ".TBL_EMAIL;
			$result_email= $this->db->query($sql_email,__FILE__,__LINE__);	
			
			while($row_email= $this->db->fetch_array($result_email))
			{
			
				$emailTo = $row_email['email_id'];
				$subject = 'Counseller Report Date '.date('d-m-Y');
				
				//message body 
				$body ="Date: ".date('d-m-Y')." Counselor Report \n\n";
				$body .="Counseller Name: ".$row_name['user']." \n\n";
				$body .="Total Walk-in: ".$totalwalkin." \n\n";
				$body .="Total Closed Cases: ".$totalclosed." \n\n";
				$body .="Total Follow Up Cases: ".$totalfollowup." \n\n";
				$body .="Total Installments Collected: ".$totalinstallments." \n\n";
				$body .="Total Amount Collected: ".$totalamount." \n\n";
				$body .="Remark: ".$remark."\n\n";
		
				$headers = 'From: '.$row_name['user'];
				
				mail($emailTo, $subject, $body, $headers);
				
				
			}

		//echo $body;
		$_SESSION['msg']="Report mailed successfully";

		?>

		<script language="javascript">

		window.location='emailReport.php';

		</script>

		<?php 
			
			break;
			default	: 
						echo "Wrong Parameter passed";
							
		}
	}
	
	function emailAllReports($runat)
	{
	
		switch($runat){
			case 'local' :
			
			
		$sql="select * from ".TBL_PERSON_RECORD." where date='".date('Y-m-d')."' ";
		$sql2="select * from ".TBL_STUDENT_INFO." where timestamp like '".date('Y-m-d')."%' ";
		$sql3="select * from ".STD_INSTALLMENTS_INFO." where paid_date='".date('Y-m-d')."' ";
		$sql4="SELECT * FROM ".TBL_REMARK." WHERE `timestamp` like '".date('Y-m-d')."%'";
		
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$totalwalkin= $this->db->num_rows($result);
		
		$result2= $this->db->query($sql2,__FILE__,__LINE__);
		$totalclosed= $this->db->num_rows($result2);
		
		$result3= $this->db->query($sql3,__FILE__,__LINE__);
		$totalinstallments= $this->db->num_rows($result3);
		
		$result4= $this->db->query($sql4,__FILE__,__LINE__);
		$totalfollowup= $this->db->num_rows($result4);
		
		$totalamount=0;
		while($row= $this->db->fetch_array($result3))
		{
			$totalamount=$totalamount+$row['amount'];
		}
	?>
		<div class="onecolumn">
		<div class="header">
			<span>Email Report for All work</span>
		</div>
		<br class="clear"/>
		<div class="content">
		<form id="email_data" name="email_data" action="" method="post">
		<table class="data" width="100%">
		<tr><th colspan="2">&nbsp;  </th></tr>
		<tr>
			<th width="28%">Total Walk-in:</th>
			<td width="72%"><?php echo $totalwalkin;?>
			<input type="hidden" name="totalwalkin" id="totalwalkin" value="<?php echo $totalwalkin;?>" />
			</td>
		</tr> 
		<tr>
			<th>Total Closed Cases:</th><td><?php echo $totalclosed;?>
			<input type="hidden" name="totalclosed" id="totalclosed" value="<?php echo $totalclosed;?>" />
			</td>
		</tr>
		<tr>
			<th>Total Follow Up:</th><td><?php echo $totalfollowup;?>
			<input type="hidden" name="totalfollowup" id="totalfollowup" value="<?php echo $totalfollowup;?>" />
			</td>
		</tr>
		<tr>
			<th>Total Installments Collected:</th><td><?php echo $totalinstallments;?>
			<input type="hidden" name="totalinstallments" id="totalinstallments" value="<?php echo $totalinstallments;?>" />
			</td>
		</tr>
		<tr>
			<th>Total Amount Collected:</th><td><?php echo $totalamount;?>
			<input type="hidden" name="totalamount" id="totalamount" value="<?php echo $totalamount;?>" />
			</td>
		</tr>
		<tr>
			<th>Remark:</th><td><textarea name="remark" cols="65" rows="10"></textarea></td>
		</tr>
		<tr>
		   <th colspan="2"><input type="submit" name="submitt" value="Send">
		   &nbsp;
		    <input type="button" onclick="javascript: history.go(-1); return false" name="cancel" value="Cancel" />
		   </th>
	   </tr>	
		</table>
		</form>
		</div></div>
	<?php 
			break;
			case 'server' :
			extract($_POST);
			
			$sql_name="select * from ".TBL_USER." where user_id='".$_SESSION['user_id']."'";
			$result_name= $this->db->query($sql_name,__FILE__,__LINE__);
			$row_name= $this->db->fetch_array($result_name);
			
			$sql_email="select * from ".TBL_EMAIL;
			$result_email= $this->db->query($sql_email,__FILE__,__LINE__);	
			
			while($row_email= $this->db->fetch_array($result_email))
			{
			
				$emailTo = $row_email['email_id'];
				$subject = 'Report for Date '.date('d-m-Y');
				
				//message body 
				$body ="Date: ".date('d-m-Y')." Report \n\n";
				$body .="Total Walk-in: ".$totalwalkin." \n\n";
				$body .="Total Closed Cases: ".$totalclosed." \n\n";
				$body .="Total Follow Up Cases: ".$totalfollowup." \n\n";
				$body .="Total Installments Collected: ".$totalinstallments." \n\n";
				$body .="Total Amount Collected: ".$totalamount." \n\n";
				$body .="Remark: ".$remark."\n\n";
		
				$headers = 'From: '.$_SESSION[user_name];
				
				mail($emailTo, $subject, $body, $headers);
				
			}

		//echo $body;
		$_SESSION['msg']="Report mailed successfully";

		?>

		<script language="javascript">

		window.location='emailReport.php';

		</script>

		<?php 
			
			break;
			default	: 
						echo "Wrong Parameter passed";
							
		}
	}
	
	function getPaymntSearchBox()
	{
		?>
			
			<div id="searchboxbg">
			<form action="" id="search_form_paymntt" name="search_form_paymntt" method="post">
				<table width="100%" class="data">
				<?php
				$dt1 = date('Y-m-d');
		
				$b_date = explode('-',$dt1);
				$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]+30,$b_date[0]));
				
				if(isset($_POST['startdattopay']))
				{
					$d1 = $_POST['startdattopay'];
				}
				else
				{
					$d1 = date('Y-m-d');
					
				}
				
				if(isset($_POST['enddatetopay']))
				{
					$d2 = $_POST['enddatetopay'];
				}
				else
				{
					$d2 = $datess;
				}
				?>
				<tr>
				<th>Date From:</th>
				<th><input type="text" id="startdattopay" name="startdattopay" title="From" class="datepicker" value="<?php echo $d1;?>" readonly="true" /></th>
				<th>Date To:</th>
				<th><input type="text" id="enddatetopay" name="enddatetopay" title="To" class="datepicker"  value="<?php echo $d2;?>" readonly="true" /></th>
				
				<th><input type="submit" name="search" id="search" value="Search" /></th>
				</tr>
				</table>			
			</form>
			</div>
		
			<br class="clear"/>
		<?php 
	}
	
	function SearchPaymenttobeCollected($paydatefrom='',$paydateto='')
	{
		
		if($paydatefrom == '')
		{
			$paydatefrom= date('Y-m-d');
			
		}	
		if($paydateto == '')
		{
			$dt1 = date('Y-m-d');
		
			$b_date = explode('-',$dt1);
			$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]+30,$b_date[0]));
			
			$paydateto=$datess;
		}
		
		$sql="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and a.student_id=b.student_id and b.dispatch='no' and a.paid='no' ";
		
		$sql_2="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='no' ";
		
		$sql_2_new_std="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='no' and a.installment_num=1 ";
		
		$sql_3="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='no' ";
		
		$sql_3_new_std="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='no' and a.installment_num=1 ";
		
		/*echo $sql.'<br>';
		echo $sql_2.'<br>';
		echo $sql_3.'<br>';*/
		
		$resultx= $this->db->query($sql,__FILE__,__LINE__);
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=0;		
		while($row= $this->db->fetch_array($result))
		{
			$x=$x+$row['amount'];
		}
		
		
		$result_2= $this->db->query($sql_2,__FILE__,__LINE__);
		$x2=0;		
		while($row_2= $this->db->fetch_array($result_2))
		{
			$x2=$x2+$row_2['amount'];
		}
		
		$result_2_new_std= $this->db->query($sql_2_new_std,__FILE__,__LINE__);
		$x2_new_std=0;		
		while($row_2_new_std= $this->db->fetch_array($result_2_new_std))
		{
			$x2_new_std=$x2_new_std+$row_2_new_std['amount'];
		}
		
		$result_3= $this->db->query($sql_3,__FILE__,__LINE__);
		$x3=0;		
		while($row_3= $this->db->fetch_array($result_3))
		{
			$x3=$x3+$row_3['amount'];
		}
		
		$result_3_new_std= $this->db->query($sql_3_new_std,__FILE__,__LINE__);
		$x3_new_std=0;		
		while($row_3_new_std= $this->db->fetch_array($result_3_new_std))
		{
			$x3_new_std=$x3_new_std+$row_3_new_std['amount'];
		}
		?>
		<div class="onecolumn">
				<div class="header">
					<span>Payment to be Collected</span>
				</div>
				<br class="clear"/>
					
				
				<br class="clear"/>
				<?php $this->getPaymntSearchBox(); ?>
				<div class="content">
				
				
			<?php if($_SESSION['user_type'] != 'User') {?>
			<a href="printpage.php?index=paymenttobecollected&paydatefrom=<?php echo $paydatefrom;?>&paydateto=<?php echo $paydateto;?>" target="_blank" >Print-View</a>
			<?php } ?>
			
			<div id="divToPrintAmountttt">
			<table class="data" width="100%" cellspacing="0" >  
			<tr>
				<th>Total Amount to be Collected :</th><td>Rs. <?php echo $x; ?> /-</td>
			</tr>
			<tr>
				<th>Amount to be Collected from DZ :</th>
				<td><div>Rs. <?php echo $x2; ?> /-</div>
					<div>From New Student: Rs. <?php echo $x2_new_std; ?> /-</div>
					<div>From Old Student: Rs. <?php echo $x2-$x2_new_std; ?> /-</div>
				</td>
			</tr>
			<tr>
				<th>Amount to be Collected from CC :</th>
				<td><div>Rs. <?php echo $x3; ?> /-</div>
					<div>From New Student: Rs. <?php echo $x3_new_std; ?> /-</div>
					<div>From Old Student: Rs. <?php echo $x3-$x3_new_std; ?> /-</div>
				</td>
			</tr>
			</table>
			</div>
			<br class="clear" />
			
			<form id="form_data" name="form_data" action="" method="post">
			
			
			<div id="divToPrintfeeduess">
			  <table class="data" width="100%" cellpadding="0" cellspacing="0" id="search_tabletobeoaid">
                <thead>
                  <tr>
                    <th width="7%">S.No.</th>
                    <th width="28%">Name</th>
                    <th width="16%">Due Date</th>
                    <th width="16%">Amount Due</th>
					<th width="14%">Phone</th>
					<th width="6%">Type</th>
                    <th width="13%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 	
				$k=0;
				while($row= $this->db->fetch_array($resultx))
				{
				?>
                  <tr>
                    <td><?php echo $k+1;?></td>
                    <td><?php echo $row['student_name'];?></td>
                    <td><?php echo  date('d-m-Y', strtotime($row['date']));if($row['remark'] != ''){?><img src="images/icon_star.png" title="<?php echo $row['remark'];?>" class="help" /><?php }?></td>
                    <td><?php echo $this->getCurrentInstallment($row['student_id']);?></td>
					<td><?php echo $row['phone'];?></td>
					<td><?php echo $this->getStudentType($row['student_id']);?></td>	
                    <td><a href="student_details.php?index=studentView&student_id=<?php echo $row['student_id'];?>" title="View" class="help"> <img src="images/icon_users.png" width="15px" height="15px" /></a>
                        <?php if($_SESSION['user_type'] != 'User') {?>
                      | <a href="student_details.php?index=editStudent&student_id=<?php echo $row['student_id'];?>" title="Edit" class="help"> <img src="images/icon_edit.png" width="15px" height="15px" /></a>
                      <?php } ?></td>
                  </tr>
                  <?php 
					$k++;
				}
				if($k == 0)
				{
				?>
                  <tr>
                    <td colspan="7" align="center">No result</td>
                  </tr>
                  <?php 
				}
				?>
                </tbody>
              </table>
			  <br class="clear"/>
			<?php 
			$rowcnt= $k-1;
			
			?>
			<div id="pager22" class="pager" <?php if($rowcnt<10) { echo 'style="display:none"';} ?> >

				<img src="tablesorter/pager/icons/first.png" class="first"/>

				<img src="tablesorter/pager/icons/prev.png" class="prev"/>

				<input type="text" class="pagedisplay"/>

				<img src="tablesorter/pager/icons/next.png" class="next"/>

				<img src="tablesorter/pager/icons/last.png" class="last"/>

				<select class="pagesize">

					<option selected="selected"  value="10">10</option>

					<option value="20">20</option>

					<option value="30">30</option>

					<option  value="40">40</option>

					<option  value="50">50</option>

					<option  value="60">60</option>

					<option  value="70">70</option>

					<option  value="80">80</option>

					<option  value="90">90</option>

					<option  value="100">100</option>

					<option  value="10000">All</option>

				</select>

			

			</div>
			<div align="right">Total Records: <?php echo $k;?></div>
			<br class="clear"/>

			</div>
			</form>
						
			</div>
			</div>
		<?php 
	}
	
	function SearchPaymenttobeCollectedprintview($paydatefrom='',$paydateto='')
	{
		
		if($paydatefrom == '')
		{
			$paydatefrom= date('Y-m-d');
			
		}	
		if($paydateto == '')
		{
			$dt1 = date('Y-m-d');
		
			$b_date = explode('-',$dt1);
			$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]+30,$b_date[0]));
			
			$paydateto=$datess;
		}
		
		$sql="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and a.student_id=b.student_id and b.dispatch='no' and a.paid='no' ";
		
		$sql_2="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='DZ' and a.student_id=b.student_id and b.dispatch='no' and a.paid='no' ";
		
		$sql_3="select a.*,b.* from  ".STD_INSTALLMENTS_INFO." a, ".TBL_STUDENT_INFO." b where a.date between '".$paydatefrom."' and '".$paydateto."' and 
		b.type='CC' and a.student_id=b.student_id and b.dispatch='no' and a.paid='no' ";
		
		/*echo $sql.'<br>';
		echo $sql_2.'<br>';
		echo $sql_3.'<br>';*/
		
		$resultx= $this->db->query($sql,__FILE__,__LINE__);
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=0;		
		while($row= $this->db->fetch_array($result))
		{
			$x=$x+$row['amount'];
		}
		
		
		$result_2= $this->db->query($sql_2,__FILE__,__LINE__);
		$x2=0;		
		while($row_2= $this->db->fetch_array($result_2))
		{
			$x2=$x2+$row_2['amount'];
		}
		
		$result_3= $this->db->query($sql_3,__FILE__,__LINE__);
		$x3=0;		
		while($row_3= $this->db->fetch_array($result_3))
		{
			$x3=$x3+$row_3['amount'];
		}
		?>
		<div class="onecolumn">
				<div class="header">
					<span>Payment to be Collected from <?php echo $paydatefrom;?> to <?php echo $paydateto;?></span>
				</div>
				<br class="clear"/>
				<div class="content">

			
			
			<table class="data" width="100%" cellspacing="0" >  
			<tr>
				<th>Total Amount to be Collected :</th><td>Rs. <?php echo $x; ?> /-</td>
			</tr>
			<tr>
				<th>Amount to be Collected from DZ :</th><td>Rs. <?php echo $x2; ?> /-</td>
			</tr>
			<tr>
				<th>Amount to be Collected from CC :</th><td>Rs. <?php echo $x3; ?> /-</td>
			</tr>
			</table>
			<br class="clear"/>
			
			
			<form id="form_data" name="form_data" action="" method="post">
			
			  <table class="data" width="100%" cellpadding="0" cellspacing="0" id="">
                <thead>
                  <tr>
                    <th width="9%">S.No.</th>
                    <th width="32%">Name</th>
                    <th width="16%">Due Date</th>
                    <th width="18%">Amount Due</th>
					<th width="16%">Phone</th>
					<th width="9%">Type</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 	
				$k=0;
				while($row= $this->db->fetch_array($resultx))
				{
				?>
                  <tr>
                    <td><?php echo $k+1;?></td>
                    <td><?php echo $row['student_name'];?></td>
                    <td><?php echo  date('d-m-Y', strtotime($row['date']));if($row['remark'] != ''){?><img src="images/icon_star.png" title="<?php echo $row['remark'];?>" class="help" /><?php }?></td>
                    <td><?php echo $this->getCurrentInstallment($row['student_id']);?></td>
					<td><?php echo $row['phone'];?></td>
					<td><?php echo $this->getStudentType($row['student_id']);?></td>	
                  </tr>
                  <?php 
					$k++;
				}
				if($k == 0)
				{
				?>
                  <tr>
                    <td colspan="7" align="center">No result</td>
                  </tr>
                  <?php 
				}
				?>
                </tbody>
              </table>
			  <br class="clear"/>
			<?php 
			$rowcnt= $k-1;
			
			?>
			
			</form>
						
			</div>
			</div>
		<?php 
	}
	
	function Bday_email_alert()
	{
	
		$sql="select * from ".TBL_BDAY_EMAIL." where email_send='no' and date='".date("Y-m-d")."'";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		//$row= $this->db->fetch_array($result);
		
		if($this->db->num_rows($result) >= 1)
		{
		?>
			<a href="javascript: void(0);" onclick="javascript: alrt.updateBdayemail({})">
			<div class="alert_warning" style="margin-top:0">
				<p>
					<img src="images/icon_warning.png" alt="success" class="mid_align"/>
					Click Here to send Birth Day E-mail.
				</p>
			</div>
			</a>
		<?php 
		}
	
	}
	
	function updateBdayemail()
	{
		ob_start();
		$sql_del="delete from ".TBL_BDAY_EMAIL." where email_send='no' and date='".date("Y-m-d")."'";
		$this->db->query($sql_del,__FILE__,__LINE__);
		
		$dt1 = date('Y-m-d');
		$b_date = explode('-',$dt1);
		
		/*for($i=1;$i<1000;$i++)
		{
			$datess = date('Y-m-d',mktime(0,0,0,$b_date[1],$b_date[2]+$i,$b_date[0]));
			
			$insert_array = array();
			$insert_array['date'] = $datess;
			$this->db->insert(TBL_BDAY_EMAIL,$insert_array);
		}*/
		
		///////////////////////
		$mnth = date('m');
		$day = date('d');
		$bdate_1 = '-'.$mnth.'-'.$day;
		
		$sql="select * from ".TBL_STUDENT_INFO." where dob like '____".$bdate_1."' ";
		$sql_2="select * from ".TBL_PERSON_RECORD." where dob like '____".$bdate_1."' ";
		
		 
		
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$result_2= $this->db->query($sql_2,__FILE__,__LINE__);

		?>
		
			
				<?php 		
				while($row= $this->db->fetch_array($result))
				{
					
					$emailTo = $row['email'];
					$subject = 'Birth Day Wish';
					
					//message body 
					$body ="Dear, ".$row['student_name']." \n\n";
					$body .="Wish You a Happy Birthday \n\n From: Tarang Singhal \n Singhal Institute Of CADD CENTRE Engineering Services (P) LTD \n\n Corporate Office :G 17 to G 20, Murli Bhawan, 10-A, Ashok Marg, Lucknow - 226001, Uttar Pradesh, INDIA.\n\n Branch Office : First Floor, Shagun Palace, Sapru Marg, Lucknow - 226001, Uttar Pradesh, INDIA";
			
					$headers = 'From: tarangsinghal@sicces.co.in';
					
					mail($emailTo, $subject, $body, $headers);
					

				 
				 $response=file_get_contents("http://122.166.5.17/desk2web/SendSMS.aspx?UserName=singhal&password=singhal1&MobileNo=".$row[phone].
"&SenderID=caddCntr&CDMAHeader=91&message=". 'Dear%20'."Student".'%20wish%20you%20happy%20birth%20day%20from%20TARANG%20SINGHAL.&isFlash=false');
				echo $response;
				
				
				}
				while($row2= $this->db->fetch_array($result_2))
				{
					 $emailTo = $row2['email'];
					$subject = 'Report for Date '.date('d-m-Y');
					
					//message body 
					$body ="Dear, ".$row2['name']." \n\n";
					$body .="Wish You a Happy Birthday \n\n From: Tarang Singhal \n Singhal Institute Of CADD CENTRE Engineering Services (P) LTD \n\n Corporate Office :G 17 to G 20, Murli Bhawan, 10-A, Ashok Marg, Lucknow - 226001, Uttar Pradesh, INDIA.\n\n Branch Office : First Floor, Shagun Palace, Sapru Marg, Lucknow - 226001, Uttar Pradesh, INDIA";
			
					$headers = 'From: tarangsinghal@sicces.co.in';
					
					mail($emailTo, $subject, $body, $headers);
					
					$response=file_get_contents("http://122.166.5.17/desk2web/SendSMS.aspx?UserName=singhal&password=singhal1&MobileNo=".$row2[phone].
"&SenderID=caddCntr&CDMAHeader=91&message=". 'Dear%20'."Student".'%20wish%20you%20happy%20birth%20day%20from%20TARANG%20SINGHAL.&isFlash=false');
					echo $response;
				}
				
		
		?>
		<script type="text/javascript">
			window.location = "home.php";
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
	}
	function getdata_alerts(){
	$sql="Select * from ".TBL_ALERTS." where customerno=".$_SESSION['customerno']."";
	$resultpages= $this->db->query($sql,__FILE__,__LINE__); 
	$arr=array();
	$x=1;
	while($row=$this->db->fetch_array($resultpages))
	{
	if($row['alertname']=="depart" && $row['alerttype']=="sms"){
	$arr["departsms"]=$row['alertmsg'];
	}
	if($row['alertname']=="depart" && $row['alerttype']=="email"){
	$arr["departemailh"]=$row['alertsubject'];
	$arr["departemailm"]=$row['alertmsg'];
	}
	if($row['alertname']=="thankyou" && $row['alerttype']=="sms"){
	$arr["thanksms"]=$row['alertmsg'];
	}
	if($row['alertname']=="thankyou" && $row['alerttype']=="email"){
	$arr["thankemailh"]=$row['alertsubject'];
	$arr["thankemailm"]=$row['alertmsg'];
	}
	if($row['alertname']=="panic" && $row['alerttype']=="sms"){
	$arr["panich"]=$row['alertsubject'];
	$arr["panicm"]=$row['alertmsg'];
	}
	
	
	}
	return $arr;
	}
	
	
}

?>