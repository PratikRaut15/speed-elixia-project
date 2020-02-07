<?php
error_reporting(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');
include_once "session.php";
include_once "loginorelse.php";
include_once "../../constants/constants.php";
include_once "db.php";
include_once "../../lib/components/gui/datagrid.php";
include_once '../../lib/autoload.php';
include_once "../../lib/system/DatabaseManager.php";
include_once "../../lib/system/Sanitise.php";
include '../../lib/bo/simple_html_dom.php';

// -------------------------------------------------------------------- Pull for Allotment ------------------------------------------------------ //
$todaysdate = date('Y-m-d H:i:s'); 

if (isset($_REQUEST["term"])){
    $tm             = new TeamManager();
    $dateObj        = new stdClass();
    $salesuser      = $tm->getSalesUser($_REQUEST['term']);
    $data =array();
 foreach($salesuser as $key=>$val){
      if(isset($val['teamid']) && isset($val["name"])){
         $arr['value'] = $val['teamid']. "-" . $val["name"];
         $arr['name'] = $val['name'];
         $arr['teamid'] = $val['teamid'];
         array_push($data, $arr);
          }
          else{
            $arr = array();
          }
        }
    echo json_encode($data);
}
else if (isset($_REQUEST["dailysalesdata"])){
    $tm             = new TeamManager();
    $dateObj        = new stdClass();
    $htmlData		    = '';

    $startdate  = date('Y-m-d',strtotime($_REQUEST['startdate']));
    $enddate    = date('Y-m-d',strtotime($_REQUEST['enddate']));

    $salesuser      = $tm->getDailySalesReport($_REQUEST['salesuserid'],$startdate,$enddate);
    if(isset($salesuser)){
    	$newarray = array();
    	foreach($salesuser as $key=>$value){
    			$i  = $key+1;
    			$timestamp 	= date('H:i', strtotime($val['timestamp']));
         	$remarks 	= $data['remarks'];
	        if(strlen($remarks)>50){
	            $remarks = substr($data['remarks'],0,50);
	            $remarks .= "...";
	        }
	        $row['srno'] 			= $i;
	        $row['salesperson'] 	= $value['name'];
	        $row['pipelineid'] 	= $value['pipelineid'];
	        $row['companyname'] 	= $value['company_name'];
	        $row['newstage'] 		= $value['newstage'];
	        $row['oldstage'] 		= $value['oldstage'];
	        $row['remarks'] 		= $remarks;
	        $row['productname'] 	= $value['product_name'];
	        $row['tcreator'] 		= $value['tcreator'];
	        $row['timestamp'] 	= $timestamp;
	        $i++;
	       array_push($newarray,$row);
    	}
	}
  echo json_encode($newarray);
    /*
    if(isset($salesuser) && !empty($salesuser)){
 ob_start();
?>
<div class="tabbable" style="overflow-y: scroll;">
        <table class="table  table-bordered table-striped dTableR dataTable">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Sales Person</th>
                    <th>Pipeline Id</th>
                    <th>Comapny Name</th>
                    <th>New Stage</th>
                    <th>Old Stage</th>
                    <th>Remarks</th>
                    <th>Product Name</th>
                    <th>Created By</th>
                    <th>Time</th>
                </tr>
            </thead>  <tr>
<?php
    	foreach($salesuser as $key => $val){
					$i  = $key+1;
					
			$timestamp 	= date('H:i', strtotime($val['timestamp']));
         	$remarks 	= $data['remarks'];
	        if(strlen($remarks)>50){
	            $remarks = substr($data['remarks'],0,50);
	            $remarks .= "...";
	        }?>
	      <td><?php echo $i; ?></td>
	       <td><?php echo $val['name']; ?></td>
	        <td><?php echo $val['pipelineid']; ?></td>
	       <td><?php echo $val['company_name']; ?></td>
	       <td><?php echo $val['newstage']; ?></td>
	       <td><?php echo $val['oldstage']; ?></td>
	       <td><?php echo $remarks; ?></td>
	       <td><?php echo $val['product_name']; ?></td>
	        <td><?php echo $val['tcreator']; ?></td>
	       <td><?php echo $timestamp; ?></td></tr>
  <?php  
		}
    	?></tr>
    	</table></div>
    	<?php
	}
	else{ ?>
		  <p>No Data Found</p>
	<?php }
$variable = ob_get_clean();
   echo json_encode($variable);
  */
}

?>