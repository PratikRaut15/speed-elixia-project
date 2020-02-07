<?php
 $conn = new mysqli("localhost", "root","", "speed");
 
 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT email_id FROM report_email_list";
$result = $conn->query($sql);
$json=array();
if ($result->num_rows > 0) {
    // output data of each row
    
    while($row = $result->fetch_assoc()) {
        
        $json["value"]=$row["email_id"];
        $data[]=$json;
    }
    
} else {
    echo "0 results";
}
echo json_encode($data);
$conn->close();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

