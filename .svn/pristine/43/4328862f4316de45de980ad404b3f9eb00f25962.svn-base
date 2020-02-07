<?php

class ManageMaster{
	
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
	
	function AddNews($runat)
	{
		switch($runat){
			case 'local' :
							if(count($_POST)>0 and $_POST['submit']=='Submit'){
								extract($_POST);
								$this->news = $news;
								$this->headline = $headline;
								
							}
						$FormName = "frm_add_news_updates";
						$ControlNames=array("headline"=>array('headline',"''","Please enter Headline","span_headline")
						);

						$ValidationFunctionName="CheckAddNewsValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						  <tr><td>&nbsp;</td></tr>
						 <tr>
						 	 <th>Course: </th>
						 	 <td><input type="text" name="headline" id="headline" value="<?php echo $this->headline;?>"  />
							 	<span id="span_headline"></span></td>
						 </tr>	 
						 
						 <tr>
						 	<th valign="top">News: </th>
							<td><textarea name="news" rows="20" cols="80"><?php echo $this->news;?></textarea>
						  
						  <tr>
						  	  <td colspan="2"><input type="submit" name="submit" value="Submit" 
							  onclick="return <?php echo $ValidationFunctionName;?>()">
							  &nbsp;
							   <input type="button" onclick="javascript: history.go(-1); return false" 
							  name="cancel" value="Cancel" /></td>
							  </td>
					      </tr>		  
							  
						</table>
						</form>
						<?php
						break;
			case 'server' :	
							extract($_POST);
							$this->headline=$headline;
							$this->news = $news;
							
							
							// server side validation
							$return =true;
							if($this->Form->ValidField($headline,'empty','Headline is empty')==false)
								$return =false;
							
								
							if($return){
							
							$insert_sql_array = array();
							$insert_sql_array['news'] = $this->news;
							$insert_sql_array['headline'] = $this->headline;
							
							$this->db->insert(NEWS_UPDATES,$insert_sql_array);
							$_SESSION['msg']='News has been added successfully';
							?>
							<script type="text/javascript">
								window.location = "Addnews.php"
							</script>
							<?php
							exit();
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->AddNews('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	}

	
	
	function editNews($runat,$news_id)
	{
		$this->news_id=$news_id;
		switch($runat){
			case 'local' :
							if(count($_POST)>0 and $_POST['submit']=='Submit'){
								extract($_POST);
								$this->college_name = $college_name;
								
							}
						$FormName = "frm_edit_news";
						$ControlNames=array("headline"=>array('headline',"''","Please enter Headline","span_headline")
						);

						$ValidationFunctionName="CheckAddNewsValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
												
						$sql="select * from ".NEWS_UPDATES." where news_id='".$this->news_id."'";
						$result= $this->db->query($sql,__FILE__,__LINE__);
						$row= $this->db->fetch_array($result);
						?>
						<br class="clear"/>
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						  <tr><td>&nbsp;</td></tr>
						  
						  <tr>
						 	 <th>Headline: </th>
						 	 <td><input type="text" name="headline" id="headline" value="<?php echo $row['headline'];?>"  />
							 	<span id="span_headline"></span></td>
						 </tr>	
						 						 
						 <tr>
						 	<th valign="top">News:</th>
							<td><textarea name="news" id="news" rows="10" cols="80"><?php echo $row['news'];?></textarea>
						  
						  <tr>
						  	  <td colspan="2"><input type="submit" name="submit" value="Submit" 
							  onclick="return <?php echo $ValidationFunctionName;?>()" >
							  &nbsp; 
							  <input type="button" onclick="javascript: history.go(-1); return false" 
							  name="cancel" value="Cancel" /></td>
					      </tr>		  
							  
						</table>
						</form>
						<?php
						break;
			case 'server' :	
							extract($_POST);
							$this->news = $news;
							$this->headline = $headline;
							
							$update_sql_array = array();
							$update_sql_array['news'] = $this->news;
							$update_sql_array['headline'] = $this->headline;
							
							$this->db->update(NEWS_UPDATES,$update_sql_array,'news_id',$this->news_id);
							$_SESSION['msg']='News has been Updated successfully';
							?>
							<script type="text/javascript">
								window.location = "NewsUpdates.php"
							</script>
							<?php							
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	}
	
	function deleteNews($news_id)
	{
		
		$sql="delete from ".NEWS_UPDATES." where news_id='".$news_id."'";
		$this->db->query($sql,__FILE__,__LINE__);
		$_SESSION['msg']='News has been Deleted successfully';
		?>
		<script type="text/javascript">
			window.location = "NewsUpdates.php"
		</script>
		<?php
	}
	
	
	function addYear($runat)
	{
		switch($runat){
			case 'local' :
							if(count($_POST)>0 and $_POST['submit']=='Submit'){
								extract($_POST);
								$this->year = $year;
								
							}
						$FormName = "frm_add_newyear";
						$ControlNames=array("year"=>array('year',"''","Please enter Year","span_year")
						);

						$ValidationFunctionName="CheckAddNewyearValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn">
							<div class="header">
								<span>Add New Years</span>
								
							<div class="switch" style="width:300px">
									<table width="300px" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td><a href="CourseYears.php">
												<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:150px"/>			
												</a>
											</td>
											
											<td><a href="CourseYears.php?index=addYear">
												<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:150px" />
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
						
						<table>
						  <tr><td colspan="2">&nbsp;</td></tr>
						 <tr>
						 	 <th>Year: </th>
						 	 <td><input type="text" name="year" id="year" value="<?php echo $this->year;?>"  />
							 	<span id="span_year"></span></td>
						 </tr>	 
						 
						  <tr>
						  	  <td colspan="2"><input type="submit" name="submit" value="Submit" 
							  onclick="return <?php echo $ValidationFunctionName;?>()">
							  &nbsp;
							   <input type="button" onclick="javascript: history.go(-1); return false" 
							  name="cancel" value="Cancel" /></td>
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
							$this->year=$year;
							
							// server side validation
							$return =true;
							if($this->Form->ValidField($year,'empty','Year is empty')==false)
								$return =false;
							
								
							if($return){
							
							$insert_sql_array = array();
							$insert_sql_array['year'] = $this->year;
							
							$this->db->insert(TBL_YEAR,$insert_sql_array);
							$_SESSION['msg']='Year has been added successfully';
							?>
							<script type="text/javascript">
								window.location = "CourseYears.php"
							</script>
							<?php
							exit();
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->addYear('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	}
		
	function showAllYears()
	{
		
		$sql="select * from ".TBL_YEAR." order by year desc";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=1;
		?>
		<div class="onecolumn">
				<div class="header">
					<span>Course Years</span>
					
				<div class="switch" style="width:300px">
						<table width="300px" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:150px"/>
								</td>
								
								<td><a href="CourseYears.php?index=addYear">
									<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:150px" />
									</a>
								</td>
							</tr>
						</tbody>
						</table>
					</div>	
				</div>
				<br class="clear"/>
				<div class="content">
			<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">  
			<thead>
			<tr>
				<th width="8%">S.No.</th>
				<th width="66%">Year</th>
				<th width="26%">Action</th>
			</tr>
			</thead>
			<tbody>
				<?php 		
				while($row= $this->db->fetch_array($result))
				{
					
				?>
				<tr>
					<td><?php echo $x;?></td>
					<td><?php echo $row['year'];?></td>
					<td> <a href="CourseYears.php?index=editYear&year_id=<?php echo $row['year_id'];?>">
					<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a href="CourseYears.php?index=editYear&year_id=<?php echo $row['year_id'];?>">
					<img src="images/icon_delete.png" width="15px" height="15px" /></a> </td>
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
	
	function deleteYear($year_id)
	{
	
		$sql="delete from ".TBL_YEAR." where year_id='".$year_id."'";
		$this->db->query($sql,__FILE__,__LINE__);
		?>
		<script language="javascript">window.location='CourseYears.php'</script>
		<?php 
	
	}
	
	function editYear($runat,$year_id)
	{
		$this->year_id=$year_id;
		switch($runat){
			case 'local' :
						
						$FormName = "frm_edit_newyear";
						$ControlNames=array("year"=>array('year',"''","Please enter Year","span_year")
						);

						$ValidationFunctionName="CheckeditNewyearValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn">
							<div class="header">
								<span>Edit Years</span>
								
							<div class="switch" style="width:300px">
									<table width="300px" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td><a href="CourseYears.php">
												<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:150px"/>			
												</a>
											</td>
											
											<td><a href="CourseYears.php?index=addYear">
												<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:150px" />
												</a>
											</td>
										</tr>
									</tbody>
									</table>
								</div>	
							</div>
							<br class="clear"/>
							<div class="content">
						<?php 
							$sql="select * from ".TBL_YEAR." where year_id='".$this->year_id."'";
							$result= $this->db->query($sql,__FILE__,__LINE__);
							$row= $this->db->fetch_array($result)
						
						?>	
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						  <tr><td colspan="2">&nbsp;</td></tr>
						 <tr>
						 	 <th>Year: </th>
						 	 <td><input type="text" name="year" id="year" value="<?php echo $row['year']?>"  />
							 	<span id="span_year"></span></td>
						 </tr>	 
						 
						  <tr>
						  	  <td colspan="2"><input type="submit" name="submit" value="Submit" 
							  onclick="return <?php echo $ValidationFunctionName;?>()">
							  &nbsp;
							   <input type="button" onclick="javascript: history.go(-1); return false" 
							  name="cancel" value="Cancel" /></td>
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
							$this->year=$year;
							
							// server side validation
							$return =true;
							if($this->Form->ValidField($year,'empty','Year is empty')==false)
								$return =false;
							
								
							if($return){
							
							$update_array = array();
							$update_array['year'] = $this->year;
							
							$this->db->update(TBL_YEAR,$update_array,'year_id',$this->year_id);
							$_SESSION['msg']='Year has been Updated successfully';
							?>
							<script type="text/javascript">
								window.location = "CourseYears.php"
							</script>
							<?php
							exit();
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->editYear('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	}
	
	
	function addSource($runat)
	{
		switch($runat){
			case 'local' :
							if(count($_POST)>0 and $_POST['submit']=='Submit'){
								extract($_POST);
								$this->source_of_info = $source_of_info;
								
							}
						$FormName = "frm_add_source_of_info";
						$ControlNames=array("source_of_info"=>array('source_of_info',"''","Please enter Source Of Info.","span_source_of_info")
						);

						$ValidationFunctionName="CheckAddsource_of_infoValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn">
							<div class="header">
								<span>Add New Source Of Info.</span>
								
							<div class="switch" style="width:300px">
									<table width="300px" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td><a href="source_of_info.php">
												<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:150px"/>			
												</a>
											</td>
											
											<td>
												<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:150px" />
												
											</td>
										</tr>
									</tbody>
									</table>
								</div>	
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						  <tr><td colspan="2">&nbsp;</td></tr>
						 <tr>
						 	 <th>Source Of Info.: </th>
						 	 <td><input type="text" name="source_of_info" id="source_of_info" value="<?php echo $this->source_of_info;?>"  />
							 	<span id="span_source_of_info"></span></td>
						 </tr>	 
						 
						  <tr>
						  	  <td colspan="2"><input type="submit" name="submit" value="Submit" 
							  onclick="return <?php echo $ValidationFunctionName;?>()">
							  &nbsp;
							   <input type="button" onclick="javascript: history.go(-1); return false" 
							  name="cancel" value="Cancel" /></td>
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
							$this->source_of_info=$source_of_info;
							
							// server side validation
							$return =true;
							if($this->Form->ValidField($source_of_info,'empty','Year is empty')==false)
								$return =false;
							
								
							if($return){
							
							$insert_sql_array = array();
							$insert_sql_array['source_of_info'] = $this->source_of_info;
							
							$this->db->insert(TBL_SOURCE_OF_INFO,$insert_sql_array);
							$_SESSION['msg']='Source of Info has been added successfully';
							?>
							<script type="text/javascript">
								window.location = "source_of_info.php"
							</script>
							<?php
							exit();
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->addSource('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	}
	
	function showAllSourceofInfo()
	{
		
		$sql="select * from ".TBL_SOURCE_OF_INFO;
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=1;
		?>
		<div class="onecolumn">
				<div class="header">
					<span>SOURCE OF INFO</span>
					
				<div class="switch" style="width:300px">
						<table width="300px" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:150px"/>
								</td>
								
								<td><a href="source_of_info.php?index=addSource">
									<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:150px" />
									</a>
								</td>
							</tr>
						</tbody>
						</table>
					</div>	
				</div>
				<br class="clear"/>
				<div class="content">
			<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">  
			<thead>
			<tr>
				<th width="8%">S.No.</th>
				<th width="66%">Source Of Info.</th>
				<th width="26%">Action</th>
			</tr>
			</thead>
			<tbody>
				<?php 		
				while($row= $this->db->fetch_array($result))
				{
					
				?>
				<tr>
					<td><?php echo $x;?></td>
					<td><?php echo $row['source_of_info'];?></td>
					<td> <a href="source_of_info.php?index=editSource&source_id=<?php echo $row['source_of_info_id'];?>">
					<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a href="source_of_info.php?index=deleteSource&source_id=<?php echo $row['source_of_info_id'];?>">
					<img src="images/icon_delete.png" width="15px" height="15px" /></a> </td>
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
	
	
	function editSource($runat,$source_id)
	{
		$this->source_id=$source_id;
		switch($runat){
			case 'local' :
						
						$FormName = "frm_edit_edittSource";
						$ControlNames=array("source_of_info"=>array('source_of_info',"''","Please enter Source","span_source")
						);

						$ValidationFunctionName="CheckeditsourceValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn">
							<div class="header">
								<span>Edit Source of Info.</span>
								
							<div class="switch" style="width:300px">
									<table width="300px" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td><a href="source_of_info.php">
												<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:150px"/>			
												</a>
											</td>
											
											<td><a href="source_of_info.php?index=addSource">
												<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:150px" />
												</a>
											</td>
										</tr>
									</tbody>
									</table>
								</div>	
							</div>
							<br class="clear"/>
							<div class="content">
						<?php 
							$sql="select * from ".TBL_SOURCE_OF_INFO." where source_of_info_id='".$this->source_id."'";
							$result= $this->db->query($sql,__FILE__,__LINE__);
							$row= $this->db->fetch_array($result)
						
						?>	
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						  <tr><td colspan="2">&nbsp;</td></tr>
						 <tr>
						 	 <th>Year: </th>
						 	 <td><input type="text" name="source_of_info" id="source_of_info" value="<?php echo $row['source_of_info']?>"  />
							 	<span id="span_source"></span></td>
						 </tr>	 
						 
						  <tr>
						  	  <td colspan="2"><input type="submit" name="submit" value="Submit" 
							  onclick="return <?php echo $ValidationFunctionName;?>()">
							  &nbsp;
							   <input type="button" onclick="javascript: history.go(-1); return false" 
							  name="cancel" value="Cancel" /></td>
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
							$this->source_of_info=$source_of_info;
							
							// server side validation
							$return =true;
							if($this->Form->ValidField($source_of_info,'empty','Source of Info. is empty')==false)
								$return =false;
							
								
							if($return){
							
							$update_array = array();
							$update_array['source_of_info'] = $this->source_of_info;
							
							$this->db->update(TBL_SOURCE_OF_INFO,$update_array,'source_of_info_id',$this->source_id);
							$_SESSION['msg']='Source of Info. has been Updated successfully';
							?>
							<script type="text/javascript">
								window.location = "source_of_info.php"
							</script>
							<?php
							exit();
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->editSource('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	}
	
	function deleteSource($source_id)
	{
	
		$sql="delete from ".TBL_SOURCE_OF_INFO." where source_of_info_id='".$source_id."'";
		$this->db->query($sql,__FILE__,__LINE__);
		?>
		<script language="javascript">window.location='source_of_info.php'</script>
		<?php 
	
	}
	
	
	function addCourse($runat)
	{
	
		switch($runat){
			case 'local' :
							if(count($_POST)>0 and $_POST['submit']=='Submit'){
								extract($_POST);
								$this->course_name = $course_name;
								$this->type = $type;
								
							}
						$FormName = "frm_add_course_name";
						$ControlNames=array("course_name"=>array('course_name',"''","Please enter Course","span_course_name"),
											"type"=>array('type',"''","Please enter type","span_type")
						);

						$ValidationFunctionName="CheckAddcourse_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn">
							<div class="header">
								<span>Add New Course</span>
								
							<div class="switch" style="width:300px">
									<table width="300px" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td><a href="courses.php">
												<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:150px"/>			
												</a>
											</td>
											
											<td>
												<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:150px" />
												
											</td>
										</tr>
									</tbody>
									</table>
								</div>	
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table class="data">
						  <tr><td colspan="2">&nbsp;</td></tr>
						 <tr>
						 	 <th>Course: </th>
						 	 <td><input type="text" name="course_name" id="course_name" value="<?php echo $this->course_name;?>"  />
							 	<span id="span_course_name"></span></td>
						 </tr>	
						 
						 <tr>
						 	 <th>Type: </th>
						 	 <td><select name="type" id="type">
								 <option value="">-Select-</option>
								 <option value="CC">CC</option>
								 <option value="DZ">DZ</option>
								 </select>
							 	<span id="span_type"></span></td>
						 </tr> 
						 
						  <tr>
						  	  <th colspan="2"><input type="submit" name="submit" value="Submit" 
							  onclick="return <?php echo $ValidationFunctionName;?>()">
							  &nbsp;
							   <input type="button" onclick="javascript: history.go(-1); return false" 
							  name="cancel" value="Cancel" />
							  </th>
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
							$this->course_name=$course_name;
							$this->type=$type;
							
							// server side validation
							$return =true;
							if($this->Form->ValidField($course_name,'empty','Course is empty')==false)
								$return =false;
							
								
							if($return){
							
							$insert_sql_array = array();
							$insert_sql_array['course_name'] = $this->course_name;
							$insert_sql_array['type'] = $this->type;
						
							
							$this->db->insert(TBL_COURSE,$insert_sql_array);
							
							$course_idd = $this->db->last_insert_id();
							
							$_SESSION['msg']='Course has been added successfully<br />
							Now you can add Branches to this course';
							?>
							<script type="text/javascript">
								window.location = "courses.php?index=editCourse&course_id=<?php echo $course_idd;?>"
							</script>
							<?php
							exit();
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->addCourse('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	
	}

	
	function showAllCourses()
	{
		
		$sql="select * from ".TBL_COURSE;
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=1;
		?>
		<div class="onecolumn">
				<div class="header">
					<span>Courses</span>
					
					<div class="switch" style="width:300px">
						<table width="300px" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:150px"/>
								</td>
								
								<td><a href="courses.php?index=addCourse">
									<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:150px" />
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
			<table class="data" width="100%" cellpadding="0" cellspacing="0">  
			<thead>
			<tr>
				<th width="6%">S.No.</th>
				<th width="80%">Course</th>
				<th width="14%">Action</th>
			</tr>
			</thead>
			<tbody>
				<?php 		
				while($row= $this->db->fetch_array($result))
				{
					
				?>
				<tr>
					<td><?php echo $x;?></td>
					<td>
					<div class="onecolumn">
						<div class="header">
							<span title="Click to Open and view Branches" class="help"><?php echo $row['course_name'];?> &nbsp;&nbsp;(<?php echo $row['type'];?>)</span>
						</div>	
						<br class="clear"/>
						<div class="content" style="display:none;">
						<?php 
						$sql_brach="select * from ".TBL_BRANCH." where course_id='".$row['course_id']."'";
						$result_brach= $this->db->query($sql_brach,__FILE__,__LINE__);
						?><table class="data" width="100%">
						<tr><th width="79%">Branch</th>
						<th width="21%">Action</th>
						</tr>
						<?php 
						while($row_brach= $this->db->fetch_array($result_brach))
						{						
						?>
						<tr>
							<td><div class="onecolumn">
							<div class="header">
								<span title="Click to open Sub Branches" class="help">
								<?php echo $row_brach['branch_name'];?></span>
							</div>	
							<br class="clear"/>
						<div class="content" style="display:none;">
						
						
						<?php 
						
						$sql_subbrach="select * from ".TBL_SUB_BRANCH." where branch_id='".$row_brach['branch_id']."'";
						$result_subbrach= $this->db->query($sql_subbrach,__FILE__,__LINE__);
						?><table class="data" width="100%">
						<tr><th width="79%">Sub Branch</th>
						<th width="21%">Action</th>
						</tr>
						<?php 
						while($row_subbrach= $this->db->fetch_array($result_subbrach))
						{						
						?>
						<tr>
						<td><?php echo $row_subbrach['sub_branch_name'];?></td>
						
						<td><a class="ajaxpopup" href="#popupdiv" title="Edit Sub-Branch" 
 onclick="javascript: managemaster.editSubBatch('<?php echo $row_subbrach['sub_branch_id'];?>',{ target:'popupdiv'
						} ); return false;"><img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a href="javascript: void(0);" title="Delete Sub-Branch" class="help" 
					onclick="javascript: if(confirm('Do u want to delete this Sub Branch?')) { managemaster.deleteSubBatch('<?php echo $row_subbrach['sub_branch_id'];?>','<?php echo $this->course_id;?>',{}) }; return false;" >
					<img src="images/icon_delete.png" width="15px" height="15px" /></a> 
				   </td></tr>
						<?php }?>
						</table>
						</div></div></td>
					
							<td><a class="ajaxpopup" href="#popupdiv" title="Edit Branch" 
 onclick="javascript: managemaster.editBatch('<?php echo $row_brach['branch_id'];?>','main',{ target:'popupdiv'
						} ); return false;"><img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a href="javascript: void(0);" title="Delete Branch" class="help"
					onclick="javascript: if(confirm('Do you want to delete this Branch?')) { managemaster.deleteBatch('<?php echo $row_brach['branch_id'];?>','',{}) }; return false;" >
					<img src="images/icon_delete.png" width="15px" height="15px" /></a>  | 
					<a class="ajaxpopup" href="#popupdiv" title="Add Sub-branch"   
					 onclick="javascript: managemaster.addSubBranch('<?php echo $row_brach['branch_id'];?>','<?php echo $row['course_id'];?>','main',{ target:'popupdiv'
											} ); return false;"><img src="images/icon_add.png" width="15px" height="15px" /></a> </td></tr>
						<?php }?>
						</table>
						</div>
						</div>
						
					</td>
					<td> <a href="courses.php?index=editCourse&course_id=<?php echo $row['course_id'];?>" 
					title="Edit Course" class="help">
					<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a class="ajaxpopup" href="#popupdiv" title="Add Branch"   
					 onclick="javascript: managemaster.addBranch('<?php echo $row['course_id'];?>','main',{ target:'popupdiv'
											} ); return false;"><img src="images/icon_add.png" width="15px" height="15px" /></a>
											| 
					<?php if($row['status'] != 'block') {?>
					<a href="javascript: void(0);" title="Block Course" class="help" 
					onclick="javascript: if(confirm('Do u want to Block this Course?')) { managemaster.blockCourse('<?php echo $row['course_id'];?>',{}) }; return false;" >
					<img src="images/icon_block.png" width="15px" height="15px" /></a>
					<?php } 
					else
					{?>
					<a href="javascript: void(0);" title="Un-Block Course" class="help" 
					onclick="javascript: if(confirm('Do u want to Un-Block this Course?')) { managemaster.unblockCourse('<?php echo $row['course_id'];?>',{}) }; return false;" >
					<img src="images/icon_unblock.png" width="15px" height="15px" /></a>
					<?php }	?>
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
	
	function editCourse($runat,$course_id)
	{
		$this->course_id=$course_id;
		switch($runat){
			case 'local' :
						
						$FormName = "frm_edit_course_name";
						$ControlNames=array("course_name"=>array('course_name',"''","Please enter Course","span_course_name")
						);

						$ValidationFunctionName="Checkeditcourse_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn">
							<div class="header">
								<span>Edit Course</span>
								
							<div class="switch" style="width:300px">
									<table width="300px" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td><a href="courses.php">
												<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:150px"/>			
												</a>
											</td>
											
											<td><a href="courses.php?index=addCourse">
												<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:150px" />
												</a>
											</td>
										</tr>
									</tbody>
									</table>
								</div>	
							</div>
							<br class="clear"/>
							<div class="content">
						<?php 
							$sql="select * from ".TBL_COURSE." where course_id='".$this->course_id."'";
							$result= $this->db->query($sql,__FILE__,__LINE__);
							$row= $this->db->fetch_array($result)
						
						?>	
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
					<a class="ajaxpopup" href="#popupdiv" title="Add New Branch to this course" 
					 onclick="javascript: managemaster.addBranch('<?php echo $this->course_id;?>',{ target:'popupdiv'
											} ); return false;">Add New Branch</a>
						<table class="data">
						  <tr><td colspan="2">&nbsp;</td></tr>
						 <tr>
						 	 <th>Course: </th>
						 	 <td><input type="text" name="course_name" id="course_name" value="<?php echo $row['course_name']?>"  />
							 	<span id="span_course_name"></span></td>
						 </tr>	
						 
						  <tr>
						 	 <th>Type: </th>
						 	 <td><select name="type" id="type">
								 <option value="">-Select-</option>
								 <option value="CC" <?php if($row['type']=='CC') { echo 'selected="selected"';} ?>>CC</option>
								 <option value="DZ" <?php if($row['type']=='DZ') { echo 'selected="selected"';} ?>>DZ</option>
								 </select>
							 	<span id="span_type"></span></td>
						 </tr> 
						 
						  <tr>
						  	  <th colspan="2"><input type="submit" name="submit" value="Submit" 
							  onclick="return <?php echo $ValidationFunctionName;?>()">
							  &nbsp;
							   <input type="button" onclick="javascript: history.go(-1); return false" 
							  name="cancel" value="Cancel" />
							  </th>
					      </tr>		  
							  
						</table>
						</form>
						<br class="clear"/>
						
						<div class="content">
						
						<h2>Branches</h2>
						<?php 
						$sql_brach="select * from ".TBL_BRANCH." where course_id='".$this->course_id."'";
						$result_brach= $this->db->query($sql_brach,__FILE__,__LINE__);
						?><table class="data" width="100%">
						<tr><th width="79%">Branch</th>
						<th width="21%">Action</th>
						</tr>
						<?php 
						while($row_brach= $this->db->fetch_array($result_brach))
						{						
						?>
						<tr>
						<td>
						
						<div class="onecolumn">
							<div class="header">
								<span title="Click to open Sub Branches" class="help">
								<?php echo $row_brach['branch_name'];?></span>
							</div>	
							<br class="clear"/>
						<div class="content" style="display:none;">
						
						
						<?php 
						
						$sql_subbrach="select * from ".TBL_SUB_BRANCH." where branch_id='".$row_brach['branch_id']."'";
						$result_subbrach= $this->db->query($sql_subbrach,__FILE__,__LINE__);
						?><table class="data" width="100%">
						<tr><th width="79%">Sub Branch</th>
						<th width="21%">Action</th>
						</tr>
						<?php 
						while($row_subbrach= $this->db->fetch_array($result_subbrach))
						{						
						?>
						<tr>
						<td><?php echo $row_subbrach['sub_branch_name'];?></td>
						
						<td><a class="ajaxpopup" href="#popupdiv" title="Edit Branch" 
 onclick="javascript: managemaster.editSubBatch('<?php echo $row_subbrach['sub_branch_id'];?>',{ target:'popupdiv'
						} ); return false;"><img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a href="javascript: void(0);" title="Delete Branch" class="help" 
					onclick="javascript: if(confirm('Do u want to delete this Sub Branch?')) { managemaster.deleteSubBatch('<?php echo $row_subbrach['sub_branch_id'];?>','<?php echo $this->course_id;?>',{}) }; return false;" >
					<img src="images/icon_delete.png" width="15px" height="15px" /></a> 
				   </td></tr>
						<?php }?>
						</table>
						</div></div>
						
						</td>
						
						<td><a class="ajaxpopup" href="#popupdiv" title="Edit Branch" 
 onclick="javascript: managemaster.editBatch('<?php echo $row_brach['branch_id'];?>',{ target:'popupdiv'
						} ); return false;"><img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a href="javascript: void(0);" title="Delete Branch" class="help" 
					onclick="javascript: if(confirm('Do u want to delete this Branch?')) { managemaster.deleteBatch('<?php echo $row_brach['branch_id'];?>','<?php echo $this->course_id;?>',{}) }; return false;" >
					<img src="images/icon_delete.png" width="15px" height="15px" /></a> | 
					<a class="ajaxpopup" href="#popupdiv" title="Add Sub-branch"   
					 onclick="javascript: managemaster.addSubBranch('<?php echo $row_brach['branch_id'];?>','<?php echo $row['course_id'];?>',{ target:'popupdiv'
											} ); return false;"><img src="images/icon_add.png" width="15px" height="15px" /></a>
											</td></tr>
						<?php }?>
						</table>
						</div>					
						</div>
						</div>
						<?php
						break;
			case 'server' :	
							extract($_POST);
							$this->course_name=$course_name;
							$this->type=$type;
							
							// server side validation
							$return =true;
							if($this->Form->ValidField($course_name,'empty','Course is empty')==false)
								$return =false;
							
								
							if($return){
							
							$update_array = array();
							$update_array['course_name'] = $this->course_name;
							$update_array['type'] = $this->type;
							
							$this->db->update(TBL_COURSE,$update_array,'course_id',$this->course_id);
							$_SESSION['msg']='Course has been Updated successfully';
							?>
							<script type="text/javascript">
								window.location = "courses.php?index=editCourse&course_id=<?php echo $this->course_id;?>"
							</script>
							<?php
							exit();
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->editCourse('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	}
	
	function blockCourse($course_id)
	{
		ob_start();
		
		$update_array = array();
		$update_array['status'] = 'block';
		
		$this->db->update(TBL_COURSE,$update_array,'course_id',$course_id);
		
		$_SESSION['msg']='Course has been Blocked successfully';
		
		?>
		<script type="text/javascript">
			window.location = "courses.php"
		</script>
		<?php
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function unblockCourse($course_id)
	{
		ob_start();
		
		$update_array = array();
		$update_array['status'] = '';
		
		$this->db->update(TBL_COURSE,$update_array,'course_id',$course_id);
		
		$_SESSION['msg']='Course has been Un-Blocked successfully';
		
		?>
		<script type="text/javascript">
			window.location = "courses.php"
		</script>
		<?php
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function deleteCourse($course_id)
	{
		ob_start();
		$sql="delete from ".TBL_COURSE." where course_id='".$course_id."'";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql_2="delete from ".TBL_BRANCH." where course_id='".$course_id."'";
		$this->db->query(sql_2,__FILE__,__LINE__);
		
		$sql_3="delete from ".TBL_SUB_BRANCH." where course_id='".$course_id."'";
		$this->db->query($sql_3,__FILE__,__LINE__);
		?>
		<script language="javascript">window.location='courses.php'</script>
		<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}
	
	function addBranch($course_id,$targetfrom='')
	{
	
		ob_start();
						$this->course_id=$course_id;
						$FormName = "frm_add_branch_name";
						$ControlNames=array("branch_name"=>array('branch_name',"''","Please enter Branch","span_branch_name")
						);

						$ValidationFunctionName="CheckAddbranch_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn" style="width:600px">
							<div class="header">
								<span>Add New Branch</span>
								
								
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						 <tr><td colspan="2">&nbsp;</td></tr>
						 
						 <tr>
						 	 <th>Branch Name: </th>
						 	 <td><input type="text" name="branch_name" id="branch_name" value=""  />
							 	<span id="span_branch_name"></span></td>
						 </tr>	
						 
						 <!--<tr>
						 	 <th>Branch Time: </th>
						 	 <td><select name="hours" id="hours">
							  <option value=""></option>
							  <?php
							  for($i=00;$i<=12;$i++)
							  {
							  ?>
							  <option value="<?php echo $i;?>"><?php echo $i;?></option>
							  <?php }?>
							  </select>
							  
							  <select name="mins" id="mins">
							  <option value="0"></option>
							  <?php
							  for($j=00;$j<60;$j++)
							  {
							  ?>
							  <option value="<?php echo $j;?>"><?php echo $j;?></option>
							  <?php }?>
							  </select>
							  
							  <select name="tym" id="tym">
							  <option value="am">am</option>
							  <option value="pm">pm</option>
							  </select>
							  <span id="span_hours"></span></td>
						 </tr>-->
						 
						 <?php if($targetfrom == '')
						 { ?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="adbtn" value="Add" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.addBranchtodb('<?php echo $this->course_id;?>',this.form.branch_name.value,{} ) } return false;">
							  
							  </td>
					      </tr>		  
						<?php } ?>	
						  
						<?php if($targetfrom == 'main')
						 { ?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="adbtn" value="Add" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.addBranchtodb('<?php echo $this->course_id;?>',this.form.branch_name.value,'main',{} ) } return false;">
							  
							  </td>
					      </tr>		  
						<?php } ?>	
						</table>
						</form>
						</div>
						</div>
					
						<br class="clear"/>
						<?php
						
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function addBranchtodb($course_id,$batch_name,$targetfrom='')
	{
		ob_start();
		
		$batch_time = $hours.": ".$mins.": ".$tym;
		
		$insert_sql_array = array();
		$insert_sql_array['course_id'] = $course_id;
		$insert_sql_array['branch_name'] = $batch_name;
		//$insert_sql_array['batch_time'] = $batch_time;
		
		$this->db->insert(TBL_BRANCH,$insert_sql_array);
		$_SESSION['msg']='Branch has been added successfully';
		if($targetfrom == 'main')
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php?index=editCourse&course_id=<?php echo $course_id;?>"
		</script>
		<?php
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
	}
	
	function editBatch($batch_id,$targetfrom='')
	{
	
		ob_start();
						$this->batch_id=$batch_id;
						$FormName = "frm_edit_branch_name";
						$ControlNames=array("branch_name"=>array('branch_name',"''","Please enter Branch","span_branch_name")
						);

						$ValidationFunctionName="Checkedibranch_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						
						$sql_brach="select * from ".TBL_BRANCH." where branch_id='".$this->batch_id."'";
						$result_brach= $this->db->query($sql_brach,__FILE__,__LINE__);
						$row_brach= $this->db->fetch_array($result_brach);
						
						/*$fulltym = array();
						$fulltym = explode(': ',$row_brach['batch_time']);
						
						
						$hours= $fulltym[0];
						$mins= $fulltym[1];
						$tym= $fulltym[2];*/
						?>
						<div class="onecolumn" style="width:600px">
							<div class="header">
								<span>Add New Branch</span>
								
								
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						 <tr><td colspan="2">&nbsp;</td></tr>
						 
						 <tr>
						 	 <th>Branch Name: </th>
						 	 <td><input type="text" name="branch_name" id="branch_name" 
							 value="<?php echo $row_brach['branch_name'];?>"  />
							 	<span id="span_branch_name"></span></td>
						 </tr>	
						 
						<!-- <tr>
						 	 <th>Branch Time: </th>
						 	 <td><select name="hours" id="hours">
							  <option value=""></option>
							  <?php
							  for($i=00;$i<=12;$i++)
							  {
							  ?>
							  <option value="<?php echo $i;?>" <?php if($hours == $i){ echo 'selected="selected"';} ?> >
							  <?php echo $i;?></option>
							  <?php }?>
							  </select>
							  
							  <select name="mins" id="mins">
							  <option value="0"></option>
							  <?php
							  for($j=00;$j<60;$j++)
							  {
							  ?>
							  <option value="<?php echo $j;?>" 
							  <?php if($mins == $j){ echo 'selected="selected"';} ?>  ><?php echo $j;?></option>
							  <?php }?>
							  </select>
							  
							  <select name="tym" id="tym">
							  <option value="am" <?php if($tym == 'am'){ echo 'selected="selected"';} ?> >am</option>
							  <option value="pm" <?php if($tym == 'pm'){ echo 'selected="selected"';} ?> >pm</option>
							  </select>
							  <span id="span_hours"></span></td>
						 </tr>-->
						 
						 <?php if($targetfrom == '')
						 {?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="upbtn" value="Update" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.UpdateBranchtodb('<?php echo $this->batch_id;?>',this.form.branch_name.value,'<?php echo $row_brach['course_id'];?>',{} ) } return false;">
							  </td>
					      </tr>	
						  <?php }?>	 
						  
						  <?php if($targetfrom == 'main')
						  {?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="upbtn" value="Update" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.UpdateBranchtodb('<?php echo $this->batch_id;?>',this.form.branch_name.value,'',{} ) } return false;">
							  </td>
					      </tr>	
						  <?php }?>	  
							  
						</table>
						</form>
						</div>
						</div>
					
						<br class="clear"/>
						<?php
						
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function UpdateBranchtodb($batch_id,$batch_name,$course_id='')
	{
		ob_start();
		
		//$batch_time = $hours.": ".$mins.": ".$tym;
		
		$update_array = array();
		$update_array['branch_name'] = $batch_name;
		
		$this->db->update(TBL_BRANCH,$update_array,'branch_id',$batch_id);
		$_SESSION['msg']='Branch has been Updated successfully';
		if($course_id=='')
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php?index=editCourse&course_id=<?php echo $course_id;?>"
		</script>
		<?php
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function deleteBatch($branch_id,$course_id)
	{
		ob_start();
		
		$delsql="delete from ".TBL_BRANCH." where branch_id='".$branch_id."'";
		$this->db->query($delsql,__FILE__,__LINE__);
		
		$delsql="delete from ".TBL_SUB_BRANCH." where branch_id='".$branch_id."'";
		$this->db->query($delsql,__FILE__,__LINE__);
		
		$_SESSION['msg']='Branch has been Deleted successfully';
		if($course_id=='')
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php?index=editCourse&course_id=<?php echo $course_id;?>"
		</script>
		<?php
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function addSubBranch($branch_id,$course_id,$targetfrom='')
	{
	
		ob_start();
						$this->branch_id=$branch_id;
						$this->course_id=$course_id;
						$FormName = "frm_add_sub_branch_name";
						$ControlNames=array("sub_branch_name"=>array('sub_branch_name',"''","Please enter Sub-Branch","span_sub_branch_name")
						);

						$ValidationFunctionName="CheckAddbranch_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						?>
						<div class="onecolumn" style="width:600px">
							<div class="header">
								<span>Add New Sub-Branch</span>
								
								
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						 <tr><td colspan="2">&nbsp;</td></tr>
						 
						 <tr>
						 	 <th>Sub-Branch Name: </th>
						 	 <td><input type="text" name="sub_branch_name" id="sub_branch_name" value=""  />
							 	<span id="span_sub_branch_name"></span></td>
						 </tr>	
												 
						 <?php if($targetfrom == '')
						 { ?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="adbtn" value="Add" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.addSubBranchtodb('<?php echo $this->branch_id;?>','<?php echo $this->course_id;?>',this.form.sub_branch_name.value,{} ) } return false;">
							  
							  </td>
					      </tr>		  
						<?php } ?>	
						  
						<?php if($targetfrom == 'main')
						 { ?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="adbtn" value="Add" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.addSubBranchtodb('<?php echo $this->branch_id;?>','<?php echo $this->course_id;?>',this.form.sub_branch_name.value,'main',{} ) } return false;">
							  
							  </td>
					      </tr>		  
						<?php } ?>	
						</table>
						</form>
						</div>
						</div>
					
						<br class="clear"/>
						<?php
						
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
		function addSubBranchtodb($branch_id,$course_id,$sub_branch_name,$targetfrom='')
	{
		ob_start();
		
		$batch_time = $hours.": ".$mins.": ".$tym;
		
		$insert_sql_array = array();
		$insert_sql_array['course_id'] = $course_id;
		$insert_sql_array['branch_id'] = $branch_id;
		$insert_sql_array['sub_branch_name'] = $sub_branch_name;
		
		$this->db->insert(TBL_SUB_BRANCH,$insert_sql_array);
		$_SESSION['msg']='Sub-Branch has been added successfully';
		if($targetfrom == 'main')
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php?index=editCourse&course_id=<?php echo $course_id;?>"
		</script>
		<?php
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
	}
	
	
	function editSubBatch($sub_batch_id,$targetfrom='')
	{
	
		ob_start();
						$this->sub_batch_id=$sub_batch_id;
						$FormName = "frm_edit_sub_branch_name";
						$ControlNames=array("sub_branch_name"=>array('sub_branch_name',"''","Please enter Sub-Branch","span_sub_branch_name")
						);

						$ValidationFunctionName="CheckediSubbranch_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						
						$sql_subbrach="select * from ".TBL_SUB_BRANCH." where sub_branch_id='".$this->sub_batch_id."'";
						$result_subbrach= $this->db->query($sql_subbrach,__FILE__,__LINE__);
						$row_subbrach= $this->db->fetch_array($result_subbrach);
						
						/*$fulltym = array();
						$fulltym = explode(': ',$row_brach['batch_time']);
						
						
						$hours= $fulltym[0];
						$mins= $fulltym[1];
						$tym= $fulltym[2];*/
						?>
						<div class="onecolumn" style="width:600px">
							<div class="header">
								<span>Edit Sub-Branch</span>
								
								
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						 <tr><td colspan="2">&nbsp;</td></tr>
						 
						 <tr>
						 	 <th>Sub-Branch Name: </th>
						 	 <td><input type="text" name="sub_branch_name" id="sub_branch_name" 
							 value="<?php echo $row_subbrach['sub_branch_name'];?>"  />
							 	<span id="span_sub_branch_name"></span></td>
						 </tr>	
						 
						 <?php if($targetfrom == '')
						 {?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="upbtn" value="Update" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.UpdateSubBranchtodb('<?php echo $this->sub_batch_id;?>',this.form.sub_branch_name.value,'<?php echo $row_subbrach['course_id'];?>',{} ) } return false;">
							  </td>
					      </tr>	
						  <?php }?>	 
						  
						  <?php if($targetfrom == 'main')
						  {?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="upbtn" value="Update" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.UpdateSubBranchtodb('<?php echo $this->sub_batch_id;?>',this.form.sub_branch_name.value,'',{} ) } return false;">
							  </td>
					      </tr>	
						  <?php }?>	  
							  
						</table>
						</form>
						</div>
						</div>
					
						<br class="clear"/>
						<?php
						
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function UpdateSubBranchtodb($sub_batch_id,$sub_batch_name,$course_id='')
	{
		ob_start();
		
		$update_array = array();
		$update_array['sub_branch_name'] = $sub_batch_name;
		
		$this->db->update(TBL_SUB_BRANCH,$update_array,'sub_branch_id',$sub_batch_id);
		$_SESSION['msg']='Sub-Branch has been Updated successfully';
		if($course_id=='')
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php?index=editCourse&course_id=<?php echo $course_id;?>"
		</script>
		<?php
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function deleteSubBatch($sub_branch_id,$course_id)
	{
		ob_start();
		
		$delsql="delete from ".TBL_SUB_BRANCH." where sub_branch_id='".$sub_branch_id."'";
		$this->db->query($delsql,__FILE__,__LINE__);
		
		$_SESSION['msg']='Sub-Branch has been Deleted successfully';
		if($course_id=='')
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "courses.php?index=editCourse&course_id=<?php echo $course_id;?>"
		</script>
		<?php
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function addCollegeNameCity($runat)
	{
	
		switch($runat){
			case 'local' :
							if(count($_POST)>0 and $_POST['submit']=='Submit'){
								extract($_POST);
								$this->course_name = $course_name;
								$this->course_fee = $course_fee;
								
							}
						$FormName = "frm_add_college_name";
						$ControlNames=array("college_name"=>array('college_name',"''","Please enter College Name","span_college_name"),
						"city"=>array('city',"''","Please enter City","span_city")
						);

						$ValidationFunctionName="CheckAddcourse_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn">
							<div class="header">
								<span>Add New College</span>
								
							<div class="switch" style="width:300px">
									<table width="300px" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td><a href="college_info.php">
												<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:150px"/>			
												</a>
											</td>
											
											<td>
												<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:150px" />
												
											</td>
										</tr>
									</tbody>
									</table>
								</div>	
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						  <tr><td colspan="2">&nbsp;</td></tr>
						 <tr>
						 	 <th>College: </th>
						 	 <td><input type="text" name="college_name" id="college_name" value="<?php echo $this->college_name;?>"  />
							 	<span id="span_college_name"></span></td>
						 </tr>	
						 
						 <tr>
						 	 <th>City: </th>
						 	 <td><input type="text" name="city" id="city" value="<?php echo $this->city;?>"  />
							 	<span id="span_city"></span></td>
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
					
						<br class="clear"/>
						<?php
						break;
			case 'server' :	
							extract($_POST);
							$this->college_name=$college_name;
							$this->city=$city;
							
							// server side validation
							$return =true;
							if($this->Form->ValidField($college_name,'empty','College Name is empty')==false)
								$return =false;
							if($this->Form->ValidField($city,'empty','City is empty')==false)
								$return =false;
								
							if($return){
							
							$insert_sql_array = array();
							$insert_sql_array['college_name'] = $this->college_name;
							$insert_sql_array['college_city'] = $this->city;
							
							$this->db->insert(TBL_COLLEGE_DETAILS,$insert_sql_array);
							
							$college_id = $this->db->last_insert_id();
							
							$_SESSION['msg']='College has been added successfully<br />
							Now you can add Branches to this College';
							?>
							<script type="text/javascript">
								window.location = "college_info.php"
							</script>
							<?php
							exit();
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->addCollegeNameCity('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	
	}
	
	function editCollege($college_id)
	{
	
		ob_start();
						$this->college_id=$college_id;
						$FormName = "frm_editcolg_name";
						$ControlNames=array("college_name"=>array('college_name',"''","Please enter College","span_college_name"),
						"college_city"=>array('college_city',"''","Please enter College City","span_college_city")
						);

						$ValidationFunctionName="Checkedicolgbranch_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						
						$sql="select * from ".TBL_COLLEGE_DETAILS." where college_id='".$this->college_id."'";
						$result= $this->db->query($sql,__FILE__,__LINE__);
						$row= $this->db->fetch_array($result);
					
						?>
						<div class="onecolumn" style="width:600px">
							<div class="header">
								<span>Edit College</span>
								
								
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						 <tr><td colspan="2">&nbsp;</td></tr>
						 
						 <tr>
						 	 <th>College: </th>
						 	 <td><input type="text" name="college_name" id="college_name" 
							 value="<?php echo $row['college_name'];?>"  />
							 	<span id="span_college_name"></span></td>
						 </tr>
						 
						 <tr>
						 	 <th>City: </th>
						 	 <td><input type="text" name="college_city" id="college_city" 
							 value="<?php echo $row['college_city'];?>"  />
							 	<span id="span_college_city"></span></td>
						 </tr>		
						 
						 <tr>
						  	  <td colspan="2" align="right"><input type="button" name="upbtn" value="Update" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.UpdateCollegetodb('<?php echo $this->college_id;?>',this.form.college_name.value,this.form.college_city.value,{} ) } return false;">
							  </td>
					      </tr>	
							  
						</table>
						</form>
						</div>
						</div>
					
						<br class="clear"/>
						<?php
						
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function UpdateCollegetodb($college_id,$college_name,$college_city='')
	{
		ob_start();
		
		//$batch_time = $hours.": ".$mins.": ".$tym;
		
		$update_array = array();
		$update_array['college_name'] = $college_name;
		$update_array['college_city'] = $college_city;
		
		$this->db->update(TBL_COLLEGE_DETAILS,$update_array,'college_id',$college_id);
		$_SESSION['msg']='College has been Updated successfully';
		
		?>
		<script type="text/javascript">
			window.location = "college_info.php"
		</script>
		<?php
		
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function deleteCollege($college_id)
	{
		ob_start();
		$this->college_id=$college_id;
		$sql="delete from ".TBL_COLLEGE_DETAILS." where college_id='".$this->college_id."'";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sqlbranch="delete from ".TBL_COLLEGE_BRANCH." where college_id='".$this->college_id."'";
		//$this->db->query(sqlbranch,__FILE__,__LINE__);
		
		$sqlsessions="delete from ".TBL_COLLEGE_SESSIONS." where college_id='".$this->college_id."'";
		//$this->db->query($sqlsessions,__FILE__,__LINE__);
		?>
		<script language="javascript">window.location='college_info.php'</script>
		<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}

	function showAllColleges()
	{
		
		$sql="select * from ".TBL_COLLEGE_DETAILS;
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=1;
		?>
		<div class="onecolumn">
				<div class="header">
					<span>Colleges</span>
					
					<div class="switch" style="width:300px">
						<table width="300px" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:150px"/>
								</td>
								
								<td><a href="college_info.php?index=addCollege">
									<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:150px" />
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
			<table class="data" width="100%" cellpadding="0" cellspacing="0">  
			<thead>
			<tr>
				<th width="6%">S.No.</th>
				<th width="80%">College</th>
				<th width="14%">Action</th>
			</tr>
			</thead>
			<tbody>
				<?php 		
				while($row= $this->db->fetch_array($result))
				{
					
				?>
				<tr>
					<td><?php echo $x;?></td>
					<td>
					<div class="onecolumn">
						<div class="header">
							<span title="Click to Open and view Branches" class="help">
							<?php echo $row['college_name'];?> (<?php echo $row['college_city'];?>)
							</span>
						</div>	
						<br class="clear"/>
						<div class="content" style="display:none;">
						<?php 
						$sql_brach="select * from ".TBL_COLLEGE_BRANCH." where college_id='".$row['college_id']."'";
						$result_brach= $this->db->query($sql_brach,__FILE__,__LINE__);
						?><table class="data" width="100%">
						<tr><th width="79%">Branch</th>
						<th width="21%">Action</th>
						</tr>
						<?php 
						while($row_brach= $this->db->fetch_array($result_brach))
						{						
						?>
						<tr>
							<td><div class="onecolumn">
							<div class="header">
								<span title="Click to open Sub Branches" class="help">
								<?php echo $row_brach['branch_name'];?></span>
							</div>	
							<br class="clear"/>
						<div class="content" style="display:none;">
						
						
						<?php 
						
						$sql_session="select * from ".TBL_COLLEGE_SESSIONS." where college_branch_id='".$row_brach['college_branch_id']."'";
						$result_session= $this->db->query($sql_session,__FILE__,__LINE__);
						?><table class="data" width="100%">
						<tr><th width="79%">Sessions</th>
						<th width="21%">Action</th>
						</tr>
						<?php 
						while($row_session= $this->db->fetch_array($result_session))
						{						
						?>
						<tr>
						<td><?php echo $row_session['session_name'];?></td>
						
						<td><a class="ajaxpopup" href="#popupdiv" title="Edit Session" 
 onclick="javascript: managemaster.editCollegeSessions('<?php echo $row_session['sessions_id'];?>',{ target:'popupdiv'
						} ); return false;"><img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a href="javascript: void(0);" title="Delete Session" class="help" 
					onclick="javascript: if(confirm('Do u want to delete this Session?')) { managemaster.deleteCollegeSessions('<?php echo $row_session['sessions_id'];?>','<?php echo $this->college_id;?>',{}) }; return false;" >
					<img src="images/icon_delete.png" width="15px" height="15px" /></a> 
				   </td></tr>
						<?php }?>
						</table>
						</div></div></td>
					
							<td><a class="ajaxpopup" href="#popupdiv" title="Edit Branch" 
 onclick="javascript: managemaster.editCollegeBatch('<?php echo $row_brach['college_branch_id'];?>','main',{ target:'popupdiv'
						} ); return false;"><img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a href="javascript: void(0);" title="Delete Branch" class="help"
					onclick="javascript: if(confirm('Do you want to delete this Branch?')) { managemaster.deleteCollegeBatch('<?php echo $row_brach['college_branch_id'];?>','',{}) }; return false;" >
					<img src="images/icon_delete.png" width="15px" height="15px" /></a>  | 
					<a class="ajaxpopup" href="#popupdiv" title="Add Session"   
					 onclick="javascript: managemaster.addCollegeSessions('<?php echo $row_brach['college_branch_id'];?>','<?php echo $row['college_id'];?>','main',{ target:'popupdiv'
											} ); return false;"><img src="images/icon_add.png" width="15px" height="15px" /></a> </td></tr>
						<?php }?>
						</table>
						</div>
						</div>
						
					</td>
					<td> <a class="ajaxpopup" href="#popupdiv" title="Edit College"   
					 onclick="javascript: managemaster.editCollege('<?php echo $row['college_id'];?>',{ target:'popupdiv'
											} ); return false;">
					<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a href="javascript: void(0);" title="Delete College" class="help" 
					onclick="javascript: if(confirm('Do you want to delete this Branch?')) 
					{ managemaster.deleteCollege('<?php echo $row['college_id'];?>',{}) };
					 return false;" >
					<img src="images/icon_delete.png" width="15px" height="15px" /></a> |
					<a class="ajaxpopup" href="#popupdiv" title="Add Branch"   
					 onclick="javascript: managemaster.addCollegeBranch('<?php echo $row['college_id'];?>','main',{ target:'popupdiv'
											} ); return false;"><img src="images/icon_add.png" width="15px" height="15px" /></a>
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
	
	
	function addCollegeBranch($college_id,$targetfrom='')
	{
	
		ob_start();
						$this->college_id=$college_id;
						$FormName = "frm_add_branch_name";
						$ControlNames=array("branch_name"=>array('branch_name',"''","Please enter Branch","span_branch_name")
						);

						$ValidationFunctionName="CheckAddbranch_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn" style="width:600px">
							<div class="header">
								<span>Add New College Branch</span>
								
								
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						 <tr><td colspan="2">&nbsp;</td></tr>
						 
						 <tr>
						 	 <th>Branch Name: </th>
						 	 <td><input type="text" name="branch_name" id="branch_name" value=""  />
							 	<span id="span_branch_name"></span></td>
						 </tr>	
						<?php if($targetfrom == '')
						 { ?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="adbtn" value="Add" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.addCollegeBranchtodb('<?php echo $this->college_id;?>',this.form.branch_name.value,{} ) } return false;">
							  
							  </td>
					      </tr>		  
						<?php } ?>	
						  
						<?php if($targetfrom == 'main')
						 { ?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="adbtn" value="Add" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.addCollegeBranchtodb('<?php echo $this->college_id;?>',this.form.branch_name.value,'main',{} ) } return false;">
							  
							  </td>
					      </tr>		  
						<?php } ?>	
						</table>
						</form>
						</div>
						</div>
					
						<br class="clear"/>
						<?php
						
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function addCollegeBranchtodb($college_id,$batch_name,$targetfrom='')
	{
		ob_start();
		
		$batch_time = $hours.": ".$mins.": ".$tym;
		
		$insert_sql_array = array();
		$insert_sql_array['college_id'] = $college_id;
		$insert_sql_array['branch_name'] = $batch_name;
		//$insert_sql_array['batch_time'] = $batch_time;
		
		$this->db->insert(TBL_COLLEGE_BRANCH,$insert_sql_array);
		$_SESSION['msg']='College Branch has been added successfully';
		if($targetfrom == 'main')
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php?index=editCollege&college_id=<?php echo $college_id;?>"
		</script>
		<?php
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
	}
	
	function editCollegeBatch($college_branch_id,$targetfrom='')
	{
	
		ob_start();
						$this->college_branch_id=$college_branch_id;
						$FormName = "frm_editcolgbranch_name";
						$ControlNames=array("branch_name"=>array('branch_name',"''","Please enter College Branch","span_branch_name")
						);

						$ValidationFunctionName="Checkedicolgbranch_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						
						$sql_brach="select * from ".TBL_COLLEGE_BRANCH." where college_branch_id='".$this->college_branch_id."'";
						$result_brach= $this->db->query($sql_brach,__FILE__,__LINE__);
						$row_brach= $this->db->fetch_array($result_brach);
						
						/*$fulltym = array();
						$fulltym = explode(': ',$row_brach['batch_time']);
						
						
						$hours= $fulltym[0];
						$mins= $fulltym[1];
						$tym= $fulltym[2];*/
						?>
						<div class="onecolumn" style="width:600px">
							<div class="header">
								<span>Edit College Branch</span>
								
								
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						 <tr><td colspan="2">&nbsp;</td></tr>
						 
						 <tr>
						 	 <th>Branch Name: </th>
						 	 <td><input type="text" name="branch_name" id="branch_name" 
							 value="<?php echo $row_brach['branch_name'];?>"  />
							 	<span id="span_branch_name"></span></td>
						 </tr>	
						 
						<!-- <tr>
						 	 <th>Branch Time: </th>
						 	 <td><select name="hours" id="hours">
							  <option value=""></option>
							  <?php
							  for($i=00;$i<=12;$i++)
							  {
							  ?>
							  <option value="<?php echo $i;?>" <?php if($hours == $i){ echo 'selected="selected"';} ?> >
							  <?php echo $i;?></option>
							  <?php }?>
							  </select>
							  
							  <select name="mins" id="mins">
							  <option value="0"></option>
							  <?php
							  for($j=00;$j<60;$j++)
							  {
							  ?>
							  <option value="<?php echo $j;?>" 
							  <?php if($mins == $j){ echo 'selected="selected"';} ?>  ><?php echo $j;?></option>
							  <?php }?>
							  </select>
							  
							  <select name="tym" id="tym">
							  <option value="am" <?php if($tym == 'am'){ echo 'selected="selected"';} ?> >am</option>
							  <option value="pm" <?php if($tym == 'pm'){ echo 'selected="selected"';} ?> >pm</option>
							  </select>
							  <span id="span_hours"></span></td>
						 </tr>-->
						 
						 <?php if($targetfrom == '')
						 {?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="upbtn" value="Update" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.UpdateCollegeBranchtodb('<?php echo $this->college_branch_id;?>',this.form.branch_name.value,'<?php echo $row_brach['college_id'];?>',{} ) } return false;">
							  </td>
					      </tr>	
						  <?php }?>	 
						  
						  <?php if($targetfrom == 'main')
						  {?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="upbtn" value="Update" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.UpdateCollegeBranchtodb('<?php echo $this->college_branch_id;?>',this.form.branch_name.value,'',{} ) } return false;">
							  </td>
					      </tr>	
						  <?php }?>	  
							  
						</table>
						</form>
						</div>
						</div>
					
						<br class="clear"/>
						<?php
						
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function UpdateCollegeBranchtodb($college_branch_id,$batch_name,$college_id='')
	{
		ob_start();
		
		//$batch_time = $hours.": ".$mins.": ".$tym;
		
		$update_array = array();
		$update_array['branch_name'] = $batch_name;
		
		$this->db->update(TBL_COLLEGE_BRANCH,$update_array,'college_branch_id',$college_branch_id);
		$_SESSION['msg']='Branch has been Updated successfully';
		if($course_id=='')
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php?index=editCollege&college_id=<?php echo $college_id;?>"
		</script>
		<?php
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function deleteCollegeBatch($branch_id,$college_id)
	{
		ob_start();
		
		$delsql="delete from ".TBL_COLLEGE_BRANCH." where college_branch_id='".$branch_id."'";
		$this->db->query($delsql,__FILE__,__LINE__);
		
		$delsql="delete from ".TBL_COLLEGE_SESSIONS." where college_branch_id='".$branch_id."'";
		//$this->db->query($delsql,__FILE__,__LINE__);
		
		$_SESSION['msg']='College Branch has been Deleted successfully';
		if($course_id=='')
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php?index=editCollege&college_id=<?php echo $college_id;?>"
		</script>
		<?php
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function addCollegeSessions($college_branch_id,$college_id,$targetfrom='')
	{
	
		ob_start();
						$this->college_branch_id=$college_branch_id;
						$this->college_id=$college_id;
						$FormName = "frm_add_sub_branch_name";
						$ControlNames=array("session_name"=>array('session_name',"''","Please enter Session","span_session_name")
						);

						$ValidationFunctionName="CheckAddbranch_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						?>
						<div class="onecolumn" style="width:600px">
							<div class="header">
								<span>Add New Session</span>
								
								
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						 <tr><td colspan="2">&nbsp;</td></tr>
						 
						 <tr>
						 	 <th>Session Name: </th>
						 	 <td><input type="text" name="session_name" id="session_name" value=""  />
							 	<span id="span_session_name"></span></td>
						 </tr>	
												 
						 <?php if($targetfrom == '')
						 { ?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="adbtn" value="Add" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.addCollegeSessionstodb('<?php echo $this->college_branch_id;?>','<?php echo $this->college_id;?>',this.form.session_name.value,{} ) } return false;">
							  
							  </td>
					      </tr>		  
						<?php } ?>	
						  
						<?php if($targetfrom == 'main')
						 { ?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="adbtn" value="Add" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.addCollegeSessionstodb('<?php echo $this->college_branch_id;?>','<?php echo $this->college_id;?>',this.form.session_name.value,'main',{} ) } return false;">
							  
							  </td>
					      </tr>		  
						<?php } ?>	
						</table>
						</form>
						</div>
						</div>
					
						<br class="clear"/>
						<?php
						
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
		function addCollegeSessionstodb($college_branch_id,$college_id,$session_name,$targetfrom='')
	{
		ob_start();
		
		//$batch_time = $hours.": ".$mins.": ".$tym;
		
		$insert_sql_array = array();
		$insert_sql_array['college_id'] = $college_id;
		$insert_sql_array['college_branch_id'] = $college_branch_id;
		$insert_sql_array['session_name'] = $session_name;
		
		$this->db->insert(TBL_COLLEGE_SESSIONS,$insert_sql_array);
		$_SESSION['msg']='Session has been added successfully';
		if($targetfrom == 'main')
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php?index=editCollege&college_id=<?php echo $college_id;?>"
		</script>
		<?php
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
	}
	
	
	function editCollegeSessions($sessions_id,$targetfrom='')
	{
	
		ob_start();
						$this->sessions_id=$sessions_id;
						$FormName = "frm_edit_sub_branch_name";
						$ControlNames=array("session_name"=>array('session_name',"''","Please enter Session","span_session_name")
						);

						$ValidationFunctionName="CheckediSession_nameValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						
						$sql_subbrach="select * from ".TBL_COLLEGE_SESSIONS." where sessions_id='".$this->sessions_id."'";
						$result_subbrach= $this->db->query($sql_subbrach,__FILE__,__LINE__);
						$row_subbrach= $this->db->fetch_array($result_subbrach);
						
						/*$fulltym = array();
						$fulltym = explode(': ',$row_brach['batch_time']);
						
						
						$hours= $fulltym[0];
						$mins= $fulltym[1];
						$tym= $fulltym[2];*/
						?>
						<div class="onecolumn" style="width:600px">
							<div class="header">
								<span>Edit Session</span>
								
								
							</div>
							<br class="clear"/>
							<div class="content">
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						 <tr><td colspan="2">&nbsp;</td></tr>
						 
						 <tr>
						 	 <th>Sub-Branch Name: </th>
						 	 <td><input type="text" name="session_name" id="session_name" 
							 value="<?php echo $row_subbrach['session_name'];?>"  />
							 	<span id="span_session_name"></span></td>
						 </tr>	
						 
						 <?php if($targetfrom == '')
						 {?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="upbtn" value="Update" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.UpdateCollegeSessionstodb('<?php echo $this->sessions_id;?>',this.form.session_name.value,'<?php echo $row_subbrach['college_id'];?>',{} ) } return false;">
							  </td>
					      </tr>	
						  <?php }?>	 
						  
						  <?php if($targetfrom == 'main')
						  {?>
						  <tr>
						  	  <td colspan="2" align="right"><input type="button" name="upbtn" value="Update" 
							  onclick="javascript:  if(<?php echo $ValidationFunctionName;?>()) 
							  {  managemaster.UpdateCollegeSessionstodb('<?php echo $this->sessions_id;?>',this.form.session_name.value,'',{} ) } return false;">
							  </td>
					      </tr>	
						  <?php }?>	  
							  
						</table>
						</form>
						</div>
						</div>
					
						<br class="clear"/>
						<?php
						
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function UpdateCollegeSessionstodb($sessions_id,$session_name,$college_id='')
	{
		ob_start();
		
		$update_array = array();
		$update_array['session_name'] = $session_name;
		
		$this->db->update(TBL_COLLEGE_SESSIONS,$update_array,'sessions_id',$sessions_id);
		$_SESSION['msg']='Session has been Updated successfully';
		if($course_id=='')
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php?index=editCollege&college_id=<?php echo $college_id;?>"
		</script>
		<?php
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function deleteCollegeSessions($sessions_id,$college_id)
	{
		ob_start();
		
		$delsql="delete from ".TBL_COLLEGE_SESSIONS." where sessions_id='".$sessions_id."'";
		$this->db->query($delsql,__FILE__,__LINE__);
		
		$_SESSION['msg']='Session has been Deleted successfully';
		if($course_id=='')
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php"
		</script>
		<?php
		}
		else
		{
		?>
		<script type="text/javascript">
			window.location = "college_info.php?index=editCollege&college_id=<?php echo $college_id;?>"
		</script>
		<?php
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function addEmail($runat)
	{
		switch($runat){
			case 'local' :
							if(count($_POST)>0 and $_POST['submit']=='Submit'){
								extract($_POST);
								$this->email = $email;
								
							}
						$FormName = "frm_add_email";
						$ControlNames=array("email"=>array('email',"EMail","Please enter Email","span_email")
						);

						$ValidationFunctionName="CheckemailValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn">
							<div class="header">
								<span>Add New Email</span>
								
							<div class="switch" style="width:300px">
									<table width="300px" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td><a href="fees_email.php">
												<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:150px"/>			
												</a>
											</td>
											
											<td><a href="fees_email.php?index=addEmail">
												<input type="button" id="chart_line" name="chart_line" class="right_switch active" value="Add New" style="width:150px" />
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
						
						<table>
						  <tr><td colspan="2">&nbsp;</td></tr>
						 <tr>
						 	 <th>Email: </th>
						 	 <td><input type="text" name="email" id="email" value="<?php echo $this->email;?>"  />
							 	<span id="span_email"></span></td>
						 </tr>	 
						 
						  <tr>
						  	  <td colspan="2"><input type="submit" name="submit" value="Submit" 
							  onclick="return <?php echo $ValidationFunctionName;?>()">
							  &nbsp;
							   <input type="button" onclick="javascript: history.go(-1); return false" 
							  name="cancel" value="Cancel" /></td>
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
							$this->email=$email;
							
							// server side validation
							$return =true;
							if($this->Form->ValidField($email,'empty','Email is empty')==false)
								$return =false;
							
								
							if($return){
							
							$insert_sql_array = array();
							$insert_sql_array['email_id'] = $this->email;
							
							$this->db->insert(TBL_EMAIL,$insert_sql_array);
							$_SESSION['msg']='Email has been added successfully';
							?>
							<script type="text/javascript">
								window.location = "fees_email.php"
							</script>
							<?php
							exit();
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->addEmail('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	}
		
	function showAllEmail()
	{
		
		$sql="select * from ".TBL_EMAIL." ";
		$result= $this->db->query($sql,__FILE__,__LINE__);
		$x=1;
		?>
		<div class="onecolumn">
				<div class="header">
					<span>Fees Email</span>
					
				<div class="switch" style="width:300px">
						<table width="300px" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<input type="button" id="chart_bar" name="chart_bar" class="left_switch active" value="View All" style="width:150px"/>
								</td>
								
								<td><a href="fees_email.php?index=addEmail">
									<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:150px" />
									</a>
								</td>
							</tr>
						</tbody>
						</table>
					</div>	
				</div>
				<br class="clear"/>
				<div class="content">
			<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">  
			<thead>
			<tr>
				<th width="8%">S.No.</th>
				<th width="66%">Email</th>
				<th width="26%">Action</th>
			</tr>
			</thead>
			<tbody>
				<?php 		
				while($row= $this->db->fetch_array($result))
				{
					
				?>
				<tr>
					<td><?php echo $x;?></td>
					<td><?php echo $row['email_id'];?></td>
					<td> <a href="fees_email.php?index=editEmail&id=<?php echo $row['id'];?>">
					<img src="images/icon_edit.png" width="15px" height="15px" /></a> | 
					<a href="javascript: void(0);" title="Delete" class="help" 
					onclick="javascript: if(confirm('Do u want to delete this Email?')) 
					{ managemaster.deleteEmail('<?php echo $row['id'];?>',{}) }; return false;" >
					<img src="images/icon_delete.png" width="15px" height="15px" /></a> </td>
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
	
	function deleteEmail($id)
	{
		ob_start();
		$sql="delete from ".TBL_EMAIL." where id='".$id."'";
		$this->db->query($sql,__FILE__,__LINE__);
		?>
		<script language="javascript">window.location='fees_email.php'</script>
		<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function editEmail($runat,$id)
	{
		$this->id=$id;
		switch($runat){
			case 'local' :
						
						$FormName = "frm_edit_email";
						$ControlNames=array("email"=>array('email',"EMail","Please enter Email","span_email")
						);

						$ValidationFunctionName="CheckeditemailValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						<div class="onecolumn">
							<div class="header">
								<span>Edit Email</span>
								
							<div class="switch" style="width:300px">
									<table width="300px" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td><a href="fees_email.php">
												<input type="button" id="chart_bar" name="chart_bar" class="left_switch" value="View All" style="width:150px"/>			
												</a>
											</td>
											
											<td><a href="fees_email.php?index=addEmail">
												<input type="button" id="chart_line" name="chart_line" class="right_switch" value="Add New" style="width:150px" />
												</a>
											</td>
										</tr>
									</tbody>
									</table>
								</div>	
							</div>
							<br class="clear"/>
							<div class="content">
						<?php 
							$sql="select * from ".TBL_EMAIL." where id='".$this->id."'";
							$result= $this->db->query($sql,__FILE__,__LINE__);
							$row= $this->db->fetch_array($result)
						
						?>	
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
						
						<table>
						  <tr><td colspan="2">&nbsp;</td></tr>
						 <tr>
						 	 <th>Email: </th>
						 	 <td><input type="text" name="email" id="email" value="<?php echo $row['email_id']?>"  />
							 	<span id="span_email"></span></td>
						 </tr>	 
						 
						  <tr>
						  	  <td colspan="2"><input type="submit" name="submit" value="Submit" 
							  onclick="return <?php echo $ValidationFunctionName;?>()">
							  &nbsp;
							   <input type="button" onclick="javascript: history.go(-1); return false" 
							  name="cancel" value="Cancel" /></td>
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
							$this->email=$email;
							
							// server side validation
							$return =true;
							if($this->Form->ValidField($email,'empty','Email is empty')==false)
								$return =false;
							
								
							if($return){
							
							$update_array = array();
							$update_array['email_id'] = $this->email;
							
							$this->db->update(TBL_EMAIL,$update_array,'id',$this->id);
							$_SESSION['msg']='Email has been Updated successfully';

							?>
							<script type="text/javascript">
								window.location = "fees_email.php"
							</script>
							<?php
							exit();
							} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->editEmail('local');
							}
							break;
			default 	: 
							echo "Wrong Parameter passed";
							
		}
	}
	
}

?>