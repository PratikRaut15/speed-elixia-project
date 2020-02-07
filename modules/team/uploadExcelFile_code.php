<?php
error_reporting(E_ALL ^ E_NOTICE);
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
	$RELATIVE_PATH_DOTS = "../../";
}
date_default_timezone_set("Asia/Calcutta");
require_once $RELATIVE_PATH_DOTS . 'config.inc.php';
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");

include("header.php");

require_once 'SimpleXLSX.php';
// function p($p){
// 	echo "<pre>";
// 	print_r($p);
// 	echo "</pre>";
// 	die;
// }	

// function pr($p){
// 	echo "<pre>";
// 	print_r($p);
// 	echo "</pre>";
	
// }	

function connectDb(){
	$con = mysqli_connect(DB_HOST,DB_LOGIN,DB_PWD,SPEEDDB);	
	if($con){
		return $con;
	}else{
		return false;	
	}	
}	


function RemoveSpecialChapr($var) {
  $string = str_replace("'", '', $var); 
  $regEx="/[^a-zA-Z0-9 -_]/"; 
  $var = preg_replace($regEx, "", $var);
  return $var;
}

function checkforRole($email = '',$Role = ''){
	$con = connectDb();
	if($con){
		if(!empty($email) && $email == 'Zone Manager'){
			$sql = "select email from user where email like '%".$email."%' and isdeleted = '0' and role = '$Role'";
		}elseif(!empty($email) && $email == 'Head Office'){
			$sql = "select email from user where email like '%".$email."%' and isdeleted = '0' and role = '$Role'";
		}
		elseif(!empty($email) && $email == 'Regional Manager'){
			$sql = "select email from user where email like '%".$email."%' and isdeleted = '0' and role = '$Role'";
		}
		elseif(!empty($email) && $email == 'Branch Manager'){
			$sql = "select email from user where email like '%".$email."%' and isdeleted = '0' and role = '$Role'";
		}
		elseif(!empty($email) && $email == 'Head Office'){
			$sql = "select email from user where email like '%".$email."%' and isdeleted = '0' and role = '$Role'";
		}
		else{
			$sql = "select email from user where email like '%".$email."%' and isdeleted = '0'";
		}
		
		$query = mysqli_query($con,$sql);		
		if($query){
			if(mysqli_num_rows($query) > 0){
				$row = mysqli_fetch_array($query);
				if(!empty($row)){
					return $row;
				}else
					return false;
			}
		}else{
			return false;
		}			
	}else{
		return false;
	}	
}


function updateRole($email = '',$reportingManager,$sapcode = ''){
	if(!empty($email) && !empty($reportingManager)){
		$con = connectDb();
		if($con){
			$reportingId = getReportingId($reportingManager,$sapcode);
			
			if(!empty($reportingId)){
				if(empty($sapcode))
					$sql = "update user set heirarchy_id = '$reportingId' where email like '%$email%' and isdeleted = 0 ORDER BY userid DESC ";
				else
					$sql = "update user set heirarchy_id = '$reportingId' where email like '%$email%' and isdeleted = 0 and username = '".$sapcode."' ORDER BY userid DESC ";
				$query = mysqli_query($con,$sql);
				if($query){
					//echo "Role updated for user => ".$email." <br> ";
					return true;
				}
			}else{
				return false;
			}
		}
	}
}

function getReportingId($email = '',$sapcode = ''){
	$con = connectDb();
	if(!empty($email)){
		if(empty($sapcode))
			$sql = "select userid from user where email like '%".$email."%' and isdeleted = 0";
		else
			$sql = "select userid from user where email like '%".$email."%' and isdeleted = 0 and username = '".$sapcode."'";
		$query = mysqli_query($con,$sql);
		if($query){
			if(mysqli_num_rows($query)>0){
				$res = mysqli_fetch_array($query);
				if(!empty($res['userid']))
					return $res['userid'];
				else
					return false;
			}else
				return false;
		}
	}
}
//$data = new Spreadsheet_Excel_Reader("example.xls");

