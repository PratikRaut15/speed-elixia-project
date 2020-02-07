<?php

class geotag{

var $Form;
var $db;
var $tabName;
var $feedbackquesionid;




function __construct(){

$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
$this->validity = new ClsJSFormValidation();
$this->Form = new ValidateForm();
}
function get_location_bylatlong($lat,$long)
{
       
        $sql = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( ".$lat."- `lat` ) * PI( ) /180 /2 ) , 2 ) + ";
		$sql.=" COS( ".$lat." * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( ".$long." - `long` ) * PI( ) /180 /2 ) , 2 ) ) ) ";
		$sql.="  AS distance FROM geotest HAVING distance <10 ORDER BY distance LIMIT 0,1 ";
        		
		$result= $this->db->query($sql,__FILE__,__LINE__);
		
		$location_string=""; 
		    while ($row=$this->db->fetch_array($result))            
            {
                if($row['distance']>1 ){
					$location_string = round($row['distance'], 2)." Km from ".$row['location'].", ".$row['city'].", ".$row['state'];
				}else{
					$location_string= "Near ".$row['location'].", ".$row['city'].", ".$row['state'];
				}
                
            }
            return $location_string;            
        
      
}




}
?>