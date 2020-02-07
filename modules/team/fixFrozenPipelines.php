<?php 
	include "../../lib/system/DatabaseManager.php";
	include_once '../../lib/system/utilities.php';

	echo "<br/> Cron Start On ".date(speedConstants::DEFAULT_TIMESTAMP)." <br/>";
    $db = new DatabaseManager();
    echo "<pre>";

    $today = date('Y-m-d H:i:s');
 	$pdo         = $db->CreatePDOConn();
    $queryCallSP = "SELECT sp.pipelineid,sph.pipeline_history_id,sph.stageid
    				from sales_pipeline sp
    				left join sales_pipeline_history sph ON pipeline_history_id = (select max(pipeline_history_id) from sales_pipeline_history sph1 where sph1.pipelineid = sp.pipelineid )
    				where sp.stageid = 12;";
    $pipelines   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    //print_r($pipelines);
    $data = array();
    $count1 = 0;
    $count2 = 0;
    foreach($pipelines as $pipeline){
    	if($pipeline['stageid']!=12){
	    	$stage = array();
	    	$query = "UPDATE sales_pipeline SET stageid = ".$pipeline['stageid'].",timestamp='".$today."' WHERE pipelineid = ".$pipeline['pipelineid'];
	    	$stage   = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
	    	// print_r($stage);
    		$count1++;
    	}else{
    		//echo "<br>";
    		$query = "SELECT stageid,pipeline_history_id from sales_pipeline_history where pipeline_history_id = (select max(pipeline_history_id) from sales_pipeline_history where pipelineid = ".$pipeline['pipelineid']." and stageid <>12 ) ";
    		$stage   = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
    		// echo "<br>";
    		echo $query = "INSERT INTO `speed`.`sales_pipeline_history`(
			    `pipelineid`,
			    `pipeline_date`,
			    `company_name`,
			    `tepidity`,
			    `sourceid`,
			    `productid`,
			    `industryid`,
			    `modeid`,
			    `teamid`,
			    `location`,
			    `remarks`,
			    `stageid`,
			    `revive_date`,
			    `loss_reason`,
			    `quantity`,
			    `device_cost`,
			    `subscription_cost`,
			    `quotation_request`,
			    `quotation_text`,
			    `quotationDetails`,
			    `create_platform`,
			    `update_platform`,
			    `delete_platform`,
			    `timestamp`,
			    `isdeleted`,
			    `teamid_creator`)
			SELECT `pipelineid`,
			    `pipeline_date`,
			    `company_name`,
			    `tepidity`,
			    `sourceid`,
			    `productid`,
			    `industryid`,
			    `modeid`,
			    `teamid`,
			    `location`,
			    `remarks`,
			    `stageid`,
			    `revive_date`,
			    `loss_reason`,
			    `quantity`,
			    `device_cost`,
			    `subscription_cost`,
			    `quotation_request`,
			    `quotation_text`,
			    `quotationDetails`,
			    `create_platform`,
			    `update_platform`,
			    `delete_platform`,
			    '".$today."',
			    `isdeleted`,
			    `teamid_creator` 
			FROM speed.sales_pipeline_history sp WHERE pipeline_history_id = ".$stage['pipeline_history_id'].";";
			$result   = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
			$query = "UPDATE sales_pipeline SET stageid = ".$stage['stageid']. ",timestamp='".$today."' where pipelineid = ".$pipeline['pipelineid'];
			$result   = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
    		$count2++;
    	}
    }
    //print_r($data);
    echo "count (latest stageid ! = 12) = ".$count1;
    echo "</br>count (latest stageid = 12) = ".$count2;
	echo "<br/> Cron Completed On ".date(speedConstants::DEFAULT_TIMESTAMP)." <br/>";
?>