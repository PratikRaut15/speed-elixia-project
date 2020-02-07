<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
//$_scripts[] = "../../scripts/trash/prototype.js";
$_scripts[] = "../../scripts/jquery.min.js";

$db = new DatabaseManager();
$type = $_GET['type'];
$team =array();
$SQL = "SELECT unit.uid,unit.teamid,team.name, unit.type_value FROM `unit` 
        INNER JOIN ".DB_PARENT.".team ON unit.teamid = team.teamid WHERE unit.customerno =1 AND unit.type_value=$type ";
$db->executeQuery($SQL);
if($db->get_rowCount()>0){
   
    while($row = $db->get_nextRow()){
        if(!isset($team[$row['teamid']]))
        {
            $team[$row['teamid']][0] =1;
        }
        else{
        $team[$row['teamid']][0] += 1;
        }
        $team[$row['teamid']][1]=$row['name'];
        
        
        
    }
}
$value= Array();
            $category = (int) $type;
            $binarycategory = sprintf("%08s",DecBin($category));
                for($shifter=1;$shifter<=4000;$shifter=$shifter<<1)
        {
                $binaryshifter = sprintf("%08s",DecBin($shifter));
                if($category & $shifter)
            {
             $value[]= $shifter;
            }
        }  
$str="";
if(in_array(0, $value)){
          $str .= "Basic," ; 
        }
        if(in_array(1, $value)){
          $str .= "AC," ; 
        }if(in_array(4, $value)){
          $str .= "Door," ; 
        }if(in_array(2, $value)){
          $str .= "Genset," ; 
        }
        if(in_array(8, $value)){
          $str .= "Single Temperature," ; 
        }
        if(in_array(16, $value)){
          $str .= "Double Temperature," ; 
        }
        if(in_array(32, $value)){
          $str .= "Panic," ; 
        }if(in_array(64, $value)){
          $str .= "Buzzer," ; 
        }
        if(in_array(128, $value)){
          $str .= "Immobilizer," ; 
        }
        if(in_array(1024, $value)){
          $str .= "Fuel Sensor," ; 
        }
         if(in_array(256, $value)){
          $str .= "Two Way Communication," ; 
        }
         if(in_array(512, $value)){
          $str .= "Portable," ; 
        }
       
       $type_display=$str;

include("header.php");
?>
<div class="container">
    <p><h2>Current Allotment Status <?php if(empty($type_display)){echo "Basic";} else{ echo $type_display;}?></h2></p>
             
  <table class="table table-bordered">
      
      <thead>
          <tr>
        <th>Alloted to</th>
        <th>count</th>
    
          </tr>
      </thead>
  
      <tbody>
          <?php
         
          $i =1;
          if(empty($team))
         {
         ?>
        <tr>
            <td><?php echo "no allotment";?></td>
            <td></td>
        </tr>
          <?php
            }else{
            
                foreach($team as $row){
            ?>
        <tr>
            <td><?php echo $row[1];?></td>
            <td><?php echo $row[0];?></td>
        </tr>
        <?php
            }}
            //var_dump($team);
            ?>
      
  </tbody>
  </table>
<br><br>
      <a href ="stock_count.php"><button type="button" class="btn btn-primary">Back</button></a>
</div>
