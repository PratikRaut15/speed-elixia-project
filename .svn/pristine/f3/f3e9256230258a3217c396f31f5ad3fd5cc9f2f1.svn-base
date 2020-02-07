	<?php
	//**************** Notification class Created for displaying notification messages across the website ****************
		class Notification
		{
		
			var $notice;	
			var $timeout;
		
				function __construct()
			{
				@$this->notice=$_SESSION[msg];
				@$this->error=$_SESSION[error_msg];
				$this->timeout=3000;
							$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
							$this->validity = new ClsJSFormValidation();
							$this->Form = new ValidateForm();
							$this->auth=new Authentication();
			}
			function SetNote($note)
			{
				$this->notice=$note;
			}
			
			function SetTimeout($SetTimeout)
			{
				$this->SetTimeout=$SetTimeout;
			}
			
			function Notify()
			{
				if($this->notice!='') {
				?>
				<script type="text/javascript">
				setTimeout('document.getElementById("message_t").style.display="none";',<?php echo $this->timeout; ?>);
				</script> 
				<div  id="message_t">
				<div class='alert_success'>
				<p>
					<img src="images/icon_accept.png" alt="success" class="mid_align"/>
					<?php echo $this->notice; ?>
				</p>	
				</div>
				</div>
				<?php
				$this->destroy_note();
				}
				else if($this->error!='')
				{
				?>
				<script type="text/javascript">
				setTimeout('document.getElementById("message_er").style.display="none";',<?php echo $this->timeout; ?>);
				</script> 
				<div  id="message_er">
				<div class='alert_error'>
				<p>
					<img src="images/icon_error.png" alt="delete" class="mid_align"/>
					<?php echo $this->error; ?>
				</p>	
				</div>
				</div>
				<?php
				$this->destroy_note();
				}
			}
			
			
			function destroy_note()
			{
				$_SESSION['msg']='';
				$_SESSION['error_msg']='';
			}
					
					function anyupdate($timestamp)
					{
					 $msgstr="null"; 
					 $count=0;
					 $sqlupdate="select * from ".TBL_NOTIF." where customerno=".$_SESSION['customerno']." and status in (6,7,8,10) and timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR) and isshown=0 order by notifid desc";   
					 
					 $record2 = $this->db->query($sqlupdate,__FILE__,__LINE__);
					 $record = $this->db->query($sqlupdate,__FILE__,__LINE__);
					 $row = $this->db->fetch_array($record);  
						while($row2 = $this->db->fetch_array($record2)){
						 $count++;
						 
						}
					$msgstr= $this->get_notification_async();
					$totalc =count($msgstr[0])+count($msgstr[1]);
					
					 if($totalc!=0){
					
							 
							$tmsg=$msgstr[0];
							$pmsg=$msgstr[1];
						if(count($msgstr[0])==0){$tmsg="null";}
						if(count($msgstr[1])==0){$pmsg="null"; }	
						 
					 }
					else
					 {
						$tmsg="null"; 
						$pmsg="null"; 
					}
					 if($row['timestamp']=="")
					 {
						 $row['timestamp']=$timestamp;
					 }
					 
					 echo $str='{"count":'.$count.',"timestamp":"'.$row['timestamp'].'","msg":'.json_encode($tmsg).',"panic":'.json_encode($pmsg).'}';
					
					}
				   function get_notification_async(){
					 if($_SESSION['branchid']!="all"){
					 $banch_clause=" and branchid=".$_SESSION['branchid'];
					 }
			 	$sqlupdate="select * from ".TBL_NOTIF." left outer join  ".TBL_FLOW."  on ".TBL_FLOW.".serviceflowid =".TBL_NOTIF.".status 
				 where customerno=".$_SESSION['customerno']." and status in (6,7,8,10)  ".$banch_clause. " and timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR) order by notifid desc limit 0,15 ";   
					 $record = $this->db->query($sqlupdate,__FILE__,__LINE__);
					 $x=0;
					 $y=0;
					 $res=array();
					 $p_msg=array();
					 while($row2 = $this->db->fetch_row($record))
					 {
					
						if($row2['1']==10){
									if($row2['6']=="0"){
									
										$p_msg[$y]['msg']=$row2['3'];
										$p_msg[$y]['msg_type']=$row2['8'];
										$p_msg[$y]['msg_time']="<span id='fl'>".date("h:i:s A ",strtotime($row2['5']))."</span>";
										$p_msg[$y]['id']=$row2['0'];
										$y++;
									}
						}else{
						
										$res[$x]['msg']="<div style='float:left;'>".$row2['3']."</div><div style='float:right;font-size:8px;'>".date("h:i A",strtotime($row2['5']))."</div>";
										$res[$x]['msg_type']=$row2['8'];
										$res[$x]['id']=$row2['0'];
										$x++;
										$this->isupdated($row2['0']);
						}
						 
							 
					 }
					
					
					
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
					
					
				   function showAllInfo()
					{
                                       if($_SESSION['branchid']!="all"){
                                           
                                           $branch_clause=" and branchid=".$_SESSION['branchid'];
                                       }
					$sqlupdate="select * from ".TBL_NOTIF." where customerno=".$_SESSION['customerno']."  ".$branch_clause;   
					
					$sqlupdate.=" order by notifid desc ";
							$resultpages= $this->db->query($sqlupdate,__FILE__,__LINE__);
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
				$result= $this->db->query($sqlupdate,__FILE__,__LINE__);
			?>
			
			<div class="onecolumn">
			<div class="header">
			<span>notification msg List</span>
			<div class="switch" style="width:300px">
					
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
										<th width="30%">message</th>
										<th width="10%">Time</th>
									
										</tr>
							
							</thead>
							<tbody>
										<?php 	
										$x=1;	
										while($row= $this->db->fetch_array($result))
										{
										?>
										<tr>
										
										<td><?php echo  $x;?></td>
										<td title="<?php echo $row['message'];?>"><?php echo $row['message'];?></td>
										
										<td title="<?php echo $row['timestamp'];?>"><?php echo $row['timestamp'];?></td>
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
									<a href="shownotifications.php">&laquo;&laquo;</a>
									<a href="shownotifications.php?pg=<?php echo $pgr-1;?>">&laquo;</a>
									<?php if($pgr == $lastpage && ($pgr-4) >= 1 ) { ?>
									<a href="shownotifications.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
									<?php } ?>
									<?php if($pgr == $lastpage || $pgr == $lastpage-1) {
									if(($pgr-3) >= 1){
									?>
									<a href="shownotifications.php?pg=<?php echo $pgr-3;?>"><?php echo $pgr-3; ?></a>
									<?php } } ?>
									
									<?php $temp0=$pgr-2;
									if($temp0 >= 1) {				
									?>
									<a href="shownotifications.php?pg=<?php echo $pgr-2;?>"><?php echo $pgr-2;?></a>
									<?php } ?>
									
									<?php $temp1=$pgr-1;
									if($temp1 >= 1) {				
									?>
									<a href="shownotifications.php?pg=<?php echo $pgr-1;?>"><?php echo $pgr-1;?></a>
									<?php } ?>
									
									<a href="shownotifications.php?pg=<?php echo $pgr;?>" class="active"><?php echo $pgr;?></a>
									
									<?php $temp2=$pgr+1;
									if($temp2 <= $lastpage) {				
									?>
									<a href="shownotifications.php?pg=<?php echo $pgr+1;?>"><?php echo $pgr+1;?></a>
									<?php } ?>
									<?php $temp3=$pgr+2;
									if($temp3 <= $lastpage) {				
									?>
									<a href="shownotifications.php?pg=<?php echo $pgr+2;?>"><?php echo $pgr+2;?></a>
									<?php } ?>
									
									<?php if($pgr == 1 || $pgr == 2) { 
									if(($pgr+3) <= $lastpage) {
									?>
									<a href="shownotifications.php?pg=<?php echo $pgr+3;?>"><?php echo $pgr+3; ?></a>
									<?php } } ?>
									<?php if($pgr == 1 && ($pgr+4) <= $lastpage) { ?>
									<a href="shownotifications.php?pg=<?php echo $pgr+4;?>"><?php echo $pgr+4; ?></a>
									<?php } ?>
									
									<a href="shownotifications.php?pg=<?php echo $pgr+1;?>">&raquo;</a>
									<a href="shownotifications.php?pg=<?php echo $lastpage;?>">&raquo;&raquo;</a>
									</div>
			
			<div align="right">Total Pages - <?php echo $lastpage;?></div>
			<div align="right">Total Records - <?php echo $numpages;?></div>
			
			</div>
			</div>
			
			<?php 
			
			}
			function update_notification(){
				$sql="UPDATE ".TBL_NOTIF." SET isnotified=1 WHERE customerno=".$_SESSION['customerno']."";
				$resultpages= $this->db->query($sql,__FILE__,__LINE__);
				
			}
				   
				   
				   
				   
				   
				   
				   
				   
		}
	
	?>