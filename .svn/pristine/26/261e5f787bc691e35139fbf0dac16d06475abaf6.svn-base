<?php
	/*****************************************************************************
		Database Class for MySQL Server. Please do not change anything
	*****************************************************************************/
	class database {
		protected $Con;
						
		public function __construct($database_host,$database_port,$database_user,$database_password,$database_name) {
			$this->Con = @mysql_connect($database_host.":".$database_port,$database_user,$database_password) or die($this->error(mysql_error(),__FILE__,__LINE__));
			@mysql_select_db($database_name,$this->Con) or die($this->error(mysql_error()));
		}
		
		public function __destruct() {
      		$this->close();
   		}
		
		public function query($sql,$errorFile,$errorLine) {
			$result = @mysql_query($sql,$this->Con) or die($this->error($sql."<br />".mysql_error(),$errorFile,$errorLine));
			return $result;
		}
		
		public function fetch_array($result) {
		
		$row=mysql_fetch_array($result);
		
		
		if(count($row)-1>0)
		{
		foreach($row as $key=>$value)
		$row[$key]=stripslashes($value);
		}
		return $row;
		}
		
		public function fetch_row($result) {
		$row=mysql_fetch_row($result);
		
		if(count($row)-1>0)
		foreach($row as $key=>$value)
		$row[$key]=stripslashes($value);
		
		return $row;
		}
		
		public function insert($table,$DataArray,$printSQL = false) {
			if(count($DataArray) == 0) {
				die($this->error("INSERT INTO statement has not been created",__FILE__,__LINE__));
			}
			foreach($DataArray as $key => $val) {
				$strFields.= "`".$key."`,";
				if($val == "CURDATE()") {
					$strValues.= "CURDATE(),";
				} elseif($val == "CURTIME()") {
					$strValues.= "CURTIME(),";
				} else {
					$strValues.= "'".addslashes($val)."',";	
				}
			}
			$strFields = substr($strFields,0,strlen($strFields)-1);
			$strValues = substr($strValues,0,strlen($strValues)-1);
			$sql = "INSERT INTO `".$table."`(".$strFields.") VALUES(".$strValues.")";
			if($printSQL == true) {
				echo $this->error($sql,__FILE__,__LINE__);
			} else {
				$this->query($sql,__FILE__,__LINE__);
			}
		}

		public function update($table,$DataArray,$updateOnField,$updateOnFieldValue,$printSQL = false) {
			if(count($DataArray) == 0) {
				die($this->error("UPDATE statement has not been created",__FILE__,__LINE__));
			}
			$sql = "UPDATE ".$table." SET ";
			foreach($DataArray as $key => $val) {
				$strFields = "`".$key."`";
				if($val == "CURDATE()") {
					$strValues = "CURDATE()";
				} elseif($val == "CURTIME()") {
					$strValues = "CURTIME()";
				} else {
					$strValues = "'".addslashes($val)."'";	
				}
				$sql.= $strFields."=".$strValues.", ";
			}
			$sql = substr($sql,0,strlen($sql)-2);
			$sql.= " WHERE `".$updateOnField."`='".addslashes($updateOnFieldValue)."'";
			if($printSQL == true) {
				echo $this->error($sql,__FILE__,__LINE__);
			} else {
				$this->query($sql,__FILE__,__LINE__);
			}
		}
		
		public function last_insert_id() {
			return mysql_insert_id($this->Con);
		}
		
		public function result($result,$row,$column) {
			return mysql_result($result,$row,$column);
		}
		
		public function num_rows($result) {
			return mysql_num_rows($result);
		}
		
		public function getDateDiff($coming_date) {
			$diff_sql = "SELECT DATEDIFF('".$coming_date."','".date('Y-m-d')."')";
			$diff_res = $this->query($diff_sql,__FILE__,__LINE__);
			return $this->result($diff_res,0,0);
		}
		
		public function getTimeStampDiff($comming_timestamp)
		{
			$startdate = time();
			$enddate = $comming_timestamp;
			
			$time_period = ( $enddate - $startdate );
			
			$days = 0;
			$hours = 0;
			$minutes = 0;
			$seconds = 0;
			
			$time_increments = array( 'Days' => 86400,
			'Hours' => 3600,
			'Minutes' => 60,
			'Seconds' => 1 );
			
			$time_span = array();
			
			while( list( $key, $value ) = each( $time_increments )) {
			$this_value = (int) ( $time_period / $value );
			$time_period = ( $time_period % $value );
	
			$time_span[$key] = $this_value;
			}
			
			return $time_span;
		}	
		
			
		public function record_number($sql) {
			$result = $this->query($sql,__FILE__,__LINE__);
			$cnt = $this->num_rows($result);
			return $cnt;
		}
		
		public function pagination($sql,$rowsPerPage,$Page) {
		
			$PageResult = $this->record_number($sql);
			if($Page == "" || $Page == 1) {
				$Page = 0;
			} else {
				$Page = ($Page-1) * $rowsPerPage;
			}
			$RecordPerPage = ceil($PageResult/$rowsPerPage);
			$ReturnResult = $this->query($sql." limit ".$Page.",".$rowsPerPage."",__FILE__,__LINE__);
			return $ReturnResult;
		}
		
		public function DisplayAjaxPage($rowsPerPage,$Page,$allCount)
		{
			/**************************************************
			
			** $rowsPerPage = number of recrods per page
			** $Page = Current page no.
			** $allCount = Total No. of Recrods

			*****************************************************/
			if($Page > 1){ ?>
			<a onclick="javascript:contact.GetContact(document.getElementById('search').value, <?php echo $Page-1; ?> ,{target: 'search_result', preloader: 'prl'});" href="#">Previous</a>
			<?PHP }
			
			if($allCount <= $rowsPerPage) $limit = 0;
			elseif(($allCount % $rowsPerPage) == 0) $limit = ($allCount / $rowsPerPage) + 1;
			else $limit = ($allCount / $rowsPerPage) + 1;
			
			if($limit > 10 && $Page > 5){
			if($Page + 4 <= $limit){
			$start = $Page - 5;
			$end = $Page + 4;
			}else{
			$start = $limit - 9;
			$end = $limit;
			}
			}elseif($limit > 10){
			$start = 1;
			$end = 10;
			}else{
			$start = 1;
			$end = $limit;
			}
			
			if($start > 1) echo "...&nbsp;";
			$start = ceil($start);
			$end = ceil($end);
			for($i=$start;$i<$end;$i++){
			if($i != $Page)
			 $ext = ' onclick="javascript:contact.GetContact(document.getElementById(\'search\').value, '.($i).',{target: \'search_result\', preloader: \'prl\'});" href="#" ';
			
			else $ext = ' style="color:#FF0000; text-decoration:none;" ';
			echo '<a' . $ext . '>' . $i . '</a>&nbsp;';
			}
			if($end < ceil($limit)) echo "...";
			if($Page < ($allCount / $rowsPerPage) and $limit>1){ ?>
			<a onclick="javascript:contact.GetContact(document.getElementById('search').value, <?php echo $Page+1; ?> ,{target: 'search_result', preloader: 'prl'});" href="#">Next</a>
			<?PHP } 
				
		}
		
		public function pagination_page_number($sql,$DividedRecordNumber,$Page,$PageName,$QueryString) 
		{
			$PageResult = $this->record_number($sql);
			$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
			if($Page == "") {
				$Page = 1;
			}
			
			$GET_INDEX = $_GET["index"];
			if( $GET_INDEX == 'List' ){ $QueryString = "index=List"; }
				
				
				$str = "<select class=\"txt\" name=\"cmbPage\" id=\"cmbPage\" onchange=\"javascript:_doPagination('".$PageName."','".$QueryString."');\">\n";
				for($i = 1;$i <= $RecordPerPage;$i++) {
					if($Page == $i) {
						$selected = ' selected';
					} else {
						$selected = '';
					}
					$str.= "<option value=\"".$i."\"".$selected.">Page ".$i."</option>\n";
				}
				$str.= "</select>";
				echo $str;
		}
		
		public function pagination_page_number_new($sql,$DividedRecordNumber,$Page,$PageName,$QueryString) {
			$PageResult = $this->record_number($sql);
			$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
			if($Page == "") {
				$Page = 1;
			}
			
				
			$str = "<select class=\"txt\" name=\"cmbPage\" id=\"cmbPage\" onchange=\"javascript:_doPagination('".$PageName."','".$QueryString."');\">\n";
			for($i = 1;$i <= $RecordPerPage;$i++) {
				if($Page == $i) {
					$selected = ' selected';
				} else {
					$selected = '';
				}
				$str.= "<option value=\"".$i."\"".$selected.">Page ".$i."</option>\n";
			}
			$str.= "</select>";
			echo $str;
		}
		
		public function paging($sql,$DividedRecordNumber,$Page,$PageName,$QueryStringName,$Class) {
			$PageResult = $this->record_number($sql);
			if($PageResult > $DividedRecordNumber):
				$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
				//echo $RecordPerPage;
				if($Page == "") {
					$Page = 1;
				}
				$PageCount = $Page - 1;
				if($PageCount > 0) {
					if(empty($QueryStringName)) {
						echo "<a href='".$PageName."?page=".$PageCount."' class='".$Class."'>&lt;&lt; Prev</a>&nbsp;";
					} else {
						echo "<a href='".$PageName."?page=".$PageCount."&".$QueryStringName."' class='".$Class."'>&lt;&lt; Prev</a>&nbsp;&nbsp;";
					}
				} else {
					echo "";
				}
				for($i = 1;$i <= $RecordPerPage;$i++) {
					if($Page == $i) {
						echo "<b>".$i."</b>&nbsp;";
					} else {
						echo "<a href='".$PageName."?page=".$i."&".$QueryStringName."' class='".$Class."'>".$i."</a>&nbsp;";
					}
				}
				$PageCount = $Page + 1;
				if($PageCount < $RecordPerPage + 1) {
					if(empty($QueryStringName)) {
						echo "<a href='".$PageName."?page=".$PageCount."' class='".$Class."'>Next &gt;&gt;</font>";
					} else {
						echo "<a href='".$PageName."?page=".$PageCount."&".$QueryStringName."' class='".$Class."'>Next &gt;&gt;</a>";
					}
				} else {
					echo "";
				}
			else:
				echo "&nbsp;";
			endif;
		}
		
		public function close() {
			@mysql_close($this->Con);
		}		
		
		private function error($arg_error_msg,$arg_file,$arg_line) {
			if(empty($arg_error_msg)==false) {
				$error_msg = "<div style=\"font-family: Tahoma; font-size: 11px; padding: 10px; background-color: #FFD1C4; color: #990000; font-weight: bold; border: 1px solid #FF0000; text-align: center;\">";
				$error_msg.= $arg_error_msg."<br>in file ".$arg_file." at line number ".$arg_line;
				$error_msg.= "</div>";
				return $error_msg;
			}
		}
	}
?>