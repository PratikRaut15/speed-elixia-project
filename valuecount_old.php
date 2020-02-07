<?php
$db = mysql_connect("localhost","UserSpeed","el!365x!@");
if($db){
//echo "test";
mysql_select_db("speed");
}


$sr = Array();
$sql = "select * from unit";
$res = mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_assoc($res)){
$type_value=0;
		if($row['acsensor']==1 || $row['is_ac_opp'] == 1){
		$type_value = $type_value + 1;
		}

		if($row['analog_sen1'] == 1 && $row['analog2_sen']==0 && $row['analog3_sen']==0 && $row['analog4_sen']==0){
		$type_value = $type_value + 8; 
		}

		if($row['analog_sen1'] == 0 && $row['analog2_sen']==1 && $row['analog3_sen']==0 && $row['analog4_sen']==0){
		$type_value = $type_value + 8 ;
		}

		if($row['analog_sen1'] == 0 && $row['analog2_sen']==0 && $row['analog3_sen']==1 && $row['analog4_sen']==0){
		$type_value = $type_value + 8 ;
		}

		if($row['analog_sen1'] == 0 && $row['analog2_sen']==0 && $row['analog3_sen']==0 && $row['analog4_sen']==1){
		$type_value = $type_value + 8 ;
		}

		if($row['analog1_sen'] == 1 && $row['analog2_sen'] == 1 ||
		$row['analog1_sen'] == 1 && $row['analog3_sen'] == 1 ||
		$row['analog1_sen'] == 1 && $row['analog4_sen'] == 1 ||
		$row['analog2_sen'] == 1 && $row['analog3_sen'] == 1 ||
		$row['analog2_sen'] == 1 && $row['analog4_sen'] == 1 ||
		$row['analog3_sen'] == 1 && $row['analog4_sen'] == 1 ){
		       $type_value = $type_value + 16;
		}

		if($row['is_panic']==1){
		$type_value = $type_value + 32;
		}

		if($row['is_buzzer']==1){
		$type_value = $type_value + 64;
		}


		if($row['is_mobiliser']==1){
		$type_value = $type_value + 128;
		}

		$r = new stdClass();
		$r->uid = $row['uid'];
		$r->unitno = $row['unitno'];
		$r->type=$type_value;
		//echo $row['unitno'].'--------'.$type_value.'<br/>';
		$sr[] = $r;

}

//print_r($sr);



foreach($sr as $row){
 echo $sql = "update unit SET type_value = ".$row->type." where uid=".$row->uid." and unitno = ".$row->unitno.""; echo"<br/>";
 $rs = mysql_query($sql);
}



