function insertIfnotExist($arrParam = array()){
	if(!empty($arrParam)){
		$con = connectDb();
		if($con){
			$sql = "SELECT userid from user where email like '%".$arrParam['email']."%' and username = '".$arrParam['sapcode']."' and isdeleted = 0 and customerno = '756'";
			$query = mysqli_query($con,$sql);
			if($query){
				if(mysqli_num_rows($query) > 0){
					return false;
				}else{
					###### Insert if Not Exist ###########	
					$sql_insert = "INSERT INTO user(customerno, realname, username, password, role, roleid, email, phone, userkey, heirarchy_id) VALUES 
					('".$arrParam['customerno']."', '".$arrParam['realname']."','".$arrParam['sapcode']."','".$arrParam['password']."','".$arrParam['role']."','".$arrParam['roleid']."','".$arrParam['email']."','".$arrParam['mobilenumber']."',(FLOOR(RAND() * (4294967295 - 100000 + 1)) + 100000), 0)";
					$query_insert = mysqli_query($con,$sql_insert);
					if($query_insert)
						return true;
					###### Insert if Not Exist ###########	
				}	
			}
		}
	}else
		return array();
}

if(isset($_POST['submit']) && isset($_FILES['file']) && isset($_FILES['file']['tmp_name'])){
		if(empty($_FILES['file']['name'])){
            $uri = $_SERVER['REQUEST_URI'];
            $str = str_ireplace("uploadExcelFile_code.php","uploadExcelFile.php",$uri);
            $url = $_SERVER['HTTP_HOST'].$str;
            echo "<script> window.location.href = 'uploadExcelFile.php'; </script>";
            //header("location:uploadExcelFile.php");
        }
			
        //$excelFile = $_FILES['file']['name'];
        
        //$xlsx = SimpleXLSX::parse('userCreateFile2.xlsx');
        //$excelFile = file_get_contents($_FILES['file']['tmp_name']);
        
        $xlsx = SimpleXLSX::parse($_FILES['file']['tmp_name']);
        
        
		if ( $xlsx = SimpleXLSX::parse($_FILES['file']['tmp_name']) ) {
			/* echo "<pre>";
			print_r( $xlsx->rows() ); */
			//p($xlsx->rows());
			$arrUsers = array();
			foreach ( $xlsx->rows() as $r => $row ) {
				$count = count($row);
				if(!empty($arrTemp) && $count == count($arrTemp)){
					break;		
				}	
				foreach ( $row as $c => $cell ) {
					if(!empty($cell))
						$arrTemp[] = $cell;
					}
				
			}
			//unset($xlsx->rows()[0]);
			//p($xlsx->rows());

			if(!empty($arrTemp)){
				foreach ( $xlsx->rows() as $r => $row ) {
						//p($row);
						    if($r == 0)
                               continue;
                            if(empty($row[5]))
                                continue;
                            if(empty($row[6]))
                                continue;
                            if(empty($row[7]))
                                continue;
                            if(empty($row[8]))
                                continue;
                            if(empty($row[9]))
                                continue;
                            if(empty($row[10]))
                                continue;
                            if(empty($row[11]))
                                continue;
                            if(empty($row[12]))
                                continue;
                            if(empty($row[13]))
                                continue;
                            if(empty($row[14]))
                                continue;
                            if(empty($row[15]))
                                continue;
                            if(empty($row[16]))
                                continue;
                            if(empty($row[17]))
                                continue;
                            if(empty($row[18]))
                                continue;
                            if(empty($row[19]))
                                continue;
                            if(empty($row[20]))
                                continue;

                            $arrDat[$arrTemp[5]] =  trim($row[5]);
							$arrDat[$arrTemp[6]] =  trim($row[6]);
							$arrDat[$arrTemp[7]] =  trim($row[7]);
							$arrDat[$arrTemp[8]] =  trim($row[8]);
							$arrDat[$arrTemp[9]] =  trim($row[9]);
							$arrDat[$arrTemp[10]] = trim($row[10]);
							$arrDat[$arrTemp[11]] = trim($row[11]);
							$arrDat[$arrTemp[12]] = trim($row[12]);
							$arrDat[$arrTemp[13]] = trim($row[13]);
							$arrDat[$arrTemp[14]] = trim($row[14]);
							$arrDat[$arrTemp[15]] = trim($row[15]);
							$arrDat[$arrTemp[16]] = trim($row[16]);
							$arrDat[$arrTemp[17]] = trim($row[17]);
							$arrDat[$arrTemp[18]] = trim($row[18]);
							$arrDat[$arrTemp[19]] = trim($row[19]);
							$arrDat[$arrTemp[20]] = trim($row[20]);
							$arrResult[] = $arrDat;
				}
			}
			
			
			if(!empty($arrResult)){
				$con = mysqli_connect("localhost","root","","speed");
				foreach($arrResult as $k => $v){
					$username = $v['username'] = RemoveSpecialChapr($v['Branch User Name']);
					$sapcode = $v['sapcode'] = RemoveSpecialChapr($v['Branch SAP CODE']);
					$mobilenumber = $v['mobilenumber'] = RemoveSpecialChapr($v['Branch Mobile Number']);
					$password = $v['Branch SAP CODE'] = RemoveSpecialChapr($v['Branch SAP CODE']);
					$v['Branch Email Id'] = str_replace("'", "", $v['Branch Email Id']);
					$ReportingManagerEmail = $v['Branch Email Id'] = RemoveSpecialChapr($v['Branch Email Id']);
					$string = str_replace("'", "", $v['Branch Email Id']); // Replaces all spaces with hyphens.
					$email = $v['Branch Email Id'] = RemoveSpecialChapr($string);
					
					$regionalEmail = $v['Regional Email'] = trim(RemoveSpecialChapr($v['Regional Email']));
					$zonalEmail = $v['Zonal Email'] = trim(RemoveSpecialChapr($v['Zonal Email']));
					$hoEmail = $v['HO Email'] = trim(RemoveSpecialChapr($v['HO Email']));
					
					$password = SHA1($password);
					$customerno = '756';
					$arrData = array();
					
					$arrData['email'] = $email;
					$arrData['sapcode'] = $sapcode;
					$arrData['username'] = $username;
					$arrData['customerno'] = $customerno;
					$arrData['mobilenumber'] = $mobilenumber;
					
					########## INSERTING Branch Email ID #########
					if(isset($v['Branch Email Id'])){
						$arrData['role'] = 'Branch Manager';
						$arrData['roleid'] = '55';
						$arrData['password'] = $password;
						$arrData['realname'] = $username;
						$response = insertIfnotExist($arrData);	
						if($response){
						$arrInserted[$arrData['email']] = $arrData;
						}else{
							$arrExistingUser[$arrData['email']] = $arrData;
						}	
					}
					########## INSERTING Branch Email ID #########
					
					
					
					########## INSERTING REGIONAL EMAIL ID #########
					if(isset($v['Regional Email'])){
						$arrData['role'] = 'Regional Manager';
						$arrData['sapcode'] = $regionalSapcode = trim($v['Regional Username']);
						$arrData['roleid'] = '54';
						$arrData['password'] = sha1($arrData['sapcode']);
						$arrData['email'] = $regionalEmail;
						$arrData['realname'] = trim($v['Regional Name']);
						$arrData['mobilenumber'] = trim($v['Regional Mobile Number']);
						$response = insertIfnotExist($arrData);	
						if($response){
						$arrInserted[$arrData['email']] = $arrData;
						}else{
							$arrExistingUser[$arrData['email']] = $arrData;
						}	
					}
					
					########## INSERTING REGIONAL EMAIL ID #########
					
					
					
					
					
					########## INSERTING ZONAL EMAIL ID #########
					if(isset($v['Zonal Email'])){
						$arrData['role'] = 'Zone Manager';
						$arrData['sapcode'] = $zonalSapcode = trim($v['Zonal Username']);
						$arrData['roleid'] = '53';
						$arrData['password'] = sha1($arrData['sapcode']);
						$arrData['email'] = $zonalEmail;
						$arrData['realname'] = trim($v['Zonal Name']);
						$arrData['mobilenumber'] = trim($v['Zonal Mobile Number']);
						$response = insertIfnotExist($arrData);	
						if($response){
						$arrInserted[$arrData['email']] = $arrData;
						}else{
							$arrExistingUser[$arrData['email']] = $arrData;
						}	
					}
					########## INSERTING ZONAL EMAIL ID #########
					
					
					
					########## INSERTING HO EMAIL ID #########
					if(isset($v['HO Email'])){
						$arrData['role'] = 'Head Office';
						$arrData['sapcode'] = $hoSapcode = trim($v['HO SAP CODE']);
						$arrData['roleid'] = '51';
						$arrData['password'] = sha1($arrData['sapcode']);
						$arrData['email'] = $hoEmail;
						$arrData['realname'] = trim($v['HO UserName']);
						$arrData['mobilenumber'] = trim($v['HO Mobile Number']);
						$response = insertIfnotExist($arrData);	
						if($response){
						$arrInserted[$arrData['email']] = $arrData;
						}else{
							$arrExistingUser[$arrData['email']] = $arrData;
						}	
					}
					########## INSERTING HO EMAIL ID #########
							
							
						
						###### loop to update ####### hierarchyId
						
						######## updating Heirarchy starts from here #########
						$RegionalEmail = $v['Regional Email'] = RemoveSpecialChapr($v['Regional Email']);
						$RegionalUserName = $v['Regional Username'] = RemoveSpecialChapr($v['Regional Username']);
						$ZonalEmail = $v['Zonal Email'] = RemoveSpecialChapr($v['Zonal Email']);
						$ZonalEmail = $v['Zonal Email'] = RemoveSpecialChapr($v['Zonal Email']);
						$HOEmail = $v['HO Email'] = RemoveSpecialChapr($v['HO Email']);
						
						if(!empty($RegionalEmail)){
							$isRegionalUpdated = updateRole($email,$RegionalEmail,$regionalSapcode);	
						}
						
						if($isRegionalUpdated){
							if(!empty($ZonalEmail)){
								$isZonalUpdated = updateRole($RegionalEmail,$ZonalEmail,$zonalSapcode);
							}
						}					
						if($isZonalUpdated){
							if(!empty($HOEmail)){
								$isHoUpdated = updateRole($ZonalEmail,$HOEmail,$hoSapcode);
							}
						}
							
						
						
						########## updating Heirarchy Ends here ##########	
						
						###### loop to update ####### hierarchyId
				}
				
			}else{
                
                $uri = $_SERVER['REQUEST_URI'];
                $str = str_ireplace("uploadExcelFile_code.php","uploadExcelFile.php",$uri);
                $url = $_SERVER['HTTP_HOST'].$str;
                echo "<script> alert('send the Proper Formatted Data'); </script>";
                echo "<script> window.location.href = 'uploadExcelFile.php'; </script>";
            }
		} else {
			echo SimpleXLSX::parseError();
		}

}else{

    $uri = $_SERVER['REQUEST_URI'];
    
    $str = str_ireplace("uploadExcelFile_code.php","uploadExcelFile.php",$uri);
    $url = $_SERVER['HTTP_HOST'].$str;
    
    
    echo "<script> window.location.href = 'uploadExcelFile.php'; </script>";

    
    //header("location:uploadExcelFile.php");
}	

