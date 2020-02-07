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

$SQL = "SELECT * FROM unit";
$db->executeQuery($SQL);
$type = array();
$type_count =array();
$type_status =array();
if($db->get_rowCount()>0){//to count total the value of type 
    
    while($row = $db->get_nextRow()){
        if(!isset($type[$row['type_value']]))
        {
            $type[$row['type_value']] =1;
        }
        else{
        $type[$row['type_value']] += 1;
        }
        
    }
}
//echo "<pre>";print_r($type);

foreach ($type as $key=>$value1)
{
    $str ="";
    //$SQL ="SELECT count(uid) as total FROM unit WHERE type_value = '$value1' ";
      //$db->executeQuery($SQL);
    
       $SQL ="SELECT count(uid) as total_off FROM unit WHERE type_value = '$key' AND customerno =1 AND teamid =0 AND (trans_statusid =1 OR trans_statusid =2 OR trans_statusid =3 OR trans_statusid =4 OR trans_statusid =17 OR trans_statusid =18 OR trans_statusid =20 OR trans_statusid =10)"; 
       $db->executeQuery($SQL);
        if($db->get_rowCount()>0){
         $value= Array();
            $category = (int) $key;
            $binarycategory = sprintf("%08s",DecBin($category));
                for($shifter=1;$shifter<=3000;$shifter=$shifter<<1)
        {
                $binaryshifter = sprintf("%08s",DecBin($shifter));
                if($category & $shifter)
            {
             $value[]= $shifter;
            }
        }  
        
    while($row = $db->get_nextRow()){
        
        
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
        
        
       
       $type_count[]= $value1."-".$row['total_off']."-".$str."-".$key;
        
        
    }
}
    
}
//echo "<br>";
//print_r($type_count);

include("header.php");

?>

<div class="container">
    <p><h2>Current Inventory Status</h2></p>
     
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Types</th>
        <th>In Office(Not Alloted)</th>
        <th>Total(Office+Customer)</th>
      </tr>
    </thead>
    <tbody>
     <?php
     $i =1;
     foreach($type_count as $row){
         
         $list = explode("-", $row);
       
         
         ?>
        <tr>
            <td><?php if($list[2]=="") {echo "Basic";} else { echo $list[2];} ?></td>
            <td><?php echo $list[1];?></td>
            <td><?php echo $list[0];?></td>
            <td><a href="stock_field.php?type=<?php echo $list[3]?>"><button type="button" class="btn btn-info" value="<?php $list[3]?>">Info</button></a></td>
            
        </tr>
         <?php     
     }
     ?>
      
    </tbody>
  </table>
</div>
<?php
include("footer.php");
?>