?>
<!-- <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!-- <title>Update Users</title> -->

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
		table,th,td{
			text-align:center;
		}
		
	</style>
  <!-- </head> -->
  <!-- <body>
  	 -->
	<div class="row"> 
		<div class="container"> 
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<h3 class="h3"> Created Users </h3>
				
				<table class="table table-bordered table-hover">
				  <th> Sr. No </th> 
				  <th> Email </th> 
				  <th> Name </th> 
				  <th> UserName </th> 
				  <th> role </th> 
				  <th> Mobile Number </th> 
				  <tbody>
				  <?php if(!empty($arrInserted)): ?>
				  <?php $i = 0; foreach($arrInserted as $key => $val): ?>
					<tr> 
                        <td> <center><?php $i++; echo $i;?> </center> </td>
						<td> <center><?php echo $val['email'];?> </center></td>
						<td> <center><?php echo $val['realname'];?> </center></td>
						<td> <center><?php echo $val['username'];?> </center></td>
						<td> <center><?php echo $val['role'];?> </center></td>
						<td> <center><?php echo $val['mobilenumber'];?> </center></td>
					</tr>	
				  <?php endforeach; ?>
				  <?php endif; ?>
				  </tbody>
				  
				</table>
				
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>
	
	<hr>
	
	<div class="row"> 
		<div class="container"> 
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<h3 class="h3"> Existing Users </h3>
				<table class="table table-bordered table-hover">
				  <th> Sr. No </th>  
				  <th> Email </th> 
				  <th> Name </th> 
				  <th> UserName </th> 
				  <th> role </th> 
				  <th> Mobile Number </th> 
				  
				  <?php if(!empty($arrExistingUser)): ?>
				  <?php $i = 0; foreach($arrExistingUser as $key => $val): ?>
					<tr> 
						<td> <center><?php $i++; echo $i;?> </center> </td>
						<td> <center><?php echo $val['email'];?> </center></td>
						<td> <center><?php echo $val['realname'];?> </center></td>
						<td> <center><?php echo $val['username'];?> </center></td>
						<td> <center><?php echo $val['role'];?> </center></td>
						<td> <center><?php echo $val['mobilenumber'];?> </center></td>
					</tr>	
				  <?php endforeach; ?>
				  <?php endif; ?>
				  
				  
				</table>
				
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <!-- </body>
</html> -->